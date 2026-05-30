<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/lupopedia-config.php';
$db = DatabaseFactory::getConnection();
$p  = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

$r1 = $db->fetchOne("SELECT COUNT(*) as c FROM {$p}edge_types WHERE is_deleted=0");
$r2 = $db->fetchOne("SELECT COUNT(*) as c FROM {$p}edge_type_definitions");
$r3 = $db->fetchOne("SELECT COUNT(*) as c FROM {$p}edges WHERE is_deleted=0");
$r4 = $db->fetchOne("SELECT COUNT(*) as c FROM {$p}edges WHERE edge_type='channel_parent' AND is_deleted=0");
$r5 = $db->fetchAll("SELECT channel_id, channel_name, parent_channel_id FROM {$p}channels WHERE parent_channel_id IS NOT NULL AND parent_channel_id > 0 AND is_deleted=0 LIMIT 20");

// debug: show raw result if 'c' key missing
$c1 = is_array($r1) ? (isset($r1['c']) ? $r1['c'] : print_r($r1, true)) : var_export($r1, true);
$c2 = is_array($r2) ? (isset($r2['c']) ? $r2['c'] : print_r($r2, true)) : var_export($r2, true);
$c3 = is_array($r3) ? (isset($r3['c']) ? $r3['c'] : print_r($r3, true)) : var_export($r3, true);
$c4 = is_array($r4) ? (isset($r4['c']) ? $r4['c'] : print_r($r4, true)) : var_export($r4, true);

echo "lupo_edge_types (active):       " . $c1 . PHP_EOL;
echo "lupo_edge_type_definitions:     " . $c2 . PHP_EOL;
echo "lupo_edges (active):            " . $c3 . PHP_EOL;
echo "lupo_edges channel_parent:      " . $c4 . PHP_EOL;
echo PHP_EOL . "Channels with parent_channel_id:" . PHP_EOL;
foreach ($r5 as $row) {
    echo "  channel_id={$row['channel_id']}  name={$row['channel_name']}  parent={$row['parent_channel_id']}" . PHP_EOL;
}
