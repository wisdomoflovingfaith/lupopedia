<?php
/**
 * EdgeQueryService
 *
 * LUPOPEDIA HEADERS:
 * file_path_from_root: app/Services/EdgeQueryService.php
 * when_updated: 20260324194000
 * last_modified_utc: 20260324194000
 * artifact_type: service_class
 * artifact_kind: query_service
 * purpose: Encapsulates graph traversal queries for channel/thread relationships
 * actor_id: 12 (ATHENA)
 * version: 4.0.87
 *
 * LUPOPEDIA FOOTER:
 * last_verified: 20260324194000
 * last_verified_by_actor_id: 102
 * orchestrator: cursor:root
 */

namespace App\Services;

use App\Classes\PDO_DB;

/**
 * EdgeQueryService
 *
 * Provides common queries for graph traversal without requiring callers
 * to know which table or column is authoritative for relationships.
 *
 * Track 4: Build EdgeQueryService PHP class
 *
 * Usage:
 *   $related = EdgeQueryService::getRelatedChannels(42);
 *   $threads = EdgeQueryService::getThreadsForChannel(42);
 *   $lineage = EdgeQueryService::getThreadLineage(1001);
 */
class EdgeQueryService
{
    private static ?PDO_DB $db = null;
    private static string $prefix = 'lupo_';

    /**
     * Initialize database connection
     */
    private static function ensureDb(): PDO_DB
    {
        if (self::$db === null) {
            self::$db = DatabaseFactory::getConnection();
            self::$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        }
        return self::$db;
    }

    /**
     * Get all channels related to a given channel_id
     *
     * Returns channels connected via 'channel_related' or 'channel_sibling' edges.
     *
     * @param int $channel_id
     * @param string $edge_type Filter by edge type ('channel_related', 'channel_sibling', etc)
     * @return array
     */
    public static function getRelatedChannels(int $channel_id, string $edge_type = 'channel_related'): array
    {
        $db = self::ensureDb();
        $prefix = self::$prefix;

        $rows = $db->fetchAll(
            "SELECT e.right_object_id AS related_channel_id, c.channel_name, c.channel_description, 
                    e.edge_type, e.semantic_weight, e.created_ymdhis
             FROM {$prefix}edges e
             JOIN {$prefix}channels c ON c.channel_id = e.right_object_id
             WHERE e.left_object_type = 'channel'
               AND e.left_object_id = :channel_id
               AND e.edge_type = :edge_type
               AND e.is_deleted = 0
             ORDER BY e.semantic_weight DESC, e.created_ymdhis DESC",
            ['channel_id' => $channel_id, 'edge_type' => $edge_type]
        );

        return $rows ?? [];
    }

    /**
     * Get all threads spawned from or belonging to a channel
     *
     * Returns threads connected to the channel via 'channel_spawned_thread' edges,
     * which may capture relationships beyond the basic channel_id FK.
     *
     * @param int $channel_id
     * @return array
     */
    public static function getThreadsForChannel(int $channel_id): array
    {
        $db = self::ensureDb();
        $prefix = self::$prefix;

        $rows = $db->fetchAll(
            "SELECT e.right_object_id AS thread_id, dt.title, dt.body_excerpt, 
                    e.edge_type, e.created_ymdhis
             FROM {$prefix}edges e
             JOIN {$prefix}dialog_threads dt ON dt.dialog_thread_id = e.right_object_id
             WHERE e.left_object_type = 'channel'
               AND e.left_object_id = :channel_id
               AND e.right_object_type = 'thread'
               AND e.is_deleted = 0
             ORDER BY e.created_ymdhis DESC",
            ['channel_id' => $channel_id]
        );

        return $rows ?? [];
    }

    /**
     * Get thread lineage using recursive CTE (MySQL 8.0+, MariaDB 10.2+)
     *
     * Traverses the thread continuation/fork chain up to the root thread.
     * Requires database support for recursive CTEs.
     *
     * @param int $thread_id
     * @param int $maxDepth Limit recursion depth to prevent infinite loops
     * @return array
     */
    public static function getThreadLineage(int $thread_id, int $maxDepth = 20): array
    {
        $db = self::ensureDb();
        $prefix = self::$prefix;

        $rows = $db->fetchAll(
            "WITH RECURSIVE thread_lineage AS (
              SELECT e.right_object_id AS ancestor_thread_id, 1 AS depth, dt.title, dt.created_ymdhis
              FROM {$prefix}edges e
              LEFT JOIN {$prefix}dialog_threads dt ON dt.dialog_thread_id = e.right_object_id
              WHERE e.left_object_type = 'thread'
                AND e.left_object_id = :thread_id
                AND e.edge_type = 'thread_continuation'
                AND e.is_deleted = 0
              UNION ALL
              SELECT e.right_object_id, tl.depth + 1, dt.title, dt.created_ymdhis
              FROM {$prefix}edges e
              LEFT JOIN {$prefix}dialog_threads dt ON dt.dialog_thread_id = e.right_object_id
              JOIN thread_lineage tl ON e.left_object_id = tl.ancestor_thread_id
              WHERE e.left_object_type = 'thread'
                AND e.edge_type = 'thread_continuation'
                AND e.is_deleted = 0
                AND tl.depth < :max_depth
            )
            SELECT ancestor_thread_id, depth, title, created_ymdhis
            FROM thread_lineage
            ORDER BY depth DESC",
            ['thread_id' => $thread_id, 'max_depth' => $maxDepth]
        );

        return $rows ?? [];
    }

    /**
     * Get all edges involving a specific object (channel or thread)
     *
     * @param string $object_type 'channel' or 'thread'
     * @param int $object_id
     * @return array
     */
    public static function getEdgesForObject(string $object_type, int $object_id): array
    {
        $db = self::ensureDb();
        $prefix = self::$prefix;

        $rows = $db->fetchAll(
            "SELECT e.* FROM {$prefix}edges e
             WHERE (
                (e.left_object_type = :object_type AND e.left_object_id = :object_id)
                OR
                (e.right_object_type = :object_type AND e.right_object_id = :object_id)
              )
              AND e.is_deleted = 0
             ORDER BY e.edge_type, e.created_ymdhis DESC",
            ['object_type' => $object_type, 'object_id' => $object_id]
        );

        return $rows ?? [];
    }

    /**
     * Get the full channel graph as adjacency list
     *
     * Useful for rendering channel maps or topology views.
     * Returns all channel-to-channel edges in the system.
     *
     * @param int $federation_node_id Optional federation filtering
     * @return array Graph structure: ['channels' => [...], 'edges' => [...]]
     */
    public static function getChannelGraph(int $federation_node_id = 1): array
    {
        $db = self::ensureDb();
        $prefix = self::$prefix;

        $channels = $db->fetchAll(
            "SELECT channel_id, channel_name, channel_description, parent_channel_id 
             FROM {$prefix}channels WHERE is_deleted = 0 ORDER BY channel_id"
        );

        $edges = $db->fetchAll(
            "SELECT e.edge_id, e.left_object_id, e.right_object_id, e.edge_type, 
                    e.bidirectional, e.semantic_weight
             FROM {$prefix}edges e
             WHERE e.left_object_type = 'channel'
               AND e.right_object_type = 'channel'
               AND e.is_deleted = 0
             ORDER BY e.edge_type, e.left_object_id"
        );

        return [
            'channels' => $channels ?? [],
            'edges' => $edges ?? [],
        ];
    }

    /**
     * Get parent-child channel hierarchy
     *
     * @param int $channel_id
     * @return array ['ancestors' => [...], 'descendants' => [...]]
     */
    public static function getChannelHierarchy(int $channel_id): array
    {
        $db = self::ensureDb();
        $prefix = self::$prefix;

        // Ancestors (parents and above)
        $ancestors = $db->fetchAll(
            "WITH RECURSIVE ancestors AS (
              SELECT channel_id, channel_name, parent_channel_id, 0 AS level
              FROM {$prefix}channels WHERE channel_id = :channel_id AND is_deleted = 0
              UNION ALL
              SELECT c.channel_id, c.channel_name, c.parent_channel_id, a.level + 1
              FROM {$prefix}channels c
              JOIN ancestors a ON c.channel_id = a.parent_channel_id
              WHERE c.is_deleted = 0 AND a.level < 20
            )
            SELECT * FROM ancestors ORDER BY level DESC",
            ['channel_id' => $channel_id]
        );

        // Descendants (children and below)
        $descendants = $db->fetchAll(
            "WITH RECURSIVE descendants AS (
              SELECT channel_id, channel_name, parent_channel_id, 0 AS level
              FROM {$prefix}channels WHERE parent_channel_id = :channel_id AND is_deleted = 0
              UNION ALL
              SELECT c.channel_id, c.channel_name, c.parent_channel_id, d.level + 1
              FROM {$prefix}channels c
              JOIN descendants d ON c.parent_channel_id = d.channel_id
              WHERE c.is_deleted = 0 AND d.level < 20
            )
            SELECT * FROM descendants ORDER BY level ASC",
            ['channel_id' => $channel_id]
        );

        return [
            'ancestors' => $ancestors ?? [],
            'descendants' => $descendants ?? [],
        ];
    }

    /**
     * Get edge type statistics
     *
     * @return array Counts per edge type
     */
    public static function getEdgeTypeStats(): array
    {
        $db = self::ensureDb();
        $prefix = self::$prefix;

        $rows = $db->fetchAll(
            "SELECT edge_type, COUNT(*) as count FROM {$prefix}edges
             WHERE is_deleted = 0
             GROUP BY edge_type
             ORDER BY count DESC"
        );

        return array_reduce($rows ?? [], function ($acc, $row) {
            $acc[$row['edge_type']] = (int)$row['count'];
            return $acc;
        }, []);
    }
}
