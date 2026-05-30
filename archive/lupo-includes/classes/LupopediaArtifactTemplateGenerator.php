<?php
/**
 * Lupopedia Artifact Template Generator
 * 
 * Creates new artifacts with single-field versioning model compliance
 * per WOLFIE's doctrine enforcement and ATHENA's enforcement doctrine.
 * 
 * SINGLE-FIELD MODEL: Only version_when_written is stored in artifacts
 * lupopedia.version and system_version are forbidden in new artifact headers
 * 
 * @version 1.0
 * @author HEPHAESTUS (actor_id 3)
 */

require_once __DIR__ . '/../functions/version_resolver.php';
require_once __DIR__ . '/../exceptions/SystemError.php';

/**
 * Lupopedia Artifact Template Generator
 * 
 * Creates artifacts with single-source versioning model:
 * - version_when_written (immutable creation version only)
 * 
 * IMPORTANT: This generator ONLY outputs version_when_written
 * lupopedia.version and system_version are NEVER added to new artifacts
 */
class LupopediaArtifactTemplateGenerator
{
    /**
     * Generate artifact template with single-field versioning
     * 
     * @param array $config Artifact configuration
     * @return string Generated artifact content
     */
    public function generateArtifact($config)
    {
        // Resolve version at creation time (immutable)
        $versionWhenWritten = get_lupopedia_system_version();
        
        // ATHENA ENFORCEMENT DOCTRINE: Strict resolver enforcement
        enforce_resolver_version($versionWhenWritten, 'template_generator');
        
        // Build header with single-field model (version_when_written only)
        $header = $this->buildSingleFieldHeader($config, $versionWhenWritten);
        
        // Generate content
        $content = $header . "\n" . $this->generateContent($config);
        
        return $content;
    }
    
    /**
     * Build single-field header block with only version_when_written
     * 
     * @param array $config Artifact configuration
     * @param string $versionWhenWritten Version at creation time (immutable)
     * @return string YAML header block
     */
    private function buildSingleFieldHeader($config, $versionWhenWritten)
    {
        $timestamp = date('YmdHis');
        
        $header = "---\n";
        $header .= "lupopedia.headers:\n";
        $header .= "  version_when_written: \"{$versionWhenWritten}\"\n";
        $header .= "  file_path_from_root: \"{$config['file_path_from_root']}\"\n";
        $header .= "  web_path: \"{$config['web_path']}\"\n";
        $header .= "  last_modified_utc: \"{$timestamp}\"\n";
        $header .= "  project_id: \"{$config['project_id']}\"\n";
        $header .= "  project_slug: \"{$config['project_slug']}\"\n";
        $header .= "  channel_id: \"{$config['channel_id']}\"\n";
        $header .= "  thread_id: \"{$config['thread_id']}\"\n";
        $header .= "  task_id: \"{$config['task_id']}\"\n";
        $header .= "  actor_id: \"{$config['actor_id']}\"\n";
        $header .= "  actor_name: \"{$config['actor_name']}\"\n";
        $header .= "  delegation_chain: \"{$config['delegation_chain']}\"\n";
        $header .= "  artifact_type: \"{$config['artifact_type']}\"\n";
        $header .= "  artifact_kind: \"{$config['artifact_kind']}\"\n";
        $header .= "  purpose: \"{$config['purpose']}\"\n";
        $header .= "  traits: " . json_encode($config['traits']) . "\n";
        $header .= "  tags: " . json_encode($config['tags']) . "\n";
        $header .= "  message_type: \"{$config['message_type']}\"\n";
        
        return $header;
    }
    
    /**
     * Generate artifact content body
     * 
     * @param array $config Artifact configuration
     * @return string Content body
     */
    private function generateContent($config)
    {
        $content = "\n";
        $content .= "# {$config['title']}\n\n";
        $content .= "{$config['description']}\n\n";
        
        if (isset($config['sections'])) {
            foreach ($config['sections'] as $section) {
                $content .= "## {$section['title']}\n\n";
                $content .= "{$section['content']}\n\n";
            }
        }
        
        return $content;
    }
    
    /**
     * Generate complete artifact with edges and footer
     * 
     * @param array $config Artifact configuration
     * @return string Complete artifact
     */
    public function generateCompleteArtifact($config)
    {
        $content = $this->generateArtifact($config);
        
        // Add edges if present
        if (isset($config['edges'])) {
            $content .= "\nlupopedia.edges:\n";
            $content .= "  outbound_edges:\n";
            foreach ($config['edges'] as $edge) {
                $content .= "    - { to: \"{$edge['to']}\", type: \"{$edge['type']}\", weight: \"{$edge['weight']}\", reason: \"{$edge['reason']}\" }\n";
            }
        }
        
        // Add footer
        $content .= "\nlupopedia.footer:\n";
        $content .= "  version: \"1.0\"\n";
        $content .= "  last_verified: \"" . date('Ymd') . "\"\n";
        $content .= "  last_verified_by: \"{$config['actor_name']}\"\n";
        $content .= "  orchestrator: \"{$config['actor_name']}\"\n";
        $content .= "  next_action:\n";
        foreach ($config['next_actions'] as $action) {
            $content .= "    - \"{$action}\"\n";
        }
        
        return $content;
    }
    
    /**
     * Validate generated artifact against single-field model
     * 
     * @param string $content Generated artifact content
     * @return array Validation result
     */
    public function validateGeneratedArtifact($content)
    {
        // Extract YAML front matter
        if (preg_match('/^---\n(.+?)\n---\n(.+)$/s', $content, $matches)) {
            $yamlContent = $matches[1];
            $headers = $this->parseYamlHeaders($yamlContent);
            
            $validator = new SingleFieldVersioningValidator();
            return $validator->validateSingleFieldVersioning($headers, false);
        }
        
        return array(
            'valid' => false,
            'errors' => array('No YAML front matter found'),
            'warnings' => array()
        );
    }
    
    /**
     * Parse YAML headers from content
     * 
     * @param string $yamlContent YAML content
     * @return array Parsed headers
     */
    private function parseYamlHeaders($yamlContent)
    {
        $headers = array();
        $lines = explode("\n", $yamlContent);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, ':') !== false && !empty($line)) {
                list($key, $value) = explode(':', $line, 2);
                $headers[trim($key)] = trim($value, ' "');
            }
        }
        
        return $headers;
    }
}
?>
