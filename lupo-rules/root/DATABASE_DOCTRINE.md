---
lupopedia.headers:
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-rules/root/DATABASE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-rules/root/DATABASE_DOCTRINE.md"
  federation_node_id: 0
  last_modified_utc: "20260406044907"
  when_updated: "20260406044907"
  channel_id: 42
  thread_id: "database-doctrine"
  author:
    type: "actor"
    id: 1
    name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: doctrine
  artifact_kind: database
  purpose: Canonical database rules for Lupopedia - naming conventions, ID generation, timestamp handling, forbidden features, and DatabaseFactory abstraction
  tags:
  - "database"
  - "doctrine"
  - "rules"
  - "naming_conventions"
  - "id_generation"
  - "timestamps"
  - "database_factory"
  - "registry_removal"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-rules/root/DATABASE_NEUTRAL_SQL_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: Cross-platform SQL compliance
    - to: "lupo-rules/root/INDEPENDENT_CODERS_MANIFESTO.md"
      type: references
      weight: 1.0
      reason: Philosophy behind database rules
    - to: "lupo-includes/classes/DatabaseFactory.php"
      type: references
      weight: 1.0
      reason: Database access abstraction layer
    - to: "lupo-includes/classes/IdGenerator.php"
      type: references
      weight: 1.0
      reason: Application-layer ID generation
    - to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
      type: references
      weight: 1.0
      reason: Install schema following doctrine
    - to: "lupo-docs/database/lupopedia/tables/active/"
      type: references
      weight: 1.0
      reason: Table documentation directory
lupopedia.footer:
  last_verified: "20260328130000"
  last_verified_by: "wolfie"
  last_verified_by_actor_id: 1
  orchestrator: "wolfie:root"
  next_action:
    - Enforce doctrine in all database-related code
    - Update existing tables to comply with naming conventions
    - Validate all SQL queries for cross-platform compatibility
    - Ensure all ID generation uses IdGenerator class
    - Document all tables in lupo-docs/database/lupopedia/tables/active/
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

## 2. Primary Key Naming

**Every primary key MUST be named `[tablename_singular]_id`.**

| Table | Primary Key | Correct? |
|-------|-------------|----------|
| `lupo_actors` | `actor_id` | ✅ |
| `lupo_channels` | `channel_id` | ✅ |
| `lupo_sessions` | `session_id` | ✅ |
| `lupo_departments` | `department_id` | ✅ |
| `lupo_agents` | `agent_id` | ✅ |
| `lupo_auth_users` | `auth_user_id` | ✅ |

**NEVER use `id` as a column name.**

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

## 4. Timestamps

**All timestamps are BIGINT UTC in YYYYMMDDHHIISS format.**

### Rules
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
```

### Forbidden
```sql
created_at DATETIME        -- ❌
updated_at TIMESTAMP       -- ❌
created_utc BIGINT         -- ❌ (use created_ymdhis)
```

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
