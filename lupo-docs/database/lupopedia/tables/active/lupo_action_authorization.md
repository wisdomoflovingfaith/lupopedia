---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_action_authorization.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_action_authorization from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_action_authorization.json"
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
# file: lupo_action_authorization.md

# lupo_action_authorization

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_action_authorization`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `action_authorization_id` | `bigint NOT NULL` |
| `action_key` | `varchar(100) NOT NULL` |
| `description` | `text NOT NULL` |
| `required_trait_keys` | `text` |
| `required_capabilities` | `text` |
| `required_role_keys` | `text` |
| `requires_all_conditions` | `tinyint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `created_by_actor_id` | `bigint NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_action_authorization_idx_action` | `action_key` | no |
| `lupo_action_authorization_unique_action_key` | `action_key` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
