<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=lupopedia;charset=utf8mb4','root','ServBay.dev',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
echo "LUPO_ACTORS_EXISTS: " . ($pdo->query("SHOW TABLES LIKE 'lupo_actors'")->fetchColumn() ? "YES" : "NO") . "\n";
echo "DESCRIBE lupo_auth_users:\n";
foreach($pdo->query('DESCRIBE lupo_auth_users')->fetchAll(PDO::FETCH_ASSOC) as $r) {
    echo "  {$r['Field']} | {$r['Type']} | Null={$r['Null']} | Key={$r['Key']} | Default={$r['Default']}\n";
}
echo "AUTH_USERS_ROW_COUNT: " . $pdo->query('SELECT COUNT(*) FROM lupo_auth_users')->fetchColumn() . "\n";
echo "\nSELECT * FROM lupo_auth_users LIMIT 5:\n";
foreach($pdo->query('SELECT * FROM lupo_auth_users LIMIT 5')->fetchAll(PDO::FETCH_ASSOC) as $r) {
    echo "  " . json_encode($r) . "\n";
}
