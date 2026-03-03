<?php
<?php
/**
 * FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
 */
---
flare.headers:
  file_path_from_root: "bin/faucet_loader.php"
  file_hash: "<?php echo hash_file('bin/faucet_loader.php'); ?>"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  last_modified_utc: "<?php echo gmdate('YmdHis'); ?>"
  delegation_chain: "10000:1002"
  artifact_type: "php_script"
  purpose: "Load agent faucets with proper override hierarchy and schema validation"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["faucets", "loader", "validation", "hierarchy"]
  lupo_agent: "windsurf"

flare.edges:
  file_path_from_root: "bin/faucet_loader.php"
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_agent_faucets.toon.json", type: "references", weight: 1.0 }
    - { to: "channels/42/faucets.json", type: "references", weight: 0.9 }
    - { to: "bin/validate_faucets.php", type: "references", weight: 0.8 }
  semantic_tags: ["faucets", "loader", "runtime", "validation"]

  last_updated_utc: "<?php echo gmdate('YmdHis'); ?>"
  system_version: "4.0.50"
flare.footer:
  last_verified_utc: "<?php echo gmdate('YmdHis'); ?>"
  last_verified_by: "windsurf"
---

/**
 * Faucet Loader - Runtime Integration for Agent Faucets
 * 
 * Loads agent faucets with proper override hierarchy:
 * 1. Per-actor: channels/<channel_id>/actors/<actor_id>/faucets.json
 * 2. Channel-wide: channels/<channel_id>/faucets.json
 * 
 * @author Windsurf (1002)
 * @version 4.0.50
 */

require_once 'lupo-includes/bootstrap.php';

class FaucetLoader {
    private $toon_schema = null;
    private $cache = [];
    
    public function __construct() {
        $this->loadToonSchema();
    }
    
    /**
     * Load TOON schema for validation
     */
    private function loadToonSchema() {
        $toon_file = 'lupo-database/lupopedia/toon/lupo_agent_faucets.toon.json';
        
        if (!file_exists($toon_file)) {
            throw new Exception("TOON schema file not found: {$toon_file}");
        }
        
        $toon_content = file_get_contents($toon_file);
        $this->toon_schema = json_decode($toon_content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON in TOON schema: " . json_last_error_msg());
        }
    }
    
    /**
     * Load faucet for specific actor in channel
     */
    public function loadFaucet($channel_id, $actor_id) {
        $cache_key = "{$channel_id}_{$actor_id}";
        
        if (isset($this->cache[$cache_key])) {
            return $this->cache[$cache_key];
        }
        
        // Try per-actor override first
        $per_actor_file = "channels/{$channel_id}/actors/{$actor_id}/faucets.json";
        
        if (file_exists($per_actor_file)) {
            $faucet = $this->loadAndValidate($per_actor_file);
            $this->cache[$cache_key] = $faucet;
            return $faucet;
        }
        
        // Fall back to channel-wide faucets
        $channel_wide_file = "channels/{$channel_id}/faucets.json";
        
        if (!file_exists($channel_wide_file)) {
            throw new Exception("Missing faucet for actor {$actor_id} in channel {$channel_id}. No per-actor or channel-wide faucets found.");
        }
        
        $channel_faucets = $this->loadAndValidate($channel_wide_file);
        
        // Find actor's faucet in channel-wide file
        foreach ($channel_faucets['faucets'] as $faucet) {
            if ($faucet['actor_id'] == $actor_id) {
                $this->cache[$cache_key] = $faucet;
                return $faucet;
            }
        }
        
        throw new Exception("Faucet not found for actor {$actor_id} in channel {$channel_id}");
    }
    
    /**
     * Load and validate faucet file against TOON schema
     */
    private function loadAndValidate($file_path) {
        if (!file_exists($file_path)) {
            throw new Exception("Faucet file not found: {$file_path}");
        }
        
        $content = file_get_contents($file_path);
        $faucet = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON in faucet file {$file_path}: " . json_last_error_msg());
        }
        
        $this->validateSchema($faucet, $file_path);
        
        return $faucet;
    }
    
    /**
     * Validate faucet against TOON schema
     */
    private function validateSchema($faucet, $file_path) {
        $required_fields = [
            'agent_faucet_id', 'actor_id', 'name', 'slug', 'description',
            'style_preset', 'model_name', 'provider', 'temperature', 'top_p',
            'max_tokens', 'presence_penalty', 'frequency_penalty', 'system_prompt',
            'safety_json', 'response_format', 'capabilities_json', 'is_default',
            'domain_id', 'created_ymdhis', 'updated_ymdhis', 'deleted_ymdhis'
        ];
        
        foreach ($required_fields as $field) {
            if (!isset($faucet[$field])) {
                throw new Exception("Missing required field '{$field}' in {$file_path}");
            }
        }
        
        // Validate data types
        $this->validateFieldTypes($faucet, $file_path);
    }
    
    /**
     * Validate field types against TOON schema
     */
    private function validateFieldTypes($faucet, $file_path) {
        $type_rules = [
            'agent_faucet_id' => 'integer',
            'actor_id' => 'integer',
            'name' => 'string',
            'slug' => 'string',
            'description' => 'string',
            'style_preset' => 'string',
            'model_name' => 'string',
            'provider' => 'string',
            'temperature' => 'float',
            'top_p' => 'float',
            'max_tokens' => 'integer',
            'presence_penalty' => 'float',
            'frequency_penalty' => 'float',
            'system_prompt' => 'string',
            'safety_json' => 'array',
            'response_format' => 'string',
            'capabilities_json' => 'string',
            'is_default' => 'integer',
            'domain_id' => 'integer',
            'created_ymdhis' => 'integer',
            'updated_ymdhis' => 'integer',
            'deleted_ymdhis' => 'integer'
        ];
        
        foreach ($type_rules as $field => $expected_type) {
            if (!isset($faucet[$field])) {
                continue; // Already caught in required fields check
            }
            
            $value = $faucet[$field];
            $actual_type = gettype($value);
            
            switch ($expected_type) {
                case 'integer':
                    if ($actual_type !== 'integer' && $actual_type !== 'double') {
                        throw new Exception("Field '{$field}' must be integer in {$file_path}, got {$actual_type}");
                    }
                    break;
                    
                case 'float':
                    if ($actual_type !== 'double' && $actual_type !== 'integer') {
                        throw new Exception("Field '{$field}' must be float in {$file_path}, got {$actual_type}");
                    }
                    break;
                    
                case 'string':
                    if ($actual_type !== 'string') {
                        throw new Exception("Field '{$field}' must be string in {$file_path}, got {$actual_type}");
                    }
                    break;
                    
                case 'array':
                    if ($actual_type !== 'array') {
                        throw new Exception("Field '{$field}' must be array in {$file_path}, got {$actual_type}");
                    }
                    break;
            }
        }
    }
}

// CLI Interface
if (php_sapi_name() === 'cli') {
    $options = getopt('', ['channel:', 'actor:']);
    
    if (!isset($options['channel']) || !isset($options['actor'])) {
        echo "Usage: php bin/faucet_loader.php --channel=<channel_id> --actor=<actor_id>\n";
        echo "Example: php bin/faucet_loader.php --channel=42 --actor=0\n";
        exit(1);
    }
    
    $channel_id = (int)$options['channel'];
    $actor_id = (int)$options['actor'];
    
    try {
        $loader = new FaucetLoader();
        $faucet = $loader->loadFaucet($channel_id, $actor_id);
        
        echo "Faucet loaded successfully:\n";
        echo "Channel: {$channel_id}\n";
        echo "Actor: {$actor_id}\n";
        echo "Name: {$faucet['name']}\n";
        echo "Slug: {$faucet['slug']}\n";
        echo "Description: {$faucet['description']}\n";
        
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        exit(1);
    }
}
?>
