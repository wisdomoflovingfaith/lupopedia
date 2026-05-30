---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260403192018"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_actor_auth_users.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: table
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: lupo_actor_auth_users.md — web_path: (table doc)

# lupo_actor_auth_users

## Purpose

Rows link **`lupo_auth_users`** to **`lupo_actors`** (relationship role, routing, soft delete). This is the canonical pairing table in install SQL. Web “act as” exclusivity is **not** enforced here; optional per-actor web limits use **`lupo_actors.web_restrict_act_as_creator_or_root`** in PHP.

## Schema

### Primary Key

`actor_auth_user_id` (BIGINT, application-assigned)

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

- Schema source: **`database/lupopedia/mysql/install/install_new_lupopedia.sql`**; TOON: **`database/lupopedia/toon/lupo_actor_auth_users.toon.json`**.
