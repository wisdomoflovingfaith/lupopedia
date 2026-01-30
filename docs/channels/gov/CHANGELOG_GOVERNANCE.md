---
wolfie.headers: explicit architecture with structured clarity for every file.
file.name: "CHANGELOG_GOVERNANCE.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Changelog Governance for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Changelog Governance"
  description: "Rules for maintaining the canonical changelog."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
in_this_file_we_have:
  - Purpose
  - Scope
  - Governance Rules
  - Enforcement
  - References
---

# Changelog Governance

## Purpose

Rules for maintaining the canonical changelog.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- Every governance change must be logged in the changelog.
- Entries must include what changed, why, and impact.
- Changelog is immutable history, not a rewrite target.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../overview/versioning/CHANGELOG.md
- ../overview/logs/changelog_dialog.md
- ../doctrine/VERSIONING_DOCTRINE.md
