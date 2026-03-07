<?php
# file: Actor Service — session: L-LUPO-ANTIGRAVITY — delegation: antigravity:cursor:captain — web_path: http://www.lupopedia.com/docs/api/ActorService
# ---
# flare.headers:
#   flare.version: "1.0"
#   flare.schema: "documentation"
#   file_path_from_root: "lupo-database/lupopedia/content/lupo-app/Services/ActorService.php"
#   last_updated_utc: "20260307"
#   system_version: "4.0.65"
#   actor_name: "antigravity"
#   artifact_type: "code"
#   purpose: "Actor domain service with PHP 5.3 compatibility and name-based workspace support (v4.0.65 update)."
# ---

namespace App\Services;

/**
 * Actor domain service — actor–auth_user linkage, actor creation, slug checks,
 * anonymous allocation, JSRN, and merge.
 */

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

class ActorService
{
    /** @var \PDO_DB */
    private $db;

    /** @var string */
    private $prefix;

    /** @var array|null actor_name => actor data from registry.json */
    private static $registryCache = null;

    public function __construct($db)
    {
        $this->db = $db;
        $this->prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    /**
     * Path to actor registry (actor_name primary). Prefer lupo-database/lupopedia/actors/registry.json.
     *
     * @return string
     */
    protected function getRegistryPath()
    {
        $base = defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : (defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : '');
        if ($base === '' && function_exists('getcwd')) {
            $base = getcwd();
        }
        $base = rtrim(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $base), DIRECTORY_SEPARATOR);
        $reg = $base . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'registry.json';
        if (defined('LUPO_DATABASE_DIR') && LUPO_DATABASE_DIR !== '') {
            $d = rtrim(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, LUPO_DATABASE_DIR), DIRECTORY_SEPARATOR);
            $reg = $base . DIRECTORY_SEPARATOR . $d . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'registry.json';
        }
        return $reg;
    }

    /**
     * Load registry (actor_name keyed). Returns array of actors keyed by actor_name.
     *
     * @return array
     */
    protected function loadRegistry()
    {
        if (self::$registryCache !== null) {
            return self::$registryCache;
        }
        $path = $this->getRegistryPath();
        if (!is_file($path) || !is_readable($path)) {
            self::$registryCache = array();
            return self::$registryCache;
        }
        $raw = @file_get_contents($path);
        $data = $raw !== false ? json_decode($raw, true) : null;
        if (!is_array($data) || !isset($data['actors']) || !is_array($data['actors'])) {
            self::$registryCache = array();
            return self::$registryCache;
        }
        self::$registryCache = $data['actors'];
        return self::$registryCache;
    }

    /**
     * Get actor by actor_name (primary). Registry first, then DB.
     *
     * @param string $actor_name
     * @return array|null Actor data or null
     */
    public function getActorByName($actor_name)
    {
        if ($actor_name === '' || !is_string($actor_name)) {
            return null;
        }
        $actor_name = trim($actor_name);
        $registry = $this->loadRegistry();
        if (isset($registry[$actor_name])) {
            $a = $registry[$actor_name];
            if (!isset($a['actor_name'])) {
                $a['actor_name'] = $actor_name;
            }
            return $a;
        }
        if ($this->db) {
            $t = $this->db->quoteIdentifier($this->prefix . 'actors');
            $row = $this->db->fetchRow(
                "SELECT * FROM {$t} WHERE actor_name = :actor_name AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
                array('actor_name' => $actor_name)
            );
            return is_array($row) ? $row : null;
        }
        return null;
    }

    /**
     * Get actor by actor_id (secondary, backward compatibility).
     *
     * @param int $actor_id
     * @return array|null Actor data or null
     */
    public function getActorById($actor_id)
    {
        $actor_id = (int) $actor_id;
        $registry = $this->loadRegistry();
        foreach ($registry as $nameKey => $a) {
            if (isset($a['actor_id']) && (int) $a['actor_id'] === $actor_id) {
                if (!isset($a['actor_name'])) {
                    $a['actor_name'] = $nameKey;
                }
                return $a;
            }
        }
        if ($this->db) {
            $t = $this->db->quoteIdentifier($this->prefix . 'actors');
            $row = $this->db->fetchRow(
                "SELECT * FROM {$t} WHERE actor_id = :actor_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
                array('actor_id' => $actor_id)
            );
            return is_array($row) ? $row : null;
        }
        return null;
    }

    /**
     * Resolve identifier (actor_name, actor_id, or slug) to actor data.
     *
     * @param string|int $identifier
     * @return array|null Actor data or null
     */
    public function resolveActor($identifier)
    {
        if (is_numeric($identifier)) {
            return $this->getActorById((int) $identifier);
        }
        if (is_string($identifier)) {
            $name = trim($identifier);
            $actor = $this->getActorByName($name);
            if ($actor !== null) {
                return $actor;
            }
            $registry = $this->loadRegistry();
            foreach ($registry as $a) {
                if (isset($a['slug']) && $a['slug'] === $name) {
                    return $a;
                }
            }
        }
        return null;
    }

    /**
     * Get canonical actor_name from any identifier.
     *
     * @param string|int $identifier
     * @return string|null actor_name or null
     */
    public function getActorName($identifier)
    {
        $actor = $this->resolveActor($identifier);
        if ($actor === null) {
            return null;
        }
        return isset($actor['actor_name']) ? $actor['actor_name'] : null;
    }

    /**
     * Validate delegation chain (colon-separated actor names).
     *
     * @param string $chain e.g. "lilith:cursor:captain"
     * @return bool True if all names resolve
     */
    public function validateDelegationChain($chain)
    {
        if (!is_string($chain) || trim($chain) === '') {
            return false;
        }
        $names = explode(':', $chain);
        foreach ($names as $name) {
            $name = trim($name);
            if ($name === '') {
                return false;
            }
            if ($this->getActorByName($name) === null) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get actor directory path (relative to project root). Name-based per ACTOR_PRIMARY_KEY_DOCTRINE.
     * Returns registry dir (e.g. lupo-actors/system) or fallback lupo-actors/{actor_name}.
     *
     * @param string $actor_name
     * @return string
     */
    public function getActorDir($actor_name)
    {
        $actor = $this->getActorByName($actor_name);
        $dir = 'lupo-actors';
        if (defined('LUPO_ACTORS_DIR') && LUPO_ACTORS_DIR !== '') {
            $dir = LUPO_ACTORS_DIR;
        }
        if ($actor !== null && isset($actor['dir']) && $actor['dir'] !== '') {
            return $actor['dir'];
        }
        return $dir . '/' . trim($actor_name);
    }

    /**
     * Get list of actors the given user is permitted to act as (for web actor selector).
     * Non-admin: user's own actor plus any actors paired to that user (paired_actor_id = user's actor_id).
     * Admin: all non-deleted actors.
     *
     * @param int  $authUserId auth_user_id of the logged-in user
     * @param bool $isAdmin    whether the user has admin role
     * @return array List of arrays with keys actor_id, actor_name, name, actor_type
     */
    public function getActorsUserCanActAs($authUserId, $isAdmin = false)
    {
        $authUserId = (int) $authUserId;
        if ($authUserId <= 0 || !$this->db) {
            return array();
        }
        $t = $this->db->quoteIdentifier($this->prefix . 'actors');
        $userActorId = 0;
        $userRow = $this->db->fetchRow(
            "SELECT actor_id, actor_name, name, actor_type FROM {$t} WHERE actor_source_type = 'user' AND actor_source_id = :aid AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('aid' => $authUserId)
        );
        if ($userRow) {
            $userActorId = (int) $userRow['actor_id'];
        }
        $list = array();
        if ($isAdmin) {
            $rows = $this->db->fetchAll(
                "SELECT actor_id, actor_name, name, actor_type FROM {$t} WHERE (is_deleted = 0 OR is_deleted IS NULL) ORDER BY actor_id ASC"
            );
            foreach ($rows as $r) {
                $list[] = array(
                    'actor_id' => (int) $r['actor_id'],
                    'actor_name' => isset($r['actor_name']) ? $r['actor_name'] : (isset($r['name']) ? $r['name'] : ''),
                    'name' => isset($r['name']) ? $r['name'] : (isset($r['actor_name']) ? $r['actor_name'] : ''),
                    'actor_type' => isset($r['actor_type']) ? $r['actor_type'] : 'user',
                );
            }
            return $list;
        }
        if ($userActorId > 0) {
            $list[] = array(
                'actor_id' => $userActorId,
                'actor_name' => isset($userRow['actor_name']) ? $userRow['actor_name'] : (isset($userRow['name']) ? $userRow['name'] : ''),
                'name' => isset($userRow['name']) ? $userRow['name'] : (isset($userRow['actor_name']) ? $userRow['actor_name'] : ''),
                'actor_type' => isset($userRow['actor_type']) ? $userRow['actor_type'] : 'user',
            );
            $paired = $this->db->fetchAll(
                "SELECT actor_id, actor_name, name, actor_type FROM {$t} WHERE paired_actor_id = :uid AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY actor_id ASC",
                array('uid' => $userActorId)
            );
            foreach ($paired as $r) {
                $list[] = array(
                    'actor_id' => (int) $r['actor_id'],
                    'actor_name' => isset($r['actor_name']) ? $r['actor_name'] : (isset($r['name']) ? $r['name'] : ''),
                    'name' => isset($r['name']) ? $r['name'] : (isset($r['actor_name']) ? $r['actor_name'] : ''),
                    'actor_type' => isset($r['actor_type']) ? $r['actor_type'] : 'agent',
                );
            }
        }
        return $list;
    }

    /**
     * Get actor data, preferring filesystem WHO.json over database (Doctrine: Root Truth = Filesystem).
     * 
     * @param int $actor_id Actor ID
     * @return array|false Actor data or false
     */
    public function getActor($actor_id)
    {
        $actor_id = (int) $actor_id;
        if ($actor_id <= 0)
            return false;

        $actor_data = array();

        // 1. Root Truth: Filesystem (Prefer Name-Based per ACTOR_PRIMARY_KEY_DOCTRINE)
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('ABSPATH') ? ABSPATH : '');
        $lupo_actors_dir = defined('LUPO_ACTORS_DIR') ? LUPO_ACTORS_DIR : 'lupo-actors';

        $actor_name = null;
        $registry = $this->loadRegistry();
        foreach ($registry as $nameKey => $a) {
            if (isset($a['actor_id']) && (int) $a['actor_id'] === $actor_id) {
                $actor_name = $nameKey;
                break;
            }
        }

        $who_file = '';
        if ($actor_name !== null) {
            $who_file = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . $lupo_actors_dir . DIRECTORY_SEPARATOR . $actor_name . DIRECTORY_SEPARATOR . 'WHO.json';
        }

        // Fallback to legacy ID-based if name-based not found
        if ($who_file === '' || !file_exists($who_file)) {
            $who_file = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . $lupo_actors_dir . DIRECTORY_SEPARATOR . $actor_id . DIRECTORY_SEPARATOR . 'WHO.json';
        }

        if ($who_file !== '' && file_exists($who_file)) {
            $json = file_get_contents($who_file);
            $fs_data = json_decode($json, true);
            if ($fs_data && isset($fs_data['whoami'])) {
                $whoami = $fs_data['whoami'];
                $actor_data = array(
                    'actor_id' => $actor_id,
                    'name' => isset($whoami['name']) ? $whoami['name'] : (isset($whoami['displayName']) ? $whoami['displayName'] : ''),
                    'actor_type' => isset($whoami['type']) ? $whoami['type'] : 'user',
                    'slug' => isset($whoami['slug']) ? $whoami['slug'] : '',
                    'metadata' => $json, // Source JSON
                    'created_ymdhis' => isset($whoami['created_utc']) ? str_replace(array('-', ':', ' '), '', $whoami['created_utc']) : 0,
                    'updated_ymdhis' => isset($whoami['updated_utc']) ? str_replace(array('-', ':', ' '), '', $whoami['updated_utc']) : 0,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    '_source' => 'filesystem'
                );
            }
        }

        // 2. Database Secondary
        $t = $this->db->quoteIdentifier($this->prefix . 'actors');
        $db_row = $this->db->fetchRow(
            "SELECT * FROM {$t} WHERE actor_id = :actor_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array(':actor_id' => $actor_id)
        );

        if ($db_row) {
            if (empty($actor_data)) {
                $actor_data = $db_row;
                $actor_data['_source'] = 'database';
            } else {
                // Merge DB data if not in FS, but FS takes precedence
                foreach ($db_row as $key => $val) {
                    if (!isset($actor_data[$key]) || $actor_data[$key] === '' || $actor_data[$key] === 0) {
                        $actor_data[$key] = $val;
                    }
                }
            }
        }

        return !empty($actor_data) ? $actor_data : false;
    }

    /**
     * Get auth_user_id for an actor (when actor is human or paired human).
     * For human: actor_source_type = 'user' -> actor_source_id.
     * For agent with paired_actor_id: recurse to paired human.
     *
     * @param int|string|array $actor actor_id, actor_name, or actor row
     * @return int|null auth_user_id or null
     */
    public function getAuthUserIdForActor($actor)
    {
        $data = null;
        if (is_array($actor)) {
            $data = $actor;
        } elseif (is_numeric($actor)) {
            $data = $this->getActorById((int) $actor);
        } elseif (is_string($actor)) {
            $data = $this->getActorByName(trim($actor));
        }
        if (!$data || !is_array($data)) {
            return null;
        }
        $srcType = isset($data['actor_source_type']) ? $data['actor_source_type'] : '';
        if (($srcType === 'user' || $srcType === 'lupo_auth_users') && isset($data['actor_source_id']) && (int) $data['actor_source_id'] > 0) {
            return (int) $data['actor_source_id'];
        }
        $type = isset($data['actor_type']) ? $data['actor_type'] : '';
        $paired = isset($data['paired_actor_id']) ? (int) $data['paired_actor_id'] : 0;
        if ($paired > 0 && ($type === 'agent' || $type === 'ide_agent')) {
            return $this->getAuthUserIdForActor($paired);
        }
        return null;
    }

    /**
     * Get full actor context including auth user (when available).
     *
     * @param int|string|array $actor actor_id, actor_name, or actor row
     * @param object|null $authService App\Auth\AuthService instance (optional; for auth_user row)
     * @return array|null array('actor' => row, 'auth_user' => row|null, 'auth_user_id' => int|null) or null
     */
    public function getActorContext($actor, $authService = null)
    {
        $data = null;
        if (is_array($actor)) {
            $data = $actor;
        } elseif (is_numeric($actor)) {
            $data = $this->getActorById((int) $actor);
        } elseif (is_string($actor)) {
            $data = $this->getActorByName(trim($actor));
        }
        if (!$data || !is_array($data)) {
            return null;
        }
        $authUserId = $this->getAuthUserIdForActor($data);
        $authUser = null;
        if ($authUserId && $authService && method_exists($authService, 'getUserByAuthUserId')) {
            $authUser = $authService->getUserByAuthUserId($authUserId);
        }
        return array(
            'actor' => $data,
            'auth_user' => $authUser,
            'auth_user_id' => $authUserId,
        );
    }

    /**
     * Get actor_id for a given auth_user_id (actor_source_type = 'user').
     *
     * @param int $authUserId Auth user ID
     * @return int|false Actor ID or false
     */
    public function getActorIdFromAuthUserId($authUserId)
    {
        $authUserId = (int) $authUserId;
        if ($authUserId <= 0) {
            return false;
        }
        $t = $this->db->quoteIdentifier($this->prefix . 'actors');
        $row = $this->db->fetchRow(
            "SELECT actor_id FROM {$t} WHERE actor_source_type = 'user' AND actor_source_id = :auth_user_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('auth_user_id' => $authUserId)
        );
        return $row ? (int) $row['actor_id'] : false;
    }

    /**
     * Get auth_user_id (actor_source_id) for a given actor_id when actor_source_type = 'user'.
     *
     * @param int $actorId Actor ID
     * @return int|false Auth user ID or false
     */
    public function getAuthUserIdFromActorId($actorId)
    {
        $actorId = (int) $actorId;
        if ($actorId <= 0) {
            return false;
        }
        $t = $this->db->quoteIdentifier($this->prefix . 'actors');
        $row = $this->db->fetchRow(
            "SELECT actor_source_id as auth_user_id FROM {$t} WHERE actor_id = :actor_id AND actor_source_type = 'user' AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('actor_id' => $actorId)
        );
        return $row ? (int) $row['auth_user_id'] : false;
    }

    /**
     * Create actor record for an auth user. Slug derived from email; unique via actorSlugExists.
     *
     * @param int $authUserId Auth user ID
     * @param string $email Email (used for slug)
     * @param string $displayName Display name (used for name)
     * @return int|false New actor_id or false
     */
    public function createActorForAuthUser($authUserId, $email, $displayName)
    {
        $authUserId = (int) $authUserId;
        if ($authUserId <= 0 || $email === '') {
            return false;
        }
        $now = class_exists('\timestamp_ymdhis') ? \timestamp_ymdhis::now() : (int) gmdate('YmdHis');
        $emailNormalized = strtolower(trim($email));
        $slug = str_replace('@', '-at-', $emailNormalized);
        $slug = str_replace('.', '-', $slug);
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        $baseSlug = $slug;
        $counter = 1;
        while ($this->actorSlugExists($slug)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        $emailLocal = strpos($emailNormalized, '@') !== false ? substr($emailNormalized, 0, strpos($emailNormalized, '@')) : $emailNormalized;
        $name = $displayName !== '' ? $displayName : $emailLocal;
        try {
            $actor_id = function_exists('lupo_findpuka') ? lupo_findpuka($this->db, $this->prefix . 'actors', 'actor_id', 1, null) : null;
            if ($actor_id === null) {
                return false;
            }
            $ok = $this->db->insert($this->prefix . 'actors', array(
                'actor_id' => $actor_id,
                'actor_name' => $slug, // actor_name is primary key
                'actor_type' => 'user',
                'slug' => $slug,
                'name' => $name,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_active' => 1,
                'is_deleted' => 0,
                'actor_source_id' => $authUserId,
                'actor_source_type' => 'user',
            ));
            return $ok !== false ? $actor_id : false;
        } catch (\Exception $e) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('ActorService: createActorForAuthUser failed: ' . $e->getMessage());
            }
            return false;
        }
    }

    /**
     * Check if an actor slug already exists (is_deleted = 0).
     *
     * @param string $slug Slug to check
     * @return bool
     */
    public function actorSlugExists($slug)
    {
        if ($slug === '') {
            return false;
        }
        $t = $this->db->quoteIdentifier($this->prefix . 'actors');
        $row = $this->db->fetchRow(
            "SELECT 1 FROM {$t} WHERE slug = :slug AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('slug' => $slug)
        );
        return $row !== null;
    }

    /**
     * Allocate next available anonymous actor_id in [1000, 9999].
     *
     * @return int|null Allocated actor_id or null if exhausted
     */
    public function allocateAnonymousActorId()
    {
        $t = $this->db->quoteIdentifier($this->prefix . 'actors');
        $rows = $this->db->fetchAll(
            "SELECT actor_id FROM {$t} WHERE actor_id BETWEEN 1000 AND 9999 ORDER BY actor_id",
            array()
        );
        $ids = array();
        foreach ($rows as $row) {
            $ids[] = (int) $row['actor_id'];
        }
        $expected = 1000;
        foreach ($ids as $actor_id) {
            if ($actor_id > $expected) {
                return $expected;
            }
            if ($actor_id === $expected) {
                $expected++;
            }
        }
        return $expected <= 9999 ? $expected : null;
    }

    /**
     * Get or allocate JSRN for actor. Uses metadata column (TOON: metadata text) for $.jsrn.
     * If metadata is JSON-like, JSON_EXTRACT works in MySQL; otherwise adapt to your schema.
     *
     * @param int $actorId Actor ID
     * @return int Assigned jsrn
     */
    public function getOrAllocateJsrnForActor($actorId)
    {
        $actorId = (int) $actorId;
        $t = $this->db->quoteIdentifier($this->prefix . 'actors');
        $row = $this->db->fetchRow(
            "SELECT JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.jsrn')) AS jsrn FROM {$t} WHERE actor_id = :actor_id LIMIT 1",
            array('actor_id' => $actorId)
        );
        if ($row && isset($row['jsrn']) && $row['jsrn'] !== null && $row['jsrn'] !== '') {
            return (int) $row['jsrn'];
        }
        $all = $this->db->fetchAll(
            "SELECT DISTINCT CAST(JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.jsrn')) AS UNSIGNED) AS jsrn FROM {$t} WHERE JSON_EXTRACT(metadata, '$.jsrn') IS NOT NULL ORDER BY jsrn",
            array()
        );
        $expected = 1;
        foreach ($all as $r) {
            $jsrn = (int) (isset($r['jsrn']) ? $r['jsrn'] : 0);
            if ($jsrn > $expected) {
                break;
            }
            if ($jsrn === $expected) {
                $expected++;
            }
        }
        $this->db->query(
            "UPDATE {$t} SET metadata = JSON_SET(COALESCE(metadata, JSON_OBJECT()), '$.jsrn', :jsrn) WHERE actor_id = :actor_id",
            array('jsrn' => $expected, 'actor_id' => $actorId)
        );
        return $expected;
    }

    /**
     * Merge anonymous actor into real actor: update sessions/events/dialog to real actor_id,
     * merge metadata, mark temp actor deleted. Uses LUPO_TABLE_PREFIX for all tables.
     *
     * @param int $tempActorId Anonymous actor (1000–9999)
     * @param int $realActorId Real actor (e.g. >= 10000)
     * @return void
     * @throws Exception On DB error
     */
    public function mergeAnonymousActorIntoRealActor($tempActorId, $realActorId)
    {
        $tempActorId = (int) $tempActorId;
        $realActorId = (int) $realActorId;
        $p = $this->prefix;
        $this->db->beginTransaction();
        try {
            $updateTables = array(
                $p . 'sessions' => 'actor_id',
                $p . 'actor_events' => 'actor_id',
                $p . 'session_events' => 'actor_id',
                $p . 'tab_events' => 'actor_id',
                $p . 'content_events' => 'actor_id',
                $p . 'world_events' => 'actor_id',
                $p . 'dialog_messages' => 'from_actor_id',
            );
            foreach ($updateTables as $table => $column) {
                $t = $this->db->quoteIdentifier($table);
                $col = $this->db->quoteIdentifier($column);
                $this->db->query(
                    "UPDATE {$t} SET {$col} = :real_actor_id WHERE {$col} = :temp_actor_id",
                    array('real_actor_id' => $realActorId, 'temp_actor_id' => $tempActorId)
                );
            }
            $actorsT = $this->db->quoteIdentifier($p . 'actors');
            $tempRow = $this->db->fetchRow("SELECT metadata FROM {$actorsT} WHERE actor_id = :actor_id LIMIT 1", array('actor_id' => $tempActorId));
            $tempMeta = array();
            if ($tempRow && !empty($tempRow['metadata'])) {
                $decoded = json_decode($tempRow['metadata'], true);
                $tempMeta = is_array($decoded) ? $decoded : array();
            }
            $realRow = $this->db->fetchRow("SELECT metadata FROM {$actorsT} WHERE actor_id = :actor_id LIMIT 1", array('actor_id' => $realActorId));
            $realMeta = array();
            if ($realRow && !empty($realRow['metadata'])) {
                $decoded = json_decode($realRow['metadata'], true);
                $realMeta = is_array($decoded) ? $decoded : array();
            }
            $mergedMeta = array_merge($tempMeta, $realMeta);
            $this->db->query(
                "UPDATE {$actorsT} SET metadata = :metadata WHERE actor_id = :real_actor_id",
                array('metadata' => json_encode($mergedMeta), 'real_actor_id' => $realActorId)
            );
            $this->db->query(
                "UPDATE {$actorsT} SET metadata = JSON_SET(COALESCE(metadata, JSON_OBJECT()), '$.merged_into', :real_actor_id), is_deleted = 1 WHERE actor_id = :temp_actor_id",
                array('real_actor_id' => $realActorId, 'temp_actor_id' => $tempActorId)
            );
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
