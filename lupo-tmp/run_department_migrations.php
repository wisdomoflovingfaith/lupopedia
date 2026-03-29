<?php
/**
 * Database Migration Runner for Department Access Control
 * 
 * This script runs the necessary SQL migrations to implement department-based
 * access control using mapping tables instead of direct department_id columns.
 * 
 * Usage: http://localhost/lupopedia/run_department_migrations.php
 */

// Load config
require_once __DIR__ . '/lupopedia-config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only allow admin users
if (!isset($_SESSION['auth_user_id']) || $_SESSION['auth_user_id'] != 1000) {
    die("Access denied. Admin user required.");
}

echo "<h1>Department Access Control Migration Runner</h1>\n";

try {
    // Get database connection
    $pdo = new PDO(
        "mysql:host=" . LUPO_DB_HOST . ";dbname=" . LUPO_DB_NAME . ";charset=utf8mb4",
        LUPO_DB_USER,
        LUPO_DB_PASSWORD,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    echo "<h2>Step 1: Create auth_user_departments table</h2>\n";
    
    // Check if table already exists
    $stmt = $pdo->query("SHOW TABLES LIKE '{$table_prefix}auth_user_departments'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table {$table_prefix}auth_user_departments already exists<br>\n";
    } else {
        // Create the table
        $sql = "CREATE TABLE {$table_prefix}auth_user_departments (
            auth_user_department_id bigint NOT NULL,
            auth_user_id bigint NOT NULL,
            department_id bigint NOT NULL,
            is_primary tinyint NOT NULL DEFAULT '0',
            role_key varchar(64) DEFAULT NULL,
            title varchar(64) DEFAULT NULL,
            created_ymdhis bigint NOT NULL DEFAULT 0,
            updated_ymdhis bigint NOT NULL,
            is_deleted tinyint NOT NULL DEFAULT '0',
            deleted_ymdhis bigint DEFAULT NULL,
            PRIMARY KEY (auth_user_department_id)
        )";
        
        $pdo->exec($sql);
        
        // Create indexes
        $pdo->exec("CREATE INDEX {$table_prefix}auth_user_departments_idx_auth_user ON {$table_prefix}auth_user_departments (auth_user_id)");
        $pdo->exec("CREATE INDEX {$table_prefix}auth_user_departments_idx_department ON {$table_prefix}auth_user_departments (department_id)");
        $pdo->exec("CREATE INDEX {$table_prefix}auth_user_departments_idx_primary ON {$table_prefix}auth_user_departments (auth_user_id, is_primary)");
        
        echo "✅ Created table {$table_prefix}auth_user_departments<br>\n";
    }
    
    echo "<h2>Step 2: Remove department_id column from auth_users (if exists)</h2>\n";
    
    // Check if department_id column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM {$table_prefix}auth_users LIKE 'department_id'");
    if ($stmt->rowCount() > 0) {
        $pdo->exec("ALTER TABLE {$table_prefix}auth_users DROP COLUMN department_id");
        $pdo->exec("DROP INDEX IF EXISTS {$table_prefix}auth_users_idx_department ON {$table_prefix}auth_users");
        echo "✅ Removed department_id column from {$table_prefix}auth_users<br>\n";
    } else {
        echo "✅ department_id column not found in {$table_prefix}auth_users (already removed)<br>\n";
    }
    
    echo "<h2>Step 3: Run department seed data</h2>\n";
    
    // Run the seed file
    $seed_file = __DIR__ . '/lupo-database/lupopedia/mysql/seed/seed_departments.sql';
    if (file_exists($seed_file)) {
        $sql = file_get_contents($seed_file);
        
        // Replace table prefix placeholders
        $sql = str_replace('lupo_', $table_prefix, $sql);
        
        // Split into individual statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                try {
                    $pdo->exec($statement);
                } catch (PDOException $e) {
                    // Ignore duplicate key errors
                    if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                        throw $e;
                    }
                }
            }
        }
        
        echo "✅ Ran department seed data<br>\n";
    } else {
        echo "❌ Seed file not found: $seed_file<br>\n";
    }
    
    echo "<h2>✅ Migration Complete!</h2>\n";
    echo "<p>Department-based access control with mapping tables is now active.</p>\n";
    echo "<p><a href='../lupo-tests/debug_departments.php'>Test the implementation</a></p>\n";
    
} catch (Exception $e) {
    echo "<h2>❌ Migration Failed</h2>\n";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>\n";
    echo "<p>Check the error and fix any issues before retrying.</p>\n";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Department Migration Runner</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        h2 { color: #666; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
</body>
</html>
