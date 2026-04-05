---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260404174956"
  file_path_from_root: "lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
  last_modified_utc: "20260404174956"
  channel_id: 42
  thread_id: "auth-user-actor-transformation"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "prd"
  artifact_kind: "auth_model"
  purpose: "Auth/actor/agent model; department act-as; primary PRD for visitor chat identity chain"
  tags:
  - "prd"
  - "auth_model"
  - "identity"
  - "transformation"
  - "v4.0.93"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-database/lupopedia/json/lupo_auth_users.json"
      type: references
      weight: 1.0
      reason: Table definition for authentication users
    - to: "lupo-database/lupopedia/json/lupo_actors.json"
      type: references
      weight: 1.0
      reason: Table definition for actors
    - to: "lupo-database/lupopedia/json/lupo_actor_auth_users.json"
      type: references
      weight: 1.0
      reason: Canonical auth_user to actor lease and relationship table
    - to: "lupo-database/lupopedia/toon/lupo_actor_auth_users.toon.json"
      type: references
      weight: 1.0
      reason: TOON schema derived from install SQL
    - to: "lupo-docs/prd/15_actors.md"
      type: references
      weight: 1.0
      reason: Department-first act-as; lupo_actor_auth_users bindings; deprecated edge-based lists
    - to: "lupo-docs/prd/25_departments_system.md"
      type: references
      weight: 1.0
      reason: lupo_auth_user_departments + lupo_actor_departments drive web act-as
    - to: "lupo-docs/implementations/13_crafty_integration/questions/THREAD_INDEX.md"
      type: references
      weight: 0.95
      reason: Visitor chat identity QUESTION threads (Crafty transition)
    - to: "lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Canonical approved mental model; joins; visitor vs Crafty"
    - to: "lupo-docs/prd/18_channel_chat_display.md"
      type: references
      weight: 0.95
      reason: "LILITH-approved UI: from_actor_id transcript; auth_user not bubble label"
lupopedia.footer:
  last_verified: '20260404174956'
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
---

> **Pairing (canonical):** Auth user ↔ actor **bindings** live in **`lupo_actor_auth_users`** (see [`15_actors.md`](15_actors.md)). **Who may act as which actor on the web** is resolved from **`lupo_auth_user_departments`** (user’s departments) joined to **`lupo_actor_departments`** (actor membership in departments). The same **hybrid** actor can therefore be used by **many** auth users who share a department. **`lupo_actors.web_restrict_act_as_creator_or_root`** still narrows specific personas when set. Older “one actor per user” pairing is **not** the 4.0.x model for department-scoped hybrids.

## Visitor-facing chat identity chain (primary — this PRD)

**Normative order:** **PRD 05 (this file)** defines the visitor chat identity chain; **[`15_actors.md`](15_actors.md)** defines actor lifecycle and department membership; **[`25_departments_system.md`](25_departments_system.md)** positions departments in routing; **[`13_crafty_integration.md`](13_crafty_integration.md)** covers Crafty import and the operator → actor narrative shift.

1. **`actor_id` is the primary runtime identity** for “who answers the visitor” — not a bare **`auth_user_id`** as the long-term sole key. The **actor** is **department-scoped** via **`lupo_actor_departments`** (same intersection idea as web act-as where the product applies it to chat).
2. **Conceptual chain:** visitor message → **department-scoped `actor_id`** for the thread → optional **LLM / automation** when install **keys**, **token/quota**, and **policy** allow → otherwise **human `auth_user`** in the **same department** (Crafty-compatible baseline) → otherwise **queue / offline / leave-message** flows.
3. **`auth_user`** remains **login, accountability, and human fallback** — it does **not** replace **`actor_id`** in chat architecture. Visitor chat must **not** be modeled only as visitor ↔ **`auth_user`** (legacy operator row) without **`actor_id`** resolution.
4. **Department-scoped eligibility** for which actors may represent a department in chat aligns with **user departments ∩ actor departments** (see **`AuthSessionManager::getActorsUserCanActAs`** and related code paths).

### Alignment with PRD 18 (channel chat UI)

**LILITH audit (department-scoped model):** Channel messages **store and render** via **`from_actor_id`** joined to **`lupo_actors`** — that is the **primary** transcript identity. **`auth_user` is not the bubble label**; the UI shows the **actor** the session is acting as. **Multiple** **`auth_users`** may act as the **same** **`actor_id`** → **one** shared strip persona (name, deterministic color from **`actor_id`**, optional **`metadata_json`** overrides — see **[PRD 18](18_channel_chat_display.md)**).

**Design Q&A:** [`lupo-docs/implementations/13_crafty_integration/questions/THREAD_INDEX.md`](../implementations/13_crafty_integration/questions/THREAD_INDEX.md) (PRD alignment complete per Q3).

**Canonical mental model (approved):** [`ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`](../doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md) — single source for **`auth_user` / `department` / `actor`** joins, eligibility (**department intersection** first; **`lupo_actor_auth_users`** supplementary), visitor attribution, and Crafty contrast. This PRD stays normative for **visitor chain steps** and **PHP resolution paths**; do not contradict the doctrine.

### Channel communication model (multi-actor routing)

**Canonical DDL field:** **`lupo_dialog_messages.to_actor_id`** (**NULL** = broadcast). Directive text may say **“said-to”** / **`said_to_actor_id`** — **same semantics**.

- **Direct / addressed line:** **`to_actor_id`** = specific **`actor_id`** → product **routes** UX (expected responder, highlight). **Does not** make the row **private** to that actor — **channel members** who can read the channel **read all messages** in the thread (**PRD 18**).
- **Broadcast:** **`to_actor_id` NULL** → general channel traffic.

**Service auto-response:** When **`to_actor_id`** matches a **service persona** the deployment wires for automation (**LILITH**, **THOTH**, **MAAT**, etc. — **resolve `actor_id` from `lupo-database/lupopedia/actors/registry.json`**), the system **MAY** auto-respond via **ROSE** / PHP orchestration. **Human** addressees are **not** auto-replied by default.

**Thread context:** Agents and services **SHALL** process **all** messages in the **`channel_id` + `dialog_thread_id`** scope when building context — **not only** lines where **`to_actor_id`** points at them (**PRD 18**, **PRD 36**, **PRD 37** §10.6).

**Out of scope:** **No** **`mention_actor_ids`** JSON column; **no** per-row visibility filter keyed on **`to_actor_id`** inside the same channel.

## Core Doctrine

## Permission rule (canonical, 4.0.x)

**Department-scoped act-as (web):** An auth user may select an actor in the web UI only if that actor has at least one **`lupo_actor_departments`** row in a department where the user has a row in **`lupo_auth_user_departments`**. Root department (`department_id = 0`) users see actors mapped to department 0 (and elevated operators may see broader lists per `AuthSessionManager`). This replaces legacy checks that compared a single `auth_user.department_id` to `actor.department_id` for hybrid pools.

**Actor auth bindings:** **`lupo_actor_auth_users`** remains the table for explicit auth_user↔actor links (primary operator mapping, audit). It does **not** replace **`lupo_actor_departments`** for “which departments may use this actor.”

## Database Requirements
- **Canonical pairing table:** **`lupo_actor_auth_users`** — status and audit for auth_user↔actor binding (see `15_actors.md`). Concurrent web sessions may map the same actor; there is no exclusive per-session lease row in install SQL.
- **`lupo_actors.web_restrict_act_as_creator_or_root`:** When set, web act-as lists and `updateActiveActor` enforce creator-or-bypass (root department, admin, module owner) in PHP.
- All IDs via IdGenerator::generate() (or explicit registry IDs where required), all timestamps BIGINT UTC `YYYYMMDDHHIISS` (no display width on integer types), no AUTO_INCREMENT, no FOREIGN KEYS, no UNSIGNED.

## Leasing logic (illustrative; production uses AuthService / AuthSessionManager)

Use **`lupo_auth_user_departments`** and **`lupo_actor_departments`** (not a single `department_id` column on the user row alone).

## Workflow: agent template → hybrid actor → department pool → auth users

1. **Agent** (template) is defined under **`lupo_agents` / `lupo-agents/`** (configuration).
2. **Actor** row is created or seeded (hybrid or system persona); **`actor_id` / `actor_name`** follow registry doctrine.
3. **Departments:** insert **`lupo_actor_departments`** so the actor belongs to one or more departments (`role_key` e.g. `hybrid`, `system`).
4. **Auth users** are linked to departments via **`lupo_auth_user_departments`** (users can be in multiple departments).
5. **Web session** chooses an allowed **`actor_id`** from the intersection of (user’s departments) and (actor’s departments), subject to **`web_restrict_act_as_creator_or_root`** and admin bypass rules in PHP.
6. **Optional:** **`lupo_actor_auth_users`** records explicit bindings for operators imported from Crafty or created in admin.

## Why This Model?
- Enables human/AI co-training, multi-user actor evolution, department-scoped access, agent autonomy, actor specialization, clean audit trails, and future multi-agent orchestration.
- Avoids identity drift and legacy mapping issues.

## Explicit notes for IDE agents
- **Agents** = template/configuration entities.
- **Actors** = runtime identities; **hybrids** are seeded or created and then **placed into departments**, not owned by a single user.
- **Auth users** = humans; **many** users in the **same** department may act as the **same** hybrid actor (concurrent sessions allowed).
- Enforce **`lupo_actor_departments` + `lupo_auth_user_departments`** for web act-as lists; do not assume one-to-one user→actor for department hybrids.

---
