<?php
/**
 * Quick Actor Activity Check
 * Usage: php check_actors.php
 * 
 * @version 4.0.31
 * @x_lupo_forwarded 1001:10000
 */

// Simple direct database check
$config_file = __DIR__ . '/lupopedia-config.php';
if (!file_exists($config_file)) {
    die("ERROR: lupopedia-config.php not found\n");
}

require_once $config_file;

if (!defined('DB_HOST') || !defined('DB_NAME')) {
    die("ERROR: Database constants not defined in config\n");
}

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    // Today's date
    $today = gmdate('Ymd');
    echo "\n=== ACTIVE ACTORS TODAY: $today (UTC) ===\n\n";
    
    // Check for today's activity using updated_ymdhis
    $stmt = $pdo->prepare("
        SELECT 
            actor_id,
            name,
            actor_type,
            updated_ymdhis
        FROM {$prefix}actors
        WHERE updated_ymdhis >= :today
          AND is_deleted = 0
        ORDER BY updated_ymdhis DESC
    ");
    
    $stmt->execute(['today' => $today . '000000']);
    $actors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total Active: " . count($actors) . "\n\n";
    
    if (empty($actors)) {
        echo "No actors active today. Checking all actors...\n\n";
        
        $stmt = $pdo->prepare("
            SELECT 
                actor_id,
                name,
                actor_type,
                updated_ymdhis
            FROM {$prefix}actors
            WHERE is_deleted = 0
            ORDER BY actor_id
        ");
        $stmt->execute();
        $actors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "=== ALL ACTORS ===\n\n";
    }
    
    foreach ($actors as $actor) {
        $type = $actor['actor_type'];
        echo "[$type] Actor #{$actor['actor_id']}: {$actor['name']}\n";
        
        if ($actor['updated_ymdhis']) {
            $ts = $actor['updated_ymdhis'];
            $date = substr($ts, 0, 4) . '-' . substr($ts, 4, 2) . '-' . substr($ts, 6, 2);
            $time = substr($ts, 8, 2) . ':' . substr($ts, 10, 2);
            echo "  Last Updated: $date $time UTC\n";
        } else {
            echo "  Last Updated: Never\n";
        }
        echo "\n";
    }
    
} catch (PDOException $e) {
    die("ERROR: " . $e->getMessage() . "\n");
}
