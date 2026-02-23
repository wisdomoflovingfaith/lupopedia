---
# FLIP Header (alias: Actor 4.0.30)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: app/Services\SecurityDecisionFramework.php
file.last_modified_system_version: "4.0.30"
file.last_modified_utc: "20260222214000"
channel_id: 42
actor_id: 10000
---

<?php

/**
 * Security Decision Framework - 4.0.30
 * 
 * Implements risk-based security decision making for semantic content.
 * Provides multi-layer security enforcement based on threat assessment.
 * 
 * @package Lupopedia\Services
 * @version 4.0.30
 */

class SecurityDecisionFramework {
    
    private $database;
    private $decision_matrix = array();
    
    public function __construct() {
        $this->database = DatabaseFactory::getConnection();
        $this->initializeDecisionMatrix();
    }
    
    /**
     * Make security decision based on validation result
     * 
     * @param array $validation_result Security validation result
     * @return array Security decision with action and status
     */
    public function makeDecision($validation_result) {
        $decision = array(
            'action' => 'allow',
            'status' => 'safe',
            'reason' => 'no_threat_detected',
            'restrictions' => array(),
            'monitoring_level' => 'standard',
            'quarantine_duration' => 0
        );
        
        // Apply decision matrix based on threat level
        switch ($validation_result['threat_level']) {
            case 'critical':
                $decision = $this->handleCriticalThreat($validation_result);
                break;
            case 'high':
                $decision = $this->handleHighThreat($validation_result);
                break;
            case 'medium':
                $decision = $this->handleMediumThreat($validation_result);
                break;
            case 'low':
                $decision = $this->handleLowThreat($validation_result);
                break;
            default:
                $decision = $this->handleUnknownThreat($validation_result);
                break;
        }
        
        // Apply emotional state considerations
        $decision = $this->applyEmotionalConsiderations($decision, $validation_result);
        
        // Apply boundary compliance considerations
        $decision = $this->applyBoundaryConsiderations($decision, $validation_result);
        
        return $decision;
    }
    
    /**
     * Handle critical threats with immediate containment
     */
    private function handleCriticalThreat($validation_result) {
        return array(
            'action' => 'emergency_containment',
            'status' => 'critical',
            'reason' => 'critical_threat_detected',
            'restrictions' => array(
                'all_operations_blocked',
                'database_access_blocked',
                'semantic_operations_blocked'
            ),
            'monitoring_level' => 'comprehensive',
            'quarantine_duration' => 3600 // 1 hour
        );
    }
    
    /**
     * Handle high threats with restrictions
     */
    private function handleHighThreat($validation_result) {
        return array(
            'action' => 'quarantine',
            'status' => 'high',
            'reason' => 'high_threat_detected',
            'restrictions' => array(
                'semantic_operations_restricted',
                'emotional_operations_restricted',
                'boundary_operations_restricted'
            ),
            'monitoring_level' => 'enhanced',
            'quarantine_duration' => 1800 // 30 minutes
        );
    }
    
    /**
     * Handle medium threats with monitoring
     */
    private function handleMediumThreat($validation_result) {
        return array(
            'action' => 'restrict',
            'status' => 'medium',
            'reason' => 'medium_threat_detected',
            'restrictions' => array(
                'enhanced_monitoring_required',
                'content_validation_required',
                'context_validation_required'
            ),
            'monitoring_level' => 'enhanced',
            'quarantine_duration' => 900 // 15 minutes
        );
    }
    
    /**
     * Handle low threats with monitoring
     */
    private function handleLowThreat($validation_result) {
        return array(
            'action' => 'monitor',
            'status' => 'low',
            'reason' => 'low_threat_detected',
            'restrictions' => array(
                'standard_monitoring',
                'periodic_validation'
            ),
            'monitoring_level' => 'standard',
            'quarantine_duration' => 300 // 5 minutes
        );
    }
    
    /**
     * Handle unknown threats with standard monitoring
     */
    private function handleUnknownThreat($validation_result) {
        return array(
            'action' => 'monitor',
            'status' => 'unknown',
            'reason' => 'unknown_threat_level',
            'restrictions' => array(
                'standard_monitoring',
                'periodic_validation'
            ),
            'monitoring_level' => 'standard',
            'quarantine_duration' => 300 // 5 minutes
        );
    }
    
    /**
     * Apply emotional considerations to security decision
     */
    private function applyEmotionalConsiderations($decision, $validation_result) {
        if ($validation_result['emotional_state'] === 'aggressive') {
            $decision['action'] = 'quarantine';
            $decision['status'] = 'high';
            $decision['reason'] = 'aggressive_emotional_state_detected';
            $decision['restrictions'][] = 'emotional_operations_blocked';
        } elseif ($validation_result['emotional_state'] === 'destructive') {
            $decision['action'] = 'emergency_containment';
            $decision['status'] = 'critical';
            $decision['reason'] = 'destructive_intent_detected';
            $decision['restrictions'][] = 'all_operations_blocked';
        } elseif ($validation_result['emotional_state'] === 'chaotic') {
            $decision['action'] = 'quarantine';
            $decision['status'] = 'high';
            $decision['reason'] = 'chaotic_emotional_state_detected';
            $decision['restrictions'][] = 'emotional_operations_blocked';
        }
        
        // Upgrade security level for unstable emotional states
        if ($validation_result['emotional_stability'] < 0.6) {
            $decision['monitoring_level'] = 'comprehensive';
            if ($decision['monitoring_level'] !== 'comprehensive') {
                $decision['monitoring_level'] = 'comprehensive';
            }
        }
        
        return $decision;
    }
    
    /**
     * Apply boundary compliance considerations to security decision
     */
    private function applyBoundaryConsiderations($decision, $validation_result) {
        if ($validation_result['boundary_status'] === 'violation') {
            $decision['action'] = 'quarantine';
            $decision['status'] = 'high';
            $decision['reason'] = 'boundary_violation_detected';
            $decision['restrictions'][] = 'boundary_operations_blocked';
        }
        
        // Upgrade security level for boundary violations
        if (!empty($validation_result['boundary_violations'])) {
            $decision['monitoring_level'] = 'enhanced';
            if ($decision['monitoring_level'] !== 'enhanced') {
                $decision['monitoring_level'] = 'enhanced';
            }
        }
        
        return $decision;
    }
    
    /**
     * Initialize decision matrix for threat levels
     */
    private function initializeDecisionMatrix() {
        $this->decision_matrix = array(
            'critical' => array(
                'action' => 'emergency_containment',
                'status' => 'critical',
                'monitoring_level' => 'comprehensive',
                'quarantine_duration' => 3600
            ),
            'high' => array(
                'action' => 'quarantine',
                'status' => 'high',
                'monitoring_level' => 'enhanced',
                'quarantine_duration' => 1800
            ),
            'medium' => array(
                'action' => 'restrict',
                'status' => 'medium',
                'monitoring_level' => 'enhanced',
                'quarantine_duration' => 900
            ),
            'low' => array(
                'action' => 'monitor',
                'status' => 'low',
                'monitoring_level' => 'standard',
                'quarantine_duration' => 300
            ),
            'unknown' => array(
                'action' => 'monitor',
                'status' => 'unknown',
                'monitoring_level' => 'standard',
                'quarantine_duration' => 300
            )
        );
    }
    
    /**
     * Get decision matrix for analysis
     */
    public function getDecisionMatrix() {
        return $this->decision_matrix;
    }
    
    /**
     * Update decision matrix for specific threat level
     */
    public function updateDecisionMatrix($threat_level, $action, $status, $monitoring_level, $quarantine_duration) {
        $this->decision_matrix[$threat_level] = array(
            'action' => $action,
            'status' => $status,
            'monitoring_level' => $monitoring_level,
            'quarantine_duration' => $quarantine_duration
        );
    }
    
    /**
     * Get security action for threat level
     */
    public function getSecurityAction($threat_level) {
        if (!isset($this->decision_matrix[$threat_level])) {
            return 'monitor';
        }
        
        return $this->decision_matrix[$threat_level]['action'];
    }
    
    /**
     * Get security status for threat level
     */
    public function getSecurityStatus($threat_level) {
        if (!isset($this->decision_matrix[$threat_level])) {
            return 'unknown';
        }
        
        return $this->decision_matrix[$threat_level]['status'];
    }
    
    /**
     * Get monitoring level for threat level
     */
    public function getMonitoringLevel($threat_level) {
        if (!isset($this->decision_matrix[$threat_level])) {
            return 'standard';
        }
        
        return $this->decision_matrix[$threat_level]['monitoring_level'];
    }
    
    /**
     * Get quarantine duration for threat level
     */
    public function getQuarantineDuration($threat_level) {
        if (!isset($this->decision_matrix[$threat_level])) {
            return 300; // Default 5 minutes
        }
        
        return $this->decision_matrix[$threat_level]['quarantine_duration'];
    }
    
    /**
     * Validate security decision against policy
     */
    public function validateDecision($decision) {
        $valid_actions = array('allow', 'monitor', 'restrict', 'quarantine', 'emergency_containment');
        $valid_statuses = array('safe', 'low', 'medium', 'high', 'critical');
        
        if (!in_array($decision['action'], $valid_actions)) {
            return false;
        }
        
        if (!in_array($decision['status'], $valid_statuses)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Get security recommendations for threat level
     */
    public function getRecommendations($threat_level) {
        $recommendations = array();
        
        switch ($threat_level) {
            case 'critical':
                $recommendations[] = "IMMEDIATE ACTION REQUIRED: Critical security threat detected";
                $recommendations[] = "Activate emergency containment protocols";
                $recommendations[] = "Notify security team immediately";
                break;
            case 'high':
                $recommendations[] = "HIGH PRIORITY: Review and investigate immediately";
                $recommendations[] = "Apply enhanced security measures";
                $recommendations[] = "Consider temporary restrictions";
                break;
            case 'medium':
                $recommendations[] = "Monitor the situation closely";
                $recommendations[] = "Apply enhanced validation";
                $recommendations[] = "Consider temporary restrictions";
                break;
            case 'low':
                $recommendations[] = "Continue monitoring";
                $recommendations[] = "Apply standard validation";
                $recommendations[] = "Maintain regular monitoring";
                break;
            case 'unknown':
                $recommendations[] = "Investigate unknown threat level";
                $recommendations[] = "Apply standard monitoring";
                $recommendations[] = "Review security policies";
                break;
        }
        
        return $recommendations;
    }
    
    /**
     * Log security decision for audit trail
     */
    public function logSecurityDecision($content, $decision, $context = array()) {
        $this->database->insert(LUPO_TABLE_PREFIX . 'security_decisions', array(
            'threat_level' => $decision['threat_level'] ?? 'unknown',
            'action' => $decision['action'] ?? 'unknown',
            'status' => $decision['status'] ?? 'unknown',
            'content_hash' => md5($content ?? ''),
            'decision_data' => json_encode($decision),
            'context_data' => json_encode($context),
            'created_ymdhis' => gmdate('YmdHis')
        ));
    }
    
    /**
     * Get security statistics for reporting
     */
    public function getSecurityStatistics($timeframe = '24h') {
        $stats = array(
            'total_decisions' => 0,
            'threat_distribution' => array(),
            'action_distribution' => array(),
            'compliance_rate' => 0,
            'average_quarantine_time' => 0
        );
        
        $start_time = $this->calculateStartTime($timeframe);
        
        // Get decision statistics
        $query = "SELECT COUNT(*) as count, action, status, threat_level, 
                          AVG(quarantine_duration) as avg_quarantine_time
                          FROM " . LUPO_TABLE_PREFIX . "security_decisions 
                          WHERE created_ymdhis >= :start_time 
                          GROUP BY action, status, threat_level";
        
        $stmt = $this->database->prepare($query);
        $stmt->execute(array('start_time' => $start_time));
        $decisions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($decisions as $decision) {
            $stats['total_decisions'] += $decision['count'];
            $stats['action_distribution'][$decision['action']] = $decision['count'];
            $stats['threat_distribution'][$decision['threat_level']] = $decision['count'];
            $stats['average_quarantine_time'] += $decision['avg_quarantine_time'];
        }
        
        // Calculate compliance rate
        $compliance_query = "SELECT COUNT(*) as total, 
                           SUM(CASE WHEN status = 'safe' THEN 1 ELSE 0 END) as compliant 
                           FROM " . LUPO_PREFIX . "security_decisions 
                           WHERE created_ymdhis >= :start_time";
        
        $stmt = $this->database->prepare($compliance_query);
        $stmt->execute(array('start_time' => $start_time));
        $compliance = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($compliance['total'] > 0) {
            $stats['compliance_rate'] = ($compliance['compliant'] / $compliance['total']) * 100;
        }
        
        return $stats;
    }
    
    /**
     * Calculate start time for timeframe
     */
    private function calculateStartTime($timeframe) {
        $now = time();
        switch ($timeframe) {
            case '1h':
                return $now - 3600;
            case '24h':
                return $now - 86400;
            case '7d':
                return $now - 604800;
            case '30d':
                return $now - 2592000;
            default:
                return $now - 3600;
        }
    }
    
    /**
     * Check if action requires database access
     */
    public function requiresDatabaseAccess($action) {
        $database_actions = array(
            'quarantine' => true,
            'emergency_containment' => true,
            'restrict' => true
        );
        
        return isset($database_actions[$action]);
    }
    
    /**
     * Check if action blocks semantic operations
     */
    public function blocksSemanticOperations($action) {
        $semantic_blocking_actions = array(
            'quarantine' => true,
            'emergency_containment' => true,
            'restrict' => false
        );
        
        return isset($semantic_blocking_actions[$action]);
    }
    
    /**
     * Get security priority for action
     */
    public function getSecurityPriority($action) {
        $priority_levels = array(
            'emergency_containment' => 1,
            'quarantine' => 2,
            'restrict' => 3,
            'monitor' => 4,
            'allow' => 5
        );
        
        return isset($priority_levels[$action]) ? $priority_levels[$action] : 5;
    }
    
    /**
     * Get timeout for action based on threat level
     */
    public function getActionTimeout($action, $threat_level) {
        $timeouts = array(
            'critical' => 0, // Immediate
            'high' => 300, // 5 minutes
            'medium' => 900, // 15 minutes
            'low' => 1800, // 30 minutes
            'unknown' => 300 // 5 minutes
        );
        
        return isset($timeouts[$threat_level]) ? $timeouts[$threat_level] : 300;
    }
    
    /**
     * Validate security context requirements
     */
    public function validateSecurityContext($context) {
        $required_fields = array(
            'actor_id',
            'channel_id',
            'security_level',
            'semantic_signature'
        );
        
        foreach ($required_fields as $field) {
            if (!isset($context[$field])) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Create security decision record
     */
    public function createSecurityDecision($content, $decision, $context = array()) {
        $this->database->insert(LUPO_TABLE_PREFIX . 'security_decisions', array(
            'threat_level' => $decision['threat_level'] ?? 'unknown',
            'action' => $decision['action'] ?? 'unknown',
            'status' => $decision['status'] ?? 'unknown',
            'content_hash' => md5($content ?? ''),
            'decision_data' => json_encode($decision),
            'context_data' => json_encode($context),
            'created_ymdhis' => gmdate('YmdHis')
        ));
    }
    
    /**
     * Update security decision record
     */
    public function updateSecurityDecision($decision_id, $decision_data) {
        $update_data['updated_ymdhis'] = gmdate('YmdHis');
        
        $where_clause = "decision_id = :decision_id";
        
        $this->database->update(LUPO_TABLE_PREFIX . 'security_decisions', $update_data, $where_clause, array(
            'decision_id' => $decision_id
        ));
    }
    
    /**
     * Get security decision by ID
     */
    public function getSecurityDecision($decision_id) {
        $query = "SELECT * FROM " . LUPO_TABLE_PREFIX . "security_decisions 
                  WHERE decision_id = :decision_id";
        
        $stmt = $this->database->prepare($query);
        $stmt->execute(array('decision_id' => $decision_id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Delete security decision record
     */
    public function deleteSecurityDecision($decision_id) {
        $update_data = array(
            'is_active' => 0,
            'deleted_ymdhis' => gmdate('YmdHis')
        );
        
        $where_clause = "decision_id = :decision_id";
        
        $this->database->update(LUPO_TABLE_PREFIX . 'security_decisions', $update_data, $where_clause, array(
            'decision_id' => $decision_id
        ));
    }
    
    /**
     * Get security decisions by actor
     */
    public function getSecurityDecisionsByActor($actor_id, $limit = 50) {
        $query = "SELECT * FROM " . LUPO_TABLE_PREFIX . "security_decisions 
                  WHERE from_actor_id = :actor_id 
                  ORDER BY created_ymdhis DESC 
                  LIMIT :limit";
        
        $stmt = $this->database->prepare($query);
        $stmt->execute(array('actor_id' => $actor_id, 'limit' => $limit));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get security decisions by threat level
     */
    public function getSecurityDecisionsByThreatLevel($threat_level, $limit = 50) {
        $query = "SELECT * FROM " . LUPO_TABLE_PREFIX . "security_decisions 
                  WHERE threat_level = :threat_level 
                  ORDER BY created_ymdhis DESC 
                  LIMIT :limit";
        
        $stmt = $this->database->prepare($query);
        $stmt->execute(array('threat_level' => $threat_level, 'limit' => $limit));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get security decisions by action
     */
    public function getSecurityDecisionsByAction($action, $limit = 50) {
        $query = "SELECT * FROM " . LUPO_TABLE . "security_decisions 
                  WHERE action = :action 
                  ORDER BY created_ymdhis DESC 
                  LIMIT :limit";
        
        $stmt = $this->database->prepare($query);
        $stmt->execute(array('action' => $action, 'limit' => $limit));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get recent security events
     */
    public function getRecentSecurityEvents($limit = 100) {
        $query = "SELECT * FROM " . LUPO_TABLE_PREFIX . "security_events 
                  ORDER BY created_ymdhis DESC 
                  LIMIT :limit";
        
        $stmt = $this->database->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get security metrics for dashboard
     */
    public function getSecurityMetrics() {
        $metrics = array(
            'total_decisions' => 0,
            'threat_distribution' => array(),
            'action_distribution' => array(),
            'compliance_rate' => 0,
            'average_quarantine_time' => 0,
            'active_quarantines' => 0,
            'monitoring_level_distribution' => array(),
            'top_threats' => array(),
            'recent_events' => array()
        );
        
        // Get total decisions
        $query = "SELECT COUNT(*) as total FROM " . LUPO_TABLE_PREFIX . "security_decisions";
        $stmt = $this->database->prepare($query);
        $stmt->execute();
        $metrics['total_decisions'] = $stmt->fetchColumn();
        
        // Get threat distribution
        $query = "SELECT threat_level, COUNT(*) as count FROM " . LUPO_TABLE_PREFIX . "security_decisions 
                  GROUP BY threat_level";
        $stmt = $this->database->prepare($query);
        $stmt->execute();
        $metrics['threat_distribution'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get action distribution
        $query = "SELECT action, COUNT(*) as count FROM " . LUPO_TABLE_PREFIX . "security_decisions 
                  GROUP BY action";
        $stmt = $this->database->prepare($query);
        $stmt->events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $metrics['action_distribution'] = $stmt->events;
        
        // Get compliance rate
        $compliance_query = "SELECT COUNT(*) as total, 
                           SUM(CASE WHEN status = 'safe' THEN 1 ELSE 0 END) as compliant 
                           FROM " . LUPO_TABLE_PREFIX . "security_decisions 
                           WHERE created_ymdhis >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
        $stmt = $this->database->prepare($compliance_query);
        $stmt->execute();
        $compliance = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($compliance['total'] > 0) {
            $metrics['compliance_rate'] = ($compliance['compliant'] / $compliance['total']) * 100;
        }
        
        // Get active quarantines
        $quarantine_query = "SELECT COUNT(*) as active FROM " . LUPO_TABLE_PREFIX . "security_quarantine 
                  WHERE is_active = 1 AND 
                          created_ymdhis >= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        $stmt = $this->database->prepare($quarantine_query);
        $stmt->execute();
        $metrics['active_quarantines'] = $stmt->fetchColumn();
        
        // Get monitoring level distribution
        $monitoring_query = "SELECT monitoring_level, COUNT(*) as count FROM " . LUPO_TABLE_PREFIX . "security_decisions 
                  WHERE created_ymdhis >= DATE_SUB(NOW(), 1 DAY) 
                  GROUP BY monitoring_level";
        $stmt = $this->database->prepare($monitoring_query);
        $stmt->execute();
        $metrics['monitoring_level_distribution'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get top threats
        $top_threats_query = "SELECT threat_level, COUNT(*) as count FROM " . LUPO_TABLE_PREFIX . "security_decisions 
                        WHERE created_ymdhis >= DATE_SUB(NOW(), 1 DAY) 
                        GROUP BY threat_level 
                        ORDER BY count DESC 
                        LIMIT 10";
        $stmt = $this->database->prepare($top_threats_query);
        $stmt->execute();
        $metrics['top_threats'] = $stmt->fetchAll(PDO::FCOL FETCH_ASSOC);
        
        // Get recent events
        $recent_events_query = "SELECT * FROM " . LUPO_TABLE_PREFIX . "security_events 
                        ORDER BY created_ymdhis DESC 
                        LIMIT 100";
        $stmt = $this->database->prepare($recent_events_query);
        $stmt->execute();
        $metrics['recent_events'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $metrics;
    }
}
