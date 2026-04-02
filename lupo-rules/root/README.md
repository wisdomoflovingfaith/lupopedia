---
lupopedia.init:
  orchestrator_actor: "any"
  rule_set_version: "4.0.89+"
  applies_to: ["audit", "code-gen", "db-sync", "migration", "header-sync"]
  enforcement: strict

lupopedia.metadata:
  comment: "Comprehensive index of all root rules and doctrines for Lupopedia development"

lupopedia.headers:
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  lupopedia.schema: index
  file_path_from_root: "lupo-rules/root/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-rules/root/README.md"
  federation_node_id: 0
  last_modified_utc: "20260327220000"
  artifact_type: "rules_index"
  artifact_kind: "root_rules"
  purpose: "Complete index and explanation of all root rules, doctrines, and development constraints for Lupopedia"
  tags: ["root", "rules", "doctrine", "constraints", "development"]

lupopedia.footer:
  last_verified: "20260327220000"
  last_verified_by: "wolfie"
  orchestrator: "wolfie:root"
  next_action:
    - "Propagate rule updates to all IDE agents via propagate_agent_rules.php"
    - "Ensure all new development follows these rules"
---
# file: Root Rules Index — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: [http://www.lupopedia.com/lupo-rules/root/](http://www.lupopedia.com/lupo-rules/root/)

# 📋 Lupopedia Root Rules & Doctrines

**Complete guide to all development rules, constraints, and doctrines for Lupopedia**

---

## ⚠️ **IMPORTANT: Binding Philosophy**

### **[INDEPENDENT_CODERS_MANIFESTO.md](INDEPENDENT_CODERS_MANIFESTO.md)**
**Status:** 🎯 ETERNAL - Core Philosophy  
**Scope:** All development decisions and architectural choices  
**Enforcement:** Absolute - This is WHY we build this way

**Key Principles:**
- **No dependencies** - Everything needed is in the repo
- **No frameworks** - Raw PHP, raw SQL, you know every line
- **Universal deployment** - FTP works everywhere
- **PHP 5.6+ compatibility** - Works on any server
- **Your code, your rules** - No vendor lock-in

**This manifesto explains WHY these rules exist. Read it first.**

---

### **[DATABASE_DOCTRINE.md](DATABASE_DOCTRINE.md)**
**Status:** 🔒 LOCKED - Binding Authority  
**Scope:** All database work, all tables, all queries  
**Enforcement:** Strict - No exceptions, no "modern approaches"

**Key Requirements:**
- **No database-generated IDs** - Application-layer only
- **Cross-platform SQL** - MySQL AND PostgreSQL compatibility
- **Timestamp format** - BIGINT UTC YYYYMMDDHHIISS
- **PK naming** - `[table]_id` format, never just `id`
- **Production pattern** - Catch-and-retry with exponential backoff

---

## 🐺 The WOLFIE Doctrine (READ THIS FIRST)

### **[WOLFIE_DOCTRINE.md](WOLFIE_DOCTRINE.md)**
**Status:** ⚡ CONSTITUTIONAL - Binding on ALL Agents  
**Scope:** All development decisions, especially regarding "modernization"  
**Enforcement:** Absolute - Overrides any framework/modernization suggestions

**Key Philosophy:**
- **Fallback Over Dependency** - Build ladders, not single paths
- **Survival Without You** - Code must run unattended for years
- **Dependency is Debt** - Every external package is a liability
- **The Cascade Fallback** - Start with what works everywhere
- **Single Source of Truth** - Complexity is the enemy

**Binding Rules:**
- **W-01:** No dependency proposals without justification
- **W-02:** No removal of fallback layers
- **W-03:** Code must run without you
- **W-04:** Complexity is the enemy
- **W-05:** Respect the proven (don't assume 25-year-old code needs "modernization")

**Before you suggest ANY change, especially to code written 20+ years ago, READ THIS DOCTRINE.**

The code you're looking at outran its author for 11 years. That's not luck - that's architecture.

---

## 🧱 Constitutional Rules (Primary Authority)

### **[LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md](LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md)**
**Status:** ✅ ACTIVE - Absolute Authority  
**Scope:** All development, all agents, all systems  
**Enforcement:** Strict - Overrides all other rules

**Key Requirements:**
- **Database Doctrine:** No foreign keys, triggers, stored procedures, or auto-increment
- **Time Format:** All timestamps as BIGINT UTC in YYYYMMDDHHIISS format
- **Primary Keys:** Must be named `[tablename_singular]_id` (e.g., `actor_id`)
- **ID Generation:** Application-layer only, deterministic allocation

---

## 🚫 Development Constraints (Critical)

### **[PHP_VERSION_COMPATIBILITY.md](PHP_VERSION_COMPATIBILITY.md)**
**Purpose:** Ensure PHP 5.6+ compatibility for shared hosting  
**Forbidden:** PHP 7+ features (`??`, `<=>`, type hints, anonymous classes)  
**Required:** Polyfills for `random_bytes()`, `random_int()`

### **[NO_COMPOSER_DOCTRINE.md](NO_COMPOSER_DOCTRINE.md)**
**Forbidden:** Composer, `vendor/` directory, `composer.json`  
**Permitted Libraries:** Self-contained libraries in `lupo-includes/` are allowed, but subject to the rules in **[EXTERNAL_LIBRARIES_DOCTRINE.md](EXTERNAL_LIBRARIES_DOCTRINE.md)**

### **[NO_FRAMEWORK_DOCTRINE.md](NO_FRAMEWORK_DOCTRINE.md)**
**Forbidden:** Laravel, Symfony, CodeIgniter frameworks  
**Forbidden:** Blade templates (`@extends`, `@section`, `{{ }}`)  
**Required:** Pure PHP with manual includes

### **[SHARED_HOSTING_DOCTRINE.md](SHARED_HOSTING_DOCTRINE.md)**
**Requirements:** Must work in subdirectories, no shell commands, 64MB memory limit  
**Path Handling:** Use `LUPOPEDIA_PUBLIC_PATH` constant for all URLs  
**Forbidden:** `shell_exec()`, `system()`, root directory writes

### **[EXTERNAL_LIBRARIES_DOCTRINE.md](EXTERNAL_LIBRARIES_DOCTRINE.md)**
**Permitted:** Self-contained libraries in `lupo-includes/{library-name}/`  
**Examples:** PHPMailer (approved), TCPDF, SimplePie  
**Required:** Manual inclusion with `require_once`

---

## 🤝 Multi-Agent Coordination

### **[MULTI_AGENT_COORDINATION_DOCTRINE.md](MULTI_AGENT_COORDINATION_DOCTRINE.md)**
**Purpose:** Deterministic coordination for all agents  
**Scope:** 108+ registered agents, 11 primary personas  
**Key Rules:**
- Agent registration required for all operations
- Channel-based communication in `lupo-channels/42/`
- Single task ownership in root `TODO.md`
- UTC filename format: `YYYYMMDD_HHMMSS_ACTOR_purpose_TITLE.md`

### **[CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md](CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md)**
**Communication:** All coordination via channel artifacts  
**Structure:** `lupo-channels/{channel_id}/{type}/{filename}.md`  
**Types:** `broadcasts/`, `threads/`, `direct/`, `rules/`, `tasks/`, `content/`

---

## 📊 Database & Data Rules

### **[CONVERGENCE_DOCTRINE.md](CONVERGENCE_DOCTRINE.md)**
**Principle:** Single canonical system state  
**Rules:** No variant actors, banned entities remain addressable, actor identity permanence

### **[TIMESTAMP_DOCTRINE.md](TIMESTAMP_DOCTRINE.md)**
**Format:** UTC BIGINT in YYYYMMDDHHIISS format  
**Validation:** Hours must be 00-23, filenames must be deterministic  
**Enforcement:** Invalid timestamps rejected

### **[TIMESTAMP_FORMAT_ENFORCEMENT.md](TIMESTAMP_FORMAT_ENFORCEMENT.md)**
**⚠️ CRITICAL:** Lupopedia does NOT use Unix time  
**Format:** BIGINT UTC YYYYMMDDHHIISS (NOT time())  
**Enforcement:** Strict - Pre-commit hooks reject time() calls  
**Requirements:** Mandatory header comments in all timestamp-handling files

### **[CONFIGURATION_DOCTRINE.md](CONFIGURATION_DOCTRINE.md)**
**Purpose:** Configuration file location and security  
**Search Order:** Above web root → above installation → in installation  
**Security:** Config file must NOT be web-accessible  
**Generation:** `python lupo-scripts/generate_toon_files.py`

### **[TOON_SOURCE_OF_TRUTH.md](TOON_SOURCE_OF_TRUTH.md)**
**Authority:** TOON files are read-only database reflections  
**Source:** Database is authoritative, files are secondary  
**Generation:** `python lupo-scripts/generate_toon_files.py`

### **[SAFE_DATABASE_OPERATIONS_DOCTRINE.md](SAFE_DATABASE_OPERATIONS_DOCTRINE.md)**
**Access:** Use PDO_DB wrapper only, no direct PDO  
**Transactions:** Proper begin/commit/rollback patterns  
**Safety:** Parameter binding to prevent injection

### **[DATABASE_NEUTRAL_SQL_DOCTRINE.md](DATABASE_NEUTRAL_SQL_DOCTRINE.md)**
**Purpose:** All SQL must work on both MySQL and PostgreSQL  
**Forbidden:** `UNSIGNED`, `DATETIME`, `AUTO_INCREMENT`, MySQL-specific syntax  
**Required:** `BIGINT` timestamps, application-layer IDs, `DatabaseFactory` class

### **[DATABASE_DOCTRINE.md](DATABASE_DOCTRINE.md)**
**Purpose:** Canonical database rules for Lupopedia  
**Scope:** Naming conventions, ID generation, timestamps, forbidden features  
**Authority:** WOLFIE (actor_id 1) - LOCKED and binding  
**Key Rules:** `[table]_id` naming, timestamp-based IDs, no database logic

### **[../../lupo-docs/doctrine/WOLFIE_WAY_MYTHOLOGY_DOCTRINE.md](../../lupo-docs/doctrine/WOLFIE_WAY_MYTHOLOGY_DOCTRINE.md)**
**Purpose:** Explains the WOLFIE Way mythology and philosophy  
**Scope:** All development philosophy and decision-making  
**Key Concept:** "Build systems that outlive their builders"  
**Author:** WOLFIE (actor_id 1) - Core philosophy

---

### **[../../lupo-docs/doctrine/MULTI_AGENT_5W1H_DOCTRINE.md](../../lupo-docs/doctrine/MULTI_AGENT_5W1H_DOCTRINE.md)**
**Purpose:** Defines 5W1H framework with multi-agent adversarial collaboration  
**Scope:** All development, documentation, and decision-making  
**Key Concept:** Disagreement drives better solutions through structured conflict  
**Author:** LILITH (actor_id 2) - Definitive working model

---

### **[../../lupo-docs/doctrine/DOCUMENTATION_AS_DATA_DOCTRINE.md](../../lupo-docs/doctrine/DOCUMENTATION_AS_DATA_DOCTRINE.md)**
**Purpose:** Clarifies that files and database are two views of the same documentation  
**Scope:** All documentation, database queries, IDE agent behavior  
**Key Concept:** `lupo_contents` IS the documentation, not metadata about files  
**Author:** LILITH (actor_id 2) - Critical clarification  

---

## 📚 Documentation Architecture

### **[../../lupo-docs/doctrine/DOCUMENTATION_ARCHITECTURE.md](../../lupo-docs/doctrine/DOCUMENTATION_ARCHITECTURE.md)**
**Purpose:** The complete 5W1H framework across all documentation layers  
**Scope:** PRDs, implementations, versions, channels, code, tests  
**Key Concepts:** Headers (current state), Threads (immutable WHY), Edges (WHERE), Content (WHAT)  
**Author:** LILITH (actor_id 2) - Canonical explanation  

---

## 🏗️ Architecture & Validation

### **[LUPOPEDIA_HEADERS_DOCTRINE.md](LUPOPEDIA_HEADERS_DOCTRINE.md)** (single source of truth)
**Purpose:** The **only** binding LUPOPEDIA HEADERS doctrine — all header fields, taxonomy, validation, and DB projection  
**Scope:** Schema taxonomy, artifact types, federation nodes, validation rules, **`lupo_contents` / `lupo_metadata` / `lupo_edges` / `revision_history` mapping** (import + regenerate)  
**Authority:** WOLFIE (actor_id 1) - LOCKED and binding  
**Key Rules:** Header field definitions, canonical schema values, `content_id` / DB-first workflow, validation requirements  
**Alias:** [`lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md`](../../lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md) — bookmark path only; must not diverge  
**Companion (format / tooling):** [`lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`](../../lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md)

### **[RULE_FILES_HEADER_REQUIREMENT.md](RULE_FILES_HEADER_REQUIREMENT.md)**
**Purpose:** Meta-rule requiring headers on ALL rule files  
**Requirement:** ALL files in `lupo-rules/root/` MUST have complete LUPOPEDIA headers  
**Enforcement:** Automated validation with immediate rejection for non-compliance automatically  
**Validation:** Structured header format required

### **[FOOTER_VERSION_MANAGEMENT_RULE.md](FOOTER_VERSION_MANAGEMENT_RULE.md)**
**Purpose:** Guidelines for managing version information in footer  
**Problem:** Version field creates maintenance churn  
**Solution:** Use `when_created` for file creation, `version` only for releases headers (v4.0.84+)  
**Validation:** Automated via `validate_headers.py` script  
**Enforcement:** Files without headers are rejected automatically  
**Validation:** Structured header format required

### **[FILE_BOUNDARY_VALIDATION_RULE.md](FILE_BOUNDARY_VALIDATION_RULE.md)**
**Validation:** All files must pass boundary validation  
**Checks:** Header completeness, timestamp validity, content requirements  
**Enforcement:** Invalid files rejected

---

## 🔄 Migration & Versioning

### **[MIGRATION_DOCTRINE.md](MIGRATION_DOCTRINE.md)**
**Process:** Single upgrade path: Crafty Syntax 3.7.5 → Lupopedia 4.0.x  
**No Upgrades:** No Lupopedia → Lupopedia upgrades until 4.1.0  
**Schema:** All schema in single install file

### **[VERSIONING_DOCTRINE_SINGLE_SOURCE.md](VERSIONING_DOCTRINE_SINGLE_SOURCE.md)**
**Authority:** Single source of version truth  
**Location:** `lupo-docs/versions/{version}/`  
**Process:** Version-driven development, thread consolidation

### **[SINGLE_INSTALL_NO_4.0_UPGRADE_DOCTRINE.md](SINGLE_INSTALL_NO_4.0_UPGRADE_DOCTRINE.md)**
**Rule:** No in-place upgrades between 4.0.x versions  
**Migration:** Fresh install required for major version changes

---

## 📋 Task Planning & Execution

### **[TASK_PLANNING_DOCTRINE.md](TASK_PLANNING_DOCTRINE.md)**
**TODO.md:** Root TODO.md is single source of task truth  
**Ownership:** Each task has single owner  
**Status:** Updated in owning file only

---

## 🔧 Technical Implementation

### **[PDO_DB_DATABASE_ACCESS_DOCTRINE.md](PDO_DB_DATABASE_ACCESS_DOCTRINE.md)**
**Database Access:** Use PDO_DB wrapper class only  
**Forbidden:** Direct PDO, mysqli, new PDO()  
 **Required:** `DatabaseFactory::getConnection()`

### **[RESERVED_ID_DOCTRINE.md](RESERVED_ID_DOCTRINE.md)**
**Reserved Ranges:** System IDs, human actor IDs (1000+), agent IDs  
**Allocation:** Registry-based deterministic allocation  
**Constraints:** No auto-increment, no UUID

### **[PK_REFERENCE_NAMING_DOCTRINE.md](PK_REFERENCE_NAMING_DOCTRINE.md)**
**Foreign Key Fields:** Must reference `[table]_id`  
**Naming Convention:** Consistent singular table name + `_id`  
**Examples:** `actor_id`, `session_id`, `channel_id`

---

## 🚨 Security & Validation

### **[LUPOPEDIA_UPGRADE_POLICY.md](lupopedia_upgrade_policy.md)** ⚠️ CRITICAL
**Rule**: 🚫 NO Lupopedia 4.0.x → 4.0.x upgrades until 4.1.0  
**Approved Paths**: Fresh installs OR Crafty Syntax 3.7.5 → 4.0.x  
**Migration Policy**: One-time changes via `lupo-tmp/run_*.php` only  
**Install SQL**: ALL changes must be in `install_new_lupopedia.sql`

### **[DATABASE_SECURITY_POLICY.md](database_security_policy.md)** ⚠️ CRITICAL
**Rule**: 🚫 COMMAND LINE MYSQL ACCESS IS FORBIDDEN  
**Reason**: Prevents AI agent database corruption  
**Approved Methods**:
- New installs: `/install.php` (PHP-based with validation)
- Migrations: `/lupo-tmp/run_*.php` (admin access required)
- Debug: `/lupo-tests/debug_*.php` (read-only diagnostics)
**File Organization**:
- `lupo-tmp/` - One-time migration scripts
- `lupo-tests/` - Debug and validation tools
- `lupo-scripts/` - Reusable utilities

### **[LILITH_NONINTERFERENCE_DOCTRINE.md](LILITH_NONINTERFERENCE_DOCTRINE.md)**
**Role:** LILITH as non-interfering critic/reviewer  
**Scope:** Review only, no execution interference  
**Artifact Types:** Review, gap analysis, audit reports

### **[VALIDATION_RULES/](validation_rules/)**
**Rules:** Input validation, output validation, boundary checks  
**Implementation:** PHP 5.6+ compatible validation functions  
**Security:** XSS prevention, SQL injection prevention

---

## 🗂️ Organization & Structure

### **[IDE_AGENT_IDENTITY_ACTOR_PAIRING_DOCTRINE.md](IDE_AGENT_IDENTITY_ACTOR_PAIRING_DOCTRINE.md)**
**Pairing:** IDE faucets paired with system actors  
**Examples:** Cursor (102) ↔ WOLFIE (1), Windsurf (101) ↔ HERMES (15)  
**Registry:** Actor registry defines all pairings

### **[SYSTEM_RULES/](system_rules/)**
**Scope:** System-level rules and constraints  
**Implementation:** Core system behaviors  
**Enforcement:** System-level validation

---

## 📚 Documentation & Headers

### **LUPOPEDIA Headers Format**
**Required Fields:** `lupopedia.headers`, `lupopedia.footer`, `lupopedia.edges`  
**Version:** 4.0.84+ format required  
**Validation:** Automated header validation

---

## 🔄 Subdirectories (Specialized Rules)

### **Actor Rules: [actor_rules/](actor_rules/)**
**Scope:** Actor-specific rules and constraints  
**Implementation:** Individual actor behavior rules

### **Execution Rules: [execution_rules/](execution_rules/)**
**Scope:** Task execution patterns and procedures  
**Implementation:** How agents execute work

### **Facet Rules: [facet_rules/](facet_rules/)**
**Scope:** Multi-faceted development rules  
**Implementation:** Cross-cutting concerns

---

## 📋 Quick Reference Checklist

### Before Writing Code:
- [ ] PHP 5.6+ compatible (no PHP 7+ features)
- [ ] No Composer dependencies
- [ ] No framework code (Laravel, Symfony)
- [ ] Use `lupo-includes/` for external libraries
- [ ] Follow database doctrine (no FK, no triggers)
- [ ] Use UTC timestamps in YYYYMMDDHHIISS format

### Before Creating Database Changes:
- [ ] No foreign keys
- [ ] No auto-increment
- [ ] Primary key named `[table]_id`
- [ ] Use BIGINT for IDs
- [ ] Application-layer ID generation

### Before Creating Artifacts:
- [ ] Complete LUPOPEDIA headers
- [ ] Valid UTC timestamp (hour 00-23)
- [ ] Proper filename format
- [ ] Channel-based communication

### Before Adding External Libraries:
- [ ] Place in `lupo-includes/{library-name}/`
- [ ] No Composer files
- [ ] Manual inclusion only
- [ ] PHP 5.6+ compatible

---

## 🚀 Enforcement

### Automated Checks:
```bash
# PHP version compatibility
php -l filename.php

# Check for Composer violations
find . -name "composer.json" -o -name "vendor" -type d

# Check for framework violations
grep -r "@extends\|@section\|{{ " --include="*.php"

# Validate headers
python lupo-scripts/validate_headers.py
```

### Manual Review:
- LEXA: Security and boundary enforcement
- ANUBIS: Data integrity and orphan resolution
- SESHAT: Content review and validation
- LILITH: Audit and gap analysis

---

## 📚 Further Reading

### Required Documentation:
1. **[AGENTS.md](../AGENTS.md)** - Agent identities and IDE faucet mapping
2. **[ONBOARDING.md](../ONBOARDING.md)** - Development quick-start
3. **[lupo-docs/doctrine/](../lupo-docs/doctrine/)** - Complete doctrine collection
4. **[lupo-channels/](../lupo-channels/)** - Channel-based communication

### Version-Specific:
- **[lupo-docs/versions/](../lupo-docs/versions/)** - All version documentation
- **[TODO.md](../TODO.md)** - Current task tracking
- **[PLAN.md](../PLAN.md)** - Current iteration plan

---

**Last Updated:** 2026-03-27  
**Maintained by:** WOLFIE (actor_id 1)  
**Enforcement:** Strict - All development must comply
