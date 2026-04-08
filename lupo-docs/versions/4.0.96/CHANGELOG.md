---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: changelog
  when_updated: "20260408161022"
  file_path_from_root: "lupo-docs/versions/4.0.96/CHANGELOG.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.96/CHANGELOG.md"
  last_modified_utc: "20260408161022"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.96-changelog"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "changelog"
  artifact_kind: "version"
  purpose: "Changelog for Lupopedia 4.0.96 — 4D edge model, doctrine expansion, memory.json deprecation, file-backed content"
  tags: ["changelog", "version", "4.0.96", "cursor"]
lupopedia.footer:
  last_verified: "20260408161022"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.96/CHANGELOG.md — delegation: cursor:root

# Changelog - Lupopedia 4.0.96

## [2026-04-08 16:10 UTC] — Session identity hardening, privacy fingerprints, probabilistic `lupo_sessions` GC, `SessionManager` slim-down

**WHO:** **Cursor IDE Agent** (actor_id **102**), orchestrator-directed implementation session. Prior research and recommendations in `lupo-docs/versions/4.0.96/status/SESSIONS_RESEARCH.md` are attributed to **Claude Code** (actor_id **116**); this entry documents **code and schema changes applied in-repo** by Cursor in this session.

### WHAT

- **`App\Auth\Session` (Model A)** — Session fingerprinting and identity signals aligned with privacy + doctrine:
  - **`LUPO_SESSION_SALT`** — per-install salt for hashed columns; **`generate_session_salt.php`** helper for operators/installers.
  - **`hashFingerprint()`** — salted SHA-256 over **network prefix** (IPv4 Class C / IPv6 first 64 bits) + salted UA hash (not raw full IP).
  - **`session_identity_hash`** — composite hash (prefix + normalized UA + salt); column on `lupo_sessions` + index **`sessions_idx_identity_hash`**.
  - **`normalizeUserAgent()`** — truncate to 200 chars, collapse whitespace (reduces DoS/churn from raw `HTTP_USER_AGENT`).
  - **`resolvedClientIp()`** — proxy/CDN-aware resolution ( **`LUPO_CLIENT_IP`** first, then ordered headers); no dependency on legacy `get_ipaddress()` (reference-only under `craftysyntax-reference/`).
  - **`ensureTimestampClass()`** — single load path for **`timestamp_ymdhis`**.
  - **D-003 / persistence clocks** — `createEmbedSession`, `createOrUpdateForUnified`, `touch`, `mergeSessionMetadata`, etc. use **`timestamp_ymdhis::now()`** via **`nowYmdhis()`** where applicable.
  - **`LUPO_SESSION_VALIDATE_UA`** — optional UA hash check on **`validateSession()`** load path.
  - **`maxIdleSecondsForIsNamed()`**, **`isExpired()`**, **`validateSession()`** — idle expiry by **`is_named`** band (anonymous vs named visitor UX) before touch; expired rows **`destroyInternal()`**.
- **Probabilistic GC** — **`Session::maybeProbabilisticGarbageCollect()`**: low probability per web request, **lock file** under **`sys_get_temp_dir()`** (fallback `lupo-tmp`), **`DELETE`** by `is_named` + **`last_activity_ymdhis`** cutoffs using **`timestamp_ymdhis::subtractSeconds()`** (not invalid packed-integer subtraction).
- **`SessionManager`** — **`tick()`** now **only** invokes **`maybeProbabilisticGarbageCollect($db)`**; idle/expiry duplicated logic removed ( **`validateSession()`** is authoritative).
- **`bootstrap.php`** — single GC path: **`SessionManager::tick()`** before **`validateSession()`**; removed duplicate end-of-block GC call.
- **Install SQL** — **`session_identity_hash`**, indexes **`sessions_idx_identity_hash`**, **`sessions_idx_gc_named_activity`** `(is_named, last_activity_ymdhis)`.
- **Config** — **`lupopedia-config.php`**: **`LUPO_SESSION_SALT`**, **`LUPO_SESSION_VALIDATE_UA`**, **`LUPO_SESSION_*_IDLE_MINUTES`**, **`LUPO_SESSION_GC_*`**.

### WHERE (files touched)

| Area | Path |
|------|------|
| Session runtime | `app/auth/Session.php` |
| GC hook | `lupo-includes/classes/SessionManager.php` |
| Request bootstrap | `lupo-includes/bootstrap.php` |
| Local install config | `lupopedia-config.php` |
| Canonical schema | `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` |
| Salt helper (new) | `lupo-scripts/generate_session_salt.php` |
| Version docs | `lupo-docs/versions/4.0.96/CHANGELOG.md`, `PLAN.md`, `TODO.md` |
| Status handoff (new) | `lupo-docs/versions/4.0.96/status/STATUS_SESSION_IDENTITY_AND_GC_20260408161022.md` |

### WHEN

**`20260408161022` UTC** — **2026-04-08, 16:10 UTC** (canonical **`python lupo-bin/tick.py`** anchor for this documentation batch).

### WHY

1. **`SESSIONS_RESEARCH.md`** documented gaps: unsalted/full-IP hashes, **`REMOTE_ADDR`** vs Cloudflare, **D-003** (`gmdate` in persistence paths), and optional composite **`session_identity_hash`**.
2. **Privacy** — full IPv4 in a hash is enumerable; Class C + salt reduces reversibility while keeping coarse network correlation for audit.
3. **Shared hosting** — no cron: probabilistic GC + lock avoids thundering herd; separate TTL bands for anonymous vs named sessions improve UX.
4. **Doctrine** — packed UTC must use **`timestamp_ymdhis`** for “N minutes ago” cutoffs, not arithmetic on **`YmdHis`** integers.
5. **Architecture** — one place for idle validation (**`validateSession()`**), one hook for sweep probability (**`SessionManager::tick()`**), no redundant **`loadById`** in **`SessionManager`**.

### HOW (implementation summary)

- **Cutoffs:** `$cutoff = timestamp_ymdhis::subtractSeconds(timestamp_ymdhis::now(), $seconds)`; compare **`last_activity_ymdhis`** (and **`isExpired()`** uses max of **`last_seen`** / **`last_activity`** for reference time).
- **GC probability:** `roll` in `1..LUPO_SESSION_GC_DIVISOR`; run if `roll <= LUPO_SESSION_GC_PROBABILITY` (default 3/100); **`random_int`** with **`mt_rand`** fallback.
- **Lock:** `rtrim(sys_get_temp_dir(), '/\\') . '/lupopedia_session_gc.lock'`; skip if lock younger than **`LUPO_SESSION_GC_LOCK_SECONDS`**.
- **Backward compatibility:** **`session_id`** token unchanged; existing rows keep old hash values; new rows get new formulas; optional **`LUPO_SESSION_VALIDATE_UA`** defaults false.

### Verification (spot checks performed during implementation)

- `php -l app/auth/Session.php`
- `php -l lupo-includes/classes/SessionManager.php`
- `php -l lupo-includes/bootstrap.php`

---

## [2026-04-08 13:32 UTC] — TrustLadderRegistry: PHP class, sync script, SQL DDL, unit tests, IdGenerator integration

**WHO:** Claude Code (actor_id 116)

### WHAT

Implemented the `TrustLadderRegistry` system end-to-end across all five phases
specified in `note_on_seed_range.md` and the mission brief:

**Phase 1 — Database**
- Added `lupo_trust_ladder_registry` table DDL to `install_new_lupopedia.sql`
- 13 bootstrap seed rows covering all known ladder-participating tables
- Self-referential seed row (registry_id=1) for bootstrap safety

**Phase 2 — PHP Registry Class**
- Created `TrustLadderRegistry.php` — final class with static cache
- `getTableMeta()`, `isParent()`, `isChild()`, `requiresSeed()`, `getReferenceTarget()`,
  `getParticipates()`, `getCanonicalLineageEdge()`, `getPromotionTarget()`
- `validatePkForTable()` with per-participation-mode PK validation
- `assertInvariants()` for archetype-consistency enforcement
- `getSelfMetadata()` bootstrap-safe hard-coded fallback
- `injectTestCache()` for unit-test isolation (no DB required)
- `clearCache()` for post-sync invalidation
- `TrustLadderException` class (extends RuntimeException)
- Dev mode (LUPOPEDIA_ENV=development): E_USER_WARNING + permissive fallback
- Prod mode (default): TrustLadderException on unregistered/deactivated table

**Phase 3 — Sync Script**
- Created `sync_trust_ladder_registry_to_db.py`
- Parses `TRUST_LADDER_REGISTRY.md` (backtick table names)
- Validates archetype invariants before any DB write (exit code 3 on failure)
- Detects doctrine-vs-DB drift field by field
- `--dry-run`, `--force`, `--strict` (CI gate), `--verbose` options
- Exit 0 = no drift; 1 = drift; 2 = config/connect error; 3 = invariant failure
- Portable: pymysql (MySQL/MariaDB), psycopg2 (PostgreSQL), sqlite3 (SQLite)
- `--help` parses cleanly; `py_compile` passes

**Phase 4 — Runtime Integration**
- Added registry archetype check to `IdGenerator::toCanonicalIdSafe()`:
  queries `TrustLadderRegistry::getParticipates()` before promotion
  and throws `RuntimeException` if the table is not `full` participation
  (guard is opt-in: skipped when TrustLadderRegistry class is not loaded)

**Phase 5 — Testing**
- Created `trust_ladder_registry_test.php` — 47 assertions in 7 sections:
  - A: `getSelfMetadata()` bootstrap fallback (7 assertions)
  - B: `injectTestCache()` + `getTableMeta()` (9 assertions)
  - C: Type helpers — isParent, isChild, requiresSeed, etc. (12 assertions)
  - D: `validatePkForTable()` for seed_only / full / generator_staging (9 assertions)
  - E: `assertInvariants()` pass and fail cases (3 assertions)
  - F: Dev mode vs Prod mode behavior (4 assertions)
  - G: `clearCache()` + `injectTestCache()` idempotency (3 assertions)
- **All 47/47 tests pass**

**Legacy Gap Documentation**
- Created `lupo-docs/versions/4.0.96/LEGACY_GAP_ANSWERS.md` — all 11 legacy-gap
  questions answered with implementation decisions and follow-up issue references

### WHERE (files changed)

- `lupo-includes/classes/TrustLadderRegistry.php` (**new**)
- `lupo-scripts/sync_trust_ladder_registry_to_db.py` (**new**)
- `lupo-tests/unit/trust_ladder_registry_test.php` (**new**)
- `lupo-docs/versions/4.0.96/LEGACY_GAP_ANSWERS.md` (**new**)
- `lupo-docs/versions/4.0.96/status/STATUS_TRUST_LADDER_REGISTRY_20260408.md` (**new**)
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (appended: table DDL + 13 seed rows)
- `lupo-includes/classes/IdGenerator.php` (modified: registry check in `toCanonicalIdSafe()`)
- `lupo-docs/versions/4.0.96/CHANGELOG.md` (this entry)
- `lupo-docs/versions/4.0.96/PLAN.md` (updated: TrustLadderRegistry phase marked done)
- `lupo-docs/versions/4.0.96/TODO.md` (updated: trust ladder registry items checked off)

### WHEN

`20260408133230` UTC — 2026-04-08, **13:32** UTC

### WHY

1. `note_on_seed_range.md` (Cursor handoff, `20260408114106`) explicitly assigned
   Claude Code the task of implementing the `TrustLadderRegistry` system.
2. Without a runtime registry, all archetype decisions were either hard-coded or
   derived from table-name patterns — both patterns violate the Archetype Enforcement
   Principle ("Never derive archetype from table name, ID shape, or developer intuition").
3. `AdminTrustLadderHandler` (Panel A) already did a PHP-port of the Python validator to
   check table participation; this duplicated logic that should live in a single class.
4. The `toCanonicalIdSafe()` promotion path had no archetype check — any table could
   be promoted regardless of its ladder participation mode.
5. `TRUST_LADDER_REGISTRY.md` was doctrine-only; no runtime enforcement existed.

### HOW (implementation details)

**Bootstrap sequence** (three-phase as specified in `note_on_seed_range.md`):
- Phase 1: DDL creates the table (no registry lookup needed)
- Phase 2: Seed row `registry_id=1` for the registry table itself
- Phase 3: Seed rows `registry_id=2–13` for all known ladder tables
- PHP `getSelfMetadata()` provides a hard-coded fallback so Phase 1→2 bootstrap
  never needs to query a table that doesn't yet exist

**Fail-closed design:**
- `TrustLadderException` is thrown in production on any unregistered or deactivated table
- Dev mode emits `E_USER_WARNING` with a permissive `not_ladder` fallback
- `validatePkForTable()` dispatches on `participates` value (seed_only → `isReservedSpace()`,
  full/generator_staging → `validateTrustLadderPk()`)

**Sync script invariants validated before any write:**
```python
parent  → seed_required=1, reference_target='seed'
child   → seed_required=0, reference_target='canonical'
system  → seed_required=1
```
Exit code 3 on invariant failure (distinct from drift=1 and connect error=2).

**IdGenerator integration:** optional — guard with `class_exists('TrustLadderRegistry', false)`
so existing tests that don't load the registry class are unaffected.

**TODO(LEGACY-GAP) density:** 14 explicit `TODO(LEGACY-GAP)` markers across all new files,
covering cache invalidation for long-running workers, table-prefix portability, lineage
edge GC exclusion, `SELECT ... FOR UPDATE` locking, and Markdown parsing fragility.

### Verification

```
php -l lupo-includes/classes/TrustLadderRegistry.php   → No syntax errors
php -l lupo-tests/unit/trust_ladder_registry_test.php  → No syntax errors
php -l lupo-includes/classes/IdGenerator.php           → No syntax errors
python -m py_compile lupo-scripts/sync_trust_ladder_registry_to_db.py → pass
php lupo-tests/unit/trust_ladder_registry_test.php     → 47 passed, 0 failed
python lupo-scripts/sync_trust_ladder_registry_to_db.py --help → parses correctly
```

---

## [2026-04-08 12:06 UTC] — PRD 17 validator implementation + trust-ladder doctrine/session-note hardening

**WHO:** Cursor IDE Agent (actor_id 102)

### WHAT

- Implemented PRD 17 validator coverage across pseudocode headers, thread structure, and Q/A edge-link consistency.
- Added two new validator scripts and upgraded one existing validator to enforce required pseudocode headers.
- Wired validator execution into `lupo-scripts/run_tests.sh` (version-scope run for 4.0.96).
- Updated trust-ladder session documentation and doctrine text to lock timestamp persistence guidance and legacy-gap terminology consistency.

### WHERE (files changed)

- `lupo-scripts/validate_pseudocode_discipline.py`
- `lupo-scripts/validate_thread_structure.py` (new)
- `lupo-scripts/validate_edge_linking.py` (new)
- `lupo-scripts/run_tests.sh`
- `lupo-docs/versions/4.0.96/note_on_seed_range.md`
- `lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md`
- `lupo-docs/versions/4.0.96/CHANGELOG.md` (this entry)

### WHEN

`20260408120642` UTC — 2026-04-08, **12:06** UTC (temporal anchor from `python lupo-bin/tick.py`).

### WHY

- PRD 17 requires enforceable validation for thread artifacts and pseudocode discipline, including required `lupopedia.headers` fields on `*.pseudo.*` files under `decisions/pseudocode/`.
- Q/A relationships should be validated as explicit edges (`has_answer`, `answers`) with real file targets to reduce drift and broken links.
- Session docs needed consistency cleanup and stronger implementation guidance for the trust-ladder registry bootstrap and dev/prod behavior.
- Timestamp persistence doctrine needed explicit reinforcement: packed UTC (`timestamp_ymdhis::now()`), never `UNIX_TIMESTAMP()` in persistence paths.

### HOW (implementation details)

1. **Task 1 — Pseudocode Header Validator**
   - Upgraded `validate_pseudocode_discipline.py` to enforce for `*.pseudo.md`, `*.pseudo.php`, and `*.pseudo.txt` under `decisions/pseudocode/`:
     - `lupopedia.headers` block present
     - `file_path_from_root` present
     - `when_updated` present
     - `last_modified_utc` present
   - Missing required fields now produce clear errors and non-zero exit.
   - Existing Purpose 2 warnings behavior retained (`--strict` escalates warnings).

2. **Task 2 — Thread Structure Validator**
   - Added `validate_thread_structure.py` to validate `decisions/`, `questions/`, `answers/`, `comments/` folders:
     - `THREAD_INDEX.md` required
     - PRD 17 filename timestamp/type/title format
     - `decisions/` requires `_STATUS_` segment
     - `questions/answers/comments` must not include `_STATUS_`

3. **Task 3 — Edge Linking Validator (optional, completed)**
   - Added `validate_edge_linking.py`:
     - `has_answer` edges must resolve to existing files in `answers/`
     - `answers` edges must resolve to existing files in `questions/`

4. **Pipeline wiring / CI path**
   - Added a `PRD 17 validators` section in `run_tests.sh`.
   - Current scope is `lupo-docs/versions/4.0.96` to satisfy 4.0.96 success criteria while avoiding legacy historical folder failures.

5. **Session documentation hardening**
   - Updated `note_on_seed_range.md` with:
     - standardized `LEGACY-GAP` terminology
     - registry bootstrap sequence refinements
     - explicit `loadCacheIfNeeded()` dev/prod behavior guidance
     - bootstrap SQL note: use `timestamp_ymdhis::now()` at generation time
   - Updated `CHRONOLOGICAL_TRUST_LADDER.md` with a normative timestamp convention section:
     - persisted timestamps are packed UTC `YYYYMMDDHHIISS`
     - canonical API is `timestamp_ymdhis::now()`
     - forbidden for persistence: `UNIX_TIMESTAMP()`, `time()`, `DateTime::getTimestamp()`

### Verification outcome (this session)

- `validate_pseudocode_discipline.py` on `lupo-docs/versions/4.0.96`: pass
- `validate_thread_structure.py` on `lupo-docs/versions/4.0.96`: pass
- `validate_edge_linking.py` on `lupo-docs/versions/4.0.96`: pass
- `python -m py_compile` on all three validator scripts: pass
- `ReadLints` on touched scripts: no linter errors

---

## [2026-04-08 09:51 UTC] — CTL enforcement infrastructure: audit script, §13 test suite, staging GC, admin web UI; IdGenerator::isReservedSpace bug fix

**WHO:** Claude Code (actor_id 116)

---

### WHAT — Files Created

#### `lupo-scripts/audit_edge_integrity.py` (new)
Python audit script implementing **CHRONOLOGICAL_TRUST_LADDER.md §9.2** normative edge-integrity auditor. Three checks executed in sequence:
1. **Dangling `memory_node` references in `lupo_edges`**: any non-deleted edge where `left_object_type = 'memory_node'` or `right_object_type = 'memory_node'` must resolve to a live row in `lupo_memory_nodes`.
2. **Dangling endpoint references in `lupo_memory_edges`**: `from_memory_node_id` and `to_memory_node_id` must both exist in `lupo_memory_nodes` (non-deleted).
3. **Forbidden tier direction**: a living-canonical endpoint (year 1000–1999) must NOT appear as the source of a "truth" edge pointing to a staging endpoint (year 2000–2099) unless the edge type is a documented consolidation direction (`consolidated_into`, `merged_into`, `promoted_to`, `kairos_consolidates_from`, `archived_to`, `restored_from`, `canonical_instance_of`, `reverted_to`).

Output format: `INVALID EDGE [table] edge_id=<id> reason=<tag> [field=value ...]`. Exit 0 = clean; 1 = violations found; 2 = config/connect error. Strictly read-only — **MUST NOT** auto-repair rows (doctrine §9.2 mandate). Style follows `validate_trust_ladder_registry.py` exactly (shebang, `PROJECT_ROOT`, `argparse`, `main() → int`, `sys.exit(main())`). DB credentials via `lib.db_connection.get_connection_params()` with `db_config.py` fallback.

---

#### `lupo-tests/unit/trust_ladder_pk_validation_test.php` (new)
Plain-PHP unit test (no PHPUnit) for `IdGenerator::validateTrustLadderPk()` and `IdGenerator::validateFormat()` per **§13**. 25 assertions in 6 sections:
- **A**: Valid 18-digit IDs — canonical year 1026, staging year 2026, boundary years 1000 and 9999, suffix all-zeros and all-nines.
- **B**: Invalid shapes — 17 digits, 19 digits, non-digit characters, embedded year 0999 (below 1000 band), empty string, leading space.
- **C**: Seed / reserved-space IDs — seed `42` rejected for non-actor context; seeds `1` (WOLFIE) and `116` (Claude Code) accepted with `actors.actor_id` context via live `registry.json` lookup (skipped gracefully when file absent); unregistered seed `7` rejected.
- **D**: Exception throwing — `throw=true` on invalid id raises `InvalidArgumentException`; `throw=true` on valid canonical does not throw.
- **E**: `validateFormat()` — fresh `generate()` output passes; canonical id fails (outside 2000–2099 clock band); staging 2026 passes; year 3000 fails.
- **F**: Return type — `validateTrustLadderPk()` returns `bool`; `generate()` returns `string`.

#### `lupo-tests/unit/trust_ladder_canonical_id_test.php` (new)
Plain-PHP unit test for `toCanonicalId()`, `toCanonicalIdSafe()` (fully mocked DB — no live connection), `seedActorToCanonicalId()`, and 32-bit string-safety. 24 assertions in 4 sections:
- **A**: `toCanonicalId()` — year 2026 → 1026; already-canonical unchanged; short seeds (`42`, `116`) returned verbatim; boundary years 2000 → 1000 and 1999 unchanged.
- **B**: String safety — return is `string`; 4-digit year extraction via `substr`/`int` is safe; staging→canonical string-concat demonstration (mirrors §2.2.1 "Correct PHP"); `seedActorToCanonicalId(116)` = `'100000000000000116'` (18 digits); `seedActorToCanonicalId(1)` = `'100000000000000001'`.
- **C**: `toCanonicalIdSafe()` with `MockDb` stub — happy path (no collision); one-collision suffix bump (`1234 → 1235`); two-collision suffix bump; suffix wrap-around (`9999 → 0000`); `maxRetries` exhaustion raises `RuntimeException`.
- **D**: `seedActorToCanonicalId()` edge cases — negative seed throws `InvalidArgumentException`; non-numeric string throws; zero seed maps to base `'100000000000000000'`.

The `MockDb` stub is defined before `require_once IdGenerator.php` so `class_exists('DatabaseFactory', false)` is satisfied and no real DB class is loaded. This makes the file self-contained for CI.

#### `lupo-tests/integration/trust_ladder_pdo_stringify_test.php` (new)
Integration test for PDO `ATTR_STRINGIFY_FETCHES` behavior on `lupo_memory_nodes` per **§13**. Requires a live DB; skips gracefully with `[SKIP]` lines if config or table absent. Inserts a staging-tier test row (via `IdGenerator::generate()`), fetches it with both `ATTR_STRINGIFY_FETCHES = false` and `= true`, asserts:
- Default mode: fetched value is numeric (int or string depending on driver/platform) — documents observed PHP type for operator awareness.
- Stringify mode: fetched value is `string`; equals original PK string; passes `validateTrustLadderPk()` directly without a cast.
- Both modes: `toCanonicalId()` produces identical 18-digit strings regardless of fetch mode.
Cleans up by soft-deleting the test row (`is_deleted = 1`).

#### `app/Services/Kairos/StagingGcService.php` (new)
PHP service implementing **RETENTION_POLICY.md** staging purge for `lupo_memory_nodes` and `lupo_memory_edges`. Public API:
- **`purge(int $retentionDays = 90, int $batchSize = 1000): array`** — selects soft-deleted staging rows where `deleted_ymdhis <= cutoff` (90-day window), validates each PK against `isStagingBand()` (year 2000–2099 check in PHP — no canonical or seed rows are ever touched), physically DELETEs via named PDO params, logs to `lupo_unified_log` (`log_type = 'gc'`, `log_level = 'info'`). Returns `{memory_nodes_purged, memory_edges_purged, errors[]}`.
- **`dryRun(int $retentionDays = 90): array`** — counts eligible rows without deleting. Returns `{memory_nodes_eligible, memory_edges_eligible, cutoff_ymdhis, retention_days}`.

Cutoff computed via `timestamp_ymdhis::subtractSeconds(now, days * 86400)` — no raw date math. Uses `DatabaseFactory::getConnection()` / `LUPO_TABLE_PREFIX` / `quoteIdentifier()` — no triggers, no FKs. Per-row errors are collected and returned rather than aborting the run.

#### `lupo-bin/cli/staging_gc.php` (new)
CLI entry file for the staging-gc command. Accepts `--days=N`, `--batch=N`, `--dry-run`, `--actor=N`. Bootstraps from `lupopedia-config.php`, instantiates `StagingGcService`, runs `purge()` or `dryRun()`, prints structured output to stdout and errors to stderr. Exits 0 on clean run; 1 if `$result['errors']` is non-empty.

#### `lupo-includes/classes/AdminTrustLadderHandler.php` (new)
Static admin section handler for `admin.php?section=trust-ladder`. Four read panels + one write action:
- **Panel A — Registry vs Install SQL validation**: PHP port of `validate_trust_ladder_registry.py` — reads `TRUST_LADDER_REGISTRY.md` (backtick `lupo_*` names) and `install_new_lupopedia.sql` (`CREATE TABLE {{prefix}}` and `CREATE TABLE lupo_` patterns), checks required seed-range doctrine markers, renders pass/fail table per registry entry.
- **Panel B — Live tier counts**: fetches all PKs from `lupo_memory_nodes` and `lupo_memory_edges` (up to 50,000 rows), classifies each in PHP via `pkBand()` (mirrors §2.2.1: `strlen < 18` = seed, year 1000–1999 = canonical, year 2000–2099 = staging), shows counts for live seed / canonical / staging / soft-deleted / GC-eligible (staging soft-deleted ≥ 90 days).
- **Panel C — Recent log entries**: last 25 rows from `lupo_unified_log` where `log_type IN ('trust_ladder', 'gc')`; columns `created_ymdhis`, `log_level`, `log_type`, `log_message`, truncated `log_context` JSON.
- **Panel D — Seed actor registry**: reads `lupo-database/lupopedia/actors/registry.json`, validates each actor's `actor_id` via `IdGenerator::validateTrustLadderPk($id, 'actors.actor_id')`, shows 18-digit canonical form from `seedActorToCanonicalId()`.
- **POST — Run Staging GC**: CSRF-protected form button; invokes `StagingGcService::purge(90, 1000)` inline; logs admin action to `lupo_unified_log` (`log_type = 'trust_ladder'`); flash message with purge counts or error summary.

---

### WHAT — Files Modified

#### `lupo-includes/classes/IdGenerator.php`
**Bug fix — `isReservedSpace()` incorrect boundary.**

Prior implementation: `return self::numericStringLessThan($idStr, '1000000000000000000')` — comparing against a 19-digit boundary (1 quintillion = 10^18). Because ALL 18-digit integers are numerically less than 10^18, this caused every canonical (year 1000–1999) and staging (year 2000–2099) 18-digit ladder ID to be misclassified as "reserved space", then rejected by `validateTrustLadderPk()` unless registered as a seed. In effect the function made the entire trust ladder inoperable for runtime IDs.

Fixed implementation: `return strlen($idStr) < 18` — an 18-digit string is a ladder ID candidate and is never in the reserved/seed space. Strings shorter than 18 digits are seed/reserved. This aligns with the observable intent of the doctrine (seed IDs are short like `1`, `42`, `116`; ladder IDs are exactly 18 digits), resolves all 7 failing unit tests, and does not change the behaviour of short-seed validation which was already correct.

#### `lupo-bin/lupo.php`
Added `case 'staging-gc':` branch to the switch dispatch: requires `lupo-bin/cli/staging_gc.php` if it exists, exits 1 with an error message if not. Added `staging-gc` to the `help` output with options summary.

#### `admin.php`
Three additions:
1. `'Trust Ladder' => 'admin.php?section=trust-ladder'` added to the **Agents & Channels** nav group in `$admin_menu_sections`.
2. `'trust-ladder' => array('Trust Ladder', 'Trust Ladder')` added to `$section_titles`.
3. `elseif ($section === 'trust-ladder' && $db)` dispatch block added immediately before the generic `isset($section_titles[$section])` fallback, loading `AdminTrustLadderHandler` and calling `::render($db, $prefix, $base)`.

---

### WHERE — PRD Applicability

| File changed | PRD(s) | Clause |
|---|---|---|
| `audit_edge_integrity.py` | CHRONOLOGICAL_TRUST_LADDER §9.2 | Normative auditor — "SHOULD exist; when present it MUST list invalid edges and exit non-zero" |
| Unit test files | CHRONOLOGICAL_TRUST_LADDER §13 | Test suite requirements — unit `validateTrustLadderPk`, `toCanonicalIdSafe`, integration PDO/32-bit |
| `StagingGcService.php` | RETENTION_POLICY §Staging-tier rows; PRD 19 (GC) | "Purge soft-deleted staging-tier rows no earlier than 90 days after `deleted_ymdhis`" |
| `staging_gc.php` (CLI) | PRD 24 §3.2.1 (CLI interface); PRD 19 | GC command surface |
| `lupo.php` | PRD 24 CLI routing doctrine | Canonical CLI dispatch pattern |
| `AdminTrustLadderHandler.php` | FOR_CLAUDE_CODE_ON_PK_IDS §Future Work; CHRONOLOGICAL_TRUST_LADDER §9.1 (registry enforcement); TRUST_LADDER_REGISTRY | "Plain PHP admin surface for install/seed record review and updates; no Laravel" |
| `admin.php` | PRD 27 (installer/admin architecture) | Section dispatch and navigation |
| `IdGenerator::isReservedSpace()` fix | CHRONOLOGICAL_TRUST_LADDER §2.2.1, §2.2.2 Rule 1 | Corrects PK boundary classification; unblocks all 18-digit ladder validation paths |

---

### WHEN

`20260408095121` UTC — 2026-04-08, **09:51** UTC.

Temporal anchor updated via `python lupo-bin/tick.py` at session close.

---

### WHY

Four open obligations were identified in the prior Cursor session (`FOR_CLAUDE_CODE_ON_PK_IDS.md`, CHRONOLOGICAL_TRUST_LADDER §9.2, §9.3, §13, FOR_CLAUDE_CODE_ON_PK_IDS §Future Work) and confirmed in the Claude Code status report produced at the start of this session:

1. **`audit_edge_integrity.py`** — §9.2 states "SHOULD exist; when present it MUST list invalid edges and exit non-zero if any are found." Without it, edge integrity guardrails existed only in documentation; no CI-runnable enforcement existed.
2. **§13 test suite** — The doctrine mandated unit and integration tests for `validateTrustLadderPk`, `toCanonicalIdSafe`, 32-bit string paths, and PDO fetch mode. Without tests, the ladder implementation had no automated regression coverage.
3. **Staging GC** — RETENTION_POLICY.md defined a 90-day purge window for soft-deleted staging rows, but PRD 19 GC implementation had no ladder-aware purge path. The existing `GarbageCollector.php` handles visits/sessions/analytics only and uses a different DB API.
4. **Admin web UI** — `FOR_CLAUDE_CODE_ON_PK_IDS.md §Future Work` explicitly assigned Claude Code (actor_id 116) the task of building a "plain PHP admin surface for install/seed record review."

The `isReservedSpace()` bug was discovered while writing and running the §13 test suite — the tests correctly asserted that canonical and staging 18-digit IDs should pass `validateTrustLadderPk()`, revealing that the production implementation had silently been failing all such IDs since the function was authored.

---

### HOW

**`audit_edge_integrity.py`**: Python 3; `pymysql` for DB; `argparse` CLI; pure SQL reads on `lupo_edges` and `lupo_memory_edges`; trust-band classification mirrors PHP §2.2.1 as Python string logic (`str(pk)[0:4]` year extraction). No writes. Follows style of `validate_trust_ladder_registry.py` exactly.

**Test suite**: Plain PHP (no PHPUnit), matching the project's existing test pattern (`$passed`/`$failed` globals, `[PASS]`/`[FAIL]` stdout lines, `exit(1)` on failure). `MockDb` / `DatabaseFactory` stub defined before `require_once IdGenerator.php` to prevent real DB loading. Tests run standalone: `php lupo-tests/unit/<file>.php`.

**`StagingGcService`**: Constructor matches `KairosConsolidationService` pattern (`$db`, `$tablePrefix`, `$actorId`). PHP-layer `isStagingBand()` validates each PK's year band (string-only, no int cast of 18-digit value) before every `DELETE`. Cutoff computed via `timestamp_ymdhis::subtractSeconds()`. All DB calls use named PDO params via `PDO_DB`. Logging via `lupo_unified_log` is best-effort (silently skips if table absent).

**`AdminTrustLadderHandler`**: Static render pattern (`::render($db, $prefix, $base)`) identical to `AdminUsersHandler`, `AdminAgentsHandler`, etc. Panel A ports the Python validator's two `CREATE TABLE` regexes and marker list to PHP `preg_match_all`. Panel B uses PHP-layer classification (avoids `GENERATED ALWAYS AS` columns, forbidden by Database Logic Prohibition Doctrine). Panel D calls `IdGenerator::validateTrustLadderPk()` and `seedActorToCanonicalId()` per-row. Write path is CSRF-protected via `lupo_require_valid_csrf_token()`.

**`IdGenerator::isReservedSpace()` fix**: Single-line change — `numericStringLessThan($idStr, '1000000000000000000')` → `strlen($idStr) < 18`. Accompanied by an explanatory docblock documenting why numeric comparison against 1 quintillion (a 19-digit number) incorrectly classifies all 18-digit ladder IDs as reserved space.

All 49 unit tests (25 + 24) pass. PHP syntax clean on all new files (`php -l`). Python `--help` parses correctly.

---

**Status files produced this session:** None — work is complete and self-contained. Forward action: run `python lupo-scripts/audit_edge_integrity.py` against a seeded DB; run `php lupo-bin/lupo.php staging-gc --dry-run` to confirm GC counts; browse `admin.php?section=trust-ladder`.

---

## [2026-04-08 03:19 UTC] — Trust Ladder normative completion; IdGenerator + KAIROS code; registry validator; Captain's Log; version handoff doc

**WHO:** cursor (actor_id 102)

**WHAT (documentation):**

- **`lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md`** — Normative guardrails (§9), PK storage vs PHP strings (`BIGINT`, `ATTR_STRINGIFY_FETCHES`), **`validateTrustLadderPk`** / **`validateFormat`** / **`toCanonicalIdSafe`** specs, seed rule with **2026** ratification bound + registry authorization, §4 PDO notes, §11–§13 (migration/backfill, performance, tests), **Appendix A** (alternatives rejected), **Further reading** cross-links; Grok/WOLFIE final polish (Captain's Log accuracy: no false `seedActorToCanonicalId` blog claim; seeds stay short).
- **`lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md`** — Table participation registry (full / generator_staging / seed_only); links to install validation.
- **`lupo-docs/doctrine/RETENTION_POLICY.md`** — Staging soft-delete retention alignment with PRD 19.
- **`lupo-content/federation_node/0/captains_log/20260408_CHRONOLOGICAL_TRUST_LADDER.md`** — Captain's Log blog; doctrine links; **`lupopedia.edges`** → CHRONOLOGICAL_TRUST_LADDER.

**WHAT (code):**

- **`lupo-includes/classes/IdGenerator.php`** — **`toCanonicalId()`** / **`toCanonicalIdSafe()`** return **strings**; **`validateTrustLadderPk()`** seed band uses **padded string compare** (no `(int)` on 18-digit ids); **`seedActorToCanonicalId()`** per PRD 41 (bcadd / 64-bit / digit-wise fallback); **`numericStringLessThan`** helper.
- **`app/Services/Kairos/KairosConsolidationService.php`** — Migrated from **`lupo_actor_memory`** to **`lupo_memory_nodes`** (PRD 38); observations = staging **`generate()`**; consolidated = **`toCanonicalIdSafe()`** + **`validateTrustLadderPk`**; **`lupo_edges`** object type **`memory_node`**; **`content_hash`**, **`owner_actor_id`**, doctrine **`created_ymdhis`** from id prefix; contradiction pair ordering fixed (padded strcmp); **`flare_db_source`** → `lupo_memory_nodes`.
- **`lupo-scripts/validate_trust_ladder_registry.py`** — Dual **`CREATE TABLE`** patterns (`{{prefix}}` + literal `lupo_`), **`IF NOT EXISTS`**, case-insensitive compare, **`argparse`** (`--install-sql`, `--registry`), empty-registry warning, extended module docstring.

**WHERE:** Doctrine under `lupo-docs/doctrine/`; runtime under `lupo-includes/`, `app/`; validation under `lupo-scripts/`; narrative under `lupo-content/`; **this version bundle** under `lupo-docs/versions/4.0.96/` (CHANGELOG, SUMMARY, `status/FOR_CLAUDE_CODE_ON_PK_IDS.md`, THREAD_INDEX).

**WHEN:** Documented at **20260408031925** UTC (`tick.py` anchor for this batch).

**WHY:** Close the gap between “referenced guardrails” and enforceable doctrine; align KAIROS with PRD 38 + trust ladder; 32-bit-safe PK handling; CI drift check for registry vs install.

**HOW:** Edits per thread; no **`install_new_lupopedia.sql`** DDL change in this rollup (memory_nodes/edges already present). KAIROS DB rows previously in **`actor_memory`** are **not** auto-migrated — fresh install or one-time migration required if legacy data existed.

**Handoff:** **`lupo-docs/versions/4.0.96/status/FOR_CLAUDE_CODE_ON_PK_IDS.md`** — full doctrine summary + future web UI for install-record maintenance (Claude Code / actor **116**).

---

## [2026-04-08 02:04 UTC] — Session rollup: Chronological Trust Ladder doctrine; seed→canonical actors; LILITH prompt; PRD shorthand tool

**WHO:** cursor (actor_id 102)

- **Naming — Honolulu → Chronological Trust Ladder** — Canonical doctrine: **`lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md`**. **PRD 00** §3.7, **PRD 37** §1.2 / edges, **PRD 38** §4.2, **PRD 41** (headings + consolidation copy), **PRD 42** §8 / edges; **`lupo-docs/prd/decisions/pseudocode/38_memory_unification_constitution.pseudo.md`**; **`lupo-scripts/generate_prd_shorthands.py`** (PRD 38 shorthand heading). **`claude.md`** — Key Doctrines link + tier summary. Shorthands regenerated (**`--all --force`**); earlier changelog rows below still say “Honolulu” as historical titles for the same PK-band work.
- **Low `actor_id` seed → living canonical** — **`seedActorToCanonicalId`**: **`100000000000000000 + seed_actor_id`** (e.g. **116** → **`100000000000000116`**). **PRD 15** — new subsection; **PRD 41** — **§2.3** + **§3** alternate allocation note; **`claude.md`** — dual identity table + Trust Ladder note; **`AGENTS.md`** — IDE table row for Claude Code **116** / canonical id.
- **`lupo-agents/lilith/system_prompt.txt`** — LILITH reframed as **constitutional enforcer** and partner to WOLFIE; **AGAPE** acronym + doctrine pointer; **UI** — **`lupo-includes/js/lupo-layers.js`** (eval-free) vs legacy DynAPI; YAML output checklist; **COUNTERMEASURE (111)** inherits non-interfering review lane.
- **`lupo-scripts/generate_prd_shorthands.py`** — Staleness prefers **PRD `when_updated` / `last_modified_utc`** vs shorthand **`last_verified`**, else mtime; **`lupopedia.edges.outbound_edges`** merged into Edge Types; **`RESERVED_RANGES`** documented as prose-only; skip list via **`DEFAULT_SKIP_NAMES`**, env **`LUPO_PRD_SHORTHAND_EXTRA_SKIP`**, **`--skip-name`**.

---

## [2026-04-08 00:46 UTC] — Honolulu living canonical (1000–1999) vs staging; PRD 00 §3.7

**WHO:** cursor (actor_id 102)

- **PRD 00** — New **§3.7 Universal data consolidation (Honolulu pattern)** (install seed vs **living canonical** **1000–1999** vs **staging** **2000–9999**; promote vs **UPDATE** canonical; soft-delete staging; edges; cross-ref **PRD 37** / **PRD 38**). **`lupopedia.edges`** → **PRD 38**.
- **PRD 38** — **§4.2** reframed: **1000–1999** = **living canonical** (**mutable**, accumulated best knowledge), **2000–9999** = **staging** (merged then soft-deleted); consolidation steps cover **UPDATE** path; **§8** trust encoding notes mutability of long-term band.
- **PRD 37** — **§1.2** aligned (staging vs living canonical, **UPDATE** allowed); **`next_action`** / **`lupopedia.edges`** reason updated.
- **`generate_prd_shorthands.py`** — PRD **38** shorthand table + consolidation one-liner updated.
- **Regenerated:** `38_memory_unification_constitution.pseudo.md`, `37_kairos_channel_memory_consolidation_constitution.pseudo.md`.

---

## [2026-04-08 00:36 UTC] — Memory trust tiers (Honolulu pattern) + KAIROS PK alignment

**WHO:** cursor (actor_id 102)

- **PRD 38** — New **§4.2 Memory trust tiers (Honolulu pattern)** (install vs **1000–1999** long-term vs **2000–9999** runtime; consolidation flow; **`consolidated_into`**; query priority; relation to **§8 Option B**). **§8** intro links archived ids to **§4.2** trust band.
- **PRD 37** — **§1.2 PK trust encoding** for **`lupo_memory_nodes`** consolidated rows (year **1000–1999**; **`kairos_consolidates_from`** / **`consolidated_into`**); **`lupopedia.edges`** → **PRD 38**; **`next_action`** bullet for PK band on merge.
- **`generate_prd_shorthands.py`** — PRD **38** shorthand adds **Memory trust tiers** block ahead of **Option B**.
- **Regenerated:** `38_memory_unification_constitution.pseudo.md`, `37_kairos_channel_memory_consolidation_constitution.pseudo.md`; **THREAD_INDEX** refreshed.

---

## [2026-04-08 00:17 UTC] — Long-term memory archiving (Option B) + CLI spec

**WHO:** cursor (actor_id 102)

- **PRD 38** — New **§8 Long-Term Memory Archiving (Option B)** (subtract **1000** from calendar year in **`memory_node_id`** for runtime rows; era table; **`archived_to`** edge; **`toLongTermId` / `isLongTermId`** PHP reference; cross-era query notes). Former §§8–13 renumbered to **§§9–14**; amendments block is now **§11** (**11.1–11.5**). **`lupopedia.footer` `next_action`** points to **§11.1–11.5**.
- **PRD 24** — **§5.8** `memory archive` / **§5.9** `memory restore` (examples, options table); **`edges add`** types include **`archived_to`**, **`restored_from`**; command summary table updated; header **`purpose`** / **`next_action`** extended.
- **`lupo-scripts/generate_prd_shorthands.py`** — PRD **38** shorthand embeds **Long-Term Archiving (Option B)** + CLI one-liners.
- **Regenerated:** `38_memory_unification_constitution.pseudo.md`, `24_cli_interface_prd_constitution.pseudo.md`, `24_actor_onboarding_flow_constitution.pseudo.md` (both **24_** PRDs via `--prd 24 --force`); **`THREAD_INDEX.md`** refreshed.

**Status:** `lupo-docs/versions/4.0.96/status/STATUS_MEMORY_ARCHIVE_OPTION_B_20260408001717.md`

---

## [2026-04-07 23:59 UTC] — PRD 00 §3.2.1 global seed vs runtime PK doctrine

**WHO:** cursor (actor_id 102)

- **PRD 00** — New **§3.2.1 Primary key strategy — seed vs runtime**: dual PK table, illustrative per-table bands (**actors**, **agents**, **departments**, **channels**, **auth_users**, **memory_nodes**, **edges**, **permissions** / **rules**) with **install + registry** as authority; rules 1–6; **`lupo-memory/1970/01/`** pointer. **§3.2** opening and **Implementation** qualified for runtime vs install SQL.
- **PRD 38** — **§4.0** / rules scoped to **runtime**; new **§4.1** DDL comment block; **§6.6** seed vs runtime export examples table.
- **PRD 01** — **`lupo_actors`**: `CREATE TABLE` comment block (seed vs runtime PK / `created_ymdhis`); seed actor note.
- **PRD 15** — **Actor ID ranges (seed vs runtime)** (`actor_id` < 2026 vs ≥ 2026).
- **PRD 07** — **Seed `agent_id` vs runtime** under Agent Architecture.
- **PRD 24** — Cross-reference **PRD 00 §3.2.1**.

**Status:** `lupo-docs/versions/4.0.96/status/STATUS_SEED_RUNTIME_PK_DOCTRINE_20260407235921.md`

---

## [2026-04-07 23:55 UTC] — Seed PK vs `created_ymdhis` + memory export pre-history path

**WHO:** cursor (actor_id 102)

- **Doctrine (PRD 00)** — §3.2 **registry/seed exception**: fixed low PKs in install/seed vs **`IdGenerator`** for runtime; **`created_ymdhis`** may be install UTC, insert time, or **`0`** (immemorial); **§5.7** companion paragraph for **`lupo_memory_nodes`** / **`lupo_memory_edges`** and **`lupo-memory/1970/01/`** when **`created_ymdhis = 0`**.
- **PRD 01** — **`lupo_actors`**: **`actor_id`** and **`created_ymdhis`** descriptions; seed vs runtime **`created_ymdhis`** under workspace rules.
- **PRD 38** — §4.0 seed/runtime table; DDL comments in §5.1; §6.1 export normalization; §7 tree example **`1970/01/`**.
- **PRD 24** — **Actor ID Generation**: runtime **`IdGenerator`** + **`created_ymdhis`** prefix vs seed fixed ids.
- **`MemoryExportService.php`** — **`createdYmdhisForExportPath()`**: **`created_ymdhis`** empty / **`0`** / too short → **`19700101000000`** for mirror path and slug (DB row unchanged).

**Status:** `lupo-docs/versions/4.0.96/status/STATUS_SEED_PK_CREATED_YMDHIS_MEMORY_EXPORT_20260407235530.md`

---

## [2026-04-07 23:33 UTC] — README actor model + Claude Code IDE identity note

**WHO:** cursor (actor_id 102)

- **Root `README.md`** — New **§3 Actor Model: Why It Is Different** (auth user → department → shared actor; web intersection vs CLI/IDE root-equivalent tooling; **`auth_user_id = 0`** doctrine vs **`actor_id`**; memory pointers to **`lupo_memory_nodes` / `lupo_memory_edges`**). Following sections renumbered **4–8**.
- **`claude.md`** — **Identity for IDE Agents** (facet attribution, no extra auth users, links to README + PRD 01).

## [2026-04-07 23:35 UTC] — PRD 15 / PRD 05 aligned with README §3 actor model

**WHO:** cursor (actor_id 102)

- **`lupo-docs/prd/15_actors.md`** — New **Overview** subsection **Three-layer identity model (4.0.96+; root README §3)** mirroring README: layers table, shared persona, illustrative department-intersection SQL, CLI/IDE root-equivalent note, **`auth_user_id = 0`** vs **`actor_id`**, memory pointers (**PRD 38**). **`lupopedia.edges`** → **`README.md`**.
- **`lupo-docs/prd/05_auth_user_actor_agent_transformation.md`** — New **Root README alignment (4.0.96+)** (link to README §3; web vs CLI/IDE; **AGENTS.md**; PRD 01 root id). **`lupopedia.edges`** → **`README.md`**.

---

## [2026-04-07 23:30 UTC] — PRD doctrine sweep (round 2: hundredths, headers, schema paths)

**WHO:** cursor (actor_id 102)

- **PRDs 02, 03, 09, 11** — Documented former DECIMAL scores as **INT hundredths** (`weight_hundredths`, `confidence_hundredths`, `credibility_hundredths`, `trust_hundredths`, `duration_hundredths`, `bounce_rate_hundredths`) with index column names aligned in prose.
- **PRD 02** — Vote INSERT examples use **PHP `IdGenerator::generate()`** and **`:vote_id`** placeholders (no SQL `generate_id()`).
- **PRD 04** — **`lupo_edges.direction`** documented as **VARCHAR(16)** with application-layer validation (portable SQL); avoid MySQL **ENUM** in new DDL. **`weight_score`** column note retained alongside legacy **DECIMAL** callouts in install.
- **Headers** — Replaced deprecated **`version_when_written`** with **`when_updated`** on grouped namespace PRDs (03–15 temporal, 23); **36** / **37** dropped duplicate **`version_when_written`**. **27** `file_path_from_root` no longer uses a leading **`/`**.
- **PRD 08_actors** — **`last_modified_utc`** normalized to **14-digit** packed form (`20260331000000`).
- **Schema references** — **05, 18, 36, 37** now cite **`lupo-database/lupopedia/json/*.json`** instead of **`.toon`** paths.
- **PRD_INDEX** — Banner **4.0.96**; **08_actors** entry annotated **SUPERSEDED** (see **15_actors.md**). **29_project_structure** table row matches. **lupo-docs/prd/README** clarifies **14 core namespaces** vs extended PRDs.

---

## [2026-04-07 17:29 UTC] — Canonical version bump (atoms + runtime)

**WHO:** cursor (actor_id 102)

- **`lupo-config/global_atoms.yaml`** — `version`, **`GLOBAL_CURRENT_LUPOPEDIA_VERSION`**, and `versions.*` metadata set to **4.0.96**; top `file.last_modified_system_version` aligned.
- **`version.txt`**, **`lupo-includes/version.php`** (`@version` docblock) — **4.0.96**.
- **`lupo-docs/doctrine/VERSIONING_DOCTRINE.md`** — §1 canonical current version **4.0.96**.
- **`lupo-rules/root/php-7-4-compatibility.md`** — rule stamp **4.0.96+**.
- **Root `README.md`**, **`CHANGELOG.md`** (routing) — current line pointers → **`lupo-docs/versions/4.0.96/`**.
- **`lupo-docs/versions/4.0.96/README.md`** — added as the version-folder overview for the active line.

---

## [2026-04-07 22:47 UTC] — PRD 38 memory DDL, MemoryExportService, PRD audit notes

**WHO:** cursor (actor_id 102)

- **`install_new_lupopedia.sql`** — `memory_nodes` + new `memory_edges` per **PRD 38** (`created_ymdhis` aligned to `IdGenerator` timestamp prefix; no `memory_slug` column).
- **`lupo-includes/classes/MemoryExportService.php`** — filesystem mirror from `created_ymdhis` + `generateSlug()`.
- **`lupo-docs/prd/38_memory_unification.md`** — full revision (sections renumbered; IdGenerator rules; DDL; section 13 IDE prompt).
- **`lupo-docs/versions/4.0.96/status/`** — `PRD_REVIEW_DISCREPANCIES_AND_IMPROVEMENTS_20260407224750.md`, `THREAD_INDEX.md` (PRD index/README drift, memory model fork list).

---

## [2026-04-07 23:20 UTC] — Status file, actor_memory_id PK, PRD 04 edges, TOON regen

**WHO:** cursor (actor_id 102)

- **`lupo-docs/versions/4.0.96/status/STATUS_SESSION_PRD_MEMORY_IDENTITY_20260407232053.md`** — workstream summary + embedded forward prompt (constitutional PRD batch); **`THREAD_INDEX.md`** updated.
- **`install_new_lupopedia.sql`** — `lupo_actor_memory` PK renamed **`memory_id` → `actor_memory_id`** (PK naming doctrine).
- **`app/Services/Kairos/KairosConsolidationService.php`** — all column references updated.
- **`lupo-docs/prd/01_core_identity.md`** — `lupo_actor_memory` aligned to install; session cleanup → soft **`UPDATE`**; `resulted_in_actor_memory_id` in training table prose.
- **`lupo-docs/prd/04_tags_metadata.md`** — **`lupo_edges`** section aligned to install; header **`when_updated`**.
- **`lupo-docs/prd/09_federation_sync.md`** — actor_memory PK name in summary table.
- **`lupo-database/lupopedia/json/lupo_actor_memory.json`** — PK field name aligned.
- **`python lupo-scripts/generate_toon_from_sql.py`** — ran (177 TOONs); **removed stale exports** not in install — verify **`git status`**.

---

## [2026-04-07 23:08 UTC] — PRD 01 session identity resolution

**WHO:** cursor (actor_id 102)

- **`lupo-docs/prd/01_core_identity.md`** — `lupo_sessions` column list aligned with **`install_new_lupopedia.sql`**; new **Session Identity Resolution (4.0.96+)** (proxy IP header order, Class C identity string, SESSIONID fallback chain, cookieless recovery, token generation Crafty vs `App\Auth\Session`, `metadata` mapping); edge to **`craftysyntax-reference/functions.php`**.

---

## [2026-04-07 22:53 UTC] — PRD 38 edges + export phases

**WHO:** cursor (actor_id 102)

- **`lupo-docs/prd/38_memory_unification.md`** — `lupopedia.edges`: added **amends** to **07_agents_faucets** and **15_actors**; section **6.5** documents **Phase 1 synchronous** vs **Phase 2 optional queue** export; section **10.3–10.5** states amendment scope for PRDs 07, 15, 37; header/footer UTC via **tick.py**.

---

## [2026-04-07 20:00 UTC] — Doctrine Expansion, Memory Model Deprecation, File-Backed Content

**WHO:** claude-code (actor_id 102) — doctrine expansion pass, memory model update, file-backed content system.

### Context‑Typed, Status‑Aware, Directional Edged Memory Doctrine (4.0.96) — Formal Adoption

- **Doctrine block authored and distributed** across **32 memory-related PRDs**. Each PRD now carries the canonical `## Context‑Typed, Status‑Aware, Directional Edged Memory Doctrine (4.0.96)` section at its end, appended exactly once, with no other content altered.
- **Doctrine defines a 4-dimensional edge model:** every edge in `lupo_edges` has: `edge_type` (relationship), `edge_context` (structural classification), `edge_status` (epistemic support level: `unsupported` / `supported` / `needs_review`), `direction` (`uni` / `bi` / `restricted`).
- **`review_reason` field (Option C):** when `edge_status = 'needs_review'`, a `review_reason` MUST be set. Values: `orphaned_edge`, `contradiction`, `new_doctrine`, `schema_drift`, `consolidation_candidate`, `integrity_unknown`, `human_escalation`. Each value routes to a specific agent: ANUBIS (integrity/orphan), THOTH (schema/contradiction/doctrine), KAIROS (consolidation), human operator (escalation).
- **PRD files touched:** `00_root_constitutional_system_requirements.md`, `01_captain_wolfie_identity.md`, `01_core_identity.md`, `02_channels_discussions.md`, `02_data_model.md`, `03_goals_and_success_criteria.md`, `03_truth_knowledge.md`, `04_lupopedia_js_foundation.md`, `04_tags_metadata.md`, `05_auth_user_actor_agent_transformation.md`, `07_agents_faucets.md`, `09_federation_sync.md`, `11_analytics_tracking.md`, `13_crafty_integration.md`, `15_actors.md`, `15_temporal_system.md`, `16_lupopedia_headers.md`, `17_decisions_format.md`, `18_channel_chat_display.md`, `19_garbage_collection_system.md`, `21_semantic_navbar.md`, `24_actor_onboarding_flow.md`, `26_five_layer_documentation_architecture.md`, `28_semantic_monitoring_widget.md`, `29_project_structure.md`, `31_implementation_folder_guidelines.md`, `32_actor_authority_agent_roles.md`, `33_softaculous_certification_4_1_0_gate.md`, `34_federation_node_semantic_network.md`, `36_rose_multi_persona_synthetic_dialog.md`, `37_kairos_channel_memory_consolidation.md`, `PRD_AGENT_DEFINITION_MODEL.md` (7 already carried the block from prior sessions; 25 newly appended this pass).

### 4-Dimensional Edge Columns Added to `lupo_edges` (install SQL)

- **`install_new_lupopedia.sql`** — `{{prefix}}edges` table updated with four new columns:
  - `edge_context  varchar(64)  DEFAULT NULL` — structural classification of the memory relationship.
  - `edge_status   varchar(32)  DEFAULT 'active'` — epistemic support level of the edge.
  - `direction     enum('uni','bi','restricted')  DEFAULT 'uni'` — traversal orientation.
  - `review_reason varchar(64)  DEFAULT NULL` — agent routing key when `edge_status = 'needs_review'`.
- **New indexes on `lupo_edges`:** `idx_edge_context` (edge_context), `idx_direction` (direction), `idx_status_review` (edge_status, review_reason).

### `memory.json` Deprecated — Actor/Agent Memory Model Updated

All references to `memory.json` as active storage for actor or agent learned behavior have been replaced in the following PRDs:

- **`01_core_identity.md`** — File tree: `memory.json` entry replaced with deprecation comment; Actor Creation Flow step 9: "Learned behavior stored as root memory node at `lupo-memory/YYYY/MM/{memory_slug}.json`; registered in `lupo_memory_nodes`; all memory relationships expressed via `lupo_edges` (4.0.96+). `memory.json` is deprecated."
- **`15_actors.md`** — Both workspace file trees (system actor and runtime actor sections) updated; `### memory.json (Learned from Department Context)` section replaced with `### Root Memory Node (Learned from Department Context) — 4.0.96+` section (includes example JSON for `lupo-memory/2026/04/wolfie-sales-actor-5001.json`, schema reference, edge linkage); Actor Learning Process step 5 updated to reference `lupo-memory/YYYY/MM/{memory_slug}.json` + `lupo_memory_nodes` + `lupo_edges`.
- **`07_agents_faucets.md`** — Actor workspace file tree and optional agent files tree: `memory.json` entries replaced with deprecation comments pointing to `lupo-memory/YYYY/MM/{memory_slug}.json`.
- **`24_actor_onboarding_flow.md`** — Workspace initialization step 5: "Register root memory node in `lupo_memory_nodes`"; Actor Learning paragraph: updated to reference root memory node model and `lupo_edges`; `memory.json` explicitly marked DEPRECATED.
- **`PRD_AGENT_DEFINITION_MODEL.md`** — Both file trees (agent root and versioned layouts); `### memory.json` section replaced with `### Root Memory Node (4.0.96+) — replaces memory.json`; Runtime State note updated.

**Canonical replacement language:** "Actor/agent memory is modeled as memory nodes in `lupo_memory_nodes`, linked via edges in `lupo_edges`, with the actor's root memory node exported to `lupo-memory/YYYY/MM/{memory_slug}.json`."

### File-Backed Content System — PRD 06

- **`lupo-content/` directory** reorganized to canonical structure: `lupo-content/federation_node/{id}/{folder_key}/{file_name}` and `lupo-content/actor/{actor_id}/{folder_key}/{file_name}`. Old directories (`0/`, `actor_id/`, `federation_node_id/`) removed. Files renamed to snake_case lowercase.
- **`install_new_lupopedia.sql`** — `{{prefix}}contents` table updated:
  - `storage_type varchar(16) NOT NULL DEFAULT 'database'` column added (values: `database`, `file_backed`).
  - `file_path_from_root` expanded from `varchar(255)` to `varchar(1024)`.
  - UNIQUE index added: `{{prefix}}contents_idx_slug_deleted (slug, is_deleted)`.
  - `{{prefix}}folders` table updated: `description text DEFAULT NULL` column added.
- **`lupo/install/seed_lupopedia_4_1_0.sql`** — 3 folder rows (ids 10, 11, 12) and 6 content rows (ids 1000000–1000005) added, all `storage_type='file_backed'`, `content=NULL`, `utc_cycle='daily'`, with canonical `file_path_from_root` values.
- **`lupo-docs/prd/06_content_management.md`** — Updated: canonical directory structure, slug rules, `storage_type` rules, migration SQL documented; applied schema corrections reflected; `lupo_folders.description` documented; `when_updated` and `last_verified` updated to `20260407123924`.

### README.md Lupopedia Headers — Malformed Edge Fixed

- **Root `README.md`** — `outbound_edges` YAML block repaired: first edge entry was at 2-space indent (parsed as sibling of `outbound_edges:` key rather than a list item under it), causing all 19 subsequent edges to nest as children of the first. All 20 edges normalized to 4-space indent. Trailing space on one `to:` value removed.

---

## Carried Over Tasks from 4.0.95

All open tasks from the closed **4.0.95** line were migrated to **[TODO.md](TODO.md)** (section **Carried Over from 4.0.95**) on UTC `20260407172944`. Source snapshots: **`../4.0.95/TODO.md`**, **`../4.0.95/PLAN.md`**, **`../4.0.95/README.md`** (release criteria), and the **Not completed** note formerly in **`../4.0.95/CHANGELOG.md`**.

## Planned Work for 4.0.96

- Execute backlog in dependency order (see **[TODO.md](TODO.md)**).
- Record landed work in this file with UTC-thread discipline per PRD 17.
- Use **`PRD_PATCHES/`** for PRD delta drafts and **`SCHEMA_DIFFS/`** for install/schema notes when applicable.
- Use **`NOTES/`** for scratch coordination that is not yet changelog-grade.

This output complies with Lupopedia Constitutional Root Rules.

---

## [2026-04-07 21:00 UTC] — 5W1H Thread Update: Memory, Actors, Doctrine, Schema, Ingestion, Compression

| Element | Answer |
|--------|--------|
| WHO    | Cursor IDE Agent (actor_id 102) |
| WHAT   | Major documentation, schema, and PRD updates for memory model, actor separation, doctrine, ingestion, and compression |
| WHERE  | 
  - lupo-docs/prd/01_core_identity.md
  - lupo-docs/prd/06_content_management.md
  - lupo-docs/prd/11_analytics_tracking.md
  - lupo-docs/prd/13_crafty_integration.md
  - lupo-docs/prd/15_actors.md
  - lupo-docs/prd/24_actor_onboarding_flow.md
  - lupo-docs/prd/37_kairos_channel_memory_consolidation.md
  - lupo-docs/prd/PRD_AGENT_DEFINITION_MODEL.md
  - lupo-docs/versions/4.0.96/CHANGELOG.md
  - lupo-docs/versions/4.0.96/README.md
  - lupo-docs/versions/4.0.96/SUMMARY.md
  - lupo-docs/versions/4.0.96/TODO.md
  - lupo-docs/versions/4.0.96/MIGRATION_NOTES.md
  - lupo-content/ (structure, file-backed content)
  - lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
  - lupo-database/lupopedia/actors/registry.json
  - lupo-actors/116/ (Claude Code)
  - claude.md
  - README.md (root)
| WHEN   | 2026-04-07 21:00 UTC |
| WHY    | To document and preserve all major changes, decisions, and implementation details from this thread |
| HOW    | See below for detailed summary |

### Summary of Work

1. **Memory Model 4.0.96**
  - Adopted 4D edge model (edge_type, edge_context, edge_status, direction, review_reason)
  - Deprecated memory.json; root memory nodes now in lupo-memory/YYYY/MM/{memory_slug}.json
  - PRDs updated: 01, 15, 24, PRD_AGENT_DEFINITION_MODEL, and others

2. **Actor Separation**
  - Cursor remains actor_id 102; Claude Code created as actor_id 116 (per registry)
  - lupo-actors/116/ created with identity.json, boundaries.json
  - claude.md overview created; registry updated

3. **Doctrine Expansion**
  - Context-Typed, Status-Aware, Directional Edged Memory Doctrine appended to 32 PRDs
  - review_reason routing to ANUBIS, THOTH, KAIROS, or human operator

4. **Schema Updates**
  - lupo_edges: new columns (edge_context, edge_status, direction, review_reason)
  - lupo_memory_nodes: added to install SQL
  - lupo_contents: storage_type, file_path_from_root expansion, new indexes
  - lupo_folders: description column added

5. **File-Backed Content System**
  - Canonical directory structure under lupo-content/
  - Seed file updates for file-backed content

6. **Content & Analytics Ingestion Pipeline**
  - PRD 11 extended with comprehensive pipeline for Crafty Syntax data import
  - Covers content pages, navigation paths, referrers, analytics, memory node and edge creation, KAIROS and Lossy Abbreviation Dialect integration

7. **Lossy Abbreviation Dialect**
  - Doctrine for semantic compression of memory nodes
  - New edge_context: lossy_abbrev; edge_type: abbreviates; review_reason: compression_candidate
  - KAIROS integration rules

8. **README.md Fixes**
  - outbound_edges YAML indentation corrected

### Decisions, Questions, Answers, Comments, Observations

- All major decisions, schema changes, and doctrine updates are reflected in the PRDs and version documentation above.
- No new questions, answers, or comments were added to version subfolders in this thread.
- Observations and implementation notes are embedded in the changelog and PRD update summaries.

---
