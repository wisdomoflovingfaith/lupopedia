<?php
/**
 * Lupopedia 3.0.0 — Install Wizard Classes
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

class InstallWizardSteps {

    public static function getWizardSteps() {
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

    public static function getCurrentStepIndex($step) {
        $steps = self::getWizardSteps();
        foreach ($steps as $i => $s) {
            if ($s['id'] === $step) {
                return $i + 1;
            }
        }
        return 1;
    }

    public static function getTotalSteps() {
        return count(self::getWizardSteps());
    }
}

class InstallWizardSecurity {

    public static function getCsrfToken() {
        if (empty($_SESSION['lupo_csrf_token'])) {
            $_SESSION['lupo_csrf_token'] = bin2hex(lupo_random_bytes(32));
        }
        return $_SESSION['lupo_csrf_token'];
    }

    public static function validateCsrf() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return true;
        }
        $token = isset($_POST['lupo_csrf']) ? $_POST['lupo_csrf'] : '';
        $stored = isset($_SESSION['lupo_csrf_token']) ? $_SESSION['lupo_csrf_token'] : '';
        return $token !== '' && lupo_hash_equals($stored, $token);
    }
}

class InstallWizardLogger {

    public static function logEntry($type, $message) {
        $ts = date('c');
        $entry = array($type, $message, $ts);
        if (!isset($_SESSION['lupo_wizard_audit_log'])) {
            $_SESSION['lupo_wizard_audit_log'] = array();
        }
        $_SESSION['lupo_wizard_audit_log'][] = $entry;
        return $entry;
    }

    public static function safeErrorMessage($context = 'operation') {
        return 'A database ' . $context . ' failed. Please check your data and try again, or download the log.';
    }
}

class InstallWizardCredentials {

    public static function getDbCredentials() {
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

class InstallWizardDb {

    public static function connectPdo($vars) {
        $dsn = "mysql:host={$vars['host']};port={$vars['port']};dbname={$vars['name']};charset={$vars['charset']}";
        return new PDO($dsn, $vars['user'], $vars['password'], array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 30,
        ));
    }

    public static function detectLivehelpTables($pdo) {
        $tables = array();
        $stmt = $pdo->query("SHOW TABLES LIKE 'livehelp_%'");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        return $tables;
    }
}

class InstallWizardSqlRunner {

    public static function runSqlFile($pdo, $path, &$log) {
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
        $sql = preg_replace('/--[^\n]*/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            function ($s) {
                return $s !== '' && !preg_match('/^\s*SET\s+/i', $s);
            }
        );
        $ok = true;
        foreach ($statements as $stmt) {
            if ($stmt === '') {
                continue;
            }
            try {
                $pdo->exec($stmt);
                $log[] = InstallWizardLogger::logEntry('ok', 'Statement executed: ' . $basename);
            } catch (PDOException $e) {
                $msg = $e->getMessage();
                if (stripos($msg, 'already exists') !== false || stripos($msg, 'Duplicate') !== false) {
                    $log[] = InstallWizardLogger::logEntry('skip', 'Statement skipped (already exists): ' . $basename);
                } else {
                    $log[] = InstallWizardLogger::logEntry('error', InstallWizardLogger::safeErrorMessage('statement') . ' [' . $basename . ']');
                    error_log('Lupopedia install SQL error [' . $basename . ']: ' . $msg);
                    $ok = false;
                }
            }
        }
        return $ok;
    }

    public static function dropLivehelpTables($pdo, $tables, &$log) {
        foreach ($tables as $table) {
            try {
                $pdo->exec("DROP TABLE IF EXISTS `" . str_replace('`', '``', $table) . "`");
                $log[] = InstallWizardLogger::logEntry('ok', 'Dropped ' . $table);
            } catch (PDOException $e) {
                $log[] = InstallWizardLogger::logEntry('error', 'Failed to drop table (see server log).');
                error_log('Lupopedia drop table ' . $table . ': ' . $e->getMessage());
            }
        }
    }
}

class InstallWizardConfigWriter {

    public static function writeConfig($db_vars, &$log, $options = array()) {
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
        $configDir = LUPOPEDIA_PATH;
        $configPath = $configDir . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
        $abspath = rtrim($configDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

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
define(\'LUPO_UPLOADS_DIR\', LUPO_CONTENT_DIR . \'/uploads\');
define(\'LUPO_PLUGINS_DIR\', LUPO_CONTENT_DIR . \'/plugins\');
define(\'LUPO_THEMES_DIR\', LUPO_CONTENT_DIR . \'/themes\');
$table_prefix = \'lupo_\';
if (!preg_match(\'/^[a-z0-9_]+$/\', $table_prefix)) { die("Invalid table prefix"); }
define(\'LUPO_TABLE_PREFIX\', $table_prefix);
if (!defined(\'LUPOPEDIA_ABSPATH\')) { define(\'LUPOPEDIA_ABSPATH\', ABSPATH); }
if (!defined(\'LUPOPEDIA_PUBLIC_PATH\')) { define(\'LUPOPEDIA_PUBLIC_PATH\', \'/\' . basename(dirname(__FILE__))); }
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
';

        if (!is_writable($configDir)) {
            $log[] = InstallWizardLogger::logEntry('error', 'Config directory not writable.');
            return null;
        }
        if (@file_put_contents($configPath, $content) === false) {
            $log[] = InstallWizardLogger::logEntry('error', 'Could not write config file.');
            return null;
        }
        @chmod($configPath, 0644);
        $log[] = InstallWizardLogger::logEntry('ok', 'Wrote lupopedia-config.php');
        return $configPath;
    }
}

class InstallWizardNormalize {

    public static function usernameToSlug($username) {
        $s = $username;
        $s = str_replace('@', '-at-', $s);
        $s = str_replace('.', '-dot-', $s);
        $s = strtolower($s);
        $s = preg_replace('/[^a-z0-9\-]/', '', $s);
        $s = trim(preg_replace('/-+/', '-', $s), '-');
        return $s !== '' ? $s . '-at-lupopedia-com' : 'unknown-at-lupopedia-com';
    }

    public static function isValidEmail($email) {
        $email = trim($email);
        return $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function isValidLupopediaSlug($s) {
        $s = trim($s);
        return $s !== '' && (bool) preg_match('/^[a-z0-9]+(-[a-z0-9]+)*-at-lupopedia-com$/', $s);
    }

    public static function isAcceptableResolvedEmail($value) {
        $value = trim($value);
        return $value !== '' && (self::isValidEmail($value) || self::isValidLupopediaSlug($value));
    }

    public static function loadCraftyUsers($pdo) {
        $stmt = $pdo->query("SELECT user_id, username, email, displayname, isoperator FROM livehelp_users ORDER BY isoperator DESC, user_id");
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : array();
    }

    public static function computeProposedIdentities($users) {
        $out = array();
        foreach ($users as $u) {
            $isOp = (strtoupper((string) (isset($u['isoperator']) ? $u['isoperator'] : '')) === 'Y');
            $username = trim((string) (isset($u['username']) ? $u['username'] : ''));
            $email = trim((string) (isset($u['email']) ? $u['email'] : ''));
            if ($isOp) {
                $slug = self::usernameToSlug($username);
                $out[] = array(
                    'user_id' => (int) $u['user_id'],
                    'username' => $username,
                    'email' => $email,
                    'displayname' => trim((string) (isset($u['displayname']) ? $u['displayname'] : '')),
                    'isoperator' => $isOp,
                    'proposed_email' => $slug,
                    'proposed_username' => $slug,
                );
            } else {
                $proposedEmail = (self::isValidEmail($email)) ? $email : self::usernameToSlug($username);
                $out[] = array(
                    'user_id' => (int) $u['user_id'],
                    'username' => $username,
                    'email' => $email,
                    'displayname' => trim((string) (isset($u['displayname']) ? $u['displayname'] : '')),
                    'isoperator' => $isOp,
                    'proposed_email' => $proposedEmail,
                    'proposed_username' => $username,
                );
            }
        }
        return $out;
    }

    public static function findDuplicateEmailGroups($identities) {
        $byEmail = array();
        foreach ($identities as $i => $row) {
            $key = strtolower($row['proposed_email']);
            if (!isset($byEmail[$key])) {
                $byEmail[$key] = array();
            }
            $byEmail[$key][] = $i;
        }
        return array_filter($byEmail, function ($indices) { return count($indices) > 1; });
    }

    public static function collectNormalizeWarnings($identities) {
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

    public static function validateResolvedEmails($identities, $resolved) {
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
                    if (!self::isValidLupopediaSlug($email)) {
                        $rowErrors[] = 'Operators must use slug-format (e.g. name-at-lupopedia-com).';
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
        $dupes = array_filter($lower, function ($ids) { return count($ids) > 1; });
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

    public static function applyNormalizationToLivehelp($pdo, $identities, $resolvedEmails) {
        $update = $pdo->prepare("UPDATE livehelp_users SET email = ?, username = ? WHERE user_id = ?");
        foreach ($identities as $row) {
            $id = $row['user_id'];
            $email = isset($resolvedEmails[$id]) ? $resolvedEmails[$id] : $row['proposed_email'];
            $username = $row['isoperator'] ? $email : $row['username'];
            $update->execute(array($email, $username, $id));
        }
    }
}

/**
 * Channel creation for install/upgrade. Uses department_id only.
 * Group tables (lupo_groups, lupo_actor_group_membership) are removed; organizational scope is department only.
 */
class InstallWizardChannels {

    public static function createReservedSystemChannels($pdo, &$log) {
        $federationNodeId = 1;
        $departmentId = 1;
        $now = (int) gmdate('YmdHis');

        $reserved = array(
            0   => array('key' => 'system',       'slug' => 'system',     'type' => 'system', 'name' => 'System Kernel Channel',       'desc' => 'System channel (kernel/system operations).', 'is_kernel' => 1, 'captain' => false),
            1   => array('key' => 'lobby',        'slug' => 'lobby',      'type' => 'public', 'name' => 'Orphan/Lobby Channel',         'desc' => 'Default public channel.',                    'is_kernel' => 0, 'captain' => true),
            42  => array('key' => 'crafty-dev',   'slug' => 'crafty-dev', 'type' => 'public', 'name' => 'Crafty Syntax Development',   'desc' => 'Crafty Syntax development channel.',         'is_kernel' => 0, 'captain' => true),
            5100=> array('key' => 'ai-dev',       'slug' => 'ai-dev',     'type' => 'public', 'name' => 'AI Agent Development',       'desc' => 'AI agent development channel.',              'is_kernel' => 0, 'captain' => true),
        );

        $check = $pdo->prepare("SELECT 1 FROM lupo_channels WHERE channel_id = ?");
        $insChannel = $pdo->prepare("
            INSERT INTO lupo_channels (
                channel_id, federation_node_id, created_by_actor_id, default_actor_id, department_id,
                channel_key, channel_slug, channel_type, language, channel_name, description, website_link,
                status_flag, created_ymdhis, updated_ymdhis, is_deleted, is_kernel
            ) VALUES (?, ?, 0, 0, ?, ?, ?, ?, 'en', ?, ?, NULL, 1, ?, ?, 0, ?)
        ");
        $nextRoleId = (int) $pdo->query("SELECT COALESCE(MAX(channel_role_id), 0) + 1 FROM lupo_channel_roles")->fetchColumn();
        $insRole = $pdo->prepare("
            INSERT INTO lupo_channel_roles (
                channel_role_id, channel_id, actor_id, role_type, created_ymdhis, updated_ymdhis, is_deleted
            ) VALUES (?, ?, 0, 'captain', ?, ?, 0)
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
                    $insRole->execute(array($nextRoleId, $channelId, $now, $now));
                    $log[] = InstallWizardLogger::logEntry('ok', 'Assigned system actor (0) as captain for channel ' . $channelId . '.');
                    $nextRoleId++;
                }
            } catch (PDOException $e) {
                $log[] = InstallWizardLogger::logEntry('error', 'Reserved channel creation failed (see server log).');
                error_log('Lupopedia reserved channel ' . $channelId . ': ' . $e->getMessage());
            }
        }
    }

    public static function createOperatorChannels($pdo, &$log) {
        $map = array();
        $federationNodeId = 1;
        $departmentId = 1;
        $defaultActorId = 1;
        $now = (int) gmdate('YmdHis');

        $stmt = $pdo->query("
            SELECT a.actor_id, a.slug, a.name
            FROM lupo_actors a
            WHERE a.actor_source_type = 'lupo_auth_users'
            AND a.is_deleted = 0
            ORDER BY a.actor_id
        ");
        if (!$stmt) {
            $log[] = InstallWizardLogger::logEntry('error', 'Could not select operators from lupo_actors.');
            return $map;
        }
        $operators = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($operators)) {
            $log[] = InstallWizardLogger::logEntry('ok', 'No operators to create channels for.');
            return $map;
        }

        $nextChannelId = (int) $pdo->query("SELECT COALESCE(MAX(channel_id), 0) + 1 FROM lupo_channels")->fetchColumn();
        $nextRoleId = (int) $pdo->query("SELECT COALESCE(MAX(channel_role_id), 0) + 1 FROM lupo_channel_roles")->fetchColumn();
        $usedKeys = array();
        foreach ($pdo->query("SELECT channel_key FROM lupo_channels WHERE federation_node_id = " . (int) $federationNodeId)->fetchAll(PDO::FETCH_COLUMN) as $k) {
            $usedKeys[strtolower($k)] = true;
        }

        $insChannel = $pdo->prepare("
            INSERT INTO lupo_channels (
                channel_id, federation_node_id, created_by_actor_id, default_actor_id, department_id,
                channel_key, channel_slug, channel_type, language, channel_name, description, website_link,
                status_flag, created_ymdhis, updated_ymdhis, is_deleted, is_kernel
            ) VALUES (?, ?, ?, ?, ?, ?, ?, 'chat_room', 'en', ?, ?, NULL, 1, ?, ?, 0, 0)
        ");
        $insRole = $pdo->prepare("
            INSERT INTO lupo_channel_roles (
                channel_role_id, channel_id, actor_id, role_type, created_ymdhis, updated_ymdhis, is_deleted
            ) VALUES (?, ?, ?, 'captain', ?, ?, 0)
        ");

        foreach ($operators as $op) {
            $actorId = (int) $op['actor_id'];
            $slug = trim((string) (isset($op['slug']) ? $op['slug'] : ''));
            $name = trim((string) (isset($op['name']) ? $op['name'] : $slug));
            if ($slug === '') {
                $slug = 'operator-' . $actorId;
            }
            $channelKey = $slug;
            $suffix = 1;
            while (isset($usedKeys[strtolower($channelKey)])) {
                $suffix++;
                $channelKey = $slug . '-' . $suffix;
            }
            $usedKeys[strtolower($channelKey)] = true;

            $channelName = $channelKey;
            $description = 'Operator channel for ' . $name;

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
                $insRole->execute(array($nextRoleId, $nextChannelId, $actorId, $now, $now));
                $map[$actorId] = $nextChannelId;
                $log[] = InstallWizardLogger::logEntry('ok', 'Created channel ' . $channelKey . ' (id ' . $nextChannelId . ') and assigned captain.');
            } catch (PDOException $e) {
                $log[] = InstallWizardLogger::logEntry('error', 'Operator channel creation failed (see server log).');
                error_log('Lupopedia operator channel ' . $slug . ': ' . $e->getMessage());
            }
            $nextChannelId++;
            $nextRoleId++;
        }

        return $map;
    }

    public static function ensureReservedChannels($pdo, &$log) {
        $required = array(0, 1, 42, 5100);
        try {
            $stmt = $pdo->query('SELECT channel_id FROM lupo_channels WHERE channel_id IN (0, 1, 42, 5100)');
            $have = $stmt ? $stmt->fetchAll(PDO::FETCH_COLUMN) : array();
            $missing = array_diff($required, array_map('intval', $have));
            if ($missing !== array()) {
                self::createReservedSystemChannels($pdo, $log);
                $log[] = InstallWizardLogger::logEntry('ok', 'Recreated missing reserved channels: ' . implode(', ', $missing));
            }
        } catch (PDOException $e) {
            $log[] = InstallWizardLogger::logEntry('error', 'Could not verify reserved channels.');
            error_log('Lupopedia ensure_reserved_channels: ' . $e->getMessage());
        }
    }

    public static function ensureOperatorChannels($pdo, &$log) {
        try {
            $stmt = $pdo->query("
                SELECT a.actor_id, a.slug, a.name
                FROM lupo_actors a
                WHERE a.actor_source_type = 'lupo_auth_users' AND a.is_deleted = 0
                AND NOT EXISTS (
                    SELECT 1 FROM lupo_channel_roles r
                    INNER JOIN lupo_channels c ON c.channel_id = r.channel_id AND c.created_by_actor_id = r.actor_id
                    WHERE r.actor_id = a.actor_id AND r.role_type = 'captain'
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
            $nextChannelId = (int) $pdo->query('SELECT COALESCE(MAX(channel_id), 0) + 1 FROM lupo_channels')->fetchColumn();
            $nextRoleId = (int) $pdo->query('SELECT COALESCE(MAX(channel_role_id), 0) + 1 FROM lupo_channel_roles')->fetchColumn();
            $usedKeys = array();
            foreach ($pdo->query('SELECT channel_key FROM lupo_channels WHERE federation_node_id = 1')->fetchAll(PDO::FETCH_COLUMN) as $k) {
                $usedKeys[strtolower($k)] = true;
            }
            $insChannel = $pdo->prepare("
                INSERT INTO lupo_channels (channel_id, federation_node_id, created_by_actor_id, default_actor_id, department_id, channel_key, channel_slug, channel_type, language, channel_name, description, website_link, status_flag, created_ymdhis, updated_ymdhis, is_deleted, is_kernel)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'chat_room', 'en', ?, ?, NULL, 1, ?, ?, 0, 0)
            ");
            $insRole = $pdo->prepare("
                INSERT INTO lupo_channel_roles (channel_role_id, channel_id, actor_id, role_type, created_ymdhis, updated_ymdhis, is_deleted)
                VALUES (?, ?, ?, 'captain', ?, ?, 0)
            ");
            foreach ($missing as $op) {
                $actorId = (int) $op['actor_id'];
                $slug = trim((string) (isset($op['slug']) ? $op['slug'] : ''));
                $name = trim((string) (isset($op['name']) ? $op['name'] : $slug));
                if ($slug === '') {
                    $slug = 'operator-' . $actorId;
                }
                $channelKey = $slug;
                $suffix = 1;
                while (isset($usedKeys[strtolower($channelKey)])) {
                    $channelKey = $slug . '-' . (++$suffix);
                }
                $usedKeys[strtolower($channelKey)] = true;
                $channelName = $channelKey;
                $description = 'Operator channel for ' . $name;
                try {
                    $insChannel->execute(array($nextChannelId, $federationNodeId, $actorId, $defaultActorId, $departmentId, $channelKey, $channelKey, $channelName, $description, $now, $now));
                    $insRole->execute(array($nextRoleId, $nextChannelId, $actorId, $now, $now));
                    $log[] = InstallWizardLogger::logEntry('ok', 'Created missing operator channel ' . $channelKey . ' (id ' . $nextChannelId . ').');
                } catch (PDOException $e) {
                    $log[] = InstallWizardLogger::logEntry('error', 'Failed to create missing operator channel (see server log).');
                    error_log('Lupopedia ensure_operator_channels ' . $slug . ': ' . $e->getMessage());
                }
                $nextChannelId++;
                $nextRoleId++;
            }
        } catch (PDOException $e) {
            $log[] = InstallWizardLogger::logEntry('error', 'Could not ensure operator channels.');
            error_log('Lupopedia ensure_operator_channels: ' . $e->getMessage());
        }
    }
}
