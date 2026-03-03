# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_agent_tool_calls.md"
  file_hash: "cac85f35e2113cd617d750e19fbe62831dbeb12c7b4113207ddca4c4d5a65c83"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_agent_tool_calls.md"
  system_version: "4.0.49"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "table_documentation"
  purpose: "Audit log of agent tool invocations"
  dialog_message: "DBDOC batch 1: enriched documentation and optimization notes."
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "database", "curated"]
  tags: ["database", "table", "lupo_agent_tool_calls"]
  lupo_agent: "codex-ide"
  lupo_agent_tool_calls.agent_tool_call_id: "bigint NOT NULL"
  lupo_agent_tool_calls.agent_id: "bigint NOT NULL"
  lupo_agent_tool_calls.faucet_id: "bigint"
  lupo_agent_tool_calls.domain_id: "bigint NOT NULL"
  lupo_agent_tool_calls.tool_name: "varchar(150) NOT NULL"
  lupo_agent_tool_calls.action_type: "varchar(100)"
  lupo_agent_tool_calls.input_json: "text"
  lupo_agent_tool_calls.output_json: "text"
  lupo_agent_tool_calls.provider: "varchar(50)"
  lupo_agent_tool_calls.model_name: "varchar(150)"
  lupo_agent_tool_calls.tokens_prompt: "int DEFAULT 0"
  lupo_agent_tool_calls.tokens_completion: "int DEFAULT 0"
  lupo_agent_tool_calls.tokens_total: "int DEFAULT 0"
  lupo_agent_tool_calls.cost_usd: "decimal(10,6) DEFAULT 0.000000"
  lupo_agent_tool_calls.latency_ms: "int DEFAULT 0"
  lupo_agent_tool_calls.status: "varchar(50) DEFAULT 'success'"
  lupo_agent_tool_calls.error_message: "text"
  lupo_agent_tool_calls.parent_call_id: "bigint"
  lupo_agent_tool_calls.thread_id: "bigint"
  lupo_agent_tool_calls.message_id: "bigint"
  lupo_agent_tool_calls.created_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_agent_tool_calls.updated_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_agent_tool_calls.is_deleted: "tinyint NOT NULL DEFAULT 0"
  lupo_agent_tool_calls.deleted_ymdhis: "bigint DEFAULT 0"
  lupo_agent_tool_calls.archived_ymdhis: "bigint DEFAULT 0"
  lupo_agent_tool_calls.completed_ymdhis: "bigint"
  table_primary_key: "agent_tool_call_id"
  table_engine: "unknown"
  table_charset: "unknown"
  table_collation: "unknown"
  table_indexes: ["lupo_agent_tool_calls_idx_agent_created", "lupo_agent_tool_calls_idx_agent", "lupo_agent_tool_calls_idx_faucet", "lupo_agent_tool_calls_idx_domain", "lupo_agent_tool_calls_idx_model", "lupo_agent_tool_calls_idx_provider", "lupo_agent_tool_calls_idx_parent", "lupo_agent_tool_calls_idx_thread", "lupo_agent_tool_calls_idx_message"]
  table_foreign_keys: []

# FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml

flare.footer:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_agent_tool_calls.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_agent_tool_calls" }
    - { to: "docs/database/lupopedia/tables/lupo_agents.md", type: "references", weight: 0.8, reason: "agent reference" }
    - { to: "docs/database/lupopedia/tables/lupo_dialog_threads.md", type: "references", weight: 0.7, reason: "thread linkage" }
  inbound_edges: []
  semantic_tags: ["database", "table", "tool_calls"]
  version: "4.0.49"
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# Table: lupo_agent_tool_calls

Purpose: Records tool usage for agent operations, auditing, and billing.
Type: database_table
Status: production_ready
Volume: high

## 1. Overview
- Key responsibilities: audit trail for tool invocations and outputs.
- System role: supports observability, billing, and debugging.
- Importance: required for compliance and performance analytics.

## 2. Schema Reference
Primary Key: agent_tool_call_id
Field Categories: identity, inputs/outputs, metrics, lifecycle.

### All Fields
| Column | Type | Notes |
|---|---|---|
| agent_tool_call_id | bigint NOT NULL | Primary key. |
| agent_id | bigint NOT NULL | Agent reference. |
| faucet_id | bigint | Billing or faucet ref. |
| domain_id | bigint NOT NULL | Federation domain. |
| tool_name | varchar(150) NOT NULL | Tool name. |
| action_type | varchar(100) | Action type. |
| input_json | text | Input payload. |
| output_json | text | Output payload. |
| provider | varchar(50) | Provider. |
| model_name | varchar(150) | Model name. |
| tokens_prompt | int DEFAULT 0 | Prompt tokens. |
| tokens_completion | int DEFAULT 0 | Completion tokens. |
| tokens_total | int DEFAULT 0 | Total tokens. |
| cost_usd | decimal(10,6) DEFAULT 0.000000 | Cost. |
| latency_ms | int DEFAULT 0 | Latency. |
| status | varchar(50) DEFAULT 'success' | Status. |
| error_message | text | Error details. |
| parent_call_id | bigint | Call tree parent. |
| thread_id | bigint | Thread reference. |
| message_id | bigint | Message reference. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | Created timestamp. |
| updated_ymdhis | bigint NOT NULL DEFAULT 0 | Updated timestamp. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT 0 | Soft delete timestamp. |
| archived_ymdhis | bigint DEFAULT 0 | Archive timestamp. |
| completed_ymdhis | bigint | Completion time. |

## 3. Relationships and Dependencies
- Primary relationships: agent_id, thread_id, message_id.
- Referencing tables: metrics, reporting, and audit pages.
- Integration points: admin analytics and billing.

## 4. Indexes and Performance
Primary Indexes:
- agent_tool_call_id
Performance Indexes:
- lupo_agent_tool_calls_idx_agent_created
- lupo_agent_tool_calls_idx_thread
- lupo_agent_tool_calls_idx_message
Index Strategy: optimize time-range queries by agent and thread.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_agent_tool_calls WHERE agent_id = :agent_id ORDER BY created_ymdhis DESC LIMIT 50;
SELECT * FROM lupo_agent_tool_calls WHERE thread_id = :thread_id ORDER BY created_ymdhis DESC;
SELECT COUNT(*) AS total FROM lupo_agent_tool_calls WHERE is_deleted = 0;
UPDATE lupo_agent_tool_calls SET updated_ymdhis = :ts WHERE agent_tool_call_id = :id;
```
Best Practices: write once, append-only, and archive with archived_ymdhis.
Anti-Patterns: overwriting output_json in place without audit trail.

## 6. Performance Considerations
- High-volume operations: frequent inserts.
- Optimization tips: consider partitioning by created_ymdhis for large volumes.
- Scaling considerations: add composite index on (agent_id, status) for ops dashboards.

## 7. Data Integrity
- Constraints: tool_name required, agent_id required.
- Validation rules: ensure JSON payloads are valid.
- Soft delete: prefer archiving rather than deleting.

## 8. Common Issues and Solutions
- Large payloads: cap size or store blob references.
- Slow queries: add composite indexes for common dashboards.
- Incomplete records: set status and completed_ymdhis on finish.

## 9. Future Enhancements
- Add request_id for correlation across systems.
- Add compressed payload storage for large tool outputs.
