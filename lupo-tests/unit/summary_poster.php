<?php
/**
 * Unit tests for SummaryPoster class
 * 
 * Tests summary message generation, filename pattern, FLIP header,
 * character limit enforcement, and truncation behavior.
 * 
 * Run: php tests/unit/summary_poster.php
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set up paths
$repo_root = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}

// Load required files
require_once $repo_root . '/app/Services/Initialization/Interfaces/TimestampHelperInterface.php';
require_once $repo_root . '/app/Services/Initialization/Interfaces/InitializationLoggerInterface.php';
require_once $repo_root . '/app/Services/Initialization/Interfaces/SummaryPosterInterface.php';
require_once $repo_root . '/app/Services/Initialization/TimestampHelper.php';
require_once $repo_root . '/app/Services/Initialization/InitializationException.php';
require_once $repo_root . '/app/Services/Initialization/SummaryPoster.php';

/**
 * Mock logger for testing
 */
class MockLogger implements InitializationLoggerInterface
{
    public $logs = array();
    
    public function log($level, $message, $context = array())
    {
        $this->logs[] = array('level' => $level, 'message' => $message, 'context' => $context);
    }
    
    public function info($message, $context = array())
    {
        $this->log('INFO', $message, $context);
    }
    
    public function warning($message, $context = array())
    {
        $this->log('WARNING', $message, $context);
    }
    
    public function error($message, $context = array())
    {
        $this->log('ERROR', $message, $context);
    }
    
    public function getEntries()
    {
        return $this->logs;
    }
    
    public function clear()
    {
        $this->logs = array();
    }
    
    public function getLogs()
    {
        return $this->logs;
    }
}

/**
 * Test runner
 */
class SummaryPosterTest
{
    private $timestampHelper;
    private $logger;
    private $poster;
    private $testDir;
    private $passed = 0;
    private $failed = 0;
    
    public function __construct()
    {
        $this->timestampHelper = new TimestampHelper();
        $this->logger = new MockLogger();
        $this->poster = new SummaryPoster($this->timestampHelper, $this->logger);
        
        // Create temporary test directory
        $this->testDir = sys_get_temp_dir() . '/lupopedia_test_' . time();
    }
    
    public function run()
    {
        echo "Running SummaryPoster unit tests...\n\n";
        
        $this->testSummaryGenerationUnder1000Characters();
        $this->testSummaryTruncationOver1000Characters();
        $this->testFilenamePatternGeneration();
        $this->testFLIPHeaderGeneration();
        $this->testEmptyRisksAndNextSteps();
        $this->testDirectoryCreation();
        
        $this->cleanup();
        $this->printSummary();
    }
    
    /**
     * Test summary generation under 1000 characters
     */
    private function testSummaryGenerationUnder1000Characters()
    {
        echo "Test: Summary generation under 1000 characters\n";
        
        try {
            $threadPath = $this->testDir . '/thread1';
            
            $messagePath = $this->poster->postSummary(
                $threadPath,
                25,
                array('retain' => 10, 'archive' => 5, 'deprecate' => 3),
                array('No critical risks identified'),
                array('Review audit report', 'Begin development work')
            );
            
            // Verify file was created
            $this->assert(file_exists($messagePath), "Message file should exist");
            
            // Read content
            $content = file_get_contents($messagePath);
            
            // Verify FLIP header is present
            $this->assert(strpos($content, '---') === 0, "Content should start with FLIP header");
            $this->assert(strpos($content, 'actor_id: 1001') !== false, "Should contain actor_id 1001");
            $this->assert(strpos($content, 'channel_id: 42') !== false, "Should contain channel_id 42");
            $this->assert(strpos($content, 'message_type: "post"') !== false, "Should contain message_type post");
            
            // Verify message body content
            $this->assert(strpos($content, '# 4.0.44 Initialization Summary') !== false, "Should contain title");
            $this->assert(strpos($content, '25 doctrines') !== false, "Should mention doctrine count");
            $this->assert(strpos($content, 'Retain:** 10') !== false, "Should mention retain count");
            $this->assert(strpos($content, 'Archive:** 5') !== false, "Should mention archive count");
            $this->assert(strpos($content, 'Deprecate:** 3') !== false, "Should mention deprecate count");
            $this->assert(strpos($content, 'No critical risks identified') !== false, "Should mention risks");
            $this->assert(strpos($content, 'Review audit report') !== false, "Should mention next steps");
            
            // Extract message body (after FLIP header)
            $parts = explode('---', $content);
            $messageBody = isset($parts[2]) ? trim($parts[2]) : '';
            
            // Verify message body is under 1000 characters
            $this->assert(strlen($messageBody) <= 1000, "Message body should be <= 1000 characters (got " . strlen($messageBody) . ")");
            
            // Verify no truncation occurred
            $this->assert(strpos($content, '... (see full report)') === false, "Should not be truncated");
            
            echo "  ✓ PASSED\n\n";
            $this->passed++;
            
        } catch (Exception $e) {
            echo "  ✗ FAILED: " . $e->getMessage() . "\n\n";
            $this->failed++;
        }
    }
    
    /**
     * Test summary truncation over 1000 characters
     */
    private function testSummaryTruncationOver1000Characters()
    {
        echo "Test: Summary truncation over 1000 characters\n";
        
        try {
            $threadPath = $this->testDir . '/thread2';
            
            // Create very long risks and next steps to exceed 1000 characters
            $longRisks = array();
            $longNextSteps = array();
            
            for ($i = 0; $i < 20; $i++) {
                $longRisks[] = "Risk item {$i}: This is a very long risk description that adds significant content to the message body to test truncation behavior when the total message exceeds 1000 characters.";
                $longNextSteps[] = "Next step {$i}: This is a very long next step description that adds significant content to the message body to test truncation behavior when the total message exceeds 1000 characters.";
            }
            
            $messagePath = $this->poster->postSummary(
                $threadPath,
                25,
                array('retain' => 10, 'archive' => 5, 'deprecate' => 3),
                $longRisks,
                $longNextSteps
            );
            
            // Verify file was created
            $this->assert(file_exists($messagePath), "Message file should exist");
            
            // Read content
            $content = file_get_contents($messagePath);
            
            // Extract message body (after FLIP header)
            $parts = explode('---', $content);
            $messageBody = isset($parts[2]) ? trim($parts[2]) : '';
            
            // Verify message body is exactly 1000 characters or less
            $this->assert(strlen($messageBody) <= 1000, "Message body should be <= 1000 characters (got " . strlen($messageBody) . ")");
            
            // Verify truncation suffix is present
            $this->assert(strpos($messageBody, '... (see full report)') !== false, "Should contain truncation suffix");
            
            // Verify logger recorded truncation warning
            $logs = $this->logger->getLogs();
            $foundWarning = false;
            foreach ($logs as $log) {
                if ($log['level'] === 'WARNING' && strpos($log['message'], 'exceeded 1000 characters') !== false) {
                    $foundWarning = true;
                    break;
                }
            }
            $this->assert($foundWarning, "Should log truncation warning");
            
            echo "  ✓ PASSED\n\n";
            $this->passed++;
            
        } catch (Exception $e) {
            echo "  ✗ FAILED: " . $e->getMessage() . "\n\n";
            $this->failed++;
        }
    }
    
    /**
     * Test filename pattern generation
     */
    private function testFilenamePatternGeneration()
    {
        echo "Test: Filename pattern generation\n";
        
        try {
            $threadPath = $this->testDir . '/thread3';
            
            $messagePath = $this->poster->postSummary(
                $threadPath,
                25,
                array('retain' => 10, 'archive' => 5, 'deprecate' => 3),
                array(),
                array()
            );
            
            // Extract filename from path
            $filename = basename($messagePath);
            
            // Verify filename pattern: YYYYMMDDHHMMSS_42_1001_initialization_summary.md
            $pattern = '/^\d{14}_42_1001_initialization_summary\.md$/';
            $this->assert(preg_match($pattern, $filename) === 1, "Filename should match pattern YYYYMMDDHHMMSS_42_1001_initialization_summary.md (got: {$filename})");
            
            echo "  ✓ PASSED\n\n";
            $this->passed++;
            
        } catch (Exception $e) {
            echo "  ✗ FAILED: " . $e->getMessage() . "\n\n";
            $this->failed++;
        }
    }
    
    /**
     * Test FLIP header generation
     */
    private function testFLIPHeaderGeneration()
    {
        echo "Test: FLIP header generation\n";
        
        try {
            $threadPath = $this->testDir . '/thread4';
            
            $messagePath = $this->poster->postSummary(
                $threadPath,
                25,
                array('retain' => 10, 'archive' => 5, 'deprecate' => 3),
                array(),
                array()
            );
            
            // Read content
            $content = file_get_contents($messagePath);
            
            // Verify FLIP header structure
            $this->assert(strpos($content, '---') === 0, "Should start with ---");
            $this->assert(strpos($content, 'flip.header: {') !== false, "Should contain flip.header");
            $this->assert(strpos($content, 'actor_id: 1001') !== false, "Should contain actor_id 1001");
            $this->assert(strpos($content, 'channel_id: 42') !== false, "Should contain channel_id 42");
            $this->assert(strpos($content, 'system_version: "4.0.44"') !== false, "Should contain system_version 4.0.44");
            $this->assert(strpos($content, 'message_type: "post"') !== false, "Should contain message_type post");
            $this->assert(strpos($content, 'visibility: "system"') !== false, "Should contain visibility system");
            $this->assert(strpos($content, 'priority: "high"') !== false, "Should contain priority high");
            
            // Verify created_ymdhis is valid timestamp
            preg_match('/created_ymdhis: (\d{14})/', $content, $matches);
            $this->assert(isset($matches[1]), "Should contain created_ymdhis");
            $this->assert($this->timestampHelper->isValidTimestamp($matches[1]), "created_ymdhis should be valid timestamp");
            
            echo "  ✓ PASSED\n\n";
            $this->passed++;
            
        } catch (Exception $e) {
            echo "  ✗ FAILED: " . $e->getMessage() . "\n\n";
            $this->failed++;
        }
    }
    
    /**
     * Test empty risks and next steps
     */
    private function testEmptyRisksAndNextSteps()
    {
        echo "Test: Empty risks and next steps\n";
        
        try {
            $threadPath = $this->testDir . '/thread5';
            
            $messagePath = $this->poster->postSummary(
                $threadPath,
                25,
                array('retain' => 10, 'archive' => 5, 'deprecate' => 3),
                array(), // Empty risks
                array()  // Empty next steps
            );
            
            // Read content
            $content = file_get_contents($messagePath);
            
            // Verify default messages for empty arrays
            $this->assert(strpos($content, 'No critical risks identified') !== false, "Should show default message for empty risks");
            $this->assert(strpos($content, 'Review audit report and begin development work') !== false, "Should show default message for empty next steps");
            
            echo "  ✓ PASSED\n\n";
            $this->passed++;
            
        } catch (Exception $e) {
            echo "  ✗ FAILED: " . $e->getMessage() . "\n\n";
            $this->failed++;
        }
    }
    
    /**
     * Test directory creation
     */
    private function testDirectoryCreation()
    {
        echo "Test: Directory creation\n";
        
        try {
            $threadPath = $this->testDir . '/nested/deep/thread6';
            
            // Verify directory doesn't exist yet
            $this->assert(!is_dir($threadPath), "Thread directory should not exist yet");
            
            $messagePath = $this->poster->postSummary(
                $threadPath,
                25,
                array('retain' => 10, 'archive' => 5, 'deprecate' => 3),
                array(),
                array()
            );
            
            // Verify directory was created
            $this->assert(is_dir($threadPath), "Thread directory should be created");
            
            // Verify file was created in the directory
            $this->assert(file_exists($messagePath), "Message file should exist in created directory");
            
            echo "  ✓ PASSED\n\n";
            $this->passed++;
            
        } catch (Exception $e) {
            echo "  ✗ FAILED: " . $e->getMessage() . "\n\n";
            $this->failed++;
        }
    }
    
    /**
     * Assert helper
     */
    private function assert($condition, $message)
    {
        if (!$condition) {
            throw new Exception($message);
        }
    }
    
    /**
     * Clean up test files
     */
    private function cleanup()
    {
        if (is_dir($this->testDir)) {
            $this->recursiveDelete($this->testDir);
        }
    }
    
    /**
     * Recursively delete directory
     */
    private function recursiveDelete($dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->recursiveDelete($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }
    
    /**
     * Print test summary
     */
    private function printSummary()
    {
        $total = $this->passed + $this->failed;
        echo "========================================\n";
        echo "Test Summary\n";
        echo "========================================\n";
        echo "Total:  {$total}\n";
        echo "Passed: {$this->passed}\n";
        echo "Failed: {$this->failed}\n";
        echo "========================================\n";
        
        if ($this->failed === 0) {
            echo "✓ All tests passed!\n";
            exit(0);
        } else {
            echo "✗ Some tests failed.\n";
            exit(1);
        }
    }
}

// Run tests
$test = new SummaryPosterTest();
$test->run();
