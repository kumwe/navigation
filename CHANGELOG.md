# Changelog

## [0.1.1] - 2026-09-07

- Normalize parent UUIDs on public placement and enforce UTF-8 titles plus bounded derived paths.
- Verify API documentation, full method signatures, parameter defaults, properties and constant values against deterministic generated metadata.
- Add package-owned regression and hostile-input tests; refresh the extraction handoff and dependency status.
- This is a release candidate record. Publication follows human merge and the package gate; independent release verification and App integration are separate.

## [0.1.0] - 2026-09-07

- Extract the canonical runtime types recorded in docs/source-map.json and their behavior tests.
- Add standalone Composer, strict analysis, API, archive and clean consumer gates.
- Preserve the merged source extraction and all package-owned behavior tests.
- NRM-2026-035: enabling-refactor; completion_claim: false.
- Publication requires package checks and stable dependency version/source identity; independent attestations and App adoption remain separate.
- Publish the recorded version automatically after the complete post-merge package gate.
