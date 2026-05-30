<?php
/**
 * KAIROS — memory consolidation using lupo_memory_nodes (PRD 38) + lupo_edges.
 *
 * Observations use staging-shaped PKs (IdGenerator::generate()). Consolidated memories use
 * living canonical band (toCanonicalIdSafe) per Chronological Trust Ladder doctrine.
 */
class KairosConsolidationService
{
    const MEMORY_OBSERVATION = 'kairos_observation';
    const MEMORY_CONSOLIDATED = 'kairos_memory';

    const EDGE_CONSOLIDATES_FROM = 'kairos_consolidates_from';
    const EDGE_CONTRADICTS = 'kairos_contradicts';

    /** Object type for lupo_edges endpoints (memory_node_id PKs). */
    const EDGE_OBJECT_TYPE_MEMORY_NODE = 'memory_node';

    /** @var PDO_DB|object */
    private $db;

    private $tablePrefix;

    private $kairosActorId;

    public function __construct($db = null, $tablePrefix = null, $kairosActorId = 115)
    {
        if (!class_exists('DatabaseFactory')) {
            require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'DatabaseFactory.php';
        }
        if (!class_exists('IdGenerator')) {
            require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'IdGenerator.php';
        }

        $this->db = $db ? $db : DatabaseFactory::getConnection();
        $this->tablePrefix = $tablePrefix !== null ? $tablePrefix : (defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_');
        $this->kairosActorId = (int) $kairosActorId;
    }

    /**
     * @return string
     */
    private function memoryNodesTable()
    {
        return $this->tablePrefix . 'memory_nodes';
    }

    /**
     * Install doctrine: created_ymdhis SHOULD match the first 14 digits of memory_node_id (packed UTC clock).
     *
     * @param int|string $memoryNodeId
     * @return string 14-digit packed UTC as decimal string (never (int) — 14 digits overflow 32-bit)
     */
    private function packedUtcFromMemoryNodeId($memoryNodeId)
    {
        $s = (string) $memoryNodeId;
        if (strlen($s) >= 14) {
            return substr($s, 0, 14);
        }

        return '0';
    }

    /**
     * @param string $memoryKey
     * @param string $memoryValue
     * @return string 64-char hex
     */
    private function contentHash($memoryKey, $memoryValue)
    {
        return hash('sha256', (string) $memoryKey . "\0" . (string) $memoryValue);
    }

    /**
     * Run one consolidation pass for the given actor (session subject).
     *
     * @param int $actorId
     * @param int|null $departmentId filter observations with matching context kairos.department_id; null = all
     * @return array
     */
    public function consolidateMemories($actorId, $departmentId = null)
    {
        $actorId = (int) $actorId;
        $stats = array(
            'merged_groups' => 0,
            'new_memories' => 0,
            'new_edges' => 0,
            'contradictions' => 0,
            'promoted_verified' => 0,
        );

        if ($actorId <= 0) {
            return $stats;
        }

        $now = KairosTemporalAnchor::getUtcYmdHis();
        $observations = $this->loadActiveObservations($actorId, $departmentId);
        $buckets = $this->bucketObservationsByNormalizedValue($observations, $departmentId);

        foreach ($buckets as $bucket) {
            if (count($bucket) < 2) {
                continue;
            }

            $merged = $this->mergeObservationGroup($bucket, $actorId, $now);
            if ($merged) {
                $stats['merged_groups']++;
                $stats['new_memories']++;
                $stats['new_edges'] += (int) $merged['edges'];
            }
        }

        $stats['contradictions'] = $this->detectContradictions($actorId, $now);
        $stats['promoted_verified'] = $this->promoteVerifiedFacts($actorId, $now);

        return $stats;
    }

    /**
     * @param int $actorId
     * @param string $text
     * @param int|null $departmentId
     * @param string|null $topicKey optional logical topic for contradiction detection
     * @return string memory_node_id (staging-shaped)
     */
    public function recordObservation($actorId, $text, $departmentId = null, $topicKey = null)
    {
        if (!class_exists('IdGenerator')) {
            require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'IdGenerator.php';
        }

        $actorId = (int) $actorId;
        $now = KairosTemporalAnchor::getUtcYmdHis();
        $memoryId = IdGenerator::generate();
        IdGenerator::validateTrustLadderPk($memoryId, 'kairos.memory_node_id', true);
        $key = 'kairos_obs:' . $memoryId;

        $kairos = array(
            'stage' => 'observation',
            'confidence' => 0.25,
            'source_observation_ids' => array(),
            'department_id' => $departmentId !== null ? (int) $departmentId : null,
            'topic_key' => $topicKey !== null ? (string) $topicKey : null,
        );

        $ctx = array('kairos' => $kairos);
        $t = $this->memoryNodesTable();
        $memVal = (string) $text;

        $this->db->insert(
            $t,
            array(
                'memory_node_id' => $memoryId,
                'created_ymdhis' => $this->packedUtcFromMemoryNodeId($memoryId),
                'owner_actor_id' => $actorId,
                'owner_type' => 'actor',
                'memory_type' => self::MEMORY_OBSERVATION,
                'memory_toon' => $key,
                'memory_value' => $memVal,
                'context' => 'experiential',
                'status' => 'unsupported',
                'review_reason' => null,
                'content_hash' => $this->contentHash($key, $memVal),
                'context_json' => json_encode($ctx),
                'updated_ymdhis' => $now,
                'expires_ymdhis' => 0,
                'is_deleted' => 0,
                'deleted_ymdhis' => 0,
            )
        );

        return (string) $memoryId;
    }

    private function loadActiveObservations($actorId, $departmentId)
    {
        $t = $this->memoryNodesTable();
        $sql = "SELECT memory_node_id, owner_actor_id AS actor_id, memory_type, memory_toon, memory_value, context_json, created_ymdhis
                FROM {$t}
                WHERE owner_actor_id = :actor_id
                AND memory_type = :mtype
                AND is_deleted = 0
                ORDER BY created_ymdhis ASC, memory_node_id ASC";

        return $this->db->fetchAll(
            $sql,
            array(
                'actor_id' => $actorId,
                'mtype' => self::MEMORY_OBSERVATION,
            )
        );
    }

    private function bucketObservationsByNormalizedValue($rows, $departmentId)
    {
        $buckets = array();

        foreach ($rows as $row) {
            $ctx = $this->decodeContext(isset($row['context_json']) ? $row['context_json'] : null);
            if ($this->isSuperseded($ctx)) {
                continue;
            }

            if (!$this->departmentMatches($ctx, $departmentId)) {
                continue;
            }

            $norm = $this->normalizeText(isset($row['memory_value']) ? $row['memory_value'] : '');
            if ($norm === '') {
                continue;
            }

            $sig = md5($norm);
            if (!isset($buckets[$sig])) {
                $buckets[$sig] = array();
            }
            $buckets[$sig][] = $row;
        }

        return $buckets;
    }

    private function mergeObservationGroup($group, $actorId, $now)
    {
        if (!class_exists('IdGenerator')) {
            require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'IdGenerator.php';
        }

        $first = $group[0];
        $content = isset($first['memory_value']) ? $first['memory_value'] : '';
        $sourceIds = array();
        $topicKey = null;

        foreach ($group as $r) {
            $sourceIds[] = (string) $r['memory_node_id'];
            $ctx = $this->decodeContext(isset($r['context_json']) ? $r['context_json'] : null);
            $tk = $this->getTopicKey($ctx);
            if ($tk !== null && $tk !== '') {
                $topicKey = $tk;
            }
        }

        $n = count($sourceIds);
        $confidence = 0.35 + (0.12 * $n);
        if ($confidence > 0.85) {
            $confidence = 0.85;
        }

        $stagingId = IdGenerator::generate();
        $memoryId = IdGenerator::toCanonicalIdSafe($stagingId, 'memory_nodes', 'memory_node_id');
        IdGenerator::validateTrustLadderPk($memoryId, 'kairos.memory_node_id', true);

        $memKey = 'kairos_mem:' . $memoryId;

        $ctxOut = array(
            'kairos' => array(
                'stage' => 'correlated',
                'confidence' => $confidence,
                'source_observation_ids' => $sourceIds,
                'department_id' => $this->readDepartmentFromGroup($group),
                'topic_key' => $topicKey,
                'verified_ymdhis' => null,
                'canonical' => false,
            ),
        );

        $t = $this->memoryNodesTable();
        $memVal = $content;

        $this->db->beginTransaction();
        try {
            $ins = $this->db->insert(
                $t,
                array(
                    'memory_node_id' => $memoryId,
                    'created_ymdhis' => $this->packedUtcFromMemoryNodeId($memoryId),
                    'owner_actor_id' => $actorId,
                    'owner_type' => 'actor',
                    'memory_type' => self::MEMORY_CONSOLIDATED,
                    'memory_toon' => $memKey,
                    'memory_value' => $memVal,
                    'context' => 'experiential',
                    'status' => 'unsupported',
                    'review_reason' => null,
                    'content_hash' => $this->contentHash($memKey, $memVal),
                    'context_json' => json_encode($ctxOut),
                    'updated_ymdhis' => $now,
                    'expires_ymdhis' => 0,
                    'is_deleted' => 0,
                    'deleted_ymdhis' => 0,
                )
            );
            if ($ins === false) {
                $this->db->rollBack();
                throw new RuntimeException(
                    'Kairos: consolidated memory insert failed: ' . $this->db->getLastError()
                );
            }

            $edgeCount = 0;
            foreach ($group as $r) {
                $this->markObservationSuperseded($r, $memoryId, $now);
                if ($this->insertConsolidationEdge((string) $memoryId, (string) $r['memory_node_id'], $now)) {
                    $edgeCount++;
                }
            }

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }

        return array('memory_id' => $memoryId, 'edges' => $edgeCount);
    }

    private function readDepartmentFromGroup($group)
    {
        foreach ($group as $r) {
            $ctx = $this->decodeContext(isset($r['context_json']) ? $r['context_json'] : null);
            if (isset($ctx['kairos']['department_id']) && $ctx['kairos']['department_id'] !== null && $ctx['kairos']['department_id'] !== '') {
                return (int) $ctx['kairos']['department_id'];
            }
        }
        return null;
    }

    private function markObservationSuperseded($row, $consolidatedMemoryId, $now)
    {
        $ctx = $this->decodeContext(isset($row['context_json']) ? $row['context_json'] : null);
        if (!isset($ctx['kairos']) || !is_array($ctx['kairos'])) {
            $ctx['kairos'] = array();
        }
        $ctx['kairos']['superseded_by_memory_id'] = (string) $consolidatedMemoryId;
        $ctx['kairos']['stage'] = 'superseded';

        $t = $this->memoryNodesTable();
        $this->db->update(
            $t,
            array(
                'context_json' => json_encode($ctx),
                'updated_ymdhis' => $now,
            ),
            'memory_node_id = :mid',
            array('mid' => $row['memory_node_id'])
        );
    }

    private function insertConsolidationEdge($memoryId, $observationMemoryId, $now)
    {
        $edges = $this->tablePrefix . 'edges';
        $ot = self::EDGE_OBJECT_TYPE_MEMORY_NODE;
        $dup = $this->db->fetchRow(
            "SELECT edge_id FROM {$edges}
             WHERE left_object_type = :lt AND left_object_id = :lid
             AND right_object_type = :rt AND right_object_id = :rid
             AND edge_type = :et AND is_deleted = 0 LIMIT 1",
            array(
                'lt' => $ot,
                'lid' => $memoryId,
                'rt' => $ot,
                'rid' => $observationMemoryId,
                'et' => self::EDGE_CONSOLIDATES_FROM,
            )
        );
        if ($dup) {
            return false;
        }

        if (!class_exists('IdGenerator')) {
            require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'IdGenerator.php';
        }

        $edgeId = IdGenerator::generate();
        IdGenerator::validateTrustLadderPk($edgeId, 'kairos.lupo_edges.edge_id', true);

        $this->db->insert(
            $edges,
            array(
                'edge_id' => $edgeId,
                'left_object_type' => $ot,
                'left_object_id' => $memoryId,
                'right_object_type' => $ot,
                'right_object_id' => $observationMemoryId,
                'edge_type' => self::EDGE_CONSOLIDATES_FROM,
                'edge_category' => 'kairos',
                'edge_description' => 'KAIROS consolidated memory from observation row',
                'channel_id' => null,
                'channel_key' => null,
                'domain_id' => 1,
                'weight_score' => 0,
                'sort_num' => 0,
                'actor_id' => $this->kairosActorId,
                'is_deleted' => 0,
                'deleted_ymdhis' => 0,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'semantic_weight' => 0,
                'relationship_type' => 'semantic',
                'bidirectional' => 0,
                'context_scope' => null,
                'properties' => null,
                'flare_weight' => 0.5,
                'flare_reason' => 'kairos_consolidation',
                'flare_db_source' => 'lupo_memory_nodes',
                'flare_auto_generated' => 1,
                'flare_verified' => 0,
                'flare_discovered_via' => 'kairos',
            )
        );

        return true;
    }

    private function detectContradictions($actorId, $now)
    {
        $t = $this->memoryNodesTable();
        $rows = $this->db->fetchAll(
            "SELECT memory_node_id, memory_value, context_json FROM {$t}
             WHERE owner_actor_id = :aid AND memory_type = :mt AND is_deleted = 0
             ORDER BY memory_node_id ASC",
            array('aid' => $actorId, 'mt' => self::MEMORY_CONSOLIDATED)
        );

        $byTopic = array();
        foreach ($rows as $row) {
            $ctx = $this->decodeContext(isset($row['context_json']) ? $row['context_json'] : null);
            $tk = $this->getTopicKey($ctx);
            if ($tk === null || $tk === '') {
                continue;
            }
            if (!isset($byTopic[$tk])) {
                $byTopic[$tk] = array();
            }
            $byTopic[$tk][] = $row;
        }

        $added = 0;
        $this->db->beginTransaction();
        try {
            foreach ($byTopic as $list) {
                if (count($list) < 2) {
                    continue;
                }
                $norms = array();
                foreach ($list as $r) {
                    $mid = (string) $r['memory_node_id'];
                    $norms[$mid] = $this->normalizeText(isset($r['memory_value']) ? $r['memory_value'] : '');
                }
                $unique = array_unique(array_values($norms));
                if (count($unique) < 2) {
                    continue;
                }

                $pair = $this->pickTwoDistinctIds($list, $norms);
                if (!$pair) {
                    continue;
                }

                $a = $pair['a'];
                $b = $pair['b'];
                $ab = $this->orderMemoryNodeIdsForEdge($a, $b);

                if ($this->insertContradictionEdge($ab['left'], $ab['right'], $now)) {
                    $added++;
                }
            }
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }

        return $added;
    }

    /**
     * Deterministic left/right for dedup: compare zero-padded 18-digit strings (numeric order).
     *
     * @param string $a
     * @param string $b
     * @return array left, right
     */
    private function orderMemoryNodeIdsForEdge($a, $b)
    {
        $pa = str_pad((string) $a, 18, '0', STR_PAD_LEFT);
        $pb = str_pad((string) $b, 18, '0', STR_PAD_LEFT);
        if (strcmp($pa, $pb) <= 0) {
            return array('left' => $a, 'right' => $b);
        }

        return array('left' => $b, 'right' => $a);
    }

    private function pickTwoDistinctIds($list, $norms)
    {
        $firstId = null;
        $firstNorm = null;
        foreach ($list as $r) {
            $id = (string) $r['memory_node_id'];
            $n = isset($norms[$id]) ? $norms[$id] : '';
            if ($firstId === null) {
                $firstId = $id;
                $firstNorm = $n;
                continue;
            }
            if ($n !== $firstNorm) {
                return array('a' => $firstId, 'b' => $id);
            }
        }
        return null;
    }

    private function insertContradictionEdge($leftId, $rightId, $now)
    {
        $edges = $this->tablePrefix . 'edges';
        $ot = self::EDGE_OBJECT_TYPE_MEMORY_NODE;
        $dup = $this->db->fetchRow(
            "SELECT edge_id FROM {$edges}
             WHERE left_object_type = :lt AND left_object_id = :lid
             AND right_object_type = :rt AND right_object_id = :rid
             AND edge_type = :et AND is_deleted = 0 LIMIT 1",
            array(
                'lt' => $ot,
                'lid' => $leftId,
                'rt' => $ot,
                'rid' => $rightId,
                'et' => self::EDGE_CONTRADICTS,
            )
        );
        if ($dup) {
            return false;
        }

        if (!class_exists('IdGenerator')) {
            require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'IdGenerator.php';
        }

        $edgeId = IdGenerator::generate();
        IdGenerator::validateTrustLadderPk($edgeId, 'kairos.lupo_edges.edge_id', true);
        $this->db->insert(
            $edges,
            array(
                'edge_id' => $edgeId,
                'left_object_type' => $ot,
                'left_object_id' => $leftId,
                'right_object_type' => $ot,
                'right_object_id' => $rightId,
                'edge_type' => self::EDGE_CONTRADICTS,
                'edge_category' => 'kairos',
                'edge_description' => 'KAIROS detected conflicting consolidated memories for same topic_key',
                'channel_id' => null,
                'channel_key' => null,
                'domain_id' => 1,
                'weight_score' => 0,
                'sort_num' => 0,
                'actor_id' => $this->kairosActorId,
                'is_deleted' => 0,
                'deleted_ymdhis' => 0,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'semantic_weight' => 0,
                'relationship_type' => 'semantic',
                'direction' => 'bi',
                'bidirectional' => 1,
                'context_scope' => null,
                'properties' => null,
                'flare_weight' => 0.5,
                'flare_reason' => 'kairos_contradiction',
                'flare_db_source' => 'lupo_memory_nodes',
                'flare_auto_generated' => 1,
                'flare_verified' => 0,
                'flare_discovered_via' => 'kairos',
            )
        );

        return true;
    }

    private function promoteVerifiedFacts($actorId, $now)
    {
        $t = $this->memoryNodesTable();
        $rows = $this->db->fetchAll(
            "SELECT memory_node_id, context_json FROM {$t}
             WHERE owner_actor_id = :aid AND memory_type = :mt AND is_deleted = 0",
            array('aid' => $actorId, 'mt' => self::MEMORY_CONSOLIDATED)
        );

        $n = 0;
        foreach ($rows as $row) {
            $ctx = $this->decodeContext(isset($row['context_json']) ? $row['context_json'] : null);
            $conf = isset($ctx['kairos']['confidence']) ? (float) $ctx['kairos']['confidence'] : 0;
            $verified = isset($ctx['kairos']['verified_ymdhis']) ? $ctx['kairos']['verified_ymdhis'] : null;
            if ($conf < 0.8) {
                continue;
            }
            $verifiedStr = trim((string) $verified);
            if ($verifiedStr !== '' && $verifiedStr !== '0') {
                continue;
            }

            if (!isset($ctx['kairos']) || !is_array($ctx['kairos'])) {
                $ctx['kairos'] = array();
            }
            $ctx['kairos']['stage'] = 'verified';
            $ctx['kairos']['verified_ymdhis'] = (string) $now;

            $this->db->update(
                $t,
                array(
                    'context_json' => json_encode($ctx),
                    'updated_ymdhis' => $now,
                ),
                'memory_node_id = :mid',
                array('mid' => $row['memory_node_id'])
            );
            $n++;
        }

        return $n;
    }

    private function decodeContext($json)
    {
        if ($json === null || $json === '') {
            return array();
        }
        if (is_array($json)) {
            return $json;
        }
        $d = json_decode((string) $json, true);
        return is_array($d) ? $d : array();
    }

    private function isSuperseded($ctx)
    {
        if (!isset($ctx['kairos']['superseded_by_memory_id'])) {
            return false;
        }
        $v = $ctx['kairos']['superseded_by_memory_id'];
        return $v !== null && $v !== '' && $v !== '0';
    }

    private function departmentMatches($ctx, $departmentId)
    {
        if ($departmentId === null || $departmentId === '') {
            return true;
        }
        $d = isset($ctx['kairos']['department_id']) ? $ctx['kairos']['department_id'] : null;
        if ($d === null || $d === '') {
            return true;
        }
        return (int) $d === (int) $departmentId;
    }

    private function getTopicKey($ctx)
    {
        if (!isset($ctx['kairos']['topic_key'])) {
            return null;
        }
        $tk = $ctx['kairos']['topic_key'];
        if ($tk === null) {
            return null;
        }
        $s = trim((string) $tk);
        return $s === '' ? null : $s;
    }

    private function normalizeText($s)
    {
        $s = strtolower(trim((string) $s));
        if ($s === '') {
            return '';
        }
        $s = preg_replace('/\s+/', ' ', $s);
        return $s;
    }
}
