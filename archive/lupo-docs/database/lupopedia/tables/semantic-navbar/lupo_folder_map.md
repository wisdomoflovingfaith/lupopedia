---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/semantic_navbar/lupo_folder_map.md
  questions_toon: null
  channel_id: 42
  actor_id: 1003
  artifact_type: table_documentation
  artifact_kind: database_table
  purpose: Junction table linking lupo_folders to objects for the semantic navbar
    Folders feature.
  tags:
  - semantic_navbar
  - folders
  - 4.0.71
  when_updated: '20260324174654'
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# Table: lupo_folder_map

**Purpose:** Links folders to entities (content, artifact, etc.). Used with lupo_folders for the navbar Folders section.

**Columns:** folder_map_id (PK), folder_id, object_type, object_id, sort_order, created_ymdhis, is_deleted, deleted_ymdhis.

**Relationships:** folder_id â†’ lupo_folders; (object_type, object_id) â†’ any entity. No DB FKs.

**Navbar use:** Backend queries by object_type and object_id for current page, joins lupo_folders to return folder list.

