<?php
echo "START ROW AUDIT\n";
require_once 'lupopedia-config.php';
$tables = array(
    'lupo_system_logs',
    'lupo_system_events',
    'lupo_task_events',
    'lupo_meta_log_events',
    'lupo_agent_event_log',
    'lupo_channel_event_log',
    'lupo_dialog_event_log',
    'lupo_memory_events',
    'lupo_tab_events',
    'lupo_world_events'
);
$db = DatabaseFactory::getConnection();
foreach ($tables as $t) {
    try {
        $row = $db->fetchRow("SELECT COUNT(*) as cnt FROM $t");
        $count = $row ? $row['cnt'] : 0;
        echo "$t: $count\n";
    } catch (Exception $e) {
        echo "$t: ERROR (" . $e->getMessage() . ")\n";
    }
}
