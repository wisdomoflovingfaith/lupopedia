<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "includes/classes/DialogMvpService.php"
#   web_path: "https://www.lupopedia.com/lupopedia/includes/classes/DialogMvpService.php"
#   status: "active"
#   when_updated: "20260418163036"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/18_channel_chat_display.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/dialog-mvp-service"
#   artifact_type: implementation
#   artifact_kind: service
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: 18
#   content_slug: "dialog-mvp-service"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "DialogMvpService — channel dialog helpers"
#   summary: "PDO_DB dialog writes, read log, membership, tasks, JSON API helpers, logDefect sink; CIL mood rules; ROSE metadata sink unchanged."
# ---------------------------------------------------------------------
/**
 * Dialog MVP utility service.
 *
 * Provides shared helpers for the Web Dialog MVP endpoints without filesystem writes.
 *
 * Doctrine:
 *  - ID generation: IdGenerator::generate() — NO AUTO_INCREMENT, NO MAX+1.
 *  - Timestamps:    14-digit BIGINT YYYYMMDDHHIISS only (timestamp_ymdhis; see nowYmdHis() fallback note).
 *  - Schema source: database/lupopedia/json/*.json — reference ONLY.
 *  - Nulls:         Pass NULL for nullable columns; never 0 as a null substitute.
 *  - mood_vector:      docs/doctrine/COUNTING_IN_LIGHT_DOCTRINE.md — neutral 666666 for all actors
 *                   except ROSE (3) and CARMEN (706) on own inserts, which may supply a valid six-hex
 *                   full-axis token; no fabricated placeholders (e.g. scan markers on mood_vector).
 *                   ROSE scan/analysis MUST NOT mutate mood_vector; use metadata_json.rose_annotation
 *                   (PRD 36; MVP sink). A future table (e.g. lupo_rose_analysis) requires TOON/install doctrine first.
 *  - Silo context:  Specialists (non-THOTH/ROSE) access ONLY their pending_tasks row.
 *                   Dialog history is never injected into specialist context.
 *  - Survivability: docs/doctrine/SURVIVABILITY_DOCTRINE.md — Pillar 1 fallbacks (nowYmdHis, generateId,
 *                   json_encode) emit logDefect breadcrumbs; never hide silent DB failures where logged.
 */
class DialogMvpService
{
    // Actor IDs with elevated read access (registry: database/lupopedia/actors/registry.json)
    const THOTH_ACTOR_ID   = 26;
    const ROSE_ACTOR_ID    = 3;
    const CARMEN_ACTOR_ID  = 706;

    // ---------------------------------------------------------------
    // CORE INFRASTRUCTURE
    // ---------------------------------------------------------------

    public static function getDb()
    {
        return DatabaseFactory::getConnection();
    }

    public static function getTablePrefix()
    {
        return defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    public static function nowYmdHis()
    {
        if (class_exists('timestamp_ymdhis', false)) {
            return (int) timestamp_ymdhis::now();
        }
        // SURVIVABILITY_DOCTRINE.md Pillar 1: packed UTC via gmdate only when timestamp_ymdhis class is absent.
        self::logDefect('P1-TIMESTAMP-FALLBACK-001', array(
            'reason' => 'timestamp_ymdhis_class_missing',
        ));
        return (int) gmdate('YmdHis');
    }

    /**
     * Generate a new doctrine-compliant ID via IdGenerator.
     * Falls back to a timestamp+random composite only if IdGenerator is unavailable.
     */
    private static function generateId()
    {
        if (!class_exists('IdGenerator', false) && defined('LUPOPEDIA_PATH')) {
            $path = LUPOPEDIA_PATH . '/includes/classes/IdGenerator.php';
            if (file_exists($path)) {
                require_once $path;
            }
        }
        if (class_exists('IdGenerator', false)) {
            return IdGenerator::generate();
        }
        // SURVIVABILITY_DOCTRINE.md Pillar 1: degraded ID only if IdGenerator missing; Pillar 2 breadcrumb (below).
        self::logDefect('P1-IDGEN-FALLBACK-001', array(
            'reason' => 'IdGenerator_missing',
        ));
        return (int) (gmdate('YmdHis') . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT));
    }

    // ---------------------------------------------------------------
    // THREAD KEY
    // ---------------------------------------------------------------

    /**
     * Unique per thread for (federation_node_id, channel_id, thread_key); suffixes dialog_thread_id.
     *
     * @param string $title Thread title
     * @param int $thread_id dialog_thread_id (allocated before insert)
     * @return string
     */
    public static function threadKeyForNewThread($title, $thread_id)
    {
        $base = strtolower(str_replace(array('_', ' '), '-', substr((string) $title, 0, 200)));
        $base = preg_replace('/[^a-z0-9.-]+/i', '-', $base);
        $base = trim($base, '-');
        if ($base === '') {
            $base = 'thread';
        }
        $suffix = '-' . (int) $thread_id;
        return substr($base . $suffix, 0, 255);
    }

    // ---------------------------------------------------------------
    // INPUT / AUTH HELPERS
    // ---------------------------------------------------------------

    public static function parseInput()
    {
        $raw = file_get_contents('php://input');
        $json = null;
        if ($raw !== false && trim($raw) !== '') {
            $json = json_decode($raw, true);
        }

        if (is_array($json)) {
            return $json;
        }

        if (!empty($_POST) && is_array($_POST)) {
            return $_POST;
        }

        return array();
    }

    public static function getCurrentActorId($db)
    {
        $actor_id = null;

        $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
        if ($authService && is_object($authService) && method_exists($authService, 'getCurrentUser')) {
            $user = $authService->getCurrentUser();
            if ($user && !empty($user['actor_id'])) {
                $actor_id = (int) $user['actor_id'];
            }
        }

        if (!$actor_id && function_exists('current_user')) {
            $user = current_user();
            if ($user && !empty($user['actor_id'])) {
                $actor_id = (int) $user['actor_id'];
            }
        }

        if (!$actor_id && isset($GLOBALS['lupo_session']) && is_object($GLOBALS['lupo_session'])) {
            $session = $GLOBALS['lupo_session'];
            if (method_exists($session, 'validateSession')) {
                $session_actor = $session->validateSession();
                if ($session_actor !== null && $session_actor !== false) {
                    $actor_id = (int) $session_actor;
                }
            }
        }

        if ($actor_id && $actor_id > 0) {
            return $actor_id;
        }

        return null;
    }

    public static function getCurrentAuthUser()
    {
        $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
        if ($authService && is_object($authService) && method_exists($authService, 'getCurrentUser')) {
            $user = $authService->getCurrentUser();
            if (is_array($user) && !empty($user['auth_user_id']) && !empty($user['actor_id'])) {
                return $user;
            }
        }

        if (function_exists('current_user')) {
            $user = current_user();
            if (is_array($user) && !empty($user['auth_user_id']) && !empty($user['actor_id'])) {
                return $user;
            }
        }

        return null;
    }

    public static function isActorMappedToAuthUser($db, $actor_id, $auth_user_id)
    {
        $actor_id     = (int) $actor_id;
        $auth_user_id = (int) $auth_user_id;
        if ($actor_id <= 0 || $auth_user_id <= 0) {
            return false;
        }

        $prefix       = self::getTablePrefix();
        $actors_table = $prefix . 'actors';
        $mapping_table = $prefix . 'actor_auth_users';

        $primary = $db->fetchRow(
            "SELECT actor_id FROM {$actors_table}
             WHERE actor_id = :actor_id
               AND (is_deleted = 0 OR is_deleted IS NULL)
               AND actor_source_id = :auth_user_id
               AND (actor_source_type = 'user' OR actor_source_type = 'lupo_auth_users')
             LIMIT 1",
            array(
                'actor_id'    => $actor_id,
                'auth_user_id'=> $auth_user_id,
            )
        );
        if ($primary) {
            return true;
        }

        if (!self::tableExists($db, $mapping_table)) {
            return false;
        }

        $mapped = $db->fetchRow(
            "SELECT actor_auth_user_id FROM {$mapping_table}
             WHERE actor_id = :actor_id
               AND auth_user_id = :auth_user_id
               AND is_deleted = 0
               AND status = :status
             LIMIT 1",
            array(
                'actor_id'    => $actor_id,
                'auth_user_id'=> $auth_user_id,
                'status'      => 'active',
            )
        );

        return $mapped ? true : false;
    }

    public static function isAuthenticatedHumanActor($db, $actor_id)
    {
        $user = self::getCurrentAuthUser();
        if (!$user || empty($user['auth_user_id']) || empty($user['actor_id'])) {
            return false;
        }

        return self::isActorMappedToAuthUser($db, $actor_id, (int) $user['auth_user_id']);
    }

    public static function actorHasChannelAccess($db, $actor_id, $channel_id)
    {
        $actor_id   = (int) $actor_id;
        $channel_id = (int) $channel_id;
        if ($actor_id <= 0 || $channel_id <= 0) {
            return false;
        }

        $table = self::getTablePrefix() . 'actor_channels';
        $row = $db->fetchRow(
            "SELECT actor_channel_id FROM {$table}
             WHERE actor_id = :actor_id
               AND channel_id = :channel_id
               AND is_deleted = 0
             LIMIT 1",
            array(
                'actor_id'  => $actor_id,
                'channel_id'=> $channel_id,
            )
        );
        if ($row) {
            return true;
        }

        $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
        if ($authService && is_object($authService) && method_exists($authService, 'isAdmin')) {
            return $authService->isAdmin($actor_id) ? true : false;
        }

        return false;
    }

    public static function ensureActorExists($db, $actor_id)
    {
        $table = self::getTablePrefix() . 'actors';
        $row = $db->fetchRow(
            "SELECT actor_id FROM {$table}
             WHERE actor_id = :actor_id AND is_deleted = 0
             LIMIT 1",
            array('actor_id' => (int) $actor_id)
        );

        return $row ? true : false;
    }

    public static function tableExists($db, $table)
    {
        try {
            $db->fetchOne("SELECT 1 FROM {$table} WHERE 1 = 0", array());
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getRuntimeActorsConfigPath()
    {
        return LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'runtime_actors.yaml';
    }

    // ---------------------------------------------------------------
    // FETCH HELPERS
    // ---------------------------------------------------------------

    public static function fetchThread($db, $thread_id)
    {
        $table = self::getTablePrefix() . 'dialog_threads';
        return $db->fetchRow(
            "SELECT dialog_thread_id, channel_id, title, assigned_actor_id, status
             FROM {$table}
             WHERE dialog_thread_id = :thread_id AND is_deleted = 0
             LIMIT 1",
            array('thread_id' => (int) $thread_id)
        );
    }

    public static function fetchMessage($db, $message_id)
    {
        $table = self::getTablePrefix() . 'dialog_messages';
        return $db->fetchRow(
            "SELECT dialog_message_id, dialog_thread_id, channel_id, from_actor_id,
                    to_actor_id, message_text, message_type, created_ymdhis
             FROM {$table}
             WHERE dialog_message_id = :message_id AND is_deleted = 0
             LIMIT 1",
            array('message_id' => (int) $message_id)
        );
    }

    /**
     * Silo: returns last N messages from a thread.
     * THOTH (26) and ROSE (3) may call this freely.
     * All other actors must NOT receive dialog history — they get task context only.
     *
     * @param int $requesting_actor_id The actor requesting the history.
     */
    public static function fetchLastThreadMessages($db, $thread_id, $limit, $requesting_actor_id = 0)
    {
        // Silo enforcement — specialists get no dialog history
        $requesting_actor_id = (int) $requesting_actor_id;
        if ($requesting_actor_id > 0
            && $requesting_actor_id !== self::THOTH_ACTOR_ID
            && $requesting_actor_id !== self::ROSE_ACTOR_ID
        ) {
            return array(); // Zero-disclosure to specialists
        }

        $prefix = self::getTablePrefix();
        $limit  = (int) $limit;
        if ($limit <= 0) {
            $limit = 5;
        }

        return $db->fetchAll(
            "SELECT m.dialog_message_id, m.from_actor_id, m.to_actor_id,
                    m.message_text, m.message_type, m.created_ymdhis,
                    a.name AS from_actor_name
             FROM {$prefix}dialog_messages m
             LEFT JOIN {$prefix}actors a ON a.actor_id = m.from_actor_id
             WHERE m.dialog_thread_id = :thread_id AND m.is_deleted = 0
             ORDER BY m.dialog_message_id DESC
             LIMIT {$limit}",
            array('thread_id' => (int) $thread_id)
        );
    }

    // ---------------------------------------------------------------
    // CHANNEL HELPERS (NEW)
    // ---------------------------------------------------------------

    /**
     * Return all non-deleted channels ordered by channel_name.
     * Used by the channel dropdown (global visibility, not membership-scoped).
     */
    public static function getAllChannels($db, $limit = 50)
    {
        $table = self::getTablePrefix() . 'channels';
        $limit = (int) $limit;
        if ($limit <= 0) { $limit = 50; }
        $rows = $db->fetchAll(
            "SELECT channel_id, channel_key, channel_name
             FROM {$table}
             WHERE is_deleted = 0 AND visibility_status = 'active'
             ORDER BY channel_name ASC
             LIMIT {$limit}",
            array()
        );
        return is_array($rows) ? $rows : array();
    }

    /**
     * Return actors/agents who are explicit members of this channel.
     * Channel membership is in lupo_actor_channels (status='A', is_deleted=0).
     * Right-panel list: ONLY these actors are shown.
     */
    public static function getChannelMembers($db, $channel_id, $limit = 50)
    {
        $channel_id = (int) $channel_id;
        if ($channel_id <= 0) { return array(); }
        $prefix = self::getTablePrefix();
        $limit  = (int) $limit;
        if ($limit <= 0) { $limit = 50; }
        $rows = $db->fetchAll(
            "SELECT a.actor_id, a.actor_name, a.name, a.is_agent
             FROM {$prefix}actor_channels ac
             JOIN {$prefix}actors a ON a.actor_id = ac.actor_id
             WHERE ac.channel_id = :cid
               AND ac.is_deleted = 0
               AND ac.status = 'A'
               AND a.is_deleted = 0
               AND a.is_active = 1
             ORDER BY a.is_agent DESC, a.actor_id ASC
             LIMIT {$limit}",
            array('cid' => $channel_id)
        );
        return is_array($rows) ? $rows : array();
    }

    /**
     * Auto-join: ensure actor is a member of channel.
     * Inserts a new actor_channels row if one does not exist (or if soft-deleted).
     * Safe to call on every page load — SELECT-then-UPDATE/INSERT pattern.
     *
     * Schema: lupo_actor_channels.json
     */
    public static function ensureChannelMembership($db, $actor_id, $channel_id, $created_by = 0, $actor_name = '')
    {
        $actor_id   = (int) $actor_id;
        $channel_id = (int) $channel_id;
        if ($actor_id <= 0 || $channel_id <= 0) { return; }

        $table = self::getTablePrefix() . 'actor_channels';
        $now   = self::nowYmdHis();

        // Check existing (including soft-deleted — unique constraint covers both)
        $existing = $db->fetchRow(
            "SELECT actor_channel_id, is_deleted
             FROM {$table}
             WHERE actor_id = :aid AND channel_id = :cid
             LIMIT 1",
            array('aid' => $actor_id, 'cid' => $channel_id)
        );

        if ($existing) {
            if ((int) $existing['is_deleted'] === 1) {
                // Re-activate soft-deleted membership
                try {
                    $db->query(
                        "UPDATE {$table}
                         SET is_deleted = 0, deleted_ymdhis = NULL, status = 'A', updated_ymdhis = :ts
                         WHERE actor_channel_id = :id",
                        array('ts' => $now, 'id' => $existing['actor_channel_id'])
                    );
                } catch (Exception $e) {
                    self::logDefect('P2-CHANNEL-MEMBERSHIP-FAIL-001', array(
                        'operation' => 'reactivate',
                        'actor_id' => $actor_id,
                        'channel_id' => $channel_id,
                        'exception' => $e->getMessage(),
                    ));
                    throw $e;
                }
            }
            // Already active — nothing to do
            return;
        }

        // New membership row — strict schema alignment (lupo_actor_channels.json)
        $creator = ($created_by > 0) ? $created_by : $actor_id;
        try {
            $db->query(
                "INSERT INTO {$table}
                 (actor_channel_id, actor_id, actor_name, created_by_actor_id,
                  channel_id, status, channel_color,
                  created_ymdhis, updated_ymdhis, is_deleted)
                 VALUES (:acid, :aid, :aname, :crid,
                         :cid, 'A', 'F7FAFF',
                         :cr, :up, 0)",
                array(
                    'acid'  => self::generateId(),
                    'aid'   => $actor_id,
                    'aname' => substr((string) $actor_name, 0, 64),
                    'crid'  => $creator,
                    'cid'   => $channel_id,
                    'cr'    => $now,
                    'up'    => $now,
                )
            );
        } catch (Exception $e) {
            self::logDefect('P2-CHANNEL-MEMBERSHIP-FAIL-001', array(
                'operation' => 'insert',
                'actor_id' => $actor_id,
                'channel_id' => $channel_id,
                'exception' => $e->getMessage(),
            ));
            throw $e;
        }
    }

    // ---------------------------------------------------------------
    // READ LOG (NEW)
    // ---------------------------------------------------------------

    /**
     * Update high-water read mark for actor in a channel+thread.
     * Uses SELECT-then-UPDATE/INSERT (no unique constraint on this table).
     *
     * Schema: lupo_dialog_read_log.json
     * Note: schema has last_read_message_id but NOT last_read_created_ymdhis.
     * OQ-04: last_read_created_ymdhis was requested but column does not exist.
     *        Only last_read_message_id is tracked until schema is updated.
     */
    public static function updateReadLog($db, $actor_id, $channel_id, $thread_id, $last_message_id)
    {
        $actor_id       = (int) $actor_id;
        $channel_id     = (int) $channel_id;
        $thread_id      = (int) $thread_id;
        $last_message_id= (int) $last_message_id;
        if ($actor_id <= 0 || $channel_id <= 0 || $thread_id <= 0 || $last_message_id <= 0) {
            return;
        }

        $table = self::getTablePrefix() . 'dialog_read_log';
        $now   = self::nowYmdHis();

        $existing = $db->fetchRow(
            "SELECT read_log_id
             FROM {$table}
             WHERE actor_id = :aid AND channel_id = :cid AND thread_id = :tid
             ORDER BY updated_ymdhis DESC
             LIMIT 1",
            array('aid' => $actor_id, 'cid' => $channel_id, 'tid' => $thread_id)
        );

        if ($existing) {
            try {
                $db->query(
                    "UPDATE {$table}
                     SET last_read_message_id = :lm, updated_ymdhis = :ts
                     WHERE read_log_id = :rid",
                    array(
                        'lm'  => $last_message_id,
                        'ts'  => $now,
                        'rid' => $existing['read_log_id'],
                    )
                );
            } catch (Exception $e) {
                self::logDefect('P2-READLOG-WRITE-001', array(
                    'operation' => 'update',
                    'actor_id' => $actor_id,
                    'channel_id' => $channel_id,
                    'thread_id' => $thread_id,
                    'last_read_message_id' => $last_message_id,
                    'exception' => $e->getMessage(),
                ));
                throw $e;
            }
        } else {
            // Strict schema alignment (lupo_dialog_read_log.json)
            try {
                $db->query(
                    "INSERT INTO {$table}
                     (read_log_id, actor_id, channel_id, thread_id,
                      last_read_message_id, updated_ymdhis)
                     VALUES (:rid, :aid, :cid, :tid, :lm, :ts)",
                    array(
                        'rid' => self::generateId(),
                        'aid' => $actor_id,
                        'cid' => $channel_id,
                        'tid' => $thread_id,
                        'lm'  => $last_message_id,
                        'ts'  => $now,
                    )
                );
            } catch (Exception $e) {
                self::logDefect('P2-READLOG-WRITE-001', array(
                    'operation' => 'insert',
                    'actor_id' => $actor_id,
                    'channel_id' => $channel_id,
                    'thread_id' => $thread_id,
                    'last_read_message_id' => $last_message_id,
                    'exception' => $e->getMessage(),
                ));
                throw $e;
            }
        }
    }

    // ---------------------------------------------------------------
    // PILLAR 2 DEFECT LOG (internal)
    // Primary sink: changelog-pending/*.json. Fallback: error_log(JSON) per Survivability Pillar 1.
    // ---------------------------------------------------------------

    /**
     * Defect breadcrumb: Pillar 1 (survivability / fallback) and Pillar 2 (recurrence / learning transfer).
     * Sink: changelog-pending/*.json; fallback error_log per SURVIVABILITY_DOCTRINE.md.
     * Safe for HermesService and other coordinators; failures never throw.
     *
     * @param string $pattern_id e.g. P1-IDGEN-FALLBACK-001, P2-CIL-INVALID-MOOD-001, P2-JSON-DECODE-001
     * @param array  $context    serializable diagnostic context
     * @return bool true if JSON file written under changelog-pending/
     */
    public static function logDefect($pattern_id, $context = array())
    {
        $pattern_id = trim((string) $pattern_id);
        if ($pattern_id === '') {
            return false;
        }
        // Avoid self::nowYmdHis() here: that path may call logDefect (P1-TIMESTAMP-FALLBACK-001) and recurse.
        if (class_exists('timestamp_ymdhis', false)) {
            $ts = (string) timestamp_ymdhis::now();
        } else {
            $ts = gmdate('YmdHis');
        }
        if (!is_array($context)) {
            $context = array('value' => (string) $context);
        }
        $payload = array(
            'timestamp'   => $ts,
            'pattern_id'  => $pattern_id,
            'context'     => $context,
            'source'      => 'DialogMvpService::logDefect',
            'agent_id'    => 'dialogmvp',
            'channel'     => 'development',
            'thread'      => 'defect-log',
            'summary'     => 'DialogMvpService defect: ' . $pattern_id,
            'files_changed' => array(),
            'open_questions' => array(),
            'handoff_to'  => null,
            'related_toons' => array(),
        );
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            error_log('[DialogMvpService::logDefect] ' . print_r($payload, true));
            return false;
        }
        if (!defined('LUPOPEDIA_PATH')) {
            error_log('[DialogMvpService::logDefect] ' . $json);
            return false;
        }
        $dir = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'changelog-pending';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        if (!is_dir($dir) || !is_writable($dir)) {
            error_log('[DialogMvpService::logDefect] ' . $json);
            return false;
        }
        $suffix = substr(md5($ts . serialize($context)), 0, 10);
        $path = $dir . DIRECTORY_SEPARATOR . $ts . '_dialogmvp_' . $suffix . '.json';
        $written = @file_put_contents($path, $json . "\n", LOCK_EX);
        if ($written === false) {
            error_log('[DialogMvpService::logDefect] ' . $json);
            return false;
        }
        return true;
    }

    // ---------------------------------------------------------------
    // ROSE annotation pass (PRD 36 section 5; COUNTING_IN_LIGHT_DOCTRINE.md — mood_vector column untouched here).
    // MVP sink: lupo_dialog_messages.metadata_json.rose_annotation (TOON-aligned). SURVIVABILITY_DOCTRINE.md: bad
    // metadata_json decode paths log P2-JSON-DECODE-001; no mood_vector scan markers.
    // Future: a dedicated row store (e.g. lupo_rose_analysis) may be added via canonical JSON/TOON then install SQL;
    // do not create tables ad hoc from this class.
    // ---------------------------------------------------------------

    /**
     * Annotate pending dialog rows for ROSE bookkeeping: merges metadata_json only.
     * Does not mutate mood_vector (no scan markers; CIL neutral token unchanged on column).
     *
     * @param int $limit Batch size per invocation.
     * @return int Number of rows whose metadata_json was updated.
     */
    public static function roseAnnotatePendingMessages($db, $limit = 100)
    {
        $table = self::getTablePrefix() . 'dialog_messages';
        $limit = (int) $limit;
        if ($limit <= 0) {
            $limit = 100;
        }
        $now = self::nowYmdHis();

        $rows = $db->fetchAll(
            "SELECT dialog_message_id, metadata_json
             FROM {$table}
             WHERE mood_vector = '666666' AND is_deleted = 0
             LIMIT {$limit}",
            array()
        );
        if (empty($rows)) {
            return 0;
        }

        $updated = 0;
        foreach ($rows as $r) {
            $mid = isset($r['dialog_message_id']) ? (int) $r['dialog_message_id'] : 0;
            if ($mid <= 0) {
                continue;
            }
            $rawMeta = isset($r['metadata_json']) ? $r['metadata_json'] : null;
            $meta = array();
            if ($rawMeta !== null && $rawMeta !== '') {
                $decoded = json_decode((string) $rawMeta, true);
                if (is_array($decoded)) {
                    $meta = $decoded;
                } elseif (trim((string) $rawMeta) !== '') {
                    self::logDefect('P2-JSON-DECODE-001', array(
                        'dialog_message_id' => $mid,
                    ));
                }
            }
            if (isset($meta['rose_annotation']['placeholder_scan_ymdhis']) && (int) $meta['rose_annotation']['placeholder_scan_ymdhis'] > 0) {
                continue;
            }
            if (!isset($meta['rose_annotation']) || !is_array($meta['rose_annotation'])) {
                $meta['rose_annotation'] = array();
            }
            $meta['rose_annotation']['placeholder_scan_ymdhis'] = $now;
            $meta['rose_annotation']['engine'] = 'DialogMvpService::roseAnnotatePendingMessages';
            $meta['rose_annotation']['note'] = 'metadata_annotation_sink_prd36_no_mood_mutation';

            $newJson = json_encode($meta, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            if ($newJson === false) {
                self::logDefect('PROPOSED-ROSE-SINK-001', array(
                    'dialog_message_id' => $mid,
                    'error' => 'json_encode_failed',
                    'detail' => 'metadata_json annotation sink unavailable for encode',
                ));
                continue;
            }
            try {
                $db->query(
                    "UPDATE {$table}
                     SET metadata_json = :mj, updated_ymdhis = :ts
                     WHERE dialog_message_id = :mid AND mood_vector = '666666' AND is_deleted = 0",
                    array('mj' => $newJson, 'ts' => $now, 'mid' => $mid)
                );
                $updated++;
            } catch (Exception $e) {
                self::logDefect('PROPOSED-ROSE-SINK-001', array(
                    'dialog_message_id' => $mid,
                    'error' => 'update_failed',
                    'detail' => $e->getMessage(),
                ));
            }
        }
        return $updated;
    }

    /**
     * @deprecated Use roseAnnotatePendingMessages(). Name retained for binary compatibility.
     */
    public static function roseAnalyzePendingMessages($db, $limit = 100)
    {
        return self::roseAnnotatePendingMessages($db, $limit);
    }

    /**
     * @deprecated Use roseAnnotatePendingMessages(); legacy name retained. Does not touch mood_vector.
     */
    public static function roseScanPendingMoodUpdates($db, $limit = 100)
    {
        return self::roseAnnotatePendingMessages($db, $limit);
    }

    // ---------------------------------------------------------------
    // WRITE OPERATIONS
    // ---------------------------------------------------------------

    /**
     * Insert a dialog message with strict schema alignment.
     *
     * FIXES HY093: array keys exactly match lupo_dialog_messages.json columns.
     * - IdGenerator::generate() for PKs (no MAX+1).
     * - channel_key fetched from lupo_channels (not from thread row).
     * - to_actor_id: NULL when not set (nullable bigint).
     * - deleted_ymdhis: NULL (nullable bigint).
     * - mood_vector: docs/doctrine/COUNTING_IN_LIGHT_DOCTRINE.md — use neutral 666666 for all actors
     *   except ROSE (3) and CARMEN (706) on own insert, where caller may pass a valid six-hex full-axis
     *   token; no fabricated mood placeholders; invalid hex for those actors is logged and coerced to 666666.
     *
     * @param object  $db
     * @param int     $thread_id
     * @param int     $from_actor_id
     * @param string  $message_text
     * @param string  $message_type
     * @param int|null $to_actor_id
     * @param string  $mood_vector        Ignored unless from_actor_id is ROSE (3) or CARMEN (706) (CIL full-axis).
     * @param string|null $metadata_json
     * @return array  ['message_id', 'thread_id', 'channel_id', 'created_ymdhis']
     */
    public static function createDialogMessage(
        $db, $thread_id, $from_actor_id, $message_text,
        $message_type, $to_actor_id, $mood_vector, $metadata_json
    ) {
        $prefix     = self::getTablePrefix();
        $t_threads  = $prefix . 'dialog_threads';
        $t_messages = $prefix . 'dialog_messages';
        $t_channels = $prefix . 'channels';

        $thread = self::fetchThread($db, $thread_id);
        if (!$thread) {
            self::logDefect('P2-DIALOG-THREAD-MISSING-001', array(
                'dialog_thread_id' => (int) $thread_id,
                'from_actor_id' => (int) $from_actor_id,
            ));
            throw new Exception('Thread not found.');
        }

        $channel_id = isset($thread['channel_id']) ? (int) $thread['channel_id'] : 0;

        // Fetch channel_key from lupo_channels — NOT from thread row (thread has no channel_key)
        $channel_key = '';
        if ($channel_id > 0) {
            $ch_row = $db->fetchRow(
                "SELECT channel_key FROM {$t_channels}
                 WHERE channel_id = :cid AND is_deleted = 0
                 LIMIT 1",
                array('cid' => $channel_id)
            );
            if ($ch_row && isset($ch_row['channel_key'])) {
                $channel_key = (string) $ch_row['channel_key'];
            }
            if ($channel_key === '') {
                self::logDefect('P2-DIALOG-INSERT-FAIL-001', array(
                    'reason' => 'channel_key_unresolved',
                    'thread_id' => (int) $thread_id,
                    'channel_id' => $channel_id,
                    'from_actor_id' => (int) $from_actor_id,
                ));
            }
        }

        $now        = self::nowYmdHis();
        $message_id = self::generateId();

        // COUNTING_IN_LIGHT_DOCTRINE.md: neutral 666666; full-axis only for ROSE (3) or CARMEN (706).
        $from = (int) $from_actor_id;
        $effective_mood = '666666';
        $allowFullAxisCil = ($from === self::ROSE_ACTOR_ID || $from === self::CARMEN_ACTOR_ID);
        if ($allowFullAxisCil && $mood_vector !== null && $mood_vector !== '') {
            $candidate = strtoupper(substr(preg_replace('/[^0-9A-Fa-f]/', '', (string) $mood_vector), 0, 6));
            if (strlen($candidate) === 6 && preg_match('/^[0-9A-F]{6}$/', $candidate)) {
                $effective_mood = $candidate;
            } else {
                self::logDefect('P2-CIL-INVALID-MOOD-001', array(
                    'actor_id' => $from,
                    'input' => (string) $mood_vector,
                ));
            }
        }

        $r = self::rewriteHumanDialogMessageBodyForInsert(
            $db,
            (string) $message_text,
            (int) $from_actor_id,
            $to_actor_id,
            $metadata_json
        );
        $message_text_for_row = $r['body'];
        $metadata_json = $r['metadata_json'];

        // Strict whitelist — lupo_dialog_messages.json
        // to_actor_id: nullable bigint — pass NULL, not 0
        // deleted_ymdhis: nullable bigint — pass NULL
        // metadata_json: nullable json — pass NULL when empty
        $insert_row = array(
            'dialog_message_id'         => $message_id,
            'dialog_thread_id'          => (int) $thread_id,
            'channel_id'                => $channel_id,
            'channel_key'               => $channel_key,
            'from_actor_id'             => (int) $from_actor_id,
            'source_faucet_slug'        => '',
            'source_faucet_instance_id' => '',
            'to_actor_id'               => ($to_actor_id !== null && (int) $to_actor_id > 0)
                                            ? (int) $to_actor_id : null,
            'read_by_actor_id'          => 0,
            'read_by_actor_utc'         => 0,
            'message_text'              => $message_text_for_row,
            'message_type'              => ($message_type !== '') ? $message_type : 'text',
            'metadata_json'             => ($metadata_json !== null && $metadata_json !== '')
                                            ? $metadata_json : null,
            'mood_vector'                  => $effective_mood,
            'mood_framework'            => 'western_analytical',
            'created_ymdhis'            => $now,
            'updated_ymdhis'            => $now,
            'is_deleted'                => 0,
            'deleted_ymdhis'            => null,
        );
        error_log('[DialogMvpService::createDialogMessage] table=' . $t_messages);
        error_log('[DialogMvpService::createDialogMessage] keys=' . implode(',', array_keys($insert_row)));
        error_log('[DialogMvpService::createDialogMessage] to_actor_id=' . var_export($insert_row['to_actor_id'], true));
        $insert_result = $db->insert($t_messages, $insert_row);
        if ($insert_result === false) {
            $last_err = method_exists($db, 'getLastError') ? $db->getLastError() : 'unknown';
            error_log('[DialogMvpService::createDialogMessage] INSERT returned false. lastError=' . $last_err);
            self::logDefect('P2-DIALOG-INSERT-FAIL-001', array(
                'reason' => 'insert_returned_false',
                'thread_id' => (int) $thread_id,
                'from_actor_id' => (int) $from_actor_id,
                'last_error' => $last_err,
            ));
            throw new Exception('createDialogMessage: INSERT failed. ' . $last_err);
        }

        // FIX HY093: PDO native prepared statements forbid duplicate named params.
        // `:ts` appeared twice — use distinct names `:ts_lm` and `:ts_up`.
        $db->query(
            "UPDATE {$t_threads}
             SET last_message_ymdhis = :ts_lm, updated_ymdhis = :ts_up
             WHERE dialog_thread_id = :tid",
            array('ts_lm' => $now, 'ts_up' => $now, 'tid' => (int) $thread_id)
        );

        return array(
            'message_id'    => $message_id,
            'thread_id'     => (int) $thread_id,
            'channel_id'    => $channel_id,
            'created_ymdhis'=> $now,
        );
    }

    // ---------------------------------------------------------------
    // FIRST-PERSON CHANNEL INGEST REWRITE (PRD 00 / PRD 02 RULE 93.FIRST_PERSON_DISPLAY_FORBIDDEN)
    // Human senders (lupo_actors.is_agent = 0): rewrite before INSERT. Agent rows: keep raw body; UI may call
    // rewriteFirstPersonEnglishForHumanIngest() defensively at render time.
    // ---------------------------------------------------------------

    /**
     * Merge optional JSON metadata for dialog_messages into an associative array.
     *
     * @param string|null $metadata_json
     * @return array
     */
    public static function mergeDialogMessageMetadataArray($metadata_json)
    {
        if ($metadata_json === null || $metadata_json === '') {
            return array();
        }
        $decoded = json_decode((string) $metadata_json, true);
        return is_array($decoded) ? $decoded : array();
    }

    /**
     * @param array $meta
     * @return string|null JSON string or null when empty
     */
    public static function encodeDialogMessageMetadataArray($meta)
    {
        if (!is_array($meta) || count($meta) === 0) {
            return null;
        }
        $json = json_encode($meta);
        return ($json !== false && $json !== '') ? $json : null;
    }

    /**
     * @param mixed $db
     * @param int   $actor_id
     * @return array|null actor_name, name, is_agent
     */
    public static function fetchActorRowForRewrite($db, $actor_id)
    {
        $actor_id = (int) $actor_id;
        if ($actor_id <= 0) {
            return null;
        }
        $prefix = self::getTablePrefix();
        $t = $prefix . 'actors';
        $row = $db->fetchRow(
            "SELECT actor_name, name, is_agent FROM {$t}
             WHERE actor_id = :aid AND is_deleted = 0
             LIMIT 1",
            array('aid' => $actor_id)
        );
        return is_array($row) ? $row : null;
    }

    /**
     * @param array|null $row
     * @param int        $actor_id
     * @return string
     */
    public static function displayNameFromActorRow($row, $actor_id)
    {
        if (is_array($row)) {
            if (isset($row['name']) && trim((string) $row['name']) !== '') {
                return trim((string) $row['name']);
            }
            if (isset($row['actor_name']) && trim((string) $row['actor_name']) !== '') {
                return trim((string) $row['actor_name']);
            }
        }
        return 'Actor ' . (int) $actor_id;
    }

    /**
     * Simple English possessive for pronoun replacement (not full grammar).
     *
     * @param string $name
     * @return string
     */
    public static function englishPossessiveFromName($name)
    {
        $n = trim((string) $name);
        if ($n === '') {
            return '';
        }
        $last = strtolower(substr($n, -1));
        if ($last === 's') {
            return $n . "'";
        }
        return $n . "'s";
    }

    /**
     * Rewrite first- and second-person English pronouns using explicit actor display strings.
     * Used for human ingest (and may be reused for agent display). ASCII-safe; UTF-8 safe via /u where used.
     *
     * @param string $text
     * @param string $senderDisplay
     * @param string $recipientDisplay literal display or "the recipient" / "the group"
     * @param bool   $recipientIsGroup true -> "you" becomes "the group" (and matching possessives)
     * @return string
     */
    public static function rewriteFirstPersonEnglishForHumanIngest($text, $senderDisplay, $recipientDisplay, $recipientIsGroup)
    {
        $out = (string) $text;
        $sender = trim((string) $senderDisplay);
        if ($sender === '') {
            $sender = 'the sender';
        }
        $recipient = trim((string) $recipientDisplay);
        if ($recipientIsGroup) {
            $recipient = 'the group';
        }
        if ($recipient === '') {
            $recipient = 'the recipient';
        }
        $sPoss = self::englishPossessiveFromName($sender);
        if ($sPoss === '') {
            $sPoss = $sender . "'s";
        }
        $rPoss = self::englishPossessiveFromName($recipient);
        if ($rPoss === '') {
            $rPoss = $recipient . "'s";
        }
        $pluralOthers = $sender . ' and others';
        $pluralPoss = self::englishPossessiveFromName($pluralOthers);
        if ($pluralPoss === '') {
            $pluralPoss = $pluralOthers . "'s";
        }

        $phrasePairs = array(
            '/\bI\'m\b/iu' => $sender . ' is',
            '/\bI\'ve\b/iu' => $sender . ' has',
            '/\bI\'ll\b/iu' => $sender . ' will',
            '/\bI\'d\b/iu' => $sender . ' would',
            '/\bI am\b/iu' => $sender . ' is',
            '/\bI have\b/iu' => $sender . ' has',
            '/\bI need\b/iu' => $sender . ' needs',
            '/\bI think\b/iu' => $sender . ' thinks',
            '/\bI want\b/iu' => $sender . ' wants',
            '/\bI was\b/iu' => $sender . ' was',
            '/\bI will\b/iu' => $sender . ' will',
            '/\bI would\b/iu' => $sender . ' would',
            '/\bI could\b/iu' => $sender . ' could',
            '/\bI should\b/iu' => $sender . ' should',
            '/\bI can\b/iu' => $sender . ' can',
            '/\bI cannot\b/iu' => $sender . ' cannot',
            '/\bI can\'t\b/iu' => $sender . ' cannot',
            '/\bI don\'t\b/iu' => $sender . ' does not',
            '/\bI do not\b/iu' => $sender . ' does not',
            '/\bI did\b/iu' => $sender . ' did',
        );
        foreach ($phrasePairs as $pattern => $replacement) {
            $tmp = preg_replace($pattern, $replacement, $out);
            if ($tmp !== null) {
                $out = $tmp;
            }
        }

        $tokenPairs = array(
            array('/\bourselves\b/iu', $pluralOthers),
            array('/\bmyself\b/iu', $sender),
            array('/\byourself\b/iu', $recipient),
            array('/\byourselves\b/iu', $recipientIsGroup ? 'the group' : $recipient),
            array('/\bours\b/iu', $pluralPoss),
            array('/\bour\b/iu', $pluralPoss),
            array('/\bus\b/iu', $pluralOthers),
            array('/\bwe\b/iu', $pluralOthers),
            array('/\byours\b/iu', $rPoss),
            array('/\byour\b/iu', $rPoss),
            array('/\byou\b/iu', $recipient),
            array('/\bmine\b/iu', $sPoss),
            array('/\bmy\b/iu', $sPoss),
            array('/\bme\b/iu', $sender),
            array('/\bI\b/iu', $sender),
        );
        foreach ($tokenPairs as $pair) {
            $tmp = preg_replace($pair[0], $pair[1], $out);
            if ($tmp !== null) {
                $out = $tmp;
            }
        }

        return $out;
    }

    /**
     * Shared human ingest first-person rewrite for any path that persists operator-visible dialog body text.
     * Agents (is_agent=1) leave body and metadata_json unchanged here; display layer rewrites per channel_chat_row.
     *
     * @param mixed       $db
     * @param string      $body
     * @param int         $from_actor_id
     * @param int|null    $to_actor_id
     * @param string|null $metadata_json_in
     * @return array      keys: body (string), metadata_json (string|null)
     */
    public static function rewriteHumanDialogMessageBodyForInsert($db, $body, $from_actor_id, $to_actor_id, $metadata_json_in)
    {
        $message_text_for_row = (string) $body;
        $out_metadata = $metadata_json_in;
        $merged_meta = self::mergeDialogMessageMetadataArray($metadata_json_in);
        $skipFirstPerson = !empty($merged_meta['skip_first_person_rewrite'])
            || !empty($merged_meta['first_person_rewrite_applied']);
        if (!$skipFirstPerson) {
            $fromRow = self::fetchActorRowForRewrite($db, $from_actor_id);
            $is_agent = ($fromRow !== null && isset($fromRow['is_agent'])) ? (int) $fromRow['is_agent'] : 1;
            if ($is_agent === 0) {
                $senderName = self::displayNameFromActorRow($fromRow, $from_actor_id);
                $recipientIsGroup = false;
                if (!empty($merged_meta['routing']['to_actor_ids']) && is_array($merged_meta['routing']['to_actor_ids'])
                    && count($merged_meta['routing']['to_actor_ids']) > 1
                ) {
                    $recipientIsGroup = true;
                }
                if ($recipientIsGroup) {
                    $recipientName = 'the group';
                } else {
                    $tid = ($to_actor_id !== null && (int) $to_actor_id > 0) ? (int) $to_actor_id : 0;
                    if ($tid > 0) {
                        $toRow = self::fetchActorRowForRewrite($db, $tid);
                        $recipientName = self::displayNameFromActorRow($toRow, $tid);
                    } else {
                        $recipientName = 'the recipient';
                    }
                }
                $originalBody = $message_text_for_row;
                $message_text_for_row = self::rewriteFirstPersonEnglishForHumanIngest(
                    $message_text_for_row,
                    $senderName,
                    $recipientName,
                    $recipientIsGroup
                );
                $merged_meta['first_person_rewrite_applied'] = true;
                if ($message_text_for_row !== $originalBody) {
                    $merged_meta['original_message_text_v1'] = $originalBody;
                }
                $out_metadata = self::encodeDialogMessageMetadataArray($merged_meta);
            }
        }

        return array(
            'body' => $message_text_for_row,
            'metadata_json' => $out_metadata,
        );
    }

    public static function createHumanRequest($db, $thread_id, $message_id, $initiator_actor_id, $target_actor_id, $message_text)
    {
        $prefix    = self::getTablePrefix();
        $t_requests = $prefix . 'human_requests';
        if (!self::tableExists($db, $t_requests)) {
            return 0;
        }

        $thread = self::fetchThread($db, $thread_id);
        if (!$thread) {
            return 0;
        }

        $target_auth_user_id = self::resolvePrimaryAuthUserIdForActor($db, $target_actor_id);
        if ($target_auth_user_id <= 0) {
            return 0;
        }

        $request_id  = self::generateId();
        $now         = self::nowYmdHis();
        $title       = 'Human response requested for thread #' . (int) $thread_id;
        $description = trim((string) $message_text);
        if ($description === '') {
            $description = 'A routed dialog message requires human response.';
        }

        // Strict whitelist for lupo_human_requests (if table exists)
        $ins = $db->insert($t_requests, array(
            'request_id'          => $request_id,
            'thread_id'           => (int) $thread_id,
            'channel_id'          => (int) $thread['channel_id'],
            'project_id'          => 0,
            'initiator_actor_id'  => (int) $initiator_actor_id,
            'target_auth_user_id' => $target_auth_user_id,
            'request_type'        => 'direct_response',
            'request_title'       => substr($title, 0, 255),
            'request_description' => $description,
            'subject_type'        => 'implementation',
            'subject_reference'   => 'dialog_message_id:' . (int) $message_id,
            'priority'            => 'normal',
            'request_mode'        => 'single_human',
            'status'              => 'pending',
            'created_ymdhis'      => $now,
            'updated_ymdhis'      => $now,
            'is_deleted'          => 0,
            'deleted_ymdhis'      => null,
        ));
        if ($ins === false) {
            self::logDefect('P2-HUMAN-REQUEST-FAIL-001', array(
                'thread_id' => (int) $thread_id,
                'message_id' => (int) $message_id,
                'initiator_actor_id' => (int) $initiator_actor_id,
                'target_actor_id' => (int) $target_actor_id,
            ));
            return 0;
        }

        return (int) $request_id;
    }

    /**
     * Create a new dialog thread.
     * Uses IdGenerator::generate() — NO MAX+1.
     *
     * Strict whitelist — lupo_dialog_threads.json
     * - escalated_to_operator_id: nullable bigint — NULL when unused.
     * - escalation_timestamp: nullable bigint — NULL.
     * - deleted_ymdhis: nullable bigint — NULL.
     */
    public static function createDialogThread($db, $channel_id, $title, $created_by_actor_id)
    {
        $prefix    = self::getTablePrefix();
        $t_channels = $prefix . 'channels';
        $t_threads  = $prefix . 'dialog_threads';

        $channel = $db->fetchRow(
            "SELECT channel_id FROM {$t_channels}
             WHERE channel_id = :channel_id AND is_deleted = 0
             LIMIT 1",
            array('channel_id' => (int) $channel_id)
        );

        if (!$channel) {
            throw new Exception('Channel not found.');
        }

        $thread_id = self::generateId();
        $now       = self::nowYmdHis();

        // Strict whitelist — lupo_dialog_threads.json
        $ins = $db->insert($t_threads, array(
            'dialog_thread_id'       => $thread_id,
            'title'                  => substr((string) $title, 0, 255),
            'thread_key'             => self::threadKeyForNewThread($title, $thread_id),
            'last_message_ymdhis'    => $now,
            // PRD 82 transcript path: memory/transcripts/{federation_node_id}/... — keep same node id on dialog_threads rows, transcript_jsonl path triples, and Hermes routing context (default local = 0).
            'federation_node_id'     => 0,
            'channel_id'             => (int) $channel_id,
            'project_slug'           => '',
            'task_name'              => '',
            'created_by_actor_id'    => (int) $created_by_actor_id,
            'summary_text'           => '',
            'bg_color'               => 'FFFFFF',
            'text_color'             => '000000',
            'alt_text_color'         => '666666',
            'status'                 => 'Open',
            'artifacts'              => null,
            'metadata_json'          => null,
            'thread_lineage'         => '',
            'created_ymdhis'         => $now,
            'updated_ymdhis'         => $now,
            'is_deleted'             => 0,
            'deleted_ymdhis'         => null,
            'escalated_to_operator_id'=> null,
            'escalation_reason'      => '',
            'escalation_timestamp'   => null,
            'visibility_status'      => 'active',
            'owner_actor_id'         => (int) $created_by_actor_id,
            'assigned_actor_id'      => (int) $created_by_actor_id,
            'thread_type'            => 'discussion',
            'thread_priority'        => 'normal',
        ));
        if ($ins === false) {
            self::logDefect('P2-DIALOG-THREAD-CREATE-001', array(
                'channel_id' => (int) $channel_id,
                'created_by_actor_id' => (int) $created_by_actor_id,
                'title_preview' => substr((string) $title, 0, 120),
            ));
            throw new Exception('Failed to create dialog thread.');
        }

        return array(
            'thread_id'     => $thread_id,
            'channel_id'    => (int) $channel_id,
            'created_by'    => (int) $created_by_actor_id,
            'created_ymdhis'=> $now,
        );
    }

    // ---------------------------------------------------------------
    // AUTH USER HELPERS
    // ---------------------------------------------------------------

    public static function resolvePrimaryAuthUserIdForActor($db, $actor_id)
    {
        $table = self::getTablePrefix() . 'actor_auth_users';
        $row = $db->fetchRow(
            "SELECT auth_user_id FROM {$table}
             WHERE actor_id = :actor_id
               AND is_deleted = 0
               AND status = :status
             ORDER BY is_primary DESC, routing_priority ASC, actor_auth_user_id ASC
             LIMIT 1",
            array(
                'actor_id' => (int) $actor_id,
                'status'   => 'active',
            )
        );

        if (!$row || empty($row['auth_user_id'])) {
            return 0;
        }

        return (int) $row['auth_user_id'];
    }

    // ---------------------------------------------------------------
    // TASK ASSIGNEE SCOPE (department + channel; PRD 02 / LILITH UI doctrine)
    // ---------------------------------------------------------------

    /**
     * Actors the current auth user may assign a task to: in channel AND
     * (admin: any channel member) OR (user has department 0: all channel members)
     * OR (share a non-zero department via actor_departments + auth_user_departments).
     *
     * @param mixed $db PDO_DB
     * @param int   $channel_id
     * @param int   $auth_user_id lupo_auth_users.auth_user_id
     * @param bool  $admin_bypass when true, all active channel members (TraitEnforcer admin path)
     * @return array rows actor_id, name, actor_name, is_agent
     */
    public static function getTaskAssignableActors($db, $channel_id, $auth_user_id, $admin_bypass = false)
    {
        $channel_id = (int) $channel_id;
        $auth_user_id = (int) $auth_user_id;
        if ($channel_id <= 0) {
            return array();
        }
        if ($admin_bypass) {
            return self::getChannelMembers($db, $channel_id, 500);
        }
        if ($auth_user_id <= 0) {
            return array();
        }
        $prefix = self::getTablePrefix();
        $deptRows = $db->fetchAll(
            "SELECT DISTINCT department_id FROM {$prefix}auth_user_departments
             WHERE auth_user_id = :uid AND is_deleted = 0",
            array('uid' => $auth_user_id)
        );
        if (!is_array($deptRows) || count($deptRows) === 0) {
            return array();
        }
        $hasRootDept = false;
        $deptIds = array();
        foreach ($deptRows as $r) {
            if (!isset($r['department_id'])) {
                continue;
            }
            $d = (int) $r['department_id'];
            if ($d === 0) {
                $hasRootDept = true;
            } elseif ($d > 0) {
                $deptIds[] = $d;
            }
        }
        $deptIds = array_values(array_unique($deptIds));
        if ($hasRootDept) {
            return self::getChannelMembers($db, $channel_id, 500);
        }
        if (count($deptIds) === 0) {
            return array();
        }
        $placeholders = array();
        $params = array('cid' => $channel_id);
        $i = 0;
        foreach ($deptIds as $d) {
            $ph = 'd' . $i;
            $placeholders[] = ':' . $ph;
            $params[$ph] = (int) $d;
            $i++;
        }
        $inSql = implode(',', $placeholders);
        $rows = $db->fetchAll(
            "SELECT DISTINCT a.actor_id, a.name, a.actor_name, a.is_agent
             FROM {$prefix}actors a
             INNER JOIN {$prefix}actor_channels ac ON ac.actor_id = a.actor_id AND ac.channel_id = :cid AND ac.is_deleted = 0 AND ac.status = 'A'
             INNER JOIN {$prefix}actor_departments ad ON ad.actor_id = a.actor_id AND ad.is_deleted = 0
             WHERE a.is_deleted = 0 AND a.is_active = 1 AND ad.department_id IN ({$inSql})
             ORDER BY a.actor_name ASC",
            $params
        );
        return is_array($rows) ? $rows : array();
    }

    /**
     * @param mixed $db PDO_DB
     * @param int   $channel_id
     * @param int   $auth_user_id
     * @param int   $assignee_actor_id
     * @param bool  $admin_bypass
     * @return bool
     */
    public static function isTaskAssigneeAuthorized($db, $channel_id, $auth_user_id, $assignee_actor_id, $admin_bypass = false)
    {
        $assignee = (int) $assignee_actor_id;
        if ($assignee <= 0) {
            return false;
        }
        $list = self::getTaskAssignableActors($db, $channel_id, $auth_user_id, $admin_bypass);
        foreach ($list as $a) {
            if (isset($a['actor_id']) && (int) $a['actor_id'] === $assignee) {
                return true;
            }
        }
        return false;
    }

    // ---------------------------------------------------------------
    // REDIRECT / RESPONSE HELPERS
    // ---------------------------------------------------------------

    public static function maybeRedirectToMessages($thread_id)
    {
        $should_redirect = isset($_POST['redirect_after_post']) || isset($_GET['redirect_after_post']);
        if ($should_redirect && !headers_sent()) {
            $base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
            header('Location: ' . $base . '/messages?thread_id=' . (int) $thread_id, true, 302);
            exit;
        }
    }

    public static function maybeRedirectToThreadMessages($thread_id)
    {
        self::maybeRedirectToMessages($thread_id);
    }

    public static function jsonResponse($payload, $status_code)
    {
        if (!headers_sent()) {
            http_response_code((int) $status_code);
            header('Content-Type: application/json; charset=utf-8');
        }

        $json = json_encode($payload);
        if ($json === false) {
            self::logDefect('P1-JSON-RESPONSE-ENCODE-001', array(
                'status_code' => (int) $status_code,
            ));
            $json = '{"ok":false,"error":"json_encode_failed"}';
        }
        echo $json;
        exit;
    }

    // ---------------------------------------------------------------
    // LEGACY COMPAT (kept to avoid breaking callers; do not use for new code)
    // ---------------------------------------------------------------

    /**
     * @deprecated Use self::generateId() instead.
     *             MAX+1 violates ID doctrine (non-deterministic, race-prone).
     */
    public static function nextId($db, $table, $id_column)
    {
        $value = $db->fetchOne(
            "SELECT COALESCE(MAX({$id_column}), 0) + 1 FROM {$table}",
            array()
        );
        return (int) $value;
    }
}
