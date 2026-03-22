<?php
/**
 * Operational Visibility - Attention View
 * 
 * Shows threads requiring attention (active, blocked, pending)
 * Database-backed, read-only interface
 * 
 * @author HEPHAESTUS (actor_id 59)
 * @thread 1030
 * @version 4.0.84
 */

// Bootstrap
require_once dirname(__DIR__) . '/../lupo-config/lupo-config.php';

// Page metadata
$page_title = "Attention View — Threads Needing Action";
$breadcrumb = [
    ['name' => 'Channels Overview', 'url' => '/visibility/'],
    ['name' => 'Attention View', 'url' => '/visibility/attention/']
];

// Query for threads needing attention
$query = "
    SELECT 
        t.thread_id,
        t.task_id,
        t.title,
        t.status,
        t.actor_name,
        t.channel_id,
        COALESCE(c.channel_name, CONCAT('Channel ', t.channel_id)) as channel_name,
        t.updated_ymdhis,
        t.created_ymdhis
    FROM lupo_dialog_threads t
    LEFT JOIN lupo_channels c ON t.channel_id = c.channel_id AND c.is_deleted = 0
    WHERE t.status IN ('active', 'blocked', 'pending')
      AND t.is_deleted = 0
    ORDER BY 
        CASE 
            WHEN t.status = 'blocked' THEN 1
            WHEN t.status = 'active' THEN 2
            WHEN t.status = 'pending' THEN 3
        END,
        t.updated_ymdhis DESC
";

$threads = [];
$status_counts = [
    'blocked' => 0,
    'active' => 0,
    'pending' => 0
];
$total_threads = 0;

try {
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        $threads[] = $row;
        $status_counts[$row['status']]++;
        $total_threads++;
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
            <h1>Attention View</h1>
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
                <a href="/visibility/" class="btn btn-secondary">All Channels</a>
            </div>
        </header>

        <main>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <strong>Error:</strong> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <section class="attention-stats">
                <h2>Threads Requiring Attention</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?php echo $total_threads; ?></h3>
                        <p>Total Needing Action</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $status_counts['blocked']; ?></h3>
                        <p>Blocked</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $status_counts['active']; ?></h3>
                        <p>Active</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $status_counts['pending']; ?></h3>
                        <p>Pending</p>
                    </div>
                </div>
            </section>

            <section class="priority-breakdown">
                <h3>Priority Breakdown</h3>
                <div class="priority-items">
                    <div class="priority-item">
                        <span class="status-blocked">●</span>
                        <strong>Blocked (<?php echo $status_counts['blocked']; ?>):</strong> 
                        Waiting for dependencies or issues to be resolved
                    </div>
                    <div class="priority-item">
                        <span class="status-active">●</span>
                        <strong>Active (<?php echo $status_counts['active']; ?>):</strong> 
                        Currently being worked on, may need monitoring
                    </div>
                    <div class="priority-item">
                        <span class="status-pending">●</span>
                        <strong>Pending (<?php echo $status_counts['pending']; ?>):</strong> 
                        Awaiting start, ready to begin work
                    </div>
                </div>
            </section>

            <section class="attention-table">
                <h3>Action Items (<?php echo $total_threads; ?> total)</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Priority</th>
                                <th>Thread ID</th>
                                <th>Task ID</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Actor</th>
                                <th>Channel</th>
                                <th>Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($threads as $thread): ?>
                                <tr>
                                    <td>
                                        <?php if ($thread['status'] === 'blocked'): ?>
                                            <span class="status-blocked" title="High Priority">HIGH</span>
                                        <?php elseif ($thread['status'] === 'active'): ?>
                                            <span class="status-active" title="Medium Priority">MED</span>
                                        <?php else: ?>
                                            <span class="status-pending" title="Low Priority">LOW</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/visibility/thread/<?php echo $thread['thread_id']; ?>/" 
                                           data-thread-id="<?php echo $thread['thread_id']; ?>">
                                            <?php echo $thread['thread_id']; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($thread['task_id']): ?>
                                            <span title="<?php echo htmlspecialchars($thread['task_id']); ?>">
                                                <?php echo substr($thread['task_id'], 0, 20); ?>...
                                            </span>
                                        <?php else: ?>
                                            <span class="status-inactive">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span title="<?php echo htmlspecialchars($thread['title']); ?>">
                                            <?php echo htmlspecialchars(substr($thread['title'], 0, 50)); ?>
                                            <?php if (strlen($thread['title']) > 50): ?>...<?php endif; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-<?php echo $thread['status']; ?>">
                                            <?php echo htmlspecialchars($thread['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($thread['actor_name']); ?>
                                    </td>
                                    <td>
                                        <a href="/visibility/channel/<?php echo $thread['channel_id']; ?>/">
                                            <?php echo htmlspecialchars($thread['channel_name']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php 
                                        $updated = $thread['updated_ymdhis'];
                                        if (strlen($updated) >= 12) {
                                            $date = substr($updated, 0, 4) . '-' . substr($updated, 4, 2) . '-' . substr($updated, 6, 2);
                                            $time = substr($updated, 8, 2) . ':' . substr($updated, 10, 2);
                                            echo $date . ' ' . $time;
                                        } else {
                                            echo htmlspecialchars($updated);
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="/visibility/thread/<?php echo $thread['thread_id']; ?>/" 
                                           class="btn btn-sm">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (empty($threads)): ?>
                    <div class="empty-state">
                        <p>No threads currently need attention. All work is resolved!</p>
                    </div>
                <?php endif; ?>
            </section>

            <section class="workflow-guidance">
                <h3>Workflow Guidance</h3>
                <div class="guidance-items">
                    <div class="guidance-item">
                        <h4>For Blocked Threads</h4>
                        <p>Review the thread detail to understand what dependencies are blocking progress. 
                           Focus on resolving the blocking issues first.</p>
                    </div>
                    <div class="guidance-item">
                        <h4>For Active Threads</h4>
                        <p>Monitor progress and provide guidance if needed. 
                           Check if any assistance or decisions are required.</p>
                    </div>
                    <div class="guidance-item">
                        <h4>For Pending Threads</h4>
                        <p>These are ready to start. Review the thread details and assign resources 
                           or provide initial direction to begin work.</p>
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <p>
                <strong>Lupopedia Operational Visibility</strong> • 
                Attention View • 
                Database-backed, read-only interface
            </p>
        </footer>
    </div>

    <script src="/lupo-views/visibility/js/navigation.js"></script>
</body>
</html>
