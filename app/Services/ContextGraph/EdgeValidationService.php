<?php
/**
 * EdgeValidationService
 *
 * Mandatory validation gate for context graph edge writes.
 * This service is read-only against DB state (no inserts/updates/deletes).
 *
 * Enforces:
 * - disjoint edge types
 * - direction semantics by edge type
 * - explicit scope matrix
 * - duplicate prevention
 * - cycle policy
 * - deterministic metadata validation
 */

if (!class_exists('DatabaseFactory')) {
    require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'class-DatabaseFactory.php';
}

if (!class_exists('EdgeIdService')) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'EdgeIdService.php';
}

class EdgeValidationService
{
    private $db;
    private $tablePrefix;
    private $tableName;
    private $edgeIdService;

    public function __construct($db = null, $tablePrefix = null, $edgeIdService = null)
    {
        $this->db = $db ? $db : DatabaseFactory::getConnection();
        $this->tablePrefix = $tablePrefix !== null ? $tablePrefix : (defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_');
        $this->tableName = $this->tablePrefix . 'context_edges';
        $this->edgeIdService = $edgeIdService ? $edgeIdService : new EdgeIdService();
    }

    /**
     * Validate edge creation request.
     */
    public function validateCreate($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction, $metadataJson)
    {
        $errors = array();

        $normalizedSourceType = $this->normalizeType($sourceType);
        $normalizedTargetType = $this->normalizeType($targetType);
        $normalizedEdgeType = $this->canonicalizeEdgeType($this->normalizeType($edgeType));
        $normalizedDirection = $this->normalizeDirection($direction);
        $normalizedSourceId = $this->normalizeId($sourceId);
        $normalizedTargetId = $this->normalizeId($targetId);

        if ($normalizedSourceType === '' || $normalizedTargetType === '') {
            $errors[] = 'source_type and target_type must be non-empty strings.';
        }
        if ($normalizedSourceId <= 0 || $normalizedTargetId <= 0) {
            $errors[] = 'source_id and target_id must be numeric values greater than zero.';
        }
        if ($normalizedEdgeType === '') {
            $errors[] = 'edge_type must be a non-empty string.';
        }
        if ($normalizedDirection === '') {
            $errors[] = 'direction must be a non-empty string.';
        }

        $errors = array_merge($errors, $this->validateEdgeType($normalizedEdgeType));
        $errors = array_merge($errors, $this->validateDirection(
            $normalizedSourceType,
            $normalizedSourceId,
            $normalizedTargetType,
            $normalizedTargetId,
            $normalizedEdgeType,
            $normalizedDirection
        ));
        $errors = array_merge($errors, $this->validateScope(
            $normalizedSourceType,
            $normalizedTargetType,
            $normalizedEdgeType
        ));
        $errors = array_merge($errors, $this->validateMetadataJson($metadataJson));

        /**
         * PHASE 3 ENFORCEMENT PREPARATION (NOT ACTIVE YET)
         * 
         * Actor vs Faucet enforcement will be activated in Phase 3.
         * Current implementation is deterministic and read-only (no behavior change).
         * Uncomment activation flag in Phase 3 gate.
         */
        $PHASE_3_ACTOR_FAUCET_ENFORCEMENT_ACTIVE = false;
        if ($PHASE_3_ACTOR_FAUCET_ENFORCEMENT_ACTIVE) {
            if ($normalizedTargetType === 'actor') {
                $errors = array_merge($errors, $this->validateActorType($normalizedTargetId));
            }
            if ($normalizedSourceType === 'actor') {
                $errors = array_merge($errors, $this->validateActorType($normalizedSourceId));
            }
        }

        if (!empty($errors)) {
            return array('valid' => false, 'errors' => array_values($errors));
        }

        if ($this->edgeExists(
            $normalizedSourceType,
            $normalizedSourceId,
            $normalizedTargetType,
            $normalizedTargetId,
            $normalizedEdgeType,
            $normalizedDirection
        )) {
            return array(
                'valid' => false,
                'errors' => array('Duplicate active edge exists for the same canonical identity.')
            );
        }

        if ($this->wouldCreateForbiddenCycle(
            $normalizedSourceType,
            $normalizedSourceId,
            $normalizedTargetType,
            $normalizedTargetId,
            $normalizedEdgeType,
            $normalizedDirection
        )) {
            return array(
                'valid' => false,
                'errors' => array('Forbidden cycle detected for edge type: ' . $normalizedEdgeType)
            );
        }

        return array('valid' => true, 'errors' => array());
    }

    /**
     * Validate edge delete request.
     */
    public function validateDelete($edgeId)
    {
        $normalizedEdgeId = trim((string) $edgeId);
        if ($normalizedEdgeId === '' || !ctype_digit($normalizedEdgeId)) {
            return array('valid' => false, 'errors' => array('edge_id must be numeric.'));
        }

        $sql = "SELECT edge_id, is_deleted FROM {$this->tableName} WHERE edge_id = :edge_id LIMIT 1";
        $row = $this->db->fetchRow($sql, array('edge_id' => $normalizedEdgeId));

        if (!$row) {
            return array('valid' => false, 'errors' => array('Edge does not exist.'));
        }
        if ((int) $row['is_deleted'] === 1) {
            return array('valid' => false, 'errors' => array('Edge is already soft-deleted.'));
        }

        /**
         * PHASE 3 ENFORCEMENT PREPARATION (NOT ACTIVE YET)
         * 
         * Edge actor validation on delete will be enforced in Phase 3.
         * Current implementation is deterministic and read-only (no behavior change).
         */
        $PHASE_3_ACTOR_FAUCET_ENFORCEMENT_ACTIVE = false;
        if ($PHASE_3_ACTOR_FAUCET_ENFORCEMENT_ACTIVE) {
            // Future: validate source_id and target_id actor type constraints via edge metadata
        }

        return array('valid' => true, 'errors' => array());
    }

    /**
     * Allowed edge types are locked by Channel 61 final model.
     */
    private function validateEdgeType($edgeType)
    {
        $allowed = array(
            'dependency', 'subtask', 'contradiction', 'refinement',
            'reference', 'example', 'question', 'answer',
            'implements', 'validates', 'contains', 'extends'
        );
        if (!in_array($edgeType, $allowed, true)) {
            return array('Invalid edge_type for locked taxonomy.');
        }
        return array();
    }

    /**
     * Direction semantics by edge type:
     * - dependency/subtask/refinement -> directed only (fwd)
     * - contradiction -> undirected canonicalized only (both)
     */
    private function validateDirection($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction)
    {
        $errors = array();

        if (in_array($edgeType, array(
            'dependency', 'subtask', 'refinement',
            'example', 'question', 'answer',
            'implements', 'validates', 'contains', 'extends'
        ), true)) {
            if ($direction !== 'fwd') {
                $errors[] = 'Directed edge types require direction=fwd.';
            }
        }

        if (in_array($edgeType, array('contradiction', 'reference'), true)) {
            if (!in_array($direction, array('both', 'fwd'), true)) {
                $errors[] = 'Bidirectional edge types require direction=both (legacy fwd accepted).';
            }
        }

        if ($edgeType === 'contradiction') {
            if ($sourceType === $targetType && $sourceId === $targetId) {
                $errors[] = 'Self-contradiction edge is not allowed.';
            }
            if ($sourceType === $targetType && !$this->isCanonicalContradictionOrder($sourceType, $sourceId, $targetType, $targetId)) {
                $errors[] = 'contradiction edges must use canonical source/target ordering.';
            }
        }

        return $errors;
    }

    /**
     * Explicit scope matrix from finalized model.
     */
    private function validateScope($sourceType, $targetType, $edgeType)
    {
        $matrix = array(
            'thread:thread' => array('dependency', 'subtask', 'contradiction', 'refinement', 'reference', 'example', 'question', 'answer', 'implements', 'validates', 'contains', 'extends'),
            'channel:thread' => array('dependency', 'subtask', 'refinement', 'reference', 'contains'),
            'channel:channel' => array('dependency', 'contradiction', 'reference', 'extends'),
            'message:thread' => array('reference', 'dependency', 'question', 'answer', 'contains'),
            'message:actor' => array('reference', 'implements', 'validates', 'contradiction'),
            'message:artifact' => array('reference', 'contains', 'implements', 'validates', 'example'),
            'message:task' => array('reference', 'dependency', 'subtask', 'question', 'answer'),
            'task:task' => array('dependency', 'subtask', 'contradiction', 'refinement', 'reference', 'example', 'question', 'answer', 'implements', 'validates', 'contains', 'extends'),
            'task:artifact' => array('reference', 'contains', 'implements', 'validates', 'example', 'extends'),
            'artifact:artifact' => array('reference', 'contradiction', 'refinement', 'example', 'extends', 'contains', 'validates'),
            'actor:task' => array('implements', 'validates', 'reference', 'contains'),
            'actor:artifact' => array('implements', 'validates', 'reference', 'contains', 'extends'),
            'actor:actor' => array('reference', 'contradiction', 'extends', 'contains')
        );

        $key = $sourceType . ':' . $targetType;
        if (!isset($matrix[$key])) {
            return array('Scope relationship is not allowed for source/target types.');
        }
        if (!in_array($edgeType, $matrix[$key], true)) {
            return array('edge_type is not allowed for this source/target scope pair.');
        }
        return array();
    }

    /**
     * Duplicate prevention using canonical edge identity + active state.
     */
    private function edgeExists($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction)
    {
        $edgeId = $this->edgeIdService->generateId(
            $sourceType,
            $sourceId,
            $targetType,
            $targetId,
            $edgeType,
            $direction
        );

        $sql = "SELECT edge_id FROM {$this->tableName} WHERE edge_id = :edge_id AND is_deleted = :is_deleted LIMIT 1";
        $row = $this->db->fetchRow($sql, array('edge_id' => (string) $edgeId, 'is_deleted' => 0));
        return (bool) $row;
    }

    /**
     * Cycle policy:
     * - dependency: cycles forbidden
     * - subtask: cycles forbidden
     * - contradiction: cycles forbidden (treat as undirected graph)
     * - refinement: cycles allowed
     *
     * Traversal limit is fixed to 5 hops for deterministic bounded validation.
     */
    private function wouldCreateForbiddenCycle($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction)
    {
        if ($edgeType === 'refinement') {
            return false;
        }

        if ($edgeType === 'contradiction') {
            return $this->pathExists($sourceType, $sourceId, $targetType, $targetId, 'contradiction', true, 5);
        }

        return $this->pathExists($targetType, $targetId, $sourceType, $sourceId, $edgeType, false, 5);
    }

    /**
     * Metadata JSON must be valid JSON and bounded.
     */
    private function validateMetadataJson($metadataJson)
    {
        if ($metadataJson === null || $metadataJson === '') {
            return array();
        }

        $raw = (string) $metadataJson;
        if (strlen($raw) > 4096) {
            return array('metadata_json exceeds deterministic 4KB limit.');
        }

        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return array('metadata_json must be valid JSON.');
        }
        if (!is_array($decoded) && !is_object($decoded)) {
            return array('metadata_json must decode to object/array.');
        }

        return array();
    }

    private function pathExists($startType, $startId, $goalType, $goalId, $edgeType, $undirected, $maxDepth)
    {
        $queue = array(
            array(
                'type' => $startType,
                'id' => (int) $startId,
                'depth' => 0
            )
        );
        $visited = array();

        while (!empty($queue)) {
            $node = array_shift($queue);
            $nodeKey = $node['type'] . ':' . $node['id'];
            if (isset($visited[$nodeKey])) {
                continue;
            }
            $visited[$nodeKey] = true;

            if ($node['type'] === $goalType && (int) $node['id'] === (int) $goalId) {
                return true;
            }
            if ((int) $node['depth'] >= (int) $maxDepth) {
                continue;
            }

            $neighbors = $this->fetchNeighbors($node['type'], (int) $node['id'], $edgeType, $undirected);
            $i = 0;
            $count = count($neighbors);
            while ($i < $count) {
                $next = $neighbors[$i];
                $nextKey = $next['type'] . ':' . $next['id'];
                if (!isset($visited[$nextKey])) {
                    $queue[] = array(
                        'type' => $next['type'],
                        'id' => (int) $next['id'],
                        'depth' => (int) $node['depth'] + 1
                    );
                }
                $i++;
            }
        }

        return false;
    }

    private function fetchNeighbors($type, $id, $edgeType, $undirected)
    {
        $neighbors = array();

        $outSql = "SELECT target_type, target_id FROM {$this->tableName}
            WHERE source_type = :source_type
              AND source_id = :source_id
              AND edge_type = :edge_type
              AND is_deleted = :is_deleted
            ORDER BY target_type ASC, target_id ASC";
        $outRows = $this->db->fetchAll($outSql, array(
            'source_type' => $type,
            'source_id' => (int) $id,
            'edge_type' => $edgeType,
            'is_deleted' => 0
        ));

        foreach ($outRows as $row) {
            $neighbors[] = array(
                'type' => $this->normalizeType($row['target_type']),
                'id' => (int) $row['target_id']
            );
        }

        if ($undirected) {
            $inSql = "SELECT source_type, source_id FROM {$this->tableName}
                WHERE target_type = :target_type
                  AND target_id = :target_id
                  AND edge_type = :edge_type
                  AND is_deleted = :is_deleted
                ORDER BY source_type ASC, source_id ASC";
            $inRows = $this->db->fetchAll($inSql, array(
                'target_type' => $type,
                'target_id' => (int) $id,
                'edge_type' => $edgeType,
                'is_deleted' => 0
            ));
            foreach ($inRows as $row) {
                $neighbors[] = array(
                    'type' => $this->normalizeType($row['source_type']),
                    'id' => (int) $row['source_id']
                );
            }
        }

        return $neighbors;
    }

    private function isCanonicalContradictionOrder($sourceType, $sourceId, $targetType, $targetId)
    {
        $left = $sourceType . ':' . (int) $sourceId;
        $right = $targetType . ':' . (int) $targetId;
        return strcmp($left, $right) <= 0;
    }

    /**
     * PHASE 3 PREPARATION: Actor vs Faucet Enforcement
     *
     * Deterministic validation to ensure actor_id references are canonical role actors,
     * not IDE faucets.
     *
     * Faucets (ide_faucet type in lupo_actors):
     * - IDs 100-106 (Kiro, Windsurf, Cursor, Antigravity, Warp, Cascade, Zencoder)
     * - These are execution surfaces, not coordination actors
     * - Cannot be used as graph edge targets in actor type edges
     *
     * Canonical role actors:
     * - Layer 1: HEPHAESTUS (14), ATHENA (12), HERMES (15), LILITH (2), ROSE (3)
     * - All agent type actors with canonical identity
     *
     * ENFORCEMENT INACTIVE: This method is ready but not called until Phase 3 gate.
     * See $PHASE_3_ACTOR_FAUCET_ENFORCEMENT_ACTIVE flag in validateCreate()/validateDelete().
     */
    private function validateActorType($actorId)
    {
        $errors = array();
        $normalizedId = $this->normalizeId($actorId);

        if ($normalizedId <= 0) {
            return array('Actor ID must be a positive integer.');
        }

        // Query lupo_actors table to determine actor type
        $actorTableName = $this->tablePrefix . 'actors';
        $sql = "SELECT actor_id, actor_type FROM {$actorTableName} WHERE actor_id = :actor_id LIMIT 1";
        $row = $this->db->fetchRow($sql, array('actor_id' => $normalizedId));

        if (!$row) {
            $errors[] = 'Actor ID ' . $normalizedId . ' does not exist in registry.';
            return $errors;
        }

        $actorType = isset($row['actor_type']) ? strtolower(trim((string) $row['actor_type'])) : 'unknown';

        // Reject IDE faucets
        if ($actorType === 'ide_faucet') {
            $errors[] = 'Actor ID ' . $normalizedId . ' is an IDE faucet and cannot be used as a graph edge target. '
                      . 'Use canonical role actors (type=agent) instead.';
        }

        // Reject non-agent types (except faucet rejection above)
        if ($actorType !== 'agent' && $actorType !== 'ide_faucet') {
            $errors[] = 'Actor ID ' . $normalizedId . ' has type "' . $actorType 
                      . '" which is not a canonical actor role. Use type=agent canonical actors.';
        }

        return $errors;
    }

    private function normalizeType($value)
    {
        return strtolower(trim((string) $value));
    }

    private function canonicalizeEdgeType($edgeType)
    {
        $aliases = array(
            'references' => 'reference',
            'routes_to' => 'reference',
            'depends_on' => 'dependency',
            'assigns' => 'implements',
            'produces' => 'contains',
            'blocks' => 'contradiction'
        );

        if (isset($aliases[$edgeType])) {
            return $aliases[$edgeType];
        }

        return $edgeType;
    }

    private function normalizeDirection($value)
    {
        return strtolower(trim((string) $value));
    }

    private function normalizeId($value)
    {
        if (is_string($value)) {
            $value = trim($value);
            if ($value === '' || !ctype_digit($value)) {
                return 0;
            }
        }
        return (int) $value;
    }
}
