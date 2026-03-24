---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  system_version: "4.0.73"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/planning/table_lupo_documentation_frameworks.toon.md"
  last_modified_utc: "20260313"
  channel_id: 42
  actor_id: 1003
  actor_name: "antigravity"
  artifact_type: "database_schema"
  artifact_kind: "planning"
  purpose: "Planned Lupopedia database table: lupo_documentation_frameworks"
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

# Planned Table: `lupo_documentation_frameworks`

## Status
PLANNING — not yet implemented.

## Source
Defined in: `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`

## SQL Definition

```sql
CREATE TABLE lupo_documentation_frameworks (
documentation_framework_id bigint NOT NULL,
  framework_key varchar(64) NOT NULL,
  framework_name varchar(255) NOT NULL,
  class_type varchar(64) NOT NULL DEFAULT 'documentation',
  namespace_key varchar(255) NOT NULL,
  channel_id bigint NOT NULL DEFAULT 1,
  collection_key varchar(64) NOT NULL DEFAULT 'active',
  orchestrator_actor_id bigint DEFAULT NULL,
  facet_slug varchar(64) DEFAULT NULL,
  agent_key varchar(64) DEFAULT NULL,
  role_key varchar(64) DEFAULT NULL,
  task_scope varchar(255) DEFAULT NULL,
  database_table varchar(255) DEFAULT NULL,
  runtime_min_php varchar(20) DEFAULT '5.6',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  properties_json json DEFAULT NULL,
  PRIMARY KEY (documentation_framework_id)
);
```

## Purpose
Explain the intended function of the table based on its structure and naming.

## Columns
| Column | Type | Null | Key | Default | Description |
|--------|------|------|-----|---------|-------------|
| documentation_framework_id | bigint | NO |  | NULL | |
| framework_key | varchar(64) | NO |  | NULL | |
| framework_name | varchar(255) | NO |  | NULL | |
| class_type | varchar(64) | NO |  | documentation | |
| namespace_key | varchar(255) | NO |  | NULL | |
| channel_id | bigint | NO |  | 1 | |
| collection_key | varchar(64) | NO |  | active | |
| orchestrator_actor_id | bigint | YES |  | NULL | |
| facet_slug | varchar(64) | YES |  | NULL | |
| agent_key | varchar(64) | YES |  | NULL | |
| role_key | varchar(64) | YES |  | NULL | |
| task_scope | varchar(255) | YES |  | NULL | |
| database_table | varchar(255) | YES |  | NULL | |
| runtime_min_php | varchar(20) | YES |  | 5.6 | |
| created_ymdhis | bigint | NO |  | 0 | |
| updated_ymdhis | bigint | NO |  | 0 | |
| is_deleted | tinyint | NO |  | 0 | |
| deleted_ymdhis | bigint | YES |  | NULL | |
| properties_json | json | YES |  | NULL | |


## Notes
- This table is not yet installed in the production schema.
- It represents a future Lupopedia capability.
