<?php
require_once(dirname(__FILE__) . '/admin_layout.php');

$channel_id = isset($_GET['channel_id']) ? (int) $_GET['channel_id'] : 1;
$actor_id = channel_admin_require_actor();
channel_admin_require_access($actor_id, $channel_id);

$db = DatabaseFactory::getConnection();
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$sql = "SELECT auth_user_id, username, display_name, email, last_login_ymdhis, is_active FROM {$table_prefix}auth_users WHERE is_deleted = 0 ORDER BY auth_user_id DESC LIMIT 25";
$rows = $db->fetchAll($sql, array());

channel_admin_page_start('Operators', 'User & Operator Management');
?>
<div class="channel-admin-card">
    <h3>Recent Operator Accounts</h3>
    <p class="channel-admin-note">Showing latest 25 auth users. Use Lupopedia admin tools for full CRUD.</p>
    <table class="channel-admin-table" style="margin-top: 12px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Display Name</th>
                <th>Email</th>
                <th>Last Login</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($rows) { foreach ($rows as $row) { ?>
                <tr>
                    <td><?php echo (int) $row['auth_user_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['display_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['last_login_ymdhis'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo ((int) $row['is_active'] === 1) ? 'Active' : 'Inactive'; ?></td>
                </tr>
            <?php } } else { ?>
                <tr>
                    <td colspan="6">No operator accounts found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
channel_admin_page_end();
?>
