<?php
/**
 * Admin Agents section view. Expects: $agents (array), $base.
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}
$agents = isset($agents) && is_array($agents) ? $agents : array();
$base = isset($base) ? $base : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '');
?>
<div class="admin-agents-section">
    <p class="admin-section-description">Agents are registered in the system. Listed below are non-deleted agents (up to 500).</p>
    <?php if (empty($agents)): ?>
    <p class="admin-empty">No agents found.</p>
    <?php else: ?>
    <table class="admin-users-table admin-list-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Key</th>
                <th>Name</th>
                <th>Archetype</th>
                <th>Version</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($agents as $row): ?>
            <tr>
                <td><?= (int) $row['agent_id'] ?></td>
                <td><?= htmlspecialchars(isset($row['agent_key']) ? $row['agent_key'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['agent_name']) ? $row['agent_name'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['archetype']) ? $row['archetype'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['version']) ? $row['version'] : '') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
