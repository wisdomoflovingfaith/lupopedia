<?php
/**
 * Database-only escalation queue service for runtime actor handoff.
 *
 * IMPORTANT AUTHORITY BOUNDARY:
 * - Rows in lupo_escalation_tasks are runtime escalation requests/queue items only.
 * - They are not canonical project task authority and do not replace lupo_tasks or TASK_REGISTRY.
 * - Canonical task authority remains in lupo_tasks + TASK_REGISTRY surfaces.
 * - Queue rows must be projected/exported into canonical task surfaces by a separate synchronization process.
 */
class EscalationTaskService
{
    private $db;
    private $prefix;

    public function __construct($db, $prefix)
    {
        $this->db = $db;
        $this->prefix = $prefix;
    }

    public function createTask($actor_id, $thread_id, $message_id, $task_type, $assigned_actor_id)
    {
        // Queue insert only: status here tracks escalation-queue processing, not project lifecycle authority.
        $table = $this->prefix . 'escalation_tasks';
        if (!DialogMvpService::tableExists($this->db, $table)) {
            throw new Exception('Escalation table is not available. Apply the runtime escalation migration first.');
        }

        $now = DialogMvpService::nowYmdHis();
        $task_id = DialogMvpService::nextId($this->db, $table, 'escalation_task_id');

        $this->db->insert($table, array(
            'escalation_task_id' => $task_id,
            'actor_id' => (int) $actor_id,
            'thread_id' => (int) $thread_id,
            'message_id' => (int) $message_id,
            'task_type' => $task_type,
            'status' => 'open',
            'assigned_actor_id' => (int) $assigned_actor_id,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now
        ));

        return array(
            'escalation_task_id' => $task_id,
            'task_type' => $task_type,
            'assigned_actor_id' => (int) $assigned_actor_id,
            'created_ymdhis' => $now
        );
    }
}