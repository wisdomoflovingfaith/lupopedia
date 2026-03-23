<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'Messaging' . DIRECTORY_SEPARATOR . 'MessageEdgeParser.php';

if (!class_exists('DatabaseFactory')) {
    class DatabaseFactory
    {
        public static function getConnection()
        {
            return null;
        }
    }
}

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'ContextGraph' . DIRECTORY_SEPARATOR . 'EdgeValidationService.php';

class FakeEdgeValidationDb
{
    public function fetchRow($sql, $params)
    {
        return null;
    }

    public function fetchAll($sql, $params)
    {
        return array();
    }
}

function assert_true($condition, $label, &$failures)
{
    if ($condition) {
        echo "[PASS] " . $label . PHP_EOL;
        return;
    }
    $failures++;
    echo "[FAIL] " . $label . PHP_EOL;
}

$failures = 0;
$parser = new MessageEdgeParser();
$validator = new EdgeValidationService(new FakeEdgeValidationDb(), 'lupo_');

$message = 'See #123 @456 [doc](789) /assign @456 /depends #123 /produces #900 /block @456';
$edges = $parser->parse($message, 'message', 100);

assert_true(is_array($edges) && count($edges) > 0, 'parser returns edge list', $failures);

foreach ($edges as $index => $edge) {
    $validation = $validator->validateCreate(
        'message',
        100,
        $edge['target_type'],
        $edge['target_id'],
        $edge['edge_type'],
        $edge['direction'],
        $edge['metadata_json']
    );
    assert_true(isset($validation['valid']) && $validation['valid'] === true, 'parser edge validates [' . $index . ']', $failures);
}

$legacyChecks = array(
    array('edge_type' => 'references', 'direction' => 'fwd', 'target_type' => 'actor', 'target_id' => 456),
    array('edge_type' => 'depends_on', 'direction' => 'fwd', 'target_type' => 'thread', 'target_id' => 123),
    array('edge_type' => 'assigns', 'direction' => 'fwd', 'target_type' => 'actor', 'target_id' => 456),
    array('edge_type' => 'produces', 'direction' => 'fwd', 'target_type' => 'artifact', 'target_id' => 900),
    array('edge_type' => 'blocks', 'direction' => 'fwd', 'target_type' => 'actor', 'target_id' => 456)
);

foreach ($legacyChecks as $index => $legacy) {
    $validation = $validator->validateCreate(
        'message',
        100,
        $legacy['target_type'],
        $legacy['target_id'],
        $legacy['edge_type'],
        $legacy['direction'],
        '{}'
    );
    assert_true(isset($validation['valid']) && $validation['valid'] === true, 'legacy alias accepted [' . $index . ']', $failures);
}

echo PHP_EOL;
echo "Failures: " . $failures . PHP_EOL;
exit($failures > 0 ? 1 : 0);
