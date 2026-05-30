---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/38_A-i_MEMORY_UNIFICATION.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/38_A-i_MEMORY_UNIFICATION.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/38_memory_unification.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/memory-unification
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 38_A-i_00_A-i_FORBIDDEN_AND_WHY_38_A_MEMORY_UNIFICATION
  title: 'PRD 38: Memory Unification ??? Constitutional Graph Compliance (Revised)'
  summary: null
---
<!-- ASCII_ART_BLOCK -->
. . . . . . . . . ._________________ LUPOPEDIA Semantic Operating System _________________
. ./ \ ` ` `_\-\ . | A two-dimensional, finite, constitutional PRD documentation
. '/| \-''-/_ / . | architecture that lets docs build software. PRDs reference
. { . , . , . ,\ .| other PRDs, forming clusters that define behavior, truth,
. / . , . , . , \ | limits, and system identity. Each file carries a header that
./ , . "O. |"O. } | records the exact prd_cluster (reading order), the full
_| . , . , \ \ ;. | transcript_jsonl dialog, and atoms_toon for canonical truth,
. '\. . , . \ \' . | ensuring deterministic lineage and reproducibility.
.. '\_ . , . \__\ | https://www.lupopedia.com/
., , ''-_ , {\__/}|
. . , . / '-.____'| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com
., , /___________________________________________________________________________________
.. , _'
___-'
<!-- /ASCII_ART_BLOCK -->

<!--HUMAN_SEMANTIC -->
This file belongs to:
 

See also:
 
<!-- /HUMAN_SEMANTIC -->
# PRD 38: Memory Unification ???????? Constitutional Graph Compliance (Revised)

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## 1. Purpose

This PRD resolves the **constitutional violation** between doctrine (unified graph) and implementation (split DB + files) while **preserving filesystem access** for IDE agents like Claude Code.

**The real requirement:** You need to browse memory files by date in `memory/YYYY/MM/` so Claude Code can read them easily. That's valid and must be preserved.

**The solution:** **Database as source of truth, filesystem as read-only export mirror.**

- All writes go to database first (`lupo_memory_nodes`, `lupo_memory_edges`)
- An export service mirrors DB rows to `memory/YYYY/MM/{slug}.json` (slug derived in PHP; see section 5)
- IDE/Claude reads from filesystem (same experience as today)
- Graph queries, KAIROS consolidation, and edge traversal use the database

**This gives you both:**
1. ??????? **Queryable graph** with typed edges, status, context, direction
2. ??????? **Filesystem access** by date for IDE agents

---

## 2. Constitutional Authority

This PRD amends **PRD 00** section **5.7** to clarify:

> *"The canonical source of truth for memory nodes is the `lupo_memory_nodes` database table. The filesystem path `memory/YYYY/MM/{slug}.json` is a **read-only export mirror** maintained by the `MemoryExportService`. The filename stem is generated from `created_ymdhis` and node fields (not a separate DB slug column). All writes MUST go to the database first; the export service synchronizes to disk."*

---

## 3. Architecture: Database + Export Mirror

### 3.0 Book engagement mirror (`lupo_contents`)

Graph truth stays in **`lupo_memory_nodes`** / **`lupo_memory_edges`** (this PRD). For **likes, comments, shares, and book UI** keyed by **`content_id`**, implementers MUST also maintain a **`lupo_contents`** row per memory node that should participate in that layer, per **PRD 50 ??4.17** (memory nodes as content). The filesystem export remains a **read-only mirror** for IDE agents; **`lupo_contents`** is an **engagement registry**, not a second source of graph edges.

**When to create the `lupo_contents` row:**

- **Immediately** when a memory node represents user-facing content (PRDs, doctrine, documentation, code files).
- **Optionally** for internal memory nodes (observations, temporary staging) ??? product policy decides.
- The **`content_id`** SHOULD equal **`memory_node_id`** unless policy requires a separate ID space (see **PRD 50 ??4.17.3**).

### 3.0.1 Webroot exposure and on-disk mirrors (WOLFIE ??? 2026-04-12)

Lupopedia uses a **subfolder install**; most shipped files can sit under a **web-accessible** path. There is **no** framework sandbox that hides ???server??? trees from HTTP by default.

- **`memory/`** exports, repo docs, and **`scripts/*.py`** under a served directory MUST be treated as **potentially world-readable** unless ops **explicitly** blocks those paths or extensions.
- **Non-PHP** tooling (e.g. **`.py`**) is **not** a normative browser-executed surface; typical Apache behavior is **plain text or download**, not CGI (do **not** assume execution).
- **Secrets** belong **only** in **`lupopedia-config.php`** (prefer **above** docroot). Do **not** place credentials in mirror JSON, headers, or scripts to speed up agents.

**Pairing with this PRD:** DB remains **write authority**; mirrors remain **exports**. **Public readability** increases risk ??? it does **not** transfer authority to the filesystem.

### 3.0.2 WOLFIE - Relationship: `memory_nodes` and `contents`

**Attribution:** WOLFIE (orchestrator). This subsection clarifies how **`lupo_memory_nodes`** and **`lupo_contents`** line up when the same logical artifact is both a **graph node** and **engageable content**. It complements **PRD 50 ??4.17** (mirror row and engagement loop).

#### Column alignment (when a memory node is also content)

| `lupo_memory_nodes` | `lupo_contents` | Relationship |
|---------------------|-----------------|--------------|
| `memory_node_id` (PK) | `content_id` (PK) | **One-to-one** when the memory node is mirrored as content and product policy **unifies** ids (see **PRD 50 ??4.17.3** if a distinct `content_id` is required). |
| `memory_key` (path) | `file_path_from_root` | **Match** when the memory node points at a repo file (export path or canonical `file_path_from_root`). |
| `memory_type` | `content_type` | **Map** by convention (e.g. `prd` -> `prd`, `doctrine` -> `doctrine`). |
| `owner_actor_id` | `actor_id` | Execution provenance ??? records which actor wrote the row. Does NOT define context scope or ownership. Context scope is defined by `channel_key` + `thread_id` + artifact lineage. See `docs/doctrine/CONTEXT_AUTHORITY_MODEL.md`. |
| `created_ymdhis` | `created_ymdhis` | **Match** on mirror upsert. |
| *(no slug column)* | `slug` | **Slug lives on `lupo_contents` only**; memory nodes do not carry a separate slug column. |

**Slug location:** **`lupo_memory_nodes`** does **NOT** have a **`slug`** column. Slugs for content identification live in **`lupo_contents.slug`**. For memory nodes that are **never** mirrored to **`lupo_contents`**, slugs are derived from **`memory_key`** or **`generateSlug()`** at export time only.

## 4. Collection Edge Predicates (AI Collections)

### 4.1 Purpose
Define standardized edge predicates for AI collections that sync with human UI collections (see PRD 73 ??8).

### 4.2 Core Collection Predicates

| Predicate | Direction | Weight Range | Purpose | Example |
|-----------|-----------|--------------|---------|---------|
| `collection_contains` | collection ??? item | 0.1-1.0 | Item belongs to collection | Collection node ??? PRD node |
| `related_to` | item ??? item | 0.1-0.9 | Semantic relationship | PRD 38 ??? PRD 51 |
| `groups_with` | item ??? item | 0.1-0.8 | Co-occurrence pattern | PRD 16 ??? PRD 72 |
| `semantically_similar` | item ??? item | 0.1-0.95 | Content similarity | PRD 29 ??? PRD 31 |
| `references` | item ??? item | 0.5-1.0 | Explicit reference | PRD 99 (limits) ??? PRD 29 (`99_limits_for_everything_and_why.md`) |

### 4.3 Edge Context Schema

```json
{
  "edge_type": "collection_contains",
  "context_json": {
    "source": "human_curated|ai_discovered|hybrid",
    "collection_id": 12345,
    "confidence_score": 0.95,
    "discovery_method": "semantic_similarity|co_occurrence|manual",
    "created_by_actor_id": 1,
    "sync_status": "synced|pending|failed",
    "last_sync_ymdhis": 20260412140000
  }
}
```

### 4.4 Sync Integration

When human collections are created/updated (PRD 73):
1. Create `collection_contains` edges with weight 1.0
2. Set `source: "human_curated"` in context
3. Mark `sync_status: "synced"`

When AI discovers collections:
1. Create edges with confidence-based weights
2. Set `source: "ai_discovered"` in context
3. Mark `sync_status: "pending"` until human approval

#### LUPOPEDIA HEADERS: sources when the memory node represents a file (e.g. a PRD)

When emitting or resolving headers for an artifact that exists as **both** a memory node and a content row, use the following **sources** (field names per **PRD 16**; columns per **`install_new_lupopedia.sql`** / TOON):

| Header field | Source table | Column / rule |
|--------------|--------------|----------------|
| `content_id` | `lupo_contents` | `content_id` (equals `memory_node_id` when ids are unified). |
| `pk_id` | `lupo_contents` | For PRD-type (and similar) content, the product **primary key** for that artifact class. **Decision:** For simplicity and consistency, **`pk_id`** SHOULD equal **`content_id`** when the content row is the canonical record for that artifact. If a separate numeric identifier is required (e.g., PRD number **38**), it MUST be stored in **`lupo_contents`** JSON (e.g. **`atom_mappings.prd_id`**) and the header generator MUST resolve it from there. Implementation MUST be consistent across all content of the same **`artifact_type`**. |
| `pk_slug` | `lupo_contents` | `slug` |
| `title` | `lupo_contents` | `title` |
| `status` | `lupo_contents` | `status` |
| `parent_pk_id` | `lupo_memory_edges` | **`to_memory_node_id`** on an edge with **`edge_type` = `'references'`** (parent / reference target in graph terms; verify direction against your edge semantics). |
| `summary` | `lupo_contents` | `description`, or a **summary** key inside an approved JSON column on the row (TOON: e.g. `content_sections`, `atom_mappings`; do not assume a `metadata_json` column until install defines it). |
| `module` | `lupo_contents` | From JSON sidecar or inferred from **`channel_id`** / routing policy. |
| `memory_key` | `lupo_memory_nodes` | `memory_key` |
| `artifact_type` | `lupo_memory_nodes` | `memory_type` |
| `artifact_kind` | `lupo_memory_nodes` | `context`, or **`kind`** inside **`context_json`** / approved JSON when present. |

**Note on `pk_id` vs `content_id`:** For this PRD's scope, **`pk_id`** MAY equal **`content_id`** OR be a separate numeric identifier stored in **`lupo_contents`** JSON. The chosen approach MUST be consistent across all content of the same **`artifact_type`**. The header validator (**`validate_lupopedia_headers_universal.py`**) accepts either pattern as long as the YAML output matches **PRD 16** field types.

**Normative:** Header writers MUST still validate against **`validate_lupopedia_headers_universal.py`** and **PRD 16**; this table is the **semantic map** between memory and content layers, not a bypass of envelope rules.

### 3.1 Data Flow

```
???????-----------------------------------------------------------------------------+
|                           WRITE PATH (Primary)                              |
+-----------------------------------------------------------------------------???????
|                                                                             |
|   KAIROS --???????? lupo_memory_nodes --???????? MemoryExportService --???????? .json file      |
|   ROSE                              (background/async)     (YYYY/MM/)       |
|   Actor Onboarding                                                          |
|   THOTH                                                                     |
|                                                                             |
+-----------------------------------------------------------------------------+

???????-----------------------------------------------------------------------------+
|                           READ PATH (Two Options)                           |
+-----------------------------------------------------------------------------???????
|                                                                             |
|   OPTION A (IDE/Claude):  Read .json file directly from disk               |
|   OPTION B (Graph query):  Query lupo_memory_nodes + lupo_memory_edges      |
|                                                                             |
+-----------------------------------------------------------------------------+
```

### 3.2 Key Principle

| Operation | Source of Truth | Secondary |
|-----------|-----------------|-----------|
| **Write** (create, update, delete) | Database only | Export service mirrors to disk |
| **Read** (graph traversal, edges, status) | Database only | N/A |
| **Read** (IDE browsing by date) | Filesystem (mirror) | Falls back to DB if missing |

**The filesystem is NEVER written directly.** All writes go through the database.

### 3.3 Memory Authority and Arbitration Model

This section is the single arbitration contract for memory authority across authored files, `memory_toon`, and database rows.

#### 3.3.1 Authoritative surface by stage

| Stage | Authoritative surface | Secondary surfaces | Notes |
|------|------------------------|--------------------|-------|
| **Unlinked artifact** (`content_id` null/missing) | **Authored file header + path context** | `memory_toon`, DB (if present) | Discovery state; runtime may create canonical DB linkage. |
| **Linked artifact** (`content_id` present and valid) | **Database row** (`lupo_contents` and mapped memory rows) | Authored file, `memory_toon` | Reconciliation state; file and sidecar must align to DB identity. |
| **Repair state** (`content_id` present but invalid/mismatch) | **No implicit trust promotion** | File and DB treated as conflicting inputs | ANUBIS/runtime repair is required before normal authority is restored. |

`memory_toon` is never an independent root authority over identity. It is a structured memory/metadata companion whose authority is derived from the current stage and validated against header + DB contracts.

#### 3.3.2 Arbitration states

- **File-first:** `content_id` is null or missing. File/header/path drive discovery and initial linkage.
- **Database-first:** `content_id` is present and validates to the expected DB row. DB is the canonical identity anchor.
- **Repair-state:** `content_id` is present but fails DB validity checks (missing row, mismatch, invalid linkage). Do not treat as database-first until repaired.

State selection is deterministic and must not use heuristics.

#### 3.3.3 Direction of truth

- **DB overrides file** when artifact is database-first or after repair commits a validated linkage.
- **File overrides DB** only in file-first discovery mode before canonical linkage exists, or when runtime explicitly executes a repair workflow that treats file content as the recovery source and then writes validated DB state.
- **No silent bidirectional merge:** transitions must be explicit and logged.

#### 3.3.4 Mirror behavior

- **Filesystem read-only mirror mode (default):** Type B `.json` export mirrors and derived sidecars are read models of DB state; direct mirror edits are non-authoritative.
- **Filesystem source-eligible mode (bounded):** authored artifacts in file-first state may act as source input for discovery/repair workflows.
- **Post-link rule:** once linked and valid, filesystem artifacts are reconciliation targets unless a formal repair workflow declares otherwise.

#### 3.3.5 Validator vs runtime responsibility

- **Validator guarantees (structural + deterministic classification):**
  - Header shape, required fields, path/header coherence, and deterministic state classification inputs.
  - Detection/reporting of file-first, database-first-candidate, and repair indicators.
- **Runtime responsibilities (state mutation + repair):**
  - DB existence/match verification in live execution context.
  - Creation/update of canonical linkage and write-back of repaired headers/sidecars.
  - ANUBIS/repair flows, retries, and no-partial-success handling.

Validator output is not a substitute for runtime repair execution; runtime execution must still enforce canonical state transitions and audit trail requirements.

#### 3.3.6 Deterministic Path and Traversal Enforcement (4.1.7 alignment)

Path and traversal behavior MUST be deterministic.

Required:

* resolve memory pointers from explicit declared paths
* enforce channel/path coherence before traversal
* traverse only with declared bounds (edge allowlist, start set, max depth)

Forbidden:

* path guessing
* filesystem discovery scans used as authority
* unbounded graph traversal

If deterministic resolution cannot be established, processing MUST stop and report failure rather than infer fallback behavior.

Memory path format (canonical):

memory/{tier}/{channel_key}/{thread}/{YYYY}/{MM}/

Memory Resolution MUST:

* use deterministic path construction
* NOT scan directories
* NOT guess locations

---

## 4. Trust tiers, scope, and roadmap

## Trust Tiers in Memory (Chronological Trust Ladder Integration)

---

## Anchored Truth Doctrine: The Sieve and THOTH [ALERT] Protocol

> **Hierarchy of Truth ??? The Sieve:**
> - **1026 Nodes (The Ancestors):** Finalized, merged, verified FACT. These are the benchmark for all truth in Lupopedia.
> - **2026 Nodes (The Descendants):** Temporary staging, active work, unverified "thoughts." Useful for drafting, but not authoritative.
> - **THE LAW:** If a 2026 node contradicts a 1026 node, the 2026 node is **WRONG** until a **Captain???s Amendment** is issued. No AI, agent, or human may override a canonical ancestor with a descendant unless the amendment is explicit and logged.

> **THOTH [ALERT] Protocol:**
> - If a contradiction is found between a 2026 and a 1026 node:
>   1. **Fix the Staging Node** if it is hallucinated or logically wrong.
>   2. **Amend the 1026 Node** only with explicit Captain (WOLFIE) approval.
> - **No merge or implementation may proceed until the contradiction is resolved.**
> - **THOTH [ALERT]** is a hard stop for all agents and implementers.

**Recency Bias Trap:**
> Modern AI models and agents must not prefer newer (2026) staging nodes over older (1026) canonical facts. The system is designed so that recency does not override truth. Only a Captain???s Amendment can change a canonical fact.

**Status:** IMPLEMENTED

Memory nodes follow the same trust tier system as all primary keys:

| Tier | PK Band | Path in `memory/` | Mutability | Use Case |
|------|---------|------------------------|------------|----------|
| **Seed** | 0-999,999 | `{channel_key}/seed/{slug}.toon` | Immutable | Install-time truths, constitutional rules |
| **Canonical** | 1,000,000,000,000,000,000 - 1,999,999,999,999,999,999 | `{channel_key}/canonical/{display_year}/{month}/{slug}.toon` | Mutable | Living truth, consolidated memory |
| **Staging** | 2,000,000,000,000,000,000 - 2,999,999,999,999,999,999 | `{channel_key}/staging/{actual_year}/{month}/{slug}.toon` | Temporary | Draft memory, unverified observations |

**Display year for canonical:** `display_year = actual_year - 1000` (e.g., 2026 -> 1026).

This aligns memory files with PK encoding, making trust tier visible in both database and filesystem.

## Channel Scope for Memory

**Status:** IMPLEMENTED (path doctrine); DB enforcement deferred ??? see `docs/doctrine/gaps/MEMORY_CHANNEL_ENFORCEMENT.md`

Memory is not global. The following rules define how memory artifacts are scoped, where they live,
and what controls cross-channel access.

### Memory Artifact Types (Normative)

Two distinct artifact types coexist under `memory/`. They have different paths, formats,
and authority levels. They MUST NOT be confused or mixed.

**Type A ??? Channel-scoped memory (primary authored memory)**

```text
memory/{channel_key}/{trust_tier}/{YYYY}/{MM}/{slug}.toon
```

- Format: `.toon` (TOON serialization per TOON_ORDERING_SPEC.md)
- Scope: bound to a single channel; `channel_key` is required
- Authority: primary authored memory; header field `channel_key` MUST match the first path segment
- Created by: authors, agents, ANUBIS, KAIROS operating within a channel context

Examples:
```text
memory/development/canonical/1026/04/38_memory_unification.toon
memory/headers/staging/2026/04/some-draft.toon
```

**Type B ??? System / global export mirror (non-channel)**

```text
memory/{YYYY}/{MM}/{slug}.json
```

- Format: `.json` (IDE-readable JSON, MemoryExportService output)
- Scope: system-level or non-channel-scoped DB row mirrors; NOT for authored channel memory
- Authority: read-only mirror of `lupo_memory_nodes` rows; DB is source of truth
- Created by: MemoryExportService only; never written directly by agents or authors

Examples:
```text
memory/1970/01/19700101_000000_actor_1_root_actor_root_context.json
memory/2026/04/20260401_120000_actor_2_root_actor_root_context.json
```

**Rule: `.toon` files are always channel-scoped (Type A). `.json` files are always system export
mirrors (Type B). Path format is a reliable indicator of type.**

### Channel Registry

`channels/registry.json` maps `channel_key` to numeric ID and metadata.

### Agent Memory Loading

When an agent starts in a channel, it loads memory only from that channel's scope (Type A path).
Reading memory from a different channel requires explicit cross-channel declaration (see below).

### Cross-Channel Access

Cross-channel memory access MUST be declared explicitly in `channels/registry.json` under
`allowed_cross_channel_memory`. The behavior is:

- **Default: DENY** ??? an agent may not read memory from a channel other than its current context
- **Allowlist only** ??? access requires the target channel key to be listed under
  `allowed_cross_channel_memory` for the requesting channel
- **No silent inheritance** ??? child channels do not automatically inherit parent channel memory

Example registry shape (intent only; full schema is defined in gap tracking):
```json
{
  "channel_key": "development",
  "allowed_cross_channel_memory": ["headers", "trust_ladder"]
}
```

The `allowed_cross_channel_memory` field shape in `channels/registry.json` is not yet
formally specified. See `docs/doctrine/gaps/MEMORY_CHANNEL_ENFORCEMENT.md` Gap 3.

### Why Channel Scope

- Different channels have different contexts (`development`, `headers`, `trust_ladder`)
- Prevents memory pollution across unrelated work
- Enables parallel agent work without interference

### Known Schema Gap

`lupo_memory_nodes` has no `channel_id` or `channel_key` column. Channel scope is currently
enforced at the filesystem path level only (for Type A `.toon` artifacts) and by agent loading
behavior. Adding `channel_key` to the DB schema is a deferred migration. See
`docs/doctrine/gaps/MEMORY_CHANNEL_ENFORCEMENT.md` Gap 1.

## Vector Similarity (Roadmap ???????? Not Yet Implemented)


### Vector Similarity Roadmap

**Status:** Planned (not yet implemented)

**Target:** PostgreSQL + pgvector extension

**Fallback for MySQL:**
- MySQL 8.0 does not support vector similarity natively
- Fallback to application-side cosine similarity with cached embeddings
- Performance acceptable for <10,000 vectors; warn beyond threshold

**Implementation Phases:**
1. Add `embedding_vector` column to `lupo_memory_nodes` (JSON or BLOB)
2. Implement pgvector for PostgreSQL installations
3. MySQL fallback uses PHP cosine similarity calculation
4. Add `has_vector_index` flag for query optimization

**Cross-reference:** PRD 70 (Data Model), PRD 75 (Temporal System)

## Database Compatibility for Memory

**Status:** IMPLEMENTED core graph + PLANNED vectors

| Feature | MySQL | PostgreSQL |
|---------|-------|------------|
| Memory graph (nodes + edges) | Yes | Yes |
| Trust tier PK encoding | Yes | Yes |
| Channel scope | Yes | Yes |
| Vector similarity | No (not mature) | Yes (`pgvector`) |
| Hybrid search (vector + graph) | No | Yes |
| CTE recursion for edge traversal | Limited | Full |

**Decision:** MySQL remains supported for existing Crafty Syntax installs. PostgreSQL is recommended for new installs that need vector search.

Code paths should gate vector logic behind capability checks (for example `$db->supportsVectors()`).

## 5. Timestamps, IDs, and schema parity

### 5.0 `IdGenerator` and `created_ymdhis`

**Runtime rows:** Primary keys for **`lupo_memory_nodes`** / **`lupo_memory_edges`** use **`IdGenerator::generate()`**, which returns an 18-digit string: **14-digit packed UTC** (`YYYYMMDDHHIISS`) **plus a 4-digit suffix** (see `includes/classes/IdGenerator.php`; suffix is CSPRNG-derived, not a monotonic sequence). **Seed rows** are exempt ???????? see **????4.1** and **PRD 00 ????3.2.1**.

**Rules (runtime inserts):**

1. On **INSERT** of a **runtime** row, set **`memory_node_id`** (or **`memory_edge_id`**) to **`IdGenerator::generate()`**. **Seed** inserts use **literal** ids from install/seed SQL ???????? **PRD 00 ????3.2.1**.
2. For **runtime** rows, set **`created_ymdhis`** to the **same** 14-digit prefix as the new PK: use `(int) substr((string) $id, 0, 14)`. **Seed** rows: install UTC or **`0`** per **????5.0.1**.
3. On **INSERT**, set **`updated_ymdhis`** to that same prefix (or to `gmdate('YmdHis')` if you require ???????last touched??????? distinct from creation ???????? default pattern: both equal at insert).
4. On **UPDATE**, set **`updated_ymdhis`** to **`gmdate('YmdHis')`**.
5. **`created_ymdhis`** is therefore **redundant but consistent** with every other Lupopedia table that carries both a time-structured BIGINT PK and a `created_ymdhis` column.

**Seed / pre-existing rows (install + immemorial):**

| Record type | `memory_node_id` (PK) | `created_ymdhis` | Notes |
|-------------|------------------------|------------------|--------|
| **Runtime** | `IdGenerator::generate()` (14-digit UTC + 4-digit suffix) | Same **14-digit** prefix as PK at insert | Default path for actor-created nodes |
| **Seed / system** | Low reserved id (e.g. 1, 2, 3) from **`install_new_lupopedia.sql`** / seed | **Install** packed UTC **or** **`0`** = ???????before temporal tracking??????? | PK is **not** timestamp-shaped; **`created_ymdhis`** carries real install time or **`0`** |

**Filesystem export:** **`MemoryExportService`** builds **`memory/{YYYY}/{MM}/`** from **`created_ymdhis`**. When **`created_ymdhis`** is **`0`** (or too short to form **`YYYYMM`**), the service uses **`19700101000000`** for path and slug ???????? **`memory/1970/01/`** (???????pre-history??????? bucket). The JSON payload still echoes the DB column values (`memory_node_id`, `created_ymdhis` unchanged).

**Example (memory node):**

```php
$memoryNodeId = IdGenerator::generate();
$createdYmdhis = (int) substr((string) $memoryNodeId, 0, 14);

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

### 5.0.1 `lupo_memory_nodes` - seed vs runtime (DDL comments)

Canonical **`CREATE TABLE`** is in **????5.1** (must match **`install_new_lupopedia.sql`**). Comment semantics:

```sql
CREATE TABLE {{prefix}}memory_nodes (
    memory_node_id BIGINT NOT NULL,
    -- Seed / install: low reserved id from install or seed_*.sql (e.g. 1..N)
    -- Runtime: IdGenerator::generate() ???????? YYYYMMDDHHIISS + 4-digit suffix

    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    -- Seed: installation packed UTC OR 0 (0 = pre-existing / before temporal tracking)
    -- Runtime: same 14-digit UTC prefix as memory_node_id at insert

    -- ... remaining columns per ????5.1
);
```

### 5.0.2 Memory trust tiers (Chronological Trust Ladder pattern)

For **timestamp-shaped** **`memory_node_id`** values (**18 digits**, **`IdGenerator`** layout), the **first four digits** are a **calendar year** embedded in the PK. That yields the **Chronological Trust Ladder**: operators and reviewers can read **authority / lifecycle** from the id **without** extra flags. **Constitutional summary:** **PRD 00** ????3.7; canonical doctrine **`docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md`**.

#### 5.0.2.1 `IdGenerator::generate()` vs `toCanonicalId` (normative)

**Reference implementation:** **`includes/classes/IdGenerator.php`** ???????? `generate()` returns an **18-digit** string; **`validateFormat()`** constrains the embedded clock to calendar years **2000????????2099**. Therefore **every fresh generator value is staging-shaped** (embedded year **2000????????2099**).

**Binding rule:**

> **`IdGenerator::generate()` always yields a staging-range id (embedded year 2000????????2099). To persist a new living canonical row (embedded year 1000????????1999), apply `toCanonicalId(IdGenerator::generate())` before `INSERT` unless product policy deliberately keeps a draft staging row.**

**Conversion (pseudocode ???????? implement next to `IdGenerator` or a shared id helper):**

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

- `IdGenerator::generate()` ???????? **`202604081200001234`** (staging-shaped).
- `toCanonicalId('202604081200001234')` ???????? **`102604081200001234`** (living canonical-shaped).
- **First web / runtime row** for an entity may **skip persisting staging**: generate once, transform, `INSERT` canonical only; link install seed ???????? canonical with an edge such as **`canonical_instance_of`** where applicable (**PRD 41**).

**When staging rows still appear:** Observations, drafts, or multi-step merges ???????? `INSERT` with raw **`IdGenerator::generate()`**, then **merge into** existing canonical (**UPDATE**) or **promote** via **`toCanonicalId`** on the staging id and soft-delete staging, per the consolidation steps below.

| Tier | PK shape | Year in id (first 4 digits) | Trust | Mutable? | Typical origin |
|------|----------|----------------------------|-------|----------|----------------|
| **Install / seed** | Low **`BIGINT`**, not timestamp-shaped | N/A (e.g. **1????????2025** per install / registry) | **Highest** (canonical system rows) | **No** (system / install only) | **`install_new_lupopedia.sql`** / seed |
| **Reserved (numeric gap)** | **> 2025** and **< 1e17** if ever used | *implementation-defined* | *Reserved for future allocation* | ???????? | Not used for timestamp ids today |
| **Living canonical** (long-term band) | **18-digit** timestamp layout | **1000????????1999** | **High** ???????? **single source of truth** for the entity / topic | **Yes** ???????? **UPDATE** as new evidence arrives (**accumulated best knowledge**; **stable id**) | KAIROS or operator consolidation into an **existing** canonical row; **????8 Option B** archive (**1026????????2025** ??????? **1000????????1999**) |
| **Staging / runtime** | **18-digit** timestamp layout | **2000????????2099** (`IdGenerator::validateFormat` band) | **Low** (incoming, may be incomplete or superseded) | **Yes** ???????? **short-lived**; merged then **soft-deleted** (or **never inserted** if converted per **????4.2.1**) | Raw **`IdGenerator::generate()`**; observations before consolidation |

**Consolidation flow (normative intent):**

1. Detect duplicate or merge-eligible rows (same logical **`memory_key`** / topic, overlapping **`owner_*`**, policy from KAIROS or operator tooling). **Staging** rows sit in the **2000????????2099** embedded-year band (raw **`IdGenerator`** output per **????4.2.1**).
2. **If no living canonical** exists: **promote** ???????? set **`memory_node_id = toCanonicalId($stagingId)`** (same **???????1000** year transform as **????8.4** / **????4.2.1**), **merge** all **non-null** fields into that row????????s payload, then **`INSERT`** (or replace staging row????????s PK per policy). If policy **creates canonical without ever persisting staging**, use **`toCanonicalId(IdGenerator::generate())`** once and **`INSERT`** directly. Link **each** consumed staging row ???????? canonical with **`lupo_memory_edges`** only (e.g. **`edge_type` = `consolidated_into`**; **`promoted_to`** if install SQL defines it). **KAIROS** lineage (e.g. **`kairos_consolidates_from`**) for the unified memory graph MUST be recorded in **`lupo_memory_edges`** ???????? not in deprecated **`lupo_actor_memory`** or any parallel memory-edge table. Align **`edge_type`** strings with **`install_new_lupopedia.sql`** / TOON and **PRD 37**.
3. **If living canonical already exists:** **UPDATE** that row ???????? merge **non-null** staging fields into the canonical payload, set **`updated_ymdhis`**. Link each staging row ???????? canonical (**`merged_into`** / **`consolidated_into`** per install SQL).
4. **Soft-delete** consumed **staging** rows (**`is_deleted = 1`**, **`deleted_ymdhis`**).
5. **Re-point** any parent / child edges or references in **application logic** (no DB FKs) to the **canonical** **`memory_node_id`** where policy requires.

**Query priority (trust order) for ???????best row??????? selection** (prefer **application-layer** sort; illustration only):

1. **Living canonical** (**1000????????1999** in id) ???????? preferred **current best knowledge** for human-facing answers when present and not soft-deleted.
2. **Staging / runtime** (**2000????????2099** embedded year on raw **`IdGenerator`** ids) ???????? **fallback** observations not yet merged.
3. **Install / seed** (**1????????2025** low ids) ???????? **system defaults**; **lowest** priority for **actor-specific** ???????what did we learn???????? queries (still highest for **global** system invariants).

**Relation to ????8 (Option B):** **Archive** applies the **same `toCanonicalId` / ???????1000 year transform** to **runtime-shaped** ids (embedded year **??????? 2000**), producing ids in the **1000????????1999** embedded-year band (e.g. **2026** ???????? **1026**). Use **`archived_to`** (????8) vs **`consolidated_into`** / merge edges (this subsection) to distinguish **cold archive** from **consolidation**. The **1000????????1999** band is **not** ???????frozen read-only???????; it is **living canonical** unless policy marks a row immutable for a special case.

---

## 5. New Tables (DDL ???????? canonical with `install_new_lupopedia.sql`)

### 5.1 `lupo_memory_nodes` (source of truth)

```sql
CREATE TABLE {{prefix}}memory_nodes (
    memory_node_id BIGINT NOT NULL,
    -- Runtime staging-shaped: IdGenerator::generate() ???????? embedded year 2000????????2099
    -- Runtime living canonical: toCanonicalId(IdGenerator::generate()) ???????? embedded year 1000????????1999 (PRD 38 ????4.2.1)
    -- Seed / install: MAY be a low reserved BIGINT (1, 2, ???????) from install SQL

    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    -- Runtime: same 14-digit UTC prefix as memory_node_id at insert
    -- Seed: installation packed UTC OR 0 (0 = pre-existing / before temporal tracking)

    owner_actor_id BIGINT NOT NULL,
    -- Execution provenance only. Does NOT define context scope.
    -- Context is channel_key + thread_id + artifact lineage (CONTEXT_AUTHORITY_MODEL.md).
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
    -- Allowed storage tokens: unidirectional | bidirectional (PRD 16 ????11.1: sidecar JSON may use outbound/inbound ???????? map on persist)

    weight_hundredths INT NOT NULL DEFAULT 100,
    -- 100 = weight 1.00 (portable integer; avoid DECIMAL in DDL)

    provenance_actor_id BIGINT NOT NULL,
    provenance_tool VARCHAR(64) NOT NULL,

    review_reason VARCHAR(64) DEFAULT NULL,
    active_until BIGINT NOT NULL DEFAULT 0,

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

**TOON mirrors and `lupo_memory_edges`:** When an export TOON embeds rows from **`lupo_memory_edges`** (for example the **`edges`** collection named in **??6.1**), writers MUST serialize those edge records???and any ordered list that carries them???as **integer-indexed ordered arrays**, not as unordered JSON/YAML maps for the primary structure. Normative rules: **[TOON Ordering Specification](../doctrine/TOON_ORDERING_SPEC.md)**; see also **??6.0.1**.

---

## 6. MemoryExportService

**Canonical implementation:** `includes/classes/MemoryExportService.php` (PDO_DB, named placeholders, `fetchRow` / `fetchAll`).

### 6.0 Header-associated memory files are JSON sidecars

For v3 headers, metadata sidecars referenced by `memory_key` are JSON files (`.toon`) and must be written as machine-readable JSON.

- Header file keeps pointer fields (`channel_key`, `trust_tier`, `memory_key`).
- Sidecar JSON stores the rich metadata payload.
- Write path remains DB-first, then export/mirror to filesystem JSON sidecar.
- Readers (IDE agents and tooling) may read these `.toon` JSON files directly for context.

### 6.0.1 TOON as canonical memory serialization

Memory node payloads exported as **`.toon`** (including paths referenced by **PRD 16** **`memory_key`**) **MUST** be serialized as TOON structures using **ordered arrays** with **integer index keys**, as defined in the **Canonical TOON Ordering Specification (v1.0.0)** ([`docs/doctrine/TOON_ORDERING_SPEC.md`](../doctrine/TOON_ORDERING_SPEC.md)). Writers **SHOULD** load an existing **`.toon`** when present, preserve index keys and entry order, modify only affected indices, and re-serialize without reordering untouched entries. Full-file rewrites are reserved for **`toon.version`** changes, index-layout migrations, or deliberate reindexing???normative detail remains in **`TOON_ORDERING_SPEC.md`**.

**Note:** The **`MemoryExportService`** mirror under **`memory/YYYY/MM/{slug}.json`** may remain JSON for IDE readability; where the pipeline emits or pairs a **`.toon`**, that **`.toon`** **MUST** conform to the ordering spec???including any **`edges`** slice materialized from **`lupo_memory_edges`** (**??5.2**). **PRD 16** ??5.2.2 (JSON master ??? TOON derived) still applies.

### 6.1 Path and slug (Type B system/export mirrors only)

**Scope:** This path convention applies exclusively to `MemoryExportService` `.json` mirrors of
`lupo_memory_nodes` DB rows (Type B, non-channel). It does NOT apply to channel-scoped `.toon`
artifacts, which follow `memory/{channel_key}/{trust_tier}/{YYYY}/{MM}/{slug}.toon` as
defined in ?????Channel Scope for Memory??? (Type A).

- **Directory:** `memory/{YYYY}/{MM}/` where `YYYY` and `MM` come from **`created_ymdhis`** (first four / next two digits of the **effective** packed UTC used for export).
- **`created_ymdhis = 0` (or too short):** **`MemoryExportService::createdYmdhisForExportPath`** substitutes **`19700101000000`** so mirrors land under **`memory/1970/01/`** (pre-history), while the DB row keeps **`0`**.
- **Filename:** `{slug}.json` where **`slug`** = `generateSlug(effective_created_ymdhis, owner_type, owner_actor_id, memory_type, memory_key)` ??? no `memory_slug` column in the database.
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

When **`created_ymdhis = 0`** (or non-numeric / too short for **`YYYYMM`**), **`MemoryExportService`** uses an **effective** timestamp **`19700101000000`** for **directory + slug** only. Mirrors land under **`memory/1970/01/{slug}.json`**. The exported JSON still reflects the **database** values for **`memory_node_id`** and **`created_ymdhis`** (unchanged).

| Record | `memory_node_id` | `created_ymdhis` | Mirror path (illustrative stem) |
|--------|------------------|------------------|----------------------------------|
| Seed root memory | `1` (example) | `0` | `memory/1970/01/19700101_000000_actor_1_root_???????.json` |
| Runtime observation | `202604081200001234` | `20260408120000` | `memory/2026/04/20260408_120000_actor_116_observation_???????.json` |

---

## 7. Filesystem Structure

The `memory/` directory contains two coexisting layout patterns corresponding to the two
artifact types defined in ??"Channel Scope for Memory". Both patterns live under the same root
and are distinguishable by extension and path shape.

**Type A ??? Channel-scoped `.toon` artifacts (primary authored memory)**

```text
memory/
+-- development/
|   +-- canonical/
|   |   +-- 1026/
|   |       +-- 04/
|   |           +-- 38_memory_unification.toon
|   +-- staging/
|       +-- 2026/
|           +-- 04/
|               +-- draft-observation.toon
+-- headers/
|   +-- canonical/
|       +-- 1026/
|           +-- 04/
|               +-- 16_lupopedia_headers.toon
```

Path shape: `{channel_key}/{trust_tier}/{YYYY}/{MM}/{slug}.toon`
Display year for canonical tier: `YYYY = actual_year - 1000` (e.g. 2026 -> 1026).

**Type B ??? System/export `.json` mirrors (MemoryExportService, non-channel)**

```text
memory/
+-- 1970/
|   +-- 01/
|       +-- 19700101_000000_actor_1_root_actor_root_context.json   # seed / created_ymdhis = 0
+-- 2026/
|   +-- 03/
|   |   +-- 20260331_120000_actor_1_root_actor_root_context.json
|   |   +-- 20260331_120001_actor_1_observation_kairos_observation_login_errors.json
|   +-- 04/
|       +-- 20260401_080000_actor_2_root_actor_root_context.json
```

Path shape: `{YYYY}/{MM}/{slug}.json` ??? no channel segment.

**Rule: `.toon` = Type A (channel-scoped). `.json` = Type B (system export mirror). Do not mix.**

**Export path for seed / immemorial rows:** When **`created_ymdhis = 0`**, Type B mirrors land
under `memory/1970/01/` (pre-history). Slug stem still encodes owner, type, and key.

**Slug format (Type B):** `YYYYMMDD_HHIISS_{owner_type}_{owner_id}_{memory_type}_{memory_key}.json`

Example: `20260331_120000_actor_1_root_actor_root_context.json`

---

## 8. Long-Term Memory Archiving (Option B)

Runtime-shaped **`memory_node_id`** values embed a calendar year in the first four digits (raw **`IdGenerator::generate()`**, years **2000????????2099** per **`IdGenerator::validateFormat`**). To move nodes out of the default ???????active runtime??????? band **without** losing data or breaking sort order, **Option B** assigns a **deterministic long-term identity** using **`toCanonicalId($runtimeId)`** (subtract **1000** from the embedded year when **??????? 2000**). The result remains a valid **`BIGINT`** id, stays lexicographically sortable with other ids, and maps cleanly to **`memory/YYYY/MM/`** export paths (which derive **`YYYY`** / **`MM`** from **`created_ymdhis`**, which MUST stay the **14-digit prefix** of **`memory_node_id`** per ????4.0).

**Trust encoding:** Archived ids fall in the **1000????????1999** year band ???????? the same **chronological long-term** band as **living canonical** / consolidated rows (**????4.2**). That band encodes **durable, high-trust** ids; rows remain **mutable** (**UPDATE** + **`updated_ymdhis`**) as **living canonical** unless product policy freezes a specific row. Treat **`archived_to`** edges and **`memory_type` / `context`** as the **source of truth** for *why* the id landed in that band (archive vs merge vs promotion).

### ??8.1 Canonical Year Offset Rule

**Display Year = Calendar Year - 1000**

This is constitutional doctrine, not a typo. Lower PK bands encode higher trust for sorting and display.

**Implementation requirements:**

1. All writes to **living canonical** / long-term **memory graph PKs** (18-digit layout) **MUST** satisfy **`IdGenerator::validateTrustLadderPk()`** on the persisted PK and **MUST** use **`toCanonicalId()`** / **`toCanonicalIdSafe()`** when promoting from staging per **??8.4** (PHP helpers) and **PRD 37**.
2. **Staging** tier PKs and generator outputs **MUST** keep embedded **calendar** year (**2000???2099** band) until consolidation.
3. **KAIROS** consolidation records staging???canonical lineage in **`lupo_memory_edges`** using normative edge types such as **`promoted_to`** and **`consolidated_into`** (exact strings per **`install_new_lupopedia.sql`** / TOON and **PRD 37**); **`MemoryPromotionService`** is the reference PHP implementation.
4. Query helpers **MUST** abstract band logic ??? use **`TrustLadder`** (**`includes/classes/TrustLadderQueryHelper.php`**) and explicit edge traversal rather than ad hoc raw SQL on ladder tables alone.

**Rationale:** Lower numeric PK bands sort before higher bands in listings, surfacing verified material before unverified drafts. This is a display / sort-order convention, not a security boundary. Explicit header **`trust_tier`** (**PRD 16**), edge predicates, and **`memory_type`** remain semantic sources of truth.

### 8.2 Era ranges


| Era | Year in id (first 4 digits) | Typical `memory_node_id` | Export path root |
|-----|----------------------------|---------------------------|------------------|
| **Seed / pre-history** | Not timestamp-shaped, or **`created_ymdhis = 0`** | Low reserved ids (install) | **`memory/1970/01/`** (see ??6.6) |
| **Living canonical / long-term** (merged, archive, promoted) | **1000???1999** | e.g. **`102604081200001234`** (`toCanonicalId` of a **2026** runtime id) | **`memory/{YYYY}/MM/`** where **`YYYY`** is the **canonical** embedded year (**1026** in the example) |
| **Runtime / staging (raw generator)** | **2000???2099** | e.g. **`202604081200001234`** | **`memory/2026/04/`** (example) |

**Canonical year offset rule (mandatory):**

All canonical (long-term, merged, or archived) `memory_node_id` values **MUST** encode the year as (calendar year ??? 1000) in the first four digits. For example, a runtime id with embedded year 2026 becomes canonical id 1026xxxx... when archived or promoted. This offset is required for all living canonical and archived ids, and is enforced by all memory graph validators and migration scripts.

**Rationale:**

- The offset (calendar year ??? 1000) creates a distinct, lexicographically sortable band for high-trust, long-term ids (1000???1999), separate from runtime/staging ids (2000???2099).
- This prevents accidental mixing of staging and canonical ids, supports deterministic migration, and enables strict validation of memory graph integrity.
- The offset is not a substitute for explicit trust ladder semantics, but is a required numeric convention for all canonical ids.

**Validation and migration requirements:**

- All memory export, migration, and query logic **MUST** enforce the offset rule for canonical ids.
- Validators **MUST** reject any canonical or archived id whose year is not in 1000???1999, or whose offset does not match the original runtime year minus 1000.
- Migration scripts **MUST** backfill or repair ids to conform to this rule if legacy data is found.
- Query helpers **MUST** use the offset band to distinguish canonical from staging ids, but **MUST NOT** rely on numeric banding alone for trust semantics (see ??4.2 and PRD 43).

**See also:** PRD 16 ??8.1 (header/memory_key year encoding), PRD 43 (trust ladder PKs), doctrine/TRUST_LADDER_REGISTRY.md (validation), and all memory graph migration scripts.

**Illustrative conversion**

- Original runtime id: **`202604081200001234`** (embedded year **2026**)
- After **`toCanonicalId`**: **`102604081200001234`** (embedded year **1026**; remainder of digits unchanged)
- Mirror file (slug stem still from node fields): e.g. **`memory/1026/04/20260408_120000_actor_116_???????.json`** ???????? directory year comes from **`created_ymdhis`** after archive (**`10260408120000`** ???????? **1026 / 04**).

### 8.3 Archive operation (normative sequence)

1. **Soft-delete** the original row: **`is_deleted = 1`**, **`deleted_ymdhis`** set (packed UTC); optionally remove mirror via **`MemoryExportService::removeMirrorFileForNode()`** for the **original** id.
2. **Insert** a **new** row in **`lupo_memory_nodes`** with **`memory_node_id = toLongTermId(originalId)`**, **`created_ymdhis`** = first **14** digits of that new id (same rule as ????4.0), copy payload fields as needed, **`is_deleted = 0`**.
3. Insert a row in **`lupo_memory_edges`**: **`edge_type = 'archived_to'`**, **from** original node (still queryable as soft-deleted) **to** the new archived node; set **4D** columns (**`edge_context`**, **`edge_status`**, **`edge_direction`**) per install + ????5.2; **`review_reason`** if status requires it.
4. Call **`MemoryExportService::exportNode($archivedMemoryNodeId)`** so the archived node????????s mirror exists under the **1026????????2025** tree.

**Restore (reverse):** apply the inverse transform on **`memory_node_id`** (add **1000** to the year when **`isLongTermId($id)`**), re-insert or update rows, link with an edge (e.g. **`restored_from`**) ???????? specify in implementation; **PRD 24** defines CLI entrypoints.

### 8.4 PHP conversion helpers (reference)

**Canonical name:** **`toCanonicalId`** ???????? forward transform from **staging-shaped** (**embedded year ??????? 2000** on 18-digit ids) to **living canonical / long-term-shaped** (**embedded year 1000????????1999**). **`toLongTermId`** in Option B is the **same transform** (alias for archive operations).

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
 * True for 18-digit ids whose embedded year is 1000????????1999 (canonical / long-term band).
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

**Restore (inverse):** when **`isLongTermId($id)`**, add **1000** to the embedded year and concatenate the remaining **14** digits; re-insert or update per **????8.3** / **PRD 24**.

Use **string** concatenation for ids on the wire if any environment risks **float** rounding; production norm is **64-bit PHP** (PRD 00 / **`PHP_INT_SIZE`**).

### 8.5 Querying across eras

- **Active runtime-shaped only:** `WHERE is_deleted = 0` and **first four digits of `memory_node_id` in 2000????????2099** (raw **`IdGenerator`** band), or equivalent filter on **`created_ymdhis`** prefix.
- **Living canonical / long-term band:** **first four digits in 1000????????1999** on **18-digit** ids, or **`isLongTermId(memory_node_id)`**.
- **Include both:** `UNION` or **`OR`**, or traverse **`lupo_memory_edges`** where **`edge_type IN ('archived_to', 'restored_from', 'consolidated_into', ???????)`**.
- **Original ???????? archived:** follow **`archived_to`** from soft-deleted original to replacement row (or reverse for restore).

---

## 9. Parent-Child Entity Distinction

The memory graph (`lupo_memory_nodes`, `lupo_memory_edges`) supports two entity archetypes with different trust ladder behaviors.

### 9.1 Definitions

| Archetype | Definition | Examples |
|-----------|------------|----------|
| **Parent** | Long-lived entities needing permanent anchor; receive ongoing updates | Housing projects, organizational units, master records, actors, channels |
| **Child** | Ephemeral entities; no permanent anchor needed; high volume | Memory observations, dialog messages, work orders, transactions |

### 9.2 Parent Entity Rules (Three-Layer)

Parent entities use all three trust tiers:

```text
Seed (Tier 0, ID < 1,000,000)
    |
    | edge: 'canonical_instance_of' (active_until = 0)
    ???????
Living Canonical (Tier 1, ID 1,000,000,000,000,000,000 - 1,999,999,999,999,999,999)
    ???????
    | edge: 'consolidated_into' (multiple staging ???????? one canonical)
    |
Staging (Tier 2, ID 2,000,000,000,000,000,000 - 2,999,999,999,999,999,999)
```

**Rules:**
- One seed : one active canonical (1:1 via `canonical_instance_of` edge with `active_until = 0`)
- When canonical is archived, create a new edge from the same seed to the new canonical
- Close old edge by setting `active_until = now()`
- Seeds are immutable (never updated after install)

### 9.3 Child Entity Rules (Two-Layer)

Child entities skip the seed tier entirely:

```text
Living Canonical (Tier 1)
    ???????
    | edge: 'promoted_to'
    |
Staging (Tier 2)
```

**Rules:**
- No seed tier (IDs never < 1,000,000)
- Staging ???????? canonical promotion via `toCanonicalIdSafe()`
- If entity type will exceed 1 million rows, classify as Child

### 9.4 Edge Types Summary

| Edge Type | Direction | Archetype | Cardinality |
|-----------|-----------|-----------|-------------|
| `canonical_instance_of` | Seed ???????? Canonical | Parent | 1:1 active |
| `consolidated_into` | Staging ???????? Canonical | Parent | Many:1 |
| `promoted_to` | Staging ???????? Canonical | Child | 1:1 |
| `archived_to` | Original ???????? Archived | Any | 1:1 |

### 9.5 Query Patterns

**Get active canonical for a parent seed:**
```sql
SELECT canonical.*
FROM lupo_memory_nodes seed
JOIN lupo_memory_edges e ON e.from_memory_node_id = seed.memory_node_id
JOIN lupo_memory_nodes canonical ON canonical.memory_node_id = e.to_memory_node_id
WHERE seed.memory_node_id = :seed_id
  AND e.edge_type = 'canonical_instance_of'
  AND e.active_until = 0;
```

**Get all staging for a parent canonical:**
```sql
SELECT staging.*
FROM lupo_memory_nodes staging
JOIN lupo_memory_edges e ON e.from_memory_node_id = staging.memory_node_id
WHERE e.to_memory_node_id = :canonical_id
  AND e.edge_type = 'consolidated_into'
  AND staging.is_deleted = 0;
```

**Cross-reference:** Seed-to-canonical edge lifecycle rules are normative in **PRD 41 ????5**.

### 9.6 Explicit archetype declaration (single source of truth)

Every ladder-participating table must declare archetype metadata in:

1. `docs/doctrine/TRUST_LADDER_REGISTRY.md` (documentation source), and
2. runtime registry/config cache consumed by application code.

Example declaration format:

```markdown
### Table: lupo_memory_nodes
archetype: parent
seed_required: true
canonical_lineage_edge: canonical_instance_of
promotion_target: canonical
```

Runtime contract example:

```php
// Never derive this from table name or ID shape alone
$tableArchetype = TrustLadderRegistry::getArchetype('lupo_memory_nodes');
// returns 'parent' | 'child' | 'system'
```

### 9.7 Defensive archetype and id checks at every entry point

Archetype is constitutional metadata, not a heuristic. Every write path must validate table archetype and id expectations before mutation.

```php
public function validateTableArchetypeAndId($table, $id)
{
    $archetype = TrustLadderRegistry::getArchetype($table);

    if ($archetype === 'parent') {
        if (!IdGenerator::isReservedSpace($id)) {
            throw new TrustLadderException(
                "Parent table {$table} received non-seed ID {$id}. Seed anchor is mandatory."
            );
        }
        if (!SeedRegistry::isValidSeed($id, $table)) {
            throw new TrustLadderException("Parent seed {$id} not registered for {$table}");
        }
    } elseif ($archetype === 'child') {
        if (IdGenerator::isReservedSpace($id)) {
            throw new TrustLadderException(
                "Child table {$table} received seed ID {$id}. Children must start as staging/canonical."
            );
        }
        IdGenerator::validateTrustLadderPk($id, $table);
    }
}
```

Minimum call sites:

- all INSERT paths,
- canonical promotion (`toCanonicalIdSafe`) flows,
- batch ingest preflight,
- edge creation for lineage edges.

### 9.8 Archetype-aware best-current query priority

Best-current logic must respect archetype:

```php
$priorityOrder = ($archetype === 'parent')
    ? array('canonical', 'staging', 'seed')
    : (($archetype === 'child') ? array('canonical', 'staging') : array('seed'));
```

For `child`, seed is illegal.  
For `system`, seed-only retrieval is expected.

## 10. IDE/Claude Read Flow (Unchanged)

Claude Code can still read memory files the same way:

```bash
# List memory files by date
ls memory/2026/04/

# Read a specific memory node
cat memory/2026/03/20260331_120000_actor_1_root_actor_root_context.json

# Search across all memory
grep -r "login_errors" memory/
```

**The only change:** Files are now **mirrors** of the database, not the source of truth. But for read-only IDE access, that's fine.

---

## 11. Migration Path

### 11.1 Migration Script: `scripts/migrate_memory_to_unified_graph.php`

```php
<?php
/**
 * PRD 38 Migration: Import existing JSON files to database, then set up export mirror
 */

class MemoryMigration38 {
    // ... (similar to previous version, but imports files to DB first)
    
    private function importJsonFile($filePath, $data) {
        $memoryNodeId = IdGenerator::generate();
        $createdYmdhis = (int) substr((string) $memoryNodeId, 0, 14);
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

## 12. Amendments to Existing PRDs

### 12.1 Amendment to PRD 00 section 5.7

**Original text:**
> *"The file path for a memory node is constructed as: `memory/YYYY/MM/{memory_slug}.json`"*

**Amended text:**
> *"The canonical source of truth for memory nodes is the `lupo_memory_nodes` database table. A read-only export mirror is maintained at `memory/YYYY/MM/{slug}.json` for IDE and filesystem browsing (slug derived from `created_ymdhis` and node fields). All writes MUST go to the database first; the `MemoryExportService` synchronizes to disk."*

### 12.2 Amendment to PRD 24 section 5

**Original text:**
> *"Register root memory node in `lupo_memory_nodes`; file stored at `memory/YYYY/MM/{memory_slug}.json`"*

**Amended text:**
> *"Create root memory node by inserting a row into `lupo_memory_nodes` with `memory_type = 'root'`. The `MemoryExportService` will automatically write the mirror file to `memory/YYYY/MM/{slug}.json` using `created_ymdhis` and `generateSlug()`."*

### 12.3 Amendment scope ???????? PRD 07 (`07_agents_faucets.md`)

**Scope:** Remove or replace any remaining **`memory.json`** workspace references with the unified model: canonical row in **`lupo_memory_nodes`**, mirror file under **`memory/YYYY/MM/{slug}.json`**, and **`MemoryExportService::exportNode()`** after writes (Phase 1: synchronous per section 6.5).

### 12.4 Amendment scope ???????? PRD 15 (`15_actors.md`)

**Scope:** Align actor workspace documentation with this PRD: **`memory_node_id`** from **`IdGenerator`**, **`created_ymdhis`** equal to the 14-digit PK prefix, derived **slug** for export filenames (no separate `memory_slug` column), and memory graph edges as implemented in **`lupo_memory_edges`** / **`lupo_edges`** per the reconciled single-graph decision.

### 12.5 Reference alignment ???????? PRD 37 (`37_kairos_channel_memory_consolidation.md`)

**Scope:** KAIROS persists channel memory to **`lupo_memory_nodes`** (and related edges) first; after insert/update, invoke export so IDE-visible mirrors stay current. Detailed consolidation behavior remains in PRD 37; this PRD only binds the **write path + mirror** contract.

### 12.6 Reference alignment ??? PRD 51 (`51_memory_graph_as_source_of_truth.md`)

**Scope:** Header field inference may read **`lupo_memory_nodes`** / **`lupo_memory_edges`** as authority before path heuristics (**PRD 16**). Graph writers SHOULD use stable **`edge_type` / `edge_context`** conventions so tooling can populate LUPOPEDIA headers from tasks and file registration APIs. Path/referrer **telemetry** is intentionally split into **raw** vs **aggregate** install tables (**`lupo_paths_raw`**, **`lupo_paths_daily`**, **`lupo_paths_monthly`**, **`lupo_referers_raw`**) per **PRD 51 ??4.6** and **PRD 11**; ETL into **`lupo_memory_edges`** remains optional and must preserve **IdGenerator** / timestamp doctrine. Does not change the DB-first write path in section 3.

---

## 13. Success Criteria

| Criterion | Validation |
|-----------|------------|
| `lupo_memory_nodes` and `lupo_memory_edges` tables added | ??????? Schema present |
| Existing JSON files imported to database | ??????? Migration runs successfully |
| `MemoryExportService::fullExport()` recreates all files | ??????? Files match original content |
| New memory writes create DB row + mirror file | ??????? Both updated |
| IDE/Claude can read files from `memory/YYYY/MM/` | ??????? Same as today |
| Graph queries work on database | ??????? Edges traversable |
| KAIROS consolidates using DB graph | ??????? Observations linked to root |

---

## 14. Summary

| Concern | Resolution |
|---------|------------|
| **Constitutional violation** | Database becomes source of truth; edges fully supported |
| **IDE/Claude needs files by date** | `memory/YYYY/MM/` preserved as read-only export mirror |
| **Queryable graph** | `lupo_memory_nodes` + `lupo_memory_edges` with full dimensions |
| **Backward compatibility** | Existing JSON files imported; export service rewrites in consistent format |
| **Performance** | Export can be async (shutdown register or queue) |

**You get both: graph database + date-browsable files.**

---

**Status:** DRAFT ???????? awaiting review

**Next actions:**
1. Reconcile **01 / 15 / 24 / 37** and actor docs with this PRD (PK name, edges table, slug rule).
2. LILITH audit
3. WOLFIE approval
4. Regenerate TOONs after install SQL is frozen
5. Ship migration script for legacy JSON on disk

---

## 15. IDE prompt fragment (timestamp consistency)

Use when auditing inserts/updates across the codebase:

```
We need to ensure all tables in Lupopedia follow the same timestamp pattern.

Rules:
1. Every table MUST have a created_ymdhis BIGINT NOT NULL DEFAULT 0 column
2. Every table MUST have an updated_ymdhis BIGINT NOT NULL DEFAULT 0 column
3. Every table MUST have is_deleted TINYINT NOT NULL DEFAULT 0 and deleted_ymdhis BIGINT NOT NULL DEFAULT 0
4. Primary keys are generated by IdGenerator::generate() which returns YYYYMMDDHHIISS + 4-digit suffix (see IdGenerator.php)
5. When inserting a row, created_ymdhis MUST be set to the same 14-digit timestamp prefix from IdGenerator::generate() (`substr((string)$id, 0, 14)`)
6. When updating a row, updated_ymdhis MUST be set to gmdate('YmdHis')

For lupo_memory_nodes:
- memory_node_id = IdGenerator::generate()
- created_ymdhis = (int) substr((string) memory_node_id, 0, 14)
- updated_ymdhis = gmdate('YmdHis') on update (or equal to created_ymdhis on insert)

Update any code that assumes created_ymdhis is set independently of the PK timestamp prefix.
```

---

## 16. Related: Memory Graph Focus Manifest (PRD 52)

Graph traversal without a **scope** tends to pull every **`edge_type`** and exhaust context. **PRD 52** defines the **Focus Manifest** ??? a runtime lens (**edge include/exclude**, **trust tier**, **`max_depth`**, optional **focus node** set) that works **on top of** this PRD???s **`lupo_memory_nodes`** / **`lupo_memory_edges`** model. It replaces the *concept* behind the removed Semantic Widget **Contexts** surface without reviving **`lupo_contexts_map`** as authority.

---

## 17. Competency probes and doctrine alignment tests (multi-agent)

Correct **memory graph** behavior (this PRD), **export mirror** parity, and **TOON/header** fields only hold if **every AI facet** that edits or ingests them **applies the same rules**. Prose agreement in chat is **weak** evidence.

**Required operator pattern:** treat a **concrete coding task** (header block, PHP/SQL/Python fragment, path layout) as a **competency probe** ??? inspect output for doctrine compliance, correct with citations, re-run until compliant. That turns code generation into a **verification harness** and a **cross-session consistency check** (a probe run in another IDE still counts; re-run here when rules change).

**Canonical procedure:** [`docs/doctrine/AI_ACTOR_COMPETENCY_TEST_PATTERN.md`](../doctrine/AI_ACTOR_COMPETENCY_TEST_PATTERN.md) ??? when **two or more actors** participate, follow **no self-grading**, **`<TEST_COMPLETE>`** termination by the examiner, **anti-parrot**, **fixed roles**, and **external containment** (violation codes in that file). **When a probe fails:** record the missing rule as **`lupo_memory_nodes`** and bind with **`lupo_memory_edges`** (node-to-node) per [`docs/doctrine/AI_ACTOR_KNOWLEDGE_UPDATE_PROTOCOL.md`](../doctrine/AI_ACTOR_KNOWLEDGE_UPDATE_PROTOCOL.md); [PRD 50 section 1.3](50_agent_coordination_protocol.md). **Constitutional anchor:** [PRD 00](00_root_constitutional_system_requirements.md) (AI actor verification protocols). **Coordination context:** [PRD 50 sections 1.2???1.4](50_agent_coordination_protocol.md) and shared state / transcripts ??? probes complement, they do not replace, pending tasks and memory edges.

---

## 18. Collection to memory graph binding (payload v1.0.0)

**Canonical payload:** [`docs/doctrine/collection_payload_format_v1_0_0.md`](../doctrine/collection_payload_format_v1_0_0.md). **Constitutional:** [PRD 00 section 22](00_root_constitutional_system_requirements.md). **Operational sequence (orchestrator + actor):** [PRD 50 section 1.4](50_agent_coordination_protocol.md) subsections **1.4.1???1.4.7**.

When a **collection payload** is ingested into the DB-backed graph:

1. **Nodes** ??? Each entry in **`payload.nodes`** **MUST** map to a **`lupo_memory_nodes`** row (or an **UPDATE** when idempotent ingest identifies an existing row by policy). **`owner_actor_id`** **MUST** reflect the **ingesting** actor (facet) unless the orchestrator pre-labels shared pool rows per written policy. **`memory_key`**, **`memory_value`** (typically payload **`content`**), **`memory_type`**, **`context`**, **`status`**, **`content_hash`**, and timestamps **MUST** follow this PRD and install SQL. Payload correlators that **do not** have columns on **`lupo_memory_nodes`** ??? including payload **`node_id`**, **`title`**, **`artifact_type`**, **`file_path`**, **`web_path`**, and top-level **`federation_node_id`** / **`collection_id`** ??? **MUST** be preserved in **`context_json`** (or a documented equivalent) so edges and exporters can resolve **`memory_node_id`** ??? payload identity.
2. **Edges** ??? Each **`nodes[].edges`** item **MUST** become a **`lupo_memory_edges`** row with **`from_memory_node_id`** = persisted ID of the **source** node and **`to_memory_node_id`** = persisted ID of the target node that corresponds to payload **`to_node_id`**, after the full **`nodes`** set is materialized. **`edge_type`**, **`provenance_actor_id`**, **`provenance_tool`**, and other columns **MUST** be preserved per schema (defaults **MUST NOT** silently drop provenance). **`lupo_memory_edges`** always references **`memory_node_id`** endpoints, never raw payload string ids in place of PKs.
3. **Root context** ??? The payload???s top-level **`memory_key`** **MUST** be stored or mirrored so exporters and actors can re-attach the same collection scope (e.g. in **`context_json`** on a root or anchor node, or a dedicated anchor row per product convention ??? document the chosen convention in the exporter).
4. **Tabs** ??? **`tabs[].tab_id`** aligns with **`lupo_collection_tabs.collection_tab_id`**; **`node_ids`** order **MUST** be preserved for UI parity. Optional **structural** edges (collection ??? tab ??? member) **MAY** be persisted **only** via additional **anchor** **`lupo_memory_nodes`** rows and normal **`lupo_memory_edges`** (see **PRD 50** section **1.4.3**); **MUST NOT** write **`tab_id`** or **`collection_id`** directly as if they were edge endpoints.
5. **Ingestion mode** ??? When the orchestrator sets **read-only** context (**PRD 50** **1.4.1**), the actor **MAY** load nodes into working memory without DDL. **Read-write** ingest **MUST** use allocators for new **`memory_node_id`** / **`memory_edge_id`** and **MUST** satisfy rows **1???4** above.

**MUST NOT:** fabricate **`memory_node_id`** values that collide with reserved-ID doctrine; use the project **allocator** / **`IdGenerator`** paths for new rows.
