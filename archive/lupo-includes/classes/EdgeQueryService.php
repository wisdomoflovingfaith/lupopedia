<?php
/**
 * EdgeQueryService — read-only query interface for the lupo_edges graph.
 *
 * All methods are read-only. Writes go through dedicated migration scripts or
 * future EdgeWriteService (not this class). No mutations, no soft-delete toggles.
 *
 * Use DatabaseFactory::getConnection() — never new PDO() or new PDO_DB().
 *
 * @package Lupopedia
 * @version 4.0.87
 */
class EdgeQueryService
{
    /** @var PDO_DB */
    private $db;

    /** @var string */
    private $prefix;

    /**
     * @param PDO_DB $db     Database wrapper from DatabaseFactory::getConnection()
     * @param string $prefix Table prefix (e.g. 'lupo_')
     */
    public function __construct($db, $prefix)
    {
        $this->db     = $db;
        $this->prefix = $prefix;
    }

    // -------------------------------------------------------------------------
    // Core object-centred lookups
    // -------------------------------------------------------------------------

    /**
     * Return all active edges where the object appears on either side.
     *
     * @param string $objectType e.g. 'channel', 'thread', 'artifact'
     * @param int    $objectId
     * @param int    $limit  Max rows (default 100)
     * @return array
     */
    public function getEdgesForObject($objectType, $objectId, $limit = 100)
    {
        $limit = max(1, (int) $limit);
        return $this->db->fetchAll(
            "SELECT *
             FROM {$this->prefix}edges
             WHERE is_deleted = 0
               AND (
                 (left_object_type  = :ot AND left_object_id  = :oi)
                 OR
                 (right_object_type = :ot2 AND right_object_id = :oi2)
               )
             ORDER BY created_ymdhis DESC
             LIMIT {$limit}",
            array('ot' => $objectType, 'oi' => (int) $objectId,
                  'ot2' => $objectType, 'oi2' => (int) $objectId)
        );
    }

    /**
     * Return active outbound edges from a specific object.
     *
     * @param string $objectType
     * @param int    $objectId
     * @param string $edgeType   Optional filter by edge_type slug
     * @param int    $limit
     * @return array
     */
    public function getOutboundEdges($objectType, $objectId, $edgeType = null, $limit = 100)
    {
        $limit  = max(1, (int) $limit);
        $params = array('ot' => $objectType, 'oi' => (int) $objectId);
        $extra  = '';
        if ($edgeType !== null) {
            $extra = ' AND edge_type = :et';
            $params['et'] = $edgeType;
        }
        return $this->db->fetchAll(
            "SELECT *
             FROM {$this->prefix}edges
             WHERE is_deleted = 0
               AND left_object_type = :ot
               AND left_object_id   = :oi
             {$extra}
             ORDER BY created_ymdhis DESC
             LIMIT {$limit}",
            $params
        );
    }

    /**
     * Return active inbound edges toward a specific object.
     *
     * @param string $objectType
     * @param int    $objectId
     * @param string $edgeType   Optional filter
     * @param int    $limit
     * @return array
     */
    public function getInboundEdges($objectType, $objectId, $edgeType = null, $limit = 100)
    {
        $limit  = max(1, (int) $limit);
        $params = array('ot' => $objectType, 'oi' => (int) $objectId);
        $extra  = '';
        if ($edgeType !== null) {
            $extra = ' AND edge_type = :et';
            $params['et'] = $edgeType;
        }
        return $this->db->fetchAll(
            "SELECT *
             FROM {$this->prefix}edges
             WHERE is_deleted = 0
               AND right_object_type = :ot
               AND right_object_id   = :oi
             {$extra}
             ORDER BY created_ymdhis DESC
             LIMIT {$limit}",
            $params
        );
    }

    // -------------------------------------------------------------------------
    // Edge-type-scoped lookups
    // -------------------------------------------------------------------------

    /**
     * Return all active edges of a given type.
     *
     * @param string $edgeType e.g. 'channel_parent'
     * @param int    $limit
     * @return array
     */
    public function getEdgesByType($edgeType, $limit = 200)
    {
        $limit = max(1, (int) $limit);
        return $this->db->fetchAll(
            "SELECT *
             FROM {$this->prefix}edges
             WHERE is_deleted  = 0
               AND edge_type   = :et
             ORDER BY created_ymdhis DESC
             LIMIT {$limit}",
            array('et' => $edgeType)
        );
    }

    /**
     * Return all active channel_parent edges (hierarchical channel graph).
     *
     * @param int $limit
     * @return array
     */
    public function getChannelParentEdges($limit = 500)
    {
        return $this->getEdgesByType('channel_parent', $limit);
    }

    // -------------------------------------------------------------------------
    // Channel-scoped lookups
    // -------------------------------------------------------------------------

    /**
     * Return active edges scoped to a specific channel.
     *
     * @param int    $channelId
     * @param string $edgeType  Optional filter
     * @param int    $limit
     * @return array
     */
    public function getEdgesByChannel($channelId, $edgeType = null, $limit = 200)
    {
        $limit  = max(1, (int) $limit);
        $params = array('cid' => (int) $channelId);
        $extra  = '';
        if ($edgeType !== null) {
            $extra = ' AND edge_type = :et';
            $params['et'] = $edgeType;
        }
        return $this->db->fetchAll(
            "SELECT *
             FROM {$this->prefix}edges
             WHERE is_deleted = 0
               AND channel_id = :cid
             {$extra}
             ORDER BY created_ymdhis DESC
             LIMIT {$limit}",
            $params
        );
    }

    // -------------------------------------------------------------------------
    // Edge-type registry
    // -------------------------------------------------------------------------

    /**
     * Return all active edge type definitions from lupo_edge_types.
     *
     * @return array
     */
    public function getEdgeTypes()
    {
        return $this->db->fetchAll(
            "SELECT *
             FROM {$this->prefix}edge_types
             WHERE is_deleted = 0
             ORDER BY edge_type_id ASC"
        );
    }

    /**
     * Return a single edge type record by slug.
     *
     * @param string $slug e.g. 'channel_parent'
     * @return array|null
     */
    public function getEdgeTypeBySlug($slug)
    {
        return $this->db->fetchOne(
            "SELECT *
             FROM {$this->prefix}edge_types
             WHERE is_deleted = 0
               AND slug = :slug
             LIMIT 1",
            array('slug' => $slug)
        ) ?: null;
    }

    // -------------------------------------------------------------------------
    // Aggregate / diagnostic
    // -------------------------------------------------------------------------

    /**
     * Return counts of active edges grouped by edge_type.
     *
     * @return array  Each row: ['edge_type' => string, 'cnt' => int]
     */
    public function getEdgeCountsByType()
    {
        return $this->db->fetchAll(
            "SELECT edge_type, COUNT(*) AS cnt
             FROM {$this->prefix}edges
             WHERE is_deleted = 0
             GROUP BY edge_type
             ORDER BY cnt DESC"
        );
    }

    /**
     * Return the total count of active edges.
     *
     * @return int
     */
    public function getTotalEdgeCount()
    {
        $row = $this->db->fetchOne(
            "SELECT COUNT(*) AS cnt
             FROM {$this->prefix}edges
             WHERE is_deleted = 0"
        );
        return $row ? (int) $row['cnt'] : 0;
    }

    /**
     * Check whether a specific directed edge already exists (duplicate guard).
     *
     * @param string $leftType
     * @param int    $leftId
     * @param string $rightType
     * @param int    $rightId
     * @param string $edgeType
     * @return bool
     */
    public function edgeExists($leftType, $leftId, $rightType, $rightId, $edgeType)
    {
        $row = $this->db->fetchOne(
            "SELECT edge_id
             FROM {$this->prefix}edges
             WHERE is_deleted        = 0
               AND left_object_type  = :lt
               AND left_object_id    = :li
               AND right_object_type = :rt
               AND right_object_id   = :ri
               AND edge_type         = :et
             LIMIT 1",
            array(
                'lt' => $leftType,
                'li' => (int) $leftId,
                'rt' => $rightType,
                'ri' => (int) $rightId,
                'et' => $edgeType,
            )
        );
        return !empty($row);
    }
}
