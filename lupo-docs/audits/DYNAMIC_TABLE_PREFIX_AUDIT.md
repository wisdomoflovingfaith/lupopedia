# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/audits/DYNAMIC_TABLE_PREFIX_AUDIT.md"
  file_hash: "6fb59c7ff3424507ce81706fb246196238d706d4f7cfbcdf97a3a70fa31320f5"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\audits\DYNAMIC_TABLE_PREFIX_AUDIT.md"
  file_hash: "8b366160becf9bdf00547824e18684dd206eadbf7247ad474a5c42a7ccd483fe"
  file_path_from_root: "lupo-docs\audits\DYNAMIC_TABLE_PREFIX_AUDIT.md"
  file_hash: "6de5b12790cd2f38ff8e4c42a641cf6f4d9fe5f8e21487eb658b7cd071b80bb3"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Dynamic Table Prefix Audit"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "dynamic_table_prefix_auditmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Dynamic Table Prefix Audit

**Date:** 2026-02-14  
**Doctrine:** All runtime PHP must use `LUPO_TABLE_PREFIX` (or fallback `'lupo_'`) for table names. No literal `lupo_tablename` in PHP.

## Allowed (DO NOT MODIFY)

- `lupo-database/migrations/install_new_lupopedia.sql`
- `lupo-database/migrations/seed_lupopedia.sql`
- `lupo-database/migrations/import_from_old_crafty_syntax.sql`
- `lupo-database/migrations/drop_old_crafty_syntax_tables.sql`
- `lupo-database/migrations_legacy/migration_REGISTRY_agents_columns_and_insert.sql`
- `lupo-docs/toons/*.toon.json` (all TOON files)

## Fixed in This Pass

| File | Change |
|------|--------|
| `install.php` | `lupo_auth_users` → `(LUPO_TABLE_PREFIX ?: 'lupo_') . 'auth_users'`; `lupo_actors` → dynamic prefix for actors_table. |
| `install_wizard_classes.php` | InstallWizardRegistryValidator: default table name built from prefix. InstallWizardDepartments: departments table via prefix. InstallWizardChannels: channels, actor_channel_roles, actors, auth_users, actor_departments, department_roles via prefix in all methods. |
| `app/Services/System/SystemHealthService.php` | Core tables array and SHOW TABLES for REGISTRY use prefix. |
| `lupo-includes/classes/LABSValidator.php` | lupo_actors, lupo_labs_declarations → prefix in each method. |
| `lupo-includes/models/GroundedAgentModel.php` | lupo_agents, lupo_agent_owners, lupo_actors, lupo_actor_actions → prefix. |
| `lupo-includes/theme/theme-loader.php` | lupo_federation_nodes → prefix. |
| `app/Services/System/LupopediaMigrationController.php` | lupo_migration_log → prefix. |
| `lupo-scripts/run_labs_handshake.php` | lupo_actors → prefix + $actors_t. |
| `lupo-scripts/migrate_user_mappings.php` | lupo_auth_users, lupo_crafty_user_mapping → prefix in each method. |

## Remaining PHP Files with Literal `lupo_` (Follow-up)

These files still contain literal `lupo_` table names or references (in SQL or comments). They should be updated in a follow-up pass to use dynamic prefix where they build runtime SQL:

- `lupo-api/v1/*.php` (timeline, artifact, dialog, actor)
- `app/Services/TriggerReplacements/*.php`
- `app/Services/EdgeService.php`
- `lupo-includes/classes/AgentAwarenessLayer.php`
- `lupo-includes/classes/EmergentRoleDiscovery.php`
- `lupo-includes/classes/CIP*.php`
- `lupo-includes/DialogChannelMigration/*.php`
- `lupo-includes/modules/content/lookup-helpers.php`, `content-controller.php`, `edge-controller.php`
- `lupo-includes/modules/truth/truth-controller.php`
- `lupo-scripts/migrate_filesystem_to_db.php`, `migrate_wolfie_headers_to_db.php`, `validate_tab_mappings.php`, `cleanup_old_directories.php`
- `test_cip_analytics.php`
- Other modules and helpers (see grep for full list)

Session keys and global names (e.g. `$_SESSION['lupo_install_type']`, `$GLOBALS['lupo_auth_service']`) are **not** table names and are not required to use the prefix constant.

## Validation

- No schema files were modified (lupo-install/seed/import/migration/TOONs untouched).
- No references to `lupo_agent_registry` were introduced.
- PHP 5.3: `array()` used in new code; short array `[]` left as-is where already present in existing code.
