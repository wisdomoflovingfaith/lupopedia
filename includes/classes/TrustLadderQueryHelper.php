<?php
/**
 * Trust ladder query helper — PK band + consolidation edge abstraction.
 *
 * Application code SHOULD use TrustLadder::getCanonical / validatePk / query
 * instead of ad hoc SQL on lupo_memory_* alone (PRD 51 §3.5, PRD 38 §8.1).
 *
 * @package Lupopedia
 */

require_once __DIR__ . '/DatabaseFactory.php';
require_once __DIR__ . '/IdGenerator.php';

trait TrustLadderQueryHelper
{
    /**
     * Resolve canonical memory_node_id by following consolidation edges (staging → canonical).
     *
     * @param int|string $id
     * @param string     $table Short logical name; only memory_nodes supported.
     *
     * @return string
     */
    public static function getCanonical($id, $table = 'memory_nodes')
    {
        $idStr = (string) $id;
        if ($table !== 'memory_nodes') {
            return $idStr;
        }
        $db     = DatabaseFactory::getConnection();
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $edges  = $prefix . 'memory_edges';

        $current = $idStr;
        $guard   = 0;
        while ($guard < 10) {
            $guard++;
            $sql = 'SELECT to_memory_node_id FROM ' . $db->quoteIdentifier($edges)
                . ' WHERE from_memory_node_id = :from_id'
                . " AND edge_type IN ('promoted_to','consolidated_into')"
                . ' AND is_deleted = 0 ORDER BY created_ymdhis DESC LIMIT 1';
            $row = $db->fetchRow($sql, array('from_id' => $current));
            if ($row === null || $row === false) {
                break;
            }
            if (!isset($row['to_memory_node_id']) || $row['to_memory_node_id'] === '' || $row['to_memory_node_id'] === null) {
                break;
            }
            $next = (string) $row['to_memory_node_id'];
            if ($next === $current) {
                break;
            }
            $current = $next;
        }

        return $current;
    }

    /**
     * Validate PK year segment for explicit trust tier (calendar-year check).
     *
     * @param int|string $pk
     * @param string     $trustTier canonical|staging|seed_only
     * @param int|null   $calendarYear Real UTC calendar year (e.g. 2026); null = gmdate('Y').
     *
     * @return bool
     */
    public static function validatePk($pk, $trustTier, $calendarYear = null)
    {
        if ($calendarYear === null) {
            $calendarYear = (int) gmdate('Y');
        }
        $pkStr     = (string) $pk;
        $trustTier = (string) $trustTier;
        if ($trustTier === 'seed_only') {
            return IdGenerator::validateTrustLadderPk($pkStr, 'TrustLadder::validatePk.seed', false);
        }
        $len = strlen($pkStr);
        if ($len !== 18 || !ctype_digit($pkStr)) {
            return false;
        }
        $pkYear = (int) substr($pkStr, 0, 4);
        if ($trustTier === 'canonical') {
            $expected = (int) $calendarYear - 1000;

            return $pkYear === $expected;
        }
        if ($trustTier === 'staging') {
            return $pkYear === (int) $calendarYear;
        }

        return false;
    }

    /**
     * Expand memory_node id list, optionally resolving each through getCanonical().
     *
     * @param object $db          PDO_DB instance
     * @param string $table       memory_nodes or lupo_memory_nodes
     * @param array  $conditions Must contain memory_node_ids => array of ids
     * @param bool   $resolveCanonical
     *
     * @return array Unique string ids suitable for IN (...) clauses
     */
    public static function query($db, $table, array $conditions, $resolveCanonical = true)
    {
        $short = $table;
        if (strpos($short, 'lupo_') === 0) {
            $short = substr($short, 5);
        }
        if ($short !== 'memory_nodes') {
            throw new InvalidArgumentException('TrustLadder::query supports memory_nodes only in 4.0.x');
        }
        if (!isset($conditions['memory_node_ids']) || !is_array($conditions['memory_node_ids'])) {
            throw new InvalidArgumentException('TrustLadder::query requires memory_node_ids array');
        }
        $uniq = array();
        foreach ($conditions['memory_node_ids'] as $mid) {
            $resolved = $resolveCanonical ? self::getCanonical($mid, 'memory_nodes') : (string) $mid;
            $uniq[$resolved] = true;
        }

        return array_keys($uniq);
    }
}

/**
 * Facade class for static calls (PRD 51 §3.5).
 */
final class TrustLadder
{
    use TrustLadderQueryHelper;
}
