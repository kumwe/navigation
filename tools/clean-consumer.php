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
    foreach (['composer.json', 'resources/public-api/v1.json', 'resources/public-api/signature-details-v1.json', 'resources/capabilities/v1.json', 'resources/service-map/v1.json', 'docs/public-api.md', 'examples/standalone.php'] as $required) {
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
    foreach (['resources/public-api/v1.json', 'resources/public-api/signature-details-v1.json', 'resources/capabilities/v1.json', 'resources/service-map/v1.json', 'docs/public-api.md'] as $manifest) {
        if ($zip->getFromName($manifest) !== file_get_contents($root . '/' . $manifest)) {
            throw new RuntimeException('Archive contract differs from reviewed source: ' . $manifest);
        }
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
$details = json_decode(file_get_contents($package . '/resources/public-api/signature-details-v1.json'), true, 512, JSON_THROW_ON_ERROR);
if (array_keys($api['symbols']) !== array_column($details['symbols'], 'name')) {
    throw new RuntimeException('Governed API and signature details disagree on ownership.');
}
foreach ($details['symbols'] as $symbol) {
    $name = $symbol['name'];
    if (!class_exists($name) && !interface_exists($name) && !enum_exists($name)) {
        throw new RuntimeException('Public export cannot autoload: ' . $name);
    }
    $reflection = new ReflectionClass($name);
    foreach ($symbol['methods'] as $method) {
        $reflected = $reflection->getMethod($method['name']);
        if ($reflected->isStatic() !== $method['static'] || (string) $reflected->getReturnType() !== $method['return']) {
            throw new RuntimeException('Installed method contract differs: ' . $name . '::' . $method['name']);
        }
        $parameters = [];
        foreach ($reflected->getParameters() as $parameter) {
            $parameters[] = [
                'name' => $parameter->getName(), 'type' => (string) $parameter->getType(),
                'optional' => $parameter->isOptional(),
                'default' => $parameter->isDefaultValueAvailable() ? var_export($parameter->getDefaultValue(), true) : null,
                'variadic' => $parameter->isVariadic(), 'reference' => $parameter->isPassedByReference(),
            ];
        }
        if ($parameters !== $method['parameters']) {
            throw new RuntimeException('Installed parameter contract differs: ' . $name . '::' . $method['name']);
        }
    }
    foreach ($symbol['properties'] as $property) {
        $reflected = $reflection->getProperty($property['name']);
        if ((string) $reflected->getType() !== $property['type'] || $reflected->isReadOnly() !== $property['readonly']) {
            throw new RuntimeException('Installed property contract differs: ' . $name . '::$' . $property['name']);
        }
    }
    foreach ($symbol['constant_values'] as $constant => $value) {
        if (var_export($reflection->getConstant($constant), true) !== $value) {
            throw new RuntimeException('Installed constant contract differs: ' . $name . '::' . $constant);
        }
    }
    if (!str_starts_with(realpath((new ReflectionClass($name))->getFileName()), realpath($package) . '/')) {
        throw new RuntimeException('Export resolved outside installed archive: ' . $name);
    }
}
$capabilities = json_decode(file_get_contents($package . '/resources/capabilities/v1.json'), true, 512, JSON_THROW_ON_ERROR);
$services = json_decode(file_get_contents($package . '/resources/service-map/v1.json'), true, 512, JSON_THROW_ON_ERROR);
if ($capabilities['package'] !== $argv[1] || $services['config_provider'] !== null) {
    throw new RuntimeException('Installed capability or no-provider contract differs.');
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
