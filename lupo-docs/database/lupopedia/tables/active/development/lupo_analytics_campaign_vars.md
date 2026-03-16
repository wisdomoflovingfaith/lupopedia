---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/development/lupo_analytics_campaign_vars.md"
  system_version: "4.0.77"
  namespace: "analytics"
  channel_id: 42
  actor_id: 106
  last_modified_utc: "20260316"
  artifact_type: "table_documentation"
  purpose: "Documentation for lupo_analytics_campaign_vars table - stores per-period campaign key/value analytics data"
  mood_rgb: "4169E1"
  artifact_kind: "table"
  traits: ["development", "analytics", "campaigns", "v4.0.77"]
  tags: ["database", "analytics", "campaigns", "development"]
  lupo_agent: "zencoder"
  table_primary_key: "campaign_var_id"
  lupo_analytics_campaign_vars.campaign_var_id: "BIGINT NOT NULL primary key"
  lupo_analytics_campaign_vars.period: "VARCHAR(64) NOT NULL — time period label (e.g. 'daily', 'monthly', '2026-03')"
  lupo_analytics_campaign_vars.date_ymd: "BIGINT — date in YYYYMMDD format for day-level aggregations"
  lupo_analytics_campaign_vars.yearmonth: "INT — year-month composite (e.g. 202603) for monthly rollups"
  lupo_analytics_campaign_vars.year: "INT — calendar year for yearly aggregations"
  lupo_analytics_campaign_vars.campaign_key: "VARCHAR(255) NOT NULL — UTM or campaign parameter name"
  lupo_analytics_campaign_vars.campaign_value: "VARCHAR(500) — campaign parameter value"
  lupo_analytics_campaign_vars.metadata_json: "JSON — extended attributes for this campaign data point"
  lupo_analytics_campaign_vars.created_ymdhis: "BIGINT NOT NULL DEFAULT 0 — YYYYMMDDHHIISS UTC creation timestamp"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_indexes: []
  doctrine_note: "No database foreign keys; referential integrity enforced in application code."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_analytics_visits.md", type: "references", weight: 0.9 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 0.9 }

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "zencoder"
---

# Table: lupo_analytics_campaign_vars

## Table Overview

- **Purpose:** Stores campaign parameter analytics data broken down by time period. Records UTM/campaign `key=value` pairs alongside the period, date, year-month, and year dimensions. Enables campaign performance reporting across multiple time granularities.
- **Category:** Analytics / Campaign Tracking
- **Status:** Development (not yet in canonical install SQL; defined in `development/` TOON only)
- **Version introduced:** 4.0.x (development)

## Where This Table Is Used

- **Campaign analytics reporting:** Queried by analytics services to retrieve campaign variable breakdowns for a given period (daily, monthly, yearly), enabling attribution and ROI reporting for traffic sources.
- **UTM parameter aggregation:** Ingests raw UTM parameters (source, medium, campaign, term, content) extracted from visitor sessions and aggregates them per period in this table.
- **Analytics pipeline:** Works in conjunction with `lupo_analytics_visits`, `lupo_analytics_visits_daily`, and `lupo_analytics_visits_monthly` to give a full picture of campaign-driven traffic.
- **Admin reporting dashboards:** The admin section may query this table to display campaign performance summaries grouped by `period` and filtered by `date_ymd` or `yearmonth`.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| campaign_var_id | bigint | No | — | Primary key. Explicit BIGINT; not AUTO_INCREMENT per reserved-ID doctrine. |
| period | varchar(64) | No | — | Time-period granularity label: `daily`, `monthly`, `yearly`, or a specific period string. |
| date_ymd | bigint | Yes | — | Date in YYYYMMDD format (e.g. `20260316`). Used for day-level lookups. |
| yearmonth | int | Yes | — | Year-month composite (e.g. `202603`). Used for monthly aggregation queries. |
| year | int | Yes | — | Calendar year (e.g. `2026`). Used for yearly roll-ups. |
| campaign_key | varchar(255) | No | — | Campaign parameter key, e.g. `utm_source`, `utm_medium`, `utm_campaign`. |
| campaign_value | varchar(500) | Yes | — | Campaign parameter value corresponding to `campaign_key`. |
| metadata_json | json | Yes | — | Optional structured attributes (e.g. channel_id, federation_node_id, extra dimensions). |
| created_ymdhis | bigint | No | `0` | Creation timestamp in YYYYMMDDHHIISS UTC format. |

## Indexes

This table has no performance indexes defined in the development TOON. When promoted to the install schema, add indexes on `(period, date_ymd)`, `(campaign_key)`, and `(yearmonth)` to support common reporting queries.

## Usage Patterns

### Fetch campaign data for a monthly period
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$rows = $db->fetchAll(
    "SELECT campaign_key, campaign_value, date_ymd
     FROM {$prefix}analytics_campaign_vars
     WHERE period = 'monthly' AND yearmonth = :ym
     ORDER BY campaign_key ASC",
    ['ym' => 202603]
);
```

### Insert a campaign data point
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$now = (int) gmdate('YmdHis');
$db->query(
    "INSERT INTO {$prefix}analytics_campaign_vars
     (campaign_var_id, period, date_ymd, yearmonth, year, campaign_key, campaign_value, created_ymdhis)
     VALUES (:id, :period, :date_ymd, :ym, :year, :key, :val, :ts)",
    ['id' => $newId, 'period' => 'daily', 'date_ymd' => 20260316,
     'ym' => 202603, 'year' => 2026, 'key' => 'utm_source', 'val' => 'google', 'ts' => $now]
);
```

## Doctrine Notes

- **No foreign keys.** No DB-level FK constraints; application code enforces referential integrity.
- **Timestamps:** `created_ymdhis` is BIGINT UTC YYYYMMDDHHIISS; set via `gmdate('YmdHis')` in PHP.
- **No AUTO_INCREMENT.** `campaign_var_id` must be assigned explicitly by application code per reserved-ID doctrine.
- **Status:** Development table; not present in `install_new_lupopedia.sql` as of 4.0.77. Schema defined in `lupo-docs/database/lupopedia/tables/active/development/lupo_analytics_campaign_vars.toon`.
