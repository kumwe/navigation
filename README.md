# Kumwe Navigation

[![Packagist version][version-badge]][packagist]
[![Package CI][ci-badge]][ci]
[![PHP requirement][php-badge]](composer.json)
[![License: Apache-2.0][license-badge]](LICENSE)

Bounded deterministic navigation trees, records and persistence contracts under `Kumwe\Navigation\`.
The package owns immutable tree operations, stable paths, scoped repository contracts and explicit version
conflicts. Core supplies persistence, authorization, transactions and rendering.

## Installation and usage

```sh
composer require kumwe/navigation:0.1.2
```

Requires PHP 8.5 and mbstring. Navigation has no other Kumwe runtime dependency. Construct values directly and
supply host repository ports; no ConfigProvider, global locator or captured request context is registered.

See the [standalone example](examples/standalone.php), [public API](docs/public-api.md),
[Core contract](docs/core-contract.md) and [integration](docs/integration.md).

## Compatibility

Trees admit at most 1,024 items and paths at most 64 items. Oversized input fails before unbounded traversal.
Parent UUID normalization, UTF-8 titles, bounded derived paths, stable ordering and immutable moves remain part
of the public contract. Repository adapters preserve scope, optimistic versioning and exact cascade ownership.
See [compatibility](COMPATIBILITY.md) and [repository guarantees](docs/readiness-review.md).

Pre-1.0 consumers pin exact verified versions. Publication and source CI are separate from independent consumer
verification and Core deployment acceptance. [Dependency status](docs/dependency-decision.md) records the minimal
runtime closure; the live badges above report published versions and default-branch CI.

## Development

```sh
composer install
composer check
composer examples
```

The package gate checks syntax, generated API/manifests, architecture, static analysis, coding standards,
dependency readiness, behavior/conformance ownership, examples, security and a fresh no-dev archive consumer.
See [test ownership](docs/test-ownership.md), [release process](docs/releasing.md),
[release record](docs/release-record.md) and [security](SECURITY.md).

[version-badge]: https://img.shields.io/packagist/v/kumwe/navigation
[packagist]: https://packagist.org/packages/kumwe/navigation
[ci-badge]: https://github.com/kumwe/navigation/actions/workflows/ci.yml/badge.svg?branch=main
[ci]: https://github.com/kumwe/navigation/actions/workflows/ci.yml
[php-badge]: https://img.shields.io/packagist/dependency-v/kumwe/navigation/php
[license-badge]: https://img.shields.io/github/license/kumwe/navigation
