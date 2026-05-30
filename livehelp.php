<?php
/* ⧉ WOLFIE HEADER v2.4 ⧉
w3_views: mechanical | relational | mythic | docs

◈ w3_MECHANICAL (REQUIRED)
w3_created_day_utc: 2026-02-01T00:00:00Z
w3_modified_day_utc: 2026-02-01T00:00:00Z
w3_updated_by: cascade
w3_taxonomy_key: wolfie.header.taxonomy
w3_taxonomy_version: 2.4
w3_package: lupopedia
w3_subpackage: livehelp
w3_module: livehelp
w3_aspect: compatibility-bridge
w3_purpose: Legacy Crafty Syntax livehelp JavaScript endpoint bridge.
w3_mutation_notes: Upgraded from WOLFIE HEADER v2.2 to v2.4 format

◈ w3_RELATIONAL (RECOMMENDED, STRUCTURAL ONLY)
w3_nourishes→:
w3_nourished_by←:
w3_tensions↔:

◈ w3_MYTHIC (OPTIONAL)
w3_epoch: wolfie-winter-2026
w3_signature:

◈ w3_DOCS (OPTIONAL — ENHANCED DOCUMENTATION)
*/
// Legacy Crafty Syntax compatibility - adapted for new Lupopedia tables

// Load config first (required by bootstrap)
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', __DIR__);
}

require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'LupopediaConfigResolver.php';
$config_file = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH);
if ($config_file !== null && is_file($config_file)) {
    require_once $config_file;
}

// Now load bootstrap
require_once 'includes/bootstrap.php';

// Get department parameter - required like legacy version
$department = isset($_GET['department']) ? intval($_GET['department']) : 0;
if ($department == 0) {
    $website = isset($_GET['website']) ? intval($_GET['website']) : 0;
    if ($website != 0) {
        header("Location: choosedepartment.php?website=$website", TRUE, 307);
    } else {
        header("Location: choosedepartment.php", TRUE, 307);
    }
    exit;
}

// Get department info from new tables
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

$stmt = $db->prepare("SELECT * FROM {$prefix}departments WHERE department_id = :department_id AND is_deleted = 0");
$stmt->execute(array(':department_id' => $department));
$department_a = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$department_a) {
    echo "<font color=990000>Error no department with that id</font>";
    exit;
}

// Get or create session/actor identity
session_start();
$session_id = session_id();

// Find existing session record
$stmt = $db->prepare("SELECT * FROM {$prefix}sessions WHERE session_id = :session_id AND is_active = 1 AND is_deleted = 0");
$stmt->execute(array(':session_id' => $session_id));
$session = $stmt->fetch(PDO::FETCH_ASSOC);

$actor_id = 0;
if ($session && $session['actor_id'] > 0) {
    $actor_id = $session['actor_id'];
    
    // Get actor info
    $stmt = $db->prepare("SELECT * FROM {$prefix}actors WHERE actor_id = :actor_id AND is_deleted = 0");
    $stmt->execute(array(':actor_id' => $actor_id));
    $actor = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$actor) {
    // Create new actor for visitor
    if (class_exists('IdGenerator')) {
        $actor_id = IdGenerator::generate();
    } else {
        $actor_id = gmdate('YmdHis') . str_pad((string) mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }
    $stmt = $db->prepare("INSERT INTO {$prefix}actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis) VALUES (:actor_id, :actor_type, :slug, :name, :created, :updated)");
    $stmt->execute(array(
        ':actor_id' => $actor_id,
        ':actor_type' => 'visitor',
        ':slug' => 'visitor_' . $actor_id,
        ':name' => $_SERVER['REMOTE_ADDR'],
        ':created' => gmdate('YmdHis'),
        ':updated' => gmdate('YmdHis')
    ));
    
    // Create or update session record
    if ($session) {
        $stmt = $db->prepare("UPDATE {$prefix}sessions SET actor_id = :actor_id, last_seen_ymdhis = :last_seen, updated_ymdhis = :updated WHERE session_id = :session_id");
        $stmt->execute(array(
            ':actor_id' => $actor_id,
            ':last_seen' => gmdate('YmdHis'),
            ':updated' => gmdate('YmdHis'),
            ':session_id' => $session_id
        ));
    } else {
        // Check if session exists before inserting (to avoid duplicate key)
        $check_stmt = $db->prepare("SELECT session_id FROM {$prefix}sessions WHERE session_id = :session_id");
        $check_stmt->execute(array(':session_id' => $session_id));
        if ($check_stmt->fetch()) {
            // Session exists, update it
            $stmt = $db->prepare("UPDATE {$prefix}sessions SET actor_id = :actor_id, last_seen_ymdhis = :last_seen, updated_ymdhis = :updated WHERE session_id = :session_id");
            $stmt->execute(array(
                ':actor_id' => $actor_id,
                ':last_seen' => gmdate('YmdHis'),
                ':updated' => gmdate('YmdHis'),
                ':session_id' => $session_id
            ));
        } else {
            // Session doesn't exist, insert it
            $stmt = $db->prepare("INSERT INTO {$prefix}sessions (session_id, actor_id, channel_id, ip_address, last_seen_ymdhis, created_ymdhis, updated_ymdhis) VALUES (:session_id, :actor_id, :channel_id, :ip_address, :last_seen, :created, :updated)");
            $stmt->execute(array(
                ':session_id' => $session_id,
                ':actor_id' => $actor_id,
                ':channel_id' => 1,
                ':ip_address' => $_SERVER['REMOTE_ADDR'],
                ':last_seen' => gmdate('YmdHis'),
                ':created' => gmdate('YmdHis'),
                ':updated' => gmdate('YmdHis')
            ));
        }
    }
    
    // Reload actor data
    $stmt = $db->prepare("SELECT * FROM {$prefix}actors WHERE actor_id = :actor_id AND is_deleted = 0");
    $stmt->execute(array(':actor_id' => $actor_id));
    $actor = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update actor's department
$stmt = $db->prepare("UPDATE {$prefix}actors SET department_id = :department_id WHERE actor_id = :actor_id");
$stmt->execute(array(':department_id' => $department, ':actor_id' => $actor_id));

// Set variables for compatibility (like legacy version)
$myid = $actor_id;
$channel = isset($actor['channel_id']) ? $actor['channel_id'] : 0;
$isnamed = isset($actor['name']) && !empty($actor['name']) && $actor['name'] != $_SERVER['REMOTE_ADDR'] ? 1 : 0;
$lastaction = gmdate('YmdHis');
$timeof = gmdate('YmdHis');
$startdate = gmdate('Ymd');

// Handle GET parameters like legacy version
$tab = isset($_GET['tab']) ? intval($_GET['tab']) : 1;
$action = isset($_GET['action']) ? $_GET['action'] : '';
$makenamed = isset($_GET['makenamed']) ? $_GET['makenamed'] : '';
$doubleframe = isset($_GET['doubleframe']) ? $_GET['doubleframe'] : 'no';

// Check if operators are online for this department (adapted from legacy)
$stmt = $db->prepare("SELECT a.* FROM {$prefix}actors a 
                    INNER JOIN {$prefix}actor_departments ad ON a.actor_id = ad.actor_id 
                    WHERE ad.department_id = :department_id 
                    AND a.actor_type = 'operator' 
                    AND a.is_active = 1 
                    AND a.is_deleted = 0
                    AND ad.is_deleted = 0");
$stmt->execute(array(':department_id' => $department));
$operators_online = $stmt->fetchAll();

// Set page URL based on tab and operator availability
if (empty($operators_online) && empty($tab)) {
    $doubleframe = "yes";
    $pageurl = "offline.php?department=" . $department;
    $tab = 1;
} else {
    $pageurl = "livehelp_main.php?department=" . $department;
}

// Handle actions like legacy version
if ($action == "leave") {
    header("Location: wentaway.php?savepage=1&department=" . $department, TRUE, 307);
    exit;
}

// Handle name submission like legacy version
if ($makenamed == "Y" && isset($_GET['newusername'])) {
    $newusername = substr($_GET['newusername'], 0, 15);
    $newusername = str_replace("'", "", $newusername);
    
    if (empty($newusername)) {
        $newusername = "no name";
    }
    
    // Update actor name
    $stmt = $db->prepare("UPDATE {$prefix}actors SET name = :name, updated_ymdhis = :updated WHERE actor_id = :actor_id");
    $stmt->execute(array(
        ':name' => $newusername,
        ':updated' => gmdate('YmdHis'),
        ':actor_id' => $actor_id
    ));
    
    $isnamed = 1;
}

// Set theme (could be made department-specific)
$theme = 'default';
$pagetogo = 'livehelp.php'; // This would normally be a theme template

// Include theme template (like legacy version does at line 789)
// For now, output basic structure that mimics what themes would expect
echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Live Help - Department ' . htmlspecialchars($department_a['name']) . '</title>
</head>
<body>
    <h1>Live Help</h1>
    <p>Department: ' . htmlspecialchars($department_a['name']) . '</p>
    <p>Actor ID: ' . $actor_id . '</p>
    <p>Channel ID: ' . $channel . '</p>
    <p>Operators Online: ' . count($operators_online) . '</p>
    <p>Status: ' . ($isnamed ? 'Named' : 'Anonymous') . '</p>';
    
if ($isnamed) {
    echo '<p>Welcome, ' . htmlspecialchars($actor['name']) . '!</p>';
} else {
    echo '<p><a href="?department=' . $department . '&makenamed=Y">Please enter your name</a></p>';
}

echo '<p><a href="' . htmlspecialchars($pageurl) . '">Continue to Chat</a></p>';
echo '<p><a href="?department=' . $department . '&action=leave">Leave Chat</a></p>';

echo '</body>
</html>';

?>
