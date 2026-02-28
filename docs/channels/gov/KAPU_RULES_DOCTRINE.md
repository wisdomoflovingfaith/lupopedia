# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\gov\KAPU_RULES_DOCTRINE.md"
  file_hash: "60e3cbe0becd3be0993171f8a5261e46f2ea4ff53cd2256907e1b60a94ed4d69"
  file_path_from_root: "docs\channels\gov\KAPU_RULES_DOCTRINE.md"
  file_hash: "6ff5ba34afe404015feefbbe9d385bfd729193cc337d5c90902022184b3ab574"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for KAPU_RULES_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "gov", "kapu_rules_doctrinemd"]
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
file.name: "KAPU_RULES_DOCTRINE.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Kapu Rules Doctrine for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Kapu Rules Doctrine"
  description: "Immutable prohibitions enforced by governance and kernel authority."
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

# Kapu Rules Doctrine

## Purpose

Immutable prohibitions enforced by governance and kernel authority.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- No foreign keys, triggers, or stored procedures in the database.
- Table count limits and schema constraints are non-negotiable.
- Prohibitions are enforced before any migration or deployment.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../doctrine/NO_FOREIGN_KEYS_DOCTRINE.md
- ../doctrine/NO_TRIGGERS_DOCTRINE.md
- ../doctrine/NO_STORED_PROCEDURES_DOCTRINE.md
- ../doctrine/TABLE_COUNT_DOCTRINE.md