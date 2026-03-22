# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/gov/MOOD_FRAMEWORK_GOVERNANCE.md"
  file_hash: "3f806cdf457495ff3eb5ffe2d689c83cd7a9de8ea421fbe53af1e0be5bd0d76f"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "lupo-docs\channels\gov\MOOD_FRAMEWORK_GOVERNANCE.md"
  file_hash: "1375a490f6bbf9e1e4827c526e375c32ae62d01fd4c009c877802e3b3cca3dbc"
  file_path_from_root: "lupo-docs\channels\gov\MOOD_FRAMEWORK_GOVERNANCE.md"
  file_hash: "7fcb67a62cf13a27cc7cad648734e6c480aada088f06d09bde552041ef6e60d5"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for MOOD_FRAMEWORK_GOVERNANCE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "gov", "mood_framework_governancemd"]
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
file.name: "MOOD_FRAMEWORK_GOVERNANCE.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Mood Framework Governance for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Mood Framework Governance"
  description: "Governance rules for mood frameworks and RGB standards."
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

# Mood Framework Governance

## Purpose

Governance rules for mood frameworks and RGB standards.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- Mood RGB outputs must follow the Mood RGB doctrine.
- Mood framework changes require versioned updates and documentation.
- Thread-level mood data is owned by threads, not channels.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../doctrine/MOOD_RGB_DOCTRINE.md
- ../doctrine/EMOTIONAL_ENGINE_SPECIFICATION_v2_0.md
- ../doctrine/DIALOG_DOCTRINE.md
