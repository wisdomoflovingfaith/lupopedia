---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  system_version: "4.0.73"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/planning/table_lupo_flare_headers.toon.md"
  last_modified_utc: "20260313"
  channel_id: 42
  actor_id: 1003
  actor_name: "antigravity"
  artifact_type: "database_schema"
  artifact_kind: "planning"
  purpose: "Planned Lupopedia database table: lupo_flare_headers"
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

# Planned Table: `lupo_flare_headers`

## Status
PLANNING — not yet implemented.

## Source
Defined in: `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`

## SQL Definition

```sql
CREATE TABLE lupo_flare_headers (
content_id bigint NOT NULL,
  flare_version varchar(20) DEFAULT NULL,
  flare_schema varchar(50) DEFAULT NULL,
  file_path_from_root text,
  web_path text,
  last_modified_utc varchar(14) DEFAULT NULL,
  system_version varchar(20) DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  actor_id bigint DEFAULT NULL,
  delegation_chain varchar(255) DEFAULT NULL,
  artifact_type varchar(50) DEFAULT NULL,
  artifact_kind varchar(50) DEFAULT NULL,
  purpose text,
  mood_rgb varchar(6) DEFAULT NULL,
  traits json DEFAULT NULL,
  tags json DEFAULT NULL,
  lupo_agent varchar(50) DEFAULT NULL,
  agent_name_identity varchar(255) DEFAULT NULL,
  PRIMARY KEY (content_id)
);
```

## Purpose
Explain the intended function of the table based on its structure and naming.

## Columns
| Column | Type | Null | Key | Default | Description |
|--------|------|------|-----|---------|-------------|
| content_id | bigint | NO |  | NULL | |
| flare_version | varchar(20) | YES |  | NULL | |
| flare_schema | varchar(50) | YES |  | NULL | |
| file_path_from_root | text | YES |  | NULL | |
| web_path | text | YES |  | NULL | |
| last_modified_utc | varchar(14) | YES |  | NULL | |
| system_version | varchar(20) | YES |  | NULL | |
| channel_id | bigint | YES |  | NULL | |
| actor_id | bigint | YES |  | NULL | |
| delegation_chain | varchar(255) | YES |  | NULL | |
| artifact_type | varchar(50) | YES |  | NULL | |
| artifact_kind | varchar(50) | YES |  | NULL | |
| purpose | text | YES |  | NULL | |
| mood_rgb | varchar(6) | YES |  | NULL | |
| traits | json | YES |  | NULL | |
| tags | json | YES |  | NULL | |
| lupo_agent | varchar(50) | YES |  | NULL | |
| agent_name_identity | varchar(255) | YES |  | NULL | |


## Notes
- This table is not yet installed in the production schema.
- It represents a future Lupopedia capability.
