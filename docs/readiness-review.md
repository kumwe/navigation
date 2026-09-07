# Extraction readiness review — 2026-09-07

Candidate version: `0.1.1`. Published baseline: `0.1.0`.

Normalize parent UUIDs on public placement and enforce UTF-8 titles plus bounded derived paths.

The existing source map remains the extraction provenance record. Content and Navigation use App baseline `24ecf956423c18933e824b43cea1bfb9127a79a9`; the surface declarations and business contracts preserve the SDK provenance in docs/source-map.json. This review adds portable boundary behavior and package-owned tests without changing App production code or test ownership.

## Runtime boundary

The neutral tree owns stable ordering, parent resolution, cycle/depth/width refusal and path reconstruction. Public placedAt normalizes parent IDs as create already does. Paths are bounded to 64 segments and 10304 bytes, the maximum 64 valid 160-byte slugs plus separators. Host target resolution, URL policy, active registries, rendering and persistence remain outside this package.

## Verification and remaining release steps

Package-owned regression tests cover the changed invariants. The public API gate now compares generated Markdown as well as JSON, including full method signatures, defaults, public properties and constant values; source file order is sorted before generation. No ConfigProvider is introduced because these values, pure algorithms and ports have no injected runtime coordinator.

Local source validation uses PHP 8.5.10 and exact dependency-tag archives where registry access is unavailable. This is distinct from the supported Composer security and built-archive consumer gates in CI. Merge only after the complete package workflow passes. The candidate is not a published or independently release-verified artifact. Publication, independent artifact verification and a coordinated exact-pin consumer train remain required before App integration. App acceptance, authorization, lifecycle, persistence and browser tests remain App-owned and were not run or claimed by this package review.
