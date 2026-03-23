<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'Messaging' . DIRECTORY_SEPARATOR . 'MessageEdgeParser.php';

$parser = new MessageEdgeParser();

$tests = array(
    array(
        'name' => 'thread reference',
        'message' => 'See #thread-123 now.',
        'expected' => array(
            array('target_type' => 'thread', 'target_id' => 'thread-123', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
        )
    ),
    array(
        'name' => 'actor mention',
        'message' => 'Route to @hephaestus.',
        'expected' => array(
            array('target_type' => 'actor', 'target_id' => 'hephaestus', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
        )
    ),
    array(
        'name' => 'artifact link',
        'message' => 'Open [plan](lupo-docs/versions/4.0.86/PLAN.md).',
        'expected' => array(
            array('target_type' => 'artifact', 'target_id' => 'lupo-docs/versions/4.0.86/PLAN.md', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
        )
    ),
    array(
        'name' => 'task reference',
        'message' => 'Execute TG-8 after TG-4.',
        'expected' => array(
            array('target_type' => 'task', 'target_id' => 'TG-8', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
            array('target_type' => 'task', 'target_id' => 'TG-4', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
        )
    ),
    array(
        'name' => 'assign command',
        'message' => '/assign @athena',
        'expected' => array(
            array('target_type' => 'actor', 'target_id' => 'athena', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
            array('target_type' => 'actor', 'target_id' => 'athena', 'edge_type' => 'implements', 'direction' => 'fwd', 'metadata_json' => '{}'),
        )
    ),
    array(
        'name' => 'depends command',
        'message' => '/depends #agent-system-design',
        'expected' => array(
            array('target_type' => 'thread', 'target_id' => 'agent-system-design', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
            array('target_type' => 'thread', 'target_id' => 'agent-system-design', 'edge_type' => 'dependency', 'direction' => 'fwd', 'metadata_json' => '{}'),
        )
    ),
    array(
        'name' => 'produces command',
        'message' => '/produces #artifact.md',
        'expected' => array(
            array('target_type' => 'artifact', 'target_id' => 'artifact.md', 'edge_type' => 'contains', 'direction' => 'fwd', 'metadata_json' => '{}'),
        )
    ),
    array(
        'name' => 'block command',
        'message' => '/block @lilith',
        'expected' => array(
            array('target_type' => 'actor', 'target_id' => 'lilith', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
            array('target_type' => 'actor', 'target_id' => 'lilith', 'edge_type' => 'contradiction', 'direction' => 'both', 'metadata_json' => '{}'),
        )
    ),
    array(
        'name' => 'deterministic ordering mixed',
        'message' => 'TG-8 refs [a](x.md) @hermes #thread-x /assign @athena /depends #thread-y /produces #out.md /block @rose',
        'expected' => array(
            array('target_type' => 'thread', 'target_id' => 'thread-x', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
            array('target_type' => 'thread', 'target_id' => 'thread-y', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
            array('target_type' => 'actor', 'target_id' => 'hermes', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
            array('target_type' => 'actor', 'target_id' => 'athena', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
            array('target_type' => 'actor', 'target_id' => 'rose', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
            array('target_type' => 'artifact', 'target_id' => 'x.md', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
            array('target_type' => 'task', 'target_id' => 'TG-8', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
            array('target_type' => 'actor', 'target_id' => 'athena', 'edge_type' => 'implements', 'direction' => 'fwd', 'metadata_json' => '{}'),
            array('target_type' => 'thread', 'target_id' => 'thread-y', 'edge_type' => 'dependency', 'direction' => 'fwd', 'metadata_json' => '{}'),
            array('target_type' => 'artifact', 'target_id' => 'out.md', 'edge_type' => 'contains', 'direction' => 'fwd', 'metadata_json' => '{}'),
            array('target_type' => 'actor', 'target_id' => 'rose', 'edge_type' => 'contradiction', 'direction' => 'both', 'metadata_json' => '{}'),
        )
    ),
    array(
        'name' => 'duplicate mention dedupe',
        'message' => '@athena @athena #x #x TG-8 TG-8 [d](x.md) [d](x.md)',
        'expected' => array(
            array('target_type' => 'thread', 'target_id' => 'x', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
            array('target_type' => 'actor', 'target_id' => 'athena', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
            array('target_type' => 'artifact', 'target_id' => 'x.md', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
            array('target_type' => 'task', 'target_id' => 'TG-8', 'edge_type' => 'reference', 'direction' => 'both', 'metadata_json' => '{}'),
        )
    ),
    array(
        'name' => 'no matches',
        'message' => 'plain message with no graph hints',
        'expected' => array()
    ),
);

$passed = 0;
$failed = 0;

foreach ($tests as $idx => $test) {
    $actual = $parser->parse($test['message'], 'message', 1);
    $expJson = json_encode($test['expected']);
    $actJson = json_encode($actual);
    if ($expJson === $actJson) {
        $passed++;
        echo "[PASS] " . $test['name'] . PHP_EOL;
    } else {
        $failed++;
        echo "[FAIL] " . $test['name'] . PHP_EOL;
        echo "  expected: " . $expJson . PHP_EOL;
        echo "  actual:   " . $actJson . PHP_EOL;
    }
}

echo PHP_EOL;
echo "Tests run: " . count($tests) . PHP_EOL;
echo "Passed: " . $passed . PHP_EOL;
echo "Failed: " . $failed . PHP_EOL;

exit($failed > 0 ? 1 : 0);
