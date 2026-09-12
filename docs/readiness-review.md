# Repository contract guarantees

Reusable NavigationRepository conformance covers every public method: absent/scoped reads, stored record round
trips, stable path ordering, missing/stale version conflicts, cycle/cross-menu refusal, descendant path/version
updates and exact cascade ownership cleanup. Bounded tree and hostile-input tests remain package-owned.

`composer ownership` enforces the complete source/test inventory. Abstract suites and adapters remain test-only
and are excluded from production archives. [Test ownership](test-ownership.md) explains adapter reuse and the
additional guarantees requiring real database tests.

Every changed source must pass the complete Composer/static/API/security/archive consumer and release automation
gates. Package publication, independent verification and Core acceptance are separate observations. See
[Core integration](core-contract.md) and [releasing](releasing.md).
