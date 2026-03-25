<?php
/**
 * Admin Users section view. Expects: $users_list, $edit_profile_user, $edit_permissions_user, $channel1_role, $message, $base.
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}
$base = isset($base) ? $base : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '');
$message = isset($message) ? $message : '';
$users_list = isset($users_list) ? $users_list : array();
$edit_profile_user = isset($edit_profile_user) ? $edit_profile_user : null;
$edit_permissions_user = isset($edit_permissions_user) ? $edit_permissions_user : null;
$channel1_role = isset($channel1_role) ? $channel1_role : '';
?>
<div class="admin-users-section">
    <?php if ($message !== ''): ?>
        <div class="admin-message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div style="margin-bottom: 1rem; padding: 0.85rem 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #334155;">
        <strong>Identity model:</strong> auth users log in, actors hold runtime permissions, and agents provide behavior/configuration. This page edits <strong>auth_user</strong> records and routes channel permissions through the linked <strong>actor_id</strong>.
    </div>

    <?php if ($edit_profile_user !== null): ?>
        <div class="admin-users-edit-profile">
            <h2>Edit profile: <?= htmlspecialchars($edit_profile_user['username']) ?></h2>
            <p class="admin-hint">This form updates the human auth record only. Runtime channel actions still execute through the paired actor.</p>
            <form method="post" action="<?= htmlspecialchars($base) ?>/admin.php?section=users&amp;save_profile=1">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(function_exists('lupo_get_csrf_token') ? lupo_get_csrf_token() : '') ?>">
                <input type="hidden" name="auth_user_id" value="<?= (int) $edit_profile_user['auth_user_id'] ?>">
                <p><label>Username (read-only)</label><br><input type="text" value="<?= htmlspecialchars($edit_profile_user['username']) ?>" disabled class="admin-input"></p>
                <p><label>Display name</label><br><input type="text" name="display_name" value="<?= htmlspecialchars(isset($edit_profile_user['display_name']) ? $edit_profile_user['display_name'] : '') ?>" class="admin-input" maxlength="42"></p>
                <p><label>Email</label><br><input type="email" name="email" value="<?= htmlspecialchars(isset($edit_profile_user['email']) ? $edit_profile_user['email'] : '') ?>" class="admin-input" maxlength="100"></p>
                <p><label><input type="checkbox" name="is_active" value="1" <?= !empty($edit_profile_user['is_active']) ? ' checked' : '' ?>> Active</label></p>
                <p><button type="submit" class="admin-btn admin-btn-primary">Save profile</button> <a href="<?= htmlspecialchars($base) ?>/admin.php?section=users" class="admin-btn">Cancel</a></p>
            </form>
        </div>
    <?php elseif ($edit_permissions_user !== null): ?>
        <div class="admin-users-edit-permissions">
            <h2>Edit permissions: <?= htmlspecialchars(isset($edit_permissions_user['display_name']) && $edit_permissions_user['display_name'] !== '' ? $edit_permissions_user['display_name'] : $edit_permissions_user['username']) ?></h2>
            <p class="admin-users-meta">Username: <?= htmlspecialchars($edit_permissions_user['username']) ?> · Actor ID: <?= (int) (isset($edit_permissions_user['actor_id']) ? $edit_permissions_user['actor_id'] : 0) ?></p>
            <p class="admin-hint">Permissions are assigned to the actor, not directly to the auth user.</p>
            <form method="post" action="<?= htmlspecialchars($base) ?>/admin.php?section=users&amp;save_permissions=1">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(function_exists('lupo_get_csrf_token') ? lupo_get_csrf_token() : '') ?>">
                <input type="hidden" name="actor_id" value="<?= (int) (isset($edit_permissions_user['actor_id']) ? $edit_permissions_user['actor_id'] : 0) ?>">
                <p><label>Channel 1 (default) role</label><br>
                    <select name="channel1_role_type" class="admin-input">
                        <option value=""<?= $channel1_role === '' ? ' selected' : '' ?>>— None —</option>
                        <option value="captain"<?= $channel1_role === 'captain' ? ' selected' : '' ?>>Captain</option>
                        <option value="administrator"<?= $channel1_role === 'administrator' ? ' selected' : '' ?>>Administrator</option>
                        <option value="monitor"<?= $channel1_role === 'monitor' ? ' selected' : '' ?>>Monitor</option>
                    </select>
                </p>
                <p class="admin-hint">Captain and Administrator grant admin access (admin.php). Monitor is a non-admin channel role.</p>
                <p><button type="submit" class="admin-btn admin-btn-primary">Save permissions</button> <a href="<?= htmlspecialchars($base) ?>/admin.php?section=users" class="admin-btn">Cancel</a></p>
            </form>
        </div>
    <?php else: ?>
        <div class="admin-users-list-wrap">
            <table class="admin-users-table">
                <thead>
                    <tr>
                        <th>Auth User ID</th>
                        <th>Username</th>
                        <th>Display name</th>
                        <th>Email</th>
                        <th>Primary Actor</th>
                        <th>Channel 1 role</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users_list as $u): ?>
                        <tr>
                            <td><?= (int) $u['auth_user_id'] ?></td>
                            <td><?= htmlspecialchars($u['username']) ?></td>
                            <td><?= htmlspecialchars(isset($u['display_name']) ? $u['display_name'] : '') ?></td>
                            <td><?= htmlspecialchars(isset($u['email']) ? $u['email'] : '') ?></td>
                            <td><?= !empty($u['actor_id']) ? (int) $u['actor_id'] : 'Unpaired' ?></td>
                            <td><?= htmlspecialchars(isset($u['channel1_role']) ? $u['channel1_role'] : '—') ?></td>
                            <td><?= !empty($u['is_active']) ? 'Yes' : 'No' ?></td>
                            <td class="admin-users-actions">
                                <a href="<?= htmlspecialchars($base) ?>/admin.php?section=users&amp;edit_profile=<?= (int) $u['auth_user_id'] ?>" class="admin-link">Edit profile</a>
                                <?php if (!empty($u['actor_id'])): ?>
                                    | <a href="<?= htmlspecialchars($base) ?>/admin.php?section=users&amp;edit_permissions=<?= (int) $u['actor_id'] ?>" class="admin-link">Edit permissions</a>
                                <?php else: ?>
                                    | <span class="admin-muted">Edit permissions (no actor)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($users_list)): ?>
                <p class="admin-empty">No users found.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
