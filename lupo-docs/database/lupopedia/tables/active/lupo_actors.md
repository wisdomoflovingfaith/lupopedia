---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_actors from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_actors.json"
    type: "references"
    weight: 1.0
    reason: "authoritative TOON JSON source"
lupopedia.footer:
  last_verified: "20260328013000"
  last_verified_by: "hephaestus"
  last_verified_by_actor_id: 23
  generated: true
  provenance: "stage3_track_c_normalization"
---
# file: lupo_actors.md

# lupo_actors

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_actors`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_name` | `varchar(64) NOT NULL` |
| `actor_id` | `bigint` |
| `actor_type` | `varchar(64) NOT NULL` |
| `slug` | `varchar(255) NOT NULL` |
| `name` | `varchar(255) NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `actor_source_id` | `bigint` |
| `actor_source_type` | `varchar(64)` |
| `metadata` | `text` |
| `actor_config` | `text` |
| `adversarial_role` | `varchar(64) DEFAULT 'none'` |
| `adversarial_oversight_actor_id` | `bigint` |
| `avatar_hash` | `varchar(64)` |
| `primary_federation_node_id` | `bigint NOT NULL DEFAULT 1` |
| `department_id` | `bigint` |
| `is_kernel` | `tinyint NOT NULL DEFAULT 0` |
| `can_login` | `tinyint NOT NULL DEFAULT 0` |
| `metadata_json` | `json` |
| `identity_provider_config` | `json` |
| `paired_actor_id` | `bigint NOT NULL DEFAULT 0` |
| `is_agent` | `tinyint NOT NULL DEFAULT 0` |
| `actor_root_path` | `varchar(512) DEFAULT 'actors/{actor_id}'` |
| `workspace_path` | `varchar(255)` |
| `php_namespace` | `varchar(120)` |
| `who_json_sync_status` | `varchar(64) DEFAULT 'pending'` |
| `last_sync_ymdhis` | `bigint DEFAULT 0` |
| `auth_user_id` | `bigint` |
| `actor_tier` | `tinyint DEFAULT 3` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actors_idx_actor_type` | `actor_type` | no |
| `lupo_actors_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_actors_idx_is_active` | `is_active` | no |
| `lupo_actors_idx_php_namespace` | `php_namespace` | no |
| `lupo_actors_idx_workspace_path` | `workspace_path` | no |
| `lupo_actors_unique_actor_id` | `actor_id` | yes |
| `lupo_actors_unique_slug` | `slug` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
