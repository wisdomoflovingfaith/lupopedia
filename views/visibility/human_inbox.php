<?php
/**
 * Human Requests Inbox View
 * Variables expected from route handler:
 * - $pending_requests
 * - $grouped_requests
 * - $auth_user_id
 * - $actor_id
 */

if (!isset($pending_requests) || !is_array($pending_requests)) {
    $pending_requests = array();
}
if (!isset($grouped_requests) || !is_array($grouped_requests)) {
    $grouped_requests = array();
}
if (!isset($auth_user_id)) {
    $auth_user_id = 0;
}
if (!isset($actor_id)) {
    $actor_id = 0;
}

$high_priority_count = 0;
foreach ($pending_requests as $req) {
    if (isset($req['priority']) && $req['priority'] === 'high') {
        $high_priority_count++;
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Human Requests Inbox</title>
    <link rel="stylesheet" href="/views/visibility/css/style.css">
    <style>
        .human-inbox { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .thread-group { margin-bottom: 24px; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .thread-header { background: #f3f3f3; padding: 14px 18px; border-bottom: 1px solid #ddd; }
        .thread-header h2 { margin: 0; font-size: 20px; }
        .thread-header .channel { color: #666; margin: 6px 0 0 0; font-size: 13px; }
        .requests-container { padding: 16px; }
        .request-card { border: 1px solid #e3e3e3; border-radius: 6px; padding: 14px; margin-bottom: 12px; background: #fff; }
        .request-card.priority-high { border-left: 4px solid #c62828; }
        .request-card.priority-normal { border-left: 4px solid #1565c0; }
        .request-card.priority-low { border-left: 4px solid #2e7d32; }
        .request-card .meta { color: #666; font-size: 13px; margin-bottom: 10px; }
        .request-card .description { background: #f8f8f8; padding: 12px; border-radius: 4px; white-space: pre-wrap; margin-bottom: 10px; }
        .request-card .actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .stats-bar { background: #f3f3f3; padding: 12px 16px; border-radius: 8px; margin-bottom: 18px; display: flex; gap: 24px; }
        .stat-item { text-align: center; }
        .stat-number { font-size: 22px; font-weight: bold; color: #0d47a1; }
        .stat-label { font-size: 11px; color: #666; text-transform: uppercase; }
        .empty-state { border: 1px dashed #bbb; border-radius: 8px; padding: 30px; text-align: center; color: #666; }
        .btn { display: inline-block; padding: 8px 14px; border-radius: 4px; text-decoration: none; border: 0; cursor: pointer; }
        .btn-primary { background: #1565c0; color: #fff; }
        .btn-secondary { background: #5f6368; color: #fff; }
        .modal { position: fixed; left: 0; top: 0; width: 100%; height: 100%; display: none; align-items: center; justify-content: center; background: rgba(0,0,0,.45); z-index: 9999; }
        .modal-content { width: 92%; max-width: 640px; background: #fff; border-radius: 8px; padding: 20px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 4px; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; border: 1px solid #ccc; border-radius: 4px; padding: 8px; }
        .form-actions { display: flex; gap: 8px; margin-top: 14px; }
    </style>
</head>
<body>
<div class="human-inbox">
    <h1>Human Requests Inbox</h1>

    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-number"><?php echo (int) count($pending_requests); ?></div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo (int) $high_priority_count; ?></div>
            <div class="stat-label">High Priority</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo (int) count($grouped_requests); ?></div>
            <div class="stat-label">Threads</div>
        </div>
    </div>

    <?php if (empty($grouped_requests)): ?>
        <div class="empty-state">
            <h3>No Pending Requests</h3>
            <p>You currently have no pending human-targeted requests.</p>
        </div>
    <?php else: ?>
        <?php foreach ($grouped_requests as $thread_id => $group): ?>
            <div class="thread-group">
                <div class="thread-header">
                    <h2><?php echo htmlspecialchars($group['thread_title']); ?></h2>
                    <p class="channel">Channel: <?php echo htmlspecialchars($group['channel_name']); ?></p>
                </div>

                <div class="requests-container">
                    <?php foreach ($group['requests'] as $req): ?>
                        <div class="request-card priority-<?php echo htmlspecialchars($req['priority']); ?>">
                            <h3><?php echo htmlspecialchars($req['request_title']); ?></h3>
                            <div class="meta">
                                <strong>From:</strong> <?php echo htmlspecialchars($req['initiator_name']); ?> |
                                <strong>Type:</strong> <?php echo htmlspecialchars($req['request_type']); ?> |
                                <strong>Priority:</strong> <?php echo htmlspecialchars($req['priority']); ?> |
                                <strong>Status:</strong> <?php echo htmlspecialchars($req['status']); ?> |
                                <strong>Created:</strong> <?php echo htmlspecialchars($req['created_ymdhis']); ?>
                            </div>

                            <div class="description"><?php echo htmlspecialchars($req['request_description']); ?></div>

                            <div class="actions">
                                <button class="btn btn-primary" onclick="openResponseModal('<?php echo (int) $req['request_id']; ?>', '<?php echo htmlspecialchars(addslashes($req['request_title'])); ?>')">Respond</button>
                                <a class="btn btn-secondary" href="<?php echo htmlspecialchars(LUPOPEDIA_PUBLIC_PATH . '/visibility/thread/' . (int) $req['thread_id']); ?>">View Thread</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div id="responseModal" class="modal">
    <div class="modal-content">
        <h2>Respond to Request</h2>
        <h3 id="modalRequestTitle" style="color:#666;margin-bottom:14px;"></h3>

        <form id="responseForm">
            <input type="hidden" id="request_id" name="request_id">

            <div class="form-group">
                <label for="response_type">Response Type</label>
                <select id="response_type" name="response_type" required>
                    <option value="answer">Answer</option>
                    <option value="decision">Decision</option>
                    <option value="clarification">Clarification</option>
                    <option value="escalation">Escalation</option>
                </select>
            </div>

            <div class="form-group">
                <label for="response_text">Response</label>
                <textarea id="response_text" name="response_text" rows="5" required></textarea>
            </div>

            <div class="form-group">
                <label for="reasoning">Reasoning (optional)</label>
                <textarea id="reasoning" name="reasoning" rows="3"></textarea>
            </div>

            <div class="form-group" id="decisionGroup" style="display:none;">
                <label for="decision">Decision</label>
                <select id="decision" name="decision">
                    <option value="">-- Select --</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="needs_revision">Needs Revision</option>
                    <option value="deferred">Deferred</option>
                </select>
            </div>

            <div class="form-group" id="conditionsGroup" style="display:none;">
                <label for="conditions">Conditions</label>
                <textarea id="conditions" name="conditions" rows="3"></textarea>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Submit Response</button>
                <button class="btn btn-secondary" type="button" onclick="closeResponseModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openResponseModal(requestId, requestTitle) {
    document.getElementById('request_id').value = requestId;
    document.getElementById('modalRequestTitle').textContent = requestTitle;
    document.getElementById('responseModal').style.display = 'flex';
}

function closeResponseModal() {
    document.getElementById('responseModal').style.display = 'none';
    document.getElementById('responseForm').reset();
    document.getElementById('decisionGroup').style.display = 'none';
    document.getElementById('conditionsGroup').style.display = 'none';
}

document.getElementById('response_type').addEventListener('change', function () {
    var decisionGroup = document.getElementById('decisionGroup');
    var conditionsGroup = document.getElementById('conditionsGroup');
    if (this.value === 'decision') {
        decisionGroup.style.display = 'block';
        conditionsGroup.style.display = 'block';
    } else {
        decisionGroup.style.display = 'none';
        conditionsGroup.style.display = 'none';
    }
});

document.getElementById('decision').addEventListener('change', function () {
    document.getElementById('conditionsGroup').style.display = this.value === 'needs_revision' ? 'block' : 'none';
});

document.getElementById('responseForm').addEventListener('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    var data = {};
    formData.forEach(function (v, k) { data[k] = v; });

    fetch('<?php echo htmlspecialchars(LUPOPEDIA_PUBLIC_PATH . '/visibility/human-inbox/api/respond'); ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    })
    .then(function (response) { return response.json(); })
    .then(function (result) {
        if (result.success) {
            alert('Response submitted.');
            closeResponseModal();
            location.reload();
        } else {
            alert('Error: ' + (result.error || 'Unknown error'));
        }
    })
    .catch(function (error) {
        alert('Network error: ' + error.message);
    });
});

document.getElementById('responseModal').addEventListener('click', function (e) {
    if (e.target === this) {
        closeResponseModal();
    }
});
</script>
</body>
</html>
