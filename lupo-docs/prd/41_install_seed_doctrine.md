---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260408014411"
  file_path_from_root: "lupo-docs/prd/41_install_seed_doctrine.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/41_install_seed_doctrine.md"
  last_modified_utc: "20260408014411"
  federation_node_id: 0
  channel_id: 42
  thread_id: "install-seed-doctrine"
  prd_id: 41
  prd_slug: install_seed_doctrine
  author:
    type: "actor"
    id: 1
    name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: doctrine
  artifact_kind: constitutional
  purpose: "Defines the fourth tier — Install Seed rows — as immutable reference truth, not active truth"
  status: "draft"
  tags:
    - doctrine
    - install_seed
    - pk_tiers
    - consolidation
    - constitutional
    - reversion
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: amends
      weight: 1.0
      reason: "Adds fourth tier to PK ranges and consolidation doctrine"
    - to: "lupo-docs/prd/38_memory_unification.md"
      type: amends
      weight: 1.0
      reason: "Memory nodes follow Chronological Trust Ladder + IdGenerator vs toCanonicalId (PRD 38 §4.2.1)"
    - to: "lupo-docs/prd/37_kairos_channel_memory_consolidation.md"
      type: amends
      weight: 1.0
      reason: "KAIROS must never use install seeds as canonical parents"
    - to: "lupo-docs/prd/01_core_identity.md"
      type: amends
      weight: 1.0
      reason: "Actor install seeds (1-2025) are immutable reference rows"
lupopedia.footer:
  last_verified: "20260408014411"
  verified_by:
    identity_type: actor
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
---

# PRD 41: Install Seed Doctrine (The Fourth Tier)

## 1. The consolidation orphan problem

When consolidating staging rows (2000‑range) into canonical rows (1000‑range), all parent references were updated to point to the new canonical rows.

But **install‑seed rows (0–999,999) became orphaned** because:

- They were originally parents
- Their children were updated to point to canonical rows
- Install seeds were never meant to be deleted
- Install seeds were never meant to be canonical
- Install seeds were never meant to be staging
- They are **reference truth**, not **active truth**

**Result:** Orphaned install seeds that still contain valuable original data, but no longer participate in the active graph.

**This is correct behavior** — but it must be documented as intentional, not a bug.

---

## 2. PK tiers: install seeds vs IdGenerator-shaped ids

Lupopedia uses **more than one PK shape**. **Do not** collapse them into a single numeric band.

### 2.1 Install seed tier (low `BIGINT`, not from `IdGenerator`)

| Tier | How allocated | Example | Mutability | Role |
|------|----------------|---------|------------|------|
| **Install seed** | Fixed in **`install_new_lupopedia.sql`** / **`seed_*.sql`**; registry-backed | **`actor_id = 1`** (WOLFIE), **`19`**, **`42`** | ❌ Immutable | **Reference truth** — baseline, audit, reversion source; **not** the living runtime row |

**Band (policy):** **`0–999,999`** for “low install” ids unless a table’s registry explicitly documents a different reserved band (**PRD 00** §3.2.1).

### 2.2 Timestamp-shaped ids (`IdGenerator` — 18 digits)

For any table whose runtime PK is **`IdGenerator::generate()`** (**`lupo-includes/classes/IdGenerator.php`** — **18 digits**, embedded calendar year **2000–2099**):

| Tier | Embedded year (first 4 digits) | How obtained | Example | Mutability |
|------|--------------------------------|--------------|---------|------------|
| **Staging-shaped** | **2000–2099** | Raw **`IdGenerator::generate()`** | `202604081200001234` | Disposable / merge source |
| **Living canonical** | **1000–1999** | **`toCanonicalId(IdGenerator::generate())`** (subtract **1000** from year when **≥ 2000**) | `102604081200001234` | ✅ Mutable (**UPDATE** living truth) |
| **Extended canonical** | **1000–1999** | Same transform **±** product-defined offset / allocator (still chronological PK band) | e.g. `1500000000000000000` (illustrative) | ✅ Mutable |

**Binding rule (same as **PRD 38** §4.2.1 / **PRD 00** §3.7):**

> **`IdGenerator::generate()` always returns a staging-shaped id. To create a new living canonical row, use `toCanonicalId(IdGenerator::generate())` before `INSERT` unless policy deliberately persists a draft staging row.**

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

### 2.3 Low install seed → living canonical `actor_id` (`seedActorToCanonicalId`)

**Not `toCanonicalId`:** **`toCanonicalId()`** applies to **18-digit staging-shaped** ids (**§2.2**). For a **low registry seed** (e.g. **`116`**), **`strlen($idStr) < 4`** returns the input **unchanged** — you do **not** get a **1000–1999** band id. Do **not** overload **`toCanonicalId()`** for **`lupo_actors`** install seeds.

**Rule (paired living row, policy band typically 1–2025):** When allocating a **living canonical** **`lupo_actors.actor_id`** that **must be deterministically paired** with a fixed install seed:

```php
function seedActorToCanonicalId($seedActorId) {
    return 100000000000000000 + (int) $seedActorId;
}
```

**Examples:** **`1` → `100000000000000001`**, **`19` → `100000000000000019`**, **`116` → `100000000000000116`**. The value is **18 digits**; the **first four** are **`1000`** (Chronological Trust Ladder **living canonical** band); the **suffix** preserves traceability to the seed.

**Coexistence with §2.2:** For **`lupo_actors`**, product **may** use **`toCanonicalId(IdGenerator::generate())`** when policy wants a **time-shaped** canonical (**§3** walkthrough). Use **`seedActorToCanonicalId($seed)`** when the canonical id must be **strictly derivable** from the seed (e.g. facet **Claude Code** **116**). Both are **living canonical** shapes; pick one rule per row at **`INSERT`**, document in registry/release notes.

**Why two notions of “canonical”:** Install seeds are **low fixed ids**; **living** canonical rows for runtime-created entities use **1000–1999 embedded year** on **18-digit** ids. They are **different columns / tables** — never assume one formula covers **`lupo_actors.actor_id`** and **`lupo_memory_nodes.memory_node_id`** without checking the table’s allocation rules.

---

## 3. The Wolfie lifecycle (corrected)

### Step 1 — Fresh install (reference seed)

```
Install seed: actor_id = 1 (WOLFIE)
- Immutable baseline
- Not the living runtime graph parent
```

### Step 2 — First runtime use (web / service)

**No persistent staging row** if policy creates canonical immediately:

```php
$stagingId   = IdGenerator::generate();              // 202604081200001234
$canonicalId = toCanonicalId($stagingId);              // 102604081200001234

copyActor(1, $canonicalId);                            // copy from install seed → new row
addEdge(1, $canonicalId, 'canonical_instance_of');     // seed → living canonical
```

**Alternate (deterministic seed pairing):** **`$canonicalId = seedActorToCanonicalId(1)`** → **`100000000000000001`** — use when policy requires a canonical id **strictly derived** from the seed without a fresh **`IdGenerator`** draw (**§2.3**). **`copyActor`**, **`addEdge`**, and orphan semantics match the **`toCanonicalId(IdGenerator::generate())`** path.

**Result:**

- **`actor_id = 1`** remains forever (reference).
- **`actor_id = 102604081200001234`** (example) is the **living** WOLFIE for sessions, edges, memory.
- **No** row is inserted for raw **`$stagingId`** when conversion happens **before** insert.

### Step 3 — Corruption / “dodo birds”

The **living canonical** row may be damaged while **`1`** stays clean.

### Step 4 — Revert living canonical from install seed

```php
copyInstallToCanonical(1, $canonicalId);               // copy fields from seed 1 → canonical row
addEdge($canonicalId, 1, 'reverted_to');              // audit: canonical acknowledged seed
```

**Edges (names are product-defined; register in `lupo_edges` conventions):** **`canonical_instance_of`** (install → canonical), **`reverted_to`** (canonical → install for audit).

### Orphaned install seed

After the graph points at **living canonical** ids, **install seed `1`** may have **no incoming runtime parents** — **expected** per §1. It remains for **reversion and audit**, not for active edges as parent (**Rule 3**).

---

## 4. Install Seed Characteristics

| Property | Value |
|----------|-------|
| **Role** | Reference truth, baseline, template, prototype |
| **Origin** | `install_new_lupopedia.sql` or `seed_*.sql` |
| **Examples** | WOLFIE (1), LILITH (2), ANUBIS (19), Department 0 (0), Channel 42 (42) |
| **Can be deleted?** | ❌ No |
| **Can be modified?** | ❌ No (except by system upgrade) |
| **Can be parent?** | ❌ No — never used as active parent in runtime graph |
| **Can be input to consolidation?** | ✅ Yes — as baseline reference |
| **Should be referenced by runtime parents?** | ❌ No — parents must point to canonical rows |
| **Can be orphaned?** | ✅ Yes — this is expected and correct |
| **Can be used for reversion?** | ✅ Yes — restore canonical from install baseline |
| **Part of living graph?** | ❌ No |

---

## 5. The Doctrine Rules

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
// WRONG — parent reference to install seed
INSERT INTO lupo_edges (from_memory_id, to_memory_id, ...) 
VALUES (1, $canonicalId, ...);

// CORRECT — parent reference to canonical row
INSERT INTO lupo_edges (from_memory_id, to_memory_id, ...) 
VALUES ($canonicalId, $otherCanonicalId, ...);
```

### Rule 4: Install seeds may remain orphaned after consolidation

```sql
-- This is expected and correct
SELECT * FROM lupo_actors a
LEFT JOIN lupo_edges e ON e.from_memory_id = a.actor_id
WHERE a.actor_id = 1 AND e.edge_id IS NULL;
-- Returns no edges → orphaned, but that's fine
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

- **Install seed ids** (low **`BIGINT`**, registry band) — **must not** be **`left_object_id` / `right_object_id`** parents for active runtime work.
- **Living canonical** — for **18-digit** **`IdGenerator`** tables, ids whose **embedded year is 1000–1999** (after **`toCanonicalId`**), or table-specific documented canonical band.

### Rule 7: Install seeds are not part of the living graph

When querying **active** memory (example), **exclude** low install **`memory_node_id`** values and prefer **18-digit** canonical-band rows per **PRD 38** §8.4 — **do not** use a single `>= 1e18` hack; filter by **id shape** and **embedded year** (or **`is_deleted`**, **`memory_type`**) as appropriate to the table.

---

## 6. What This Means for Consolidation

### The correct consolidation flow

```
Install seed (low fixed id, e.g. 1) ──┐ (baseline reference; immutable)
                                      │
Raw IdGenerator (staging-shaped) ────┼──► toCanonicalId → INSERT canonical (1000–1999 year)
                                      │        OR merge UPDATE existing canonical + soft-delete staging
Draft staging row (optional) ────────┘

Result:
- Install seeds may be graph-orphaned (expected) but never deleted
- Runtime parents point to **living canonical** ids (18-digit, embedded year 1000–1999), not install seeds
- Staging rows are soft-deleted after merge **or never inserted** if converted before persist (**PRD 38** §4.2.1)
```

### What KAIROS Must Enforce

| Operation | Allowed? | Notes |
|-----------|----------|-------|
| Read install seed as input | ✅ Yes | Baseline reference for consolidation |
| Write edge from install seed to canonical | ❌ No | Install seeds should not be active parents |
| Write edge from canonical to install seed | ⚠️ Yes, but only for reversion tracking | Edge type = `reverted_to` |
| Delete install seed | ❌ No | Never |
| Modify install seed | ❌ No | Never (except system upgrade) |
| Promote staging using install seed baseline | ✅ Yes | This is the intended use |
| Revert canonical to install seed | ✅ Yes | This is why install seeds are kept |

---

## 7. Actor ID transition

Normative walkthrough: **§3 The Wolfie lifecycle**. Summary:

1. **Install:** `actor_id = 1` (immutable seed).
2. **First runtime row:** `$canonicalId = toCanonicalId(IdGenerator::generate());` then **`copyActor(1, $canonicalId)`** and **`addEdge(1, $canonicalId, 'canonical_instance_of')`** — **no** persisted raw staging id unless product requires a draft.
3. **Graph:** runtime references **`$canonicalId`**, not **`1`**.
4. **Revert:** **`copyInstallToCanonical(1, $canonicalId)`** + **`addEdge($canonicalId, 1, 'reverted_to')`**.

**Note:** Whether **`lupo_actors`** uses **18-digit** canonical ids in production is a **product + registry** decision; the **pattern** (seed → **`toCanonicalId`**) applies wherever **`IdGenerator`** is the runtime allocator for that PK.

---

## 8. Why this resolves the install-seed orphan problem

| Problem | Without Doctrine | With Doctrine |
|---------|------------------|---------------|
| Install seeds become orphaned | "Bug, need to fix" | "Expected, correct behavior" |
| Parents reference install seeds | Happens accidentally | Enforced against |
| Consolidation ignores install seeds | Sometimes, inconsistently | Explicitly allowed as baseline input |
| Can't revert to baseline | Manual, scary | Explicit `revertToInstall()` function |
| Install seeds accumulate garbage | Yes | No — immutable |
| System upgrade path unclear | Manual | Documented exception |

---

## 9. Affected PRDs

| PRD | Change |
|-----|--------|
| **PRD 00** | §3.7 Chronological Trust Ladder consolidation; cross-ref **PRD 38** §4.2.1 for **`toCanonicalId`** |
| **PRD 38** | Memory nodes follow four-tier rules; install seed memory nodes (1-1000) are immutable |
| **PRD 37** | KAIROS must never use install seeds as canonical parents; may use as baseline input |
| **PRD 01** | Actor install seeds (1-2025) are immutable reference rows |
| **PRD 15** | Actor merge protocol excludes install seeds; reversion documented |
| **PRD 24** | CLI `memory archive` cannot archive install seeds |
| **PRD 07** | Agent install seeds (1-2025) are immutable reference templates |

---

## 10. Summary table

| Aspect | Install seed (low fixed id) | Living canonical (18-digit) | Extended canonical (18-digit) | Staging-shaped (18-digit) |
|--------|------------------------------|------------------------------|--------------------------------|---------------------------|
| **How** | SQL / registry | **`toCanonicalId(IdGenerator::generate())`** | Same transform + policy offset | Raw **`IdGenerator::generate()`** |
| **Embedded year** | N/A | **1000–1999** | **1000–1999** | **2000–2099** |
| **Example** | `1` | `102604081200001234` | (product-specific) | `202604081200001234` |
| Mutability | ❌ Immutable | ✅ Mutable | ✅ Mutable | 🗑️ Disposable / optional |
| Active parent? | ❌ No | ✅ Yes | ✅ Yes | Temporarily only |
| Orphaned OK? | ✅ Expected | ❌ No | ❌ No | N/A |
| Revert source/target | Source | Target of copy from seed | — | ❌ No |

---

## 11. The core doctrine statement

> **Install-seed rows (low fixed PKs from SQL/registry) are immutable reference truth. `IdGenerator::generate()` always produces a staging-shaped id; living canonical rows for IdGenerator-backed PKs use `toCanonicalId(IdGenerator::generate())` before insert unless a draft staging row is intentional. Install seeds may be used to revert or audit living canonical rows but must never be active parents in the runtime graph. Orphaned install seeds after consolidation are correct and intentional.**

---

**Status:** DRAFT — awaiting review

**Next actions:**
1. Review by LILITH
2. Approval by WOLFIE
3. Update PRD 00, 37, 38, 01, 15, 24, 07
4. Update KAIROS implementation to enforce rules
5. Add `revertToInstall()` function to ActorService

---

This is the **complete, correct four-tier doctrine** that explains the **Chronological Trust Ladder** pattern and applies it to Lupopedia.

Do you want me to now generate the pseudocode/shorthand version for this PRD?