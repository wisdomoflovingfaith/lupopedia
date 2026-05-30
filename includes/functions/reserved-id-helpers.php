<?php
/**
 * Reserved ID helpers — RESERVED ID DOCTRINE.
 * PHP 5.3+ compatible; no namespaces. Uses PDO_DB only. array() only.
 *
 * findpuka(): returns the next available primary-key ID in a table within an allowed range.
 * Does NOT use AUTO_INCREMENT or lastInsertId(). For use with lupo_actors, lupo_channels,
 * lupo_actor_channel_roles, lupo_auth_users, and other registry-backed tables.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}

/**
 * Find next available ID in a table for the given primary key column (findpuka equivalent).
 * Scans used IDs in [min_id, max_id] and returns the first gap, or min_id if none used, or null if range exhausted.
 *
 * @param object $db        PDO_DB instance
 * @param string $table     Full table name (e.g. lupo_actors)
 * @param string $pk_column Primary key column name (e.g. actor_id)
 * @param int    $min_id    Minimum ID (inclusive) of allowed range
 * @param int|null $max_id  Maximum ID (inclusive) of allowed range; null = use COALESCE(MAX(pk),0)+1
 * @return int|null Next available ID, or null if range exhausted or db error
 */
function lupo_findpuka($db, $table, $pk_column, $min_id = 1, $max_id = null)
{
    if (!$db || !$table || !$pk_column) {
        return null;
    }
    $quoted_table = $db->quoteIdentifier($table);
    $quoted_pk = $db->quoteIdentifier($pk_column);

    if ($max_id === null) {
        $next = $db->fetchOne("SELECT COALESCE(MAX(" . $quoted_pk . "), 0) + 1 FROM " . $quoted_table, array());
        return $next !== null && $next !== false ? (int) $next : null;
    }

    $min_id = (int) $min_id;
    $max_id = (int) $max_id;
    if ($min_id > $max_id) {
        return null;
    }

    $rows = $db->fetchAll(
        "SELECT " . $quoted_pk . " FROM " . $quoted_table . " WHERE " . $quoted_pk . " >= :lo AND " . $quoted_pk . " <= :hi ORDER BY " . $quoted_pk,
        array('lo' => $min_id, 'hi' => $max_id)
    );
    $used = array();
    foreach ($rows as $row) {
        if (isset($row[$pk_column])) {
            $used[(int) $row[$pk_column]] = true;
        }
    }

    // Root Truth: Check filesystem for used IDs (Doctrine: Filesystem > Database)
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('ABSPATH') ? ABSPATH : '');
    $fs_dir = '';

    if (strpos($table, 'actors') !== false) {
        $fs_dir = $app_root . DIRECTORY_SEPARATOR . (defined('LUPO_ACTORS_DIR') ? LUPO_ACTORS_DIR : 'actors');
    } elseif (strpos($table, 'channels') !== false) {
        $fs_dir = $app_root . DIRECTORY_SEPARATOR . (defined('LUPO_CHANNEL_DIR') ? LUPO_CHANNEL_DIR : 'channels');
    }

    if ($fs_dir !== '' && is_dir($fs_dir)) {
        $files = scandir($fs_dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..')
                continue;
            if (is_numeric($file)) {
                $id = (int) $file;
                if ($id >= $min_id && ($max_id === null || $id <= $max_id)) {
                    $used[$id] = true;
                }
            }
        }
    }

    for ($id = $min_id; $id <= ($max_id !== null ? $max_id : 999999); $id++) {
        if (!isset($used[$id])) {
            return $id;
        }
        if ($max_id === null && $id >= 999999)
            break; // sanity limit
    }
    return null;
}
