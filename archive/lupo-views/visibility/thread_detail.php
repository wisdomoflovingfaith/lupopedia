<?php
/**
 * Operational Visibility - Thread Detail Read View
 * 
 * Shows comprehensive thread information with context
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

// Get thread ID from URL
$thread_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Validate thread exists and get metadata
$thread_query = "
    SELECT 
        t.thread_id,
        t.task_id,
        t.title,
        t.status,
        t.actor_name,
        t.channel_id,
        COALESCE(c.channel_name, CONCAT('Channel ', t.channel_id)) as channel_name,
        t.thread_role,
        t.parent_thread_id,
        t.root_thread_id,
        t.lineage_depth,
        t.created_ymdhis,
        t.updated_ymdhis,
        t.rollup_scope
    FROM lupo_dialog_threads t
    LEFT JOIN lupo_channels c ON t.channel_id = c.channel_id AND c.is_deleted = 0
    WHERE t.thread_id = ? AND t.is_deleted = 0
";

$thread = null;
try {
    $stmt = $db->prepare($thread_query);
    $stmt->bind_param('i', $thread_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $thread = $result->fetch_assoc();
} catch (Exception $e) {
    $error_message = "Database query failed: " . $e->getMessage();
}

if (!$thread) {
    http_response_code(404);
    die("Thread not found");
}

// Get latest artifact for this thread
$artifact_query = "
    SELECT 
        file_path_from_root,
        web_path,
        last_modified_utc,
        actor_name,
        artifact_type,
        artifact_kind,
        purpose
    FROM (
        SELECT 
            file_path_from_root,
            web_path,
            last_modified_utc,
            actor_name,
            artifact_type,
            artifact_kind,
            purpose,
            ROW_NUMBER() OVER (ORDER BY last_modified_utc DESC) as rn
        FROM lupo_artifacts 
        WHERE thread_id = ? AND is_deleted = 0
    ) a
    WHERE rn = 1
";

$latest_artifact = null;
try {
    $stmt = $db->prepare($artifact_query);
    $stmt->bind_param('i', $thread_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $latest_artifact = $result->fetch_assoc();
} catch (Exception $e) {
    // Artifact query failure is not critical
    $latest_artifact = null;
}

// Get related threads (same channel, active)
$related_query = "
    SELECT 
        thread_id,
        title,
        status,
        actor_name
    FROM lupo_dialog_threads 
    WHERE channel_id = ? 
      AND thread_id != ? 
      AND status IN ('active', 'blocked', 'pending')
      AND is_deleted = 0
    ORDER BY updated_ymdhis DESC
    LIMIT 5
";

$related_threads = [];
try {
    $stmt = $db->prepare($related_query);
    $stmt->bind_param('ii', $thread['channel_id'], $thread_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $related_threads[] = $row;
    }
} catch (Exception $e) {
    // Related threads query failure is not critical
    $related_threads = [];
}

// Page metadata
$page_title = "Thread {$thread['thread_id']} — {$thread['title']}";
$breadcrumb = [
    ['name' => 'Channels Overview', 'url' => '/visibility/'],
    ['name' => $thread['channel_name'], 'url' => "/visibility/channel/{$thread['channel_id']}/"],
    ['name' => "Thread {$thread['thread_id']}", 'url' => "/visibility/thread/{$thread_id}/"]
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
            <h1>Thread <?php echo htmlspecialchars($thread['thread_id']); ?></h1>
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
                <a href="/visibility/channel/<?php echo $thread['channel_id']; ?>/" class="btn btn-secondary">Channel <?php echo $thread['channel_id']; ?></a>
            </div>
        </header>

        <main>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <strong>Error:</strong> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <section class="thread-metadata">
                <h3>Thread Metadata</h3>
                <div class="metadata-grid">
                    <div class="metadata-item">
                        <strong>Thread ID:</strong>
                        <span data-thread-id="<?php echo $thread['thread_id']; ?>"><?php echo $thread['thread_id']; ?></span>
                    </div>
                    <div class="metadata-item">
                        <strong>Task ID:</strong>
                        <span><?php echo $thread['task_id'] ? htmlspecialchars($thread['task_id']) : '—'; ?></span>
                    </div>
                    <div class="metadata-item">
                        <strong>Status:</strong>
                        <span class="status-<?php echo $thread['status']; ?>"><?php echo htmlspecialchars($thread['status']); ?></span>
                    </div>
                    <div class="metadata-item">
                        <strong>Actor:</strong>
                        <span><?php echo htmlspecialchars($thread['actor_name']); ?></span>
                    </div>
                    <div class="metadata-item">
                        <strong>Channel:</strong>
                        <span>
                            <a href="/visibility/channel/<?php echo $thread['channel_id']; ?>/">
                                <?php echo htmlspecialchars($thread['channel_name']); ?>
                            </a>
                        </span>
                    </div>
                    <div class="metadata-item">
                        <strong>Role:</strong>
                        <span><?php echo htmlspecialchars($thread['thread_role']); ?></span>
                    </div>
                    <div class="metadata-item">
                        <strong>Parent Thread:</strong>
                        <span>
                            <?php if ($thread['parent_thread_id'] && $thread['parent_thread_id'] != $thread['thread_id']): ?>
                                <a href="/visibility/thread/<?php echo $thread['parent_thread_id']; ?>/">
                                    <?php echo $thread['parent_thread_id']; ?>
                                </a>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="metadata-item">
                        <strong>Root Thread:</strong>
                        <span>
                            <?php if ($thread['root_thread_id'] && $thread['root_thread_id'] != $thread['thread_id']): ?>
                                <a href="/visibility/thread/<?php echo $thread['root_thread_id']; ?>/">
                                    <?php echo $thread['root_thread_id']; ?>
                                </a>
                            <?php else: ?>
                                This thread
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="metadata-item">
                        <strong>Lineage Depth:</strong>
                        <span><?php echo $thread['lineage_depth']; ?></span>
                    </div>
                    <div class="metadata-item">
                        <strong>Created:</strong>
                        <span>
                            <?php 
                            $created = $thread['created_ymdhis'];
                            if (strlen($created) >= 12) {
                                $date = substr($created, 0, 4) . '-' . substr($created, 4, 2) . '-' . substr($created, 6, 2);
                                $time = substr($created, 8, 2) . ':' . substr($created, 10, 2);
                                echo $date . ' ' . $time . ' UTC';
                            } else {
                                echo htmlspecialchars($created);
                            }
                            ?>
                        </span>
                    </div>
                    <div class="metadata-item">
                        <strong>Last Updated:</strong>
                        <span>
                            <?php 
                            $updated = $thread['updated_ymdhis'];
                            if (strlen($updated) >= 12) {
                                $date = substr($updated, 0, 4) . '-' . substr($updated, 4, 2) . '-' . substr($updated, 6, 2);
                                $time = substr($updated, 8, 2) . ':' . substr($updated, 10, 2);
                                echo $date . ' ' . $time . ' UTC';
                            } else {
                                echo htmlspecialchars($updated);
                            }
                            ?>
                        </span>
                    </div>
                    <div class="metadata-item">
                        <strong>Rollup Scope:</strong>
                        <span><?php echo htmlspecialchars($thread['rollup_scope']); ?></span>
                    </div>
                </div>
            </section>

            <?php if ($latest_artifact): ?>
                <section class="artifact-info">
                    <h3>Latest Artifact</h3>
                    <div class="artifact-details">
                        <div class="artifact-detail">
                            <strong>File:</strong>
                            <span><?php echo htmlspecialchars($latest_artifact['file_path_from_root']); ?></span>
                        </div>
                        <div class="artifact-detail">
                            <strong>Type:</strong>
                            <span><?php echo htmlspecialchars($latest_artifact['artifact_type']); ?> / <?php echo htmlspecialchars($latest_artifact['artifact_kind']); ?></span>
                        </div>
                        <div class="artifact-detail">
                            <strong>Purpose:</strong>
                            <span><?php echo htmlspecialchars($latest_artifact['purpose']); ?></span>
                        </div>
                        <div class="artifact-detail">
                            <strong>Modified:</strong>
                            <span><?php echo htmlspecialchars($latest_artifact['last_modified_utc']); ?></span>
                        </div>
                        <div class="artifact-detail">
                            <strong>Actor:</strong>
                            <span><?php echo htmlspecialchars($latest_artifact['actor_name']); ?></span>
                        </div>
                    </div>
                    <div class="artifact-actions">
                        <?php if ($latest_artifact['web_path']): ?>
                            <a href="<?php echo htmlspecialchars($latest_artifact['web_path']); ?>" 
                               target="_blank" 
                               class="btn btn-primary">
                                View Full Artifact
                            </a>
                        <?php endif; ?>
                        <a href="/lupo-channels/<?php echo $thread['channel_id']; ?>/threads/<?php echo $thread['thread_id']; ?>/" 
                           target="_blank" 
                           class="btn btn-secondary">
                            View Thread Directory
                        </a>
                    </div>
                </section>
            <?php else: ?>
                <section class="artifact-info">
                    <h3>Latest Artifact</h3>
                    <p>No artifacts found for this thread.</p>
                </section>
            <?php endif; ?>

            <?php
            // Add human requests section if authenticated
            if (isset($_SESSION['auth_user_id'])) {
                require_once LUPOPEDIA_ABSPATH . '/lupo-includes/HumanRequestService.php';
                $request_service = new HumanRequestService();
                $thread_requests = $request_service->getThreadRequests($thread_id);
                $thread_request_summary = $request_service->getThreadRequestSummary($thread_id);
                
                if (!empty($thread_requests)):
            ?>
            <section class="human-requests">
                <h3>Human-Targeted Requests</h3>
                <p>
                    <strong>Dynamic Summary:</strong>
                    Total <?php echo (int) $thread_request_summary['total']; ?>,
                    Draft <?php echo (int) $thread_request_summary['draft']; ?>,
                    Pending <?php echo (int) $thread_request_summary['pending']; ?>,
                    Answered <?php echo (int) $thread_request_summary['answered']; ?>,
                    Resolved <?php echo (int) $thread_request_summary['resolved']; ?>,
                    Cancelled <?php echo (int) $thread_request_summary['cancelled']; ?>,
                    Expired <?php echo (int) $thread_request_summary['expired']; ?>.
                </p>
                
                <?php foreach ($thread_requests as $req): ?>
                    <div class="request-item status-<?php echo $req['status']; ?>">
                        <h4><?php echo htmlspecialchars($req['request_title']); ?></h4>
                        
                        <div class="request-meta">
                            <p><strong>To:</strong> <?php echo htmlspecialchars($req['target_username']); ?></p>
                            <p><strong>From:</strong> <?php echo htmlspecialchars($req['initiator_name']); ?></p>
                            <p><strong>Type:</strong> <?php echo htmlspecialchars($req['request_type']); ?></p>
                            <p><strong>Priority:</strong> <?php echo htmlspecialchars($req['priority']); ?></p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($req['status']); ?></p>
                            <p><strong>Created:</strong> <?php echo $req['created_ymdhis']; ?></p>
                        </div>
                        
                        <div class="request-description">
                            <p><?php echo nl2br(htmlspecialchars($req['request_description'])); ?></p>
                        </div>
                        
                        <?php if ($req['subject_reference']): ?>
                            <div class="request-subject">
                                <p><strong>Subject:</strong> <?php echo htmlspecialchars($req['subject_reference']); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($req['latest_response']): ?>
                            <div class="latest-response">
                                <h5>Latest Response:</h5>
                                <p><?php echo nl2br(htmlspecialchars($req['latest_response'])); ?></p>
                                <p class="response-time"><small>At: <?php echo $req['response_time']; ?></small></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($req['status'] === 'pending' && $req['target_auth_user_id'] == $_SESSION['auth_user_id']): ?>
                            <div class="request-actions">
                                <a href="/visibility/human-inbox" class="btn btn-primary">
                                    Respond to This Request
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                
                <?php if (array_filter($thread_requests, fn($r) => $r['status'] === 'pending' && $r['target_auth_user_id'] == $_SESSION['auth_user_id'])): ?>
                    <div class="requests-summary">
                        <p><strong>You have pending requests in this thread.</strong> <a href="/visibility/human-inbox">View your inbox</a> to respond.</p>
                    </div>
                <?php endif; ?>
            </section>
            <?php endif; ?>
            <?php } ?>

            <section class="thread-context">
                <h3>Thread Context</h3>
                <div class="context-content">
                    <p><strong>Title:</strong> <?php echo htmlspecialchars($thread['title']); ?></p>
                    
                    <?php if ($thread['task_id']): ?>
                        <p><strong>Task:</strong> <?php echo htmlspecialchars($thread['task_id']); ?></p>
                    <?php endif; ?>
                    
                    <p><strong>Status:</strong> 
                        <span class="status-<?php echo $thread['status']; ?>"><?php echo htmlspecialchars($thread['status']); ?></span>
                    </p>
                    
                    <p><strong>Owner:</strong> <?php echo htmlspecialchars($thread['actor_name']); ?></p>
                    
                    <p><strong>Channel:</strong> 
                        <a href="/visibility/channel/<?php echo $thread['channel_id']; ?>/">
                            <?php echo htmlspecialchars($thread['channel_name']); ?>
                        </a>
                    </p>
                    
                    <?php
                    // Generate context based on thread properties
                    $context_description = '';
                    if ($thread['status'] === 'active') {
                        $context_description = 'This thread is currently active and being worked on.';
                    } elseif ($thread['status'] === 'blocked') {
                        $context_description = 'This thread is blocked and waiting for dependencies or issues to be resolved.';
                    } elseif ($thread['status'] === 'pending') {
                        $context_description = 'This thread is pending and ready to begin work.';
                    } elseif ($thread['status'] === 'resolved') {
                        $context_description = 'This thread has been resolved and work is complete.';
                    } elseif ($thread['status'] === 'completed') {
                        $context_description = 'This thread has been completed and finalized.';
                    }
                    
                    if ($thread['thread_role'] === 'parent') {
                        $context_description .= ' This is a parent thread that may have child threads.';
                    } elseif ($thread['thread_role'] === 'child') {
                        $context_description .= ' This is a child thread derived from a parent thread.';
                    }
                    ?>
                    
                    <p><strong>Context:</strong> <?php echo $context_description; ?></p>
                </div>
            </section>

            <?php if (!empty($related_threads)): ?>
                <section class="related-threads">
                    <h3>Related Threads in Channel</h3>
                    <div class="related-thread-list">
                        <?php foreach ($related_threads as $related): ?>
                            <div class="related-thread">
                                <a href="/visibility/thread/<?php echo $related['thread_id']; ?>/" 
                                   class="thread-link">
                                    Thread <?php echo $related['thread_id']; ?>
                                </a>
                                <span class="thread-status status-<?php echo $related['status']; ?>">
                                    <?php echo htmlspecialchars($related['status']); ?>
                                </span>
                                <span class="thread-actor">
                                    by <?php echo htmlspecialchars($related['actor_name']); ?>
                                </span>
                                <span class="thread-title">
                                    <?php echo htmlspecialchars(substr($related['title'], 0, 80)); ?>
                                    <?php if (strlen($related['title']) > 80): ?>...<?php endif; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <section class="navigation-help">
                <h3>Navigation Help</h3>
                <div class="help-items">
                    <div class="help-item">
                        <strong>Click Thread IDs</strong> to copy them to clipboard
                    </div>
                    <div class="help-item">
                        <strong>Use Alt+C</strong> to go to Channels Overview
                    </div>
                    <div class="help-item">
                        <strong>Use Alt+A</strong> to go to Attention View
                    </div>
                    <div class="help-item">
                        <strong>Press Escape</strong> to go back to previous page
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <p>
                <strong>Lupopedia Operational Visibility</strong> • 
                Thread <?php echo htmlspecialchars($thread['thread_id']); ?> • 
                Database-backed, read-only interface
            </p>
        </footer>
    </div>

    <script src="/lupo-views/visibility/js/navigation.js"></script>
</body>
</html>
