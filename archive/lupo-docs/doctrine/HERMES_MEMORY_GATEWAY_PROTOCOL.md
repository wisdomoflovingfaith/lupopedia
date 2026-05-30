---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-docs/doctrine/HERMES_MEMORY_GATEWAY_PROTOCOL.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/doctrine/HERMES_MEMORY_GATEWAY_PROTOCOL.md"
  status: "active"
  when_updated: "20260416144907"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/hermes-protocol.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/hermes-protocol"
  artifact_type: prd
  artifact_kind: specification
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: "82"
  content_slug: "hermes-protocol"
  default_collection_id: null
  lupopedia.schema: doctrine
  title: "HERMES Memory Gateway Protocol"
  summary: "Technical specification for HERMES (actor_id 15) Memory Gateway: Transcript JSONL format, Staging Memory Toon pattern extraction, and year offset handling."
---
# HERMES Memory Gateway Protocol

## 1. The Transcript JSONL Format

The transcript is a chronological, append-only file of every message that passes through HERMES.

### 1.1 Path Resolution
- **Base Directory:** `lupo-memory/transcripts/`
- **Full Path:** `{base}/{federation_node_id}/{channel_key}/{thread_slug}.jsonl`
- **Example:** `lupo-memory/transcripts/0/development/hermes-memory-gateway.jsonl`

### 1.2 Record Structure (One Object Per Line)
```json
{"ts":20260416143316,"from_actor_id":1,"to_actor_id":116,"message_text":"[task] who: CLAUDE what: update PRD 50","message_type":"task","routing_provenance":"hermes:task-router"}
```

| Field | Requirement |
|---|---|
| `ts` | UTC 14-digit BIGINT `YYYYMMDDHHIISS` |
| `from_actor_id` | Original sender ID |
| `to_actor_id` | Target actor ID (0 for broadcast) |
| `message_text` | Verbatim text |
| `message_type` | `task`, `alert`, `decision`, `question`, `stdout`, `stderr`, `directed`, `system` |
| `routing_provenance` | `hermes:{rule_key}` (e.g. `hermes:task-router`) |

## 2. The Staging Memory Toon Protocol

Patterns are extracted from routed messages and stored in staging toons for evaluation by THOTH.

### 2.1 Path Resolution
- **Base Directory:** `lupo-memory/`
- **Staging Tier Path:** `{base}/{channel_key}/staging/{YYYY}/{MM}/{thread_slug}.toon`
- **Year Format:** Calendar year (e.g. `2026`). **Do not use 1026 offset in staging.**

### 2.2 Staging Toon Schema
```json
{
  "type": "staging_memory",
  "channel_key": "development",
  "thread_slug": "hermes-memory-gateway",
  "trust_tier": "staging",
  "when_updated": 20260416143316,
  "source_actor_id": 15,
  "patterns": [
    {
      "pattern_type": "task_assignment",
      "ts": 20260416143316,
      "from_actor_id": 1,
      "to_actor_id": 116,
      "summary": "Update PRD 50 section 5.3",
      "occurrence_count": 1,
      "promotion_candidate": false
    }
  ]
}
```

## 3. Pattern Extraction Rules

HERMES extracts the following patterns:

1.  **`task_assignment`**: Triggered by `[task]` prefix.
2.  **`alert`**: Triggered by `[alert]` prefix.
3.  **`decision`**: Triggered by `[decision]` prefix.
4.  **`question`**: Triggered by `[question]` prefix or `OQ-NNN` reference.
5.  **`cross_channel_route`**: Triggered when a message is routed to a non-primary channel.

### 3.1 Deduplication
Patterns are considered duplicates if they share:
- `pattern_type`
- `from_actor_id`
- `to_actor_id`
- `summary` (first 80 normalized chars)

### 3.2 Promotion Threshold
When `occurrence_count` reaches **3** (configurable via `HERMES_PROMOTION_THRESHOLD`), HERMES sets `promotion_candidate: true`.

## 4. Trust Ladder Implementation (Year Offsets)

| Tier | Folder Segment | Role |
|---|---|---|
| **Tier 3 (Staging)** | `2026/04/` | HERMES **writes** patterns here. |
| **Tier 2 (Canonical)** | `1026/04/` | THOTH **promotes** patterns here. |

HERMES NEVER writes directly to the 1026 offset path. This boundary is the primary security gate for system truth.

## 5. Implementation Rules (PHP)

- **Atomic Appends:** Use `FILE_APPEND | LOCK_EX` for JSONL to prevent race conditions.
- **Single Timestamp:** Use one `gmdate('YmdHis')` call for both JSONL and TOON writes within a single routing operation.
- **Directory Creation:** Ensure `mkdir(..., 0755, true)` is called before writing.
- **UTF-8 Only:** No emojis, smart quotes, or high-ASCII in memory artifacts.

## Cross-References
- **PRD 82** — Canonical HERMES specification.
- **HERMES_DOCTRINE.md** — Narrative routing and visibility doctrine.
- **PRD 43** — Trust Ladder and year offset definitions.
