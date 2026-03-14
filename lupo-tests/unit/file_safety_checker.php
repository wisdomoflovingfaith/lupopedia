<?php
/**
 * Unit tests for FileSafetyChecker class
 * 
 * Tests file operation tracking and verification that no files are deleted.
 * 
 * Run: php tests/unit/file_safety_checker.php
 */

// Set up paths
define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
define('LUPOPEDIA_ABSPATH', LUPOPEDIA_PATH . '/');

// Load required classes
require_once LUPOPEDIA_PATH . '/app/Services/Initialization/Interfaces/InitializationLoggerInterface.php';
require_once LUPOPEDIA_PATH . '/app/Services/Initialization/Interfaces/FileSafetyCheckerInterface.php';
require_once LUPOPEDIA_PATH . '/app/Services/Initialization/InitializationLogger.php';
require_once LUPOPEDIA_PATH . '/app/Services/Initialization/FileSafetyChecker.php';

// Test counter
$tests_passed = 0;
$tests_failed = 0;

/**
 * Test assertion helper
 */
function test_assert($condition, $message)
{
    global $tests_passed, $tests_failed;
    
    if ($condition) {
        echo "  ✓ {$message}\n";
        $tests_passed++;
    } else {
        echo "  ✗ FAILED: {$message}\n";
        $tests_failed++;
    }
}

echo "=== FileSafetyChecker Unit Tests ===\n\n";

// Test 1: Track create operations
echo "Test 1: Track create operations\n";

$logger1 = new InitializationLogger();
$checker1 = new FileSafetyChecker($logger1);

$checker1->trackOperation('create', 'docs/status/report.md');
$checker1->trackOperation('create', 'channels/42/threads/test/thread.json');

$operations1 = $checker1->getOperations();
test_assert(count($operations1) === 2, 'tracked 2 operations');
test_assert($operations1[0]['operation'] === 'create', 'first operation is create');
test_assert($operations1[0]['file_path'] === 'docs/status/report.md', 'first file path is correct');
test_assert($checker1->getOperationCount('create') === 2, 'create count is 2');

echo "\n";

// Test 2: Track read operations
echo "Test 2: Track read operations\n";

$logger2 = new InitializationLogger();
$checker2 = new FileSafetyChecker($logger2);

$checker2->trackOperation('read', 'channels/0/broadcasts/doctrine_001.md');
$checker2->trackOperation('read', 'docs/status/old_file.md');

$operations2 = $checker2->getOperations();
test_assert(count($operations2) === 2, 'tracked 2 read operations');
test_assert($checker2->getOperationCount('read') === 2, 'read count is 2');
test_assert($checker2->getOperationCount('delete') === 0, 'delete count is 0');

echo "\n";

// Test 3: Verify no deletes with only safe operations
echo "Test 3: Verify no deletes with only safe operations\n";

$logger3 = new InitializationLogger();
$checker3 = new FileSafetyChecker($logger3);

$checker3->trackOperation('create', 'file1.md');
$checker3->trackOperation('read', 'file2.md');
$checker3->trackOperation('create', 'file3.md');

$noDeletes = $checker3->verifyNoDeletes();
test_assert($noDeletes === true, 'verifyNoDeletes returns true with no deletes');
test_assert(count($checker3->getDeleteOperations()) === 0, 'getDeleteOperations returns empty array');

echo "\n";

// Test 4: Detect delete operations
echo "Test 4: Detect delete operations\n";

$logger4 = new InitializationLogger();
$checker4 = new FileSafetyChecker($logger4);

$checker4->trackOperation('create', 'file1.md');
$checker4->trackOperation('delete', 'file2.md');
$checker4->trackOperation('read', 'file3.md');

$noDeletes4 = $checker4->verifyNoDeletes();
test_assert($noDeletes4 === false, 'verifyNoDeletes returns false when deletes detected');
test_assert($checker4->getOperationCount('delete') === 1, 'delete count is 1');

$deleteOps = $checker4->getDeleteOperations();
test_assert(count($deleteOps) === 1, 'getDeleteOperations returns 1 delete');
test_assert($deleteOps[0]['file_path'] === 'file2.md', 'delete operation file path is correct');

echo "\n";

// Test 5: Verify safe operations only
echo "Test 5: Verify safe operations only\n";

$logger5 = new InitializationLogger();
$checker5 = new FileSafetyChecker($logger5);

$checker5->trackOperation('create', 'file1.md');
$checker5->trackOperation('read', 'file2.md');

$safeOnly = $checker5->verifySafeOperationsOnly();
test_assert($safeOnly === true, 'verifySafeOperationsOnly returns true with only create and read');

echo "\n";

// Test 6: Detect unsafe operations (update or delete)
echo "Test 6: Detect unsafe operations (update or delete)\n";

$logger6 = new InitializationLogger();
$checker6 = new FileSafetyChecker($logger6);

$checker6->trackOperation('create', 'file1.md');
$checker6->trackOperation('update', 'file2.md');

$safeOnly6 = $checker6->verifySafeOperationsOnly();
test_assert($safeOnly6 === false, 'verifySafeOperationsOnly returns false with update operation');

echo "\n";

// Test 7: Get operation counts
echo "Test 7: Get operation counts\n";

$logger7 = new InitializationLogger();
$checker7 = new FileSafetyChecker($logger7);

$checker7->trackOperation('create', 'file1.md');
$checker7->trackOperation('create', 'file2.md');
$checker7->trackOperation('read', 'file3.md');
$checker7->trackOperation('update', 'file4.md');
$checker7->trackOperation('delete', 'file5.md');

$counts = $checker7->getOperationCounts();
test_assert($counts['create'] === 2, 'create count is 2');
test_assert($counts['read'] === 1, 'read count is 1');
test_assert($counts['update'] === 1, 'update count is 1');
test_assert($counts['delete'] === 1, 'delete count is 1');

echo "\n";

// Test 8: Reset operations
echo "Test 8: Reset operations\n";

$logger8 = new InitializationLogger();
$checker8 = new FileSafetyChecker($logger8);

$checker8->trackOperation('create', 'file1.md');
$checker8->trackOperation('read', 'file2.md');

test_assert(count($checker8->getOperations()) === 2, 'tracked 2 operations before reset');

$checker8->reset();

test_assert(count($checker8->getOperations()) === 0, 'operations cleared after reset');
test_assert($checker8->getOperationCount('create') === 0, 'create count is 0 after reset');
test_assert($checker8->getOperationCount('read') === 0, 'read count is 0 after reset');

echo "\n";

// Test 9: Invalid operation type
echo "Test 9: Invalid operation type\n";

$logger9 = new InitializationLogger();
$checker9 = new FileSafetyChecker($logger9);

$checker9->trackOperation('invalid', 'file1.md');
$checker9->trackOperation('create', 'file2.md');

test_assert(count($checker9->getOperations()) === 1, 'invalid operation not tracked');
test_assert($checker9->getOperationCount('create') === 1, 'valid operation tracked');

echo "\n";

// Test 10: Get summary
echo "Test 10: Get summary\n";

$logger10 = new InitializationLogger();
$checker10 = new FileSafetyChecker($logger10);

$checker10->trackOperation('create', 'file1.md');
$checker10->trackOperation('read', 'file2.md');

$summary = $checker10->getSummary();
test_assert(is_string($summary), 'getSummary returns string');
test_assert(strpos($summary, 'Total operations tracked: 2') !== false, 'summary contains total count');
test_assert(strpos($summary, 'No delete operations detected') !== false, 'summary confirms no deletes');

echo "\n";

// Print summary
echo "=== Test Summary ===\n";
echo "Passed: {$tests_passed}\n";
echo "Failed: {$tests_failed}\n";

if ($tests_failed === 0) {
    echo "\n✓ All tests passed!\n";
    exit(0);
} else {
    echo "\n✗ Some tests failed.\n";
    exit(1);
}
