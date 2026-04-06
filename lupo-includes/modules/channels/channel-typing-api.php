<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: module
  when_updated: "20260406012834"
  file_path_from_root: "lupo-includes/modules/channels/channel-typing-api.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/modules/channels/channel-typing-api.php"
  last_modified_utc: "20260406012834"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "api"
  artifact_kind: "channel_typing_preview"
  purpose: "Ephemeral typing drafts in lupo_channel_typing_previews (PDO_DB); actor_id from session; CSRF on POST (non-visitor)."
  tags: ["channels", "typing", "ephemeral", "timestamp_ymdhis", "pdo_db", "untrusted"]
---
*/

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Config not loaded'));
    exit;
}

$UNTRUSTED = array(
    'server' => (isset($_SERVER) && is_array($_SERVER)) ? $_SERVER : array(),
    'get' => (isset($_GET) && is_array($_GET)) ? $_GET : array(),
    'post' => (isset($_POST) && is_array($_POST)) ? $_POST : array(),
);

$lupo_raw_input = @file_get_contents('php://input');
if ($lupo_raw_input === false) {
    $lupo_raw_input = '';
}
$lupo_decoded_input = json_decode($lupo_raw_input, true);
$UNTRUSTED['input'] = is_array($lupo_decoded_input) ? $lupo_decoded_input : array();

$app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
if (!class_exists('timestamp_ymdhis', false)) {
    require_once rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'TimestampYmdhis.php';
}
if (!class_exists('IdGenerator', false)) {
    require_once rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'IdGenerator.php';
}
if (!class_exists('DatabaseFactory', false)) {
    require_once rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'DatabaseFactory.php';
}

$stale_seconds = 30;
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$typing_table = $table_prefix . 'channel_typing_previews';

/**
 * Hard-delete soft-deleted typing rows ~10% of requests (rand 1–10 === 7).
 *
 * Constitutional note (ephemeral data): soft-deleted typing previews are hard-deleted here on purge
 * because they have no long-term lineage value; accumulating soft-deleted rows would bloat the table.
 * Applies only to rows already marked is_deleted = 1 — not canonical dialog content.
 *
 * @param object $db PDO_DB
 * @param string $typing_table Prefixed table name
 * @return void
 */
function channel_typing_maybe_purge_soft_deleted($db, $typing_table)
{
    if ((int) rand(1, 10) !== 7) {
        return;
    }
    $n = $db->delete($typing_table, 'is_deleted = :del', array('del' => 1));
    if ($n > 0 && function_exists('error_log')) {
        error_log('channel-typing-api: purged ' . (int) $n . ' soft-deleted row(s) from channel_typing_previews');
    }
}

/**
 * @param object $db PDO_DB
 * @param string $typing_table Prefixed table name
 * @param int $channel_id
 * @param int $stale_seconds
 * @return array thread_id => preview row shape for JSON
 */
function channel_typing_get($db, $typing_table, $channel_id, $stale_seconds)
{
    $cutoff_ymdhis = (string) timestamp_ymdhis::subtractSeconds(timestamp_ymdhis::now(), (int) $stale_seconds);
    $sql = "SELECT dialog_thread_id, from_actor_id, actor_name, preview_text, updated_ymdhis
            FROM {$typing_table}
            WHERE channel_id = :cid AND is_deleted = 0 AND updated_ymdhis >= :cutoff";
    $rows = $db->fetchAll($sql, array('cid' => $channel_id, 'cutoff' => $cutoff_ymdhis));
    $out = array();
    if (!is_array($rows)) {
        return $out;
    }
    foreach ($rows as $row) {
        if (empty($row['preview_text'])) {
            continue;
        }
        $tid = (string) (int) $row['dialog_thread_id'];
        $out[$tid] = array(
            'actor_id'       => (int) $row['from_actor_id'],
            'actor_name'     => isset($row['actor_name']) ? (string) $row['actor_name'] : 'Visitor',
            'preview_text'   => (string) $row['preview_text'],
            'updated_ymdhis' => (string) $row['updated_ymdhis'],
        );
    }
    return $out;
}

/**
 * @param object $db PDO_DB
 * @param string $typing_table
 * @param int $channel_id
 * @param int $dialog_thread_id
 * @param int $actor_id
 * @param string $preview_text
 * @param string $actor_name
 * @return bool
 */
function channel_typing_post($db, $typing_table, $channel_id, $dialog_thread_id, $actor_id, $preview_text, $actor_name)
{
    $now = (string) timestamp_ymdhis::now();
    $sqlSel = "SELECT channel_typing_preview_id, is_deleted FROM {$typing_table}
               WHERE channel_id = :cid AND dialog_thread_id = :tid LIMIT 1";
    $existing = $db->fetchRow($sqlSel, array('cid' => $channel_id, 'tid' => $dialog_thread_id));

    if ($preview_text === '' || strlen($preview_text) === 0) {
        if ($existing && isset($existing['channel_typing_preview_id']) && (int) $existing['is_deleted'] === 0) {
            $db->update(
                $typing_table,
                array(
                    'preview_text'   => '',
                    'updated_ymdhis' => $now,
                    'is_deleted'     => 1,
                    'deleted_ymdhis' => $now,
                ),
                'channel_typing_preview_id = :id',
                array('id' => $existing['channel_typing_preview_id'])
            );
        }
        return true;
    }

    $preview_text = substr($preview_text, 0, 1000);
    $dispName = (strlen($actor_name) > 0 ? $actor_name : 'Visitor');
    $actor_name = substr($dispName, 0, 255);

    if ($existing && isset($existing['channel_typing_preview_id'])) {
        $db->update(
            $typing_table,
            array(
                'from_actor_id'   => (int) $actor_id,
                'actor_name'      => $actor_name,
                'preview_text'    => $preview_text,
                'updated_ymdhis'  => $now,
                'is_deleted'      => 0,
                'deleted_ymdhis'  => 0,
            ),
            'channel_typing_preview_id = :id',
            array('id' => $existing['channel_typing_preview_id'])
        );
        return true;
    }

    $newId = IdGenerator::generate();
    if (!IdGenerator::validateFormat($newId)) {
        if (function_exists('error_log')) {
            error_log('channel-typing-api: IdGenerator returned invalid id');
        }
        return false;
    }

    $ok = $db->insert($typing_table, array(
        'channel_typing_preview_id' => $newId,
        'channel_id'                => $channel_id,
        'dialog_thread_id'          => $dialog_thread_id,
        'from_actor_id'             => (int) $actor_id,
        'actor_name'                => $actor_name,
        'preview_text'              => $preview_text,
        'created_ymdhis'            => $now,
        'updated_ymdhis'            => $now,
        'is_deleted'                => 0,
        'deleted_ymdhis'            => 0,
    ));
    if ($ok === false) {
        if (function_exists('error_log')) {
            error_log('channel-typing-api: insert into channel_typing_previews failed');
        }
        return false;
    }
    return true;
}

/**
 * Model A CSRF: token must match lupo_sessions row for current PHP session id.
 *
 * @param object $db PDO_DB
 * @param string $token
 * @return bool
 */
function channel_typing_verify_csrf_token($db, $token)
{
    $t = is_string($token) ? $token : '';
    if ($t === '') {
        return false;
    }
    if (!isset($GLOBALS['lupo_session']) || !is_object($GLOBALS['lupo_session'])) {
        return false;
    }
    $sid = $GLOBALS['lupo_session']->getSessionId();
    if ($sid === '' || $sid === false) {
        return false;
    }
    $loaded = \App\Auth\Session::loadById($db, $sid);
    return $loaded && isset($loaded->csrf_token) && is_string($loaded->csrf_token)
        && hash_equals($loaded->csrf_token, $t);
}

/**
 * Display label for typing row from lupo_actors (do not trust client actor_name for POST).
 *
 * @param object $db PDO_DB
 * @param string $table_prefix
 * @param int $actor_id
 * @return string
 */
function channel_typing_resolve_actor_display_name($db, $table_prefix, $actor_id)
{
    if ($actor_id <= 0) {
        return 'Visitor';
    }
    $row = $db->fetchRow(
        "SELECT name, actor_name FROM {$table_prefix}actors WHERE actor_id = :aid AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
        array('aid' => $actor_id)
    );
    if (!$row) {
        return 'Visitor';
    }
    if (isset($row['name']) && (string) $row['name'] !== '') {
        return substr((string) $row['name'], 0, 255);
    }
    if (isset($row['actor_name']) && (string) $row['actor_name'] !== '') {
        return substr((string) $row['actor_name'], 0, 255);
    }
    return 'Visitor';
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

try {
    $db = DatabaseFactory::getConnection();
} catch (Exception $e) {
    if (function_exists('error_log')) {
        error_log('channel-typing-api: ' . $e->getMessage());
    }
    http_response_code(503);
    echo json_encode(array('error' => 'Database unavailable'));
    exit;
}

channel_typing_maybe_purge_soft_deleted($db, $typing_table);

$method = isset($UNTRUSTED['server']['REQUEST_METHOD']) ? $UNTRUSTED['server']['REQUEST_METHOD'] : 'GET';
$channel_id = isset($UNTRUSTED['get']['channel_id']) ? (int) $UNTRUSTED['get']['channel_id'] : 0;
if ($channel_id <= 0 && $method === 'POST') {
    $channel_id = isset($UNTRUSTED['input']['channel_id']) ? (int) $UNTRUSTED['input']['channel_id'] : 0;
    if ($channel_id <= 0 && isset($UNTRUSTED['post']['channel_id'])) {
        $channel_id = (int) $UNTRUSTED['post']['channel_id'];
    }
}

if ($channel_id <= 0) {
    echo json_encode(array('error' => 'channel_id required'));
    exit;
}

if ($method === 'GET') {
    $previews = channel_typing_get($db, $typing_table, $channel_id, $stale_seconds);
    echo json_encode(array('previews' => $previews));
    exit;
}

if ($method === 'POST') {
    $dialog_thread_id = isset($UNTRUSTED['input']['dialog_thread_id']) ? (int) $UNTRUSTED['input']['dialog_thread_id'] : 0;
    if ($dialog_thread_id <= 0 && isset($UNTRUSTED['post']['dialog_thread_id'])) {
        $dialog_thread_id = (int) $UNTRUSTED['post']['dialog_thread_id'];
    }
    $preview_text = isset($UNTRUSTED['input']['preview_text']) ? (string) $UNTRUSTED['input']['preview_text'] : '';
    if ($preview_text === '' && isset($UNTRUSTED['post']['preview_text'])) {
        $preview_text = (string) $UNTRUSTED['post']['preview_text'];
    }

    $visitor_sid = '';
    if (isset($UNTRUSTED['input']['cslhVISITOR'])) {
        $visitor_sid = (string) $UNTRUSTED['input']['cslhVISITOR'];
    } elseif (isset($UNTRUSTED['post']['cslhVISITOR'])) {
        $visitor_sid = (string) $UNTRUSTED['post']['cslhVISITOR'];
    }

    $visitor_mode = false;
    if ($visitor_sid !== '') {
        $helper_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'crafty_syntax' . DIRECTORY_SEPARATOR . 'visitor-session-helper.php';
        if (is_file($helper_path)) {
            require_once $helper_path;
            if (function_exists('crafty_syntax_validate_visitor_session') && crafty_syntax_validate_visitor_session($visitor_sid)) {
                $visitor_mode = true;
            }
        }
    }

    if (!$visitor_mode) {
        $csrf = '';
        if (isset($UNTRUSTED['server']['HTTP_X_CSRF_TOKEN'])) {
            $csrf = (string) $UNTRUSTED['server']['HTTP_X_CSRF_TOKEN'];
        }
        if ($csrf === '' && isset($UNTRUSTED['input']['csrf_token'])) {
            $csrf = (string) $UNTRUSTED['input']['csrf_token'];
        }
        if ($csrf === '' && isset($UNTRUSTED['post']['csrf_token'])) {
            $csrf = (string) $UNTRUSTED['post']['csrf_token'];
        }
        if (!channel_typing_verify_csrf_token($db, $csrf)) {
            http_response_code(403);
            echo json_encode(array('error' => 'Invalid CSRF token'));
            exit;
        }
    }

    $actor_id = null;
    if ($visitor_mode) {
        $actor_id = 0;
    } else {
        $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
        if ($authService) {
            $user = $authService->getCurrentUser();
            if ($user && !empty($user['actor_id'])) {
                $actor_id = (int) $user['actor_id'];
            }
        } elseif (function_exists('current_user')) {
            $user = current_user();
            if ($user && !empty($user['actor_id'])) {
                $actor_id = (int) $user['actor_id'];
            }
        }
        if ($actor_id === null && isset($GLOBALS['lupo_session']) && is_object($GLOBALS['lupo_session'])) {
            $vs = $GLOBALS['lupo_session']->validateSession();
            if ($vs !== false) {
                $actor_id = (int) $vs;
            }
        }
        if ($actor_id === null) {
            http_response_code(401);
            echo json_encode(array('error' => 'Not authenticated'));
            exit;
        }
    }

    if ($dialog_thread_id <= 0) {
        echo json_encode(array('error' => 'dialog_thread_id required'));
        exit;
    }

    if ($visitor_mode) {
        if ($channel_id === 0) {
            $ok_thread = $db->fetchRow(
                "SELECT 1 AS o FROM {$table_prefix}dialog_threads WHERE dialog_thread_id = :tid AND channel_id IS NULL AND is_deleted = 0 LIMIT 1",
                array('tid' => $dialog_thread_id)
            );
        } else {
            $ok_thread = $db->fetchRow(
                "SELECT 1 AS o FROM {$table_prefix}dialog_threads WHERE dialog_thread_id = :tid AND channel_id = :cid AND is_deleted = 0 LIMIT 1",
                array('tid' => $dialog_thread_id, 'cid' => $channel_id)
            );
        }
        if (!$ok_thread) {
            http_response_code(403);
            echo json_encode(array('error' => 'Access denied to channel'));
            exit;
        }
    } else {
        $has_access = false;
        $mem = $db->fetchRow(
            "SELECT 1 AS o FROM {$table_prefix}actor_channels WHERE actor_id = :aid AND channel_id = :cid AND is_deleted = 0 LIMIT 1",
            array('aid' => $actor_id, 'cid' => $channel_id)
        );
        if ($mem) {
            $has_access = true;
        }
        if (!$has_access && isset($GLOBALS['lupo_auth_service']) && is_object($GLOBALS['lupo_auth_service'])
            && method_exists($GLOBALS['lupo_auth_service'], 'isAdmin')) {
            if ($GLOBALS['lupo_auth_service']->isAdmin($actor_id)) {
                $has_access = true;
            }
        }
        if (!$has_access) {
            http_response_code(403);
            echo json_encode(array('error' => 'Access denied to channel'));
            exit;
        }
    }

    $actor_name = channel_typing_resolve_actor_display_name($db, $table_prefix, $actor_id);

    $ok = channel_typing_post($db, $typing_table, $channel_id, $dialog_thread_id, $actor_id, $preview_text, $actor_name);
    echo json_encode(array('ok' => $ok));
    exit;
}

http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;
