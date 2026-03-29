---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_channel_state.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "channels"
  purpose: "Normalized table documentation for lupo_channel_state from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_channel_state.json"
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
# file: lupo_channel_state.md

# lupo_channel_state

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_channel_state`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `channel_state_id` | `bigint NOT NULL` |
| `channel_id` | `bigint NOT NULL` |
| `active_actors_json` | `json` |
| `speaker_actors_json` | `json` |
| `observer_actors_json` | `json` |
| `layers_enabled_json` | `json` |
| `operational_mode` | `varchar(32)` |
| `emotional_state_json` | `json` |
| `mood_framework` | `varchar(32) NOT NULL DEFAULT 'western_analytical'` |
| `recent_topics_json` | `json` |
| `semantic_weight` | `float DEFAULT 0` |
| `trend_score` | `float DEFAULT 0` |
| `last_activity_ymdhis` | `bigint` |
| `context_vector` | `blob` |
| `routing_rules` | `varchar(32)` |
| `edge_visibility` | `varchar(32)` |
| `retention_policy` | `varchar(32)` |
| `decay_policy` | `varchar(32)` |
| `archive_flag` | `tinyint DEFAULT 0` |
| `metadata_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_channel_state_idx_channel_id` | `channel_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
