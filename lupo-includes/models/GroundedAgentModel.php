<?php

/**
 * Grounded Agent Model
 * 
 * Implements agent management with no foreign keys doctrine compliance.
 * Actor-centric identity with application-level referential integrity.
 * 
 * @package Lupopedia\Models
 * @version 1.0.0
 * @author Captain Wolfie
 */

class GroundedAgentModel
{
    private $db;
    
    public function __construct($database_connection)
    {
        $this->db = $database_connection;
    }
    
    /**
     * Create new agent with actor record
     */
    public function createAgent($data)
    {
        // Step 1: Create actor record
        $actor_id = $this->createActorRecord([
            'type' => 'agent',
            'created_ymdhis' => date('YmdHis'),
            'updated_ymdhis' => date('YmdHis')
        ]);
        
        // Step 2: Create agent record
        $agent_data = array_merge($data, [
            'actor_id' => $actor_id,
            'created_ymdhis' => date('YmdHis'),
            'updated_ymdhis' => date('YmdHis')
        ]);
        
        $agent_id = $this->insert('lupo_agents', $agent_data);
        
        // Step 3: Create ownership record
        if (isset($data['owner_actor_id'])) {
            $this->createOwnership($agent_id, $data['owner_actor_id']);
        }
        
        return ['agent_id' => $agent_id, 'actor_id' => $actor_id];
    }
    
    /**
     * Get agent by code
     */
    public function getByCode($code)
    {
        $sql = "SELECT * FROM lupo_agents WHERE code = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $code);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Check if actor can use agent
     */
    public function canActorUseAgent($actor_id, $agent_id)
    {
        $agent = $this->getById($agent_id);
        if (!$agent) return false;
        
        // Check ownership
        if ($agent['owner_actor_id'] == $actor_id) {
            return true;
        }
        
        // Check permissions table
        $sql = "SELECT * FROM lupo_agent_owners WHERE agent_id = ? AND owner_actor_id = ? AND is_active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $agent_id, $actor_id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
    
    /**
     * Log action with actor and agent
     */
    public function logAction($actor_id, $agent_id, $action_type, $description, $metadata = [])
    {
        $data = [
            'actor_id' => $actor_id,
            'agent_id' => $agent_id,
            'action_type' => $action_type,
            'description' => $description,
            'metadata' => json_encode($metadata),
            'created_ymdhis' => date('YmdHis')
        ];
        
        return $this->insert('lupo_actor_actions', $data);
    }
    
    // Private helper methods (RESERVED ID DOCTRINE: explicit actor_id, no lastInsertId)
    private function createActorRecord($data)
    {
        $res = $this->db->query("SELECT COALESCE(MAX(actor_id), 0) + 1 AS next_id FROM lupo_actors");
        $row = $res ? $res->fetch_assoc() : null;
        $actor_id = $row && isset($row['next_id']) ? (int) $row['next_id'] : 1;
        $actor_type = isset($data['type']) ? $data['type'] : 'agent';
        $now = isset($data['created_ymdhis']) ? $data['created_ymdhis'] : gmdate('YmdHis');
        $insert_data = array(
            'actor_id' => $actor_id,
            'actor_type' => $actor_type,
            'slug' => 'agent-' . $actor_id,
            'name' => isset($data['name']) ? $data['name'] : 'Agent',
            'created_ymdhis' => $now,
            'updated_ymdhis' => isset($data['updated_ymdhis']) ? $data['updated_ymdhis'] : $now,
            'is_active' => 1,
            'is_deleted' => 0,
        );
        $this->insert('lupo_actors', $insert_data);
        return $actor_id;
    }
    
    private function createOwnership($agent_id, $owner_actor_id)
    {
        $data = [
            'agent_id' => $agent_id,
            'owner_actor_id' => $owner_actor_id,
            'permission_level' => 'owner',
            'granted_ymdhis' => date('YmdHis'),
            'is_active' => 1
        ];
        
        return $this->insert('lupo_agent_owners', $data);
    }
    
    private function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = str_repeat('?,', count($data) - 1) . '?';
        $values = array_values($data);
        
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($values)), ...$values);
        $stmt->execute();
        
        return $this->db->insert_id;
    }
    
    private function getById($agent_id)
    {
        $sql = "SELECT * FROM lupo_agents WHERE agent_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $agent_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
