<?php
/**
 * Operational Visibility - Channel Thread List
 * 
 * Shows all threads in a specific channel with sorting options
 * Database-backed, read-only interface
 * 
 * @author HEPHAESTUS (actor_id 59)
 * @thread 1030
 * @version 4.0.84
 */

// Bootstrap
require_once dirname(__DIR__, 2) . '/lupopedia-config.php';

try {
    $db = DatabaseFactory::getConnection();
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get channel ID from URL
$channel_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Validate channel exists
$channel_query = "
    SELECT 
        channel_id,
        COALESCE(channel_name, CONCAT('Channel ', channel_id)) as channel_name
    FROM lupo_channels 
    WHERE channel_id = ? AND is_deleted = 0
";

$channel = null;
try {
    $stmt = $db->prepare($channel_query);
    $stmt->bind_param('i', $channel_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $channel = $result->fetch_assoc();
} catch (Exception $e) {
    $error_message = "Database query failed: " . $e->getMessage();
}

if (!$channel) {
    http_response_code(404);
    die("Channel not found");
}

// Get sorting preference
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'updated_desc';
$valid_sorts = ['updated_desc', 'status', 'thread_id'];
if (!in_array($sort, $valid_sorts)) {
    $sort = 'updated_desc';
}

// Query for threads in this channel
$threads_query = "
    SELECT 
        t.thread_id,
        t.task_id,
        t.title,
        t.status,
        t.actor_name,
        t.thread_role,
        t.parent_thread_id,
        t.root_thread_id,
        t.lineage_depth,
        t.updated_ymdhis,
        t.created_ymdhis,
        t.rollup_scope
    FROM lupo_dialog_threads t
    WHERE t.channel_id = ? AND t.is_deleted = 0
    ORDER BY 
        CASE 
            WHEN ? = 'updated_desc' THEN t.updated_ymdhis
            WHEN ? = 'status' THEN CASE 
                WHEN t.status = 'active' THEN 1
                WHEN t.status = 'blocked' THEN 2
                WHEN t.status = 'pending' THEN 3
                WHEN t.status = 'resolved' THEN 4
                ELSE 5
            END
            ELSE t.thread_id
        END DESC,
        t.thread_id DESC
";

$threads = [];
$total_threads = 0;
$status_counts = [
    'active' => 0,
    'blocked' => 0,
    'pending' => 0,
    'resolved' => 0,
    'completed' => 0
];

try {
    $stmt = $db->prepare($threads_query);
    $stmt->bind_param('iss', $channel_id, $sort, $sort);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $threads[] = $row;
        $total_threads++;
        if (isset($status_counts[$row['status']])) {
            $status_counts[$row['status']]++;
        }
    }
} catch (Exception $e) {
    $error_message = "Database query failed: " . $e->getMessage();
}

// Page metadata
$page_title = "Channel {$channel['channel_id']} — {$channel['channel_name']}";
$breadcrumb = [
    ['name' => 'Channels Overview', 'url' => '/visibility/'],
    ['name' => $channel['channel_name'], 'url' => "/visibility/channel/{$channel_id}/"]
];
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
            <h1>Channel <?php echo htmlspecialchars($channel['channel_id']); ?></h1>
            <h2><?php echo htmlspecialchars($channel['channel_name']); ?></h2>
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
                <a href="/visibility/" class="btn btn-secondary">All Channels</a>
            </div>
        </header>

        <main>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <strong>Error:</strong> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <section class="channel-stats">
                <h3>Channel Overview</h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?php echo $total_threads; ?></h3>
                        <p>Total Threads</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $status_counts['active']; ?></h3>
                        <p>Active</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $status_counts['blocked']; ?></h3>
                        <p>Blocked</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $status_counts['pending']; ?></h3>
                        <p>Pending</p>
                    </div>
                </div>
            </section>

            <section class="threads-table">
                <div class="sorting-controls">
                    <h3>Threads (<?php echo $total_threads; ?> total)</h3>
                    <div class="sort-options">
                        <button class="sort-btn <?php echo $sort === 'updated_desc' ? 'active' : ''; ?>" 
                                data-sort="updated_desc" 
                                href="/visibility/channel/<?php echo $channel_id; ?>/?sort=updated_desc">
                            Updated ↓
                        </button>
                        <button class="sort-btn <?php echo $sort === 'status' ? 'active' : ''; ?>" 
                                data-sort="status" 
                                href="/visibility/channel/<?php echo $channel_id; ?>/?sort=status">
                            Status
                        </button>
                        <button class="sort-btn <?php echo $sort === 'thread_id' ? 'active' : ''; ?>" 
                                data-sort="thread_id" 
                                href="/visibility/channel/<?php echo $channel_id; ?>/?sort=thread_id">
                            Thread ID ↓
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Thread ID</th>
                                <th>Task ID</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Actor</th>
                                <th>Role</th>
                                <th>Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($threads as $thread): ?>
                                <tr>
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
                                            <?php echo htmlspecialchars(substr($thread['title'], 0, 60)); ?>
                                            <?php if (strlen($thread['title']) > 60): ?>...<?php endif; ?>
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
                                        <?php echo htmlspecialchars($thread['thread_role']); ?>
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
                        <p>No threads found in this channel.</p>
                    </div>
                <?php endif; ?>
            </section>

            <section class="thread-info">
                <h3>Thread Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Thread ID:</strong> Unique identifier for the thread
                    </div>
                    <div class="info-item">
                        <strong>Task ID:</strong> Task identifier if thread represents a specific task
                    </div>
                    <div class="info-item">
                        <strong>Status:</strong> Current state (active, blocked, pending, resolved, completed)
                    </div>
                    <div class="info-item">
                        <strong>Actor:</strong> Primary responsible agent or persona
                    </div>
                    <div class="info-item">
                        <strong>Role:</strong> Thread hierarchy (parent, child, derived, legacy_flat)
                    </div>
                    <div class="info-item">
                        <strong>Updated:</strong> Last modification timestamp (UTC)
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <p>
                <strong>Lupopedia Operational Visibility</strong> • 
                Channel <?php echo htmlspecialchars($channel['channel_id']); ?> • 
                Database-backed, read-only interface
            </p>
        </footer>
    </div>

    <script src="/lupo-views/visibility/js/navigation.js"></script>
</body>
</html>
