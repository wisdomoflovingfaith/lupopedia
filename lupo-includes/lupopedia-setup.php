<?php
/**
 * Lupopedia Setup Script
 * 
 * Handles initial setup and upgrade from Crafty Syntax 3.7.5 to Lupopedia + Crafty Syntax 4.0.0
 * 
 * @package Lupopedia
 * @version 4.0.0
 */

// Prevent direct access
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(__DIR__));
}

/**
 * Find old Crafty Syntax config.php file
 * 
 * @return string|false Path to config.php or false if not found
 */
function lupopedia_find_old_config() {
    $search_paths = [
        dirname(__DIR__) . '/../config.php',               // Parent of lupopedia directory (most common)
        dirname($_SERVER['DOCUMENT_ROOT']) . '/config.php', // Above webroot
        $_SERVER['DOCUMENT_ROOT'] . '/config.php',         // Webroot
        __DIR__ . '/../config.php',                        // Lupopedia directory
        __DIR__ . '/config.php',                           // Current directory (lupo-includes/)
    ];
    
    // Also check common Crafty Syntax installation paths
    if (isset($_SERVER['DOCUMENT_ROOT'])) {
        $search_paths[] = $_SERVER['DOCUMENT_ROOT'] . '/craftysyntax/config.php';
        $search_paths[] = $_SERVER['DOCUMENT_ROOT'] . '/livehelp/config.php';
        $search_paths[] = $_SERVER['DOCUMENT_ROOT'] . '/lh/config.php';
        $search_paths[] = $_SERVER['DOCUMENT_ROOT'] . '/cs/config.php';
    }
    
    foreach ($search_paths as $path) {
        if (file_exists($path) && is_readable($path)) {
            // Verify it's a Crafty Syntax config file
            $content = @file_get_contents($path);
            if ($content && (strpos($content, '$server') !== false || strpos($content, '$database') !== false)) {
                return $path;
            }
        }
    }
    
    return false;
}

/**
 * Parse old Crafty Syntax config.php and extract database credentials
 * 
 * @param string $config_path Path to old config.php
 * @return array|false Database credentials array or false on failure
 */
function lupopedia_parse_old_config($config_path) {
    if (!file_exists($config_path) || !is_readable($config_path)) {
        return false;
    }
    
    // Read config file
    $content = file_get_contents($config_path);
    if ($content === false) {
        return false;
    }
    
    // Extract variables using regex (safer than include/require)
    $vars = [];
    
    // Extract $server
    if (preg_match('/\$server\s*=\s*[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
        $vars['host'] = $matches[1];
    } elseif (preg_match('/\$server\s*=\s*([^;]+);/', $content, $matches)) {
        $vars['host'] = trim($matches[1], " \t\n\r\0\x0B\"'");
    }
    
    // Extract $database
    if (preg_match('/\$database\s*=\s*[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
        $vars['name'] = $matches[1];
    } elseif (preg_match('/\$database\s*=\s*([^;]+);/', $content, $matches)) {
        $vars['name'] = trim($matches[1], " \t\n\r\0\x0B\"'");
    }
    
    // Extract $datausername
    if (preg_match('/\$datausername\s*=\s*[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
        $vars['user'] = $matches[1];
    } elseif (preg_match('/\$datausername\s*=\s*([^;]+);/', $content, $matches)) {
        $vars['user'] = trim($matches[1], " \t\n\r\0\x0B\"'");
    }
    
    // Extract $password
    if (preg_match('/\$password\s*=\s*[\'"]([^\'"]*)[\'"]/', $content, $matches)) {
        $vars['password'] = $matches[1];
    } elseif (preg_match('/\$password\s*=\s*([^;]+);/', $content, $matches)) {
        $vars['password'] = trim($matches[1], " \t\n\r\0\x0B\"'");
    } else {
        $vars['password'] = ''; // Empty password is valid
    }
    
    // Extract $dbtype (optional, defaults to mysql)
    if (preg_match('/\$dbtype\s*=\s*[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
        $vars['type'] = strtolower($matches[1]);
    } else {
        $vars['type'] = 'mysql'; // Default
    }
    
    // Validate we have minimum required fields
    if (empty($vars['host']) || empty($vars['name']) || empty($vars['user'])) {
        return false;
    }
    
    // Set defaults
    $vars['port'] = '3306';
    $vars['charset'] = 'utf8mb4';
    $vars['collate'] = 'utf8mb4_unicode_ci';
    
    return $vars;
}

/**
 * Generate authentication keys and salts
 * 
 * @return array Array of keys and salts
 */
function lupopedia_generate_auth_keys() {
    $keys = [];
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
    
    $key_names = ['AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 
                  'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT'];
    
    foreach ($key_names as $key_name) {
        $keys[$key_name] = '';
        for ($i = 0; $i < 64; $i++) {
            $keys[$key_name] .= $characters[rand(0, strlen($characters) - 1)];
        }
    }
    
    return $keys;
}

/**
 * Create lupopedia-config.php file
 * 
 * @param array $db_vars Database credentials
 * @param string $preferred_path Preferred path for config file
 * @return string|false Path to created config file or false on failure
 */
function lupopedia_create_config_file($db_vars, $preferred_path = null) {
    // Generate auth keys
    $auth_keys = lupopedia_generate_auth_keys();
    
    // Determine config file location
    $config_path = null;
    
    if ($preferred_path && is_writable(dirname($preferred_path))) {
        $config_path = $preferred_path;
    } else {
        // Try preferred location: one level above webroot
        $above_webroot = dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php';
        if (is_writable(dirname($above_webroot))) {
            $config_path = $above_webroot;
        } else {
            // Fallback: same directory as old config or lupopedia directory
            $fallback = dirname(__DIR__) . '/lupopedia-config.php';
            if (is_writable(dirname($fallback))) {
                $config_path = $fallback;
            }
        }
    }
    
    if (!$config_path) {
        return false;
    }
    
    // Determine ABSPATH (should be parent of lupopedia directory)
    $abspath = dirname(__DIR__) . '/';
    
    // Escape values for safe inclusion in PHP file
    $db_type = addslashes($db_vars['type']);
    $db_name = addslashes($db_vars['name']);
    $db_user = addslashes($db_vars['user']);
    $db_password = addslashes($db_vars['password']);
    $db_host = addslashes($db_vars['host']);
    $db_port = addslashes($db_vars['port']);
    $db_charset = addslashes($db_vars['charset']);
    $db_collate = addslashes($db_vars['collate']);
    $abspath_escaped = addslashes($abspath);
    
    // Escape auth keys
    foreach ($auth_keys as $key => $value) {
        $auth_keys[$key] = addslashes($value);
    }
    
    // Build config file content
    $config_content = <<<PHP
<?php
/**
 * Lupopedia Configuration File
 * 
 * This file contains the base configuration for Lupopedia.
 * Generated during upgrade from Crafty Syntax 3.7.5
 * 
 * @package Lupopedia
 */

// ** Directory and URL settings ** //
\$lupo_prefix = 'lupo-';

// ** Database settings ** //
define('DB_TYPE', '{$db_type}');
define('DB_NAME', '{$db_name}');
define('DB_USER', '{$db_user}');
define('DB_PORT', '{$db_port}');
define('DB_PASSWORD', '{$db_password}');
define('DB_HOST', '{$db_host}');
define('DB_CHARSET', '{$db_charset}');
define('DB_COLLATE', '{$db_collate}');

// ** Authentication unique keys and salts ** //
define('AUTH_KEY',         '{$auth_keys['AUTH_KEY']}');
define('SECURE_AUTH_KEY',  '{$auth_keys['SECURE_AUTH_KEY']}');
define('LOGGED_IN_KEY',    '{$auth_keys['LOGGED_IN_KEY']}');
define('NONCE_KEY',        '{$auth_keys['NONCE_KEY']}');
define('AUTH_SALT',        '{$auth_keys['AUTH_SALT']}');
define('SECURE_AUTH_SALT', '{$auth_keys['SECURE_AUTH_SALT']}');
define('LOGGED_IN_SALT',   '{$auth_keys['LOGGED_IN_SALT']}');
define('NONCE_SALT',       '{$auth_keys['NONCE_SALT']}');

// ** Application settings ** //
define('LUPOPEDIA_DEBUG', false);
define('LUPOPEDIA_ENV', 'production');

// ** Define directory constants ** //
if (!defined('ABSPATH')) {
    define('ABSPATH', '{$abspath_escaped}');
}

// ** Core directories ** //
define('LUPO_PREFIX', \$lupo_prefix);
define('LUPO_ADMIN_DIR', LUPO_PREFIX . 'admin');
define('LUPO_INCLUDES_DIR', LUPO_PREFIX . 'includes');
define('LUPO_CONTENT_DIR', LUPO_PREFIX . 'content');

// ** Content directories ** //
define('LUPO_UPLOADS_DIR', LUPO_CONTENT_DIR . '/uploads');
define('LUPO_PLUGINS_DIR', LUPO_CONTENT_DIR . '/plugins');
define('LUPO_THEMES_DIR', LUPO_CONTENT_DIR . '/themes');

// ** Set up the database table prefix ** //
\$table_prefix = 'lupo_';

// ** Absolute path to the Lupopedia directory ** //
if (!defined('LUPOPEDIA_ABSPATH')) {
    define('LUPOPEDIA_ABSPATH', ABSPATH);
}

define('LUPOPEDIA_CONFIG_LOADED', true);
// ** Load the main bootstrap file ** //
require_once ABSPATH . LUPO_INCLUDES_DIR . '/bootstrap.php';
PHP;

    // Write config file
    $result = @file_put_contents($config_path, $config_content);
    if ($result === false) {
        return false;
    }
    
    // Set secure permissions (644)
    @chmod($config_path, 0644);
    
    return $config_path;
}

/**
 * Test database connection
 * 
 * @param array $db_vars Database credentials
 * @return bool True if connection successful
 */
function lupopedia_test_db_connection($db_vars) {
    try {
        $dsn = "mysql:host={$db_vars['host']};port={$db_vars['port']};dbname={$db_vars['name']};charset={$db_vars['charset']}";
        $pdo = new PDO($dsn, $db_vars['user'], $db_vars['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5
        ]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Backup old config.php file
 * 
 * @param string $old_config_path Path to old config.php
 * @return bool True on success
 */
function lupopedia_backup_old_config($old_config_path) {
    $backup_path = $old_config_path . '.backup';
    return @copy($old_config_path, $backup_path);
}

/**
 * Detect livehelp_* tables in database
 * 
 * @param PDO $pdo Database connection
 * @return array Array of table names
 */
function lupopedia_detect_livehelp_tables($pdo) {
    $tables = [];
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE 'livehelp_%'");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
    } catch (PDOException $e) {
        // Return empty array on error
    }
    return $tables;
}

/**
 * Execute migration SQL file
 * 
 * @param PDO $pdo Database connection
 * @param string $sql_file Path to SQL file
 * @return array ['success' => bool, 'message' => string, 'errors' => array]
 */
function lupopedia_run_migration_sql($pdo, $sql_file) {
    if (!file_exists($sql_file) || !is_readable($sql_file)) {
        return [
            'success' => false,
            'message' => "Migration SQL file not found: {$sql_file}",
            'errors' => []
        ];
    }
    
    $sql_content = file_get_contents($sql_file);
    if ($sql_content === false) {
        return [
            'success' => false,
            'message' => "Could not read migration SQL file",
            'errors' => []
        ];
    }
    
    // Remove BOM if present
    $sql_content = preg_replace('/^\xEF\xBB\xBF/', '', $sql_content);
    
    // Split SQL into individual statements
    // Remove comments and empty lines
    $sql_content = preg_replace('/--.*$/m', '', $sql_content);
    $sql_content = preg_replace('/\/\*.*?\*\//s', '', $sql_content);
    $statements = array_filter(
        array_map('trim', explode(';', $sql_content)),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^\s*SET\s+/i', $stmt);
        }
    );
    
    $errors = [];
    $executed = 0;
    
    // Execute each statement
    $pdo->beginTransaction();
    
    try {
        foreach ($statements as $statement) {
            if (empty(trim($statement))) {
                continue;
            }
            
            try {
                $pdo->exec($statement);
                $executed++;
            } catch (PDOException $e) {
                // Log error but continue (some errors may be expected if tables already exist)
                $errors[] = [
                    'statement' => substr($statement, 0, 100) . '...',
                    'error' => $e->getMessage()
                ];
            }
        }
        
        $pdo->commit();
        
        return [
            'success' => true,
            'message' => "Migration executed successfully. {$executed} statements executed.",
            'errors' => $errors,
            'executed' => $executed
        ];
        
    } catch (Exception $e) {
        $pdo->rollBack();
        return [
            'success' => false,
            'message' => "Migration failed: " . $e->getMessage(),
            'errors' => $errors
        ];
    }
}

/**
 * Drop livehelp_* tables
 * 
 * @param PDO $pdo Database connection
 * @param array $tables Array of table names to drop
 * @return array ['success' => bool, 'message' => string, 'dropped' => array]
 */
function lupopedia_drop_livehelp_tables($pdo, $tables) {
    $dropped = [];
    $errors = [];
    
    $pdo->beginTransaction();
    
    try {
        foreach ($tables as $table) {
            try {
                $pdo->exec("DROP TABLE IF EXISTS `{$table}`");
                $dropped[] = $table;
            } catch (PDOException $e) {
                $errors[] = [
                    'table' => $table,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        $pdo->commit();
        
        return [
            'success' => empty($errors),
            'message' => "Dropped " . count($dropped) . " tables.",
            'dropped' => $dropped,
            'errors' => $errors
        ];
        
    } catch (Exception $e) {
        $pdo->rollBack();
        return [
            'success' => false,
            'message' => "Failed to drop tables: " . $e->getMessage(),
            'dropped' => $dropped,
            'errors' => $errors
        ];
    }
}

/**
 * Main setup/upgrade handler
 */
function lupopedia_run_setup() {
    // Handle wizard steps
    $step = isset($_GET['step']) ? $_GET['step'] : 'start';
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    // Check if config already exists (and we're not in migration wizard)
    if ($step == 'start') {
        $config_above = dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php';
        $config_in_dir = dirname(__DIR__) . '/lupopedia-config.php';
        
        if (file_exists($config_above) || file_exists($config_in_dir)) {
            // Config exists - check if we're in upgrade mode
            $old_config_path = lupopedia_find_old_config();
            
            if ($old_config_path) {
                // Config exists but old config also exists - continue to migration
                $step = 'upgrade';
            } else {
                // Config exists, no old config - redirect to main app
                header('Location: ' . dirname($_SERVER['PHP_SELF']) . '/index.php');
                exit;
            }
        }
    }
    
    // Look for old Crafty Syntax config
    $old_config_path = lupopedia_find_old_config();
    
    if ($old_config_path || $step == 'migrate' || $step == 'verify' || $step == 'drop') {
        // UPGRADE MODE: Migrate from Crafty Syntax 3.7.5
        
        // Parse old config
        $db_vars = lupopedia_parse_old_config($old_config_path);
        
        if (!$db_vars) {
            die('ERROR: Could not parse old config.php file. Please check file permissions and format.');
        }
        
        // Connect to database
        try {
            $dsn = "mysql:host={$db_vars['host']};port={$db_vars['port']};dbname={$db_vars['name']};charset={$db_vars['charset']}";
            $pdo = new PDO($dsn, $db_vars['user'], $db_vars['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 30
            ]);
        } catch (PDOException $e) {
            die('ERROR: Could not connect to database: ' . htmlspecialchars($e->getMessage()));
        }
        
        // Step 1: Create config file (if not exists)
        $config_above = dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php';
        $config_in_dir = dirname(__DIR__) . '/lupopedia-config.php';
        $config_exists = (file_exists($config_above) || file_exists($config_in_dir));
        
        if (!$config_exists && $step != 'migrate' && $step != 'verify' && $step != 'drop') {
            $new_config_path = lupopedia_create_config_file($db_vars);
            if (!$new_config_path) {
                die('ERROR: Could not create lupopedia-config.php file. Please check directory permissions.');
            }
            lupopedia_backup_old_config($old_config_path);
            define('LUPOPEDIA_CONFIG_PATH', $new_config_path);
        } elseif ($config_exists) {
            $config_path = file_exists($config_above) ? $config_above : $config_in_dir;
            define('LUPOPEDIA_CONFIG_PATH', $config_path);
            require_once $config_path;
        }
        
        // Step 2: Detect livehelp tables
        $livehelp_tables = lupopedia_detect_livehelp_tables($pdo);
        
        // Step 3: Run migration (if requested)
        if ($action == 'run_migration') {
            $migration_file = __DIR__ . '/../database/migrations/craftysyntax_to_lupopedia_mysql.sql';
            $migration_result = lupopedia_run_migration_sql($pdo, $migration_file);
            $step = 'verify';
        }
        
        // Step 4: Verify migration (if requested)
        if ($action == 'verify_ok') {
            $step = 'drop';
        }
        
        // Step 5: Drop livehelp tables (if requested)
        if ($action == 'drop_tables') {
            $drop_result = lupopedia_drop_livehelp_tables($pdo, $livehelp_tables);
            $step = 'complete';
        }
        
        // Display wizard
        lupopedia_display_upgrade_wizard($step, $livehelp_tables, $migration_result ?? null, $drop_result ?? null);
        
    } else {
        // FRESH INSTALL MODE: No old config found
        
        // For fresh installs, show setup wizard (to be implemented)
        // For now, show message that manual config is needed
        
        die('Fresh installation detected. Please create lupopedia-config.php manually or use the setup wizard (coming soon).');
    }
}

/**
 * Display upgrade wizard
 */
function lupopedia_display_upgrade_wizard($step, $livehelp_tables, $migration_result = null, $drop_result = null) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Lupopedia Upgrade Wizard</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
            .step { background: #f5f5f5; padding: 20px; margin: 20px 0; border-left: 4px solid #0073aa; }
            .success { background: #d4edda; border-color: #28a745; }
            .error { background: #f8d7da; border-color: #dc3545; }
            .warning { background: #fff3cd; border-color: #ffc107; }
            h1 { color: #0073aa; }
            h2 { color: #555; }
            ul { list-style: disc; margin-left: 30px; }
            button, .button { background: #0073aa; color: white; padding: 10px 20px; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
            button:hover, .button:hover { background: #005a87; }
            .button-danger { background: #dc3545; }
            .button-danger:hover { background: #c82333; }
        </style>
    </head>
    <body>
        <h1>🐺 Lupopedia Upgrade Wizard</h1>
        <p>Upgrading from Crafty Syntax 3.7.5 to Lupopedia 4.0.1</p>
        
        <?php if ($step == 'upgrade' || $step == 'start'): ?>
            <div class="step">
                <h2>Step 1: Ready to Upgrade</h2>
                <p>We detected a Crafty Syntax installation. The following <strong><?php echo count($livehelp_tables); ?></strong> legacy tables were found:</p>
                <?php if (!empty($livehelp_tables)): ?>
                    <ul>
                        <?php foreach ($livehelp_tables as $table): ?>
                            <li><code><?php echo htmlspecialchars($table); ?></code></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p><strong>No livehelp_* tables found.</strong> Migration may have already been completed, or this is a fresh installation.</p>
                <?php endif; ?>
                
                <?php if (!empty($livehelp_tables)): ?>
                    <form method="post">
                        <input type="hidden" name="action" value="run_migration">
                        <p><strong>Ready to migrate?</strong> This will:</p>
                        <ol>
                            <li>Run the migration SQL to move all data from legacy tables to Lupopedia tables</li>
                            <li>Verify the migration was successful</li>
                            <li>Optionally drop the legacy tables</li>
                        </ol>
                        <button type="submit">Run Migration</button>
                    </form>
                <?php else: ?>
                    <p><a href="index.php" class="button">Continue to Lupopedia</a></p>
                <?php endif; ?>
            </div>
            
        <?php elseif ($step == 'verify'): ?>
            <div class="step <?php echo $migration_result['success'] ? 'success' : 'error'; ?>">
                <h2>Step 2: Migration <?php echo $migration_result['success'] ? 'Completed' : 'Failed'; ?></h2>
                <p><?php echo htmlspecialchars($migration_result['message']); ?></p>
                
                <?php if (!empty($migration_result['errors'])): ?>
                    <p><strong>Warnings:</strong> Some statements produced errors (this may be normal if tables already exist):</p>
                    <ul>
                        <?php foreach (array_slice($migration_result['errors'], 0, 10) as $error): ?>
                            <li><?php echo htmlspecialchars($error['error']); ?></li>
                        <?php endforeach; ?>
                        <?php if (count($migration_result['errors']) > 10): ?>
                            <li>... and <?php echo count($migration_result['errors']) - 10; ?> more warnings</li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
                
                <?php if ($migration_result['success']): ?>
                    <p><strong>Please verify that your data migrated correctly before proceeding.</strong></p>
                    <p>Check your Lupopedia tables to ensure all data is present and correct.</p>
                    <form method="post">
                        <input type="hidden" name="action" value="verify_ok">
                        <button type="submit">Data Looks Good - Continue</button>
                    </form>
                <?php else: ?>
                    <p><a href="?step=upgrade" class="button">Go Back</a></p>
                <?php endif; ?>
            </div>
            
        <?php elseif ($step == 'drop'): ?>
            <div class="step warning">
                <h2>Step 3: Drop Legacy Tables</h2>
                <p><strong>⚠️ Warning:</strong> This will permanently delete the following <strong><?php echo count($livehelp_tables); ?></strong> legacy tables:</p>
                <ul>
                    <?php foreach ($livehelp_tables as $table): ?>
                        <li><code><?php echo htmlspecialchars($table); ?></code></li>
                    <?php endforeach; ?>
                </ul>
                <p><strong>This action cannot be undone!</strong> Make sure you have:</p>
                <ol>
                    <li>Verified all data migrated correctly</li>
                    <li>Created a database backup</li>
                    <li>Confirmed Lupopedia is working properly</li>
                </ol>
                <form method="post">
                    <input type="hidden" name="action" value="drop_tables">
                    <button type="submit" class="button-danger">Yes, Drop Legacy Tables</button>
                </form>
                <p><a href="?step=verify" class="button">Go Back</a></p>
            </div>
            
        <?php elseif ($step == 'complete'): ?>
            <div class="step success">
                <h2>✅ Upgrade Complete!</h2>
                <p><?php echo htmlspecialchars($drop_result['message']); ?></p>
                <?php if (!empty($drop_result['dropped'])): ?>
                    <p><strong>Dropped tables:</strong></p>
                    <ul>
                        <?php foreach ($drop_result['dropped'] as $table): ?>
                            <li><code><?php echo htmlspecialchars($table); ?></code></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <p><strong>Your Lupopedia installation is now complete!</strong></p>
                <p><a href="index.php" class="button">Continue to Lupopedia</a></p>
            </div>
        <?php endif; ?>
        
    </body>
    </html>
    <?php
}

// Run setup if accessed directly
if (php_sapi_name() !== 'cli') {
    lupopedia_run_setup();
}
