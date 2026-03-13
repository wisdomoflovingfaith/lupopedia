---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_truth_knowledge.md"
  last_modified_utc: "20260312"
  system_version: "4.0.69"
  channel_id: 1
  actor_id: 103
  delegation_chain: "103:10000"
  artifact_type: "documentation"
  artifact_kind: "database_table"
  purpose: "JetBrains domain table documentation for lupo_truth_knowledge"
  lupo_agent: "jetbrains"
---

# Table: lupo_truth_knowledge

## Table Overview
- purpose: Knowledge graph truth entities (questions, answers, evidence, sources, relations).
- category: active
- status: active (present in current TOON and install schema)
- version introduced: not explicitly documented in TOON/install comments
- version deprecated: not applicable
- removal notes: not applicable
- migration references: none found in migration docs scanned

## Column Documentation
| Column | Type | Nullability | Default | Description |
|---|---|---|---|---|
| truth_id | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| truth_type | varchar(32) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| parent_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| question_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| answer_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| evidence_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| source_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| topic_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| relation_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| actor_id | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| object_type | varchar(50) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| object_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| left_object_type | varchar(50) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| left_object_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| right_object_type | varchar(50) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| right_object_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| slug | varchar(255) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| title | varchar(255) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| text_content | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| question_text | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| answer_text | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| evidence_text | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| source_url | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| source_title | varchar(255) DEFAULT | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| qtype | varchar(50) | Nullable/unspecified | ''unknown | TOON-defined field; canonical semantic description not specified in TOON. |
| status | varchar(64) | Nullable/unspecified | ''active | TOON-defined field; canonical semantic description not specified in TOON. |
| evidence_type | varchar(50) DEFAULT | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| source_type | varchar(50) DEFAULT | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| relation_type | varchar(50) DEFAULT | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| format | varchar(64) | Nullable/unspecified | ''text | TOON-defined field; canonical semantic description not specified in TOON. |
| format_override | varchar(50) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| confidence_score | decimal(5,2) | Nullable/unspecified | 0.00 | TOON-defined field; canonical semantic description not specified in TOON. |
| evidence_score | decimal(5,2) | Nullable/unspecified | 0.00 | TOON-defined field; canonical semantic description not specified in TOON. |
| weight_score | decimal(5,2) | Nullable/unspecified | 0.00 | TOON-defined field; canonical semantic description not specified in TOON. |
| reliability_score | decimal(5,2) | Nullable/unspecified | 0.00 | TOON-defined field; canonical semantic description not specified in TOON. |
| importance_score | decimal(5,2) | Nullable/unspecified | 0.00 | TOON-defined field; canonical semantic description not specified in TOON. |
| sort_num | int | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| view_count | bigint | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| likes_count | bigint | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| shares_count | bigint | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| answer_count | bigint | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| contradiction_flag | tinyint | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| is_featured | tinyint | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| is_verified | tinyint | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| last_activity_ymdhis | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| default_collection_id | bigint | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| created_ymdhis | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| updated_ymdhis | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| is_deleted | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| deleted_ymdhis | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| truth_question_parent_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |

## Relationships
- foreign keys: none (database doctrine forbids foreign keys)
- inbound references: no canonical inbound reference list found in TOON
- outbound references: No foreign keys or explicit relationships in TOON (`relationships: []`).
- join patterns: Self-joins by `parent_id`; joins to `lupo_truth_answers` via question/answer id paths.

## Usage Notes
- migration notes: TOON and install schema are aligned for this table name.
- compatibility notes: current schema uses BIGINT timestamp doctrine and soft-delete patterns where present.
- warnings: avoid assuming implicit constraints; use doctrine that logic is application-side.
- future considerations: if additional relationships are introduced, document via TOON updates first.
- historical changes if updating existing docs: existing flat documentation was retained; this file is the category-structured canonical doc for this domain pass.
