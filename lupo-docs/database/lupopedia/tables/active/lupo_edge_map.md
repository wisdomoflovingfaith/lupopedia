# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_edge_map.md"
  last_modified_utc: "20260312"
  system_version: "4.0.71"
  actor_id: 1004
  lupo_agent: "antigravity"
  artifact_type: "table_documentation"
  purpose: "Mapping of semantic edges between objects/contents"
  tags: ["database", "table", "semantic_navbar"]
---

# Table: lupo_edge_map

## 1. Overview
The `lupo_edge_map` table defines relationships (edges) between different entities in the Lupopedia semantic graph. It allows for the expression of complex associations such as "cites", "refutes", "explains", or "is a prerequisite for".

## 2. Schema Reference

| Column | Type | Notes |
|---|---|---|
| edge_map_id | bigint NOT NULL | Primary Key, Auto-increment |
| edge_id | bigint NOT NULL | Reference to the logical edge entry |
| edge_type_id | bigint NOT NULL | Reference to `lupo_edge_types.edge_type_id` |
| source_type | varchar(64) NOT NULL | Type of the source object (e.g., 'content', 'actor') |
| source_id | bigint NOT NULL | ID of the source object |
| target_type | varchar(64) NOT NULL | Type of the target object |
| target_id | bigint NOT NULL | ID of the target object |
| created_ymdhis | bigint NOT NULL | BIGINT UTC timestamp (YYYYMMDDHHIISS) |
| is_deleted | tinyint NOT NULL | Soft delete flag |

## 3. Indexes and Performance
- `PRIMARY KEY (edge_map_id)`
- `CREATE INDEX lupo_edge_map_idx_edge ON lupo_edge_map (edge_id)`
- `CREATE INDEX lupo_edge_map_idx_type ON lupo_edge_map (edge_type_id)`
- `CREATE INDEX lupo_edge_map_idx_source ON lupo_edge_map (source_type, source_id)`
- `CREATE INDEX lupo_edge_map_idx_target ON lupo_edge_map (target_type, target_id)`

## 4. Usage Patterns
Used by the Semantic Navbar Backend API to retrieve related content/objects for the current page.

```sql
SELECT m.*, t.label as edge_label 
FROM lupo_edge_map m 
JOIN lupo_edge_types t ON m.edge_type_id = t.edge_type_id
WHERE m.source_type = 'content' AND m.source_id = :content_id AND m.is_deleted = 0;
```
