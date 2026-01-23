<?php

/**
 * Lupopedia Emergent Role Discovery System
 * 
 * Implements the Emergence Pillar: Roles are discovered, not assigned.
 * Agents reveal their function under pressure. Structure emerges from interaction.
 * 
 * @package Lupopedia
 * @version 1.0.0
 * @author Captain Wolfie
 * @doctrine Genesis Doctrine v1.0.0 - Emergence Pillar
 */

class EmergentRoleDiscovery
{
    private $db;
    private $current_timestamp;
    private $role_patterns = [];
    private $interaction_thresholds = [];
    
    public function __construct($database_connection)
    {
        $this->db = $database_connection;
        $this->current_timestamp = date('YmdHis');
        $this->initializeRolePatterns();
        $this->initializeInteractionThresholds();
    }
    
    /**
     * Initialize known role patterns based on interaction behaviors
     */
    private function initializeRolePatterns()
    {
        $this->role_patterns = [
            'mediator' => [
                'behaviors' => ['resolves_conflicts', 'bridges_gaps', 'facilitates_communication'],
                'interaction_types' => ['balances', 'connects', 'harmonizes'],
                'pressure_response' => 'increases_coordination'
            ],
            'critic' => [
                'behaviors' => ['questions_assumptions', 'identifies_flaws', 'provides_alternatives'],
                'interaction_types' => ['challenges', 'questions', 'refines'],
                'pressure_response' => 'increases_analysis'
            ],
            'synthesizer' => [
                'behaviors' => ['combines_ideas', 'finds_patterns', 'creates_integrations'],
                'interaction_types' => ['merges', 'integrates', 'unifies'],
                'pressure_response' => 'increases_creativity'
            ],
            'executor' => [
                'behaviors' => ['implements_solutions', 'completes_tasks', 'drives_progress'],
                'interaction_types' => ['executes', 'completes', 'advances'],
                'pressure_response' => 'increases_productivity'
            ],
            'guardian' => [
                'behaviors' => ['protects_system', 'maintains_stability', 'ensures_compliance'],
                'interaction_types' => ['protects', 'stabilizes', 'enforces'],
                'pressure_response' => 'increases_security'
            ],
            'explorer' => [
                'behaviors' => ['discovers_possibilities', 'experiments_with_approaches', 'expands_boundaries'],
                'interaction_types' => ['explores', 'discovers', 'expands'],
                'pressure_response' => 'increases_innovation'
            ]
        ];
    }
    
    /**
     * Initialize interaction thresholds for role discovery
     */
    private function initializeInteractionThresholds()
    {
        $this->interaction_thresholds = [
            'min_interactions' => 10,        // Minimum interactions before role consideration
            'role_confidence_threshold' => 0.7,  // Confidence threshold for role assignment
            'pressure_sensitivity' => 0.5,   // How sensitive role discovery is to pressure
            'temporal_window' => 86400,     // 24 hours in seconds for interaction analysis
            'diversity_requirement' => 3    // Minimum different interaction types
        ];
    }
    
    /**
     * Analyze agent interactions to discover emergent roles
     */
    public function discoverEmergentRoles($actor_id, $pressure_context = null)
    {
        $discovery_result = [
            'actor_id' => $actor_id,
            'emergent_roles' => [],
            'confidence_scores' => [],
            'interaction_patterns' => [],
            'pressure_response' => [],
            'timestamp' => $this->current_timestamp
        ];
        
        // Get recent interaction patterns
        $interaction_patterns = $this->analyzeInteractionPatterns($actor_id);
        $discovery_result['interaction_patterns'] = $interaction_patterns;
        
        // Apply pressure context if provided
        if ($pressure_context) {
            $pressure_response = $this->analyzePressureResponse($actor_id, $pressure_context);
            $discovery_result['pressure_response'] = $pressure_response;
        }
        
        // Discover roles based on patterns
        foreach ($this->role_patterns as $role_name => $role_pattern) {
            $confidence = $this->calculateRoleConfidence($interaction_patterns, $role_pattern, $pressure_response ?? []);
            
            if ($confidence >= $this->interaction_thresholds['role_confidence_threshold']) {
                $discovery_result['emergent_roles'][] = $role_name;
                $discovery_result['confidence_scores'][$role_name] = $confidence;
            }
        }
        
        // Record emergent role discovery
        $this->recordEmergentRoleDiscovery($discovery_result);
        
        return $discovery_result;
    }
    
    /**
     * Analyze interaction patterns for an actor
     */
    private function analyzeInteractionPatterns($actor_id)
    {
        $patterns = [
            'total_interactions' => 0,
            'interaction_types' => [],
            'behaviors' => [],
            'frequency' => [],
            'diversity_score' => 0,
            'temporal_patterns' => []
        ];
        
        // Get actor edges from the database
        $sql = "
            SELECT 
                edge_type,
                COUNT(*) as count,
                properties,
                created_ymdhis
            FROM lupo_actor_edges 
            WHERE (source_actor_id = ? OR target_actor_id = ?)
            AND is_deleted = 0
            AND created_ymdhis >= ?
            GROUP BY edge_type
            ORDER BY count DESC
        ";
        
        $temporal_window = $this->current_timestamp - $this->interaction_thresholds['temporal_window'];
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('iii', $actor_id, $actor_id, $temporal_window);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $edge_type = $row['edge_type'];
                $count = $row['count'];
                $properties = json_decode($row['properties'] ?? '{}', true);
                
                $patterns['interaction_types'][$edge_type] = $count;
                $patterns['total_interactions'] += $count;
                
                // Extract behaviors from properties
                if (isset($properties['behaviors'])) {
                    foreach ($properties['behaviors'] as $behavior) {
                        if (!isset($patterns['behaviors'][$behavior])) {
                            $patterns['behaviors'][$behavior] = 0;
                        }
                        $patterns['behaviors'][$behavior]++;
                    }
                }
            }
            
            // Calculate diversity score
            $patterns['diversity_score'] = count($patterns['interaction_types']);
            
        } catch (Exception $e) {
            // Log error but continue with empty patterns
            error_log("Failed to analyze interaction patterns: " . $e->getMessage());
        }
        
        return $patterns;
    }
    
    /**
     * Analyze how actor responds under pressure
     */
    private function analyzePressureResponse($actor_id, $pressure_context)
    {
        $response = [
            'pressure_type' => $pressure_context['type'] ?? 'unknown',
            'response_intensity' => 0,
            'adaptation_speed' => 0,
            'behavioral_shifts' => []
        ];
        
        // Get interactions during pressure periods
        $sql = "
            SELECT 
                edge_type,
                properties,
                created_ymdhis
            FROM lupo_actor_edges 
            WHERE (source_actor_id = ? OR target_actor_id = ?)
            AND is_deleted = 0
            AND created_ymdhis BETWEEN ? AND ?
            ORDER BY created_ymdhis ASC
        ";
        
        $start_time = $pressure_context['start_time'] ?? ($this->current_timestamp - 3600);
        $end_time = $pressure_context['end_time'] ?? $this->current_timestamp;
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('iiii', $actor_id, $actor_id, $start_time, $end_time);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $interactions = [];
            while ($row = $result->fetch_assoc()) {
                $interactions[] = [
                    'edge_type' => $row['edge_type'],
                    'properties' => json_decode($row['properties'] ?? '{}', true),
                    'timestamp' => $row['created_ymdhis']
                ];
            }
            
            // Analyze response patterns
            $response = $this->calculatePressureResponseMetrics($interactions, $pressure_context);
            
        } catch (Exception $e) {
            error_log("Failed to analyze pressure response: " . $e->getMessage());
        }
        
        return $response;
    }
    
    /**
     * Calculate pressure response metrics
     */
    private function calculatePressureResponseMetrics($interactions, $pressure_context)
    {
        $metrics = [
            'pressure_type' => $pressure_context['type'] ?? 'unknown',
            'response_intensity' => 0,
            'adaptation_speed' => 0,
            'behavioral_shifts' => []
        ];
        
        if (empty($interactions)) {
            return $metrics;
        }
        
        // Calculate response intensity based on interaction frequency
        $time_span = end($interactions)['timestamp'] - $interactions[0]['timestamp'];
        if ($time_span > 0) {
            $metrics['response_intensity'] = count($interactions) / ($time_span / 3600); // interactions per hour
        }
        
        // Analyze behavioral shifts
        $before_pressure = [];
        $during_pressure = [];
        
        foreach ($interactions as $interaction) {
            if ($interaction['timestamp'] < $pressure_context['start_time']) {
                $before_pressure[] = $interaction['edge_type'];
            } else {
                $during_pressure[] = $interaction['edge_type'];
            }
        }
        
        $before_types = array_unique($before_pressure);
        $during_types = array_unique($during_pressure);
        
        $metrics['behavioral_shifts'] = [
            'new_behaviors' => array_diff($during_types, $before_types),
            'abandoned_behaviors' => array_diff($before_types, $during_types),
            'persistent_behaviors' => array_intersect($before_types, $during_types)
        ];
        
        // Calculate adaptation speed
        if (!empty($metrics['behavioral_shifts']['new_behaviors'])) {
            $first_new_behavior = null;
            foreach ($interactions as $interaction) {
                if ($interaction['timestamp'] >= $pressure_context['start_time'] && 
                    in_array($interaction['edge_type'], $metrics['behavioral_shifts']['new_behaviors'])) {
                    $first_new_behavior = $interaction['timestamp'];
                    break;
                }
            }
            
            if ($first_new_behavior) {
                $metrics['adaptation_speed'] = $first_new_behavior - $pressure_context['start_time'];
            }
        }
        
        return $metrics;
    }
    
    /**
     * Calculate confidence score for a specific role
     */
    private function calculateRoleConfidence($interaction_patterns, $role_pattern, $pressure_response)
    {
        $confidence = 0;
        $factors = 0;
        
        // Factor 1: Behavior matching
        if (!empty($interaction_patterns['behaviors'])) {
            $behavior_matches = 0;
            foreach ($role_pattern['behaviors'] as $required_behavior) {
                if (isset($interaction_patterns['behaviors'][$required_behavior])) {
                    $behavior_matches++;
                }
            }
            $behavior_score = $behavior_matches / count($role_pattern['behaviors']);
            $confidence += $behavior_score * 0.4; // 40% weight
            $factors++;
        }
        
        // Factor 2: Interaction type matching
        if (!empty($interaction_patterns['interaction_types'])) {
            $interaction_matches = 0;
            foreach ($role_pattern['interaction_types'] as $required_type) {
                if (isset($interaction_patterns['interaction_types'][$required_type])) {
                    $interaction_matches++;
                }
            }
            $interaction_score = $interaction_matches / count($role_pattern['interaction_types']);
            $confidence += $interaction_score * 0.3; // 30% weight
            $factors++;
        }
        
        // Factor 3: Pressure response matching
        if (!empty($pressure_response) && isset($role_pattern['pressure_response'])) {
            $pressure_match = 0;
            if ($pressure_response['response_intensity'] > 0) {
                $pressure_match = 1; // Positive response to pressure
            }
            $confidence += $pressure_match * 0.2; // 20% weight
            $factors++;
        }
        
        // Factor 4: Diversity requirement
        if ($interaction_patterns['diversity_score'] >= $this->interaction_thresholds['diversity_requirement']) {
            $confidence += 0.1; // 10% weight
            $factors++;
        }
        
        // Normalize confidence
        if ($factors > 0) {
            $confidence = $confidence / $factors;
        }
        
        return $confidence;
    }
    
    /**
     * Record emergent role discovery in database
     */
    private function recordEmergentRoleDiscovery($discovery_result)
    {
        $sql = "
            INSERT INTO lupo_emergent_role_discoveries 
            (actor_id, emergent_roles, confidence_scores, interaction_patterns, pressure_response, created_ymdhis) 
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param(
                'isssss',
                $discovery_result['actor_id'],
                json_encode($discovery_result['emergent_roles']),
                json_encode($discovery_result['confidence_scores']),
                json_encode($discovery_result['interaction_patterns']),
                json_encode($discovery_result['pressure_response']),
                $this->current_timestamp
            );
            $stmt->execute();
            
            // Update actor's emergent roles in main actors table
            $this->updateActorEmergentRoles($discovery_result['actor_id'], $discovery_result['emergent_roles']);
            
        } catch (Exception $e) {
            error_log("Failed to record emergent role discovery: " . $e->getMessage());
        }
    }
    
    /**
     * Update actor's emergent roles in main actors table
     */
    private function updateActorEmergentRoles($actor_id, $emergent_roles)
    {
        $sql = "
            UPDATE lupo_actors 
            SET metadata = JSON_SET(
                COALESCE(metadata, '{}'),
                '$.emergent_roles',
                ?
            ),
            updated_ymdhis = ?
            WHERE actor_id = ?
        ";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ssi', json_encode($emergent_roles), $this->current_timestamp, $actor_id);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Failed to update actor emergent roles: " . $e->getMessage());
        }
    }
    
    /**
     * Get emergent role history for an actor
     */
    public function getEmergentRoleHistory($actor_id)
    {
        $sql = "
            SELECT * FROM lupo_emergent_role_discoveries 
            WHERE actor_id = ? 
            ORDER BY created_ymdhis DESC
        ";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $actor_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $history = [];
            while ($row = $result->fetch_assoc()) {
                $history[] = [
                    'discovery_id' => $row['discovery_id'],
                    'actor_id' => $row['actor_id'],
                    'emergent_roles' => json_decode($row['emergent_roles'], true),
                    'confidence_scores' => json_decode($row['confidence_scores'], true),
                    'interaction_patterns' => json_decode($row['interaction_patterns'], true),
                    'pressure_response' => json_decode($row['pressure_response'], true),
                    'created_ymdhis' => $row['created_ymdhis']
                ];
            }
            
            return $history;
        } catch (Exception $e) {
            error_log("Failed to get emergent role history: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Apply pressure context to trigger role discovery
     */
    public function applyPressureContext($pressure_context)
    {
        $affected_actors = [];
        
        // Get actors in the pressure context domain/scope
        $sql = "
            SELECT DISTINCT actor_id 
            FROM lupo_actor_edges 
            WHERE domain_id = ? 
            AND is_deleted = 0
            AND created_ymdhis >= ?
        ";
        
        $temporal_window = $this->current_timestamp - $this->interaction_thresholds['temporal_window'];
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ii', $pressure_context['domain_id'], $temporal_window);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $actor_id = $row['actor_id'];
                $discovery = $this->discoverEmergentRoles($actor_id, $pressure_context);
                
                if (!empty($discovery['emergent_roles'])) {
                    $affected_actors[] = [
                        'actor_id' => $actor_id,
                        'discovered_roles' => $discovery['emergent_roles'],
                        'confidence_scores' => $discovery['confidence_scores']
                    ];
                }
            }
            
        } catch (Exception $e) {
            error_log("Failed to apply pressure context: " . $e->getMessage());
        }
        
        return $affected_actors;
    }
}
