<?php
if (!isset($operators)) {
    $operators = [];
}
if (!isset($visitors)) {
    $visitors = [];
}
$base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
?>
<div class="channel-people-list">
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
                    <li class="channel-person-item channel-visitor" data-actor-id="<?= (int)($v['actor_id'] ?? 0) ?>">
                        <span class="channel-person-name"><?= htmlspecialchars($v['actor_name'] ?? $v['actor_slug'] ?? 'Visitor') ?></span>
                        <span class="channel-person-type"><?= htmlspecialchars($v['actor_type'] ?? '') ?></span>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </section>
</div>
