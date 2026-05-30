<?php
if (!isset($threads)) {
    $threads = [];
}
?>
<ul class="channel-threads-list" aria-label="Threads in this channel">
    <?php if (empty($threads)): ?>
        <li class="channel-thread-item channel-thread-empty">No threads yet</li>
    <?php else: ?>
        <?php foreach ($threads as $t): ?>
            <li class="channel-thread-item" data-thread-id="<?= (int)($t['dialog_thread_id'] ?? 0) ?>">
                <a href="#thread-<?= (int)($t['dialog_thread_id'] ?? 0) ?>" class="channel-thread-link" data-thread-id="<?= (int)($t['dialog_thread_id'] ?? 0) ?>">
                    <span class="channel-thread-title"><?= htmlspecialchars($t['task_name'] ?? $t['summary_text'] ?? 'Thread #' . ($t['dialog_thread_id'] ?? '')) ?></span>
                    <span class="channel-thread-status"><?= htmlspecialchars($t['status'] ?? '') ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
