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
| `mood_rgb` | CHAR(6) | Hex color code representing the message's emotional tone. |
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
