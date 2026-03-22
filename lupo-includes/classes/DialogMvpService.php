<?php
/**
 * Dialog MVP utility service.
 *
 * Provides shared helpers for the Web Dialog MVP endpoints without filesystem writes.
 */
class DialogMvpService
{
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
        return (int) gmdate('YmdHis');
    }

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
        $actor_id = (int) $actor_id;
        $auth_user_id = (int) $auth_user_id;
        if ($actor_id <= 0 || $auth_user_id <= 0) {
            return false;
        }

        $table_prefix = self::getTablePrefix();
        $actors_table = $table_prefix . 'actors';
        $mapping_table = $table_prefix . 'actor_auth_users';

        $primary = $db->fetchRow(
            "SELECT actor_id FROM {$actors_table} WHERE actor_id = :actor_id AND (is_deleted = 0 OR is_deleted IS NULL) AND actor_source_id = :auth_user_id AND (actor_source_type = 'user' OR actor_source_type = 'lupo_auth_users') LIMIT 1",
            array(
                'actor_id' => $actor_id,
                'auth_user_id' => $auth_user_id
            )
        );
        if ($primary) {
            return true;
        }

        if (!self::tableExists($db, $mapping_table)) {
            return false;
        }

        $mapped = $db->fetchRow(
            "SELECT actor_auth_user_id FROM {$mapping_table} WHERE actor_id = :actor_id AND auth_user_id = :auth_user_id AND is_deleted = 0 AND status = :status LIMIT 1",
            array(
                'actor_id' => $actor_id,
                'auth_user_id' => $auth_user_id,
                'status' => 'active'
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
        $actor_id = (int) $actor_id;
        $channel_id = (int) $channel_id;
        if ($actor_id <= 0 || $channel_id <= 0) {
            return false;
        }

        $table = self::getTablePrefix() . 'actor_channels';
        $row = $db->fetchRow(
            "SELECT actor_channel_id FROM {$table} WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1",
            array(
                'actor_id' => $actor_id,
                'channel_id' => $channel_id
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
            "SELECT actor_id FROM {$table} WHERE actor_id = :actor_id AND is_deleted = 0 LIMIT 1",
            array('actor_id' => (int) $actor_id)
        );

        return $row ? true : false;
    }

    public static function nextId($db, $table, $id_column)
    {
        $value = $db->fetchOne(
            "SELECT COALESCE(MAX({$id_column}), 0) + 1 FROM {$table}",
            array()
        );

        return (int) $value;
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
        return LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-config' . DIRECTORY_SEPARATOR . 'runtime_actors.yaml';
    }

    public static function fetchThread($db, $thread_id)
    {
        $table = self::getTablePrefix() . 'dialog_threads';
        return $db->fetchRow(
            "SELECT dialog_thread_id, channel_id, title, assigned_actor_id, status FROM {$table} WHERE dialog_thread_id = :thread_id AND is_deleted = 0 LIMIT 1",
            array('thread_id' => (int) $thread_id)
        );
    }

    public static function fetchMessage($db, $message_id)
    {
        $table = self::getTablePrefix() . 'dialog_messages';
        return $db->fetchRow(
            "SELECT dialog_message_id, dialog_thread_id, channel_id, from_actor_id, to_actor_id, message_text, message_type, created_ymdhis FROM {$table} WHERE dialog_message_id = :message_id AND is_deleted = 0 LIMIT 1",
            array('message_id' => (int) $message_id)
        );
    }

    public static function fetchLastThreadMessages($db, $thread_id, $limit)
    {
        $table_prefix = self::getTablePrefix();
        $limit = (int) $limit;
        if ($limit <= 0) {
            $limit = 5;
        }

        return $db->fetchAll(
            "SELECT m.dialog_message_id, m.from_actor_id, m.to_actor_id, m.message_text, m.message_type, m.created_ymdhis, a.name AS from_actor_name "
            . "FROM {$table_prefix}dialog_messages m "
            . "LEFT JOIN {$table_prefix}actors a ON a.actor_id = m.from_actor_id "
            . "WHERE m.dialog_thread_id = :thread_id AND m.is_deleted = 0 "
            . "ORDER BY m.dialog_message_id DESC LIMIT {$limit}",
            array('thread_id' => (int) $thread_id)
        );
    }

    public static function createDialogMessage($db, $thread_id, $from_actor_id, $message_text, $message_type, $to_actor_id, $mood_rgb, $metadata_json)
    {
        $table_prefix = self::getTablePrefix();
        $t_threads = $table_prefix . 'dialog_threads';
        $t_messages = $table_prefix . 'dialog_messages';
        $thread = self::fetchThread($db, $thread_id);

        if (!$thread) {
            throw new Exception('Thread not found.');
        }

        $channel_id = isset($thread['channel_id']) ? (int) $thread['channel_id'] : 0;
        $now = self::nowYmdHis();
        $message_id = self::nextId($db, $t_messages, 'dialog_message_id');

        $db->insert($t_messages, array(
            'dialog_message_id' => $message_id,
            'message_id' => $message_id,
            'dialog_thread_id' => (int) $thread_id,
            'channel_id' => $channel_id,
            'from_actor_id' => (int) $from_actor_id,
            'to_actor_id' => $to_actor_id !== null ? (int) $to_actor_id : null,
            'message_text' => $message_text,
            'message_body' => $message_text,
            'message_type' => $message_type,
            'metadata_json' => $metadata_json,
            'mood_rgb' => $mood_rgb,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0
        ));

        $db->update(
            $t_threads,
            array(
                'last_message_ymdhis' => $now,
                'updated_ymdhis' => $now
            ),
            'dialog_thread_id = :thread_id',
            array('thread_id' => (int) $thread_id)
        );

        return array(
            'message_id' => $message_id,
            'thread_id' => (int) $thread_id,
            'channel_id' => $channel_id,
            'created_ymdhis' => $now
        );
    }

    public static function resolvePrimaryAuthUserIdForActor($db, $actor_id)
    {
        $table = self::getTablePrefix() . 'actor_auth_users';
        $row = $db->fetchRow(
            "SELECT auth_user_id FROM {$table} WHERE actor_id = :actor_id AND is_deleted = 0 AND status = :status ORDER BY is_primary DESC, routing_priority ASC, actor_auth_user_id ASC LIMIT 1",
            array(
                'actor_id' => (int) $actor_id,
                'status' => 'active'
            )
        );

        if (!$row || empty($row['auth_user_id'])) {
            return 0;
        }

        return (int) $row['auth_user_id'];
    }

    public static function createHumanRequest($db, $thread_id, $message_id, $initiator_actor_id, $target_actor_id, $message_text)
    {
        $table_prefix = self::getTablePrefix();
        $t_requests = $table_prefix . 'human_requests';
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

        $request_id = self::nextId($db, $t_requests, 'request_id');
        $now = self::nowYmdHis();
        $title = 'Human response requested for thread #' . (int) $thread_id;
        $description = trim((string) $message_text);
        if ($description === '') {
            $description = 'A routed dialog message requires human response.';
        }

        $db->insert($t_requests, array(
            'request_id' => $request_id,
            'thread_id' => (int) $thread_id,
            'channel_id' => (int) $thread['channel_id'],
            'project_id' => 0,
            'initiator_actor_id' => (int) $initiator_actor_id,
            'target_auth_user_id' => $target_auth_user_id,
            'request_type' => 'direct_response',
            'request_title' => substr($title, 0, 255),
            'request_description' => $description,
            'subject_type' => 'implementation',
            'subject_reference' => 'dialog_message_id:' . (int) $message_id,
            'priority' => 'normal',
            'request_mode' => 'single_human',
            'status' => 'pending',
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'deleted_ymdhis' => 0
        ));

        return (int) $request_id;
    }

    public static function createDialogThread($db, $channel_id, $title, $created_by_actor_id)
    {
        $table_prefix = self::getTablePrefix();
        $t_channels = $table_prefix . 'channels';
        $t_threads = $table_prefix . 'dialog_threads';
        $channel = $db->fetchRow(
            "SELECT channel_id FROM {$t_channels} WHERE channel_id = :channel_id AND is_deleted = 0 LIMIT 1",
            array('channel_id' => (int) $channel_id)
        );

        if (!$channel) {
            throw new Exception('Channel not found.');
        }

        $thread_id = self::nextId($db, $t_threads, 'dialog_thread_id');
        $now = self::nowYmdHis();

        $db->insert($t_threads, array(
            'dialog_thread_id' => $thread_id,
            'title' => substr((string) $title, 0, 255),
            'last_message_ymdhis' => $now,
            'federation_node_id' => 1,
            'channel_id' => (int) $channel_id,
            'project_slug' => null,
            'task_name' => null,
            'created_by_actor_id' => (int) $created_by_actor_id,
            'status' => 'Open',
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'owner_actor_id' => (int) $created_by_actor_id,
            'assigned_actor_id' => (int) $created_by_actor_id,
            'thread_type' => 'discussion',
            'thread_priority' => 'normal',
            'visibility_status' => 'active'
        ));

        return array(
            'thread_id' => $thread_id,
            'channel_id' => (int) $channel_id,
            'created_by' => (int) $created_by_actor_id,
            'created_ymdhis' => $now
        );
    }

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
        $should_redirect = isset($_POST['redirect_after_post']) || isset($_GET['redirect_after_post']);
        if ($should_redirect && !headers_sent()) {
            $base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
            header('Location: ' . $base . '/messages?thread_id=' . (int) $thread_id, true, 302);
            exit;
        }
    }

    public static function jsonResponse($payload, $status_code)
    {
        if (!headers_sent()) {
            http_response_code((int) $status_code);
            header('Content-Type: application/json; charset=utf-8');
        }

        echo json_encode($payload);
        exit;
    }
}
