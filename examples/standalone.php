<?php
declare(strict_types=1);
if (!class_exists(Composer\Autoload\ClassLoader::class, false)) {
    require dirname(__DIR__) . '/vendor/autoload.php';
}
use Kumwe\Navigation\Domain\MenuItem;
use Kumwe\Navigation\Domain\MenuTree;
$menu = MenuTree::create('018f22e2-7c8b-7ab0-8f3a-88e8026bc100');
if ($menu->items() !== []) { throw new RuntimeException('Empty tree mismatch.'); }
echo "Navigation model example passed.\n";
