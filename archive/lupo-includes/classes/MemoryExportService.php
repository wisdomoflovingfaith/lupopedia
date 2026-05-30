<?php
// lupo-includes/classes/MemoryExportService.php
//
// PRD 38: mirror lupo_memory_nodes (+ edges) to lupo-memory/YYYY/MM/{slug}.json
// Runtime rows: created_ymdhis SHOULD match the 14-digit prefix of memory_node_id (IdGenerator).
// Seed / pre-existing rows: memory_node_id may be low; created_ymdhis may be 0 — export uses 19700101000000 for path/slug (lupo-memory/1970/01/).

class MemoryExportService
{
    /** @var PDO_DB */
    private $db;

    /** @var string */
    private $prefix;

    /** @var string */
    private $exportRoot;

    public function __construct()
    {
        $this->db = DatabaseFactory::getConnection();
        $this->prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $base = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : dirname(__DIR__, 2);
        $this->exportRoot = $base . '/lupo-memory';
    }

    /**
     * Export one active (non-deleted) memory node to the filesystem mirror.
     *
     * @param string|int $memoryNodeId
     * @return void
     */
    public function exportNode($memoryNodeId)
    {
        $table = $this->prefix . 'memory_nodes';
        $sql = 'SELECT * FROM ' . $this->db->quoteIdentifier($table)
            . ' WHERE memory_node_id = :mid AND is_deleted = 0 LIMIT 1';
        $node = $this->db->fetchRow($sql, array('mid' => $memoryNodeId));

        if (!$node) {
            $this->removeMirrorFileForNode($memoryNodeId);
            return;
        }

        $created = $this->createdYmdhisForExportPath($node['created_ymdhis']);
        $year = substr($created, 0, 4);
        $month = substr($created, 4, 2);
        $dir = $this->exportRoot . '/' . $year . '/' . $month;

        if (!is_dir($dir)) {
            if (!@mkdir($dir, 0755, true) && !is_dir($dir)) {
                return;
            }
        }

        $slug = $this->generateSlug(
            $created,
            isset($node['owner_type']) ? $node['owner_type'] : 'actor',
            isset($node['owner_actor_id']) ? $node['owner_actor_id'] : 0,
            isset($node['memory_type']) ? $node['memory_type'] : 'unknown',
            isset($node['memory_toon']) ? $node['memory_toon'] : ''
        );

        $filePath = $dir . '/' . $slug . '.json';

        $memVal = $node['memory_value'];
        $decodedVal = json_decode($memVal, true);
        if ($decodedVal === null && $memVal !== null && $memVal !== 'null') {
            $decodedVal = $memVal;
        }

        $ctxJson = null;
        if (!empty($node['context_json'])) {
            $ctxJson = json_decode($node['context_json'], true);
        }

        $export = array(
            'memory_node_id' => $node['memory_node_id'],
            'created_ymdhis' => $node['created_ymdhis'],
            'owner_actor_id' => $node['owner_actor_id'],
            'owner_type' => $node['owner_type'],
            'memory_type' => $node['memory_type'],
            'memory_key' => $node['memory_toon'],
            'memory_value' => $decodedVal,
            'context' => $node['context'],
            'status' => $node['status'],
            'review_reason' => $node['review_reason'],
            'content_hash' => $node['content_hash'],
            'context_json' => $ctxJson,
            'updated_ymdhis' => $node['updated_ymdhis'],
            'expires_ymdhis' => $node['expires_ymdhis'],
            'edges' => $this->getEdgesForNode($memoryNodeId),
        );

        @file_put_contents($filePath, json_encode($export, JSON_PRETTY_PRINT));
    }

    /**
     * Build filesystem-safe slug (filename stem, no extension).
     *
     * @param string $createdYmdhis fourteen-digit packed UTC or compatible prefix
     * @param string $ownerType
     * @param int|string $ownerActorId
     * @param string $memoryType
     * @param string $memoryKey
     * @return string
     */
    public function generateSlug($createdYmdhis, $ownerType, $ownerActorId, $memoryType, $memoryKey)
    {
        $createdYmdhis = (string) $createdYmdhis;
        $date = strlen($createdYmdhis) >= 8 ? substr($createdYmdhis, 0, 8) : '00000000';
        $time = strlen($createdYmdhis) >= 14 ? substr($createdYmdhis, 8, 6) : '000000';

        $safeKey = str_replace(array(':', '/', ' ', '\\'), '_', (string) $memoryKey);

        return sprintf(
            '%s_%s_%s_%s_%s_%s',
            $date,
            $time,
            $ownerType,
            (string) $ownerActorId,
            $memoryType,
            $safeKey
        );
    }

    /**
     * Packed UTC for mirror path and slug. Seed / immemorial rows use created_ymdhis = 0 (or too short) → Unix epoch folder.
     *
     * @param string|int $createdYmdhis from lupo_memory_nodes.created_ymdhis
     * @return string fourteen-digit packed UTC string
     */
    private function createdYmdhisForExportPath($createdYmdhis)
    {
        $s = (string) $createdYmdhis;
        if ($s === '' || $s === '0' || strlen($s) < 6) {
            return '19700101000000';
        }
        return $s;
    }

    /**
     * @param string|int $memoryNodeId
     * @return array
     */
    private function getEdgesForNode($memoryNodeId)
    {
        $t = $this->prefix . 'memory_edges';
        $sql = 'SELECT * FROM ' . $this->db->quoteIdentifier($t)
            . ' WHERE (from_memory_node_id = :mid OR to_memory_node_id = :mid2)'
            . ' AND is_deleted = 0';
        return $this->db->fetchAll($sql, array('mid' => $memoryNodeId, 'mid2' => $memoryNodeId));
    }

    /**
     * Remove mirror file when the node is gone or soft-deleted.
     *
     * @param string|int $memoryNodeId
     * @return void
     */
    public function removeMirrorFileForNode($memoryNodeId)
    {
        $table = $this->prefix . 'memory_nodes';
        $sql = 'SELECT * FROM ' . $this->db->quoteIdentifier($table)
            . ' WHERE memory_node_id = :mid LIMIT 1';
        $node = $this->db->fetchRow($sql, array('mid' => $memoryNodeId));

        if (!$node) {
            return;
        }

        $created = $this->createdYmdhisForExportPath($node['created_ymdhis']);
        $year = substr($created, 0, 4);
        $month = substr($created, 4, 2);
        $slug = $this->generateSlug(
            $created,
            isset($node['owner_type']) ? $node['owner_type'] : 'actor',
            isset($node['owner_actor_id']) ? $node['owner_actor_id'] : 0,
            isset($node['memory_type']) ? $node['memory_type'] : 'unknown',
            isset($node['memory_toon']) ? $node['memory_toon'] : ''
        );
        $filePath = $this->exportRoot . '/' . $year . '/' . $month . '/' . $slug . '.json';
        if (is_file($filePath)) {
            @unlink($filePath);
        }
    }

    /**
     * @return int count exported
     */
    public function fullExport()
    {
        $table = $this->prefix . 'memory_nodes';
        $sql = 'SELECT memory_node_id FROM ' . $this->db->quoteIdentifier($table) . ' WHERE is_deleted = 0';
        $nodes = $this->db->fetchAll($sql, array());
        $n = 0;
        foreach ($nodes as $row) {
            if (!empty($row['memory_node_id'])) {
                $this->exportNode($row['memory_node_id']);
                $n++;
            }
        }
        return $n;
    }

    /**
     * @param string|int $sinceYmdhis
     * @return int count exported
     */
    public function exportSince($sinceYmdhis)
    {
        $table = $this->prefix . 'memory_nodes';
        $sql = 'SELECT memory_node_id FROM ' . $this->db->quoteIdentifier($table)
            . ' WHERE updated_ymdhis >= :since AND is_deleted = 0';
        $nodes = $this->db->fetchAll($sql, array('since' => $sinceYmdhis));
        $n = 0;
        foreach ($nodes as $row) {
            if (!empty($row['memory_node_id'])) {
                $this->exportNode($row['memory_node_id']);
                $n++;
            }
        }
        return $n;
    }
}
