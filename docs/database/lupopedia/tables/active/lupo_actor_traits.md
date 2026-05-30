---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_actor_traits.md"
  web_path: '[lupo_actor_traits](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_traits)'
  last_modified_utc: "20260327234500"
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: "core"
  purpose: Per-actor trait records (capabilities / flags) used for authorization and
    orchestration behavior.
  tags:
  - database
  - table
  - core
  when_updated: "20260327234500"
lupopedia.edges:
  comment: "Snapshot stage1 confidence-scored edges (git=1.0, code-scan=0.7, db=0.5)."
    repo search; non-exhaustive).
  meta: php_hits=0 python_hits=1
  outbound_edges:
  - to: database.table.lupo_actor_traits
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: scripts/check_doc_schema_consistency.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: (no_php_refs_found)
    type: USED_IN_PHP
    weight: 0.0
    confidence: 0.7
    source: "code-scan"
lupopedia.footer:
  provenance: "phase2_git_header_recovered_body_regenerated"
  generated: true
  last_verified: "20260327234500"
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_actor_traits.md

# lupo_actor_traits

## Purpose
Canonical table documentation regenerated from TOON JSON for `lupo_actor_traits`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_trait_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `trait_key` | `varchar(128) NOT NULL` |
| `trait_value` | `varchar(512)` |
| `federation_node_id` | `bigint NOT NULL DEFAULT 1` |
| `created_by_actor_id` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `metadata` | `text` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_traits_idx_actor` | `actor_id` | no |
| `lupo_actor_traits_idx_actor_key` | `actor_id`, `trait_key` | no |
| `lupo_actor_traits_idx_federation` | `federation_node_id` | no |
| `lupo_actor_traits_idx_is_deleted` | `is_deleted` | no |
| `lupo_actor_traits_idx_trait_key` | `trait_key` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Phase 2 deterministic rebuild
- Edge mode: placeholder only
