<?php
/**
 * Operators & Visitors panel. Legacy: admin_users.
 * Shows: pending visitors (New chat request) at top, then operators, then active visitors in channel.
 */
$operators = isset($operators) ? $operators : [];
$visitors = isset($visitors) ? $visitors : [];
$pending_visitors = isset($pending_visitors) ? $pending_visitors : [];
$base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
$channel_id = isset($channel_id) ? (int) $channel_id : 0;
$department_id = isset($department_id) ? (int) $department_id : 0;
?>
<div class="channel-people-list" id="channel-people-list"
     data-channel-id="<?= $channel_id ?>"
     data-department-id="<?= $department_id ?>"
     data-base="<?= htmlspecialchars($base) ?>"
     data-pending-url="<?= htmlspecialchars($base . 'api/operator/pending-visitors') ?>"
     data-accept-url="<?= htmlspecialchars($base . 'api/operator/accept-visitor') ?>"
     data-sound-url="<?= htmlspecialchars($base . 'legacy/craftysyntax/sounds/new_chats.wav') ?>">
    <!-- Pending visitors (unassigned; all operators in department see these) -->
    <section class="channel-people-section channel-pending-section" id="channel-pending-section" aria-label="New chat requests" style="<?= empty($pending_visitors) ? 'display:none;' : '' ?>">
        <h3 class="channel-people-section-title channel-pending-title">New chat request</h3>
        <ul class="channel-pending-list" id="channel-pending-list">
            <?php foreach ($pending_visitors as $p): ?>
            <li class="channel-person-item channel-pending-item channel-pending-blink" data-visitor-session-id="<?= htmlspecialchars($p['visitor_session_id'] ?? '') ?>" data-dialog-thread-id="<?= (int)($p['dialog_thread_id'] ?? 0) ?>" data-department-id="<?= (int)($p['department_id'] ?? 0) ?>">
                <span class="channel-person-name">Visitor <?= htmlspecialchars(substr($p['visitor_session_id'] ?? '', 0, 8)) ?></span>
                <button type="button" class="channel-accept-visitor-btn" aria-label="Accept chat">Accept</button>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="channel-people-section" aria-label="Operators">
        <h3 class="channel-people-section-title">Operators</h3>
        <ul class="channel-operators-list">
            <?php if (empty($operators)): ?>
                <li class="channel-person-empty">No operators</li>
            <?php else: ?>
                <?php foreach ($operators as $op): ?>
                    <li class="channel-person-item channel-operator" data-actor-id="<?= (int)($op['actor_id'] ?? 0) ?>">
                        <span class="channel-person-name"><?= htmlspecialchars($op['actor_name'] ?? $op['actor_slug'] ?? 'Operator') ?></span>
                        <span class="channel-person-status"><?= htmlspecialchars($op['availability_status'] ?? '') ?></span>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </section>
    <section class="channel-people-section" aria-label="In channel">
        <h3 class="channel-people-section-title">In channel</h3>
        <ul class="channel-visitors-list">
            <?php if (empty($visitors)): ?>
                <li class="channel-person-empty">No visitors</li>
            <?php else: ?>
                <?php foreach ($visitors as $v): ?>
                    <li class="channel-person-item channel-visitor" data-actor-id="<?= (int)($v['actor_id'] ?? 0) ?>" data-dialog-thread-id="<?= (int)($v['dialog_thread_id'] ?? 0) ?>">
                        <span class="channel-person-name"><?= htmlspecialchars($v['actor_name'] ?? $v['actor_slug'] ?? 'Visitor') ?></span>
                        <span class="channel-person-type"><?= htmlspecialchars($v['actor_type'] ?? '') ?></span>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </section>
</div>
