---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-docs/prd/79_A_INSTALL_SEED_DOCTRINE.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/79_A_INSTALL_SEED_DOCTRINE.md"
  status: "active"
  when_updated: "20260422232349"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/79_install_seed_doctrine.toon"
  atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/development/prd_files/install-seed-doctrine"
  artifact_type: "prd"
  artifact_kind: "specification"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "prd"
  prd_cluster: 00_A_79_A
  title: "PRD 79: Install Seed Doctrine (The Fourth Tier)"
  summary: "Four-tier PK doctrine for install seeds vs IdGenerator-shaped ids. Includes canonical Auth User ID Space partition (Section 13) as constitutional constant."
---

## 1. The consolidation orphan problem

When consolidating staging rows (2000-range) into canonical rows (1000-range), all parent
references were updated to point to the new canonical rows.

But **install-seed rows (0--999,999) became orphaned** because:

- They were originally parents
- Their children were updated to point to canonical rows
- Install seeds were never meant to be deleted
- Install seeds were never meant to be canonical
- Install seeds were never meant to be staging
- They are **reference truth**, not **active truth**

**Result:** Orphaned install seeds that still contain valuable original data, but no longer
participate in the active graph.

**This is correct behavior** -- but it must be documented as intentional, not a bug.

---

## 2. PK tiers: install seeds vs IdGenerator-shaped ids
Lupopedia uses **more than one PK shape**. **Do not** collapse them into a single numeric
band.

### 2.1 Install seed tier (low BIGINT, not from IdGenerator)

| Tier | How allocated | Example | Mutability | Role |
|------|---------------|---------|------------|------|
| **Install seed** | Fixed in `install_new_lupopedia.sql` / `seed_*.sql`; registry-backed | `actor_id = 1` (WOLFIE), `19`, `42` | Immutable | Reference truth -- baseline, audit, reversion source; not the living runtime row |

**Band (policy):** `0--999,999` for "low install" ids unless a table's registry explicitly
documents a different reserved band (PRD 00 s.3.2.1).

### 2.2 Timestamp-shaped ids (IdGenerator -- 18 digits)

For any table whose runtime PK is `IdGenerator::generate()`
(`lupo-includes/classes/IdGenerator.php` -- 18 digits, embedded calendar year 2000--2099):

| Tier | Embedded year (first 4 digits) | How obtained | Example | Mutability |
|------|-------------------------------|--------------|---------|------------|
| **Staging-shaped** | 2000--2099 | Raw `IdGenerator::generate()` | `202604081200001234` | Disposable / merge source |
| **Living canonical** | 1000--1999 | `toCanonicalId(IdGenerator::generate())` (subtract 1000 from year when >= 2000) | `102604081200001234` | Mutable (UPDATE living truth) |
| **Extended canonical** | 1000--1999 | Same transform +/- product-defined offset / allocator (still chronological PK band) | e.g. `1500000000000000000` (illustrative) | Mutable |

**Binding rule (same as PRD 38 s.4.2.1 / PRD 00 s.3.7):**

> `IdGenerator::generate()` always returns a staging-shaped id. To create a new living
> canonical row, use `toCanonicalId(IdGenerator::generate())` before INSERT unless policy
> deliberately persists a draft staging row.

```php
function toCanonicalId($stagingId) {
    $idStr = (string) $stagingId;
    if (strlen($idStr) < 4) {
        return $stagingId;
    }
    $year = (int) substr($idStr, 0, 4);
    if ($year >= 2000) {
        $newYear = $year - 1000;
        return (int) ($newYear . substr($idStr, 4));
    }
    return (int) $stagingId;
}
```

### 2.3 Low install seed -> living canonical actor_id (seedActorToCanonicalId)

**Not toCanonicalId:** `toCanonicalId()` applies to 18-digit staging-shaped ids (s.2.2).
For a low registry seed (e.g. `116`), `strlen($idStr) < 4` returns the input unchanged --
you do not get a 1000--1999 band id. Do not overload `toCanonicalId()` for `lupo_actors`
install seeds.

**Rule (paired living row, policy band typically 1--2025):** When allocating a living
canonical `lupo_actors.actor_id` that must be deterministically paired with a fixed install
seed:

```php
function seedActorToCanonicalId($seedActorId) {
    return 100000000000000000 + (int) $seedActorId;
}
```

**Examples:** `1 -> 100000000000000001`, `19 -> 100000000000000019`,
`116 -> 100000000000000116`. The value is 18 digits; the first four are `1000`
(Chronological Trust Ladder living canonical band); the suffix preserves traceability
to the seed.

**Coexistence with s.2.2:** For `lupo_actors`, product may use
`toCanonicalId(IdGenerator::generate())` when policy wants a time-shaped canonical (s.3
walkthrough). Use `seedActorToCanonicalId($seed)` when the canonical id must be strictly
derivable from the seed (e.g. Claude Code 116). Both are living canonical shapes; pick one
rule per row at INSERT, document in registry/release notes.

**Why two notions of "canonical":** Install seeds are low fixed ids; living canonical rows
for runtime-created entities use 1000--1999 embedded year on 18-digit ids. They are
different columns / tables -- never assume one formula covers `lupo_actors.actor_id` and
`lupo_memory_nodes.memory_node_id` without checking the table's allocation rules.

---

## 3. The Wolfie lifecycle (corrected)

### Step 1 -- Fresh install (reference seed)

```
Install seed: actor_id = 1 (WOLFIE)
- Immutable baseline
- Not the living runtime graph parent
```

### Step 2 -- First runtime use (web / service)

**No persistent staging row** if policy creates canonical immediately:

```php
$stagingId   = IdGenerator::generate();           // 202604081200001234
$canonicalId = toCanonicalId($stagingId);          // 102604081200001234

copyActor(1, $canonicalId);                        // copy from install seed -> new row
addEdge(1, $canonicalId, 'canonical_instance_of'); // seed -> living canonical
```

**Alternate (deterministic seed pairing):** `$canonicalId = seedActorToCanonicalId(1)`
-> `100000000000000001` -- use when policy requires a canonical id strictly derived from
the seed without a fresh `IdGenerator` draw (s.2.3). `copyActor`, `addEdge`, and orphan
semantics match the `toCanonicalId(IdGenerator::generate())` path.

**Result:**

- `actor_id = 1` remains forever (reference).
- `actor_id = 102604081200001234` (example) is the living WOLFIE for sessions, edges,
  memory.
- No row is inserted for raw `$stagingId` when conversion happens before insert.

### Step 3 -- Corruption / "dodo birds"

The living canonical row may be damaged while `1` stays clean.

### Step 4 -- Revert living canonical from install seed

```php
copyInstallToCanonical(1, $canonicalId);          // copy fields from seed 1 -> canonical
addEdge($canonicalId, 1, 'reverted_to');          // audit: canonical acknowledged seed
```

**Edges (names are product-defined; register in lupo_edges conventions):**
`canonical_instance_of` (install -> canonical), `reverted_to` (canonical -> install for
audit).

### Orphaned install seed

After the graph points at living canonical ids, install seed `1` may have no incoming
runtime parents -- expected per s.1. It remains for reversion and audit, not for active
edges as parent (Rule 3).

---

## 4. Install Seed Characteristics

| Property | Value |
|----------|-------|
| **Role** | Reference truth, baseline, template, prototype |
| **Origin** | `install_new_lupopedia.sql` or `seed_*.sql` |
| **Examples** | WOLFIE (1), LILITH (2), ANUBIS (19), Department 0 (0), Channel 42 (42) |
| **Can be deleted?** | No |
| **Can be modified?** | No (except by system upgrade) |
| **Can be parent?** | No -- never used as active parent in runtime graph |
| **Can be input to consolidation?** | Yes -- as baseline reference |
| **Should be referenced by runtime parents?** | No -- parents must point to canonical rows |
| **Can be orphaned?** | Yes -- this is expected and correct |
| **Can be used for reversion?** | Yes -- restore canonical from install baseline |
| **Part of living graph?** | No |

---

## 5. Seed-to-Canonical Edge Requirements

### 5.1 When Seeds Need Canonical Links

A seed row (ID `0-999,999`) that represents a **parent entity** (see PRD 38 s.9) MUST have
an outgoing `canonical_instance_of` edge pointing to its active living canonical row.

**Seed-only entities** (system config, registry entries) do not require this edge.

### 5.2 Edge Schema for Seed-to-Canonical

Required edge fields (on `lupo_memory_edges` or the entity-specific edge table):

- `edge_type` (`VARCHAR(64) NOT NULL`)
- `active_until` (`BIGINT NOT NULL DEFAULT 0`)

**active_until semantics:**
- `0` = currently active edge
- Non-zero packed UTC = edge was closed at that time (canonical superseded)

### 5.3 Seed Immutability with Canonical Indirection

**Rule:** Application code MUST NEVER directly update a seed row's business data.

**Correct pattern:**
1. Seed row exists (immutable anchor, ID `< 1,000,000`)
2. Active canonical row exists (mutable, ID starts with embedded year `1xxx`)
3. Updates go to canonical row
4. Seed -> canonical edge tracks lineage

**When canonical becomes stale:**
1. Archive old canonical (soft-delete or mark inactive)
2. Create new canonical from best staging data
3. Insert new `canonical_instance_of` edge (seed -> new canonical)
4. Close old edge (`active_until = now()`)

### 5.4 Validation Rule

In `IdGenerator::validateTrustLadderPk()`:

```php
// For seed IDs (0-999,999) on parent-entity tables
if ($id <= 999999 && $tableIsParent($table)) {
    if (!$this->hasActiveCanonicalEdge($id)) {
        throw new ValidationException(
            "Seed ID {$id} in parent table {$table} has no active canonical_instance_of edge"
        );
    }
}
```

### 5.5 Seed Range Lock

**CONSTITUTIONAL:** The seed ID range is permanently locked to `0-999,999` (inclusive).

- IDs `1,000,000` and above are NOT seeds
- IDs `1,000,000,000,000,000,000+` are canonical or staging
- This range is immutable and requires constitutional amendment to change

**Cross-reference:** Parent/child definitions are normative in PRD 38 s.9.

### 5.6 Lineage edge enforcement (parent-only)

Only `parent` archetype tables may maintain `canonical_instance_of` seed->canonical
---

```php
if ($archetype === 'parent') {
    EdgeWriter::createOrUpdate(
        $seedId,
        $canonicalId,
        'canonical_instance_of',
        0
    );
} else {
    if ($edgeType === 'canonical_instance_of') {
        throw new TrustLadderException(
            "Child/system tables may not use canonical_instance_of"
        );
    }
}
```

Child archetype uses `promoted_to` instead.
System archetype remains seed-only unless PRD amendment explicitly allows otherwise.

### 5.7 Archetype-aware validation hooks


1. pre-insert id validation
2. promotion (`toCanonicalIdSafe`) entry
3. batch ingest preflight
4. lineage edge creation

`validateTrustLadderPk` should receive table context so parent/child/system rules are
enforceable by registry metadata.

### 5.8 Reclassification guardrail

Changing archetype (`child -> parent` or `parent -> child`) is high risk and requires:

1. PRD update (PRD 38, PRD 79, CTL, registry)
2. impact simulation script run (row/edge effect report)
3. `TODO(HONOLULU-GAP)` note documenting risk assumptions
4. human + second-reviewer approval before merge

---

## 6. The Doctrine Rules

### Rule 1: Install seeds are immutable reference rows

```sql
-- NEVER
UPDATE lupo_actors SET actor_name = 'new' WHERE actor_id = 1;
UPDATE lupo_memory_nodes SET memory_value = '{}' WHERE memory_node_id = 1;
DELETE FROM lupo_actors WHERE actor_id = 1;
```

### Rule 2: Install seeds may be used as input to consolidation

```php
// KAIROS may read install seeds when consolidating
$canonical = consolidate([
    'staging_id' => $stagingId,
    'seed_id' => 1,  // WOLFIE's baseline as reference
]);
```

### Rule 3: Install seeds must never be used as active parents in the runtime graph

```php
// WRONG -- parent reference to install seed
INSERT INTO lupo_edges (from_memory_id, to_memory_id, ...)
VALUES (1, $canonicalId, ...);

// CORRECT -- parent reference to canonical row
INSERT INTO lupo_edges (from_memory_id, to_memory_id, ...)
VALUES ($canonicalId, $otherCanonicalId, ...);
```

### Rule 4: Install seeds may remain orphaned after consolidation

```sql
-- This is expected and correct
SELECT * FROM lupo_actors a
LEFT JOIN lupo_edges e ON e.from_memory_id = a.actor_id
WHERE a.actor_id = 1 AND e.edge_id IS NULL;
-- Returns no edges -> orphaned, but that's fine
```

### Rule 5: Install seeds may be used to regenerate or revert canonical rows

```php
// Revert canonical Wolfie back to install baseline
function revertToInstall($canonicalId, $installId) {
    // Copy install values to canonical
    // Add 'reverted_to' edge
    // Log the operation
}
```

### Rule 6: All runtime parents must reference living canonical rows, not install seeds

**Enforced by:** consolidation services, KAIROS, edge writers, channel APIs.

- **Install seed ids** (low BIGINT, registry band) -- must not be `left_object_id` /
  `right_object_id` parents for active runtime work.
- **Living canonical** -- for 18-digit `IdGenerator` tables, ids whose embedded year is
  1000--1999 (after `toCanonicalId`), or table-specific documented canonical band.

### Rule 7: Install seeds are not part of the living graph

When querying active memory (example), exclude low install `memory_node_id` values and
prefer 18-digit canonical-band rows per PRD 38 s.8.4 -- do not use a single `>= 1e18`
hack; filter by id shape and embedded year (or `is_deleted`, `memory_type`) as appropriate
to the table.

---

## 7. What This Means for Consolidation

### The correct consolidation flow

```
Install seed (low fixed id, e.g. 1) --+ (baseline reference; immutable)
                                      |
Raw IdGenerator (staging-shaped) ----+--> toCanonicalId -> INSERT canonical (1000-1999 year)
                                      |        OR merge UPDATE existing canonical
Draft staging row (optional) --------+           + soft-delete staging

Result:
- Install seeds may be graph-orphaned (expected) but never deleted
- Runtime parents point to living canonical ids (18-digit, embedded year 1000--1999),
  not install seeds
- Staging rows are soft-deleted after merge OR never inserted if converted before persist
  (PRD 38 s.4.2.1)
```

### What KAIROS Must Enforce

| Operation | Allowed? | Notes |
|-----------|----------|-------|
| Read install seed as input | Yes | Baseline reference for consolidation |
| Write edge from install seed to canonical | No | Install seeds should not be active parents |
| Write edge from canonical to install seed | Audit only | Edge type = `reverted_to` |
| Delete install seed | No | Never |
| Modify install seed | No | Never (except system upgrade) |
| Promote staging using install seed baseline | Yes | This is the intended use |
| Revert canonical to install seed | Yes | This is why install seeds are kept |

---

## 8. Actor ID transition

Normative walkthrough: s.3 The Wolfie lifecycle. Summary:

1. **Install:** `actor_id = 1` (immutable seed).
2. **First runtime row:** `$canonicalId = toCanonicalId(IdGenerator::generate());` then
   `copyActor(1, $canonicalId)` and `addEdge(1, $canonicalId, 'canonical_instance_of')` --
   no persisted raw staging id unless product requires a draft.
3. **Graph:** runtime references `$canonicalId`, not `1`.
4. **Revert:** `copyInstallToCanonical(1, $canonicalId)` +
   `addEdge($canonicalId, 1, 'reverted_to')`.

**Note:** Whether `lupo_actors` uses 18-digit canonical ids in production is a product +
registry decision; the pattern (seed -> `toCanonicalId`) applies wherever `IdGenerator`
is the runtime allocator for that PK.

---

## 9. Why this resolves the install-seed orphan problem

| Problem | Without Doctrine | With Doctrine |
|---------|------------------|---------------|
| Install seeds become orphaned | "Bug, need to fix" | "Expected, correct behavior" |
| Parents reference install seeds | Happens accidentally | Enforced against |
| Consolidation ignores install seeds | Sometimes, inconsistently | Explicitly allowed as baseline input |
| Can't revert to baseline | Manual, scary | Explicit `revertToInstall()` function |
| Install seeds accumulate garbage | Yes | No -- immutable |
| System upgrade path unclear | Manual | Documented exception |

---

## 10. Affected PRDs

| PRD | Change |
|-----|--------|
| **PRD 00** | s.3.7 Chronological Trust Ladder consolidation; cross-ref PRD 38 s.4.2.1 for `toCanonicalId` |
| **PRD 38** | Memory nodes follow four-tier rules; install seed memory nodes (1-1000) are immutable |
| **PRD 37** | KAIROS must never use install seeds as canonical parents; may use as baseline input |
| **PRD 01** | Actor install seeds (1-2025) are immutable reference rows |
| **PRD 15** | Actor merge protocol excludes install seeds; reversion documented |
| **PRD 24** | CLI `memory archive` cannot archive install seeds |
| **PRD 07** | Agent install seeds (1-2025) are immutable reference templates |
| **PRD 85** | Crafty Syntax import: user_id values MUST be < 10000 (see s.13) |

---

## 11. Summary table

| Aspect | Install seed (low fixed id) | Living canonical (18-digit) | Extended canonical (18-digit) | Staging-shaped (18-digit) |
|--------|-----------------------------|-----------------------------|-------------------------------|--------------------------|
| **How** | SQL / registry | `toCanonicalId(IdGenerator::generate())` | Same transform + policy offset | Raw `IdGenerator::generate()` |
| **Embedded year** | N/A | 1000--1999 | 1000--1999 | 2000--2099 |
| **Example** | `1` | `102604081200001234` | (product-specific) | `202604081200001234` |
| Mutability | Immutable | Mutable | Mutable | Disposable / optional |
| Active parent? | No | Yes | Yes | Temporarily only |
| Orphaned OK? | Yes -- expected | No | No | N/A |
| Revert source/target | Source | Target of copy from seed | -- | No |

---

## 12. The core doctrine statement

> Install-seed rows (low fixed PKs from SQL/registry) are immutable reference truth.
> `IdGenerator::generate()` always produces a staging-shaped id; living canonical rows for
> IdGenerator-backed PKs use `toCanonicalId(IdGenerator::generate())` before insert unless
> a draft staging row is intentional. Install seeds may be used to revert or audit living
> canonical rows but must never be active parents in the runtime graph. Orphaned install
> seeds after consolidation are correct and intentional.

---

## 13. Auth User ID Space Doctrine (Constitutional Constant)

This three-tier partition is a **constitutional constant** and may not be changed without amending PRD 00.

### 13.1 Default Actor for Imported Crafty Users

When importing users from Crafty Syntax (auth_user_id range 1–9999):

* Each user SHALL be paired with ROSE as their default actor
* ROSE SHALL be set as `is_primary = 1` 
* ROSE SHALL have `routing_priority = 1` 

Implementation details:

* ROSE actor_id = 3 (from actors/registry.json)
* Pairing created in `import_from_old_crafty_syntax.sql`
* Only affects imported users (1–9999), not seed users (10000, 10001)

Rationale:

* Imported users are new to Lupopedia
* ROSE provides onboarding and cultural mirroring
* Users may later pair with other actors

### 13.2 Canonical Partition Table

```
-- =============================================================================
-- USER ID SPACE (Strict Doctrine)
-- =============================================================================
-- 0          = True system root (internal, no login, no password)
-- 1 - 9999   = Crafty Syntax imported users from livehelp_users
--              (MUST stay below 10000 - enforced in PHP during import)
-- 10000      = Main Admin / Root Operator (human login)
-- 10001      = Red Team / Adversarial testing user
-- 10002+     = All new users created by IdGenerator (YYYYMMDDHHIISS + 4 digits)
-- =============================================================================
```

### 13.2 Named Constants (atoms file)

These constants MUST be present in
`lupo-memory/atoms/lupopedia_global_constants.atom.toon`:

```
USER_ID_SYSTEM_ROOT     = 0
USER_ID_CRAFTY_MAX      = 9999
USER_ID_MAIN_ADMIN      = 10000
USER_ID_RED_TEAM        = 10001
USER_ID_NEW_USER_MIN    = 10002
```

Atoms override PRDs. All PHP code and all PRDs that reference user ID boundaries MUST
derive their values from these atom constants, not from hard-coded integers.

### 13.3 PHP Enforcement Rules

**Rule A -- Crafty import boundary:**
Every INSERT into `lupo_crafty_user_mapping` MUST be preceded by a PHP guard:

```php
// CONSTITUTIONAL: Crafty livehelp_users.user_id must be < USER_ID_CRAFTY_MAX + 1
if ($craftySyntaxUserId >= USER_ID_MAIN_ADMIN) {
    throw new ImportException(
        "Crafty user_id {$craftySyntaxUserId} >= 10000. " .
        "System-reserved IDs must never appear in crafty_user_mapping."
    );
}
if ($craftySyntaxUserId <= USER_ID_SYSTEM_ROOT) {
    throw new ImportException(
        "Crafty user_id {$craftySyntaxUserId} <= 0. " .
        "System root ID 0 must never appear in crafty_user_mapping."
    );
}
```

**Rule B -- New user ID generation:**
All new users created after install MUST use IdGenerator:

```php
$newAuthUserId = IdGenerator::generate(); // YYYYMMDDHHIISS + 4 random digits
// Result is always >= USER_ID_NEW_USER_MIN (10002) by construction
// because the year component alone is 2026+ which exceeds 10002
```

**Rule C -- Reserved ID protection:**
No INSERT, UPDATE, or DELETE may target `auth_user_id` values 0, 10000, or 10001 from
application code. These rows are immutable install seeds per s.4.

### 13.4 Installer Seed Rows

The installer (`install_new_lupopedia.sql`) seeds exactly these rows in `lupo_auth_users`:

| auth_user_id | username | Purpose | Login? |
|-------------|----------|---------|--------|
| 0 | system | True system root | No |
| 10000 | admin | Main Admin / Root Operator | Yes |
| 10001 | redteam | Red Team / Adversarial testing | Yes |

**No other fixed auth_user_id values are seeded by the installer.**

Rows 1--9999 are populated only by the Crafty Syntax import process (PRD 85).
Rows 10002+ are populated only by IdGenerator at runtime.

### 13.5 Why This Range Was Chosen

- **ID 0** is the universal convention for "no user / system" in database design. It is
  never a real human account.
- **1--9999** gives Crafty Syntax up to 9,999 imported operators. Crafty Syntax
  `livehelp_users.user_id` is a plain integer that starts at 1 and is rarely above a few
  hundred in production instances. 9,999 is a safe upper bound with margin.
- **10000** is a clean decimal boundary that is visually distinct from both the Crafty
  import range and the IdGenerator range. Human operators can read it without confusion.
- **10001** is immediately adjacent for the red team user, keeping system-reserved IDs
  contiguous.
- **10002+** is where IdGenerator takes over. Since IdGenerator produces 18-digit
  timestamps starting with year 2026, the actual values are in the 20-quadrillion range,
  far above 10002. The 10002 floor is a logical minimum, not a practical one.

### 13.6 Cross-References

- **PRD 00_A s.14** -- FORBIDDEN: Never assume user ID space
- **PRD 85** -- Crafty Syntax import, user mapping doctrine
- **PRD 32** -- Actor Authority: red team actor assignment to auth_user_id 10001
- **PRD 43** -- Parent-Child Trust Ladder: adversarial testing setup
- `lupo-memory/atoms/lupopedia_global_constants.atom.toon` -- canonical constants

---

## 14. Next Actions

1. Add USER_ID_* constants to `lupo-memory/atoms/lupopedia_global_constants.atom.toon`
2. Update PRD 85 (Crafty Syntax Import) to reference USER_ID_CRAFTY_MAX from atoms
3. Verify installer seed rows match s.13.4 partition table
4. Review by LILITH
5. Approval by WOLFIE
6. Update PRD 00, 37, 38, 01, 15, 24, 07 for ASCII cleanup and cross-references

## AGAPE Integration

AGAPE is the technical resilience and self-healing framework consisting of:
- Fallback ladders (multi-agent handoff)
- Environment probing (violation detection)
- Graceful degradation (20-minute actor message timeout → trigger teaching/hand-off to another actor)
- Evidence-driven validation (no heartbeat/status polling — only track when_updated)
- Adaptive pathing
- WHY files (PRD 98_A) as the automatic violation logging and constitutional self-healing mechanism

---

## Revision note

| Date | Change |
|------|--------|
| 20260412133907 | Initial draft -- four-tier PK doctrine |
| 20260421221540 | Header upgraded to 4.1.4 (22 fields). ASCII cleanup throughout. Added s.13 Auth User ID Space Doctrine as constitutional constant. Added atoms_toon reference. Updated s.10 to cross-ref PRD 85. Renumbered s.12 core doctrine statement unchanged; old s.12 is now s.14 Next Actions. |
