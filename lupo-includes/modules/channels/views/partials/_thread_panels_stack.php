<?php
/**
 * All active threads as stacked colored panels. Each panel shows full conversation history.
 * Colors from dialog_threads.bg_color. No tabs — all threads on one screen.
 */
if (!isset($threads_with_messages)) {
    $threads_with_messages = [];
}
?>
<?php if (empty($threads_with_messages)): ?>
    <p class="channel-no-threads">No active threads. New conversations will appear here as colored panels.</p>
<?php else: ?>
    <?php foreach ($threads_with_messages as $thread): ?>
        <?php
        $tid = (int)($thread['dialog_thread_id'] ?? 0);
        $bg = isset($thread['bg_color']) && preg_match('/^[0-9A-Fa-f]{6}$/', $thread['bg_color']) ? $thread['bg_color'] : 'FFFACD';
        $msgs = isset($thread['messages']) ? $thread['messages'] : [];
        $title = htmlspecialchars($thread['task_name'] ?? $thread['summary_text'] ?? 'Thread #' . $tid);
        ?>
        <section class="channel-thread-panel" id="thread-<?= $tid ?>" data-thread-id="<?= $tid ?>" style="background-color: #<?= $bg ?>;">
            <h3 class="channel-thread-panel-title"><?= $title ?></h3>
            <span class="channel-thread-panel-status"><?= htmlspecialchars($thread['status'] ?? '') ?></span>
            <div class="channel-thread-panel-messages" role="log" aria-label="Conversation for <?= $title ?>">
                <?php if (empty($msgs)): ?>
                    <p class="channel-thread-no-messages">No messages yet.</p>
                <?php else: ?>
                    <ul class="channel-thread-messages-list">
                        <?php foreach ($msgs as $m): ?>
                            <li class="channel-thread-message" data-message-id="<?= (int)($m['dialog_message_id'] ?? 0) ?>">
                                <span class="channel-thread-message-meta"><?= htmlspecialchars($m['created_ymdhis'] ?? '') ?> · from <?= (int)($m['from_actor_id'] ?? 0) ?></span>
                                <span class="channel-thread-message-text"><?= htmlspecialchars($m['message_text'] ?? '') ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>
<?php endif; ?>
