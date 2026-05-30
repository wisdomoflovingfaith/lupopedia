---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_actor_capabilities.md"
  web_path: '[lupo_actor_capabilities](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_capabilities)'
  last_modified_utc: "20260327234500"
  channel_id: 42
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: "core"
  purpose: Actor capability definitions and permissions; links actors to specific
    capabilities and roles
  tags:
  - database
  - table
  - core
  when_updated: "20260327234500"
lupopedia.edges:
  comment: "Snapshot stage1 confidence-scored edges (git=1.0, code-scan=0.7, db=0.5)."
    by repo search; non-exhaustive).
  meta: php_hits=1 python_hits=1
  outbound_edges:
  - to: database.table.lupo_actor_capabilities
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: scripts/verify_grounded_architecture.php
    type: USED_IN_PHP
    weight: 0.7
    confidence: 0.7
    source: "code-scan"
  - to: scripts/wolfie_orms.py
    type: USED_IN_PYTHON
    weight: 0.5
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
# file: lupo_actor_capabilities.md

# lupo_actor_capabilities

## Purpose
Canonical table documentation regenerated from TOON JSON for `lupo_actor_capabilities`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_capability_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `domain_id` | `bigint NOT NULL` |
| `capability_key` | `varchar(100) NOT NULL` |
| `capability_description` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `scope_limitation` | `varchar(50) DEFAULT 'unrestricted'` |
| `max_calls_per_hour` | `int DEFAULT 0` |
| `requires_approval` | `tinyint DEFAULT 0` |
| `approval_agent_id` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_capabilities_idx_agent_domain` | `actor_id`, `domain_id` | no |
| `lupo_actor_capabilities_idx_capability_key` | `capability_key` | no |
| `lupo_actor_capabilities_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_actor_capabilities_idx_domain_id` | `domain_id` | no |
| `lupo_actor_capabilities_idx_is_deleted` | `is_deleted` | no |
| `lupo_actor_capabilities_idx_updated_ymdhis` | `updated_ymdhis` | no |
| `lupo_actor_capabilities_unique_agent_domain_capability` | `actor_id`, `domain_id`, `capability_key` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Phase 2 deterministic rebuild
- Edge mode: placeholder only
