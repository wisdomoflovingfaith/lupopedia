<?php
// Function: loadDefaultSessionIfMissing
// Checks DB for actor's session; loads default JSON and UPSERTs if missing.
// Params: $db (PDO), $actor_id (int), $sessions_dir = 'lupo-sessions/' (string)
// Returns: session_id (string) on success, false on error

function loadDefaultSessionIfMissing($db, $actor_id, $sessions_dir = 'lupo-sessions/') {
    try {
        // Check if session exists in DB
        $stmt = $db->prepare("SELECT session_id FROM lupo_sessions WHERE actor_id = :actor_id AND status = 'active' AND is_deleted = 0 LIMIT 1");
        $stmt->execute(['actor_id' => $actor_id]);
        if ($stmt->fetch()) {
            return true;  // Exists, no action
        }

        // Load default JSON
        $default_file = $sessions_dir . 'actor_' . $actor_id . '_default.json';
        if (!file_exists($default_file)) {
            throw new Exception("Default session file missing: $default_file");
        }
        $session_data = json_decode(file_get_contents($default_file), true);
        if (!$session_data) {
            throw new Exception("Invalid JSON in $default_file");
        }

        // Update dynamic fields
        $now = gmdate('YmdHis');
        $session_data['last_seen_ymdhis'] = $now;  // Use last_seen_ymdhis per mapping
        $session_data['created_ymdhis'] = $now;
        if (strpos($session_data['current_session_id'], '00000000-0000-0000-0000-000000000000') !== false) {
            // Generate real UUID if placeholder
            $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();  // Requires ramsey/uuid
            $session_data['current_session_id'] = str_replace('00000000-0000-0000-0000-000000000000', $uuid, $session_data['current_session_id']);
        }

        // UPSERT to DB (MySQL example; adjust for other DBs)
        $stmt = $db->prepare("
            INSERT INTO lupo_sessions (
                session_id, actor_id, federation_node_id, last_seen_ymdhis, metadata, created_ymdhis, status, is_deleted
            ) VALUES (
                :session_id, :actor_id, :federation_node_id, :last_seen_ymdhis, :metadata, :created_ymdhis, 'active', 0
            ) ON DUPLICATE KEY UPDATE
                last_seen_ymdhis = VALUES(last_seen_ymdhis), metadata = VALUES(metadata), status = 'active'
        ");
        $stmt->execute([
            'session_id' => $session_data['current_session_id'],
            'actor_id' => $session_data['actor_id'],
            'federation_node_id' => $session_data['federation_node_id'],
            'last_seen_ymdhis' => $session_data['last_active_ymdhis'],  // Map if field names differ
            'metadata' => json_encode($session_data['metadata']),
            'created_ymdhis' => $session_data['created_ymdhis'] ?? $now
        ]);

        // Log to lupo_channel_logs
        $log_stmt = $db->prepare("INSERT INTO lupo_channel_logs (channel_id, actor_id, log_type_id, log_text, created_ymdhis) VALUES (0, :actor_id, 1, :log_text, :created)");
        $log_stmt->execute(['actor_id' => $actor_id, 'log_text' => "Default session loaded and synced for actor $actor_id", 'created' => $now]);

        return $session_data['current_session_id'];
    } catch (Exception $e) {
        // Log error
        error_log("Session load error for actor $actor_id: " . $e->getMessage());
        return false;
    }
}

// Usage example in boot_system_agent.php or install.php:
// $db = getPDO();  // Your DB connection
// foreach ([0,1,2,3,4,5,19,1000,1001,1002,1003,1004,1005,1007] as $actor) {
//     loadDefaultSessionIfMissing($db, $actor);
// }
?>
