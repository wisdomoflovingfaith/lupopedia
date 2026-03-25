<?php
/**
 * Admin Agents section view. Expects: $agents (array), $base.
 * Displays complete agent listing with IDE detection, metrics, and action links.
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}
$agents = isset($agents) && is_array($agents) ? $agents : array();
$base = isset($base) ? $base : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '');
?>
<div class="admin-agents-section">
    <p class="admin-section-description">Actor-centric view of agent-capable actors. Operational identity stays on the actor record; model/provider behavior belongs to lupo_agents.</p>
    <p class="admin-hint">This page lists actors flagged as agent-capable or agent-backed. Faucet or IDE execution context is separate from actor identity.</p>
    <?php if (empty($agents)): ?>
    <p class="admin-empty">No agents found.</p>
    <?php else: ?>
    <table class="admin-users-table admin-list-table">
        <thead>
            <tr>
                <th>Actor ID</th>
                <th>Actor Name</th>
                <th>Actor Type</th>
                <th>IDE Faucet Actor</th>
                <th>Status</th>
                <th>Created</th>
                <th>Last Active</th>
                <th>Actions (24h)</th>
                <th>Threads</th>
                <th>Tickets</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($agents as $row): ?>
            <?php 
                // Determine agent status
                $status = 'unknown';
                if (isset($row['last_active_ymdhis']) && $row['last_active_ymdhis'] > 0) {
                    $now = time();
                    $lastActiveTime = strtotime($row['last_active_ymdhis'] . ' UTC');
                    if ($lastActiveTime !== false) {
                        $hoursSinceActive = ($now - $lastActiveTime) / 3600;
                        if ($hoursSinceActive <= 24) {
                            $status = 'active';
                        } elseif ($hoursSinceActive <= 168) { // 7 days
                            $status = 'dormant';
                        } else {
                            $status = 'archived';
                        }
                    }
                } else {
                    $status = 'archived';
                }
                
                // Status color coding
                $statusClass = '';
                if ($status === 'active') {
                    $statusClass = 'status-active';
                } elseif ($status === 'dormant') {
                    $statusClass = 'status-dormant';
                } else {
                    $statusClass = 'status-archived';
                }
            ?>
            <tr>
                <td><?= (int) $row['actor_id'] ?></td>
                <td><?= htmlspecialchars(isset($row['agent_name']) ? $row['agent_name'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['agent_type']) ? $row['agent_type'] : '') ?></td>
                <td>
                    <?php if (isset($row['is_ide_agent']) && $row['is_ide_agent'] === 'Yes'): ?>
                        <span class="ide-agent-badge">Yes</span>
                    <?php else: ?>
                        <span class="non-ide-agent-badge">No</span>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span>
                </td>
                <td><?= isset($row['created_ymdhis']) && $row['created_ymdhis'] > 0 ? date('Y-m-d H:i', strtotime($row['created_ymdhis'] . ' UTC')) : 'Unknown' ?></td>
                <td><?= isset($row['last_active_ymdhis']) && $row['last_active_ymdhis'] > 0 ? date('Y-m-d H:i', strtotime($row['last_active_ymdhis'] . ' UTC')) : 'Never' ?></td>
                <td><?= isset($row['actions_24h']) ? (int) $row['actions_24h'] : 0 ?></td>
                <td><?= isset($row['thread_count']) ? (int) $row['thread_count'] : 0 ?></td>
                <td><?= isset($row['ticket_count']) ? (int) $row['ticket_count'] : 0 ?></td>
                <td>
                    <div class="action-links">
                        <a href="<?= htmlspecialchars($base) ?>/admin.php?section=agents&action=view&actor_id=<?= (int) $row['actor_id'] ?>" class="action-link view-link">View</a>
                        <a href="<?= htmlspecialchars($base) ?>/admin.php?section=agents&action=edit&actor_id=<?= (int) $row['actor_id'] ?>" class="action-link edit-link">Edit</a>
                        <a href="<?= htmlspecialchars($base) ?>/admin.php?section=agents&action=threads&actor_id=<?= (int) $row['actor_id'] ?>" class="action-link threads-link">Threads</a>
                        <a href="<?= htmlspecialchars($base) ?>/admin.php?section=agents&action=tickets&actor_id=<?= (int) $row['actor_id'] ?>" class="action-link tickets-link">Tickets</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <style>
    .admin-agents-section {
        margin: 20px 0;
    }
    
    .ide-agent-badge {
        background-color: #28a745;
        color: white;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: bold;
    }
    
    .non-ide-agent-badge {
        background-color: #6c757d;
        color: white;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 11px;
    }
    
    .status-badge {
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: bold;
    }
    
    .status-active {
        background-color: #28a745;
        color: white;
    }
    
    .status-dormant {
        background-color: #ffc107;
        color: #212529;
    }
    
    .status-archived {
        background-color: #6c757d;
        color: white;
    }
    
    .action-links {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }
    
    .action-link {
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 10px;
        text-decoration: none;
        color: white;
    }
    
    .view-link {
        background-color: #17a2b8;
    }
    
    .edit-link {
        background-color: #ffc107;
        color: #212529;
    }
    
    .threads-link {
        background-color: #6f42c1;
    }
    
    .tickets-link {
        background-color: #e83e8c;
    }
    
    .action-link:hover {
        opacity: 0.8;
        text-decoration: underline;
    }
    </style>
    <?php endif; ?>
</div>
