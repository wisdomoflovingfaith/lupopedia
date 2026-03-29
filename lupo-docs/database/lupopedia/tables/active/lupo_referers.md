---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_referers.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_referers from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_referers.json"
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
# file: lupo_referers.md

# lupo_referers

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_referers`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `referer_id` | `bigint NOT NULL auto_increment` |
| `content_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `referer_url` | `varchar(2000)` |
| `referer_domain` | `varchar(255)` |
| `referer_path` | `varchar(2000)` |
| `referer_content_id` | `bigint` |
| `date_ymd` | `int NOT NULL` |
| `visits` | `int NOT NULL DEFAULT 1` |
| `depth` | `int NOT NULL DEFAULT 0` |
| `metadata_json` | `json` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_referers_idx_actor_id` | `actor_id` | no |
| `lupo_referers_idx_content_id` | `content_id` | no |
| `lupo_referers_idx_date` | `date_ymd` | no |
| `lupo_referers_idx_referer_content_id` | `referer_content_id` | no |
| `lupo_referers_idx_referer_domain` | `referer_domain` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
