# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\gov\TRANSLATION_LOSS_PROTOCOLS.md"
  file_hash: "542d8d78e14a37dd293c84be323898a6a815d4280183b4378f964e6051050efc"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
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
  file_path_from_root: "docs\channels\gov\TRANSLATION_LOSS_PROTOCOLS.md"
  file_hash: "845f8a756438fecc140e2a16285fa7812fe52c2e80d595a13ae6652a24eccdcd"
  file_path_from_root: "docs\channels\gov\TRANSLATION_LOSS_PROTOCOLS.md"
  file_hash: "011bc0c3d5e3b62170557623cf4b5e933577c5052fe93009995fedc1ef39dcfa"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for TRANSLATION_LOSS_PROTOCOLS.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "gov", "translation_loss_protocolsmd"]
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
file.name: "TRANSLATION_LOSS_PROTOCOLS.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Translation Loss Protocols for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Translation Loss Protocols"
  description: "Protocols for preserving meaning across summaries, migrations, and transformations."
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

# Translation Loss Protocols

## Purpose

Protocols for preserving meaning across summaries, migrations, and transformations.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- Record source, transformation, and loss notes when summarizing.
- Critical governance content requires human review on lossy transforms.
- Never overwrite canonical doctrine with derived summaries.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../doctrine/DOCUMENTATION_DOCTRINE.md
- ../doctrine/CRITIQUE_INTEGRATION_PROTOCOL.md
- ../overview/versioning/CHANGELOG.md
