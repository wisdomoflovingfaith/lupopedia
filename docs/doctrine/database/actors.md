---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/database/actors.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/database/actors.md
---

# lupo_actors

**Purpose:** **Unified identity layer** for authenticated humans, AI agents, services, and system users only. Anonymous users do not have rows in lupo_actors; they exist in **lupo_sessions** only. Every authenticated or system entity that can send messages, hold roles, or be referenced in dialogs has one row in `lupo_actors`. Identity is separated from credentials (lupo_auth_users) and from permissions (3-level role system). No dedicated ID range for anonymous users.

**Schema:** See `docs/toons/lupo_actors.toon.json`. Primary key: `actor_id`. Columns include `actor_type` (e.g. agent, service, human, system), `slug`, `name`, `actor_source_id`, `actor_source_type`, `metadata`, lifecycle fields.

---

## Use and need

- **Single identity table:** Channels, threads, messages, roles, and presence refer to `actor_id`. No separate “operator” or “visitor” table; type and source distinguish them.
- **Human actors:** For imported Crafty users, `actor_id = auth_user_id` and `actor_source_type = 'lupo_auth_users'`. Only operators (isoperator = 'Y') get a lupo_actors row at import; visitors may be created on demand or via other flows.
- **Agents/services:** System and AI agents have `actor_type` = 'agent' or 'service', `actor_source_type` = 'system' or registry; reserved IDs from REGISTRY.
- **Anonymous:** Anonymous visitors do not get rows in lupo_actors. They exist only in lupo_sessions. No anonymous actor range.

---

## Mapping from Crafty Syntax

**Legacy:** `livehelp_users` (operators only get lupo_actors rows; visitors are in lupo_auth_users only and do not get lupo_actors at import). `livehelp_identity_monthly` is not imported into lupo_actors.

**Migration:** See `livehelp_users_migration.md`, `livehelp_identity_migration.md`, `import_from_old_crafty_syntax.sql`.

- **Operators:** Import creates lupo_actors only for rows with `isoperator = 'Y'`. `actor_id = user_id`, `actor_source_id = auth_user_id`, `actor_source_type = 'lupo_auth_users'`. Name/slug from username/displayname.
- **Anonymous:** Not in lupo_actors. Anonymous visitors exist in lupo_sessions only. livehelp_identity_monthly / livehelp_identity_daily are DROPPED with no import into actors.
- **Permissions:** There is no lupo_operators. Staff permissions use **lupo_actor_channel_roles** (captain, administrator, monitor) and **lupo_department_roles**; see `operator_to_roles_migration.md` and `actor_channel_roles.md`.
