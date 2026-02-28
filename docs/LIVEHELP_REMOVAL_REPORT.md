# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\LIVEHELP_REMOVAL_REPORT.md"
  file_hash: "ccee024238e69574efe63c2ad1121282af10ae50af3a5e13ab2b09acfdcbd794"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Livehelp Removal — Codebase Report"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "livehelp_removal_reportmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Livehelp Removal — Codebase Report

**Date:** 2026-02-04  
**Scope:** Remove all references to `livehelp_operators` and other `livehelp_*` tables; use `lupo_auth_users`, role tables, and unified session handler only.

---

## Summary

- **`livehelp_operators`:** Removed from all application code. No remaining references.
- **`livehelp_users`:** Replaced with `lupo_sessions` (and `lupo_auth_users` where user identity is needed).
- **`livehelp_departments`:** Replaced with `lupo_departments`.
- **`livehelp_sessions`:** Replaced with `lupo_sessions`.
- **`livehelp_messages`:** Replaced with `lupo_dialog_messages` where used.

All authentication and authorization now use:

- **`lupo_auth_users`** (auth_user_id, email, display_name, password_hash, last_login_ymdhis)
- **`lupo_sessions`** (session_id, actor_id, last_seen_ymdhis, expires_ymdhis, session_data)
- **`lupo_actors`** / **`lupo_actor_channel_roles`** for role-based permissions
- **Unified session handler** and **AuthManager** (no operator-based logic)

---

## Files Changed

### Auth & session (core)

| File | Changes |
|------|--------|
| `app/Http/Controllers/AuthController.php` | Removed Crafty Syntax / operator login path. Login uses `lupo_auth_users` only. Redirects to `/login` or `/dashboard`. |
| `app/auth/AuthManager.php` | `getUserById()` uses `lupo_auth_users` (auth_user_id, email, display_name). Removed `getCraftyOperator()`, `isCraftyOperator()`. Permissions from `lupo_actors` + `lupo_actor_channel_roles`. `getUserMapping()` / `createUserMapping()` no longer depend on operator ID. `logAuthEvent()` passes `crafty_operator_id` as null. |
| `app/middleware/AuthMiddleware.php` | Removed `updateCraftyActivity()` and any `livehelp_users` update. Only updates `lupo_sessions` (last_seen_ymdhis, updated_ymdhis). `logAuthenticationActivity()` passes null for operator. |
| `app/Http/Controllers/Admin/AuthenticationController.php` | All queries use `lupo_auth_users` (no `users` or `livehelp_operators`). Removed `getCraftyOperators()`, `getUnmappedOperators()`. Stats use `total_lupo_users` / `mapped_users` (no crafty operator counts). `getUserMappings()` joins `lupo_auth_users`. `getActiveSessions()` / `getRecentAuthenticationActivity()` join `lupo_auth_users`. `storeMapping()` requires only `lupo_user_id` (no `crafty_operator_id`). `getLupopediaUsers()` from `lupo_auth_users`. |

### Views (admin auth)

| File | Changes |
|------|--------|
| `app/views/admin/authentication/index.blade.php` | Removed “Crafty Operators” stat card; 4th card is “Mapped Users”. Removed “Unmapped Crafty Operators” section. Mappings table no longer has “Crafty Operator” column. `created_at` displayed as YmdHis. Support for array/object from controller. |
| `app/views/admin/authentication/mapping.blade.php` | Removed Crafty Operator dropdown and `crafty_operator_id`. Form only: Lupopedia user, mapping type, notes. Existing mappings table shows “Lupopedia User” and “Type”. Removed `crafty_id` URL param. |

### Legacy Crafty Syntax services

| File | Changes |
|------|--------|
| `app/Services/CraftySyntax/WorldGraphHelper.php` | Department label from `lupo_departments` (name, department_id) instead of `livehelp_departments`. |
| `app/Services/CraftySyntax/LegacyAuthentication.php` | Session cleanup/update use `lupo_sessions` (last_seen_ymdhis, updated_ymdhis). No `livehelp_users`. |
| `app/Services/CraftySyntax/LegacyAdminCommon.php` | Admin user row from `lupo_sessions` (session_id, actor_id AS user_id, last_seen_ymdhis AS lastaction, plus legacy columns as 0). No `livehelp_users`. |
| `app/Services/CraftySyntax/LegacyUserChatFlush.php` | User row from `lupo_sessions` (actor_id AS user_id). No `livehelp_users`. |
| `app/Services/CraftySyntax/LegacyAdminChatFlush.php` | Admin row from `lupo_sessions`. “chattype=flush” update removed (no such column in lupo_sessions). Last message time from `lupo_dialog_messages` (created_ymdhis AS timeof) instead of `livehelp_messages`. |
| `app/Services/CraftySyntax/LegacyIsFlushDetection.php` | Department data from `lupo_departments` (department_id, name); other legacy fields defaulted. No `livehelp_departments`. |
| `app/Services/CraftySyntax/LegacySessionIdentity.php` | New/update session written to `lupo_sessions` (session_id, actor_id, ip_address, user_agent, session_data, last_seen_ymdhis, expires_ymdhis, created_ymdhis, updated_ymdhis). No `livehelp_users`. |
| `app/Services/CraftySyntax/LegacySessionManager.php` | PHP session handler: read/write/destroy/gc use `lupo_sessions` (session_data, last_seen_ymdhis, expires_ymdhis). No `livehelp_sessions` or UNIX_TIMESTAMP. |

### Scripts

| File | Changes |
|------|--------|
| `scripts/migrate_user_mappings.php` | Header updated: operator-based migration deprecated. `getLupopediaUsers()` from `lupo_auth_users`. `getCraftyOperators()` returns `[]`. Inserts use YmdHis for created_at/updated_at. `updateUsersTable()` is a no-op (no crafty_operator_id on users). |

---

## Remaining References (comments / docs only)

- **Comments** in several Legacy* files and in `live.php`, `livehelp_js.php` that mention “livehelp_users → lupo_users” or similar: kept as historical notes; no runtime use of livehelp_*.
- **Docs / SQL / backups:** `docs/`, `database/install/`, `database/migrations/`, `migrations/`, `complete_schema.txt`, `backups/` may still mention `livehelp_*` in comments or schema docs; no application code paths use those tables.

---

## Confirmation

- **No references to `livehelp_operators`** remain in application code (only in comments/docs and deprecated script header).
- **No references to any other `livehelp_*` tables** remain in active code paths; legacy Crafty paths now use `lupo_sessions`, `lupo_departments`, `lupo_dialog_messages`, and `lupo_auth_users` where appropriate.
- **No legacy operator logic** remains: auth is `lupo_auth_users` + roles; session is `lupo_sessions`; no operator IDs or operator-based permissions.
