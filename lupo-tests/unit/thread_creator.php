<?php
/**
 * Unit tests for ThreadCreator class
 * 
 * Tests thread creation, metadata generation, and error handling.
 * 
 * Run: php tests/unit/thread_creator.php
 */

// Set up test environment
define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
define('LUPOPEDIA_ABSPATH', LUPOPEDIA_PATH . '/');

// Load required classes
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/InitializationException.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/ThreadCreationException.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/Interfaces/TimestampHelperInterface.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/Interfaces/ThreadCreatorInterface.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/TimestampHelper.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/ThreadCreator.php';

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
 * Clean up test thread directory
 */
function cleanup_test_thread($basePath, $threadId)
{
    $threadPath = $basePath . '/channels/42/threads/' . $threadId;
    
    if (is_dir($threadPath)) {
        // Remove thread.json if exists
        $jsonPath = $threadPath . '/thread.json';
        if (file_exists($jsonPath)) {
            @unlink($jsonPath);
        }
        
        // Remove directory
        @rmdir($threadPath);
    }
}

echo "Running ThreadCreator unit tests...\n\n";

// Test 1: Create new thread successfully
echo "Test 1: Create new thread successfully\n";
$timestampHelper = new TimestampHelper();
$basePath = LUPOPEDIA_PATH;
$creator = new ThreadCreator($timestampHelper, $basePath);

$threadId = 'TEST_THREAD_' . time();
$title = 'Test Thread Title';
$actorId = 1001;
$channelId = 42;

// Clean up before test
cleanup_test_thread($basePath, $threadId);

try {
    $metadata = $creator->createThread($threadId, $title, $actorId, $channelId);
    
    test_assert(is_array($metadata), 'createThread returns array');
    test_assert($metadata['thread_id'] === $threadId, 'thread_id is set correctly');
    test_assert($metadata['title'] === $title, 'title is set correctly');
    test_assert($metadata['type'] === 'development', 'type is set to "development"');
    test_assert($metadata['priority'] === 'high', 'priority is set to "high"');
    test_assert($metadata['visibility'] === 'system', 'visibility is set to "system"');
    test_assert($metadata['created_by_actor_id'] === $actorId, 'created_by_actor_id is set correctly');
    test_assert($metadata['channel_id'] === $channelId, 'channel_id is set correctly');
    test_assert(isset($metadata['created_ymdhis']), 'created_ymdhis is set');
    test_assert(preg_match('/^\d{14}$/', $metadata['created_ymdhis']), 'created_ymdhis matches YYYYMMDDHHMMSS format');
    
    // Verify directory was created
    $threadPath = $basePath . '/channels/42/threads/' . $threadId;
    test_assert(is_dir($threadPath), 'thread directory was created');
    
    // Verify thread.json was created
    $jsonPath = $threadPath . '/thread.json';
    test_assert(file_exists($jsonPath), 'thread.json file was created');
    
    // Verify thread.json content
    $jsonContent = file_get_contents($jsonPath);
    $parsedJson = json_decode($jsonContent, true);
    test_assert($parsedJson !== null, 'thread.json contains valid JSON');
    test_assert($parsedJson['thread_id'] === $threadId, 'thread.json contains correct thread_id');
    
} catch (Exception $e) {
    test_assert(false, 'createThread should not throw exception: ' . $e->getMessage());
}

echo "\n";

// Test 2: Handle existing thread directory
echo "Test 2: Handle existing thread directory\n";

try {
    // Try to create the same thread again
    $creator->createThread($threadId, $title, $actorId, $channelId);
    test_assert(false, 'createThread should throw exception for existing thread');
} catch (ThreadCreationException $e) {
    test_assert(true, 'createThread throws ThreadCreationException for existing thread');
    test_assert(strpos($e->getMessage(), 'already exists') !== false, 'exception message mentions "already exists"');
}

echo "\n";

// Test 3: threadExists method
echo "Test 3: threadExists method\n";

test_assert($creator->threadExists($threadId) === true, 'threadExists returns true for existing thread');
test_assert($creator->threadExists('NONEXISTENT_THREAD') === false, 'threadExists returns false for non-existent thread');

echo "\n";

// Test 4: Verify all required fields are present
echo "Test 4: Verify all required fields are present\n";

$requiredFields = array(
    'thread_id',
    'title',
    'type',
    'priority',
    'visibility',
    'created_ymdhis',
    'created_by_actor_id',
    'channel_id'
);

$threadPath = $basePath . '/channels/42/threads/' . $threadId;
$jsonPath = $threadPath . '/thread.json';
$jsonContent = file_get_contents($jsonPath);
$parsedJson = json_decode($jsonContent, true);

foreach ($requiredFields as $field) {
    test_assert(isset($parsedJson[$field]), "thread.json contains required field: $field");
}

echo "\n";

// Clean up after tests
cleanup_test_thread($basePath, $threadId);

// Summary
echo "=====================================\n";
echo "Tests passed: $tests_passed\n";
echo "Tests failed: $tests_failed\n";
echo "=====================================\n";

if ($tests_failed > 0) {
    exit(1);
}

exit(0);
