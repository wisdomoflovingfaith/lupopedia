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
<div class="admin-channels-container">
    <div class="admin-header-flex">
        <h2 class="admin-title">Channel Management <span class="badge"><?= count($channels) ?> Total</span></h2>
        <div class="admin-actions">
            <button class="btn btn-secondary" onclick="window.location.reload();">Refresh Metrics</button>
        </div>
    </div>

    <div class="admin-table-wrapper card">
        <table class="premium-table">
            <thead>
                <tr>
                    <th>Channel Profile</th>
                    <th>Type</th>
                    <th class="stat-col">Threads</th>
                    <th class="stat-col">Active (24h)</th>
                    <th class="stat-col" title="Total Tickets">Tickets</th>
                    <th class="stat-col" title="Open Tickets">Open</th>
                    <th>Last Activity</th>
                    <th class="actions-col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($channels as $row): ?>
                    <?php
                    $lastActivity = $row['last_activity'] > 0 ? date('Y-m-d H:i:s', strtotime($row['last_activity'])) : 'Never';
                    $isSystem = (isset($row['department_id']) && $row['department_id'] == 0);
                    ?>
                    <tr class="<?= $isSystem ? 'system-row' : '' ?>">
                        <td>
                            <div class="channel-info">
                                <span class="channel-name"><?= htmlspecialchars($row['channel_name'] ?: 'Unnamed') ?></span>
                                <span class="channel-key"><?= htmlspecialchars($row['channel_key'] ?: 'no_key') ?></span>
                            </div>
                        </td>
                        <td>
                            <span
                                class="type-tag type-<?= htmlspecialchars($row['channel_type']) ?>"><?= htmlspecialchars(ucfirst($row['channel_type'])) ?></span>
                        </td>
                        <td class="stat-value"><?= (int) $row['thread_count'] ?></td>
                        <td class="stat-value">
                            <span class="actor-count <?= $row['active_actors_24h'] > 0 ? 'active' : '' ?>">
                                <?= (int) $row['active_actors_24h'] ?>
                            </span>
                        </td>
                        <td class="stat-value"><?= (int) $row['ticket_count'] ?></td>
                        <td class="stat-value">
                            <?php if ($row['open_tickets'] > 0): ?>
                                <span class="open-ticket-badge"><?= (int) $row['open_tickets'] ?></span>
                            <?php else: ?>
                                <span class="zero-stat">0</span>
                            <?php endif; ?>
                        </td>
                        <td class="activity-cell">
                            <span class="timestamp"
                                title="<?= $lastActivity ?>"><?= $row['last_activity'] > 0 ? substr($row['last_activity'], 0, 8) . '...' : 'None' ?></span>
                        </td>
                        <td class="actions-cell">
                            <div class="action-group">
                                <a href="admin.php?section=channel_view&id=<?= (int) $row['channel_id'] ?>" class="icon-btn"
                                    title="View Detail">View</a>
                                <a href="admin.php?section=channel_threads&id=<?= (int) $row['channel_id'] ?>"
                                    class="icon-btn" title="Threads">Threads</a>
                                <a href="admin.php?section=channel_tickets&id=<?= (int) $row['channel_id'] ?>"
                                    class="icon-btn" title="Tickets">Tickets</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700&display=swap');

    .admin-channels-container {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding: 20px;
        background: #f8fafc;
    }

    .admin-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .admin-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .badge {
        font-size: 0.875rem;
        background: #e2e8f0;
        color: #475569;
        padding: 2px 10px;
        border-radius: 20px;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .premium-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .premium-table th {
        background: #f1f5f9;
        padding: 12px 16px;
        font-weight: 600;
        color: #64748b;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        border-bottom: 1px solid #e2e8f0;
    }

    .premium-table tr {
        transition: background-color 0.2s;
    }

    .premium-table tr:hover {
        background-color: #f8fafc;
    }

    .premium-table td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .channel-info {
        display: flex;
        flex-direction: column;
    }

    .channel-name {
        font-weight: 600;
        color: #0f172a;
        font-size: 1rem;
    }

    .channel-key {
        font-size: 0.75rem;
        color: #94a3b8;
        font-family: monospace;
    }

    .type-tag {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        background: #f1f5f9;
    }

    .type-chat_room {
        background: #dcfce7;
        color: #166534;
    }

    .type-system {
        background: #fee2e2;
        color: #991b1b;
    }

    .stat-col {
        text-align: center;
        width: 80px;
    }

    .stat-value {
        text-align: center;
        font-weight: 500;
    }

    .actor-count.active {
        color: #2563eb;
        background: #dbeafe;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .open-ticket-badge {
        background: #ef4444;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: 600;
    }

    .zero-stat {
        color: #cbd5e1;
    }

    .system-row {
        background-color: #fdf2f2;
    }

    .system-row:hover {
        background-color: #fef2f2;
    }

    .action-group {
        display: flex;
        gap: 8px;
    }

    .icon-btn {
        padding: 6px 12px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s;
    }

    .icon-btn:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #f0f7ff;
    }
</style>