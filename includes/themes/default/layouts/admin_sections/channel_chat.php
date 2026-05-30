<?php
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}

$actors = isset($chat_data['actors']) ? $chat_data['actors'] : array();
$channels = isset($chat_data['channels']) ? $chat_data['channels'] : array();
$departments = isset($chat_data['departments']) ? $chat_data['departments'] : array();
$prefs = isset($chat_data['prefs']) ? $chat_data['prefs'] : array();
$effective_actor_id = isset($chat_data['effective_actor_id']) ? (int) $chat_data['effective_actor_id'] : 0;
$effective_reason = isset($chat_data['effective_reason']) ? $chat_data['effective_reason'] : '';
$message = isset($chat_data['message']) ? $chat_data['message'] : '';
$base = isset($chat_data['base']) ? $chat_data['base'] : '';
$api_base = isset($chat_data['api_base']) ? $chat_data['api_base'] : '';
$csrf_token = isset($chat_data['csrf_token']) ? $chat_data['csrf_token'] : '';
?>

<div class="admin-channel-chat">
    <?php if ($message !== ''): ?>
        <div class="admin-message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <p class="admin-section-description">
        Authenticated chat identity is actor-first. The server resolves the posting actor from session state, allowed actor pairings, and optional actor or department preferences.
    </p>
    <div style="margin-bottom: 1rem; padding: 0.85rem 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #334155;">
        <strong>Resolution model:</strong> auth user authenticates the human, actor supplies runtime identity, department narrows fallback context, and agent preference is advisory behavior metadata only. Client-side code no longer converts an agent preference into actor identity.
    </div>

    <form method="post" action="<?= htmlspecialchars($base . '/admin.php?section=channel-chat') ?>" style="margin-bottom: 1rem;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        <input type="hidden" name="save_chat_identity_preferences" value="1">
        <div style="display: grid; grid-template-columns: repeat(3, minmax(180px, 1fr)); gap: 10px;">
            <label>
                <span class="admin-hint">Preferred Department</span><br>
                <select id="pref_department_id" name="preferred_department_id" class="admin-input">
                    <option value="0">Any department</option>
                    <?php foreach ($departments as $d): ?>
                        <?php $did = isset($d['department_id']) ? (int) $d['department_id'] : 0; ?>
                        <option value="<?= $did ?>"<?= ((int) (isset($prefs['preferred_department_id']) ? $prefs['preferred_department_id'] : 0) === $did) ? ' selected="selected"' : '' ?>>
                            <?= htmlspecialchars((string) $did . ' - ' . (isset($d['department_name']) ? $d['department_name'] : 'Department')) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                <span class="admin-hint">Preferred Agent Context</span><br>
                <select id="pref_agent_id" name="preferred_agent_id" class="admin-input">
                    <option value="0">No agent preference</option>
                    <?php foreach ($actors as $a): ?>
                        <?php
                        $aid = isset($a['actor_id']) ? (int) $a['actor_id'] : 0;
                        $atype = isset($a['actor_type']) ? (string) $a['actor_type'] : '';
                        $is_agent = !empty($a['is_agent']) || $atype === 'agent' || $atype === 'ide_agent';
                        if (!$is_agent) {
                            continue;
                        }
                        $label = isset($a['name']) && $a['name'] !== '' ? $a['name'] : (isset($a['actor_name']) ? $a['actor_name'] : ('Actor ' . $aid));
                        ?>
                        <option value="<?= $aid ?>"<?= ((int) (isset($prefs['preferred_agent_id']) ? $prefs['preferred_agent_id'] : 0) === $aid) ? ' selected="selected"' : '' ?>>
                            <?= htmlspecialchars($label . ' (' . $aid . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                <span class="admin-hint">Preferred Actor Override</span><br>
                <select id="pref_actor_id" name="preferred_actor_id" class="admin-input">
                    <option value="0">Automatic</option>
                    <?php foreach ($actors as $a): ?>
                        <?php
                        $aid = isset($a['actor_id']) ? (int) $a['actor_id'] : 0;
                        $label = isset($a['name']) && $a['name'] !== '' ? $a['name'] : (isset($a['actor_name']) ? $a['actor_name'] : ('Actor ' . $aid));
                        ?>
                        <option value="<?= $aid ?>"<?= ((int) (isset($prefs['preferred_actor_id']) ? $prefs['preferred_actor_id'] : 0) === $aid) ? ' selected="selected"' : '' ?>>
                            <?= htmlspecialchars($label . ' (' . $aid . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
        <div style="margin-top: 10px;">
            <button type="submit" class="admin-btn admin-btn-primary">Save Chat Identity Preferences</button>
            <span class="admin-hint">Current effective actor: <strong id="effective_actor_label"><?= (int) $effective_actor_id ?></strong></span>
        </div>
        <div class="admin-hint" style="margin-top: 6px;"><?= htmlspecialchars($effective_reason) ?></div>
        <div class="admin-hint" style="margin-top: 6px;">Preferred actor override changes posting identity. Department and agent preference remain server-side resolution inputs.</div>
        <div class="admin-hint" id="acting_context_label" style="margin-top: 6px;">Posting actor is resolved on the server.</div>
    </form>

    <div style="display: grid; grid-template-columns: 240px 1fr; gap: 12px;">
        <div>
            <label>
                <span class="admin-hint">Channel</span><br>
                <select id="chat_channel_id" class="admin-input">
                    <?php foreach ($channels as $c): ?>
                        <?php $cid = isset($c['channel_id']) ? (int) $c['channel_id'] : 0; ?>
                        <option value="<?= $cid ?>"><?= htmlspecialchars((string) $cid . ' - ' . (isset($c['channel_name']) ? $c['channel_name'] : 'Channel')) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="button" id="chat_refresh" class="admin-btn" style="margin-top: 8px;">Refresh Messages</button>
            <div id="chat_status" class="admin-hint" style="margin-top: 10px;">Ready.</div>
        </div>
        <div>
            <div id="chat_messages" style="height: 340px; overflow-y: auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; font-family: ui-monospace, Menlo, Consolas, monospace; font-size: 12px;"></div>
            <div style="display: flex; gap: 8px; margin-top: 8px;">
                <textarea id="chat_body" class="admin-input" style="max-width: none; flex: 1; height: 72px;" placeholder="Type message for selected channel..."></textarea>
                <button type="button" id="chat_send" class="admin-btn admin-btn-primary">Send</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var actors = <?= json_encode($actors) ?>;
    var apiBase = <?= json_encode($api_base) ?>;
    var csrfToken = <?= json_encode($csrf_token) ?>;
    var basePath = <?= json_encode($base) ?>;
    var lastSeen = '';
    var pollTimer = null;

    function byId(id) { return document.getElementById(id); }
    function setStatus(msg) { byId('chat_status').textContent = msg; }
    function getSelectedChannelText() {
        var el = byId('chat_channel_id');
        if (!el || el.selectedIndex < 0) {
            return '';
        }
        return el.options[el.selectedIndex].text || '';
    }
    function updateActingContext(actorId) {
        var label = byId('acting_context_label');
        if (!label) {
            return;
        }
        var channelId = getSelectedChannelId();
        var channelText = getSelectedChannelText();
        if (!channelId || channelId <= 0) {
            label.textContent = 'Acting context: no channel selected.';
            return;
        }
        label.textContent = 'Acting in channel ' + channelText + ' as actor ' + String(actorId || 0) + '. Agent preference remains advisory.';
    }

    function getSelectedChannelId() {
        var v = parseInt(byId('chat_channel_id').value || '0', 10);
        return isNaN(v) ? 0 : v;
    }

    function resolveExplicitActorSelection() {
        var preferredActorId = parseInt(byId('pref_actor_id').value || '0', 10);

        if (preferredActorId > 0) {
            return preferredActorId;
        }
        return 0;
    }

    function switchActiveActor(actorId) {
        if (!actorId || actorId <= 0) {
            return Promise.resolve();
        }
        var form = new FormData();
        form.append('csrf_token', csrfToken);
        form.append('actor_id', String(actorId));
        form.append('redirect', window.location.pathname + window.location.search);
        return fetch(basePath + '/switch-actor.php', {
            method: 'POST',
            credentials: 'same-origin',
            body: form
        }).then(function () {
            byId('effective_actor_label').textContent = String(actorId);
            updateActingContext(actorId);
        });
    }

    function renderMessages(messages) {
        var box = byId('chat_messages');
        if (!messages || !messages.length) {
            if (box.innerHTML === '') {
                box.innerHTML = '<div class="admin-hint">No messages.</div>';
            }
            return;
        }
        var html = '';
        for (var i = 0; i < messages.length; i++) {
            var m = messages[i];
            var body = (m.body || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            html += '<div style="padding:6px 0; border-bottom:1px solid #edf2f7;">'
                + '<div style="color:#475569;">[' + (m.created_at || '') + '] <strong>' + (m.actor_name || ('Actor ' + m.actor_id)) + '</strong></div>'
                + '<div style="white-space:pre-wrap; color:#111827;">' + body + '</div>'
                + '</div>';
            if (m.created_at) {
                lastSeen = m.created_at;
            }
        }
        box.innerHTML += html;
        box.scrollTop = box.scrollHeight;
    }

    function loadMessages(reset) {
        var channelId = getSelectedChannelId();
        if (channelId <= 0) {
            setStatus('Choose a channel.');
            return;
        }
        if (reset) {
            lastSeen = '';
            byId('chat_messages').innerHTML = '';
        }
        var url = apiBase + '/' + channelId + '/messages?limit=100';
        if (lastSeen) {
            url += '&since=' + encodeURIComponent(lastSeen);
        }
        fetch(url, { credentials: 'same-origin' })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (!data || !data.success) {
                    throw new Error(data && data.error ? data.error.message : 'Unable to load messages.');
                }
                renderMessages(data.messages || []);
                setStatus('Loaded channel ' + channelId + '.');
                updateActingContext(parseInt(byId('effective_actor_label').textContent || '0', 10));
            })
            .catch(function (err) {
                setStatus('Load error: ' + err.message);
            });
    }

    function sendMessage() {
        var channelId = getSelectedChannelId();
        var bodyEl = byId('chat_body');
        var body = (bodyEl.value || '').trim();
        var explicitActorId = resolveExplicitActorSelection();
        var selectedActorId = explicitActorId > 0 ? explicitActorId : parseInt(byId('effective_actor_label').textContent || '0', 10);
        if (channelId <= 0 || body === '') {
            setStatus('Choose channel and enter message.');
            return;
        }
        (explicitActorId > 0 ? switchActiveActor(explicitActorId) : Promise.resolve()).then(function () {
            return fetch(apiBase + '/' + channelId + '/messages', {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    body: body,
                    message_type: 'text',
                    routing_type: 'broadcast'
                })
            });
        }).then(function (res) {
            return res.json();
        }).then(function (data) {
            if (!data || !data.success) {
                throw new Error(data && data.error ? data.error.message : 'Message send failed.');
            }
            bodyEl.value = '';
            setStatus('Message sent as actor ' + selectedActorId + ' in channel ' + channelId + '.');
            loadMessages(true);
        }).catch(function (err) {
            setStatus('Send error: ' + err.message);
        });
    }

    byId('chat_refresh').addEventListener('click', function () { loadMessages(true); });
    byId('chat_send').addEventListener('click', sendMessage);
    byId('chat_channel_id').addEventListener('change', function () {
        updateActingContext(parseInt(byId('effective_actor_label').textContent || '0', 10));
        loadMessages(true);
    });
    byId('pref_actor_id').addEventListener('change', function () {
        var explicitActorId = resolveExplicitActorSelection();
        updateActingContext(explicitActorId > 0 ? explicitActorId : parseInt(byId('effective_actor_label').textContent || '0', 10));
    });

    updateActingContext(parseInt(byId('effective_actor_label').textContent || '0', 10));
    loadMessages(true);
    pollTimer = setInterval(function () { loadMessages(false); }, 5000);
})();
</script>
