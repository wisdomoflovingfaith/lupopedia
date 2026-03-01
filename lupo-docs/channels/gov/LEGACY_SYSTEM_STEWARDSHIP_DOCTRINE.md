# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\gov\LEGACY_SYSTEM_STEWARDSHIP_DOCTRINE.md"
  file_hash: "4f24415c59538155b74a87c267c0cef5a7872292f3763cfcbdaccc61d586e712"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\gov\LEGACY_SYSTEM_STEWARDSHIP_DOCTRINE.md"
  file_hash: "324aeb9d019a05f71dd498274e7572497b60142982acee855981e9f015486c08"
  file_path_from_root: "docs\channels\gov\LEGACY_SYSTEM_STEWARDSHIP_DOCTRINE.md"
  file_hash: "876739bb1ea1437f909f13cff0d7605621302a93277e5bbcad3b4ed341bc7dad"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for LEGACY_SYSTEM_STEWARDSHIP_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "gov", "legacy_system_stewardship_doctrinemd"]
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
file.name: "LEGACY_SYSTEM_STEWARDSHIP_DOCTRINE.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Legacy System Stewardship Doctrine for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Legacy System Stewardship Doctrine"
  description: "Rules for stewarding legacy systems and migrations."
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

# Legacy System Stewardship Doctrine

## Purpose

Rules for stewarding legacy systems and migrations.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- Preserve legacy behavior unless doctrine requires change.
- Document all legacy mapping and refactor decisions.
- Legacy data must remain auditable and reversible.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../doctrine/CRAFTY_SYNTAX_MIGRATION_DOCTRINE.md
- ../doctrine/MIGRATION_DOCTRINE.md
- ../overview/migrations/CRAFTY_SYNTAX_3_5_5_TO_LUPOPEDIA.md.txt