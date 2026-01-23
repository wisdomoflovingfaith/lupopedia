<?php
/**
 * wolfie.headers: explicit architecture with structured clarity for every file.
 * file.last_modified_system_version: 4.0.76
 * 
 * CIP Analytics Engine
 * 
 * Aggregates CIP events, computes trends for DI/IV/AIS/DPD metrics,
 * identifies high-impact critique sources and subsystems, and surfaces
 * governance insights for doctrine updates.
 * 
 * @package Lupopedia
 * @version 4.0.76
 * @author kiro (AI Assistant)
 */

class CIPAnalyticsEngine {
    
    private $db;
    private $version = '4.0.76';
    
    public function __construct($database_connection) {
        $this->db = $database_connection;
    }
    
    /**
     * Process CIP event and calculate analytics metrics
     * 
     * @param int $event_id CIP event ID to analyze
     * @return array Analytics results
     */
    public function processEvent($event_id) {
        // Get CIP event data
        $event = $this->getCIPEvent($event_id);
        if (!$event) {
            throw new Exception("CIP event not found: {$event_id}");
        }
        
        // Calculate core metrics
        $analytics = [
            'event_id' => $event_id,
            'defensiveness_index' => $this->calculateDefensivenessIndex($event),
            'integration_velocity' => $this->calculateIntegrationVelocity($event),
            'architectural_impact_score' => $this->calculateArchitecturalImpactScore($event),
            'doctrine_propagation_depth' => $this->calculateDoctrinePropagationDepth($event),
            'critique_source_weight' => $this->calculateCritiqueSourceWeight($event),
            'subsystem_impact_json' => json_encode($this->analyzeSubsystemImpact($event)),
            'trend_analysis_json' => json_encode($this->generateTrendAnalysis($event)),
            'calculated_ymdhis' => $this->getCurrentTimestamp(),
            'analytics_version' => $this->version
        ];
        
        // Store analytics
        $this->storeAnalytics($analytics);
        
        // Mark event as processed
        $this->markEventProcessed($event_id);
        
        // Trigger self-correction if needed
        if ($this->shouldTriggerSelfCorrection($analytics)) {
            $this->triggerSelfCorrection($event_id, $analytics);
        }
        
        return $analytics;
    }
    
    /**
     * Calculate Defensiveness Index (DI)
     * Measures how defensive the system's response to critique is
     * 
     * @param array $event CIP event data
     * @return float DI score (0.0000-1.0000)
     */
    private function calculateDefensivenessIndex($event) {
        $di_factors = [];
        
        // Response time factor (faster = more defensive)
        $response_time_hours = $this->getResponseTimeHours($event);
        $di_factors['response_time'] = min(1.0, $response_time_hours / 24.0);
        
        // Rejection indicators in response
        $rejection_score = $this->analyzeRejectionIndicators($event);
        $di_factors['rejection'] = $rejection_score;
        
        // Justification length (longer = more defensive)
        $justification_length = strlen($event['response_content'] ?? '');
        $di_factors['justification'] = min(1.0, $justification_length / 1000.0);
        
        // Counter-critique presence
        $counter_critique = $this->detectCounterCritique($event);
        $di_factors['counter_critique'] = $counter_critique ? 0.8 : 0.0;
        
        // Calculate weighted average
        $weights = ['response_time' => 0.2, 'rejection' => 0.4, 'justification' => 0.2, 'counter_critique' => 0.2];
        $di = 0.0;
        foreach ($di_factors as $factor => $value) {
            $di += $value * $weights[$factor];
        }
        
        return round($di, 4);
    }
    
    /**
     * Calculate Integration Velocity (IV)
     * Measures how quickly critique is integrated into system changes
     * 
     * @param array $event CIP event data
     * @return float IV score (0.0000-1.0000)
     */
    private function calculateIntegrationVelocity($event) {
        $integration_time_hours = $this->getIntegrationTimeHours($event);
        
        // Faster integration = higher velocity
        if ($integration_time_hours <= 1) return 1.0000;
        if ($integration_time_hours <= 6) return 0.8000;
        if ($integration_time_hours <= 24) return 0.6000;
        if ($integration_time_hours <= 72) return 0.4000;
        if ($integration_time_hours <= 168) return 0.2000;
        
        return 0.0000; // No integration after 1 week
    }
    
    /**
     * Calculate Architectural Impact Score (AIS)
     * Measures the breadth and depth of changes triggered by critique
     * 
     * @param array $event CIP event data
     * @return float AIS score (0.0000-1.0000)
     */
    private function calculateArchitecturalImpactScore($event) {
        $impact_factors = [];
        
        // Number of files changed
        $files_changed = $this->countFilesChanged($event);
        $impact_factors['files'] = min(1.0, $files_changed / 10.0);
        
        // Number of subsystems affected
        $subsystems_affected = $this->countSubsystemsAffected($event);
        $impact_factors['subsystems'] = min(1.0, $subsystems_affected / 5.0);
        
        // Doctrine changes triggered
        $doctrine_changes = $this->countDoctrineChanges($event);
        $impact_factors['doctrine'] = min(1.0, $doctrine_changes / 3.0);
        
        // Agent behavior modifications
        $agent_modifications = $this->countAgentModifications($event);
        $impact_factors['agents'] = min(1.0, $agent_modifications / 5.0);
        
        // Calculate weighted average
        $weights = ['files' => 0.25, 'subsystems' => 0.35, 'doctrine' => 0.25, 'agents' => 0.15];
        $ais = 0.0;
        foreach ($impact_factors as $factor => $value) {
            $ais += $value * $weights[$factor];
        }
        
        return round($ais, 4);
    }
    
    /**
     * Calculate Doctrine Propagation Depth (DPD)
     * Measures how many layers deep the critique propagates through the system
     * 
     * @param array $event CIP event data
     * @return int DPD levels (0-10)
     */
    private function calculateDoctrinePropagationDepth($event) {
        $propagation_levels = [];
        
        // Level 0: Direct response
        if ($this->hasDirectResponse($event)) $propagation_levels[] = 0;
        
        // Level 1: Immediate file changes
        if ($this->hasImmediateChanges($event)) $propagation_levels[] = 1;
        
        // Level 2: Doctrine updates
        if ($this->hasDoctrineUpdates($event)) $propagation_levels[] = 2;
        
        // Level 3: Agent behavior changes
        if ($this->hasAgentBehaviorChanges($event)) $propagation_levels[] = 3;
        
        // Level 4: System configuration changes
        if ($this->hasSystemConfigChanges($event)) $propagation_levels[] = 4;
        
        // Level 5: Cross-subsystem integration
        if ($this->hasCrossSubsystemIntegration($event)) $propagation_levels[] = 5;
        
        return count($propagation_levels);
    }
    
    /**
     * Generate comprehensive trend analysis
     * 
     * @param array $event Current CIP event
     * @return array Trend analysis data
     */
    private function generateTrendAnalysis($event) {
        return [
            'recent_di_trend' => $this->getRecentDITrend(),
            'recent_iv_trend' => $this->getRecentIVTrend(),
            'recent_ais_trend' => $this->getRecentAISTrend(),
            'critique_source_patterns' => $this->analyzeCritiqueSourcePatterns(),
            'subsystem_impact_patterns' => $this->analyzeSubsystemImpactPatterns(),
            'seasonal_patterns' => $this->analyzeSeasonalPatterns(),
            'improvement_recommendations' => $this->generateImprovementRecommendations()
        ];
    }
    
    /**
     * Store analytics in database
     * 
     * @param array $analytics Analytics data to store
     */
    private function storeAnalytics($analytics) {
        $sql = "INSERT INTO lupo_cip_analytics (
            event_id, defensiveness_index, integration_velocity, 
            architectural_impact_score, doctrine_propagation_depth,
            critique_source_weight, subsystem_impact_json, trend_analysis_json,
            calculated_ymdhis, analytics_version
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $analytics['event_id'],
            $analytics['defensiveness_index'],
            $analytics['integration_velocity'],
            $analytics['architectural_impact_score'],
            $analytics['doctrine_propagation_depth'],
            $analytics['critique_source_weight'],
            $analytics['subsystem_impact_json'],
            $analytics['trend_analysis_json'],
            $analytics['calculated_ymdhis'],
            $analytics['analytics_version']
        ]);
    }
    
    /**
     * Check if self-correction should be triggered
     * 
     * @param array $analytics Analytics results
     * @return bool Whether to trigger self-correction
     */
    private function shouldTriggerSelfCorrection($analytics) {
        // High defensiveness with low integration velocity
        if ($analytics['defensiveness_index'] > 0.7 && $analytics['integration_velocity'] < 0.3) {
            return true;
        }
        
        // High architectural impact with low propagation depth
        if ($analytics['architectural_impact_score'] > 0.8 && $analytics['doctrine_propagation_depth'] < 2) {
            return true;
        }
        
        // Consistent pattern of defensive responses
        $recent_di_avg = $this->getRecentDIAverage();
        if ($recent_di_avg > 0.6 && $analytics['defensiveness_index'] > $recent_di_avg) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Trigger self-correction process
     * 
     * @param int $event_id CIP event ID
     * @param array $analytics Analytics results
     */
    private function triggerSelfCorrection($event_id, $analytics) {
        // Mark event as triggering self-correction
        $sql = "UPDATE lupo_cip_events SET 
                self_correction_triggered = TRUE,
                correction_cascade_depth = ?
                WHERE id = ?";
        
        $cascade_depth = $this->calculateCorrectionCascadeDepth($analytics);
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cascade_depth, $event_id]);
        
        // Log self-correction trigger
        $this->logSelfCorrectionTrigger($event_id, $analytics, $cascade_depth);
    }
    
    /**
     * Get current timestamp in YMDHIS format
     * 
     * @return int Current timestamp
     */
    private function getCurrentTimestamp() {
        return intval(date('YmdHis'));
    }
    
    // Additional helper methods would be implemented here...
    // (getCIPEvent, getResponseTimeHours, analyzeRejectionIndicators, etc.)
}