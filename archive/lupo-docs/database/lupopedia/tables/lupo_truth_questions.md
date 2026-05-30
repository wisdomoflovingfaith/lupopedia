---
lupopedia.schema: table_documentation
file_path_from_root: "lupo-docs/database/lupopedia/tables/lupo_truth_questions.md"
web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/database/lupopedia/tables/lupo_truth_questions.md"
last_modified_utc: "20260329080000"
actor_id: 2
actor_name: "lilith"
purpose: "Documentation for lupo_truth_questions table"
tags:
  - "database"
  - "schema"
  - "truth_system"
  - "lilith_audited"
---

# Table: lupo_truth_questions

## Schema Definition

```sql
CREATE TABLE lupo_truth_questions (
    truth_question_id bigint NOT NULL,
    parent_question_id bigint DEFAULT NULL,
    root_question_id bigint DEFAULT NULL,
    depth tinyint NOT NULL DEFAULT 0,
    target_object_type varchar(64) NOT NULL,
    target_object_id bigint NOT NULL,
    question_text text NOT NULL,
    question_summary varchar(500) DEFAULT NULL,
    asked_by_actor_id bigint NOT NULL,
    asked_in_channel_id bigint DEFAULT NULL,
    asked_in_thread_id bigint DEFAULT NULL,
    asked_in_session_id varchar(128) DEFAULT NULL,
    question_status varchar(32) NOT NULL DEFAULT 'open',
    is_answered tinyint NOT NULL DEFAULT 0,
    is_featured tinyint NOT NULL DEFAULT 0,
    view_count bigint NOT NULL DEFAULT 0,
    answer_count bigint NOT NULL DEFAULT 0,
    follower_count bigint NOT NULL DEFAULT 0,
    created_ymdhis bigint NOT NULL,
    updated_ymdhis bigint NOT NULL,
    answered_ymdhis bigint DEFAULT NULL,
    closed_ymdhis bigint DEFAULT NULL,
    is_deleted tinyint NOT NULL DEFAULT 0,
    deleted_ymdhis bigint DEFAULT NULL,
    metadata_json json DEFAULT NULL,
    PRIMARY KEY (truth_question_id)
);
```

## Columns

| Column                | Type           | Nullable | Default     | Description                                 |
|----------------------|----------------|----------|-------------|---------------------------------------------|
| truth_question_id     | bigint         | No       |             | Unique question ID                          |
| parent_question_id    | bigint         | Yes      | NULL        | Parent question (for hierarchy)             |
| root_question_id      | bigint         | Yes      | NULL        | Root question (for hierarchy)               |
| depth                | tinyint        | No       | 0           | Depth in question tree                      |
| target_object_type    | varchar(64)    | No       |             | Type of entity this question is about        |
| target_object_id      | bigint         | No       |             | ID of entity this question is about         |
| question_text         | text           | No       |             | The question text                           |
| question_summary      | varchar(500)   | Yes      | NULL        | Short summary of the question               |
| asked_by_actor_id     | bigint         | No       |             | Actor who asked the question                |
| asked_in_channel_id   | bigint         | Yes      | NULL        | Channel context                             |
| asked_in_thread_id    | bigint         | Yes      | NULL        | Thread context                              |
| asked_in_session_id   | varchar(128)   | Yes      | NULL        | Session context                             |
| question_status       | varchar(32)    | No       | 'open'      | Status of the question                      |
| is_answered           | tinyint        | No       | 0           | Whether the question is answered            |
| is_featured           | tinyint        | No       | 0           | Whether the question is featured            |
| view_count            | bigint         | No       | 0           | Number of views                             |
| answer_count          | bigint         | No       | 0           | Number of answers                           |
| follower_count        | bigint         | No       | 0           | Number of followers                         |
| created_ymdhis        | bigint         | No       |             | Creation timestamp (UTC, BIGINT)            |
| updated_ymdhis        | bigint         | No       |             | Last update timestamp (UTC, BIGINT)         |
| answered_ymdhis       | bigint         | Yes      | NULL        | When answered (UTC, BIGINT)                 |
| closed_ymdhis         | bigint         | Yes      | NULL        | When closed (UTC, BIGINT)                   |
| is_deleted            | tinyint        | No       | 0           | Soft delete flag                            |
| deleted_ymdhis        | bigint         | Yes      | NULL        | Soft delete timestamp (UTC, BIGINT)         |
| metadata_json         | json           | Yes      | NULL        | Additional metadata                         |

## Indexes

| Index Name                          | Columns                                 | Purpose                                 |
|-------------------------------------|-----------------------------------------|-----------------------------------------|
| PRIMARY                            | truth_question_id                        | Primary key                             |
| lupo_truth_questions_idx_parent     | parent_question_id                       | Hierarchy lookup                        |
| lupo_truth_questions_idx_root       | root_question_id                         | Hierarchy lookup                        |
| lupo_truth_questions_idx_target     | target_object_type, target_object_id      | Polymorphic target lookup               |
| lupo_truth_questions_idx_asked_by   | asked_by_actor_id                        | Actor lookup                            |
| lupo_truth_questions_idx_channel    | asked_in_channel_id                      | Channel context lookup                  |
| lupo_truth_questions_idx_thread     | asked_in_thread_id                       | Thread context lookup                   |
| lupo_truth_questions_idx_status     | question_status, is_answered             | Status lookup                           |
| lupo_truth_questions_idx_created    | created_ymdhis                           | Created date lookup                     |
| lupo_truth_questions_idx_featured   | is_featured                              | Featured questions                      |
| lupo_truth_questions_idx_deleted    | is_deleted                               | Soft delete lookup                      |

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
| lupo_truth_answers   | 1-to-many | Answers to this question                 |
| lupo_truth_context_map | 1-to-many | Contexts this question appears in        |
| lupo_truth_followers | 1-to-many | Actors following this question           |

## Usage Examples

### Insert a new question
```sql
INSERT INTO lupo_truth_questions (...);
```

### Query questions for a thread
```sql
SELECT * FROM lupo_truth_questions 
WHERE target_object_type = 'dialog_thread' 
  AND target_object_id = 12345
  AND is_deleted = 0;
```

## Migration Notes

Replaces functionality from deprecated `lupo_truth_knowledge` table.
For Crafty Syntax import, map old data structures as follows:
- Old truth_type 'question' → lupo_truth_questions
- Old truth_type 'answer' → lupo_truth_answers
- Old truth_type 'evidence' → lupo_truth_evidence
