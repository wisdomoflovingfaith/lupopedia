<?php
/**
 * Trust Ladder PK Validation — Unit Tests
 *
 * Covers IdGenerator::validateTrustLadderPk() and ::validateFormat()
 * per CHRONOLOGICAL_TRUST_LADDER.md §13.
 *
 * No DB connection required. Run: php lupo-tests/unit/trust_ladder_pk_validation_test.php
 */

define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));

// Prevent any require inside IdGenerator from pulling in DatabaseFactory.
// For this unit file only validateTrustLadderPk / validateFormat / generate are used
// and all DB-touching methods are NOT called.
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/IdGenerator.php';

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

function assert_false($label, $value) {
    global $passed, $failed;
    if (!$value) {
        echo "[PASS] $label\n";
        $passed++;
    } else {
        echo "[FAIL] $label\n";
        $failed++;
    }
}

echo "=== Trust Ladder PK Validation Tests ===\n\n";

// -----------------------------------------------------------------------
// Section A: validateTrustLadderPk — valid cases
// -----------------------------------------------------------------------
echo "-- A: Valid IDs --\n";

// A1. Valid 18-digit living canonical (embedded year 1026)
assert_true('A1: canonical year 1026', IdGenerator::validateTrustLadderPk('102604081234560000'));

// A2. Valid 18-digit staging (embedded year 2026)
assert_true('A2: staging year 2026', IdGenerator::validateTrustLadderPk('202604081234560000'));

// A3. Embedded year 1000 — lowest canonical band
assert_true('A3: canonical year 1000', IdGenerator::validateTrustLadderPk('100004081234560000'));

// A4. Embedded year 9999 — upper limit of the valid band
assert_true('A4: year 9999 upper limit', IdGenerator::validateTrustLadderPk('999904081234560000'));

// A5. Suffix all zeros is valid
assert_true('A5: zero suffix', IdGenerator::validateTrustLadderPk('202604081200000000'));

// A6. Suffix all nines is valid
assert_true('A6: nine suffix', IdGenerator::validateTrustLadderPk('202604081200009999'));

// -----------------------------------------------------------------------
// Section B: validateTrustLadderPk — invalid shapes
// -----------------------------------------------------------------------
echo "\n-- B: Invalid shapes --\n";

// B1. 17 digits — too short for 18-digit band
assert_false('B1: 17 digits', IdGenerator::validateTrustLadderPk('12345678901234567'));

// B2. 19 digits — too long (not seed, not 18-digit)
assert_false('B2: 19 digits', IdGenerator::validateTrustLadderPk('1234567890123456789'));

// B3. Contains non-digit characters
assert_false('B3: contains alpha', IdGenerator::validateTrustLadderPk('2026040812345ABC00'));

// B4. Embedded year 0999 — below 1000 band (18 digits, year in 0-band)
// '099904081234560000' — year=0999 which is < 1000, so invalid for 18-digit ladder
// Note: under the 0-999,999 seed band, this value is not seed-space and still fails
// because the embedded year is below 1000.
assert_false('B4: year 0999 (below 1000 band)', IdGenerator::validateTrustLadderPk('099904081234560000'));

// B5. Empty string
assert_false('B5: empty string', IdGenerator::validateTrustLadderPk(''));

// B6. String with leading space
assert_false('B6: leading space', IdGenerator::validateTrustLadderPk(' 202604081234560000'));

// -----------------------------------------------------------------------
// Section C: seed / reserved-space IDs
// -----------------------------------------------------------------------
echo "\n-- C: Seed / reserved-space IDs --\n";

// C1. Short seed without a matching registry entry (context: a non-actor table)
// Expected: false — reserved space but not registered for that context.
assert_false('C1: seed 42 with non-actor context',
    IdGenerator::validateTrustLadderPk('42', 'memory_nodes.memory_node_id'));

// C2. Short seed 1 with actor context — should hit the registry.json lookup.
// This is integration-dependent; skip gracefully if registry.json is missing.
$registryPath = LUPOPEDIA_PATH . '/lupo-database/lupopedia/actors/registry.json';
if (is_file($registryPath)) {
    $result = IdGenerator::validateTrustLadderPk('1', 'actors.actor_id');
    assert_true('C2: actor seed 1 (WOLFIE) with actors.actor_id context', $result);
} else {
    echo "[SKIP] C2: actors/registry.json not readable — skipping seed actor lookup\n";
}

// C3. Actor seed 116 (Claude Code) with actor context — if registry.json present
if (is_file($registryPath)) {
    $result = IdGenerator::validateTrustLadderPk('116', 'actors.actor_id');
    assert_true('C3: actor seed 116 (Claude Code) with actors.actor_id context', $result);
} else {
    echo "[SKIP] C3: actors/registry.json not readable — skipping\n";
}

// C4. A short ID (7) that has no registry entry anywhere
// Expected: false — reserved space but not registered
assert_false('C4: seed 7 no context no registry match',
    IdGenerator::validateTrustLadderPk('7', 'some_table.some_col'));

// -----------------------------------------------------------------------
// Section D: throw=true path
// -----------------------------------------------------------------------
echo "\n-- D: Exception throwing --\n";

// D1. throw=true on invalid id should throw InvalidArgumentException
$caughtD1 = false;
try {
    IdGenerator::validateTrustLadderPk('bad_value', 'test.context', true);
} catch (InvalidArgumentException $e) {
    $caughtD1 = true;
} catch (Exception $e) {
    // Unexpected exception type
}
assert_true('D1: throw=true raises InvalidArgumentException on invalid id', $caughtD1);

// D2. throw=true on valid canonical id should NOT throw
$caughtD2 = false;
try {
    IdGenerator::validateTrustLadderPk('102604081234560000', null, true);
} catch (Exception $e) {
    $caughtD2 = true;
}
assert_false('D2: throw=true on valid canonical id does not throw', $caughtD2);

// -----------------------------------------------------------------------
// Section E: validateFormat — staging-only validation
// -----------------------------------------------------------------------
echo "\n-- E: validateFormat (staging clock band only) --\n";

// E1. Fresh generate() output must pass validateFormat
$fresh = IdGenerator::generate();
assert_true('E1: fresh generate() passes validateFormat', IdGenerator::validateFormat($fresh));

// E2. Fresh generate() output is 18 digits
assert_true('E2: fresh generate() is 18 digits', strlen($fresh) === 18);

// E3. A canonical-band ID must FAIL validateFormat (it is outside 2000–2099 clock range)
assert_false('E3: canonical id fails validateFormat', IdGenerator::validateFormat('102604081234560000'));

// E4. Staging ID in 2026 passes validateFormat
assert_true('E4: staging 2026 id passes validateFormat', IdGenerator::validateFormat('202604081200000001'));

// E5. An ID with year outside 20xx fails validateFormat
assert_false('E5: year 3000 fails validateFormat', IdGenerator::validateFormat('300004081200000001'));

// -----------------------------------------------------------------------
// Section F: string type assertions
// -----------------------------------------------------------------------
echo "\n-- F: Return type assertions --\n";

// F1. validateTrustLadderPk returns bool
$r = IdGenerator::validateTrustLadderPk('202604081234560000');
assert_true('F1: return type is bool', is_bool($r));

// F2. generate() returns string
assert_true('F2: generate() returns string', is_string(IdGenerator::generate()));

// -----------------------------------------------------------------------
// Summary
// -----------------------------------------------------------------------
echo "\n=== Results: $passed passed, $failed failed ===\n";

if ($failed > 0) {
    exit(1);
}
exit(0);
