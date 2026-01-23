---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-08
author: Wolfie (Eric Robin Gerdes)
architect: Captain Wolfie
dialog:
  speaker: cursor
  target: @everyone
  message: "Created CHARSET_COLLATION_DOCTRINE.md documenting mandatory rules for charset and collation usage in Lupopedia schema definitions."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "schema", "charset"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
file:
  title: "Charset and Collation Doctrine"
  description: "Mandatory rules for charset and collation usage in Lupopedia schema definitions"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Captain Wolfie"
---

# ðŸŸ¦ **Charset and Collation Doctrine (Lupopedia 4.0.1)**

## **Mandatory Rules for Charset and Collation Usage**

This doctrine defines the mandatory rules for charset and collation usage in Lupopedia schema definitions. Cursor must follow these rules for all CREATE TABLE statements, SQL rewrites, and schema definitions.

---

## **ðŸŸ© 1. Charset and Collation MUST Be Defined ONLY at the TABLE Level**

**Charset and collation MUST be defined ONLY at the TABLE level, never at the column level.**

**Correct usage:**
```sql
CREATE TABLE `lupo_contents` (
  `content_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `body` TEXT NULL,
  `metadata` JSON NULL,
  ...
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Incorrect usage:**
```sql
-- âŒ WRONG: Column-level charset/collation
CREATE TABLE `lupo_contents` (
  `title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  ...
) ENGINE=InnoDB;
```

**Rationale:**
- Cleaner schema definitions
- Consistent encoding across all columns
- Easier to maintain and modify
- Standard MySQL/MariaDB best practice
- No redundant specifications

---

## **ðŸŸ¦ 2. Cursor MUST NOT Add CHARACTER SET or COLLATE Clauses at the Column Level**

**Column definitions must NEVER include charset or collation.**

**Correct column definitions:**
```sql
`title` VARCHAR(255) NOT NULL
`body` TEXT NULL
`metadata` JSON NULL
`slug` VARCHAR(255) NOT NULL
```

**Incorrect column definitions:**
```sql
-- âŒ WRONG: Column-level charset/collation
`title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL

-- âŒ WRONG: Column-level charset/collation
`body` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL

-- âŒ WRONG: Column-level charset/collation
`slug` VARCHAR(255) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
```

**Cursor must:**
- Never add `CHARACTER SET` to column definitions
- Never add `COLLATE` to column definitions
- Always keep column definitions clean and simple
- Let table-level charset/collation apply to all columns

---

## **ðŸŸ© 3. Cursor MUST NOT Generate MySQLâ€‘8â€‘Only Collations**

**Cursor MUST NOT generate MySQL 8.0â€‘only collations such as:**
- `utf8mb4_0900_ai_ci`
- `utf8mb4_0900_as_ci`
- `utf8mb4_0900_bin`
- Any `utf8mb4_0900_*` collation

**These are forbidden for:**
- Portability (not supported in MySQL 5.7, MariaDB 10.x)
- MariaDB compatibility (MariaDB uses different collations)
- Shared hosting compatibility (many hosts still use MySQL 5.7 or MariaDB)
- Legacy environment support

**Correct collation:**
```sql
COLLATE=utf8mb4_unicode_ci
```

**Incorrect collations:**
```sql
-- âŒ WRONG: MySQL 8.0-only collation
COLLATE=utf8mb4_0900_ai_ci

-- âŒ WRONG: MySQL 8.0-only collation
COLLATE=utf8mb4_0900_as_ci

-- âŒ WRONG: MySQL 8.0-only collation
COLLATE=utf8mb4_0900_bin
```

**Cursor must:**
- Always use `utf8mb4_unicode_ci` for table-level collation
- Never use `utf8mb4_0900_*` collations
- Check for and remove any MySQL 8.0-only collations
- Ensure compatibility with MySQL 5.7+ and MariaDB 10.3+

---

## **ðŸŸ¦ 4. All VARCHAR, TEXT, and JSON Columns MUST Be Clean**

**All VARCHAR, TEXT, and JSON columns MUST be defined without charset/collation clauses.**

**Correct usage:**
```sql
CREATE TABLE `lupo_contents` (
  `content_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `body` TEXT NULL,
  `excerpt` VARCHAR(500) NULL,
  `metadata` JSON NULL,
  `slug` VARCHAR(255) NOT NULL,
  ...
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Incorrect usage:**
```sql
-- âŒ WRONG: Column-level charset/collation
CREATE TABLE `lupo_contents` (
  `content_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `metadata` JSON CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  ...
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Cursor must:**
- Keep all VARCHAR, TEXT, and JSON column definitions clean
- Never add charset/collation to individual columns
- Let table-level charset/collation apply automatically

---

## **ðŸŸ© 5. If Cursor Encounters Columnâ€‘Level Charset/Collation, It MUST Remove It**

**If Cursor encounters existing SQL with column-level charset/collation, it MUST remove it during rewrites.**

**Before (incorrect):**
```sql
CREATE TABLE `lupo_contents` (
  `title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  ...
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**After (correct):**
```sql
CREATE TABLE `lupo_contents` (
  `title` VARCHAR(255) NOT NULL,
  `body` TEXT NULL,
  ...
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Cursor must:**
- Strip out `CHARACTER SET utf8mb4` from column definitions
- Strip out `COLLATE utf8mb4_*` from column definitions
- Ensure table-level charset/collation is present
- Clean up legacy SQL during rewrites

---

## **ðŸŸ¦ 6. Standard Table Definition Format**

**All CREATE TABLE statements MUST follow this format:**
```sql
CREATE TABLE `lupo_tablename` (
  `column_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `column_name` VARCHAR(255) NOT NULL,
  `text_column` TEXT NULL,
  `json_column` JSON NULL,
  ...
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Key points:**
- Table name uses `LUPO_PREFIX` (see [TABLE_PREFIXING_DOCTRINE.md](TABLE_PREFIXING_DOCTRINE.md))
- Columns are clean (no charset/collation)
- Charset/collation only at table level
- Uses `utf8mb4_unicode_ci` (not MySQL 8.0-only collations)

---

## **ðŸŸ© 7. Summary for Cursor**

**When working with Lupopedia schema definitions:**

1. **Table level:** Always include `DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci`
2. **Column level:** Never include `CHARACTER SET` or `COLLATE`
3. **Collation:** Always use `utf8mb4_unicode_ci` (never `utf8mb4_0900_*`)
4. **Cleanup:** Remove column-level charset/collation from existing SQL
5. **VARCHAR/TEXT/JSON:** Keep definitions clean and simple

**This ensures:**
- Portability across MySQL 5.7+, MySQL 8.0+, and MariaDB 10.3+
- Compatibility with shared hosting environments
- Clean, maintainable schema definitions
- Consistent encoding across all columns

---

## **Related Documentation**

**Core Doctrine (Primary References):**
- [TABLE_PREFIXING_DOCTRINE.md](TABLE_PREFIXING_DOCTRINE.md) â€” Table and column naming rules
- [SQL_REWRITE_DOCTRINE.md](SQL_REWRITE_DOCTRINE.md) â€” Rules for rewriting SQL
- [CONFIGURATION_DOCTRINE.md](CONFIGURATION_DOCTRINE.md) â€” Configuration and prefix definition

**Schema References:**
- [Database Schema](../schema/DATABASE_SCHEMA.md) â€” Complete schema reference showing charset/collation usage

**Development Context (LOW Priority):**
- [Database Philosophy](../architecture/DATABASE_PHILOSOPHY.md) â€” Application-first principles that support clean schema design
- [For Installers and Users](../developer/dev/FOR_INSTALLERS_AND_USERS.md) â€” User-friendly explanation of database compatibility
- [Contributor Training](../developer/dev/CONTRIBUTOR_TRAINING.md) â€” Standards that include charset/collation requirements

---

*Last Updated: January 2026*  
*Version: 4.0.1*  
*Author: Captain Wolfie*
