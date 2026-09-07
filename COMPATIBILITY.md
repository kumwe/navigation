# Compatibility

Requires PHP 8.5. The source map records a deliberate namespace ownership break; no aliases or dual class declarations are shipped. Canonical packages own imported types. Exceptions and wire shapes remain inherited unless a decision below documents a change. Independent release verification is required before a consumer exact-pins a stable version.

Versioned decision NAV-001: the initial extraction limits a tree to 1024 items and a path to 64 items. Oversized inputs formerly had no explicit limit; they now fail with InvalidMenuTree before unbounded traversal. Valid inputs retain identifier, ordering and immutable move behavior. Tests prove depth admission/refusal in both input orders.

## 0.1.1 boundary corrections

Normalize parent UUIDs on public placement and enforce UTF-8 titles plus bounded derived paths. Public constructor parameter order and declaration serialization remain compatible; malformed/unbounded or externally mutable inputs are rejected or detached as documented in [the review](docs/readiness-review.md). App adoption must use the released public API and keep host integration tests in App.
