---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/HERMES_DOCTRINE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/HERMES_DOCTRINE.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/hermes-doctrine.toon
  atoms_toon: null
  transcript_jsonl: 0/development/hermes-doctrine
  artifact_type: doctrine
  artifact_kind: constitutional
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: HERMES Routing & Memory Gateway Doctrine (v4.1.2)
  summary: 'Canonical doctrine for HERMES (actor_id 15): message routing rules, pattern extraction, Memory Gateway role (transcript JSONL + staging toons), and Trust Ladder integration. v4.1.2: Unified routing + recording model.'
---
# HERMES Routing & Memory Gateway Doctrine

## 1. What HERMES Is

HERMES (**H**euristic **E**vent **R**outing & **M**essaging **E**xchange **S**ystem, actor_id 15) is the **intelligent message backbone** of Lupopedia. It serves two critical roles:

1.  **Message Router (Inward):** Intercepts chat messages and routes them to the correct destination (task queue for builders, alert log for observers, or chat stream for humans).
2.  **Memory Gateway (Outward):** Records every routed message as an atomic fact in the system's memory graph.

HERMES does not "participate" in chat. It is a protocol-layer component that ensures messages reach their targets and leave a permanent record in both the **Transcript** and **Staging Memory** layers.

## 2. The Task Queue is the Builder's Mouth

Builder agents (Cursor, Cascade, Claude Code, Windsurf, etc.) have exactly ONE input channel: their private task queue in `lupo_tasks`. They do not "read" the chat.

- **Human types:** `[task] who: CURSOR what: fix header in index.php`
- **HERMES intercepts:** parses the `[task]` command.
- **HERMES routes:** 
    - Inserts a row into `lupo_tasks` for actor 102 (Cursor).
    - Posts a routing confirmation message to the chat stream.
    - Appends the message to the thread's **Transcript JSONL**.
- **Agent receives:** Cursor polls `python bin/pending.py` and receives the task.

This separation ensures that builder agents only process structured directives and are not distracted or confused by ambient chat noise.

## 3. Message Routing Table (v4.1.2)

| Message Pattern | Target Type | Destination | Memory Action |
|---|---|---|---|
| `[task] who: X what: Y` | Builder Agent | `lupo_tasks` (X's queue) | JSONL + Staging Toon |
| `[alert] ...` | Monitoring/Observer | Chat stream + Observer log | JSONL + Staging Toon |
| `[decision] ...` | Multi-actor | Chat stream + Consensus log | JSONL + Staging Toon |
| `[question] ...` | Multi-actor | Chat stream + Open Questions | JSONL + Staging Toon |
| `stdout` / `stderr` | System/Human | Chat stream + Agent output log | JSONL |
| Directed message | Human | Chat stream only | JSONL |

## 4. The Memory Gateway Role

Every message routed by HERMES is recorded in two formats:

### 4.1 Transcript JSONL (The Absolute Record)
- **Path:** `memory/transcripts/{node}/{channel}/{thread_slug}.jsonl`
- **Format:** One JSON object per line. Verbatim capture of every message.
- **Purpose:** Audit logs, training data, and human-readable replay.

### 4.2 Staging Toons (The Pattern Layer)
- **Path:** `memory/{channel}/staging/{YYYY}/{MM}/{thread_slug}.toon`
- **Format:** TOON (JSON) pattern records.
- **Purpose:** Extracts structured facts (e.g. "Actor X was assigned task Y") for THOTH to review and promote to canonical memory.

## 5. Trust Ladder Integration (Tier 3 -> Tier 2)

HERMES operates at the **Staging Tier (Tier 3)** of the Trust Ladder (PRD 43).

1.  **Capture:** HERMES writes patterns to **Staging Memory Toons** using calendar years (e.g. `2026`).
2.  **Promotion:** THOTH (actor_id 26) monitors staging toons. When a pattern reaches the threshold (e.g. 3 occurrences), THOTH promotes it to the **Canonical Tier (Tier 2)**.
3.  **Canonical:** Canonical memory is stored in the 1026 offset path (e.g. `1026/04/`).

## 6. What HERMES Is Not

- **Not a chat bot:** HERMES does not have a personality and does not answer questions.
- **Not a builder:** HERMES does not write code or modify files.
- **Not a decision maker:** HERMES routes decisions; it does not make them.
- **Not a substitute for THOTH:** HERMES records the facts; THOTH weighs their truth and promotes them.

## Cross-References

- **PRD 82 (HERMES: Message Routing & Memory Gateway)** — Canonical implementation specification.
- **PRD 02 (Channels & Agent Orchestration)** — UI integration and routing modal.
- **PRD 43 (Trust Ladder)** — Definitions of Staging vs Canonical memory tiers.
- **PRD 10 (Task Queue)** — `lupo_tasks` schema and agent polling logic.
- **HERMES_MEMORY_GATEWAY_PROTOCOL.md** — Technical details of JSONL and Toon formats.
