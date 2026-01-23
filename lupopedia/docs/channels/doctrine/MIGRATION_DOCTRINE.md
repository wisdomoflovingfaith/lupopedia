---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-08
author: Wolfie (Eric Robin Gerdes)
architect: Captain Wolfie
dialog:
  speaker: cursor
  target: @everyone
  message: "Created MIGRATION_DOCTRINE.md to document that LiveHelp tables are migration-only and not part of Lupopedia 4.0.1 runtime."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "migration"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
file:
  title: "Lupopedia Migration Doctrine (LiveHelp â†’ Crafty Syntax 4.0.3)"
  description: "Doctrine explaining the temporary role of livehelp_* tables during upgrade from Crafty Syntax 3.7.5 to Lupopedia 4.0.3"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Captain Wolfie"
---

# ðŸŸ¦ **Lupopedia Migration Doctrine (LiveHelp â†’ Crafty Syntax 4.0.3)**

### **Purpose of This Document (For Cursor & Contributors)**

This doctrine explains the **temporary role** of the legacy `livehelp_*` tables during the upgrade from **Crafty Syntax 3.7.5** to **Lupopedia 4.0.3**, and defines how the migration SQL is executed during installation. The migration SQL is complete and production-ready. Cursor must follow this doctrine when generating documentation, code, or refactor logic.

---

# ðŸŸ© **1. LiveHelp Is a Temporary Migration Subsystem**

The legacy **LiveHelp** tables (`livehelp_*`) are **not part of Lupopedia 4.0.3**.  
They exist **only during the upgrade process** from Crafty Syntax 3.7.5.

LiveHelp is **not**:

- a module  
- a subsystem  
- a feature  
- a runtime dependency  
- something Cursor should generate code for  

LiveHelp is **migration-only**.

Once the upgrade is complete, **all `livehelp_*` tables are removed**.

---

# ðŸŸ¦ **2. Migration SQL Handles All Data Conversion**

The migration from Crafty Syntax 3.7.5 â†’ Lupopedia 4.0.3 is performed by a **single SQL migration file** that:

- reads from all `livehelp_*` tables  
- transforms the data  
- inserts it into the new 4.0.1 tables  
- performs ID remapping  
- handles foreign keys  
- normalizes timestamps  
- resolves orphaned references  
- populates Crafty Syntax 4.0.3 tables  
- populates CRM, THOTH, Content, and Routing tables where applicable  

Cursor **must not** attempt to generate or rewrite this migration SQL.

The migration SQL is **hand-authored** and **authoritative**.

---

# ðŸŸ§ **3. Location of the Migration SQL File**

Cursor must document and respect the following:

### **Migration SQL lives here:**

```
/database/migrations/
```

This folder contains:

- `craftysyntax_to_lupopedia_mysql.sql` (migration from Crafty Syntax 3.7.5 â†’ Lupopedia 4.0.3) - **COMPLETE AND PRODUCTION-READY**
- any future migration SQL files  
- no PHP logic  
- no auto-generated content  

Cursor must **not** attempt to rewrite or regenerate these SQL files.

Cursor may reference them in documentation, but must treat them as **immutable artifacts**.

---

# ðŸŸ© **4. Upgrade Wizard Executes the Migration SQL**

The upgrade wizard (`lupo-includes/lupopedia-setup.php`) handles the complete migration process:

1. **Detection**: The wizard detects `config.php` (Crafty Syntax 3.7.5 config file - only exists in old installations) to enter upgrade mode.
2. **Config Migration**: Parses old `config.php` and creates `lupopedia-config.php` (WordPress-style, outside web root) with **enforced `lupo_` prefix**.
3. **Table Detection**: Detects all `livehelp_*` tables in the database.
4. **Migration Execution**: Automatically executes the complete migration SQL file from `/database/migrations/craftysyntax_to_lupopedia_mysql.sql` (version 4.0.3, production-ready) using the **enforced `lupo_` prefix**.
5. **Verification**: User verifies data migrated correctly before proceeding.
6. **Table Cleanup**: After user confirmation, the wizard **drops all `livehelp_*` tables**.
7. **Completion**: System continues with a clean Lupopedia 4.0.3 schema using the `lupo_` prefix.

**Important**: Upgrades from Crafty Syntax 3.7.5 to Lupopedia 4.0.3 **always use the `lupo_` prefix** (enforced, not user-selectable). New installs starting from version 4.1.0 allow users to choose their own prefix.

The upgrade wizard:
- Uses transactions for safe migration execution
- Requires explicit user confirmation at critical steps
- Provides clear warnings before dropping legacy tables
- Follows the Migration Doctrine (SQL is authoritative, not generated)

Cursor must document this behavior but must **not** generate installer logic.

---

# ðŸŸ¦ **5. Cursor's Responsibilities (Critical)**

Cursor must:

### âœ” Document the migration process  
### âœ” Reference the migration SQL file  
### âœ” Explain that LiveHelp is temporary  
### âœ” Treat LiveHelp as a migration artifact  
### âœ” Treat the migration SQL as authoritative  
### âœ” Avoid generating code that interacts with `livehelp_*` tables  
### âœ” Avoid generating new migration SQL  

Cursor must **not**:

### âœ˜ Generate SQL for LiveHelp  
### âœ˜ Rewrite the migration SQL  
### âœ˜ Treat LiveHelp as a module  
### âœ˜ Treat LiveHelp as a runtime subsystem  
### âœ˜ Create PHP classes for LiveHelp  
### âœ˜ Add LiveHelp to the module registry  
### âœ˜ Add LiveHelp to routing or agent logic  

Cursor must understand that LiveHelp is **not part of the runtime OS**.

---

# ðŸŸ§ **6. Post-Migration State**

After the migration completes:

- All `livehelp_*` tables are dropped  
- The database contains only Lupopedia 4.0.1 tables  
- Crafty Syntax 4.0.1 replaces LiveHelp entirely  
- No legacy tables remain  
- No legacy code paths remain  
- No references to LiveHelp should appear in documentation or code  

Cursor must generate documentation and code **as if LiveHelp never existed**, except in the migration doctrine.

---

# ðŸŸ¦ **7. Summary for Cursor**

Here is the short version Cursor must internalize:

- LiveHelp is **migration-only**  
- Migration SQL is **pre-written** and **authoritative**  
- Migration SQL lives in `/database/migrations/`  
- Installer executes it automatically  
- Cursor must **not** generate SQL for LiveHelp  
- Cursor must **not** treat LiveHelp as a module  
- After migration, LiveHelp tables are deleted  
- Cursor must document this behavior  

---

# ðŸŸ© **8. Table Count Clarification**

Lupopedia 4.0.1 has **145 tables total**:

- **111 core tables** â€” Permanent Lupopedia 4.0.1 tables
- **34 legacy migration tables** (`livehelp_*`) â€” Temporary, migration-only, removed after upgrade
- **4 system tables** â€” Includes `federation_nodes` (Federation Layer, formerly `node_registry`), `modules`, etc.

The 34 `livehelp_*` tables are included in the TOON files for migration reference only. They are **not part of the runtime schema** after migration completes.

---

## **Related Documentation**

**Core References:**
- [Database Schema](../schema/DATABASE_SCHEMA.md) â€” Complete table documentation including migration-only tables
- [SQL Rewrite Doctrine](SQL_REWRITE_DOCTRINE.md) â€” Rules for refactoring SQL during migration
- [TOON Doctrine](TOON_DOCTRINE.md) â€” Rules for working with TOON files during migration
- [Upgrade Plan](../developer/modules/UPGRADE_PLAN_3.7.5_TO_4.0.0.md) â€” Detailed upgrade documentation

**Development Context (LOW Priority):**
- [Legacy Refactor Plan](../developer/modules/LEGACY_REFACTOR_PLAN.md) â€” Complete plan for handling legacy Crafty Syntax files
- [History](../history/HISTORY.md) â€” Background on Crafty Syntax evolution that necessitates migration
- [Database Philosophy](../architecture/DATABASE_PHILOSOPHY.md) â€” Application-first principles that guide migration approach
- [Configuration Doctrine](CONFIGURATION_DOCTRINE.md) â€” WordPress-style configuration that simplifies migration

---

*Last Updated: January 2026*  
*Version: GLOBAL_CURRENT_LUPOPEDIA_VERSION*  
*Author: Captain Wolfie*
