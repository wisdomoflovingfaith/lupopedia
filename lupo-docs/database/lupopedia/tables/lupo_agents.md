# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_agents.md"
  file_hash: "19d09fa3d0420469ae6d982734229e1718efd4d26c6655eb4def123a6b0950a2"
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
  file_path_from_root: "docs/database/lupopedia/tables/lupo_agents.md"
  system_version: "4.0.49"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "table_documentation"
  purpose: "Agent configuration and operational metadata"
  dialog_message: "DBDOC batch 1: enriched documentation and optimization notes."
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "database", "curated"]
  tags: ["database", "table", "lupo_agents"]
  lupo_agent: "codex-ide"
  lupo_agents.agent_id: "bigint NOT NULL"
  lupo_agents.agent_key: "varchar(100) NOT NULL"
  lupo_agents.agent_name: "varchar(150) NOT NULL"
  lupo_agents.archetype: "varchar(150)"
  lupo_agents.description: "text"
  lupo_agents.version: "varchar(50) DEFAULT '1.0'"
  lupo_agents.model_name: "varchar(100)"
  lupo_agents.is_global_authority: "tinyint NOT NULL DEFAULT 0"
  lupo_agents.is_internal_only: "tinyint NOT NULL DEFAULT 0"
  lupo_agents.created_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_agents.updated_ymdhis: "bigint"
  lupo_agents.is_deleted: "tinyint NOT NULL DEFAULT 0"
  lupo_agents.deleted_ymdhis: "bigint"
  lupo_agents.avg_response_time_ms: "int DEFAULT 0"
  lupo_agents.total_tokens_processed: "bigint DEFAULT 0"
  lupo_agents.success_rate: "float DEFAULT 1"
  lupo_agents.cost_per_1k_tokens: "decimal(10,4) DEFAULT 0.0000"
  lupo_agents.temperature: "float DEFAULT 0.7"
  lupo_agents.top_p: "float DEFAULT 1"
  lupo_agents.max_tokens: "int DEFAULT 2048"
  lupo_agents.presence_penalty: "float DEFAULT 0"
  lupo_agents.frequency_penalty: "float DEFAULT 0"
  lupo_agents.system_prompt: "text"
  lupo_agents.provider: "varchar(50) DEFAULT 'openai'"
  lupo_agents.api_key_id: "bigint"
  lupo_agents.timeout_ms: "int DEFAULT 20000"
  lupo_agents.safety_json: "json"
  lupo_agents.response_format: "varchar(50)"
  lupo_agents.pono_score: "decimal(3,2) DEFAULT 1.00"
  lupo_agents.pilau_score: "decimal(3,2) DEFAULT 0.00"
  lupo_agents.kapakai_score: "decimal(3,2) DEFAULT 0.50"
  lupo_agents.kapu_active: "tinyint DEFAULT 0"
  lupo_agents.kapu_until: "bigint"
  lupo_agents.kapu_reason: "varchar(500)"
  lupo_agents.kapu_consent_given: "tinyint DEFAULT 0"
  lupo_agents.kapu_appeal_pending: "tinyint DEFAULT 0"
  table_primary_key: "agent_id"
  table_engine: "unknown"
  table_charset: "unknown"
  table_collation: "unknown"
  table_indexes: ["lupo_agents_idx_created_ymdhis", "lupo_agents_idx_is_deleted", "lupo_agents_idx_is_global_authority", "lupo_agents_idx_updated_ymdhis", "lupo_agents_unique_agent_key", "lupo_agents_idx_api_key_id"]
  table_foreign_keys: []

# FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml

flare.footer:
  outbound_edges:
    - { to: "docs/toons/lupo_agents.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_agents" }
    - { to: "docs/database/lupopedia/tables/lupo_agent_heartbeats.md", type: "references", weight: 0.8, reason: "agent lifecycle tracking" }
    - { to: "docs/database/lupopedia/tables/lupo_agent_tool_calls.md", type: "references", weight: 0.8, reason: "agent tool call logs" }
    - { to: "docs/database/lupopedia/tables/lupo_api_tokens.md", type: "references", weight: 0.7, reason: "API token linkage" }
  inbound_edges: []
  semantic_tags: ["database", "table", "agents"]
  version: "4.0.49"
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# Table: lupo_agents

Purpose: Stores AI agent configuration, model parameters, and policy metadata.
Type: database_table
Status: production_ready
Volume: low

## 1. Overview
- Key responsibilities: define agents, model parameters, and safety metadata.
- System role: central registry for AI agents and runtime controls.
- Importance: governs access, capability, and policy enforcement.

## 2. Schema Reference
Primary Key: agent_id
Field Categories: identity, model parameters, operational metrics, policy flags.

### All Fields
| Column | Type | Notes |
|---|---|---|
| agent_id | bigint NOT NULL | Primary key. |
| agent_key | varchar(100) NOT NULL | Unique agent slug. |
| agent_name | varchar(150) NOT NULL | Display name. |
| archetype | varchar(150) | Classification. |
| description | text | Summary. |
| version | varchar(50) DEFAULT '1.0' | Agent version. |
| model_name | varchar(100) | Model identifier. |
| is_global_authority | tinyint NOT NULL DEFAULT 0 | Global authority flag. |
| is_internal_only | tinyint NOT NULL DEFAULT 0 | Internal visibility flag. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | Created timestamp. |
| updated_ymdhis | bigint | Updated timestamp. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Soft delete timestamp. |
| avg_response_time_ms | int DEFAULT 0 | Performance metric. |
| total_tokens_processed | bigint DEFAULT 0 | Token count. |
| success_rate | float DEFAULT 1 | Success ratio. |
| cost_per_1k_tokens | decimal(10,4) DEFAULT 0.0000 | Cost metric. |
| temperature | float DEFAULT 0.7 | Sampling temperature. |
| top_p | float DEFAULT 1 | Sampling top_p. |
| max_tokens | int DEFAULT 2048 | Token limit. |
| presence_penalty | float DEFAULT 0 | Presence penalty. |
| frequency_penalty | float DEFAULT 0 | Frequency penalty. |
| system_prompt | text | Prompt. |
| provider | varchar(50) DEFAULT 'openai' | Provider name. |
| api_key_id | bigint | API key reference id. |
| timeout_ms | int DEFAULT 20000 | Timeout. |
| safety_json | json | Safety policy JSON. |
| response_format | varchar(50) | Response format. |
| pono_score | decimal(3,2) DEFAULT 1.00 | Policy score. |
| pilau_score | decimal(3,2) DEFAULT 0.00 | Policy score. |
| kapakai_score | decimal(3,2) DEFAULT 0.50 | Policy score. |
| kapu_active | tinyint DEFAULT 0 | Policy flag. |
| kapu_until | bigint | Policy time limit. |
| kapu_reason | varchar(500) | Policy reason. |
| kapu_consent_given | tinyint DEFAULT 0 | Consent flag. |
| kapu_appeal_pending | tinyint DEFAULT 0 | Appeal flag. |

## 3. Relationships and Dependencies
- Primary relationships: API keys and agent activity tables.
- Referencing tables: tool calls, heartbeats, system prompts.
- Integration points: admin UI and automation policies.

## 4. Indexes and Performance
Primary Indexes:
- agent_id
Performance Indexes:
- lupo_agents_unique_agent_key
- lupo_agents_idx_api_key_id
- lupo_agents_idx_is_global_authority
Index Strategy: lookup by agent_key and filter by policy flags.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_agents WHERE is_deleted = 0 ORDER BY agent_id ASC;
SELECT * FROM lupo_agents WHERE agent_key = :agent_key AND is_deleted = 0;
UPDATE lupo_agents SET updated_ymdhis = :ts WHERE agent_id = :id;
```
Best Practices: keep agent_key stable and update updated_ymdhis on changes.
Anti-Patterns: writing large prompts without caching or trimming.

## 6. Performance Considerations
- High-volume operations: low. Mostly configuration reads.
- Optimization tips: cache agent_key lookups at application layer.
- Scaling considerations: use pagination for admin views.

## 7. Data Integrity
- Constraints: unique agent_key.
- Validation rules: enforce provider and model_name values.
- Soft delete: maintain lifecycle integrity.

## 8. Common Issues and Solutions
- Duplicate agent_key: rely on unique index.
- Stale metrics: update via background jobs.
- Policy drift: track kapu fields and audit changes.

## 9. Future Enhancements
- Add agent_capabilities mapping table.
- Add updated_ymdhis default value if consistent across DB.
