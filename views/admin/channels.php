<?php
/**
 * Admin Channels section view. Expects: $channels (array), $metrics (array).
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}
$channels = isset($channels) && is_array($channels) ? $channels : array();
?>
<div class="admin-table-container">
    <table class="admin-table" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Key</th>
                <th>Name</th>
                <th>Type</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Department</th>
                <th>Threads</th>
                <th>Messages</th>
                <th>Agents</th>
                <th>Users</th>
                <th>Last Activity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($channels as $row): ?>
            <tr class="<?= (isset($row['department_id']) && $row['department_id'] == 0) ? 'dept-0-highlight' : '' ?>">
                <td><?= (int) $row['channel_id'] ?></td>
                <td><?= htmlspecialchars(isset($row['channel_key']) ? $row['channel_key'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['channel_name']) ? $row['channel_name'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['channel_type']) ? $row['channel_type'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['channel_slug']) ? $row['channel_slug'] : '') ?></td>
                <td><?= (int) (isset($row['status_flag']) ? $row['status_flag'] : 0) ?></td>
                <td><?= (int) (isset($row['department_id']) ? $row['department_id'] : 0) ?></td>
                <td><?= isset($metrics[$row['channel_id']]['threads']) ? number_format($metrics[$row['channel_id']]['threads']) : 0 ?></td>
                <td><?= isset($metrics[$row['channel_id']]['messages']) ? number_format($metrics[$row['channel_id']]['messages']) : 0 ?></td>
                <td><?= isset($metrics[$row['channel_id']]['agents']) ? number_format($metrics[$row['channel_id']]['agents']) : 0 ?></td>
                <td><?= isset($metrics[$row['channel_id']]['users']) ? number_format($metrics[$row['channel_id']]['users']) : 0 ?></td>
                <td><?php 
                    $last = isset($metrics[$row['channel_id']]['last_activity']) ? $metrics[$row['channel_id']]['last_activity'] : null;
                    echo ($last && $last > 0) ? date('Y-m-d H:i:s', $last) : 'Never';
                ?></td>
                <td>
                    <a href="admin.php?section=channel_view&id=<?= (int) $row['channel_id'] ?>" class="btn btn-primary">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
.admin-table-container {
    overflow-x: auto;
    width: 100%;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th,
.admin-table td {
    padding: 6px 8px;
    white-space: nowrap;
}

.dept-0-highlight {
    background-color: #e3f2fd !important;
    border-left: 4px solid #0066cc !important;
}

.btn {
    display: inline-block;
    padding: 4px 8px;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.9em;
    font-weight: bold;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
    text-decoration: none;
}
</style>
