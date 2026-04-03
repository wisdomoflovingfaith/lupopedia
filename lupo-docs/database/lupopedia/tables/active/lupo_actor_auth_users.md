---
lupopedia.headers:
  when_updated: "20260403192018"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_auth_users.md"
  last_modified_utc: "20260403192018"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "auth"
  purpose: "Auth user ↔ actor pairing; canonical install table (no separate exclusive lease-session table)"
  tags:
  - database
  - table
  - normalized
  - 4.0.94
lupopedia.edges:
  outbound_edges:
  - to: "lupo-database/lupopedia/toon/lupo_actor_auth_users.toon.json"
    type: "references"
    weight: 1.0
    reason: "TOON schema from install_new_lupopedia.sql"
  - to: "lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
    type: "references"
    weight: 0.9
    reason: "Auth user ↔ actor model"
lupopedia.footer:
  last_verified: "20260403192018"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
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

- Schema source: **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`**; TOON: **`lupo-database/lupopedia/toon/lupo_actor_auth_users.toon.json`**.
