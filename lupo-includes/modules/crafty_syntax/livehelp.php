<?php
/**
 * Visitor chat entry point (legacy livehelp.php equivalent).
 * Preserves legacy behavior using Lupopedia schema. No frameset.
 * - Redirects to choosedepartment when no department.
 * - Creates/updates visitor session (lupo_sessions).
 * - Renders single-page layout with iframe for message stream.
 * All URLs use LUPOPEDIA_PUBLIC_PATH.
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
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

$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><title>Live Help</title></head><body><p>Service unavailable.</p></body></html>';
    exit;
}

$base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
$department = isset($_GET['department']) ? (int)$_GET['department'] : 0;
$website = isset($_GET['website']) ? (int)$_GET['website'] : 0;
$winwidth = isset($_GET['winwidth']) ? (int)$_GET['winwidth'] : 600;
$winheight = isset($_GET['winheight']) ? (int)$_GET['winheight'] : 450;

// Preserve legacy query string for compatibility
$querystringadd = '';
if (!empty($_GET['cslhVISITOR'])) {
    $querystringadd .= '&cslhVISITOR=' . rawurlencode((string)$_GET['cslhVISITOR']);
}
if (!empty($_GET['serversession'])) {
    $querystringadd .= '&serversession=1';
}
if (!empty($_GET['relative'])) {
    $querystringadd .= '&relative=Y';
}
if ($website !== 0) {
    $querystringadd .= '&website=' . $website;
}

// No department → redirect to choosedepartment (legacy behavior)
if ($department === 0 || $department === null) {
    $redirect = $base . 'choosedepartment.php';
    if ($website !== 0) {
        $redirect .= '?website=' . $website;
    }
    header('Location: ' . $redirect, true, 307);
    exit;
}

// Validate department exists (lupo_departments)
$stmt = $db->prepare("SELECT department_id, name FROM {$prefix}departments WHERE department_id = :id AND is_deleted = 0 LIMIT 1");
$stmt->execute(array(':id' => $department));
$dept_row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$dept_row) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><title>Live Help</title></head><body><p>Error: no department with that id.</p></body></html>';
    exit;
}

// Resolve or create visitor session (cslhVISITOR) → lupo_sessions
$session_id = isset($_GET['cslhVISITOR']) ? (string)$_GET['cslhVISITOR'] : '';
if ($session_id === '' && !empty($_COOKIE['cslhVISITOR'])) {
    $session_id = (string)$_COOKIE['cslhVISITOR'];
}
if ($session_id === '') {
    $session_id = 'v' . bin2hex(lupo_random_bytes(12));
}

$now = date('YmdHis');
try {
    $stmt = $db->prepare(
        "INSERT INTO {$prefix}sessions (session_id, federation_node_id, actor_id, ip_address, user_agent, last_seen_ymdhis, created_ymdhis, updated_ymdhis)" .
        " VALUES (:sid, 1, 0, :ip, :ua, :now, :now, :now)" .
        " ON DUPLICATE KEY UPDATE last_seen_ymdhis = :now2, updated_ymdhis = :now3"
    );
    $stmt->execute(array(
        ':sid' => $session_id,
        ':ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0',
        ':ua' => substr(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '', 0, 255),
        ':now' => $now,
        ':now2' => $now,
        ':now3' => $now,
    ));
} catch (Exception $e) {
    // Non-fatal; continue with session_id
}

// Set cookie so subsequent requests carry cslhVISITOR (legacy compatibility). PHP 5.3: no options array.
if (!headers_sent()) {
    $cookie_path = $base !== '' ? $base : '/';
    setcookie('cslhVISITOR', $session_id, 0, $cookie_path, '', false, false);
}

// iframe URL for message stream (visitor chat stream endpoint)
$stream_url = $base . 'visitor-chat-stream.php?department=' . $department . '&cslhVISITOR=' . rawurlencode($session_id) . $querystringadd;

$page_title = 'Live Help - ' . htmlspecialchars($dept_row['name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_title ?></title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: sans-serif; background: #f5f5f5; }
        .livehelp-header { padding: 0.5rem 1rem; background: #fff; border-bottom: 1px solid #ddd; }
        .livehelp-header a { color: #1976d2; text-decoration: none; }
        .livehelp-header a:hover { text-decoration: underline; }
        .livehelp-iframe { width: 100%; height: calc(100vh - 120px); min-height: 300px; border: 0; display: block; background: #fff; }
        .livehelp-footer { padding: 0.5rem 1rem; font-size: 0.875rem; color: #666; }
    </style>
</head>
<body>
    <div class="livehelp-header">
        <strong>Live Help</strong>
        <span> — <?= htmlspecialchars($dept_row['name']) ?></span>
        | <a href="<?= htmlspecialchars($base . 'livehelp.php?action=leave&department=' . $department) ?>">Exit chat</a>
    </div>
    <iframe id="livehelp-stream" class="livehelp-iframe" src="<?= htmlspecialchars($stream_url) ?>" title="Chat messages"></iframe>
    <div class="livehelp-footer">
        Session: <?= htmlspecialchars(substr($session_id, 0, 12)) ?>… — Department: <?= (int)$department ?>
    </div>
    <script>
        (function() {
            var base = <?= json_encode($base) ?>;
            var department = <?= (int)$department ?>;
            var cslhVISITOR = <?= json_encode($session_id) ?>;
            var qs = '&website=<?= (int)$website ?>&winwidth=<?= (int)$winwidth ?>&winheight=<?= (int)$winheight ?>';
            window.LIVEHELP = { base: base, department: department, cslhVISITOR: cslhVISITOR, querystring: qs };
        })();
    </script>
</body>
</html>
