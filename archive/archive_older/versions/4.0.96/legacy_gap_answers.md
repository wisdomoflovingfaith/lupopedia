---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260408133230"
  file_path_from_root: "docs/versions/4.0.96/LEGACY_GAP_ANSWERS.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.96/LEGACY_GAP_ANSWERS.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: note
  artifact_kind: legacy_gap_answers
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: docs/versions/4.0.96/LEGACY_GAP_ANSWERS.md — delegation: claude_code:root

# Legacy Gap Answers — TrustLadderRegistry Implementation

**Author:** Claude Code (actor_id 116)
**Session:** 2026-04-08 13:32 UTC
**Context:** Answers to the 11 questions raised in `note_on_seed_range.md` during
the TrustLadderRegistry implementation sprint. These answers are implementation decisions,
not constitutional amendments — they can be revised with operator approval.

---

## Q1. Should batch backoff remain random jitter only, or include exponential escalation?

**Answer:** Include exponential escalation with a capped ceiling.

Random jitter alone is an anti-contention pattern, not a fairness or
liveness pattern. Under sustained contention (many concurrent promotions to
the same canonical slot), pure random jitter means workers keep retrying at
the same average rate without any signal that the window is saturated.

**Recommended strategy:**
```
delay = min(BASE_DELAY * (2 ** attempt), MAX_DELAY) + random(0, JITTER_MS)
BASE_DELAY = 50ms, MAX_DELAY = 5000ms, JITTER_MS = 100ms
```

Apply this to `toCanonicalIdSafe()` retry loops and any batch import path
that calls `IdGenerator::generate()` at high frequency.

**Why the cap matters:** Without a ceiling, exponential backoff causes batch
imports to stall indefinitely under moderate contention. 5 seconds is a
practical ceiling for a 10-retry loop (cumulatively ~12 seconds maximum).

---

## Q2. What is the fallback when all 10,000 suffixes are exhausted?

**Answer:** Throw `RuntimeException` with a clear diagnostic, log to `lupo_unified_log`, and surface the error to operators.

The current `toCanonicalIdSafe()` already throws `RuntimeException` after `maxRetries`
exhaustion. The exhaustion case means 10,000 rows were inserted in the **same
second** with the same 14-digit timestamp prefix — extremely unlikely in any
realistic workload.

**Recommended addition:** On exhaustion, log to `lupo_unified_log` with
`log_type='trust_ladder'`, `log_level='critical'`, and include the timestamp
prefix and table name. This enables post-mortem analysis.

**Do NOT:** silently fall back to a non-ladder PK, AUTO_INCREMENT, or UUID.
Tier contamination is worse than a failed insert.

**Long-term escape hatch:** If a table legitimately expects 10,000+ inserts
per second, revisit whether 18-digit timestamp-shaped IDs (with 4-digit
suffix = 10,000 slots/second) are the right PK strategy for that table. This
is a constitutional question, not a patch.

---

## Q3. Where is locking needed for concurrent promotion (`SELECT ... FOR UPDATE` or equivalent)?

**Answer:** In `toCanonicalIdSafe()` during the collision-check SELECT.

The current implementation does a SELECT-then-INSERT (TOCTOU race). Two
concurrent promotions of different staging IDs that collide on the same
canonical slot can both see "no conflict" and then one will fail on the
INSERT duplicate-key error.

**Recommended for MySQL/MariaDB:**
```sql
SELECT 1 AS x FROM lupo_memory_nodes WHERE memory_node_id = :id FOR UPDATE LIMIT 1
```

**For SQLite:** `BEGIN IMMEDIATE` transaction before the SELECT.
**For PostgreSQL:** `SELECT ... FOR UPDATE` (same as MySQL) with `NOWAIT` to
avoid indefinite blocking; catch `LockNotAvailable` and retry.

**`DatabaseFactory` portability note:** The `FOR UPDATE` clause must be
emitted conditionally based on the DB type. Add `quoteForUpdate()` or a
`selectForUpdate()` method to `PDO_DB` / `DatabaseFactory` if it doesn't exist.

**Where NOT needed:** Read-only paths (registry load, audit scripts). Lock
only the promotion write path.

---

## Q4. Which references should target seed IDs vs canonical IDs?

**Answer:** Per `TRUST_LADDER_REGISTRY.md` `reference_target` field — not hard-coded.

| Archetype | reference_target | Why |
|-----------|-----------------|-----|
| parent    | `seed`          | Parent tables have stable seed rows; child references point to the seed identity |
| child     | `canonical`     | Child rows reference the promoted 18-digit canonical PK of the parent |
| system    | `seed`          | System tables (registry, edges) self-reference at the seed tier |

**Operational rule:** Any code that writes a FK or lineage edge MUST call
`TrustLadderRegistry::getReferenceTarget($table)` to resolve this — never
hard-code `seed` or `canonical`.

---

## Q5. Is 32-bit runtime support still required, or should production hard-ban it?

**Answer:** 32-bit is transitional — warn loudly but do not hard-ban yet.

`CHRONOLOGICAL_TRUST_LADDER.md §2.2.1` states: "Production MUST use 64-bit PHP 7.4+;
legacy 32-bit / PHP 5.6 hosts are transitional."

**Recommended stance for 4.0.96:**
- Emit `E_USER_WARNING` at bootstrap if `PHP_INT_SIZE !== 8`.
- All 18-digit PK manipulation must remain string-based (already enforced in `IdGenerator`).
- `IdGenerator::seedActorToCanonicalId()` already has a 32-bit fallback (digit-wise addition).
- Add a `php bin/lupo.php doctor` check that reports 32-bit PHP as a `WARNING`.
- Gate the 4.1.0 Softaculous certification on a 64-bit PHP requirement.

**Hard-ban in 4.1.0 or later**, once a Softaculous minimum PHP version
requirement is confirmed. File as an issue now.

---

## Q6. How long should `consolidated_into` edges remain after staging deletion?

**Answer:** Retain indefinitely — never physically delete consolidation lineage edges.

`consolidated_into`, `merged_into`, `promoted_to`, `canonical_instance_of`,
and `archived_to` edges encode provenance. Deleting them erases the audit
trail for how a canonical row came to exist.

**Policy:**
- Soft-delete (`is_deleted = 1`) the staging row after promotion.
- `StagingGcService` purges soft-deleted staging ROWS (nodes/data) after
  90 days, but must **not** delete lineage edges that reference those rows.
- The lineage edge itself is never eligible for GC — it is a permanent
  chronicle of the promotion event.

**Practical implication:** `lupo_memory_edges` (and `lupo_edges` for object-type
pairings) must not be included in the staging-row GC batch for lineage edge
types. Add an exclusion filter in `StagingGcService` for these edge types.

---

## Q7. How should registry cache invalidation work after sync for long-running workers/daemons?

**Answer:** Two-tier: in-process TTL + a shared invalidation signal.

**Short-term (4.0.96):**
- `TrustLadderRegistry::clearCache()` is the explicit invalidation API.
- Document that after running `sync_trust_ladder_registry_to_db.py`, operators
  must restart PHP-FPM workers (or call `clearCache()` from an admin endpoint).
- For CLI tools (`bin/lupo.php`), the process is short-lived — no TTL needed.

**Medium-term (4.1.x):**
- Add a `registry_version` integer row in `lupo_system_config`.
- `loadCacheIfNeeded()` checks this version on every N-th call (e.g. every 100 calls)
  and re-loads if the version differs from what was loaded.
- This avoids a DB query on every `getTableMeta()` while still catching post-sync state.

**Until implemented:** `TODO(LEGACY-GAP): Cache invalidation for long-running workers.`

---

## Q8. What is the migration path for tables that already exist but are not yet registered?

**Answer:** Three-phase migration:

1. **Audit** — Run `sync_trust_ladder_registry_to_db.py --dry-run --strict` to identify
   all unregistered tables (tables present in install SQL but absent from the registry).
2. **Document** — Add each missing table to `TRUST_LADDER_REGISTRY.md` with the correct
   archetype and participates value. This is the doctrine-first step.
3. **Sync** — Run `sync_trust_ladder_registry_to_db.py --force` to write the rows.
   Deploy updated `TrustLadderRegistry.php` only after the DB rows exist.

**During the migration window:** Set `LUPOPEDIA_ENV=development` so that
unregistered table lookups emit warnings instead of throwing exceptions.
Return to `production` mode after all tables are registered.

**Constraint:** Never guess or hard-code archetypes in code. All archetype
decisions must flow through the Markdown registry → sync → DB.

---

## Q9. Should we add `last_validated_ymdhis` and alert if registry is stale beyond 24 hours?

**Answer:** Yes, as a Phase 2 enhancement — not blocking for 4.0.96.

**Recommended design:**
- Add a `last_validated_ymdhis BIGINT NOT NULL DEFAULT 0` column to
  `lupo_trust_ladder_registry` (additive migration, no data loss).
- `sync_trust_ladder_registry_to_db.py` writes `timestamp_ymdhis::now()` to
  this column after each successful sync.
- `php bin/lupo.php doctor` checks the max `last_validated_ymdhis` and
  emits a WARNING if more than 86,400 seconds (24 hours) have elapsed.

**Why not in 4.0.96:** Adding a column now requires DDL migration tooling that
does not yet exist in this codebase. File as a follow-up issue with priority `medium`.

---

## Q10. How should admin UI expose registry diff safely?

**Answer:** Read-only diff view, no inline editing, restricted to department 0 actors.

**Implementation guidance (for `AdminTrustLadderHandler`):**
- Add a "Registry Doctrine vs DB" panel that calls the PHP equivalent of
  the sync script's `detect_drift()` logic: parse the Markdown, query the DB,
  diff field by field.
- Display as a colour-coded table: green = in-sync, amber = changed, red = missing.
- Do NOT expose raw SQL or full notes text in the web view — truncate or redact
  sensitive `notes` content (LEGACY-GAP annotations are internal).
- Do NOT allow inline edits from the UI. Mutations must go through the sync
  script (doctrine-first workflow).
- Gate the panel behind `$_SESSION['actor_department_id'] === 0` (Root department).

**Sensitive doctrine detail leakage:** The registry `notes` column may contain
`TODO(LEGACY-GAP)` annotations intended for developers, not end-users. Strip or
truncate these before rendering in the admin UI.

---

## Q11. How does `TrustLadderRegistry` behave during unit tests (especially SQLite)?

**Answer:** Use `injectTestCache()` — bypass DB entirely.

**Pattern for unit tests:**

```php
// In test setUp / before first getTableMeta() call:
TrustLadderRegistry::clearCache();
TrustLadderRegistry::injectTestCache([
    'lupo_memory_nodes' => [
        'registry_id'   => 2,
        'table_name'    => 'lupo_memory_nodes',
        'archetype'     => 'parent',
        'participates'  => 'full',
        'seed_required' => 1,
        'reference_target' => 'seed',
        // ... other fields
    ],
]);
// Now any call to TrustLadderRegistry::getTableMeta('lupo_memory_nodes')
// returns the injected data without touching the DB.
```

**For SQLite integration tests** that need a live DB: create the
`lupo_trust_ladder_registry` table in the SQLite test fixture and insert seed
rows before the test runs. The PHP class works identically against SQLite
via `DatabaseFactory` (same `fetchAll` API).

**Development mode behaviour (LUPOPEDIA_ENV=development):**
- When the DB is unavailable (no config, no table), unregistered table lookups
  emit `E_USER_WARNING` and return the permissive not_ladder fallback.
- This allows unit tests that don't call `injectTestCache()` to limp along
  without crashing, though the behaviour is permissive rather than strict.

**Never use `injectTestCache()` in production.**

---

## Summary: Open Issues to File

| # | Issue | Priority |
|---|-------|----------|
| 1 | Add exponential backoff + cap to `toCanonicalIdSafe()` retry loop | medium |
| 2 | Log suffix-exhaustion to `lupo_unified_log` with `log_type='trust_ladder'` | medium |
| 3 | Add `SELECT ... FOR UPDATE` to `toCanonicalIdSafe()` collision check | high |
| 4 | Add `quoteForUpdate()` to `DatabaseFactory` / `PDO_DB` for portability | medium |
| 5 | StagingGcService: exclude lineage edge types from GC | high |
| 6 | Cache invalidation: add `registry_version` token to `lupo_system_config` | medium |
| 7 | Add `last_validated_ymdhis` column + doctor check for stale registry | low |
| 8 | Admin UI: Registry diff panel in `AdminTrustLadderHandler` | low |
| 9 | Hard-ban 32-bit PHP in 4.1.0 gate checklist | low |
| 10 | Table prefix portability for Softaculous custom-prefix installs | medium |

This output complies with Lupopedia Constitutional Root Rules.
