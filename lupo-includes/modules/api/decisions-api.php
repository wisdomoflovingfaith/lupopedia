<?php

/**
 * Decisions API - REST endpoints for Bayesian Decision Tracking
 * 
 * Provides API surface for decision management, evidence recording, and queries.
 * Security: Session-based authentication, no client-supplied actor_id.
 */

require_once dirname(__FILE__) . '/../../bootstrap.php';

class DecisionsApiController {
    
    private $db;
    private $table_prefix;
    private $service;
    
    public function __construct() {
        $this->db = DatabaseFactory::getConnection();
        $this->table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $this->service = new BayesianDecisionService();
    }
    
    /**
     * Send JSON response
     */
    private function sendJsonResponse($data, $httpCode = 200) {
        header('Content-Type: application/json');
        http_response_code($httpCode);
        echo json_encode($data);
        exit;
    }
    
    /**
     * Get current actor from session
     */
    private function getCurrentActorId() {
        if (isset($GLOBALS['lupo_auth_service']) && $GLOBALS['lupo_auth_service']->isLoggedIn()) {
            return $GLOBALS['lupo_auth_service']->getCurrentActorId();
        }
        return null;
    }
    
    /**
     * Validate channel/project access
     */
    private function validateChannelProjectAccess($channelId, $projectId) {
        $actorId = $this->getCurrentActorId();
        if (!$actorId) {
            $this->sendJsonResponse(['error' => 'Authentication required'], 401);
        }
        
        // Check channel membership
        $sql = "SELECT COUNT(*) as count FROM " . $this->table_prefix . "actor_channels 
                   WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0";
        $result = $this->db->fetch($sql, array(
            'actor_id' => $actorId,
            'channel_id' => $channelId
        ));
        
        if (!$result || $result['count'] == 0) {
            $this->sendJsonResponse(['error' => 'Access denied - not a channel member'], 403);
        }
        
        return true;
    }
    
    /**
     * POST /api/decisions - Create a new decision
     */
    public function createDecision() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input->decision_id) || !isset($input->channel_id) || !isset($input->project_id)) {
            $this->sendJsonResponse(['error' => 'Missing required fields: decision_id, channel_id, project_id'], 400);
        }
        
        $this->validateChannelProjectAccess($input->channel_id, $input->project_id);
        
        $data = array(
            'decision_id' => (int)$input->decision_id,
            'channel_id' => (int)$input->channel_id,
            'project_id' => (int)$input->project_id,
            'actor_id' => $this->getCurrentActorId(),
            'decision_type' => isset($input->decision_type) ? $input->decision_type : 'general',
            'probability' => isset($input->probability) ? (float)$input->probability : 0.5,
            'created_ymdhis' => gmdate('YmdHis')
        );
        
        $decisionId = $this->service->recordDecision($data);
        
        if ($decisionId) {
            $this->sendJsonResponse([
                'success' => true,
                'decision_id' => $decisionId,
                'message' => 'Decision created successfully'
            ], 201);
        } else {
            $this->sendJsonResponse(['error' => 'Failed to create decision'], 500);
        }
    }
    
    /**
     * GET /api/decisions/{id} - Get decision by ID
     */
    public function getDecision($id) {
        $this->validateChannelProjectAccess($_GET['channel_id'] ?? null, $_GET['project_id'] ?? null);
        
        $decision = $this->service->getDecision($id);
        
        if ($decision) {
            $this->sendJsonResponse($decision);
        } else {
            $this->sendJsonResponse(['error' => 'Decision not found'], 404);
        }
    }
    
    /**
     * POST /api/decisions/{id}/evidence - Record evidence for decision
     */
    public function recordEvidence($decisionId) {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input->evidence_type) || !isset($input->evidence_source)) {
            $this->sendJsonResponse(['error' => 'Missing required fields: evidence_type, evidence_source'], 400);
        }
        
        $this->validateChannelProjectAccess($_GET['channel_id'] ?? null, $_GET['project_id'] ?? null);
        
        $evidenceId = $this->service->recordEvidence(
            $decisionId,
            $_GET['channel_id'] ?? 0,
            $_GET['project_id'] ?? 0,
            $input->evidence_type,
            $input->evidence_source,
            isset($input->evidence_value) ? $input->evidence_value : null,
            isset($input->likelihood) ? (float)$input->likelihood : null,
            isset($input->confidence) ? (float)$input->confidence : null
        );
        
        $this->sendJsonResponse([
            'success' => true,
            'evidence_id' => $evidenceId,
            'message' => 'Evidence recorded successfully'
        ]);
    }
    
    /**
     * GET /api/decisions/{id}/evidence - Get evidence for decision
     */
    public function getEvidence($decisionId) {
        $this->validateChannelProjectAccess($_GET['channel_id'] ?? null, $_GET['project_id'] ?? null);
        
        $evidence = $this->service->getEvidenceForDecision($decisionId);
        
        $this->sendJsonResponse($evidence);
    }
    
    /**
     * Route requests based on HTTP method and path
     */
    public function route() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathParts = explode('/', trim($path, '/'));
        
        // Remove empty first element
        array_shift($pathParts);
        
        if ($method === 'POST' && count($pathParts) === 1 && $pathParts[0] === 'decisions') {
            $this->createDecision();
        } elseif ($method === 'GET' && count($pathParts) === 2 && $pathParts[0] === 'decisions') {
            $decisionId = (int)$pathParts[1];
            
            if (count($pathParts) === 3 && $pathParts[2] === 'evidence') {
                $this->getEvidence($decisionId);
            } else {
                $this->getDecision($decisionId);
            }
        } else {
            $this->sendJsonResponse(['error' => 'Endpoint not found'], 404);
        }
    }
}

// Route the request
$controller = new DecisionsApiController();
$controller->route();
