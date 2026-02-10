<?php
/**
 * Visitor image endpoint (legacy image.php equivalent).
 * Serves getstate (online/offline icon) and getcredit (credit line image)
 * using Lupopedia schema: lupo_channel_roles + lupo_channels for staffed departments, lupo_department_metadata.
 * All paths use LUPOPEDIA_PUBLIC_PATH for URLs.
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$db = $GLOBALS['mydatabase'] ?? null;
if (!$db) {
    header('Content-Type: image/gif');
    header('HTTP/1.0 500 Internal Server Error');
    exit;
}

$what = isset($_GET['what']) ? (string)$_GET['what'] : 'getstate';
if ($what === 'getcredit') {
    $ghost_session = true;
}
$department = isset($_GET['department']) ? (int)$_GET['department'] : 0;

// userstat: visitor ping / control image (legacy: returns browse.gif or request*; we return no-action gif)
if ($what === 'userstat') {
    $session_id = isset($_GET['cslhVISITOR']) ? (string)$_GET['cslhVISITOR'] : '';
    if ($session_id !== '') {
        $now = date('YmdHis');
        try {
            $stmt = $db->prepare(
                "UPDATE {$prefix}sessions SET last_seen_ymdhis = :now, updated_ymdhis = :now WHERE session_id = :sid"
            );
            $stmt->execute([':now' => $now, ':sid' => $session_id]);
        } catch (Throwable $e) {
            // ignore
        }
    }
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '');
    $legacy_images = $app_root . DIRECTORY_SEPARATOR . 'legacy' . DIRECTORY_SEPARATOR . 'craftysyntax' . DIRECTORY_SEPARATOR;
    $filepath = $legacy_images . 'images' . DIRECTORY_SEPARATOR . 'browse.gif';
    if (!is_file($filepath)) {
        $filepath = $legacy_images . 'images' . DIRECTORY_SEPARATOR . 'controlimage_noaction.gif';
    }
    if (!is_file($filepath)) {
        header('Content-Type: image/gif');
        header('Content-Length: 0');
        exit;
    }
    header('Content-Type: image/gif');
    header('Cache-Control: no-cache, must-revalidate');
    readfile($filepath);
    exit;
}

// Resolve visitor session id (legacy cslhVISITOR)
$session_id = isset($_GET['cslhVISITOR']) ? (string)$_GET['cslhVISITOR'] : '';
if ($session_id === '' && !empty($_COOKIE['cslhVISITOR'])) {
    $session_id = (string)$_COOKIE['cslhVISITOR'];
}
if ($session_id === '') {
    $session_id = 'v' . bin2hex(random_bytes(12));
}

// Optional: touch lupo_sessions for this visitor (lightweight tracking)
$now = date('YmdHis');
if ($session_id !== '' && $what === 'getstate') {
    $page = isset($_GET['page']) ? substr((string)$_GET['page'], 0, 250) : '';
    $referer = isset($_GET['referer']) ? substr((string)$_GET['referer'], 0, 250) : '';
    $title = isset($_GET['title']) ? substr((string)$_GET['title'], 0, 100) : '';
    try {
        $stmt = $db->prepare(
            "INSERT INTO {$prefix}sessions (session_id, federation_node_id, actor_id, ip_address, user_agent, name_key, is_named, last_seen_ymdhis, created_ymdhis, updated_ymdhis)" .
            " VALUES (:sid, 1, 0, :ip, :ua, NULL, 0, :now, :now, :now)" .
            " ON DUPLICATE KEY UPDATE last_seen_ymdhis = :now2, updated_ymdhis = :now3"
        );
        $stmt->execute([
            ':sid' => $session_id,
            ':ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            ':ua' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
            ':now' => $now,
            ':now2' => $now,
            ':now3' => $now,
        ]);
    } catch (Throwable $e) {
        // Non-fatal: continue without session write
    }
}

// Any channel in this department with at least one role? (lupo_channel_roles + lupo_channels)
$noonehome = true;
try {
    if ($department !== 0) {
        $stmt = $db->prepare(
            "SELECT 1 FROM {$prefix}channel_roles r " .
            "INNER JOIN {$prefix}channels c ON c.channel_id = r.channel_id AND c.is_deleted = 0 " .
            "WHERE c.department_id = :dept AND r.is_deleted = 0 LIMIT 1"
        );
        $stmt->execute([':dept' => $department]);
    } else {
        $stmt = $db->prepare(
            "SELECT 1 FROM {$prefix}channel_roles WHERE is_deleted = 0 LIMIT 1"
        );
        $stmt->execute([]);
    }
    if ($stmt->fetch()) {
        $noonehome = false;
    }
} catch (Throwable $e) {
    // leave noonehome true
}

// Department metadata for online/offline image paths and creditline
$onlineimage = 'images/online.gif';
$offlineimage = 'images/offline.gif';
$leaveamessage = 'YES';
$creditline = 'L';
$hide = isset($_GET['hide']) && (string)$_GET['hide'] === 'Y';

try {
    $dept_id = $department;
    if ($dept_id === 0 && $db instanceof \PDO_DB) {
        $row = $db->fetchRow("SELECT department_id FROM {$prefix}departments WHERE is_deleted = 0 LIMIT 1");
        $dept_id = $row ? (int) $row['department_id'] : 0;
    }
    if ($dept_id !== 0) {
        $stmt = $db->prepare("SELECT metadata_json FROM {$prefix}department_metadata WHERE department_id = :id AND is_deleted = 0 LIMIT 1");
        $stmt->execute([':id' => $dept_id]);
        $meta = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($meta && !empty($meta['metadata_json'])) {
            $json = is_string($meta['metadata_json']) ? json_decode($meta['metadata_json'], true) : $meta['metadata_json'];
            if (is_array($json)) {
                if (!empty($json['onlineimage'])) {
                    $onlineimage = $json['onlineimage'];
                }
                if (!empty($json['offlineimage'])) {
                    $offlineimage = $json['offlineimage'];
                }
                if (isset($json['leaveamessage'])) {
                    $leaveamessage = (strtoupper((string)$json['leaveamessage']) === 'NO') ? 'NO' : 'YES';
                }
                if (!empty($json['creditline'])) {
                    $creditline = substr((string)$json['creditline'], 0, 1);
                }
            }
        }
    }
} catch (Throwable $e) {
    // use defaults
}

// Base path for image files: legacy craftysyntax images or project assets (from config, never hardcode folder name)
$app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '');
$legacy_images = $app_root . DIRECTORY_SEPARATOR . 'legacy' . DIRECTORY_SEPARATOR . 'craftysyntax' . DIRECTORY_SEPARATOR;
$asset_images = $app_root . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'visitor-images' . DIRECTORY_SEPARATOR;
function resolve_image_path($relative, $legacy, $asset) {
    $path = $legacy . str_replace('/', DIRECTORY_SEPARATOR, $relative);
    if (is_file($path)) {
        return $path;
    }
    $path = $asset . basename($relative);
    if (is_file($path)) {
        return $path;
    }
    return $legacy . 'images' . DIRECTORY_SEPARATOR . 'livehelp3.gif';
}

if ($what === 'getcredit') {
    $xyz = isset($_GET['xy']) ? substr((string)$_GET['xy'], 0, 1) : $creditline;
    $filepath = null;
    if ($xyz === 'N' || $hide) {
        $filepath = $legacy_images . 'images' . DIRECTORY_SEPARATOR . 'blank.gif';
        if (!is_file($filepath)) {
            $filepath = $asset_images . 'blank.gif';
        }
    }
    if ($filepath === null && $noonehome && $leaveamessage !== 'YES') {
        $filepath = $legacy_images . 'images' . DIRECTORY_SEPARATOR . 'livehelp3.gif';
        if (!is_file($filepath)) {
            $filepath = $asset_images . 'livehelp3.gif';
        }
    }
    if ($filepath === null) {
        $filepath = $noonehome
            ? resolve_image_path($offlineimage, $legacy_images, $asset_images)
            : resolve_image_path($onlineimage, $legacy_images, $asset_images);
    }
    if (!is_file($filepath)) {
        $filepath = $legacy_images . 'images' . DIRECTORY_SEPARATOR . 'livehelp.gif';
        if (!is_file($filepath)) {
            $filepath = $asset_images . 'livehelp.gif';
        }
    }
    if (!is_file($filepath)) {
        header('Content-Type: image/gif');
        header('Content-Length: 0');
        exit;
    }
    header('Content-Type: image/gif');
    header('Cache-Control: no-cache, must-revalidate');
    readfile($filepath);
    exit;
}

// getstate
if ($hide) {
    $filepath = $legacy_images . 'images' . DIRECTORY_SEPARATOR . 'livehelp3.gif';
    if (!is_file($filepath)) {
        $filepath = $asset_images . 'livehelp3.gif';
    }
} elseif (!$noonehome) {
    $filepath = resolve_image_path($onlineimage, $legacy_images, $asset_images);
} elseif ($leaveamessage === 'YES') {
    $filepath = resolve_image_path($offlineimage, $legacy_images, $asset_images);
} else {
    $filepath = $legacy_images . 'images' . DIRECTORY_SEPARATOR . 'livehelp3.gif';
    if (!is_file($filepath)) {
        $filepath = $asset_images . 'livehelp3.gif';
    }
}
if (!is_file($filepath)) {
    $filepath = $legacy_images . 'images' . DIRECTORY_SEPARATOR . 'livehelp3.gif';
    if (!is_file($filepath)) {
        header('Content-Type: image/gif');
        header('Content-Length: 0');
        exit;
    }
}
header('Content-Type: image/gif');
header('Cache-Control: no-cache, must-revalidate');
readfile($filepath);
