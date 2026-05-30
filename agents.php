<?php
/**
 * Agents entry point: actor-based content routing with FLARE parse, guards, drift, sync, and hooks.
 * Resolve -> Parse -> Evaluate Guards -> Detect Drift -> Sync if needed -> Execute Hooks -> Render.
 * Canonical identity: Antigravity = actor_id 42. Root user = 10000. Actors under 10000 = AI agents.
 *
 * @package Lupopedia
 */

define('LUPOPEDIA_PATH', __DIR__);
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__));

$config_path = null;
if (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php')) {
    $config_path = dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php';
} elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php')) {
    $config_path = dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php';
} elseif (@file_exists(LUPOPEDIA_PATH . '/lupopedia-config.php')) {
    $config_path = LUPOPEDIA_PATH . '/lupopedia-config.php';
}

if ($config_path === null || !is_readable($config_path)) {
    header('HTTP/1.1 503 Service Unavailable');
    exit('Configuration not found. Run install first.');
}

require_once $config_path;

if (!defined('LUPOPEDIA_CONFIG_LOADED') || !LUPOPEDIA_CONFIG_LOADED) {
    header('HTTP/1.1 503 Service Unavailable');
    exit('Configuration invalid.');
}

$base_path = defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : (defined('ABSPATH') ? ABSPATH : __DIR__ . DIRECTORY_SEPARATOR);
$base_path = rtrim($base_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
$actors_dir = defined('LUPO_ACTORS_DIR') ? LUPO_ACTORS_DIR : 'actors';
$channels_dir = defined('LUPO_CHANNELS_DIR') ? LUPO_CHANNELS_DIR : 'channels';

$classes = array('Resolver', 'FlareParser', 'GuardEvaluator', 'DriftDetector', 'SyncService', 'HookExecutor', 'Renderer', 'ActorLookup');
foreach ($classes as $cls) {
    $f = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $cls . '.php';
    if (is_file($f)) {
        require_once $f;
    }
}

$actor = ActorLookup::fromRequest();
if ($actor === null || !isset($actor['actor_id'])) {
    header('Content-Type: application/json');
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('error' => 'Missing or invalid actor_id, actor_name, or actor'));
    exit;
}
$actor_id = (int) $actor['actor_id'];
$actor_name = isset($actor['actor_name']) ? $actor['actor_name'] : ('actor_' . $actor_id);
$actor_dir = isset($actor['dir']) ? $actor['dir'] : ($actors_dir . '/' . $actor_id);

$root_id = 10000;
$is_ai_agent = ($actor_id < $root_id);
$is_root = ($actor_id === $root_id);

$actor_path = Resolver::actorPathByDir($base_path, $actor_dir);
if ($actor_path === null || !is_dir($actor_path)) {
    $actor_path = Resolver::actorPath($base_path, $actors_dir, $actor_id);
}
if ($actor_path === null) {
    $actor_path = $base_path . str_replace('/', DIRECTORY_SEPARATOR, $actor_dir);
}
if (!is_dir($actor_path)) {
    Renderer::json(array(
        'actor_id'    => $actor_id,
        'actor_name'  => $actor_name,
        'path'        => $actor_path,
        'exists'      => false,
        'is_ai_agent' => $is_ai_agent,
        'is_root'     => $is_root,
        'error'       => 'Actor path invalid or not under root',
    ), 400);
    exit;
}

$file_to_load = null;
if (isset($_GET['file']) && $_GET['file'] !== '') {
    $file_to_load = Resolver::fileUnderActor($actor_path, $_GET['file']);
}
$cf_ray = defined('LUPO_CF_RAY') ? LUPO_CF_RAY : '';
$client_ip = defined('LUPO_CLIENT_IP') ? LUPO_CLIENT_IP : (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');

if ($file_to_load !== null && is_readable($file_to_load)) {
    $content = @file_get_contents($file_to_load);
    if ($content === false) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Error reading file'));
        exit;
    }
    $parsed = FlareParser::parseSafe($content, $base_path);
    $headers = isset($parsed['headers']) ? $parsed['headers'] : array();
    $body = isset($parsed['body']) ? $parsed['body'] : $content;
    $guards_allow = GuardEvaluator::guardsAllow($headers);
    $drift = DriftDetector::detect($headers, '', array());
    $conflicts_log = $actor_path . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'conflicts.log';
    SyncService::applyPolicy($drift, $file_to_load, $conflicts_log, $base_path);
    HookExecutor::run($headers, 'init', $guards_allow, $actor_path, $base_path);
    Renderer::markdown($body, $actor_id, $cf_ray);
    HookExecutor::run($headers, 'close', $guards_allow, $actor_path, $base_path);
    exit;
}

Renderer::json(array(
    'actor_id'    => $actor_id,
    'actor_name'  => $actor_name,
    'path'        => $actor_path,
    'exists'      => is_dir($actor_path),
    'is_ai_agent' => $is_ai_agent,
    'is_root'     => $is_root,
    'client_ip'   => $client_ip,
    'cf_ray'      => $cf_ray,
));
