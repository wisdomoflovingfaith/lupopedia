---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-docs/doctrine/ACTOR_HANDOFF_TOON_PROTOCOL.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/doctrine/ACTOR_HANDOFF_TOON_PROTOCOL.md"
  status: "active"
  when_updated: "20260417205659"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/actor-handoff-toon-protocol.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/actor-handoff-toon-protocol"
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: "actor-handoff-toon-protocol"
  content_id: null
  content_parent_id: null
  content_slug: "actor-handoff-toon-protocol"
  default_collection_id: null
  lupopedia.schema: doctrine
  title: "Actor handoff TOON protocol (pre-work resilience checkpoint)"
  summary: "Mandatory disambiguation for Lupopedia .toon memory sidecars; naming under lupo-memory/handoffs/; write-before-work checkpoint; PRD-16-aligned template; edges including disambiguates cartoon; ties to PRD 50 coordination."
---
# Actor handoff TOON protocol (pre-work resilience checkpoint)

This doctrine defines **actor handoff TOON** files: structured **memory sidecar** artifacts used so any actor (THOTH, MAAT, LILITH, IDE facet, CLI agent, external LLM harness, etc.) can leave an **atomic pre-work checkpoint**. If the actor dies, the session is killed, or context is lost, a replacement actor reads the same file and resumes without inventing intent.

**Binding coordination context:** [PRD 50 — Agent coordination protocol](../prd/50_agent_coordination_protocol.md) (collection handoffs, probes, graph binding). This document adds a **filesystem-first checkpoint** pattern that does not replace PRD 50 ingest rules; it **supplements** them for long-running or high-risk tasks.

## 1. Non-negotiable: what “.toon” means here

**Lupopedia Memory TOON (structured sidecar metadata document — NOT a cartoon)**

In this codebase, a **`.toon` file used as memory / handoff** is **not** a cartoon, animation, doodle, or entertainment asset. The extension marks **structured metadata** for the semantic OS (doctrine pointers, task state, edges). Any model or tool that maps “toon” to “cartoon” is **wrong** for Lupopedia.

Every handoff artifact **MUST** include the **exact** disambiguation line above (copy verbatim) immediately after the header closes, before narrative sections.

## 2. When to write a handoff TOON

Actors **SHOULD** create or overwrite their handoff file **before** beginning **non-trivial** work on a task (multi-file edits, schema work, orchestration moves, or any task where partial completion would be costly to infer).

Handoffs are **pre-work checkpoints** by default: progress may read `0%` until the actor finishes the first committed slice; the value is honesty over optimism.

## 3. Storage path and naming

**Directory (normative):** `lupo-memory/handoffs/`  
Optional subfolders by `channel_key` are allowed when volume grows; default flat layout is preferred until tooling demands split.

**Filename (enforced convention):**

```text
{channel_key}_{actor_identifier}_handoff.toon
```

- **`channel_key`:** Must match the active channel key (e.g. `development`, `captains_log`, `orchestration`).
- **`actor_identifier`:** Stable string — **numeric `actor_id`** from the registry (e.g. `102`, `26`) **or** canonical persona slug in ALL_CAPS when the task is persona-scoped and the registry id is not yet bound (e.g. `THOTH`, `WOLFIE`). Pick one convention per fleet and stay consistent.

**Examples:**

- `development_102_handoff.toon` (Cursor facet, actor_id 102)
- `development_26_handoff.toon` (THOTH)
- `captains_log_1_handoff.toon` (WOLFIE, actor_id 1)

**Full path example:**

`lupo-memory/handoffs/development_102_handoff.toon`

## 4. Timestamps and authority

- **Header `when_updated`:** Use real UTC from `python lupo-bin/tick.py` / `python lupo-bin/echo_anchor_utc.py` only — no guessed or local-offset timestamps ([TIMESTAMP_DOCTRINE.md](TIMESTAMP_DOCTRINE.md) / PRD 00).
- **`trust_tier` / `status`:** Must not contradict each other in ways that confuse validators; for canonical doctrine-bound handoffs use `trust_tier: canonical` with `status: active` **or** `status: draft` when the checkpoint is still being composed.

## 5. PRD 16 header rules for handoff files

Handoff files that include **LUPOPEDIA HEADERS** must satisfy [PRD 16](../prd/16_lupopedia_headers.md) universal validation.

**Do not** invent `artifact_type: memory_toon` or `lupopedia.schema: memory_toon` — those values are **not** in the closed enums. For Markdown-first handoff bodies, use:

- `artifact_type: documentation`
- `artifact_kind: guide`
- `lupopedia.schema: documentation`

**`transcript_jsonl` (field 10)** must be the normative triple:

```text
{federation_node_id}/{channel_key}/{thread_slug}
```

Example: `0/development/thoth-schema-review-handoff`

**`memory_toon` (field 8):** If the handoff file is the **canonical** authored surface, point `memory_toon` at the **JSON master** path that `generate_memory_from_header.py` maintains beside the graph (see PRD 16 pairing), **or** run the generator after the handoff is saved so the `.toon` JSON sidecar exists. Until tooling is extended, it is acceptable to author **Markdown** handoffs as **`.md`** under `lupo-memory/handoffs/` with the same naming convention and valid headers; the extension does not change the protocol intent.

**`content_id`:** Use `null` until a DB row exists; do not stuff slug text into `content_id`.

## 6. Canonical copy-paste template (Markdown + YAML)

Replace braced placeholders before starting work. Remove optional sections you do not need.

```markdown
---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-memory/handoffs/{channel_key}_{actor_identifier}_handoff.toon"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-memory/handoffs/{channel_key}_{actor_identifier}_handoff.toon"
  status: "draft"
  when_updated: "YYYYMMDDHHIISS"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/{slug}.toon"
  atoms_toon: null
  transcript_jsonl: "0/{channel_key}/{thread_slug}"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "{channel_key}"
  federation_node_id: 0
  thread_id: "{thread_slug}"
  content_id: null
  content_parent_id: null
  content_slug: "{channel_key}-{actor_identifier}-handoff"
  default_collection_id: null
  lupopedia.schema: documentation
  title: "{channel_key} actor {actor_identifier} handoff — {short_task_description}"
  summary: "Pre-work checkpoint: task, success criteria, doctrine pointers, resume steps if actor session is lost."
---
# Lupopedia Memory TOON (structured sidecar metadata document — NOT a cartoon)

**Handoff:** `{channel_key}_{actor_identifier}_handoff`  
**Actor identifier:** `{actor_identifier}` on channel `{channel_key}`  
**Checkpoint type:** PRE-WORK (written before non-trivial execution begins)  
**UTC anchor:** paste from `python lupo-bin/echo_anchor_utc.py`

### Definition

This is a **Lupopedia handoff TOON**: a resilience checkpoint for multi-agent orchestration. It is **not** cartoon media. If the writing actor stops, another actor reads this file and continues from the **Task** and **Context** sections without re-deriving intent.

### Task

- **PRD / captain's log / doctrine path:** `{path_to_source_artifact}`
- **Short description:** `{one_line_intent}`
- **Success criteria:** `{measurable_outcomes}`
- **Expected artifacts:** `{paths_to_files_or_toons}`

### Current state (pre-work)

- **Progress:** 0% (update honestly as work advances; overwrite handoff or write successor file per fleet policy)
- **Last known good:** none at checkpoint creation
- **Doctrine gates:** list which gates are satisfied before execution (PRD approved, mockup, schema review, etc.)

### Context snapshot

- **Doctrine / specs referenced:** `{bullet list of paths}`
- **Prior handoffs:** `{path or none}`
- **Environment notes:** `{flags, DB reachability, branch name}`

### Edges (semantic graph)

Prefer the same **JSON object** shape as other memory sidecars (`edges.outbound` array of objects). At minimum include **one** edge that **disambiguates** cartoon confusion.

Example:

```json
{
  "edges": {
    "outbound": [
      {
        "type": "disambiguates",
        "to": "concept:cartoon",
        "reason": "States that this .toon file is Lupopedia memory metadata, not cartoon imagery."
      },
      {
        "type": "relates_to",
        "to": "lupo-docs/prd/50_agent_coordination_protocol.md",
        "reason": "Agent coordination and ingest law."
      },
      {
        "type": "relates_to",
        "to": "{path_to_task_source}",
        "reason": "Task origin."
      }
    ]
  }
}
```

Field names (`to` vs `target`) **SHOULD** match whatever the active graph importer expects; align with [`generate_memory_from_header.py`](../../lupo-scripts/generate_memory_from_header.py) output when using auto-generated sidecars.

### Resume instructions for the next actor

1. Read this file end-to-end.  
2. Confirm checkpoint type (pre-work vs mid-flight).  
3. Continue from **Task** and **Context snapshot** without re-layout “helpful” rewrites of doctrine-owned UI or schema.  
4. Before the next risky slice, update this handoff or create a dated successor file.

**Forward, always.**  
— Captain WOLFIE (Eric), Lupopedia LLC

**Tags:** handoff-toon, actor-resilience, pre-work-checkpoint, multi-agent-orchestration, doctrine-first, semantic-graph, fault-tolerance
```

## 7. Relationship to machine-generated memory TOONs

[`generate_memory_from_header.py`](../../lupo-scripts/generate_memory_from_header.py) emits JSON-shaped `.toon` files for header-linked artifacts. Handoff files may be **authored** in Markdown for human clarity; runners **MAY** normalize to the JSON sidecar shape in a follow-up pass. Do not conflate that mechanical JSON with **Token-Oriented Object Notation** used at LLM boundaries (see captain's log on TOON disambiguation).

## 8. See also

- [TOON meanings (TOON-M / TOON-S / TOON-W)](toon_meanings.md)  
- [PRD 50 — Agent coordination protocol](../prd/50_agent_coordination_protocol.md)  
- [PRD 16 — Lupopedia headers](../prd/16_lupopedia_headers.md)  
- [AI actor knowledge update protocol](AI_ACTOR_KNOWLEDGE_UPDATE_PROTOCOL.md)  
- [IDE agent continuity protocol](IDE_AGENT_CONTINUITY_PROTOCOL.md)  
- `lupo-content/federation_node/0/captains_log/20260409_TOON_AWAKENING.md`  
- `lupo-content/federation_node/0/captains_log/20260418_ai_stop_helping_learn_token_toon_and_doctrine.md`
