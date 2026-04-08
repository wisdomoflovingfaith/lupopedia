---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260401000000"
  file_path_from_root: "lupo-docs/prd/19_garbage_collection_system.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/19_garbage_collection_system.md"
  last_modified_utc: "20260401000000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-garbage-collection"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "specification"
  purpose: "Garbage Collection System for unified table architecture — paths, referrers, visits, campaigns"
  tags:
  - "prd"
  - "gc"
  - "garbage-collection"
  - "paths"
  - "referrers"
  - "visits"
  - "unified-tables"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/prd/11_analytics_tracking.md"
      type: references
      weight: 1.0
      reason: "Analytics tables that GC aggregates"
    - to: "lupo-docs/versions/4.0.93/gc.php"
      type: implements
      weight: 1.0
      reason: "Legacy GC pattern reference"
    - to: "lupo-database/lupopedia/json/lupo_paths.json"
      type: references
      weight: 1.0
      reason: "Paths table schema reference (unified)"
    - to: "lupo-database/lupopedia/json/lupo_referers_daily.json"
      type: references
      weight: 1.0
      reason: "Referrers daily table schema reference (unified)"
lupopedia.footer:
  last_verified: "20260401000000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
  next_action:
    - "Implement GarbageCollector class for unified tables"
    - "Create GC script with random execution pattern"
    - "Add retention configuration to system_config"
    - "Test path aggregation with real visit data"
---

# PRD: Garbage Collection System (The GC) — Unified Table Architecture

## Overview

The Garbage Collection System handles all background data aggregation, pruning, and maintenance tasks for Lupopedia. Inspired by the 2003 `gc.php` pattern that kept Crafty Syntax running on 1.2M installations, this system uses:

- **Randomized execution** — spreads load across requests
- **Self-limiting runs** — prevents table locks
- **Unified tables** — single table per entity with date columns for aggregation
- **Configurable retention** — per-table retention policies
- **Soft delete awareness** — respects `is_deleted` flags

## Constitutional Compliance

All GC operations follow Lupopedia constitutional rules:
- NO foreign keys — all relationships handled in application logic
- NO triggers — GC is explicit application-layer maintenance
- BIGINT timestamps — all time comparisons use YYYYMMDDHHIISS
- Soft delete — GC respects `is_deleted` flags, never hard deletes without archiving

---

## Unified Table Architecture

### The Pattern

Instead of separate `_daily`, `_monthly`, `_yearly` tables, use a **single table** with:

- `date_ymd` column (YYYYMMDD) for day-level granularity
- `year_num`, `month_num`, `day_num` columns for aggregation (optional, can be derived)
- `count_num` column that gets incremented

This allows queries to aggregate by any period using SQL date functions on `date_ymd`.

### Unified Tables in Lupopedia

| Table | Purpose | Granularity | Retention |
|-------|---------|-------------|-----------|
| `lupo_paths` | Navigation paths | Per path, with year/month/day | 90 days |
| `lupo_referers` | Referrer tracking | Per referrer per day | 365 days |
| `lupo_visits` | Raw visits | Per event | 30 days (then aggregated) |
| `lupo_visits_daily` | Daily visit aggregates | Per day | 365 days |
| `lupo_analytics_campaign_vars` | Campaign tracking | Per campaign per day | 365 days |
| `lupo_sessions` | Session tracking | Per session | 24 hours after expiration |
| `lupo_actor_memory` | Actor learning | Per memory entry | Configurable (30 days default) |

### Why Unified Tables Work

| Problem | Old Approach | Unified Approach |
|---------|--------------|------------------|
| **Daily aggregates** | Separate table | Same table, filter by date_ymd |
| **Monthly aggregates** | Another table | SQL: `GROUP BY YEAR(date_ymd), MONTH(date_ymd)` |
| **Schema complexity** | 3 tables per entity | 1 table per entity |
| **GC complexity** | Clean 3 tables | Clean 1 table |
| **Query complexity** | UNION across tables | Simple WHERE clause |

---

## Tables Maintained by GC

### 1. `lupo_paths` — Navigation Paths

```sql
CREATE TABLE lupo_paths (
  path_id bigint NOT NULL,
  entercontentid bigint,
  exitcontentid bigint,
  enter_table varchar(255),
  exit_table varchar(255),
  year_num int,
  month_num int,
  day_num int,
  count_num int NOT NULL DEFAULT 0,
  transition_type varchar(64),
  transition_metadata text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint
);
```

**GC Operations:**
- Aggregate raw visits into paths (increment `count_num`)
- Prune paths older than retention period
- Update `year_num`, `month_num`, `day_num` from timestamps

### 2. `lupo_referers` — Referrer Tracking (Unified)

```sql
CREATE TABLE lupo_referers (
  referer_id bigint NOT NULL,
  referer_domain varchar(255),               -- Domain of referrer (e.g., google.com)
  referer_url varchar(2000),                 -- Full referrer URL
  target_content_id bigint,                 -- Which page/content was visited (FK to lupo_contents)
  target_path_url varchar(500),              -- Fallback path if content_id unknown
  date_ymd int NOT NULL,                    -- YYYYMMDD
  visits int NOT NULL DEFAULT 0,            -- Number of visits from this referrer to this page
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint,
  PRIMARY KEY (referer_id),
  UNIQUE KEY lupo_referers_unq (referer_domain, target_content_id, date_ymd),
  KEY lupo_referers_idx_target (target_content_id),
  KEY lupo_referers_idx_date (date_ymd),
  KEY lupo_referers_idx_domain (referer_domain)
);
```

**GC Operations:**
- Aggregate raw referrers from `lupo_visits` with target content tracking
- Update existing rows (increment `visits`)
- Prune rows older than retention period
- **No separate monthly table** — queries can group by month: `SELECT YEAR(date_ymd), MONTH(date_ymd), SUM(visits)` 
- **Content-specific referrer tracking** — know which pages get traffic from which referrers 

### 3. `lupo_visits` — Raw Visits

```sql
CREATE TABLE lupo_visits (
  visit_id bigint NOT NULL,
  session_id bigint,
  actor_id bigint,
  path_url text,
  referer text,
  created_ymdhis bigint NOT NULL,
  is_processed tinyint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint
);
```

**GC Operations:**
- Mark as processed after aggregation
- Prune raw visits older than retention period (30 days)

### 4. `lupo_visits_daily` — Daily Visit Aggregates (Per Content)

```sql
CREATE TABLE lupo_visits_daily (
  visits_daily_id bigint NOT NULL,
  content_id bigint,                              -- Which page/content was visited
  path_url varchar(500),                          -- Fallback if content_id not available
  visit_date date NOT NULL,                       -- YYYY-MM-DD
  total_visits int DEFAULT 0,                     -- Total visits to this page
  unique_sessions int DEFAULT 0,                  -- Unique sessions that visited
  unique_visitors int DEFAULT 0,                  -- Unique visitors (actors)
  avg_duration_seconds int DEFAULT 0,             -- Average time on page
  bounce_count int DEFAULT 0,                     -- Exits where this was the only page
  entry_count int DEFAULT 0,                      -- Times this was the entry page
  exit_count int DEFAULT 0,                       -- Times this was the exit page
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint,
  PRIMARY KEY (visits_daily_id),
  UNIQUE KEY idx_content_date (content_id, visit_date),
  KEY idx_path_date (path_url(100), visit_date),
  KEY idx_date (visit_date)
);
```

**GC Operations:**
- Aggregate from `lupo_visits` grouped by content and date
- Track per-page metrics: visits, bounces, entries, exits
- Calculate unique sessions and visitors per content
- Prune rows older than retention period (365 days)

### 5. `lupo_analytics_campaign_vars` — Campaign Tracking

```sql
CREATE TABLE lupo_analytics_campaign_vars (
  campaign_var_id bigint NOT NULL,
  period varchar(64) NOT NULL,
  date_ymd bigint,
  yearmonth int,
  year int,
  campaign_key varchar(255) NOT NULL,
  campaign_value varchar(500),
  metadata_json json,
  created_ymdhis bigint NOT NULL DEFAULT 0
);
```

**GC Operations:**
- Aggregate campaign parameters from `lupo_visits.path_url` 
- Prune campaigns older than retention period

### 6. `lupo_sessions` — Session Tracking

```sql
CREATE TABLE lupo_sessions (
  session_id varchar(64) NOT NULL,
  actor_id bigint NOT NULL,
  auth_user_id bigint,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  expires_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint
);
```

**GC Operations:**
- Soft delete expired sessions
- Prune sessions older than retention period

### 7. `lupo_actor_memory` — Actor Learning

```sql
CREATE TABLE lupo_actor_memory (
  memory_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  memory_type varchar(64) NOT NULL,
  memory_key varchar(128) NOT NULL,
  memory_value text,
  expires_ymdhis bigint,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint
);
```

**GC Operations:**
- Soft delete expired memory entries
- Prune memory older than retention period

---

## GC Functions

### 1. Path Aggregation (`aggregate_paths`)

**Purpose:** Aggregate raw visit sequences into `lupo_paths` with counts.

**Source:** `lupo_visits` (raw page views)

**Target:** `lupo_paths` 

**Logic:**
```sql
-- Find unaggregated visits with enter/exit
SELECT v1.visit_id, v1.path_url as enter, v2.path_url as exit, 
       v1.created_ymdhis, v1.session_id
FROM lupo_visits v1
JOIN lupo_visits v2 ON v1.session_id = v2.session_id 
  AND v1.created_ymdhis < v2.created_ymdhis
  AND v1.is_processed = 0
WHERE NOT EXISTS (
    SELECT 1 FROM lupo_visits v3 
    WHERE v1.session_id = v3.session_id 
      AND v1.created_ymdhis < v3.created_ymdhis 
      AND v3.created_ymdhis < v2.created_ymdhis
)
LIMIT 5000;

-- For each path, update or insert into lupo_paths
INSERT INTO lupo_paths 
    (entercontentid, exitcontentid, year_num, month_num, day_num, 
     transition_type, count_num, created_ymdhis, updated_ymdhis)
VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?)
ON DUPLICATE KEY UPDATE 
    count_num = count_num + 1,
    updated_ymdhis = ?;
```

### 2. Path Pruning (`prune_paths`)

**Purpose:** Remove old path data based on retention policy.

**Retention:** Keep last 90 days

**Logic:**
```sql
UPDATE lupo_paths 
SET is_deleted = 1, deleted_ymdhis = ?
WHERE created_ymdhis < ? 
  AND is_deleted = 0
LIMIT 10000;
```

### 3. Referrer Aggregation (`aggregate_referrers`)

**Purpose:** Aggregate raw referrer data into unified `lupo_referers` table with target tracking.

**Source:** `lupo_visits.referer` 

**Target:** `lupo_referers` 

**Logic:**
```sql
-- Extract domain and target from referrer URLs
SELECT 
    SUBSTRING_INDEX(SUBSTRING_INDEX(referer, '/', 3), '://', -1) as domain,
    referer as full_url,
    COALESCE(v.content_id, 0) as target_content_id,
    v.path_url as target_path_url,
    DATE(v.created_ymdhis) as visit_date,
    COUNT(*) as cnt
FROM lupo_visits v
WHERE v.referer IS NOT NULL
  AND v.referer != ''
  AND v.is_processed = 0
  AND v.is_deleted = 0
GROUP BY domain, full_url, COALESCE(v.content_id, 0), v.path_url, DATE(v.created_ymdhis)
LIMIT 5000;

-- Update unified table
INSERT INTO lupo_referers 
    (referer_domain, referer_url, target_content_id, target_path_url, date_ymd, visits, created_ymdhis, updated_ymdhis)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE 
    visits = visits + VALUES(visits),
    updated_ymdhis = ?;
```

### 4. Referrer Pruning (`prune_referrers`)

**Purpose:** Remove old referrer data based on retention policy.

**Retention:** Keep last 365 days

**Logic:**
```sql
UPDATE lupo_referers 
SET is_deleted = 1, deleted_ymdhis = ?
WHERE date_ymd < ? 
  AND is_deleted = 0
LIMIT 10000;
```

### 5. Daily Visit Aggregation (`aggregate_daily_visits`)

**Purpose:** Aggregate raw visits into `lupo_visits_daily` per content.

**Source:** `lupo_visits` 

**Target:** `lupo_visits_daily` 

**Logic:**
```sql
-- Aggregate by content and date
SELECT 
    COALESCE(v.content_id, 0) as content_id,
    v.path_url,
    DATE(v.created_ymdhis) as visit_date,
    COUNT(*) as total_visits,
    COUNT(DISTINCT v.session_id) as unique_sessions,
    COUNT(DISTINCT v.actor_id) as unique_visitors,
    AVG(v.duration_seconds) as avg_duration_seconds,
    SUM(CASE WHEN v.is_bounce = 1 THEN 1 ELSE 0 END) as bounce_count,
    SUM(CASE WHEN v.is_entry_page = 1 THEN 1 ELSE 0 END) as entry_count,
    SUM(CASE WHEN v.is_exit_page = 1 THEN 1 ELSE 0 END) as exit_count
FROM lupo_visits v
WHERE v.is_processed = 1
  AND v.is_deleted = 0
GROUP BY COALESCE(v.content_id, 0), v.path_url, DATE(v.created_ymdhis)
LIMIT 1000;

-- Insert/update daily table
INSERT INTO lupo_visits_daily 
    (content_id, path_url, visit_date, total_visits, unique_sessions, 
     unique_visitors, avg_duration_seconds, bounce_count, entry_count, exit_count,
     created_ymdhis, updated_ymdhis)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE 
    total_visits = total_visits + VALUES(total_visits),
    unique_sessions = unique_sessions + VALUES(unique_sessions),
    unique_visitors = unique_visitors + VALUES(unique_visitors),
    avg_duration_seconds = VALUES(avg_duration_seconds),
    bounce_count = bounce_count + VALUES(bounce_count),
    entry_count = entry_count + VALUES(entry_count),
    exit_count = exit_count + VALUES(exit_count),
    updated_ymdhis = VALUES(updated_ymdhis);
```

### 6. Campaign Aggregation (`aggregate_campaigns`)

**Purpose:** Aggregate campaign analytics from URL parameters.

**Source:** `lupo_visits.path_url` 

**Target:** `lupo_analytics_campaign_vars` 

**Logic:**
```sql
-- Extract utm parameters from URLs
SELECT 
    DATE(created_ymdhis) as date_ymd,
    'utm_source' as campaign_key,
    SUBSTRING_INDEX(SUBSTRING_INDEX(path_url, 'utm_source=', -1), '&', 1) as campaign_value,
    COUNT(*) as cnt
FROM lupo_visits
WHERE path_url LIKE '%utm_source=%'
  AND is_processed = 0
GROUP BY date_ymd, campaign_key, campaign_value
LIMIT 5000;

-- Insert/update campaign table
INSERT INTO lupo_analytics_campaign_vars 
    (period, date_ymd, campaign_key, campaign_value, metadata_json, created_ymdhis)
VALUES (?, ?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE 
    metadata_json = JSON_SET(metadata_json, '$.count', 
        COALESCE(JSON_EXTRACT(metadata_json, '$.count'), 0) + ?);
```

### 7. Session Cleanup (`cleanup_sessions`)

**Purpose:** Remove expired sessions.

**Source:** `lupo_sessions` 

**Logic:**
```sql
UPDATE lupo_sessions 
SET is_deleted = 1, deleted_ymdhis = ?
WHERE expires_ymdhis < ? 
  AND is_deleted = 0
LIMIT 10000;
```

### 8. Actor Memory Pruning (`prune_actor_memory`)

**Purpose:** Remove expired actor memory.

**Source:** `lupo_actor_memory` 

**Logic:**
```sql
UPDATE lupo_actor_memory 
SET is_deleted = 1, deleted_ymdhis = ?
WHERE expires_ymdhis IS NOT NULL 
  AND expires_ymdhis < ?
  AND is_deleted = 0
LIMIT 10000;
```

### 9. Raw Visit Pruning (`prune_raw_visits`)

**Purpose:** Remove raw visits after they've been aggregated.

**Retention:** Keep last 30 days

**Logic:**
```sql
UPDATE lupo_visits 
SET is_deleted = 1, deleted_ymdhis = ?
WHERE created_ymdhis < ? 
  AND is_deleted = 0
LIMIT 10000;
```

### 10. Table Optimization (`optimize_tables`)

**Purpose:** Run OPTIMIZE TABLE after deletions to reclaim space.

**Logic:**
```sql
OPTIMIZE TABLE lupo_paths;
OPTIMIZE TABLE lupo_referers;
OPTIMIZE TABLE lupo_visits;
OPTIMIZE TABLE lupo_visits_daily;
OPTIMIZE TABLE lupo_sessions;
OPTIMIZE TABLE lupo_actor_memory;
```

---

## Execution Pattern (The 2003 Magic)

### Random Execution

```php
// Called on every request (from image.php, livehelp_js.php, etc.)
function maybe_run_gc() {
    // 1% chance per request
    if (rand(1, 100) != 7) {
        return;
    }
    
    $gc = new GarbageCollector();
    $gc->run();
}
```

### Self-Limiting

```php
class GarbageCollector {
    const MAX_PER_RUN = 10000;
    private $deleted = 0;
    
    public function prune($table, $condition, $params = []) {
        if ($this->deleted >= self::MAX_PER_RUN) {
            return;  // Stop if we've done enough
        }
        
        $remaining = self::MAX_PER_RUN - $this->deleted;
        $sql = "UPDATE $table SET is_deleted = 1, deleted_ymdhis = ? 
                WHERE $condition AND is_deleted = 0 
                LIMIT $remaining";
        
        $params = array_merge([gmdate('YmdHis')], $params);
        $affected = $this->db->execute($sql, $params);
        $this->deleted += $affected;
    }
}
```

### Multi-Tier Aggregation Flow

```
lupo_visits (raw, 30-day retention)
        │
        ├── aggregate_paths() → lupo_paths (90-day retention)
        │
        ├── aggregate_referrers() → lupo_referers (365-day retention)
        │
        └── aggregate_daily_visits() → lupo_visits_daily (365-day retention)
                │
                └── (future: monthly aggregates via SQL queries)
```

---

## Configuration Options

Store in `lupo_system_config`:

| Key | Default | Description |
|-----|---------|-------------|
| `gc_path_retention_days` | 90 | How long to keep path data |
| `gc_referrer_retention_days` | 365 | How long to keep referrer data |
| `gc_visits_retention_days` | 30 | How long to keep raw visits |
| `gc_daily_visits_retention_days` | 365 | How long to keep daily aggregates |
| `gc_session_retention_hours` | 24 | How long to keep expired sessions |
| `gc_memory_retention_days` | 30 | How long to keep actor memory |
| `gc_campaign_retention_days` | 365 | How long to keep campaign data |
| `gc_max_per_run` | 10000 | Max rows to delete per run |
| `gc_execution_chance` | 1 | Percent chance per request (1-100) |

---

## Implementation: GarbageCollector Class

```php
<?php
// lupo-includes/classes/GarbageCollector.php

class GarbageCollector {
    private $db;
    private $deleted = 0;
    private $config;
    private $max_per_run;
    
    const DEFAULT_MAX_PER_RUN = 10000;
    
    public function __construct() {
        $this->db = DatabaseFactory::getConnection();
        $this->loadConfig();
        $this->max_per_run = $this->config['gc_max_per_run'] ?? self::DEFAULT_MAX_PER_RUN;
    }
    
    /**
     * Main GC entry point — runs all functions
     */
    public function run() {
        $this->aggregatePaths();
        $this->prunePaths();
        
        $this->aggregateReferrers();
        $this->pruneReferrers();
        
        $this->aggregateDailyVisits();
        $this->pruneRawVisits();
        $this->pruneDailyVisits();
        
        $this->aggregateCampaigns();
        $this->pruneCampaigns();
        
        $this->cleanupSessions();
        $this->pruneActorMemory();
        
        $this->optimizeTables();
        $this->logRun();
    }
    
    /**
     * Aggregate raw visits into lupo_paths
     */
    private function aggregatePaths() {
        // Find sequential visits from same session (no intermediate pages)
        $sql = "SELECT v1.visit_id, v1.path_url as enter, v2.path_url as exit, 
                       v1.created_ymdhis, v1.session_id
                FROM lupo_visits v1
                JOIN lupo_visits v2 ON v1.session_id = v2.session_id 
                  AND v1.created_ymdhis < v2.created_ymdhis
                  AND v1.is_processed = 0
                WHERE NOT EXISTS (
                    SELECT 1 FROM lupo_visits v3 
                    WHERE v1.session_id = v3.session_id 
                      AND v1.created_ymdhis < v3.created_ymdhis 
                      AND v3.created_ymdhis < v2.created_ymdhis
                )
                LIMIT 5000";
        
        $visits = $this->db->query($sql);
        
        foreach ($visits as $visit) {
            $year = substr($visit['created_ymdhis'], 0, 4);
            $month = substr($visit['created_ymdhis'], 4, 2);
            $day = substr($visit['created_ymdhis'], 6, 2);
            
            $enter_id = $this->getContentIdFromUrl($visit['enter']);
            $exit_id = $this->getContentIdFromUrl($visit['exit']);
            
            $sql = "INSERT INTO lupo_paths 
                    (entercontentid, exitcontentid, year_num, month_num, day_num, 
                     count_num, created_ymdhis, updated_ymdhis)
                    VALUES (?, ?, ?, ?, ?, 1, ?, ?)
                    ON DUPLICATE KEY UPDATE 
                    count_num = count_num + 1,
                    updated_ymdhis = ?";
            
            $this->db->execute($sql, [
                $enter_id, $exit_id,
                $year, $month, $day,
                $visit['created_ymdhis'],
                $visit['created_ymdhis'],
                $visit['created_ymdhis']
            ]);
            
            // Mark source visits as processed
            $sql = "UPDATE lupo_visits SET is_processed = 1 WHERE visit_id = ?";
            $this->db->execute($sql, [$visit['visit_id']]);
        }
    }
    
    /**
     * Aggregate referrers into unified lupo_referers table
     */
    private function aggregateReferrers() {
        $sql = "SELECT 
                    SUBSTRING_INDEX(SUBSTRING_INDEX(referer, '/', 3), '://', -1) as domain,
                    DATE(created_ymdhis) as visit_date,
                    COUNT(*) as cnt
                FROM lupo_visits
                WHERE referer IS NOT NULL
                  AND referer != ''
                  AND is_processed = 0
                  AND is_deleted = 0
                GROUP BY domain, DATE(created_ymdhis)
                LIMIT 5000";
        
        $referrers = $this->db->query($sql);
        
        foreach ($referrers as $ref) {
            $date_ymd = str_replace('-', '', $ref['visit_date']);
            
            $sql = "INSERT INTO lupo_referers 
                    (referer_domain, date_ymd, visits, created_ymdhis, updated_ymdhis)
                    VALUES (?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE 
                    visits = visits + VALUES(visits),
                    updated_ymdhis = ?";
            
            $this->db->execute($sql, [
                $ref['domain'],
                $date_ymd,
                $ref['cnt'],
                gmdate('YmdHis'),
                gmdate('YmdHis'),
                gmdate('YmdHis')
            ]);
        }
    }
    
    /**
     * Aggregate daily visit counts
     */
    private function aggregateDailyVisits() {
        $sql = "SELECT 
                    DATE(created_ymdhis) as visit_date,
                    COUNT(*) as total_visits,
                    COUNT(DISTINCT session_id) as unique_sessions
                FROM lupo_visits
                WHERE is_processed = 1
                  AND is_deleted = 0
                GROUP BY DATE(created_ymdhis)
                LIMIT 1000";
        
        $daily = $this->db->query($sql);
        
        foreach ($daily as $day) {
            $sql = "INSERT INTO lupo_visits_daily 
                    (visit_date, total_visits, unique_sessions, created_ymdhis, updated_ymdhis)
                    VALUES (?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE 
                    total_visits = total_visits + VALUES(total_visits),
                    unique_sessions = unique_sessions + VALUES(unique_sessions),
                    updated_ymdhis = ?";
            
            $this->db->execute($sql, [
                $day['visit_date'],
                $day['total_visits'],
                $day['unique_sessions'],
                gmdate('YmdHis'),
                gmdate('YmdHis'),
                gmdate('YmdHis')
            ]);
        }
    }
    
    /**
     * Prune old paths
     */
    private function prunePaths() {
        if ($this->deleted >= $this->max_per_run) return;
        
        $retention = $this->config['gc_path_retention_days'] ?? 90;
        $cutoff = gmdate('YmdHis', strtotime("-$retention days"));
        
        $this->prune('lupo_paths', 'created_ymdhis < ?', [$cutoff]);
    }
    
    /**
     * Prune old referrers
     */
    private function pruneReferrers() {
        if ($this->deleted >= $this->max_per_run) return;
        
        $retention = $this->config['gc_referrer_retention_days'] ?? 365;
        $cutoff = strtotime("-$retention days");
        $cutoff_ymd = date('Ymd', $cutoff);
        
        $this->prune('lupo_referers', 'date_ymd < ?', [$cutoff_ymd]);
    }
    
    /**
     * Prune raw visits
     */
    private function pruneRawVisits() {
        if ($this->deleted >= $this->max_per_run) return;
        
        $retention = $this->config['gc_visits_retention_days'] ?? 30;
        $cutoff = gmdate('YmdHis', strtotime("-$retention days"));
        
        $this->prune('lupo_visits', 'created_ymdhis < ? AND is_processed = 1', [$cutoff]);
    }
    
    /**
     * Prune daily visits
     */
    private function pruneDailyVisits() {
        if ($this->deleted >= $this->max_per_run) return;
        
        $retention = $this->config['gc_daily_visits_retention_days'] ?? 365;
        $cutoff = date('Y-m-d', strtotime("-$retention days"));
        
        $this->prune('lupo_visits_daily', 'visit_date < ?', [$cutoff]);
    }
    
    /**
     * Prune campaigns
     */
    private function pruneCampaigns() {
        if ($this->deleted >= $this->max_per_run) return;
        
        $retention = $this->config['gc_campaign_retention_days'] ?? 365;
        $cutoff = gmdate('YmdHis', strtotime("-$retention days"));
        
        $this->prune('lupo_analytics_campaign_vars', 'created_ymdhis < ?', [$cutoff]);
    }
    
    /**
     * Clean up expired sessions
     */
    private function cleanupSessions() {
        if ($this->deleted >= $this->max_per_run) return;
        
        $retention = $this->config['gc_session_retention_hours'] ?? 24;
        $cutoff = gmdate('YmdHis', strtotime("-$retention hours"));
        
        $this->prune('lupo_sessions', 'expires_ymdhis < ?', [$cutoff]);
    }
    
    /**
     * Prune expired actor memory
     */
    private function pruneActorMemory() {
        if ($this->deleted >= $this->max_per_run) return;
        
        $retention = $this->config['gc_memory_retention_days'] ?? 30;
        $cutoff = gmdate('YmdHis', strtotime("-$retention days"));
        
        $this->prune('lupo_actor_memory', 'expires_ymdhis IS NOT NULL AND expires_ymdhis < ?', [$cutoff]);
    }
    
    /**
     * Generic prune function with self-limiting
     */
    private function prune($table, $condition, $params = []) {
        $remaining = $this->max_per_run - $this->deleted;
        if ($remaining <= 0) return;
        
        $sql = "UPDATE $table 
                SET is_deleted = 1, deleted_ymdhis = ? 
                WHERE $condition AND is_deleted = 0 
                LIMIT $remaining";
        
        $all_params = array_merge([gmdate('YmdHis')], $params);
        $this->db->execute($sql, $all_params);
        $this->deleted += $this->db->affected_rows();
    }
    
    /**
     * Aggregate campaign data from URLs
     */
    private function aggregateCampaigns() {
        // Extract utm_source from URLs
        $sql = "SELECT 
                    DATE(created_ymdhis) as date_ymd,
                    'utm_source' as campaign_key,
                    SUBSTRING_INDEX(SUBSTRING_INDEX(path_url, 'utm_source=', -1), '&', 1) as campaign_value,
                    COUNT(*) as cnt
                FROM lupo_visits
                WHERE path_url LIKE '%utm_source=%'
                  AND is_processed = 0
                GROUP BY date_ymd, campaign_key, campaign_value
                LIMIT 5000";
        
        $campaigns = $this->db->query($sql);
        
        foreach ($campaigns as $camp) {
            $date_ymd = strtotime($camp['date_ymd']);
            $yearmonth = date('Ym', $date_ymd);
            $year = date('Y', $date_ymd);
            
            $sql = "INSERT INTO lupo_analytics_campaign_vars 
                    (period, date_ymd, yearmonth, year, campaign_key, campaign_value, 
                     metadata_json, created_ymdhis)
                    VALUES ('day', ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE 
                    metadata_json = JSON_SET(metadata_json, '$.count', 
                        COALESCE(JSON_EXTRACT(metadata_json, '$.count'), 0) + ?)";
            
            $metadata = json_encode(['count' => $camp['cnt']]);
            
            $this->db->execute($sql, [
                $camp['date_ymd'],
                $yearmonth,
                $year,
                $camp['campaign_key'],
                $camp['campaign_value'],
                $metadata,
                gmdate('YmdHis'),
                $camp['cnt']
            ]);
        }
    }
    
    /**
     * Optimize tables after deletions
     */
    private function optimizeTables() {
        if ($this->deleted == 0) return;
        
        $tables = ['lupo_paths', 'lupo_referers', 'lupo_visits', 
                   'lupo_visits_daily', 'lupo_sessions', 'lupo_actor_memory'];
        
        foreach ($tables as $table) {
            $this->db->query("OPTIMIZE TABLE $table");
        }
    }
    
    /**
     * Get content ID from URL path
     */
    private function getContentIdFromUrl($url) {
        // Extract slug from URL and lookup in lupo_contents
        // Simplified for now — returns 0 if not found
        return 0;
    }
    
    /**
     * Load configuration from system_config
     */
    private function loadConfig() {
        $sql = "SELECT config_key, config_value FROM lupo_system_config 
                WHERE config_key LIKE 'gc_%'";
        $rows = $this->db->query($sql);
        
        foreach ($rows as $row) {
            $this->config[$row['config_key']] = $row['config_value'];
        }
    }
    
    /**
     * Log GC run
     */
    private function logRun() {
        $sql = "INSERT INTO lupo_unified_log 
                (log_type, log_level, log_message, created_ymdhis)
                VALUES ('gc', 'info', ?, ?)";
        
        $message = sprintf("GC run completed: %d rows deleted", $this->deleted);
        $this->db->execute($sql, [$message, gmdate('YmdHis')]);
    }
    
    /**
     * Static method to trigger GC (called from requests)
     */
    public static function maybeRun() {
        $config = self::getGCConfig();
        $chance = $config['gc_execution_chance'] ?? 1;
        
        if (rand(1, 100) <= $chance) {
            $gc = new self();
            $gc->run();
        }
    }
    
    private static function getGCConfig() {
        // Load from database or return defaults
        return [
            'gc_execution_chance' => 1,
            'gc_path_retention_days' => 90,
            'gc_referrer_retention_days' => 365,
            'gc_visits_retention_days' => 30,
            'gc_daily_visits_retention_days' => 365,
            'gc_session_retention_hours' => 24,
            'gc_memory_retention_days' => 30,
            'gc_campaign_retention_days' => 365,
        ];
    }
}
```


---

## Context‑Typed, Status‑Aware, Directional Edged Memory Doctrine (4.0.96)

1. Memory in Lupopedia is represented as a directed graph of nodes and edges. 
  Each memory node is a first-class entity in the semantic network and may be 
  owned by actors, departments, auth_users, channels, federation nodes, or the 
  global system.

2. Every edge in the memory graph has FOUR dimensions:
  - **edge type** (the relationship)
  - **edge context** (the classification of the memory)
  - **edge status** (the epistemic support level)
  - **edge direction** (the traversal orientation)

3. **Edge Direction** defines whether the relationship is:
  - unidirectional (A → B)
  - bidirectional (A ↔ B)
  - restricted-direction (A → B but not B → A unless explicitly defined)
  Reverse traversal MUST NOT be inferred unless explicitly defined.

4. **Edge Type** defines the relationship between nodes, including but not 
  limited to:
  - influences
  - inherits
  - authored_by
  - observed_by
  - contradicts
  - supports
  - consolidates_from
  - refines
  - overrides

5. **Edge Context** defines the classification of the memory node. Context is 
  not based on the content of the memory, but on the structural support 
  provided by the graph. The primary context classifications are:
  - doctrine
  - experiential
  - system_generated
  - countermeasure_generated
  - summary
  - contradictory
  - deprecated

6. **Edge Status** defines the epistemic support level of the memory node:
  - **unsupported**: insufficient supporting edges; provisional memory.
  - **supported**: sufficient supporting edges; validated memory.
  - **needs_review**: conflicting, incomplete, or ambiguous edges requiring 
    agent or human intervention.

7. When `edge_status = 'needs_review'`, a **review_reason** MUST be provided. 
  This field explains *why* the edge requires review and *which agent* should 
  handle it. Examples include:
  - orphaned_edge
  - contradiction
  - new_doctrine
  - schema_drift
  - consolidation_candidate
  - integrity_unknown
  - human_escalation

  Agents use this field to determine their work queues:
  - ANUBIS handles: integrity_unknown, orphaned_edge
  - THOTH handles: schema_drift, contradiction, new_doctrine
  - KAIROS handles: consolidation_candidate
  - Human operator handles: human_escalation

8. Memory nodes may transition between statuses as edges are added, removed, 
  or reclassified. A node may move from unsupported → supported when 
  sufficient supporting edges accumulate.

9. Actors inherit memory edges from:
  - their department
  - their auth_user
  - their federation node
  - their assigned faucets
  - their assigned tasks

10. Memory traversal is context-aware and direction-aware. Actors may only 
   traverse edges permitted by their boundaries, department rules, auth_user 
   pairing, faucet assignments, and operational mode (live, simulation, 
   analysis).

11. No inference is allowed. All edges, contexts, statuses, directions, and 
   review reasons must be explicitly defined in PRDs, database rows, or 
   system-generated memory.

12. Memory is not a flat file. It is a structured, typed, classified, 
   status-aware, direction-aware graph. Traversal depth determines visible 
   memory; deeper traversal reveals more context, subject to boundary rules.

13. All changes to memory structure, edge types, edge contexts, edge statuses, 
   edge directions, or review reasons must be documented in PRDs and versioned.
```
