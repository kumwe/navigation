# Migration handoff

```yaml
schema: kumwe-migration-handoff/v2
artifact_kind: framework_php
migration_id: KUMWE-MIG-2026-035
change_set: KUMWE-CS-2026-034
state: draft_pr_open
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
    - Kumwe\App\Navigation\Application
    - Kumwe\App\Navigation\Domain
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
    php: ^8.5
    ext-mbstring: '*'
  active_related_pull_requests: []
target:
  repository: https://github.com/kumwe/navigation
  artifact_identity: kumwe/navigation
  canonical_namespace_or_abi: Kumwe\Navigation\
  branch: agent/extract-navigation-runtime-v2
  pull_request: https://github.com/kumwe/navigation/pull/2
ownership:
  responsibility: Bounded deterministic navigation trees, records and persistence
    contracts.
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
    sha256: f52b95c2b081f2244be1e9e6ae56f89b2c212aad0b1c31d4bbb2b77659e8562a
  - path: resources/capabilities/v1.json
    sha256: 8310aeb766cf6547cfc1202537d8b31009c96cb2397e2a0d99aec83da4042a81
  - path: resources/service-map/v1.json
    sha256: 17162675e4eabb37db40a52b05e4cdb7e3159c3809b9b493c3ef14045dbca648
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
  - repository: https://github.com/kumwe/app
    old_fqcn: Kumwe\App\Navigation\Domain\InvalidMenuTree
    new_fqcn: Kumwe\Navigation\Domain\InvalidMenuTree
    source_path: src/Navigation/Domain/InvalidMenuTree.php
    target_path: src/Domain/InvalidMenuTree.php
    source_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
    source_sha256: 45299fe6c09fb4b09b2ffb1425e0f71c8316cfcbb22ec876b30abc74c9f5ec42
    kind: class
    public_methods: []
    public_properties: []
    public_constants: []
    compatibility: namespace ownership move; see COMPATIBILITY.md
  - repository: https://github.com/kumwe/app
    old_fqcn: Kumwe\App\Navigation\Domain\MenuItem
    new_fqcn: Kumwe\Navigation\Domain\MenuItem
    source_path: src/Navigation/Domain/MenuItem.php
    target_path: src/Domain/MenuItem.php
    source_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
    source_sha256: 651edaa7eac4884895d9e3d79031512266c5dbee1252b3fc1deb90a8e89a7ead
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
    compatibility: namespace ownership move; see COMPATIBILITY.md
  - repository: https://github.com/kumwe/app
    old_fqcn: Kumwe\App\Navigation\Domain\MenuTree
    new_fqcn: Kumwe\Navigation\Domain\MenuTree
    source_path: src/Navigation/Domain/MenuTree.php
    target_path: src/Domain/MenuTree.php
    source_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
    source_sha256: f7e1b340e097ae06d84f7211f72570c1a0cd8638f3a1b346ee0e0df2e6da9025
    kind: class
    public_methods:
    - create
    - id
    - item
    - items
    - move
    public_properties: []
    public_constants: []
    compatibility: namespace ownership move; see COMPATIBILITY.md
  - repository: https://github.com/kumwe/app
    old_fqcn: Kumwe\App\Navigation\Application\MenuItemRecord
    new_fqcn: Kumwe\Navigation\Application\MenuItemRecord
    source_path: src/Navigation/Application/MenuItemRecord.php
    target_path: src/Application/MenuItemRecord.php
    source_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
    source_sha256: 0733b5246a4ed1c1849d66be9cb63f6d44dbf5f0f297f8fe349a0b3f278bf89f
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
    compatibility: namespace ownership move; see COMPATIBILITY.md
  - repository: https://github.com/kumwe/app
    old_fqcn: Kumwe\App\Navigation\Application\MenuRecord
    new_fqcn: Kumwe\Navigation\Application\MenuRecord
    source_path: src/Navigation/Application/MenuRecord.php
    target_path: src/Application/MenuRecord.php
    source_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
    source_sha256: ccd1abc2ff882ae1abaebf35bfba6e354f79ff4a0f8c7aed9bc7a4739b4475fe
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
    compatibility: namespace ownership move; see COMPATIBILITY.md
  - repository: https://github.com/kumwe/app
    old_fqcn: Kumwe\App\Navigation\Application\NavigationNotFound
    new_fqcn: Kumwe\Navigation\Application\NavigationNotFound
    source_path: src/Navigation/Application/NavigationNotFound.php
    target_path: src/Application/NavigationNotFound.php
    source_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
    source_sha256: 4b4a9813ac6fe552d2fc4601bb7a21fec284cb9136b53afd32220c60163db09e
    kind: class
    public_methods: []
    public_properties: []
    public_constants: []
    compatibility: namespace ownership move; see COMPATIBILITY.md
  - repository: https://github.com/kumwe/app
    old_fqcn: Kumwe\App\Navigation\Application\NavigationRepository
    new_fqcn: Kumwe\Navigation\Application\NavigationRepository
    source_path: src/Navigation/Application/NavigationRepository.php
    target_path: src/Application/NavigationRepository.php
    source_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
    source_sha256: 45d2c9508e61c9be8b9ae78c4974ad32447583e761b24a82dc0e44d4e9c48800
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
    compatibility: namespace ownership move; see COMPATIBILITY.md
  - repository: https://github.com/kumwe/app
    old_fqcn: Kumwe\App\Navigation\Application\NavigationVersionConflict
    new_fqcn: Kumwe\Navigation\Application\NavigationVersionConflict
    source_path: src/Navigation/Application/NavigationVersionConflict.php
    target_path: src/Application/NavigationVersionConflict.php
    source_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
    source_sha256: caf2b1f1cbefb4b06489df26c395104d23ab2702079aa39321ff5cd11d68dda6
    kind: class
    public_methods: []
    public_properties: []
    public_constants: []
    compatibility: namespace ownership move; see COMPATIBILITY.md
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
    provider_absence_reason: Values, ports and deterministic stateless algorithms
      capture no collaborator or ambient state.
native_cpp: null
php_extension: null
tests:
  moved_or_added:
  - tests/Domain/MenuItemTest.php
  - tests/Domain/MenuTreeTest.php
  remain_in_app_or_consumer:
  - tests/Support/customize-demo-profile.php
  - tests/Unit/Content/Presentation/TranslationGroupPresenterTest.php
  - tests/Unit/Http/Handler/HomePageHandlerTest.php
  - tests/Unit/Http/Handler/PublishedContentHandlerTest.php
  - tests/Unit/Navigation/Application/NavigationServiceTest.php
  - tests/Unit/Navigation/Application/PublicNavigationTest.php
  - tests/Unit/Site/Application/PublicPageLocatorTest.php
  split_tests: []
  prohibited_duplicates: &id001
  - tests/Unit/Navigation/Domain/MenuItemTest.php
  - tests/Unit/Navigation/Domain/MenuTreeTest.php
  corpora:
  - path: tests/Domain/MenuItemTest.php
    sha256: c12dbfb46e61c16fd169b8088fb6cc7d8847b1a18ce9413031367da46527a529
  - path: tests/Domain/MenuTreeTest.php
    sha256: 5cd3e6e070cb84ced503645a695ab7a4458dac5afd1001629ba9684b8b1d86e1
documentation:
  charter: CHARTER.md
  readme: README.md
  public_api: docs/public-api.md
  architecture: docs/architecture.md
  integration_or_consumer: docs/integration.md
  examples:
  - examples/standalone.php
  changelog_record: CHANGELOG.md / 0.1.0
release_expectations:
  version_policy: SemVer; initial version 0.1.0 recorded for human merge; exact pre-1.0
    consumer pin after independent verification
  expected_artifact_types:
  - Composer ZIP
  required_checks:
  - '@composer:validate'
  - '@lint'
  - '@api'
  - '@architecture'
  - '@analyse'
  - '@cs'
  - '@test'
  - '@examples'
  - '@security'
  - '@clean-consumer'
  required_registry_or_installer: Composer
  required_external_attestation: false
next_task:
  phase_name: Independent release verification, followed by separately authorized
    App Phase 2
  permitted_only_when:
  - Human review and merge
  - All dependencies and this release independently attested
  - Current App drift reconciled upstream
  consumer_repository: https://github.com/kumwe/app
  dependency_or_native_change: Exact-pin independently verified immutable package;
    no adoption of development branches
  namespace_or_api_replacements:
  - old: Kumwe\App\Navigation\Domain\InvalidMenuTree
    new: Kumwe\Navigation\Domain\InvalidMenuTree
  - old: Kumwe\App\Navigation\Domain\MenuItem
    new: Kumwe\Navigation\Domain\MenuItem
  - old: Kumwe\App\Navigation\Domain\MenuTree
    new: Kumwe\Navigation\Domain\MenuTree
  - old: Kumwe\App\Navigation\Application\MenuItemRecord
    new: Kumwe\Navigation\Application\MenuItemRecord
  - old: Kumwe\App\Navigation\Application\MenuRecord
    new: Kumwe\Navigation\Application\MenuRecord
  - old: Kumwe\App\Navigation\Application\NavigationNotFound
    new: Kumwe\Navigation\Application\NavigationNotFound
  - old: Kumwe\App\Navigation\Application\NavigationRepository
    new: Kumwe\Navigation\Application\NavigationRepository
  - old: Kumwe\App\Navigation\Application\NavigationVersionConflict
    new: Kumwe\Navigation\Application\NavigationVersionConflict
  files_to_update:
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
  - tests/Support/customize-demo-profile.php
  - tests/Unit/Content/Presentation/TranslationGroupPresenterTest.php
  - tests/Unit/Http/Handler/HomePageHandlerTest.php
  - tests/Unit/Http/Handler/PublishedContentHandlerTest.php
  - tests/Unit/Navigation/Application/NavigationServiceTest.php
  - tests/Unit/Navigation/Application/PublicNavigationTest.php
  - tests/Unit/Site/Application/PublicPageLocatorTest.php
  - composer.json
  - composer.lock
  files_to_remove:
  - src/Navigation/Domain/InvalidMenuTree.php
  - src/Navigation/Domain/MenuItem.php
  - src/Navigation/Domain/MenuTree.php
  - src/Navigation/Application/MenuItemRecord.php
  - src/Navigation/Application/MenuRecord.php
  - src/Navigation/Application/NavigationNotFound.php
  - src/Navigation/Application/NavigationRepository.php
  - src/Navigation/Application/NavigationVersionConflict.php
  tests_to_remove: *id001
  tests_to_retain_or_add:
  - tests/Support/customize-demo-profile.php
  - tests/Unit/Content/Presentation/TranslationGroupPresenterTest.php
  - tests/Unit/Http/Handler/HomePageHandlerTest.php
  - tests/Unit/Http/Handler/PublishedContentHandlerTest.php
  - tests/Unit/Navigation/Application/NavigationServiceTest.php
  - tests/Unit/Navigation/Application/PublicNavigationTest.php
  - tests/Unit/Site/Application/PublicPageLocatorTest.php
  di_or_provisioning_changes:
  - No provider or factories; retain host services and bind host persistence ports
    explicitly.
  capability_index_changes:
  - Replace implementation owner with exact verified package manifest
  changelog_and_evidence_changes:
  - Record enabling-refactor; completion_claim false
  verification_commands:
  - composer validate --strict
  - composer check
  - Applicable App integration, database, authority and delivery tests
concurrency:
  likely_conflict_files:
  - composer.json
  - composer.lock
  related_migrations:
  - access-context
  - access-control
  - contribution
  - localization
  ownership_conflicts: []
  integration_train: null
  resolution_rule: semantic-preservation
governance:
  roadmap_source_sha256: a202155ef1a65f5ab293d4f8397ebf4ac430db7f1e877c776bbe7851e6fe18d8
  roadmap_refs: []
  non_roadmap_refs:
  - NRM-2026-035
  completion_claim: false
decisions:
- Canonical namespace move; no aliases or dual production ownership after adoption
- See COMPATIBILITY.md for initial bounded-input decision
blockers:
- Human review and merge of the 0.1.0 release record; automatic publication follows the package gate
- Independent release verification and App adoption remain separate follow-up work
```
