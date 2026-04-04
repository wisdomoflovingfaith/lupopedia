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
$baseUrl = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
if (!isset($_SESSION['pending_auth_user_id'])) {
    header('Location: ' . $baseUrl . '/login.php');
    exit;
}

// Load required classes
require_once __DIR__ . '/lupo-includes/classes/DatabaseFactory.php';
require_once __DIR__ . '/lupo-includes/classes/AuthSessionManager.php';

$db = DatabaseFactory::getConnection();
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$sessionManager = new AuthSessionManager();
$auth_user_id = (int) $_SESSION['pending_auth_user_id'];

$permRow = $db->fetchRow(
    "SELECT 1 AS ok FROM {$table_prefix}permissions WHERE user_id = :uid AND permission = 'owner' AND target_type = 'module' AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
    array('uid' => $auth_user_id)
);
$isAdminForAgentList = !empty($permRow);

$agents = $sessionManager->getActorsUserCanActAs($auth_user_id, $isAdminForAgentList);

// Handle form submission: pick an existing agent actor (seed personas), not lupo_agents.agent_id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actor_id'])) {
    $selected_actor_id = (int) $_POST['actor_id'];
    $allowed = $sessionManager->getActorsUserCanActAs($auth_user_id, $isAdminForAgentList);
    $allowed_ids = array();
    foreach ($allowed as $row) {
        if (isset($row['actor_id'])) {
            $allowed_ids[] = (int) $row['actor_id'];
        }
    }

    if (!in_array($selected_actor_id, $allowed_ids, true)) {
        $error = 'That identity is not available. Please choose another.';
    } else {
        $actor = $db->fetchRow(
            "SELECT actor_name FROM {$table_prefix}actors WHERE actor_id = :actor_id AND is_deleted = 0 AND is_active = 1 LIMIT 1",
            array('actor_id' => $selected_actor_id)
        );
        if ($actor && isset($actor['actor_name'])) {
            $sessionManager->createSession($selected_actor_id, $actor['actor_name']);
            unset($_SESSION['pending_auth_user_id']);
            unset($_SESSION['pending_username']);
            if (!empty($_SESSION['password_change_required'])) {
                $_SESSION['password_change_actor_id'] = $selected_actor_id;
                header('Location: ' . $baseUrl . '/change-password');
                exit;
            }
            header('Location: ' . $baseUrl . '/admin.php');
            exit;
        }
        $error = 'Failed to start session for the selected identity. Please try again.';
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
                <input type="hidden" name="actor_id" id="selected_actor_id">
                
                <?php foreach ($agents as $agent): ?>
                <?php
                    $aid = isset($agent['actor_id']) ? (int) $agent['actor_id'] : 0;
                    $aname = isset($agent['actor_name']) ? $agent['actor_name'] : '';
                    $disp = isset($agent['name']) ? $agent['name'] : $aname;
                    $sub = isset($agent['actor_type']) ? $agent['actor_type'] : '';
                ?>
                <div class="agent-card" data-actor-id="<?php echo $aid; ?>" onclick="selectAgent(<?php echo $aid; ?>)">
                    <div class="agent-name"><?php echo htmlspecialchars($disp); ?></div>
                    <div class="agent-description"><?php echo htmlspecialchars($sub !== '' ? $sub : 'Agent identity'); ?></div>
                </div>
                <?php endforeach; ?>
                
                <button type="submit" id="submit-btn" disabled>Continue as Selected Agent</button>
            </form>
        <?php endif; ?>
    </div>
    
    <script>
        let selectedId = null;
        
        function selectAgent(actorId) {
            selectedId = actorId;
            document.getElementById('selected_actor_id').value = actorId;
            document.getElementById('submit-btn').disabled = false;
            
            // Highlight selected card
            document.querySelectorAll('.agent-card').forEach(card => {
                card.classList.remove('selected');
                if (parseInt(card.dataset.actorId, 10) == actorId) {
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
                selectAgent(parseInt(cards[currentIndex + 1].dataset.actorId, 10));
            } else if (e.key === 'ArrowUp' && currentIndex > 0) {
                e.preventDefault();
                selectAgent(parseInt(cards[currentIndex - 1].dataset.actorId, 10));
            } else if (e.key === 'Enter' && selectedId) {
                e.preventDefault();
                document.getElementById('agent-form').submit();
            }
        });
    </script>
</body>
</html>
