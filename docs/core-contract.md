# Core integration contract

Navigation owns bounded deterministic trees, records, placement/path rules and persistence interfaces. Core owns
scope authority, authorization, database adapters, transactions, contribution registries, HTTP, rendering,
delivery and recovery. A navigation record or identifier never grants access to an item or site.

Construct pure values directly and supply explicit repository implementations. Operations do not capture actor,
site, request, connection or container state. Preserve caller transaction boundaries and optimistic versions.

Repository adapters provide scoped/absent reads, record round trips and stable path ordering. Missing or stale
versions conflict; moves refuse cycles and cross-menu placement. Descendant paths/versions and cascade cleanup
follow the documented ownership contract. Pure trees admit at most 1,024 items and paths at most 64 items,
normalize parent UUIDs and enforce UTF-8/bounded titles and derived paths.

Package tests and reusable repository conformance retain portable behavior, refusal and ordering assertions.
Core adapters must additionally prove real database atomicity, concurrent updates, authorization, rendering and
recovery. Passing package conformance does not establish Core workload acceptance.

The [source map](source-map.json) preserves canonical namespace ownership. Verify current consumer references
before replacing a legacy implementation. Remove duplicate implementation tests with their superseded source;
retain Core composition and operational tests. No aliases or parallel class declarations are supported.

See [public API](public-api.md), [integration](integration.md), [compatibility](../COMPATIBILITY.md),
[test ownership](test-ownership.md) and [release record](release-record.md).
