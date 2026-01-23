<?php
/**
 * wolfie.header.identity: ai-actors-index
 * wolfie.header.placement: /ai-actors/index.php
 * wolfie.header.version: 2026.1.0.5
 * wolfie.header.dialog:
 *   speaker: Wolfie
 *   target: ai-actors-index
 *   message: "Created AI Actors chat interface: integrates crafty syntax chat functionality with PHP agents for version 2026.1.0.5"
 * wolfie.header.mood.label: innovative
 * wolfie.header.mood.rgb: "336699"
 */

// Load Lupopedia configuration
require_once dirname(__DIR__) . '/lupopedia-config.php';

/**
 * AI Actors Chat Interface
 * 
 * This page provides a crafty syntax chat interface for interacting with PHP agents
 * Integrates legacy crafty syntax functionality with modern AI agent system
 */

// Get current agent from URL parameter or default
$current_agent_key = $_GET['agent'] ?? 'wolfie';
$department_id = $_GET['department'] ?? 1;

// Load available agents (hardcoded for now)
function get_available_agents() {
    return [
        'wolfie' => [
            'agent_key' => 'wolfie',
            'agent_name' => 'Wolfie',
            'description' => 'Lead AI architect and system coordinator',
            'archetype' => 'guide'
        ],
        'lilith' => [
            'agent_key' => 'lilith',
            'agent_name' => 'Lilith',
            'description' => 'Emotional geometry and temporal coherence specialist',
            'archetype' => 'mystic'
        ],
        'maat' => [
            'agent_key' => 'maat',
            'agent_name' => 'Maat',
            'description' => 'Governance and truth validation authority',
            'archetype' => 'judge'
        ]
    ];
}

// Load crafty syntax chat questions (hardcoded for now)
function get_chat_questions($department_id = 1) {
    return [
        [
            'headertext' => 'Name',
            'field_type' => 'username',
            'options' => '',
            'is_required' => 0,
            'sort_order' => 0
        ],
        [
            'headertext' => 'Question',
            'field_type' => 'textarea',
            'options' => '',
            'is_required' => 1,
            'sort_order' => 1
        ]
    ];
}

// Get current agent info
$agents = get_available_agents();
$current_agent = $agents[$current_agent_key] ?? $agents['wolfie'];

// Get chat questions
$chat_questions = get_chat_questions($department_id);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'] ?? '';
    $username = $_POST['username'] ?? 'Anonymous';
    $email = $_POST['email'] ?? '';
    
    if (!empty($message)) {
        // Process the message through the agent system
        // This would integrate with the actual agent response system
        $response = "Thank you for your message, {$username}. The {$current_agent['agent_name']} agent is processing your request.";
        
        // Store the conversation (simplified for now)
        session_start();
        if (!isset($_SESSION['chat_history'])) {
            $_SESSION['chat_history'] = [];
        }
        $_SESSION['chat_history'][] = [
            'timestamp' => time(),
            'username' => $username,
            'message' => $message,
            'agent' => $current_agent_key,
            'response' => $response
        ];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Actors Chat - <?php echo htmlspecialchars($current_agent['agent_name']); ?></title>
    <link rel="stylesheet" href="<?php echo LUPOPEDIA_URL; ?>/lupo-includes/css/crafty_syntax.css">
    <style>
        .ai-actors-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        
        .agent-selector {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .agent-selector select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .agent-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .agent-info h2 {
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        
        .agent-info .archetype {
            font-style: italic;
            opacity: 0.9;
        }
        
        .chat-container {
            display: flex;
            gap: 20px;
        }
        
        .chat-form {
            flex: 1;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
        }
        
        .chat-history {
            flex: 1;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        
        .required {
            color: red;
        }
        
        .submit-btn {
            background: #336699;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .submit-btn:hover {
            background: #254d7a;
        }
        
        .chat-message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
        }
        
        .user-message {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
        }
        
        .agent-response {
            background: #f3e5f5;
            border-left: 4px solid #9c27b0;
        }
        
        .message-meta {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="ai-actors-container">
        <h1>AI Actors Chat Interface</h1>
        
        <div class="agent-selector">
            <label for="agent-select">Select Agent:</label>
            <select id="agent-select" onchange="changeAgent(this.value)">
                <?php foreach ($agents as $key => $agent): ?>
                    <option value="<?php echo htmlspecialchars($key); ?>" 
                            <?php echo $key === $current_agent_key ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($agent['agent_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="agent-info">
            <h2><?php echo htmlspecialchars($current_agent['agent_name']); ?></h2>
            <div class="archetype"><?php echo htmlspecialchars($current_agent['archetype'] ?? 'AI Agent'); ?></div>
            <p><?php echo htmlspecialchars($current_agent['description'] ?? 'AI agent ready to assist you.'); ?></p>
        </div>
        
        <div class="chat-container">
            <div class="chat-form">
                <h3>Send Message</h3>
                <form method="POST" action="<?php echo defined('LUPOPEDIA_URL') ? LUPOPEDIA_URL : '/lupopedia'; ?>/ai-actors/" class="agent-chat-form">
                    <?php foreach ($chat_questions as $question): ?>
                        <div class="form-group">
                            <label for="<?php echo htmlspecialchars($question['field_type']); ?>">
                                <?php echo htmlspecialchars($question['headertext']); ?>
                                <?php if ($question['is_required']): ?>
                                    <span class="required">*</span>
                                <?php endif; ?>
                            </label>
                            <?php if ($question['field_type'] === 'textarea'): ?>
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    <?php echo $question['is_required'] ? 'required' : ''; ?>
                                    placeholder="Type your message here..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            <?php elseif ($question['field_type'] === 'username'): ?>
                                <input 
                                    type="text" 
                                    id="username" 
                                    name="username" 
                                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                    placeholder="Enter your name">
                            <?php elseif ($question['field_type'] === 'email'): ?>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                    placeholder="Enter your email"
                                    <?php echo $question['is_required'] ? 'required' : ''; ?>>
                            <?php else: ?>
                                <input 
                                    type="text" 
                                    id="<?php echo htmlspecialchars($question['field_type']); ?>" 
                                    name="<?php echo htmlspecialchars($question['field_type']); ?>"
                                    placeholder="<?php echo htmlspecialchars($question['headertext']); ?>"
                                    <?php echo $question['is_required'] ? 'required' : ''; ?>>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <button type="submit" class="submit-btn">Send Message</button>
                </form>
            </div>
            
            <div class="chat-history">
                <h3>Chat History</h3>
                <?php
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION['chat_history']) && !empty($_SESSION['chat_history'])):
                    foreach (array_reverse($_SESSION['chat_history']) as $msg):
                ?>
                    <div class="chat-message user-message">
                        <div class="message-meta">
                            <?php echo htmlspecialchars($msg['username']); ?> - 
                            <?php echo date('M j, Y H:i', $msg['timestamp']); ?>
                        </div>
                        <div><?php echo htmlspecialchars($msg['message']); ?></div>
                    </div>
                    
                    <div class="chat-message agent-response">
                        <div class="message-meta">
                            <?php echo htmlspecialchars($agents[$msg['agent']]['agent_name'] ?? 'Agent'); ?> - 
                            <?php echo date('M j, Y H:i', $msg['timestamp']); ?>
                        </div>
                        <div><?php echo htmlspecialchars($msg['response']); ?></div>
                    </div>
                <?php
                    endforeach;
                else:
                ?>
                    <p>No chat history yet. Start a conversation!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        function changeAgent(agentKey) {
            const url = new URL(window.location);
            url.searchParams.set('agent', agentKey);
            window.location.href = url.toString();
        }
        
        // Auto-scroll chat history to bottom
        document.addEventListener('DOMContentLoaded', function() {
            const chatHistory = document.querySelector('.chat-history');
            if (chatHistory) {
                chatHistory.scrollTop = chatHistory.scrollHeight;
            }
        });
    </script>
</body>
</html>
