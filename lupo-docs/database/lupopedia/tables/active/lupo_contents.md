---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_contents.md"
  system_version: "4.0.73"
  namespace: "content"
  channel_id: 1
  actor_id: 1003
  last_modified_utc: "20260313"
  artifact_type: "documentation"
  artifact_kind: "database_table"
  purpose: "JetBrains domain table documentation for lupo_contents"
  lupo_agent: "antigravity"

lupopedia.edges:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Edges reflect discovered relationships between database tables and PHP/Python codebase entities. Values should be verified against live database schemas/queries for the most current semantic graph state."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_contents.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_contents" }

lupopedia.engagement:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Engagement metrics track edit frequency and importance of each file in the version transition process."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  views: 0

lupopedia.footer:
  version: "4.0.73"
  last_verified: "20260313"
  last_verified_by: "antigravity"
---

# Table: lupo_contents

## Table Overview
- purpose: Primary content records for knowledge and documentation entities.
- category: active
- status: active (present in current TOON and install schema)
- version introduced: not explicitly documented in TOON/install comments
- version deprecated: not applicable
- removal notes: not applicable
- migration references: none found in migration docs scanned

## Column Documentation
| Column | Type | Nullability | Default | Description |
|---|---|---|---|---|
| content_id | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| content_parent_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| federation_node_id | bigint | Nullable/unspecified | 1 | TOON-defined field; canonical semantic description not specified in TOON. |
| federation_source_url | varchar(2000) COMMENT ''Canonical URL of content at source | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| channel_id | bigint COMMENT ''Channel this content belongs to (doctrine: content | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| department_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| actor_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| title | varchar(255) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| slug | varchar(255) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| custom_path | varchar(255) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| description | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| seo_keywords | varchar(500) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| body | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| content | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| content_type | varchar(50) | Nullable/unspecified | ''article | TOON-defined field; canonical semantic description not specified in TOON. |
| format | varchar(20) | Nullable/unspecified | ''markdown | TOON-defined field; canonical semantic description not specified in TOON. |
| content_url | varchar(2000) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| default_collection_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| source_url | varchar(2000) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| source_title | varchar(500) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| is_template | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| status | varchar(64) | Nullable/unspecified | ''draft | TOON-defined field; canonical semantic description not specified in TOON. |
| visibility | varchar(64) | Nullable/unspecified | ''public | TOON-defined field; canonical semantic description not specified in TOON. |
| view_count | int | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| created_ymdhis | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| utc_cycle | varchar(64) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| triage_status | varchar(64) | NOT NULL | ''untriaged | TOON-defined field; canonical semantic description not specified in TOON. |
| triage_notes | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| updated_ymdhis | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| is_deleted | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| is_active | tinyint | NOT NULL | 1 | TOON-defined field; canonical semantic description not specified in TOON. |
| deleted_ymdhis | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| content_sections | json | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| version_number | int | NOT NULL | 1 | TOON-defined field; canonical semantic description not specified in TOON. |
| file_path_from_root | varchar(500) COMMENT ''FLIP Header: path from repo root (4.0.13) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| file_last_modified_system_version | varchar(20) COMMENT ''FLIP: system version | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| file_last_modified_utc | bigint COMMENT ''FLIP: UTC last modified YYYYMMDDHHIISS | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| tags | json | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| dialog_notes | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| atom_mappings | json COMMENT ''Consolidated from lupo_content_atom_map | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| category_mappings | json COMMENT ''Consolidated from lupo_content_category_map | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| content_events | json COMMENT ''Consolidated from lupo_content_events | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| hashtags | json COMMENT ''Consolidated from lupo_content_hashtag | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| inbound_links | json COMMENT ''Consolidated from lupo_content_inbound_links | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| like_users | json COMMENT ''Consolidated from lupo_content_likes | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| media_attachments | json COMMENT ''Consolidated from lupo_content_media | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| question_mappings | json COMMENT ''Consolidated from lupo_content_question_map | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| content_references | json COMMENT ''Consolidated from lupo_content_references | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| revision_history | json COMMENT ''Consolidated from lupo_content_revisions | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| share_users | json COMMENT ''Consolidated from lupo_content_shares | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| tag_relationships | json COMMENT ''Consolidated from lupo_content_tag_relationships | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| like_count | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| share_count | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| comment_count | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |

## Relationships
- foreign keys: none (database doctrine forbids foreign keys)
- inbound references: no canonical inbound reference list found in TOON
- outbound references: No foreign keys or explicit relationships in TOON (`relationships: []`).
- join patterns: Join by `content_id`; common joins: `lupo_help_tree.content_id`, truth/knowledge references by id where applicable.

## Usage Notes
- migration notes: TOON and install schema are aligned for this table name.
- compatibility notes: current schema uses BIGINT timestamp doctrine and soft-delete patterns where present.
- warnings: avoid assuming implicit constraints; use doctrine that logic is application-side.
- future considerations: if additional relationships are introduced, document via TOON updates first.
- historical changes if updating existing docs: existing flat documentation was retained; this file is the category-structured canonical doc for this domain pass.
