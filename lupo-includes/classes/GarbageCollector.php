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
