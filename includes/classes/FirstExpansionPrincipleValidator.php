<?php

/**
 * Lupopedia First Expansion Principle Validator
 * 
 * Implements the First Expansion Principle validation:
 * "Growth must increase meaning without increasing fragility."
 * 
 * Validates that new features satisfy:
 * 1. POLYMORPHISM — Supports unknown future types
 * 2. NON-INTERFERENCE — Does not break existing actors or edges
 * 3. SELF-DESCRIPTION — Explainable through doctrine
 * 4. TEMPORAL INTEGRITY — Obeys the canonical time model
 * 5. REVERSIBILITY — Removable without collapse
 * 
 * @package Lupopedia
 * @version 1.0.0
 * @author Captain Wolfie
 * @doctrine Genesis Doctrine v1.0.0 - First Expansion Principle
 */

class FirstExpansionPrincipleValidator
{
    private $db;
    private $current_timestamp;
    private $validation_weights = [
        'polymorphism' => 0.25,
        'non_interference' => 0.25,
        'self_description' => 0.20,
        'temporal_integrity' => 0.15,
        'reversibility' => 0.15
    ];
    
    public function __construct($database_connection)
    {
        $this->db = $database_connection;
        $this->current_timestamp = date('YmdHis');
    }
    
    /**
     * Main validation method for the First Expansion Principle
     */
    public function validateExpansionPrinciple($feature_specification)
    {
        $validation_result = [
            'feature_name' => $feature_specification['name'] ?? 'unknown',
            'overall_score' => 0,
            'overall_status' => 'requires_review',
            'principle_scores' => [],
            'detailed_analysis' => [],
            'fragility_risks' => [],
            'meaning_increase_assessment' => [],
            'recommendations' => [],
            'timestamp' => $this->current_timestamp
        ];
        
        // Validate each principle component
        $principle_results = [
            'polymorphism' => $this->validatePolymorphism($feature_specification),
            'non_interference' => $this->validateNonInterference($feature_specification),
            'self_description' => $this->validateSelfDescription($feature_specification),
            'temporal_integrity' => $this->validateTemporalIntegrity($feature_specification),
            'reversibility' => $this->validateReversibility($feature_specification)
        ];
        
        $validation_result['principle_scores'] = $principle_results;
        
        // Calculate overall score
        $overall_score = 0;
        foreach ($principle_results as $principle => $result) {
            $score = $result['score'] ?? 0;
            $weight = $this->validation_weights[$principle];
            $overall_score += $score * $weight;
            
            // Collect detailed analysis
            $validation_result['detailed_analysis'][$principle] = $result['analysis'] ?? [];
            
            // Collect fragility risks
            if (isset($result['fragility_risks'])) {
                $validation_result['fragility_risks'] = array_merge(
                    $validation_result['fragility_risks'], 
                    $result['fragility_risks']
                );
            }
        }
        
        $validation_result['overall_score'] = round($overall_score, 2);
        
        // Assess meaning increase
        $validation_result['meaning_increase_assessment'] = $this->assessMeaningIncrease($feature_specification);
        
        // Determine overall status
        $validation_result['overall_status'] = $this->determineOverallStatus($validation_result);
        
        // Generate recommendations
        $validation_result['recommendations'] = $this->generateRecommendations($validation_result);
        
        // Record validation in database
        $this->recordExpansionValidation($validation_result, $feature_specification['validated_by'] ?? 1);
        
        return $validation_result;
    }
    
    /**
     * 1. POLYMORPHISM — Supports unknown future types
     */
    private function validatePolymorphism($feature_specification)
    {
        $result = [
            'score' => 0,
            'analysis' => [],
            'fragility_risks' => [],
            'compliance_level' => 'non_compliant'
        ];
        
        $score = 0;
        $analysis = [];
        $risks = [];
        
        // Check for flexible data structures
        if (isset($feature_specification['data_structures'])) {
            $polymorphic_structures = 0;
            $total_structures = count($feature_specification['data_structures']);
            
            foreach ($feature_specification['data_structures'] as $structure) {
                if (isset($structure['type_field']) && $structure['type_field']) {
                    $polymorphic_structures++;
                }
                if (isset($structure['json_properties']) && $structure['json_properties']) {
                    $polymorphic_structures++;
                }
                if (isset($structure['extensible']) && $structure['extensible']) {
                    $polymorphic_structures++;
                }
            }
            
            if ($total_structures > 0) {
                $polymorphism_ratio = $polymorphic_structures / ($total_structures * 3);
                $score += $polymorphism_ratio * 0.4;
                $analysis[] = "Polymorphic structures: {$polymorphic_structures}/{$total_structures}";
            }
        }
        
        // Check for type extensibility
        if (isset($feature_specification['type_system'])) {
            $type_system = $feature_specification['type_system'];
            
            if (isset($type_system['extensible']) && $type_system['extensible']) {
                $score += 0.3;
                $analysis[] = "Extensible type system detected";
            }
            
            if (isset($type_system['dynamic_types']) && $type_system['dynamic_types']) {
                $score += 0.2;
                $analysis[] = "Dynamic type support detected";
            }
            
            if (isset($type_system['version_compatibility']) && $type_system['version_compatibility']) {
                $score += 0.1;
                $analysis[] = "Version compatibility mechanisms detected";
            }
        }
        
        // Check for interface flexibility
        if (isset($feature_specification['interfaces'])) {
            $flexible_interfaces = 0;
            $total_interfaces = count($feature_specification['interfaces']);
            
            foreach ($feature_specification['interfaces'] as $interface) {
                if (isset($interface['backward_compatible']) && $interface['backward_compatible']) {
                    $flexible_interfaces++;
                }
                if (isset($interface['optional_parameters']) && $interface['optional_parameters']) {
                    $flexible_interfaces++;
                }
            }
            
            if ($total_interfaces > 0) {
                $interface_flexibility = $flexible_interfaces / ($total_interfaces * 2);
                $score += $interface_flexibility * 0.3;
                $analysis[] = "Flexible interfaces: {$flexible_interfaces}/{$total_interfaces}";
            }
        }
        
        // Identify fragility risks
        if ($score < 0.5) {
            $risks[] = "Low polymorphism - feature may break with future data types";
        }
        
        if (!isset($feature_specification['data_structures'])) {
            $risks[] = "No data structures defined - high rigidity risk";
        }
        
        $result['score'] = round(min($score, 1.0), 2);
        $result['analysis'] = $analysis;
        $result['fragility_risks'] = $risks;
        $result['compliance_level'] = $this->getComplianceLevel($score);
        
        return $result;
    }
    
    /**
     * 2. NON-INTERFERENCE — Does not break existing actors or edges
     */
    private function validateNonInterference($feature_specification)
    {
        $result = [
            'score' => 0,
            'analysis' => [],
            'fragility_risks' => [],
            'compliance_level' => 'non_compliant'
        ];
        
        $score = 0;
        $analysis = [];
        $risks = [];
        
        // Check for isolation mechanisms
        if (isset($feature_specification['isolation'])) {
            $isolation = $feature_specification['isolation'];
            
            if (isset($isolation['namespace_isolation']) && $isolation['namespace_isolation']) {
                $score += 0.3;
                $analysis[] = "Namespace isolation implemented";
            }
            
            if (isset($isolation['database_isolation']) && $isolation['database_isolation']) {
                $score += 0.3;
                $analysis[] = "Database isolation implemented";
            }
            
            if (isset($isolation['dependency_injection']) && $isolation['dependency_injection']) {
                $score += 0.2;
                $analysis[] = "Dependency injection for loose coupling";
            }
        }
        
        // Check for backward compatibility
        if (isset($feature_specification['backward_compatibility'])) {
            $compatibility = $feature_specification['backward_compatibility'];
            
            if (isset($compatibility['api_versioning']) && $compatibility['api_versioning']) {
                $score += 0.1;
                $analysis[] = "API versioning implemented";
            }
            
            if (isset($compatibility['data_migration']) && $compatibility['data_migration']) {
                $score += 0.1;
                $analysis[] = "Data migration paths defined";
            }
        }
        
        // Check for conflict analysis
        if (isset($feature_specification['conflict_analysis'])) {
            $conflict_analysis = $feature_specification['conflict_analysis'];
            
            if (isset($conflict_analysis['actor_conflicts']) && is_array($conflict_analysis['actor_conflicts'])) {
                if (empty($conflict_analysis['actor_conflicts'])) {
                    $score += 0.2;
                    $analysis[] = "No actor conflicts detected";
                } else {
                    $risks[] = "Actor conflicts detected: " . implode(', ', $conflict_analysis['actor_conflicts']);
                }
            }
            
            if (isset($conflict_analysis['edge_conflicts']) && is_array($conflict_analysis['edge_conflicts'])) {
                if (empty($conflict_analysis['edge_conflicts'])) {
                    $score += 0.2;
                    $analysis[] = "No edge conflicts detected";
                } else {
                    $risks[] = "Edge conflicts detected: " . implode(', ', $conflict_analysis['edge_conflicts']);
                }
            }
        }
        
        // Check for resource sharing analysis
        if (isset($feature_specification['resource_sharing'])) {
            $sharing = $feature_specification['resource_sharing'];
            
            if (isset($sharing['shared_resources']) && !empty($sharing['shared_resources'])) {
                foreach ($sharing['shared_resources'] as $resource) {
                    if (isset($resource['locking']) && $resource['locking']) {
                        $score += 0.1;
                        $analysis[] = "Resource locking implemented for: " . $resource['name'];
                    } else {
                        $risks[] = "Unlocked shared resource: " . $resource['name'];
                    }
                }
            }
        }
        
        // Identify fragility risks
        if ($score < 0.6) {
            $risks[] = "Insufficient isolation - may interfere with existing system";
        }
        
        if (!isset($feature_specification['isolation'])) {
            $risks[] = "No isolation mechanisms defined - high interference risk";
        }
        
        $result['score'] = round(min($score, 1.0), 2);
        $result['analysis'] = $analysis;
        $result['fragility_risks'] = $risks;
        $result['compliance_level'] = $this->getComplianceLevel($score);
        
        return $result;
    }
    
    /**
     * 3. SELF-DESCRIPTION — Explainable through doctrine
     */
    private function validateSelfDescription($feature_specification)
    {
        $result = [
            'score' => 0,
            'analysis' => [],
            'fragility_risks' => [],
            'compliance_level' => 'non_compliant'
        ];
        
        $score = 0;
        $analysis = [];
        $risks = [];
        
        // Check for doctrine explanation
        if (isset($feature_specification['doctrine_explanation'])) {
            $explanation = $feature_specification['doctrine_explanation'];
            
            // Length and clarity checks
            $word_count = str_word_count($explanation);
            if ($word_count > 0 && $word_count <= 100) {
                $score += 0.3;
                $analysis[] = "Doctrine explanation length appropriate ({$word_count} words)";
            } elseif ($word_count > 100) {
                $analysis[] = "Doctrine explanation too long ({$word_count} words)";
            } else {
                $risks[] = "Doctrine explanation missing";
            }
            
            // Content quality checks
            $required_elements = ['purpose', 'scope', 'governance'];
            $elements_found = 0;
            foreach ($required_elements as $element) {
                if (strpos(strtolower($explanation), $element) !== false) {
                    $elements_found++;
                }
            }
            
            if ($elements_found >= 2) {
                $score += 0.3;
                $analysis[] = "Key doctrinal elements present";
            }
        }
        
        // Check for metadata documentation
        if (isset($feature_specification['metadata'])) {
            $metadata = $feature_specification['metadata'];
            
            if (isset($metadata['version']) && $metadata['version']) {
                $score += 0.1;
                $analysis[] = "Version metadata documented";
            }
            
            if (isset($metadata['dependencies']) && is_array($metadata['dependencies'])) {
                $score += 0.1;
                $analysis[] = "Dependencies documented";
            }
            
            if (isset($metadata['api_documentation']) && $metadata['api_documentation']) {
                $score += 0.1;
                $analysis[] = "API documentation included";
            }
        }
        
        // Check for self-documenting code practices
        if (isset($feature_specification['code_structure'])) {
            $code_structure = $feature_specification['code_structure'];
            
            if (isset($code_structure['descriptive_names']) && $code_structure['descriptive_names']) {
                $score += 0.1;
                $analysis[] = "Descriptive naming conventions";
            }
            
            if (isset($code_structure['inline_documentation']) && $code_structure['inline_documentation']) {
                $score += 0.1;
                $analysis[] = "Inline documentation present";
            }
        }
        
        // Check for automated documentation generation
        if (isset($feature_specification['automation'])) {
            $automation = $feature_specification['automation'];
            
            if (isset($automation['doc_generation']) && $automation['doc_generation']) {
                $score += 0.1;
                $analysis[] = "Automated documentation generation";
            }
        }
        
        // Identify fragility risks
        if ($score < 0.5) {
            $risks[] = "Poor self-description - feature may become unmanageable";
        }
        
        if (!isset($feature_specification['doctrine_explanation'])) {
            $risks[] = "No doctrine explanation - violates governance requirements";
        }
        
        $result['score'] = round(min($score, 1.0), 2);
        $result['analysis'] = $analysis;
        $result['fragility_risks'] = $risks;
        $result['compliance_level'] = $this->getComplianceLevel($score);
        
        return $result;
    }
    
    /**
     * 4. TEMPORAL INTEGRITY — Obeys the canonical time model
     */
    private function validateTemporalIntegrity($feature_specification)
    {
        $result = [
            'score' => 0,
            'analysis' => [],
            'fragility_risks' => [],
            'compliance_level' => 'non_compliant'
        ];
        
        $score = 0;
        $analysis = [];
        $risks = [];
        
        // Check for UTC timestamp usage
        if (isset($feature_specification['data_structures'])) {
            $utc_compliant = 0;
            $total_timestamps = 0;
            
            foreach ($feature_specification['data_structures'] as $structure) {
                if (isset($structure['timestamps'])) {
                    foreach ($structure['timestamps'] as $timestamp) {
                        $total_timestamps++;
                        if (isset($timestamp['utc']) && $timestamp['utc'] && 
                            isset($timestamp['format']) && $timestamp['format'] === 'YYYYMMDDHHMMSS') {
                            $utc_compliant++;
                        }
                    }
                }
            }
            
            if ($total_timestamps > 0) {
                $utc_compliance = $utc_compliant / $total_timestamps;
                $score += $utc_compliance * 0.4;
                $analysis[] = "UTC compliant timestamps: {$utc_compliant}/{$total_timestamps}";
            }
        }
        
        // Check for temporal awareness
        if (isset($feature_specification['temporal_features'])) {
            $temporal_features = $feature_specification['temporal_features'];
            
            if (isset($temporal_features['time_aware']) && $temporal_features['time_aware']) {
                $score += 0.2;
                $analysis[] = "Time-aware functionality implemented";
            }
            
            if (isset($temporal_features['temporal_consistency']) && $temporal_features['temporal_consistency']) {
                $score += 0.2;
                $analysis[] = "Temporal consistency mechanisms";
            }
            
            if (isset($temporal_features['chronological_ordering']) && $temporal_features['chronological_ordering']) {
                $score += 0.1;
                $analysis[] = "Chronological ordering preserved";
            }
        }
        
        // Check for temporal event handling
        if (isset($feature_specification['events'])) {
            $temporal_events = 0;
            $total_events = count($feature_specification['events']);
            
            foreach ($feature_specification['events'] as $event) {
                if (isset($event['timestamp_utc']) && $event['timestamp_utc']) {
                    $temporal_events++;
                }
            }
            
            if ($total_events > 0) {
                $event_temporal_compliance = $temporal_events / $total_events;
                $score += $event_temporal_compliance * 0.3;
                $analysis[] = "Temporal events: {$temporal_events}/{$total_events}";
            }
        }
        
        // Identify fragility risks
        if ($score < 0.7) {
            $risks[] = "Poor temporal integrity - may corrupt system timeline";
        }
        
        if (!isset($feature_specification['temporal_features'])) {
            $risks[] = "No temporal features defined - timeline corruption risk";
        }
        
        $result['score'] = round(min($score, 1.0), 2);
        $result['analysis'] = $analysis;
        $result['fragility_risks'] = $risks;
        $result['compliance_level'] = $this->getComplianceLevel($score);
        
        return $result;
    }
    
    /**
     * 5. REVERSIBILITY — Removable without collapse
     */
    private function validateReversibility($feature_specification)
    {
        $result = [
            'score' => 0,
            'analysis' => [],
            'fragility_risks' => [],
            'compliance_level' => 'non_compliant'
        ];
        
        $score = 0;
        $analysis = [];
        $risks = [];
        
        // Check for rollback mechanisms
        if (isset($feature_specification['rollback'])) {
            $rollback = $feature_specification['rollback'];
            
            if (isset($rollback['automated_rollback']) && $rollback['automated_rollback']) {
                $score += 0.3;
                $analysis[] = "Automated rollback mechanism";
            }
            
            if (isset($rollback['data_cleanup']) && $rollback['data_cleanup']) {
                $score += 0.2;
                $analysis[] = "Data cleanup procedures defined";
            }
            
            if (isset($rollback['state_restoration']) && $rollback['state_restoration']) {
                $score += 0.2;
                $analysis[] = "State restoration capability";
            }
        }
        
        // Check for dependency management
        if (isset($feature_specification['dependencies'])) {
            $dependencies = $feature_specification['dependencies'];
            
            $removable_dependencies = 0;
            $total_dependencies = count($dependencies);
            
            foreach ($dependencies as $dependency) {
                if (isset($dependency['removable']) && $dependency['removable']) {
                    $removable_dependencies++;
                }
            }
            
            if ($total_dependencies > 0) {
                $dependency_reversibility = $removable_dependencies / $total_dependencies;
                $score += $dependency_reversibility * 0.2;
                $analysis[] = "Removable dependencies: {$removable_dependencies}/{$total_dependencies}";
            }
        }
        
        // Check for graceful degradation
        if (isset($feature_specification['degradation'])) {
            $degradation = $feature_specification['degradation'];
            
            if (isset($degradation['graceful_degradation']) && $degradation['graceful_degradation']) {
                $score += 0.2;
                $analysis[] = "Graceful degradation mechanisms";
            }
            
            if (isset($degradation['fallback_behavior']) && $degradation['fallback_behavior']) {
                $score += 0.1;
                $analysis[] = "Fallback behavior defined";
            }
        }
        
        // Check for atomic operations
        if (isset($feature_specification['operations'])) {
            $atomic_operations = 0;
            $total_operations = count($feature_specification['operations']);
            
            foreach ($feature_specification['operations'] as $operation) {
                if (isset($operation['atomic']) && $operation['atomic']) {
                    $atomic_operations++;
                }
            }
            
            if ($total_operations > 0) {
                $atomicity = $atomic_operations / $total_operations;
                $score += $atomicity * 0.1;
                $analysis[] = "Atomic operations: {$atomic_operations}/{$total_operations}";
            }
        }
        
        // Identify fragility risks
        if ($score < 0.6) {
            $risks[] = "Poor reversibility - removal may cause system collapse";
        }
        
        if (!isset($feature_specification['rollback'])) {
            $risks[] = "No rollback mechanisms defined - irreversible changes risk";
        }
        
        $result['score'] = round(min($score, 1.0), 2);
        $result['analysis'] = $analysis;
        $result['fragility_risks'] = $risks;
        $result['compliance_level'] = $this->getComplianceLevel($score);
        
        return $result;
    }
    
    /**
     * Assess the meaning increase provided by the feature
     */
    private function assessMeaningIncrease($feature_specification)
    {
        $assessment = [
            'semantic_value' => 0,
            'connectivity_value' => 0,
            'knowledge_value' => 0,
            'overall_meaning_increase' => 0,
            'analysis' => []
        ];
        
        // Semantic value assessment
        if (isset($feature_specification['semantic_contribution'])) {
            $semantic = $feature_specification['semantic_contribution'];
            
            if (isset($semantic['new_relationships']) && $semantic['new_relationships']) {
                $assessment['semantic_value'] += 0.3;
                $assessment['analysis'][] = "Creates new semantic relationships";
            }
            
            if (isset($semantic['meaning_enrichment']) && $semantic['meaning_enrichment']) {
                $assessment['semantic_value'] += 0.3;
                $assessment['analysis'][] = "Enriches existing meaning";
            }
            
            if (isset($semantic['context_awareness']) && $semantic['context_awareness']) {
                $assessment['semantic_value'] += 0.4;
                $assessment['analysis'][] = "Provides context awareness";
            }
        }
        
        // Connectivity value assessment
        if (isset($feature_specification['connectivity'])) {
            $connectivity = $feature_specification['connectivity'];
            
            if (isset($connectivity['new_edges']) && $connectivity['new_edges']) {
                $assessment['connectivity_value'] += 0.4;
                $assessment['analysis'][] = "Creates new graph edges";
            }
            
            if (isset($connectivity['bridge_creation']) && $connectivity['bridge_creation']) {
                $assessment['connectivity_value'] += 0.3;
                $assessment['analysis'][] = "Creates conceptual bridges";
            }
            
            if (isset($connectivity['network_effects']) && $connectivity['network_effects']) {
                $assessment['connectivity_value'] += 0.3;
                $assessment['analysis'][] = "Generates network effects";
            }
        }
        
        // Knowledge value assessment
        if (isset($feature_specification['knowledge_contribution'])) {
            $knowledge = $feature_specification['knowledge_contribution'];
            
            if (isset($knowledge['new_insights']) && $knowledge['new_insights']) {
                $assessment['knowledge_value'] += 0.4;
                $assessment['analysis'][] = "Generates new insights";
            }
            
            if (isset($knowledge['pattern_discovery']) && $knowledge['pattern_discovery']) {
                $assessment['knowledge_value'] += 0.3;
                $assessment['analysis'][] = "Enables pattern discovery";
            }
            
            if (isset($knowledge['learning_capability']) && $knowledge['learning_capability']) {
                $assessment['knowledge_value'] += 0.3;
                $assessment['analysis'][] = "Provides learning capability";
            }
        }
        
        // Calculate overall meaning increase
        $assessment['overall_meaning_increase'] = round(
            ($assessment['semantic_value'] + $assessment['connectivity_value'] + $assessment['knowledge_value']) / 3, 
            2
        );
        
        return $assessment;
    }
    
    /**
     * Determine overall validation status
     */
    private function determineOverallStatus($validation_result)
    {
        $overall_score = $validation_result['overall_score'];
        $fragility_risks = count($validation_result['fragility_risks']);
        $meaning_increase = $validation_result['meaning_increase_assessment']['overall_meaning_increase'];
        
        // Must have good score AND low fragility AND meaning increase
        if ($overall_score >= 0.8 && $fragility_risks <= 1 && $meaning_increase >= 0.6) {
            return 'passed';
        } elseif ($overall_score >= 0.6 && $fragility_risks <= 3 && $meaning_increase >= 0.4) {
            return 'warning';
        } elseif ($overall_score < 0.4 || $fragility_risks > 5) {
            return 'failed';
        } else {
            return 'requires_review';
        }
    }
    
    /**
     * Generate recommendations based on validation results
     */
    private function generateRecommendations($validation_result)
    {
        $recommendations = [];
        
        foreach ($validation_result['principle_scores'] as $principle => $result) {
            if ($result['score'] < 0.7) {
                switch ($principle) {
                    case 'polymorphism':
                        $recommendations[] = "Add extensible data structures and type systems to support future requirements";
                        break;
                    case 'non_interference':
                        $recommendations[] = "Implement better isolation mechanisms and conflict analysis";
                        break;
                    case 'self_description':
                        $recommendations[] = "Improve documentation and add comprehensive doctrinal explanations";
                        break;
                    case 'temporal_integrity':
                        $recommendations[] = "Ensure all timestamps use UTC canonical format and implement temporal consistency";
                        break;
                    case 'reversibility':
                        $recommendations[] = "Add rollback mechanisms and ensure graceful degradation";
                        break;
                }
            }
        }
        
        if ($validation_result['meaning_increase_assessment']['overall_meaning_increase'] < 0.5) {
            $recommendations[] = "Increase semantic value by adding more meaningful relationships and knowledge contributions";
        }
        
        if (!empty($validation_result['fragility_risks'])) {
            $recommendations[] = "Address identified fragility risks before deployment";
        }
        
        return $recommendations;
    }
    
    /**
     * Get compliance level based on score
     */
    private function getComplianceLevel($score)
    {
        if ($score >= 0.8) {
            return 'fully_compliant';
        } elseif ($score >= 0.6) {
            return 'substantially_compliant';
        } elseif ($score >= 0.4) {
            return 'partially_compliant';
        } else {
            return 'non_compliant';
        }
    }
    
    /**
     * Record expansion validation in database
     */
    private function recordExpansionValidation($validation_result, $actor_id)
    {
        $sql = "
            INSERT INTO lupo_expansion_principle_validations 
            (feature_name, polymorphism_score, non_interference_score, self_description_score, 
             temporal_integrity_score, reversibility_score, overall_score, validation_details, 
             fragility_risk_assessment, meaning_increase_assessment, status, validated_by, created_ymdhis) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param(
                'sddddddsssssi',
                $validation_result['feature_name'],
                $validation_result['principle_scores']['polymorphism']['score'],
                $validation_result['principle_scores']['non_interference']['score'],
                $validation_result['principle_scores']['self_description']['score'],
                $validation_result['principle_scores']['temporal_integrity']['score'],
                $validation_result['principle_scores']['reversibility']['score'],
                $validation_result['overall_score'],
                json_encode($validation_result['detailed_analysis']),
                json_encode($validation_result['fragility_risks']),
                json_encode($validation_result['meaning_increase_assessment']),
                $validation_result['overall_status'],
                $actor_id,
                $this->current_timestamp
            );
            $stmt->execute();
            
        } catch (Exception $e) {
            error_log("Failed to record expansion validation: " . $e->getMessage());
        }
    }
}
