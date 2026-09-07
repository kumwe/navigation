# navigation

Bounded deterministic navigation trees, records and persistence contracts.

Requires PHP 8.5 and the runtime dependencies in `composer.json`. The canonical namespace is `Kumwe\Navigation\`. The initial 0.1.0 release is recorded for publication after human merge and the complete package gate. Verify the published release before separate App adoption.

Run `composer install`, `composer check`, and `composer examples`. [Public API](docs/public-api.md), [architecture](docs/architecture.md), [integration](docs/integration.md), and [release protocol](docs/releasing.md) describe the contract.

The package has no ConfigProvider. Values are constructed directly; ports are supplied by the host. Deterministic pure operations do not capture a site, actor, request, connection or container. App owns authorization, transactions, persistence, dispatch and presentation.

Released consumers exact-pin pre-1.0 versions. Apache-2.0; inherited source behavior is preserved except the explicitly documented bounded-input decisions.
