<?php
/**
 * ASCLEPIUS Health Monitor
 * 
 * Agent ID: 703
 * Purpose: System health diagnostics for Softaculous certification
 * 
 * Output format:
 * [OK] message
 * [WARN] message
 * [FAIL] message (exits with non-zero code)
 * [SKIP] message
 */

// Determine base path (physical, explicit)
$base_path = dirname(__DIR__, 2); // agents/ -> lupopedia/

// Load configuration explicitly
$config_path = $base_path . '/lupopedia-config.php';
if (!file_exists($config_path)) {
    echo "[FAIL] Configuration file not found at: $config_path\n";
    exit(1);
}
require_once $config_path;

// Load database factory explicitly
$db_factory_path = LUPO_INCLUDES_DIR . '/classes/DatabaseFactory.php';
if (!file_exists($db_factory_path)) {
    echo "[FAIL] Database factory not found at: $db_factory_path\n";
    exit(1);
}
require_once $db_factory_path;

// Load ID generator explicitly (for tests)
$id_generator_path = LUPO_INCLUDES_DIR . '/classes/IdGenerator.php';
if (file_exists($id_generator_path)) {
    require_once $id_generator_path;
}

/**
 * Health Check Runner
 */
class AsclepiusHealthCheck
{
    private $db = null;
    private $passed = 0;
    private $failed = 0;
    private $warnings = 0;
    
    public function run()
    {
        echo "=== ASCLEPIUS Health Monitor ===\n";
        echo "Agent ID: 703\n";
        echo "Timestamp: " . gmdate('Y-m-d H:i:s') . " UTC\n\n";
        
        $this->checkConfiguration();
        $this->checkDatabaseConnection();
        $this->checkRegistryIntegrity();
        $this->checkAgentDiscovery();
        $this->checkIdGenerator();
        $this->checkGarbageCollector();
        
        echo "\n=== Summary ===\n";
        echo "[OK] Passed: {$this->passed}\n";
        echo "[WARN] Warnings: {$this->warnings}\n";
        echo "[FAIL] Failed: {$this->failed}\n";
        
        return $this->failed === 0 ? 0 : 1;
    }
    
    private function output($status, $message)
    {
        echo "[$status] $message\n";
        if ($status === 'OK') $this->passed++;
        if ($status === 'WARN') $this->warnings++;
        if ($status === 'FAIL') $this->failed++;
    }
    
    private function checkConfiguration()
    {
        if (!defined('LUPOPEDIA_PATH')) {
            $this->output('FAIL', 'LUPOPEDIA_PATH not defined');
            return;
        }
        $this->output('OK', 'LUPOPEDIA_PATH: ' . LUPOPEDIA_PATH);
        
        if (!defined('LUPO_INCLUDES_DIR')) {
            $this->output('FAIL', 'LUPO_INCLUDES_DIR not defined');
            return;
        }
        $this->output('OK', 'LUPO_INCLUDES_DIR: ' . LUPO_INCLUDES_DIR);
        
        if (!defined('DB_PREFIX')) {
            $this->output('WARN', 'DB_PREFIX not defined, using default');
        } else {
            $this->output('OK', 'DB_PREFIX: ' . DB_PREFIX);
        }
    }
    
    private function checkDatabaseConnection()
    {
        try {
            $this->db = DatabaseFactory::getConnection();
            $this->output('OK', 'Database connection established');
            
            // Test query
            $result = $this->db->query("SELECT 1 as test");
            if ($result) {
                $this->output('OK', 'Database query successful');
            } else {
                $this->output('FAIL', 'Database query failed');
            }
        } catch (Exception $e) {
            $this->output('FAIL', 'Database connection failed: ' . $e->getMessage());
        }
    }
    
    private function checkRegistryIntegrity()
    {
        $registry_path = LUPOPEDIA_PATH . '/database/lupopedia/actors/actor_id/registry.json';
        if (!file_exists($registry_path)) {
            $this->output('FAIL', 'Registry file not found: ' . $registry_path);
            return;
        }
        
        $content = file_get_contents($registry_path);
        $registry = json_decode($content, true);
        
        if ($registry === null) {
            $this->output('FAIL', 'Invalid JSON in registry');
            return;
        }
        
        if (!isset($registry['agents']['asclepius']) || $registry['agents']['asclepius'] != 703) {
            $this->output('FAIL', 'ASCLEPIUS not found in registry with ID 703');
            return;
        }
        
        $this->output('OK', 'Registry contains ASCLEPIUS (ID 703)');
        
        // Check agent directory exists
        $agent_dir = LUPOPEDIA_PATH . '/agents/asclepius';
        if (!is_dir($agent_dir)) {
            $this->output('FAIL', 'Agent directory not found: ' . $agent_dir);
            return;
        }
        $this->output('OK', 'Agent directory exists: agents/asclepius/');
    }
    
    private function checkAgentDiscovery()
    {
        $discovery_path = LUPO_INCLUDES_DIR . '/classes/AgentDiscovery.php';
        if (!file_exists($discovery_path)) {
            $this->output('WARN', 'AgentDiscovery class not found');
            return;
        }
        
        require_once $discovery_path;
        
        if (!class_exists('AgentDiscovery')) {
            $this->output('WARN', 'AgentDiscovery class not loaded');
            return;
        }
        
        $this->output('OK', 'AgentDiscovery class found');
    }
    
    private function checkIdGenerator()
    {
        if (!class_exists('IdGenerator')) {
            $this->output('WARN', 'IdGenerator class not available');
            return;
        }
        
        try {
            $id = IdGenerator::generate();
            if (strlen($id) >= 14) {
                $this->output('OK', 'IdGenerator works: ' . substr($id, 0, 14) . '...');
            } else {
                $this->output('FAIL', 'IdGenerator returned invalid ID: ' . $id);
            }
        } catch (Exception $e) {
            $this->output('FAIL', 'IdGenerator failed: ' . $e->getMessage());
        }
    }
    
    private function checkGarbageCollector()
    {
        $gc_path = LUPO_INCLUDES_DIR . '/classes/GarbageCollector.php';
        if (!file_exists($gc_path)) {
            $this->output('WARN', 'GarbageCollector class not found');
            return;
        }
        
        require_once $gc_path;
        
        if (!class_exists('GarbageCollector')) {
            $this->output('WARN', 'GarbageCollector class not loaded');
            return;
        }
        
        $this->output('OK', 'GarbageCollector class found');
    }
}

// Run the health check
$health = new AsclepiusHealthCheck();
$exit_code = $health->run();
exit($exit_code);
