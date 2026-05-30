<?php
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(dirname(dirname(__FILE__))));
}
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(dirname(dirname(dirname(__FILE__)))));
}

require_once dirname(dirname(dirname(__FILE__))) . '/lupopedia-config.php';

$db = DatabaseFactory::getConnection();
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$channel_id = isset($_GET['channel_id']) ? (int) $_GET['channel_id'] : 42;

$channel = $db->fetchRow(
    "SELECT channel_id, channel_name FROM {$table_prefix}channels WHERE channel_id = :channel_id AND is_deleted = 0 LIMIT 1",
    array('channel_id' => $channel_id)
);

$threads = $db->fetchAll(
    "SELECT dialog_thread_id, title, status, thread_type, thread_priority, assigned_actor_id, last_message_ymdhis "
    . "FROM {$table_prefix}dialog_threads "
    . "WHERE channel_id = :channel_id AND is_deleted = 0 "
    . "ORDER BY last_message_ymdhis DESC, dialog_thread_id DESC",
    array('channel_id' => $channel_id)
);

$base = rtrim(LUPOPEDIA_PUBLIC_PATH, '/');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dialog MVP - Threads</title>
    <style>
        body { font-family: Georgia, serif; margin: 24px; background: #f4f1ea; color: #1b2a2f; }
        h1 { margin: 0 0 8px; }
        p { margin: 0 0 16px; }
        form { background: #fff; border: 1px solid #d9d4c8; padding: 12px; margin: 0 0 20px; }
        label { display: block; margin: 8px 0 4px; font-weight: bold; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        button { margin-top: 10px; padding: 8px 12px; border: 1px solid #1b2a2f; background: #e5ddcb; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #d9d4c8; padding: 10px; text-align: left; }
        th { background: #e5ddcb; }
        a { color: #0b5e77; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Dialog MVP: Threads</h1>
    <p>
        Channel: <?php echo $channel ? htmlspecialchars($channel['channel_name']) : ('#' . (int) $channel_id); ?>
        | <a href="<?php echo htmlspecialchars($base . '/channels'); ?>">Back to channels</a>
    </p>

    <form method="post" action="<?php echo htmlspecialchars($base . '/thread'); ?>">
        <h3>Create New Thread</h3>
        <input type="hidden" name="channel_id" value="<?php echo (int) $channel_id; ?>">
        <input type="hidden" name="redirect_after_post" value="1">
        <label for="thread_title">Title</label>
        <input id="thread_title" name="title" type="text" maxlength="255" required>
        <button type="submit">Create New Thread</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Thread ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>Type</th>
                <th>Priority</th>
                <th>Assigned Actor</th>
                <th>Last Message UTC</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($threads as $thread): ?>
                <tr>
                    <td><?php echo (int) $thread['dialog_thread_id']; ?></td>
                    <td>
                        <a href="<?php echo htmlspecialchars($base . '/messages?thread_id=' . (int) $thread['dialog_thread_id']); ?>">
                            <?php echo htmlspecialchars($thread['title']); ?>
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($thread['status']); ?></td>
                    <td><?php echo htmlspecialchars($thread['thread_type']); ?></td>
                    <td><?php echo htmlspecialchars($thread['thread_priority']); ?></td>
                    <td><?php echo (int) $thread['assigned_actor_id']; ?></td>
                    <td><?php echo htmlspecialchars((string) $thread['last_message_ymdhis']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
