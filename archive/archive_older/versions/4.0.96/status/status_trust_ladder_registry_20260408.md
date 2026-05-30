---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260408133230"
  file_path_from_root: "docs/versions/4.0.96/status/STATUS_TRUST_LADDER_REGISTRY_20260408.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.96/status/STATUS_TRUST_LADDER_REGISTRY_20260408.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: note
  artifact_kind: session_status
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
# file: STATUS_TRUST_LADDER_REGISTRY_20260408.md — delegation: claude_code:root

# Session Status: TrustLadderRegistry Implementation

**Actor:** Claude Code (actor_id 116)
**Session timestamp:** `20260408133230` UTC — 2026-04-08 **13:32** UTC
**Phase:** TrustLadderRegistry sprint (5-phase implementation per mission brief)

---

## Completed This Session

| Component | File | Assertions / Lines | Status |
|-----------|------|--------------------|--------|
| `TrustLadderRegistry.php` | `includes/classes/TrustLadderRegistry.php` | ~310 lines | ✅ Done |
| `sync_trust_ladder_registry_to_db.py` | `scripts/sync_trust_ladder_registry_to_db.py` | ~340 lines | ✅ Done |
| Unit tests | `tests/unit/trust_ladder_registry_test.php` | 47/47 pass | ✅ Done |
| SQL DDL + seed rows | `install_new_lupopedia.sql` (appended) | 13 seed rows | ✅ Done |
| `IdGenerator::toCanonicalIdSafe()` registry check | `includes/classes/IdGenerator.php` | +25 lines | ✅ Done |
| Legacy gap answers | `docs/versions/4.0.96/LEGACY_GAP_ANSWERS.md` | 11 answers | ✅ Done |

---

## Test Results

```
php tests/unit/trust_ladder_registry_test.php
Results: 47 passed, 0 failed
```

All 7 sections (A–G) green. No failures.

---

## Implementation Observations

### 1. Bootstrap sequence is sound

The three-phase bootstrap (DDL → self-referential seed row → all other seed rows) works
cleanly. The PHP `getSelfMetadata()` hard-coded fallback ensures that even during Phase 1
(before the DB row exists), the registry table's own archetype is answerable without a DB
query. No chicken-and-egg failure was encountered.

### 2. `TrustLadderException` pattern fits the project style

The existing `RuntimeException`-based error handling in `IdGenerator` and `StagingGcService`
established the pattern. `TrustLadderException extends RuntimeException` is idiomatic and
consistent. No new base class hierarchy was needed.

### 3. Dev mode permissive fallback worked as designed

Setting `LUPOPEDIA_ENV=development` in the unit test bootstrap allowed unregistered-table
lookups to return the `not_ladder` fallback instead of throwing. This matches the spec
exactly and is the right behavior for iterative development.

### 4. The `isReservedSpace()` fix from the prior session was essential

Without the prior session's `strlen($idStr) < 18` fix, `validatePkForTable()` for
`full` and `generator_staging` tables would have incorrectly rejected all 18-digit
ladder PKs as seed-space violations. The fix was prerequisite infrastructure.

### 5. Sync script Markdown parsing limitation

The regex-based Markdown parser for `TRUST_LADDER_REGISTRY.md` is fragile against
format changes. Specifically:
- It relies on backtick-quoted table names starting with `lupo_`
- It strips `**bold**` wrappers from participates/archetype cells
- It does not handle multi-line notes columns

**Recommendation:** Add a structured YAML front-matter block to `TRUST_LADDER_REGISTRY.md`
as a machine-readable companion to the human-readable table. This would allow the sync
script to use a proper YAML parser instead of regex on Markdown.

This is a `TODO(LEGACY-GAP)` but not blocking for 4.0.96.

### 6. `canonical_lineage_edge` is heuristically derived

The sync script heuristically assigns `canonical_lineage_edge = 'canonical_instance_of'`
only to `full` + `parent` tables. Other tables get `null`. This may miss cases where a
`generator_staging` table also uses a lineage edge on promotion.

The authoritative value for each table's lineage edge should come from the doctrine
Markdown, but the current table format doesn't have a column for it. This is another
argument for adding a YAML front-matter companion to the registry.

**Filed as:** `TODO(LEGACY-GAP)` in `sync_trust_ladder_registry_to_db.py`.

### 7. `lupo_actor_memory` deprecation captured

The deprecated `lupo_actor_memory` table is registered with `is_active=0` and
`participates='not_ladder'`. This records its historical participation without
giving it runtime authority. The `GarbageCollector.php` references to this table
(noted in PLAN.md Phase 7.5) should be verified against this registry entry.

### 8. `SELECT ... FOR UPDATE` gap (Q3 answer)

The collision-detection loop in `toCanonicalIdSafe()` remains a TOCTOU race.
The `FOR UPDATE` locking needed is documented in `LEGACY_GAP_ANSWERS.md` Q3 but
is not implemented in this session — it requires a `DatabaseFactory` API extension
for cross-database portable locking. This is the highest-priority open issue.

---

## Improvement Suggestions

1. **Add YAML front-matter to `TRUST_LADDER_REGISTRY.md`** — machine-readable companion
   that the sync script can parse reliably without Markdown regex.

2. **Add `last_validated_ymdhis` column to the registry table** — enables `doctor`
   to alert on stale registry (>24h since last sync). Low-risk additive migration.

3. **Add `registry_version` to `lupo_system_config`** — shared invalidation signal
   for FPM worker cache invalidation without process restart.

4. **`quoteForUpdate()` method in `DatabaseFactory`** — enables portable
   `SELECT ... FOR UPDATE` across MySQL/PostgreSQL/SQLite/MariaDB.

5. **`php bin/lupo.php doctor` registry check** — validate that all tables
   in the install SQL appear in the registry. Currently absent from `doctor`.

6. **Admin UI registry diff panel** — add to `AdminTrustLadderHandler`, read-only,
   Root-department only, shows doctrine vs DB row comparison.

---

## Open Issues Created This Session

All issues from `LEGACY_GAP_ANSWERS.md §Summary` should be filed in the project
issue tracker. Priority order:

1. `SELECT ... FOR UPDATE` in `toCanonicalIdSafe()` — **HIGH**
2. StagingGcService: exclude lineage edges from GC — **HIGH**
3. Cache invalidation for long-running workers — **MEDIUM**
4. YAML front-matter for registry Markdown — **MEDIUM**
5. `quoteForUpdate()` in DatabaseFactory — **MEDIUM**
6. 32-bit PHP hard-ban in 4.1.0 — **LOW** (carry to 4.1.0 gate)

---

**Forward action:** Run `php bin/lupo.php doctor` against a seeded DB to confirm
registry integration. Run `python scripts/sync_trust_ladder_registry_to_db.py --dry-run`
against a fresh install to verify the SQL seed rows match the doctrine Markdown.
