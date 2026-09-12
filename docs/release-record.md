---
schema: kumwe-package-release-record/v1
artifact_kind: framework_php
migration_id: KUMWE-MIG-2026-035
change_set: KUMWE-CS-2026-034
source:
  app:
    repository: https://github.com/kumwe/app
    baseline_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
    examined_paths:
      - src/Navigation/Domain/InvalidMenuTree.php
      - src/Navigation/Domain/MenuItem.php
      - src/Navigation/Domain/MenuTree.php
      - src/Navigation/Application/MenuItemRecord.php
      - src/Navigation/Application/MenuRecord.php
      - src/Navigation/Application/NavigationNotFound.php
      - src/Navigation/Application/NavigationRepository.php
      - src/Navigation/Application/NavigationVersionConflict.php
    old_namespace_roots:
      - Kumwe\App\Navigation\Application\
      - Kumwe\App\Navigation\Domain\
    capability_index_sha256: null
  semantic_inputs:
    - owner: https://github.com/kumwe/app
      version_or_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
      manifest_or_corpus: src/Navigation/Domain/InvalidMenuTree.php
      sha256: 45299fe6c09fb4b09b2ffb1425e0f71c8316cfcbb22ec876b30abc74c9f5ec42
    - owner: https://github.com/kumwe/app
      version_or_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
      manifest_or_corpus: src/Navigation/Domain/MenuItem.php
      sha256: 651edaa7eac4884895d9e3d79031512266c5dbee1252b3fc1deb90a8e89a7ead
    - owner: https://github.com/kumwe/app
      version_or_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
      manifest_or_corpus: src/Navigation/Domain/MenuTree.php
      sha256: f7e1b340e097ae06d84f7211f72570c1a0cd8638f3a1b346ee0e0df2e6da9025
    - owner: https://github.com/kumwe/app
      version_or_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
      manifest_or_corpus: src/Navigation/Application/MenuItemRecord.php
      sha256: 0733b5246a4ed1c1849d66be9cb63f6d44dbf5f0f297f8fe349a0b3f278bf89f
    - owner: https://github.com/kumwe/app
      version_or_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
      manifest_or_corpus: src/Navigation/Application/MenuRecord.php
      sha256: ccd1abc2ff882ae1abaebf35bfba6e354f79ff4a0f8c7aed9bc7a4739b4475fe
    - owner: https://github.com/kumwe/app
      version_or_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
      manifest_or_corpus: src/Navigation/Application/NavigationNotFound.php
      sha256: 4b4a9813ac6fe552d2fc4601bb7a21fec284cb9136b53afd32220c60163db09e
    - owner: https://github.com/kumwe/app
      version_or_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
      manifest_or_corpus: src/Navigation/Application/NavigationRepository.php
      sha256: 45d2c9508e61c9be8b9ae78c4974ad32447583e761b24a82dc0e44d4e9c48800
    - owner: https://github.com/kumwe/app
      version_or_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
      manifest_or_corpus: src/Navigation/Application/NavigationVersionConflict.php
      sha256: caf2b1f1cbefb4b06489df26c395104d23ab2702079aa39321ff5cd11d68dda6
  examined_dependencies:
    - php ^8.5
    - ext-mbstring *
target:
  repository: https://github.com/kumwe/navigation
  artifact_identity: kumwe/navigation
  canonical_namespace_or_abi: Kumwe\Navigation\
ownership:
  responsibility: Bounded deterministic navigation trees, records and persistence contracts.
  non_responsibilities:
    - authorization
    - transactions
    - persistence adapters
    - active registries
    - trust and lifecycle
    - HTTP and rendering
  allowed_dependency_ceiling:
    - php
    - ext-mbstring
  implementation_owner: kumwe/navigation
  next_consumer: kumwe/app
  public_manifests:
    - path: resources/public-api/v1.json
      sha256: fc1caa1c9b859e091d0cda6de0b5283458110ffaa6c4a8ea8e90060287dc7484
    - path: resources/capabilities/v1.json
      sha256: 84fb94a6c8d5f988b5a492c3aa2dbba6f6f4ba18c482480acbd07f86eeae922b
    - path: resources/service-map/v1.json
      sha256: 4a93bf6835f4b896af14756c6f542f50d68c7e26ce5cc28b637a65f275ff8020
    - path: resources/public-api/signature-details-v1.json
      sha256: c21a9252fb7f5bf07f3900d95a74834227a57f9a080660ede1818dc5f0a350d7
  intentionally_excluded:
    - NavigationService.php
    - PublicNavigation.php
framework_php:
  composer_package: kumwe/navigation
  canonical_namespace: Kumwe\Navigation\
  public_api_manifest: resources/public-api/v1.json
  capability_manifest: resources/capabilities/v1.json
  service_map: resources/service-map/v1.json
  extracted_symbols:
    - old_fqcn: Kumwe\App\Navigation\Domain\InvalidMenuTree
      new_fqcn: Kumwe\Navigation\Domain\InvalidMenuTree
      source_path: src/Navigation/Domain/InvalidMenuTree.php
      target_path: src/Domain/InvalidMenuTree.php
      kind: class
      public_methods: []
      public_properties: []
      public_constants: []
      compatibility: Canonical namespace ownership move; see COMPATIBILITY.md for validation changes and docs/public-api.md for exact signatures.
      exceptions: []
      serialization_contract: null
    - old_fqcn: Kumwe\App\Navigation\Domain\MenuItem
      new_fqcn: Kumwe\Navigation\Domain\MenuItem
      source_path: src/Navigation/Domain/MenuItem.php
      target_path: src/Domain/MenuItem.php
      kind: class
      public_methods:
        - create
        - id
        - title
        - slug
        - parentId
        - path
        - placedAt
      public_properties: []
      public_constants: []
      compatibility: Canonical namespace ownership move; see COMPATIBILITY.md for validation changes and docs/public-api.md for exact signatures.
      exceptions:
        - InvalidArgumentException
      serialization_contract: null
    - old_fqcn: Kumwe\App\Navigation\Domain\MenuTree
      new_fqcn: Kumwe\Navigation\Domain\MenuTree
      source_path: src/Navigation/Domain/MenuTree.php
      target_path: src/Domain/MenuTree.php
      kind: class
      public_methods:
        - create
        - id
        - item
        - items
        - move
      public_properties: []
      public_constants: []
      compatibility: Canonical namespace ownership move; see COMPATIBILITY.md for validation changes and docs/public-api.md for exact signatures.
      exceptions:
        - Kumwe\Navigation\Domain\InvalidMenuTree
        - InvalidArgumentException
      serialization_contract: null
    - old_fqcn: Kumwe\App\Navigation\Application\MenuItemRecord
      new_fqcn: Kumwe\Navigation\Application\MenuItemRecord
      source_path: src/Navigation/Application/MenuItemRecord.php
      target_path: src/Application/MenuItemRecord.php
      kind: class
      public_methods:
        - __construct
        - toArray
      public_properties:
        - id
        - menuId
        - parentId
        - title
        - slug
        - path
        - position
        - version
        - createdAt
        - updatedAt
        - targetType
        - contentId
        - targetUrl
        - template
        - colorScheme
      public_constants: []
      compatibility: Canonical namespace ownership move; see COMPATIBILITY.md for validation changes and docs/public-api.md for exact signatures.
      exceptions: []
      serialization_contract: Public toArray shape is documented in docs/public-api.md and covered by package-owned tests.
    - old_fqcn: Kumwe\App\Navigation\Application\MenuRecord
      new_fqcn: Kumwe\Navigation\Application\MenuRecord
      source_path: src/Navigation/Application/MenuRecord.php
      target_path: src/Application/MenuRecord.php
      kind: class
      public_methods:
        - __construct
        - toArray
      public_properties:
        - id
        - handle
        - title
        - version
        - createdAt
        - updatedAt
      public_constants: []
      compatibility: Canonical namespace ownership move; see COMPATIBILITY.md for validation changes and docs/public-api.md for exact signatures.
      exceptions: []
      serialization_contract: Public toArray shape is documented in docs/public-api.md and covered by package-owned tests.
    - old_fqcn: Kumwe\App\Navigation\Application\NavigationNotFound
      new_fqcn: Kumwe\Navigation\Application\NavigationNotFound
      source_path: src/Navigation/Application/NavigationNotFound.php
      target_path: src/Application/NavigationNotFound.php
      kind: class
      public_methods: []
      public_properties: []
      public_constants: []
      compatibility: Canonical namespace ownership move; see COMPATIBILITY.md for validation changes and docs/public-api.md for exact signatures.
      exceptions: []
      serialization_contract: null
    - old_fqcn: Kumwe\App\Navigation\Application\NavigationRepository
      new_fqcn: Kumwe\Navigation\Application\NavigationRepository
      source_path: src/Navigation/Application/NavigationRepository.php
      target_path: src/Application/NavigationRepository.php
      kind: interface
      public_methods:
        - menus
        - menu
        - items
        - item
        - insertMenu
        - updateMenu
        - itemIdsForMenuDeletion
        - deleteMenu
        - insertItem
        - updateItem
        - deleteItem
        - pathForParent
        - assertMoveIsAcyclic
        - moveDescendantPaths
      public_properties: []
      public_constants: []
      compatibility: Canonical namespace ownership move; see COMPATIBILITY.md for validation changes and docs/public-api.md for exact signatures.
      exceptions: []
      serialization_contract: null
    - old_fqcn: Kumwe\App\Navigation\Application\NavigationVersionConflict
      new_fqcn: Kumwe\Navigation\Application\NavigationVersionConflict
      source_path: src/Navigation/Application/NavigationVersionConflict.php
      target_path: src/Application/NavigationVersionConflict.php
      kind: class
      public_methods: []
      public_properties: []
      public_constants: []
      compatibility: Canonical namespace ownership move; see COMPATIBILITY.md for validation changes and docs/public-api.md for exact signatures.
      exceptions: []
      serialization_contract: null
  consumers:
    app_code:
      - src/Administrator/Http/Handler/AdministratorNavigationHandler.php
      - src/Administrator/Http/Handler/AdministratorSettingsHandler.php
      - src/Delivery/Console/Command/ManageNavigationCommand.php
      - src/Delivery/Http/Api/Navigation/MenuCollectionHandler.php
      - src/Delivery/Http/Api/Navigation/MenuItemCollectionHandler.php
      - src/Delivery/Http/Api/Navigation/NavigationApiResponder.php
      - src/Demo/Infrastructure/DemoContentProfileInstaller.php
      - src/Demo/Infrastructure/DemoProfileExporter.php
      - src/Infrastructure/Mcp/KumweMcpHandlers.php
      - src/Infrastructure/Mcp/McpToolErrorVocabulary.php
      - src/Kernel/ContainerFactory.php
      - src/Navigation/Infrastructure/Persistence/DoctrineNavigationRepository.php
    configuration_and_di: []
    reflection_and_string_references: []
    fixtures_and_examples: []
    external: []
  dependency_injection:
    mode: direct
    provider: null
    factories: []
    aliases: []
    service_lifetimes: []
    configuration_keys: []
    provider_absence_reason: Values, ports and deterministic stateless algorithms capture no collaborator or ambient state.
native_cpp: null
php_extension: null
tests:
  moved_or_added:
    - tests/NavigationRepositoryConformanceTest.php
    - tests/Conformance/
    - tests/Fixture/
    - tests/ownership.json
    - tests/Domain/MenuItemTest.php
    - tests/Domain/MenuTreeTest.php
    - tests/Domain/PlacementBoundaryTest.php
  remain_in_app_or_consumer:
    - tests/Support/customize-demo-profile.php
    - tests/Unit/Content/Presentation/TranslationGroupPresenterTest.php
    - tests/Unit/Http/Handler/HomePageHandlerTest.php
    - tests/Unit/Http/Handler/PublishedContentHandlerTest.php
    - tests/Unit/Navigation/Application/NavigationServiceTest.php
    - tests/Unit/Navigation/Application/PublicNavigationTest.php
    - tests/Unit/Site/Application/PublicPageLocatorTest.php
  split_tests: []
  prohibited_duplicates:
    - tests/Unit/Navigation/Domain/MenuItemTest.php
    - tests/Unit/Navigation/Domain/MenuTreeTest.php
  corpora:
    - tests/Domain/MenuItemTest.php
    - tests/Domain/MenuTreeTest.php
    - tests/Domain/PlacementBoundaryTest.php
documentation:
  charter: CHARTER.md
  readme: README.md
  public_api: docs/public-api.md
  architecture: docs/architecture.md
  integration_or_consumer: docs/integration.md
  examples:
    - examples/standalone.php
  changelog_record: CHANGELOG.md#0.1.1
release_expectations:
  version_policy: SemVer; 0.1.1 candidate source release record, published baseline 0.1.0. Exact consumer pins follow independent artifact verification.
  expected_artifact_types:
    - Composer ZIP
  required_checks:
    - "@composer:validate"
    - "@lint"
    - "@api"
    - "@architecture"
    - "@analyse"
    - "@cs"
    - "@test"
    - "@examples"
    - "@security"
    - "@clean-consumer"
    - "@manifests"
  required_registry_or_installer: Composer
  required_external_attestation: true
governance:
  completion_claim: false
decisions:
  - Canonical namespace move; no aliases or dual production ownership after adoption
  - See COMPATIBILITY.md for initial bounded-input decision
  - Full original source digests remain in docs/source-map.json; governed extracted-symbol inventory uses the exact v2 schema.
  - Owner package contracts are implemented; App adoption and legacy deletion remain a separate consumer phase.
blockers: []
consumer_contract:
  permitted_only_when:
    - Verify the published package and exact dependency identities before consumer deployment.
  consumer_repository: https://github.com/kumwe/app
  dependency_or_native_change: Compose the verified package through its documented public API and host-owned services.
  namespace_or_api_replacements: []
  files_to_update: []
  files_to_remove: []
  tests_to_remove: []
  tests_to_retain_or_add:
    - tests/Support/customize-demo-profile.php
    - tests/Unit/Content/Presentation/TranslationGroupPresenterTest.php
    - tests/Unit/Http/Handler/HomePageHandlerTest.php
    - tests/Unit/Http/Handler/PublishedContentHandlerTest.php
    - tests/Unit/Navigation/Application/NavigationServiceTest.php
    - tests/Unit/Navigation/Application/PublicNavigationTest.php
    - tests/Unit/Site/Application/PublicPageLocatorTest.php
  di_or_provisioning_changes:
    - No provider or factories; retain host services and bind host persistence ports explicitly.
  capability_index_changes:
    - Core maintains its current dependency and capability inventory.
  changelog_and_evidence_changes:
    - Record enabling-refactor; completion_claim false
  verification_commands:
    - composer check
    - composer clean-consumer
---

# Navigation release record

## Package contract

Navigation owns bounded deterministic trees, records and persistence contracts. The [Core contract](core-contract.md) defines the host boundary.
Retained migration/change-set IDs identify independent attestations and historical source ownership.

## Public API and responsibility

The [public API](public-api.md), [charter](../CHARTER.md) and canonical public manifests define the supported
package surface. Core retains authorization, transactions, persistence and runtime lifecycle responsibilities.

## Dependencies and semantic inputs

[Composer metadata](../composer.json) declares runtime dependencies. The machine record preserves exact semantic
inputs, public manifest hashes and source provenance. Coordinates do not self-attest independent verification.

## Consumer contract

Use the documented API and host-supplied services. The package never acquires authority from metadata or port
selection. See [integration](integration.md) and the [Core contract](core-contract.md).

## Test ownership

Package-owned tests enforce behavior, boundaries, malformed-input refusals and conformance. Core retains its
composition, storage, authority, deployment and recovery coverage. Test ownership remains explicit.

## Consumer verification

Verify the published artifact, exact dependency identities and authoritative no-dev consumer before deployment.
Package publication and source CI do not establish Core integration or production workload acceptance.

## Compatibility and drift

Preserve public signatures, wire shapes, semantic ownership and dependency boundaries. Refresh declared public
manifest hashes with reviewed contract changes. Final publication identities belong in external evidence.

## Validation

Run the complete composer check command and release automation regressions. The shared PR and default-branch
workflow validates the tested source, including package-owned tests and the built archive's no-dev consumer.
See [releasing](releasing.md) for versioning, immutable tag handling and publication evidence.
