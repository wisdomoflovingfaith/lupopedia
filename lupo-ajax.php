<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: api
  when_updated: "20260406012335"
  file_path_from_root: "lupo-ajax.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-ajax.php"
  questions_toon: null
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "api"
  artifact_kind: "ajax_endpoint"
  purpose: "Eye API — PDO_DB, UNTRUSTED, Model A CSRF from lupo_sessions; no MySQLi."
  tags: ["api", "eye", "tracking", "ajax", "pdo_db"]
---
*/

define('LUPOPEDIA_PATH', __DIR__);
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'LupopediaConfigResolver.php';
define('LUPOPEDIA_PUBLIC_PATH', LupopediaConfigResolver::publicPathFromRequest(LUPOPEDIA_PATH));

$UNTRUSTED = array(
    'get' => (isset($_GET) && is_array($_GET)) ? $_GET : array(),
    'post' => (isset($_POST) && is_array($_POST)) ? $_POST : array(),
    'server' => (isset($_SERVER) && is_array($_SERVER)) ? $_SERVER : array(),
    'cookie' => (isset($_COOKIE) && is_array($_COOKIE)) ? $_COOKIE : array(),
);

$lupoRawInput = @file_get_contents('php://input');
if ($lupoRawInput === false) {
    $lupoRawInput = '';
}
$UNTRUSTED['input_json'] = array();
if ($lupoRawInput !== '') {
    $lupoDecoded = json_decode($lupoRawInput, true);
    $UNTRUSTED['input_json'] = is_array($lupoDecoded) ? $lupoDecoded : array();
}

require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'LupopediaConfigResolver.php';
$lupopediaConfigPath = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH);

if (!$lupopediaConfigPath) {
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Configuration file not found'));
    exit;
}

require_once $lupopediaConfigPath;

if (function_exists('error_reporting')) {
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        ini_set('log_errors', '0');
        ini_set('html_errors', '1');
    } else {
        error_reporting(E_ALL & ~E_DEPRECATED);
        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
        ini_set('log_errors', '1');
        ini_set('html_errors', '0');
    }
}

if (!defined('LUPOPEDIA_SUBDIRECTORY')) {
    if (isset($UNTRUSTED['server']['SCRIPT_NAME'])) {
        $script_path = $UNTRUSTED['server']['SCRIPT_NAME'];
        $subdir = dirname($script_path);
        if ($subdir === '/' || $subdir === '\\') {
            $subdir = '';
        }
        define('LUPOPEDIA_SUBDIRECTORY', rtrim($subdir, '/') . '/');
    } else {
        define('LUPOPEDIA_SUBDIRECTORY', LUPOPEDIA_PUBLIC_PATH);
    }
}

define('EYE_WIDGET_ENABLED', true);
define('EYE_TRACKING_LEVEL', 'full');
define('LUPO_GOLD_CONTEXT_WEIGHT_MIN', 0.8);
define('LUPO_GOLD_EDGE_WEIGHT_MIN', 0.5);
define('EYE_MAX_GRAPH_NODES', 200);
define('EYE_MAX_GRAPH_EDGES', 500);

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', defined('DB_PREFIX') ? DB_PREFIX : 'lupo_');
}

header('Content-Type: application/json');

$httpHost = isset($UNTRUSTED['server']['HTTP_HOST']) ? $UNTRUSTED['server']['HTTP_HOST'] : '';
$allowed_origins = array(
    'https://' . $httpHost,
    'http://' . $httpHost,
);
if (isset($UNTRUSTED['server']['HTTP_ORIGIN']) && in_array($UNTRUSTED['server']['HTTP_ORIGIN'], $allowed_origins, true)) {
    header('Access-Control-Allow-Origin: ' . $UNTRUSTED['server']['HTTP_ORIGIN']);
    header('Access-Control-Allow-Credentials: true');
}

require_once LUPOPEDIA_PATH . '/includes/classes/DatabaseFactory.php';
require_once LUPOPEDIA_PATH . '/includes/classes/IdGenerator.php';

/**
 * PDO_DB connection (Doctrine: DatabaseFactory only).
 *
 * @return PDO_DB|null
 */
function lupo_ajax_get_db()
{
    static $db = null;
    static $failed = false;
    if ($failed) {
        return null;
    }
    if ($db !== null) {
        return $db;
    }
    try {
        $db = DatabaseFactory::getConnection();
    } catch (Exception $e) {
        $failed = true;
        error_log('lupo-ajax.php DB: ' . $e->getMessage());
        return null;
    }
    return $db;
}

/**
 * @param array $server  $UNTRUSTED['server']
 */
function get_client_ip($server)
{
    if (!is_array($server)) {
        $server = array();
    }
    $trusted_proxies = array(
        '127.0.0.1',
        '::1',
        '10.0.0.0/8',
        '172.16.0.0/12',
        '192.168.0.0/16',
        '173.245.48.0/20',
        '103.21.244.0/22',
        '103.22.200.0/22',
        '103.31.4.0/22',
        '141.101.64.0/18',
        '108.162.192.0/18',
        '190.93.240.0/20',
        '188.114.96.0/20',
        '197.234.240.0/22',
        '198.41.128.0/17',
        '162.158.0.0/15',
        '104.16.0.0/13',
        '104.24.0.0/14',
        '172.64.0.0/13',
        '131.0.72.0/22',
        '23.235.32.0/20',
        '43.249.72.0/22',
        '103.244.50.0/24',
        '103.245.222.0/23',
        '103.245.224.0/24',
        '104.156.80.0/20',
        '146.75.0.0/16',
        '151.101.0.0/16',
        '157.52.64.0/18',
        '167.82.0.0/17',
        '167.82.128.0/17',
        '185.31.16.0/22',
        '199.27.72.0/21',
        '199.232.0.0/16',
        '13.32.0.0/15',
        '13.224.0.0/14',
        '13.248.0.0/14',
        '64.252.64.0/18',
        '70.132.0.0/18',
        '71.152.0.0/17',
        '99.84.0.0/16',
        '99.86.0.0/16',
        '108.138.0.0/15',
        '108.156.0.0/14',
        '130.176.0.0/16',
        '144.220.0.0/16',
        '204.246.160.0/19',
        '204.246.192.0/18',
        '205.251.192.0/19',
        '205.251.224.0/20',
        '205.251.240.0/20',
        '205.251.249.0/24',
        '216.137.32.0/19',
        '2.16.0.0/13',
        '2.20.0.0/14',
        '23.0.0.0/12',
    );

    $headers = array(
        'HTTP_CF_CONNECTING_IP',
        'HTTP_TRUE_CLIENT_IP',
        'HTTP_FASTLY_CLIENT_IP',
        'HTTP_FLY_CLIENT_IP',
        'HTTP_X_VERCEL_FORWARDED_FOR',
        'HTTP_CDN_CONNECTING_IP',
        'HTTP_BUNNY_CDN_CONNECTING_IP',
        'HTTP_CLOUDFRONT_VIEWER_ADDRESS',
        'HTTP_X_GOOG_REAL_IP',
        'HTTP_X_AZURE_CLIENTIP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_REAL_IP',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'HTTP_CLIENT_IP',
        'REMOTE_ADDR',
    );

    $remote_addr = isset($server['REMOTE_ADDR']) ? $server['REMOTE_ADDR'] : '';
    $is_trusted = is_ip_in_trusted_ranges($remote_addr, $trusted_proxies);
    $fallback = '';

    foreach ($headers as $header) {
        if (empty($server[$header])) {
            continue;
        }
        $raw = $server[$header];

        if (!$is_trusted && $header !== 'REMOTE_ADDR') {
            continue;
        }

        if ($header === 'HTTP_CLOUDFRONT_VIEWER_ADDRESS') {
            $parts = explode(':', $raw, 2);
            $candidates = array(trim($parts[0]));
        } elseif ($header === 'HTTP_X_FORWARDED_FOR' || $header === 'HTTP_FORWARDED_FOR') {
            $candidates = array_map('trim', explode(',', $raw));
        } elseif ($header === 'HTTP_FORWARDED') {
            $candidates = extract_forwarded_ips($raw);
        } else {
            $candidates = array(trim($raw));
        }

        foreach ($candidates as $candidate) {
            if ($candidate === '') {
                continue;
            }
            $candidate = trim($candidate, '[]');
            if (filter_var($candidate, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $candidate;
            }
            if ($fallback === '' && filter_var($candidate, FILTER_VALIDATE_IP)) {
                $fallback = $candidate;
            }
        }
    }

    return $fallback !== '' ? $fallback : $remote_addr;
}

function extract_forwarded_ips($forwarded_header)
{
    $ips = array();
    preg_match_all('/for\s*=\s*"?([^",\s]+)"?/', $forwarded_header, $matches);
    foreach ($matches[1] as $match) {
        $ips[] = trim($match, '"');
    }
    return $ips;
}

function is_ip_in_trusted_ranges($ip, $ranges)
{
    if ($ip === '') {
        return false;
    }
    foreach ($ranges as $range) {
        if (strpos($range, '/') === false) {
            if ($ip === $range) {
                return true;
            }
            continue;
        }
        $parts = explode('/', $range);
        $subnet = $parts[0];
        $mask = isset($parts[1]) ? (int) $parts[1] : 32;
        $ip_long = ip2long($ip);
        $subnet_long = ip2long($subnet);
        if ($ip_long === false || $subnet_long === false) {
            continue;
        }
        $mask_long = ~((1 << (32 - $mask)) - 1);
        if (($ip_long & $mask_long) == ($subnet_long & $mask_long)) {
            return true;
        }
    }
    return false;
}

$rate_limits = array(
    'track' => array('limit' => 100, 'window' => 60),
    'consent' => array('limit' => 10, 'window' => 300),
    'config' => array('limit' => 5, 'window' => 300),
    'heartbeat' => array('limit' => 10, 'window' => 60),
);

/**
 * CSRF from lupo_sessions (Model A). Bootstrap must have started PHP session cookie.
 *
 * @param string $token
 * @return bool
 */
function lupo_ajax_verify_csrf_token($token)
{
    $db = lupo_ajax_get_db();
    if (!$db) {
        return false;
    }
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
 * Rate buckets in $_SESSION (bootstrap-owned PHP session only; not identity authority).
 *
 * @param string $ip
 * @param string $action
 * @param array  $rate_limits
 * @param int|null $limit
 * @param int|null $window
 * @return bool
 */
function check_rate_limit($ip, $action, $rate_limits, $limit = null, $window = null)
{
    if ($limit === null) {
        $config = isset($rate_limits[$action]) ? $rate_limits[$action] : array('limit' => 100, 'window' => 60);
        $limit = isset($config['limit']) ? $config['limit'] : 100;
        $window = isset($config['window']) ? $config['window'] : 60;
    }

    if (!class_exists('timestamp_ymdhis', false)) {
        require_once LUPOPEDIA_PATH . '/includes/classes/TimestampYmdhis.php';
    }

    $key = 'eye_rate_' . md5($ip . '_' . $action);

    if (!isset($_SESSION) || !is_array($_SESSION)) {
        return true;
    }

    if (!isset($_SESSION[$key]) || !isset($_SESSION[$key]['start_ymdhis']) || isset($_SESSION[$key]['start'])) {
        $blocked = (isset($_SESSION[$key]['blocked']) && $_SESSION[$key]['blocked']);
        $_SESSION[$key] = array('count' => 0, 'start_ymdhis' => timestamp_ymdhis::now(), 'blocked' => $blocked);
    }

    $current = $_SESSION[$key];

    if (timestamp_ymdhis::diffInSeconds(timestamp_ymdhis::now(), (int) $current['start_ymdhis']) > $window) {
        $blocked = isset($current['blocked']) ? $current['blocked'] : false;
        $_SESSION[$key] = array('count' => 0, 'start_ymdhis' => timestamp_ymdhis::now(), 'blocked' => $blocked);
        $current = $_SESSION[$key];
    }

    if (!empty($current['blocked'])) {
        return false;
    }

    if ($current['count'] >= $limit) {
        return false;
    }

    $_SESSION[$key]['count']++;
    return true;
}

function get_current_utc()
{
    return gmdate('YmdHis');
}

function replace_prefix($sql)
{
    return str_replace('{{prefix}}', LUPO_TABLE_PREFIX, $sql);
}

$action = isset($UNTRUSTED['get']['action']) ? $UNTRUSTED['get']['action'] : '';

switch ($action) {
    case 'csrf_token':
        $db = lupo_ajax_get_db();
        if (!$db) {
            http_response_code(500);
            echo json_encode(array('error' => 'Database unavailable'));
            break;
        }
        $token = null;
        if (isset($GLOBALS['lupo_session']) && is_object($GLOBALS['lupo_session'])) {
            $token = $GLOBALS['lupo_session']->getCsrfToken();
            if (($token === null || $token === '') && $GLOBALS['lupo_session']->getSessionId()) {
                $loaded = \App\Auth\Session::loadById($db, $GLOBALS['lupo_session']->getSessionId());
                if ($loaded && !empty($loaded->csrf_token)) {
                    $token = $loaded->csrf_token;
                }
            }
        }
        if ($token === null || $token === '') {
            http_response_code(500);
            echo json_encode(array('error' => 'Could not issue CSRF token'));
            break;
        }
        echo json_encode(array('csrf_token' => $token));
        break;

    case 'track':
        $csrf_token = '';
        if (isset($UNTRUSTED['post']['csrf_token'])) {
            $csrf_token = (string) $UNTRUSTED['post']['csrf_token'];
        }
        if ($csrf_token === '' && isset($UNTRUSTED['server']['HTTP_X_CSRF_TOKEN'])) {
            $csrf_token = (string) $UNTRUSTED['server']['HTTP_X_CSRF_TOKEN'];
        }
        if (!lupo_ajax_verify_csrf_token($csrf_token)) {
            http_response_code(403);
            echo json_encode(array('error' => 'Invalid CSRF token'));
            exit;
        }

        if (!check_rate_limit(get_client_ip($UNTRUSTED['server']), $action, $rate_limits)) {
            http_response_code(429);
            $w = isset($rate_limits[$action]['window']) ? $rate_limits[$action]['window'] : 60;
            echo json_encode(array('error' => 'Rate limit exceeded', 'retry_after' => $w));
            exit;
        }

        $db = lupo_ajax_get_db();
        if (!$db) {
            http_response_code(500);
            echo json_encode(array('error' => 'Database unavailable'));
            exit;
        }

        $session_token = isset($UNTRUSTED['cookie']['lupo_session']) ? (string) $UNTRUSTED['cookie']['lupo_session'] : '';
        if ($session_token === '') {
            $created = \App\Auth\Session::createEmbedSession($db, 0);
            if (!$created) {
                http_response_code(500);
                echo json_encode(array('error' => 'Could not create visitor session'));
                exit;
            }
            $session_token = $created->session_id;
            $cookiePath = LUPOPEDIA_SUBDIRECTORY;
            $secure = isset($UNTRUSTED['server']['HTTPS']) && $UNTRUSTED['server']['HTTPS'] !== ''
                && strtolower((string) $UNTRUSTED['server']['HTTPS']) !== 'off';
            setcookie('lupo_session', $session_token, time() + 86400 * 30, $cookiePath, '', $secure, true);
        }

        $input = $UNTRUSTED['input_json'];
        $page_url_raw = isset($input['page_url']) ? $input['page_url'] : '';
        if ($page_url_raw === '' && isset($UNTRUSTED['server']['HTTP_REFERER'])) {
            $page_url_raw = $UNTRUSTED['server']['HTTP_REFERER'];
        }
        $page_url = filter_var($page_url_raw, FILTER_SANITIZE_URL);
        if ($page_url === false) {
            $page_url = '';
        }
        $actor_id_int = isset($input['actor_id']) ? (int) $input['actor_id'] : 0;
        $actor_id = ($actor_id_int > 0) ? $actor_id_int : null;
        $referrer_raw = isset($input['referrer']) ? $input['referrer'] : '';
        $referrer = filter_var($referrer_raw, FILTER_SANITIZE_URL);
        if ($referrer === false) {
            $referrer = '';
        }
        $meta = array();
        if ($referrer !== '') {
            $meta['referrer'] = $referrer;
        }
        if (!empty($input['campaign']) && is_string($input['campaign'])) {
            $meta['campaign'] = substr(preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $input['campaign']), 0, 128);
        }
        $transition_metadata = empty($meta) ? '' : json_encode($meta);
        $transition_type = 'page_view';

        $visit_id = IdGenerator::generate();
        $prefix = LUPO_TABLE_PREFIX;
        $now = (int) get_current_utc();

        try {
            $db->query(
                "INSERT INTO {$prefix}visits (visit_id, session_id, actor_id, path_url, transition_type, transition_metadata, created_ymdhis, is_processed, is_deleted) 
                 VALUES (:visit_id, :session_id, :actor_id, :path_url, :transition_type, :transition_metadata, :created_ymdhis, 0, 0)",
                array(
                    'visit_id' => $visit_id,
                    'session_id' => null,
                    'actor_id' => $actor_id,
                    'path_url' => $page_url,
                    'transition_type' => $transition_type,
                    'transition_metadata' => $transition_metadata,
                    'created_ymdhis' => $now,
                )
            );
        } catch (Exception $e) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('lupo-ajax track: ' . $e->getMessage());
            }
            http_response_code(500);
            echo json_encode(array('error' => 'Failed to track visit'));
            exit;
        }

        echo json_encode(array('success' => true, 'tracked' => 1, 'visit_id' => $visit_id));
        break;

    case 'context':
        $page_id = isset($UNTRUSTED['get']['page_id']) ? (int) $UNTRUSTED['get']['page_id'] : 0;
        if ($page_id <= 0) {
            http_response_code(400);
            echo json_encode(array('error' => 'page_id required'));
            break;
        }

        $db = lupo_ajax_get_db();
        if (!$db) {
            http_response_code(500);
            echo json_encode(array('error' => 'Database unavailable'));
            break;
        }

        $lim = defined('EYE_MAX_GRAPH_EDGES') ? (int) EYE_MAX_GRAPH_EDGES : 200;
        if ($lim < 1) {
            $lim = 1;
        }
        if ($lim > 10000) {
            $lim = 10000;
        }
        $prefix = LUPO_TABLE_PREFIX;
        $sql = "SELECT left_object_id, right_object_id, edge_type, semantic_weight 
                FROM {$prefix}edges 
                WHERE left_object_type = 'content' AND left_object_id = :pid 
                AND is_deleted = 0 
                ORDER BY semantic_weight DESC 
                LIMIT " . $lim;
        $edges = $db->fetchAll($sql, array('pid' => $page_id));

        echo json_encode(array(
            'success' => true,
            'edges' => $edges,
            'count' => count($edges),
        ));
        break;

    case 'gold':
        $db = lupo_ajax_get_db();
        if (!$db) {
            http_response_code(500);
            echo json_encode(array('error' => 'Database unavailable'));
            break;
        }
        $threshold = defined('LUPO_GOLD_CONTEXT_WEIGHT_MIN') ? LUPO_GOLD_CONTEXT_WEIGHT_MIN : 0.8;
        $prefix = LUPO_TABLE_PREFIX;
        $sql = "SELECT context_id, context_name, weight_score 
                FROM {$prefix}contexts 
                WHERE weight_score >= :th AND is_deleted = 0 
                ORDER BY weight_score DESC 
                LIMIT 50";
        $contexts = $db->fetchAll($sql, array('th' => $threshold));

        echo json_encode(array(
            'success' => true,
            'contexts' => $contexts,
            'threshold' => $threshold,
        ));
        break;

    case 'consent':
        $consent_action = isset($UNTRUSTED['post']['consent_action']) ? (string) $UNTRUSTED['post']['consent_action'] : '';
        $cookiePath = LUPOPEDIA_SUBDIRECTORY;
        if ($consent_action === 'grant') {
            setcookie('lupo_consent', '1', time() + 365 * 86400, $cookiePath, '', false, false);
            echo json_encode(array('success' => true, 'consent' => 'granted'));
        } elseif ($consent_action === 'revoke') {
            setcookie('lupo_consent', '', time() - 3600, $cookiePath, '', false, false);
            echo json_encode(array('success' => true, 'consent' => 'revoked'));
        } else {
            echo json_encode(array('consent' => !empty($UNTRUSTED['cookie']['lupo_consent'])));
        }
        break;

    case 'heartbeat':
        $hb_sid = isset($UNTRUSTED['cookie']['lupo_session']) ? (string) $UNTRUSTED['cookie']['lupo_session'] : '';
        $db = lupo_ajax_get_db();
        if ($hb_sid !== '' && $db) {
            $loaded = \App\Auth\Session::loadById($db, $hb_sid);
            if ($loaded) {
                $loaded->touch();
                echo json_encode(array('success' => true, 'session_valid' => true));
                break;
            }
        }
        echo json_encode(array('success' => false, 'session_valid' => false));
        break;

    default:
        http_response_code(400);
        echo json_encode(array('error' => 'Unknown action: ' . htmlspecialchars($action)));
}
