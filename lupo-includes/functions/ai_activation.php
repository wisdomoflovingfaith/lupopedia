<?php
/**
 * AI Activation Helpers
 * 
 * Handles checking and activating AI agents (actor_id 0-9999).
 * PHP 5.3+ compatible.
 *
 * @package Lupopedia
 * @version 4.0.53
 */

/**
 * PHP 5.3-safe random bytes.
 */
if (!function_exists('lupo_random_bytes')) {
    function lupo_random_bytes($length)
    {
        if (function_exists('random_bytes')) {
            return random_bytes($length);
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length);
            return $bytes !== false ? $bytes : lupo_random_bytes_fallback($length);
        }
        return lupo_random_bytes_fallback($length);
    }
}

if (!function_exists('lupo_random_bytes_fallback')) {
    function lupo_random_bytes_fallback($length)
    {
        $bytes = '';
        for ($i = 0; $i < $length; $i++) {
            $bytes .= chr(mt_rand(0, 255));
        }
        return $bytes;
    }
}

/**
 * Check if an Actor AI is currently running based on session heartbeats.
 * 
 * @param int $actor_id
 * @param object $db PDO_DB instance
 * @param int $heartbeat_seconds Heartbeat threshold (default 300s / 5m)
 * @return bool
 */
function isActorAIRunning($actor_id, $db, $heartbeat_seconds = 300)
{
    if (!$db || $actor_id === null)
        return false;

    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $threshold = gmdate('YmdHis', strtotime("-{$heartbeat_seconds} seconds"));

    $sql = "SELECT COUNT(*) FROM {$prefix}sessions 
            WHERE actor_id = :actor_id 
            AND is_active = 1 
            AND is_expired = 0 
            AND is_revoked = 0 
            AND is_deleted = 0 
            AND last_seen_ymdhis >= :threshold";

    $count = $db->fetchOne($sql, array('actor_id' => $actor_id, 'threshold' => $threshold));
    return (int) $count > 0;
}

/**
 * Ensure an Actor AI is active. If not running, attempt to "activate" it
 * by creating a system session or logging an activation request.
 * 
 * @param int $actor_id
 * @param object $db PDO_DB instance
 * @param string $reason Why it's being activated
 * @return bool True if active or successfully activated
 */
function ensureActorActive($actor_id, $db, $reason = 'system_request')
{
    if (isActorAIRunning($actor_id, $db)) {
        return true;
    }

    // Attempt activation logic
    $now = gmdate('YmdHis');
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    // 1. Verify actor exists and is an active AI agent
    $actor = $db->fetchRow("SELECT * FROM {$prefix}actors WHERE actor_id = :id AND is_active = 1 AND is_deleted = 0", array('id' => $actor_id));
    if (!$actor) {
        return false;
    }

    // 2. Create a system session to represent the "running" state
    // Use the L<actor_id> prefix as per Windsurf's instruction
    $session_id = 'L' . $actor_id . '_' . bin2hex(lupo_random_bytes(8));
    $expires = gmdate('YmdHis', strtotime("+1 hour"));

    $db->insert($prefix . 'sessions', array(
        'session_id' => $session_id,
        'actor_id' => $actor_id,
        'federation_node_id' => 0, // System Node
        'is_active' => 1,
        'security_level' => 'high',
        'system_context' => 'ai_activation',
        'metadata' => json_encode(array('reason' => $reason, 'activated_at' => $now)),
        'created_ymdhis' => $now,
        'updated_ymdhis' => $now,
        'last_seen_ymdhis' => $now,
        'expires_ymdhis' => $expires
    ));

    return true;
}
