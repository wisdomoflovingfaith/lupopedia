<?php
/**
 * Channel message stream — iframe target (legacy livehelp pattern).
 * Minimal HTML document: message list + polling. Parent show.php embeds this in an iframe with fixed height.
 */
$base = isset($channel_public_path) ? $channel_public_path : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '');
$channel_id = isset($channel_id) ? (int) $channel_id : 0;
$initial_after_ymdhis = isset($initial_after_ymdhis) ? (string) $initial_after_ymdhis : '0';
if (!isset($messages)) {
    $messages = array();
}
if (!isset($thread_colors)) {
    $thread_colors = array();
}
if (!isset($actor_names)) {
    $actor_names = array();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Messages</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($base) ?>/lupo-includes/modules/channels/channel-interface.css">
    <style>
        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; height: 100%; font-family: inherit; background: #fff; }
        .channel-stream-root { display: flex; flex-direction: column; height: 100%; min-height: 0; overflow: hidden; }
        .channel-panel-message-stream { flex: 1; min-height: 0; overflow-y: auto; overflow-x: hidden; -webkit-overflow-scrolling: touch; }
    </style>
</head>
<body>
    <div class="channel-stream-root" id="channel-stream-root">
        <main class="channel-panel channel-panel-message-stream" aria-label="Channel messages">
            <?php
            $partial = __DIR__ . '/partials/_message_stream.php';
            if (file_exists($partial)) {
                include $partial;
            } else {
                echo '<ul class="channel-message-stream" id="channel-message-stream" role="log"><li class="channel-no-messages">No messages yet.</li></ul>';
            }
            ?>
        </main>
    </div>
    <script>
(function() {
    var root = document.getElementById('channel-stream-root');
    var base = <?= json_encode($base) ?>;
    var channelId = <?= (int) $channel_id ?>;
    var afterYmdhis = <?= json_encode($initial_after_ymdhis) ?>;
    var streamEl = document.getElementById('channel-message-stream');
    var messagesUrl = base + '/api/channel/messages';
    var checkUrl = base + '/api/channel/check';
    var pollInterval = 2100;
    var pollCount = 0;
    var maxPollBeforeCheck = 15;
    var useCheckFallback = false;

    function appendMessages(data) {
        if (!streamEl || !data.messages || data.messages.length === 0) return;
        var placeholder = streamEl.querySelector('.channel-no-messages');
        if (placeholder) placeholder.remove();
        var colors = data.thread_colors || {};
        var names = data.actor_names || {};
        var frag = document.createDocumentFragment();
        data.messages.forEach(function(m) {
            var li = document.createElement('li');
            li.className = 'channel-message-block';
            li.setAttribute('data-message-id', m.dialog_message_id || '');
            li.setAttribute('data-thread-id', m.dialog_thread_id || '');
            var tid = parseInt(m.dialog_thread_id, 10) || 0;
            var bg = (colors[tid] && /^[0-9A-Fa-f]{6}$/.test(colors[tid])) ? colors[tid] : 'FFFACD';
            li.style.backgroundColor = '#' + bg;
            var meta = document.createElement('span');
            meta.className = 'channel-message-block-meta';
            meta.textContent = m.created_ymdhis || '';
            var sender = document.createElement('span');
            sender.className = 'channel-message-block-sender';
            sender.textContent = (names[m.from_actor_id] || ('actor_' + m.from_actor_id)) + ': ';
            var text = document.createElement('span');
            text.className = 'channel-message-block-text';
            text.textContent = m.message_text || '';
            li.appendChild(meta);
            li.appendChild(sender);
            li.appendChild(text);
            frag.appendChild(li);
        });
        streamEl.appendChild(frag);
        if (data.last_ymdhis) afterYmdhis = data.last_ymdhis;
        var main = streamEl.closest('.channel-panel-message-stream');
        if (main) main.scrollTop = main.scrollHeight;
    }

    function primaryPoll() {
        var url = messagesUrl + '?channel_id=' + encodeURIComponent(channelId) + '&after_ymdhis=' + encodeURIComponent(afterYmdhis);
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) return;
            pollCount++;
            try {
                var data = JSON.parse(xhr.responseText || '{}');
                if (data.messages && data.messages.length > 0) {
                    appendMessages(data);
                }
                if (data.last_ymdhis) afterYmdhis = data.last_ymdhis;
            } catch (e) {}
            if (pollCount >= maxPollBeforeCheck) useCheckFallback = true;
        };
        xhr.onerror = function() { useCheckFallback = true; };
        xhr.send();
    }

    function secondaryCheck() {
        var url = checkUrl + '?channel_id=' + encodeURIComponent(channelId) + '&after_ymdhis=' + encodeURIComponent(afterYmdhis);
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) return;
            try {
                var data = JSON.parse(xhr.responseText || '{}');
                if (data.refresh === true) {
                    window.location.reload();
                }
            } catch (e) {}
        };
        xhr.send();
    }

    function tick() {
        if (useCheckFallback) {
            secondaryCheck();
        } else {
            primaryPoll();
        }
    }

    window.addEventListener('message', function(ev) {
        if (ev.data === 'channel-message-sent' || (ev.data && ev.data.type === 'channel-message-sent')) {
            tick();
        }
    });

    setInterval(tick, pollInterval);
    tick();
})();
    </script>
</body>
</html>
