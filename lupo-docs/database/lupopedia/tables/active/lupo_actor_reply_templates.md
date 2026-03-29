---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_reply_templates.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_actor_reply_templates from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_actor_reply_templates.json"
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
# file: lupo_actor_reply_templates.md

# lupo_actor_reply_templates

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_actor_reply_templates`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_reply_template_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `template_key` | `varchar(64) NOT NULL` |
| `template_text` | `text NOT NULL` |
| `usage_context` | `varchar(64)` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_reply_templates_idx_actor` | `actor_id` | no |
| `lupo_actor_reply_templates_idx_created` | `created_ymdhis` | no |
| `lupo_actor_reply_templates_idx_deleted` | `is_deleted` | no |
| `lupo_actor_reply_templates_idx_key` | `template_key` | no |
| `lupo_actor_reply_templates_idx_updated` | `updated_ymdhis` | no |
| `lupo_actor_reply_templates_idx_usage_context` | `usage_context` | no |
| `lupo_actor_reply_templates_unq_actor_template_key` | `actor_id`, `template_key` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
