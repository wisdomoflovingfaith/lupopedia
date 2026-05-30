<?php
/**
 * Staging GC Service — Chronological Trust Ladder
 *
 * Purges soft-deleted staging-tier rows (embedded year 2000–2099) from
 * lupo_memory_nodes and lupo_memory_edges per RETENTION_POLICY.md:
 *   - Minimum retention: 90 days after deleted_ymdhis before physical purge.
 *   - MUST NOT touch living canonical (year 1000–1999) or seed rows.
 *
 * Normative: CHRONOLOGICAL_TRUST_LADDER.md §9.3, RETENTION_POLICY.md.
 * PRD 19 (Garbage Collection System) authorizes this purge path.
 */

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(dirname(dirname(__DIR__))));
}

class StagingGcService
{
    /** @var object PDO_DB instance */
    private $db;

    /** @var string Table prefix (e.g. lupo_) */
    private $tablePrefix;

    /** @var int Actor running GC (for log attribution) */
    private $actorId;

    /** Lowest BIGINT value for 18-digit staging band (year 2000). */
    const STAGING_MIN_PK = '2000000000000000000';

    /** Upper bound for 18-digit staging band (year 2100, exclusive). */
    const STAGING_MAX_PK = '2100000000000000000';

    /**
     * Lineage edge types that must NEVER be physically deleted by GC.
     *
     * These edges encode provenance — the permanent record of how a canonical
     * row came to exist (promotion, consolidation, archival, revert). Deleting
     * them erases the audit trail even if the associated staging row has been
     * purged (per LEGACY_GAP_ANSWERS.md Q6 and RETENTION_POLICY.md).
     *
     * Applied as an exclusion filter on lupo_memory_edges (and lupo_edges when
     * that table is added to the GC path in a future sprint).
     *
     * TODO(LEGACY-GAP): Confirm this list is exhaustive against all edge types
     *   in use. Any lineage edge type missing here becomes GC-eligible by accident.
     */
    const LINEAGE_EDGE_TYPES = array(
        'canonical_instance_of',
        'consolidated_into',
        'merged_into',
        'promoted_to',
        'kairos_consolidates_from',
        'archived_to',
        'restored_from',
        'reverted_to',
    );

    /**
     * @param object|null $db          PDO_DB instance; null → DatabaseFactory::getConnection()
     * @param string|null $tablePrefix Table prefix; null → LUPO_TABLE_PREFIX constant or 'lupo_'
     * @param int         $actorId     Actor id for log attribution (default 0 = system)
     */
    public function __construct($db = null, $tablePrefix = null, $actorId = 0)
    {
        if (!class_exists('DatabaseFactory', false)) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DatabaseFactory.php';
        }
        if (!class_exists('IdGenerator', false)) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/IdGenerator.php';
        }
        if (!class_exists('timestamp_ymdhis', false)) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/TimestampYmdhis.php';
        }

        $this->db          = $db !== null ? $db : DatabaseFactory::getConnection();
        $this->tablePrefix = $tablePrefix !== null
            ? $tablePrefix
            : (defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_');
        $this->actorId     = (int) $actorId;
    }

    // -----------------------------------------------------------------------
    // Public API
    // -----------------------------------------------------------------------

    /**
     * Purge soft-deleted staging rows from lupo_memory_nodes and lupo_memory_edges
     * that have been soft-deleted for at least $retentionDays days.
     *
     * @param int $retentionDays Minimum days after deleted_ymdhis (default 90)
     * @param int $batchSize     Max rows per table per run (default 1000)
     * @return array {memory_nodes_purged: int, memory_edges_purged: int, errors: string[]}
     */
    public function purge($retentionDays = 90, $batchSize = 1000)
    {
        $retentionDays = max(1, (int) $retentionDays);
        $batchSize     = max(1, (int) $batchSize);
        $summary       = array(
            'memory_nodes_purged'  => 0,
            'memory_edges_purged'  => 0,
            'errors'               => array(),
        );

        $cutoffYmdhis = $this->cutoffYmdhis($retentionDays);

        $summary['memory_nodes_purged'] = $this->purgeTable(
            'memory_nodes',
            'memory_node_id',
            $cutoffYmdhis,
            $batchSize,
            $summary['errors']
        );

        // Lineage edge types are excluded from GC — they must survive indefinitely
        // as permanent provenance records even after their staging rows are purged.
        $summary['memory_edges_purged'] = $this->purgeTable(
            'memory_edges',
            'memory_edge_id',
            $cutoffYmdhis,
            $batchSize,
            $summary['errors'],
            self::LINEAGE_EDGE_TYPES,
            'edge_type'
        );

        $this->logGcRun($summary, $retentionDays, $cutoffYmdhis);

        return $summary;
    }

    /**
     * Dry-run: count rows eligible for purge without deleting them.
     *
     * @param int $retentionDays
     * @return array {memory_nodes_eligible: int, memory_edges_eligible: int}
     */
    public function dryRun($retentionDays = 90)
    {
        $retentionDays = max(1, (int) $retentionDays);
        $cutoffYmdhis  = $this->cutoffYmdhis($retentionDays);

        return array(
            'memory_nodes_eligible'        => $this->countEligible('memory_nodes', 'memory_node_id', $cutoffYmdhis),
            'memory_edges_eligible'        => $this->countEligible(
                'memory_edges',
                'memory_edge_id',
                $cutoffYmdhis,
                self::LINEAGE_EDGE_TYPES,
                'edge_type'
            ),
            'memory_edges_lineage_excluded' => $this->countEligible(
                'memory_edges',
                'memory_edge_id',
                $cutoffYmdhis
                // No exclusion — gives total including lineage, so caller can diff.
            ),
            'cutoff_ymdhis'                => $cutoffYmdhis,
            'retention_days'               => $retentionDays,
            'lineage_edge_types_excluded'  => self::LINEAGE_EDGE_TYPES,
        );
    }

    // -----------------------------------------------------------------------
    // Private helpers
    // -----------------------------------------------------------------------

    /**
     * Compute the packed UTC cutoff (deleted_ymdhis must be <= this value for purge eligibility).
     * Rows deleted BEFORE this point have been soft-deleted for >= $retentionDays.
     *
     * @param int $retentionDays
     * @return string 14-digit packed UTC string (YYYYMMDDHHIISS)
     */
    private function cutoffYmdhis($retentionDays)
    {
        // timestamp_ymdhis::now() returns a packed UTC integer; subtract seconds for cutoff.
        $nowPacked    = (string) timestamp_ymdhis::now();
        $cutoffPacked = (string) timestamp_ymdhis::subtractSeconds((int) $nowPacked, $retentionDays * 86400);
        return $cutoffPacked;
    }

    /**
     * Purge staging-tier soft-deleted rows from one table.
     * Returns the number of rows physically deleted.
     *
     * @param string   $shortTable       e.g. 'memory_nodes'
     * @param string   $pkColumn         e.g. 'memory_node_id'
     * @param string   $cutoffYmdhis
     * @param int      $batchSize
     * @param array    &$errors
     * @param string[] $excludeEdgeTypes Edge type values to exclude (e.g. LINEAGE_EDGE_TYPES).
     *                                   When non-empty, rows with edge_type IN this list are skipped.
     *                                   Only meaningful when the table has an edge_type column.
     * @param string   $edgeTypeColumn   Column name to filter on (default 'edge_type')
     * @return int
     */
    private function purgeTable(
        $shortTable,
        $pkColumn,
        $cutoffYmdhis,
        $batchSize,
        &$errors,
        array $excludeEdgeTypes = array(),
        $edgeTypeColumn = 'edge_type'
    ) {
        $fullTable = $this->tablePrefix . $shortTable;
        $qt        = $this->db->quoteIdentifier($fullTable);
        $qpk       = $this->db->quoteIdentifier($pkColumn);

        // Base params for the SELECT.
        $selectParams = array(
            ':cutoff'     => $cutoffYmdhis,
            ':batch_size' => $batchSize,
        );

        // Build optional lineage-edge exclusion clause.
        // Rows whose edge_type is in LINEAGE_EDGE_TYPES must never be physically deleted —
        // they are permanent provenance records (LEGACY_GAP_ANSWERS.md Q6).
        $exclusionClause = '';
        if (!empty($excludeEdgeTypes)) {
            $qcol        = $this->db->quoteIdentifier($edgeTypeColumn);
            $placeholders = array();
            foreach ($excludeEdgeTypes as $i => $type) {
                $key                  = ':excl_et_' . $i;
                $placeholders[]       = $key;
                $selectParams[$key]   = $type;
            }
            $exclusionClause = " AND ($qcol IS NULL OR $qcol NOT IN ("
                . implode(', ', $placeholders) . '))';
        }

        // Select staging-tier soft-deleted rows past the retention window.
        // The PHP layer re-validates each PK as staging-band before deletion.
        $selectSql = "SELECT $qpk AS pk FROM $qt
                      WHERE is_deleted = 1
                        AND deleted_ymdhis > 0
                        AND deleted_ymdhis <= :cutoff"
                   . $exclusionClause
                   . " LIMIT :batch_size";

        try {
            $rows = $this->db->fetchAll($selectSql, array(
                ':cutoff'     => $cutoffYmdhis,
                ':batch_size' => $batchSize,
            ));
        } catch (Exception $e) {
            $errors[] = "SELECT from $fullTable failed: " . $e->getMessage();
            return 0;
        }

        $purged = 0;
        foreach ($rows as $row) {
            $pkVal = isset($row['pk']) ? (string) $row['pk'] : '';
            if ($pkVal === '') {
                continue;
            }

            // PHP-layer tier validation — MUST be staging band before physical delete.
            if (!$this->isStagingBand($pkVal)) {
                // Not staging — skip silently (canonical or seed rows are never purged here).
                continue;
            }

            // Physical DELETE — only soft-deleted staging rows.
            $deleteSql = "DELETE FROM $qt WHERE $qpk = :pk AND is_deleted = 1";
            try {
                $this->db->query($deleteSql, array(':pk' => $pkVal));
                $purged++;
            } catch (Exception $e) {
                $errors[] = "DELETE $fullTable pk=$pkVal failed: " . $e->getMessage();
            }
        }

        return $purged;
    }

    /**
     * Count rows eligible for purge (dry-run helper).
     *
     * @param string   $shortTable
     * @param string   $pkColumn
     * @param string   $cutoffYmdhis
     * @param string[] $excludeEdgeTypes Edge type values to exclude (same semantics as purgeTable)
     * @param string   $edgeTypeColumn
     * @return int
     */
    private function countEligible(
        $shortTable,
        $pkColumn,
        $cutoffYmdhis,
        array $excludeEdgeTypes = array(),
        $edgeTypeColumn = 'edge_type'
    ) {
        $fullTable = $this->tablePrefix . $shortTable;
        $qt        = $this->db->quoteIdentifier($fullTable);

        $params          = array(':cutoff' => $cutoffYmdhis);
        $exclusionClause = '';
        if (!empty($excludeEdgeTypes)) {
            $qcol         = $this->db->quoteIdentifier($edgeTypeColumn);
            $placeholders = array();
            foreach ($excludeEdgeTypes as $i => $type) {
                $key               = ':excl_et_' . $i;
                $placeholders[]    = $key;
                $params[$key]      = $type;
            }
            $exclusionClause = " AND ($qcol IS NULL OR $qcol NOT IN ("
                . implode(', ', $placeholders) . '))';
        }

        try {
            $row = $this->db->fetchRow(
                "SELECT COUNT(*) AS cnt FROM $qt
                 WHERE is_deleted = 1
                   AND deleted_ymdhis > 0
                   AND deleted_ymdhis <= :cutoff"
                . $exclusionClause,
                $params
            );
            return ($row && isset($row['cnt'])) ? (int) $row['cnt'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Check whether a PK string is in the staging band (year 2000–2099).
     * Mirrors PHP §2.2.1 year extraction — string only, no int cast of full id.
     *
     * @param string $pkStr
     * @return bool
     */
    private function isStagingBand($pkStr)
    {
        $pkStr = (string) $pkStr;
        if (strlen($pkStr) !== 18 || !ctype_digit($pkStr)) {
            return false;
        }
        $year = (int) substr($pkStr, 0, 4);
        return $year >= 2000 && $year <= 2099;
    }

    /**
     * Log the GC run result to lupo_unified_log (best-effort; silently skip if table absent).
     *
     * @param array  $summary
     * @param int    $retentionDays
     * @param string $cutoffYmdhis
     */
    private function logGcRun(array $summary, $retentionDays, $cutoffYmdhis)
    {
        $logTable = $this->tablePrefix . 'unified_log';
        $qt       = $this->db->quoteIdentifier($logTable);

        if (!class_exists('IdGenerator', false)) {
            return;
        }

        $logId      = IdGenerator::generate();
        $nowYmdhis  = (string) timestamp_ymdhis::now();
        $context    = json_encode(array(
            'action'               => 'staging_gc',
            'retention_days'       => $retentionDays,
            'cutoff_ymdhis'        => $cutoffYmdhis,
            'memory_nodes_purged'  => $summary['memory_nodes_purged'],
            'memory_edges_purged'  => $summary['memory_edges_purged'],
            'errors'               => $summary['errors'],
            'actor_id'             => $this->actorId,
        ));

        $total = $summary['memory_nodes_purged'] + $summary['memory_edges_purged'];
        $msg   = sprintf(
            'Staging GC: purged %d memory_nodes, %d memory_edges (>%d days)',
            $summary['memory_nodes_purged'],
            $summary['memory_edges_purged'],
            $retentionDays
        );

        $sql = "INSERT INTO $qt
                    (log_id, log_type, log_level, log_message, log_context,
                     actor_id, created_ymdhis)
                VALUES
                    (:log_id, 'gc', 'info', :msg, :ctx, :actor, :now)";

        try {
            $this->db->query($sql, array(
                ':log_id' => $logId,
                ':msg'    => $msg,
                ':ctx'    => $context,
                ':actor'  => $this->actorId,
                ':now'    => $nowYmdhis,
            ));
        } catch (Exception $e) {
            // Unified log may not exist in all installs; silently skip.
        }
    }
}
