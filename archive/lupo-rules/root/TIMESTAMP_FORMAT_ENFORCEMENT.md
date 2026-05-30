---
lupopedia.headers:
  lupopedia.version: "4.0.89"
  lupopedia.schema: rule
  file_path_from_root: "lupo-rules/root/TIMESTAMP_FORMAT_ENFORCEMENT.md"
  web_path: "http://www.lupopedia.com/lupo-rules/root/TIMESTAMP_FORMAT_ENFORCEMENT.md"
  last_modified_utc: "20260406044907"
  when_updated: "20260406044907"
  system_version: "4.0.89"
  channel_id: 42
  thread_id: "timestamp-doctrine"
  author:
    type: "actor"
    id: 1
    name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: rule
  artifact_kind: root_doctrine
  purpose: Critical enforcement rule for YYYYMMDDHHIISS timestamp format - prohibits Unix time usage
  tags:
  - "timestamp"
  - "format"
  - "enforcement"
  - "doctrine"
  - "critical"
  - "ymlmdhiss"
  - "bigint"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-rules/root/TIMESTAMP_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: Core timestamp format specification
    - to: "lupo-rules/root/FILE_BOUNDARY_VALIDATION_RULE.md"
      type: references
      weight: 1.0
      reason: Filename timestamp validation
    - to: "lupo-rules/root/LUPOPEDIA_HEADERS_FORMAT.md"
      type: references
      weight: 1.0
      reason: Header timestamp requirements
    - to: "lupo-scripts/propagate_agent_rules.php"
      type: references
      weight: 1.0
      reason: Rule propagation script integration
lupopedia.footer:
  last_verified: "20260328120000"
  verified_by:
    identity_type: actor
    actor_id: 1
    agent_name_identity: "WOLFIE"
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: "cascade"
  orchestrator: "wolfie:root"
  next_action:
  - Update rule propagation script to inject timestamp notices
  - Run propagation to all agents
  - Add to pre-commit hook validation
  - Update code review checklist
---

# file: TIMESTAMP_FORMAT_ENFORCEMENT.md — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupo-rules/root/TIMESTAMP_FORMAT_ENFORCEMENT.md

# Timestamp Format Enforcement Doctrine

## ⚠️ CRITICAL: LUPOPEDIA DOES NOT USE UNIX TIME ⚠️

**All timestamps in Lupopedia are BIGINT UTC in YYYYMMDDHHIISS format.**

**THIS IS NOT UNIX TIME. DO NOT USE time(). DO NOT USE date() WITHOUT CONVERSION.**

## The Format
```
YYYYMMDDHHIISS
|||||||||||+- Seconds (00-59)
||||||||||+-- Minutes (00-59)
|||||||||+--- Hours (00-23)
||||||||+---- Day (01-31)
|||||||+----- Month (01-12)
||||||+------ Year (0000-9999)
|||||+------- Year
||||+-------- Year
|||+--------- Year
||+---------- Year
|+----------- Year
+------------ Year
```

**Example:** `20260328120000` = March 28, 2026, 12:00:00 UTC

## ✅ CORRECT CODE

### PHP

```php
// Get current timestamp
$now = gmdate('YmdHis');  // "20260328120000"

// Add 1 hour (use DateTime, never integer addition)
$dt = DateTime::createFromFormat('YmdHis', $timestamp);
$dt->modify('+1 hour');
$new = $dt->format('YmdHis');

// Compare (works as integer)
if ($timestamp1 > $timestamp2) { ... }

// Convert to Unix for display ONLY
$unix = DateTime::createFromFormat('YmdHis', $timestamp)->getTimestamp();
$display = date('Y-m-d H:i:s', $unix);
```

### Python

```python
from datetime import datetime

# Get current timestamp
now = datetime.utcnow().strftime('%Y%m%d%H%M%S')  # "20260328120000"

# Add 1 hour
dt = datetime.strptime(timestamp, '%Y%m%d%H%M%S')
from datetime import timedelta
dt = dt + timedelta(hours=1)
new = dt.strftime('%Y%m%d%H%M%S')
```

### SQL

```sql
-- ✅ CORRECT
INSERT INTO table (created_ymdhis) VALUES (20260328120000);

-- Convert from datetime (if needed)
UPDATE table SET created_ymdhis = DATE_FORMAT(NOW(), '%Y%m%d%H%i%s');
```

## ❌ INCORRECT CODE (NEVER DO THIS)

### PHP

```php
// WRONG - This is Unix time
$now = time();
$now = microtime(true);

// WRONG - This adds seconds to YYYYMMDDHHIISS
$tomorrow = $timestamp + 86400;  // 20260328120000 + 86400 = 20260328206400 (invalid)

// WRONG - This treats YYYYMMDDHHIISS as Unix time
$date = date('Y-m-d', $timestamp);
```

### SQL

```sql
-- WRONG - This inserts Unix time
INSERT INTO table (created_ymdhis) VALUES (UNIX_TIMESTAMP());

-- WRONG - This inserts MySQL datetime
INSERT INTO table (created_ymdhis) VALUES (NOW());
```

## Enforcement

### 1. Code Review Checklist

Every PR must verify:

- [ ] No `time()` calls for timestamps
- [ ] No integer arithmetic on timestamp values
- [ ] All timestamp generation uses `gmdate('YmdHis')` in PHP
- [ ] All timestamp generation uses `datetime.utcnow().strftime('%Y%m%d%H%M%S')` in Python

### 2. Automated Checks

The pre-commit hook will reject code containing:

- `time()` (unless in legacy compatibility code)
- `date('YmdHis')` without `gmdate`
- `UNIX_TIMESTAMP()` in SQL
- `NOW()` in SQL (for timestamp columns)

### 3. Mandatory Header Comment

Every PHP file that handles timestamps MUST include this comment at the top:

```php
/**
 * TIMESTAMP NOTICE: Lupopedia uses BIGINT UTC YYYYMMDDHHIISS format.
 * Do NOT use time() or integer arithmetic on timestamps.
 * Use gmdate('YmdHis') for current time.
 * Use DateTime::createFromFormat('YmdHis', $ts) for manipulation.
 */
```

Every Python file that handles timestamps MUST include this comment:

```python
# TIMESTAMP NOTICE: Lupopedia uses BIGINT UTC YYYYMMDDHHIISS format.
# Do NOT use time.time() or integer arithmetic on timestamps.
# Use datetime.utcnow().strftime('%Y%m%d%H%M%S') for current time.
# Use datetime.strptime(ts, '%Y%m%d%H%M%S') for manipulation.
```

## Why This Matters

- **Crafty Syntax compatibility** — Existing data uses YYYYMMDDHHIISS
- **Human readability** — Raw timestamps are readable in the database
- **Sorting** — Integer comparison works chronologically
- **No 2038 problem** — YYYYMMDDHHIISS works for any year

## Related Rules

- **TIMESTAMP_DOCTRINE.md** — Format specification
- **FILE_BOUNDARY_VALIDATION_RULE.md** — Validates filenames use this format
- **LUPOPEDIA_HEADERS_FORMAT.md** — Header timestamps use this format

---

## Additional: Update Rule Propagation Script

Add to `lupo-scripts/propagate_agent_rules.php` to inject the timestamp notice into every agent's rule files:

```php
/**
 * Inject timestamp format notice into generated rule files
 */
function inject_timestamp_notice($content, $language = 'php')
{
    $notice_php = "/**\n * TIMESTAMP NOTICE: Lupopedia uses BIGINT UTC YYYYMMDDHHIISS format.\n * Do NOT use time() or integer arithmetic on timestamps.\n * Use gmdate('YmdHis') for current time.\n * Use DateTime::createFromFormat('YmdHis', \$ts) for manipulation.\n */\n\n";
    
    $notice_python = "# TIMESTAMP NOTICE: Lupopedia uses BIGINT UTC YYYYMMDDHHIISS format.\n# Do NOT use time.time() or integer arithmetic on timestamps.\n# Use datetime.utcnow().strftime('%Y%m%d%H%M%S') for current time.\n# Use datetime.strptime(ts, '%Y%m%d%H%M%S') for manipulation.\n\n";
    
    if ($language === 'php') {
        return $notice_php . $content;
    } elseif ($language === 'python') {
        return $notice_python . $content;
    }
    return $content;
}
```

## Summary

| Action | Owner |
|--------|-------|
| Create TIMESTAMP_FORMAT_ENFORCEMENT.md | THOTH |
| Update root rules index | THOTH |
| Update propagation script | HEPHAESTUS |
| Run propagation to all agents | HEPHAESTUS |

---

**WOLFIE (actor_id 1)** — This new root rule, combined with mandatory header comments, will ensure that every generated rule file reminds agents about the timestamp format. The propagation script will inject the notice automatically. This should reduce the AI defaulting to Unix time.
