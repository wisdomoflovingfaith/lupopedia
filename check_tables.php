<?php
require_once 'lupopedia-config.php';
$db = DatabaseFactory::getConnection();
$tables = $db->fetchAll("SHOW TABLES");
$tableList = array();
foreach ($tables as $table) {
    $tableList[] = array_values($table)[0];
}

$required = array('lupo_channel_boot_lifecycle', 'lupo_channel_boot_detail_lifecycle');
foreach ($required as $table) {
    if (in_array($table, $tableList)) {
        echo "Table $table: EXISTS\n";
    } else {
        echo "Table $table: MISSING\n";
    }
}
