---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/database/dialog_threads.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
---

# lupo_dialog_threads

**Purpose:** **Conversation threads** within a channel. One row per thread; holds thread identity, channel_id, optional bg_color or other UI metadata, and lifecycle timestamps. Messages in the thread are in **lupo_dialog_messages** (dialog_thread_id). Replaces legacy “channel” as the unit of a single conversation; thread-level metadata (e.g. colors) replaces what Crafty stored in livehelp_operator_channels.

**Schema:** See `docs/toons/lupo_dialog_threads.toon.json`. Primary key: `dialog_thread_id`. Key columns include channel_id, timestamps, and any metadata/bg_color columns defined in the TOON.

---

## Use and need

- **UI tabs:** One tab per thread in the channel UI; selecting a tab sets the composer target. Order by created_ymdhis or equivalent.
- **Colors:** Legacy channelcolor from operator_channels maps to thread-level metadata (e.g. bg_color) so each thread can have its own color.
- **Messages:** All messages for a thread share the same dialog_thread_id in lupo_dialog_messages.

---

## Mapping from Crafty Syntax

**Legacy:** `livehelp_transcripts` (one transcript = one conversation).

**Migration:** `docs/doctrine/migrations/livehelp_transcripts_migration.md`, `import_from_old_crafty_syntax.sql`. One thread per transcript: recno → dialog_thread_id, starttime/endtime → created_ymdhis/updated_ymdhis. Full transcript text is stored in **lupo_dialog_messages** (one message per thread containing the transcript content). livehelp_transcripts → IMPORTED into lupo_dialog_threads + lupo_dialog_messages → DROPPED.
