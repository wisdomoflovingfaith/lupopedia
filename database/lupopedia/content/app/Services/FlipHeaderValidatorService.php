<?php
/**
 * FLIP Header Validator Service
 * 
 * Validates FLIP headers and determines routing for unknown recipients
 * Integrates with ANUBIS unknown recipient protocol
 * 
 * @package Lupopedia\App\Services
 * @version 4.0.29
 * @author Captain Wolfie Stoned - Lupopedia LLC 2026
 */

class FlipHeaderValidatorService
{
    private $anubisService;
    private $db;
    
    public function __construct()
    {
        $this->anubisService = new AnubisUnknownRecipientService();
        $this->db = DatabaseFactory::getConnection();
    }
    
    /**
     * Validate FLIP header and determine routing
     * 
     * @param string $filePath Path to the file
     * @param array $flipHeader Parsed FLIP header
     * @return array Validation result with routing information
     */
    public function validateAndRoute($filePath, $flipHeader)
    {
        $result = [
            'valid' => true,
            'errors' => [],
            'warnings' => [],
            'routing' => null,
            'action' => 'proceed'
        ];
        
        // Step 1: Basic FLIP header structure validation
        $structureResult = $this->validateStructure($flipHeader);
        if (!$structureResult['valid']) {
            $result['valid'] = false;
            $result['errors'] = array_merge($result['errors'], $structureResult['errors']);
            return $result;
        }
        
        // Step 2: Recipient validation and routing
        $routingResult = $this->validateRecipients($filePath, $flipHeader);
        $result['routing'] = $routingResult;
        
        // Step 3: If unknown recipient, route to ANUBIS
        if ($routingResult['reason'] === 'unknown_recipient') {
            $anubisResult = $this->anubisService->processOrphanFile(
                $filePath, 
                $flipHeader, 
                $routingResult['reason_code']
            );
            
            $result['action'] = 'routed_to_anubis';
            $result['anubis_result'] = $anubisResult;
            $result['warnings'][] = "File routed to ANUBIS: {$routingResult['reason_code']}";
        }
        
        // Step 4: Additional validations
        $this->validateTimestamps($flipHeader, $result);
        $this->validateFileIntegrity($filePath, $flipHeader, $result);
        
        return $result;
    }
    
    /**
     * Validate basic FLIP header structure
     * 
     * @param array $flipHeader FLIP header data
     * @return array Validation result
     */
    private function validateStructure($flipHeader)
    {
        $result = ['valid' => true, 'errors' => []];
        
        // Required fields
        $requiredFields = [
            'file_path_from_root',
            'file.last_modified_system_version',
            'file.last_modified_utc'
        ];
        
        foreach ($requiredFields as $field) {
            if (!isset($flipHeader[$field]) || empty($flipHeader[$field])) {
                $result['valid'] = false;
                $result['errors'][] = "Missing required field: {$field}";
            }
        }
        
        // Validate version format
        if (isset($flipHeader['file.last_modified_system_version'])) {
            if (!$this->isValidVersion($flipHeader['file.last_modified_system_version'])) {
                $result['valid'] = false;
                $result['errors'][] = "Invalid version format: {$flipHeader['file.last_modified_system_version']}";
            }
        }
        
        // Validate timestamp format
        if (isset($flipHeader['file.last_modified_utc'])) {
            if (!$this->isValidTimestamp($flipHeader['file.last_modified_utc'])) {
                $result['valid'] = false;
                $result['errors'][] = "Invalid timestamp format: {$flipHeader['file.last_modified_utc']}";
            }
        }
        
        return $result;
    }
    
    /**
     * Validate recipients and determine routing
     * 
     * @param string $filePath File path
     * @param array $flipHeader FLIP header
     * @return array Routing information
     */
    private function validateRecipients($filePath, $flipHeader)
    {
        return $this->anubisService->determineRecipient($filePath, $flipHeader);
    }
    
    /**
     * Validate timestamps in FLIP header
     * 
     * @param array $flipHeader FLIP header
     * @param array &$result Result reference (modified)
     */
    private function validateTimestamps($flipHeader, &$result)
    {
        $currentTimestamp = gmdate('YmdHis');
        
        // Check if file timestamp is too old (> 1 year)
        if (isset($flipHeader['file.last_modified_utc'])) {
            $fileTimestamp = $flipHeader['file.last_modified_utc'];
            $maxAge = (int)date('YmdHis', strtotime('-1 year'));
            
            if ($fileTimestamp < $maxAge) {
                $result['warnings'][] = "File timestamp is very old: {$fileTimestamp}";
            }
            
            // Check if file timestamp is in future
            if ($fileTimestamp > $currentTimestamp) {
                $result['warnings'][] = "File timestamp is in future: {$fileTimestamp}";
            }
        }
    }
    
    /**
     * Validate file integrity
     * 
     * @param string $filePath File path
     * @param array $flipHeader FLIP header
     * @param array &$result Result reference (modified)
     */
    private function validateFileIntegrity($filePath, $flipHeader, &$result)
    {
        if (!file_exists($filePath)) {
            $result['valid'] = false;
            $result['errors'][] = "File does not exist: {$filePath}";
            return;
        }
        
        $fileSize = filesize($filePath);
        
        // Check file size limits
        if ($fileSize > 50 * 1024 * 1024) { // 50MB
            $result['warnings'][] = "Large file size: " . round($fileSize / 1024 / 1024, 2) . "MB";
        }
        
        // Check for suspicious content
        if ($fileSize > 0) {
            $content = file_get_contents($filePath, false, null, 0, 1024); // First 1KB
            
            // Check for potentially dangerous content
            $dangerousPatterns = [
                '/<\?php/i',
                '/<script/i',
                '/javascript:/i',
                '/eval\s*\(/i',
                '/exec\s*\(/i'
            ];
            
            foreach ($dangerousPatterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    $result['warnings'][] = "Potentially dangerous content detected";
                    break;
                }
            }
        }
    }
    
    /**
     * Validate version format (x.y.z)
     * 
     * @param string $version Version string
     * @return bool True if valid
     */
    private function isValidVersion($version)
    {
        return preg_match('/^\d+\.\d+\.\d+(-[a-zA-Z0-9]+)?$/', $version);
    }
    
    /**
     * Validate timestamp format (YYYYMMDDHHIISS)
     * 
     * @param string $timestamp Timestamp string
     * @return bool True if valid
     */
    private function isValidTimestamp($timestamp)
    {
        if (!preg_match('/^\d{14}$/', $timestamp)) {
            return false;
        }
        
        $date = DateTime::createFromFormat('YmdHis', $timestamp);
        return $date && $date->format('YmdHis') === $timestamp;
    }
    
    /**
     * Parse FLIP header from file content
     * 
     * @param string $filePath File path
     * @return array|false Parsed FLIP header or false on failure
     */
    public function parseFlipHeaderFromFile($filePath)
    {
        if (!file_exists($filePath)) {
            return false;
        }
        
        $content = file_get_contents($filePath);
        if ($content === false) {
            return false;
        }
        
        return $this->parseFlipHeader($content);
    }
    
    /**
     * Parse FLIP header from content
     * 
     * @param string $content File content
     * @return array|false Parsed FLIP header or false on failure
     */
    public function parseFlipHeader($content)
    {
        // Look for YAML front matter
        if (preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $content, $matches)) {
            try {
                $yaml = $matches[1];
                $parsed = yaml_parse($yaml);
                
                if ($parsed === false) {
                    return false;
                }
                
                return $parsed;
            } catch (Exception $e) {
                return false;
            }
        }
        
        return false;
    }
    
    /**
     * Batch validate multiple files
     * 
     * @param array $filePaths Array of file paths
     * @return array Batch results
     */
    public function batchValidate($filePaths)
    {
        $results = [];
        
        foreach ($filePaths as $filePath) {
            $flipHeader = $this->parseFlipHeaderFromFile($filePath);
            
            if ($flipHeader === false) {
                $results[$filePath] = [
                    'valid' => false,
                    'errors' => ['Unable to parse FLIP header'],
                    'action' => 'failed'
                ];
                continue;
            }
            
            $results[$filePath] = $this->validateAndRoute($filePath, $flipHeader);
        }
        
        return $results;
    }
    
    /**
     * Get validation statistics
     * 
     * @param int $hours Number of hours to look back
     * @return array Statistics
     */
    public function getValidationStats($hours = 24)
    {
        // This would typically query a validation log table
        // For now, return ANUBIS stats as proxy
        return $this->anubisService->getAnubisStats($hours);
    }
}
