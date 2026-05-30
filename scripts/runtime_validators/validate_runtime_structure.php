<?php
#   lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "scripts/runtime_validators/validate_runtime_structure.php"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/runtime_validators/validate_runtime_structure.php"
#   status: "active"
#   when_updated: "20260420080000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/validate-runtime-structure.toon"
#   atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
#   transcript_jsonl: "0/development/validate-runtime-structure"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "validate-runtime-structure"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Runtime Structure Validator"
#   summary: "Validates runtime directory layout according to PRD 70 §3.1 and ensures required files exist for active actors."
# ---------------------------------------------------------------------
/**
 * PUBLIC API (all static; packed int = YYYYMMDDHHIISS UTC):
 *
 *   Core
 *     validate()                        Main validation entry point.
 *     __construct($basePath)            Initialize validator with runtime path.
 *     validateRootStructure()           Validate root directory structure.
 *     validateChannels()                Validate channel directories exist.
 *     validateActors()                  Validate actor directories and files.
 *     validateChannelActors($channelKey)  Validate actors.jsonl for a channel.
 *     validateActorFiles($channelKey, $actorId)  Validate required files for an actor.
 *     addError($code, $message)        Add error to errors array.
 *     addWarning($code, $message)      Add warning to warnings array.
 *     formatOutput()                    Format output as machine-readable JSON.
 */
/**
 * Lupopedia Runtime Ledger Structure Validator
 * 
 * Validates directory layout according to PRD 70 §3.1
 * Ensures required files exist for each active actor
 * Ensures channel directories exist for all channels in channels.jsonl
 * 
 * Read-only validation - no modifications to runtime files
 */
class RuntimeStructureValidator {
    private $errors = [];
    private $warnings = [];
    private $basePath;
    
    public function __construct($basePath = null) {
        $this->basePath = $basePath ?: dirname(__DIR__, 2) . '/runtime';
    }
    
    /**
     * Main validation entry point
     */
    public function validate() {
        $this->errors = [];
        $this->warnings = [];
        
        // Validate root directory structure
        $this->validateRootStructure();
        
        // Validate global channels.jsonl
        $this->validateGlobalChannels();
        
        // Get channels and validate each
        $channels = $this->getChannels();
        foreach ($channels as $channel) {
            $this->validateChannel($channel);
        }
        
        return $this->formatOutput();
    }
    
    /**
     * Validate root directory structure per PRD 70 §3.1
     */
    private function validateRootStructure() {
        if (!is_dir($this->basePath)) {
            $this->addError('STRUCTURE_ROOT_MISSING', "Runtime directory not found: {$this->basePath}");
            return;
        }
        
        $requiredFiles = ['channels.jsonl'];
        foreach ($requiredFiles as $file) {
            $path = $this->basePath . '/' . $file;
            if (!file_exists($path)) {
                $this->addError('STRUCTURE_MISSING_FILE', "Required file missing: {$file}");
            } elseif (!is_readable($path)) {
                $this->addError('STRUCTURE_NOT_READABLE', "File not readable: {$file}");
            }
        }
    }
    
    /**
     * Validate global channels.jsonl format and content
     */
    private function validateGlobalChannels() {
        $channelsFile = $this->basePath . '/channels.jsonl';
        
        if (!file_exists($channelsFile)) {
            return; // Already reported in validateRootStructure
        }
        
        $lines = file($channelsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            $this->addError('STRUCTURE_CHANNELS_READ', "Cannot read channels.jsonl");
            return;
        }
        
        foreach ($lines as $lineNum => $line) {
            $lineNum++; // 1-based for error reporting
            
            // Check for non-ASCII characters
            if (preg_match('/[^\x00-\x7F]/', $line)) {
                $this->addError('STRUCTURE_NON_ASCII', "Non-ASCII character in channels.jsonl line {$lineNum}");
            }
            
            // Validate JSON format
            $data = json_decode($line, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->addError('STRUCTURE_JSON_INVALID', "Invalid JSON in channels.jsonl line {$lineNum}: " . json_last_error_msg());
                continue;
            }
            
            // Validate required fields
            $required = ['channel_key', 'channel_state'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    $this->addError('STRUCTURE_MISSING_FIELD', "Missing required field '{$field}' in channels.jsonl line {$lineNum}");
                }
            }
            
            // Validate channel_key format
            if (isset($data['channel_key']) && !preg_match('/^[a-z0-9_]+$/', $data['channel_key'])) {
                $this->addError('STRUCTURE_INVALID_CHANNEL_KEY', "Invalid channel_key format in channels.jsonl line {$lineNum}: {$data['channel_key']}");
            }
        }
    }
    
    /**
     * Get list of channels from channels.jsonl
     */
    private function getChannels() {
        $channelsFile = $this->basePath . '/channels.jsonl';
        if (!file_exists($channelsFile)) {
            return [];
        }
        
        $channels = [];
        $lines = file($channelsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($data['channel_key'])) {
                $channels[] = $data['channel_key'];
            }
        }
        
        return array_unique($channels);
    }
    
    /**
     * Validate individual channel directory structure
     */
    private function validateChannel($channelKey) {
        $channelPath = $this->basePath . '/' . $channelKey;
        
        if (!is_dir($channelPath)) {
            $this->addError('STRUCTURE_CHANNEL_MISSING', "Channel directory missing: {$channelKey}");
            return;
        }
        
        // Validate actors.jsonl exists
        $actorsFile = $channelPath . '/actors.jsonl';
        if (!file_exists($actorsFile)) {
            $this->addError('STRUCTURE_ACTORS_MISSING', "actors.jsonl missing for channel: {$channelKey}");
        } elseif (!is_readable($actorsFile)) {
            $this->addError('STRUCTURE_ACTORS_NOT_READABLE', "actors.jsonl not readable for channel: {$channelKey}");
        }
        
        // Get actors in this channel
        $actors = $this->getActorsInChannel($channelKey);
        
        // Validate each actor directory
        foreach ($actors as $actorId) {
            $this->validateActor($channelKey, $actorId);
        }
    }
    
    /**
     * Get list of actors in a channel from actors.jsonl
     */
    private function getActorsInChannel($channelKey) {
        $actorsFile = $this->basePath . '/' . $channelKey . '/actors.jsonl';
        if (!file_exists($actorsFile)) {
            return [];
        }
        
        $actors = [];
        $lines = file($actorsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($data['actor_id'])) {
                $actors[] = $data['actor_id'];
            }
        }
        
        return array_unique($actors);
    }
    
    /**
     * Validate individual actor directory structure
     */
    private function validateActor($channelKey, $actorId) {
        $actorPath = $this->basePath . '/' . $channelKey . '/' . $actorId;
        
        if (!is_dir($actorPath)) {
            $this->addError('STRUCTURE_ACTOR_DIR_MISSING', "Actor directory missing: {$channelKey}/{$actorId}");
            return;
        }
        
        // Validate required files for actor
        $requiredFiles = [
            'tasks.jsonl',
            'interrupts.jsonl', 
            'dependencies.jsonl',
            'install_state.json'
        ];
        
        foreach ($requiredFiles as $file) {
            $filePath = $actorPath . '/' . $file;
            if (!file_exists($filePath)) {
                $this->addError('STRUCTURE_ACTOR_FILE_MISSING', "Required file missing for actor {$actorId} in channel {$channelKey}: {$file}");
            } elseif (!is_readable($filePath)) {
                $this->addError('STRUCTURE_ACTOR_FILE_NOT_READABLE', "File not readable for actor {$actorId} in channel {$channelKey}: {$file}");
            }
        }
        
        // Special validation for install_state.json (single object, not JSONL)
        $installStateFile = $actorPath . '/install_state.json';
        if (file_exists($installStateFile)) {
            $content = file_get_contents($installStateFile);
            if ($content === false) {
                $this->addError('STRUCTURE_INSTALL_STATE_READ', "Cannot read install_state.json for actor {$actorId} in channel {$channelKey}");
            } else {
                // Check for non-ASCII characters
                if (preg_match('/[^\x00-\x7F]/', $content)) {
                    $this->addError('STRUCTURE_INSTALL_STATE_NON_ASCII', "Non-ASCII characters in install_state.json for actor {$actorId} in channel {$channelKey}");
                }
                
                // Validate JSON format
                $data = json_decode($content, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->addError('STRUCTURE_INSTALL_STATE_JSON_INVALID', "Invalid JSON in install_state.json for actor {$actorId} in channel {$channelKey}: " . json_last_error_msg());
                }
            }
        }
    }
    
    /**
     * Add error to errors array
     */
    private function addError($code, $message) {
        $this->errors[] = [
            'code' => $code,
            'message' => $message,
            'severity' => 'error'
        ];
    }
    
    /**
     * Add warning to warnings array
     */
    private function addWarning($code, $message) {
        $this->warnings[] = [
            'code' => $code,
            'message' => $message,
            'severity' => 'warning'
        ];
    }
    
    /**
     * Format output as machine-readable JSON
     */
    private function formatOutput() {
        return [
            'validator' => 'validate_runtime_structure',
            'timestamp' => date('Y-m-d H:i:s'),
            'runtime_path' => $this->basePath,
            'status' => empty($this->errors) ? 'pass' : 'fail',
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'summary' => [
                'error_count' => count($this->errors),
                'warning_count' => count($this->warnings)
            ]
        ];
    }
}

// Command line execution
if (php_sapi_name() === 'cli') {
    $runtimePath = $argv[1] ?? null;
    $validator = new RuntimeStructureValidator($runtimePath);
    $result = $validator->validate();
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    exit(empty($result['errors']) ? 0 : 1);
}
