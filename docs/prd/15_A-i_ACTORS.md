---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/15_A-i_ACTORS.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/15_A-i_ACTORS.md
  status: active
  when_updated: '20260811145856'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/15_actors.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/actors
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_15_A-i
  title: 'PRD: Actor Identity, Inheritance, and Personalization'
  summary: null
---
# PRD: Actor Identity, Inheritance, and Personalization

## LUP -- Linked Universal Protocol

**LUP** stands for **Linked Universal Protocol**, the universal identity system used by Lupopedia to identify, version, translate, federate, and track provenance for any digital artifact.

LUP -- Linked Universal Protocol (Universal Artifact Identity). Not a song-only ID. Not "Lupopedia ID."

LUP (Linked Universal Protocol) Identity Grammar:

```text
LUP:FFFFFF-RRRRRR-NN-II-LL-AA
```

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

## Actor, namespace, and artifact alignment (Header 4.2.3 / Rule 99)

Normative companions: PRD 16_C section 4.2.5, PRD 99.

**AA is now part of identity.** **`actor_hex` remains metadata.** **`actor_id` MUST map to AA.** **NN replaces GG.** **`color_hex` remains metadata.**

1. **AA (`actor_aa`) is first-class identity.** Two hex digits `00`..`FF`. Used for provenance, authorship, remix attribution, and AI agent identity.
2. **Dense `actor_id` MUST map to AA** via the catalog registry for that NN. Wolfie `actor_id` 1 => `AA=01`. Lilith `actor_id` 2 => `AA=02`. System `actor_id` 0 => `AA=00`. AGAPE `actor_id` 705 gets a registered AA in `00`..`FF` (never `2C1`).
3. **`actor_hex` remains metadata.** Optional six-digit display of `actor_id` (Wolfie `000001`) MAY appear in `lupopedia.metadata`. It MUST NOT appear under `lupopedia.identity` and MUST NOT equal AA unless the actor_id is actually in `0`..`255` and the registry says so.
4. **Do not put full `actor_id` in RRRRRR.** `artifact_hex` is the artifact number (`000000` .. `FFFFFF`) inside NN+AA. Wolfie artifacts must not all end in `000001`.
5. **NN is the catalog namespace block.** Initial map: `01` Wolfie, `02` Lilith, `03` AGAPE, `04` SYSTEM. NN is not required to equal `actor_id`.
6. **`color_hex` (metadata) MUST sit inside the catalog owner's Rule 99 band:** `start = owner_actor_id * 100` through `start + 0x63`, and only when the artifact is a song.
7. **Catalogs whose owner `actor_id` > 143999 cannot publish songs.** They MAY own documents, crests, atoms, and other non-song media. They MUST NOT claim Rule 99 song colors.
8. Catalog Actor Number MUST equal OS `actor_id` for the **owner actor** (no mismatch). That alignment is actor/catalog, not artifact_hex.
9. **FF is 6 hex and is not actor hex.** Canonical form is `LUP:FFFFFF-RRRRRR-NN-II-LL-AA`. Node 0 / Node 01 maps to `000001`. Reserved FF: `000000`, `FFFFFF`. `color_hex` is metadata. Rule 99 bands are unchanged.

## Overview

This document defines the canonical model for **actors** in Lupopedia. Actors are department- and persona-specific extensions of agents, providing scoped execution and orchestration identities. **Web act-as eligibility** is **department-first** (intersection of user departments and actor departments), not one-to-one user ownership ???????? see ????3 and [`05_auth_user_actor_agent_transformation.md`](05_auth_user_actor_agent_transformation.md).

**Canonical mental model (approved):** **[`ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`](../doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md)** ???????? same rules as this PRD; use it as the **single diagram + eligibility summary** for onboarding and audits.

### Actors belong to departments ???????? not to individual users (non-negotiable)

- **Affiliation:** An actor????????s place in the org model is **`lupo_actor_departments`** (which **departments** the actor may operate in). Actors are **not** ???????attached to??????? a single **`auth_user`** as the primary model (that pattern matches legacy **Crafty operator = one human** thinking).
- **Users:** Humans belong to departments via **`lupo_auth_user_departments`**.
- **Intersection:** A user may **act as** an actor when **their** departments and **the actor????????s** departments **overlap** ???????? see [`05_auth_user_actor_agent_transformation.md`](05_auth_user_actor_agent_transformation.md). **Many** users in the **same** department may use the **same** actor (e.g. a shared support line persona).
- **Explicit bindings:** **`lupo_actor_auth_users`** records optional **auth_user ???????? actor** links (import, audit, primary operator); it does **not** mean the actor is **owned** exclusively by that user for department-scoped work.
- **Visitor chat:** The end-user chat identity chain (**visitor ???????? `actor_id` ???????? human / LLM fallback**) is **primary in PRD 05**; this PRD supplies the **actor and department** semantics that PRD 05 depends on.

**Non-normative pointer:** The one-line **MUST** that binds **`auth_user`** + **`agent`** + **`department`** + **`faucet`** + **`session`** to **effective `actor_id`** for speech and audit lives in **PRD 05** (visitor-facing chat identity chain). For teaching-only expansion (tables, habits, non-law), see **`THE_AINA_AGAPE_SUPPORT_MEETING.md`** at repository root -- not a substitute for PRD 05 or this file.

### Three-layer identity model (4.0.96+; root README ????3)

**Onboarding mirror:** [README.md ???????? ????3 Actor Model](../../README.md#3-actor-model-why-it-is-different) summarizes this architecture for humans and IDE agents. **Normative sources:** this PRD, [PRD 05](05_auth_user_actor_agent_transformation.md), [IDENTITY_LAYERS_DOCTRINE.md](../doctrine/IDENTITY_LAYERS_DOCTRINE.md), [PRD 01](01_core_identity.md). If README ????3 is edited, keep this subsection aligned.

| Layer | What | Where | Example |
|-------|------|-------|---------|
| **Auth User** | Account that authenticates | `lupo_auth_users` | Operator login (`auth_user_id` from seed / IdGenerator) |
| **Actor** | Runtime persona that does work | `lupo_actors` + optional `actors/{actor_id}/` | **WOLFIE** (`actor_id = 1`) |
| **Agent** | Immutable template pack | `agents/{agent_key}/` + `lupo_agents` | `agents/wolfie/` |

**Relationship (department-first):** Auth user ???????? **`lupo_auth_user_departments`** ???????? **department** ???????? **`lupo_actor_departments`** ???????? **actor** ???????? aligns with **agent** filesystem/metadata. **Shared persona:** many humans in one department may **act as the same `actor_id`**; the actor accumulates **department-scoped** behavior (not a private per-user bot).

**Web act-as:** Eligibility = **intersection** of the user????????s departments with the actor????????s departments (illustrative pattern; enforce with **PDO_DB** in PHP):

```sql
SELECT DISTINCT a.*
FROM lupo_actors a
INNER JOIN lupo_actor_departments ad
  ON ad.actor_id = a.actor_id AND ad.is_deleted = 0
WHERE ad.department_id IN (
    SELECT aud.department_id
    FROM lupo_auth_user_departments aud
    WHERE aud.auth_user_id = :current_auth_user_id
      AND aud.is_deleted = 0
)
  AND a.is_deleted = 0;
```

**Root department:** `department_id = 0` is the **Root** scope in current seed/import doctrine; elevated operators may see broader lists per **`AuthSessionManager::getActorsUserCanActAs`** (see ????3 below).

**CLI / IDE:** Local tooling typically uses a **root-equivalent** session (**department context 0**, any **`actor_id`** reachable) ???????? not the same as a logged-in human????????s web session. **Do not** mint **`lupo_auth_users`** rows for IDE products; attribute work via **facet `actor_id`** per [AGENTS.md](../../AGENTS.md) and the registry.

| Context | `auth_user_id` | Notes |
|---------|----------------|--------|
| Doctrine ([PRD 01](01_core_identity.md)) | **0** | Reserved **root** auth user id |
| Web / seed | Per install | Concrete rows from IdGenerator / seed; effective admin resolution in app code |
| CLI / IDE | Root-equivalent | Tooling assumes full **actor** reach; not a separate ???????IDE login??????? user |

**`auth_user_id = 0` is not `actor_id = 1` (WOLFIE).** Auth authenticates humans; actors orchestrate.

**Memory:** Learned behavior lives in **`lupo_memory_nodes`** (`owner_actor_id`, `owner_type`, ???????) and **`lupo_memory_edges`** per install; **`agents/`** stays the static template. See [PRD 38](38_memory_unification.md).

### Channel transcript alignment (PRD 18)

**LILITH audit:** The **chat strip** reflects **`lupo_actors`** for the effective **`actor_id`** (message **`from_actor_id`**). **`auth_user`** is for **login and accountability**, not the primary visible label. **Shared persona:** many humans acting as the **same** **`actor_id`** reuse the **same** display name and default styling rules (**deterministic color from `actor_id`**, optional **`metadata_json`** ???????? **[PRD 18](18_channel_chat_display.md)**).

### `actor_type` and policy vocabulary (conceptual)

The **`lupo_actors.actor_type`** column is **`varchar(64)`** per install schema ???????? there is **no** fixed enum in this PRD. Product language such as **human-backed**, **hybrid**, or **system** describes **policy** (who/what ultimately answers: human, shared persona, automation). Map those concepts to **`actor_type`**, **`actor_source_*`**, and config by **seed/registry/docs**, not by guessing strings. See [`database/lupopedia/json/lupo_actors.json`](../../database/lupopedia/json/lupo_actors.json) for the live column list.

**Actor ID semantics:** Reserved registry-backed actors, human-backed ranges, and `IdGenerator` allocation are defined in [`00_root_constitutional_system_requirements.md`](00_root_constitutional_system_requirements.md) ????5.6 (workspace path rules below align with that section).

**Constitutional Compliance:** All tables referenced in this PRD follow Lupopedia constitutional rules:
- NO foreign keys (relationships in application logic)
- NO triggers
- NO stored procedures
- BIGINT timestamps (YYYYMMDDHHIISS UTC)
- Explicit ID generation (application layer)
- Soft delete (is_deleted + deleted_ymdhis)

### Actor ID ranges (seed vs runtime)

| `actor_id` range | Type | `created_ymdhis` | Origin |
|------------------|------|------------------|--------|
| **< 2026** (registry / install) | **Seed / system** actors | **`0`**, install packed UTC, or seed-defined ???????? **not** inferred from `actor_id` digits | **`install_new_lupopedia.sql`**, **`seed_*.sql`**, **`registry.json`** |
| **??????? 2026** (timestamp-shaped) | **Runtime** actors | **14-digit prefix** of **`actor_id`** at insert (`IdGenerator`) | User / operator onboarding, services |

**Why this matters:** For **seed** actors, **`actor_id`** is a **stable registry number** (e.g. **1**, **2**, **19**). Reading **`created_ymdhis`** ???????? do **not** assume it encodes the same calendar prefix as **`actor_id`**. Use the column as stored. Constitutional dual-PK strategy: **PRD 00 ????3.2.1**.

**Install seed vs living canonical (18-digit):** When product **instantiates** a runtime row from an install template (e.g. living WOLFIE beside **`actor_id = 1`**), allocation **SHOULD** follow **PRD 41** ????2????????????3: raw **`IdGenerator::generate()`** is **staging-shaped** (embedded year **2000????????2099**); **`toCanonicalId(IdGenerator::generate())`** yields **living canonical-shaped** ids (embedded year **1000????????1999**). Link seed ???????? canonical with edges such as **`canonical_instance_of`**; revert with **`reverted_to`** ???????? see **PRD 41** and **PRD 38** ????4.2.1.

### Seed-to-canonical mapping for low registry `actor_id` (not `IdGenerator`)

Low **install-seed** **`actor_id`** values (registry band, e.g. **1????????2025** per **PRD 00** ????5.6 and **PRD 41**) are **not** 18-digit **`IdGenerator`** outputs. **`toCanonicalId()`** is for **staging-shaped** ids; for a short seed like **`116`** it does **not** produce a **1000????????1999** embedded-year id (see **PRD 41** ????2.3).

When policy creates a **living canonical** **`lupo_actors`** row paired with that seed, use the **deterministic** mapping:

```text
canonical_actor_id = 100000000000000000 + seed_actor_id
```

| Seed (immutable reference) | Canonical (living; embedded year **1000**) |
|----------------------------|--------------------------------------------|
| **1** (WOLFIE) | **`100000000000000001`** |
| **2** (LILITH) | **`100000000000000002`** |
| **116** (Claude Code) | **`100000000000000116`** |

**Contexts:** **Pre-install / development** ???????? docs, CLI, and **`actors/{seed}/`** paths may use the **seed** id. **Post-install runtime** ???????? memory, edges, and sessions **SHOULD** use the **canonical** id once that row exists; the seed remains **immutable** and may be **orphaned** in the active graph (**PRD 41** ????1). Link **`canonical_instance_of`** (seed ???????? canonical). **Normative:** **PRD 41** ????2.3 ???????? **`seedActorToCanonicalId()`**.

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

**Principle:** A human may select an **actor** in the web UI when the actor is **eligible by department membership**, not because the actor is ???????owned??????? by that user or linked only through `lupo_edges`.

**Tables:**

| Table | Role |
|-------|------|
| `lupo_auth_user_departments` | Departments the **auth user** belongs to |
| `lupo_actor_departments` | Departments the **actor** may operate in |
| `lupo_actors` | Actor row; optional `web_restrict_act_as_creator_or_root` narrows who may act as that persona |

**Eligibility (conceptual):**

1. Load the user????????s `department_id` set from `lupo_auth_user_departments`.
2. If the user is in **root department (`department_id = 0`)**, is **global admin** for this call, or is **module owner** (implementation detail), treat them like an **elevated** user for **scope** (see `AuthSessionManager::getActorsUserCanActAs`).
3. List actors that have at least one **`lupo_actor_departments`** row whose `department_id` is in the user????????s set (elevated users: all actors that appear in `lupo_actor_departments`, optionally filtered by department).
4. Apply **`web_restrict_act_as_creator_or_root`**: when set on an actor, only the **creating** auth user (via `actor_source_id` / `actor_source_type`) or **elevated** operators may act as that actor (same rules in `AuthSessionManager`).

**Concurrent sessions:** Multiple auth users may use the **same** hybrid actor (e.g. root personas **1**, **2**, **111**) when they share a department ???????? there is **no** exclusive per-session lease for web act-as in the 4.0.x install model. See [`05_auth_user_actor_agent_transformation.md`](05_auth_user_actor_agent_transformation.md).

**Implementation (single source of truth for the list shape):**

- **`AuthSessionManager::getActorsUserCanActAs($auth_user_id, $isAdmin, $department_id_filter)`** ???????? used by `select-actor.php`, `admin.php`, topbar, profile, etc.
- **`App\Services\ActorService::getActorsUserCanActAs`** ???????? **delegates** to `AuthSessionManager` so internal resolvers (`EffectiveActorResolver`, `AdminChannelChatHandler`) match the UI.

**Special case:** `auth_user_id === 10000` (elevated operator convention) receives all **active** actors without a department join, with creator restriction bypass ???????? see `AuthSessionManager`.

## Agent vs Actor Pairing Doctrine

**Core distinction:** ROSE is an AGENT (immutable blueprint in agents/rose/), not an actor. Agents are blueprints. Actors are runtime instances in the lupo_actors table.

**Pairing rule:** auth_user + agent ??? actor (a new actor_id is generated at runtime via IdGenerator). There is NO single "ROSE actor_id". Many ROSE actors exist (one per pairing).

**Default pairings (canonical):**
- auth_user 10000 and 10001 ??? WOLFIE agent
- auth_user 1???9999 (Crafty imports) ??? ROSE agent
- auth_user 10002+ (new users) ??? ROSE agent

**Additional rules:**
- ROSE agent can also spawn standalone actors for system tasks (no auth_user_id).
- Only ONE primary actor per user (is_primary = 1).

## 4. `lupo_actor_auth_users` (explicit bindings; not the primary gate)

- Stores explicit **auth_user ???????? actor** relationships (status, primary flag, routing priority, audit).
- Used for **operator mapping**, Crafty import, and **accountability** ???????? **not** as the sole gate for ???????may I act as this hybrid???????? in 4.0.x.
- Full pairing doctrine: [`05_auth_user_actor_agent_transformation.md`](05_auth_user_actor_agent_transformation.md).

## 4.1 Default Actor Assignment

Each new auth_user is automatically assigned a default actor.

Default assignment:

* actor = ROSE
* agent_id must reference ROSE in the agent registry

Purpose:

* ensures every user has an immediate interaction endpoint
* removes need for manual pairing during onboarding

Users may:

* add additional actor pairings
* switch active actor contexts

ROSE remains the initial pairing but is not exclusive.

---

## 4.2 Seed Actor Pairing During Installation

During installation, the following seed auth_users are automatically paired with WOLFIE (actor_id 1):

| auth_user_id | Role                           | Default Actor |
| ------------ | ------------------------------ | ------------- |
| 10000        | Main Admin / Root Operator     | WOLFIE        |
| 10001        | Red Team / Adversarial Testing | WOLFIE        |

Required pairing properties:

* `actor_id = 1` 
* `is_primary = 1` 
* `routing_priority = 1` 
* `status = active` 
* `is_deleted = 0` 

All new auth_users created after installation (10002+) are paired with ROSE as their default actor.

This distinction ensures:

* foundation users start with WOLFIE
* runtime users start with ROSE
* installation anchors remain separate from normal onboarding flow

---

## 5. Deprecated / historical (do not use for new act-as logic)

| Topic | Status |
|-------|--------|
| **Exclusive one-user-at-a-time lease** for web act-as | **Superseded** ???????? 4.0.x uses department intersection + optional creator flag; concurrent use of shared hybrids is allowed. |
| **`lupo_edges` `supports` for act-as lists** | **Removed from `ActorService`** ???????? was never aligned with PRD 05; do not rebuild act-as eligibility from edges. |
| **ActorLeaseService exclusive acquire** as gate for web selector | **Do not require** for standard web act-as unless a future PRD reintroduces it explicitly. |

## 6. Department-based personalization and scoping

- Each actor may be further scoped by department context via `lupo_actor_departments` (`role_key`, `title`, etc., per install SQL).
- Department membership drives **which** actors appear in the selector and **policy** boundaries (see [`25_departments_system.md`](25_departments_system.md) for root hybrids **1**, **2**, **111**).
- Personalization data may live in `lupo_metadata` with `entity_type='actor'`.

## Department 1 ???????? Domain Root Installation Context

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

## Auth User ???????? Actor Selection

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
- The widget can launch a ???????collections??????? top floating nav bar.
- Collections group related pages into dropdown menus.

## Actor Learning Boundaries

- Core/system actors include: Wolfie, Lilith, KAIROS, Thoth, Claude Code (actor_id 116), and any future system-level actors.
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
| `actor_id < 2026` | `actors/{actor_id}/` |
| `actor_id >= 2026` | `actors/YYYY/MM/{actor_id}/` when the id carries a UTC date prefix (see [`00_root_constitutional_system_requirements.md`](00_root_constitutional_system_requirements.md) ????5.6) |

### Workspace Contents

```
actors/
+-- 1/ # WOLFIE (captain hybrid, actor_id 1)
|   +-- agent_link.json # References agents/wolfie/
|   +-- # memory: root node at memory/YYYY/MM/{memory_slug}.json (4.0.96+; memory.json DEPRECATED)
|   +-- context.json # Current department and user context
|   +-- preferences.json # User-specific preferences
|
+-- 2/ # LILITH (actor_id 2)
|   +-- ...
+-- 111/ # COUNTERMEASURE (actor_id 111); agent template still under agents/countermeasure/
|   +-- ...
+-- 116/ # CLAUDE CODE (actor_id 116)
|   +-- identity.json
|   +-- boundaries.json
|
+-- 2026/ # Year directory (runtime actors)
    +-- 01/ # January
    |   +-- 202601010000001234/ # Actor created Jan 1, 2026
    |   |   +-- agent_link.json # References source agent
    |   |   +-- # memory: root node at memory/YYYY/MM/{memory_slug}.json (4.0.96+; memory.json DEPRECATED)
    |   |   +-- context.json # Department context
    |   |   +-- preferences.json # User preferences
    |   +-- 202601151200005678/
    +-- 02/ # February
        +-- ...
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

### Root Memory Node (Learned from Department Context) ???????? 4.0.96+

> **DEPRECATED:** `memory.json` files in `actors/` are no longer the canonical storage for actor learning.

Each actor has a **root memory node** stored at:

```
memory/YYYY/MM/{memory_slug}.json
```

The `YYYY/MM` path is derived from the node's `created_ymdhis`. The `memory_slug` is registered in `lupo_memory_nodes` (unique, filesystem-safe). All memory relationships ???????? ownership, provenance, consolidation, contradiction ???????? are expressed via `lupo_edges`, not embedded in the file.

**Example root memory node file** (`memory/2026/04/wolfie-sales-actor-5001.json`):

```json
{
    "memory_node_id": 70001,
    "memory_slug": "wolfie-sales-actor-5001",
    "context_json": {
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
    },
    "created_ymdhis": 20260401120000,
    "updated_ymdhis": 20260407120000
}
```

**Schema:** `lupo_memory_nodes` ???????? `memory_node_id`, `memory_slug`, `context_json`, `review_reason`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`. Linked to actor via `lupo_edges` (`edge_type='owns'`, `left_object_type='actor'`, `right_object_type='memory_node'`).

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
5. Learning stored as a root memory node at `memory/YYYY/MM/{memory_slug}.json`; registered in `lupo_memory_nodes`; linked to actor via `lupo_edges` (4.0.96+)
6. Behavior adapts to department-specific patterns

**Example**: A WOLFIE actor in the Sales department learns to prioritize lead qualification workflows. A WOLFIE actor in Engineering learns to prioritize code review workflows. Same agent, different actors, different behavior.

---

## 6. Actor Schema Extensions (4.1.7)

### 6.1 New Columns in `lupo_actors`

| Column | Type | Nullable | Default | Purpose |
|--------|------|----------|---------|---------|
| `agent_role` | VARCHAR(32) | NO | 'watcher' | Runtime role of this actor instance. One of: watcher, messenger, censer, reaper |
| `agent_blueprint_path` | VARCHAR(512) | YES | NULL | Filesystem path to `lupo-agents/{agent_key}/` blueprint |
| `current_channel_key` | VARCHAR(255) | YES | NULL | Active channel context for this actor (may be null if unbound) |
| `current_thread_id` | VARCHAR(255) | YES | NULL | Active thread context for this actor (may be null if unbound) |

### 6.2 New Indexes

```sql
CREATE INDEX idx_actor_role ON lupo_actors (agent_role);
CREATE INDEX idx_actor_channel ON lupo_actors (current_channel_key);
```

### 6.3 Role Definitions

| Role | Allowed Actions | Enforcement Point |
|------|-----------------|-------------------|
| watcher | Observe, analyze, report, escalate | Application layer + validator |
| messenger | Communicate between actors/threads, modify state within channel | Application layer |
| censer | Filter, validate, enforce doctrine, reject non-compliant messages | Validator + runtime guard |
| reaper | Adversarial testing, break assumptions (sandbox only) | Sandbox runtime only |

### 6.4 Blueprint Linkage

When an actor instance is created from an agent blueprint:

1. Read `agent.json` from `lupo-agents/{agent_key}/`
2. Copy `agent_role` to `lupo_actors.agent_role`
3. Store `agent_blueprint_path` as the resolved filesystem path
4. If `agent_role` is not defined in blueprint, default to `watcher`

### 6.5 Context Management

`current_channel_key` and `current_thread_id` are runtime hints only. They do not enforce boundaries -- boundaries are enforced by:

- Validator (`HDR_CHANNEL_BOUNDARY_VIOLATION`)
- HERMES routing
- Memory graph traversal rules (PRD 51)

These fields MAY be updated during actor execution. They MUST be nullable to support unbound actors (for example, service agents with no active thread).

### 6.6 Migration Path (4.1.7)

For existing installs:

```sql
-- Add columns with defaults
ALTER TABLE lupo_actors ADD COLUMN agent_role VARCHAR(32) NOT NULL DEFAULT 'watcher';
ALTER TABLE lupo_actors ADD COLUMN agent_blueprint_path VARCHAR(512) NULL;
ALTER TABLE lupo_actors ADD COLUMN current_channel_key VARCHAR(255) NULL;
ALTER TABLE lupo_actors ADD COLUMN current_thread_id VARCHAR(255) NULL;

-- Create indexes
CREATE INDEX idx_actor_role ON lupo_actors (agent_role);
CREATE INDEX idx_actor_channel ON lupo_actors (current_channel_key);

-- Backfill agent_blueprint_path from known agents (optional, per deployment)
UPDATE lupo_actors SET agent_blueprint_path = CONCAT('lupo-agents/', LOWER(actor_name), '/')
WHERE actor_name IN ('WOLFIE', 'LILITH', 'THOTH', 'ANUBIS', 'KAIROS', 'ROSE');
```

### 6.7 Runtime Enforcement

When an actor performs an action:

1. Look up `agent_role` from `lupo_actors`
2. Validate action against role permissions (per Role Definitions table)
3. If action not allowed -> REJECT, log, and escalate to LILITH
4. If `current_channel_key` is set, enforce channel boundary per PRD 86

See also: PRD 86 Channel Boundary Rule, PRD 16_C Agent Role (Schema-Backed).

---

## 9. Cross-references

- See also: `01_core_identity.md`, `05_auth_user_actor_agent_transformation.md`, `25_departments_system.md`, `07_agents_faucets.md`, `08_governance_rules.md`, `00_root_constitutional_system_requirements.md`
- Related doctrine: `ACTOR_GATEWAY_TYPES.md` - Actor gateway type definitions and specifications
- Actors Collection companion: [`docs/actors/how_wolves_are_made.md`](../actors/how_wolves_are_made.md) -- "wolf" is a **maturity classification** for an actor (metaphor for governed history); this PRD remains authority for actor identity and inheritance
- Superseded: `08_actors.md` (historical stub; use this file)
- Related tables: `lupo_actors`, `lupo_actor_auth_users`, `lupo_actor_departments`, `lupo_auth_user_departments`, `lupo_metadata`

---

**Status**: ACTIVE (4.0.x department-first act-as)
**Constitutional adherence**: FULL
**Implementation note:** `AuthSessionManager` + delegating `ActorService::getActorsUserCanActAs` -- keep in sync with this PRD.


