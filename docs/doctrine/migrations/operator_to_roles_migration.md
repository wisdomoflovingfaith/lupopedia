---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/migrations/operator_to_roles_migration.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/migrations/operator_to_roles_migration.md
---

# Migration Note: lupo_operators Removed — 3-Level Role System

**Status:** Completed (lupo_operators and lupo_operators_* tables removed.)  
**Replacement:** 3-level permission model using lupo_actor_channel_roles, lupo_department_roles, and system (department_id = 0).

---

## 1. Summary

Lupopedia originally had **lupo_operators** (and related operator_* tables) for "operator" identity and permissions. These were removed. Permissions are now entirely **role-based** across three layers:

1. **Channel roles** — **lupo_actor_channel_roles**  
   - One row per (actor_id, channel_id, role_key).  
   - **role_key** values: `captain`, `administrator`, `monitor`.  
   - Determines who can manage a channel, view it, or monitor it.

2. **Department roles** — **lupo_department_roles**  
   - Department-scoped roles (e.g. department admin, member).  
   - Applied when the actor is acting in a department context.

3. **System roles** — **department_id = 0**  
   - Global/system admin.  
   - Reserved; not user-selectable. Used for system-wide administration.

**Resolution order:** channel → department → system. Code checks channel roles first, then department roles, then system.

---

## 2. What Replaced lupo_operators

| Old (removed)              | New (current)                                                                 |
|----------------------------|-------------------------------------------------------------------------------|
| lupo_operators             | No table. Identity in **lupo_actors**; credentials in **lupo_auth_users**.  |
| Operator “is staff” flag   | **lupo_actor_channel_roles** (role_key = captain | administrator | monitor).   |
| Operator–channel assignment| **lupo_actor_channel_roles** (actor_id, channel_id, role_key).               |
| Operator–department       | **lupo_actor_departments** (actor_id, department_id); department roles in **lupo_department_roles**. |
| Crafty isadmin = 'Y'       | Install wizard inserts **lupo_actor_channel_roles** (actor_id, channel_id=1, role_key='captain'). |

---

## 3. Import and Wizard Behavior

- **import_from_old_crafty_syntax.sql** does **not** insert into lupo_operators (table does not exist). It imports livehelp_users → lupo_auth_users and (operators only) → lupo_actors; livehelp_operator_departments → lupo_actor_departments.
- **Install wizard** (install_wizard_classes.php): After import, **createOperatorChannels** creates a personal channel per imported Crafty operator and inserts **lupo_actor_channel_roles** with role_key = 'captain'. For each livehelp_users row with isadmin = 'Y', the wizard inserts **lupo_actor_channel_roles** (actor_id, channel_id=1, role_key='captain') so they have admin channel access.
- **lupo_channel_roles** (role_type) still exists in schema; some code paths use **lupo_actor_channel_roles** (role_key) for permission checks. See docs/ACTOR_CHANNEL_ROLES_VS_CHANNEL_ROLES_ANALYSIS.md and database/migrations_legacy/migration_operator_to_actor_channel_roles.sql for existing-DB migration.

---

## 4. References

- **docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md** — What was changed when lupo_operators was removed.
- **docs/doctrine/database/actor_channel_roles.md** — Use of lupo_actor_channel_roles and role keys.
- **docs/doctrine/migrations/livehelp_users_migration.md** — livehelp_users → lupo_auth_users / lupo_actors; notes that operator permissions use the 3-level role system.
- **database/migrations_legacy/migration_operator_to_actor_channel_roles.sql** — One-time migration from lupo_channel_roles to lupo_actor_channel_roles for existing installs (not run by wizard).
