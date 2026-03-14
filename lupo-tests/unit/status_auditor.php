<?php
/**
 * Unit tests for StatusAuditor
 * 
 * Tests status directory auditing functionality including:
 * - Scanning empty status directory
 * - Auditing files with valid FLIP headers
 * - Auditing files without FLIP headers
 * - Error handling for unreadable files
 * - Disposition counting
 * - File classification (retain/archive/deprecate)
 * 
 * Run from repo root: php tests/unit/status_auditor.php
 * PHP 5.3-compatible; no frameworks. Exit 0 = PASS, non-zero = FAIL.
 * 
 * @package Lupopedia\Tests\Unit
 * @since 4.0.44
 */

$repo_root = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}

require_once $repo_root . '/app/Services/Initialization/InitializationException.php';
require_once $repo_root . '/app/Services/Initialization/StatusAuditException.php';
require_once $repo_root . '/app/Services/Initialization/Interfaces/FLIPHeaderParserInterface.php';
require_once $repo_root . '/app/Services/Initialization/Interfaces/VersionClassifierInterface.php';
require_once $repo_root . '/app/Services/Initialization/Interfaces/StatusAuditorInterface.php';
require_once $repo_root . '/app/Services/Initialization/Interfaces/InitializationLoggerInterface.php';
require_once $repo_root . '/app/Services/Initialization/FLIPHeaderParser.php';
require_once $repo_root . '/app/Services/Initialization/VersionClassifier.php';
require_once $repo_root . '/app/Services/Initialization/StatusAuditor.php';

/**
 * Mock logger for testing
 */
class MockLogger implements InitializationLoggerInterface
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

/**
 * Test runner
 */
function runTests()
{
    $passed = 0;
    $failed = 0;
    $tests = array(
        'testScanNonExistentDirectory',
        'testAuditFileWithFLIPHeader',
        'testAuditFileWithoutFLIPHeader',
        'testAuditFileRetainDisposition',
        'testAuditFileArchiveDisposition',
        'testAuditFileDeprecateDisposition',
        'testAuditFileNoVersion',
        'testGetDispositionCounts',
        'testAuditNonExistentFile',
        'testEnrichWithMetadata',
        'testScanEmptyDirectory',
        'testMultipleFileAudit'
    );
    
    foreach ($tests as $test) {
        echo "Running {$test}... ";
        try {
            call_user_func($test);
            echo "PASS\n";
            $passed++;
        } catch (Exception $e) {
            echo "FAIL: " . $e->getMessage() . "\n";
            $failed++;
        }
    }
    
    echo "\n";
    echo "Tests passed: {$passed}\n";
    echo "Tests failed: {$failed}\n";
    
    return $failed === 0 ? 0 : 1;
}

/**
 * Test scanning non-existent directory throws exception
 */
function testScanNonExistentDirectory()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $logger = new MockLogger();
    $auditor = new StatusAuditor($parser, $classifier, $logger);
    
    $exceptionThrown = false;
    try {
        $auditor->scanStatusDirectory('/nonexistent/directory');
    } catch (StatusAuditException $e) {
        $exceptionThrown = true;
        assert(strpos($e->getMessage(), 'does not exist') !== false, 'Exception should mention directory does not exist');
    }
    
    assert($exceptionThrown, 'Should throw StatusAuditException for non-existent directory');
}

/**
 * Test auditing file with valid FLIP header
 */
function testAuditFileWithFLIPHeader()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $logger = new MockLogger();
    $auditor = new StatusAuditor($parser, $classifier, $logger);
    
    // Create temporary test file
    $tempFile = sys_get_temp_dir() . '/test_status_' . uniqid() . '.md';
    $content = <<<'EOD'
---
system_version: "4.0.44"
actor_id: 1001
created_ymdhis: 20260224153045
---

# Test Status File

This is a test status file for version 4.0.44.
EOD;
    file_put_contents($tempFile, $content);
    
    try {
        $result = $auditor->auditFile($tempFile);
        
        assert($result['version'] === '4.0.44', 'Should extract version 4.0.44');
        assert($result['disposition'] === 'retain', 'Version 4.0.44 should be retain');
        assert($result['filename'] === basename($tempFile), 'Should include filename');
        assert($result['file_path'] === $tempFile, 'Should include file path');
        assert(!empty($result['rationale']), 'Should include rationale');
        assert($result['actor_id'] === 1001, 'Should extract actor_id');
        assert($result['created_ymdhis'] === 20260224153045, 'Should extract created_ymdhis');
    } finally {
        unlink($tempFile);
    }
}

/**
 * Test auditing file without FLIP header
 */
function testAuditFileWithoutFLIPHeader()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $logger = new MockLogger();
    $auditor = new StatusAuditor($parser, $classifier, $logger);
    
    // Create temporary test file without FLIP header
    $tempFile = sys_get_temp_dir() . '/test_status_' . uniqid() . '.md';
    $content = "This file was created for version 4.0.38 of the system.";
    file_put_contents($tempFile, $content);
    
    try {
        $result = $auditor->auditFile($tempFile);
        
        assert($result['version'] === '4.0.38', 'Should extract version from content');
        assert($result['disposition'] === 'archive', 'Version 4.0.38 should be archive');
        assert($result['actor_id'] === null, 'Should have null actor_id without FLIP header');
    } finally {
        unlink($tempFile);
    }
}

/**
 * Test retain disposition (4.0.42+)
 */
function testAuditFileRetainDisposition()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $logger = new MockLogger();
    $auditor = new StatusAuditor($parser, $classifier, $logger);
    
    $tempFile = sys_get_temp_dir() . '/test_status_' . uniqid() . '.md';
    $content = "Status report for version 4.0.43";
    file_put_contents($tempFile, $content);
    
    try {
        $result = $auditor->auditFile($tempFile);
        
        assert($result['version'] === '4.0.43', 'Should extract version 4.0.43');
        assert($result['disposition'] === 'retain', 'Version 4.0.43 should be retain');
        assert(strpos($result['rationale'], 'current') !== false, 'Rationale should mention current');
    } finally {
        unlink($tempFile);
    }
}

/**
 * Test archive disposition (4.0.35-4.0.41)
 */
function testAuditFileArchiveDisposition()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $logger = new MockLogger();
    $auditor = new StatusAuditor($parser, $classifier, $logger);
    
    $tempFile = sys_get_temp_dir() . '/test_status_' . uniqid() . '.md';
    $content = "Status report for version 4.0.40";
    file_put_contents($tempFile, $content);
    
    try {
        $result = $auditor->auditFile($tempFile);
        
        assert($result['version'] === '4.0.40', 'Should extract version 4.0.40');
        assert($result['disposition'] === 'archive', 'Version 4.0.40 should be archive');
        assert(strpos($result['rationale'], 'recent') !== false, 'Rationale should mention recent');
    } finally {
        unlink($tempFile);
    }
}

/**
 * Test deprecate disposition (≤4.0.34)
 */
function testAuditFileDeprecateDisposition()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $logger = new MockLogger();
    $auditor = new StatusAuditor($parser, $classifier, $logger);
    
    $tempFile = sys_get_temp_dir() . '/test_status_' . uniqid() . '.md';
    $content = "Status report for version 4.0.30";
    file_put_contents($tempFile, $content);
    
    try {
        $result = $auditor->auditFile($tempFile);
        
        assert($result['version'] === '4.0.30', 'Should extract version 4.0.30');
        assert($result['disposition'] === 'deprecate', 'Version 4.0.30 should be deprecate');
        assert(strpos($result['rationale'], 'legacy') !== false, 'Rationale should mention legacy');
    } finally {
        unlink($tempFile);
    }
}

/**
 * Test file with no version defaults to retain
 */
function testAuditFileNoVersion()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $logger = new MockLogger();
    $auditor = new StatusAuditor($parser, $classifier, $logger);
    
    $tempFile = sys_get_temp_dir() . '/test_status_' . uniqid() . '.md';
    $content = "Status report without version information";
    file_put_contents($tempFile, $content);
    
    try {
        $result = $auditor->auditFile($tempFile);
        
        assert($result['version'] === null, 'Should have null version');
        assert($result['disposition'] === 'retain', 'No version should default to retain');
        assert(strpos($result['rationale'], 'No version') !== false, 'Rationale should mention no version');
    } finally {
        unlink($tempFile);
    }
}

/**
 * Test disposition counting
 */
function testGetDispositionCounts()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $logger = new MockLogger();
    $auditor = new StatusAuditor($parser, $classifier, $logger);
    
    // Create temporary directory with test files
    $tempDir = sys_get_temp_dir() . '/test_status_dir_' . uniqid();
    mkdir($tempDir);
    
    try {
        // Create files with different dispositions
        file_put_contents($tempDir . '/retain1.md', 'Version 4.0.44');
        file_put_contents($tempDir . '/retain2.md', 'Version 4.0.43');
        file_put_contents($tempDir . '/archive1.md', 'Version 4.0.38');
        file_put_contents($tempDir . '/deprecate1.md', 'Version 4.0.30');
        file_put_contents($tempDir . '/noversion.md', 'No version here');
        
        $auditor->scanStatusDirectory($tempDir);
        $counts = $auditor->getDispositionCounts();
        
        assert($counts['retain'] === 3, 'Should have 3 retain files (2 with version + 1 without)');
        assert($counts['archive'] === 1, 'Should have 1 archive file');
        assert($counts['deprecate'] === 1, 'Should have 1 deprecate file');
    } finally {
        // Clean up
        array_map('unlink', glob($tempDir . '/*'));
        rmdir($tempDir);
    }
}

/**
 * Test auditing non-existent file throws exception
 */
function testAuditNonExistentFile()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $logger = new MockLogger();
    $auditor = new StatusAuditor($parser, $classifier, $logger);
    
    $exceptionThrown = false;
    try {
        $auditor->auditFile('/nonexistent/file.md');
    } catch (StatusAuditException $e) {
        $exceptionThrown = true;
        assert(strpos($e->getMessage(), 'does not exist') !== false, 'Exception should mention file does not exist');
    }
    
    assert($exceptionThrown, 'Should throw StatusAuditException for non-existent file');
}

/**
 * Test metadata enrichment from FLIP header
 */
function testEnrichWithMetadata()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $logger = new MockLogger();
    $auditor = new StatusAuditor($parser, $classifier, $logger);
    
    $tempFile = sys_get_temp_dir() . '/test_status_' . uniqid() . '.md';
    $content = <<<'EOD'
---
wolfie.headers: {
  system_version: "4.0.44",
  actor_id: 1002,
  created_ymdhis: 20260224120000,
  last_modified_utc: 20260224150000
}
---

# Test File
EOD;
    file_put_contents($tempFile, $content);
    
    try {
        $result = $auditor->auditFile($tempFile);
        
        assert($result['actor_id'] === 1002, 'Should extract actor_id from nested header');
        assert($result['created_ymdhis'] === 20260224120000, 'Should extract created_ymdhis');
        assert($result['last_modified_utc'] === 20260224150000, 'Should extract last_modified_utc');
    } finally {
        unlink($tempFile);
    }
}

/**
 * Test scanning empty directory
 */
function testScanEmptyDirectory()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $logger = new MockLogger();
    $auditor = new StatusAuditor($parser, $classifier, $logger);
    
    $tempDir = sys_get_temp_dir() . '/test_empty_dir_' . uniqid();
    mkdir($tempDir);
    
    try {
        $auditor->scanStatusDirectory($tempDir);
        $results = $auditor->getAuditResults();
        $counts = $auditor->getDispositionCounts();
        
        assert(count($results) === 0, 'Should have no results for empty directory');
        assert($counts['retain'] === 0, 'Should have 0 retain count');
        assert($counts['archive'] === 0, 'Should have 0 archive count');
        assert($counts['deprecate'] === 0, 'Should have 0 deprecate count');
    } finally {
        rmdir($tempDir);
    }
}

/**
 * Test auditing multiple files
 */
function testMultipleFileAudit()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $logger = new MockLogger();
    $auditor = new StatusAuditor($parser, $classifier, $logger);
    
    $tempDir = sys_get_temp_dir() . '/test_multi_dir_' . uniqid();
    mkdir($tempDir);
    
    try {
        // Create multiple test files
        file_put_contents($tempDir . '/file1.md', 'Version 4.0.44');
        file_put_contents($tempDir . '/file2.log', 'Version 4.0.38');
        file_put_contents($tempDir . '/file3.md', 'Version 4.0.30');
        file_put_contents($tempDir . '/file4.txt', 'Should be ignored'); // Wrong extension
        
        $auditor->scanStatusDirectory($tempDir);
        $results = $auditor->getAuditResults();
        
        assert(count($results) === 3, 'Should audit 3 files (.md and .log only)');
        
        // Verify all files were processed
        $filenames = array_map(function($r) { return $r['filename']; }, $results);
        assert(in_array('file1.md', $filenames), 'Should include file1.md');
        assert(in_array('file2.log', $filenames), 'Should include file2.log');
        assert(in_array('file3.md', $filenames), 'Should include file3.md');
        assert(!in_array('file4.txt', $filenames), 'Should not include file4.txt');
    } finally {
        // Clean up
        array_map('unlink', glob($tempDir . '/*'));
        rmdir($tempDir);
    }
}

// Run tests
exit(runTests());
