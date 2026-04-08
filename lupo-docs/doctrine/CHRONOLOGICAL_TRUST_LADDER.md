---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260408113851"
  file_path_from_root: "lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md"
  last_modified_utc: "20260408113851"
  federation_node_id: 0
  channel_id: 42
  thread_id: "chronological-trust-ladder"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: doctrine
  artifact_kind: constitutional
  purpose: "PK-encoded trust tiers based on deterministic time-based IDs — seed, living canonical, staging"
  tags:
    - doctrine
    - trust_ladder
    - pk_tiers
    - chronological
    - constitutional
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor — PK generation and timestamp rules"
    - to: "lupo-docs/prd/38_memory_unification.md"
      type: implements
      weight: 1.0
      reason: "Memory nodes follow trust ladder tiers"
    - to: "lupo-docs/prd/41_install_seed_doctrine.md"
      type: references
      weight: 1.0
      reason: "Tier 0 — seed/install records"
    - to: "lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md"
      type: references
      weight: 1.0
      reason: "Authoritative table participation registry"
    - to: "lupo-docs/doctrine/RETENTION_POLICY.md"
      type: references
      weight: 1.0
      reason: "Staging retention and GC alignment"
lupopedia.footer:
  last_verified: "20260408113851"
  verified_by:
    identity_type: actor
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
---

# Chronological Trust Ladder Doctrine

## 1. Overview

The Chronological Trust Ladder (originally developed for the City of Honolulu in 2000, generalized for Lupopedia) encodes **trust level** directly into the primary key of timestamp-shaped records.

**Core insight:** A human or AI can look at a PK and immediately know:

- Is this a **seed** record (installed with system)?
- Is this **living canonical** (verified, trusted, mutable)?
- Is this **staging** (temporary, unverified, should be merged)?

No extra status column needed. The PK tells the story.

**Normative requirement:** This doctrine MUST be paired with the guardrails in **§9**. References without definitions are not sufficient for enforcement.

---

## 2. The Three Tiers

| Tier | PK Format | Embedded year (first 4 digits, when 18-digit layout) | Trust | Mutability | Lifespan |
|------|-----------|--------------------------------------------------------|-------|------------|----------|
| **0. Seed/Install** | Low reserved / registry **`BIGINT`** (not from **`IdGenerator`**) | N/A | HIGHEST | Immutable | Permanent |
| **1. Living Canonical** | 18-digit timestamp layout | **1000–1999** | HIGH | Mutable (UPDATE as new evidence arrives) | Permanent |
| **2. Staging** | 18-digit timestamp layout | **2000–2099** (**`IdGenerator`** norm; wider bands only where table policy explicitly allows) | LOW | Mutable (short-lived, then merged and soft-deleted) | Temporary |

**Registry:** `lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md` is the authoritative list of which tables participate in which tier semantics.

### 2.0 Visual ID examples

| Type | Example ID | Year band | Meaning |
|------|------------|-----------|---------|
| **Seed** | `42` | N/A | Install-time / registry-reserved (short numeric; not 18-digit layout) |
| **Living canonical** | `102604081234567890` | **1026** | Trusted truth; mutable; stable id after promotion |
| **Staging** | `202604081234567890` | **2026** | New / unverified; staging-shaped (**`IdGenerator`**); merge or delete expected |
| **Promoted (staging → canonical)** | `202604081234567890` → `102604081234567890` | **2026** → **1026** | Staging id becomes canonical via **`toCanonicalIdSafe()`** (collision-safe insert); suffix may bump on collision |

Illustrative only — suffix digits vary with **`IdGenerator`**; always validate with **`validateTrustLadderPk()`** / **`validateFormat()`** as required.

### 2.0.1 Year band epoch note

**Why 1000–1999 for living canonical?** Intentional, not a typo. Lower embedded “year” digits are chosen so **human readers** associate **1000–1999** with **established / canonical** truth and **2000–2099** with **newer / staging** material. The bands encode **trust tier**, not calendar history.

**Year 3000 horizon:** **`toCanonicalId()`** (subtract 1000 from the embedded four-digit year when **≥ 2000**) behaves as designed while staging ids use embedded years **2000–2999** in the generator’s supported range. If a staging id ever used embedded year **3000+**, the transform would overlap bands used for other tiers — **not a practical concern** for a system anchored in **2026**. If Lupopedia were still running near a **3000+** calendar era, a **constitutional amendment** and PK policy review would be required (see also **Appendix A**).

### 2.1 IdGenerator Behavior

**`IdGenerator::generate()`** returns a **staging-shaped** id: embedded calendar year in the **2000–2099** band (**18 digits**). Every new runtime id from the generator starts as **low-trust staging**.

```php
$stagingId = IdGenerator::generate();  // e.g., 202604081200001234 (year 2026)
```

### 2.2 Normative PK shape validation (ladder tables)

See **§2.2.1** first for **storage vs PHP representation** (BIGINT columns, string handling, PDO).

Before **any** INSERT or UPDATE that persists a PK on a **ladder-participating** table (see registry), the application **MUST** validate shape using **`IdGenerator::validateTrustLadderPk()`** per **§2.2.2**.

Raw output of **`IdGenerator::generate()`** on **non-ladder** code paths (e.g. dialog messages) **MUST** additionally satisfy **`IdGenerator::validateFormat()`** per **§2.2.3** when that path requires strict generator conformance.

#### 2.2.1 PK storage and representation

**Critical — PK storage vs representation:**

| Layer | Type | Example |
|-------|------|---------|
| **Database column** | **`BIGINT NOT NULL`** (canonical install SQL) | `102604081200001234` |
| **PHP (retrieved)** | **`string`** when PDO stringifies large integers (see **§4**) | `"102604081200001234"` |
| **PHP (generation)** | **`string`** (concatenation, not integer math) | `"102604081200001234"` |

**DDL note:** Canonical **`install_new_lupopedia.sql`** uses signed **`bigint`** columns (project schema doctrine does **not** use **`UNSIGNED`**). Ladder PKs in product use are **non-negative** and fit the **positive** signed **`BIGINT`** range; the server stores and compares them as 64-bit integers — **no PHP integer is required** for correctness.

**Rules:**

1. PK columns for 18-digit ladder ids **MUST** remain **`BIGINT`** (as in install SQL).
2. **PDO MUST** set **`PDO::ATTR_STRINGIFY_FETCHES => true`** on the connection used for ladder paths so **large integer columns** are returned as **strings**, avoiding lossy conversion to PHP **`float`** on some drivers and avoiding mistaken use as **`int`** on 32-bit runtimes.
3. **`PDO::ATTR_EMULATE_PREPARES`** SHOULD be **`false`** (native prepares) where the driver supports it.
4. Application code **MUST** treat **full** 18-digit ids as **strings** for manipulation (concatenation, **`substr`**, padding).
5. **MUST NOT** cast a **full** 18-digit id to **`(int)`** in PHP on code paths that must support 32-bit PHP (overflow risk) or where precision is ambiguous; only **short** seed ids or **small** slices (e.g. 4-digit year) may be cast to **`int`** after **`substr`**.
6. Year extraction **MUST** use **`substr((string) $id, 0, 4)`** then cast to **`int`** for band comparison only.

**32-bit PHP safety:** Safe when the full id **never** becomes a single PHP integer — manipulation stays **string-based**; the **database** evaluates **`BIGINT`** in SQL.

**Correct PHP:**

```php
$idStr = (string) $row['memory_node_id'];   // Prefer string from PDO; cast if driver returns int on 64-bit only where safe
$year = (int) substr($idStr, 0, 4);        // Four digits — safe as int
$rest = substr($idStr, 4);                 // Remaining 14 digits as string
$newId = ($year - 1000) . $rest;           // String concatenation after year adjust
```

**Incorrect (32-bit unsafe or fragile):**

```php
$id = (int) $row['memory_node_id'];        // WRONG on 32-bit — overflow; risky if value exceeds PHP_INT_MAX
$newId = $id - 10000000000000000;          // WRONG — integer math on full 18-digit value
```

**Database query (safe):** drivers compare bound parameters to **`BIGINT`** correctly; bind the id as **string** or **integer** per PDO rules — **prepared statements with named parameters** only.

#### 2.2.1a Timestamp convention (normative)

All persisted ladder-adjacent timestamps (`created_ymdhis`, `updated_ymdhis`, `deleted_ymdhis`, `active_until`, etc.) use packed UTC integers in `YYYYMMDDHHIISS` format.

**Canonical implementation:** `timestamp_ymdhis::now()` (see `lupo-includes/classes/TimestampYmdhis.php`).

**Never use for persistence:**
- `UNIX_TIMESTAMP()` in SQL
- `time()` in PHP (except ephemeral runtime calculations not persisted)
- `DateTime::getTimestamp()` for persisted ymdhis fields

**Always use:**

```php
$now = timestamp_ymdhis::now(); // Example: 20260408113851
```

**Why:** packed UTC integers remain human-readable, sort correctly as integers, and align with Lupopedia's doctrine for persisted timestamps.

#### 2.2.2 `IdGenerator::validateTrustLadderPk()` — specification

**PHP binding (normative):**

```php
public static function validateTrustLadderPk($id, $context = null, $throw = false)
```

**Parameters:**

- **`$id`** (`int`|`string`) — The PK value to validate.
- **`$context`** (`string`|`null`) — Optional context for error messages (e.g. `'memory_nodes.memory_node_id'`).
- **`$throw`** (`bool`) — If **true**, throw **`InvalidArgumentException`** on failure; if **false**, return **false**.

**Validation rules (normative):**

1. **Seed-exempt band (0–999,999):**

   Any id where `0 <= (int)$id <= 999999` is the **seed/reserved space**.

   **Validation rules:**
   - Must be explicitly registered in `TRUST_LADDER_REGISTRY.md` (or linked seed registries)
   - If registered → valid seed
   - If NOT registered → invalid for ladder participation
   - Length has no restriction as long as value <= 999,999

   **Allowed examples:** `0`, `42`, `999999`

   **Disallowed:** `1000000` (must be 18-digit canonical or staging format)
2. Else if length **≠ 18** → **invalid**.
3. Else if the full 18 characters are not all digits → **invalid**.
4. Else let **`$year = (int) substr($id, 0, 4)`**. If **`$year < 1000`** or **`$year > 9999`** → **invalid**.
5. Else if the remaining **14** characters (positions 4–17) are not all numeric → **invalid**.
6. Else → **valid** (covers embedded years **1000–9999**, including staging **2000–2099** and living canonical **1000–1999**).

**Implementation note:** Callers **SHOULD** pass **table/column context** into validation (or a dedicated seed-registry lookup) so rule **1** can be enforced; a bare **`$id`** without context cannot prove registry authorization.

**Reference implementation for expanded reserved-space validation:**

```php
/**
 * Check if an ID is in the seed/reserved space
 */
public static function isReservedSpace($id)
{
    $idInt = (int) $id;
    return $idInt >= 0 && $idInt <= 999999;
}

/**
 * Expanded validation with range checking
 */
public static function validateTrustLadderPk($id, $context = null, $throw = false)
{
    $idStr = (string) $id;
    $idInt = (int) $idStr;  // Will be accurate for values < PHP_INT_MAX

    // RULE 1: Seed/reserved space (0–999,999)
    if ($idInt >= 0 && $idInt <= 999999) {
        // Must be registered as seed OR explicitly allowed
        if (self::isRegisteredSeed($idStr, $context)) {
            return true;  // Valid seed
        }

        // Not registered - invalid for ladder participation
        if ($throw) {
            throw new InvalidArgumentException(
                "ID {$idStr} in seed range (0–999,999) but not registered in TRUST_LADDER_REGISTRY.md"
            );
        }
        return false;
    }

    // RULE 2: 18-digit format required for canonical/staging
    if (strlen($idStr) !== 18) {
        if ($throw) {
            throw new InvalidArgumentException("ID must be 18 digits for ladder tiers");
        }
        return false;
    }

    // RULE 3: All digits
    if (!ctype_digit($idStr)) {
        if ($throw) {
            throw new InvalidArgumentException("ID must contain only digits");
        }
        return false;
    }

    // RULE 4: Year band validation
    $year = (int) substr($idStr, 0, 4);
    if ($year < 1000 || $year > 9999) {
        if ($throw) {
            throw new InvalidArgumentException("Invalid year band");
        }
        return false;
    }

    // RULE 5: Remaining 14 digits numeric
    $rest = substr($idStr, 4);
    if (!ctype_digit($rest) || strlen($rest) !== 14) {
        if ($throw) {
            throw new InvalidArgumentException("Invalid suffix format");
        }
        return false;
    }

    return true;  // Valid canonical or staging ID
}
```

**MUST** be invoked before INSERT/UPDATE of ladder PKs on tables listed as participating in **TRUST_LADDER_REGISTRY.md**.

#### 2.2.3 `IdGenerator::validateFormat()` — raw generator output only

**PHP binding:**

```php
public static function validateFormat($id)
```

**Purpose:** Validates that **`$id`** is exactly **18 digits** and that the **14-digit** packed clock prefix **`substr($id,0,14)`** lies in the **20000101000000–20991231235959** range (staging clock band for **`generate()`**).

**MUST** be used when asserting an id **is** a fresh **`IdGenerator::generate()`** result. **MUST NOT** be treated as sufficient for full ladder semantics (canonical **1000–1999** ids **fail** this check by design).

### 2.3 Promoting staging to canonical

Use **`IdGenerator::toCanonicalId()`** only as the mathematical transform. **INSERT** of a new canonical row **MUST** use **`IdGenerator::toCanonicalIdSafe()`** per **§2.3.1** so collisions cannot silently reuse an occupied PK.

```php
$canonicalId = IdGenerator::toCanonicalIdSafe($stagingId, 'memory_nodes', 'memory_node_id');
```

#### 2.3.1 `IdGenerator::toCanonicalIdSafe()` — specification

**PHP binding (normative — string-safe):**

```php
public static function toCanonicalIdSafe($stagingId, string $table, string $pkColumn = 'memory_node_id', int $maxRetries = 10): string
```

**Parameters:**

- **`$stagingId`** — Staging-shaped id (typically embedded year **2000–2099**); **MUST** be normalized with **`(string)`** before logic.
- **`$table`** — Short table name **without** prefix (e.g. **`memory_nodes`**).
- **`$pkColumn`** — Primary key column name.
- **`$maxRetries`** — Maximum suffix-bump attempts (default **10**).

**Note:** **`$table`** selects which physical table is queried for collisions (short name **without** prefix, e.g. **`memory_nodes`** → **`lupo_memory_nodes`**). The default **`$pkColumn`** is **`memory_node_id`** (for **`lupo_memory_nodes`**); other tables **MUST** pass their PK column name explicitly.

**Output:** A collision-free canonical id **as a string** of **18 digits**, safe to bind for INSERT (embedded year **1000–1999** after transform, unless suffix bump changes only the last four digits).

**Algorithm (normative — string operations throughout):**

1. **`$idStr = (string) $stagingId`**. If length **≠ 18** (and not a documented short-seed exception path), fail validation per **`validateTrustLadderPk`** policy.
2. **`$year = (int) substr($idStr, 0, 4)`**, **`$rest = substr($idStr, 4)`** (14 digits).
3. If **`$year >= 2000`**: **`$newYear = $year - 1000`**, **`$baseIdStr = $newYear . $rest`** (string concatenation — **not** integer math on the full id).
4. Else: **`$baseIdStr = $idStr`**.
5. **`$currentIdStr = $baseIdStr`**. For **`$attempt`** from **0** to **`$maxRetries - 1`**:
   - Query for **any** row in **`lupo_{$table}`** with **`$pkColumn = :id`** bound to **`$currentIdStr`** (PDO maps string → **`BIGINT`** safely).
   - If **no** row → **`validateTrustLadderPk($currentIdStr, …, true)`** then **return** **`$currentIdStr`**.
   - If **row exists** → collision: **`$suffix = (int) substr($currentIdStr, 14, 4)`**, **`$prefix14 = substr($currentIdStr, 0, 14)`**, **`$suffix = ($suffix + 1) % 10000`**, **`$currentIdStr = $prefix14 . str_pad((string) $suffix, 4, '0', STR_PAD_LEFT)`**; continue.
6. If the loop exhausts → **`RuntimeException`** (message below).

**Implementation note:** Existing **`IdGenerator::toCanonicalIdSafe()`** may still use **`int`** internally on **64-bit** builds; **normative target** for shared-core compatibility is **string** return and **string** collision loop as above.

**Suffix semantics:** The last **four** digits are **non-semantic** collision salt. **MUST NOT** be interpreted as business meaning; export paths, logs, and doctrine **MUST NOT** depend on suffix value for meaning.

**Idempotency (SHOULD):** A second call with the same **`$stagingId`** after a successful INSERT **SHOULD** detect the existing canonical row (e.g. via **`promoted_to` / `consolidated_into`** edges or application-level cache).

**Failure mode (normative):**

```text
Unable to generate unique canonical ID after {maxRetries} attempts for staging ID {stagingId} in table lupo_{table}
```

---

## 3. Consolidation Flow

When multiple staging records exist for the same logical entity:

```text
Staging A (embedded year 2000–2099) ──┐
Staging B (embedded year 2000–2099) ──┼──► Living Canonical (1000–1999)
Staging C (embedded year 2000–2099) ──┘         │
                                  ▼
                            UPDATE with merged non-null fields
                            updated_ymdhis = now
                            is_deleted = 0
```

**Steps:**

1. If no living canonical exists → **promote** ( **`toCanonicalIdSafe()`** then INSERT ).
2. If living canonical exists → **UPDATE** canonical with merged non-null fields from staging.
3. Add edge: staging → canonical (`consolidated_into`, `merged_into`, or `promoted_to`).
4. Soft-delete staging rows (`is_deleted = 1`, `deleted_ymdhis = now`).

---

## 4. Query Priority (Trust Order)

1. **Living canonical** (embedded year **1000–1999**) — preferred.
2. **Staging** (embedded year **2000–2099**) — if no canonical.
3. **Seed/install** (low reserved ids) — lowest priority for “best living answer” queries.

**Preferred implementation:** extract embedded year in PHP **from a string** (see **§2.2.1**); avoid raw **`BETWEEN`** on full **`BIGINT`** PKs in production SQL without per-table review.

**Database `BIGINT` handling:** MySQL and PostgreSQL evaluate **`BIGINT`** comparisons and **`BETWEEN`** on numeric literals and bound parameters in SQL **on the server**. **Illustrative** queries using large numeric literals are **valid** for the engine; **application code** still **MUST NOT** rely on casting those values to PHP **`int`** when 32-bit safety is required (use **string** in PHP, **`BIGINT`** in SQL).

**PDO configuration (normative for ladder-safe fetches):**

```php
$pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
```

**Binding:** **`PDO_DB`** and bootstrap **SHOULD** align with this so **`memory_node_id`**, **`memory_edge_id`**, **`dialog_message_id`**, **`edge_id`**, etc. are returned as **strings** where the value would otherwise exceed **`PHP_INT_MAX`** on 32-bit PHP or lose precision. Until **`ATTR_STRINGIFY_FETCHES`** is **true** in the shared connection, callers **MUST** defensively **`(string)`** cast known **`BIGINT`** PK columns when passing them into string-only helpers.

**Illustrative SQL only** (not normative):

```sql
-- UPDATED QUERY PRIORITY (seed band 0-999,999)
SELECT * FROM lupo_memory_nodes
WHERE owner_actor_id = ? AND memory_key = ?
ORDER BY
    CASE
        -- Seed tier: 0 to 999,999 (lowest priority)
        WHEN memory_node_id BETWEEN 0 AND 999999 THEN 3

        -- Living canonical: 1,000,000,000,000,000,000 to 1,999,999,999,999,999,999
        WHEN memory_node_id >= 1000000000000000000
             AND memory_node_id < 2000000000000000000 THEN 1

        -- Staging: 2,000,000,000,000,000,000 and above
        ELSE 2
    END,
    created_ymdhis DESC
LIMIT 1;
```

**Important:** The SQL `CASE` treats IDs in `0-999,999` as lowest-priority seed candidates.
Application code MUST validate via `validateTrustLadderPk()` that such IDs are actually
registered seeds; non-registered IDs in this range should not participate in ladder queries.

**Query optimization recommendation:**

For very large tables, **prefer application-layer** extraction of the four-digit embedded year (or trust band) for filters and sorts — already aligned with **§2.2.1**.

**Optional:** add a **plain, application-maintained** integer column (e.g. **`trust_ladder_year_band`**) updated on INSERT/UPDATE in PHP, with an index for **`WHERE year_band BETWEEN 1000 AND 1999`**. **Do not** use **database-generated / computed / virtual columns** in canonical Lupopedia schema — forbidden by **Database Logic Prohibition Doctrine** (logic stays in application code).

**Not normative:** vendor-specific **`GENERATED ALWAYS AS`** columns are **out of tree** for canonical installs; forks that experiment with them **MUST NOT** be described as default Lupopedia behavior.

---

## 5. Archiving (Option B)

1. Soft-delete original.
2. Insert new row with id from **`toCanonicalIdSafe()`** (or policy-equivalent collision-safe canonical allocation).
3. Add edge **`archived_to`** (or documented equivalent).
4. Export mirror follows **`created_ymdhis`** per **§6**.

---

## 6. Filesystem Export

The export mirror uses **`created_ymdhis`** (not the PK’s embedded year alone). **`MemoryExportService`** maps **`created_ymdhis = 0`** (or too short for **`YYYYMM`**) to **`lupo-memory/1970/01/`**. See **PRD 38**.

| `created_ymdhis` | Meaning | Export path |
|------------------|---------|-------------|
| **0** | Pre-history / immaterial | **`lupo-memory/1970/01/`** |
| Normal packed UTC | Clock-based | `lupo-memory/{YYYY}/{MM}/` |

---

## 7. Why This Pattern Matters

| Problem | Without Trust Ladder | With Trust Ladder |
|---------|---------------------|-------------------|
| Identifying seed/install | Status column | Low band + policy |
| Identifying canonical truth | `is_canonical` flag | Embedded year **1000–1999** |
| Identifying staging | `is_staging` flag | Embedded year **2000–2099** from **`IdGenerator`** |
| Merging | Ad hoc | Staging → canonical |
| Lineage | Edges only | PK band + edges |

---

## 8. Related Doctrine

- **`lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md`** — table participation
- **`lupo-docs/doctrine/RETENTION_POLICY.md`** — staging retention
- **PRD 00 §3.2.1** — PK generation rules
- **PRD 38** — memory unification
- **PRD 41** — install seed tier

---

## 9. Mandatory Guardrails (Normative)

The ladder **MUST** be enforced with the following. **Documentation alone is not enforcement.**

### 9.1 Registry runtime enforcement

**Rule:** Application code that performs a **trust-ladder-affecting** operation (INSERT/UPDATE canonical promotion, tier classification, or edge that implies ladder semantics) **MUST** verify the table is listed in **`TRUST_LADDER_REGISTRY.md`** for the intended participation class.

**Normative check (pseudo-code):**

```php
function assertTableInTrustLadderRegistry($shortTableName, $participation) {
    // $shortTableName e.g. 'memory_nodes' (no prefix)
    // $participation: 'full_ladder' | 'generator_only' | 'seed_only' | 'excluded'
    $allowed = /* parse TRUST_LADDER_REGISTRY.md or a generated PHP/JSON mirror */;
    if (!isset($allowed[$shortTableName]) || $allowed[$shortTableName] !== $participation) {
        throw new RuntimeException(
            "Table '" . $shortTableName . "' is not registered for participation '" . $participation . "'. See TRUST_LADDER_REGISTRY.md."
        );
    }
}
```

**Binding:** Until a **`RegistryService`** exists, teams **MUST** keep the markdown registry accurate and run **`python lupo-scripts/validate_trust_ladder_registry.py`** in CI; code reviews **MUST** block new ladder paths for unlisted tables.

**Archetype invariant:** Every ladder-participating table must have exactly one declared archetype (`parent`, `child`, `system`) in both doctrine registry and runtime registry cache. Do not infer archetype from id shape or table naming.

### 9.2 Edge integrity rules

An edge is **invalid** if:

1. **Dangling reference:** left or right endpoint id does not exist for the declared object type / table (per application resolution rules).
2. **Forbidden tier direction** for the edge’s declared semantics:
   - **Living canonical → staging** as a “truth” edge: **FORBIDDEN** (canonical must not depend on lower-trust staging as authority).
   - **Staging → living canonical** for consolidation: **ALLOWED** (`consolidated_into`, `merged_into`, `promoted_to`, etc.).
   - **Seed → canonical** for lineage / template: **ALLOWED** (e.g. `canonical_instance_of`).
   - **Canonical → seed** for audit / reversion: **ALLOWED** only where doctrine allows (`reverted_to`).
3. **Ladder semantics on a table** not registered for ladder participation when the operation assumes tier logic.
4. **Archetype mismatch:**
   - `parent` tables must have exactly one active `canonical_instance_of` edge for each required seed anchor.
   - `child` tables must have zero `canonical_instance_of` edges.

**Auditor:** **`lupo-scripts/audit_edge_integrity.py`** **SHOULD** exist; when present it **MUST** list invalid edges and exit non-zero if any are found. **MUST NOT** auto-repair rows (log / report only).

### 9.3 Retention policy

**Normative:** Soft-deleted **staging-tier** rows **MUST** be purged per **`lupo-docs/doctrine/RETENTION_POLICY.md`**. **Garbage collection** (**PRD 19**) **MUST** implement that policy.

**Forbidden:** Per-agent or per-request TTL overrides that contradict the retention doctrine without constitutional amendment.

### 9.4 Observability (trust ladder incidents)

**MUST** log ladder violations to **`lupo_unified_log`** when that table exists in the install (columns include **`log_type`**, **`log_level`**, **`log_message`**, **`log_context`**, **`actor_id`**, **`created_ymdhis`**).

| Event | `log_level` | `log_type` (normative tag) |
|-------|-------------|----------------------------|
| Invalid PK shape after validation path | `error` | `trust_ladder` |
| **`toCanonicalIdSafe`** exhaustion | `critical` | `trust_ladder` |
| Registry / participation mismatch | `error` | `trust_ladder` |
| Edge integrity failure | `warning` | `trust_ladder` |

**`log_context` (JSON) SHOULD include:** `violation_type`, `table`, `pk`, `details`, `actor_id` (if known).

### 9.5 Migration requirements

Any migration touching ladder PKs **MUST**:

1. Update **`TRUST_LADDER_REGISTRY.md`** if participation changes.
2. Re-run **`python lupo-scripts/validate_trust_ladder_registry.py`**.
3. Include in migration metadata: **`trust_ladder_impacting: true`** when the change affects PK bands or ladder tables.
4. Be reviewed under constitutional enforcement (WOLFIE / LILITH) when policy changes.
5. When assigning new seed IDs, keep them in `0-999,999`, update the appropriate seed registry JSON file, and run `python lupo-scripts/validate_seed_registry.py`.
6. For any archetype reclassification, run a migration-impact validator and require two-person sign-off.

---

## 10. Validation Commands

```bash
python lupo-scripts/validate_trust_ladder_registry.py
```

Recommended:

```bash
python lupo-scripts/audit_edge_integrity.py
python lupo-scripts/validate_parent_child_consistency.py
python lupo-scripts/validate_parent_child_reclassification.py --table=<table> --new-archetype=<parent|child|system>
```

---

## 11. Migration and backfill strategy

**Scope:** Applies when a **table or dataset** is **adopted into** the trust ladder (e.g. post-**4.1.0** migration work, imports, or greenfield modules). **4.0.x** norm remains **fresh install from Crafty → Lupopedia** — not Lupopedia→Lupopedia upgrade — see **Single Install** / release doctrine; this section is forward-looking and **process** guidance.

1. **Inventory existing ids:** Classify **seed** (short / low numeric & authorized), **18-digit well-formed**, and **malformed**.
2. **Registry update:** Add or adjust the table in **`TRUST_LADDER_REGISTRY.md`** with the correct **Participates** / seed policy.
3. **Validation:** Run **`python lupo-scripts/validate_trust_ladder_registry.py`** against **`install_new_lupopedia.sql`**.
4. **Backfill canonical ids (if needed):** For staging rows that must become canonical, allocate with **`toCanonicalIdSafe()`**, re-point edges, soft-delete or archive originals per consolidation rules (**§3**).
5. **Test:** Run **`audit_edge_integrity.py`** when it exists — before and after.
6. **Migration metadata:** Set **`trust_ladder_impacting: true`** when the change affects ladder PKs or participation.

**Never:** Assign new canonical ids without collision checking — **`toCanonicalIdSafe()`** (or equivalent explicit collision probe) **is required**.

---

## 12. Performance considerations

- **Index size:** **`BIGINT`** PKs use **8 bytes** per value; btree indexes on **`BIGINT`** are standard.
- **`toCanonicalIdSafe()` cost:** Collision loop is **O(1)** in practice; **10,000** suffix slots per clock prefix make collisions rare.
- **Validation:** **`validateTrustLadderPk()`** is **O(1)** string work — acceptable on every write path.
- **Heavy read paths:** See **§4** (application-layer band extraction or optional indexed, **application-maintained** band column).

---

## 13. Test suite requirements

Ladder behavior **SHOULD** be covered by automated tests where **`lupo-tests`** or CI exist:

- **Unit:** **`validateTrustLadderPk()`** — valid / invalid shapes; **seed** ids **with** and **without** registry authorization (when table context is simulated).
- **Unit:** **`toCanonicalIdSafe()`** — collision path and **`RuntimeException`** on exhaustion.
- **Integration:** String-only id paths suitable for **32-bit** PHP (no full-id **`int`** cast).
- **Integration:** PDO fetch behavior when **`ATTR_STRINGIFY_FETCHES`** is enabled vs disabled (documented expectations).
- **CI:** **`python lupo-scripts/validate_trust_ladder_registry.py`** **MUST** pass — registry / install drift **MUST** fail the pipeline.

---

## Appendix A: Why Not Modern Alternatives

AI assistants and reviewers often propose UUIDs, **`AUTO_INCREMENT`**, Unix epoch ids, or other common patterns. This appendix states why Lupopedia standardizes on **18-digit, timestamp-shaped `BIGINT` PKs** with an application-layer generator instead.

| Alternative | Why rejected |
|-------------|--------------|
| **UUID v4** | Not sortable by creation time; no trust-tier encoding; 128-bit overhead vs 64-bit **`BIGINT`**. |
| **UUID v7** | Time-ordered, but not universally supported everywhere Lupopedia must run; still 128-bit; no embedded trust ladder band. |
| **`AUTO_INCREMENT`** | Database-generated (violates application-layer ID control in this architecture); no temporal layout in the id; not portable across Crafty import / deterministic seed rules. |
| **Unix timestamp** | 32-bit overflow in 2038 where epoch is stored as 32-bit; not human-readable without conversion; poor discrimination within the same second without extra fields. |
| **Snowflake-style ids** | Typically requires coordination or machine ids; not chosen for portability and simplicity across MySQL and PostgreSQL without extra infrastructure. |
| **ULID** | String-based (26 characters); good sortability but not a native **`BIGINT`**; different indexing and binding story than numeric PKs here. |
| **KSUID** | String-based; same class of tradeoff as ULID. |
| **Sequential `INT`** | No temporal or trust-band information; does not support the chronological ladder model. |
| **Year 3000+ embedded year / band collision** | Not a practical concern for a system designed in **2026**; epoch and transform limits are documented in **§2.0.1**. |

**Lupopedia's choice:** **`BIGINT`** primary key columns with application-generated values in the form **`YYYYMMDDHHIISS` + 4-digit suffix** (see **`IdGenerator`**). Canonical install DDL uses signed **`bigint NOT NULL`** (no **`UNSIGNED`** in schema doctrine — see **§2.2.1**); ladder values are non-negative and fit the positive **`BIGINT`** range.

**Why this works:**

- **64-bit in the database**, **string-safe in PHP** when **§2.2.1** / **§4** are followed (no full-id **`int`** on 32-bit).
- **Sortable by creation time** for the clock prefix (PK order ≈ chronological order for the embedded timestamp).
- **Human-readable** for debugging (digits are the packed UTC clock + suffix).
- **Embedded trust tier** via the four-digit year band (e.g. **1000–1999** canonical, **2000–2099** staging) as defined in this doctrine.
- **Application-controlled allocation** — no **`AUTO_INCREMENT`**; tier rules and **`IdGenerator`** are explicit; suffix breaks same-second ambiguity.
- **Portable** across MySQL 8.0+ and PostgreSQL (numeric **`BIGINT`** comparisons).

---

## Appendix B: Lessons from Honolulu (2000-2005)

### B.1 Parent vs Child operational distinction

Production consolidation behavior separated entities into two practical archetypes:

- **Parent entities**: long-lived records that need stable lineage anchors and seed-to-canonical continuity.
- **Child entities**: high-volume, short-lived rows where staging-to-canonical flow is sufficient.

This doctrine aligns with that pattern through:
- seed band rules (`0-999,999`) for immutable anchors,
- canonical/staging year-band rules for 18-digit ladder IDs,
- explicit edge semantics (`canonical_instance_of`, `consolidated_into`, `promoted_to`).

### B.2 Batch import guardrails

Large single-transaction writes on ladder tables showed contention in historical operations. The practical mitigation that proved stable was:

- **chunk size**: 5000 rows or fewer per insert/delete cycle,
- **random backoff**: jittered sleep between chunks (for example 0.0-2.0 seconds),
- **avoid fixed delay**: fixed sleeps synchronize concurrent workers and increase repeated collisions.

### B.3 GC and import application

Any trust-ladder batch path (imports, consolidations, or large GC loops) should use bounded chunking plus random backoff to reduce lock amplification and synchronized retry storms.

Cross-reference: **PRD 19 §7** for implementation pattern and operational defaults.

---

**Status:** ACTIVE

**Constitutional Adherence:** FULL
