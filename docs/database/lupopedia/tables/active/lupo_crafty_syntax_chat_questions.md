---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_crafty_syntax_chat_questions.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_crafty_syntax_chat_questions.md
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
# file: lupo_crafty_syntax_chat_questions.md

# lupo_crafty_syntax_chat_questions

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_crafty_syntax_chat_questions`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `crafty_syntax_chat_question_id` | `bigint NOT NULL auto_increment` |
| `department_id` | `bigint NOT NULL DEFAULT 0` |
| `sort_order` | `int NOT NULL DEFAULT 0` |
| `headertext` | `mediumtext` |
| `field_type` | `varchar(60)` |
| `options` | `mediumtext` |
| `flags` | `varchar(255)` |
| `module_name` | `varchar(100)` |
| `is_required` | `tinyint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_crafty_syntax_chat_questions_idx_department` | `department_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
