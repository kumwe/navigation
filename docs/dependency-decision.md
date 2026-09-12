# Dependency status

Navigation requires PHP 8.5 and mbstring. It has no Kumwe runtime dependency: public records and repository ports
carry scalar identities and neutral tree values. [Composer metadata](../composer.json) is authoritative.

The package is available through Packagist without root VCS overrides. Pre-1.0 consumers pin an exact verified
Navigation version. Review any new dependency against the portable ownership boundary and validate the complete
resolved graph and built-archive no-dev consumer before publication.

`composer dependency-readiness` verifies that dependency evidence agrees with the declared requirements,
including the empty Kumwe dependency set. Independent artifact verification and Core acceptance remain separate.
