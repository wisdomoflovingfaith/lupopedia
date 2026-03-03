# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_analytics_visits.md"
  file_hash: "35a496c3489fac0bc609d233814e3101f0647297345846d6b40cdbd463849ebb"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_analytics_visits.md"
  system_version: "4.0.49"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "table_documentation"
  purpose: "Analytics visit tracking for content and sessions"
  dialog_message: "DBDOC batch 2: enriched documentation and optimization notes."
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "database", "curated"]
  tags: ["database", "table", "lupo_analytics_visits"]
  lupo_agent: "codex-ide"
  lupo_analytics_visits.analytics_visit_id: "bigint NOT NULL"
  lupo_analytics_visits.visit_type: "varchar(20) NOT NULL DEFAULT 'realtime'"
  lupo_analytics_visits.session_id: "varchar(100) NOT NULL"
  lupo_analytics_visits.actor_id: "bigint NOT NULL DEFAULT 0"
  lupo_analytics_visits.content_id: "bigint"
  lupo_analytics_visits.federation_node_id: "bigint NOT NULL"
  lupo_analytics_visits.url_path: "varchar(500) NOT NULL DEFAULT ''"
  lupo_analytics_visits.referer_url: "varchar(500)"
  lupo_analytics_visits.referer_domain: "varchar(255)"
  lupo_analytics_visits.referer_path: "varchar(500)"
  lupo_analytics_visits.came_from: "varchar(500)"
  lupo_analytics_visits.department_id: "bigint NOT NULL DEFAULT 1"
  lupo_analytics_visits.period_type: "varchar(64)"
  lupo_analytics_visits.period_date: "bigint"
  lupo_analytics_visits.date_ymd: "bigint"
  lupo_analytics_visits.date_ym: "bigint"
  lupo_analytics_visits.first_seen_ymdhis: "bigint NOT NULL"
  lupo_analytics_visits.last_seen_ymdhis: "bigint NOT NULL"
  lupo_analytics_visits.view_count: "int NOT NULL DEFAULT 1"
  lupo_analytics_visits.visits: "int NOT NULL DEFAULT 0"
  lupo_analytics_visits.unique_sessions: "int NOT NULL DEFAULT 0"
  lupo_analytics_visits.unique_actors: "int NOT NULL DEFAULT 0"
  lupo_analytics_visits.direct_visits: "int NOT NULL DEFAULT 0"
  lupo_analytics_visits.internal_visits: "int NOT NULL DEFAULT 0"
  lupo_analytics_visits.entry_count: "int NOT NULL DEFAULT 0"
  lupo_analytics_visits.exit_count: "int NOT NULL DEFAULT 0"
  lupo_analytics_visits.seconds_active: "int NOT NULL DEFAULT 0"
  lupo_analytics_visits.total_seconds: "int NOT NULL DEFAULT 0"
  lupo_analytics_visits.avg_seconds: "int NOT NULL DEFAULT 0"
  lupo_analytics_visits.user_agent: "varchar(255)"
  lupo_analytics_visits.ip_address: "varchar(45)"
  lupo_analytics_visits.created_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_analytics_visits.updated_ymdhis: "bigint NOT NULL"
  lupo_analytics_visits.is_deleted: "tinyint NOT NULL DEFAULT 0"
  lupo_analytics_visits.deleted_ymdhis: "bigint DEFAULT 0"
  lupo_analytics_visits.archived_ymdhis: "bigint DEFAULT 0"
  table_primary_key: "analytics_visit_id"
  table_engine: "unknown"
  table_charset: "unknown"
  table_collation: "unknown"
  table_indexes: ["lupo_analytics_visits_idx_actor", "lupo_analytics_visits_idx_content", "lupo_analytics_visits_idx_created", "lupo_analytics_visits_idx_date_ym", "lupo_analytics_visits_idx_date_ymd", "lupo_analytics_visits_idx_department", "lupo_analytics_visits_idx_period_date", "lupo_analytics_visits_idx_session", "lupo_analytics_visits_idx_updated", "lupo_analytics_visits_idx_visit_type", "lupo_analytics_visits_uq_daily", "lupo_analytics_visits_uq_monthly", "lupo_analytics_visits_uq_period", "lupo_analytics_visits_uq_realtime"]
  table_foreign_keys: []

# FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml

flare.footer:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_analytics_visits.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_analytics_visits" }
    - { to: "docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.7, reason: "content analytics" }
    - { to: "docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.6, reason: "department/channel scope" }
  inbound_edges: []
  semantic_tags: ["database", "table", "analytics"]
  version: "4.0.49"
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# Table: lupo_analytics_visits

Purpose: Tracks analytics visits and session activity for content and channels.
Type: database_table
Status: production_ready
Volume: high

## 1. Overview
- Key responsibilities: capture per-visit metrics and aggregation flags.
- System role: feeds analytics dashboards and reporting.
- Importance: core telemetry for content and operator insights.

## 2. Schema Reference
Primary Key: analytics_visit_id
Field Categories: identity, session, metrics, aggregation, lifecycle.

### All Fields
| Column | Type | Notes |
|---|---|---|
| analytics_visit_id | bigint NOT NULL | Primary key. |
| visit_type | varchar(20) NOT NULL DEFAULT 'realtime' | Visit bucket. |
| session_id | varchar(100) NOT NULL | Session identity. |
| actor_id | bigint NOT NULL DEFAULT 0 | Actor reference. |
| content_id | bigint | Content reference. |
| federation_node_id | bigint NOT NULL | Federation scope. |
| url_path | varchar(500) NOT NULL DEFAULT '' | URL path. |
| referer_url | varchar(500) | Referrer URL. |
| referer_domain | varchar(255) | Referrer domain. |
| referer_path | varchar(500) | Referrer path. |
| came_from | varchar(500) | Campaign tag. |
| department_id | bigint NOT NULL DEFAULT 1 | Department scope. |
| period_type | varchar(64) | Period label. |
| period_date | bigint | Period date bucket. |
| date_ymd | bigint | YYYYMMDD bucket. |
| date_ym | bigint | YYYYMM bucket. |
| first_seen_ymdhis | bigint NOT NULL | First seen timestamp. |
| last_seen_ymdhis | bigint NOT NULL | Last seen timestamp. |
| view_count | int NOT NULL DEFAULT 1 | Views. |
| visits | int NOT NULL DEFAULT 0 | Visit count. |
| unique_sessions | int NOT NULL DEFAULT 0 | Unique sessions. |
| unique_actors | int NOT NULL DEFAULT 0 | Unique actors. |
| direct_visits | int NOT NULL DEFAULT 0 | Direct hits. |
| internal_visits | int NOT NULL DEFAULT 0 | Internal hits. |
| entry_count | int NOT NULL DEFAULT 0 | Entry count. |
| exit_count | int NOT NULL DEFAULT 0 | Exit count. |
| seconds_active | int NOT NULL DEFAULT 0 | Active seconds. |
| total_seconds | int NOT NULL DEFAULT 0 | Total seconds. |
| avg_seconds | int NOT NULL DEFAULT 0 | Average seconds. |
| user_agent | varchar(255) | User agent. |
| ip_address | varchar(45) | IP address. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | Created timestamp. |
| updated_ymdhis | bigint NOT NULL | Updated timestamp. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT 0 | Soft delete timestamp. |
| archived_ymdhis | bigint DEFAULT 0 | Archive timestamp. |

## 3. Relationships and Dependencies
- Primary relationships: content_id, department_id, federation_node_id.
- Referencing tables: analytics summaries, reporting.
- Integration points: reporting dashboards and retention jobs.

## 4. Indexes and Performance
Primary Indexes:
- analytics_visit_id
Performance Indexes:
- lupo_analytics_visits_idx_session
- lupo_analytics_visits_uq_realtime
- lupo_analytics_visits_uq_daily
- lupo_analytics_visits_uq_monthly
Index Strategy: optimize for session and period-based lookups.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_analytics_visits WHERE session_id = :sid AND visit_type = 'realtime';
SELECT * FROM lupo_analytics_visits WHERE content_id = :content AND date_ymd = :ymd AND visit_type = 'pageview';
SELECT COUNT(*) AS total FROM lupo_analytics_visits WHERE date_ymd = :ymd AND is_deleted = 0;
UPDATE lupo_analytics_visits SET updated_ymdhis = :ts WHERE analytics_visit_id = :id;
```
Best Practices: batch inserts and update aggregate counts rather than re-deriving.
Anti-Patterns: full scans without date buckets.

## 6. Performance Considerations
- High-volume operations: continuous inserts and updates.
- Optimization tips: add composite index on (federation_node_id, date_ymd) for federation reports.
- Scaling considerations: consider partitioning by date_ymd in high-volume environments.

## 7. Data Integrity
- Constraints: session_id required, first_seen/last_seen required.
- Validation rules: enforce visit_type values.
- Soft delete: archive before delete.

## 8. Common Issues and Solutions
- Slow reports: use date_ymd/date_ym buckets and indexes.
- Hot partitions: rotate archival and purge by archived_ymdhis.
- Data drift: ensure federation_node_id aligns with content node.

## 9. Future Enhancements
- Add summary rollups for weekly periods.
- Add composite index for (department_id, date_ymd).
