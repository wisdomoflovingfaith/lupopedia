<?php
#   lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "lupo-scripts/runtime_validators/validate_runtime_memory_integration.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/runtime_validators/validate_runtime_memory_integration.php"
#   status: "active"
#   when_updated: "20260420080000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/validate-runtime-memory-integration.toon"
#   atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
#   transcript_jsonl: "0/development/validate-runtime-memory-integration"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "validate-runtime-memory-integration"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Runtime Memory Integration Validator"
#   summary: "Validates that runtime ledger events properly map to memory nodes and edges without inference."
/**
 * Lupopedia Runtime Ledger Memory Integration Validator
 * 
 * Ensures task completion events map to memory nodes
 * Ensures dependencies map to memory edges
 * Ensures handoff events create continuity artifacts
 * Ensures no inference (PRD 38 §11)
 * 
 * Read-only validation - no modifications to runtime files
 */
# ---------------------------------------------------------------------

class RuntimeMemoryIntegrationValidator {
    private $errors = [];
    private $warnings = [];
    private $basePath;
    private $tasksByActor = [];
    private $memoryNodes = [];
    private $memoryEdges = [];
    
    public function __construct($basePath = null) {
        $this->basePath = $basePath ?: dirname(__DIR__, 2) . '/lupo-runtime';
    }
    
    /**
     * Main validation entry point
     */
    public function validate() {
        $this->errors = [];
        $this->warnings = [];
        $this->tasksByActor = [];
        $this->memoryNodes = [];
        $this->memoryEdges = [];
        
        // Load runtime data
        $this->loadRuntimeData();
        
        // Load memory graph data
        $this->loadMemoryData();
        
        // Validate memory integration
        $this->validateTaskMemoryMapping();
        $this->validateDependencyMemoryMapping();
        $this->validateHandoffContinuity();
        $this->validateNoInference();
        
        return $this->formatOutput();
    }
    
    /**
     * Load runtime tasks and dependencies
     */
    private function loadRuntimeData() {
        // Get channels
        $channelsFile = $this->basePath . '/channels.jsonl';
        if (!file_exists($channelsFile)) {
            return;
        }
        
        $channels = [];
        $lines = file($channelsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($data['channel_key'])) {
                $channels[] = $data['channel_key'];
            }
        }
        
        // Load tasks and dependencies for each actor
        foreach ($channels as $channelKey) {
            $actorsFile = $this->basePath . '/' . $channelKey . '/actors.jsonl';
            if (!file_exists($actorsFile)) {
                continue;
            }
            
            $actors = [];
            $actorLines = file($actorsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($actorLines as $line) {
                $data = json_decode($line, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($data['actor_id'])) {
                    $actors[] = $data['actor_id'];
                }
            }
            
            foreach ($actors as $actorId) {
                $this->loadTasksForActor($channelKey, $actorId);
                $this->loadDependenciesForActor($channelKey, $actorId);
            }
        }
    }
    
    /**
     * Load tasks for an actor
     */
    private function loadTasksForActor($channelKey, $actorId) {
        $tasksFile = $this->basePath . '/' . $channelKey . '/' . $actorId . '/tasks.jsonl';
        if (!file_exists($tasksFile)) {
            return;
        }
        
        $key = $channelKey . ':' . $actorId;
        $this->tasksByActor[$key] = [];
        
        $lines = file($tasksFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->tasksByActor[$key][] = $data;
            }
        }
    }
    
    /**
     * Load dependencies for an actor
     */
    private function loadDependenciesForActor($channelKey, $actorId) {
        $depsFile = $this->basePath . '/' . $channelKey . '/' . $actorId . '/dependencies.jsonl';
        if (!file_exists($depsFile)) {
            return;
        }
        
        $key = $channelKey . ':' . $actorId;
        if (!isset($this->dependenciesByActor[$key])) {
            $this->dependenciesByActor[$key] = [];
        }
        
        $lines = file($depsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->dependenciesByActor[$key][] = $data;
            }
        }
    }
    
    /**
     * Load memory graph data
     */
    private function loadMemoryData() {
        $memoryPath = dirname(__DIR__, 2) . '/lupo-memory';
        
        // Load memory nodes (simplified - look for TOON files)
        if (is_dir($memoryPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($memoryPath));
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'toon') {
                    $this->loadMemoryToon($file->getPathname());
                }
            }
        }
    }
    
    /**
     * Load memory data from TOON file
     */
    private function loadMemoryToon($toonFile) {
        $content = file_get_contents($toonFile);
        if ($content === false) {
            return;
        }
        
        // Skip if it's a header file (starts with ---)
        if (strpos(trim($content), '---') === 0) {
            // Find end of header
            $headerEnd = strpos($content, "\n---\n");
            if ($headerEnd !== false) {
                $content = substr($content, $headerEnd + 4);
            }
        }
        
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return;
        }
        
        // Extract memory nodes and edges
        if (isset($data['memory_nodes'])) {
            foreach ($data['memory_nodes'] as $node) {
                $nodeId = $node['node_id'] ?? null;
                if ($nodeId) {
                    $this->memoryNodes[$nodeId] = $node;
                }
            }
        }
        
        if (isset($data['memory_edges'])) {
            foreach ($data['memory_edges'] as $edge) {
                $edgeId = $edge['edge_id'] ?? null;
                if ($edgeId) {
                    $this->memoryEdges[$edgeId] = $edge;
                }
            }
        }
    }
    
    /**
     * Validate task completion events map to memory nodes
     */
    private function validateTaskMemoryMapping() {
        foreach ($this->tasksByActor as $actorKey => $tasks) {
            list($channelKey, $actorId) = explode(':', $actorKey);
            
            foreach ($tasks as $task) {
                $taskState = $task['task_state'] ?? null;
                $taskName = $task['task_name'] ?? null;
                $eventId = $task['event_id'] ?? null;
                
                // Check if completed task has corresponding memory node
                if ($taskState === 'completed' && $taskName) {
                    $foundMemoryNode = false;
                    
                    // Look for memory node related to this task
                    foreach ($this->memoryNodes as $node) {
                        $nodeTitle = $node['title'] ?? '';
                        $nodeContent = $node['content'] ?? '';
                        
                        // Check if node mentions this task
                        if (strpos($nodeTitle, $taskName) !== false || 
                            strpos($nodeContent, $taskName) !== false ||
                            strpos($nodeContent, $eventId) !== false) {
                            $foundMemoryNode = true;
                            break;
                        }
                    }
                    
                    if (!$foundMemoryNode) {
                        $this->addWarning('MEMORY_TASK_NODE_MISSING', 
                            "Completed task '{$taskName}' (event: {$eventId}) for actor {$actorId} in channel {$channelKey} has no corresponding memory node");
                    }
                }
            }
        }
    }
    
    /**
     * Validate dependencies map to memory edges
     */
    private function validateDependencyMemoryMapping() {
        if (!isset($this->dependenciesByActor)) {
            return;
        }
        
        foreach ($this->dependenciesByActor as $actorKey => $dependencies) {
            list($channelKey, $actorId) = explode(':', $actorKey);
            
            foreach ($dependencies as $dep) {
                $depId = $dep['dependency_id'] ?? null;
                $taskName = $dep['task_name'] ?? null;
                $dependsOnTask = $dep['depends_on_task'] ?? null;
                
                if ($depId && $taskName && $dependsOnTask) {
                    $foundMemoryEdge = false;
                    
                    // Look for memory edge related to this dependency
                    foreach ($this->memoryEdges as $edge) {
                        $edgeDesc = $edge['description'] ?? '';
                        $edgeFrom = $edge['from_node_title'] ?? '';
                        $edgeTo = $edge['to_node_title'] ?? '';
                        
                        // Check if edge represents this dependency
                        if ((strpos($edgeDesc, $taskName) !== false && strpos($edgeDesc, $dependsOnTask) !== false) ||
                            (strpos($edgeFrom, $dependsOnTask) !== false && strpos($edgeTo, $taskName) !== false) ||
                            strpos($edgeDesc, $depId) !== false) {
                            $foundMemoryEdge = true;
                            break;
                        }
                    }
                    
                    if (!$foundMemoryEdge) {
                        $this->addWarning('MEMORY_DEPENDENCY_EDGE_MISSING', 
                            "Dependency '{$depId}' ({$taskName} -> {$dependsOnTask}) for actor {$actorId} in channel {$channelKey} has no corresponding memory edge");
                    }
                }
            }
        }
    }
    
    /**
     * Validate handoff events create continuity artifacts
     */
    private function validateHandoffContinuity() {
        foreach ($this->tasksByActor as $actorKey => $tasks) {
            list($channelKey, $actorId) = explode(':', $actorKey);
            
            foreach ($tasks as $task) {
                $handoffTo = $task['handoff_to_actor_id'] ?? null;
                $taskName = $task['task_name'] ?? null;
                $eventId = $task['event_id'] ?? null;
                
                if ($handoffTo && $taskName) {
                    $foundContinuityArtifact = false;
                    
                    // Look for continuity artifact in memory
                    foreach ($this->memoryNodes as $node) {
                        $nodeTitle = $node['title'] ?? '';
                        $nodeContent = $node['content'] ?? '';
                        $nodeType = $node['memory_type'] ?? '';
                        
                        // Check if this is a continuity artifact
                        if (($nodeType === 'handoff' || strpos($nodeTitle, 'handoff') !== false || strpos($nodeTitle, 'continuity') !== false) &&
                            (strpos($nodeContent, $taskName) !== false || 
                             strpos($nodeContent, $eventId) !== false ||
                             strpos($nodeContent, "actor {$actorId}") !== false ||
                             strpos($nodeContent, "actor {$handoffTo}") !== false)) {
                            $foundContinuityArtifact = true;
                            break;
                        }
                    }
                    
                    if (!$foundContinuityArtifact) {
                        $this->addWarning('MEMORY_HANDOFF_CONTINUITY_MISSING', 
                            "Handoff from actor {$actorId} to {$handoffTo} for task '{$taskName}' has no continuity artifact in memory");
                    }
                }
            }
        }
    }
    
    /**
     * Validate no inference (PRD 38 §11)
     */
    private function validateNoInference() {
        // Check that memory nodes don't contain inferred relationships
        foreach ($this->memoryNodes as $nodeId => $node) {
            $content = $node['content'] ?? '';
            $title = $node['title'] ?? '';
            
            // Look for inference indicators
            $inferencePatterns = [
                '/\binferred\b/i',
                '/\bassumed\b/i',
                '/\blikely\b/i',
                '/\bprobably\b/i',
                '/\bseems like\b/i',
                '/\bappears to be\b/i',
                '/\bcould be\b/i',
                '/\bmight be\b/i'
            ];
            
            foreach ($inferencePatterns as $pattern) {
                if (preg_match($pattern, $content) || preg_match($pattern, $title)) {
                    $this->addError('MEMORY_INFERENCE_DETECTED', 
                        "Memory node '{$nodeId}' contains inferred language: '{$title}'");
                    break;
                }
            }
        }
        
        // Check that memory edges don't contain inferred relationships
        foreach ($this->memoryEdges as $edgeId => $edge) {
            $description = $edge['description'] ?? '';
            
            // Look for inference indicators
            $inferencePatterns = [
                '/\binferred\b/i',
                '/\bassumed\b/i',
                '/\blikely\b/i',
                '/\bprobably\b/i'
            ];
            
            foreach ($inferencePatterns as $pattern) {
                if (preg_match($pattern, $description)) {
                    $this->addError('MEMORY_EDGE_INFERENCE', 
                        "Memory edge '{$edgeId}' contains inferred language: '{$description}'");
                    break;
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
            'validator' => 'validate_runtime_memory_integration',
            'timestamp' => date('Y-m-d H:i:s'),
            'runtime_path' => $this->basePath,
            'memory_nodes_found' => count($this->memoryNodes),
            'memory_edges_found' => count($this->memoryEdges),
            'tasks_found' => array_sum(array_map('count', $this->tasksByActor)),
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
    $validator = new RuntimeMemoryIntegrationValidator($runtimePath);
    $result = $validator->validate();
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    exit(empty($result['errors']) ? 0 : 1);
}
