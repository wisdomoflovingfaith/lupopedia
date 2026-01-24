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
