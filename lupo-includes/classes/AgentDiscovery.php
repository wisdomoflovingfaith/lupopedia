<?php

/**
 * Agent Discovery System
 * 
 * Discovers and loads all agents from the lupo-agents/ directory structure.
 * This replaces the database-driven agent system with a filesystem-based approach.
 * 
 * @author Lupopedia Agent System
 * @version 1.0.0
 */

class AgentDiscovery {
    
    /**
     * Discover all agents from the lupo-agents/ directory
     * 
     * @return array Array of agent configurations indexed by agent_key
     */
    public static function discoverAgents() {
        $agents = [];
        $agentDirs = glob('lupo-agents/*', GLOB_ONLYDIR);
        
        foreach ($agentDirs as $dir) {
            $dirName = basename($dir);
            
            // Skip template and meta directories
            if ($dirName === '_TEMPLATE' || $dirName === 'meta') {
                continue;
            }
            
            $config = self::loadAgentConfig($dir);
            if ($config) {
                $agents[$config['agent_key']] = $config;
            }
        }
        
        return $agents;
    }
    
    /**
     * Load agent configuration from directory
     * 
     * @param string $agentDir Path to agent directory
     * @return array|null Agent configuration or null if invalid
     */
    private static function loadAgentConfig($agentDir) {
        $configFile = $agentDir . '/agent.json';
        
        if (!file_exists($configFile)) {
            error_log("Agent config file not found: $configFile");
            return null;
        }
        
        $configContent = file_get_contents($configFile);
        if ($configContent === false) {
            error_log("Failed to read agent config: $configFile");
            return null;
        }
        
        $config = json_decode($configContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Invalid JSON in agent config: $configFile - " . json_last_error_msg());
            return null;
        }
        
        // Ensure required fields exist
        if (!isset($config['agent_key'])) {
            $config['agent_key'] = basename($agentDir);
        }
        
        // Load additional files if they exist
        $config['capabilities'] = self::loadAgentFile($agentDir, 'capabilities.json');
        $config['properties'] = self::loadAgentFile($agentDir, 'properties.json');
        $config['system_prompt'] = self::loadAgentFile($agentDir, 'system_prompt.txt');
        
        return $config;
    }
    
    /**
     * Load additional agent files (capabilities, properties, etc.)
     * 
     * @param string $agentDir Path to agent directory
     * @param string $filename Name of file to load
     * @return array|string|null File contents or null if not found
     */
    private static function loadAgentFile($agentDir, $filename) {
        $filePath = $agentDir . '/' . $filename;
        
        if (!file_exists($filePath)) {
            return null;
        }
        
        $content = file_get_contents($filePath);
        if ($content === false) {
            return null;
        }
        
        // Parse JSON files, return text files as-is
        if (pathinfo($filename, PATHINFO_EXTENSION) === 'json') {
            $decoded = json_decode($content, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        }
        
        return $content;
    }
    
    /**
     * Get agent configuration by agent_key
     * 
     * @param string $agentKey The agent key to find
     * @return array|null Agent configuration or null if not found
     */
    public static function getAgent($agentKey) {
        $agents = self::discoverAgents();
        return isset($agents[$agentKey]) ? $agents[$agentKey] : null;
    }
    
    /**
     * Get agent configuration by agent_id (backward compatibility)
     * 
     * @param int $agentId The agent ID to find
     * @return array|null Agent configuration or null if not found
     */
    public static function getAgentById($agentId) {
        $agents = self::discoverAgents();
        
        foreach ($agents as $agent) {
            if (isset($agent['agent_id']) && $agent['agent_id'] == $agentId) {
                return $agent;
            }
        }
        
        return null;
    }
    
    /**
     * Get agents by layer
     * 
     * @param string $layer The layer to filter by (coordination, application, kernel)
     * @return array Array of agents in the specified layer
     */
    public static function getAgentsByLayer($layer) {
        $agents = self::discoverAgents();
        $layerAgents = [];
        
        foreach ($agents as $agent) {
            if (isset($agent['layer']) && $agent['layer'] === $layer) {
                $layerAgents[$agent['agent_key']] = $agent;
            }
        }
        
        return $layerAgents;
    }
    
    /**
     * Get required agents only
     * 
     * @return array Array of required agents
     */
    public static function getRequiredAgents() {
        $agents = self::discoverAgents();
        $requiredAgents = [];
        
        foreach ($agents as $agent) {
            if (isset($agent['is_required']) && $agent['is_required']) {
                $requiredAgents[$agent['agent_key']] = $agent;
            }
        }
        
        return $requiredAgents;
    }
    
    /**
     * Get kernel agents only
     * 
     * @return array Array of kernel agents
     */
    public static function getKernelAgents() {
        $agents = self::discoverAgents();
        $kernelAgents = [];
        
        foreach ($agents as $agent) {
            if (isset($agent['is_kernel']) && $agent['is_kernel']) {
                $kernelAgents[$agent['agent_key']] = $agent;
            }
        }
        
        return $kernelAgents;
    }
    
    /**
     * Search agents by name, role, or aliases
     * 
     * @param string $query Search query
     * @return array Array of matching agents
     */
    public static function searchAgents($query) {
        $agents = self::discoverAgents();
        $matches = [];
        $query = strtolower($query);
        
        foreach ($agents as $agent) {
            $searchFields = [
                isset($agent['name']) ? strtolower($agent['name']) : '',
                isset($agent['role']) ? strtolower($agent['role']) : '',
                isset($agent['agent_key']) ? strtolower($agent['agent_key']) : ''
            ];
            
            // Add aliases to search fields
            if (isset($agent['aliases']) && is_array($agent['aliases'])) {
                foreach ($agent['aliases'] as $alias) {
                    $searchFields[] = strtolower($alias);
                }
            }
            
            foreach ($searchFields as $field) {
                if (strpos($field, $query) !== false) {
                    $matches[$agent['agent_key']] = $agent;
                    break;
                }
            }
        }
        
        return $matches;
    }
    
    /**
     * Validate agent configuration
     * 
     * @param array $config Agent configuration
     * @return array Array of validation errors (empty if valid)
     */
    public static function validateAgentConfig($config) {
        $errors = [];
        
        // Required fields
        $requiredFields = ['agent_key', 'name', 'role', 'layer'];
        foreach ($requiredFields as $field) {
            if (!isset($config[$field]) || empty($config[$field])) {
                $errors[] = "Missing required field: $field";
            }
        }
        
        // Valid layers
        $validLayers = ['coordination', 'application', 'kernel'];
        if (isset($config['layer']) && !in_array($config['layer'], $validLayers)) {
            $errors[] = "Invalid layer: {$config['layer']}. Must be one of: " . implode(', ', $validLayers);
        }
        
        // Valid agent_key format
        if (isset($config['agent_key'])) {
            if (!preg_match('/^[a-z][a-z0-9_]*$/', $config['agent_key'])) {
                $errors[] = "Invalid agent_key format: {$config['agent_key']}. Must start with lowercase letter and contain only lowercase letters, numbers, and underscores";
            }
        }
        
        return $errors;
    }
    
    /**
     * Get agent statistics
     * 
     * @return array Statistics about discovered agents
     */
    public static function getStatistics() {
        $agents = self::discoverAgents();
        
        $stats = [
            'total_agents' => count($agents),
            'required_agents' => 0,
            'kernel_agents' => 0,
            'coordination_agents' => 0,
            'application_agents' => 0,
            'agents_with_aliases' => 0
        ];
        
        foreach ($agents as $agent) {
            if (isset($agent['is_required']) && $agent['is_required']) {
                $stats['required_agents']++;
            }
            
            if (isset($agent['is_kernel']) && $agent['is_kernel']) {
                $stats['kernel_agents']++;
            }
            
            if (isset($agent['layer'])) {
                $layerKey = $agent['layer'] . '_agents';
                if (isset($stats[$layerKey])) {
                    $stats[$layerKey]++;
                }
            }
            
            if (isset($agent['aliases']) && !empty($agent['aliases'])) {
                $stats['agents_with_aliases']++;
            }
        }
        
        return $stats;
    }
}

?>
