# Old Operator Tables Removal + Role Table Verification Report

**Date:** 2026-02-10  
**Scope:** Remove deprecated operator tables from schema references (install SQL, dev migrations, REQUIRED_TABLES). Verify role table usage. No PHP changes, no DROP, no TOON regeneration.

---

## A. Deprecated tables (targets)

- lupo_operator_chat_assignments  
- lupo_operator_escalation_rules  
- lupo_operator_escalations  
- lupo_operator_kapu_log  
- lupo_operator_sessions  
- lupo_operator_skills  
- lupo_operator_status  
- lupo_operators  

---

## Files modified

| File | Change |
|------|--------|
| docs/REQUIRED_TABLES_4.2.1.md | Removed the 8 list entries for the deprecated operator tables above. |

**Install SQL:** `database/migrations/install_new_lupopedia.sql` was searched for these table names. **No CREATE TABLE blocks** for any of the eight tables exist there (only unrelated column names such as `crafty_operator_id`, `anubis_operator` appear). **No changes made.**

**Dev alignment migrations:** `dev_20260204_fix_schema_alignment.sql`, `dev_20260204_fix_schema_alignment_summary.txt`, and `dev_20260205_doctrine_alignment_phase2.sql` were searched for `lupo_operator*`. **No ALTER TABLE or other references** to these eight tables exist. **No changes made.**

---

## Lines removed

| File | Lines removed |
|------|----------------|
| docs/REQUIRED_TABLES_4.2.1.md | 8 (one list entry per deprecated table) |
| **Total** | **8** |

---

## Confirmations

- **Deprecated operator tables removed from REQUIRED_TABLES:** All eight entries were removed from `docs/REQUIRED_TABLES_4.2.1.md`. They were not present in install SQL or dev alignment migrations, so nothing was removed there.
- **No PHP or runtime code touched:** No `.php` files were modified. No services, helpers, or modules were changed.

---

## Remaining references to old operator tables (not modified)

These references were **not** changed per your constraints (cleanup limited to install SQL, dev migrations, and REQUIRED_TABLES):

| File | Line(s) | Reference |
|------|---------|-----------|
| database/migrations/import_from_old_crafty_syntax.sql | 1113 | Comment: "Then lupo_operators, then fix lupo_actor_departments.actor_id" |
| database/migrations/2026_01_30_kapu_protocol.sql | 7–9, 22–24 | CREATE TABLE IF NOT EXISTS lupo_operator_kapu_log; CREATE TABLE IF NOT EXISTS lupo_operator_escalations |
| database/migrations/2026_01_30_demo_operators.sql | 24–25, 31, 37–40 | INSERT INTO lupo_operators; INSERT INTO lupo_operator_status; SELECT FROM lupo_operators |

If you later want these migration/demo scripts updated or removed, that can be done in a separate change.

---

## B. Verification: role table usage

**lupo_channel_roles** is the table used for all permission/role logic:

- **app/auth/AuthRoleResolver.php** — isAdmin(), hasAnyChannelRole() use `lupo_channel_roles` (channel_id = 1, role_type captain/administrator; any channel role for is_operator).
- **app/auth/AuthManager.php** — getPermissions() reads from `lupo_channel_roles` (channel-scoped roles only).
- **lupo-includes/modules/module-loader.php** — channel access and “my channels” list use `channel_roles`.
- **lupo-includes/modules/channels/channels-controller.php** — role checks, channel members, captain/administrator/monitor management, log permission, my-channels list: all use `channel_roles`.
- **lupo-includes/modules/channels/operator-accept-visitor-api.php** — operator channel access check uses `channel_roles`.
- **lupo-includes/modules/crafty_syntax/choosedepartment.php, livehelp-js.php, visitor-image.php** — staffed-department checks use `channel_roles` + lupo_channels.

**lupo_actor_channel_roles** is used only for protocol/awareness logic:

- **app/Services/TriggerReplacements/EnforceProtocolCompletionService.php** — reads `protocol_completion_status` from `lupo_actor_channel_roles`.
- **lupo-includes/classes/AgentAwarenessLayer.php** — LEFT JOIN and INSERT into `lupo_actor_channel_roles` for awareness snapshots.

**PHP search for old operator tables:** No PHP file references `lupo_operator_chat_assignments`, `lupo_operator_escalation_rules`, `lupo_operator_escalations`, `lupo_operator_kapu_log`, `lupo_operator_sessions`, `lupo_operator_skills`, `lupo_operator_status`, or `lupo_operators` (grep over `*.php`: no matches).

**Confirmation:** The system uses the _roles tables exclusively for permission and channel role behavior: **lupo_channel_roles** for all permission/role logic; **lupo_actor_channel_roles** only for protocol/awareness. No PHP references the old operator tables.
