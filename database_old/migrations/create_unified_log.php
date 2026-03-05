<?php
/**
 * Create Unified Log Table
 */

require_once __DIR__ . '/../../lupopedia-config.php';

echo "🚀 Creating Unified Log Table\n";
echo "Current UTC: " . gmdate('Y-m-d H:i:s') . "\n\n";

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connection: Success\n";
    
    // Drop existing table if it exists
    echo "🔄 Dropping existing table (if any)...\n";
    $pdo->exec("DROP TABLE IF EXISTS lupo_unified_log");
    
    // Create unified log table
    echo "🔄 Creating unified log table...\n";
    $sql = "CREATE TABLE lupo_unified_log (
        log_id BIGINT PRIMARY KEY AUTO_INCREMENT,
        log_type ENUM('anubis_deletion', 'anubis_general', 'anubis_processing', 'audit', 'auth_audit', 'bans', 'channel_boot', 'event', 'interpretation', 'search_rebuild') NOT NULL,
        log_level ENUM('debug', 'info', 'warning', 'error', 'critical') DEFAULT 'info',
        log_message TEXT NOT NULL,
        log_context JSON,
        actor_id INT DEFAULT NULL,
        channel_id INT DEFAULT NULL,
        session_id VARCHAR(128) DEFAULT NULL,
        ip_address VARCHAR(45) DEFAULT NULL,
        user_agent TEXT DEFAULT NULL,
        created_ymdhis BIGINT NOT NULL,
        INDEX idx_log_type_created (log_type, created_ymdhis),
        INDEX idx_actor_log (actor_id, log_type),
        INDEX idx_channel_log (channel_id, log_type),
        INDEX idx_session_log (session_id, log_type),
        INDEX idx_created_ymdhis (created_ymdhis)
    )";
    
    $pdo->exec($sql);
    echo "✅ Unified log table created successfully\n";
    
    // Add some test data
    echo "🔄 Adding test data...\n";
    $test_data = [
        ['log_type' => 'event', 'log_level' => 'info', 'log_message' => 'Table optimization test', 'actor_id' => 1002, 'created_ymdhis' => gmdate('YmdHis')],
        ['log_type' => 'audit', 'log_level' => 'info', 'log_message' => 'Unified log table created', 'actor_id' => 1002, 'created_ymdhis' => gmdate('YmdHis')],
    ];
    
    foreach ($test_data as $data) {
        $stmt = $pdo->prepare("INSERT INTO lupo_unified_log (log_type, log_level, log_message, actor_id, created_ymdhis) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['log_type'], $data['log_level'], $data['log_message'], $data['actor_id'], $data['created_ymdhis']]);
    }
    
    echo "✅ Test data added\n";
    
    // Verification
    echo "\n🔍 Verification:\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM lupo_unified_log");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "📝 Total records: " . $result['count'] . "\n";
    
    $stmt = $pdo->query("SELECT log_type, COUNT(*) as count FROM lupo_unified_log GROUP BY log_type ORDER BY log_type");
    echo "📋 Log types:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  - {$row['log_type']}: {$row['count']} records\n";
    }
    
    // Count current tables
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "' AND table_name LIKE 'lupo_%'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "📊 Current table count: " . $result['count'] . "\n";
    
    echo "\n🎉 Unified log table creation complete!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

?>
