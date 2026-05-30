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
$thread_id = isset($_GET['thread_id']) ? (int) $_GET['thread_id'] : 0;

$thread = null;
$messages = array();
if ($thread_id > 0) {
    $thread = $db->fetchRow(
        "SELECT dialog_thread_id, channel_id, title, assigned_actor_id, status FROM {$table_prefix}dialog_threads WHERE dialog_thread_id = :thread_id AND is_deleted = 0 LIMIT 1",
        array('thread_id' => $thread_id)
    );

    if ($thread) {
        $messages = $db->fetchAll(
            "SELECT m.dialog_message_id, m.from_actor_id, m.to_actor_id, m.message_text, m.message_type, m.created_ymdhis, a.name AS from_actor_name "
            . "FROM {$table_prefix}dialog_messages m "
            . "LEFT JOIN {$table_prefix}actors a ON a.actor_id = m.from_actor_id "
            . "WHERE m.dialog_thread_id = :thread_id AND m.is_deleted = 0 "
            . "ORDER BY m.dialog_message_id ASC",
            array('thread_id' => $thread_id)
        );
    }
}

$base = rtrim(LUPOPEDIA_PUBLIC_PATH, '/');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dialog MVP - Messages</title>
    <style>
        body { font-family: Georgia, serif; margin: 24px; background: #f4f1ea; color: #1b2a2f; }
        h1 { margin: 0 0 8px; }
        p { margin: 0 0 16px; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-bottom: 20px; }
        th, td { border: 1px solid #d9d4c8; padding: 10px; text-align: left; vertical-align: top; }
        th { background: #e5ddcb; }
        form { background: #fff; border: 1px solid #d9d4c8; padding: 12px; margin-bottom: 16px; }
        label { display: block; margin: 8px 0 4px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { margin-top: 10px; padding: 8px 12px; border: 1px solid #1b2a2f; background: #e5ddcb; cursor: pointer; }
        a { color: #0b5e77; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Dialog MVP: Messages</h1>
    <p>
        <?php if ($thread): ?>
            Thread <?php echo (int) $thread['dialog_thread_id']; ?>: <?php echo htmlspecialchars($thread['title']); ?>
            | Channel <?php echo (int) $thread['channel_id']; ?>
            | Assigned Actor: <?php echo (int) $thread['assigned_actor_id']; ?>
            | <a href="<?php echo htmlspecialchars($base . '/threads?channel_id=' . (int) $thread['channel_id']); ?>">Back to threads</a>
        <?php else: ?>
            Provide ?thread_id=123 in the URL.
        <?php endif; ?>
    </p>

    <?php if ($thread): ?>
        <form method="post" action="<?php echo htmlspecialchars($base . '/message'); ?>">
            <h3>Post Message</h3>
            <input type="hidden" name="thread_id" value="<?php echo (int) $thread['dialog_thread_id']; ?>">
            <input type="hidden" name="redirect_after_post" value="1">
            <label for="message_text">Message Text</label>
            <textarea id="message_text" name="message_text" rows="4" required></textarea>
            <label for="message_type">Message Type</label>
            <select id="message_type" name="message_type">
                <option value="text">text</option>
                <option value="system">system</option>
                <option value="command">command</option>
            </select>
            <label for="to_actor_id">Target Actor ID (optional)</label>
            <input id="to_actor_id" name="to_actor_id" type="number" min="0">
            <button type="submit">POST /message</button>
        </form>

        <form method="post" action="<?php echo htmlspecialchars($base . '/message/0/actor'); ?>" onsubmit="this.action='<?php echo htmlspecialchars($base . '/message/'); ?>' + this.message_id.value + '/actor';">
            <h3>Assign Actor</h3>
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="redirect_after_post" value="1">
            <label for="assign_message_id">Message ID</label>
            <input id="assign_message_id" name="message_id" type="number" min="1" required>
            <label for="assign_actor_id">Assign To Actor ID</label>
            <input id="assign_actor_id" name="actor_id" type="number" min="1" required>
            <button type="submit">PATCH /message/:id/actor</button>
        </form>

        <form method="post" action="<?php echo htmlspecialchars($base . '/message/0/route'); ?>" onsubmit="this.action='<?php echo htmlspecialchars($base . '/message/'); ?>' + this.message_id.value + '/route';">
            <h3>Trigger Routing</h3>
            <input type="hidden" name="redirect_after_post" value="1">
            <label for="route_message_id">Message ID</label>
            <input id="route_message_id" name="message_id" type="number" min="1" required>
            <button type="submit">POST /message/:id/route</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Message ID</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Type</th>
                    <th>Text</th>
                    <th>Created UTC</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?php echo (int) $message['dialog_message_id']; ?></td>
                        <td>
                            <?php echo (int) $message['from_actor_id']; ?>
                            <?php if (!empty($message['from_actor_name'])): ?>
                                (<?php echo htmlspecialchars($message['from_actor_name']); ?>)
                            <?php endif; ?>
                        </td>
                        <td><?php echo (int) $message['to_actor_id']; ?></td>
                        <td><?php echo htmlspecialchars($message['message_type']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($message['message_text'])); ?></td>
                        <td><?php echo htmlspecialchars((string) $message['created_ymdhis']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
