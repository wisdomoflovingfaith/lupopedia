---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_operator_scratchpad.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_operator_scratchpad.md"
  status: "active"
  when_updated: "20260415214000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/lupo_operator_scratchpad.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/lupo_operator_scratchpad_doc"
  artifact_type: documentation
  artifact_kind: table
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: "lupo_operator_scratchpad"
  title: "Table: lupo_operator_scratchpad"
  summary: "Persistent private text buffer for the operator (actor_id=1). Replaces manual Notepad drafting."
  module: "Orchestration"
  lupopedia.schema: documentation
  lupopedia.edges:
    - type: USEDINPHP
      to: "app/Services/TaskService.php"
      comment: "Promotion logic from scratchpad to task"
    - type: DEPENDSONTABLE
      to: "lupo_actors"
      comment: "Referenced via actor_id"
    - type: REFERENCEDBYTABLE
      to: "lupo_routing_events"
      comment: "source_scratchpad_id reference"
    - type: DEFINESSCHEMAFOR
      to: "lupo_operator_scratchpad"
---
# Table: lupo_operator_scratchpad

## Purpose
The **Operator Scratchpad** is a persistent, private text buffer for the human operator (actor_id=1). It is designed to replace external tools like `Notepad.exe` for iterative prompt composition and staging before a task is dispatched or a message is posted to a channel.

It is NOT a channel and its content is NOT visible to agents until "promoted."

## Schema

### Primary Key
- `scratchpad_id`: bigint NOT NULL

### Columns

| Column | Type Definition | Description |
|---|---|---|
| `scratchpad_id` | `bigint NOT NULL` | Primary key (YYYYMMDDHHIISS or Auto-Inc) |
| `actor_id` | `bigint NOT NULL` | Owner persona (typically CAPTAIN=10001) |
| `content_body` | `text NOT NULL` | The draft text content |
| `last_saved_ymdhis` | `bigint NOT NULL` | Timestamp of last manual or auto-save |
| `is_promoted` | `tinyint(1) DEFAULT 0` | Flag indicating if this draft has been turned into a task/message |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `PRIMARY` | `scratchpad_id` | yes |
| `idx_actor_updated` | `actor_id`, `last_saved_ymdhis` | no |

## Doctrine
- **Visibility:** Private to the operator. Agents have zero read access to this table.
- **Persistence:** State is preserved across sessions.
- **Promotion:** Content is "dispatched" to the system via promotion APIs, creating a record in `lupo_routing_events`.
- **Source of Truth:** Aligns with `database/lupopedia/json/lupo_operator_scratchpad.json`.
