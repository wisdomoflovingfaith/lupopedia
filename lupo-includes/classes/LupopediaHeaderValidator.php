<?php
/**
 * Header Validation Hook - Prevents creation of files without LUPOPEDIA headers
 * This should be integrated into any IDE or tool that creates files
 */

class LupopediaHeaderValidator {
    private static $required_header_fields = [
        'lupopedia.headers' => ['version', 'schema', 'file_path_from_root', 'web_path', 
                                'last_modified_utc', 'when_updated', 'system_version', 
                                'channel_id', 'thread_id', 'actor_id', 'actor_name', 
                                'delegation_chain', 'artifact_type', 'artifact_kind', 'purpose', 'tags'],
        'lupopedia.edges' => ['outbound_edges'],
        'lupopedia.footer' => ['version', 'last_verified', 'last_verified_by', 
                               'last_verified_by_actor_id', 'orchestrator', 'next_action']
    ];
    
    /**
     * Validates that a file has proper LUPOPEDIA headers
     * @param string $content File content to validate
     * @param string $filepath File path (for error messages)
     * @return array ['valid' => bool, 'errors' => array]
     */
    public static function validateHeaders($content, $filepath = '') {
        $errors = [];
        
        // Check if content starts with YAML front matter
        if (!preg_match('/^---\s*\n/', $content)) {
            $errors[] = "File must start with YAML front matter (---)";
            return ['valid' => false, 'errors' => $errors];
        }
        
        // Extract YAML front matter
        if (!preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $content, $matches)) {
            $errors[] = "Invalid YAML front matter format";
            return ['valid' => false, 'errors' => $errors];
        }
        
        try {
            $yaml = yaml_parse($matches[1]);
        } catch (Exception $e) {
            $errors[] = "YAML parsing error: " . $e->getMessage();
            return ['valid' => false, 'errors' => $errors];
        }
        
        // 1. Check for deprecated fields
        $deprecated_fields = ['lupopedia.version', 'system_version'];
        foreach ($deprecated_fields as $field) {
            if (isset($yaml[$field]) || strpos($matches[1], $field) !== false) {
                $errors[] = "Deprecated field '$field' found. Remove it.";
            }
        }
        
        // 2. Check web_path includes /lupopedia/
        if (isset($yaml['lupopedia.headers']['web_path'])) {
            $web_path = $yaml['lupopedia.headers']['web_path'];
            if (strpos($web_path, '/lupopedia/') === false) {
                $errors[] = "web_path must include '/lupopedia/' subdirectory. Found: " . $web_path;
            }
        }
        
        // Get federation_node_id (default to 1)
        $fedNodeId = isset($yaml['lupopedia.headers']['federation_node_id']) 
            ? (int)$yaml['lupopedia.headers']['federation_node_id'] 
            : 1;
        
        // 3. Check for hardcoded version strings
        if (preg_match('/version:\s*"4\./', $matches[1])) {
            $errors[] = "Hardcoded version string found. Use when_updated instead.";
        }
        
        // 4. Optional: content_id (only if artifact has been imported)
        if (preg_match('/content_id:\s*(\d+)/', $matches[1], $contentIdMatch)) {
            $contentId = $contentIdMatch[1];
            // Validate content_id is numeric
            if (!is_numeric($contentId)) {
                $errors[] = "content_id must be numeric. Found: " . $contentId;
            }
        }
        
        // Validate web_path based on federation node
        if (isset($yaml['lupopedia.headers']['web_path'])) {
            $web_path = $yaml['lupopedia.headers']['web_path'];
            $is_internal = strpos($web_path, '/lupopedia/') !== false;
            
            if (!$is_internal) {
                // External: federation_node_id required
                if (!preg_match('/federation_node_id:\s*(\d+)/', $yaml, $fed_match)) {
                    return "❌ External web_path requires federation_node_id >= 2";
                }
                $fed_node = (int)$fed_match[1];
                if ($fed_node < 2) {
                    return "❌ federation_node_id must be >= 2 for external web_path (got {$fed_node})";
                }
                
                // Check file location
                $expected_path = "lupo-content/federation_node_id/$fed_node/";
                if (strpos($file_path, $expected_path) !== 0) {
                    error_log("⚠️ External research files should be in $expected_path");
                }
            }
        
        // Validate required sections
        foreach (self::$required_header_fields as $section => $fields) {
            if (!isset($yaml[$section])) {
                $errors[] = "Missing required section: {$section}";
                continue;
            }
            
            foreach ($fields as $field) {
                if (!isset($yaml[$section][$field])) {
                    $errors[] = "Missing required field: {$section}.{$field}";
                }
            }
        }
        
        // Validate specific field formats
        if (isset($yaml['lupopedia.headers']['last_modified_utc'])) {
            if (!preg_match('/^\d{14}$/', $yaml['lupopedia.headers']['last_modified_utc'])) {
                $errors[] = "last_modified_utc must be in YYYYMMDDHHIISS format";
            }
        }
        
        return ['valid' => empty($errors), 'errors' => $errors];
    }
    
    /**
     * Generates a template header for new files
     * @param array $params File parameters
     * @return string Complete YAML header
     */
    public static function generateHeader($params) {
        $defaults = [
            'version' => '4.0.89',
            'schema' => 'documentation',
            'channel_id' => 42,
            'actor_id' => 1,
            'actor_name' => 'wolfie',
            'delegation_chain' => 'wolfie:root',
            'artifact_type' => 'documentation',
            'artifact_kind' => 'general',
            'system_version' => '4.0.89',
            'last_modified_utc' => gmdate('YmdHis'),
            'when_updated' => gmdate('YmdHis'),
            'thread_id' => '4.0.89-' . uniqid(),
            'last_verified_by' => 'wolfie',
            'last_verified_by_actor_id' => 1,
            'orchestrator' => 'wolfie:root'
        ];
        
        $params = array_merge($defaults, $params);
        
        $yaml = "---\n";
        $yaml .= "lupopedia.headers:\n";
        $yaml .= "  lupopedia.version: \"{$params['version']}\"\n";
        $yaml .= "  lupopedia.schema: \"{$params['schema']}\"\n";
        $yaml .= "  file_path_from_root: \"{$params['file_path_from_root']}\"\n";
        $yaml .= "  web_path: \"http://www.lupopedia.com/{$params['file_path_from_root']}\"\n";
        $yaml .= "  last_modified_utc: \"{$params['last_modified_utc']}\"\n";
        $yaml .= "  when_updated: \"{$params['when_updated']}\"\n";
        $yaml .= "  system_version: \"{$params['system_version']}\"\n";
        $yaml .= "  channel_id: {$params['channel_id']}\n";
        $yaml .= "  thread_id: \"{$params['thread_id']}\"\n";
        $yaml .= "  actor_id: {$params['actor_id']}\n";
        $yaml .= "  actor_name: \"{$params['actor_name']}\"\n";
        $yaml .= "  delegation_chain: \"{$params['delegation_chain']}\"\n";
        $yaml .= "  artifact_type: \"{$params['artifact_type']}\"\n";
        $yaml .= "  artifact_kind: \"{$params['artifact_kind']}\"\n";
        $yaml .= "  purpose: \"{$params['purpose']}\"\n";
        $yaml .= "  tags:\n";
        if (isset($params['tags']) && is_array($params['tags'])) {
            foreach ($params['tags'] as $tag) {
                $yaml .= "  - \"{$tag}\"\n";
            }
        }
        
        $yaml .= "lupopedia.edges:\n";
        $yaml .= "  outbound_edges:\n";
        if (isset($params['edges']) && is_array($params['edges'])) {
            foreach ($params['edges'] as $edge) {
                $yaml .= "    - to: \"{$edge['to']}\"\n";
                $yaml .= "      type: {$edge['type']}\n";
                $yaml .= "      weight: {$edge['weight']}\n";
                $yaml .= "      reason: \"{$edge['reason']}\"\n";
            }
        }
        
        $yaml .= "lupopedia.footer:\n";
        $yaml .= "  version: \"{$params['version']}\"\n";
        $yaml .= "  last_verified: \"{$params['last_modified_utc']}\"\n";
        $yaml .= "  last_verified_by: \"{$params['last_verified_by']}\"\n";
        $yaml .= "  last_verified_by_actor_id: {$params['last_verified_by_actor_id']}\"\n";
        $yaml .= "  orchestrator: \"{$params['orchestrator']}\"\n";
        $yaml .= "  next_action:\n";
        if (isset($params['next_actions']) && is_array($params['next_actions'])) {
            foreach ($params['next_actions'] as $action) {
                $yaml .= "    - \"{$action}\"\n";
            }
        }
        
        $yaml .= "---\n\n";
        
        return $yaml;
    }
}

// Usage example for validation:
// $result = LupopediaHeaderValidator::validateHeaders($content, $filepath);
// if (!$result['valid']) {
//     throw new Exception("Header validation failed: " . implode(', ', $result['errors']));
// }
?>
