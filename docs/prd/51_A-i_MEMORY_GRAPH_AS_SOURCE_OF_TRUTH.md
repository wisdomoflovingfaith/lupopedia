---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/51_A-i_MEMORY_GRAPH_AS_SOURCE_OF_TRUTH.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/51_A-i_MEMORY_GRAPH_AS_SOURCE_OF_TRUTH.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/canonical/development/memory_cluster/2026/05/51_memory_graph_as_source_of_truth.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/51-memory-graph-as-source-of-truth
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_16_C-i_38_A-i_49_A-i_51_A-i_86_A-i
  title: 'PRD 51: Memory Graph as Contextual Suggestion Layer (Non-Authoritative)'
  summary: Memory graph and thread context provide deterministic contextual suggestions for headers. They do not perform inference, override prd_cluster doctrine, or override atoms_toon truth.
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

### ASCII_ART_BLOCK Protection

See PRD 86.

<!--HUMAN_SEMANTIC -->
This file belongs to:
- PRD Group 51 (Memory graph / contextual suggestion layer)
- Channel: development
- Trust tier: canonical

See also:
- PRD 16 - Lupopedia Headers
- PRD 38 - Memory Unification
- PRD 49 - Questions and Answers System
- PRD 86 - Immune System Enforcement
<!-- /HUMAN_SEMANTIC -->
# PRD 51: Memory Graph as Contextual Suggestion Layer (Non-Authoritative)

## No-Guessing rule (canonical reference)

This PRD inherits the canonical No-Guessing Rule from **PRD 16**.

All No-Guessing behavior MUST reference **PRD 16**.

This document MUST NOT redefine No-Guessing behavior.

## Canonical Year Offset Rule for Memory Graph PKs (Normative)

All canonical (long-term, merged, or archived) `memory_node_id` values used as memory graph primary keys or for header inference MUST encode:

year_offset = calendar_year - 1000

Example:
2026 -> 1026

**Rationale:**
- The offset (calendar_year - 1000) creates a distinct, lexicographically sortable band for high-trust, long-term ids (1000-1999), separate from runtime/staging ids (2000-2099).
- This prevents accidental mixing of staging and canonical ids, supports deterministic migration, and enables strict validation of memory graph and header integrity.
- Numeric banding is not a substitute for explicit trust semantics, but is a required convention for all canonical ids in memory graph and header operations.

**Validation and migration requirements:**
- All memory graph PKs and header inference logic **MUST** enforce the offset rule for canonical ids.
- Validators MUST reject any canonical or archived id whose year is not in 1000-1999, or whose offset does not match the original runtime year minus 1000.
- Migration scripts **MUST** backfill or repair ids to conform to this rule if legacy data is found.
- Query helpers **MUST** use the offset band to distinguish canonical from staging ids, but **MUST NOT** rely on numeric banding alone for trust semantics (see PRD 43).

**See also:** PRD 16 Section 8.1 (header/memory_toon year encoding), PRD 38 Section 8.1 (memory unification), PRD 43 Section 8.1 (trust ladder PKs), doctrine/TRUST_LADDER_REGISTRY.md (validation), and all memory graph migration scripts.

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

Reduce **guessing** of LUPOPEDIA header fields (**PRD 16**) from filesystem paths and filename regex. Authority is defined by:

- `prd_cluster` as governing doctrine
- `atoms_toon` as canonical truth constraints

Operational context, including memory graph, dialog thread, task metadata, path analytics, and referrer analytics, provides deterministic contextual suggestion only (not inference).

memory graph MAY provide suggestion-only context

Definition - Suggestion vs Inference:

Suggestion is limited to:

- returning candidates only per **Candidate Set Definition (Normative)** under **Mode 1 -- Suggestion Mode** (below)
- bounded result sets that comply with that definition

In Suggestion Mode (default; Memory Graph Modes (4.1.7)), the system MUST NOT:

- generate candidates via traversal
- expand graph beyond declared bounds
- rank or score candidates heuristically

If suggestion requires traversal:

STOP
REPORT "MEMORY_TRAVERSAL_REQUIRED_FOR_SUGGESTION"

Suggestion means:
- presenting candidate values to a human or process
- never writing header fields from implicit acceptance of a suggestion alone

Suggestion MAY be presented to a human.

Header fields MUST only be set when:
- values are deterministically confirmed OR
- values are explicitly assigned as literal field values by an authorized path (human operator or system field edit), never by implicit acceptance of a suggestion alone

Human confirmation MUST produce:
- explicit value assignment to named fields
- not implicit acceptance of a suggestion as sufficient to commit headers

Inference means:
- filling header fields without explicit confirmation
- resolving missing values automatically
- constructing relationships not explicitly declared

Rule:

Memory graph MAY:
- present suggestions

Memory graph MUST NOT:
- perform inference

If a suggestion cannot be deterministically confirmed:
STOP
REPORT "DOCTRINE NOT FOUND"

memory graph MUST NOT determine header values

Operational context MUST NOT override `prd_cluster` or `atoms_toon`.

### Authority Boundary (4.1.7)

Memory graph and thread context are NOT sources of truth.

Canonical truth is defined by:
- prd_cluster
- atoms_toon

Memory graph provides contextual suggestion only and MUST NOT:
- define truth
- override truth
- mutate header meaning

Memory graph MUST NOT:

- influence PRD interpretation
- alter prd_cluster meaning
- modify execution behavior

When a file is created from work that already has context (a task, a chat thread, or an existing memory node), implementations MAY retrieve candidate values from context for `artifact_type`, `artifact_kind`, `content_id`, `content_parent_id`, `channel_key`, `title`, `summary`, and related fields before applying legacy path rules.

Context MUST NOT be used to:

- fill missing fields automatically
- resolve ambiguity
- infer relationships

If deterministic confirmation is not possible:

STOP
REPORT "DOCTRINE NOT FOUND"

Legacy note: Older examples may mention pre-4.1.7 header terms. Current PRD 16 headers use `content_id`, `content_parent_id`, `transcript_jsonl`, and `memory_toon`. Legacy terms are explanatory only and MUST NOT be used in new 4.1.7 headers.

**Out of scope for this PRD:** changing the 22-key header envelope; replacing **PRD 16** validators; automatic LLM extraction without an explicit product decision.

## Memory Graph Modes (4.1.7)

Memory graph operates in two distinct modes:

### Mode 1 -- Suggestion Mode (default)

- NO traversal allowed
- MUST use:
  - direct indexed lookup
  - precomputed mappings
- MUST NOT:
  - walk edges
  - expand graph

#### Candidate Set Definition (Normative)

A candidate set MUST be derived from:

- direct key-based lookup
- explicit indexed mapping
- precomputed static mapping tables

A candidate set MUST NOT be derived from:

- filtered query results
- ORDER BY operations
- LIMIT-based selection
- dynamic query conditions
- aggregation pipelines

The system MUST NOT:

- compute candidate sets at runtime
- derive candidates from counts, weights, or frequency
- construct candidate lists via selection logic

If candidate set requires computation:

STOP
REPORT "CANDIDATE_SET_NON_DETERMINISTIC"

Violation:

STOP
REPORT "TRAVERSAL_NOT_ALLOWED_IN_SUGGESTION"

### Mode 2 -- Traversal Mode (explicit only)

Traversal is allowed ONLY when:

- explicitly requested by governing PRD
- traversal policy is defined
- bounds are declared

Traversal MUST include:

- start node
- edge allowlist
- max depth
- deterministic order

If any missing:

STOP
REPORT "MEMORY_TRAVERSAL_UNDEFINED"

## 1.1 Header 4.1.7 Alignment

This PRD operates under PRD 16 header interpretation semantics.

Header reading order is defined in PRD 16.

This PRD MUST NOT redefine reading order.

Memory graph execution MUST respect PRD 16 read order.

Memory graph and thread context:

* MUST NOT execute before prd_cluster and atoms_toon are interpreted
* MUST NOT override canonical truth
* MUST NOT invent header meanings
* MUST provide context suggestion only

Memory graph execution MUST NOT occur before:

- prd_cluster interpretation
- atoms_toon interpretation

Violation of read order:

STOP
REPORT "DOCTRINE NOT FOUND"

### transcript_jsonl Constraint (4.1.7)

transcript_jsonl is append-only.

Agents MAY append to transcript_jsonl.

Agents MUST NOT:
- read transcript_jsonl by default
- treat transcript as context
- derive truth from transcript

transcript_jsonl is not part of header inference.

### Header interpretation (PRD 16)

Deterministic header interpretation and the canonical No-Guessing Rule are defined in **PRD 16**. This PRD adds memory-context suggestion and traversal-mode bounds only; it does not restate No-Guessing.

If deterministic resolution fails when applying this PRD together with **PRD 16**:

STOP
REPORT "DOCTRINE NOT FOUND"

---

## 2. Problem statement

Path-only inference is brittle.

| Path-based habit | Failure mode |
|------------------|--------------|
| Directory `docs/prd/` implies `artifact_type: prd` | Files move; non-PRD files land in the tree |
| Filename regex for removed slug field | Slug conventions drift or break |
| Folder name as `module` | PRD 16 `module` is often `null`; channel and module are not the same thing |
| Guessed `content_parent_id` | Real parentage may come from a task or thread, not the path |
| No link to **why** the file exists | Audit and reproduction suffer |

**Principle:** The **repository path** is a hint, not the authority. Authority is **operational context** (memory graph + dialog thread + explicit task metadata), then **human-confirmed** header rows.

---

## 3. Design principles

### 3.1 Three complementary sources

1. **Memory graph** (`lupo_memory_nodes`, `lupo_memory_edges` - see TOON and PRD 38). Best when work is driven by tasks, KAIROS, IDE agents, or explicit graph writes. Edges can encode type, slug, references, authorship, and links to `file_path_from_root` (often via `memory_toon` / `context_json`). **Suggestion Mode** (default; Memory Graph Modes (4.1.7)) MUST NOT walk edges or expand the graph; use direct indexed lookup and precomputed mappings only. **Traversal Mode** (explicit only; Memory Graph Modes (4.1.7)) MAY traverse edges when traversal is explicitly requested, traversal policy is defined, traversal bounds are declared, and graph traversal is bounded, deterministic, and constrained by a declared policy such as an active Focus Manifest (PRD 52). Unbounded graph traversal is forbidden. When traversal policy is missing in Traversal Mode, resolution MUST stop and report a deterministic failure instead of guessing traversal scope. Implicit edge following is forbidden.
2. **Dialog thread + channel** (**`lupo_dialog_threads`**, **`lupo_channels`**, **`lupo_dialog_messages`**). Best when work is driven by the **web chat**: channel policy, `thread_key`, recent messages, and thread **`metadata_json`** / **`artifacts`** carry intent without scanning the whole graph.
3. **Path and referrer analytics** (`lupo_paths`, `lupo_referers`, `lupo_visits`, `lupo_paths_summary`, `lupo_visits_daily`, `lupo_referers_daily` - TOON). Long-horizon navigation and referrer aggregates (Crafty Syntax lineage via import) indicate what was viewed together and what sent traffic. Analytics MAY provide suggestion-only context and MAY use materialized memory edges - not as a substitute for explicit authorship edges.

Analytics suggestions MUST be:

- explicitly requested
- bounded to aggregated datasets
- non-graph-expanding

Analytics MUST NOT:

- trigger graph traversal
- influence header resolution
- modify candidate selection

Analytics suggestions MUST follow the same constraints as memory suggestions:

- deterministic lookup only
- bounded result set
- no traversal
- no heuristic ranking, score-weighted ordering, or normalized scores (analytics display: see Analytics boundary below)
- no heuristic scoring

Analytics boundary (counts vs ranking):

Analytics MAY:

- present aggregated counts
- present raw counts as values attached to entities or keys (no implicit priority)

Analytics MUST NOT:

- apply weighting beyond raw counts for presentation order
- normalize scores for ranking
- apply heuristics to reorder candidates

Ordering MUST NOT imply priority, recommendation, or selection.

Presentation order MUST be:

- stable
- deterministic
- NOT interpreted as ranking

**Web-first** flows should prefer **thread context**; **task/graph-first** flows should prefer **memory queries**; **relatedness** and **discovery** may draw on **path/referrer** signals under **SILENT_HARVEST** / **PRD 34** ethics.

### 3.2 Collection Integration (PRD 72/73)

When resolving headers for content that belongs to collections:

Walking `collection_contains` or `parent_collection` edges (items 2 and 4 below) is **Traversal Mode** only (Memory Graph Modes (4.1.7)), with start node, edge allowlist, max depth, and policy declared. **Suggestion Mode** MUST use keyed or table lookup (item 1 pattern) instead of edge walks when Traversal Mode prerequisites are absent.

1. **Human UI Collections**: Query `lupo_collections` tables via `collection_id` in memory edge context
2. **AI Memory Collections**: Traverse `collection_contains` edges in memory graph
3. **Sync Status**: Check `sync_status` in edge context to determine if human-approved
4. **Collection Hierarchy**: Follow `parent_collection` edges for nested collections

**Example:** When a PRD is in multiple collections:
```php
// Check for collection membership via memory graph
$collections = $memoryGraph->getIncomingEdges($memoryNodeId, 'collection_contains');
foreach ($collections as $edge) {
    if ($edge->context['sync_status'] === 'synced') {
        $header->collections[] = $edge->context['collection_id'];
    }
}
```

### 3.3 Precedence (4.1.7 Alignment)

When resolving or suggesting header fields, apply this order:

1. `prd_cluster` (governing doctrine)
2. `atoms_toon` (canonical truth constraints)
3. explicit human or admin override
4. bound task id with task-linked memory node
5. active dialog thread id
6. memory graph lookup by `file_path_from_root` MUST:
   - use explicit indexed mapping
   - NOT perform fuzzy matching
   - NOT scan graph
   - NOT infer relationships

Operational context sources MUST NOT override `prd_cluster` or `atoms_toon`.

Document the chosen source in **`provenance_tool`** / logs when writing edges or sidecar metadata (**PRD 38**).

### 3.4 Non-Resolution Inputs (Human Review Only)

The following inputs are human-review-only and MUST NOT participate in automated resolution:

- path heuristics
- path/referrer analytics

Path/referrer analytics and path heuristics MUST remain non-authoritative and review-gated.
Path heuristics MUST NOT be used for header resolution.
Path/referrer analytics MUST NOT be used for automated header resolution.

### Authority resolution and conflicts (4.1.7)

Canonical No-Guessing, pointer rules, and deterministic header interpretation: **PRD 16** (see **No-Guessing rule (canonical reference)** at top of this PRD).

This subsection states only ordering and conflict handling for this PRD:

Memory graph and operational context MUST NOT be used to infer missing header fields, fill unknown relationships, guess parent-child structure, or construct implicit meaning except as **Suggestion** (section 1), **Candidate Set Definition**, and **Mode 1 -- Suggestion Mode** allow.

Authority resolution order:

1. prd_cluster (governing doctrine)
2. atoms_toon (validation and refinement)
3. all other sources

If context sources conflict or cannot resolve deterministically:

STOP
REPORT "DOCTRINE NOT FOUND"

Rule:

atoms_toon MUST NOT contradict prd_cluster

If contradiction detected:

STOP
REPORT "ATOM_PRD_CONFLICT"

### 3.5 PRD 16 constraints

- **Tags** are **not** header keys (**PRD 16**); do not add `tags` to YAML. Use **sidecar** / **`lupo_metadata`** / graph edges for tag-like data.
- `channel_key` in the header is the document's home channel; it need not equal the middle segment of `transcript_jsonl` (PRD 16).
- **`content_id`** remains **`null`** until a **`lupo_contents`** row exists (**PRD 16**, **PRD 50 ??4.17**).

### 3.6 Webroot exposure vs graph authority (WOLFIE - 2026-04-12)

Header and graph inference run in a **subfolder install** where many repo paths may be **web-reachable**. **Operational authority** still lives in **DB + session** (**PRD 38**); a readable **`.py`** or export mirror does **not** grant write authority.

- Treat **`scripts/*.py`**, **`memory/`** exports, and docs under a public docroot as **potentially world-readable** unless explicitly blocked.
- **Non-PHP** scripts are **not** assumed web-executed; do **not** rely on hidden execution or framework sandboxing that Lupopedia does **not** ship.
- **Secrets** only in **`lupopedia-config.php`** - see **`lupopedia_quick_reference.md`** (*Webroot execution model*) and **PRD 38 Section 3.0.1**.

### 3.7 Query abstraction for PK bands

Application code **MUST NOT** rely on ad hoc raw SQL alone against trust-ladder **`lupo_memory_*`** tables when resolving **which** row is authoritative across staging vs canonical bands. Use the **`TrustLadder`** helper (**`includes/classes/TrustLadderQueryHelper.php`**) so consolidation edges are honored:

```php
// Resolve canonical target for a staging or canonical id (follows promoted_to / consolidated_into)
$canonicalId = TrustLadder::getCanonical($stagingOrCanonicalId, 'memory_nodes');

// Validate PK before write (calendar year from gmdate('Y') or injected clock for tests)
TrustLadder::validatePk($pk, 'canonical', (int) gmdate('Y'));

// Build an IN list that expands to canonical ids when $resolveCanonical is true
$idsForIn = TrustLadder::query($db, 'memory_nodes', array('memory_node_ids' => $candidateIds), true);
```

**Rationale:** Prevents orphaned staging rows and drift when multiple PK bands coexist; **`trust_tier`** on headers (**PRD 16 Section 8.1**) and **`edge_type`** on **`lupo_memory_edges`** remain semantic truth - numeric bands are sort/display discipline (**PRD 38 Section 8.1**, **CHRONOLOGICAL_TRUST_LADDER.md**).

### 3.8 Bounded Graph Traversal (4.1.7 alignment)

Graph-aware context expansion MUST be bounded and deterministic.

Memory Graph Rules:

* Edges MUST NOT contain weights
* Weighted traversal is FORBIDDEN
* Heuristic scoring is FORBIDDEN
* Traversal MUST be deterministic

Required controls:

* explicit start node set
* explicit edge-type allowlist
* explicit max depth
* deterministic traversal order

Traversal MUST:

* specify start node
* specify edge allowlist
* specify max depth
* execute in deterministic order

Forbidden:

* unbounded edge walking
* implicit "read everything" expansion
* heuristic traversal without declared limits

When traversal policy is missing, resolution MUST stop and report a deterministic failure instead of guessing traversal scope.

If any of the above are missing:
STOP
REPORT "MEMORY_TRAVERSAL_UNDEFINED"

## Role Constraints on Memory Usage

Watcher:

* MAY read memory graph
* MUST NOT mutate or trigger actions

Messenger:

* MAY read and relay memory
* MUST NOT reinterpret edges

Censer:

* MUST validate memory graph usage
* MUST enforce deterministic traversal

Reaper:

* MAY simulate failure paths
* MUST NOT alter canonical memory

Global rule:

Memory graph MUST NOT be used to infer role

Agent role MUST be provided by:
- blueprint metadata OR
- execution context

Memory graph is not a valid role source

### Role Violation Enforcement

If role constraints are violated:

Watcher acting (mutate or trigger):

STOP
REPORT "AGENT_ROLE_VIOLATION_WATCHER"

Messenger modifying meaning (reinterpret edges):

STOP
REPORT "AGENT_ROLE_VIOLATION_MESSENGER"

Censer bypassing validation or failing to enforce deterministic traversal:

STOP
REPORT "AGENT_ROLE_VIOLATION_CENSER"

Reaper altering canonical memory or bypassing validation:

STOP
REPORT "AGENT_ROLE_VIOLATION_REAPER"

---

## 4. Context sources (normative names)

### Memory Path Compliance (PRD 38)

All memory paths MUST follow:

memory/{tier}/{channel_key}/{thread}/{YYYY}/{MM}/

No alternative path formats are allowed.

No directory scanning is allowed for discovery.

### 4.1 Memory graph

Canonical tables: **`lupo_memory_nodes`**, **`lupo_memory_edges`** (column names per TOON). Typical fields for inference:

| Header field (PRD 16) | Graph source |
|-----------------------|-----------------------------|
| `artifact_type` / `artifact_kind` | Edges with `edge_type` / `edge_context` conventions (e.g. classification edges), or `context_json` on the node |
| `content_id` | Node or edge payload for PRD identity |
| `content_parent_id` | Edge such as **references** / **implements** to parent artifact node |
| `title` / `summary` | `memory_value`, task title, or related node |
| `memory_toon` | Target export path for the artifact |

**Convention:** `lupo_memory_edges.edge_type` and `edge_context` are **varchar**; register allowed pairs in code or in **`lupo_edge_types`** only if product unifies memory edges with global edge typing (**PRD 38**). Do not invent SQL **`INSERT INTO edge_types`** blocks in this PRD as shipped DDL.

#### 4.1.1 TOON ordering and graph semantics

Graph inference **MUST** treat TOON **index ordering** as **canonical truth** for fields represented in **`.toon`** memory and registry exports, in line with the **Canonical TOON Ordering Specification (v1.0.0)** ([`docs/doctrine/TOON_ORDERING_SPEC.md`](../doctrine/TOON_ORDERING_SPEC.md)). Drift in ordering, duplicate index keys, or silent reordering of primary structures **SHOULD** be flagged as a reconciliation defect against **Pattern #7 (Memory-Graph-Header Reconciliation)** and queued for remediation (roles and drift detection: **`TOON_ORDERING_SPEC.md`** ??6).

### 4.2 Dialog thread and channel

Canonical tables: **`lupo_dialog_threads`** (`dialog_thread_id`, `thread_key`, `channel_id`, `title`, `summary_text`, `metadata_json`, `artifacts`, -), **`lupo_channels`**, **`lupo_dialog_messages`**.

| Header field | Thread / channel source |
|--------------|-------------------------|
| `channel_key` | Resolved from **`lupo_channels`** for the thread's `channel_id` (not raw folder names) |
| `dialog_transcript` | Product slug for transcript routing (**PRD 16** field 22); may be derived from `federation_node_id` + channel + `thread_key` |
| `title` | `thread_key`, thread `title`, first user instruction (policy-defined) |
| `content_parent_id` / cross-refs | Parsed from recent **`lupo_dialog_messages`** (e.g. PRD 16 references) plus optional **`metadata_json`** on the thread |

thread_id MUST be:
- BIGINT OR null

thread_key MUST be used for:
- routing
- transcript_jsonl composition

**Thread memory node (optional):** A memory node representing the thread may link **contains** / **spawned** edges to file nodes. That is **product design**, not required schema today.

### 4.3 Path and referrer analytics (Crafty Syntax lineage, Lupopedia tables)

**Historical note:** Crafty Syntax Live Help maintained rich **path** and **referrer** tables (legacy names such as `livehelp_paths_visits*`, `livehelp_referers_visits*`). Those tables are **not** part of the Lupopedia runtime schema; the **Crafty - Lupopedia** import maps aggregated history into **`lupo_*`** analytics tables (see **`import_from_old_crafty_syntax.sql`** and **AGENTS.md** -silent harvest- overview).

**Lupopedia canonical tables (verify TOON before coding):**

| Table | Role |
|-------|------|
| **`lupo_paths_raw`** | High-volume **URL-pair** transitions (`from_url`, `to_url`); append-only; **`is_aggregated`** for rollup (**??4.6**) |
| **`lupo_paths_daily`** | Daily URL-pair counts + **`date_ymd`**; optional **`from_content_id` / `to_content_id`** for widget queries |
| **`lupo_paths_monthly`** | Monthly URL-pair counts + **`date_ym`** (long-horizon) |
| **`lupo_paths`** | **Content-id** transitions: `entercontentid`, `exitcontentid`, `year_num` / `month_num` / `day_num`, `count_num`, - (Crafty import + GC from **`lupo_visits`**) |
| **`lupo_paths_summary`** | Rolled-up path statistics |
| **`lupo_referers_raw`** | Per-hit referrer + landing URL stream; rolls up into **`lupo_referers`** / **`lupo_referers_daily`** |
| **`lupo_referers`** | Referrer rows keyed by **`content_id`**, `referer_domain`, `date_ymd`, `visits`, - |
| **`lupo_referers_daily`** | Daily aggregates by domain (`visit_ymd`, `visit_count`, -) |
| **`lupo_visits`** / **`lupo_visits_daily`** | Per-hit **`path_url`** stream (`is_processed`) and visit dailies |

**Conceptual map (analytics - graph thinking):**

| Crafty-era idea | Lupopedia analytics | Optional memory graph projection |
|-----------------|---------------------|----------------------------------|
| Page / content | **`lupo_contents.content_id`** (and paths- enter/exit content ids) | Memory or content mirror node (**PRD 50 ??4.17**) |
| Transition A -> B | **`lupo_paths`** and/or **`lupo_paths_daily`** / **`lupo_paths_monthly`** (URL or content-id endpoints) | Edge e.g. **`navigated_to`**, **`edge_context`** = `path_aggregate`, **`weight_hundredths`** from counts |
| Referrer -> page | **`lupo_referers`** / **`lupo_referers_daily`** (and raw **`lupo_referers_raw`** before rollup) | Edge e.g. **`referred_by`** or separate referrer **nodes** (product choice) |

**Doctrine:** **SILENT_HARVEST** and **PRD 34** - analytics are **operator-scoped** and **consent-aligned**; do not repurpose as covert cross-site surveillance. Public copy and features must stay inside documented ethics.

### Analytics-Derived Edge Authority Limit

Analytics-derived edges MUST be marked as:

- non-authoritative
- low-trust
- suggestion-only

They MUST NOT override:

- `prd_cluster`
- `atoms_toon`
- validated memory nodes
- explicit human/admin override

Analytics may suggest relationships. Analytics must not define truth.

Analytics-derived signals MUST remain:

- non-authoritative
- bounded
- explicitly invoked

Analytics MUST NOT be used for:

- automatic header inference
- relationship creation without validation
- graph mutation without human or PRD-governed process

**Materialization (design only):** A batch or incremental job MAY read **`lupo_paths`** / **`lupo_referers*`** and insert **`lupo_memory_edges`** with application-allocated **`memory_edge_id`** and **`created_ymdhis`** from **`gmdate('YmdHis')`** (no `NOW()`, no `DATETIME` columns). **`from_memory_node_id` / `to_memory_node_id`** must resolve through an explicit mapping from **`content_id`** to memory nodes if endpoints are not already memory rows - **do not** pretend `content_id` equals `memory_node_id` without allocator policy (**PRD 50**, reserved-ID doctrine).

### 4.4 Import strategy: raw URLs -> `content_id` -> memory graph

**Scope:** This subsection describes how **decades of Crafty-era path and referrer data** (often stored as **raw URLs** in legacy tables or in **`lupo_visits.path_url`** / JSON in **`transition_metadata`**) can be **normalized**, matched to **`lupo_contents`**, and optionally projected into **`lupo_memory_nodes`** / **`lupo_memory_edges`**. It is **specification** for tooling (e.g. **`scripts/import_crafty_syntax_paths.py`** or a PHP batch job), not normative vendor SQL.

**Relationship to existing import:** **`import_from_old_crafty_syntax.sql`** already maps **`livehelp_paths_firsts`** / **`livehelp_paths_monthly`** into **`lupo_paths`** using **`visit_recno` / `exit_recno`** as **`entercontentid` / `exitcontentid`** (Crafty-era numeric keys), and maps referrer dailies into **`lupo_referers`**. That leaves **URL-level reconciliation** (path strings - **`lupo_contents.file_path_from_root`** / **`source_url`**) and **optional memory-graph materialization** to application code. **Do not** assume legacy **`livehelp_*`** tables exist in a Lupopedia runtime DB; run URL normalization against **`lupo_visits`**, **`lupo_paths`** metadata, or a **staging** extract from the upgrade source.

#### 4.4.1 The problem

Crafty Syntax and early import rows often carry **page URLs**, not stable **`content_id`** references:

| Source (illustrative) | Column / field | Example | Issue |
|------------------------|----------------|---------|--------|
| Legacy path visits | `enter_url` / `exit_url` | `/products/category1.html` | Raw path, no `content_id` |
| Legacy referrer visits | `referer_url` | `https://google.com/search?q=-` | Full URL with query params |
| Daily referrer aggregates | `referer_domain` | `google.com` | Domain only |
| **`lupo_visits`** | `path_url` | same patterns | Must normalize before matching **`lupo_contents`** |

**Goal:** Map normalized URLs to **`lupo_contents.content_id`** so pages can back **memory nodes** (or **`lupo_contents`** mirror per **PRD 50 ??4.17**) and **edges** can use stable endpoints.

#### 4.4.2 Step 1 - Normalize URLs (application layer)

Prefer **Python or PHP** normalization (portable, testable). **Do not** rely on MySQL-only regex builtins as the only implementation path.

| Raw URL | Normalized form (policy) |
|---------|---------------------------|
| `/products/category1.html` | `/products/category1.html` |
| `/products/category1.html?ref=home` | `/products/category1.html` (strip query for static paths unless policy keeps it) |
| `https://www.example.com/docs/prd/16_lupopedia_headers.md` | `/docs/prd/16_lupopedia_headers.md` (strip scheme + host for **same-site** URLs; respect **`LUPOPEDIA_PUBLIC_PATH`**) |
| `/index.php?route=home` | `/index.php?route=home` or `/index.php?route=home` with `session` stripped (see **??4.4.8**) |
| External referrer | `google.com` (domain only for referrer-side nodes) |

**Rules (summary):** strip fragment; optionally strip query for static extensions; strip leading/trailing slashes consistently; for same-install absolute URLs, reduce to **public path**; for external referrers, extract **registrable domain** (policy-defined).

#### 4.4.3 Step 2 - Match URLs to existing content

Build a lookup: **`normalized_url` -> `content_id`** using **`lupo_contents`** (TOON: e.g. **`file_path_from_root`**, **`source_url`**, **`title`**).

**Matching priority (recommended):**

| Priority | Match type | Example |
|----------|------------|---------|
| 1 | Exact path | `/docs/prd/16_lupopedia_headers.md` -> `file_path_from_root` |
| 2 | Suffix / stem | `/prd/16_lupopedia_headers.md` -> `-/docs/prd/16_lupopedia_headers.md` |
| 3 | Token / slug contains | filename stem in path |
| 4 | No match | Create **`lupo_contents`** placeholder (explicit **`content_id`** allocator) or leave unlinked per policy |

Use **PDO_DB**, prepared statements, and explicit **`created_ymdhis`** (**`gmdate('YmdHis')`**). Avoid ambiguous **`LIKE '%-%'`** on large tables without indexes - prefer precomputed lookup maps in memory for batch jobs.

#### 4.4.4 Step 3 - Create content rows for unmatched URLs

When policy allows **synthetic content** for historical URLs: **`INSERT`** into **`lupo_contents`** with **every column listed explicitly**, deterministic **`content_id`** (no `AUTO_INCREMENT`), **`storage_type`** appropriate for external/historical reference (e.g. URI in **`source_url`** or dedicated field per TOON), **`created_ymdhis` / `updated_ymdhis`** from PHP, soft-delete columns set per schema.

#### 4.4.5 Step 4 - Create memory nodes for pages (optional)

If the product materializes the graph: **`INSERT`** **`lupo_memory_nodes`** with application-allocated **`memory_node_id`**, **`owner_actor_id`** (e.g. **WOLFIE** `1` for bulk import attribution), **`memory_type`**, **`memory_toon` / `memory_value`**, **`status`**, and **`content_hash`** computed in PHP if required - **not** DB `SHA2` as a doctrine shortcut unless the install schema explicitly defines it portably.

**Mapping `content_id` -> `memory_node_id`:** Use a **mapping table** or documented convention (**PRD 38**, **PRD 50**); **do not** equate IDs without product approval.

#### 4.4.6 Step 5 - Edges from path aggregates

Read **`lupo_paths`** (keyed by **`entercontentid` / `exitcontentid`** and **`count_num`**) **after** URL-`content_id` reconciliation if those integers were legacy recnos; and/or read **`lupo_paths_daily`** / **`lupo_paths_monthly`** when those rows carry **`from_content_id` / `to_content_id`**. Alternatively, derive transitions from normalized **`lupo_visits`** or **`lupo_paths_raw`** groupings.

**`lupo_memory_edges`:** set **`edge_type`** e.g. **`navigated_to`**, **`edge_context`** e.g. **`path_aggregate`**, **`weight_hundredths`** from visit counts (scale policy), **`created_ymdhis`** as **14-digit BIGINT UTC** from the aggregate period (e.g. `date_ymd` ?? noon UTC rule - **must match** existing analytics conventions), **`provenance_tool`** e.g. `crafty_syntax_import`, **`provenance_actor_id`** per attribution rules.

#### 4.4.7 Step 6 - Edges from referrer aggregates

Use **`lupo_referers`** / **`lupo_referers_daily`** (TOON names). **Referrer domain** endpoints MAY be **`lupo_memory_nodes`** with **`memory_type`** **`referrer_domain`** (or rows in **`lupo_contents`** if product treats referrers as content - choose one model and document it). **Edge** e.g. **`referred_by`**, **`edge_context`** **`referrer_aggregate`**, weight from visit counts.

There is **no** separate canonical **`lupo_referrer_nodes`** table in core schema at time of writing; **do not** assume it exists without checking **install SQL** / TOON.

#### 4.4.8 Step 7 - Query parameters and dynamic routes

Some URLs require **parameter-aware** normalization (e.g. **`index.php?route=home`** vs **`index.php?route=prd`**):

| Raw URL | Normalized (example policy) |
|---------|------------------------------|
| `/index.php?route=home&session=abc123` | `/index.php?route=home` (drop `session`) |
| `/index.php?route=prd&id=16` | `/index.php?route=prd` or include `id` if routing depends on it |
| `/api/transcript?channel=development` | Keep `channel=` if it changes resource identity |

Implement with **explicit allowlists** of query keys per route prefix; cover with unit tests.

#### 4.4.9 Step 8 - Import script structure (Python sketch)

A dedicated script (e.g. **`scripts/import_crafty_syntax_paths.py`**) SHOULD support:

- **`--dry-run`**, **`--batch-size`**, **`--years`** (filter aggregates), **`--skip-paths`**, **`--skip-referrers`**
- Functions **`import_paths`** / **`import_referrers`** returning counters (`urls_processed`, `content_matched`, `content_created`, `edges_created`, -)
- **Batch transactions** (e.g. 10k-50k rows) to limit lock time
- Logging compatible with **SILENT_HARVEST** / operator audit expectations

#### 4.4.10 Steps 9-10 - Node and edge types (imported data)

**Memory / content node flavors (illustrative):**

| Node type / role | Description | Example |
|------------------|-------------|---------|
| `page` | On-site page (matched or placeholder) | `/docs/prd/16_lupopedia_headers.md` |
| `external_page` | Off-site page (only if policy stores full URL) | `https://github.com/user/repo` |
| `referrer_domain` | Traffic source domain | `google.com` |
| `path_aggregate` | Optional metadata node for a daily/monthly bucket | Policy-defined key |

**Edge types (illustrative):**

| `edge_type` | Direction | Meaning | Weight |
|-------------|-----------|---------|--------|
| `navigated_to` | Page A -> Page B | Transition counts | From **`count_num`** |
| `referred_by` | Domain -> page | Referrer traffic | Visit counts |
| `co_occurred_with` | Page -> Page | Same session / window | Co-occurrence count |
| `temporal_trend` | Time bucket -> page | Popularity over time | Count per period |

Register strings next to writers (**??11** phase 1).

#### 4.4.11 Step 11 - Data volume (order-of-magnitude)

| Source | Estimated rows (illustrative) | After aggregation |
|--------|--------------------------------|-------------------|
| Raw path events (multi-year) | Tens of millions possible | Prefer **daily/monthly** aggregates for memory projection |
| **`lupo_paths`** / dailies | Sub-million to low millions | Import as edges with date context |
| Raw referrers | Tens of millions possible | Aggregate to daily before heavy edge materialization |
| Unique on-site paths | 10k-50k typical | **`lupo_contents`** + optional memory nodes |
| Unique referrer domains | 50k-200k possible | Referrer nodes or domain-only edges |

**Recommendation:** Materialize **aggregates** into the memory graph; keep **raw** rows in **`lupo_visits`**, **`lupo_paths_raw`**, and **`lupo_referers_raw`** (per retention policy) for audit and drill-down (**PRD 11**, admin Data hub).

#### 4.4.12 Step 12 - Performance

| Issue | Mitigation |
|-------|------------|
| Very large row counts | Batch commits; **`--years`** window; idempotent edge upsert policy |
| URL normalization | Pre-process in Python; cache **`url -> content_id`** |
| Content matching | In-memory dict from **`lupo_contents`** extract |
| Duplicate edges | Application-level dedupe keys or **`INSERT`** discipline with explicit conflict rules |
| Long runtime | Offline job; operator-visible progress logs |

### 4.5 Semantic Widget (PRD 28) and -also viewed-

The **Eye** and related UI (**PRD 28**) MAY query **`lupo_paths`** / **`lupo_paths_daily`** (when **`from_content_id` / `to_content_id`** are populated) / **`lupo_referers*`** for -people who viewed this also viewed--- and -top referrers,- **or** read **materialized** **`lupo_memory_edges`** if the product chooses to ETL analytics into the memory layer. Presentation copy MUST respect **SILENT_HARVEST** (no misleading -global user- claims).

### 4.6 Path and referrer storage tiers (raw vs aggregate, install schema)

**Intent:** Match the **Crafty Syntax** separation that worked at scale: **one family of tables for raw, append-only telemetry** and **separate tables for queryable aggregates** - avoiding mixed concerns in a single wide table.

**Problem with mixing raw and rolled-up rows in one table**

| Issue | Why it hurts |
|-------|----------------|
| Mixed concerns | Operators cannot tell -one click- vs -sum of clicks- without fragile conventions |
| Write vs read indexes | Indexes that help dashboards slow inserts on the firehose |
| Retention / GC | Trimming raw history is harder when the same table serves long-lived aggregates |
| Mental model | Drift from the **`visit_track` - daily/monthly** pattern operators already know from Crafty |

**Install tables (verify TOON; names use `lupo_` prefix at runtime)**

| Table | Purpose | Volume | Retention (policy defaults) |
|-------|---------|--------|-----------------------------|
| **`lupo_paths_raw`** | Each recorded **from_url -> to_url** transition | High | Short; **`is_aggregated`** then soft-delete or purge per operator config |
| **`lupo_paths_daily`** | Daily counts per URL pair + **`date_ymd`** | Medium | Medium; optional trim after monthly rollup |
| **`lupo_paths_monthly`** | Monthly counts per URL pair + **`date_ym`** | Low | Long / indefinite for trends |
| **`lupo_referers_raw`** | Per-hit referrer + landing **`page_url`** | High | Short; same rollup pattern as paths |
| **`lupo_visits`** | Per-page **`path_url`** hits (**existing**) | High | **`is_processed`** + GC (existing doctrine) |
| **`lupo_paths`** | **Content-id** transition counts (**existing**) | Lower | Long-lived; fed by Crafty import and/or GC from **`lupo_visits`** |

**Important:** **`lupo_paths`** (enter/exit **content ids**) and **`lupo_paths_daily`** (URL strings + optional content ids) are **complementary**, not duplicates. Import and Eye implementations choose the read path that matches how endpoints were stored.

**Aggregation flow (application / PHP, not normative SQL)**

1. **Deterministic scheduled job** (same *idea* as Crafty **`gc.php`**, **PRD 11**): select a cutoff **`created_ymdhis`** (14-digit UTC from **`gmdate('YmdHis')`**), bucket by calendar **`date_ymd`** derived in PHP from that packed UTC - **never** store Unix epoch or use **`NOW()`** in schema.
2. **Roll `lupo_paths_raw` -> `lupo_paths_daily`:** upsert counts grouped by normalized **`from_url`**, **`to_url`**, **`date_ymd`**. While aggregating, resolve **`from_content_id` / `to_content_id`** via **`lupo_contents`** (**??4.4**) so **PRD 28** can query by **`content_id`**.
3. **Mark raw rows** **`is_aggregated = 1`**. Physical **`DELETE`** of old raw rows is allowed only under operator policy and **maintenance-table** doctrine (see constitutional rules on hard delete); prefer **soft delete** when audit matters.
4. **Roll `lupo_paths_daily` -> `lupo_paths_monthly`** on a slower cadence; optionally trim old daily rows.
5. **Mirror** for **`lupo_referers_raw` -> `lupo_referers` / `lupo_referers_daily`**.

**Doctrine constraints**

- **No `AUTO_INCREMENT`:** **`path_raw_id`**, **`path_daily_id`**, **`path_monthly_id`**, **`referer_raw_id`** use **IdGenerator** (or explicit allocator) like other **`lupo_*`** tables.
- **Timestamps:** **`created_ymdhis` / `updated_ymdhis`** are **BIGINT UTC `YmdHis`** only.
- **Migration from an existing DB:** 4.0.x does **not** ship **ALTER** chains; new installs get these tables from **`install_new_lupopedia.sql`**. Existing sites follow **drop - reinstall** policy until **4.2.0** or a documented exception.

**Benefits**

- Raw stays append-friendly; aggregate tables stay read-friendly.
- GC can target **`paths_raw` / `referers_raw`** without touching **`lupo_paths`** content-id history.
- Aligns public narrative with **SILENT_HARVEST**: dashboards read aggregates; raw is operator-scoped drill-down.

---

## 5. Header inference mapping (conceptual)

This table is **logical**. Exact `edge_type` / `edge_context` strings are implementation constants and MUST be documented next to the writer that creates them.

| PRD 16 header key | Primary source (graph) | Primary source (thread) |
|-------------------|-------------------------|--------------------------|
| `artifact_type` | Classification edge or node `memory_type` | Channel default + message intent |
| `artifact_kind` | Same | Same |
| `content_id` | Task or artifact node | Thread slug / naming policy |
| `content_parent_id` | **references** target | Parsed PRD mentions |
| `channel_key` | Channel on task/thread if stored | **`lupo_channels`** |
| `title` / `summary` | Node text / task | Thread title / summary / messages |
| `memory_toon` | Set when export path known | Set after path chosen |
| `trust_tier` | Node **status** / policy | Channel or thread policy |
| Suggested **references** (non-authoritative) | Co-viewed **`exitcontentid`** / **`entercontentid`** from **`lupo_paths`** for same **`content_id`** | N/A |

---

## 6. APIs (illustrative)

Paths MUST sit under the real REST prefix and auth model (**PRD 07**). Names below are **spec placeholders**.

### 6.1 Header fields from task or file

```http
GET /api/memory/header-fields?task_id={id}
GET /api/memory/header-fields?file_path_from_root={path}
```

**Response (example):**

```json
{
  "status": "ok",
  "fields": {
    "artifact_type": "prd",
    "artifact_kind": "specification",
    "content_id": 50,
    "content_parent_id": null,
    "channel_key": "development",
    "title": "PRD 50: Agent Coordination Protocol",
    "summary": "Cross-agent coordination and transcript feed"
  },
  "resolution_status": "deterministic",
  "source": "memory_graph"
}
```

### 6.2 Thread context (web chat)

```http
GET /api/thread/context?dialog_thread_id={id}
```

Returns channel key, thread metadata, recent messages (bounded), optional list of files already attached to the thread (**`artifacts`** JSON, **`lupo_contents`**, or future link table - **verify TOON** before coding).

### 6.3 Register file node after write

```http
POST /api/memory/file-node
Content-Type: application/json
```

Body includes `file_path_from_root`, optional `task_id`, optional `dialog_thread_id`, resolved header snapshot, and **`content_hash`** aligned with **`lupo_memory_nodes.content_hash`** (TOON: `char(64)`).

### 6.4 Navigation and referrer hints (content-scoped)

Illustrative endpoints for **PRD 28** or header-assist tooling. Implement with **PDO_DB**, named placeholders, and **packed UTC** bounds (no `DATE_SUB(NOW(), -)` in stored logic - compute cutoff **`ymdhis`** in PHP).

```http
GET /api/analytics/co-viewed?content_id={id}&since_ymdhis={bigint}
GET /api/analytics/top-referrers?content_id={id}&since_ymdhis={bigint}
```

**Query source:** Prefer **`lupo_paths`** / **`lupo_referers`** for live analytics; optionally **`lupo_memory_edges`** if **??4.3** materialization is enabled. **Do not** use `from_memory_node_id = :content_id` unless product explicitly maps **`content_id`** to **`memory_node_id`**.

---

## 7. End-to-end workflows

### 7.1 Task-backed creation (graph-first)

```text
Task / graph writer creates memory node + edges (type, slug, references, ...)
        |
        v
GET header-fields (task_id) -> resolution_status
        |
        v
Write file with PRD 16 header (tick.py for UTC)
        |
        v
POST file-node -> new lupo_memory_nodes row + edges (created_by task, file path)
```

### 7.2 Chat-backed creation (thread-first)

```text
User in channel; thread has thread_key + messages
        |
        v
GET thread/context (dialog_thread_id)
        |
        v
Infer header fields (channel_key, content_id, refs from messages)
        |
        v
Write file; optional POST file-node; optional thread.artifacts update
```

### 7.3 Path analytics as suggestion input (MAY)

```text
Resolve lupo_contents.content_id for file_path (if any)
        |
        v
Query lupo_paths (and/or lupo_referers) for co-viewed / top referrers
        |
        v
Present candidate reference edges or header.summary hints; if not deterministic, STOP and REPORT "DOCTRINE NOT FOUND"
        |
        v
Human or graph writer confirms before canonical trust tier
```

ASCII only (no Unicode box drawing).

---

## 8. Tooling integration

- **`scripts/add_lupopedia_header_to_file.py`** (and batch variants) SHOULD accept optional **`--task-id`** and **`--dialog-thread-id`** when the caller has them.
- Call the APIs when online. If offline, deterministic resolution is still required; otherwise STOP and REPORT "DOCTRINE NOT FOUND".
- After a successful write, optionally call **POST file-node** so the graph stays aligned (**PRD 38**, **PRD 50**).

Example pattern (pseudocode, not shipped):

```python
# Pseudocode: merge graph + thread + path analytics + filesystem path
fields = fetch_from_task_or_thread(task_id, dialog_thread_id, file_path)
hints = fetch_co_viewed_referrers(content_id_for_path(file_path))  # MAY be used for display/logging only
present_suggestions(fields, hints)
if not is_deterministic(fields):
    # STOP; REPORT "DOCTRINE NOT FOUND"
    return
write_header(fields, tick_utc=...)
register_file_node_if_configured(...)
```

---

## 9. Deterministic Resolution Policy

Resolution MUST be deterministic OR FAIL.

resolution_status MAY be used for logging/UI only
resolution_status MUST NOT influence resolution outcome
resolution_status MUST NOT be numeric
resolution_status MUST NOT represent probability

Violation handling:

If resolution_status is used to:

- influence header values
- alter candidate selection
- bias resolution behavior

THEN:

STOP
REPORT "RESOLUTION_STATUS_MISUSE"

---

## 10. Schema and performance notes (4.0.x)

- **No standalone ALTER TABLE scripts** as normative delivery for 4.0.x: any new column belongs in **`install_new_lupopedia.sql`** per project migration doctrine.
- **Optional cache:** Prefer existing **`lupo_memory_nodes.context_json`** for small header snapshots before adding columns.
- **Indexes:** If querying by file path becomes hot, add an index via **install** (e.g. on `memory_toon` or a dedicated path field) - design with **PDO_DB** and TOON parity.
- **Separate `lupo_edge_types` registry** for memory edges is optional; if used, align **`edge_type_key`** with **`lupo_memory_edges.edge_type`** writers.

---

## 11. Implementation roadmap

| Phase | Deliverable | Owner | Priority |
|-------|-------------|-------|----------|
| 1 | Documented `edge_type` / `edge_context` constants for header-related graph writes | Product | HIGH |
| 2 | `GET .../header-fields` (task + path) backed by `lupo_memory_*` | Product | HIGH |
| 3 | `GET .../thread/context` backed by `lupo_dialog_*` + `lupo_channels` | Product | HIGH |
| 4 | `POST .../file-node` idempotent upsert pattern | Product | HIGH |
| 5 | Wire optional flags into header adder scripts | Product | MEDIUM |
| 6 | resolution_status scoring + logging | Product | MEDIUM |
| 7 | Tests + LILITH review of graph/thread parity | Product | MEDIUM |
| 4a | Crafty Syntax path import script (see **??4.4**) | Cursor | HIGH |
| 4b | URL normalization and content matching | Cursor | HIGH |
| 4c | MUST NOT create **`lupo_contents`** rows automatically; propose unmatched URLs for human approval | Cursor | HIGH |
| 4d | Create memory edges from path aggregates | Cursor | HIGH |
| 4e | Create memory edges from referrer aggregates | Cursor | HIGH |
| 4f | Performance optimization and batch processing | Cursor | MEDIUM |
| 4g | Deterministic GC: **`lupo_paths_raw` -> `lupo_paths_daily` -> `lupo_paths_monthly`**; **`lupo_referers_raw` -> `lupo_referers*`** (**??4.6**) | Cursor | HIGH |
| 8 | Optional ETL: **`lupo_paths`** / **`lupo_paths_daily`** / **`lupo_referers*`** -> **`lupo_memory_edges`** (edge_type conventions) | Product | LOW |
| 9 | Wire **??6.4** to Semantic Widget / book (**PRD 28**) with SILENT_HARVEST copy | Product | MEDIUM |

ETL into memory edges MUST be explicitly invoked.
ETL MUST NOT occur implicitly or automatically.

---

## 12. Open questions

| Topic | Notes |
|-------|--------|
| Single vs multiple `content_parent_id` | Header allows one scalar; multiple parents may live in graph only |
| Thread to `transcript_jsonl` string | Exact composition rule vs **PRD 16** field 22 |
| IDE agents without thread | Task-only and path fallback |
| **`lupo_contents` mirror** | **PRD 50 ??4.17** when engagement is required |
| **Referrer rows without `referer_content_id`** | How to attach edges when only domain-level daily exists |

---

## 13. References

- **PRD 16:** Lupopedia headers (22 keys, `content_id`, `dialog_transcript`).
- **PRD 38:** Memory unification (`lupo_memory_nodes`, `lupo_memory_edges`, export mirror).
- **PRD 50:** Agent coordination; memory nodes as **`lupo_contents`** for engagement.
- **PRD 28:** Semantic monitoring widget (Eye); may consume **4.3-6.4** signals.
- **PRD 34:** Federation / semantic network (ethics, public claims).
- **PRD 11:** Analytics and tracking tables (path/visit/referrer family).
- **`docs/doctrine/SILENT_HARVEST_DOCTRINE.md`:** Path and visit analytics ethics.
- **`database/.../import_from_old_crafty_syntax.sql`:** Crafty path/referrer -> **`lupo_*`** mapping.
- **PRD 10:** Tasks and workflow.
- **PRD 07:** Agents, faucets, API auth.

---

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

Vector similarity MAY provide suggestion-only context.
Vector similarity MUST NOT be used for header inference or truth resolution.

---

*This PRD complies with Lupopedia Constitutional Root Rules as a specification document; it does not introduce foreign keys, DB triggers, or ALTER-only migration chains for 4.0.x.*
