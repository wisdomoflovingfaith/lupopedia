<?php
/**
 * Canonical Wolfie Header Generator for Cascade Compatibility
 * 
 * This script generates canonical Wolfie headers that are:
 * - Fully deterministic
 * - Schema-agnostic
 * - Dreaming-safe (no interpretive fields)
 * - Witness-safe (no meta-layer artifacts)
 * - Migration-safe (no schema mutations)
 * - Doctrine-aligned with 4.2.3
 */

class CanonicalHeaderGenerator
{
    private const TEMPLATE_PATH = __DIR__ . '/../templates/canonical_wolfie_header_template.yaml';
    private const GLOBAL_ATOMS_PATH = __DIR__ . '/../config/global_atoms.yaml';
    
    /**
     * Generate canonical Wolfie header for a file
     */
    public static function generateHeader(array $params): string
    {
        $template = self::loadTemplate();
        $atoms = self::loadGlobalAtoms();
        
        $replacements = [
            '<FILENAME>' => $params['filename'] ?? 'unknown_file',
            '<SYSTEM_VERSION>' => $params['version'] ?? $atoms['GLOBAL_CURRENT_LUPOPEDIA_VERSION'] ?? '4.2.3',
            '<UTC_TIMESTAMP>' => gmdate('Y-m-d H:i:s'),
            '<UTC_DAY>' => gmdate('Y-m-d'),
            '<HUMAN_READABLE_TITLE>' => $params['title'] ?? 'Untitled Document',
            '<SHORT_DESCRIPTION>' => $params['description'] ?? 'No description provided',
            '<BRANCH_NAME>' => $params['branch'] ?? 'main'
        ];
        
        $header = str_replace(array_keys($replacements), array_values($replacements), $template);
        
        // Update dialog message if provided
        if (isset($params['message'])) {
            $header = preg_replace(
                '/message: ".*?"/',
                'message: "' . $params['message'] . '"',
                $header
            );
        }
        
        // Generate minimal core-only header if requested
        if (isset($params['core_only']) && $params['core_only']) {
            return self::generateCoreHeader($replacements);
        }
        
        return $header;
    }
    
    /**
     * Generate minimal mandatory WOLFIE HEADER CORE
     */
    private static function generateCoreHeader(array $replacements): string
    {
        return "---\nwolfie.headers: explicit architecture with structured clarity for every file.\n\nfile.name: \"{$replacements['<FILENAME>']}\"\nfile.last_modified_system_version: {$replacements['<SYSTEM_VERSION>']}\nfile.last_modified_utc: {$replacements['<UTC_TIMESTAMP>']}\nfile.utc_day: {$replacements['<UTC_DAY>']}\n\nauthor: GLOBAL_CURRENT_AUTHORS\n\nsystem_context:\n  schema_state: \"Frozen\"\n  table_ceiling: 185\n  governance_active:\n    - \"GOV-AD-PROHIBIT-001\"\n    - \"GOV-LILITH-0001\"\n    - \"GOV-INTEGRATION-0001\"\n    - \"LABS-001\"\n    - \"TABLE_COUNT_DOCTRINE\"\n    - \"LIMITS_DOCTRINE\"\n  doctrine_mode: \"File-Sovereignty\"\n---";
    }
    
    /**
     * Load the canonical template
     */
    private static function loadTemplate(): string
    {
        if (!file_exists(self::TEMPLATE_PATH)) {
            throw new Exception("Template not found: " . self::TEMPLATE_PATH);
        }
        
        return file_get_contents(self::TEMPLATE_PATH);
    }
    
    /**
     * Load global atoms for resolution
     */
    private static function loadGlobalAtoms(): array
    {
        if (!file_exists(self::GLOBAL_ATOMS_PATH)) {
            return [];
        }
        
        $content = file_get_contents(self::GLOBAL_ATOMS_PATH);
        return yaml_parse($content) ?: [];
    }
    
    /**
     * Validate header compliance with Cascade requirements
     */
    public static function validateHeader(string $header): array
    {
        $issues = [];
        
        // Check for mandatory core fields
        $requiredCoreFields = [
            'wolfie.headers:',
            'file.name:',
            'file.last_modified_system_version:',
            'file.last_modified_utc:',
            'file.utc_day:',
            'author:',
            'system_context:',
            'schema_state: "Frozen"',
            'table_ceiling: 185',
            'governance_active:',
            'doctrine_mode: "File-Sovereignty"'
        ];
        
        foreach ($requiredCoreFields as $field) {
            if (strpos($header, $field) === false) {
                $issues[] = "Missing required core field: {$field}";
            }
        }
        
        // Check for required governance items
        $requiredGovernance = [
            'GOV-AD-PROHIBIT-001',
            'GOV-LILITH-0001',
            'GOV-INTEGRATION-0001',
            'LABS-001',
            'TABLE_COUNT_DOCTRINE',
            'LIMITS_DOCTRINE'
        ];
        
        foreach ($requiredGovernance as $gov) {
            if (strpos($header, $gov) === false) {
                $issues[] = "Missing required governance item: {$gov}";
            }
        }
        
        // Check for prohibited interpretive fields
        $prohibitedPatterns = [
            '/mood\s*:\s*"[^"]*"/',  # No interpretive mood (except RGB)
            '/emotion\s*:\s*"[^"]*"/',  # No emotion fields
            '/symbolic\s*:\s*"[^"]*"/',  # No symbolic fields
        ];
        
        foreach ($prohibitedPatterns as $pattern) {
            if (preg_match($pattern, $header)) {
                $issues[] = "Contains prohibited interpretive field";
            }
        }
        
        return [
            'valid' => empty($issues),
            'issues' => $issues
        ];
    }
}

// Example usage
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    // Full header example
    $params = [
        'filename' => 'example_file.md',
        'title' => 'Example Document',
        'description' => 'An example document with canonical header',
        'message' => 'Generated canonical header for Cascade compatibility',
        'branch' => 'main'  // Optional: defaults to 'main'
    ];
    
    echo "=== FULL CANONICAL HEADER ===\n";
    $header = CanonicalHeaderGenerator::generateHeader($params);
    echo $header;
    
    $validation = CanonicalHeaderGenerator::validateHeader($header);
    echo "\n\nValidation: " . ($validation['valid'] ? 'PASS' : 'FAIL');
    if (!$validation['valid']) {
        echo "\nIssues: " . implode(', ', $validation['issues']);
    }
    
    echo "\n\n=== MANDATORY CORE HEADER ===\n";
    $coreHeader = CanonicalHeaderGenerator::generateHeader(['filename' => 'core_file.md', 'core_only' => true]);
    echo $coreHeader;
    
    $coreValidation = CanonicalHeaderGenerator::validateHeader($coreHeader);
    echo "\n\nCore Validation: " . ($coreValidation['valid'] ? 'PASS' : 'FAIL');
    if (!$coreValidation['valid']) {
        echo "\nIssues: " . implode(', ', $coreValidation['issues']);
    }
}
