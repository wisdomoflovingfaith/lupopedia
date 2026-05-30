---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_sticky_notes.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_sticky_notes.md"
  status: "active"
  when_updated: "20260415214000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/lupo_sticky_notes.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/lupo_sticky_notes_doc"
  artifact_type: documentation
  artifact_kind: table
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: "lupo_sticky_notes"
  title: "Table: lupo_sticky_notes"
  summary: "Persistent channel-scoped annotations for the operator. Digital equivalent of physical sticky notes."
  module: "Orchestration"
  lupopedia.schema: documentation
  lupopedia.edges:
    - type: DEPENDSONTABLE
      to: "lupo_channels"
      comment: "channel_id scope"
    - type: DEPENDSONTABLE
      to: "lupo_actors"
      comment: "actor_id owner"
    - type: DEFINESSCHEMAFOR
      to: "lupo_sticky_notes"
---
# Table: lupo_sticky_notes

## Purpose
**Digital Sticky Notes** are persistent, channel-scoped annotations created by the human operator. They are designed to track high-level context, blockers, or mental state ("Who has what", "What is blocked") that doesn't fit into the chronological chat feed.

## Schema

### Primary Key
- `note_id`: bigint NOT NULL

### Columns

| Column | Type Definition | Description |
|---|---|---|
| `note_id` | `bigint NOT NULL` | Primary key (YYYYMMDDHHIISS or Auto-Inc) |
| `channel_id` | `bigint NOT NULL` | Scopes the note to a specific channel (0 = global) |
| `actor_id` | `bigint NOT NULL` | The creator persona |
| `note_content` | `text NOT NULL` | The text content of the note |
| `note_color` | `varchar(7) DEFAULT '#ffff00'` | Hex color code for visual distinction |
| `is_pinned` | `tinyint(1) DEFAULT 1` | Flag to keep note at top of dashboard sidebar |
| `created_ymdhis` | `bigint NOT NULL` | Creation timestamp (YYYYMMDDHHIISS) |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `PRIMARY` | `note_id` | yes |
| `idx_actor_channel` | `actor_id`, `channel_id` | no |

## Doctrine
- **Visibility:** Always visible in the dashboard sidebar.
- **Scoping:** Can be global or pinned to a specific channel.
- **Source of Truth:** Aligns with `database/lupopedia/json/lupo_sticky_notes.json`.
