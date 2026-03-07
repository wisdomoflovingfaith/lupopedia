<?php
/**
 * Registry Sync Tool
 *
 * Synchronize registry.json with the lupo_actors database table.
 * Specifically handles the new workspace_path and php_namespace fields.
 *
 * @package Lupopedia
 * @version 4.0.62
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(dirname(__FILE__)) . '/');
}
require_once ABSPATH . 'lupopedia-config.php';

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db && class_exists('DatabaseFactory')) {
    try {
        $db = DatabaseFactory::getConnection();
    } catch (Exception $e) {
        die("Error: Database connection failed.\n");
    }
}
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$registry_path = ABSPATH . 'lupo-database/lupopedia/actors/registry.json';

if (!file_exists($registry_path)) {
    die("Error: Registry file not found: $registry_path\n");
}

$registry = json_decode(file_get_contents($registry_path), true);
if (!is_array($registry) || !isset($registry['actors'])) {
    die("Error: Invalid registry format.\n");
}

echo "Registry Sync Started (v4.0.62)...\n";

$t = $table_prefix . 'actors';
try {
    $stmt = $db->prepare("SELECT actor_id, actor_name, name, actor_type, slug, workspace_path, php_namespace FROM {$t} WHERE is_deleted = 0");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $r) {
        $actor_id = (int) $r['actor_id'];
        $actor_name = $r['actor_name'] ?: ($r['slug'] ?: $r['name']);

        // Find existing or create new entry
        $found = false;
        foreach ($registry['actors'] as $key => &$a) {
            if ((int) $a['actor_id'] === $actor_id) {
                $a['actor_name'] = $actor_name;
                $a['type'] = $r['actor_type'];
                $a['slug'] = $r['slug'];
                $a['dir'] = $r['workspace_path'] ?: $a['dir'];
                $a['workspace_path'] = $r['workspace_path'];
                $a['php_namespace'] = $r['php_namespace'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            echo "  [NEW] Adding actor: $actor_name ($actor_id)\n";
            $registry['actors'][$actor_name] = array(
                'actor_name' => $actor_name,
                'actor_id' => $actor_id,
                'type' => $r['actor_type'],
                'slug' => $r['slug'],
                'dir' => $r['workspace_path'] ?: 'lupo-actors/' . $actor_name,
                'workspace_path' => $r['workspace_path'],
                'php_namespace' => $r['php_namespace']
            );
        }
    }

    $registry['last_updated'] = gmdate('Ymd');
    $registry['schema_version'] = '4.0.62';

    file_put_contents($registry_path, json_encode($registry, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    echo "Done. Registry updated successfully.\n";

} catch (Exception $e) {
    die("Error: Sync failed - " . $e->getMessage() . "\n");
}
