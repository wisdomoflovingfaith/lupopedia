---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created WOLFIE Timestamp Doctrine document establishing strict YYYYMMDDHHIISS format requirement for all timestamps across Lupopedia and Crafty Syntax."
tags:
  categories: ["documentation", "standards", "database"]
  collections: ["core-docs", "development-standards"]
  channels: ["dev", "public"]
file:
  title: "WOLFIE Timestamp Doctrine"
  description: "Strict timestamp format requirement: YYYYMMDDHHIISS (UTC, zero-padded, numeric) for all code, migrations, and data models"
  version: "4.0.1"
  status: published
  author: "Captain Wolfie"
---

# â±ï¸ WOLFIE Timestamp Doctrine

> **âš ï¸ DEPRECATED:** This file is maintained for historical reference.  
> **ðŸ‘‰ See [TIMESTAMP_DOCTRINE.md](../../doctrine/TIMESTAMP_DOCTRINE.md) for the canonical, authoritative version.**

---

## REQUIRED FOR ALL CODE, MIGRATIONS, AND DATA MODELS

All timestamps in Lupopedia and Crafty Syntax **MUST** use the Wolfie Timestamp Format:

```
YYYYMMDDHHIISS   (UTC, zero-padded, numeric)
```

This is a **strict requirement**. Do **NOT** use:
- âŒ UNIX epoch timestamps
- âŒ MySQL TIMESTAMP
- âŒ MySQL DATETIME
- âŒ ISO8601 strings
- âŒ Local timezone conversions
- âŒ Framework-provided timestamp helpers

The **ONLY** valid timestamp format is:

**MySQL:**
```sql
DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S')
```

**PHP:**
```php
(int) gmdate("YmdHis")
```

**Python:**
```python
from datetime import datetime, timezone
datetime.now(timezone.utc).strftime('%Y%m%d%H%M%S')
```

**JavaScript/TypeScript:**
```javascript
const now = new Date();
const year = now.getUTCFullYear();
const month = String(now.getUTCMonth() + 1).padStart(2, '0');
const day = String(now.getUTCDate()).padStart(2, '0');
const hour = String(now.getUTCHours()).padStart(2, '0');
const minute = String(now.getUTCMinutes()).padStart(2, '0');
const second = String(now.getUTCSeconds()).padStart(2, '0');
const timestamp = parseInt(`${year}${month}${day}${hour}${minute}${second}`);
```

## Scope

This rule applies to:
- âœ… All database tables
- âœ… All migrations
- âœ… All seeders
- âœ… All PHP code
- âœ… All agent-generated content
- âœ… All AI-generated code
- âœ… All documentation examples
- âœ… All WOLFIE Headers (`created_ymdhis`, `updated_ymdhis`)
- âœ… All log entries
- âœ… All audit trails
- âœ… All import/export scripts

## Enforcement

**Any AI agent generating code MUST follow this rule without exception.**

If an AI assistant attempts to use UNIX time or DATETIME, **correct it immediately** and regenerate the output using the Wolfie Timestamp Doctrine.

## Database Column Naming

All timestamp columns must use the `_ymdhis` suffix:
- `created_ymdhis` - Creation timestamp
- `updated_ymdhis` - Last update timestamp
- `deleted_ymdhis` - Soft deletion timestamp
- `published_ymdhis` - Publication timestamp
- `expires_ymdhis` - Expiration timestamp
- `start_ymdhis` - Start timestamp
- `end_ymdhis` - End timestamp

## Data Type

All timestamp columns must be stored as:
```sql
`column_name` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS'
```

Or for nullable timestamps:
```sql
`column_name` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS'
```

## Why This Format?

The `YYYYMMDDHHIISS` format is:
- **Lexicographically sortable** - String comparison works correctly
- **Timezone agnostic** - Always UTC, no ambiguity
- **DST-proof** - No daylight saving time issues
- **Human-readable** - Can be read without conversion
- **Database-agnostic** - Works on MySQL, PostgreSQL, SQLite, etc.
- **Future-proof** - No 2038 problem (unlike UNIX timestamps)
- **Fast to compare** - Integer operations are faster than string parsing
- **Portable** - Works across all programming languages and frameworks

## Migration Examples

### Correct Migration Pattern
```sql
INSERT INTO `agents` (
    `agent_id`,
    `agent_key`,
    `agent_name`,
    `created_ymdhis`,
    `updated_ymdhis`
) VALUES (
    1,
    'wolfie',
    'WOLFIE',
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S'),
    NULL
);
```

### Incorrect Patterns (DO NOT USE)
```sql
-- âŒ WRONG: Using UNIX timestamp
INSERT INTO `agents` (`created_ymdhis`) VALUES (UNIX_TIMESTAMP());

-- âŒ WRONG: Using MySQL TIMESTAMP
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP

-- âŒ WRONG: Using DATETIME
`created_at` DATETIME NOT NULL

-- âŒ WRONG: Using ISO8601 string
`created_at` VARCHAR(25) DEFAULT '2025-01-05T12:34:56Z'
```

## PHP Code Examples

### Correct PHP Pattern
```php
// âœ… CORRECT: Using gmdate with YmdHis format
$timestamp = (int) gmdate("YmdHis");

// âœ… CORRECT: For database insertion
$stmt->execute([
    ':created_ymdhis' => (int) gmdate("YmdHis"),
    ':updated_ymdhis' => (int) gmdate("YmdHis")
]);
```

### Incorrect PHP Patterns (DO NOT USE)
```php
// âŒ WRONG: Using time() (UNIX timestamp)
$timestamp = time();

// âŒ WRONG: Using date() with different format
$timestamp = date('Y-m-d H:i:s');

// âŒ WRONG: Using DateTime with ISO format
$timestamp = (new DateTime())->format('Y-m-d\TH:i:s\Z');

// âŒ WRONG: Using strtotime
$timestamp = strtotime('now');
```

## Related Documentation

- [Database Philosophy](../../architecture/DATABASE_PHILOSOPHY.md) - Core database design principles that support explicit timestamp control
- [No Triggers Doctrine](../../doctrine/NO_TRIGGERS_DOCTRINE.md) - Why database triggers are forbidden and timestamps must be explicit
- [Architecture Sync](../../architecture/ARCHITECTURE_SYNC.md) - Complete system architecture implementing timestamp doctrine
- [Database Schema](../../schema/DATABASE_SCHEMA.md) - Complete database schema reference showing BIGINT timestamp fields

## Version History

- **4.0.0** (2026-01-05) - Initial formal doctrine document created

---

*This doctrine is non-negotiable and applies to all code, migrations, and data models in Lupopedia and Crafty Syntax.*


