<?php
/**
 * ResolutionEngine
 *
 * Context graph intelligence layer. Deterministic, read-only traversal and
 * resolution of edge relationships. No data mutation permitted.
 *
 * Uses EdgeService exclusively — no direct DB access.
 *
 * Edge type semantics:
 *   dependency  — directed (from → to). Execution of source requires target.
 *                 Cycles are forbidden and assumed clean post-validation.
 *   subtask     — directed (parent → child). Source owns or decomposes into target.
 *                 Cycles are forbidden and assumed clean post-validation.
 *   contradiction — undirected, stored canonically with lower source_id first.
 *                   Resolution requires both forward and reverse lookup.
 *   refinement  — additive, directed. Source is refined by target.
 *
 * Conflict handling:
 *   dependency conflicts → returned as-is for caller to treat as blocking
 *   contradictions       → reported, not auto-resolved
 *   refinements          → additive only, no conflict
 *
 * Deterministic order guarantee: created_ymdhis ASC, edge_id ASC everywhere.
 */

if (!class_exists('EdgeService')) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'EdgeService.php';
}

if (!class_exists('DatabaseFactory')) {
    require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes/DatabaseFactory.php';
}

class ResolutionEngine
{
    private $edgeService;

    public function __construct($edgeService = null)
    {
        if ($edgeService !== null) {
            $this->edgeService = $edgeService;
        } else {
            $db = DatabaseFactory::getConnection();
            $this->edgeService = new EdgeService($db);
        }
    }

    /**
     * Return all dependency edges originating from source, in deterministic order.
     *
     * Dependency conflicts (multiple dependencies on the same target) are returned
     * as-is. Callers MUST treat an unresolvable dependency as execution-blocking.
     *
     * @param string $sourceType
     * @param int    $sourceId
     * @return array  Rows from lupo_context_edges matching edge_type=dependency
     */
    public function resolveDependencies($sourceType, $sourceId)
    {
        $edges = $this->edgeService->getEdgesByType($sourceType, $sourceId, 'dependency');
        return $this->sortEdgesDeterministically($edges);
    }

    /**
     * Return all subtask edges where source is the parent, in deterministic order.
     *
     * Subtask relationships are additive decompositions. Cycles are assumed absent
     * (enforced by EdgeValidationService at write time — not re-checked here).
     *
     * @param string $sourceType
     * @param int    $sourceId
     * @return array
     */
    public function resolveSubtasks($sourceType, $sourceId)
    {
        $edges = $this->edgeService->getEdgesByType($sourceType, $sourceId, 'subtask');
        return $this->sortEdgesDeterministically($edges);
    }

    /**
     * Return all contradiction edges involving source, in deterministic order.
     *
     * Contradictions are canonically undirected: EdgeIdService stores them with
     * the lower source_id as the edge source. This means the node under inspection
     * may appear as either source or target. Both directions are queried and merged
     * so callers receive the complete contradiction set regardless of canonical order.
     *
     * Contradictions are REPORTED — not automatically resolved. The caller decides
     * what to do with them.
     *
     * @param string $sourceType
     * @param int    $sourceId
     * @return array
     */
    public function resolveContradictions($sourceType, $sourceId)
    {
        $asSource = $this->edgeService->getEdgesByType($sourceType, $sourceId, 'contradiction');
        $asTarget = $this->edgeService->getEdgesAsTargetByType($sourceType, $sourceId, 'contradiction');

        $merged = $this->mergeDeduplicatedEdges($asSource, $asTarget);
        return $this->sortEdgesDeterministically($merged);
    }

    /**
     * Return all refinement edges originating from source, in deterministic order.
     *
     * Refinements are additive only. No conflict resolution applies.
     *
     * @param string $sourceType
     * @param int    $sourceId
     * @return array
     */
    public function resolveRefinements($sourceType, $sourceId)
    {
        $edges = $this->edgeService->getEdgesByType($sourceType, $sourceId, 'refinement');
        return $this->sortEdgesDeterministically($edges);
    }

    /**
     * Return complete structured graph context for source node.
     *
     * Calls all four resolution methods and assembles them into a single envelope.
     * The result structure is stable: keys are always present, empty arrays when
     * no edges exist.
     *
     * @param string $sourceType
     * @param int    $sourceId
     * @return array
     *   array(
     *     'dependencies'   => array(...),
     *     'subtasks'       => array(...),
     *     'contradictions' => array(...),
     *     'refinements'    => array(...)
     *   )
     */
    public function resolveFullContext($sourceType, $sourceId)
    {
        return array(
            'dependencies'   => $this->resolveDependencies($sourceType, $sourceId),
            'subtasks'       => $this->resolveSubtasks($sourceType, $sourceId),
            'contradictions' => $this->resolveContradictions($sourceType, $sourceId),
            'refinements'    => $this->resolveRefinements($sourceType, $sourceId),
        );
    }

    // -------------------------------------------------------------------------
    // Helper methods
    // -------------------------------------------------------------------------

    /**
     * Filter an edge array to only those matching the given edge_type.
     *
     * @param array  $edges
     * @param string $edgeType  Already-normalized lowercase value.
     * @return array
     */
    public function filterByEdgeType($edges, $edgeType)
    {
        $normalized = strtolower(trim((string) $edgeType));
        $result = array();
        foreach ($edges as $edge) {
            if (isset($edge['edge_type']) && $edge['edge_type'] === $normalized) {
                $result[] = $edge;
            }
        }
        return $result;
    }

    /**
     * Sort edge rows deterministically: created_ymdhis ASC, then edge_id ASC.
     *
     * This mirrors the ORDER BY clause used in EdgeService queries, ensuring
     * a consistent result even when edges originate from merged sources.
     *
     * @param array $edges
     * @return array
     */
    public function sortEdgesDeterministically($edges)
    {
        usort($edges, array($this, 'compareEdgesCallback'));
        return $edges;
    }

    /**
     * Group edges by edge_type key.
     *
     * @param array $edges
     * @return array  Keys are edge_type strings; values are arrays of edge rows.
     */
    public function groupEdges($edges)
    {
        $grouped = array();
        foreach ($edges as $edge) {
            $type = isset($edge['edge_type']) ? (string) $edge['edge_type'] : '';
            if (!isset($grouped[$type])) {
                $grouped[$type] = array();
            }
            $grouped[$type][] = $edge;
        }
        return $grouped;
    }

    /**
     * Merge two edge arrays, deduplicating by edge_id.
     *
     * The first array's copy of a duplicated edge always wins (stable). This is
     * used for contradiction resolution where the same edge may appear in both
     * the forward and reverse query results.
     *
     * @param array $primary
     * @param array $secondary
     * @return array
     */
    private function mergeDeduplicatedEdges($primary, $secondary)
    {
        $seen = array();
        $result = array();

        foreach ($primary as $edge) {
            $id = isset($edge['edge_id']) ? (string) $edge['edge_id'] : '';
            if ($id !== '' && !isset($seen[$id])) {
                $seen[$id] = true;
                $result[] = $edge;
            }
        }

        foreach ($secondary as $edge) {
            $id = isset($edge['edge_id']) ? (string) $edge['edge_id'] : '';
            if ($id !== '' && !isset($seen[$id])) {
                $seen[$id] = true;
                $result[] = $edge;
            }
        }

        return $result;
    }

    /**
     * usort() comparator: created_ymdhis ASC, then edge_id ASC.
     *
     * @param array $a
     * @param array $b
     * @return int
     */
    private function compareEdgesCallback($a, $b)
    {
        $tsA = isset($a['created_ymdhis']) ? (string) $a['created_ymdhis'] : '';
        $tsB = isset($b['created_ymdhis']) ? (string) $b['created_ymdhis'] : '';

        if ($tsA !== $tsB) {
            return strcmp($tsA, $tsB);
        }

        $idA = isset($a['edge_id']) ? (string) $a['edge_id'] : '';
        $idB = isset($b['edge_id']) ? (string) $b['edge_id'] : '';
        return strcmp($idA, $idB);
    }
}
