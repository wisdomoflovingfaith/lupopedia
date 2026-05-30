<?php
/**
 * System Integrity Monitor
 * 
 * ATHENA ENFORCEMENT DOCTRINE: Runtime verification for version integrity
 * 
 * @version 1.0
 * @author ATHENA (actor_id 12)
 */

require_once __DIR__ . '/version_resolver.php';
require_once __DIR__ . '/../classes/SingleFieldVersioningValidator.php';

/**
 * Verify system-wide version integrity
 * 
 * @return array Integrity check results
 */
function verify_system_version_integrity()
{
    $results = array(
        'timestamp' => gmdate('YmdHis'),
        'current_version' => get_lupopedia_system_version(),
        'checks' => array(),
        'violations' => array(),
        'status' => 'PASS'
    );
    
    // Check 1: Resolver consistency
    $resolver_check = check_resolver_consistency();
    $results['checks']['resolver_consistency'] = $resolver_check;
    
    // Check 2: Template generator compliance
    $template_check = check_template_generator_compliance();
    $results['checks']['template_generator'] = $template_check;
    
    // Check 3: Validator strictness
    $validator_check = check_validator_strictness();
    $results['checks']['validator'] = $validator_check;
    
    // Check 4: Projection enforcement
    $projection_check = check_projection_enforcement();
    $results['checks']['projection'] = $projection_check;
    
    // Aggregate violations
    foreach ($results['checks'] as $check_name => $check_result) {
        if ($check_result['status'] !== 'PASS') {
            $results['violations'][] = array(
                'check' => $check_name,
                'issue' => $check_result['issue'],
                'severity' => $check_result['severity']
            );
            $results['status'] = 'FAIL';
        }
    }
    
    return $results;
}

/**
 * Check resolver consistency
 */
function check_resolver_consistency()
{
    try {
        $version1 = get_lupopedia_system_version();
        $version2 = get_lupopedia_system_version();
        
        if ($version1 !== $version2) {
            return array(
                'status' => 'FAIL',
                'issue' => 'Resolver not deterministic: ' . var_export(compact('version1', 'version2'), true),
                'severity' => 'CRITICAL'
            );
        }
        
        return array(
            'status' => 'PASS',
            'message' => 'Resolver is deterministic: ' . $version1
        );
    } catch (Exception $e) {
        return array(
            'status' => 'FAIL',
            'issue' => 'Resolver error: ' . $e->getMessage(),
            'severity' => 'CRITICAL'
        );
    }
}

/**
 * Check template generator compliance
 */
function check_template_generator_compliance()
{
    try {
        $generator = new LupopediaArtifactTemplateGenerator();
        
        $config = array(
            'file_path_from_root' => 'test/integrity.md',
            'web_path' => 'http://test/integrity',
            'project_id' => 0,
            'project_slug' => 'test',
            'channel_id' => 66,
            'thread_id' => 1007,
            'task_id' => 'integrity_check',
            'actor_id' => 12,
            'actor_name' => 'athena',
            'delegation_chain' => 'athena:root',
            'artifact_type' => 'test',
            'artifact_kind' => 'integrity',
            'purpose' => 'System integrity check',
            'title' => 'Integrity Check',
            'description' => 'System integrity verification',
            'traits' => array('test'),
            'tags' => array('test'),
            'message_type' => 'test'
        );
        
        $content = $generator->generateArtifact($config);
        
        // Check if content contains correct version
        $expected_version = get_lupopedia_system_version();
        $expected_pattern = 'version_when_written: "' . $expected_version . '"';
        
        if (strpos($content, $expected_pattern) === false) {
            return array(
                'status' => 'FAIL',
                'issue' => 'Template generator did not produce correct version',
                'severity' => 'CRITICAL'
            );
        }
        
        // Check for forbidden fields
        if (strpos($content, 'lupopedia.version:') !== false) {
            return array(
                'status' => 'FAIL',
                'issue' => 'Template generator produced forbidden lupopedia.version field',
                'severity' => 'CRITICAL'
            );
        }
        
        if (strpos($content, 'system_version:') !== false) {
            return array(
                'status' => 'FAIL',
                'issue' => 'Template generator produced forbidden system_version field',
                'severity' => 'CRITICAL'
            );
        }
        
        return array(
            'status' => 'PASS',
            'message' => 'Template generator produces correct single-field headers'
        );
    } catch (SystemError $e) {
        return array(
            'status' => 'FAIL',
            'issue' => 'Template generator enforcement error: ' . $e->getMessage(),
            'severity' => 'CRITICAL'
        );
    } catch (Exception $e) {
        return array(
            'status' => 'FAIL',
            'issue' => 'Template generator error: ' . $e->getMessage(),
            'severity' => 'HIGH'
        );
    }
}

/**
 * Check validator strictness
 */
function check_validator_strictness()
{
    try {
        $validator = new SingleFieldVersioningValidator();
        
        // Test 1: Valid new artifact should pass
        $valid_headers = array('version_when_written' => get_lupopedia_system_version());
        $result = $validator->validateSingleFieldVersioning($valid_headers, false);
        
        if (!$result['valid']) {
            return array(
                'status' => 'FAIL',
                'issue' => 'Validator rejected valid new artifact',
                'severity' => 'CRITICAL'
            );
        }
        
        // Test 2: Stale version should be rejected with ValidationError
        try {
            $stale_headers = array('version_when_written' => '4.0.79');
            $validator->validateSingleFieldVersioning($stale_headers, false);
            
            return array(
                'status' => 'FAIL',
                'issue' => 'Validator did not reject stale version',
                'severity' => 'CRITICAL'
            );
        } catch (ValidationError $e) {
            // Expected behavior
            return array(
                'status' => 'PASS',
                'message' => 'Validator correctly rejects stale versions'
            );
        }
        
    } catch (Exception $e) {
        return array(
            'status' => 'FAIL',
            'issue' => 'Validator error: ' . $e->getMessage(),
            'severity' => 'HIGH'
        );
    }
}

/**
 * Check projection enforcement
 */
function check_projection_enforcement()
{
    try {
        $projection = new Channel66HeaderProjection();
        
        // Use reflection to test getCurrentSystemVersion method
        $reflection = new ReflectionClass($projection);
        $method = $reflection->getMethod('getCurrentSystemVersion');
        $method->setAccessible(true);
        
        $version = $method->invoke($projection);
        
        $expected_version = get_lupopedia_system_version();
        
        if ($version !== $expected_version) {
            return array(
                'status' => 'FAIL',
                'issue' => 'Projection returned wrong version: ' . $version . ' != ' . $expected_version,
                'severity' => 'CRITICAL'
            );
        }
        
        return array(
            'status' => 'PASS',
            'message' => 'Projection uses correct resolver version'
        );
        
    } catch (SystemError $e) {
        return array(
            'status' => 'FAIL',
            'issue' => 'Projection enforcement error: ' . $e->getMessage(),
            'severity' => 'CRITICAL'
        );
    } catch (Exception $e) {
        return array(
            'status' => 'FAIL',
            'issue' => 'Projection error: ' . $e->getMessage(),
            'severity' => 'HIGH'
        );
    }
}

/**
 * Log integrity check results
 */
function log_integrity_check($results)
{
    $log_entry = sprintf(
        "[%s] INTEGRITY CHECK: %s - %d checks, %d violations\n",
        $results['timestamp'],
        $results['status'],
        count($results['checks']),
        count($results['violations'])
    );
    
    error_log($log_entry);
    
    if ($results['status'] === 'FAIL') {
        foreach ($results['violations'] as $violation) {
            error_log(sprintf(
                "  VIOLATION: %s - %s (%s)\n",
                $violation['check'],
                $violation['issue'],
                $violation['severity']
            ));
        }
    }
}
?>
