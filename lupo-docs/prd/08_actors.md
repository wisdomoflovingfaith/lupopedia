---
lupopedia.headers:
  lupopedia.schema: prd
  file_path_from_root: lupo-docs/prd/08_actors.md
  last_modified_utc: '20260331000000'
  artifact_type: prd
  artifact_kind: specification
  purpose: Actor identity, inheritance, and personalization model
  traits:
    - superseded
    - actors
    - agent-extension
    - department-scope
    - one-to-one-auth-user
  tags:
    - tag-actors
    - tag-agents
    - tag-identity
    - tag-department
    - tag-personalization
    - tag-modularity
  web_path: http://www.lupopedia.com/lupo-docs/prd/08_actors.md
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
---

> **SUPERSEDED:** Canonical actor PRD is [`15_actors.md`](15_actors.md). This file is retained for historical reference only.

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

## 6. Actor Folder Structure and Registry

### 6.1 Canonical Folder Paths

- **Core actors** (actor_id < 2026): Must live in `lupo-actors/<actor_id>/`
- **Runtime actors** (actor_id >= 20260101010101): Must live in `lupo-actors/YYYY/MM/<actor_id>/`
  - YYYY/MM extracted from the timestamp ID (e.g., actor_id `202603311735001234` → `lupo-actors/2026/03/202603311735001234/`)

### 6.2 Legacy Named Folder Deprecation

- **Slug-only** folder names as direct children of `lupo-actors/` (e.g. `wolfie/`, `lilith/`) are **DEPRECATED**; canonical hubs are **`lupo-actors/{actor_id}/`** (e.g. `lupo-actors/1/`, `lupo-actors/2/`)
- The registry must always reference numeric actor_id folders
- Existing slug-named folders must be migrated to numeric paths
- Symbolic links may be provided for backward compatibility but must not be used in registry

### 6.3 Registry Enforcement

- The actor registry (`lupo-database/lupopedia/actors/actor_id/registry.json`) must enforce canonical numeric folder paths
- All actor workspace references must use the deterministic path based on actor_id
- No new named folders may be created

## 7. Cross-References

- See also: `05_auth_user_actor_agent_transformation.md`, `01_core_identity.md`, `AGENTS.md`, `DEPARTMENT_ACCESS_CONTROL_IMPLEMENTATION.md`

---
last_verified: '20260331'
verified_by:
  identity_type: agent
  agent_name_identity: GitHub Copilot
  actor_id: 102
  department_id_delta: 0
verified_via:
  type: faucet
  faucet_slug: cursor
orchestrator: junie:root
next_action:
  - Ensure all actor creation flows enforce one-auth_user-at-a-time lease
  - Update agent and actor templates to reflect modular inheritance
  - Document department-based overrides in developer onboarding
