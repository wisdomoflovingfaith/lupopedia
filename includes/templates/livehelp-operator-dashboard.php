<?php
/**
 * Operator Live Help Dashboard Template
 * 
 * Renders the operator interface for managing live help chats.
 * Three-panel layout: active chats (left), transcript (center), visitor info (right)
 * 
 * Template variables expected:
 * - $operator: Array with operator info (actor_id, name)
 * - $active_chats: Array of active chat objects
 * - $incoming_invitations: Array of pending invitation objects
 * - $channels: Array of channels operator belongs to
 * - $config: System config array (optional)
 */
?>
<div class="livehelp-operator-dashboard" data-operator-id="<?php echo isset($operator['actor_id']) ? (int)$operator['actor_id'] : 0; ?>">
    
    <!-- Header -->
    <div class="lh-header">
        <div class="lh-header-left">
            <h1>Live Help Dashboard</h1>
            <span class="operator-name"><?php echo htmlspecialchars($operator['name'] ?? 'Operator'); ?></span>
        </div>
        <div class="lh-header-right">
            <div class="operator-status-display">
                <span class="status-label">Status:</span>
                <select id="operator-status-dropdown" class="status-selector" data-actor-id="<?php echo (int)$operator['actor_id']; ?>">
                    <option value="online">Online</option>
                    <option value="busy">Busy</option>
                    <option value="away">Away</option>
                    <option value="offline" selected>Offline</option>
                </select>
            </div>
            <span class="last-update">Updated: <span id="last-update-time">just now</span></span>
        </div>
    </div>
    
    <!-- Main Container -->
    <div class="lh-main-container">
        
        <!-- Left Panel: Active Chats & Invitations -->
        <div class="lh-left-panel">
            
            <!-- Incoming Invitations -->
            <?php if (!empty($incoming_invitations)): ?>
            <div class="lh-section invitations-section">
                <h3 class="section-title">Incoming Invitations (<span id="invitation-count"><?php echo count($incoming_invitations); ?></span>)</h3>
                <div class="invitations-list" id="invitations-list">
                    <?php foreach ($incoming_invitations as $chat): ?>
                    <div class="invitation-card" data-chat-id="<?php echo (int)$chat['chat_collection_id']; ?>">
                        <div class="invitation-header">
                            <span class="visitor-name"><?php echo htmlspecialchars($chat['visitor_name'] ?? 'Guest'); ?></span>
                            <span class="time-badge"><?php echo time_elapsed($chat['initiated_ymdhis'] ?? ''); ?>ago</span>
                        </div>
                        <div class="invitation-actions">
                            <button class="btn btn-accept" onclick="acceptChat(<?php echo (int)$chat['chat_collection_id']; ?>)">Accept</button>
                            <button class="btn btn-decline" onclick="declineChat(<?php echo (int)$chat['chat_collection_id']; ?>)">Decline</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Active Chats -->
            <div class="lh-section active-chats-section">
                <h3 class="section-title">Active Chats (<span id="active-chat-count"><?php echo count($active_chats); ?></span>)</h3>
                <div class="active-chats-list" id="active-chats-list">
                    <?php if (empty($active_chats)): ?>
                    <div class="empty-state">
                        <p>No active chats. Waiting for incoming visitors...</p>
                    </div>
                    <?php else: ?>
                    <?php foreach ($active_chats as $chat): ?>
                    <div class="chat-card active" data-chat-id="<?php echo (int)$chat['chat_collection_id']; ?>" onclick="selectChat(<?php echo (int)$chat['chat_collection_id']; ?>)">
                        <div class="chat-header">
                            <span class="visitor-name"><?php echo htmlspecialchars($chat['visitor_name'] ?? 'Guest'); ?></span>
                            <span class="unread-badge"><?php // TODO: Show unread message count ?>></span>
                        </div>
                        <div class="chat-meta">
                            <span class="duration">Duration: <?php echo format_duration((int)($chat['duration_seconds'] ?? 0)); ?></span>
                        </div>
                        <div class="last-message-preview">
                            <?php // TODO: Show last message preview ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Center Panel: Chat Transcript -->
        <div class="lh-center-panel">
            
            <div id="current-chat-container" class="current-chat-container">
                <div class="no-chat-selected">
                    <p>Select a chat to begin or start a new conversation</p>
                </div>
            </div>
            
            <div id="chat-view" class="chat-view" style="display: none;">
                <div class="chat-title-bar">
                    <span class="chat-visitor-name" id="chat-visitor-name">Visitor</span>
                    <span class="chat-duration" id="chat-duration">00:00</span>
                    <button class="btn btn-end-chat" onclick="endCurrentChat()">End Chat</button>
                </div>
                
                <div class="chat-transcript" id="chat-transcript">
                    <!-- Messages will be populated here via AJAX -->
                </div>
                
                <div class="chat-input-area">
                    <div class="quick-replies-toolbar">
                        <label class="quick-reply-label">Quick Replies:</label>
                        <select class="quick-reply-selector" id="quick-reply-selector" onchange="insertQuickReply()">
                            <option value="">-- Select a reply --</option>
                            <?php // TODO: Load quick replies from DB ?>
                        </select>
                    </div>
                    
                    <div class="message-input-group">
                        <textarea id="message-input" class="message-input" placeholder="Type your message here..." rows="3"></textarea>
                        <button class="btn btn-send" onclick="sendMessage()">Send (Ctrl+Enter)</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Panel: Visitor Info & Actions -->
        <div class="lh-right-panel">
            <div id="visitor-info-container" class="visitor-info-container">
                <div class="no-visitor-selected">
                    <p>Select a chat to view visitor details</p>
                </div>
            </div>
            
            <div id="visitor-info" class="visitor-info" style="display: none;">
                <div class="info-section">
                    <h4>Visitor Info</h4>
                    <div class="info-field">
                        <label>Name:</label> <span id="visitor-name-info">-</span>
                    </div>
                    <div class="info-field">
                        <label>Session Duration:</label> <span id="visitor-session-duration">-</span>
                    </div>
                    <div class="info-field">
                        <label>IP Address:</label> <span id="visitor-ip">-</span>
                    </div>
                </div>
                
                <div class="info-section">
                    <h4>Visit History</h4>
                    <div class="visit-history" id="visit-history">
                        <?php // TODO: Load visit history pages ?>
                    </div>
                </div>
                
                <div class="info-section">
                    <h4>Actions</h4>
                    <button class="btn btn-send-quick-reply">Send Quick Reply</button>
                    <button class="btn btn-send-email">Email Transcript</button>
                    <button class="btn btn-convert-lead">Convert to Lead</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
.livehelp-operator-dashboard {
    display: flex;
    flex-direction: column;
    height: 100vh;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background-color: #f5f6f8;
}

.lh-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #2c3e50;
    color: white;
    padding: 15px 20px;
    border-bottom: 1px solid #34495e;
}

.lh-header h1 {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
}

.operator-name {
    font-size: 14px;
    opacity: 0.9;
    margin-left: 10px;
}

.operator-status-display {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-right: 20px;
}

.status-label {
    font-weight: 600;
    font-size: 12px;
}

.status-selector {
    padding: 6px 10px;
    border-radius: 4px;
    border: 1px solid #ecf0f1;
    background-color: white;
    color: #2c3e50;
    font-weight: 600;
    cursor: pointer;
}

.lh-main-container {
    display: flex;
    flex: 1;
    gap: 1px;
    background-color: #e0e4e9;
    overflow: hidden;
}

.lh-left-panel {
    flex: 0 0 280px;
    background-color: white;
    overflow-y: auto;
    border-right: 1px solid #ddd;
}

.lh-center-panel {
    flex: 1;
    background-color: white;
    display: flex;
    flex-direction: column;
}

.lh-right-panel {
    flex: 0 0 300px;
    background-color: #fafbfc;
    overflow-y: auto;
    border-left: 1px solid #ddd;
}

/* Sections */
.lh-section {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
}

.section-title {
    margin: 0 0 12px 0;
    font-size: 14px;
    font-weight: 600;
    color: #2c3e50;
}

/* Cards */
.invitation-card, .chat-card {
    padding: 12px;
    margin-bottom: 8px;
    background-color: #f9fafb;
    border: 1px solid #e0e4e9;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.invitation-card:hover, .chat-card:hover {
    background-color: #eef3f8;
    border-color: #0066cc;
}

.chat-card.active {
    background-color: #e3f2fd;
    border-color: #0066cc;
}

.invitation-header, .chat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.visitor-name {
    font-weight: 600;
    color: #2c3e50;
    font-size: 13px;
}

.time-badge {
    font-size: 11px;
    color: #7f8c8d;
    background-color: #ecf0f1;
    padding: 2px 6px;
    border-radius: 3px;
}

.invitation-actions {
    display: flex;
    gap: 8px;
}

.btn {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-accept {
    background-color: #27ae60;
    color: white;
    flex: 1;
}

.btn-accept:hover {
    background-color: #229954;
}

.btn-decline {
    background-color: #e74c3c;
    color: white;
    flex: 1;
}

.btn-decline:hover {
    background-color: #c0392b;
}

.btn-send {
    background-color: #0066cc;
    color: white;
    padding: 8px 16px;
}

.btn-send:hover {
    background-color: #0052a3;
}

.btn-end-chat {
    background-color: #e74c3c;
    color: white;
    padding: 6px 12px;
    font-size: 12px;
}

/* Chat View */
.no-chat-selected, .no-visitor-selected {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #7f8c8d;
    font-size: 14px;
}

.chat-view {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.chat-title-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
    background-color: #f9fafb;
    border-bottom: 1px solid #e0e4e9;
}

.chat-visitor-name {
    font-weight: 600;
    color: #2c3e50;
    font-size: 14px;
}

.chat-duration {
    font-size: 12px;
    color: #7f8c8d;
}

.chat-transcript {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.message {
    display: flex;
    margin-bottom: 12px;
    animation: slide-up 0.3s ease;
}

@keyframes slide-up {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.message.from-operator {
    justify-content: flex-end;
}

.message.from-visitor {
    justify-content: flex-start;
}

.message-body {
    max-width: 60%;
    padding: 10px 14px;
    border-radius: 8px;
    line-height: 1.4;
    font-size: 13px;
    word-wrap: break-word;
}

.message.from-operator .message-body {
    background-color: #0066cc;
    color: white;
}

.message.from-visitor .message-body {
    background-color: #e9ecef;
    color: #2c3e50;
}

.message-timestamp {
    font-size: 11px;
    color: #7f8c8d;
    margin-top: 4px;
}

/* Input Area */
.chat-input-area {
    padding: 15px 20px;
    background-color: #f9fafb;
    border-top: 1px solid #e0e4e9;
}

.quick-replies-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}

.quick-reply-label {
    font-size: 12px;
    font-weight: 600;
    color: #2c3e50;
}

.quick-reply-selector {
    flex: 1;
    padding: 6px 10px;
    border: 1px solid #e0e4e9;
    border-radius: 4px;
    font-size: 12px;
}

.message-input-group {
    display: flex;
    gap: 10px;
}

.message-input {
    flex: 1;
    padding: 10px;
    border: 1px solid #e0e4e9;
    border-radius: 4px;
    font-family: inherit;
    font-size: 13px;
    resize: none;
}

.message-input:focus {
    outline: none;
    border-color: #0066cc;
    box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
}

/* Visitor Info */
.visitor-info {
    padding: 15px;
}

.info-section {
    margin-bottom: 20px;
}

.info-section h4 {
    margin: 0 0 10px 0;
    font-size: 12px;
    font-weight: 700;
    color: #2c3e50;
    text-transform: uppercase;
}

.info-field {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
    font-size: 12px;
}

.info-field label {
    font-weight: 600;
    color: #7f8c8d;
}

.info-field span {
    color: #2c3e50;
}

.visit-history {
    font-size: 12px;
}

.visit-history-item {
    padding: 6px 0;
    border-bottom: 1px solid #e9ecef;
}

/* Empty States */
.empty-state {
    padding: 30px 15px;
    text-align: center;
    color: #7f8c8d;
    font-size: 13px;
}

/* Responsive */
@media (max-width: 1200px) {
    .lh-right-panel {
        flex-basis: 250px;
    }
    
    .message-body {
        max-width: 70%;
    }
}

@media (max-width: 900px) {
    .lh-main-container {
        flex-direction: column;
    }
    
    .lh-left-panel,
    .lh-center-panel,
    .lh-right-panel {
        flex: none;
        border: none;
    }
}
</style>

<!-- JavaScript -->
<script>
// Current chat being viewed
let currentChatId = null;
let operatorActorId = <?php echo isset($operator['actor_id']) ? (int)$operator['actor_id'] : 0; ?>;
let messagePollingInterval = null;

/**
 * Accept incoming chat invitation
 */
function acceptChat(chatId) {
    const data = {
        operator_actor_id: operatorActorId,
        chat_collection_id: chatId
    };
    
    fetch('<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/livehelp.php?action=operator_accept', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(result => {
        if (result.success) {
            // Move chat from invitations to active
            document.querySelector('[data-chat-id="' + chatId + '"]').remove();
            selectChat(chatId);
        } else {
            alert('Failed to accept chat: ' + (result.error || 'Unknown error'));
        }
    })
    .catch(err => console.error('Error:', err));
}

/**
 * Decline incoming chat invitation
 */
function declineChat(chatId) {
    const reason = prompt('Decline reason (optional):', '');
    
    const data = {
        operator_actor_id: operatorActorId,
        chat_collection_id: chatId,
        reason: reason || ''
    };
    
    fetch('<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/livehelp.php?action=operator_decline', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(result => {
        if (result.success) {
            document.querySelector('[data-chat-id="' + chatId + '"]').remove();
            alert('Chat re-routed to next available operator');
        } else {
            alert('Failed to decline chat');
        }
    })
    .catch(err => console.error('Error:', err));
}

/**
 * Select a chat to view
 */
function selectChat(chatId) {
    currentChatId = chatId;
    
    // Update UI
    document.querySelectorAll('.chat-card').forEach(el => el.classList.remove('active'));
    document.querySelector('[data-chat-id="' + chatId + '"]')?.classList.add('active');
    
    // Show chat view
    document.querySelector('.no-chat-selected')?.style.setDisplay('none');
    document.querySelector('#chat-view')?.style.setDisplay('flex');
    
    // Load messages
    pollMessages();
    startMessagePolling();
}

/**
 * Send message
 */
function sendMessage() {
    const messageInput = document.querySelector('#message-input');
    const messageBody = messageInput.value.trim();
    
    if (!messageBody || !currentChatId) return;
    
    const data = {
        channel_id: 1, // TODO: Get from current channel context
        chat_collection_id: currentChatId,
        message_body: messageBody,
        message_type: 'chat'
    };
    
    fetch('<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/livehelp.php?action=send_message', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(result => {
        if (result.success) {
            messageInput.value = '';
            pollMessages();
        } else {
            alert('Failed to send message');
        }
    })
    .catch(err => console.error('Error:', err));
}

/**
 * End current chat
 */
function endCurrentChat() {
    if (!currentChatId || !confirm('End this chat?')) return;
    
    const data = {
        chat_collection_id: currentChatId,
        reason: 'operator_ended'
    };
    
    fetch('<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/livehelp.php?action=end_chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(result => {
        if (result.success) {
            currentChatId = null;
            location.reload();
        }
    })
    .catch(err => console.error('Error:', err));
}

/**
 * Poll for new messages
 */
function pollMessages() {
    if (!currentChatId) return;
    
    const url = '<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/livehelp.php?action=poll_messages' +
                '&chat_id=' + currentChatId +
                '&since=' + (lastMessageTime || '');
    
    fetch(url)
    .then(r => r.json())
    .then(result => {
        if (result.success && result.messages.length > 0) {
            appendMessages(result.messages);
            lastMessageTime = result.messages[result.messages.length - 1].created_ymdhis;
        }
    })
    .catch(err => console.error('Error polling:', err));
}

let lastMessageTime = '';

/**
 * Append messages to transcript
 */
function appendMessages(messages) {
    const transcript = document.querySelector('#chat-transcript');
    
    messages.forEach(msg => {
        const isOperator = msg.actor_id === operatorActorId;
        const className = isOperator ? 'from-operator' : 'from-visitor';
        
        const div = document.createElement('div');
        div.className = 'message ' + className;
        div.innerHTML = `
            <div class="message-body">${escapeHtml(msg.message_body)}</div>
            <div class="message-timestamp">${msg.created_ymdhis}</div>
        `;
        
        transcript.appendChild(div);
    });
    
    // Scroll to bottom
    transcript.scrollTop = transcript.scrollHeight;
}

/**
 * Start polling for new messages
 */
function startMessagePolling() {
    if (messagePollingInterval) clearInterval(messagePollingInterval);
    messagePollingInterval = setInterval(pollMessages, 2000);
}

/**
 * Stop polling
 */
function stopMessagePolling() {
    if (messagePollingInterval) clearInterval(messagePollingInterval);
}

/**
 * Update operator status
 */
document.querySelector('#operator-status-dropdown')?.addEventListener('change', function(e) {
    const newStatus = e.target.value;
    // TODO: Call API to update status
    console.log('Status changed to:', newStatus);
});

/**
 * Keyboard shortcuts
 */
document.querySelector('#message-input')?.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'Enter') {
        sendMessage();
    }
});

/**
 * Escape HTML
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Format duration in seconds to MM:SS
 */
function formatDuration(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
}

/**
 * Time elapsed helper
 */
function calculateTimeElapsed(ymdhis) {
    const then = new Date(
        ymdhis.substring(0, 4) + '-' +
        ymdhis.substring(4, 6) + '-' +
        ymdhis.substring(6, 8) + 'T' +
        ymdhis.substring(8, 10) + ':' +
        ymdhis.substring(10, 12) + ':' +
        ymdhis.substring(12, 14) + 'Z'
    );
    
    const now = new Date();
    const diffMS = now - then;
    const diffSecs = Math.floor(diffMS / 1000);
    const diffMins = Math.floor(diffSecs / 60);
    
    if (diffMins < 1) return diffSecs + 's';
    if (diffMins < 60) return diffMins + 'm';
    const diffHours = Math.floor(diffMins / 60);
    return diffHours + 'h';
}
</script>

<?php
/**
 * Helper functions for template
 */

function time_elapsed($ymdhis) {
    if (!$ymdhis) return '';
    $then = new DateTime($ymdhis, new DateTimeZone('UTC'));
    $now = new DateTime('now', new DateTimeZone('UTC'));
    $diff = $now->diff($then);
    
    if ($diff->y > 0) return $diff->y . 'y ';
    if ($diff->m > 0) return $diff->m . 'mo ';
    if ($diff->d > 0) return $diff->d . 'd ';
    if ($diff->h > 0) return $diff->h . 'h ';
    if ($diff->i > 0) return $diff->i . 'm ';
    return $diff->s . 's ';
}

function format_duration($seconds) {
    $mins = floor($seconds / 60);
    $secs = $seconds % 60;
    return str_pad($mins, 2, '0', STR_PAD_LEFT) . ':' . str_pad($secs, 2, '0', STR_PAD_LEFT);
}
?>
