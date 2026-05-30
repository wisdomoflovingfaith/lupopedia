---
lupopedia.schema: table_documentation
file_path_from_root: "lupo-docs/database/lupopedia/tables/lupo_truth_answers.md"
web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/database/lupopedia/tables/lupo_truth_answers.md"
last_modified_utc: "20260329080000"
actor_id: 2
actor_name: "lilith"
purpose: "DEPRECATED: See lupo_truth_questions, lupo_truth_answers, lupo_truth_evidence, lupo_truth_context_map, lupo_truth_followers."
tags:
  - "database"
  - "schema"
  - "truth_system"
  - "deprecated"
  - "lilith_audited"
---

# DEPRECATED: lupo_truth_answers (legacy)

> **This table is deprecated.**
>
> Please see the new split truth schema:
> - lupo_truth_questions
> - lupo_truth_answers
> - lupo_truth_evidence
> - lupo_truth_context_map
> - lupo_truth_followers

All new truth data is stored in the new tables. This file is retained for migration reference only.

## Schema Definition

```sql
CREATE TABLE lupo_truth_answers (
    truth_answer_id bigint NOT NULL,
    truth_question_id bigint NOT NULL,
    answer_text text NOT NULL,
    answer_summary varchar(500) DEFAULT NULL,
    answered_by_actor_id bigint NOT NULL,
    answered_in_channel_id bigint DEFAULT NULL,
    answered_in_thread_id bigint DEFAULT NULL,
    answered_in_message_id bigint DEFAULT NULL,
    is_accepted tinyint NOT NULL DEFAULT 0,
    acceptance_votes int NOT NULL DEFAULT 0,
    rejection_votes int NOT NULL DEFAULT 0,
    confidence_score decimal(3,2) NOT NULL DEFAULT 0.50,
    answer_status varchar(32) NOT NULL DEFAULT 'active',
    view_count bigint NOT NULL DEFAULT 0,
    helpful_count bigint NOT NULL DEFAULT 0,
    created_ymdhis bigint NOT NULL,
    updated_ymdhis bigint NOT NULL,
    accepted_ymdhis bigint DEFAULT NULL,
    is_deleted tinyint NOT NULL DEFAULT 0,
    deleted_ymdhis bigint DEFAULT NULL,
    metadata_json json DEFAULT NULL,
    PRIMARY KEY (truth_answer_id)
);
```

## Columns

| Column                | Type           | Nullable | Default     | Description                                 |
|----------------------|----------------|----------|-------------|---------------------------------------------|
| truth_answer_id       | bigint         | No       |             | Unique answer ID                            |
| truth_question_id     | bigint         | No       |             | Question this answer belongs to             |
| answer_text           | text           | No       |             | The answer text                             |
| answer_summary        | varchar(500)   | Yes      | NULL        | Short summary of the answer                 |
| answered_by_actor_id  | bigint         | No       |             | Actor who answered                          |
| answered_in_channel_id| bigint         | Yes      | NULL        | Channel context                             |
| answered_in_thread_id | bigint         | Yes      | NULL        | Thread context                              |
| answered_in_message_id| bigint         | Yes      | NULL        | Message context                             |
| is_accepted           | tinyint        | No       | 0           | Whether this answer is accepted             |
| acceptance_votes      | int            | No       | 0           | Number of acceptance votes                  |
| rejection_votes       | int            | No       | 0           | Number of rejection votes                   |
| confidence_score      | decimal(3,2)   | No       | 0.50        | Confidence score (0.00-1.00)                |
| answer_status         | varchar(32)    | No       | 'active'    | Status of the answer                        |
| view_count            | bigint         | No       | 0           | Number of views                             |
| helpful_count         | bigint         | No       | 0           | Number of helpful votes                     |
| created_ymdhis        | bigint         | No       |             | Creation timestamp (UTC, BIGINT)            |
| updated_ymdhis        | bigint         | No       |             | Last update timestamp (UTC, BIGINT)         |
| accepted_ymdhis       | bigint         | Yes      | NULL        | When accepted (UTC, BIGINT)                 |
| is_deleted            | tinyint        | No       | 0           | Soft delete flag                            |
| deleted_ymdhis        | bigint         | Yes      | NULL        | Soft delete timestamp (UTC, BIGINT)         |
| metadata_json         | json           | Yes      | NULL        | Additional metadata                         |

## Indexes

| Index Name                          | Columns                                 | Purpose                                 |
|-------------------------------------|-----------------------------------------|-----------------------------------------|
| PRIMARY                            | truth_answer_id                         | Primary key                             |
| lupo_truth_answers_idx_question     | truth_question_id                       | Lookup by question                      |
| lupo_truth_answers_idx_answered_by  | answered_by_actor_id                    | Lookup by actor                         |
| lupo_truth_answers_idx_channel      | answered_in_channel_id                  | Channel context lookup                  |
| lupo_truth_answers_idx_thread       | answered_in_thread_id                   | Thread context lookup                   |
| lupo_truth_answers_idx_accepted     | is_accepted, acceptance_votes           | Accepted answers                        |
| lupo_truth_answers_idx_confidence   | confidence_score                        | Confidence score lookup                 |
| lupo_truth_answers_idx_status       | answer_status                           | Status lookup                           |
| lupo_truth_answers_idx_created      | created_ymdhis                          | Created date lookup                     |
| lupo_truth_answers_idx_deleted      | is_deleted                              | Soft delete lookup                      |

## Constitutional Compliance

- [x] NO foreign keys - All relationships explicit in application logic
- [x] NO triggers - No database-level logic
- [x] NO stored procedures
- [x] BIGINT timestamps only (YYYYMMDDHHIISS UTC format)
- [x] Explicit ID generation (application layer)
- [x] Soft delete support (is_deleted + deleted_ymdhis)
- [x] Actor attribution (created_by/updated_by where applicable)

## Relationships

| Related Table         | Type      | Description                              |
|----------------------|-----------|------------------------------------------|
| lupo_truth_questions | many-to-1 | The question this answers                |
| lupo_truth_evidence  | 1-to-many | Evidence supporting this answer          |

## Usage Examples

### Insert a new answer
```sql
INSERT INTO lupo_truth_answers (...);
```

### Query answers for a question
```sql
SELECT * FROM lupo_truth_answers 
WHERE truth_question_id = 12345
  AND is_deleted = 0;
```

## Migration Notes

Replaces functionality from deprecated `lupo_truth_answers` table.
For Crafty Syntax import, map old data structures as follows:
- Old truth_type 'answer' → lupo_truth_answers
- Old truth_type 'evidence' → lupo_truth_evidence
