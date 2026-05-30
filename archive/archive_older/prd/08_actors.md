---
lupopedia.headers:
  header_format_version: "4.0.98"
  lupopedia.schema: prd
  when_updated: "20260410130550"
  file_path_from_root: "archive/prd/08_actors.md"
  web_path: "http://www.lupopedia.com/lupopedia/archive/prd/08_actors.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "memory/development/canonical/2026/04/actors.toon"
  artifact_type: prd
  artifact_kind: specification
  thread_id: ""
  content_id: null
  pk_id: 8
  pk_slug: "08-actors"
  title: "PRD: Actor Identity, Inheritance, and Personalization"
  status: "archived"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: "0/development/prd_files/actors"
---
> **SUPERSEDED:** Canonical actor PRD is [`15_actors.md`](../../docs/prd/15_actors.md). This file is retained for historical reference only.

# PRD: Actor Identity, Inheritance, and Personalization

## Overview

This document defines the canonical model for **actors** in Lupopedia. Actors are department- and persona-specific extensions of agents, providing a personalized, scoped execution and orchestration identity for each user and department context.

## 1. Actor as Department/Persona-Specific Agent Extension
- An **actor** is always created as an extension of a specific agent.
- The actor is aware of the agent it extends (agent_key, agent_id) and maintains a persistent reference to it.
- The actor's identity is unique and department/persona-scoped.

## 2. Inheritance and Personalization of Agent Resources
- Actors inherit all modular resources from their agent:
  - `api/`, `assets/`, `components/`, `context/`, `data/`, `hooks/`, `pages/`, `includes/`, `tools/`, `utils/` (and any future modular folders)
- Actors may personalize, override, or extend any inherited resource within their own scope.
- The actor's resource tree is a superset of the agent's, with actor-specific overrides taking precedence.

## 3. One-Auth_User-at-a-Time Lease Rule
- Only one `auth_user` may extend (lease/control) a given actor at any time.
- The lease is exclusive: no concurrent control or impersonation is permitted.
- Lease state is tracked in the actor's metadata and enforced at the orchestration layer.

## 4. Department-Based Personalization and Scoping
- Each actor is further personalized by the department context of the user(s) using it.
- Department membership determines available features, permissions, and resource overrides.
- Department context is immutable for the lifetime of the actor instance.

## 5. Actor Lifecycle
- Creation: An actor is instantiated by an `auth_user` selecting an agent and department context.
- Personalization: The actor inherits agent resources and applies department/user-specific overrides.
- Lease: The actor is leased to the creating `auth_user` until explicitly released or reassigned.
- Termination: The actor is deleted or archived when no longer needed.
