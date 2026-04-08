<?php
/**
 * Lupopedia 4.0.26 — Install Wizard Classes
 *
 * Helper logic converted from install.php into classes per CLASS_CONVERSION_DOCTRINE.md.
 * PHP 5.3–compatible: no type hints, no return types, no short arrays, no ??.
 * Loaded by install.php only. Reference semantics (&$log) preserved.
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'InstallWizardMysqliLink.php';

class InstallWizardSteps
{

    public static function getWizardSteps()
    {
        $isUpgrade = ((isset($_SESSION['lupo_install_type']) ? $_SESSION['lupo_install_type'] : '') === 'upgrade');
        if ($isUpgrade) {
            return array(
                array('id' => 'welcome', 'label' => 'Welcome'),
                array('id' => 'credentials', 'label' => 'Database'),
                array('id' => 'bootstrap', 'label' => 'Bootstrap'),
                array('id' => 'normalize', 'label' => 'Identity'),
                array('id' => 'confirm', 'label' => 'Confirm'),
                array('id' => 'run', 'label' => 'Run'),
                array('id' => 'config', 'label' => 'Config'),
                array('id' => 'complete', 'label' => 'Complete'),
            );
        }
        return array(
            array('id' => 'welcome', 'label' => 'Welcome'),
            array('id' => 'credentials', 'label' => 'Database'),
            array('id' => 'confirm', 'label' => 'Confirm'),
            array('id' => 'run', 'label' => 'Run'),
            array('id' => 'config', 'label' => 'Config'),
            array('id' => 'complete', 'label' => 'Complete'),
        );
    }

    public static function getCurrentStepIndex($step)
    {
        $steps = self::getWizardSteps();
        foreach ($steps as $i => $s) {
            if ($s['id'] === $step) {
                return $i + 1;
            }
        }
        return 1;
    }

    public static function getTotalSteps()
    {
        return count(self::getWizardSteps());
    }
}

class InstallWizardSecurity
{

    public static function getCsrfToken()
    {
        if (empty($_SESSION['lupo_csrf_token'])) {
            $_SESSION['lupo_csrf_token'] = bin2hex(lupo_random_bytes(32));
        }
        return $_SESSION['lupo_csrf_token'];
    }

    public static function validateCsrf()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return true;
        }
        $token = isset($_POST['lupo_csrf']) ? $_POST['lupo_csrf'] : '';
        $stored = isset($_SESSION['lupo_csrf_token']) ? $_SESSION['lupo_csrf_token'] : '';
        return $token !== '' && lupo_hash_equals($stored, $token);
    }
}

class InstallWizardLogger
{

    public static function logEntry($type, $message)
    {
        $ts = date('c');
        $entry = array($type, $message, $ts);
        if (!isset($_SESSION['lupo_wizard_audit_log'])) {
            $_SESSION['lupo_wizard_audit_log'] = array();
        }
        $_SESSION['lupo_wizard_audit_log'][] = $entry;
        return $entry;
    }

    public static function safeErrorMessage($context = 'operation')
    {
        return 'A database ' . $context . ' failed. Please check your data and try again, or download the log.';
    }
}

class InstallWizardCredentials
{

    /**
     * Whether a Crafty Syntax config.php exists in any standard location.
     * If true, this is for sure an upgrade from Crafty Syntax (use for install_type).
     * @return bool
     */
    public static function craftyConfigExists()
    {
        $config_paths = array(
            $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php',
            dirname($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . 'config.php',
            $_SERVER['DOCUMENT_ROOT'] . '/../config.php'
        );

        foreach ($config_paths as $config) {
            if (file_exists($config) && is_readable($config)) {
                $content = file_get_contents($config);
                if (
                    $content !== false &&
                    (strpos($content, '$server') !== false || strpos($content, '$database') !== false)
                ) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Detect Crafty Syntax 3.7.5 version from config file content.
     * @return string|null Version detected (e.g., '3.7.5', '3.6.0', null)
     */
    public static function crafty3775VersionDetect()
    {
        $config_paths = [
            $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php',
            dirname($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . 'config.php',
            $_SERVER['DOCUMENT_ROOT'] . '/../config.php'
        ];

        foreach ($config_paths as $config) {
            if (file_exists($config) && is_readable($config)) {
                $content = file_get_contents($config);
                if ($content !== false) {
                    // Look for Crafty 3.7.5 signatures
                    if (preg_match('/livehelp_[\d\.]+\.\d+/', $content)) {
                        return '3.7.5';
                    }
                    // Look for version strings in common patterns
                    if (preg_match('/\$livehelp_version\s*=\s*[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
                        return $matches[1]; // Extract version
                    }
                }
            }
        }
        return null;
    }

    /**
     * Validate pre-migration state for Crafty upgrade.
     * Checks if required Crafty tables exist and are accessible.
     *
     * @param object $pdo
     * @param string|null $table_prefix LUPO_TABLE_PREFIX (e.g. lupo_); used only for actors join
     * @return array Validation result
     */
    public static function validateCraftyPreMigration($pdo, $table_prefix = null)
    {
        $validation_results = array();

        try {
            $tp = ($table_prefix !== null && $table_prefix !== '')
                ? $table_prefix
                : (defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_');
            $actors_table = preg_replace('/[^a-zA-Z0-9_]/', '', $tp . 'actors');
            $actors_ident = '`' . str_replace('`', '``', $actors_table) . '`';

            // Check key Crafty tables that must exist for migration
            $required_tables = array(
                'livehelp_users',
                'livehelp_operator_departments',
                'livehelp_departments',
                'livehelp_operator_history',
                'livehelp_requests',
                'livehelp_visits',
                'livehelp_messages',
                'livehelp_chat_transcripts',
                'livehelp_keywords'
            );

            foreach ($required_tables as $table) {
                $t = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
                if (!InstallWizardDb::tableExists($pdo, $t)) {
                    $validation_results[] = array(
                        'test' => 'crafty_tables_exist',
                        'status' => 'FAIL',
                        'message' => "Required Crafty table '$table' not found"
                    );
                    return $validation_results;
                }
            }

            // Check for data integrity (no orphaned users)
            $result = $pdo->query("
                SELECT COUNT(*) as orphaned_count 
                FROM livehelp_users lu 
                LEFT JOIN " . $actors_ident . " a ON lu.username = a.slug 
                WHERE a.actor_id IS NULL
            ");

            $orphaned_count = $result ? (int) $result->fetchColumn() : 0;
            if ($orphaned_count > 0) {
                $validation_results[] = array(
                    'test' => 'crafty_data_integrity',
                    'status' => 'FAIL',
                    'message' => "Found {$orphaned_count} orphaned Crafty users"
                );
                return $validation_results;
            }

            $validation_results[] = array('test' => 'crafty_pre_migration_check', 'status' => 'PASS');
            return $validation_results;

        } catch (Exception $e) {
            $validation_results[] = array(
                'test' => 'crafty_pre_migration_check',
                'status' => 'ERROR',
                'message' => $e->getMessage()
            );
            return $validation_results;
        }
    }

    public static function getDbCredentials()
    {
        if (!empty($_POST['db_host']) && !empty($_POST['db_name']) && !empty($_POST['db_user'])) {
            return array(
                'host' => trim((string) $_POST['db_host']),
                'port' => !empty($_POST['db_port']) ? trim((string) $_POST['db_port']) : '3306',
                'name' => trim((string) $_POST['db_name']),
                'user' => trim((string) $_POST['db_user']),
                'password' => (string) (isset($_POST['db_password']) ? $_POST['db_password'] : ''),
                'charset' => 'utf8mb4',
                'type' => 'mysql',
            );
        }
        if (!empty($_SESSION['lupo_install_db_vars']) && is_array($_SESSION['lupo_install_db_vars'])) {
            return $_SESSION['lupo_install_db_vars'];
        }
        $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '';
        $search = array(
            dirname(LUPOPEDIA_PATH) . DIRECTORY_SEPARATOR . 'config.php',
            ($docRoot !== '' ? $docRoot . DIRECTORY_SEPARATOR . 'config.php' : ''),
            LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'config.php',
        );
        foreach ($search as $path) {
            if ($path === '' || !is_file($path) || !is_readable($path)) {
                continue;
            }
            $content = @file_get_contents($path);
            if ($content === false || (strpos($content, '$server') === false && strpos($content, '$database') === false)) {
                continue;
            }
            $vars = array();
            if (preg_match('/\$server\s*=\s*[\'"]([^\'"]+)[\'"]/', $content, $m)) {
                $vars['host'] = $m[1];
            }
            if (preg_match('/\$database\s*=\s*[\'"]([^\'"]+)[\'"]/', $content, $m)) {
                $vars['name'] = $m[1];
            }
            if (preg_match('/\$datausername\s*=\s*[\'"]([^\'"]+)[\'"]/', $content, $m)) {
                $vars['user'] = $m[1];
            }
            if (preg_match('/\$password\s*=\s*[\'"]([^\'"]*)[\'"]/', $content, $m)) {
                $vars['password'] = $m[1];
            } else {
                $vars['password'] = '';
            }
            if (!empty($vars['host']) && !empty($vars['name']) && !empty($vars['user'])) {
                $vars['port'] = '3306';
                $vars['charset'] = 'utf8mb4';
                $vars['type'] = 'mysql';
                return $vars;
            }
        }
        return null;
    }
}

class InstallWizardDb
{

    /**
     * Install wizard DB connection (mysqli, WordPress-style — avoids PDO MySQL 2014 unbuffered conflicts).
     *
     * @param array $vars host, port, name, user, password, charset
     * @return InstallWizardMysqliLink
     */
    public static function connectPdo($vars)
    {
        return new InstallWizardMysqliLink($vars);
    }

    /**
     * Real PDO with buffered queries for code paths that require PDO_DB (e.g. ai_activation during install).
     *
     * @param array $vars
     * @return PDO
     */
    public static function connectPdoBuffered($vars)
    {
        if (!extension_loaded('pdo_mysql')) {
            throw new Exception('pdo_mysql extension is required for PDO_DB during install.');
        }
        $dsn = "mysql:host={$vars['host']};port={$vars['port']};dbname={$vars['name']};charset={$vars['charset']}";
        $opts = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 30,
            PDO::ATTR_STRINGIFY_FETCHES => true,
            PDO::ATTR_EMULATE_PREPARES => false,
        );
        if (defined('PDO::MYSQL_ATTR_USE_BUFFERED_QUERY')) {
            $opts[PDO::MYSQL_ATTR_USE_BUFFERED_QUERY] = true;
        }
        $pdo = new PDO($dsn, $vars['user'], $vars['password'], $opts);
        return $pdo;
    }

    /**
     * Escape a literal for MySQL LIKE (default escape \). Does not use information_schema (shared-host safe).
     *
     * @param string $literal
     * @return string
     */
    public static function escapeMysqlLikePattern($literal)
    {
        return str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), (string) $literal);
    }

    /**
     * Whether a table exists (SHOW TABLES LIKE with escaped _/% — no information_schema).
     *
     * @param object $pdo InstallWizardMysqliLink or PDO with query() and quote()
     * @param string $tableName Full physical name (e.g. myprefix_departments)
     * @return bool
     */
    public static function tableExists($pdo, $tableName)
    {
        $t = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $tableName);
        if ($t === '') {
            return false;
        }
        $pat = self::escapeMysqlLikePattern($t);
        $stmt = $pdo->query('SHOW TABLES LIKE ' . $pdo->quote($pat));

        return $stmt && $stmt->rowCount() > 0;
    }

    public static function detectLivehelpTables($pdo)
    {
        $tables = array();
        // Literal prefix livehelp_ then any suffix: escape the underscore so LIKE _ is not a wildcard.
        $stmt = $pdo->query("SHOW TABLES LIKE 'livehelp\\_%'");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        return $tables;
    }
}

class InstallWizardRegistryValidator
{

    /**
     * Extract registry_id values from SQL that contains INSERT INTO ... registry ... VALUES.
     * Only considers statements that insert into registry (ignores other tables).
     *
     * @param string $sql Raw SQL content (will be split by ; for statement detection)
     * @return array List of integer IDs (may be empty)
     */
    public static function extractRegistryIdsFromSql($sql)
    {
        $ids = array();
        $statements = explode(';', $sql);
        foreach ($statements as $stmt) {
            if (stripos($stmt, 'INSERT') === false || stripos($stmt, 'registry') === false || stripos($stmt, 'VALUES') === false) {
                continue;
            }
            if (preg_match_all('/\s*(\d+)\s*,/', $stmt, $m)) {
                $ids = array_merge($ids, array_map('intval', $m));
            }
        }
        return array_unique($ids);
    }

    /**
     * Check if any of the given IDs already exist in lupo_registry.
     * Doctrine: if exists, must not insert — show fatal error.
     *
     * @param PDO $pdo
     * @param array $ids List of registry_id values about to be inserted
     * @param string|null $tableName Full table name (default: LUPO_TABLE_PREFIX . 'registry')
     * @return int|null First conflicting ID, or null if no conflict
     */
    public static function checkRegistryIdConflict($pdo, $ids, $tableName = null)
    {
        if ($tableName === null || $tableName === '') {
            $tableName = (defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_') . 'registry';
        }
        if (empty($ids)) {
            return null;
        }

        $tableSafe = preg_replace('/[^a-zA-Z0-9_]/', '', $tableName);
        foreach ($ids as $id) {
            $id = (int) $id;
            try {
                $stmt = $pdo->prepare('SELECT 1 FROM ' . $tableSafe . ' WHERE registry_id = :id LIMIT 1');
                $stmt->execute(array('id' => $id));
                if ($stmt->fetch()) {
                    return $id;
                }
            } catch (Exception $e) {
                // Log error but continue checking
                continue;
            }
        }
        return null;
    }
}

class InstallWizardSqlRunner
{

    /**
     * Apply table prefix to SQL: {{prefix}} is always replaced (default lupo_); if prefix is not lupo_, also replace
     * literal lupo_ only outside single-quoted string literals (avoids corrupting text inside VALUES (...)).
     *
     * @param string $sql
     * @param string|null $table_prefix
     * @return string
     */
    public static function applyTablePrefixToSql($sql, $table_prefix = null)
    {
        $tp = ($table_prefix !== null && $table_prefix !== '') ? $table_prefix : 'lupo_';
        $sql = str_replace('{{prefix}}', $tp, $sql);
        if ($tp === 'lupo_') {
            return $sql;
        }
        $from = 'lupo_';
        $to = $tp;
        $fromLen = strlen($from);
        $len = strlen($sql);
        $out = '';
        $inSingle = false;
        $i = 0;
        while ($i < $len) {
            $c = $sql[$i];
            if ($inSingle) {
                if ($c === '\\' && $i + 1 < $len) {
                    $out .= $c . $sql[$i + 1];
                    $i += 2;
                    continue;
                }
                if ($c === "'" && $i + 1 < $len && $sql[$i + 1] === "'") {
                    $out .= "''";
                    $i += 2;
                    continue;
                }
                if ($c === "'") {
                    $inSingle = false;
                }
                $out .= $c;
                $i++;
            } else {
                if ($c === "'") {
                    $inSingle = true;
                    $out .= $c;
                    $i++;
                    continue;
                }
                if ($i + $fromLen <= $len && substr($sql, $i, $fromLen) === $from) {
                    $out .= $to;
                    $i += $fromLen;
                    continue;
                }
                $out .= $c;
                $i++;
            }
        }

        return $out;
    }

    /**
     * Split SQL into statements by semicolon, respecting single-quoted strings so that
     * semicolons inside COMMENT = '...;...' or other string literals do not break the split.
     * Escaped single quote '' is supported. PHP 5.3 compatible.
     *
     * @param string $sql
     * @return array Non-empty trimmed statements
     */
    public static function splitSqlStatements($sql)
    {
        $len = strlen($sql);
        $statements = array();
        $current = '';
        $inSingle = false;
        $i = 0;
        while ($i < $len) {
            $c = $sql[$i];
            if ($inSingle) {
                // Handle backslash escapes inside single-quoted strings (e.g. \')
                if ($c === "\\" && $i + 1 < $len) {
                    $current .= $c . $sql[$i + 1];
                    $i += 2;
                    continue;
                }
                if ($c === "'" && $i + 1 < $len && $sql[$i + 1] === "'") {
                    $current .= "''";
                    $i += 2;
                    continue;
                }
                if ($c === "'") {
                    $inSingle = false;
                }
                $current .= $c;
                $i++;
            } else {
                if ($c === ';') {
                    $trimmed = trim($current);
                    if ($trimmed !== '') {
                        $statements[] = $trimmed;
                    }
                    $current = '';
                    $i++;
                } elseif ($c === "'") {
                    $inSingle = true;
                    $current .= $c;
                    $i++;
                } else {
                    $current .= $c;
                    $i++;
                }
            }
        }
        $trimmed = trim($current);
        if ($trimmed !== '') {
            $statements[] = $trimmed;
        }
        return $statements;
    }

    /**
     * Run a SQL file. Replaces {{prefix}} with $table_prefix (default lupo_). When prefix is not lupo_, also replaces literal lupo_ (legacy seed files).
     *
     * @param PDO $pdo
     * @param string $path Full path to .sql file
     * @param array $log Log array (by reference)
     * @param string|null $table_prefix Table prefix including trailing underscore (e.g. 'lupo_'). Null defaults to lupo_; {{prefix}} in SQL is always replaced.
     * @return bool
     */
    public static function runSqlFile($pdo, $path, &$log, $table_prefix = null)
    {
        $basename = basename($path);
        if (!is_file($path) || !is_readable($path)) {
            $log[] = InstallWizardLogger::logEntry('error', 'File not found or not readable: ' . $basename);
            return false;
        }
        $sql = file_get_contents($path);
        if ($sql === false) {
            $log[] = InstallWizardLogger::logEntry('error', 'Could not read file: ' . $basename);
            return false;
        }
        $sql = preg_replace('/^\xEF\xBB\xBF/', '', $sql);
        // Only strip lines that are entirely comment (whitespace + -- + rest). Do not strip -- in middle of line (can be inside string or identifier).
        $sql = preg_replace('/^\s*--[^\n]*\n/m', "\n", $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        $sql = InstallWizardSqlRunner::applyTablePrefixToSql($sql, $table_prefix);
        $statements = InstallWizardSqlRunner::splitSqlStatements($sql);
        if (stripos($sql, 'registry') !== false && stripos($sql, 'INSERT') !== false) {
            $ids = InstallWizardRegistryValidator::extractRegistryIdsFromSql($sql);
            if (!empty($ids)) {
                $conflictId = InstallWizardRegistryValidator::checkRegistryIdConflict($pdo, $ids, null);
                if ($conflictId !== null) {
                    throw new RuntimeException('Registry ID conflict: ID ' . (int) $conflictId . ' already exists.');
                }
            }
        }
        $ok = true;
        $idx = 0;
        foreach ($statements as $stmt) {
            $idx++;
            $stmtTrim = trim($stmt);
            if ($stmtTrim === '' || preg_match('/^\s*--/', $stmtTrim)) {
                continue;
            }
            try {
                $pdo->exec($stmt);
                $log[] = InstallWizardLogger::logEntry('ok', 'Statement executed: ' . $basename);
            } catch (Exception $e) {
                $msg = $e->getMessage();
                if (stripos($msg, 'already exists') !== false || stripos($msg, 'Duplicate') !== false) {
                    $log[] = InstallWizardLogger::logEntry('skip', 'Statement skipped (already exists): ' . $basename);
                } else {
                    $safe = substr(preg_replace('/[\r\n]/', ' ', $msg), 0, 200);
                    $preview = trim(preg_replace('/\s+/', ' ', $stmt));
                    $preview = strlen($preview) > 80 ? substr($preview, 0, 80) . '...' : $preview;
                    $log[] = InstallWizardLogger::logEntry('error', 'SQL failed [' . $basename . '] statement ' . $idx . ': ' . $safe);
                    $log[] = InstallWizardLogger::logEntry('error', 'Failed statement preview: ' . $preview);
                    error_log('Lupopedia install SQL error [' . $basename . '] statement ' . $idx . ': ' . $msg);
                    error_log('Lupopedia install SQL failed statement preview: ' . $preview);
                    $ok = false;
                }
            }
        }
        return $ok;
    }

    /**
     * Last SQL failure message(s) from runSqlFile log for user-visible exceptions (pairs error line + statement preview).
     *
     * @param array $log
     * @return string
     */
    public static function formatLastSqlFailureForException($log)
    {
        if (!is_array($log) || empty($log)) {
            return '';
        }
        // Find the last "SQL failed [...]" line (log may have trailing "ok" entries after a mid-file failure).
        $lastFailIdx = -1;
        $n = count($log);
        for ($i = $n - 1; $i >= 0; $i--) {
            $entry = $log[$i];
            if (!is_array($entry) || !isset($entry[1])) {
                continue;
            }
            if (strpos($entry[1], 'SQL failed [') !== false) {
                $lastFailIdx = $i;
                break;
            }
        }
        if ($lastFailIdx < 0) {
            return '';
        }
        $out = array($log[$lastFailIdx][1]);
        if ($lastFailIdx + 1 < $n && isset($log[$lastFailIdx + 1][1])
            && strpos($log[$lastFailIdx + 1][1], 'Failed statement preview') !== false) {
            $out[] = $log[$lastFailIdx + 1][1];
        }
        $summary = implode(' ', $out);
        if (strlen($summary) > 600) {
            $summary = substr($summary, 0, 597) . '...';
        }
        return $summary;
    }

    public static function dropLivehelpTables($pdo, $tables, &$log)
    {
        foreach ($tables as $table) {
            try {
                $pdo->exec("DROP TABLE IF EXISTS `" . str_replace('`', '``', $table) . "`");
                $log[] = InstallWizardLogger::logEntry('ok', 'Dropped ' . $table);
            } catch (Exception $e) {
                $log[] = InstallWizardLogger::logEntry('error', 'Failed to drop table (see server log).');
                error_log('Lupopedia drop table ' . $table . ': ' . $e->getMessage());
            }
        }
    }
}

class InstallWizardConfigWriter
{

    public static function writeConfig($db_vars, &$log, $options = array())
    {
        $auth = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
        $keys = array('AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT');
        $keyValues = array();
        foreach ($keys as $k) {
            $keyValues[$k] = '';
            for ($i = 0; $i < 64; $i++) {
                $keyValues[$k] .= $auth[lupo_random_int(0, strlen($auth) - 1)];
            }
            $keyValues[$k] = addslashes($keyValues[$k]);
        }
        require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'LupopediaConfigResolver.php';
        $writeTargets = LupopediaConfigResolver::defaultWriteTargets(LUPOPEDIA_PATH);
        $configDir = $writeTargets[0];
        $configPath = $writeTargets[1];
        $installRoot = rtrim(str_replace('\\', DIRECTORY_SEPARATOR, LUPOPEDIA_PATH), DIRECTORY_SEPARATOR);
        $abspath = $installRoot . DIRECTORY_SEPARATOR;
        $publicPathForDefine = '/' . basename($installRoot);
        $publicPathForDefineEsc = addslashes($publicPathForDefine);

        $content = '<?php
/**
 * Lupopedia Configuration — generated by install wizard
 * @package Lupopedia
 */
$lupo_prefix = \'lupo-\';
define(\'DB_TYPE\', \'' . addslashes($db_vars['type']) . '\');
define(\'DB_NAME\', \'' . addslashes($db_vars['name']) . '\');
define(\'DB_USER\', \'' . addslashes($db_vars['user']) . '\');
define(\'DB_PORT\', \'' . addslashes($db_vars['port']) . '\');
define(\'DB_PASSWORD\', \'' . addslashes($db_vars['password']) . '\');
define(\'DB_HOST\', \'' . addslashes($db_vars['host']) . '\');
define(\'DB_CHARSET\', \'' . addslashes($db_vars['charset']) . '\');
define(\'DB_COLLATE\', \'utf8mb4_unicode_ci\');
define(\'AUTH_KEY\', \'' . $keyValues['AUTH_KEY'] . '\');
define(\'SECURE_AUTH_KEY\', \'' . $keyValues['SECURE_AUTH_KEY'] . '\');
define(\'LOGGED_IN_KEY\', \'' . $keyValues['LOGGED_IN_KEY'] . '\');
define(\'NONCE_KEY\', \'' . $keyValues['NONCE_KEY'] . '\');
define(\'AUTH_SALT\', \'' . $keyValues['AUTH_SALT'] . '\');
define(\'SECURE_AUTH_SALT\', \'' . $keyValues['SECURE_AUTH_SALT'] . '\');
define(\'LOGGED_IN_SALT\', \'' . $keyValues['LOGGED_IN_SALT'] . '\');
define(\'NONCE_SALT\', \'' . $keyValues['NONCE_SALT'] . '\');
define(\'LUPOPEDIA_DEBUG\', false);
define(\'LUPOPEDIA_ENV\', \'production\');
if (!defined(\'ABSPATH\')) { define(\'ABSPATH\', \'' . addslashes(str_replace('\\', '/', $abspath)) . '\'); }
define(\'LUPO_PREFIX\', $lupo_prefix);
define(\'LUPO_ADMIN_DIR\', LUPO_PREFIX . \'admin\');
define(\'LUPO_INCLUDES_DIR\', LUPO_PREFIX . \'includes\');
define(\'LUPO_CONTENT_DIR\', LUPO_PREFIX . \'content\');
define(\'LUPO_ACTORS_DIR\', LUPO_PREFIX . \'actors\');
define(\'LUPO_CHANNELS_DIR\', LUPO_PREFIX . \'channels\');
define(\'LUPO_PROMPTS_SUBDIR\', \'prompts\');
define(\'LUPO_UPLOADS_DIR\', LUPO_CONTENT_DIR . \'/uploads\');
define(\'LUPO_PLUGINS_DIR\', LUPO_CONTENT_DIR . \'/plugins\');
define(\'LUPO_THEMES_DIR\', LUPO_CONTENT_DIR . \'/themes\');
if (!defined(\'LUPO_DATABASE_DIR\')) { define(\'LUPO_DATABASE_DIR\', LUPO_PREFIX . \'database\'); }
define(\'LUPO_APP_DIR\', \'lupo-database/lupopedia/content/lupo-app\');
$table_prefix = \'' . (isset($options['table_prefix']) && preg_match('/^[a-z0-9_]+$/', $options['table_prefix']) ? addslashes($options['table_prefix']) : 'lupo_') . '\';
if (!preg_match(\'/^[a-z0-9_]+$/\', $table_prefix)) { die("Invalid table prefix"); }
define(\'LUPO_TABLE_PREFIX\', $table_prefix);
if (!defined(\'LUPOPEDIA_ABSPATH\')) { define(\'LUPOPEDIA_ABSPATH\', ABSPATH); }
' . "if (!defined('LUPOPEDIA_PATH')) { define('LUPOPEDIA_PATH', rtrim(ABSPATH, '/')); }\n" . 'if (!defined(\'LUPOPEDIA_PUBLIC_PATH\')) { define(\'LUPOPEDIA_PUBLIC_PATH\', \'' . $publicPathForDefineEsc . '\'); }
if (!defined(\'LUPOPEDIA_URL\')) { define(\'LUPOPEDIA_URL\', LUPOPEDIA_PUBLIC_PATH); }
define(\'LUPOPEDIA_CONFIG_LOADED\', true);
' . (isset($options['site_name']) ? "define('LUPOPEDIA_SITE_NAME', '" . addslashes($options['site_name']) . "');\n" : '')
            . (isset($options['base_url']) ? "define('LUPOPEDIA_BASE_URL', '" . addslashes($options['base_url']) . "');\n" : '')
            . (isset($options['admin_email']) ? "define('LUPOPEDIA_ADMIN_EMAIL', '" . addslashes($options['admin_email']) . "');\n" : '')
            . (isset($options['timezone']) ? "define('LUPOPEDIA_TIMEZONE', '" . addslashes($options['timezone']) . "');\n" : '')
            . (isset($options['default_language']) ? "define('LUPOPEDIA_LANGUAGE', '" . addslashes($options['default_language']) . "');\n" : '')
            . (isset($options['support_email']) && $options['support_email'] !== '' ? "define('LUPOPEDIA_SUPPORT_EMAIL', '" . addslashes($options['support_email']) . "');\n" : '')
            . (isset($options['default_visitor_channel']) && $options['default_visitor_channel'] !== '' ? "define('LUPOPEDIA_DEFAULT_VISITOR_CHANNEL', '" . addslashes($options['default_visitor_channel']) . "');\n" : '')
            . (!empty($options['enable_ai_channels']) ? "define('LUPOPEDIA_ENABLE_AI_CHANNELS', true);\n" : '') . '
// Optional: Cloudflare integration (define LUPO_CLOUDFLARE_ENABLED, LUPO_CLOUDFLARE_TRUST_PROXY, etc. when behind Cloudflare)
require_once ABSPATH . LUPO_INCLUDES_DIR . \'/bootstrap.php\';
';

        if (!is_writable($configDir)) {
            $log[] = InstallWizardLogger::logEntry('error', 'Config directory not writable.');
            return null;
        }
        if (@file_put_contents($configPath, $content) === false) {
            $log[] = InstallWizardLogger::logEntry('error', 'Could not write config file.');
            return null;
        }
        @chmod($configPath, 0600);
        $log[] = InstallWizardLogger::logEntry('ok', 'Wrote lupopedia-config.php to ' . $configPath);

        self::ensureActorZeroDirs($installRoot, $log);
        self::ensureChannelsDir($installRoot, $log);

        // Enqueue background command for channel/artifact import (Doctrine #8: System Commands Queue)
        self::enqueueBackgroundCommand($db_vars, $log, isset($options['table_prefix']) ? $options['table_prefix'] : 'lupo_');

        // Crafty config.php is only used during upgrade (for credentials). After successful upgrade we remove it
        // so only lupopedia-config.php remains and users are not confused by two configs.
        $craftyConfig = $installRoot . DIRECTORY_SEPARATOR . 'config.php';
        if (is_file($craftyConfig)) {
            $safeToDelete = (is_file($configPath) && is_readable($configPath));
            if ($safeToDelete) {
                $verify = @file_get_contents($configPath, false, null, 0, 200);
                $safeToDelete = ($verify !== false && strpos($verify, 'LUPOPEDIA_CONFIG_LOADED') !== false);
            }
            if ($safeToDelete && @unlink($craftyConfig)) {
                $log[] = InstallWizardLogger::logEntry('ok', 'Deleted Crafty config.php; lupopedia-config.php is now the only active config.');
            } elseif (!$safeToDelete) {
                $log[] = InstallWizardLogger::logEntry('skip', 'Skipped deleting config.php: lupopedia-config.php missing or unreadable.');
            } else {
                $log[] = InstallWizardLogger::logEntry('error', 'Could not delete config.php; please remove it manually so only lupopedia-config.php is used.');
            }
        }

        return $configPath;
    }

    /**
     * Ensure actor 0 (system) directories exist: prompts/ and logs/ under lupo-actors/0/.
     * Uses mkdir(..., 0755, true). Safe to call multiple times.
     *
     * @param string $configDir Project root (e.g. LUPOPEDIA_PATH)
     * @param array  $log       Log array (by reference)
     * @return void
     */
    public static function ensureActorZeroDirs($configDir, &$log)
    {
        $actors_dir = 'lupo-actors';
        $base = rtrim($configDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $actors_dir . DIRECTORY_SEPARATOR . '0';
        $dirs = array('prompts', 'logs');
        foreach ($dirs as $sub) {
            $path = $base . DIRECTORY_SEPARATOR . $sub;
            if (!is_dir($path)) {
                if (@mkdir($path, 0755, true)) {
                    $log[] = InstallWizardLogger::logEntry('ok', 'Created ' . $actors_dir . '/0/' . $sub . '/');
                } else {
                    $log[] = InstallWizardLogger::logEntry('skip', 'Could not create ' . $actors_dir . '/0/' . $sub . '/ (check permissions)');
                }
            }
        }
    }

    /**
     * Ensure lupo-channels/ exists (for channel artifacts: {node_id}/{channel_id}/).
     *
     * @param string $configDir Project root
     * @param array  $log       Log array (by reference)
     */
    public static function ensureChannelsDir($configDir, &$log)
    {
        $channels_dir = 'lupo-channels';
        $path = rtrim($configDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $channels_dir;
        if (!is_dir($path)) {
            if (@mkdir($path, 0755, true)) {
                $log[] = InstallWizardLogger::logEntry('ok', 'Created ' . $channels_dir . '/');
            } else {
                $log[] = InstallWizardLogger::logEntry('skip', 'Could not create ' . $channels_dir . '/ (check permissions)');
            }
        }
    }

    /**
     * Enqueue background command for post-install tasks (Doctrine #8: System Commands Queue).
     *
     * NOTE (4.0.93+): Registry-open based PK allocation was removed in favor of
     * deterministic IDs. This method now uses a simple MAX(command_id)+1 pattern
     * on {{prefix}}system_commands when available, and degrades gracefully when
     * the table is missing.
     *
     * @param array $db_vars Database connection info
     * @param array $log Log array (by reference)
     * @param string $table_prefix Table prefix (e.g. 'lupo_')
     * @return void
     */
    private static function enqueueBackgroundCommand($db_vars, &$log, $table_prefix)
    {
        try {
            $pdo = new InstallWizardMysqliLink($db_vars);

            $commands_table = $table_prefix . 'system_commands';
            $now = (int) gmdate('YmdHis');

            // Determine next command_id deterministically (no registry_open)
            $command_id = 1;
            try {
                $stmtMax = $pdo->query("SELECT COALESCE(MAX(command_id), 0) AS mx FROM `{$commands_table}`");
                $rowMax = $stmtMax ? $stmtMax->fetch(PDO::FETCH_ASSOC) : null;
                $maxId = $rowMax && isset($rowMax['mx']) ? (int) $rowMax['mx'] : 0;
                $command_id = $maxId + 1;
            } catch (Exception $e) {
                // If the table is missing or unreadable, skip enqueue silently.
                $log[] = InstallWizardLogger::logEntry('skip', 'Could not inspect system_commands for next command_id; skipping background enqueue.');
                return;
            }

            if (!$command_id || $command_id == 0) {
                $log[] = InstallWizardLogger::logEntry('skip', 'Could not allocate deterministic command_id from system_commands');
                return;
            }

            // Prepare command args
            $command_args = array(
                'script' => 'lupo-scripts/import_channels_and_artifacts.py',
                'root' => LUPOPEDIA_PATH,
                'mode' => 'initial_import',
                'paths' => array('lupo-channels', 'artifacts'),
                'system_version' => '4.0.42'
            );

            // Insert command (explicit column list per Doctrine #5)
            $stmt = $pdo->prepare("
                INSERT INTO `{$commands_table}` (
                    command_id, command_type, command_args_json, working_dir, status, priority,
                    created_ymdhis, scheduled_ymdhis, started_ymdhis, finished_ymdhis,
                    claimed_by_actor_id, claimed_by_host, process_id, attempt_count, max_attempts,
                    timeout_seconds, return_code, output_text, output_sha1, last_heartbeat_ymdhis,
                    is_deleted, deleted_ymdhis
                ) VALUES (
                    ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    ?, ?
                )
            ");

            $stmt->execute(array(
                $command_id,
                'python_import_channels_and_artifacts',
                json_encode($command_args),
                LUPOPEDIA_PATH,
                'queued',
                0,
                $now,
                $now,
                null,
                null,
                null,
                null,
                null,
                0,
                3,
                3600,
                null,
                null,
                null,
                null,
                0,
                null
            ));

            $log[] = InstallWizardLogger::logEntry('ok', 'Enqueued background command (ID: ' . $command_id . ')');

        } catch (Exception $e) {
            $log[] = InstallWizardLogger::logEntry('skip', 'Could not enqueue background command: ' . $e->getMessage());
        }
    }
}

/**
 * Create the main admin user (auth_user_id 10000, actor_id 10000) for new installs.
 * Step 7 (Config) collects admin email and password; they are stored as user 10000.
 * Reserved ID doctrine: explicit ID; if row exists → UPDATE, else INSERT.
 * PHP 5.3 compatible: no ??, no [], no typed properties.
 */
class InstallWizardMainAdmin
{

    const MAIN_ADMIN_AUTH_USER_ID = 10000;
    const MAIN_ADMIN_ACTOR_ID = 10000;
    const DEFAULT_EMAIL = 'captain@lupopedia.com';

    /**
     * Bcrypt hash for wizard (no config required). PHP 5.3 safe.
     *
     * @param string $password
     * @return string|false
     */
    public static function hashPassword($password)
    {
        if (!is_string($password) || $password === '') {
            return false;
        }
        if (function_exists('password_hash')) {
            $opts = array('cost' => 10);
            return password_hash($password, PASSWORD_BCRYPT, $opts);
        }
        $alphabet = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $salt = '';
        for ($i = 0; $i < 22; $i++) {
            $salt .= $alphabet[mt_rand(0, 63)];
        }
        $setting = '$2y$10$' . $salt;
        $hash = crypt($password, $setting);
        return (is_string($hash) && strlen($hash) > 13) ? $hash : false;
    }

    /**
     * Create or update main admin: auth_user_id 10000, actor_id 10000 (seed identity: root);
     * root user as captain role on channels 0, 1, 42; department 0 administrator; admin module owner.
     *
     * @param PDO $pdo
     * @param string $table_prefix e.g. lupo_
     * @param string $email
     * @param string $password
     * @param array $log
     * @return bool
     */
    public static function createMainAdmin($pdo, $table_prefix, $email, $password, &$log)
    {
        $prefix = ($table_prefix !== null && $table_prefix !== '') ? $table_prefix : 'lupo_';
        $auth_t = $prefix . 'auth_users';
        $actors_t = $prefix . 'actors';
        $acr_t = $prefix . 'actor_channel_roles';
        $ac_t = $prefix . 'actor_channels';
        $dr_t = $prefix . 'department_roles';
        $ad_t = $prefix . 'actor_departments';
        $perm_t = $prefix . 'permissions';
        $mod_t = $prefix . 'modules';
        $aud_t = $prefix . 'auth_user_departments';
        $aau_t = $prefix . 'actor_auth_users';

        $email = trim($email);
        $password = is_string($password) ? $password : '';
        if ($email === '' || $password === '') {
            $log[] = InstallWizardLogger::logEntry('error', 'Main admin email and password are required.');
            return false;
        }
        $hash = self::hashPassword($password);
        if ($hash === false) {
            $log[] = InstallWizardLogger::logEntry('error', 'Could not hash main admin password.');
            return false;
        }
        $username = $email; // Use actual email as username, not slug
        if (strlen($username) > 30) {
            $username = substr($username, 0, 30);
        }
        $display_name = 'Captain';
        $now = (int) gmdate('YmdHis');

        try {
            $auth_id = self::MAIN_ADMIN_AUTH_USER_ID;
            $actor_id = self::MAIN_ADMIN_ACTOR_ID;
            $checkAuth = $pdo->prepare('SELECT 1 FROM `' . str_replace('`', '``', $auth_t) . '` WHERE auth_user_id = ? LIMIT 1');
            $checkAuth->execute(array($auth_id));
            $authExists = $checkAuth->fetchColumn();
            if ($authExists) {
                $up = $pdo->prepare('UPDATE `' . str_replace('`', '``', $auth_t) . '` SET username = ?, display_name = ?, email = ?, password_hash = ?, updated_ymdhis = ?, is_active = 1 WHERE auth_user_id = ?');
                $up->execute(array($username, $display_name, $email, $hash, $now, $auth_id));
                $log[] = InstallWizardLogger::logEntry('ok', 'Updated main admin user (auth_user_id ' . $auth_id . ').');
            } else {
                $ins = $pdo->prepare('INSERT INTO `' . str_replace('`', '``', $auth_t) . '` (auth_user_id, username, display_name, email, password_hash, auth_provider, provider_id, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis) VALUES (?, ?, ?, ?, ?, ?, NULL, ?, ?, 1, 0, NULL)');
                $ins->execute(array($auth_id, $username, $display_name, $email, $hash, 'local', $now, $now));
                $log[] = InstallWizardLogger::logEntry('ok', 'Created main admin user (auth_user_id ' . $auth_id . ', ' . $email . ').');
            }

            $checkActor = $pdo->prepare('SELECT 1 FROM `' . str_replace('`', '``', $actors_t) . '` WHERE actor_id = ? LIMIT 1');
            $checkActor->execute(array($actor_id));
            $actorExists = $checkActor->fetchColumn();
            $slug = 'user-' . $auth_id;
            $name = $display_name;
            $actor_name = 'captain';
            if ($actorExists) {
                $upA = $pdo->prepare('UPDATE `' . str_replace('`', '``', $actors_t) . '` SET actor_name = ?, slug = ?, name = ?, actor_source_id = ?, actor_source_type = ?, auth_user_id = ?, updated_ymdhis = ? WHERE actor_id = ?');
                $upA->execute(array($actor_name, $slug, $name, $auth_id, 'user', $auth_id, $now, $actor_id));
                $log[] = InstallWizardLogger::logEntry('ok', 'Updated main admin actor (actor_id ' . $actor_id . ').');
            } else {
                $insA = $pdo->prepare('INSERT INTO `' . str_replace('`', '``', $actors_t) . '` (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type, auth_user_id) VALUES (?, ?, ?, ?, ?, ?, ?, 1, 0, NULL, ?, ?, ?)');
                $insA->execute(array($actor_name, $actor_id, 'user', $slug, $name, $now, $now, $auth_id, 'user', $auth_id));
                $log[] = InstallWizardLogger::logEntry('ok', 'Created main admin actor (actor_id ' . $actor_id . ').');
            }

            $checkAud = $pdo->prepare('SELECT 1 FROM `' . str_replace('`', '``', $aud_t) . '` WHERE auth_user_id = ? AND department_id = 0 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1');
            $checkAud->execute(array($auth_id));
            if (!$checkAud->fetchColumn()) {
                $nextAudId = (int) $pdo->query('SELECT COALESCE(MAX(auth_user_department_id), 0) + 1 FROM `' . str_replace('`', '``', $aud_t) . '`')->fetchColumn();
                $insAud = $pdo->prepare('INSERT INTO `' . str_replace('`', '``', $aud_t) . '` (auth_user_department_id, auth_user_id, department_id, is_primary, role_key, title, created_ymdhis, updated_ymdhis, is_deleted) VALUES (?, ?, 0, 1, ?, ?, ?, ?, 0)');
                $insAud->execute(array($nextAudId, $auth_id, 'administrator', 'Main Administrator', $now, $now));
                $log[] = InstallWizardLogger::logEntry('ok', 'Linked main admin auth user to System department (0).');
            }

            $checkAau = $pdo->prepare('SELECT 1 FROM `' . str_replace('`', '``', $aau_t) . '` WHERE actor_id = ? AND auth_user_id = ? AND relationship_role = ? AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1');
            $checkAau->execute(array($actor_id, $auth_id, 'supporting_human'));
            if (!$checkAau->fetchColumn()) {
                $nextAauId = (int) $pdo->query('SELECT COALESCE(MAX(actor_auth_user_id), 0) + 1 FROM `' . str_replace('`', '``', $aau_t) . '`')->fetchColumn();
                $insAau = $pdo->prepare('INSERT INTO `' . str_replace('`', '``', $aau_t) . '` (actor_auth_user_id, actor_id, auth_user_id, relationship_role, is_primary, routing_priority, status, created_ymdhis, updated_ymdhis, is_deleted) VALUES (?, ?, ?, ?, 1, 100, ?, ?, ?, 0)');
                $insAau->execute(array($nextAauId, $actor_id, $auth_id, 'supporting_human', 'active', $now, $now));
                $log[] = InstallWizardLogger::logEntry('ok', 'Linked main admin actor to auth user (actor_auth_users).');
            }

            $channels = array(0, 1, 42, 51);
            $nextAcrId = (int) $pdo->query('SELECT COALESCE(MAX(actor_channel_role_id), 0) + 1 FROM `' . str_replace('`', '``', $acr_t) . '`')->fetchColumn();
            $insAcr = $pdo->prepare('INSERT INTO `' . str_replace('`', '``', $acr_t) . '` (actor_channel_role_id, actor_id, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted) VALUES (?, ?, ?, ?, ?, ?, 0)');
            foreach ($channels as $chId) {
                $checkAcr = $pdo->prepare('SELECT 1 FROM `' . str_replace('`', '``', $acr_t) . '` WHERE actor_id = ? AND channel_id = ? AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1');
                $checkAcr->execute(array($actor_id, $chId));
                if (!$checkAcr->fetchColumn()) {
                    $insAcr->execute(array($nextAcrId, $actor_id, $chId, 'captain', $now, $now));
                    $log[] = InstallWizardLogger::logEntry('ok', 'Assigned captain on channel ' . $chId . ' for main admin.');
                    $nextAcrId++;
                }
            }

            $nextAcId = (int) $pdo->query('SELECT COALESCE(MAX(actor_channel_id), 0) + 1 FROM `' . str_replace('`', '``', $ac_t) . '`')->fetchColumn();
            $insAc = $pdo->prepare('INSERT INTO `' . str_replace('`', '``', $ac_t) . '` (actor_channel_id, actor_id, channel_id, status, start_date, channel_color, created_ymdhis, updated_ymdhis, is_deleted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)');
            foreach ($channels as $chId) {
                $checkAc = $pdo->prepare('SELECT 1 FROM `' . str_replace('`', '``', $ac_t) . '` WHERE actor_id = ? AND channel_id = ? AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1');
                $checkAc->execute(array($actor_id, $chId));
                if (!$checkAc->fetchColumn()) {
                    $insAc->execute(array($nextAcId, $actor_id, $chId, 'A', $now, 'F7FAFF', $now, $now));
                    $log[] = InstallWizardLogger::logEntry('ok', 'Added main admin to channel ' . $chId . ' (actor_channels).');
                    $nextAcId++;
                }
            }

            $checkDr = $pdo->prepare('SELECT 1 FROM `' . str_replace('`', '``', $dr_t) . '` WHERE actor_id = ? AND department_id = 0 AND role_key = ? AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1');
            $checkDr->execute(array($actor_id, 'administrator'));
            if (!$checkDr->fetchColumn()) {
                $nextDrId = (int) $pdo->query('SELECT COALESCE(MAX(department_role_id), 0) + 1 FROM `' . str_replace('`', '``', $dr_t) . '`')->fetchColumn();
                $insDr = $pdo->prepare('INSERT INTO `' . str_replace('`', '``', $dr_t) . '` (department_role_id, actor_id, department_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES (?, ?, 0, ?, ?, ?, 0, NULL)');
                $insDr->execute(array($nextDrId, $actor_id, 'administrator', $now, $now));
                $log[] = InstallWizardLogger::logEntry('ok', 'Assigned administrator on System department (0) for main admin.');
            }

            $checkAd = $pdo->prepare('SELECT 1 FROM `' . str_replace('`', '``', $ad_t) . '` WHERE actor_id = ? AND department_id = 0 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1');
            $checkAd->execute(array($actor_id));
            if (!$checkAd->fetchColumn()) {
                $nextAdId = (int) $pdo->query('SELECT COALESCE(MAX(actor_department_id), 0) + 1 FROM `' . str_replace('`', '``', $ad_t) . '`')->fetchColumn();
                $insAd = $pdo->prepare('INSERT INTO `' . str_replace('`', '``', $ad_t) . '` (actor_department_id, actor_id, department_id, title, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES (?, ?, 0, ?, ?, ?, 0, NULL)');
                $insAd->execute(array($nextAdId, $actor_id, 'System Administrator', $now, $now));
            }

            $adminMod = $pdo->query("SELECT module_id FROM `" . str_replace('`', '``', $mod_t) . "` WHERE module_key = 'admin' AND is_active = 1 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1");
            $adminModuleRow = $adminMod ? $adminMod->fetch(PDO::FETCH_ASSOC) : null;
            $adminModuleId = $adminModuleRow && isset($adminModuleRow['module_id']) ? (int) $adminModuleRow['module_id'] : null;
            if ($adminModuleId !== null) {
                $checkPerm = $pdo->prepare('SELECT 1 FROM `' . str_replace('`', '``', $perm_t) . '` WHERE target_type = ? AND target_id = ? AND user_id = ? AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1');
                $checkPerm->execute(array('module', $adminModuleId, $auth_id));
                if (!$checkPerm->fetchColumn()) {
                    $nextPermId = (int) $pdo->query('SELECT COALESCE(MAX(permission_id), 0) + 1 FROM `' . str_replace('`', '``', $perm_t) . '`')->fetchColumn();
                    $insPerm = $pdo->prepare('INSERT INTO `' . str_replace('`', '``', $perm_t) . '` (permission_id, target_type, target_id, user_id, department_id, permission, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES (?, ?, ?, ?, NULL, ?, ?, ?, 0, NULL)');
                    $insPerm->execute(array($nextPermId, 'module', $adminModuleId, $auth_id, 'owner', $now, $now));
                    $log[] = InstallWizardLogger::logEntry('ok', 'Granted owner on Admin module for main admin (global admin access).');
                }
            }

            return true;
        } catch (Exception $e) {
            $log[] = InstallWizardLogger::logEntry('error', 'Main admin creation failed: ' . $e->getMessage());
            error_log('Lupopedia InstallWizardMainAdmin: ' . $e->getMessage());
            return false;
        }
    }
}

class InstallWizardNormalize
{

    public static function usernameToSlug($username)
    {
        $s = $username;
        $s = str_replace('@', '-at-', $s);
        $s = str_replace('.', '-dot-', $s);
        $s = strtolower($s);
        $s = preg_replace('/[^a-z0-9\-]/', '', $s);
        $s = trim(preg_replace('/-+/', '-', $s), '-');
        return $s !== '' ? $s . '-at-lupopedia-com' : 'unknown-at-lupopedia-com';
    }

    public static function isValidEmail($email)
    {
        $email = trim($email);
        return $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function isValidLupopediaSlug($s)
    {
        $s = trim($s);
        return $s !== '' && (bool) preg_match('/^[a-z0-9]+(-[a-z0-9]+)*-at-lupopedia-com$/', $s);
    }

    public static function isAcceptableResolvedEmail($value)
    {
        $value = trim($value);
        return $value !== '' && (self::isValidEmail($value) || self::isValidLupopediaSlug($value));
    }

    public static function loadCraftyUsers($pdo)
    {
        $stmt = $pdo->query("SELECT user_id, username, email, displayname, isoperator FROM livehelp_users ORDER BY isoperator DESC, user_id");
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : array();
    }

    /**
     * Proposes identity (email/username) changes for operators only.
     * Visitor sessions (isoperator != 'Y') are not normalized; their usernames are left unchanged.
     */
    public static function computeProposedIdentities($users)
    {
        $out = array();
        foreach ($users as $u) {
            $isOp = (strtoupper((string) (isset($u['isoperator']) ? $u['isoperator'] : '')) === 'Y');
            if (!$isOp) {
                continue;
            }
            $username = trim((string) (isset($u['username']) ? $u['username'] : ''));
            $email = trim((string) (isset($u['email']) ? $u['email'] : ''));
            $slug = self::usernameToSlug($username);
            $proposedEmail = (self::isValidEmail($email)) ? $email : $slug;
            $proposedUsername = (self::isValidEmail($email)) ? $email : $slug; // Use email as username if valid
            $out[] = array(
                'user_id' => (int) $u['user_id'],
                'username' => $username,
                'email' => $email,
                'displayname' => trim((string) (isset($u['displayname']) ? $u['displayname'] : '')),
                'isoperator' => true,
                'proposed_email' => $proposedEmail,
                'proposed_username' => $proposedUsername,
            );
        }
        return $out;
    }

    public static function findDuplicateEmailGroups($identities)
    {
        $byEmail = array();
        foreach ($identities as $i => $row) {
            $key = strtolower($row['proposed_email']);
            if (!isset($byEmail[$key])) {
                $byEmail[$key] = array();
            }
            $byEmail[$key][] = $i;
        }
        return array_filter($byEmail, function ($indices) {
            return count($indices) > 1;
        });
    }

    public static function collectNormalizeWarnings($identities)
    {
        $warnings = array();
        foreach ($identities as $i => $row) {
            $id = $row['user_id'];
            $username = $row['username'];
            $email = $row['email'];
            if ($row['isoperator'] && $username === '') {
                $warnings[] = "Operator (user_id {$id}) has no username; will use slug 'unknown-at-lupopedia-com'.";
            }
            if ($username === '' && !$row['isoperator']) {
                $warnings[] = "User ID {$id} has blank username; email will be used or generated from slug.";
            }
            if ($email === '' && !$row['isoperator']) {
                $warnings[] = "User ID {$id} has blank email; proposed email generated from username.";
            }
        }
        return $warnings;
    }

    public static function validateResolvedEmails($identities, $resolved)
    {
        $errors = array();
        $byId = array();
        foreach ($identities as $row) {
            $id = $row['user_id'];
            $email = trim((string) (isset($resolved[$id]) ? $resolved[$id] : ''));
            $rowErrors = array();
            if ($email === '') {
                $rowErrors[] = 'Email cannot be empty.';
            } else {
                if ($row['isoperator']) {
                    if (!self::isValidLupopediaSlug($email) && !self::isValidEmail($email)) {
                        $rowErrors[] = 'Operators must use a valid email or slug-format (e.g. name-at-lupopedia-com).';
                    }
                } else {
                    if (!self::isAcceptableResolvedEmail($email)) {
                        $rowErrors[] = 'Invalid email or slug format.';
                    }
                }
            }
            if (!empty($rowErrors)) {
                $byId[$id] = $rowErrors;
                $errors = array_merge($errors, $rowErrors);
            }
        }
        $lower = array();
        foreach ($resolved as $id => $email) {
            $email = trim($email);
            if ($email === '') {
                continue;
            }
            $key = strtolower($email);
            if (!isset($lower[$key])) {
                $lower[$key] = array();
            }
            $lower[$key][] = $id;
        }
        $dupes = array_filter($lower, function ($ids) {
            return count($ids) > 1;
        });
        if (!empty($dupes)) {
            $errors[] = 'Duplicate emails: each account must have a unique email.';
            foreach ($dupes as $emailKey => $ids) {
                foreach ($ids as $id) {
                    if (!isset($byId[$id])) {
                        $byId[$id] = array();
                    }
                    $byId[$id][] = 'This email is duplicated; choose a unique value.';
                }
            }
        }
        return array('errors' => $errors, 'by_id' => $byId);
    }

    public static function applyNormalizationToLivehelp($pdo, $identities, $resolvedEmails)
    {
        // livehelp_users.username is varchar(30); proposed_username (slug) can exceed that.
        $maxUsernameLen = 30;
        $update = $pdo->prepare("UPDATE livehelp_users SET email = ?, username = ? WHERE user_id = ?");
        foreach ($identities as $row) {
            $id = $row['user_id'];
            $email = isset($resolvedEmails[$id]) ? $resolvedEmails[$id] : $row['proposed_email'];
            $username = $row['isoperator'] ? $row['proposed_username'] : $row['username'];
            $username = substr($username, 0, $maxUsernameLen);
            $update->execute(array($email, $username, $id));
        }
    }
}

/**
 * System department (department_id = 0). Must exist before any other departments.
 * Reserved, not user-selectable. Department 0 cannot be edited or deleted by the wizard.
 */
class InstallWizardDepartments
{

    /** Reserved department_id for system/global roles. Must not be edited or deleted. */
    const SYSTEM_DEPARTMENT_ID = 0;

    public static function ensureSystemDepartment($pdo, &$log)
    {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $dept = $prefix . 'departments';
        $check = $pdo->prepare("SELECT 1 FROM " . $dept . " WHERE department_id = 0 LIMIT 1");
        $check->execute(array());
        if ($check->fetchColumn()) {
            $log[] = InstallWizardLogger::logEntry('skip', 'System department (0) already exists.');
        } else {
            $now = (int) gmdate('YmdHis');
            $ins = $pdo->prepare("
                INSERT INTO " . $dept . " (department_id, federation_node_id, name, description, department_type, default_actor_id, settings_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
                VALUES (0, 1, 'System', 'System Department (Reserved)', 'system', 0, NULL, ?, ?, 0, NULL)
            ");
            try {
                $ins->execute(array($now, $now));
                $log[] = InstallWizardLogger::logEntry('ok', 'Created system department (department_id = 0).');
            } catch (Exception $e) {
                $log[] = InstallWizardLogger::logEntry('error', 'Failed to create system department (see server log).');
                error_log('Lupopedia ensureSystemDepartment: ' . $e->getMessage());
            }
        }
        self::ensureDefaultDepartment($pdo, $log);
    }

    /** Ensure department 1 (General) exists for channels. Required after import which may omit it. */
    public static function ensureDefaultDepartment($pdo, &$log)
    {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $dept = $prefix . 'departments';
        $check = $pdo->prepare("SELECT 1 FROM " . $dept . " WHERE department_id = 1 LIMIT 1");
        $check->execute(array());
        if ($check->fetchColumn()) {
            return;
        }
        $now = (int) gmdate('YmdHis');
        $ins = $pdo->prepare("
            INSERT INTO " . $dept . " (department_id, federation_node_id, name, description, department_type, default_actor_id, settings_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
            VALUES (1, 1, 'General', 'Default department for channels', 'general', 0, NULL, ?, ?, 0, NULL)
        ");
        try {
            $ins->execute(array($now, $now));
            $log[] = InstallWizardLogger::logEntry('ok', 'Created default department (department_id = 1).');
        } catch (Exception $e) {
            $log[] = InstallWizardLogger::logEntry('error', 'Failed to create default department (see server log).');
            error_log('Lupopedia ensureDefaultDepartment: ' . $e->getMessage());
        }
    }

    /** Whether department_id is the reserved system department (not user-selectable, cannot edit/delete). */
    public static function isSystemDepartment($departmentId)
    {
        return (int) $departmentId === self::SYSTEM_DEPARTMENT_ID;
    }
}

/**
 * Channel creation for install/upgrade. Uses department_id only.
 * Group tables (lupo_groups, lupo_actor_group_membership) are removed; organizational scope is department only.
 */
class InstallWizardChannels
{

    public static function createReservedSystemChannels($pdo, &$log)
    {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $ch = $prefix . 'channels';
        $acr = $prefix . 'actor_channel_roles';
        $federationNodeId = 1;
        $departmentId = 1;
        $now = (int) gmdate('YmdHis');

        $reserved = array(
            0 => array('key' => 'system', 'slug' => 'system', 'type' => 'system', 'name' => 'System Kernel Channel', 'desc' => 'System channel (kernel/system operations).', 'is_kernel' => 1, 'captain' => false),
            1 => array('key' => 'administration', 'slug' => 'administration', 'type' => 'public', 'name' => 'Administration', 'desc' => 'Global admin channel (channel_id = 1).', 'is_kernel' => 0, 'captain' => true),
            42 => array('key' => 'crafty-dev', 'slug' => 'crafty-dev', 'type' => 'public', 'name' => 'Crafty Syntax Development', 'desc' => 'Crafty Syntax development channel.', 'is_kernel' => 0, 'captain' => true),
            51 => array('key' => 'ai-dev', 'slug' => 'ai-dev', 'type' => 'public', 'name' => 'AI Agent Development', 'desc' => 'AI agent development channel.', 'is_kernel' => 0, 'captain' => true),
        );

        $check = $pdo->prepare("SELECT 1 FROM " . $ch . " WHERE channel_id = ?");
        $insChannel = $pdo->prepare("
            INSERT INTO " . $ch . " (
                channel_id, federation_node_id, created_by_actor_id, default_actor_id, department_id,
                channel_key, channel_slug, channel_type, language, channel_name, description, website_link,
                status_flag, created_ymdhis, updated_ymdhis, is_deleted, is_kernel
            ) VALUES (?, ?, 0, 0, ?, ?, ?, ?, 'en', ?, ?, NULL, 1, ?, ?, 0, ?)
        ");
        $nextAcrId = (int) $pdo->query("SELECT COALESCE(MAX(actor_channel_role_id), 0) + 1 FROM " . $acr)->fetchColumn();
        $insAcr = $pdo->prepare("
            INSERT INTO " . $acr . " (actor_channel_role_id, actor_id, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted)
            VALUES (?, 0, ?, 'captain', ?, ?, 0)
        ");

        foreach ($reserved as $channelId => $def) {
            $check->execute(array($channelId));
            if ($check->fetchColumn()) {
                $log[] = InstallWizardLogger::logEntry('skip', 'Reserved channel ' . $channelId . ' (' . $def['key'] . ') already exists.');
                continue;
            }
            try {
                $insChannel->execute(array(
                    $channelId,
                    $federationNodeId,
                    $departmentId,
                    $def['key'],
                    $def['slug'],
                    $def['type'],
                    $def['name'],
                    $def['desc'],
                    $now,
                    $now,
                    $def['is_kernel'],
                ));
                $log[] = InstallWizardLogger::logEntry('ok', 'Created reserved channel ' . $channelId . ' (' . $def['key'] . ').');
                if ($def['captain']) {
                    $insAcr->execute(array($nextAcrId, $channelId, $now, $now));
                    $log[] = InstallWizardLogger::logEntry('ok', 'Assigned system actor (0) as captain for channel ' . $channelId . '.');
                    $nextAcrId++;
                }
            } catch (Exception $e) {
                $msg = $e->getMessage();
                $log[] = InstallWizardLogger::logEntry('error', 'Reserved channel ' . $channelId . ' (' . $def['key'] . ') failed: ' . $msg);
                error_log('Lupopedia reserved channel ' . $channelId . ': ' . $msg);
            }
        }
    }

    /**
     * Create personal channels for each imported Crafty operator (actor_source_type = 'lupo_auth_users')
     * and assign captain in lupo_actor_channel_roles. Then assign captain on channel_id = 1 (Administration)
     * for each livehelp_users row where isadmin = 'Y'. No lupo_operators; permissions = lupo_actor_channel_roles.
     */
    public static function createOperatorChannels($pdo, &$log)
    {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $actors_t = $prefix . 'actors';
        $ch = $prefix . 'channels';
        $acr = $prefix . 'actor_channel_roles';
        $auth_t = $prefix . 'auth_users';
        $ad_t = $prefix . 'actor_departments';
        $dr_t = $prefix . 'department_roles';
        $map = array();
        $federationNodeId = 1;
        $departmentId = 1;
        $defaultActorId = 1;
        $now = (int) gmdate('YmdHis');

        $stmt = $pdo->query("
            SELECT a.actor_id, a.slug, a.name
            FROM " . $actors_t . " a
            WHERE a.actor_source_type = '" . str_replace("'", "''", $auth_t) . "'
            AND a.is_deleted = 0
            ORDER BY a.actor_id
        ");
        if (!$stmt) {
            $log[] = InstallWizardLogger::logEntry('error', 'Could not select actors (imported operators) from actors table.');
            return $map;
        }
        $actors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($actors)) {
            $log[] = InstallWizardLogger::logEntry('ok', 'No actors to create personal channels for.');
        }

        $nextChannelId = (int) $pdo->query("SELECT COALESCE(MAX(channel_id), 0) + 1 FROM " . $ch)->fetchColumn();
        $nextAcrId = (int) $pdo->query("SELECT COALESCE(MAX(actor_channel_role_id), 0) + 1 FROM " . $acr)->fetchColumn();
        $usedKeys = array();
        foreach ($pdo->query("SELECT channel_key FROM " . $ch . " WHERE federation_node_id = " . (int) $federationNodeId)->fetchAll(PDO::FETCH_COLUMN) as $k) {
            $usedKeys[strtolower($k)] = true;
        }

        $insChannel = $pdo->prepare("
            INSERT INTO " . $ch . " (
                channel_id, federation_node_id, created_by_actor_id, default_actor_id, department_id,
                channel_key, channel_slug, channel_type, language, channel_name, description, website_link,
                status_flag, created_ymdhis, updated_ymdhis, is_deleted, is_kernel
            ) VALUES (?, ?, ?, ?, ?, ?, ?, 'chat_room', 'en', ?, ?, NULL, 1, ?, ?, 0, 0)
        ");
        $insAcr = $pdo->prepare("
            INSERT INTO " . $acr . " (
                actor_channel_role_id, actor_id, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted
            ) VALUES (?, ?, ?, 'captain', ?, ?, 0)
        ");

        foreach ($actors as $row) {
            $actorId = (int) $row['actor_id'];
            $slug = trim((string) (isset($row['slug']) ? $row['slug'] : ''));
            $name = trim((string) (isset($row['name']) ? $row['name'] : $slug));
            if ($slug === '') {
                $slug = 'actor-' . $actorId;
            }
            $channelKey = $slug;
            $suffix = 1;
            while (isset($usedKeys[strtolower($channelKey)])) {
                $suffix++;
                $channelKey = $slug . '-' . $suffix;
            }
            $usedKeys[strtolower($channelKey)] = true;

            $channelName = $name . "'s Channel";
            $description = 'Personal channel for ' . $name;

            try {
                $insChannel->execute(array(
                    $nextChannelId,
                    $federationNodeId,
                    $actorId,
                    $defaultActorId,
                    $departmentId,
                    $channelKey,
                    $channelKey,
                    $channelName,
                    $description,
                    $now,
                    $now,
                ));
                $insAcr->execute(array($nextAcrId, $actorId, $nextChannelId, $now, $now));
                $map[$actorId] = $nextChannelId;
                $log[] = InstallWizardLogger::logEntry('ok', 'Created personal channel ' . $channelKey . ' (id ' . $nextChannelId . ') and assigned captain.');
            } catch (Exception $e) {
                $log[] = InstallWizardLogger::logEntry('error', 'Personal channel creation failed (see server log).');
                error_log('Lupopedia createOperatorChannels ' . $slug . ': ' . $e->getMessage());
            }
            $nextChannelId++;
            $nextAcrId++;
        }

        // Global admin: Crafty livehelp_users.isadmin = 'Y' => Lupopedia global admin (captain on channel 1, department 0 administrator, owner on admin module).
        // Only run when upgrading: livehelp_users exists. New install has no livehelp_* tables.
        $livehelpUsersExists = false;
        try {
            $livehelpUsersExists = InstallWizardDb::tableExists($pdo, 'livehelp_users');
        } catch (Exception $e) {
            // ignore
        }
        if ($livehelpUsersExists) {
        try {
            $adminStmt = $pdo->query("
                SELECT a.actor_id, au.auth_user_id, u.username
                FROM livehelp_users u
                INNER JOIN " . $auth_t . " au ON au.username = u.username
                INNER JOIN " . $actors_t . " a ON a.actor_source_id = au.auth_user_id AND a.actor_source_type = '" . str_replace("'", "''", $auth_t) . "'
                WHERE UPPER(TRIM(COALESCE(u.isadmin, ''))) = 'Y' AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
            ");
            if ($adminStmt) {
                $admins = $adminStmt->fetchAll(PDO::FETCH_ASSOC);
                $nextAdId = (int) $pdo->query("SELECT COALESCE(MAX(actor_department_id), 0) + 1 FROM " . $ad_t)->fetchColumn();
                $nextDrId = (int) $pdo->query("SELECT COALESCE(MAX(department_role_id), 0) + 1 FROM " . $dr_t)->fetchColumn();
                $insAd = $pdo->prepare("INSERT INTO " . $ad_t . " (actor_department_id, actor_id, department_id, title, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES (?, ?, 0, 'System Administrator', ?, ?, 0, NULL)");
                $insDr = $pdo->prepare("INSERT INTO " . $dr_t . " (department_role_id, actor_id, department_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES (?, ?, 0, 'administrator', ?, ?, 0, NULL)");
                $mod_t = $prefix . 'modules';
                $perm_t = $prefix . 'permissions';
                $adminModuleRow = $pdo->query("SELECT module_id FROM " . $mod_t . " WHERE module_key = 'admin' AND is_active = 1 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1")->fetch(PDO::FETCH_ASSOC);
                $adminModuleId = $adminModuleRow ? (int) $adminModuleRow['module_id'] : null;
                $nextPermId = null;
                $insPerm = null;
                if ($adminModuleId !== null) {
                    $nextPermId = (int) $pdo->query("SELECT COALESCE(MAX(permission_id), 0) + 1 FROM " . $perm_t)->fetchColumn();
                    $insPerm = $pdo->prepare("INSERT INTO " . $perm_t . " (permission_id, target_type, target_id, user_id, department_id, permission, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES (?, 'module', ?, ?, NULL, 'owner', ?, ?, 0, NULL)");
                }
                foreach ($admins as $admin) {
                    $actorId = (int) $admin['actor_id'];
                    $authUserId = (int) $admin['auth_user_id'];
                    $uname = trim((string) $admin['username']);
                    $check = $pdo->prepare("SELECT 1 FROM " . $acr . " WHERE actor_id = ? AND channel_id = 1 AND role_key = 'captain' AND is_deleted = 0 LIMIT 1");
                    $check->execute(array($actorId));
                    if (!$check->fetchColumn()) {
                        $insAcr->execute(array($nextAcrId, $actorId, 1, $now, $now));
                        $log[] = InstallWizardLogger::logEntry('ok', 'Assigned captain on Administration (channel 1) for ' . $uname . '.');
                        $nextAcrId++;
                    }
                    $checkAd = $pdo->prepare("SELECT 1 FROM " . $ad_t . " WHERE actor_id = ? AND department_id = 0 AND is_deleted = 0 LIMIT 1");
                    $checkAd->execute(array($actorId));
                    if (!$checkAd->fetchColumn()) {
                        $insAd->execute(array($nextAdId, $actorId, $now, $now));
                        $nextAdId++;
                    }
                    $checkDr = $pdo->prepare("SELECT 1 FROM " . $dr_t . " WHERE actor_id = ? AND department_id = 0 AND role_key = 'administrator' AND is_deleted = 0 LIMIT 1");
                    $checkDr->execute(array($actorId));
                    if (!$checkDr->fetchColumn()) {
                        $insDr->execute(array($nextDrId, $actorId, $now, $now));
                        $log[] = InstallWizardLogger::logEntry('ok', 'Assigned administrator on System department (0) for ' . $uname . '.');
                        $nextDrId++;
                    }
                    if ($adminModuleId !== null && $insPerm !== null) {
                        $checkPerm = $pdo->prepare("SELECT 1 FROM " . $perm_t . " WHERE target_type = 'module' AND target_id = ? AND user_id = ? AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1");
                        $checkPerm->execute(array($adminModuleId, $authUserId));
                        if (!$checkPerm->fetchColumn()) {
                            $insPerm->execute(array($nextPermId, $adminModuleId, $authUserId, $now, $now));
                            $log[] = InstallWizardLogger::logEntry('ok', 'Granted owner on Admin module for ' . $uname . ' (global admin access).');
                            $nextPermId++;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $log[] = InstallWizardLogger::logEntry('error', 'Admin channel captain assignment failed (see server log).');
            error_log('Lupopedia createOperatorChannels admin channel: ' . $e->getMessage());
        }
        } else {
            $log[] = InstallWizardLogger::logEntry('ok', 'No livehelp_users table (new install); skipping admin channel assignment.');
        }

        return $map;
    }

    public static function ensureReservedChannels($pdo, &$log)
    {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $ch = $prefix . 'channels';
        $required = array(0, 1, 42, 51);
        try {
            $stmt = $pdo->query('SELECT channel_id FROM ' . $ch . ' WHERE channel_id IN (0, 1, 42, 51)');
            $have = $stmt ? $stmt->fetchAll(PDO::FETCH_COLUMN) : array();
            $missing = array_diff($required, array_map('intval', $have));
            if ($missing !== array()) {
                $log[] = InstallWizardLogger::logEntry('ok', 'Creating missing reserved channels: ' . implode(', ', $missing));
                self::createReservedSystemChannels($pdo, $log);
                $stmt2 = $pdo->query('SELECT channel_id FROM ' . $ch . ' WHERE channel_id IN (0, 1, 42, 51)');
                $haveAfter = $stmt2 ? $stmt2->fetchAll(PDO::FETCH_COLUMN) : array();
                $stillMissing = array_diff($required, array_map('intval', $haveAfter));
                if ($stillMissing !== array()) {
                    $log[] = InstallWizardLogger::logEntry('error', 'Reserved channel(s) still missing after create: ' . implode(', ', $stillMissing) . '. Check error(s) above.');
                } else {
                    $log[] = InstallWizardLogger::logEntry('ok', 'Reserved channels created: ' . implode(', ', $missing));
                }
            }
        } catch (Exception $e) {
            $log[] = InstallWizardLogger::logEntry('error', 'Could not verify reserved channels.');
            error_log('Lupopedia ensure_reserved_channels: ' . $e->getMessage());
        }
    }

    /**
     * Ensure every actor (imported Crafty operator) has a personal channel with captain in lupo_actor_channel_roles.
     */
    public static function ensureOperatorChannels($pdo, &$log)
    {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $actors_t = $prefix . 'actors';
        $ch = $prefix . 'channels';
        $acr = $prefix . 'actor_channel_roles';
        $auth_t = $prefix . 'auth_users';
        try {
            $stmt = $pdo->query("
                SELECT a.actor_id, a.slug, a.name
                FROM " . $actors_t . " a
                WHERE a.actor_source_type = '" . str_replace("'", "''", $auth_t) . "' AND a.is_deleted = 0
                AND NOT EXISTS (
                    SELECT 1 FROM " . $acr . " r
                    INNER JOIN " . $ch . " c ON c.channel_id = r.channel_id AND c.created_by_actor_id = r.actor_id
                    WHERE r.actor_id = a.actor_id AND r.role_key = 'captain' AND r.is_deleted = 0
                )
                ORDER BY a.actor_id
            ");
            if (!$stmt) {
                return;
            }
            $missing = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($missing)) {
                return;
            }
            $federationNodeId = 1;
            $departmentId = 1;
            $defaultActorId = 1;
            $now = (int) gmdate('YmdHis');
            $nextChannelId = (int) $pdo->query('SELECT COALESCE(MAX(channel_id), 0) + 1 FROM ' . $ch)->fetchColumn();
            $nextAcrId = (int) $pdo->query('SELECT COALESCE(MAX(actor_channel_role_id), 0) + 1 FROM ' . $acr)->fetchColumn();
            $usedKeys = array();
            foreach ($pdo->query('SELECT channel_key FROM ' . $ch . ' WHERE federation_node_id = 1')->fetchAll(PDO::FETCH_COLUMN) as $k) {
                $usedKeys[strtolower($k)] = true;
            }
            $insChannel = $pdo->prepare("
                INSERT INTO " . $ch . " (channel_id, federation_node_id, created_by_actor_id, default_actor_id, department_id, channel_key, channel_slug, channel_type, language, channel_name, description, website_link, status_flag, created_ymdhis, updated_ymdhis, is_deleted, is_kernel)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'chat_room', 'en', ?, ?, NULL, 1, ?, ?, 0, 0)
            ");
            $insAcr = $pdo->prepare("
                INSERT INTO " . $acr . " (actor_channel_role_id, actor_id, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted)
                VALUES (?, ?, ?, 'captain', ?, ?, 0)
            ");
            foreach ($missing as $row) {
                $actorId = (int) $row['actor_id'];
                $slug = trim((string) (isset($row['slug']) ? $row['slug'] : ''));
                $name = trim((string) (isset($row['name']) ? $row['name'] : $slug));
                if ($slug === '') {
                    $slug = 'actor-' . $actorId;
                }
                $channelKey = $slug;
                $suffix = 1;
                while (isset($usedKeys[strtolower($channelKey)])) {
                    $channelKey = $slug . '-' . (++$suffix);
                }
                $usedKeys[strtolower($channelKey)] = true;
                $channelName = $name . "'s Channel";
                $description = 'Personal channel for ' . $name;
                try {
                    $insChannel->execute(array($nextChannelId, $federationNodeId, $actorId, $defaultActorId, $departmentId, $channelKey, $channelKey, $channelName, $description, $now, $now));
                    $insAcr->execute(array($nextAcrId, $actorId, $nextChannelId, $now, $now));
                    $log[] = InstallWizardLogger::logEntry('ok', 'Created missing personal channel ' . $channelKey . ' (id ' . $nextChannelId . ').');
                } catch (Exception $e) {
                    $log[] = InstallWizardLogger::logEntry('error', 'Failed to create missing personal channel (see server log).');
                    error_log('Lupopedia ensureOperatorChannels ' . $slug . ': ' . $e->getMessage());
                }
                $nextChannelId++;
                $nextAcrId++;
            }
        } catch (Exception $e) {
            $log[] = InstallWizardLogger::logEntry('error', 'Could not ensure personal channels.');
            error_log('Lupopedia ensureOperatorChannels: ' . $e->getMessage());
        }
    }
}

/**
 * 4.0.93+ deterministic IDs: registry_open lifecycle is retired.
 * Keep this class as a compatibility no-op for older call sites.
 */
class InstallWizardUnregistry
{

    /** Default cap for max id range (0 to cap) so unregistry stays small. */
    const DEFAULT_MAX_CAP = 500;

    /**
     * @param PDO $pdo
     * @param array $log
     * @param int $maxCap
     */
    public static function seedUnregistryFromGaps($pdo, &$log, $maxCap = 500)
    {
        $log[] = InstallWizardLogger::logEntry('skip', 'InstallWizardUnregistry no-op: registry_open allocator retired (deterministic IDs).');
        return;
    }
}

/**
 * 4.0.20: Ensure Stoned Wolfie banned test identities exist after import/seed.
 * AI: actor_id 420 (lupo_agents, lupo_actors, lupo_banned_actors, lupo_auth_users disabled).
 * Human: next free actor_id >= 10000 (lupo_auth_users, lupo_actors, lupo_banned_actors).
 * Idempotent: skips if already present.
 */
class InstallWizardBannedIdentities
{

    const STONED_WOLFIE_AI_ACTOR_ID = 420;
    const STONED_WOLFIE_HUMAN_EMAIL = 'stonedwolfie@lupopedia.com';

    /**
     * Ensure both Stoned Wolfie (AI and human) banned identities exist.
     *
     * @param PDO $pdo
     * @param array $log
     * @param string $table_prefix e.g. lupo_
     */
    public static function ensureStonedWolfieBannedIdentities($pdo, &$log, $table_prefix)
    {
        $now = (int) gmdate('YmdHis');
        $actors_t = $table_prefix . 'actors';
        $agents_t = $table_prefix . 'agent_definitions';
        $banned_t = $table_prefix . 'banned_actors';
        $auth_t = $table_prefix . 'auth_users';
        try {
            // ----- AI (actor_id 420) -----
            $stmt = $pdo->query("SELECT 1 FROM " . $actors_t . " WHERE actor_id = " . (int) self::STONED_WOLFIE_AI_ACTOR_ID . " LIMIT 1");
            $aiExists = $stmt && $stmt->fetch();
            if (!$aiExists) {
                // agent_definitions: doctrine identity only (no LLM config columns — those live in agent_llm_configs)
                $pdo->exec("INSERT INTO " . $agents_t . " (agent_id, agent_key, slug, name, layer, archetype, description, version, status, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis) VALUES (420, 'stoned_wolfie_ai', 'stoned-wolfie-ai', 'Stoned Wolfie (AI)', 'reserved', 'banned_test', 'Banned AI test identity for adversarial harness.', '1.0.0', 'inactive', 1, " . $now . ", " . $now . ", " . $now . ")");
                $pdo->exec("INSERT INTO " . $actors_t . " (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type, avatar_hash) VALUES ('stoned_wolfie_ai', 420, 'agent', 'stoned-wolfie-ai', 'Stoned Wolfie (AI)', " . $now . ", " . $now . ", 0, 1, " . $now . ", 420, 'lupo_agent_definitions', NULL)");
                $nextBanId = (int) $pdo->query("SELECT COALESCE(MAX(banned_actor_id), 0) + 1 FROM " . $banned_t)->fetchColumn();
                $pdo->exec("INSERT INTO " . $banned_t . " (banned_actor_id, actor_id, ip_address, reason, banned_ymdhis, banned_by_actor_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES (" . $nextBanId . ", 420, NULL, 'banned_test_identity_ai', " . $now . ", 1000, " . $now . ", " . $now . ", 0, NULL)");
                $pdo->exec("INSERT INTO " . $auth_t . " (auth_user_id, username, display_name, email, password_hash, auth_provider, provider_id, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis) VALUES (420, 'stoned_wolfie_ai', 'Stoned Wolfie (AI)', 'stoned.wolfie.ai@banned.local', NULL, 'local', NULL, " . $now . ", " . $now . ", 0, 0, NULL)");
                $log[] = InstallWizardLogger::logEntry('ok', 'Stoned Wolfie (AI) banned identity inserted (actor_id 420).');
            }

            // ----- Human (next free actor_id >= 10000) -----
            $stmt = $pdo->prepare("SELECT auth_user_id FROM " . $auth_t . " WHERE email = ? LIMIT 1");
            $stmt->execute(array(self::STONED_WOLFIE_HUMAN_EMAIL));
            $humanRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$humanRow) {
                $nextActorId = (int) $pdo->query("SELECT COALESCE(MAX(actor_id), 9999) + 1 FROM " . $actors_t)->fetchColumn();
                $pwdHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
                $pdo->exec("INSERT INTO " . $auth_t . " (auth_user_id, username, display_name, email, password_hash, auth_provider, provider_id, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis) VALUES (" . $nextActorId . ", 'stonedwolfie', 'Stoned Wolfie', 'stonedwolfie@lupopedia.com', " . $pdo->quote($pwdHash) . ", 'local', NULL, " . $now . ", " . $now . ", 0, 0, NULL)");
                $pdo->exec("INSERT INTO " . $actors_t . " (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type, avatar_hash) VALUES ('stonedwolfie', " . $nextActorId . ", 'user', 'user-" . $nextActorId . "', 'Stoned Wolfie', " . $now . ", " . $now . ", 0, 0, NULL, " . $nextActorId . ", 'lupo_auth_users', NULL)");
                $nextBanId = (int) $pdo->query("SELECT COALESCE(MAX(banned_actor_id), 0) + 1 FROM " . $banned_t)->fetchColumn();
                $pdo->exec("INSERT INTO " . $banned_t . " (banned_actor_id, actor_id, ip_address, reason, banned_ymdhis, banned_by_actor_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES (" . $nextBanId . ", " . $nextActorId . ", NULL, 'banned_test_identity_human', " . $now . ", 1000, " . $now . ", " . $now . ", 0, NULL)");
                $log[] = InstallWizardLogger::logEntry('ok', 'Stoned Wolfie (human) banned identity inserted (actor_id ' . $nextActorId . ').');
            }
        } catch (Exception $e) {
            $log[] = InstallWizardLogger::logEntry('error', 'Stoned Wolfie banned identities: ' . $e->getMessage());
            error_log('Lupopedia ensureStonedWolfieBannedIdentities: ' . $e->getMessage());
        }
    }
}
