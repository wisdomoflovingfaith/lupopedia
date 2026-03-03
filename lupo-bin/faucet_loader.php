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
 * 1. Per-actor (override): lupopedia/channels/lupo-channels/<channel_id>/actors/<actor_id>/faucets.json
 * 2. Channel-wide (override): lupopedia/channels/lupo-channels/<channel_id>/faucets.json
 * 3. ID-scoped (base): lupopedia/actors/faucets/<agent_faucet_id>/faucet.json (via by_actor.json or DB)
 *
 * Uses LUPOPEDIA_PATH or LUPO_DATABASE_DIR for base path so paths are not CWD-dependent.
 *
 * @author Windsurf (1002), Cursor (1003)
 * @version 4.0.56
 */

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(__DIR__));
}
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'bootstrap.php';

class FaucetLoader {
    private $toon_schema = null;
    private $cache = array();
    private $base_path = null;

    public function __construct() {
        $this->resolveBasePath();
        $this->loadToonSchema();
    }

    /**
     * Resolve base path for lupopedia file-based data (lupo-database/lupopedia or equivalent)
     */
    private function resolveBasePath() {
        if ($this->base_path !== null) {
            return;
        }
        if (defined('LUPO_DATABASE_DIR') && LUPO_DATABASE_DIR) {
            $db_dir = rtrim(LUPO_DATABASE_DIR, DIRECTORY_SEPARATOR . '/\\');
            $this->base_path = $db_dir . DIRECTORY_SEPARATOR . 'lupopedia';
        } else {
            $root = defined('LUPOPEDIA_PATH') && LUPOPEDIA_PATH ? rtrim(LUPOPEDIA_PATH, DIRECTORY_SEPARATOR . '/\\') : dirname(__DIR__);
            $this->base_path = $root . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia';
        }
    }

    /**
     * Load TOON schema for validation (canonical path under base)
     */
    private function loadToonSchema() {
        $toon_file = $this->base_path . DIRECTORY_SEPARATOR . 'toon' . DIRECTORY_SEPARATOR . 'lupo_agent_faucets.toon.json';
        if (!file_exists($toon_file)) {
            $legacy = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lupo-docs' . DIRECTORY_SEPARATOR . 'toons' . DIRECTORY_SEPARATOR . 'lupo_agent_faucets.toon.json';
            if (file_exists($legacy)) {
                $toon_file = $legacy;
            }
        }
        if (!file_exists($toon_file)) {
            throw new Exception("TOON schema file not found: " . $toon_file);
        }
        $toon_content = file_get_contents($toon_file);
        $this->toon_schema = json_decode($toon_content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON in TOON schema: " . json_last_error_msg());
        }
    }

    /**
     * Resolve agent_faucet_id for (channel_id, actor_id) from by_actor.json or DB
     */
    private function resolveAgentFaucetId($channel_id, $actor_id) {
        $manifest = $this->base_path . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'faucets' . DIRECTORY_SEPARATOR . 'by_actor.json';
        if (file_exists($manifest)) {
            $json = json_decode(file_get_contents($manifest), true);
            if (json_last_error() === JSON_ERROR_NONE && isset($json['entries']) && is_array($json['entries'])) {
                foreach ($json['entries'] as $entry) {
                    $aid = isset($entry['actor_id']) ? (int) $entry['actor_id'] : null;
                    $domain = isset($entry['domain_id']) ? (int) $entry['domain_id'] : null;
                    if ($aid === (int) $actor_id && $domain === (int) $channel_id) {
                        return isset($entry['agent_faucet_id']) ? (int) $entry['agent_faucet_id'] : null;
                    }
                }
            }
        }
        if (isset($GLOBALS['mydatabase'])) {
            $db = $GLOBALS['mydatabase'];
            $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
            $t = $table_prefix . 'agent_faucets';
            $sql = "SELECT agent_faucet_id FROM " . $t . " WHERE actor_id = :actor_id AND domain_id = :domain_id AND (deleted_ymdhis IS NULL OR deleted_ymdhis = 0) LIMIT 1";
            $row = $db->fetch($sql, array('actor_id' => $actor_id, 'domain_id' => $channel_id));
            if ($row && isset($row['agent_faucet_id'])) {
                return (int) $row['agent_faucet_id'];
            }
        }
        return null;
    }

    /**
     * Load faucet for specific actor in channel
     */
    public function loadFaucet($channel_id, $actor_id) {
        $cache_key = $channel_id . '_' . $actor_id;
        if (isset($this->cache[$cache_key])) {
            return $this->cache[$cache_key];
        }
        $sep = DIRECTORY_SEPARATOR;
        $ch = (string) $channel_id;
        $ac = (string) $actor_id;

        // 1. Per-actor override (canonical path under base)
        $per_actor_file = $this->base_path . $sep . 'channels' . $sep . 'lupo-channels' . $sep . $ch . $sep . 'actors' . $sep . $ac . $sep . 'faucets.json';
        if (file_exists($per_actor_file)) {
            $faucet = $this->loadAndValidate($per_actor_file, $actor_id);
            $this->cache[$cache_key] = $faucet;
            return $faucet;
        }

        // 2. Channel-wide override
        $channel_wide_file = $this->base_path . $sep . 'channels' . $sep . 'lupo-channels' . $sep . $ch . $sep . 'faucets.json';
        if (file_exists($channel_wide_file)) {
            $faucet = $this->loadChannelWideAndGetActor($channel_wide_file, $actor_id);
            if ($faucet !== null) {
                $this->cache[$cache_key] = $faucet;
                return $faucet;
            }
        }

        // 3. ID-scoped base: actors/faucets/<agent_faucet_id>/faucet.json
        $agent_faucet_id = $this->resolveAgentFaucetId($channel_id, $actor_id);
        if ($agent_faucet_id !== null) {
            $id_scoped_file = $this->base_path . $sep . 'actors' . $sep . 'faucets' . $sep . $agent_faucet_id . $sep . 'faucet.json';
            if (file_exists($id_scoped_file)) {
                $faucet = $this->loadAndValidate($id_scoped_file, null);
                $this->cache[$cache_key] = $faucet;
                return $faucet;
            }
        }

        throw new Exception("Missing faucet for actor " . $actor_id . " in channel " . $channel_id . ". No per-actor, channel-wide, or ID-scoped faucet found.");
    }
    
    /**
     * Load channel-wide faucets file and return single faucet for actor_id, or null
     */
    private function loadChannelWideAndGetActor($file_path, $actor_id) {
        if (!file_exists($file_path)) {
            return null;
        }
        $content = file_get_contents($file_path);
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['faucets']) || !is_array($data['faucets'])) {
            return null;
        }
        foreach ($data['faucets'] as $faucet) {
            if (isset($faucet['actor_id']) && (int) $faucet['actor_id'] === (int) $actor_id) {
                $this->validateSchema($faucet, $file_path);
                return $faucet;
            }
        }
        return null;
    }

    /**
     * Load and validate faucet file against TOON schema.
     * If $actor_id is set and root has "faucets" array, extract first matching by actor_id; else treat root as single faucet.
     */
    private function loadAndValidate($file_path, $actor_id = null) {
        if (!file_exists($file_path)) {
            throw new Exception("Faucet file not found: " . $file_path);
        }
        $content = file_get_contents($file_path);
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON in faucet file " . $file_path . ": " . json_last_error_msg());
        }
        if (isset($data['faucets']) && is_array($data['faucets'])) {
            $found = null;
            if ($actor_id !== null) {
                foreach ($data['faucets'] as $f) {
                    if (isset($f['actor_id']) && (int) $f['actor_id'] === (int) $actor_id) {
                        $found = $f;
                        break;
                    }
                }
            }
            if ($found === null && count($data['faucets']) > 0) {
                $found = $data['faucets'][0];
            }
            $data = $found !== null ? $found : $data;
        }
        $this->validateSchema($data, $file_path);
        return $data;
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
