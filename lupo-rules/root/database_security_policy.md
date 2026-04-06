---
lupopedia.headers:
  lupopedia.version: "4.0.89"
  lupopedia.schema: rules
  file_path_from_root: "lupo-rules/root/database_security_policy.md"
  web_path: "http://www.lupopedia.com/lupo-rules/root/database_security_policy.md"
  last_modified_utc: "20260406044907"
  when_updated: "20260406044907"
  system_version: "4.0.89"
  channel_id: 42
  thread_id: "4.0.89-database-security"
  author:
    type: "actor"
    id: 1
    name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: rules
  artifact_kind: policy
  purpose: Database security policy forbidding command line MySQL access
  tags:
  - "4.0.89"
  - "database_security"
  - "rules"
  - "mysql"
  - "security"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-rules/root/README.md"
      type: references
      weight: 1.0
      reason: Root rules index
    - to: "lupo-rules/root/lupopedia_upgrade_policy.md"
      type: references
      weight: 1.0
      reason: Related upgrade policy
lupopedia.footer:
  version: "4.0.89"
  last_verified: "20260328130000"
  last_verified_by: "wolfie"
  last_verified_by_actor_id: 1
  orchestrator: "wolfie:root"
  next_action:
    - Enforce database security policy across all development
    - Monitor compliance with no command line MySQL rule
    - Validate all SQL execution through PHP scripts
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
- Path: `/lupo-tmp/run_*.php` or `/lupo-tests/debug_*.php`
- Admin-only access required
- Safe PHP-based SQL execution
- Error handling and logging

#### 3. Debug Tools (Testing)
- Path: `/lupo-tests/debug_*.php`
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
- Use `lupo-tmp/` for one-time migration scripts
- Use `lupo-tests/` for debug and validation tools
- NEVER run SQL directly from command line
- ALWAYS backup before migrations

### 📁 PROPER FILE ORGANIZATION

```
lupo-tmp/           # One-time migration scripts
├── run_*.php       # Migration runners (admin access required)

lupo-tests/         # Debug and validation tools
├── debug_*.php     # Read-only diagnostics
└── unit/           # Unit tests

lupo-scripts/       # Reusable utility scripts
├── generate_*.php  # Data generation
└── verify_*.php    # Validation tools
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
