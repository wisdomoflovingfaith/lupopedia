<?php
/**
 * Path resolution for agents.php with realpath containment. Ensures resolved paths
 * stay under a given root (e.g. LUPO_ACTORS_DIR or LUPO_CHANNELS_DIR). Use for safe file access.
 *
 * @package Lupopedia
 */

class Resolver
{
    /**
     * Resolve actor workspace path: lupo-actors/{actor_id}/. Returns realpath or null if invalid.
     *
     * @param string $base_path LUPOPEDIA_ABSPATH
     * @param string $actors_dir LUPO_ACTORS_DIR
     * @param int    $actor_id
     * @return string|null Real path or null if not under base
     */
    public static function actorPath($base_path, $actors_dir, $actor_id)
    {
        $root = rtrim($base_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $actors_dir);
        $root_real = @realpath($root);
        if ($root_real === false) {
            return null;
        }
        $safe_id = (string) (int) $actor_id;
        if ($safe_id !== (string) $actor_id) {
            return null;
        }
        $candidate = $root_real . DIRECTORY_SEPARATOR . $safe_id;
        $resolved = @realpath($candidate);
        if ($resolved === false) {
            $resolved = $candidate;
        }
        if (strpos($resolved, $root_real) !== 0) {
            return null;
        }
        return $resolved;
    }

    /**
     * Resolve actor workspace path by relative dir (e.g. lupo-actors/system). For name-based dirs.
     * Returns real path if under actors root; otherwise null.
     *
     * @param string $base_path LUPOPEDIA_ABSPATH
     * @param string $actor_dir_rel Relative dir from registry (e.g. lupo-actors/system)
     * @return string|null Real path or null if invalid
     */
    public static function actorPathByDir($base_path, $actor_dir_rel)
    {
        $base_path = rtrim($base_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $candidate = $base_path . str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $actor_dir_rel);
        $resolved = @realpath($candidate);
        if ($resolved === false) {
            $resolved = $candidate;
        }
        $actors_dir = defined('LUPO_ACTORS_DIR') ? LUPO_ACTORS_DIR : 'lupo-actors';
        $root = $base_path . str_replace('/', DIRECTORY_SEPARATOR, $actors_dir);
        $root_real = @realpath($root);
        if ($root_real === false) {
            $root_real = $root;
        }
        if (strpos($resolved, $root_real) !== 0 && strpos($resolved, $root) !== 0) {
            return null;
        }
        return $resolved;
    }

    /**
     * Resolve channel path: lupo-channels/{node_id}/{channel_id}/.
     *
     * @param string $base_path
     * @param string $channels_dir LUPO_CHANNELS_DIR
     * @param int    $node_id
     * @param int    $channel_id
     * @return string|null
     */
    public static function channelPath($base_path, $channels_dir, $node_id, $channel_id)
    {
        $root = rtrim($base_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $channels_dir);
        $root_real = @realpath($root);
        if ($root_real === false) {
            return null;
        }
        $candidate = $root_real . DIRECTORY_SEPARATOR . (int) $node_id . DIRECTORY_SEPARATOR . (int) $channel_id;
        $resolved = @realpath($candidate);
        if ($resolved === false) {
            $resolved = $candidate;
        }
        if (strpos($resolved, $root_real) !== 0) {
            return null;
        }
        return $resolved;
    }

    /**
     * Check that a file path is under root and safe (realpath containment). Use for serving files.
     *
     * @param string $resolved_root Real path of allowed root
     * @param string $input_path    Requested path (e.g. root + /file.md)
     * @return bool
     */
    public static function underRoot($resolved_root, $input_path)
    {
        $candidate = @realpath($input_path);
        if ($candidate === false) {
            return false;
        }
        return strpos($candidate, $resolved_root) === 0;
    }

    /**
     * Resolve file under actor path: only basename .md allowed, realpath containment.
     *
     * @param string $actor_path Real path of actor dir (from actorPath())
     * @param string $filename   Requested filename (will be basename)
     * @return string|null Real path of file or null
     */
    public static function fileUnderActor($actor_path, $filename)
    {
        $base = basename($filename);
        if (!preg_match('/^[a-z0-9_\-\.]+\.md$/i', $base)) {
            return null;
        }
        $root_real = @realpath($actor_path);
        if ($root_real === false) {
            return null;
        }
        $full = $root_real . DIRECTORY_SEPARATOR . $base;
        return self::underRoot($root_real, $full) ? $full : null;
    }
}
