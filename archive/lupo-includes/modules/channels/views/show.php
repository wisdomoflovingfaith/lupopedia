<?php
/**
 * Channel operator cockpit — legacy Crafty Syntax behavior (docs/notes_from_legacy_craftysyntax.md).
 * Layout: rooms bar (presence), message stream (all threads interleaved), operators/visitors, tabs + composer.
 * Selecting a tab changes only the composer target; stream stays unified. All paths use LUPOPEDIA_PUBLIC_PATH.
 */
$base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
if (!function_exists('lupo_index_slug_url')) {
    $ah = dirname(dirname(dirname(__DIR__))) . '/functions/auth-helpers.php';
    if (is_file($ah)) {
        require_once $ah;
    }
}
$channel_name = isset($channel['channel_name']) ? htmlspecialchars($channel['channel_name']) : 'Channel';
$channel_id = isset($channel_id) ? (int) $channel_id : 0;
$selected_thread_id = isset($selected_thread_id) ? (int) $selected_thread_id : 0;
$initial_after_ymdhis = isset($initial_after_ymdhis) ? (string) $initial_after_ymdhis : '0';
$current_actor_id = isset($current_actor_id) ? (int) $current_actor_id : 0;
$actor_has_channel_role = isset($actor_has_channel_role) ? $actor_has_channel_role : false;
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
        <h1 class="channel-interface-title"><?= htmlspecialchars($channel['channel_name'] ?? 'Channel') ?></h1>
        <div class="channel-rooms-actions">
            <a href="<?= htmlspecialchars(function_exists('lupo_index_slug_url') ? lupo_index_slug_url('channels/my-channels') : ($base . '/index.php?' . http_build_query(array('slug' => 'channels/my-channels')))) ?>" class="channel-interface-mylink">My Channels</a>
            <span class="channel-presence-dot" aria-hidden="true"></span>
            <span class="channel-presence-label">Online</span>
            <?php if (!empty($actor_has_channel_role)): ?>
            <a href="<?= htmlspecialchars(function_exists('lupo_index_slug_url') ? lupo_index_slug_url('channels/' . $channel_id . '/edit') : ($base . '/index.php?' . http_build_query(array('slug' => 'channels/' . $channel_id . '/edit')))) ?>" class="channel-btn channel-header-button" id="channel-edit">Edit Channel</a>
            <?php endif; ?>
            <a href="<?= htmlspecialchars(function_exists('lupo_index_slug_url') ? lupo_index_slug_url('channels/' . $channel_id . '/log') : ($base . '/index.php?' . http_build_query(array('slug' => 'channels/' . $channel_id . '/log')))) ?>" class="channel-btn channel-header-button" id="channel-view-log">View Log</a>
            <a href="<?= htmlspecialchars(function_exists('lupo_index_slug_url') ? lupo_index_slug_url('channels/' . $channel_id, array('clear' => 'now')) : ($base . '/index.php?' . http_build_query(array('slug' => 'channels/' . $channel_id, 'clear' => 'now')))) ?>" class="channel-btn channel-btn-clear" id="channel-clear-now">Clear to now</a>
            <a href="<?= htmlspecialchars(function_exists('lupo_index_slug_url') ? lupo_index_slug_url('channels/' . $channel_id) : ($base . '/index.php?' . http_build_query(array('slug' => 'channels/' . $channel_id)))) ?>" class="channel-btn channel-btn-refresh" id="channel-force-refresh">Refresh</a>
        </div>
    </header>
    <div class="channel-interface-body">
        <!-- Panel 1: Message stream in iframe (legacy livehelp pattern — fixed height, does not extend past viewport) -->
        <?php
        $stream_src = function_exists('lupo_index_slug_url')
            ? lupo_index_slug_url('channels/' . $channel_id . '/stream', !empty($_GET['clear']) ? array('clear' => (string) $_GET['clear']) : null)
            : ($base . '/index.php?' . http_build_query(array_merge(array('slug' => 'channels/' . $channel_id . '/stream'), !empty($_GET['clear']) ? array('clear' => (string) $_GET['clear']) : array())));
        ?>
        <iframe id="channel-stream-iframe" class="channel-stream-iframe" src="<?= htmlspecialchars($stream_src) ?>" title="Channel messages"></iframe>
        <!-- Panel 2: Channel roles + visitors (legacy admin_users) -->
        <aside class="channel-panel channel-panel-people" aria-label="Channel roles and visitors">
            <h2 class="channel-panel-title">Channel roles &amp; visitors</h2>
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
            $href = function_exists('lupo_index_slug_url')
                ? lupo_index_slug_url('channels/' . $channel_id, array('thread' => $tid))
                : ($base . '/index.php?' . http_build_query(array('slug' => 'channels/' . $channel_id, 'thread' => $tid)));
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
// Message stream polls inside the iframe (channel stream view). When composer sends a message, iframe is notified via postMessage in _composer.php.

// Pending visitors poll, sound, title flash, accept (legacy: admin_users peoplestring + accept)
(function() {
    var listEl = document.getElementById('channel-people-list');
    if (!listEl) return;
    var channelId = listEl.getAttribute('data-channel-id') || '0';
    var departmentId = listEl.getAttribute('data-department-id') || '0';
    var base = listEl.getAttribute('data-base') || '';
    var pendingUrl = listEl.getAttribute('data-pending-url') || (base + 'lupo-api/operator/pending-visitors');
    var acceptUrl = listEl.getAttribute('data-accept-url') || (base + 'lupo-api/operator/accept-visitor');
    var soundUrl = listEl.getAttribute('data-sound-url') || (base + 'legacy/craftysyntax/sounds/new_chats.wav');
    var pendingList = document.getElementById('channel-pending-list');
    var pendingSection = document.getElementById('channel-pending-section');
    var normalTitle = document.title;
    var lastPendingCount = 0;
    var firstPendingPoll = true;
    var userHasInteracted = false;
    var titleFlashInterval = null;

    function playNewChatSound() {
        if (!userHasInteracted) return;
        try {
            var a = new Audio(soundUrl);
            a.volume = 0.5;
            a.play().catch(function() {});
        } catch (e) {}
    }

    function startTitleFlash() {
        if (titleFlashInterval) return;
        var alt = '⚠ New Chat Request!';
        var t = true;
        titleFlashInterval = setInterval(function() {
            document.title = t ? alt : normalTitle;
            t = !t;
        }, 1000);
    }

    function stopTitleFlash() {
        if (titleFlashInterval) {
            clearInterval(titleFlashInterval);
            titleFlashInterval = null;
        }
        document.title = normalTitle;
    }

    document.addEventListener('click', function() { userHasInteracted = true; stopTitleFlash(); }, { once: false });
    document.addEventListener('keydown', function() { userHasInteracted = true; stopTitleFlash(); }, { once: false });

    function renderPendingList(pending) {
        if (!pendingList) return;
        pendingList.innerHTML = '';
        pending.forEach(function(p) {
            var li = document.createElement('li');
            li.className = 'channel-person-item channel-pending-item channel-pending-blink';
            li.setAttribute('data-visitor-session-id', p.visitor_session_id || '');
            li.setAttribute('data-dialog-thread-id', String(p.dialog_thread_id || ''));
            li.setAttribute('data-department-id', String(p.department_id || ''));
            var name = document.createElement('span');
            name.className = 'channel-person-name';
            name.textContent = 'Visitor ' + (p.visitor_session_id || '').substring(0, 8);
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'channel-accept-visitor-btn';
            btn.setAttribute('aria-label', 'Accept chat');
            btn.textContent = 'Accept';
            btn.addEventListener('click', function() { doAccept(p); });
            li.appendChild(name);
            li.appendChild(btn);
            pendingList.appendChild(li);
        });
        if (pendingSection) pendingSection.style.display = pending.length ? '' : 'none';
    }

    function doAccept(p) {
        var form = new FormData();
        form.append('operator_channel_id', channelId);
        form.append('dialog_thread_id', String(p.dialog_thread_id || ''));
        form.append('visitor_session_id', p.visitor_session_id || '');
        form.append('department_id', String(p.department_id || ''));
        var xhr = new XMLHttpRequest();
        xhr.open('POST', acceptUrl, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) return;
            try {
                var data = JSON.parse(xhr.responseText || '{}');
                if (data.ok) {
                    window.location.href = base + 'channels/' + channelId + '/?thread=' + (p.dialog_thread_id || '');
                }
            } catch (e) {}
        };
        xhr.send(form);
    }

    pendingList && pendingList.querySelectorAll('.channel-accept-visitor-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var li = btn.closest('.channel-pending-item');
            if (!li) return;
            doAccept({
                visitor_session_id: li.getAttribute('data-visitor-session-id'),
                dialog_thread_id: li.getAttribute('data-dialog-thread-id'),
                department_id: li.getAttribute('data-department-id')
            });
        });
    });

    function pollPending() {
        var url = pendingUrl + '?department_id=' + encodeURIComponent(departmentId) + '&channel_id=' + encodeURIComponent(channelId);
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) return;
            try {
                var data = JSON.parse(xhr.responseText || '{}');
                var pending = data.pending_visitors || [];
                if (firstPendingPoll) {
                    firstPendingPoll = false;
                    lastPendingCount = pending.length;
                } else if (pending.length > lastPendingCount) {
                    playNewChatSound();
                    startTitleFlash();
                }
                lastPendingCount = pending.length;
                renderPendingList(pending);
            } catch (e) {}
        };
        xhr.send();
    }

    setInterval(pollPending, 3000);
    pollPending();
})();
</script>
