<?php
/**
 * Lupopedia Version Resolver
 * 
 * Canonical version resolution following WOLFIE's single-field
 * versioning model enforcement with ATHENA's enforcement doctrine.
 * 
 * @version 1.0
 * @author HEPHAESTUS (actor_id 3)
 */

// Load exception classes
require_once __DIR__ . '/../exceptions/SystemError.php';
require_once __DIR__ . '/../exceptions/ValidationError.php';

/**
 * Get current Lupopedia system version from canonical sources
 * 
 * Resolution order:
 * 1. LUPEDIA_VERSION file (primary source of truth)
 * 2. includes/version.php runtime helper (secondary)
 * 3. config/version.php config fallback (tertiary)
 * 
 * @return string Current system version
 */
function get_lupopedia_system_version()
{
    // Primary source: LUPEDIA_VERSION file
    $versionFile = __DIR__ . '/../../LUPEDIA_VERSION';
    if (file_exists($versionFile)) {
        $version = trim(file_get_contents($versionFile));
        if (!empty($version)) {
            return $version;
        }
    }
    
    // Secondary source: version.php runtime helper
    $versionPhpFile = __DIR__ . '/../../version.php';
    if (file_exists($versionPhpFile)) {
        include_once $versionPhpFile;
        if (defined('LUPO_VERSION')) {
            return LUPO_VERSION;
        }
    }
    
    // Tertiary source: config fallback
    $configVersionFile = __DIR__ . '/../../config/version.php';
    if (file_exists($configVersionFile)) {
        include_once $configVersionFile;
        if (defined('LUPO_VERSION')) {
            return LUPO_VERSION;
        }
    }
    
    // Last resort: explicit fallback (should never be reached)
    error_log("WARNING: Using hardcoded version fallback - all version sources failed");
    return '4.0.83';
}

/**
 * Enforce resolver-only version assignment with strict system error
 * 
 * ATHENA ENFORCEMENT DOCTRINE: Any mismatch is a SYSTEM ERROR
 * 
 * @param string $version Version to validate
 * @param string $context Context for error reporting
 * @return bool True if version matches resolver
 * @throws SystemError If version mismatch detected
 */
function enforce_resolver_version($version, $context = 'unknown')
{
    $current_version = get_lupopedia_system_version();
    
    if ($version !== $current_version) {
        throw new SystemError(
            "STALE VERSION DETECTED in $context: '$version' != '$current_version'",
            $context,
            1001
        );
    }
    
    return true;
}

/**
 * Validate single-field versioning model compliance
 * 
 * FINAL MODEL: Only version_when_written is stored in artifacts
 * lupopedia.version and system_version are NEVER stored - always resolved at runtime
 * 
 * @param array $headers Headers to validate
 * @param bool $isLegacyArtifact Whether this is a legacy artifact
 * @return array Validation result
 */
function validate_single_field_versioning($headers, $isLegacyArtifact = false)
{
    $errors = array();
    $warnings = array();
    
    // Required fields for NEW artifacts only (single-field model)
    if (!$isLegacyArtifact) {
        $required_fields = array('version_when_written');
    } else {
        // Legacy artifacts get warn-first treatment - no requirements
        $required_fields = array();
    }
    
    // CRITICAL: lupopedia.version must NEVER be present in artifact headers
    if (isset($headers['lupopedia.version'])) {
        $errors[] = "CRITICAL: lupopedia.version field found in artifact headers - this field must NEVER be stored";
    }
    
    // CRITICAL: system_version must NEVER be present in artifact headers
    if (isset($headers['system_version'])) {
        $errors[] = "CRITICAL: system_version field found in artifact headers - this field must NEVER be stored";
    }
    
    foreach ($required_fields as $field) {
        if (!isset($headers[$field])) {
            if ($isLegacyArtifact) {
                $warnings[] = "Legacy artifact missing recommended field: {$field}";
            } else {
                $errors[] = "New artifact missing required field: {$field}";
            }
        }
    }
    
    // Validate version_when_written format only
    if (isset($headers['version_when_written'])) {
        if (!preg_match('/^\d+\.\d+\.\d+$/', $headers['version_when_written'])) {
            $errors[] = "Invalid version_when_written format: {$headers['version_when_written']}";
        }
    }
    
    return array(
        'valid' => empty($errors),
        'errors' => $errors,
        'warnings' => $warnings,
        'compliance_level' => $isLegacyArtifact ? 'legacy_warn_first' : 'new_enforced'
    );
}
?>
