---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
channel_key: system/kernel
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created SQL Type Doctrine documenting mandatory rules for SQL type syntax: no display widths, simplified types, no UNSIGNED unless explicitly requested, no column-level charset/collation."
tags:
  categories: ["doctrine", "sql", "schema"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "doctrine"]
file:
  title: "SQL Type Doctrine"
  description: "Mandatory rules for SQL type syntax in schema definitions, migrations, and generated SQL"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# SQL Type Doctrine

This doctrine establishes mandatory rules for SQL type syntax in all schema definitions, migrations, and generated SQL.

## Overview

SQL type definitions must use simplified, modern syntax without deprecated features. Display widths for integer types are deprecated in MySQL 8+ and must not appear in any SQL.

## Rules

### 1. No Display Widths

Cursor must **NEVER** generate integer types with display widths.

**Forbidden examples:**
- `BIGINT(20)`
- `INT(11)`
- `TINYINT(4)`
- `SMALLINT(6)`

**Correct examples:**
- `BIGINT`
- `INT`
- `TINYINT`
- `SMALLINT`

### 2. Simplified Type Form

Cursor must **ALWAYS** use the simplified, modern SQL type form:

- `BIGINT` (not `BIGINT(20)`)
- `INT` (not `INT(11)`)
- `SMALLINT` (not `SMALLINT(6)`)
- `TINYINT` (not `TINYINT(4)`)

### 3. Display Width Deprecation

Display widths for integer types are deprecated in MySQL 8+ and must not appear in any schema, migration, or generated SQL.

Display widths are:
- Meaningless for storage (storage size is determined by the type, not the width)
- Deprecated in MySQL 8.0.17+
- Cause warnings in modern MySQL
- Not enforced by MySQL (the width is ignored)

### 4. No Automatic Display Widths

Cursor must **NOT** infer or add display widths automatically, even when:
- Reading existing schemas with display widths
- Converting from other database systems
- Generating SQL from ORM models
- Copying examples from documentation

### 5. Schema Definition Standards

All schema definitions must follow the clean doctrine:

- **No UNSIGNED** unless explicitly requested (per WOLFIE ID column standard, IDs are `BIGINT` NOT UNSIGNED)
- **No display widths** (use `BIGINT`, not `BIGINT(20)`)
- **No column-level charset/collation** (use table-level charset/collation)
- **BIGINT** for IDs (primary keys and foreign keys)
- **BIGINT** for timestamps (YYYYMMDDHHIISS format)

### 6. Normalization Requirement

If Cursor sees an existing schema with display widths, it must **NOT** preserve them. It must normalize them to the modern form.

**Example:**
```sql
-- Wrong: Preserving display width
CREATE TABLE `example` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT
);

-- Correct: Normalized to modern form
CREATE TABLE `example` (
  `id` BIGINT NOT NULL AUTO_INCREMENT
);
```

## Type Reference

### Integer Types

| Type | Correct Syntax | Forbidden Syntax |
|------|---------------|------------------|
| BIGINT | `BIGINT` | `BIGINT(20)` |
| INT | `INT` | `INT(11)` |
| SMALLINT | `SMALLINT` | `SMALLINT(6)` |
| TINYINT | `TINYINT` | `TINYINT(4)` |
| MEDIUMINT | `MEDIUMINT` | `MEDIUMINT(9)` |

### Other Types

- `VARCHAR(n)` - Width is required (storage size)
- `CHAR(n)` - Width is required (fixed size)
- `DECIMAL(p,s)` - Precision and scale are required (mathematical precision)
- `ENUM(...)` - Values are required (enumeration definition)
- `SET(...)` - Values are required (set definition)

## Migration Examples

### Creating Tables

```sql
-- Correct
CREATE TABLE `lupo_permissions` (
  `permission_id` BIGINT NOT NULL AUTO_INCREMENT,
  `target_id` BIGINT NOT NULL,
  `user_id` BIGINT DEFAULT NULL,
  `created_ymdhis` BIGINT NOT NULL,
  `is_deleted` TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (`permission_id`)
);

-- Wrong: Display widths
CREATE TABLE `lupo_permissions` (
  `permission_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `target_id` BIGINT(20) NOT NULL,
  `user_id` BIGINT(20) DEFAULT NULL,
  `created_ymdhis` BIGINT(20) NOT NULL,
  `is_deleted` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`permission_id`)
);
```

### Altering Tables

```sql
-- Correct
ALTER TABLE `lupo_permissions`
MODIFY COLUMN `permission_id` BIGINT NOT NULL AUTO_INCREMENT;

-- Wrong: Display width
ALTER TABLE `lupo_permissions`
MODIFY COLUMN `permission_id` BIGINT(20) NOT NULL AUTO_INCREMENT;
```

## Related Doctrine

- **[ID Column Standard](../internal/published/standards/WOLFIE_ID_COLUMN_STANDARD.md)** - BIGINT NOT UNSIGNED for all IDs
- **[SQL Rewrite Doctrine](SQL_REWRITE_DOCTRINE.md)** - Rules for rewriting SQL
- **[Charset Collation Doctrine](CHARSET_COLLATION_DOCTRINE.md)** - Table-level charset/collation rules
- **[Table Prefixing Doctrine](TABLE_PREFIXING_DOCTRINE.md)** - Table naming and prefixing rules

## Compliance

This doctrine is **mandatory** for all SQL generation. Cursor must:

1. ✅ Always use simplified type forms (BIGINT, not BIGINT(20))
2. ✅ Never generate display widths for integer types
3. ✅ Normalize existing schemas to remove display widths
4. ✅ Use BIGINT NOT UNSIGNED for IDs (per ID column standard)
5. ✅ Use table-level charset/collation (not column-level)
