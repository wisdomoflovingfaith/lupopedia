<?php
/**
 * Actor Helpers
 * 
 * Procedural wrappers for ActorService with Filesystem > Database primacy.
 * PHP 5.3+ compatible.
 *
 * @package Lupopedia\Functions
 * @version 4.0.53
 */

/**
 * Get actor data with filesystem primacy.
 * 
 * @param int $actor_id
 * @return array|false
 */
function lupo_get_actor($actor_id)
{
    global $lupo_actor_service;

    if (isset($lupo_actor_service)) {
        return $lupo_actor_service->getActor($actor_id);
    }

    // Fallback if service not loaded (e.g. during early bootstrap)
    $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : DatabaseFactory::getConnection();
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    $actor_data = array();
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('ABSPATH') ? ABSPATH : '');
    $lupo_actors_dir = defined('LUPO_ACTORS_DIR') ? LUPO_ACTORS_DIR : 'lupo-actors';
    $who_file = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . $lupo_actors_dir . DIRECTORY_SEPARATOR . (int) $actor_id . DIRECTORY_SEPARATOR . 'WHO.json';

    if ($who_file !== '' && file_exists($who_file)) {
        $json = file_get_contents($who_file);
        $fs_data = json_decode($json, true);
        if ($fs_data && isset($fs_data['whoami'])) {
            $whoami = $fs_data['whoami'];
            $actor_data = array(
                'actor_id' => (int) $actor_id,
                'name' => isset($whoami['name']) ? $whoami['name'] : (isset($whoami['displayName']) ? $whoami['displayName'] : ''),
                'actor_type' => isset($whoami['type']) ? $whoami['type'] : 'user',
                'slug' => isset($whoami['slug']) ? $whoami['slug'] : '',
                'metadata' => $json,
                'is_active' => 1,
                'is_deleted' => 0,
                '_source' => 'filesystem'
            );
        }
    }

    $t = $db->quoteIdentifier($prefix . 'actors');
    $db_row = $db->fetchRow("SELECT * FROM {$t} WHERE actor_id = :id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1", array(':id' => $actor_id));

    if ($db_row) {
        if (empty($actor_data)) {
            $actor_data = $db_row;
            $actor_data['_source'] = 'database';
        } else {
            foreach ($db_row as $key => $val) {
                if (!isset($actor_data[$key]) || $actor_data[$key] === '' || $actor_data[$key] === 0) {
                    $actor_data[$key] = $val;
                }
            }
        }
    }

    return !empty($actor_data) ? $actor_data : false;
}
