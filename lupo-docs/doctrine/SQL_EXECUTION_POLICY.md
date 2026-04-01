---
lupopedia.headers:
  lupopedia.version: "4.0.89"
  lupopedia.schema: documentation
  file_path_from_root: "SQL_EXECUTION_POLICY.md"
  web_path: "http://www.lupopedia.com/SQL_EXECUTION_POLICY.md"
  last_modified_utc: "20260328130000"
  when_updated: "20260328130000"
  system_version: "4.0.89"
  channel_id: 42
  thread_id: "4.0.89-sql-policy"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: documentation
  artifact_kind: policy
  purpose: SQL execution policy and security guidelines
  tags:
  - "4.0.89"
  - "sql_execution"
  - "security"
  - "database"
  - "policy"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-rules/root/database_security_policy.md"
      type: references
      weight: 1.0
      reason: Database security policy
    - to: "lupo-rules/root/README.md"
      type: references
      weight: 1.0
      reason: Root rules index
lupopedia.footer:
  version: "4.0.89"
  last_verified: "20260328130000"
  last_verified_by: "wolfie"
  last_verified_by_actor_id: 1
  orchestrator: "wolfie:root"
  next_action:
    - Enforce SQL execution policy
    - Validate all SQL runs through PHP scripts
    - Monitor compliance with security guidelines
---

# SQL Execution Policy

## 🚫 IMPORTANT SECURITY NOTICE

**Direct MySQL command line access is DISABLED** for security reasons. This prevents AI agents from accidentally damaging the database with unchecked SQL batches.

## ✅ PROPER SQL EXECUTION METHODS

### 1. For New Installations
Run the install wizard: `http://localhost/lupopedia/install.php`
- All SQL is executed through PHP with proper validation
- Department access control is included automatically

### 2. For Existing Databases
Use the migration runner: `http://localhost/lupopedia/lupo-tmp/run_department_migrations.php`
- Requires admin login (auth_user_id = 1000)
- Safely executes department access control migrations
- Includes error handling and rollback capabilities

### 3. For Development/Testing
Use the debug tools in `lupo-tests/`:
- `lupo-tests/debug_departments.php` - Test department mappings
- `lupo-tests/debug_whoami.php` - Test user session state

## 🛡️ WHY THIS POLICY EXISTS

1. **Prevents Database Corruption**: AI agents have historically damaged databases with unchecked SQL
2. **Ensures Validation**: All SQL goes through PHP validation and error handling
3. **Maintains Security**: Only authorized users can execute schema changes
4. **Provides Audit Trail**: PHP scripts log all changes and errors

## 📋 WHAT'S INCLUDED IN THE INSTALL

### Database Schema Changes
- `lupo_auth_user_departments` mapping table (many-to-many)
- `lupo_actor_departments` mapping table (already exists)
- Department 0 (root) with full access
- Proper indexes for performance

### Seed Data
- System actors (1-14) mapped to department 0
- Root auth user (1000) mapped to department 0
- Existing users assigned to department 0

### Code Changes
- `AuthSessionManager` uses mapping tables
- Actor creation creates department mappings
- Department-based access control enforcement

## 🔧 MANUAL EXECUTION (NOT RECOMMENDED)

If you must use phpMyAdmin:
1. **BACKUP YOUR DATABASE FIRST**
2. Run each SQL statement individually
3. Check for errors after each statement
4. Never run large batches without testing

## 📞 GETTING HELP

If you encounter issues:
1. Check the debug tools for diagnostics
2. Review the migration logs
3. Contact the system administrator
4. Do NOT attempt direct command line MySQL access

---

**Remember**: The PHP-based approach is safer, more reliable, and provides better error handling than direct SQL execution.
