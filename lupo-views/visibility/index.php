<?php
/**
 * Operational Visibility - Channels Overview
 * 
 * Provides overview of all channels with thread counts and status
 * Database-backed, read-only interface
 * 
 * @author HEPHAESTUS (actor_id 59)
 * @thread 1030
 * @version 4.0.84
 */

// Bootstrap
require_once dirname(__DIR__) . '/../lupo-config/lupo-config.php';

// Page metadata
$page_title = "Operational Visibility - Channels Overview";
$breadcrumb = [
    ['name' => 'Channels Overview', 'url' => '/visibility/']
];

// Query for channel overview
$query = "
    SELECT 
        c.channel_id,
        COALESCE(c.channel_name, CONCAT('Channel ', c.channel_id)) as channel_name,
        COUNT(t.thread_id) as total_threads,
        SUM(CASE WHEN t.status = 'active' THEN 1 ELSE 0 END) as active_threads,
        SUM(CASE WHEN t.status = 'blocked' THEN 1 ELSE 0 END) as blocked_threads,
        SUM(CASE WHEN t.status = 'pending' THEN 1 ELSE 0 END) as pending_threads,
        SUM(CASE WHEN t.status IN ('active', 'blocked', 'pending') THEN 1 ELSE 0 END) as needs_attention
    FROM lupo_channels c
    LEFT JOIN lupo_dialog_threads t ON c.channel_id = t.channel_id AND t.is_deleted = 0
    WHERE c.is_deleted = 0
    GROUP BY c.channel_id, c.channel_name
    ORDER BY c.channel_id
";

$channels = [];
$total_channels = 0;
$total_active_threads = 0;
$total_needs_attention = 0;

try {
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        $channels[] = $row;
        $total_channels++;
        $total_active_threads += $row['active_threads'];
        $total_needs_attention += $row['needs_attention'];
    }
} catch (Exception $e) {
    $error_message = "Database query failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="/lupo-views/visibility/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Operational Visibility</h1>
            <nav class="breadcrumb">
                <?php foreach ($breadcrumb as $i => $crumb): ?>
                    <?php if ($i > 0): ?> → <?php endif; ?>
                    <?php if ($i === count($breadcrumb) - 1): ?>
                        <span class="current"><?php echo htmlspecialchars($crumb['name']); ?></span>
                    <?php else: ?>
                        <a href="<?php echo htmlspecialchars($crumb['url']); ?>"><?php echo htmlspecialchars($crumb['name']); ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>
            <div class="header-actions">
                <a href="/visibility/attention/" class="btn btn-primary">Attention View</a>
            </div>
        </header>

        <main>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <strong>Error:</strong> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <section class="overview-stats">
                <h2>Channel Overview</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?php echo $total_channels; ?></h3>
                        <p>Total Channels</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $total_active_threads; ?></h3>
                        <p>Active Threads</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $total_needs_attention; ?></h3>
                        <p>Need Attention</p>
                    </div>
                </div>
            </section>

            <section class="channels-table">
                <h3>Channels (<?php echo count($channels); ?> active)</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Channel ID</th>
                                <th>Channel Name</th>
                                <th>Total Threads</th>
                                <th>Active</th>
                                <th>Blocked</th>
                                <th>Pending</th>
                                <th>Need Attention</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($channels as $channel): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($channel['channel_id']); ?></td>
                                    <td><?php echo htmlspecialchars($channel['channel_name']); ?></td>
                                    <td><?php echo number_format($channel['total_threads']); ?></td>
                                    <td>
                                        <?php if ($channel['active_threads'] > 0): ?>
                                            <span class="status-active"><?php echo number_format($channel['active_threads']); ?></span>
                                        <?php else: ?>
                                            <span class="status-inactive">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($channel['blocked_threads'] > 0): ?>
                                            <span class="status-blocked"><?php echo number_format($channel['blocked_threads']); ?></span>
                                        <?php else: ?>
                                            <span class="status-inactive">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($channel['pending_threads'] > 0): ?>
                                            <span class="status-pending"><?php echo number_format($channel['pending_threads']); ?></span>
                                        <?php else: ?>
                                            <span class="status-inactive">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($channel['needs_attention'] > 0): ?>
                                            <span class="status-needs-attention"><?php echo number_format($channel['needs_attention']); ?></span>
                                        <?php else: ?>
                                            <span class="status-inactive">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/visibility/channel/<?php echo $channel['channel_id']; ?>/" class="btn btn-sm">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="legend">
                <h3>Status Legend</h3>
                <div class="legend-items">
                    <div class="legend-item">
                        <span class="status-active">●</span> Active: Work in progress
                    </div>
                    <div class="legend-item">
                        <span class="status-blocked">●</span> Blocked: Waiting for dependencies
                    </div>
                    <div class="legend-item">
                        <span class="status-pending">●</span> Pending: Awaiting start
                    </div>
                    <div class="legend-item">
                        <span class="status-needs-attention">●</span> Need Attention: Active + Blocked + Pending
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <p>
                <strong>Lupopedia Operational Visibility</strong> • 
                Thread 1030 Implementation • 
                Database-backed, read-only interface
            </p>
        </footer>
    </div>

    <script src="/lupo-views/visibility/js/navigation.js"></script>
</body>
</html>
