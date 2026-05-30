<?php
/**
 * PromptLibraryService — persisted prompt artifacts (library + dispatch).
 *
 * Table: lupo_prompts (install_new_lupopedia.sql). No FKs; IdGenerator for prompt_id.
 */
namespace App\Services;

class PromptLibraryService
{
    const STATUS_DRAFT = 'draft';
    const STATUS_REFINING = 'refining';
    const STATUS_APPROVED = 'approved';
    const STATUS_DISPATCHED = 'dispatched';

    /**
     * @param mixed $db PDO_DB
     * @return string
     */
    public static function prefix($db)
    {
        return defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    /**
     * @param mixed $db
     * @return bool
     */
    public static function promptsTableExists($db)
    {
        if (!class_exists('\DialogMvpService', false) && defined('LUPOPEDIA_PATH')) {
            $p = LUPOPEDIA_PATH . '/lupo-includes/classes/DialogMvpService.php';
            if (is_file($p)) {
                require_once $p;
            }
        }
        if (class_exists('\DialogMvpService', false)) {
            return \DialogMvpService::tableExists($db, self::prefix($db) . 'prompts');
        }
        try {
            $db->fetchOne('SELECT 1 FROM ' . self::prefix($db) . 'prompts WHERE 1 = 0', array());
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param mixed $db
     * @param int   $actor_id
     * @param int   $channel_id
     * @return bool
     */
    public static function actorCanAccessChannel($db, $actor_id, $channel_id)
    {
        $actor_id = (int) $actor_id;
        $channel_id = (int) $channel_id;
        if ($actor_id <= 0 || $channel_id <= 0) {
            return false;
        }
        if (!class_exists('\DialogMvpService', false) && defined('LUPOPEDIA_PATH')) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DialogMvpService.php';
        }
        if (class_exists('\DialogMvpService', false)) {
            return \DialogMvpService::actorHasChannelAccess($db, $actor_id, $channel_id);
        }
        return false;
    }

    /**
     * @param mixed $db
     * @param string $channel_key
     * @return int
     */
    public static function resolveChannelIdByKey($db, $channel_key)
    {
        $channel_key = trim((string) $channel_key);
        if ($channel_key === '') {
            return 0;
        }
        $p = self::prefix($db);
        $row = $db->fetchRow(
            "SELECT channel_id FROM {$p}channels WHERE channel_key = :ck AND is_deleted = 0 LIMIT 1",
            array('ck' => $channel_key)
        );
        if ($row && !empty($row['channel_id'])) {
            return (int) $row['channel_id'];
        }
        return 0;
    }

    /**
     * @param mixed $db
     * @param int    $actor_id
     * @param string $channel_key
     * @return bool
     */
    public static function actorCanAccessChannelKey($db, $actor_id, $channel_key)
    {
        $cid = self::resolveChannelIdByKey($db, $channel_key);
        return self::actorCanAccessChannel($db, $actor_id, $cid);
    }

    /**
     * @param mixed $db
     * @param int   $actor_id
     * @return array
     */
    public static function listByChannelKey($db, $channel_key, $limit = 200)
    {
        if (!self::promptsTableExists($db)) {
            return array();
        }
        $channel_key = trim((string) $channel_key);
        $limit = (int) $limit;
        if ($limit <= 0) {
            $limit = 200;
        }
        if ($limit > 500) {
            $limit = 500;
        }
        $p = self::prefix($db);
        $rows = $db->fetchAll(
            "SELECT prompt_id, title, prompt_text, status, created_by_actor_id, created_ymdhis, last_updated_ymdhis, channel_key, thread_id
             FROM {$p}prompts
             WHERE channel_key = :ck AND is_deleted = 0
             ORDER BY last_updated_ymdhis DESC
             LIMIT {$limit}",
            array('ck' => $channel_key)
        );
        return is_array($rows) ? $rows : array();
    }

    /**
     * @param mixed $db
     * @param int   $prompt_id
     * @param int   $actor_id  requester (must be creator or channel member)
     * @return array|null
     */
    public static function getByIdForActor($db, $prompt_id, $actor_id)
    {
        if (!self::promptsTableExists($db)) {
            return null;
        }
        $prompt_id = (int) $prompt_id;
        $actor_id = (int) $actor_id;
        if ($prompt_id <= 0 || $actor_id <= 0) {
            return null;
        }
        $p = self::prefix($db);
        $row = $db->fetchRow(
            "SELECT prompt_id, title, prompt_text, status, created_by_actor_id, created_ymdhis, last_updated_ymdhis, channel_key, thread_id
             FROM {$p}prompts
             WHERE prompt_id = :pid AND is_deleted = 0 LIMIT 1",
            array('pid' => $prompt_id)
        );
        if (!$row) {
            return null;
        }
        $ck = isset($row['channel_key']) ? (string) $row['channel_key'] : '';
        if ((int) $row['created_by_actor_id'] === $actor_id) {
            return $row;
        }
        if ($ck !== '' && self::actorCanAccessChannelKey($db, $actor_id, $ck)) {
            return $row;
        }
        return null;
    }

    /**
     * @param mixed $db
     * @return bool|string error string on failure
     */
    public static function validateStatus($status)
    {
        $status = strtolower(trim((string) $status));
        $allowed = array(self::STATUS_DRAFT, self::STATUS_REFINING, self::STATUS_APPROVED, self::STATUS_DISPATCHED);
        if (!in_array($status, $allowed, true)) {
            return 'Invalid status.';
        }
        return true;
    }

    /**
     * @param mixed $db
     * @param int   $actor_id
     * @param array $data prompt_id?, title, prompt_text, status, channel_key, thread_id
     * @return array{ok:bool, prompt_id?:int, error?:string}
     */
    public static function savePrompt($db, $actor_id, $data)
    {
        if (!self::promptsTableExists($db)) {
            return array('ok' => false, 'error' => 'lupo_prompts table is not installed.');
        }
        $actor_id = (int) $actor_id;
        if ($actor_id <= 0) {
            return array('ok' => false, 'error' => 'Actor required.');
        }
        $channel_key = isset($data['channel_key']) ? trim((string) $data['channel_key']) : '';
        if ($channel_key === '' || !self::actorCanAccessChannelKey($db, $actor_id, $channel_key)) {
            return array('ok' => false, 'error' => 'Invalid channel_key or not a member.');
        }
        $title = isset($data['title']) ? trim((string) $data['title']) : '';
        if ($title === '') {
            return array('ok' => false, 'error' => 'title is required.');
        }
        $prompt_text = isset($data['prompt_text']) ? (string) $data['prompt_text'] : '';
        if (trim($prompt_text) === '') {
            return array('ok' => false, 'error' => 'prompt_text is required.');
        }
        $status = isset($data['status']) ? strtolower(trim((string) $data['status'])) : self::STATUS_DRAFT;
        $vs = self::validateStatus($status);
        if ($vs !== true) {
            return array('ok' => false, 'error' => (string) $vs);
        }
        $thread_id = isset($data['thread_id']) ? trim((string) $data['thread_id']) : '';
        if (strlen($thread_id) > 64) {
            $thread_id = substr($thread_id, 0, 64);
        }
        $p = self::prefix($db);
        if (!class_exists('IdGenerator', false) && defined('LUPOPEDIA_PATH')) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/IdGenerator.php';
        }
        if (!class_exists('timestamp_ymdhis', false) && defined('LUPOPEDIA_PATH')) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/TimestampYmdhis.php';
        }
        $now = class_exists('timestamp_ymdhis', false) ? (int) \timestamp_ymdhis::now() : (int) gmdate('YmdHis');

        $existing_id = isset($data['prompt_id']) ? (int) $data['prompt_id'] : 0;
        if ($existing_id > 0) {
            $cur = $db->fetchRow(
                "SELECT prompt_id, created_by_actor_id FROM {$p}prompts WHERE prompt_id = :pid AND is_deleted = 0 LIMIT 1",
                array('pid' => $existing_id)
            );
            if (!$cur) {
                return array('ok' => false, 'error' => 'Prompt not found.');
            }
            if ((int) $cur['created_by_actor_id'] !== $actor_id) {
                return array('ok' => false, 'error' => 'Only the author may update this prompt.');
            }
            $db->update(
                $p . 'prompts',
                array(
                    'title' => substr($title, 0, 255),
                    'prompt_text' => $prompt_text,
                    'status' => $status,
                    'last_updated_ymdhis' => $now,
                    'updated_ymdhis' => $now,
                    'channel_key' => $channel_key,
                    'thread_id' => $thread_id,
                ),
                'prompt_id = :pid',
                array('pid' => $existing_id)
            );
            return array('ok' => true, 'prompt_id' => $existing_id);
        }

        $prompt_id = \IdGenerator::generate();
        $db->insert($p . 'prompts', array(
            'prompt_id' => $prompt_id,
            'title' => substr($title, 0, 255),
            'prompt_text' => $prompt_text,
            'status' => $status,
            'created_by_actor_id' => $actor_id,
            'created_ymdhis' => $now,
            'last_updated_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'channel_key' => $channel_key,
            'thread_id' => $thread_id,
            'is_deleted' => 0,
            'deleted_ymdhis' => null,
        ));
        return array('ok' => true, 'prompt_id' => $prompt_id);
    }

    /**
     * @param mixed $db
     * @param int   $assignee_actor_id
     * @return string
     */
    public static function resolveActorTaskToken($db, $assignee_actor_id)
    {
        $assignee_actor_id = (int) $assignee_actor_id;
        if ($assignee_actor_id <= 0) {
            return 'ACTOR';
        }
        $p = self::prefix($db);
        $row = $db->fetchRow(
            "SELECT name, actor_name, slug FROM {$p}actors WHERE actor_id = :aid AND is_deleted = 0 LIMIT 1",
            array('aid' => $assignee_actor_id)
        );
        if (!$row) {
            return 'ACTOR' . $assignee_actor_id;
        }
        $nm = '';
        if (isset($row['name']) && trim((string) $row['name']) !== '') {
            $nm = trim((string) $row['name']);
        } elseif (isset($row['actor_name']) && trim((string) $row['actor_name']) !== '') {
            $nm = trim((string) $row['actor_name']);
        } elseif (isset($row['slug']) && trim((string) $row['slug']) !== '') {
            $nm = trim((string) $row['slug']);
        }
        if ($nm === '') {
            return 'ACTOR' . $assignee_actor_id;
        }
        return strtoupper($nm);
    }

    /**
     * @param mixed $db
     * @param int   $message_id
     * @param int   $channel_id
     * @param int   $creator_actor_id
     * @param int   $assignee_actor_id
     * @param string $task_body
     * @return int|false
     */
    public static function insertPendingTask($db, $message_id, $channel_id, $creator_actor_id, $assignee_actor_id, $task_body)
    {
        if ((int) $assignee_actor_id <= 0 || trim((string) $task_body) === '') {
            return false;
        }
        if (!class_exists('IdGenerator', false) && defined('LUPOPEDIA_PATH')) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/IdGenerator.php';
        }
        if (!class_exists('timestamp_ymdhis', false) && defined('LUPOPEDIA_PATH')) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/TimestampYmdhis.php';
        }
        $now = class_exists('timestamp_ymdhis', false) ? (int) \timestamp_ymdhis::now() : (int) gmdate('YmdHis');
        $task_id = (int) \IdGenerator::generate();
        $p = self::prefix($db);
        try {
            $db->insert($p . 'dialog_pending_tasks', array(
                'task_id' => $task_id,
                'message_id' => (int) $message_id,
                'channel_id' => (int) $channel_id,
                'assignee_actor_id' => (int) $assignee_actor_id,
                'creator_actor_id' => (int) $creator_actor_id,
                'task_body' => (string) $task_body,
                'status' => 'pending',
                'priority' => 1,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'completed_ymdhis' => null,
            ));
            return $task_id;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param mixed $db
     * @param int   $actor_id sender
     * @param array $opts prompt_id, thread_id (int), channel_id, to_actor_id, dispatch_as_task (bool)
     * @return array{ok:bool, message_id?:int, error?:string}
     */
    public static function dispatchPrompt($db, $actor_id, $opts)
    {
        if (!class_exists('\DialogMvpService', false) && defined('LUPOPEDIA_PATH')) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DialogMvpService.php';
        }
        if (!class_exists('\DialogMvpService', false)) {
            return array('ok' => false, 'error' => 'DialogMvpService unavailable.');
        }
        $actor_id = (int) $actor_id;
        if ($actor_id <= 0) {
            return array('ok' => false, 'error' => 'Actor required.');
        }
        $prompt_id = isset($opts['prompt_id']) ? (int) $opts['prompt_id'] : 0;
        $thread_id = isset($opts['thread_id']) ? (int) $opts['thread_id'] : 0;
        $channel_id = isset($opts['channel_id']) ? (int) $opts['channel_id'] : 0;
        $to_actor_id = isset($opts['to_actor_id']) ? (int) $opts['to_actor_id'] : 0;
        $as_task = !empty($opts['dispatch_as_task']);
        if ($prompt_id <= 0 || $thread_id <= 0 || $channel_id <= 0) {
            return array('ok' => false, 'error' => 'prompt_id, thread_id, and channel_id are required.');
        }
        $prompt = self::getByIdForActor($db, $prompt_id, $actor_id);
        if (!$prompt) {
            return array('ok' => false, 'error' => 'Prompt not found or access denied.');
        }
        if (!self::actorCanAccessChannel($db, $actor_id, $channel_id)) {
            return array('ok' => false, 'error' => 'Not a member of this channel.');
        }
        $th = \DialogMvpService::fetchThread($db, $thread_id);
        if (!$th || (int) $th['channel_id'] !== $channel_id) {
            return array('ok' => false, 'error' => 'Thread not in this channel.');
        }
        $title = isset($prompt['title']) ? (string) $prompt['title'] : '';
        $ptext = isset($prompt['prompt_text']) ? (string) $prompt['prompt_text'] : '';
        $to_bind = ($to_actor_id > 0) ? $to_actor_id : null;
        $meta = array(
            'prompt_library' => array(
                'prompt_id' => $prompt_id,
                'dispatch' => 1,
                'dispatch_as_task' => $as_task ? 1 : 0,
            ),
        );
        $meta_json = json_encode($meta);
        if ($as_task) {
            if ($to_actor_id <= 0) {
                return array('ok' => false, 'error' => 'Task dispatch requires a target actor.');
            }
            $who = self::resolveActorTaskToken($db, $to_actor_id);
            $raw = '[task] who: ' . $who . ' what: ' . $ptext;
            $created = \DialogMvpService::createDialogMessage(
                $db,
                $thread_id,
                $actor_id,
                $raw,
                'task',
                $to_bind,
                '666666',
                $meta_json
            );
            self::insertPendingTask($db, (int) $created['message_id'], $channel_id, $actor_id, $to_actor_id, $ptext);
        } else {
            $body = "[prompt artifact #" . $prompt_id . ": " . $title . "]\n\n" . $ptext;
            $created = \DialogMvpService::createDialogMessage(
                $db,
                $thread_id,
                $actor_id,
                $body,
                'stdout',
                $to_bind,
                '666666',
                $meta_json
            );
        }
        $now = \DialogMvpService::nowYmdHis();
        $p = self::prefix($db);
        $db->update(
            $p . 'prompts',
            array(
                'status' => self::STATUS_DISPATCHED,
                'last_updated_ymdhis' => $now,
                'updated_ymdhis' => $now,
            ),
            'prompt_id = :pid AND is_deleted = 0',
            array('pid' => $prompt_id)
        );
        return array('ok' => true, 'message_id' => isset($created['message_id']) ? (int) $created['message_id'] : 0);
    }
}
