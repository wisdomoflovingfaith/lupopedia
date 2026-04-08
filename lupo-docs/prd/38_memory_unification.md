---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260408012727"
  file_path_from_root: "lupo-docs/prd/38_memory_unification.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/38_memory_unification.md"
  last_modified_utc: "20260408012727"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-38-memory-unification"
  prd_id: 38
  prd_slug: memory_unification
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: prd
  artifact_kind: constitutional_compliance
  purpose: "Unify memory graph with database as source of truth + filesystem export for IDE/Claude access by date"
  status: "draft"
  tags:
    - prd
    - memory
    - graph
    - constitutional
    - unification
    - edges
    - filesystem_export
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: amends
      weight: 1.0
      reason: "Constitutional anchor — §3.7 Chronological Trust Ladder / §5.7 unified memory graph + filesystem export mirror"
    - to: "lupo-docs/prd/07_agents_faucets.md"
      type: amends
      weight: 1.0
      reason: "Removes memory.json references; replaces with unified DB graph + export mirror"
    - to: "lupo-docs/prd/15_actors.md"
      type: amends
      weight: 1.0
      reason: "Actor workspace documentation updated to reference unified memory graph"
    - to: "lupo-docs/prd/24_actor_onboarding_flow.md"
      type: amends
      weight: 1.0
      reason: "Actor onboarding — creates DB root node + exports to filesystem"
    - to: "lupo-docs/prd/37_kairos_channel_memory_consolidation.md"
      type: references
      weight: 1.0
      reason: "KAIROS writes to DB; export service mirrors to filesystem"
lupopedia.footer:
  last_verified: "20260408012727"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
  orchestrator: "cursor:root"
  next_action:
    - "Apply amendment prose in PRD 07, 15, 24, 37, and 00 section 5.7 per sections 11.1–11.5"
    - "Reconcile lupo_edges vs lupo_memory_edges in dependent PRDs once graph split is finalized"
    - "Regenerate TOONs for memory_nodes and memory_edges after schema freeze"
    - "Wire actor onboarding + KAIROS to insert rows then call MemoryExportService::exportNode()"
    - "lupo-memory/ remains read-only export mirror (not source of truth)"
---

# PRD 38: Memory Unification — Constitutional Graph Compliance (Revised)

## 1. Purpose

This PRD resolves the **constitutional violation** between doctrine (unified graph) and implementation (split DB + files) while **preserving filesystem access** for IDE agents like Claude Code.

**The real requirement:** You need to browse memory files by date in `lupo-memory/YYYY/MM/` so Claude Code can read them easily. That's valid and must be preserved.

**The solution:** **Database as source of truth, filesystem as read-only export mirror.**

- All writes go to database first (`lupo_memory_nodes`, `lupo_memory_edges`)
- An export service mirrors DB rows to `lupo-memory/YYYY/MM/{slug}.json` (slug derived in PHP; see section 5)
- IDE/Claude reads from filesystem (same experience as today)
- Graph queries, KAIROS consolidation, and edge traversal use the database

**This gives you both:**
1. ✅ **Queryable graph** with typed edges, status, context, direction
2. ✅ **Filesystem access** by date for IDE agents

---

## 2. Constitutional Authority

This PRD amends **PRD 00** section **5.7** to clarify:

> *"The canonical source of truth for memory nodes is the `lupo_memory_nodes` database table. The filesystem path `lupo-memory/YYYY/MM/{slug}.json` is a **read-only export mirror** maintained by the `MemoryExportService`. The filename stem is generated from `created_ymdhis` and node fields (not a separate DB slug column). All writes MUST go to the database first; the export service synchronizes to disk."*

---

## 3. Architecture: Database + Export Mirror

### 3.1 Data Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           WRITE PATH (Primary)                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│   KAIROS ──→ lupo_memory_nodes ──→ MemoryExportService ──→ .json file      │
│   ROSE                              (background/async)     (YYYY/MM/)       │
│   Actor Onboarding                                                          │
│   THOTH                                                                     │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                           READ PATH (Two Options)                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│   OPTION A (IDE/Claude):  Read .json file directly from disk               │
│   OPTION B (Graph query):  Query lupo_memory_nodes + lupo_memory_edges      │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Key Principle

| Operation | Source of Truth | Secondary |
|-----------|-----------------|-----------|
| **Write** (create, update, delete) | Database only | Export service mirrors to disk |
| **Read** (graph traversal, edges, status) | Database only | N/A |
| **Read** (IDE browsing by date) | Filesystem (mirror) | Falls back to DB if missing |

**The filesystem is NEVER written directly.** All writes go through the database.

---

## 4. Timestamps, IDs, and schema parity

### 4.0 `IdGenerator` and `created_ymdhis`

**Runtime rows:** Primary keys for **`lupo_memory_nodes`** / **`lupo_memory_edges`** use **`IdGenerator::generate()`**, which returns an 18-digit string: **14-digit packed UTC** (`YYYYMMDDHHIISS`) **plus a 4-digit suffix** (see `lupo-includes/classes/IdGenerator.php`; suffix is CSPRNG-derived, not a monotonic sequence). **Seed rows** are exempt — see **§4.1** and **PRD 00 §3.2.1**.

**Rules (runtime inserts):**

1. On **INSERT** of a **runtime** row, set **`memory_node_id`** (or **`memory_edge_id`**) to **`IdGenerator::generate()`**. **Seed** inserts use **literal** ids from install/seed SQL — **PRD 00 §3.2.1**.
2. For **runtime** rows, set **`created_ymdhis`** to the **same** 14-digit prefix as the new PK: use `IdGenerator::extractTimestamp($id)` or `(int) substr((string) $id, 0, 14)`. **Seed** rows: install UTC or **`0`** per **§4.1**.
3. On **INSERT**, set **`updated_ymdhis`** to that same prefix (or to `gmdate('YmdHis')` if you require “last touched” distinct from creation — default pattern: both equal at insert).
4. On **UPDATE**, set **`updated_ymdhis`** to **`gmdate('YmdHis')`**.
5. **`created_ymdhis`** is therefore **redundant but consistent** with every other Lupopedia table that carries both a time-structured BIGINT PK and a `created_ymdhis` column.

**Seed / pre-existing rows (install + immemorial):**

| Record type | `memory_node_id` (PK) | `created_ymdhis` | Notes |
|-------------|------------------------|------------------|--------|
| **Runtime** | `IdGenerator::generate()` (14-digit UTC + 4-digit suffix) | Same **14-digit** prefix as PK at insert | Default path for actor-created nodes |
| **Seed / system** | Low reserved id (e.g. 1, 2, 3) from **`install_new_lupopedia.sql`** / seed | **Install** packed UTC **or** **`0`** = “before temporal tracking” | PK is **not** timestamp-shaped; **`created_ymdhis`** carries real install time or **`0`** |

**Filesystem export:** **`MemoryExportService`** builds **`lupo-memory/{YYYY}/{MM}/`** from **`created_ymdhis`**. When **`created_ymdhis`** is **`0`** (or too short to form **`YYYYMM`**), the service uses **`19700101000000`** for path and slug → **`lupo-memory/1970/01/`** (“pre-history” bucket). The JSON payload still echoes the DB column values (`memory_node_id`, `created_ymdhis` unchanged).

**Example (memory node):**

```php
$memoryNodeId = IdGenerator::generate();
$createdYmdhis = (int) IdGenerator::extractTimestamp($memoryNodeId);

$db->query(
    'INSERT INTO ' . $table . ' (memory_node_id, created_ymdhis, updated_ymdhis, owner_actor_id, owner_type, memory_type, memory_key, memory_value, context, status, content_hash, context_json, expires_ymdhis, is_deleted, deleted_ymdhis) VALUES (:mid, :c, :u, :oaid, :otype, :mtype, :mkey, :mval, :ctx, :st, :chash, :cjson, :exp, 0, 0)',
    array(
        'mid' => $memoryNodeId,
        'c' => $createdYmdhis,
        'u' => $createdYmdhis,
        'oaid' => $ownerActorId,
        'otype' => 'actor',
        'mtype' => 'root',
        'mkey' => 'actor:root_context',
        'mval' => json_encode($payload),
        'ctx' => 'experiential',
        'st' => 'supported',
        'chash' => hash('sha256', json_encode($payload)),
        'cjson' => null,
        'exp' => 0,
    )
);
```

**Primary key column name:** Table `lupo_memory_nodes` uses **`memory_node_id`** (PK naming doctrine: singular `memory_node` + `_id`). For **runtime** inserts, the numeric value is what **`IdGenerator`** returns.

### 4.1 `lupo_memory_nodes` — seed vs runtime (DDL comments)

Canonical **`CREATE TABLE`** is in **§5.1** (must match **`install_new_lupopedia.sql`**). Comment semantics:

```sql
CREATE TABLE {{prefix}}memory_nodes (
    memory_node_id BIGINT NOT NULL,
    -- Seed / install: low reserved id from install or seed_*.sql (e.g. 1..N)
    -- Runtime: IdGenerator::generate() → YYYYMMDDHHIISS + 4-digit suffix

    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    -- Seed: installation packed UTC OR 0 (0 = pre-existing / before temporal tracking)
    -- Runtime: same 14-digit UTC prefix as memory_node_id at insert

    -- ... remaining columns per §5.1
);
```

### 4.2 Memory trust tiers (Chronological Trust Ladder pattern)

For **timestamp-shaped** **`memory_node_id`** values (**18 digits**, **`IdGenerator`** layout), the **first four digits** are a **calendar year** embedded in the PK. That yields the **Chronological Trust Ladder**: operators and reviewers can read **authority / lifecycle** from the id **without** extra flags. **Constitutional summary:** **PRD 00** §3.7; canonical doctrine **`lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md`**.

#### 4.2.1 `IdGenerator::generate()` vs `toCanonicalId` (normative)

**Reference implementation:** **`lupo-includes/classes/IdGenerator.php`** — `generate()` returns an **18-digit** string; **`validateFormat()`** constrains the embedded clock to calendar years **2000–2099**. Therefore **every fresh generator value is staging-shaped** (embedded year **2000–2099**).

**Binding rule:**

> **`IdGenerator::generate()` always yields a staging-range id (embedded year 2000–2099). To persist a new living canonical row (embedded year 1000–1999), apply `toCanonicalId(IdGenerator::generate())` before `INSERT` unless product policy deliberately keeps a draft staging row.**

**Conversion (pseudocode — implement next to `IdGenerator` or a shared id helper):**

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

**Examples:**

- `IdGenerator::generate()` → **`202604081200001234`** (staging-shaped).
- `toCanonicalId('202604081200001234')` → **`102604081200001234`** (living canonical-shaped).
- **First web / runtime row** for an entity may **skip persisting staging**: generate once, transform, `INSERT` canonical only; link install seed → canonical with an edge such as **`canonical_instance_of`** where applicable (**PRD 41**).

**When staging rows still appear:** Observations, drafts, or multi-step merges — `INSERT` with raw **`IdGenerator::generate()`**, then **merge into** existing canonical (**UPDATE**) or **promote** via **`toCanonicalId`** on the staging id and soft-delete staging, per the consolidation steps below.

| Tier | PK shape | Year in id (first 4 digits) | Trust | Mutable? | Typical origin |
|------|----------|----------------------------|-------|----------|----------------|
| **Install / seed** | Low **`BIGINT`**, not timestamp-shaped | N/A (e.g. **1–2025** per install / registry) | **Highest** (canonical system rows) | **No** (system / install only) | **`install_new_lupopedia.sql`** / seed |
| **Reserved (numeric gap)** | **> 2025** and **< 1e17** if ever used | *implementation-defined* | *Reserved for future allocation* | — | Not used for timestamp ids today |
| **Living canonical** (long-term band) | **18-digit** timestamp layout | **1000–1999** | **High** — **single source of truth** for the entity / topic | **Yes** — **UPDATE** as new evidence arrives (**accumulated best knowledge**; **stable id**) | KAIROS or operator consolidation into an **existing** canonical row; **§8 Option B** archive (**1026–2025** ⊂ **1000–1999**) |
| **Staging / runtime** | **18-digit** timestamp layout | **2000–2099** (`IdGenerator::validateFormat` band) | **Low** (incoming, may be incomplete or superseded) | **Yes** — **short-lived**; merged then **soft-deleted** (or **never inserted** if converted per **§4.2.1**) | Raw **`IdGenerator::generate()`**; observations before consolidation |

**Consolidation flow (normative intent):**

1. Detect duplicate or merge-eligible rows (same logical **`memory_key`** / topic, overlapping **`owner_*`**, policy from KAIROS or operator tooling). **Staging** rows sit in the **2000–2099** embedded-year band (raw **`IdGenerator`** output per **§4.2.1**).
2. **If no living canonical** exists: **promote** — set **`memory_node_id = toCanonicalId($stagingId)`** (same **−1000** year transform as **§8.3** / **§4.2.1**), **merge** all **non-null** fields into that row’s payload, then **`INSERT`** (or replace staging row’s PK per policy). If policy **creates canonical without ever persisting staging**, use **`toCanonicalId(IdGenerator::generate())`** once and **`INSERT`** directly. Link **each** consumed staging row → canonical with **`lupo_memory_edges`** (e.g. **`edge_type = consolidated_into`**; **`promoted_to`** if install SQL defines it). **KAIROS** may emit **`kairos_consolidates_from`** on **`lupo_edges`** + **`actor_memory`**; align names per install SQL and **PRD 37**.
3. **If living canonical already exists:** **UPDATE** that row — merge **non-null** staging fields into the canonical payload, set **`updated_ymdhis`**. Link each staging row → canonical (**`merged_into`** / **`consolidated_into`** per install SQL).
4. **Soft-delete** consumed **staging** rows (**`is_deleted = 1`**, **`deleted_ymdhis`**).
5. **Re-point** any parent / child edges or references in **application logic** (no DB FKs) to the **canonical** **`memory_node_id`** where policy requires.

**Query priority (trust order) for “best row” selection** (prefer **application-layer** sort; illustration only):

1. **Living canonical** (**1000–1999** in id) — preferred **current best knowledge** for human-facing answers when present and not soft-deleted.
2. **Staging / runtime** (**2000–2099** embedded year on raw **`IdGenerator`** ids) — **fallback** observations not yet merged.
3. **Install / seed** (**1–2025** low ids) — **system defaults**; **lowest** priority for **actor-specific** “what did we learn?” queries (still highest for **global** system invariants).

**Relation to §8 (Option B):** **Archive** applies the **same `toCanonicalId` / −1000 year transform** to **runtime-shaped** ids (embedded year **≥ 2000**), producing ids in the **1000–1999** embedded-year band (e.g. **2026** → **1026**). Use **`archived_to`** (§8) vs **`consolidated_into`** / merge edges (this subsection) to distinguish **cold archive** from **consolidation**. The **1000–1999** band is **not** “frozen read-only”; it is **living canonical** unless policy marks a row immutable for a special case.

---

## 5. New Tables (DDL — canonical with `install_new_lupopedia.sql`)

### 5.1 `lupo_memory_nodes` (source of truth)

```sql
CREATE TABLE {{prefix}}memory_nodes (
    memory_node_id BIGINT NOT NULL,
    -- Runtime staging-shaped: IdGenerator::generate() → embedded year 2000–2099
    -- Runtime living canonical: toCanonicalId(IdGenerator::generate()) → embedded year 1000–1999 (PRD 38 §4.2.1)
    -- Seed / install: MAY be a low reserved BIGINT (1, 2, …) from install SQL

    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    -- Runtime: same 14-digit UTC prefix as memory_node_id at insert
    -- Seed: installation packed UTC OR 0 (0 = pre-existing / before temporal tracking)

    owner_actor_id BIGINT NOT NULL,
    owner_type VARCHAR(32) NOT NULL DEFAULT 'actor',

    memory_type VARCHAR(32) NOT NULL,
    memory_key VARCHAR(255) NOT NULL,
    memory_value TEXT,

    context VARCHAR(32) NOT NULL DEFAULT 'experiential',
    status VARCHAR(32) NOT NULL DEFAULT 'unsupported',
    review_reason VARCHAR(64) DEFAULT NULL,

    content_hash CHAR(64) NOT NULL,
    context_json JSON DEFAULT NULL,

    updated_ymdhis BIGINT NOT NULL DEFAULT 0,
    expires_ymdhis BIGINT NOT NULL DEFAULT 0,

    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT NOT NULL DEFAULT 0,

    PRIMARY KEY (memory_node_id)
);

CREATE INDEX {{prefix}}memory_nodes_idx_owner ON {{prefix}}memory_nodes (owner_actor_id, owner_type, is_deleted);
CREATE INDEX {{prefix}}memory_nodes_idx_created ON {{prefix}}memory_nodes (created_ymdhis, is_deleted);
CREATE INDEX {{prefix}}memory_nodes_idx_type ON {{prefix}}memory_nodes (memory_type, status, is_deleted);
CREATE INDEX {{prefix}}memory_nodes_idx_key ON {{prefix}}memory_nodes (memory_key, owner_actor_id);
CREATE INDEX {{prefix}}memory_nodes_idx_updated ON {{prefix}}memory_nodes (updated_ymdhis, is_deleted);
CREATE INDEX {{prefix}}memory_nodes_idx_expires ON {{prefix}}memory_nodes (expires_ymdhis, is_deleted);
```

### 5.2 `lupo_memory_edges`

```sql
CREATE TABLE {{prefix}}memory_edges (
    memory_edge_id BIGINT NOT NULL,
    from_memory_node_id BIGINT NOT NULL,
    to_memory_node_id BIGINT NOT NULL,

    edge_type VARCHAR(64) NOT NULL,
    edge_context VARCHAR(32) NOT NULL DEFAULT 'system_generated',
    edge_status VARCHAR(32) NOT NULL DEFAULT 'supported',
    edge_direction VARCHAR(16) NOT NULL DEFAULT 'unidirectional',

    weight_hundredths INT NOT NULL DEFAULT 100,
    -- 100 = weight 1.00 (portable integer; avoid DECIMAL in DDL)

    provenance_actor_id BIGINT NOT NULL,
    provenance_tool VARCHAR(64) NOT NULL,

    review_reason VARCHAR(64) DEFAULT NULL,

    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT NOT NULL DEFAULT 0,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT NOT NULL DEFAULT 0,

    PRIMARY KEY (memory_edge_id)
);

CREATE INDEX {{prefix}}memory_edges_idx_from ON {{prefix}}memory_edges (from_memory_node_id, is_deleted);
CREATE INDEX {{prefix}}memory_edges_idx_to ON {{prefix}}memory_edges (to_memory_node_id, is_deleted);
CREATE INDEX {{prefix}}memory_edges_idx_type ON {{prefix}}memory_edges (edge_type, edge_context, edge_status);
```

---

## 6. MemoryExportService

**Canonical implementation:** `lupo-includes/classes/MemoryExportService.php` (PDO_DB, named placeholders, `fetchRow` / `fetchAll`).

### 6.1 Path and slug

- **Directory:** `lupo-memory/{YYYY}/{MM}/` where `YYYY` and `MM` come from **`created_ymdhis`** (first four / next two digits of the **effective** packed UTC used for export).
- **`created_ymdhis = 0` (or too short):** **`MemoryExportService::createdYmdhisForExportPath`** substitutes **`19700101000000`** so mirrors land under **`lupo-memory/1970/01/`** (pre-history), while the DB row keeps **`0`**.
- **Filename:** `{slug}.json` where **`slug`** = `generateSlug(effective_created_ymdhis, owner_type, owner_actor_id, memory_type, memory_key)` — no `memory_slug` column in the database.
- **Export payload** includes `memory_node_id`, `created_ymdhis`, `content_hash`, decoded `memory_value` / `context_json` when JSON, and **`edges`** from `lupo_memory_edges` for the same `memory_node_id`.

### 6.2 `generateSlug` (deterministic stem)

```php
public function generateSlug($createdYmdhis, $ownerType, $ownerActorId, $memoryType, $memoryKey) {
    $createdYmdhis = (string) $createdYmdhis;
    $date = strlen($createdYmdhis) >= 8 ? substr($createdYmdhis, 0, 8) : '00000000';
    $time = strlen($createdYmdhis) >= 14 ? substr($createdYmdhis, 8, 6) : '000000';
    $safeKey = str_replace(array(':', '/', ' ', '\\'), '_', (string) $memoryKey);
    return sprintf(
        '%s_%s_%s_%s_%s_%s',
        $date,
        $time,
        $ownerType,
        (string) $ownerActorId,
        $memoryType,
        $safeKey
    );
}
```

### 6.3 Removal mirror on soft delete

When a node no longer qualifies for export (`is_deleted = 1` or removed), callers should invoke **`removeMirrorFileForNode($memoryNodeId)`**, which loads the row (including soft-deleted) to recompute the slug path and **`unlink`** the mirror file.

### 6.4 Trigger export on DB write

```php
$exportService = new MemoryExportService();
$exportService->exportNode($memoryNodeId);
```

### 6.5 Export timing strategy (phased)

| Phase | Behavior |
|-------|----------|
| **Phase 1 (MVP)** | **Synchronous** export immediately after a successful DB write to `lupo_memory_nodes` (or in the same request via `register_shutdown_function` to avoid nested writes during the insert). Mirror files stay consistent with the database without a queue. |
| **Phase 2 (optional)** | **Asynchronous** export via a work queue or batch worker if volume or latency requires it; DB remains source of truth, filesystem may lag briefly. |

Defer queue design, locking, and retry policy to implementation; Phase 1 does not require them.

### 6.6 Export path for seed / immemorial rows

When **`created_ymdhis = 0`** (or non-numeric / too short for **`YYYYMM`**), **`MemoryExportService`** uses an **effective** timestamp **`19700101000000`** for **directory + slug** only. Mirrors land under **`lupo-memory/1970/01/{slug}.json`**. The exported JSON still reflects the **database** values for **`memory_node_id`** and **`created_ymdhis`** (unchanged).

| Record | `memory_node_id` | `created_ymdhis` | Mirror path (illustrative stem) |
|--------|------------------|------------------|----------------------------------|
| Seed root memory | `1` (example) | `0` | `lupo-memory/1970/01/19700101_000000_actor_1_root_….json` |
| Runtime observation | `202604081200001234` | `20260408120000` | `lupo-memory/2026/04/20260408_120000_actor_116_observation_….json` |

---

## 7. Filesystem Structure (Preserved)

The `lupo-memory/` directory structure remains **exactly the same** for IDE/Claude:

```
lupo-memory/
├── 1970/
│   └── 01/
│       └── 19700101_000000_actor_1_root_actor_root_context.json   # seed / created_ymdhis = 0
├── 2026/
│   ├── 03/
│   │   ├── 20260331_120000_actor_1_root_actor_root_context.json
│   │   ├── 20260331_120001_actor_1_observation_kairos_observation_login_errors.json
│   │   └── 20260331_120005_actor_1_consolidated_kairos_memory_login_errors.json
│   └── 04/
│       ├── 20260401_080000_actor_2_root_actor_root_context.json
│       └── 20260401_080001_actor_2_observation_kairos_observation_auth_fix.json
└── ...
```

**Export path for seed / immemorial rows:** When **`created_ymdhis = 0`**, the mirror uses **year 1970, month 01** so all pre-history exports stay in one directory, distinct from runtime-dated trees. Slug stem still encodes owner, type, and key (see **`generateSlug`**).

**Slug format:** `YYYYMMDD_HHIISS_{owner_type}_{owner_id}_{memory_type}_{memory_key}.json`

Example: `20260331_120000_actor_1_root_actor_root_context.json`

---

## 8. Long-Term Memory Archiving (Option B)

Runtime-shaped **`memory_node_id`** values embed a calendar year in the first four digits (raw **`IdGenerator::generate()`**, years **2000–2099** per **`IdGenerator::validateFormat`**). To move nodes out of the default “active runtime” band **without** losing data or breaking sort order, **Option B** assigns a **deterministic long-term identity** using **`toCanonicalId($runtimeId)`** (subtract **1000** from the embedded year when **≥ 2000**). The result remains a valid **`BIGINT`** id, stays lexicographically sortable with other ids, and maps cleanly to **`lupo-memory/YYYY/MM/`** export paths (which derive **`YYYY`** / **`MM`** from **`created_ymdhis`**, which MUST stay the **14-digit prefix** of **`memory_node_id`** per §4.0).

**Trust encoding:** Archived ids fall in the **1000–1999** year band — the same **chronological long-term** band as **living canonical** / consolidated rows (**§4.2**). That band encodes **durable, high-trust** ids; rows remain **mutable** (**UPDATE** + **`updated_ymdhis`**) as **living canonical** unless product policy freezes a specific row. Treat **`archived_to`** edges and **`memory_type` / `context`** as the **source of truth** for *why* the id landed in that band (archive vs merge vs promotion).

### 8.1 Era ranges

| Era | Year in id (first 4 digits) | Typical `memory_node_id` | Export path root |
|-----|----------------------------|---------------------------|------------------|
| **Seed / pre-history** | Not timestamp-shaped, or **`created_ymdhis = 0`** | Low reserved ids (install) | **`lupo-memory/1970/01/`** (see §6.6) |
| **Living canonical / long-term** (merged, archive, promoted) | **1000–1999** | e.g. **`102604081200001234`** (`toCanonicalId` of a **2026** runtime id) | **`lupo-memory/{YYYY}/MM/`** where **`YYYY`** is the **canonical** embedded year (**1026** in the example) |
| **Runtime / staging (raw generator)** | **2000–2099** | e.g. **`202604081200001234`** | **`lupo-memory/2026/04/`** (example) |

**Illustrative conversion**

- Original runtime id: **`202604081200001234`** (embedded year **2026**)
- After **`toCanonicalId`**: **`102604081200001234`** (embedded year **1026**; remainder of digits unchanged)
- Mirror file (slug stem still from node fields): e.g. **`lupo-memory/1026/04/20260408_120000_actor_116_….json`** — directory year comes from **`created_ymdhis`** after archive (**`10260408120000`** → **1026 / 04**).

### 8.2 Archive operation (normative sequence)

1. **Soft-delete** the original row: **`is_deleted = 1`**, **`deleted_ymdhis`** set (packed UTC); optionally remove mirror via **`MemoryExportService::removeMirrorFileForNode()`** for the **original** id.
2. **Insert** a **new** row in **`lupo_memory_nodes`** with **`memory_node_id = toLongTermId(originalId)`**, **`created_ymdhis`** = first **14** digits of that new id (same rule as §4.0), copy payload fields as needed, **`is_deleted = 0`**.
3. Insert a row in **`lupo_memory_edges`**: **`edge_type = 'archived_to'`**, **from** original node (still queryable as soft-deleted) **to** the new archived node; set **4D** columns (**`edge_context`**, **`edge_status`**, **`edge_direction`**) per install + §5.2; **`review_reason`** if status requires it.
4. Call **`MemoryExportService::exportNode($archivedMemoryNodeId)`** so the archived node’s mirror exists under the **1026–2025** tree.

**Restore (reverse):** apply the inverse transform on **`memory_node_id`** (add **1000** to the year when **`isLongTermId($id)`**), re-insert or update rows, link with an edge (e.g. **`restored_from`**) — specify in implementation; **PRD 24** defines CLI entrypoints.

### 8.3 PHP conversion helpers (reference)

**Canonical name:** **`toCanonicalId`** — forward transform from **staging-shaped** (**embedded year ≥ 2000** on 18-digit ids) to **living canonical / long-term-shaped** (**embedded year 1000–1999**). **`toLongTermId`** in Option B is the **same transform** (alias for archive operations).

```php
/**
 * @param int|string $stagingId 18-digit IdGenerator-style id (or shorter non-runtime id)
 * @return int|string Embedded year minus 1000 when year >= 2000; else unchanged
 */
function toCanonicalId($stagingId) {
    $idStr = (string) $stagingId;
    if (strlen($idStr) < 4) {
        return $stagingId;
    }
    $year = (int) substr($idStr, 0, 4);
    if ($year >= 2000) {
        $newYear = $year - 1000;
        return (string) $newYear . substr($idStr, 4);
    }
    return $stagingId;
}

/** Option B archive: identical forward transform. */
function toLongTermId($runtimeId) {
    return toCanonicalId($runtimeId);
}

/**
 * True for 18-digit ids whose embedded year is 1000–1999 (canonical / long-term band).
 */
function isLongTermId($id) {
    $idStr = (string) $id;
    if (strlen($idStr) !== 18) {
        return false;
    }
    $year = (int) substr($idStr, 0, 4);
    return ($year >= 1000 && $year <= 1999);
}
```

**Restore (inverse):** when **`isLongTermId($id)`**, add **1000** to the embedded year and concatenate the remaining **14** digits; re-insert or update per **§8.2** / **PRD 24**.

Use **string** concatenation for ids on the wire if any environment risks **float** rounding; production norm is **64-bit PHP** (PRD 00 / **`PHP_INT_SIZE`**).

### 8.4 Querying across eras

- **Active runtime-shaped only:** `WHERE is_deleted = 0` and **first four digits of `memory_node_id` in 2000–2099** (raw **`IdGenerator`** band), or equivalent filter on **`created_ymdhis`** prefix.
- **Living canonical / long-term band:** **first four digits in 1000–1999** on **18-digit** ids, or **`isLongTermId(memory_node_id)`**.
- **Include both:** `UNION` or **`OR`**, or traverse **`lupo_memory_edges`** where **`edge_type IN ('archived_to', 'restored_from', 'consolidated_into', …)`**.
- **Original ↔ archived:** follow **`archived_to`** from soft-deleted original to replacement row (or reverse for restore).

---

## 9. IDE/Claude Read Flow (Unchanged)

Claude Code can still read memory files the same way:

```bash
# List memory files by date
ls lupo-memory/2026/04/

# Read a specific memory node
cat lupo-memory/2026/03/20260331_120000_actor_1_root_actor_root_context.json

# Search across all memory
grep -r "login_errors" lupo-memory/
```

**The only change:** Files are now **mirrors** of the database, not the source of truth. But for read-only IDE access, that's fine.

---

## 10. Migration Path

### 10.1 Migration Script: `lupo-scripts/migrate_memory_to_unified_graph.php`

```php
<?php
/**
 * PRD 38 Migration: Import existing JSON files to database, then set up export mirror
 */

class MemoryMigration38 {
    // ... (similar to previous version, but imports files to DB first)
    
    private function importJsonFile($filePath, $data) {
        $memoryNodeId = IdGenerator::generate();
        $createdYmdhis = (int) IdGenerator::extractTimestamp($memoryNodeId);
        $updatedYmdhis = isset($data['updated_ymdhis']) ? (int) $data['updated_ymdhis'] : $createdYmdhis;

        $this->db->query(
            "INSERT INTO {$this->prefix}memory_nodes
             (memory_node_id, created_ymdhis, updated_ymdhis, owner_actor_id, owner_type, memory_type, memory_key,
              memory_value, context, status, content_hash, context_json, expires_ymdhis, is_deleted, deleted_ymdhis)
             VALUES (:mid, :c, :u, :oaid, :otype, :mtype, :mkey, :mval, :ctx, :st, :chash, :cjson, :exp, 0, 0)",
            array(
                'mid' => $memoryNodeId,
                'c' => $createdYmdhis,
                'u' => $updatedYmdhis,
                'oaid' => isset($data['owner_actor_id']) ? $data['owner_actor_id'] : $this->extractActorId($filePath),
                'otype' => isset($data['owner_type']) ? $data['owner_type'] : 'actor',
                'mtype' => isset($data['memory_type']) ? $data['memory_type'] : 'root',
                'mkey' => isset($data['memory_key']) ? $data['memory_key'] : 'actor:root_context',
                'mval' => json_encode(isset($data['memory_value']) ? $data['memory_value'] : array()),
                'ctx' => isset($data['context']) ? $data['context'] : 'doctrine',
                'st' => isset($data['status']) ? $data['status'] : 'supported',
                'chash' => hash('sha256', json_encode($data)),
                'cjson' => json_encode(isset($data['context_json']) ? $data['context_json'] : array()),
                'exp' => isset($data['expires_ymdhis']) ? (int) $data['expires_ymdhis'] : 0,
            )
        );

        return $memoryNodeId;
    }
    
    public function run() {
        // 1. Import all existing JSON files to database
        $this->importAllJsonFiles();
        
        // 2. Run full export from DB to filesystem (rewrites all files with new slug format)
        $exportService = new MemoryExportService();
        $count = $exportService->fullExport();
        
        echo "Exported {$count} nodes to filesystem mirror\n";
    }
}
```

---

## 11. Amendments to Existing PRDs

### 11.1 Amendment to PRD 00 section 5.7

**Original text:**
> *"The file path for a memory node is constructed as: `lupo-memory/YYYY/MM/{memory_slug}.json`"*

**Amended text:**
> *"The canonical source of truth for memory nodes is the `lupo_memory_nodes` database table. A read-only export mirror is maintained at `lupo-memory/YYYY/MM/{slug}.json` for IDE and filesystem browsing (slug derived from `created_ymdhis` and node fields). All writes MUST go to the database first; the `MemoryExportService` synchronizes to disk."*

### 11.2 Amendment to PRD 24 section 5

**Original text:**
> *"Register root memory node in `lupo_memory_nodes`; file stored at `lupo-memory/YYYY/MM/{memory_slug}.json`"*

**Amended text:**
> *"Create root memory node by inserting a row into `lupo_memory_nodes` with `memory_type = 'root'`. The `MemoryExportService` will automatically write the mirror file to `lupo-memory/YYYY/MM/{slug}.json` using `created_ymdhis` and `generateSlug()`."*

### 11.3 Amendment scope — PRD 07 (`07_agents_faucets.md`)

**Scope:** Remove or replace any remaining **`memory.json`** workspace references with the unified model: canonical row in **`lupo_memory_nodes`**, mirror file under **`lupo-memory/YYYY/MM/{slug}.json`**, and **`MemoryExportService::exportNode()`** after writes (Phase 1: synchronous per section 6.5).

### 11.4 Amendment scope — PRD 15 (`15_actors.md`)

**Scope:** Align actor workspace documentation with this PRD: **`memory_node_id`** from **`IdGenerator`**, **`created_ymdhis`** equal to the 14-digit PK prefix, derived **slug** for export filenames (no separate `memory_slug` column), and memory graph edges as implemented in **`lupo_memory_edges`** / **`lupo_edges`** per the reconciled single-graph decision.

### 11.5 Reference alignment — PRD 37 (`37_kairos_channel_memory_consolidation.md`)

**Scope:** KAIROS persists channel memory to **`lupo_memory_nodes`** (and related edges) first; after insert/update, invoke export so IDE-visible mirrors stay current. Detailed consolidation behavior remains in PRD 37; this PRD only binds the **write path + mirror** contract.

---

## 12. Success Criteria

| Criterion | Validation |
|-----------|------------|
| `lupo_memory_nodes` and `lupo_memory_edges` tables added | ✅ Schema present |
| Existing JSON files imported to database | ✅ Migration runs successfully |
| `MemoryExportService::fullExport()` recreates all files | ✅ Files match original content |
| New memory writes create DB row + mirror file | ✅ Both updated |
| IDE/Claude can read files from `lupo-memory/YYYY/MM/` | ✅ Same as today |
| Graph queries work on database | ✅ Edges traversable |
| KAIROS consolidates using DB graph | ✅ Observations linked to root |

---

## 13. Summary

| Concern | Resolution |
|---------|------------|
| **Constitutional violation** | Database becomes source of truth; edges fully supported |
| **IDE/Claude needs files by date** | `lupo-memory/YYYY/MM/` preserved as read-only export mirror |
| **Queryable graph** | `lupo_memory_nodes` + `lupo_memory_edges` with full dimensions |
| **Backward compatibility** | Existing JSON files imported; export service rewrites in consistent format |
| **Performance** | Export can be async (shutdown register or queue) |

**You get both: graph database + date-browsable files.**

---

**Status:** DRAFT — awaiting review

**Next actions:**
1. Reconcile **01 / 15 / 24 / 37** and actor docs with this PRD (PK name, edges table, slug rule).
2. LILITH audit
3. WOLFIE approval
4. Regenerate TOONs after install SQL is frozen
5. Ship migration script for legacy JSON on disk

---

## 14. IDE prompt fragment (timestamp consistency)

Use when auditing inserts/updates across the codebase:

```
We need to ensure all tables in Lupopedia follow the same timestamp pattern.

Rules:
1. Every table MUST have a created_ymdhis BIGINT NOT NULL DEFAULT 0 column
2. Every table MUST have an updated_ymdhis BIGINT NOT NULL DEFAULT 0 column
3. Every table MUST have is_deleted TINYINT NOT NULL DEFAULT 0 and deleted_ymdhis BIGINT NOT NULL DEFAULT 0
4. Primary keys are generated by IdGenerator::generate() which returns YYYYMMDDHHIISS + 4-digit suffix (see IdGenerator.php)
5. When inserting a row, created_ymdhis MUST be set to the same 14-digit timestamp prefix from IdGenerator::generate() (IdGenerator::extractTimestamp)
6. When updating a row, updated_ymdhis MUST be set to gmdate('YmdHis')

For lupo_memory_nodes:
- memory_node_id = IdGenerator::generate()
- created_ymdhis = (int) IdGenerator::extractTimestamp(memory_node_id)
- updated_ymdhis = gmdate('YmdHis') on update (or equal to created_ymdhis on insert)

Update any code that assumes created_ymdhis is set independently of the PK timestamp prefix.
```