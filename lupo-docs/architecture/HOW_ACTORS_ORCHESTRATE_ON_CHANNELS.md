---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md"
  web_path: "http://www.lupopedia.com/lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  delegation_chain: "wolfie:root"
  artifact_type: "architecture"
  artifact_kind: "orchestration"
  purpose: "Canonical in-depth explanation of how actors orchestrate on channels: identity, faucets, sessions, traits, roles, dialog, tasks. Schema and examples from TOONs."
  tags: ["actors", "channels", "orchestration", "faucets", "sessions", "traits", "roles", "4.0.69"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/ActorFaucetOntology.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/", type: "references", weight: 0.9 }
    - { to: "lupo-docs/architecture/cursor_actors_channels_semantic_architecture_4.0.69.md", type: "references", weight: 0.9 }
lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "wolfie"
---
# file: How Actors Orchestrate on Channels (canonical) — session: L-LUPO-ROOT-CURSOR — delegation: wolfie:root (faucet: cursor)

# How Actors Orchestrate on Channels

**Canonical location:** `lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md`

This document explains **how orchestration works** when actors operate on channels in Lupopedia. It uses the **actual schema** from the TOON files in `lupo-database/lupopedia/toon/` so that every table and column name matches the database. **Actors orchestrate; faucets execute; sessions carry runtime context.** Traits constrain actors; roles scope permissions to channels; tasks are transient work items.

---

## 1. The model in one paragraph

An **actor** is an orchestration identity stored in `lupo_actors` (canonical primary key: `actor_name`; unique secondary identifier: `actor_id`). The actor **coordinates and governs** work. They do not "run" directly: they operate **through** a **faucet** (execution surface), such as Cursor IDE or an LLM API, recorded in `lupo_agent_faucets`. **Sessions** (`lupo_sessions`) carry the runtime context: which actor, which faucet, which channel. On a **channel** (`lupo_channels`), the actor's ability to do things is determined by **traits** (intrinsic, in `lupo_actor_traits`) and **roles** (channel-local, in `lupo_actor_channel_roles`). **Dialog** (threads and messages) and **tasks** live in channel-scoped tables. Orchestration is the flow: **identity (actor) → execution (faucet) → context (session) → place (channel) → permission (trait + role) → action (dialog, task).**

---

## 2. Identity: actors (orchestration)

**Table (TOON):** `lupo_actors`  
**Primary key:** `actor_name` (varchar(64)). **Unique secondary identifier:** `actor_id` (bigint). Unique constraint on `slug`.

> **ACTOR PRIMARY KEY DOCTRINE (install SQL):** In the canonical schema, `actor_name` is the table primary key; `actor_id` is a unique secondary identifier. The DB identity model still uses `actor_id` as the operational numeric identity in relationships and application code (e.g. `from_actor_id`, `owner_actor_id`). Docs must not collapse these two concepts: **PK = actor_name** (canonical row identity), **actor_id = unique numeric handle** (used everywhere in FKs and APIs). See `install_new_lupopedia.sql` comments and ActorService resolution by name/id.

Relevant columns from the TOON:

| Column | Type | Meaning |
|--------|------|--------|
| `actor_name` | varchar(64) | **Primary key.** Canonical name (e.g. wolfie, lilith). |
| `actor_id` | bigint | Unique secondary identifier; reserved IDs from registry; used as operational identity in relationships. |
| `actor_type` | varchar(64) | human, agent, system, etc. |
| `slug` | varchar(255) | URL-safe identifier. |
| `name` | varchar(255) | Display name. |
| `primary_federation_node_id` | bigint | Default federation node (e.g. 1). |
| `paired_actor_id` | bigint | Paired human (e.g. 1000 = root). |
| `is_agent` | tinyint | 1 if non-human agent. |
| `created_ymdhis`, `updated_ymdhis` | bigint | UTC YmdHis timestamps. |

**Example (conceptual):**

```text
actor_id: 1
actor_name: wolfie
actor_type: agent
slug: wolfie
name: Wolfie
primary_federation_node_id: 1
paired_actor_id: 1000
```

The actor is the **who**. They hold rules, skills, and traits. They do **not** hold faucet config (temperature, model); that is in `lupo_agent_faucets`.

---

## 3. Execution: faucets (surfaces)

**Table (TOON):** `lupo_agent_faucets`  
**Primary key:** `agent_faucet_id` (bigint).

Relevant columns:

| Column | Type | Meaning |
|--------|------|--------|
| `agent_faucet_id` | bigint | Primary key. |
| `actor_id` | bigint | The actor this faucet serves (e.g. 1 = Wolfie). |
| `name` | varchar(100) | Display name. |
| `slug` | varchar(100) | Stable slug (e.g. cursor, antigravity). |
| `faucet_class` | varchar(32) | `ide` or `llm`. |
| `model_name`, `temperature`, `system_prompt`, etc. | various | Runtime config. |

**Example:**

```text
agent_faucet_id: 101
actor_id: 1
name: Cursor IDE
slug: cursor
faucet_class: ide
```

So: **Wolfie (actor_id 1) uses the Cursor faucet (slug `cursor`)**. The same actor might have another row for Antigravity (slug `antigravity`). Orchestration stays with the actor; execution is bound to the faucet.

---

## 4. Runtime context: sessions

**Table (TOON):** `lupo_sessions`  
**Primary key:** `session_id` (varchar(255)).

Relevant columns:

| Column | Type | Meaning |
|--------|------|--------|
| `session_id` | varchar(255) | Unique session key (e.g. L-LUPO-ROOT-CURSOR). |
| `federation_node_id` | bigint | Node (e.g. 1). |
| `actor_id` | bigint | Identity in this session. |
| `faucet_slug` | varchar(100) | Which faucet (e.g. cursor). |
| `faucet_instance_id` | varchar(100) | Instance/run identifier. |
| `actor_name` | varchar(64) | Denormalized. |
| `channel_id` | bigint | Current channel (e.g. 42). |
| `session_data` | text | Serialized context. |
| `metadata` | json | Extra context. |
| `created_ymdhis`, `last_seen_ymdhis`, `updated_ymdhis` | bigint | Timestamps. |

**Example:**

```text
session_id: L-LUPO-ROOT-CURSOR
federation_node_id: 1
actor_id: 1
faucet_slug: cursor
faucet_instance_id: run-abc123
channel_id: 42
```

So: "Wolfie (1) is on channel 42, via the Cursor faucet, in this session." Sessions **carry** this context; they don't define identity. Identity is the actor; execution is the faucet.

---

## 5. Place: channels

**Table (TOON):** `lupo_channels`  
**Primary key:** `channel_id` (bigint).

Relevant columns:

| Column | Type | Meaning |
|--------|------|--------|
| `channel_id` | bigint | Unique channel (e.g. 42). |
| `federation_node_id` | bigint | Node this channel belongs to. |
| `created_by_actor_id` | bigint | Creator. |
| `channel_key` | varchar(64) | Internal key. |
| `channel_name` | varchar(255) | Human-readable (e.g. "Lupopedia Development (general)"). |
| `channel_type` | varchar(32) | e.g. chat_room. |
| `status_flag`, `is_deleted` | tinyint | Lifecycle. |

**Example:**

```text
channel_id: 42
federation_node_id: 1
channel_key: lupopedia-development
channel_name: Lupopedia Development (general)
channel_type: chat_room
```

Orchestration on a channel means: **this actor, in this session, is acting in this channel.** Membership and permission are in the next two tables.

---

## 6. Membership: actor–channel link

**Table (TOON):** `lupo_actor_channels`  
**Primary key:** `actor_channel_id` (bigint). Unique on (`actor_id`, `channel_id`).

Relevant columns:

| Column | Type | Meaning |
|--------|------|--------|
| `actor_channel_id` | bigint | Primary key. |
| `actor_id` | bigint | Actor. |
| `channel_id` | bigint | Channel. |
| `status` | char(1) | e.g. A = active. |
| `created_ymdhis`, `updated_ymdhis` | bigint | Timestamps. |

**Example:**

```text
actor_channel_id: 5001
actor_id: 1
channel_id: 42
status: A
```

So: **Wolfie is a member of channel 42.** What they are **allowed to do** on that channel is defined by **roles**, not by membership alone.

---

## 7. Permission on a channel: roles

**Table (TOON):** `lupo_actor_channel_roles`  
**Primary key:** `actor_channel_role_id` (bigint).

Relevant columns:

| Column | Type | Meaning |
|--------|------|--------|
| `actor_channel_role_id` | bigint | Primary key. |
| `actor_id` | bigint | Actor. |
| `channel_id` | bigint | Channel. |
| `role_key` | varchar(64) | e.g. admin, member, captain, operator. |
| `created_ymdhis`, `updated_ymdhis` | bigint | Timestamps. |

**Example:**

```text
actor_channel_role_id: 701
actor_id: 1
channel_id: 42
role_key: captain
```

So: **On channel 42, Wolfie has the role `captain`.** The kernel (e.g. TraitEnforcer + `lupo_action_authorization`) uses `role_key` together with traits to decide if an action is allowed.

---

## 8. Intrinsic permission: traits

**Table (TOON):** `lupo_actor_traits`  
**Primary key:** `actor_trait_id` (bigint).

Relevant columns:

| Column | Type | Meaning |
|--------|------|--------|
| `actor_trait_id` | bigint | Primary key. |
| `actor_id` | bigint | Actor. |
| `trait_key` | varchar(128) | e.g. CAPABILITY_ORCHESTRATION, CAPABILITY_COMMUNICATION. |
| `trait_value` | varchar(512) | Optional value. |
| `federation_node_id` | bigint | Scope (default 1). |
| `created_by_actor_id` | bigint | Who assigned. |
| `created_ymdhis`, `updated_ymdhis` | bigint | Timestamps. |

**Example:**

```text
actor_trait_id: 1
actor_id: 1
trait_key: CAPABILITY_ORCHESTRATION
trait_value: primary
federation_node_id: 1
```

Traits are **actor-scoped**, not channel-scoped. Roles are **channel-scoped**. An action can require "has trait X **or** has role Y on channel Z" (see `lupo_action_authorization`).

---

## 9. What actions are allowed: action authorization

**Table (TOON):** `lupo_action_authorization`  
**Primary key:** `action_authorization_id` (bigint). Unique on `action_key`.

Relevant columns:

| Column | Type | Meaning |
|--------|------|--------|
| `action_authorization_id` | bigint | Primary key. |
| `action_key` | varchar(100) | e.g. dialog.send_message, channel.create. |
| `description` | text | Human-readable. |
| `required_trait_keys` | text | JSON array of trait_key; any match can grant. |
| `required_role_keys` | text | JSON array of role_key; any match (on channel) can grant. |
| `requires_all_conditions` | tinyint | 1 = all required must match; 0 = any. |
| `created_by_actor_id` | bigint | Who defined. |

**Example:**

```text
action_key: dialog.send_message
description: Send message in channel
required_trait_keys: ["CAPABILITY_COMMUNICATION"]
required_role_keys: ["member", "operator", "captain"]
requires_all_conditions: 0
```

So: to send a message, the actor must have trait `CAPABILITY_COMMUNICATION` **or** one of the roles `member`, `operator`, or `captain` on that channel. The kernel checks this **before** performing the action (e.g. in the channel send API via TraitEnforcer).

---

## 10. Orchestrating dialog: threads and messages

**Threads (TOON: `lupo_dialog_threads`)**  
**Primary key:** `dialog_thread_id` (bigint).

Relevant columns:

| Column | Type | Meaning |
|--------|------|--------|
| `dialog_thread_id` | bigint | Primary key. |
| `channel_id` | bigint | Channel. |
| `federation_node_id` | bigint | Node. |
| `created_by_actor_id` | bigint | Actor who created the thread. |
| `title` | varchar(255) | Thread title. |
| `project_slug`, `task_name` | varchar | Optional scope. |
| `status` | varchar(64) | e.g. Open. |
| `last_message_ymdhis` | bigint | Last activity. |
| `created_ymdhis`, `updated_ymdhis` | bigint | Timestamps. |

**Messages (TOON: `lupo_dialog_messages`)**  
**Primary key:** `dialog_message_id` (bigint).

Relevant columns:

| Column | Type | Meaning |
|--------|------|--------|
| `dialog_message_id` | bigint | Primary key. |
| `dialog_thread_id` | bigint | Thread. |
| `channel_id` | bigint | Channel. |
| `from_actor_id` | bigint | Sender (actor). |
| `source_faucet_slug` | varchar(100) | Faucet used (e.g. cursor). |
| `source_faucet_instance_id` | varchar(100) | Instance. |
| `to_actor_id` | bigint | Optional recipient. |
| `message_text` | varchar(1000) | Body. |
| `message_type` | varchar(64) | e.g. text. |
| `created_ymdhis`, `updated_ymdhis` | bigint | Timestamps. |

---

## 11. Orchestrating work: tasks

**Table (TOON):** `lupo_tasks`  
**Primary key:** `task_id` (bigint). Unique on (`task_key`, `channel_id`).

Relevant columns:

| Column | Type | Meaning |
|--------|------|--------|
| `task_id` | bigint | Primary key. |
| `task_key` | varchar(64) | Stable key per channel. |
| `channel_id` | bigint | Channel. |
| `owner_actor_id` | bigint | Actor who owns the task. |
| `acting_as_actor_id` | bigint | Optional actor performing. |
| `title` | varchar(255) | Title. |
| `task_status` | varchar(64) | e.g. pending, active, completed. |
| `task_type`, `task_priority` | varchar(64) | Classification. |
| `created_ymdhis`, `started_ymdhis`, `completed_ymdhis` | bigint | Lifecycle. |

Tasks are **transient work items** on a channel; the actor (owner or acting_as) is the orchestration identity. **Do not use** `assigned_to_actor_id`; the canonical schema uses `owner_actor_id` and `acting_as_actor_id` only.

---

## 12. Resource bundles (collections)

**Tables (TOON):** `lupo_collections`, `lupo_collection_tabs`, `lupo_collection_tab_map`, `lupo_collection_tab_paths`, `lupo_actor_collections`.

Collections are **channel-scoped resource bundles** that group artifacts, content, URLs, and paths for menus and sidebars. They support:

- **Structured resource grouping** — Tabs inside a collection (e.g. Docs, Links); entries in each tab via `lupo_collection_tab_map` with `item_type`: `artifact`, `content`, `url`, `path`.
- **Channel-local navigation** — Collections with `channel_id = current_channel` are used for the channel sidebar and channel resource views.
- **UI menu source** — Collections with `is_nav_menu = 1` are top-level nav (dropdowns); `nav_icon` and tab order drive header/sidebar rendering.

Resolution: `CollectionTabsService::getCollectionsForNavMenu()` for global nav; `CollectionTabsService::getCollectionsForChannel($channelId)` for channel collections. See `lupo-docs/doctrine/COLLECTIONS_DOCTRINE.md` and `lupo-docs/specs/WEB_NAVIGATION_ARCHITECTURE.md`.

---

## 13. End-to-end flow (example)

1. **Identity** — Wolfie is actor_id 1 in `lupo_actors` (row keyed by actor_name = 'wolfie').
2. **Faucet** — Cursor is a row in `lupo_agent_faucets` with `actor_id = 1`, `slug = 'cursor'`, `faucet_class = 'ide'`.
3. **Session** — Session L-LUPO-ROOT-CURSOR in `lupo_sessions`: `actor_id = 1`, `faucet_slug = 'cursor'`, `channel_id = 42`.
4. **Channel** — Channel 42 in `lupo_channels`.
5. **Membership** — Row in `lupo_actor_channels`: `actor_id = 1`, `channel_id = 42`.
6. **Roles** — Row in `lupo_actor_channel_roles`: `actor_id = 1`, `channel_id = 42`, `role_key = 'captain'`.
7. **Traits** — Row in `lupo_actor_traits`: `actor_id = 1`, `trait_key = 'CAPABILITY_COMMUNICATION'`.
8. **Authorization** — For `dialog.send_message`, `lupo_action_authorization`; TraitEnforcer checks; allow.
9. **Action** — Insert into `lupo_dialog_messages` with `from_actor_id = 1`, `source_faucet_slug = 'cursor'`, etc.
10. **Task** — Row in `lupo_tasks` with `owner_actor_id = 1`, `channel_id = 42`.

---

## 14. Summary table (TOON-backed)

| Concept | Table | Key columns | Purpose |
|--------|--------|-------------|---------|
| Actor | `lupo_actors` | `actor_name` (PK), `actor_id` (unique), `slug` | Orchestration identity |
| Faucet | `lupo_agent_faucets` | `agent_faucet_id`, `actor_id`, `slug`, `faucet_class` | Execution surface |
| Session | `lupo_sessions` | `session_id`, `actor_id`, `faucet_slug`, `faucet_instance_id`, `channel_id` | Runtime context |
| Channel | `lupo_channels` | `channel_id`, `channel_key`, `channel_name` | Place/context |
| Membership | `lupo_actor_channels` | `actor_channel_id`, `actor_id`, `channel_id` | Actor on channel |
| Role | `lupo_actor_channel_roles` | `actor_channel_role_id`, `actor_id`, `channel_id`, `role_key` | Channel-local permission |
| Trait | `lupo_actor_traits` | `actor_trait_id`, `actor_id`, `trait_key`, `federation_node_id` | Intrinsic capability |
| Action auth | `lupo_action_authorization` | `action_authorization_id`, `action_key`, `required_trait_keys`, `required_role_keys` | What is allowed |
| Thread | `lupo_dialog_threads` | `dialog_thread_id`, `channel_id`, `created_by_actor_id` | Conversation thread |
| Message | `lupo_dialog_messages` | `dialog_message_id`, `from_actor_id`, `source_faucet_slug`, `channel_id` | Single message |
| Task | `lupo_tasks` | `task_id`, `task_key`, `channel_id`, `owner_actor_id` | Transient work item |
| Collection | `lupo_collections` | `collection_id`, `channel_id`, `is_nav_menu`, `nav_icon` | Channel-scoped resource bundle |

---

## 15. References

- **Identity and layers:** `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`
- **Actor vs faucet:** `lupo-docs/doctrine/ActorFaucetOntology.md`
- **Schema (TOONs):** `lupo-database/lupopedia/toon/`
- **Canonical architecture:** `lupo-docs/architecture/cursor_actors_channels_semantic_architecture_4.0.69.md`
- **Authorization:** `lupo-docs/doctrine/AUTHORIZATION_DOCTRINE.md`
- **Traits:** `lupo-docs/doctrine/TRAITS_DOCTRINE.md`
- **Faucet traceability:** `lupo-docs/doctrine/FAUCET_TRACEABILITY_DOCTRINE.md`
- **Collections (resource bundles):** `lupo-docs/doctrine/COLLECTIONS_DOCTRINE.md`
- **Web navigation:** `lupo-docs/specs/WEB_NAVIGATION_ARCHITECTURE.md`
