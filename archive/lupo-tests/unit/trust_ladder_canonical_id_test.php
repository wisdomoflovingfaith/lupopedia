<?php
/**
 * Trust Ladder Canonical ID — Unit Tests
 *
 * Covers IdGenerator::toCanonicalId(), toCanonicalIdSafe() (mocked DB),
 * seedActorToCanonicalId(), and 32-bit string-safety assertions.
 * Per CHRONOLOGICAL_TRUST_LADDER.md §13.
 *
 * No live DB required — DatabaseFactory is stubbed before IdGenerator loads.
 * Run: php lupo-tests/unit/trust_ladder_canonical_id_test.php
 */

define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));

// -----------------------------------------------------------------------
// DatabaseFactory stub — must be defined BEFORE IdGenerator loads
// IdGenerator::toCanonicalIdSafe() guards with class_exists('DatabaseFactory')
// so our pre-defined stub prevents the real one from being required.
// -----------------------------------------------------------------------

/**
 * Configurable DB mock for toCanonicalIdSafe() collision testing.
 *
 * Usage: MockDb::$return_row_count = N  sets how many times fetchRow() returns
 *        a non-null row (simulating a collision) before returning null.
 */
class MockDb {
    public static $return_row_count = 0;  // how many calls return a collision row
    private static $call_count = 0;

    public static function reset($collisions = 0) {
        self::$return_row_count = $collisions;
        self::$call_count = 0;
    }

    public function fetchRow($sql, $params) {
        self::$call_count++;
        if (self::$call_count <= self::$return_row_count) {
            return array('x' => 1);  // collision row
        }
        return null;  // no row — id is free
    }

    public function quoteIdentifier($name) {
        return '`' . str_replace('`', '', $name) . '`';
    }
}

class DatabaseFactory {
    public static function getConnection() {
        return new MockDb();
    }
}

// Now safe to load IdGenerator — it will use our DatabaseFactory stub.
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/IdGenerator.php';

// -----------------------------------------------------------------------
// Test helpers
// -----------------------------------------------------------------------
$passed = 0;
$failed = 0;

function assert_true($label, $value) {
    global $passed, $failed;
    if ($value) {
        echo "[PASS] $label\n";
        $passed++;
    } else {
        echo "[FAIL] $label\n";
        $failed++;
    }
}

function assert_equals($label, $expected, $actual) {
    global $passed, $failed;
    if ($expected === $actual) {
        echo "[PASS] $label\n";
        $passed++;
    } else {
        echo "[FAIL] $label (expected " . var_export($expected, true) . ", got " . var_export($actual, true) . ")\n";
        $failed++;
    }
}

echo "=== Trust Ladder Canonical ID Tests ===\n\n";

// -----------------------------------------------------------------------
// Section A: toCanonicalId — math transform
// -----------------------------------------------------------------------
echo "-- A: toCanonicalId() --\n";

// A1. Staging 2026 → canonical 1026 (year subtract 1000)
$result = IdGenerator::toCanonicalId('202604081200001234');
assert_equals('A1: staging 2026 → canonical 1026', '102604081200001234', $result);

// A2. Already canonical — no change
$result = IdGenerator::toCanonicalId('102604081200001234');
assert_equals('A2: canonical is unchanged', '102604081200001234', $result);

// A3. Short seed — returned unchanged
$result = IdGenerator::toCanonicalId('42');
assert_equals('A3: short seed 42 unchanged', '42', $result);

// A4. Short seed 116 — unchanged (not long enough for year-strip)
$result = IdGenerator::toCanonicalId('116');
assert_equals('A4: short seed 116 unchanged', '116', $result);

// A5. Year 2000 (boundary) → 1000
$result = IdGenerator::toCanonicalId('200004081200001234');
assert_equals('A5: year 2000 → year 1000', '100004081200001234', $result);

// A6. Year 1999 (already canonical) — no change
$result = IdGenerator::toCanonicalId('199904081200001234');
assert_equals('A6: year 1999 unchanged', '199904081200001234', $result);

// -----------------------------------------------------------------------
// Section B: String-safety / 32-bit PHP assertions
// -----------------------------------------------------------------------
echo "\n-- B: String safety --\n";

// B1. toCanonicalId return is always a string
$r = IdGenerator::toCanonicalId('202604081200001234');
assert_true('B1: toCanonicalId returns string', is_string($r));

// B2. Year extraction via substr is pure string work — 4 digits safe as int
$id18 = '102604081200001234';
$yearStr = substr($id18, 0, 4);
$yearInt = (int) $yearStr;
assert_equals('B2: substr year extraction', '1026', $yearStr);
assert_equals('B2b: year int cast', 1026, $yearInt);

// B3. Demonstrate that staging→canonical conversion is pure string work (no int math on full id).
// Take a staging id, extract year as (int) from substr (safe — only 4 digits), subtract 1000,
// then string-concat the result. Mirrors the doctrine §2.2.1 "Correct PHP" example.
$staging = '202604081200001234';
$sYear   = (int) substr($staging, 0, 4);   // 2026 — safe 4-digit int
$sRest   = substr($staging, 4);             // '04081200001234' — string
$reconstructed = ($sYear - 1000) . $sRest; // '1026' . '04081200001234' = '102604081200001234'
assert_equals('B3: string-concat staging→canonical reconstruct', '102604081200001234', $reconstructed);

// B4. seedActorToCanonicalId produces 18-digit string
$actorCanonical = IdGenerator::seedActorToCanonicalId(116);
assert_true('B4: seedActorToCanonicalId returns string', is_string($actorCanonical));
assert_equals('B4b: length is 18', 18, strlen($actorCanonical));
// 100000000000000000 + 116 = 100000000000000116
assert_equals('B4c: actor 116 canonical', '100000000000000116', $actorCanonical);

// B5. seedActorToCanonicalId(1) → 100000000000000001
assert_equals('B5: actor 1 canonical', '100000000000000001', IdGenerator::seedActorToCanonicalId(1));

// -----------------------------------------------------------------------
// Section C: toCanonicalIdSafe — collision handling (mocked DB)
// -----------------------------------------------------------------------
echo "\n-- C: toCanonicalIdSafe() with mock DB --\n";

// C1. Happy path — no collision (mock returns null on first call)
MockDb::reset(0);
$safe = IdGenerator::toCanonicalIdSafe('202604081200001234', 'memory_nodes', 'memory_node_id');
assert_equals('C1: no collision → canonical', '102604081200001234', $safe);
assert_true('C1b: returns string', is_string($safe));
assert_equals('C1c: length 18', 18, strlen($safe));

// C2. One collision — suffix bumped by 1
// Staging id suffix = 1234, canonical suffix = 1234. First attempt collides, second is free.
MockDb::reset(1);
$safe2 = IdGenerator::toCanonicalIdSafe('202604081200001234', 'memory_nodes', 'memory_node_id');
// Canonical base: 102604081200001234
// After suffix bump: prefix14 = 10260408120000, suffix 1234 → 1235
// So result should be '102604081200001235'
assert_equals('C2: one collision → suffix bumped', '102604081200001235', $safe2);

// C3. Two collisions — suffix bumped twice (1234 → 1235 → 1236)
MockDb::reset(2);
$safe3 = IdGenerator::toCanonicalIdSafe('202604081200001234', 'memory_nodes', 'memory_node_id');
assert_equals('C3: two collisions → suffix bumped twice', '102604081200001236', $safe3);

// C4. Suffix wrap-around — suffix 9999 → 0000
// Canonical with suffix 9999: '102604081200009999'
$stagingWith9999 = '202604081200009999';
MockDb::reset(1);  // first attempt collides, second free
$safe4 = IdGenerator::toCanonicalIdSafe($stagingWith9999, 'memory_nodes', 'memory_node_id');
// 9999 + 1 = 10000 % 10000 = 0 → '0000'
assert_equals('C4: suffix 9999 wraps to 0000', '102604081200000000', $safe4);

// C5. Exhaustion — maxRetries=3, all collide → RuntimeException
MockDb::reset(100);  // always returns a row
$exhausted      = false;
$exhaustedMsg   = '';
try {
    IdGenerator::toCanonicalIdSafe('202604081200001234', 'memory_nodes', 'memory_node_id', 3);
} catch (RuntimeException $e) {
    $exhausted    = true;
    $exhaustedMsg = $e->getMessage();
} catch (Exception $e) {
    // unexpected
}
assert_true('C5: maxRetries exhausted → RuntimeException', $exhausted);
assert_true('C5b: exception message mentions staging ID', strpos($exhaustedMsg, '202604081200001234') !== false);
assert_true('C5c: exception message mentions attempt count', strpos($exhaustedMsg, '3') !== false);

// C6. Exhaustion logging fallback — error_log() fires when execute() absent from mock.
// Capture error_log output via a custom handler to confirm the fallback ran.
$loggedMessages = array();
set_error_handler(null);  // clear any prior handler
$prevHandler = set_error_handler(function($errno, $errstr) use (&$loggedMessages) {
    $loggedMessages[] = $errstr;
    return true;
});
$logFired = false;
$origErrorLog = ini_get('error_log');
// Route error_log to stderr for capture (close enough for test purposes).
// We verify the fallback path by confirming RuntimeException still propagates
// even though MockDb::execute() does not exist (would trigger \Throwable catch).
MockDb::reset(100);
$exhausted2 = false;
try {
    IdGenerator::toCanonicalIdSafe('202604081200005678', 'memory_nodes', 'memory_node_id', 2);
} catch (RuntimeException $e) {
    $exhausted2 = true;
}
restore_error_handler();
assert_true('C6: exhaustion logging fallback does not suppress RuntimeException', $exhausted2);

// -----------------------------------------------------------------------
// Section D: seedActorToCanonicalId edge cases
// -----------------------------------------------------------------------
echo "\n-- D: seedActorToCanonicalId edge cases --\n";

// D1. Negative seed → InvalidArgumentException
$caughtNeg = false;
try {
    IdGenerator::seedActorToCanonicalId(-1);
} catch (InvalidArgumentException $e) {
    $caughtNeg = true;
}
assert_true('D1: negative seed throws InvalidArgumentException', $caughtNeg);

// D2. Non-numeric string → InvalidArgumentException
$caughtStr = false;
try {
    IdGenerator::seedActorToCanonicalId('abc');
} catch (InvalidArgumentException $e) {
    $caughtStr = true;
}
assert_true('D2: non-numeric seed throws InvalidArgumentException', $caughtStr);

// D3. Zero seed is valid (maps to base)
$zeroCanonical = IdGenerator::seedActorToCanonicalId(0);
assert_equals('D3: seed 0 maps to base', '100000000000000000', $zeroCanonical);

// -----------------------------------------------------------------------
// Summary
// -----------------------------------------------------------------------
echo "\n=== Results: $passed passed, $failed failed ===\n";

if ($failed > 0) {
    exit(1);
}
exit(0);
