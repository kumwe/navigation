<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$metadata = json_decode(file_get_contents($root . '/composer.json'), true, 512, JSON_THROW_ON_ERROR);
foreach (['kumwe/app', 'kumwe/extension-sdk', 'doctrine/orm', 'doctrine/dbal', 'twig/twig', 'mezzio/mezzio'] as $forbidden) {
    if (isset($metadata['require'][$forbidden])) {
        throw new RuntimeException('Forbidden runtime dependency: ' . $forbidden);
    }
}
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/src'));
foreach ($files as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') { continue; }
    foreach (token_get_all(file_get_contents($file->getPathname())) as $token) {
        if (!is_array($token)) { continue; }
        if (in_array($token[0], [T_NAME_QUALIFIED, T_NAME_FULLY_QUALIFIED], true)
            && preg_match('/^(?:\\\\)?(?:Kumwe\\\\(?:App|Extension)|Doctrine|Twig|Mezzio)\\\\/', $token[1])) {
            throw new RuntimeException('Forbidden source ownership: ' . $token[1]);
        }
        if ($token[0] === T_STRING && in_array(strtolower($token[1]), ['class_alias', 'eval'], true)) {
            throw new RuntimeException('Aliases and dynamic execution are forbidden.');
        }
    }
}
$services = json_decode(file_get_contents($root . '/resources/service-map/v1.json'), true, 512, JSON_THROW_ON_ERROR);
if ($services['config_provider'] !== null || glob($root . '/src/*ConfigProvider.php') !== []) {
    throw new RuntimeException('This value and port package must not acquire ambient container services.');
}
echo "Architecture boundary passed.\n";
