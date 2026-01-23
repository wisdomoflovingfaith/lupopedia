<?php
/**
 * wolfie.header.identity: session-helpers
 * wolfie.header.placement: /lupo-includes/functions/session-helpers.php
 * wolfie.header.version: 4.0.9
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Fixed critical bug in lupo_create_session() for version 4.0.9: expires_ymdhis calculation was adding seconds directly to YYYYMMDDHHMMSS timestamp (invalid). Now uses timestamp_ymdhis::addSeconds(). Added enhanced error logging and session_id length validation."
 *   mood: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. session-helpers.php cannot be called directly.");
}

/**
 * Session Management Functions
 * 
 * Provides database-backed session management using the lupo_sessions table.
 * Sessions are tracked in both PHP native sessions and the database for
 * security, auditing, and multi-device support.
 * 
 * @package Lupopedia
 * @subpackage Functions
 */

// Default session lifetime in seconds (24 hours)
if (!defined('LUPO_SESSION_LIFETIME')) {
    define('LUPO_SESSION_LIFETIME', 86400);
}

// Default federation node ID (domain/tenant identifier)
if (!defined('LUPO_DEFAULT_NODE_ID')) {
    define('LUPO_DEFAULT_NODE_ID', 1);
}

/**
 * Get current UTC timestamp in YYYYMMDDHHMMSS format
 * 
 * @return int UTC timestamp as BIGINT
 */
function lupo_utc_timestamp() {
    if (class_exists('timestamp_ymdhis')) {
        return timestamp_ymdhis::now();
    }
    // Fallback if class not available
    return (int) gmdate('YmdHis');
}

/**
 * Start PHP session if not already started
 * 
 * Initializes PHP session and ensures session cookie is set.
 * 
 * @return bool True if session started, false otherwise
 */
function lupo_start_session() {
    if (session_status() === PHP_SESSION_NONE) {
        return session_start();
    }
    return true;
}

/**
 * Get current PHP session ID
 * 
 * @return string|false Session ID or false if no session
 */
function lupo_get_session_id() {
    lupo_start_session();
    return session_id();
}

/**
 * Get client IP address
 * 
 * Handles proxy headers (X-Forwarded-For, X-Real-IP) for accurate IP detection.
 * 
 * @return string IP address (IPv4 or IPv6)
 */
function lupo_get_client_ip() {
    $ip_keys = [
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_REAL_IP',
        'HTTP_CLIENT_IP',
        'REMOTE_ADDR'
    ];
    
    foreach ($ip_keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = $_SERVER[$key];
            // Handle comma-separated IPs (X-Forwarded-For can have multiple)
            if (strpos($ip, ',') !== false) {
                $ips = explode(',', $ip);
                $ip = trim($ips[0]);
            }
            // Validate IP format
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    
    return '0.0.0.0';
}

/**
 * Get user agent string
 * 
 * @return string User agent string or empty string
 */
function lupo_get_user_agent() {
    return $_SERVER['HTTP_USER_AGENT'] ?? '';
}

/**
 * Detect device type from user agent
 * 
 * @param string $user_agent User agent string
 * @return string Device type: 'desktop', 'mobile', 'tablet', 'bot', or 'other'
 */
function lupo_detect_device_type($user_agent = null) {
    if ($user_agent === null) {
        $user_agent = lupo_get_user_agent();
    }
    
    if (empty($user_agent)) {
        return 'other';
    }
    
    // Check for bots
    $bot_patterns = ['bot', 'crawler', 'spider', 'scraper', 'curl', 'wget'];
    foreach ($bot_patterns as $pattern) {
        if (stripos($user_agent, $pattern) !== false) {
            return 'bot';
        }
    }
    
    // Check for mobile
    $mobile_patterns = ['mobile', 'android', 'iphone', 'ipod', 'blackberry', 'windows phone'];
    foreach ($mobile_patterns as $pattern) {
        if (stripos($user_agent, $pattern) !== false) {
            return 'mobile';
        }
    }
    
    // Check for tablet
    $tablet_patterns = ['ipad', 'tablet', 'playbook'];
    foreach ($tablet_patterns as $pattern) {
        if (stripos($user_agent, $pattern) !== false) {
            return 'tablet';
        }
    }
    
    return 'desktop';
}

/**
 * Create a new session record in the database
 * 
 * Creates a session record in lupo_sessions table and links it to an actor.
 * 
 * @param int $actor_id Actor ID (0 for anonymous)
 * @param string $auth_method Authentication method ('password', 'oauth', 'api_key', etc.)
 * @param string $auth_provider Authentication provider ('local', 'google', 'github', etc.)
 * @return string|false Session ID on success, false on failure
 */
function lupo_create_session($actor_id, $auth_method = 'password', $auth_provider = 'local') {
    if (!isset($GLOBALS['mydatabase'])) {
        $error_msg = "AUTH ERROR: Database connection not available in lupo_create_session()";
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log($error_msg);
            if (ini_get('display_errors') && !headers_sent()) {
                echo "<!-- " . htmlspecialchars($error_msg) . " -->\n";
            }
        }
        return false;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    // Verify database connection is valid PDO object
    if (!($db instanceof PDO)) {
        $error_msg = "AUTH ERROR: Database connection is not a PDO object in lupo_create_session()";
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log($error_msg);
            if (ini_get('display_errors') && !headers_sent()) {
                echo "<!-- " . htmlspecialchars($error_msg) . " -->\n";
            }
        }
        return false;
    }
    
    // Ensure session is started before getting ID
    lupo_start_session();
    $session_id = session_id();
    
    if (empty($session_id)) {
        $error_msg = "AUTH ERROR: Could not get PHP session ID in lupo_create_session() - session_status: " . session_status() . ", session_id(): " . var_export(session_id(), true);
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log($error_msg);
            if (ini_get('display_errors') && !headers_sent()) {
                echo "<!-- " . htmlspecialchars($error_msg) . " -->\n";
            }
        }
        return false;
    }
    
    // Debug: Log session ID retrieval
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
        error_log("SESSION DEBUG: Got PHP session ID: " . substr($session_id, 0, 20) . "... (length: " . strlen($session_id) . ")");
    }
    
    // Validate session_id length (TOON schema: varchar(100))
    if (strlen($session_id) > 100) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH ERROR: Session ID too long (" . strlen($session_id) . " chars, max 100) in lupo_create_session()");
        }
        // Truncate to 100 characters (should not happen with default PHP session config)
        $session_id = substr($session_id, 0, 100);
    }
    
    $now = lupo_utc_timestamp();
    
    // CRITICAL FIX: Use timestamp_ymdhis::addSeconds() instead of simple addition
    // Adding seconds directly to YYYYMMDDHHMMSS produces invalid timestamps
    if (class_exists('timestamp_ymdhis')) {
        $expires = timestamp_ymdhis::addSeconds($now, LUPO_SESSION_LIFETIME);
    } else {
        // Fallback: convert to epoch, add seconds, convert back
        $epoch = gmmktime(
            (int)substr($now, 8, 2),   // hour
            (int)substr($now, 10, 2),  // minute
            (int)substr($now, 12, 2),   // second
            (int)substr($now, 4, 2),    // month
            (int)substr($now, 6, 2),    // day
            (int)substr($now, 0, 4)     // year
        );
        $expires = (int)gmdate('YmdHis', $epoch + LUPO_SESSION_LIFETIME);
    }
    
    $ip_address = lupo_get_client_ip();
    $user_agent = lupo_get_user_agent();
    $device_type = lupo_detect_device_type($user_agent);
    
    // Determine security level based on auth method
    $security_level = 'medium';
    if ($auth_method === 'api_key') {
        $security_level = 'high';
    } elseif ($auth_method === 'password' && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $security_level = 'high';
    }
    
    // Initialize variables for error logging
    $sql = '';
    $params = [];
    
    try {
        // Verify LUPO_PREFIX is defined
        if (!defined('LUPO_PREFIX')) {
            $error_msg = "AUTH ERROR: LUPO_PREFIX constant not defined in lupo_create_session()";
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log($error_msg);
                if (ini_get('display_errors')) {
                    echo "<!-- " . htmlspecialchars($error_msg) . " -->\n";
                }
            }
            return false;
        }
        
        // Use table prefix constant if defined, otherwise fallback to LUPO_PREFIX with underscore replacement
        if (defined('LUPO_TABLE_PREFIX')) {
            $table_prefix = LUPO_TABLE_PREFIX;
        } else {
            // Fallback: replace dash with underscore for table names
            $table_prefix = str_replace('-', '_', LUPO_PREFIX);
        }
        $table_name = $table_prefix . 'sessions';
        
        // Verify table exists (debug only)
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            try {
                $check_sql = "SELECT 1 FROM {$table_name} LIMIT 1";
                $check_stmt = $db->prepare($check_sql);
                $check_stmt->execute();
            } catch (PDOException $e) {
                $error_msg = "AUTH ERROR: Table {$table_name} does not exist or is not accessible: " . $e->getMessage();
                error_log($error_msg);
                if (ini_get('display_errors')) {
                    echo "<!-- " . htmlspecialchars($error_msg) . " -->\n";
                }
                return false;
            }
        }
        
        $sql = "INSERT INTO {$table_name} (
            session_id,
            federation_node_id,
            actor_id,
            ip_address,
            user_agent,
            device_type,
            auth_method,
            auth_provider,
            security_level,
            is_active,
            is_expired,
            is_revoked,
            login_ymdhis,
            last_seen_ymdhis,
            expires_ymdhis,
            created_ymdhis,
            updated_ymdhis,
            is_deleted
        ) VALUES (
            :session_id,
            :federation_node_id,
            :actor_id,
            :ip_address,
            :user_agent,
            :device_type,
            :auth_method,
            :auth_provider,
            :security_level,
            1,
            0,
            0,
            :login_ymdhis,
            :last_seen_ymdhis,
            :expires_ymdhis,
            :created_ymdhis,
            :updated_ymdhis,
            0
        )";
        
        $stmt = $db->prepare($sql);
        
        // Prepare parameters
        $params = [
            ':session_id' => $session_id,
            ':federation_node_id' => LUPO_DEFAULT_NODE_ID,
            ':actor_id' => (int)$actor_id,
            ':ip_address' => $ip_address,
            ':user_agent' => $user_agent,
            ':device_type' => $device_type,
            ':auth_method' => $auth_method,
            ':auth_provider' => $auth_provider,
            ':security_level' => $security_level,
            ':login_ymdhis' => $now,
            ':last_seen_ymdhis' => $now,
            ':expires_ymdhis' => $expires,
            ':created_ymdhis' => $now,
            ':updated_ymdhis' => $now
        ];
        
        // Enhanced debug logging before execute
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            $debug_msg = "SESSION DEBUG: Attempting INSERT - Session ID: " . substr($session_id, 0, 20) . "... (length: " . strlen($session_id) . "), Actor ID: " . $actor_id . ", Expires: " . $expires . ", Table: {$table_name}, Now: " . $now;
            error_log($debug_msg);
            error_log("SESSION DEBUG: SQL = " . $sql);
            error_log("SESSION DEBUG: Params = " . json_encode($params));
            
            // Also display on screen in debug mode (visible, not just HTML comment)
            if (ini_get('display_errors')) {
                echo "<!-- " . htmlspecialchars($debug_msg) . " -->\n";
                echo "<!-- SQL: " . htmlspecialchars($sql) . " -->\n";
                echo "<!-- Params: " . htmlspecialchars(json_encode($params, JSON_PRETTY_PRINT)) . " -->\n";
            }
        }
        
        // Execute the statement
        $result = $stmt->execute($params);
        
        // Check for PDO errors even if execute() didn't throw
        if (!$result) {
            $error_info = $stmt->errorInfo();
            $error_msg = "AUTH ERROR: PDO execute() returned false - SQLSTATE: " . ($error_info[0] ?? 'unknown') . ", Error: " . ($error_info[2] ?? 'unknown');
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log($error_msg);
                // Display on screen in debug mode
                if (ini_get('display_errors')) {
                    echo "<!-- " . htmlspecialchars($error_msg) . " -->\n";
                }
            }
            throw new Exception("PDO execute failed: " . ($error_info[2] ?? 'Unknown error'));
        }
        
        // Verify row was inserted
        $row_count = $stmt->rowCount();
        if ($row_count === 0) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log("AUTH ERROR: INSERT executed but no rows affected - Session ID: " . substr($session_id, 0, 8) . "...");
            }
            throw new Exception("INSERT executed but no rows affected");
        }
        
        // Debug logging for successful session creation
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("SESSION: Session created successfully - ID: " . substr($session_id, 0, 8) . "..., Actor ID: " . $actor_id . ", Expires: " . $expires . ", Rows affected: " . $row_count);
        }
        
        return $session_id;
        
    } catch (PDOException $e) {
        // PDO-specific errors
        $error_info = $e->errorInfo ?? [];
        $error_msg = "AUTH ERROR: PDOException in lupo_create_session() - SQLSTATE: " . ($error_info[0] ?? $e->getCode()) . ", Error: " . $e->getMessage();
        
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log($error_msg);
            error_log("AUTH ERROR: File: " . $e->getFile() . ", Line: " . $e->getLine());
            if (!empty($sql)) {
                error_log("AUTH ERROR: SQL: " . $sql);
            }
            if (!empty($params)) {
                error_log("AUTH ERROR: Params: " . json_encode($params));
            }
            
            // Display on screen in debug mode for immediate visibility
            // Only output if headers haven't been sent (prevents "headers already sent" errors)
            if (ini_get('display_errors') && !headers_sent()) {
                echo "<!-- " . htmlspecialchars($error_msg) . " -->\n";
                echo "<!-- File: " . htmlspecialchars($e->getFile()) . ", Line: " . $e->getLine() . " -->\n";
                if (!empty($sql)) {
                    echo "<!-- SQL: " . htmlspecialchars($sql) . " -->\n";
                }
                if (!empty($params)) {
                    echo "<!-- Params: " . htmlspecialchars(json_encode($params)) . " -->\n";
                }
            }
        }
        return false;
    } catch (Exception $e) {
        // General exceptions
        $error_msg = "AUTH ERROR: Exception in lupo_create_session(): " . $e->getMessage() . " (File: " . $e->getFile() . ", Line: " . $e->getLine() . ")";
        
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log($error_msg);
            if (!empty($sql)) {
                error_log("AUTH ERROR: SQL: " . $sql);
            }
            
            // Display on screen in debug mode for immediate visibility
            if (ini_get('display_errors') && !headers_sent()) {
                echo "<!-- " . htmlspecialchars($error_msg) . " -->\n";
                if (!empty($sql)) {
                    echo "<!-- SQL: " . htmlspecialchars($sql) . " -->\n";
                }
            }
        }
        return false;
    }
}

/**
 * Validate a session
 * 
 * Checks if a session exists, is active, not expired, and not revoked.
 * Updates last_seen_ymdhis if session is valid.
 * 
 * @param string $session_id Session ID to validate
 * @return int|false Actor ID if session is valid, false otherwise
 */
function lupo_validate_session($session_id = null) {
    if ($session_id === null) {
        $session_id = lupo_get_session_id();
    }
    
    if (empty($session_id)) {
        return false;
    }
    
    if (!isset($GLOBALS['mydatabase'])) {
        return false;
    }
    
    $db = $GLOBALS['mydatabase'];
    $now = lupo_utc_timestamp();
    
    try {
        // Use table prefix constant if defined, otherwise fallback to LUPO_PREFIX with underscore replacement
        if (defined('LUPO_TABLE_PREFIX')) {
            $table_prefix = LUPO_TABLE_PREFIX;
        } else {
            $table_prefix = str_replace('-', '_', LUPO_PREFIX);
        }
        $sql = "SELECT 
            actor_id,
            is_active,
            is_expired,
            is_revoked,
            expires_ymdhis
        FROM {$table_prefix}sessions
        WHERE session_id = :session_id
          AND is_deleted = 0
        LIMIT 1";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':session_id' => $session_id]);
        $session = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$session) {
            return false;
        }
        
        // Check if session is active
        if ($session['is_active'] != 1) {
            return false;
        }
        
        // Check if session is expired
        if ($session['is_expired'] == 1) {
            return false;
        }
        
        // Check if session is revoked
        if ($session['is_revoked'] == 1) {
            return false;
        }
        
        // Check if session has expired by time
        if ($session['expires_ymdhis'] < $now) {
            // Mark as expired
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log("SESSION: Session expired by time - ID: " . substr($session_id, 0, 8) . "..., Expires: " . $session['expires_ymdhis'] . ", Now: " . $now);
            }
            lupo_mark_session_expired($session_id);
            return false;
        }
        
        // Update last seen timestamp
        lupo_update_session_activity($session_id);
        
        return (int)$session['actor_id'];
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH ERROR: Failed to validate session: " . $e->getMessage());
        }
        return false;
    }
}

/**
 * Update session activity timestamp
 * 
 * Updates last_seen_ymdhis for a session.
 * 
 * @param string $session_id Session ID
 * @return bool True on success, false on failure
 */
function lupo_update_session_activity($session_id = null) {
    if ($session_id === null) {
        $session_id = lupo_get_session_id();
    }
    
    if (empty($session_id)) {
        return false;
    }
    
    if (!isset($GLOBALS['mydatabase'])) {
        return false;
    }
    
    $db = $GLOBALS['mydatabase'];
    $now = lupo_utc_timestamp();
    
    try {
        // Use table prefix constant if defined, otherwise fallback to LUPO_PREFIX with underscore replacement
        if (defined('LUPO_TABLE_PREFIX')) {
            $table_prefix = LUPO_TABLE_PREFIX;
        } else {
            $table_prefix = str_replace('-', '_', LUPO_PREFIX);
        }
        $sql = "UPDATE {$table_prefix}sessions
        SET last_seen_ymdhis = :last_seen_ymdhis,
            updated_ymdhis = :updated_ymdhis
        WHERE session_id = :session_id
          AND is_deleted = 0";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':session_id' => $session_id,
            ':last_seen_ymdhis' => $now,
            ':updated_ymdhis' => $now
        ]);
        
        return true;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH ERROR: Failed to update session activity: " . $e->getMessage());
        }
        return false;
    }
}

/**
 * Mark a session as expired
 * 
 * @param string $session_id Session ID
 * @return bool True on success, false on failure
 */
function lupo_mark_session_expired($session_id) {
    if (empty($session_id)) {
        return false;
    }
    
    if (!isset($GLOBALS['mydatabase'])) {
        return false;
    }
    
    $db = $GLOBALS['mydatabase'];
    $now = lupo_utc_timestamp();
    
    try {
        // Use table prefix constant if defined, otherwise fallback to LUPO_PREFIX with underscore replacement
        if (defined('LUPO_TABLE_PREFIX')) {
            $table_prefix = LUPO_TABLE_PREFIX;
        } else {
            $table_prefix = str_replace('-', '_', LUPO_PREFIX);
        }
        $sql = "UPDATE {$table_prefix}sessions
        SET is_expired = 1,
            is_active = 0,
            updated_ymdhis = :updated_ymdhis
        WHERE session_id = :session_id
          AND is_deleted = 0";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':session_id' => $session_id,
            ':updated_ymdhis' => $now
        ]);
        
        // Debug logging for session expiration
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("SESSION: Session marked as expired - ID: " . substr($session_id, 0, 8) . "...");
        }
        
        return true;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH ERROR: Failed to mark session expired: " . $e->getMessage());
        }
        return false;
    }
}

/**
 * Destroy a session
 * 
 * Marks session as inactive and revoked in database, then destroys PHP session.
 * 
 * @param string $session_id Session ID (optional, uses current session if not provided)
 * @return bool True on success, false on failure
 */
function lupo_destroy_session($session_id = null) {
    if ($session_id === null) {
        $session_id = lupo_get_session_id();
    }
    
    if (empty($session_id)) {
        return false;
    }
    
    if (!isset($GLOBALS['mydatabase'])) {
        return false;
    }
    
    $db = $GLOBALS['mydatabase'];
    $now = lupo_utc_timestamp();
    
    try {
        // Use table prefix constant if defined, otherwise fallback to LUPO_PREFIX with underscore replacement
        if (defined('LUPO_TABLE_PREFIX')) {
            $table_prefix = LUPO_TABLE_PREFIX;
        } else {
            $table_prefix = str_replace('-', '_', LUPO_PREFIX);
        }
        $sql = "UPDATE {$table_prefix}sessions
        SET is_active = 0,
            is_revoked = 1,
            updated_ymdhis = :updated_ymdhis
        WHERE session_id = :session_id
          AND is_deleted = 0";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':session_id' => $session_id,
            ':updated_ymdhis' => $now
        ]);
        
        // Destroy PHP session
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
            }
            session_destroy();
        }
        
        // Debug logging for session destruction
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("SESSION: Session destroyed - ID: " . substr($session_id, 0, 8) . "...");
        }
        
        return true;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH ERROR: Failed to destroy session: " . $e->getMessage());
        }
        return false;
    }
}

?>
