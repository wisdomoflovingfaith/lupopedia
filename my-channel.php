<?php
/**
 * My Channel — Lupopedia equivalent of Crafty Syntax "Live Help" operator entry point.
 * Resolves session actor_id, ensures actor has a channel (create if none), redirects to /lupopedia/channels/<channel_id>/
 *
 * Doctrine: No schema changes. Uses lupo_actor_channels, lupo_channels. BIGINT timestamps YYYYMMDDHHIISS. No FK, no triggers.
 */

define('LUPOPEDIA_PATH', __DIR__);
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__));

// Config path detection (same as index.php)
if (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php');
} elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php');
} elseif (@file_exists(LUPOPEDIA_PATH . '/lupopedia-config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', LUPOPEDIA_PATH . '/lupopedia-config.php');
} else {
    require_once LUPOPEDIA_PATH . '/lupo-includes/lupopedia-setup.php';
    exit;
}

require_once LUPOPEDIA_CONFIG_PATH;

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

require_once LUPOPEDIA_PATH . '/lupo-includes/functions/session-helpers.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/functions/redirect-helpers.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/functions/auth-helpers.php';

// Resolve session actor_id (support both current_user and session-only for Phase 1 actors with actor_source_type = 'lupo_auth_users')
$actor_id = null;
$user = current_user();
if ($user && !empty($user['actor_id'])) {
    $actor_id = (int) $user['actor_id'];
} else {
    $actor_id = lupo_validate_session();
}
if (!$actor_id) {
    $login_url = LUPOPEDIA_PUBLIC_PATH . '/login?redirect=' . urlencode(LUPOPEDIA_PUBLIC_PATH . '/my-channel.php');
    lupo_safe_redirect($login_url, 0, 'Please sign in to open My Channel.');
    exit;
}

$db = $GLOBALS['mydatabase'] ?? null;
if (!$db) {
    header('HTTP/1.1 503 Service Unavailable');
    echo 'Database unavailable.';
    exit;
}

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : str_replace('-', '_', LUPO_PREFIX);
$now = lupo_utc_timestamp();

// Query lupo_actor_channels for this actor (non-deleted only)
$sql = "SELECT actor_channel_id, channel_id, updated_ymdhis
        FROM {$table_prefix}actor_channels
        WHERE actor_id = :actor_id AND is_deleted = 0
        ORDER BY updated_ymdhis DESC";
$stmt = $db->prepare($sql);
$stmt->execute([':actor_id' => $actor_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($rows) === 0) {
    // A) Create new channel and link actor to it
    $channel_key = 'my-channel-' . $actor_id . '-' . $now;
    $federation_node_id = defined('LUPO_DEFAULT_NODE_ID') ? (int) LUPO_DEFAULT_NODE_ID : 1;
    $ins_channel = "INSERT INTO {$table_prefix}channels (
        federation_node_id, created_by_actor_id, default_actor_id, department_id,
        channel_key, channel_slug, channel_type, language, channel_name,
        status_flag, created_ymdhis, updated_ymdhis, is_deleted
    ) VALUES (
        :federation_node_id, :created_by_actor_id, 1, 1,
        :channel_key, :channel_slug, 'chat_room', 'en', :channel_name,
        1, :created_ymdhis, :updated_ymdhis, 0
    )";
    $stmt_ins = $db->prepare($ins_channel);
    $stmt_ins->execute([
        ':federation_node_id' => $federation_node_id,
        ':created_by_actor_id' => $actor_id,
        ':channel_key' => $channel_key,
        ':channel_slug' => $channel_key,
        ':channel_name' => 'My Channel',
        ':created_ymdhis' => $now,
        ':updated_ymdhis' => $now,
    ]);
    $channel_id = (int) $db->lastInsertId();
    if ($channel_id <= 0) {
        header('HTTP/1.1 500 Internal Server Error');
        echo 'Could not create channel.';
        exit;
    }
    $ins_ac = "INSERT INTO {$table_prefix}actor_channels (
        actor_id, channel_id, status, start_date, channel_color,
        created_ymdhis, updated_ymdhis, is_deleted
    ) VALUES (
        :actor_id, :channel_id, 'A', :start_date, 'F7FAFF',
        :created_ymdhis, :updated_ymdhis, 0
    )";
    $stmt_ac = $db->prepare($ins_ac);
    $stmt_ac->execute([
        ':actor_id' => $actor_id,
        ':channel_id' => $channel_id,
        ':start_date' => $now,
        ':created_ymdhis' => $now,
        ':updated_ymdhis' => $now,
    ]);
} else {
    // B) Use row with highest updated_ymdhis (first row from ORDER BY updated_ymdhis DESC)
    $channel_id = (int) $rows[0]['channel_id'];
}

$redirect_url = LUPOPEDIA_PUBLIC_PATH . '/channels/' . $channel_id . '/';
lupo_safe_redirect($redirect_url, 0);
exit;
