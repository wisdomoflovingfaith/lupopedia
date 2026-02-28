<?php
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    $base_path = dirname(dirname(dirname(__FILE__)));
    require_once $base_path . '/lupopedia-config.php';
    require_once $base_path . '/lupo-includes/bootstrap.php';
}

function channel_admin_require_actor() {
    $actor_id = null;
    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;

    if ($authService && method_exists($authService, 'getCurrentUser')) {
        $user = $authService->getCurrentUser();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }

    if (!$actor_id && function_exists('current_user')) {
        $user = current_user();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }

    if (!$actor_id && isset($GLOBALS['lupo_session'])) {
        $actor_id = $GLOBALS['lupo_session']->validateSession();
    }

    if (!$actor_id) {
        $public_path = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
        $redirect = $public_path . '/login?redirect=' . urlencode($public_path . '/channels/1/');
        header('Location: ' . $redirect);
        exit;
    }

    return (int) $actor_id;
}

function channel_admin_require_access($actor_id, $channel_id) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    $has_access = false;
    $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channels WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':actor_id' => $actor_id, ':channel_id' => $channel_id));
    if ($stmt->fetch() !== false) {
        $has_access = true;
    }

    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if (!$has_access && $authService && method_exists($authService, 'isAdmin')) {
        if ($authService->isAdmin($actor_id)) {
            $has_access = true;
        }
    }

    if (!$has_access) {
        header('HTTP/1.0 403 Forbidden');
        echo '<h1>Access denied</h1>';
        exit;
    }
}

function channel_admin_get_channel_info($channel_id) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    $sql = "SELECT channel_id, channel_name, channel_key, channel_slug, status_flag FROM {$table_prefix}channels WHERE channel_id = :channel_id AND is_deleted = 0";
    return $db->fetch($sql, array('channel_id' => $channel_id));
}

function channel_admin_security_headers() {
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com; style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com; frame-src 'self'; connect-src 'self' ws: wss:");
    header("X-Frame-Options: SAMEORIGIN");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: strict-origin-when-cross-origin");
}

function channel_admin_get_csrf_token() {
    if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes(16);
        } else {
            $bytes = '';
            for ($i = 0; $i < 16; $i++) {
                $bytes .= chr(mt_rand(0, 255));
            }
        }
        $_SESSION['csrf_token'] = bin2hex($bytes);
    }
    return $_SESSION['csrf_token'];
}
?>
