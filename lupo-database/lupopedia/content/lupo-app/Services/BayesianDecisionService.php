<?php

/**
 * BayesianDecisionService - minimal foundation for recording and navigating decisions.
 *
 * 4.0.77 scope: schema + doctrine foundation only.
 * This service provides a thin, doctrine-aligned scaffold over the lupo_decisions
 * family of tables without implementing full Bayesian logic or integrations.
 */

class BayesianDecisionService {

    /**
     * @var PDO_DB
     */
    private $db;

    /**
     * @var string
     */
    private $table_decisions;

    /**
     * @var string
     */
    private $table_edges;

    /**
     * @var string
     */
    private $table_influences;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->db = DatabaseFactory::getConnection();

        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $this->table_decisions   = $table_prefix . 'decisions';
        $this->table_edges       = $table_prefix . 'decision_edges';
        $this->table_influences  = $table_prefix . 'decision_influences';
    }

    /**
     * Record a decision row and return its decision_id.
     *
     * The caller must provide a deterministic decision_id that follows
     * Lupopedia ID allocation doctrine (registry / allocator).
     * Scope: channel_id and project_id are required (every decision is scoped by channel and project).
     *
     * @param array $data Must include decision_id, channel_id, project_id, actor_id, session_id, decision_type, decision_status, created_ymdhis (or it will be set to now)
     * @return int decision_id on success, 0 on validation failure
     */
    public function recordDecision(array $data) {
        if (!isset($data['decision_id'])) {
            return 0;
        }
        if (!isset($data['channel_id']) || $data['channel_id'] === '' || $data['channel_id'] === null) {
            return 0;
        }
        if (!isset($data['project_id']) || $data['project_id'] === '' || $data['project_id'] === null) {
            return 0;
        }

        $now = gmdate('YmdHis');

        if (!isset($data['created_ymdhis'])) {
            $data['created_ymdhis'] = $now;
        }

        if (!isset($data['created_by_actor_id']) && isset($data['actor_id'])) {
            $data['created_by_actor_id'] = $data['actor_id'];
        }

        if (!isset($data['is_deleted'])) {
            $data['is_deleted'] = 0;
        }

        $this->db->insert($this->table_decisions, $data);

        return (int)$data['decision_id'];
    }

    /**
     * Fetch a single decision by id (ignoring soft-deleted rows by default).
     *
     * @param int  $decision_id
     * @param bool $includeDeleted
     * @return array|null
     */
    public function getDecision($decision_id, $includeDeleted = false) {
        $sql = "SELECT * FROM " . $this->table_decisions . " WHERE decision_id = :id";
        $params = array('id' => $decision_id);

        if (!$includeDeleted) {
            $sql .= " AND is_deleted = 0";
        }

        return $this->db->fetch($sql, $params);
    }

    /**
     * Fetch parent decision (if any).
     *
     * @param int $decision_id
     * @return array|null
     */
    public function getParentDecision($decision_id) {
        $sql = "SELECT d2.* FROM " . $this->table_decisions . " d1
                JOIN " . $this->table_decisions . " d2 ON d1.parent_decision_id = d2.decision_id
                WHERE d1.decision_id = :id AND d2.is_deleted = 0";

        return $this->db->fetch($sql, array('id' => $decision_id));
    }

    /**
     * Fetch child decisions for a given parent.
     *
     * @param int $decision_id
     * @return array
     */
    public function getChildDecisions($decision_id) {
        $sql = "SELECT * FROM " . $this->table_decisions . "
                WHERE parent_decision_id = :id AND is_deleted = 0
                ORDER BY created_ymdhis ASC";

        return $this->db->fetchAll($sql, array('id' => $decision_id));
    }

    /**
     * Fetch outgoing edges for a decision.
     *
     * @param int $decision_id
     * @return array
     */
    public function getOutgoingEdges($decision_id) {
        $sql = "SELECT * FROM " . $this->table_edges . "
                WHERE source_decision_id = :id AND is_deleted = 0";

        return $this->db->fetchAll($sql, array('id' => $decision_id));
    }

    /**
     * Fetch incoming edges for a decision.
     *
     * @param int $decision_id
     * @return array
     */
    public function getIncomingEdges($decision_id) {
        $sql = "SELECT * FROM " . $this->table_edges . "
                WHERE target_decision_id = :id AND is_deleted = 0";

        return $this->db->fetchAll($sql, array('id' => $decision_id));
    }

    /**
     * Fetch influences for a decision.
     *
     * @param int $decision_id
     * @return array
     */
    public function getInfluences($decision_id) {
        $sql = "SELECT * FROM " . $this->table_influences . "
                WHERE decision_id = :id AND is_deleted = 0";

        return $this->db->fetchAll($sql, array('id' => $decision_id));
    }
}

