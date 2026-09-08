<?php

declare(strict_types=1);

namespace Kumwe\Navigation\Tests\Conformance;

use DateTimeImmutable;
use InvalidArgumentException;
use Kumwe\Navigation\Application\MenuItemRecord;
use Kumwe\Navigation\Application\MenuRecord;
use Kumwe\Navigation\Application\NavigationRepository;
use Kumwe\Navigation\Application\NavigationVersionConflict;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/** Override repository() with a fresh adapter to reuse all assertions without copying tests. */
abstract class NavigationRepositoryContract extends TestCase
{
    abstract protected function repository(): NavigationRepository;

    protected function menuRecord(int $id = 1, int $version = 1): MenuRecord
    {
        $at = new DateTimeImmutable('2026-09-08T12:00:00Z');
        return new MenuRecord($this->id($id), 'menu-' . $id, 'Menu ' . $id, $version, $at, $at);
    }

    protected function itemRecord(int $id, int $menu = 1, ?int $parent = null, string $path = '/news', int $version = 1): MenuItemRecord
    {
        $at = new DateTimeImmutable('2026-09-08T12:00:00Z');
        return new MenuItemRecord(
            $this->id($id),
            $this->id($menu),
            $parent === null ? null : $this->id($parent),
            'Item ' . $id,
            basename($path),
            $path,
            0,
            $version,
            $at,
            $at,
            'url',
            null,
            'https://example.com',
            'page',
            'dark'
        );
    }

    protected function id(int $id): string
    {
        return sprintf('018f22e2-7c8b-7ab0-8f3a-%012d', $id);
    }

    public function testEmptyAndUnknownLookupsAreAbsent(): void
    {
        $repository = $this->repository();
        self::assertSame([], $repository->menus());
        self::assertSame([], $repository->items($this->id(1)));
        self::assertNull($repository->menu($this->id(1)));
        self::assertNull($repository->item($this->id(10)));
    }

    public function testRecordsRoundTripAndItemsAreScopedAndPathOrdered(): void
    {
        $repository = $this->repository();
        foreach ([2, 1] as $id) {
            $repository->insertMenu($this->menuRecord($id));
        }
        foreach (
            [$this->itemRecord(12, path: '/z'), $this->itemRecord(10),
            $this->itemRecord(11, parent: 10, path: '/news/child'), $this->itemRecord(20, menu: 2)] as $item
        ) {
            $repository->insertItem($item);
        }
        self::assertEquals($this->menuRecord(), $repository->menu($this->id(1)));
        self::assertSame($this->itemRecord(10)->toArray(), $repository->item($this->id(10))->toArray());
        self::assertSame([$this->id(10), $this->id(11), $this->id(12)], array_column($repository->items($this->id(1)), 'id'));
        self::assertSame([$this->id(20)], array_column($repository->items($this->id(2)), 'id'));
        self::assertEquals($repository->menus(), $repository->menus());
    }

    public function testVersionedWritesRejectMissingAndStaleRowsWithoutMutation(): void
    {
        $repository = $this->repository();
        $repository->insertMenu($this->menuRecord());
        $repository->insertItem($this->itemRecord(10));
        $repository->updateMenu($this->menuRecord(version: 2), 1);
        $repository->updateItem($this->itemRecord(10, path: '/renamed', version: 2), 1);
        foreach (
            [
            fn () => $repository->updateMenu($this->menuRecord(version: 3), 1),
            fn () => $repository->updateMenu($this->menuRecord(9, 2), 1),
            fn () => $repository->deleteMenu($this->id(1), 1),
            fn () => $repository->itemIdsForMenuDeletion($this->id(1), 1),
            fn () => $repository->itemIdsForMenuDeletion($this->id(9), 1),
            fn () => $repository->updateItem($this->itemRecord(10, version: 3), 1),
            fn () => $repository->updateItem($this->itemRecord(99, version: 2), 1),
            fn () => $repository->deleteItem($this->id(10), 1),
            fn () => $repository->deleteItem($this->id(99), 1),
            fn () => $repository->deleteMenu($this->id(9), 1),
            ] as $write
        ) {
            try {
                $write();
                self::fail('Stale or missing write was accepted.');
            } catch (NavigationVersionConflict) {
                self::assertSame(2, $repository->menu($this->id(1))->version);
            }
            self::assertSame('/renamed', $repository->item($this->id(10))->path);
        }
    }

    public function testParentPathRejectsMissingAndCrossMenuParents(): void
    {
        $repository = $this->repository();
        $repository->insertMenu($this->menuRecord());
        $repository->insertItem($this->itemRecord(10));
        self::assertSame('/root', $repository->pathForParent($this->id(1), null, 'root'));
        self::assertSame('/news/child', $repository->pathForParent($this->id(1), $this->id(10), 'child'));
        foreach ([[$this->id(2), $this->id(10)], [$this->id(1), $this->id(99)]] as [$menu, $parent]) {
            try {
                $repository->pathForParent($menu, $parent, 'child');
                self::fail('Invalid parent accepted.');
            } catch (InvalidArgumentException) {
                self::assertCount(1, $repository->items($this->id(1)));
            }
        }
    }

    #[DataProvider('invalidMoves')]
    public function testMovesRejectSelfDescendantUnknownAndForeignParents(int $parent): void
    {
        $repository = $this->tree();
        $this->expectException(InvalidArgumentException::class);
        $repository->assertMoveIsAcyclic($this->id(10), $this->id(1), $this->id($parent));
    }

    public static function invalidMoves(): iterable
    {
        yield 'self' => [10];
        yield 'child' => [11];
        yield 'grandchild' => [12];
        yield 'unknown' => [99];
        yield 'other menu' => [20];
    }

    public function testMoveRewritesOnlyDescendantsAndFencesEveryChangedVersion(): void
    {
        $repository = $this->tree();
        $at = new DateTimeImmutable('2026-09-08T13:00:00Z');
        $repository->assertMoveIsAcyclic($this->id(10), $this->id(1), null);
        $repository->assertMoveIsAcyclic($this->id(10), $this->id(1), $this->id(13));
        $repository->updateItem($this->itemRecord(10, path: '/renamed', version: 2), 1);
        self::assertSame('/news/child', $repository->item($this->id(11))->path);
        $before = $repository->item($this->id(11));
        $repository->moveDescendantPaths($this->id(10), '/news', '/renamed', $at);
        self::assertSame('/renamed/child', $repository->item($this->id(11))->path);
        self::assertSame('/renamed/child/grandchild', $repository->item($this->id(12))->path);
        self::assertSame('/newsroom', $repository->item($this->id(13))->path);
        self::assertSame('/news', $repository->item($this->id(20))->path);
        foreach ([11, 12] as $id) {
            self::assertSame(2, $repository->item($this->id($id))->version);
            self::assertEquals($at, $repository->item($this->id($id))->updatedAt);
        }
        $after = $repository->item($this->id(11));
        foreach (['id', 'menuId', 'parentId', 'title', 'slug', 'position', 'createdAt', 'targetType', 'contentId', 'targetUrl', 'template', 'colorScheme'] as $field) {
            self::assertEquals($before->$field, $after->$field);
        }
        $this->expectException(NavigationVersionConflict::class);
        $repository->updateItem($this->itemRecord(11, parent: 10, path: '/stale', version: 2), 1);
    }

    public function testCascadeDeletionPreservesOtherTreesAndReturnsExactOwnershipCleanupIds(): void
    {
        $repository = $this->tree();
        $ids = $repository->itemIdsForMenuDeletion($this->id(1), 1);
        sort($ids);
        self::assertSame(array_map($this->id(...), [10, 11, 12, 13]), $ids);
        self::assertCount(4, $repository->items($this->id(1)));
        $repository->deleteItem($this->id(11), 1);
        self::assertNull($repository->item($this->id(11)));
        self::assertNull($repository->item($this->id(12)));
        self::assertNotNull($repository->item($this->id(10)));
        $repository->deleteMenu($this->id(1), 1);
        self::assertNull($repository->menu($this->id(1)));
        self::assertSame([], $repository->items($this->id(1)));
        self::assertNotNull($repository->menu($this->id(2)));
        self::assertNotNull($repository->item($this->id(20)));
    }

    private function tree(): NavigationRepository
    {
        $repository = $this->repository();
        $repository->insertMenu($this->menuRecord());
        $repository->insertMenu($this->menuRecord(2));
        foreach (
            [$this->itemRecord(10), $this->itemRecord(11, parent: 10, path: '/news/child'),
            $this->itemRecord(12, parent: 11, path: '/news/child/grandchild'), $this->itemRecord(13, path: '/newsroom'),
            $this->itemRecord(20, menu: 2)] as $item
        ) {
            $repository->insertItem($item);
        }
        return $repository;
    }
}
