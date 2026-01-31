<?php
/**
 * Crafty Syntax Operator Console - Entry Point
 *
 * This is the main entry point for the Crafty Syntax subsystem.
 * Handles routing and renders the operator overview.
 *
 * Path: /lupopedia/crafty_syntax/
 */

// Load bootstrap (handles auth, session, config)
require_once __DIR__ . '/bootstrap.php';

// Auth is enforced in bootstrap via require_login() and require_operator()
// If we reach here, user is authenticated and has operator role

// Get current operator data
$operator = lupo_crafty_operator();
$user = lupo_crafty_current_user();

// P1-5: AJAX endpoint for live mood updates
if (isset($_GET['ajax']) && $_GET['ajax'] === 'mood') {
    header('Content-Type: application/json');
    $emotional = lupo_crafty_get_emotional_metadata($operator);
    echo json_encode($emotional);
    exit;
}

if (!$operator || !$user) {
    // This should not happen due to bootstrap checks, but handle gracefully
    header('HTTP/1.1 500 Internal Server Error');
    echo '<h1>Error</h1><p>Unable to load operator data.</p>';
    exit;
}

// Fetch operator status
$db = lupo_crafty_db();
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

$stmt = $db->prepare("SELECT * FROM {$table_prefix}operator_status WHERE operator_id = :operator_id LIMIT 1");
$stmt->execute([':operator_id' => $operator['operator_id']]);
$status = $stmt->fetch(PDO::FETCH_ASSOC);

// Default status if not found
if (!$status) {
    $status = [
        'status' => 'offline',
        'active_chat_count' => 0,
        'max_chat_capacity' => 5,
        'last_seen_ymdhis' => lupo_utc_timestamp()
    ];
}

// Fetch system data
$stmt = $db->prepare("SELECT COUNT(*) as count FROM {$table_prefix}dialog_threads WHERE status = 'active'");
$stmt->execute();
$active_threads = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$stmt = $db->prepare("SELECT * FROM {$table_prefix}departments WHERE is_deleted = 0");
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Emotional metadata (using real calculation)
$emotional = lupo_crafty_get_emotional_metadata($operator);

// Expertise snapshot (placeholder)
$stmt = $db->prepare("
    SELECT
        o.operator_id,
        a.name as operator_name,
        o.pono_score,
        o.pilau_score,
        o.kapakai_score,
        os.status as availability_label,
        os.active_chat_count
    FROM {$table_prefix}operators o
    JOIN {$table_prefix}actors a ON o.actor_id = a.actor_id
    LEFT JOIN {$table_prefix}operator_status os ON o.operator_id = os.operator_id
    WHERE o.is_active = 1
    ORDER BY o.operator_id ASC
    LIMIT 10
");
$stmt->execute();
$operators = $stmt->fetchAll(PDO::FETCH_ASSOC);

$expertise_snapshot = [];
foreach ($operators as $op) {
    // Calculate scores (simplified)
    $pono = $op['pono_score'] ?? 1.0;
    $pilau = $op['pilau_score'] ?? 0.0;
    $kapakai = $op['kapakai_score'] ?? 0.5;

    $emotional_stability = $pono - $pilau;
    $availability_score = ($op['availability_label'] === 'online') ? 1 : 0;
    $load_score = 1 - (($op['active_chat_count'] ?? 0) / 5);
    $performance_score = 0; // Placeholder
    $expertise_score = ($emotional_stability + $availability_score + $load_score) / 3;

    // P1-4: Calculate Heartmind Unified Metric
    // Formula: heartmind = (expertise_score * 0.6 + emotional_stability * 0.4) * (1 - kapakai)
    $heartmind = ($expertise_score * 0.6 + $emotional_stability * 0.4) * (1 - $kapakai);

    // Calculate mood_rgb using emotional metadata function
    $mood_rgb = lupo_crafty_calculate_mood_rgb($pono, $pilau, $kapakai);

    $expertise_snapshot[] = [
        'operator_id' => $op['operator_id'],
        'operator_name' => $op['operator_name'],
        'department_names' => [$operator['department_name'] ?? 'Unassigned'],
        'channel_names' => [],
        'emotional_stability' => round($emotional_stability, 2),
        'availability_label' => $op['availability_label'] ?? 'offline',
        'availability_score' => $availability_score,
        'load_score' => round($load_score, 2),
        'active_chat_count' => $op['active_chat_count'] ?? 0,
        'performance_score' => $performance_score,
        'expertise_score' => round($expertise_score, 2),
        'heartmind' => round($heartmind, 2),
        'mood_rgb' => $mood_rgb
    ];
}

// Recent threads
$stmt = $db->prepare("
    SELECT dialog_thread_id, summary_text, status, updated_ymdhis
    FROM {$table_prefix}dialog_threads
    ORDER BY updated_ymdhis DESC
    LIMIT 10
");
$stmt->execute();
$recent_threads = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recent transcripts (placeholder - using same threads)
$recent_transcripts = $recent_threads;

// Render view
require_once __DIR__ . '/views/operator_overview.php';
