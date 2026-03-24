<?php
/**
 * Thread 1043 Iteration 1 — Step 1: Drop all lupo_ tables
 * HEPHAESTUS execution script. Delete after use.
 */
$host = 'localhost';
$port = '3306';
$user = 'root';
$pass = 'ServBay.dev';
$db   = 'lupopedia';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo "CONNECT FAIL: " . $e->getMessage() . "\n";
    exit(1);
}

echo "CONNECTED OK\n";

// Get all lupo_ tables
$stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '$db' AND table_name LIKE 'lupo_%' ORDER BY table_name");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
$count = count($tables);
echo "PRE-DROP TABLE COUNT: $count\n";
foreach ($tables as $t) { echo "  TABLE: $t\n"; }

if ($count === 0) {
    echo "STEP1_RESULT: NO TABLES TO DROP — already clean\n";
    exit(0);
}

// Drop
$pdo->exec("SET FOREIGN_KEY_CHECKS=0");
$dropped = 0;
$errors = [];
foreach ($tables as $t) {
    try {
        $pdo->exec("DROP TABLE IF EXISTS `$t`");
        $dropped++;
        echo "DROPPED: $t\n";
    } catch (PDOException $e) {
        $errors[] = "ERROR dropping $t: " . $e->getMessage();
        echo "FAIL_DROP: $t — " . $e->getMessage() . "\n";
    }
}
$pdo->exec("SET FOREIGN_KEY_CHECKS=1");

echo "DROPPED_COUNT: $dropped\n";
echo "DROP_ERRORS: " . count($errors) . "\n";

// Verify
$stmt2 = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '$db' AND table_name LIKE 'lupo_%'");
$remaining = $stmt2->fetchAll(PDO::FETCH_COLUMN);
$remaining_count = count($remaining);
echo "POST-DROP REMAINING: $remaining_count\n";
foreach ($remaining as $r) { echo "  STILL_EXISTS: $r\n"; }

if ($remaining_count === 0) {
    echo "STEP1_RESULT: PASS — zero lupo_ tables remain\n";
} else {
    echo "STEP1_RESULT: FAIL — $remaining_count tables remain\n";
    exit(1);
}
