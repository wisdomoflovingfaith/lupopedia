---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_auth_users.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "auth"
  purpose: "Normalized table documentation for lupo_actor_auth_users from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_actor_auth_users.json"
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
# file: lupo_actor_auth_users.md

# lupo_actor_auth_users

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_actor_auth_users`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_auth_user_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `auth_user_id` | `bigint NOT NULL` |
| `relationship_role` | `varchar(64) NOT NULL DEFAULT 'supporting_human'` |
| `is_primary` | `tinyint NOT NULL DEFAULT 0` |
| `routing_priority` | `smallint NOT NULL DEFAULT 100` |
| `status` | `varchar(32) NOT NULL DEFAULT 'active'` |
| `metadata_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_auth_users_idx_actor_role_primary_lookup` | `actor_id`, `relationship_role`, `status`, `is_deleted`, `is_primary`, `routing_priority`, `auth_user_id` | no |
| `lupo_actor_auth_users_idx_actor_routing` | `actor_id`, `status`, `is_deleted`, `relationship_role`, `is_primary`, `routing_priority`, `auth_user_id` | no |
| `lupo_actor_auth_users_idx_actor_status_primary_priority` | `actor_id`, `status`, `is_primary`, `routing_priority` | no |
| `lupo_actor_auth_users_idx_auth_user_status` | `auth_user_id`, `status` | no |
| `lupo_actor_auth_users_idx_status` | `status` | no |
| `lupo_actor_auth_users_unq_actor_user_role` | `actor_id`, `auth_user_id`, `relationship_role` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
