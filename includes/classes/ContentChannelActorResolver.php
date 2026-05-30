<?php
/**
 * Resolve actors on the channel that a content (by file path from root) belongs to.
 * PHP 5.3+ compatible; no namespaces. Uses PDO_DB only. No FKs, no triggers, no schema inference.
 * FLIP/FLP: content → lupo_edges (HAS_CONTENT) → channel_id → lupo_actor_channels + lupo_actors.
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

class ContentChannelActorResolver {

    /** @var object PDO_DB */
    private $db;
    /** @var string Table prefix (e.g. lupo_) */
    private $prefix;
    /** @var string|null Repo root for path validation (null = skip realpath check) */
    private $repo_root;

    /**
     * @param object $db        PDO_DB instance
     * @param string $prefix    Table prefix (e.g. lupo_)
     * @param string|null $repo_root Repo root path for validating file_path_from_root (optional)
     */
    public function __construct($db, $prefix, $repo_root = null) {
        $this->db = $db;
        $this->prefix = $prefix;
        $this->repo_root = $repo_root;
    }

    /**
     * Validate and sanitize path-from-root: no "..", must resolve inside repo_root if set.
     *
     * @param string $path_from_root Path relative to repo root (e.g. docs/doctrine/FLIP/README.md)
     * @return string|null Sanitized path (forward slashes) or null if invalid
     */
    public function validateAndSanitizePathFromRoot($path_from_root) {
        if (!is_string($path_from_root) || trim($path_from_root) === '' || strpos($path_from_root, '..') !== false) {
            return null;
        }
        $path = trim($path_from_root);
        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');
        if ($path === '') {
            return null;
        }
        if ($this->repo_root !== null && $this->repo_root !== '') {
            $resolved = realpath($this->repo_root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path));
            $real_root = realpath($this->repo_root);
            if ($resolved === false || $real_root === false) {
                return null;
            }
            if ($resolved !== $real_root && strpos($resolved, $real_root . DIRECTORY_SEPARATOR) !== 0) {
                return null;
            }
        }
        return $path;
    }

    /**
     * Get actors on the channel that the given file path (from root) belongs to.
     * Path is validated/sanitized; then content_id → channel_id → actors.
     *
     * @param string $filePath file_path_from_root (e.g. docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md)
     * @return array List of actors: array( array('actor_id'=>..., 'actor_name'=>..., 'type'=>..., 'role'=>..., 'status'=>..., 'joined_at'=>...), ... )
     */
    public function getActorsForFilePath($filePath) {
        $path = $this->validateAndSanitizePathFromRoot($filePath);
        if ($path === null) {
            return array();
        }
        $contents_table = $this->db->quoteIdentifier($this->prefix . 'contents');
        $row = $this->db->fetchRow(
            "SELECT content_id FROM " . $contents_table . " WHERE file_path_from_root = :path AND is_deleted = 0 LIMIT 1",
            array('path' => $path)
        );
        if (!$row || !isset($row['content_id'])) {
            return array();
        }
        $content_id = (int) $row['content_id'];
        $edges_table = $this->db->quoteIdentifier($this->prefix . 'edges');
        $edge_row = $this->db->fetchRow(
            "SELECT left_object_id FROM " . $edges_table . " WHERE left_object_type = 'channel' AND right_object_type = 'content' AND right_object_id = :cid AND edge_type = 'HAS_CONTENT' AND is_deleted = 0 LIMIT 1",
            array('cid' => $content_id)
        );
        if (!$edge_row || !isset($edge_row['left_object_id'])) {
            return array();
        }
        $channel_id = (int) $edge_row['left_object_id'];
        $ac_table = $this->db->quoteIdentifier($this->prefix . 'actor_channels');
        $a_table = $this->db->quoteIdentifier($this->prefix . 'actors');
        $acr_table = $this->db->quoteIdentifier($this->prefix . 'actor_channel_roles');
        $sql = "SELECT a.actor_id, a.name AS actor_name, a.actor_type AS type, ac.status, ac.start_date, ac.created_ymdhis, acr.role_key AS role "
             . "FROM " . $ac_table . " ac INNER JOIN " . $a_table . " a ON a.actor_id = ac.actor_id "
             . "LEFT JOIN " . $acr_table . " acr ON acr.actor_id = ac.actor_id AND acr.channel_id = ac.channel_id AND acr.is_deleted = 0 "
             . "WHERE ac.channel_id = :chid AND ac.is_deleted = 0 AND a.is_deleted = 0 ORDER BY ac.created_ymdhis";
        $rows = $this->db->fetchAll($sql, array('chid' => $channel_id));
        $out = array();
        foreach ($rows as $r) {
            $joined_at = isset($r['start_date']) && $r['start_date'] !== null ? $r['start_date'] : (isset($r['created_ymdhis']) ? $r['created_ymdhis'] : null);
            $out[] = array(
                'actor_id' => (int) $r['actor_id'],
                'actor_name' => isset($r['actor_name']) ? (string) $r['actor_name'] : '',
                'type' => isset($r['type']) ? (string) $r['type'] : '',
                'role' => isset($r['role']) ? (string) $r['role'] : '',
                'status' => isset($r['status']) ? (string) $r['status'] : '',
                'joined_at' => $joined_at,
            );
        }
        return $out;
    }
}
