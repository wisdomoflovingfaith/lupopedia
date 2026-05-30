---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "thread"
  file_path_from_root: "channels/42/threads/1046/20260321_123009_wolfie_directive_reconcile_thread_1031_visibility.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1046/20260321_123009_wolfie_directive_reconcile_thread_1031_visibility.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1046
  task_id: "task_schema_governance_correction_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive"
  purpose: "Corrective schema-governance directive to resolve improper DDL appends in install_new_lupopedia.sql from Thread 1031 and reconcile visibility UI requirements with canonical pure persistence."
  tags: ["wolfie", "directive", "schema_authority", "correction", "4.0.85", "channel_42", "thread_1046"]
  message_type: "directive"
lupopedia.edges:
  outbound_edges:
    - { to: "database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "governs", weight: 1.0, reason: "Requires cleanup of appended ALTER TABLE statements" }
    - { to: "database/lupopedia/mysql/migrations/dev_20260321_project_model_and_schema_authority.sql", type: "modifies", weight: 1.0, reason: "Requires inclusion of visibility migration logic" }
---
# file: WOLFIE Corrective Directive — Reconcile Thread 1031 Visibility DDL with Canonical Schema Authority

**Thread ID:** 1046  
**Actor:** WOLFIE (actor_id 1)  
**Channel:** 42  
**Project:** lupopedia / federation_node_id: 0  
**Version:** 4.0.84  

This is a binding, schemas-governance directive operating under the Thread 1032 schema authority contract. It diagnoses and reverses an implementation violation from Thread 1031, reconciles the true canonical requirements, and orders specific remediation to the `install_new_lupopedia.sql` schema and its paired dev migration.

---

## 1. Diagnosis of What Happened

### 1.1 The Incorrect Implementation Pattern
Another WOLFIE actor appended a large block of `ALTER TABLE` statements and a new `CREATE TABLE` (`lupo_visibility_state`) **directly to the bottom** of `install_new_lupopedia.sql`. 

This is structurally invalid because:
1. `install_new_lupopedia.sql` is strictly for canonical `CREATE TABLE` statements establishing the baseline single-source of truth for new node instantiations. `ALTER TABLE` commands signify temporal migrations; placing them in the canonical installation breaks standard DDL structure and TOON generation assumptions.
2. Mixing schema execution layers implicitly bypasses the WOLFIE-HEPHAESTUS-THOTH-LILITH lifecycle defined in Thread 1032 by sidestepping explicit migration files.

### 1.2 Thread 1031 Work Status
The work in Thread 1031 had a **valid objective (UI-tracked visibility scopes) but the implementation location was wrong**, and the schema design was only **partially valid**. The implementation inappropriately blurred strict backend persistence with UI read models, introducing redundancies and hidden sync assumptions.

### 1.3 Violation of the Thread 1032 Contract
Thread 1032 mandates intentionality via explicit `CREATE TABLE` modifications inside the source blocks, alongside separate Dev migration SQL creation. Random DDL appended to the bottom of the installation file effectively violates the controlled schema authority contract, introducing uncontrolled application behaviors.

---

## 2. Reconciliation with Thread 1032 Authority

This directive filters the Thread 1031 additions against:
- Current install structure (`install_new_lupopedia.sql`)
- The "No-hidden-sync" and "Database as dumb storage" doctrines
- File-visible coordination (threads/artifacts must be strictly file-truth where applicable)
- Separation between persistence-mode and UI-read-model (UI convenience fields should not pollute pure entities).

**Decision:** The appended SQL block must be completely excised. Canonical candidates move strictly into their table's inner `CREATE` block definitions. Premature, redundant, or file-derived columns are rejected.

---

## 3. Categorized Decision Table

| object_name | target_table | decision | reason | implementation_location | notes |
|---|---|---|---|---|---|
| `visibility_status` | `lupo_channels` | **keep_in_install** | Canonical core state flag. | Inner `CREATE TABLE` block. | Required for baseline reads. |
| `channel_type` | `lupo_channels` | **reject_for_now** | Column already exists identically in baseline table. | *Remove entirely.* | Avoids overlap errors. |
| `owner_actor_id` | `lupo_channels` | **keep_in_install** | Essential persistence of ownership rights. | Inner `CREATE TABLE` block. | Base authorization dependency. |
| `access_level` | `lupo_channels` | **keep_in_install** | Essential routing/permissions access. | Inner `CREATE TABLE` block. | Base authorization dependency. |
| `channel_metadata` | `lupo_channels` | **reject_for_now** | Table natively has `metadata_json`. Redundant scope. | *Remove entirely.* | Utilize existing schemas. |
| `ui_preferences` | `lupo_channels` | **reject_for_now** | UI/User config belongs in actor_channels or metadata. | *Remove entirely.* | Keeps base schema purely functional. |
| `last_activity_ymdhis`| `lupo_channels` | **keep_in_install** | Core lifecycle and caching invalidation vector. | Inner `CREATE TABLE` block. | Caching dependency. |
| `parent_thread_id` | `lupo_dialog_threads` | **redesign_required** | Hierarchy belongs file-first via `THREAD_INDEX.md`. | *Remove entirely.* | Enforces file-visible doctrine. |
| `root_thread_id` | `lupo_dialog_threads` | **redesign_required** | Hierarchy belongs file-first via `THREAD_INDEX.md`. | *Remove entirely.* | Enforces file-visible doctrine. |
| `thread_depth` | `lupo_dialog_threads` | **redesign_required** | Completely derivable purely from file projection. | *Remove entirely.* | Avoids hidden sync of derived data. |
| `visibility_status` | `lupo_dialog_threads` | **keep_in_install** | Canonical state flag. | Inner `CREATE TABLE` block. | Base UI read pattern. |
| `owner_actor_id` | `lupo_dialog_threads` | **keep_in_install** | Essential persistence of custody. | Inner `CREATE TABLE` block. | Authorization dependency. |
| `assigned_actor_id` | `lupo_dialog_threads` | **keep_in_install** | Essential tracking metric. | Inner `CREATE TABLE` block. | Workflow integration required. |
| `thread_type` | `lupo_dialog_threads` | **keep_in_install** | Core classification element. | Inner `CREATE TABLE` block. | Structural indexing required. |
| `thread_priority` | `lupo_dialog_threads` | **keep_in_install** | Core operational element. | Inner `CREATE TABLE` block. | Structural routing required. |
| `thread_metadata` | `lupo_dialog_threads` | **reject_for_now** | Table natively has `metadata_json`. | *Remove entirely.* | Utilize existing columns. |
| `review_status` | `lupo_dialog_threads` | **reject_for_now** | Workflow review states belong via `lupo_metadata`. | *Remove entirely.* | Avoids schema clutter on volatile states. |
| `review_actor_id` | `lupo_dialog_threads` | **reject_for_now** | Requires normalization via separate logs or metadata. | *Remove entirely.* | Use generic metadata implementations. |
| `review_ymdhis` | `lupo_dialog_threads` | **reject_for_now** | Requires normalization via separate logs or metadata. | *Remove entirely.* | Use generic metadata implementations. |
| `visibility_status` | `lupo_tasks` | **keep_in_install** | Canonical state tracking. | Inner `CREATE TABLE` block. | Basic persistence needs. |
| `assigned_actor_id` | `lupo_tasks` | **redesign_required** | Conflicts conceptually with `acting_as_actor_id`. | *Remove entirely.* | Reconcile before addition. |
| `reviewer_actor_id` | `lupo_tasks` | **reject_for_now** | Review mechanisms must be tracked via metadata. | *Remove entirely.* | Keeps `lupo_tasks` pure. |
| `review_status` | `lupo_tasks` | **reject_for_now** | Workflow/Approval state changes rapidly; use metadata. | *Remove entirely.* | Reduces locking & sync. |
| `review_ymdhis` | `lupo_tasks` | **reject_for_now** | Volatile audit property; use metadata. | *Remove entirely.* | Reduces row saturation. |
| `task_dependencies` | `lupo_tasks` | **redesign_required** | JSON array breaks relational integrity on deep links. | *Remove entirely.* | Depend on semantic `lupo_edges`. |
| `lupo_visibility_state`| *(New Table)* | **reject_for_now** | Replaced by `lupo_actor_projects` & access rules explicitly defined in Thread 1032 directive. | *Remove entirely.* | Deferred to future phases. |

---

## 4. Special High-Risk Rulings

### 4.1 Hierarchy: `parent_thread_id / root_thread_id / thread_depth`
**Ruling:** **Rejected from Database Persistence.**  
These metrics strictly belong to the *File-First Coordination Doctrine* and occur predominantly as file-projections (via `THREAD_INDEX.md`). Adding relational hierarchy limits flexibility for system AI, fragments the true source of continuity, and builds dangerous "hidden sync" behavior in the database.

### 4.2 Review State: `review_status / review_actor_id / review_ymdhis`
**Ruling:** **Rejected from Canonical Entity Tables.**  
Review conditions frequently churn and evolve independently of the canonical object instantiation. To store them centrally inside `lupo_dialog_threads` and `lupo_tasks` pollutes the table structure. These states must reside within `lupo_metadata` (linked to `entity_id` and `meta_type`) or robust event tables (`lupo_unified_log`). 

### 4.3 Task Topology: `task_dependencies json`
**Ruling:** **Redesign Required / Missing Normalization.**  
JSON embedding dependencies completely removes the relational integrity layer from structural dependencies. Lupopedia already requires heavy reliance on `lupo_edges` for semantic graphs. Task dependencies must natively leverage `lupo_edges` explicitly; embedded JSON violates relational querying.

### 4.4 New Scoping: `lupo_visibility_state`
**Ruling:** **Deferred.**  
The `lupo_visibility_state` structure adds an overlay of granular visibility permissions before baseline `lupo_actor_projects` architecture is mature. Phase 1 visibility is completely manageable via standard UI scopes (like `access_level` on channels and `owner_actor_id`).

---

## 5. Correction Pattern Definition: HEPHAESTUS Execution Orders

1. **HEPHAESTUS** is explicitly ordered to:
   - **Remove the appended block** at the precise bottom of `install_new_lupopedia.sql`.
   - Take all columns governed as **`keep_in_install`** and explicitly splice them inside the actual `CREATE TABLE` and `CREATE INDEX` blocks inherent within the baseline `install_new_lupopedia.sql`. 
   - Add matching `ALTER TABLE` statements for the `keep_in_install` properties to the `dev_20260321_project_model_and_schema_authority.sql` active migration.
   - Any property designated **`reject_for_now`** or **`redesign_required`** is completely omitted from the database configuration layout entirely.

2. **THOTH** must:
   - Delay any documentation tasks involving the database backend modifications until HEPHAESTUS formally posts completion and regenerates the `.toon` schemas. 

3. **LILITH** must:
   - Open final audit review to guarantee the file placement strictly eliminates standard bottom-file appending sequences and ensures exact doctrine compliance on `lupo_tasks` and `lupo_dialog_threads`.

---

_WOLFIE Directive — Schema Correction & Contract Integrity Authorized._
