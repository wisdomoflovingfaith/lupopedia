---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/planning/table_lupo_temporal_coherence_snapshots.toon.md
  last_modified_utc: '20260313'
  channel_id: 42
  actor_id: 1003
  actor_name: antigravity
  artifact_type: database_schema
  artifact_kind: planning
  purpose: 'Planned Lupopedia database table: lupo_temporal_coherence_snapshots'
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

# Planned Table: `lupo_temporal_coherence_snapshots`

## Status
PLANNING — not yet implemented.

## Source
Defined in: `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`

## SQL Definition

```sql
CREATE TABLE lupo_temporal_coherence_snapshots (
snapshot_id bigint NOT NULL,
  utc_anchor bigint NOT NULL,
  observation_latency_ms int NOT NULL DEFAULT 0,
  recursion_depth tinyint NOT NULL DEFAULT 0,
  self_awareness_score decimal(3,2) DEFAULT NULL,
  timestamp_integrity varchar(32) NOT NULL DEFAULT 'unknown',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (snapshot_id)
);
```

## Purpose
Explain the intended function of the table based on its structure and naming.

## Columns
| Column | Type | Null | Key | Default | Description |
|--------|------|------|-----|---------|-------------|
| snapshot_id | bigint | NO |  | NULL | |
| utc_anchor | bigint | NO |  | NULL | |
| observation_latency_ms | int | NO |  | 0 | |
| recursion_depth | tinyint | NO |  | 0 | |
| self_awareness_score | decimal(3,2) | YES |  | NULL | |
| timestamp_integrity | varchar(32) | NO |  | unknown | |
| created_ymdhis | bigint | NO |  | 0 | |
| is_deleted | tinyint | NO |  | 0 | |
| deleted_ymdhis | bigint | YES |  | NULL | |


## Notes
- This table is not yet installed in the production schema.
- It represents a future Lupopedia capability.
