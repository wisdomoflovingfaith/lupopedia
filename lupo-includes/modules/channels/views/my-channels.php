<?php
/**
 * My Channels — list of channels the current actor has a role in.
 * Expects: $my_channels (array of channel_id, channel_name, role_type), $channel_public_path (string).
 */
$base = isset($channel_public_path) ? $channel_public_path : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '');
$channels = isset($my_channels) ? $my_channels : array();
?>
<div class="my-channels-page">
    <h1 class="my-channels-title">My Channels</h1>
    <p class="my-channels-intro">Channels where you have a role. Click a channel to open it.</p>
    <?php if (empty($channels)): ?>
        <p class="my-channels-empty">You are not assigned to any channels yet.</p>
    <?php else: ?>
        <ul class="my-channels-list">
            <?php foreach ($channels as $c): ?>
                <?php
                $cid = (int) (isset($c['channel_id']) ? $c['channel_id'] : 0);
                $name = isset($c['channel_name']) ? htmlspecialchars($c['channel_name']) : 'Channel ' . $cid;
                $role = isset($c['role_type']) ? htmlspecialchars($c['role_type']) : '';
                ?>
                <li class="my-channels-item">
                    <a href="<?= $base ?>/channels/<?= $cid ?>" class="my-channels-link"><?= $name ?></a>
                    <?php if ($role !== ''): ?>
                        <span class="my-channels-role"><?= $role ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
