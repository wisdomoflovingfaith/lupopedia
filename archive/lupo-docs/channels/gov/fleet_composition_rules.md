# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/gov/FLEET_COMPOSITION_RULES.md"
  file_hash: "f3a7d74b3cae7e818bf940ba9faf1152d3f0340e499d05b765ddd88a348afa47"
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
  file_path_from_root: "lupo-docs\channels\gov\FLEET_COMPOSITION_RULES.md"
  file_hash: "5ab5b90b0d8d1baa19341ccca8473fcb9373761df841d86cbde491407c5e1500"
  file_path_from_root: "lupo-docs\channels\gov\FLEET_COMPOSITION_RULES.md"
  file_hash: "712f7c8f7259f7ca49dcf922183fb78e3d39f19ed02eb98afee4da3fe9645952"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLEET_COMPOSITION_RULES.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "gov", "fleet_composition_rulesmd"]
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
file.name: "FLEET_COMPOSITION_RULES.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Fleet Composition Rules for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Fleet Composition Rules"
  description: "Rules governing the agent fleet assigned to a channel."
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

# Fleet Composition Rules

## Purpose

Rules governing the agent fleet assigned to a channel.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- Fleet composition is defined by channel metadata and AAL rules.
- Only registered agents may be assigned to governance-sensitive channels.
- Changes to fleet composition require audit logging.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../doctrine/AGENT_CLASSIFICATION.md
- ../doctrine/AGENT_AWARENESS_DOCTRINE.md
- ../doctrine/METADATA_GOVERNANCE.md
