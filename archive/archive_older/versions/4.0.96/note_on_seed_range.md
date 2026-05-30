---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260408114106"
  file_path_from_root: "docs/versions/4.0.96/note_on_seed_range.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.96/note_on_seed_range.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: note
  artifact_kind: version_note
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
# Note To Claude — Seed Range + Legacy Gap Checklist

This is a handoff note for Claude regarding the trust-ladder updates in `4.0.96`.

## Seed Range Decision

The seed/reserved band is now **strictly `0-999,999` (inclusive)** and is treated as a constitutional lock for the current doctrine line.

## What changed in this amendment pass

- `CHRONOLOGICAL_TRUST_LADDER.md` now defines seed space as `0 <= id <= 999999`.
- Query priority in CTL now treats only `BETWEEN 0 AND 999999` as seed tier.
- `IdGenerator::isReservedSpace()` now returns true only for numeric IDs in `0-999,999`.
- `IdGenerator::validateTrustLadderPk()` now enforces registry-backed validation for this reduced seed band.
- `TRUST_LADDER_REGISTRY.md` now uses `0-999,999` in the seed range section and includes `entity_archetype` classification.
- Validators and related tooling were aligned to the same range.
- `PRD 38` now includes parent-child trust-ladder distinctions and seed/canonical/staging edge patterns.
- `PRD 41` now includes seed-to-canonical edge requirements with `active_until` lifecycle semantics.
- `PRD 19` now includes batch import guardrails from the original/legacy deployment (chunking + random backoff).
- `PRD 00` now includes constitutional parent-child classification guidance.
- CTL now includes Appendix B with lessons summary from the original/legacy deployment.

## Why this was done

The prior broad threshold (`< 1 quintillion`) created overlap/ambiguity with ladder-shaped IDs and made operational classification too permissive for seed participation.

The reduced range keeps seed IDs explicit and bounded while preserving a clean separation from 18-digit canonical/staging tiers.

## Operational rule

If a value is outside `0-999,999`, it is **not** seed-space and must satisfy the canonical/staging 18-digit trust-ladder validation path.

---

## Context to keep in mind (what to look for)

The system memory source for original/legacy deployment behavior is intentionally incomplete. The high-level pattern is trusted, but edge cases may be missing.

### Operator truth statement

The implementation context includes this explicit reality:

> "I'm working from 20-year-old memory."

Treat this as a directive to actively search for missing failure modes and over-simplified assumptions.

### Prompt to use for Claude Code / IDE

Use this as the working instruction during trust-ladder implementation reviews:

1. Read the CTL doctrine section being implemented.
2. Ask: "What can break here that the original/legacy deployment likely encountered but we have not modeled yet?"
3. If a gap is found, add:
   - `TODO(LEGACY-GAP): <description>`
4. For high-risk areas, prefer defensive implementation:
   - bounded retries,
   - randomized/exponential backoff where appropriate,
   - explicit race-condition handling,
   - conservative failure behavior.
5. Flag assumptions that seem "too simple."

### High-risk areas to review every time

1. Batch import backoff behavior at very large row counts.
2. Parent/child reclassification over time.
3. Seed ID collision behavior in federated scenarios.
4. Canonical promotion collision exhaustion (`toCanonicalIdSafe` limits).
5. 32-bit runtime safety boundaries.
6. Parent/child reference strategy (seed vs canonical references).
7. Archive/restore semantics and id reuse policy.
8. Concurrent staging promotion race conditions.
9. Edge table growth and pruning policy.
10. Cross-federation ID overlap handling.
11. Registry bootstrap and self-referential metadata handling.

### Questions Claude should answer while implementing

1. Should batch backoff remain random jitter only, or include exponential escalation?
2. What is the fallback when all 10,000 suffixes are exhausted?
3. Where is locking needed for concurrent promotion (`SELECT ... FOR UPDATE` or equivalent)?
4. Which references should target seed IDs vs canonical IDs?
5. Is 32-bit runtime support still required, or should production hard-ban it?
6. How long should `consolidated_into` edges remain after staging deletion?

### Baseline assumptions considered reliable

- Three-tier trust ladder model (seed, canonical, staging).
- 18-digit timestamp-shaped IDs with year-band semantics.
- Collision-aware canonical promotion flow.
- Randomized backoff as a valid anti-contention pattern.
- Seed immutability in the `0-999,999` band.

Everything else should be treated as potentially incomplete until verified in doctrine/code/tests.

---

## Suggestion for review: MySQL-backed trust ladder registry

This is a suggested implementation path (for Claude review) so PHP/web UI can resolve archetypes and table rules at runtime without parsing Markdown in hot paths.

**TL;DR for Claude:** Build `lupo_trust_ladder_registry` as a system table -> implement `TrustLadderRegistry` with aggressive fail-closed behavior -> enforce archetype on every write path -> keep Markdown as doctrine source.

### Primary instruction for this sprint

Implement runtime `TrustLadderRegistry` plus archetype enforcement exactly as described.  
Treat every insert, promotion, and edge write as an archetype-violation surface.  
Fail closed aggressively and add `TODO(LEGACY-GAP)` notes liberally.

### Why this helps

- Web handlers (for example admin trust-ladder views, validation preflight, ingest/promotion paths) need deterministic runtime metadata.
- A table-backed registry avoids repeated Markdown parsing and reduces drift risk between docs and runtime behavior.
- It enables strict runtime checks: archetype, seed requirements, lineage edge type, promotion target.
- It reinforces portability discipline by keeping behavior metadata central and database-agnostic in PHP paths.

### Key recommendations and improvements

### 1) Make the registry table itself trust-ladder aware (bootstrapping safety)

Suggested table name: `lupo_trust_ladder_registry`

Recommended archetype for this table: `system` (seed-only).

Suggested table spec:

```sql
CREATE TABLE lupo_trust_ladder_registry (
    registry_id     BIGINT PRIMARY KEY,
    table_name      VARCHAR(128) NOT NULL UNIQUE,
    archetype       ENUM('parent','child','system') NOT NULL,
    participates    ENUM('full','generator_staging','seed_only','not_ladder') NOT NULL DEFAULT 'full',
    seed_required   TINYINT(1) NOT NULL DEFAULT 0,
    canonical_lineage_edge VARCHAR(64) DEFAULT NULL,
    promotion_target VARCHAR(32) DEFAULT NULL,
    is_active       TINYINT(1) NOT NULL DEFAULT 1,
    created_ymdhis  BIGINT NOT NULL,
    updated_ymdhis  BIGINT NOT NULL,
    is_deleted      TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis  BIGINT DEFAULT 0,
    canonical_id    BIGINT NULL,
    seed_range_override VARCHAR(32) NULL,
    reference_target ENUM('seed','canonical') DEFAULT 'canonical',
    notes TEXT NULL,
    INDEX idx_table_name (table_name),
    INDEX idx_archetype (archetype)
);
```

Why:

- The registry must not be a "special bypass". It should follow trust-ladder discipline too.
- Bootstrapping becomes explicit and auditable.
- Future policy exceptions remain encoded metadata, not hidden logic.

### 2) Add critical defensive fields

- `seed_range_override` (`VARCHAR(32) NULL`) for rare per-table override (normally NULL)
- `reference_target` (`ENUM('seed','canonical') DEFAULT 'canonical'`) to answer reference-target policy explicitly
- `notes` (`TEXT NULL`) for `LEGACY-GAP` and reviewer rationale

### Bootstrap Sequence for Registry Table

The registry table bootstraps itself in three phases during install:

**Phase 1:** Create table (DDL only, no registry lookup).

**Phase 2:** Insert seed row for `lupo_trust_ladder_registry` itself.

```sql
INSERT INTO lupo_trust_ladder_registry (
    registry_id, table_name, archetype, participates,
    seed_required, canonical_lineage_edge, promotion_target,
    is_active, created_ymdhis, updated_ymdhis,
    canonical_id, reference_target, notes
) VALUES (
    1,
    'lupo_trust_ladder_registry',
    'system',
    'seed_only',
    1,
    NULL,
    NULL,
    1,
    20260408113443,
    20260408113443,
    NULL,
    'seed',
    'Bootstrap: registry table follows its own rules. Seed-only archetype.'
);
-- Use timestamp_ymdhis::now() at generation time
```

**Phase 3:** Load runtime registry cache after this seed row exists.

During very early bootstrap (before any cache exists), `TrustLadderRegistry` should have a `getSelfMetadata()` static fallback that hard-codes the registry table's own metadata.

Note to Claude: values like `20260408113443` are packed UTC integers from `timestamp_ymdhis::now()`, not Unix epoch timestamps. Never use `UNIX_TIMESTAMP()` in Lupopedia persistence paths.

### 3) Improved PHP access layer (`TrustLadderRegistry`)

```php
final class TrustLadderRegistry
{
    private static $cache = null;

    private static function loadCacheIfNeeded()
    {
        if (self::$cache !== null) {
            return;
        }

        // TODO(LEGACY-GAP): Implement cache population from lupo_trust_ladder_registry.
        // In development: missing tables => E_USER_WARNING + permissive fallback.
        // In production: missing tables => throw TrustLadderException (fail closed).
    }

    public static function clearCache()
    {
        self::$cache = null;
    }

    public static function getTableMeta($tableName)
    {
        self::loadCacheIfNeeded();

        if (!isset(self::$cache[$tableName])) {
            throw new TrustLadderException(
                "Table {$tableName} is not registered in trust ladder. " .
                "Add it to TRUST_LADDER_REGISTRY.md and run sync script."
            );
        }

        $meta = self::$cache[$tableName];

        if (!$meta['is_active'] || $meta['is_deleted']) {
            throw new TrustLadderException("Trust ladder metadata for {$tableName} is deactivated");
        }

        return $meta;
    }

    public static function isParent($table)
    {
        return self::getTableMeta($table)['archetype'] === 'parent';
    }

    public static function isChild($table)
    {
        return self::getTableMeta($table)['archetype'] === 'child';
    }

    public static function requiresSeed($table)
    {
        return (bool) self::getTableMeta($table)['seed_required'];
    }

    public static function getReferenceTarget($table)
    {
        return self::getTableMeta($table)['reference_target']; // 'seed' | 'canonical'
    }
}
```

### 4) Sync script hardening (critical)

Script location: `scripts/sync_trust_ladder_registry_to_db.py`

Run this during deployment after any change to `TRUST_LADDER_REGISTRY.md`.

`sync_trust_ladder_registry_to_db.py` should:

1. parse Markdown registry (doctrine source),
2. validate archetype and flag consistency,
3. fail on doctrine-vs-DB drift with explicit diff,
4. support `--dry-run` and `--force`,
5. emit `TODO(LEGACY-GAP)` warnings where existing data may be impacted.

Required invariant:

```python
if table_meta['archetype'] == 'parent' and not table_meta['seed_required']:
    raise ValidationError("Parent tables must have seed_required = true")
if table_meta['archetype'] == 'child' and table_meta['seed_required']:
    raise ValidationError("Child tables must have seed_required = false")
if table_meta['archetype'] == 'parent' and table_meta['reference_target'] != 'seed':
    raise ValidationError("Parent tables must use reference_target = 'seed'")
if table_meta['archetype'] == 'child' and table_meta['reference_target'] != 'canonical':
    raise ValidationError("Child tables must use reference_target = 'canonical'")
if table_meta['archetype'] == 'system' and table_meta['seed_required'] == 0:
    raise ValidationError("System tables must have seed_required = true")
```

### 5) Additional `LEGACY-GAP` questions for Claude

Add these to the implementation checklist:

7. How should registry cache invalidation work after sync for long-running workers/daemons?
8. What is the migration path for tables that already exist but are not yet registered?
9. Should we add `last_validated_ymdhis` and alert if registry is stale beyond 24 hours?
10. How should admin UI expose registry diff safely without leaking sensitive doctrine details?
11. How do we safely bootstrap the registry table itself on fresh installs (seed-row chicken-and-egg)?
12. How should `TrustLadderRegistry` behave during unit tests (especially SQLite)?

### 6) Tightened rollout sequence

1. Add `lupo_trust_ladder_registry` table + seed rows for known ladder tables.
2. Implement `TrustLadderRegistry` PHP class + cache.
3. Run `sync_trust_ladder_registry_to_db.py --strict --dry-run`.
4. Resolve all drift.
5. Add runtime checks across hot paths (IdGenerator validation context, inserts, promotion, edge writers).
6. Gate CI on registry DB parity checks.
7. Add admin diagnostics view with doctrine-vs-DB row diff.

### Critical Rule (copy-paste ready)

#### Archetype Enforcement Principle

Never derive archetype, seed requirement, or lineage rules from table name, ID shape, or developer intuition.

All decisions must flow through:

`TrustLadderRegistry::getTableMeta($table)`

Any code path that bypasses this metadata lookup (or falls back to legacy numeric-only assumptions) should be rejected in review.

`TODO(LEGACY-GAP): Verify every existing ladder table has been classified, and classification matches historical usage patterns. Pay special attention to tables that may mix parent-style and child-style rows.`

### Development Mode Exception

In `LUPOPEDIA_ENV=development`, missing registry entries should raise `E_USER_WARNING` but not throw hard exceptions, allowing iterative table development.

In `LUPOPEDIA_ENV=production`, missing registry entries must fail closed with exceptions.

---

## Critical Context: DatabaseFactory and Cross-Database Portability

### Why the Ladder System Exists

The PK ID ladder system is not only a historical pattern. It satisfies a current practical requirement:

**Lupopedia must run on multiple databases without business-logic rewrites.**

Supported targets:

- MySQL 8.0+
- PostgreSQL 15+
- SQLite 3.35+ (testing)
- MariaDB 10.6+

### The Portability Problem with Database-Generated IDs

Traditional identity columns are database-specific:

| Database | Syntax | Last ID retrieval |
|----------|--------|-------------------|
| MySQL | `AUTO_INCREMENT` | `LAST_INSERT_ID()` |
| PostgreSQL | `SERIAL` / `IDENTITY` | `RETURNING` / `lastval()` |
| SQLite | `AUTOINCREMENT` | `last_insert_rowid()` |

This forces `DatabaseFactory` and write paths to:

1. detect database type per insert path,
2. vary insert SQL by database,
3. vary last-ID retrieval by database,
4. maintain more conditionals and test permutations.

### The Ladder Portability Solution

```php
// Same strategy on every supported database.
$id = IdGenerator::generate(); // e.g. 202604081234567890
$db->insert('table', array('id' => $id, 'column' => $value));

// ID known before insert. No database-specific "last ID" branch needed.
processId($id);
```

Benefits:

- DatabaseFactory remains simpler (less per-database identity branching)
- No `RETURNING` vs `LAST_INSERT_ID()` divergence in app logic
- Batch insert paths can pre-generate IDs in PHP
- SQLite test environments follow same ID semantics as MySQL/PostgreSQL paths
- Database migration/copy workflows avoid sequence reset complexity

### Trade-Off Summary

| Aspect | Ladder System | DB-generated identity |
|--------|----------------|-----------------------|
| Cross-database insert complexity | Lower | Higher |
| ID generation location | Application (PHP) | Database |
| Portability | Strong | Abstraction-heavy |
| 32-bit safety concerns | Needs string discipline | Easier for small ints |
| Convention familiarity | Unusual | Common |

### Decision Rationale

The ladder model is primarily a portability decision. Performance is a major secondary advantage.

For the 4.x line, changing this direction requires constitutional amendment plus explicit validation that replacement abstraction does not regress reliability.

### Related Code

- `includes/classes/DatabaseFactory.php`
- `includes/classes/IdGenerator.php`
- `docs/prd/00_root_constitutional_system_requirements.md`

### Escape Hatch (Future Branch)

If a future branch needs a parallel sequential surrogate key, use additive migration (not replacement) to keep ladder compatibility:

```sql
ALTER TABLE lupo_memory_nodes ADD COLUMN sequential_id BIGINT UNIQUE;
```

This supports phased transition experiments while preserving existing ladder identity continuity.

This output complies with Lupopedia Constitutional Root Rules.
