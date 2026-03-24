---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/planning/table_lupo_anubis_deletion_log.toon.md
  last_modified_utc: '20260313'
  channel_id: 42
  actor_id: 1003
  actor_name: antigravity
  artifact_type: database_schema
  artifact_kind: planning
  purpose: 'Planned Lupopedia database table: lupo_anubis_deletion_log'
  mood_rgb: 4169E1
  traits:
  - planning
  - database
  - table
  - future_feature
  tags:
  - database
  - table
  - planning
  - lupopedia
  lupo_agent: antigravity
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of outbound edges for files edited during 4.0.73 finalization
    and initialization thread by ANTIGRAVITY IDE Agent.
  meta: "Thread: Finalize 4.0.72 \u2192 Push to GitHub \u2192 Initialize 4.0.73 \u2192\
    \ Migrate Tasks \u2192 Validate Upgrade Path"
  outbound_edges:
  - to: lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql
    type: schema_reference
    weight: 1.0
  semantic_tags:
  - planning
  - database
  - table
  - future_feature
lupopedia.engagement:
  comment: Snapshot of files edited during 4.0.73 finalization and initialization
    thread by ANTIGRAVITY IDE Agent. Engagement metrics track edit frequency and importance
    of each file in the version transition process.
  meta: "Thread: Finalize 4.0.72 \u2192 Push to GitHub \u2192 Initialize 4.0.73 \u2192\
    \ Migrate Tasks \u2192 Validate Upgrade Path"
  views: 0
  like_count: 0
  share_count: 0
lupopedia.footer:
  last_verified: '20260313000000'
  last_verified_by: cursor
  orchestrator: antigravity
  next_action:
  - Monitor this table for implementation readiness
  - Review schema for doctrine compliance
  last_verified_by_actor_id: 102
---

# Planned Table: `lupo_anubis_deletion_log`

## Status
PLANNING — not yet implemented.

## Source
Defined in: `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`

## SQL Definition

```sql
CREATE TABLE lupo_anubis_deletion_log (
anubis_deletion_id bigint NOT NULL,
  table_name varchar(255) NOT NULL,
  record_id bigint NOT NULL,
  deleted_ymdhis bigint NOT NULL,
  deletion_type varchar(64) NOT NULL,
  replacement_table varchar(255) DEFAULT NULL,
  replacement_id bigint DEFAULT NULL,
  anubis_operator varchar(255) NOT NULL,
  context_json json DEFAULT NULL,
  notes text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (anubis_deletion_id)
);
```

## Purpose
Explain the intended function of the table based on its structure and naming.

## Columns
| Column | Type | Null | Key | Default | Description |
|--------|------|------|-----|---------|-------------|
| anubis_deletion_id | bigint | NO |  | NULL | |
| table_name | varchar(255) | NO |  | NULL | |
| record_id | bigint | NO |  | NULL | |
| deleted_ymdhis | bigint | NO |  | NULL | |
| deletion_type | varchar(64) | NO |  | NULL | |
| replacement_table | varchar(255) | YES |  | NULL | |
| replacement_id | bigint | YES |  | NULL | |
| anubis_operator | varchar(255) | NO |  | NULL | |
| context_json | json | YES |  | NULL | |
| notes | text | YES |  | NULL | |
| created_ymdhis | bigint | NO |  | 0 | |
| updated_ymdhis | bigint | NO |  | 0 | |
| is_deleted | tinyint | NO |  | 0 | |


## Notes
- This table is not yet installed in the production schema.
- It represents a future Lupopedia capability.
