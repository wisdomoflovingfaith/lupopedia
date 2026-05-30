---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: Constitutional_AI_Governance.md
  web_path: https://www.lupopedia.com/lupopedia/Constitutional_AI_Governance.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/constitutional-ai-governance.toon
  atoms_toon: null
  transcript_jsonl: 0/development/constitutional-ai-governance
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: constitutional-ai-governance
  lupopedia.schema: documentation
  prd_cluster: 00_C-i_05_A-i_02_C-i_07_A-i_15_A-i
  title: Constitutional AI governance (PRD-grounded pairing and channels)
  summary: 'Maps tmp/DRAFT.md thesis to normative PRD 05/07/15/02: auth_user, agent, actor, departments, channels, projection, and accountability.'
---
<!--HUMAN_SEMANTIC -->
This file belongs to:
- Governing PRDs: 00_C (constitutional implementation), 05 (auth/actor/agent chain), 02 (channels), 07 (agents/faucets), 15 (actors)
- Channel: development
- Purpose: Bridge Captain log draft (tmp/DRAFT.md) with cited PRD examples for constitutional AI governance in Lupopedia.

This is a guide, not a normative PRD. On conflict, the PRD files win.
<!-- /HUMAN_SEMANTIC -->
# Constitutional AI governance (PRD-grounded pairing and channels)

This document ties the argument in **`tmp/DRAFT.md`** (humans as anchors, pairing versus supervision-only) to **how Lupopedia actually specifies** auth users, agents, actors, departments, and channels in the PRDs. Every numbered example below points to a **published PRD** path under **`docs/prd/`**.

---

## 1. What the draft claims (summary)

**`tmp/DRAFT.md`** says constitutional AI stacks often keep the human as an **external supervisor**, and argues that **persistent identity-bound pairing** between principals and agents is the missing pillar: agents become operational as **paired actors** inside **governed channels** with threads, artifacts, and tasks.

The PRDs **do** encode pairing, departments, and channel routing, but with **precise tables and rules** that differ slightly from informal "one human owns one bot" language. Sections 2--4 give the **normative** shape; section 5 maps the draft vocabulary onto it.

---

## 2. Pairing auth users to agents and actors (PRD examples)

### 2.1 Three layers (PRD 15)

**PRD 15** defines the three-layer model in a table (auth user, actor, agent):

| Layer | What | Where | Example (from PRD 15) |
|-------|------|-------|-------------------------|
| **Auth User** | Account that authenticates | `lupo_auth_users` | Operator login |
| **Actor** | Runtime persona that does work | `lupo_actors` + optional `actors/{actor_id}/` | **WOLFIE** (`actor_id = 1`) |
| **Agent** | Immutable template pack | `agents/{agent_key}/` + `lupo_agents` | `agents/wolfie/` |

Source: **`docs/prd/15_A-i_ACTORS.md`** (section "Three-layer identity model").

### 2.2 Agent is blueprint; actor is instance (PRD 07 and PRD 05)

**PRD 07** states the pairing doctrine in one line:

> **Pairing rule:** auth_user + agent yields actor (a new actor_id is generated at runtime via IdGenerator). There is NO single "ROSE actor_id". Many ROSE actors exist (one per pairing).

**Default pairings (canonical)** from the same PRD:

- auth_user 10000 and 10001 pair with **WOLFIE** agent
- auth_user 1-9999 (Crafty imports) pair with **ROSE** agent
- auth_user 10002+ (new users) pair with **ROSE** agent

Source: **`docs/prd/07_A-i_AGENTS_FAUCETS.md`** (section "Agent vs Actor Pairing Doctrine").

**PRD 05** repeats the same core distinction (ROSE is an **agent**, not an actor; many ROSE **actors** exist) and adds **department-scoped** web behavior: who may act as which actor is resolved from **`lupo_auth_user_departments`** joined to **`lupo_actor_departments`**, not from a single legacy "one actor per user" assumption.

Source: **`docs/prd/05_A-i_AUTH_USER_ACTOR_AGENT_TRANSFORMATION.md`** (opening blockquote and "Permission rule").

### 2.3 Explicit tables for bindings (PRD 05)

**PRD 05** lists concrete tables:

- **`lupo_actor_auth_users`** -- explicit auth_user--actor bindings (audit, primary operator mapping).
- **`lupo_actor_departments`** -- which departments an actor may operate in.
- **`lupo_auth_user_departments`** -- which departments a human belongs to.

Web act-as: user may select an actor only if their departments **intersect** the actor's departments (department-first rule).

Source: **`docs/prd/05_A-i_AUTH_USER_ACTOR_AGENT_TRANSFORMATION.md`** ("Pairing (canonical)", "Permission rule", "Database Requirements").

### 2.4 Many humans, one actor strip (PRD 05 + PRD 15)

**PRD 05** (alignment with PRD 18): channel messages store and render via **`from_actor_id`** joined to **`lupo_actors`**. **`auth_user` is not the bubble label**; multiple auth users may act as the **same** `actor_id` and share one strip persona (deterministic color from `actor_id`).

**PRD 15** states the same policy: **many users in the same department may use the same actor** (shared support line persona).

Sources: **`docs/prd/05_A-i_AUTH_USER_ACTOR_AGENT_TRANSFORMATION.md`** ("Alignment with PRD 18"); **`docs/prd/15_A-i_ACTORS.md`** ("Actors belong to departments -- not to individual users").

### 2.5 IDE facets are not minted as auth users (PRD 05)

**PRD 05** directs tooling: **do not** create **`lupo_auth_users`** for IDE facets; use **facet `actor_id`** per **`AGENTS.md`**.

Source: **`docs/prd/05_A-i_AUTH_USER_ACTOR_AGENT_TRANSFORMATION.md`** ("Root README alignment").

---

## 3. How channels work (PRD examples)

### 3.1 Channel, storage, projection, presence (PRD 02)

**PRD 02** defines four concepts in a normative table:

| Concept | Definition (from PRD 02) |
|---------|---------------------------|
| **Channel** | Shared **routing context** (namespace for threads, membership, and policy). Membership does **not** grant message-body visibility by itself. |
| **`lupo_dialog_messages`** | **Shared storage** for all routed lines. |
| **Projection** | **Filtered participant view**: a row is visible when the viewer is an endpoint -- **`from_actor_id`** or **`to_actor_id`** (Crafty **saidfrom** / **saidto** semantics under Lupopedia naming). |
| **Presence** | **Awareness only** (joined, online, idle). Presence does **not** imply read access to unrelated message bodies. |

**Default visibility rule:** a participant sees messages where **either** `from_actor_id` **or** `to_actor_id` equals that participant's actor id (plus product-defined broadcast cases). **Do not** assume channel membership implies full visibility.

Source: **`docs/prd/02_C-i_CHANNELS_DISCUSSIONS.md`** ("Projection and Presence Model (Normative)").

### 3.2 Routing fields: `to_actor_id` and broadcast (PRD 05)

**PRD 05** documents the DDL semantics used with channels:

- **`lupo_dialog_messages.to_actor_id`** -- **NULL** means broadcast.
- Direct line: **`to_actor_id`** = specific **`actor_id`** for UX routing (expected responder); **PRD 05** notes this does not make the row private to that actor in the same way a DM would; channel read rules remain tied to channel policy and **PRD 18**.

Source: **`docs/prd/05_A-i_AUTH_USER_ACTOR_AGENT_TRANSFORMATION.md`** ("Channel communication model").

### 3.3 Thread context for automation (PRD 05)

**PRD 05** states that agents and services **SHALL** process **all** messages in the **`channel_id` + `dialog_thread_id`** scope when building context -- **not only** lines where **`to_actor_id`** points at them (cross-reference to PRD 18, 36, 37).

Source: **`docs/prd/05_A-i_AUTH_USER_ACTOR_AGENT_TRANSFORMATION.md`** ("Thread context").

### 3.4 Service personas and registry (PRD 05)

When **`to_actor_id`** matches a **service persona** wired for automation (examples named in PRD: **LILITH**, **THOTH**, **MAAT**), the system **MAY** auto-respond via **ROSE** / PHP orchestration; **human** addressees are **not** auto-replied by default. Resolve **`actor_id` from `database/lupopedia/actors/registry.json`**.

Source: **`docs/prd/05_A-i_AUTH_USER_ACTOR_AGENT_TRANSFORMATION.md`** ("Service auto-response").

---

## 4. Constitutional layer (brief pointer)

Database and time rules (no FKs/triggers/procedures in the constitutional pattern, BIGINT UTC timestamps, application-generated IDs, soft deletes) live in **`docs/prd/00_C-i_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md`**. Multi-agent coordination (HERMES envelopes, transcript rules) is specified in **PRD 50** and **PRD 82**; this guide does not duplicate them.

---

## 5. Mapping `tmp/DRAFT.md` terms to PRD terms

| Draft term (tmp/DRAFT.md) | PRD-grounded reading |
|---------------------------|----------------------|
| **Auth_User as principal** | **`lupo_auth_users`** is login and accountability; visitor-facing chat still keys on **`actor_id`** per **PRD 05**. |
| **Stateless Agent** | **Agent** = immutable template under **`agents/`** and **`lupo_agents`** (**PRD 07**, **PRD 15**). |
| **Paired Actor** | **Actor** = runtime row in **`lupo_actors`**, often created from **auth_user + agent** pairing (**PRD 07**), scoped by **departments** (**PRD 05**, **PRD 15**). |
| **Channel as governed container** | **Channel** = routing context; messages in **`lupo_dialog_messages`**; visibility = **projection** over **`from_actor_id` / `to_actor_id`** (**PRD 02**). |
| **"No ghosts"** | Service personas still have **`actor_id`** from **`registry.json`**; IDE work uses **facet actor_id**, not fake **`lupo_auth_users`** (**PRD 05**). |

---

## 6. Closing note

**`tmp/DRAFT.md`** argues that supervision-only models hit drift, identity vacuum, and council explosion. The PRDs supply a **structural** response: **department-first act-as**, **explicit pairing tables**, **actor_id on the wire**, **projection-based channels**, and **registry-resolved service actors**. Implementers should treat **`docs/prd/05_A-i`**, **`07_A-i`**, **`15_A-i`**, and **`02_C-i`** as the source of truth for governance claims in product copy.

---

## References (normative)

- **`docs/prd/00_C-i_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md`**
- **`docs/prd/05_A-i_AUTH_USER_ACTOR_AGENT_TRANSFORMATION.md`**
- **`docs/prd/07_A-i_AGENTS_FAUCETS.md`**
- **`docs/prd/15_A-i_ACTORS.md`**
- **`docs/prd/02_C-i_CHANNELS_DISCUSSIONS.md`**
- **`tmp/DRAFT.md`** (Captain log input for this guide)

---

This output complies with Lupopedia Constitutional Root Rules for technical claims tied to the cited PRDs.
