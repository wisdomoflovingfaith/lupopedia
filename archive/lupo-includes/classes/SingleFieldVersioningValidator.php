<?php
/**
 * Single-Field Versioning Validator
 * 
 * Enforces WOLFIE's single-field versioning model and
 * ATHENA's enforcement doctrine for LUPOPEDIA HEADERS compliance.
 * 
 * @version 1.0
 * @author HEPHAESTUS (actor_id 3)
 */

require_once __DIR__ . '/../functions/version_resolver.php';
require_once __DIR__ . '/../exceptions/ValidationError.php';

/**
 * Single-Field Versioning Validator
 * 
 * Enforces single-field versioning model:
 * - version_when_written (only field allowed)
 */
class SingleFieldVersioningValidator
{
    /**
     * Validate single-field versioning compliance
     * 
     * @param array $headers Parsed headers to validate
     * @param bool $isLegacyArtifact Whether this is a legacy artifact
     * @return array Validation result
     */
    public function validateSingleFieldVersioning($headers, $isLegacyArtifact = false)
    {
        $errors = array();
        $warnings = array();
        
        // Required fields for new artifacts (single-field model)
        $requiredFields = array(
            'version_when_written'
        );
        
        // Forbidden fields for new artifacts
        $forbiddenFields = array(
            'lupopedia.version',
            'system_version'
        );
        
        // Check required fields
        foreach ($requiredFields as $field) {
            if (!isset($headers[$field])) {
                $errors[] = "Missing required field: {$field}";
            }
        }
        
        // Check forbidden fields
        if (!$isLegacyArtifact) {
            foreach ($forbiddenFields as $field) {
                if (isset($headers[$field])) {
                    $errors[] = "Forbidden field found: {$field} (only version_when_written allowed)";
                }
            }
        }
        
        // Validate version_when_written format
        if (isset($headers['version_when_written'])) {
            if (!$this->isValidSemanticVersion($headers['version_when_written'])) {
                $errors[] = "Invalid version_when_written format: {$headers['version_when_written']}";
            }
            
            // ATHENA ENFORCEMENT DOCTRINE: Strict validation for new artifacts
            if (!$isLegacyArtifact) {
                $current_version = get_lupopedia_system_version();
                if ($headers['version_when_written'] !== $current_version) {
                    throw new ValidationError(
                        "Stale version_when_written detected: '{$headers['version_when_written']}' != current '$current_version'",
                        'single_field_validation',
                        array(
                            'provided_version' => $headers['version_when_written'],
                            'current_version' => $current_version,
                            'field' => 'version_when_written'
                        ),
                        1002
                    );
                }
            }
        }
        
        // Legacy artifact warnings
        if ($isLegacyArtifact) {
            if (isset($headers['lupopedia.version'])) {
                $warnings[] = "Legacy artifact contains lupopedia.version field (deprecated)";
            }
            if (isset($headers['system_version'])) {
                $warnings[] = "Legacy artifact contains system_version field (deprecated)";
            }
        }
        
        return array(
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
            'compliance_level' => $this->getComplianceLevel($errors, $warnings, $isLegacyArtifact)
        );
    }
    
    /**
     * Check if version follows semantic versioning format
     * 
     * @param string $version Version string to validate
     * @return bool True if valid semantic version
     */
    private function isValidSemanticVersion($version)
    {
        // Basic semantic version pattern: X.Y.Z
        return preg_match('/^\d+\.\d+\.\d+$/', $version);
    }
    
    /**
     * Determine compliance level
     * 
     * @param array $errors Validation errors
     * @param array $warnings Validation warnings
     * @param bool $isLegacyArtifact Whether this is a legacy artifact
     * @return string Compliance level
     */
    private function getComplianceLevel($errors, $warnings, $isLegacyArtifact)
    {
        if (!empty($errors)) {
            return 'REJECT';
        }
        
        if ($isLegacyArtifact && !empty($warnings)) {
            return 'WARN';
        }
        
        if (!empty($warnings)) {
            return 'WARN';
        }
        
        return 'PASS';
    }
    
    /**
     * Get validation summary for reporting
     * 
     * @param array $result Validation result
     * @return string Human-readable summary
     */
    public function getValidationSummary($result)
    {
        $status = $result['compliance_level'];
        $summary = "Versioning Compliance: {$status}";
        
        if (!empty($result['errors'])) {
            $summary .= "\nErrors:\n  - " . implode("\n  - ", $result['errors']);
        }
        
        if (!empty($result['warnings'])) {
            $summary .= "\nWarnings:\n  - " . implode("\n  - ", $result['warnings']);
        }
        
        return $summary;
    }
}
