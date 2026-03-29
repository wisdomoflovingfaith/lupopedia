---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_comments.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_comments from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_comments.json"
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
# file: lupo_comments.md

# lupo_comments

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_comments`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `comment_id` | `bigint NOT NULL auto_increment` |
| `target_type` | `varchar(64) NOT NULL` |
| `target_id` | `bigint NOT NULL` |
| `channel_id` | `bigint NOT NULL DEFAULT 42` |
| `actor_id` | `bigint NOT NULL` |
| `faucet_id` | `bigint` |
| `comment_text` | `text NOT NULL` |
| `comment_type` | `varchar(64) NOT NULL DEFAULT 'comment'` |
| `parent_comment_id` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `metadata_json` | `json` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_comments_idx_actor_id` | `actor_id` | no |
| `lupo_comments_idx_channel_id` | `channel_id` | no |
| `lupo_comments_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_comments_idx_faucet_id` | `faucet_id` | no |
| `lupo_comments_idx_is_deleted` | `is_deleted` | no |
| `lupo_comments_idx_parent_comment_id` | `parent_comment_id` | no |
| `lupo_comments_idx_target` | `target_type`, `target_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
