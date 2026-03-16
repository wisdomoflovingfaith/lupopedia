# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_paths_summary.md"
  last_modified_utc: "20260312"
  system_version: "4.0.71"
  actor_id: 1004
  lupo_agent: "antigravity"
  artifact_type: "table_documentation"
  purpose: "Summary metrics for visitor paths and usage frequency"
  namespace: "core"
  tags: ["database", "table", "analytics", "semantic_navbar"]
---

# Table: lupo_paths_summary

## 1. Overview
The `lupo_paths_summary` table stores aggregated metrics for visitor paths from the `lupo_paths` table. This allows for quick identification of "previous pages" or popular paths for navigation without scanning the full paths log.

## 2. Schema Reference

| Column | Type | Notes |
|---|---|---|
| summary_id | bigint NOT NULL | Primary Key, Auto-increment |
| path_id | bigint NOT NULL | Reference to `lupo_paths.path_id` |
| total_count | bigint NOT NULL | Total number of times this path was traversed |
| last_used_ymdhis | bigint NOT NULL | Last traversal timestamp (YYYYMMDDHHIISS) |
| created_ymdhis | bigint NOT NULL | Creation timestamp (YYYYMMDDHHIISS) |
| updated_ymdhis | bigint NOT NULL | Update timestamp (YYYYMMDDHHIISS) |

## 3. Indexes and Performance
- `PRIMARY KEY (summary_id)`
- `CREATE INDEX lupo_paths_summary_idx_path ON lupo_paths_summary (path_id)`

## 4. Usage Patterns
Used by the Semantic Navbar to list frequently visited or recent paths for the current user/context.

```sql
SELECT s.*, p.path_url 
FROM lupo_paths_summary s 
JOIN lupo_paths p ON s.path_id = p.path_id
ORDER BY s.last_used_ymdhis DESC LIMIT 10;
```
