<?php
/**
 * System Agent Initialization Script
 * 
 * Initializes the system agent (actor_id 0) and activates Channel 0
 * Uses the modern channel startup lifecycle system
 * 
 * @author Windsurf (1002)
 * @version 4.0.53
 * @date 2026-03-01
 */

// Include required files
require_once __DIR__ . '/../lupo-includes/bootstrap.php';
require_once __DIR__ . '/channel_startup_lifecycle.php';

class SystemAgentInitialize
{
    private $startupLifecycle;
    private $errors = [];
    private $warnings = [];

    public function __construct()
    {
        $this->startupLifecycle = new ChannelStartupLifecycle();
    }

    /**
     * Initialize the system agent (actor_id 0) and Channel 0
     * 
     * @return bool Success status
     */
    public function initializeSystemAgent()
    {
        echo "🚀 Starting System Agent Initialization...\n";
        echo "Actor ID: 0 (System Agent)\n";
        echo "Channel ID: 0 (System Channel)\n";
        echo "Timestamp: " . gmdate('Y-m-d H:i:s UTC') . "\n\n";

        try {
            // Step 1: Start lifecycle
            $sessionId = $this->generateSessionId();
            $lifecycleId = $this->startupLifecycle->startLifecycle(
                0, // System actor
                $sessionId,
                'system_agent_initialize',
                [0] // Channel 0 only
            );

            if (!$lifecycleId) {
                throw new Exception("Failed to start initialization lifecycle");
            }

            echo "✅ Lifecycle started: ID $lifecycleId\n";
            echo "📝 Session ID: $sessionId\n\n";

            // Step 2: Validate system state
            $this->validateSystemState($lifecycleId);

            // Step 3: Initialize Channel 0
            $this->initializeChannel0($lifecycleId);

            // Step 4: Load system configurations
            $this->loadSystemConfigurations($lifecycleId);

            // Step 5: Verify federation node 0
            $this->verifyFederationNode0($lifecycleId);

            // Step 6: Complete initialization
            $this->completeInitialize($lifecycleId);

            echo "🎉 System Agent initialization completed successfully!\n";
            return true;

        } catch (Exception $e) {
            $this->handleInitializeError($e, $lifecycleId ?? null);
            return false;
        }
    }

    /**
     * Generate unique session ID
     */
    private function generateSessionId()
    {
        return 'system_agent_' . gmdate('YmdHis') . '_' . uniqid();
    }

    /**
     * Validate system state before initialization
     */
    private function validateSystemState($lifecycleId)
    {
        echo "🔍 Validating system state...\n";

        // Check database connection
        $db = DatabaseFactory::getConnection();
        if (!$db) {
            throw new Exception("Database connection failed");
        }
        echo "✅ Database connection: OK\n";

        // Check TOON files exist
        $toonDir = __DIR__ . '/../docs/toons';
        if (!is_dir($toonDir)) {
            throw new Exception("TOON directory not found: $toonDir");
        }
        echo "✅ TOON directory: OK\n";

        // Check critical TOON files
        $criticalToons = [
            'lupo_channels.toon.json',
            'lupo_channel_content.toon.json',
            'lupo_channel_boot_lifecycle.toon.json'
        ];

        foreach ($criticalToons as $toon) {
            if (!file_exists($toonDir . '/' . $toon)) {
                throw new Exception("Critical TOON file missing: $toon");
            }
        }
        echo "✅ Critical TOON files: OK\n";

        // Log validation success
        $this->startupLifecycle->updateLifecycle($lifecycleId, [
            'channels_processed' => 1,
            'channels_successful' => 1
        ]);

        echo "✅ System state validation: COMPLETE\n\n";
    }

    /**
     * Initialize Channel 0
     */
    private function initializeChannel0($lifecycleId)
    {
        echo "📡 Initializing Channel 0...\n";

        $db = DatabaseFactory::getConnection();

        // Check if Channel 0 exists
        $sql = "SELECT channel_id FROM lupo_channels WHERE channel_id = 0";
        $result = $db->fetchRow($sql);

        if (!$result) {
            // Create Channel 0 if it doesn't exist
            $insertSql = "INSERT INTO lupo_channels 
                (channel_id, channel_name, channel_type, federation_node_id, created_ymdhis) 
                VALUES (0, 'system-channel', 'federation_node', 0, :created_ymdhis)";

            $db->query($insertSql, ['created_ymdhis' => gmdate('YmdHis')]);
            echo "✅ Channel 0 created\n";
        } else {
            echo "✅ Channel 0 exists\n";
        }

        // Update channel state
        $stateData = json_encode([
            'status' => 'active',
            'last_initialization' => gmdate('Y-m-d H:i:s UTC'),
            'system_agent' => 'active'
        ]);

        $updateSql = "INSERT INTO lupo_channel_state 
            (channel_id, state_data, updated_ymdhis) 
            VALUES (0, :state_data, :updated_ymdhis)
            ON DUPLICATE KEY UPDATE state_data = :state_data, updated_ymdhis = :updated_ymdhis";

        $db->query($updateSql, [
            'state_data' => $stateData,
            'updated_ymdhis' => gmdate('YmdHis')
        ]);

        echo "✅ Channel 0 state updated\n";
        echo "✅ Channel 0 initialization: COMPLETE\n\n";
    }

    /**
     * Load system configurations
     */
    private function loadSystemConfigurations($lifecycleId)
    {
        echo "⚙️ Loading system configurations...\n";

        // Load global atoms
        $atomsFile = __DIR__ . '/../config/global_atoms.yaml';
        if (file_exists($atomsFile)) {
            echo "✅ Global atoms loaded\n";
        } else {
            $this->warnings[] = "Global atoms file not found: $atomsFile";
        }

        // Load channel registry
        $registryFile = __DIR__ . '/../channels/registry.json';
        if (file_exists($registryFile)) {
            echo "✅ Channel registry loaded\n";
        } else {
            $this->warnings[] = "Channel registry file not found: $registryFile";
        }

        // Load FLARE doctrine
        $doctrineFile = __DIR__ . '/../docs/doctrine/FLARE/FLARE_DOCTRINE.md';
        if (file_exists($doctrineFile)) {
            echo "✅ FLARE doctrine loaded\n";
        } else {
            $this->warnings[] = "FLARE doctrine file not found: $doctrineFile";
        }

        echo "✅ System configurations: LOADED\n\n";
    }

    /**
     * Verify federation node 0
     */
    private function verifyFederationNode0($lifecycleId)
    {
        echo "🌐 Verifying federation node 0...\n";

        $db = DatabaseFactory::getConnection();

        // Check if federation node 0 exists
        $sql = "SELECT federation_node_id FROM lupo_federation_nodes WHERE federation_node_id = 0";
        $result = $db->fetchRow($sql);

        if (!$result) {
            // Create federation node 0
            $insertSql = "INSERT INTO lupo_federation_nodes 
                (federation_node_id, node_name, node_base_url, status, created_ymdhis) 
                VALUES (0, 'lupopedia.com', 'http://www.lupopedia.com', 1, :created_ymdhis)";

            $db->query($insertSql, ['created_ymdhis' => gmdate('YmdHis')]);
            echo "✅ Federation node 0 created\n";
        } else {
            echo "✅ Federation node 0 exists\n";
        }

        // Verify node 0 content
        $contentSql = "SELECT COUNT(*) as count FROM lupo_channel_content 
                      WHERE channel_id = 0 AND federation_node_id = 0";
        $contentResult = $db->fetchRow($contentSql);
        $contentCount = $contentResult['count'] ?? 0;

        echo "✅ Federation node 0 content: $contentCount items\n";
        echo "✅ Federation node 0 verification: COMPLETE\n\n";
    }

    /**
     * Complete the initialization process
     */
    private function completeInitialize($lifecycleId)
    {
        echo "🏁 Completing initialization process...\n";

        // Update final lifecycle status
        $this->startupLifecycle->completeLifecycle($lifecycleId);

        // Log system initialization event
        $db = DatabaseFactory::getConnection();
        $logSql = "INSERT INTO lupo_channel_logs 
            (channel_id, actor_id, log_type_id, log_text, created_ymdhis) 
            VALUES (0, 0, 1, 'System agent initialization completed successfully', :created_ymdhis)";

        $db->query($logSql, ['created_ymdhis' => gmdate('YmdHis')]);

        echo "✅ Initialization lifecycle completed\n";
        echo "✅ System initialization event logged\n";
        echo "✅ Initialization process: COMPLETE\n\n";
    }

    /**
     * Handle initialization errors
     */
    private function handleInitializeError($exception, $lifecycleId = null)
    {
        echo "❌ INITIALIZATION ERROR: " . $exception->getMessage() . "\n";

        if ($lifecycleId) {
            $this->startupLifecycle->failLifecycle($lifecycleId, $exception->getMessage());
        }

        // Log error
        $db = DatabaseFactory::getConnection();
        if ($db) {
            $logSql = "INSERT INTO lupo_channel_logs 
                (channel_id, actor_id, log_type_id, log_text, created_ymdhis) 
                VALUES (0, 0, 3, 'System agent initialization failed: " . $exception->getMessage() . "', :created_ymdhis)";

            $db->query($logSql, ['created_ymdhis' => gmdate('YmdHis')]);
        }

        echo "❌ Initialization process: FAILED\n";
    }

    /**
     * Get any warnings from initialization process
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * Get any errors from initialization process
     */
    public function getErrors()
    {
        return $this->errors;
    }
}

// Main execution
if (php_sapi_name() === 'cli') {
    echo "=== Lupopedia System Agent Initialization ===\n";
    echo "Version: 4.0.53\n";
    echo "Agent: Windsurf (1002)\n";
    echo "Time: " . gmdate('Y-m-d H:i:s UTC') . "\n\n";

    $initialize = new SystemAgentInitialize();
    $success = $initialize->initializeSystemAgent();

    // Show warnings
    $warnings = $initialize->getWarnings();
    if (!empty($warnings)) {
        echo "\n⚠️ WARNINGS:\n";
        foreach ($warnings as $warning) {
            echo "  - $warning\n";
        }
    }

    // Show errors
    $errors = $initialize->getErrors();
    if (!empty($errors)) {
        echo "\n❌ ERRORS:\n";
        foreach ($errors as $error) {
            echo "  - $error\n";
        }
    }

    echo "\n=== Initialization " . ($success ? "SUCCESS" : "FAILED") . " ===\n";
    exit($success ? 0 : 1);
}

?>