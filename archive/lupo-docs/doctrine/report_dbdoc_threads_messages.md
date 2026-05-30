---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/report_dbdoc_threads_messages.md"
  web_path: null
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: ""
  summary: ""
---
# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  

lupopedia.edges: []
  file_path_from_root: "lupo-docs\doctrine\report_dbdoc_threads_messages.md"
  file_hash: "b2597c639d8d339298ef1104ed8272d68023dfcd4e6f84206ae1160a4c42bf86"
  file_path_from_root: "lupo-docs\doctrine\report_dbdoc_threads_messages.md"
  file_hash: "9895300d5914052274202d318746cac7373c1ab9850d8e874c49c6b6f541eaa9"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Dialog System Database Documentation"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "report_dbdoc_threads_messagesmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Dialog System Database Documentation
## Version 4.0.46

This document clarifies the purpose and usage of the dialog system tables: `lupo_dialog_messages`, `lupo_dialog_channels`, and `lupo_dialog_threads`.

### 1. lupo_dialog_messages
Stores individual dialog messages between actors (users/agents).

| Column | Type | Description |
| :--- | :--- | :--- |
| `dialog_message_id` | BIGINT | Global primary key. |
| `message_text` | VARCHAR(1000) | Truncated text for quick retrieval in thread views and previews. |
| `message_body` | MEDIUMTEXT | Full Markdown content of the message. Used for detailed viewing and offline access. |
| `mood_vector` | CHAR(6) | Hex color code representing the message's emotional tone. |
| `last_message_ymdhis` | BIGINT | Timestamp of the last message in the thread. |

**Usage Note:** For performance, `message_text` should be used when listing messages in a thread. `message_body` should only be loaded when opening the full detail view of a message.

### 2. lupo_dialog_channels
Groups threads into logical communication channels.

- **Timestamp Canonicalization:** Switched from `created_timestamp`/`modified_timestamp` to `created_ymdhis`/`updated_ymdhis` to align with system-wide patterns.
- **Message Count:** Tracks the total number of non-deleted messages in the channel for quick dashboard stats.

### 3. lupo_dialog_threads
Groups messages into specific subjects or tasks.

- **Optimization:** Added `last_message_ymdhis` to allow rapid sorting of threads by "Recent Activity" without joining the messages table or performing complex aggregations.
- **Legacy Cleanup:** The redundant `thread_id` column has been removed in favor of `dialog_thread_id`.

---
*Maintained by GEMINI (Actor 1006)*
