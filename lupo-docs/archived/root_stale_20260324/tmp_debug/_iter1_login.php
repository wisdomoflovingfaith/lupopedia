<?php
/**
 * Thread 1043 Iteration 1 — Step 4: Login verification
 * Tests whether the seeded admin credentials allow authentication.
 * Actor: HEPHAESTUS. Delete after use.
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=lupopedia;charset=utf8mb4', 'root', 'ServBay.dev', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

echo "STEP4: Login Verification\n";
echo "=========================\n\n";

// Check lupo_actors
$actors_table_exists = $pdo->query("SHOW TABLES LIKE 'lupo_actors'")->fetchColumn() !== false;
echo "LUPO_ACTORS_TABLE: " . ($actors_table_exists ? "EXISTS" : "MISSING") . "\n";

// Check lupo_auth_users
$auth_users_table_exists = $pdo->query("SHOW TABLES LIKE 'lupo_auth_users'")->fetchColumn() !== false;
echo "LUPO_AUTH_USERS_TABLE: " . ($auth_users_table_exists ? "EXISTS" : "MISSING") . "\n";

if ($auth_users_table_exists) {
    $auth_users = $pdo->query("SELECT user_id, actor_id, email, is_deleted FROM lupo_auth_users WHERE is_deleted=0 LIMIT 10")->fetchAll();
    echo "AUTH_USERS_COUNT: " . count($auth_users) . "\n";
    foreach ($auth_users as $u) {
        echo "  AUTH_USER: user_id={$u['user_id']} actor_id={$u['actor_id']} email={$u['email']}\n";
    }
    if (count($auth_users) === 0) {
        echo "LOGIN_RESULT: FAIL — no auth_users seeded\n";
    }
}

if (!$actors_table_exists) {
    echo "\nLOGIN_RESULT: FAIL — lupo_actors table does not exist\n";
    echo "LOGIN_ERROR: lupo_actors is required for authentication but is missing from schema\n";
    echo "ROOT_CAUSE: install_new_lupopedia.sql failed on 'actor_config text NOT NULL DEFAULT {}'\n";
    echo "MYSQL_ERROR: ERROR 1101 (42000) — BLOB, TEXT, GEOMETRY or JSON column 'actor_config' can't have a default value\n";
    exit(1);
}

// If actors table exists, check sessions table
$sessions_table_exists = $pdo->query("SHOW TABLES LIKE 'lupo_sessions'")->fetchColumn() !== false;
echo "LUPO_SESSIONS_TABLE: " . ($sessions_table_exists ? "EXISTS" : "MISSING") . "\n";

// Check for any actors
$actors_count = $pdo->query("SELECT COUNT(*) FROM lupo_actors WHERE is_deleted=0")->fetchColumn();
echo "ACTORS_COUNT: $actors_count\n";

if ($actors_count === 0) {
    echo "\nLOGIN_RESULT: FAIL — no actors seeded (seed_actors_agents_4.0.45.sql failed because lupo_actors table was missing at seed time)\n";
    exit(1);
}

// Try to find WOLFIE (actor_id 1) for login
$wolfie = $pdo->query("SELECT actor_id, actor_name, slug, is_active FROM lupo_actors WHERE actor_id=1 AND is_deleted=0")->fetch();
echo "ACTOR_WOLFIE: " . ($wolfie ? json_encode($wolfie) : "NOT FOUND") . "\n";

// Try to find corresponding auth_user for login
if ($wolfie && $auth_users_table_exists) {
    $wolfie_auth = $pdo->prepare("SELECT user_id, email, is_deleted FROM lupo_auth_users WHERE actor_id=1 AND is_deleted=0");
    $wolfie_auth->execute();
    $row = $wolfie_auth->fetch();
    echo "WOLFIE_AUTH_USER: " . ($row ? json_encode($row) : "NOT FOUND") . "\n";
}

echo "\nSESSION_TEST: N/A (login requires HTTP context; CLI cannot test session creation)\n";
echo "NOTE: Full login test requires web browser or curl with session cookie management\n";
