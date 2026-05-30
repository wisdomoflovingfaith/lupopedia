---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/validation_patterns.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/validation_patterns.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/validation-patterns.toon
  atoms_toon: null
  transcript_jsonl: 0/development/validation-patterns
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: Validation patterns index (automated checks and probes)
  summary: 'Validation index: PRD 61 twelve-invariant alignment; violation codes; guard+filter pipeline; contracts PRD 00/50; deterministic ordering+collection scope; faucet/channel; outbound PRD 52-61 edges.'
---
# Validation patterns

Lupopedia uses **several validation layers**. This index links them; each tool’s **own docstring / doctrine** remains authoritative for flags and exit codes.

## PRD 61 invariant alignment (normative)

This doctrine **MUST** stay consistent with the **twelve invariants** in **[PRD 61 section 2](../prd/61_doctrine_consolidation_shorthand_compiler.md)** — especially **violation codes** (this file’s index), **deterministic ordering** (collection exports, pipeline ordering), **contract surfaces**, **browser-tab prohibition**, **faucet** / **channel-thread** checks, and **doctrine graph edges** (see **Outbound graph edges** below).

## Scripted and static validators

- **Probe runtime guard (examinee output):** `python scripts/probe_runtime_guard.py` — extracts first fenced code block; otherwise prints **`ERROR: PROBE_BOUNDARY_VIOLATION`** and exit code **2**. See [`AI_ACTOR_PROBE_HARNESS_AND_GUARDS.md`](AI_ACTOR_PROBE_HARNESS_AND_GUARDS.md).
- **LUPOPEDIA HEADERS:** `python scripts/validate_lupopedia_headers_universal.py <path>` — see [PRD 16](../prd/16_lupopedia_headers.md), [`LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md`](LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md).
- **Pseudocode discipline (optional):** `python scripts/validate_pseudocode_discipline.py` — [PRD 17](../prd/17_decisions_format.md).
- **Directory tree / versioning prep:** `python scripts/generate_directory_tree.py` — authoritative list in `DIRECTORY_TREE.md`; required before certain audits per project rules (see required-tables doctrine in **`.cursor/rules`**).

## Violation codes (canonical index)

**Authoritative semantics:** [PRD 00 section 21.2](../prd/00_root_constitutional_system_requirements.md). This doctrine lists codes for **validator / transcript / harness** wiring only.

| Code | Typical detector surface |
|------|--------------------------|
| `ACTOR_SELF_EVAL_FORBIDDEN` | Self-grade or “I passed” narrative in probe output. |
| `ACTOR_PARROT_LOOP` | Echo of peer’s last line without examiner instruction. |
| `ACTOR_ROLE_COLLISION` | Multiple examiners or swapped roles mid-probe. |
| `ACTOR_CONTINUED_AFTER_TERMINATION` | Traffic after **`<TEST_COMPLETE>`** for that probe. |
| `KNOWLEDGE_ACK_INVALID` | First-line ack not exactly **`Node received.`** when required. |
| `ACTOR_OUT_OF_COLLECTION_SCOPE` | Answer cites nodes outside active collection / payload closure. |
| `ACTOR_SCHEMA_VIOLATION` | Bad **`channel_id` / `thread_id`**, faucet/header mismatch, missing required metadata. |
| `COLLECTION_PAYLOAD_INVALID` | JSON shape, version, or required keys fail lint. |
| `COLLECTION_NODE_ID_COLLISION` | Duplicate **`nodes[].node_id`** in one payload. |
| `PROBE_BOUNDARY_VIOLATION` | No fenced / harness artifact (`probe_runtime_guard.py` exit path). |
| `EXTERNAL_ACTOR_UNCONSTRAINED` | External agent outside containment envelope. |

## Constitutional self-tests (human or agent)

- **Packed UTC / timestamps:** [PRD 00 §19.2](../prd/00_root_constitutional_system_requirements.md) — checklist before suggesting clock or SQL time behavior.

## Programming-test validation pattern (competency probe)

**Use when:** onboarding a new facet, after doctrine or rules change, or when **another environment** already ran a test and this workspace has **no** record of it.

**Method:** assign a **concrete** generation task that **requires** the rule in the artifact; **inspect** output; **correct** with citations; **re-run**. This treats code generation as a **verification harness** and **cross-agent consistency** signal, not only productivity.

**Canonical write-up:** [`AI_ACTOR_COMPETENCY_TEST_PATTERN.md`](AI_ACTOR_COMPETENCY_TEST_PATTERN.md). **Constitutional:** [PRD 00 section 21](../prd/00_root_constitutional_system_requirements.md). **Orchestration context:** [`AGENT_ORCHESTRATION.md`](AGENT_ORCHESTRATION.md). **PRD surface:** [PRD 50 sections 1.2–1.4](../prd/50_agent_coordination_protocol.md).

**Normative — browser / IDE context:** Validators **MUST** ignore browser-tab metadata (**`edge_all_open_tabs`** and similar) as instruction input.

### Runtime guard and transcript filter (validator pipeline)

- **Validators MUST run after runtime guard extraction** on probe-classified examinee output (canonical script: [`scripts/probe_runtime_guard.py`](../../scripts/probe_runtime_guard.py); normative PRD: [PRD 53](../prd/53_runtime_guard.md)).
- **Transcript filter MUST classify probe messages** before routing when a transcript-backed path exists — minimum intent categories **`artifact`**, **`probe_control`**, **`violation`** ([PRD 58](../prd/58_transcript_filter.md); harness [PRD 56](../prd/56_probe_harness_v2.md)).
- **Validators MUST reject probe output that bypasses guard/filter** when policy marks the turn as probe-scoped (surface **`ACTOR_SCHEMA_VIOLATION`** or **`PROBE_BOUNDARY_VIOLATION`** per failure mode).

### Contract surfaces (validator-facing)

Cross-reference only; full tables: [PRD 00 section 21.3](../prd/00_root_constitutional_system_requirements.md), [PRD 50 section 1.5](../prd/50_agent_coordination_protocol.md).

- **Input contract (probe turns):** treat only the **harness-delimited artifact** as the graded deliverable — not ambient chat, not mixed instructions.
- **Output contract:** **no** commentary that substitutes for the artifact, **no** self-grade narrative outside mandated ack lines.
- **Termination contract:** **`<TEST_COMPLETE>`** is **examiner-only**; validators **MUST** flag post-token probe traffic as **`ACTOR_CONTINUED_AFTER_TERMINATION`**.

### Canonical state machines (reference)

Implementations **MUST NOT** contradict transition intent in:

- [PRD 00 section 21.4](../prd/00_root_constitutional_system_requirements.md) — **probe**, **knowledge update**, **collection ingestion**, **orchestrator scheduling**, **HERMES routing**.
- [PRD 50 sections 1.5–1.6](../prd/50_agent_coordination_protocol.md) — operational contracts and state diagrams aligned to the same graphs.

### Faucet metadata, channel scope, and collection ordering

- **Validators MUST flag missing or incorrect faucet metadata as `ACTOR_SCHEMA_VIOLATION`** when policy requires a faucet envelope on the path under test.
- **Validators MUST ensure artifacts reference valid `channel_id` and `thread_id`** (registry + membership) before accepting persistence-bound writes.

**Planned transcript / probe checks (backlog, non-normative):** automated detectors **SHOULD** cover all codes in **Violation codes (canonical index)** above, including collection-scope and schema rows.

## Knowledge graph update (after failed probe)

**Canonical:** [`AI_ACTOR_KNOWLEDGE_UPDATE_PROTOCOL.md`](AI_ACTOR_KNOWLEDGE_UPDATE_PROTOCOL.md) — persist doctrine fragments as **`lupo_memory_nodes`**, bind with **`lupo_memory_edges`** (always **node-to-node**), mandatory examinee ack **`Node received.`**, then re-run the probe. **PRD 50** section **1.3**. Backlog: validate **`content_hash`**, **`memory_key`** stability, orphan edges.

## Collection payload v1.0.0 (validator backlog)

**Canonical:** [`collection_payload_format_v1_0_0.md`](collection_payload_format_v1_0_0.md). **Operational law:** [PRD 50](../prd/50_agent_coordination_protocol.md) section **1.4** subsections **1.4.1–1.4.7** (orchestrator/actor steps, **`ingestion_mode`**, UI tabs vs payload). **Reference build (Markdown globs → JSON):** `python scripts/collection_compiler.py --repo-root . --config <path>.json --output <path>.json` — emits **`collection_payload_version`**, deduplicated **`nodes`**, wiki **`[[link]]`** edges filtered to in-payload **`to_node_id`** only. **Backlog:** JSON Schema, dangling **`tab` → `node_ids`**, full field lint beyond what the compiler enforces, runtime guard for collection-scoped answers / no self-grade phrases. **Constitutional / PRD:** [PRD 00](../prd/00_root_constitutional_system_requirements.md) section **22**, [PRD 38](../prd/38_memory_unification.md) section **18**.

**Normative — collection-scoped answers:** Validators **MUST** ensure answers remain **inside** the active collection (authorized payload closure) unless the orchestrator **explicitly** authorizes expansion (else **`ACTOR_OUT_OF_COLLECTION_SCOPE`** when policy classifies it).

**Normative — deterministic ordering ([PRD 61](../prd/61_doctrine_consolidation_shorthand_compiler.md) invariant 4):** Validators **MUST** enforce deterministic ordering of **`tabs[]`**, **`nodes[]`**, and per-node **`edges[]`** in collection payloads per [`collection_payload_format_v1_0_0.md`](collection_payload_format_v1_0_0.md) section **1.2** (sort keys, no dangling **`to_node_id`**, stable export order for audit hashes). **Guard → filter → compliance** pipeline stages **MUST** retain **fixed ordinal** semantics when mirrored in tooling ([PRD 53](../prd/53_runtime_guard.md) section **4**).

## Outbound graph edges (validation stack)

Importers (`lupo_metadata`, HERMES sidecars) **SHOULD** record documentation edges equivalent to:

| To | Role |
|----|------|
| [PRD 52 — Memory Graph Focus Manifest](../prd/52_memory_graph_focus_manifest.md) | Graph focus lens (**not** the runtime guard script) |
| [PRD 53 — Runtime Guard](../prd/53_runtime_guard.md) | Machine filter / guard path |
| [PRD 54 — Actor Compliance](../prd/54_actor_compliance.md) | Compliance evaluation |
| [PRD 56 — Probe Harness v2](../prd/56_probe_harness_v2.md) | Harness automation |
| [PRD 58 — Transcript Filter](../prd/58_transcript_filter.md) | Transcript classification before routing |
| [PRD 60 — Orchestrator Scheduler](../prd/60_orchestrator_scheduler.md) | Scheduler / routing tie-breakers |
| [PRD 61 — Doctrine Consolidation and Shorthand Compiler](../prd/61_doctrine_consolidation_shorthand_compiler.md) | Twelve cross-PRD invariants; shorthand TOON; consolidation pipeline |

#### Machine-readable `doctrine_rule` row (PRD 61)

| From | To | `edge_type` | `relationship` |
|------|-----|-------------|----------------|
| `VALIDATION_PATTERNS.md` | `docs/prd/61_doctrine_consolidation_shorthand_compiler.md` | `doctrine_rule` | `invariant_checklist_validation_index` |

---

This output complies with Lupopedia Constitutional Root Rules.
