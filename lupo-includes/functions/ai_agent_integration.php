<?php
/**
 * AI Agent Integration Module
 * 
 * Provides functions for activating and managing AI agents
 * Integrates with system initialization
 * 
 * @author Windsurf (1002)
 * @version 4.0.54
 * @date 2026-03-01
 */

/**
 * Initialize core AI agents during system boot
 * 
 * @param object $db Database connection
 * @param int $lifecycleId Lifecycle ID for logging
 * @return array Success and error counts
 */
function initializeCoreAIAgents($db, $lifecycleId = null)
{
    echo "🤖 Initializing Core AI Agents (LILITH, SYSTEM, CAPTAIN WOLFIE)...\n";

    // Core AI agents that need to be activated
    $coreAIAgents = [
        2 => 'LILITH',      // Actor 2 - Emotional Intelligence Agent
        0 => 'SYSTEM',       // Actor 0 - System Agent
        1 => 'CAPTAIN WOLFIE' // Actor 1 - Leadership Agent
    ];

    $successCount = 0;
    $errorCount = 0;

    foreach ($coreAIAgents as $actorId => $agentName) {
        echo "  🔄 Activating $agentName (Actor $actorId)... ";

        try {
            // Check if AI agent is already running
            if (isActorAIRunning($actorId, $db)) {
                echo "✅ Already active\n";
                $successCount++;
                continue;
            }

            // Attempt to activate AI agent
            $activated = ensureActorActive($actorId, $db, "system_agent_boot_$actorId");

            if ($activated) {
                echo "✅ Activated successfully\n";
                $successCount++;

                // Log activation to lifecycle if provided
                if ($lifecycleId && class_exists('ChannelStartupLifecycle')) {
                    $lifecycle = new ChannelStartupLifecycle();
                    $lifecycle->addDetail(
                        $lifecycleId,
                        0,
                        "ai_agent_activated",
                        "AI agent $agentName (Actor $actorId) activated during system boot"
                    );
                }
            } else {
                echo "❌ Activation failed\n";
                $errorCount++;

                // Log error to lifecycle if provided
                if ($lifecycleId && class_exists('ChannelStartupLifecycle')) {
                    $lifecycle = new ChannelStartupLifecycle();
                    $lifecycle->addDetail(
                        $lifecycleId,
                        0,
                        "ai_activation_failed",
                        "Failed to activate AI agent $agentName (Actor $actorId)"
                    );
                }
            }

        } catch (Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
            $errorCount++;
        }
    }

    echo "\n📊 AI Agent initialization summary:\n";
    echo "  ✅ Successful: $successCount agents\n";
    echo "  ❌ Failed: $errorCount agents\n";

    if ($errorCount > 0) {
        echo "⚠️ Some AI agent initializations failed - check logs for details\n";
    } else {
        echo "🎉 All core AI agents initialized successfully!\n";
    }

    // Log AI initialization event
    try {
        $logSql = "INSERT INTO lupo_channel_logs 
            (channel_id, actor_id, log_type_id, log_text, created_ymdhis) 
            VALUES (0, 0, 1, :log_text, :created_ymdhis)";

        $db->query($logSql, [
            'log_text' => "Core AI agent initialization completed: $successCount successful, $errorCount failed",
            'created_ymdhis' => gmdate('YmdHis')
        ]);

        echo "✅ AI agent initialization event logged\n\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to log initialization event: " . $e->getMessage() . "\n";
    }

    return ['success' => $successCount, 'errors' => $errorCount];
}

/**
 * Check if an Actor AI is currently running
 * 
 * @param int $actor_id
 * @param object $db Database connection
 * @param int $heartbeat_seconds Heartbeat threshold (default 300s / 5m)
 * @return bool
 */
function isActorAIRunning($actor_id, $db, $heartbeat_seconds = 300)
{
    if (!$db || $actor_id === null) {
        return false;
    }

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
 * 
 * @param int $actor_id
 * @param object $db Database connection
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
    if (function_exists('random_bytes')) {
        $session_id = 'L' . $actor_id . '_' . bin2hex(random_bytes(8));
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        $session_id = 'L' . $actor_id . '_' . bin2hex(openssl_random_pseudo_bytes(8));
    } else {
        $session_id = 'L' . $actor_id . '_' . uniqid();
    }

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

?>
