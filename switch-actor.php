<?php
/**
 * Switch active actor (web). POST actor_id (+ optional save_as_default).
 * Updates session active_actor_id and optionally session.md for CLI sync.
 * Trae IDE doc: Web Authentication and Actor Selection (2026-03-07).
 */

define('LUPOPEDIA_PATH', __DIR__);
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__));

$config_paths = array(
    dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php',
    dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php',
    LUPOPEDIA_PATH . '/lupopedia-config.php',
);
$config_loaded = false;
foreach ($config_paths as $p) {
    if (@file_exists($p)) {
        require_once $p;
        $config_loaded = true;
        break;
    }
}
if (!$config_loaded || !defined('LUPOPEDIA_CONFIG_LOADED')) {
    header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/install.php');
    exit;
}

if (!function_exists('lupo_get_csrf_token')) {
    require_once LUPOPEDIA_PATH . '/lupo-includes/functions/security.php';
}

$authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
$actorService = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
if (!$authService) {
    header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/login');
    exit;
}
$authService->requireLogin();
$user = $authService->getCurrentUser();
if ($user === false || !$actorService) {
    header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/login');
    exit;
}

$base = rtrim(LUPOPEDIA_PUBLIC_PATH, '/');
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : (isset($_GET['redirect']) ? $_GET['redirect'] : ($base . '/admin.php'));
if (strpos($redirect, 'http') !== 0 && strpos($redirect, '/') !== 0) {
    $redirect = $base . '/' . ltrim($redirect, '/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actor_id'])) {
    $token = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '';
    $valid = ($token !== '' && function_exists('lupo_get_csrf_token') && $token === lupo_get_csrf_token());
    if (!$valid) {
        header('Location: ' . $redirect . '?msg=csrf');
        exit;
    }
    $actorId = (int) $_POST['actor_id'];
    $allowed = $actorService->getActorsUserCanActAs($user['auth_user_id'], !empty($user['is_admin']));
    $allowedIds = array();
    foreach ($allowed as $a) {
        $allowedIds[(int) $a['actor_id']] = true;
    }
    if ($actorId > 0 && isset($allowedIds[$actorId])) {
        $authService->setActiveActorId($actorId);
        if (!empty($_POST['save_as_default'])) {
            $authService->setPreferredActorId($actorId);
        }
        $actorData = $actorService->getActorById($actorId);
        $actorName = 'system';
        if (is_array($actorData)) {
            $actorName = isset($actorData['actor_name']) && $actorData['actor_name'] !== '' ? $actorData['actor_name'] : (isset($actorData['name']) ? $actorData['name'] : 'actor_' . $actorId);
        }
        $dbDir = defined('LUPO_DATABASE_DIR') ? LUPO_DATABASE_DIR : 'lupo-database';
        $sessionMd = LUPOPEDIA_PATH . '/' . $dbDir . '/session.md';
        $dir = dirname($sessionMd);
        if (is_dir($dir) || @mkdir($dir, 0755, true)) {
            $content = "actor_name: " . $actorName . "\n";
            $content .= "actor_id: " . $actorId . "\n";
            $content .= "session_id:\n";
            $content .= "channel_id: 0\n";
            $content .= "federation_node_id: 0\n";
            $content .= "context_source: web_actor_selector\n";
            @file_put_contents($sessionMd, $content);
        }
    }
}
header('Location: ' . $redirect);
exit;
