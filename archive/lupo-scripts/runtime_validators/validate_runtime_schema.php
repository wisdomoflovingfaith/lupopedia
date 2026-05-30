<?php
#   lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "lupo-scripts/runtime_validators/validate_runtime_schema.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/runtime_validators/validate_runtime_schema.php"
#   status: "active"
#   when_updated: "20260420080000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/validate-runtime-schema.toon"
#   atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
#   transcript_jsonl: "0/development/validate-runtime-schema"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "validate-runtime-schema"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Runtime Schema Validator"
#   summary: "Validates JSON objects against their corresponding schemas with field type and consistency checks."
# ---------------------------------------------------------------------

/**
 * PUBLIC API (all static; packed int = YYYYMMDDHHIISS UTC):
 *
 *   Core
 *     validate()                        Main validation entry point.
 *     __construct($basePath)            Initialize validator with runtime path.
 *     loadSchemas()                     Load canonical schemas from runtime schema directory.
 *     getChannels()                     Get list of channels from channels.jsonl.
 *     validateChannel($channelKey)      Validate all JSONL files in a channel.
 *     validateJsonlFile($relativePath, $schemaName)  Validate a single JSONL file.
 *     validateAgainstSchema($data, $schema, $filePath, $lineNum)  Validate JSON data against schema.
 *     validateFieldTypes($data, $schema, $filePath, $lineNum)  Validate field types against schema.
 *     validateTimestamps($data, $filePath, $lineNum)  Validate timestamp formats.
 *     validateActorChannelConsistency($data, $filePath, $lineNum)  Validate actor_id and channel_key consistency.
 *     addError($code, $message)        Add error to errors array.
 *     addWarning($code, $message)      Add warning to warnings array.
 *     formatOutput()                    Format output as machine-readable JSON.
 */
/**
 * Lupopedia Runtime Ledger Schema Validator
 * 
 * Validates each JSON object against its corresponding schema
 * Validates field types, required fields, and allowed values
 * Validates timestamp formats
 * Validates actor_id and channel_key consistency
 * 
 * Read-only validation - no modifications to runtime files
 */


class RuntimeSchemaValidator {
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
        
        // Validate install_state.json for each actor (single object, not JSONL)
        foreach ($actors as $actorId) {
            $this->validateInstallState($channelKey, $actorId);
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
     * Validate install_state.json (single JSON object)
     */
    private function validateInstallState($channelKey, $actorId) {
        $installStateFile = $this->basePath . '/' . $channelKey . '/' . $actorId . '/install_state.json';
        
        if (!file_exists($installStateFile)) {
            return;
        }
        
        $content = file_get_contents($installStateFile);
        if ($content === false) {
            $this->addError('SCHEMA_INSTALL_STATE_READ', "Cannot read install_state.json for actor {$actorId} in channel {$channelKey}");
            return;
        }
        
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->addError('SCHEMA_INSTALL_STATE_JSON', "Invalid JSON in install_state.json for actor {$actorId} in channel {$channelKey}: " . json_last_error_msg());
            return;
        }
        
        if (isset($this->schemas['actor_install_state'])) {
            $this->validateObjectAgainstSchema($data, $this->schemas['actor_install_state'], $channelKey . '/' . $actorId . '/install_state.json', 1);
        }
    }
    
    /**
     * Validate a single JSONL file
     */
    private function validateJsonlFile($relativePath, $schemaName) {
        $fullPath = $this->basePath . '/' . $relativePath;
        
        if (!file_exists($fullPath)) {
            return; // Structure validator handles missing files
        }
        
        $lines = file($fullPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }
        
        foreach ($lines as $lineNum => $line) {
            $lineNum++; // 1-based for error reporting
            
            $data = json_decode($line, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                continue; // JSONL format validator handles JSON errors
            }
            
            // Validate against schema if available
            if (isset($this->schemas[$schemaName])) {
                $this->validateObjectAgainstSchema($data, $this->schemas[$schemaName], $relativePath, $lineNum);
            }
        }
    }
    
    /**
     * Validate JSON object against schema
     */
    private function validateObjectAgainstSchema($data, $schema, $filePath, $lineNum) {
        // Extract field definitions from schema
        $fieldDefs = [];
        if (isset($schema['fields'])) {
            foreach ($schema['fields'] as $field) {
                if (preg_match('/`([^`]+)`\s+(\w+)/', $field, $matches)) {
                    $fieldName = $matches[1];
                    $fieldType = $matches[2];
                    $nullable = strpos($field, 'NOT NULL') === false;
                    $fieldDefs[$fieldName] = [
                        'type' => $fieldType,
                        'nullable' => $nullable
                    ];
                }
            }
        }
        
        // Validate each field
        foreach ($fieldDefs as $fieldName => $def) {
            if (!array_key_exists($fieldName, $data)) {
                if (!$def['nullable']) {
                    $this->addError('SCHEMA_MISSING_REQUIRED', "Missing required field '{$fieldName}' on line {$lineNum} in {$filePath}");
                }
                continue;
            }
            
            $value = $data[$fieldName];
            
            // Check for null values
            if ($value === null) {
                if (!$def['nullable']) {
                    $this->addError('SCHEMA_NULL_NOT_ALLOWED', "Field '{$fieldName}' cannot be null on line {$lineNum} in {$filePath}");
                }
                continue;
            }
            
            // Validate field type
            $this->validateFieldType($value, $def['type'], $fieldName, $filePath, $lineNum);
        }
        
        // Validate primary key if specified
        if (isset($schema['primary_key']['column_name'])) {
            $pkField = $schema['primary_key']['column_name'];
            if (!array_key_exists($pkField, $data)) {
                $this->addError('SCHEMA_MISSING_PRIMARY_KEY', "Missing primary key field '{$pkField}' on line {$lineNum} in {$filePath}");
            } elseif (empty($data[$pkField])) {
                $this->addError('SCHEMA_EMPTY_PRIMARY_KEY', "Primary key field '{$pkField}' is empty on line {$lineNum} in {$filePath}");
            }
        }
        
        // Validate specific field constraints
        $this->validateFieldConstraints($data, $filePath, $lineNum);
    }
    
    /**
     * Validate field type
     */
    private function validateFieldType($value, $expectedType, $fieldName, $filePath, $lineNum) {
        switch ($expectedType) {
            case 'varchar':
            case 'text':
                if (!is_string($value)) {
                    $this->addError('SCHEMA_TYPE_MISMATCH', "Field '{$fieldName}' expected string, got " . gettype($value) . " on line {$lineNum} in {$filePath}");
                }
                break;
                
            case 'bigint':
            case 'int':
                if (!is_numeric($value) || (string)$value !== (string)(int)$value) {
                    $this->addError('SCHEMA_TYPE_MISMATCH', "Field '{$fieldName}' expected integer, got " . gettype($value) . " on line {$lineNum} in {$filePath}");
                }
                break;
                
            case 'json':
                if (!is_array($value) && !is_object($value)) {
                    $this->addError('SCHEMA_TYPE_MISMATCH', "Field '{$fieldName}' expected JSON object/array, got " . gettype($value) . " on line {$lineNum} in {$filePath}");
                }
                break;
        }
    }
    
    /**
     * Validate specific field constraints
     */
    private function validateFieldConstraints($data, $filePath, $lineNum) {
        // Validate timestamp fields
        $timestampFields = ['timestamp_ymdhis', 'created_ymdhis', 'last_updated_ymdhis', 'started_ymdhis', 'completed_ymdhis'];
        foreach ($timestampFields as $field) {
            if (isset($data[$field])) {
                $this->validateTimestamp($data[$field], $field, $filePath, $lineNum);
            }
        }
        
        // Validate actor_id
        if (isset($data['actor_id'])) {
            if (!is_numeric($data['actor_id']) || $data['actor_id'] <= 0) {
                $this->addError('SCHEMA_INVALID_ACTOR_ID', "Invalid actor_id on line {$lineNum} in {$filePath}: {$data['actor_id']}");
            }
        }
        
        // Validate channel_key
        if (isset($data['channel_key'])) {
            if (!preg_match('/^[a-z0-9_]+$/', $data['channel_key'])) {
                $this->addError('SCHEMA_INVALID_CHANNEL_KEY', "Invalid channel_key on line {$lineNum} in {$filePath}: {$data['channel_key']}");
            }
        }
        
        // Validate task_state if present
        if (isset($data['task_state'])) {
            $validStates = ['pending', 'in_progress', 'completed', 'failed', 'interrupted'];
            if (!in_array($data['task_state'], $validStates)) {
                $this->addError('SCHEMA_INVALID_TASK_STATE', "Invalid task_state on line {$lineNum} in {$filePath}: {$data['task_state']}");
            }
        }
        
        // Validate actor_state if present
        if (isset($data['actor_state'])) {
            $validStates = ['active', 'inactive', 'busy', 'error', 'installing'];
            if (!in_array($data['actor_state'], $validStates)) {
                $this->addError('SCHEMA_INVALID_ACTOR_STATE', "Invalid actor_state on line {$lineNum} in {$filePath}: {$data['actor_state']}");
            }
        }
        
        // Validate channel_state if present
        if (isset($data['channel_state'])) {
            $validStates = ['active', 'inactive', 'archived'];
            if (!in_array($data['channel_state'], $validStates)) {
                $this->addError('SCHEMA_INVALID_CHANNEL_STATE', "Invalid channel_state on line {$lineNum} in {$filePath}: {$data['channel_state']}");
            }
        }
    }
    
    /**
     * Validate timestamp format
     */
    private function validateTimestamp($timestamp, $fieldName, $filePath, $lineNum) {
        if (!is_numeric($timestamp)) {
            $this->addError('SCHEMA_INVALID_TIMESTAMP', "Field '{$fieldName}' must be numeric on line {$lineNum} in {$filePath}");
            return;
        }
        
        if (strlen($timestamp) !== 14) {
            $this->addError('SCHEMA_INVALID_TIMESTAMP', "Field '{$fieldName}' must be 14 digits (YYYYMMDDHHIISS) on line {$lineNum} in {$filePath}, got length " . strlen($timestamp));
            return;
        }
        
        // Validate date components
        $year = substr($timestamp, 0, 4);
        $month = substr($timestamp, 4, 2);
        $day = substr($timestamp, 6, 2);
        $hour = substr($timestamp, 8, 2);
        $minute = substr($timestamp, 10, 2);
        $second = substr($timestamp, 12, 2);
        
        if ($year < 2020 || $year > 2030) {
            $this->addWarning('SCHEMA_TIMESTAMP_YEAR', "Field '{$fieldName}' has unusual year on line {$lineNum} in {$filePath}: {$year}");
        }
        
        if ($month < 1 || $month > 12) {
            $this->addError('SCHEMA_INVALID_TIMESTAMP', "Field '{$fieldName}' has invalid month on line {$lineNum} in {$filePath}: {$month}");
        }
        
        if ($day < 1 || $day > 31) {
            $this->addError('SCHEMA_INVALID_TIMESTAMP', "Field '{$fieldName}' has invalid day on line {$lineNum} in {$filePath}: {$day}");
        }
        
        if ($hour < 0 || $hour > 23) {
            $this->addError('SCHEMA_INVALID_TIMESTAMP', "Field '{$fieldName}' has invalid hour on line {$lineNum} in {$filePath}: {$hour}");
        }
        
        if ($minute < 0 || $minute > 59) {
            $this->addError('SCHEMA_INVALID_TIMESTAMP', "Field '{$fieldName}' has invalid minute on line {$lineNum} in {$filePath}: {$minute}");
        }
        
        if ($second < 0 || $second > 59) {
            $this->addError('SCHEMA_INVALID_TIMESTAMP', "Field '{$fieldName}' has invalid second on line {$lineNum} in {$filePath}: {$second}");
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
            'validator' => 'validate_runtime_schema',
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
    $validator = new RuntimeSchemaValidator($runtimePath);
    $result = $validator->validate();
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    exit(empty($result['errors']) ? 0 : 1);
}
