---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/80_A_DATABASE_DESIGN_DOCTRINE.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/80_A_DATABASE_DESIGN_DOCTRINE.md"
  status: active
  when_updated: "20260422232349"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/80_database_design_doctrine.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/database-design-doctrine
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: "00"
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_80_A
  title: "PRD 80: Database Design Doctrine ? FK, AUTO_INCREMENT, Timestamps, Y2038"
  summary: "Canonical database rules: no foreign keys, no AUTO_INCREMENT, packed UTC timestamps only, Y2038 compliance, database-neutral SQL, chronological trust ladder."
---
# PRD 80: Database Design Doctrine

## Purpose

This PRD defines the **non-negotiable database design rules** for Lupopedia. These rules ensure:

- Portability across MySQL and PostgreSQL
- No reliance on database-side logic
- Deterministic, timestamp-sortable primary keys
- Y2038 compliance through packed UTC timestamps
- Safe operation on shared hosting without SUPER privileges

**Constitutional anchor:** [PRD 00 ??3](00_root_constitutional_system_requirements.md) ??? these rules are binding for all 4.0.x releases.

---

## 3.1 No Foreign Keys

Foreign keys are forbidden because:

- Shared hosting often blocks them
- They break portability and federation
- They break soft deletes and multi-agent repair workflows

All relationships must be enforced in the application layer.

**Implementation:** `install_new_lupopedia.sql` must contain zero `FOREIGN KEY` or `REFERENCES` clauses.

---

## 3.2 No AUTO_INCREMENT

**Runtime default:** For rows **created at runtime** by application code, primary keys MUST be obtained using **`IdGenerator::generate()`** unless another PRD documents an explicit allocator. **Install and seed** rows are a separate case ??? see **??3.2.1**.

This ensures (for **runtime** PKs):

- 63-bit signed-safe BIGINTs
- Timestamp-sortable IDs
- No reliance on DB sequences
- No race conditions
- No DB-specific behavior

**Implementation:** `lupo-includes/classes/IdGenerator.php`. **Runtime** `INSERT` paths must call **`IdGenerator::generate()`** for the PK unless a PRD documents another allocator. **Install/seed SQL** may use **literal PK values** per **??3.2.1**. Never pass **`null`** or rely on **`AUTO_INCREMENT`** expecting the DB to fill the PK.

**Registry and seed exceptions (summary).** `install_new_lupopedia.sql` and **`seed_*.sql`** MAY assign **fixed, low-numbered primary keys** to reserved rows without calling **`IdGenerator::generate()`** in that INSERT. Full dual-strategy rules: **??3.2.1**.

**Test:** `lupo-tests/unit/id_generation_compliance_test.php`

### 3.2.1 Primary key strategy ??? seed vs runtime

Lupopedia uses a **dual PK strategy** across many tables:

| Record type | PK shape | `created_ymdhis` (or table's create clock) | Origin |
|-------------|-----------|---------------------------------------------|--------|
| **Seed / install** | **Low, fixed BIGINT** (registry constants; not timestamp-shaped) | **Install** packed UTC, **`gmdate('YmdHis')`** at seed insert, or **`0`** = *before temporal tracking* / immemorial | **`install_new_lupopedia.sql`**, **`seed_*.sql`** |
| **Runtime** | **`IdGenerator::generate()`** ??? **`YYYYMMDDHHIISS` + 4-digit suffix** | **Same 14-digit prefix** as the new PK at insert (when the table uses that pattern) | Application **PHP** (`DatabaseFactory` / services) |

**Illustrative reserved / seed bands (authoritative values = install + seed + registries + table PRDs):**

| Table / entity | Typical pattern | Resolve IDs from |
|----------------|-----------------|------------------|
| **`lupo_actors`** | Registry-backed **low** `actor_id`; workspace layout **`actor_id` < 2026** = system hub path | **`registry.json`**, install, **PRD 01 / 15** |
| **`lupo_agents`** | **`agent_id` 1???2025** reserved for core packs | **PRD 07**, **`actor_id/registry.json` `agents` map**, install |
| **`lupo_departments`** | **`department_id` 0** (Root), **1** (domain root) + imported dept ids | **PRD 25**, install, import SQL |
| **`lupo_channels`** | **Low `channel_id`** for seeded workspace channels (e.g. coordination **42**) | **`lupo-channels/registry.json`**, install |
| **`lupo_auth_users`** | Doctrine reserves **`auth_user_id = 0`** (root); seed may add **other** low ids for operators ??? **do not** assume a single numeric story without reading **PRD 01** + seed | **PRD 01**, install |
| **`lupo_memory_nodes`** | Seed MAY use **low `memory_node_id`**; runtime uses **IdGenerator** | **PRD 38**, install |
| **`lupo_edges`**, **`lupo_memory_edges`** | Seed MAY use **low edge PK**; runtime uses **IdGenerator** | Install, **PRD 04 / 38** |
| **`lupo_permissions`**, **`lupo_rules`**, other config | Base rows often **low PK** | Install |

**Rules:**

1. **Seed** rows use **fixed PKs** defined in install/seed SQL ??? **not** `AUTO_INCREMENT`, **not** random.
2. **Runtime** rows use **`IdGenerator::generate()`** unless a PRD names another explicit allocator.
3. **`created_ymdhis` = `0`** (where allowed) means *pre-dates temporal tracking* or *immemorial*; it is **not** Unix epoch stored in the DB ??? still **packed-decimal doctrine** when non-zero.
4. For **runtime** rows whose PK is IdGenerator-shaped, **`created_ymdhis`** SHOULD equal the **14-digit prefix** of that PK at insert.
5. **Application code MUST NOT** assume every PK is timestamp-shaped; use **table + PRD** rules and **row provenance** (seed vs runtime).
6. **Per-table reserved bands** are **not** guessed in hot paths ??? load from **install**, **seed**, or documented registry files.

**Filesystem mirror (memory):** When **`lupo_memory_nodes.created_ymdhis`** is **`0`** (or too short for **`YYYYMM`**), **`MemoryExportService`** maps the mirror to **`lupo-memory/1970/01/`** using an **effective** packed UTC for path/slug only ??? see **PRD 38** ??6. **Do not** write **`lupo-memory/`** as source of truth; DB remains authoritative.

---

## 3.3 No UNSIGNED

UNSIGNED is forbidden because PostgreSQL does not support it. It breaks database neutrality.

---

## 3.4 No TRIGGERS, FUNCTIONS, or PROCEDURES

These are forbidden because shared hosting often blocks them, they break portability, and they hide logic from the application layer.

**Implementation:** `install_new_lupopedia.sql` must contain zero `CREATE TRIGGER`, `CREATE FUNCTION`, or `CREATE PROCEDURE` statements.

---

## 3.5 Timestamp format and manipulation (packed decimal `BIGINT`)

**Storage (canonical clock in the database)**

All lineage and clock columns SHALL store time as a **`BIGINT` packed decimal** in **UTC**:

`YYYYMMDDHHIISS` (fourteen digits; pad to length 14 when treating as text).

Example: `20260405212034` = 2026-04-05 21:20:34 UTC.

- **Lexical order = chronological order** for correctly padded 14-digit values (integer compare and string compare agree).
- **Forbidden column types and encodings:** `DATETIME`, `TIMESTAMP`, vendor time-with-zone types, and **Unix epoch seconds or milliseconds as the canonical persisted clock** for these columns.

**Scope vs ??3.4:** ??3.4 forbids **stored database program objects** (`CREATE TRIGGER`, `CREATE FUNCTION`, `CREATE PROCEDURE`). ??3.5 additionally forbids **built-in SQL date/time expressions and temporal defaults** in ordinary DDL/DML so all clock math stays in **PHP** and SQL stays portable across MySQL and PostgreSQL.

### 3.5.1 Persisted values MUST be packed UTC ??? `BIGINT` does not mean "epoch allowed"

A **`BIGINT`** column **can** physically store a Unix epoch count (e.g. `1743894428`). That encoding is **FORBIDDEN** for Lupopedia lineage/clock columns. The integer **MUST** decode as **fourteen-digit calendar UTC** (`YYYYMMDDHHIISS`), not "seconds since 1970-01-01."

| Forbidden as **stored** clock value | Why |
|--------------------------------------|-----|
| `time()`, `gmmktime(...)`, `mktime(...)` written to DB | Unix epoch ??? wrong encoding |
| `$_SERVER['REQUEST_TIME']`, `REQUEST_TIME_FLOAT` truncated/rounded into DB | Unix epoch |
| `strtotime(...)` / `DateTime::getTimestamp()` written to DB | Unix epoch |
| Milliseconds-since-epoch in DB | Not packed decimal |
| Any integer that is **not** a valid packed `YYYYMMDDHHIISS` | Breaks sort/compare doctrine |

**Correct persistence (examples):**

```php
// Correct ??? packed UTC
$db->insert($table, array('created_ymdhis' => timestamp_ymdhis::now()));
$db->insert($table, array('created_ymdhis' => (int) gmdate('YmdHis'))); // equivalent to now(); still packed, not epoch

// Forbidden ??? epoch in a column that must hold packed UTC
$db->insert($table, array('created_ymdhis' => time()));
```

### 3.5.2 Display timezone vs storage (separate concerns)

- **Storage:** **Always** packed **UTC** in the column. **Do not** store local wall time. **Do not** embed timezone offsets or zone names inside the timestamp integer.
- **Display:** Operator or actor "local" or preference-based time is a **UI/session/prefs** concern. Convert **after** read: interpret the row as **UTC** packed, build a **UTC** instant in PHP (e.g. **`timestamp_ymdhis::explode()`** plus **`DateTime::createFromFormat`** with **`DateTimeZone('UTC')`**), then **`setTimezone()`** to the actor's or viewer's **`DateTimeZone`** (identifier from prefs, session, or locale doctrine ??? see **identity / locale** PRDs; **do not** invent a non-existent schema column in code comments).

### 3.5.3 Canonical PHP utility: `timestamp_ymdhis`

**Canonical class:** **`timestamp_ymdhis`** ??? `lupo-includes/classes/TimestampYmdhis.php` (file name casing may vary by OS; class name is lowercase **`timestamp_ymdhis`**).

- **Persist "now":** **`timestamp_ymdhis::now()`** **or** **`(int) gmdate('YmdHis')`** (same meaning; **both** are packed UTC ??? **neither** is `time()`).
- **Arithmetic** on packed values (**add/subtract seconds, diff, interval helpers**): **MUST** use **`timestamp_ymdhis`** (**`addSeconds`**, **`subtractSeconds`**, **`diffInSeconds`**, etc.). **Do not** add `86400` (or any raw delta) to the packed integer.
- **Comparison** on packed values: integer compare **or** **`timestamp_ymdhis::isBefore` / `isAfter` / `isBetween`**.
- **Human / API strings:** **`toHuman`**, **`convert_bigint_to_iso8601`**, **`fromHuman`**, **`convert_iso8601_to_bigint`** as appropriate.

**Forbidden:** Persisting **epoch** or **non-packed** integers into clock columns; doing **calendar** math on packed fields **without** **`timestamp_ymdhis`** (or an explicitly reviewed equivalent). **Display-only** **`DateTime`** / **`DateTimeZone`** usage **after** unpacking UTC is **allowed** (see **??3.5.2**).

**Forbidden in SQL (non-exhaustive)**

| Category | Examples (do not use) |
|----------|------------------------|
| "Now" in the database | `NOW()`, `CURRENT_TIMESTAMP`, `CURRENT_DATE`, `CURDATE()`, `LOCALTIMESTAMP`, `SYSDATE` |
| Interval math in SQL | `DATE_ADD()`, `DATE_SUB()`, `INTERVAL ???` |
| Epoch bridges in SQL | `FROM_UNIXTIME()`, `UNIX_TIMESTAMP()`, `TO_TIMESTAMP()` / `TIMESTAMP '???'` casts used as clocks |
| Extraction in SQL | `DATE()`, `YEAR()`, `MONTH()`, `DAY()`, `EXTRACT(...)` on temporal types to drive filters |
| Automatic DB clocks | `DEFAULT CURRENT_TIMESTAMP`, `ON UPDATE CURRENT_TIMESTAMP`, generated "now" columns |

**Required pattern**

1. **Compute** instants and range bounds in **PHP** with **`timestamp_ymdhis`** and/or **`(int) gmdate('YmdHis')`** for "current packed UTC."
2. **Query** by comparing packed integers (or bound parameters that hold those integers): `WHERE created_ymdhis >= :t0 AND created_ymdhis < :t1` using **named placeholders** and **`PDO_DB`** ??? **not** by injecting raw values into SQL strings.
3. **Never** "add seconds" with naive integer addition on the packed value (e.g. `+ 86400` on `20260228120000`) ??? that **corrupts** the encoding. Use **`timestamp_ymdhis::addSeconds()`** (or equivalent calendar-correct conversion).

### 3.5.4 Year 2038 (Y2038) compliance

**The problem**

Unix epoch seconds in a **32-bit signed** integer overflow after **2038-01-19 03:14:07 UTC** (values wrap). That limit is **`time_t`** / epoch storage semantics, not "integers are bad."

**Lupopedia stance**

1. **Persistence:** Canonical lineage/clock columns **SHALL NOT** store Unix epoch seconds or milliseconds (**??3.5.1**). Values **SHALL** be packed **`YYYYMMDDHHIISS`** UTC in **`BIGINT`**.
2. **Runtime (tiered):** **Production** deployments **SHALL** use **64-bit PHP 7.4+** so packed UTC values and **`timestamp_ymdhis`** integer arithmetic are **Y2038-safe**. **Legacy** hosts (e.g. existing Crafty Syntax on **PHP 5.6???7.3** or **32-bit** builds) **MAY** run the tree for transitional use: **Y2038-safe packed-UTC semantics in PHP are not guaranteed** on **32-bit** (and integer overflow for fourteen-digit packed "now" can occur even before 2038). **Honest stance:** persistence in **BIGINT** remains correct; **runtime consumers** on narrow int must move to **64-bit PHP 7.4+** before relying on long-horizon clock math.
3. **No 32-bit epoch dependence for product truth:** The **database** encoding for clocks **must not** be "epoch in a big integer" ??? that reproduces **Y2038** class failures on 32-bit consumers and confuses dumps.

**Forbidden patterns (Y2038-class or doctrine violations)**

| Pattern | Why |
|---------|-----|
| `time()`, `gmmktime()`, `strtotime()` / **`DateTime::getTimestamp()`** written to clock columns | Epoch ??? **??3.5.1** |
| MySQL **`TIMESTAMP`** (or equivalent) as the canonical lineage clock type | Epoch-oriented vendor type ??? use **`BIGINT`** packed per **??3.5** |
| SQL **`UNIX_TIMESTAMP`**, **`FROM_UNIXTIME`**, **`NOW()`**, **`DATE_ADD`** for those filters | **??3.5** ??? clock math in **PHP** |
| Any clock integer that is **not** a valid fourteen-digit packed UTC instant | Breaks sort/compare and review |

**Required patterns (Y2038-safe for Lupopedia clocks)**

| Pattern | Why |
|---------|-----|
| **`timestamp_ymdhis::now()`** or **`(int) gmdate('YmdHis')`** | Packed UTC ??? **not** epoch persistence |
| **`BIGINT`** packed **`YYYYMMDDHHIISS`** | Lexical order matches chronology through year **9999** at column width |
| **`timestamp_ymdhis::addSeconds`** / **`diffInSeconds`** / interval helpers | Calendar-correct on packed fields |
| **`WHERE created_ymdhis >= :t0`** (bound packed int) | No DB date functions |

**Canonical utility**

**`timestamp_ymdhis`** (`lupo-includes/classes/TimestampYmdhis.php`) is the **canonical** class for packed-clock operations; **`(int) gmdate('YmdHis')`** is **equivalent** for **current** packed "now" only (**??3.5.3**).

```php
$now = timestamp_ymdhis::now();
$db->insert($table, array('created_ymdhis' => $now));
// FORBIDDEN for packed clock columns:
$db->insert($table, array('created_ymdhis' => time()));
```

**Industry context**

Epoch seconds remain common in APIs and databases elsewhere. For Lupopedia lineage clocks, **epoch-in-`BIGINT`** suggestions from tools or vendors are **non-compliant** unless a future PRD defines a **different** column with explicit semantics.

**Related:** Repo artifact clock and **`lupopedia.headers`** timestamps ??? **??3.5a** and **`lupo-includes/functions/time.php`** (**`lupo_pulse_temporal_anchor()`** / **`LupoPulse()`**) for **`temporal_anchor.json`**; that is **not** a substitute for column clock rules above.

---

## 3.5a Temporal anchor (official clock; the "tick" rule)

**Official clock:** All agents (**IDE**, **chat**, **PHP**, automation) MUST treat **`lupo-bin/temporal_anchor.json`** as the single source of truth for **human-facing UTC strings** used in repo artifacts. The canonical field is **`current_utc`** (14 digits, `YYYYMMDDHHMMSS` / `gmdate('YmdHis')` UTC, same string shape as DB `BIGINT` timestamps in ??3.5). **`last_session_end`** carries the previous **`current_utc`** for handoff awareness.

**Binding:** Values written into **`lupopedia.headers`** (`last_modified_utc`, `when_updated`), **`lupopedia.footer`** (`last_verified`), and **UTC date/time prefixes** on new canonical thread artifacts (per [PRD 17](17_decisions_format.md) and [TIMESTAMP doctrine](../doctrine/TIMESTAMP_DOCTRINE.md)) MUST be taken from that anchor (or the same-tick echo), not from:

- inferred "today" or "current time" inside an LLM or chat session,
- training-data cutoffs or model guesses,
- unrelated files' timestamps copied for convenience,
- **manual date entry** invented by an agent ("looks like Tuesday") ??? **forbidden**.

**If the anchor is missing or unreadable:** The agent MUST NOT guess timestamps. It MUST run **`python lupo-bin/tick.py`** (or request that the operator run it) before proceeding with time-sensitive artifact writes.

**Mechanism (repository):**

1. **IDE / CLI:** Run **`python lupo-bin/tick.py`** before a batch of such writes. It updates **`temporal_anchor.json`** (**`current_utc`**, **`last_session_end`**, **`system_year`**, **`format_standard`**) and root **`CURRENT_UTC`**.
2. **Same batch, no second tick:** Run **`python lupo-bin/echo_anchor_utc.py`** and reuse the printed value.
3. **PHP / web:** When a logged-in user loads **`admin.php`**, **`lupo_pulse_temporal_anchor()`** ( **`lupo-includes/functions/time.php`**; alias **`LupoPulse()`** ) may refresh the same JSON if the file is missing or older than **60 seconds**, so chat and IDE see a clock aligned with the server without hammering disk.

**Lupopedia session anchor (chat handoff):** For stateless LLM sessions, operators SHOULD paste a short status block that includes **`SYSTEM_TIME:`** from **`current_utc`** and **`SOURCE: lupo-bin/temporal_anchor.json`** so the model does not hallucinate a calendar.

**Root rule:** [lupo-rules/root/UTC_TEMPORAL_ANCHOR_DOCTRINE.md](../../lupo-rules/root/UTC_TEMPORAL_ANCHOR_DOCTRINE.md). **Expanded workflow:** [TICK_PY_DOCTRINE.md](../doctrine/TICK_PY_DOCTRINE.md).

**Rationale:** Language models are stateless with respect to real time; a file-backed pulse is the "session variable" that keeps audits, migrations, and multi-agent handoff aligned with **`BIGINT` UTC** in the schema.

---

## 3.6 Database Neutral SQL

All SQL must run on MySQL 8.0+ and PostgreSQL 15+.

Forbidden SQL patterns:

- `ON DUPLICATE KEY UPDATE`
- `IF NOT EXISTS` in `CREATE TABLE`
- `SHOW TABLES`
- `REPLACE INTO`
- `UNSIGNED`
- `AUTO_INCREMENT`
- `ENGINE=` or `COLLATE=` clauses
- **SQL date/time functions and DB-side clocks** ??? `NOW()`, `CURRENT_TIMESTAMP`, `DATE_ADD`, `INTERVAL`, `FROM_UNIXTIME`, `UNIX_TIMESTAMP`, `DEFAULT CURRENT_TIMESTAMP`, etc. (**full rule:** **??3.5**)

**Clarification (portable SQL vs shipped PHP introspection):** The **`SHOW TABLES`** prohibition applies to **SQL text** that is claimed **database-neutral** (one string for both MySQL and PostgreSQL). It does **not** allow **`information_schema`** in **shipped PHP** ??? **PRD 00 ??1.1 RULE 93.NO_INFORMATION_SCHEMA** forbids that on shared hosts. **MySQL-only** paths (installer, mysqli wizard, Crafty-era import) **MAY** use **`SHOW TABLES`**, **`SHOW CREATE TABLE`**, and **`DESCRIBE` / `SHOW COLUMNS`** from PHP when escaping **`LIKE`** metacharacters for literal table names.

**Implementation:** See `lupo-rules/root/DATABASE_NEUTRAL_SQL_DOCTRINE.md`.

---

## 3.7 Universal data consolidation (Chronological Trust Ladder pattern)

**Scope.** Where a table uses **`IdGenerator`**-style **timestamp-shaped** **`BIGINT`** primary keys (**18 digits**: embedded calendar year + packed UTC clock + suffix), product code **MAY** encode **trust tier** in the **first four digits** of the PK so operators read **authority / lifecycle** without extra flags. This subsection is the **constitutional summary**; **memory** normative detail is **PRD 38** ??4.2 and **??4.2.1**; **KAIROS** behavior is **PRD 37**; **install seed vs living canonical** for low fixed PKs is **PRD 79**.

**Multiple PK families:** **Install seeds** use **low fixed `BIGINT`s** from SQL/registry (**??3.2.1**, **PRD 79**). **`IdGenerator::generate()`** (**`lupo-includes/classes/IdGenerator.php`**) always returns an **18-digit** id whose embedded calendar year is **2000???2099** ??? that is the **staging-shaped** output. To allocate a **new living canonical** id (embedded year **1000???1999**), use **`toCanonicalId(IdGenerator::generate())`** **before** **`INSERT`** unless product policy deliberately persists a **draft staging** row first (**PRD 38** ??4.2.1, **PRD 79** ??2.2).

**PK trust tiers (timestamp-shaped ids and install seed rows)**

| PK shape | Tier | Mutable? | Lifespan |
|----------|------|----------|----------|
| **Low install / seed** ids (**not** timestamp-shaped; per **??3.2.1** and registry) | **System / install** | **No** (immutable per install doctrine) | Permanent |
| **18-digit** id, embedded year **1000???1999** | **Living canonical** | **Yes** ??? **UPDATE** as new evidence arrives; **id stays stable** | Permanent until soft-deleted |
| **18-digit** id, embedded year **2000???2099** (raw **`IdGenerator`** output) | **Staging / runtime** | **Yes** ??? short-lived; merged into canonical, then **soft-deleted**, or **never inserted** if converted pre-persist | Temporary |

**Consolidation flow (application layer only ??? no DB FKs, no hard deletes on lineage)**

1. New data is written as **staging** (**2000???2099** embedded year on raw **`IdGenerator`** ids) or arrives from existing staging rows.
2. **If no living canonical** exists for the logical entity (topic / **`memory_key`** / policy-defined key): **promote** ??? set canonical id to **`toCanonicalId($stagingId)`** (or **`toCanonicalId(IdGenerator::generate())`** if no staging row is persisted), populate payload, record edges from staging ??? canonical (**`promoted_to`**, **`consolidated_into`**, or install-aligned **`edge_type`**) ??? full reference **PRD 38** ??8.3 / ??4.2.1.
3. **If living canonical already exists:** **merge** non-null fields into that row (**UPDATE** canonical; **no** silent overwrite of non-null with null unless policy explicitly allows), set **`updated_ymdhis`** (packed UTC per **??3.5**), record edges staging ??? canonical (**`merged_into`** / **`consolidated_into`**).
4. **Soft-delete** consumed staging rows (**`is_deleted`**, **`deleted_ymdhis`**).
5. **Re-point** parent / child links in **application logic** so stable references target the **canonical** id.

**Benefits.** The PK band signals trust; **repeated passes refine** the canonical row; **edges + soft delete** preserve auditability; **parents** target a **stable** canonical id.

**Cross-references:** **PRD 37** ??1.2; **PRD 38** ??4.2, ??8 (Option B archive shares the **1000???1999** visual band ??? disambiguate with **`archived_to`** vs merge edges and **`memory_type` / `context`**).

---

## Related PRDs

| PRD | Title | Relationship |
|-----|-------|--------------|
| 00 | Root Constitutional System Requirements | Constitutional anchor |
| 38 | Memory Unification | Memory graph, PK trust tiers |
| 37 | KAIROS Channel Memory Consolidation | Consolidation behavior |
| 79 | Install Seed Doctrine | Seed vs runtime PK rules |
| 16 | Lupopedia Headers | Header timestamp alignment |

---

This output complies with Lupopedia Constitutional Root Rules.
