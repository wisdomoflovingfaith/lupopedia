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
