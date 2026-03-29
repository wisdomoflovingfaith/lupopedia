---
lupopedia.schema: table_documentation
file_path_from_root: "lupo-docs/database/lupopedia/tables/lupo_truth_evidence.md"
web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/database/lupopedia/tables/lupo_truth_evidence.md"
last_modified_utc: "20260329080000"
actor_id: 2
actor_name: "lilith"
purpose: "Documentation for lupo_truth_evidence table"
tags:
  - "database"
  - "schema"
  - "truth_system"
  - "lilith_audited"
---

# Table: lupo_truth_evidence

## Schema Definition

```sql
CREATE TABLE lupo_truth_evidence (
    truth_evidence_id bigint NOT NULL,
    truth_answer_id bigint NOT NULL,
    evidence_type varchar(64) NOT NULL,
    source_object_type varchar(64) NOT NULL,
    source_object_id bigint NOT NULL,
    source_federation_node_id bigint DEFAULT NULL,
    source_url varchar(2000) DEFAULT NULL,
    source_title varchar(500) DEFAULT NULL,
    evidence_text text,
    evidence_excerpt varchar(1000) DEFAULT NULL,
    reliability_score decimal(3,2) NOT NULL DEFAULT 0.50,
    relevance_score decimal(3,2) NOT NULL DEFAULT 0.50,
    is_verified tinyint NOT NULL DEFAULT 0,
    verified_by_actor_id bigint DEFAULT NULL,
    verified_ymdhis bigint DEFAULT NULL,
    verification_notes text DEFAULT NULL,
    submitted_by_actor_id bigint NOT NULL,
    created_ymdhis bigint NOT NULL,
    updated_ymdhis bigint NOT NULL,
    is_deleted tinyint NOT NULL DEFAULT 0,
    deleted_ymdhis bigint DEFAULT NULL,
    PRIMARY KEY (truth_evidence_id)
);
```

## Columns

| Column                  | Type           | Nullable | Default     | Description                                 |
|------------------------|----------------|----------|-------------|---------------------------------------------|
| truth_evidence_id       | bigint         | No       |             | Unique evidence ID                          |
| truth_answer_id         | bigint         | No       |             | Answer this evidence supports               |
| evidence_type           | varchar(64)    | No       |             | Type of evidence                            |
| source_object_type      | varchar(64)    | No       |             | Type of source object                       |
| source_object_id        | bigint         | No       |             | ID of source object                         |
| source_federation_node_id| bigint        | Yes      | NULL        | Federation node for cross-node evidence     |
| source_url              | varchar(2000)  | Yes      | NULL        | Source URL                                  |
| source_title            | varchar(500)   | Yes      | NULL        | Source title                                |
| evidence_text           | text           | Yes      | NULL        | Evidence content                            |
| evidence_excerpt        | varchar(1000)  | Yes      | NULL        | Short excerpt                               |
| reliability_score       | decimal(3,2)   | No       | 0.50        | Reliability score                           |
| relevance_score         | decimal(3,2)   | No       | 0.50        | Relevance score                             |
| is_verified             | tinyint        | No       | 0           | Whether evidence is verified                |
| verified_by_actor_id    | bigint         | Yes      | NULL        | Actor who verified                          |
| verified_ymdhis         | bigint         | Yes      | NULL        | When verified (UTC, BIGINT)                 |
| verification_notes      | text           | Yes      | NULL        | Notes on verification                       |
| submitted_by_actor_id   | bigint         | No       |             | Actor who submitted                         |
| created_ymdhis          | bigint         | No       |             | Creation timestamp (UTC, BIGINT)            |
| updated_ymdhis          | bigint         | No       |             | Last update timestamp (UTC, BIGINT)         |
| is_deleted              | tinyint        | No       | 0           | Soft delete flag                            |
| deleted_ymdhis          | bigint         | Yes      | NULL        | Soft delete timestamp (UTC, BIGINT)         |

## Indexes

| Index Name                          | Columns                                 | Purpose                                 |
|-------------------------------------|-----------------------------------------|-----------------------------------------|
| PRIMARY                            | truth_evidence_id                       | Primary key                             |
| lupo_truth_evidence_idx_answer      | truth_answer_id                         | Lookup by answer                        |
| lupo_truth_evidence_idx_source      | source_object_type, source_object_id     | Source object lookup                    |
| lupo_truth_evidence_idx_federation  | source_federation_node_id               | Federation node lookup                  |
| lupo_truth_evidence_idx_verified    | is_verified, reliability_score          | Verified evidence lookup                |
| lupo_truth_evidence_idx_evidence_type| evidence_type                          | Evidence type lookup                    |
| lupo_truth_evidence_idx_submitted_by| submitted_by_actor_id                   | Submitter lookup                        |
| lupo_truth_evidence_idx_created     | created_ymdhis                          | Created date lookup                     |
| lupo_truth_evidence_idx_deleted     | is_deleted                              | Soft delete lookup                      |

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
| lupo_truth_answers   | many-to-1 | The answer this evidence supports        |

## Usage Examples

### Insert new evidence
```sql
INSERT INTO lupo_truth_evidence (...);
```

### Query evidence for an answer
```sql
SELECT * FROM lupo_truth_evidence 
WHERE truth_answer_id = 12345
  AND is_deleted = 0;
```

## Migration Notes

Replaces functionality from deprecated `lupo_truth_knowledge` and `lupo_truth_answers` tables.
For Crafty Syntax import, map old data structures as follows:
- Old truth_type 'evidence' → lupo_truth_evidence
