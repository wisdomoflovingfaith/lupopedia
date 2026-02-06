<?php
/**
 * DatabaseFactory — single point for database connections (Doctrine: SQL 17.2).
 * All DB access must use DatabaseFactory::getConnection() or lupo_get_db().
 * Returns PDO_DB wrapper only. Direct PDO, mysqli, and new PDO_DB() are forbidden.
 *
 * FILE: lupo-includes/class-DatabaseFactory.php
 * TYPE: php
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. class-DatabaseFactory.php cannot be called directly.");
}

if (!class_exists('PDO_DB')) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'class-pdo_db.php';
}

class DatabaseFactory {
    /** @var PDO_DB|null */
    private static $connection = null;

    /**
     * Get the single database connection (PDO_DB). Creates it from config if needed.
     * Use this or lupo_get_db() for ALL database access. Do not instantiate PDO or PDO_DB directly.
     *
     * @return PDO_DB
     * @throws Exception If config constants are missing or connection fails
     */
    public static function getConnection() {
        if (self::$connection !== null) {
            return self::$connection;
        }
        $host = defined('DB_HOST') ? DB_HOST : 'localhost';
        $user = defined('DB_USER') ? DB_USER : '';
        $pass = defined('DB_PASSWORD') ? DB_PASSWORD : '';
        $name = defined('DB_NAME') ? DB_NAME : '';
        $type = defined('DB_TYPE') ? DB_TYPE : 'mysql';
        self::$connection = new PDO_DB($host, $user, $pass, $name, $type);
        // UTC timezone (bootstrap may also set this; idempotent)
        if ($type === 'mysql') {
            self::$connection->exec("SET time_zone = '+00:00'");
        } else {
            self::$connection->exec("SET timezone = 'UTC'");
        }
        return self::$connection;
    }

    /**
     * Reset the stored connection (e.g. for tests). Do not use in production.
     */
    public static function resetConnection() {
        self::$connection = null;
    }
}

/**
 * Return the canonical DB connection (PDO_DB). Use for all DB access.
 * @return PDO_DB|null
 */
function lupo_get_db() {
    if (isset($GLOBALS['mydatabase']) && $GLOBALS['mydatabase'] !== null) {
        return $GLOBALS['mydatabase'];
    }
    if (class_exists('DatabaseFactory')) {
        return DatabaseFactory::getConnection();
    }
    return null;
}
