---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/05_A-i_AUTH_USER_ACTOR_AGENT_TRANSFORMATION.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/05_A-i_AUTH_USER_ACTOR_AGENT_TRANSFORMATION.md
  status: active
  when_updated: '20260728021358'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/05_auth_user_actor_agent_transformation.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/auth-user-actor-agent-transformation
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_05_A-i
  title: 'PRD: Auth User, Actor, Agent Transformation'
  summary: Auth user to actor to agent transformation model, department-scoped hybrids, and default agent pairing rules for Crafty imports and new users.
---
# PRD: Auth User, Actor, Agent Transformation

> **Pairing (canonical):** Auth user - actor **bindings** live in **`lupo_actor_auth_users`** (see [`15_actors.md`](15_actors.md)). **Who may act as which actor on the web** is resolved from **`lupo_auth_user_departments`** (user-s departments) joined to **`lupo_actor_departments`** (actor membership in departments). The same **hybrid** actor can therefore be used by **many** auth users who share a department. **`lupo_actors.web_restrict_act_as_creator_or_root`** still narrows specific personas when set. The old "one actor per user" pairing is **not** the 4.0.x model for department-scoped hybrids.

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## Root README alignment (4.0.96+)

**Human-readable summary:** [README.md - 3 Actor Model](../../README.md#3-actor-model-why-it-is-different) - **Auth User** - **department intersection** - **shared Actor** - **Agent** template; collective intelligence per department; **web** vs **CLI/IDE** access rules.

- **Web:** Act-as eligibility remains **department-first** (this PRD + [`15_actors.md`](15_actors.md) section 3).
- **CLI / IDE:** **Root-equivalent** tooling context (any **`actor_id`** for maintenance/scripts); **do not** create **`lupo_auth_users`** for IDE facets - use **facet `actor_id`** per [AGENTS.md](../../AGENTS.md).
- **Doctrine:** Reserved root **`auth_user_id = 0`** per [PRD 01](01_core_identity.md) - **not** the same as **`actor_id = 1` (WOLFIE)**.

## Visitor-facing chat identity chain (primary - this PRD)

**Normative order:** **PRD 05 (this file)** defines the visitor chat identity chain; **[`15_actors.md`](15_actors.md)** defines actor lifecycle and department membership; **[`25_departments_system.md`](25_departments_system.md)** positions departments in routing; **[`13_crafty_integration.md`](13_crafty_integration.md)** covers Crafty import and the operator - actor narrative shift.

**MUST (identity binding chain, speech and audit):** **`auth_user`** + **`agent`** + **`department`** + **`faucet`** + **`session`** -> **effective `actor_id`** for speech and for transcript or audit attribution.

1. **`actor_id` is the primary runtime identity** for the entity who answers the visitor - not a bare **`auth_user_id`** as the long-term sole key. The **actor** is **department-scoped** via **`lupo_actor_departments`** (same intersection idea as web act-as where the product applies it to chat).
2. **Conceptual chain:** visitor message - **department-scoped `actor_id`** for the thread - optional **LLM / automation** when install **keys**, **token/quota**, and **policy** allow - otherwise **human `auth_user`** in the **same department** (Crafty-compatible baseline) - otherwise **queue / offline / leave-message** flows.
3. **`auth_user`** remains **login, accountability, and human fallback** - it does **not** replace **`actor_id`** in chat architecture. Visitor chat must **not** be modeled only as visitor - **`auth_user`** (legacy operator row) without **`actor_id`** resolution.
4. **Department-scoped eligibility** for which actors may represent a department in chat aligns with **user departments intersecting actor departments** (see **`AuthSessionManager::getActorsUserCanActAs`** and related code paths).

### Alignment with PRD 18 (channel chat UI)

**LILITH audit (department-scoped model):** Channel messages **store and render** via **`from_actor_id`** joined to **`lupo_actors`** - that is the **primary** transcript identity. **`auth_user` is not the bubble label**; the UI shows the **actor** the session is acting as. **Multiple** **`auth_users`** may act as the **same** **`actor_id`** - **one** shared strip persona (name, deterministic color from **`actor_id`**, optional **`metadata_json`** overrides - see **[PRD 18](18_channel_chat_display.md)**).

**Design Q&A:** [`docs/implementations/13_crafty_integration/questions/THREAD_INDEX.md`](../implementations/13_crafty_integration/questions/THREAD_INDEX.md) (PRD alignment complete per Q3).

**Canonical mental model (approved):** [`ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`](../doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md) - single source for **`auth_user` / `department` / `actor`** joins, eligibility (**department intersection** first; **`lupo_actor_auth_users`** supplementary), visitor attribution, and Crafty contrast. This PRD stays normative for **visitor chain steps** and **PHP resolution paths**; do not contradict the doctrine.

### Channel communication model (multi-actor routing)

**Canonical DDL field:** **`lupo_dialog_messages.to_actor_id`** (**NULL** = broadcast). Directive text may say "said-to" / **`said_to_actor_id`** - **same semantics**.

- **Direct / addressed line:** **`to_actor_id`** = specific **`actor_id`** - product **routes** UX (expected responder, highlight). **Does not** make the row **private** to that actor - **channel members** who can read the channel **read all messages** in the thread (**PRD 18**).
- **Broadcast:** **`to_actor_id` NULL** - general channel traffic.

**Service auto-response:** When **`to_actor_id`** matches a **service persona** the deployment wires for automation (**LILITH**, **THOTH**, **MAAT**, etc. - **resolve `actor_id` from `database/lupopedia/actors/registry.json`**), the system **MAY** auto-respond via **ROSE** / PHP orchestration. **Human** addressees are **not** auto-replied by default.

**Thread context:** Agents and services **SHALL** process **all** messages in the **`channel_id` + `dialog_thread_id`** scope when building context - **not only** lines where **`to_actor_id`** points at them (**PRD 18**, **PRD 36**, **PRD 37** section 10.6).

**Out of scope:** **No** **`mention_actor_ids`** JSON column; **no** per-row visibility filter keyed on **`to_actor_id`** inside the same channel.

## Core Doctrine

## Permission rule (canonical, 4.0.x)

**Department-scoped act-as (web):** An auth user may select an actor in the web UI only if that actor has at least one **`lupo_actor_departments`** row in a department where the user has a row in **`lupo_auth_user_departments`**. Root department (`department_id = 0`) users see actors mapped to department 0 (and elevated operators may see broader lists per `AuthSessionManager`). This replaces legacy checks that compared a single `auth_user.department_id` to `actor.department_id` for hybrid pools.

**Actor auth bindings:** **`lupo_actor_auth_users`** remains the table for explicit auth_user-actor links (primary operator mapping, audit). It does **not** replace **`lupo_actor_departments`** for determining which departments may use this actor.

## Database Requirements
- **Canonical pairing table:** **`lupo_actor_auth_users`** - status and audit for auth_user-actor binding (see `15_actors.md`). Concurrent web sessions may map the same actor; there is no exclusive per-session lease row in install SQL.
- **`lupo_actors.web_restrict_act_as_creator_or_root`:** When set, web act-as lists and `updateActiveActor` enforce creator-or-bypass (root department, admin, module owner) in PHP.
- All IDs via IdGenerator::generate() (or explicit registry IDs where required), all timestamps BIGINT UTC `YYYYMMDDHHIISS` (no display width on integer types), no AUTO_INCREMENT, no FOREIGN KEYS, no UNSIGNED.

## Leasing logic (illustrative; production uses AuthService / AuthSessionManager)

Use **`lupo_auth_user_departments`** and **`lupo_actor_departments`** (not a single `department_id` column on the user row alone).

## Workflow: agent template - hybrid actor - department pool - auth users

1. **Agent** (template) is defined under **`lupo_agents` / `agents/`** (configuration).
2. **Actor** row is created or seeded (hybrid or system persona); **`actor_id` / `actor_name`** follow registry doctrine.
3. **Departments:** insert **`lupo_actor_departments`** so the actor belongs to one or more departments (`role_key` e.g. `hybrid`, `system`).
4. **Auth users** are linked to departments via **`lupo_auth_user_departments`** (users can be in multiple departments).
5. **Web session** chooses an allowed **`actor_id`** from the intersection of (user-s departments) and (actor-s departments), subject to **`web_restrict_act_as_creator_or_root`** and admin bypass rules in PHP.
6. **Optional:** **`lupo_actor_auth_users`** records explicit bindings for operators imported from Crafty or created in admin.

**Clarification (normative):** Web act-as eligibility is **department intersection first** (`lupo_auth_user_departments` x `lupo_actor_departments`). **`lupo_actor_auth_users` is supplementary** (explicit binding / audit / Crafty import / admin) -- it is **not** required for every department-scoped hybrid session. Confirm default pairing seed IDs against install/seed and registry before implementation; do not invent IDs from onboarding prose alone.

**Actors Collection companion:** [`docs/actors/how_wolves_are_made.md`](../actors/how_wolves_are_made.md) (maturity language is metaphor; this PRD remains authority for the transformation chain).

## Why This Model?
- Enables human/AI co-training, multi-user actor evolution, department-scoped access, agent autonomy, actor specialization, clean audit trails, and future multi-agent orchestration.
- Avoids identity drift and legacy mapping issues.

## Agent vs Actor Pairing Doctrine

**Core distinction:** ROSE is an AGENT (immutable blueprint in agents/rose/), not an actor. Agents are blueprints. Actors are runtime instances in the lupo_actors table.

**Pairing rule:** auth_user + agent yields an actor (a new actor_id is generated at runtime via IdGenerator). There is NO single "ROSE actor_id". Many ROSE actors exist (one per pairing).

**Default pairings (canonical):**
- auth_user 10000 and 10001 pair with WOLFIE agent
- auth_user 1-9999 (Crafty imports) pair with ROSE agent
- auth_user 10002+ (new users) pair with ROSE agent

**Additional rules:**
- ROSE agent can also spawn standalone actors for system tasks (no auth_user_id).
- Only ONE primary actor per user (is_primary = 1).

## Explicit notes for IDE agents
- **Agents** = template/configuration entities.
- **Actors** = runtime identities; **hybrids** are seeded or created and then **placed into departments**, not owned by a single user.
- **Auth users** = humans; **many** users in the **same** department may act as the **same** hybrid actor (concurrent sessions allowed).
- Enforce **`lupo_actor_departments` + `lupo_auth_user_departments`** for web act-as lists; do not assume one-to-one user-actor for department hybrids.

---
