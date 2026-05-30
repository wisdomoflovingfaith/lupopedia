<?php
/**
 * Admin channel chat interface with effective actor resolution controls.
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

class AdminChannelChatHandler
{
    /**
     * Render chat section.
     *
     * @param object $db
     * @param string $prefix
     * @param string $base
     * @return string
     */
    public static function render($db, $prefix, $base)
    {
        require_once LUPOPEDIA_PATH . '/includes/classes/EffectiveActorResolver.php';

        $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
        $actorService = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;

        $user = $authService && method_exists($authService, 'getCurrentUser') ? $authService->getCurrentUser() : (function_exists('current_user') ? current_user() : false);
        if (!$user || empty($user['auth_user_id']) || !$actorService) {
            return '<p class="admin-empty">Authenticated user or actor service unavailable.</p>';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_chat_identity_preferences'])) {
            lupo_require_valid_csrf_token();
            $preferred_actor_id = isset($_POST['preferred_actor_id']) ? (int) $_POST['preferred_actor_id'] : 0;
            $preferred_agent_id = isset($_POST['preferred_agent_id']) ? (int) $_POST['preferred_agent_id'] : 0;
            $preferred_department_id = isset($_POST['preferred_department_id']) ? (int) $_POST['preferred_department_id'] : 0;

            EffectiveActorResolver::setPreferences($preferred_actor_id, $preferred_agent_id, $preferred_department_id);

            if ($preferred_actor_id > 0 && $authService && method_exists($authService, 'setActiveActorId')) {
                $authService->setActiveActorId($preferred_actor_id);
            }

            header('Location: ' . $base . '/admin.php?section=channel-chat&msg=chat_prefs_saved');
            exit;
        }

        $is_admin = !empty($user['is_admin']);
        $allowed = $actorService->getActorsUserCanActAs((int) $user['auth_user_id'], $is_admin);
        if (!is_array($allowed)) {
            $allowed = array();
        }

        $allowed_ids = array();
        foreach ($allowed as $a) {
            if (isset($a['actor_id'])) {
                $allowed_ids[] = (int) $a['actor_id'];
            }
        }

        $actors = self::loadAllowedActors($db, $prefix, $allowed_ids);
        $departments = self::loadDepartments($db, $prefix);
        $channels = self::loadAccessibleChannels($db, $prefix, $allowed_ids, $is_admin);

        $prefs = EffectiveActorResolver::getPreferences();
        $effective = EffectiveActorResolver::resolveForCurrentUser($db, 0);
        $effective_actor_id = isset($effective['actor_id']) ? (int) $effective['actor_id'] : 0;

        $message = '';
        if (isset($_GET['msg']) && $_GET['msg'] === 'chat_prefs_saved') {
            $message = 'Chat identity preferences saved.';
        }

        $public_path = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
        $api_base = $public_path . '/api/channels';

        $chat_data = array(
            'actors' => $actors,
            'channels' => $channels,
            'departments' => $departments,
            'prefs' => $prefs,
            'effective_actor_id' => $effective_actor_id,
            'effective_reason' => isset($effective['reason']) ? $effective['reason'] : '',
            'message' => $message,
            'base' => $base,
            'api_base' => $api_base,
            'csrf_token' => function_exists('lupo_get_csrf_token') ? lupo_get_csrf_token() : '',
        );

        ob_start();
        include LUPOPEDIA_PATH . '/includes/themes/default/layouts/admin_sections/channel_chat.php';
        return ob_get_clean();
    }

    private static function loadAllowedActors($db, $prefix, $actor_ids)
    {
        if (empty($actor_ids)) {
            return array();
        }
        $placeholders = array();
        $params = array();
        foreach ($actor_ids as $i => $aid) {
            $key = 'aid' . $i;
            $placeholders[] = ':' . $key;
            $params[$key] = (int) $aid;
        }
        $t = $db->quoteIdentifier($prefix . 'actors');
        $sql = "SELECT actor_id, actor_name, name, actor_type, department_id, is_agent
                FROM {$t}
                WHERE actor_id IN (" . implode(', ', $placeholders) . ")
                  AND (is_deleted = 0 OR is_deleted IS NULL)
                ORDER BY actor_id ASC";
        return $db->fetchAll($sql, $params);
    }

    private static function loadDepartments($db, $prefix)
    {
        $t = $db->quoteIdentifier($prefix . 'departments');
        $sql = "SELECT department_id, department_name
                FROM {$t}
                WHERE (is_deleted = 0 OR is_deleted IS NULL)
                ORDER BY department_id ASC";
        return $db->fetchAll($sql, array());
    }

    private static function loadAccessibleChannels($db, $prefix, $actor_ids, $is_admin)
    {
        if ($is_admin) {
            $t = $db->quoteIdentifier($prefix . 'channels');
            $sql = "SELECT channel_id, channel_name
                    FROM {$t}
                    WHERE (is_deleted = 0 OR is_deleted IS NULL)
                    ORDER BY channel_id ASC";
            return $db->fetchAll($sql, array());
        }

        if (empty($actor_ids)) {
            return array();
        }

        $placeholders = array();
        $params = array();
        foreach ($actor_ids as $i => $aid) {
            $key = 'aid' . $i;
            $placeholders[] = ':' . $key;
            $params[$key] = (int) $aid;
        }

        $ac = $db->quoteIdentifier($prefix . 'actor_channels');
        $c = $db->quoteIdentifier($prefix . 'channels');
        $sql = "SELECT DISTINCT c.channel_id, c.channel_name
                FROM {$c} c
                INNER JOIN {$ac} ac ON ac.channel_id = c.channel_id
                WHERE ac.actor_id IN (" . implode(', ', $placeholders) . ")
                  AND (ac.is_deleted = 0 OR ac.is_deleted IS NULL)
                  AND (c.is_deleted = 0 OR c.is_deleted IS NULL)
                ORDER BY c.channel_id ASC";
        return $db->fetchAll($sql, $params);
    }
}

