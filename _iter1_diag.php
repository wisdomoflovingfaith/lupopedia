<?php
// Thread 1043 Iteration 1 — Step 3 diagnostic
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=lupopedia;charset=utf8mb4', 'root', 'ServBay.dev', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$exists = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema='lupopedia' AND table_name LIKE 'lupo_%' ORDER BY table_name")->fetchAll(PDO::FETCH_COLUMN);
echo "EXISTS_COUNT: " . count($exists) . "\n";
foreach ($exists as $t) { echo "  EXISTS: $t\n"; }

// Check lupo_actors specifically
$actors = $pdo->query("SHOW TABLES LIKE 'lupo_actors'")->fetchColumn();
echo "\nLUPO_ACTORS_EXISTS: " . ($actors ? "YES" : "NO") . "\n";

// Check auth_users
$au = $pdo->query("SHOW TABLES LIKE 'lupo_auth_users'")->fetchColumn();
echo "LUPO_AUTH_USERS_EXISTS: " . ($au ? "YES" : "NO") . "\n";

// Check channels
$ch = $pdo->query("SELECT COUNT(*) FROM lupo_channels WHERE is_deleted=0")->fetchColumn();
echo "CHANNELS_COUNT: $ch\n";

// Check if channel 42 exists
$ch42 = $pdo->query("SELECT channel_id, channel_name FROM lupo_channels WHERE channel_id=42 AND is_deleted=0")->fetch(PDO::FETCH_ASSOC);
echo "CHANNEL_42: " . ($ch42 ? json_encode($ch42) : "MISSING") . "\n";
