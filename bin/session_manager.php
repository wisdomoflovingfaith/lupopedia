<?php
/**
 * Session Management System
 * 
 * Syncs actor session.json files to lupo_sessions database table
 * Prevents cross-contamination between multiple IDE agents
 * Follows TOON schema: lupo_sessions
 * 
 * @author Windsurf (1002)
 * @version 4.0.52
 * @date 2026-03-01
 */

class SessionManager {
    private $db;
    private $actorsPath;
    private $errors = [];
    
    public function __construct($db = null) {
        $this->db = $db ?: DatabaseFactory::getConnection();
        $this->actorsPath = LUPOPEDIA_PATH . '/actors';
    }
    
    /**
     * Sync all actor session.json files to database
     * 
     * @return array Results of sync operation
     */
    public function syncAllActorSessions() {
        $results = [
            'processed' => 0,
            'success' => 0,
            'failed' => 0,
            'details' => []
        ];
        
        // Get all actor directories
        $actorDirs = glob($this->actorsPath . '/*', GLOB_ONLYDIR);
        
        foreach ($actorDirs as $actorDir) {
            $actorId = basename($actorDir);
            $sessionFile = $actorDir . '/session.json';
            
            if (file_exists($sessionFile)) {
                $result = $this->syncActorSession($actorId, $sessionFile);
                $results['processed']++;
                
                if ($result['success']) {
                    $results['success']++;
                } else {
                    $results['failed']++;
                    $results['details'][] = "Actor $actorId: " . $result['error'];
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Sync individual actor session to database
     * 
     * @param int $actorId Actor ID
     * @param string $sessionFile Path to session.json file
     * @return array Sync result
     */
    public function syncActorSession($actorId, $sessionFile) {
        try {
            // Read session.json file
            $sessionData = json_decode(file_get_contents($sessionFile), true);
            if (!$sessionData) {
                return ['success' => false, 'error' => 'Invalid JSON in session.json'];
            }
            
            // Validate required fields
            $required = ['current_session_id', 'actor_id', 'last_active_ymdhis', 'node_id', 'system_version'];
            foreach ($required as $field) {
                if (!isset($sessionData[$field])) {
                    return ['success' => false, 'error' => "Missing required field: $field"];
                }
            }
            
            // Map session.json to lupo_sessions structure
            $now = gmdate('YmdHis');
            $params = [
                'session_id' => $sessionData['current_session_id'],
                'federation_node_id' => (int)$sessionData['node_id'],
                'actor_id' => (int)$sessionData['actor_id'],
                'channel_id' => 0, // System channel for IDE agents
                'ip_address' => '127.0.0.1', // Local development
                'user_agent' => 'IDE-Agent-' . $sessionData['actor_id'],
                'device_id' => null,
                'device_type' => 'ide',
                'auth_method' => 'local',
                'auth_provider' => 'lupopedia',
                'security_level' => 'high',
                'name_key' => null,
                'is_named' => 0,
                'is_authenticated' => 1,
                'is_active' => 1,
                'is_expired' => 0,
                'is_revoked' => 0,
                'session_data' => json_encode([
                    'actor_slug' => $sessionData['actor_slug'] ?? '',
                    'current_session_id' => $sessionData['current_session_id'],
                    'last_active_ymdhis' => $sessionData['last_active_ymdhis'],
                    'node_id' => $sessionData['node_id'],
                    'system_version' => $sessionData['system_version']
                ]),
                'system_context' => 'ide_agent',
                'metadata' => json_encode([
                    'system_version' => $sessionData['system_version'],
                    'actor_type' => 'ide_agent',
                    'sync_source' => 'session_json'
                ]),
                'login_ymdhis' => $sessionData['last_active_ymdhis'],
                'last_seen_ymdhis' => (int)$sessionData['last_active_ymdhis'],
                'expires_ymdhis' => null, // No expiration for IDE agents
                'created_ymdhis' => $sessionData['last_active_ymdhis'],
                'updated_ymdhis' => $now,
                'is_deleted' => 0,
                'deleted_ymdhis' => null
            ];
            
            // Use UPSERT pattern (MySQL/MariaDB compatible)
            return $this->upsertSession($params);
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * UPSERT session record (Update or Insert)
     * 
     * @param array $params Session parameters
     * @return array Result
     */
    private function upsertSession($params) {
        // Check if session exists
        $exists = $this->db->fetchColumn(
            "SELECT 1 FROM lupo_sessions WHERE session_id = :session_id",
            ['session_id' => $params['session_id']]
        );
        
        if ($exists) {
            // Update existing session
            $updateParams = [
                'last_seen_ymdhis' => $params['last_seen_ymdhis'],
                'updated_ymdhis' => $params['updated_ymdhis'],
                'is_active' => 1,
                'session_data' => $params['session_data'],
                'metadata' => $params['metadata']
            ];
            
            $this->db->update('lupo_sessions', $updateParams, ['session_id' => $params['session_id']]);
            
            return ['success' => true, 'action' => 'updated'];
        } else {
            // Insert new session
            $this->db->insert('lupo_sessions', $params);
            
            return ['success' => true, 'action' => 'inserted'];
        }
    }
    
    /**
     * Get active sessions for monitoring
     * 
     * @return array Active sessions
     */
    public function getActiveSessions() {
        $sql = "SELECT 
                    session_id, 
                    actor_id, 
                    federation_node_id, 
                    last_seen_ymdhis, 
                    is_active,
                    metadata
                 FROM lupo_sessions 
                 WHERE is_active = 1 AND is_deleted = 0 
                 ORDER BY last_seen_ymdhis DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Cleanup expired sessions
     * 
     * @param int $maxAge Maximum age in seconds (default: 24 hours)
     * @return int Number of sessions cleaned up
     */
    public function cleanupExpiredSessions($maxAge = 86400) {
        $cutoff = gmdate('YmdHis', time() - $maxAge);
        
        $sql = "UPDATE lupo_sessions 
                 SET is_active = 0, 
                     is_expired = 1, 
                     updated_ymdhis = :updated_ymdhis
                 WHERE is_active = 1 
                   AND last_seen_ymdhis < :cutoff
                   AND is_deleted = 0";
        
        $params = [
            'updated_ymdhis' => gmdate('YmdHis'),
            'cutoff' => $cutoff
        ];
        
        $this->db->execute($sql, $params);
        
        return $this->db->rowCount();
    }
    
    /**
     * Validate session integrity
     * 
     * @param string $sessionId Session ID to validate
     * @return array Validation result
     */
    public function validateSession($sessionId) {
        $sql = "SELECT 
                    actor_id, 
                    federation_node_id, 
                    is_active, 
                    is_expired, 
                    is_revoked,
                    last_seen_ymdhis
                 FROM lupo_sessions 
                 WHERE session_id = :session_id 
                   AND is_deleted = 0";
        
        $session = $this->db->fetch($sql, ['session_id' => $sessionId]);
        
        if (!$session) {
            return ['valid' => false, 'reason' => 'Session not found'];
        }
        
        // Check session status
        if (!$session['is_active']) {
            return ['valid' => false, 'reason' => 'Session inactive'];
        }
        
        if ($session['is_expired']) {
            return ['valid' => false, 'reason' => 'Session expired'];
        }
        
        if ($session['is_revoked']) {
            return ['valid' => false, 'reason' => 'Session revoked'];
        }
        
        // Check session age (max 24 hours for IDE agents)
        $maxAge = 86400; // 24 hours
        $sessionAge = time() - $this->convertYmdHisToTimestamp($session['last_seen_ymdhis']);
        
        if ($sessionAge > $maxAge) {
            return ['valid' => false, 'reason' => 'Session too old'];
        }
        
        return ['valid' => true, 'session' => $session];
    }
    
    /**
     * Convert YmdHis to Unix timestamp
     * 
     * @param int $ymdhis YmdHis timestamp
     * @return int Unix timestamp
     */
    private function convertYmdHisToTimestamp($ymdhis) {
        $year = substr($ymdhis, 0, 4);
        $month = substr($ymdhis, 4, 2);
        $day = substr($ymdhis, 6, 2);
        $hour = substr($ymdhis, 8, 2);
        $minute = substr($ymdhis, 10, 2);
        $second = substr($ymdhis, 12, 2);
        
        return gmmktime($hour, $minute, $second, $month, $day, $year);
    }
    
    /**
     * Get session statistics
     * 
     * @return array Session statistics
     */
    public function getSessionStatistics() {
        $sql = "SELECT 
                    COUNT(*) as total_sessions,
                    COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_sessions,
                    COUNT(CASE WHEN is_expired = 1 THEN 1 END) as expired_sessions,
                    COUNT(CASE WHEN is_revoked = 1 THEN 1 END) as revoked_sessions,
                    COUNT(CASE WHEN is_deleted = 0 THEN 1 END) as valid_sessions
                 FROM lupo_sessions";
        
        $stats = $this->db->fetch($sql);
        
        // Get active sessions by actor type
        $actorSql = "SELECT 
                        actor_id,
                        COUNT(*) as session_count
                     FROM lupo_sessions 
                     WHERE is_active = 1 AND is_deleted = 0 
                     GROUP BY actor_id
                     ORDER BY session_count DESC";
        
        $actorStats = $this->db->fetchAll($actorSql);
        
        return [
            'overview' => $stats,
            'by_actor' => $actorStats
        ];
    }
    
    /**
     * Get any errors from session operations
     */
    public function getErrors() {
        return $this->errors;
    }
}

// CLI interface for session management
if (php_sapi_name() === 'cli') {
    echo "=== Lupopedia Session Management ===\n";
    echo "Version: 4.0.52\n";
    echo "Agent: Windsurf (1002)\n";
    echo "Time: " . gmdate('Y-m-d H:i:s UTC') . "\n\n";
    
    $sessionManager = new SessionManager();
    
    // Parse command line arguments
    $command = $argv[1] ?? 'sync';
    
    switch ($command) {
        case 'sync':
            echo "🔄 Syncing all actor sessions to database...\n";
            $results = $sessionManager->syncAllActorSessions();
            
            echo "✅ Processed: {$results['processed']} actors\n";
            echo "✅ Success: {$results['success']} sessions\n";
            echo "❌ Failed: {$results['failed']} sessions\n";
            
            if (!empty($results['details'])) {
                echo "\n⚠️ Errors:\n";
                foreach ($results['details'] as $error) {
                    echo "  - $error\n";
                }
            }
            break;
            
        case 'active':
            echo "📊 Active sessions:\n";
            $activeSessions = $sessionManager->getActiveSessions();
            
            foreach ($activeSessions as $session) {
                $metadata = json_decode($session['metadata'], true) ?: [];
                $actorType = $metadata['actor_type'] ?? 'unknown';
                echo "  Actor {$session['actor_id']} ({$actorType}): {$session['session_id']}\n";
                echo "    Last seen: {$session['last_seen_ymdhis']}\n";
                echo "    Node: {$session['federation_node_id']}\n\n";
            }
            break;
            
        case 'cleanup':
            echo "🧹 Cleaning up expired sessions...\n";
            $cleaned = $sessionManager->cleanupExpiredSessions();
            echo "✅ Cleaned up: $cleaned expired sessions\n";
            break;
            
        case 'stats':
            echo "📈 Session statistics:\n";
            $stats = $sessionManager->getSessionStatistics();
            
            echo "Total sessions: {$stats['overview']['total_sessions']}\n";
            echo "Active sessions: {$stats['overview']['active_sessions']}\n";
            echo "Expired sessions: {$stats['overview']['expired_sessions']}\n";
            echo "Revoked sessions: {$stats['overview']['revoked_sessions']}\n";
            echo "Valid sessions: {$stats['overview']['valid_sessions']}\n\n";
            
            echo "By actor:\n";
            foreach ($stats['by_actor'] as $actor) {
                echo "  Actor {$actor['actor_id']}: {$actor['session_count']} sessions\n";
            }
            break;
            
        case 'validate':
            if (!isset($argv[2])) {
                echo "❌ Usage: php session_manager.php validate <session_id>\n";
                exit(1);
            }
            
            $sessionId = $argv[2];
            echo "🔍 Validating session: $sessionId\n";
            
            $validation = $sessionManager->validateSession($sessionId);
            if ($validation['valid']) {
                echo "✅ Session is valid\n";
                echo "  Actor: {$validation['session']['actor_id']}\n";
                echo "  Node: {$validation['session']['federation_node_id']}\n";
                echo "  Last seen: {$validation['session']['last_seen_ymdhis']}\n";
            } else {
                echo "❌ Session is invalid: {$validation['reason']}\n";
            }
            break;
            
        default:
            echo "Usage:\n";
            echo "  php session_manager.php sync      - Sync all actor sessions\n";
            echo "  php session_manager.php active    - Show active sessions\n";
            echo "  php session_manager.php cleanup   - Clean up expired sessions\n";
            echo "  php session_manager.php stats     - Show session statistics\n";
            echo "  php session_manager.php validate <session_id> - Validate specific session\n";
            exit(1);
    }
    
    echo "\n=== Session Management Complete ===\n";
}

?>
