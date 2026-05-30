---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_search_index.md"
  web_path: '[lupo_search_index](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_search_index)'
  last_modified_utc: "20260327234500"
  channel_id: 42
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: "core"
  purpose: Search index management; tracks content indexing, search terms, and semantic
    relationships
  tags:
  - database
  - table
  - core
  when_updated: "20260327234500"
lupopedia.edges:
  comment: "Snapshot stage1 confidence-scored edges (git=1.0, code-scan=0.7, db=0.5)."
    repo search; non-exhaustive).
  meta: php_hits=0 python_hits=0
  outbound_edges:
  - to: database.table.lupo_search_index
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: (no_php_refs_found)
    type: USED_IN_PHP
    weight: 0.0
    confidence: 0.7
    source: "code-scan"
  - to: (no_python_refs_found)
    type: USED_IN_PYTHON
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
# file: lupo_search_index.md

# lupo_search_index

## Purpose
Canonical table documentation regenerated from TOON JSON for `lupo_search_index`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `search_index_id` | `bigint NOT NULL` |
| `domain_id` | `bigint NOT NULL` |
| `entity_type` | `varchar(50) NOT NULL` |
| `entity_id` | `bigint NOT NULL` |
| `title_text` | `text` |
| `body_text` | `text` |
| `keywords_text` | `text` |
| `search_metadata` | `text` |
| `relevance_score` | `float DEFAULT 1` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_search_index_idx_domain_type` | `domain_id`, `entity_type` | no |
| `lupo_search_index_idx_entity_reference` | `entity_type`, `entity_id` | no |
| `lupo_search_index_idx_is_deleted` | `is_deleted` | no |
| `lupo_search_index_idx_relevance` | `relevance_score` | no |
| `lupo_search_index_idx_updated` | `updated_ymdhis` | no |
| `lupo_search_index_unique_entity` | `domain_id`, `entity_type`, `entity_id` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Phase 2 deterministic rebuild
- Edge mode: placeholder only
