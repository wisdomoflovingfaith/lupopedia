<?php
/**
 * Query live edges from lupo_edges by namespace (Synthesized Documentation Framework).
 * PHP 5.3 compatible. Uses PDO_DB via DatabaseFactory. Min runtime PHP 5.6.
 *
 * Usage: php lupo-bin/query_edges.php [namespace]
 *   php lupo-bin/query_edges.php lupopedia.code.logic
 *   php lupo-bin/query_edges.php   (all edges, no namespace filter)
 *
 * Output: JSON array of edge rows. Namespace filters by context_scope.
 */

$basePath = dirname(__DIR__);
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $basePath . DIRECTORY_SEPARATOR);
}
$configPaths = array(
    $basePath . DIRECTORY_SEPARATOR . 'lupopedia-config.php',
    dirname($basePath) . DIRECTORY_SEPARATOR . 'lupopedia-config.php',
);
$configPath = null;
foreach ($configPaths as $p) {
    if (is_file($p)) {
        $configPath = $p;
        break;
    }
}
if (!$configPath) {
    fwrite(STDERR, "ERROR: lupopedia-config.php not found.\n");
    exit(1);
}
require_once $configPath;
require_once LUPOPEDIA_PATH . 'lupo-includes' . DIRECTORY_SEPARATOR . 'bootstrap.php';

$namespace = isset($argv[1]) ? trim($argv[1]) : '';
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$edges_table = $table_prefix . 'edges';

$db = DatabaseFactory::getConnection();

if ($namespace !== '') {
    $sql = "SELECT edge_id, left_object_type, left_object_id, right_object_type, right_object_id, "
        . "edge_type, edge_category, context_scope, channel_id, weight_score, created_ymdhis, updated_ymdhis "
        . "FROM " . $db->quoteIdentifier($edges_table) . " WHERE is_deleted = 0 AND context_scope = :ns ORDER BY edge_id";
    $rows = $db->fetchAll($sql, array('ns' => $namespace));
} else {
    $sql = "SELECT edge_id, left_object_type, left_object_id, right_object_type, right_object_id, "
        . "edge_type, edge_category, context_scope, channel_id, weight_score, created_ymdhis, updated_ymdhis "
        . "FROM " . $db->quoteIdentifier($edges_table) . " WHERE is_deleted = 0 ORDER BY edge_id";
    $rows = $db->fetchAll($sql, array());
}

echo json_encode($rows, JSON_PRETTY_PRINT);
