<?php
/**
 * Channel Boot Lifecycle Helpers
 * 
 * Provides functions for managing channel boot lifecycle operations
 * Follows TOON schema: lupo_channel_boot_lifecycle and lupo_channel_boot_detail_lifecycle
 * 
 * @author Windsurf (1002)
 * @version 4.0.52
 * @date 2026-03-01
 */

class ChannelBootLifecycle {
    private $db;
    private $errors = [];
    private $warnings = [];
    
    public function __construct($db = null) {
        $this->db = $db ?: DatabaseFactory::getConnection();
    }
    
    /**
     * Start a new channel boot lifecycle
     * 
     * @param int $actorId Actor initiating the boot
     * @param string $sessionId Session identifier
     * @param string $lifecycleType Type of lifecycle (e.g., 'full_boot', 'incremental', 'recovery')
     * @param array $channelIds Array of channel IDs to process
     * @return int|false Lifecycle ID or false on error
     */
    public function startLifecycle($actorId, $sessionId, $lifecycleType, $channelIds) {
        try {
            $startTime = gmdate('YmdHis');
            
            // Insert main lifecycle record
            $sql = "INSERT INTO lupo_channel_boot_lifecycle 
                (actor_id, session_id, lifecycle_start_time, lifecycle_status, lifecycle_type, total_channels, created_ymdhis) 
                VALUES (:actor_id, :session_id, :start_time, 'started', :lifecycle_type, :total_channels, :created_ymdhis)";
            
            $params = [
                'actor_id' => $actorId,
                'session_id' => $sessionId,
                'start_time' => $startTime,
                'lifecycle_type' => $lifecycleType,
                'total_channels' => count($channelIds),
                'created_ymdhis' => $startTime
            ];
            
            $this->db->insert('lupo_channel_boot_lifecycle', $params);
            $lifecycleId = $this->db->lastInsertId();
            
            // Insert detail records for each channel
            foreach ($channelIds as $channelId) {
                $this->insertChannelDetail($lifecycleId, $channelId);
            }
            
            return $lifecycleId;
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to start lifecycle: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Insert a channel detail record for a lifecycle
     * 
     * @param int $lifecycleId Lifecycle ID
     * @param int $channelId Channel ID to process
     * @return bool Success status
     */
    private function insertChannelDetail($lifecycleId, $channelId) {
        try {
            $startTime = gmdate('YmdHis');
            
            $sql = "INSERT INTO lupo_channel_boot_detail_lifecycle 
                (lifecycle_id, channel_id, detail_start_time, detail_status, created_ymdhis) 
                VALUES (:lifecycle_id, :channel_id, :start_time, 'started', :created_ymdhis)";
            
            $params = [
                'lifecycle_id' => $lifecycleId,
                'channel_id' => $channelId,
                'start_time' => $startTime,
                'created_ymdhis' => $startTime
            ];
            
            $this->db->insert('lupo_channel_boot_detail_lifecycle', $params);
            return true;
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to insert channel detail for channel {$channelId}: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Update channel detail with completion status
     * 
     * @param int $lifecycleId Lifecycle ID
     * @param int $channelId Channel ID
     * @param string $status New status ('completed', 'failed', 'partial')
     * @param int $itemsLoaded Number of items loaded
     * @param int $totalItems Total items expected
     * @param string|null $errorMessage Optional error message
     * @return bool Success status
     */
    public function updateChannelDetail($lifecycleId, $channelId, $status, $itemsLoaded = 0, $totalItems = 0, $errorMessage = null) {
        try {
            $endTime = gmdate('YmdHis');
            $duration = null;
            
            // Calculate duration if we have start time
            $detailSql = "SELECT detail_start_time FROM lupo_channel_boot_detail_lifecycle 
                          WHERE lifecycle_id = :lifecycle_id AND channel_id = :channel_id";
            $detailParams = ['lifecycle_id' => $lifecycleId, 'channel_id' => $channelId];
            $startTime = $this->db->fetchOne($detailSql, $detailParams);
            
            if ($startTime && $startTime['detail_start_time']) {
                $duration = ($endTime - $startTime['detail_start_time']) * 1000; // Convert to milliseconds
            }
            
            $sql = "UPDATE lupo_channel_boot_detail_lifecycle 
                SET detail_end_time = :end_time, detail_status = :status, 
                    content_items_loaded = :items_loaded, total_content_items = :total_items, 
                    detail_duration_ms = :duration";
            
            $params = [
                'end_time' => $endTime,
                'status' => $status,
                'items_loaded' => $itemsLoaded,
                'total_items' => $totalItems,
                'duration' => $duration
            ];
            
            if ($errorMessage !== null) {
                $sql .= ", error_message = :error_message";
                $params['error_message'] = $errorMessage;
            }
            
            $sql .= " WHERE lifecycle_id = :lifecycle_id AND channel_id = :channel_id";
            $params['lifecycle_id'] = $lifecycleId;
            $params['channel_id'] = $channelId;
            
            return $this->db->execute($sql, $params);
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to update channel detail for channel {$channelId}: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Complete the entire lifecycle
     * 
     * @param int $lifecycleId Lifecycle ID to complete
     * @param array $performanceMetrics Optional performance metrics
     * @return bool Success status
     */
    public function completeLifecycle($lifecycleId, $performanceMetrics = []) {
        try {
            $endTime = gmdate('YmdHis');
            
            // Get current statistics
            $statsSql = "SELECT 
                            COUNT(*) as total_processed,
                            SUM(CASE WHEN detail_status = 'completed' THEN 1 ELSE 0 END) as successful,
                            SUM(CASE WHEN detail_status = 'failed' THEN 1 ELSE 0 END) as failed,
                            SUM(detail_duration_ms) as total_duration_ms
                        FROM lupo_channel_boot_detail_lifecycle 
                        WHERE lifecycle_id = :lifecycle_id";
            
            $stats = $this->db->fetchOne($statsSql, ['lifecycle_id' => $lifecycleId]);
            
            // Update lifecycle record
            $sql = "UPDATE lupo_channel_boot_lifecycle 
                SET lifecycle_end_time = :end_time, lifecycle_status = 'completed',
                    channels_processed = :processed, channels_successful = :successful, channels_failed = :failed,
                    lifecycle_duration_ms = :duration";
            
            $params = [
                'end_time' => $endTime,
                'processed' => $stats['total_processed'],
                'successful' => $stats['successful'],
                'failed' => $stats['failed'],
                'duration' => $stats['total_duration_ms']
            ];
            
            if (!empty($performanceMetrics)) {
                $sql .= ", performance_metrics = :performance_metrics";
                $params['performance_metrics'] = json_encode($performanceMetrics);
            }
            
            $sql .= " WHERE lifecycle_id = :lifecycle_id";
            $params['lifecycle_id'] = $lifecycleId;
            
            return $this->db->execute($sql, $params);
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to complete lifecycle {$lifecycleId}: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Fail the entire lifecycle
     * 
     * @param int $lifecycleId Lifecycle ID to fail
     * @param string $errorDetails Error details as JSON
     * @return bool Success status
     */
    public function failLifecycle($lifecycleId, $errorDetails) {
        try {
            $endTime = gmdate('YmdHis');
            
            // Update lifecycle record with failure
            $sql = "UPDATE lupo_channel_boot_lifecycle 
                SET lifecycle_end_time = :end_time, lifecycle_status = 'failed',
                    channels_processed = 0, channels_successful = 0, channels_failed = total_channels,
                    error_details = :error_details";
            
            $params = [
                'end_time' => $endTime,
                'error_details' => $errorDetails
            ];
            
            $sql .= " WHERE lifecycle_id = :lifecycle_id";
            $params['lifecycle_id'] = $lifecycleId;
            
            return $this->db->execute($sql, $params);
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to fail lifecycle {$lifecycleId}: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Get active lifecycles
     * 
     * @param int $limit Optional limit
     * @return array Active lifecycles
     */
    public function getActiveLifecycles($limit = 10) {
        try {
            $sql = "SELECT 
                            l.lifecycle_id, l.actor_id, l.session_id, l.lifecycle_type, 
                            l.lifecycle_start_time, l.lifecycle_status, l.total_channels,
                            l.channels_processed, l.channels_successful, l.channels_failed,
                            COUNT(d.detail_lifecycle_id) as active_channels
                        FROM lupo_channel_boot_lifecycle l
                        LEFT JOIN lupo_channel_boot_detail_lifecycle d ON l.lifecycle_id = d.lifecycle_id
                        WHERE l.lifecycle_status IN ('started', 'loading')
                        GROUP BY l.lifecycle_id
                        ORDER BY l.lifecycle_start_time DESC
                        LIMIT :limit";
            
            return $this->db->fetchAll($sql, ['limit' => $limit]);
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to get active lifecycles: " . $e->getMessage();
            return [];
        }
    }
    
    /**
     * Get lifecycle details
     * 
     * @param int $lifecycleId Lifecycle ID
     * @return array Lifecycle details
     */
    public function getLifecycleDetails($lifecycleId) {
        try {
            $sql = "SELECT 
                            d.detail_lifecycle_id, d.channel_id, d.detail_start_time, d.detail_end_time,
                            d.detail_status, d.content_items_loaded, d.total_content_items,
                            d.detail_duration_ms, d.error_message,
                            c.channel_name
                        FROM lupo_channel_boot_detail_lifecycle d
                        LEFT JOIN lupo_channels c ON d.channel_id = c.channel_id
                        WHERE d.lifecycle_id = :lifecycle_id
                        ORDER BY d.detail_start_time";
            
            return $this->db->fetchAll($sql, ['lifecycle_id' => $lifecycleId]);
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to get lifecycle details: " . $e->getMessage();
            return [];
        }
    }
    
    /**
     * Get errors encountered
     * 
     * @return array Error messages
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Get warnings encountered
     * 
     * @return array Warning messages
     */
    public function getWarnings() {
        return $this->warnings;
    }
    
    /**
     * Check if there are any errors
     * 
     * @return bool True if errors exist
     */
    public function hasErrors() {
        return !empty($this->errors);
    }
    
    /**
     * Clear all errors and warnings
     * 
     * @return void
     */
    public function clearErrors() {
        $this->errors = [];
        $this->warnings = [];
    }
}
