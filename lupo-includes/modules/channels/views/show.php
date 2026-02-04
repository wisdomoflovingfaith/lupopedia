<?php
/**
 * Channel operator cockpit — legacy Crafty Syntax behavior (docs/notes_from_legacy_craftysyntax.md).
 * Layout: rooms bar (presence), message stream (all threads interleaved), operators/visitors, tabs + composer.
 * Selecting a tab changes only the composer target; stream stays unified. All paths use LUPOPEDIA_PUBLIC_PATH.
 */
$base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
$channel_name = isset($channel['channel_name']) ? htmlspecialchars($channel['channel_name']) : 'Channel';
$channel_id = isset($channel_id) ? (int) $channel_id : 0;
$selected_thread_id = isset($selected_thread_id) ? (int) $selected_thread_id : 0;
$initial_after_ymdhis = isset($initial_after_ymdhis) ? (string) $initial_after_ymdhis : '0';
$current_actor_id = isset($current_actor_id) ? (int) $current_actor_id : 0;
?>
<link rel="stylesheet" href="<?= $base ?>/lupo-includes/modules/channels/channel-interface.css">
<div class="channel-operator-interface" id="channel-operator-interface"
     data-channel-id="<?= $channel_id ?>"
     data-actor-id="<?= $current_actor_id ?>"
     data-initial-after-ymdhis="<?= htmlspecialchars($initial_after_ymdhis) ?>"
     data-base="<?= htmlspecialchars($base) ?>">
    <?php
    $typing_partial = __DIR__ . '/partials/_typing_preview_panel.php';
    if (file_exists($typing_partial)) {
        include $typing_partial;
    }
    ?>
    <!-- Rooms bar (legacy admin_rooms.php): presence / status -->
    <header class="channel-interface-header channel-rooms-bar">
        <h1 class="channel-interface-title"><?= $channel_name ?></h1>
        <div class="channel-rooms-actions">
            <a href="<?= $base ?>/my-channel.php" class="channel-interface-mylink">My Channel</a>
            <span class="channel-presence-dot" aria-hidden="true"></span>
            <span class="channel-presence-label">Online</span>
            <a href="<?= $base ?>/channels/<?= $channel_id ?>/?clear=now" class="channel-btn channel-btn-clear" id="channel-clear-now">Clear to now</a>
            <a href="<?= $base ?>/channels/<?= $channel_id ?>/" class="channel-btn channel-btn-refresh" id="channel-force-refresh">Refresh</a>
        </div>
    </header>
    <div class="channel-interface-body">
        <!-- Panel 1: Unified message stream (all threads interleaved by created_ymdhis; legacy §1) -->
        <main class="channel-panel channel-panel-message-stream" aria-label="Channel messages (all threads)">
            <?php
            $partial = __DIR__ . '/partials/_message_stream.php';
            if (file_exists($partial)) {
                include $partial;
            } else {
                echo '<ul class="channel-message-stream" id="channel-message-stream" role="log"><li class="channel-no-messages">No messages yet.</li></ul>';
            }
            ?>
        </main>
        <!-- Panel 2: Operators + visitors (legacy admin_users) -->
        <aside class="channel-panel channel-panel-people" aria-label="Operators and visitors">
            <h2 class="channel-panel-title">Operators &amp; visitors</h2>
            <?php
            $partial = __DIR__ . '/partials/_operators_visitors.php';
            if (file_exists($partial)) {
                include $partial;
            } else {
                echo '<p class="channel-people-empty">No one in channel</p>';
            }
            ?>
        </aside>
    </div>
    <!-- Tabs bar (legacy admin_chat_bot: one tab per thread; selecting tab changes composer target only) -->
    <div class="channel-tabs-bar" role="tablist" aria-label="Threads">
        <?php
        $threads = isset($threads) ? $threads : [];
        foreach ($threads as $t):
            $tid = (int)($t['dialog_thread_id'] ?? 0);
            $label = htmlspecialchars($t['task_name'] ?? $t['summary_text'] ?? 'Thread #' . $tid);
            $bg = isset($thread_colors[$tid]) && preg_match('/^[0-9A-Fa-f]{6}$/', $thread_colors[$tid] ?? '') ? $thread_colors[$tid] : 'FFFACD';
            $active = ($tid === $selected_thread_id);
            $href = $base . '/channels/' . $channel_id . '/?thread=' . $tid;
        ?>
        <a href="<?= $href ?>" class="channel-tab <?= $active ? 'channel-tab-active' : '' ?>" role="tab" data-thread-id="<?= $tid ?>" style="background-color:#<?= $bg ?>;"><?= $label ?></a>
        <?php endforeach; ?>
        <?php if (empty($threads)): ?>
        <span class="channel-tab channel-tab-empty">No threads</span>
        <?php endif; ?>
    </div>
    <!-- Panel 3: Composer (legacy admin_chat_bot bottom; send to selected thread) -->
    <footer class="channel-composer-bar">
        <?php
        $partial = __DIR__ . '/partials/_composer.php';
        if (file_exists($partial)) {
            include $partial;
        } else {
            echo '<p>Message composer (select a thread above)</p>';
        }
        ?>
    </footer>
</div>
<script>
(function() {
    var base = document.getElementById('channel-operator-interface').getAttribute('data-base') || '';
    var channelId = document.getElementById('channel-operator-interface').getAttribute('data-channel-id') || '0';
    var actorId = document.getElementById('channel-operator-interface').getAttribute('data-actor-id') || '0';
    var afterYmdhis = document.getElementById('channel-operator-interface').getAttribute('data-initial-after-ymdhis') || '0';
    var streamEl = document.getElementById('channel-message-stream');
    var messagesUrl = base + '/api/channel/messages';
    var checkUrl = base + '/api/channel/check';
    var pollInterval = 2100;
    var pollCount = 0;
    var maxPollBeforeCheck = 15;
    var useCheckFallback = false;

    function escapeHtml(s) {
        if (s == null) return '';
        var div = document.createElement('div');
        div.textContent = s;
        return div.innerHTML;
    }

    function renderMessage(m, threadColors, actorNames) {
        var tid = parseInt(m.dialog_thread_id, 10) || 0;
        var bg = (threadColors[tid] && /^[0-9A-Fa-f]{6}$/.test(threadColors[tid])) ? threadColors[tid] : 'FFFACD';
        var fromId = parseInt(m.from_actor_id, 10) || 0;
        var sender = actorNames[fromId] || ('actor_' + fromId);
        var msgId = parseInt(m.dialog_message_id, 10) || 0;
        return '<li class="channel-message-block" data-message-id="' + msgId + '" data-thread-id="' + tid + '" style="background-color:#' + escapeHtml(bg) + ';">' +
            '<span class="channel-message-block-meta">' + escapeHtml(m.created_ymdhis || '') + '</span> ' +
            '<span class="channel-message-block-sender">' + escapeHtml(sender) + ':</span> ' +
            '<span class="channel-message-block-text">' + escapeHtml(m.message_text || '') + '</span></li>';
    }

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

    var iface = document.getElementById('channel-operator-interface');
    if (iface) {
        iface.addEventListener('channel-message-sent', function() {
            tick();
        });
    }

    setInterval(tick, pollInterval);
    tick();
})();
</script>
