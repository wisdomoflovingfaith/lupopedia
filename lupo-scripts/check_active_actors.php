<?php
/**
 * Check Active Actors Today
 * 
 * Shows all actors that have made an action today (UTC)
 * 
 * Usage: php scripts/check_active_actors.php
 */

require_once __DIR__ . '/../lupopedia-config.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/class-pdo_db.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/class-DatabaseFactory.php';

$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

// Today in YYYYMMDD format
$today = gmdate('Ymd');
echo "\n=== CHECKING ACTIVE ACTORS FOR: $today (UTC) ===\n\n";

// Query actors with activity today
$sql = "SELECT 
    a.actor_id,
    a.actor_name,
    a.actor_type,
    a.is_ai_agent,
    a.last_activity_ymdhis,
    a.created_ymdhis
FROM {$prefix}actors a
WHERE a.last_activity_ymdhis >= :today_start
  AND a.last_activity_ymdhis < :today_end
  AND a.is_deleted = 0
ORDER BY a.last_activity_ymdhis DESC";

$today_start = $today . '000000';
$tomorrow = gmdate('Ymd', strtotime('+1 day'));
$today_end = $tomorrow . '000000';

$actors = $db->fetchAll($sql, [
    'today_start' => $today_start,
    'today_end' => $today_end
]);

echo "Total Active Actors: " . count($actors) . "\n\n";

if (empty($actors)) {
    echo "No actors have been active today.\n";
    echo "Checking last 7 days instead...\n\n";
    
    $week_ago = gmdate('Ymd', strtotime('-7 days'));
    $sql = "SELECT 
        a.actor_id,
        a.actor_name,
        a.actor_type,
        a.is_ai_agent,
        a.last_activity_ymdhis,
        a.created_ymdhis
    FROM {$prefix}actors a
    WHERE a.last_activity_ymdhis >= :week_start
      AND a.is_deleted = 0
    ORDER BY a.last_activity_ymdhis DESC
    LIMIT 20";
    
    $actors = $db->fetchAll($sql, ['week_start' => $week_ago . '000000']);
    echo "Active in Last 7 Days: " . count($actors) . "\n\n";
}

foreach ($actors as $actor) {
    $type = $actor['is_ai_agent'] ? 'AI AGENT' : 'HUMAN';
    $last = $actor['last_activity_ymdhis'];
    
    // Format timestamp
    $date = substr($last, 0, 4) . '-' . substr($last, 4, 2) . '-' . substr($last, 6, 2);
    $time = substr($last, 8, 2) . ':' . substr($last, 10, 2) . ':' . substr($last, 12, 2);
    
    echo "[$type] Actor #{$actor['actor_id']}: {$actor['actor_name']}\n";
    echo "  Type: {$actor['actor_type']}\n";
    echo "  Last Activity: $date $time UTC\n\n";
}

// Show AI agents specifically
echo "\n=== AI AGENTS SUMMARY ===\n\n";
$ai_sql = "SELECT 
    actor_id,
    actor_name,
    actor_type,
    last_activity_ymdhis
FROM {$prefix}actors
WHERE is_ai_agent = 1
  AND is_deleted = 0
ORDER BY actor_id";

$ai_agents = $db->fetchAll($ai_sql);
echo "Total AI Agents: " . count($ai_agents) . "\n\n";

foreach ($ai_agents as $agent) {
    $last = $agent['last_activity_ymdhis'];
    if ($last) {
        $date = substr($last, 0, 4) . '-' . substr($last, 4, 2) . '-' . substr($last, 6, 2);
        $time = substr($last, 8, 2) . ':' . substr($last, 10, 2);
        $activity = "$date $time UTC";
    } else {
        $activity = "Never";
    }
    
    echo "Actor #{$agent['actor_id']}: {$agent['actor_name']}\n";
    echo "  Type: {$agent['actor_type']}\n";
    echo "  Last Activity: $activity\n\n";
}
