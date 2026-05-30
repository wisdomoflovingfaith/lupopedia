<?php
/**
 * Resolve effective actor for authenticated chat operations.
 *
 * Priority:
 * 1) Active actor selected in session (if still allowed)
 * 2) Explicit preferred_actor_id override
 * 3) Current authenticated user's default actor_id
 * 4) Preferred department fallback within allowed actors
 * 5) First allowed actor
 *
 * preferred_agent_id is stored as an advisory behavior preference, but it does not
 * directly become actor identity during resolution.
 *
 * Optional channel guard enforces actor-channel membership unless user is global admin.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}

class EffectiveActorResolver
{
    /**
     * Resolve effective actor for current authenticated user.
     *
     * @param object $db PDO_DB instance
     * @param int $channel_id Optional channel guard (0 = no channel guard)
     * @return array
     */
    public static function resolveForCurrentUser($db, $channel_id)
    {
        $channel_id = (int) $channel_id;
        $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
        $actorService = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

        $user = false;
        if ($authService && is_object($authService) && method_exists($authService, 'getCurrentUser')) {
            $user = $authService->getCurrentUser();
        } elseif (function_exists('current_user')) {
            $user = current_user();
        }

        if (!$user || empty($user['auth_user_id'])) {
            return array(
                'actor_id' => 0,
                'source' => 'none',
                'reason' => 'No authenticated user available.',
                'preferences' => self::getPreferences(),
            );
        }

        $is_admin = !empty($user['is_admin']);
        $allowed = array();
        if ($actorService && is_object($actorService) && method_exists($actorService, 'getActorsUserCanActAs')) {
            $allowed = $actorService->getActorsUserCanActAs((int) $user['auth_user_id'], $is_admin);
        }
        if (!is_array($allowed)) {
            $allowed = array();
        }

        $allowed_ids = array();
        foreach ($allowed as $a) {
            if (isset($a['actor_id'])) {
                $allowed_ids[(int) $a['actor_id']] = true;
            }
        }

        $prefs = self::getPreferences();
        $active_actor_id = 0;
        if ($authService && is_object($authService) && method_exists($authService, 'getActiveActorId')) {
            $active_actor_id = (int) $authService->getActiveActorId();
        }
        $current_user_actor_id = isset($user['actor_id']) ? (int) $user['actor_id'] : 0;

        $candidate_ids = array();
        $candidate_sources = array();
        if ($active_actor_id > 0) {
            if (!isset($candidate_sources[$active_actor_id])) {
                $candidate_ids[] = $active_actor_id;
            }
            $candidate_sources[$active_actor_id] = 'active_actor';
        }
        if (!empty($prefs['preferred_actor_id'])) {
            $preferred_actor_id = (int) $prefs['preferred_actor_id'];
            if (!isset($candidate_sources[$preferred_actor_id])) {
                $candidate_ids[] = $preferred_actor_id;
            }
            $candidate_sources[$preferred_actor_id] = 'preferred_actor';
        }
        if ($current_user_actor_id > 0) {
            if (!isset($candidate_sources[$current_user_actor_id])) {
                $candidate_ids[] = $current_user_actor_id;
            }
            $candidate_sources[$current_user_actor_id] = 'current_user_actor';
        }

        $actor_rows = self::fetchAllowedActorRows($db, $table_prefix, array_keys($allowed_ids));
        $actor_rows_by_id = array();
        foreach ($actor_rows as $row) {
            $actor_rows_by_id[(int) $row['actor_id']] = $row;
        }

        $preferred_department_id = !empty($prefs['preferred_department_id']) ? (int) $prefs['preferred_department_id'] : 0;

        // Candidate pass: explicit list first, then filtered allowed list.
        foreach ($candidate_ids as $candidate_id) {
            $candidate_id = (int) $candidate_id;
            if ($candidate_id <= 0 || !isset($allowed_ids[$candidate_id])) {
                continue;
            }
            if ($preferred_department_id > 0 && isset($actor_rows_by_id[$candidate_id])) {
                $dept = isset($actor_rows_by_id[$candidate_id]['department_id']) ? (int) $actor_rows_by_id[$candidate_id]['department_id'] : 0;
                if ($dept !== $preferred_department_id) {
                    continue;
                }
            }
            if (self::passesChannelGuard($db, $table_prefix, $candidate_id, $channel_id, $is_admin)) {
                if ($authService && is_object($authService) && method_exists($authService, 'setActiveActorId')) {
                    $authService->setActiveActorId($candidate_id);
                }
                $candidate_source = isset($candidate_sources[$candidate_id]) ? $candidate_sources[$candidate_id] : 'candidate';
                $reason = 'Resolved from active actor or explicit preference candidate.';
                if ($candidate_source === 'active_actor') {
                    $reason = 'Resolved from active session actor.';
                } elseif ($candidate_source === 'preferred_actor') {
                    $reason = 'Resolved from preferred actor override.';
                } elseif ($candidate_source === 'current_user_actor') {
                    $reason = 'Resolved from authenticated user default actor.';
                }
                return array(
                    'actor_id' => $candidate_id,
                    'source' => $candidate_source,
                    'reason' => $reason,
                    'preferences' => $prefs,
                );
            }
        }

        // Fallback by department preference within allowed set.
        if ($preferred_department_id > 0) {
            foreach ($actor_rows as $row) {
                $candidate_id = (int) $row['actor_id'];
                $dept = isset($row['department_id']) ? (int) $row['department_id'] : 0;
                if ($dept !== $preferred_department_id) {
                    continue;
                }
                if (self::passesChannelGuard($db, $table_prefix, $candidate_id, $channel_id, $is_admin)) {
                    if ($authService && is_object($authService) && method_exists($authService, 'setActiveActorId')) {
                        $authService->setActiveActorId($candidate_id);
                    }
                    return array(
                        'actor_id' => $candidate_id,
                        'source' => 'department_fallback',
                        'reason' => 'Resolved from preferred department fallback.',
                        'preferences' => $prefs,
                    );
                }
            }
        }

        // Last fallback: first allowed actor with channel access.
        foreach ($allowed_ids as $candidate_id => $ok) {
            if (self::passesChannelGuard($db, $table_prefix, (int) $candidate_id, $channel_id, $is_admin)) {
                if ($authService && is_object($authService) && method_exists($authService, 'setActiveActorId')) {
                    $authService->setActiveActorId((int) $candidate_id);
                }
                return array(
                    'actor_id' => (int) $candidate_id,
                    'source' => 'allowed_fallback',
                    'reason' => 'Resolved from first allowed actor fallback.',
                    'preferences' => $prefs,
                );
            }
        }

        return array(
            'actor_id' => 0,
            'source' => 'none',
            'reason' => 'No allowed actor can access requested channel.',
            'preferences' => $prefs,
        );
    }

    /**
     * Persist chat identity preferences to session.
     *
     * @param int $preferred_actor_id
    * @param int $preferred_agent_id Advisory behavior preference only; not a direct actor selector.
     * @param int $preferred_department_id
     * @return array
     */
    public static function setPreferences($preferred_actor_id, $preferred_agent_id, $preferred_department_id)
    {
        if (function_exists('session_status')) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        } elseif (session_id() === '') {
            session_start();
        }

        $_SESSION['chat_identity_preferences'] = array(
            'preferred_actor_id' => max(0, (int) $preferred_actor_id),
            'preferred_agent_id' => max(0, (int) $preferred_agent_id),
            'preferred_department_id' => max(0, (int) $preferred_department_id),
        );

        return $_SESSION['chat_identity_preferences'];
    }

    /**
    * Read chat identity preferences from session.
    * preferred_agent_id is retained for UI state and future agent binding, but actor
    * resolution remains actor-first.
     *
     * @return array
     */
    public static function getPreferences()
    {
        if (function_exists('session_status')) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        } elseif (session_id() === '') {
            session_start();
        }
        $defaults = array(
            'preferred_actor_id' => 0,
            'preferred_agent_id' => 0,
            'preferred_department_id' => 0,
        );
        if (!isset($_SESSION['chat_identity_preferences']) || !is_array($_SESSION['chat_identity_preferences'])) {
            return $defaults;
        }
        $p = $_SESSION['chat_identity_preferences'];
        return array(
            'preferred_actor_id' => isset($p['preferred_actor_id']) ? max(0, (int) $p['preferred_actor_id']) : 0,
            'preferred_agent_id' => isset($p['preferred_agent_id']) ? max(0, (int) $p['preferred_agent_id']) : 0,
            'preferred_department_id' => isset($p['preferred_department_id']) ? max(0, (int) $p['preferred_department_id']) : 0,
        );
    }

    private static function fetchAllowedActorRows($db, $table_prefix, $actor_ids)
    {
        $rows = array();
        if (empty($actor_ids)) {
            return $rows;
        }
        $placeholders = array();
        $params = array();
        $i = 0;
        foreach ($actor_ids as $actor_id) {
            $key = 'id' . $i;
            $placeholders[] = ':' . $key;
            $params[$key] = (int) $actor_id;
            $i++;
        }
        $t = $db->quoteIdentifier($table_prefix . 'actors');
        $sql = "SELECT actor_id, actor_type, name, actor_name, department_id, is_agent
                FROM {$t}
                WHERE actor_id IN (" . implode(',', $placeholders) . ")
                  AND (is_deleted = 0 OR is_deleted IS NULL)";
        return $db->fetchAll($sql, $params);
    }

    private static function passesChannelGuard($db, $table_prefix, $actor_id, $channel_id, $is_admin)
    {
        $actor_id = (int) $actor_id;
        $channel_id = (int) $channel_id;
        if ($actor_id <= 0) {
            return false;
        }
        if ($channel_id <= 0 || $is_admin) {
            return true;
        }
        $t = $db->quoteIdentifier($table_prefix . 'actor_channels');
        $row = $db->fetchRow(
            "SELECT 1 FROM {$t} WHERE actor_id = :actor_id AND channel_id = :channel_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('actor_id' => $actor_id, 'channel_id' => $channel_id)
        );
        return is_array($row);
    }
}

