<?php
/**
 * Unit tests for TimestampHelper class
 * 
 * Tests timestamp generation, validation, and formatting functionality.
 * 
 * @package Lupopedia\Tests\Unit
 * @since 4.0.44
 */

// Load the interface and class
require_once __DIR__ . '/../../lupo-app/Services/Initialization/Interfaces/TimestampHelperInterface.php';
require_once __DIR__ . '/../../lupo-app/Services/Initialization/TimestampHelper.php';

// Test counter
$tests_passed = 0;
$tests_failed = 0;

/**
 * Assert helper function
 */
function assert_true($condition, $message) {
    global $tests_passed, $tests_failed;
    if ($condition) {
        echo "[PASS] $message\n";
        $tests_passed++;
    } else {
        echo "[FAIL] $message\n";
        $tests_failed++;
    }
}

function assert_false($condition, $message) {
    assert_true(!$condition, $message);
}

function assert_equals($expected, $actual, $message) {
    global $tests_passed, $tests_failed;
    if ($expected === $actual) {
        echo "[PASS] $message\n";
        $tests_passed++;
    } else {
        echo "[FAIL] $message (expected: '$expected', got: '$actual')\n";
        $tests_failed++;
    }
}

// Create instance
$helper = new TimestampHelper();

echo "=== TimestampHelper Unit Tests ===\n\n";

// Test 1: getCurrentUTC returns 14-digit string
echo "Test Group: getCurrentUTC()\n";
$timestamp = $helper->getCurrentUTC();
assert_true(is_string($timestamp), "getCurrentUTC returns a string");
assert_equals(14, strlen($timestamp), "getCurrentUTC returns 14 characters");
assert_true(ctype_digit($timestamp), "getCurrentUTC returns only digits");
assert_true(preg_match('/^\d{14}$/', $timestamp) === 1, "getCurrentUTC matches YYYYMMDDHHMMSS pattern");
echo "\n";

// Test 2: Validate valid timestamps
echo "Test Group: isValidTimestamp() - Valid timestamps\n";
assert_true($helper->isValidTimestamp('20260224153045'), "Valid timestamp: 20260224153045");
assert_true($helper->isValidTimestamp('20000101000000'), "Valid timestamp: 20000101000000 (Y2K)");
assert_true($helper->isValidTimestamp('20991231235959'), "Valid timestamp: 20991231235959 (end of day)");
assert_true($helper->isValidTimestamp('20240229120000'), "Valid timestamp: 20240229120000 (leap year)");
assert_true($helper->isValidTimestamp($timestamp), "Valid timestamp: current timestamp from getCurrentUTC()");
echo "\n";

// Test 3: Validate invalid timestamps
echo "Test Group: isValidTimestamp() - Invalid timestamps\n";
assert_false($helper->isValidTimestamp('2026022415304'), "Invalid: too short (13 digits)");
assert_false($helper->isValidTimestamp('202602241530455'), "Invalid: too long (15 digits)");
assert_false($helper->isValidTimestamp('20261399999999'), "Invalid: month 99");
assert_false($helper->isValidTimestamp('20260232153045'), "Invalid: day 32");
assert_false($helper->isValidTimestamp('20260224253045'), "Invalid: hour 25");
assert_false($helper->isValidTimestamp('20260224156045'), "Invalid: minute 60");
assert_false($helper->isValidTimestamp('20260224153060'), "Invalid: second 60");
assert_false($helper->isValidTimestamp('20230229120000'), "Invalid: Feb 29 in non-leap year");
assert_false($helper->isValidTimestamp('abcd0224153045'), "Invalid: contains letters");
assert_false($helper->isValidTimestamp('2026-02-24 15:30:45'), "Invalid: contains separators");
assert_false($helper->isValidTimestamp(''), "Invalid: empty string");
assert_false($helper->isValidTimestamp(null), "Invalid: null");
assert_false($helper->isValidTimestamp(20260224153045), "Invalid: integer instead of string");
echo "\n";

// Test 4: Format for display
echo "Test Group: formatForDisplay()\n";
assert_equals(
    '2026-02-24 15:30:45 UTC',
    $helper->formatForDisplay('20260224153045'),
    "Format valid timestamp correctly"
);
assert_equals(
    '2000-01-01 00:00:00 UTC',
    $helper->formatForDisplay('20000101000000'),
    "Format midnight timestamp correctly"
);
assert_equals(
    '2099-12-31 23:59:59 UTC',
    $helper->formatForDisplay('20991231235959'),
    "Format end-of-day timestamp correctly"
);
assert_equals(
    'Invalid timestamp',
    $helper->formatForDisplay('invalid'),
    "Return error message for invalid timestamp"
);
assert_equals(
    'Invalid timestamp',
    $helper->formatForDisplay('20261399999999'),
    "Return error message for malformed timestamp"
);
echo "\n";

// Test 5: UTC timezone verification
echo "Test Group: UTC timezone verification\n";
$before = time();
$timestamp = $helper->getCurrentUTC();
$after = time();

// Parse the timestamp
$year = (int) substr($timestamp, 0, 4);
$month = (int) substr($timestamp, 4, 2);
$day = (int) substr($timestamp, 6, 2);
$hour = (int) substr($timestamp, 8, 2);
$minute = (int) substr($timestamp, 10, 2);
$second = (int) substr($timestamp, 12, 2);

// Convert to Unix timestamp
$timestamp_unix = gmmktime($hour, $minute, $second, $month, $day, $year);

// Verify it's within reasonable range (allowing for test execution time)
$diff_before = abs($timestamp_unix - $before);
$diff_after = abs($timestamp_unix - $after);
assert_true($diff_before <= 2, "Timestamp is close to current time (before check)");
assert_true($diff_after <= 2, "Timestamp is close to current time (after check)");

// Verify it matches gmdate output
$expected = gmdate('YmdHis', $timestamp_unix);
assert_equals($expected, $timestamp, "Timestamp matches gmdate() output");
echo "\n";

// Summary
echo "=== Test Summary ===\n";
echo "Passed: $tests_passed\n";
echo "Failed: $tests_failed\n";
echo "Total: " . ($tests_passed + $tests_failed) . "\n";

if ($tests_failed > 0) {
    exit(1);
}

echo "\nAll tests passed!\n";
exit(0);
