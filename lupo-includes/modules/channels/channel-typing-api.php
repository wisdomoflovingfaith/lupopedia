<?php
/**
 * Channel typing preview API — GET (operator polls) and POST (visitor submits draft).
 * Ephemeral file-based cache; preview text is never stored as a message until submitted.
 * All paths use LUPOPEDIA_PUBLIC_PATH. No references to livehelp_*.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Config not loaded']);
    exit;
}

$app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
$cache_dir = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'channels' . DIRECTORY_SEPARATOR . 'cache';
$stale_seconds = 30;

if (!is_dir($cache_dir)) {
    @mkdir($cache_dir, 0755, true);
}

function channel_typing_cache_path($cache_dir, $channel_id) {
    return $cache_dir . DIRECTORY_SEPARATOR . 'typing_' . (int) $channel_id . '.json';
}

function channel_typing_get($cache_dir, $channel_id, $stale_seconds) {
    $path = channel_typing_cache_path($cache_dir, $channel_id);
    $cutoff_ymdhis = date('YmdHis', time() - (int) $stale_seconds);
    $out = [];
    if (is_file($path)) {
        $raw = @file_get_contents($path);
        if ($raw !== false) {
            $data = json_decode($raw, true);
            if (is_array($data)) {
                foreach ($data as $thread_id => $entry) {
                    if (!is_array($entry) || empty($entry['preview_text'])) {
                        continue;
                    }
                    $updated = isset($entry['updated_ymdhis']) ? (string) $entry['updated_ymdhis'] : '';
                    if ($updated >= $cutoff_ymdhis) {
                        $out[(string) $thread_id] = [
                            'actor_id'       => (int) ($entry['actor_id'] ?? 0),
                            'actor_name'     => isset($entry['actor_name']) ? (string) $entry['actor_name'] : 'Visitor',
                            'preview_text'   => (string) $entry['preview_text'],
                            'updated_ymdhis' => $updated,
                        ];
                    }
                }
            }
        }
    }
    return $out;
}

function channel_typing_post($cache_dir, $channel_id, $dialog_thread_id, $actor_id, $preview_text, $actor_name) {
    $path = channel_typing_cache_path($cache_dir, $channel_id);
    $data = [];
    if (is_file($path)) {
        $raw = @file_get_contents($path);
        if ($raw !== false) {
            $dec = json_decode($raw, true);
            if (is_array($dec)) {
                $data = $dec;
            }
        }
    }
    $tid = (string) (int) $dialog_thread_id;
    $now = date('YmdHis');
    if ($preview_text === '' || strlen($preview_text) === 0) {
        unset($data[$tid]);
    } else {
        $data[$tid] = [
            'actor_id'       => (int) $actor_id,
            'actor_name'     => strlen($actor_name) > 0 ? $actor_name : 'Visitor',
            'preview_text'   => $preview_text,
            'updated_ymdhis' => $now,
        ];
    }
    $ok = @file_put_contents($path, json_encode($data), LOCK_EX) !== false;
    return $ok;
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$channel_id = isset($_GET['channel_id']) ? (int) $_GET['channel_id'] : 0;
if ($channel_id <= 0 && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input') ?: '{}', true) ?: [];
    $channel_id = isset($input['channel_id']) ? (int) $input['channel_id'] : (int) ($_POST['channel_id'] ?? 0);
}
if ($channel_id <= 0) {
    echo json_encode(['error' => 'channel_id required']);
    exit;
}

if ($method === 'GET') {
    $previews = channel_typing_get($cache_dir, $channel_id, $stale_seconds);
    echo json_encode(['previews' => $previews]);
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input') ?: '{}', true) ?: [];
    $dialog_thread_id = isset($input['dialog_thread_id']) ? (int) $input['dialog_thread_id'] : (int) ($_POST['dialog_thread_id'] ?? 0);
    $actor_id = isset($input['actor_id']) ? (int) $input['actor_id'] : (int) ($_POST['actor_id'] ?? 0);
    $preview_text = isset($input['preview_text']) ? (string) $input['preview_text'] : (string) ($_POST['preview_text'] ?? '');
    $actor_name = isset($input['actor_name']) ? (string) $input['actor_name'] : (string) ($_POST['actor_name'] ?? 'Visitor');
    channel_typing_post($cache_dir, $channel_id, $dialog_thread_id, $actor_id, $preview_text, $actor_name);
    echo json_encode(['ok' => true]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
exit;
