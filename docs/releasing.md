# Release protocol

This candidate contains no release record and is publication-blocked. After review and dependency release verification, record the intended SemVer version in CHANGELOG.md and run the identical package lane for PR and default-branch checks. Only a maintainer merge may trigger release-on-record; agents do not create tags. Existing tags and releases are immutable.

The built Composer archive must install into a fresh no-dev authoritative-classmap consumer and execute the documented example. A separate verification session records the actual source SHA, archive digests, dependency identities, manifests, registry coordinate and gate evidence outside the source tree. Publication alone does not authorize App adoption. Roll back consumers by exact-pinning an earlier verified release; never move tags. Report security issues through repository security reporting.
