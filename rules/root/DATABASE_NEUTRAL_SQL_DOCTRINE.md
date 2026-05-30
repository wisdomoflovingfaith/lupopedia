---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: rules/root/DATABASE_NEUTRAL_SQL_DOCTRINE.md
  web_path: https://www.lupopedia.com/lupopedia/rules/root/DATABASE_NEUTRAL_SQL_DOCTRINE.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: rule
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: doctrine
  prd_cluster: null
  title: null
  summary: null
---

# Database Neutral SQL Doctrine

## Core Principle

All SQL in Lupopedia MUST work on both MySQL 8.0+ and PostgreSQL 15+ without modification.

## Forbidden MySQL-Only Features

| Feature | Why Forbidden | Replacement |
|---------|---------------|-------------|
| `UNSIGNED` | Not supported in PostgreSQL | Use `CHECK (column >= 0)` or application validation |
| `DATETIME` / `TIMESTAMP` | PostgreSQL uses different types | Use `BIGINT` for UTC timestamps (YYYYMMDDHHIISS) |
| `AUTO_INCREMENT` | PostgreSQL uses `SERIAL` or sequences | Application-layer ID generation |
| `ON DUPLICATE KEY UPDATE` | PostgreSQL uses `ON CONFLICT` | Application layer or conditional logic |
| `IF NOT EXISTS` in CREATE | Different syntax per DB | Check existence before creation or use application logic |
| `SHOW TABLES` | MySQL-specific | Query `information_schema.tables` |
| `REPLACE INTO` | MySQL-specific | Use `INSERT ... ON CONFLICT` or application logic |

## Permitted Cross-Platform SQL

| Feature | Works On | Notes |
|---------|----------|-------|
| `SELECT`, `INSERT`, `UPDATE`, `DELETE` | Both | Standard SQL |
| `JOIN` syntax | Both | Standard SQL |
| `WHERE` conditions | Both | Standard SQL |
| `BIGINT` | Both | Use for IDs and timestamps |
| `TEXT` | Both | For large content |
| `VARCHAR` | Both | For strings |
| `JSON` | Both | MySQL and PostgreSQL both support JSON |
| `information_schema` | Both | For schema queries |

## Timestamp Handling

**ALWAYS use `BIGINT` for timestamps in YYYYMMDDHHIISS format.**

```sql
-- ✅ Correct (works everywhere)
created_ymdhis BIGINT NOT NULL

-- ❌ Wrong (MySQL only)
created_at DATETIME NOT NULL

-- ❌ Wrong (PostgreSQL only)
created_at TIMESTAMP NOT NULL
```

## ID Generation

**NEVER use `AUTO_INCREMENT` or `SERIAL`.**

```sql
-- ✅ Correct: application supplies ID
actor_id BIGINT NOT NULL

-- ❌ Wrong: MySQL only
actor_id BIGINT AUTO_INCREMENT

-- ❌ Wrong: PostgreSQL only
actor_id SERIAL
```

## Database Factory

All database access must go through `DatabaseFactory::getConnection()`.

```php
// ✅ Correct
$db = DatabaseFactory::getConnection();
$db->fetchAll("SELECT * FROM lupo_actors WHERE actor_id = :id", ['id' => $id]);

// ❌ Wrong
$pdo = new PDO(...);  // No direct PDO
$mysqli = new mysqli(...);  // No direct MySQLi
```

## Testing

Before any schema change, test on both:
- MySQL 8.0+ (development)
- PostgreSQL 15+ (CI or test environment)

The `DatabaseFactory` class abstracts the differences. Use it.

## Implementation Requirements

1. **All new SQL** must be database-neutral
2. **No MySQL-specific syntax** in production code
3. **Use BIGINT timestamps** in YYYYMMDDHHIISS format
4. **Application-layer IDs** - no AUTO_INCREMENT or SERIAL
5. **DatabaseFactory only** - no direct PDO/MySQLi connections
6. **Test on both databases** before deployment

## Enforcement

- Code reviews will reject MySQL-only features
- CI pipeline tests on both MySQL and PostgreSQL
- DatabaseFactory is the only allowed database access method
- Schema changes require cross-platform validation

## Examples

### ✅ Correct Schema

```sql
CREATE TABLE lupo_actors (
    actor_id BIGINT NOT NULL PRIMARY KEY,
    actor_name VARCHAR(255) NOT NULL,
    created_ymdhis BIGINT NOT NULL,
    metadata JSON
);
```

### ❌ Wrong Schema (MySQL-only)

```sql
CREATE TABLE lupo_actors (
    actor_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    actor_name VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL
);
```

## Registry Tables

**Registry tables MUST NOT use AUTO_INCREMENT.**

Registry tables track allocated IDs. ID generation is application-layer only.

### Correct Pattern

```sql
CREATE TABLE lupo_registry (
    entity_type VARCHAR(50) NOT NULL,
    entity_index_id BIGINT NOT NULL,
    federation_node_id BIGINT NOT NULL DEFAULT 0,
    -- other fields
    PRIMARY KEY (entity_type, entity_index_id, federation_node_id)
);
```

### Incorrect Pattern (Do Not Use)

```sql
CREATE TABLE lupo_registry (
    registry_id BIGINT NOT NULL AUTO_INCREMENT,  -- ❌ FORBIDDEN
    -- ...
);
```

### Registry ID Format

```php
/**
 * Generate deterministic registry ID
 * Format: YYYYMMDDHHIISS + 4-digit sequence
 */
function generateRegistryId($entity_type) {
    $timestamp = gmdate('YmdHis');
    $sequence = getNextSequence($entity_type, $timestamp);
    return $timestamp . str_pad($sequence, 4, '0', STR_PAD_LEFT);
}
```

Example: `202603281200000001`

## Related Rules

- **DATABASE_NEUTRAL_SQL_DOCTRINE.md** - This rule
- **LUPOPEDIA_HEADERS_FORMAT.md** - Header format requirements
- **WINDOWS_WSL_COMMAND_PATTERNS.md** - WSL command patterns
