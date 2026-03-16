---
lupopedia.headers:
  lupopedia.version: "4.0.78"
  lupopedia.schema: "database_table"
  system_version: "4.0.78"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_analytics_visits.md"
  web_path: "[lupo_analytics_visits](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_analytics_visits)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "analytics"
  purpose: "Documentation for lupo_analytics_visits table - analytics visit and session activity for traffic and usage tracking"
  traits: ["canonical", "analytics", "visits", "v4.0.78"]
  tags: ["database", "analytics", "visits", "traffic"]
  table_primary_key: "analytics_visit_id"
  doctrine_note: "No database foreign keys; schema source: existing table documentation. This table is not in install_new_lupopedia.sql as of 4.0.78; for visit tracking in the current install see lupo_visits."

lupopedia.edges:
  comment: "Snapshot of edges for lupo_analytics_visits table doc at 4.0.78."
  outbound_edges:
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_visits.md", type: "references", weight: 0.9, reason: "Visit tracking in current install" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_contents.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "references", weight: 0.7 }

lupopedia.footer:
  version: "4.0.78"
  last_verified: "20260316"
  last_verified_by: "cursor"
---
# file: lupo_analytics_visits — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_analytics_visits

# Table: lupo_analytics_visits

## Schema source note

**This table is not present in `install_new_lupopedia.sql` as of 4.0.78.** The schema below is from existing table documentation (and TOON-derived content where applicable). For visit/session tracking in the current install schema, see **lupo_visits**. When lupo_analytics_visits is added to install or future_features SQL, this doc should be aligned to that DDL.

## Table Overview

- **Purpose:** Records analytics visit and session activity for traffic and usage tracking. Supports realtime and aggregated (daily, monthly, period) visit records, referrer/path/campaign analysis, and audience behavior metrics. Used for dashboards, reporting rollups, and analytics pipelines.
- **Category:** Analytics / Telemetry
- **Status:** Documented; not in current install SQL (see schema source note above).
- **Relationship to lupo_visits:** install_new_lupopedia.sql defines **lupo_visits** (visit_id, session_id, actor_id, path_url, enter/exit content, etc.) for visit tracking; lupo_analytics_visits represents a richer analytics-specific design (referrer, period buckets, view/visit counts) when present.

## Where This Table Is Used

- **Visit and session analytics:** Per-session and per-actor visit records for realtime and period-based reporting (visit_type: realtime, pageview, etc.).
- **Traffic analysis and dashboards:** Aggregations by date_ymd, date_ym, period_date, and department_id for traffic summaries and dashboards.
- **Referrer, path, and campaign analysis:** referer_url, referer_domain, referer_path, came_from support referrer and campaign attribution.
- **Audience behavior measurement:** unique_sessions, unique_actors, direct_visits, internal_visits, entry_count, exit_count, seconds_active/total_seconds/avg_seconds for engagement and retention metrics.
- **Reporting rollups:** period_type and period_date support daily, monthly, or custom period rollups; view_count, visits, and related counters feed reporting pipelines.
- **Connection to analytics pipelines:** Raw or summarized visit storage (depending on visit_type and period_type) for ETL, reporting jobs, and retention/cohort analysis.
- **Relationship to channels, actors, sessions:** actor_id and session_id tie visits to actors and sessions; department_id and federation_node_id provide channel/federation scope when the table is in use.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| analytics_visit_id | bigint | No | — | Primary key. |
| visit_type | varchar(20) | No | 'realtime' | Visit bucket (e.g. realtime, pageview). |
| session_id | varchar(100) | No | — | Session identity. |
| actor_id | bigint | No | 0 | Actor reference. |
| content_id | bigint | Yes | — | Content reference. |
| federation_node_id | bigint | No | — | Federation scope. |
| url_path | varchar(500) | No | '' | URL path. |
| referer_url | varchar(500) | Yes | — | Referrer URL. |
| referer_domain | varchar(255) | Yes | — | Referrer domain. |
| referer_path | varchar(500) | Yes | — | Referrer path. |
| came_from | varchar(500) | Yes | — | Campaign/source tag. |
| department_id | bigint | No | 1 | Department scope. |
| period_type | varchar(64) | Yes | — | Period label (e.g. daily, monthly). |
| period_date | bigint | Yes | — | Period date bucket. |
| date_ymd | bigint | Yes | — | YYYYMMDD bucket. |
| date_ym | bigint | Yes | — | YYYYMM bucket. |
| first_seen_ymdhis | bigint | No | — | First seen timestamp (BIGINT UTC). |
| last_seen_ymdhis | bigint | No | — | Last seen timestamp (BIGINT UTC). |
| view_count | int | No | 1 | View count. |
| visits | int | No | 0 | Visit count. |
| unique_sessions | int | No | 0 | Unique sessions in aggregate. |
| unique_actors | int | No | 0 | Unique actors in aggregate. |
| direct_visits | int | No | 0 | Direct hits. |
| internal_visits | int | No | 0 | Internal hits. |
| entry_count | int | No | 0 | Entry count. |
| exit_count | int | No | 0 | Exit count. |
| seconds_active | int | No | 0 | Active seconds. |
| total_seconds | int | No | 0 | Total seconds. |
| avg_seconds | int | No | 0 | Average seconds. |
| user_agent | varchar(255) | Yes | — | User agent. |
| ip_address | varchar(45) | Yes | — | IP address. |
| created_ymdhis | bigint | No | 0 | Created timestamp (BIGINT UTC). |
| updated_ymdhis | bigint | No | — | Updated timestamp (BIGINT UTC). |
| is_deleted | tinyint | No | 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Yes | 0 | Soft delete timestamp. |
| archived_ymdhis | bigint | Yes | 0 | Archive timestamp. |

## Indexes

- **PRIMARY KEY:** analytics_visit_id
- **Indexes (from existing documentation):** lupo_analytics_visits_idx_actor, lupo_analytics_visits_idx_content, lupo_analytics_visits_idx_created, lupo_analytics_visits_idx_date_ym, lupo_analytics_visits_idx_date_ymd, lupo_analytics_visits_idx_department, lupo_analytics_visits_idx_period_date, lupo_analytics_visits_idx_session, lupo_analytics_visits_idx_updated, lupo_analytics_visits_idx_visit_type; unique indexes lupo_analytics_visits_uq_daily, lupo_analytics_visits_uq_monthly, lupo_analytics_visits_uq_period, lupo_analytics_visits_uq_realtime. Confirm against DDL when table is added to install/future_features.

## Relationships

- **Logical references (no DB FKs):** actor_id → lupo_actors.actor_id; content_id → lupo_contents.content_id; department_id → department/channel scope; federation_node_id → federation node; session_id is application-defined session identity. All integrity in application code.

## Doctrine Notes

- **No foreign keys.** All referential integrity in application code.
- **Timestamps:** created_ymdhis, updated_ymdhis, first_seen_ymdhis, last_seen_ymdhis, period_date, date_ymd, date_ym, deleted_ymdhis, archived_ymdhis are BIGINT UTC; set in PHP only.
- **Soft delete:** Filter by is_deleted = 0 unless querying deleted or archived rows.
- **Schema source:** Table not in install_new_lupopedia.sql as of 4.0.78; columns and indexes above from existing documentation. Align to install or future_features DDL when available.
