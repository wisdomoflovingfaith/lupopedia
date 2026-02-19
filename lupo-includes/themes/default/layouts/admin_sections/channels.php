<?php
/**
 * Admin Channels section view. Expects: $channels (array), $base.
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}
$channels = isset($channels) && is_array($channels) ? $channels : array();
$base = isset($base) ? $base : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '');
?>
<div class="admin-channels-section">
    <p class="admin-section-description">Channels are conversation spaces. Listed below are non-deleted channels (up to 500).</p>
    <?php if (empty($channels)): ?>
    <p class="admin-empty">No channels found.</p>
    <?php else: ?>
    <table class="admin-users-table admin-list-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Key</th>
                <th>Name</th>
                <th>Type</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Department</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($channels as $row): ?>
            <tr>
                <td><?= (int) $row['channel_id'] ?></td>
                <td><?= htmlspecialchars(isset($row['channel_key']) ? $row['channel_key'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['channel_name']) ? $row['channel_name'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['channel_type']) ? $row['channel_type'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['channel_slug']) ? $row['channel_slug'] : '') ?></td>
                <td><?= (int) (isset($row['status_flag']) ? $row['status_flag'] : 0) ?></td>
                <td><?= (int) (isset($row['department_id']) ? $row['department_id'] : 0) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
