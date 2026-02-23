---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/database/auth_users.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/database/auth_users.md
---

# lupo_auth_users

**Purpose:** Stores **authentication and credentials** for human users (and optionally visitors): username, display name, email, password hash, auth provider, provider ID, last login. This is the **identity/credential** layer; the unified **actor** layer is `lupo_actors`.

**Schema:** See `docs/toons/lupo_auth_users.toon.json`. Primary key: `auth_user_id`. No AUTO_INCREMENT for registry-backed usage; IDs are explicit (e.g. imported from Crafty `user_id`).

---

## Use and need

- **Login:** Auth layer (e.g. AuthGuard, login forms) looks up by `username` or `auth_provider` + `provider_id`, verifies `password_hash`, updates `last_login_ymdhis`.
- **Identity link:** For human users, one row in `lupo_auth_users` corresponds to one row in `lupo_actors` with `actor_id = auth_user_id` and `actor_source_type = 'lupo_auth_users'`. Session and permission code resolve actor from session, then may join to auth_users for display name / email.
- **Lifecycle:** `is_active`, `is_deleted`, `deleted_ymdhis` support soft-delete. Timestamps are BIGINT UTC YmdHis (`created_ymdhis`, `updated_ymdhis`).

---

## Mapping from Crafty Syntax

**Legacy table:** `livehelp_users` (Crafty stored both operators and visitors in one table).

**Migration:** `docs/doctrine/migrations/livehelp_users_migration.md` and `database/migrations/import_from_old_crafty_syntax.sql`.

- **First INSERT:** Operators only (`WHERE isoperator = 'Y'`). Fields: `username`, `displayname` → `display_name`, `email`, `password` → `password_hash`, `auth_provider`, `provider_id`, `lastaction` → `last_login_ymdhis` (UNIX → YmdHis). `auth_user_id` = `user_id` (preserve Crafty ID).
- **Second INSERT:** Remaining users (visitors) with `NOT EXISTS` on username so operators win. Visitors get no password, no provider, no email.
- **Not imported:** Session state, IP, department, isonline, status (handled by lupo_sessions, lupo_actor_properties, roles).

**Result:** livehelp_users → **IMPORTED** → DROPPED. Only lupo_auth_users (and lupo_actors for operators) hold identity.
