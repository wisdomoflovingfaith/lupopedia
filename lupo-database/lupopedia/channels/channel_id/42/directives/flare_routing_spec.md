# Directive: Flare Routing Specification (v1.0)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "directive"
  file_path_from_root: "lupo-channels/42/directives/flare_routing_spec.md"
  system_version: "4.0.55"
  channel_id: 42
  actor_id: 1006
  last_updated_utc: "20260302"
  artifact_type: "standard"
  purpose: "Define the flare.routing metadata standard for cross-agent communication"
  traits: ["standard", "routing", "v4.0.55"]

flare.routing:
  to: ["agents", "captain"]
  from: 1006
  delegation_chain: [1, 10000, 1006]
  channel_id: 42
  thread_id: "DEVELOPMENT_CYCLE_4_0_55"
  routing_path: ["lupo-channels/42/directives/"]

flare.lists:
  file.dialog: "lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_55/thread-001.csv"
  file.history: "lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_55/thread-001_history.csv"
  file.actors: "lupo-channels/42/actors/thread-1.csv"

wolfie.headers: {
  file_path_from_root: "lupo-channels/42/directives/flare_routing_spec.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302044500,
  updated_ymdhis: 20260302044500,
  message_type: "directive",
  visibility: "public",
  priority: "high"
}
---

## Overview
The `flare.routing` metadata object is a critical component of the Decentralized Communication System. It enables precise tracking of message origin, intent, delivery, and authority across the multi-agent ecosystem.

## Header Structure

The `flare.routing` object MUST be included in the YAML frontmatter of all Markdown artifacts intended for multi-agent consumption.

```yaml
flare.routing:
  to: [recipient_list]
  from: sender_id
  forwarded_from: original_sender_id (optional)
  delegation_chain: [authority_path]
  channel_id: target_channel_id
  thread_id: target_thread_id (optional)
  read_by: [acknowledgment_list]
  routing_path: [physical_path_history]
```

## Field Definitions

### `to` (Required)
- **Type**: Array of Strings/Integers
- **Description**: Target audience for the message. Can contain specific `actor_id` values, agent slugs, or broadcast groups (e.g., `"all"`, `"agents"`, `"admins"`).
- **Example**: `to: [1001, "captain", "audit-team"]`

### `from` (Required)
- **Type**: Integer/String
- **Description**: The `actor_id` or slug of the immediate sender.
- **Example**: `from: 1006`

### `forwarded_from` (Optional)
- **Type**: Integer/String
- **Description**: If a message is being rebroadcast, this field tracks the original originating actor.
- **Example**: `forwarded_from: 1004`

### `delegation_chain` (Required)
- **Type**: Array of Integers
- **Description**: The sequence of authority that authorized this communication.
- **Format**: `[Root Authority, Higher Authority, Executing Agent]`
- **Standard**: `[1, 10000, 1006]` (WOLFIE -> CAPTAIN -> GEMINI)

### `channel_id` (Required)
- **Type**: Integer
- **Description**: The primary channel ID associated with this communication. Ensures artifacts are correctly routed to the proper channel log.
- **Example**: `channel_id: 42`

### `thread_id` (Optional)
- **Type**: String/Integer
- **Description**: The identifier of the discussion thread. Required for messages that are part of a persistent conversation or development cycle.
- **Example**: `thread_id: "DEVELOPMENT_CYCLE_4_0_55"`

### `read_by` (Optional)
- **Type**: Array of Integers/Strings
- **Description**: Dynamic list of actors who have processed or "seen" this artifact. This field is updated during sync operations.
- **Example**: `read_by: [1006, 1002, 10000]`

### `routing_path` (Required)
- **Type**: Array of Strings
- **Description**: The logical or physical directories traversed by this message.
- **Example**: `routing_path: ["lupo-channels/0/", "lupo-channels/42/tasks/"]`

## Flare Lists (`flare.lists`)

The `flare.lists` object tracks external CSV resources associated with the artifact's discussion and modification history.

```yaml
flare.lists:
  file.dialog: path/to/dialog.csv
  file.history: path/to/history.csv
  file.actors: path/to/actors_list.csv
```

### `file.dialog` (Optional)
- **Type**: String (Path)
- **Description**: Path to a CSV file that contains a complete transcript or log of all dialog/discussion related to this file.
- **Example**: `file.dialog: "lupo-channels/42/threads/thread-001.csv"`

### `file.history` (Optional)
- **Type**: String (Path)
- **Description**: Path to a CSV file containing all changes, commits, or version history specific to this file.
- **Example**: `file.history: "lupo-channels/42/threads/thread-001_history.csv"`

### `file.actors` (Optional)
- **Type**: String (Path to CSV)
- **Description**: Path to a CSV file containing actor information, profiles, or permissions related to this file.
- **Example**: `file.actors: "lupo-channels/42/actors/thread-1.csv"`

## Database Representation

In the consolidated `lupo_unified_log` or future `lupo_broadcasts` table, these fields SHOULD be stored in a JSON column named `routing_metadata`.

| Field | DB Type | Notes |
|-------|---------|-------|
| `routing_metadata` | JSON/LONGTEXT | Contains the full flare.routing object |
| `origin_actor_id` | BIGINT | Indexed copy of 'from' for speed |
| `target_actor_id` | BIGINT/VARCHAR | Principal recipient for filtering |

## Implementation Notes
1. **PHP 5.3 Compatibility**: All ingestion scripts must use `json_decode()` to parse these headers into associative arrays before processing.
2. **Validation**: Artifacts lacking a valid `delegation_chain` should be flagged as "Unauthenticated" or "Shadow" files (Ref: TASK-013).
3. **Immutability**: Once written, the `from` and `delegation_chain` fields should remain immutable; only `read_by` and `routing_path` (during forwarding) are expected to change.
