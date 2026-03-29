---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_crafty_syntax_leave_message.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "legacy"
  purpose: "Normalized table documentation for lupo_crafty_syntax_leave_message from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_crafty_syntax_leave_message.json"
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
# file: lupo_crafty_syntax_leave_message.md

# lupo_crafty_syntax_leave_message

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_crafty_syntax_leave_message`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `crafty_syntax_leave_message_id` | `bigint NOT NULL auto_increment` |
| `department_id` | `bigint NOT NULL DEFAULT 0` |
| `email` | `varchar(255) NOT NULL DEFAULT ''` |
| `phone` | `varchar(45)` |
| `name` | `varchar(200)` |
| `subject` | `varchar(255) NOT NULL DEFAULT ''` |
| `message` | `text` |
| `priority` | `tinyint NOT NULL DEFAULT 2` |
| `session_data` | `text` |
| `form_data` | `text` |
| `ip_address` | `varchar(45)` |
| `user_agent` | `varchar(255)` |
| `status` | `enum('new','in_progress','resolved','spam') NOT NULL DEFAULT 'new'` |
| `assigned_to` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_crafty_syntax_leave_message_idx_assigned` | `assigned_to` | no |
| `lupo_crafty_syntax_leave_message_idx_created` | `created_ymdhis` | no |
| `lupo_crafty_syntax_leave_message_idx_department` | `department_id` | no |
| `lupo_crafty_syntax_leave_message_idx_email` | `email` | no |
| `lupo_crafty_syntax_leave_message_idx_message_search` | `email`, `name`, `subject`, `message` | no |
| `lupo_crafty_syntax_leave_message_idx_priority` | `priority` | no |
| `lupo_crafty_syntax_leave_message_idx_status` | `status` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
