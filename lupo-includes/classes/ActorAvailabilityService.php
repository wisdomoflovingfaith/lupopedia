<?php
/**
 * ActorAvailabilityService – Manage operator availability status for live help system
 * 
 * Responsible for:
 * - Getting/setting operator status (online, busy, away, offline)
 * - Tracking activity for auto-away detection
 * - Finding available operators in a channel
 * - Enforcing unique constraint (one status record per actor per channel)
 */

class ActorAvailabilityService
{
    private $db;
    private $table_prefix;
    
    const STATUS_ONLINE = 'online';
    const STATUS_BUSY = 'busy';
    const STATUS_AWAY = 'away';
    const STATUS_OFFLINE = 'offline';
    
    const VALID_STATUSES = ['online', 'busy', 'away', 'offline'];
    const AUTO_AWAY_TIMEOUT_MINUTES = 15;
    
    /**
     * Constructor
     * 
     * @param PDO_DB $db Database connection
     * @param string $table_prefix Table prefix (default: 'lupo_')
     */
    public function __construct($db, $table_prefix = 'lupo_')
    {
        $this->db = $db;
        $this->table_prefix = $table_prefix;
    }
    
    /**
     * Get current status for an operator in a channel
     * 
     * @param int $actor_id
     * @param int $channel_id
     * @return string|false Status string or false if not found
     */
    public function getStatusForOperator($actor_id, $channel_id)
    {
        $result = $this->db->fetchRow(
            "SELECT status FROM {$this->table_prefix}actor_availability_status 
             WHERE actor_id = :actor_id 
               AND channel_id = :channel_id 
               AND is_deleted = 0",
            ['actor_id' => $actor_id, 'channel_id' => $channel_id]
        );
        
        return $result ? $result['status'] : false;
    }
    
    /**
     * Set operator status
     * 
     * Creates or updates the availability record. Enforces unique constraint.
     * 
     * @param int $actor_id
     * @param int $channel_id
     * @param string $new_status One of: online, busy, away, offline
     * @return bool True on success, false on invalid status
     * @throws Exception If database error occurs
     */
    public function setStatus($actor_id, $channel_id, $new_status)
    {
        if (!in_array($new_status, self::VALID_STATUSES)) {
            return false;
        }
        
        $now = gmdate('YmdHis');
        $table = $this->table_prefix . 'actor_availability_status';
        
        // Check if record exists
        $existing = $this->db->fetchRow(
            "SELECT availability_id FROM {$table} 
             WHERE actor_id = :actor_id 
               AND channel_id = :channel_id",
            ['actor_id' => $actor_id, 'channel_id' => $channel_id]
        );
        
        if ($existing) {
            // Update existing record
            $result = $this->db->update(
                $table,
                [
                    'status' => $new_status,
                    'last_activity_ymdhis' => $now,
                    'updated_ymdhis' => $now,
                    'is_deleted' => 0,
                    'deleted_ymdhis' => null,
                ],
                [
                    'availability_id' => $existing['availability_id'],
                ]
            );
            return (bool) $result;
        } else {
            // Create new record with auto-assigned ID
            $availability_id = $this->_allocateId();
            $result = $this->db->insert(
                $table,
                [
                    'availability_id' => $availability_id,
                    'actor_id' => $actor_id,
                    'channel_id' => $channel_id,
                    'status' => $new_status,
                    'last_activity_ymdhis' => $now,
                    'created_ymdhis' => $now,
                    'updated_ymdhis' => $now,
                    'is_deleted' => 0,
                ]
            );
            return (bool) $result;
        }
    }
    
    /**
     * Get all available operators in a channel (status: online or busy)
     * 
     * @param int $channel_id
     * @return array Array of ['actor_id' => int, 'status' => string]
     */
    public function getAvailableOperators($channel_id)
    {
        $results = $this->db->fetchAll(
            "SELECT actor_id, status FROM {$this->table_prefix}actor_availability_status 
             WHERE channel_id = :channel_id 
               AND status IN ('online', 'busy') 
               AND is_deleted = 0 
             ORDER BY actor_id",
            ['channel_id' => $channel_id]
        );
        
        return $results ?: [];
    }
    
    /**
     * Get online operators only (not busy, not away)
     * 
     * @param int $channel_id
     * @return array Array of actor_ids that are online
     */
    public function getOnlineOperators($channel_id)
    {
        $results = $this->db->fetchAll(
            "SELECT actor_id FROM {$this->table_prefix}actor_availability_status 
             WHERE channel_id = :channel_id 
               AND status = 'online' 
               AND is_deleted = 0 
             ORDER BY actor_id",
            ['channel_id' => $channel_id]
        );
        
        return array_column($results, 'actor_id');
    }
    
    /**
     * Touch activity timestamp for auto-away detection
     * 
     * Updates last_activity_ymdhis to current time. Called on any operator action.
     * 
     * @param int $actor_id
     * @param int $channel_id
     * @return bool True on success
     */
    public function touchActivity($actor_id, $channel_id)
    {
        $now = gmdate('YmdHis');
        $result = $this->db->update(
            $this->table_prefix . 'actor_availability_status',
            ['last_activity_ymdhis' => $now, 'updated_ymdhis' => $now],
            [
                'actor_id' => $actor_id,
                'channel_id' => $channel_id,
                'is_deleted' => 0,
            ]
        );
        
        return (bool) $result;
    }
    
    /**
     * Auto-away detection: Find operators idle > N minutes and transition to away
     * 
     * Called by a periodic task (cron job). Scans idle operators and transitions
     * their status from 'online' or 'busy' to 'away'.
     * 
     * @return int Number of operators transitioned to away
     */
    public function processAutoAway()
    {
        $now_ymdhis = gmdate('YmdHis');
        $idle_threshold_ymdhis = $this->_subtractMinutes($now_ymdhis, self::AUTO_AWAY_TIMEOUT_MINUTES);
        
        $idle_operators = $this->db->fetchAll(
            "SELECT availability_id FROM {$this->table_prefix}actor_availability_status 
             WHERE status IN ('online', 'busy') 
               AND last_activity_ymdhis < :threshold 
               AND is_deleted = 0",
            ['threshold' => $idle_threshold_ymdhis]
        );
        
        $count = 0;
        foreach ($idle_operators as $record) {
            $updated = $this->db->update(
                $this->table_prefix . 'actor_availability_status',
                [
                    'status' => self::STATUS_AWAY,
                    'updated_ymdhis' => $now_ymdhis,
                ],
                ['availability_id' => $record['availability_id']]
            );
            if ($updated) {
                $count++;
            }
        }
        
        return $count;
    }
    
    /**
     * Get current activity timestamp for an operator
     * 
     * @param int $actor_id
     * @param int $channel_id
     * @return string|false Activity timestamp (YYYYMMDDHHIISS) or false if not found
     */
    public function getLastActivityTime($actor_id, $channel_id)
    {
        $result = $this->db->fetchRow(
            "SELECT last_activity_ymdhis FROM {$this->table_prefix}actor_availability_status 
             WHERE actor_id = :actor_id 
               AND channel_id = :channel_id 
               AND is_deleted = 0",
            ['actor_id' => $actor_id, 'channel_id' => $channel_id]
        );
        
        return $result ? $result['last_activity_ymdhis'] : false;
    }
    
    /**
     * Count operators with given status in a channel
     * 
     * @param int $channel_id
     * @param string $status One of: online, busy, away, offline
     * @return int Count
     */
    public function countOperatorsByStatus($channel_id, $status)
    {
        $result = $this->db->fetchRow(
            "SELECT COUNT(*) as cnt FROM {$this->table_prefix}actor_availability_status 
             WHERE channel_id = :channel_id 
               AND status = :status 
               AND is_deleted = 0",
            ['channel_id' => $channel_id, 'status' => $status]
        );
        
        return $result ? (int) $result['cnt'] : 0;
    }
    
    /**
     * Soft-delete operator availability (e.g., when unassigned from channel)
     * 
     * @param int $actor_id
     * @param int $channel_id
     * @return bool True on success
     */
    public function deleteOperatorFromChannel($actor_id, $channel_id)
    {
        $now = gmdate('YmdHis');
        $result = $this->db->update(
            $this->table_prefix . 'actor_availability_status',
            [
                'is_deleted' => 1,
                'deleted_ymdhis' => $now,
                'updated_ymdhis' => $now,
            ],
            [
                'actor_id' => $actor_id,
                'channel_id' => $channel_id,
            ]
        );
        
        return (bool) $result;
    }
    
    // ===== PRIVATE HELPERS =====
    
    /**
     * Allocate unique availability_id
     * 
     * Uses DeterministicIdService for consistent ID generation.
     * Falls back to simple increment if service unavailable.
     * 
     * @return int
     */
    private function _allocateId()
    {
        // Try using DeterministicIdService if available
        if (class_exists('DeterministicIdService')) {
            $service = new DeterministicIdService($this->db, $this->table_prefix);
            return $service->allocateId('actor_availability_status');
        }
        
        // Fallback: get next ID from registry
        $last = $this->db->fetchRow(
            "SELECT MAX(availability_id) as max_id FROM {$this->table_prefix}actor_availability_status"
        );
        
        return ($last && $last['max_id']) ? (int) $last['max_id'] + 1 : 1001;
    }
    
    /**
     * Subtract minutes from a YMDHIS timestamp
     * 
     * @param string $ymdhis Timestamp in YYYYMMDDHHIISS format
     * @param int $minutes Minutes to subtract
     * @return string Result timestamp in YYYYMMDDHHIISS format
     */
    private function _subtractMinutes($ymdhis, $minutes)
    {
        if (!class_exists('timestamp_ymdhis', false)) {
            require_once defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH . '/lupo-includes/classes/TimestampYmdhis.php' : __DIR__ . '/TimestampYmdhis.php';
        }
        $packed = (int) preg_replace('/\D/', '', (string) $ymdhis);
        return (string) timestamp_ymdhis::subtractSeconds($packed, (int) $minutes * 60);
    }
}
