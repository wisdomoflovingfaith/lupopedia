---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: pseudocode
  when_updated: "20260405211127"
  last_modified_utc: "20260405211127"
  file_path_from_root: "lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/15_actors_constitution.pseudo.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/15_actors_constitution.pseudo.md"
  channel_id: 42
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: pseudocode
  artifact_kind: constitution_shorthand
  purpose: "PRD 15 digest — actors belong to departments, act-as, tables (Purpose 1 per PRD 17)"
  tags:
    - pseudocode
    - constitution_shorthand
    - prd_15
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/15_actors.md"
      type: references
      weight: 1.0
      reason: "Canonical PRD"
    - to: "lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
      type: references
      weight: 1.0
lupopedia.footer:
  last_verified: "20260405211127"
  verified_by:
    actor_id: 102
---

# PRD 15 shorthand — Actors (departments, not users)

**Canonical:** [PRD 15](../../../../prd/15_actors.md)

## Non-negotiable mental model

- **Actors belong to departments** via **`lupo_actor_departments`** — **not** “owned by” one **`auth_user`** as the primary model.
- **Users** belong to departments via **`lupo_auth_user_departments`**.
- **Act-as eligibility** = **intersection** of user departments **∩** actor departments (**PRD 05**).
- **Many users** in the same department may act as the **same** **`actor_id`** (shared persona / support line).

## `lupo_actor_auth_users`

- Optional **explicit** auth_user ↔ actor binding (import, audit, “primary” operator mapping).
- Does **not** mean exclusive ownership for department-scoped web chat.

## Key tables (names only — see TOON/install SQL)

| Table | Role |
|-------|------|
| **`lupo_actors`** | Actor definition — **`actor_id`**, **`actor_name`**, **`actor_type`**, **`agent_id`**, traits |
| **`lupo_actor_departments`** | Which departments an actor may operate in |
| **`lupo_actor_auth_users`** | Lease/binding metadata between auth user and actor |
| **`lupo_departments`** | Department rows |
| **`lupo_metadata`** | Personalization — **`entity_type='actor'`**, etc. |

## Chat UI (PRD 18 alignment)

- Bubble / strip shows **`lupo_actors`** for effective **`from_actor_id`**.
- **Deterministic styling** from **`actor_id`** (color, etc.); optional **`metadata_json`** overrides.

## Constitutional

No FKs/triggers; BIGINT UTC; application-generated IDs; soft delete — **PRD 00**.
