---
lupopedia.headers:
  lupopedia.version: "4.0.87"
  lupopedia.schema: "doctrine"
  system_version: "4.0.87"
  file_path_from_root: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/IDENTITY_LAYERS"
  last_modified_utc: "20260325213000"
  channel_id: 42
  thread_id: 1006
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "12:1"
  artifact_type: "doctrine"
  artifact_kind: "identity"
  purpose: "Canonical five-layer identity model for 4.0.87: auth user, actor, department, agent, and faucet."
  tags: ["identity", "auth_user", "actor", "department", "agent", "faucet", "4.0.87", "ws3"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1006/20260325_190000_athena_identity_model_clarification_4_0_87.md", type: "derived_from", weight: 1.0 }
    - { to: "lupo-docs/doctrine/IDENTITY_MODEL.md", type: "complements", weight: 0.9 }
    - { to: "AGENTS.md", type: "aligns", weight: 0.9 }

lupopedia.footer:
  approved_for_version: "4.1.0"
  approved_for_version_utc: "20260327103238"
  approved_for_version_by: "Cursor IDE Agent (Lead Orchestration)"
  approved_for_version_by_actor_id: 102
  last_verified: "20260325213000"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "wolfie:root"

# file: IDENTITY_LAYERS_DOCTRINE — delegation: 12:1 — web_path: http://www.lupopedia.com/doctrine/IDENTITY_LAYERS

# Identity Layers Doctrine (v4.0.88 clarification)

This doctrine defines the canonical 4.0.87 identity separation to eliminate layer confusion and prevent privilege ambiguity. The model has five layers: Auth User, Actor, Department, Agent, and Faucet.

4.0.88 clarification: departments are primary identity execution scope; context is secondary and currently underdeveloped.

---

## 1. Layer Overview

| Layer | Table | Purpose | Core Rule |
|-------|-------|---------|-----------|
| Auth User | `lupo_auth_users` | Human login identity | Human authentication only |
| Actor | `lupo_actors` | Operational orchestration identity | Universal operational identity key is `actor_id` |
| Department | `lupo_actor_departments` + `lupo_departments` | Execution context and authority scope | Each operational actor has one primary department context |
| Agent | `lupo_agents` | AI runtime configuration | Configuration layer only; no direct posting identity |
| Faucet | `lupo_agent_faucets` | Execution surface (IDE/API) | Interface surface, not orchestration identity |

---

## 2. Layer Definitions

### 2.1 Auth User (`lupo_auth_users`)

- Human login credentials and user-level preferences.
- May support multiple actors based on role and workflow.
- Authentication must not be conflated with orchestration identity.

### 2.2 Actor (`lupo_actors`)

- Canonical identity used for orchestration, channels, and audit attribution.
- All operational actions resolve to actor context server-side.
- Actors can be human-operated or autonomous.

### 2.3 Department (`lupo_actor_departments`, `lupo_departments`)

- Actor-scoped execution context that governs operational surface.
- Defines authority domain, default pairing context, and policy grouping.
- Department context is part of effective actor resolution.

### 2.3.a Department vs Context (4.0.88)

- Departments are the primary operational layer.
- Context is a secondary layer that represents task scope, runtime focus, and situational execution state.
- Context must live under departments and must not replace department authority boundaries.
- Current reality in 4.0.88: context remains underdefined and root department operations are temporarily absorbing context responsibilities.
- Required direction: context must be formalized in future versions while departments remain primary.

### 2.4 Agent (`lupo_agents`)

- AI model metadata, prompts, and capability configuration.
- Bound to actor execution context.
- Does not replace actor identity in database relationships.

### 2.5 Faucet (`lupo_agent_faucets`)

- IDE/API interface surface through which actors execute.
- Supports multiple surfaces per actor.
- Faucet identity is not a substitute for actor identity.

---

## 3. Binding Rules

### 3.1 Human-to-Actor Binding

- Auth users may support one or more operational actors.
- Accountability must retain human attribution where a human initiated the operation.
- Server-side permission checks must validate actor eligibility for the authenticated user.

### 3.2 Actor-to-Department Binding

- Operational actor context is department-scoped.
- Department membership determines execution context and policy boundary.
- Department 1 is core execution domain for primary orchestration personas.

### 3.3 Agent-to-Actor Binding

- Agent configuration binds to actor runtime identity.
- Agent changes update capabilities/configuration, not identity attribution.

### 3.4 Faucet-to-Actor Binding

- Faucets provide access surfaces for actors.
- Multiple faucets can map to a single actor as needed.
- Faucet existence does not imply permission escalation.

---

## 4. Actor ID Range Semantics (4.0.87)

| Range | Meaning |
|-------|---------|
| 0 | System/anonymous context |
| 1-99 | Core personas and autonomous orchestration actors |
| 100-106 | IDE faucet-linked orchestration identities |
| 107-999 | Non-human specialized orchestration actors |
| 1000+ | Human actor identities |

Notes:
- Root auth user remains `auth_user_id = 0`.
- Numeric ranges are governed by the canonical actor registry.

---

## 5. Security and Audit Requirements

- Actor identity for write operations is resolved from authenticated server context.
- Client-supplied actor identity must never be trusted as authority.
- Audit trails must preserve actor and, where applicable, supporting auth user context.
- Department context must be visible in permission-sensitive pathways.

---

## 6. Implementation Guidance

- Keep identity-layer terminology consistent across docs and code.
- Use `actor_id` as the operational join key in relationships.
- Treat `lupo_agents` as configuration metadata and `lupo_agent_faucets` as interface surfaces.
- Enforce department-aware actor context in admin and channel operations.
- Do not use context labels as an authority override for department scope.
- Treat `lupo-context/` as an evolving structure that inherits and refines department-scoped execution, not a replacement identity layer.

---

## 7. References

- `lupo-channels/42/threads/1006/20260325_190000_athena_identity_model_clarification_4_0_87.md`
- `AGENTS.md`
- `lupo-docs/doctrine/IDENTITY_MODEL.md`
- `lupo-database/lupopedia/actors/actor_id/registry.json`
