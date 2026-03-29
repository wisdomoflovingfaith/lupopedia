---
lupopedia.headers:
  when_updated: "20260327234500"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_departments.md"
  last_modified_utc: "20260327234500"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Regenerated table documentation for lupo_actor_departments from TOON JSON"
  tags:
  - database
  - table
  - regenerated
  - 4.0.88
lupopedia.edges:
  comment: "Snapshot stage1 confidence-scored edges (git=1.0, code-scan=0.7, db=0.5)."
  meta: git_hits=8 code_scan_hits=6 db_hits=0
  outbound_edges:
  - to: "database.table.lupo_actor_departments"
    type: "DEFINES_SCHEMA_FOR"
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
    reason: "historical edge restored from clean doc history"
  - to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
    type: "schema_reference"
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
    reason: "canonical install schema reference"
  - to: "lupo-database/lupopedia/content/lupo-app/Services/SavedCollectionsService.php"
    type: "USED_IN_PHP"
    weight: 0.7
    confidence: 0.7
    source: "code-scan"
    reason: "direct table usage in operational service"
  - to: "lupo-database/lupopedia/content/lupo-app/auth/AuthRoleResolver.php"
    type: "USED_IN_PHP"
    weight: 0.7
    confidence: 0.7
    source: "code-scan"
    reason: "department resolution logic references table"
  - to: "lupo-scripts/rebuild_schema_from_toons.py"
    type: "USED_IN_PYTHON"
    weight: 0.7
    confidence: 0.7
    source: "code-scan"
    reason: "schema rebuild script includes table"
  - to: "lupo-scripts/wolfie_orms.py"
    type: "USED_IN_PYTHON"
    weight: 0.7
    confidence: 0.7
    source: "code-scan"
    reason: "ORM query helper references table"
  - to: "lupo-database/lupopedia/json/lupo_actor_departments.json"
    type: "references"
    weight: 1.0
    confidence: 1.0
    source: "schema-source"
    reason: "authoritative TOON JSON source"
lupopedia.footer:
  last_verified: "20260327234500"
  last_verified_by: "hephaestus"
  last_verified_by_actor_id: 23
  generated: true
  provenance: "phase2_synthetic_header_no_git_recovery"
  generated_at_iso: "2026-03-27T23:45:00Z"
---
# file: lupo_actor_departments.md

# lupo_actor_departments

## Purpose
Canonical table documentation regenerated from TOON JSON for `lupo_actor_departments`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_department_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `department_id` | `bigint NOT NULL` |
| `role_key` | `varchar(64)` |
| `title` | `varchar(64)` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_departments_idx_actor` | `actor_id` | no |
| `lupo_actor_departments_idx_department` | `department_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Phase 2 deterministic rebuild
- Edge mode: placeholder only
