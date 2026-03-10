<?php
define('LUPOPEDIA_PATH', __DIR__);
define('LUPO_DATABASE_DIR', LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-database');
define('LUPO_MYSQL_DIR', LUPO_DATABASE_DIR . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'mysql');

require_once __DIR__ . '/lupopedia-config.php';
require_once __DIR__ . '/install_wizard_classes.php';

function run_import()
{
    $dsn = DB_TYPE . ":host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));

        $importSql = LUPO_MYSQL_DIR . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'import_from_old_crafty_syntax.sql';
        $log = array();
        $table_prefix = LUPO_TABLE_PREFIX;

        echo "Running import from: $importSql\n";

        $ok = InstallWizardSqlRunner::runSqlFile($pdo, $importSql, $log, $table_prefix);

        if ($ok) {
            echo "Import reported success!\n";
        } else {
            echo "Import reported errors (see below).\n";
        }

        foreach ($log as $entry) {
            if ($entry[0] === 'error') {
                echo "ERROR: " . $entry[1] . "\n";
            }
        }

    } catch (Exception $e) {
        echo "PDO Error: " . $e->getMessage() . "\n";
    }
}

run_import();
