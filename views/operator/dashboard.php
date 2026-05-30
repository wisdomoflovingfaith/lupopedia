<?php
/**
 * Operator Dashboard View
 * Modern interface for operator management
 */

session_start();

if (!isset($_SESSION['operator_id'])) {
    header('Location: /lupopedia/login.php');
    exit;
}

$db = DatabaseFactory::getConnection();
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

// Get operator info
$sql = "SELECT actor_id, actor_name, actor_type, metadata_json 
        FROM {$table_prefix}actors 
        WHERE actor_id = :operator_id";
$operator = $db->fetchOne($sql, ['operator_id' => $_SESSION['operator_id']]);

// Get waiting visitors count
$sql = "SELECT COUNT(*) FROM {$table_prefix}visits 
        WHERE status = 'waiting' 
        AND operator_id IS NULL";
$waiting_count = $db->fetchOne($sql, []);

// Get active chats
$sql = "SELECT v.*, a.actor_name as operator_name 
        FROM {$table_prefix}visits v
        LEFT JOIN {$table_prefix}actors a ON v.operator_id = a.actor_id
        WHERE v.status = 'active' 
        AND v.operator_id = :operator_id";
$active_chats = $db->fetchAll($sql, ['operator_id' => $_SESSION['operator_id']]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Dashboard - Lupopedia</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #f5f5f5; }
        .dashboard { display: flex; height: 100vh; }
        .sidebar { width: 300px; background: #2c3e50; color: white; padding: 20px; }
        .main { flex: 1; display: flex; flex-direction: column; }
        .header { background: white; padding: 20px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; }
        .queue-panel { background: white; margin: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; }
        .chat-panel { background: white; margin: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; flex: 1; overflow-y: auto; }
        .visitor-item { padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; }
        .visitor-item:hover { background: #f0f0f0; }
        .visitor-item.waiting { border-left: 3px solid #e74c3c; }
        .visitor-item.active { border-left: 3px solid #2ecc71; }
        .chat-message { margin-bottom: 10px; padding: 8px 12px; border-radius: 8px; }
        .chat-message.operator { background: #3498db; color: white; text-align: right; }
        .chat-message.visitor { background: #ecf0f1; color: #333; }
        button { padding: 8px 16px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #2980b9; }
        .message-input { display: flex; gap: 10px; margin-top: 20px; }
        .message-input textarea { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px; resize: vertical; }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h2>Operator Console</h2>
            <p>Welcome, <?php echo htmlspecialchars($operator['actor_name']); ?></p>
            <hr style="margin: 20px 0;">
            <h3>Stats</h3>
            <p>Waiting: <?php echo $waiting_count; ?></p>
            <p>Active: <?php echo count($active_chats); ?></p>
            <hr style="margin: 20px 0;">
            <button onclick="logout()">Logout</button>
        </div>
        
        <div class="main">
            <div class="header">
                <h1>Operator Dashboard</h1>
                <div>
                    <button onclick="refreshQueue()">Refresh</button>
                </div>
            </div>
            
            <div class="queue-panel">
                <h3>Waiting Visitors</h3>
                <div id="visitor-queue">
                    <p>Loading...</p>
                </div>
            </div>
            
            <div class="chat-panel" id="chat-panel">
                <h3>Select a visitor to start chatting</h3>
            </div>
        </div>
    </div>
    
    <script>
        let currentVisitorId = null;
        let lastMessageId = 0;
        let pollInterval = null;
        
        function refreshQueue() {
            fetch('/lupopedia/api/operator/queue.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('visitor-queue');
                    if (data.visitors && data.visitors.length > 0) {
                        container.innerHTML = data.visitors.map(v => `
                            <div class="visitor-item waiting" onclick="selectVisitor(${v.visitor_id})">
                                <strong>Visitor ${v.visitor_id}</strong><br>
                                Page: ${v.page_url || 'Unknown'}<br>
                                Waiting since: ${v.created_at}
                            </div>
                        `).join('');
                    } else {
                        container.innerHTML = '<p>No waiting visitors</p>';
                    }
                })
                .catch(error => console.error('Error:', error));
        }
        
        function selectVisitor(visitorId) {
            currentVisitorId = visitorId;
            
            // Claim visitor
            fetch('/lupopedia/api/operator/claim.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ visitor_id: visitorId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadChatHistory(visitorId);
                    startPolling(visitorId);
                }
            });
        }
        
        function loadChatHistory(visitorId) {
            fetch(`/lupopedia/api/operator/messages.php?visitor_id=${visitorId}`)
                .then(response => response.json())
                .then(data => {
                    displayMessages(data.messages);
                    if (data.messages.length > 0) {
                        lastMessageId = data.messages[data.messages.length - 1].message_id;
                    }
                });
        }
        
        function startPolling(visitorId) {
            if (pollInterval) clearInterval(pollInterval);
            pollInterval = setInterval(() => {
                fetch(`/lupopedia/api/operator/messages.php?visitor_id=${visitorId}&since=${lastMessageId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.messages && data.messages.length > 0) {
                            displayMessages(data.messages);
                            lastMessageId = data.messages[data.messages.length - 1].message_id;
                        }
                    });
            }, 2000);
        }
        
        function displayMessages(messages) {
            const container = document.getElementById('chat-panel');
            const messageHtml = messages.map(m => `
                <div class="chat-message ${m.direction === 'operator' ? 'operator' : 'visitor'}">
                    <strong>${m.direction === 'operator' ? 'You' : 'Visitor'}:</strong><br>
                    ${escapeHtml(m.message)}
                    <small style="display: block; font-size: 10px; opacity: 0.7;">${m.timestamp}</small>
                </div>
            `).join('');
            
            if (container.innerHTML.includes('<h3>')) {
                const messagesContainer = document.getElementById('chat-messages');
                if (messagesContainer) {
                    messagesContainer.innerHTML += messageHtml;
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            }
        }
        
        function sendMessage() {
            const input = document.getElementById('message-input');
            const message = input.value.trim();
            if (!message || !currentVisitorId) return;
            
            fetch('/lupopedia/api/operator/send.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    visitor_id: currentVisitorId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    input.value = '';
                    // Add own message immediately
                    const messagesContainer = document.getElementById('chat-messages');
                    messagesContainer.innerHTML += `
                        <div class="chat-message operator">
                            <strong>You:</strong><br>
                            ${escapeHtml(message)}
                            <small style="display: block; font-size: 10px; opacity: 0.7;">Just now</small>
                        </div>
                    `;
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            });
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function logout() {
            fetch('/lupopedia/api/operator/logout.php', { method: 'POST' })
                .then(() => window.location.href = '/lupopedia/login.php');
        }
        
        refreshQueue();
        setInterval(refreshQueue, 10000);
    </script>
</body>
</html>
<?php
$db->close();
?>
