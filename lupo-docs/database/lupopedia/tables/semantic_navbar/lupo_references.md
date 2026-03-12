---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/semantic_navbar/lupo_references.md"
  system_version: "4.0.71"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1003
  artifact_type: "table_documentation"
  artifact_kind: "database_table"
  purpose: "Stores citation/source link records for the semantic navbar References feature."
  tags: ["semantic_navbar", "references", "4.0.71"]
lupopedia.footer:
  version: "4.0.71"
  last_verified: "20260312"
---
# Table: lupo_references

**Purpose:** Citation and source link records. The semantic navbar "References" section uses this table (with lupo_reference_links) to show links and citations attached to a page.

**Columns:** reference_id (PK), source_entity_type, source_entity_id, url, title, citation_text, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis.

**Relationships:** Application links references to objects via lupo_reference_links (object_type, object_id). No DB FKs.

**Example query (navbar):** References for a content page:
```sql
SELECT r.reference_id, r.url, r.title, r.citation_text
FROM lupo_references r
JOIN lupo_reference_links rl ON rl.reference_id = r.reference_id AND rl.is_deleted = 0
WHERE rl.object_type = 'content' AND rl.object_id = :content_id AND r.is_deleted = 0
ORDER BY rl.sort_order, r.created_ymdhis;
```

**Navbar use:** API resolves current page to content_id (or entity), then queries lupo_reference_links + lupo_references by object_type/object_id and returns list for the References section.
