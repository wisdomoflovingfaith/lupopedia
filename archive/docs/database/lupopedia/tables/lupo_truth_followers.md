---
lupopedia.schema: table_documentation
file_path_from_root: "docs/database/lupopedia/tables/lupo_truth_followers.md"
web_path: "http://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/lupo_truth_followers.md"
last_modified_utc: "20260329080000"
actor_id: 2
actor_name: "lilith"
purpose: "Documentation for lupo_truth_followers table"
tags:
  - "database"
  - "schema"
  - "truth_system"
  - "lilith_audited"
---

# Table: lupo_truth_followers

## Schema Definition

```sql
CREATE TABLE lupo_truth_followers (
    truth_follower_id bigint NOT NULL,
    truth_question_id bigint NOT NULL,
    actor_id bigint NOT NULL,
    notification_enabled tinyint NOT NULL DEFAULT 1,
    created_ymdhis bigint NOT NULL,
    updated_ymdhis bigint NOT NULL,
    is_deleted tinyint NOT NULL DEFAULT 0,
    deleted_ymdhis bigint DEFAULT NULL,
    PRIMARY KEY (truth_follower_id)
);
```

## Columns

| Column                | Type           | Nullable | Default     | Description                                 |
|----------------------|----------------|----------|-------------|---------------------------------------------|
| truth_follower_id     | bigint         | No       |             | Unique follower ID                          |
| truth_question_id     | bigint         | No       |             | Question being followed                     |
| actor_id              | bigint         | No       |             | Actor following the question                |
| notification_enabled  | tinyint        | No       | 1           | Whether notifications are enabled           |
| created_ymdhis        | bigint         | No       |             | Creation timestamp (UTC, BIGINT)            |
| updated_ymdhis        | bigint         | No       |             | Last update timestamp (UTC, BIGINT)         |
| is_deleted            | tinyint        | No       | 0           | Soft delete flag                            |
| deleted_ymdhis        | bigint         | Yes      | NULL        | Soft delete timestamp (UTC, BIGINT)         |

## Indexes

| Index Name                          | Columns                                 | Purpose                                 |
|-------------------------------------|-----------------------------------------|-----------------------------------------|
| PRIMARY                            | truth_follower_id                       | Primary key                             |
| lupo_truth_followers_unq_question_actor | truth_question_id, actor_id          | Unique per question/actor               |
| lupo_truth_followers_idx_question   | truth_question_id                       | Lookup by question                      |
| lupo_truth_followers_idx_actor      | actor_id                                | Lookup by actor                         |
| lupo_truth_followers_idx_deleted    | is_deleted                              | Soft delete lookup                      |

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
| lupo_truth_questions | many-to-1 | The question being followed              |

## Usage Examples

### Insert new follower
```sql
INSERT INTO lupo_truth_followers (...);
```

### Query followers for a question
```sql
SELECT * FROM lupo_truth_followers 
WHERE truth_question_id = 12345
  AND is_deleted = 0;
```

## Migration Notes

New table for Option A truth schema.
