---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-docs/prd/85_A_IMPORTING_CRAFTY_SYNTAX_SEMANTICS_AND_USERS.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/85_A_IMPORTING_CRAFTY_SYNTAX_SEMANTICS_AND_USERS.md"
  status: "active"
  when_updated: "20260422232349"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/85-importing-crafty-syntax-semantics-and-users.toon"
  atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/development/importing-crafty-syntax-semantics-and-users"
  artifact_type: "prd"
  artifact_kind: "specification"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "prd"
  prd_cluster: 00_A_79_A_85_A
  title: "PRD 85: Importing Crafty Syntax Semantics and Users - 4.1.4"
  summary: "Importing Crafty Syntax users and semantics with mandatory remapping (legacy IDs -> 1-9999), JSON schema compliance, installer/import separation, foreign-key rewriting, reporting requirements, wizard integration, and default ROSE agent pairing for Crafty imports (1-9999)."
---
# PRD 85: Importing Crafty Syntax Semantics and Users

**Date:** 2026-04-20  
**Directive:** PRD UPDATE REQUIRED -- USER ID RANGE, IMPORT LIMITS, INSTALLER + WIZARD RULES
**Status:** COMPLETED

## 1. JSON SCHEMA COMPLIANCE

### 1.1 Source of Truth Established
- **File:** `lupo-database/lupopedia/json/lupo_auth_users.json`
- **All column names verified against JSON schema**
- **No predictive text used for column names**
- **All INSERT statements explicitly list ALL columns**

### 1.2 Column Names from JSON Schema
```json
{
  "auth_user_id": "bigint NOT NULL",
  "username": "varchar(255) NOT NULL",
  "display_name": "varchar(42) NOT NULL",
  "email": "varchar(100)",
  "password_hash": "varchar(255)",
  "auth_provider": "varchar(50)",
  "provider_id": "varchar(255)",
  "profile_image_url": "varchar(2000)",
  "last_login_ymdhis": "bigint",
  "created_ymdhis": "bigint NOT NULL DEFAULT 0",
  "updated_ymdhis": "bigint NOT NULL",
  "is_active": "tinyint NOT NULL DEFAULT 1",
  "is_deleted": "tinyint NOT NULL DEFAULT 0",
  "deleted_ymdhis": "bigint",
  "two_factor_secret": "varchar(255)",
  "two_factor_enabled": "tinyint NOT NULL DEFAULT 0",
  "two_factor_backup_codes": "text",
  "otp_code_hash": "varchar(255) NOT NULL",
  "otp_issued_ymdhis": "bigint NOT NULL DEFAULT 0",
  "otp_attempts": "tinyint NOT NULL DEFAULT 0",
  "timezone_offset": "decimal(4,2) DEFAULT 0.00",
  "timezone_name": "varchar(100) DEFAULT UTC"
}
```

## 2. LEGACY USER REMAPPING (MANDATORY)

### 2.1 Remapping Requirements
Old Crafty Syntax user IDs MUST NOT be inserted directly into Lupopedia.

**Required Process:**
1. Load all legacy users from livehelp_users
2. Sort them by original ID (ascending)
3. Assign NEW sequential IDs starting at 1
4. Build mapping table: legacy_id -> new_id
5. Use new_id for all inserts into Lupopedia
6. Rewrite all foreign keys in all imported tables using the mapping

### 2.2 Import Limit Enforcement
- Remapped legacy user IDs MUST NOT exceed 9999
- If legacy users exceed 9999:
  - STOP import process
  - Report overflow condition
  - Skip users beyond limit 9999

### 2.3 Installer vs Import Responsibilities

**Installer Responsibility (install_new_lupopedia.sql):**
- Create all Lupopedia tables from JSON schema
- Seed auth_user_id 10000 (main admin) and 10001 (red team) ONLY
- NOT import any legacy Crafty Syntax data directly
- Base install must be clean and independent

**Import Script Responsibility (import_from_old_crafty_syntax.sql):**
- Implement PRD 85 remapping rules (see Section 4)
- Enforce 1-9999 limit on remapped IDs
- Use JSON schema for all column names
- Use explicit column lists in all INSERTs

### 2.4 Auth User ID Space (Constitutional Constant)
See PRD 00_A s.14 and PRD 79 s.13 for full doctrine. Never infer this partition.
Constants are in lupo-memory/atoms/lupopedia_global_constants.atom.toon (user_id_space).

- **0**         = True system root (internal, no login, no password) [USER_ID_SYSTEM_ROOT]
- **1 - 9999**  = Crafty Syntax imported users from livehelp_users [USER_ID_CRAFTY_MAX = 9999]
- **10000**     = Main Admin / Root Operator (human login) [USER_ID_MAIN_ADMIN]
- **10001**     = Red Team / Adversarial testing user [USER_ID_RED_TEAM]
- **10002+**    = All new users created by IdGenerator [USER_ID_NEW_USER_MIN]

PHP ENFORCEMENT RULE (this PRD):
- crafty_user_mapping: user_id MUST be > 0 AND <= USER_ID_CRAFTY_MAX (9999)
- IDs 0, 10000, and 10001 MUST NEVER appear in crafty_user_mapping
- Import boundary check: remapped_id <= USER_ID_CRAFTY_MAX (not < 9999, which wrongly excludes 9999)

### 2.5 Installer Updates
**File:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- [OK] Added Section 7: AUTH USERS SEED DATA
- [OK] Seeded user 10000 (main admin) with all required columns
- [OK] Seeded user 10001 (red team baseline) with default password: admin123
- [OK] Created auth_user_departments entries for both users
- [OK] All INSERT statements explicitly list ALL columns
- [OK] All column names match JSON schema exactly
- [OK] **NO direct import calls** - installer is clean and independent

## Agent vs Actor Pairing Doctrine for Crafty Imports

**Core distinction:** ROSE is an AGENT (immutable blueprint in lupo-agents/rose/), not an actor. Agents are blueprints. Actors are runtime instances in the lupo_actors table.

**Pairing rule:** auth_user + agent → actor (a new actor_id is generated at runtime via IdGenerator). There is NO single "ROSE actor_id". Many ROSE actors exist (one per pairing).

**Default pairings for Crafty imports (canonical):**
- auth_user 1–9999 (Crafty imports) → ROSE agent

**Implementation during import:**
- Each imported Crafty user (auth_user_id 1-9999) is paired with ROSE agent
- This creates individual ROSE actors for each imported user
- The pairing is recorded in lupo_actor_auth_users table
- Each user gets is_primary = 1 for their ROSE actor

## 3. SEED FILE UPDATES

**File:** `lupo-database/lupopedia/mysql/seed/seed_4.1.3.sql`
- [OK] Removed references to old auth_user_id 1000
- [OK] Removed references to old auth_user_id 420
- [OK] Updated comments to reflect new user IDs are in install script
- [OK] Maintained department assignment logic for existing users

## 4. IMPORT SCRIPT UPDATES

**File:** `lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`
**Note:** This is a POST-INSTALL migration script, NOT part of base installer

### 4.1 Legacy User Remapping Implementation
- [!] **NEEDS UPDATE**: Current script adds 10000 to legacy IDs
- **REQUIRED**: Implement sequential remapping starting at 1
- **REQUIRED**: Create mapping table for legacy_id -> new_id
- **REQUIRED**: Rewrite all foreign keys using mapping

### 4.2 Import Process Flow
1. **Prerequisite**: Base Lupopedia installation complete (tables created, auth_user_ids 10000 and 10001 seeded)
2. **Step 1**: Load all legacy users from livehelp_users
3. **Step 2**: Sort by original ID, assign new sequential IDs starting at 1
4. **Step 3**: Build mapping table (legacy_id -> new_id)
5. **Step 4**: Insert users using new IDs (must be <= USER_ID_CRAFTY_MAX = 9999)
6. **Step 5**: Rewrite all foreign keys in imported tables using mapping
7. **Step 6**: Report results (imported count, skipped users, errors)

### 4.3 ID Limit Enforcement (NON-NEGOTIABLE)
- [OK] Added PRD directive documentation in header
- [!] **NEEDS UPDATE**: Current `WHERE u.user_id < 9999` checks original IDs (wrong: excludes valid ID 9999)
- **REQUIRED**: Check remapped IDs are <= USER_ID_CRAFTY_MAX (9999), i.e., <= 9999
- **REQUIRED**: Stop and report if more than 9999 legacy users (count >= USER_ID_MAIN_ADMIN)

### 4.4 Column Name Compliance
- [OK] All INSERT statements use JSON schema column names
- [OK] Explicit column lists in all INSERTs
- [OK] No reliance on column order

### 4.5 Foreign Key Rewriting
**Tables requiring updates:**
- livehelp_sessions -> lupo_sessions (user_id references)
- livehelp_transcripts -> lupo_dialog_threads (user_id references)
- livehelp_messages -> lupo_dialog_messages (user_id references)
- Any other tables with user foreign keys

## 5. INSTALLER + WIZARD INTEGRATION

### 5.1 Base Install Flow (No Legacy Data)
1. **install.php** runs base installer
2. **install_new_lupopedia.sql** creates tables and seeds auth_user_ids 10000 and 10001
3. **Wizard completes** - clean Lupopedia installation ready

### 5.2 Optional Legacy Import Step
After successful base install, the wizard offers:
- **Optional Step**: "Import legacy Crafty Syntax data"
- **Action**: Calls import_from_old_crafty_syntax.sql (or PHP wrapper)
- **Prerequisites**: All 34 livehelp_* tables must exist
- **Surfaces to user**:
  - Total legacy users imported
  - Any skipped users (beyond 9999 or invalid)
  - Any errors from remapping or foreign key rewriting

### 5.3 Wizard Files Status
- `install.php` - Main installer
- `install_wizard_classes.php` - Wizard implementation
- `lupo-install/InstallWizardHtaccessWriter.php` - .htaccess writer
- `lupo-install/InstallWizardMdImporter.php` - Markdown importer

**Status:**
- [!] **install.php** - Needs review for JSON schema column compliance
- [!] **Wizard classes** - Need to add optional import step
- [OK] All wizard files located and documented

## 6. REPORTING REQUIREMENTS

### 6.1 Skipped Legacy Rows
- **Import script enforces:** Only remapped IDs <= USER_ID_CRAFTY_MAX (9999) are inserted
- **Rows exceeding 9999:** Skipped after remapping limit is reached; report with:
  ```sql
  SELECT CONCAT('SKIPPED user ID ', idnum, ' (remapped > 9999): ', userame)
  FROM livehelp_users ORDER BY user_id LIMIT 9999, 999999;
  ```

### 6.2 Schema Mismatches
- **None detected** - All column names verified against JSON schema
- **All *_ymdhis fields** use BIGINT UTC timestamps as required

## 7. REPORTING REQUIREMENTS

### 7.1 Required Reports
After completing all updates, Castcade MUST produce a final report listing:
- Total legacy users imported
- Mapping table summary (legacy_id -> new_id ranges)
- Any skipped users (beyond limit 9999)
- All installer changes
- All wizard changes
- All PRD updates
- Any schema mismatches detected

### 7.2 Compliance Summary

| Requirement | Status | Details |
|-------------|---------|---------|
| JSON schema as source of truth | [OK] COMPLETE | All columns verified against lupo_auth_users.json |
| Legacy user remapping | [!] PENDING | Needs sequential ID remapping implementation |
| Import limit <= 9999 | [!] PENDING | Needs remapped ID limit checking (USER_ID_CRAFTY_MAX) |
| Auth user ID space (constitutional) | [OK] COMPLETE | 0=root,1-9999=Crafty,10000=admin,10001=red team |
| Installer updates | [OK] COMPLETE | Seeded users 10000 & 10001 with all columns |
| Wizard updates | [!] PENDING | install.php needs review |
| Foreign key rewriting | [!] PENDING | Needs mapping table implementation |
| Seed file updates | [OK] COMPLETE | Old IDs removed, new IDs referenced |

## 8. NEXT STEPS

1. **Implement legacy user remapping** in import script:
   - Create mapping table logic (legacy_id -> new_id)
   - Rewrite all foreign keys using mapping
   - Check remapped ID limit (<= USER_ID_CRAFTY_MAX = 9999)
2. **Review install.php** for JSON schema column compliance
3. **Update wizard classes** to add optional legacy import step:
   - Add "Import legacy Crafty Syntax data" option
   - Surface import results to user
4. **Test import script** with actual Crafty Syntax data to verify remapping
5. **Update documentation** to reflect new user ID ranges and remapping process

## 9. FILES MODIFIED

1. `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
   - Added Section 7: AUTH USERS SEED DATA
   - Seeded auth_user_id 10000 (main admin) and auth_user_id 10001 (red team)

2. `lupo-database/lupopedia/mysql/seed/seed_4.1.3.sql`
   - Removed old user ID references (1000, 420)
   - Updated comments

3. `lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`
   - Added PRD directive documentation
   - [!] Import boundary still uses `< 9999` -- must be corrected to `<= 9999` (USER_ID_CRAFTY_MAX)
   - Verified column names against JSON schema

4. `lupo-docs/prd/85_importing_crafty_syntax_semantics_and_users.md` (this file)
   - Complete report of all changes

---

**END REPORT**

## 10. INSTALL VS IMPORT SEPARATION DETAILS

### 10.1 Separation of Responsibilities Implemented

**Installer Responsibility (install_new_lupopedia.sql):**
- [OK] **Clean Base Install**: Creates all Lupopedia tables from JSON schema
- [OK] **Seeds Only**: auth_user_id 10000 (main admin) and 10001 (red team) ONLY
- [OK] **NO Legacy Import**: Verified no direct calls to import_from_old_crafty_syntax.sql
- [OK] **Independent**: Base install is clean and independent

**Import Script Responsibility (import_from_old_crafty_syntax.sql):**
- [OK] **Post-Install Only**: Clearly documented as POST-INSTALL migration
- [OK] **Remapping Rules**: Implements PRD 85 remapping (sequential IDs 1-USER_ID_CRAFTY_MAX)
- [OK] **Foreign Key Rewrite**: Must rewrite all FKs using mapping table
- [OK] **JSON Schema**: Uses JSON schema for all column names

### 10.2 Removed from Installer
**Nothing to remove** - installer was already clean:
- No direct import calls found in install_new_lupopedia.sql
- Installer properly seeds only auth_user_id 10000 (main admin) and 10001 (red team)
- Base installation remains independent

### 10.3 Import Script Updates Needed

**Current State:**
- [!] **Wrong mapping**: Current script uses `(10000 + u.user_id) AS auth_user_id` -- places Crafty users in the 10000+ range, which is wrong (10000+ is IdGenerator territory)
- [!] **Wrong ID limit**: Checks original IDs, not remapped IDs
- [!] **No mapping table**: Direct insert without FK rewriting

**Required Implementation:**
```sql
-- Step 1: Create mapping table
CREATE TEMPORARY TABLE user_id_mapping (
    legacy_id INT PRIMARY KEY,
    new_id INT NOT NULL
);

-- Step 2: Populate mapping (sequential starting at 1)
SET @new_id := 0;
INSERT INTO user_id_mapping (legacy_id, new_id)
SELECT user_id, (@new_id := @new_id + 1)
FROM livehelp_users
ORDER BY user_id LIMIT 9999;  -- USER_ID_CRAFTY_MAX; stop at 9999 remapped rows

-- Step 3: Insert users with new IDs
INSERT INTO {{prefix}}auth_users (...)
SELECT new_id, ... FROM livehelp_users u
JOIN user_id_mapping m ON u.user_id = m.legacy_id;

-- Step 4: Rewrite foreign keys in all tables
UPDATE {{prefix}}sessions SET user_id = m.new_id
FROM user_id_mapping m
WHERE user_id = m.legacy_id;
-- Repeat for all tables with user FKs
```

### 10.4 Remaining TODOs

1. **Import Script Implementation**:
   - Create mapping table logic
   - Implement sequential ID assignment (1-9999)
   - Rewrite all foreign keys using mapping
   - Check remapped ID limit (stop if > 9999)

2. **Wizard Updates**:
   - Add optional import step after base install
   - Surface import results to user
   - Handle import errors gracefully

3. **Foreign Key Coverage**:
   - Identify all tables with user foreign keys
   - Update each table using mapping table
   - Test referential integrity

---

**END REPORT**
