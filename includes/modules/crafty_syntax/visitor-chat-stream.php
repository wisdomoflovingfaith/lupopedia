<?php
/**
 * Visitor chat message stream (iframe target from livehelp.php).
 * Legacy: user_chat_refresh.php / livehelp_chat.php content area.
 * Full implementation: message polling (primary XHR 2100ms), typing preview, check fallback, send form.
 * Uses LUPOPEDIA_PUBLIC_PATH for all URLs. Visitor session cslhVISITOR in all requests.
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

$department = isset($_GET['department']) ? (int) $_GET['department'] : 0;
$session_id = isset($_GET['cslhVISITOR']) ? (string) $_GET['cslhVISITOR'] : '';
if ($session_id === '' && !empty($_COOKIE['cslhVISITOR'])) {
    $session_id = (string) $_COOKIE['cslhVISITOR'];
}
if ($session_id === '') {
    $session_id = 'v' . bin2hex(lupo_random_bytes(12));
}

$base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$channel_id = 0;
$dialog_thread_id = 0;
$preview_setting = 2; // 1=full text, 2=dots, 3=no preview, 4=off. Legacy previewsetting.

// Ensure session row exists and get/create visitor thread (channel + dialog_thread)
$now = date('YmdHis');
if ($db && $department > 0) {
    try {
        // H-03: Populate session_identity_hash in legacy session creation
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        $ua = substr(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '', 0, 255);
        
        // Compute session identity hash using Session class methods
        // user_id is null here (pre-login / anonymous session) — 'unknown' placeholder used inside computeIdentityHash
        if (class_exists('App\Auth\Session', false)) {
            $identity_hash = App\Auth\Session::computeIdentityHash($ip, $ua, null);
        } else {
            // Fallback: must match computeIdentityHash() formula exactly
            // SHA256(class_c_ip + '|unknown|' + user_agent + '|' + salt)
            $class_c = implode('.', array_slice(explode('.', $ip), 0, 3));
            $salt     = defined('LUPO_SESSION_SALT') ? LUPO_SESSION_SALT : '';
            $identity_hash = hash('sha256', $class_c . '|unknown|' . $ua . '|' . $salt);
        }
        
        $stmt = $db->prepare(
            "INSERT INTO {$prefix}sessions (session_id, federation_node_id, actor_id, ip_address, user_agent, session_identity_hash, last_seen_ymdhis, created_ymdhis, updated_ymdhis)" .
            " VALUES (:sid, 1, 0, :ip, :ua, :identity_hash, :now, :now, :now)" .
            " ON DUPLICATE KEY UPDATE last_seen_ymdhis = :now2, updated_ymdhis = :now3"
        );
        $stmt->execute(array(
            ':sid' => $session_id,
            ':ip' => $ip,
            ':ua' => $ua,
            ':identity_hash' => $identity_hash,
            ':now' => $now,
            ':now2' => $now,
            ':now3' => $now,
        ));
    } catch (Exception $e) {
        // continue
    }

    require_once __DIR__ . '/visitor-session-helper.php';
    $existing = crafty_syntax_visitor_thread_from_session($session_id);
    if ($existing && $existing['dialog_thread_id'] > 0) {
        $dialog_thread_id = $existing['dialog_thread_id'];
        // Verify thread exists and get its channel_id (NULL = pending)
        $stmt = $db->prepare("SELECT channel_id FROM {$prefix}dialog_threads WHERE dialog_thread_id = :tid AND is_deleted = 0 LIMIT 1");
        $stmt->execute(array(':tid' => $dialog_thread_id));
        $trow = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($trow) {
            $thread_channel = isset($trow['channel_id']) ? (int) $trow['channel_id'] : null;
            if ($thread_channel > 0) {
                $channel_id = $thread_channel;
            }
            // else thread is pending (channel_id IS NULL): keep channel_id=0 for API calls
        } else {
            $dialog_thread_id = 0;
        }
    }

    if ($dialog_thread_id <= 0) {
        // Create ONLY dialog_thread (no channel). Legacy: visitor stays pending until operator accepts.
        $federation_node_id = defined('LUPO_DEFAULT_NODE_ID') ? (int) LUPO_DEFAULT_NODE_ID : 1;
        try {
            $cols = "federation_node_id, created_by_actor_id, status, created_ymdhis, updated_ymdhis, is_deleted";
            $vals = ":fn, 0, 'Open', :now, :now, 0";
            $stmt = $db->prepare("INSERT INTO {$prefix}dialog_threads ($cols) VALUES ($vals)");
            $stmt->execute(array(':fn' => $federation_node_id, ':now' => $now));
            $dialog_thread_id = (int) $db->lastInsertId();
        } catch (Exception $e) {
            $dialog_thread_id = 0;
        }
        if ($dialog_thread_id > 0) {
            crafty_syntax_visitor_save_pending_thread_to_session($session_id, $department, $dialog_thread_id);
        }
    }
}

// Config: previewsetting from lupo_modules.config_json (module_id = 1)
if ($db) {
    try {
        $stmt = $db->prepare("SELECT config_json FROM {$prefix}modules WHERE module_id = 1 AND is_deleted = 0 LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['config_json'])) {
            $cfg = json_decode($row['config_json'], true);
            if (is_array($cfg) && isset($cfg['previewsetting'])) {
                $preview_setting = (int) $cfg['previewsetting'];
            }
        }
    } catch (Exception $e) {
        // keep default
    }
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; margin: 0; padding: 0; background: #fff; min-height: 100vh; display: flex; flex-direction: column; }
        .visitor-stream-container { flex: 1; display: flex; flex-direction: column; min-height: 200px; }
        .visitor-message-list { flex: 1; overflow-y: auto; padding: 0.75rem; margin: 0; list-style: none; }
        .visitor-message-list .msg { margin-bottom: 0.5rem; padding: 0.4rem 0.6rem; border-radius: 6px; max-width: 85%; }
        .visitor-message-list .msg.visitor { margin-left: 0; margin-right: auto; background: #e3f2fd; }
        .visitor-message-list .msg.operator { margin-left: auto; margin-right: 0; background: #f5f5f5; }
        .visitor-message-list .msg .sender { font-size: 0.75rem; color: #666; margin-bottom: 0.2rem; }
        .visitor-message-list .msg .time { font-size: 0.7rem; color: #999; }
        .visitor-typing-panel { position: fixed; top: 8px; right: 8px; max-width: 220px; padding: 6px 10px; background: #fff3e0; border: 1px solid #ffcc80; border-radius: 6px; font-size: 0.85rem; display: none; z-index: 10; }
        .visitor-typing-panel.visible { display: block; }
        .visitor-composer { padding: 0.5rem; border-top: 1px solid #ddd; background: #fafafa; }
        .visitor-composer form { display: flex; gap: 0.5rem; align-items: flex-end; }
        .visitor-composer textarea { flex: 1; min-height: 44px; max-height: 120px; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; resize: vertical; }
        .visitor-composer button { padding: 0.5rem 1rem; background: #1976d2; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
        .visitor-composer button:disabled { opacity: 0.6; cursor: not-allowed; }
        .visitor-no-channel { padding: 2rem; text-align: center; color: #666; }
    </style>
</head>
<body>
    <div class="visitor-stream-container">
        <div id="visitor-typing-panel" class="visitor-typing-panel" aria-live="polite"></div>
        <ul id="visitor-message-list" class="visitor-message-list"></ul>
        <?php if ($dialog_thread_id <= 0): ?>
            <div class="visitor-no-channel">Unable to start chat. Please refresh the page.</div>
        <?php else: ?>
            <div class="visitor-composer">
                <form id="visitor-send-form" action="<?= htmlspecialchars($base) ?>api/channel/send" method="post">
                    <input type="hidden" name="channel_id" value="<?= (int) $channel_id ?>">
                    <input type="hidden" name="dialog_thread_id" value="<?= (int) $dialog_thread_id ?>">
                    <input type="hidden" name="actor_id" value="0">
                    <input type="hidden" name="cslhVISITOR" value="<?= htmlspecialchars($session_id) ?>">
                    <textarea id="visitor-message-text" name="message_text" placeholder="Type a message..." rows="2"></textarea>
                    <button type="submit" id="visitor-send-btn">Send</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <script>
(function() {
    var base = <?= json_encode($base) ?>;
    var channelId = <?= (int) $channel_id ?>;
    var dialogThreadId = <?= (int) $dialog_thread_id ?>;
    var cslhVISITOR = <?= json_encode($session_id) ?>;
    var previewSetting = <?= (int) $preview_setting ?>;

    var messageList = document.getElementById('visitor-message-list');
    var typingPanel = document.getElementById('visitor-typing-panel');
    var sendForm = document.getElementById('visitor-send-form');
    var messageText = document.getElementById('visitor-message-text');
    var sendBtn = document.getElementById('visitor-send-btn');

    var afterYmdhis = '0';
    var primaryFailCount = 0;
    var PRIMARY_FAIL_MAX = 5;
    var PRIMARY_INTERVAL = 2100;
    var CHECK_INTERVAL = 3500;
    var TYPING_INTERVAL = 2000;

    function addParam(url, key, value) {
        var sep = url.indexOf('?') >= 0 ? '&' : '?';
        return url + sep + encodeURIComponent(key) + '=' + encodeURIComponent(value);
    }

    function primaryPoll() {
        if (channelId <= 0 || dialogThreadId <= 0) return;
        var url = base + 'api/channel/messages';
        url = addParam(url, 'channel_id', channelId);
        url = addParam(url, 'after_ymdhis', afterYmdhis);
        url = addParam(url, 'dialog_thread_id', dialogThreadId);
        url = addParam(url, 'cslhVISITOR', cslhVISITOR);

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) return;
            if (xhr.status === 200) {
                primaryFailCount = 0;
                try {
                    var data = JSON.parse(xhr.responseText);
                    if (data.messages && data.messages.length) {
                        appendMessages(data.messages, data.actor_names || {}, data.thread_colors || {});
                        if (data.last_ymdhis) afterYmdhis = String(data.last_ymdhis);
                    }
                } catch (e) {}
            } else {
                primaryFailCount++;
                if (primaryFailCount >= PRIMARY_FAIL_MAX) {
                    window.location.reload();
                }
            }
        };
        xhr.open('GET', url, true);
        xhr.send();
    }

    function appendMessages(messages, actorNames, threadColors) {
        if (!messageList) return;
        for (var i = 0; i < messages.length; i++) {
            var m = messages[i];
            var fromId = parseInt(m.from_actor_id, 10) || 0;
            var isVisitor = fromId === 0;
            var name = isVisitor ? 'You' : (actorNames[fromId] || 'Operator');
            var timeStr = formatTime(m.created_ymdhis);
            var li = document.createElement('li');
            li.className = 'msg ' + (isVisitor ? 'visitor' : 'operator');
            li.setAttribute('data-dialog-message-id', m.dialog_message_id);
            var bg = (threadColors[m.dialog_thread_id] || 'FFFACD');
            if (bg && /^[0-9A-Fa-f]{6}$/.test(bg)) li.style.backgroundColor = '#' + bg;
            li.innerHTML = '<span class="sender">' + escapeHtml(name) + '</span><div>' + escapeHtml(m.message_text || '') + '</div><span class="time">' + escapeHtml(timeStr) + '</span>';
            messageList.appendChild(li);
        }
        messageList.scrollTop = messageList.scrollHeight;
    }

    function formatTime(ymdhis) {
        if (!ymdhis || ymdhis.length < 14) return '';
        var y = ymdhis.slice(0,4), M = ymdhis.slice(4,6), d = ymdhis.slice(6,8);
        var h = ymdhis.slice(8,10), m = ymdhis.slice(10,12), s = ymdhis.slice(12,14);
        return h + ':' + m + ':' + s;
    }

    function escapeHtml(s) {
        if (!s) return '';
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function checkPoll() {
        if (channelId <= 0 || dialogThreadId <= 0) return;
        var url = base + 'api/channel/check';
        url = addParam(url, 'channel_id', channelId);
        url = addParam(url, 'after_ymdhis', afterYmdhis);
        url = addParam(url, 'dialog_thread_id', dialogThreadId);
        url = addParam(url, 'cslhVISITOR', cslhVISITOR);

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) return;
            if (xhr.status === 200) {
                try {
                    var data = JSON.parse(xhr.responseText);
                    if (data.refresh === true) window.location.reload();
                } catch (e) {}
            }
        };
        xhr.open('GET', url, true);
        xhr.send();
    }

    function typingPoll() {
        if (channelId <= 0 || previewSetting >= 3) return;
        var url = base + 'api/channel/typing';
        url = addParam(url, 'channel_id', channelId);
        url = addParam(url, 'cslhVISITOR', cslhVISITOR);

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) return;
            if (xhr.status === 200 && typingPanel) {
                try {
                    var data = JSON.parse(xhr.responseText);
                    var previews = data.previews || {};
                    var html = '';
                    for (var tid in previews) {
                        if (!previews.hasOwnProperty(tid)) continue;
                        var p = previews[tid];
                        if (p.actor_id === 0) continue;
                        var text = p.preview_text || '';
                        var name = p.actor_name || 'Operator';
                        if (previewSetting === 1 && text) html += '<div><strong>' + escapeHtml(name) + '</strong>: ' + escapeHtml(text) + '</div>';
                        else if (previewSetting === 2) html += '<div>' + escapeHtml(name) + ' is typing...</div>';
                    }
                    typingPanel.innerHTML = html;
                    typingPanel.classList.toggle('visible', html !== '');
                } catch (e) {
                    typingPanel.classList.remove('visible');
                }
            }
        };
        xhr.open('GET', url, true);
        xhr.send();
    }

    if (sendForm) {
        sendForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var text = (messageText && messageText.value) ? messageText.value.trim() : '';
            if (!text || !sendBtn) return;

            sendBtn.disabled = true;
            var formData = new FormData(sendForm);
            formData.set('message_text', text);

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState !== 4) return;
                sendBtn.disabled = false;
                if (messageText) messageText.value = '';
                if (xhr.status === 200) {
                    try {
                        var data = JSON.parse(xhr.responseText);
                        if (data.created_ymdhis) afterYmdhis = String(data.created_ymdhis);
                    } catch (e) {}
                }
                clearTypingOnSend();
            };
            xhr.open('POST', sendForm.action);
            xhr.send(formData);
        });
    }

    function clearTypingOnSend() {
        var url = base + 'api/channel/typing';
        var body = JSON.stringify({
            channel_id: channelId,
            dialog_thread_id: dialogThreadId,
            preview_text: '',
            cslhVISITOR: cslhVISITOR
        });
        var xhr = new XMLHttpRequest();
        xhr.open('POST', url);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(body);
    }

    if (dialogThreadId > 0) {
        primaryPoll();
        setInterval(primaryPoll, PRIMARY_INTERVAL);
        setInterval(checkPoll, CHECK_INTERVAL);
        if (channelId > 0 && previewSetting < 3) {
            typingPoll();
            setInterval(typingPoll, TYPING_INTERVAL);
        }
    }
})();
    </script>
</body>
</html>
