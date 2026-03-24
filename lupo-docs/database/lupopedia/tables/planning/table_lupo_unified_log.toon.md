---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  system_version: "4.0.73"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/planning/table_lupo_unified_log.toon.md"
  last_modified_utc: "20260313"
  channel_id: 42
  actor_id: 1003
  actor_name: "antigravity"
  artifact_type: "database_schema"
  artifact_kind: "planning"
  purpose: "Planned Lupopedia database table: lupo_unified_log"
  mood_rgb: "4169E1"
  traits: ["planning", "database", "table", "future_feature"]
  tags: ["database", "table", "planning", "lupopedia"]
  lupo_agent: "antigravity"

lupopedia.edges:
  comment: "Snapshot of outbound edges for files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql", type: "schema_reference", weight: 1.0 }
  semantic_tags: ["planning", "database", "table", "future_feature"]

lupopedia.engagement:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Engagement metrics track edit frequency and importance of each file in the version transition process."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  views: 0
  like_count: 0
  share_count: 0

lupopedia.footer:
  version: "4.0.73"
  last_verified: "20260313"
  last_verified_by: "antigravity"
  orchestrator: "antigravity"
  next_action:
    - "Monitor this table for implementation readiness"
    - "Review schema for doctrine compliance"
---

# Planned Table: `lupo_unified_log`

## Status
PLANNING — not yet implemented.

## Source
Defined in: `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`

## SQL Definition

```sql
CREATE TABLE lupo_unified_log (
log_id bigint NOT NULL AUTO_INCREMENT,
  log_type enum('anubis_deletion','anubis_general','anubis_processing','audit','auth_audit','bans','channel_boot','event','interpretation','search_rebuild') NOT NULL,
  log_level enum('debug','info','warning','error','critical') DEFAULT 'info',
  log_message text NOT NULL,
  log_context json,
  actor_id int,
  channel_id int,
  session_id varchar(128),
  ip_address varchar(45),
  user_agent text,
  created_ymdhis bigint NOT NULL,
  PRIMARY KEY (log_id)
);
```

## Purpose
Explain the intended function of the table based on its structure and naming.

## Columns
| Column | Type | Null | Key | Default | Description |
|--------|------|------|-----|---------|-------------|
| log_id | bigint | NO |  | NULL | |
| log_type | enum('anubis_deletion','anubis_general','anubis_processing','audit','auth_audit','bans','channel_boot','event','interpretation','search_rebuild') | NO |  | NULL | |
| log_level | enum('debug','info','warning','error','critical') | YES |  | info | |
| log_message | text | NO |  | NULL | |
| log_context | json | YES |  | NULL | |
| actor_id | int | YES |  | NULL | |
| channel_id | int | YES |  | NULL | |
| session_id | varchar(128) | YES |  | NULL | |
| ip_address | varchar(45) | YES |  | NULL | |
| user_agent | text | YES |  | NULL | |
| created_ymdhis | bigint | NO |  | NULL | |


## Notes
- This table is not yet installed in the production schema.
- It represents a future Lupopedia capability.
