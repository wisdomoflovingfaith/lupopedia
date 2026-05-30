---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-docs/doctrine/CONTEXT_AUTHORITY_MODEL.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/doctrine/CONTEXT_AUTHORITY_MODEL.md"
  status: "active"
  when_updated: "20260417110000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/context-authority-model.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/context-authority-model"
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "context-authority-model"
  default_collection_id: null
  lupopedia.schema: doctrine
  title: "Context Authority Model"
  summary: "Canonical rule: context in Lupopedia is defined by channel_key, thread_id, content_id, and artifact lineage -- NOT by the actor executing the work. Actors are interchangeable execution surfaces. This doctrine prevents agent misattribution of context to actor identity and governs handoff continuity across actor transitions."
---
# Context Authority Model

**Created:** 2026-04-17
**Status:** Active
**Owner:** WOLFIE (actor_id 1)
**Related PRDs:** PRD 02, PRD 38, PRD 16

---

## Context Authority Rule (Normative)

Context in Lupopedia is NOT derived from the actor executing work.

Actors are interchangeable execution surfaces and may change during a task.

Context MUST be derived from:

- `channel_key`
- `thread_id`
- `content_id` / artifact identity
- associated memory / TOON lineage

Actor attribution represents execution provenance only, not contextual ownership.

A task may move across multiple actors (e.g., Auggie -> Cursor -> Claude -> Gemini) without
changing its context.

Therefore:

- Agents MUST NOT infer context from actor name
- Agents MUST use channel and thread as the primary context boundary
- Handoff artifacts and memory MUST preserve context across actor transitions

---

## Supporting Statements

### 1. Actor Role

Actors are transient execution nodes.

They may:
- start a task
- continue a task
- resume a task from another actor

They do NOT define the task's identity or context.

`owner_actor_id` on `lupo_memory_nodes` and related tables records which actor wrote the
row (execution provenance). It does NOT define context scope. Two rows with different
`owner_actor_id` values may belong to the same logical context if they share `channel_key`,
`thread_id`, and artifact lineage.

---

### 2. Channel as Context Boundary

Channels define context isolation.

All work must be interpreted within its channel unless explicitly linked via edges or
allowed cross-channel rules (see `lupo-docs/doctrine/gaps/MEMORY_CHANNEL_ENFORCEMENT.md`
and `allowed_cross_channel_memory` in `lupo-channels/registry.json`).

The `channel_key` field in artifact headers and memory nodes is the authoritative channel
assignment. It is not derived from the actor who wrote the artifact.

---

### 3. Thread as Task Container

Threads represent the unit of work (question, task, investigation).

Context continuity is preserved through the thread, not the actor.

A task started by Auggie in thread T1 of channel "development" is still in thread T1 when
Cursor continues it. The `thread_id` does not change across actor transitions.

---

### 4. Handoff Continuity

When work transitions between actors, continuity MUST be preserved via:

- memory TOON files (carry `channel_key`, `thread_id`, artifact lineage)
- LUPOPEDIA headers (carry `channel_key`, `content_id`, `content_parent_id`)
- thread linkage (`thread_id` persists across actor changes)
- channel context (`channel_key` in destination artifact matches source)

Loss of actor does NOT imply loss of context.

An actor receiving a handoff MUST read the TOON artifact and headers to establish context.
An actor MUST NOT re-derive context from actor name, chat history, or recency bias.

---

## Disambiguation: UI "Active Target Actor Tab"

PRD 02 uses the phrase "active target actor tab" to mean the currently selected dispatch
target in the chat UI. This is a UI routing control, not a context definition.

The tab selection determines `to_actor_id` for the next message. It does NOT:
- define the semantic context of the work
- transfer ownership of artifacts to the selected actor
- change the `channel_key` or `thread_id` of any existing work

The UI "active target actor tab" is a routing surface. Context comes from the channel and
thread, not from which tab is selected.

---

## What `owner_actor_id` Means (Clarification)

`owner_actor_id` on `lupo_memory_nodes`, `lupo_memory_edges`, and `lupo_contents` records
the actor that created or last wrote the row. This is execution provenance.

It does NOT mean:
- the actor "owns" the conceptual context of that memory
- the context boundary changes when `owner_actor_id` changes
- memory written by actor A is inaccessible to actor B in the same channel/thread

Channel and thread scope govern access, not `owner_actor_id`.

---

## Anti-Patterns (Forbidden)

The following inferences are explicitly forbidden:

| Forbidden Inference | Why It Is Wrong |
|---------------------|-----------------|
| "This is Auggie's context because Auggie wrote it" | `owner_actor_id` is provenance, not scope |
| "Claude started this task so it belongs to the Claude thread" | Tasks belong to channel + thread, not actors |
| "I can't continue this task — a different actor started it" | Actors are interchangeable; context transfers via TOON + headers |
| "The active tab is Cursor so the context is Cursor's" | Tabs are routing controls, not context definitions |
| "This memory node belongs to actor 116 so I need actor 116's context" | Memory scope is channel-scoped, not actor-scoped |

---

## Related

- PRD 02 §"Context Authority Rule" — cross-reference in Orchestration Doctrine
- PRD 38 — memory architecture; `owner_actor_id` provenance note
- PRD 16 §5 — header fields including `channel_key`, `thread_id`, `content_id`
- `lupo-docs/doctrine/gaps/MEMORY_CHANNEL_ENFORCEMENT.md` — channel enforcement gaps
- `lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md` — coordination via channels
- `lupo-docs/doctrine/ide_agent_continuity_protocol.md` — handoff protocol between agents
