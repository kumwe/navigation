# Public API

All values enforce the documented constructor invariants. Domain methods perform no I/O, own no transaction and make no authorization decisions. Immutable values are safe to share; host inputs and lookup ports must remain generation-stable for the duration of an operation. Exceptions and parameter detail appear below verbatim from the source contract.

## Kumwe\Navigation\Application\MenuItemRecord

/**
 * Stored state of a single navigation menu item as the application layer passes it around.
 *
 * The item carries its materialised `path` instead of deriving one on read, so the public site can
 * match an incoming request against one column and the administrator UI can render a tree without
 * walking parents; `NavigationService` recomputes that path, and every descendant path, whenever a
 * move changes it. `version` is the optimistic-locking counter a writer quotes back, and the
 * `targetType`/`contentId`/`targetUrl` triple is a discriminated target rather than three
 * independent columns: which of the two nullable values carries meaning depends on the type.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Capture a menu item exactly as it is stored.
     *
     * @param  string             $id           UUIDv7 primary key of the item.
     * @param  string             $menuId       Menu the item belongs to; an item never moves between menus.
     * @param  ?string            $parentId     Parent item, or null when the item sits at the menu root.
     * @param  string             $title        Label the navigation renders for this item.
     * @param  string             $slug         Lowercase URL segment this item contributes to the path.
     * @param  string             $path         Absolute site path: the parent's path joined with this slug.
     * @param  int                $position     Sort order among siblings; lower values render first.
     * @param  int                $version      Optimistic-locking counter incremented by every write.
     * @param  DateTimeImmutable  $createdAt    When the item was first stored.
     * @param  DateTimeImmutable  $updatedAt    When the item was last written.
     * @param  string             $targetType   What the item points at: `content`, `anchor` or `url`.
     * @param  ?string            $contentId    Content the item resolves to, for `content` and `anchor` targets.
     * @param  ?string            $targetUrl    Fragment for an `anchor` target, or the link for a `url` target.
     * @param  ?string            $template     Site template overriding the linked page's type-declared
     *         layout, or null to keep the content type's decision.
     * @param  ?string            $colorScheme  Presentation colour-scheme handle overriding the site's
     *         active scheme while the linked page renders, or null to keep the site's decision.
     *
     * @since  2.0.0
     */

```php
public function __construct(string $id, string $menuId, ?string $parentId, string $title, string $slug, string $path, int $position, int $version, DateTimeImmutable $createdAt, DateTimeImmutable $updatedAt, string $targetType = 'content', ?string $contentId = NULL, ?string $targetUrl = NULL, ?string $template = NULL, ?string $colorScheme = NULL);
```

### toArray

/**
     * Export the item in the snake_case shape the HTTP API, console and MCP surfaces serialise.
     *
     * @return  array<string, mixed>  Keys named after the storage columns, timestamps as RFC 3339.
     *
     * @since   2.0.0
     */

```php
public function toArray(): array;
```

### Public properties

- `readonly string $id`
- `readonly string $menuId`
- `readonly ?string $parentId`
- `readonly string $title`
- `readonly string $slug`
- `readonly string $path`
- `readonly int $position`
- `readonly int $version`
- `readonly DateTimeImmutable $createdAt`
- `readonly DateTimeImmutable $updatedAt`
- `readonly string $targetType`
- `readonly ?string $contentId`
- `readonly ?string $targetUrl`
- `readonly ?string $template`
- `readonly ?string $colorScheme`

## Kumwe\Navigation\Application\MenuRecord

/**
 * Stored state of a navigation menu — the named container its items hang from.
 *
 * A menu is addressed two ways: by `id` for management writes, and by `handle` for rendering, which
 * is how a site's presentation contract names the menu it draws as primary navigation without
 * embedding a generated identifier in a theme. The record itself holds no items; ask
 * `NavigationRepository::items()` for those. `version` is the optimistic-locking counter that an
 * update or delete must quote back, so a stale administrator screen cannot overwrite a newer edit.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Capture a menu exactly as it is stored.
     *
     * @param  string             $id         UUIDv7 primary key of the menu.
     * @param  string             $handle     Stable lowercase name a theme or setting refers to the menu by.
     * @param  string             $title      Human-readable label shown to operators.
     * @param  int                $version    Optimistic-locking counter incremented by every write.
     * @param  DateTimeImmutable  $createdAt  When the menu was first stored.
     * @param  DateTimeImmutable  $updatedAt  When the menu was last written.
     *
     * @since  2.0.0
     */

```php
public function __construct(string $id, string $handle, string $title, int $version, DateTimeImmutable $createdAt, DateTimeImmutable $updatedAt);
```

### toArray

/**
     * Export the menu in the snake_case shape the HTTP API, console and MCP surfaces serialise.
     *
     * @return  array<string, mixed>  Keys named after the storage columns, timestamps as RFC 3339.
     *
     * @since   2.0.0
     */

```php
public function toArray(): array;
```

### Public properties

- `readonly string $id`
- `readonly string $handle`
- `readonly string $title`
- `readonly int $version`
- `readonly DateTimeImmutable $createdAt`
- `readonly DateTimeImmutable $updatedAt`

## Kumwe\Navigation\Application\NavigationNotFound

/**
 * Signals that a menu or menu item the caller named does not exist.
 *
 * `NavigationService` reads through the repository's nullable lookups and converts the null into this
 * exception, so callers never have to distinguish "no such record" from "not readable": an actor who
 * may not see a record is refused with an authorization failure before the lookup runs. Delivery code
 * maps it to a 404, and its message is written for an operator rather than naming the missing id.
 *
 * @since  2.0.0
 */

## Kumwe\Navigation\Application\NavigationRepository

/**
 * Port through which navigation menus and their items are read and written.
 *
 * The port is deliberately wider than plain CRUD: path materialisation, cycle rejection and the
 * cascading path rewrite live here because they need set-based access to the stored tree, and doing
 * them in the store keeps `NavigationService` free of a full in-memory tree load on every edit. Every
 * mutation takes the `version` the caller read and refuses when the stored row has moved on, so an
 * implementation must make that check part of the same statement rather than a read followed by a
 * write. Reads are unfiltered: site scoping and capability checks belong to the callers above.
 *
 * @since  2.0.0
 */

### menus

/**
     * List every stored menu.
     *
     * @return  list<MenuRecord>  All menus in a stable order, empty when none exist.
     *
     * @since   2.0.0
     */

```php
public function menus(): array;
```

### menu

/**
     * Look up one menu by its identifier.
     *
     * @param   string  $id  UUID of the menu to load.
     *
     * @return  ?MenuRecord  The stored menu, or null when no menu carries that identifier.
     *
     * @since   2.0.0
     */

```php
public function menu(string $id): ?Kumwe\Navigation\Application\MenuRecord;
```

### items

/**
     * List every item belonging to one menu.
     *
     * Items come back ordered by path first, so a caller walking the result meets a parent before its
     * children and can build a tree in a single pass.
     *
     * @param   string  $menuId  UUID of the menu whose items are wanted.
     *
     * @return  list<MenuItemRecord>  The menu's items, empty when the menu is empty or unknown.
     *
     * @since   2.0.0
     */

```php
public function items(string $menuId): array;
```

### item

/**
     * Look up one menu item by its identifier.
     *
     * @param   string  $id  UUID of the item to load.
     *
     * @return  ?MenuItemRecord  The stored item, or null when no item carries that identifier.
     *
     * @since   2.0.0
     */

```php
public function item(string $id): ?Kumwe\Navigation\Application\MenuItemRecord;
```

### insertMenu

/**
     * Store a new menu.
     *
     * @param   MenuRecord  $menu  Fully formed menu to write, already carrying version 1.
     *
     * @return  void
     *
     * @since   2.0.0
     */

```php
public function insertMenu(Kumwe\Navigation\Application\MenuRecord $menu): void;
```

### updateMenu

/**
     * Overwrite a stored menu, provided nobody else has written it since it was read.
     *
     * @param   MenuRecord  $menu             New state, already carrying the incremented version.
     * @param   int         $expectedVersion  Version the caller read, which the stored row must still hold.
     *
     * @return  void
     *
     * @throws  NavigationVersionConflict  When the stored menu is gone or no longer at that version.
     *
     * @since   2.0.0
     */

```php
public function updateMenu(Kumwe\Navigation\Application\MenuRecord $menu, int $expectedVersion): void;
```

### itemIdsForMenuDeletion

/**
     * Claim a menu for deletion and report the items that will go with it.
     *
     * Called inside the deleting transaction and before `deleteMenu()`, so the implementation is
     * expected to lock the menu row while it checks the version. The returned identifiers are what the
     * caller uses to retract the per-item site-ownership records that the cascade would otherwise leave
     * behind, since the rows themselves disappear with the menu.
     *
     * @param   string  $id               UUID of the menu about to be deleted.
     * @param   int     $expectedVersion  Version the caller read, which the stored menu must still hold.
     *
     * @return  list<string>  Identifiers of every item under the menu, empty when it has none.
     *
     * @throws  NavigationVersionConflict  When the stored menu is gone or no longer at that version.
     *
     * @since   2.0.0
     */

```php
public function itemIdsForMenuDeletion(string $id, int $expectedVersion): array;
```

### deleteMenu

/**
     * Delete a menu and, through the stored cascade, every item beneath it.
     *
     * @param   string  $id               UUID of the menu to delete.
     * @param   int     $expectedVersion  Version the caller read, which the stored menu must still hold.
     *
     * @return  void
     *
     * @throws  NavigationVersionConflict  When the stored menu is gone or no longer at that version.
     *
     * @since   2.0.0
     */

```php
public function deleteMenu(string $id, int $expectedVersion): void;
```

### insertItem

/**
     * Store a new menu item.
     *
     * @param   MenuItemRecord  $item  Fully formed item to write, with its path already resolved.
     *
     * @return  void
     *
     * @since   2.0.0
     */

```php
public function insertItem(Kumwe\Navigation\Application\MenuItemRecord $item): void;
```

### updateItem

/**
     * Overwrite a stored menu item, provided nobody else has written it since it was read.
     *
     * Only the item itself is touched. When the write changes its path, the caller follows up with
     * `moveDescendantPaths()` to bring the subtree along.
     *
     * @param   MenuItemRecord  $item             New state, already carrying the incremented version.
     * @param   int             $expectedVersion  Version the caller read, which the row must still hold.
     *
     * @return  void
     *
     * @throws  NavigationVersionConflict  When the stored item is gone or no longer at that version.
     *
     * @since   2.0.0
     */

```php
public function updateItem(Kumwe\Navigation\Application\MenuItemRecord $item, int $expectedVersion): void;
```

### deleteItem

/**
     * Delete a menu item and, through the stored cascade, its descendants.
     *
     * @param   string  $id               UUID of the item to delete.
     * @param   int     $expectedVersion  Version the caller read, which the stored item must still hold.
     *
     * @return  void
     *
     * @throws  NavigationVersionConflict  When the stored item is gone or no longer at that version.
     *
     * @since   2.0.0
     */

```php
public function deleteItem(string $id, int $expectedVersion): void;
```

### pathForParent

/**
     * Work out the absolute path an item would occupy under a given parent.
     *
     * The parent is resolved against the named menu, which is what stops an edit from grafting an item
     * onto a branch of some other menu.
     *
     * @param   string   $menuId    UUID of the menu the item belongs to.
     * @param   ?string  $parentId  UUID of the intended parent, or null to place the item at the root.
     * @param   string   $slug      Already-validated URL segment the item contributes.
     *
     * @return  string  Absolute path beginning with a slash, ending in the supplied slug.
     *
     * @throws  \InvalidArgumentException  When the named parent is not an item of that menu.
     *
     * @since   2.0.0
     */

```php
public function pathForParent(string $menuId, ?string $parentId, string $slug): string;
```

### assertMoveIsAcyclic

/**
     * Refuse a reparent that would close a loop in the tree.
     *
     * Callers invoke this before computing the new path, so a move that would make an item its own
     * ancestor is rejected while the stored tree is still intact. Moving an item to the root is always
     * acyclic and returns without a store read.
     *
     * @param   string   $itemId    UUID of the item being moved.
     * @param   string   $menuId    UUID of the menu both the item and the intended parent must belong to.
     * @param   ?string  $parentId  UUID of the intended parent, or null when moving to the root.
     *
     * @return  void
     *
     * @throws  \InvalidArgumentException  When the parent is the item itself, one of its descendants,
     *          unknown, or part of another menu.
     *
     * @since   2.0.0
     */

```php
public function assertMoveIsAcyclic(string $itemId, string $menuId, ?string $parentId): void;
```

### moveDescendantPaths

/**
     * Rewrite the stored paths of everything beneath an item that has just moved.
     *
     * Run inside the same transaction as the `updateItem()` that moved the root, since between the two
     * statements the subtree still records the old prefix. Each rewritten descendant has its own
     * version bumped, so an editor holding a stale copy of a moved child is caught on their next write.
     *
     * @param   string             $itemId   UUID of the item that moved; its descendants are rewritten.
     * @param   string             $oldPath  Path prefix the subtree currently carries.
     * @param   string             $newPath  Path prefix replacing it.
     * @param   DateTimeImmutable  $at       Timestamp recorded as the update time on every rewritten row.
     *
     * @return  void
     *
     * @throws  NavigationVersionConflict  When a descendant row cannot be rewritten as written.
     *
     * @since   2.0.0
     */

```php
public function moveDescendantPaths(string $itemId, string $oldPath, string $newPath, DateTimeImmutable $at): void;
```

## Kumwe\Navigation\Application\NavigationVersionConflict

/**
 * Signals that a navigation write lost the optimistic-locking race and was refused.
 *
 * Every menu and item update, delete and cascading path move quotes the `version` the caller read.
 * When the stored row no longer carries that version — because another editor saved first, or because
 * the row vanished — the write is rejected rather than applied blind, which keeps two administrator
 * screens from silently overwriting each other's tree. The remedy is always the same: reload the
 * record and retry, so delivery code answers with a conflict status rather than a retry loop.
 *
 * @since  2.0.0
 */

## Kumwe\Navigation\Domain\InvalidMenuTree

/**
 * Signals that a set of menu items does not form a valid tree.
 *
 * `MenuTree` refuses to exist in a broken shape, so this is raised at construction and on every move
 * rather than left for a renderer to trip over: duplicate item ids, a parent that is not in the set,
 * two siblings claiming the same slug, a parent cycle, or a move that would place an item below
 * itself. Each of those would otherwise produce a path that is ambiguous or impossible to reach, so
 * the exception marks a rejected edit and never a transient failure worth retrying.
 *
 * @since  2.0.0
 */

## Kumwe\Navigation\Domain\MenuItem

/**
 * One entry of a menu, validated the moment it comes into existence.
 *
 * The constructor is private, so an item can only exist in a shape the navigation rules accept: a
 * canonical UUID, a trimmed title of 1 to 255 characters, and a slug that is already a safe URL
 * segment. Items are born without a path, because placement belongs to `MenuTree`: the tree derives
 * the path from the parent chain and hands back a repositioned copy through `placedAt()`. Nothing
 * outside the tree can therefore give an item a path its ancestry does not justify.
 *
 * @since  2.0.0
 */

### create

/**
     * Creates a fresh, still unplaced item from caller-supplied values.
     *
     * Identifiers are lowercased and the title trimmed here, so two callers spelling the same UUID in
     * different cases produce items a tree treats as one. The path is left empty because only a
     * `MenuTree` knows the ancestry that gives it a value.
     *
     * @param   string   $id        Canonical UUID to identify the item by; letter case is normalised.
     * @param   string   $title     Display label; surrounding whitespace is stripped.
     * @param   string   $slug      URL segment for this item, already lowercase and hyphen-joined.
     * @param   ?string  $parentId  UUID of the parent item, or null to place the item at the root.
     *
     * @return  self  An item whose path is still empty, ready to hand to `MenuTree::create()`.
     *
     * @throws  InvalidArgumentException  When an identifier is not a canonical UUID, the title is not 1 to
     *          255 characters, or the slug is not a valid URL segment.
     *
     * @since   2.0.0
     */

```php
public static function create(string $id, string $title, string $slug, ?string $parentId = NULL): Kumwe\Navigation\Domain\MenuItem;
```

### id

/**
     * Returns the identifier the rest of the navigation model addresses this item by.
     *
     * @return  string  Canonical UUID, lowercased when the item was created.
     *
     * @since   2.0.0
     */

```php
public function id(): string;
```

### title

/**
     * Returns the label a visitor sees for this entry.
     *
     * @return  string  Trimmed display text of 1 to 255 characters.
     *
     * @since   2.0.0
     */

```php
public function title(): string;
```

### slug

/**
     * Returns the URL segment this item contributes to its own path and to every descendant path.
     *
     * @return  string  Lowercase letters and digits joined by single hyphens, at most 160 characters.
     *
     * @since   2.0.0
     */

```php
public function slug(): string;
```

### parentId

/**
     * Returns the item this one hangs beneath.
     *
     * @return  ?string  UUID of the parent, or null when the item sits at the root of its menu.
     *
     * @since   2.0.0
     */

```php
public function parentId(): ?string;
```

### path

/**
     * Returns the absolute path this item resolves to inside its menu.
     *
     * @return  string  Leading-slash path built from the slug chain, or an empty string while the item
     *          has not been placed in a tree.
     *
     * @since   2.0.0
     */

```php
public function path(): string;
```

### placedAt

/**
     * Returns a copy of this item hung under a new parent, carrying the path that placement produces.
     *
     * `MenuTree` is the intended caller: it supplies the parent and the derived path together, so an item
     * can never end up with a path that contradicts its ancestry. The receiver is left untouched.
     *
     * @param   ?string  $parentId  UUID of the new parent, or null to place the item at the root.
     * @param   string   $path      Absolute path the tree computed from the new parent chain.
     *
     * @return  self  A new item; the one it was called on is unchanged.
     *
     * @throws  InvalidArgumentException  When the parent id is not a canonical UUID, or the path is not an
     *          absolute chain of valid slugs.
     *
     * @since   2.0.0
     */

```php
public function placedAt(?string $parentId, string $path): Kumwe\Navigation\Domain\MenuItem;
```

## Kumwe\Navigation\Domain\MenuTree

/**
 * Immutable menu whose item paths are always derived from, and consistent with, the parent chain.
 *
 * This is where the structural rules of a menu live. Building or changing a tree re-derives every path
 * from scratch and re-checks the invariants — each referenced parent exists, no two siblings share a
 * slug, and the parent chain holds no cycle — so a tree that exists is a tree that is coherent. The
 * derived paths do not depend on the order items were supplied in, which makes a rebuild reproducible
 * and the rendered navigation diff-stable. Every operation returns a new tree and leaves the original
 * intact, so a rejected change cannot strand a half-applied menu.
 *
 * @since  2.0.0
 */

### create

/**
     * Builds a tree from loose items, deriving every path and enforcing every structural rule.
     *
     * Input order is irrelevant: a child may be listed before its parent and the resulting paths are the
     * same either way, which lets a caller stream rows out of storage without sorting them first.
     *
     * @param   string    $id     Canonical UUID of the menu.
     * @param   MenuItem  $items  Items to place, in any order; each may appear only once.
     *
     * @return  self  A tree in which every item carries the path its ancestry implies.
     *
     * @throws  InvalidMenuTree  When the tree exceeds 1024 items or a path exceeds 64 items,
     *          when an item is supplied twice, a referenced parent is absent, two
     *          siblings share a slug, or the parent chain contains a cycle.
     * @throws  InvalidArgumentException  When the menu id is not a canonical UUID.
     *
     * @since   2.0.0
     */

```php
public static function create(string $id, Kumwe\Navigation\Domain\MenuItem ...$items): Kumwe\Navigation\Domain\MenuTree;
```

### id

/**
     * Returns the identifier of the menu this tree represents.
     *
     * @return  string  Canonical UUID, lowercased at construction.
     *
     * @since   2.0.0
     */

```php
public function id(): string;
```

### item

/**
     * Looks up a single item by identifier.
     *
     * @param   string  $id  Item identifier; matching ignores letter case.
     *
     * @return  MenuItem  The stored item, carrying its resolved path.
     *
     * @throws  InvalidArgumentException  When this tree holds no item with that id.
     *
     * @since   2.0.0
     */

```php
public function item(string $id): Kumwe\Navigation\Domain\MenuItem;
```

### items

/**
     * Returns every item in a stable, render-ready order.
     *
     * Ordering by path and then by id puts a parent ahead of its descendants and makes two trees holding
     * the same items iterate identically, whatever order they were assembled in.
     *
     * @return  list<MenuItem>  All items, ordered by path and then by id.
     *
     * @since   2.0.0
     */

```php
public function items(): array;
```

### move

/**
     * Returns a new tree with one item re-hung under a different parent.
     *
     * The whole tree is rebuilt, so the moved item's descendants travel with it and every path is
     * re-derived rather than patched. The receiver is untouched, so a rejected move costs nothing.
     *
     * @param   string   $itemId       Item to move; matching ignores letter case.
     * @param   ?string  $newParentId  Item to hang it beneath, or null to lift it to the root.
     *
     * @return  self  A new tree with the moved subtree re-pathed.
     *
     * @throws  InvalidArgumentException  When the item or the named new parent is not in this tree.
     * @throws  InvalidMenuTree  When the move would put the item below itself or one of its descendants,
     *          or the rebuilt tree would break sibling-slug uniqueness.
     *
     * @since   2.0.0
     */

```php
public function move(string $itemId, ?string $newParentId): Kumwe\Navigation\Domain\MenuTree;
```

