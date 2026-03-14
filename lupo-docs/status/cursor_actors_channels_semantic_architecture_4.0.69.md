---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md"
  web_path: "http://www.lupopedia.com/status/cursor_actors_channels_semantic_architecture_4.0.69"
  last_modified_utc: "20260311"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  delegation_chain: "wolfie:root"
  artifact_type: "status"
  artifact_kind: "architecture"
  purpose: "Canonical architecture of actors, channels, edges, semantic layer, and deployment; aligns with install schema, TOONs, and doctrine; supersedes brainstorm_on_actors_and_channels"
  tags: ["actors", "channels", "semantic", "edges", "installation", "a2a", "4.0.69"]
lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  session_name: "L-LUPO-ROOT-CURSOR"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  federation_node_id: 1
  context_source: "session_file"
  human_actor_name: "root"
  paired_actor_id: 1000
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/ActorFaucetOntology.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/COMMUNICATION_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "implements", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/", type: "references", weight: 0.9 }
    - { to: "lupo-database/lupopedia/toon/", type: "references", weight: 0.9 }
lupopedia.footer:
  last_verified: "20260311"
  last_verified_by: "wolfie"
---
# file: Actors, Channels, and Semantic Architecture (4.0.69) — session: L-LUPO-ROOT-CURSOR — delegation: wolfie:root (faucet: cursor) — web_path: http://www.lupopedia.com/status/cursor_actors_channels_semantic_architecture_4.0.69

# Actors, Channels, and Semantic Architecture (v4.0.69)

**Canonical location:** `lupo-docs/architecture/cursor_actors_channels_semantic_architecture_4.0.69.md` — prefer that file for edits and references.

This document is the **canonical status** for how Lupopedia models actors, channels, edges, semantic information, and deployment. It is based on the **actual database schema** in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`, the **TOON** files in `lupo-database/lupopedia/toon/` and `lupo-database/lupopedia/toon/`, and the doctrine in `lupo-docs/doctrine/`. It corrects and extends the earlier brainstorm (`docs/status/brainstorm_on_actors_and_channels.md`) by aligning with all tables, edges, and documented semantics.

---

## 1. Installation and deployment (shared hosting)

Lupopedia is **always** installed in a **subdirectory of the document root**, not at web root. This is required for shared hosting and auto installers (e.g. Softaculous, Installatron).

- **Typical path:** `public_html/lupopedia/` or `user/u/username/public/lupopedia/` where **lupopedia** is the project folder name. The folder name may differ (e.g. `livehelp`, `support`); never hardcode it.
- **URLs and paths:** All internal URLs and asset paths MUST use the **`LUPOPEDIA_PUBLIC_PATH`** constant (e.g. `LUPOPEDIA_PUBLIC_PATH . '/login'`). This constant is set from the actual install folder.
- **Config:** **`lupopedia-config.php`** may be placed **above the web root** for security (e.g. one level up from document root). The bootstrap looks for it above docroot, then inside the install directory.
- **Implication:** The application is “backwards” from a typical “app above web root” layout: the project lives inside a web-accessible folder so it can run on shared servers. Only the config file is allowed outside.

**Reference:** `lupo-docs/doctrine/CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md`, `AGENTS.md`, `README.md`.

### 1.1 Fallback when database is not accessible

When the database is **not accessible** (e.g. offline, migration, or connection failure), the system MAY read from **file-based sources** under **`lupo-database/`**:

- **Markdown (MD) files** — Session files (`lupo-database/sessions/*.md`), doctrine, status docs, and LUPOPEDIA-headed artifacts can supply session context, actor/channel metadata, and required fields.
- **CSV files** — Table snapshots under `lupo-database/lupopedia/csv/` (and any sibling CSV layout) provide schema and optional data for reference. See `lupo-database/lupopedia/csv/README.md`.

**Behavior:** Database remains the source of truth when available. Fallback to MD/CSV is for resilience and tooling only; runtime enforcement and canonical state stay DB-backed when the DB is reachable.

---

## 2. What Lupopedia controls

Lupopedia is a **Semantic OS** built on Crafty Syntax Live Help 3.7.5. It:

- **Semantic information:** Content graph, atoms, edges, metadata, truth knowledge, and LUPOPEDIA HEADERS stored in `lupo_metadata`.
- **Conversational channels:** Development and governance contexts (e.g. Channel 42 = Lupopedia Development (general)) with dialog threads and messages.
- **Channel-as-A2A:** Channels function in an **actor-to-actor (A2A)** style: all **actors** on a channel work together on **tasks**, **logs**, and **threads**. Membership is in `lupo_actor_channels`; roles in `lupo_actor_channel_roles`; communication in `lupo_dialog_threads` and `lupo_dialog_messages`; tasks in `lupo_tasks`. No single “owner”; the channel is the shared context.

---

## 3. Actors: identity and representation

**Actors** are the orchestration identities of Lupopedia; they coordinate and govern through faucets, sessions, channels, rules, and traits. **Faucets** are execution surfaces, not identities.

**Table:** `lupo_actors` (with table prefix). **Primary key:** `actor_name` (varchar(64)); **unique secondary identifier:** `actor_id` (bigint). Canonical row identity is `actor_name`; operational identity in relationships and code is `actor_id`. **Reserved ID doctrine:** No AUTO_INCREMENT; IDs come from `lupo_registry_open` or reserved constants. **Human Actor ID doctrine:** Human actors MUST have `actor_id >= 1000` (see `lupo-docs/doctrine/HumanActorIdDoctrine.md`).

| Concept | Storage / notes |
|--------|------------------|
| **Actor types** | `actor_type`: human, agent, ide_agent, system, etc. (Legacy/transitional: `ide_agent` in schema; in doctrine, IDE surfaces are **faucets**, not actors.) |
| **Slug** | `slug` — URL-safe identifier |
| **Pairing** | `paired_actor_id` — e.g. IDE faucet session paired to human (10000 = root) |
| **Extended state** | `metadata_json` — opaque blob for runtime/config |
| **Capabilities** | `lupo_actor_capabilities` — capability keys per actor |
| **Traits (intrinsic)** | `lupo_actor_traits` — actor-scoped trait_key/trait_value; intrinsic constraints only (see DESIGN_NOTE_LUPO_ACTOR_TRAITS_4.0.69) |
| **Actor–actor graph** | `lupo_actor_edges` — source_actor_id, target_actor_id, edge_type, domain_id, weight, properties |
| **Metadata (headers, skills, rules)** | `lupo_metadata` — entity_type=`actor`, entity_id=actor_id; channel-scoped when channel_id set |

**IDE surfaces (Cursor, Antigravity, Windsurf, Codex, JetBrains, Warp, etc.):** Per **Actor–Faucet ontology**, they are **faucets**, not actors. Identity (e.g. Wolfie) is the **actor**; the IDE is the **faucet** in `lupo_agent_faucets` with `faucet_class = 'ide'`. Session and attribution use `actor_id` for the identity operating through that faucet.

**Reference:** `lupo-docs/doctrine/ActorFaucetOntology.md`, `lupo-docs/doctrine/HumanActorIdDoctrine.md`, RESERVED ID doctrine in `.cursor/rules/`.

---

## 4. Channels: context and A2A

**Table:** `lupo_channels`. Primary key: `channel_id`. **Channel 42** = **Lupopedia Development (general)** (human-readable `channel_name` in seed/docs).

| Column | Purpose |
|--------|--------|
| `channel_id` | Unique channel ID (registry/reserved). |
| `channel_key` | Internal key (e.g. `lupopedia-development`). |
| `channel_name` | Human-readable name (e.g. "Lupopedia Development (general)"). |
| `channel_slug`, `channel_type` | Slug and type (e.g. chat_room). |
| `federation_node_id` | Domain/node this channel belongs to. |
| `metadata_json`, `aal_metadata_json` | Channel metadata; A2A-style metadata when used. |

**Channel membership and roles:**

- **`lupo_actor_channels`** — which actors are on which channel (`actor_id`, `channel_id`, status, etc.).
- **`lupo_actor_channel_roles`** — role per actor per channel (e.g. `role_key = 'admin'`).

**Channel–content and channel–department:**

- **`lupo_channel_departments`** — channel ↔ department linkage.
- **Content** can be scoped by `channel_id` in `lupo_contents` and via **`lupo_edges`** (e.g. HAS_CONTENT: left = channel, right = content).

All **dialog** for a channel uses **`lupo_dialog_threads`** and **`lupo_dialog_messages`** with the same `channel_id`. **Tasks** for the channel use **`lupo_tasks`** with `channel_id`. So: channels are the shared context for **tasks**, **logs** (e.g. audit, rule_logs), and **threads/messages**.

---

## 5. Edges and semantic layer

### 5.1 Generic edge graph: `lupo_edges`

**Purpose:** Typed relationships between any two objects (content, channel, actor, etc.). Replaces legacy edge/entity-edge tables.

| Key columns | Purpose |
|-------------|--------|
| `left_object_type`, `left_object_id` | Subject of the edge (e.g. `channel`, `content`). |
| `right_object_type`, `right_object_id` | Object (e.g. `content`, `actor`). |
| `edge_type` | e.g. `HAS_CONTENT`, `HAS_MEMBER`, `REFERENCES`. |
| `edge_category`, `edge_description` | Optional classification and description. |
| `channel_id`, `channel_key` | Channel scope when applicable. |
| `semantic_weight`, `relationship_type` | Weight and type (e.g. semantic). |
| `actor_id` | Attribution. |
| `domain_id` | Federation/domain. |

**Content → channel resolution:** To get `channel_id` for a content, query `lupo_edges` with `edge_type = 'HAS_CONTENT'`, `right_object_type = 'content'`, `right_object_id = content_id`; `left_object_id` is the channel_id.

### 5.2 Actor graph: `lupo_actor_edges`

**Purpose:** Directed relationships between actors (e.g. delegation, pairing) within a domain.

| Key columns | Purpose |
|-------------|--------|
| `source_actor_id`, `target_actor_id` | Actor pair. |
| `edge_type` | Relationship type. |
| `domain_id` | Domain scope. |
| `weight`, `properties` | Optional weight and JSON. |

### 5.3 Semantic and content tables

| Table | Purpose |
|-------|--------|
| **`lupo_atoms`** | Named atoms per context (atom_name, context_id, value_json, summary, tags). |
| **`lupo_contents`** | Content items: title, slug, body, content_type, `channel_id`, `federation_node_id`, FLIP/LUPOPEDIA header fields (file_path_from_root, file_last_modified_*), atom_mappings, hashtags, engagement caches, etc. |
| **`lupo_semantic_index`** | Consolidated semantic index: semantic_type, slug, name, title, relationships (source/target content/page), entity_type/entity_id, layer, timeframe. |
| **`lupo_truth_knowledge`** | Truth/QA graph: truth_type, question/answer/evidence/source/topic/relation IDs, object/left/right refs, scores, status. |
| **`lupo_metadata`** | Entity–attribute–value plus channel-scoped and hierarchical metadata; backs LUPOPEDIA HEADERS (root → blocks → properties). Columns: entity_type, entity_id, channel_id, parent_metadata_id, class_name, property_key, property_value, meta_type. |

---

## 6. Communication: dialog tables only

**Doctrine:** All conversation and messaging use the **dialog** tables (see `lupo-docs/doctrine/COMMUNICATION_DOCTRINE.md`). There are **no** `lupo_threads` or `lupo_messages`; those were removed in 4.0.69.

| Table | Purpose |
|-------|--------|
| **`lupo_dialog_channels`** | Per-channel dialog metadata (channel_id, channel_name, file_source, speaker, target, message_count). |
| **`lupo_dialog_threads`** | Threads per channel: dialog_thread_id, channel_id, project_slug, task_name, status, created_by_actor_id, last_message_ymdhis. |
| **`lupo_dialog_messages`** | Messages: dialog_message_id, dialog_thread_id, channel_id, from_actor_id, to_actor_id, message_text, message_type, read_by_actor_id, mood_rgb, etc. |

Channel 42 discussions and version threads all use these tables with `channel_id = 42`.

---

## 7. Rules, tasks, and registry

| Table | Purpose |
|-------|--------|
| **`lupo_rules`** | Rule registry: rule_id, rule_name, rule_type, rule_script (JSON), severity, etc. Explicit IDs (no AUTO_INCREMENT for rule_id). |
| **`lupo_rule_targets`** | Attachment of rules to targets: target_table (e.g. channels, actors), target_id (e.g. 42), rule_id, priority, applied_by_actor_id. |
| **`lupo_rule_logs`** | Rule evaluation log: rule_id, target_table, target_id, actor_id, result, created_ymdhis. |
| **`lupo_tasks`** | Tasks per channel: task_id, task_key, channel_id, owner_actor_id, task_type, task_status, task_priority, metadata_json, etc. Explicit task_id (reserved ID doctrine). |
| **`lupo_registry`** | Entity registry: entity_type, entity_index (e.g. channel_id, actor_id), federation_node_id, is_kernel, metadata. |
| **`lupo_registry_open`** | Open/slot tracking for ID allocation (entity_type, entity_index_id). |

---

## 8. Full table list (install schema)

Tables are defined in **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`**. TOONs (column/type reference) live in **`lupo-database/lupopedia/toon/*.toon.json`** and **`lupo-database/lupopedia/toon/*.toon`**. Count and list below are for orientation; always confirm against the install SQL and TOONs.

### Actors and agents

`lupo_actors`, `lupo_banned_actors`, `lupo_bans_log`, `lupo_actor_actions`, `lupo_actor_capabilities`, `lupo_actor_channels`, `lupo_actor_channel_roles`, `lupo_actor_collections`, `lupo_actor_conflicts`, `lupo_actor_departments`, `lupo_actor_apps`, `lupo_actor_edges`, `lupo_actor_traits`, `lupo_actor_handshakes`, `lupo_actor_moods`, `lupo_actor_reply_templates`, `lupo_agents`, `lupo_agent_context_snapshots`, `lupo_agent_dependencies`, `lupo_agent_experiences`, `lupo_agent_external_events`, `lupo_agent_faucets`, `lupo_agent_faucet_credentials`, `lupo_agent_files`, `lupo_agent_heartbeats`, `lupo_agent_tool_calls`, `lupo_agent_versions`.

### Metadata and semantic

`lupo_metadata`, `lupo_atoms`, `lupo_contents`, `lupo_edges`, `lupo_semantic_index`, `lupo_truth_knowledge`, `lupo_truth_answers`, `lupo_artifacts`, `lupo_artifact_chunks`.

### Channels and dialog

`lupo_channels`, `lupo_channel_boot_detail`, `lupo_channel_boot_lifecycle`, `lupo_channel_boot_detail_lifecycle`, `lupo_channel_escalations`, `lupo_channel_escalation_rules`, `lupo_channel_files`, `lupo_channel_state`, `lupo_channel_departments`, `lupo_dialog_channels`, `lupo_dialog_threads`, `lupo_dialog_messages`.

### Rules and tasks

`lupo_rules`, `lupo_rule_targets`, `lupo_rule_logs`, `lupo_tasks`.

### Auth, sessions, registry

`lupo_auth_users`, `lupo_auth_providers`, `lupo_auth_audit_log`, `lupo_sessions`, `lupo_registry`, `lupo_registry_open`, `lupo_permissions`.

### Federation and navigation

`lupo_federation_nodes`, `lupo_federation_categories`, `lupo_federation_category_map`, `lupo_visits`, `lupo_paths`, `lupo_referers`.

### Collections, contexts, departments

`lupo_collections`, `lupo_collection_tabs`, `lupo_collection_tab_map`, `lupo_collection_tab_paths`, `lupo_contexts`, `lupo_contexts_map`, `lupo_departments`, `lupo_department_roles`, `lupo_department_metadata`.

### Other

`lupo_audit_log`, `lupo_schema_migrations`, `lupo_calibration_impacts`, `lupo_crafty_user_mapping`, `lupo_crafty_syntax_*`, `lupo_crm_leads`, `lupo_crm_lead_messages`, `lupo_doctrine_evolution_audit`, `lupo_tickets`, `lupo_ticket_messages`, `lupo_emotional_frameworks`, `lupo_emotional_geometry_calibrations`, `lupo_event_metadata`, `lupo_governance_overrides`, `lupo_help_topics`, `lupo_help_tree`, `lupo_interpretation_log`, `lupo_labs_declarations`, `lupo_labs_violations`, `lupo_memory_rollups`, `lupo_modules`, `lupo_multi_agent_critique_sync`, `lupo_notifications`, `lupo_search_rebuild_log`, `lupo_system_config`, `lupo_system_commands`, `lupo_uploads`, `lupo_world_registry`, `lupo_analytics_campaign_vars`, `lupo_anubis_*`, `lupo_api_*`, `lupo_cip_*`.

---

## 9. Traits, skills, and rules (conceptual)

- **Traits:** Intrinsic “who” of an actor. Not a separate table today; can be represented in `lupo_metadata` (entity_type=actor) or in actor directory docs. A dedicated `lupo_actor_traits` would require a schema change and doctrine update.
- **Skills:** Documented in `lupo-skills/` and actor `skills/*.md`; attached via `lupopedia.skills` header and `lupo_metadata` (seed: `seed_skills_4.0.68.sql`). Skill resolution: `SkillService.php`.
- **Rules:** Stored in `lupo_rules`; attached to channels/actors via `lupo_rule_targets`. Evaluated by `RuleEngine.php` / `RuleEvaluator.php`. Root rules in `lupo-rules/root/*.md`; synced to `.cursor/rules/*.mdc`.

---

## 10. IDE session and LUPOPEDIA HEADERS

- **Session files:** `lupo-database/sessions/{session_id}.md`. Naming: `L-LUPO-<ACTOR_NAME>_<ACTOR_FAUCET>_<UUID>.md` or simple names (e.g. `L-LUPO-ROOT-CURSOR.md`). Session = runtime context; headers = artifact metadata. See `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md` §2.1.
- **LUPOPEDIA HEADERS:** File identity, attribution, channel, version; stored as structured rows in `lupo_metadata` (root → blocks → properties). Optional `channel_name` and `thread_name` for human-readable context. Identity line: `# file: {title} — session: {session_name} — delegation: {chain} — web_path: {url}`.

---

## 11. References

| Resource | Purpose |
|----------|--------|
| **lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql** | Canonical DDL for all tables. |
| **lupo-database/lupopedia/toon/*.toon.json**, **lupo-database/lupopedia/toon/*.toon** | Column/type reference (TOONs); table count from TOONs after `python scripts/generate_toon_files.py`. |
| **lupo-docs/doctrine/ActorFaucetOntology.md** | Actor vs Faucet; IDE agents as faucets. |
| **lupo-docs/doctrine/COMMUNICATION_DOCTRINE.md** | Dialog tables only; no lupo_threads/lupo_messages. |
| **lupo-docs/doctrine/HumanActorIdDoctrine.md** | Human actor_id >= 1000. |
| **lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md** | Actor, faucet, session, trait, role, task (canonical separation). |
| **lupo-docs/doctrine/SESSION_RECONCILIATION_DOCTRINE.md** | Session truth: lupo_sessions vs session files; when corrections allowed; who logs. |
| **lupo-docs/doctrine/FEDERATION_SCOPING_DOCTRINE.md** | federation_node_id vs channel_id vs domain_id; precedence and content scoping. |
| **lupo-docs/doctrine/EDGE_VOCABULARY_DOCTRINE.md** | Canonical edge_type, relationship_type, and object type pairs. |
| **lupo-docs/doctrine/LUPOPEDIA_HEADERS/** | Header format, session block, channel_name, thread_name. |
| **lupo-docs/doctrine/CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md** | Subdirectory install, LUPOPEDIA_PUBLIC_PATH, config above web root. |
| **docs/status/DESIGN_NOTE_LUPO_ACTOR_TRAITS_4.0.69.md** | Decision: lupo_actor_traits table; actor-scoped traits only. |
| **AGENTS.md**, **README.md** | Paths, doctrines, architecture overview. |

---

*Status document: Wolfie (actor_id 1) via Cursor faucet, session L-LUPO-ROOT-CURSOR. Aligned with install schema, TOONs, and doctrine as of 4.0.69.*
