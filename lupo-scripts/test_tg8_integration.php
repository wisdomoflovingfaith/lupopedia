<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'Messaging' . DIRECTORY_SEPARATOR . 'TG8IntegrationService.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'Messaging' . DIRECTORY_SEPARATOR . 'MessageEdgeParser.php';

class StubParser
{
    private $edges;

    public function __construct($edges)
    {
        $this->edges = $edges;
    }

    public function parse($messageText, $sourceType, $sourceId)
    {
        return $this->edges;
    }
}

class StubEdgeService
{
    public $calls = array();
    private $failAtIndexes;
    private $callIndex;

    public function __construct($failAtIndexes)
    {
        $this->failAtIndexes = $failAtIndexes;
        $this->callIndex = 0;
    }

    public function createEdge($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction, $metadataJson)
    {
        $this->calls[] = array(
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'edge_type' => $edgeType,
            'direction' => $direction,
            'metadata_json' => $metadataJson
        );

        if (in_array($this->callIndex, $this->failAtIndexes, true)) {
            $this->callIndex++;
            throw new Exception('Simulated failure');
        }

        $this->callIndex++;
        return true;
    }
}

function run_case($name, $edges, $failAtIndexes, $expected)
{
    $logs = array();
    $logger = function ($message) use (&$logs) {
        $logs[] = $message;
    };

    $parser = new StubParser($edges);
    $edgeService = new StubEdgeService($failAtIndexes);
    $service = new TG8IntegrationService($parser, $edgeService, $logger);

    $result = $service->processMessage('ignored', 123);
    $actual = array(
        'parsed_count' => $result['parsed_count'],
        'created_count' => $result['created_count'],
        'failed_count' => $result['failed_count'],
        'error_count' => count($result['errors']),
        'call_count' => count($edgeService->calls),
        'log_count' => count($logs)
    );

    $ok = json_encode($actual) === json_encode($expected);
    if ($ok) {
        echo "[PASS] " . $name . PHP_EOL;
        return 0;
    }

    echo "[FAIL] " . $name . PHP_EOL;
    echo "  expected: " . json_encode($expected) . PHP_EOL;
    echo "  actual:   " . json_encode($actual) . PHP_EOL;
    return 1;
}

$failures = 0;

$simpleEdges = array(
    array('target_type' => 'thread', 'target_id' => '42', 'edge_type' => 'references', 'direction' => 'fwd', 'metadata_json' => '{}')
);
$failures += run_case('simple message', $simpleEdges, array(), array(
    'parsed_count' => 1, 'created_count' => 1, 'failed_count' => 0, 'error_count' => 0, 'call_count' => 1, 'log_count' => 0
));

$mixedEdges = array(
    array('target_type' => 'thread', 'target_id' => '42', 'edge_type' => 'references', 'direction' => 'fwd', 'metadata_json' => '{}'),
    array('target_type' => 'actor', 'target_id' => '12', 'edge_type' => 'assigns', 'direction' => 'fwd', 'metadata_json' => '{}'),
    array('target_type' => 'task', 'target_id' => '8', 'edge_type' => 'references', 'direction' => 'fwd', 'metadata_json' => '{}')
);
$failures += run_case('mixed message', $mixedEdges, array(), array(
    'parsed_count' => 3, 'created_count' => 3, 'failed_count' => 0, 'error_count' => 0, 'call_count' => 3, 'log_count' => 0
));

$duplicateEdges = array(
    array('target_type' => 'thread', 'target_id' => '42', 'edge_type' => 'references', 'direction' => 'fwd', 'metadata_json' => '{}'),
    array('target_type' => 'thread', 'target_id' => '42', 'edge_type' => 'references', 'direction' => 'fwd', 'metadata_json' => '{}')
);
$failures += run_case('duplicate message edges are attempted', $duplicateEdges, array(), array(
    'parsed_count' => 2, 'created_count' => 2, 'failed_count' => 0, 'error_count' => 0, 'call_count' => 2, 'log_count' => 0
));

$failureSimulationEdges = array(
    array('target_type' => 'thread', 'target_id' => '42', 'edge_type' => 'references', 'direction' => 'fwd', 'metadata_json' => '{}'),
    array('target_type' => 'actor', 'target_id' => '12', 'edge_type' => 'assigns', 'direction' => 'fwd', 'metadata_json' => '{}'),
    array('target_type' => 'task', 'target_id' => '8', 'edge_type' => 'references', 'direction' => 'fwd', 'metadata_json' => '{}')
);
$failures += run_case('failure simulation continues processing', $failureSimulationEdges, array(1), array(
    'parsed_count' => 3, 'created_count' => 2, 'failed_count' => 1, 'error_count' => 1, 'call_count' => 3, 'log_count' => 1
));

$logs = array();
$logger = function ($message) use (&$logs) {
    $logs[] = $message;
};
$realParser = new MessageEdgeParser();
$realEdgeService = new StubEdgeService(array());
$realService = new TG8IntegrationService($realParser, $realEdgeService, $logger);
$realResult = $realService->processMessage('Ping #thread-x @athena TG-8 [plan](a.md)', 777);
$realExpected = array(
    'parsed_count' => 4,
    'created_count' => 4,
    'failed_count' => 0,
    'error_count' => 0,
    'call_count' => 4
);
$realActual = array(
    'parsed_count' => $realResult['parsed_count'],
    'created_count' => $realResult['created_count'],
    'failed_count' => $realResult['failed_count'],
    'error_count' => count($realResult['errors']),
    'call_count' => count($realEdgeService->calls)
);
if (json_encode($realExpected) === json_encode($realActual)) {
    echo "[PASS] real parser wiring" . PHP_EOL;
} else {
    $failures++;
    echo "[FAIL] real parser wiring" . PHP_EOL;
    echo "  expected: " . json_encode($realExpected) . PHP_EOL;
    echo "  actual:   " . json_encode($realActual) . PHP_EOL;
}

exit($failures > 0 ? 1 : 0);
