<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "lupo-templates/channels/index.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-templates/channels/index.php"
#   status: "complete"
#   when_updated: "20260419131059"
#   trust_tier: "staging"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/staging/2026/04/channels-index-php.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/channels-index"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: 2
#   content_slug: "channels-index-php"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Channels Index Mockup (target selector)"
#   summary: "Copy of channels/index.php for lupo-templates; target selector; composer labels by mode; sidebar Visitors/Users + collapsible Agents/Recent Files/Tasks (gray headers); feed demo lines."
# ---------------------------------------------------------------------
define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(LUPOPEDIA_PATH));
// Default channel constants — no magic numbers in channel-resolution fallback logic.
define('LUPO_DEFAULT_CHANNEL_KEY', 'lupopedia-development');
define('LUPO_DEFAULT_CHANNEL_ID', 42);
$UNTRUSTED = array(
    'get'    => (isset($_GET)    && is_array($_GET))    ? $_GET    : array(),
    'server' => (isset($_SERVER) && is_array($_SERVER)) ? $_SERVER : array(),
);

require_once LUPOPEDIA_PATH . '/lupo-includes/classes/LupopediaConfigResolver.php';
$lupoResolvedCfg = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH);
define('LUPOPEDIA_CONFIG_PATH', $lupoResolvedCfg ?: LUPOPEDIA_PATH . '/lupopedia-config.php');
require_once LUPOPEDIA_CONFIG_PATH;

// Include security helpers for CSRF
if (file_exists(LUPOPEDIA_PATH . '/lupo-includes/functions/security.php')) {
    require_once LUPOPEDIA_PATH . '/lupo-includes/functions/security.php';
}
if (!function_exists('lupo_verify_csrf_token')) {
    function lupo_verify_csrf_token($token) {
        if (!function_exists('lupo_get_csrf_token')) return false;
        $session_token = (string) lupo_get_csrf_token();
        return ($token !== '' && $session_token !== '' && $token === $session_token);
    }
}
if (!function_exists('lupo_get_csrf_token')) {
    // Minimal fallback: return empty string so template renders without fatal error.
    // Real token is provided by security.php loaded above.
    function lupo_get_csrf_token() { return ''; }
}

require_once LUPOPEDIA_PATH . '/lupo-includes/classes/LupoLocale.php';
LupoLocale::bootstrap(LUPOPEDIA_PATH);
require_once LUPOPEDIA_PATH . '/lupo-includes/lupo-i18n.php';

$authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
if ($authService) {
    $authService->requireLogin();
} else {
    if (!function_exists('require_login')) {
        require_once LUPOPEDIA_PATH . '/lupo-includes/functions/auth-helpers.php';
    }
    require_login();
}

$user = $authService ? $authService->getCurrentUser() : (function_exists('current_user') ? current_user() : array());
$isUserLoggedIn = ($user !== false && !empty($user));
$base           = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';

// Resolve Actor
$admin_active_actor_id      = 0;
$admin_active_actor_display = 'Unknown';
if ($isUserLoggedIn) {
    if (isset($GLOBALS['lupo_session']) && is_object($GLOBALS['lupo_session'])) {
        $admin_active_actor_id = (int) $GLOBALS['lupo_session']->getActorId();
    }
    if ($admin_active_actor_id <= 0 && !empty($user['actor_id'])) {
        $admin_active_actor_id = (int) $user['actor_id'];
    }
}

// Dependencies
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DatabaseFactory.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/TimestampYmdhis.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/IdGenerator.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DialogMvpService.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/functions/channel_chat_row.php';

// Task auth: must be populated before POST handling (isTaskAssigneeAuthorized / task mode checks).
$current_auth_user_id = ($isUserLoggedIn && is_array($user) && isset($user['auth_user_id'])) ? (int) $user['auth_user_id'] : 0;
$task_scope_admin = ($authService && is_object($authService) && method_exists($authService, 'isAdmin') && $admin_active_actor_id > 0)
    ? (bool) $authService->isAdmin($admin_active_actor_id)
    : false;

$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$db = DatabaseFactory::getConnection();

if ($admin_active_actor_id > 0) {
    $actor_row = $db->fetchRow("SELECT name, actor_name FROM {$prefix}actors WHERE actor_id = :aid", array('aid' => $admin_active_actor_id));
    if ($actor_row) {
        $admin_active_actor_display = $actor_row['name'] ?: $actor_row['actor_name'];
    }
}

// Read-only API provider / budget dashboard (GET api_dashboard=1). Skips channel chat stack; content from lupo-templates/admin/api_dashboard.php.
$channels_api_dashboard = isset($UNTRUSTED['get']['api_dashboard']) && (string) $UNTRUSTED['get']['api_dashboard'] === '1';
if ($channels_api_dashboard) {
    $admin_page_title = lupo_t('channels.api_agents.title', 'API Call Agents / Channels');
    $admin_active_key = 'Channels';
    ob_start();
    echo '<style type="text/css">';
    echo '.lupo-channels-api-dashboard{font-family:monospace;padding:12px;max-width:960px;}';
    echo '.lupo-channels-api-dashboard .lupo-api-dash-notice{margin:10px 0;padding:8px;border:1px solid #666;background:#faf8ef;}';
    echo '.lupo-channels-api-dashboard .lupo-api-dash-summary{margin:8px 0;}';
    echo '.lupo-channels-api-dashboard .lupo-table{width:100%;border-collapse:collapse;margin-top:10px;}';
    echo '.lupo-channels-api-dashboard .lupo-table th,.lupo-channels-api-dashboard .lupo-table td{border:1px solid #333;padding:6px 8px;text-align:left;vertical-align:top;}';
    echo '.lupo-channels-api-dashboard .lupo-table th{background:#2d3748;color:#e2e8f0;}';
    echo '.lupo-channels-api-dashboard .lupo-status-green{color:#228B22;font-weight:bold;}';
    echo '.lupo-channels-api-dashboard .lupo-status-yellow{color:#b8860b;font-weight:bold;}';
    echo '.lupo-channels-api-dashboard .lupo-status-red{color:#b22222;font-weight:bold;}';
    echo '.lupo-channels-api-dashboard .lupo-status-muted{color:#444;font-weight:bold;}';
    echo '</style>';
    echo '<div class="lupo-channels-api-dashboard">';
    require LUPOPEDIA_PATH . '/lupo-templates/admin/api_dashboard.php';
    echo '</div>';
    $admin_main_content = ob_get_clean();
    require LUPOPEDIA_PATH . '/lupo-includes/themes/default/layouts/admin_layout.php';
    exit;
}

// Input
$channel_name_key = preg_replace('/[^a-z0-9_\-]/', '', strtolower(isset($UNTRUSTED['get']['channel']) ? (string) $UNTRUSTED['get']['channel'] : 'lupopedia-development')) ?: 'lupopedia-development';
$__packed_utc = (int) timestamp_ymdhis::now();
$__tc = timestamp_ymdhis::explode($__packed_utc);
$today_thread_key = sprintf('%04d-%02d-%02d', $__tc['year'], $__tc['month'], $__tc['day']);

// State
$channel_id   = 0;
$thread_id    = 0;
$messages     = array();
$actors       = array();
$all_channels = array();
$db_error     = '';
$last_time_cursor = 0;

function hermes_normalize_summary($text, $max_len) {
    $clean = preg_replace('/\s+/', ' ', trim((string) $text));
    if (strlen($clean) > $max_len) {
        return substr($clean, 0, $max_len);
    }
    return $clean;
}

function hermes_route_message($message, $from_actor_id, $to_actor_id, $channel_id, $task_assignee_id = 0, $auth_user_id = 0, $task_scope_admin_bypass = false) {
    $text = trim((string) $message);
    $message_type = ((int) $to_actor_id > 0) ? 'directed' : 'system';
    $routing_provenance = 'hermes:direct';
    $patterns = array();
    $taskParsed = array(
        'target_actor_id' => 0,
        'task_body' => '',
    );

    if ((int) $task_assignee_id > 0 && stripos($text, '[task]') === 0) {
        $task_body = trim((string) preg_replace('/^\[task\]\s*/i', '', $text));
        if ($task_body === '') {
            $task_body = $text;
        }
        if ($auth_user_id > 0) {
            global $db;
            if (!DialogMvpService::isTaskAssigneeAuthorized($db, (int) $channel_id, (int) $auth_user_id, (int) $task_assignee_id, (bool) $task_scope_admin_bypass)) {
                $taskParsed['task_body'] = $task_body;
                $taskParsed['target_actor_id'] = 0;
                $message_type = 'task';
                $routing_provenance = 'hermes:error';
            } else {
                $taskParsed['target_actor_id'] = (int) $task_assignee_id;
                $taskParsed['task_body'] = $task_body;
            }
        } else {
            $taskParsed['target_actor_id'] = (int) $task_assignee_id;
            $taskParsed['task_body'] = $task_body;
        }
    } elseif (stripos($text, '[task]') === 0) {
        $routing_provenance = 'hermes:error';
        $message_type = 'task';
        $taskParsed['task_body'] = trim((string) preg_replace('/^\[task\]\s*/i', '', $text));
    }

    if ($taskParsed['target_actor_id'] > 0) {
        $message_type = 'task';
        $routing_provenance = 'hermes:task-router';
        $patterns[] = array(
            'pattern_type' => 'task_assignment',
            'from_actor_id' => (int) $from_actor_id,
            'to_actor_id' => (int) $taskParsed['target_actor_id'],
            'summary' => hermes_normalize_summary($taskParsed['task_body'], 160)
        );
    } elseif (preg_match('/^\[alert\]/i', $text)) {
        $message_type = 'alert';
        $routing_provenance = 'hermes:alert';
        $patterns[] = array(
            'pattern_type' => 'alert',
            'from_actor_id' => (int) $from_actor_id,
            'to_actor_id' => (int) $to_actor_id,
            'summary' => hermes_normalize_summary($text, 160)
        );
    } elseif (preg_match('/^\[decision\]/i', $text)) {
        $message_type = 'decision';
        $routing_provenance = 'hermes:decision';
        $patterns[] = array(
            'pattern_type' => 'decision',
            'from_actor_id' => (int) $from_actor_id,
            'to_actor_id' => (int) $to_actor_id,
            'summary' => hermes_normalize_summary($text, 160)
        );
    } elseif (preg_match('/^\[question\]/i', $text) || preg_match('/\bOQ\-[0-9]+\b/i', $text)) {
        $message_type = 'question';
        $routing_provenance = 'hermes:question';
        $patterns[] = array(
            'pattern_type' => 'question',
            'from_actor_id' => (int) $from_actor_id,
            'to_actor_id' => (int) $to_actor_id,
            'summary' => hermes_normalize_summary($text, 160)
        );
    } elseif (preg_match('/^\[stderr\]/i', $text)) {
        $message_type = 'stderr';
        $routing_provenance = 'hermes:stderr';
    } elseif (preg_match('/^\[stdout\]/i', $text)) {
        $message_type = 'stdout';
        $routing_provenance = 'hermes:stdout';
    }

    if (preg_match('/send to .*channel|cross-channel|route to/i', $text)) {
        $patterns[] = array(
            'pattern_type' => 'cross_channel_route',
            'from_actor_id' => (int) $from_actor_id,
            'to_actor_id' => (int) $to_actor_id,
            'summary' => hermes_normalize_summary($text, 160)
        );
    }

    return array(
        'message_type' => $message_type,
        'routing_provenance' => $routing_provenance,
        'patterns' => $patterns,
        'task_target_actor_id' => (int) $taskParsed['target_actor_id'],
        'task_body' => (string) $taskParsed['task_body'],
        'channel_id' => (int) $channel_id
    );
}

function hermes_write_to_transcript($message_data, $channel_id) {
    global $db, $prefix, $today_thread_key;
    // PRD 82 (lupo-docs/prd/82_hermes_message_routing_memory_gateway.md): single canonical JSONL path only.
    // federation_node_id in the transcript path must match lupo_channels.federation_node_id for this channel_id (default 0 when unset); keep channel row and transcript segment aligned.
    $now = (int) DialogMvpService::nowYmdHis();
    $record = array(
        'ts' => $now,
        'from_actor_id' => isset($message_data['from_actor_id']) ? (int) $message_data['from_actor_id'] : 0,
        'to_actor_id' => isset($message_data['to_actor_id']) ? (int) $message_data['to_actor_id'] : 0,
        'message_text' => isset($message_data['message_text']) ? (string) $message_data['message_text'] : '',
        'message_type' => isset($message_data['message_type']) ? (string) $message_data['message_type'] : 'system',
        'routing_provenance' => isset($message_data['routing_provenance']) ? (string) $message_data['routing_provenance'] : 'hermes:direct'
    );
    $ch = $db->fetchRow(
        "SELECT channel_key, federation_node_id FROM {$prefix}channels WHERE channel_id = :cid AND is_deleted = 0 LIMIT 1",
        array('cid' => (int) $channel_id)
    );
    $channel_key = ($ch && !empty($ch['channel_key'])) ? (string) $ch['channel_key'] : 'development';
    $channel_key = preg_replace('/[^a-z0-9_\-]/', '-', strtolower($channel_key));
    $fed = ($ch && isset($ch['federation_node_id'])) ? (int) $ch['federation_node_id'] : 0;
    $thread_slug = preg_replace('/[^a-z0-9_\-]/', '-', strtolower((string) $today_thread_key));
    if ($thread_slug === '') {
        $thread_slug = 'general';
    }
    $dir = LUPOPEDIA_PATH . '/lupo-memory/transcripts/' . $fed . '/' . $channel_key;
    if (!is_dir($dir)) {
        if (!@mkdir($dir, 0755, true)) {
            DialogMvpService::logDefect('P1-CHANNELS-TRANSCRIPT-MKDIR-001', array(
                'channel_id' => (int) $channel_id,
                'path' => $dir,
                'reason' => 'mkdir_failed',
            ));
            return array('ok' => false, 'path' => $dir, 'ts' => $now);
        }
    }
    $path = $dir . '/' . $thread_slug . '.jsonl';
    $line = json_encode($record, JSON_UNESCAPED_SLASHES);
    if ($line === false) {
        DialogMvpService::logDefect('P2-CHANNELS-TRANSCRIPT-JSON-001', array(
            'channel_id' => $channel_id,
            'reason' => 'json_encode_failed',
            'record' => $record,
        ));
        return array('ok' => false, 'path' => $path, 'ts' => $now);
    }
    $written = @file_put_contents($path, $line . "\n", FILE_APPEND | LOCK_EX);
    if ($written === false) {
        DialogMvpService::logDefect('P1-CHANNELS-TRANSCRIPT-WRITE-001', array(
            'channel_id' => $channel_id,
            'path' => $path,
            'reason' => 'file_write_failed',
        ));
    }
    return array(
        'ok' => ($written !== false),
        'path' => str_replace('\\', '/', $path),
        'ts' => $now
    );
}

function hermes_flag_promotion_candidate($pattern_id, $reason) {
    return array(
        'pattern_id' => (string) $pattern_id,
        'promotion_candidate' => true,
        'reason' => (string) $reason
    );
}

function hermes_write_to_staging_toon($extracted_patterns, $channel_id) {
    if (!is_array($extracted_patterns) || count($extracted_patterns) === 0) {
        return array('ok' => true, 'path' => '', 'flagged' => array());
    }
    global $db, $prefix, $today_thread_key;
    $ch = $db->fetchRow(
        "SELECT channel_key FROM {$prefix}channels WHERE channel_id = :cid LIMIT 1",
        array('cid' => (int) $channel_id)
    );
    $channel_key = ($ch && !empty($ch['channel_key'])) ? (string) $ch['channel_key'] : 'development';
    $channel_key = preg_replace('/[^a-z0-9_\-]/', '-', strtolower($channel_key));
    $thread_slug = preg_replace('/[^a-z0-9_\-]/', '-', strtolower((string) $today_thread_key));
    if ($thread_slug === '') {
        $thread_slug = 'general';
    }
    $nowPacked = (int) DialogMvpService::nowYmdHis();
    $ex = timestamp_ymdhis::explode($nowPacked);
    $yyyy = sprintf('%04d', $ex['year']);
    $mm = sprintf('%02d', $ex['month']);
    $dir = LUPOPEDIA_PATH . '/lupo-memory/' . $channel_key . '/staging/' . $yyyy . '/' . $mm;
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $path = $dir . '/' . $thread_slug . '.toon';
    $now = (int) DialogMvpService::nowYmdHis();
    $doc = array(
        'type' => 'staging_memory',
        'channel_key' => $channel_key,
        'thread_slug' => $thread_slug,
        'trust_tier' => 'staging',
        'when_updated' => $now,
        'source_actor_id' => 15,
        'patterns' => array()
    );
    if (is_file($path)) {
        $raw = @file_get_contents($path);
        if ($raw !== false) {
            $parsed = json_decode($raw, true);
            if (is_array($parsed) && isset($parsed['patterns']) && is_array($parsed['patterns'])) {
                $doc = $parsed;
                $doc['when_updated'] = $now;
            }
        }
    }
    $flagged = array();
    $patterns = $doc['patterns'];
    foreach ($extracted_patterns as $p) {
        $ptype = isset($p['pattern_type']) ? (string) $p['pattern_type'] : 'signal';
        $from = isset($p['from_actor_id']) ? (int) $p['from_actor_id'] : 0;
        $to = isset($p['to_actor_id']) ? (int) $p['to_actor_id'] : 0;
        $summary = isset($p['summary']) ? hermes_normalize_summary($p['summary'], 160) : '';
        $needle = strtolower(substr($summary, 0, 80));
        $found_index = -1;
        for ($i = 0; $i < count($patterns); $i++) {
            $existing = $patterns[$i];
            $existing_summary = isset($existing['summary']) ? strtolower(substr((string) $existing['summary'], 0, 80)) : '';
            if (
                isset($existing['pattern_type']) && (string) $existing['pattern_type'] === $ptype &&
                isset($existing['from_actor_id']) && (int) $existing['from_actor_id'] === $from &&
                isset($existing['to_actor_id']) && (int) $existing['to_actor_id'] === $to &&
                $existing_summary === $needle
            ) {
                $found_index = $i;
                break;
            }
        }
        if ($found_index >= 0) {
            $patterns[$found_index]['occurrence_count'] = isset($patterns[$found_index]['occurrence_count'])
                ? ((int) $patterns[$found_index]['occurrence_count'] + 1) : 2;
            $patterns[$found_index]['ts'] = $now;
            if ((int) $patterns[$found_index]['occurrence_count'] >= 3 && empty($patterns[$found_index]['promotion_candidate'])) {
                $patterns[$found_index]['promotion_candidate'] = true;
                $pid = isset($patterns[$found_index]['pattern_id']) ? $patterns[$found_index]['pattern_id'] : ($ptype . ':' . $from . ':' . $to . ':' . substr(md5($needle), 0, 8));
                $patterns[$found_index]['pattern_id'] = $pid;
                $patterns[$found_index]['promotion_reason'] = 'occurrence_count_reached_threshold';
                $flagged[] = hermes_flag_promotion_candidate($pid, 'occurrence_count_reached_threshold');
            }
        } else {
            $pid = $ptype . ':' . $from . ':' . $to . ':' . substr(md5($needle . ':' . $now), 0, 8);
            $patterns[] = array(
                'pattern_id' => $pid,
                'pattern_type' => $ptype,
                'ts' => $now,
                'from_actor_id' => $from,
                'to_actor_id' => $to,
                'summary' => $summary,
                'occurrence_count' => 1,
                'promotion_candidate' => false
            );
        }
    }
    $doc['patterns'] = $patterns;
    $json = json_encode($doc, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        return array('ok' => false, 'path' => $path, 'flagged' => $flagged);
    }
    $ok = (@file_put_contents($path, $json . "\n", LOCK_EX) !== false);
    return array(
        'ok' => $ok,
        'path' => str_replace('\\', '/', $path),
        'flagged' => $flagged
    );
}

function hermes_create_pending_task($message_id, $channel_id, $creator_actor_id, $assignee_actor_id, $task_body) {
    if ((int) $assignee_actor_id <= 0 || trim((string) $task_body) === '') {
        return false;
    }
    global $db, $prefix;
    $now = (int) timestamp_ymdhis::now();
    $task_id = (int) IdGenerator::generate();
    $row = array(
        'task_id' => $task_id,
        'message_id' => (int) $message_id,
        'channel_id' => (int) $channel_id,
        'assignee_actor_id' => (int) $assignee_actor_id,
        'creator_actor_id' => (int) $creator_actor_id,
        'task_body' => (string) $task_body,
        'status' => 'pending',
        'priority' => 1,
        'created_ymdhis' => $now,
        'updated_ymdhis' => $now,
        'completed_ymdhis' => null
    );
    try {
        $db->insert($prefix . 'dialog_pending_tasks', $row);
        return $task_id;
    } catch (Exception $e) {
        DialogMvpService::logDefect('P2-CHANNELS-TASK-CREATE-001', array(
            'message_id' => (int) $message_id,
            'channel_id' => (int) $channel_id,
            'assignee_actor_id' => (int) $assignee_actor_id,
            'exception' => $e->getMessage(),
            'reason' => 'db_insert_exception',
        ));
        return false;
    }
}

try {
    // 1. Resolve channel
    $chan_row = $db->fetchRow("SELECT channel_id FROM {$prefix}channels WHERE channel_key = :ck AND is_deleted = 0 LIMIT 1", array('ck' => $channel_name_key));
    if (!$chan_row && $channel_name_key !== 'lupopedia-development') {
        $chan_row = $db->fetchRow("SELECT channel_id, channel_key FROM {$prefix}channels WHERE channel_key = :dck AND is_deleted = 0 LIMIT 1", array('dck' => LUPO_DEFAULT_CHANNEL_KEY));
        if ($chan_row) { header('Location: ' . $base . '/channels/?channel=' . urlencode($chan_row['channel_key'])); exit; }
    }

    if ($chan_row) {
        $channel_id = (int) $chan_row['channel_id'];
    } else {
        $channel_id = LUPO_DEFAULT_CHANNEL_ID;
        $now = (int) timestamp_ymdhis::now();
        // PRD 82 (lupo-docs/prd/82_hermes_message_routing_memory_gateway.md): federation_node_id on the channel row must match the transcript path segment (hermes_write_to_transcript); default 0 everywhere.
        try {
            $db->insert($prefix . 'channels', array(
                'channel_id' => $channel_id,
                'federation_node_id' => 0,
                'created_by_actor_id' => 1,
                'owner_actor_id' => 1,
                'channel_key' => 'lupopedia-development',
                'channel_slug' => 'lupopedia-development',
                'channel_name' => 'Lupopedia Development',
                'channel_type' => 'chat_room',
                'visibility_status' => 'active',
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_deleted' => 0,
                'last_activity_ymdhis' => $now
            ));
        } catch (Exception $e) {
            DialogMvpService::logDefect('P1-CHANNELS-CHANNEL-FALLBACK-001', array(
                'channel_id' => $channel_id,
                'exception' => $e->getMessage(),
            ));
            $db->query("UPDATE {$prefix}channels SET is_deleted = 0, visibility_status = 'active', channel_key = :dck WHERE channel_id = :dcid", array('dck' => LUPO_DEFAULT_CHANNEL_KEY, 'dcid' => LUPO_DEFAULT_CHANNEL_ID));
        }
        if ($channel_name_key !== 'lupopedia-development') { header('Location: ' . $base . '/channels/?channel=lupopedia-development'); exit; }
    }

    // MANDATORY JOIN - Requirement 2: Auto-adds the current actor as a member
    if ($channel_id > 0 && $admin_active_actor_id > 0) {
        try {
            DialogMvpService::ensureChannelMembership($db, $admin_active_actor_id, $channel_id, $admin_active_actor_id, $admin_active_actor_display);
        } catch (Exception $e) {
            DialogMvpService::logDefect('P1-CHANNELS-MEMBERSHIP-001', array(
                'actor_id' => $admin_active_actor_id,
                'channel_id' => $channel_id,
                'exception' => $e->getMessage(),
            ));
        }
    }

    // 2. Resolve/Create Thread
    $t = $db->fetchRow("SELECT dialog_thread_id FROM {$prefix}dialog_threads WHERE thread_key = :tk AND channel_id = :cid AND is_deleted = 0 LIMIT 1", array('tk' => $today_thread_key, 'cid' => $channel_id));
    if ($t) {
        $thread_id = (int) $t['dialog_thread_id'];
    } else {
        $res = DialogMvpService::createDialogThread($db, $channel_id, $channel_name_key . ' / ' . $today_thread_key, $admin_active_actor_id ?: 1);
        $thread_id = (int) $res['thread_id'];
    }

    // 3. POST Handling
    if ($thread_id > 0 && $admin_active_actor_id > 0 && ($UNTRUSTED['server']['REQUEST_METHOD'] ?? '') === 'POST') {
        if (lupo_verify_csrf_token($_POST['csrf_token'] ?? '')) {
            $raw_msg = trim((string) ($_POST['message_text'] ?? ''));
            if ($raw_msg !== '') {
                $to_actor = (int) ($_POST['to_actor_id'] ?? 0);
                $action_mode = isset($_POST['action']) ? (string) $_POST['action'] : 'message';
                $ajax = !empty($_POST['is_ajax']);
                $task_to_actor_id = 0;

                if ($action_mode === 'task') {
                    $task_to_actor_id = (int) $admin_active_actor_id;
                    if ($current_auth_user_id <= 0) {
                        DialogMvpService::logDefect('P2-CHANNELS-TASK-ROUTE-001', array(
                            'reason' => 'task_auth_required',
                            'channel_id' => $channel_id,
                            'ajax' => $ajax ? 1 : 0,
                        ));
                        if ($ajax) {
                            header('Content-Type: application/json; charset=utf-8');
                            echo json_encode(array('ok' => false, 'error' => 'task_auth_required'));
                            exit;
                        }
                        $raw_msg = '';
                    } elseif ($current_auth_user_id > 0 && !DialogMvpService::isTaskAssigneeAuthorized($db, $channel_id, $current_auth_user_id, $task_to_actor_id, $task_scope_admin)) {
                        DialogMvpService::logDefect('P2-CHANNELS-TASK-ROUTE-001', array(
                            'reason' => 'task_assignee_unauthorized',
                            'channel_id' => $channel_id,
                            'auth_user_id' => $current_auth_user_id,
                            'task_to_actor_id' => $task_to_actor_id,
                            'ajax' => $ajax ? 1 : 0,
                        ));
                        if ($ajax) {
                            header('Content-Type: application/json; charset=utf-8');
                            echo json_encode(array('ok' => false, 'error' => 'task_assignee_unauthorized'));
                            exit;
                        }
                        $raw_msg = '';
                    } else {
                        $raw_msg = '[task] ' . $raw_msg;
                    }
                }

                if ($raw_msg !== '') {
                    $route = hermes_route_message(
                        $raw_msg,
                        $admin_active_actor_id,
                        $to_actor,
                        $channel_id,
                        $action_mode === 'task' ? $task_to_actor_id : 0,
                        $current_auth_user_id,
                        $task_scope_admin
                    );
                    if ($action_mode === 'message') {
                        $route['message_type'] = 'stdout';
                        $route['routing_provenance'] = 'hermes:stdout-ui';
                    }
                    $task_route_ok = true;
                    if ($action_mode === 'task') {
                        if (empty($route['task_target_actor_id']) || (isset($route['routing_provenance']) && $route['routing_provenance'] === 'hermes:error')) {
                            $task_route_ok = false;
                            DialogMvpService::logDefect('P2-CHANNELS-TASK-ROUTE-001', array(
                                'reason' => 'task_routing_failed',
                                'channel_id' => $channel_id,
                                'routing_provenance' => isset($route['routing_provenance']) ? (string) $route['routing_provenance'] : '',
                                'task_target_actor_id' => isset($route['task_target_actor_id']) ? (int) $route['task_target_actor_id'] : 0,
                                'ajax' => $ajax ? 1 : 0,
                            ));
                            if ($ajax) {
                                header('Content-Type: application/json; charset=utf-8');
                                echo json_encode(array('ok' => false, 'error' => 'task_routing_failed'));
                                exit;
                            }
                        }
                    }
                    if ($task_route_ok) {
                        if (!empty($route['task_target_actor_id'])) {
                            $to_actor = (int) $route['task_target_actor_id'];
                        }
                        $transcript_write = hermes_write_to_transcript(array(
                            'from_actor_id' => $admin_active_actor_id,
                            'to_actor_id' => $to_actor,
                            'message_text' => $raw_msg,
                            'message_type' => $route['message_type'],
                            'routing_provenance' => $route['routing_provenance']
                        ), $channel_id);
                        if (empty($transcript_write['ok'])) {
                            DialogMvpService::logDefect('P1-CHANNELS-TRANSCRIPT-001', array(
                                'path' => isset($transcript_write['path']) ? $transcript_write['path'] : '',
                                'channel_id' => $channel_id,
                            ));
                        }
                        $staging_write = hermes_write_to_staging_toon($route['patterns'], $channel_id);
                        if (empty($staging_write['ok'])) {
                            DialogMvpService::logDefect('P2-CHANNELS-STAGING-001', array(
                                'path' => isset($staging_write['path']) ? $staging_write['path'] : '',
                                'channel_id' => $channel_id,
                            ));
                        }
                        $meta = array(
                            'routing_provenance' => $route['routing_provenance'],
                            'transcript_path' => isset($transcript_write['path']) ? $transcript_write['path'] : '',
                            'staging_toon_path' => isset($staging_write['path']) ? $staging_write['path'] : ''
                        );
                        $created = DialogMvpService::createDialogMessage(
                            $db,
                            $thread_id,
                            $admin_active_actor_id,
                            $raw_msg,
                            $route['message_type'],
                            $to_actor,
                            '666666',
                            json_encode($meta)
                        );
                        if (!empty($route['task_target_actor_id']) && !empty($created['message_id'])) {
                            $hermes_task_id = hermes_create_pending_task(
                                (int) $created['message_id'],
                                $channel_id,
                                $admin_active_actor_id,
                                (int) $route['task_target_actor_id'],
                                !empty($route['task_body']) ? $route['task_body'] : $raw_msg
                            );
                            if ($hermes_task_id === false) {
                                DialogMvpService::logDefect('P2-CHANNELS-TASK-CREATE-001', array(
                                    'message_id' => (int) $created['message_id'],
                                    'channel_id' => $channel_id,
                                    'assignee_actor_id' => (int) $route['task_target_actor_id'],
                                ));
                            }
                        }
                        if ($ajax) {
                            header('Content-Type: application/json; charset=utf-8');
                            echo json_encode(array(
                                'ok' => true,
                                'message_type' => $route['message_type'],
                                'routing_provenance' => $route['routing_provenance']
                            ));
                            exit;
                        }
                        header('Location: ' . $base . '/channels/?channel=' . urlencode($channel_name_key));
                        exit;
                    }
                }
            }
        }
    }

    // 4. Load initial messages (BASE GROUND)
    $messages = $db->fetchAll(
        "SELECT m.dialog_message_id, m.from_actor_id, m.to_actor_id, m.message_text, m.message_type, m.created_ymdhis,
                COALESCE(a.name, a.actor_name, 'UNKNOWN') AS actor_display,
                COALESCE(t.bg_color, 'fefdcd') AS msg_bg,
                COALESCE(t.text_color, '426446') AS msg_fc,
                COALESCE(t.alt_text_color, '040662') AS msg_ac
         FROM {$prefix}dialog_messages m
         LEFT JOIN {$prefix}actors a ON a.actor_id = m.from_actor_id
         LEFT JOIN {$prefix}dialog_threads t ON t.dialog_thread_id = m.dialog_thread_id
         WHERE m.channel_id = :cid AND m.is_deleted = 0
           AND (m.to_actor_id IS NULL
                OR m.to_actor_id = :cur_to
                OR m.from_actor_id = :cur_from)
         ORDER BY m.created_ymdhis ASC LIMIT 200",
        array(
            'cid' => $channel_id,
            'cur_to' => (int) $admin_active_actor_id,
            'cur_from' => (int) $admin_active_actor_id,
        )
    );
    // Mockup-only: prepend feed lines and align with PRD 02 visitor message-count (N=1 = wants chat).
    $min_msg_ts = (int) $__packed_utc;
    foreach ($messages as $_m) {
        $min_msg_ts = min($min_msg_ts, (int) (isset($_m['created_ymdhis']) ? $_m['created_ymdhis'] : $__packed_utc));
    }
    $channels_mockup_visitor_demo_messages = array(
        array(
            'dialog_message_id' => 0,
            'from_actor_id' => 0,
            'to_actor_id' => null,
            'message_text' => '[MOCKUP] Pending accept: visitor session demo-wait-001 has N=1 row in lupo_dialog_messages (one endpoint hit). Sidebar row GUEST-RIVER shows [Accept Chat].',
            'message_type' => 'system',
            'created_ymdhis' => $min_msg_ts - 2,
            'actor_display' => 'SYSTEM',
            'msg_bg' => 'fefdcd',
            'msg_fc' => '426446',
            'msg_ac' => '040662',
        ),
        array(
            'dialog_message_id' => 0,
            'from_actor_id' => 0,
            'to_actor_id' => null,
            'message_text' => 'Hello -- I need live help with checkout totals. Please accept this chat.',
            'message_type' => '',
            'created_ymdhis' => $min_msg_ts - 1,
            'actor_display' => 'GUEST-RIVER',
            'msg_bg' => 'fefdcd',
            'msg_fc' => '426446',
            'msg_ac' => '040662',
        ),
    );
    $messages = array_merge($channels_mockup_visitor_demo_messages, $messages);
    $last_time_cursor = 0;
    foreach ($messages as $msg) {
        $last_time_cursor = max($last_time_cursor, (int) (isset($msg['created_ymdhis']) ? $msg['created_ymdhis'] : 0));
    }

    // Requirement 3: Members list updates correctly (fetched after mandatory join)
    $actors = DialogMvpService::getChannelMembers($db, $channel_id, 500);

    // Requirement 1: channel dropdown shows all channels
    $all_channels = DialogMvpService::getAllChannels($db, 1000);
    
    $found_curr = false;
    foreach ($all_channels as $ch) { if ((int)$ch['channel_id'] === $channel_id) { $found_curr = true; break; } }
    if (!$found_curr && $channel_id > 0) {
        $curr_ch = $db->fetchRow("SELECT channel_id, channel_key, channel_name FROM {$prefix}channels WHERE channel_id = :cid", array('cid' => $channel_id));
        if ($curr_ch) { array_unshift($all_channels, $curr_ch); }
    }

} catch (Exception $e) {
    $db_error = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}

ob_start();
?>
<style>
.admin-sidebar { display: none !important; }
.admin-body { background: #f5f5dc; }
.admin-main h1 { display: none; }
.channel-live-wrapper { display: grid; grid-template-columns: 1fr 280px; grid-template-rows: minmax(0, 1fr); gap: 0px; height: calc(100vh - 174px); font-family: monospace; }
.channel-main-column { grid-column: 1 / 2; grid-row: 1 / 2; min-height: 0; display: flex; flex-direction: column; background: #111111; border: 1px solid #333333; }
.channel-main-area { flex: 1; min-height: 0; display: flex; flex-direction: column; }
.channel-nine-grid { flex: 1; min-height: 0; display: grid; grid-template-columns: 10px 1fr 10px; grid-template-rows: 10px 1fr 10px; gap: 0; background: #1a1b1e; }
.ch9 { background: #222222; border: 1px solid #333333; box-sizing: border-box; }
.ch9-tl { border-radius: 2px 0 0 0; }
.ch9-t { border-left: none; border-right: none; }
.ch9-tr { border-radius: 0 2px 0 0; }
.ch9-ml { border-top: none; border-bottom: none; }
.ch9-mid { border: none; min-height: 0; display: flex; flex-direction: column; overflow: hidden; background: #000000; }
.ch9-mr { border-top: none; border-bottom: none; }
.ch9-bl { border-radius: 0 0 0 2px; }
.ch9-b { border-left: none; border-right: none; }
.ch9-br { border-radius: 0 0 2px 0; }
.channel-feed { flex: 1; min-height: 0; overflow-y: auto; overflow-x: hidden; padding: 10px; display: flex; flex-direction: column; gap: 2px; background: #000000; }
.channel-feed .chat-message.chat-line { width: 100%; max-width: 100%; box-sizing: border-box; margin: 0; align-self: stretch; }
.chat-message.chat-line { display: flex; flex-direction: row; align-items: flex-start; padding: 4px 8px; font-size: 13px; line-height: 1.45; gap: 8px; box-sizing: border-box; }
.msg-line-core { display: flex; flex-direction: row; align-items: flex-start; flex: 1; min-width: 0; }
.msg-time { color: #888888; margin-right: 8px; flex-shrink: 0; font-variant-numeric: tabular-nums; }
.msg-sender { font-weight: bold; margin-right: 8px; flex-shrink: 0; }
.msg-content { flex: 1; white-space: pre-wrap; word-break: break-word; min-width: 0; }
.msg-route-btn { flex-shrink: 0; align-self: flex-start; font-family: monospace; font-size: 11px; cursor: pointer; background-color: #2a2a2a; border: 1px solid #444444; color: #cccccc; padding: 1px 4px; line-height: 1.3; }
.msg-route-btn:hover { filter: brightness(1.12); }
#lupo-route-modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 999; }
#lupo-route-modal { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #1a1b1e; border: 1px solid #444444; padding: 20px; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.5); min-width: 320px; max-width: 90vw; font-family: monospace; color: #e0e0e0; }
#lupo-route-modal h3 { margin: 0 0 12px 0; font-size: 14px; }
#lupo-route-modal label { display: block; font-size: 12px; margin-bottom: 4px; color: #aaaaaa; }
#lupo-route-modal select, #lupo-route-modal textarea { width: 100%; box-sizing: border-box; margin-bottom: 12px; font-family: monospace; font-size: 12px; background: #111111; color: #dddddd; border: 1px solid #444444; padding: 6px; }
#lupo-route-modal textarea { min-height: 72px; resize: vertical; }
#lupo-route-modal .lupo-route-modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px; }
#lupo-route-modal .lupo-route-modal-actions button { font-family: monospace; padding: 8px 14px; cursor: pointer; border: none; font-weight: bold; }
#lupo-route-modal-btn-cancel { background: #333333; color: #eeeeee; }
#lupo-route-modal-btn-send { background: #555555; color: #ffffff; }
#lupo-route-modal-status { font-size: 11px; color: #ffaa00; min-height: 1.2em; margin-bottom: 8px; }
.chat-line-thoth { font-weight: bold; }
.chat-line-thoth .msg-time { color: #ffaaaa; }
.chat-line-task:not(.chat-line-directed) .msg-content { font-weight: bold; border-left: 2px solid #ffbf00; padding-left: 6px; background-color: rgba(255, 191, 0, 0.12); }
.msg-slug-cursor .msg-sender { color: #ffd700; }
.msg-slug-auggie .msg-sender { color: #1e90ff; }
.msg-slug-gemini .msg-sender { color: #32cd32; }
.msg-slug-cascade .msg-sender { color: #8a2be2; }
.msg-slug-thoth .msg-sender { color: #ffd700; }
.msg-slug-lilith .msg-sender { color: #cccccc; }
.msg-slug-rose .msg-sender { color: #ffb6c1; }
.msg-slug-default .msg-sender { color: #e0e0e0; }
.chat-message.chat-line.chat-line-directed .msg-time,
.chat-message.chat-line.chat-line-directed .msg-sender,
.chat-message.chat-line.chat-line-directed .msg-content {
    color: inherit !important;
}
.last-active-message { box-shadow: inset 0 0 0 2px var(--active-actor-color, #1E88E5); background-color: rgba(30,136,229,0.18) !important; }
.channel-bottom-area { flex-shrink: 0; display: flex; flex-direction: column; border-top: 1px solid #333333; background: #1a1b1e; }
.active-target-bar { padding: 6px 10px; font-size: 12px; font-weight: bold; letter-spacing: 0.02em; color: #e0e0e0; border-bottom: 1px solid #444444; background: #1a1b1e; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 8px 12px; }
.active-target-bar .active-target-bracket { color: #ffffff; }
.active-target-bar-main { flex: 1; min-width: 0; }
.active-target-bar-actions { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
.target-mode-row { display: flex; flex-wrap: wrap; align-items: center; gap: 8px 12px; }
.target-mode-label { color: #e0e0e0; font-weight: bold; }
.target-mode-select { font-family: monospace; font-size: 12px; padding: 4px 8px; background: #111111; color: #e0e0e0; border: 1px solid #555555; }
.actor-tab-bar.is-hidden { display: none !important; }
.lupo-prompt-bar-btn { font-family: monospace; font-size: 11px; font-weight: bold; padding: 4px 8px; cursor: pointer; background: #2a2a2a; color: #cccccc; border: 1px solid #555555; }
.lupo-prompt-bar-btn:hover { border-color: #777777; color: #ffffff; }
.lupo-pl-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.55); z-index: 1005; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box; }
.lupo-pl-overlay.lupo-pl-open { display: flex; }
.lupo-pl-dialog { background: #1a1b1e; border: 1px solid #444444; max-width: 560px; width: 100%; max-height: 85vh; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 4px 16px rgba(0,0,0,0.6); font-family: monospace; color: #e0e0e0; }
.lupo-pl-dialog-hd { padding: 10px 12px; border-bottom: 1px solid #333333; display: flex; justify-content: space-between; align-items: center; font-size: 13px; }
.lupo-pl-dialog-bd { padding: 10px 12px; overflow-y: auto; flex: 1; min-height: 0; font-size: 12px; }
.lupo-pl-dialog-ft { padding: 10px 12px; border-top: 1px solid #333333; display: flex; flex-wrap: wrap; gap: 8px; justify-content: flex-end; }
.lupo-pl-list-row { border: 1px solid #333333; margin-bottom: 8px; padding: 8px; background: #111111; }
.lupo-pl-list-row .lupo-pl-row-title { font-weight: bold; color: #f5f5f5; margin-bottom: 4px; }
.lupo-pl-list-row .lupo-pl-row-meta { font-size: 10px; color: #888888; margin-bottom: 6px; }
.lupo-pl-list-row .lupo-pl-row-preview { color: #bbbbbb; white-space: pre-wrap; word-break: break-word; margin-bottom: 8px; max-height: 4.5em; overflow: hidden; }
.lupo-pl-row-actions { display: flex; flex-wrap: wrap; gap: 6px; }
.lupo-pl-row-actions button { font-family: monospace; font-size: 11px; padding: 4px 8px; cursor: pointer; background: #333333; color: #eeeeee; border: 1px solid #555555; }
.lupo-pl-row-actions button.lupo-pl-primary { background: #4a4a2a; border-color: #6a6a3a; color: #ffffcc; }
.lupo-pl-field { margin-bottom: 10px; }
.lupo-pl-field label { display: block; font-size: 11px; color: #999999; margin-bottom: 4px; }
.lupo-pl-field input, .lupo-pl-field select, .lupo-pl-field textarea { width: 100%; box-sizing: border-box; font-family: monospace; font-size: 12px; background: #111111; color: #dddddd; border: 1px solid #444444; padding: 6px; }
.lupo-pl-preview-pre { white-space: pre-wrap; word-break: break-word; max-height: 60vh; overflow-y: auto; font-size: 12px; color: #dddddd; }
.lupo-pl-status-msg { font-size: 11px; color: #ffaa00; margin-bottom: 8px; min-height: 1.2em; }
.actor-tab-bar { background: #1a1b1e; border-bottom: 1px solid #333333; padding: 0; display: flex; flex-wrap: wrap; align-items: stretch; gap: 0; }
.actor-tab { font-family: monospace; font-size: 13px; font-weight: bold; margin: 0; padding: 6px 12px; cursor: pointer; border: none; border-right: 1px solid #333333; border-top: 2px solid transparent; background: transparent; color: #c8c8c8; line-height: 1.2; }
.actor-tab:hover { filter: brightness(1.08); }
.actor-tab.observer-tab { background: #000000; color: #ffffff; border-top-color: #444444; }
.actor-tab.observer-tab.active { background: #1a1a1a; color: #ffffff; border-top-color: #666666; }
.channel-controls { padding: 10px; background: #1a1b1e; }
.lupo-compose-row { display: flex; flex-direction: row; align-items: stretch; gap: 10px; flex-wrap: wrap; }
.lupo-compose-row textarea {
    flex: 1 1 auto;
    min-width: 220px;
    max-width: 640px;
    width: auto;
    height: 60px;
    font-family: monospace;
    font-size: 13px;
    border: 1px solid var(--input-border, #444);
    background: var(--input-bg, rgba(0,0,0,0.35));
    color: #e0e0e0;
    padding: 8px;
    resize: none;
    box-sizing: border-box;
    transition: background-color 0.2s ease, border-color 0.2s ease;
}
.lupo-compose-actions { display: flex; flex-direction: column; gap: 8px; justify-content: center; flex-shrink: 0; }
.send-message-btn { font-family: monospace; padding: 8px 16px; background: #555555; color: #fff; border: none; cursor: pointer; font-weight: bold; white-space: nowrap; }
.send-task-btn { font-family: monospace; padding: 8px 16px; background: #32cd32; color: #000; border: none; cursor: pointer; font-weight: bold; white-space: nowrap; }
/* Right column: channel picker, members, recent files / tasks (dark sidecar) */
.channel-sidebar.channel-sidecar,
.channel-sidebar {
    grid-column: 2 / 3;
    grid-row: 1 / 2;
    background: #000000;
    color: #e0e0e0;
    padding: 10px;
    border: 1px solid #333333;
    overflow-y: auto;
    font-size: 12px;
}
.channel-sidebar select {
    width: 100%;
    box-sizing: border-box;
    font-family: monospace;
    font-size: 12px;
    background: #111111;
    color: #e0e0e0;
    border: 1px solid #444444;
    padding: 4px 6px;
}
.channel-sidebar .sidebar-head {
    font-size: 10px;
    text-transform: uppercase;
    color: #9e9e9e;
    margin: 10px 0 5px;
    border-bottom: 1px solid #444444;
    padding-bottom: 2px;
}
.channel-sidebar .actor-row {
    padding: 3px 0;
    border-bottom: 1px solid #2a2a2a;
    color: #e8e8e8;
}
.channel-sidebar .status-dot { display: inline-block; width: 8px; height: 8px; background: #2e7d32; border-radius: 50%; margin-right: 5px; }
.channel-sidebar .status-dot-agent { background: #9575cd; }
.channel-sidebar .recent-files,
.channel-sidebar .recent-tasks { font-size: 11px; color: #b0b0b0; }
.channel-sidebar .recent-item {
    padding: 3px 0;
    border-bottom: 1px solid #2a2a2a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #d0d0d0;
}
.channel-sidebar .visitor-sidecar-note { font-size: 10px; color: #888888; margin: 0 0 8px; line-height: 1.3; }
.channel-sidebar details.visitor-browse-details {
    margin: 0 0 10px;
    border: 1px solid #333333;
    border-radius: 2px;
}
.channel-sidebar summary.visitor-browse-summary {
    font-size: 10px;
    text-transform: uppercase;
    color: #9e9e9e;
    padding: 6px 8px;
    border-bottom: 1px solid #333333;
    cursor: pointer;
    user-select: none;
    list-style: none;
}
.channel-sidebar summary.visitor-browse-summary::-webkit-details-marker { display: none; }
.channel-sidebar details.visitor-browse-details[open] summary.visitor-browse-summary { border-bottom-color: #444444; }
/* Light header bar: match sidecar-accordion-light-summary (Browsing Visitors, Users only). */
.channel-sidebar details.visitor-browse-details.visitor-browse-details--light-header summary.visitor-browse-summary {
    font-size: 11px;
    text-transform: uppercase;
    font-weight: bold;
    letter-spacing: 0.02em;
    color: #222222;
    background: #e0e0e0;
    padding: 8px 10px;
    border-bottom: 1px solid #c8c8c8;
}
.channel-sidebar details.visitor-browse-details.visitor-browse-details--light-header:not([open]) summary.visitor-browse-summary {
    border-bottom: none;
}
.channel-sidebar details.visitor-browse-details.visitor-browse-details--light-header[open] summary.visitor-browse-summary {
    border-bottom: 1px solid #bbbbbb;
}
.channel-sidebar .visitor-browse-list { padding: 2px 6px 6px; }
.channel-sidebar .visitor-browse-line {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    padding: 5px 0;
    border-bottom: 1px solid #2a2a2a;
    font-size: 11px;
    font-family: monospace;
}
.channel-sidebar .visitor-browse-line:last-child { border-bottom: none; }
.channel-sidebar .visitor-browse-ip { color: #7a9e7a; flex: 0 0 auto; }
.channel-sidebar .visitor-browse-label { color: #e8e8e8; flex: 1 1 auto; min-width: 72px; }
.channel-sidebar .visitor-browse-line button {
    font-family: monospace;
    font-size: 10px;
    padding: 3px 7px;
    cursor: pointer;
    border: 1px solid #555555;
    background: #1e1e1e;
    color: #e0e0e0;
    flex: 0 0 auto;
}
.channel-sidebar .visitor-browse-line button:hover:not(:disabled) { filter: brightness(1.12); }
.channel-sidebar .visitor-btn-invited {
    opacity: 0.7;
    cursor: default;
    border-color: #666666;
    color: #999999;
}
.channel-sidebar .visitor-wants-row.is-pending-wants {
    background: #221a00;
    margin: 0 0 10px;
    padding: 6px 8px;
    border: 1px solid #664400;
    border-radius: 2px;
}
.channel-sidebar .visitor-wants-row .visitor-browse-line { border-bottom: none; padding: 2px 0; }
.channel-sidebar .user-mock-line {
    justify-content: space-between;
    gap: 12px;
}
.channel-sidebar .user-mock-line .visitor-browse-label { font-weight: bold; }
.channel-sidebar details.sidecar-accordion-light {
    margin: 0 0 10px;
    border: 1px solid #555555;
    border-radius: 2px;
    overflow: hidden;
}
.channel-sidebar summary.sidecar-accordion-light-summary {
    font-size: 11px;
    text-transform: uppercase;
    font-weight: bold;
    letter-spacing: 0.02em;
    color: #222222;
    background: #e0e0e0;
    padding: 8px 10px;
    cursor: pointer;
    user-select: none;
    list-style: none;
    border-bottom: 1px solid #c8c8c8;
}
.channel-sidebar details.sidecar-accordion-light:not([open]) summary.sidecar-accordion-light-summary {
    border-bottom: none;
}
.channel-sidebar summary.sidecar-accordion-light-summary::-webkit-details-marker { display: none; }
.channel-sidebar details.sidecar-accordion-light[open] summary.sidecar-accordion-light-summary {
    border-bottom: 1px solid #bbbbbb;
}
.channel-sidebar .sidecar-accordion-light-body {
    padding: 4px 6px 8px;
    background: #000000;
}
.channel-sidebar .agent-mock-line {
    justify-content: flex-start;
    gap: 10px;
}
.channel-sidebar .agent-mock-status {
    color: #9ccc9c;
    font-size: 10px;
    min-width: 64px;
    flex: 0 0 auto;
}
.channel-sidebar .agent-mock-line .visitor-browse-label {
    font-weight: bold;
    flex: 1 1 auto;
    min-width: 100px;
}
.channel-sidebar .user-mock-invited-label {
    font-family: monospace;
    font-size: 10px;
    padding: 3px 7px;
    color: #999999;
    border: 1px solid #666666;
    background: #1a1a1a;
    flex: 0 0 auto;
}
/* Dark scrollbar track (feed, sidecar, prompt-library modals) */
.channel-feed,
.channel-sidebar,
.lupo-pl-dialog-bd,
.lupo-pl-preview-pre {
    scrollbar-width: thin;
    scrollbar-color: #4a4a4a #141414;
}
.channel-feed::-webkit-scrollbar,
.channel-sidebar::-webkit-scrollbar,
.lupo-pl-dialog-bd::-webkit-scrollbar,
.lupo-pl-preview-pre::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}
.channel-feed::-webkit-scrollbar-track,
.channel-sidebar::-webkit-scrollbar-track,
.lupo-pl-dialog-bd::-webkit-scrollbar-track,
.lupo-pl-preview-pre::-webkit-scrollbar-track {
    background-color: #141414;
}
.channel-feed::-webkit-scrollbar-thumb,
.channel-sidebar::-webkit-scrollbar-thumb,
.lupo-pl-dialog-bd::-webkit-scrollbar-thumb,
.lupo-pl-preview-pre::-webkit-scrollbar-thumb {
    background-color: #4a4a4a;
    border-radius: 5px;
    border: 2px solid #141414;
}
.channel-feed::-webkit-scrollbar-thumb:hover,
.channel-sidebar::-webkit-scrollbar-thumb:hover,
.lupo-pl-dialog-bd::-webkit-scrollbar-thumb:hover,
.lupo-pl-preview-pre::-webkit-scrollbar-thumb:hover {
    background-color: #5a5a5a;
}
</style>

<div style="background:#000; color:#fff; padding:5px 10px; font-family:monospace; font-size:12px; border-bottom:1px solid #444; display:flex; justify-content:space-between;">
    <div>ACTING AS: <strong><?= strtoupper(htmlspecialchars($admin_active_actor_display, ENT_QUOTES, 'UTF-8')) ?></strong> (ID: <?= $admin_active_actor_id ?>)</div>
    <div id="transport-status">Mode: Base</div>
</div>
<div style="background:#3d2a00;color:#ffcc66;padding:5px 10px;font-family:monospace;font-size:11px;border-bottom:1px solid #664400;">MOCKUP FILE: lupo-templates/channels/index.php -- target selector; Visitors + collapsible Users (Invite/Invited) + Agents dropdown. Layout review only.</div>

<div class="channel-live-wrapper">
    <div class="channel-main-column">
        <div class="channel-main-area">
            <div class="channel-nine-grid" id="channel-nine-frame" aria-label="Channel feed frame">
                <div class="ch9 ch9-tl" aria-hidden="true"></div>
                <div class="ch9 ch9-t" aria-hidden="true"></div>
                <div class="ch9 ch9-tr" aria-hidden="true"></div>
                <div class="ch9 ch9-ml" aria-hidden="true"></div>
                <div class="ch9 ch9-mid">
                    <div class="channel-feed" id="lupo-feed">
                        <?php foreach ($messages as $msg): ?>
                        <?= lupo_channel_chat_row_html($msg, $admin_active_actor_id) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="ch9 ch9-mr" aria-hidden="true"></div>
                <div class="ch9 ch9-bl" aria-hidden="true"></div>
                <div class="ch9 ch9-b" aria-hidden="true"></div>
                <div class="ch9 ch9-br" aria-hidden="true"></div>
            </div>
            <div class="channel-bottom-area">
                <div class="active-target-bar sending-bar" id="active-target-bar">
                    <span class="active-target-bar-main target-mode-row">
                        <span class="target-mode-label">Send message to:</span>
                        <select id="target-mode-select" class="target-mode-select" aria-label="Outbound target class">
                            <option value="user" selected>User</option>
                            <option value="visitor">Visitor</option>
                            <option value="agent">Agent</option>
                        </select>
                        <strong id="active-target-summary" class="active-target-bracket">[USER] WOLFIE</strong>
                    </span>
                    <span class="active-target-bar-actions">
                        <button type="button" class="lupo-prompt-bar-btn" id="lupo-prompt-library-open" title="Browse saved prompt artifacts">[Prompt Library]</button>
                        <button type="button" class="lupo-prompt-bar-btn" id="lupo-prompt-save-open" title="Save composer text as a prompt artifact">[Save Prompt]</button>
                    </span>
                </div>
                <div id="target-tab-bars">
                    <div class="actor-tab-bar actor-tab-bar-user" id="target-tabs-user" role="tablist" aria-label="Human actors with auth user (mock list)">
                        <button type="button" role="tab" class="actor-tab active" data-actor-id="1" data-actor-name="WOLFIE" data-actor-color="#8d6e63">[WOLFIE]</button>
                        <button type="button" role="tab" class="actor-tab" data-actor-id="10001" data-actor-name="HELEN" data-actor-color="#4a90d9">[HELEN]</button>
                    </div>
                    <div class="actor-tab-bar actor-tab-bar-visitor is-hidden" id="target-tabs-visitor" role="tablist" aria-label="Active visitor sessions (mock)">
                        <button type="button" role="tab" class="actor-tab active visitor-tab" data-session-id="demo-visitor-sess-001" data-actor-name="ANON-PEBBLE" data-actor-color="#6d6d6d">[PEBBLE]</button>
                        <button type="button" role="tab" class="actor-tab visitor-tab" data-session-id="demo-wait-001" data-actor-name="GUEST-RIVER" data-actor-color="#ff9800">[RIVER]</button>
                        <button type="button" role="tab" class="actor-tab visitor-tab" data-session-id="demo-visitor-sess-002" data-actor-name="VISITOR-B" data-actor-color="#1565c0">[VIS-B]</button>
                    </div>
                    <div class="actor-tab-bar actor-tab-bar-agent is-hidden" id="target-tabs-agent" role="tablist" aria-label="Agent actors without auth user session (mock list)">
                        <button type="button" role="tab" class="actor-tab active" data-actor-id="102" data-actor-name="CURSOR" data-actor-color="#ffd700">[CURSOR]</button>
                        <button type="button" role="tab" class="actor-tab" data-actor-id="111" data-actor-name="GEMINI" data-actor-color="#32cd32">[GEMINI]</button>
                        <button type="button" role="tab" class="actor-tab" data-actor-id="117" data-actor-name="CASCADE" data-actor-color="#8a2be2">[CASCADE]</button>
                        <button type="button" role="tab" class="actor-tab observer-tab" data-actor-id="2" data-actor-name="LILITH" data-actor-color="#cccccc">[LILITH]</button>
                    </div>
                </div>
                <div class="channel-controls" id="channel-input-area">
                    <form id="lupo-compose" method="post">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(lupo_get_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="active_target_actor_name" id="active_target_actor_name" value="WOLFIE">
                        <input type="hidden" name="target_mode" id="active_target_mode" value="user">
                        <input type="hidden" name="to_actor_id" id="compose_to_actor_id" value="1">
                        <input type="hidden" name="to_session_id" id="compose_to_session_id" value="">
                        <div class="lupo-compose-row">
                            <textarea name="message_text" placeholder="Enter message... (Enter to send, Shift+Enter for newline)"></textarea>
                            <div class="lupo-compose-actions">
                                <button type="submit" name="action" value="message" class="send-message-btn">Send Message</button>
                                <button type="submit" name="action" value="task" class="send-task-btn"<?= $admin_active_actor_id <= 0 ? ' disabled="disabled"' : '' ?> title="Task is assigned to your current actor (ACTING AS)">Send Task</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="channel-sidebar channel-sidecar" id="channel-sidecar">
        <div class="sidebar-head">Channel</div>
        <select onchange="location.href='?channel='+this.value" style="width:100%; font-family:monospace;">
            <?php foreach ($all_channels as $ch): ?>
            <option value="<?= htmlspecialchars($ch['channel_key'], ENT_QUOTES, 'UTF-8') ?>" <?= $ch['channel_key'] === $channel_name_key ? 'selected' : '' ?>><?= htmlspecialchars($ch['channel_name'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
        <div class="sidebar-head">Visitors (mock)</div>
        <p class="visitor-sidecar-note">Department union (mock copy). Chatting visitors use bottom visitor tabs only, not this list. Collapse uses HTML <code>details</code> (no extra JS).</p>
        <details class="visitor-browse-details visitor-browse-details--light-header" open>
            <summary class="visitor-browse-summary">Browsing Visitors (2)</summary>
            <div class="visitor-browse-list">
                <div class="visitor-browse-line">
                    <span class="visitor-browse-ip">[192.0.2.10]</span>
                    <span class="visitor-browse-label">ANON-PEBBLE</span>
                    <button type="button" title="Mockup only">[Invite]</button>
                </div>
                <div class="visitor-browse-line">
                    <span class="visitor-browse-ip">[203.0.113.5]</span>
                    <span class="visitor-browse-label">Guest</span>
                    <button type="button" class="visitor-btn-invited" disabled title="Mockup: visitor_status invited">[Invited]</button>
                </div>
            </div>
        </details>
        <div class="sidebar-head">Wants chat</div>
        <div class="visitor-wants-row is-pending-wants">
            <div class="visitor-browse-line">
                <span class="visitor-browse-ip">[198.51.100.7]</span>
                <span class="visitor-browse-label">GUEST-RIVER</span>
                <button type="button" class="mock-visitor-accept-btn" data-session-id="demo-wait-001" title="Switches composer to Visitor mode and selects this session">[Accept Chat]</button>
            </div>
        </div>
        <details class="visitor-browse-details visitor-browse-details--light-header" open>
            <summary class="visitor-browse-summary">Users (2)</summary>
            <p class="visitor-sidecar-note" style="margin-top:6px;">Mock only: actors with an auth user on this actor, and not chatting with you (<strong><?= htmlspecialchars($admin_active_actor_display, ENT_QUOTES, 'UTF-8') ?></strong>). Dialog messages between you and that actor: zero = Invite button; exactly one = Invited (text); more than one = hidden (chatting).</p>
            <div class="visitor-browse-list">
                <div class="visitor-browse-line user-mock-line">
                    <span class="visitor-browse-label">HELEN</span>
                    <button type="button" title="Mockup only">Invite</button>
                </div>
                <div class="visitor-browse-line user-mock-line">
                    <span class="visitor-browse-label">ROSE</span>
                    <span class="user-mock-invited-label" title="Mockup: exactly one dialog message between operator and this actor">Invited</span>
                </div>
            </div>
        </details>
        <details class="sidecar-accordion-light" open>
            <summary class="sidecar-accordion-light-summary">Agents</summary>
            <div class="sidecar-accordion-light-body">
                <div class="visitor-browse-list">
                    <div class="visitor-browse-line agent-mock-line">
                        <span class="visitor-browse-label">CURSOR</span>
                        <span class="agent-mock-status">Idle</span>
                        <button type="button" title="Mockup only">Add to Chat</button>
                    </div>
                    <div class="visitor-browse-line agent-mock-line">
                        <span class="visitor-browse-label">GEMINI</span>
                        <span class="agent-mock-status">Busy</span>
                        <button type="button" title="Mockup only">Add to Chat</button>
                    </div>
                    <div class="visitor-browse-line agent-mock-line">
                        <span class="visitor-browse-label">CASCADE</span>
                        <span class="agent-mock-status">Idle</span>
                        <button type="button" title="Mockup only">Add to Chat</button>
                    </div>
                    <div class="visitor-browse-line agent-mock-line">
                        <span class="visitor-browse-label">CLAUDE CODE</span>
                        <span class="agent-mock-status">Idle</span>
                        <button type="button" title="Mockup only">Add to Chat</button>
                    </div>
                    <div class="visitor-browse-line agent-mock-line">
                        <span class="visitor-browse-label">LILITH</span>
                        <span class="agent-mock-status">Monitor</span>
                        <button type="button" title="Mockup only">Add to Chat</button>
                    </div>
                    <div class="visitor-browse-line agent-mock-line">
                        <span class="visitor-browse-label">THOTH</span>
                        <span class="agent-mock-status">Recording</span>
                        <button type="button" title="Mockup only">Add to Chat</button>
                    </div>
                    <div class="visitor-browse-line agent-mock-line">
                        <span class="visitor-browse-label">WINDSURF IDE</span>
                        <span class="agent-mock-status">Idle</span>
                        <button type="button" title="Mockup only">Add to Chat</button>
                    </div>
                    <div class="visitor-browse-line agent-mock-line">
                        <span class="visitor-browse-label">ANTIGRAVITY IDE</span>
                        <span class="agent-mock-status">Idle</span>
                        <button type="button" title="Mockup only">Add to Chat</button>
                    </div>
                </div>
            </div>
        </details>
        <details class="sidecar-accordion-light">
            <summary class="sidecar-accordion-light-summary">Recent Files</summary>
            <div class="sidecar-accordion-light-body">
                <div id="recent-files-list" class="recent-files"></div>
            </div>
        </details>
        <details class="sidecar-accordion-light">
            <summary class="sidecar-accordion-light-summary">Recent Tasks</summary>
            <div class="sidecar-accordion-light-body">
                <div id="recent-tasks-list" class="recent-tasks"></div>
            </div>
        </details>
    </div>
</div>

<div id="lupo-route-modal-overlay" aria-hidden="true"></div>
<div id="lupo-route-modal" role="dialog" aria-modal="true" aria-labelledby="lupo-route-modal-title">
    <h3 id="lupo-route-modal-title">Route Message</h3>
    <div id="lupo-route-modal-status"></div>
    <div>
        <label for="lupo-route-channel">Destination Channel</label>
        <select id="lupo-route-channel">
            <?php foreach ($all_channels as $rch): ?>
            <option value="<?= htmlspecialchars($rch['channel_key'], ENT_QUOTES, 'UTF-8') ?>"<?= ($rch['channel_key'] === $channel_name_key) ? ' selected' : '' ?>><?= htmlspecialchars($rch['channel_name'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label for="lupo-route-actor">Destination Actor (optional)</label>
        <select id="lupo-route-actor"><option value="0">Broadcast</option></select>
    </div>
    <div>
        <label for="lupo-route-explain">Routing explanation (for destination channel)</label>
        <textarea id="lupo-route-explain" placeholder="e.g., Gemini updated the chat mockup, here is what she did..."></textarea>
    </div>
    <div class="lupo-route-modal-actions">
        <button type="button" id="lupo-route-modal-btn-cancel">Cancel</button>
        <button type="button" id="lupo-route-modal-btn-send">Confirm Route</button>
    </div>
</div>

<div id="lupo-pl-overlay" class="lupo-pl-overlay" aria-hidden="true">
    <div id="lupo-pl-library-wrap" class="lupo-pl-dialog" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="lupo-pl-library-title">
        <div class="lupo-pl-dialog-hd"><span id="lupo-pl-library-title">Prompt Library</span><button type="button" class="lupo-prompt-bar-btn" id="lupo-pl-library-close">Close</button></div>
        <div class="lupo-pl-dialog-bd">
            <div id="lupo-pl-library-status" class="lupo-pl-status-msg"></div>
            <div id="lupo-pl-list"></div>
        </div>
    </div>
    <div id="lupo-pl-save-wrap" class="lupo-pl-dialog" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="lupo-pl-save-heading">
        <div class="lupo-pl-dialog-hd"><span id="lupo-pl-save-heading">Save Prompt</span><button type="button" class="lupo-prompt-bar-btn" id="lupo-pl-save-close">Close</button></div>
        <div class="lupo-pl-dialog-bd">
            <div id="lupo-pl-save-status" class="lupo-pl-status-msg"></div>
            <input type="hidden" id="lupo-pl-save-prompt-id" value="">
            <div class="lupo-pl-field"><label for="lupo-pl-save-title-input">Title (required)</label><input type="text" id="lupo-pl-save-title-input" maxlength="255" placeholder="Short label for this prompt"></div>
            <div class="lupo-pl-field"><label for="lupo-pl-save-status-select">Status</label>
                <select id="lupo-pl-save-status-select">
                    <option value="draft">draft</option>
                    <option value="refining">refining</option>
                    <option value="approved">approved</option>
                </select>
            </div>
            <p style="font-size:11px;color:#888;margin:0;">Body is taken from the composer textarea when you click Save.</p>
        </div>
        <div class="lupo-pl-dialog-ft">
            <button type="button" class="lupo-prompt-bar-btn" id="lupo-pl-save-cancel">Cancel</button>
            <button type="button" class="lupo-prompt-bar-btn lupo-pl-primary" id="lupo-pl-save-submit">Save</button>
        </div>
    </div>
    <div id="lupo-pl-preview-wrap" class="lupo-pl-dialog" style="display:none;" role="dialog" aria-modal="true">
        <div class="lupo-pl-dialog-hd"><span id="lupo-pl-preview-title">Preview</span><button type="button" class="lupo-prompt-bar-btn" id="lupo-pl-preview-close">Close</button></div>
        <div class="lupo-pl-dialog-bd"><pre id="lupo-pl-preview-pre" class="lupo-pl-preview-pre"></pre></div>
    </div>
    <div id="lupo-pl-dispatch-wrap" class="lupo-pl-dialog" style="display:none;" role="dialog" aria-modal="true">
        <div class="lupo-pl-dialog-hd"><span>Dispatch prompt</span><button type="button" class="lupo-prompt-bar-btn" id="lupo-pl-dispatch-close">Close</button></div>
        <div class="lupo-pl-dialog-bd">
            <div id="lupo-pl-dispatch-status" class="lupo-pl-status-msg"></div>
            <p id="lupo-pl-dispatch-question" style="font-size:12px;line-height:1.5;margin:0;"></p>
        </div>
        <div class="lupo-pl-dialog-ft">
            <button type="button" class="lupo-prompt-bar-btn" id="lupo-pl-dispatch-cancel">Cancel</button>
            <button type="button" class="lupo-prompt-bar-btn lupo-pl-primary" id="lupo-pl-dispatch-confirm">Send</button>
        </div>
    </div>
</div>

<script src="<?= $base ?>/lupo-includes/js/lupo-layers.js"></script>
<script>
(function() {
    'use strict';
    var feedEl   = document.getElementById('lupo-feed');
    var statusEl = document.getElementById('transport-status');
    var form     = document.getElementById('lupo-compose');
    var ta       = form.querySelector('textarea');
    var targetModeSelect = document.getElementById('target-mode-select');
    var tabBarUser = document.getElementById('target-tabs-user');
    var tabBarVisitor = document.getElementById('target-tabs-visitor');
    var tabBarAgent = document.getElementById('target-tabs-agent');
    var targetTabBarsRoot = document.getElementById('target-tab-bars');
    var toActorHidden = document.getElementById('compose_to_actor_id');
    var composeToSessionInput = document.getElementById('compose_to_session_id');
    var activeTargetModeInput = document.getElementById('active_target_mode');
    var activeTargetNameEl = document.getElementById('active-target-summary');
    var activeTargetBarEl = document.getElementById('active-target-bar');
    var activeTargetNameInput = document.getElementById('active_target_actor_name');
    var recentFilesListEl = document.getElementById('recent-files-list');
    var recentTasksListEl = document.getElementById('recent-tasks-list');
    var sendMessageBtn = form.querySelector('button[name="action"][value="message"]');
    var sendTaskBtn = form.querySelector('button[name="action"][value="task"]');
    var channelInputAreaEl = document.getElementById('channel-input-area');
    var submitAction = 'message';
    var activeTarget = {
        id: '1',
        name: 'WOLFIE',
        color: '#8d6e63',
        isObserver: false,
        mode: 'user'
    };

    var state = {
        channel_id:     <?= (int) $channel_id ?>,
        thread_id:      <?= (int) $thread_id ?>,
        after_time:     <?= (int) $last_time_cursor ?>,
        isAsyncLocked:  false,
        isPollInFlight: false,
        // DOM size guard: count lines already server-rendered + appended.
        // Reload at DOM_RELOAD_THRESHOLD to prevent memory growth (doctrine: ~500 lines).
        domLineCount:   <?= count($messages) ?>
    };
    var stateChannelKey = '<?= htmlspecialchars($channel_name_key, ENT_QUOTES, 'UTF-8') ?>';
    var stateThreadKey = '<?= htmlspecialchars($today_thread_key, ENT_QUOTES, 'UTF-8') ?>';
    var promptsApiBase = '<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/api/prompts';
    var routeSendUrl = '<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/lupo-api/dialog/send-route-copy.php';
    var routeMembersUrl = '<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/lupo-api/dialog/channel-members.php';
    var routeModalOverlay = document.getElementById('lupo-route-modal-overlay');
    var routeModal = document.getElementById('lupo-route-modal');
    var routeModalStatus = document.getElementById('lupo-route-modal-status');
    var routeChannelSel = document.getElementById('lupo-route-channel');
    var routeActorSel = document.getElementById('lupo-route-actor');
    var routeExplainTa = document.getElementById('lupo-route-explain');
    var routeBtnCancel = document.getElementById('lupo-route-modal-btn-cancel');
    var routeBtnSend = document.getElementById('lupo-route-modal-btn-send');
    var routeMessageId = 0;

    var DOM_RELOAD_THRESHOLD = 500;
    var POLL_INTERVAL_MS     = 2500; // floor = 2100ms per Crafty doctrine

    feedEl.scrollTop = feedEl.scrollHeight;

    function appendLine(html) {
        var div = document.createElement('div');
        div.innerHTML = html;
        var node = div.firstChild;
        if (node) {
            feedEl.appendChild(node);
            state.domLineCount++;
            feedEl.scrollTop = feedEl.scrollHeight;
        }
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function hexToRgba(hex, alpha) {
        var s = String(hex || '').replace('#', '');
        if (s.length !== 6) { return 'rgba(26,27,30,' + alpha + ')'; }
        var r = parseInt(s.substr(0, 2), 16);
        var g = parseInt(s.substr(2, 2), 16);
        var b = parseInt(s.substr(4, 2), 16);
        return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
    }

    function syncInputToActor(actorColor, isObserver) {
        var root = document.documentElement;
        if (isObserver) {
            root.style.setProperty('--input-bg', '#1a1b1e');
            root.style.setProperty('--input-border', '#444444');
            root.style.setProperty('--active-actor-color', '#444444');
            if (activeTargetBarEl) {
                activeTargetBarEl.style.backgroundColor = '#1a1b1e';
                activeTargetBarEl.style.color = '#aaaaaa';
                activeTargetBarEl.style.borderBottomColor = '#444444';
            }
            if (channelInputAreaEl) {
                channelInputAreaEl.style.backgroundColor = '#1a1b1e';
            }
            if (ta) {
                ta.style.borderColor = '#444444';
            }
            if (sendTaskBtn) {
                sendTaskBtn.style.display = 'none';
            }
            return;
        }
        if (sendTaskBtn) {
            sendTaskBtn.style.display = '';
            sendTaskBtn.style.backgroundColor = actorColor;
            sendTaskBtn.style.color = '#000000';
        }
        root.style.setProperty('--input-bg', hexToRgba(actorColor, '0.14'));
        root.style.setProperty('--input-border', actorColor);
        root.style.setProperty('--active-actor-color', actorColor);
        if (activeTargetBarEl) {
            activeTargetBarEl.style.backgroundColor = hexToRgba(actorColor, '0.35');
            activeTargetBarEl.style.color = '#ffffff';
            activeTargetBarEl.style.borderBottomColor = actorColor;
        }
        if (channelInputAreaEl) {
            channelInputAreaEl.style.backgroundColor = hexToRgba(actorColor, '0.2');
        }
        if (ta) {
            ta.style.borderColor = actorColor;
        }
    }

    function paintActorTabs(activeBtn) {
        var bar = activeBtn ? activeBtn.closest('.actor-tab-bar') : null;
        if (!bar) {
            return;
        }
        var all = bar.querySelectorAll('.actor-tab');
        var k;
        for (k = 0; k < all.length; k++) {
            var b = all[k];
            b.style.backgroundColor = '';
            b.style.color = '';
            b.style.borderTopColor = '';
            b.style.borderRight = '1px solid #333333';
        }
        for (k = 0; k < all.length; k++) {
            var t = all[k];
            if (t.classList.contains('observer-tab')) {
                t.style.backgroundColor = '#000000';
                t.style.color = '#ffffff';
                t.style.borderTopColor = '#444444';
            } else {
                var ac = t.getAttribute('data-actor-color') || '#cccccc';
                t.style.backgroundColor = 'transparent';
                t.style.color = ac;
                t.style.borderTopColor = 'transparent';
            }
        }
        if (!activeBtn) {
            return;
        }
        if (activeBtn.classList.contains('observer-tab')) {
            activeBtn.style.backgroundColor = '#1a1a1a';
            activeBtn.style.color = '#ffffff';
            activeBtn.style.borderTopColor = '#666666';
        } else {
            var col = activeBtn.getAttribute('data-actor-color') || '#ffd700';
            activeBtn.style.backgroundColor = col;
            activeBtn.style.color = '#000000';
            activeBtn.style.borderTopColor = col;
        }
    }

    function getLineActorName(line) {
        if (!line) { return ''; }
        var d = line.getAttribute('data-actor-display');
        if (d) {
            return String(d).toUpperCase();
        }
        var sender = line.querySelector ? line.querySelector('.msg-sender') : null;
        if (sender && sender.textContent) {
            var t = sender.textContent.replace(/^\s*\[|\]\s*$/g, '');
            return String(t).toUpperCase();
        }
        return '';
    }

    function applyActiveOutputRule() {
        var rows = feedEl ? feedEl.querySelectorAll('.chat-line') : [];
        if (!rows || rows.length === 0) { return; }
        for (var i = 0; i < rows.length; i++) {
            rows[i].classList.remove('last-active-message');
        }
        var wanted = String(activeTarget.name || '').toUpperCase();
        if (wanted === '') { return; }
        for (var j = rows.length - 1; j >= 0; j--) {
            if (getLineActorName(rows[j]) === wanted) {
                rows[j].classList.add('last-active-message');
                break;
            }
        }
    }

    // [STARTUP PROBE] — One-time capability negotiation.
    // HTML load = base state. Must NOT remain here.
    // Probe fetch endpoint; on success, promote one-way to XMLHTTP and lock.
    function startupNegotiation() {
        if (state.isAsyncLocked) { return; }

        var probeUrl = '<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/lupo-api/dialog/fetch-messages.php'
            + '?channel_id=' + state.channel_id
            + '&after_time=' + state.after_time
            + '&promote=1';

        fetch(probeUrl, { credentials: 'same-origin' })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res && res.ok) {
                    // [ONE-WAY PROMOTION] Lock mode for this session. No further bouncing.
                    lockIntoAsync();
                    if (res.messages && res.messages.length > 0) {
                        res.messages.forEach(function(m) { appendLine(m.html); });
                        state.after_time = res.last_time;
                    }
                } else {
                    // Probe failed — remain in Base mode, retry on next user action.
                    statusEl.innerHTML = 'Mode: Base (probe failed)';
                    statusEl.style.color = '#fa0';
                    console.warn('[lupopedia] Startup probe failed, staying in Base mode.', res);
                }
            })
            .catch(function(err) {
                statusEl.innerHTML = 'Mode: Base (network error)';
                statusEl.style.color = '#f00';
                console.error('[lupopedia] Startup negotiation error:', err);
            });
    }

    // [SESSION LOCK-IN] — One-way promotion to async polling.
    // Stores chattype=xmlhttp in session metadata via promote=1 probe.
    // Once locked, never bounces back to Base mode.
    function lockIntoAsync() {
        state.isAsyncLocked = true;
        statusEl.innerHTML = 'Mode: XMLHTTP (locked)';
        statusEl.style.color = '#0f0';
        setInterval(poll, POLL_INTERVAL_MS);
    }

    // [INCREMENTAL POLL] — Append-only DOM updates via after_time cursor.
    // after_time = last known created_ymdhis (14-digit BIGINT UTC).
    // Cursor advances to max(created_ymdhis) in each response.
    // isPollInFlight prevents overlapping requests when server is slow.
    function poll() {
        if (state.isPollInFlight) { return; }

        // [DOM SIZE GUARD] Reload after threshold to prevent memory growth.
        // Not a failure — normal maintenance reload per doctrine.
        if (state.domLineCount >= DOM_RELOAD_THRESHOLD) {
            location.reload();
            return;
        }

        state.isPollInFlight = true;

        var pollUrl = '<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/lupo-api/dialog/fetch-messages.php'
            + '?channel_id=' + state.channel_id
            + '&after_time=' + state.after_time;

        fetch(pollUrl, { credentials: 'same-origin' })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res && res.ok && res.messages && res.messages.length > 0) {
                    res.messages.forEach(function(m) { appendLine(m.html); });
                    state.after_time = res.last_time;
                    applyActiveOutputRule();
                }
            })
            .catch(function(err) {
                console.error('[lupopedia] Poll failed:', err);
                statusEl.innerHTML = 'Mode: XMLHTTP (poll error)';
                statusEl.style.color = '#f80';
            })
            .finally(function() {
                state.isPollInFlight = false;
            });
    }

    // [SEND MESSAGE] — AJAX submit with CSRF token.
    // Falls back to native form POST on AJAX failure (form.submit()).
    if (sendMessageBtn) {
        sendMessageBtn.addEventListener('click', function() { submitAction = 'message'; });
    }
    if (sendTaskBtn) {
        sendTaskBtn.addEventListener('click', function() { submitAction = 'task'; });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var txt = ta.value.trim();
        if (!txt) { return; }
        var fd = new FormData(form);
        fd.set('action', submitAction);
        if (activeTargetNameInput) {
            fd.set('active_target_actor_name', activeTarget.name);
        }
        fd.set('is_ajax', '1');
        fetch(location.href, { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res && res.ok) {
                    ta.value = '';
                    // Immediate poll to show sent message without waiting for interval.
                    if (state.isAsyncLocked) { poll(); }
                } else if (res && res.error && statusEl) {
                    statusEl.innerHTML = 'Send failed: ' + String(res.error);
                    statusEl.style.color = '#f80';
                } else {
                    form.submit(); // Fallback to native POST
                }
            })
            .catch(function() {
                form.submit(); // Network failure: fall back to full POST
            });
    });

    ta.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            submitAction = 'message';
            form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
        }
    });

    function syncComposerActionLabels(mode) {
        var libBtn = document.getElementById('lupo-prompt-library-open');
        var savBtn = document.getElementById('lupo-prompt-save-open');
        if (!libBtn || !savBtn) {
            return;
        }
        if (mode === 'visitor') {
            libBtn.textContent = '[Canned Messages]';
            libBtn.setAttribute('title', 'Mockup: canned replies for visitors');
            savBtn.textContent = '[Save Message]';
            savBtn.setAttribute('title', 'Mockup: save visitor message draft');
        } else {
            libBtn.textContent = '[Prompt Library]';
            libBtn.setAttribute('title', 'Browse saved prompt artifacts');
            savBtn.textContent = '[Save Prompt]';
            savBtn.setAttribute('title', 'Save composer text as a prompt artifact');
        }
    }

    function setTargetMode(mode) {
        if (activeTargetModeInput) {
            activeTargetModeInput.value = mode;
        }
        activeTarget.mode = mode;
        syncComposerActionLabels(mode);
        if (tabBarUser) {
            tabBarUser.classList.toggle('is-hidden', mode !== 'user');
        }
        if (tabBarVisitor) {
            tabBarVisitor.classList.toggle('is-hidden', mode !== 'visitor');
        }
        if (tabBarAgent) {
            tabBarAgent.classList.toggle('is-hidden', mode !== 'agent');
        }
        var bar = tabBarUser;
        if (mode === 'visitor') {
            bar = tabBarVisitor;
        } else if (mode === 'agent') {
            bar = tabBarAgent;
        }
        if (!bar) {
            return;
        }
        var pick = bar.querySelector('.actor-tab.active') || bar.querySelector('.actor-tab');
        if (pick) {
            setActiveActorTab(pick);
        }
    }

    function setActiveActorTab(btn) {
        if (!btn) { return; }
        var bar = btn.closest('.actor-tab-bar');
        if (!bar || bar.classList.contains('is-hidden')) { return; }
        var mode = targetModeSelect ? String(targetModeSelect.value || 'user') : 'user';
        var buttons = bar.querySelectorAll('.actor-tab');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].classList.remove('active');
        }
        btn.classList.add('active');
        var actorColor = btn.getAttribute('data-actor-color') || '#ffd700';
        var isObserver = btn.classList.contains('observer-tab');
        var sessionId = btn.getAttribute('data-session-id');
        if (mode === 'visitor' && sessionId) {
            if (toActorHidden) {
                toActorHidden.value = '0';
            }
            if (composeToSessionInput) {
                composeToSessionInput.value = String(sessionId);
            }
            var visLabel = btn.getAttribute('data-actor-name') || 'VISITOR';
            activeTarget.id = '0';
            activeTarget.name = String(visLabel);
            activeTarget.color = String(actorColor);
            activeTarget.isObserver = false;
            if (activeTargetNameInput) {
                activeTargetNameInput.value = visLabel;
            }
            if (activeTargetNameEl) {
                activeTargetNameEl.textContent = '[VISITOR] ' + String(sessionId);
            }
            sessionStorage.setItem('lupo_active_target_mode', 'visitor');
            sessionStorage.setItem('lupo_active_target_session', String(sessionId));
            sessionStorage.setItem('lupo_active_target_color', String(actorColor));
            sessionStorage.setItem('lupo_active_target_observer', '0');
            sessionStorage.removeItem('lupo_active_target_id');
            sessionStorage.removeItem('lupo_active_target_name');
        } else {
            if (composeToSessionInput) {
                composeToSessionInput.value = '';
            }
            var actorId = btn.getAttribute('data-actor-id') || '0';
            var actorName = btn.getAttribute('data-actor-name') || 'Broadcast';
            activeTarget.id = String(actorId);
            activeTarget.name = String(actorName);
            activeTarget.color = String(actorColor);
            activeTarget.isObserver = isObserver;
            if (toActorHidden) {
                toActorHidden.value = String(actorId);
            }
            if (activeTargetNameInput) {
                activeTargetNameInput.value = actorName;
            }
            var modeTag = mode === 'agent' ? 'AGENT' : 'USER';
            if (activeTargetNameEl) {
                activeTargetNameEl.textContent = '[' + modeTag + '] ' + actorName;
            }
            sessionStorage.setItem('lupo_active_target_id', String(actorId));
            sessionStorage.setItem('lupo_active_target_name', String(actorName));
            sessionStorage.setItem('lupo_active_target_color', String(actorColor));
            sessionStorage.setItem('lupo_active_target_observer', isObserver ? '1' : '0');
            sessionStorage.setItem('lupo_active_target_mode', mode === 'agent' ? 'agent' : 'user');
            sessionStorage.removeItem('lupo_active_target_session');
        }
        paintActorTabs(btn);
        syncInputToActor(actorColor, isObserver);
        applyActiveOutputRule();
        fetchRecentFiles();
        fetchRecentTasks();
    }

    if (targetTabBarsRoot && targetModeSelect) {
        targetTabBarsRoot.addEventListener('click', function(e) {
            var target = e.target;
            if (!target || !target.classList.contains('actor-tab')) { return; }
            var tbar = target.closest('.actor-tab-bar');
            if (!tbar || tbar.classList.contains('is-hidden')) { return; }
            setActiveActorTab(target);
        });
        targetModeSelect.addEventListener('change', function() {
            setTargetMode(String(targetModeSelect.value || 'user'));
        });
        var startMode = sessionStorage.getItem('lupo_active_target_mode');
        if (startMode === 'visitor' || startMode === 'agent' || startMode === 'user') {
            targetModeSelect.value = startMode;
        }
        setTargetMode(String(targetModeSelect.value || 'user'));
    }

    var sidecarEl = document.getElementById('channel-sidecar');
    if (sidecarEl && targetModeSelect) {
        sidecarEl.addEventListener('click', function(ev) {
            var btn = ev.target;
            if (!btn || !btn.classList || !btn.classList.contains('mock-visitor-accept-btn')) {
                return;
            }
            ev.preventDefault();
            var sid = btn.getAttribute('data-session-id');
            if (!sid) {
                return;
            }
            targetModeSelect.value = 'visitor';
            setTargetMode('visitor');
            var vbar = document.getElementById('target-tabs-visitor');
            if (!vbar) {
                return;
            }
            var vtabs = vbar.querySelectorAll('.visitor-tab');
            var j;
            for (j = 0; j < vtabs.length; j++) {
                if (String(vtabs[j].getAttribute('data-session-id')) === String(sid)) {
                    setActiveActorTab(vtabs[j]);
                    break;
                }
            }
            if (statusEl) {
                statusEl.innerHTML = 'Mockup: visitor target ' + String(sid);
                statusEl.style.color = '#ffcc66';
            }
        });
    }

    function getCsrfToken() {
        var inp = form.querySelector('input[name="csrf_token"]');
        return inp ? String(inp.value || '') : '';
    }

    function closeRouteModal() {
        if (routeModalOverlay) { routeModalOverlay.style.display = 'none'; }
        if (routeModal) { routeModal.style.display = 'none'; }
        routeMessageId = 0;
    }

    function setRouteActorOptions(members) {
        if (!routeActorSel) { return; }
        while (routeActorSel.firstChild) {
            routeActorSel.removeChild(routeActorSel.firstChild);
        }
        var b = document.createElement('option');
        b.value = '0';
        b.textContent = 'Broadcast';
        routeActorSel.appendChild(b);
        if (!members || !members.length) { return; }
        for (var i = 0; i < members.length; i++) {
            var m = members[i];
            var aid = m.actor_id != null ? String(m.actor_id) : '0';
            if (aid === '0') { continue; }
            var lab = m.label ? String(m.label) : ('Actor ' + aid);
            var o = document.createElement('option');
            o.value = aid;
            o.textContent = lab;
            routeActorSel.appendChild(o);
        }
    }

    function loadRouteChannelMembers(chKey) {
        if (!routeActorSel) { return; }
        var url = routeMembersUrl + '?channel_key=' + encodeURIComponent(chKey || '');
        fetch(url, { credentials: 'same-origin' })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res && res.ok && res.members) {
                    setRouteActorOptions(res.members);
                } else {
                    setRouteActorOptions([]);
                }
            })
            .catch(function() { setRouteActorOptions([]); });
    }

    function openRouteModal(mid) {
        routeMessageId = parseInt(mid, 10) || 0;
        if (routeMessageId <= 0) { return; }
        if (routeModalStatus) { routeModalStatus.textContent = ''; }
        if (routeExplainTa) { routeExplainTa.value = ''; }
        if (routeChannelSel && stateChannelKey) {
            routeChannelSel.value = stateChannelKey;
            var ok = false;
            for (var oi = 0; oi < routeChannelSel.options.length; oi++) {
                if (routeChannelSel.options[oi].value === stateChannelKey) { ok = true; break; }
            }
            if (!ok && routeChannelSel.options.length) {
                routeChannelSel.selectedIndex = 0;
            }
        }
        loadRouteChannelMembers(routeChannelSel ? routeChannelSel.value : '');
        if (routeModalOverlay) { routeModalOverlay.style.display = 'block'; }
        if (routeModal) { routeModal.style.display = 'block'; }
    }

    if (feedEl) {
        feedEl.addEventListener('click', function(ev) {
            var t = ev.target;
            if (!t || !t.classList || !t.classList.contains('msg-route-btn')) { return; }
            var mid = t.getAttribute('data-dialog-message-id');
            if (mid) { openRouteModal(mid); }
        });
    }
    if (routeChannelSel) {
        routeChannelSel.addEventListener('change', function() {
            loadRouteChannelMembers(routeChannelSel.value);
        });
    }
    if (routeBtnCancel) {
        routeBtnCancel.addEventListener('click', closeRouteModal);
    }
    if (routeModalOverlay) {
        routeModalOverlay.addEventListener('click', closeRouteModal);
    }
    var plOverlay = document.getElementById('lupo-pl-overlay');
    var plLibraryWrap = document.getElementById('lupo-pl-library-wrap');
    var plSaveWrap = document.getElementById('lupo-pl-save-wrap');
    var plPreviewWrap = document.getElementById('lupo-pl-preview-wrap');
    var plDispatchWrap = document.getElementById('lupo-pl-dispatch-wrap');
    var plListEl = document.getElementById('lupo-pl-list');
    var plLibraryStatus = document.getElementById('lupo-pl-library-status');
    var plSaveStatus = document.getElementById('lupo-pl-save-status');
    var plDispatchStatus = document.getElementById('lupo-pl-dispatch-status');
    var plDispatchQuestion = document.getElementById('lupo-pl-dispatch-question');
    var plSaveTitleInput = document.getElementById('lupo-pl-save-title-input');
    var plSaveStatusSelect = document.getElementById('lupo-pl-save-status-select');
    var plSavePromptId = document.getElementById('lupo-pl-save-prompt-id');
    var plPreviewPre = document.getElementById('lupo-pl-preview-pre');
    var plPreviewTitle = document.getElementById('lupo-pl-preview-title');
    var plDispatchPromptId = 0;
    var plDispatchAsTask = false;

    function plCloseAll() {
        if (plOverlay) {
            plOverlay.classList.remove('lupo-pl-open');
            plOverlay.setAttribute('aria-hidden', 'true');
        }
        if (plLibraryWrap) { plLibraryWrap.style.display = 'none'; }
        if (plSaveWrap) { plSaveWrap.style.display = 'none'; }
        if (plPreviewWrap) { plPreviewWrap.style.display = 'none'; }
        if (plDispatchWrap) { plDispatchWrap.style.display = 'none'; }
        plDispatchPromptId = 0;
    }

    function plOpenPanel(wrapEl) {
        plCloseAll();
        if (!plOverlay || !wrapEl) { return; }
        wrapEl.style.display = 'flex';
        plOverlay.classList.add('lupo-pl-open');
        plOverlay.setAttribute('aria-hidden', 'false');
    }

    function plFormatTs(ymdhis) {
        var s = String(ymdhis || '');
        if (s.length < 14) { return s; }
        return s.substr(0, 4) + '-' + s.substr(4, 2) + '-' + s.substr(6, 2) + ' ' + s.substr(8, 2) + ':' + s.substr(10, 2) + ':' + s.substr(12, 2) + ' UTC';
    }

    function plFetchList() {
        if (!plListEl || !plLibraryStatus) { return; }
        plLibraryStatus.textContent = 'Loading...';
        plListEl.innerHTML = '';
        var url = promptsApiBase + '/list?channel_key=' + encodeURIComponent(stateChannelKey);
        fetch(url, { credentials: 'same-origin' })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                plLibraryStatus.textContent = '';
                if (!res || !res.ok || !res.prompts) {
                    plLibraryStatus.textContent = (res && res.error) ? String(res.error) : 'Could not load prompts.';
                    return;
                }
                if (res.prompts.length === 0) {
                    plListEl.innerHTML = '<div style="color:#888;font-size:12px;">No saved prompts for this channel yet.</div>';
                    return;
                }
                var html = '';
                res.prompts.forEach(function(p) {
                    var pid = parseInt(p.prompt_id, 10) || 0;
                    var escT = escapeHtml(p.title || '');
                    var escP = escapeHtml(p.preview || '');
                    var st = escapeHtml(p.status || '');
                    html += '<div class="lupo-pl-list-row" data-prompt-id="' + pid + '">';
                    html += '<div class="lupo-pl-row-title">' + escT + '</div>';
                    html += '<div class="lupo-pl-row-meta">status: ' + st + ' | updated: ' + escapeHtml(plFormatTs(p.last_updated_ymdhis)) + '</div>';
                    html += '<div class="lupo-pl-row-preview">' + escP + '</div>';
                    html += '<div class="lupo-pl-row-actions">';
                    html += '<button type="button" class="lupo-pl-load" data-prompt-id="' + pid + '">[Load]</button>';
                    html += '<button type="button" class="lupo-pl-preview" data-prompt-id="' + pid + '">[Preview]</button>';
                    html += '<button type="button" class="lupo-pl-send lupo-pl-primary" data-prompt-id="' + pid + '">[Send to ACTIVE ACTOR]</button>';
                    html += '</div></div>';
                });
                plListEl.innerHTML = html;
            })
            .catch(function() {
                plLibraryStatus.textContent = 'Network error loading prompts.';
            });
    }

    if (plListEl) {
        plListEl.addEventListener('click', function(ev) {
            var t = ev.target;
            if (!t || !t.getAttribute) { return; }
            var pid = parseInt(t.getAttribute('data-prompt-id'), 10) || 0;
            if (pid <= 0) { return; }
            if (t.classList.contains('lupo-pl-load')) {
                fetch(promptsApiBase + '/get?prompt_id=' + pid, { credentials: 'same-origin' })
                    .then(function(r) { return r.json(); })
                    .then(function(res) {
                        if (res && res.ok && res.prompt && ta) {
                            ta.value = res.prompt.prompt_text || '';
                            plCloseAll();
                        } else if (plLibraryStatus) {
                            plLibraryStatus.textContent = (res && res.error) ? String(res.error) : 'Load failed.';
                        }
                    })
                    .catch(function() {
                        if (plLibraryStatus) { plLibraryStatus.textContent = 'Network error.'; }
                    });
                return;
            }
            if (t.classList.contains('lupo-pl-preview')) {
                fetch(promptsApiBase + '/get?prompt_id=' + pid, { credentials: 'same-origin' })
                    .then(function(r) { return r.json(); })
                    .then(function(res) {
                        if (res && res.ok && res.prompt && plPreviewPre) {
                            plPreviewPre.textContent = res.prompt.prompt_text || '';
                            if (plPreviewTitle) { plPreviewTitle.textContent = res.prompt.title || 'Preview'; }
                            if (plLibraryWrap) { plLibraryWrap.style.display = 'none'; }
                            plOpenPanel(plPreviewWrap);
                        }
                    });
                return;
            }
            if (t.classList.contains('lupo-pl-send')) {
                var modeTask = (submitAction === 'task');
                var toId = parseInt(activeTarget.id, 10) || 0;
                if (modeTask && toId <= 0) {
                    if (plLibraryStatus) { plLibraryStatus.textContent = 'Select a target actor (not Broadcast) before dispatching a task.'; }
                    return;
                }
                if (state.thread_id <= 0 || state.channel_id <= 0) {
                    if (plLibraryStatus) { plLibraryStatus.textContent = 'Thread or channel not ready; reload the page.'; }
                    return;
                }
                var actorLabel = activeTargetNameEl ? activeTargetNameEl.textContent.replace(/^\s*\[|\]\s*$/g, '') : 'ACTIVE';
                plDispatchPromptId = pid;
                plDispatchAsTask = modeTask;
                var q = modeTask
                    ? ('Send this prompt as a TASK to [' + String(actorLabel).toUpperCase() + ']?')
                    : ('Send this prompt as a MESSAGE to [' + String(actorLabel).toUpperCase() + ']?');
                if (plDispatchQuestion) { plDispatchQuestion.textContent = q; }
                if (plDispatchStatus) { plDispatchStatus.textContent = ''; }
                if (plLibraryWrap) { plLibraryWrap.style.display = 'none'; }
                plOpenPanel(plDispatchWrap);
            }
        });
    }

    var plLibOpenBtn = document.getElementById('lupo-prompt-library-open');
    if (plLibOpenBtn) {
        plLibOpenBtn.addEventListener('click', function() {
            if (plLibraryStatus) { plLibraryStatus.textContent = ''; }
            plOpenPanel(plLibraryWrap);
            plFetchList();
        });
    }
    var plSaveOpenBtn = document.getElementById('lupo-prompt-save-open');
    if (plSaveOpenBtn) {
        plSaveOpenBtn.addEventListener('click', function() {
            if (plSaveStatus) { plSaveStatus.textContent = ''; }
            if (plSavePromptId) { plSavePromptId.value = ''; }
            if (plSaveTitleInput) { plSaveTitleInput.value = ''; }
            if (plSaveStatusSelect) { plSaveStatusSelect.value = 'draft'; }
            plOpenPanel(plSaveWrap);
        });
    }
    var plLibCloseBtn = document.getElementById('lupo-pl-library-close');
    if (plLibCloseBtn) { plLibCloseBtn.addEventListener('click', plCloseAll); }
    var plSaveCloseBtn = document.getElementById('lupo-pl-save-close');
    if (plSaveCloseBtn) { plSaveCloseBtn.addEventListener('click', plCloseAll); }
    var plSaveCancelBtn = document.getElementById('lupo-pl-save-cancel');
    if (plSaveCancelBtn) { plSaveCancelBtn.addEventListener('click', plCloseAll); }
    var plPreviewCloseBtn = document.getElementById('lupo-pl-preview-close');
    if (plPreviewCloseBtn) {
        plPreviewCloseBtn.addEventListener('click', function() {
            if (plPreviewWrap) { plPreviewWrap.style.display = 'none'; }
            if (plLibraryWrap) { plLibraryWrap.style.display = 'flex'; plFetchList(); }
        });
    }
    var plDispatchCloseBtn = document.getElementById('lupo-pl-dispatch-close');
    if (plDispatchCloseBtn) {
        plDispatchCloseBtn.addEventListener('click', function() {
            if (plDispatchWrap) { plDispatchWrap.style.display = 'none'; }
            if (plLibraryWrap) { plLibraryWrap.style.display = 'flex'; }
        });
    }
    var plDispatchCancelBtn = document.getElementById('lupo-pl-dispatch-cancel');
    if (plDispatchCancelBtn) {
        plDispatchCancelBtn.addEventListener('click', function() {
            if (plDispatchWrap) { plDispatchWrap.style.display = 'none'; }
            if (plLibraryWrap) { plLibraryWrap.style.display = 'flex'; }
        });
    }
    if (plOverlay) {
        plOverlay.addEventListener('click', function(ev) {
            if (ev.target === plOverlay) { plCloseAll(); }
        });
    }
    var plSaveSubmitBtn = document.getElementById('lupo-pl-save-submit');
    if (plSaveSubmitBtn) {
        plSaveSubmitBtn.addEventListener('click', function() {
        if (!plSaveTitleInput || !ta) { return; }
        var title = plSaveTitleInput.value.trim();
        var body = ta.value.trim();
        if (title === '') {
            if (plSaveStatus) { plSaveStatus.textContent = 'Title is required.'; }
            return;
        }
        if (body === '') {
            if (plSaveStatus) { plSaveStatus.textContent = 'Composer is empty.'; }
            return;
        }
        var payload = {
            csrf_token: getCsrfToken(),
            title: title,
            prompt_text: body,
            status: plSaveStatusSelect ? plSaveStatusSelect.value : 'draft',
            channel_key: stateChannelKey,
            thread_id: stateThreadKey
        };
        var ep = plSavePromptId && plSavePromptId.value ? parseInt(plSavePromptId.value, 10) : 0;
        if (ep > 0) { payload.prompt_id = ep; }
        if (plSaveStatus) { plSaveStatus.textContent = 'Saving...'; }
        fetch(promptsApiBase + '/save', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res && res.ok) {
                    plCloseAll();
                    if (plSavePromptId) { plSavePromptId.value = ''; }
                } else {
                    if (plSaveStatus) { plSaveStatus.textContent = (res && res.error) ? String(res.error) : 'Save failed.'; }
                }
            })
            .catch(function() {
                if (plSaveStatus) { plSaveStatus.textContent = 'Network error.'; }
            });
        });
    }
    var plDispatchConfirmBtn = document.getElementById('lupo-pl-dispatch-confirm');
    if (plDispatchConfirmBtn) {
        plDispatchConfirmBtn.addEventListener('click', function() {
        if (plDispatchPromptId <= 0) { return; }
        if (plDispatchStatus) { plDispatchStatus.textContent = 'Sending...'; }
        var toId = parseInt(activeTarget.id, 10) || 0;
        if (plDispatchAsTask && toId <= 0) {
            if (plDispatchStatus) { plDispatchStatus.textContent = 'Task requires a target actor.'; }
            return;
        }
        var payload = {
            csrf_token: getCsrfToken(),
            prompt_id: plDispatchPromptId,
            thread_id: state.thread_id,
            channel_id: state.channel_id,
            to_actor_id: toId,
            dispatch_as_task: plDispatchAsTask ? 1 : 0
        };
        fetch(promptsApiBase + '/dispatch', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res && res.ok) {
                    plCloseAll();
                    if (state.isAsyncLocked) { poll(); }
                } else {
                    if (plDispatchStatus) { plDispatchStatus.textContent = (res && res.error) ? String(res.error) : 'Dispatch failed.'; }
                }
            })
            .catch(function() {
                if (plDispatchStatus) { plDispatchStatus.textContent = 'Network error.'; }
            });
        });
    }

    if (routeBtnSend) {
        routeBtnSend.addEventListener('click', function() {
            if (routeMessageId <= 0) { return; }
            if (routeModalStatus) { routeModalStatus.textContent = ''; }
            var destKey = routeChannelSel ? String(routeChannelSel.value || '') : '';
            if (!destKey) {
                if (routeModalStatus) { routeModalStatus.textContent = 'Pick a destination channel.'; }
                return;
            }
            var destActor = routeActorSel ? parseInt(routeActorSel.value, 10) || 0 : 0;
            var explain = routeExplainTa ? String(routeExplainTa.value || '').trim() : '';
            var payload = {
                csrf_token: getCsrfToken(),
                dialog_message_id: routeMessageId,
                source_channel_id: state.channel_id,
                destination_channel_key: destKey,
                destination_actor_id: destActor,
                routing_explanation: explain
            };
            routeBtnSend.disabled = true;
            fetch(routeSendUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
                .then(function(r) { return r.json().then(function(j) { return { status: r.status, body: j }; }); })
                .then(function(pair) {
                    var res = pair.body;
                    if (res && res.ok) {
                        closeRouteModal();
                        if (state.isAsyncLocked) { poll(); }
                        if (statusEl) {
                            statusEl.innerHTML = 'Routed copy sent';
                            statusEl.style.color = '#0f0';
                            setTimeout(function() {
                                if (state.isAsyncLocked) {
                                    statusEl.innerHTML = 'Mode: XMLHTTP (locked)';
                                    statusEl.style.color = '#0f0';
                                }
                            }, 2500);
                        }
                    } else {
                        var err = (res && res.error) ? String(res.error) : ('HTTP ' + pair.status);
                        if (routeModalStatus) { routeModalStatus.textContent = err; }
                    }
                })
                .catch(function() {
                    if (routeModalStatus) { routeModalStatus.textContent = 'Network error.'; }
                })
                .finally(function() {
                    routeBtnSend.disabled = false;
                });
        });
    }

    // Begin startup negotiation 500ms after page load.
    // Gives page layout time to settle before first network request.
    setTimeout(startupNegotiation, 500);

    function fetchRecentFiles() {
        if (!recentFilesListEl) { return; }
        var url = '<?= $base ?>/api/files/recent?channel_id=' + state.channel_id + '&actor_id=' + encodeURIComponent(activeTarget.id) + '&limit=5';
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(res) {
                var files = [];
                if (res && res.files && Array.isArray(res.files)) {
                    files = res.files;
                } else if (res && res.data && res.data.files && Array.isArray(res.data.files)) {
                    files = res.data.files;
                }
                renderRecentFiles(files);
            })
            .catch(function() { renderRecentFiles([]); });
    }

    function renderRecentFiles(files) {
        if (!recentFilesListEl) { return; }
        if (!files || files.length === 0) {
            recentFilesListEl.innerHTML = '<div class="recent-item" style="font-style:italic;">No recent files.</div>';
            return;
        }
        var html = '';
        files.forEach(function(f) {
            var fp = f.file_path_from_root || f.file_path || f.path || '';
            html += '<div class="recent-item">' + escapeHtml(fp) + '</div>';
        });
        recentFilesListEl.innerHTML = html;
    }

    function fetchRecentTasks() {
        if (!recentTasksListEl) { return; }
        var url = '<?= $base ?>/api/tasks/list?channel_id=' + state.channel_id + '&actor_id=' + encodeURIComponent(activeTarget.id) + '&limit=5';
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(res) {
                var tasks = [];
                if (res && res.tasks && Array.isArray(res.tasks)) {
                    tasks = res.tasks;
                } else if (res && res.data && res.data.tasks && Array.isArray(res.data.tasks)) {
                    tasks = res.data.tasks;
                }
                renderRecentTasks(tasks);
            })
            .catch(function() { renderRecentTasks([]); });
    }

    function renderRecentTasks(tasks) {
        if (!recentTasksListEl) { return; }
        if (!tasks || tasks.length === 0) {
            recentTasksListEl.innerHTML = '<div class="recent-item" style="font-style:italic;">No recent tasks.</div>';
            return;
        }
        var html = '';
        tasks.forEach(function(t) {
            var body = t.task_body || t.title || t.task_description || '';
            html += '<div class="recent-item">' + escapeHtml(body) + '</div>';
        });
        recentTasksListEl.innerHTML = html;
    }

    fetchRecentFiles();
    fetchRecentTasks();
    setInterval(fetchRecentFiles, 15000);
    setInterval(fetchRecentTasks, 15000);
})();
</script>
<?php
$admin_main_content = ob_get_clean();
require LUPOPEDIA_PATH . '/lupo-includes/themes/default/layouts/admin_layout.php';
