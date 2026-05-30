<?php

/**
 * LUPOPEDIA HEADERS
 *
 * lupopedia.headers:
 *   lupopedia.schema: class
 *   file_path_from_root: includes/classes/TaskService.php
 *   header_format_version: "4.0.99"
 *   when_updated: "20260414160000"
 *   purpose: "Central coordination system for tasks. Supports DB + Filesystem sync."
 *   tags: [tasks, coordination, agents, api]
 */

class TaskService
{
    private $db;
    private $prefix;
    private $basePath;

    public function __construct($db, $prefix = null)
    {
        $this->db = $db;
        $this->prefix = $prefix ?: (defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_');
        $this->basePath = defined('ABSPATH') && defined('LUPO_CHANNELS_DIR') 
            ? ABSPATH . LUPO_CHANNELS_DIR 
            : __DIR__ . '/../../channels';

        if (!class_exists('timestamp_ymdhis', false)) {
            require_once __DIR__ . '/TimestampYmdhis.php';
        }
        if (!class_exists('IdGenerator', false)) {
            require_once __DIR__ . '/IdGenerator.php';
        }
    }

    /**
     * Parse chat command: [task] title: X, assigned_to: Y, priority: Z, desc: W
     */
    public function parseTaskCommand($text)
    {
        if (strpos($text, '[task]') === false) {
            return null;
        }

        $content = substr($text, strpos($text, '[task]') + 6);
        $parts = explode(',', $content);
        $data = [];

        foreach ($parts as $part) {
            $kv = explode(':', $part, 2);
            if (count($kv) === 2) {
                $key = trim($kv[0]);
                $val = trim($kv[1]);
                $data[$key] = $val;
            }
        }

        return [
            'title' => $data['title'] ?? ($data['name'] ?? 'Untitled Task'),
            'assigned_to' => $data['assigned_to'] ?? ($data['actor'] ?? null),
            'priority' => $data['priority'] ?? 'normal',
            'description' => $data['desc'] ?? ($data['description'] ?? null),
            'type' => $data['type'] ?? 'general'
        ];
    }

    public function createTask($data)
    {
        $now = (string) timestamp_ymdhis::now();
        $taskId = IdGenerator::generate();
        $taskKey = $data['task_key'] ?? 'task_' . $taskId;

        $fields = [
            'task_id' => $taskId,
            'task_key' => $taskKey,
            'channel_id' => $data['channel_id'] ?? 0,
            'owner_actor_id' => $data['owner_actor_id'] ?? 0,
            'title' => $data['title'] ?? 'Untitled Task',
            'description' => $data['description'] ?? null,
            'task_status' => $data['status'] ?? 'pending',
            'task_priority' => $data['priority'] ?? 'normal',
            'task_type' => $data['type'] ?? 'general',
            'acting_as_actor_id' => $data['assigned_to_id'] ?? null,
            'parent_agent_id' => $data['parent_agent_id'] ?? null,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'visibility_status' => $data['visibility'] ?? 'active',
            'is_deleted' => 0
        ];

        // Ensure ENUM compliance for priority
        $validPriorities = ['low', 'normal', 'high', 'urgent', 'critical'];
        if (!in_array($fields['task_priority'], $validPriorities)) {
            $fields['task_priority'] = 'normal';
        }

        $sql = "INSERT INTO {$this->prefix}tasks (
            task_id, task_key, channel_id, owner_actor_id, title, description,
            task_status, task_priority, task_type, acting_as_actor_id,
            parent_agent_id, created_ymdhis, updated_ymdhis, visibility_status, is_deleted
        ) VALUES (
            :task_id, :task_key, :channel_id, :owner_actor_id, :title, :description,
            :task_status, :task_priority, :task_type, :acting_as_actor_id,
            :parent_agent_id, :created_ymdhis, :updated_ymdhis, :visibility_status, 0
        )";

        $this->db->query($sql, $fields);

        // Sync to filesystem for IDE agents
        $this->syncToFilesystem($fields);

        return $taskId;
    }

    public function updateTask($taskId, $data)
    {
        $now = (string) timestamp_ymdhis::now();
        $data['updated_ymdhis'] = $now;

        $allowedFields = [
            'title', 'description', 'task_status', 'task_priority', 
            'task_type', 'acting_as_actor_id', 'started_ymdhis', 
            'completed_ymdhis', 'actual_duration_seconds', 'metadata_json',
            'visibility_status', 'is_deleted', 'deleted_ymdhis'
        ];

        $updates = [];
        $params = ['task_id' => $taskId];

        foreach ($data as $k => $v) {
            if (in_array($k, $allowedFields)) {
                $updates[] = "{$k} = :{$k}";
                $params[$k] = $v;
            }
        }

        if (empty($updates)) return false;

        $updates[] = "updated_ymdhis = :updated_ymdhis";
        $params['updated_ymdhis'] = $now;

        $sql = "UPDATE {$this->prefix}tasks SET " . implode(', ', $updates) . " WHERE task_id = :task_id";
        $this->db->query($sql, $params);

        // Refresh and sync to FS
        $fullTask = $this->getTaskById($taskId);
        if ($fullTask) {
            $this->syncToFilesystem($fullTask);
        }

        return true;
    }

    public function getTaskById($taskId)
    {
        return $this->db->fetchRow(
            "SELECT * FROM {$this->prefix}tasks WHERE task_id = :id AND is_deleted = 0",
            ['id' => $taskId]
        );
    }

    public function listTasks($filters = [])
    {
        $where = ["is_deleted = 0"];
        $params = [];

        if (!empty($filters['channel_id'])) {
            $where[] = "channel_id = :cid";
            $params['cid'] = $filters['channel_id'];
        }
        if (!empty($filters['status'])) {
            $where[] = "task_status = :status";
            $params['status'] = $filters['status'];
        }
        if (!empty($filters['actor_id'])) {
            $where[] = "acting_as_actor_id = :aid";
            $params['aid'] = $filters['actor_id'];
        }

        $sql = "SELECT * FROM {$this->prefix}tasks WHERE " . implode(' AND ', $where) . " ORDER BY task_priority DESC, created_ymdhis DESC";
        return $this->db->fetchAll($sql, $params);
    }

    public function getNextTaskForAgent($actorId)
    {
        return $this->db->fetchRow(
            "SELECT * FROM {$this->prefix}tasks 
             WHERE acting_as_actor_id = :aid 
             AND task_status = 'pending' 
             AND is_deleted = 0 
             ORDER BY task_priority DESC, created_ymdhis ASC 
             LIMIT 1",
            ['aid' => $actorId]
        );
    }

    private function syncToFilesystem($task)
    {
        $channelId = $task['channel_id'] ?? 0;
        $status = $task['task_status'] ?? 'pending';
        $taskKey = $task['task_key'] ?? 'task_' . $task['task_id'];

        $dir = $this->basePath . DIRECTORY_SEPARATOR . $channelId . DIRECTORY_SEPARATOR . 'tasks' . DIRECTORY_SEPARATOR . $status;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . DIRECTORY_SEPARATOR . $taskKey . '.json';
        file_put_contents($path, json_encode($task, JSON_PRETTY_PRINT));

        // Clean up old status files
        $statuses = ['pending', 'active', 'completed'];
        foreach ($statuses as $s) {
            if ($s === $status) continue;
            $oldPath = $this->basePath . DIRECTORY_SEPARATOR . $channelId . DIRECTORY_SEPARATOR . 'tasks' . DIRECTORY_SEPARATOR . $s . DIRECTORY_SEPARATOR . $taskKey . '.json';
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }
    }
}
