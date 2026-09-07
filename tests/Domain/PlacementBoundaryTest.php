<?php

declare(strict_types=1);

namespace Kumwe\Navigation\Tests\Domain;

use InvalidArgumentException;
use Kumwe\Navigation\Domain\MenuItem;
use Kumwe\Navigation\Domain\MenuTree;
use PHPUnit\Framework\TestCase;

final class PlacementBoundaryTest extends TestCase
{
    public function testPublicPlacementNormalizesParentBeforeTreeLookup(): void
    {
        $rootId = '018f22e2-7c8b-7ab0-8f3a-88e8026bb160';
        $childId = '018f22e2-7c8b-7ab0-8f3a-88e8026bb161';
        $root = MenuItem::create($rootId, 'Root', 'root');
        $child = MenuItem::create($childId, 'Child', 'child')->placedAt(strtoupper($rootId), '/root/child');
        self::assertSame($rootId, $child->parentId());
        self::assertSame('/root/child', MenuTree::create($rootId, $child, $root)->item($childId)->path());
    }

    public function testPlacementRejectsUnboundedPathsAndInvalidUtf8Titles(): void
    {
        $id = '018f22e2-7c8b-7ab0-8f3a-88e8026bb160';
        foreach ([
            static fn () => MenuItem::create($id, "\xFF", 'root'),
            static fn () => MenuItem::create($id, 'Root', 'root')->placedAt(null, str_repeat('/x', 65)),
            static fn () => MenuItem::create($id, 'Root', 'root')->placedAt(null, '/' . str_repeat('x', 10_304)),
        ] as $construct) {
            try {
                $construct();
                self::fail('An invalid placement was accepted.');
            } catch (InvalidArgumentException) {
                self::assertTrue(true);
            }
        }
    }
}
