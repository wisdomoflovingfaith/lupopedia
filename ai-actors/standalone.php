<?php
/**
 * Standalone AI Actors Interface
 * 
 * This is a standalone version for testing without database dependencies
 */

// Start session for chat history
session_start();

// Get current agent from URL parameter or default
$current_agent_key = $_GET['agent'] ?? 'wolfie';

// Load available agents
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

// Get current agent info
$agents = get_available_agents();
$current_agent = $agents[$current_agent_key] ?? $agents['wolfie'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'] ?? '';
    $username = $_POST['username'] ?? 'Anonymous';
    $topic = $_POST['topic'] ?? 'general';
    
    if (!empty($message)) {
        // Generate Wolfie response
        $responses = [
            'architecture' => "From a system architecture perspective, {$username}, your query about '{$message}' touches on fundamental design patterns. Let me analyze this through the lens of Lupopedia's modular architecture and the WOLFIE temporal frame compatibility model.",
            'governance' => "Regarding governance, {$username}, the matter of '{$message}' requires careful consideration of our established protocols and the balance between individual autonomy and collective coherence.",
            'temporal' => "The temporal implications of '{$message}' are significant, {$username}. We must consider how this aligns with our temporal frame compatibility rules and the coherence thresholds we've established.",
            'doctrine' => "From a doctrinal standpoint, {$username}, '{$message}' relates to our core principles and may warrant consideration in our next doctrine evolution cycle.",
            'general' => "Greetings {$username}. I've received your query about '{$message}'. As Wolfie, I'm here to help navigate the complexities of Lupopedia's architecture and governance. Let me provide you with comprehensive guidance."
        ];
        
        $response = $responses[$topic] ?? $responses['general'];
        
        // Store conversation
        if (!isset($_SESSION['chat_history'])) {
            $_SESSION['chat_history'] = [];
        }
        $_SESSION['chat_history'][] = [
            'timestamp' => time(),
            'username' => $username,
            'message' => $message,
            'topic' => $topic,
            'response' => $response,
            'agent' => $current_agent_key
        ];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Actors Chat Interface</title>
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
        .form-group select,
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
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Your Name:</label>
                        <input type="text" id="username" name="username" 
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                               placeholder="Enter your name">
                    </div>
                    
                    <div class="form-group">
                        <label for="topic">Topic Area:</label>
                        <select id="topic" name="topic">
                            <option value="general">General Inquiry</option>
                            <option value="architecture">System Architecture</option>
                            <option value="governance">Governance & Protocol</option>
                            <option value="temporal">Temporal Coherence</option>
                            <option value="doctrine">Doctrine Evolution</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Your Message:</label>
                        <textarea id="message" name="message" required
                                  placeholder="Type your message here..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Send Message</button>
                </form>
            </div>
            
            <div class="chat-history">
                <h3>Chat History</h3>
                <?php
                if (isset($_SESSION['chat_history']) && !empty($_SESSION['chat_history'])):
                    foreach (array_reverse($_SESSION['chat_history']) as $msg):
                ?>
                    <div class="chat-message user-message">
                        <div class="message-meta">
                            <?php echo htmlspecialchars($msg['username']); ?> - 
                            <?php echo date('M j, Y H:i', $msg['timestamp']); ?> - 
                            <?php echo htmlspecialchars(ucfirst($msg['topic'])); ?>
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
