<?php
#   lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "lupo-scripts/runtime_validators/validate_runtime_coordination.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/runtime_validators/validate_runtime_coordination.php"
#   status: "active"
#   when_updated: "20260420080000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/validate-runtime-coordination.toon"
#   atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
#   transcript_jsonl: "0/development/validate-runtime-coordination"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "validate-runtime-coordination"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Runtime Coordination Validator"
#   summary: "Validates runtime ledger coordination including channel isolation, task boundaries, and handoff rules."
/**
 * Lupopedia Runtime Ledger Coordination Validator
 * 
 * Enforces channel_key isolation (PRD 70 §4.1)
 * Enforces task boundary rules (PRD 70 §4.2)
 * Enforces append-only rules for .jsonl files
 * Enforces valid handoff targets
 * Enforces dependency resolution rules
 * 
 * Read-only validation - no modifications to runtime files
 */
# ---------------------------------------------------------------------

class RuntimeCoordinationValidator {
    private $errors = [];
    private $warnings = [];
    private $basePath;
    private $tasksByActor = [];
    private $actorsByChannel = [];
    private $channels = [];
    
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
        $this->actorsByChannel = [];
        $this->channels = [];
        
        // Load runtime data
        $this->loadRuntimeData();
        
        // Validate coordination rules
        $this->validateChannelKeyIsolation();
        $this->validateTaskBoundaries();
        $this->validateAppendOnlyRules();
        $this->validateHandoffTargets();
        $this->validateDependencyResolution();
        
        return $this->formatOutput();
    }
    
    /**
     * Load all runtime data for validation
     */
    private function loadRuntimeData() {
        // Load channels
        $this->loadChannels();
        
        // Load actors by channel
        foreach ($this->channels as $channelKey) {
            $this->loadActorsInChannel($channelKey);
        }
        
        // Load tasks for each actor
        foreach ($this->actorsByChannel as $channelKey => $actors) {
            foreach ($actors as $actorId) {
                $this->loadTasksForActor($channelKey, $actorId);
                $this->loadDependenciesForActor($channelKey, $actorId);
                $this->loadInterruptsForActor($channelKey, $actorId);
            }
        }
    }
    
    /**
     * Load channels from channels.jsonl
     */
    private function loadChannels() {
        $channelsFile = $this->basePath . '/channels.jsonl';
        if (!file_exists($channelsFile)) {
            return;
        }
        
        $lines = file($channelsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($data['channel_key'])) {
                $this->channels[] = $data['channel_key'];
            }
        }
    }
    
    /**
     * Load actors in a channel
     */
    private function loadActorsInChannel($channelKey) {
        $actorsFile = $this->basePath . '/' . $channelKey . '/actors.jsonl';
        if (!file_exists($actorsFile)) {
            return;
        }
        
        $this->actorsByChannel[$channelKey] = [];
        $lines = file($actorsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($data['actor_id'])) {
                $this->actorsByChannel[$channelKey][] = $data['actor_id'];
            }
        }
        $this->actorsByChannel[$channelKey] = array_unique($this->actorsByChannel[$channelKey]);
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
     * Load interrupts for an actor
     */
    private function loadInterruptsForActor($channelKey, $actorId) {
        $interruptsFile = $this->basePath . '/' . $channelKey . '/' . $actorId . '/interrupts.jsonl';
        if (!file_exists($interruptsFile)) {
            return;
        }
        
        $key = $channelKey . ':' . $actorId;
        if (!isset($this->interruptsByActor[$key])) {
            $this->interruptsByActor[$key] = [];
        }
        
        $lines = file($interruptsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->interruptsByActor[$key][] = $data;
            }
        }
    }
    
    /**
     * Validate channel_key isolation (PRD 70 §4.1)
     */
    private function validateChannelKeyIsolation() {
        foreach ($this->tasksByActor as $actorKey => $tasks) {
            list($channelKey, $actorId) = explode(':', $actorKey);
            
            foreach ($tasks as $task) {
                // Check task channel_key matches actor's channel
                if (isset($task['channel_key']) && $task['channel_key'] !== $channelKey) {
                    $this->addError('COORDINATION_CHANNEL_ISOLATION', 
                        "Task in wrong channel: actor {$actorId} in channel {$channelKey} has task with channel_key {$task['channel_key']}");
                }
                
                // Check task actor_id matches
                if (isset($task['actor_id']) && $task['actor_id'] != $actorId) {
                    $this->addError('COORDINATION_ACTOR_ISOLATION', 
                        "Task owned by wrong actor: task actor_id {$task['actor_id']} but file belongs to actor {$actorId}");
                }
            }
        }
        
        // Validate dependencies don't cross channels
        if (isset($this->dependenciesByActor)) {
            foreach ($this->dependenciesByActor as $actorKey => $dependencies) {
                list($channelKey, $actorId) = explode(':', $actorKey);
                
                foreach ($dependencies as $dep) {
                    if (isset($dep['channel_key']) && $dep['channel_key'] !== $channelKey) {
                        $this->addError('COORDINATION_DEP_CHANNEL_CROSS', 
                            "Dependency crosses channels: actor {$actorId} in channel {$channelKey} depends on task in channel {$dep['channel_key']}");
                    }
                }
            }
        }
    }
    
    /**
     * Validate task boundary rules (PRD 70 §4.2)
     */
    private function validateTaskBoundaries() {
        foreach ($this->tasksByActor as $actorKey => $tasks) {
            list($channelKey, $actorId) = explode(':', $actorKey);
            
            if (count($tasks) < 2) {
                continue; // No boundaries to validate
            }
            
            // Sort tasks by timestamp
            usort($tasks, function($a, $b) {
                $tsA = $a['timestamp_ymdhis'] ?? 0;
                $tsB = $b['timestamp_ymdhis'] ?? 0;
                return $tsA <=> $tsB;
            });
            
            // Check for proper task state transitions
            $previousTask = null;
            foreach ($tasks as $task) {
                if ($previousTask === null) {
                    $previousTask = $task;
                    continue;
                }
                
                $prevState = $previousTask['task_state'] ?? null;
                $currState = $task['task_state'] ?? null;
                
                // Validate state transitions
                if ($prevState === 'completed' && $currState === 'in_progress') {
                    // Starting new task after completion - OK
                } elseif ($prevState === 'interrupted' && $currState === 'in_progress') {
                    // Resuming after interrupt - OK
                } elseif ($prevState === 'in_progress' && $currState === 'completed') {
                    // Completing current task - OK
                } elseif ($prevState === 'in_progress' && $currState === 'interrupted') {
                    // Interrupting current task - OK
                } elseif ($prevState === 'failed' && $currState === 'in_progress') {
                    // Starting new task after failure - OK
                } else {
                    $this->addWarning('COORDINATION_TASK_TRANSITION', 
                        "Unusual task state transition: {$prevState} -> {$currState} for actor {$actorId} in channel {$channelKey}");
                }
                
                $previousTask = $task;
            }
        }
    }
    
    /**
     * Validate append-only rules for .jsonl files
     */
    private function validateAppendOnlyRules() {
        // Check that timestamps are monotonic (append-only)
        foreach ($this->tasksByActor as $actorKey => $tasks) {
            list($channelKey, $actorId) = explode(':', $actorKey);
            
            $prevTimestamp = 0;
            foreach ($tasks as $task) {
                $timestamp = $task['timestamp_ymdhis'] ?? 0;
                if ($timestamp < $prevTimestamp) {
                    $this->addError('COORDINATION_APPEND_ONLY', 
                        "Non-monotonic timestamp: {$timestamp} < {$prevTimestamp} for actor {$actorId} in channel {$channelKey}");
                }
                $prevTimestamp = $timestamp;
            }
        }
        
        // Check for duplicate event IDs (should not happen in append-only)
        foreach ($this->tasksByActor as $actorKey => $tasks) {
            list($channelKey, $actorId) = explode(':', $actorKey);
            
            $eventIds = [];
            foreach ($tasks as $task) {
                $eventId = $task['event_id'] ?? null;
                if ($eventId !== null) {
                    if (isset($eventIds[$eventId])) {
                        $this->addError('COORDINATION_DUPLICATE_EVENT', 
                            "Duplicate event_id '{$eventId}' for actor {$actorId} in channel {$channelKey}");
                    }
                    $eventIds[$eventId] = true;
                }
            }
        }
    }
    
    /**
     * Validate handoff targets
     */
    private function validateHandoffTargets() {
        foreach ($this->tasksByActor as $actorKey => $tasks) {
            list($channelKey, $actorId) = explode(':', $actorKey);
            
            foreach ($tasks as $task) {
                // Check if task has handoff
                $handoffTo = $task['handoff_to_actor_id'] ?? null;
                if ($handoffTo !== null) {
                    // Validate handoff target exists in same channel
                    if (!isset($this->actorsByChannel[$channelKey])) {
                        $this->addError('COORDINATION_HANDOFF_NO_CHANNEL', 
                            "Handoff to actor {$handoffTo} but channel {$channelKey} not found");
                    } elseif (!in_array($handoffTo, $this->actorsByChannel[$channelKey])) {
                        $this->addError('COORDINATION_HANDOFF_INVALID_ACTOR', 
                            "Handoff to non-existent actor {$handoffTo} in channel {$channelKey}");
                    }
                    
                    // Validate task state allows handoff
                    $taskState = $task['task_state'] ?? null;
                    if ($taskState !== 'completed' && $taskState !== 'interrupted') {
                        $this->addWarning('COORDINATION_HANDOFF_STATE', 
                            "Handoff from non-completed task (state: {$taskState}) for actor {$actorId} in channel {$channelKey}");
                    }
                }
            }
        }
    }
    
    /**
     * Validate dependency resolution rules
     */
    private function validateDependencyResolution() {
        if (!isset($this->dependenciesByActor)) {
            return;
        }
        
        foreach ($this->dependenciesByActor as $actorKey => $dependencies) {
            list($channelKey, $actorId) = explode(':', $actorKey);
            
            foreach ($dependencies as $dep) {
                $dependsOnTask = $dep['depends_on_task'] ?? null;
                $taskName = $dep['task_name'] ?? null;
                
                if ($dependsOnTask === null || $taskName === null) {
                    continue;
                }
                
                // Check if dependency task exists
                $depFound = false;
                foreach ($this->tasksByActor as $otherActorKey => $tasks) {
                    list($otherChannel, $otherActor) = explode(':', $otherActorKey);
                    
                    // Dependencies should be within same channel
                    if ($otherChannel !== $channelKey) {
                        continue;
                    }
                    
                    foreach ($tasks as $task) {
                        $currentTaskName = $task['task_name'] ?? null;
                        $taskState = $task['task_state'] ?? null;
                        
                        if ($currentTaskName === $dependsOnTask) {
                            $depFound = true;
                            
                            // Check if dependency is resolved
                            if ($taskState !== 'completed') {
                                $this->addWarning('COORDINATION_DEP_UNRESOLVED', 
                                    "Task '{$taskName}' depends on unresolved task '{$dependsOnTask}' (state: {$taskState}) for actor {$actorId} in channel {$channelKey}");
                            }
                            break 2;
                        }
                    }
                }
                
                if (!$depFound) {
                    $this->addError('COORDINATION_DEP_MISSING', 
                        "Task '{$taskName}' depends on non-existent task '{$dependsOnTask}' for actor {$actorId} in channel {$channelKey}");
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
            'validator' => 'validate_runtime_coordination',
            'timestamp' => date('Y-m-d H:i:s'),
            'runtime_path' => $this->basePath,
            'channels_found' => count($this->channels),
            'actors_found' => array_sum(array_map('count', $this->actorsByChannel)),
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
    $validator = new RuntimeCoordinationValidator($runtimePath);
    $result = $validator->validate();
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    exit(empty($result['errors']) ? 0 : 1);
}
