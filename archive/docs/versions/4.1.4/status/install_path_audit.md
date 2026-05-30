---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "docs/versions/4.1.4/status/install_path_audit.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.4/status/install_path_audit.md"
  status: "active"
  when_updated: "20260422093000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/install-path-audit.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/install_path_audit"
  artifact_type: "documentation"
  artifact_kind: "report"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "documentation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE"
  title: "Install Path Audit Report"
  summary: "Complete audit of install.php execution path, including all invoked files, SQL execution chain, and configuration creation."
---

# Install Path Audit Report

## EXACT INSTALL SEQUENCE

### 1. Pre-flight Validation
- PHP 5.3+ requirement check
- PDO extension validation
- JSON extension validation
- Project root writability check
- Optional extensions: mbstring, curl, openssl, fileinfo (warn only)

### 2. Database Detection
- Check for `livehelp_*` tables existence
- **If exists:** Upgrade from Crafty Syntax 3.7.5 path
- **If not exists:** Fresh install path

### 3. Fresh Install Path
1. **Schema Install:** `install_new_lupopedia.sql`
2. **Seed Data:** `seed_lupopedia_4_1_0.sql` (preferred) or `seed_4.1.0.sql` (fallback)
3. **System Channels:** Create reserved system channels
4. **MD Import:** Import all markdown files
5. **Banned Identities:** Ensure stoned wolfie banned identities
6. **AI Agent Activation:** Activate core AI agents
7. **Config Generation:** Write `lupopedia-config.php`
8. **Runtime Setup:** Create directories and `.htaccess` rules

### 4. Upgrade Path (Crafty Syntax 3.7.5)
1. **Identity Normalization:** Validate unique emails, update `livehelp_users`
2. **Schema Install:** `install_new_lupopedia.sql`
3. **Seed Data:** Consolidated seed file
4. **Import:** `import_from_old_crafty_syntax.sql`
5. **Personal Channels:** Create personal channels and captain roles
6. **Drop Old Tables:** `drop_old_crafty_syntax_tables.sql`
7. **Config Generation:** Write `lupopedia-config.php`
8. **Runtime Setup:** Create directories and `.htaccess` rules

## ALL INVOKED FILES/CLASSES

### Core Installer
- `install.php` (main entry point)

### Wizard Classes (install/)
- `InstallWizardSecurity.php` (CSRF validation)
- `InstallWizardDb.php` (database operations)
- `InstallWizardSqlRunner.php` (SQL file execution)
- `InstallWizardCredentials.php` (credential handling)
- `InstallWizardLogger.php` (logging)
- `InstallWizardNormalize.php` (identity normalization)
- `InstallWizardChannels.php` (channel creation)
- `InstallWizardDepartments.php` (department management)
- `InstallWizardMdImporter.php` (markdown import)
- `InstallWizardBannedIdentities.php` (banned identity management)
- `InstallWizardMainAdmin.php` (admin user creation)
- `InstallWizardConfigWriter.php` (config file generation)
- `InstallWizardHtaccessWriter.php` (Apache .htaccess handling)
- `InstallWizardSteps.php` (wizard step management)

### Legacy Import
- `ImportLegacyCraftySyntax.php` (Crafty Syntax import logic)

## EXACT SQL FILES EXECUTED

### Canonical SQL Files
✅ `database/lupopedia/mysql/install/install_new_lupopedia.sql`
✅ `install/seed_lupopedia_4_1_0.sql` (preferred)
✅ `database/lupopedia/mysql/seed/seed_4.1.0.sql` (fallback)
✅ `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`
✅ `database/lupopedia/mysql/import/drop_old_crafty_syntax_tables.sql`

### Non-Canonical SQL Files (Detected)
⚠️ `database/lupopedia/mysql/seed/seed_4.1.3.sql` (version-specific)
⚠️ Multiple backup SQL files with timestamps

## ANY NON-CANONICAL SQL USAGE

**Detected Non-Canonical Usage:**
1. **Fallback to legacy seed:** Uses `seed_4.1.0.sql` if consolidated seed missing
2. **Version-specific seed:** `seed_4.1.3.sql` exists but not referenced
3. **Backup files:** Multiple timestamped backup files present

**Risk Assessment:** MEDIUM - Installer has fallback logic but may use outdated seed data

## CONFIG CREATION PATH

### 1. Configuration Collection
- Admin email (required, validated)
- Support email (optional, validated if provided)
- API keys (optional, collected if provided)

### 2. Config File Generation
- Location: Web root (`lupopedia-config.php`)
- Permissions: 0600 (read/write by owner only)
- Protection: `.htaccess` deny from all

### 3. Config Content
- Database credentials
- Table prefix
- API keys (if provided)
- Path configurations
- System settings

## MANUAL FALLBACK BEHAVIOR

### Database Connection Failures
- Graceful degradation with error messages
- No silent failures
- Detailed error logging

### File Permission Issues
- Warning messages for non-critical permissions
- Blocking errors for critical permissions
- Manual intervention guidance

### Missing SQL Files
- Fallback to alternative seed file
- Clear error messages for missing install SQL
- Stop-and-fix approach for critical files

## .HTACCESS HANDLING (SAFE ON SHARED HOSTING)

### Apache Detection
- Automatic detection of Apache server
- Safe fallback for non-Apache servers

### .htaccess Operations
1. **Config Protection:** Deny access to `lupopedia-config.php`
2. **Runtime Directories:** Create and protect runtime directories
3. **Rewrite Rules:** URL rewriting for clean URLs

### Shared Hosting Safety
- Only writes .htaccess if web root is writable
- Graceful degradation if .htaccess cannot be written
- No fatal errors if .htaccess operations fail

## API KEY ASSUMPTIONS

### API Keys Are Optional
- System can install with ZERO API keys
- Human-only mode fully supported
- AI agents activate only if API keys present

### API Key Storage
- Stored in `lupopedia-config.php`
- Protected by file permissions and .htaccess
- Not displayed in installer interface

### API Key Validation
- Basic format validation
- No live API validation during install
- Validation deferred to runtime

## CAN INSTALL SUCCEED WITH ZERO AI KEYS?

**YES** - Install is designed to work with:
- Zero API keys (human-only mode)
- Partial API key configuration
- Full API key configuration

**Human-Only Mode Features:**
- Basic user management
- Manual content creation
- Core system functionality
- No AI-powered features

## WHAT BREAKS AFTER DROPPING ALL TABLES + CONFIG?

### After Table Drop
- System detects missing tables
- Triggers fresh install path
- No data loss (tables already empty)

### After Config Delete
- System treats as fresh install
- Requires database credential re-entry
- Requires admin email setup
- Loses API key configuration

### Recovery Process
1. Access install.php
2. Provide database credentials
3. Complete installation wizard
4. System fully functional

## CRITICAL FINDINGS

### 🚨 Header Version Violation
- install.php uses header_format_version "4.1.2"
- System requires "4.1.4"
- **BLOCKER in strict mode**

### ⚠️ SQL File Confusion
- Multiple seed file versions exist
- Fallback logic may use outdated data
- **MEDIUM risk**

### ✅ Robust Fallback Behavior
- Graceful degradation throughout
- Clear error messages
- Manual recovery paths documented

## CONCLUSION

The install path is well-architected with robust fallback mechanisms. The primary blocker is the header version violation. Once fixed, the installer should handle fresh reinstalls reliably.

**VERDICT: SAFE AFTER header version fix**
