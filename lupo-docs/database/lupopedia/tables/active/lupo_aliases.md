---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_aliases.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_aliases from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_aliases.json"
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
# file: lupo_aliases.md

# lupo_aliases

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_aliases`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `alias_id` | `bigint NOT NULL` |
| `slug` | `varchar(255) NOT NULL` |
| `alias` | `varchar(255) NOT NULL` |
| `alias_type` | `varchar(50) DEFAULT 'semantic'` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_aliases_idx_slug` | `slug` | no |
| `lupo_aliases_uniq_alias` | `alias` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
