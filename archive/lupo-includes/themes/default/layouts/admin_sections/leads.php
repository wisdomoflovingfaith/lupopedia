<?php
/**
 * Admin Leads section view. Expects: $leads (array), $base.
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}
$leads = isset($leads) && is_array($leads) ? $leads : array();
$base = isset($base) ? $base : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '');
?>
<div class="admin-leads-section">
    <p class="admin-section-description">CRM leads from the leads database. Listed below are non-deleted leads (up to 500, newest first).</p>
    <?php if (empty($leads)): ?>
    <p class="admin-empty">No leads found.</p>
    <?php else: ?>
    <table class="admin-users-table admin-list-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>First</th>
                <th>Last</th>
                <th>Source</th>
                <th>Status</th>
                <th>Score</th>
                <th>Assigned</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($leads as $row): ?>
            <tr>
                <td><?= (int) $row['crm_lead_id'] ?></td>
                <td><?= htmlspecialchars(isset($row['email']) ? $row['email'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['first_name']) ? $row['first_name'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['last_name']) ? $row['last_name'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['source']) ? $row['source'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['status']) ? $row['status'] : '') ?></td>
                <td><?= (int) (isset($row['lead_score']) ? $row['lead_score'] : 0) ?></td>
                <td><?= isset($row['assigned_to']) && $row['assigned_to'] !== null ? (int) $row['assigned_to'] : '—' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
