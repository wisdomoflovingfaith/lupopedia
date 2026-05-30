---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/TIMESTAMP_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/TIMESTAMP_DOCTRINE"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: timestamp_rules
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: "Timestamp Doctrine \u2013 One Global Time Lens"
  summary: ""
---
# file: Timestamp Doctrine – One Global Time Lens — session: L-LUPO-ROOT-THOTH — delegation: thoth:wolfie — web_path: http://www.lupopedia.com/doctrine/TIMESTAMP_DOCTRINE

# Timestamp Doctrine – One Global Time Lens

## 1. Core Principle

**Lupopedia does not shove a timezone string onto every column.**

Instead, the entire system shares a single, documented timezone context (set once in configuration) and stores every temporal value as a 14-digit BIGINT: `YYYYMMDDHHIISS`.

**Example:** `20251114083045` means November 14, 2025, 08:30:45 UTC (or configured timezone).

## 2. Why Integer Timestamps Win

| Property | Benefit |
|----------|---------|
| **Consistency** | One timezone eliminates "created in UTC, updated in PST, deleted in EST" confusion. Every log, queue, and report lines up. |
| **Sortability** | Lexicographic order matches chronological order. Index scans and CSV comparisons stay fast even on shared hosting. |
| **Human Readable** | Ops teams can glance at raw data and know exactly when something happened—no conversion scripts required. |
| **Deterministic Math** | Maintenance scripts treat timestamps as integers. Calculating windows or truncating to day/hour is a single arithmetic operation. |
| **2038-Proof** | Values live in `BIGINT` columns—not 32-bit UNIX epochs—so Lupopedia glides past the January 19, 2038 overflow that will nuke legacy INT-based systems. |

## 3. The Architect's Statement

> "Modern frameworks bolt a timezone onto every column 'for safety.' Safer for what? Creating records in UTC, updating them in PST, and deleting them in EST? What business actually needs this?"

Wolfie's philosophy: validate at the boundary, store uniformly, let automation rely on simple, reliable data.

## 4. Implementation Rules

### 4.1 Database Columns
- All timestamp columns MUST be `BIGINT`
- No `DATETIME`, `TIMESTAMP`, or `TIMESTAMPTZ` types
- No column-level timezone information

### 4.2 Application Code
- Timestamps MUST be set in application code using `gmdate('YmdHis')` (UTC) or configured timezone
- NEVER use database-generated timestamps (`CURRENT_TIMESTAMP`, `ON UPDATE`)
- Timezone is configured once, globally

### 4.3 Display
- Display functions convert integers to human-readable format on output
- Internal representation remains integer for all operations

### 4.4 Example

```php
// Setting a timestamp
$now = (int) gmdate('YmdHis'); // 20260319143045

// Inserting into database
$sql = "INSERT INTO lupo_events (event_id, event_time) VALUES (?, ?)";
$stmt = $db->prepare($sql);
$stmt->execute([$event_id, $now]);

// Calculating a window
$one_day_ago = $now - 1000000; // 24 hours in YmdHis terms
// (Note: proper date arithmetic uses dedicated functions, not raw subtraction)
```

### 4.5 Installer exception (database access layer; same constitutional carve-out)

Timestamp *encoding* (§4.1–4.3) applies to **runtime** application code and schema. **Separately**, the **installer exception** for **how** the database is accessed during install is defined in **`docs/doctrine/DATABASE_DOCTRINE.md`** — **Runtime database access (PDO_DB) and installer exception**. In short: **runtime** MUST use **`PDO_DB`** only and **named placeholders**; **no mysqli** in runtime; **`install.php`** / wizard **may** use **mysqli** only for the narrow purposes listed there (buffered multi-statement execution, prefix migration testing, schema import, fallback validation) and **must not** move mysqli into runtime. This is a **controlled, constitutional carve-out**; see DATABASE_DOCTRINE for the numbered rule.

## 5. Comparison: Modern Frameworks vs Lupopedia

| Framework Approach | Lupopedia Approach |
|--------------------|---------------------|
| TIMESTAMPTZ per column | One global timezone |
| Stored as string/object | Stored as integer |
| Timezone logic in queries | Timezone logic at boundary |
| Requires conversion for math | Direct integer comparison |
| 2038 risk on 32-bit systems | 2038-proof (BIGINT) |

## 6. Historical Context

This discipline comes directly from Crafty Syntax (2003). Wolfie built it then, proved it across 1.2M installations and 22 years, and encoded it as doctrine for Lupopedia.

> "The same integer timestamps that kept 1.2M installs consistent when cron jobs ran on bargain-bin hosts now power Lupopedia's multi-agent coordination."

## 7. Enforcement

- Schema validation scripts check for `DATETIME`/`TIMESTAMP` columns
- Code review flags any use of `CURRENT_TIMESTAMP`
- Migration tools convert legacy timestamp formats during upgrade

## 8. See Also

- `docs/origin/WOLFIE_ORIGIN.md` – Why this philosophy exists
- `database/lupopedia/mysql/install/install_new_lupopedia.sql` – Implementation
- `docs/doctrine/DATABASE_DOCTRINE.md` – Broader database rules
