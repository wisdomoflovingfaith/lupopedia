# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/dbdoc_optimization_20260227_part1.md"
  system_version: "4.0.49"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "report"
  purpose: "DBDOC batch 1 optimization review for five tables"
  dialog_message: "Recommended next step: approve optimization items, then apply TOON -> install schema -> dev migration workflow."
  mood_rgb: "4B0082"
  artifact_kind: "database_review"
  traits: ["database", "optimization", "dbdoc"]
  tags: ["dbdoc", "optimization", "4.0.49", "batch1"]
  lupo_agent: "codex-ide"

flare.footer:
  outbound_edges:
    - { to: "docs/database/lupopedia/tables/lupo_document_embeddings.md", type: "references", weight: 0.8, reason: "reviewed table" }
    - { to: "docs/database/lupopedia/tables/lupo_collections.md", type: "references", weight: 0.8, reason: "reviewed table" }
    - { to: "docs/database/lupopedia/tables/lupo_search_index.md", type: "references", weight: 0.8, reason: "reviewed table" }
    - { to: "docs/database/lupopedia/tables/lupo_agents.md", type: "references", weight: 0.8, reason: "reviewed table" }
    - { to: "docs/database/lupopedia/tables/lupo_agent_tool_calls.md", type: "references", weight: 0.8, reason: "reviewed table" }
  semantic_tags: ["dbdoc", "optimization", "batch1"]
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# DBDOC Optimization Review - Batch 1 (20260227)

Scope: lupo_document_embeddings, lupo_collections, lupo_search_index, lupo_agents, lupo_agent_tool_calls.

## Summary
This report lists optimization and schema adjustments that would improve performance and consistency while respecting doctrine (no foreign keys, no triggers, no stored procedures). All changes must follow TOON update -> install_new_lupopedia.sql -> dev migration workflow.

## Proposed Changes (By Table)

### 1) lupo_document_embeddings
- Add composite index on (chunk_id, embedding_model) to speed multi-model lookup.
- Add optional embedding_hash column (varchar(64)) for deduplication and faster change detection.
- Consider a small int column for embedding_dim to validate payload dimensions.

### 2) lupo_collections
- Add composite index on (federation_node_id, is_deleted, sort_order) for common listing queries.
- Add index on published_ymdhis if used for feeds.
- Consider converting properties to properties_json (json) for stricter validation if cross-db JSON support is acceptable.

### 3) lupo_search_index
- Add composite index on (domain_id, entity_type, is_deleted) for common filters.
- Consider index on (entity_type, entity_id, is_deleted) to reduce lookups during updates.
- Keep text fields denormalized; avoid fulltext features unless you want DB-specific behavior.

### 4) lupo_agents
- Add index on provider if filtering by vendor is common.
- Add index on model_name for operational dashboards.
- Consider setting updated_ymdhis default to 0 for consistency (currently nullable).

### 5) lupo_agent_tool_calls
- Add composite index on (agent_id, status) for monitoring dashboards.
- Add index on created_ymdhis to speed time-range queries for analytics.
- Consider moving large input_json/output_json to chunk table if row size becomes a bottleneck.

## Notes
- All changes require updated TOON files and a new dev migration.
- Avoid FK constraints; enforce relationships in application code.
- For high-volume tables, prefer composite indexes over ad-hoc queries.

## Next Steps
1. Approve which changes to implement.
2. Update TOON files for selected tables.
3. Update install_new_lupopedia.sql.
4. Create a dev migration SQL file for live databases.
