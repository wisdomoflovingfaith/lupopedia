<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "lupo-api/dialog/channel-members.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-api/dialog/channel-members.php"
#   status: "complete"
#   when_updated: "20260417203317"
#   trust_tier: "staging"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/staging/2026/04/channel-members-api.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/channel-members-api"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "02"
#   content_slug: "channel-members-api"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Channel members JSON for route modal"
#   summary: "GET channel_key; returns actor list for channels UI send-to-actor routing."
# ---------------------------------------------------------------------
define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
$UNTRUSTED = array(
    'get' => (isset($_GET) && is_array($_GET)) ? $_GET : array(),
);

require_once LUPOPEDIA_PATH . '/lupo-includes/classes/LupopediaConfigResolver.php';
$lupoResolvedCfg = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, '/');
define('LUPOPEDIA_CONFIG_PATH', $lupoResolvedCfg ?: LUPOPEDIA_PATH . '/lupopedia-config.php');
require_once LUPOPEDIA_CONFIG_PATH;

require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DatabaseFactory.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DialogMvpService.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $db = DatabaseFactory::getConnection();
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    $channel_key = isset($UNTRUSTED['get']['channel_key']) ? trim((string) $UNTRUSTED['get']['channel_key']) : '';
    if ($channel_key === '') {
        echo json_encode(array('ok' => false, 'error' => 'Missing channel_key'));
        exit;
    }

    $actor_id = DialogMvpService::getCurrentActorId($db);
    if ($actor_id <= 0) {
        http_response_code(403);
        echo json_encode(array('ok' => false, 'error' => 'Unauthorized', 'code' => 403));
        exit;
    }

    $ch = $db->fetchRow(
        "SELECT channel_id, channel_key, visibility_status FROM {$prefix}channels
         WHERE channel_key = :ck AND is_deleted = 0 LIMIT 1",
        array('ck' => $channel_key)
    );
    if (!$ch || empty($ch['channel_id'])) {
        http_response_code(404);
        echo json_encode(array('ok' => false, 'error' => 'Channel not found'));
        exit;
    }

    $channel_id = (int) $ch['channel_id'];
    $_chan_key = isset($ch['channel_key']) ? (string) $ch['channel_key'] : '';
    $_is_public = isset($ch['visibility_status']) && $ch['visibility_status'] === 'public';
    $_dev_bypass = (strpos($_chan_key, 'development') !== false || $_chan_key === '');

    if (!$_dev_bypass && !$_is_public) {
        if (!DialogMvpService::actorHasChannelAccess($db, $actor_id, $channel_id)) {
            http_response_code(403);
            echo json_encode(array('ok' => false, 'error' => 'Unauthorized', 'code' => 403));
            exit;
        }
    }

    $rows = DialogMvpService::getChannelMembers($db, $channel_id, 200);
    $members = array();
    if (is_array($rows)) {
        foreach ($rows as $r) {
            $aid = isset($r['actor_id']) ? (int) $r['actor_id'] : 0;
            if ($aid <= 0) {
                continue;
            }
            $nm = '';
            if (isset($r['name']) && trim((string) $r['name']) !== '') {
                $nm = trim((string) $r['name']);
            } elseif (isset($r['actor_name']) && trim((string) $r['actor_name']) !== '') {
                $nm = trim((string) $r['actor_name']);
            } else {
                $nm = 'ACTOR ' . $aid;
            }
            $members[] = array(
                'actor_id' => $aid,
                'label' => strtoupper($nm),
            );
        }
    }

    echo json_encode(array('ok' => true, 'members' => $members));
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('ok' => false, 'error' => $e->getMessage()));
}
