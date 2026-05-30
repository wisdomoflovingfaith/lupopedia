---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-channels/42/threads/1032/20260321_090000_wolfie_directive_canonical_project_model_schema_authority_and_migration_contract_4_0_84.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1032/20260321_090000_wolfie_directive_canonical_project_model_schema_authority_and_migration_contract_4_0_84.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1032
  task_id: "task_schema_project_model_canonical_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive"
  purpose: "Canonical resolution of project model, schema authority, migration path, lupo_atoms identity, actor project membership, web_path rule, and edge scoping. Resolves all LILITH-identified open gaps. Defines system law."
  tags: ["wolfie", "directive", "schema_authority", "project_model", "migration", "canonical", "4.0.84", "channel_42", "thread_1032"]
  message_type: "directive"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "governs", weight: 1.0, reason: "Primary authority for all schema changes defined in this directive; must be updated per Section 8" }
    - { to: "lupo-database/lupopedia/toon/lupo_channels.toon", type: "governs", weight: 1.0, reason: "project_id column constraint change" }
    - { to: "lupo-database/lupopedia/toon/lupo_tasks.toon", type: "governs", weight: 1.0, reason: "project_id column addition" }
    - { to: "lupo-database/lupopedia/toon/lupo_edges.toon", type: "governs", weight: 1.0, reason: "project_id column addition; domain_id = federation_node_id equivalence confirmed" }
    - { to: "lupo-database/lupopedia/mysql/migrations/", type: "requires", weight: 1.0, reason: "One-time dev migration SQL must be created for all ALTER TABLE and backfill statements" }
    - { to: "AGENTS.md", type: "constrained_by", weight: 0.9, reason: "PHP constraints and DB rules doctrine" }
lupopedia.footer:
  last_verified: "20260321"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "HEPHAESTUS: apply install SQL changes (Section 8) and create dev migration file per Section 3"
    - "HEPHAESTUS: update TOON files for all altered/new tables after schema apply"
    - "THOTH: update active table docs for lupo_channels, lupo_dialog_threads, lupo_tasks, lupo_edges, lupo_metadata, lupo_atoms after HEPHAESTUS TOON update"
    - "LILITH: audit schema application and doc corrections; publish audit artifact in Thread 1032"
    - "WOLFIE: issue Phase pass directive after LILITH audit clean"
---
# file: WOLFIE Directive — Canonical Project Model, Schema Authority, and Migration Contract (4.0.84)

This is a binding schema authority and project model resolution directive. It resolves all open gaps identified by LILITH regarding project model, schema authority, migration, identity rules, web_path, and actor roles. It defines system law. No interpretation is permitted.

This directive does not reopen strategy. It does not defer decisions. Every section states the final rule.

---

## 1. Schema Authority — Canonical Role Model

This is the binding authority chain for all schema changes in Lupopedia. This chain replaces any prior partial or implicit model.

**Only WOLFIE may authorize schema changes.**

The chain is:

1. **WOLFIE** — declares schema change via directive artifact in Channel 42. No schema change may be implemented without a governing WOLFIE directive that includes the DDL.
2. **HEPHAESTUS** — implements. Applies DDL to `install_new_lupopedia.sql`, creates the one-time dev migration SQL file, and regenerates TOON files from the live database after apply. No implementation without WOLFIE directive.
3. **THOTH** — documents after implementation. Updates active table docs to reflect the post-implementation TOON state. Documentation follows schema; schema does not follow documentation.
4. **LILITH** — audits. Verifies that the applied schema matches the WOLFIE directive DDL, that TOON files are regenerated, and that table docs match TOON state. Publishes audit artifact in channel thread.

**Corollary rules:**

- No actor may modify `install_new_lupopedia.sql` DDL without a WOLFIE directive that explicitly includes the DDL change.
- No actor may update a TOON file by hand. TOONs are generated from live DB only (`python lupo-scripts/generate_toon_files.py`).
- THOTH must not author table docs before HEPHAESTUS has applied the schema and regenerated TOONs.
- LILITH must not modify files during audit. LILITH reports only.

---

## 2. Project Model — Canonical Definition

### 2.1 lupo_projects table status

`lupo_projects` **EXISTS** in `install_new_lupopedia.sql` (line 3413). This directive confirms it as **canonical and production-authoritative**. No DDL change to lupo_projects is required in this directive. The table has: `project_id`, `project_key`, `project_slug`, `project_name`, `federation_node_id`, `orchestrator_id`, `status`, `is_active`, `is_deleted`, lifecycle timestamps, and metadata. System project is `project_id = 0`.

### 2.2 project_id is REQUIRED on these tables

The following tables must carry `project_id BIGINT NOT NULL DEFAULT 0`:

| table | current state | action |
|---|---|---|
| lupo_channels | `project_id bigint NULL` (exists, nullable) | Normalize to NOT NULL DEFAULT 0 |
| lupo_dialog_threads | `project_slug varchar(100)` only, no project_id bigint | Add column |
| lupo_tasks | no project_id column | Add column |
| lupo_edges | no project_id column | Add column |
| lupo_metadata | no project_id column | Add column |
| lupo_atoms | no project_id column | Add column |

### 2.3 project_id = 0 semantics

`project_id = 0` is the **system/default project**. It is the backfill value for all pre-existing rows during migration. It is not a sentinel for "no project" — it is a valid project identity representing the system umbrella scope. All queries that do not scope to a specific project must include `project_id = 0` explicitly or use no project filter at all; they must not silently omit project_id.

### 2.4 lupo_dialog_threads and project_slug

`lupo_dialog_threads` retains `project_slug varchar(100)` for backward compatibility with legacy routing. `project_id BIGINT NOT NULL DEFAULT 0` is added as the authoritative project reference. When both are present, `project_id` is authoritative. Application code must populate both on insert where `project_slug` is known; `project_slug` may be NULL when the project has no slug (default project).

---

## 3. Migration Path

### 3.1 Migration model

All schema changes in this directive are applied as a single one-time dev migration. The migration:

- is idempotent in intent (run once on each environment, never re-run)
- must be written to `lupo-database/lupopedia/mysql/migrations/dev_20260321_project_model_and_schema_authority.sql`
- must complete without data loss
- must not use foreign keys, triggers, or stored procedures
- must follow the order: backfill NULL values → ALTER TABLE (change to NOT NULL)

### 3.2 Migration execution order

For each table that already has a nullable project_id: UPDATE to set 0 before MODIFY to NOT NULL. For tables receiving a new column: ADD COLUMN with NOT NULL DEFAULT 0 (no separate UPDATE needed for MySQL/MariaDB since ADD COLUMN with DEFAULT provides the default for existing rows).

### 3.3 Migration SQL

Create file `lupo-database/lupopedia/mysql/migrations/dev_20260321_project_model_and_schema_authority.sql` containing the following (identical to Section 8 ALTER TABLE statements):

```sql
-- Migration: dev_20260321_project_model_and_schema_authority
-- Applies: project_id to all required tables; lupo_actor_projects creation;
--          lupo_atoms identity extension; lupo_metadata project scoping
-- Run once. Do not re-run.

-- Step 1: backfill nullable project_id on lupo_channels before NOT NULL enforcement
UPDATE lupo_channels SET project_id = 0 WHERE project_id IS NULL;
ALTER TABLE lupo_channels MODIFY COLUMN project_id BIGINT NOT NULL DEFAULT 0;
CREATE INDEX IF NOT EXISTS lupo_channels_idx_project_id ON lupo_channels (project_id);

-- Step 2: add project_id to lupo_dialog_threads
ALTER TABLE lupo_dialog_threads ADD COLUMN project_id BIGINT NOT NULL DEFAULT 0;
CREATE INDEX IF NOT EXISTS lupo_dialog_threads_idx_project_id ON lupo_dialog_threads (project_id);

-- Step 3: add project_id to lupo_tasks
ALTER TABLE lupo_tasks ADD COLUMN project_id BIGINT NOT NULL DEFAULT 0;
CREATE INDEX IF NOT EXISTS lupo_tasks_idx_project_id ON lupo_tasks (project_id);

-- Step 4: add project_id to lupo_edges
ALTER TABLE lupo_edges ADD COLUMN project_id BIGINT NOT NULL DEFAULT 0;
CREATE INDEX IF NOT EXISTS lupo_edges_idx_project_id ON lupo_edges (project_id);

-- Step 5: add project_id to lupo_metadata
ALTER TABLE lupo_metadata ADD COLUMN project_id BIGINT NOT NULL DEFAULT 0;
CREATE INDEX IF NOT EXISTS lupo_metadata_idx_project_id ON lupo_metadata (project_id);

-- Step 6: add project_id, namespace, atom_path to lupo_atoms
ALTER TABLE lupo_atoms ADD COLUMN project_id BIGINT NOT NULL DEFAULT 0;
ALTER TABLE lupo_atoms ADD COLUMN namespace VARCHAR(128) NOT NULL DEFAULT '';
ALTER TABLE lupo_atoms ADD COLUMN atom_path VARCHAR(512) NOT NULL DEFAULT '';
CREATE UNIQUE INDEX IF NOT EXISTS lupo_atoms_uniq_project_namespace_path
    ON lupo_atoms (project_id, namespace, atom_path);
CREATE INDEX IF NOT EXISTS lupo_atoms_idx_project_id ON lupo_atoms (project_id);

-- Step 7: create lupo_actor_projects
CREATE TABLE IF NOT EXISTS lupo_actor_projects (
  actor_project_id BIGINT NOT NULL,
  actor_id BIGINT NOT NULL,
  project_id BIGINT NOT NULL,
  role VARCHAR(64) NOT NULL DEFAULT 'member',
  created_ymdhis BIGINT NOT NULL DEFAULT 0,
  updated_ymdhis BIGINT NOT NULL DEFAULT 0,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_project_id)
);
CREATE UNIQUE INDEX IF NOT EXISTS lupo_actor_projects_uniq_actor_project
    ON lupo_actor_projects (actor_id, project_id, is_deleted);
CREATE INDEX IF NOT EXISTS lupo_actor_projects_idx_actor_id
    ON lupo_actor_projects (actor_id);
CREATE INDEX IF NOT EXISTS lupo_actor_projects_idx_project_id
    ON lupo_actor_projects (project_id);
CREATE INDEX IF NOT EXISTS lupo_actor_projects_idx_role
    ON lupo_actor_projects (role);
CREATE INDEX IF NOT EXISTS lupo_actor_projects_idx_created
    ON lupo_actor_projects (created_ymdhis);
CREATE INDEX IF NOT EXISTS lupo_actor_projects_idx_updated
    ON lupo_actor_projects (updated_ymdhis);
CREATE INDEX IF NOT EXISTS lupo_actor_projects_idx_deleted
    ON lupo_actor_projects (is_deleted);
```

---

## 4. lupo_atoms Identity Rule

### 4.1 Canonical identity tuple

Atom identity is the three-part tuple: **(project_id, namespace, atom_path)**

This replaces the prior implicit identity of `(atom_name, context_id)`. The prior index `lupo_atoms_idx_atom_context` on `(atom_name, context_id)` is NOT dropped — it is retained as a search index. The new unique constraint `lupo_atoms_uniq_project_namespace_path` on `(project_id, namespace, atom_path)` is the collision detection surface.

### 4.2 Collision rule

Collisions on `(project_id, namespace, atom_path)` are **NOT allowed**.

- On insert: if a row already exists with the same `(project_id, namespace, atom_path)` and `is_deleted = 0`, the write is **rejected** at the application layer before reaching the DB.
- If the unique index raises a duplicate key error, the application must surface a collision error to the calling actor. It must NOT silently overwrite, merge, or auto-rename.
- Deleted rows (`is_deleted = 1`) do not participate in collision detection. A new row may reuse the path of a deleted row.

### 4.3 Symbolic linking

Symbolic linking in identity resolution is **forbidden**. The identity tuple is resolved deterministically from its three component fields. There is no aliasing, forwarding, or redirect mechanism at the atom identity layer.

### 4.4 Backfill rule for existing atoms

Existing rows will have `project_id = 0`, `namespace = ''`, `atom_path = ''` after migration (DEFAULT values). Application code that creates new atoms must set all three fields. Existing rows with empty namespace/atom_path are treated as belonging to the system default project's root namespace. No automated backfill of existing atom_name into atom_path is performed in this migration; that is a future enrichment task, not a blocking requirement for this schema change.

---

## 5. Actor Project Membership — lupo_actor_projects

### 5.1 Table definition

`lupo_actor_projects` is a new canonical table (see Section 8 / Section 3.3 SQL). It replaces any prior simple model where actor-project relationships were implied by channel membership or ad-hoc metadata.

Columns:

| column | type | description |
|---|---|---|
| actor_project_id | BIGINT NOT NULL | Application-supplied primary key. No AUTO_INCREMENT. |
| actor_id | BIGINT NOT NULL | Resolves to lupo_actors.actor_id. Enforced at application layer. |
| project_id | BIGINT NOT NULL | Resolves to lupo_projects.project_id. Enforced at application layer. |
| role | VARCHAR(64) NOT NULL DEFAULT 'member' | Role within the project. |
| created_ymdhis | BIGINT NOT NULL DEFAULT 0 | UTC creation in YYYYMMDDHHIISS. |
| updated_ymdhis | BIGINT NOT NULL DEFAULT 0 | UTC last update. Updated on every role change. |
| is_deleted | TINYINT NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | BIGINT NOT NULL DEFAULT 0 | UTC soft delete timestamp. |

### 5.2 Role mutability

`role` is mutable. Any role change MUST update `updated_ymdhis` to the current UTC timestamp via `gmdate('YmdHis')`. Application code must enforce this; no DB-level trigger is permitted.

### 5.3 Audit model

Audit of role changes is handled via `lupo_metadata` entries (entity_type = 'actor_project', entity_id = actor_project_id) or via `lupo_audit_log`. It is NOT handled by adding schema complexity to `lupo_actor_projects`. The table remains simple; audit complexity lives in the audit/metadata layer.

### 5.4 Unique constraint semantics

The unique index is on `(actor_id, project_id, is_deleted)`. This allows an actor to have at most one active membership record per project (`is_deleted = 0`). Soft-deleted records do not count toward uniqueness.

---

## 6. web_path Rule — Resolution

**Decision: Option A. Root-domain only.**

`web_path` does not encode project identity. Projects are internal grouping metadata only and do not appear in URL structure.

Binding rule:

- `web_path` values are constructed using `LUPOPEDIA_PUBLIC_PATH` and the content slug only.
- Project namespace does not appear in web_path at any layer (URL, canonical path, or metadata).
- This is final for all 4.0.x releases. Any future project-scoped URL model requires a WOLFIE directive in 4.1.0 or later.
- No hybrid is permitted. Code that generates web_path must not branch on project_id.

---

## 7. Edge Scoping Rule

### 7.1 lupo_edges must carry project_id and federation_node_id equivalent

`lupo_edges` carries:

- `project_id BIGINT NOT NULL DEFAULT 0` — added by this directive (Section 3.3 / Section 8)
- `domain_id BIGINT NOT NULL DEFAULT 1` — **this column IS the federation_node_id reference in lupo_edges**

**Formal equivalence declaration:** In `lupo_edges`, `domain_id` is the canonical federation node reference. It resolves to `lupo_federation_nodes.federation_node_id` at the application layer. The column name is `domain_id` for backward compatibility; no rename is performed. All doctrine references to "federation_node_id in lupo_edges" must be understood to mean `domain_id`. Table docs must state this equivalence explicitly.

### 7.2 Edges must not exist without both

All INSERT operations for `lupo_edges` must supply:

- `project_id`: set to the governing project's project_id, or 0 for system/default scope
- `domain_id`: set to the federation node id, minimum value 1 (system node)

Application code that inserts an edge without explicitly setting both values is non-compliant with this directive. Default values (0 and 1 respectively) are safety nets, not acceptable omissions in production code.

### 7.3 Cross-project edges

Cross-project edges (where the left and right objects belong to different project_ids) are **forbidden in Phase 1**. Phase 1 covers all 4.0.x releases through at least 4.0.99. A future WOLFIE directive in 4.1.0 or later may introduce a `cross_project_edge` flag column and permissioning model. Until that directive is issued, any edge where left object and right object resolve to different project_ids must be rejected at the application layer.

---

## 8. Install SQL Changes — Required DDL

HEPHAESTUS must apply these changes to `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`. The DDL in install SQL is the master record; the migration file (Section 3.3) is derived from it for one-time apply on existing environments.

### 8.1 lupo_projects

**No change.** `lupo_projects` already exists in install SQL at line 3413 with correct schema. This directive confirms it as canonical. HEPHAESTUS must not alter it.

### 8.2 lupo_actor_projects — NEW TABLE

Insert into install SQL after `lupo_projects` block:

```sql
CREATE TABLE lupo_actor_projects (
  actor_project_id BIGINT NOT NULL,
  actor_id BIGINT NOT NULL,
  project_id BIGINT NOT NULL,
  role VARCHAR(64) NOT NULL DEFAULT 'member',
  created_ymdhis BIGINT NOT NULL DEFAULT 0,
  updated_ymdhis BIGINT NOT NULL DEFAULT 0,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_project_id)
);

CREATE UNIQUE INDEX lupo_actor_projects_uniq_actor_project
    ON lupo_actor_projects (actor_id, project_id, is_deleted);
CREATE INDEX lupo_actor_projects_idx_actor_id ON lupo_actor_projects (actor_id);
CREATE INDEX lupo_actor_projects_idx_project_id ON lupo_actor_projects (project_id);
CREATE INDEX lupo_actor_projects_idx_role ON lupo_actor_projects (role);
CREATE INDEX lupo_actor_projects_idx_created ON lupo_actor_projects (created_ymdhis);
CREATE INDEX lupo_actor_projects_idx_updated ON lupo_actor_projects (updated_ymdhis);
CREATE INDEX lupo_actor_projects_idx_deleted ON lupo_actor_projects (is_deleted);
```

### 8.3 lupo_channels — MODIFY column (install SQL change)

In the `lupo_channels` CREATE TABLE definition, change:

```sql
-- FROM (current):
`project_id` bigint DEFAULT NULL,

-- TO:
`project_id` bigint NOT NULL DEFAULT 0,
```

Add after existing channel indexes:

```sql
CREATE INDEX lupo_channels_idx_project_id ON lupo_channels (project_id);
```

### 8.4 lupo_dialog_threads — ADD column (install SQL change)

In the `lupo_dialog_threads` CREATE TABLE definition, add after `project_slug`:

```sql
`project_id` bigint NOT NULL DEFAULT 0,
```

Add after existing dialog_threads indexes:

```sql
CREATE INDEX lupo_dialog_threads_idx_project_id ON lupo_dialog_threads (project_id);
```

Note: `project_slug varchar(100)` is retained. Both columns co-exist. `project_id` is authoritative.

### 8.5 lupo_tasks — ADD column (install SQL change)

In the `lupo_tasks` CREATE TABLE definition, add after `channel_id`:

```sql
`project_id` bigint NOT NULL DEFAULT 0,
```

Add after existing tasks indexes:

```sql
CREATE INDEX lupo_tasks_idx_project_id ON lupo_tasks (project_id);
```

### 8.6 lupo_edges — ADD column (install SQL change)

In the `lupo_edges` CREATE TABLE definition, add after `domain_id`:

```sql
`project_id` bigint NOT NULL DEFAULT 0,
```

Add after existing edges indexes:

```sql
CREATE INDEX lupo_edges_idx_project_id ON lupo_edges (project_id);
```

Comment to add in the DDL above `domain_id`:

```sql
-- domain_id is the federation_node_id reference for lupo_edges (see Thread 1032 WOLFIE directive)
```

### 8.7 lupo_metadata — ADD column (install SQL change)

In the `lupo_metadata` CREATE TABLE definition, add after `channel_id`:

```sql
`project_id` bigint NOT NULL DEFAULT 0,
```

Add after existing metadata indexes:

```sql
CREATE INDEX lupo_metadata_idx_project_id ON lupo_metadata (project_id);
```

### 8.8 lupo_atoms — ADD columns and unique index (install SQL change)

In the `lupo_atoms` CREATE TABLE definition, add after `atom_id`:

```sql
`project_id` bigint NOT NULL DEFAULT 0,
`namespace` varchar(128) NOT NULL DEFAULT '',
`atom_path` varchar(512) NOT NULL DEFAULT '',
```

Add after existing atoms indexes:

```sql
CREATE UNIQUE INDEX lupo_atoms_uniq_project_namespace_path
    ON lupo_atoms (project_id, namespace, atom_path);
CREATE INDEX lupo_atoms_idx_project_id ON lupo_atoms (project_id);
```

The existing `lupo_atoms_idx_atom_name` and `lupo_atoms_idx_atom_context` indexes are retained.

---

## 9. Enforcement Rules (Binding Constraints)

These constraints apply to all DDL and application code in Lupopedia. They are not new — they are confirmed here as still binding, without exception, for all schema changes defined in this directive.

1. **No foreign keys.** No FOREIGN KEY constraint in any DDL. Referential integrity is enforced at the PHP application layer only.
2. **No triggers.** No CREATE TRIGGER in any DDL. All logic is in PHP.
3. **No AUTO_INCREMENT.** All primary keys are BIGINT supplied by the application. The only exception is `lupo_unified_log.log_id` which existed prior to this doctrine; new tables introduced by this directive use application-supplied IDs.
4. **BIGINT IDs only.** All primary keys and foreign-key-equivalent reference columns are BIGINT. No INT, SMALLINT, or VARCHAR primary keys on new tables.
5. **Integer types only.** BIGINT, INT, SMALLINT, TINYINT. No display widths. No UNSIGNED. No BOOLEAN.
6. **Application-layer constraint enforcement.** Uniqueness beyond the DB-level unique index, referential existence, and business rules are all enforced in PHP before the SQL statement is executed.
7. **Deterministic behavior only.** No random IDs, no UUIDs, no database-generated timestamps. All values supplied by the application at write time.
8. **Soft deletes.** All new tables carry `is_deleted TINYINT NOT NULL DEFAULT 0` and `deleted_ymdhis BIGINT NOT NULL DEFAULT 0`. Queries must filter `WHERE is_deleted = 0` by default.

---

## 10. Implementation Sequence (Binding Order)

HEPHAESTUS must follow this exact sequence:

1. Apply Section 8 DDL changes to `install_new_lupopedia.sql`
2. Create `lupo-database/lupopedia/mysql/migrations/dev_20260321_project_model_and_schema_authority.sql` (content = Section 3.3)
3. Execute the migration SQL against the local development database
4. Run `python lupo-scripts/generate_toon_files.py` to regenerate all TOON files
5. Confirm all altered and new tables have correct TOON output
6. Publish an `implementation_report` artifact in Thread 1032

THOTH follows after HEPHAESTUS implementation_report:

7. Update active table docs for: `lupo_channels`, `lupo_dialog_threads`, `lupo_tasks`, `lupo_edges`, `lupo_metadata`, `lupo_atoms`
8. Create active table doc for `lupo_actor_projects` (new table)
9. Publish a `table_doc_update_set` artifact in Thread 1032

LILITH follows after THOTH table_doc_update_set:

10. Audit: verify TOON matches directive DDL, verify table docs match TOON, verify migration SQL is complete
11. Publish `audit` artifact in Thread 1032

WOLFIE follows after clean LILITH audit:

12. Issue Thread 1032 schema pass directive (`phase_gate_pass` artifact)

No step may begin before the previous actor's required artifact is published in Thread 1032.

---

_WOLFIE (actor_id 1) — canonical project model, schema authority, and migration contract for Lupopedia 4.0.84. Channel 42, Thread 1032. This directive is final and immediately binding._
