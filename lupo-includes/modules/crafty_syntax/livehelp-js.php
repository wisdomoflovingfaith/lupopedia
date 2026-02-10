<?php
/**
 * Live Help visitor JS (legacy livehelp_js.php equivalent).
 * Outputs JavaScript that shows online/offline icon and click-to-open chat
 * using Lupopedia schema. All URLs use LUPOPEDIA_PUBLIC_PATH.
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$db = $GLOBALS['mydatabase'] ?? null;
if (!$db) {
    header('Content-Type: application/javascript; charset=utf-8');
    echo "console.error('Live Help: database unavailable');";
    exit;
}

// Visitor session id (legacy cslhVISITOR) - no session created on this request
$session_id = isset($_GET['cslhVISITOR']) ? (string)$_GET['cslhVISITOR'] : '';
if ($session_id === '' && !empty($_COOKIE['cslhVISITOR'])) {
    $session_id = (string)$_COOKIE['cslhVISITOR'];
}
if ($session_id === '') {
    $session_id = 'v' . bin2hex(random_bytes(12));
}

$base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
$WEBPATH = parse_url($base, PHP_URL_PATH);
if ($WEBPATH === null || $WEBPATH === false || $WEBPATH === '') {
    $WEBPATH = '/';
} else {
    $WEBPATH = '/' . ltrim($WEBPATH, '/');
    if (substr($WEBPATH, -1) !== '/') {
        $WEBPATH .= '/';
    }
}

$department = isset($_GET['department']) ? (int)$_GET['department'] : 0;
$website = isset($_GET['website']) ? (int)$_GET['website'] : 0;
$winwidth = isset($_GET['winwidth']) ? (int)$_GET['winwidth'] : 600;
$winheight = isset($_GET['winheight']) ? (int)$_GET['winheight'] : 450;
$usetable = (!empty($_GET['usetable']) && (string)$_GET['usetable'] !== 'Y') ? 'N' : 'Y';
$creditline = 'L';
$leaveamessage = 'YES';
$pingtimes = isset($_GET['pingtimes']) ? (int)$_GET['pingtimes'] : 12;
$querystringadd = '&cslheg=1';
if (!empty($_GET['serversession'])) {
    $querystringadd .= '&serversession=1';
} else {
    $querystringadd .= '&serversession=0';
}
if (!empty($_GET['relative'])) {
    $querystringadd .= '&relative=Y';
}
if (!empty($_GET['username'])) {
    $querystringadd .= '&username=' . rawurlencode((string)$_GET['username']);
}
$parentdot = (!empty($_GET['frameparent'])) ? 'parent.' : '';
$force = !empty($_GET['force']);

if ($department === 0 && $db instanceof \PDO_DB) {
    $row = $db->fetchRow("SELECT department_id FROM {$prefix}departments WHERE is_deleted = 0 LIMIT 1");
    $department = $row ? (int) $row['department_id'] : 0;
}
$dept_id = $department;

// Department metadata (creditline, leaveamessage)
try {
    if ($dept_id !== 0) {
        $stmt = $db->prepare("SELECT metadata_json FROM {$prefix}department_metadata WHERE department_id = :id AND is_deleted = 0 LIMIT 1");
        $stmt->execute([':id' => $dept_id]);
        $meta = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($meta && !empty($meta['metadata_json'])) {
            $json = is_string($meta['metadata_json']) ? json_decode($meta['metadata_json'], true) : $meta['metadata_json'];
            if (is_array($json)) {
                if (isset($json['creditline'])) {
                    $creditline = substr((string)$json['creditline'], 0, 1);
                }
                if (isset($json['leaveamessage'])) {
                    $leaveamessage = (strtoupper((string)$json['leaveamessage']) === 'NO') ? 'NO' : 'YES';
                }
            }
        }
    }
} catch (Throwable $e) {
    // use defaults
}
if (!empty($_GET['creditline'])) {
    $creditline = substr((string)$_GET['creditline'], 0, 1);
}
if (!empty($_GET['leaveamessage'])) {
    $leaveamessage = (strtoupper((string)$_GET['leaveamessage']) === 'NO') ? 'NO' : 'YES';
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

$urlreplace = $force
    ? $base . 'chat?department=' . $department . '&website=' . $website . '&resizewidth=500&resizeheight=350'
    : "javascript:openLiveHelp(" . $department . ")";
$target = $force ? ' target="_blank" ' : '';

header('Content-Type: application/javascript; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
?>
var WEBPATH = "<?= addslashes($WEBPATH) ?>";

var cscontrol_<?= $department ?> = new Image();
var csTimeout_<?= $department ?> = <?= (int)$pingtimes ?>;
var csID_<?= $department ?> = null;
var openLiveHelpalready = false;
var place_<?= $department ?> = 1;
var csloaded_<?= $department ?> = false;

function openLiveHelp(department) {
    openLiveHelpalready = true;
    csTimeout_<?= $department ?> = 0;
    var url = WEBPATH + 'chat?department=' + department + '&website=<?= (int)$website ?>&cslhVISITOR=<?= addslashes($session_id) ?><?= addslashes($querystringadd) ?>';
    window.open(url, 'chat54050872', 'width=<?= (int)$winwidth ?>,height=<?= (int)$winheight ?>,menubar=no,scrollbars=1,resizable=1');
}

function csgetimage_<?= $department ?>() {
    csID_<?= $department ?> = Math.round(Math.random() * 9999);
    var randu = Math.round(Math.random() * 9999);
    var locationvar = (<?= $parentdot ?>document.location + '').replace(/[^A-Za-z0-9_)\+\^{}~( ',\.\&\%=\/\\?#:-]/g, '').replace(/=[a-z0-9]{32}/g, 'x=1').replace(/\./g, '--dot--').replace(/http:\/\//g, '').replace(/https:\/\//g, '').substr(0, 250);
    var var_title = (<?= $parentdot ?>document.title + '').replace(/[^A-Za-z0-9_)\+\^{}~( ',\.\&\%=\/\\?#:-]/g, '').substr(0, 100);
    var var_referrer = (<?= $parentdot ?>document.referrer + '').replace(/[^A-Za-z0-9_)\+\^{}~( ',\.\&\%=\/\\?#:-]/g, '').replace(/=[a-z0-9]{32}/g, 'x=1').replace(/\./g, '--dot--').replace(/http:\/\//g, '').replace(/https:\/\//g, '').substr(0, 250);
    var u = WEBPATH + 'image.php?what=userstat&page=' + encodeURIComponent(locationvar) + '&randu=' + randu + '&pageid=' + csID_<?= $department ?> + '&department=<?= (int)$department ?>&cslhVISITOR=<?= addslashes($session_id) ?>&title=' + encodeURIComponent(var_title) + '&referer=' + encodeURIComponent(var_referrer) + '<?= addslashes($querystringadd) ?>';
    cscontrol_<?= $department ?> = new Image();
    cscontrol_<?= $department ?>.onload = function() { cslookatimage_<?= $department ?>(); };
    cscontrol_<?= $department ?>.src = u;
}

function cslookatimage_<?= $department ?>() {
    var w = 0;
    if (typeof cscontrol_<?= $department ?> !== 'undefined' && cscontrol_<?= $department ?>) {
        w = cscontrol_<?= $department ?>.width || 0;
    }
    if (w === 55 && !openLiveHelpalready) {
        openLiveHelp(<?= (int)$department ?>);
    }
}

function csrepeat_<?= $department ?>() {
    if (csTimeout_<?= $department ?> < 1) return;
    csTimeout_<?= $department ?>--;
    csgetimage_<?= $department ?>();
    setTimeout(function() { csrepeat_<?= $department ?>(); }, 10000);
}

function wherecslhisdue_<?= $department ?>() {
    var container = document.getElementById('craftysyntax_<?= $department ?>') || document.getElementById('craftysyntax');
    if (!container) return;
    var urltohelpimage = '<?= addslashes($base) ?>image.php?what=getstate&department=<?= (int)$department ?>&nowis=<?= date('YmdHis') ?>&cslhVISITOR=<?= addslashes($session_id) ?>&leaveamessage=<?= addslashes($leaveamessage) ?><?= addslashes($querystringadd) ?>';
    var urltocslhimage = '<?= addslashes($base) ?>image.php?what=getcredit&department=<?= (int)$department ?>&nowis=<?= date('YmdHis') ?>&cslhVISITOR=<?= addslashes($session_id) ?>&xy=<?= addslashes($creditline) ?>&leaveamessage=<?= addslashes($leaveamessage) ?><?= addslashes($querystringadd) ?>';
    var html = '<a name="chatRef" href="<?= addslashes($urlreplace) ?>" <?= $target ?> onclick="csTimeout_<?= $department ?>=0;"><img name="csIcon" src="' + urltohelpimage + '" alt="Live Help" border="0"></a>';
    <?php if ($creditline !== 'N') : ?>
    html += '<br clear="both"><a href="https://lupopedia.com/?utm_source=poweredby&utm_campaign=poweredby" target="_blank" rel="noopener"><img src="' + urltocslhimage + '" border="0" style="margin-top:4px;" alt="Powered by LUPOPEDIA"></a>';
    <?php endif; ?>
    container.innerHTML = html;
    csloaded_<?= $department ?> = true;
}

(function() {
    var urltohelpimage_<?= $department ?> = '<?= addslashes($base) ?>image.php?what=getstate&department=<?= (int)$department ?>&nowis=<?= date('YmdHis') ?>&cslhVISITOR=<?= addslashes($session_id) ?>&leaveamessage=<?= addslashes($leaveamessage) ?><?= addslashes($querystringadd) ?>';
    var urltocslhimage_<?= $department ?> = '<?= addslashes($base) ?>image.php?what=getcredit&department=<?= (int)$department ?>&nowis=<?= date('YmdHis') ?>&cslhVISITOR=<?= addslashes($session_id) ?>&xy=<?= addslashes($creditline) ?>&leaveamessage=<?= addslashes($leaveamessage) ?><?= addslashes($querystringadd) ?>';

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            wherecslhisdue_<?= $department ?>();
        });
    } else {
        wherecslhisdue_<?= $department ?>();
    }

    <?php if ($noonehome) : ?>
    setTimeout(function() { csgetimage_<?= $department ?>(); }, 4000);
    <?php endif; ?>
    setTimeout(function() { csrepeat_<?= $department ?>(); }, 8000);
})();
