<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: functions
  when_updated: "20260406013447"
  file_path_from_root: "includes/functions/ai_agent_integration.php"
  web_path: "http://www.lupopedia.com/lupopedia/includes/functions/ai_agent_integration.php"
  questions_toon: null
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "functions"
  artifact_kind: "ai_agent_integration"
  purpose: "Core AI agent boot helpers — initializeCoreAIAgents, isActorAIRunning, ensureActorActive; registry-backed actor ids; Session authority for activation rows."
  tags: ["ai", "agents", "boot", "timestamp_ymdhis", "functions", "session"]
---
*/

/**
 * Resolve PDO_DB: prefers $GLOBALS['mydatabase'] (e.g. install wizard), else DatabaseFactory.
 *
 * @return PDO_DB|null
 */
function lupo_ai_integration_get_db()
{
    if (isset($GLOBALS['mydatabase']) && $GLOBALS['mydatabase'] instanceof PDO_DB) {
        return $GLOBALS['mydatabase'];
    }
    if (class_exists('DatabaseFactory', false)) {
        try {
            return DatabaseFactory::getConnection();
        } catch (Exception $e) {
            return null;
        }
    }
    return null;
}

/**
 * Ordered core boot agents: lilith, system, wolfie — actor_id and display label from registry.json.
 *
 * @return array List of array('actor_id' => int, 'label' => string)
 */
function lupo_ai_integration_core_agents_ordered()
{
    $keys = array('lilith', 'system', 'wolfie');
    $fallback = array(
        array('actor_id' => 2, 'label' => 'LILITH'),
        array('actor_id' => 0, 'label' => 'SYSTEM'),
        array('actor_id' => 1, 'label' => 'CAPTAIN WOLFIE'),
    );
    $root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : dirname(dirname(__DIR__));
    $path = $root . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'registry.json';
    if (!is_file($path)) {
        return $fallback;
    }
    $raw = @file_get_contents($path);
    if ($raw === false || $raw === '') {
        return $fallback;
    }
    $data = json_decode($raw, true);
    if (!is_array($data) || !isset($data['actors']) || !is_array($data['actors'])) {
        return $fallback;
    }
    $actors = $data['actors'];
    $out = array();
    foreach ($keys as $key) {
        if (!isset($actors[$key]['actor_id'])) {
            continue;
        }
        $id = (int) $actors[$key]['actor_id'];
        if ($key === 'system') {
            $dn = 'SYSTEM';
        } elseif ($key === 'wolfie') {
            $dn = 'CAPTAIN WOLFIE';
        } else {
            $dn = isset($actors[$key]['display_name']) ? trim((string) $actors[$key]['display_name']) : '';
            $dn = $dn !== '' ? strtoupper($dn) : strtoupper($key);
        }
        $out[] = array('actor_id' => $id, 'label' => $dn);
    }
    if (count($out) < 1) {
        return $fallback;
    }
    return $out;
}

/**
 * Ensure App\Auth\Session can load (install wizard may not have run bootstrap).
 *
 * @return void
 */
function lupo_ai_integration_require_session_class()
{
    if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
        define('LUPOPEDIA_CONFIG_LOADED', true);
    }
    if (class_exists('App\\Auth\\Session', false)) {
        return;
    }
    $root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : dirname(dirname(__DIR__));
    $sessionFile = $root . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'Session.php';
    if (is_file($sessionFile)) {
        require_once $sessionFile;
    }
}

/**
 * Initialize core AI agents during system boot (CLI / lifecycle). Uses DatabaseFactory or mydatabase global.
 *
 * @param int|null $lifecycleId Lifecycle ID for ChannelStartupLifecycle logging
 * @return array Keys: success (int), errors (int)
 */
function initializeCoreAIAgents($lifecycleId = null)
{
    echo "🤖 Initializing Core AI Agents (registry: lilith, system, wolfie)...\n";

    $db = lupo_ai_integration_get_db();
    if (!$db) {
        echo "❌ No database connection available for AI agent initialization\n";
        return array('success' => 0, 'errors' => 1);
    }

    $coreAgents = lupo_ai_integration_core_agents_ordered();
    $successCount = 0;
    $errorCount = 0;

    foreach ($coreAgents as $row) {
        $actorId = $row['actor_id'];
        $agentName = $row['label'];
        echo "  🔄 Activating $agentName (Actor $actorId)... ";

        try {
            if (isActorAIRunning($actorId)) {
                echo "✅ Already active\n";
                $successCount++;
                continue;
            }

            $activated = ensureActorActive($actorId, "system_agent_boot_$actorId");

            if ($activated) {
                echo "✅ Activated successfully\n";
                $successCount++;

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

    try {
        if (!class_exists('timestamp_ymdhis', false)) {
            require_once dirname(__DIR__) . '/classes/TimestampYmdhis.php';
        }
        if (!class_exists('IdGenerator', false)) {
            require_once dirname(__DIR__) . '/classes/IdGenerator.php';
        }
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $now = (string) timestamp_ymdhis::now();
        $ctx = json_encode(array('success' => $successCount, 'errors' => $errorCount));
        if ($ctx === false) {
            $ctx = '{}';
        }
        $db->insert(
            $prefix . 'unified_log',
            array(
                'log_id' => IdGenerator::generate(),
                'log_type' => 'ai_agent_boot',
                'log_level' => 'info',
                'log_message' => "Core AI agent initialization completed: $successCount successful, $errorCount failed",
                'log_context' => $ctx,
                'actor_id' => 0,
                'channel_id' => 0,
                'session_id' => null,
                'ip_address' => null,
                'user_agent' => null,
                'created_ymdhis' => $now,
            )
        );
        echo "✅ AI agent initialization event logged\n\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to log initialization event: " . $e->getMessage() . "\n";
    }

    return array('success' => $successCount, 'errors' => $errorCount);
}

/**
 * Check if an Actor AI is currently running (session heartbeat).
 *
 * @param int $actor_id
 * @param int $heartbeat_seconds
 * @return bool
 */
function isActorAIRunning($actor_id, $heartbeat_seconds = 300)
{
    $db = lupo_ai_integration_get_db();
    if (!$db || $actor_id === null) {
        return false;
    }

    if (!class_exists('timestamp_ymdhis', false)) {
        require_once dirname(__DIR__) . '/classes/TimestampYmdhis.php';
    }
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $threshold = (string) timestamp_ymdhis::subtractSeconds(timestamp_ymdhis::now(), (int) $heartbeat_seconds);

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
 * Ensure an Actor AI is active via Session::createEmbedSession plus activation metadata update.
 *
 * @param int $actor_id
 * @param string $reason
 * @return bool
 */
function ensureActorActive($actor_id, $reason = 'system_request')
{
    if (isActorAIRunning($actor_id)) {
        return true;
    }

    $db = lupo_ai_integration_get_db();
    if (!$db) {
        return false;
    }

    if (!class_exists('timestamp_ymdhis', false)) {
        require_once dirname(__DIR__) . '/classes/TimestampYmdhis.php';
    }
    $nowInt = timestamp_ymdhis::now();
    $now = (string) $nowInt;
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    $actor = $db->fetchRow(
        "SELECT * FROM {$prefix}actors WHERE actor_id = :id AND is_active = 1 AND is_deleted = 0",
        array('id' => $actor_id)
    );
    if (!$actor) {
        return false;
    }

    lupo_ai_integration_require_session_class();
    if (!class_exists('App\\Auth\\Session', false)) {
        return false;
    }

    $session = App\Auth\Session::createEmbedSession($db, $actor_id);
    if (!$session) {
        return false;
    }

    $actorName = isset($actor['actor_name']) ? (string) $actor['actor_name'] : '';
    $expires = (string) timestamp_ymdhis::addHours($nowInt, 1);
    $metadata = json_encode(array('reason' => $reason, 'activated_at' => $now));
    if ($metadata === false) {
        $metadata = '{}';
    }

    $db->update(
        $prefix . 'sessions',
        array(
            'actor_name' => $actorName,
            'security_level' => 'high',
            'system_context' => 'ai_activation',
            'status' => 'active',
            'metadata' => $metadata,
            'expires_ymdhis' => $expires,
            'updated_ymdhis' => (int) $now,
        ),
        'session_id = :sid',
        array('sid' => $session->session_id)
    );

    return true;
}
