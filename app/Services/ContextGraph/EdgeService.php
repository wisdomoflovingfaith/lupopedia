<?php
/**
 * Core context graph edge service.
 *
 * All edge writes and reads go through this service.
 * Deterministic, idempotent, soft-delete only.
 */

if (!class_exists('EdgeIdService')) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'EdgeIdService.php';
}

if (!class_exists('EdgeValidationService')) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'EdgeValidationService.php';
}

if (!class_exists('DatabaseFactory')) {
    require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes/DatabaseFactory.php';
}

class EdgeService
{
    private $db;
    private $tablePrefix;
    private $tableName;
    private $edgeIdService;
    private $edgeValidationService;

    public function __construct($db = null, $tablePrefix = null, $edgeIdService = null, $edgeValidationService = null)
    {
        $this->db = $db ? $db : DatabaseFactory::getConnection();
        $this->tablePrefix = $tablePrefix !== null ? $tablePrefix : (defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_');
        $this->tableName = $this->tablePrefix . 'context_edges';
        $this->edgeIdService = $edgeIdService ? $edgeIdService : new EdgeIdService();
        $this->edgeValidationService = $edgeValidationService ? $edgeValidationService : new EdgeValidationService($this->db, $this->tablePrefix, $this->edgeIdService);
    }

    public function createEdge($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction, $metadataJson)
    {
        $normalizedSourceType = $this->normalizeType($sourceType);
        $normalizedTargetType = $this->normalizeType($targetType);
        $normalizedEdgeType = $this->normalizeType($edgeType);
        $normalizedDirection = $this->normalizeDirection($direction);
        $normalizedSourceId = (int) $sourceId;
        $normalizedTargetId = (int) $targetId;

        // Mandatory mutation gate: reject invalid input before any DB mutation path.
        $validation = $this->edgeValidationService->validateCreate(
            $normalizedSourceType,
            $normalizedSourceId,
            $normalizedTargetType,
            $normalizedTargetId,
            $normalizedEdgeType,
            $normalizedDirection,
            $metadataJson
        );
        if (!isset($validation['valid']) || !$validation['valid']) {
            throw new Exception('Edge create validation failed: ' . json_encode($validation));
        }

        $edgeId = $this->edgeIdService->generateId(
            $normalizedSourceType,
            $normalizedSourceId,
            $normalizedTargetType,
            $normalizedTargetId,
            $normalizedEdgeType,
            $normalizedDirection
        );

        $existing = $this->fetchEdgeById($edgeId);
        if ($existing && (int) $existing['is_deleted'] === 0) {
            return $existing;
        }

        $now = gmdate('YmdHis');
        $storedMetadataJson = $this->buildStoredMetadataJson($normalizedDirection, $metadataJson);

        $this->db->beginTransaction();

        try {
            if ($existing && (int) $existing['is_deleted'] === 1) {
                $this->db->update(
                    $this->tableName,
                    array(
                        'source_type' => $normalizedSourceType,
                        'source_id' => $normalizedSourceId,
                        'target_type' => $normalizedTargetType,
                        'target_id' => $normalizedTargetId,
                        'edge_type' => $normalizedEdgeType,
                        'metadata_json' => $storedMetadataJson,
                        'updated_ymdhis' => $now,
                        'is_deleted' => 0,
                        'deleted_ymdhis' => 0
                    ),
                    'edge_id = :where_edge_id',
                    array('where_edge_id' => $edgeId)
                );
            } else {
                $this->db->insert(
                    $this->tableName,
                    array(
                        'edge_id' => $edgeId,
                        'source_type' => $normalizedSourceType,
                        'source_id' => $normalizedSourceId,
                        'target_type' => $normalizedTargetType,
                        'target_id' => $normalizedTargetId,
                        'edge_type' => $normalizedEdgeType,
                        'metadata_json' => $storedMetadataJson,
                        'created_ymdhis' => $now,
                        'updated_ymdhis' => $now,
                        'is_deleted' => 0,
                        'deleted_ymdhis' => 0
                    )
                );
            }

            $this->db->commit();
        } catch (Exception $exception) {
            $this->safeRollback();
            throw $exception;
        }

        return $this->fetchEdgeById($edgeId);
    }

    public function getEdges($sourceType, $sourceId)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE source_type = :source_type AND source_id = :source_id AND is_deleted = :is_deleted ORDER BY created_ymdhis ASC, edge_id ASC";

        return $this->db->fetchAll(
            $sql,
            array(
                'source_type' => $this->normalizeType($sourceType),
                'source_id' => (int) $sourceId,
                'is_deleted' => 0
            )
        );
    }

    /**
     * Return all active edges where this node is the source, filtered by edge_type.
     * Deterministic order: created_ymdhis ASC, edge_id ASC.
     */
    public function getEdgesByType($sourceType, $sourceId, $edgeType)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE source_type = :source_type AND source_id = :source_id AND edge_type = :edge_type AND is_deleted = :is_deleted ORDER BY created_ymdhis ASC, edge_id ASC";

        return $this->db->fetchAll(
            $sql,
            array(
                'source_type' => $this->normalizeType($sourceType),
                'source_id' => (int) $sourceId,
                'edge_type' => $this->normalizeType($edgeType),
                'is_deleted' => 0
            )
        );
    }

    /**
     * Return all active edges where this node is the TARGET, filtered by edge_type.
     * Required for undirected/order-independent edges (e.g. contradiction) where the
     * canonical storage puts lower source_id as the edge source regardless of direction.
     * Deterministic order: created_ymdhis ASC, edge_id ASC.
     */
    public function getEdgesAsTargetByType($targetType, $targetId, $edgeType)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE target_type = :target_type AND target_id = :target_id AND edge_type = :edge_type AND is_deleted = :is_deleted ORDER BY created_ymdhis ASC, edge_id ASC";

        return $this->db->fetchAll(
            $sql,
            array(
                'target_type' => $this->normalizeType($targetType),
                'target_id' => (int) $targetId,
                'edge_type' => $this->normalizeType($edgeType),
                'is_deleted' => 0
            )
        );
    }

    public function deleteEdge($edgeId)
    {
        // Mandatory mutation gate: reject invalid delete before soft-delete update.
        $validation = $this->edgeValidationService->validateDelete($edgeId);
        if (!isset($validation['valid']) || !$validation['valid']) {
            throw new Exception('Edge delete validation failed: ' . json_encode($validation));
        }

        $existing = $this->fetchEdgeById($edgeId);
        if (!$existing || (int) $existing['is_deleted'] === 1) {
            return false;
        }

        $now = gmdate('YmdHis');

        $affected = $this->db->update(
            $this->tableName,
            array(
                'is_deleted' => 1,
                'deleted_ymdhis' => $now,
                'updated_ymdhis' => $now
            ),
            'edge_id = :where_edge_id AND is_deleted = :where_is_deleted',
            array(
                'where_edge_id' => (string) $edgeId,
                'where_is_deleted' => 0
            )
        );

        return $affected > 0;
    }

    private function fetchEdgeById($edgeId)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE edge_id = :edge_id LIMIT 1";
        return $this->db->fetchRow($sql, array('edge_id' => (string) $edgeId));
    }

    private function normalizeType($value)
    {
        return strtolower(trim((string) $value));
    }

    private function normalizeDirection($direction)
    {
        return strtolower(trim((string) $direction));
    }

    private function buildStoredMetadataJson($direction, $metadataJson)
    {
        $envelope = array('direction' => $direction);

        if ($metadataJson === null || $metadataJson === '') {
            return json_encode($envelope);
        }

        $decoded = json_decode($metadataJson, true);
        if (is_array($decoded)) {
            foreach ($decoded as $key => $value) {
                $envelope[$key] = $value;
            }
        } else {
            $envelope['raw_metadata'] = (string) $metadataJson;
        }

        ksort($envelope);
        return json_encode($envelope);
    }

    private function safeRollback()
    {
        try {
            $this->db->rollBack();
        } catch (Exception $rollbackException) {
        }
    }
}