---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_atoms.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_atoms.md
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
# file: lupo_atoms.md

# lupo_atoms

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_atoms`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `atom_id` | `bigint NOT NULL` |
| `atom_name` | `varchar(255) NOT NULL` |
| `context_id` | `bigint NOT NULL` |
| `is_authoritative` | `tinyint NOT NULL DEFAULT 0` |
| `value_json` | `json` |
| `summary` | `text` |
| `tags` | `varchar(255)` |
| `created_ymd` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymd` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_atoms_idx_atom_context` | `atom_name`, `context_id` | no |
| `lupo_atoms_idx_atom_name` | `atom_name` | no |
| `lupo_atoms_idx_authoritative` | `is_authoritative` | no |
| `lupo_atoms_idx_context_id` | `context_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
