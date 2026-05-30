<?php
/**
 * Unified message stream: all channel messages interleaved by created_ymdhis.
 * Each message rendered as a block with its thread’s bg_color (Crafty Syntax admin_connect style).
 * No grouping by thread; single scrolling stream.
 */
if (!isset($messages)) {
    $messages = [];
}
if (!isset($thread_colors)) {
    $thread_colors = [];
}
if (!isset($actor_names)) {
    $actor_names = [];
}
?>
<ul class="channel-message-stream" id="channel-message-stream" role="log" aria-label="Channel messages (all threads)">
<?php if (empty($messages)): ?>
    <li class="channel-no-messages">No messages yet. New messages from all threads will appear here in time order.</li>
<?php else: ?>
        <?php foreach ($messages as $m): ?>
            <?php
            $tid = (int)($m['dialog_thread_id'] ?? 0);
            $bg = isset($thread_colors[$tid]) && preg_match('/^[0-9A-Fa-f]{6}$/', $thread_colors[$tid]) ? $thread_colors[$tid] : 'FFFACD';
            $from_id = (int)($m['from_actor_id'] ?? 0);
            $sender = isset($actor_names[$from_id]) ? htmlspecialchars($actor_names[$from_id]) : 'actor_' . $from_id;
            ?>
            <li class="channel-message-block" data-message-id="<?= (int)($m['dialog_message_id'] ?? 0) ?>" data-thread-id="<?= $tid ?>" style="background-color: #<?= $bg ?>;">
                <span class="channel-message-block-meta"><?= htmlspecialchars($m['created_ymdhis'] ?? '') ?></span>
                <span class="channel-message-block-sender"><?= $sender ?>:</span>
                <span class="channel-message-block-text"><?= htmlspecialchars($m['message_text'] ?? '') ?></span>
            </li>
        <?php endforeach; ?>
<?php endif; ?>
</ul>
