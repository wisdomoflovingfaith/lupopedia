<?php
/**
 * Actor Selection Page — change active actor while logged in.
 * Uses AuthSessionManager (department-scoped lupo_actor_departments), not lupo_actors.auth_user_id.
 */

$lupoRoot = __DIR__;
$lupoPub = '/' . basename($lupoRoot);
require_once $lupoRoot . '/lupo-includes/classes/LupopediaConfigResolver.php';
$lupoCfgPath = LupopediaConfigResolver::resolve($lupoRoot, $lupoPub);
if ($lupoCfgPath === null) {
    $lupoBase = rtrim(dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ''), '/');
    header('Location: ' . ($lupoBase === '' ? '/install.php' : $lupoBase . '/install.php'));
    exit;
}
require_once $lupoCfgPath;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DatabaseFactory.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AuthSessionManager.php';

$sessionManager = new AuthSessionManager();
$db = DatabaseFactory::getConnection();

$active_actor_id = $sessionManager->getActiveActorId();
$auth_user_id = 0;
if ($active_actor_id > 0) {
    $resolved = $sessionManager->getAuthUserId($active_actor_id);
    if ($resolved !== null) {
        $auth_user_id = (int) $resolved;
    }
}
if ($auth_user_id <= 0) {
    header('Location: ' . rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/login.php');
    exit;
}
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

$permRow = $db->fetchRow(
    "SELECT 1 AS ok FROM {$prefix}permissions WHERE user_id = :uid AND permission = 'owner' AND target_type = 'module' AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
    array('uid' => $auth_user_id)
);
$isAdminForList = !empty($_SESSION['is_admin']) || !empty($permRow);

$userDepartments = $sessionManager->getUserDepartments($auth_user_id);
$user_department_ids = array();
$user_has_root_department = false;
foreach ($userDepartments as $ud) {
    $did = (int) $ud['department_id'];
    $user_department_ids[] = $did;
    if ($did === 0) {
        $user_has_root_department = true;
    }
}

$can_filter_all_departments = $user_has_root_department || $isAdminForList;

$raw_filter = '';
if (isset($_GET['filter_department_id'])) {
    $raw_filter = $_GET['filter_department_id'];
} elseif (isset($_POST['filter_department_id'])) {
    $raw_filter = $_POST['filter_department_id'];
}
$filter_department_id = null;
if ($raw_filter !== '') {
    $fid = (int) $raw_filter;
    if ($can_filter_all_departments) {
        $exists = $db->fetchRow(
            "SELECT 1 AS ok FROM {$prefix}departments WHERE department_id = :d AND is_deleted = 0 LIMIT 1",
            array('d' => $fid)
        );
        if (!empty($exists)) {
            $filter_department_id = $fid;
        }
    } elseif (in_array($fid, $user_department_ids, true)) {
        $filter_department_id = $fid;
    }
}

$actors = $sessionManager->getActorsUserCanActAs($auth_user_id, $isAdminForList, $filter_department_id);
$available_agents = $sessionManager->getAvailableAgents();

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/admin.php';

$current_actor_id = $sessionManager->getActiveActorId();
$current_actor = null;
if ($current_actor_id > 0) {
    $current_actor = $db->fetchRow(
        "SELECT actor_id, actor_name, name, actor_type FROM {$prefix}actors WHERE actor_id = :id AND is_active = 1 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
        array('id' => $current_actor_id)
    );
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actor_id'])) {
    $new_actor_id = (int) $_POST['actor_id'];
    if ($sessionManager->updateActiveActor($new_actor_id)) {
        $r = isset($_POST['redirect']) ? $_POST['redirect'] : rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/admin.php';
        header('Location: ' . $r);
        exit;
    }
    $error = 'That actor is not available for your account. If you changed department filter, try “All departments” or pick an actor listed below.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agent_id']) && isset($_POST['create_actor'])) {
    $agent_id = (int) $_POST['agent_id'];
    $agent_ok = false;
    foreach ($available_agents as $ag) {
        if ((int) $ag['agent_id'] === $agent_id) {
            $agent_ok = true;
            break;
        }
    }
    if ($agent_ok) {
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'user';
        $dept_for_new = null;
        if ($filter_department_id !== null) {
            if ($can_filter_all_departments) {
                $dept_for_new = $filter_department_id;
            } elseif (in_array($filter_department_id, $user_department_ids, true)) {
                $dept_for_new = $filter_department_id;
            }
        }
        $new_actor_id = $sessionManager->createActorFromAgent($auth_user_id, $agent_id, $username, $dept_for_new);
        if ($new_actor_id && $sessionManager->updateActiveActor($new_actor_id)) {
            $r = isset($_POST['redirect']) ? $_POST['redirect'] : rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/admin.php';
            header('Location: ' . $r);
            exit;
        }
        $error = 'Failed to create actor from the selected agent. Please try again.';
    } else {
        $error = 'Invalid agent selection.';
    }
}

$dept_filter_options = array();
if ($can_filter_all_departments) {
    $dept_filter_options = $db->fetchAll(
        "SELECT department_id, name FROM {$prefix}departments WHERE is_deleted = 0 ORDER BY department_id ASC",
        array()
    );
} else {
    foreach ($userDepartments as $ud) {
        $dept_filter_options[] = array(
            'department_id' => $ud['department_id'],
            'name' => isset($ud['name']) ? $ud['name'] : ('Department ' . $ud['department_id']),
        );
    }
}
$show_dept_filter = count($dept_filter_options) > 1;

$base = rtrim(LUPOPEDIA_PUBLIC_PATH, '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Actor - LUPOPEDIA</title>
    <link rel="icon" type="image/x-icon" href="<?= htmlspecialchars(LUPOPEDIA_PUBLIC_PATH) ?>/favicon.ico">
    <link rel="stylesheet" href="<?= htmlspecialchars(LUPOPEDIA_PUBLIC_PATH) ?>/lupo-includes/css/main.css">
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
        .dept-filter {
            margin: 1rem 0 1.5rem;
            padding: 1rem;
            background: #f7fafc;
            border-radius: 6px;
        }
        .dept-filter label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .dept-filter select {
            min-width: 240px;
            padding: 0.5rem;
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
            <?php
            $cur_disp = (isset($current_actor['name']) && $current_actor['name'] !== '') ? $current_actor['name'] : $current_actor['actor_name'];
            ?>
            <p>Currently acting as: <strong><?php echo htmlspecialchars($cur_disp); ?></strong></p>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($show_dept_filter): ?>
            <form method="get" class="dept-filter">
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
                <label for="filter_department_id"><?php echo $can_filter_all_departments ? 'Department' : 'My departments'; ?></label>
                <select id="filter_department_id" name="filter_department_id" onchange="this.form.submit()">
                    <option value=""><?php echo $can_filter_all_departments ? 'All departments' : 'All my departments'; ?></option>
                    <?php foreach ($dept_filter_options as $dopt): ?>
                        <option value="<?php echo (int) $dopt['department_id']; ?>"<?php echo ($filter_department_id !== null && (int) $filter_department_id === (int) $dopt['department_id']) ? ' selected' : ''; ?>>
                            <?php echo htmlspecialchars(isset($dopt['name']) ? $dopt['name'] : ('Department ' . $dopt['department_id'])); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        <?php endif; ?>

        <?php if (!empty($actors)): ?>
            <h2>Actors you can use</h2>
            <div class="actor-grid">
                <?php foreach ($actors as $actor): ?>
                    <form method="post">
                        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
                        <input type="hidden" name="actor_id" value="<?php echo (int) $actor['actor_id']; ?>">
                        <?php if ($filter_department_id !== null): ?>
                            <input type="hidden" name="filter_department_id" value="<?php echo (int) $filter_department_id; ?>">
                        <?php endif; ?>
                        <div class="actor-card<?php echo ((int) $actor['actor_id'] === $current_actor_id) ? ' current' : ''; ?>" onclick="this.closest('form').submit()">
                            <div class="actor-name">
                                <?php
                                $adisp = (isset($actor['name']) && $actor['name'] !== '') ? $actor['name'] : $actor['actor_name'];
                                echo htmlspecialchars($adisp);
                                ?>
                                <?php if ((int) $actor['actor_id'] === $current_actor_id): ?>
                                    <span class="current-badge">Current</span>
                                <?php endif; ?>
                            </div>
                            <div class="actor-type">Type: <?php echo htmlspecialchars($actor['actor_type']); ?> (ID: <?php echo (int) $actor['actor_id']; ?>)</div>
                            <?php if ((int) $actor['actor_id'] !== $current_actor_id): ?>
                                <div style="margin-top: 1rem;">
                                    <button type="submit" class="btn">Select</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p><em>No actors match this department filter. Choose &ldquo;All&rdquo; departments or another department.</em></p>
        <?php endif; ?>

        <?php if (!empty($available_agents)): ?>
            <h2>Create new actor from agent</h2>
            <p class="actor-type">Uses your Lupopedia agent templates (<code>lupo_agent_definitions</code>). New actors are linked to your account and department<?php echo $filter_department_id !== null ? ' (current filter)' : ''; ?>.</p>
            <div class="actor-grid">
                <?php foreach ($available_agents as $agent): ?>
                    <form method="post">
                        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
                        <input type="hidden" name="agent_id" value="<?php echo (int) $agent['agent_id']; ?>">
                        <input type="hidden" name="create_actor" value="1">
                        <?php if ($filter_department_id !== null): ?>
                            <input type="hidden" name="filter_department_id" value="<?php echo (int) $filter_department_id; ?>">
                        <?php endif; ?>
                        <div class="actor-card" onclick="this.closest('form').submit()">
                            <div class="actor-name"><?php echo htmlspecialchars($agent['agent_name']); ?></div>
                            <div class="actor-type">
                                <?php if (!empty($agent['description'])): ?>
                                    <?php echo htmlspecialchars($agent['description']); ?>
                                <?php else: ?>
                                    Key: <?php echo htmlspecialchars(isset($agent['agent_key']) ? $agent['agent_key'] : ''); ?>
                                <?php endif; ?>
                                (agent_id <?php echo (int) $agent['agent_id']; ?>)
                            </div>
                            <div style="margin-top: 1rem;">
                                <button type="submit" class="btn btn-secondary">Create actor</button>
                            </div>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div style="margin-top: 2rem;">
            <a href="<?php echo htmlspecialchars($redirect); ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</body>
</html>
