<?php
/**
 * FLARE Header Web API — Resolve and return FLARE header from database.
 * 
 * NOTE: This is the successor to flip-header.php.
 *
 * GET /api/flare-header.php?path=... | ?url=... | ?content_id=...
 *
 * Returns JSON: {header: "yaml_string", resolved: true|false, channel_id: ...}
 * Use ?format=yaml for raw YAML (Content-Type: text/yaml).
 *
 * @package Lupopedia\API
 * @version 4.1.0
 */

$config_paths = array(
    dirname(dirname(__DIR__)) . '/lupopedia-config.php',
    dirname(__DIR__) . '/lupopedia-config.php',
    __DIR__ . '/../lupopedia-config.php'
);

$config_loaded = false;
foreach ($config_paths as $p) {
    if (file_exists($p)) {
        require_once $p;
        $config_loaded = true;
        break;
    }
}

if (!$config_loaded) {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    http_response_code(500);
    echo json_encode(array('error' => 'Config not found'));
    exit;
}

if (!defined('LUPOPEDIA_CONFIG_LOADED') || !class_exists('DatabaseFactory')) {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    http_response_code(500);
    echo json_encode(array('error' => 'Bootstrap not loaded'));
    exit;
}

$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$repo_root = defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : (defined('ABSPATH') ? ABSPATH : realpath(__DIR__ . '/..'));

function validate_path_from_root($repo_root, $path_from_root)
{
    if (!is_string($path_from_root) || trim($path_from_root) === '' || strpos($path_from_root, '..') !== false) {
        return null;
    }
    $path = trim(str_replace('\\', '/', $path_from_root));
    $path = ltrim($path, '/');
    if ($path === '') {
        return null;
    }
    if ($repo_root && $repo_root !== '') {
        $resolved = realpath($repo_root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path));
        $real_root = realpath($repo_root);
        if ($resolved === false || $real_root === false) {
            return null;
        }
        if ($resolved !== $real_root && strpos($resolved, $real_root . DIRECTORY_SEPARATOR) !== 0) {
            return null;
        }
    }
    return $path;
}

$path = isset($_GET['path']) ? (string) $_GET['path'] : null;
$url = isset($_GET['url']) ? (string) $_GET['url'] : null;
$content_id = isset($_GET['content_id']) ? (int) $_GET['content_id'] : null;

$row = null;
$contents_table = $db->quoteIdentifier($prefix . 'contents');
$cols = 'content_id, content_parent_id, federation_node_id, actor_id, title, slug, custom_path, content_type, content_url, default_collection_id, status, visibility, view_count, share_count, like_count, comment_count, triage_status, version_number, file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes, is_active';

if ($path !== null && $path !== '') {
    $sanitized = validate_path_from_root($repo_root, $path);
    if ($sanitized === null) {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid path'));
        exit;
    }
    $row = $db->fetchRow(
        "SELECT " . $cols . " FROM " . $contents_table . " WHERE file_path_from_root = :p AND is_deleted = 0 LIMIT 1",
        array('p' => $sanitized)
    );
} elseif ($url !== null && $url !== '') {
    $row = $db->fetchRow(
        "SELECT " . $cols . " FROM " . $contents_table . " WHERE (content_url = :u OR custom_path = :u2) AND is_deleted = 0 LIMIT 1",
        array('u' => $url, 'u2' => $url)
    );
} elseif ($content_id > 0) {
    $row = $db->fetchRow(
        "SELECT " . $cols . " FROM " . $contents_table . " WHERE content_id = :cid AND is_deleted = 0 LIMIT 1",
        array('cid' => $content_id)
    );
} else {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    http_response_code(400);
    echo json_encode(array('error' => 'Provide path, url, or content_id'));
    exit;
}

if (!$row || !isset($row['content_id'])) {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    http_response_code(404);
    echo json_encode(array('error' => 'Content not found'));
    exit;
}

$cid = (int) $row['content_id'];
$edges_table = $db->quoteIdentifier($prefix . 'edges');
$contents_table = $db->quoteIdentifier($prefix . 'contents');

$edge_row = $db->fetchRow(
    "SELECT left_object_id FROM " . $edges_table . " WHERE left_object_type = 'channel' AND right_object_id = :cid AND edge_type = 'HAS_CONTENT' AND is_deleted = 0 LIMIT 1",
    array('cid' => $cid)
);
$channel_id = ($edge_row && isset($edge_row['left_object_id'])) ? (int) $edge_row['left_object_id'] : 42;

// Fetch outbound edges
$outbound = $db->fetchAll(
    "SELECT COALESCE(c.file_path_from_root, CAST(e.right_object_id AS CHAR)) as target, e.edge_type, e.flare_weight, e.flare_reason 
     FROM " . $edges_table . " e
     LEFT JOIN " . $contents_table . " c ON e.right_object_id = c.content_id AND c.is_deleted = 0
     WHERE e.left_object_id = :cid AND e.is_deleted = 0",
    array('cid' => $cid)
);

// Fetch inbound edges
$inbound = $db->fetchAll(
    "SELECT COALESCE(c.file_path_from_root, CAST(e.left_object_id AS CHAR)) as source, e.edge_type, e.flare_weight, e.flare_reason 
     FROM " . $edges_table . " e
     LEFT JOIN " . $contents_table . " c ON e.left_object_id = c.content_id AND c.is_deleted = 0
     WHERE e.right_object_id = :cid AND e.is_deleted = 0",
    array('cid' => $cid)
);

$registry_table = $db->quoteIdentifier($prefix . 'registry');
$registry_row = $db->fetchRow(
    "SELECT registry_id FROM " . $registry_table . " WHERE entity_type = 'content' AND entity_index = :cid AND is_deleted = 0 LIMIT 1",
    array('cid' => $cid)
);
$registry_id = ($registry_row && isset($registry_row['registry_id'])) ? (int) $registry_row['registry_id'] : null;

$fp = isset($row['file_path_from_root']) ? $row['file_path_from_root'] : '';
$ver = isset($row['file_last_modified_system_version']) && $row['file_last_modified_system_version'] !== null ? $row['file_last_modified_system_version'] : '4.1.0';
$utc = isset($row['file_last_modified_utc']) && $row['file_last_modified_utc'] !== null ? $row['file_last_modified_utc'] : 0;
$utc_str = (string) $utc;
$utc8 = (strlen($utc_str) >= 8) ? substr($utc_str, 0, 8) : '00000000';
$utc_full = (strlen($utc_str) === 14) ? $utc_str : '00000000000000';
$dialog_notes = isset($row['dialog_notes']) ? trim((string) $row['dialog_notes']) : '';
$tags = isset($row['tags']) ? $row['tags'] : '["flare", "doctrine"]';
$actor_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';

$lines = array(
    '---',
    '# FLARE Protocol Restructuring (v4.1.0)',
    '# Generated by Lupopedia API v4.1.0 (Three-Part Semantic Split)',
    'flare.headers:',
    '  file_path_from_root: "' . $fp . '"',
    '  system_version: "4.1.0"',
    '  channel_id: ' . $channel_id,
    '  actor_id: ' . ($row['actor_id'] ?? 2035),
    '  last_modified_utc: "' . $utc8 . '"',
    '  delegation_chain: "' . ($row['actor_id'] ?? 2035) . ':10000"',
    '  artifact_type: "' . (strpos($fp, 'doctrine') !== false ? 'doctrine' : 'guide') . '"',
    '  purpose: "' . addslashes($row['title'] ?? '') . '"',
    '  actor_ip: "' . $actor_ip . '"',
    '',
    'flare.edges:',
    '  outbound_edges:',
);

if (empty($outbound)) {
    $lines[] = '    []';
} else {
    foreach ($outbound as $e) {
        $edge_str = '- { to: "' . $e['target'] . '", type: "' . $e['edge_type'] . '", weight: ' . ($e['flare_weight'] ?? '0.50');
        if (!empty($e['flare_reason'])) {
            $edge_str .= ', reason: "' . addslashes($e['flare_reason']) . '"';
        }
        $edge_str .= ' }';
        $lines[] = '    ' . $edge_str;
    }
}

$lines[] = '  inbound_edges:';
if (empty($inbound)) {
    $lines[] = '    []';
} else {
    foreach ($inbound as $e) {
        $edge_str = '- { from: "' . $e['source'] . '", type: "' . $e['edge_type'] . '", weight: ' . ($e['flare_weight'] ?? '0.50');
        if (!empty($e['flare_reason'])) {
            $edge_str .= ', reason: "' . addslashes($e['flare_reason']) . '"';
        }
        $edge_str .= ' }';
        $lines[] = '    ' . $edge_str;
    }
}

$lines[] = '  semantic_tags: ' . $tags;
$lines[] = '';
$lines[] = 'flare.footer:';
$lines[] = '  view_count: ' . ($row['view_count'] ?? 0);
$lines[] = '  like_count: ' . ($row['like_count'] ?? 0);
$lines[] = '  share_count: ' . ($row['share_count'] ?? 0);
$lines[] = '  comment_count: ' . ($row['comment_count'] ?? 0);
$lines[] = '  last_verified: "' . $utc8 . '"';
$lines[] = '  last_verified_by: "Lupopedia API"';
$lines[] = '';
$lines[] = '# Legacy X-Lupo Headers (Backward Compatibility)';
$lines[] = 'X-Lupo-File-Path: ' . $fp;
$lines[] = 'X-Lupo-Version: "' . $ver . '"';
$lines[] = 'X-Lupo-UTC-Timestamp: "' . $utc_full . '"';
$lines[] = 'X-Lupo-Channel: ' . $channel_id;
$lines[] = 'X-Lupo-Actor-ID: ' . ($row['actor_id'] ?? 2035);
$lines[] = 'X-Lupo-Tags: ' . $tags;
$lines[] = 'X-Lupo-View-Count: ' . ($row['view_count'] ?? 0);
$lines[] = 'X-Lupo-Share-Count: ' . ($row['share_count'] ?? 0);
$lines[] = 'X-Lupo-Registry-ID: ' . ($registry_id ?? $cid);

if ($dialog_notes !== '') {
    $lines[] = '';
    $lines[] = '# optional dialog (from dialog_notes; not for inference)';
    $lines[] = $dialog_notes;
}
$lines[] = '---';

$header = implode("\n", $lines);
$resolved = ($channel_id !== null);

$format_yaml = isset($_GET['format']) && trim(strtolower((string) $_GET['format'])) === 'yaml';

if ($format_yaml) {
    header('Content-Type: text/yaml');
    header('Access-Control-Allow-Origin: *');
    echo $header;
} else {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    echo json_encode(array(
        'header' => $header,
        'resolved' => $resolved,
        'channel_id' => $channel_id,
    ));
}
