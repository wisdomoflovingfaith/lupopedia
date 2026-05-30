<?php
/**
 * Trust Ladder PDO ATTR_STRINGIFY_FETCHES Integration Test
 *
 * Verifies that lupo_memory_nodes PKs (BIGINT) are correctly handled
 * as strings when PDO::ATTR_STRINGIFY_FETCHES is enabled, per
 * CHRONOLOGICAL_TRUST_LADDER.md §2.2.1 and §13.
 *
 * Requires a live DB. Skip gracefully if not available.
 * Run: php lupo-tests/integration/trust_ladder_pdo_stringify_test.php
 */

define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));

$passed = 0;
$failed = 0;
$skipped = 0;

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

function skip_test($label, $reason = '') {
    global $skipped;
    echo "[SKIP] $label" . ($reason ? " ($reason)" : '') . "\n";
    $skipped++;
}

echo "=== Trust Ladder PDO STRINGIFY_FETCHES Integration Tests ===\n\n";

// -----------------------------------------------------------------------
// Bootstrap: load config for DB credentials
// -----------------------------------------------------------------------
$configPath = null;
$candidates = array(
    LUPOPEDIA_PATH . '/lupopedia-config.php',
    LUPOPEDIA_PATH . '/config.php',
);
foreach ($candidates as $c) {
    if (file_exists($c)) {
        $configPath = $c;
        break;
    }
}

if (!$configPath) {
    echo "[SKIP] No config file found — skipping all integration tests\n";
    $skipped = 1;
    echo "\n=== Results: $passed passed, $failed failed, $skipped skipped ===\n";
    exit(0);
}

// Suppress output from config bootstrap
ob_start();
try {
    require_once $configPath;
} catch (Exception $e) {
    ob_end_clean();
    echo "[SKIP] Config bootstrap failed: " . $e->getMessage() . "\n";
    echo "\n=== Results: $passed passed, $failed failed, 1 skipped ===\n";
    exit(0);
}
ob_end_clean();

// -----------------------------------------------------------------------
// Get raw PDO credentials from DatabaseFactory or constants
// -----------------------------------------------------------------------
$dbHost = defined('DB_HOST') ? DB_HOST : (defined('LUPO_DB_HOST') ? LUPO_DB_HOST : 'localhost');
$dbUser = defined('DB_USER') ? DB_USER : (defined('LUPO_DB_USER') ? LUPO_DB_USER : '');
$dbPass = defined('DB_PASS') ? DB_PASS : (defined('LUPO_DB_PASS') ? LUPO_DB_PASS : '');
$dbName = defined('DB_NAME') ? DB_NAME : (defined('LUPO_DB_NAME') ? LUPO_DB_NAME : '');
$tablePrefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

if ($dbUser === '' || $dbName === '') {
    // Try pulling from DatabaseFactory if direct constants not available
    if (!class_exists('DatabaseFactory', false)) {
        $dfPath = LUPOPEDIA_PATH . '/lupo-includes/classes/DatabaseFactory.php';
        if (file_exists($dfPath)) {
            require_once $dfPath;
        }
    }
    if (class_exists('DatabaseFactory', false)) {
        try {
            $dfConn = DatabaseFactory::getConnection();
            // Use the internal PDO if accessible via reflection (best-effort)
            $ref = new ReflectionObject($dfConn);
            if ($ref->hasProperty('pdo')) {
                $prop = $ref->getProperty('pdo');
                $prop->setAccessible(true);
                $pdo = $prop->getValue($dfConn);
            }
        } catch (Exception $e) {
            // ignore
        }
    }
}

// Try connecting directly with raw PDO so we control ATTR_STRINGIFY_FETCHES
function make_pdo($host, $user, $pass, $name, $stringify) {
    $dsn = "mysql:host=$host;dbname=$name;charset=utf8mb4";
    $opts = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_STRINGIFY_FETCHES => $stringify,
    );
    return new PDO($dsn, $user, $pass, $opts);
}

$pdoDefault = null;
$pdoStringify = null;
try {
    $pdoDefault  = make_pdo($dbHost, $dbUser, $dbPass, $dbName, false);
    $pdoStringify = make_pdo($dbHost, $dbUser, $dbPass, $dbName, true);
} catch (Exception $e) {
    echo "[SKIP] Cannot connect to DB: " . $e->getMessage() . "\n";
    echo "\n=== Results: $passed passed, $failed failed, 1 skipped ===\n";
    exit(0);
}

// -----------------------------------------------------------------------
// Check table exists
// -----------------------------------------------------------------------
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/IdGenerator.php';

$memTable = $tablePrefix . 'memory_nodes';
try {
    $check = $pdoDefault->query("SHOW TABLES LIKE '" . $memTable . "'");
    if (!$check || $check->rowCount() === 0) {
        echo "[SKIP] Table $memTable does not exist — skipping\n";
        echo "\n=== Results: $passed passed, $failed failed, 1 skipped ===\n";
        exit(0);
    }
} catch (Exception $e) {
    echo "[SKIP] Cannot check table: " . $e->getMessage() . "\n";
    echo "\n=== Results: $passed passed, $failed failed, 1 skipped ===\n";
    exit(0);
}

// -----------------------------------------------------------------------
// Insert a staging-tier test row
// -----------------------------------------------------------------------
$testNodeId = IdGenerator::generate();  // staging-shaped

// Derive created_ymdhis from first 14 digits of PK (per doctrine)
$createdYmdhis = substr($testNodeId, 0, 14);

$insertSql = "INSERT INTO `$memTable`
    (memory_node_id, owner_type, owner_id, owner_actor_id, memory_type, memory_toon,
     memory_value, memory_slug, created_ymdhis, updated_ymdhis, is_deleted)
    VALUES
    (:id, 'actor', 116, 116, 'kairos_observation', 'test:pdo_stringify',
     '{\"test\":true}', 'test_pdo_stringify', :created, :updated, 0)";

$insertOk = false;
try {
    $stmt = $pdoDefault->prepare($insertSql);
    $stmt->execute(array(
        ':id'      => $testNodeId,
        ':created' => $createdYmdhis,
        ':updated' => $createdYmdhis,
    ));
    $insertOk = true;
} catch (Exception $e) {
    // Column set might differ; try minimal insert
    try {
        $minSql = "INSERT INTO `$memTable` (memory_node_id, is_deleted)
                   VALUES (:id, 0)";
        $stmt = $pdoDefault->prepare($minSql);
        $stmt->execute(array(':id' => $testNodeId));
        $insertOk = true;
    } catch (Exception $e2) {
        echo "[SKIP] Cannot insert test row into $memTable: " . $e2->getMessage() . "\n";
        echo "\n=== Results: $passed passed, $failed failed, 1 skipped ===\n";
        exit(0);
    }
}

echo "Inserted test row with staging PK: $testNodeId\n\n";

// -----------------------------------------------------------------------
// Test 1: ATTR_STRINGIFY_FETCHES = false (default for most PDO_MySQL drivers)
// -----------------------------------------------------------------------
echo "-- Test 1: STRINGIFY_FETCHES = false --\n";

$stmt1 = $pdoDefault->prepare("SELECT memory_node_id FROM `$memTable` WHERE memory_node_id = :id");
$stmt1->execute(array(':id' => $testNodeId));
$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

if ($row1) {
    $raw1 = $row1['memory_node_id'];
    $type1 = gettype($raw1);
    echo "  PHP type of memory_node_id: $type1\n";
    echo "  Value: $raw1\n";

    // Document observed type (may be int on 64-bit, string on 32-bit)
    assert_true('Test 1a: fetched value is numeric (int or string)', is_int($raw1) || is_string($raw1));

    // Casting to string and validating should always work
    $asStr1 = (string) $raw1;
    assert_true('Test 1b: (string) cast equals original staging id',
        $asStr1 === $testNodeId);
    assert_true('Test 1c: validateTrustLadderPk passes after (string) cast',
        IdGenerator::validateTrustLadderPk($asStr1));
} else {
    skip_test('Test 1', 'Row not found');
}

// -----------------------------------------------------------------------
// Test 2: ATTR_STRINGIFY_FETCHES = true (ladder-safe mode)
// -----------------------------------------------------------------------
echo "\n-- Test 2: STRINGIFY_FETCHES = true (ladder-safe) --\n";

$stmt2 = $pdoStringify->prepare("SELECT memory_node_id FROM `$memTable` WHERE memory_node_id = :id");
$stmt2->execute(array(':id' => $testNodeId));
$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

if ($row2) {
    $raw2 = $row2['memory_node_id'];
    $type2 = gettype($raw2);
    echo "  PHP type of memory_node_id: $type2\n";
    echo "  Value: $raw2\n";

    assert_true('Test 2a: STRINGIFY_FETCHES=true returns string', is_string($raw2));
    assert_true('Test 2b: string value equals original staging id', $raw2 === $testNodeId);
    assert_true('Test 2c: validateTrustLadderPk passes directly (no cast needed)',
        IdGenerator::validateTrustLadderPk($raw2));
} else {
    skip_test('Test 2', 'Row not found');
}

// -----------------------------------------------------------------------
// Test 3: toCanonicalId works on both fetch modes
// -----------------------------------------------------------------------
echo "\n-- Test 3: toCanonicalId on fetched values --\n";

if ($row1 && $row2) {
    $canonical1 = IdGenerator::toCanonicalId((string) $row1['memory_node_id']);
    $canonical2 = IdGenerator::toCanonicalId($row2['memory_node_id']);
    assert_true('Test 3a: canonical ids are equal regardless of fetch mode',
        $canonical1 === $canonical2);
    assert_true('Test 3b: canonical id is 18 digits', strlen($canonical1) === 18);
    assert_true('Test 3c: canonical id is in 1000 band',
        (int) substr($canonical1, 0, 4) >= 1000 && (int) substr($canonical1, 0, 4) <= 1999);
} else {
    skip_test('Test 3', 'Cannot run without both row fetches');
}

// -----------------------------------------------------------------------
// Cleanup: soft-delete the test row
// -----------------------------------------------------------------------
echo "\n-- Cleanup --\n";
try {
    $delStmt = $pdoDefault->prepare(
        "UPDATE `$memTable` SET is_deleted = 1 WHERE memory_node_id = :id"
    );
    $delStmt->execute(array(':id' => $testNodeId));
    echo "  Soft-deleted test row $testNodeId\n";
} catch (Exception $e) {
    echo "  WARNING: Could not soft-delete test row: " . $e->getMessage() . "\n";
}

// -----------------------------------------------------------------------
// Summary
// -----------------------------------------------------------------------
echo "\n=== Results: $passed passed, $failed failed, $skipped skipped ===\n";

if ($failed > 0) {
    exit(1);
}
exit(0);
