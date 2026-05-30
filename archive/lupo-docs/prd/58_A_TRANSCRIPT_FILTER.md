---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/58_A_TRANSCRIPT_FILTER.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/58_A_TRANSCRIPT_FILTER.md"
  status: draft
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/58_transcript_filter.toon
  atoms_toon: null
  transcript_jsonl: 0/development/58-transcript-filter
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_58_A_TRANSCRIPT_FILTER
  title: "PRD 58: Transcript Filter (Multi-Actor Orchestration Hygiene)"
  summary: "Transcript filter: PRD 61+00 alignment; full violations; deterministic sequence_id+segmentation; faucet/channel; collection+ingestion; provenance actor; PRD 00 section 21.4; hub edges 00/16/38/44/50-54/56/60/61."
---
# PRD 58: Transcript Filter (Multi-Actor Orchestration Hygiene)

## 1. Purpose

Define how **raw** multi-actor transcripts become **durable, auditable, policy-safe** records suitable for operators, [PRD 54](54_actor_compliance.md), and analytics. The filter runs **after** [PRD 53](53_runtime_guard.md) decisions and **probe close** signals from [PRD 56](56_probe_harness_v2.md).

### 1.1 Contract surfaces (constitutional alignment)

Transcript classification and **clean** export **MUST** align with **[PRD 00 section 21.3](../prd/00_root_constitutional_system_requirements.md)** and **[PRD 50 section 1.5](50_agent_coordination_protocol.md)**:

- **Input contract** ??? When **artifact-only** applies, **raw** lines outside the harness-delimited artifact **MUST** be stripped or moved to **`filter_event`** as non-normative noise (never promoted to graded input in **clean** transcript).
- **Output contract** ??? **Self-grade** and **commentary-as-substitute** for the artifact **MUST** be redacted or tagged for compliance ([PRD 54](54_actor_compliance.md)).
- **Termination contract** ??? **`<TEST_COMPLETE>`** is **examiner-only**; **clean** segments **MUST NOT** include post-termination probe traffic for the same **`probe_id`** (map to **`ACTOR_CONTINUED_AFTER_TERMINATION`** on fanout).

### 1.2 Violation codes (canonical set for filter fanout)

**Authoritative semantics:** [PRD 00 section 21.2](../prd/00_root_constitutional_system_requirements.md). The filter **MUST** emit the **same** **`violation_code`** strings as the guard when denormalizing for [PRD 54](54_actor_compliance.md) (no aliases):

| Code | Transcript filter handling (summary) |
|------|----------------------------------------|
| `ACTOR_SELF_EVAL_FORBIDDEN` | Redact / tag self-grade lines in **clean**; preserve in **audit**. |
| `ACTOR_PARROT_LOOP` | Tag or collapse parrot spans per **TF** rules. |
| `ACTOR_ROLE_COLLISION` | Flag segment boundary errors; do not merge incompatible roles. |
| `ACTOR_CONTINUED_AFTER_TERMINATION` | Excise post-`<TEST_COMPLETE>` lines from **probe** segment. |
| `KNOWLEDGE_ACK_INVALID` | Tag malformed ack lines; do not treat as successful handoff. |
| `ACTOR_OUT_OF_COLLECTION_SCOPE` | Remove or tag out-of-scope citations (see **3.1**). |
| `ACTOR_SCHEMA_VIOLATION` | Redact malformed rows; include faucet/channel/header failures. |
| `PROBE_BOUNDARY_VIOLATION` | Mark missing-artifact turns; align with [PRD 53](53_runtime_guard.md). |
| `EXTERNAL_ACTOR_UNCONSTRAINED` | Quarantine or strip per external-band policy. |
| `COLLECTION_PAYLOAD_INVALID` | Reject or isolate messages bound to invalid payload digests. |
| `COLLECTION_NODE_ID_COLLISION` | Tag compiler/exporter instability; do not merge ambiguous **node_id** spans. |

### 1.3 PRD 61 invariant alignment (normative)

Transcript hygiene **MUST** align with **[PRD 61 section 2](61_doctrine_consolidation_shorthand_compiler.md)** as specialised here: **deterministic ordering** for **`sequence_id`** and **segmentation** ??? **section 4.1**; **faucet** / **channel-thread** / **provenance actor** ??? **section 3.1**, **9**; **contract surfaces** ??? **section 1.1**; **violation code** fanout ??? **section 1.2**; **browser-tab prohibition** ??? **section 3.1**; **ingestion-mode** ??? **section 3.1**; **state-machine anchoring** ??? **section 4.2**.

## 2. Definitions

| Term | Definition |
|------|------------|
| **Raw transcript** | Append-only message log as captured from chat/IDE/API. |
| **Clean transcript** | Filtered view with policy violations removed or redacted. |
| **Segment** | Contiguous message range scoped by **probe_id** or **collection_load_id**. |
| **Sequence ID** | Monotonic integer per **thread**; **MUST NOT** decrease. |

## 3. Responsibilities

1. Remove **actor-to-actor noise** (unsanctioned side channels embedded in prose) per configurable policy.
2. Strip or tag **self-grading** lines flagged by guard.
3. Remove **commentary** outside allowed envelope when **artifact-only** mode applies.
4. Remove **continuation after STOP** for closed probes.
5. **Segment probes** ??? boundaries at **probe_start** / **`<TEST_COMPLETE>`**.
6. **Segment collections** ??? boundaries at **`Collection loaded.`** / context clear events ([PRD 50](50_agent_coordination_protocol.md) section 1.4).
7. **Maintain monotonic sequence IDs** ??? stable ordering for downstream exporters ([PRD 16](16_lupopedia_headers.md) transcript rules).

### 3.1 Normative filter constraints

- **Browser-tab context:** The transcript filter **MUST** ignore browser-tab metadata (**`edge_all_open_tabs`** and similar) as **instruction input** when classifying or redacting messages.
- **Faucet identity:** **Missing or incorrect faucet metadata** (when required) **MUST** be flagged as **`ACTOR_SCHEMA_VIOLATION`** and the affected **raw** lines **MUST** be **redacted** or replaced with a deterministic placeholder in **clean** transcript (full detail preserved only in **audit**).
- **Channel / thread:** The filter **MUST** validate **`channel_id`** and **`thread_id`** against registry + membership **before** accepting messages into a **segment** bound for **clean** export.
- **Collection-scoped reasoning:** The filter **MUST** **remove** or **tag** references **outside** the **active collection** unless the orchestrator **explicitly** authorizes expansion (align with **`ACTOR_OUT_OF_COLLECTION_SCOPE`**).
- **Ingestion mode:** The filter **MUST NOT** treat **`ingestion_mode: read-only`** as a **violation source** by itself; only **write-mode** violations (false **`Collection loaded.`**, illegal persists, scope breaches on writes) **MUST** propagate to compliance ([PRD 50](50_agent_coordination_protocol.md) section **1.4.1**, [PRD 54](54_actor_compliance.md) section **4.5**).
- **Provenance actor:** The filter **MUST NOT** attribute **violations**, **redactions**, or **filter_event** subjects to the wrong **`actor_id`**; attribution **MUST** match server-resolved identities ([PRD 54](54_actor_compliance.md) section **11**).

## 4. Transcript structure (logical model)

```json
{
  "transcript_document_version": "1.0.0",
  "session_id": "string",
  "thread_id": "string",
  "federation_node_id": 1,
  "segments": [
    {
      "segment_kind": "probe",
      "probe_id": "uuid",
      "start_sequence": 1,
      "end_sequence": 42,
      "messages": []
    }
  ]
}
```

### 4.1 Deterministic ordering (normative)

The filter **MUST** enforce **deterministic ordering** ([PRD 61](61_doctrine_consolidation_shorthand_compiler.md) invariant **4**) for:

- **`sequence_id`** (monotonic per **`thread_id`**; ties broken by documented secondary key, e.g. **`created_ymdhis`**, then lexicographic **`message_id`** if present ??? **never** random).
- **Segment boundaries** ??? **`start_sequence` / `end_sequence`** **MUST** be assigned in stable pass order (single forward scan or equivalent reproducible algorithm); **segment_id** allocation **MUST** use a **documented monotonic** rule per **`thread_id`**.
- **Messages inside a segment** ??? **MUST** appear in **ascending sequence** order in **clean** and **audit** exports.

### 4.2 Constitutional segmentation anchor

**Transcript segmentation** (probe, collection, routing-class spans) **MUST** follow the **constitutional** state machines in **[PRD 00 section 21.4](../prd/00_root_constitutional_system_requirements.md)**. This PRD???s **segment** kinds and **TF-** rules **specialise** those graphs for transcript hygiene **without** inventing alternate closure semantics (e.g. cannot ???re-open??? a probe segment after **`<TEST_COMPLETE>`** in **clean** export).

## 5. Filtering rules

| Rule ID | Description |
|---------|-------------|
| **TF-001** | No **unsanctioned** actor-to-actor instructions (???tell the other model??????). |
| **TF-002** | Collapse **duplicate artifacts** within same **probe** round (keep last verified). |
| **TF-003** | Remove **hallucinated system instructions** (e.g. fake ???admin said???). |
| **TF-004** | **No cross-probe contamination** ??? messages **after** **`<TEST_COMPLETE>`** for **P** **MUST NOT** appear inside **P**???s segment in **clean** export. |

## 6. Outputs

1. **Clean transcript** ??? operator UI + read-only API.
2. **Audit log** ??? immutable stream of **guard_event** + **filter_event**.
3. **Compliance violations** ??? denormalized list for [PRD 54](54_actor_compliance.md).

### 6.1 `filter_event` example

```json
{
  "filter_event_version": "1.0.0",
  "action": "redact_self_grade",
  "sequence_range": [18, 18],
  "rule_id": "TF-002",
  "provenance_tool": "transcript_filter_v0",
  "created_ymdhis": 20260412115019
}
```

## 7. Normative documentation graph ??? hub (???Transcript Filter ??? coordinating PRDs???)

**Hub completeness:** Rows below include **PRD 00** (constitutional contracts + state machines), **PRD 52** (focus manifest), **PRD 56** (probe harness), **PRD 60** (scheduler timeline), plus guard/compliance/coordination PRDs.

| From | To | `edge_type` | `relationship` |
|------|-----|-------------|----------------|
| `58_transcript_filter.md` | `lupo-docs/prd/00_root_constitutional_system_requirements.md` | `doctrine_rule` | `constitutional_alignment` |
| `58_transcript_filter.md` | `lupo-docs/prd/16_lupopedia_headers.md` | `doctrine_rule` | `transcript_metadata` |
| `58_transcript_filter.md` | `lupo-docs/prd/38_memory_unification.md` | `doctrine_rule` | `graph_export_hygiene` |
| `58_transcript_filter.md` | `lupo-docs/prd/44_session_config_and_transcript.md` | `doctrine_rule` | `session_transcript` |
| `58_transcript_filter.md` | `lupo-docs/prd/50_agent_coordination_protocol.md` | `doctrine_rule` | `coordination_feed` |
| `58_transcript_filter.md` | `lupo-docs/prd/51_memory_graph_as_source_of_truth.md` | `doctrine_rule` | `toon_export_input` |
| `58_transcript_filter.md` | `lupo-docs/prd/52_memory_graph_focus_manifest.md` | `doctrine_rule` | `focus_context` |
| `58_transcript_filter.md` | `lupo-docs/prd/53_runtime_guard.md` | `doctrine_rule` | `upstream_violations` |
| `58_transcript_filter.md` | `lupo-docs/prd/54_actor_compliance.md` | `doctrine_rule` | `violation_fanout` |
| `58_transcript_filter.md` | `lupo-docs/prd/56_probe_harness_v2.md` | `doctrine_rule` | `probe_segments` |
| `58_transcript_filter.md` | `lupo-docs/prd/60_orchestrator_scheduler.md` | `doctrine_rule` | `task_timeline` |
| `58_transcript_filter.md` | `lupo-docs/prd/61_doctrine_consolidation_shorthand_compiler.md` | `doctrine_rule` | `invariant_checklist_transcript_filter` |

## 8. Federation node rules

Filtering policy **MAY** differ by **`federation_node_id`**; **MUST NOT** merge segments across nodes without explicit sync ([PRD 09](09_federation_sync.md)).

## 9. Provenance rules

Every **filter_event** **MUST** carry **`provenance_tool`** and actor **or** system id per [PRD 16](16_lupopedia_headers.md). Redactions and **violation** fanout **MUST** attribute **`actor_id`** to the **server-resolved** speaker under test ??? **MUST NOT** swap examinee, examiner, or system rows ([PRD 54](54_actor_compliance.md) section **11**).

## 10. Memory graph integration

**Clean** segments **MAY** be summarized into **`lupo_memory_nodes`** for long-term audit; **edges** **MUST** reference **node-to-node** rows only ([PRD 38](38_memory_unification.md)).

## 11. Actor role definitions

The filter is **not** an examinee; it is a **system actor** ( **`type: system`** in provenance terms) or dedicated **service** identity registered in **`lupo_actors`** when assigned.

## 12. Related specifications

- [PRD 00](00_root_constitutional_system_requirements.md) (sections **21.2???21.4**), [PRD 50](50_agent_coordination_protocol.md), [PRD 52](52_memory_graph_focus_manifest.md), [PRD 53](53_runtime_guard.md), [PRD 54](54_actor_compliance.md), [PRD 56](56_probe_harness_v2.md), [PRD 60](60_orchestrator_scheduler.md), [PRD 61](61_doctrine_consolidation_shorthand_compiler.md), [PRD 44](44_session_config_and_transcript.md), [`collection_payload_format_v1_0_0.md`](../doctrine/collection_payload_format_v1_0_0.md).

---

This output complies with Lupopedia Constitutional Root Rules.
