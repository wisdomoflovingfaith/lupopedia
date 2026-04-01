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
