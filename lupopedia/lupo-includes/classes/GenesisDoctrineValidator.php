<?php

/**
 * Lupopedia Genesis Doctrine Validator
 * 
 * Implements the Five Pillars validation and First Expansion Principle checks.
 * Enforces the Three Litmus Tests for all new features.
 * 
 * @package Lupopedia
 * @version 1.0.0
 * @author Captain Wolfie
 * @doctrine Genesis Doctrine v1.0.0
 */

class GenesisDoctrineValidator
{
    private $db;
    private $current_timestamp;
    private $errors = [];
    private $warnings = [];
    
    // Pillar validation constants
    const ACTOR_PILLAR = 'actor';
    const TEMPORAL_PILLAR = 'temporal';
    const EDGE_PILLAR = 'edge';
    const DOCTRINE_PILLAR = 'doctrine';
    const EMERGENCE_PILLAR = 'emergence';
    
    public function __construct($database_connection)
    {
        $this->db = $database_connection;
        $this->current_timestamp = date('YmdHis');
    }
    
    /**
     * Main validation entry point - applies all Five Pillars and Litmus Tests
     */
    public function validateFeature($feature_specification)
    {
        $this->errors = [];
        $this->warnings = [];
        
        $result = [
            'valid' => true,
            'errors' => [],
            'warnings' => [],
            'pillar_compliance' => [],
            'litmus_results' => [],
            'expansion_validation' => [],
            'timestamp' => $this->current_timestamp
        ];
        
        // Apply Three Litmus Tests
        $litmus_results = $this->applyLitmusTests($feature_specification);
        $result['litmus_results'] = $litmus_results;
        
        if (!$litmus_results['passed']) {
            $result['valid'] = false;
            $result['errors'] = array_merge($result['errors'], $litmus_results['errors']);
        }
        
        // Apply Five Pillars validation
        $pillar_compliance = $this->validateFivePillars($feature_specification);
        $result['pillar_compliance'] = $pillar_compliance;
        
        foreach ($pillar_compliance as $pillar => $compliance) {
            if (!$compliance['compliant']) {
                $result['valid'] = false;
                $result['errors'] = array_merge($result['errors'], $compliance['violations']);
            }
            $result['warnings'] = array_merge($result['warnings'], $compliance['warnings']);
        }
        
        // Apply First Expansion Principle
        $expansion_validation = $this->validateFirstExpansionPrinciple($feature_specification);
        $result['expansion_validation'] = $expansion_validation;
        
        if (!$expansion_validation['valid']) {
            $result['valid'] = false;
            $result['errors'] = array_merge($result['errors'], $expansion_validation['errors']);
        }
        
        $result['errors'] = array_unique($result['errors']);
        $result['warnings'] = array_unique($result['warnings']);
        
        return $result;
    }
    
    /**
     * THE THREE LITMUS TESTS
     * Before any new feature, ask these three questions
     */
    private function applyLitmusTests($feature_specification)
    {
        $results = [
            'passed' => true,
            'errors' => [],
            'test_results' => []
        ];
        
        // 1. THE ACTOR TEST
        $actor_test = $this->performActorTest($feature_specification);
        $results['test_results']['actor_test'] = $actor_test;
        if (!$actor_test['passed']) {
            $results['passed'] = false;
            $results['errors'][] = 'ACTOR TEST FAILED: ' . $actor_test['reason'];
        }
        
        // 2. THE TEMPORAL TEST
        $temporal_test = $this->performTemporalTest($feature_specification);
        $results['test_results']['temporal_test'] = $temporal_test;
        if (!$temporal_test['passed']) {
            $results['passed'] = false;
            $results['errors'][] = 'TEMPORAL TEST FAILED: ' . $temporal_test['reason'];
        }
        
        // 3. THE DOCTRINE TEST
        $doctrine_test = $this->performDoctrineTest($feature_specification);
        $results['test_results']['doctrine_test'] = $doctrine_test;
        if (!$doctrine_test['passed']) {
            $results['passed'] = false;
            $results['errors'][] = 'DOCTRINE TEST FAILED: ' . $doctrine_test['reason'];
        }
        
        return $results;
    }
    
    /**
     * 1. THE ACTOR TEST
     * Does this feature make sense in terms of actors, edges, and identity?
     */
    private function performActorTest($feature_specification)
    {
        $test_result = [
            'passed' => true,
            'reason' => '',
            'analysis' => []
        ];
        
        // Check if feature can be expressed as actors and edges
        if (!isset($feature_specification['actors']) && !isset($feature_specification['actor_interactions'])) {
            $test_result['passed'] = false;
            $test_result['reason'] = 'Feature cannot be expressed as actors and edges';
            return $test_result;
        }
        
        // Validate actor presence
        if (isset($feature_specification['actors'])) {
            foreach ($feature_specification['actors'] as $actor) {
                if (!isset($actor['type']) || !isset($actor['identity'])) {
                    $test_result['passed'] = false;
                    $test_result['reason'] = 'Actor missing type or identity';
                    return $test_result;
                }
            }
        }
        
        // Check for anonymous influence (violation of Actor Pillar)
        if (isset($feature_specification['anonymous_actions']) && $feature_specification['anonymous_actions']) {
            $test_result['passed'] = false;
            $test_result['reason'] = 'Feature allows anonymous influence - violates Actor Pillar';
            return $test_result;
        }
        
        $test_result['analysis']['actors_identified'] = count($feature_specification['actors'] ?? []);
        $test_result['analysis']['edges_defined'] = isset($feature_specification['actor_interactions']);
        
        return $test_result;
    }
    
    /**
     * 2. THE TEMPORAL TEST
     * Does this feature respect time, probability, and drift?
     */
    private function performTemporalTest($feature_specification)
    {
        $test_result = [
            'passed' => true,
            'reason' => '',
            'analysis' => []
        ];
        
        // Check for temporal awareness
        if (!isset($feature_specification['temporal_aspect'])) {
            $test_result['passed'] = false;
            $test_result['reason'] = 'Feature ignores temporal engine - will corrupt meaning';
            return $test_result;
        }
        
        // Validate UTC timestamp usage
        if (isset($feature_specification['data_structures'])) {
            foreach ($feature_specification['data_structures'] as $structure) {
                if (isset($structure['timestamps'])) {
                    foreach ($structure['timestamps'] as $timestamp_field) {
                        if (!isset($timestamp_field['utc']) || !$timestamp_field['utc']) {
                            $test_result['passed'] = false;
                            $test_result['reason'] = 'Non-UTC timestamp found - violates Temporal Pillar';
                            return $test_result;
                        }
                    }
                }
            }
        }
        
        // Check for probability awareness
        if (isset($feature_specification['probabilistic_elements'])) {
            foreach ($feature_specification['probabilistic_elements'] as $element) {
                if (!isset($element['probability'])) {
                    $test_result['warnings'][] = 'Probabilistic element missing explicit probability';
                }
            }
        }
        
        $test_result['analysis']['temporal_awareness'] = true;
        $test_result['analysis']['utc_compliance'] = true;
        
        return $test_result;
    }
    
    /**
     * 3. THE DOCTRINE TEST
     * Can this feature be explained in one paragraph of doctrine?
     */
    private function performDoctrineTest($feature_specification)
    {
        $test_result = [
            'passed' => true,
            'reason' => '',
            'analysis' => []
        ];
        
        // Check for doctrine explanation
        if (!isset($feature_specification['doctrine_explanation'])) {
            $test_result['passed'] = false;
            $test_result['reason'] = 'Feature cannot be explained in doctrine - cannot be governed';
            return $test_result;
        }
        
        $explanation = $feature_specification['doctrine_explanation'];
        
        // Check explanation length (one paragraph guideline)
        if (str_word_count($explanation) > 100) {
            $test_result['warnings'][] = 'Doctrine explanation exceeds one paragraph guideline';
        }
        
        // Check for key doctrinal elements
        $required_elements = ['purpose', 'scope', 'governance'];
        foreach ($required_elements as $element) {
            if (!isset($feature_specification[$element])) {
                $test_result['warnings'][] = "Missing doctrinal element: {$element}";
            }
        }
        
        $test_result['analysis']['explanation_provided'] = true;
        $test_result['analysis']['explanation_length'] = str_word_count($explanation);
        
        return $test_result;
    }
    
    /**
     * Validate compliance with all Five Pillars
     */
    private function validateFivePillars($feature_specification)
    {
        $compliance = [];
        
        $compliance[self::ACTOR_PILLAR] = $this->validateActorPillar($feature_specification);
        $compliance[self::TEMPORAL_PILLAR] = $this->validateTemporalPillar($feature_specification);
        $compliance[self::EDGE_PILLAR] = $this->validateEdgePillar($feature_specification);
        $compliance[self::DOCTRINE_PILLAR] = $this->validateDoctrinePillar($feature_specification);
        $compliance[self::EMERGENCE_PILLAR] = $this->validateEmergencePillar($feature_specification);
        
        return $compliance;
    }
    
    /**
     * ACTOR PILLAR — IDENTITY IS PRIMARY
     * Everything is an actor. Every action is a handshake. Every relationship is an edge.
     */
    private function validateActorPillar($feature_specification)
    {
        $result = [
            'compliant' => true,
            'violations' => [],
            'warnings' => []
        ];
        
        // Inviolability: No action without an actor
        if (isset($feature_specification['actions'])) {
            foreach ($feature_specification['actions'] as $action) {
                if (!isset($action['actor'])) {
                    $result['compliant'] = false;
                    $result['violations'][] = 'Action without actor detected';
                }
            }
        }
        
        // Inviolability: No hidden actors
        if (isset($feature_specification['hidden_actors']) && $feature_specification['hidden_actors']) {
            $result['compliant'] = false;
            $result['violations'][] = 'Hidden actors detected - violates Actor Pillar';
        }
        
        // Inviolability: No anonymous influence
        if (isset($feature_specification['anonymous_influence']) && $feature_specification['anonymous_influence']) {
            $result['compliant'] = false;
            $result['violations'][] = 'Anonymous influence detected - violates Actor Pillar';
        }
        
        return $result;
    }
    
    /**
     * TEMPORAL PILLAR — TIME IS THE SPINE
     * Events have probability, drift, and convergence. 95% = already happened.
     */
    private function validateTemporalPillar($feature_specification)
    {
        $result = [
            'compliant' => true,
            'violations' => [],
            'warnings' => []
        ];
        
        // Inviolability: All events must have canonical UTC
        if (isset($feature_specification['events'])) {
            foreach ($feature_specification['events'] as $event) {
                if (!isset($event['timestamp_utc'])) {
                    $result['compliant'] = false;
                    $result['violations'][] = 'Event without canonical UTC timestamp';
                }
            }
        }
        
        // Inviolability: Probability must be explicit
        if (isset($feature_specification['probabilistic_elements'])) {
            foreach ($feature_specification['probabilistic_elements'] as $element) {
                if (!isset($element['probability'])) {
                    $result['compliant'] = false;
                    $result['violations'][] = 'Probabilistic element without explicit probability';
                }
            }
        }
        
        return $result;
    }
    
    /**
     * EDGE PILLAR — RELATIONSHIPS ARE MEANING
     * Actors are nodes. Edges are story. The graph is the semantic fabric.
     */
    private function validateEdgePillar($feature_specification)
    {
        $result = [
            'compliant' => true,
            'violations' => [],
            'warnings' => []
        ];
        
        // Inviolability: No edge without intent
        if (isset($feature_specification['edges'])) {
            foreach ($feature_specification['edges'] as $edge) {
                if (!isset($edge['intent'])) {
                    $result['compliant'] = false;
                    $result['violations'][] = 'Edge without intent detected';
                }
                
                // Inviolability: No edge without type
                if (!isset($edge['type'])) {
                    $result['compliant'] = false;
                    $result['violations'][] = 'Edge without type detected';
                }
                
                // Inviolability: No edge without timestamp
                if (!isset($edge['timestamp'])) {
                    $result['compliant'] = false;
                    $result['violations'][] = 'Edge without timestamp detected';
                }
            }
        }
        
        return $result;
    }
    
    /**
     * DOCTRINE PILLAR — LAW PREVENTS DRIFT
     * Doctrine is the operating system. It must be explicit, versioned, and enforceable.
     */
    private function validateDoctrinePillar($feature_specification)
    {
        $result = [
            'compliant' => true,
            'violations' => [],
            'warnings' => []
        ];
        
        // Inviolability: Doctrine must be explicit
        if (!isset($feature_specification['doctrine_explanation'])) {
            $result['compliant'] = false;
            $result['violations'][] = 'Missing explicit doctrine explanation';
        }
        
        // Inviolability: Doctrine must be versioned
        if (!isset($feature_specification['doctrine_version'])) {
            $result['compliant'] = false;
            $result['violations'][] = 'Missing doctrine version';
        }
        
        // Inviolability: Doctrine must be enforceable
        if (!isset($feature_specification['enforcement_rules'])) {
            $result['compliant'] = false;
            $result['violations'][] = 'Missing enforcement rules - doctrine not enforceable';
        }
        
        return $result;
    }
    
    /**
     * EMERGENCE PILLAR — ROLES ARE DISCOVERED, NOT ASSIGNED
     * Agents reveal their function under pressure. Structure emerges from interaction.
     */
    private function validateEmergencePillar($feature_specification)
    {
        $result = [
            'compliant' => true,
            'violations' => [],
            'warnings' => []
        ];
        
        // Inviolability: No preassigned roles
        if (isset($feature_specification['preassigned_roles']) && $feature_specification['preassigned_roles']) {
            $result['compliant'] = false;
            $result['violations'][] = 'Preassigned roles detected - violates Emergence Pillar';
        }
        
        // Inviolability: No forced behavior
        if (isset($feature_specification['forced_behavior']) && $feature_specification['forced_behavior']) {
            $result['compliant'] = false;
            $result['violations'][] = 'Forced behavior detected - violates Emergence Pillar';
        }
        
        // Inviolability: No artificial hierarchy
        if (isset($feature_specification['artificial_hierarchy']) && $feature_specification['artificial_hierarchy']) {
            $result['compliant'] = false;
            $result['violations'][] = 'Artificial hierarchy detected - violates Emergence Pillar';
        }
        
        // Check for emergent behavior mechanisms
        if (!isset($feature_specification['emergent_mechanisms'])) {
            $result['warnings'][] = 'No emergent behavior mechanisms defined';
        }
        
        return $result;
    }
    
    /**
     * FIRST EXPANSION PRINCIPLE
     * "Growth must increase meaning without increasing fragility."
     */
    private function validateFirstExpansionPrinciple($feature_specification)
    {
        $result = [
            'valid' => true,
            'errors' => [],
            'warnings' => []
        ];
        
        // 1. POLYMORPHISM — Supports unknown future types
        if (!isset($feature_specification['polymorphic_support'])) {
            $result['valid'] = false;
            $result['errors'][] = 'Feature lacks polymorphic support - will break with future types';
        }
        
        // 2. NON-INTERFERENCE — Does not break existing actors or edges
        if (!isset($feature_specification['interference_analysis'])) {
            $result['valid'] = false;
            $result['errors'][] = 'Missing interference analysis - may break existing system';
        }
        
        // 3. SELF-DESCRIPTION — Explainable through doctrine
        if (!isset($feature_specification['self_description'])) {
            $result['valid'] = false;
            $result['errors'][] = 'Feature cannot self-describe through doctrine';
        }
        
        // 4. TEMPORAL INTEGRITY — Obeys the canonical time model
        if (!isset($feature_specification['temporal_integrity'])) {
            $result['valid'] = false;
            $result['errors'][] = 'Feature violates canonical time model';
        }
        
        // 5. REVERSIBILITY — Removable without collapse
        if (!isset($feature_specification['reversibility_plan'])) {
            $result['valid'] = false;
            $result['errors'][] = 'Feature lacks reversibility plan - may cause system collapse';
        }
        
        return $result;
    }
    
    /**
     * Record validation results for governance tracking
     */
    public function recordValidationResult($feature_name, $validation_result, $actor_id)
    {
        $insert_sql = "
            INSERT INTO lupo_doctrine_validations 
            (feature_name, validation_result, actor_id, created_ymdhis) 
            VALUES (?, ?, ?, ?)
        ";
        
        $result_json = json_encode($validation_result);
        
        try {
            $stmt = $this->db->prepare($insert_sql);
            $stmt->bind_param('ssis', $feature_name, $result_json, $actor_id, $this->current_timestamp);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->errors[] = 'Failed to record validation result: ' . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Get validation history for a feature
     */
    public function getValidationHistory($feature_name)
    {
        $sql = "
            SELECT * FROM lupo_doctrine_validations 
            WHERE feature_name = ? 
            ORDER BY created_ymdhis DESC
        ";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('s', $feature_name);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $history = [];
            while ($row = $result->fetch_assoc()) {
                $history[] = [
                    'validation_id' => $row['validation_id'],
                    'feature_name' => $row['feature_name'],
                    'validation_result' => json_decode($row['validation_result'], true),
                    'actor_id' => $row['actor_id'],
                    'created_ymdhis' => $row['created_ymdhis']
                ];
            }
            
            return $history;
        } catch (Exception $e) {
            $this->errors[] = 'Failed to retrieve validation history: ' . $e->getMessage();
            return [];
        }
    }
}
