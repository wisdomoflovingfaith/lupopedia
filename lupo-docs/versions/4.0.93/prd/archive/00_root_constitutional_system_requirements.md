---
lupopedia.headers:
  lupopedia.schema: prd
  file_path_from_root: "lupo-docs/versions/4.0.93/prd/00_root_constitutional_system_requirements.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/prd/00_root_constitutional_system_requirements.md"
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
- PHP 8.3 maximum

Forbidden:

- Namespaces
- Strict types
- Typed properties
- Arrow functions
- Enums
- Attributes
- Middleware patterns
- Laravel-style frameworks

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

## 7. Absolute-Root Pathing (RULE 93.PATH_PURITY)

All documentation links must:

- Start with `/`
- Never use `../`
- Never use `~/`
- Never use relative paths

---

## 8. Installer Constitutional Rules

- Must run on shared hosting  
- Must not modify parent directories  
- Must not assume root access  
- Must not require composer  
- Must not require CLI tools  
- Must not require database privileges beyond CREATE/INSERT/UPDATE/DELETE  

---

## 9. Enforcement

These rules are binding and override all other PRDs.

Any violation is considered a constitutional error and must be corrected immediately.

---

# Refinements

- All doctrine and PRD files must reference this file as the constitutional anchor using an outbound edge.
- All new PRDs must be reviewed for compliance with these requirements.
- Any agent, IDE, or human contributor must treat this file as the highest authority.
- All system-level enforcement, validation, and review must check for compliance with these rules.
- This file must be versioned and updated only by lead orchestration agents or with explicit review.
