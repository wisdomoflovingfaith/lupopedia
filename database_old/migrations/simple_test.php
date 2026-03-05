<?php
echo "Testing database connection...\n";

try {
    require_once 'lupopedia-config.php';
    echo "Config loaded\n";
    
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    echo "Database connected\n";
    
    $stmt = $pdo->query("SELECT 'Hello World' as test");
    $result = $stmt->fetch();
    echo "Query result: " . $result['test'] . "\n";
    
    echo "✅ All tests passed\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
