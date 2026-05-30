---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/planning/table_lupo_federation_discovery.toon.md
  last_modified_utc: '20260313'
  channel_id: 42
  actor_id: 1003
  actor_name: antigravity
  artifact_type: database_schema
  artifact_kind: planning
  purpose: 'Planned Lupopedia database table: lupo_federation_discovery'
  mood_vector: 4169E1
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
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260313000000'
  last_verified_by: cursor
  orchestrator: antigravity
  next_action:
  - Monitor this table for implementation readiness
  - Review schema for doctrine compliance
  last_verified_by_actor_id: 102
---

# Planned Table: `lupo_federation_discovery`

## Status
PLANNING â€” not yet implemented.

## Source
Defined in: `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`

## SQL Definition

```sql
CREATE TABLE lupo_federation_discovery (
federation_discovery_id bigint NOT NULL,
  domain varchar(255) NOT NULL,
  install_url varchar(500) DEFAULT NULL,
  is_lupopedia tinyint NOT NULL DEFAULT '0',
  last_seen_ymdhis bigint DEFAULT NULL,
  first_seen_ymdhis bigint DEFAULT NULL,
  hashtag_count bigint DEFAULT '0',
  question_count bigint DEFAULT '0',
  atom_count bigint DEFAULT '0',
  context_count bigint DEFAULT '0',
  collection_count bigint DEFAULT '0',
  keywords varchar(500) DEFAULT NULL,
  description text,
  import_hashtags tinyint NOT NULL DEFAULT '0',
  import_questions tinyint NOT NULL DEFAULT '0',
  import_atoms tinyint NOT NULL DEFAULT '0',
  import_contexts tinyint NOT NULL DEFAULT '0',
  import_collections tinyint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (federation_discovery_id)
);
```

## Purpose
Explain the intended function of the table based on its structure and naming.

## Columns
| Column | Type | Null | Key | Default | Description |
|--------|------|------|-----|---------|-------------|
| federation_discovery_id | bigint | NO |  | NULL | |
| domain | varchar(255) | NO |  | NULL | |
| install_url | varchar(500) | YES |  | NULL | |
| is_lupopedia | tinyint | NO |  | 0 | |
| last_seen_ymdhis | bigint | YES |  | NULL | |
| first_seen_ymdhis | bigint | YES |  | NULL | |
| hashtag_count | bigint | YES |  | 0 | |
| question_count | bigint | YES |  | 0 | |
| atom_count | bigint | YES |  | 0 | |
| context_count | bigint | YES |  | 0 | |
| collection_count | bigint | YES |  | 0 | |
| keywords | varchar(500) | YES |  | NULL | |
| description | text | YES |  | NULL | |
| import_hashtags | tinyint | NO |  | 0 | |
| import_questions | tinyint | NO |  | 0 | |
| import_atoms | tinyint | NO |  | 0 | |
| import_contexts | tinyint | NO |  | 0 | |
| import_collections | tinyint | NO |  | 0 | |
| created_ymdhis | bigint | NO |  | 0 | |
| updated_ymdhis | bigint | NO |  | NULL | |


## Notes
- This table is not yet installed in the production schema.
- It represents a future Lupopedia capability.

