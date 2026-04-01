<?php
/**
 * Agent Selection Page
 * Allows user to choose which agent to act as
 */

// Load config
require_once __DIR__ . '/lupopedia-config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is pending agent selection
if (!isset($_SESSION['pending_auth_user_id'])) {
    header('Location: /lupopedia/login.php');
    exit;
}

// Load required classes
require_once __DIR__ . '/lupo-includes/classes/DatabaseFactory.php';
require_once __DIR__ . '/lupo-includes/classes/AuthSessionManager.php';

$sessionManager = new AuthSessionManager();
$auth_user_id = $_SESSION['pending_auth_user_id'];
$agents = $sessionManager->getActorsUserCanActAs($auth_user_id);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agent_id'])) {
    $agent_id = intval($_POST['agent_id']);
    $auth_user_id = $_SESSION['pending_auth_user_id'];
    $username = $_SESSION['pending_username'];
    
    $actor_id = $sessionManager->createActorFromAgent($auth_user_id, $agent_id, $username);
    
    if ($actor_id) {
        // Get actor name for session
        $db = DatabaseFactory::getConnection();
        $actor = $db->fetchRow("SELECT actor_name FROM lupo_actors WHERE actor_id = :actor_id", ['actor_id' => $actor_id]);
        
        $sessionManager->createSession($actor_id, $actor['actor_name']);
        
        // Clear pending session data
        unset($_SESSION['pending_auth_user_id']);
        unset($_SESSION['pending_username']);
        
        header('Location: /lupopedia/admin.php');
        exit;
    } else {
        $error = "Failed to create actor from selected agent. Please try again.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Agent - Lupopedia</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            max-width: 800px; 
            margin: 50px auto; 
            padding: 20px; 
            background: #f8f9fa;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #2c3e50; 
            margin-bottom: 10px;
            font-size: 2em;
        }
        .subtitle {
            color: #6c757d;
            margin-bottom: 30px;
            font-size: 1.1em;
        }
        .agent-card { 
            border: 2px solid #e9ecef; 
            border-radius: 8px; 
            padding: 24px; 
            margin-bottom: 16px; 
            cursor: pointer; 
            transition: all 0.2s; 
            background: white;
        }
        .agent-card:hover { 
            background: #f8f9fa; 
            border-color: #3498db; 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52,152,219,0.15);
        }
        .agent-card.selected { 
            background: #e8f4fd; 
            border-color: #3498db; 
        }
        .agent-name { 
            font-size: 1.3em; 
            font-weight: 600; 
            margin-bottom: 8px; 
            color: #2c3e50;
        }
        .agent-description { 
            color: #6c757d; 
            line-height: 1.5;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        button { 
            background: #3498db; 
            color: white; 
            border: none; 
            padding: 14px 28px; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 1.1em; 
            margin-top: 24px; 
            font-weight: 500;
            transition: background 0.2s;
        }
        button:hover { 
            background: #2980b9; 
        }
        button:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .user-info {
            color: #6c757d;
            font-size: 0.9em;
        }
        @media (max-width: 600px) {
            body { margin: 20px auto; padding: 10px; }
            .container { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <div>
                <h1>Select Your Agent Identity</h1>
                <p class="subtitle">Choose which agent you want to act as in Lupopedia</p>
            </div>
            <div class="user-info">
                Logged in as: <?php echo htmlspecialchars($_SESSION['pending_username'] ?? ''); ?>
            </div>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (empty($agents)): ?>
            <div class="error">
                No agents are currently available. All agents may be in use. Please try again later.
            </div>
        <?php else: ?>
            <form method="POST" id="agent-form">
                <input type="hidden" name="agent_id" id="selected_agent_id">
                
                <?php foreach ($agents as $agent): ?>
                <div class="agent-card" data-agent-id="<?php echo $agent['agent_id']; ?>" onclick="selectAgent(<?php echo $agent['agent_id']; ?>)">
                    <div class="agent-name"><?php echo htmlspecialchars($agent['agent_name']); ?></div>
                    <div class="agent-description"><?php echo htmlspecialchars($agent['description'] ?? 'No description available'); ?></div>
                </div>
                <?php endforeach; ?>
                
                <button type="submit" id="submit-btn" disabled>Continue as Selected Agent</button>
            </form>
        <?php endif; ?>
    </div>
    
    <script>
        let selectedId = null;
        
        function selectAgent(agentId) {
            selectedId = agentId;
            document.getElementById('selected_agent_id').value = agentId;
            document.getElementById('submit-btn').disabled = false;
            
            // Highlight selected card
            document.querySelectorAll('.agent-card').forEach(card => {
                card.classList.remove('selected');
                if (card.dataset.agentId == agentId) {
                    card.classList.add('selected');
                }
            });
        }
        
        document.getElementById('agent-form').addEventListener('submit', function(e) {
            if (!selectedId) {
                e.preventDefault();
                alert('Please select an agent');
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const cards = Array.from(document.querySelectorAll('.agent-card'));
            const selected = document.querySelector('.agent-card.selected');
            let currentIndex = selected ? cards.indexOf(selected) : -1;
            
            if (e.key === 'ArrowDown' && currentIndex < cards.length - 1) {
                e.preventDefault();
                selectAgent(cards[currentIndex + 1].dataset.agentId);
            } else if (e.key === 'ArrowUp' && currentIndex > 0) {
                e.preventDefault();
                selectAgent(cards[currentIndex - 1].dataset.agentId);
            } else if (e.key === 'Enter' && selectedId) {
                e.preventDefault();
                document.getElementById('agent-form').submit();
            }
        });
    </script>
</body>
</html>
