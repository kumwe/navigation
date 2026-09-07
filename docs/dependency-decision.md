# Dependency status

The runtime dependency graph uses exact published Kumwe versions. The following source tags are the reviewed dependency coordinates; this table is not an external release attestation.

| Package | Exact version | Tag commit |
| --- | --- | --- |

Navigation has no Kumwe runtime dependency: its current public closure carries scalar identities and neutral tree/record ports.

A floating `latest`, `*` or development branch is not an immutable release coordinate. A newer direct pin must be compatible with every transitive exact pin; update the dependency train bottom-up and verify each successor before publishing a dependent package. Existing exact dependencies are retained here to avoid creating an unsatisfiable mixed graph.

Composer repository configuration is root-only. Until all packages are discoverable through Packagist, a consumer must reproduce the explicit VCS repositories from composer.json and those required by its full dependency graph. The built-archive consumer gate exercises this resolution.
