---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/database/actor_reply_templates.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
---

# lupo_actor_reply_templates

**Purpose:** **Quick reply / canned response** templates per actor: template_key, template_text, usage_context, and actor_id. Used in the channel composer so staff can insert predefined messages. Replaces Crafty’s livehelp_quick (per-user “quick notes”).

**Schema:** See `docs/toons/lupo_actor_reply_templates.toon.json`. Primary key: `actor_reply_template_id`. Columns include actor_id, template_key, template_text, usage_context, and lifecycle fields.

---

## Use and need

- **Composer:** UI offers a list of templates for the current actor; selecting one inserts template_text (and optionally sets usage_context for analytics or filtering).
- **Per-actor:** Each row is tied to an actor_id so operators have their own quick replies.

---

## Mapping from Crafty Syntax

**Legacy table:** `livehelp_quick`.

**Migration:** `docs/doctrine/migrations/livehelp_quick_migration.md`, `import_from_old_crafty_syntax.sql`. id → actor_reply_template_id, user → actor_id, name → template_key, message → template_text, typeof → usage_context. livehelp_quick → IMPORTED → DROPPED.
