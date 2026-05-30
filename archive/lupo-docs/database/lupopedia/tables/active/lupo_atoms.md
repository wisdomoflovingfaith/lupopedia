---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_atoms.md"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
