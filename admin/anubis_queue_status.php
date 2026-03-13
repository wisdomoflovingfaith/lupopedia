<?php
/**
 * ANUBIS Queue Status Dashboard
 * 
 * Provides an overview of orphaned files and processing status.
 * PHP 5.3+ compatible.
 *
 * @package Lupopedia\ANUBIS
 * @version 4.0.53
 */

define('LUPOPEDIA_PATH', dirname(__DIR__));
require_once LUPOPEDIA_PATH . '/lupo-includes/bootstrap.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/ANUBIS/QueueProcessor.php';

// Security check
$authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
if ($authService) {
    $authService->requireAdmin();
} else {
    // Fallback if AuthService not initialized
    require_once LUPOPEDIA_PATH . '/lupo-includes/functions/auth-helpers.php';
    if (!function_exists('is_admin') || !is_admin()) {
        header('HTTP/1.1 403 Forbidden');
        die("Access denied. Admin role required.");
    }
}

$db = DatabaseFactory::getConnection();
$queue = new ANUBIS_QueueProcessor($db);
$stats = $queue->getQueueStats();

$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$recent_items = $db->fetchAll(
    "SELECT * FROM {$prefix}anubis_queue 
     ORDER BY updated_utc DESC LIMIT 50"
);

?>
<!DOCTYPE html>
<html>

<head>
    <title>ANUBIS Queue Status</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
            line-height: 1.5;
            background: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }

        .stat-card h3 {
            margin: 0;
            font-size: 0.9em;
            color: #7f8c8d;
        }

        .stat-card .value {
            font-size: 1.8em;
            font-weight: bold;
            color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 0.9em;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f8f9fa;
            color: #555;
        }

        .status-pending {
            color: #f39c12;
            font-weight: bold;
        }

        .status-recovered {
            color: #27ae60;
            font-weight: bold;
        }

        .status-failed {
            color: #c0392b;
            font-weight: bold;
        }

        .status-quarantined {
            color: #8e44ad;
            font-weight: bold;
        }

        .status-processing {
            color: #3498db;
            font-weight: bold;
        }

        .priority-high {
            color: #e74c3c;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🤖 ANUBIS Custodial Intelligence — Queue Status</h1>

        <div class="stats-grid">
            <?php
            $total = 0;
            foreach ($stats as $stat):
                $total += $stat['count'];
                ?>
                <div class="stat-card">
                    <h3><?php echo strtoupper($stat['status']); ?></h3>
                    <div class="value"><?php echo $stat['count']; ?></div>
                    <div style="font-size:0.8em; color:#7f8c8d;"><?php echo (int) $stat['filesystem_copies']; ?> on disk
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="stat-card" style="background: #34495e; color: white;">
                <h3 style="color: #bdc3c7;">TOTAL ITEMS</h3>
                <div class="value" style="color: white;"><?php echo $total; ?></div>
            </div>
        </div>

        <h2>Recent Activity</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>File Path</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Attempts</th>
                    <th>FS Copy?</th>
                    <th>Last Update (UTC)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_items as $item): ?>
                    <tr>
                        <td><?php echo $item['queue_id']; ?></td>
                        <td title="<?php echo htmlspecialchars($item['file_path']); ?>">
                            <?php echo htmlspecialchars(basename($item['file_path'])); ?>
                        </td>
                        <td>
                            <span class="status-<?php echo $item['status']; ?>">
                                <?php echo strtoupper($item['status']); ?>
                            </span>
                        </td>
                        <td class="<?php echo $item['priority'] <= 2 ? 'priority-high' : ''; ?>">
                            P<?php echo $item['priority']; ?>
                        </td>
                        <td><?php echo $item['attempts']; ?></td>
                        <td><?php echo $item['filesystem_copy_exists'] ? '✅' : '❌'; ?></td>
                        <td><?php echo $item['updated_utc']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($recent_items)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #7f8c8d;">No items in queue.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
