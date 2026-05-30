<?php
/**
 * Resolve actor from request: actor_id, actor_name, or actor (slug).
 * Returns array with actor_id and actor_name (actor_name primary per ACTOR_PRIMARY_KEY_DOCTRINE).
 *
 * @package Lupopedia
 */

class ActorLookup
{
    /** @var array slug/name => actor_id (fallback when registry not available) */
    private static $map = array(
        'antigravity' => 42,
        'system'      => 0,
        'wolfie'      => 1,
        'lilith'      => 2038,
        'anubis'      => 19,
        'cursor'      => 1003,
        'root'        => 10000,
        'rose'        => 3,
        'eris'        => 4,
        'metis'       => 5,
        'vishwakarma' => 25,
        'kiro'        => 1000,
        'windsurf'    => 1001,
        'warp'        => 1004,
        'cascade'     => 1005,
    );

    /** @var array|null registry actors keyed by actor_name */
    private static $registry = null;

    /**
     * Load registry from database/lupopedia/actors/registry.json.
     *
     * @return array
     */
    private static function loadRegistry()
    {
        if (self::$registry !== null) {
            return self::$registry;
        }
        $base = defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : (defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : '');
        if ($base === '' && function_exists('getcwd')) {
            $base = getcwd();
        }
        $base = rtrim(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $base), DIRECTORY_SEPARATOR);
        $path = $base . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'registry.json';
        if (defined('LUPO_DATABASE_DIR') && LUPO_DATABASE_DIR !== '') {
            $d = rtrim(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, LUPO_DATABASE_DIR), DIRECTORY_SEPARATOR);
            $path = $base . DIRECTORY_SEPARATOR . $d . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'registry.json';
        }
        if (!is_file($path) || !is_readable($path)) {
            self::$registry = array();
            return self::$registry;
        }
        $raw = @file_get_contents($path);
        $data = $raw !== false ? json_decode($raw, true) : null;
        if (!is_array($data) || !isset($data['actors']) || !is_array($data['actors'])) {
            self::$registry = array();
            return self::$registry;
        }
        self::$registry = $data['actors'];
        return self::$registry;
    }

    /**
     * Resolve actor from GET: actor_id, actor_name, or actor (slug).
     * Returns array('actor_id' => int, 'actor_name' => string) or null.
     *
     * @return array|null
     */
    public static function fromRequest()
    {
        $registry = self::loadRegistry();
        $actors_dir = defined('LUPO_ACTORS_DIR') ? LUPO_ACTORS_DIR : 'actors';
        if (isset($_GET['actor_id']) && is_numeric($_GET['actor_id'])) {
            $id = (int) $_GET['actor_id'];
            foreach ($registry as $name => $a) {
                if (isset($a['actor_id']) && (int) $a['actor_id'] === $id) {
                    $dir = isset($a['dir']) ? $a['dir'] : ($actors_dir . '/' . $name);
                    return array('actor_id' => $id, 'actor_name' => $name, 'dir' => $dir);
                }
            }
            $dir = $actors_dir . '/' . (string) $id;
            return array('actor_id' => $id, 'actor_name' => 'actor_' . $id, 'dir' => $dir);
        }
        $slug = '';
        if (isset($_GET['actor_name']) && is_string($_GET['actor_name'])) {
            $slug = strtolower(trim($_GET['actor_name']));
        } elseif (isset($_GET['actor']) && is_string($_GET['actor'])) {
            $slug = strtolower(trim($_GET['actor']));
        }
        if ($slug !== '') {
            $slug = preg_replace('/[^a-z0-9\-_]/', '', $slug);
            if (isset($registry[$slug])) {
                $a = $registry[$slug];
                $id = isset($a['actor_id']) ? (int) $a['actor_id'] : 0;
                $dir = isset($a['dir']) ? $a['dir'] : ($actors_dir . '/' . $slug);
                return array('actor_id' => $id, 'actor_name' => $slug, 'dir' => $dir);
            }
            $id = self::slugToId($slug);
            if ($id !== null) {
                $dir = $actors_dir . '/' . $slug;
                return array('actor_id' => $id, 'actor_name' => $slug, 'dir' => $dir);
            }
        }
        return null;
    }

    /**
     * @param string $slug
     * @return int|null
     */
    public static function slugToId($slug)
    {
        if (isset(self::$map[$slug])) {
            return (int) self::$map[$slug];
        }
        $registry = self::loadRegistry();
        if (isset($registry[$slug])) {
            return (int) $registry[$slug]['actor_id'];
        }
        return null;
    }

    /**
     * Find actor ID by name (case-insensitive)
     */
    public static function findIdByName($name)
    {
        $slug = strtolower(trim($name));
        $id = self::slugToId($slug);
        if ($id !== null) return $id;

        // Try searching in registry for display name matches
        $registry = self::loadRegistry();
        foreach ($registry as $s => $a) {
            if (isset($a['name']) && strtolower($a['name']) === $slug) {
                return (int)$a['actor_id'];
            }
        }
        return null;
    }

    /**
     * Register slug -> id (e.g. from registry). Merges into static map.
     *
     * @param string $slug
     * @param int    $id
     */
    public static function register($slug, $id)
    {
        self::$map[$slug] = (int) $id;
    }
}
