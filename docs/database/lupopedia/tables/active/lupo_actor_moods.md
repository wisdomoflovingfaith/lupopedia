---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_actor_moods.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_actor_moods.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: table
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: null
  prd_cluster: null
  title: ''
  summary: ''
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
