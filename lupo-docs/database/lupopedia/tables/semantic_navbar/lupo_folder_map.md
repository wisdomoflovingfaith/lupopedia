---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/semantic_navbar/lupo_folder_map.md"
  system_version: "4.0.71"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1003
  artifact_type: "table_documentation"
  artifact_kind: "database_table"
  purpose: "Junction table linking lupo_folders to objects for the semantic navbar Folders feature."
  tags: ["semantic_navbar", "folders", "4.0.71"]
lupopedia.footer:
  version: "4.0.71"
  last_verified: "20260312"
---
# Table: lupo_folder_map

**Purpose:** Links folders to entities (content, artifact, etc.). Used with lupo_folders for the navbar Folders section.

**Columns:** folder_map_id (PK), folder_id, object_type, object_id, sort_order, created_ymdhis, is_deleted, deleted_ymdhis.

**Relationships:** folder_id → lupo_folders; (object_type, object_id) → any entity. No DB FKs.

**Navbar use:** Backend queries by object_type and object_id for current page, joins lupo_folders to return folder list.
