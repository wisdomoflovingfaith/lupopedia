<?php
# file: Context Resolver — session: L-LUPO-ANTIGRAVITY — delegation: antigravity:cursor:captain — web_path: http://www.lupopedia.com/docs/api/ContextResolver
# ---
# flare.headers:
#   flare.version: "1.0"
#   flare.schema: "documentation"
#   file_path_from_root: "lupo-includes/classes/ContextResolver.php"
#   last_updated_utc: "20260307"
#   system_version: "4.0.65"
#   actor_name: "antigravity"
#   artifact_type: "code"
#   purpose: "Resolves full Lupopedia context (CLI/agents) with registry and session-file primacy (v4.0.65 update)."
# ---
/**
 * ContextResolver — Resolves full Lupopedia execution context for CLI/agents.
 * Priority: session.md (first-class) → enrich from lupo_sessions (DB) → enrich from registry/actor service → defaults.
 *
 * @package Lupopedia
 * @version 4.0.65
 */

class ContextResolver
{
    /**
     * Resolve execution context including dialog headers.
     * Keys: actor_name, actor_id, actor_type, actor_nature, agent_name, human_actor_name, human_actor_id,
     * paired_actor_id, paired_actor_name, session_mode, department_id, channel_id, thread_id,
     * federation_node_id, workspace, session_id, context_source.
     *
     * @param object|null $db PDO_DB or null when database unavailable
     * @param string $table_prefix Table prefix (e.g. lupo_)
     * @param string $state_file Path to .lupo_actor file
     * @param string $base_path ABSPATH (project root)
     * @return array Full context with dialog headers
     */
    public static function resolve($db, $table_prefix, $state_file, $base_path)
    {
        $actors_dir = defined('LUPO_ACTORS_DIR') ? LUPO_ACTORS_DIR : 'lupo-actors';
        $session_md = $base_path . (defined('LUPO_DATABASE_DIR') ? LUPO_DATABASE_DIR : 'lupo-database') . '/session.md';

        // A — Session file first (first-class source)
        $ctx = self::_resolveFromSessionMd($session_md, $base_path, $actors_dir);
        if ($ctx !== null) {
            $ctx['workspace'] = '/' . trim($actors_dir, '/') . '/' . $ctx['actor_name'] . '/';
            $ctx['context_source'] = 'session.md';
            if ($db && $table_prefix !== '') {
                self::_enrichFromSessionTable($ctx, $db, $table_prefix);
            }
            self::_enrichActorMeta($ctx, $base_path, $db, $table_prefix);
            if (isset($ctx['human_actor_name']) && (isset($ctx['paired_actor_id']) && (int) $ctx['paired_actor_id'] > 0)) {
                $ctx['paired_actor_name'] = $ctx['human_actor_name'];
            } else {
                $ctx['paired_actor_name'] = isset($ctx['paired_actor_name']) ? $ctx['paired_actor_name'] : '';
            }
            if ($ctx['context_source'] === 'session.md' && ($db || self::_usedRegistryForEnrichment($ctx))) {
                $ctx['context_source'] = 'session.md + registry';
            }
            $ctx['source'] = $ctx['context_source'];
            return $ctx;
        }

        // B — Database session (when session.md absent or empty)
        if ($db) {
            $ctx = self::_resolveFromSessionTable($db, $table_prefix, $state_file, $base_path);
            if ($ctx !== null) {
                $ctx['workspace'] = '/' . trim($actors_dir, '/') . '/' . $ctx['actor_name'] . '/';
                $ctx['context_source'] = 'lupo_sessions';
                $ctx['source'] = $ctx['context_source'];
                self::_enrichActorMeta($ctx, $base_path, $db, $table_prefix);
                if (isset($ctx['human_actor_name']) && (int) $ctx['paired_actor_id'] > 0) {
                    $ctx['paired_actor_name'] = $ctx['human_actor_name'];
                } else {
                    $ctx['paired_actor_name'] = isset($ctx['paired_actor_name']) ? $ctx['paired_actor_name'] : '';
                }
                return $ctx;
            }
        }

        // D — Defaults
        $ctx = array(
            'actor_name' => 'system',
            'actor_id' => 0,
            'actor_type' => 'system',
            'actor_nature' => 'system',
            'agent_name' => 'none',
            'human_actor_name' => 'none',
            'human_actor_id' => 0,
            'paired_actor_id' => 0,
            'paired_actor_name' => '',
            'session_mode' => 'system',
            'department_id' => 0,
            'channel_id' => 0,
            'thread_id' => 0,
            'federation_node_id' => 0,
            'workspace' => '/' . trim($actors_dir, '/') . '/system/',
            'session_id' => '',
            'context_source' => 'default',
            'source' => 'default'
        );
        return $ctx;
    }

    /**
     * Enrich context from lupo_sessions when we have actor_id or session_id (fill missing session_id, channel_id, federation_node_id).
     *
     * @param array $ctx Context (modified in place)
     * @param object $db PDO_DB
     * @param string $table_prefix
     */
    protected static function _enrichFromSessionTable(&$ctx, $db, $table_prefix)
    {
        $t = $table_prefix . 'sessions';
        try {
            if (isset($ctx['session_id']) && $ctx['session_id'] !== '') {
                $stmt = $db->prepare("SELECT session_id, actor_id, actor_name, channel_id, federation_node_id FROM {$t} WHERE session_id = :sid AND is_deleted = 0 LIMIT 1");
                $stmt->execute(array('sid' => $ctx['session_id']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
            } elseif (isset($ctx['actor_id']) && (int) $ctx['actor_id'] > 0) {
                $stmt = $db->prepare("SELECT session_id, actor_id, actor_name, channel_id, federation_node_id FROM {$t} WHERE actor_id = :aid AND is_deleted = 0 ORDER BY last_seen_ymdhis DESC LIMIT 1");
                $stmt->execute(array('aid' => (int) $ctx['actor_id']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $row = null;
            }
            if ($row) {
                $db_actor_name = isset($row['actor_name']) && $row['actor_name'] !== '' ? trim($row['actor_name']) : '';
                $db_actor_id = isset($row['actor_id']) ? (int) $row['actor_id'] : 0;
                $file_actor_name = isset($ctx['actor_name']) ? trim($ctx['actor_name']) : '';
                $file_actor_id = isset($ctx['actor_id']) ? (int) $ctx['actor_id'] : 0;
                if ($db_actor_name !== '' && $file_actor_name !== '' && ($db_actor_name !== $file_actor_name || $db_actor_id !== $file_actor_id)) {
                    error_log('WARNING: Session file and DB session mismatch. session.md: actor_name=' . $file_actor_name . ', actor_id=' . $file_actor_id . '; DB: actor_name=' . $db_actor_name . ', actor_id=' . $db_actor_id . '. Using DB as canonical.');
                    $conflicts = array();
                    if ($file_actor_name !== $db_actor_name) {
                        $conflicts[] = array('field' => 'actor_name', 'file_value' => $file_actor_name, 'db_value' => $db_actor_name, 'resolution' => 'database_wins');
                    }
                    if ((int) $file_actor_id !== (int) $db_actor_id) {
                        $conflicts[] = array('field' => 'actor_id', 'file_value' => $file_actor_id, 'db_value' => $db_actor_id, 'resolution' => 'database_wins');
                    }
                    $ctx['conflicts'] = $conflicts;
                    $ctx['actor_name'] = $db_actor_name;
                    $ctx['actor_id'] = $db_actor_id;
                    $ctx['context_source'] = 'lupo_sessions (session.md ignored due to conflict)';
                }
                if (empty($ctx['session_id'])) {
                    $ctx['session_id'] = $row['session_id'];
                }
                if (!isset($ctx['channel_id']) || $ctx['channel_id'] === 0) {
                    $ctx['channel_id'] = (int) $row['channel_id'];
                }
                if (!isset($ctx['federation_node_id'])) {
                    $ctx['federation_node_id'] = (int) $row['federation_node_id'];
                }
            }
        } catch (Exception $e) {
            // ignore
        }
    }

    /**
     * Whether we used registry for enrichment (simplified: if we have actor_type from registry we consider it used).
     */
    protected static function _usedRegistryForEnrichment($ctx)
    {
        return isset($ctx['actor_type']) && $ctx['actor_type'] !== 'system';
    }

    /**
     * Enrich context with actor_type, paired_actor_id, session_mode, human identity, active agent (dual-identity).
     * Human identity is derived from lupo_actors.paired_actor_id when DB is available; otherwise from registry.
     *
     * @param array $ctx Context array (modified in place)
     * @param string $base_path ABSPATH
     * @param object|null $db PDO_DB or null
     * @param string $table_prefix Table prefix (e.g. lupo_)
     */
    protected static function _enrichActorMeta(&$ctx, $base_path, $db = null, $table_prefix = '')
    {
        $reg = self::_loadRegistry($base_path);
        $actor_name = isset($ctx['actor_name']) ? $ctx['actor_name'] : 'system';
        $actor_id = isset($ctx['actor_id']) ? (int) $ctx['actor_id'] : 0;

        $actor_type = 'system';
        $paired_actor_id = 0;
        if (isset($ctx['actor_type']) && $ctx['actor_type'] !== '') {
            $actor_type = $ctx['actor_type'];
        }

        if ($db && $table_prefix !== '') {
            $row = self::_getActorTypeAndPairedFromDb($db, $table_prefix, $actor_id, $actor_name);
            if ($row) {
                $actor_type = isset($row['actor_type']) ? $row['actor_type'] : 'system';
                $paired_actor_id = isset($row['paired_actor_id']) ? (int) $row['paired_actor_id'] : 0;
                if (!isset($ctx['workspace_path']) && isset($row['workspace_path'])) {
                    $ctx['workspace_path'] = $row['workspace_path'];
                }
                if (!isset($ctx['php_namespace']) && isset($row['php_namespace'])) {
                    $ctx['php_namespace'] = $row['php_namespace'];
                }
            }
        }
        if (isset($ctx['paired_actor_id'])) {
            $paired_actor_id = (int) $ctx['paired_actor_id'];
        }
        if ($actor_type === 'system' && $reg && isset($reg['actors'][$actor_name])) {
            $a = $reg['actors'][$actor_name];
            $actor_type = isset($a['type']) ? $a['type'] : 'system';
            if ($actor_type === 'agent' && $actor_id >= 1000 && $actor_id <= 1010) {
                $actor_type = 'ide_agent';
            }
            if (!isset($row) && isset($a['paired_actor_id'])) {
                $paired_actor_id = (int) $a['paired_actor_id'];
            }
            if (!isset($ctx['workspace_path']) && isset($a['workspace_path'])) {
                $ctx['workspace_path'] = $a['workspace_path'];
            }
            if (!isset($ctx['php_namespace']) && isset($a['php_namespace'])) {
                $ctx['php_namespace'] = $a['php_namespace'];
            }
            if ($paired_actor_id === 0 && ($actor_type === 'ide_agent' || $actor_type === 'agent')) {
                $paired_actor_id = 10000;
            }
        }
        // Session file may set human_actor_name; resolve to paired_actor_id so hybrid mode works
        $session_human = isset($ctx['human_actor_name']) && $ctx['human_actor_name'] !== '' && $ctx['human_actor_name'] !== 'none' ? $ctx['human_actor_name'] : '';
        if ($session_human !== '' && ($actor_type === 'agent' || $actor_type === 'ide_agent') && $paired_actor_id === 0) {
            $resolved_id = self::_resolveActorIdFromRegistry($base_path, $session_human);
            if ($resolved_id > 0) {
                $paired_actor_id = $resolved_id;
            }
        }

        $ctx['actor_type'] = $actor_type;
        $ctx['paired_actor_id'] = $paired_actor_id;
        $ctx['actor_nature'] = ($actor_type === 'ide_agent') ? 'delegated_agent' : $actor_type;

        // Apply workspace override if stored in DB/Registry
        if (isset($ctx['workspace_path']) && $ctx['workspace_path'] !== '') {
            $ctx['workspace'] = '/' . trim($ctx['workspace_path'], '/') . '/';
        }

        if ($actor_type === 'human') {
            $ctx['human_actor_name'] = $actor_name;
            $ctx['human_actor_id'] = $actor_id;
            $ctx['agent_name'] = 'none';
            $ctx['session_mode'] = 'human_direct';
        } elseif ($actor_type === 'agent' || $actor_type === 'ide_agent') {
            $ctx['agent_name'] = $actor_name;
            if ($paired_actor_id > 0) {
                if ($session_human !== '') {
                    $ctx['human_actor_name'] = $session_human;
                    $ctx['human_actor_id'] = self::_resolveActorIdFromRegistry($base_path, $session_human);
                    if ($ctx['human_actor_id'] === 0 && $db && $table_prefix !== '') {
                        $ctx['human_actor_id'] = $paired_actor_id;
                    }
                } else {
                    $ctx['human_actor_name'] = self::_getHumanActorNameFromRegistry($reg, $paired_actor_id);
                    $ctx['human_actor_id'] = self::_resolveActorIdFromRegistry($base_path, $ctx['human_actor_name']);
                    if ($ctx['human_actor_name'] === 'system' && $db && $table_prefix !== '') {
                        $ctx['human_actor_name'] = self::_getActorNameById($db, $table_prefix, $paired_actor_id);
                        $ctx['human_actor_id'] = $paired_actor_id;
                    }
                    if ($ctx['human_actor_id'] === 0 && $ctx['human_actor_name'] !== 'none') {
                        $ctx['human_actor_id'] = $paired_actor_id;
                    }
                }
                $ctx['session_mode'] = 'hybrid';
            } else {
                $ctx['human_actor_name'] = 'none';
                $ctx['human_actor_id'] = 0;
                $ctx['session_mode'] = 'autonomous_agent';
            }
        } else {
            $ctx['human_actor_name'] = 'none';
            $ctx['human_actor_id'] = 0;
            $ctx['agent_name'] = 'none';
            $ctx['session_mode'] = 'system';
        }

        if (!isset($ctx['department_id'])) {
            $ctx['department_id'] = 0;
        }
        if (!isset($ctx['thread_id'])) {
            $ctx['thread_id'] = 0;
        }
    }

    /**
     * Get actor_type and paired_actor_id from lupo_actors (by actor_id or actor_name).
     *
     * @param object $db PDO_DB
     * @param string $table_prefix
     * @param int $actor_id
     * @param string $actor_name
     * @return array|null Row with actor_type, paired_actor_id or null
     */
    protected static function _getActorTypeAndPairedFromDb($db, $table_prefix, $actor_id, $actor_name)
    {
        $t = $table_prefix . 'actors';
        try {
            if ($actor_name !== '' && $actor_name !== 'system') {
                $stmt = $db->prepare("SELECT actor_name, actor_type, paired_actor_id, workspace_path, php_namespace FROM {$t} WHERE actor_name = :name AND is_deleted = 0 LIMIT 1");
                $stmt->execute(array('name' => $actor_name));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    return $row;
                }
            }
            if ($actor_id > 0) {
                $stmt = $db->prepare("SELECT actor_name, actor_type, paired_actor_id, workspace_path, php_namespace FROM {$t} WHERE actor_id = :id AND is_deleted = 0 LIMIT 1");
                $stmt->execute(array('id' => $actor_id));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    return $row;
                }
            }
        } catch (Exception $e) {
            return null;
        }
        return null;
    }

    /**
     * Resolve actor_name from lupo_actors by actor_id (when registry does not have the human).
     *
     * @param object|null $db PDO_DB
     * @param string $table_prefix
     * @param int $actor_id
     * @return string actor_name or 'none'
     */
    protected static function _getActorNameById($db, $table_prefix, $actor_id)
    {
        if (!$db || $table_prefix === '' || $actor_id <= 0) {
            return 'none';
        }
        $t = $table_prefix . 'actors';
        try {
            $stmt = $db->prepare("SELECT actor_name, name FROM {$t} WHERE actor_id = :id AND is_deleted = 0 LIMIT 1");
            $stmt->execute(array('id' => $actor_id));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['actor_name']) && $row['actor_name'] !== '') {
                return $row['actor_name'];
            }
            if ($row && isset($row['name']) && $row['name'] !== '') {
                return $row['name'];
            }
        } catch (Exception $e) {
            return 'none';
        }
        return 'none';
    }

    /**
     * Load actor registry JSON.
     *
     * @param string $base_path ABSPATH
     * @return array|null Registry array or null
     */
    protected static function _loadRegistry($base_path)
    {
        $reg = $base_path . 'lupo-database/lupopedia/actors/registry.json';
        if (!file_exists($reg) || !is_readable($reg)) {
            return null;
        }
        $json = json_decode(file_get_contents($reg), true);
        return is_array($json) ? $json : null;
    }

    /**
     * Resolve human actor name by actor_id (e.g. 10000 = root).
     *
     * @param array $reg Registry from _loadRegistry
     * @param int $human_actor_id Default human actor ID (e.g. 10000)
     * @return string Actor name or 'system'
     */
    protected static function _getHumanActorNameFromRegistry($reg, $human_actor_id = 10000)
    {
        if (!is_array($reg) || !isset($reg['actors'])) {
            return 'system';
        }
        foreach ($reg['actors'] as $name => $a) {
            if (isset($a['actor_id']) && (int) $a['actor_id'] === (int) $human_actor_id) {
                return $name;
            }
        }
        return 'system';
    }

    /**
     * Try to get context from lupo_sessions using .lupo_actor (actor_id or session_id).
     * Session table does not have department_id or thread_id; they default to 0.
     *
     * @param object $db PDO_DB
     * @param string $table_prefix
     * @param string $state_file
     * @param string $base_path ABSPATH
     * @return array|null Context or null
     */
    protected static function _resolveFromSessionTable($db, $table_prefix, $state_file, $base_path)
    {
        $local = self::_readStateFile($state_file);
        $t = $table_prefix . 'sessions';

        $base_ctx = array(
            'department_id' => 0,
            'thread_id' => 0,
            'workspace' => ''
        );

        try {
            if ($local && isset($local['session_id']) && $local['session_id'] !== '') {
                $stmt = $db->prepare("SELECT session_id, actor_id, actor_name, channel_id, federation_node_id FROM {$t} WHERE session_id = :sid AND is_deleted = 0 LIMIT 1");
                $stmt->execute(array('sid' => $local['session_id']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    $ctx = array_merge($base_ctx, array(
                        'actor_name' => isset($row['actor_name']) && $row['actor_name'] !== '' ? $row['actor_name'] : 'system',
                        'actor_id' => (int) $row['actor_id'],
                        'channel_id' => (int) $row['channel_id'],
                        'federation_node_id' => (int) $row['federation_node_id'],
                        'session_id' => $row['session_id']
                    ));
                    return $ctx;
                }
            }
            if ($local && isset($local['actor_id'])) {
                $stmt = $db->prepare("SELECT session_id, actor_id, actor_name, channel_id, federation_node_id FROM {$t} WHERE actor_id = :aid AND is_deleted = 0 ORDER BY last_seen_ymdhis DESC LIMIT 1");
                $stmt->execute(array('aid' => (int) $local['actor_id']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    $name = isset($row['actor_name']) && $row['actor_name'] !== '' ? $row['actor_name'] : (isset($local['name']) ? $local['name'] : 'system');
                    $ctx = array_merge($base_ctx, array(
                        'actor_name' => $name,
                        'actor_id' => (int) $row['actor_id'],
                        'channel_id' => (int) $row['channel_id'],
                        'federation_node_id' => (int) $row['federation_node_id'],
                        'session_id' => $row['session_id']
                    ));
                    return $ctx;
                }
            }
        } catch (Exception $e) {
            return null;
        }
        return null;
    }

    /**
     * Read .lupo_actor JSON state file.
     *
     * @param string $state_file
     * @return array|null Decoded array or null
     */
    protected static function _readStateFile($state_file)
    {
        if (!file_exists($state_file) || !is_readable($state_file)) {
            return null;
        }
        $raw = file_get_contents($state_file);
        if ($raw === false) {
            return null;
        }
        $data = json_decode($raw, true);
        return is_array($data) ? $data : null;
    }

    /**
     * Try to get context from lupo-database/session.md (key: value or YAML-like).
     *
     * @param string $path Path to session.md
     * @param string $base_path ABSPATH
     * @param string $actors_dir LUPO_ACTORS_DIR
     * @return array|null Context or null
     */
    protected static function _resolveFromSessionMd($path, $base_path, $actors_dir)
    {
        if (!file_exists($path) || !is_readable($path)) {
            return null;
        }
        $raw = file_get_contents($path);
        if ($raw === false) {
            return null;
        }
        $actor_name = '';
        $channel_id = 0;
        $federation_node_id = 0;
        $session_id = '';
        $actor_id = 0;
        $department_id = 0;
        $thread_id = 0;
        $agent_name = '';
        $actor_type = '';
        $actor_nature = '';
        $human_actor_name = '';
        $paired_actor_id = 0;
        $paired_actor_name = '';
        $session_mode = '';

        // Parse key: value lines (in body or inside --- frontmatter)
        $lines = preg_match('/^---\s*\n(.*?)\n---/s', $raw, $m) ? explode("\n", $m[1]) : explode("\n", $raw);
        foreach ($lines as $line) {
            if (preg_match('/^([a-z_]+):\s*(.*)$/', trim($line), $kv)) {
                $k = $kv[1];
                $v = trim($kv[2], " \t\"'");
                if ($k === 'actor_name') {
                    $actor_name = $v;
                } elseif ($k === 'channel_id') {
                    $channel_id = (int) $v;
                } elseif ($k === 'federation_node_id') {
                    $federation_node_id = (int) $v;
                } elseif ($k === 'session_id') {
                    $session_id = $v;
                } elseif ($k === 'actor_id') {
                    $actor_id = (int) $v;
                } elseif ($k === 'department_id') {
                    $department_id = (int) $v;
                } elseif ($k === 'thread_id') {
                    $thread_id = (int) $v;
                } elseif ($k === 'agent_name') {
                    $agent_name = $v;
                } elseif ($k === 'actor_type') {
                    $actor_type = $v;
                } elseif ($k === 'actor_nature') {
                    $actor_nature = $v;
                } elseif ($k === 'human_actor_name') {
                    $human_actor_name = $v;
                } elseif ($k === 'paired_actor_id') {
                    $paired_actor_id = (int) $v;
                } elseif ($k === 'session_mode') {
                    $session_mode = $v;
                } elseif ($k === 'paired_actor_name') {
                    $paired_actor_name = $v;
                }
            }
        }

        if ($actor_name === '' && $session_id === '' && $channel_id === 0) {
            return null;
        }
        if ($actor_name === '') {
            $actor_name = 'system';
        }
        if ($actor_id === 0) {
            $actor_id = self::_resolveActorIdFromRegistry($base_path, $actor_name);
        }
        if ($agent_name === '') {
            $agent_name = $actor_name;
        }
        $workspace = '/' . trim($actors_dir, '/') . '/' . $actor_name . '/';
        $ctx = array(
            'actor_name' => $actor_name,
            'actor_id' => $actor_id,
            'channel_id' => $channel_id,
            'federation_node_id' => $federation_node_id,
            'workspace' => $workspace,
            'session_id' => $session_id,
            'department_id' => $department_id,
            'thread_id' => $thread_id,
            'agent_name' => $agent_name,
            'source' => 'session.md'
        );
        if ($actor_type !== '') {
            $ctx['actor_type'] = $actor_type;
        }
        if ($actor_nature !== '') {
            $ctx['actor_nature'] = $actor_nature;
        }
        if ($human_actor_name !== '') {
            $ctx['human_actor_name'] = $human_actor_name;
        }
        if ($paired_actor_id > 0) {
            $ctx['paired_actor_id'] = $paired_actor_id;
        }
        if ($session_mode !== '') {
            $ctx['session_mode'] = $session_mode;
        }
        if ($paired_actor_name !== '') {
            $ctx['paired_actor_name'] = $paired_actor_name;
        }
        return $ctx;
    }

    /**
     * Resolve actor_id from registry by actor_name.
     *
     * @param string $base_path ABSPATH
     * @param string $actor_name
     * @return int
     */
    protected static function _resolveActorIdFromRegistry($base_path, $actor_name)
    {
        $reg = $base_path . 'lupo-database/lupopedia/actors/registry.json';
        if (!file_exists($reg) || !is_readable($reg)) {
            return 0;
        }
        $json = json_decode(file_get_contents($reg), true);
        if (!is_array($json) || !isset($json['actors'][$actor_name]['actor_id'])) {
            return 0;
        }
        return (int) $json['actors'][$actor_name]['actor_id'];
    }
}
