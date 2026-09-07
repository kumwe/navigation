# Governance schema provenance

These unmodified capability and service-map schema snapshots come from kumwe/app at commit 24ecf956423c18933e824b43cea1bfb9127a79a9, docs/architecture/governance/schemas. They are package-owned development validation inputs; runtime code has no App dependency. The manifest gate checks the supported schema keyword set and rejects unsupported additions. Public API reflection and its governed JSON generation are checked separately by tools/public-api.php. The full handoff was additionally checked read-only through the actual App PackageManifests parser.
