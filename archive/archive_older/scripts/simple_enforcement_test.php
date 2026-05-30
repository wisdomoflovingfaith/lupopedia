<?php
require_once 'includes/functions/version_resolver.php';
require_once 'includes/classes/LupopediaArtifactTemplateGenerator.php';
require_once 'includes/classes/SingleFieldVersioningValidator.php';
require_once 'includes/classes/Channel66HeaderProjection.php';

echo "=== SIMPLE ENFORCEMENT TEST ===\n";

$currentVersion = get_lupopedia_system_version();
echo "Current version: $currentVersion\n\n";

// Test 1: Resolver enforcement
echo "Test 1: Resolver enforcement\n";
try {
    enforce_resolver_version('4.0.79', 'test');
    echo "FAIL: Should have thrown SystemError\n";
} catch (SystemError $e) {
    echo "PASS: " . $e->getMessage() . "\n";
}

// Test 2: Template generator
echo "\nTest 2: Template generator\n";
$generator = new LupopediaArtifactTemplateGenerator();
$config = array(
    'file_path_from_root' => 'test.md',
    'web_path' => 'http://test',
    'project_id' => 0,
    'project_slug' => 'test',
    'channel_id' => 66,
    'thread_id' => 1007,
    'task_id' => 'test',
    'actor_id' => 12,
    'actor_name' => 'athena',
    'delegation_chain' => 'athena:root',
    'artifact_type' => 'test',
    'artifact_kind' => 'test',
    'purpose' => 'Test',
    'title' => 'Test',
    'description' => 'Test',
    'traits' => array('test'),
    'tags' => array('test'),
    'message_type' => 'test'
);

$content = $generator->generateArtifact($config);
if (strpos($content, "version_when_written: \"$currentVersion\"") !== false) {
    echo "PASS: Template generates correct version\n";
} else {
    echo "FAIL: Template did not generate correct version\n";
}

// Test 3: Validator
echo "\nTest 3: Validator\n";
$validator = new SingleFieldVersioningValidator();
try {
    $validator->validateSingleFieldVersioning(array('version_when_written' => '4.0.79'), false);
    echo "FAIL: Should have thrown ValidationError\n";
} catch (ValidationError $e) {
    echo "PASS: " . $e->getMessage() . "\n";
}

// Test 4: Projection
echo "\nTest 4: Projection\n";
$projection = new Channel66HeaderProjection();
$reflection = new ReflectionClass($projection);
$method = $reflection->getMethod('getCurrentSystemVersion');
$method->setAccessible(true);
$version = $method->invoke($projection);
if ($version === $currentVersion) {
    echo "PASS: Projection uses correct version\n";
} else {
    echo "FAIL: Projection returned $version, expected $currentVersion\n";
}

echo "\n=== TEST COMPLETE ===\n";
?>
