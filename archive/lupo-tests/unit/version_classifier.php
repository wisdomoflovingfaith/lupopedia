<?php
/**
 * Unit tests for VersionClassifier
 * 
 * Tests version extraction and classification functionality including:
 * - Version extraction from FLIP headers
 * - Version extraction from content
 * - Classification for versions 4.0.42+
 * - Classification for versions 4.0.35-4.0.41
 * - Classification for versions ≤4.0.34
 * - Default classification for missing version
 * - Rationale generation
 * 
 * Run from repo root: php tests/unit/version_classifier.php
 * PHP 5.3-compatible; no frameworks. Exit 0 = PASS, non-zero = FAIL.
 * 
 * @package Lupopedia\Tests\Unit
 * @since 4.0.44
 */

$repo_root = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}

require_once $repo_root . '/lupo-app/Services/Initialization/Interfaces/FLIPHeaderParserInterface.php';
require_once $repo_root . '/lupo-app/Services/Initialization/Interfaces/VersionClassifierInterface.php';
require_once $repo_root . '/lupo-app/Services/Initialization/FLIPHeaderParser.php';
require_once $repo_root . '/lupo-app/Services/Initialization/VersionClassifier.php';

/**
 * Test runner
 */
function runTests()
{
    $passed = 0;
    $failed = 0;
    $tests = array(
        'testExtractVersionFromFLIPHeader',
        'testExtractVersionFromWolfieHeaders',
        'testExtractVersionFromContent',
        'testExtractVersionNoVersion',
        'testClassifyRetain',
        'testClassifyArchive',
        'testClassifyDeprecate',
        'testClassifyNoVersion',
        'testClassifyEdgeCases',
        'testGetRationale',
        'testGetRationaleNoVersion',
        'testVersionNormalization',
        'testInvalidVersionFormat'
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
 * Test extracting version from FLIP header (direct system_version field)
 */
function testExtractVersionFromFLIPHeader()
{
    $content = <<<'EOD'
---
system_version: "4.0.44"
actor_id: 1001
---

# Content here
EOD;
    
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $version = $classifier->extractVersion($content);
    
    assert($version === '4.0.44', 'Should extract version from FLIP header');
}

/**
 * Test extracting version from wolfie.headers block
 */
function testExtractVersionFromWolfieHeaders()
{
    $content = <<<'EOD'
---
wolfie.headers: {
  file_path_from_root: "example.md",
  system_version: "4.0.42",
  actor_id: 1001
}
---

# Content here
EOD;
    
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $version = $classifier->extractVersion($content);
    
    assert($version === '4.0.42', 'Should extract version from wolfie.headers');
}

/**
 * Test extracting version from content (pattern matching)
 */
function testExtractVersionFromContent()
{
    $content = "This file was created for version 4.0.35 of the system.";
    
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $version = $classifier->extractVersion($content);
    
    assert($version === '4.0.35', 'Should extract version from content');
}

/**
 * Test extracting version when no version present
 */
function testExtractVersionNoVersion()
{
    $content = "Just some content without any version information.";
    
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    $version = $classifier->extractVersion($content);
    
    assert($version === null, 'Should return null when no version found');
}

/**
 * Test classification for retain (4.0.42+)
 */
function testClassifyRetain()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    
    assert($classifier->classifyFile('4.0.42') === 'retain', '4.0.42 should be retain');
    assert($classifier->classifyFile('4.0.43') === 'retain', '4.0.43 should be retain');
    assert($classifier->classifyFile('4.0.44') === 'retain', '4.0.44 should be retain');
    assert($classifier->classifyFile('4.0.50') === 'retain', '4.0.50 should be retain');
    assert($classifier->classifyFile('4.1.0') === 'retain', '4.1.0 should be retain');
}

/**
 * Test classification for archive (4.0.35-4.0.41)
 */
function testClassifyArchive()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    
    assert($classifier->classifyFile('4.0.35') === 'archive', '4.0.35 should be archive');
    assert($classifier->classifyFile('4.0.38') === 'archive', '4.0.38 should be archive');
    assert($classifier->classifyFile('4.0.41') === 'archive', '4.0.41 should be archive');
}

/**
 * Test classification for deprecate (≤4.0.34)
 */
function testClassifyDeprecate()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    
    assert($classifier->classifyFile('4.0.34') === 'deprecate', '4.0.34 should be deprecate');
    assert($classifier->classifyFile('4.0.30') === 'deprecate', '4.0.30 should be deprecate');
    assert($classifier->classifyFile('4.0.1') === 'deprecate', '4.0.1 should be deprecate');
    assert($classifier->classifyFile('3.7.5') === 'deprecate', '3.7.5 should be deprecate');
}

/**
 * Test classification for no version (default to retain)
 */
function testClassifyNoVersion()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    
    assert($classifier->classifyFile(null) === 'retain', 'null should default to retain');
    assert($classifier->classifyFile('') === 'retain', 'empty string should default to retain');
}

/**
 * Test classification edge cases (boundary values)
 */
function testClassifyEdgeCases()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    
    // Boundary between deprecate and archive
    assert($classifier->classifyFile('4.0.34') === 'deprecate', '4.0.34 is last deprecate');
    assert($classifier->classifyFile('4.0.35') === 'archive', '4.0.35 is first archive');
    
    // Boundary between archive and retain
    assert($classifier->classifyFile('4.0.41') === 'archive', '4.0.41 is last archive');
    assert($classifier->classifyFile('4.0.42') === 'retain', '4.0.42 is first retain');
}

/**
 * Test rationale generation
 */
function testGetRationale()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    
    $rationale = $classifier->getRationale('4.0.44', 'retain');
    assert(strpos($rationale, '4.0.44') !== false, 'Rationale should include version');
    assert(strpos($rationale, 'current') !== false, 'Rationale should mention current');
    
    $rationale = $classifier->getRationale('4.0.38', 'archive');
    assert(strpos($rationale, '4.0.38') !== false, 'Rationale should include version');
    assert(strpos($rationale, 'recent') !== false, 'Rationale should mention recent');
    
    $rationale = $classifier->getRationale('4.0.30', 'deprecate');
    assert(strpos($rationale, '4.0.30') !== false, 'Rationale should include version');
    assert(strpos($rationale, 'legacy') !== false, 'Rationale should mention legacy');
}

/**
 * Test rationale for no version
 */
function testGetRationaleNoVersion()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    
    $rationale = $classifier->getRationale(null, 'retain');
    assert(strpos($rationale, 'No version') !== false, 'Rationale should mention no version');
    assert(strpos($rationale, 'defaulting') !== false, 'Rationale should mention defaulting');
}

/**
 * Test version normalization (v prefix, whitespace)
 */
function testVersionNormalization()
{
    $content1 = <<<'EOD'
---
system_version: "v4.0.44"
---
EOD;
    
    $content2 = <<<'EOD'
---
system_version: " 4.0.44 "
---
EOD;
    
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    
    $version1 = $classifier->extractVersion($content1);
    assert($version1 === '4.0.44', 'Should normalize v prefix');
    
    $version2 = $classifier->extractVersion($content2);
    assert($version2 === '4.0.44', 'Should normalize whitespace');
}

/**
 * Test invalid version format handling
 */
function testInvalidVersionFormat()
{
    $parser = new FLIPHeaderParser();
    $classifier = new VersionClassifier($parser);
    
    // Invalid formats should default to retain
    assert($classifier->classifyFile('invalid') === 'retain', 'Invalid format should default to retain');
    assert($classifier->classifyFile('4.0') === 'retain', 'Incomplete version should default to retain');
    assert($classifier->classifyFile('4.0.x') === 'retain', 'Non-numeric patch should default to retain');
}

// Run tests
exit(runTests());
