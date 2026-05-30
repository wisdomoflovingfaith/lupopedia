---
lupopedia.schema: table_documentation
file_path_from_root: "docs/database/lupopedia/tables/lupo_truth_context_map.md"
web_path: "http://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/lupo_truth_context_map.md"
last_modified_utc: "20260329080000"
actor_id: 2
actor_name: "lilith"
purpose: "Documentation for lupo_truth_context_map table"
tags:
  - "database"
  - "schema"
  - "truth_system"
  - "lilith_audited"
---

# Table: lupo_truth_context_map

## Schema Definition

```sql
CREATE TABLE lupo_truth_context_map (
    truth_context_map_id bigint NOT NULL,
    truth_question_id bigint NOT NULL,
    context_id bigint DEFAULT NULL,
    collection_id bigint DEFAULT NULL,
    context_card_id bigint DEFAULT NULL,
    sort_order int NOT NULL DEFAULT 0,
    mapping_reason varchar(255) DEFAULT NULL,
    added_by_actor_id bigint NOT NULL,
    created_ymdhis bigint NOT NULL,
    updated_ymdhis bigint NOT NULL,
    is_deleted tinyint NOT NULL DEFAULT 0,
    deleted_ymdhis bigint DEFAULT NULL,
    PRIMARY KEY (truth_context_map_id)
);
```

## Columns

| Column                | Type           | Nullable | Default     | Description                                 |
|----------------------|----------------|----------|-------------|---------------------------------------------|
| truth_context_map_id  | bigint         | No       |             | Unique context map ID                       |
| truth_question_id     | bigint         | No       |             | Question this context mapping is for        |
| context_id            | bigint         | Yes      | NULL        | Context ID                                  |
| collection_id         | bigint         | Yes      | NULL        | Collection ID                               |
| context_card_id       | bigint         | Yes      | NULL        | Context card ID                             |
| sort_order            | int            | No       | 0           | Ordering within context                     |
| mapping_reason        | varchar(255)   | Yes      | NULL        | Reason for mapping                          |
| added_by_actor_id     | bigint         | No       |             | Actor who added mapping                     |
| created_ymdhis        | bigint         | No       |             | Creation timestamp (UTC, BIGINT)            |
| updated_ymdhis        | bigint         | No       |             | Last update timestamp (UTC, BIGINT)         |
| is_deleted            | tinyint        | No       | 0           | Soft delete flag                            |
| deleted_ymdhis        | bigint         | Yes      | NULL        | Soft delete timestamp (UTC, BIGINT)         |

## Indexes

| Index Name                          | Columns                                 | Purpose                                 |
|-------------------------------------|-----------------------------------------|-----------------------------------------|
| PRIMARY                            | truth_context_map_id                    | Primary key                             |
| lupo_truth_context_map_idx_question | truth_question_id                       | Lookup by question                      |
| lupo_truth_context_map_idx_context  | context_id                              | Lookup by context                       |
| lupo_truth_context_map_idx_collection| collection_id                          | Lookup by collection                    |
| lupo_truth_context_map_idx_card     | context_card_id                         | Lookup by card                          |
| lupo_truth_context_map_idx_added_by | added_by_actor_id                       | Lookup by actor                         |
| lupo_truth_context_map_idx_created  | created_ymdhis                          | Created date lookup                     |
| lupo_truth_context_map_idx_deleted  | is_deleted                              | Soft delete lookup                      |

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
| lupo_truth_questions | many-to-1 | The question this context mapping is for |

## Usage Examples

### Insert new context mapping
```sql
INSERT INTO lupo_truth_context_map (...);
```

### Query context mappings for a question
```sql
SELECT * FROM lupo_truth_context_map 
WHERE truth_question_id = 12345
  AND is_deleted = 0;
```

## Migration Notes

New table for Option A truth schema.
