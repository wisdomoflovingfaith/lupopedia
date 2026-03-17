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
     * Assert probability value is valid (0.0 to 1.0).
     *
     * @param mixed $value
     * @param string $fieldName
     * @return float
     * @throws InvalidArgumentException
     */
    private function assertProbability($value, $fieldName) {
        if (!is_numeric($value)) {
            throw new InvalidArgumentException($fieldName . ' must be numeric.');
        }
        $v = (float) $value;
        if ($v < 0.0 || $v > 1.0) {
            throw new InvalidArgumentException($fieldName . ' must be between 0.0 and 1.0.');
        }
        return $v;
    }

    /**
     * Calculate posterior probability using Bayes' theorem.
     *
     * @param float $prior Prior probability
     * @param float $likelihood Likelihood of evidence
     * @param float $evidenceProbability Probability of evidence
     * @return float Posterior probability
     */
    public function calculatePosterior($prior, $likelihood, $evidenceProbability) {
        $pPrior = $this->assertProbability($prior, 'prior');
        $pLike  = $this->assertProbability($likelihood, 'likelihood');
        $pEv    = $this->assertProbability($evidenceProbability, 'evidence');
        if ($pEv == 0.0) {
            throw new InvalidArgumentException('evidenceProbability must be > 0 for Bayes update.');
        }
        $posterior = ($pPrior * $pLike) / $pEv;
        return $this->normalizeProbability($posterior);
    }

    /**
     * Normalize probability to valid range.
     *
     * @param float $p
     * @return float Normalized probability
     */
    public function normalizeProbability($p) {
        if ($p < 0.0) { return 0.0; }
        if ($p > 1.0) { return 1.0; }
        return (float) $p;
    }

    /**
     * Combine evidence sequentially using Bayes updates.
     *
     * @param float $prior Initial prior probability
     * @param array $likelihoods Array of likelihoods
     * @param float $evidenceProbability Probability of evidence
     * @return float Final posterior probability
     */
    public function combineEvidenceSequential($prior, array $likelihoods, $evidenceProbability) {
        $p = $this->assertProbability($prior, 'prior');
        foreach ($likelihoods as $idx => $lik) {
            $p = $this->calculatePosterior($p, $lik, $evidenceProbability);
        }
        return $p;
    }

    /**
     * Update decision probability from evidence.
     *
     * @param int $decisionId
     * @param array $likelihoods Evidence likelihoods
     * @param float $evidenceProbability Evidence probability
     * @return float Updated posterior probability
     */
    public function updateDecisionProbabilityFromEvidence($decisionId, array $likelihoods, $evidenceProbability) {
        $decision = $this->getDecisionById($decisionId);
        if (!$decision) {
            throw new RuntimeException('Decision not found: ' . (int)$decisionId);
        }
        $prior = isset($decision['probability']) ? $decision['probability'] : 0.5;
        $posterior = $this->combineEvidenceSequential($prior, $likelihoods, $evidenceProbability);
        $this->saveDecisionProbability($decisionId, $prior, $posterior);
        return $posterior;
    }

    /**
     * Save decision probability with optional history tracking.
     *
     * @param int $decisionId
     * @param float $oldProbability
     * @param float $newProbability
     */
    private function saveDecisionProbability($decisionId, $oldProbability, $newProbability) {
        $this->db->update(
            $this->table_decisions,
            array(
                'probability' => $newProbability,
                'updated_ymdhis' => gmdate('YmdHis')
            ),
            'decision_id = :id',
            array('id' => $decisionId)
        );
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
     * @param int $decisionId
     * @return array
     */
    public function getInfluencesForDecision($decisionId) {
        $sql = "SELECT * FROM " . $this->table_influences . "
                 WHERE target_decision_id = :id AND is_deleted = 0";
        return $this->db->fetchAll($sql, array('id' => (int)$decisionId));
    }

    /**
     * Record evidence for a decision.
     *
     * @param int $decisionId
     * @param int $channelId
     * @param int $projectId
     * @param string $type
     * @param string $source
     * @param string $value
     * @param float $likelihood
     * @param float $confidence
     * @return int evidence_id
     */
    public function recordEvidence($decisionId, $channelId, $projectId, $type, $source, $value, $likelihood, $confidence) {
        $evidenceId = $this->generateDecisionEvidenceId();
        $now = gmdate('YmdHis');
        
        $this->db->insert($this->table_prefix . 'decision_evidence', array(
            'decision_evidence_id' => $evidenceId,
            'decision_id' => $decisionId,
            'channel_id' => $channelId,
            'project_id' => $projectId,
            'evidence_type' => $type,
            'evidence_source' => $source,
            'evidence_value' => $value,
            'likelihood' => $likelihood,
            'confidence' => $confidence,
            'status' => 'active',
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'federation_node_id' => 1,
            'is_deleted' => 0
        ));
        
        return $evidenceId;
    }

    /**
     * Get evidence for a decision.
     *
     * @param int $decisionId
     * @return array
     */
    public function getEvidenceForDecision($decisionId) {
        $sql = "SELECT * FROM " . $this->table_prefix . "decision_evidence"
                 . " WHERE decision_id = :id AND is_deleted = 0"
                 . " ORDER BY created_ymdhis ASC";
        return $this->db->fetchAll($sql, array('id' => (int)$decisionId));
    }

    /**
     * Generate deterministic decision_evidence_id.
     *
     * @return int
     */
    private function generateDecisionEvidenceId() {
        $sql = "SELECT COALESCE(MAX(decision_evidence_id), 0) + 1 
                   FROM " . $this->table_prefix . "decision_evidence";
        $result = $this->db->fetch($sql);
        return $result ? (int)$result[0] : 1;
    }

    /**
     * Set decision state to pending.
     *
     * @param int $decisionId
     * @return bool
     */
    public function setStatePending($decisionId) {
        return $this->updateDecisionState($decisionId, 'pending');
    }

    /**
     * Set decision state to evaluating.
     *
     * @param int $decisionId
     * @return bool
     */
    public function setStateEvaluating($decisionId) {
        return $this->updateDecisionState($decisionId, 'evaluating');
    }

    /**
     * Set decision state to confirmed.
     *
     * @param int $decisionId
     * @return bool
     */
    public function confirmDecision($decisionId) {
        return $this->updateDecisionState($decisionId, 'confirmed');
    }

    /**
     * Set decision state to rejected.
     *
     * @param int $decisionId
     * @return bool
     */
    public function rejectDecision($decisionId) {
        return $this->updateDecisionState($decisionId, 'rejected');
    }

    /**
     * Update decision state.
     *
     * @param int $decisionId
     * @param string $state
     * @return bool
     */
    private function updateDecisionState($decisionId, $state) {
        $sql = "UPDATE " . $this->table_decisions . "
                SET decision_status = :state, updated_ymdhis = :updated
                WHERE decision_id = :id";
        return $this->db->update($sql, array(
            'state' => $state,
            'updated' => gmdate('YmdHis'),
            'id' => $decisionId
        ));
    }
}
