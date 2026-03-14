<?php
/**
 * Unit tests for InitializationLogger class
 * 
 * Tests structured logging functionality with different log levels.
 * 
 * Run: php tests/unit/initialization_logger.php
 */

// Set up test environment
define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
define('LUPOPEDIA_ABSPATH', LUPOPEDIA_PATH . '/');

// Load required classes
require_once LUPOPEDIA_PATH . '/app/Services/Initialization/Interfaces/InitializationLoggerInterface.php';
require_once LUPOPEDIA_PATH . '/app/Services/Initialization/InitializationLogger.php';

// Test counter
$tests_passed = 0;
$tests_failed = 0;

/**
 * Assert helper
 */
function test_assert($condition, $message)
{
    global $tests_passed, $tests_failed;
    
    if ($condition) {
        echo "[PASS] $message\n";
        $tests_passed++;
    } else {
        echo "[FAIL] $message\n";
        $tests_failed++;
    }
}

echo "Running InitializationLogger unit tests...\n\n";

// Test 1: Logger initialization
echo "Test 1: Logger initialization\n";

$logger = new InitializationLogger();
$entries = $logger->getEntries();

test_assert(is_array($entries), 'getEntries returns array');
test_assert(empty($entries), 'initial entries array is empty');

echo "\n";

// Test 2: Log info message
echo "Test 2: Log info message\n";

$logger->info('Test info message');
$entries = $logger->getEntries();

test_assert(count($entries) === 1, 'one entry added after info()');
test_assert($entries[0]['level'] === 'INFO', 'log level is INFO');
test_assert($entries[0]['message'] === 'Test info message', 'message is correct');
test_assert(isset($entries[0]['timestamp']), 'timestamp is set');
test_assert(preg_match('/^\d{14}$/', $entries[0]['timestamp']), 'timestamp matches YYYYMMDDHHMMSS format');
test_assert(is_array($entries[0]['context']), 'context is array');
test_assert(empty($entries[0]['context']), 'context is empty when not provided');

echo "\n";

// Test 3: Log warning message with context
echo "Test 3: Log warning message with context\n";

$logger->warning('Test warning message', array('file' => 'test.md', 'line' => 42));
$entries = $logger->getEntries();

test_assert(count($entries) === 2, 'two entries after warning()');
test_assert($entries[1]['level'] === 'WARNING', 'log level is WARNING');
test_assert($entries[1]['message'] === 'Test warning message', 'message is correct');
test_assert(!empty($entries[1]['context']), 'context is not empty');
test_assert($entries[1]['context']['file'] === 'test.md', 'context contains file');
test_assert($entries[1]['context']['line'] === 42, 'context contains line');

echo "\n";

// Test 4: Log error message
echo "Test 4: Log error message\n";

$logger->error('Test error message', array('error_code' => 500));
$entries = $logger->getEntries();

test_assert(count($entries) === 3, 'three entries after error()');
test_assert($entries[2]['level'] === 'ERROR', 'log level is ERROR');
test_assert($entries[2]['message'] === 'Test error message', 'message is correct');
test_assert($entries[2]['context']['error_code'] === 500, 'context contains error_code');

echo "\n";

// Test 5: Generic log method
echo "Test 5: Generic log method\n";

$logger->log('INFO', 'Generic log message', array('key' => 'value'));
$entries = $logger->getEntries();

test_assert(count($entries) === 4, 'four entries after log()');
test_assert($entries[3]['level'] === 'INFO', 'log level is INFO');
test_assert($entries[3]['message'] === 'Generic log message', 'message is correct');
test_assert($entries[3]['context']['key'] === 'value', 'context contains key');

echo "\n";

// Test 6: Clear log entries
echo "Test 6: Clear log entries\n";

$logger->clear();
$entries = $logger->getEntries();

test_assert(empty($entries), 'entries array is empty after clear()');

echo "\n";

// Test 7: Multiple log levels
echo "Test 7: Multiple log levels\n";

$logger->info('Info 1');
$logger->warning('Warning 1');
$logger->error('Error 1');
$logger->info('Info 2');

$entries = $logger->getEntries();

test_assert(count($entries) === 4, 'four entries logged');
test_assert($entries[0]['level'] === 'INFO', 'first entry is INFO');
test_assert($entries[1]['level'] === 'WARNING', 'second entry is WARNING');
test_assert($entries[2]['level'] === 'ERROR', 'third entry is ERROR');
test_assert($entries[3]['level'] === 'INFO', 'fourth entry is INFO');

echo "\n";

// Test 8: Log level normalization
echo "Test 8: Log level normalization\n";

$logger2 = new InitializationLogger();
$logger2->log('info', 'Test lowercase');
$logger2->log('Warning', 'Test mixed case');
$logger2->log('ERROR', 'Test uppercase');

$entries2 = $logger2->getEntries();

test_assert($entries2[0]['level'] === 'INFO', 'lowercase level normalized to uppercase');
test_assert($entries2[1]['level'] === 'WARNING', 'mixed case level normalized to uppercase');
test_assert($entries2[2]['level'] === 'ERROR', 'uppercase level remains uppercase');

echo "\n";

// Test 9: Empty context handling
echo "Test 9: Empty context handling\n";

$logger3 = new InitializationLogger();
$logger3->info('Message without context');
$logger3->warning('Message with empty context', array());

$entries3 = $logger3->getEntries();

test_assert(is_array($entries3[0]['context']), 'context is array when not provided');
test_assert(empty($entries3[0]['context']), 'context is empty when not provided');
test_assert(is_array($entries3[1]['context']), 'context is array when empty array provided');
test_assert(empty($entries3[1]['context']), 'context is empty when empty array provided');

echo "\n";

// Summary
echo "=====================================\n";
echo "Tests passed: $tests_passed\n";
echo "Tests failed: $tests_failed\n";
echo "=====================================\n";

if ($tests_failed > 0) {
    exit(1);
}

exit(0);
