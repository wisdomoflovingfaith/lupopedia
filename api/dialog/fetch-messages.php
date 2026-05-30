<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "api/dialog/fetch-messages.php"
#   web_path: "https://www.lupopedia.com/lupopedia/api/dialog/fetch-messages.php"
#   status: "complete"
#   when_updated: "20260418035748"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/api-fetch-messages.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/fetch-messages-api"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "02"
#   content_slug: "fetch-messages-api"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Fetch Messages API — Delta Polling Endpoint"
#   summary: "Delta polling for channel messages; optional promote path for offline shim."
# ---------------------------------------------------------------------
define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
$UNTRUSTED = array(
    'get'    => (isset($_GET)    && is_array($_GET))    ? $_GET    : array(),
);

require_once LUPOPEDIA_PATH . '/includes/classes/LupopediaConfigResolver.php';
$lupoResolvedCfg = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, '/');
define('LUPOPEDIA_CONFIG_PATH', $lupoResolvedCfg ?: LUPOPEDIA_PATH . '/lupopedia-config.php');
require_once LUPOPEDIA_CONFIG_PATH;

require_once LUPOPEDIA_PATH . '/includes/classes/DatabaseFactory.php';
require_once LUPOPEDIA_PATH . '/includes/classes/TimestampYmdhis.php';
require_once LUPOPEDIA_PATH . '/includes/functions/channel_chat_row.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $db = DatabaseFactory::getConnection();
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    $channel_id = isset($UNTRUSTED['get']['channel_id']) ? (int) $UNTRUSTED['get']['channel_id'] : 0;
    $after_time = isset($UNTRUSTED['get']['after_time']) ? (int) $UNTRUSTED['get']['after_time'] : 0;
    $promote    = !empty($UNTRUSTED['get']['promote']);

    if ($channel_id <= 0) {
        echo json_encode(array('ok' => false, 'error' => 'Missing channel_id'));
        exit;
    }

    // --- AUTH GATE (OQ-12 / OQ-19 / OQ-23) -----------------------------------------------
    // Session validation + channel membership check before returning any messages.
    // Merged from OQ-12, OQ-19, OQ-23 (same deficiency, three reporters).
    $_lupo_chan_meta  = $db->fetchRow(
        "SELECT channel_key, visibility_status FROM {$prefix}channels WHERE channel_id = :cid AND is_deleted = 0 LIMIT 1",
        array('cid' => $channel_id)
    );
    $_lupo_chan_key   = $_lupo_chan_meta ? (string) $_lupo_chan_meta['channel_key'] : '';
    $_lupo_is_public  = $_lupo_chan_meta && $_lupo_chan_meta['visibility_status'] === 'public';
    // Dev-channel exception: channel_key='development' bypasses for now.
    $_lupo_dev_bypass = (strpos($_lupo_chan_key, 'development') !== false || $_lupo_chan_key === '');

    if (!$_lupo_dev_bypass && !$_lupo_is_public) {
        $_lupo_actor_id = 0;
        if (isset($GLOBALS['lupo_session']) && is_object($GLOBALS['lupo_session']) && method_exists($GLOBALS['lupo_session'], 'validateSession')) {
            $_lupo_actor_id = (int) $GLOBALS['lupo_session']->validateSession();
        }
        if ($_lupo_actor_id <= 0) {
            http_response_code(403);
            echo json_encode(array('error' => 'Unauthorized', 'code' => 403));
            exit;
        }
        $_lupo_is_member = $db->fetchRow(
            "SELECT 1 FROM {$prefix}actor_channels WHERE actor_id = :aid AND channel_id = :cid AND is_deleted = 0 LIMIT 1",
            array('aid' => $_lupo_actor_id, 'cid' => $channel_id)
        );
        if (!$_lupo_is_member) {
            http_response_code(403);
            echo json_encode(array('error' => 'Unauthorized', 'code' => 403));
            exit;
        }
    }
    // --- END AUTH GATE -------------------------------------------------------------------

    $_lupo_operator_actor_id = 0;
    if (isset($GLOBALS['lupo_session']) && is_object($GLOBALS['lupo_session']) && method_exists($GLOBALS['lupo_session'], 'getActorId')) {
        $_lupo_operator_actor_id = (int) $GLOBALS['lupo_session']->getActorId();
    }

    // [STARTUP NEGOTIATION] Persist chattype capability lock in session metadata.
    // Isolated try/catch — promote failure must never kill the message fetch.
    if ($promote && isset($GLOBALS['lupo_session']) && method_exists($GLOBALS['lupo_session'], 'getSessionId')) {
        try {
            $sid = $GLOBALS['lupo_session']->getSessionId();
            if ($sid && class_exists('App\\Auth\\Session') && method_exists('App\\Auth\\Session', 'mergeSessionMetadata')) {
                App\Auth\Session::mergeSessionMetadata($db, $sid, array('chattype' => 'xmlhttp'));
            }
        } catch (Exception $e) {
            // Non-fatal — lock-in is best-effort; JS maintains local lock in state.isAsyncLocked
            error_log('[fetch-messages] chattype promote failed (non-fatal): ' . $e->getMessage());
        }
    }

    // Fetch messages newer than after_time.
    // LIMIT 200: cap delta payload per poll — prevents runaway response on
    // large backlogs. Client advances cursor on each response; catches up gradually.
    $messages = $db->fetchAll(
        "SELECT m.dialog_message_id,
                m.from_actor_id,
                m.to_actor_id,
                m.message_text,
                m.message_type,
                m.created_ymdhis,
                COALESCE(a.name, a.actor_name, 'UNKNOWN') AS actor_display,
                COALESCE(a.is_agent, 0) AS from_is_agent,
                COALESCE(ta.name, ta.actor_name, '') AS to_actor_display,
                COALESCE(t.bg_color, 'fefdcd') AS msg_bg,
                COALESCE(t.text_color, '426446') AS msg_fc,
                COALESCE(t.alt_text_color, '040662') AS msg_ac
         FROM {$prefix}dialog_messages m
         LEFT JOIN {$prefix}actors a ON a.actor_id = m.from_actor_id
         LEFT JOIN {$prefix}actors ta ON ta.actor_id = m.to_actor_id AND ta.is_deleted = 0
         LEFT JOIN {$prefix}dialog_threads t ON t.dialog_thread_id = m.dialog_thread_id
         WHERE m.channel_id = :cid AND m.is_deleted = 0 AND m.created_ymdhis > :at
           AND (m.to_actor_id IS NULL OR m.to_actor_id = :cur_to OR m.from_actor_id = :cur_from)
         ORDER BY m.created_ymdhis ASC
         LIMIT 200",
        array(
            'cid' => $channel_id,
            'at' => $after_time,
            'cur_to' => $_lupo_operator_actor_id,
            'cur_from' => $_lupo_operator_actor_id,
        )
    );

    $last_time = $after_time;
    $formatted = array();

    if ($messages) {
        foreach ($messages as $msg) {
            $last_time = max($last_time, (int) $msg['created_ymdhis']);
            $formatted[] = array(
                'html' => lupo_channel_chat_row_html($msg, $_lupo_operator_actor_id),
                'time' => (int) $msg['created_ymdhis']
            );
        }
    }

    echo json_encode(array(
        'ok' => true,
        'messages' => $formatted,
        'last_time' => $last_time
    ));

} catch (Exception $e) {
    echo json_encode(array('ok' => false, 'error' => $e->getMessage()));
}
