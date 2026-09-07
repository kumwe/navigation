# Releasing

The newest stable heading in CHANGELOG.md records the release version. Navigation
starts at 0.1.0. Maintainers review and rebase-merge the release PR into the default
branch. No manual setup command is required.

The release workflow reuses the complete package CI at the actual post-rebase
commit: syntax, API, governed-manifest and architecture checks, maximum-level static analysis,
coding standards, package-owned behavior tests, examples, security audit, and
the isolated no-dev archive consumer. Package gate also requires the common
release automation regression tests. A failure prevents publication.

After that gate succeeds, the publisher creates the exact version tag at the
tested commit and publishes the GitHub release in the same workflow. Composer
consumers use the semantic tag and registry source archive. Existing tags and
releases are never changed. Re-running the workflow verifies an existing release;
an unfinished tag is publishable only when it matches the tested commit.

Release identity comes from the event commit, never the former PR head. Publication
is queued per branch. Regression fixtures cover real rebases, annotated and
ancestor tags, retries, source mismatches, API failures, and optional platform
settings. Run bash tools/test-release-record.sh, bash tools/test-release-integrity.sh,
and bash tools/test-release-on-record.sh when changing those helpers.

Normal publishing does not require branch protection, an immutable-release
setting, or an external attestation. Repository permissions remain effective.
Publication is distinct from independent artifact verification and App adoption.
Confirm the published tag/source and clean consumer result before the separate
App integration round. Do not claim a release exists until GitHub publishes it.
