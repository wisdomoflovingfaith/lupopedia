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
        $aau = $db->quoteIdentifier($prefix . 'actor_auth_users');
        $cr = $db->quoteIdentifier($prefix . 'actor_channel_roles');
        $now = gmdate('YmdHis');

        // POST: save profile
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['save_profile']) && isset($_POST['auth_user_id'])) {
            lupo_require_valid_csrf_token();
            $auth_user_id = (int) $_POST['auth_user_id'];
            $display_name = isset($_POST['display_name']) ? trim((string) $_POST['display_name']) : '';
            $email = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
            $is_active = isset($_POST['is_active']) && $_POST['is_active'] === '1' ? 1 : 0;
            $profile_ok = false;
            if ($auth_user_id > 0 && $display_name !== '') {
                $db->update($prefix . 'auth_users', array(
                    'display_name' => $display_name,
                    'email' => $email,
                    'is_active' => $is_active,
                    'updated_ymdhis' => $now,
                ), 'auth_user_id = :aid', array(':aid' => $auth_user_id));
                $profile_ok = true;
            }
            if (function_exists('lupo_diag_admin_action')) {
                $cu = (isset($GLOBALS['lupo_auth_service']) && is_object($GLOBALS['lupo_auth_service']) && method_exists($GLOBALS['lupo_auth_service'], 'getCurrentUser')) ? $GLOBALS['lupo_auth_service']->getCurrentUser() : (function_exists('current_user') ? current_user() : array());
                $actor_id = (is_array($cu) && isset($cu['actor_id'])) ? (int) $cu['actor_id'] : 0;
                lupo_diag_admin_action($actor_id, 'save_profile', 'auth_user', $auth_user_id, $profile_ok);
            }
            header('Location: ' . $base . '/admin.php?section=users&msg=profile_saved');
            exit;
        }

        // POST: save permissions (channel 1 role)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['save_permissions']) && isset($_POST['actor_id'])) {
            lupo_require_valid_csrf_token();
            $target_actor_id = (int) $_POST['actor_id'];
            $new_role = isset($_POST['channel1_role_type']) ? trim((string) $_POST['channel1_role_type']) : '';
            $perm_ok = false;
            if ($target_actor_id > 0) {
                $db->query(
                    "UPDATE {$cr} SET is_deleted = 1, deleted_ymdhis = :now WHERE actor_id = :actor_id AND channel_id = 1",
                    array(':now' => $now, ':actor_id' => $target_actor_id)
                );
                if ($new_role !== '') {
                    $next_id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $prefix . 'actor_channel_roles', 'actor_channel_role_id', 1, null) : (int) $db->fetchOne("SELECT COALESCE(MAX(actor_channel_role_id), 0) + 1 FROM {$cr}", array());
                    if ($next_id === null) {
                        $next_id = (int) $db->fetchOne("SELECT COALESCE(MAX(actor_channel_role_id), 0) + 1 FROM {$cr}", array());
                    }
                    $db->query(
                        "INSERT INTO {$cr} (actor_channel_role_id, channel_id, actor_id, role_key, created_ymdhis, updated_ymdhis, is_deleted) VALUES (:id, 1, :actor_id, :role_key, :now, :now2, 0)",
                        array(':id' => $next_id, ':actor_id' => $target_actor_id, ':role_key' => $new_role, ':now' => $now, ':now2' => $now)
                    );
                }
                $perm_ok = true;
            }
            if (function_exists('lupo_diag_admin_action')) {
                $cu = (isset($GLOBALS['lupo_auth_service']) && is_object($GLOBALS['lupo_auth_service']) && method_exists($GLOBALS['lupo_auth_service'], 'getCurrentUser')) ? $GLOBALS['lupo_auth_service']->getCurrentUser() : (function_exists('current_user') ? current_user() : array());
                $actor_id = (is_array($cu) && isset($cu['actor_id'])) ? (int) $cu['actor_id'] : 0;
                lupo_diag_admin_action($actor_id, 'save_permissions', 'actor_channel_role', $target_actor_id, $perm_ok);
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
            // Distinct placeholders (:aid1/:aid2): PDO does not allow reusing one named marker in multiple positions.
            $user_row = $db->fetchRow(
                "SELECT u.auth_user_id, u.username, u.display_name, u.email, " . $actor_id . " AS actor_id
                 FROM {$au} u
                 WHERE u.auth_user_id = COALESCE(
                         (
                                 SELECT aau.auth_user_id
                                 FROM {$aau} aau
                                 WHERE aau.actor_id = :aid1
                                     AND aau.status = 'active'
                                     AND (aau.is_deleted = 0 OR aau.is_deleted IS NULL)
                                 ORDER BY aau.is_primary DESC, aau.routing_priority ASC, aau.actor_auth_user_id ASC
                                 LIMIT 1
                         ),
                         (
                                 SELECT a.actor_source_id
                                 FROM {$ac} a
                                 WHERE a.actor_id = :aid2
                                     AND (a.actor_source_type = 'user' OR a.actor_source_type = '{$prefix}auth_users')
                                     AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
                                 LIMIT 1
                         )
                 )
                     AND (u.is_deleted = 0 OR u.is_deleted IS NULL)
                 LIMIT 1",
                array(':aid1' => $actor_id, ':aid2' => $actor_id)
            );
            if ($user_row) {
                $role_row = $db->fetchRow("SELECT role_key FROM {$cr} WHERE actor_id = :aid AND channel_id = 1 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1", array(':aid' => $actor_id));
                $channel1_role = $role_row ? (string) $role_row['role_key'] : '';
                $edit_profile_user = null;
                $edit_permissions_user = $user_row;
                $users_list = array();
                return self::includeView($users_list, $edit_profile_user, $edit_permissions_user, $channel1_role, $message, $base);
            }
        }

        // List all users with channel 1 role
        $users = $db->fetchAll(
            "SELECT u.auth_user_id, u.username, u.display_name, u.email, u.is_active,
                    (
                        SELECT aau.actor_id
                        FROM {$aau} aau
                        WHERE aau.auth_user_id = u.auth_user_id
                          AND aau.status = 'active'
                          AND (aau.is_deleted = 0 OR aau.is_deleted IS NULL)
                        ORDER BY aau.is_primary DESC, aau.routing_priority ASC, aau.actor_auth_user_id ASC
                        LIMIT 1
                    ) AS actor_id
             FROM {$au} u
             WHERE (u.is_deleted = 0 OR u.is_deleted IS NULL)
             ORDER BY u.username",
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
                "SELECT actor_id, role_key FROM {$cr} WHERE channel_id = 1 AND actor_id IN ({$in_sql}) AND (is_deleted = 0 OR is_deleted IS NULL)",
                $in_params
            );
            foreach ($role_rows as $r) {
                $roles_by_actor[(int) $r['actor_id']] = $r['role_key'];
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
        include LUPOPEDIA_PATH . '/includes/themes/default/layouts/admin_sections/users.php';
        return ob_get_clean();
    }
}
