<?php
/**
 * Canonical Versioning Validator
 * 
 * Enforces ATHENA's canonical versioning model
 * for LUPOPEDIA HEADERS compliance.
 * 
 * @version 1.0
 * @author HEPHAESTUS (actor_id 3)
 */

require_once __DIR__ . '/version_resolver.php';

/**
 * Single-Field Versioning Validator
 * 
 * Enforces single-source versioning model:
 * - version_when_written (immutable creation version only)
 */
class ThreeFieldVersioningValidator
{
    /**
     * Validate single-field versioning compliance
     * 
     * @param array $headers Parsed headers to validate
     * @param bool $isLegacyArtifact Whether this is a legacy artifact
     * @return array Validation result
     */
    public function validateThreeFieldVersioning($headers, $isLegacyArtifact = false)
    {
        $errors = array();
        $warnings = array();
        
        // Required fields for new artifacts (single-field model)
        $requiredFields = array(
            'version_when_written'
        );
        
        // Legacy artifacts get warn-first treatment
        if ($isLegacyArtifact) {
            // For legacy artifacts, version_when_written is optional
            // lupopedia.version and system_version are NOT required (they're deprecated)
            $legacyRequiredFields = array();
            
            foreach ($legacyRequiredFields as $field) {
                if (!isset($headers[$field])) {
                    $warnings[] = "Legacy artifact missing recommended field: {$field}";
                }
            }
            
            // Warn about deprecated fields if present
            if (isset($headers['lupopedia.version'])) {
                $warnings[] = "Legacy artifact contains deprecated lupopedia.version field";
            }
            if (isset($headers['system_version'])) {
                $warnings[] = "Legacy artifact contains deprecated system_version field";
            }
            
            // Check if legacy artifact has stale system_version
            if (isset($headers['system_version'])) {
                $currentVersion = get_lupopedia_system_version();
                if ($headers['system_version'] !== $currentVersion) {
                    $warnings[] = "Legacy artifact has stale system_version: {$headers['system_version']} (current: {$currentVersion})";
                }
            }
            
            // Check if version_when_written is present in legacy (should be warned)
            if (isset($headers['version_when_written'])) {
                $warnings[] = "Legacy artifact contains version_when_written field (should only be in new artifacts)";
            }
            
        } else {
            // New artifacts must have canonical fields only
            foreach ($requiredFields as $field) {
                if (!isset($headers[$field])) {
                    $errors[] = "New artifact missing required field: {$field}";
                }
            }
            
            // CRITICAL: lupopedia.version must NEVER be present in new artifact headers
            if (isset($headers['lupopedia.version'])) {
                $errors[] = "CRITICAL: lupopedia.version field found in artifact headers - this field must NEVER be stored in new artifacts";
            }
            
            // CRITICAL: system_version must NEVER be present in new artifact headers
            if (isset($headers['system_version'])) {
                $errors[] = "CRITICAL: system_version field found in artifact headers - this field must NEVER be stored in new artifacts";
            }
            
            // Validate field semantics
            $this->validateFieldSemantics($headers, $errors, $warnings);
        }
        
        return array(
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
            'compliance_level' => $isLegacyArtifact ? 'legacy_warn_first' : 'new_enforced'
        );
    }
    
    /**
     * Validate field semantics according to single-source doctrine
     * 
     * @param array $headers Headers to validate
     * @param array &$errors Errors array to populate
     * @param array &$warnings Warnings array to populate
     */
    private function validateFieldSemantics($headers, &$errors, &$warnings)
    {
        // Validate version_when_written (should be runtime version format)
        if (isset($headers['version_when_written'])) {
            if (!preg_match('/^\d+\.\d+\.\d+$/', $headers['version_when_written'])) {
                $errors[] = "version_when_written '{$headers['version_when_written']}' has invalid format (should be semantic version)";
            }
        }
    }
    
    /**
     * Get validation summary for reporting
     * 
     * @param array $validationResult Result from validateThreeFieldVersioning
     * @return string Human-readable summary
     */
    public function getValidationSummary($validationResult)
    {
        if ($validationResult['valid']) {
            return "✅ VALID - Single-field versioning model compliant";
        } else {
            $errorCount = count($validationResult['errors']);
            $warningCount = count($validationResult['warnings']);
            return "❌ INVALID - {$errorCount} error(s), {$warningCount} warning(s)";
        }
    }
}
?>
