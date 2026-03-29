<?php
/**
 * Actor Selection Page - For changing actors while logged in
 * Allows user to choose which actor to act as from their available actors
 */

// Load config
require_once __DIR__ . '/lupopedia-config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['auth_user_id'])) {
    header('Location: ' . rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/login.php');
    exit;
}

// Load required classes
require_once LUPOPEDIA_PATH . '/lupo-includes/class-DatabaseFactory.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AuthSessionManager.php';

$sessionManager = new AuthSessionManager();
$auth_user_id = $_SESSION['auth_user_id'];

// Get available actors for this user
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

// Get user's actors that are not already being used by other users
$sql = "SELECT a.actor_id, a.actor_name, a.name, a.actor_type 
        FROM {$prefix}actors a 
        WHERE a.auth_user_id = :auth_user_id 
        AND a.is_active = 1 
        AND a.is_deleted = 0
        ORDER BY a.actor_type, a.name";

$actors = $db->fetchAll($sql, ['auth_user_id' => $auth_user_id]);

// Also get agents that can be created as new actors
$agent_sql = "SELECT agent_id, agent_name, archetype 
             FROM {$prefix}agents 
             WHERE is_deleted = 0 
             AND agent_id NOT IN (
                 SELECT actor_id FROM {$prefix}actors 
                 WHERE auth_user_id = :auth_user_id AND is_deleted = 0
             )
             ORDER BY agent_name";

$available_agents = $db->fetchAll($agent_sql, ['auth_user_id' => $auth_user_id]);

// Get current actor
$current_actor_id = $sessionManager->getActiveActorId();
$current_actor = null;
foreach ($actors as $actor) {
    if ((int) $actor['actor_id'] === $current_actor_id) {
        $current_actor = $actor;
        break;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actor_id'])) {
    $new_actor_id = intval($_POST['actor_id']);
    
    // Verify this actor belongs to the user
    $is_valid = false;
    foreach ($actors as $actor) {
        if ((int) $actor['actor_id'] === $new_actor_id) {
            $is_valid = true;
            break;
        }
    }
    
    if ($is_valid) {
        // Update session with new actor
        $sessionManager->updateActiveActor($new_actor_id);
        
        // Redirect to requested page or admin
        $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/admin.php';
        header('Location: ' . $redirect);
        exit;
    } else {
        $error = "Invalid actor selection.";
    }
}

// Handle creating new actor from agent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agent_id']) && isset($_POST['create_actor'])) {
    $agent_id = intval($_POST['agent_id']);
    
    // Verify agent is available
    $is_valid = false;
    foreach ($available_agents as $agent) {
        if ((int) $agent['agent_id'] === $agent_id) {
            $is_valid = true;
            break;
        }
    }
    
    if ($is_valid) {
        // Create new actor from agent
        $username = $_SESSION['username'] ?? 'user';
        $new_actor_id = $sessionManager->createActorFromAgent($auth_user_id, $agent_id, $username);
        
        if ($new_actor_id) {
            // Update session with new actor
            $sessionManager->updateActiveActor($new_actor_id);
            
            // Redirect to requested page or admin
            $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/admin.php';
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = "Failed to create actor from selected agent. Please try again.";
        }
    } else {
        $error = "Invalid agent selection.";
    }
}

// Get redirect parameter
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/admin.php';

$base = rtrim(LUPOPEDIA_PUBLIC_PATH, '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Actor - LUPOPEDIA</title>
    <link rel="icon" type="image/x-icon" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/favicon.ico">
    <link rel="stylesheet" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/css/main.css">
    <style>
        .actor-selection-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .actor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .actor-card {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .actor-card:hover {
            border-color: #4299e1;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .actor-card.current {
            border-color: #48bb78;
            background: #f0fff4;
        }
        .actor-name {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .actor-type {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .current-badge {
            display: inline-block;
            background: #48bb78;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-left: 0.5rem;
        }
        .error {
            background: #fed7d7;
            color: #c53030;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .btn {
            background: #4299e1;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 0.5rem 0.5rem 0.5rem 0;
        }
        .btn:hover {
            background: #3182ce;
        }
        .btn-secondary {
            background: #718096;
        }
        .btn-secondary:hover {
            background: #4a5568;
        }
    </style>
</head>
<body>
    <div class="actor-selection-container">
        <h1>Select Actor</h1>
        
        <?php if ($current_actor): ?>
            <p>Currently acting as: <strong><?= htmlspecialchars($current_actor['name'] ?: $current_actor['actor_name']) ?></strong></p>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($actors)): ?>
            <h2>Your Existing Actors</h2>
            <div class="actor-grid">
                <?php foreach ($actors as $actor): ?>
                    <form method="post">
                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
                        <input type="hidden" name="actor_id" value="<?= (int) $actor['actor_id'] ?>">
                        <div class="actor-card <?= ((int) $actor['actor_id'] === $current_actor_id) ? 'current' : '' ?>" onclick="this.closest('form').submit()">
                            <div class="actor-name">
                                <?= htmlspecialchars($actor['name'] ?: $actor['actor_name']) ?>
                                <?php if ((int) $actor['actor_id'] === $current_actor_id): ?>
                                    <span class="current-badge">Current</span>
                                <?php endif; ?>
                            </div>
                            <div class="actor-type">Type: <?= htmlspecialchars($actor['actor_type']) ?> (ID: <?= (int) $actor['actor_id'] ?>)</div>
                            <?php if ((int) $actor['actor_id'] !== $current_actor_id): ?>
                                <div style="margin-top: 1rem;">
                                    <button type="submit" class="btn">Select</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($available_agents)): ?>
            <h2>Create New Actor</h2>
            <div class="actor-grid">
                <?php foreach ($available_agents as $agent): ?>
                    <form method="post">
                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
                        <input type="hidden" name="agent_id" value="<?= (int) $agent['agent_id'] ?>">
                        <input type="hidden" name="create_actor" value="1">
                        <div class="actor-card" onclick="this.closest('form').submit()">
                            <div class="actor-name"><?= htmlspecialchars($agent['agent_name']) ?></div>
                            <div class="actor-type">Type: <?= htmlspecialchars($agent['archetype']) ?> (Agent ID: <?= (int) $agent['agent_id'] ?>)</div>
                            <div style="margin-top: 1rem;">
                                <button type="submit" class="btn btn-secondary">Create Actor</button>
                            </div>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 2rem;">
            <a href="<?= htmlspecialchars($redirect) ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</body>
</html>
