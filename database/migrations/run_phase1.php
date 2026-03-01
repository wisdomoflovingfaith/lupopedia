<?php
/**
 * Run Phase 1 Table Consolidation
 */

// Load configuration
require_once __DIR__ . '/../../lupopedia-config.php';

echo "🚀 Starting Phase 1: Logging Table Consolidation\n";
echo "Current UTC: " . gmdate('Y-m-d H:i:s') . "\n\n";

try {
    // Connect to database
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connection: Success\n";
    
    // Read Phase 1 SQL
    $sql = file_get_contents(__DIR__ . '/table_consolidation_phase1.sql');
    
    // Execute statement by statement with better error handling
    $statements = preg_split('/;\s*\r?\n/', $sql);
    
    foreach ($statements as $i => $statement) {
        $statement = trim($statement);
        if (empty($statement) || preg_match('/^--/', $statement)) {
            continue;
        }
        
        echo "🔄 Statement " . ($i + 1) . ": " . substr($statement, 0, 60) . "...\n";
        
        try {
            $pdo->exec($statement);
            echo "   ✅ Success\n";
        } catch (PDOException $e) {
            echo "   ❌ Error: " . $e->getMessage() . "\n";
            // Continue with other statements
        }
    }
    
    echo "\n🔍 Verification:\n";
    
    // Check if unified log table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'lupo_unified_log'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Unified log table: Exists\n";
        
        // Count records
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM lupo_unified_log");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "📝 Unified log records: " . $result['count'] . "\n";
        
        // Show log types
        $stmt = $pdo->query("SELECT log_type, COUNT(*) as count FROM lupo_unified_log GROUP BY log_type ORDER BY log_type");
        echo "📋 Log types:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - {$row['log_type']}: {$row['count']} records\n";
        }
    } else {
        echo "❌ Unified log table: Not created\n";
    }
    
    // Count current tables
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "' AND table_name LIKE 'lupo_%'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "📊 Current table count: " . $result['count'] . "\n";
    
} catch (Exception $e) {
    echo "❌ Fatal Error: " . $e->getMessage() . "\n";
}

echo "\n🎉 Phase 1 execution complete\n";

?>
