---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  system_version: "4.0.73"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/planning/table_lupo_emotional_constellations.toon.md"
  last_modified_utc: "20260313"
  channel_id: 42
  actor_id: 1003
  actor_name: "antigravity"
  artifact_type: "database_schema"
  artifact_kind: "planning"
  purpose: "Planned Lupopedia database table: lupo_emotional_constellations"
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

# Planned Table: `lupo_emotional_constellations`

## Status
PLANNING — not yet implemented.

## Source
Defined in: `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`

## SQL Definition

```sql
CREATE TABLE lupo_emotional_constellations (
constellation_id char(26) NOT NULL,
  framework_name varchar(255) NOT NULL,
  cultural_origin varchar(255) DEFAULT NULL,
  description text,
  stars json NOT NULL,
  is_canonical tinyint NOT NULL DEFAULT '0',
  canonical_for_culture varchar(255) DEFAULT NULL,
  created_ymdhis bigint DEFAULT NULL,
  deprecated_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (constellation_id)
);
```

## Purpose
Explain the intended function of the table based on its structure and naming.

## Columns
| Column | Type | Null | Key | Default | Description |
|--------|------|------|-----|---------|-------------|
| constellation_id | char(26) | NO |  | NULL | |
| framework_name | varchar(255) | NO |  | NULL | |
| cultural_origin | varchar(255) | YES |  | NULL | |
| description | text | YES |  | NULL | |
| stars | json | NO |  | NULL | |
| is_canonical | tinyint | NO |  | 0 | |
| canonical_for_culture | varchar(255) | YES |  | NULL | |
| created_ymdhis | bigint | YES |  | NULL | |
| deprecated_ymdhis | bigint | YES |  | NULL | |


## Notes
- This table is not yet installed in the production schema.
- It represents a future Lupopedia capability.
