<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "lupo-scripts/migrate_dialog_channel_edges.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175911"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
lupopedia.headers:
  when_updated: "20260324175617"
  file_path_from_root: "lupo-scripts/migrate_dialog_channel_edges.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175617"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
 * Migration: migrate DialogChannel relationships from JSON to lupo_edges
 * File: lupo-scripts/migrate_dialog_channel_edges.php
 */

require_once __DIR__ . '/../lupopedia-config.php';
require_once __DIR__ . '/../lupo-includes/bootstrap.php';

$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

echo "Migrating DialogChannel relationships from JSON to {$prefix}edges...\n";

$rows = $db->fetchAll("SELECT channel_id, channels FROM {$prefix}dialog_channels WHERE channels IS NOT NULL AND is_deleted = 0");
$count = 0;

foreach ($rows as $row) {
    $related = json_decode($row['channels'], true);
    if (!is_array($related)) {
        continue;
    }
    
    foreach ($related as $target_channel_id) {
        $target_id = (int)$target_channel_id;
        if ($target_id <= 0 || $target_id === (int)$row['channel_id']) {
            continue;
        }
        
        // Check for existing edge to avoid duplicates
        $exists = $db->fetchOne(
            "SELECT edge_id FROM {$prefix}edges WHERE left_object_type = 'channel' AND left_object_id = :lid AND right_object_type = 'channel' AND right_object_id = :rid AND edge_type = 'channel_related' AND is_deleted = 0",
            array('lid' => (int)$row['channel_id'], 'rid' => $target_id)
        );
        
        if (!$exists) {
            $db->insert($prefix . 'edges', array(
                'left_object_type'  => 'channel',
                'left_object_id'    => (int)$row['channel_id'],
                'right_object_type' => 'channel',
                'right_object_id'   => $target_id,
                'edge_type'         => 'channel_related',
                'channel_id'        => (int)$row['channel_id'],
                'domain_id'         => 1,
                'bidirectional'     => 1,
                'actor_id'          => 108, // Junie
                'flare_auto_generated' => 1,
                'flare_db_source'   => 'lupo_dialog_channels.channels',
                'flare_reason'      => 'Migrated from lupo_dialog_channels.channels JSON',
                'created_ymdhis'    => (int)gmdate('YmdHis'),
                'updated_ymdhis'    => (int)gmdate('YmdHis'),
                'is_deleted'        => 0,
                'deleted_ymdhis'    => 0,
            ));
            $count++;
        }
    }
}

echo "Successfully migrated $count relationships into {$prefix}edges.\n";
