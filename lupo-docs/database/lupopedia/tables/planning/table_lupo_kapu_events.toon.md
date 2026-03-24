---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/planning/table_lupo_kapu_events.toon.md
  last_modified_utc: '20260313'
  channel_id: 42
  actor_id: 1003
  actor_name: antigravity
  artifact_type: database_schema
  artifact_kind: planning
  purpose: 'Planned Lupopedia database table: lupo_kapu_events'
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

# Planned Table: `lupo_kapu_events`

## Status
PLANNING — not yet implemented.

## Source
Defined in: `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`

## SQL Definition

```sql
CREATE TABLE lupo_kapu_events (
kapu_id bigint NOT NULL,
  agent_id varchar(255) DEFAULT NULL,
  imposed_by_actor_id varchar(255) DEFAULT NULL,
  kapu_type varchar(64) DEFAULT NULL,
  restrictions json DEFAULT NULL,
  restoration_plan json DEFAULT NULL,
  kapakai_level decimal(3,2) DEFAULT NULL,
  review_schedule json DEFAULT NULL,
  accepted_at bigint DEFAULT NULL,
  appealed_at bigint DEFAULT NULL,
  active tinyint DEFAULT '1',
  created_at bigint DEFAULT NULL,
  PRIMARY KEY (kapu_id)
);
```

## Purpose
Explain the intended function of the table based on its structure and naming.

## Columns
| Column | Type | Null | Key | Default | Description |
|--------|------|------|-----|---------|-------------|
| kapu_id | bigint | NO |  | NULL | |
| agent_id | varchar(255) | YES |  | NULL | |
| imposed_by_actor_id | varchar(255) | YES |  | NULL | |
| kapu_type | varchar(64) | YES |  | NULL | |
| restrictions | json | YES |  | NULL | |
| restoration_plan | json | YES |  | NULL | |
| kapakai_level | decimal(3,2) | YES |  | NULL | |
| review_schedule | json | YES |  | NULL | |
| accepted_at | bigint | YES |  | NULL | |
| appealed_at | bigint | YES |  | NULL | |
| active | tinyint | YES |  | 1 | |
| created_at | bigint | YES |  | NULL | |


## Notes
- This table is not yet installed in the production schema.
- It represents a future Lupopedia capability.
