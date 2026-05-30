<?php
/**
 * lupopedia.headers:
 *   header_format_version: "4.1.3"
 *   file_path_from_root: "app/Services/HermesService.php"
 *   web_path: "https://www.lupopedia.com/lupopedia/app/Services/HermesService.php"
 *   status: "complete"
 *   when_updated: "20260418200947"
 *   trust_tier: "canonical"
 *   questions_toon: null
 *   memory_toon: "lupo-memory/development/canonical/1026/04/82_hermes_memory_gateway.toon"
 *   atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
 *   transcript_jsonl: "0/development/hermes-service"
 *   artifact_type: implementation
 *   artifact_kind: library
 *   channel_key: "development"
 *   federation_node_id: 0
 *   thread_id: ""
 *   content_id: null
 *   content_parent_id: 82
 *   content_slug: "hermes-service"
 *   default_collection_id: null
 *   lupopedia.schema: implementation
 *   title: "HermesService — routing, transcript JSONL, pending tasks"
 *   summary: "PRD 82 routing decision (route), file transcript append (appendTranscript), pending task insert (createPendingTask). PDO_DB, IdGenerator, timestamp_ymdhis. Pillar 1/2 defect logging via DialogMvpService::logDefect."
 */
namespace App\Services;

/**
 * HermesService — message routing decision + channel transcript JSONL append + pending task insert.
 *
 * Normative: lupo-docs/prd/82_hermes_message_routing_memory_gateway.md (§3.1–3.3, §4.1).
 * DB access: PDO_DB only. Timestamps: timestamp_ymdhis::now(). IDs: IdGenerator::generate().
 * Transcript payloads: do not invent mood_vector or placeholder mood tokens; neutral handling is
 * lupo-docs/doctrine/COUNTING_IN_LIGHT_DOCTRINE.md (callers own column semantics).
 *
 * @see lupo-docs/doctrine/SURVIVABILITY_DOCTRINE.md (Pillar 1 graceful degradation, Pillar 2 defect logging)
 *
 * Defect pattern IDs (P1-HERMES-*, P2-HERMES-*): listed in lupo-docs/doctrine/AGAPE_DEFECT_TAXONOMY.md where adopted;
 * otherwise treated as PROPOSED- until taxonomy merge.
 */
class HermesService
{
    /** @var mixed PDO_DB */
    private $db;

    /** @var string */
    private $tablePrefix;

    /** @var array|null slug (lowercase) => actor_id from actors/actor_id/registry.json agents map */
    private static $agentsRegistryActorIds = null;

    /**
     * @param mixed $db PDO_DB from DatabaseFactory::getConnection()
     */
    public function __construct($db)
    {
        $this->db = $db;
        $this->tablePrefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        if (!class_exists('\\timestamp_ymdhis', false) && defined('LUPOPEDIA_PATH')) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/TimestampYmdhis.php';
        }
        if (!class_exists('\\IdGenerator', false) && defined('LUPOPEDIA_PATH')) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/IdGenerator.php';
        }
        if (!class_exists('\\Utf8StructuredWrite', false) && defined('LUPOPEDIA_PATH')) {
            $utf8Path = LUPOPEDIA_PATH . '/lupo-includes/classes/Utf8StructuredWrite.php';
            if (is_file($utf8Path)) {
                require_once $utf8Path;
            }
        }
    }

    /**
     * Pillar 2 defect log (learning transfer); never throws. See SURVIVABILITY_DOCTRINE.md (Pillar 1 = graceful degradation, Pillar 2 = recurrence logging).
     *
     * @param string $pattern_id
     * @param array  $context
     */
    private function logDefectIfAvailable($pattern_id, $context = array())
    {
        try {
            if (!is_array($context)) {
                $context = array('value' => (string) $context);
            }
            $context['emitter'] = 'HermesService';
            if (!class_exists('\DialogMvpService', false) && defined('LUPOPEDIA_PATH')) {
                $p = LUPOPEDIA_PATH . '/lupo-includes/classes/DialogMvpService.php';
                if (is_file($p)) {
                    require_once $p;
                }
            }
            if (!class_exists('\DialogMvpService', false) || !is_callable(array('\DialogMvpService', 'logDefect'))) {
                return;
            }
            \DialogMvpService::logDefect((string) $pattern_id, $context);
        } catch (\Exception $e) {
            return;
        }
    }

    /**
     * Route a message and return a routing decision (no transcript write).
     *
     * @param string $message
     * @param int    $from_actor_id
     * @param int    $to_actor_id
     * @param int    $channel_id
     * @param int    $task_assignee_id explicit assignee from UI (no free-form who: parsing)
     * @param int    $auth_user_id      for department + channel validation (0 = skip check; callers should avoid untrusted 0)
     * @param bool   $task_scope_admin_bypass admin may assign to any channel member
     * @return array
     */
    public function route($message, $from_actor_id, $to_actor_id, $channel_id, $task_assignee_id = 0, $auth_user_id = 0, $task_scope_admin_bypass = false)
    {
        $message = trim((string) $message);
        $from_actor_id = (int) $from_actor_id;
        $to_actor_id = (int) $to_actor_id;
        $channel_id = (int) $channel_id;
        $task_assignee_id = (int) $task_assignee_id;
        $auth_user_id = (int) $auth_user_id;

        if (class_exists('\\timestamp_ymdhis', false)) {
            $ts = (int) \timestamp_ymdhis::now();
        } else {
            $this->logDefectIfAvailable('P1-HERMES-TIMESTAMP-FALLBACK-001', array(
                'from_actor_id' => $from_actor_id,
                'channel_id' => $channel_id,
                'reason' => 'timestamp_ymdhis_missing',
            ));
            $ts = (int) gmdate('YmdHis');
        }

        $base = array(
            'ok' => false,
            'action' => 'unknown',
            'task_target_actor_id' => 0,
            'routing_provenance' => 'hermes:unknown',
            'message_type' => 'directed',
            'destination' => 'chat',
            'raw_message' => $message,
            'channel_id' => $channel_id,
            'from_actor_id' => $from_actor_id,
            'task_summary' => '',
            'ts' => $ts,
        );

        if ($message === '') {
            $this->logDefectIfAvailable('P2-HERMES-ROUTE-001', array(
                'from_actor_id' => $from_actor_id,
                'to_actor_id' => $to_actor_id,
                'channel_id' => $channel_id,
                'reason' => 'empty_message',
            ));
            $base['routing_provenance'] = 'hermes:error';
            return $base;
        }

        if (preg_match('/^send\\s+to\\s+(\\S+)/i', $message, $m)) {
            $base['ok'] = true;
            $base['action'] = 'cross_channel';
            $base['message_type'] = 'directed';
            $base['routing_provenance'] = 'hermes:cross-channel';
            $base['destination'] = 'channel:' . $m[1];
            return $base;
        }

        if (stripos($message, '[task]') === 0) {
            $task_body = trim((string) preg_replace('/^\[task\]\s*/i', '', $message));
            if ($task_body === '') {
                $task_body = $message;
            }
            $aid = (int) $task_assignee_id;
            if ($aid <= 0) {
                $this->logDefectIfAvailable('P2-HERMES-TASK-001', array(
                    'channel_id' => $channel_id,
                    'from_actor_id' => $from_actor_id,
                    'task_assignee_id' => $task_assignee_id,
                    'auth_user_id' => $auth_user_id,
                    'reason' => 'assignee_missing',
                ));
                $base['action'] = 'task';
                $base['message_type'] = 'task';
                $base['routing_provenance'] = 'hermes:error';
                $base['destination'] = 'hermes:error';
                $base['task_summary'] = $task_body;
                return $base;
            }
            if ($auth_user_id > 0) {
                if (!class_exists('\DialogMvpService', false) && defined('LUPOPEDIA_PATH')) {
                    require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DialogMvpService.php';
                }
                if (class_exists('\DialogMvpService', false) && !\DialogMvpService::isTaskAssigneeAuthorized($this->db, $channel_id, $auth_user_id, $aid, (bool) $task_scope_admin_bypass)) {
                    $this->logDefectIfAvailable('P2-HERMES-TASK-001', array(
                        'channel_id' => $channel_id,
                        'from_actor_id' => $from_actor_id,
                        'task_assignee_id' => $task_assignee_id,
                        'auth_user_id' => $auth_user_id,
                        'reason' => 'assignee_unauthorized',
                    ));
                    $base['action'] = 'task';
                    $base['message_type'] = 'task';
                    $base['routing_provenance'] = 'hermes:error';
                    $base['destination'] = 'hermes:error';
                    $base['task_summary'] = $task_body;
                    return $base;
                }
            }
            $base['ok'] = true;
            $base['action'] = 'task';
            $base['task_target_actor_id'] = $aid;
            $base['message_type'] = 'task';
            $base['routing_provenance'] = 'hermes:task-router';
            $base['destination'] = 'lupo_dialog_pending_tasks';
            $base['task_summary'] = $task_body;
            return $base;
        }

        if (stripos($message, '[alert]') === 0) {
            $base['ok'] = true;
            $base['action'] = 'alert';
            $base['message_type'] = 'alert';
            $base['routing_provenance'] = 'hermes:alert';
            $base['destination'] = 'chat';
            return $base;
        }

        if (stripos($message, '[decision]') === 0) {
            $base['ok'] = true;
            $base['action'] = 'decision';
            $base['message_type'] = 'decision';
            $base['routing_provenance'] = 'hermes:decision';
            $base['destination'] = 'lupo_routing_events';
            return $base;
        }

        if (stripos($message, '[question]') === 0 || preg_match('/OQ-\\d+/', $message)) {
            $base['ok'] = true;
            $base['action'] = 'question';
            $base['message_type'] = 'question';
            $base['routing_provenance'] = 'hermes:question';
            $base['destination'] = 'open_questions.md';
            return $base;
        }

        if (stripos($message, '[stderr]') === 0) {
            $base['ok'] = true;
            $base['action'] = 'stderr';
            $base['message_type'] = 'stderr';
            $base['routing_provenance'] = 'hermes:stderr';
            $base['destination'] = 'chat+log';
            return $base;
        }

        if (stripos($message, '[stdout]') === 0) {
            $base['ok'] = true;
            $base['action'] = 'stdout';
            $base['message_type'] = 'stdout';
            $base['routing_provenance'] = 'hermes:stdout';
            $base['destination'] = 'chat+log';
            return $base;
        }

        $base['ok'] = true;
        $base['action'] = 'directed';
        $base['routing_provenance'] = 'hermes:directed';
        $base['destination'] = 'chat';
        return $base;
    }

    /**
     * Append one JSONL line to PRD 82 canonical transcript only (no SQL, no channel_id mirror path).
     * lupo-docs/prd/82_hermes_message_routing_memory_gateway.md — lupo-memory/transcripts/{federation_node_id}/{channel_key}/{thread_slug}.jsonl
     * Pillar 1 path: filesystem continuity; failures logged, no throw.
     *
     * @param int    $federation_node_id
     * @param string $channel_key        path segment (sanitized)
     * @param string $thread_slug        path segment (sanitized)
     * @param array  $message_data       ts, from_actor_id, to_actor_id, message_text, message_type, routing_provenance
     * @return bool
     */
    public function appendTranscript($federation_node_id, $channel_key, $thread_slug, $message_data)
    {
        if (!defined('LUPOPEDIA_PATH')) {
            $this->logDefectIfAvailable('P1-HERMES-TRANSCRIPT-001', array(
                'federation_node_id' => (int) $federation_node_id,
                'channel_key' => (string) $channel_key,
                'thread_slug' => (string) $thread_slug,
                'path' => '',
                'reason' => 'no_lupopedia_path',
            ));
            return false;
        }
        $fed = (int) $federation_node_id;
        if ($fed < 0) {
            $fed = 0;
        }
        $ck = preg_replace('/[^a-z0-9_\-]/', '-', strtolower(trim((string) $channel_key)));
        $tslug = preg_replace('/[^a-z0-9_\-]/', '-', strtolower(trim((string) $thread_slug)));
        if ($ck === '') {
            $this->logDefectIfAvailable('P1-HERMES-TRANSCRIPT-001', array(
                'federation_node_id' => $fed,
                'channel_key' => (string) $channel_key,
                'thread_slug' => (string) $thread_slug,
                'path' => '',
                'reason' => 'invalid_channel_key',
            ));
            return false;
        }
        if ($tslug === '') {
            $this->logDefectIfAvailable('P1-HERMES-TRANSCRIPT-001', array(
                'federation_node_id' => $fed,
                'channel_key' => $ck,
                'thread_slug' => (string) $thread_slug,
                'path' => '',
                'reason' => 'invalid_thread_slug',
            ));
            return false;
        }
        $dir = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-memory' . DIRECTORY_SEPARATOR . 'transcripts'
            . DIRECTORY_SEPARATOR . $fed . DIRECTORY_SEPARATOR . $ck;
        if (!is_dir($dir)) {
            if (!@mkdir($dir, 0755, true) && !is_dir($dir)) {
                $this->logDefectIfAvailable('P1-HERMES-TRANSCRIPT-001', array(
                    'federation_node_id' => $fed,
                    'channel_key' => $ck,
                    'thread_slug' => $tslug,
                    'path' => $dir,
                    'reason' => 'mkdir_failed',
                ));
                return false;
            }
        }
        $path = $dir . DIRECTORY_SEPARATOR . $tslug . '.jsonl';
        $line = json_encode($message_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($line === false) {
            $errMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : 'json_encode_failed';
            $this->logDefectIfAvailable('P1-HERMES-TRANSCRIPT-001', array(
                'federation_node_id' => $fed,
                'channel_key' => $ck,
                'thread_slug' => $tslug,
                'path' => $path,
                'reason' => $errMsg,
            ));
            return false;
        }
        if (class_exists('\Utf8StructuredWrite', false)) {
            $prep = \Utf8StructuredWrite::prepareForFilesystemWrite($line, $path);
            if (empty($prep['ok'])) {
                $this->logDefectIfAvailable('P2-ENCODING-UTF8-STRUCTURED-001', array(
                    'pattern' => 'encoding_mismatch',
                    'file_path' => $path,
                    'sample' => function_exists('mb_substr') ? mb_substr($line, 0, 240, 'UTF-8') : substr($line, 0, 240),
                    'reason' => isset($prep['reason']) ? (string) $prep['reason'] : 'prepare_failed',
                ));
                return false;
            }
            $line = $prep['text'];
        } else {
            $this->logDefectIfAvailable('P1-HERMES-UTF8-GUARD-MISSING-001', array(
                'file_path' => $path,
                'reason' => 'class_not_loaded',
                'federation_node_id' => $fed,
                'channel_key' => $ck,
                'thread_slug' => $tslug,
            ));
        }
        $lineLen = strlen($line);
        if ($lineLen > 262144) {
            $this->logDefectIfAvailable('P2-HERMES-TRANSCRIPT-SIZE-001', array(
                'file_path' => $path,
                'line_bytes' => $lineLen,
                'federation_node_id' => $fed,
                'channel_key' => $ck,
                'thread_slug' => $tslug,
                'reason' => 'transcript_line_soft_size_exceeded',
            ));
        }
        $written = @file_put_contents($path, $line . "\n", FILE_APPEND | LOCK_EX);
        if ($written === false) {
            $this->logDefectIfAvailable('P1-HERMES-TRANSCRIPT-001', array(
                'federation_node_id' => $fed,
                'channel_key' => $ck,
                'thread_slug' => $tslug,
                'path' => $path,
                'reason' => 'file_put_contents_failed',
            ));
            return false;
        }
        return $written !== false;
    }

    /**
     * Insert pending task when routing decision is a resolved task.
     *
     * @param array $routing_decision from route()
     * @param int   $message_id dialog message id or synthetic IdGenerator id
     * @return string|false task_id string on success
     */
    public function createPendingTask($routing_decision, $message_id)
    {
        if (!is_array($routing_decision)) {
            $this->logDefectIfAvailable('P2-HERMES-TASK-CREATE-001', array(
                'message_id' => (int) $message_id,
                'channel_id' => 0,
                'task_target_actor_id' => 0,
                'reason' => 'invalid_routing_decision_not_array',
            ));
            return false;
        }
        if (!isset($routing_decision['action']) || $routing_decision['action'] !== 'task') {
            $this->logDefectIfAvailable('P2-HERMES-TASK-CREATE-001', array(
                'message_id' => (int) $message_id,
                'channel_id' => isset($routing_decision['channel_id']) ? (int) $routing_decision['channel_id'] : 0,
                'task_target_actor_id' => isset($routing_decision['task_target_actor_id']) ? (int) $routing_decision['task_target_actor_id'] : 0,
                'reason' => 'invalid_routing_decision_action',
            ));
            return false;
        }
        if (empty($routing_decision['task_target_actor_id']) || (int) $routing_decision['task_target_actor_id'] <= 0) {
            $this->logDefectIfAvailable('P2-HERMES-TASK-CREATE-001', array(
                'message_id' => (int) $message_id,
                'channel_id' => isset($routing_decision['channel_id']) ? (int) $routing_decision['channel_id'] : 0,
                'task_target_actor_id' => 0,
                'reason' => 'missing_assignee',
            ));
            return false;
        }
        if ($routing_decision['routing_provenance'] === 'hermes:error') {
            $this->logDefectIfAvailable('P2-HERMES-TASK-CREATE-001', array(
                'message_id' => (int) $message_id,
                'channel_id' => isset($routing_decision['channel_id']) ? (int) $routing_decision['channel_id'] : 0,
                'task_target_actor_id' => (int) $routing_decision['task_target_actor_id'],
                'reason' => 'hermes_error_task_decision',
            ));
            return false;
        }

        $original_message_id = (int) $message_id;
        $message_id = (int) $message_id;
        if ($message_id <= 0) {
            $message_id = (int) \IdGenerator::generate();
            $this->logDefectIfAvailable('P1-HERMES-ID-FALLBACK-001', array(
                'original_message_id' => $original_message_id,
                'replacement_message_id' => $message_id,
                'replacement_path' => 'IdGenerator::generate',
            ));
        }

        $taskId = \IdGenerator::generate();
        $channelId = isset($routing_decision['channel_id']) ? (int) $routing_decision['channel_id'] : 0;
        $assignee = (int) $routing_decision['task_target_actor_id'];
        if (class_exists('\\timestamp_ymdhis', false)) {
            $now = (string) \timestamp_ymdhis::now();
        } else {
            $this->logDefectIfAvailable('P1-HERMES-TIMESTAMP-FALLBACK-001', array(
                'message_id' => $message_id,
                'channel_id' => $channelId,
                'task_target_actor_id' => $assignee,
                'reason' => 'timestamp_ymdhis_missing',
            ));
            $now = gmdate('YmdHis');
        }
        $creator = isset($routing_decision['from_actor_id']) ? (int) $routing_decision['from_actor_id'] : 0;
        if ($creator <= 0) {
            $creator = $assignee;
        }
        $body = isset($routing_decision['task_summary']) ? (string) $routing_decision['task_summary'] : (string) $routing_decision['raw_message'];

        $table = $this->tablePrefix . 'dialog_pending_tasks';
        $row = array(
            'task_id' => $taskId,
            'message_id' => $message_id,
            'channel_id' => $channelId,
            'assignee_actor_id' => $assignee,
            'creator_actor_id' => $creator,
            'task_body' => $body,
            'status' => 'pending',
            'priority' => 1,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'completed_ymdhis' => null,
        );

        $ok = $this->db->insert($table, $row);
        if ($ok === false) {
            $this->logDefectIfAvailable('P2-HERMES-TASK-CREATE-001', array(
                'message_id' => $message_id,
                'channel_id' => $channelId,
                'task_target_actor_id' => $assignee,
                'table' => $table,
                'task_id' => (string) $taskId,
                'reason' => 'db_insert_failed',
            ));
            return false;
        }
        return (string) $taskId;
    }

    /**
     * @param string $agent_key
     * @return int
     */
    private function resolveActorKey($agent_key)
    {
        $agent_key = trim((string) $agent_key);
        if ($agent_key === '') {
            return 0;
        }
        $t = $this->tablePrefix . 'actors';
        $sql = "SELECT actor_id FROM {$t} WHERE is_deleted = 0 AND (LOWER(COALESCE(agent_key,'')) = LOWER(:k) OR LOWER(slug) = LOWER(:k2) OR LOWER(actor_name) = LOWER(:k3)) LIMIT 1";
        $row = $this->db->fetchRow($sql, array('k' => $agent_key, 'k2' => $agent_key, 'k3' => $agent_key));
        if ($row && isset($row['actor_id'])) {
            return (int) $row['actor_id'];
        }
        $fromReg = $this->resolveActorFromAgentsRegistry($agent_key);
        if ($fromReg <= 0) {
            $this->logDefectIfAvailable('P2-HERMES-ACTOR-RESOLVE-001', array('agent_key' => $agent_key));
        }
        return $fromReg;
    }

    /**
     * Fallback: registry.json agents map uses lupo_actors.actor_id (see file _notes).
     * Pillar 1: offline registry when DB row absent; load failures logged (P1-HERMES-REGISTRY-001).
     *
     * @param string $agent_key
     * @return int
     */
    private function resolveActorFromAgentsRegistry($agent_key)
    {
        $map = $this->loadAgentsRegistryActorMap();
        $lk = strtolower((string) $agent_key);
        if ($lk !== '' && isset($map[$lk])) {
            return (int) $map[$lk];
        }
        return 0;
    }

    /**
     * @return array map lowercase slug => actor_id
     */
    private function loadAgentsRegistryActorMap()
    {
        if (self::$agentsRegistryActorIds !== null) {
            return self::$agentsRegistryActorIds;
        }
        self::$agentsRegistryActorIds = array();
        if (!defined('LUPOPEDIA_PATH')) {
            $this->logDefectIfAvailable('P1-HERMES-REGISTRY-001', array(
                'path' => '',
                'reason' => 'no_lupopedia_path',
            ));
            return self::$agentsRegistryActorIds;
        }
        $path = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia'
            . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'actor_id' . DIRECTORY_SEPARATOR . 'registry.json';
        if (!is_file($path)) {
            $this->logDefectIfAvailable('P1-HERMES-REGISTRY-001', array(
                'path' => str_replace('\\', '/', $path),
                'reason' => 'file_missing',
            ));
            return self::$agentsRegistryActorIds;
        }
        $raw = @file_get_contents($path);
        if ($raw === false || $raw === '') {
            $this->logDefectIfAvailable('P1-HERMES-REGISTRY-001', array(
                'path' => str_replace('\\', '/', $path),
                'reason' => 'file_unreadable_or_empty',
            ));
            return self::$agentsRegistryActorIds;
        }
        $j = json_decode($raw, true);
        if (!is_array($j)) {
            $this->logDefectIfAvailable('P1-HERMES-REGISTRY-001', array(
                'path' => str_replace('\\', '/', $path),
                'reason' => 'invalid_json',
            ));
            return self::$agentsRegistryActorIds;
        }
        if (!isset($j['agents']) || !is_array($j['agents'])) {
            $this->logDefectIfAvailable('P1-HERMES-REGISTRY-001', array(
                'path' => str_replace('\\', '/', $path),
                'reason' => 'agents_block_missing',
            ));
            return self::$agentsRegistryActorIds;
        }
        foreach ($j['agents'] as $slug => $id) {
            self::$agentsRegistryActorIds[strtolower((string) $slug)] = (int) $id;
        }
        return self::$agentsRegistryActorIds;
    }
}
