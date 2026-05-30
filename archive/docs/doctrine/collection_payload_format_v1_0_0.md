---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/collection_payload_format_v1_0_0.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/collection_payload_format_v1_0_0.md"
  status: "active"
  when_updated: "20260412124740"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/collection-payload-format-v1-0-0.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/collection-payload-v1"
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: "collection-payload-format-v1-0-0"
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: "Collection payload format v1.0.0 (AI actor ingestion)"
  summary: "Collection JSON v1.0.0: contracts, violation codes, normalization, deterministic ordering, graph integrity, ingestion metadata outside envelope; PRD 00/38/50/52\u201354/56/58/60 + orchestration edges."
---
# Collection payload format v1.0.0 (AI actor ingestion)

**Version:** `1.0.0` (semver for this **JSON shape** only; bump when fields are added, renamed, or requiredness changes).

**Purpose:** Machine-ingestible, **complete semantic closure** for one **collection** and its **tabs** and **nodes**, so any IDE or CLI actor can load context **without** inferring missing graph from prose. Aligns UI “Collections” semantics with **memory graph** persistence (**PRD 38**) and coordination (**PRD 50**).

**Normative consumer rules:** [PRD 00 section 22](../prd/00_root_constitutional_system_requirements.md), [PRD 38 section 18](../prd/38_memory_unification.md), [PRD 50 section 1.4](../prd/50_agent_coordination_protocol.md).

## 1. Canonical JSON shape

Top-level object **MUST** include the keys below. Types are logical; serializers **SHOULD** use UTF-8 JSON with string keys.

```json
{
  "collection_payload_version": "1.0.0",
  "collection_id": 0,
  "collection_name": "",
  "federation_node_id": 0,
  "memory_key": "",

  "tabs": [
    {
      "tab_id": 0,
      "tab_name": "",
      "node_ids": [""]
    }
  ],

  "nodes": [
    {
      "node_id": "",
      "title": "",
      "artifact_type": "",
      "memory_key": "",
      "file_path": "",
      "web_path": "",
      "content": "",
      "edges": [
        {
          "edge_type": "",
          "to_node_id": "",
          "provenance_actor_id": 0,
          "provenance_tool": ""
        }
      ]
    }
  ]
}
```

### 1.1 Field notes

| Field | Required | Meaning |
|-------|----------|---------|
| `collection_payload_version` | yes | Literal **`"1.0.0"`** for this spec revision. |
| `collection_id` | yes | Matches **`lupo_collections.collection_id`** (with `LUPO_TABLE_PREFIX`) when exported from DB. |
| `collection_name` | yes | Human label; need not be unique. |
| `federation_node_id` | yes | Scope for the collection (see **`lupo_collection_tabs.federations_node_id`** in install SQL; payload uses correct spelling **`federation_node_id`**). |
| `memory_key` | yes | Root context key for this collection export (e.g. TOON path or stable slug used by orchestrator). |
| `tabs` | yes | Array; may be empty only if product explicitly allows collection-without-tabs (default: **non-empty** when tabs exist in UI). |
| `tabs[].tab_id` | yes | Corresponds to **`lupo_collection_tabs.collection_tab_id`**. |
| `tabs[].tab_name` | yes | Display name (maps to tab **`name`**). |
| `tabs[].node_ids` | yes | Ordered list of **`nodes[].node_id`** values in tab order. |
| `nodes` | yes | All members referenced from **`tabs[].node_ids`** **MUST** appear here. |
| `nodes[].node_id` | yes | Stable string ID **within this payload** (correlator for edges); ingest **MAY** map to new **`memory_node_id`** per allocator doctrine. |
| `nodes[].title` | yes | Short label for actor UI / logs. |
| `nodes[].artifact_type` | yes | e.g. `text/markdown`, or Lupopedia **`artifact_type`** taxonomy when stricter. |
| `nodes[].memory_key` | yes | Canonical memory path for the node (often `.toon` path or header **`memory_key`**). |
| `nodes[].file_path` | yes | Repo-relative path when the node mirrors a file (same intent as LUPOPEDIA **`file_path_from_root`**); **`''`** if none. |
| `nodes[].web_path` | yes | Public URL per install; **`''`** if not web-published. |
| `nodes[].content` | yes | Raw body (often Markdown). Empty string allowed for pointer-only nodes if product policy allows. |
| `nodes[].edges` | yes | Array (empty allowed). **From**-endpoint is implicitly the **containing** `node_id` after ingest. |

### 1.2 Normalization, deterministic ordering, and graph integrity (normative)

**`memory_key` (top-level and per-node):**

- **MUST** be **POSIX-style path key** normalized: use **`/`** only (no backslashes); implementers **MUST** reject or normalize away `\\` in exporters.
- **MUST NOT** end with **`//`** or contain an empty path segment between slashes.
- **MUST NOT** embed **Windows drive letters** or drive-relative roots (e.g. `C:`, `D:\\`, `\\\\?\\`).

**`nodes[].node_id` (payload correlators):**

- **MUST** be **lowercase ASCII** for `a`–`z`, `0`–`9`, and **`_`** only (no spaces in the canonical serialized form).
- **MUST** be **underscore-normalized**: hyphen and space **MUST** map to **`_`**; **MUST NOT** contain consecutive underscores after normalization; **MUST NOT** have a leading or trailing **`_`**.
- **MUST** be **deterministic** for a given exporter input (same collection snapshot → same **`node_id`** strings); random suffixes **MUST NOT** appear unless the exporter documents a stable seed-based rule.

**Graph integrity (no dangling references):**

- **`tabs[].node_ids[]`** — every element **MUST** equal **`nodes[].node_id`** for **some** row in **`nodes[]`** in this payload.
- **`nodes[].node_id`** values **MUST** be **unique** within the payload; duplicates **MUST** be reported as **`COLLECTION_NODE_ID_COLLISION`**.
- **`nodes[].edges[].to_node_id`** — every value **MUST** equal **`nodes[].node_id`** for **some** row in **`nodes[]`**; **no dangling edges**.

**Deterministic export ordering (for stable diffs and audit hashes):**

- **`tabs[]`** **MUST** be sorted in **strict ascending `tab_id`** order.
- **`tabs[].node_ids[]`** **MUST** preserve the **tab’s member order** as defined by the product UI or compiler (order is semantic; do **not** re-sort this inner array by `node_id` unless the UI order is already `node_id` order).
- **`nodes[]`** **SHOULD** be sorted in **ascending `node_id`** order (lexicographic, UTF-8 byte order).
- **`nodes[].edges[]`** **SHOULD** be sorted in **ascending `to_node_id`** order (then **`edge_type`**, **`provenance_actor_id`** as tie-breakers for full determinism).

### 1.3 Payload edge object

Each element of **`nodes[].edges`** **MUST** include:

- **`edge_type`** — e.g. `doctrine_rule`, `reference`, `parent`, `child` (registry SHOULD be frozen in a follow-on TOON or table doc).
- **`to_node_id`** — **MUST** reference **`nodes[].node_id`** in the **same** payload (no dangling targets).
- **`provenance_actor_id`** — actor who asserted the edge (registry-backed).
- **`provenance_tool`** — e.g. `collection_exporter_v1`, `wolfie_ide`.

Ingest to **`lupo_memory_edges`:** **`from_memory_node_id`** = persisted ID of the **from** node; **`to_memory_node_id`** = persisted ID of **`to_node_id`** after all nodes are materialized. Additional columns (`edge_context`, `edge_status`, `weight_hundredths`, timestamps, soft delete) **MUST** follow install SQL and **PRD 38**.

### 1.4 Contract surfaces (v1.0.0 normative)

Authoritative orchestration text: [PRD 50 section 1.4–1.5](../prd/50_agent_coordination_protocol.md). This doctrine binds the **collection JSON** layer only.

| Surface | Rule |
|---------|------|
| **Input** | Exactly **one** logical **JSON object** per collection handoff; orchestrator **MUST NOT** interleave unrelated chat or parallel instructions in the **same** turn as the payload unless policy explicitly allows (default: **do not** mix). |
| **Output** | Actor **MUST** emit **`Node received.`** as the **first line** of the response, then after successful ingest emit **`Collection loaded.`** on its **own** line (per **PRD 50** §§1.4.2–1.4.4). |
| **Termination** | **`<TEST_COMPLETE>`** is **examiner-only** for competency probes that apply to the same thread; actors **MUST NOT** emit it or continue probe-scoped traffic after it. |

### 1.5 Violation codes (collection ingestion)

Log **one** primary code where possible; align with [PRD 50 section 1.2](../prd/50_agent_coordination_protocol.md) and **`MULTI_AGENT_COORDINATION_DOCTRINE`**.

| Code | When |
|------|------|
| `COLLECTION_PAYLOAD_INVALID` | Missing/wrong **`collection_payload_version`**, required keys, JSON shape, or type constraints fail validation. |
| `COLLECTION_NODE_ID_COLLISION` | Duplicate **`nodes[].node_id`** or exporter instability violates uniqueness. |
| `ACTOR_OUT_OF_COLLECTION_SCOPE` | Actor reasons or cites graph outside this payload’s **`nodes[]`** / **`tabs[]`** closure without orchestrator expansion. |
| `ACTOR_SCHEMA_VIOLATION` | Channel/thread/faucet/`actor_id` metadata invalid for the write path, or header envelope inconsistent with resolved identity. |
| `KNOWLEDGE_ACK_INVALID` | First-line ack is not exactly **`Node received.`** when the protocol requires it for this handoff. |

## 2. Actor ingestion rules (summary)

- Actors **MUST** treat the payload as **complete** context for that collection: **MUST NOT** invent nodes, edges, or paths not present.
- **Normative:** Human-open tabs or IDE browsing context **MUST NOT** be treated as part of the collection payload.
- **Normative — collection-scoped reasoning:** Actors **MUST** restrict reasoning to nodes inside **this** payload unless the orchestrator authorizes expansion.
- Actors **MUST NOT** treat omitted optional future fields as license to hallucinate (unknown keys **SHOULD** be ignored by v1.0.0 consumers).
- Persisted rows **MUST** use **`LUPO_TABLE_PREFIX`**, prepared statements, and **reserved-ID / allocator** rules for new **`memory_node_id`** / **`memory_edge_id`** values.
- **`ingestion_actor_id`** and **`ingestion_mode`** **MUST** remain **outside** the v1.0.0 JSON envelope — session or transport metadata only; **MUST NOT** be invented as undocumented top-level payload keys ([PRD 50](../prd/50_agent_coordination_protocol.md) §1.4.1).

**Full operational law** (prepare → send → ingest → confirm → verify → terminate; **`ingestion_actor_id`** / **`ingestion_mode`** as session metadata; UI tabs vs export): [PRD 50 section 1.4](../prd/50_agent_coordination_protocol.md) subsections **1.4.1–1.4.7**.

## 3. Outbound documentation graph edges

Normative cross-references represented as rows for importers (`lupo_metadata`, sidecars, or HERMES). **`relationship`** is descriptive; persist as **`reason`** or metadata where your edge model requires it.

| From (this doctrine) | To | `edge_type` | `relationship` |
|------------------------|-----|-------------|----------------|
| `collection_payload_format_v1_0_0.md` | `docs/prd/00_root_constitutional_system_requirements.md` | `doctrine_rule` | `defines` |
| `collection_payload_format_v1_0_0.md` | `docs/prd/38_memory_unification.md` | `doctrine_rule` | `binds_memory_graph` |
| `collection_payload_format_v1_0_0.md` | `docs/prd/50_agent_coordination_protocol.md` | `doctrine_rule` | `governs_ingestion` |
| `collection_payload_format_v1_0_0.md` | `docs/doctrine/AGENT_ORCHESTRATION.md` | `doctrine_rule` | `orchestration_surface` |
| `collection_payload_format_v1_0_0.md` | `docs/doctrine/VALIDATION_PATTERNS.md` | `doctrine_rule` | `validator_input` |
| `collection_payload_format_v1_0_0.md` | `docs/prd/52_memory_graph_focus_manifest.md` | `doctrine_rule` | `graph_focus_lens` |
| `collection_payload_format_v1_0_0.md` | `docs/prd/53_runtime_guard.md` | `doctrine_rule` | `runtime_guard_input` |
| `collection_payload_format_v1_0_0.md` | `docs/prd/54_actor_compliance.md` | `doctrine_rule` | `compliance_evaluation` |
| `collection_payload_format_v1_0_0.md` | `docs/prd/56_probe_harness_v2.md` | `doctrine_rule` | `probe_harness` |
| `collection_payload_format_v1_0_0.md` | `docs/prd/58_transcript_filter.md` | `doctrine_rule` | `transcript_classification` |
| `collection_payload_format_v1_0_0.md` | `docs/prd/60_orchestrator_scheduler.md` | `doctrine_rule` | `orchestrator_scheduler` |

### 3.1 Orchestration stack edges (runtime guard upgrade line)

Normative **documentation** edges for the **guard → compliance → scheduler → harness → transcript** chain (importers: `lupo_metadata`, sidecars, HERMES). Paths are repo-relative.

| From | To | `edge_type` | `relationship` |
|------|-----|-------------|----------------|
| `docs/prd/53_runtime_guard.md` | `docs/prd/54_actor_compliance.md` | `doctrine_rule` | `violations_recorded` |
| `docs/prd/54_actor_compliance.md` | `docs/prd/60_orchestrator_scheduler.md` | `doctrine_rule` | `priority_adjustment` |
| `docs/prd/60_orchestrator_scheduler.md` | `docs/prd/56_probe_harness_v2.md` | `doctrine_rule` | `dispatches_probes` |
| `docs/prd/56_probe_harness_v2.md` | `docs/prd/58_transcript_filter.md` | `doctrine_rule` | `telemetry_hygiene` |

**Transcript filter hub:** [PRD 58](../prd/58_transcript_filter.md) section **7** lists **outbound** edges to coordinating PRDs (constitutional, headers, memory, session, coordination, graph, focus manifest, guard, compliance, harness, scheduler).

## 4. Related

- [`AI_ACTOR_COMPETENCY_TEST_PATTERN.md`](AI_ACTOR_COMPETENCY_TEST_PATTERN.md), [`AI_ACTOR_KNOWLEDGE_UPDATE_PROTOCOL.md`](AI_ACTOR_KNOWLEDGE_UPDATE_PROTOCOL.md), [`AI_ACTOR_PROBE_HARNESS_AND_GUARDS.md`](AI_ACTOR_PROBE_HARNESS_AND_GUARDS.md)
- [`AGENT_ORCHESTRATION.md`](AGENT_ORCHESTRATION.md) (collection ingestion surface), [`VALIDATION_PATTERNS.md`](VALIDATION_PATTERNS.md)
- **System upgrade (guard stack):** [PRD 53](../prd/53_runtime_guard.md), [PRD 54](../prd/54_actor_compliance.md), [PRD 56](../prd/56_probe_harness_v2.md), [PRD 58](../prd/58_transcript_filter.md), [PRD 60](../prd/60_orchestrator_scheduler.md)
- **File → JSON:** [`scripts/collection_compiler.py`](../../scripts/collection_compiler.py) — Markdown globs under a repo root → v1.0.0 payload (non-normative convenience; field rules here remain authoritative).
- Install DDL: `lupo_collections`, `lupo_collection_tabs`, `lupo_memory_nodes`, `lupo_memory_edges` in `database/lupopedia/mysql/install/install_new_lupopedia.sql`

## 5. Tooling backlog (non-normative)

- **Exporter** — PHP or Python: DB / richer sources → v1.0.0 JSON (beyond glob compiler).
- **Validator** — JSON Schema, dangling `node_ids`, required keys, `collection_payload_version`.
- **DB writer** — validated payload → `INSERT`/`UPDATE` **`lupo_memory_*`** + edge rows per allocator (not the same as the file compiler).

---

This output complies with Lupopedia Constitutional Root Rules.
