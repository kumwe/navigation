<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$metadata = json_decode(file_get_contents($root . '/composer.json'), true, 512, JSON_THROW_ON_ERROR);
$workspace = sys_get_temp_dir() . '/kumwe-surface-consumer-' . bin2hex(random_bytes(8));
mkdir($workspace, 0700, true);
function consumerCommand(array $args): void
{
    passthru(implode(' ', array_map('escapeshellarg', $args)), $status);
    if ($status !== 0) { throw new RuntimeException('Consumer command failed: ' . $status); }
}
try {
    consumerCommand(['composer', '--working-dir=' . $root, 'archive', '--format=zip', '--dir=' . $workspace, '--file=candidate']);
    $archive = $workspace . '/candidate.zip';
    $zip = new ZipArchive();
    if ($zip->open($archive) !== true) { throw new RuntimeException('Archive unreadable.'); }
    foreach (['composer.json', 'resources/public-api/v1.json', 'examples/standalone.php'] as $required) {
        if ($zip->getFromName($required) === false) { throw new RuntimeException('Missing archive entry: ' . $required); }
    }
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $entry = $zip->getNameIndex($i);
        if (!is_string($entry) || preg_match('~^(?:tests|vendor|tools|\.git|\.github)/~', $entry)
            || in_array($entry, ['composer.lock', 'phpunit.xml', 'phpstan.neon', 'phpcs.xml'], true)) {
            throw new RuntimeException('Development file in archive: ' . (string) $entry);
        }
    }
    if (json_decode($zip->getFromName('composer.json'), true, 512, JSON_THROW_ON_ERROR) !== $metadata) {
        throw new RuntimeException('Archived metadata differs from the candidate.');
    }
    $zip->close();
    $package = $metadata;
    $package['version'] = 'dev-candidate';
    $package['dist'] = ['type' => 'zip', 'url' => 'file://' . $archive, 'shasum' => sha1_file($archive)];
    $consumer = $workspace . '/consumer';
    mkdir($consumer);
    $require = [$metadata['name'] => 'dev-candidate'];
    foreach ($metadata['require'] as $name => $version) {
        if (str_starts_with($version, 'dev-')) { $require[$name] = $version; }
    }
    file_put_contents($consumer . '/composer.json', json_encode([
        'name' => 'kumwe/isolated-surface-consumer', 'license' => 'proprietary', 'require' => $require,
        'repositories' => array_merge([['type' => 'package', 'package' => $package]], $metadata['repositories'] ?? []),
        'config' => ['allow-plugins' => false], 'minimum-stability' => 'stable', 'prefer-stable' => true,
    ], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n");
    consumerCommand(['composer', '--working-dir=' . $consumer, 'install', '--no-interaction', '--no-progress', '--prefer-dist', '--no-dev', '--classmap-authoritative', '--no-scripts']);
    $smoke = <<<'SMOKE'
<?php
$loader = require __DIR__ . '/vendor/autoload.php';
if (!$loader->isClassMapAuthoritative() || class_exists('PHPUnit\Framework\TestCase')) {
    throw new RuntimeException('Expected authoritative no-dev autoloader.');
}
$package = __DIR__ . '/vendor/' . $argv[1];
$api = json_decode(file_get_contents($package . '/resources/public-api/v1.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($api['symbols'] as $symbol) {
    $name = $symbol['name'];
    if (!class_exists($name) && !interface_exists($name) && !enum_exists($name)) {
        throw new RuntimeException('Public export cannot autoload: ' . $name);
    }
    if (!str_starts_with(realpath((new ReflectionClass($name))->getFileName()), realpath($package) . '/')) {
        throw new RuntimeException('Export resolved outside installed archive: ' . $name);
    }
}
require $package . '/examples/standalone.php';
echo count($api['symbols']) . " exports passed archive consumer.\n";
SMOKE;
    // Archive examples work as a root package and as a consumer dependency.
    file_put_contents($consumer . '/smoke.php', $smoke);
    consumerCommand(['php', $consumer . '/smoke.php', $metadata['name']]);
    echo 'Candidate archive SHA-256: ' . hash_file('sha256', $archive) . "\n";
    echo "Archive consumer passed; this does not attest dependency releases.\n";
} finally {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($workspace, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
        if ($file->isDir() && !$file->isLink()) { rmdir($file->getPathname()); } else { unlink($file->getPathname()); }
    }
    rmdir($workspace);
}
