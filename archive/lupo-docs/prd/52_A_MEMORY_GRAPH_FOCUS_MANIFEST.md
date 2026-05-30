---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/52_A_MEMORY_GRAPH_FOCUS_MANIFEST.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/52_A_MEMORY_GRAPH_FOCUS_MANIFEST.md"
  status: draft
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/52_memory_graph_focus_manifest.toon
  atoms_toon: null
  transcript_jsonl: 0/development/52-memory-graph-focus-manifest
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_52_A_MEMORY_GRAPH_FOCUS_MANIFEST
  title: "PRD 52: Memory Graph Focus Manifest (Runtime Context Selector)"
  summary: "Runtime lens over lupo_memory_edges: active_context, edge include/exclude, trust tier, max_depth, focus_nodes; storage options; agent traversal; replaces static Contexts UI concept without reviving lupo_contexts_map."
---
# PRD 52: Memory Graph Focus Manifest (Runtime Context Selector)

## 1. Purpose

Define a **Focus Manifest**: a **runtime context selector** that limits how agents and tools **traverse** the memory graph (**`lupo_memory_nodes`**, **`lupo_memory_edges`** per **PRD 38**). The manifest does **not** replace edges or nodes; it **filters** which edge types, trust tiers, and depths are honored during a given load or task.

**Problem:** Unbounded traversal follows every outgoing edge type and can overload context windows or imply false authority (e.g. mixing **`authored_by`** sightseeing with **`implements`** task chains).

**Non-goal:** Reintroducing the removed **Semantic Widget** **Contexts** icon or the legacy **`lupo_contexts_map`** table as the source of truth. Those were **static** and **widget-local**. The Focus Manifest is **dynamic**, **policy-aware**, and **reusable** across CLI, IDE agents, and server loaders (**PRD 50**).

---

## 2. Background (what was lost)

| Former surface | Limitation |
|----------------|------------|
| **Contexts** icon (**PRD 28**) | Removed from the Eye command bar; no UI affordance for ???what to emphasize??? when reading memory. |
| **`lupo_contexts_map`** | Fixed rows; not a substitute for graph-native **edge_type** / **edge_status** semantics (**PRD 38**). |

**Intent preserved:** Operators and agents still need a **declared lens** (???header migration???, ???edge verification???, ???registry pass???) so traversal stays **finite** and **purposeful**.

---

## 3. Definitions

| Term | Meaning |
|------|---------|
| **Focus Manifest** | A structured document (JSON or equivalent) or a **memory node** payload that declares **`active_context`**, filters, depth, and optional anchor nodes. |
| **Traversal** | Walking **`lupo_memory_edges`** from a **start** node subject to manifest rules. |
| **Edge filter** | Allow/deny lists keyed by **`edge_type`** (and optionally **`edge_status`** / **`edge_context`** when product policy extends the schema). |
| **Trust tier filter** | Restricts which **nodes** (or edges) participate based on classification aligned with **PRD 16** header **`trust_tier`** vocabulary and **PRD 38** node classification (e.g. canonical vs staging); exact column mapping is implementation-defined but MUST be explicit in code. |
| **Focus nodes** | Optional list of **`memory_node_id`** values that **override** free graph expansion when non-empty (agents concentrate on this set and edges **between** them only, unless spec extended). |

---

## 4. Normative schema (JSON)

Implementations MAY store this object inside a file, a **`context_json`** / approved JSON column on **`lupo_memory_nodes`**, or a dedicated row shape. Field names are **stable** for interchange.

```json
{
  "focus_manifest_version": "1",
  "active_context": "header_migration",
  "description": "Concentrate on PRDs, header patterns, and verification edges.",
  "edge_filter": {
    "include": ["references", "implements", "depends_on"],
    "exclude": ["authored_by", "observed_by", "archived_to"]
  },
  "trust_tier_filter": ["canonical", "seed"],
  "max_depth": 3,
  "start_memory_node_id": "100000000000000001",
  "focus_memory_node_ids": [
    "100000000000000002",
    "100000000000000003"
  ]
}
```

### 4.1 Field rules

| Field | Required | Description |
|-------|----------|-------------|
| **`focus_manifest_version`** | Yes | Integer or semver string; bump when breaking consumers. |
| **`active_context`** | Yes | Short machine key (snake_case) for logs and UI. |
| **`description`** | No | Human-readable scope. |
| **`edge_filter.include`** | No | If present and non-empty, only these **`edge_type`** values are followed (after exclusions). |
| **`edge_filter.exclude`** | No | These **`edge_type`** values are never followed. |
| **`trust_tier_filter`** | No | If present and non-empty, only nodes matching these tiers participate. |
| **`max_depth`** | No | Default **3** when omitted; **0** means start node only. |
| **`start_memory_node_id`** | No | Preferred BFS/DFS root when not implied by caller (e.g. session **`active_memory_ref`**). |
| **`focus_memory_node_ids`** | No | If non-empty, implementations SHOULD restrict expansion to this set (and edges with both endpoints in the set) unless a product flag allows ???one hop out???. |

**Numeric IDs:** JSON numbers MUST be safe for 64-bit consumers; prefer **string** encoding of **`memory_node_id`** in interchange files to avoid float rounding in weak parsers.

---

## 5. Where it lives (storage options)

| Option | Path / table | Pros | Cons |
|--------|----------------|------|------|
| **A. JSON file** | e.g. **`lupo-config/focus_manifest.json`** (name not normative until shipped) | Version-controlled, easy for IDE agents | Not multi-tenant; merge conflicts |
| **B. DB row** | Dedicated table or **`lupo_metadata`** keyed by actor/channel | Queryable, auditable | Requires schema + CRUD |
| **C. Memory node** | **`lupo_memory_nodes`** with **`memory_type`** = **`focus`** (or agreed constant) | Manifest is **in the graph**; edges can link ???this focus??? ??? ???important nodes??? | Needs allocator + export mirror rules (**PRD 38**) |

**Recommendation (product default):** **Option C** ??? represent each named focus as a **memory node** so **`lupo_memory_edges`** can attach **references** / **implements** from the focus node to anchor artifacts. **Option A** remains valid for **local-only** IDE workflows until the node type is implemented.

**Constitutional note:** No new **`CREATE TABLE`** in this PRD until **install SQL** is updated in a separate change set (**PRD 00** / single-install doctrine). Until then, **file-based** manifests are documentation-complete.

---

## 6. How agents and loaders use it

1. Resolve **start node** (session, task binding, or CLI **`--memory-node-id`**).
2. Load the **active** Focus Manifest (file, DB, or **`memory_type`** = **`focus`** node).
3. Fetch outgoing edges from the DB (**PDO_DB**, **PRD 38**).
4. Drop edges whose **`edge_type`** is in **`exclude`** (if set).
5. If **`include`** is non-empty, keep only those **`edge_type`** values.
6. Drop target nodes that fail **`trust_tier_filter`** (if set).
7. Stop when depth reaches **`max_depth`**.
8. If **`focus_memory_node_ids`** is non-empty, apply the restricted subgraph rule in **??4.1**.

**Determinism:** Edge ordering for equal weight SHOULD be stable (e.g. sort by **`to_memory_node_id`**, then **`edge_type`**).

---

## 7. How focus is set

| Method | Description |
|--------|-------------|
| **Manual** | Edit JSON or update the focus memory node; commit when file-based. |
| **Chat / operator command** | e.g. **`/focus header_migration`** resolves a registered manifest key (implementation). |
| **Task context** | When a **`lupo_tasks`** (or successor) row is created, copy **`active_context`** from task type. |
| **Suggestion** | An agent proposes a manifest; human confirms before persisting (**no silent scope expansion**). |

---

## 8. Security and authority

- **Server-side** graph APIs MUST NOT treat a client-supplied manifest as **authorization**. Manifests adjust **which edges are returned for a given read**, not **which rows the caller may write**.
- **Trust tier** and **membership** checks remain governed by **AuthService** / channel policy (**PRD 50**), not by the manifest alone.
- **Webroot exposure:** If a Focus Manifest is stored as a **JSON file** under a **web-served** tree (**??5** option A), assume it is **publicly readable** unless the server **explicitly** blocks that path or extension. **Visibility is default; protection must be explicit** (WOLFIE doctrine, **`lupopedia_quick_reference.md`**). Do **not** embed secrets in manifest files; **`lupopedia-config.php`** remains the **only** sanctioned secret container.

---

## 9. Relationship to other PRDs

| PRD | Relationship |
|-----|----------------|
| **PRD 38** | Canonical memory graph; export mirror; edge dimensions. |
| **PRD 51** | Thread and graph as header authority; focus manifest narrows **which** graph paths matter for a run. |
| **PRD 28** | **Contexts** icon removed; this PRD restores the **concept** of scoped attention without restoring the old table/UI. |
| **PRD 50** | Agent coordination, tasks, and chat; manifest can be bound to session or task metadata. |
| **PRD 16** | **`trust_tier`** vocabulary alignment for filters. |

---

## 10. Implementation checklist (non-normative)

1. Choose storage (**??5**) for first ship.
2. Add loader in **`memory.php` / `load-context`** path (or successor) that accepts optional **`focus_manifest_key`**.
3. Document one or two **stock** manifests (e.g. **`registry_pass`**, **`header_migration`**) in **`lupo-docs/doctrine/`** or channel threads.
4. Extend **PRD 28** Eye docs only if a **future** UI exposes manifest picker (optional).

---

**Status:** **DRAFT** ??? WOLFIE authorization to specify; no install DDL required until implementers add **`memory_type`** = **`focus`** or a config file contract.
