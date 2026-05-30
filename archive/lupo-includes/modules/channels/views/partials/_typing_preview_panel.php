<?php
/**
 * Floating typing-preview panel (top-right). Real-time visitor typing per thread.
 * Updates via polling; each preview uses thread bg_color. Preview disappears when visitor sends.
 * All paths use LUPOPEDIA_PUBLIC_PATH.
 */
$base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
$channel_id = isset($channel_id) ? (int) $channel_id : 0;
$thread_colors = isset($thread_colors) && is_array($thread_colors) ? $thread_colors : [];
?>
<div id="channel-typing-preview-panel" class="channel-typing-preview-panel" aria-label="Typing preview"
     data-channel-id="<?= $channel_id ?>"
     data-typing-url="<?= htmlspecialchars($base . '/api/channel/typing') ?>"
     data-thread-colors="<?= htmlspecialchars(json_encode($thread_colors)) ?>">
    <h3 class="channel-typing-preview-title">Typing preview</h3>
    <div id="channel-typing-preview-list" class="channel-typing-preview-list"></div>
</div>
<script>
(function() {
    var panel = document.getElementById('channel-typing-preview-panel');
    if (!panel) return;
    var list = document.getElementById('channel-typing-preview-list');
    var channelId = panel.getAttribute('data-channel-id');
    var typingUrl = panel.getAttribute('data-typing-url');
    var threadColors = {};
    try {
        threadColors = JSON.parse(panel.getAttribute('data-thread-colors') || '{}');
    } catch (e) {}
    var pollInterval = 2000;

    function escapeHtml(s) {
        var div = document.createElement('div');
        div.textContent = s;
        return div.innerHTML;
    }

    function render(previews) {
        if (!list) return;
        var keys = Object.keys(previews || {});
        if (keys.length === 0) {
            list.innerHTML = '<p class="channel-typing-preview-empty">No one typing</p>';
            panel.classList.remove('channel-typing-preview-has-items');
            return;
        }
        panel.classList.add('channel-typing-preview-has-items');
        var html = '';
        keys.forEach(function(threadId) {
            var p = previews[threadId];
            var bg = (threadColors[threadId] && /^[0-9A-Fa-f]{6}$/.test(threadColors[threadId])) ? threadColors[threadId] : 'FFFACD';
            html += '<div class="channel-typing-preview-block" style="background-color:#' + escapeHtml(bg) + ';" data-thread-id="' + escapeHtml(threadId) + '">';
            html += '<span class="channel-typing-preview-sender">' + escapeHtml(p.actor_name || 'Visitor') + ':</span> ';
            html += '<span class="channel-typing-preview-text">' + escapeHtml(p.preview_text || '') + '</span>';
            html += '</div>';
        });
        list.innerHTML = html;
    }

    function poll() {
        var url = typingUrl + '?channel_id=' + encodeURIComponent(channelId);
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                try {
                    var data = JSON.parse(xhr.responseText || '{}');
                    render(data.previews || {});
                } catch (e) {
                    render({});
                }
            }
        };
        xhr.send();
    }

    poll();
    setInterval(poll, pollInterval);
})();
</script>
