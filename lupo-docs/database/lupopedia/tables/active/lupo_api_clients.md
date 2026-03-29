---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_api_clients.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "integration"
  purpose: "Normalized table documentation for lupo_api_clients from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_api_clients.json"
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
# file: lupo_api_clients.md

# lupo_api_clients

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_api_clients`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `api_client_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL DEFAULT 0` |
| `client_key` | `varchar(255) NOT NULL` |
| `client_secret` | `varchar(255) NOT NULL` |
| `client_name` | `varchar(150) NOT NULL` |
| `client_description` | `text` |
| `scopes` | `text` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `expires_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_api_clients_idx_active` | `is_active` | no |
| `lupo_api_clients_idx_actor` | `actor_id` | no |
| `lupo_api_clients_idx_expires` | `expires_ymdhis` | no |
| `lupo_api_clients_uq_client_key` | `client_key` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
