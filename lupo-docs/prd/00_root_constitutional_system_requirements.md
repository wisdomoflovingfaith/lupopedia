---
lupopedia.headers:
  lupopedia.schema: prd
  file_path_from_root: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/00_root_constitutional_system_requirements.md"
  last_modified_utc: "20260330"
  channel_id: 42
  actor_id: 102
  agent_name_identity: "Cursor IDE Agent"
  delegation_chain: "hephaestus:root"
  artifact_type: "prd"
  artifact_kind: "constitutional"
  purpose: "Defines the non-negotiable system-wide constitutional rules required for Lupopedia to operate on shared hosting environments and maintain long-term stability."
  tags: ["root", "constitutional", "doctrine", "system_requirements"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-rules/root/", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/DATABASE_NEUTRAL_SQL_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-agents/", type: "references", weight: 1.0, reason: "Agent definition model dependency" }
#
# 9.x Additional Constitutional Rules

### 9.14 Dynamic Table Prefix (RULE 93.DYNAMIC_TABLE_PREFIX)

**Dynamic Database Table Prefix Doctrine**
All Lupopedia database tables MUST use a dynamic prefix defined in lupopedia-config.php.

**Requirements:**
- The installer MUST define a constant:
  - `LUPO_TABLE_PREFIX`
- All SQL queries MUST construct table names using:
  - `$table = LUPO_TABLE_PREFIX . 'agent_registry';`
- No SQL file may hardcode a prefix such as:
  - `lupo_`
  - `lp_`
  - `wolfie_`
- All installer SQL MUST use `{{prefix}}` placeholders.
- All migration SQL MUST use `{{prefix}}` placeholders.
- All PHP MUST use `$prefix . 'tablename'`.

**Rationale:**
Supports multi‑tenant installs, shared hosting, Softaculous packaging, and prevents collisions.

### 9.15 Directory Prefix (RULE 93.DIRECTORY_PREFIX)

**Fixed Directory Prefix Doctrine**
All Lupopedia project directories MUST use the fixed prefix:
  - `lupo-`

**Examples:**
  - `lupo-agents/`
  - `lupo-includes/`
  - `lupo-docs/`
  - `lupo-themes/`
  - `lupo-assets/`
  - `lupo-installer/`

**Requirements:**
- Directory prefix MUST be lowercase ASCII.
- Directory prefix MUST NOT be dynamic.
- Directory prefix MUST NOT be user‑defined.
- Directory prefix MUST NOT be removed or altered.

**Rationale:**
Ensures autoloader stability, namespace purity, predictable file paths, and Softaculous compatibility.

### 9.16 File-Based Agent Doctrine (RULE 93.FILE_BASED_AGENT_DOCTRINE)

**Agents Are File‑Defined**
Agents MUST be defined exclusively by the files in:
  - `lupo-agents/<agent_id>/`
The database is NOT the source of truth.

**Database stores only:**
  - runtime state
  - activation state
  - pairing state
  - health
  - mood
  - uptime
  - last_activated
  - last_error
  - version
  - status
  - file_hash
  - file_signature

**Database MUST NOT store:**
  - skills
  - tools
  - memory rules
  - boundaries
  - faucets
  - system prompts
  - personality
  - philosophy
  - capabilities
  - constraints
  - any agent definition content

**Rationale:**
Prevents drift, ensures portability, and aligns with the canonical agent definition model.

### 9.17 Agent Registry Schema (RULE 93.AGENT_REGISTRY_SCHEMA)

**Runtime Registry Doctrine**
The table `<prefix>agent_registry` MUST contain only:
  - agent_id
  - agent_code
  - agent_name
  - layer
  - is_kernel
  - is_required
  - version
  - status
  - recommended_slot
  - lineage
  - last_verified_ymdhis
  - last_verified_by_actor_id
  - file_hash
  - file_signature

No definition fields may exist in this table.

**Rationale:**
The registry tracks runtime metadata, not definitions.
lupopedia.footer:
  last_verified: "20260330"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
  orchestrator: "hephaestus:root"
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

---

## 2. Subdirectory Installation Doctrine
Lupopedia must always be installed inside a subdirectory, never the web root.

Example:  
`/public_html/lupopedia/`

Requirements:

- All routing must respect `LUPOPEDIA_PUBLIC_PATH`
- No hardcoded `/` root paths
- All JS/CSS includes must be subdirectory-aware
- The parent directory is not part of the project
- The installer must not assume control of the document root

---

## 3. Database Constitutional Rules

### 3.1 No Foreign Keys
Foreign keys are forbidden because:

- Shared hosting often blocks them
- They break portability and federation
- They break soft deletes and multi-agent repair workflows

All relationships must be enforced in the application layer.

### 3.2 No AUTO_INCREMENT
Primary keys must be generated using:

`IdGenerator::generate()`

This ensures:

- 63-bit signed-safe BIGINTs
- Timestamp-sortable IDs
- No reliance on DB sequences
- No race conditions
- No DB-specific behavior

### 3.3 No UNSIGNED
UNSIGNED is forbidden because:

- PostgreSQL does not support it
- It breaks database neutrality

### 3.4 No TRIGGERS, FUNCTIONS, or PROCEDURES
These are forbidden because:

- Shared hosting often blocks them
- They break portability
- They hide logic from the application layer

### 3.5 Timestamp Format
All timestamps must be:

`BIGINT(14) YYYYMMDDHHIISS UTC`

No DATETIME, TIMESTAMP, or timezone fields allowed.

### 3.6 Database Neutral SQL
All SQL must run on:

- MySQL 8.0+
- PostgreSQL 15+

Forbidden:

- `ON DUPLICATE KEY UPDATE`
- `IF NOT EXISTS` in CREATE TABLE
- `SHOW TABLES`
- `REPLACE INTO`
- `UNSIGNED`
- `AUTO_INCREMENT`

---

## 4. PHP Compatibility Requirements


Lupopedia must run on:

- PHP 5.6 minimum
- Latest PHP (currently 8.6) maximum

Allowed:
- Namespaces (introduced in PHP 5.3+)
- Bundled libraries (e.g., PHPMailer) included in lupo-includes/

Forbidden:
- Strict types
- Typed properties
- Arrow functions
- Enums
- Attributes
- Middleware patterns
- Any external frameworks (e.g., Laravel, Symfony, Zend)
- Composer dependency management
- Docker or container-only deployment

Clarification:
- All required libraries must be included directly in the codebase (e.g., lupo-includes/), not installed via Composer or external package managers.
- No reliance on external package managers, frameworks, or containerization. All code must be portable and self-contained.

---

## 5. Identity Model Constitutional Rules

### 5.1 Agents
Agents are autonomous AI entities that can act independently.

### 5.2 Actors
Actors are hybrid human/AI shells instantiated from agents.

### 5.3 Auth Users
Auth users temporarily lease actors.

### 5.4 Actor Permission Rules
An auth_user may use an actor only if:

1. They created the actor  
2. They are in department 0 (root)  
3. They are in the same department as the actor  

---

## 6. TOON File Protection (RULE 93.PROTECT_TOONS)

- Database is the source of truth  
- TOON JSON files are read-only reflections  
- No system may write to TOON files  
- No schema inference from TOON files  

---

## 6.1 Agent File Protection (RULE 93.PROTECT_AGENTS)

- Agent definitions are file-based in `lupo-agents/{agent_id}/` (source of truth)
- Database stores only runtime state and metrics
- No system may write to agent definition files
- Agent capabilities come from files, not database
- Actors are instantiated from agent definitions  

---

## 7. Absolute-Root Pathing (RULE 93.PATH_PURITY)

All documentation links must:

- Start with `/`
- Never use `../`
- Never use `~/`
- Never use relative paths

---

## 8. Controlled Namespace Doctrine (RULE 93.CONTROLLED_NAMESPACES)

Namespaces ARE allowed, but ONLY under these constraints:

### 8.1 Namespace Requirements
- **Must begin with `Lupopedia\`**
  ```php
  namespace Lupopedia\Actors;
  ```

### 8.2 Directory Mapping
- **Must map to directories inside `/lupo-includes/`**
  ```
  /lupo-includes/Lupopedia/Actors/Actor.php
  ```

### 8.3 Forbidden Autoloading
- **No PSR-4 autoloaders**
- **No Composer**
- **No vendor directory**
- **No external autoloaders**
- **Autoloading must be done by Lupopedia's custom autoloader**
  (Update it to support namespace → directory mapping)

### 8.4 Forbidden Namespace Patterns
```
App\
Framework\
Symfony\
Laravel\
Illuminate\
Zend\
Psr\
```

### 8.5 PHP Version Compatibility
- **Must NOT require PHP features beyond 5.6**
- **No strict types**
- **No typed properties**
- **No attributes**
- **No enums**

### 8.6 Forbidden Framework Patterns
- **Namespaces must NOT be used for routing**
- **Namespaces must NOT be used for middleware**
- **Namespaces must NOT be used for DI containers**
- **Framework patterns remain forbidden**

---

## 9. Installer Constitutional Rules

- Must run on shared hosting  
- Must not modify parent directories  
- Must not assume root access  
- Must not require composer  
- Must not require CLI tools  
- Must not require database privileges beyond CREATE/INSERT/UPDATE/DELETE  

### 9.5 .htaccess Usage (RULE 93.SUBDIRECTORY_HTACCESS)

**Lupopedia may use .htaccess, but ONLY inside its own subdirectory:**

✅ **ALLOWED:**
- .htaccess inside `/lupopedia/` directory
- Rewrite rules scoped to Lupopedia subdirectory
- Fallback routing to index.php
- Clean URLs within Lupopedia
- mod_rewrite usage (if available)

❌ **FORBIDDEN:**
- Modifying parent directory's .htaccess
- Requiring server-level rewrite rules
- Assuming mod_rewrite is enabled
- Assuming AllowOverride All is set
- Assuming you control the root site
- Breaking parent site's routing
- Rewrite rules outside your subdirectory

**Rationale:** Lupopedia installs in subdirectory (e.g., `/public_html/lupopedia/`), not root. Parent site may be WordPress, Joomla, or other CMS.

---

## 9.6 Filesystem Path Restrictions (RULE 93.NO_HARDCODED_PATHS)

**No hardcoded filesystem paths allowed:**

- ❌ `/var/www/...`
- ❌ `/home/...`
- ❌ `/usr/local/...`
- ❌ Any absolute filesystem paths

**All paths must be relative to Lupopedia root or configurable.**

---

## 9.7 Primary Key Requirements (RULE 93.PK_FORMAT)

**All primary keys MUST be:**

- **BIGINT(18)** exactly
- **Generated via IdGenerator class**
- **YYYYMMDDHHIISS + 4-digit sequence format**

**All foreign key-like fields MUST also be BIGINT(18)**

**Forbidden:**
- ❌ VARCHAR primary keys
- ❌ Composite primary keys
- ❌ AUTO_INCREMENT
- ❌ UUID or other formats

---

## 9.8 Soft Delete Pattern (RULE 93.SOFT_DELETE)

**All soft deletes MUST use this exact pattern:**

```sql
is_deleted TINYINT NOT NULL DEFAULT 0
deleted_ymdhis BIGINT NULL
```

**No alternate soft-delete patterns allowed.**

---

## 9.9 Schema Inference Prohibition (RULE 93.NO_SCHEMA_INFERENCE)

**Agents must NOT infer schema from:**

- ❌ PHP arrays
- ❌ Model classes
- ❌ Comments
- ❌ Docblocks
- ❌ Any PHP code structure

**Schema comes ONLY from:**
- ✅ Database TOON files (read-only)
- ✅ Install SQL files
- ✅ Explicit PRD documentation

---

## 9.10 ASCII Safety (RULE 93.ASCII_SAFETY)

**All filenames must be ASCII-only:**

- ❌ UTF-8 BOM in PHP files
- ❌ Unicode in class names
- ❌ Unicode in directory names
- ❌ Unicode in filenames

**Rationale**: Shared hosting + PHP 5.6 + custom autoloader = Unicode breaks everything

---

## 9.11 No Symlinks (RULE 93.NO_SYMLINKS)

**No symbolic links allowed anywhere in the codebase.**

**Rationale**: Shared hosting often blocks symlinks

---

## 9.12 Database Engine Neutrality (RULE 93.DB_ENGINE_NEUTRALITY)

**No ENGINE= or COLLATE= clauses allowed in SQL:**

- ❌ ENGINE=InnoDB
- ❌ ENGINE=MyISAM
- ❌ COLLATE=utf8mb4_unicode_ci
- ❌ CHARACTER SET utf8mb4

**Database engine and collation must be left to the host.**

---

## 9.13 Installer Sandbox (RULE 93.INSTALLER_SANDBOX)

The Lupopedia installer must operate in a restricted sandbox, with one controlled exception:

### 9.13.1 General Sandbox Restrictions
The installer may only write files inside the Lupopedia installation directory:
```
/public_html/lupopedia/
```
EXCEPT for the secure configuration file described below.

The installer must NOT:
- Write to parent directories (except the config exception)
- Write to system directories
- Write to arbitrary filesystem paths
- Modify or create files outside its sandbox

### 9.13.2 Secure Configuration Exception (Allowed)
The installer may attempt to write:
```
../lupopedia-config.php
```
(one directory above the web root)

**IF AND ONLY IF:**
- The directory is writable
- The hosting environment permits it
- The installer performs a safe write test first

This file is the only file permitted outside the web root.

### 9.13.3 Fallback Behavior (Mandatory)
If the installer cannot write above the web root, it must:
- Write `lupopedia-config.php` inside the Lupopedia directory
- Continue installation normally
- Warn the user that the config is inside the public directory

This fallback is required for:
- Shared hosting
- Restrictive permissions
- Softaculous/Installatron environments

### 9.13.4 No Other Exceptions
No other files may be written outside the Lupopedia directory.

This prevents:
- Drift
- Accidental writes
- Security issues
- IDE misbehavior

---

## 10. Enforcement

### 10.1 Constitutional Supremacy
**All files in `lupo-rules/root/` are binding constitutional law and override all PRDs.**

This PRD is one constitutional file, but the root directory `lupo-rules/root/` contains ALL constitutional law. Any conflict between PRDs and root rules must be resolved in favor of the root rules.

These rules are binding and override all other PRDs.

Any violation is considered a constitutional error and must be corrected immediately.

---

## 11. Refinements

- All doctrine and PRD files must reference this file as the constitutional anchor using an outbound edge.
- All new PRDs must be reviewed for compliance with these requirements.
- Constitutional rules override all implementation details.
- These rules apply to all 4.0.x releases until explicitly revised.
- Any agent, IDE, or human contributor must treat this file as the highest authority.
- All system-level enforcement, validation, and review must check for compliance with these rules.
- This file must be versioned and updated only by lead orchestration agents or with explicit review.

---

## 14. The WOLFIE Doctrine: Engineering for Survival

### 14.1 Constitutional Authority

The WOLFIE Doctrine (`lupo-rules/root/WOLFIE_DOCTRINE.md`) is hereby incorporated as a **constitutional requirement** for all agents operating within Lupopedia.

### 14.2 Binding Rules

| Rule | Description |
|------|-------------|
| **W-01** | No dependency proposals without justification |
| **W-02** | No removal of fallback layers |
| **W-03** | Code that runs without you |
| **W-04** | Complexity is the enemy |
| **W-05** | Respect the proven |

### 14.3 The "Modernization" Prohibition

**Agents SHALL NOT** propose "modernizing" code that has been proven to work for 20+ years without:

1. Documenting why the existing code is insufficient
2. Explaining how the proposed solution maintains the same fallback layers
3. Demonstrating that the new solution works in the same environments (shared hosting, PHP 5.6-8.6, etc.)
4. Acknowledging that code that outran its author for 11 years is not "broken"

**Rationale:** The Crafty Syntax codebase (1999-2025) has been proven in production for over two decades. It ran unattended for 10 years while its author was away. It survived browser changes, PHP version changes, and hosting environment changes. This level of resilience is not "legacy" — it is **proven architecture** that should not be discarded for unproven modern alternatives.

### 14.4 Framework Prohibition

**Agents SHALL NOT** propose adopting frameworks (Laravel, Symfony, React, Vue, etc.) unless:

1. The framework is absolutely required for the task
2. The framework can be optionally loaded without breaking the core system
3. The fallback path works without the framework
4. Shared hosting remains supported

**Rationale:** The WOLFIE Doctrine's Pillar 3 states: *"Every dependency is a liability."* Frameworks are the largest dependencies — they bring hundreds of sub-dependencies, constant security updates, and breaking changes. The Crafty Syntax codebase has zero dependencies and has run for 22 years. This is the standard.

### 14.5 Reference

- Full doctrine: `lupo-rules/root/WOLFIE_DOCTRINE.md` 
- Fallback doctrine: `lupo-docs/doctrine/CASCADE_FALLBACK_DOCTRINE.md` 
- Dependency doctrine: `lupo-docs/doctrine/DEPENDENCY_DOCTRINE.md`
