<?php
/**
 * Unified message composer: send to selected thread. Legacy: admin_chat_bot.php.
 * Selecting tab (thread) changes only this composer target; stream stays unified.
 * Typing: POST preview every 5s when length > 2; clear on send (legacy writediv).
 * All paths use LUPOPEDIA_PUBLIC_PATH.
 */
$threads = isset($threads) ? $threads : [];
$channel_id = isset($channel_id) ? (int) $channel_id : 0;
$base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
$selected_thread_id = isset($selected_thread_id) ? (int) $selected_thread_id : 0;
$first_thread_id = !empty($threads) ? (int)($threads[0]['dialog_thread_id'] ?? 0) : 0;
if ($selected_thread_id <= 0) {
    $selected_thread_id = $first_thread_id;
}
$current_actor_id = isset($current_actor_id) ? (int) $current_actor_id : 0;
$actor_name = isset($current_actor_name) ? $current_actor_name : (isset($actor_names[$current_actor_id]) ? $actor_names[$current_actor_id] : 'Operator');
?>
<form class="channel-composer-form" id="channel-composer-form" action="<?= $base ?>/api/channel/send" method="post"
      data-channel-id="<?= $channel_id ?>"
      data-typing-url="<?= htmlspecialchars($base . '/api/channel/typing') ?>"
      data-send-url="<?= htmlspecialchars($base . '/api/channel/send') ?>"
      data-actor-id="<?= $current_actor_id ?>"
      data-actor-name="<?= htmlspecialchars($actor_name) ?>">
    <input type="hidden" name="channel_id" value="<?= $channel_id ?>">
    <label for="channel-composer-thread" class="channel-composer-label">Send to:</label>
    <select id="channel-composer-thread" name="dialog_thread_id" class="channel-composer-select" aria-label="Select thread to reply to">
        <?php if (empty($threads)): ?>
            <option value="">No threads</option>
        <?php else: ?>
            <?php foreach ($threads as $t): ?>
                <?php $tid = (int)($t['dialog_thread_id'] ?? 0); $label = htmlspecialchars($t['task_name'] ?? $t['summary_text'] ?? 'Thread #' . $tid); ?>
                <option value="<?= $tid ?>"<?= $tid === $selected_thread_id ? ' selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    <label for="channel-message-input" class="sr-only">Message</label>
    <textarea id="channel-message-input" name="message_text" class="channel-composer-input" rows="2" placeholder="Type a message..." maxlength="1000" aria-label="Message to send"></textarea>
    <button type="submit" class="channel-send-btn">Send</button>
</form>
<script>
(function() {
    var form = document.getElementById('channel-composer-form');
    var input = document.getElementById('channel-message-input');
    var threadSelect = document.getElementById('channel-composer-thread');
    if (!form || !input) return;
    var typingUrl = form.getAttribute('data-typing-url') || '';
    var base = form.getAttribute('data-send-url') ? form.getAttribute('data-send-url').replace(/\/api\/channel\/send$/, '') : '';
    if (!typingUrl) typingUrl = base + '/api/channel/typing';
    var channelId = form.getAttribute('data-channel-id') || '';
    var actorId = form.getAttribute('data-actor-id') || '';
    var actorName = form.getAttribute('data-actor-name') || 'Operator';
    var typingInterval = 5000;
    var lastTypingValue = '';

    function postTyping(previewText) {
        var threadId = threadSelect ? (threadSelect.value || '') : '';
        if (!threadId) return;
        var body = 'channel_id=' + encodeURIComponent(channelId) + '&dialog_thread_id=' + encodeURIComponent(threadId) + '&actor_id=' + encodeURIComponent(actorId) + '&actor_name=' + encodeURIComponent(actorName) + '&preview_text=' + encodeURIComponent(previewText);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', typingUrl, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(body);
    }

    function clearTyping() {
        var threadId = threadSelect ? (threadSelect.value || '') : '';
        if (threadId) postTyping('');
        lastTypingValue = '';
    }

    setInterval(function() {
        var val = (input.value || '').trim();
        if (val.length > 2) {
            if (val !== lastTypingValue) {
                postTyping(val);
                lastTypingValue = val;
            }
        } else {
            if (lastTypingValue !== '') clearTyping();
        }
    }, typingInterval);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var threadId = threadSelect ? (threadSelect.value || '') : '';
        var msg = (input.value || '').trim();
        if (!threadId || msg === '') return;
        clearTyping();
        var sendUrl = (form.getAttribute('data-send-url') || base + '/api/channel/send');
        var body = 'channel_id=' + encodeURIComponent(channelId) + '&dialog_thread_id=' + encodeURIComponent(threadId) + '&message_text=' + encodeURIComponent(msg);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', sendUrl, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) return;
            input.value = '';
            lastTypingValue = '';
            var ev = new CustomEvent('channel-message-sent');
            document.getElementById('channel-operator-interface').dispatchEvent(ev);
            var ifr = document.getElementById('channel-stream-iframe');
            if (ifr && ifr.contentWindow) {
                ifr.contentWindow.postMessage('channel-message-sent', '*');
            }
        };
        xhr.send(body);
    });
})();
</script>
