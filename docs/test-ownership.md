# Package test ownership and adapter conformance

The package owns portable behavior, hostile-input refusal, invariants and the sequential semantics of its public persistence ports. `tests/ownership.json` accounts for every production PHP file and every test, contract suite and fixture. `composer ownership` rejects source/test inventory drift, missing runnable suites and test-only runtime autoload entries. This inventory is a review aid; it is not a coverage metric or a substitute for executing the tests.

The abstract suite in `tests/Conformance/` can be reused by a host adapter test. Load it from the exact tagged source checkout as a test dependency, subclass it and implement its protected factory hooks against isolated storage. Do not copy the test assertions into App or add the fixtures to production autoload. Composer/Git archives intentionally exclude tests; use the matching repository tag to obtain the test kit.

The fixture implements every repository method used by the abstract suite. Factory hooks must share the same isolated records within a test and reset them between tests. The assertions observe public values and errors; the fixture deliberately implements no transaction, authorization, request, SQL or delivery service.

Host responsibility tests remain necessary for concurrent first writers, database locking and adapter parity, transaction rollback, authorization/trust, process termination, recovery and application composition. The in-memory fixture proves sequential contract expectations only. No App tests are removed by this package change.
