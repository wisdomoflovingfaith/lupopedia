<?php
/**
 * MemoryPromotionService
 *
 * Promotes staging memory nodes (embedded year 2000-2099) to canonical
 * memory nodes (embedded year 1000-1999) using Chronological Trust Ladder
 * deterministic ID mapping.
 *
 * Doctrine guarantees:
 * - Prefix-aware tables (no hardcoded lupo_)
 * - Named placeholders only
 * - Explicit SELECT -> INSERT/UPDATE flow (no ON DUPLICATE KEY UPDATE)
 * - One provenance edge per source/target pair (idempotent)
 * - Soft-delete staging source (no hard delete)
 * - Single transaction for atomicity
 */
class MemoryPromotionService
{
    const EDGE_TYPE_PROMOTED_TO = 'promoted_to';
    const PROVENANCE_TOOL = 'memory_promotion_service';
    const PROMOTION_HISTORY_MAX = 10;

    /** @var object */
    private $db;

    /** @var string */
    private $tablePrefix;

    public function __construct($db = null, $tablePrefix = null)
    {
        $this->requireCoreClass('DatabaseFactory', 'DatabaseFactory.php');
        $this->requireCoreClass('IdGenerator', 'IdGenerator.php');
        $this->requireCoreClass('timestamp_ymdhis', 'TimestampYmdhis.php');

        $this->db = $db !== null ? $db : DatabaseFactory::getConnection();
        $this->tablePrefix = $tablePrefix !== null
            ? $tablePrefix
            : (defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_');
    }

    /**
     * Promote one staging memory node to canonical.
     *
     * Return shape:
     * array(
     *   'canonical_memory_node_id' => string,
     *   'status' => 'promoted'|'merged'|'already_promoted',
     *   'edge_created' => bool,
     *   'staging_soft_deleted' => bool
     * )
     *
     * @param int|string $stagingMemoryNodeId
     * @param int $reviewerActorId
     * @return array
     */
    public function promoteStagingToCanonical($stagingMemoryNodeId, $reviewerActorId)
    {
        $stagingId = (string) $stagingMemoryNodeId;
        $reviewerActorId = (int) $reviewerActorId;
        if ($reviewerActorId <= 0) {
            throw new InvalidArgumentException('reviewerActorId must be a positive integer');
        }
        if (!$this->isStagingId($stagingId)) {
            throw new InvalidArgumentException('stagingMemoryNodeId is not in staging tier (year 2000-2099): ' . $stagingId);
        }

        $canonicalId = (string) IdGenerator::toCanonicalId($stagingId);
        if ($canonicalId === $stagingId) {
            throw new RuntimeException('Canonical ID transform failed for staging id: ' . $stagingId);
        }

        $nodesTable = $this->nodesTable();
        $edgesTable = $this->edgesTable();
        $now = (string) timestamp_ymdhis::now();
        $status = 'promoted';
        $edgeCreated = false;
        $stagingSoftDeleted = false;

        $this->db->beginTransaction();
        try {
            $staging = $this->db->fetchRow(
                'SELECT * FROM ' . $this->db->quoteIdentifier($nodesTable)
                . ' WHERE memory_node_id = :memory_node_id LIMIT 1 FOR UPDATE',
                array('memory_node_id' => $stagingId)
            );
            if (!$staging) {
                throw new RuntimeException('Staging node not found: ' . $stagingId);
            }

            $canonical = $this->db->fetchRow(
                'SELECT * FROM ' . $this->db->quoteIdentifier($nodesTable)
                . ' WHERE memory_node_id = :memory_node_id LIMIT 1 FOR UPDATE',
                array('memory_node_id' => $canonicalId)
            );

            $edge = $this->existingPromotionEdge($stagingId, $canonicalId);

            // Idempotent re-run path: edge exists and staging already deleted.
            if ($edge && isset($staging['is_deleted']) && (int) $staging['is_deleted'] === 1) {
                $this->db->commit();
                return array(
                    'canonical_memory_node_id' => $canonicalId,
                    'status' => 'already_promoted',
                    'edge_created' => false,
                    'staging_soft_deleted' => false,
                );
            }

            if (!$canonical) {
                $this->createCanonicalNodeFromStaging($canonicalId, $staging, $reviewerActorId, $now);
                $status = 'promoted';
            } else {
                $this->mergeStagingIntoCanonical($staging, $canonical, $reviewerActorId, $now);
                $status = 'merged';
            }

            if (!$edge) {
                $this->insertPromotionEdge($stagingId, $canonicalId, $reviewerActorId, $now);
                $edgeCreated = true;
            }

            if (isset($staging['is_deleted']) && (int) $staging['is_deleted'] === 0) {
                $this->db->update(
                    $nodesTable,
                    array(
                        'is_deleted' => 1,
                        'deleted_ymdhis' => $now,
                        'updated_ymdhis' => $now,
                    ),
                    'memory_node_id = :memory_node_id',
                    array('memory_node_id' => $stagingId)
                );
                $stagingSoftDeleted = true;
            }

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }

        return array(
            'canonical_memory_node_id' => $canonicalId,
            'status' => $status,
            'edge_created' => $edgeCreated,
            'staging_soft_deleted' => $stagingSoftDeleted,
        );
    }

    /**
     * @param string $canonicalId
     * @param array $staging
     * @param int $reviewerActorId
     * @param string $now
     * @return void
     */
    private function createCanonicalNodeFromStaging($canonicalId, array $staging, $reviewerActorId, $now)
    {
        $key = isset($staging['memory_toon']) ? (string) $staging['memory_toon'] : '';
        $value = isset($staging['memory_value']) ? (string) $staging['memory_value'] : '';
        $ctx = $this->decodeJson(isset($staging['context_json']) ? $staging['context_json'] : null);
        if (isset($ctx['promotion'])) {
            if (!isset($ctx['promotion_history']) || !is_array($ctx['promotion_history'])) {
                $ctx['promotion_history'] = array();
            }
            $ctx['promotion_history'][] = $ctx['promotion'];
            $ctx['promotion_history'] = $this->rotatePromotionHistory($ctx['promotion_history']);
        }
        $ctx['promotion'] = array(
            'source_staging_memory_node_id' => (string) $staging['memory_node_id'],
            'promoted_ymdhis' => $now,
            'promoted_by_actor_id' => $reviewerActorId,
            'service' => self::PROVENANCE_TOOL,
        );

        $insertData = array(
            'memory_node_id' => (string) $canonicalId,
            'created_ymdhis' => isset($staging['created_ymdhis']) ? (string) $staging['created_ymdhis'] : $now,
            'owner_actor_id' => isset($staging['owner_actor_id']) ? (string) $staging['owner_actor_id'] : (string) $reviewerActorId,
            'owner_type' => isset($staging['owner_type']) && $staging['owner_type'] !== '' ? (string) $staging['owner_type'] : 'actor',
            'memory_type' => isset($staging['memory_type']) ? (string) $staging['memory_type'] : 'memory',
            'memory_toon' => $key,
            'memory_value' => $value,
            'context' => isset($staging['context']) && $staging['context'] !== '' ? (string) $staging['context'] : 'experiential',
            'status' => 'supported',
            'review_reason' => null,
            'content_hash' => hash('sha256', $key . "\0" . $value),
            'context_json' => json_encode($ctx),
            'updated_ymdhis' => $now,
            'expires_ymdhis' => isset($staging['expires_ymdhis']) ? (string) $staging['expires_ymdhis'] : '0',
            'is_deleted' => 0,
            'deleted_ymdhis' => 0,
        );

        $this->db->insert($this->nodesTable(), $insertData);
    }

    /**
     * Merge staging into existing canonical using explicit precedence.
     *
     * Stable fields keep canonical values. Mutable payload fields update only when
     * staging has a newer updated_ymdhis.
     *
     * @param array $staging
     * @param array $canonical
     * @param int $reviewerActorId
     * @param string $now
     * @return void
     */
    private function mergeStagingIntoCanonical(array $staging, array $canonical, $reviewerActorId, $now)
    {
        $stagingUpdated = isset($staging['updated_ymdhis']) ? (string) $staging['updated_ymdhis'] : '0';
        $canonicalUpdated = isset($canonical['updated_ymdhis']) ? (string) $canonical['updated_ymdhis'] : '0';
        $stagingIsNewer = strcmp(str_pad($stagingUpdated, 14, '0', STR_PAD_LEFT), str_pad($canonicalUpdated, 14, '0', STR_PAD_LEFT)) > 0;

        $canonicalKey = isset($canonical['memory_toon']) ? (string) $canonical['memory_toon'] : '';
        $canonicalValue = isset($canonical['memory_value']) ? (string) $canonical['memory_value'] : '';
        $stagingKey = isset($staging['memory_toon']) ? (string) $staging['memory_toon'] : '';
        $stagingValue = isset($staging['memory_value']) ? (string) $staging['memory_value'] : '';

        $newKey = $canonicalKey;
        $newValue = $canonicalValue;
        if ($stagingIsNewer) {
            if ($stagingKey !== '') {
                $newKey = $stagingKey;
            }
            if ($stagingValue !== '') {
                $newValue = $stagingValue;
            }
        }

        $ctx = $this->decodeJson(isset($canonical['context_json']) ? $canonical['context_json'] : null);
        if (!isset($ctx['promotion_history']) || !is_array($ctx['promotion_history'])) {
            $ctx['promotion_history'] = array();
        }
        $ctx['promotion_history'][] = array(
            'source_staging_memory_node_id' => (string) $staging['memory_node_id'],
            'merged_ymdhis' => $now,
            'merged_by_actor_id' => $reviewerActorId,
            'staging_was_newer' => $stagingIsNewer ? 1 : 0,
            'service' => self::PROVENANCE_TOOL,
        );
        $ctx['promotion_history'] = $this->rotatePromotionHistory($ctx['promotion_history']);

        $this->db->update(
            $this->nodesTable(),
            array(
                'memory_toon' => $newKey,
                'memory_value' => $newValue,
                'content_hash' => hash('sha256', $newKey . "\0" . $newValue),
                'context_json' => json_encode($ctx),
                'status' => 'supported',
                'updated_ymdhis' => $now,
                'review_reason' => null,
            ),
            'memory_node_id = :memory_node_id',
            array('memory_node_id' => (string) $canonical['memory_node_id'])
        );
    }

    /**
     * @param string $stagingId
     * @param string $canonicalId
     * @param int $reviewerActorId
     * @param string $now
     * @return void
     */
    private function insertPromotionEdge($stagingId, $canonicalId, $reviewerActorId, $now)
    {
        $this->requireCoreClass('IdGenerator', 'IdGenerator.php');
        $edgeId = (string) IdGenerator::generate();
        IdGenerator::validateTrustLadderPk($edgeId, 'memory_edges.memory_edge_id', true);

        $this->db->insert(
            $this->edgesTable(),
            array(
                'memory_edge_id' => $edgeId,
                'from_memory_node_id' => (string) $stagingId,
                'to_memory_node_id' => (string) $canonicalId,
                'edge_type' => self::EDGE_TYPE_PROMOTED_TO,
                'edge_context' => 'system_generated',
                'edge_status' => 'supported',
                'edge_direction' => 'unidirectional',
                'weight_hundredths' => 100,
                'provenance_actor_id' => (string) $reviewerActorId,
                'provenance_tool' => self::PROVENANCE_TOOL,
                'review_reason' => null,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_deleted' => 0,
                'deleted_ymdhis' => 0,
            )
        );
    }

    /**
     * @param string $stagingId
     * @param string $canonicalId
     * @return array|null
     */
    private function existingPromotionEdge($stagingId, $canonicalId)
    {
        return $this->db->fetchRow(
            'SELECT memory_edge_id FROM ' . $this->db->quoteIdentifier($this->edgesTable())
            . ' WHERE from_memory_node_id = :from_memory_node_id'
            . ' AND to_memory_node_id = :to_memory_node_id'
            . ' AND edge_type = :edge_type'
            . ' AND is_deleted = 0'
            . ' LIMIT 1',
            array(
                'from_memory_node_id' => $stagingId,
                'to_memory_node_id' => $canonicalId,
                'edge_type' => self::EDGE_TYPE_PROMOTED_TO,
            )
        );
    }

    /**
     * @param mixed $value
     * @return array
     */
    private function decodeJson($value)
    {
        if (is_array($value)) {
            return $value;
        }
        if ($value === null || $value === '') {
            return array();
        }
        $decoded = json_decode((string) $value, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        return array();
    }

    /**
     * @param string $memoryNodeId
     * @return bool
     */
    private function isStagingId($memoryNodeId)
    {
        $id = (string) $memoryNodeId;
        if (!preg_match('/^\d{18}$/', $id)) {
            return false;
        }
        $year = (int) substr($id, 0, 4);
        return $year >= 2000 && $year <= 2099;
    }

    /**
     * @return string
     */
    private function nodesTable()
    {
        return $this->tablePrefix . 'memory_nodes';
    }

    /**
     * @return string
     */
    private function edgesTable()
    {
        return $this->tablePrefix . 'memory_edges';
    }

    /**
     * Keep only last N promotion history entries.
     *
     * @param array $history
     * @return array
     */
    private function rotatePromotionHistory(array $history)
    {
        $count = count($history);
        if ($count <= self::PROMOTION_HISTORY_MAX) {
            return $history;
        }
        return array_slice($history, $count - self::PROMOTION_HISTORY_MAX);
    }

    /**
     * Resolve project root once and load a required core class file.
     *
     * @param string $className
     * @param string $fileName
     * @return void
     */
    private function requireCoreClass($className, $fileName)
    {
        if (class_exists($className, false)) {
            return;
        }
        $root = defined('LUPOPEDIA_PATH')
            ? LUPOPEDIA_PATH
            : dirname(__DIR__, 3);
        require_once $root . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $fileName;
    }
}
