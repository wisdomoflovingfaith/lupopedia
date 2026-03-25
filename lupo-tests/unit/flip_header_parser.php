<?php
/**
 * Unit tests for FLIPHeaderParser
 * 
 * Tests FLIP header parsing functionality including:
 * - Valid FLIP headers with all fields
 * - Missing FLIP headers
 * - Malformed YAML syntax
 * - Partial headers with missing fields
 * - Both inline JSON-like and multi-line YAML formats
 * 
 * Run from repo root: php tests/unit/flip_header_parser.php
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
require_once $repo_root . '/lupo-app/Services/Initialization/FLIPHeaderParser.php';

/**
 * Test runner
 */
function runTests()
{
    $passed = 0;
    $failed = 0;
    $tests = array(
        'testValidInlineHeader',
        'testValidMultilineHeader',
        'testMissingHeader',
        'testMalformedYaml',
        'testPartialHeader',
        'testEmptyContent',
        'testGetField',
        'testGetFieldWithDefault',
        'testHasHeader',
        'testComplexInlineHeader'
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
 * Test valid inline JSON-like header
 */
function testValidInlineHeader()
{
    $content = <<<'EOD'
---
wolfie.headers: {
  file_path_from_root: "example.md",
  system_version: "4.0.44",
  actor_id: 1001,
  channel_id: 42
}
---

# Content here
EOD;
    
    $parser = new FLIPHeaderParser();
    $result = $parser->parse($content);
    
    assert(!empty($result), 'Result should not be empty');
    assert(isset($result['wolfie.headers']), 'Should have wolfie.headers key');
    assert(is_array($result['wolfie.headers']), 'wolfie.headers should be array');
    assert($result['wolfie.headers']['file_path_from_root'] === 'example.md', 'file_path_from_root should match');
    assert($result['wolfie.headers']['system_version'] === '4.0.44', 'system_version should match');
    assert($result['wolfie.headers']['actor_id'] === 1001, 'actor_id should be integer 1001');
    assert($result['wolfie.headers']['channel_id'] === 42, 'channel_id should be integer 42');
}

/**
 * Test valid multi-line YAML header
 */
function testValidMultilineHeader()
{
    $content = <<<'EOD'
---
wolfie.headers:
  file_path_from_root: "example.md"
  system_version: "4.0.44"
  actor_id: 1001
  channel_id: 42
---

# Content here
EOD;
    
    $parser = new FLIPHeaderParser();
    $result = $parser->parse($content);
    
    assert(!empty($result), 'Result should not be empty');
    assert(isset($result['wolfie.headers']), 'Should have wolfie.headers key');
}

/**
 * Test missing header
 */
function testMissingHeader()
{
    $content = "# Just a regular markdown file\n\nNo FLIP header here.";
    
    $parser = new FLIPHeaderParser();
    $result = $parser->parse($content);
    
    assert(empty($result), 'Result should be empty for missing header');
    assert(is_array($result), 'Result should still be an array');
}

/**
 * Test malformed YAML (missing closing delimiter)
 */
function testMalformedYaml()
{
    $content = <<<'EOD'
---
wolfie.headers: {
  file_path_from_root: "example.md"
}

# Content without closing ---
EOD;
    
    $parser = new FLIPHeaderParser();
    $result = $parser->parse($content);
    
    // Should handle gracefully and return empty array
    assert(is_array($result), 'Result should be an array');
}

/**
 * Test partial header with missing fields
 */
function testPartialHeader()
{
    $content = <<<'EOD'
---
wolfie.headers: {
  file_path_from_root: "example.md"
}
---

# Content here
EOD;
    
    $parser = new FLIPHeaderParser();
    $result = $parser->parse($content);
    
    assert(!empty($result), 'Result should not be empty');
    assert(isset($result['wolfie.headers']), 'Should have wolfie.headers key');
    assert(isset($result['wolfie.headers']['file_path_from_root']), 'Should have file_path_from_root');
    assert(!isset($result['wolfie.headers']['system_version']), 'Should not have system_version');
}

/**
 * Test empty content
 */
function testEmptyContent()
{
    $parser = new FLIPHeaderParser();
    $result = $parser->parse('');
    
    assert(empty($result), 'Result should be empty for empty content');
    assert(is_array($result), 'Result should be an array');
}

/**
 * Test getField method
 */
function testGetField()
{
    $content = <<<'EOD'
---
wolfie.headers: {
  file_path_from_root: "example.md",
  system_version: "4.0.44"
}
---
EOD;
    
    $parser = new FLIPHeaderParser();
    $value = $parser->getField($content, 'wolfie.headers', null);
    
    assert($value !== null, 'Should find wolfie.headers field');
    assert(is_array($value), 'Field value should be array');
    assert($value['system_version'] === '4.0.44', 'Nested value should match');
}

/**
 * Test getField with default value
 */
function testGetFieldWithDefault()
{
    $content = <<<'EOD'
---
wolfie.headers: {
  file_path_from_root: "example.md"
}
---
EOD;
    
    $parser = new FLIPHeaderParser();
    $value = $parser->getField($content, 'nonexistent', 'default_value');
    
    assert($value === 'default_value', 'Should return default for missing field');
}

/**
 * Test hasHeader method
 */
function testHasHeader()
{
    $withHeader = "---\nwolfie.headers: {}\n---\n";
    $withoutHeader = "# Just content";
    
    $parser = new FLIPHeaderParser();
    
    assert($parser->hasHeader($withHeader) === true, 'Should detect header');
    assert($parser->hasHeader($withoutHeader) === false, 'Should not detect header');
    assert($parser->hasHeader('') === false, 'Should handle empty string');
}

/**
 * Test complex inline header with multiple fields
 */
function testComplexInlineHeader()
{
    $content = <<<'EOD'
---
wolfie.headers: {
  file_path_from_root: "AGENTS.md",
  system_version: "4.0.44",
  channel_id: 1,
  actor_id: 1002,
  created_ymdhis: 20260224171500,
  updated_ymdhis: 20260224171500,
  message_type: "documentation",
  visibility: "public",
  priority: "high"
}
---
EOD;
    
    $parser = new FLIPHeaderParser();
    $result = $parser->parse($content);
    
    assert(!empty($result), 'Result should not be empty');
    assert(isset($result['wolfie.headers']), 'Should have wolfie.headers');
    
    $headers = $result['wolfie.headers'];
    assert($headers['file_path_from_root'] === 'AGENTS.md', 'file_path_from_root should match');
    assert($headers['system_version'] === '4.0.44', 'system_version should match');
    assert($headers['channel_id'] === 1, 'channel_id should be integer');
    assert($headers['actor_id'] === 1002, 'actor_id should be integer');
    assert($headers['created_ymdhis'] === 20260224171500, 'created_ymdhis should be integer');
    assert($headers['message_type'] === 'documentation', 'message_type should match');
    assert($headers['visibility'] === 'public', 'visibility should match');
    assert($headers['priority'] === 'high', 'priority should match');
}

// Run tests
exit(runTests());
