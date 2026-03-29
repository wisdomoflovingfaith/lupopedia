---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_api_tokens.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "integration"
  purpose: "Normalized table documentation for lupo_api_tokens from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_api_tokens.json"
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
# file: lupo_api_tokens.md

# lupo_api_tokens

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_api_tokens`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `api_token_id` | `bigint NOT NULL` |
| `domain_id` | `bigint NOT NULL DEFAULT 1` |
| `actor_id` | `bigint NOT NULL DEFAULT 0` |
| `token_key` | `varchar(255) NOT NULL` |
| `token_label` | `varchar(150)` |
| `scopes` | `text` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `expires_ymdhis` | `bigint` |
| `last_used_ymdhis` | `bigint` |
| `created_ip` | `varchar(45)` |
| `last_used_ip` | `varchar(45)` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `notes` | `text` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_api_tokens_idx_active` | `is_active` | no |
| `lupo_api_tokens_idx_actor` | `actor_id` | no |
| `lupo_api_tokens_idx_actor_active` | `actor_id`, `is_active` | no |
| `lupo_api_tokens_idx_domain` | `domain_id` | no |
| `lupo_api_tokens_idx_expires` | `expires_ymdhis` | no |
| `lupo_api_tokens_idx_last_used` | `last_used_ymdhis` | no |
| `lupo_api_tokens_uq_token_key` | `token_key` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
