<?php
function lupo_crafty_h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function lupo_crafty_base_url() {
    $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    return $base . '/crafty_syntax/';
}

function lupo_crafty_url($page, $params = []) {
    $params['page'] = $page;
    return lupo_crafty_base_url() . 'index.php?' . http_build_query($params);
}

function lupo_crafty_format_ymdhis($value) {
    if (empty($value)) {
        return '—';
    }
    $value = (string)$value;
    if (strlen($value) !== 14) {
        return lupo_crafty_h($value);
    }
    return substr($value, 0, 4) . '-' . substr($value, 4, 2) . '-' . substr($value, 6, 2) .
        ' ' . substr($value, 8, 2) . ':' . substr($value, 10, 2) . ':' . substr($value, 12, 2) . ' UTC';
}

function lupo_crafty_nav_sections($operator, $is_admin) {
    return [
        'General' => [
            ['label' => 'Documentation', 'url' => 'https://lupopedia.com/salessyntax_docs/howto.php', 'external' => true],
            ['label' => 'Help', 'url' => lupo_crafty_url('settings', ['tab' => 'help'])],
        ],
        'CRM Tools' => [
            ['label' => 'Leads Database', 'url' => lupo_crafty_url('modules', ['tab' => 'leads'])],
            ['label' => 'Email Message Database', 'url' => lupo_crafty_url('modules', ['tab' => 'emails'])],
            ['label' => 'Proactive Leads', 'url' => lupo_crafty_url('modules', ['tab' => 'autoleads'])],
            ['label' => 'Import Leads', 'url' => lupo_crafty_url('modules', ['tab' => 'import'])],
        ],
        'AI Agents Tools' => [
            ['label' => 'Agents', 'url' => lupo_crafty_url('modules', ['tab' => 'agents'])],
            ['label' => 'Channels', 'url' => lupo_crafty_url('modules', ['tab' => 'channels'])],
        ],
        'Live Help' => [
            ['label' => 'Operator Overview', 'url' => lupo_crafty_url('operator')],
            ['label' => 'Quick Replies', 'url' => lupo_crafty_url('modules', ['tab' => 'quick'])],
            ['label' => 'Auto Invites', 'url' => lupo_crafty_url('modules', ['tab' => 'autoinvite'])],
            ['label' => 'Emotion Icons', 'url' => lupo_crafty_url('settings', ['tab' => 'smilies'])],
            ['label' => 'Layer Images', 'url' => lupo_crafty_url('settings', ['tab' => 'layers'])],
        ],
        'Operators' => [
            ['label' => 'Edit Your Account', 'url' => lupo_crafty_url('operator', ['action' => 'profile'])],
            $is_admin ? ['label' => 'Create/Edit Operators', 'url' => lupo_crafty_url('operator', ['action' => 'manage'])] : null,
        ],
        'Departments' => [
            ['label' => 'Department HTML Code', 'url' => lupo_crafty_url('departments', ['tab' => 'embed'])],
            $is_admin ? ['label' => 'Create/Edit Departments', 'url' => lupo_crafty_url('departments')] : null,
        ],
        'Analytics' => [
            ['label' => 'Visitor Analytics', 'url' => lupo_crafty_url('analytics', ['tab' => 'visits'])],
            ['label' => 'Messages', 'url' => lupo_crafty_url('analytics', ['tab' => 'messages'])],
            ['label' => 'Transcripts', 'url' => lupo_crafty_url('analytics', ['tab' => 'transcripts'])],
            ['label' => 'Referers', 'url' => lupo_crafty_url('analytics', ['tab' => 'referers'])],
        ],
        'Modules' => [
            ['label' => 'Questions & Answers', 'url' => lupo_crafty_url('modules', ['tab' => 'qa'])],
        ],
    ];
}

function lupo_crafty_render($view, $data = []) {
    extract($data, EXTR_SKIP);
    include __DIR__ . '/../views/' . $view . '.php';
}

/**
 * Get operator ID from auth user ID
 *
 * @param int $auth_user_id
 * @return int|null Operator ID or null if not found
 */
function lupo_get_operator_id_from_auth_user_id($auth_user_id) {
    if (empty($auth_user_id)) {
        return null;
    }

    $db = lupo_crafty_db();
    if (!$db) {
        return null;
    }

    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    try {
        $sql = "SELECT operator_id
                FROM {$table_prefix}operators
                WHERE auth_user_id = :auth_user_id
                  AND is_active = 1
                LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->execute([':auth_user_id' => (int)$auth_user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return (int)$result['operator_id'];
        }

        return null;

    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("CRAFTY HELPERS: Failed to get operator ID: " . $e->getMessage());
        }
        return null;
    }
}

/**
 * Check if current operator is admin
 *
 * @param int $operator_id Optional operator ID (uses current if not provided)
 * @return bool
 */
function lupo_crafty_is_admin($operator_id = null) {
    if ($operator_id === null) {
        $operator = lupo_crafty_operator();
        if (!$operator) {
            return false;
        }
        $operator_id = $operator['operator_id'];
    }

    // Get auth_user_id from operator
    $db = lupo_crafty_db();
    if (!$db) {
        return false;
    }

    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    try {
        $sql = "SELECT auth_user_id FROM {$table_prefix}operators WHERE operator_id = :operator_id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':operator_id' => (int)$operator_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        $auth_user_id = (int)$result['auth_user_id'];

        // Get actor_id from auth_user_id
        $actor_id = lupo_get_actor_id_from_auth_user_id($auth_user_id);
        if (!$actor_id) {
            return false;
        }

        // Use main Lupopedia function
        return lupo_is_admin($actor_id);

    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("CRAFTY HELPERS: Failed to check admin status: " . $e->getMessage());
        }
        return false;
    }
}
