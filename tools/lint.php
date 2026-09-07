<?php
declare(strict_types=1);
foreach (['src', 'tests', 'tools', 'examples'] as $dir) {
    if (!is_dir(__DIR__ . '/../' . $dir)) { continue; }
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/../' . $dir, FilesystemIterator::SKIP_DOTS)) as $file) {
        if ($file->getExtension() !== 'php') { continue; }
        exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file->getPathname()), $output, $status);
        if ($status !== 0) { throw new RuntimeException(implode("\n", $output)); }
        $output = [];
    }
}
echo "Syntax passed.\n";
