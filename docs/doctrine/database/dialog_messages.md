---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/database/dialog_messages.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
---

# lupo_dialog_messages

**Purpose:** **Messages** belonging to a dialog thread. Each row has dialog_thread_id, channel_id, from_actor_id, to_actor_id, message_text, created_ymdhis (and any other columns in the TOON). Order by created_ymdhis ASC for display. Ephemeral livehelp_messages are not imported; durable content comes from livehelp_transcripts.

**Schema:** See `docs/toons/lupo_dialog_messages.toon.json`. Primary key: `dialog_message_id`. Foreign references are application-managed (no FK); actor and channel identity are in lupo_actors and lupo_channels.

---

## Use and need

- **Channel UI:** Messages for a channel are read by channel_id (and optionally dialog_thread_id). Composer sends new messages with from_actor_id = current actor, to_actor_id and thread/channel as context.
- **Transcript import:** For legacy transcripts, one message per thread is created containing the full transcript text; timestamps from transcript start/end.
- **Ordering:** Messages ordered by created_ymdhis ASC; threads can be interleaved by sort order for legacy compatibility.

---

## Mapping from Crafty Syntax

**Legacy:** `livehelp_transcripts` (content) and `livehelp_messages` (ephemeral buffer).

**Migration:** livehelp_messages is **DROPPED** (no import). livehelp_transcripts → one **lupo_dialog_message** per transcript row, with transcript text in message_text and recno/starttime/endtime mapping to dialog_message_id and timestamps. See `livehelp_transcripts_migration.md`, `livehelp_messages_migration.md`, and `MIGRATION_MAPPING_REFERENCE.md`.
