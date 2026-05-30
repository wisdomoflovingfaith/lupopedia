<?php
/**
 * HEPHAESTUS SQL Migration Executor
 * Executes P0 edge graph migrations (Tracks 1-3c)
 * Actor: HEPHAESTUS (14)
 */

// Set up path
define('LUPOPEDIA_PATH', dirname(__FILE__));
define('LUPOPEDIA_PUBLIC_PATH', LUPOPEDIA_PATH);

// Load bootstrap
require_once LUPOPEDIA_PATH . '/includes/bootstrap.php';

$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

// Track execution
$results = [
    'Track 1 (Edge Types)' => ['status' => 'pending', 'rows' => 0, 'error' => null],
    'Track 2 (Type Definitions)' => ['status' => 'pending', 'rows' => 0, 'error' => null],
    'Track 3c (Parent Channel Backfill)' => ['status' => 'pending', 'rows' => 0, 'error' => null],
];

echo "=== HEPHAESTUS SQL Migration Execution (2026-03-24) ===\n\n";

// Track 1: Seed edge types (12 types)
echo "[Track 1] Seeding lupo_edge_types (12 edge types)...\n";
try {
    $edgeTypes = [
        [1, 'channel_related', 'Channel Related', 'Channels related semantically. Bidirectional.', 1],
        [2, 'channel_parent', 'Channel Parent', 'Formal hierarchical parent. Directional.', 0],
        [3, 'channel_successor', 'Channel Successor', 'Channel was superseded by target.', 0],
        [4, 'channel_spawned_thread', 'Channel Spawned Thread', 'Channel originated this thread.', 0],
        [5, 'channel_references', 'Channel References', 'Channel cites target.', 0],
        [6, 'thread_continuation', 'Thread Continuation', 'Continues conversation from target.', 0],
        [7, 'thread_spawned_from', 'Thread Spawned From', 'Forked from target thread.', 0],
        [8, 'thread_references', 'Thread References', 'Cites target thread/channel.', 0],
        [9, 'thread_crosses_channel', 'Thread Crosses Channel', 'Activity spans multiple channels.', 0],
        [10, 'channel_sibling', 'Channel Sibling', 'Same level, shared purpose. Bidirectional.', 1],
        [11, 'artifact_spawned_from', 'Artifact Spawned From', 'Produced from this thread.', 0],
        [12, 'channel_observes', 'Channel Observes', 'Monitoring relationship. Directional.', 0],
    ];
    
    $inserted = 0;
    foreach ($edgeTypes as $type) {
        list($id, $slug, $label, $desc, $bidir) = $type;
        $db->insert($prefix . 'edge_types', [
            'edge_type_id' => $id,
            'slug' => $slug,
            'label' => $label,
            'description' => $desc,
            'is_bidirectional' => $bidir,
            'created_ymdhis' => '20260324120000',
            'updated_ymdhis' => '20260324120000',
            'is_deleted' => 0,
        ]);
        $inserted++;
    }
    
    $results['Track 1 (Edge Types)']['status'] = 'success';
    $results['Track 1 (Edge Types)']['rows'] = $inserted;
    echo "  ✓ Inserted $inserted edge types\n";
} catch (Exception $e) {
    $results['Track 1 (Edge Types)']['status'] = 'error';
    $results['Track 1 (Edge Types)']['error'] = $e->getMessage();
    echo "  ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Track 2: Seed edge type definitions (type safety rules)
echo "[Track 2] Seeding lupo_edge_type_definitions (type safety)...\n";
try {
    $definitions = [
        [1, 'channel_related', 'channel', 'channel', 'channel', 'channel'],
        [2, 'channel_parent', 'channel', 'channel', 'channel', 'channel'],
        [3, 'channel_successor', 'channel', 'channel', 'channel', 'channel'],
        [4, 'channel_spawned_thread', 'channel', 'thread', 'channel', 'thread'],
        [5, 'channel_references', 'channel', 'channel', 'channel', 'channel'],
        [6, 'thread_continuation', 'thread', 'thread', 'thread', 'thread'],
        [7, 'thread_spawned_from', 'thread', 'thread', 'thread', 'thread'],
        [8, 'thread_references', 'thread', 'channel|thread', 'thread', 'channel|thread'],
        [9, 'thread_crosses_channel', 'thread', 'channel', 'thread', 'channel'],
        [10, 'channel_sibling', 'channel', 'channel', 'channel', 'channel'],
        [11, 'artifact_spawned_from', 'artifact', 'channel|thread', 'artifact', 'channel|thread'],
        [12, 'channel_observes', 'channel', 'channel', 'channel', 'channel'],
    ];
    
    $inserted = 0;
    foreach ($definitions as $def) {
        list($id, $type, $left, $right, $left_multi, $right_multi) = $def;
        $db->insert($prefix . 'edge_type_definitions', [
            'definition_id' => $id,
            'edge_type' => $type,
            'allowed_left_objects' => $left,
            'allowed_right_objects' => $right,
            'created_ymdhis' => '20260324120000',
            'updated_ymdhis' => '20260324120000',
            'is_deleted' => 0,
        ]);
        $inserted++;
    }
    
    $results['Track 2 (Type Definitions)']['status'] = 'success';
    $results['Track 2 (Type Definitions)']['rows'] = $inserted;
    echo "  ✓ Inserted $inserted type definitions\n";
} catch (Exception $e) {
    $results['Track 2 (Type Definitions)']['status'] = 'error';
    $results['Track 2 (Type Definitions)']['error'] = $e->getMessage();
    echo "  ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Track 3c: Backfill parent_channel_id as channel_parent edges
echo "[Track 3c] Backfilling parent_channel_id as channel_parent edges...\n";
try {
    $channels = $db->fetchAll(
        "SELECT channel_id, parent_channel_id FROM {$prefix}channels 
         WHERE parent_channel_id IS NOT NULL AND parent_channel_id != 0 AND is_deleted = 0"
    );
    
    $inserted = 0;
    foreach ($channels as $channel) {
        $db->insert($prefix . 'edges', [
            'left_object_type' => 'channel',
            'left_object_id' => (int)$channel['channel_id'],
            'right_object_type' => 'channel',
            'right_object_id' => (int)$channel['parent_channel_id'],
            'edge_type' => 'channel_parent',
            'bidirectional' => 0,
            'created_ymdhis' => '20260324120000',
            'updated_ymdhis' => '20260324120000',
            'is_deleted' => 0,
        ]);
        $inserted++;
    }
    
    $results['Track 3c (Parent Channel Backfill)']['status'] = 'success';
    $results['Track 3c (Parent Channel Backfill)']['rows'] = $inserted;
    echo "  ✓ Backfilled $inserted parent channel edges\n";
} catch (Exception $e) {
    $results['Track 3c (Parent Channel Backfill)']['status'] = 'error';
    $results['Track 3c (Parent Channel Backfill)']['error'] = $e->getMessage();
    echo "  ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";
echo "=== Execution Summary ===\n";
foreach ($results as $track => $result) {
    $status = $result['status'] === 'success' ? '✓' : ($result['status'] === 'error' ? '✗' : '⏳');
    $rowsText = $result['rows'] > 0 ? " ({$result['rows']} rows)" : '';
    echo "$status $track: {$result['status']}$rowsText\n";
    if ($result['error']) {
        echo "  Error: {$result['error']}\n";
    }
}

echo "\n=== Next Steps ===\n";
echo "1. Verify edge_types table has 12 rows\n";
echo "2. Verify edge_type_definitions table has 12 rows\n";
echo "3. Verify edges table has new channel_parent relationships\n";
echo "4. Route to THOTH for P0 edge review queue (48-hour SLA)\n";
?>
