<?php
/**
 * Standalone diagnostic script: Collection 0 tab/content pipeline.
 * Runs outside Lupopedia UI — no bootstrap, session, or auth.
 * Loads DB from lupopedia-config.php first; falls back to config.php (legacy) only if needed.
 *
 * Run in browser: https://localhost/lupopedia/debug_collection_zero.php
 */

// Prevent output before we send headers
$errors = array();

// 1) Load database credentials: lupopedia-config.php first (post-install), then config.php (legacy)
$config_path = __DIR__ . '/lupopedia-config.php';
if (!is_file($config_path)) {
    $config_path = __DIR__ . '/config.php';
}
if (!is_file($config_path)) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Collection 0 Debug</title></head><body>';
    echo '<p style="color:red;">Config file not found (tried lupopedia-config.php then config.php).</p></body></html>';
    exit;
}

require $config_path;

// Resolve DB credentials: lupopedia-config (DB_*) vs Crafty config.php ($server, etc.)
if (isset($server, $database, $datausername, $password)) {
    $db_host = $server;
    $db_name = $database;
    $db_user = $datausername;
    $db_pass = $password;
} elseif (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASSWORD')) {
    $db_host = DB_HOST;
    $db_name = DB_NAME;
    $db_user = DB_USER;
    $db_pass = DB_PASSWORD;
} else {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Collection 0 Debug</title></head><body>';
    echo '<p style="color:red;">Database credentials not found in config.</p></body></html>';
    exit;
}

$table_prefix = (defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_');
$collections_table   = $table_prefix . 'collections';
$tabs_table          = $table_prefix . 'collection_tabs';
$contents_table      = $table_prefix . 'contents';
$tab_map_table       = $table_prefix . 'collection_tab_map';

// 2) Connect via PDO with ERRMODE_EXCEPTION
$dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8mb4';
try {
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Collection 0 Debug</title></head><body>';
    echo '<p style="color:red;">DB connection failed: ' . htmlspecialchars($e->getMessage()) . '</p></body></html>';
    exit;
}

/**
 * Run a query and render section: header, row count, HTML table, red warning if 0 rows.
 */
function run_section(PDO $pdo, $title, $sql, array $params = array()) {
    echo '<h2>' . htmlspecialchars($title) . '</h2>';
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = count($rows);
        echo '<p><strong>Row count:</strong> ' . $count . '</p>';
        if ($count === 0) {
            echo '<p style="color:red;font-weight:bold;">No rows returned.</p>';
        } else {
            echo '<table border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse;">';
            echo '<thead><tr>';
            foreach (array_keys($rows[0]) as $col) {
                echo '<th>' . htmlspecialchars($col) . '</th>';
            }
            echo '</tr></thead><tbody>';
            foreach ($rows as $row) {
                echo '<tr>';
                foreach ($row as $val) {
                    echo '<td>' . htmlspecialchars($val === null ? '' : (string)$val) . '</td>';
                }
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
    } catch (PDOException $e) {
        echo '<p style="color:red;">Query error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    echo '<hr>';
}

// Output HTML
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Collection 0 Debug</title>
    <style>
        body { font-family: sans-serif; margin: 1em; }
        table { font-size: 0.9em; }
        th, td { text-align: left; vertical-align: top; }
    </style>
</head>
<body>
<h1>Collection 0 diagnostic</h1>
<p>Standalone script — no bootstrap, session, or auth. Tables: <?php echo htmlspecialchars($table_prefix); ?>*</p>
<hr>

<?php
// A) All collections
run_section($pdo, 'A) COLLECTIONS', "SELECT * FROM {$collections_table} ORDER BY collection_id");

// B) Tabs for Collection 0
run_section($pdo, 'B) TABS FOR COLLECTION 0', "SELECT * FROM {$tabs_table} WHERE collection_id = 0 ORDER BY sort_order, collection_tab_id");

// C) Contents with default_collection_id = 0 (lupo_contents has default_collection_id, not collection_id)
run_section($pdo, 'C) CONTENTS (default_collection_id = 0)', "SELECT * FROM {$contents_table} WHERE default_collection_id = 0 ORDER BY content_id");

// D) Tab → Content mapping (tab column is "name", not "tab_name" per TOON)
run_section($pdo, 'D) TAB → CONTENT MAPPING (collection_id = 0)', "
    SELECT m.*, t.name AS tab_name, c.title
    FROM {$tab_map_table} m
    LEFT JOIN {$tabs_table} t ON t.collection_tab_id = m.collection_tab_id
    LEFT JOIN {$contents_table} c ON c.content_id = m.item_id AND m.item_type = 'content'
    WHERE t.collection_id = 0
    ORDER BY t.sort_order, m.sort_order
");
?>
<p><em>End of diagnostic.</em></p>
</body>
</html>
