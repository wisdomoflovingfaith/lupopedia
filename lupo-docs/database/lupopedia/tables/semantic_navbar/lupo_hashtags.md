---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/semantic_navbar/lupo_hashtags.md
  last_modified_utc: '20260312'
  channel_id: 42
  actor_id: 1003
  artifact_type: table_documentation
  artifact_kind: database_table
  purpose: Normalized hashtag registry for the semantic navbar Hashtags feature.
  tags:
  - semantic_navbar
  - hashtags
  - 4.0.71
  when_updated: '20260324174654'
lupopedia.footer:
  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# Table: lupo_hashtags

**Purpose:** Normalized list of hashtags (tag_slug, label, use_count). The navbar Hashtags section can use this with lupo_hashtag_map, or fall back to lupo_contents.hashtags JSON.

**Columns:** hashtag_id (PK), tag_slug, label, use_count, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis.

**Relationships:** lupo_hashtag_map links hashtag_id to objects. No DB FKs.

**Example query (navbar):** Hashtags for a content page:
```sql
SELECT h.hashtag_id, h.tag_slug, h.label
FROM lupo_hashtags h
JOIN lupo_hashtag_map m ON m.hashtag_id = h.hashtag_id AND m.is_deleted = 0
WHERE m.object_type = 'content' AND m.object_id = :content_id AND h.is_deleted = 0;
```

**Navbar use:** API queries lupo_hashtag_map by object_type/object_id, joins lupo_hashtags, returns tag list for the Hashtags section.
