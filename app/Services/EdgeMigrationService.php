<?php
/**
 * EdgeMigrationService
 *
 * LUPOPEDIA HEADERS:
 * file_path_from_root: app/Services/EdgeMigrationService.php
 * when_updated: 20260324194000
 * last_modified_utc: 20260324194000
 * artifact_type: service_class
 * artifact_kind: migration_service
 * purpose: Migrate existing channel/thread relationships from legacy columns to lupo_edges
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
 * EdgeMigrationService
 *
 * Handles migration of relationship data from legacy columns/JSON fields
 * to the canonical lupo_edges table.
 *
 * Track 3a: Migrate lupo_dialog_channels.channels JSON array to lupo_edges
 * Track 3b: Parse lupo_dialog_threads.thread_lineage TEXT (placeholder for parsing logic)
 * Track 3c: Backfill lupo_channels.parent_channel_id as channel_parent edges (SQL-based)
 *
 * Usage:
 *   $migrator = new EdgeMigrationService();
 *   $result = $migrator->migrateDialogChannelRelations();
 */
class EdgeMigrationService
{
    private PDO_DB $db;
    private string $prefix;
    private int $actorId = 12; // ATHENA

    public function __construct()
    {
        $this->db = DatabaseFactory::getConnection();
        $this->prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    /**
     * Track 3a: Migrate lupo_dialog_channels.channels JSON to lupo_edges
     *
     * For each row in lupo_dialog_channels with a non-null 'channels' JSON array,
     * deserialize and create one lupo_edges row per referenced channel with
     * edge_type = 'channel_related' and bidirectional = 1.
     *
     * @return array ['success' => int, 'skipped' => int, 'errors' => array]
     */
    public function migrateDialogChannelRelations(): array
    {
        $result = ['success' => 0, 'skipped' => 0, 'errors' => []];

        try {
            $rows = $this->db->fetchAll(
                "SELECT channel_id, channels FROM {$this->prefix}dialog_channels 
                 WHERE channels IS NOT NULL AND is_deleted = 0"
            );

            if (empty($rows)) {
                return $result;
            }

            foreach ($rows as $row) {
                $sourceChannelId = (int)$row['channel_id'];
                $channelsJson = $row['channels'];

                // Decode JSON
                $related = json_decode($channelsJson, true);
                if (!is_array($related)) {
                    $result['skipped']++;
                    continue;
                }

                // Create edge for each referenced channel
                foreach ($related as $targetChannelId) {
                    $targetId = (int)$targetChannelId;

                    // Skip invalid or self-references
                    if ($targetId <= 0 || $targetId === $sourceChannelId) {
                        continue;
                    }

                    try {
                        $this->db->insert($this->prefix . 'edges', [
                            'left_object_type'     => 'channel',
                            'left_object_id'       => $sourceChannelId,
                            'right_object_type'    => 'channel',
                            'right_object_id'      => $targetId,
                            'edge_type'            => 'channel_related',
                            'channel_id'           => $sourceChannelId,
                            'domain_id'            => 1,
                            'bidirectional'        => 1,
                            'semantic_weight'      => 1.0,
                            'actor_id'             => $this->actorId,
                            'flare_auto_generated' => 1,
                            'flare_db_source'      => 'lupo_dialog_channels.channels',
                            'flare_reason'         => 'Migrated from lupo_dialog_channels.channels JSON',
                            'created_ymdhis'       => (int)gmdate('YmdHis'),
                            'updated_ymdhis'       => (int)gmdate('YmdHis'),
                            'is_deleted'           => 0,
                            'deleted_ymdhis'       => 0,
                        ]);
                        $result['success']++;
                    } catch (\Exception $e) {
                        $result['errors'][] = "Failed to insert edge: {$e->getMessage()}";
                    }
                }
            }
        } catch (\Exception $e) {
            $result['errors'][] = "Fetch failed: {$e->getMessage()}";
        }

        return $result;
    }

    /**
     * Track 3b: Parse thread_lineage TEXT and migrate to lupo_edges
     *
     * This is a placeholder. Thread lineage is free-text and requires
     * heuristic parsing. Common patterns:
     *   - "Thread 123 continuation"
     *   - "Spawned from 456"
     *   - "Fork of thread 789"
     *
     * The parsing logic is site-specific and should be implemented
     * based on actual thread_lineage data format.
     *
     * @return array ['success' => int, 'parsed' => int, 'unparseable' => int, 'errors' => array]
     */
    public function migrateThreadLineage(): array
    {
        $result = ['success' => 0, 'parsed' => 0, 'unparseable' => 0, 'errors' => []];

        try {
            $rows = $this->db->fetchAll(
                "SELECT dialog_thread_id, thread_lineage FROM {$this->prefix}dialog_threads 
                 WHERE thread_lineage IS NOT NULL AND thread_lineage != '' AND is_deleted = 0"
            );

            if (empty($rows)) {
                return $result;
            }

            foreach ($rows as $row) {
                $threadId = (int)$row['dialog_thread_id'];
                $lineageText = trim($row['thread_lineage']);

                // TODO: Implement heuristic parsing of thread_lineage text
                // Example pattern matching:
                //   - Extract numeric IDs
                //   - Infer edge type (continuation, fork, etc)
                //   - Store in lupo_edges with edge_type and properties JSON
                //
                // For now, just count unparseable rows
                $result['unparseable']++;
            }
        } catch (\Exception $e) {
            $result['errors'][] = "Fetch failed: {$e->getMessage()}";
        }

        return $result;
    }

    /**
     * Verify migration success by checking edge counts
     *
     * @return array ['total_edges' => int, 'by_type' => array]
     */
    public function verifyMigration(): array
    {
        $result = ['total_edges' => 0, 'by_type' => []];

        try {
            // Count all edges
            $totalRow = $this->db->fetchOne(
                "SELECT COUNT(*) as cnt FROM {$this->prefix}edges WHERE is_deleted = 0"
            );
            $result['total_edges'] = (int)($totalRow['cnt'] ?? 0);

            // Count by edge type
            $byTypeRows = $this->db->fetchAll(
                "SELECT edge_type, COUNT(*) as cnt FROM {$this->prefix}edges 
                 WHERE is_deleted = 0 GROUP BY edge_type"
            );

            foreach ($byTypeRows as $row) {
                $result['by_type'][$row['edge_type']] = (int)$row['cnt'];
            }
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return $result;
    }
}
