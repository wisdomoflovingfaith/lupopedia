<?php
/**
 * Visitor session helper for Crafty Syntax visitor chat.
 * Validates cslhVISITOR, updates last_seen_ymdhis, resolves channel_id and dialog_thread_id from session metadata.
 * Used by channel APIs when request includes cslhVISITOR (visitor mode). All paths use LUPOPEDIA_PUBLIC_PATH.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}

/**
 * Get cslhVISITOR from GET or POST (legacy: always include in requests).
 *
 * @return string Empty if not present.
 */
function crafty_syntax_visitor_session_id() {
    $sid = isset($_GET['cslhVISITOR']) ? (string) $_GET['cslhVISITOR'] : '';
    if ($sid === '' && isset($_POST['cslhVISITOR'])) {
        $sid = (string) $_POST['cslhVISITOR'];
    }
    return $sid;
}

/**
 * Validate visitor session: must exist in lupo_sessions with actor_id = 0, not expired.
 * Updates last_seen_ymdhis on success. Does not use lupo_validate_session (which may enforce expires_ymdhis).
 *
 * @param string $session_id cslhVISITOR value
 * @return bool True if valid visitor session
 */
function crafty_syntax_validate_visitor_session($session_id) {
    if ($session_id === '' || !isset($GLOBALS['mydatabase'])) {
        return false;
    }
    $db = $GLOBALS['mydatabase'];
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $now = date('YmdHis');
    try {
        $stmt = $db->prepare(
            "SELECT session_id, actor_id FROM {$prefix}sessions WHERE session_id = :sid AND is_deleted = 0 AND actor_id = 0 LIMIT 1"
        );
        $stmt->execute(array(':sid' => $session_id));
        if ($stmt->fetch() === false) {
            return false;
        }
        $stmt = $db->prepare(
            "UPDATE {$prefix}sessions SET last_seen_ymdhis = :now, updated_ymdhis = :now WHERE session_id = :sid"
        );
        $stmt->execute(array(':now' => $now, ':sid' => $session_id));
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Get visitor's thread info from session metadata (crafty_syntax key).
 * Returns channel_id (0 when pending), dialog_thread_id, status ('pending'|'active').
 *
 * @param string $session_id cslhVISITOR value
 * @return array{channel_id: int, dialog_thread_id: int, status: string}|null
 */
function crafty_syntax_visitor_thread_from_session($session_id) {
    if ($session_id === '' || !isset($GLOBALS['mydatabase'])) {
        return null;
    }
    $db = $GLOBALS['mydatabase'];
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $meta_col = 'metadata';
    try {
        $stmt = $db->prepare("SELECT {$meta_col} FROM {$prefix}sessions WHERE session_id = :sid AND is_deleted = 0 LIMIT 1");
        $stmt->execute(array(':sid' => $session_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row || empty($row[$meta_col])) {
            return null;
        }
        $data = json_decode($row[$meta_col], true);
        if (!is_array($data) || empty($data['crafty_syntax'])) {
            return null;
        }
        $cs = $data['crafty_syntax'];
        $dialog_thread_id = isset($cs['dialog_thread_id']) ? (int) $cs['dialog_thread_id'] : 0;
        if ($dialog_thread_id <= 0) {
            return null;
        }
        $status = isset($cs['status']) ? (string) $cs['status'] : 'pending';
        $channel_id = isset($cs['channel_id']) ? (int) $cs['channel_id'] : 0;
        return array(
            'channel_id'       => $channel_id,
            'dialog_thread_id' => $dialog_thread_id,
            'status'           => $status,
        );
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Save visitor's PENDING thread (no channel). status = "pending".
 *
 * @param string $session_id cslhVISITOR value
 * @param int $department_id Department
 * @param int $dialog_thread_id Dialog thread ID (thread must have channel_id IS NULL)
 * @return bool Success
 */
function crafty_syntax_visitor_save_pending_thread_to_session($session_id, $department_id, $dialog_thread_id) {
    if ($session_id === '' || $dialog_thread_id <= 0 || !isset($GLOBALS['mydatabase'])) {
        return false;
    }
    $db = $GLOBALS['mydatabase'];
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $now = date('YmdHis');
    $meta_col = 'metadata';
    try {
        $stmt = $db->prepare("SELECT {$meta_col} FROM {$prefix}sessions WHERE session_id = :sid AND is_deleted = 0 LIMIT 1");
        $stmt->execute(array(':sid' => $session_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = array();
        if ($row && !empty($row[$meta_col])) {
            $dec = json_decode($row[$meta_col], true);
            if (is_array($dec)) {
                $data = $dec;
            }
        }
        $data['crafty_syntax'] = array(
            'department_id'     => (int) $department_id,
            'channel_id'        => 0,
            'dialog_thread_id'  => (int) $dialog_thread_id,
            'status'            => 'pending',
        );
        $json = json_encode($data);
        $stmt = $db->prepare("UPDATE {$prefix}sessions SET {$meta_col} = :meta, last_seen_ymdhis = :now, updated_ymdhis = :now WHERE session_id = :sid");
        $stmt->execute(array(':meta' => $json, ':now' => $now, ':sid' => $session_id));
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Save visitor's channel_id and dialog_thread_id into session metadata (active/accepted).
 *
 * @param string $session_id cslhVISITOR value
 * @param int $department_id Department (for metadata)
 * @param int $channel_id Channel ID (operator's channel)
 * @param int $dialog_thread_id Dialog thread ID
 * @return bool Success
 */
function crafty_syntax_visitor_save_thread_to_session($session_id, $department_id, $channel_id, $dialog_thread_id) {
    if ($session_id === '' || $channel_id <= 0 || $dialog_thread_id <= 0 || !isset($GLOBALS['mydatabase'])) {
        return false;
    }
    $db = $GLOBALS['mydatabase'];
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $now = date('YmdHis');
    $meta_col = 'metadata';
    try {
        $stmt = $db->prepare("SELECT {$meta_col} FROM {$prefix}sessions WHERE session_id = :sid AND is_deleted = 0 LIMIT 1");
        $stmt->execute(array(':sid' => $session_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = array();
        if ($row && !empty($row[$meta_col])) {
            $dec = json_decode($row[$meta_col], true);
            if (is_array($dec)) {
                $data = $dec;
            }
        }
        $data['crafty_syntax'] = array(
            'department_id'     => (int) $department_id,
            'channel_id'        => (int) $channel_id,
            'dialog_thread_id'  => (int) $dialog_thread_id,
            'status'            => 'active',
        );
        $json = json_encode($data);
        $stmt = $db->prepare("UPDATE {$prefix}sessions SET {$meta_col} = :meta, last_seen_ymdhis = :now, updated_ymdhis = :now WHERE session_id = :sid");
        $stmt->execute(array(':meta' => $json, ':now' => $now, ':sid' => $session_id));
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Set visitor session to accepted: status = "active", channel_id = operator_channel_id.
 *
 * @param string $session_id cslhVISITOR value
 * @param int $operator_channel_id Operator's channel ID
 * @return bool Success
 */
function crafty_syntax_visitor_set_accepted($session_id, $operator_channel_id) {
    if ($session_id === '' || $operator_channel_id <= 0 || !isset($GLOBALS['mydatabase'])) {
        return false;
    }
    $db = $GLOBALS['mydatabase'];
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $meta_col = 'metadata';
    try {
        $stmt = $db->prepare("SELECT {$meta_col} FROM {$prefix}sessions WHERE session_id = :sid AND is_deleted = 0 LIMIT 1");
        $stmt->execute(array(':sid' => $session_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row || empty($row[$meta_col])) {
            return false;
        }
        $data = json_decode($row[$meta_col], true);
        if (!is_array($data) || empty($data['crafty_syntax'])) {
            return false;
        }
        $data['crafty_syntax']['channel_id'] = (int) $operator_channel_id;
        $data['crafty_syntax']['status'] = 'active';
        $now = date('YmdHis');
        $json = json_encode($data);
        $stmt = $db->prepare("UPDATE {$prefix}sessions SET {$meta_col} = :meta, last_seen_ymdhis = :now, updated_ymdhis = :now WHERE session_id = :sid");
        $stmt->execute(array(':meta' => $json, ':now' => $now, ':sid' => $session_id));
        return true;
    } catch (Exception $e) {
        return false;
    }
}
