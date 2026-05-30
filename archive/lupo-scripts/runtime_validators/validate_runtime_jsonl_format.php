<?php
#   lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "lupo-scripts/runtime_validators/validate_runtime_jsonl_format.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/runtime_validators/validate_runtime_jsonl_format.php"
#   status: "active"
#   when_updated: "20260420080000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/validate-runtime-jsonl-format.toon"
#   atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
#   transcript_jsonl: "0/development/validate-runtime-jsonl-format"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "validate-runtime-jsonl-format"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Runtime JSONL Format Validator"
#   summary: "Validates JSONL format correctness including ASCII-only content and required fields per schema."
/**
 * PUBLIC API (all static; packed int = YYYYMMDDHHIISS UTC):
 *
 *   Core
 *     validate()                        Main validation entry point.
 *     __construct($basePath)            Initialize validator with runtime path.
 *     loadSchemas()                     Load JSON schemas from runtime directory.
 *     getChannels()                     Get list of channels from channels.jsonl.
 *     validateChannel($channelKey)      Validate all JSONL files in a channel.
 *     getActorsInChannel($channelKey)   Get list of actors in a channel.
 *     validateActor($channelKey, $actorId)  Validate all JSONL files for an actor.
 *     validateJsonlFile($relativePath, $schemaName)  Validate a single JSONL file.
 *     validateAgainstSchema($data, $schema, $filePath, $lineNum)  Validate JSON data against schema.
 *     addError($code, $message)        Add error to errors array.
 *     addWarning($code, $message)      Add warning to warnings array.
 *     formatOutput()                    Format output as machine-readable JSON.
 */
/**
 * Lupopedia Runtime Ledger JSONL Format Validator
 * 
 * Validates JSONL correctness (one JSON object per line)
 * Validates ASCII-only content
 * Validates required fields per schema
 * Validates no trailing commas, no trailing whitespace
 * 
 * Read-only validation - no modifications to runtime files
 */
# ---------------------------------------------------------------------

class RuntimeJsonlFormatValidator {
    private $errors = [];
    private $warnings = [];
    private $basePath;
    private $schemas = [];
    
    public function __construct($basePath = null) {
        $this->basePath = $basePath ?: dirname(__DIR__, 2) . '/lupo-runtime';
        $this->loadSchemas();
    }
    
    /**
     * Load canonical schemas from runtime schema directory
     */
    private function loadSchemas() {
        $schemaPath = dirname(__DIR__, 2) . '/lupo-docs/database/lupopedia/tables/runtime';
        
        $schemaFiles = [
            'global_channels.json' => 'global_channels',
            'channel_actors.json' => 'channel_actors',
            'actor_tasks.json' => 'actor_tasks',
            'actor_interrupts.json' => 'actor_interrupts',
            'actor_dependencies.json' => 'actor_dependencies',
            'actor_install_state.json' => 'actor_install_state'
        ];
        
        foreach ($schemaFiles as $file => $schemaName) {
            $filePath = $schemaPath . '/' . $file;
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                if ($content !== false) {
                    $schema = json_decode($content, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $this->schemas[$schemaName] = $schema;
                    }
                }
            }
        }
    }
    
    /**
     * Main validation entry point
     */
    public function validate() {
        $this->errors = [];
        $this->warnings = [];
        
        // Validate global channels.jsonl
        $this->validateJsonlFile('channels.jsonl', 'global_channels');
        
        // Get channels and validate each
        $channels = $this->getChannels();
        foreach ($channels as $channel) {
            $this->validateChannel($channel);
        }
        
        return $this->formatOutput();
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
     * Validate all JSONL files in a channel
     */
    private function validateChannel($channelKey) {
        $channelPath = $this->basePath . '/' . $channelKey;
        
        if (!is_dir($channelPath)) {
            return;
        }
        
        // Validate channel actors.jsonl
        $this->validateJsonlFile($channelKey . '/actors.jsonl', 'channel_actors');
        
        // Get actors in this channel
        $actors = $this->getActorsInChannel($channelKey);
        
        // Validate each actor's JSONL files
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
     * Validate all JSONL files for an actor
     */
    private function validateActor($channelKey, $actorId) {
        $actorPath = $this->basePath . '/' . $channelKey . '/' . $actorId;
        
        if (!is_dir($actorPath)) {
            return;
        }
        
        $jsonlFiles = [
            'tasks.jsonl' => 'actor_tasks',
            'interrupts.jsonl' => 'actor_interrupts',
            'dependencies.jsonl' => 'actor_dependencies'
        ];
        
        foreach ($jsonlFiles as $file => $schemaName) {
            $this->validateJsonlFile($channelKey . '/' . $actorId . '/' . $file, $schemaName);
        }
    }
    
    /**
     * Validate a single JSONL file
     */
    private function validateJsonlFile($relativePath, $schemaName) {
        $fullPath = $this->basePath . '/' . $relativePath;
        
        if (!file_exists($fullPath)) {
            $this->addError('JSONL_FILE_MISSING', "JSONL file missing: {$relativePath}");
            return;
        }
        
        if (!is_readable($fullPath)) {
            $this->addError('JSONL_NOT_READABLE', "JSONL file not readable: {$relativePath}");
            return;
        }
        
        $content = file_get_contents($fullPath);
        if ($content === false) {
            $this->addError('JSONL_READ_ERROR', "Cannot read JSONL file: {$relativePath}");
            return;
        }
        
        // Check for empty file (allowed for new files)
        if (trim($content) === '') {
            $this->addWarning('JSONL_EMPTY', "JSONL file is empty: {$relativePath}");
            return;
        }
        
        // Split into lines and validate each
        $lines = explode("\n", $content);
        $lineCount = count($lines);
        
        foreach ($lines as $lineNum => $line) {
            $lineNum++; // 1-based for error reporting
            
            // Skip empty lines (should not exist in valid JSONL)
            if (trim($line) === '') {
                if ($lineNum < $lineCount) { // Not just trailing newline
                    $this->addError('JSONL_EMPTY_LINE', "Empty line {$lineNum} in {$relativePath}");
                }
                continue;
            }
            
            // Check for trailing whitespace
            if (strlen($line) !== strlen(rtrim($line))) {
                $this->addError('JSONL_TRAILING_WHITESPACE', "Trailing whitespace on line {$lineNum} in {$relativePath}");
            }
            
            // Check for non-ASCII characters
            if (preg_match('/[^\x00-\x7F]/', $line)) {
                $this->addError('JSONL_NON_ASCII', "Non-ASCII character on line {$lineNum} in {$relativePath}");
            }
            
            // Validate JSON format
            $data = json_decode($line, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->addError('JSONL_INVALID_JSON', "Invalid JSON on line {$lineNum} in {$relativePath}: " . json_last_error_msg());
                continue;
            }
            
            // Validate against schema if available
            if (isset($this->schemas[$schemaName])) {
                $this->validateAgainstSchema($data, $this->schemas[$schemaName], $relativePath, $lineNum);
            }
        }
        
        // Check for trailing newlines (should end with single newline)
        if (substr($content, -1) !== "\n") {
            $this->addWarning('JSONL_NO_FINAL_NEWLINE', "File does not end with newline: {$relativePath}");
        } elseif (substr($content, -2) === "\n\n") {
            $this->addWarning('JSONL_EXTRA_NEWLINES', "File ends with multiple newlines: {$relativePath}");
        }
    }
    
    /**
     * Validate JSON data against schema
     */
    private function validateAgainstSchema($data, $schema, $filePath, $lineNum) {
        // Validate required fields
        if (isset($schema['fields'])) {
            $requiredFields = [];
            foreach ($schema['fields'] as $field) {
                // Extract field name from SQL-style definition
                if (preg_match('/`([^`]+)`/', $field, $matches)) {
                    $fieldName = $matches[1];
                    if (strpos($field, 'NOT NULL') !== false) {
                        $requiredFields[] = $fieldName;
                    }
                }
            }
            
            foreach ($requiredFields as $field) {
                if (!array_key_exists($field, $data)) {
                    $this->addError('JSONL_MISSING_REQUIRED', "Missing required field '{$field}' on line {$lineNum} in {$filePath}");
                }
            }
        }
        
        // Validate primary key if specified
        if (isset($schema['primary_key']['column_name'])) {
            $pkField = $schema['primary_key']['column_name'];
            if (!array_key_exists($pkField, $data)) {
                $this->addError('JSONL_MISSING_PRIMARY_KEY', "Missing primary key field '{$pkField}' on line {$lineNum} in {$filePath}");
            } elseif (empty($data[$pkField])) {
                $this->addError('JSONL_EMPTY_PRIMARY_KEY', "Primary key field '{$pkField}' is empty on line {$lineNum} in {$filePath}");
            }
        }
        
        // Validate timestamp fields
        $timestampFields = ['timestamp_ymdhis', 'created_ymdhis', 'last_updated_ymdhis', 'started_ymdhis', 'completed_ymdhis'];
        foreach ($timestampFields as $field) {
            if (isset($data[$field])) {
                if (!is_numeric($data[$field]) || strlen($data[$field]) !== 14) {
                    $this->addError('JSONL_INVALID_TIMESTAMP', "Invalid timestamp format for '{$field}' on line {$lineNum} in {$filePath}: {$data[$field]} (expected YYYYMMDDHHIISS)");
                }
            }
        }
        
        // Validate actor_id and channel_key consistency
        if (isset($data['actor_id']) && !is_numeric($data['actor_id'])) {
            $this->addError('JSONL_INVALID_ACTOR_ID', "Invalid actor_id on line {$lineNum} in {$filePath}: {$data['actor_id']}");
        }
        
        if (isset($data['channel_key']) && !preg_match('/^[a-z0-9_]+$/', $data['channel_key'])) {
            $this->addError('JSONL_INVALID_CHANNEL_KEY', "Invalid channel_key on line {$lineNum} in {$filePath}: {$data['channel_key']}");
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
            'validator' => 'validate_runtime_jsonl_format',
            'timestamp' => date('Y-m-d H:i:s'),
            'runtime_path' => $this->basePath,
            'schemas_loaded' => array_keys($this->schemas),
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
    $validator = new RuntimeJsonlFormatValidator($runtimePath);
    $result = $validator->validate();
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    exit(empty($result['errors']) ? 0 : 1);
}
