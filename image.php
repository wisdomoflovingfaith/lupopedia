<?php
/**
 * Live Help image endpoint — online/offline icon and control images.
 * Companion to livehelp_js.php. Replicates legacy/craftysyntax/image.php behavior.
 * Uses PDO, new schema (lupo_* from TOONs), LUPOPEDIA_PUBLIC_PATH. No /public folder.
 *
 * @package Lupopedia
 * @see lupo-database/lupopedia/toon/*.toon.json
 */

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', __DIR__);
}
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__));
}

if (!function_exists('lupo_random_bytes')) {
    function lupo_random_bytes($length) {
        if (function_exists('random_bytes')) {
            return random_bytes($length);
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length);
            return $bytes !== false ? $bytes : lupo_random_bytes_fallback($length);
        }
        return lupo_random_bytes_fallback($length);
    }
    function lupo_random_bytes_fallback($length) {
        $bytes = '';
        for ($i = 0; $i < $length; $i++) {
            $bytes .= chr(mt_rand(0, 255));
        }
        return $bytes;
    }
}

if (!defined('LUPOPEDIA_CONFIG_PATH')) {
    require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'LupopediaConfigResolver.php';
    $lupoCfgResolved = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH);
    if ($lupoCfgResolved !== null) {
        define('LUPOPEDIA_CONFIG_PATH', $lupoCfgResolved);
    }
}
if (!defined('LUPOPEDIA_CONFIG_PATH')) {
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '';
    if (file_exists(dirname($docRoot) . '/config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', dirname($docRoot) . '/config.php');
    } elseif (file_exists(dirname($docRoot) . LUPOPEDIA_PUBLIC_PATH . '/config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', dirname($docRoot) . LUPOPEDIA_PUBLIC_PATH . '/config.php');
    } elseif (@file_exists(LUPOPEDIA_PATH . '/config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', LUPOPEDIA_PATH . '/config.php');
    }
}
if (!defined('LUPOPEDIA_CONFIG_PATH') || !is_file(LUPOPEDIA_CONFIG_PATH)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
require_once LUPOPEDIA_CONFIG_PATH;
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    define('LUPOPEDIA_CONFIG_LOADED', true);
}

require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes/pdo_db.php';
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes/DatabaseFactory.php';

try {
    $mydatabase = DatabaseFactory::getConnection();
} catch (Exception $e) {
    header('HTTP/1.0 503 Service Unavailable');
    exit;
}

$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

$UNTRUSTED = array(
    'what' => isset($_GET['what']) ? (string) $_GET['what'] : '',
    'cmd'  => isset($_GET['cmd']) ? (string) $_GET['cmd'] : '',
    'department' => isset($_GET['department']) ? (string) $_GET['department'] : '',
    'hide' => isset($_GET['hide']) ? (string) $_GET['hide'] : '',
    'towhat' => isset($_GET['towhat']) ? (string) $_GET['towhat'] : '',
    'whatplace' => isset($_GET['whatplace']) ? (string) $_GET['whatplace'] : '',
    'page' => isset($_GET['page']) ? (string) $_GET['page'] : '',
    'pageid' => isset($_GET['pageid']) ? (string) $_GET['pageid'] : '',
    'title' => isset($_GET['title']) ? (string) $_GET['title'] : '',
    'referer' => isset($_GET['referer']) ? (string) $_GET['referer'] : '',
);
if (!empty($UNTRUSTED['cmd'])) {
    $UNTRUSTED['what'] = $UNTRUSTED['cmd'];
}
if (empty($UNTRUSTED['what'])) {
    $UNTRUSTED['what'] = 'getstate';
}

$session_id = isset($_GET['cslhVISITOR']) ? (string) $_GET['cslhVISITOR'] : '';
if ($session_id === '' && !empty($_COOKIE['cslhVISITOR'])) {
    $session_id = (string) $_COOKIE['cslhVISITOR'];
}
if ($session_id === '') {
    $session_id = 'v' . bin2hex(lupo_random_bytes(12));
}
$identity = array('SESSIONID' => $session_id);

$department = !empty($UNTRUSTED['department']) ? (int) $UNTRUSTED['department'] : 0;
if ($department === 0) {
    $row = $mydatabase->fetchRow("SELECT department_id FROM {$prefix}departments WHERE is_deleted = 0 ORDER BY department_id ASC LIMIT 1");
    $department = $row ? (int) $row['department_id'] : 0;
}

$leaveamessage = 'YES';
if ($department > 0) {
    $deptRow = $mydatabase->fetchRow("SELECT settings_json FROM {$prefix}departments WHERE department_id = :id AND is_deleted = 0", array('id' => $department));
    if ($deptRow !== null && !empty($deptRow['settings_json'])) {
        $json = is_string($deptRow['settings_json']) ? json_decode($deptRow['settings_json'], true) : $deptRow['settings_json'];
        if (is_array($json) && isset($json['leaveamessage'])) {
            $leaveamessage = (strtoupper((string) $json['leaveamessage']) === 'NO') ? 'NO' : 'YES';
        }
    }
}
if (!empty($_GET['leaveamessage'])) {
    $leaveamessage = (strtoupper((string) $_GET['leaveamessage']) === 'NO') ? 'NO' : 'YES';
}

/**
 * Send image from project images folder. No absolute paths; path relative to LUPOPEDIA_PATH.
 */
function send_image($filepath, $mime = 'image/gif') {
    $base = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : __DIR__;
    $safe = basename($filepath);
    if ($safe === '' || strpos($safe, '..') !== false) {
        $safe = 'blank.gif';
    }
    $full = $base . DIRECTORY_SEPARATOR . 'lupo-images' . DIRECTORY_SEPARATOR . $safe;
    if (!is_file($full) || !is_readable($full)) {
        $full = $base . DIRECTORY_SEPARATOR . 'lupo-images' . DIRECTORY_SEPARATOR . 'blank.gif';
    }
    header('Content-Type: ' . $mime);
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    readfile($full);
}

/**
 * Anyone online in this department (or any) = session with recent last_seen + actor_channel_role (captain/monitor/administrator).
 */
function is_anyone_online($db, $prefix, $department_id) {
    if (!class_exists('timestamp_ymdhis', false)) {
        require_once (defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : __DIR__) . '/lupo-includes/classes/TimestampYmdhis.php';
    }
    $cutoff = (string) timestamp_ymdhis::subtractSeconds(timestamp_ymdhis::now(), 20 * 60);
    if ($department_id !== 0) {
        $row = $db->fetchRow(
            "SELECT 1 FROM {$prefix}sessions s " .
            "INNER JOIN {$prefix}actor_channel_roles r ON r.actor_id = s.actor_id AND r.is_deleted = 0 " .
            "INNER JOIN {$prefix}channels c ON c.channel_id = r.channel_id AND c.is_deleted = 0 " .
            "WHERE s.is_active = 1 AND s.is_expired = 0 AND s.last_seen_ymdhis >= :cutoff " .
            "AND r.role_key IN ('captain','monitor','administrator') AND c.department_id = :dept LIMIT 1",
            array('cutoff' => $cutoff, 'dept' => $department_id)
        );
    } else {
        $row = $db->fetchRow(
            "SELECT 1 FROM {$prefix}sessions s " .
            "INNER JOIN {$prefix}actor_channel_roles r ON r.actor_id = s.actor_id AND r.is_deleted = 0 " .
            "INNER JOIN {$prefix}channels c ON c.channel_id = r.channel_id AND c.is_deleted = 0 " .
            "WHERE s.is_active = 1 AND s.is_expired = 0 AND s.last_seen_ymdhis >= :cutoff " .
            "AND r.role_key IN ('captain','monitor','administrator') LIMIT 1",
            array('cutoff' => $cutoff)
        );
    }
    return $row !== null;
}

// ----- getlayerinvite: digit image for ones/tens/hundreds from session invite id
if ($UNTRUSTED['what'] === 'getlayerinvite') {
    $filepath = 'lupo-images/requestDHTML.gif';
    $visitorRow = $mydatabase->fetchRow(
        "SELECT session_data FROM {$prefix}sessions WHERE session_id = :sid AND is_deleted = 0 LIMIT 1",
        array('sid' => $identity['SESSIONID'])
    );
    if ($visitorRow !== null && !empty($visitorRow['session_data'])) {
        $datapairs = explode('&', (string) $visitorRow['session_data']);
        foreach ($datapairs as $pair) {
            $dataset = explode('=', $pair, 2);
            if (isset($dataset[1]) && $dataset[0] === 'invite') {
                $layerid = (int) $dataset[1];
                $hundreds = (int) floor($layerid / 100);
                $tens = (int) floor(($layerid - $hundreds * 100) / 10);
                $ones = $layerid - $hundreds * 100 - $tens * 10;
                $digit = 0;
                if ($UNTRUSTED['whatplace'] === 'ones') $digit = $ones;
                if ($UNTRUSTED['whatplace'] === 'tens') $digit = $tens;
                if ($UNTRUSTED['whatplace'] === 'hundreds') $digit = $hundreds;
                $filepath = 'lupo-images/digit' . (int) $digit . '.gif';
                break;
            }
        }
    }
    send_image($filepath);
    exit;
}

// ----- changestat: update visitor status (invited/stopped), return browse.gif
if ($UNTRUSTED['what'] === 'changestat') {
    $towhat = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $UNTRUSTED['towhat']);
    if ($towhat !== '') {
        $sess = $mydatabase->fetchRow("SELECT session_data FROM {$prefix}sessions WHERE session_id = :sid AND is_deleted = 0 LIMIT 1", array('sid' => $identity['SESSIONID']));
        $data = $sess !== null && $sess['session_data'] !== null && $sess['session_data'] !== '' ? $sess['session_data'] : '';
        $pairs = array();
        foreach (explode('&', $data) as $p) {
            $kv = explode('=', $p, 2);
            if (isset($kv[0]) && $kv[0] !== 'status') {
                $pairs[$kv[0]] = isset($kv[1]) ? $kv[1] : '';
            }
        }
        $pairs['status'] = $towhat;
        $newData = array();
        foreach ($pairs as $k => $v) {
            $newData[] = $k . '=' . $v;
        }
        $mydatabase->query(
            "UPDATE {$prefix}sessions SET session_data = :data, last_seen_ymdhis = :now, updated_ymdhis = :now WHERE session_id = :sid",
            array('data' => implode('&', $newData), 'now' => gmdate('YmdHis'), 'sid' => $identity['SESSIONID'])
        );
    }
    send_image('lupo-images/browse.gif');
    exit;
}

// ----- browse: static browse image
if ($UNTRUSTED['what'] === 'browse') {
    send_image('lupo-images/browse.gif');
    exit;
}

// ----- userstat: control image (requestchat / requestDHTML / browse) and update last_seen
if ($UNTRUSTED['what'] === 'userstat') {
    $rightnow = gmdate('YmdHis');
    $visitorRow = $mydatabase->fetchRow(
        "SELECT actor_id, session_data FROM {$prefix}sessions WHERE session_id = :sid AND is_deleted = 0 LIMIT 1",
        array('sid' => $identity['SESSIONID'])
    );
    $status = '';
    if ($visitorRow !== null) {
        $mydatabase->query(
            "UPDATE {$prefix}sessions SET last_seen_ymdhis = :now, updated_ymdhis = :now WHERE session_id = :sid",
            array('now' => $rightnow, 'sid' => $identity['SESSIONID'])
        );
        if (!empty($visitorRow['session_data'])) {
            foreach (explode('&', (string) $visitorRow['session_data']) as $p) {
                $kv = explode('=', $p, 2);
                if (isset($kv[0]) && $kv[0] === 'status' && isset($kv[1])) {
                    $status = $kv[1];
                    break;
                }
            }
        }
    }
    if ($status === 'request') {
        send_image('lupo-images/requestchat.gif');
        exit;
    }
    if ($status === 'DHTML' || $status === 'invited') {
        send_image('lupo-images/requestDHTML.gif');
        exit;
    }
    send_image('lupo-images/browse.gif');
    exit;
}

// ----- getcredit: credit line icon (online/offline by xy L/W/Y/Z/N and hide)
if ($UNTRUSTED['what'] === 'getcredit') {
    $xyz = isset($_GET['xy']) ? substr((string) $_GET['xy'], 0, 1) : 'L';
    if ($xyz === 'N') {
        if ($department > 0) {
            $d = $mydatabase->fetchRow("SELECT settings_json FROM {$prefix}departments WHERE department_id = :id AND is_deleted = 0", array('id' => $department));
            if ($d !== null && !empty($d['settings_json'])) {
                $j = is_string($d['settings_json']) ? json_decode($d['settings_json'], true) : $d['settings_json'];
                if (is_array($j) && isset($j['creditline'])) {
                    $xyz = substr((string) $j['creditline'], 0, 1);
                }
            }
        }
    }
    $noonehome = !is_anyone_online($mydatabase, $prefix, $department);
    $hide = ($UNTRUSTED['hide'] === 'Y');

    if (!$noonehome) {
        if (($xyz === 'N') || $hide) {
            send_image('lupo-images/blank.gif');
            exit;
        }
        if (($xyz === 'L') || $xyz === '') { send_image('lupo-images/livehelp.gif'); exit; }
        if ($xyz === 'W') { send_image('lupo-images/livehelp2.gif'); exit; }
        if ($xyz === 'Y') { send_image('lupo-images/livehelp4.gif'); exit; }
        if ($xyz === 'Z') { send_image('lupo-images/livehelp5.gif'); exit; }
        send_image('lupo-images/livehelp.gif');
        exit;
    }

    if ($leaveamessage === 'YES') {
        if (($xyz === 'N') || $hide) {
            send_image('lupo-images/blank.gif');
            exit;
        }
        if (($xyz === 'L') || $xyz === '') { send_image('lupo-images/livehelp.gif'); exit; }
        if ($xyz === 'W') { send_image('lupo-images/livehelp2.gif'); exit; }
        if ($xyz === 'Y') { send_image('lupo-images/livehelp4.gif'); exit; }
        if ($xyz === 'Z') { send_image('lupo-images/livehelp5.gif'); exit; }
        send_image('lupo-images/livehelp.gif');
        exit;
    }

    if (($xyz === 'N') || $hide) {
        send_image('lupo-images/blank.gif');
        exit;
    }
    send_image('lupo-images/livehelp3.gif');
    exit;
}

// ----- getstate: online/offline icon for main live help button
if ($UNTRUSTED['what'] === 'getstate') {
    if ($UNTRUSTED['hide'] === 'Y') {
        send_image('lupo-images/livehelp3.gif');
        exit;
    }
    $noonehome = !is_anyone_online($mydatabase, $prefix, $department);
    if (!$noonehome) {
        send_image('lupo-images/livehelp.gif');
        exit;
    }
    if ($leaveamessage === 'YES') {
        send_image('lupo-images/livehelp3.gif');
        exit;
    }
    send_image('lupo-images/livehelp3.gif');
    exit;
}

// Default: offline icon
send_image('lupo-images/livehelp3.gif');
