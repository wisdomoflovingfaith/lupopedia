---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd_proposals/XX_A-i_DEPT0_LEARNING_PIPELINE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd_proposals/XX_A-i_DEPT0_LEARNING_PIPELINE.md
  status: draft
  when_updated: '20260801010640'
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: proposal
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: dept0-learning-pipeline
  lupopedia.schema: proposal
  prd_cluster: 41_A-i_39_A-i_54_A-i_86_A-i
  title: 'PRD proposal XX: Department 0 Learning Pipeline -- awaiting PRD 84 NN allocation'
  summary: 'Defines the stages, constraints, audit requirements, and PONO gate for core actor learning from Department 0. Moved from docs/prd/ -- illegal group token XX. Do not renumber without PRD 84 allocation.'
---
# PRD proposal XX: Department 0 Learning Pipeline -- Constitutional Architecture

**Supersession:** former path `docs/prd/XX_A-i_DEPT0_LEARNING_PIPELINE.md` --superseded_by--> `docs/prd_proposals/XX_A-i_DEPT0_LEARNING_PIPELINE.md`. Identifier XX is not an allocated PRD group.

**Identifier:** XX (letters; do not renumber to a numeric PRD without **PRD 84** allocation)  
**Normative date:** TBD  
**Status:** Draft (proposals folder)  
**Authority:** PRD-first; does not override PRD 00, PRD 41, PRD 39, PRD 54, PRD 86  
**Implementation note:** This PRD specifies **what** must happen, not **how**. Implementation details belong in separate technical specs or tooling PRDs.

---

## 1. Purpose

Define the constitutional pipeline through which **core actors** (Wolfie, Lilith, KAIROS, Thoth, and any future registry-backed system personas) learn from **Department 0** human interactions.

Ensure that learning is:

- **Auditable** -- every learning event is traceable to a source and timestamp.
- **Non-destructive** -- original canonical text is preserved.
- **PONO-gated** -- each learning event passes a correctness/balance check before being committed to canonical memory.
- **Sovereign** -- only Dept 0 humans can teach core actors (per PRD 41).

This PRD does **not** specify:

- Specific database schemas, file formats, or API endpoints.
- Scheduling, batching, or real-time behavior.
- Any particular LLM, embedding model, or vector store.

Those details belong in separate implementation PRDs or technical design documents.

---

## 2. Scope

**In scope:**

- Core actors defined in PRD 41 (and future PRD-registered core personas).
- Learning sources: only interactions where the auth user belongs to Department 0.
- Artifact types that may be ingested: transcripts (JSONL), PRDs, WHY files, Captain's Logs (with strip rules per PRD 39).
- Memory artifacts: TOON staging area, canonical memory store (abstract).
- Audit trail: immutable log of every learning event.

**Out of scope:**

- Non-core actor learning (governed by department isolation rules in PRD 41).
- Cross-department learning (forbidden unless a dedicated PRD authorizes it).
- Real-time inference or agent prompting (that's runtime, not learning).

---

## 3. Core Definitions

| Term | Definition |
|------|------------|
| **Learning event** | A single atomic update to a core actor's canonical memory, derived from a specific Dept 0 source. |
| **Source transcript** | A JSONL file (per `transcript_jsonl` header field) containing raw interaction logs from a Dept 0 channel/thread. |
| **Stripped content** | The canonical text recovered after removing all WOLF markers (per PRD 39 strip rules). |
| **Semantic edge** | A relationship extracted from stripped content, linking concepts, actors, or artifacts. |
| **TOON staging** | A temporary holding area where semantic edges are aggregated before compaction. |
| **Compaction** | The process of promoting staged edges to canonical memory, resolving conflicts, and applying time-based decay rules. |
| **PONO gate** | A validation step that checks whether a learning event satisfies correctness, balance, and constitutional alignment. |
| **Audit trail** | An immutable log recording each learning event's source, timestamp, actor, and PONO gate result. |

---

## 4. Pipeline Stages (Constitutional Only)

The pipeline consists of **five mandatory stages**. No stage may be skipped.

### Stage 1: Ingestion

- Input: A Dept 0 channel/thread marked with `trust_tier: canonical` or `status: active`.
- Action: Identify new source transcripts (JSONL) since the last learning cycle.
- Constraint: Source must have `federation_node_id: 0` and `channel_key` belonging to Dept 0.
- Output: A list of candidate source artifacts.

### Stage 2: Stripping and Extraction

- Input: Candidate source artifacts.
- Action: Apply PRD 39 strip rules to recover canonical text.
- Action: Extract semantic edges (concept nodes, references, relationships).
- Constraint: No WOLF markers survive into semantic edges.
- Output: A set of raw semantic edges in TOON staging.

### Stage 3: Compaction

- Input: Raw semantic edges in TOON staging.
- Action: Merge edges, resolve conflicts (latest timestamp wins unless overridden by human).
- Action: Apply decay/forgetting rules (if defined in future PRD).
- Output: Compacted, canonical memory updates ready for commit.

### Stage 4: PONO Gate

- Input: Compacted memory updates.
- Action: Validate against:
  - **PONO** (correctness/balance): Does the update align with PRD 00, PRD 41, and the 12 Commandments?
  - **KAPU** (forbidden patterns): Does the update introduce any prohibited cross-department contamination or vibe-driven content?
  - **KULEANA** (responsibility): Is the update clearly attributable to a specific Dept 0 human and source artifact?
- Constraint: If gate fails, the learning event is **rejected** and recorded in the audit trail with reason.
- Output: Pass/fail status for each memory update.

### Stage 5: Audit and Commit

- Input: Passed memory updates.
- Action: Write each update to canonical memory store (abstract location).
- Action: Append a record to the audit trail (see Section 6).
- Output: Confirmed learning event.

---

## 5. Constraints

1. **No implementation details** in this PRD. Any proposal that adds database schemas, API endpoints, or code belongs in a separate implementation PRD.
2. **Learning is asynchronous** -- no real-time requirement. Processing may be batched.
3. **Human override** -- Any learning event may be manually rejected by a Dept 0 human after the fact, with a logged reason.
4. **Zero automatic execution** -- This pipeline does not trigger any side effects outside memory updates. (Per PRD 39 Commandment 5.)
5. **Testing required** -- Any implementation must include a test suite that validates the PONO gate against a known corpus of Dept 0 interactions.

---

## 6. Audit Trail Requirements

Every learning event **MUST** produce an immutable record containing:

| Field | Description |
|-------|-------------|
| `learning_event_id` | Deterministic BIGINT or content hash assigned by the application layer (no random UUID) |
| `timestamp` | UTC BIGINT (per `tick.py`) |
| `source_artifact` | Path to transcript JSONL or canonical document |
| `source_channel` | `channel_key` |
| `source_thread` | `thread_key` (if applicable) |
| `actor_updated` | Core actor name (Wolfie, Lilith, etc.) |
| `pono_gate_result` | PASS / FAIL / HUMAN_REJECTED |
| `failure_reason` | If failed, mandatory explanation |
| `canonical_memory_ref` | Pointer to where the update was stored (abstract) |

Audit trail MUST be append-only, readable by Dept 0 humans, and searchable by `timestamp` and `source_artifact`.

---

## 7. Relationship to Other PRDs

| PRD | Relationship |
|-----|--------------|
| PRD 00 | Constitutional root -- no violation permitted. |
| PRD 41 | Defines Dept 0 sovereignty and core actor learning boundaries. |
| PRD 39 | Defines WOLF strip rules for source artifacts. |
| PRD 54 | Immune system probes MUST validate that learning events follow this pipeline. |
| PRD 86 | Header and immune enforcement; validators align with pipeline audit requirements. |
| PRD 98_B | Captain's Log has zero doctrinal authority; learning from Logs must strip WOLF and respect this pipeline. |

---

## 8. Non-Implementation Statement

**This PRD does not specify:**

- Programming languages, frameworks, or libraries.
- File formats beyond those already defined in other PRDs (JSONL, Markdown, TOON).
- Scheduling intervals, batch sizes, or performance targets.
- Specific LLM or embedding models.
- User interfaces or dashboards (though they may be built from the audit trail).

All such details are **out of scope** and belong in separate implementation-focused PRDs or technical design documents.

---

## 9. Version History

| Version | Date (UTC) | Change |
|---------|------------|--------|
| v0.2 | 20260607 | Replaced with Captain skeleton (5-stage pipeline, audit trail, PONO gate); merged from prior v0.1 synthesis |
| v0.1 | 20260607 | Initial constitutional architecture (Cursor synthesis; superseded by v0.2 skeleton) |

---

## 10. Open Questions (Non-Normative)

- Should compaction include a "forgetting curve" (time-based decay)? Defer to future PRD.
- Should the PONO gate be automated, human-reviewed, or both? Defer to implementation PRD.
- What is the canonical memory store? Abstract for now -- concrete choice belongs in implementation.
- Should **Dept 0 Echo Mode** (learn only from frozen canonical PRDs/WHYs during low human availability) be normative here or in a separate PRD? Defer to Captain.

---

## Appendix A -- Archived v0.1 Synthesis (Non-Normative)

Preserved from pre-skeleton Cursor draft. Not authoritative; retained for history only.

- Nine-stage pipeline variant (Dept 0 input through PONO check)
- **Dept 0 Echo Mode** section (fallback from frozen PRDs/WHYs)
- Tooling surfaces table (dashboard, audit log, compaction visualizer, PONO probe hook)
- Extended PONO/KAPU/KULEANA gate summary table

Route superseded material here; do not delete.
