<?php
/**
 * Unit tests for LogWriter class
 * 
 * Tests log generation with various data inputs, FLIP header generation,
 * SHA-256 checksum handling, and Markdown formatting.
 * 
 * Run from repo root: php tests/unit/log_writer.php
 * PHP 5.3-compatible; no frameworks. Exit 0 = PASS, non-zero = FAIL.
 * 
 * @package Lupopedia\Tests\Unit
 * @since 4.0.44
 */

$repo_root = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}

// Load required classes
require_once $repo_root . '/lupo-app/Services/Initialization/InitializationException.php';
require_once $repo_root . '/lupo-app/Services/Initialization/LogWriterException.php';
require_once $repo_root . '/lupo-app/Services/Initialization/Interfaces/TimestampHelperInterface.php';
require_once $repo_root . '/lupo-app/Services/Initialization/Interfaces/InitializationLoggerInterface.php';
require_once $repo_root . '/lupo-app/Services/Initialization/Interfaces/LogWriterInterface.php';
require_once $repo_root . '/lupo-app/Services/Initialization/TimestampHelper.php';
require_once $repo_root . '/lupo-app/Services/Initialization/LogWriter.php';

// Mock logger for testing
class MockInitializationLogger implements InitializationLoggerInterface
{
    private $entries = array();
    
    public function log($level, $message, $context = array())
    {
        $this->entries[] = array(
            'level' => $level,
            'message' => $message,
            'context' => $context
        );
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
        return $this->entries;
    }
    
    public function clear()
    {
        $this->entries = array();
    }
}

// Test runner
class LogWriterTest
{
    private $timestampHelper;
    private $logger;
    private $logWriter;
    private $testOutputDir;
    private $passed = 0;
    private $failed = 0;
    
    public function __construct()
    {
        $this->timestampHelper = new TimestampHelper();
        $this->logger = new MockInitializationLogger();
        $this->logWriter = new LogWriter($this->timestampHelper, $this->logger);
        $this->testOutputDir = LUPOPEDIA_PATH . '/tests/output/log_writer';
        
        // Ensure test output directory exists
        if (!is_dir($this->testOutputDir)) {
            mkdir($this->testOutputDir, 0755, true);
        }
    }
    
    public function run()
    {
        echo "Running LogWriter tests...\n\n";
        
        $this->testLogStructureWithMinimalData();
        $this->testLogStructureWithCompleteData();
        $this->testFLIPHeaderGeneration();
        $this->testSHA256ChecksumGeneration();
        $this->testMarkdownFormatting();
        $this->testInvalidStartTimestamp();
        $this->testInvalidEndTimestamp();
        $this->testEmptyArrays();
        
        echo "\n" . str_repeat("=", 70) . "\n";
        echo "Test Results: {$this->passed} passed, {$this->failed} failed\n";
        echo str_repeat("=", 70) . "\n";
        
        return $this->failed === 0 ? 0 : 1;
    }
    
    private function testLogStructureWithMinimalData()
    {
        echo "Test: Log structure with minimal data\n";
        
        $outputPath = $this->testOutputDir . '/minimal_log.md';
        $startTime = '20260224150000';
        $endTime = '20260224150100';
        
        try {
            $result = $this->logWriter->writeLog(
                $outputPath,
                $startTime,
                $endTime,
                array(), // No channels
                array(), // No threads
                array(), // No doctrines
                array(), // No files
                array(), // No anomalies
                array()  // No checksums
            );
            
            $this->assert($result === $outputPath, "Should return output path");
            $this->assert(file_exists($outputPath), "Log file should exist");
            
            $content = file_get_contents($outputPath);
            $this->assert(strpos($content, '---') === 0, "Should start with FLIP header");
            $this->assert(strpos($content, 'actor_id: 1001') !== false, "Should contain actor_id 1001");
            $this->assert(strpos($content, 'system_version: "4.0.44"') !== false, "Should contain system version");
            $this->assert(strpos($content, 'artifact_kind: "log"') !== false, "Should contain artifact_kind log");
            $this->assert(strpos($content, 'initialization_start_ymdhis: ' . $startTime) !== false, "Should contain start timestamp");
            $this->assert(strpos($content, 'initialization_end_ymdhis: ' . $endTime) !== false, "Should contain end timestamp");
            $this->assert(strpos($content, '# System Initialization Log') !== false, "Should contain title");
            $this->assert(strpos($content, '## Overview') !== false, "Should contain overview section");
            $this->assert(strpos($content, '## Channels Scanned') !== false, "Should contain channels section");
            $this->assert(strpos($content, '## Threads Created') !== false, "Should contain threads section");
            $this->assert(strpos($content, '## Doctrines Loaded') !== false, "Should contain doctrines section");
            $this->assert(strpos($content, '## Status Files Audited') !== false, "Should contain files section");
            $this->assert(strpos($content, '## Anomalies Encountered') !== false, "Should contain anomalies section");
            $this->assert(strpos($content, '## File Checksums') !== false, "Should contain checksums section");
            
            echo "  ✓ Passed\n\n";
        } catch (Exception $e) {
            echo "  ✗ Failed: " . $e->getMessage() . "\n\n";
            $this->failed++;
        }
    }
    
    private function testLogStructureWithCompleteData()
    {
        echo "Test: Log structure with complete data\n";
        
        $outputPath = $this->testOutputDir . '/complete_log.md';
        $startTime = '20260224150000';
        $endTime = '20260224151500';
        
        $channelsScanned = array(
            array(
                'channel_id' => 0,
                'channel_name' => 'System Broadcasts',
                'file_count' => 25,
                'status' => 'Success'
            )
        );
        
        $threadsCreated = array(
            array(
                'thread_id' => 'DEVELOPMENT_CYCLE_4_0_44',
                'title' => 'Crafty Syntax / Lupopedia Development — Version 4.0.44',
                'channel_id' => 42,
                'status' => 'Created'
            )
        );
        
        $doctrinesLoaded = array(
            array(
                'doctrine_number' => 'D001',
                'title' => 'Database Rules',
                'system_version' => '4.0.44',
                'enforcement_scope' => 'global'
            ),
            array(
                'doctrine_number' => 'D002',
                'title' => 'Timestamp Rules',
                'system_version' => '4.0.44',
                'enforcement_scope' => 'global'
            )
        );
        
        $filesAudited = array(
            array(
                'filename' => 'status_report_4_0_43.md',
                'version' => '4.0.43',
                'disposition' => 'retain'
            ),
            array(
                'filename' => 'old_status_4_0_30.md',
                'version' => '4.0.30',
                'disposition' => 'deprecate'
            )
        );
        
        $anomalies = array(
            array(
                'type' => 'Missing FLIP Header',
                'description' => 'File old_status.md lacks FLIP header',
                'severity' => 'Low'
            )
        );
        
        $checksums = array(
            'docs/status/audit_report.md' => 'abc123def456...',
            'channels/42/threads/DEVELOPMENT_CYCLE_4_0_44/thread.json' => 'def789ghi012...'
        );
        
        try {
            $result = $this->logWriter->writeLog(
                $outputPath,
                $startTime,
                $endTime,
                $channelsScanned,
                $threadsCreated,
                $doctrinesLoaded,
                $filesAudited,
                $anomalies,
                $checksums
            );
            
            $this->assert(file_exists($outputPath), "Log file should exist");
            
            $content = file_get_contents($outputPath);
            
            // Check channels
            $this->assert(strpos($content, 'System Broadcasts') !== false, "Should contain channel name");
            $this->assert(strpos($content, '| 0 |') !== false, "Should contain channel ID");
            
            // Check threads
            $this->assert(strpos($content, 'DEVELOPMENT_CYCLE_4_0_44') !== false, "Should contain thread ID");
            $this->assert(strpos($content, 'Crafty Syntax / Lupopedia Development') !== false, "Should contain thread title");
            
            // Check doctrines
            $this->assert(strpos($content, '**Total Doctrines Loaded:** 2') !== false, "Should show doctrine count");
            $this->assert(strpos($content, 'D001') !== false, "Should contain doctrine number");
            $this->assert(strpos($content, 'Database Rules') !== false, "Should contain doctrine title");
            
            // Check files audited
            $this->assert(strpos($content, 'status_report_4_0_43.md') !== false, "Should contain audited filename");
            $this->assert(strpos($content, 'Retain') !== false, "Should contain disposition");
            
            // Check anomalies
            $this->assert(strpos($content, '**Total Anomalies:** 1') !== false, "Should show anomaly count");
            $this->assert(strpos($content, 'Missing FLIP Header') !== false, "Should contain anomaly type");
            
            // Check checksums
            $this->assert(strpos($content, 'abc123def456') !== false, "Should contain checksum");
            $this->assert(strpos($content, 'audit_report.md') !== false, "Should contain checksummed file path");
            
            echo "  ✓ Passed\n\n";
        } catch (Exception $e) {
            echo "  ✗ Failed: " . $e->getMessage() . "\n\n";
            $this->failed++;
        }
    }
    
    private function testFLIPHeaderGeneration()
    {
        echo "Test: FLIP header generation\n";
        
        $outputPath = $this->testOutputDir . '/flip_header_test.md';
        $startTime = '20260224150000';
        $endTime = '20260224150100';
        
        try {
            $this->logWriter->writeLog(
                $outputPath,
                $startTime,
                $endTime,
                array(), array(), array(), array(), array(), array()
            );
            
            $content = file_get_contents($outputPath);
            
            // Extract FLIP header
            $headerEnd = strpos($content, '---', 3);
            $header = substr($content, 0, $headerEnd + 3);
            
            $this->assert(strpos($header, 'flip.header: {') !== false, "Should have flip.header key");
            $this->assert(strpos($header, 'actor_id: 1001') !== false, "Should have actor_id 1001");
            $this->assert(strpos($header, 'system_version: "4.0.44"') !== false, "Should have system_version 4.0.44");
            $this->assert(strpos($header, 'artifact_kind: "log"') !== false, "Should have artifact_kind log");
            $this->assert(strpos($header, 'initialization_start_ymdhis: ' . $startTime) !== false, "Should have start timestamp");
            $this->assert(strpos($header, 'initialization_end_ymdhis: ' . $endTime) !== false, "Should have end timestamp");
            
            echo "  ✓ Passed\n\n";
        } catch (Exception $e) {
            echo "  ✗ Failed: " . $e->getMessage() . "\n\n";
            $this->failed++;
        }
    }
    
    private function testSHA256ChecksumGeneration()
    {
        echo "Test: SHA-256 checksum generation\n";
        
        $outputPath = $this->testOutputDir . '/checksum_test.md';
        $startTime = '20260224150000';
        $endTime = '20260224150100';
        
        $checksums = array(
            'file1.md' => hash('sha256', 'test content 1'),
            'file2.md' => hash('sha256', 'test content 2')
        );
        
        try {
            $this->logWriter->writeLog(
                $outputPath,
                $startTime,
                $endTime,
                array(), array(), array(), array(), array(),
                $checksums
            );
            
            $content = file_get_contents($outputPath);
            
            $this->assert(strpos($content, '## File Checksums (SHA-256)') !== false, "Should have checksums section");
            $this->assert(strpos($content, 'file1.md') !== false, "Should contain first file");
            $this->assert(strpos($content, 'file2.md') !== false, "Should contain second file");
            $this->assert(strpos($content, $checksums['file1.md']) !== false, "Should contain first checksum");
            $this->assert(strpos($content, $checksums['file2.md']) !== false, "Should contain second checksum");
            
            echo "  ✓ Passed\n\n";
        } catch (Exception $e) {
            echo "  ✗ Failed: " . $e->getMessage() . "\n\n";
            $this->failed++;
        }
    }
    
    private function testMarkdownFormatting()
    {
        echo "Test: Markdown formatting\n";
        
        $outputPath = $this->testOutputDir . '/markdown_test.md';
        $startTime = '20260224150000';
        $endTime = '20260224150100';
        
        try {
            $this->logWriter->writeLog(
                $outputPath,
                $startTime,
                $endTime,
                array(), array(), array(), array(), array(), array()
            );
            
            $content = file_get_contents($outputPath);
            
            // Check for valid Markdown structure
            $this->assert(preg_match('/^---\n/', $content) === 1, "Should start with YAML delimiter");
            $this->assert(preg_match('/# System Initialization Log/', $content) === 1, "Should have H1 title");
            $this->assert(preg_match('/## Overview/', $content) === 1, "Should have H2 sections");
            $this->assert(preg_match('/\*\*[^*]+\*\*/', $content) === 1, "Should have bold text");
            
            echo "  ✓ Passed\n\n";
        } catch (Exception $e) {
            echo "  ✗ Failed: " . $e->getMessage() . "\n\n";
            $this->failed++;
        }
    }
    
    private function testInvalidStartTimestamp()
    {
        echo "Test: Invalid start timestamp\n";
        
        $outputPath = $this->testOutputDir . '/invalid_start.md';
        $startTime = 'invalid';
        $endTime = '20260224150100';
        
        try {
            $this->logWriter->writeLog(
                $outputPath,
                $startTime,
                $endTime,
                array(), array(), array(), array(), array(), array()
            );
            
            echo "  ✗ Failed: Should have thrown exception\n\n";
            $this->failed++;
        } catch (LogWriterException $e) {
            $this->assert(strpos($e->getMessage(), 'Invalid start timestamp') !== false, "Should mention invalid start timestamp");
            echo "  ✓ Passed\n\n";
        }
    }
    
    private function testInvalidEndTimestamp()
    {
        echo "Test: Invalid end timestamp\n";
        
        $outputPath = $this->testOutputDir . '/invalid_end.md';
        $startTime = '20260224150000';
        $endTime = 'invalid';
        
        try {
            $this->logWriter->writeLog(
                $outputPath,
                $startTime,
                $endTime,
                array(), array(), array(), array(), array(), array()
            );
            
            echo "  ✗ Failed: Should have thrown exception\n\n";
            $this->failed++;
        } catch (LogWriterException $e) {
            $this->assert(strpos($e->getMessage(), 'Invalid end timestamp') !== false, "Should mention invalid end timestamp");
            echo "  ✓ Passed\n\n";
        }
    }
    
    private function testEmptyArrays()
    {
        echo "Test: Empty arrays\n";
        
        $outputPath = $this->testOutputDir . '/empty_arrays.md';
        $startTime = '20260224150000';
        $endTime = '20260224150100';
        
        try {
            $this->logWriter->writeLog(
                $outputPath,
                $startTime,
                $endTime,
                array(), array(), array(), array(), array(), array()
            );
            
            $content = file_get_contents($outputPath);
            
            $this->assert(strpos($content, '*No channels were scanned') !== false, "Should show no channels message");
            $this->assert(strpos($content, '*No threads were created') !== false, "Should show no threads message");
            $this->assert(strpos($content, '*No doctrines were loaded') !== false, "Should show no doctrines message");
            $this->assert(strpos($content, '*No status files were audited') !== false, "Should show no files message");
            $this->assert(strpos($content, '*No anomalies were encountered') !== false, "Should show no anomalies message");
            $this->assert(strpos($content, '*No checksums were generated') !== false, "Should show no checksums message");
            
            echo "  ✓ Passed\n\n";
        } catch (Exception $e) {
            echo "  ✗ Failed: " . $e->getMessage() . "\n\n";
            $this->failed++;
        }
    }
    
    private function assert($condition, $message)
    {
        if ($condition) {
            $this->passed++;
        } else {
            echo "    Assertion failed: {$message}\n";
            $this->failed++;
        }
    }
}

// Run tests
$test = new LogWriterTest();
$exitCode = $test->run();
exit($exitCode);
