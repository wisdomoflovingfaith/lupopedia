<?php
/**
 * Admin Users section handler. Builds list, edit profile, and edit permissions UI.
 * PHP 5.3+ compatible; no namespaces. Uses PDO_DB and TOON-backed table/column names.
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

class AdminUsersHandler {

    /**
     * Render the admin Users section HTML. Handles POST (save profile / save permissions) with redirect.
     *
     * @param object $db   PDO_DB instance
     * @param string $prefix Table prefix (e.g. lupo_)
     * @param string $base  Base URL path (e.g. LUPOPEDIA_PUBLIC_PATH)
     * @return string HTML for the section, or exits on POST redirect
     */
    public static function render($db, $prefix, $base) {
        $au = $db->quoteIdentifier($prefix . 'auth_users');
        $ac = $db->quoteIdentifier($prefix . 'actors');
        $cr = $db->quoteIdentifier($prefix . 'channel_roles');
        $now = gmdate('YmdHis');

        // POST: save profile
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['save_profile']) && isset($_POST['auth_user_id'])) {
            $auth_user_id = (int) $_POST['auth_user_id'];
            $display_name = isset($_POST['display_name']) ? trim((string) $_POST['display_name']) : '';
            $email = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
            $is_active = isset($_POST['is_active']) && $_POST['is_active'] === '1' ? 1 : 0;
            if ($auth_user_id > 0 && $display_name !== '') {
                $db->update($prefix . 'auth_users', array(
                    'display_name' => $display_name,
                    'email' => $email,
                    'is_active' => $is_active,
                    'updated_ymdhis' => $now,
                ), 'auth_user_id = :aid', array(':aid' => $auth_user_id));
            }
            header('Location: ' . $base . '/admin.php?section=users&msg=profile_saved');
            exit;
        }

        // POST: save permissions (channel 1 role)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['save_permissions']) && isset($_POST['actor_id'])) {
            $actor_id = (int) $_POST['actor_id'];
            $new_role = isset($_POST['channel1_role_type']) ? trim((string) $_POST['channel1_role_type']) : '';
            if ($actor_id > 0) {
                $db->query(
                    "UPDATE {$cr} SET is_deleted = 1, deleted_ymdhis = :now WHERE actor_id = :actor_id AND channel_id = 1",
                    array(':now' => $now, ':actor_id' => $actor_id)
                );
                if ($new_role !== '') {
                    $next_id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $prefix . 'channel_roles', 'channel_role_id', 1, null) : (int) $db->fetchOne("SELECT COALESCE(MAX(channel_role_id), 0) + 1 FROM {$cr}", array());
                    if ($next_id === null) {
                        $next_id = (int) $db->fetchOne("SELECT COALESCE(MAX(channel_role_id), 0) + 1 FROM {$cr}", array());
                    }
                    $db->query(
                        "INSERT INTO {$cr} (channel_role_id, channel_id, actor_id, role_type, created_ymdhis, updated_ymdhis, is_deleted) VALUES (:id, 1, :actor_id, :role_type, :now, :now2, 0)",
                        array(':id' => $next_id, ':actor_id' => $actor_id, ':role_type' => $new_role, ':now' => $now, ':now2' => $now)
                    );
                }
            }
            header('Location: ' . $base . '/admin.php?section=users&msg=permissions_saved');
            exit;
        }

        $message = '';
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'profile_saved') {
                $message = 'Profile saved.';
            } elseif ($_GET['msg'] === 'permissions_saved') {
                $message = 'Permissions saved.';
            }
        }

        // GET: edit profile
        if (isset($_GET['edit_profile'])) {
            $auth_user_id = (int) $_GET['edit_profile'];
            $row = $db->fetchRow("SELECT auth_user_id, username, display_name, email, is_active FROM {$au} WHERE auth_user_id = :id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1", array(':id' => $auth_user_id));
            if ($row) {
                $edit_profile_user = $row;
                $edit_permissions_user = null;
                $channel1_role = '';
                $users_list = array();
                return self::includeView($users_list, $edit_profile_user, $edit_permissions_user, $channel1_role, $message, $base);
            }
        }

        // GET: edit permissions
        if (isset($_GET['edit_permissions'])) {
            $actor_id = (int) $_GET['edit_permissions'];
            $user_row = $db->fetchRow(
                "SELECT u.auth_user_id, u.username, u.display_name, u.email, a.actor_id FROM {$au} u INNER JOIN {$ac} a ON a.actor_source_id = u.auth_user_id AND a.actor_source_type = 'user' AND (a.is_deleted = 0 OR a.is_deleted IS NULL) WHERE a.actor_id = :aid AND (u.is_deleted = 0 OR u.is_deleted IS NULL) LIMIT 1",
                array(':aid' => $actor_id)
            );
            if ($user_row) {
                $role_row = $db->fetchRow("SELECT role_type FROM {$cr} WHERE actor_id = :aid AND channel_id = 1 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1", array(':aid' => $actor_id));
                $channel1_role = $role_row ? (string) $role_row['role_type'] : '';
                $edit_profile_user = null;
                $edit_permissions_user = $user_row;
                $users_list = array();
                return self::includeView($users_list, $edit_profile_user, $edit_permissions_user, $channel1_role, $message, $base);
            }
        }

        // List all users with channel 1 role
        $users = $db->fetchAll(
            "SELECT u.auth_user_id, u.username, u.display_name, u.email, u.is_active, a.actor_id FROM {$au} u LEFT JOIN {$ac} a ON a.actor_source_id = u.auth_user_id AND a.actor_source_type = 'user' AND (a.is_deleted = 0 OR a.is_deleted IS NULL) WHERE (u.is_deleted = 0 OR u.is_deleted IS NULL) ORDER BY u.username",
            array()
        );
        $actor_ids = array_values(array_filter(array_column($users, 'actor_id')));
        $roles_by_actor = array();
        if (!empty($actor_ids)) {
            $in_placeholders = array();
            $in_params = array();
            foreach ($actor_ids as $i => $aid) {
                $key = 'aid' . $i;
                $in_placeholders[] = ':' . $key;
                $in_params[':' . $key] = (int) $aid;
            }
            $in_sql = implode(', ', $in_placeholders);
            $role_rows = $db->fetchAll(
                "SELECT actor_id, role_type FROM {$cr} WHERE channel_id = 1 AND actor_id IN ({$in_sql}) AND (is_deleted = 0 OR is_deleted IS NULL)",
                $in_params
            );
            foreach ($role_rows as $r) {
                $roles_by_actor[(int) $r['actor_id']] = $r['role_type'];
            }
        }
        foreach ($users as $k => $u) {
            $users[$k]['channel1_role'] = (isset($u['actor_id'], $roles_by_actor[(int) $u['actor_id']])) ? $roles_by_actor[(int) $u['actor_id']] : '—';
        }
        $users_list = $users;
        $edit_profile_user = null;
        $edit_permissions_user = null;
        $channel1_role = '';
        return self::includeView($users_list, $edit_profile_user, $edit_permissions_user, $channel1_role, $message, $base);
    }

    /**
     * Include the users view and return captured output.
     */
    private static function includeView($users_list, $edit_profile_user, $edit_permissions_user, $channel1_role, $message, $base) {
        ob_start();
        include LUPOPEDIA_PATH . '/lupo-includes/themes/default/layouts/admin_sections/users.php';
        return ob_get_clean();
    }
}
