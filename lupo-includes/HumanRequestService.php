<?php
/**
 * Human Request Service
 *
 * Thread 1038 governance-enforced implementation.
 * Lifecycle: draft, pending, answered, resolved, cancelled, expired.
 */

class HumanRequestService
{
    private $db;
    private $column_cache = array();
    private $routing_max_attempts = 3;

    public function __construct()
    {
        if (isset($GLOBALS['mydatabase']) && $GLOBALS['mydatabase']) {
            $this->db = $GLOBALS['mydatabase'];
        } elseif (isset($GLOBALS['db']) && $GLOBALS['db']) {
            $this->db = $GLOBALS['db'];
        } elseif (class_exists('DatabaseFactory')) {
            $this->db = DatabaseFactory::getConnection();
        } else {
            throw new Exception('Database connection unavailable for HumanRequestService');
        }
    }

    public function createRequest($data)
    {
        $required = array('thread_id', 'channel_id', 'target_auth_user_id', 'request_type', 'request_title', 'request_description');
        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                throw new Exception('Missing required field: ' . $field);
            }
        }

        $initiator_id = isset($data['initiator_actor_id']) ? (int) $data['initiator_actor_id'] : $this->getCurrentActorId();
        if (!$this->canInitiateRequest($initiator_id)) {
            throw new Exception('Actor not authorized to initiate requests');
        }

        $target_auth_user_id = (int) $data['target_auth_user_id'];
        if (!$this->authUserExists($target_auth_user_id)) {
            throw new Exception('Target auth user not found');
        }

        $request_type = trim((string) $data['request_type']);
        if (!$this->isValidRequestType($request_type)) {
            throw new Exception('Invalid request type: ' . $request_type);
        }

        $priority = isset($data['priority']) ? trim((string) $data['priority']) : 'normal';
        if (!$this->isValidPriority($priority)) {
            throw new Exception('Invalid priority');
        }

        $initiator_auth_user_id = $this->resolveAuthUserIdForActor($initiator_id);
        if ($initiator_auth_user_id > 0 && $initiator_auth_user_id === $target_auth_user_id) {
            throw new Exception('Self-targeting is not allowed');
        }

        $this->validateNoCircularChain((int) $data['thread_id'], $initiator_id, $target_auth_user_id);

        $status = $this->isAutonomousAgent($initiator_id) ? 'draft' : 'pending';
        $request_mode = isset($data['request_mode']) ? trim((string) $data['request_mode']) : 'single_human';
        if ($request_mode === '') {
            $request_mode = 'single_human';
        }

        $request_id = $this->generateNumericId('lupo_human_requests', 'request_id');
        $now = $this->getCurrentYMDHIS();
        $expires_ymdhis = $status === 'pending' ? $this->computeExpiry($now, $priority) : 0;

        $fields = array(
            'request_id', 'thread_id', 'channel_id', 'project_id',
            'initiator_actor_id', 'target_auth_user_id',
            'request_type', 'request_title', 'request_description',
            'subject_type', 'subject_reference',
            'priority', 'status', 'created_ymdhis', 'updated_ymdhis'
        );
        $values = array(
            $request_id,
            (int) $data['thread_id'],
            (int) $data['channel_id'],
            isset($data['project_id']) ? (int) $data['project_id'] : 0,
            $initiator_id,
            $target_auth_user_id,
            $request_type,
            trim((string) $data['request_title']),
            trim((string) $data['request_description']),
            isset($data['subject_type']) ? $data['subject_type'] : null,
            isset($data['subject_reference']) ? $data['subject_reference'] : null,
            $priority,
            $status,
            $now,
            $now
        );

        if ($this->hasColumn('lupo_human_requests', 'request_mode')) {
            $fields[] = 'request_mode';
            $values[] = $request_mode;
        }

        if ($this->hasColumn('lupo_human_requests', 'expires_ymdhis')) {
            $fields[] = 'expires_ymdhis';
            $values[] = $expires_ymdhis;
        }

        $placeholders = array();
        foreach ($fields as $unused) {
            $placeholders[] = '?';
        }

        $sql = 'INSERT INTO lupo_human_requests (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $placeholders) . ')';
        $this->db->execute($sql, $values);

        if (isset($data['context']) && is_array($data['context']) && !empty($data['context'])) {
            $this->addRequestContext($request_id, $data['context']);
        }

        return $request_id;
    }

    public function routeToHumanMvp($actor_id, $thread_id, $trigger_type, $options)
    {
        $actor_id = (int) $actor_id;
        $thread_id = (int) $thread_id;
        $trigger_type = trim((string) $trigger_type);

        if ($actor_id <= 0) {
            throw new Exception('actor_id is required');
        }
        if ($thread_id <= 0) {
            throw new Exception('thread_id is required');
        }
        if ($trigger_type === '') {
            throw new Exception('trigger_type is required');
        }

        $fallback_index = isset($options['fallback_index']) ? (int) $options['fallback_index'] : 0;
        if ($fallback_index < 0) {
            $fallback_index = 0;
        }
        if ($fallback_index >= $this->routing_max_attempts) {
            $fallback_index = $this->routing_max_attempts - 1;
        }

        $now = $this->getCurrentYMDHIS();
        $task_id = isset($options['task_id']) && (int) $options['task_id'] > 0 ? (int) $options['task_id'] : null;

        if ($this->isLoopBlocked($actor_id, $thread_id, $trigger_type, $now)) {
            $routing_decision_id = $this->createRoutingDecision(array(
                'actor_id' => $actor_id,
                'thread_id' => $thread_id,
                'task_id' => $task_id,
                'routing_strategy' => 'primary_then_fallback',
                'candidate_users_json' => '[]',
                'selected_auth_user_id' => 0,
                'fallback_index' => $fallback_index,
                'decision_reason' => 'loop_break_key cooldown active',
                'decision_status' => 'blocked_loop',
                'trigger_type' => $trigger_type,
                'created_ymdhis' => $now,
                'completed_ymdhis' => $now
            ));

            return array(
                'routing_decision_id' => $routing_decision_id,
                'request_id' => 0,
                'selected_auth_user_id' => 0,
                'decision_status' => 'blocked_loop',
                'fallback_index' => $fallback_index
            );
        }

        $candidates = $this->fetchRoutingCandidates($actor_id);
        if (empty($candidates)) {
            $routing_decision_id = $this->createRoutingDecision(array(
                'actor_id' => $actor_id,
                'thread_id' => $thread_id,
                'task_id' => $task_id,
                'routing_strategy' => 'primary_then_fallback',
                'candidate_users_json' => '[]',
                'selected_auth_user_id' => 0,
                'fallback_index' => $fallback_index,
                'decision_reason' => 'no active candidates',
                'decision_status' => 'terminal_no_available_support_human',
                'trigger_type' => $trigger_type,
                'created_ymdhis' => $now,
                'completed_ymdhis' => $now,
                'idempotency_key' => null
            ));

            return array(
                'routing_decision_id' => $routing_decision_id,
                'request_id' => 0,
                'selected_auth_user_id' => 0,
                'decision_status' => 'terminal_no_available_support_human',
                'fallback_index' => $fallback_index
            );
        }

        if (!isset($candidates[$fallback_index])) {
            $routing_decision_id = $this->createRoutingDecision(array(
                'actor_id' => $actor_id,
                'thread_id' => $thread_id,
                'task_id' => $task_id,
                'routing_strategy' => 'primary_then_fallback',
                'candidate_users_json' => json_encode($candidates),
                'selected_auth_user_id' => 0,
                'fallback_index' => $fallback_index,
                'decision_reason' => 'candidate list exhausted for fallback index',
                'decision_status' => 'terminal_no_available_support_human',
                'trigger_type' => $trigger_type,
                'created_ymdhis' => $now,
                'completed_ymdhis' => $now,
                'idempotency_key' => null
            ));

            return array(
                'routing_decision_id' => $routing_decision_id,
                'request_id' => 0,
                'selected_auth_user_id' => 0,
                'decision_status' => 'terminal_no_available_support_human',
                'fallback_index' => $fallback_index
            );
        }

        $selected = $candidates[$fallback_index];
        $selected_auth_user_id = (int) $selected['auth_user_id'];

        // Atomic idempotency guard: DB unique constraint on idempotency_key prevents concurrent duplicates.
        // Two concurrent requests in the same 5-minute bucket race at the DB level; the loser gets a
        // unique constraint exception, detects the key exists, and returns blocked_idempotency.
        $bucket = $this->getFiveMinuteBucketRange($now);
        $idempotency_key = $this->computeIdempotencyKey($actor_id, $thread_id, $trigger_type, $bucket['start']);

        try {
            $routing_decision_id = $this->createRoutingDecision(array(
                'actor_id' => $actor_id,
                'thread_id' => $thread_id,
                'task_id' => $task_id,
                'routing_strategy' => 'primary_then_fallback',
                'candidate_users_json' => json_encode($candidates),
                'selected_auth_user_id' => $selected_auth_user_id,
                'fallback_index' => $fallback_index,
                'decision_reason' => 'deterministic selection by primary_then_fallback',
                'decision_status' => 'selected',
                'trigger_type' => $trigger_type,
                'created_ymdhis' => $now,
                'completed_ymdhis' => 0,
                'idempotency_key' => $idempotency_key
            ));
        } catch (Exception $e) {
            // If the idempotency key is already present, a concurrent request won the race.
            if ($this->idempotencyKeyExists($idempotency_key)) {
                return array(
                    'routing_decision_id' => 0,
                    'request_id' => 0,
                    'selected_auth_user_id' => 0,
                    'decision_status' => 'blocked_idempotency',
                    'fallback_index' => $fallback_index
                );
            }
            throw $e;
        }

        $thread_context = $this->resolveThreadContext($thread_id);
        $request_data = array(
            'thread_id' => $thread_id,
            'channel_id' => isset($options['channel_id']) ? (int) $options['channel_id'] : (int) $thread_context['channel_id'],
            'project_id' => isset($options['project_id']) ? (int) $options['project_id'] : (int) $thread_context['project_id'],
            'initiator_actor_id' => $actor_id,
            'target_auth_user_id' => $selected_auth_user_id,
            'request_type' => isset($options['request_type']) ? (string) $options['request_type'] : 'clarification',
            'request_title' => isset($options['request_title']) ? (string) $options['request_title'] : ('Routing escalation: ' . $trigger_type),
            'request_description' => isset($options['request_description']) ? (string) $options['request_description'] : ('Deterministic routing request for trigger_type=' . $trigger_type),
            'subject_type' => 'routing_decision',
            'subject_reference' => (string) $routing_decision_id,
            'priority' => isset($options['priority']) ? (string) $options['priority'] : 'normal',
            'request_mode' => 'single_human',
            'context' => array(
                array(
                    'type' => 'routing_decision',
                    'content' => 'routing_decision_id=' . $routing_decision_id . '; selected_auth_user_id=' . $selected_auth_user_id,
                    'source_artifact' => 'lupo_routing_decisions',
                    'source_section' => 'routing_decision_id:' . $routing_decision_id
                )
            )
        );

        try {
            $request_id = $this->createRequest($request_data);
        } catch (Exception $e) {
            // Dispatch failed: transition routing decision to terminal failure state.
            // No orphan 'selected' decisions allowed.
            $this->updateRoutingDecisionStatus(
                $routing_decision_id,
                'failed',
                $this->getCurrentYMDHIS(),
                $e->getMessage()
            );
            throw $e;
        }

        $this->updateRoutingDecisionStatus($routing_decision_id, 'dispatched', $this->getCurrentYMDHIS());

        return array(
            'routing_decision_id' => $routing_decision_id,
            'request_id' => (int) $request_id,
            'selected_auth_user_id' => $selected_auth_user_id,
            'decision_status' => 'dispatched',
            'fallback_index' => $fallback_index
        );
    }

    public function respondToRequest($request_id, $response_data)
    {
        $request = $this->getRequest($request_id);
        if (!$request) {
            throw new Exception('Request not found');
        }

        if ($request['status'] !== 'pending') {
            throw new Exception('Only pending requests can be answered');
        }

        if (!isset($response_data['auth_user_id']) || !isset($response_data['actor_id'])) {
            throw new Exception('auth_user_id and actor_id are required');
        }

        $auth_user_id = (int) $response_data['auth_user_id'];
        $actor_id = (int) $response_data['actor_id'];

        if (!$this->validateAuthActorPair($auth_user_id, $actor_id)) {
            throw new Exception('Invalid auth user/actor pairing');
        }

        if ((int) $request['target_auth_user_id'] !== $auth_user_id) {
            throw new Exception('Only target auth user can respond');
        }

        $this->validateActorAuthority($actor_id, $request['request_type']);

        $response_type = isset($response_data['response_type']) ? trim((string) $response_data['response_type']) : 'answer';
        $valid_response_types = array('answer', 'decision', 'clarification', 'escalation');
        if (!in_array($response_type, $valid_response_types, true)) {
            throw new Exception('Invalid response type');
        }

        $decision = isset($response_data['decision']) ? trim((string) $response_data['decision']) : null;
        if ($decision !== null && $decision !== '') {
            $valid_decisions = array('approved', 'rejected', 'needs_revision', 'deferred');
            if (!in_array($decision, $valid_decisions, true)) {
                throw new Exception('Invalid decision value');
            }
        } else {
            $decision = null;
        }

        $response_text = isset($response_data['response_text']) ? trim((string) $response_data['response_text']) : '';
        if ($response_text === '') {
            throw new Exception('response_text is required');
        }

        $response_id = $this->generateNumericId('lupo_human_request_responses', 'response_id');
        $now = $this->getCurrentYMDHIS();

        $sql = 'INSERT INTO lupo_human_request_responses ('
            . 'response_id, request_id, auth_user_id, actor_id, response_type, response_text, reasoning, decision, conditions, response_ymdhis, is_deleted, deleted_ymdhis'
            . ') VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0)';

        $this->db->execute($sql, array(
            $response_id,
            (int) $request_id,
            $auth_user_id,
            $actor_id,
            $response_type,
            $response_text,
            isset($response_data['reasoning']) ? $response_data['reasoning'] : null,
            $decision,
            isset($response_data['conditions']) ? $response_data['conditions'] : null,
            $now
        ));

        $update = array(
            'status' => 'answered',
            'response_text' => $response_text,
            'response_auth_user_id' => $auth_user_id,
            'response_actor_id' => $actor_id,
            'answered_ymdhis' => $now
        );

        if ($this->hasColumn('lupo_human_requests', 'resolved_ymdhis')) {
            $update['resolved_ymdhis'] = 0;
        }

        $this->updateRequest($request_id, $update);

        if ($decision === 'approved' || $decision === 'rejected') {
            $this->transitionStatus($request_id, 'resolved', $actor_id, array('allow_auto' => true));
        }

        return $response_id;
    }

    public function transitionStatus($request_id, $new_status, $actor_id, $options)
    {
        $request = $this->getRequest($request_id);
        if (!$request) {
            throw new Exception('Request not found');
        }

        $new_status = trim((string) $new_status);
        $valid = array('draft', 'pending', 'answered', 'resolved', 'cancelled', 'expired');
        if (!in_array($new_status, $valid, true)) {
            throw new Exception('Invalid lifecycle status');
        }

        $current = $request['status'];
        $actor_id = (int) $actor_id;
        $allow_auto = isset($options['allow_auto']) ? (bool) $options['allow_auto'] : false;

        if ($current === $new_status) {
            return true;
        }

        $is_initiator = ((int) $request['initiator_actor_id'] === $actor_id);
        $is_wolfie = ($actor_id === 1);

        $allowed = false;
        if ($current === 'draft' && $new_status === 'pending') {
            $allowed = $is_wolfie || $is_initiator;
        } elseif ($current === 'pending' && $new_status === 'cancelled') {
            $allowed = $is_initiator || $is_wolfie;
        } elseif ($current === 'pending' && $new_status === 'expired') {
            $allowed = $is_wolfie || $allow_auto;
        } elseif ($current === 'answered' && $new_status === 'resolved') {
            $allowed = $is_initiator || $is_wolfie || $allow_auto;
        } elseif ($current === 'answered' && $new_status === 'pending') {
            $allowed = $is_initiator;
        } elseif ($new_status === 'cancelled') {
            $allowed = $is_initiator || $is_wolfie;
        }

        if (!$allowed) {
            throw new Exception('Invalid lifecycle transition: ' . $current . ' -> ' . $new_status);
        }

        $update = array('status' => $new_status);
        if ($new_status === 'resolved' && $this->hasColumn('lupo_human_requests', 'resolved_ymdhis')) {
            $update['resolved_ymdhis'] = $this->getCurrentYMDHIS();
        }
        if ($new_status === 'pending' && $this->hasColumn('lupo_human_requests', 'expires_ymdhis')) {
            $update['expires_ymdhis'] = $this->computeExpiry($this->getCurrentYMDHIS(), $request['priority']);
        }

        $this->updateRequest($request_id, $update);
        return true;
    }

    public function expireOpenRequests($actor_id)
    {
        if ((int) $actor_id !== 1) {
            throw new Exception('Only WOLFIE can run expireOpenRequests');
        }

        if (!$this->hasColumn('lupo_human_requests', 'expires_ymdhis')) {
            return 0;
        }

        $now = $this->getCurrentYMDHIS();
        $sql = 'SELECT request_id FROM lupo_human_requests WHERE status = ? AND expires_ymdhis > 0 AND expires_ymdhis < ? AND is_deleted = 0';
        $rows = $this->db->fetchAll($sql, array('pending', $now));
        $count = 0;
        foreach ($rows as $row) {
            $this->transitionStatus((int) $row['request_id'], 'expired', $actor_id, array('allow_auto' => true));
            $count++;
        }
        return $count;
    }

    public function getPendingRequests($auth_user_id, $filters)
    {
        $sql = 'SELECT r.*, a_initiator.name as initiator_name, t.title as thread_title, c.channel_name as channel_name '
            . 'FROM lupo_human_requests r '
            . 'LEFT JOIN lupo_actors a_initiator ON r.initiator_actor_id = a_initiator.actor_id '
            . 'LEFT JOIN lupo_dialog_threads t ON r.thread_id = t.thread_id '
            . 'LEFT JOIN lupo_channels c ON r.channel_id = c.channel_id '
            . 'WHERE r.target_auth_user_id = ? AND r.status = ? AND r.is_deleted = 0';

        $params = array((int) $auth_user_id, 'pending');

        if (isset($filters['priority']) && $filters['priority'] !== '') {
            $sql .= ' AND r.priority = ?';
            $params[] = $filters['priority'];
        }
        if (isset($filters['thread_id']) && (int) $filters['thread_id'] > 0) {
            $sql .= ' AND r.thread_id = ?';
            $params[] = (int) $filters['thread_id'];
        }
        if (isset($filters['request_type']) && $filters['request_type'] !== '') {
            $sql .= ' AND r.request_type = ?';
            $params[] = $filters['request_type'];
        }

        $sql .= ' ORDER BY CASE r.priority WHEN ? THEN 1 WHEN ? THEN 2 WHEN ? THEN 3 ELSE 4 END, r.created_ymdhis ASC';
        $params[] = 'high';
        $params[] = 'normal';
        $params[] = 'low';

        return $this->db->fetchAll($sql, $params);
    }

    public function getThreadRequests($thread_id)
    {
        $sql = 'SELECT r.*, a_initiator.name as initiator_name, u.username as target_username '
            . 'FROM lupo_human_requests r '
            . 'LEFT JOIN lupo_actors a_initiator ON r.initiator_actor_id = a_initiator.actor_id '
            . 'LEFT JOIN lupo_auth_users u ON r.target_auth_user_id = u.auth_user_id '
            . 'WHERE r.thread_id = ? AND r.is_deleted = 0 '
            . 'ORDER BY r.created_ymdhis DESC';
        return $this->db->fetchAll($sql, array((int) $thread_id));
    }

    public function getThreadRequestSummary($thread_id)
    {
        $statuses = array('draft', 'pending', 'answered', 'resolved', 'cancelled', 'expired');
        $summary = array('total' => 0);
        foreach ($statuses as $status) {
            $summary[$status] = 0;
        }

        $sql = 'SELECT status, COUNT(*) as cnt FROM lupo_human_requests WHERE thread_id = ? AND is_deleted = 0 GROUP BY status';
        $rows = $this->db->fetchAll($sql, array((int) $thread_id));
        foreach ($rows as $row) {
            $status = $row['status'];
            $cnt = (int) $row['cnt'];
            if (!isset($summary[$status])) {
                $summary[$status] = 0;
            }
            $summary[$status] += $cnt;
            $summary['total'] += $cnt;
        }

        return $summary;
    }

    public function getRequest($request_id)
    {
        $sql = 'SELECT r.*, a.name as initiator_name, u.username as target_username '
            . 'FROM lupo_human_requests r '
            . 'LEFT JOIN lupo_actors a ON r.initiator_actor_id = a.actor_id '
            . 'LEFT JOIN lupo_auth_users u ON r.target_auth_user_id = u.auth_user_id '
            . 'WHERE r.request_id = ? AND r.is_deleted = 0';
        return $this->db->fetchRow($sql, array((int) $request_id));
    }

    public function getRequestResponses($request_id)
    {
        $sql = 'SELECT resp.*, a.name as actor_name, u.username as user_name '
            . 'FROM lupo_human_request_responses resp '
            . 'LEFT JOIN lupo_actors a ON resp.actor_id = a.actor_id '
            . 'LEFT JOIN lupo_auth_users u ON resp.auth_user_id = u.auth_user_id '
            . 'WHERE resp.request_id = ? AND resp.is_deleted = 0 '
            . 'ORDER BY resp.response_ymdhis ASC';
        return $this->db->fetchAll($sql, array((int) $request_id));
    }

    public function updateRequest($request_id, $data)
    {
        if (empty($data)) {
            return;
        }

        $set = array();
        $params = array();
        foreach ($data as $field => $value) {
            if ($value === null) {
                continue;
            }
            $set[] = $field . ' = ?';
            $params[] = $value;
        }

        $set[] = 'updated_ymdhis = ?';
        $params[] = $this->getCurrentYMDHIS();
        $params[] = (int) $request_id;

        $sql = 'UPDATE lupo_human_requests SET ' . implode(', ', $set) . ' WHERE request_id = ?';
        $this->db->execute($sql, $params);
    }

    private function validateNoCircularChain($thread_id, $initiator_actor_id, $target_auth_user_id)
    {
        $target_actor_id = $this->resolveActorIdForAuthUser($target_auth_user_id);
        if ($target_actor_id <= 0) {
            return;
        }

        $sql = 'SELECT 1 FROM lupo_human_requests '
            . 'WHERE thread_id = ? AND initiator_actor_id = ? AND target_auth_user_id = ? '
            . 'AND status IN (?, ?, ?) AND is_deleted = 0 LIMIT 1';
        $row = $this->db->fetchOne($sql, array((int) $thread_id, (int) $target_actor_id, $this->resolveAuthUserIdForActor($initiator_actor_id), 'draft', 'pending', 'answered'));

        if ($row) {
            throw new Exception('Circular request chain detected in this thread');
        }
    }

    private function validateActorAuthority($actor_id, $request_type)
    {
        $matrix = array(
            'clarification' => array('any_primary'),
            'approval' => array(1, 12, 8),
            'verification' => array(2, 7),
            'schema_change' => array(1),
            'doctrine_change' => array(1),
            'implementation' => array(8, 12),
            'direct_response' => array('any_primary')
        );

        if (!isset($matrix[$request_type])) {
            throw new Exception('Unsupported request type for authority validation');
        }

        $rule = $matrix[$request_type];
        if (in_array('any_primary', $rule, true)) {
            if ($actor_id >= 1 && $actor_id <= 14) {
                return;
            }
            throw new Exception('Actor not authorized for primary-persona request type');
        }

        if (!in_array((int) $actor_id, $rule, true)) {
            throw new Exception('Actor ' . (int) $actor_id . ' is not authorized for request type ' . $request_type);
        }
    }

    private function resolveActorIdForAuthUser($auth_user_id)
    {
        if (function_exists('lupo_get_actor_id_from_auth_user_id')) {
            $actor_id = (int) lupo_get_actor_id_from_auth_user_id((int) $auth_user_id);
            if ($actor_id > 0) {
                return $actor_id;
            }
        }

        $sql = 'SELECT actor_id FROM lupo_actors WHERE auth_user_id = ? AND is_deleted = 0 ORDER BY actor_id ASC LIMIT 1';
        try {
            $actor_id = $this->db->fetchOne($sql, array((int) $auth_user_id));
            return (int) $actor_id;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function resolveAuthUserIdForActor($actor_id)
    {
        $sql = 'SELECT auth_user_id FROM lupo_actor_auth_users '
            . 'WHERE actor_id = ? AND is_primary = 1 AND status = ? AND is_deleted = 0 '
            . 'ORDER BY routing_priority ASC, auth_user_id ASC LIMIT 1';
        try {
            $auth_user_id = $this->db->fetchOne($sql, array((int) $actor_id, 'active'));
            return (int) $auth_user_id;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function validateAuthActorPair($auth_user_id, $actor_id)
    {
        $resolved_actor_id = $this->resolveActorIdForAuthUser((int) $auth_user_id);
        if ($resolved_actor_id > 0 && (int) $actor_id === $resolved_actor_id) {
            return true;
        }
        return false;
    }

    private function generateNumericId($table, $id_column)
    {
        $sql = 'SELECT COALESCE(MAX(' . $id_column . '), 0) + 1 FROM ' . $table;
        $next = $this->db->fetchOne($sql, array());
        return (int) $next;
    }

    private function addRequestContext($request_id, $context_items)
    {
        foreach ($context_items as $item) {
            $context_id = $this->generateNumericId('lupo_human_request_context', 'context_id');
            $sql = 'INSERT INTO lupo_human_request_context '
                . '(context_id, request_id, context_type, content, source_artifact_path, source_line_range, created_ymdhis) '
                . 'VALUES (?, ?, ?, ?, ?, ?, ?)';
            $this->db->execute($sql, array(
                $context_id,
                (int) $request_id,
                isset($item['type']) ? $item['type'] : 'thread_artifact',
                isset($item['content']) ? $item['content'] : '',
                isset($item['source_artifact']) ? $item['source_artifact'] : null,
                isset($item['source_section']) ? $item['source_section'] : null,
                $this->getCurrentYMDHIS()
            ));
        }
    }

    private function canInitiateRequest($actor_id)
    {
        $actor_id = (int) $actor_id;
        if ($actor_id >= 1 && $actor_id <= 99) {
            return true;
        }
        return $actor_id >= 1000;
    }

    private function isAutonomousAgent($actor_id)
    {
        $actor_id = (int) $actor_id;
        return ($actor_id >= 15 && $actor_id <= 99);
    }

    private function authUserExists($auth_user_id)
    {
        $sql = 'SELECT 1 FROM lupo_auth_users WHERE auth_user_id = ? AND is_deleted = 0';
        return (bool) $this->db->fetchOne($sql, array((int) $auth_user_id));
    }

    private function isValidPriority($priority)
    {
        return in_array($priority, array('high', 'normal', 'low'), true);
    }

    private function isValidRequestType($request_type)
    {
        $valid_types = array('clarification', 'approval', 'verification', 'direct_response', 'schema_change', 'doctrine_change', 'implementation');
        return in_array($request_type, $valid_types, true);
    }

    private function computeExpiry($created_ymdhis, $priority)
    {
        $created = DateTime::createFromFormat('YmdHis', (string) $created_ymdhis, new DateTimeZone('UTC'));
        if (!$created) {
            return 0;
        }

        if ($priority === 'high') {
            $created->modify('+4 days');
        } elseif ($priority === 'normal') {
            $created->modify('+14 days');
        } else {
            return 0;
        }

        return (int) $created->format('YmdHis');
    }

    private function hasColumn($table, $column)
    {
        $cache_key = $table . '.' . $column;
        if (isset($this->column_cache[$cache_key])) {
            return $this->column_cache[$cache_key];
        }

        try {
            $sql = 'SELECT 1 FROM information_schema.columns WHERE table_name = ? AND column_name = ? LIMIT 1';
            $exists = (bool) $this->db->fetchOne($sql, array($table, $column));
            $this->column_cache[$cache_key] = $exists;
            return $exists;
        } catch (Exception $e) {
            $this->column_cache[$cache_key] = false;
            return false;
        }
    }

    private function getCurrentActorId()
    {
        if (function_exists('current_user')) {
            $user = current_user();
            if (is_array($user) && isset($user['actor_id'])) {
                return (int) $user['actor_id'];
            }
        }
        if (isset($_SESSION['actor_id'])) {
            return (int) $_SESSION['actor_id'];
        }
        throw new Exception('No authenticated actor found');
    }

    private function getCurrentYMDHIS()
    {
        return (int) gmdate('YmdHis');
    }

    private function fetchRoutingCandidates($actor_id)
    {
        $sql = 'SELECT auth_user_id, relationship_role, is_primary, routing_priority '
            . 'FROM lupo_actor_auth_users '
            . 'WHERE actor_id = ? AND status = ? AND is_deleted = 0 '
            . 'ORDER BY is_primary DESC, routing_priority ASC, auth_user_id ASC';

        return $this->db->fetchAll($sql, array((int) $actor_id, 'active'));
    }

    private function createRoutingDecision($data)
    {
        $routing_decision_id = $this->generateNumericId('lupo_routing_decisions', 'routing_decision_id');

        $sql = 'INSERT INTO lupo_routing_decisions ('
            . 'routing_decision_id, actor_id, thread_id, task_id, routing_strategy, candidate_users_json, '
            . 'selected_auth_user_id, fallback_index, decision_reason, decision_status, trigger_type, created_ymdhis, completed_ymdhis, idempotency_key'
            . ') VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $this->db->execute($sql, array(
            $routing_decision_id,
            (int) $data['actor_id'],
            (int) $data['thread_id'],
            isset($data['task_id']) ? $data['task_id'] : null,
            $data['routing_strategy'],
            (string) $data['candidate_users_json'],
            (int) $data['selected_auth_user_id'],
            (int) $data['fallback_index'],
            isset($data['decision_reason']) ? $data['decision_reason'] : null,
            $data['decision_status'],
            $data['trigger_type'],
            (int) $data['created_ymdhis'],
            (int) $data['completed_ymdhis'],
            isset($data['idempotency_key']) ? $data['idempotency_key'] : null
        ));

        return (int) $routing_decision_id;
    }

    private function updateRoutingDecisionStatus($routing_decision_id, $decision_status, $completed_ymdhis, $failure_reason = null)
    {
        if ($failure_reason !== null) {
            $sql = 'UPDATE lupo_routing_decisions SET decision_status = ?, completed_ymdhis = ?, decision_reason = ? WHERE routing_decision_id = ?';
            $this->db->execute($sql, array((string) $decision_status, (int) $completed_ymdhis, (string) $failure_reason, (int) $routing_decision_id));
        } else {
            $sql = 'UPDATE lupo_routing_decisions SET decision_status = ?, completed_ymdhis = ? WHERE routing_decision_id = ?';
            $this->db->execute($sql, array((string) $decision_status, (int) $completed_ymdhis, (int) $routing_decision_id));
        }
    }

    private function resolveThreadContext($thread_id)
    {
        $sql = 'SELECT channel_id, project_id FROM lupo_dialog_threads WHERE thread_id = ? LIMIT 1';
        $row = $this->db->fetchRow($sql, array((int) $thread_id));
        if (is_array($row)) {
            return array(
                'channel_id' => isset($row['channel_id']) ? (int) $row['channel_id'] : 0,
                'project_id' => isset($row['project_id']) ? (int) $row['project_id'] : 0
            );
        }

        return array('channel_id' => 0, 'project_id' => 0);
    }

    private function hasActiveIdempotentDecision($actor_id, $thread_id, $trigger_type, $now_ymdhis)
    {
        $bucket = $this->getFiveMinuteBucketRange($now_ymdhis);
        $sql = 'SELECT 1 FROM lupo_routing_decisions '
            . 'WHERE actor_id = ? AND thread_id = ? AND trigger_type = ? '
            . 'AND created_ymdhis >= ? AND created_ymdhis <= ? '
            . 'AND decision_status IN (?, ?) '
            . 'LIMIT 1';

        $row = $this->db->fetchOne($sql, array(
            (int) $actor_id,
            (int) $thread_id,
            (string) $trigger_type,
            (int) $bucket['start'],
            (int) $bucket['end'],
            'selected',
            'dispatched'
        ));

        return (bool) $row;
    }

    private function isLoopBlocked($actor_id, $thread_id, $trigger_type, $now_ymdhis)
    {
        $cooldown_start = $this->subtractMinutesYmdhis($now_ymdhis, 10);

        $sql = 'SELECT COUNT(*) FROM lupo_routing_decisions '
            . 'WHERE actor_id = ? AND thread_id = ? AND trigger_type = ? '
            . 'AND created_ymdhis >= ? '
            . 'AND decision_status IN (?, ?, ?)';

        $count = (int) $this->db->fetchOne($sql, array(
            (int) $actor_id,
            (int) $thread_id,
            (string) $trigger_type,
            (int) $cooldown_start,
            'selected',
            'dispatched',
            'terminal_no_available_support_human'
        ));

        return $count >= $this->routing_max_attempts;
    }

    private function computeIdempotencyKey($actor_id, $thread_id, $trigger_type, $bucket_start)
    {
        return sha1((string)(int) $actor_id . ':' . (string)(int) $thread_id . ':' . $trigger_type . ':' . (string)(int) $bucket_start);
    }

    private function idempotencyKeyExists($key)
    {
        $sql = 'SELECT 1 FROM lupo_routing_decisions WHERE idempotency_key = ? LIMIT 1';
        return (bool) $this->db->fetchOne($sql, array($key));
    }

    private function getFiveMinuteBucketRange($ymdhis)
    {
        $dt = DateTime::createFromFormat('YmdHis', (string) $ymdhis, new DateTimeZone('UTC'));
        if (!$dt) {
            return array('start' => 0, 'end' => 0);
        }

        $minute = (int) $dt->format('i');
        $bucket_minute = (int) (floor($minute / 5) * 5);
        $dt->setTime((int) $dt->format('H'), $bucket_minute, 0);
        $start = (int) $dt->format('YmdHis');

        $dt_end = clone $dt;
        $dt_end->modify('+4 minutes 59 seconds');
        $end = (int) $dt_end->format('YmdHis');

        return array('start' => $start, 'end' => $end);
    }

    private function subtractMinutesYmdhis($ymdhis, $minutes)
    {
        $dt = DateTime::createFromFormat('YmdHis', (string) $ymdhis, new DateTimeZone('UTC'));
        if (!$dt) {
            return 0;
        }

        $dt->modify('-' . (int) $minutes . ' minutes');
        return (int) $dt->format('YmdHis');
    }
}
