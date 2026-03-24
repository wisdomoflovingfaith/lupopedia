---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/semantic_navbar/lupo_reference_links.md
  last_modified_utc: '20260312'
  channel_id: 42
  actor_id: 1003
  artifact_type: table_documentation
  artifact_kind: database_table
  purpose: Junction table linking lupo_references to objects (e.g. content) for the
    semantic navbar References feature.
  tags:
  - semantic_navbar
  - references
  - 4.0.71
  when_updated: '20260324174654'
lupopedia.footer:
  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# Table: lupo_reference_links

**Purpose:** Links references to entities (content, artifact, etc.). Used with lupo_references to drive the navbar References section.

**Columns:** reference_link_id (PK), reference_id, object_type, object_id, sort_order, created_ymdhis, is_deleted, deleted_ymdhis.

**Relationships:** reference_id → lupo_references; (object_type, object_id) → any entity. No DB FKs.

**Navbar use:** Backend queries by object_type = 'content' and object_id = current content_id, joins to lupo_references to return reference list.
