---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_help_topics.md
  web_path: '[lupo_help_topics](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_help_topics)'
  last_modified_utc: '20260317'
  channel_id: 42
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: content
  purpose: Help topic management; organizes help content, categories, and support
    documentation
  tags:
  - database
  - table
  - content
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_help_topics table doc at 4.0.79 (grounded by
    repo search; non-exhaustive).
  meta: php_hits=1 python_hits=1
  outbound_edges:
  - to: database.table.lupo_help_topics
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: lupo-scripts/setup_help_list_modules.php
    type: USED_IN_PHP
    weight: 0.7
lupopedia.footer:
  last_verified: '20260317000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_help_topics — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_help_topics

# file: lupo_help_topics ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_help_topics
# Table: lupo_help_topics

## Table Overview
- purpose: Help topic records for support/knowledge navigation.
- category: active
- status: active (present in current TOON and install schema)
- version introduced: not explicitly documented in TOON/install comments
- version deprecated: not applicable
- removal notes: not applicable
- migration references: none found in migration docs scanned

## Column Documentation
| Column | Type | Nullability | Default | Description |
|---|---|---|---|---|
| help_topic_id | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| slug | varchar(255) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| title | varchar(255) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| content_html | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| content_markdown | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| category | varchar(100) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| parent_slug | varchar(255) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| view_count | bigint | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| helpful_count | bigint | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| not_helpful_count | bigint | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| created_ymdhis | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| updated_ymdhis | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| author_actor_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| is_deleted | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |

## Relationships
- foreign keys: none (database doctrine forbids foreign keys)
- inbound references: no canonical inbound reference list found in TOON
- outbound references: No foreign keys or explicit relationships in TOON (`relationships: []`).
- join patterns: Join by `slug`/`parent_slug` for topic nesting.

## Usage Notes
- migration notes: TOON and install schema are aligned for this table name.
- compatibility notes: current schema uses BIGINT timestamp doctrine and soft-delete patterns where present.
- warnings: avoid assuming implicit constraints; use doctrine that logic is application-side.
- future considerations: if additional relationships are introduced, document via TOON updates first.
- historical changes if updating existing docs: existing flat documentation was retained; this file is the category-structured canonical doc for this domain pass.
