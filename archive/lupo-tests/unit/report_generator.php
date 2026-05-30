<?php
/**
 * Unit tests for ReportGenerator
 * 
 * Tests the ReportGenerator class to ensure it creates valid Markdown reports
 * with all required sections.
 * 
 * Usage: php tests/unit/report_generator.php
 */

// Set up environment
define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
define('LUPOPEDIA_ABSPATH', LUPOPEDIA_PATH . '/');

// Load required files
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/Interfaces/TimestampHelperInterface.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/Interfaces/InitializationLoggerInterface.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/Interfaces/ReportGeneratorInterface.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/InitializationException.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/TimestampHelper.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/ReportGenerationException.php';
require_once LUPOPEDIA_PATH . '/lupo-app/Services/Initialization/ReportGenerator.php';

// Simple logger implementation for testing
class TestLogger implements InitializationLoggerInterface
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

// Test helper functions
function assert_true($condition, $message)
{
    if (!$condition) {
        echo "FAIL: {$message}\n";
        return false;
    }
    echo "PASS: {$message}\n";
    return true;
}

function assert_contains($haystack, $needle, $message)
{
    if (strpos($haystack, $needle) === false) {
        echo "FAIL: {$message}\n";
        echo "  Expected to find: {$needle}\n";
        return false;
    }
    echo "PASS: {$message}\n";
    return true;
}

function assert_file_exists($path, $message)
{
    if (!file_exists($path)) {
        echo "FAIL: {$message}\n";
        echo "  File not found: {$path}\n";
        return false;
    }
    echo "PASS: {$message}\n";
    return true;
}

// Test cases
echo "=== ReportGenerator Unit Tests ===\n\n";

$passCount = 0;
$failCount = 0;

// Test 1: Constructor and basic instantiation
echo "Test 1: Constructor and basic instantiation\n";
try {
    $timestampHelper = new TimestampHelper();
    $logger = new TestLogger();
    $generator = new ReportGenerator($timestampHelper, $logger);
    
    if (assert_true($generator instanceof ReportGeneratorInterface, "ReportGenerator implements interface")) {
        $passCount++;
    } else {
        $failCount++;
    }
} catch (Exception $e) {
    echo "FAIL: Constructor threw exception: " . $e->getMessage() . "\n";
    $failCount++;
}
echo "\n";

// Test 2: Generate report with empty audit results
echo "Test 2: Generate report with empty audit results\n";
try {
    $timestampHelper = new TimestampHelper();
    $logger = new TestLogger();
    $generator = new ReportGenerator($timestampHelper, $logger);
    
    $auditResults = array();
    $dispositionCounts = array(
        'retain' => 0,
        'archive' => 0,
        'deprecate' => 0
    );
    
    $outputPath = LUPOPEDIA_PATH . '/tests/unit/test_output/empty_report.md';
    
    // Ensure output directory exists
    $outputDir = dirname($outputPath);
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
    }
    
    $result = $generator->generateAuditReport($auditResults, $dispositionCounts, $outputPath);
    
    if (assert_file_exists($outputPath, "Report file created")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    $content = file_get_contents($outputPath);
    
    if (assert_contains($content, 'flip.header:', "Report contains FLIP header")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    if (assert_contains($content, 'actor_id: 1001', "FLIP header contains actor_id 1001")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    if (assert_contains($content, 'system_version: "4.0.44"', "FLIP header contains system_version 4.0.44")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    if (assert_contains($content, '## Executive Summary', "Report contains executive summary")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    if (assert_contains($content, '## File Disposition Table', "Report contains disposition table")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    if (assert_contains($content, '## Recommendations', "Report contains recommendations")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    if (assert_contains($content, '## Risk Assessment', "Report contains risk assessment")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    // Clean up
    @unlink($outputPath);
    
} catch (Exception $e) {
    echo "FAIL: Report generation threw exception: " . $e->getMessage() . "\n";
    $failCount++;
}
echo "\n";

// Test 3: Generate report with mixed dispositions
echo "Test 3: Generate report with mixed dispositions\n";
try {
    $timestampHelper = new TimestampHelper();
    $logger = new TestLogger();
    $generator = new ReportGenerator($timestampHelper, $logger);
    
    $auditResults = array(
        array(
            'filename' => 'status_4_0_42.md',
            'file_path' => 'docs/status/status_4_0_42.md',
            'version' => '4.0.42',
            'disposition' => 'retain',
            'rationale' => 'Version 4.0.42 or later - relevant for 4.0.44'
        ),
        array(
            'filename' => 'status_4_0_38.md',
            'file_path' => 'docs/status/status_4_0_38.md',
            'version' => '4.0.38',
            'disposition' => 'archive',
            'rationale' => 'Version 4.0.35-4.0.41 - historical reference'
        ),
        array(
            'filename' => 'status_4_0_30.md',
            'file_path' => 'docs/status/status_4_0_30.md',
            'version' => '4.0.30',
            'disposition' => 'deprecate',
            'rationale' => 'Version 4.0.34 or earlier - obsolete'
        ),
        array(
            'filename' => 'unknown_file.log',
            'file_path' => 'docs/status/unknown_file.log',
            'version' => null,
            'disposition' => 'retain',
            'rationale' => 'No version metadata - defaulting to retain'
        )
    );
    
    $dispositionCounts = array(
        'retain' => 2,
        'archive' => 1,
        'deprecate' => 1
    );
    
    $outputPath = LUPOPEDIA_PATH . '/tests/unit/test_output/mixed_report.md';
    
    $result = $generator->generateAuditReport($auditResults, $dispositionCounts, $outputPath);
    
    if (assert_file_exists($outputPath, "Report file created")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    $content = file_get_contents($outputPath);
    
    if (assert_contains($content, '**Total Files Scanned:** 4', "Report shows correct file count")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    if (assert_contains($content, '**Retain:** 2 files', "Report shows correct retain count")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    if (assert_contains($content, '**Archive:** 1 files', "Report shows correct archive count")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    if (assert_contains($content, '**Deprecate:** 1 files', "Report shows correct deprecate count")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    if (assert_contains($content, 'status_4_0_42.md', "Report includes retain file")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    if (assert_contains($content, 'status_4_0_38.md', "Report includes archive file")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    if (assert_contains($content, 'status_4_0_30.md', "Report includes deprecate file")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    if (assert_contains($content, 'unknown_file.log', "Report includes file without version")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    // Verify table structure
    if (assert_contains($content, '| Filename | Version | Disposition | Rationale |', "Report has table header")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    // Clean up
    @unlink($outputPath);
    
} catch (Exception $e) {
    echo "FAIL: Report generation threw exception: " . $e->getMessage() . "\n";
    $failCount++;
}
echo "\n";

// Test 4: Verify Markdown escaping
echo "Test 4: Verify Markdown escaping\n";
try {
    $timestampHelper = new TimestampHelper();
    $logger = new TestLogger();
    $generator = new ReportGenerator($timestampHelper, $logger);
    
    $auditResults = array(
        array(
            'filename' => 'file|with|pipes.md',
            'file_path' => 'docs/status/file|with|pipes.md',
            'version' => '4.0.42',
            'disposition' => 'retain',
            'rationale' => 'Test | escaping | in | table'
        )
    );
    
    $dispositionCounts = array(
        'retain' => 1,
        'archive' => 0,
        'deprecate' => 0
    );
    
    $outputPath = LUPOPEDIA_PATH . '/tests/unit/test_output/escape_report.md';
    
    $result = $generator->generateAuditReport($auditResults, $dispositionCounts, $outputPath);
    
    $content = file_get_contents($outputPath);
    
    if (assert_contains($content, '\\|', "Report escapes pipe characters")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    // Clean up
    @unlink($outputPath);
    
} catch (Exception $e) {
    echo "FAIL: Report generation threw exception: " . $e->getMessage() . "\n";
    $failCount++;
}
echo "\n";

// Test 5: Verify timestamp format
echo "Test 5: Verify timestamp format\n";
try {
    $timestampHelper = new TimestampHelper();
    $logger = new TestLogger();
    $generator = new ReportGenerator($timestampHelper, $logger);
    
    $auditResults = array();
    $dispositionCounts = array('retain' => 0, 'archive' => 0, 'deprecate' => 0);
    
    $outputPath = LUPOPEDIA_PATH . '/tests/unit/test_output/timestamp_report.md';
    
    $result = $generator->generateAuditReport($auditResults, $dispositionCounts, $outputPath);
    
    $content = file_get_contents($outputPath);
    
    // Check for YYYYMMDDHHMMSS format in FLIP header
    if (preg_match('/created_ymdhis: (\d{14})/', $content, $matches)) {
        if (assert_true(strlen($matches[1]) === 14, "Timestamp is 14 digits")) {
            $passCount++;
        } else {
            $failCount++;
        }
    } else {
        echo "FAIL: No timestamp found in FLIP header\n";
        $failCount++;
    }
    
    // Check for UTC display format
    if (assert_contains($content, 'UTC', "Report includes UTC timezone indicator")) {
        $passCount++;
    } else {
        $failCount++;
    }
    
    // Clean up
    @unlink($outputPath);
    
} catch (Exception $e) {
    echo "FAIL: Report generation threw exception: " . $e->getMessage() . "\n";
    $failCount++;
}
echo "\n";

// Summary
echo "=== Test Summary ===\n";
echo "PASSED: {$passCount}\n";
echo "FAILED: {$failCount}\n";

if ($failCount === 0) {
    echo "\nAll tests passed!\n";
    exit(0);
} else {
    echo "\nSome tests failed.\n";
    exit(1);
}
