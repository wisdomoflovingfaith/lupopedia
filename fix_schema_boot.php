<?php
require_once 'lupopedia-config.php';
$db = DatabaseFactory::getConnection();

try {
    // 1. Make channel_id nullable or have a default in main lifecycle table
    $db->getPdo()->exec("ALTER TABLE lupo_channel_boot_lifecycle MODIFY channel_id bigint DEFAULT 0");
    echo "Altered lupo_channel_boot_lifecycle: channel_id now has default 0\n";

    // 2. Ensure total_channels and other fields have defaults if they don't
    $db->getPdo()->exec("ALTER TABLE lupo_channel_boot_lifecycle MODIFY total_channels int DEFAULT 0");
    $db->getPdo()->exec("ALTER TABLE lupo_channel_boot_lifecycle MODIFY channels_processed int DEFAULT 0");
    $db->getPdo()->exec("ALTER TABLE lupo_channel_boot_lifecycle MODIFY channels_successful int DEFAULT 0");
    $db->getPdo()->exec("ALTER TABLE lupo_channel_boot_lifecycle MODIFY channels_failed int DEFAULT 0");
    echo "Ensured defaults for count fields in lupo_channel_boot_lifecycle\n";

} catch (Exception $e) {
    echo "Error during schema fix: " . $e->getMessage() . "\n";
}
