<?php
require_once __DIR__ . '/lupopedia-config.php';
$db = DatabaseFactory::getConnection();
$p  = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

$total = $db->fetchOne("SELECT COUNT(*) as c FROM {$p}metadata WHERE is_deleted=0");
echo "metadata total (active): " . $total['c'] . PHP_EOL;

$stale = $db->fetchAll("
    SELECT entity_type, entity_id, property_key, LEFT(property_value,30) as val, class_name
    FROM {$p}metadata
    WHERE is_deleted=0
      AND property_key='last_verified'
      AND (property_value IS NULL OR CAST(property_value AS UNSIGNED) < 20260301000000)
    LIMIT 10
");
echo "stale rows: " . count($stale) . PHP_EOL;
foreach ($stale as $r) {
    echo "  entity_type={$r['entity_type']} entity_id={$r['entity_id']} val={$r['val']}" . PHP_EOL;
}

// Also check distinct entity_types and counts
$types = $db->fetchAll("SELECT entity_type, COUNT(*) as cnt FROM {$p}metadata WHERE is_deleted=0 GROUP BY entity_type");
echo PHP_EOL . "Metadata by entity_type:" . PHP_EOL;
foreach ($types as $t) {
    echo "  {$t['entity_type']}: {$t['cnt']}" . PHP_EOL;
}
