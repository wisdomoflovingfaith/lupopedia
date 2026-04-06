---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260403202128"
  file_path_from_root: "/lupo-docs/prd/27_installer_requirements.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/27_installer_requirements.md"
  last_modified_utc: "20260403202128"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-installer-requirements"
  prd_id: 27
  prd_slug: installer_requirements
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "hephaestus:root"
  artifact_type: "prd"
  artifact_kind: "installer"
  purpose: "Defines the installer requirements and constraints for Lupopedia, referencing the root constitutional system requirements."
  status: "approved"
  tags: ["installer", "constitutional", "system_requirements"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/prd/00_root_constitutional_system_requirements.md", type: "references", weight: 1.0, reason: "Constitutional anchor" }
    - { to: "lupo-docs/versions/4.0.93/WHAT_TO_DO_NEXT.md", type: "references", weight: 0.95, reason: "Installer verification §14" }
lupopedia.footer:
  last_verified: "20260403202128"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
  orchestrator: "hephaestus:root"
  lilith_audit: "LILITH Audit: Installer Requirements PRD (01_installer_requirements.md)"
---

# Installer Requirements (4.0.93+)

## Purpose
This PRD defines the installer requirements for Lupopedia, ensuring compliance with the root constitutional system requirements and maximum compatibility with shared hosting environments.

---

## 1. Shared Hosting Compatibility
- Installer must run on shared hosting with no root access.
- No server-level dependencies or configuration changes.
- No requirement for composer, npm, or external package managers.
- No background daemons or cron jobs beyond standard PHP cron.

## 2. Subdirectory Installation
- Installer must support and default to subdirectory installation.
- Must not modify or assume control of the document root or parent directories.
- All generated paths must respect `LUPOPEDIA_PUBLIC_PATH`.

## 3. Database Setup
- Must not require database privileges beyond CREATE/INSERT/UPDATE/DELETE.
- Must not create foreign keys, triggers, functions, or procedures.
- Must not use AUTO_INCREMENT or UNSIGNED.
- All primary keys must be generated using `IdGenerator::generate()`.
- All timestamps must be `BIGINT` UTC `YYYYMMDDHHIISS` (no display width on integer types; see `00_root_constitutional_system_requirements.md` §3.5 and §9.7).
- Must use database-neutral SQL compatible with MySQL 8.0+ and PostgreSQL 15+.

### 3.1 Schema and seed files (4.0.93+)

**4.0.x install model (binding):** There is **no** Lupopedia→Lupopedia upgrade or migration chain. Any schema change is **merged into** `install_new_lupopedia.sql` (and seed/import SQL as needed). Getting a new schema means **dropping Lupopedia tables** and running a **fresh** wizard install—or fresh install plus **Crafty Syntax 3.7.5** tables and **`import_from_old_crafty_syntax.sql`**. **4.1.0** (see **PRD 33**) is when auto-installer–grade **Crafty→Lupopedia** and later **Lupopedia→Lupopedia** stories may apply; until then, treat every 4.0.x environment as **install from current DDL**, not `ALTER` in place.

- **DDL:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` uses `{{prefix}}` placeholders; the installer replaces them with `LUPO_TABLE_PREFIX` at runtime.
- **Consolidated seed:** After DDL, the wizard runs `install/seed_lupopedia_4_1_0.sql` (single file). Source fragments remain in `lupo-database/lupopedia/mysql/seed/` for history and regeneration; rebuild with `lupo-scripts/build_consolidated_seed_4_1_0.py` when those sources change.
- **Crafty transition (only supported data path in 4.0.x besides empty DB):** When moving from **Crafty Syntax 3.7.5**, `import_from_old_crafty_syntax.sql` runs after schema + seed. This is **not** a Lupopedia→Lupopedia upgrade.
- **Additional post-seed SQL:** Optional seeds (e.g. Anubis queue tables) may run after the consolidated seed; see root `install.php` and CHANGELOG for current list.

### 3.2 Implementation paths (verified 2026-03-30)
- **Entry points:** The install wizard is **`/install.php`** with helpers in **`/install_wizard_classes.php`** at the **repository root** (not `install/install.php`). SQL runner class **`InstallWizardSqlRunner`** lives in `install_wizard_classes.php` (no separate file by that class name).
- **Crafty import file:** `lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` (uses `{{prefix}}` for Lupopedia tables).
- **Detail:** See `/lupo-docs/versions/4.0.93/WHAT_TO_DO_NEXT.md` §14 for read-only verification notes and edge cases.

## 3.3 Database Introspection Limitations

### INFORMATION_SCHEMA Prohibition

The installer MUST NOT query `INFORMATION_SCHEMA` or any database system tables.

**Why this is prohibited:**

| Reason | Explanation |
|--------|-------------|
| **Limited Privileges** | Shared hosting database users typically have access ONLY to their assigned database |
| **Cross-Platform** | PostgreSQL uses `pg_catalog`, not `information_schema` |
| **Security Doctrine** | Least privilege principle - installer should not need system table access |
| **Hosting Restrictions** | Many shared hosts explicitly block `information_schema` access |

### Allowed Introspection

The ONLY allowed schema introspection is:

```sql
-- Check if a table exists (MySQL/PostgreSQL compatible)
SHOW TABLES LIKE '{{prefix}}table_name';
```

**For PostgreSQL compatibility**, use application logic instead of system tables:
- Track installation state in `lupo_schema_migrations` 
- Use `CREATE TABLE IF NOT EXISTS` where appropriate
- Never query `pg_catalog` or `information_schema` 

### Example: What NOT to Do

```sql
-- ❌ PROHIBITED: Querying INFORMATION_SCHEMA
SELECT COUNT(*) FROM information_schema.tables 
WHERE table_schema = DATABASE() AND table_name = 'lupo_actors';

-- ❌ PROHIBITED: MySQL system tables
SELECT * FROM mysql.user WHERE user = 'lupo_user';

-- ❌ PROHIBITED: PostgreSQL system tables
SELECT COUNT(*) FROM pg_tables WHERE tablename = 'lupo_actors';
```

### Example: What TO Do

```sql
-- ✅ ALLOWED: Check if table exists via application logic
SHOW TABLES LIKE '{{prefix}}actors';

-- ✅ ALLOWED: Use CREATE IF NOT EXISTS
CREATE TABLE IF NOT EXISTS {{prefix}}actors (
    actor_id BIGINT NOT NULL,
    -- ... columns ...
    PRIMARY KEY (actor_id)
);

-- ✅ ALLOWED: Track migrations
INSERT INTO {{prefix}}schema_migrations (version, applied_ymdhis) 
VALUES ('4.0.93', {{current_utc}});
```

## 3.4 Database Privilege Limitations

### Minimum Required Privileges

| Privilege | Required | Notes |
|-----------|----------|-------|
| CREATE | Yes | Initial table creation |
| INSERT | Yes | Seed data insertion |
| UPDATE | Yes | Runtime operations |
| DELETE | Yes | Soft delete operations |
| SELECT | Yes | Reading data |
| DROP | No | Never used in runtime |
| ALTER | No | Fresh install only |
| INDEX | No | Handled in CREATE TABLE |
| REFERENCES | No | No foreign keys |
| TRIGGER | No | Prohibited by doctrine |

### Prohibited Operations

The installer MUST NOT attempt:

```sql
-- ❌ PROHIBITED: Accessing other databases
SELECT * FROM mysql.user;
SELECT * FROM information_schema.tables WHERE table_schema = 'mysql';

-- ❌ PROHIBITED: Creating or altering users
CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON *.* TO 'newuser'@'localhost';

-- ❌ PROHIBITED: Accessing system tables
SHOW DATABASES;
SELECT * FROM pg_catalog.pg_tables;
```

**Rationale**: The installer only needs access to the single database it is installing into. Any attempt to access system tables or other databases violates the least privilege doctrine and will fail on shared hosting.

## 4. PHP Compatibility
- Installer must run on PHP 7.4 through 8.6 (latest).
- Namespaces are allowed (PHP 5.3+).
- No frameworks (Laravel, Symfony, etc.), no Composer, no Docker.
- All required libraries must be bundled in the codebase (e.g., lupo-includes/), not installed via Composer or external package managers.
- Must not use strict types, typed properties, arrow functions, enums, or attributes.

## 5. Enforcement
- Installer must validate its own compliance with the root constitutional system requirements.
- Any violation is a constitutional error and must be corrected immediately.

---

## LILITH Audit: Installer Requirements PRD (01_installer_requirements.md)

### What's Correct ✅

| Element | Status |
|---------|--------|
| Shared hosting compatibility | ✅ Well-defined |
| Subdirectory installation | ✅ Required and documented |
| Database privilege restrictions | ✅ CREATE/INSERT/UPDATE/DELETE only |
| No foreign keys, triggers, stored procedures | ✅ Constitutional compliance |
| No AUTO_INCREMENT, UNSIGNED | ✅ Constitutional compliance |
| BIGINT timestamps | ✅ Constitutional compliance |
| Database-neutral SQL | ✅ MySQL/PostgreSQL compatible |
| {{prefix}} placeholder system | ✅ Documented |
| Consolidated seed file | ✅ Documented with build script |
| PHP 7.4–8.6 compatibility | ✅ Correct range |
| No frameworks, Composer, Docker | ✅ Enforced |

### LILITH Findings

```yaml
findings:
  accuracy_score: 96
  constitutional_violations: []
  security_concerns: []
  bias_detected: no
  better_alternative_exists: No
  counter_proposal: null
  recommendations:
    - "ADD explicit prohibition against INFORMATION_SCHEMA queries"
    - "CLARIFY that installer only has access to configured database, not mysql system tables"
    - "ADD note about SHOW TABLES as only allowed schema introspection"
    - "ADD database privilege limitations section"
  verdict: approved_with_minor_corrections
```

### Key LILITH Corrections Applied

✅ **Added Section 3.3**: Database Introspection Limitations
- Explicitly prohibits INFORMATION_SCHEMA queries
- Documents allowed introspection methods (SHOW TABLES, CREATE IF NOT EXISTS)
- Explains cross-platform compatibility issues

✅ **Added Section 3.4**: Database Privilege Limitations  
- Clarifies minimum required privileges
- Lists prohibited operations
- Enforces least privilege doctrine

**LILITH Sign-off**: ✅ **Installer Requirements PRD APPROVED** with addition of sections 3.3 and 3.4 prohibiting `INFORMATION_SCHEMA` queries and clarifying database privilege limitations.

---

## Reference
See: [00_root_constitutional_system_requirements.md](00_root_constitutional_system_requirements.md)
