<?php
// Security check
require_once '../../../lupo-includes/functions/auth-helpers.php';
if (!lupo_is_admin()) {
    header('HTTP/1.0 403 Forbidden');
    exit('Access denied');
}

$channel_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Load controller
require_once '../../../lupo-includes/modules/channels/ChannelsController.php';
$channels_controller = new ChannelsController($GLOBALS['mydatabase']);

// Get channel data
try {
    $channel_data = $channels_controller->admin_view($channel_id);
} catch (Exception $e) {
    echo '<div class="admin-error-box">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    return;
}
?>

<div class="admin-section">
    <div class="admin-header">
        <h2>Channel View: <?php echo htmlspecialchars($channel_data['channel']['name']); ?></h2>
        <div class="admin-actions">
            <a href="admin.php?section=channels" class="btn btn-secondary">← Back to Channels</a>
            <?php if ($channel_data['channel']['department_id'] == 0): ?>
                <span class="dept-0-indicator">🔷 Department 0</span>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="channel-overview">
        <div class="channel-metadata">
            <h3>Channel Information</h3>
            <table class="admin-info-table">
                <tr><th>ID</th><td><?php echo $channel_data['channel']['channel_id']; ?></td></tr>
                <tr><th>Key</th><td><?php echo htmlspecialchars($channel_data['channel']['channel_key']); ?></td></tr>
                <tr><th>Name</th><td><?php echo htmlspecialchars($channel_data['channel']['name']); ?></td></tr>
                <tr><th>Type</th><td><?php echo htmlspecialchars($channel_data['channel']['channel_type']); ?></td></tr>
                <tr><th>Slug</th><td><?php echo htmlspecialchars($channel_data['channel']['slug']); ?></td></tr>
                <tr><th>Status</th><td><?php echo htmlspecialchars($channel_data['channel']['status_flag']); ?></td></tr>
                <tr><th>Department</th><td><?php echo htmlspecialchars($channel_data['channel']['department_name']); ?></td></tr>
                <tr><th>Created</th><td><?php echo date('Y-m-d H:i:s', $channel_data['channel']['created_ymdhis']); ?></td></tr>
            </table>
        </div>
        
        <div class="channel-metrics">
            <h3>Channel Metrics</h3>
            <table class="admin-metrics-table">
                <tr><th>Threads</th><td><?php echo number_format($channel_data['metrics']['thread_count']); ?></td></tr>
                <tr><th>Messages</th><td><?php echo number_format($channel_data['metrics']['message_count']); ?></td></tr>
                <tr><th>Active Agents</th><td><?php echo number_format($channel_data['metrics']['active_agents']); ?></td></tr>
                <tr><th>Active Users</th><td><?php echo number_format($channel_data['metrics']['active_users']); ?></td></tr>
                <tr><th>Last Activity</th><td><?php echo date('Y-m-d H:i:s', $channel_data['metrics']['last_activity']); ?></td></tr>
            </table>
        </div>
    </div>
    
    <div class="channel-activity">
        <h3>Recent Activity</h3>
        <table class="admin-activity-table">
            <thead>
                <tr>
                    <th>Message ID</th>
                    <th>Actor</th>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($channel_data['recent_messages'] as $message): ?>
                <tr>
                    <td><?php echo $message['dialog_message_id']; ?></td>
                    <td>
                        <?php echo htmlspecialchars($message['actor_name']); ?>
                        <span class="actor-type <?php echo $message['actor_type']; ?>">
                            <?php echo $message['actor_type']; ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($message['message_type']); ?></td>
                    <td><?php echo htmlspecialchars(substr($message['message_text'], 0, 100)); ?>...</td>
                    <td><?php echo date('Y-m-d H:i:s', $message['created_ymdhis']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="channel-participants">
        <h3>Active Participants</h3>
        
        <div class="participant-group">
            <h4>Active Agents (<?php echo count($channel_data['active_agents']); ?>)</h4>
            <table class="admin-participants-table">
                <thead>
                    <tr>
                        <th>Agent ID</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($channel_data['active_agents'] as $agent): ?>
                    <tr>
                        <td><?php echo $agent['actor_id']; ?></td>
                        <td><?php echo htmlspecialchars($agent['name']); ?></td>
                        <td><span class="status-<?php echo $agent['status']; ?>"><?php echo $agent['status']; ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="participant-group">
            <h4>Active Users (<?php echo count($channel_data['active_users']); ?>)</h4>
            <table class="admin-participants-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($channel_data['active_users'] as $user): ?>
                    <tr>
                        <td><?php echo $user['actor_id']; ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><span class="status-<?php echo $user['status']; ?>"><?php echo $user['status']; ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.admin-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ddd;
}

.dept-0-indicator {
    background: #e3f2fd;
    color: #0066cc;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
}

.channel-overview {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.channel-metrics {
    grid-column: 2;
}

.admin-info-table,
.admin-metrics-table,
.admin-activity-table,
.admin-participants-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.admin-info-table th,
.admin-metrics-table th,
.admin-activity-table th,
.admin-participants-table th {
    background: #f5f5f5;
    padding: 8px;
    text-align: left;
    border-bottom: 2px solid #ddd;
}

.admin-info-table td,
.admin-metrics-table td,
.admin-activity-table td,
.admin-participants-table td {
    padding: 8px;
    border-bottom: 1px solid #eee;
}

.actor-type {
    font-size: 0.8em;
    color: #666;
}

.status-A {
    color: #28a745;
    font-weight: bold;
}

.status-I {
    color: #ffc107;
    font-weight: bold;
}

.btn {
    display: inline-block;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 4px;
    font-weight: bold;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}
</style>
</div>
</div>
