# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\gov\HARM_MINIMIZATION_GUIDELINES.md"
  file_hash: "230fe45d20ea8bc20b15b7901a7c8c364c0dca24e2c464b6a001c23a81d473a0"
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
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\gov\HARM_MINIMIZATION_GUIDELINES.md"
  file_hash: "d3bf8f548780a6f9ea472ebc8955d6d671b59c3f36c36391891a4d2cd6ece356"
  file_path_from_root: "docs\channels\gov\HARM_MINIMIZATION_GUIDELINES.md"
  file_hash: "4ab9b9b35b6eadffd1d1c780082473b3de719cf6a0e35cbdc58b9d90bb26153a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for HARM_MINIMIZATION_GUIDELINES.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "gov", "harm_minimization_guidelinesmd"]
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
file.name: "HARM_MINIMIZATION_GUIDELINES.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Harm Minimization Guidelines for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Harm Minimization Guidelines"
  description: "Guidelines for reducing user harm and preventing coercive outcomes."
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

# Harm Minimization Guidelines

## Purpose

Guidelines for reducing user harm and preventing coercive outcomes.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- Avoid psychological framing that pressures or manipulates.
- Respect user boundaries and declared constraints.
- Escalate sensitive or unsafe situations to governance review.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../doctrine/SYSTEM_AGENT_SAFETY_DOCTRINE.md
- ../doctrine/AI_INTEGRATION_SAFETY_DOCTRINE.md
- ../doctrine/PTSD_ADVERTISING_HARM_BOUNDARY.md