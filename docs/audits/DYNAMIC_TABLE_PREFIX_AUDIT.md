# Dynamic Table Prefix Audit

**Date:** 2026-02-14  
**Doctrine:** All runtime PHP must use `LUPO_TABLE_PREFIX` (or fallback `'lupo_'`) for table names. No literal `lupo_tablename` in PHP.

## Allowed (DO NOT MODIFY)

- `database/migrations/install_new_lupopedia.sql`
- `database/migrations/seed_lupopedia.sql`
- `database/migrations/import_from_old_crafty_syntax.sql`
- `database/migrations/drop_old_crafty_syntax_tables.sql`
- `database/migrations_legacy/migration_unified_registry_agents_columns_and_insert.sql`
- `docs/toons/*.toon.json` (all TOON files)

## Fixed in This Pass

| File | Change |
|------|--------|
| `install.php` | `lupo_auth_users` → `(LUPO_TABLE_PREFIX ?: 'lupo_') . 'auth_users'`; `lupo_actors` → dynamic prefix for actors_table. |
| `install_wizard_classes.php` | InstallWizardUnifiedRegistryValidator: default table name built from prefix. InstallWizardDepartments: departments table via prefix. InstallWizardChannels: channels, actor_channel_roles, actors, auth_users, actor_departments, department_roles via prefix in all methods. |
| `app/Services/System/SystemHealthService.php` | Core tables array and SHOW TABLES for unified_registry use prefix. |
| `lupo-includes/classes/LABSValidator.php` | lupo_actors, lupo_labs_declarations → prefix in each method. |
| `lupo-includes/models/GroundedAgentModel.php` | lupo_agents, lupo_agent_owners, lupo_actors, lupo_actor_actions → prefix. |
| `lupo-includes/theme/theme-loader.php` | lupo_federation_nodes → prefix. |
| `app/Services/System/LupopediaMigrationController.php` | lupo_migration_log → prefix. |
| `scripts/run_labs_handshake.php` | lupo_actors → prefix + $actors_t. |
| `scripts/migrate_user_mappings.php` | lupo_auth_users, lupo_crafty_user_mapping → prefix in each method. |

## Remaining PHP Files with Literal `lupo_` (Follow-up)

These files still contain literal `lupo_` table names or references (in SQL or comments). They should be updated in a follow-up pass to use dynamic prefix where they build runtime SQL:

- `api/v1/*.php` (timeline, artifact, dialog, actor)
- `app/Services/TriggerReplacements/*.php`
- `app/Services/EdgeService.php`
- `lupo-includes/classes/AgentAwarenessLayer.php`
- `lupo-includes/classes/EmergentRoleDiscovery.php`
- `lupo-includes/classes/CIP*.php`
- `lupo-includes/DialogChannelMigration/*.php`
- `lupo-includes/modules/content/lookup-helpers.php`, `content-controller.php`, `edge-controller.php`
- `lupo-includes/modules/truth/truth-controller.php`
- `scripts/migrate_filesystem_to_db.php`, `migrate_wolfie_headers_to_db.php`, `validate_tab_mappings.php`, `cleanup_old_directories.php`
- `test_cip_analytics.php`
- Other modules and helpers (see grep for full list)

Session keys and global names (e.g. `$_SESSION['lupo_install_type']`, `$GLOBALS['lupo_auth_service']`) are **not** table names and are not required to use the prefix constant.

## Validation

- No schema files were modified (install/seed/import/migration/TOONs untouched).
- No references to `lupo_agent_registry` were introduced.
- PHP 5.3: `array()` used in new code; short array `[]` left as-is where already present in existing code.
