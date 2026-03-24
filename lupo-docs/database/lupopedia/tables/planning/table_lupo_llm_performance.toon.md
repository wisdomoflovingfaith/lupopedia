---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/planning/table_lupo_llm_performance.toon.md
  last_modified_utc: '20260313'
  channel_id: 42
  actor_id: 1003
  actor_name: antigravity
  artifact_type: database_schema
  artifact_kind: planning
  purpose: 'Planned Lupopedia database table: lupo_llm_performance'
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

# Planned Table: `lupo_llm_performance`

## Status
PLANNING — not yet implemented.

## Source
Defined in: `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`

## SQL Definition

```sql
CREATE TABLE lupo_llm_performance (
performance_id BIGINT NOT NULL,
    actor_id BIGINT NOT NULL,
    llm_module VARCHAR(100) NOT NULL,
    provider VARCHAR(50),
    total_tokens BIGINT DEFAULT 0,
    avg_response_time_ms INT DEFAULT 0,
    success_rate FLOAT DEFAULT 1,
    cost_per_1k_tokens DECIMAL(10,4) DEFAULT 0.0000,
    quality_score FLOAT DEFAULT 1,
    last_used_ymdhis BIGINT DEFAULT 0,
    performance_data JSON,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT
);
```

## Purpose
Explain the intended function of the table based on its structure and naming.

## Columns
| Column | Type | Null | Key | Default | Description |
|--------|------|------|-----|---------|-------------|
| performance_id | BIGINT | NO |  | NULL | |
| actor_id | BIGINT | NO |  | NULL | |
| llm_module | VARCHAR(100) | NO |  | NULL | |
| provider | VARCHAR(50) | YES |  | NULL | |
| total_tokens | BIGINT | YES |  | 0 | |
| avg_response_time_ms | INT | YES |  | 0 | |
| success_rate | FLOAT | YES |  | 1 | |
| cost_per_1k_tokens | DECIMAL(10,4) | YES |  | 0.0000 | |
| quality_score | FLOAT | YES |  | 1 | |
| last_used_ymdhis | BIGINT | YES |  | 0 | |
| performance_data | JSON | YES |  | NULL | |
| created_ymdhis | BIGINT | NO |  | 0 | |
| updated_ymdhis | BIGINT | YES |  | NULL | |
| is_deleted | TINYINT | NO |  | 0 | |
| deleted_ymdhis | BIGINT | YES |  | NULL | |


## Notes
- This table is not yet installed in the production schema.
- It represents a future Lupopedia capability.
