<?php
/**
 * Test FLIPHeaderParser with Channel 0 broadcast files
 * 
 * Tests parsing FLIP headers from actual Channel 0 broadcast doctrine files.
 * 
 * Run from repo root: php tests/unit/flip_header_parser_broadcast.php
 * PHP 5.3-compatible; no frameworks. Exit 0 = PASS, non-zero = FAIL.
 * 
 * @package Lupopedia\Tests\Unit
 * @since 4.0.44
 */

$repo_root = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}

require_once $repo_root . '/app/Services/Initialization/Interfaces/FLIPHeaderParserInterface.php';
require_once $repo_root . '/app/Services/Initialization/FLIPHeaderParser.php';

$parser = new FLIPHeaderParser();
$failed = 0;
$passed = 0;

// Test with a specific broadcast file
$testFile = $repo_root . '/channels/0/broadcasts/20260224160000_0_10000_php_5_3_compatibility_doctrine.md';

echo "Testing Channel 0 broadcast file parsing...\n\n";

if (!file_exists($testFile)) {
    echo "FAIL: Test file does not exist: {$testFile}\n";
    exit(1);
}

$content = file_get_contents($testFile);
if ($content === false) {
    echo "FAIL: Could not read test file\n";
    exit(1);
}

echo "Test 1: Parse FLIP header... ";
$result = $parser->parse($content);

if (empty($result)) {
    echo "FAIL: No header parsed\n";
    $failed++;
} else {
    echo "PASS\n";
    $passed++;
}

echo "Test 2: Check wolfie.headers exists... ";
if (!isset($result['wolfie.headers'])) {
    echo "FAIL: Missing wolfie.headers key\n";
    $failed++;
} else {
    echo "PASS\n";
    $passed++;
}

echo "Test 3: Check flip.footer exists... ";
if (!isset($result['flip.footer'])) {
    echo "FAIL: Missing flip.footer key\n";
    $failed++;
} else {
    echo "PASS\n";
    $passed++;
}

if (isset($result['wolfie.headers'])) {
    $headers = $result['wolfie.headers'];
    
    echo "Test 4: Check channel_id field... ";
    if (!isset($headers['channel_id'])) {
        echo "FAIL: Missing channel_id\n";
        $failed++;
    } elseif ($headers['channel_id'] != 0) {
        echo "FAIL: Wrong channel_id: " . $headers['channel_id'] . "\n";
        $failed++;
    } else {
        echo "PASS\n";
        $passed++;
    }
    
    echo "Test 5: Check actor_id field... ";
    if (!isset($headers['actor_id'])) {
        echo "FAIL: Missing actor_id\n";
        $failed++;
    } elseif ($headers['actor_id'] !== 10000) {
        echo "FAIL: Wrong actor_id: " . $headers['actor_id'] . "\n";
        $failed++;
    } else {
        echo "PASS\n";
        $passed++;
    }
    
    echo "Test 6: Check system_version field... ";
    if (!isset($headers['system_version'])) {
        echo "FAIL: Missing system_version\n";
        $failed++;
    } elseif ($headers['system_version'] !== '4.0.42') {
        echo "FAIL: Wrong system_version: " . $headers['system_version'] . "\n";
        $failed++;
    } else {
        echo "PASS\n";
        $passed++;
    }
    
    echo "Test 7: Check broadcast_type field... ";
    if (!isset($headers['broadcast_type'])) {
        echo "FAIL: Missing broadcast_type\n";
        $failed++;
    } elseif ($headers['broadcast_type'] !== 'doctrine') {
        echo "FAIL: Wrong broadcast_type: " . $headers['broadcast_type'] . "\n";
        $failed++;
    } else {
        echo "PASS\n";
        $passed++;
    }
    
    echo "\nExtracted header fields:\n";
    foreach ($headers as $key => $value) {
        echo "  - {$key}: " . (is_scalar($value) ? $value : gettype($value)) . "\n";
    }
}

// Test getField method
echo "\nTest 8: getField method... ";
$headers = $parser->getField($content, 'wolfie.headers', null);
if ($headers === null) {
    echo "FAIL: Could not extract wolfie.headers\n";
    $failed++;
} elseif (!is_array($headers)) {
    echo "FAIL: wolfie.headers is not an array\n";
    $failed++;
} elseif (!isset($headers['channel_id'])) {
    echo "FAIL: Missing channel_id in extracted headers\n";
    $failed++;
} else {
    echo "PASS\n";
    $passed++;
}

// Test hasHeader method
echo "Test 9: hasHeader method... ";
if (!$parser->hasHeader($content)) {
    echo "FAIL: hasHeader returned false\n";
    $failed++;
} else {
    echo "PASS\n";
    $passed++;
}

echo "\n";
echo "Tests passed: {$passed}\n";
echo "Tests failed: {$failed}\n";

exit($failed === 0 ? 0 : 1);
