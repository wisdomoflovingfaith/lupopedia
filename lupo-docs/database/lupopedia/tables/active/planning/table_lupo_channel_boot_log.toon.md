---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  system_version: "4.0.73"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/planning/table_lupo_channel_boot_log.toon.md"
  last_modified_utc: "20260313"
  channel_id: 42
  actor_id: 1003
  actor_name: "antigravity"
  artifact_type: "database_schema"
  artifact_kind: "planning"
  purpose: "Planned Lupopedia database table: lupo_channel_boot_log"
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

# Planned Table: `lupo_channel_boot_log`

## Status
PLANNING — not yet implemented.

## Source
Defined in: `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`

## SQL Definition

```sql
CREATE TABLE lupo_channel_boot_log (
boot_id bigint NOT NULL,
  actor_id bigint DEFAULT NULL,
  session_id varchar(64) DEFAULT NULL,
  boot_start_time bigint,
  boot_end_time bigint,
  boot_status varchar(64) NOT NULL DEFAULT 'started',
  channels_loaded int NOT NULL DEFAULT '0',
  total_channels int NOT NULL DEFAULT '0',
  error_details json DEFAULT NULL,
  performance_metrics json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (boot_id)
);
```

## Purpose
Explain the intended function of the table based on its structure and naming.

## Columns
| Column | Type | Null | Key | Default | Description |
|--------|------|------|-----|---------|-------------|
| boot_id | bigint | NO |  | NULL | |
| actor_id | bigint | YES |  | NULL | |
| session_id | varchar(64) | YES |  | NULL | |
| boot_start_time | bigint | YES |  | NULL | |
| boot_end_time | bigint | YES |  | NULL | |
| boot_status | varchar(64) | NO |  | started | |
| channels_loaded | int | NO |  | 0 | |
| total_channels | int | NO |  | 0 | |
| error_details | json | YES |  | NULL | |
| performance_metrics | json | YES |  | NULL | |
| created_ymdhis | bigint | NO |  | 0 | |


## Notes
- This table is not yet installed in the production schema.
- It represents a future Lupopedia capability.
