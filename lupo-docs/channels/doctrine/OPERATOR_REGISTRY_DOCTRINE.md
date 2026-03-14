# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\OPERATOR_REGISTRY_DOCTRINE.md"
  file_hash: "752ebda9d9813d1769994926d5e4b7c609fb06d8b526ddf5ee8b80c23eed49ee"
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
  file_path_from_root: "lupo-docs\channels\doctrine\OPERATOR_REGISTRY_DOCTRINE.md"
  file_hash: "4cbac8f6f40bc4e4cd818355e75e348a3362ff248a6df4fb5c3b159ac87a40b4"
  file_path_from_root: "lupo-docs\channels\doctrine\OPERATOR_REGISTRY_DOCTRINE.md"
  file_hash: "dece1bd0fd1441599ff49d651810dacea9bfe40b3de45023014a17452fd0e391"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for OPERATOR_REGISTRY_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "operator_registry_doctrinemd"]
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
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Added doctrine notes for lupo_operators and linked operator roles to departments."
tags:
  categories: ["documentation", "doctrine"]
  collections: ["core-docs"]
  channels: ["dev", "public"]
file:
  title: "Operator Registry Doctrine"
  description: "Doctrine for human operator records and departmental assignments."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Operator Registry Doctrine

## lupo_operators

lupo_operators represents authenticated human users (from lupo_auth_users)
who have been elevated into expert roles within specific departments
(lupo_departments). Operators act as human experts who can provide live help
to visitors and serve as human-in-the-loop collaborators for AI agents.
Each operator is also represented as an actor in the unified actor mesh
(lupo_actors). Operators may have availability status for routing and
escalation logic.

## lupo_departments (operator assignments)

Departments define domains of expertise. Human operators (lupo_operators)
may be assigned to departments to provide expert support, live help, and
human fallback for AI agents. Departments may have multiple operators, and
operators may serve in multiple departments.
