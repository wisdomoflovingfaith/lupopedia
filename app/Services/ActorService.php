<?php

namespace App\Services;

/**
 * Actor domain service — actor–auth_user linkage, actor creation, slug checks,
 * anonymous allocation, JSRN, and merge. Uses PDO_DB and LUPO_TABLE_PREFIX only.
 * No actor_roles, no unified_sessions, no operator tables. Doctrine: all logic in app.
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

    public function __construct($db)
    {
        $this->db = $db;
        $this->prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    /**
     * Get actor_id for a given auth_user_id (actor_source_type = 'user').
     *
     * @param int $authUserId Auth user ID
     * @return int|false Actor ID or false
     */
    public function getActorIdFromAuthUserId(int $authUserId)
    {
        if ($authUserId <= 0) {
            return false;
        }
        $t = $this->db->quoteIdentifier($this->prefix . 'actors');
        $row = $this->db->fetchRow(
            "SELECT actor_id FROM {$t} WHERE actor_source_type = 'user' AND actor_source_id = :auth_user_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            ['auth_user_id' => $authUserId]
        );
        return $row ? (int) $row['actor_id'] : false;
    }

    /**
     * Get auth_user_id (actor_source_id) for a given actor_id when actor_source_type = 'user'.
     *
     * @param int $actorId Actor ID
     * @return int|false Auth user ID or false
     */
    public function getAuthUserIdFromActorId(int $actorId)
    {
        if ($actorId <= 0) {
            return false;
        }
        $t = $this->db->quoteIdentifier($this->prefix . 'actors');
        $row = $this->db->fetchRow(
            "SELECT actor_source_id as auth_user_id FROM {$t} WHERE actor_id = :actor_id AND actor_source_type = 'user' AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            ['actor_id' => $actorId]
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
    public function createActorForAuthUser(int $authUserId, string $email, string $displayName)
    {
        if ($authUserId <= 0 || $email === '') {
            return false;
        }
        $now = class_exists('timestamp_ymdhis') ? timestamp_ymdhis::now() : (int) gmdate('YmdHis');
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
    public function actorSlugExists(string $slug): bool
    {
        if ($slug === '') {
            return false;
        }
        $t = $this->db->quoteIdentifier($this->prefix . 'actors');
        $row = $this->db->fetchRow(
            "SELECT 1 FROM {$t} WHERE slug = :slug AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            ['slug' => $slug]
        );
        return $row !== null;
    }

    /**
     * Allocate next available anonymous actor_id in [1000, 9999].
     *
     * @return int|null Allocated actor_id or null if exhausted
     */
    public function allocateAnonymousActorId(): ?int
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
    public function getOrAllocateJsrnForActor(int $actorId): int
    {
        $t = $this->db->quoteIdentifier($this->prefix . 'actors');
        $row = $this->db->fetchRow(
            "SELECT JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.jsrn')) AS jsrn FROM {$t} WHERE actor_id = :actor_id LIMIT 1",
            ['actor_id' => $actorId]
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
            $jsrn = (int) ($r['jsrn'] ?? 0);
            if ($jsrn > $expected) {
                break;
            }
            if ($jsrn === $expected) {
                $expected++;
            }
        }
        $this->db->query(
            "UPDATE {$t} SET metadata = JSON_SET(COALESCE(metadata, JSON_OBJECT()), '$.jsrn', :jsrn) WHERE actor_id = :actor_id",
            ['jsrn' => $expected, 'actor_id' => $actorId]
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
     * @throws \Exception On DB error
     */
    public function mergeAnonymousActorIntoRealActor(int $tempActorId, int $realActorId): void
    {
        $p = $this->prefix;
        $this->db->beginTransaction();
        try {
            $updateTables = [
                $p . 'sessions' => 'actor_id',
                $p . 'actor_events' => 'actor_id',
                $p . 'session_events' => 'actor_id',
                $p . 'tab_events' => 'actor_id',
                $p . 'content_events' => 'actor_id',
                $p . 'world_events' => 'actor_id',
                $p . 'dialog_messages' => 'from_actor_id',
            ];
            foreach ($updateTables as $table => $column) {
                $t = $this->db->quoteIdentifier($table);
                $col = $this->db->quoteIdentifier($column);
                $this->db->query(
                    "UPDATE {$t} SET {$col} = :real_actor_id WHERE {$col} = :temp_actor_id",
                    ['real_actor_id' => $realActorId, 'temp_actor_id' => $tempActorId]
                );
            }
            $actorsT = $this->db->quoteIdentifier($p . 'actors');
            $tempRow = $this->db->fetchRow("SELECT metadata FROM {$actorsT} WHERE actor_id = :actor_id LIMIT 1", ['actor_id' => $tempActorId]);
            $tempMeta = array();
            if ($tempRow && !empty($tempRow['metadata'])) {
                $decoded = json_decode($tempRow['metadata'], true);
                $tempMeta = is_array($decoded) ? $decoded : array();
            }
            $realRow = $this->db->fetchRow("SELECT metadata FROM {$actorsT} WHERE actor_id = :actor_id LIMIT 1", ['actor_id' => $realActorId]);
            $realMeta = array();
            if ($realRow && !empty($realRow['metadata'])) {
                $decoded = json_decode($realRow['metadata'], true);
                $realMeta = is_array($decoded) ? $decoded : array();
            }
            $mergedMeta = array_merge($tempMeta, $realMeta);
            $this->db->query(
                "UPDATE {$actorsT} SET metadata = :metadata WHERE actor_id = :real_actor_id",
                ['metadata' => json_encode($mergedMeta), 'real_actor_id' => $realActorId]
            );
            $this->db->query(
                "UPDATE {$actorsT} SET metadata = JSON_SET(COALESCE(metadata, JSON_OBJECT()), '$.merged_into', :real_actor_id), is_deleted = 1 WHERE actor_id = :temp_actor_id",
                ['real_actor_id' => $realActorId, 'temp_actor_id' => $tempActorId]
            );
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
