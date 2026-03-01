# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\gov\IDENTITY_AND_CHANNEL_PROVENANCE_RULES.md"
  file_hash: "3ce0c51a0fb0d1dd032e7573ab022adc584acda81904cc170372b9d26228d892"
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
  file_path_from_root: "docs\channels\gov\IDENTITY_AND_CHANNEL_PROVENANCE_RULES.md"
  file_hash: "9564405e5267b15f0c35483a14dd97b19713fd03dc0ab63ea3369092c449f59b"
  file_path_from_root: "docs\channels\gov\IDENTITY_AND_CHANNEL_PROVENANCE_RULES.md"
  file_hash: "e9ca70b41fffaad70a2c236ae29d83d3ecfcbb55e530f39b6a75dc0febf24f30"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for IDENTITY_AND_CHANNEL_PROVENANCE_RULES.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "gov", "identity_and_channel_provenance_rulesmd"]
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
file.name: "IDENTITY_AND_CHANNEL_PROVENANCE_RULES.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Identity and Channel Provenance Rules for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Identity and Channel Provenance Rules"
  description: "Rules for preserving channel identity and provenance over time."
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

# Identity and Channel Provenance Rules

## Purpose

Rules for preserving channel identity and provenance over time.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- Channel identity is defined by the Channel Identity Block.
- Manifests and timestamps must remain traceable and consistent.
- Provenance changes require explicit governance record.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../agents/agent-1/doctrine/CHANNEL_IDENTITY_BLOCK.md
- ../agents/agent-1/doctrine/CHANNEL_MANIFEST_SPEC.md
- ../doctrine/TIMESTAMP_DOCTRINE.md