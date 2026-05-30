<?php
/**
 * MemoryGraph.php — Graph traversal for memory compaction system
 * DRAFT — Not final
 *
 * PHP 7.4+ runtime; PHP 5.6 source-compatible (no str_ends_with, no named args,
 * no union types — per PRD 00 §4 / lupo-rules/root/php-7-4-compatibility.md).
 */

class MemoryGraph {
    private $db;
    private $projectRoot;
    /** @var string database|filesystem|auto */
    private $source = 'auto';
    /** @var array|null Cached session data */
    private $session = null;

    public function __construct($db = null, $source = 'auto') {
        $this->db = $db;
        $this->projectRoot = dirname(__DIR__, 2);
        $this->setSource($source);
        if ($this->db === null && class_exists('DatabaseFactory')) {
            try {
                $this->db = DatabaseFactory::getConnection();
            } catch (Exception $e) {
                $this->db = null;
            }
        }
    }

    /**
     * @param string $source database|filesystem|auto
     * @return void
     */
    public function setSource($source) {
        $allowed = array('database', 'filesystem', 'auto');
        if (in_array($source, $allowed, true)) {
            $this->source = $source;
            return;
        }
        $this->source = 'auto';
    }

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Load memory context starting from a ref.
     *
     * Resolution order when $startRef is null:
     *   1. session.json active_memory_ref
     *   2. Most-recent .toon in lupo-memory/YYYY/MM/ (current month)
     *   3. getChannelContext() — never returns empty array
     *
     * @param string|null $startRef  Path like "lupo-memory/2026/04/M-foo.toon",
     *                               bare ID like "M-foo-20260409", or null.
     * @param int|null    $maxDepth  Override channel config max_depth.
     * @return array
     */
    public function loadContext($startRef = null, $maxDepth = null) {
        $session = $this->getSession();

        // 1. Resolve startRef
        if ($startRef === null) {
            $startRef = isset($session['active_memory_ref']) ? $session['active_memory_ref'] : null;
        }

        // 2. Fallback: scan current month for most-recent .toon
        if (empty($startRef)) {
            $startRef = $this->findLatestToon();
            if ($startRef !== null) {
                fwrite(STDERR, "[MemoryGraph] active_memory_ref is null. Using latest toon: {$startRef}\n");
            }
        }

        // 3. Fallback: return channel context — never silent empty
        if (empty($startRef)) {
            return $this->getChannelContext();
        }

        // 4. Resolve max_depth from channel config if not overridden
        if ($maxDepth === null) {
            $maxDepth = $this->getMaxDepth($session);
        }

        // 5. Load exclude patterns from channel config
        $excludePatterns = $this->getExcludePatterns($session);

        // 6. Traverse
        $visited = array();
        $graph   = array();
        $this->traverse($startRef, 0, $maxDepth, $excludePatterns, $visited, $graph);

        return $graph;
    }

    /**
     * Set active_memory_ref in both channel config.json and session.json.
     * Called by agents after writing a new .toon file.
     *
     * @param string $memRef  Path relative to project root.
     * @return bool
     */
    public function setActiveMemoryRef($memRef) {
        $session = $this->getSession();
        $ck      = isset($session['active_channel_key']) ? $session['active_channel_key'] : '';
        $sl      = isset($session['active_slug'])        ? $session['active_slug']        : '';

        // Update channel config
        if ($ck && $sl) {
            $configPath = $this->projectRoot . '/lupo-channels/0/' . $ck . '/' . $sl . '/config.json';
            if (file_exists($configPath)) {
                $config = json_decode(file_get_contents($configPath), true);
                if (!isset($config['memory_follow_rules'])) {
                    $config['memory_follow_rules'] = array();
                }
                $config['memory_follow_rules']['active_memory_ref'] = $memRef;
                file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT));
            }
        }

        // Update session.json
        $sessionPath = $this->projectRoot . '/lupo-config/session.json';
        if (file_exists($sessionPath)) {
            $s = json_decode(file_get_contents($sessionPath), true);
            $s['active_memory_ref'] = $memRef;
            file_put_contents($sessionPath, json_encode($s, JSON_PRETTY_PRINT));
            $this->session = $s; // Invalidate cache
        }

        return true;
    }

    // -------------------------------------------------------------------------
    // CLI entry point
    // -------------------------------------------------------------------------

    public static function cliLoadContext() {
        $graph   = new self();
        $context = $graph->loadContext();
        echo json_encode($context, JSON_PRETTY_PRINT);
    }

    // -------------------------------------------------------------------------
    // Private: traversal
    // -------------------------------------------------------------------------

    private function traverse($ref, $depth, $maxDepth, $excludePatterns, &$visited, &$graph) {
        if ($depth > $maxDepth || in_array($ref, $visited, true)) {
            return;
        }

        // Check exclude patterns before resolving
        if ($this->matchesExclude($ref, $excludePatterns)) {
            return;
        }

        $visited[] = $ref;
        $data      = $this->resolveRef($ref);

        if ($data === null) {
            return;
        }

        $graph[] = $data;

        // Follow edges — support both header_json_v1 (edges.outbound[]) and toon_v1 (edges[])
        $edges = array();
        if (isset($data['edges']['outbound']) && is_array($data['edges']['outbound'])) {
            $edges = $data['edges']['outbound'];
        } elseif (isset($data['edges']) && is_array($data['edges'])) {
            $edges = $data['edges'];
        }

        foreach ($edges as $edge) {
            if (!isset($edge['to'])) {
                continue;
            }
            $to = (string) $edge['to'];
            // Skip non-file refs like ACTOR:116, CHANNEL:..., TASK:...
            if (strpos($to, ':') !== false && !$this->looksLikePath($to)) {
                continue;
            }
            $this->traverse($to, $depth + 1, $maxDepth, $excludePatterns, $visited, $graph);
        }
    }

    // -------------------------------------------------------------------------
    // Private: ref resolution
    // -------------------------------------------------------------------------

    /**
     * Resolve a ref string to a decoded array, or null on failure.
     * Handles: full project-relative paths, bare toon IDs, FILE: prefixes.
     */
    private function resolveRef($ref) {
        if ($this->source === 'database' || $this->source === 'auto') {
            $dbNode = $this->loadFromDatabase($ref);
            if (is_array($dbNode)) {
                return $dbNode;
            }
            if ($this->source === 'database') {
                return null;
            }
        }

        // Strip FILE: prefix
        if (strncmp($ref, 'FILE:', 5) === 0) {
            $ref = substr($ref, 5);
        }

        $root = $this->projectRoot;

        // Full project-relative path
        $absPath = $root . '/' . ltrim($ref, '/\\');
        if (file_exists($absPath)) {
            return $this->decodeJsonFile($absPath);
        }

        // Add .toon extension if missing
        if (!$this->strEndsWith($ref, '.toon') && !$this->strEndsWith($ref, '.json')) {
            $absPath = $root . '/' . ltrim($ref, '/\\') . '.toon';
            if (file_exists($absPath)) {
                return $this->decodeJsonFile($absPath);
            }
        }

        // Bare ID like "M-constitutional-20260409" — scan memory tree
        if ($this->looksLikeToonId($ref)) {
            return $this->findToonById($ref);
        }

        return null;
    }

    /**
     * Scan lupo-memory/ recursively for a .toon whose filename matches $id.
     */
    private function findToonById($id) {
        $memDir = $this->projectRoot . '/lupo-memory';
        if (!is_dir($memDir)) {
            return null;
        }
        // Clean the id — strip .toon suffix if present
        $basename = $this->strEndsWith($id, '.toon') ? $id : $id . '.toon';

        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($memDir, RecursiveDirectoryIterator::SKIP_DOTS));
        foreach ($it as $file) {
            if ($file->getFilename() === $basename) {
                return $this->decodeJsonFile($file->getPathname());
            }
        }
        return null;
    }

    /**
     * Find the most-recently modified .toon in lupo-memory/YYYY/MM/ (current month first,
     * then any month in current year, then any month).
     *
     * @return string|null  Project-relative path like "lupo-memory/2026/04/M-foo.toon"
     */
    private function findLatestToon() {
        $memoryDir = $this->projectRoot . '/lupo-memory';
        if (!is_dir($memoryDir)) {
            return null;
        }
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($memoryDir, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        $latestToon = null;
        $latestTime = 0;
        foreach ($iterator as $file) {
            if ($file->getExtension() === 'toon') {
                if ($file->getMTime() > $latestTime) {
                    $latestTime = $file->getMTime();
                    $latestToon = $file->getPathname();
                }
            }
        }
        return $latestToon ? str_replace($this->projectRoot . '/', '', $latestToon) : null;
    }

    // -------------------------------------------------------------------------
    // Private: channel context fallback
    // -------------------------------------------------------------------------

    /**
     * Returns a minimal context object when no memory files exist.
     * Never returns an empty array — always gives agents something useful.
     */
    private function getChannelContext() {
        $session = $this->getSession();
        return array(
            '_context_type'  => 'channel_bootstrap',
            'info'           => 'No active memory ref found. This is a fresh channel or memory has not been initialized.',
            'channel_key'    => isset($session['active_channel_key']) ? $session['active_channel_key'] : null,
            'slug'           => isset($session['active_slug'])        ? $session['active_slug']        : null,
            'version'        => isset($session['version'])            ? $session['version']            : null,
            'timestamp'      => isset($session['timestamp']['current']) ? $session['timestamp']['current'] : null,
            'instructions'   => array(
                'Generate memory companions: python lupo-scripts/generate_json_headers.py --all',
                'Migrate transcripts:        python lupo-scripts/migrate_transcript_to_memory.py --all',
                'Set active ref via PHP:     MemoryGraph::setActiveMemoryRef("lupo-memory/YYYY/MM/M-foo.toon")',
                'Set active ref via Python:  python lupo-bin/tick.py --channel_key dev --slug my/slug',
            ),
        );
    }

    // -------------------------------------------------------------------------
    // Private: config helpers
    // -------------------------------------------------------------------------

    private function getSession() {
        if ($this->session !== null) {
            return $this->session;
        }
        $path = $this->projectRoot . '/lupo-config/session.json';
        if (file_exists($path)) {
            $this->session = json_decode(file_get_contents($path), true);
        }
        if (!is_array($this->session)) {
            $this->session = array();
        }
        return $this->session;
    }

    private function getMaxDepth($session) {
        $configPath = $this->channelConfigPath($session);
        if ($configPath && file_exists($configPath)) {
            $config = json_decode(file_get_contents($configPath), true);
            if (isset($config['memory_follow_rules']['max_depth'])) {
                return (int) $config['memory_follow_rules']['max_depth'];
            }
        }
        return 3;
    }

    private function getExcludePatterns($session) {
        $configPath = $this->channelConfigPath($session);
        if ($configPath && file_exists($configPath)) {
            $config = json_decode(file_get_contents($configPath), true);
            if (isset($config['memory_follow_rules']['exclude_patterns']) && is_array($config['memory_follow_rules']['exclude_patterns'])) {
                return $config['memory_follow_rules']['exclude_patterns'];
            }
        }
        return array();
    }

    private function channelConfigPath($session) {
        $ck = isset($session['active_channel_key']) ? $session['active_channel_key'] : '';
        $sl = isset($session['active_slug'])        ? $session['active_slug']        : '';
        if (!$ck || !$sl) {
            return null;
        }
        return $this->projectRoot . '/lupo-channels/0/' . $ck . '/' . $sl . '/config.json';
    }

    // -------------------------------------------------------------------------
    // Private: utilities (PHP 5.6-safe, no str_ends_with / str_starts_with)
    // -------------------------------------------------------------------------

    private function strEndsWith($haystack, $needle) {
        $len = strlen($needle);
        if ($len === 0) {
            return true;
        }
        return substr($haystack, -$len) === $needle;
    }

    private function looksLikePath($ref) {
        return strpos($ref, '/') !== false || strpos($ref, '\\') !== false;
    }

    private function looksLikeToonId($ref) {
        // Bare IDs start with M- and contain no path separators
        return strncmp($ref, 'M-', 2) === 0 && !$this->looksLikePath($ref);
    }

    private function matchesExclude($ref, $patterns) {
        foreach ($patterns as $pattern) {
            if (fnmatch($pattern, $ref)) {
                return true;
            }
            // Also check basename
            if (fnmatch($pattern, basename($ref))) {
                return true;
            }
        }
        return false;
    }

    private function decodeJsonFile($path) {
        $raw = file_get_contents($path);
        if ($raw === false) {
            return null;
        }
        $data = json_decode($raw, true);
        return is_array($data) ? $data : null;
    }

    /**
     * Load node from DB and return toon-like shape.
     *
     * @param string $ref
     * @return array|null
     */
    private function loadFromDatabase($ref) {
        if ($this->db === null) {
            return null;
        }

        $tablePrefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $nodesTable = $tablePrefix . 'memory_nodes';
        $edgesTable = $tablePrefix . 'memory_edges';

        $node = null;
        $sql = '';
        $params = array();

        if (is_string($ref) && preg_match('/^\d+$/', $ref)) {
            $sql = 'SELECT * FROM ' . $this->db->quoteIdentifier($nodesTable)
                . ' WHERE memory_node_id = :mid AND is_deleted = 0 LIMIT 1';
            $params = array('mid' => (int) $ref);
            $node = $this->db->fetchRow($sql, $params);
        }

        if (!$node && is_string($ref) && strncmp($ref, 'FILE:', 5) === 0) {
            $key = substr($ref, 5);
            $sql = 'SELECT * FROM ' . $this->db->quoteIdentifier($nodesTable)
                . ' WHERE memory_toon = :mkey AND is_deleted = 0 ORDER BY updated_ymdhis DESC LIMIT 1';
            $params = array('mkey' => $key);
            $node = $this->db->fetchRow($sql, $params);
        }

        if (!$node && is_string($ref) && strncmp($ref, 'CHANNEL:', 8) === 0) {
            $key = substr($ref, 8);
            $sql = 'SELECT * FROM ' . $this->db->quoteIdentifier($nodesTable)
                . ' WHERE memory_type = :mtype AND memory_toon = :mkey AND is_deleted = 0 '
                . ' ORDER BY updated_ymdhis DESC LIMIT 1';
            $params = array('mtype' => 'channel', 'mkey' => $key);
            $node = $this->db->fetchRow($sql, $params);
        }

        if (!$node && is_string($ref) && strncmp($ref, 'TASK:', 5) === 0) {
            $key = substr($ref, 5);
            $sql = 'SELECT * FROM ' . $this->db->quoteIdentifier($nodesTable)
                . ' WHERE memory_toon = :mkey AND is_deleted = 0 ORDER BY updated_ymdhis DESC LIMIT 1';
            $params = array('mkey' => $key);
            $node = $this->db->fetchRow($sql, $params);
        }

        if (!$node && is_string($ref)) {
            $sql = 'SELECT * FROM ' . $this->db->quoteIdentifier($nodesTable)
                . ' WHERE memory_toon = :mkey AND is_deleted = 0 ORDER BY updated_ymdhis DESC LIMIT 1';
            $params = array('mkey' => $ref);
            $node = $this->db->fetchRow($sql, $params);
        }

        if (!$node) {
            return null;
        }

        $edgeRows = $this->db->fetchAll(
            'SELECT * FROM ' . $this->db->quoteIdentifier($edgesTable)
            . ' WHERE from_memory_node_id = :mid AND is_deleted = 0',
            array('mid' => $node['memory_node_id'])
        );

        $edges = array();
        foreach ($edgeRows as $edge) {
            $to = isset($edge['to_memory_node_id']) ? (string) $edge['to_memory_node_id'] : '';
            if ($to === '') {
                continue;
            }
            $weight = 1.0;
            if (isset($edge['weight_hundredths'])) {
                $weight = ((int) $edge['weight_hundredths']) / 100.0;
            }
            $edges[] = array(
                'to' => $to,
                'type' => isset($edge['edge_type']) ? $edge['edge_type'] : 'references',
                'weight' => $weight,
            );
        }

        $payload = null;
        if (isset($node['memory_value']) && $node['memory_value'] !== null && $node['memory_value'] !== '') {
            $decoded = json_decode($node['memory_value'], true);
            if (is_array($decoded)) {
                $payload = $decoded;
            }
        }

        $content = array();
        if (is_array($payload) && isset($payload['content']) && is_array($payload['content'])) {
            $content = $payload['content'];
        } else {
            $content = array(
                'memory_value' => isset($node['memory_value']) ? $node['memory_value'] : null,
                'memory_key' => isset($node['memory_toon']) ? $node['memory_toon'] : null,
            );
        }

        $summary = isset($payload['summary']) ? $payload['summary'] : '';
        if ($summary === '') {
            $summary = isset($node['memory_toon']) ? (string) $node['memory_toon'] : 'memory_node';
        }

        return array(
            'id' => isset($payload['id']) ? $payload['id'] : ('DB:' . $node['memory_node_id']),
            'type' => isset($node['memory_type']) ? $node['memory_type'] : 'memory_node',
            'ts' => isset($payload['ts']) ? $payload['ts'] : (string) $node['created_ymdhis'] . '.000',
            'actor_id' => isset($node['owner_actor_id']) ? (int) $node['owner_actor_id'] : 0,
            'summary' => $summary,
            'edges' => $edges,
            'content' => $content,
            'schema_version' => isset($payload['schema_version']) ? $payload['schema_version'] : 'toon_v1',
            'status' => isset($node['status']) ? $node['status'] : 'supported',
            'memory_node_id' => (string) $node['memory_node_id'],
            'memory_key' => isset($node['memory_toon']) ? $node['memory_toon'] : '',
        );
    }
}
