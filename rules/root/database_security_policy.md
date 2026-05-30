---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: rules/root/database_security_policy.md
  web_path: https://www.lupopedia.com/lupopedia/rules/root/database_security_policy.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: rules
  artifact_kind: policy
  channel_key: null
  federation_node_id: null
  thread_key: 4.0.89-database-security
  lupopedia.schema: rules
  prd_cluster: null
  title: null
  summary: null
---

# Database Security Policy

## 🚫 COMMAND LINE MYSQL ACCESS IS FORBIDDEN

**RULE**: Direct MySQL command line access is DISABLED for security reasons.

### WHY THIS EXISTS
- AI agents have historically damaged databases with unchecked SQL batches
- Prevents accidental data corruption
- Ensures all SQL goes through proper validation
- Maintains system security and integrity

### ✅ APPROVED METHODS

#### 1. Install Wizard (New Installations)
- Path: `/install.php`
- Executes all SQL through PHP with validation
- Includes department access control automatically

#### 2. Migration Runners (Existing Databases)
- Path: `/tmp/run_*.php` or `/tests/debug_*.php`
- Admin-only access required
- Safe PHP-based SQL execution
- Error handling and logging

#### 3. Debug Tools (Testing)
- Path: `/tests/debug_*.php`
- Read-only database operations
- Validation and diagnostics
- No schema changes

### 🛡️ ENFORCEMENT

#### For AI Agents
- NEVER use `mysql -u root -p` or similar commands
- ALWAYS use PHP-based migration runners
- VALIDATE all SQL before execution
- LOG all database changes

#### For Developers
- Use `tmp/` for one-time migration scripts
- Use `tests/` for debug and validation tools
- NEVER run SQL directly from command line
- ALWAYS backup before migrations

### 📁 PROPER FILE ORGANIZATION

```
tmp/           # One-time migration scripts
+-- run_*.php       # Migration runners (admin access required)

tests/         # Debug and validation tools
+-- debug_*.php     # Read-only diagnostics
+-- unit/           # Unit tests

scripts/       # Reusable utility scripts
+-- generate_*.php  # Data generation
+-- verify_*.php    # Validation tools
```

### ⚠️ VIOLATION CONSEQUENCES

- Security breach protocol activation
- System access revocation
- Database corruption risk
- Loss of audit trail

---

**REMEMBER**: PHP-based execution is SAFER, more RELIABLE, and provides proper ERROR HANDLING.

**APPROVED BY**: WOLFIE (actor_id 1)
**ENFORCED BY**: LEXA (actor_id 24)
