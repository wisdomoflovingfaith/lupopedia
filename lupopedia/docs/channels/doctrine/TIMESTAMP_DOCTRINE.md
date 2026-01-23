---
wolfie.header.identity: TIMESTAMP_DOCTRINE
wolfie.header.placement: /docs/doctrine/TIMESTAMP_DOCTRINE.md
wolfie.header.version: 4.0.9
channel_key: system/kernel
wolfie.header.dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created canonical Timestamp Doctrine for version 4.0.9. This is the FINAL, AUTHORITATIVE version. All AI agents MUST follow this doctrine without exception. All timestamps MUST be BIGINT(14) YYYYMMDDHHMMSS UTC format. No exceptions."
  mood: "FF0000"
tags:
  categories: ["doctrine", "database", "standards"]
  collections: ["core-docs"]
  channels: ["dev", "public", "doctrine"]
file:
  title: "Lupopedia Timestamp Doctrine"
  description: "Canonical, final, authoritative doctrine for timestamp storage and manipulation. MANDATORY for all code, migrations, and AI agents."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Lupopedia Timestamp Doctrine
Version: 1.0  
Author: Wolfie (Eric Robin Gerdes)  
Status: Canonical Architecture Doctrine

---

## 1. Purpose
This doctrine defines the only valid way Lupopedia stores, manipulates, and interprets time.  
It exists to ensure:

- determinism  
- portability  
- database independence  
- digital archaeology  
- zero-magic behavior  
- long-term survivability  

All contributors, agents, and tools must follow this doctrine without exception.

---

## 2. Storage Format (Mandatory)
All timestamps in Lupopedia MUST be stored as:

### BIGINT(14)  
### Format: `YYYYMMDDHHMMSS`  
### Timezone: **UTC ONLY**

Examples:
- `20260112183000`
- `19991231235959`

This format is:

- sortable as a number  
- sortable as a string  
- human-readable  
- machine-readable  
- timezone-free  
- DST-free  
- framework-agnostic  
- 2038-proof  
- migration-stable  

No other storage format is permitted.

---

## 3. Forbidden Formats (Prohibited)
Lupopedia MUST NOT use:

- `DATETIME`  
- `TIMESTAMP`  
- epoch seconds  
- ISO8601 strings  
- timezone-aware fields  
- ORM timestamp helpers  
- database-native date arithmetic  

These formats introduce ambiguity, portability issues, and long-term instability.

---

## 4. Time Arithmetic (Allowed)
Time arithmetic MUST be performed in **application code**, never in SQL.

Valid operations include:

- add seconds  
- subtract seconds  
- compute differences  
- convert to/from epoch internally ONLY for arithmetic  

Epoch MAY be used internally for math, but MUST NEVER be stored or returned.

All arithmetic MUST use:

### `timestamp_ymdhis` helper functions

If unavailable, fallback logic MUST:

1. parse `YYYYMMDDHHMMSS` into components  
2. convert to epoch  
3. add/subtract seconds  
4. convert back to `YYYYMMDDHHMMSS`  

This ensures correctness without relying on database-specific behavior.

---

## 5. Display Time (User-Facing)
All stored timestamps are UTC.

User-facing time is computed as:

```
display_time = utc_timestamp + timezone_offset
```

Where:

- `timezone_offset` is `DECIMAL(4,2)`  
- stored per user  
- represents hours offset from UTC  

The database NEVER stores local time.

---

## 6. Canonical Time Rule for All Agents (MANDATORY)

**All timestamps, dates, and 'when' values must be expressed in UTC.**

**MANDATORY: Agents must never infer the current date or time from:**
- File metadata
- OS time
- Commit history
- File content
- System clocks
- Local timezone information
- File modification timestamps
- Git commit timestamps
- Build timestamps
- Any other implicit time source

**If the current UTC date/time is required, the agent must:**
- Request it explicitly from the user
- Wait for explicit confirmation
- Never assume or infer the current time
- Never use system time without explicit permission

**Exception:** If the current UTC date/time has already been provided in the current session, the agent may use that value for subsequent operations within the same session.

**Rationale:**
- Prevents timestamp drift across different systems
- Ensures consistency in multi-agent workflows
- Avoids timezone confusion
- Maintains accurate historical records
- Prevents agents from using stale or incorrect time information
- Ensures reproducibility across different environments
- Maintains temporal accuracy in distributed systems

**Example - Correct Behavior:**
```
Agent: "I need to create a timestamp. What is the current UTC date and time?"
User: "2026-01-13 18:30:00 UTC"
Agent: [Creates timestamp: 20260113183000]
```

**Example - Incorrect Behavior:**
```
Agent: [Checks system time: 2026-01-13 10:30:00 PST]
Agent: [Converts to UTC: 2026-01-13 18:30:00 UTC]
Agent: [Creates timestamp: 20260113183000] ❌ WRONG - Never infer time
```

**This rule applies to:**
- Dialog entry timestamps
- WOLFIE header dates
- File modification dates
- Version timestamps
- Database timestamps
- Log timestamps
- Any temporal reference in documentation or code
- All BIGINT(14) YYYYMMDDHHMMSS values

**Enforcement:**
- All AI agents (Kiro, Claude, Cascade, Junie, Terminal AI) must follow this rule
- Human author (Captain_wolfie) provides authoritative time when needed
- No exceptions for "convenience" or "automation"
- Violations are doctrine violations and must be corrected

---

## 7. Doctrine Enforcement
All code MUST:

- store UTC  
- store BIGINT(14)  
- store `YYYYMMDDHHMMSS`  
- use `timestamp_ymdhis` for arithmetic  
- avoid SQL date functions  
- avoid framework date helpers  
- avoid timezone conversions in storage  

Cursor MUST read this doctrine and enforce it.

Any deviation is a doctrine violation.

---

## 8. Digital Archaeology
This format ensures:

- future contributors can read timestamps without tooling  
- migrations remain stable across decades  
- no dependency on MySQL, PHP, or OS time behavior  
- timestamps remain sortable and comparable forever  
- schema remains portable across all SQL engines  

This is essential for Lupopedia's long-term survivability.

---

## 9. Summary (Non-Negotiable)
- Store UTC  
- Store BIGINT(14)  
- Store `YYYYMMDDHHMMSS`  
- Never store epoch  
- Never store DATETIME  
- Never store TIMESTAMP  
- Never store local time  
- Never rely on SQL date functions  

This doctrine is absolute and binding.

---

## 10. Code Examples

### PHP (Correct)
```php
// Get current UTC timestamp
$now = (int) gmdate('YmdHis');

// Use timestamp_ymdhis class for arithmetic
if (class_exists('timestamp_ymdhis')) {
    $expires = timestamp_ymdhis::addSeconds($now, 86400);
} else {
    // Fallback: convert to epoch, add seconds, convert back
    $epoch = gmmktime(
        (int)substr($now, 8, 2),   // hour
        (int)substr($now, 10, 2),  // minute
        (int)substr($now, 12, 2),   // second
        (int)substr($now, 4, 2),    // month
        (int)substr($now, 6, 2),    // day
        (int)substr($now, 0, 4)     // year
    );
    $expires = (int)gmdate('YmdHis', $epoch + 86400);
}
```

### PHP (WRONG - DO NOT USE)
```php
// ❌ WRONG: Adding seconds directly to YYYYMMDDHHMMSS
$expires = $now + 86400;  // This produces invalid timestamp!

// ❌ WRONG: Using time() (epoch)
$timestamp = time();

// ❌ WRONG: Using DATETIME
$timestamp = date('Y-m-d H:i:s');

// ❌ WRONG: Using SQL date functions in application code
$sql = "SELECT DATE_ADD(NOW(), INTERVAL 1 DAY)";
```

### SQL (Correct)
```sql
-- Get current UTC timestamp
SELECT DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS now_ymdhis;

-- Insert with explicit timestamp
INSERT INTO lupo_sessions (
    session_id,
    created_ymdhis,
    expires_ymdhis
) VALUES (
    'abc123',
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S'),
    DATE_FORMAT(DATE_ADD(UTC_TIMESTAMP(), INTERVAL 1 DAY), '%Y%m%d%H%i%S')
);
```

### SQL (WRONG - DO NOT USE)
```sql
-- ❌ WRONG: Using TIMESTAMP column type
CREATE TABLE test (
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ❌ WRONG: Using DATETIME column type
CREATE TABLE test (
    created_at DATETIME NOT NULL
);

-- ❌ WRONG: Storing epoch seconds
CREATE TABLE test (
    created_at INT UNSIGNED NOT NULL  -- epoch seconds
);
```

---

## 10. Database Column Definition

All timestamp columns MUST be defined as:

```sql
`column_name` BIGINT(14) NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS'
```

Or for nullable timestamps:

```sql
`column_name` BIGINT(14) DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS'
```

Column naming convention: Use `_ymdhis` suffix:
- `created_ymdhis`
- `updated_ymdhis`
- `deleted_ymdhis`
- `expires_ymdhis`
- `login_ymdhis`
- `last_seen_ymdhis`

---

## 11. AI Agent Requirements

**CRITICAL:** All AI agents (Cursor, Copilot, DeepSeek, Claude, Gemini, etc.) MUST:

1. Read this doctrine before generating any timestamp-related code
2. Use `BIGINT(14)` for all timestamp columns
3. Use `YYYYMMDDHHMMSS` format (14 digits, UTC)
4. Use `timestamp_ymdhis` class for arithmetic
5. Never suggest DATETIME, TIMESTAMP, or epoch storage
6. Never add seconds directly to YYYYMMDDHHMMSS timestamps
7. Never use SQL date functions for arithmetic in application code

**Violation of this doctrine is a critical error and must be corrected immediately.**

---

*This doctrine is canonical, final, and non-negotiable. It applies to all code, migrations, data models, and AI-generated content in Lupopedia.*
