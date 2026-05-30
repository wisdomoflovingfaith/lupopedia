<?php
if (!isset($messages)) {
    $messages = [];
}
if (!isset($threads)) {
    $threads = [];
}
if (!isset($channel_id)) {
    $channel_id = 0;
}
$base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
?>
<div class="channel-chat-tabs" role="tablist" aria-label="Active threads">
    <?php if (!empty($threads)): ?>
        <?php foreach (array_slice($threads, 0, 5) as $i => $t): ?>
            <button type="button" class="channel-chat-tab" role="tab" data-thread-id="<?= (int)($t['dialog_thread_id'] ?? 0) ?>" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>">
                <?= htmlspecialchars($t['task_name'] ?? $t['summary_text'] ?? 'Thread #' . ($t['dialog_thread_id'] ?? '')) ?>
            </button>
        <?php endforeach; ?>
    <?php else: ?>
        <span class="channel-chat-tab-placeholder">No active threads</span>
    <?php endif; ?>
</div>
<div class="channel-chat-messages" role="log" aria-live="polite" aria-label="Chat messages">
    <?php if (empty($messages)): ?>
        <p class="channel-chat-empty">No messages yet. Start the conversation.</p>
    <?php else: ?>
        <ul class="channel-messages-list">
            <?php foreach ($messages as $m): ?>
                <li class="channel-message-item" data-message-id="<?= (int)($m['dialog_message_id'] ?? 0) ?>">
                    <span class="channel-message-meta"><?= htmlspecialchars($m['created_ymdhis'] ?? '') ?> · from <?= (int)($m['from_actor_id'] ?? 0) ?></span>
                    <span class="channel-message-text"><?= htmlspecialchars($m['message_text'] ?? '') ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<div class="channel-chat-compose">
    <form class="channel-chat-form" action="<?= $base ?>/api/channel/send" method="post" data-channel-id="<?= (int)$channel_id ?>">
        <input type="hidden" name="channel_id" value="<?= (int)$channel_id ?>">
        <label for="channel-message-input" class="sr-only">Message</label>
        <textarea id="channel-message-input" name="message_text" class="channel-message-input" rows="2" placeholder="Type a message..." maxlength="1000"></textarea>
        <button type="submit" class="channel-send-btn">Send</button>
    </form>
</div>
