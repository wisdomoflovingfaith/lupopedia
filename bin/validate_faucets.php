<?php
/**
 * Faucet Validator - Validation CLI Tool for Agent Faucets
 *
 * Recursively scans and validates all faucet files against TOON schema.
 * Scans: (1) channels/ (legacy), (2) database/lupopedia/actors/faucets/<id>/faucet.json (id-scoped).
 *
 * @author Windsurf (1002), Cursor (1003)
 * @version 4.0.56
 */

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(__DIR__));
}
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'bootstrap.php';

class FaucetValidator {
    private $toon_schema = null;
    private $errors = array();
    private $warnings = array();
    private $stats = array(
        'total_files' => 0,
        'valid_files' => 0,
        'invalid_files' => 0,
        'total_faucets' => 0,
        'channels' => array(),
        'default_faucets' => array(),
        'slug_duplicates' => array(),
        'actor_mismatches' => array(),
        'id_scoped_faucets' => 0
    );
    private $base_path = null;

    public function __construct() {
        $this->resolveBasePath();
        $this->loadToonSchema();
    }

    private function resolveBasePath() {
        if (defined('LUPO_DATABASE_DIR') && LUPO_DATABASE_DIR) {
            $this->base_path = rtrim(LUPO_DATABASE_DIR, DIRECTORY_SEPARATOR . '/\\') . DIRECTORY_SEPARATOR . 'lupopedia';
        } else {
            $root = defined('LUPOPEDIA_PATH') && LUPOPEDIA_PATH ? rtrim(LUPOPEDIA_PATH, DIRECTORY_SEPARATOR . '/\\') : dirname(__DIR__);
            $this->base_path = $root . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'lupopedia';
        }
    }

    /**
     * Load TOON schema (canonical path first, then legacy)
     */
    private function loadToonSchema() {
        $toon_file = $this->base_path . DIRECTORY_SEPARATOR . 'toon' . DIRECTORY_SEPARATOR . 'lupo_agent_faucets.toon.json';
        if (!file_exists($toon_file)) {
            $toon_file = (defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'toons' . DIRECTORY_SEPARATOR . 'lupo_agent_faucets.toon.json';
        }
        if (!file_exists($toon_file)) {
            $toon_file = (defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'toons' . DIRECTORY_SEPARATOR . 'lupo_agent_faucets.toon.json';
        }
        if (!file_exists($toon_file)) {
            throw new Exception("TOON schema file not found");
        }
        $toon_content = file_get_contents($toon_file);
        $this->toon_schema = json_decode($toon_content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON in TOON schema: " . json_last_error_msg());
        }
    }

    /**
     * Recursively scan and validate all faucet files (channels + id-scoped)
     */
    public function validateAll() {
        $this->scanDirectory('channels');
        $this->scanIdScopedFaucets();
        $this->outputResults();
    }

    /**
     * Scan database/lupopedia/actors/faucets/<id>/faucet.json
     */
    private function scanIdScopedFaucets() {
        $faucets_dir = $this->base_path . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'faucets';
        if (!is_dir($faucets_dir)) {
            return;
        }
        $items = scandir($faucets_dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $faucets_dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $faucet_file = $path . DIRECTORY_SEPARATOR . 'faucet.json';
                if (file_exists($faucet_file)) {
                    $this->validateFile($faucet_file, 'id-scoped', $item, null);
                    $this->stats['id_scoped_faucets']++;
                }
            }
        }
    }
    
    /**
     * Scan channels directory for faucet files
     */
    private function scanDirectory($base_path) {
        if (!is_dir($base_path)) {
            return;
        }
        
        $items = scandir($base_path);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            
            $full_path = $base_path . '/' . $item;
            
            if (is_dir($full_path)) {
                $this->scanChannelDirectory($full_path, $item);
            }
        }
    }
    
    /**
     * Scan individual channel directory
     */
    private function scanChannelDirectory($channel_path, $channel_id) {
        // Check for channel-wide faucets
        $channel_faucets = $channel_path . '/faucets.json';
        if (file_exists($channel_faucets)) {
            $this->validateFile($channel_faucets, 'channel-wide', $channel_id);
        }
        
        // Check for per-actor faucets
        $actors_path = $channel_path . '/actors';
        if (is_dir($actors_path)) {
            $this->scanActorsDirectory($actors_path, $channel_id);
        }
        
        // Initialize channel stats
        if (!isset($this->stats['channels'][$channel_id])) {
            $this->stats['channels'][$channel_id] = [
                'channel_wide_faucets' => 0,
                'per_actor_faucets' => 0,
                'actors' => []
            ];
        }
    }
    
    /**
     * Scan actors directory within channel
     */
    private function scanActorsDirectory($actors_path, $channel_id) {
        $items = scandir($actors_path);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            
            $actor_path = $actors_path . '/' . $item;
            
            if (is_dir($actor_path)) {
                $faucet_file = $actor_path . '/faucets.json';
                if (file_exists($faucet_file)) {
                    $this->validateFile($faucet_file, 'per-actor', $channel_id, $item);
                    
                    // Track actor in channel stats
                    $this->stats['channels'][$channel_id]['actors'][] = $item;
                    $this->stats['channels'][$channel_id]['per_actor_faucets']++;
                    
                    // Validate directory/JSON actor_id match
                    $faucet_content = json_decode(file_get_contents($faucet_file), true);
                    if ($faucet_content && isset($faucet_content['actor_id'])) {
                        if ($faucet_content['actor_id'] != $item) {
                            $this->stats['actor_mismatches'][] = [
                                'file' => $faucet_file,
                                'directory_actor_id' => $item,
                                'json_actor_id' => $faucet_content['actor_id']
                            ];
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Validate individual faucet file
     */
    private function validateFile($file_path, $type, $channel_id, $actor_id = null) {
        $this->stats['total_files']++;
        try {
            $content = file_get_contents($file_path);
            $faucets = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->addError($file_path, "Invalid JSON: " . json_last_error_msg());
                $this->stats['invalid_files']++;
                return;
            }
            if ($type === 'channel-wide') {
                $this->validateChannelWideFaucets($faucets, $file_path, $channel_id);
            } elseif ($type === 'id-scoped') {
                $this->stats['total_faucets']++;
                $this->validateSingleFaucet($faucets, $file_path, "root");
            } else {
                if (isset($faucets['faucets']) && is_array($faucets['faucets']) && count($faucets['faucets']) > 0) {
                    $faucets = $faucets['faucets'][0];
                }
                $this->validatePerActorFaucets($faucets, $file_path, $channel_id, $actor_id);
            }
            $this->stats['valid_files']++;
        } catch (Exception $e) {
            $this->addError($file_path, $e->getMessage());
            $this->stats['invalid_files']++;
        }
    }
    
    /**
     * Validate channel-wide faucets
     */
    private function validateChannelWideFaucets($faucets, $file_path, $channel_id) {
        if (!isset($faucets['faucets']) || !is_array($faucets['faucets'])) {
            $this->addError($file_path, "Missing 'faucets' array in channel-wide file");
            return;
        }
        
        $this->stats['channels'][$channel_id]['channel_wide_faucets'] = count($faucets['faucets']);
        $this->stats['total_faucets'] += count($faucets['faucets']);
        
        foreach ($faucets['faucets'] as $index => $faucet) {
            $this->validateSingleFaucet($faucet, $file_path, "faucets[{$index}]");
            
            // Track is_default = 1 enforcement
            if (isset($faucet['is_default']) && $faucet['is_default'] == 1) {
                $actor_id = $faucet['actor_id'];
                if (isset($this->stats['default_faucets'][$channel_id][$actor_id])) {
                    $this->addError($file_path, "Multiple is_default = 1 faucets for actor {$actor_id} in channel {$channel_id}");
                } else {
                    $this->stats['default_faucets'][$channel_id][$actor_id] = true;
                }
            }
            
            // Track duplicate slugs across channels
            $slug = $faucet['slug'];
            if (isset($this->stats['slug_duplicates'][$slug])) {
                $this->addError($file_path, "Duplicate slug '{$slug}' found in channel {$channel_id} (already exists in {$this->stats['slug_duplicates'][$slug]})");
            } else {
                $this->stats['slug_duplicates'][$slug] = $channel_id;
            }
        }
    }
    
    /**
     * Validate per-actor faucets
     */
    private function validatePerActorFaucets($faucets, $file_path, $channel_id, $actor_id) {
        $this->stats['total_faucets']++;
        $this->validateSingleFaucet($faucets, $file_path, "root");
    }
    
    /**
     * Validate single faucet against TOON schema
     */
    private function validateSingleFaucet($faucet, $file_path, $path) {
        $required_fields = [
            'agent_faucet_id', 'actor_id', 'name', 'slug', 'description',
            'style_preset', 'model_name', 'provider', 'temperature', 'top_p',
            'max_tokens', 'presence_penalty', 'frequency_penalty', 'system_prompt',
            'safety_json', 'response_format', 'capabilities_json', 'is_default',
            'domain_id', 'created_ymdhis', 'updated_ymdhis', 'deleted_ymdhis'
        ];
        
        foreach ($required_fields as $field) {
            if (!isset($faucet[$field])) {
                $this->addError($file_path, "Missing required field '{$field}' at {$path}");
            }
            
            // Enforce non-null required fields
            if ($faucet[$field] === null || $faucet[$field] === '') {
                $this->addError($file_path, "Required field '{$field}' cannot be null or empty at {$path}");
            }
        }
        
        // Enforce deleted_ymdhis == 0 for active faucets
        if (isset($faucet['deleted_ymdhis']) && $faucet['deleted_ymdhis'] != 0) {
            $this->addError($file_path, "Active faucets must have deleted_ymdhis = 0, got {$faucet['deleted_ymdhis']} at {$path}");
        }
        
        // Validate field types
        $this->validateFieldTypes($faucet, $file_path, $path);
        $this->rejectVendorEmotionalCollision($faucet, $file_path, $path);
    }

    /**
     * Block Samsung / phone-network identity leakage (phonetic collision with SAMSAṂ only).
     */
    private function rejectVendorEmotionalCollision($faucet, $file_path, $path) {
        $loaderClass = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'EmotionalFaucetPluginLoader.php';
        if (!class_exists('EmotionalFaucetPluginLoader') && file_exists($loaderClass)) {
            require_once $loaderClass;
        }
        $tokens = array();
        foreach (array('name', 'slug', 'alias_name') as $field) {
            if (isset($faucet[$field]) && is_string($faucet[$field]) && $faucet[$field] !== '') {
                $tokens[] = $faucet[$field];
            }
        }
        if (class_exists('EmotionalFaucetPluginLoader')) {
            $emo = new EmotionalFaucetPluginLoader($this->base_path);
            foreach ($tokens as $token) {
                if ($emo->isVendorCollisionToken($token)) {
                    $this->addError($file_path, "Vendor collision blocked at {$path}: '{$token}' is Samsung/phone-network external metadata, not Lupopedia (SAMSAṂ phonetic collision only).");
                }
            }
            if (isset($faucet['edges']) && is_array($faucet['edges'])) {
                $filtered = $emo->filterVendorRelatedEdges($faucet['edges']);
                if (count($filtered) !== count($faucet['edges'])) {
                    $this->addError($file_path, "Vendor-related edges rejected at {$path}: phone-network/OEM edges are not part of Lupopedia emotional architecture.");
                }
            }
            return;
        }
        foreach ($tokens as $token) {
            $lower = strtolower($token);
            if (strpos($lower, 'samsung') !== false || strpos($lower, 'phone_network') !== false) {
                $this->addError($file_path, "Vendor collision blocked at {$path}: Samsung/phone-network metadata is external, not Lupopedia.");
            }
        }
    }
    
    /**
     * Validate field types against TOON schema
     */
    private function validateFieldTypes($faucet, $file_path, $path) {
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
                        $this->addWarning($file_path, "Field '{$field}' should be integer at {$path}, got {$actual_type}");
                    }
                    break;
                    
                case 'float':
                    if ($actual_type !== 'double' && $actual_type !== 'integer') {
                        $this->addWarning($file_path, "Field '{$field}' should be float at {$path}, got {$actual_type}");
                    }
                    break;
                    
                case 'string':
                    if ($actual_type !== 'string') {
                        $this->addWarning($file_path, "Field '{$field}' should be string at {$path}, got {$actual_type}");
                    }
                    break;
                    
                case 'array':
                    if ($actual_type !== 'array') {
                        $this->addWarning($file_path, "Field '{$field}' should be array at {$path}, got {$actual_type}");
                    }
                    break;
            }
        }
    }
    
    /**
     * Add validation error
     */
    private function addError($file_path, $message) {
        $this->errors[] = "ERROR: {$file_path} - {$message}";
    }
    
    /**
     * Add validation warning
     */
    private function addWarning($file_path, $message) {
        $this->warnings[] = "WARNING: {$file_path} - {$message}";
    }
    
    /**
     * Output validation results
     */
    private function outputResults() {
        echo "=== Faucet Validation Report ===\n";
        echo "Total Files: {$this->stats['total_files']}\n";
        echo "Valid Files: {$this->stats['valid_files']}\n";
        echo "Invalid Files: {$this->stats['invalid_files']}\n";
        echo "Total Faucets: {$this->stats['total_faucets']}\n\n";
        
        // Channel breakdown
        echo "=== Channel Breakdown ===\n";
        foreach ($this->stats['channels'] as $channel_id => $channel_stats) {
            echo "Channel {$channel_id}:\n";
            echo "  Channel-wide faucets: {$channel_stats['channel_wide_faucets']}\n";
            echo "  Per-actor faucets: {$channel_stats['per_actor_faucets']}\n";
            if (!empty($channel_stats['actors'])) {
                echo "  Actors: " . implode(', ', $channel_stats['actors']) . "\n";
            }
            echo "\n";
        }
        
        // Errors and warnings
        if (!empty($this->errors)) {
            echo "=== ERRORS ===\n";
            foreach ($this->errors as $error) {
                echo "{$error}\n";
            }
        }
        
        if (!empty($this->warnings)) {
            echo "=== WARNINGS ===\n";
            foreach ($this->warnings as $warning) {
                echo "{$warning}\n";
            }
        }
        
        // Hardening validation results
        echo "\n=== HARDENING VALIDATION ===\n";
        foreach ($this->stats['default_faucets'] as $channel_id => $default_faucets) {
            foreach ($default_faucets as $actor_id => $is_default) {
                if ($is_default) {
                    echo "Channel {$channel_id}: Actor {$actor_id} has is_default = 1\n";
                }
            }
        }
        
        foreach ($this->stats['slug_duplicates'] as $slug => $channel_id) {
            echo "Duplicate slug '{$slug}' found in channels: " . implode(', ', array_keys($this->stats['slug_duplicates'], $slug)) . "\n";
        }
        
        foreach ($this->stats['actor_mismatches'] as $mismatch) {
            echo "Actor ID mismatch in {$mismatch['file']}: directory={$mismatch['directory_actor_id']}, json={$mismatch['json_actor_id']}\n";
        }
        
        // Exit with non-zero code on errors
        if (!empty($this->errors)) {
            echo "\nVALIDATION FAILED\n";
            exit(1);
        } else {
            echo "\nVALIDATION PASSED\n";
            exit(0);
        }
    }
}

// CLI Interface
if (php_sapi_name() === 'cli') {
    try {
        $validator = new FaucetValidator();
        $validator->validateAll();
    } catch (Exception $e) {
        echo "FATAL ERROR: " . $e->getMessage() . "\n";
        exit(1);
    }
}
?>
