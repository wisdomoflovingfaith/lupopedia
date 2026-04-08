## Context‑Typed, Status‑Aware, Directional Edged Memory Doctrine (4.0.96)

1. Memory in Lupopedia is represented as a directed graph of nodes and edges. 
   Each memory node is a first-class entity in the semantic network and may be 
   owned by actors, departments, auth_users, channels, federation nodes, or the 
   global system.

2. Every edge in the memory graph has FOUR dimensions:
   - **edge type** (the relationship)
   - **edge context** (the classification of the memory)
   - **edge status** (the epistemic support level)
   - **edge direction** (the traversal orientation)

3. **Edge Direction** defines whether the relationship is:
   - unidirectional (A → B)
   - bidirectional (A ↔ B)
   - restricted-direction (A → B but not B → A unless explicitly defined)
   Reverse traversal MUST NOT be inferred unless explicitly defined.

4. **Edge Type** defines the relationship between nodes, including but not 
   limited to:
   - influences
   - inherits
   - authored_by
   - observed_by
   - contradicts
   - supports
   - consolidates_from
   - refines
   - overrides

5. **Edge Context** defines the classification of the memory node. Context is 
   not based on the content of the memory, but on the structural support 
   provided by the graph. The primary context classifications are:
   - doctrine
   - experiential
   - system_generated
   - countermeasure_generated
   - summary
   - contradictory
   - deprecated

6. **Edge Status** defines the epistemic support level of the memory node:
   - **unsupported**: insufficient supporting edges; provisional memory.
   - **supported**: sufficient supporting edges; validated memory.
   - **needs_review**: conflicting, incomplete, or ambiguous edges requiring 
     agent or human intervention.

7. When `edge_status = 'needs_review'`, a **review_reason** MUST be provided. 
   This field explains *why* the edge requires review and *which agent* should 
   handle it. Examples include:
   - orphaned_edge
   - contradiction
   - new_doctrine
   - schema_drift
   - consolidation_candidate
   - integrity_unknown
   - human_escalation

   Agents use this field to determine their work queues:
   - ANUBIS handles: integrity_unknown, orphaned_edge
   - THOTH handles: schema_drift, contradiction, new_doctrine
   - KAIROS handles: consolidation_candidate
   - Human operator handles: human_escalation

8. Memory nodes may transition between statuses as edges are added, removed, 
   or reclassified. A node may move from unsupported → supported when 
   sufficient supporting edges accumulate.

9. Actors inherit memory edges from:
   - their department
   - their auth_user
   - their federation node
   - their assigned faucets
   - their assigned tasks

10. Memory traversal is context-aware and direction-aware. Actors may only 
    traverse edges permitted by their boundaries, department rules, auth_user 
    pairing, faucet assignments, and operational mode (live, simulation, 
    analysis).

11. No inference is allowed. All edges, contexts, statuses, directions, and 
    review reasons must be explicitly defined in PRDs, database rows, or 
    system-generated memory.

12. Memory is not a flat file. It is a structured, typed, classified, 
    status-aware, direction-aware graph. Traversal depth determines visible 
    memory; deeper traversal reveals more context, subject to boundary rules.

13. All changes to memory structure, edge types, edge contexts, edge statuses, 
    edge directions, or review reasons must be documented in PRDs and versioned.
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260407233553"
  file_path_from_root: "lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
  last_modified_utc: "20260407233553"
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
    - to: "lupo-database/lupopedia/json/lupo_actor_auth_users.json"
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
    - to: "README.md"
      type: references
      weight: 1.0
      reason: "Root README §3 — auth/actor/agent onboarding mirror (4.0.96+)"
lupopedia.footer:
  last_verified: '20260407233553'
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
---

> **Pairing (canonical):** Auth user ↔ actor **bindings** live in **`lupo_actor_auth_users`** (see [`15_actors.md`](15_actors.md)). **Who may act as which actor on the web** is resolved from **`lupo_auth_user_departments`** (user’s departments) joined to **`lupo_actor_departments`** (actor membership in departments). The same **hybrid** actor can therefore be used by **many** auth users who share a department. **`lupo_actors.web_restrict_act_as_creator_or_root`** still narrows specific personas when set. Older “one actor per user” pairing is **not** the 4.0.x model for department-scoped hybrids.

## Root README alignment (4.0.96+)

**Human-readable summary:** [README.md — §3 Actor Model](../../README.md#3-actor-model-why-it-is-different) — **Auth User** → **department intersection** → **shared Actor** → **Agent** template; collective intelligence per department; **web** vs **CLI/IDE** access rules.

- **Web:** Act-as eligibility remains **department-first** (this PRD + [`15_actors.md`](15_actors.md) §3).
- **CLI / IDE:** **Root-equivalent** tooling context (any **`actor_id`** for maintenance/scripts); **do not** create **`lupo_auth_users`** for IDE facets — use **facet `actor_id`** per [AGENTS.md](../../AGENTS.md).
- **Doctrine:** Reserved root **`auth_user_id = 0`** per [PRD 01](01_core_identity.md) — **not** the same as **`actor_id = 1` (WOLFIE)**.

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
