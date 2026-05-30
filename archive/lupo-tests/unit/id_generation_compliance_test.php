<?php
/**
 * Test ID Generation Compliance
 * 
 * Verifies that IdGenerator produces 63-bit signed-safe BIGINTs
 * in the format: YYYYMMDDHHIISS + random suffix
 */

require_once __DIR__ . '/../../lupo-includes/classes/IdGenerator.php';

echo "=== ID Generation Compliance Test ===\n\n";

// Test 1: Generate multiple IDs
echo "Test 1: Generate 10 IDs\n";
for ($i = 0; $i < 10; $i++) {
    $id = IdGenerator::generate();
    echo "ID $i: $id\n";
    
    // Validate format
    if (!IdGenerator::validateFormat($id)) {
        echo "  ❌ Invalid format!\n";
    } else {
        echo "  ✅ Valid format\n";
    }
    
    // Check if it's 18 digits (fits in 63-bit signed BIGINT)
    if (strlen($id) !== 18) {
        echo "  ❌ Wrong length: " . strlen($id) . " digits (should be 18)\n";
    } else {
        echo "  ✅ Correct length: 18 digits\n";
    }
    
    // Extract components
    $timestamp = IdGenerator::extractTimestamp($id);
    $suffix = IdGenerator::extractSuffix($id);
    echo "  Timestamp: $timestamp\n";
    echo "  Suffix: $suffix\n\n";
}

// Test 2: Check 63-bit signed safety
echo "\nTest 2: 63-bit Signed Safety Check\n";
$test_id = IdGenerator::generate();
$numeric_id = (int)$test_id;

// PHP_INT_MAX check
if ($numeric_id > PHP_INT_MAX) {
    echo "❌ ID exceeds PHP_INT_MAX\n";
} else {
    echo "✅ ID within PHP_INT_MAX\n";
}

// 63-bit signed max: 9,223,372,036,854,775,807
$signed_63bit_max = 9223372036854775807;
if ($numeric_id > $signed_63bit_max) {
    echo "❌ ID exceeds 63-bit signed max\n";
} else {
    echo "✅ ID within 63-bit signed range\n";
}

echo "\nTest 3: Timestamp Format Validation\n";
$timestamp = IdGenerator::extractTimestamp($test_id);
if (preg_match('/^\d{14}$/', $timestamp)) {
    echo "✅ Timestamp is 14 digits\n";
} else {
    echo "❌ Timestamp format error\n";
}

// Check if timestamp is valid date
$year = substr($timestamp, 0, 4);
$month = substr($timestamp, 4, 2);
$day = substr($timestamp, 6, 2);
$hour = substr($timestamp, 8, 2);
$minute = substr($timestamp, 10, 2);
$second = substr($timestamp, 12, 2);

if (checkdate($month, $day, $year) && $hour < 24 && $minute < 60 && $second < 60) {
    echo "✅ Timestamp represents valid date/time\n";
} else {
    echo "❌ Invalid date/time in timestamp\n";
}

echo "\nTest 4: Random Suffix Range Check\n";
$suffix = IdGenerator::extractSuffix($test_id);
$suffix_int = (int)$suffix;
if ($suffix_int >= 0 && $suffix_int <= 9999) {
    echo "✅ Suffix in valid range: 0-9999\n";
} else {
    echo "❌ Suffix out of range: $suffix_int\n";
}

echo "\n=== Test Complete ===\n";
