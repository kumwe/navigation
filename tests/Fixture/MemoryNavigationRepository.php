<?php

declare(strict_types=1);

namespace Kumwe\Navigation\Tests\Fixture;

use DateTimeImmutable;
use InvalidArgumentException;
use Kumwe\Navigation\Application\MenuItemRecord;
use Kumwe\Navigation\Application\MenuRecord;
use Kumwe\Navigation\Application\NavigationRepository;
use Kumwe\Navigation\Application\NavigationVersionConflict;

/** Single-process executable specification; contains no database or production service wiring. */
final class MemoryNavigationRepository implements NavigationRepository
{
    private array $menus = [];
    private array $items = [];

    public function menus(): array
    {
        $menus = array_values($this->menus);
        usort($menus, static fn (MenuRecord $a, MenuRecord $b): int => [$a->handle, $a->id] <=> [$b->handle, $b->id]);
        return $menus;
    }

    public function menu(string $id): ?MenuRecord
    {
        return $this->menus[$id] ?? null;
    }
    public function item(string $id): ?MenuItemRecord
    {
        return $this->items[$id] ?? null;
    }

    public function items(string $menuId): array
    {
        $items = array_values(array_filter($this->items, static fn (MenuItemRecord $item): bool => $item->menuId === $menuId));
        usort($items, static fn (MenuItemRecord $a, MenuItemRecord $b): int => [$a->path, $a->id] <=> [$b->path, $b->id]);
        return $items;
    }

    public function insertMenu(MenuRecord $menu): void
    {
        if (isset($this->menus[$menu->id])) {
            throw new InvalidArgumentException('Duplicate menu.');
        }
        $this->menus[$menu->id] = $menu;
    }

    public function updateMenu(MenuRecord $menu, int $expectedVersion): void
    {
        $this->requireVersion($this->menu($menu->id)?->version, $expectedVersion);
        $this->menus[$menu->id] = $menu;
    }

    public function itemIdsForMenuDeletion(string $id, int $expectedVersion): array
    {
        $this->requireVersion($this->menu($id)?->version, $expectedVersion);
        return array_map(static fn (MenuItemRecord $item): string => $item->id, $this->items($id));
    }

    public function deleteMenu(string $id, int $expectedVersion): void
    {
        $this->requireVersion($this->menu($id)?->version, $expectedVersion);
        foreach ($this->items($id) as $item) {
            unset($this->items[$item->id]);
        }
        unset($this->menus[$id]);
    }

    public function insertItem(MenuItemRecord $item): void
    {
        if (isset($this->items[$item->id])) {
            throw new InvalidArgumentException('Duplicate item.');
        }
        $this->items[$item->id] = $item;
    }

    public function updateItem(MenuItemRecord $item, int $expectedVersion): void
    {
        $this->requireVersion($this->item($item->id)?->version, $expectedVersion);
        $this->items[$item->id] = $item;
    }

    public function deleteItem(string $id, int $expectedVersion): void
    {
        $this->requireVersion($this->item($id)?->version, $expectedVersion);
        $ids = $this->descendants($id);
        foreach ([$id, ...$ids] as $child) {
            unset($this->items[$child]);
        }
    }

    public function pathForParent(string $menuId, ?string $parentId, string $slug): string
    {
        if ($parentId === null) {
            return '/' . $slug;
        }
        $parent = $this->item($parentId);
        if ($parent === null || $parent->menuId !== $menuId) {
            throw new InvalidArgumentException('Invalid parent.');
        }
        return $parent->path . '/' . $slug;
    }

    public function assertMoveIsAcyclic(string $itemId, string $menuId, ?string $parentId): void
    {
        $visited = [];
        while ($parentId !== null) {
            if ($parentId === $itemId || isset($visited[$parentId])) {
                throw new InvalidArgumentException('Cyclic move.');
            }
            $visited[$parentId] = true;
            $parent = $this->item($parentId);
            if ($parent === null || $parent->menuId !== $menuId) {
                throw new InvalidArgumentException('Invalid parent.');
            }
            $parentId = $parent->parentId;
        }
    }

    public function moveDescendantPaths(string $itemId, string $oldPath, string $newPath, DateTimeImmutable $at): void
    {
        // Select by ancestry, not a raw string prefix: /newsroom is not below /news.
        foreach ($this->descendants($itemId) as $id) {
            $item = $this->items[$id];
            if (!str_starts_with($item->path, $oldPath . '/')) {
                throw new NavigationVersionConflict('Descendant path changed.');
            }
        }
        foreach ($this->descendants($itemId) as $id) {
            $item = $this->items[$id];
            $this->items[$id] = new MenuItemRecord(
                $item->id,
                $item->menuId,
                $item->parentId,
                $item->title,
                $item->slug,
                $newPath . substr($item->path, strlen($oldPath)),
                $item->position,
                $item->version + 1,
                $item->createdAt,
                $at,
                $item->targetType,
                $item->contentId,
                $item->targetUrl,
                $item->template,
                $item->colorScheme
            );
        }
    }

    private function descendants(string $id): array
    {
        $ids = [];
        $pending = [$id];
        while ($pending !== []) {
            $parent = array_pop($pending);
            foreach ($this->items as $item) {
                if ($item->parentId === $parent && !isset($ids[$item->id])) {
                    $ids[$item->id] = true;
                    $pending[] = $item->id;
                }
            }
        }
        return array_keys($ids);
    }

    private function requireVersion(?int $actual, int $expected): void
    {
        if ($actual !== $expected) {
            throw new NavigationVersionConflict('Navigation version changed.');
        }
    }
}
