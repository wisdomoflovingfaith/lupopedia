---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/development/lupo_analytics_campaign_vars.md"
  web_path: "[lupo_analytics_campaign_vars](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_analytics_campaign_vars)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "analytics"
  purpose: "Documentation for lupo_analytics_campaign_vars table - stores per-period campaign key/value analytics data"
  tags: ["database", "table", "analytics"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_analytics_campaign_vars table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=1 python_hits=1"
  outbound_edges:
    - { to: "database.table.lupo_analytics_campaign_vars", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-includes/schema-config.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-scripts/wolfie_orms.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_analytics_campaign_vars ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_analytics_campaign_vars
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
