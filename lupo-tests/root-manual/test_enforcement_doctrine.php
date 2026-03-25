<?php
/**
 * Test ATHENA's Enforcement Doctrine Implementation
 * 
 * Tests all enforcement points to ensure stale version_when_written
 * cannot be written in any new artifact creation path.
 */

require_once 'lupo-includes/functions/version_resolver.php';
require_once 'lupo-includes/classes/LupopediaArtifactTemplateGenerator.php';
require_once 'lupo-includes/classes/SingleFieldVersioningValidator.php';
require_once 'lupo-includes/classes/Channel66HeaderProjection.php';

echo "=== ATHENA ENFORCEMENT DOCTRINE TESTS ===\n\n";

$testResults = array();
$currentVersion = get_lupopedia_system_version();
echo "Current system version: $currentVersion\n\n";

// Test A — Valid Creation
echo "Test A: Valid Creation\n";
try {
    $generator = new LupopediaArtifactTemplateGenerator();
    $config = array(
        'file_path_from_root' => 'test/valid.md',
        'web_path' => 'http://test/valid',
        'project_id' => 0,
        'project_slug' => 'test',
        'channel_id' => 66,
        'thread_id' => 1007,
        'task_id' => 'test_valid',
        'actor_id' => 12,
        'actor_name' => 'athena',
        'delegation_chain' => 'athena:root',
        'artifact_type' => 'test',
        'artifact_kind' => 'test',
        'purpose' => 'Valid test artifact',
        'title' => 'Valid Test',
        'description' => 'Valid test artifact',
        'traits' => array('test'),
        'tags' => array('test'),
        'message_type' => 'test'
    );
    
    $content = $generator->generateArtifact($config);
    
    // Check for correct version
    $hasCorrectVersion = strpos($content, "version_when_written: \"$currentVersion\"") !== false;
    $hasForbiddenFields = strpos($content, 'lupopedia.version:') !== false || strpos($content, 'system_version:') !== false;
    
    if ($hasCorrectVersion && !$hasForbiddenFields) {
        echo "  ✅ PASS: Generated artifact has correct version and no forbidden fields\n";
        $testResults['valid_creation'] = 'PASS';
    } else {
        echo "  ❌ FAIL: Generated artifact missing correct version or has forbidden fields\n";
        $testResults['valid_creation'] = 'FAIL';
    }
    
    // Validate the generated content
    $validator = new SingleFieldVersioningValidator();
    $result = $validator->validateSingleFieldVersioning(array('version_when_written' => $currentVersion), false);
    
    if ($result['valid']) {
        echo "  ✅ PASS: Validator accepts correct version\n";
    } else {
        echo "  ❌ FAIL: Validator rejects correct version\n";
        $testResults['valid_creation'] = 'FAIL';
    }
    
} catch (Exception $e) {
    echo "  ❌ FAIL: Exception during valid creation: " . $e->getMessage() . "\n";
    $testResults['valid_creation'] = 'FAIL';
}

echo "\n";

// Test B — Stale Manual Injection
echo "Test B: Stale Manual Injection\n";
try {
    $validator = new SingleFieldVersioningValidator();
    
    // Test with stale version
    $staleHeaders = array('version_when_written' => '4.0.79');
    $validator->validateSingleFieldVersioning($staleHeaders, false);
    
    echo "  ❌ FAIL: Validator should have thrown ValidationError for stale version\n";
    $testResults['stale_injection'] = 'FAIL';
    
} catch (ValidationError $e) {
    echo "  ✅ PASS: Validator correctly threw ValidationError for stale version\n";
    echo "    Error: " . $e->getMessage() . "\n";
    $testResults['stale_injection'] = 'PASS';
} catch (Exception $e) {
    echo "  ❌ FAIL: Wrong exception type: " . get_class($e) . "\n";
    $testResults['stale_injection'] = 'FAIL';
}

echo "\n";

// Test C — Forbidden Field Rejection
echo "Test C: Forbidden Field Rejection\n";
try {
    $validator = new SingleFieldVersioningValidator();
    
    // Test with forbidden fields
    $forbiddenHeaders = array(
        'version_when_written' => $currentVersion,
        'lupopedia.version' => '1.0'
    );
    
    $result = $validator->validateSingleFieldVersioning($forbiddenHeaders, false);
    
    if (!$result['valid'] && strpos(implode(' ', $result['errors']), 'Forbidden field') !== false) {
        echo "  ✅ PASS: Validator rejects forbidden field\n";
        $testResults['forbidden_field'] = 'PASS';
    } else {
        echo "  ❌ FAIL: Validator should reject forbidden field\n";
        $testResults['forbidden_field'] = 'FAIL';
    }
    
} catch (Exception $e) {
    echo "  ❌ FAIL: Exception during forbidden field test: " . $e->getMessage() . "\n";
    $testResults['forbidden_field'] = 'FAIL';
}

echo "\n";

// Test D — Template Generator Enforcement
echo "Test D: Template Generator Enforcement\n";
try {
    // Try to manually force stale version into generator
    $generator = new LupopediaArtifactTemplateGenerator();
    
    // The generator should always use resolver, so we test that it's working
    $config = array(
        'file_path_from_root' => 'test/enforce.md',
        'web_path' => 'http://test/enforce',
        'project_id' => 0,
        'project_slug' => 'test',
        'channel_id' => 66,
        'thread_id' => 1007,
        'task_id' => 'test_enforce',
        'actor_id' => 12,
        'actor_name' => 'athena',
        'delegation_chain' => 'athena:root',
        'artifact_type' => 'test',
        'artifact_kind' => 'test',
        'purpose' => 'Enforcement test',
        'title' => 'Enforcement Test',
        'description' => 'Enforcement test',
        'traits' => array('test'),
        'tags' => array('test'),
        'message_type' => 'test'
    );
    
    $content = $generator->generateArtifact($config);
    
    // Verify it has the correct version, not a stale one
    if (strpos($content, "version_when_written: \"$currentVersion\"") !== false) {
        echo "  ✅ PASS: Template generator enforces correct version\n";
        $testResults['template_enforcement'] = 'PASS';
    } else {
        echo "  ❌ FAIL: Template generator did not enforce correct version\n";
        $testResults['template_enforcement'] = 'FAIL';
    }
    
} catch (SystemError $e) {
    echo "  ❌ FAIL: Template generator threw SystemError: " . $e->getMessage() . "\n";
    $testResults['template_enforcement'] = 'FAIL';
} catch (Exception $e) {
    echo "  ❌ FAIL: Template generator threw exception: " . $e->getMessage() . "\n";
    $testResults['template_enforcement'] = 'FAIL';
}

echo "\n";

// Test E — Projection Enforcement
echo "Test E: Projection Enforcement\n";
try {
    $projection = new Channel66HeaderProjection();
    
    // Use reflection to test getCurrentSystemVersion
    $reflection = new ReflectionClass($projection);
    $method = $reflection->getMethod('getCurrentSystemVersion');
    $method->setAccessible(true);
    
    $version = $method->invoke($projection);
    
    if ($version === $currentVersion) {
        echo "  ✅ PASS: Projection uses correct resolver version\n";
        $testResults['projection_enforcement'] = 'PASS';
    } else {
        echo "  ❌ FAIL: Projection returned wrong version: $version != $currentVersion\n";
        $testResults['projection_enforcement'] = 'FAIL';
    }
    
} catch (SystemError $e) {
    echo "  ❌ FAIL: Projection threw SystemError: " . $e->getMessage() . "\n";
    $testResults['projection_enforcement'] = 'FAIL';
} catch (Exception $e) {
    echo "  ❌ FAIL: Projection threw exception: " . $e->getMessage() . "\n";
    $testResults['projection_enforcement'] = 'FAIL';
}

echo "\n";

// Test F — Resolver Source of Truth
echo "Test F: Resolver Source of Truth\n";
try {
    $resolverVersion = get_lupopedia_system_version();
    $lupoVersionFile = trim(file_get_contents('LUPEDIA_VERSION'));
    
    if ($resolverVersion === $lupoVersionFile && $resolverVersion === '4.0.83') {
        echo "  ✅ PASS: Resolver reads from LUPEDIA_VERSION correctly\n";
        $testResults['resolver_source'] = 'PASS';
    } else {
        echo "  ❌ FAIL: Resolver version mismatch\n";
        echo "    Resolver: $resolverVersion\n";
        echo "    LUPEDIA_VERSION: $lupoVersionFile\n";
        echo "    Expected: 4.0.83\n";
        $testResults['resolver_source'] = 'FAIL';
    }
    
} catch (Exception $e) {
    echo "  ❌ FAIL: Exception checking resolver: " . $e->getMessage() . "\n";
    $testResults['resolver_source'] = 'FAIL';
}

echo "\n";

// Summary
echo "=== TEST SUMMARY ===\n";
$passCount = 0;
$totalCount = count($testResults);

foreach ($testResults as $test => $result) {
    echo "$test: $result\n";
    if ($result === 'PASS') $passCount++;
}

echo "\nPassed: $passCount/$totalCount tests\n";

if ($passCount === $totalCount) {
    echo "✅ ALL TESTS PASSED - Enforcement doctrine is working\n";
} else {
    echo "❌ SOME TESTS FAILED - Enforcement doctrine needs fixes\n";
}

?>
