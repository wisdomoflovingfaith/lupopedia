<?php
/**
 * Lightweight runtime actor loop for the Web Dialog MVP.
 */
class RuntimeActorLoopService
{
    private $db;
    private $prefix;
    private $llm;
    private $escalations;

    public function __construct($db, $prefix, $llm_service, $escalation_service)
    {
        $this->db = $db;
        $this->prefix = $prefix;
        $this->llm = $llm_service;
        $this->escalations = $escalation_service;
    }

    public function processMessage($message_id)
    {
        $message = DialogMvpService::fetchMessage($this->db, $message_id);
        if (!$message) {
            throw new Exception('Message not found.');
        }

        $thread_id = isset($message['dialog_thread_id']) ? (int) $message['dialog_thread_id'] : 0;
        $thread = DialogMvpService::fetchThread($this->db, $thread_id);
        if (!$thread) {
            throw new Exception('Thread not found.');
        }

        $target_actor_id = $this->determineTargetActorId($message, $thread);
        $decision_status = 'queued';
        $response_message_id = 0;
        $human_request_id = 0;
        $escalation = null;
        $message_text = isset($message['message_text']) ? (string) $message['message_text'] : '';

        if ($this->llm->hasRuntimeActor($target_actor_id)) {
            $response_message_id = $this->process_actor_message($target_actor_id, $thread_id);
            $decision_status = $response_message_id > 0 ? 'completed' : 'runtime_failed';
        } else {
            $human_request_id = DialogMvpService::createHumanRequest(
                $this->db,
                $thread_id,
                $message_id,
                (int) $message['from_actor_id'],
                $target_actor_id,
                $message_text
            );
            $decision_status = $human_request_id > 0 ? 'human_dispatched' : 'human_required';
        }

        if ($this->requiresEscalation($message_text)) {
            $task_type = $this->detectEscalationType($message_text);
            $escalation_actor_id = $target_actor_id > 0 ? $target_actor_id : 12;
            $assigned_actor_id = 3;
            // Runtime queue event only; canonical task ownership/state remains in lupo_tasks + TASK_REGISTRY.
            $escalation = $this->escalations->createTask($escalation_actor_id, $thread_id, $message_id, $task_type, $assigned_actor_id);
            $decision_status = 'escalated';
        }

        $routing_decision_id = $this->recordRoutingDecision($message_id, $thread_id, $target_actor_id, $decision_status, $escalation, $human_request_id);

        return array(
            'success' => true,
            'routing_decision_id' => $routing_decision_id,
            'target_actor_id' => $target_actor_id,
            'decision_status' => $decision_status,
            'response_message_id' => $response_message_id > 0 ? (int) $response_message_id : null,
            'human_request_id' => $human_request_id > 0 ? (int) $human_request_id : null,
            'escalation_task_id' => $escalation ? (int) $escalation['escalation_task_id'] : null
        );
    }

    public function process_actor_message($actor_id, $thread_id)
    {
        $actor_id = (int) $actor_id;
        $thread_id = (int) $thread_id;
        if ($actor_id <= 0 || $thread_id <= 0) {
            return 0;
        }

        $context = $this->buildContext($thread_id, $actor_id, 5);
        $response = $this->llm->generateResponse($actor_id, $context);
        $messages = isset($context['messages']) && is_array($context['messages']) ? $context['messages'] : array();
        $to_actor_id = 0;
        if (!empty($messages)) {
            $last = $messages[count($messages) - 1];
            if (isset($last['from_actor_id']) && (int) $last['from_actor_id'] > 0) {
                $to_actor_id = (int) $last['from_actor_id'];
            }
        }

        $created = DialogMvpService::createDialogMessage(
            $this->db,
            $thread_id,
            $actor_id,
            $response,
            'text',
            $to_actor_id,
            '666666',
            json_encode(array('runtime_actor_loop' => true, 'executor' => 'process_actor_message'))
        );

        return isset($created['message_id']) ? (int) $created['message_id'] : 0;
    }

    private function determineTargetActorId($message, $thread)
    {
        if (!empty($message['to_actor_id'])) {
            return (int) $message['to_actor_id'];
        }

        if (!empty($thread['assigned_actor_id'])) {
            return (int) $thread['assigned_actor_id'];
        }

        $text = strtolower((string) $message['message_text']);
        if (preg_match('/documentation|docs|header|writeup|explain/', $text)) {
            return 7;
        }
        if (preg_match('/audit|review|critic|validate/', $text)) {
            return 2;
        }

        return 12;
    }

    private function buildContext($thread_id, $actor_id, $limit)
    {
        $messages = DialogMvpService::fetchLastThreadMessages($this->db, $thread_id, $limit);
        $messages = array_reverse($messages);
        $actor = $this->llm->getActorConfig($actor_id);

        return array(
            'thread_id' => (int) $thread_id,
            'actor_id' => (int) $actor_id,
            'system_prompt' => $actor && isset($actor['system_prompt']) ? $actor['system_prompt'] : '',
            'messages' => $messages
        );
    }

    private function requiresEscalation($message_text)
    {
        return preg_match('/schema change|database schema|new table|add column|migration|doctrine change|code change|modify php|implement in code|update install sql/', strtolower((string) $message_text)) === 1;
    }

    private function detectEscalationType($message_text)
    {
        $text = strtolower((string) $message_text);
        if (preg_match('/schema|table|column|migration|install sql|database schema/', $text)) {
            return 'schema_change';
        }
        if (preg_match('/doctrine/', $text)) {
            return 'doctrine_change';
        }
        return 'code_change';
    }

    private function recordRoutingDecision($message_id, $thread_id, $target_actor_id, $decision_status, $escalation, $human_request_id)
    {
        $table = $this->prefix . 'routing_decisions';
        $now = DialogMvpService::nowYmdHis();
        $routing_decision_id = DialogMvpService::nextId($this->db, $table, 'routing_decision_id');
        $reason = 'Runtime actor loop processed message.';
        if ($escalation) {
            $reason = 'Runtime actor loop created escalation task #' . $escalation['escalation_task_id'] . '.';
        } elseif ($human_request_id > 0) {
            $reason = 'Runtime actor loop created human request #' . $human_request_id . '.';
        }

        $this->db->insert($table, array(
            'routing_decision_id' => $routing_decision_id,
            'actor_id' => (int) $target_actor_id,
            'thread_id' => (int) $thread_id,
            'task_id' => null,
            'routing_strategy' => 'runtime_actor_loop',
            'candidate_users_json' => json_encode(array(array('actor_id' => (int) $target_actor_id))),
            'selected_auth_user_id' => 0,
            'fallback_index' => 0,
            'decision_reason' => $reason,
            'decision_status' => $decision_status,
            'trigger_type' => 'runtime_loop',
            'created_ymdhis' => $now,
            'completed_ymdhis' => $decision_status === 'completed' || $decision_status === 'escalated' ? $now : 0,
            'idempotency_key' => sha1('runtime:' . $message_id . ':' . $target_actor_id . ':' . $now)
        ));

        return $routing_decision_id;
    }
}