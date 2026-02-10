# Analysis: lupo_actor_channel_roles vs lupo_channel_roles

**Purpose:** Determine which table is actually used by the codebase and which is the duplicate.  
**Scope:** Full repository search; analysis only (no code changes).

---

## 1. Table definitions (from install_new_lupopedia.sql)

| Table | PK | Key columns | Purpose (from schema) |
|-------|-----|-------------|------------------------|
| **lupo_channel_roles** | channel_role_id | channel_id, actor_id, **role_type** (varchar), metadata_json, created_ymdhis, updated_ymdhis, is_deleted | Channel-scoped role assignment (captain, administrator, monitor) |
| **lupo_actor_channel_roles** | actor_channel_role_id | actor_id, channel_id, **role_key** (varchar), handshake_metadata_json, awareness_snapshot_json, protocol_completion_status, protocol_version, join_sequence_step, … | Actor–channel roles plus protocol/awareness (AAL, RSHAP, CJP) |

They are **not** the same concept: different PK names, different role column (`role_type` vs `role_key`), and `lupo_actor_channel_roles` has protocol/awareness fields that `lupo_channel_roles` does not.

---

## 2. All references (table)

### lupo_channel_roles

| File path | Line(s) | Snippet / usage | R/W | Active / dead |
|-----------|---------|------------------|-----|----------------|
| app/auth/AuthRoleResolver.php | 43, 44–47 | `$cr = $this->db->quoteIdentifier($prefix . 'channel_roles');` then SELECT 1 … role_type IN ('captain','administrator') | Read | Active |
| app/auth/AuthRoleResolver.php | 89, 90–92 | Same table; SELECT 1 for hasAnyChannelRole | Read | Active |
| app/auth/AuthManager.php | 97, 99–101 | quoteIdentifier + SELECT role_type FROM … channel_roles for permissions | Read | Active |
| lupo-includes/modules/channels/channels-controller.php | 101 | SELECT 1 FROM {$table_prefix}channel_roles WHERE channel_id, actor_id, is_deleted | Read | Active |
| lupo-includes/modules/channels/channels-controller.php | 164 | SELECT r.channel_role_id, r.actor_id, r.channel_id, r.role_type … FROM channel_roles r LEFT JOIN actors | Read | Active |
| lupo-includes/modules/channels/channels-controller.php | 416 | SELECT channel_role_id, role_type FROM channel_roles | Read | Active |
| lupo-includes/modules/channels/channels-controller.php | 523 | Same (channel_role_id, role_type) | Read | Active |
| lupo-includes/modules/channels/channels-controller.php | 620 | SELECT channel_id, role_type FROM channel_roles WHERE actor_id (my-channels list) | Read | Active |
| lupo-includes/modules/channels/channels-controller.php | 711 | SELECT 1 FROM channel_roles (role check) | Read | Active |
| lupo-includes/modules/channels/channels-controller.php | 727 | SELECT channel_role_id, channel_id, actor_id, role_type FROM channel_roles | Read | Active |
| lupo-includes/modules/channels/channels-controller.php | 830 | SELECT 1 FROM channel_roles | Read | Active |
| lupo-includes/modules/channels/channels-controller.php | 866, 870, 874, 877 | UPDATE / SELECT / UPDATE / INSERT channel_roles (captain) | Write | Active |
| lupo-includes/modules/channels/channels-controller.php | 883, 890, 895, 899 | UPDATE / SELECT / UPDATE / INSERT channel_roles (administrator) | Write | Active |
| lupo-includes/modules/channels/channels-controller.php | 905, 911, 915, 918 | UPDATE / SELECT / UPDATE / INSERT channel_roles (monitor) | Write | Active |
| lupo-includes/modules/channels/operator-accept-visitor-api.php | 66 | SELECT 1 FROM {$table_prefix}channel_roles WHERE channel_id, actor_id, is_deleted | Read | Active |
| lupo-includes/modules/module-loader.php | 284 | SELECT 1 FROM {$table_prefix}channel_roles WHERE channel_id, actor_id | Read | Active |
| lupo-includes/modules/module-loader.php | 312–314 | SELECT r.channel_id, r.role_type, c.channel_name FROM channel_roles r INNER JOIN channels c | Read | Active |
| lupo-includes/modules/crafty_syntax/choosedepartment.php | 37–39 | SELECT c.department_id FROM channel_roles r INNER JOIN channels c | Read | Active |
| lupo-includes/modules/crafty_syntax/livehelp-js.php | 101–108 | SELECT 1 FROM channel_roles r … / FROM channel_roles WHERE is_deleted = 0 | Read | Active |
| lupo-includes/modules/crafty_syntax/visitor-image.php | 96–103 | Same pattern (channel_roles for staffed departments) | Read | Active |
| database/install/seed_admin_captain.sql | 137, 140, 147 | MAX(channel_role_id), DELETE FROM, INSERT INTO lupo_channel_roles | Read/Write | Active (seed) |
| database/migrations/install_new_lupopedia.sql | 1345–1360 | CREATE TABLE lupo_channel_roles + indexes | Schema | Active |
| database/migrations/dev_20260204_fix_schema_alignment.sql | 692–699 | ALTER TABLE lupo_channel_roles MODIFY COLUMN … | Migration | Active |
| database/migrations/dev_20260204_fix_schema_alignment_summary.txt | 693–700 | Summary of lupo_channel_roles columns | Doc | Active |
| migrations/channel_roles_escalations_toon_alignment.sql | 4, 15–19 | CREATE TABLE IF NOT EXISTS lupo_channel_roles | Schema | Active |
| docs/HELPER_TO_CLASS_MAPPING_ANALYSIS.md | 5, 18, 23 | Text: role model channel roles only (lupo_channel_roles) | Doc | Reference |
| docs/AUTH_REFACTOR_REPORT.md | 12, 77 | Text: AuthRoleResolver uses lupo_channel_roles | Doc | Reference |
| docs/doctrine/migrations/generated/README.md | 10 | Text: use lupo_channel_roles | Doc | Reference |
| docs/doctrine/migrations/generated/drop_lupo_actor_roles.sql | 2 | Comment: only role table is lupo_channel_roles | Doc | Reference |
| docs/channels/developer/dev/AUTH_*.md | various | Admin check from lupo_channel_roles | Doc | Reference |
| docs/channels/doctrine/CHANNEL_GOVERNANCE_LOG_TABLES.md | 23 | Section ## lupo_channel_roles | Doc | Reference |

### lupo_actor_channel_roles

| File path | Line(s) | Snippet / usage | R/W | Active / dead |
|-----------|---------|------------------|-----|----------------|
| app/Services/TriggerReplacements/EnforceProtocolCompletionService.php | 47–49 | `SELECT protocol_completion_status FROM lupo_actor_channel_roles WHERE actor_id, channel_id` (hardcoded table name) | Read | Active (protocol enforcement) |
| lupo-includes/classes/AgentAwarenessLayer.php | 420 | `LEFT JOIN lupo_actor_channel_roles acr ON …` (hardcoded) | Read | Active (but see note below) |
| lupo-includes/classes/AgentAwarenessLayer.php | 437–442 | `INSERT INTO lupo_actor_channel_roles (actor_id, channel_id, role, metadata_json, …)` — uses columns **role** and **metadata_json**; install schema has **role_key**, **awareness_snapshot_json** / **handshake_metadata_json** | Write | Likely broken / legacy (column mismatch) |
| database/migrations/install_new_lupopedia.sql | 99–124 | CREATE TABLE lupo_actor_channel_roles + indexes | Schema | Active |
| database/migrations/dev_20260204_fix_schema_alignment.sql | 27–41 | ALTER TABLE lupo_actor_channel_roles MODIFY … | Migration | Active |
| database/migrations/dev_20260204_fix_schema_alignment_summary.txt | 28–42 | Summary of lupo_actor_channel_roles columns | Doc | Active |
| migrations/structural_alignment_mysql_migration.sql | 131 | ('lupo_actor_channel_roles', 1) | Migration list | Active |
| docs/REQUIRED_TABLES_4.2.1.md | 74 | List entry lupo_actor_channel_roles | Doc | Reference |
| docs/LIVEHELP_REMOVAL_REPORT.md | 20, 32 | Text: lupo_actor_channel_roles for role-based permissions | Doc | Reference |
| docs/channels/… (multiple) | various | Doctrine, migrations 4.0.70–4.0.73, CHANNEL_JOIN_PROTOCOL, AGENT_AWARENESS_DOCTRINE, dialogs, changelog dialogs | Doc / dialog | Reference / design |
| docs/toons/lupo_actor_channel_roles.toon.json | — | TOON file | Schema | Canonical |
| DIRECTORY_TREE.md | 561–562, 1412, etc. | File listing | Doc | Reference |

---

## 3. Which table is actually used (summary)

| Consumer | lupo_channel_roles | lupo_actor_channel_roles |
|----------|--------------------|---------------------------|
| **AuthRoleResolver** | ✅ Yes (isAdmin, hasAnyChannelRole) | No |
| **AuthManager** | ✅ Yes (getPermissions) | No |
| **Channel role checks** | ✅ Yes (channels-controller, operator-accept-visitor-api, module-loader) | No |
| **Services** | — | EnforceProtocolCompletionService (read), AgentAwarenessLayer (read + write; write may be broken) |
| **Helpers** | No direct refs (auth uses AuthRoleResolver) | No |
| **Modules** | ✅ Channels module, Crafty Syntax (choosedepartment, livehelp-js, visitor-image) | No |
| **Migrations / install** | ✅ install_new_lupopedia.sql, dev_20260204_*, seed_admin_captain.sql, channel_roles_escalations_toon_alignment.sql | ✅ install_new_lupopedia.sql, dev_20260204_*, structural_alignment |
| **Crafty Syntax compatibility** | ✅ choosedepartment, livehelp-js, visitor-image | No |

**Conclusion:**  
- **lupo_channel_roles** is the table used for **all auth, channel UI, and Crafty Syntax** role checks and role assignment (captain, administrator, monitor). It is the **real** table for “actor has role in channel” in production code paths.  
- **lupo_actor_channel_roles** is used only by **protocol/awareness** code (EnforceProtocolCompletionService, AgentAwarenessLayer) and in **schema/docs/dialogs**. It has a different schema (role_key, protocol/awareness columns). One PHP write (AgentAwarenessLayer) uses columns that do not match the current install schema and is likely broken or legacy.

---

## 4. Duplicate vs two distinct concepts

- They are **not** strict duplicates: different PKs, different role column names, and only `lupo_actor_channel_roles` has protocol/awareness fields.
- For **“who is captain/administrator/monitor in a channel?”** the codebase uses **only lupo_channel_roles**.
- **lupo_actor_channel_roles** is used for **protocol completion and awareness** (AAL/RSHAP/CJP). If that feature set is retired or folded into another table, then `lupo_actor_channel_roles` could be dropped after migrating those two PHP usages.

---

## 5. Recommendation

- **Do not drop lupo_channel_roles.** It is the only table used for auth, channel roles, and Crafty Syntax.
- **Do not drop lupo_actor_channel_roles** without a decision on the protocol/awareness layer:
  - If **keeping** AAL/RSHAP/CJP: keep the table and fix **AgentAwarenessLayer** (INSERT columns to match schema: `role_key`, `awareness_snapshot_json` or similar; remove or map `role`/`metadata_json`). Optionally refactor EnforceProtocolCompletionService to use LUPO_TABLE_PREFIX and quoted identifier instead of hardcoded `lupo_actor_channel_roles`.
  - If **retiring** protocol/awareness: migrate EnforceProtocolCompletionService and AgentAwarenessLayer off `lupo_actor_channel_roles`, then drop the table and remove it from install/migrations/docs/TOONs.

**Safe to drop:** Neither table is “safe to drop” without the above migration:  
- Dropping **lupo_channel_roles** would break auth and channels.  
- Dropping **lupo_actor_channel_roles** would break EnforceProtocolCompletionService and AgentAwarenessLayer (and any doc/TOON that assumes the table exists).

---

## 6. Files that would need cleanup before dropping lupo_actor_channel_roles (if retired)

If the decision is to **drop lupo_actor_channel_roles** and retire protocol/awareness in code:

| File | Change |
|------|--------|
| app/Services/TriggerReplacements/EnforceProtocolCompletionService.php | Remove or rewrite protocol check (no longer read from lupo_actor_channel_roles). |
| lupo-includes/classes/AgentAwarenessLayer.php | Remove or rewrite getChannelActors JOIN and storeAwarenessSnapshot INSERT (no longer use lupo_actor_channel_roles). |
| database/migrations/install_new_lupopedia.sql | Remove CREATE TABLE lupo_actor_channel_roles and its indexes. |
| database/migrations/dev_20260204_fix_schema_alignment.sql | Remove ALTER TABLE lupo_actor_channel_roles statements. |
| database/migrations/dev_20260204_fix_schema_alignment_summary.txt | Remove lupo_actor_channel_roles lines. |
| migrations/structural_alignment_mysql_migration.sql | Remove reference to lupo_actor_channel_roles. |
| docs/REQUIRED_TABLES_4.2.1.md | Remove lupo_actor_channel_roles from list. |
| docs/LIVEHELP_REMOVAL_REPORT.md | Update text that mentions lupo_actor_channel_roles. |
| docs/toons/lupo_actor_channel_roles.toon.json | Delete or archive (TOONs are generated; regenerate after schema change). |
| All docs/channels/… and dialogs/… that reference lupo_actor_channel_roles | Update or remove references (doctrine, migrations, dialogs). |

No cleanup is recommended for **lupo_channel_roles**; it is the single source of truth for channel roles in the application.
