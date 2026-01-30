---
wolfie.headers: explicit architecture with structured clarity for every file.
file.name: "MIGRATION_APPROVAL_PROTOCOL.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Migration Approval Protocol for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Migration Approval Protocol"
  description: "Approval gates for database and structural migrations."
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

# Migration Approval Protocol

## Purpose

Approval gates for database and structural migrations.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- Migrations must align with schema prohibitions and table limits.
- Approval requires review against migration doctrine and governance rules.
- All migrations must be logged and reversible when possible.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../doctrine/MIGRATION_DOCTRINE.md
- ../doctrine/MIGRATION_ORCHESTRATOR_DOCTRINE.md
- ../doctrine/TABLE_COUNT_DOCTRINE.md
