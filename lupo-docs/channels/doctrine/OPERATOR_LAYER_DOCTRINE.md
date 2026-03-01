# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\OPERATOR_LAYER_DOCTRINE.md"
  file_hash: "3a3d299eedbf7c99144e2df2f833f1c4b7940863803fd9d9d49485d04cec6e51"
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
  file_path_from_root: "docs\channels\doctrine\OPERATOR_LAYER_DOCTRINE.md"
  file_hash: "e58ab0ab56beb77f68ae44f665ca4d183aa66c5161aae060ed0f636e6daf4e78"
  file_path_from_root: "docs\channels\doctrine\OPERATOR_LAYER_DOCTRINE.md"
  file_hash: "cdb0fd222adeb371c923e3d440654d9e580d71caf1606b8e39e814be1b4ad233"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for OPERATOR_LAYER_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "operator_layer_doctrinemd"]
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
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Defined operator layer doctrine and mapped operator tables to routing, escalation, and collaboration roles."
tags:
  categories: ["documentation", "doctrine"]
  collections: ["core-docs"]
  channels: ["dev", "public"]
file:
  title: "Operator Layer Doctrine"
  description: "Defines human operator roles, tables, and escalation responsibilities in the semantic OS."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Operator Layer Doctrine

## What an Operator Is

An operator is a human user (from lupo_auth_users) who is elevated into an
expert role inside one or more departments (lupo_departments). Operators are
also represented as actors in lupo_actors so they can participate in the
unified actor mesh.

## Purpose of the Operator Layer

The operator layer provides human-in-the-loop support for visitors and AI
agents. It enables AI agents to escalate decisions to humans when confidence,
safety, ethics, or complexity thresholds are reached. It also supports
multi-operator collaboration on the same dialog or channel, mirroring the
legacy Crafty Syntax live help model.

## Table Roles in the Operator Layer

### lupo_operators

Core registry of which human users are operators in which departments. It
bridges lupo_auth_users, lupo_actors, and lupo_departments, and stores basic
activation and availability status.

### lupo_operator_status

Tracks real-time presence and capacity of operators (online/offline, busy,
away, etc.). Used for routing and load balancing when assigning chats or
escalations.

### lupo_operator_sessions

Records operator login sessions into the operator console. Supports auditing,
presence inference, and historical analysis of operator activity.

### lupo_operator_skills

Describes operator skills and proficiencies (product areas, languages,
specialties). Enables intelligent routing of chats and AI escalations to the
best-suited operator.

### lupo_operator_chat_assignments

Tracks which operator is currently handling which dialog thread. Supports
multi-operator collaboration, handoff, and escalation chains.

### lupo_operator_escalation_rules

Defines department-level rules that tell AI agents when they must escalate to
a human operator (low confidence, ethical concerns, safety-sensitive decisions,
high-impact actions). This is part of the system safety and governance model.

## Operator Layer Bridge

The operator layer is the bridge between human users (lupo_auth_users), the
actor mesh (lupo_actors), departments (lupo_departments), and AI agents
(lupo_agents). Operators are the human override and collaboration layer for
AI. Agents can ask operators for help, escalate decisions, and hand off
conversations when needed.