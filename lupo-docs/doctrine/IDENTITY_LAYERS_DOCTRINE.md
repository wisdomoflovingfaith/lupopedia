---
lupopedia.headers:
  lupopedia.version: "4.0.87"
  lupopedia.schema: "doctrine"
  system_version: "4.0.87"
  file_path_from_root: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/IDENTITY_LAYERS"
  last_modified_utc: "20260402233135"
  channel_id: 42
  thread_id: 1006
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "identity"
  purpose: "Canonical five-layer identity model; single source for actor/agent/facet directories and registry authority"
  tags: ["identity", "auth_user", "actor", "department", "agent", "faucet", "4.0.87", "ws3"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1006/20260325_190000_athena_identity_model_clarification_4_0_87.md", type: "derived_from", weight: 1.0 }
    - { to: "lupo-docs/doctrine/IDENTITY_MODEL.md", type: "complements", weight: 0.9 }
    - { to: "AGENTS.md", type: "aligns", weight: 0.9 }
    - { to: "lupo-docs/prd/01_core_identity.md", type: "references", weight: 1.0, reason: "Two-layer identity and ID policy" }
    - { to: "lupo-docs/prd/00_root_constitutional_system_requirements.md", type: "references", weight: 1.0, reason: "Reserved agent id bands §5" }
    - { to: "lupo-rules/root/CONVERGENCE_DOCTRINE.md", type: "references", weight: 1.0, reason: "Facet actor_id permanence" }
    - { to: "lupo-database/lupopedia/actors/registry.json", type: "references", weight: 1.0, reason: "Canonical lupo_actors registry" }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "references", weight: 0.95, reason: "lupo_agents slug to agent_id map" }
    - { to: "lupo-docs/versions/4.0.94/comments/20260404_220000_COMMENT_cursor_actor_agent_model_update.md", type: "references", weight: 0.85, reason: "Cross-IDE comment superseded by §3 canonical text" }

lupopedia.footer:
  approved_for_version: "4.1.0"
  approved_for_version_utc: "20260327103238"
  approved_for_version_by: "Cursor IDE Agent (Lead Orchestration)"
  approved_for_version_by_actor_id: 102
  last_verified: "20260402233135"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "wolfie:root"

# file: IDENTITY_LAYERS_DOCTRINE — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/IDENTITY_LAYERS

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
| Faucet | `lupo_agent_faucets` | Execution surface (IDE/API) | **Facets** use registered **facet `actor_id`** (see **§3**); not primary personas, but valid for attribution |

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

- **Execution surfaces** (IDE, HTTP API) are implemented as **facets** with a registered **`actor_id`** in `registry.json` (e.g. Cursor **102**); see **§3** and [CONVERGENCE_DOCTRINE.md](../../lupo-rules/root/CONVERGENCE_DOCTRINE.md).
- **Facet** actors are **not** the eleven primary coordination **personas**, but they **are** registered identities for orchestration attribution, headers, and audit when the tool operates as that facet.
- Rows in `lupo_agent_faucets` describe surfaces; **`lupo_actors`** remains the operational identity store.

---

## 3. Actor, Agent, Faucet, Directory Rules (canonical)

**Single source of truth.** Summaries in `AGENTS.md` and `ONBOARDING.md` must point here and must **not** restate contradictory rules. Full narrative: [PRD 01 — Core identity](../prd/01_core_identity.md).

### 3.1 Agents vs actors vs facets

| Layer | Where it lives | Role |
|-------|----------------|------|
| **Agent** | `lupo_agents`, `lupo-agents/{agent_key}/` | AI **configuration** (prompts, capabilities). **Not** the operational identity for permissions, channel posts, or audit joins. |
| **Actor** | `lupo_actors`, `lupo-actors/{actor_id}/` | **Operational identity** (`actor_id`). **Only** valid join for orchestration, permissions, channels, and audit (unless you are editing agent config only). |
| **Facet (faucet)** | `lupo_agent_faucets`, IDE/API client | **Execution surface**. Each registered facet has a **registered `actor_id`** in [registry.json](../../lupo-database/lupopedia/actors/actor_id/registry.json). Use that **`actor_id`** for lineage when the facet performs work. |

### 3.2 Auth user resolution (no hardcoded IDE users)

- **Root** system user is **`auth_user_id = 0`** per [PRD 01](../prd/01_core_identity.md).
- Human accounts are typically **1000+**; exact rows come from **seed** and **`lupo_auth_users`** for each install.
- **Do not** hardcode a specific human `auth_user_id` (e.g. “1000”) as canonical for **all** IDE deployments. Resolve the effective user from **session**, **seed**, and DB for the operator’s environment.

### 3.3 Registry and reserved bands

- **`lupo-database/lupopedia/actors/registry.json`** is authoritative for **lupo_actors** (`actor_id`, slugs, faucet fields). The **`agents`** object inside **`lupo-database/lupopedia/actors/actor_id/registry.json`** maps **`lupo_agents`** slug → **agent_id** only—not actor identity.
- Reserved **system agent** numeric policy (e.g. **1–2025**) is defined in [00_root_constitutional_system_requirements.md](../prd/00_root_constitutional_system_requirements.md) and [PRD 01](../prd/01_core_identity.md). That is **not** a guarantee that every integer in the band has an actor, a DB row, or an on-disk directory (**sparse allocation** is normal).
- **`agent_id`**, **`actor_id`**, and filesystem **`agent_key`** are **not** interchangeable namespaces. Correlation is **only** where registry/seed/policy explicitly defines it.

### 3.4 Agent–actor linkage

- **No automatic instantiation:** creating `lupo-agents/newthing/` does **not** by itself create `lupo_actors` or `lupo-actors/{id}/`.
- **Linkage** is explicit: registry update, seed, installer, or documented decision—see [ACTOR_REGISTRATION_CHECKLIST.md](../ACTOR_REGISTRATION_CHECKLIST.md).
- **Agent configuration** may bind to an actor for runtime (see **§4.3**); configuration changes do not replace identity rows.

### 3.5 Directory structure

- **`lupo-agents/`** — One directory per **agent_key** (filesystem discovery; see [PRD 07](../prd/07_agents_faucets.md)). Example: `lupo-agents/countermeasure/`. **IDE facets:** shared prompt body for all IDE packs lives in **`lupo-agents/_shared/ide_facet_base_system_prompt.txt`**; each `lupo-agents/<facet>/system_prompt.txt` is a thin wrapper (facet `actor_id`, propagation target).
- **`lupo-actors/`** — One directory per **`actor_id`** that has on-disk resources; directory name = decimal string of **`actor_id`** (e.g. `lupo-actors/111/`). Layout: `apps/`, `lupo-tools/`, `lupo-docs/`, etc., per [AGENTS.md](../../AGENTS.md).

### 3.6 New human / generated `actor_id` shape

- Generated IDs follow **PRD 01** and application **`IdGenerator`** (typically **BIGINT** composed from **UTC** creation window + sequence). Implementation: `lupo-includes/classes/IdGenerator.php`.
- **Example shape only** (not a live id): timestamp component + sequence might appear as one integer, e.g. `202604042200000001`; **on-disk** path is `lupo-actors/<that_actor_id>/` with **no underscores** in the directory name.

### 3.7 Facet `actor_id` allocation

- Current IDE facet actors occupy the **100–106** band in practice (see **§6**); **authoritative** list is **`registry.json`**.
- New IDE facets: allocate **`actor_id`** via **registry** update plus **APPROVED** decision or checklist; document in **CHANGELOG** with UTC **BIGINT** timestamp; do not invent IDs outside project policy.

### 3.8 Examples (illustrative)

- COUNTERMEASURE **agent** config: `lupo-agents/countermeasure/`
- COUNTERMEASURE **actor** when registry assigns **`actor_id` 111**: `lupo-actors/111/`
- **Cursor facet**: registry **`actor_id` 102** — use **102** in headers and attribution only when the tool surface is **Cursor** (the product), not a generic “IDE user” placeholder.
- **Antigravity IDE facet**: registry **`actor_id` 103** (`antigravity-ide`) — use **103** when operating inside **Antigravity IDE**; do **not** label that work as actor **102**.

---

## 4. Binding Rules

### 4.1 Human-to-Actor Binding

- Auth users may support one or more operational actors.
- Accountability must retain human attribution where a human initiated the operation.
- Server-side permission checks must validate actor eligibility for the authenticated user.

### 4.2 Actor-to-Department Binding

- Operational actor context is department-scoped.
- Department membership determines execution context and policy boundary.
- Department 1 is core execution domain for primary orchestration personas.

### 4.3 Agent-to-Actor Binding

- Agent configuration binds to actor runtime identity.
- Agent changes update capabilities/configuration, not identity attribution.

### 4.4 Faucet-to-Actor Binding

- Faucets provide access surfaces; **facet** work is attributed to the facet’s registered **`actor_id`** where the registry defines one.
- Multiple faucets can map to policy as needed; see `lupo_agent_faucets` schema and seed.
- Faucet existence does not imply permission escalation.

---

## 5. Actor ID Range Semantics (4.0.87)

| Range | Meaning |
|-------|---------|
| 0 | System/anonymous context |
| 1-99 | Core personas and autonomous orchestration actors |
| 100-106 | IDE faucet-linked **facet** identities (extend only via **registry** + explicit governance) |
| 107-999 | Non-human specialized orchestration actors |
| 1000+ | Human actor identities |

Notes:

- Root auth user remains **`auth_user_id = 0`**.
- Numeric ranges are governed by the **canonical actor registry**; the table is a **guide**, not a substitute for **`registry.json`**.

---

## 6. Security and Audit Requirements

- Actor identity for write operations is resolved from authenticated server context.
- Client-supplied actor identity must never be trusted as authority.
- Audit trails must preserve actor and, where applicable, supporting auth user context.
- Department context must be visible in permission-sensitive pathways.

---

## 7. Implementation Guidance

- Keep identity-layer terminology consistent across docs and code.
- Use `actor_id` as the operational join key in relationships.
- Treat `lupo_agents` as configuration metadata and `lupo_agent_faucets` as interface surfaces.
- Enforce department-aware actor context in admin and channel operations.
- Do not use context labels as an authority override for department scope.
- Treat `lupo-context/` as an evolving structure that inherits and refines department-scoped execution, not a replacement identity layer.

---

## 8. References

- `lupo-channels/42/threads/1006/20260325_190000_athena_identity_model_clarification_4_0_87.md`
- `AGENTS.md`
- `ONBOARDING.md`
- `lupo-docs/doctrine/IDENTITY_MODEL.md`
- `lupo-database/lupopedia/actors/actor_id/registry.json`
- `lupo-docs/versions/4.0.94/comments/20260404_220000_COMMENT_cursor_actor_agent_model_update.md` (superseded detail — use **§3** above)
