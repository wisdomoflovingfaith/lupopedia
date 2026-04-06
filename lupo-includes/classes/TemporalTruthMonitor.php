<?php

/**
 * Temporal Truth Monitor
 *
 * Monitors temporal drift and suggests RS-UTC-2026 casting when needed.
 * All persisted / compared clocks use packed UTC BIGINT (YYYYMMDDHHIISS) via
 * {@see timestamp_ymdhis} — no Unix epoch in drift math (PRD 00 §3.5).
 *
 * @package Lupopedia
 * @version 2026.04.05
 * @author Captain Wolfie
 * @spell RS-UTC-2026
 */

if (!class_exists('timestamp_ymdhis')) {
    $tsPath = __DIR__ . DIRECTORY_SEPARATOR . 'TimestampYmdhis.php';
    if (is_file($tsPath)) {
        require_once $tsPath;
    }
}

class TemporalTruthMonitor
{
    private $db;
    private $current_timestamp;
    private $drift_thresholds = [
        'warning' => 300,      // 5 minutes
        'critical' => 900,     // 15 minutes
        'emergency' => 1800    // 30 minutes
    ];
    
    public function __construct($database_connection)
    {
        $this->db = $database_connection;
        $this->current_timestamp = class_exists('timestamp_ymdhis')
            ? (string) timestamp_ymdhis::now()
            : gmdate('YmdHis');
    }
    
    /**
     * Check current temporal alignment status
     */
    public function checkTemporalAlignment()
    {
        $status = [
            'current_utc' => $this->current_timestamp,
            'last_sync' => $this->getLastSyncTimestamp(),
            'drift_seconds' => 0,
            'drift_status' => 'aligned',
            'recommendation' => 'no_action_needed',
            'sync_confidence' => 1.0,
            'temporal_truth_status' => 'ESTABLISHED'
        ];
        
        // Calculate drift from last sync
        if ($status['last_sync']) {
            $status['drift_seconds'] = $this->calculateDrift($status['last_sync']);
            $status['drift_status'] = $this->getDriftStatus($status['drift_seconds']);
            $status['recommendation'] = $this->getRecommendation($status['drift_status']);
            $status['sync_confidence'] = $this->calculateConfidence($status['drift_seconds']);
            $status['temporal_truth_status'] = $this->getTemporalTruthStatus($status['drift_status']);
        } else {
            $status['recommendation'] = 'cast_initial_sync';
            $status['sync_confidence'] = 0.0;
            $status['temporal_truth_status'] = 'UNKNOWN';
        }
        
        return $status;
    }
    
    /**
     * Packed UTC instant of last RS-UTC-2026 sync (utc_timestamp column = YYYYMMDDHHIISS, not Unix epoch).
     */
    private function getLastSyncTimestamp()
    {
        if (!$this->db) {
            return null;
        }
        
        $sql = "
            SELECT utc_timestamp 
            FROM lupo_reverse_shaka_syncs 
            ORDER BY created_ymdhis DESC 
            LIMIT 1
        ";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                return (int) $row['utc_timestamp'];
            }
            
        } catch (Exception $e) {
            error_log("Failed to get last sync timestamp: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Drift in seconds: now (packed) minus last sync (packed), via timestamp_ymdhis.
     * No time(), no strtotime(), no Unix persistence.
     *
     * @param int|string $last_sync_timestamp Packed UTC from utc_timestamp column
     * @return int Signed seconds appropriate for threshold checks (non-negative for normal drift)
     */
    private function calculateDrift($last_sync_timestamp)
    {
        if (!class_exists('timestamp_ymdhis')) {
            return 0;
        }
        $last = (int) $last_sync_timestamp;
        if ($last <= 0) {
            return 0;
        }
        $now = timestamp_ymdhis::now();

        return timestamp_ymdhis::diffInSeconds($now, $last);
    }

    /**
     * Get drift status based on thresholds
     */
    private function getDriftStatus($drift_seconds)
    {
        if ($drift_seconds <= $this->drift_thresholds['warning']) {
            return 'aligned';
        } elseif ($drift_seconds <= $this->drift_thresholds['critical']) {
            return 'warning';
        } elseif ($drift_seconds <= $this->drift_thresholds['emergency']) {
            return 'critical';
        } else {
            return 'emergency';
        }
    }
    
    /**
     * Get recommendation based on drift status
     */
    private function getRecommendation($drift_status)
    {
        switch ($drift_status) {
            case 'aligned':
                return 'no_action_needed';
            case 'warning':
                return 'consider_sync';
            case 'critical':
                return 'cast_compressed_sync';
            case 'emergency':
                return 'cast_emergency_sync';
            default:
                return 'cast_full_sync';
        }
    }
    
    /**
     * Calculate confidence based on drift
     */
    private function calculateConfidence($drift_seconds)
    {
        $max_drift = $this->drift_thresholds['emergency'];
        
        if ($drift_seconds >= $max_drift) {
            return 0.0;
        }
        
        return round(1.0 - ($drift_seconds / $max_drift), 2);
    }
    
    /**
     * Get temporal truth status
     */
    private function getTemporalTruthStatus($drift_status)
    {
        switch ($drift_status) {
            case 'aligned':
                return 'ESTABLISHED';
            case 'warning':
                return 'SOFT_ESTABLISHED';
            case 'critical':
                return 'COMPROMISED';
            case 'emergency':
                return 'LOST';
            default:
                return 'UNKNOWN';
        }
    }
    
    /**
     * Get sync statistics
     */
    public function getSyncStatistics()
    {
        if (!$this->db) {
            return [];
        }
        
        $stats = [
            'total_castings' => 0,
            'last_24_hours' => 0,
            'last_week' => 0,
            'most_common_variant' => 'unknown',
            'average_confidence' => 0.0,
            'temporal_truth_uptime' => 0.0
        ];
        
        try {
            // Total castings
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM lupo_reverse_shaka_syncs");
            $stmt->execute();
            $result = $stmt->get_result();
            $stats['total_castings'] = $result->fetch_assoc()['total'];
            
            // Last 24 hours / week — packed UTC via timestamp_ymdhis (PRD 00 §3.5.3)
            if (class_exists('timestamp_ymdhis')) {
                $nowPacked = timestamp_ymdhis::now();
                $day_ago = (string) timestamp_ymdhis::subtractSeconds($nowPacked, 86400);
                $week_ago = (string) timestamp_ymdhis::subtractSeconds($nowPacked, 7 * 86400);
            } else {
                $dtDay = new DateTime('now', new DateTimeZone('UTC'));
                $dtDay->modify('-24 hours');
                $day_ago = $dtDay->format('YmdHis');
                $dtWeek = new DateTime('now', new DateTimeZone('UTC'));
                $dtWeek->modify('-7 days');
                $week_ago = $dtWeek->format('YmdHis');
            }
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM lupo_reverse_shaka_syncs WHERE created_ymdhis >= ?");
            $stmt->bind_param('s', $day_ago);
            $stmt->execute();
            $result = $stmt->get_result();
            $stats['last_24_hours'] = $result->fetch_assoc()['count'];
            
            // Last week
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM lupo_reverse_shaka_syncs WHERE created_ymdhis >= ?");
            $stmt->bind_param('s', $week_ago);
            $stmt->execute();
            $result = $stmt->get_result();
            $stats['last_week'] = $result->fetch_assoc()['count'];
            
            // Most common variant
            $stmt = $this->db->prepare("
                SELECT spell_variant, COUNT(*) as count 
                FROM lupo_reverse_shaka_syncs 
                GROUP BY spell_variant 
                ORDER BY count DESC 
                LIMIT 1
            ");
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $stats['most_common_variant'] = $row['spell_variant'];
            }
            
            // Average confidence (from sync results)
            $stmt = $this->db->prepare("
                SELECT AVG(system_alignment_confidence) as avg_confidence 
                FROM lupo_reverse_shaka_syncs 
                WHERE system_alignment_confidence IS NOT NULL
            ");
            $stmt->execute();
            $result = $stmt->get_result();
            $avg_confidence = $result->fetch_assoc()['avg_confidence'];
            $stats['average_confidence'] = round($avg_confidence ?? 0.0, 2);
            
            // Temporal truth uptime (percentage of time in aligned state)
            $stats['temporal_truth_uptime'] = $this->calculateTemporalTruthUptime();
            
        } catch (Exception $e) {
            error_log("Failed to get sync statistics: " . $e->getMessage());
        }
        
        return $stats;
    }
    
    /**
     * Calculate temporal truth uptime percentage
     */
    private function calculateTemporalTruthUptime()
    {
        // This is a simplified calculation
        // In practice, you'd analyze the sync history over time
        $current_alignment = $this->checkTemporalAlignment();
        
        if ($current_alignment['drift_status'] === 'aligned') {
            return 100.0;
        } elseif ($current_alignment['drift_status'] === 'warning') {
            return 85.0;
        } elseif ($current_alignment['drift_status'] === 'critical') {
            return 50.0;
        } else {
            return 0.0;
        }
    }
    
    /**
     * Auto-cast sync if needed (for automated systems)
     */
    public function autoSyncIfNeeded($force_emergency = false)
    {
        $alignment = $this->checkTemporalAlignment();
        
        if ($alignment['recommendation'] === 'no_action_needed' && !$force_emergency) {
            return [
                'action_taken' => 'none',
                'reason' => 'temporal_truth_established',
                'alignment_status' => $alignment
            ];
        }
        
        // Determine which spell to cast
        $spell_variant = 'full';
        switch ($alignment['recommendation']) {
            case 'consider_sync':
                $spell_variant = 'ultra';
                break;
            case 'cast_compressed_sync':
                $spell_variant = 'compressed';
                break;
            case 'cast_emergency_sync':
                $spell_variant = 'emergency';
                break;
            case 'cast_full_sync':
                $spell_variant = 'full';
                break;
        }
        
        if ($force_emergency) {
            $spell_variant = 'emergency';
        }
        
        // Cast the spell
        require_once __DIR__ . '/ReverseShakaUTC2026.php';
        $spell_caster = new ReverseShakaUTC2026($this->db);
        
        switch ($spell_variant) {
            case 'ultra':
                $result = $spell_caster->castUltraCompressed();
                break;
            case 'compressed':
                $result = $spell_caster->castCompressed();
                break;
            case 'emergency':
                $result = $spell_caster->emergencySync();
                break;
            case 'full':
            default:
                $result = $spell_caster->castFull();
                break;
        }
        
        return [
            'action_taken' => 'auto_sync_cast',
            'spell_variant' => $spell_variant,
            'reason' => $alignment['recommendation'],
            'previous_alignment' => $alignment,
            'sync_result' => $result
        ];
    }
    
    /**
     * Get temporal health dashboard data
     */
    public function getTemporalHealthDashboard()
    {
        $alignment = $this->checkTemporalAlignment();
        $statistics = $this->getSyncStatistics();
        
        return [
            'current_status' => $alignment,
            'statistics' => $statistics,
            'health_score' => $this->calculateHealthScore($alignment, $statistics),
            'alerts' => $this->generateAlerts($alignment),
            'recommendations' => $this->generateRecommendations($alignment, $statistics),
            'last_updated' => $this->current_timestamp
        ];
    }
    
    /**
     * Calculate overall temporal health score
     */
    private function calculateHealthScore($alignment, $statistics)
    {
        $score = 0;
        $factors = 0;
        
        // Alignment confidence (40% weight)
        $score += $alignment['sync_confidence'] * 0.4;
        $factors += 0.4;
        
        // Average sync confidence (30% weight)
        $score += ($statistics['average_confidence'] / 100) * 0.3;
        $factors += 0.3;
        
        // Temporal truth uptime (20% weight)
        $score += ($statistics['temporal_truth_uptime'] / 100) * 0.2;
        $factors += 0.2;
        
        // Recent activity (10% weight)
        $recent_activity_score = min($statistics['last_24_hours'] / 10, 1.0);
        $score += $recent_activity_score * 0.1;
        $factors += 0.1;
        
        return round(($score / $factors) * 100, 1);
    }
    
    /**
     * Generate alerts based on current status
     */
    private function generateAlerts($alignment)
    {
        $alerts = [];
        
        if ($alignment['drift_status'] === 'emergency') {
            $alerts[] = [
                'level' => 'critical',
                'message' => 'Temporal truth lost - immediate sync required',
                'action' => 'cast_emergency_sync'
            ];
        } elseif ($alignment['drift_status'] === 'critical') {
            $alerts[] = [
                'level' => 'warning',
                'message' => 'Significant temporal drift detected',
                'action' => 'cast_compressed_sync'
            ];
        } elseif ($alignment['drift_status'] === 'warning') {
            $alerts[] = [
                'level' => 'info',
                'message' => 'Minor temporal drift detected',
                'action' => 'consider_sync'
            ];
        }
        
        if (!$alignment['last_sync']) {
            $alerts[] = [
                'level' => 'critical',
                'message' => 'No previous sync records found',
                'action' => 'cast_initial_sync'
            ];
        }
        
        return $alerts;
    }
    
    /**
     * Generate recommendations
     */
    private function generateRecommendations($alignment, $statistics)
    {
        $recommendations = [];
        
        if ($alignment['recommendation'] !== 'no_action_needed') {
            $recommendations[] = [
                'type' => 'immediate',
                'action' => $alignment['recommendation'],
                'reason' => 'Restore temporal alignment'
            ];
        }
        
        if ($statistics['last_24_hours'] === 0) {
            $recommendations[] = [
                'type' => 'maintenance',
                'action' => 'schedule_regular_syncs',
                'reason' => 'No recent sync activity detected'
            ];
        }
        
        if ($statistics['average_confidence'] < 0.8) {
            $recommendations[] = [
                'type' => 'improvement',
                'action' => 'review_sync_patterns',
                'reason' => 'Below average sync confidence'
            ];
        }
        
        return $recommendations;
    }
}
