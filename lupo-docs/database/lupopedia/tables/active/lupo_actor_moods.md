---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_moods.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_actor_moods from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_actor_moods.json"
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
# file: lupo_actor_moods.md

# lupo_actor_moods

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_actor_moods`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_id` | `bigint NOT NULL` |
| `mood_r` | `tinyint NOT NULL` |
| `mood_g` | `tinyint NOT NULL` |
| `mood_b` | `tinyint NOT NULL` |
| `mood_framework` | `varchar(32) NOT NULL DEFAULT 'western_analytical'` |
| `timestamp_utc` | `bigint NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
