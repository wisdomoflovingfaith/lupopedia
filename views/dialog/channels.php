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

$channels = $db->fetchAll(
    "SELECT c.channel_id, c.channel_name, c.channel_type, c.visibility_status, c.updated_ymdhis, "
    . "COUNT(t.dialog_thread_id) AS thread_count "
    . "FROM {$table_prefix}channels c "
    . "LEFT JOIN {$table_prefix}dialog_threads t ON t.channel_id = c.channel_id AND t.is_deleted = 0 "
    . "WHERE c.is_deleted = 0 "
    . "GROUP BY c.channel_id, c.channel_name, c.channel_type, c.visibility_status, c.updated_ymdhis "
    . "ORDER BY c.channel_id ASC",
    array()
);

$base = rtrim(LUPOPEDIA_PUBLIC_PATH, '/');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dialog MVP - Channels</title>
    <style>
        body { font-family: Georgia, serif; margin: 24px; background: #f4f1ea; color: #1b2a2f; }
        h1 { margin: 0 0 16px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #d9d4c8; padding: 10px; text-align: left; }
        th { background: #e5ddcb; }
        a { color: #0b5e77; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Dialog MVP: Channels</h1>
    <table>
        <thead>
            <tr>
                <th>Channel ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Visibility</th>
                <th>Threads</th>
                <th>Updated UTC</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($channels as $channel): ?>
                <tr>
                    <td><?php echo (int) $channel['channel_id']; ?></td>
                    <td>
                        <a href="<?php echo htmlspecialchars($base . '/threads?channel_id=' . (int) $channel['channel_id']); ?>">
                            <?php echo htmlspecialchars($channel['channel_name']); ?>
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($channel['channel_type']); ?></td>
                    <td><?php echo htmlspecialchars($channel['visibility_status']); ?></td>
                    <td><?php echo (int) $channel['thread_count']; ?></td>
                    <td><?php echo htmlspecialchars((string) $channel['updated_ymdhis']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
