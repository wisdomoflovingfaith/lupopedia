---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "doctrine"
  system_version: "4.0.77"
  file_path_from_root: "lupo-docs/doctrine/BAYESIAN_DECISION_DOCTRINE.md"
  web_path: "[web_path](http://www.lupopedia.com/doctrine/BAYESIAN_DECISION_DOCTRINE)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "design"
  purpose: "Doctrine for optional Bayesian Decision Tracking foundation tables and metadata usage"
  tags: ["bayesian", "decisions", "future_feature", "schema", "doctrine"]

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Extend doctrine when core engine and integrations are implemented"
    - "Align decision_state metadata examples with concrete usage"
---
# file: BAYESIAN_DECISION_DOCTRINE — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/BAYESIAN_DECISION_DOCTRINE

# Bayesian Decision Doctrine (foundation, 4.0.77)

## 1. Scope and status

- **Scope:** Defines the schema and minimal usage rules for the **Bayesian Decision Tracking foundation** (`lupo_decisions`, `lupo_decision_edges`, `lupo_decision_influences`) and related metadata.
- **Status:** **Required schema in 4.0.77**, implemented as part of `install_new_lupopedia.sql`. The 4.0.77 scope is **schema + doctrine foundation only**; engine, integrations, APIs, and analytics remain deferred.
- **Doctrine alignment:** Fully aligned with Lupopedia Constitutional Root Rules (no foreign keys, BIGINT UTC, deterministic IDs, soft delete, application‑level logic).

## 2. Scope boundaries (required)

Every decision, edge, and influence is scoped by **channel**, **project**, and **federation node**. These scope fields are **required** in schema and in application code.

- **channel_id** — `BIGINT NOT NULL` in `lupo_decisions`, `lupo_decision_edges`, `lupo_decision_influences`. Every row must belong to a channel.
- **project_id** — `BIGINT NOT NULL` in all three tables. Every row must belong to a project.
- **federation_node_id** — already present; default 1 for local node.

**Implications:**

- Application code that records or queries decisions **must** supply and filter by `channel_id` and `project_id`. Queries that omit `channel_id` or `project_id` are **invalid** for production use.
- The service scaffold (`BayesianDecisionService`) requires `channel_id` and `project_id` in `recordDecision()`; future methods for edges and influences must require them as well.
- Indexes support scoped query patterns: `(channel_id, created_ymdhis)`, `(project_id, created_ymdhis)`, `(channel_id, project_id, created_ymdhis)`.

## 3. Tables and roles

### 3.1 `lupo_decisions`

- **Role:** Canonical record of decision nodes (per actor/session/channel/project).
- **Location:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- **Primary key:** `decision_id bigint` (registry‑allocated, no AUTO_INCREMENT).
- **Key fields (summary):**
  - `actor_id`, `channel_id`, `project_id` (required), `session_id`
  - `root_decision_id`, `parent_decision_id`, `depth`
  - `decision_type`, `decision_status`, `decision_key`
  - `probability`, `probability_lower`, `probability_upper`, `probability_model`
  - `state_snapshot_id` (logical ref into `lupo_metadata`)
  - `federation_node_id`, `origin_decision_id`
  - `created_ymdhis`, `created_by_actor_id`, `updated_ymdhis`
  - `abandoned_ymdhis`, `pruned_ymdhis`, `is_deleted`, `deleted_ymdhis`

### 3.2 `lupo_decision_edges`

- **Role:** Structural and navigational edges between decision nodes (e.g. leads_to, contradicts, influences).
- **Primary key:** `(source_decision_id, target_decision_id, edge_type)`.
- **Key fields:** `channel_id`, `project_id` (required), `probability`, `session_id`, `federation_node_id`, timestamps, soft‑delete.

### 3.3 `lupo_decision_influences`

- **Role:** Captures influence relationships between decisions (e.g. causal, informational, constraint).
- **Primary key:** `(decision_id, influencing_decision_id, influence_type)`.
- **Key fields:** `channel_id`, `project_id` (required), `weight`, `session_id`, `federation_node_id`, timestamps, soft‑delete.

All three tables:

- Use **BIGINT UTC `YYYYMMDDHHIISS`** timestamps.
- Have **no foreign keys, no triggers, no DB‑side logic**.
- Use **logical references only** (relationships enforced in PHP).

## 4. Metadata: `decision_state` snapshots

The canonical metadata store remains **`lupo_metadata`**. Decision tracking **reuses** this, it does **not** introduce a new metadata table.

- **Entity type:** `entity_type = 'decision_state'` (recommended).
- **Attachment:** `entity_id = decision_id` (or a stable composite key encoded in `property_key` when needed).
- **Structure:** Use the existing row‑based LUPOPEDIA HEADERS model:
  - Root row (`lupopedia_header_root`) → block row (e.g. `lupopedia.metadata`) → property rows.
  - Optional JSON payloads may be stored in `property_value` when appropriate.

**Recommended properties for decision state:**

- `agent_context` — short description of active goals / focus.
- `prompt_slice` — minimal input segment that drove the decision (not full prompt).
- `key_variables` — compact encoding of key variables at decision time.
- `active_rules` — list of rule IDs or doctrine labels applied.
- `content_references` — IDs / paths of relevant artifacts (no blobs).
- Optional: `state_hash` (stored as a property) for deduplication.

**Deduplication pattern (doctrine level):**

- Compute a hash (e.g. SHA‑256) of the normalized state payload in PHP.
- Store hash as a `property_key`/`property_value` pair (e.g. `decision_state_hash`).
- Reuse existing state rows when the hash matches and semantics are identical.

## 5. ID and allocation rules

- `decision_id` and any future IDs related to this subsystem **must**:
  - Be **BIGINT**, deterministically allocated by the application.
  - Never use AUTO_INCREMENT or `lastInsertId()`.
  - Be allocated via the existing registry/allocator patterns (e.g. `lupo_registry_open`) when those paths are extended for decisions.
- `actor_id`, `channel_id`, `project_id`, `session_id` track existing doctrine:
  - They are **logical references only**; integrity is implemented in PHP, not in SQL.

## 6. Session and integration notes (foundation only)

- For 4.0.77, the canonical foundation is:
  - Tables + TOONs + doctrine (this file).
  - Engine classes, session integration, dialog/task hooks, CLI/API endpoints are **future work**.
- When engine code is added:
  - Writes MUST go through the PDO_DB wrapper (no raw PDO).
  - Decision creation SHOULD precede dialog/task writes when the decision describes that action.
  - Any `current_decision_id`‑style field in `lupo_sessions` MUST be added only if it is fully aligned with existing session doctrine and TOONs.

## 7. Placement and canonical source

- Schema lives in **install SQL** (required tables):
  - `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- TOONs: `lupo-database/lupopedia/toon/lupo_decisions.toon.json`, `lupo_decision_edges.toon.json`, `lupo_decision_influences.toon.json`.
- These tables are **required** in 4.0.77; they are part of the canonical install. Engine, integrations, and APIs remain deferred; the schema and service scaffold are foundation only.

