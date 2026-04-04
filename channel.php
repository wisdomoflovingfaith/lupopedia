<?php
/**
 * Standalone minimalist channel chat page (full fallback-friendly JS).
 * Does not replace the main /channels/{id}/ layout (index.php + channels-controller).
 *
 * URL: channel.php?channel_id=N[&thread_id=M] or pretty /channel-chat/N/ (see .htaccess).
 *
 * @package Lupopedia
 */

define('LUPOPEDIA_PATH', __DIR__);
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__));

$lupopediaConfigPath = null;
if (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php')) {
    $lupopediaConfigPath = dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php';
} elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php')) {
    $lupopediaConfigPath = dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php';
} elseif (@file_exists(LUPOPEDIA_PATH . '/lupopedia-config.php')) {
    $lupopediaConfigPath = LUPOPEDIA_PATH . '/lupopedia-config.php';
}

if ($lupopediaConfigPath === null || !is_file($lupopediaConfigPath)) {
    header('HTTP/1.1 503 Service Unavailable');
    echo 'Configuration not found.';
    exit;
}

require_once $lupopediaConfigPath;

$channel_id = isset($_GET['channel_id']) ? (int) $_GET['channel_id'] : 0;
$thread_id = isset($_GET['thread_id']) ? (int) $_GET['thread_id'] : 0;

if ($channel_id <= 0) {
    header('HTTP/1.0 404 Not Found');
    echo 'Channel not found.';
    exit;
}

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    header('HTTP/1.1 503 Service Unavailable');
    echo 'Database unavailable.';
    exit;
}

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$t_ch = $table_prefix . 'channels';
$channel = $db->fetchRow(
    "SELECT channel_id, channel_name, channel_key FROM {$t_ch} WHERE channel_id = :id AND is_deleted = 0",
    array('id' => $channel_id)
);

if ($channel === null || empty($channel)) {
    header('HTTP/1.0 404 Not Found');
    echo 'Channel not found.';
    exit;
}

$actor_id = 0;
$authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
if ($authService && is_object($authService) && method_exists($authService, 'getCurrentUser')) {
    $user = $authService->getCurrentUser();
    if ($user && !empty($user['actor_id'])) {
        $actor_id = (int) $user['actor_id'];
    }
}

if ($actor_id <= 0) {
    $pub = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    $pub = rtrim($pub, '/');
    $here = $pub . '/channel.php?channel_id=' . $channel_id;
    if ($thread_id > 0) {
        $here .= '&thread_id=' . $thread_id;
    }
    $login_url = $pub . '/login?redirect=' . rawurlencode($here);
    if (function_exists('lupo_safe_redirect')) {
        lupo_safe_redirect($login_url, 0, 'Please sign in to view this channel.');
    } else {
        header('Location: ' . $login_url);
    }
    exit;
}

$has_channel_access = false;
$t_ac = $table_prefix . 'actor_channels';
$rowMem = $db->fetchRow(
    "SELECT 1 AS o FROM {$t_ac} WHERE actor_id = :a AND channel_id = :c AND is_deleted = 0 LIMIT 1",
    array('a' => $actor_id, 'c' => $channel_id)
);
if ($rowMem !== null) {
    $has_channel_access = true;
}
if (!$has_channel_access && $authService && method_exists($authService, 'isAdmin')) {
    if ($authService->isAdmin($actor_id)) {
        $has_channel_access = true;
    }
}
if (!$has_channel_access) {
    header('HTTP/1.0 403 Forbidden');
    echo 'You do not have access to this channel.';
    exit;
}

$public_path = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
$channel_name = isset($channel['channel_name']) ? $channel['channel_name'] : ('#' . $channel_id);
$csrf = '';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($channel_name); ?> — Lupopedia</title>
    <meta name="csrf-token" content="<?php echo htmlspecialchars($csrf); ?>">
    <meta name="channel-id" content="<?php echo (int) $channel_id; ?>">
    <meta name="thread-id" content="<?php echo (int) $thread_id; ?>">
    <meta name="actor-id" content="<?php echo (int) $actor_id; ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(rtrim($public_path, '/') . '/lupo-ui/css/chat-display.css'); ?>">
    <script src="<?php echo htmlspecialchars(rtrim($public_path, '/') . '/lupo-ui/js/chat-display.js'); ?>"></script>
</head>
<body>
<div class="chat-container">
    <div class="chat-header">
        <h1>#<?php echo htmlspecialchars($channel_name); ?></h1>
        <div class="chat-status" id="connection-status">
            <span class="status-dot connecting"></span> Connecting…
        </div>
    </div>
    <div class="chat-messages" id="chat-messages">
        <div class="loading-indicator">Loading messages…</div>
    </div>
    <div class="typing-indicator" id="typing-indicator" style="display:none;"></div>
    <div class="chat-input-area">
        <textarea id="message-input" placeholder="Type your message…" rows="3"></textarea>
        <span class="gap-spacer"></span>
        <button type="button" id="send-button" class="send-button">Send</button>
    </div>
</div>
<script>
window.CHAT_PAGE = {
    channelId: <?php echo (int) $channel_id; ?>,
    threadId: <?php echo (int) $thread_id; ?>,
    actorId: <?php echo (int) $actor_id; ?>,
    publicPath: <?php echo json_encode($public_path); ?>,
    csrfToken: <?php echo json_encode($csrf); ?>
};
lupoChatDisplayDomReady(function () {
    var el = document.getElementById('chat-messages');
    if (!el || !window.ChatDisplay) { return; }
    var c = new window.ChatDisplay({
        container: el,
        channelId: window.CHAT_PAGE.channelId,
        threadId: window.CHAT_PAGE.threadId,
        actorId: window.CHAT_PAGE.actorId,
        publicPath: window.CHAT_PAGE.publicPath,
        csrfToken: window.CHAT_PAGE.csrfToken,
        pollingInterval: 2000,
        autoScroll: true,
        kairosTickIntervalMs: 300000,
        kairosDepartmentId: 0
    });
    c.init();
});
</script>
</body>
</html>
