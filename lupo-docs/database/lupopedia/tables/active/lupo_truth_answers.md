---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_truth_answers.md
  last_modified_utc: '20260312'
  channel_id: 1
  actor_id: 103
  delegation_chain: 103:10000
  artifact_type: documentation
  artifact_kind: database_table
  purpose: JetBrains domain table documentation for lupo_truth_answers
  lupo_agent: jetbrains
  when_updated: '20260324174654'
lupopedia:
  footer:
    last_verified: '20260324174654'
    last_verified_by: cursor
    last_verified_by_actor_id: 102
    orchestrator: cursor:root
---

# Table: lupo_truth_answers

## Table Overview
- purpose: Answer records linked to truth-question entities.
- category: active
- status: active (present in current TOON and install schema)
- version introduced: not explicitly documented in TOON/install comments
- version deprecated: not applicable
- removal notes: not applicable
- migration references: MIGRATION_MAPPING_REFERENCE.md, livehelp_qa_migration.md

## Column Documentation
| Column | Type | Nullability | Default | Description |
|---|---|---|---|---|
| truth_answer_id | bigint auto_increment | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| truth_question_id | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| actor_id | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| answer_text | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| confidence | decimal(5,2) | Nullable/unspecified | 0.00 | TOON-defined field; canonical semantic description not specified in TOON. |
| evidence_count | int | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| source_count | int | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| status | varchar(64) | Nullable/unspecified | ''active | TOON-defined field; canonical semantic description not specified in TOON. |
| created_ymdhis | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| updated_ymdhis | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| is_deleted | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| deleted_ymdhis | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| evidence_score | decimal(5,2) | Nullable/unspecified | 0.00 | TOON-defined field; canonical semantic description not specified in TOON. |
| contradiction_flag | tinyint | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| likes_count | bigint | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |

## Relationships
- foreign keys: none (database doctrine forbids foreign keys)
- inbound references: no canonical inbound reference list found in TOON
- outbound references: No foreign keys or explicit relationships in TOON (`relationships: []`).
- join patterns: Join by `truth_question_id` and `actor_id` with `lupo_truth_knowledge`/`lupo_actors`.

## Usage Notes
- migration notes: TOON and install schema are aligned for this table name.
- compatibility notes: current schema uses BIGINT timestamp doctrine and soft-delete patterns where present.
- warnings: avoid assuming implicit constraints; use doctrine that logic is application-side.
- future considerations: if additional relationships are introduced, document via TOON updates first.
- historical changes if updating existing docs: existing flat documentation was retained; this file is the category-structured canonical doc for this domain pass.
