> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

﻿# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/database/lupopedia/tables/migrations/operator_to_roles_migration.md"
  file_hash: "36a63c712c402a0ce26665e797f5169fa29a51a821711295cf36f275fc63e55c"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  namespace: "legacy"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\operator_to_roles_migration.md"
  file_hash: "d463aa11db2900c368bf38ef82cf6b9e129ecf560159b98de13e766bafaa9dba"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for operator_to_roles_migration.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "operator_to_roles_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
lupopedia.headers: explicit architecture with structured clarity for every file.
  tags: ["legacy-reference"]
file_path_from_root: docs/database/lupopedia/tables/operator_to_roles_migration.md
  file_hash: "fa13f3f2dbec7ee5ca804f364bdb6cf69430a37a78158995a6d232bdcbf00e72"
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["legacy-reference", "lost", "orphan", "doctrine"]
mood_vector: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/migrations/operator_to_roles_migration.md
---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: lupo_operators Removed â€” 3-Level Role System

**Status:** Completed (lupo_operators and lupo_operators_* tables removed.)  
**Replacement:** 3-level permission model using lupo_actor_channel_roles, lupo_department_roles, and system (department_id = 0).

---

## 1. Summary

Lupopedia originally had **lupo_operators** (and related operator_* tables) for "operator" identity and permissions. These were removed. Permissions are now entirely **role-based** across three layers:

1. **Channel roles** â€” **lupo_actor_channel_roles**  
   - One row per (actor_id, channel_id, role_key).  
   - **role_key** values: `captain`, `administrator`, `monitor`.  
   - Determines who can manage a channel, view it, or monitor it.

2. **Department roles** â€” **lupo_department_roles**  
   - Department-scoped roles (e.g. department admin, member).  
   - Applied when the actor is acting in a department context.

3. **System roles** â€” **department_id = 0**  
   - Global/system admin.  
   - Reserved; not user-selectable. Used for system-wide administration.

**Resolution order:** channel â†’ department â†’ system. Code checks channel roles first, then department roles, then system.

---

## 2. What Replaced lupo_operators

| Old (removed)              | New (current)                                                                 |
|----------------------------|-------------------------------------------------------------------------------|
| lupo_operators             | No table. Identity in **lupo_actors**; credentials in **lupo_auth_users**.  |
| Operator â€œis staffâ€ flag   | **lupo_actor_channel_roles** (role_key = captain | administrator | monitor).   |
| Operatorâ€“channel assignment| **lupo_actor_channel_roles** (actor_id, channel_id, role_key).               |
| Operatorâ€“department       | **lupo_actor_departments** (actor_id, department_id); department roles in **lupo_department_roles**. |
| Crafty isadmin = 'Y'       | Install wizard inserts **lupo_actor_channel_roles** (actor_id, channel_id=1, role_key='captain'). |

---

## 3. Import and Wizard Behavior

- **import_from_old_crafty_syntax.sql** does **not** insert into lupo_operators (table does not exist). It imports livehelp_users â†’ lupo_auth_users and (operators only) â†’ lupo_actors; livehelp_operator_departments â†’ lupo_actor_departments.
- **Install wizard** (install_wizard_classes.php): After import, **createOperatorChannels** creates a personal channel per imported Crafty operator and inserts **lupo_actor_channel_roles** with role_key = 'captain'. For each livehelp_users row with isadmin = 'Y', the wizard inserts **lupo_actor_channel_roles** (actor_id, channel_id=1, role_key='captain') so they have admin channel access.
- **lupo_channel_roles** (role_type) still exists in schema; some code paths use **lupo_actor_channel_roles** (role_key) for permission checks. See docs/ACTOR_CHANNEL_ROLES_VS_CHANNEL_ROLES_ANALYSIS.md and database/migrations_legacy/migration_operator_to_actor_channel_roles.sql for existing-DB migration.

---

## 4. References

- **docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md** â€” What was changed when lupo_operators was removed.
- **docs/database/lupopedia/tables/active/lupo_actor_channel_roles.md** â€” Use of lupo_actor_channel_roles and role keys.
- **docs/doctrine/migrations/livehelp_users_migration.md** â€” livehelp_users â†’ lupo_auth_users / lupo_actors; notes that operator permissions use the 3-level role system.
- **database/migrations_legacy/migration_operator_to_actor_channel_roles.sql** â€” One-time migration from lupo_channel_roles to lupo_actor_channel_roles for existing installs (not run by wizard).

