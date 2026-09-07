# Architecture

Bounded deterministic navigation trees, records and persistence contracts.

Source provenance is recorded in [source-map.json](source-map.json). The package has no App or Extension SDK production dependency. Ports define persistence requirements; concrete implementations remain host-owned. No global state, DI registration or alternate host is introduced.

NavigationService, PublicNavigation, permission-aware registries and Doctrine adapters remain App-owned. The tree derives paths without evaluating permissions or resolving routes. No Content dependency is required by the current closure.
