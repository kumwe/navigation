# Extraction readiness review — 2026-09-08

Proposed successor: `0.1.2`. Published baseline: [v0.1.1](https://github.com/kumwe/navigation/releases/tag/v0.1.1) at `9ab8019cc7fbc32b2cd81da74d021bc294ece058`. Review: [PR #5](https://github.com/kumwe/navigation/pull/5).

The reusable NavigationRepository suite covers every public method: absent/scoped reads, stored record round trips, stable path ordering, missing/stale version conflicts, cycle/cross-menu refusal, descendant path/version updates and exact cascade ownership cleanup. Existing bounded tree and hostile-input tests remain. Production source and public signatures are unchanged.

The complete source/test inventory is enforced by `composer ownership`. Abstract suites and adapters stay in test-only autoload and remain outside production archives. [Test ownership](test-ownership.md) explains adapter reuse and the guarantees still requiring real host/database tests.

Local PHP 8.5.10: 23 tests / 100 assertions and ownership gate pass. The final PR must pass the full existing Composer/static/API/security/archive consumer and release automation gates. This proposed successor is not yet published or independently release-verified. A human merge, automated immutable publication and independent artifact/dependency attestation remain the release steps before later core adoption. No App integration or App acceptance result is claimed.
