---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_crafty_syntax_auto_invite.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "legacy"
  purpose: "Normalized table documentation for lupo_crafty_syntax_auto_invite from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_crafty_syntax_auto_invite.json"
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
# file: lupo_crafty_syntax_auto_invite.md

# lupo_crafty_syntax_auto_invite

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_crafty_syntax_auto_invite`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `crafty_syntax_auto_invite_id` | `bigint NOT NULL auto_increment` |
| `is_offline` | `tinyint NOT NULL DEFAULT 0` |
| `is_active` | `tinyint NOT NULL DEFAULT 0` |
| `department_id` | `bigint NOT NULL DEFAULT 0` |
| `message` | `mediumtext` |
| `page_url` | `varchar(500)` |
| `visits` | `int NOT NULL DEFAULT 0` |
| `referrer_url` | `varchar(500)` |
| `invite_type` | `varchar(50)` |
| `trigger_seconds` | `int NOT NULL DEFAULT 0` |
| `operator_user_id` | `bigint NOT NULL DEFAULT 0` |
| `show_socialpane` | `tinyint NOT NULL DEFAULT 0` |
| `exclude_mobile` | `tinyint NOT NULL DEFAULT 0` |
| `only_mobile` | `tinyint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 20250101000000` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 20250101000000` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_crafty_syntax_auto_invite_idx_created` | `created_ymdhis` | no |
| `lupo_crafty_syntax_auto_invite_idx_department` | `department_id` | no |
| `lupo_crafty_syntax_auto_invite_idx_operator` | `operator_user_id` | no |
| `lupo_crafty_syntax_auto_invite_idx_page_url` | `page_url` | no |
| `lupo_crafty_syntax_auto_invite_idx_status` | `is_active`, `is_deleted` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
