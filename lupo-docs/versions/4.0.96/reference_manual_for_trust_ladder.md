---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260408113124"
  file_path_from_root: "lupo-docs/versions/4.0.96/reference_manual_for_trust_ladder.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.96/reference_manual_for_trust_ladder.md"
  last_modified_utc: "20260408113124"
  federation_node_id: 0
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: manual
  artifact_kind: reference
  purpose: "In-depth reference manual for trust ladder architecture, operations, and known edge cases in 4.0.96"
  tags:
    - trust_ladder
    - reference_manual
    - 4.0.96
    - doctrine
    - operations
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md"
      type: references
      weight: 1.0
      reason: "Canonical trust ladder doctrine"
    - to: "lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md"
      type: references
      weight: 1.0
      reason: "Table participation and archetype registry"
    - to: "lupo-docs/prd/38_memory_unification.md"
      type: references
      weight: 1.0
      reason: "Memory graph parent-child model"
    - to: "lupo-docs/prd/41_install_seed_doctrine.md"
      type: references
      weight: 1.0
      reason: "Seed doctrine and seed-canonical lineage"
    - to: "lupo-docs/prd/19_garbage_collection_system.md"
      type: references
      weight: 1.0
      reason: "Batch and GC guardrails"
lupopedia.footer:
  last_verified: "20260408113124"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
  orchestrator: "cursor:root"
---

# Reference Manual For Trust Ladder (4.0.96)

## 1) Purpose and Scope

This manual is the practical, in-depth operator/developer guide for the trust ladder system in `4.0.96`.  
It explains how seed, canonical, and staging IDs work together, how lineage edges are used, and what failure modes are known.

This manual is not the constitutional source of truth. Canonical doctrine remains:

- `lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md`
- `lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md`
- `lupo-docs/prd/38_memory_unification.md`
- `lupo-docs/prd/41_install_seed_doctrine.md`
- `lupo-docs/prd/19_garbage_collection_system.md`

---

## 2) Core Model

### 2.1 Tier model

| Tier | ID shape | Meaning |
|------|----------|---------|
| Seed | `0-999,999` | Immutable seed/reference space |
| Canonical | 18-digit, year band `1000-1999` | Living trusted row |
| Staging | 18-digit, year band `2000-2099` | Draft/incoming row |

### 2.2 Why the seed range is fixed

The seed band is locked to `0-999,999` to avoid overlap with 18-digit trust-ladder IDs.  
Any broader numeric rule (for example `< 1 quintillion`) is too permissive and can misclassify valid ladder IDs.

### 2.3 Parent vs Child archetypes

- **Parent**: Requires seed anchor + active canonical lineage edge (`canonical_instance_of`).
- **Child**: Skips seed tier; staging promotes directly to canonical.
- **System**: Registry/config style data with seed-only semantics.
- System tables are seed-only and never participate in canonical/staging promotion.

---

## 3) ID Lifecycle

### 3.1 Canonical path (parent archetype)

1. Seed exists (`0-999,999`) and is immutable.
2. Active canonical exists (18-digit, `1xxx` year band).
3. Seed points to canonical with `canonical_instance_of` and `active_until = 0`.
4. Staging rows consolidate into canonical (`consolidated_into`), then staging is soft-deleted.

### 3.2 Canonical rollover (parent)

When canonical must be replaced:

1. Close old seed->canonical edge (`active_until = now` packed UTC).
2. Create new canonical.
3. Create new active `canonical_instance_of` edge with `active_until = 0`.

### 3.3 Child path

1. New row starts staging (`IdGenerator::generate()`).
2. Promote via `toCanonicalIdSafe()`.
3. Link with `promoted_to`.
4. Soft-delete staging as policy requires.

---

## 4) Validation and Enforcement Surfaces

### 4.1 Runtime validation

Runtime checks should be layered:

1. `validateTableArchetypeAndId($table, $id)` for archetype-policy enforcement
2. `IdGenerator::validateTrustLadderPk($id, $table)` for shape and tier validation

`IdGenerator::validateTrustLadderPk()` enforces:

- seed check (`0-999,999`) + registry-gated validity,
- or strict 18-digit canonical/staging shape checks.

`IdGenerator::isReservedSpace()` is seed-space detection only (`0-999,999`).

### 4.2 Registry validation

- `python lupo-scripts/validate_trust_ladder_registry.py`
  - validates registry table references against install SQL
  - validates required seed-range section markers

- `python lupo-scripts/validate_seed_registry.py`
  - validates registered seed IDs align to the locked seed band

### 4.3 Edge integrity validation

- `python lupo-scripts/audit_edge_integrity.py`
  - checks dangling references
  - checks forbidden canonical->staging direction for non-consolidation edges
  - applies trust-band logic for edge endpoints

### 4.4 Explicit archetype declaration is mandatory

Archetype is a single-source constitutional field, not an inferred property.

Every ladder table must declare exactly one archetype in:

1. `TRUST_LADDER_REGISTRY.md`
2. runtime registry/config cache

Allowed values:

- `parent`
- `child`
- `system`

Runtime access pattern:

```php
$tableArchetype = TrustLadderRegistry::getArchetype('lupo_memory_nodes');
```

Never derive archetype from:

- table name,
- id shape,
- heuristic assumptions.

### 4.5 Defensive entry-point checks

All write/promotion flows should validate table archetype and id together:

```php
public function validateTableArchetypeAndId($table, $id)
{
    $archetype = TrustLadderRegistry::getArchetype($table);

    if ($archetype === 'parent') {
        if (!IdGenerator::isReservedSpace($id)) {
            throw new TrustLadderException("Parent requires seed anchor id");
        }
        if (!SeedRegistry::isValidSeed($id, $table)) {
            throw new TrustLadderException("Seed not registered for parent table");
        }
    } elseif ($archetype === 'child') {
        if (IdGenerator::isReservedSpace($id)) {
            throw new TrustLadderException("Child table cannot receive seed ids");
        }
        IdGenerator::validateTrustLadderPk($id, $table);
    }
}
```

Minimum call surfaces:

- every INSERT path,
- pre-promotion (`toCanonicalIdSafe`) path,
- batch ingest pre-flight,
- edge writer path for lineage edges.

---

## 5) Query Priority Rules

For "best current" retrieval:

1. Canonical (`1000-1999`) first
2. Staging (`2000-2099`) second
3. Seed (`0-999,999`) lowest

Seed IDs must still be registry-valid. Numeric range alone does not authorize ladder participation.

---

## 6) Operational Playbooks

### 6.1 Insert flow (safe default)

1. Generate staging ID with `IdGenerator::generate()`.
2. Validate with `validateTableArchetypeAndId($table, $id)` then `validateTrustLadderPk($id, $table)`.
3. Insert staging row.
4. Consolidate/promote with `toCanonicalIdSafe()` where policy applies.
5. Write lineage edge.
6. Soft-delete staging if consumed.

### 6.2 Batch ingest flow

For large imports on ladder tables:

- chunk `<= 5000` rows
- jittered random backoff between chunks
- avoid fixed delay lockstep
- monitor collision/retry logs

### 6.3 GC flow

- Staging rows are soft-deleted first
- retention window applies before purge
- large delete loops should use chunking + jitter to reduce synchronized lock contention

---

## 7) Known Edge Cases and Risks

### 7.1 Canonical collision suffix exhaustion

**Risk:** `toCanonicalIdSafe()` may exhaust suffix attempts under high contention.  
**Current behavior:** throws runtime exception after retry limit.  
**Recommended hardening:** add higher-level bounded retry with jitter and optional prefix-shift strategy.

### 7.2 Concurrent promotion race

**Risk:** two workers promote same staging concept simultaneously.  
**Symptoms:** duplicate promotion attempts, collision storms, inconsistent edge fan-out.  
**Mitigation:** transactional locking strategy, idempotency keys, or deterministic winner rules.

### 7.3 Seed orphan assumptions

Seed rows may appear graph-orphaned in runtime traversal and still be correct (reference anchors).  
Do not auto-delete or auto-repair seed rows solely because they look disconnected.

### 7.4 Parent/child misclassification

- Parent as child -> lineage anchor missing.
- Child as parent -> unnecessary seed overhead and growth costs.
- See `§14 Archetype Decision Checklist` before classifying new tables.

Reclassification must be explicit via PRD amendment + review.

### 7.5 Federation conflict surface

Potential conflict areas in multi-node merges:

- same seed ID used with divergent semantics across nodes,
- conflicting canonical lineage for same seed anchor,
- edge graph divergence after independent promotion.

This area needs explicit federation conflict policy per deployment phase.

### 7.6 32-bit runtime safety

Risk remains where code casts 18-digit IDs to PHP int.  
Use string-first handling for full IDs; only cast small slices (for example 4-digit year) after extraction.

### 7.7 Archetype drift between docs and runtime

If doctrine registry and runtime cache disagree on table archetype, the same row may be treated as parent in one path and child in another.  
This is one of the fastest ways to create unrecoverable lineage corruption, including bad edge writes and inconsistent query tier behavior.

### 7.8 Reclassification hazards (`child <-> parent`)

Changing archetype can orphan rows/edges or explode edge fan-out if done without migration simulation.  
Always run reclassification impact analysis before changing archetype metadata.

---

## 8) Honolulu Memory-Gap Checklist (Implementation Discipline)

When implementing trust-ladder changes, assume historical memory may omit edge cases.

Required mindset:

1. Read doctrine section first.
2. Identify likely blind spots.
3. Mark unresolved uncertainty as `TODO(HONOLULU-GAP): ...`.
4. Prefer defensive patterns for high-risk paths.
5. Reject "too simple" assumptions without test evidence.

High-priority review targets:

- batch backoff strategy under extreme volume,
- suffix exhaustion fallback behavior,
- promotion race locking strategy,
- seed/canonical reference rules in child tables,
- edge retention and pruning policy,
- federation overlap resolution.

Template for archetype changes:

```php
// TODO(HONOLULU-GAP): Parent/child archetype for {$table} chosen from current PRD.
// Confirm no historical child rows become orphaned seeds after change.
// Verify projected edge fan-out growth at 10M rows.
```

---

## 9) Minimum Test Matrix

### 9.1 PK validation tests

- valid seed boundaries: `0`, `999999`
- invalid seed overflow: `1000000`
- canonical/staging shape validation
- registry-authorized vs unauthorized seed behavior
- parent table given valid 18-digit staging ID -> rejected (seed anchor required)
- child table given valid seed ID -> rejected (seed IDs forbidden for child)

### 9.2 Promotion tests

- canonical collision with suffix bump
- retry-limit exhaustion path
- concurrent attempt simulation

### 9.3 Query precedence tests

- canonical present + staging present + seed present
- ensure canonical chosen first
- ensure unauthorized seed does not participate

### 9.4 GC/batch tests

- chunk enforcement (`<=5000`)
- random backoff path
- large-batch behavior stability

---

## 10) Troubleshooting Guide

### Symptom: Canonical rows rejected as seed

Check:

1. `IdGenerator::isReservedSpace()` implementation
2. seed range doctrine (`0-999,999`)
3. any stale helper using old `< 1 quintillion` logic

### Symptom: Seed row accepted but should not participate

Check:

1. registry authorization path for seed IDs
2. context passed to `validateTrustLadderPk()`
3. registry file drift vs install/doctrine

### Symptom: Promotion fails intermittently under load

Check:

1. collision retry logs
2. concurrent worker overlap
3. batch size and backoff strategy

### Symptom: Edge audit reports forbidden tier direction

Check:

1. edge type classification (consolidation vs truth edge)
2. endpoint band detection logic
3. stale/incorrect archetype assumptions

---

## 11) Implementation Guardrails (Do/Do Not)

### Do

- Keep seed IDs in `0-999,999`.
- Keep 18-digit IDs string-safe in PHP handling.
- Use explicit edge semantics for lineage.
- Validate with scripts before merge.
- Treat doctrine updates and runtime updates as a pair.

### Do not

- Expand seed range ad hoc.
- Rely on raw numeric threshold hacks for trust classification.
- bypass registry checks for seed participation.
- hard-delete lineage-critical rows.
- assume historical memory is complete without test confirmation.

---

## 12) Performance and Capacity Benefits (Projected)

This section summarizes realistic projections comparing trust-ladder indexing patterns against a traditional model (`AUTO_INCREMENT` plus secondary flags such as `is_canonical` and `created_ymdhis`-driven indexes).

### 12.1 Assumptions used for projection

| Variable | Value |
|----------|-------|
| Table | `lupo_memory_nodes` |
| Row count | 10,000,000 rows |
| Index type | B-tree (default MySQL/PostgreSQL) |
| Hardware class | 4 vCPU, 8 GB RAM, SSD |
| Baseline query | "latest canonical memory for actor/key" |

Footnote: projections assume mixed parent/child workload. Pure child-heavy tables can see higher gains because they avoid seed-anchor lineage overhead.

### 12.2 Query class: latest canonical by key

**Traditional model behavior (typical):**
- Requires broad composite secondary index (actor + key + canonical flag + created clock).
- Large secondary index footprint hurts cache locality.
- More index maintenance during writes.

**Trust-ladder model behavior (typical):**
- Leverages PK ordering and year-band semantics for priority.
- Lower dependency on large composite secondary indexes.
- Better page locality for chronological reads and consolidations.

**Projection at 10M rows:**

| Metric | Traditional pattern | Trust-ladder pattern | Relative gain |
|--------|----------------------|----------------------|---------------|
| Secondary index footprint | Multi-GB class | Sub-GB class or reused PK path | Large reduction |
| Average lookup latency | Higher | Lower | ~5x class improvement (workload-dependent) |
| Tail latency (P99) | Higher variance | Lower variance | ~8x class improvement (workload-dependent) |

### 12.3 Query class: staging retention scan / GC candidate fetch

**Traditional model:**
- Predicate based on `is_staging` + created clock index.
- Can fragment into non-contiguous scans under mixed data distributions.

**Trust-ladder model:**
- Year-band / PK-range scans are contiguous.
- Better sequential read behavior for purge candidate windows.

**Projection at 10M rows with 20% staging:**

| Metric | Traditional pattern | Trust-ladder pattern | Relative gain |
|--------|----------------------|----------------------|---------------|
| Scan locality | Mixed/random | More contiguous | Better |
| Average scan time | Higher | Lower | ~4-5x class improvement |
| Batch delete throughput | Lower | Higher | up to ~10x in favorable conditions |

### 12.4 Query class: month export and mirror generation

**Traditional model:**
- Often needs dedicated created-time secondary index.
- Extra write amplification from maintaining additional indexes.

**Trust-ladder model:**
- Time slicing can use PK/year-band ranges directly in many flows.
- Fewer dedicated secondary indexes required for common export windows.

**Projection:**

| Metric | Traditional pattern | Trust-ladder pattern | Relative gain |
|--------|----------------------|----------------------|---------------|
| Extra index requirement | Usually yes | Often no | Reduced |
| Cold-cache query time | Higher | Lower | ~2-5x class improvement |
| Write amplification | Higher | Lower | ~3x class write efficiency |

### 12.5 Insert/write throughput impact

Core principle: each extra secondary index amplifies write cost.  
Trust-ladder design keeps hot-path semantics encoded in PK banding and edges, reducing index fan-out.

| Metric | Traditional (multi-index) | Ladder-oriented (leaner indexing) | Relative gain |
|--------|---------------------------|-----------------------------------|---------------|
| B-tree updates per insert | More | Fewer | ~3-4x fewer updates |
| Single-thread insert rate | Lower | Higher | ~3-4x class gain |
| Contended multi-thread rate | Lower | Higher | ~3x class gain |

### 12.6 Storage projection (10M rows, indicative)

| Component | Traditional | Ladder-oriented | Benefit |
|-----------|-------------|-----------------|---------|
| Data payload | Similar | Similar | Neutral |
| PK index | Similar | Similar | Neutral |
| Secondary indexes | Significantly larger | Significantly smaller | Major savings |
| Total footprint | Higher | Lower | ~50-60% class reduction in indexed footprint |

### 12.7 Scale implication (100M row horizon)

At larger volumes, index footprint and cache hit rate dominate cost/performance:

- Traditional multi-index strategy may require much higher RAM to sustain acceptable hit rates.
- Trust-ladder strategy keeps active index surface smaller, enabling viable operation on smaller VPS tiers.

Practical effect: trust-ladder is not only faster at scale, it can be the difference between "operational on commodity hardware" and "requires enterprise-tier RAM/IO".

### 12.8 Important caveats

These are projected classes, not guaranteed constants. Real outcomes depend on:

- distribution of `memory_key` cardinality,
- ratio of parent vs child workloads,
- edge-table growth behavior,
- buffer pool sizing,
- concurrent writer count,
- query plan quality and schema/index drift.

Treat these numbers as planning guidance; validate in staging with representative production-like data.

## 13) Quick Command Reference

```bash
python lupo-scripts/validate_trust_ladder_registry.py
python lupo-scripts/validate_seed_registry.py
python lupo-scripts/audit_edge_integrity.py
python lupo-scripts/validate_parent_child_consistency.py
python lupo-scripts/validate_parent_child_reclassification.py --table=<table> --new-archetype=<parent|child|system>
```

---

## 14) Archetype Decision Checklist (Before New Tables)

1. Does the entity need immutable long-lived reference semantics? -> `parent`
2. Is the entity mostly transient/high-volume and consolidates cleanly? -> `child`
3. Is it registry/config style data? -> `system`
4. Has `TRUST_LADDER_REGISTRY.md` been updated?
5. Has runtime archetype metadata been updated?
6. Were insert/promotion/edge paths updated with archetype checks?
7. Will validators catch misclassification in CI?

## 15) Practical Definition of "Done"

A trust-ladder change is operationally complete only when:

1. Doctrine and PRD text are aligned.
2. Runtime logic matches doctrine.
3. Registry/archetype metadata is updated.
4. Validators pass.
5. Edge-case notes are captured for unresolved risk.
6. Changelog/version notes are updated.

This output complies with Lupopedia Constitutional Root Rules.
