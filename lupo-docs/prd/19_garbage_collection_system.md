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
  purpose: "Garbage Collection System for path aggregation, referrer tracking, campaign analytics, and data retention"
  tags:
  - "prd"
  - "gc"
  - "garbage-collection"
  - "paths"
  - "referrers"
  - "campaigns"
  - "retention"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/11_analytics_tracking.md"
      type: references
      weight: 1.0
      reason: "Analytics tables that GC aggregates"
    - to: "lupo-docs/versions/4.0.93/gc.php"
      type: implements
      weight: 1.0
      reason: "Legacy GC pattern reference"
    - to: "lupo-database/lupopedia/toon/lupo_paths.toon"
      type: references
      weight: 1.0
      reason: "Paths table schema"
lupopedia.footer:
  last_verified: "20260401000000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
  next_action:
    - "Implement GarbageCollector class"
    - "Create GC script with random execution pattern"
    - "Add retention configuration to system_config"
    - "Test path aggregation with real visit data"
---

# PRD: Garbage Collection System (The GC)

## Overview

The Garbage Collection System handles all background data aggregation, pruning, and maintenance tasks for Lupopedia. Inspired by the 2003 `gc.php` pattern that kept Crafty Syntax running on 1.2M installations, this system uses:

- **Randomized execution** — spreads load across requests
- **Self-limiting runs** — prevents table locks
- **Multi-tier aggregation** — raw → daily → monthly → yearly
- **Configurable retention** — per-table retention policies
- **Soft delete awareness** — respects `is_deleted` flags

## Constitutional Compliance

All GC operations follow Lupopedia constitutional rules:
- NO foreign keys — all relationships handled in application logic
- NO triggers — GC is explicit application-layer maintenance
- BIGINT timestamps — all time comparisons use YYYYMMDDHHIISS
- Soft delete — GC respects `is_deleted` flags, never hard deletes without archiving

---

## Tables Maintained by GC

| Table | Purpose | GC Operations |
|-------|---------|---------------|
| `lupo_paths` | Aggregated navigation paths | Update counts, prune old paths |
| `lupo_referers_daily` | Referrer counts by day | Aggregate raw referrers, prune old |
| `lupo_visits_daily` | Daily visit counts | Aggregate from `lupo_visits` |
| `lupo_analytics_campaign_vars` | Campaign analytics | Update counts, prune old |
| `lupo_sessions` | Session cleanup | Remove expired sessions |
| `lupo_visits` | Raw visit events | Archive to daily aggregates, prune old |
| `lupo_actor_memory` | Actor learning | Prune expired memory (if `expires_ymdhis` set) |

---

## GC Functions

### 1. Path Aggregation (`aggregate_paths`)

**Purpose:** Aggregate raw visit sequences into `lupo_paths` with counts.

**Source:** `lupo_visits` (raw page views)

**Target:** `lupo_paths` 

**Logic:**
```sql
-- For each unique (entercontentid, exitcontentid, transition_type)
-- Increment count_num in lupo_paths
-- Update year_num, month_num, day_num from visit timestamps
```

**Frequency:** Every GC run (random chance)

### 2. Path Pruning (`prune_paths`)

**Purpose:** Remove old path data based on retention policy.

**Retention:** Keep last N days (default: 90)

**Logic:**
```sql
-- Soft delete paths older than retention period
UPDATE lupo_paths 
SET is_deleted = 1, deleted_ymdhis = ?
WHERE created_ymdhis < ? 
  AND is_deleted = 0
LIMIT 10000;  -- Self-limiting
```

### 3. Referrer Aggregation (`aggregate_referrers`)

**Purpose:** Aggregate raw referrer data into daily counts.

**Source:** `lupo_visits.referer` 

**Target:** `lupo_referers_daily` 

**Logic:**
```sql
-- Count referrers per day
INSERT INTO lupo_referers_daily (referer_domain, date_ymd, visits)
SELECT referer_domain, DATE(created_ymdhis), COUNT(*)
FROM lupo_visits
WHERE referer_domain IS NOT NULL
  AND is_deleted = 0
GROUP BY referer_domain, DATE(created_ymdhis)
ON DUPLICATE KEY UPDATE visits = visits + VALUES(visits);
```

### 4. Campaign Aggregation (`aggregate_campaigns`)

**Purpose:** Aggregate campaign analytics from URL parameters.

**Source:** `lupo_visits.path_url` (extract campaign parameters)

**Target:** `lupo_analytics_campaign_vars` 

**Logic:**
```sql
-- Extract utm_source, utm_medium, utm_campaign from URLs
-- Count occurrences per campaign
```

### 5. Session Cleanup (`cleanup_sessions`)

**Purpose:** Remove expired sessions.

**Source:** `lupo_sessions` 

**Logic:**
```sql
-- Soft delete sessions past expiration
UPDATE lupo_sessions 
SET is_deleted = 1, deleted_ymdhis = ?
WHERE expires_ymdhis < ? 
  AND is_deleted = 0
LIMIT 10000;
```

### 6. Actor Memory Pruning (`prune_actor_memory`)

**Purpose:** Remove expired actor memory.

**Source:** `lupo_actor_memory` 

**Logic:**
```sql
-- Soft delete memory with expiration
UPDATE lupo_actor_memory 
SET is_deleted = 1, deleted_ymdhis = ?
WHERE expires_ymdhis IS NOT NULL 
  AND expires_ymdhis < ?
  AND is_deleted = 0
LIMIT 10000;
```

### 7. Table Optimization (`optimize_tables`)

**Purpose:** Run OPTIMIZE TABLE after deletions to reclaim space.

**Source:** Tables that had deletions

**Logic:**
```sql
OPTIMIZE TABLE lupo_paths;
OPTIMIZE TABLE lupo_referers_daily;
OPTIMIZE TABLE lupo_sessions;
-- etc.
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
    
    public function prune($table, $condition) {
        if ($this->deleted >= self::MAX_PER_RUN) {
            return;  // Stop if we've done enough
        }
        
        $remaining = self::MAX_PER_RUN - $this->deleted;
        $sql = "DELETE FROM $table WHERE $condition LIMIT $remaining";
        $affected = $this->db->execute($sql);
        $this->deleted += $affected;
    }
}
```

### Multi-Tier Aggregation

```
Raw visits (lupo_visits)
        │
        ▼ (aggregate daily)
lupo_visits_daily
        │
        ▼ (aggregate monthly)
lupo_visits_monthly (optional)
```

---

## Configuration Options

Store in `lupo_system_config`:

| Key | Default | Description |
|-----|---------|-------------|
| `gc_path_retention_days` | 90 | How long to keep path data |
| `gc_referrer_retention_days` | 365 | How long to keep referrer data |
| `gc_session_retention_hours` | 24 | How long to keep expired sessions |
| `gc_memory_retention_days` | 30 | How long to keep actor memory |
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
     * Main GC entry point
     */
    public function run() {
        $this->aggregatePaths();
        $this->prunePaths();
        $this->aggregateReferrers();
        $this->pruneReferrers();
        $this->cleanupSessions();
        $this->pruneActorMemory();
        $this->optimizeTables();
        
        $this->logRun();
    }
    
    /**
     * Aggregate raw visits into lupo_paths
     */
    private function aggregatePaths() {
        // Find unaggregated visits (where is_processed = 0)
        $sql = "SELECT visit_id, entercontentid, exitcontentid, transition_type, created_ymdhis
                FROM lupo_visits
                WHERE is_processed = 0
                  AND is_deleted = 0
                LIMIT 5000";
        $visits = $this->db->query($sql);
        
        foreach ($visits as $visit) {
            $year = substr($visit['created_ymdhis'], 0, 4);
            $month = substr($visit['created_ymdhis'], 4, 2);
            $day = substr($visit['created_ymdhis'], 6, 2);
            
            $sql = "INSERT INTO lupo_paths 
                    (entercontentid, exitcontentid, year_num, month_num, day_num, 
                     transition_type, count_num, created_ymdhis, updated_ymdhis)
                    VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?)
                    ON DUPLICATE KEY UPDATE 
                    count_num = count_num + 1,
                    updated_ymdhis = ?";
            
            $this->db->execute($sql, [
                $visit['entercontentid'],
                $visit['exitcontentid'],
                $year, $month, $day,
                $visit['transition_type'],
                $visit['created_ymdhis'],
                $visit['created_ymdhis'],
                $visit['created_ymdhis']
            ]);
            
            // Mark as processed
            $sql = "UPDATE lupo_visits SET is_processed = 1 WHERE visit_id = ?";
            $this->db->execute($sql, [$visit['visit_id']]);
        }
    }
    
    /**
     * Prune old path data
     */
    private function prunePaths() {
        if ($this->deleted >= $this->max_per_run) return;
        
        $retention_days = $this->config['gc_path_retention_days'] ?? 90;
        $cutoff = gmdate('YmdHis', strtotime("-$retention_days days"));
        $remaining = $this->max_per_run - $this->deleted;
        
        $sql = "UPDATE lupo_paths 
                SET is_deleted = 1, deleted_ymdhis = ?
                WHERE created_ymdhis < ?
                  AND is_deleted = 0
                LIMIT ?";
        
        $this->db->execute($sql, [gmdate('YmdHis'), $cutoff, $remaining]);
        $this->deleted += $this->db->affected_rows();
    }
    
    /**
     * Aggregate referrer data
     */
    private function aggregateReferrers() {
        $sql = "SELECT referer_domain, DATE(created_ymdhis) as visit_date, COUNT(*) as cnt
                FROM lupo_visits
                WHERE referer_domain IS NOT NULL
                  AND is_processed = 0
                  AND is_deleted = 0
                GROUP BY referer_domain, DATE(created_ymdhis)
                LIMIT 5000";
        
        $referrers = $this->db->query($sql);
        
        foreach ($referrers as $ref) {
            $sql = "INSERT INTO lupo_referers_daily (referer_domain, date_ymd, visits, created_ymdhis)
                    VALUES (?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE 
                    visits = visits + VALUES(visits),
                    updated_ymdhis = ?";
            
            $this->db->execute($sql, [
                $ref['referer_domain'],
                $ref['visit_date'],
                $ref['cnt'],
                gmdate('YmdHis'),
                gmdate('YmdHis')
            ]);
        }
    }
    
    /**
     * Prune old referrer data
     */
    private function pruneReferrers() {
        if ($this->deleted >= $this->max_per_run) return;
        
        $retention_days = $this->config['gc_referrer_retention_days'] ?? 365;
        $cutoff = gmdate('Ymd', strtotime("-$retention_days days"));
        $remaining = $this->max_per_run - $this->deleted;
        
        $sql = "UPDATE lupo_referers_daily 
                SET is_deleted = 1, deleted_ymdhis = ?
                WHERE date_ymd < ?
                  AND is_deleted = 0
                LIMIT ?";
        
        $this->db->execute($sql, [gmdate('YmdHis'), $cutoff, $remaining]);
        $this->deleted += $this->db->affected_rows();
    }
    
    /**
     * Clean up expired sessions
     */
    private function cleanupSessions() {
        if ($this->deleted >= $this->max_per_run) return;
        
        $retention_hours = $this->config['gc_session_retention_hours'] ?? 24;
        $cutoff = gmdate('YmdHis', strtotime("-$retention_hours hours"));
        $remaining = $this->max_per_run - $this->deleted;
        
        $sql = "UPDATE lupo_sessions 
                SET is_deleted = 1, deleted_ymdhis = ?
                WHERE expires_ymdhis < ?
                  AND is_deleted = 0
                LIMIT ?";
        
        $this->db->execute($sql, [gmdate('YmdHis'), $cutoff, $remaining]);
        $this->deleted += $this->db->affected_rows();
    }
    
    /**
     * Prune expired actor memory
     */
    private function pruneActorMemory() {
        if ($this->deleted >= $this->max_per_run) return;
        
        $retention_days = $this->config['gc_memory_retention_days'] ?? 30;
        $cutoff = gmdate('YmdHis', strtotime("-$retention_days days"));
        $remaining = $this->max_per_run - $this->deleted;
        
        $sql = "UPDATE lupo_actor_memory 
                SET is_deleted = 1, deleted_ymdhis = ?
                WHERE expires_ymdhis IS NOT NULL
                  AND expires_ymdhis < ?
                  AND is_deleted = 0
                LIMIT ?";
        
        $this->db->execute($sql, [gmdate('YmdHis'), $cutoff, $remaining]);
        $this->deleted += $this->db->affected_rows();
    }
    
    /**
     * Optimize tables after deletions
     */
    private function optimizeTables() {
        if ($this->deleted == 0) return;
        
        $tables = ['lupo_paths', 'lupo_referers_daily', 'lupo_sessions', 'lupo_visits'];
        
        foreach ($tables as $table) {
            $this->db->query("OPTIMIZE TABLE $table");
        }
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
            'gc_session_retention_hours' => 24,
            'gc_memory_retention_days' => 30,
        ];
    }
}
```

---

## Integration Points

### In `image.php` (Legacy) or `lupo_ajax.php` (Modern)

```php
// At the end of request processing
require_once('lupo-includes/classes/GarbageCollector.php');
GarbageCollector::maybeRun();
```

### In `gc.php` (Standalone script for CLI/cron)

```php
#!/usr/bin/env php
<?php
// lupo-scripts/gc.php - Standalone GC runner
require_once('lupo-config.php');
require_once('lupo-includes/classes/GarbageCollector.php');

$gc = new GarbageCollector();
$gc->run();

echo "GC run completed\n";
```

---

## Testing Requirements

| Test | Description |
|------|-------------|
| **Random execution** | Verify GC runs with configured probability |
| **Self-limiting** | Verify GC stops after `max_per_run` deletions |
| **Path aggregation** | Verify raw visits correctly aggregated to `lupo_paths` |
| **Referrer aggregation** | Verify referrers aggregated to daily counts |
| **Retention policies** | Verify old data correctly soft-deleted |
| **Table optimization** | Verify OPTIMIZE runs after deletions |
| **Soft delete** | Verify `is_deleted` flag set, not hard delete |

---

## Legacy Crafty Syntax Reference

The original `gc.php` (2003) pattern:

```php
// Random cleaning of OLD daily data
$randomNumber = rand(1, 501);
if($randomNumber == 7){
    // ... clean up
}

// Random archiving of visitor sessions
$randomNumber = rand(1, 12);
if($randomNumber == 4){
    // ... archive sessions
}
```

This pattern is preserved in the new GC:
- **Random execution** prevents server load spikes
- **Self-limiting** prevents long-running queries
- **Multi-tier aggregation** preserves history while keeping current data fast

---

**Status**: DRAFT
**Constitutional Adherence**: FULL
**Next Review**: After implementation
