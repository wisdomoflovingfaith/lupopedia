---
lupopedia.headers:
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/ACTOR_FORENSIC_LOGGING_AND_COLLECTIONS_INTELLIGENCE_PLAN.md"
  version_when_written: "4.0.84"
  web_path: "http://www.lupopedia.com/lupo-docs/ACTOR_FORENSIC_LOGGING_AND_COLLECTIONS_INTELLIGENCE_PLAN"
  last_modified_utc: "20260315"
  channel_id: 42
  actor_id: 103
  actor_name: "antigravity"
  agent_name_identity: "Antigravity IDE Agent"
  artifact_type: "plan"
  artifact_kind: "documentation"
  purpose: "Research and implementation plan for actor forensic logging and semantic collections intelligence"
  traits: ["logging", "forensics", "collections-intelligence", "v4.0.75"]
  tags: ["logging", "doctrine", "architecture", "collections"]
---

# ACTOR_FORENSIC_LOGGING_AND_COLLECTIONS_INTELLIGENCE_PLAN.md

## 1. Executive Summary

Lupopedia requires a strictly deterministic, append-only logging system. This system will securely record actor identity, session contexts, file operations, federation nodes, referral chains, search expressions, and session lifecycle events. 

This architecture addresses two fundamental capabilities:
1. **Forensic Logging**: An immutable audit trail of all actor system activity for security and debugging.
2. **Collections Intelligence**: A semantic telemetry pipeline that analyzes usage patterns—such as file co-readings, search expressions, and referral chains—to train a system for autonomous collection grouping and intelligent tab optimization. 

Logs must be append-only, tamper-resistant, machine-parsable (Strict NDJSON), and feed directly into the `lupo_edges` and semantic indexing models.

---

## 2. Current State Research

An investigation of the existing codebase and schema reveals the following:

### 2.1 Inspect Current Logs
- **Location**: `lupo-logs/admin/` currently contains date-stamped `.jsonl` files (e.g., `2026-02-21.jsonl`, `2026-02-26.jsonl`). 
- **Write Mechanism**: The system relies on disparate write mechanisms rather than a unified service. 
- **Conclusion**: The current logs follow a generic daily-file approach for admin diagnostics without proper semantic categorization, strict NDJSON validation, or comprehensive actor trace chains.

### 2.2 Schema / TOON Mapping
Inspected TOON schema structures relating to logging and channels:
- **`lupo_unified_log`**: Contains `log_id`, `actor_id`, `channel_id`, `session_id`. However, it writes to the DB in ways that risk schema bloat if used for file-read telemetry.
- **`lupo_audit_log`**: Existing structure to track `entity_type` and `event_type`.
- **`lupo_sessions`**: **Critical Gap Identified.** Provides `session_id`, `actor_id`, `created_ymdhis`, but missing a strict linkage to `channel_id`.
- **`lupo_channels`**: Extensive configuration for channel contexts but limited session enforcement.
- **`lupo_edges`**: Built perfectly for Semantic Collections Intelligence via the `semantic_weight` field. 

**Gap Report**:
- `lupo_sessions` must be updated via a migration to enforce a `channel_id`.
- Forensic logs are best served entirely through the `lupo-logs` filesystem approach to prevent database transaction bloat handling simple `file_read` access, allowing the DB to be reserved for aggregated semantic weights (`lupo_edges`).

### 2.3 Code Path Inventory
The following execution paths frequently trigger discovery or file access and must be designated for logging instrumentation:
- Content renderers (e.g., `lupo-includes/modules/content/renderers/render-markdown.php`)
- Command line binary points (e.g., `lupo-bin/lupo.php`, `lupo-bin/query_edges.php`)
- IDE channel handlers (`lupo-includes/classes/ChannelService.php`, `lupo-includes/classes/AdminChannelsHandler.php`)
- Legacy session managers (`LegacySessionManager.php`, `class-SessionManager.php`)

*Note*: Paths requesting explicit new OOP wrappers (like `DocumentReader.php` or `SearchEngine.php`) will need to be properly instantiated if they do not yet exist physically in the application flow.

---

## 3. Requirements

The logging system must strictly record the following properties to provide end-to-end traceability:

- **Actor Identity**: `actor_id`, `actor_name`, `agent_name_identity`, `identity_claim`.
- **Session Context**: `session_id`, `request_id`, `session_start`, `session_end`.
- **Channel Context**: Actors can remain in exactly *one* channel at a time (`channel_id`, `previous_channel`).
- **Federation Context**: `federation_node_id`.
- **File Operations**: `file_path`, `referring_file_path`, `discovery_method`.
- **Search Operations**: `search_expression`, `search_engine`, `pattern_type`, `results_count`.
- **Execution Context**: `event_type`, `duration_ms`, `success_flag`, `error_message`.

---

## 4. Architecture

Logs must be append-only strictly formatted **NDJSON** (Newline Delimited JSON). 

### Example Record:
```json
{"timestamp":"20260315101230","event_type":"file_read","actor_id":103,"actor_name":"antigravity","channel_id":42,"federation_node_id":1,"file_path":"lupo-docs/doctrine/ACTORS.md","referrer":"lupo-docs/status/PLAN.md","discovery_method":"referral","success":true}
```

### Constraints Enforcement
- Absolutely no newlines inside JSON properties prior to `json_encode`. 
- Newline `\n` strictly dictates boundaries.
- Uses strict 14-digit integer times (`YYYYMMDDHHIISS`).

---

## 5. Schema Changes

### File System Hierarchy (`lupo-logs/`) 
The log structure must be migrated to support modular NDJSON archives:
`lupo-logs/`
  `actors/actor-{actor_id}/`
  `channels/channel-{channel_id}/`
  `sessions/`
  `file-reads/`
  `grep/`
  `admin/`
  `archive/`

### Database Changes
Very strictly, to avoid schema bloat, **no new SQL tables** should be generated to store high-frequency `file_read` and `search` events. Those stay on the filesystem. 
**However**, `lupo_sessions` MUST be updated (doctrine-aligned migration):
- Add column `channel_id BIGINT DEFAULT NULL` to `lupo_sessions`.

---

## 6. Event Types

A taxonomy of canonical events to standardize string constants:
| Event Type | Description |
|---|---|
| `file_read` | Direct file access retrieval |
| `file_read_referred` | File accessed via previous reference link |
| `search_executed` | Grep / Database search performed |
| `channel_join` | Actor joined a specified channel |
| `channel_leave` | Actor left a specified channel |
| `channel_violation` | Multichannel or rogue attempt detected |
| `session_start` | Session creation handshake |
| `session_end` | Session explicitly closed |
| `actor_register`| New actor orchestration event |
| `collection_suggested` | System proposes a new semantic collection map |

---

## 7. Collections Intelligence (Usage Analytics)

Analytics generated from parsing the NDJSON logs feed the Lupopedia semantic graph. The analysis pipeline functions via periodic jobs:

1. **Grep Pattern Analysis**: Detect clusters of co-retrieved files queried by the same `search_expression`. Patterns with frequent file combinations automatically become candidate collection tabs.
2. **Referral Chain Weighting**: Analyzing the `A -> B -> C` flow. A parsed log showing a jump from `PLAN.md` to `ACTORS.md` triggers an increment in stringency via a database UPDATE to `lupo_edges`.`semantic_weight`.
3. **Collection Auto-Suggestion**: Utilizing actor usage patterns and shared referrals, the engine automatically proposes grouped artifacts by dropping Markdown proposal matrices into `lupo-docs/collections/proposed/` for human review.
4. **Tab Popularity Metrics**: Measuring access metrics to rank defaults within the UI. 

---

## 8. Instrumentation Points

**Antigravity** advises integration hooks at the following endpoints:

- **File Reads**
  - `$reader->read()` -> `ForensicLogger::logFileRead(...)` (If wrapping DocumentReader in `lupo-includes/classes/`)
  - `lupo_parse_markdown()` inside `lupo-includes/modules/content/renderers/render-markdown.php`
  - Binary shell bindings via `lupo-bin/lupo.php` targeting commands like `cat`, `view`.

- **Reference Resolution**
  - Edge retrieval helpers in `lupo-bin/query_edges.php` and references API endpoints.

- **Grep / Search**
  - Search command handler in `lupo-bin/lupo.php` and standard search/search engine bindings. 

- **Channel / Session Operations**
  - `class-SessionManager.php` -> Inside `start()`, `end()`, `update()`.
  - `ChannelService.php` / `AdminChannelsHandler.php` -> Inside `join()`, `leave()`.

---

## 9. Channel Enforcement

**Rule:** An Actor may only exist inside one channel at a time.
1. The modified `lupo_sessions` checks `channel_id` upon operation.
2. If `channel_id` parameter requests differ from the session database, it triggers a `channel_violation`.
3. Action: Log violation locally, reject the operation upstream, flag Actor in diagnostics.

---

## 10. Implementation Phases

- **Phase 1: Research**: Identified logs & schema mappings. Proved that `lupo_sessions` needs updating while the rest remains structurally sound to accept filesystem log processing without DB bloat.
- **Phase 2: Schema & Format Definition**: Draft the `channel_id` session SQL migration and lock in NDJSON mapping.
- **Phase 3: Logging Service**: Implement `ForensicLogger.php` in `lupo-includes/classes/` ensuring `file_put_contents` with `FILE_APPEND | LOCK_EX` and stripped newline payload validation.
- **Phase 4: Instrumentation**: Deploy `ForensicLogger` event hooks inside markdown parsing, queries, APIs, and the session manager.
- **Phase 5: Collections Intelligence**: Orchestrate a background CLI script `lupo-scripts/process_semantic_logs.php` to incrementally calculate referral edge weights and propose collections.
- **Phase 6: Reporting & Validation**: Deliver `lupo-scripts/search-archived-logs.php` for archival parsing of zipped older records.

---

## 11. Risks

- **Log Growth**: Addressed via day-change or `100MB` limit rotation, enforcing gzip archiving at 7 days to `archive/`. 
- **Log Poisoning**: Addressed by strictly enforcing PHP-level input validation, dropping custom nested line breaks prior to JSON encode.
- **Performance Overhead**: Addressed by relying on low-overhead atomic filesystem locking (`LOCK_EX`) rather than flooding the active PHP-PDO pipeline for reads. 
- **Schema Bloat**: Avoided entirely by refusing to dump basic searches and read events into heavy MySQL tables (like a hypothetical `lupo_actor_file_reads`).

---

## 12. Doctrine Notes

- **Append-only guarantees**: Deletions are forbidden inside `lupo-logs`.
- **Zero failure silencers**: Logging calls must ensure data integrity; while they shouldn't randomly break user operations, filesystem rights must be absolute.
- **Lineage Integrity**: The parameter `referrer` is strictly immutable. 

## 13. Conclusion
The Actor Forensic Logging implementation provides dual capability: ensuring deterministic security while turning developer usage telemetry into intelligent auto-groupings. This approach achieves maximum semantic graph benefit while fully obeying the project's restrictive 5.3 + pure DB structural doctrines. 
