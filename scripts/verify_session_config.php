<?php
/**
 * Verify Session Configuration for 4.0.96
 * 
 * This script verifies that LUPO_SESSION_SALT is properly configured
 * for the session identity hash system implemented in 4.0.96.
 * 
 * Usage: php scripts/verify_session_config.php
 */

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(__DIR__));
}

echo "=== Session Configuration Verification (4.0.96) ===\n\n";

// Check 1: Verify LUPO_SESSION_SALT is defined
echo "1. Checking LUPO_SESSION_SALT constant...\n";
if (defined('LUPO_SESSION_SALT')) {
    $salt = LUPO_SESSION_SALT;
    if (empty($salt)) {
        echo "   ❌ FAIL: LUPO_SESSION_SALT is defined but empty\n";
        $errors[] = 'LUPO_SESSION_SALT is empty';
    } elseif (strlen($salt) < 32) {
        echo "   ❌ FAIL: LUPO_SESSION_SALT is too short (minimum 32 chars recommended)\n";
        $errors[] = 'LUPO_SESSION_SALT too short';
    } else {
        echo "   ✅ PASS: LUPO_SESSION_SALT is defined and non-empty (" . strlen($salt) . " chars)\n";
    }
} else {
    echo "   ❌ FAIL: LUPO_SESSION_SALT is not defined\n";
    $errors[] = 'LUPO_SESSION_SALT not defined';
}

// Check 2: Verify Session class can compute identity hash
echo "\n2. Testing Session::computeIdentityHash()...\n";
if (class_exists('App\Auth\Session', false)) {
    $test_ip = '192.168.1.100';
    $test_ua = 'Mozilla/5.0 (Test Browser)';
    
    try {
        $hash = App\Auth\Session::computeIdentityHash($test_ip, $test_ua);
        if (!empty($hash) && strlen($hash) === 64) { // SHA-256 = 64 hex chars
            echo "   ✅ PASS: Identity hash computed successfully (64 chars)\n";
        } else {
            echo "   ❌ FAIL: Identity hash has unexpected length: " . strlen($hash) . "\n";
            $errors[] = 'Identity hash wrong length';
        }
    } catch (Exception $e) {
        echo "   ❌ FAIL: Exception computing identity hash: " . $e->getMessage() . "\n";
        $errors[] = 'Identity hash computation failed';
    }
} else {
    echo "   ❌ FAIL: App\\Auth\\Session class not available\n";
    $errors[] = 'Session class not available';
}

// Check 3: Verify database schema has session_identity_hash column
echo "\n3. Checking database schema for session_identity_hash column...\n";
try {
    if (!class_exists('DatabaseFactory', false)) {
        require_once LUPOPEDIA_PATH . '/includes/classes/DatabaseFactory.php';
    }
    $db = DatabaseFactory::getConnection();
    
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $table = $prefix . 'sessions';
    
    $row = $db->fetchRow("DESCRIBE " . $db->quoteIdentifier($table) . " WHERE Field = 'session_identity_hash'");
    if ($row) {
        echo "   ✅ PASS: session_identity_hash column exists\n";
        echo "   Type: " . $row['Type'] . "\n";
    } else {
        echo "   ❌ FAIL: session_identity_hash column missing\n";
        $errors[] = 'session_identity_hash column missing';
    }
} catch (Exception $e) {
    echo "   ❌ FAIL: Database error: " . $e->getMessage() . "\n";
    $errors[] = 'Database connection failed';
}

// Check 4: Verify session identity hash population in existing sessions
echo "\n4. Checking existing sessions for identity hash population...\n";
try {
    $row = $db->fetchRow(
        "SELECT COUNT(*) AS total, COUNT(session_identity_hash) AS with_hash FROM " . 
        $db->quoteIdentifier($table) . 
        " WHERE created_ymdhis >= DATE_SUB(NOW(), INTERVAL 1 DAY)"
    );
    
    $total = (int) $row['total'];
    $with_hash = (int) $row['with_hash'];
    
    if ($total === 0) {
        echo "   ℹ️  INFO: No sessions created in last 24 hours to check\n";
    } else {
        $percentage = $total > 0 ? round(($with_hash / $total) * 100, 1) : 0;
        echo "   Sessions (last 24h): $total total, $with_hash with hash ($percentage%)\n";
        
        if ($percentage >= 95) {
            echo "   ✅ PASS: Most recent sessions have identity hash\n";
        } else {
            echo "   ❌ FAIL: Low percentage of sessions with identity hash\n";
            $errors[] = 'Low session identity hash population';
        }
    }
} catch (Exception $e) {
    echo "   ❌ FAIL: Error checking session population: " . $e->getMessage() . "\n";
    $errors[] = 'Session population check failed';
}

// Summary
echo "\n=== SUMMARY ===\n";
if (empty($errors)) {
    echo "✅ ALL CHECKS PASSED - Session configuration is correct for 4.0.96\n";
    exit(0);
} else {
    echo "❌ CHECKS FAILED:\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
    echo "\nTo fix LUPO_SESSION_SALT:\n";
    echo "1. Generate a salt: php scripts/generate_session_salt.php\n";
    echo "2. Add to lupopedia-config.php: define('LUPO_SESSION_SALT', 'generated_value_here');\n";
    exit(1);
}
