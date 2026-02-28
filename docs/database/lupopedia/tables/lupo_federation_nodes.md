# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_federation_nodes.md"
  file_hash: "ca6bd0dfc6967d5630a497836ad6263a7eee64535e28e9877955c014df2239b2"
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
  file_path_from_root: "docs/database/lupopedia/tables/lupo_federation_nodes.md"
  system_version: "4.0.49"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "table_documentation"
  purpose: "Federation node registry and metadata"
  dialog_message: "DBDOC batch 2: enriched documentation and optimization notes."
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "database", "curated"]
  tags: ["database", "table", "lupo_federation_nodes"]
  lupo_agent: "codex-ide"
  lupo_federation_nodes.federation_node_id: "bigint NOT NULL"
  lupo_federation_nodes.node_base_url: "varchar(500) NOT NULL"
  lupo_federation_nodes.default_department_id: "bigint"
  lupo_federation_nodes.node_name: "varchar(255)"
  lupo_federation_nodes.node_description: "text"
  lupo_federation_nodes.node_contact: "varchar(255)"
  lupo_federation_nodes.meta_json: "json"
  lupo_federation_nodes.content_count: "bigint NOT NULL DEFAULT 0"
  lupo_federation_nodes.atom_count: "bigint NOT NULL DEFAULT 0"
  lupo_federation_nodes.hashtag_count: "bigint NOT NULL DEFAULT 0"
  lupo_federation_nodes.actor_count: "bigint NOT NULL DEFAULT 0"
  lupo_federation_nodes.last_sync_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_federation_nodes.trust_level: "tinyint NOT NULL DEFAULT 0"
  lupo_federation_nodes.status: "tinyint NOT NULL DEFAULT 1"
  lupo_federation_nodes.is_deleted: "tinyint NOT NULL DEFAULT 0"
  lupo_federation_nodes.deleted_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_federation_nodes.created_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_federation_nodes.updated_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_federation_nodes.active_theme_slug: "varchar(64) DEFAULT 'default'"
  table_primary_key: "federation_node_id"
  table_engine: "unknown"
  table_charset: "unknown"
  table_collation: "unknown"
  table_indexes: ["lupo_federation_nodes_idx_is_deleted", "lupo_federation_nodes_idx_node_base_url", "lupo_federation_nodes_idx_status", "lupo_federation_nodes_idx_trust_level"]
  table_foreign_keys: []

# FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml

flare.footer:
  outbound_edges:
    - { to: "docs/toons/lupo_federation_nodes.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_federation_nodes" }
    - { to: "docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.7, reason: "channels share federation scope" }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.7, reason: "actor registry per node" }
  inbound_edges: []
  semantic_tags: ["database", "table", "federation"]
  version: "4.0.49"
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# Table: lupo_federation_nodes

Purpose: Stores federation node metadata and sync state.
Type: database_table
Status: production_ready
Volume: low

## 1. Overview
- Key responsibilities: node registry, trust level, sync metadata.
- System role: enables federation and cross-node synchronization.
- Importance: gatekeeper for multi-node systems.

## 2. Schema Reference
Primary Key: federation_node_id
Field Categories: identity, metadata, counters, lifecycle.

### All Fields
| Column | Type | Notes |
|---|---|---|
| federation_node_id | bigint NOT NULL | Primary key. |
| node_base_url | varchar(500) NOT NULL | Base URL. |
| default_department_id | bigint | Default department. |
| node_name | varchar(255) | Display name. |
| node_description | text | Description. |
| node_contact | varchar(255) | Contact. |
| meta_json | json | Extra metadata. |
| content_count | bigint NOT NULL DEFAULT 0 | Content count. |
| atom_count | bigint NOT NULL DEFAULT 0 | Atom count. |
| hashtag_count | bigint NOT NULL DEFAULT 0 | Hashtag count. |
| actor_count | bigint NOT NULL DEFAULT 0 | Actor count. |
| last_sync_ymdhis | bigint NOT NULL DEFAULT 0 | Sync time. |
| trust_level | tinyint NOT NULL DEFAULT 0 | Trust level. |
| status | tinyint NOT NULL DEFAULT 1 | Status flag. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint NOT NULL DEFAULT 0 | Soft delete time. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | Created timestamp. |
| updated_ymdhis | bigint NOT NULL DEFAULT 0 | Updated timestamp. |
| active_theme_slug | varchar(64) DEFAULT 'default' | Theme slug. |

## 3. Relationships and Dependencies
- Primary relationships: default_department_id, actor_count references.
- Referencing tables: channels, collections, analytics.
- Integration points: federation sync jobs.

## 4. Indexes and Performance
Primary Indexes:
- federation_node_id
Performance Indexes:
- lupo_federation_nodes_idx_node_base_url
- lupo_federation_nodes_idx_trust_level
Index Strategy: optimize lookup by base URL and trust status.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_federation_nodes WHERE is_deleted = 0 ORDER BY trust_level DESC;
SELECT * FROM lupo_federation_nodes WHERE node_base_url = :url AND is_deleted = 0;
UPDATE lupo_federation_nodes SET last_sync_ymdhis = :ts WHERE federation_node_id = :id;
```
Best Practices: update counters in batch jobs.
Anti-Patterns: per-request updates on every content change.

## 6. Performance Considerations
- High-volume operations: low; mostly periodic sync.
- Optimization tips: add index on (status, trust_level) if used in dashboards.
- Scaling considerations: cache node metadata in memory for API calls.

## 7. Data Integrity
- Constraints: node_base_url required and unique (enforced at app level).
- Validation rules: normalize URLs before insert.
- Soft delete: prefer status flag + is_deleted.

## 8. Common Issues and Solutions
- Duplicate node URLs: normalize and enforce uniqueness.
- Stale counters: recompute in periodic job.
- Sync drift: update last_sync_ymdhis on sync completion.

## 9. Future Enhancements
- Add unique index on node_base_url.
- Add parent_node_id if federation hierarchy is introduced.
