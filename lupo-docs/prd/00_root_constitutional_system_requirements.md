---
lupopedia.headers:
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/00_root_constitutional_system_requirements.md"
  federation_node_id: 0
  when_updated: "20260401000000"
  last_modified_utc: "20260401000000"
  channel_id: 42
  thread_id: "constitutional-root-requirements"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: doctrine
  artifact_kind: constitutional
  purpose: "Non-negotiable system-wide constitutional rules for Lupopedia. Overrides all other PRDs and doctrines."
  tags:
    - root
    - constitutional
    - doctrine
    - system_requirements
    - php56
    - shared_hosting
    - database_neutral
    - identity_model
lupopedia.edges:
  outbound_edges:
    - to: "lupo-rules/root/"
      type: references
      weight: 1.0
      reason: "All root rules are constitutional law; this PRD is one entry point into that directory"
    - to: "lupo-rules/root/WOLFIE_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "WOLFIE Doctrine incorporated as constitutional requirement in section 14 and section 9.20"
    - to: "lupo-rules/root/DATABASE_NEUTRAL_SQL_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Database neutrality rules mandated in section 3.6"
    - to: "lupo-rules/root/php-5-6-compatibility.md"
      type: references
      weight: 1.0
      reason: "PHP 5.6 minimum compatibility rules mandated in section 4"
    - to: "lupo-docs/channels/doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Subdirectory installation doctrine mandated in section 2"
    - to: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Five-layer identity model defined in section 5"
    - to: "lupo-docs/doctrine/CASCADE_FALLBACK_DOCTRINE.md"
      type: references
      weight: 0.9
      reason: "Fallback doctrine referenced in section 14.5"
    - to: "lupo-docs/doctrine/DEPENDENCY_DOCTRINE.md"
      type: references
      weight: 0.9
      reason: "Dependency doctrine referenced in section 14.5"
    - to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
      type: references
      weight: 1.0
      reason: "Canonical DDL must comply with all section 3 database constitutional rules"
    - to: "lupo-includes/classes/IdGenerator.php"
      type: implements
      weight: 1.0
      reason: "Enforces section 3.2 — all primary keys generated via IdGenerator::generate()"
    - to: "lupo-includes/classes/DatabaseFactory.php"
      type: implements
      weight: 1.0
      reason: "Enforces section 3 — all DB access must go through DatabaseFactory::getConnection()"
    - to: "lupo-agents/"
      type: references
      weight: 1.0
      reason: "Agent definition model dependency — section 6.1 file-based agent doctrine"
    - to: "lupo-tests/unit/id_generation_compliance_test.php"
      type: references
      weight: 0.9
      reason: "Test suite validating section 3.2 IdGenerator compliance"
    - to: "lupo-tests/regression/installer/"
      type: references
      weight: 0.9
      reason: "Regression tests validating section 9 installer constitutional rules"
    - to: "lupo-database/lupopedia/json/"
      type: references
      weight: 1.0
      reason: "TOON JSON files — authoritative column/type reference per section 9.9"
    - to: "lupo-docs/database/lupopedia/tables/active/"
      type: references
      weight: 1.0
      reason: "Table documentation generated from TOON exports — required reading before any SQL per section 9.9"
    - to: "lupo-docs/database/lupopedia/tables/semantic_navbar/"
      type: references
      weight: 0.9
      reason: "Semantic navbar table docs — folders, hashtags, references per section 9.9"
    - to: "lupo-scripts/generate_toon_files.py"
      type: references
      weight: 0.9
      reason: "Script that generates TOON JSON files from live database"
lupopedia.footer:
  last_verified: "20260401"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: "cursor:root"
  next_action:
    - "All new PRDs must declare an outbound edge to this file as their constitutional anchor"
    - "Add edges to CASCADE_FALLBACK_DOCTRINE and DEPENDENCY_DOCTRINE once those files are created"
    - "Add content_id once this file is imported via import_content.py"
---

# Root Constitutional System Requirements (4.0.93+)

## Purpose

This document defines the non-negotiable constitutional rules that govern the entire Lupopedia system.
These rules ensure:

- Compatibility with shared hosting
- Predictable behavior across unknown server configurations
- Maximum portability and zero reliance on server-level features
- Safe multi-agent operation
- Long-term maintainability
- Installer reliability (Softaculous, Installatron, manual installs)
- Subdirectory installation support

These rules override all other PRDs, doctrines, and implementation details.

**All doctrine and PRD files must reference this file as their constitutional anchor using an outbound edge.**

---

## 1. Shared Hosting Constraints (Mandatory)

Lupopedia must run on shared hosting where:

- The environment cannot be controlled
- Database permissions are limited
- No SUPER privileges exist
- No ability to create triggers, functions, or procedures
- No ability to modify server configuration
- No root access
- No custom extensions
- No guaranteed MySQL version beyond 8.0+
- No guaranteed PostgreSQL version beyond 15+

Therefore:

- All logic must be implemented in PHP
- No database-level logic is allowed
- No server-level dependencies
- No background daemons
- No cron requirements beyond standard PHP cron

**Implementation:** All business logic lives in `app/Services/` and `lupo-includes/classes/`. No stored procedures, triggers, or views may exist in `install_new_lupopedia.sql`.

---

## 2. Subdirectory Installation Doctrine

Lupopedia must always be installed inside a subdirectory, never the web root.

Example: `/public_html/lupopedia/`

Requirements:

- All routing must respect `LUPOPEDIA_PUBLIC_PATH`
- No hardcoded `/` root paths
- All JS/CSS includes must be subdirectory-aware
- The parent directory is not part of the project
- The installer must not assume control of the document root

**Implementation:** `index.php` defines `LUPOPEDIA_PUBLIC_PATH`. All URL construction must use this constant. See `lupo-docs/channels/doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md`.

---

## 3. Database Constitutional Rules

### 3.1 No Foreign Keys

Foreign keys are forbidden because:

- Shared hosting often blocks them
- They break portability and federation
- They break soft deletes and multi-agent repair workflows

All relationships must be enforced in the application layer.

**Implementation:** `install_new_lupopedia.sql` must contain zero `FOREIGN KEY` or `REFERENCES` clauses.

### 3.2 No AUTO_INCREMENT

Primary keys must be generated using `IdGenerator::generate()`.

This ensures:

- 63-bit signed-safe BIGINTs
- Timestamp-sortable IDs
- No reliance on DB sequences
- No race conditions
- No DB-specific behavior

**Implementation:** `lupo-includes/classes/IdGenerator.php`. All `INSERT` statements must call `IdGenerator::generate()` for the PK column before insertion. Never pass `null` or `0` as a PK expecting the DB to fill it.

**Test:** `lupo-tests/unit/id_generation_compliance_test.php`

### 3.3 No UNSIGNED

UNSIGNED is forbidden because PostgreSQL does not support it. It breaks database neutrality.

### 3.4 No TRIGGERS, FUNCTIONS, or PROCEDURES

These are forbidden because shared hosting often blocks them, they break portability, and they hide logic from the application layer.

**Implementation:** `install_new_lupopedia.sql` must contain zero `CREATE TRIGGER`, `CREATE FUNCTION`, or `CREATE PROCEDURE` statements.

### 3.5 Timestamp Format

All timestamps must be `BIGINT` in `YYYYMMDDHHIISS` UTC format. No `DATETIME`, `TIMESTAMP`, or timezone fields allowed.

**Implementation:** Use `gmdate('YmdHis')` in PHP. Never use `time()`, `date()`, or database-generated timestamps. Never add seconds directly to the integer value — use the `timestamp_ymdhis` helper class for arithmetic.

### 3.6 Database Neutral SQL

All SQL must run on MySQL 8.0+ and PostgreSQL 15+.

Forbidden SQL patterns:

- `ON DUPLICATE KEY UPDATE`
- `IF NOT EXISTS` in `CREATE TABLE`
- `SHOW TABLES`
- `REPLACE INTO`
- `UNSIGNED`
- `AUTO_INCREMENT`
- `ENGINE=` or `COLLATE=` clauses

**Implementation:** See `lupo-rules/root/DATABASE_NEUTRAL_SQL_DOCTRINE.md`.

---

## 4. PHP Compatibility Requirements

Lupopedia must run on PHP 5.6 minimum through the latest PHP (currently 8.6) maximum.

Allowed:

- Namespaces (PHP 5.3+)
- Bundled libraries (e.g., PHPMailer) included in `lupo-includes/`

Forbidden:

- Strict types (`declare(strict_types=1)`)
- Typed properties
- Arrow functions (`fn() =>`)
- Enums
- Attributes (`#[...]`)
- Named arguments
- Union types
- Match expressions
- Return type declarations in core paths
- Middleware patterns
- External frameworks (Laravel, Symfony, Zend, etc.)
- Composer dependency management
- Docker or container-only deployment

**Implementation:** All required libraries must be included directly in `lupo-includes/`. No `vendor/` directory. No `composer.json` in the project root. See `lupo-rules/root/php-5-6-compatibility.md`.

---

## 5. Identity Model Constitutional Rules

### 5.1 Agents

Agents are autonomous AI entities defined exclusively by files in `lupo-agents/<agent_id>/`. The database stores only runtime state — never definition content.

### 5.2 Actors

Actors are hybrid human/AI shells instantiated from agents. `actor_id` is the universal operational identity key. There is no `user_id` in relationships.

### 5.3 Auth Users

Auth users temporarily lease actors. Authentication must not be conflated with orchestration identity.

### 5.4 Actor Permission Rules

An auth_user may use an actor only if:

1. They created the actor
2. They are in department 0 (root)
3. They are in the same department as the actor

**Implementation:** `app/auth/AuthRoleResolver.php` enforces these rules. Actor identity for write operations is always resolved server-side — client-supplied `actor_id` in request bodies is never trusted.

See `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md` for the full five-layer model.

---

## 6. TOON File Protection (RULE 93.PROTECT_TOONS)

- Database is the source of truth
- TOON JSON files (`lupo-database/lupopedia/toon/*.toon.json`) are read-only reflections
- No system may write to TOON files
- No schema inference from TOON files

**Implementation:** TOON files are generated only by `lupo-scripts/generate_toon_files.py`. No application code may write to `lupo-database/lupopedia/toon/`.

---

## 6.1 Agent File Protection (RULE 93.PROTECT_AGENTS)

- Agent definitions are file-based in `lupo-agents/{agent_id}/` (source of truth)
- Database stores only runtime state and metrics
- No system may write to agent definition files
- Agent capabilities come from files, not database

**Implementation:** `lupo_agent_registry` table schema must be validated against the column list in section 9.17. Any code that writes agent capability or definition data to the database must be rejected.

---

## 7. Absolute-Root Pathing (RULE 93.PATH_PURITY)

All documentation links must start with `/` and never use `../`, `~/`, or relative paths.

**Implementation:** LUPOPEDIA HEADERS `web_path` must always include the `/lupopedia/` subdirectory prefix. Validators in `lupo-scripts/validate_lupopedia_headers_universal.py` enforce this.

---

## 8. Controlled Namespace Doctrine (RULE 93.CONTROLLED_NAMESPACES)

Namespaces ARE allowed, but ONLY under these constraints:

### 8.1 Namespace Requirements

Must begin with `Lupopedia\`:

```php
namespace Lupopedia\Actors;
```

### 8.2 Directory Mapping

Must map to directories inside `lupo-includes/`:

```
lupo-includes/Lupopedia/Actors/Actor.php
```

### 8.3 Forbidden Autoloading

- No PSR-4 autoloaders, Composer, vendor directory, or external autoloaders
- Autoloading must use Lupopedia's custom `spl_autoload_register()` implementation

### 8.4 Forbidden Namespace Patterns

`App\`, `Framework\`, `Symfony\`, `Laravel\`, `Illuminate\`, `Zend\`, `Psr\`

### 8.5 Forbidden Framework Patterns

Namespaces must NOT be used for routing, middleware, or DI containers.

---

## 9. Installer Constitutional Rules

- Must run on shared hosting
- Must not modify parent directories (except the config exception in section 9.13.2)
- Must not assume root access
- Must not require Composer or CLI tools
- Must not require database privileges beyond `CREATE`, `INSERT`, `UPDATE`, `DELETE`

**Implementation:** Entry point is `install.php`. All installer logic must be self-contained PHP. See `lupo-tests/regression/installer/` for regression coverage.

### 9.5 .htaccess Usage (RULE 93.SUBDIRECTORY_HTACCESS)

Allowed:
- `.htaccess` inside `/lupopedia/` directory only
- Rewrite rules scoped to Lupopedia subdirectory
- Fallback routing to `index.php`

Forbidden:
- Modifying parent directory's `.htaccess`
- Assuming `mod_rewrite` is enabled or `AllowOverride All` is set
- Rewrite rules outside your subdirectory

---

### 9.6 Filesystem Path Restrictions (RULE 93.NO_HARDCODED_PATHS)

No hardcoded filesystem paths. All paths must be derived from `LUPOPEDIA_PATH` or `LUPOPEDIA_ABSPATH`.

---

### 9.7 Primary Key Requirements (RULE 93.PK_FORMAT)

All primary keys MUST be bare `BIGINT` (no display width), generated via `IdGenerator::generate()`, in `YYYYMMDDHHIISS` + 4-digit sequence format. All reference fields must also be `BIGINT`.

Forbidden: `VARCHAR` PKs, composite PKs, `AUTO_INCREMENT`, UUID, `BIGINT(18)` with display width.

**Test:** `lupo-tests/unit/id_generation_compliance_test.php`

---

### 9.8 Soft Delete Pattern (RULE 93.SOFT_DELETE)

All soft deletes MUST use:

```sql
is_deleted TINYINT NOT NULL DEFAULT 0,
deleted_ymdhis BIGINT NOT NULL DEFAULT 0
```

All queries must filter `WHERE is_deleted = 0` by default. Never use hard `DELETE` on production rows.

---

### 9.9 Schema Inference Prohibition (RULE 93.NO_SCHEMA_INFERENCE)

**Agents and IDE tools MUST NEVER guess, infer, or assume column names, table names, or table structure.**

This is a hard constitutional rule. Guessing schema produces broken SQL, wrong column references, and silent data corruption that is extremely difficult to debug.

#### Forbidden inference sources:

- PHP arrays or variable names
- Model class property names
- Comments or docblocks
- Any PHP or Python code structure
- Memory of "similar" projects
- General knowledge of common column naming patterns
- PRD descriptions alone (PRDs describe intent, not schema)

#### Critical misconception — JSON files are NOT a file database

The TOON JSON files in `lupo-database/lupopedia/json/` are **schema reference documents**, not a file-based database. Lupopedia uses **MySQL** as its database. The JSON files exist solely so agents and tools can read column names, types, and indexes without parsing large SQL files or guessing. They must never be used as a data source, queried as if they were records, or treated as the system of record for any data.

#### Required sources — always consult before writing any SQL or table reference:

1. **Table documentation** — `lupo-docs/database/lupopedia/tables/active/<table_name>.md` — human-readable docs with column lists, types, indexes, and example queries. **Read this first.**
2. **TOON JSON files** — `lupo-database/lupopedia/json/<table_name>.json` — machine-readable schema generated from the live database by `lupo-scripts/generate_toon_files.py`. Contains fields, indexes, and primary key. **Contains no row data — structure only.**
3. **Install SQL** — `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — canonical DDL. Use for authoritative CREATE TABLE definitions when needed, but the table docs and JSON files are faster for column lookups.

#### Table documentation locations:

- `lupo-docs/database/lupopedia/tables/active/` — all active tables, one `.md` per table
- `lupo-docs/database/lupopedia/tables/semantic_navbar/` — semantic navbar tables (`lupo_folders`, `lupo_folder_map`, `lupo_hashtags`, `lupo_hashtag_map`, `lupo_references`, `lupo_reference_links`)
- `lupo-docs/database/lupopedia/tables/deprecated/` — deprecated tables (do not use for new code)

#### Workflow for any agent writing SQL or referencing a table:

1. Read `lupo-docs/database/lupopedia/tables/active/<table_name>.md` for the column list
2. If the table doc is missing, read `lupo-database/lupopedia/json/<table_name>.json` for the `fields` array
3. If neither exists, the table may not exist — do NOT create ad-hoc SQL; follow section 9.18 (Missing Table Protocol)
4. Write SQL using only confirmed column names from those sources
5. Never substitute a guessed column name even if it "seems obvious"

**Rationale:** The table prefix is dynamic (`LUPO_TABLE_PREFIX`), primary keys are deterministic BIGINTs, and column names are project-specific and do not follow generic conventions. A single wrong column name silently returns no rows or corrupts data with no error message. The TOON files and table docs exist precisely to eliminate this risk.

---

### 9.10 ASCII Safety (RULE 93.ASCII_SAFETY)

All filenames must be ASCII-only. No UTF-8 BOM in PHP files. No Unicode in class names, directory names, or filenames.

---

### 9.11 No Symlinks (RULE 93.NO_SYMLINKS)

No symbolic links allowed anywhere in the codebase.

---

### 9.12 Database Engine Neutrality (RULE 93.DB_ENGINE_NEUTRALITY)

No `ENGINE=`, `COLLATE=`, or `CHARACTER SET` clauses in any SQL. Database engine and collation must be left to the host.

---

### 9.13 Installer Sandbox (RULE 93.INSTALLER_SANDBOX)

#### 9.13.1 General Sandbox Restrictions

The installer may only write files inside the Lupopedia installation directory, except for the config exception below.

#### 9.13.2 Secure Configuration Exception

The installer may attempt to write `../lupopedia-config.php` (one directory above web root) only if the directory is writable and a safe write test passes first.

#### 9.13.3 Fallback Behavior (Mandatory)

If the installer cannot write above the web root, it must write `lupopedia-config.php` inside the Lupopedia directory, continue normally, and warn the user.

**Implementation:** `install.php` must implement the write-test-then-fallback pattern. `lupo-tests/regression/installer/` must cover both paths.

---

### 9.14 Dynamic Table Prefix (RULE 93.DYNAMIC_TABLE_PREFIX)

All tables MUST use a dynamic prefix from `lupopedia-config.php`.

- Installer MUST define `LUPO_TABLE_PREFIX`
- All PHP MUST use `LUPO_TABLE_PREFIX . 'tablename'`
- All installer SQL MUST use `{{prefix}}` placeholders
- All migration SQL MUST use `{{prefix}}` placeholders
- No SQL file may hardcode `lupo_`, `lp_`, or any fixed prefix

**Implementation:** Fallback pattern: `defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_'`

---

### 9.15 Directory Prefix (RULE 93.DIRECTORY_PREFIX)

All project directories MUST use the fixed prefix `lupo-`. Lowercase ASCII only. Not dynamic, not user-defined, not removable.

---

### 9.16 File-Based Agent Doctrine (RULE 93.FILE_BASED_AGENT_DOCTRINE)

Agents MUST be defined exclusively by files in `lupo-agents/<agent_id>/`.

Database stores only: `status`, `version`, `file_hash`, `file_signature`, `last_activated`, `last_error`, `uptime`, `health`, `mood`, `activation_state`, `pairing_state`.

Database MUST NOT store: skills, tools, memory rules, boundaries, faucets, system prompts, personality, philosophy, capabilities, constraints, or any definition content.

---

### 9.17 Agent Registry Schema (RULE 93.AGENT_REGISTRY_SCHEMA)

The table `<prefix>agent_registry` MUST contain only:

| Column | Purpose |
|--------|---------|
| `agent_id` | Primary key |
| `agent_code` | Short identifier |
| `agent_name` | Display name |
| `layer` | Orchestration layer |
| `is_kernel` | Kernel flag |
| `is_required` | Required flag |
| `version` | File version |
| `status` | Runtime status |
| `recommended_slot` | Slot hint |
| `lineage` | Agent lineage |
| `last_verified_ymdhis` | Last verification timestamp |
| `last_verified_by_actor_id` | Verifying actor |
| `file_hash` | File integrity hash |
| `file_signature` | File signature |

No definition fields may exist in this table.

---

### 9.18 Missing Table Protocol (RULE 93.MISSING_TABLE_PROTOCOL)

When a table needed for a feature does not exist in `install_new_lupopedia.sql`, the correct procedure is:

1. **Verify the table is truly missing** — check `lupo-database/lupopedia/json/<table>.json` and `install_new_lupopedia.sql`. If a TOON JSON exists, the table is in the live DB but missing from the install script.
2. **Create a SQL proposal file** at `lupo-database/lupopedia/mysql/migrations/add_<table_name>_YYYYMMDD.sql` containing the `CREATE TABLE` and `CREATE INDEX` statements using `{{prefix}}` placeholders.
3. **The SQL file is reviewed and applied** by updating `install_new_lupopedia.sql` directly — adding the `CREATE TABLE` block in the appropriate section.
4. **No data migration is needed** — there is no Lupopedia-to-Lupopedia upgrade path. All schema changes take effect on fresh install via `install_new_lupopedia.sql`.
5. **Update the TOON** — after the install SQL is updated, regenerate TOON files via `lupo-scripts/generate_toon_files.py` and create a table doc in `lupo-docs/database/lupopedia/tables/active/<table_name>.md`.

**Forbidden:**
- Creating tables via CLI (`mysql -u root -p < file.sql`) — see section 9.18
- Hardcoding the prefix in the SQL file — always use `{{prefix}}`
- Using `AUTO_INCREMENT` — use `IdGenerator::generate()` in PHP; the PK column is bare `BIGINT NOT NULL`
- Using `UNSIGNED`, `ENGINE=`, `COLLATE=`, `FOREIGN KEY`, triggers, or procedures

**SQL proposal file format:**

```sql
-- Table: {{prefix}}example_table
-- Purpose: [one line description]
-- Added: YYYYMMDD
-- Apply to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql

CREATE TABLE {{prefix}}example_table (
  example_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (example_id)
);
CREATE INDEX {{prefix}}example_table_idx_actor ON {{prefix}}example_table (actor_id);
CREATE INDEX {{prefix}}example_table_idx_is_deleted ON {{prefix}}example_table (is_deleted);
```

---

### 9.19 No Direct CLI Database Execution (RULE 93.NO_CLI_DB_EXEC)

**IDE agents, scripts, and contributors MUST NOT execute SQL directly against the database via CLI tools.**

#### Forbidden patterns:

```bash
# ALL of these are forbidden
mysql -u root -p < some_sql_file.sql
mysql -u root -p lupopedia < install.sql
mysql -u user -ppassword -e "ALTER TABLE lupo_actors ADD COLUMN ..."
mysqldump lupopedia > backup.sql | mysql lupopedia_new
psql -U postgres lupopedia < migration.sql
```

#### Why this is forbidden:

- CLI execution bypasses `LUPO_TABLE_PREFIX` — hardcoded table names in SQL files will create wrong tables or corrupt the wrong ones
- CLI execution bypasses `IdGenerator::generate()` — any `INSERT` with `AUTO_INCREMENT` or a hardcoded PK will produce non-deterministic, non-sortable IDs that break the system
- CLI execution bypasses the installer's write-test-then-fallback logic
- CLI execution bypasses all PHP-layer validation, soft-delete enforcement, and audit logging
- CLI execution is not portable — it assumes a local MySQL/PostgreSQL binary, a specific user, and a specific password, none of which are guaranteed on shared hosting
- CLI execution cannot be reviewed, rolled back, or tested through the standard test suite

#### Required pattern — all schema changes and data operations MUST go through:

1. **Schema changes:** Update `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`, then create a migration file in `lupo-database/lupopedia/mysql/migrations/dev_YYYYMMDD_description.sql`, then run it through the PHP migration runner or installer wizard
2. **Seed data:** Add to `lupo-database/lupopedia/mysql/seed/` and run through the installer
3. **One-time data fixes:** Write a PHP migration script in `lupo-database/lupopedia/mysql/migrations/` that uses `DatabaseFactory::getConnection()` and `IdGenerator::generate()`
4. **Install/upgrade:** Use `install.php` and its supporting wizard class — this is the only approved entry point for schema creation

#### The migration pattern:

```php
// CORRECT — PHP migration using DatabaseFactory and IdGenerator
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$id = IdGenerator::generate();
$db->insert($prefix . 'actors', array(
    'actor_id'       => $id,
    'actor_name'     => 'example',
    'created_ymdhis' => gmdate('YmdHis')
));
```

**Rationale:** The prefix system, deterministic PKs, and PHP-layer integrity checks only work when all database operations go through the application layer. A single raw CLI execution can silently corrupt the prefix mapping, create duplicate or invalid IDs, or insert rows that violate soft-delete conventions — with no audit trail and no rollback path.

---

### 9.20 Proven Code Preservation Doctrine (RULE 93.PROVEN_CODE)

**Agents MUST NOT propose replacing, rewriting, or "modernizing" working code solely because it is old.**

This rule exists because of a specific, recurring failure pattern: an agent encounters code written in 1999, assumes it is outdated, and proposes replacing it with a framework, library, or "modern" equivalent — introducing dependencies, complexity, and fragility into code that has been running without issues for 25+ years.

#### The Core Test

Before proposing any change to existing working code, an agent must answer:

1. **Is it broken?** If no — do not propose replacing it.
2. **Does it have a security vulnerability?** If no — do not propose replacing it.
3. **Does it use a deprecated browser/PHP API that actively breaks things?** If no — do not propose replacing it.
4. **Does the proposed replacement work on PHP 5.6, shared hosting, and without dependencies?** If no — the replacement is not acceptable regardless of how "modern" it is.

#### What "Deprecated" Actually Means Here

Not all deprecations are equal. Agents must distinguish:

| Type | Example | Action Required |
|------|---------|-----------------|
| Actively broken in current browsers/PHP | HTML framesets, `mysql_*` functions | Fix — these genuinely do not work |
| Deprecated but still functional | `document.write`, `XMLHttpRequest` | Leave alone unless there is a specific bug |
| "Deprecated" by framework opinion | jQuery patterns, callback-style JS | Irrelevant — Lupopedia does not use frameworks |
| "Old" but working perfectly | 1999 eye animation, dynlayer.js | Do not touch |

#### The Eye Animation Example (Canonical Reference)

The floating eye animation in Lupopedia was written in 1999 using `dynlayer.js` and GIF sprites. It:
- Has zero dependencies
- Works in every browser from Netscape 4 to Chrome 2026
- Has never had a bug report
- Requires no maintenance
- Is approximately 50 lines of JavaScript

When an agent encounters this code and suggests replacing it with a React component, a CSS animation library, or any npm package, that agent is in violation of this rule. The correct response is: **leave it alone**.

#### Forbidden Agent Behaviors

- Proposing `npm install`, `composer require`, or any package manager command to solve a problem that can be solved with vanilla PHP or JavaScript
- Suggesting a framework (React, Vue, Alpine, Livewire, etc.) for UI behavior that already works without one
- Rewriting working JavaScript as "modern ES6+" when the existing code runs everywhere
- Proposing to replace `XMLHttpRequest` with `fetch()` without providing a fallback for environments where `fetch` is unavailable
- Describing working code as "legacy," "outdated," or "needs modernization" without a specific, concrete defect to fix
- Suggesting CSS frameworks (Bootstrap, Tailwind) for styling that already works

#### What Agents Should Do Instead

- Read the existing code and understand why it works before suggesting changes
- If a genuine bug exists, fix the minimal amount of code needed to address it
- If a browser API is actively broken (not just deprecated), propose a fix with a fallback layer
- If new functionality is needed, write it in vanilla PHP/JS following the existing patterns
- Propose the simplest solution that works everywhere, not the most modern one

#### The Fallback Ladder Principle

When new functionality genuinely requires a choice between approaches, always build a fallback ladder:

```
Best modern path (works in 90% of environments)
    ↓ falls back to
Older compatible path (works in 99% of environments)
    ↓ falls back to
Universal baseline (works everywhere, always)
```

Never remove a lower rung of the ladder. The oldest rung is the most reliable.

**Reference:** `lupo-rules/root/WOLFIE_DOCTRINE.md` — read Section 1 before touching any code that predates 2010.

---

## 10. Enforcement

### 10.1 Constitutional Supremacy

All files in `lupo-rules/root/` are binding constitutional law and override all PRDs. Any conflict between PRDs and root rules must be resolved in favor of the root rules. Any violation is a constitutional error and must be corrected immediately.

### 10.2 Validation Tooling

| Rule | Validator |
|------|-----------|
| Section 3 database rules | `lupo-scripts/verify_db_against_toons.py` |
| Section 3.2 IdGenerator | `lupo-tests/unit/id_generation_compliance_test.php` |
| Section 4 PHP 5.6 compat | `php -l` + `lupo-scripts/run_unit_tests.sh` |
| Section 7 path purity | `lupo-scripts/validate_lupopedia_headers_universal.py` |
| Section 9 installer | `lupo-tests/regression/installer/` |
| Section 9.18 missing table protocol | SQL proposal file + install SQL update |
| Section 9.19 CLI prohibition | Code review — no automated scanner yet |
| Section 9.20 proven code preservation | Code review — agent must justify any change to pre-2010 code |
| Schema DDL | `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` |

---

## 11. Refinements

- All doctrine and PRD files must reference this file as the constitutional anchor using an outbound edge.
- All new PRDs must be reviewed for compliance with these requirements.
- Constitutional rules override all implementation details.
- These rules apply to all 4.0.x releases until explicitly revised.
- Any agent, IDE, or human contributor must treat this file as the highest authority.
- This file must be versioned and updated only by lead orchestration agents or with explicit review.

---

## 14. The WOLFIE Doctrine: Engineering for Survival

### 14.1 Constitutional Authority

The WOLFIE Doctrine (`lupo-rules/root/WOLFIE_DOCTRINE.md`) is hereby incorporated as a constitutional requirement for all agents operating within Lupopedia.

### 14.2 Binding Rules

| Rule | Description |
|------|-------------|
| W-01 | No dependency proposals without justification |
| W-02 | No removal of fallback layers |
| W-03 | Code that runs without you |
| W-04 | Complexity is the enemy |
| W-05 | Respect the proven |

### 14.3 The "Modernization" Prohibition

Agents SHALL NOT propose "modernizing" code that has been proven to work for 20+ years without:

1. Documenting why the existing code is insufficient
2. Explaining how the proposed solution maintains the same fallback layers
3. Demonstrating that the new solution works in the same environments (shared hosting, PHP 5.6-8.6, etc.)
4. Acknowledging that code that outran its author for 11 years is not "broken"

**Rationale:** The Crafty Syntax codebase (1999-2025) ran unattended for 10 years. This level of resilience is not "legacy" — it is proven architecture.

### 14.4 Framework Prohibition

Agents SHALL NOT propose adopting frameworks (Laravel, Symfony, React, Vue, etc.) unless the framework is absolutely required, can be optionally loaded without breaking core, the fallback path works without it, and shared hosting remains supported.

### 14.5 Reference

- Full doctrine: `lupo-rules/root/WOLFIE_DOCTRINE.md`
- Fallback doctrine: `lupo-docs/doctrine/CASCADE_FALLBACK_DOCTRINE.md`
- Dependency doctrine: `lupo-docs/doctrine/DEPENDENCY_DOCTRINE.md`
