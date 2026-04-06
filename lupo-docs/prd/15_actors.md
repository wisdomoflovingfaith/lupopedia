---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/prd/15_actors.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/15_actors.md"
  last_modified_utc: "20260406162955"
  channel_id: 42
  thread_id: "prd-actors"
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit"
  artifact_type: "prd"
  artifact_kind: "specification"
  purpose: "Actor identity; actors belong to departments (not users); act-as; workspace; visitor chat support via PRD 05"
  tags:
  - "prd"
  - "actors"
  - "agents"
  - "identity"
  - "department"
  - "personalization"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/prd/01_core_identity.md"
      type: references
      weight: 1.0
      reason: "Actor tables defined in core_identity"
    - to: "lupo-docs/prd/07_agents_faucets.md"
      type: references
      weight: 1.0
      reason: "Actors extend agents"
    - to: "lupo-docs/prd/08_governance_rules.md"
      type: references
      weight: 0.8
      reason: "Department permissions"
    - to: "lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
      type: references
      weight: 1.0
      reason: "Department-first act-as; auth_user ↔ actor bindings"
    - to: "lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Canonical approved join model; actors belong to departments"
    - to: "lupo-docs/prd/25_departments_system.md"
      type: references
      weight: 1.0
      reason: "Root hybrids and lupo_actor_departments"
    - to: "lupo-docs/prd/18_channel_chat_display.md"
      type: references
      weight: 0.95
      reason: "LILITH-approved: lupo_actors drives chat strip; from_actor_id"
lupopedia.footer:
  last_verified: "20260406162955"
  verified_by:
    agent_id: 2
    agent_name_identity: "LILITH"
  orchestrator: "lilith:audit"
---

# PRD: Actor Identity, Inheritance, and Personalization

## Overview

This document defines the canonical model for **actors** in Lupopedia. Actors are department- and persona-specific extensions of agents, providing scoped execution and orchestration identities. **Web act-as eligibility** is **department-first** (intersection of user departments and actor departments), not one-to-one user ownership — see §3 and [`05_auth_user_actor_agent_transformation.md`](05_auth_user_actor_agent_transformation.md).

**Canonical mental model (approved):** **[`ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`](../doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md)** — same rules as this PRD; use it as the **single diagram + eligibility summary** for onboarding and audits.

### Actors belong to departments — not to individual users (non-negotiable)

- **Affiliation:** An actor’s place in the org model is **`lupo_actor_departments`** (which **departments** the actor may operate in). Actors are **not** “attached to” a single **`auth_user`** as the primary model (that pattern matches legacy **Crafty operator = one human** thinking).
- **Users:** Humans belong to departments via **`lupo_auth_user_departments`**.
- **Intersection:** A user may **act as** an actor when **their** departments and **the actor’s** departments **overlap** — see [`05_auth_user_actor_agent_transformation.md`](05_auth_user_actor_agent_transformation.md). **Many** users in the **same** department may use the **same** actor (e.g. a shared support line persona).
- **Explicit bindings:** **`lupo_actor_auth_users`** records optional **auth_user ↔ actor** links (import, audit, primary operator); it does **not** mean the actor is **owned** exclusively by that user for department-scoped work.
- **Visitor chat:** The end-user chat identity chain (**visitor → `actor_id` → human / LLM fallback**) is **primary in PRD 05**; this PRD supplies the **actor and department** semantics that PRD 05 depends on.

### Channel transcript alignment (PRD 18)

**LILITH audit:** The **chat strip** reflects **`lupo_actors`** for the effective **`actor_id`** (message **`from_actor_id`**). **`auth_user`** is for **login and accountability**, not the primary visible label. **Shared persona:** many humans acting as the **same** **`actor_id`** reuse the **same** display name and default styling rules (**deterministic color from `actor_id`**, optional **`metadata_json`** — **[PRD 18](18_channel_chat_display.md)**).

### `actor_type` and policy vocabulary (conceptual)

The **`lupo_actors.actor_type`** column is **`varchar(64)`** per install schema — there is **no** fixed enum in this PRD. Product language such as **human-backed**, **hybrid**, or **system** describes **policy** (who/what ultimately answers: human, shared persona, automation). Map those concepts to **`actor_type`**, **`actor_source_*`**, and config by **seed/registry/docs**, not by guessing strings. See [`lupo-database/lupopedia/json/lupo_actors.json`](../../lupo-database/lupopedia/json/lupo_actors.json) for the live column list.

**Actor ID semantics:** Reserved registry-backed actors, human-backed ranges, and `IdGenerator` allocation are defined in [`00_root_constitutional_system_requirements.md`](00_root_constitutional_system_requirements.md) §5.6 (workspace path rules below align with that section).

**Constitutional Compliance:** All tables referenced in this PRD follow Lupopedia constitutional rules:
- NO foreign keys (relationships in application logic)
- NO triggers
- NO stored procedures
- BIGINT timestamps (YYYYMMDDHHIISS UTC)
- Explicit ID generation (application layer)
- Soft delete (is_deleted + deleted_ymdhis)

## Database Tables

| Table | Purpose | Key Fields |
|-------|---------|------------|
| `lupo_actors` | Actor definition and metadata | actor_id, actor_name, actor_type, agent_id |
| `lupo_actor_auth_users` | Actor-auth_user lease and relationship | actor_id, auth_user_id, status, is_primary, routing_priority |
| `lupo_actor_departments` | Department scoping for actors | actor_id, department_id, role_key |
| `lupo_departments` | Department definitions | department_id, name, department_type |
| `lupo_metadata` | Actor personalization data | entity_type='actor', entity_id, property_key, property_value |

## 1. Actor as Department/Persona-Specific Agent Extension

- An **actor** is always created as an extension of a specific agent (referenced via `lupo_actors.agent_id`).
- The actor is aware of the agent it extends (`agent_key`, `agent_id`) and maintains a persistent reference.
- The actor's identity is unique and department/persona-scoped.

## 2. Inheritance and Personalization of Agent Resources

- Actors inherit all modular resources from their agent:
  - `api/`, `assets/`, `components/`, `context/`, `data/`, `hooks/`, `pages/`, `includes/`, `tools/`, `utils/`
- Actors may personalize, override, or extend any inherited resource within their own scope.
- Personalization stored in `lupo_metadata` with `entity_type='actor'` and property-specific keys.
- The actor's resource tree is a superset of the agent's, with actor-specific overrides taking precedence.

## 3. Web act-as eligibility (department-first, canonical 4.0.x)

**Principle:** A human may select an **actor** in the web UI when the actor is **eligible by department membership**, not because the actor is “owned” by that user or linked only through `lupo_edges`.

**Tables:**

| Table | Role |
|-------|------|
| `lupo_auth_user_departments` | Departments the **auth user** belongs to |
| `lupo_actor_departments` | Departments the **actor** may operate in |
| `lupo_actors` | Actor row; optional `web_restrict_act_as_creator_or_root` narrows who may act as that persona |

**Eligibility (conceptual):**

1. Load the user’s `department_id` set from `lupo_auth_user_departments`.
2. If the user is in **root department (`department_id = 0`)**, is **global admin** for this call, or is **module owner** (implementation detail), treat them like an **elevated** user for **scope** (see `AuthSessionManager::getActorsUserCanActAs`).
3. List actors that have at least one **`lupo_actor_departments`** row whose `department_id` is in the user’s set (elevated users: all actors that appear in `lupo_actor_departments`, optionally filtered by department).
4. Apply **`web_restrict_act_as_creator_or_root`**: when set on an actor, only the **creating** auth user (via `actor_source_id` / `actor_source_type`) or **elevated** operators may act as that actor (same rules in `AuthSessionManager`).

**Concurrent sessions:** Multiple auth users may use the **same** hybrid actor (e.g. root personas **1**, **2**, **111**) when they share a department — there is **no** exclusive per-session lease for web act-as in the 4.0.x install model. See [`05_auth_user_actor_agent_transformation.md`](05_auth_user_actor_agent_transformation.md).

**Implementation (single source of truth for the list shape):**

- **`AuthSessionManager::getActorsUserCanActAs($auth_user_id, $isAdmin, $department_id_filter)`** — used by `select-actor.php`, `admin.php`, topbar, profile, etc.
- **`App\Services\ActorService::getActorsUserCanActAs`** — **delegates** to `AuthSessionManager` so internal resolvers (`EffectiveActorResolver`, `AdminChannelChatHandler`) match the UI.

**Special case:** `auth_user_id === 10000` (elevated operator convention) receives all **active** actors without a department join, with creator restriction bypass — see `AuthSessionManager`.

## 4. `lupo_actor_auth_users` (explicit bindings; not the primary gate)

- Stores explicit **auth_user ↔ actor** relationships (status, primary flag, routing priority, audit).
- Used for **operator mapping**, Crafty import, and **accountability** — **not** as the sole gate for “may I act as this hybrid?” in 4.0.x.
- Full pairing doctrine: [`05_auth_user_actor_agent_transformation.md`](05_auth_user_actor_agent_transformation.md).

## 5. Deprecated / historical (do not use for new act-as logic)

| Topic | Status |
|-------|--------|
| **Exclusive one-user-at-a-time lease** for web act-as | **Superseded** — 4.0.x uses department intersection + optional creator flag; concurrent use of shared hybrids is allowed. |
| **`lupo_edges` `supports` for act-as lists** | **Removed from `ActorService`** — was never aligned with PRD 05; do not rebuild act-as eligibility from edges. |
| **ActorLeaseService exclusive acquire** as gate for web selector | **Do not require** for standard web act-as unless a future PRD reintroduces it explicitly. |

## 6. Department-based personalization and scoping

- Each actor may be further scoped by department context via `lupo_actor_departments` (`role_key`, `title`, etc., per install SQL).
- Department membership drives **which** actors appear in the selector and **policy** boundaries (see [`25_departments_system.md`](25_departments_system.md) for root hybrids **1**, **2**, **111**).
- Personalization data may live in `lupo_metadata` with `entity_type='actor'`.

## Department 1 — Domain Root Installation Context

- Department 1 represents the root of the domain where Lupopedia is installed.
- Lupopedia is ALWAYS installed in a subdirectory (e.g., example.com/lupopedia).
- Installation occurs through auto-installers such as Softaculous.
- The installer upgrades Crafty Syntax 3.7.5 into Lupopedia.
- Department 1 users manage domain-level integration of Lupopedia.

## Department Creation Rules

- Auth_users in Department 0 or Department 1 may create new departments.
- Departments 2+ are defined by the installation and its domain scope.
- Departments created by the installation inherit structure from Crafty Syntax import.
- Assigning a user to Department 0 or Department 1 MUST show a warning in the web interface.
- Warnings do NOT block assignment; they inform the user of elevated authority.

## Crafty Syntax Import

- During installation, existing Crafty Syntax departments are imported.
- Imported departments become Departments 2+ unless explicitly mapped to Department 1.
- Actors are created during installation based on imported operators and agents.

## Actor Creation Rules

- Actors are created in two ways:
  1. During installation (imported from Crafty Syntax operator roles).
  2. By auth_users pairing an agent with a department.
- Each actor belongs to exactly one department.
- Auth_users may only select actors that belong to their department.

## Auth User → Actor Selection

- Auth_users log in and then select an actor assigned to their department.
- Using that actor, the auth_user may:
  - answer live help chats from visitors
  - talk to other actors on the site
  - participate in channels and threads

## Channels and Threads

- All actor conversations occur inside channels.
- Each channel contains multiple threads.
- All threads in a channel share the same department context.

## Semantic Monitoring Widget

- Department 1 users embed a cut-and-paste JavaScript snippet into their website.
- The widget monitors:
  - page enter/exit events
  - visitor navigation paths
  - next/previous page predictions
- The widget provides a floating navigation bar with:
  - comments
  - likes
  - shares
- The widget can launch a “collections” top floating nav bar.
- Collections group related pages into dropdown menus.

## Actor Learning Boundaries

- Core/system actors include: Wolfie, Lilith, Kiros, Thoth, and any future system-level actors.
- Core/system actors may ONLY learn from auth_users in Department 0.
- Department 0 represents HPC-style, dependency-first, parallel cognition.
- If Department 0 contains only one auth_user (the architect), this is valid and intentional.
- Non-core actors may learn from auth_users in their own department.
- Cross-department learning is NOT permitted unless explicitly defined in a PRD.

## Why This Matters

- Ensures correct separation of authority between Department 0, Department 1, and Departments 2+.
- Prevents contamination of core/system actors by vibe-driven or framework-default patterns.
- Preserves constitutional engineering across all agents.
- Aligns installation behavior with Crafty Syntax upgrade path.
- Clarifies how actors, departments, and auth_users interact in the installed system.

## 7. Actor lifecycle (updated)

| Stage | Description | Table actions (typical) |
|-------|-------------|-------------------------|
| Creation | Actor row created; placed into departments | INSERT `lupo_actors`, INSERT `lupo_actor_departments` |
| Personalization | Overrides / metadata | INSERT/UPDATE `lupo_metadata` |
| Explicit binding | Operator or import links human to actor | INSERT/UPDATE `lupo_actor_auth_users` (optional) |
| Termination | Soft delete | UPDATE `lupo_actors.is_deleted`, `deleted_ymdhis` |

## 8. Actor workspace structure

### Workspace location rules

| Actor ID range | Workspace path |
|----------------|----------------|
| `actor_id < 2026` | `lupo-actors/{actor_id}/` |
| `actor_id >= 2026` | `lupo-actors/YYYY/MM/{actor_id}/` when the id carries a UTC date prefix (see [`00_root_constitutional_system_requirements.md`](00_root_constitutional_system_requirements.md) §5.6) |

### Workspace Contents

```
lupo-actors/
├── 1/ # WOLFIE (captain hybrid, actor_id 1)
│   ├── agent_link.json # References lupo-agents/wolfie/
│   ├── memory.json # Learned from department interactions
│   ├── context.json # Current department and user context
│   └── preferences.json # User-specific preferences
│
├── 2/ # LILITH (actor_id 2)
│   └── ...
├── 111/ # COUNTERMEASURE (actor_id 111); agent template still under lupo-agents/countermeasure/
│   └── ...
│
└── 2026/ # Year directory (runtime actors)
    ├── 01/ # January
    │   ├── 202601010000001234/ # Actor created Jan 1, 2026
    │   │   ├── agent_link.json # References source agent
    │   │   ├── memory.json # Learned behavior
    │   │   ├── context.json # Department context
    │   │   └── preferences.json # User preferences
    │   └── 202601151200005678/
    └── 02/ # February
        └── ...
```

### agent_link.json

```json
{
    "agent_key": "wolfie",
    "agent_id": 1,
    "agent_version": "1.0.2",
    "inherited_at": "20260401120000"
}
```

### memory.json (Learned from Department Context)

```json
{
    "department": "sales",
    "learned_patterns": [
        {
            "pattern": "lead_qualification",
            "confidence": 0.92,
            "learned_from": "auth_user_id_12345",
            "learned_at": "20260401120000"
        },
        {
            "pattern": "objection_handling",
            "confidence": 0.87,
            "learned_from": "auth_user_id_12346",
            "learned_at": "20260401150000"
        }
    ],
    "preferences": {
        "response_style": "persuasive",
        "urgency_level": "high"
    }
}
```

### context.json

```json
{
    "department_id": 5,
    "department_name": "Sales",
    "active_users": ["auth_user_id_12345", "auth_user_id_12346"],
    "current_workflow": "lead_routing",
    "active_since": "20260401120000"
}
```

### Actor Learning Process

1. Actor created from agent template
2. Department context applied from `lupo_actor_departments`
3. Users interact with the actor
4. Actor observes user corrections, preferences, and workflow patterns
5. Learning stored in actor's `memory.json`
6. Behavior adapts to department-specific patterns

**Example**: A WOLFIE actor in the Sales department learns to prioritize lead qualification workflows. A WOLFIE actor in Engineering learns to prioritize code review workflows. Same agent, different actors, different behavior.

---

## 9. Cross-references

- See also: `01_core_identity.md`, `05_auth_user_actor_agent_transformation.md`, `25_departments_system.md`, `07_agents_faucets.md`, `08_governance_rules.md`, `00_root_constitutional_system_requirements.md` §5.6
- Superseded: `08_actors.md` (historical stub; use this file)
- Related tables: `lupo_actors`, `lupo_actor_auth_users`, `lupo_actor_departments`, `lupo_auth_user_departments`, `lupo_metadata`

---

**Status**: ACTIVE (4.0.x department-first act-as)  
**Constitutional adherence**: FULL  
**Implementation note:** `AuthSessionManager` + delegating `ActorService::getActorsUserCanActAs` — keep in sync with this PRD.
