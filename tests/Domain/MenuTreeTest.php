<?php

declare(strict_types=1);

namespace Kumwe\Navigation\Tests\Domain;

use Kumwe\Navigation\Domain\InvalidMenuTree;
use Kumwe\Navigation\Domain\MenuItem;
use Kumwe\Navigation\Domain\MenuTree;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MenuTree::class)]
#[UsesClass(MenuItem::class)]
#[UsesClass(InvalidMenuTree::class)]
final class MenuTreeTest extends TestCase
{
    private const MENU_ID = '018f22e2-7c8b-7ab0-8f3a-88e8026bb160';
    private const HOME_ID = '018f22e2-7c8b-7ab0-8f3a-88e8026bb161';
    private const DOCS_ID = '018f22e2-7c8b-7ab0-8f3a-88e8026bb162';
    private const API_ID = '018f22e2-7c8b-7ab0-8f3a-88e8026bb163';
    private const COMPANY_ID = '018f22e2-7c8b-7ab0-8f3a-88e8026bb164';

    public function testBuildsDeterministicPathsRegardlessOfInputOrder(): void
    {
        $tree = MenuTree::create(
            self::MENU_ID,
            MenuItem::create(self::API_ID, 'API', 'api', self::DOCS_ID),
            MenuItem::create(self::HOME_ID, 'Home', 'home'),
            MenuItem::create(self::DOCS_ID, 'Documentation', 'docs', self::HOME_ID),
        );

        self::assertSame('/home', $tree->item(self::HOME_ID)->path());
        self::assertSame('/home/docs', $tree->item(self::DOCS_ID)->path());
        self::assertSame('/home/docs/api', $tree->item(self::API_ID)->path());
        self::assertSame(
            ['/home', '/home/docs', '/home/docs/api'],
            array_map(static fn (MenuItem $item): string => $item->path(), $tree->items()),
        );
    }

    public function testMoveRebuildsEveryDescendantPathAndLeavesOriginalUnchanged(): void
    {
        $tree = $this->tree();
        $moved = $tree->move(self::DOCS_ID, self::COMPANY_ID);

        self::assertSame('/home/docs', $tree->item(self::DOCS_ID)->path());
        self::assertSame('/company/docs', $moved->item(self::DOCS_ID)->path());
        self::assertSame('/company/docs/api', $moved->item(self::API_ID)->path());
    }

    public function testRejectsMoveBelowDescendant(): void
    {
        $this->expectException(InvalidMenuTree::class);

        $this->tree()->move(self::DOCS_ID, self::API_ID);
    }

    public function testRejectsCycleInInitialTree(): void
    {
        $this->expectException(InvalidMenuTree::class);

        MenuTree::create(
            self::MENU_ID,
            MenuItem::create(self::HOME_ID, 'Home', 'home', self::DOCS_ID),
            MenuItem::create(self::DOCS_ID, 'Documentation', 'docs', self::HOME_ID),
        );
    }

    public function testRejectsDuplicateSiblingSlug(): void
    {
        $this->expectException(InvalidMenuTree::class);

        MenuTree::create(
            self::MENU_ID,
            MenuItem::create(self::HOME_ID, 'Home', 'home'),
            MenuItem::create(self::DOCS_ID, 'Duplicate Home', 'home'),
        );
    }

    public function testRejectsMissingParent(): void
    {
        $this->expectException(InvalidMenuTree::class);

        MenuTree::create(
            self::MENU_ID,
            MenuItem::create(self::HOME_ID, 'Home', 'home', self::DOCS_ID),
        );
    }

    public function testTreeWidthAndDepthLimitsApplyRegardlessOfInputOrder(): void
    {
        $items = [];
        $parent = null;
        for ($i = 0; $i < 64; $i++) {
            $id = sprintf('018f22e2-7c8b-7ab0-8f3a-%012d', $i);
            $items[] = MenuItem::create($id, 'Entry', 'entry-' . $i, $parent);
            $parent = $id;
        }
        self::assertCount(64, MenuTree::create(self::MENU_ID, ...$items)->items());
        self::assertCount(64, MenuTree::create(self::MENU_ID, ...array_reverse($items))->items());
        $items[] = MenuItem::create(self::HOME_ID, 'Too deep', 'too-deep', $parent);
        foreach ([$items, array_reverse($items)] as $ordered) {
            try {
                MenuTree::create(self::MENU_ID, ...$ordered);
                self::fail('A 65-item path was accepted.');
            } catch (InvalidMenuTree) {
                self::assertTrue(true);
            }
        }
        $this->expectException(InvalidMenuTree::class);
        MenuTree::create(self::MENU_ID, ...array_fill(0, 1025, $items[0]));
    }

    private function tree(): MenuTree
    {
        return MenuTree::create(
            self::MENU_ID,
            MenuItem::create(self::HOME_ID, 'Home', 'home'),
            MenuItem::create(self::DOCS_ID, 'Documentation', 'docs', self::HOME_ID),
            MenuItem::create(self::API_ID, 'API', 'api', self::DOCS_ID),
            MenuItem::create(self::COMPANY_ID, 'Company', 'company'),
        );
    }
}
