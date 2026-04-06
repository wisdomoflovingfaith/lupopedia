<?php

/**
 * LUPOPEDIA HEADERS (class — YAML excerpt; canonical: lupo-docs/doctrine/LUPOPEDIA_HEADERS/)
 *
 * lupopedia.headers:
 *   lupopedia.schema: class
 *   file_path_from_root: lupo-includes/classes/TaskService.php
 *   last_modified_utc: "20260405233750"
 *   when_updated: "20260405233750"
 *   channel_id: 42
 *   actor_id: 102
 *   delegation_chain: cursor:root
 *   artifact_type: class
 *   artifact_kind: service
 *   purpose: Status-driven channel tasks (filesystem JSON under lupo-channels) and task paths.
 *   tags: [tasks, channels, filesystem, service]
 */

class TaskService
{
    private $db;
    private $prefix;
    private $basePath;

    public function __construct($db, $prefix)
    {
        $this->db = $db;
        $this->prefix = $prefix;
        $this->basePath = ABSPATH . LUPO_CHANNELS_DIR;
        if (!class_exists('timestamp_ymdhis', false)) {
            require_once dirname(__FILE__) . '/TimestampYmdhis.php';
        }
        if (!class_exists('IdGenerator', false)) {
            require_once dirname(__FILE__) . '/IdGenerator.php';
        }
    }

    /**
     * @return string
     */
    private function packedNowUtcString()
    {
        return (string) timestamp_ymdhis::now();
    }

    /**
     * Get the filesystem path for a task based on status
     */
    public function getTaskPath($channelId, $status, $taskKey)
    {
        return $this->basePath . DIRECTORY_SEPARATOR . $channelId . DIRECTORY_SEPARATOR . 'tasks' . DIRECTORY_SEPARATOR . $status . DIRECTORY_SEPARATOR . $taskKey . '.json';
    }

    /**
     * Create a new task (Pending)
     */
    public function createTask($channelId, $taskKey, $ownerActorId, $title, $description, $parentAgentId = null)
    {
        $now = $this->packedNowUtcString();
        $taskId = IdGenerator::generate();

        $taskData = array(
            'task_id' => $taskId,
            'task_key' => $taskKey,
            'channel_id' => $channelId,
            'owner_actor_id' => $ownerActorId,
            'title' => $title,
            'description' => $description,
            'status' => 'pending',
            'parent_agent_id' => $parentAgentId,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now
        );

        // Filesystem
        $path = $this->getTaskPath($channelId, 'pending', $taskKey);
        if (!is_dir(dirname($path)))
            mkdir(dirname($path), 0755, true);
        file_put_contents($path, json_encode($taskData, JSON_PRETTY_PRINT));

        // DB: canonical table lupo_tasks; parent_agent_id in metadata_json (canonical table has no parent_agent_id column)
        $metaJson = $parentAgentId !== null
            ? json_encode(array('parent_agent_id' => $parentAgentId))
            : null;
        $sql = "INSERT INTO {$this->prefix}tasks (
                    task_id, task_key, channel_id, owner_actor_id, title, description,
                    task_status, metadata_json, created_ymdhis, updated_ymdhis, is_deleted
                ) VALUES (
                    :id, :key, :cid, :owner, :title, :desc, 'pending', :meta, :now1, :now2, 0
                )";
        $this->db->query($sql, array(
            'id' => $taskId,
            'key' => $taskKey,
            'cid' => $channelId,
            'owner' => $ownerActorId,
            'title' => $title,
            'desc' => $description,
            'meta' => $metaJson,
            'now1' => $now,
            'now2' => $now
        ));

        return $taskId;
    }

    /**
     * Update task status and move filesystem location
     */
    public function updateStatus($channelId, $taskKey, $newStatus)
    {
        $oldStatus = null;
        $validStatuses = array('pending', 'active', 'completed');

        foreach ($validStatuses as $s) {
            $p = $this->getTaskPath($channelId, $s, $taskKey);
            if (is_file($p)) {
                $oldStatus = $s;
                break;
            }
        }

        if (!$oldStatus || $oldStatus === $newStatus)
            return false;

        $oldPath = $this->getTaskPath($channelId, $oldStatus, $taskKey);
        $newPath = $this->getTaskPath($channelId, $newStatus, $taskKey);

        $data = json_decode(file_get_contents($oldPath), true);
        $data['status'] = $newStatus;
        $data['updated_ymdhis'] = gmdate('YmdHis');

        if (!is_dir(dirname($newPath)))
            mkdir(dirname($newPath), 0755, true);
        file_put_contents($newPath, json_encode($data, JSON_PRETTY_PRINT));
        unlink($oldPath);

        // Update DB
        $sql = "UPDATE {$this->prefix}tasks SET task_status = :status, updated_ymdhis = :now WHERE task_key = :key AND channel_id = :cid";
        $this->db->query($sql, array(
            'status' => $newStatus,
            'now' => $data['updated_ymdhis'],
            'key' => $taskKey,
            'cid' => $channelId
        ));

        return true;
    }

    /**
     * Submit for approval (WOLFIE validation pattern)
     */
    public function requestApproval($channelId, $taskKey, $approverId)
    {
        $path = $this->getTaskPath($channelId, 'active', $taskKey);
        if (!is_file($path))
            return false;

        $data = json_decode(file_get_contents($path), true);
        $data['pending_approval_from'] = $approverId;
        $data['updated_ymdhis'] = $this->packedNowUtcString();

        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));

        // Update DB metadata
        $sql = "UPDATE {$this->prefix}tasks SET metadata_json = :meta, updated_ymdhis = :now WHERE task_key = :key AND channel_id = :cid";
        $this->db->query($sql, array(
            'meta' => json_encode(array('pending_approval_from' => $approverId)),
            'now' => $data['updated_ymdhis'],
            'key' => $taskKey,
            'cid' => $channelId
        ));

        return true;
    }

    /**
     * Get unified task record (filesystem is truth; DB metadata_json merged in).
     * Callers use this or the getters below so persistence strategy is hidden.
     *
     * @param int $channelId
     * @param string $taskKey
     * @return array|null Task data with parent_agent_id, consensus_hash, approval_chain etc., or null if not found
     */
    public function getTaskData($channelId, $taskKey)
    {
        $statuses = array('pending', 'active', 'completed');
        $data = null;
        foreach ($statuses as $s) {
            $path = $this->getTaskPath($channelId, $s, $taskKey);
            if (is_file($path)) {
                $data = json_decode(file_get_contents($path), true);
                break;
            }
        }
        if (!$data)
            return null;

        $row = $this->db->fetchRow(
            "SELECT metadata_json FROM {$this->prefix}tasks WHERE task_key = :key AND channel_id = :cid AND is_deleted = 0 LIMIT 1",
            array('key' => $taskKey, 'cid' => $channelId)
        );
        if ($row && !empty($row['metadata_json'])) {
            $meta = json_decode($row['metadata_json'], true);
            if (is_array($meta)) {
                foreach ($meta as $k => $v) {
                    if (!array_key_exists($k, $data))
                        $data[$k] = $v;
                }
            }
        }
        return $data;
    }

    /**
     * Get parent_agent_id for a task (from FS or metadata_json). Caller does not depend on persistence.
     */
    public function getParentAgentId($channelId, $taskKey)
    {
        $data = $this->getTaskData($channelId, $taskKey);
        if (!$data)
            return null;
        return isset($data['parent_agent_id']) ? $data['parent_agent_id'] : null;
    }

    /**
     * Get consensus_hash for a task (from metadata_json).
     */
    public function getConsensusHash($channelId, $taskKey)
    {
        $data = $this->getTaskData($channelId, $taskKey);
        if (!$data)
            return null;
        return isset($data['consensus_hash']) ? $data['consensus_hash'] : null;
    }

    /**
     * Get approval_chain (e.g. JSON string or array) for a task (from metadata_json).
     */
    public function getApprovalChain($channelId, $taskKey)
    {
        $data = $this->getTaskData($channelId, $taskKey);
        if (!$data)
            return null;
        return isset($data['approval_chain_json']) ? $data['approval_chain_json'] : (isset($data['approval_chain']) ? $data['approval_chain'] : null);
    }

    /**
     * Merge metadata into task and persist to both filesystem and DB. Keys e.g. parent_agent_id, consensus_hash, approval_chain_json.
     *
     * @param int $channelId
     * @param string $taskKey
     * @param array $meta Key-value pairs to merge into task metadata
     * @return bool
     */
    public function setTaskMetadata($channelId, $taskKey, array $meta)
    {
        $data = $this->getTaskData($channelId, $taskKey);
        if (!$data)
            return false;

        foreach ($meta as $k => $v) {
            $data[$k] = $v;
        }
        $data['updated_ymdhis'] = $this->packedNowUtcString();

        $statuses = array('pending', 'active', 'completed');
        foreach ($statuses as $s) {
            $path = $this->getTaskPath($channelId, $s, $taskKey);
            if (is_file($path)) {
                file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
                break;
            }
        }

        $dbMeta = array();
        $metaKeys = array('parent_agent_id', 'consensus_hash', 'approval_chain_json', 'approval_chain', 'pending_approval_from');
        foreach ($metaKeys as $k) {
            if (array_key_exists($k, $data))
                $dbMeta[$k] = $data[$k];
        }
        $metaJson = empty($dbMeta) ? null : json_encode($dbMeta);
        $sql = "UPDATE {$this->prefix}tasks SET metadata_json = :meta, updated_ymdhis = :now WHERE task_key = :key AND channel_id = :cid";
        $this->db->query($sql, array('meta' => $metaJson, 'now' => $data['updated_ymdhis'], 'key' => $taskKey, 'cid' => $channelId));
        return true;
    }
}
