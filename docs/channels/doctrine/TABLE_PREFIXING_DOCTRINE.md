---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
channel_key: system/kernel
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-12
author: Wolfie (Eric Robin Gerdes)
architect: Captain Wolfie
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Updated TABLE_PREFIXING_DOCTRINE.md for version 4.0.9: Added CRITICAL prefix validation rule. Table prefixes MUST contain only lowercase letters, numbers, and underscores. Hyphens, spaces, uppercase, symbols, unicode, and emoji are FORBIDDEN. Added mandatory validation rule to prevent invalid prefixes."
  mood: "FF0000"
tags:
  categories: ["documentation", "doctrine", "schema", "naming"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
file:
  title: "Table Prefixing and Primary Key Naming Doctrine"
  description: "Mandatory rules for table prefixing, primary key naming, and column naming conventions in Lupopedia"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Captain Wolfie"
---

# ðŸŸ¦ **Table Prefixing and Primary Key Naming Doctrine (Lupopedia 4.0.1)**

## **Mandatory Rules for Table and Column Naming**

This doctrine defines the mandatory rules for table prefixing, primary key naming, and column naming conventions in Lupopedia. Cursor must follow these rules for all database schema definitions, SQL rewrites, and table references.

---

## **ðŸŸ© 1. All Table Names MUST Be Prefixed**

**All table names MUST be prefixed with the dynamic installation prefix defined in `lupopedia-config.php`.**

**Prefix Behavior by Installation Type:**

- **Upgrades from Crafty Syntax 3.7.5 to Lupopedia 4.0.3**: The prefix `"lupo_"` is **enforced** (hardcoded during migration)
- **New installs starting from version 4.1.0**: The user **chooses** their own prefix during setup, stored in `lupopedia-config.php`

---

## **ðŸš¨ CRITICAL: Prefix Validation Rule (MANDATORY)**

**Table prefixes MUST contain ONLY:**
- âœ… lowercase letters (`a-z`)
- âœ… numbers (`0-9`)
- âœ… underscores (`_`)

**FORBIDDEN in table prefixes:**
- âŒ **Hyphens** (`-`) â€” **FORBIDDEN**
- âŒ Spaces â€” **FORBIDDEN**
- âŒ Uppercase letters â€” **FORBIDDEN**
- âŒ Symbols â€” **FORBIDDEN**
- âŒ Unicode â€” **FORBIDDEN**
- âŒ Emoji â€” **FORBIDDEN**

**Rationale:**
This keeps the entire system:
- âœ… Portable across database engines
- âœ… Predictable in behavior
- âœ… SQL-safe (no quoting issues)
- âœ… Filesystem-safe (no path problems)
- âœ… Doctrine-aligned (consistent rules)

**Validation Rule (MUST be enforced in installer/config):**
```php
// Installer validation rule (simple, strict, future-proof)
if (!preg_match('/^[a-z0-9_]+$/', $prefix)) {
    die("Invalid table prefix. Only lowercase letters, numbers, and underscores are allowed.");
}
```

This prevents:
- Hyphens (causes table name mismatches)
- Spaces (breaks SQL queries)
- Uppercase (inconsistent behavior)
- Symbols (SQL injection risks)
- Unicode (encoding issues)
- Emoji (yes, people try)

**Example:**
```php
// âœ… CORRECT: In lupopedia-config.php:
$table_prefix = 'lupo_';  // Valid: lowercase letters and underscore
define('LUPO_TABLE_PREFIX', $table_prefix);

// âŒ WRONG: Hyphen in prefix
$table_prefix = 'lupo-';  // INVALID: hyphen forbidden

// âŒ WRONG: Uppercase
$table_prefix = 'LUPO_';  // INVALID: uppercase forbidden

// âŒ WRONG: Spaces
$table_prefix = 'lupo _';  // INVALID: space forbidden

// Table name in code:
$table_name = LUPO_TABLE_PREFIX . 'contents';  // Results in: lupo_contents
$table_name = LUPO_TABLE_PREFIX . 'atoms';     // Results in: lupo_atoms
```

**Important for SQL Queries:**
- All SQL queries **must** read the prefix from `lupopedia-config.php` (via `LUPO_PREFIX` constant)
- Never hardcode table names in SQL - always use the dynamic prefix
- Migration SQL for upgrades (3.7.5 â†’ 4.0.3) uses `lupo_` prefix (enforced)
- Application code must always use `LUPO_PREFIX . 'table_name'` pattern

**Correct usage:**
```sql
CREATE TABLE `lupo_contents` (
  `content_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  ...
);
```

**Incorrect usage:**
```sql
-- âŒ WRONG: Hardcoded table name
CREATE TABLE `contents` (
  ...
);

-- âŒ WRONG: Table name without prefix
SELECT * FROM contents;
```

**Cursor must:**
- Always use `LUPO_PREFIX` when referencing tables in code
- Never hardcode table names (except in migration SQL where `lupo_` is enforced for upgrades)
- Never create unprefixed tables
- Read prefix from `lupopedia-config.php` (via `LUPO_PREFIX` constant) for all SQL queries
- Remember: Upgrades (3.7.5â†’4.0.3) enforce `lupo_` prefix, new installs (4.1.0+) allow user selection

---

## **ðŸŸ¦ 2. Column Names MUST NOT Include the Prefix**

**Column names MUST NOT include the prefix. Column names are universal across all installations.**

**Rationale:**
Column names are standardized across all Lupopedia installations. This ensures:
- Portability across different prefixes
- Consistency in schema definitions
- Compatibility with migrations and refactor tools
- No confusion between table prefixes and column names

**Correct usage:**
```sql
CREATE TABLE `lupo_contents` (
  `content_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `node_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  ...
);
```

**Incorrect usage:**
```sql
-- âŒ WRONG: Column name with prefix
CREATE TABLE `lupo_contents` (
  `lupo_content_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  ...
);

-- âŒ WRONG: Column name with prefix
`lupo_title` VARCHAR(255) NOT NULL,
```

**Cursor must:**
- Never add the table prefix to column names
- Use universal column names that work across all installations
- Follow standard column naming conventions (lowercase, underscores)

---

## **ðŸŸ© 3. Primary Keys MUST Follow the Pattern: singular_table_name + "_id"**

**Primary keys MUST follow the pattern:**
```
singular_table_name + "_id"
```

**Examples:**
```
Table: lupo_contents     â†’ PK: content_id
Table: lupo_atoms        â†’ PK: atom_id
Table: lupo_agents       â†’ PK: agent_id
Table: lupo_dialog_messages â†’ PK: message_id
Table: lupo_federation_nodes â†’ PK: node_id
```

**Rationale:**
This pattern ensures:
- Consistency across all tables
- Easy identification of primary keys
- Clear relationship to table names
- Standard convention that works with foreign key references (even though Lupopedia doesn't use FKs)

**Correct usage:**
```sql
CREATE TABLE `lupo_contents` (
  `content_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  ...
);

CREATE TABLE `lupo_atoms` (
  `atom_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  ...
);

CREATE TABLE `lupo_dialog_messages` (
  `message_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  ...
);
```

**Incorrect usage:**
```sql
-- âŒ WRONG: Plural primary key
CREATE TABLE `lupo_contents` (
  `contents_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  ...
);

-- âŒ WRONG: Table name in primary key
CREATE TABLE `lupo_contents` (
  `lupo_contents_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  ...
);

-- âŒ WRONG: Generic primary key name
CREATE TABLE `lupo_contents` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  ...
);
```

**Cursor must:**
- Always use singular table name for primary key
- Always append "_id" to the singular table name
- Never use plural form in primary key
- Never include table prefix in primary key name
- Never use generic "id" as primary key

---

## **ðŸŸ¦ 4. The Prefix Applies ONLY to Table Names, Never to Columns**

**Summary:**
- âœ… Table names: MUST use `LUPO_PREFIX`
- âŒ Column names: MUST NOT use prefix
- âŒ Primary keys: MUST NOT use prefix
- âŒ Foreign keys: MUST NOT use prefix

**Example of correct table definition:**
```sql
CREATE TABLE `lupo_contents` (
  `content_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `node_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` BIGINT(14) UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` BIGINT(14) UNSIGNED NOT NULL DEFAULT 0,
  `deleted_at` BIGINT(14) UNSIGNED NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Example of correct PHP code:**
```php
// âœ… CORRECT: Table name uses LUPO_PREFIX
$table = LUPO_PREFIX . 'contents';

// âœ… CORRECT: Column names don't use prefix
$query = "SELECT content_id, title, slug FROM {$table} WHERE node_id = ?";
```

---

## **ðŸŸ© 5. Summary for Cursor**

**When working with Lupopedia tables and columns:**

1. **Table names:** Always use `LUPO_PREFIX` (e.g., `LUPO_PREFIX . 'contents'`)
2. **Column names:** Never use prefix (e.g., `content_id`, not `lupo_content_id`)
3. **Primary keys:** Use singular table name + "_id" (e.g., `contents` â†’ `content_id`)
4. **All columns:** Universal names, no prefix, lowercase with underscores

**This ensures:**
- Portability across installations with different prefixes
- Consistency in schema definitions
- Compatibility with migrations and refactors
- Standard naming conventions

---

## **Related Documentation**

**Core Doctrine (Primary References):**
- [CONFIGURATION_DOCTRINE.md](CONFIGURATION_DOCTRINE.md) â€” How `LUPO_PREFIX` is defined
- [SQL_REWRITE_DOCTRINE.md](SQL_REWRITE_DOCTRINE.md) â€” Rules for rewriting SQL
- [CHARSET_COLLATION_DOCTRINE.md](CHARSET_COLLATION_DOCTRINE.md) â€” Charset and collation rules

**Schema References:**
- [Database Schema](../schema/DATABASE_SCHEMA.md) â€” Complete schema reference showing prefix usage

**Development Context (LOW Priority):**
- [Legacy Refactor Plan](../developer/modules/LEGACY_REFACTOR_PLAN.md) â€” How prefixing helps organize legacy table migration
- [Contributor Training](../developer/dev/CONTRIBUTOR_TRAINING.md) â€” Standards that include table naming requirements
- [Database Philosophy](../architecture/DATABASE_PHILOSOPHY.md) â€” Clean schema principles that prefixing supports

---

*Last Updated: January 12, 2026*  
*Version: 4.0.9*  
*Author: Captain Wolfie*

---

## **ðŸŸ© Version History**

- **4.0.9** (2026-01-12) - Added CRITICAL prefix validation rule. Hyphens are now FORBIDDEN in table prefixes. Added mandatory validation to prevent invalid prefixes.
- **4.0.1** (2026-01-08) - Initial doctrine document created
