---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/sql_execution_policy.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/sql_execution_policy.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: policy
  channel_key: null
  federation_node_id: null
  thread_key: 4.0.89-sql-policy
  lupopedia.schema: documentation
  prd_cluster: null
  title: ''
  summary: ''
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
Use the migration runner: `http://localhost/lupopedia/tmp/run_department_migrations.php`
- Requires admin login (auth_user_id = 1000)
- Safely executes department access control migrations
- Includes error handling and rollback capabilities

### 3. For Development/Testing
Use the debug tools in `tests/`:
- `tests/debug_departments.php` - Test department mappings
- `tests/debug_whoami.php` - Test user session state

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
