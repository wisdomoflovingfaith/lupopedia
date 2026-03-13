---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/semantic_navbar/lupo_folders.md"
  system_version: "4.0.71"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1003
  artifact_type: "table_documentation"
  artifact_kind: "database_table"
  purpose: "Folder entities for folder-based grouping in the semantic navbar Folders feature."
  tags: ["semantic_navbar", "folders", "4.0.71"]
lupopedia.footer:
  version: "4.0.71"
  last_verified: "20260312"
---
# Table: lupo_folders

**Purpose:** Folders (name, slug, parent_folder_id, actor_id, channel_id). The navbar "Folders" section shows which folders the current page belongs to via lupo_folder_map.

**Columns:** folder_id (PK), name, slug, parent_folder_id, actor_id, channel_id, sort_order, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis.

**Relationships:** parent_folder_id → lupo_folders (self); lupo_folder_map links folder_id to objects. No DB FKs.

**Example query (navbar):** Folders containing a content page:
```sql
SELECT f.folder_id, f.name, f.slug
FROM lupo_folders f
JOIN lupo_folder_map m ON m.folder_id = f.folder_id AND m.is_deleted = 0
WHERE m.object_type = 'content' AND m.object_id = :content_id AND f.is_deleted = 0
ORDER BY f.sort_order, f.name;
```

**Navbar use:** API queries lupo_folder_map by object_type/object_id, joins lupo_folders, returns folder list for the Folders section.
