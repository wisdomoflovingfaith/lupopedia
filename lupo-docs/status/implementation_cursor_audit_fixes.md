---
lupopedia.init:
  document_type: "status_report"
  system_version: "4.0.73"

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/status/implementation_cursor_audit_fixes.md"
  web_path: "http://www.lupopedia.com/status/implementation_cursor_audit_fixes"
  last_modified_utc: "20260313"
  system_version: "4.0.73"
  channel_id: 42
  actor_id: 1003
  artifact_type: "documentation"
  artifact_kind: "implementation_report"
  purpose: "Summary and verification checklist for Cursor orchestrator task: Antigravity audit fixes + lupopedia.metadata snapshot headers (v4.0.73)."

lupopedia.edges:
  comment: "Snapshot at artifact creation."
  meta: "L-LUPO-ROOT-CURSOR; orchestrator audit + metadata headers."
  outbound_edges:
    - { to: "lupo-rules/root/README.md", type: "references", weight: 1.0 }
    - { to: "README.md", type: "references", weight: 1.0 }
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "TODO.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.9 }
    - { to: "database/migrations/20260313_lupo_orchestrator_rules.sql", type: "references", weight: 0.95 }
    - { to: "scripts/sync_orchestrator_rules_to_db.php", type: "references", weight: 0.95 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md", type: "references", weight: 0.95 }

lupopedia.engagement:
  comment: "Implementation report for Cursor orchestrator task."
  meta: "v4.0.73 audit + metadata."
  views: 0

lupopedia.footer:
  version: "4.0.73"
  last_verified: "20260313"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Run sync script after migration when lupo_orchestrator_rules table exists"
    - "When rehydrating from lupo_metadata, populate lupopedia.metadata with property_key–grouped row snapshots per OPTIONAL_BLOCKS.md"
---
# file: Implementation Cursor Audit Fixes — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/status/implementation_cursor_audit_fixes

# Implementation Report — Cursor Orchestrator Audit Fixes + LUPOPEDIA METADATA HEADERS (v4.0.73)

**Session:** L-LUPO-ROOT-CURSOR  
**Delegation:** cursor:root  
**Date:** 2026-03-13

## Objective

Implement orchestrator rules enforcement, add `lupopedia.metadata` blocks (snapshot of metadata rows for the artifact, not table schema), create `lupo_orchestrator_rules` table and sync script, and add metadata headers to core repository files. **Note:** `lupopedia.metadata` was later corrected to represent metadata **rows/values** grouped by `property_key`, not column definitions; see OPTIONAL_BLOCKS.md and CHANGELOG.

## Rules Loaded

All 17 rule files in `lupo-rules/root/` were read and treated as absolute doctrine. Conflict between prompt and rule files was resolved in favor of the rule files (e.g. primary key for `lupo_actors` remains `actor_id` per TOON and reserved-id doctrine).

## Completed Items

### 1. Rule file initialization (lupo-rules/root/)

- **lupopedia.init** added at the top of every rule file with:
  - `orchestrator_actor: "any"`
  - `rule_set_version: "4.0.73+"`
  - `applies_to: ["audit", "code-gen", "db-sync", "migration", "header-sync"]`
  - `enforcement: strict`
- **lupopedia.metadata** block added to every rule file. Correct meaning: snapshot of metadata **rows/values** for this file or entity (grouped by `property_key`), not table schema. Where no metadata rows exist yet, the block contains only `comment: "Snapshot of metadata for this file or entity at artifact creation."` (see OPTIONAL_BLOCKS.md).
- **lupopedia.footer** updated to `version: "4.0.73"`, `last_verified: "20260313"`, `orchestrator: "cursor"`, and `next_action` where applicable.
- **Files updated:** README.md, pk-reference-naming-doctrine.md, database-logic-prohibition-doctrine.md, migration-doctrine.md, php-5-6-compatibility.md, required-tables-future-features-doctrine.md, single-install-no-4.0-upgrade-doctrine.md, versioning-doctrine-single-source.md, no-laravel-no-middleware.md, toon-source-of-truth.md, wheeler-reverse20-ban.md, stoned-wolfie-schrodinger-ban.md, quantum-state-uncertainty-ban.md, reserved-id-doctrine.md, flip-doctrine.md, experimental-ai-artifact-ban.md, pdo-db-database-access-doctrine.md.

### 2. lupo_actors.md

- **Primary key:** Left as **actor_id** per TOON and pk-reference-naming-doctrine. No change to actor_name as PK (that would violate doctrine).
- **lupopedia.init** and **lupopedia.metadata** blocks added; **table_primary_key** kept and documented as `actor_id`.

### 3. lupo_orchestrator_rules table

- **Schema (doctrine-compliant):** No `TINYINT(1)` (used `TINYINT`); no `DATETIME` (used `BIGINT` for `updated_ymdhis`); `applies_to_json` as `TEXT` for portability (JSON stored as string).
- **Install:** Table and indexes added to `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`.
- **Migration:** `database/migrations/20260313_lupo_orchestrator_rules.sql` created (one-time; run via `php scripts/run_one_time_sql.php database/migrations/20260313_lupo_orchestrator_rules.sql` or manually).

### 4. Sync script

- **scripts/sync_orchestrator_rules_to_db.php** reads every `.md` file in `lupo-rules/root/`, computes MD5 checksum, and inserts or updates rows in `lupo_orchestrator_rules` (rule_slug, rule_content, checksum, etc.). Run after the migration.

### 5. Core files: README.md, CHANGELOG.md, TODO.md

- **README.md:** Added **lupopedia.init** (file_identity, artifact_type, artifact_kind, namespace, domain) and **lupopedia.metadata** (snapshot block with comment; not table schema). Footer already had required fields; **orchestrator** and **version** confirmed.
- **CHANGELOG.md:** Added **lupopedia.metadata** (snapshot block) and extended **lupopedia.init** with file_identity, artifact_type, artifact_kind, namespace, domain.
- **TODO.md (root):** Created with full LUPOPEDIA HEADERS including **lupopedia.init**, **lupopedia.metadata** (snapshot with comment), **lupopedia.headers**, **lupopedia.edges**, **lupopedia.engagement**, **lupopedia.footer**. Content includes immediate tasks (run migration, run sync script) and pointer to CHANGELOG for version-specific pending work.

## Verification Checklist

- [x] Every rule file in `lupo-rules/root/` has `lupopedia.init` + `lupopedia.metadata`.
- [x] `lupo_actors.md` has `lupopedia.metadata`; PK remains `actor_id`.
- [x] `lupo_orchestrator_rules` table defined in future_features and migration created.
- [x] Sync script created and uses PDO_DB / table prefix.
- [x] README.md, CHANGELOG.md, TODO.md contain `lupopedia.metadata` blocks (snapshot of metadata rows for the artifact, or comment-only when no rows exist).

## Sample Query for IDE Agents

To load active orchestrator rules (e.g. for cursor or any):

```sql
SELECT rule_content FROM lupo_orchestrator_rules
WHERE (orchestrator_actor = 'cursor' OR orchestrator_actor = 'any')
AND is_active = 1;
```

(Use `LUPO_TABLE_PREFIX` in application code so the table name is `{prefix}orchestrator_rules`.)

## Before/After (Core Files)

- **README.md:** Before: lupopedia.headers, session, edges, footer. After: + lupopedia.init (file_identity, artifact_type, artifact_kind, namespace, domain), + lupopedia.metadata (comment snapshot; not table schema), footer version/orchestrator set.
- **CHANGELOG.md:** Before: lupopedia.init (minimal), lupopedia.headers, footer. After: lupopedia.init extended (file_identity, artifact_type, artifact_kind, namespace, domain), + lupopedia.metadata (snapshot with comment).
- **TODO.md:** New file with full headers and body; no “before.”

## Commit Message

Suggested commit message:

```
L-LUPO-ROOT-CURSOR: Implement Antigravity audit + full lupopedia.metadata headers (v4.0.73)
```

---

**Verdict:** Implementation complete. All rule files and core files have init and metadata blocks; orchestrator rules table and sync script are in place; doctrine (PK naming, no DB logic, BIGINT timestamps) was respected.
