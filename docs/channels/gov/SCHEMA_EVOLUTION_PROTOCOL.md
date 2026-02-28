# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\gov\SCHEMA_EVOLUTION_PROTOCOL.md"
  file_hash: "8e5348d0ff66e3b73c007c7143010f8a3efe816e89e3f31f1233e421f1a3ab56"
  file_path_from_root: "docs\channels\gov\SCHEMA_EVOLUTION_PROTOCOL.md"
  file_hash: "a800ffd7c2913bbccc2b4e57e28294eb5ebfa8c047568ada9a98724da8b175dd"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for SCHEMA_EVOLUTION_PROTOCOL.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "gov", "schema_evolution_protocolmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.name: "SCHEMA_EVOLUTION_PROTOCOL.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Schema Evolution Protocol for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Schema Evolution Protocol"
  description: "Protocol for schema changes and long-term evolution."
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

# Schema Evolution Protocol

## Purpose

Protocol for schema changes and long-term evolution.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- Schema changes must respect no-FK, no-trigger, no-procedure rules.
- TOON files must reflect schema updates.
- Schema evolution requires migration approval and logging.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../doctrine/NO_FOREIGN_KEYS_DOCTRINE.md
- ../doctrine/NO_TRIGGERS_NO_PROCEDURES_DOCTRINE.md
- ../doctrine/TOON_DOCTRINE.md