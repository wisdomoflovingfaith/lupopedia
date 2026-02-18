<?php
/**
 * FLIP Header Web API — Resolve and return FLIP header from database.
 *
 * GET /api/flip-header.php?path=... | ?url=... | ?content_id=...
 *
 * Returns JSON: {header: "yaml_string", resolved: true|false, channel_id: ...}
 * Use ?format=yaml for raw YAML (Content-Type: text/yaml).
 * For external agents (e.g. Grok browsing lupopedia.com/lupopedia/api/flip-header.php?path=...)
 *
 * LEXA security: parameterized SQL only; path validation (inside repo root, no ..); no concat.
 * Doctrine: PDO_DB only; no schema inference.
 *
 * @package Lupopedia\API
 * @version 4.0.15
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

function validate_path_from_root($repo_root, $path_from_root) {
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
$cols = 'content_id, file_path_from_root, file_last_modified_system_version, file_last_modified_utc, slug, title, content_url, custom_path, content_type, dialog_notes';

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
$edge_row = $db->fetchRow(
    "SELECT left_object_id FROM " . $edges_table . " WHERE left_object_type = 'channel' AND right_object_type = 'content' AND right_object_id = :cid AND edge_type = 'HAS_CONTENT' AND is_deleted = 0 LIMIT 1",
    array('cid' => $cid)
);
$channel_id = ($edge_row && isset($edge_row['left_object_id'])) ? (int) $edge_row['left_object_id'] : null;

$fp = isset($row['file_path_from_root']) ? $row['file_path_from_root'] : '';
$ver = isset($row['file_last_modified_system_version']) && $row['file_last_modified_system_version'] !== null ? $row['file_last_modified_system_version'] : '0000';
$utc = isset($row['file_last_modified_utc']) && $row['file_last_modified_utc'] !== null ? $row['file_last_modified_utc'] : 0;
$utc_str = (string) $utc;
$utc_str = (strlen($utc_str) === 14) ? $utc_str : '00000000000000';
$dialog_notes = isset($row['dialog_notes']) ? trim((string) $row['dialog_notes']) : '';

$lines = array(
    '---',
    '# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)',
    'wolfie.headers: explicit architecture with structured clarity for every file.',
    'file_path_from_root: ' . $fp,
    'file.last_modified_system_version: "' . $ver . '"',
    'file.last_modified_utc: "' . $utc_str . '"',
);
if ($channel_id !== null) {
    $lines[] = 'channel_id: ' . $channel_id;
} else {
    $lines[] = '# channel_id unresolved — requires lupo_contents lookup by application.';
}
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
