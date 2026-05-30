---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/identity_layers_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/identity_layers_doctrine.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: identity
  channel_key: null
  federation_node_id: null
  thread_key: 1006
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
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

- **Execution surfaces** (IDE, HTTP API) are implemented as **facets** with a registered **`actor_id`** in `registry.json` (e.g. Cursor **102**); see **§3** and [CONVERGENCE_DOCTRINE.md](../../rules/root/CONVERGENCE_DOCTRINE.md).
- **Facet** actors are **not** the eleven primary coordination **personas**, but they **are** registered identities for orchestration attribution, headers, and audit when the tool operates as that facet.
- Rows in `lupo_agent_faucets` describe surfaces; **`lupo_actors`** remains the operational identity store.

---

## 3. Actor, Agent, Faucet, Directory Rules (canonical)

**Single source of truth.** Summaries in `AGENTS.md` and `ONBOARDING.md` must point here and must **not** restate contradictory rules. Full narrative: [PRD 01 — Core identity](../prd/01_core_identity.md).

### 3.1 Agents vs actors vs facets

| Layer | Where it lives | Role |
|-------|----------------|------|
| **Agent** | `lupo_agents`, `agents/{agent_key}/` | AI **configuration** (prompts, capabilities). **Not** the operational identity for permissions, channel posts, or audit joins. |
| **Actor** | `lupo_actors`, `actors/{actor_id}/` | **Operational identity** (`actor_id`). **Only** valid join for orchestration, permissions, channels, and audit (unless you are editing agent config only). |
| **Facet (faucet)** | `lupo_agent_faucets`, IDE/API client | **Execution surface**. Each registered facet has a **registered `actor_id`** in [registry.json](../../database/lupopedia/actors/actor_id/registry.json). Use that **`actor_id`** for lineage when the facet performs work. |

### 3.2 Auth user resolution (no hardcoded IDE users)

- **Root** system user is **`auth_user_id = 0`** per [PRD 01](../prd/01_core_identity.md).
- Human accounts are typically **1000+**; exact rows come from **seed** and **`lupo_auth_users`** for each install.
- **Do not** hardcode a specific human `auth_user_id` (e.g. “1000”) as canonical for **all** IDE deployments. Resolve the effective user from **session**, **seed**, and DB for the operator’s environment.

### 3.3 Registry and reserved bands

- **`database/lupopedia/actors/registry.json`** is authoritative for **lupo_actors** (`actor_id`, slugs, faucet fields). The **`agents`** object inside **`database/lupopedia/actors/actor_id/registry.json`** maps **`lupo_agents`** slug → **agent_id** only—not actor identity.
- Reserved **system agent** numeric policy (e.g. **1–2025**) is defined in [00_root_constitutional_system_requirements.md](../prd/00_root_constitutional_system_requirements.md) and [PRD 01](../prd/01_core_identity.md). That is **not** a guarantee that every integer in the band has an actor, a DB row, or an on-disk directory (**sparse allocation** is normal).
- **`agent_id`**, **`actor_id`**, and filesystem **`agent_key`** are **not** interchangeable namespaces. Correlation is **only** where registry/seed/policy explicitly defines it.

### 3.4 Agent–actor linkage

- **No automatic instantiation:** creating `agents/newthing/` does **not** by itself create `lupo_actors` or `actors/{id}/`.
- **Linkage** is explicit: registry update, seed, installer, or documented decision—see [ACTOR_REGISTRATION_CHECKLIST.md](../ACTOR_REGISTRATION_CHECKLIST.md).
- **Agent configuration** may bind to an actor for runtime (see **§4.3**); configuration changes do not replace identity rows.

### 3.5 Directory structure

- **`agents/`** — One directory per **agent_key** (filesystem discovery; see [PRD 07](../prd/07_agents_faucets.md)). Example: `agents/countermeasure/`. **IDE facets:** shared prompt body for all IDE packs lives in **`agents/_shared/ide_facet_base_system_prompt.txt`**; each `agents/<facet>/system_prompt.txt` is a thin wrapper (facet `actor_id`, propagation target).
- **`actors/`** — **Actor hub** paths are keyed by **`actor_id`**, not by slug. Authoritative layout is [PRD 00 §5.6](../prd/00_root_constitutional_system_requirements.md#56-actor-id-semantics):
  - **`actor_id` &lt; 2026** (reserved / install / registry band): **`actors/{actor_id}/`** (decimal string, e.g. `actors/111/` for COUNTERMEASURE).
  - **`actor_id` ≥ 2026** (typical web / `IdGenerator` allocations with a UTC timestamp prefix): **`actors/YYYY/MM/{actor_id}/`** where **YYYY** and **MM** are taken from the leading date portion of the id (see PRD 00).
- **Do not** create **`actors/{slug}/`** for the actor hub when a numeric path exists or is required by registry **`dir`** — slug-named hubs are legacy drift (e.g. `actors/countermeasure/` was incorrect for **`actor_id` 111**).
- Standard subdirs under the hub: `apps/`, `tools/`, `docs/`, etc., per [AGENTS.md](../../AGENTS.md).

### 3.6 New human / generated `actor_id` shape

- Generated IDs follow **PRD 01** and application **`IdGenerator`** (typically **BIGINT**: **UTC `YYYYMMDDHHIISS`** + **4-digit sequence** **0000–9999**). Implementation: `includes/classes/IdGenerator.php`.
- **On-disk** for those runtime ids: **`actors/YYYY/MM/{actor_id}/`** per **§3.5** and PRD 00 §5.6 (not a flat single segment when **`actor_id` ≥ 2026**).

### 3.7 Facet `actor_id` allocation

- Current IDE facet actors occupy the **100–106** band in practice (see **§6**); **authoritative** list is **`registry.json`**.
- New IDE facets: allocate **`actor_id`** via **registry** update plus **APPROVED** decision or checklist; document in **CHANGELOG** with UTC **BIGINT** timestamp; do not invent IDs outside project policy.

### 3.8 Examples (illustrative)

- COUNTERMEASURE **agent** config: `agents/countermeasure/`
- COUNTERMEASURE **actor** when registry assigns **`actor_id` 111**: `actors/111/`
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
- Treat `context/` as an evolving structure that inherits and refines department-scoped execution, not a replacement identity layer.

---

## 8. References

- `channels/42/threads/1006/20260325_190000_athena_identity_model_clarification_4_0_87.md`
- `AGENTS.md`
- `ONBOARDING.md`
- `docs/doctrine/IDENTITY_MODEL.md`
- `database/lupopedia/actors/actor_id/registry.json`
- `docs/versions/4.0.94/comments/20260404_220000_COMMENT_cursor_actor_agent_model_update.md` (superseded detail — use **§3** above)
