---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_actor_moods.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: table
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
