---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/database/crafty_syntax_auto_invite.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/database/crafty_syntax_auto_invite.md
---

# lupo_crafty_syntax_auto_invite

**Purpose:** **Crafty compatibility table** for auto-invite rules: when to show an invite (e.g. after N seconds, on a page URL), department, message text, and options (offline, mobile, social pane, etc.). Preserves legacy livehelp_autoinvite behavior during and after migration. Column names may retain legacy-style suffixes (e.g. operator_user_id) for compatibility.

**Schema:** See `docs/toons/lupo_crafty_syntax_auto_invite.toon.json`. Primary key: `crafty_syntax_auto_invite_id`. All columns must match TOON.

---

## Use and need

- **Invite logic:** Layer or chat UI reads rules by department/page/visits and shows the configured message after trigger_seconds or other conditions.
- **Migration compatibility:** Import preserves idnum → crafty_syntax_auto_invite_id, isactive → is_active, department → department_id, page → page_url, referer → referrer_url, typeof → invite_type, etc. See migration doc.

---

## Mapping from Crafty Syntax

**Legacy table:** `livehelp_autoinvite`.

**Migration:** `docs/doctrine/migrations/livehelp_autoinvite_migration.md`, `import_from_old_crafty_syntax.sql`. Full field mapping in migration file. Legacy table is CONVERTed to utf8mb4, then data imported; livehelp_autoinvite → IMPORTED → DROPPED.
