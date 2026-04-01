<?php
/**
 * ChannelThreadEdgeMapService
 *
 * Read-only graph map builder for:
 * - one channel
 * - its direct channel edges
 * - related channels via those edges
 * - channel threads
 * - thread edges and derived relationships
 */

if (!class_exists('DatabaseFactory')) {
    require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes/DatabaseFactory.php';
}

class ChannelThreadEdgeMapService
{
    private $db;
    private $tablePrefix;

    public function __construct($db = null, $tablePrefix = null)
    {
        $this->db = $db ? $db : DatabaseFactory::getConnection();
        $this->tablePrefix = $tablePrefix !== null ? $tablePrefix : (defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_');
    }

    /**
     * Build map for one channel and its related graph.
     *
     * @param int $channelId
     * @param int $threadLimit
     * @param int $edgeLimit
     * @return array|null
     */
    public function buildChannelMap($channelId, $threadLimit, $edgeLimit)
    {
        $channelId = (int) $channelId;
        if ($channelId <= 0) {
            return null;
        }

        $threadLimit = max(1, min(500, (int) $threadLimit));
        $edgeLimit = max(1, min(5000, (int) $edgeLimit));

        $channelsT = $this->tablePrefix . 'channels';
        $threadsT = $this->tablePrefix . 'dialog_threads';
        $edgesT = $this->tablePrefix . 'context_edges';

        $channel = $this->db->fetchRow(
            "SELECT channel_id, channel_key, channel_name, channel_type, status_flag, visibility_status, owner_actor_id, access_level, last_activity_ymdhis
             FROM {$channelsT}
             WHERE channel_id = :channel_id AND (is_deleted = 0 OR is_deleted IS NULL)
             LIMIT 1",
            array('channel_id' => $channelId)
        );
        if (!$channel) {
            return null;
        }

        $channelEdges = $this->db->fetchAll(
            "SELECT edge_id, source_type, source_id, target_type, target_id, edge_type, metadata_json, created_ymdhis, updated_ymdhis
             FROM {$edgesT}
             WHERE ((source_type = 'channel' AND source_id = :channel_id) OR (target_type = 'channel' AND target_id = :channel_id))
               AND (is_deleted = 0 OR is_deleted IS NULL)
             ORDER BY created_ymdhis ASC, edge_id ASC
             LIMIT {$edgeLimit}",
            array('channel_id' => $channelId)
        );

        $relatedChannelIds = array();
        foreach ($channelEdges as $edge) {
            $sid = isset($edge['source_id']) ? (int) $edge['source_id'] : 0;
            $tid = isset($edge['target_id']) ? (int) $edge['target_id'] : 0;
            $stype = isset($edge['source_type']) ? (string) $edge['source_type'] : '';
            $ttype = isset($edge['target_type']) ? (string) $edge['target_type'] : '';
            if ($stype === 'channel' && $sid !== $channelId && $sid > 0) {
                $relatedChannelIds[$sid] = true;
            }
            if ($ttype === 'channel' && $tid !== $channelId && $tid > 0) {
                $relatedChannelIds[$tid] = true;
            }
        }

        $relatedChannels = $this->fetchChannelsByIds(array_keys($relatedChannelIds));

        $threads = $this->db->fetchAll(
            "SELECT dialog_thread_id, channel_id, task_name, title, summary_text, status, thread_type, thread_priority, visibility_status, owner_actor_id, assigned_actor_id, created_ymdhis, updated_ymdhis
             FROM {$threadsT}
             WHERE channel_id = :channel_id AND (is_deleted = 0 OR is_deleted IS NULL)
             ORDER BY updated_ymdhis DESC, dialog_thread_id DESC
             LIMIT {$threadLimit}",
            array('channel_id' => $channelId)
        );

        $threadIds = array();
        foreach ($threads as $t) {
            $threadIds[] = (int) $t['dialog_thread_id'];
        }

        $threadEdges = array();
        $threadEdgesByThread = array();
        $relatedThreadIds = array();
        if (!empty($threadIds)) {
            $threadEdges = $this->fetchThreadEdges($threadIds, $edgeLimit);
            foreach ($threadEdges as $edge) {
                $sid = isset($edge['source_id']) ? (int) $edge['source_id'] : 0;
                $tid = isset($edge['target_id']) ? (int) $edge['target_id'] : 0;
                $stype = isset($edge['source_type']) ? (string) $edge['source_type'] : '';
                $ttype = isset($edge['target_type']) ? (string) $edge['target_type'] : '';

                if (($stype === 'thread' || $stype === 'dialog_thread') && in_array($sid, $threadIds, true)) {
                    if (!isset($threadEdgesByThread[$sid])) {
                        $threadEdgesByThread[$sid] = array();
                    }
                    $threadEdgesByThread[$sid][] = $edge;
                    if (($ttype === 'thread' || $ttype === 'dialog_thread') && $tid > 0 && !in_array($tid, $threadIds, true)) {
                        $relatedThreadIds[$tid] = true;
                    }
                    if ($ttype === 'channel' && $tid > 0 && $tid !== $channelId) {
                        $relatedChannelIds[$tid] = true;
                    }
                }

                if (($ttype === 'thread' || $ttype === 'dialog_thread') && in_array($tid, $threadIds, true)) {
                    if (!isset($threadEdgesByThread[$tid])) {
                        $threadEdgesByThread[$tid] = array();
                    }
                    $threadEdgesByThread[$tid][] = $edge;
                    if (($stype === 'thread' || $stype === 'dialog_thread') && $sid > 0 && !in_array($sid, $threadIds, true)) {
                        $relatedThreadIds[$sid] = true;
                    }
                    if ($stype === 'channel' && $sid > 0 && $sid !== $channelId) {
                        $relatedChannelIds[$sid] = true;
                    }
                }
            }
        }

        // Re-hydrate related channels after thread-edge derived additions.
        $relatedChannels = $this->fetchChannelsByIds(array_keys($relatedChannelIds));
        $relatedThreads = $this->fetchThreadsByIds(array_keys($relatedThreadIds));

        return array(
            'channel' => $channel,
            'channel_edges' => $channelEdges,
            'related_channels' => $relatedChannels,
            'threads' => $threads,
            'thread_edges_by_thread' => $threadEdgesByThread,
            'related_threads' => $relatedThreads,
            'summary' => array(
                'channel_edge_count' => count($channelEdges),
                'thread_count' => count($threads),
                'thread_edge_count' => count($threadEdges),
                'related_channel_count' => count($relatedChannels),
                'related_thread_count' => count($relatedThreads),
            ),
        );
    }

    private function fetchThreadEdges($threadIds, $edgeLimit)
    {
        if (empty($threadIds)) {
            return array();
        }
        $edgesT = $this->tablePrefix . 'context_edges';

        $placeholders = array();
        $params = array();
        $i = 0;
        foreach ($threadIds as $id) {
            $key = 'tid' . $i;
            $placeholders[] = ':' . $key;
            $params[$key] = (int) $id;
            $i++;
        }
        $inClause = implode(',', $placeholders);

        $sql = "SELECT edge_id, source_type, source_id, target_type, target_id, edge_type, metadata_json, created_ymdhis, updated_ymdhis
                FROM {$edgesT}
                WHERE (((source_type = 'thread' OR source_type = 'dialog_thread') AND source_id IN ({$inClause}))
                    OR ((target_type = 'thread' OR target_type = 'dialog_thread') AND target_id IN ({$inClause})))
                  AND (is_deleted = 0 OR is_deleted IS NULL)
                ORDER BY created_ymdhis ASC, edge_id ASC
                LIMIT {$edgeLimit}";

        return $this->db->fetchAll($sql, $params);
    }

    private function fetchChannelsByIds($channelIds)
    {
        if (empty($channelIds)) {
            return array();
        }
        $channelsT = $this->tablePrefix . 'channels';
        $placeholders = array();
        $params = array();
        $i = 0;
        foreach ($channelIds as $id) {
            $key = 'cid' . $i;
            $placeholders[] = ':' . $key;
            $params[$key] = (int) $id;
            $i++;
        }
        $sql = "SELECT channel_id, channel_key, channel_name, channel_type, status_flag
                FROM {$channelsT}
                WHERE channel_id IN (" . implode(',', $placeholders) . ")
                  AND (is_deleted = 0 OR is_deleted IS NULL)
                ORDER BY channel_id ASC";
        return $this->db->fetchAll($sql, $params);
    }

    private function fetchThreadsByIds($threadIds)
    {
        if (empty($threadIds)) {
            return array();
        }
        $threadsT = $this->tablePrefix . 'dialog_threads';
        $placeholders = array();
        $params = array();
        $i = 0;
        foreach ($threadIds as $id) {
            $key = 'rid' . $i;
            $placeholders[] = ':' . $key;
            $params[$key] = (int) $id;
            $i++;
        }
        $sql = "SELECT dialog_thread_id, channel_id, task_name, title, status, thread_type, thread_priority
                FROM {$threadsT}
                WHERE dialog_thread_id IN (" . implode(',', $placeholders) . ")
                  AND (is_deleted = 0 OR is_deleted IS NULL)
                ORDER BY dialog_thread_id ASC";
        return $this->db->fetchAll($sql, $params);
    }
}
