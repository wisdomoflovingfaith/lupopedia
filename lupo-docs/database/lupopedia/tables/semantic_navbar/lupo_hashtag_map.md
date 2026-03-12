---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/semantic_navbar/lupo_hashtag_map.md"
  system_version: "4.0.71"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1003
  artifact_type: "table_documentation"
  artifact_kind: "database_table"
  purpose: "Junction table linking lupo_hashtags to objects for the semantic navbar Hashtags feature."
  tags: ["semantic_navbar", "hashtags", "4.0.71"]
lupopedia.footer:
  version: "4.0.71"
  last_verified: "20260312"
---
# Table: lupo_hashtag_map

**Purpose:** Links hashtags to entities (content, artifact, etc.). Used with lupo_hashtags for the navbar Hashtags section.

**Columns:** hashtag_map_id (PK), hashtag_id, object_type, object_id, created_ymdhis, is_deleted, deleted_ymdhis.

**Relationships:** hashtag_id → lupo_hashtags; (object_type, object_id) → any entity. No DB FKs.

**Navbar use:** Backend queries by object_type and object_id for current page, joins lupo_hashtags to return tag list.
