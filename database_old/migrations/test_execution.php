<?php
/**
 * Simple test script for table optimization
 */

echo "🚀 Starting v4.0.55 Table Optimization Test\n";
echo "Current UTC: " . gmdate('Y-m-d H:i:s') . "\n\n";

// Load configuration
require_once __DIR__ . '/../../lupopedia-config.php';

// Test database connection
try {
    // Use Lupopedia database credentials
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connection: Success\n";
    
    // Count current tables
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "' AND table_name LIKE 'lupo_%'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "📊 Current table count: " . $result['count'] . "\n";
    
    // Test if unified log table exists
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
        echo "❌ Unified log table: Not found\n";
    }
    
    // Test enhanced sessions
    $stmt = $pdo->query("SHOW COLUMNS FROM lupo_sessions LIKE 'session_events'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Enhanced sessions table: Has JSON columns\n";
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM lupo_sessions WHERE session_events IS NOT NULL");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "📝 Sessions with events: " . $result['count'] . "\n";
    } else {
        echo "❌ Enhanced sessions table: Not yet upgraded\n";
    }
    
    echo "\n🎉 Test completed successfully\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

?>
