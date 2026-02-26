---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/database/sessions.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/database/sessions.md
---

# lupo_sessions

**Purpose:** **Session storage** for authenticated and anonymous users: session_id, actor_id (nullable or sentinel for anonymous), payload (session data), optional system_context, and expiry. Anonymous users exist only here—they do not have rows in lupo_actors. Authenticated users have both a session and an actor row. Replaces Crafty’s livehelp_sessions with a deterministic, actor-aware model. Single session table for the app; no separate “sessions” in current install.

**Schema:** See `docs/toons/lupo_sessions.toon.json`. Primary key: `session_id`. Timestamps BIGINT UTC (e.g. expires_ymdhis). Session data is stored in the payload/lifecycle columns as defined in the TOON.

---

## Use and need

- **Request handling:** Session middleware or auth layer loads session by session_id (e.g. cookie), resolves actor_id, and attaches actor to the request. All session reads/writes go through the Session class (App\Auth\Session) using PDO_DB.
- **Actor binding:** Each session row is tied to an actor_id so permissions and UI can use “current actor” without re-looking up from a separate user table.
- **Expiry:** expires_ymdhis (or equivalent in schema) used for cleanup and invalidation. No DB-side DEFAULT CURRENT_TIMESTAMP; set in application code per doctrine.

---

## Mapping from Crafty Syntax

**Legacy table:** `livehelp_sessions`.

**Migration:** livehelp_sessions is **DROPPED**; there is **no import** of session data. Lupopedia uses lupo_sessions only; structure and lifecycle are new. See `docs/doctrine/migrations/livehelp_sessions_migration.md`. Session and operator state from Crafty are not migrated; users log in again after upgrade and get new sessions.
