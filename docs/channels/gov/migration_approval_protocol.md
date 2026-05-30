> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/gov/MIGRATION_APPROVAL_PROTOCOL.md"
  file_hash: "0f57d05909a11fa9393edc7a6155599e8898bbc15451d6d8defb28a6090f435b"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\gov\MIGRATION_APPROVAL_PROTOCOL.md"
  file_hash: "347d5bddc95a75663c5cab4703f633ac88e8d0d1a6e299f130176c9bbc297766"
  file_path_from_root: "docs\channels\gov\MIGRATION_APPROVAL_PROTOCOL.md"
  file_hash: "0c790031ae5198e774d8e11464577a58bb755c101c4020d472bbbee4a66b1f7a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for MIGRATION_APPROVAL_PROTOCOL.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "gov", "migration_approval_protocolmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

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
