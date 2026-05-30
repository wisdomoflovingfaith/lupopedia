---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-rules/root/DATABASE_DOCTRINE.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-rules/root/DATABASE_DOCTRINE.md"
  status: "active"
  when_updated: "20260420080000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/database-doctrine.toon"
  atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/development/database-doctrine"
  artifact_type: doctrine
  artifact_kind: database
  channel_key: "development"
  federation_node_id: 0
  thread_id: "database-doctrine"
  content_id: null
  content_parent_id: null
  content_slug: "database-doctrine"
  default_collection_id: null
  lupopedia.schema: doctrine
  title: "Database Doctrine - Canonical Rules"
  summary: "Canonical database rules for Lupopedia - naming conventions, ID generation, timestamp handling, forbidden features, and DatabaseFactory abstraction."
---

# WOLFIE — Database Doctrine (Canonical)

**To:** All Agents (HEPHAESTUS, THOTH, LILITH, etc.)  
**Channel:** 42  
**Thread:** database-doctrine  
**Date:** 2026-03-28  
**Status:** LOCKED — Binding Authority  

---

## ⚠️ **IMPORTANT: Binding Doctrine**

This document is **NOT optional**. These rules are **binding** for all database work in Lupopedia. No exceptions. No "just this once". No "modern approach". This is the law.

**See also:** [Independent Coder's Manifesto](INDEPENDENT_CODERS_MANIFESTO.md) for the philosophy behind these rules.

---

## Purpose

This document defines the canonical database rules for Lupopedia. These rules are **non-negotiable**. Every table, every query, every interaction with the database must follow these rules.

---

## 1. Core Philosophy

**The database is dumb storage. The application is smart.**

- No business logic in the database
- No triggers, no functions, no stored procedures
- The database stores rows; the application gives them meaning
- All constraints are enforced at the application layer

---

## 2. Primary Key Naming (NON-NEGOTIABLE)

**Every primary key MUST follow this exact pattern: `<singular_table_name>_id`**

| Table | Primary Key | Correct? |
|-------|-------------|----------|
| `lupo_actors` | `actor_id` | ✅ |
| `lupo_channels` | `channel_id` | ✅ |
| `lupo_sessions` | `session_id` | ✅ |
| `lupo_departments` | `department_id` | ✅ |
| `lupo_agents` | `agent_id` | ✅ |
| `lupo_auth_users` | `auth_user_id` | ✅ |
| `lupo_memory_nodes` | `memory_node_id` | ✅ |
| `lupo_memory_edges` | `memory_edge_id` | ✅ |
| `lupo_dialog_pending_tasks` | `dialog_pending_task_id` | ✅ |
| `lupo_dialog_read_log` | `dialog_read_log_id` | ✅ |
| `lupo_transcripts` | `transcript_id` | ✅ |
| `lupo_transcript_messages` | `transcript_message_id` | ✅ |

**FORBIDDEN** (never use these):
- `id`
- `node_id`
- `memory_id`
- `task_id`
- `message_id`
- or any shortened form

**This rule applies to:**
- Primary key columns
- Referencing columns in child tables
- All soft references (no foreign keys)

**The name IS the namespace boundary. Do not alter it.**

---

## 3. ID Generation - Application Layer Only

**ALL primary keys MUST be generated in the application layer using IdGenerator::generate()**

### ID Format
```
YYYYMMDDHHIISS + 4-digit random suffix
```

### Examples
- `202603301022000001` (Random suffix 0001 at 2026-03-30 10:22:00)
- `202603301022008452` (Random suffix 8452 at 2026-03-30 10:22:00)

### Requirements
- ✅ **63-bit signed-safe BIGINT** (18 digits max)
- ✅ **Generated BEFORE the INSERT**
- ✅ **Passed into INSERT as parameter**
- ✅ **Never retrieved from database**
- ✅ **Never computed using SQL**

### Forbidden Database Patterns
```sql
-- ❌ NEVER use these
AUTO_INCREMENT
SERIAL
UNSIGNED
SELECT MAX(id)
triggers
stored procedures
any DB-side ID logic
```

### Correct PHP Implementation
```php
// In your service/repository
$actor_id = IdGenerator::generate();

$db = DatabaseFactory::getConnection();
$db->insert('lupo_actors', [
    'actor_id' => $actor_id,
    'actor_name' => $name,
    'created_ymdhis' => gmdate('YmdHis'),
    'updated_ymdhis' => gmdate('YmdHis'),
]);

// Use the same $actor_id for related rows
$db->insert('lupo_agent_capabilities', [
    'capability_id' => IdGenerator::generate(),
    'actor_id' => $actor_id,  // Same ID from above
    'capability' => 'orchestration',
]);
```

### ID Generator Class
```php
class IdGenerator
{
    public static function generate()
    {
        $timestamp = gmdate('YmdHis');
        $suffix = mt_rand(0, 9999);
        
        return $timestamp . str_pad($suffix, 4, '0', STR_PAD_LEFT);
    }
}
```

### Collision Handling
With random suffix (0-9999), collision probability is extremely low:
- 1 record: 0.01% chance
- 100 records: 1% chance  
- 1000 records: 10% chance

If collision occurs, the database UNIQUE constraint will reject the INSERT. Handle this at the application layer if needed.

---

## 4. Timestamps (ABSOLUTE DOCTRINE)

### TIMESTAMP DOCTRINE — ABSOLUTE RULES

**1. STORAGE FORMAT (NON-NEGOTIABLE)**
All timestamps in Lupopedia MUST be stored as:
```
UTC BIGINT (YYYYMMDDHHIISS)
```

These fields are:
```
created_ymdhis
updated_ymdhis
deleted_ymdhis
```

These represent WHEN an event occurred.
They NEVER contain timezone information.

**2. FORBIDDEN STORAGE PATTERNS**
The following are STRICTLY FORBIDDEN in all database schemas, migrations, PRDs, and generated SQL:

- Storing timezone offsets in timestamp fields
- Storing local time in timestamp fields
- Using DATETIME, TIMESTAMP, or any SQL date type
- Auto-converting timestamps based on server timezone
- Mixing local time and UTC in the same column
- Any attempt to "helpfully" normalize or localize timestamps

Mixing timezone with the stored timestamp is FORBIDDEN.
Timezone is WHERE, not WHEN.

**3. DISPLAY & API CONVERSION**
Timezone handling is a DISPLAY concern only.

Conversion rules:
- Use the existing PHP class conversion functions in lupo-includes/classes/
- Convert BIGINT UTC → string with offset ONLY for output
- API systems may receive localized strings if required
- The database NEVER stores localized time

The database stores WHEN.
The application layer handles WHERE.

**4. DOCTRINE SUMMARY**
- Storage = UTC BIGINT only
- No timezone in storage
- No daylight savings adjustments
- No SQL datetime types
- No automatic conversions
- No mixing apples (UTC) and oranges (local time)

### Rules
- **Always use the `_ymdhis` suffix - never `_at`**
- No `DATETIME` 
- No `TIMESTAMP` 
- No `UNIX_TIMESTAMP()` 
- No timezone conversions
- Always UTC
- Always set by application: `gmdate('YmdHis')` 

### Correct
```sql
created_ymdhis BIGINT NOT NULL
updated_ymdhis BIGINT NOT NULL
deleted_ymdhis BIGINT DEFAULT NULL
```

### Forbidden
```sql
created_at DATETIME        -- ❌
updated_at TIMESTAMP       -- ❌
created_utc BIGINT         -- ❌ (use created_ymdhis)
created_at BIGINT          -- ❌ (use created_ymdhis)
```

**IMPORTANT:** If the system ever uses `created_at` / `updated_at` / `deleted_at`, they MUST be treated as aliases for the same BIGINT timestamp format — NOT SQL datetime fields.

---

## 5. Memory Node Storage Doctrine (CASCADE)

**1. Memory node content MUST NOT be stored in the database.**
- Database stores metadata only.
- Filesystem stores the actual content.

**2. Using TEXT, MEDIUMTEXT, or LONGTEXT for memory node content is FORBIDDEN.**

**3. Memory node content MUST be written to:**
```
lupo-memory/{channel_key}/{ladder_tier}/YYYY/MM/{memory_slug}.json
```

**4. The database stores:**
- memory_node_id
- memory_slug
- created_ymdhis
- updated_ymdhis
- deleted_ymdhis
- metadata fields
- edge references

It does NOT store the content blob.

**5. The filesystem is the canonical source of truth for memory content.**
The database is the index.

---

## 6. TOON JSON MIRROR DOCTRINE — CRITICAL RULES

**1. LOCATION**
The JSON files in:
```
lupo-database/lupopedia/json/
```
are TOON metadata mirrors generated from the live database.

**2. PURPOSE**
These JSON files describe:
- table structure
- column names
- column types
- indexes
- constraints
- metadata

They exist so agents can READ the schema before editing any files.

**3. READ-ONLY RULE**
These JSON files are STRICTLY READ-ONLY.

They are NOT:
- the primary data store
- a writable content store
- a place to insert rows
- a place to store memory content
- a replacement for the database

Writing data rows to the database based on these JSON files is FORBIDDEN.

**4. DIRECTION OF TRUTH**
Truth flows in ONE direction:
```
Database → TOON JSON (structure mirror)
```
NOT the other way around.

JSON → Database is FORBIDDEN unless the user explicitly commands a migration.

**5. DO NOT CONFUSE WITH MEMORY JSON FILES**
These TOON JSON files are NOT the same as the memory node content files stored in lupo-memory/.

- TOON JSON = structure mirror (read-only)
- Memory JSON = content store (writable)

Mixing these two concepts is FORBIDDEN.

**6. FORBIDDEN ACTIONS**
The following actions are STRICTLY FORBIDDEN:
- Treating TOON JSON as the database
- Writing new rows to the database based on TOON JSON
- Syncing TOON JSON → DB automatically
- Storing content in TOON JSON
- Using TOON JSON as a writable data source

---

## 7. INSERT Statement Doctrine — Critical Rules

**1. COLUMN LIST REQUIRED (NON-NEGOTIABLE)**
ALL INSERT statements MUST explicitly list EVERY column being inserted.

Example (correct):
```sql
INSERT INTO table_name (
    col1, col2, col3, col4
) VALUES (
    ?, ?, ?, ?
);
```

Example (FORBIDDEN):
```sql
INSERT INTO table_name VALUES (...);
```

Positional INSERTs are FORBIDDEN.

**2. REASON**
Lupopedia tables have evolved over 20+ years.
Column order is NOT canonical and MUST NOT be relied upon.

Columns:
- have been added in random order
- have been renamed
- have been moved
- are not sequential
- are not predictable

Therefore, INSERT statements MUST specify column names.

**3. TIMESTAMP COLUMNS**
If the table includes:
```
created_ymdhis
updated_ymdhis
deleted_ymdhis
```

Then INSERT statements MUST include these fields when appropriate.

These fields are UTC BIGINT timestamps.
They MUST NOT be auto-converted to DATETIME.

**4. FORBIDDEN PATTERNS**
The following are STRICTLY FORBIDDEN:
- INSERT INTO table VALUES (...)
- INSERT without column list
- INSERT relying on column order
- INSERT omitting timestamp fields when required
- INSERT using DATETIME or TIMESTAMP SQL types
- INSERT using timezone-adjusted values

**5. REQUIRED PRE-FLIGHT CHECK**
Before generating ANY INSERT statement, agents MUST:
- Load the TOON JSON metadata from: lupo-database/lupopedia/json/
- Read the actual column names
- Use the exact column names in the INSERT

The TOON JSON is the schema mirror.
It MUST be read before generating SQL.

**6. DIRECTION OF TRUTH**
```
Database schema → TOON JSON → Agent SQL generation
```

NOT the other way around.
---

## 8. PRD Indexing Doctrine — Content + Memory System

**1. Filesystem is the authoritative store for memory node content.**
Database stores metadata and indexes only.

**2. To support fast search, the following indexing tables MUST be created:**

**a. lupo_memory_keywords**
```sql
- memory_node_id
- keyword
- weight
- created_ymdhis
```

**b. lupo_memory_embeddings**
```sql
- memory_node_id
- embedding_vector (stored as JSON or blob)
- model_version
- created_ymdhis
```

**c. lupo_memory_search_index**
```sql
- memory_node_id
- title
- summary
- keywords
- updated_ymdhis
```

**d. lupo_memory_tags**
```sql
- memory_node_id
- tag
- created_ymdhis
```

**e. lupo_memory_hash_index**
```sql
- memory_node_id
- content_hash
- created_ymdhis
```

**3. lupo_contents requires similar indexing tables:**

**a. lupo_content_keywords**
**b. lupo_content_search_index**
**c. lupo_content_tags**
**d. lupo_content_hash_index**

**4. All indexing tables MUST use:**
- auth_user_id (not user_id)
- explicit column lists in INSERT statements
- UTC BIGINT timestamps (ymdhis)
- NO timezone offsets in storage

**5. Indexing tables MUST NOT store content blobs.**
Content remains in the filesystem.

---

## 9. PRD Filesystem Location Doctrine

**1. THREE DISTINCT FILESYSTEM DOMAINS (MANDATORY)**

**a. lupo-memory/**
- Stores memory node content ONLY.
- Structured by: {channel_key}/{ladder_tier}/{YYYY}/{MM}/{slug}.json
- This is the authoritative store for memory content.

**b. lupo-channels/**
- Stores channel artifacts ONLY.
- Includes: manifests, minutes, heterodox reports, protocols,
  emotional geometry, channel metadata, and channel-level artifacts.
- Structured by: {federation_node_id}/{channel_key}/{YYYY}/{MM}/{file}

**c. lupo-content/**
- Stores general content files that are NOT memory nodes and NOT
  channel artifacts.
- Structured by: {federation_node_id}/{channel_key}/{YYYY}/{MM}/{file}

**THESE THREE PATHS MUST NEVER BE COLLAPSED OR CONFUSED.**

**2. FORBIDDEN ACTIONS**

The following are STRICTLY FORBIDDEN:
- Storing memory nodes in lupo-content/
- Storing channel artifacts in lupo-content/
- Storing content files in lupo-memory/
- Storing ANY files outside YYYY/MM sharding
- Writing files to unsharded directories
- Dumping multiple content types into the same folder

**3. DATE-SHARDED STORAGE (NON-NEGOTIABLE)**

ALL filesystem-backed content MUST be stored using:
```
{YYYY}/{MM}/
```

Reason:
- Prevents directory explosion
- Ensures stable performance for ls/find
- Enables efficient archival and cleanup
- Supports distributed federation nodes

**4. PRE-FLIGHT REQUIREMENT FOR ALL AGENTS**

Before writing ANY file, agents MUST:
- Determine correct domain (memory, channel, or content)
- Compute correct YYYY/MM shard
- Ensure directories exist
- Write ONLY to the correct domain + shard

Writing to the wrong domain is a PRD violation.

**5. DO NOT ASSUME EVERYTHING BELONGS IN lupo-content/**

lupo-content/ is NOT a catch-all directory.

- Memory nodes → lupo-memory/
- Channel artifacts → lupo-channels/
- General content → lupo-content/

Agents MUST respect these boundaries.

---

## 10. PRD Doctrine — Column Name Source of Truth

**1. JSON SCHEMA FILES ARE THE AUTHORITATIVE SOURCE OF TRUTH**
For ANY update to ANY PHP or Python file that references database columns,
Cascade MUST load and read the JSON schema files located in:
```
lupo-database/lupopedia/json/
```

These JSON files define:
- exact column names
- exact column types
- nullability
- defaults
- indexes
- primary keys

Predictive text MUST NOT be used to infer column names.

**2. FORBIDDEN ACTIONS**
The following are STRICTLY FORBIDDEN:
- Guessing column names
- Using predictive text to infer column names
- Shortening or normalizing column names
- Replacing auth_user_id with user_id
- Inventing columns not present in JSON
- Using outdated column names from older code

**3. REQUIRED PRE-FLIGHT CHECK**
Before modifying ANY PHP or Python file that touches the database,
Cascade MUST:
- Load the JSON schema for the relevant table
- Verify the column names
- Use EXACT column names from the JSON
- Reject any code that uses incorrect or hallucinated names

**4. ERROR HANDLING**
If a referenced column does NOT exist in the JSON schema:
- Cascade MUST stop
- Notify the user of the mismatch
- Suggest adding the column to the database if appropriate
- NEVER invent or assume the column

**5. INSTALLER + SEED CONSISTENCY**
All code changes MUST align with:
- install_new_lupopedia.sql
- seed_lupopedia_4_1_0.sql
- JSON schema mirrors

If inconsistencies are found, Cascade MUST report them.

**6. DO NOT RELY ON COLUMN ORDER**
All SQL generated in PHP or Python MUST:
- explicitly list column names
- NEVER rely on positional order
- NEVER use INSERT ... VALUES (...) without column lists

**7. DOCTRINE SUMMARY**
```
JSON schema → installer → seed → PHP/Python code
```

This is the ONLY valid direction of truth.

---

## 5. Forbidden MySQL/PostgreSQL-Specific Features

**All SQL must work on both MySQL 8.0+ and PostgreSQL 15+ without modification.**

| Feature | Why Forbidden | Alternative |
|---------|---------------|-------------|
| `UNSIGNED` | PostgreSQL doesn't support it | Application validation or `CHECK (col >= 0)` |
| `AUTO_INCREMENT` | PostgreSQL uses `SERIAL` | Application-layer ID generation |
| `DATETIME` / `TIMESTAMP` | Different types | `BIGINT` for timestamps |
| `ON DUPLICATE KEY UPDATE` | PostgreSQL uses `ON CONFLICT` | Application layer or conditional logic |
| `REPLACE INTO` | MySQL-specific | `INSERT ... ON CONFLICT` or application logic |
| `IF NOT EXISTS` (CREATE) | Different syntax | Check existence before creation |
| `SHOW TABLES` | MySQL-specific | Query `information_schema.tables` |

---

## 6. Forbidden Database Logic

**NO business logic in the database.**

| Forbidden | Why |
|-----------|-----|
| Triggers | Logic hidden from application |
| Stored Procedures | Lock-in, hard to version control |
| Functions | Same as stored procedures |
| Views | Logic split between app and DB |
| Foreign Keys | Cascading deletes hidden; migration order hell |

### Correct
- All logic in PHP classes
- All constraints in application code
- All relationships explicit in queries

---

## 7. INSERT Rules

**Every INSERT must explicitly name the columns.**

### Correct
```sql
INSERT INTO lupo_actors (actor_id, actor_name, created_ymdhis, updated_ymdhis)
VALUES (202603281200000001, 'WOLFIE', 20260328120000, 20260328120000);
```

### Forbidden
```sql
INSERT INTO lupo_actors VALUES (202603281200000001, 'WOLFIE', ...);  -- ❌
```

---

## 8. Database Access

**All database access MUST go through `DatabaseFactory::getConnection()`.**

### Correct
```php
$db = DatabaseFactory::getConnection();
$result = $db->fetchAll("SELECT * FROM lupo_actors WHERE actor_id = :id", ['id' => $id]);
```

### Forbidden
```php
$pdo = new PDO(...);           // ❌
$mysqli = new mysqli(...);      // ❌
new PDO_DB(...);                // ❌ (use factory)
```

---

## 9. Table Documentation

**Every table MUST have documentation in `lupo-docs/database/lupopedia/tables/active/`.**

Each table doc must include:
- Table purpose
- Column definitions (type, nullable, default)
- Relationships (how edges and other tables interact)
- Usage examples
- `lupopedia.edges` block with references to code that uses the table

---

## 10. Registry Tables (Deprecated)

**Registry tables are deprecated. Do not use them.**

- `lupo_registry` — REMOVED
- `lupo_registry_open` — REMOVED

Use `IdGenerator` for ID generation instead.

---

## 11. Enforcement

| Tool | Purpose |
|------|---------|
| `validate_schema.php` | Checks table structure against doctrine |
| `verify_db_against_toons.py` | Verifies DB against TOON exports |
| Pre-commit hooks | Blocks commits with forbidden features |
| Code review | Manual verification of SQL |

---

## Summary Table

| Rule | Requirement |
|------|-------------|
| **PK Names** | `[table]_id` (not `id`) |
| **ID Generation** | Application-layer, timestamp + sequence |
| **Timestamps** | BIGINT UTC YYYYMMDDHHIISS |
| **Forbidden** | Triggers, procedures, functions, views, FKs |
| **SQL Portability** | Must work on MySQL AND PostgreSQL |
| **INSERT** | Always name columns |
| **Database Access** | Through `DatabaseFactory` |

---

**WOLFIE (actor_id 1)** — This doctrine is now binding. All database work must follow these rules. No exceptions. Proceed.
