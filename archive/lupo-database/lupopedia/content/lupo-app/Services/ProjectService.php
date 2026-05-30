<?php
/**
 * Project domain service — project registry, create, get, update, archive, freeze.
 * Doctrine: PDO_DB only, BIGINT timestamps, no AUTO_INCREMENT for project_id.
 *
 * @package App\Services
 * @version 4.0.76
 */

namespace App\Services;

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

class ProjectService
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
     * @return string Table name (quoted)
     */
    private function table()
    {
        return $this->db->quoteIdentifier($this->prefix . 'projects');
    }

    /**
     * Create a project. Caller must supply project_id (registry allocation).
     *
     * @param array $data project_id, project_key, project_slug, project_name, federation_node_id, orchestrator_id, ...
     * @return bool Success
     */
    public function createProject($data)
    {
        $t = $this->table();
        $now = (int) gmdate('YmdHis');
        $defaults = array(
            'project_type' => 'standard',
            'status' => 'active',
            'is_active' => 1,
            'is_deleted' => 0,
            'is_archived' => 0,
            'is_frozen' => 0,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'deleted_ymdhis' => 0,
        );
        $row = array_merge($defaults, $data);
        $allowed = array(
            'project_id', 'project_key', 'project_slug', 'project_name', 'federation_node_id',
            'default_channel_id', 'orchestrator_id', 'project_type', 'description', 'status',
            'is_active', 'is_deleted', 'is_archived', 'is_frozen', 'metadata_json',
            'created_ymdhis', 'updated_ymdhis', 'deleted_ymdhis', 'created_by_actor_id', 'updated_by_actor_id',
        );
        $insert = array();
        foreach ($allowed as $col) {
            if (array_key_exists($col, $row)) {
                $insert[$col] = $row[$col];
            }
        }
        if (!isset($insert['project_id']) || !isset($insert['project_key']) || !isset($insert['project_slug'])
            || !isset($insert['project_name']) || !isset($insert['federation_node_id']) || !isset($insert['orchestrator_id'])) {
            return false;
        }
        $this->db->insert($this->prefix . 'projects', $insert);
        return true;
    }

    /**
     * Get project by project_id.
     *
     * @param int $project_id
     * @return array|null Row or null
     */
    public function getProjectById($project_id)
    {
        $t = $this->table();
        $id = (int) $project_id;
        $row = $this->db->fetchRow(
            "SELECT * FROM {$t} WHERE project_id = :id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('id' => $id)
        );
        return is_array($row) ? $row : null;
    }

    /**
     * Get project by project_key and federation_node_id.
     *
     * @param string $project_key
     * @param int $federation_node_id
     * @return array|null Row or null
     */
    public function getProjectByKey($project_key, $federation_node_id = 1)
    {
        $t = $this->table();
        $node = (int) $federation_node_id;
        $row = $this->db->fetchRow(
            "SELECT * FROM {$t} WHERE project_key = :key AND federation_node_id = :node AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('key' => $project_key, 'node' => $node)
        );
        return is_array($row) ? $row : null;
    }

    /**
     * Get project by project_slug and federation_node_id.
     *
     * @param string $project_slug
     * @param int $federation_node_id
     * @return array|null Row or null
     */
    public function getProjectBySlug($project_slug, $federation_node_id = 1)
    {
        $t = $this->table();
        $node = (int) $federation_node_id;
        $row = $this->db->fetchRow(
            "SELECT * FROM {$t} WHERE project_slug = :slug AND federation_node_id = :node AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('slug' => $project_slug, 'node' => $node)
        );
        return is_array($row) ? $row : null;
    }

    /**
     * Update project. Sets updated_ymdhis in application.
     *
     * @param int $project_id
     * @param array $data Fields to update (no project_id)
     * @param int|null $updated_by_actor_id Optional actor
     * @return bool Success
     */
    public function updateProject($project_id, $data, $updated_by_actor_id = null)
    {
        $id = (int) $project_id;
        $data['updated_ymdhis'] = (int) gmdate('YmdHis');
        if ($updated_by_actor_id !== null) {
            $data['updated_by_actor_id'] = (int) $updated_by_actor_id;
        }
        unset($data['project_id']);
        $allowed = array(
            'project_key', 'project_slug', 'project_name', 'default_channel_id', 'orchestrator_id',
            'project_type', 'description', 'status', 'is_active', 'is_deleted', 'is_archived', 'is_frozen',
            'metadata_json', 'updated_ymdhis', 'deleted_ymdhis', 'updated_by_actor_id',
        );
        $update = array();
        foreach ($allowed as $col) {
            if (array_key_exists($col, $data)) {
                $update[$col] = $data[$col];
            }
        }
        if (empty($update)) {
            return false;
        }
        $affected = $this->db->update($this->prefix . 'projects', $update, 'project_id = :id', array('id' => $id));
        return $affected !== false;
    }

    /**
     * Set project to archived state.
     *
     * @param int $project_id
     * @param int|null $updated_by_actor_id
     * @return bool Success
     */
    public function archiveProject($project_id, $updated_by_actor_id = null)
    {
        return $this->updateProject($project_id, array(
            'status' => 'archived',
            'is_active' => 0,
            'is_archived' => 1,
        ), $updated_by_actor_id);
    }

    /**
     * Set project to frozen state.
     *
     * @param int $project_id
     * @param int|null $updated_by_actor_id
     * @return bool Success
     */
    public function freezeProject($project_id, $updated_by_actor_id = null)
    {
        return $this->updateProject($project_id, array(
            'status' => 'frozen',
            'is_active' => 0,
            'is_frozen' => 1,
        ), $updated_by_actor_id);
    }

    /**
     * List projects for a federation node (optional filter by status).
     *
     * @param int $federation_node_id
     * @param string|null $status Optional: active, archived, frozen
     * @return array List of project rows
     */
    public function listProjects($federation_node_id = 1, $status = null)
    {
        $t = $this->table();
        $node = (int) $federation_node_id;
        $sql = "SELECT * FROM {$t} WHERE federation_node_id = :node AND (is_deleted = 0 OR is_deleted IS NULL)";
        $params = array('node' => $node);
        if ($status !== null && $status !== '') {
            $sql .= " AND status = :status";
            $params['status'] = $status;
        }
        $sql .= " ORDER BY project_id ASC";
        $rows = $this->db->fetchAll($sql, $params);
        return is_array($rows) ? $rows : array();
    }
}
