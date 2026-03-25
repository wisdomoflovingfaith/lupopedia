<?php
/**
 * Unit tests for Validator class
 * 
 * Tests validation checks for initialization workflow outputs.
 * 
 * Run: php tests/unit/validator.php
 */

// Set up test environment
define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
define('LUPOPEDIA_ABSPATH', LUPOPEDIA_PATH . '/');

// Load required classes
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/InitializationException.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/ValidationException.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/Interfaces/InitializationLoggerInterface.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/Interfaces/ValidatorInterface.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/InitializationLogger.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/Validator.php';

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

/**
 * Create test files for validation
 */
function setup_test_files($basePath)
{
    // Create test thread directory
    $threadPath = $basePath . '/channels/42/threads/TEST_VALIDATION_THREAD';
    if (!is_dir($threadPath)) {
        @mkdir($threadPath, 0755, true);
    }
    
    // Create thread.json
    $threadMetadata = array(
        'thread_id' => 'TEST_VALIDATION_THREAD',
        'title' => 'Test Thread',
        'type' => 'development',
        'priority' => 'high',
        'visibility' => 'system',
        'created_ymdhis' => gmdate('YmdHis'),
        'created_by_actor_id' => 1001,
        'channel_id' => 42
    );
    file_put_contents($threadPath . '/thread.json', json_encode($threadMetadata, JSON_PRETTY_PRINT));
    
    // Create test audit report
    $auditReportPath = $basePath . '/docs/status/test_audit_report.md';
    file_put_contents($auditReportPath, "# Test Audit Report\n\nThis is a test.");
    
    // Create test summary (under 1000 chars)
    $summaryPath = $threadPath . '/test_summary.md';
    $summaryContent = "---\nactor_id: 1001\n---\n\nTest summary message.";
    file_put_contents($summaryPath, $summaryContent);
    
    // Create test log
    $logPath = $basePath . '/docs/status/test_log.md';
    file_put_contents($logPath, "# Test Log\n\nThis is a test log.");
    
    return array(
        'thread_path' => $threadPath,
        'audit_report_path' => $auditReportPath,
        'summary_path' => $summaryPath,
        'log_path' => $logPath,
        'thread_metadata' => $threadMetadata
    );
}

/**
 * Clean up test files
 */
function cleanup_test_files($testFiles)
{
    // Remove thread.json
    if (isset($testFiles['thread_path'])) {
        $jsonPath = $testFiles['thread_path'] . '/thread.json';
        if (file_exists($jsonPath)) {
            @unlink($jsonPath);
        }
        
        // Remove summary
        $summaryPath = $testFiles['thread_path'] . '/test_summary.md';
        if (file_exists($summaryPath)) {
            @unlink($summaryPath);
        }
        
        // Remove directory
        @rmdir($testFiles['thread_path']);
    }
    
    // Remove audit report
    if (isset($testFiles['audit_report_path']) && file_exists($testFiles['audit_report_path'])) {
        @unlink($testFiles['audit_report_path']);
    }
    
    // Remove log
    if (isset($testFiles['log_path']) && file_exists($testFiles['log_path'])) {
        @unlink($testFiles['log_path']);
    }
}

echo "Running Validator unit tests...\n\n";

$basePath = LUPOPEDIA_PATH;
$logger = new InitializationLogger();
$validator = new Validator($logger, $basePath);

// Set up test files
$testFiles = setup_test_files($basePath);

// Test 1: All validation checks pass
echo "Test 1: All validation checks pass\n";

$context = array(
    'doctrine_count' => 25,
    'thread_id' => 'TEST_VALIDATION_THREAD',
    'thread_metadata' => $testFiles['thread_metadata'],
    'audit_report_path' => 'docs/status/test_audit_report.md',
    'summary_path' => 'channels/42/threads/TEST_VALIDATION_THREAD/test_summary.md',
    'log_path' => 'docs/status/test_log.md',
    'files_deleted' => array()
);

$summary = $validator->validateInitialization($context);

test_assert(is_array($summary), 'validateInitialization returns array');
test_assert(isset($summary['is_valid']), 'summary contains is_valid field');
test_assert(isset($summary['checks']), 'summary contains checks field');
test_assert(isset($summary['errors']), 'summary contains errors field');
test_assert(isset($summary['timestamp']), 'summary contains timestamp field');
test_assert($summary['is_valid'] === true, 'validation passes with valid context');
test_assert($validator->isValid() === true, 'isValid() returns true');
test_assert(empty($validator->getErrors()), 'getErrors() returns empty array');

// Verify all checks passed
test_assert($summary['checks']['doctrine_count']['status'] === 'pass', 'doctrine_count check passed');
test_assert($summary['checks']['thread_directory']['status'] === 'pass', 'thread_directory check passed');
test_assert($summary['checks']['thread_metadata']['status'] === 'pass', 'thread_metadata check passed');
test_assert($summary['checks']['audit_report']['status'] === 'pass', 'audit_report check passed');
test_assert($summary['checks']['summary_length']['status'] === 'pass', 'summary_length check passed');
test_assert($summary['checks']['system_log']['status'] === 'pass', 'system_log check passed');
test_assert($summary['checks']['file_safety']['status'] === 'pass', 'file_safety check passed');

echo "\n";

// Test 2: Doctrine count validation fails
echo "Test 2: Doctrine count validation fails\n";

$logger2 = new InitializationLogger();
$validator2 = new Validator($logger2, $basePath);

$context2 = array(
    'doctrine_count' => 15, // Less than 20
    'thread_id' => 'TEST_VALIDATION_THREAD',
    'thread_metadata' => $testFiles['thread_metadata'],
    'audit_report_path' => 'docs/status/test_audit_report.md',
    'summary_path' => 'channels/42/threads/TEST_VALIDATION_THREAD/test_summary.md',
    'log_path' => 'docs/status/test_log.md',
    'files_deleted' => array()
);

$summary2 = $validator2->validateInitialization($context2);

test_assert($summary2['is_valid'] === false, 'validation fails with insufficient doctrines');
test_assert($validator2->isValid() === false, 'isValid() returns false');
test_assert(!empty($validator2->getErrors()), 'getErrors() returns non-empty array');
test_assert($summary2['checks']['doctrine_count']['status'] === 'fail', 'doctrine_count check failed');

echo "\n";

// Test 3: Thread directory validation fails
echo "Test 3: Thread directory validation fails\n";

$logger3 = new InitializationLogger();
$validator3 = new Validator($logger3, $basePath);

$context3 = array(
    'doctrine_count' => 25,
    'thread_id' => 'NONEXISTENT_THREAD',
    'thread_metadata' => $testFiles['thread_metadata'],
    'audit_report_path' => 'docs/status/test_audit_report.md',
    'summary_path' => 'channels/42/threads/TEST_VALIDATION_THREAD/test_summary.md',
    'log_path' => 'docs/status/test_log.md',
    'files_deleted' => array()
);

$summary3 = $validator3->validateInitialization($context3);

test_assert($summary3['is_valid'] === false, 'validation fails with nonexistent thread');
test_assert($summary3['checks']['thread_directory']['status'] === 'fail', 'thread_directory check failed');

echo "\n";

// Test 4: Thread metadata validation fails
echo "Test 4: Thread metadata validation fails\n";

$logger4 = new InitializationLogger();
$validator4 = new Validator($logger4, $basePath);

$incompleteMetadata = array(
    'thread_id' => 'TEST_VALIDATION_THREAD',
    'title' => 'Test Thread'
    // Missing other required fields
);

$context4 = array(
    'doctrine_count' => 25,
    'thread_id' => 'TEST_VALIDATION_THREAD',
    'thread_metadata' => $incompleteMetadata,
    'audit_report_path' => 'docs/status/test_audit_report.md',
    'summary_path' => 'channels/42/threads/TEST_VALIDATION_THREAD/test_summary.md',
    'log_path' => 'docs/status/test_log.md',
    'files_deleted' => array()
);

$summary4 = $validator4->validateInitialization($context4);

test_assert($summary4['is_valid'] === false, 'validation fails with incomplete metadata');
test_assert($summary4['checks']['thread_metadata']['status'] === 'fail', 'thread_metadata check failed');

echo "\n";

// Test 5: File safety validation fails
echo "Test 5: File safety validation fails\n";

$logger5 = new InitializationLogger();
$validator5 = new Validator($logger5, $basePath);

$context5 = array(
    'doctrine_count' => 25,
    'thread_id' => 'TEST_VALIDATION_THREAD',
    'thread_metadata' => $testFiles['thread_metadata'],
    'audit_report_path' => 'docs/status/test_audit_report.md',
    'summary_path' => 'channels/42/threads/TEST_VALIDATION_THREAD/test_summary.md',
    'log_path' => 'docs/status/test_log.md',
    'files_deleted' => array('some_file.txt', 'another_file.md')
);

$summary5 = $validator5->validateInitialization($context5);

test_assert($summary5['is_valid'] === false, 'validation fails when files were deleted');
test_assert($summary5['checks']['file_safety']['status'] === 'fail', 'file_safety check failed');

echo "\n";

// Test 6: Verify requirement numbers in check results
echo "Test 6: Verify requirement numbers in check results\n";

test_assert($summary['checks']['doctrine_count']['requirement'] === '7.1', 'doctrine_count has requirement 7.1');
test_assert($summary['checks']['thread_directory']['requirement'] === '7.2', 'thread_directory has requirement 7.2');
test_assert($summary['checks']['thread_metadata']['requirement'] === '7.3', 'thread_metadata has requirement 7.3');
test_assert($summary['checks']['audit_report']['requirement'] === '7.4', 'audit_report has requirement 7.4');
test_assert($summary['checks']['summary_length']['requirement'] === '7.5', 'summary_length has requirement 7.5');
test_assert($summary['checks']['system_log']['requirement'] === '7.6', 'system_log has requirement 7.6');
test_assert($summary['checks']['file_safety']['requirement'] === '7.7', 'file_safety has requirement 7.7');

echo "\n";

// Clean up test files
cleanup_test_files($testFiles);

// Summary
echo "=====================================\n";
echo "Tests passed: $tests_passed\n";
echo "Tests failed: $tests_failed\n";
echo "=====================================\n";

if ($tests_failed > 0) {
    exit(1);
}

exit(0);
