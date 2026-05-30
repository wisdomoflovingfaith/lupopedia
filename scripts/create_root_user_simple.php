<?php
/**
 * Create Root User (Simple)
 * Creates the root user for login with minimal fields
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'ServBay.dev');
define('DB_NAME', 'lupopedia');

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CREATE ROOT USER ===\n\n";
    
    // Get current timestamp
    $now = gmdate('YmdHis');
    echo "Using timestamp: {$now}\n\n";
    
    // Check and insert root auth user
    $stmt = $conn->prepare("SELECT auth_user_id, username FROM lupo_auth_users WHERE auth_user_id = 1000");
    $stmt->execute([1000]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        echo "⚠️ Root user already exists: {$existing['username']}\n";
    } else {
        // Default password: ServBay.dev (hashed)
        $password_hash = password_hash('ServBay.dev', PASSWORD_DEFAULT);
        
        // Insert with minimal required fields
        $sql = "INSERT INTO lupo_auth_users (auth_user_id, username, display_name, email, created_ymdhis, updated_ymdhis) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([1000, 'root', 'root', 'wisdomoflovingfaith@gmail.com', $now, $now]);
        
        // Update password separately
        $sql = "UPDATE lupo_auth_users SET password_hash = ? WHERE auth_user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$password_hash, 1000]);
        
        echo "✅ Root user created\n";
        echo "  Username: root\n";
        echo "  Password: ServBay.dev\n";
        echo "  Email: wisdomoflovingfaith@gmail.com\n";
    }
    
    // Final verification
    $auth_count = $conn->query("SELECT COUNT(*) FROM lupo_auth_users WHERE auth_user_id = 1000")->fetchColumn();
    
    echo "\nRoot User Status: " . ($auth_count > 0 ? "✅ Ready" : "❌ Missing") . "\n";
    
    if ($auth_count > 0) {
        echo "\n🎉 SUCCESS: Root user is ready for login!\n";
        echo "\nLogin Credentials:\n";
        echo "Username: root\n";
        echo "Password: ServBay.dev\n";
        echo "\nReady for fresh install!\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
