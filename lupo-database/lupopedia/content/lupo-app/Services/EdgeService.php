<?php

class EdgeService
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Create a new edge in the lupo_edges table
     * 
     * @param string $leftObjectType Type of the left object (e.g., 'content', 'atom')
     * @param int $leftObjectId ID of the left object
     * @param string $rightObjectType Type of the right object (e.g., 'content', 'atom')
     * @param int $rightObjectId ID of the right object
     * @param string $edgeType Type/name of the edge relationship
     * @param int $weightScore Weight score for the edge (default: 0)
     * @param int $sortNum Sort order (default: 0)
     * @param int|null $actorId Actor ID who created the edge (optional)
     * @param int|null $channelId Channel ID for channel-scoped temporal tracking (optional)
     * @param string|null $channelKey Stable string identifier for cross-system temporal linking (optional)
     * @return int The ID of the created edge
     */
    public function createEdge(
        string $leftObjectType,
        int $leftObjectId,
        string $rightObjectType,
        int $rightObjectId,
        string $edgeType,
        int $weightScore = 0,
        int $sortNum = 0,
        ?int $actorId = null,
        ?int $channelId = null,
        ?string $channelKey = null
    ): int {
        $now = time();
        
        $sql = "INSERT INTO lupo_edges (
                    left_object_type, 
                    left_object_id, 
                    right_object_type, 
                    right_object_id, 
                    edge_type, 
                    weight_score, 
                    sort_num, 
                    actor_id, 
                    channel_id,
                    channel_key,
                    created_ymdhis, 
                    updated_ymdhis
                ) VALUES (
                    :left_object_type, 
                    :left_object_id, 
                    :right_object_type, 
                    :right_object_id, 
                    :edge_type, 
                    :weight_score, 
                    :sort_num, 
                    :actor_id,
                    :channel_id,
                    :channel_key,
                    :created_ymdhis, 
                    :updated_ymdhis
                )";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':left_object_type' => $leftObjectType,
            ':left_object_id'   => $leftObjectId,
            ':right_object_type' => $rightObjectType,
            ':right_object_id'  => $rightObjectId,
            ':edge_type'        => $edgeType,
            ':weight_score'     => $weightScore,
            ':sort_num'         => $sortNum,
            ':actor_id'         => $actorId,
            ':channel_id'       => $channelId ?? null,
            ':channel_key'      => $channelKey ?? null,
            ':created_ymdhis'   => $now,
            ':updated_ymdhis'   => $now,
        ]);
        
        return (int)$this->db->lastInsertId();
    }

    /**
     * Soft delete an edge by setting is_deleted flag and deletion timestamp
     * 
     * @param int $edgeId ID of the edge to delete
     * @return bool True if successful, false otherwise
     */
    public function deleteEdge(int $edgeId): bool
    {
        $sql = "UPDATE lupo_edges 
                SET is_deleted = 1, 
                    deleted_ymdhis = :deleted_ymdhis,
                    updated_ymdhis = :updated_ymdhis
                WHERE edge_id = :edge_id 
                AND is_deleted = 0";
        
        $stmt = $this->db->prepare($sql);
        $now = time();
        
        return $stmt->execute([
            ':edge_id'        => $edgeId,
            ':deleted_ymdhis' => $now,
            ':updated_ymdhis' => $now,
        ]);
    }

    /**
     * Get edges by left object
     * 
     * @param string $objectType Type of the object
     * @param int $objectId ID of the object
     * @param string|null $edgeType Optional edge type filter
     * @return array Array of edge records
     */
    public function getEdgesByLeftObject(string $objectType, int $objectId, ?string $edgeType = null): array
    {
        $sql = "SELECT * FROM lupo_edges 
                WHERE left_object_type = :left_object_type 
                AND left_object_id = :left_object_id 
                AND is_deleted = 0";
        
        $params = [
            ':left_object_type' => $objectType,
            ':left_object_id'   => $objectId,
        ];
        
        if ($edgeType) {
            $sql .= " AND edge_type = :edge_type";
            $params[':edge_type'] = $edgeType;
        }
        
        $sql .= " ORDER BY sort_num ASC, created_ymdhis DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get edges by right object
     * 
     * @param string $objectType Type of the object
     * @param int $objectId ID of the object
     * @param string|null $edgeType Optional edge type filter
     * @return array Array of edge records
     */
    public function getEdgesByRightObject(string $objectType, int $objectId, ?string $edgeType = null): array
    {
        $sql = "SELECT * FROM lupo_edges 
                WHERE right_object_type = :right_object_type 
                AND right_object_id = :right_object_id 
                AND is_deleted = 0";
        
        $params = [
            ':right_object_type' => $objectType,
            ':right_object_id'   => $objectId,
        ];
        
        if ($edgeType) {
            $sql .= " AND edge_type = :edge_type";
            $params[':edge_type'] = $edgeType;
        }
        
        $sql .= " ORDER BY sort_num ASC, created_ymdhis DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get edges by channel context
     * 
     * @param int|null $channelId Channel ID to filter by (optional)
     * @param string|null $channelKey Channel key to filter by (optional)
     * @return array Array of edge records
     */
    public function getEdgesByChannel(?int $channelId = null, ?string $channelKey = null): array
    {
        $sql = "SELECT * FROM lupo_edges WHERE is_deleted = 0";
        $params = [];
        
        if ($channelId !== null) {
            $sql .= " AND channel_id = :channel_id";
            $params[':channel_id'] = $channelId;
        }
        
        if ($channelKey !== null) {
            $sql .= " AND channel_key = :channel_key";
            $params[':channel_key'] = $channelKey;
        }
        
        $sql .= " ORDER BY created_ymdhis DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
