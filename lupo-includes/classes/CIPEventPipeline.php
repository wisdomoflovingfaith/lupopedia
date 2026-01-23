<?php
/**
 * wolfie.headers: explicit architecture with structured clarity for every file.
 * file.last_modified_system_version: 4.0.78
 * 
 * CIP Event Pipeline
 * 
 * Orchestrates the complete CIP workflow: critique ingestion, analytics processing,
 * self-correction triggering, doctrine refinement, and emotional geometry calibration.
 * 
 * @package Lupopedia
 * @version 4.0.78
 * @author cascade (AI Assistant)
 */

class CIPEventPipeline {
    
    private $db;
    private $version = '4.0.78';
    private $analytics_engine;
    private $doctrine_refinement;
    private $emotional_calibration;
    
    public function __construct($database_connection) {
        $this->db = $database_connection;
        $this->analytics_engine = new CIPAnalyticsEngine($database_connection);
        $this->doctrine_refinement = new CIPDoctrineRefinementModule($database_connection);
        $this->emotional_calibration = new CIPEmotionalGeometryCalibration($database_connection);
    }
    
    /**
     * Process a complete CIP event through the pipeline
     * 
     * @param array $critique_data Critique event data
     * @return array Pipeline processing results
     */
    public function processCritiqueEvent($critique_data) {
        $pipeline_results = [
            'critique_ingested' => false,
            'analytics_processed' => false,
            'self_correction_triggered' => false,
            'doctrine_refinements' => [],
            'emotional_calibrations' => [],
            'pipeline_status' => 'initiated',
            'processing_time_ms' => 0
        ];
        
        $start_time = microtime(true);
        
        try {
            // Step 1: Ingest critique event
            $cip_event_id = $this->ingestCritique($critique_data);
            if ($cip_event_id) {
                $pipeline_results['critique_ingested'] = true;
                $pipeline_results['cip_event_id'] = $cip_event_id;
            }
            
            // Step 2: Process analytics
            if ($cip_event_id) {
                $analytics = $this->analytics_engine->processEvent($cip_event_id);
                $pipeline_results['analytics_processed'] = true;
                $pipeline_results['analytics'] = $analytics;
                
                // Step 3: Check for self-correction trigger
                if ($this->checkSelfCorrectionTrigger($analytics)) {
                    $pipeline_results['self_correction_triggered'] = true;
                    
                    // Step 4: Process doctrine refinements
                    $refinements = $this->doctrine_refinement->processAnalyticsForRefinement($cip_event_id, $analytics);
                    $pipeline_results['doctrine_refinements'] = $refinements;
                    
                    // Step 5: Process emotional geometry calibrations
                    $calibrations = $this->emotional_calibration->processAnalyticsForCalibration($analytics['id'], $analytics);
                    $pipeline_results['emotional_calibrations'] = $calibrations;
                    
                    // Step 6: Track propagation depth
                    $this->trackPropagationDepth($cip_event_id, $analytics, $refinements, $calibrations);
                }
                
                $pipeline_results['pipeline_status'] = 'completed';
            }
            
        } catch (Exception $e) {
            $pipeline_results['pipeline_status'] = 'failed';
            $pipeline_results['error'] = $e->getMessage();
            error_log("CIP Pipeline failed: " . $e->getMessage());
        }
        
        $pipeline_results['processing_time_ms'] = round((microtime(true) - $start_time) * 1000);
        
        return $pipeline_results;
    }
    
    /**
     * Ingest critique event into CIP system
     * 
     * @param array $critique_data Critique event data
     * @return int|null CIP event ID
     */
    private function ingestCritique($critique_data) {
        $sql = "INSERT INTO lupo_cip_events (
            timestamp_utc, source_agent, target_subsystem, defensiveness_index,
            integration_velocity, architectural_impact_score, shadow_alignment,
            doctrine_propagation_depth, resolution_status, notes, created_ymdhis
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        try {
            $stmt->execute([
                $critique_data['timestamp_utc'] ?? $this->getCurrentTimestamp(),
                $critique_data['source_agent'] ?? 'unknown',
                $critique_data['target_subsystem'] ?? 'system',
                $critique_data['defensiveness_index'] ?? 0.0,
                $critique_data['integration_velocity'] ?? 0.0,
                $critique_data['architectural_impact_score'] ?? 0.0,
                $critique_data['shadow_alignment'] ?? 0.0,
                $critique_data['doctrine_propagation_depth'] ?? 0,
                $critique_data['resolution_status'] ?? 'pending',
                $critique_data['notes'] ?? '',
                $this->getCurrentTimestamp()
            ]);
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("Critique ingestion failed: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Check if self-correction should be triggered
     * 
     * @param array $analytics Analytics results
     * @return bool Whether to trigger self-correction
     */
    private function checkSelfCorrectionTrigger($analytics) {
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
     * Track propagation depth of critique integration
     * 
     * @param int $cip_event_id CIP event ID
     * @param array $analytics Analytics results
     * @param array $refinements Doctrine refinements
     * @param array $calibrations Emotional calibrations
     */
    private function trackPropagationDepth($cip_event_id, $analytics, $refinements, $calibrations) {
        $propagation_level = 0;
        
        // Level 0: Direct analytics processing
        $this->recordPropagation($cip_event_id, $propagation_level++, 'analytics_engine', 'system_config', 
            'CIP analytics processed', 1.0);
        
        // Level 1: Doctrine refinements
        if (!empty($refinements)) {
            foreach ($refinements as $refinement) {
                $this->recordPropagation($cip_event_id, $propagation_level, 'doctrine', 
                    basename($refinement['doctrine_file_path']), 
                    'Doctrine refinement proposed', 0.8);
            }
            $propagation_level++;
        }
        
        // Level 2: Emotional geometry calibrations
        if (!empty($calibrations)) {
            foreach ($calibrations as $calibration) {
                $this->recordPropagation($cip_event_id, $propagation_level, 'emotional_geometry',
                    $calibration['calibration_target'] . ':' . $calibration['target_identifier'],
                    'Emotional baseline calibration', 0.6);
            }
            $propagation_level++;
        }
        
        // Level 3: Multi-agent synchronization (if applicable)
        if ($this->requiresMultiAgentSync($analytics, $refinements, $calibrations)) {
            $this->recordPropagation($cip_event_id, $propagation_level, 'agent_behavior', 'fleet',
                'Multi-agent critique synchronization', 0.9);
        }
    }
    
    /**
     * Record propagation tracking entry
     * 
     * @param int $cip_event_id CIP event ID
     * @param int $propagation_level Propagation depth level
     * @param string $propagation_type Type of propagation
     * @param string $affected_subsystem Affected subsystem
     * @param string $change_description Description of change
     * @param float $propagation_strength Strength of propagation
     */
    private function recordPropagation($cip_event_id, $propagation_level, $propagation_type, 
                                     $affected_subsystem, $change_description, $propagation_strength) {
        $sql = "INSERT INTO lupo_cip_propagation_tracking (
            cip_event_id, propagation_level, affected_subsystem, propagation_type,
            change_description, propagation_strength, completion_status,
            started_ymdhis, completed_ymdhis, propagation_version
        ) VALUES (?, ?, ?, ?, ?, ?, 'completed', ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $current_time = $this->getCurrentTimestamp();
        
        $stmt->execute([
            $cip_event_id,
            $propagation_level,
            $affected_subsystem,
            $propagation_type,
            $change_description,
            $propagation_strength,
            $current_time,
            $current_time,
            $this->version
        ]);
    }
    
    /**
     * Check if multi-agent synchronization is required
     * 
     * @param array $analytics Analytics results
     * @param array $refinements Doctrine refinements
     * @param array $calibrations Emotional calibrations
     * @return bool Whether multi-agent sync is required
     */
    private function requiresMultiAgentSync($analytics, $refinements, $calibrations) {
        // High architectural impact requires fleet coordination
        if ($analytics['architectural_impact_score'] > 0.8) {
            return true;
        }
        
        // Doctrine refinements affecting agent behavior require sync
        foreach ($refinements as $refinement) {
            if (strpos($refinement['doctrine_file_path'], 'AGENT') !== false || 
                strpos($refinement['doctrine_file_path'], 'MULTI_AGENT') !== false) {
                return true;
            }
        }
        
        // Emotional geometry calibrations for multiple agents require sync
        $agent_calibrations = array_filter($calibrations, function($cal) {
            return $cal['calibration_target'] === 'agent';
        });
        
        return count($agent_calibrations) > 1;
    }
    
    /**
     * Get recent average Defensiveness Index
     * 
     * @return float Recent DI average
     */
    private function getRecentDIAverage() {
        $sql = "SELECT AVG(defensiveness_index) as avg_di 
                FROM lupo_cip_analytics 
                WHERE calculated_ymdhis >= ? 
                LIMIT 50";
        
        $stmt = $this->db->prepare($sql);
        $seven_days_ago = $this->getCurrentTimestamp() - (7 * 24 * 60 * 60); // 7 days ago
        $stmt->execute([$seven_days_ago]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (float)$result['avg_di'] : 0.0;
    }
    
    /**
     * Get current timestamp in YMDHIS format
     * 
     * @return int Current timestamp
     */
    private function getCurrentTimestamp() {
        return intval(date('YmdHis'));
    }
    
    /**
     * Get pipeline status summary
     * 
     * @return array Pipeline status summary
     */
    public function getPipelineStatus() {
        $sql = "SELECT 
                    COUNT(*) as total_events,
                    SUM(CASE WHEN analytics_processed = TRUE THEN 1 ELSE 0 END) as processed_events,
                    SUM(CASE WHEN self_correction_triggered = TRUE THEN 1 ELSE 0 END) as self_correction_events,
                    AVG(correction_cascade_depth) as avg_cascade_depth
                FROM lupo_cip_events";
        
        $stmt = $this->db->query($sql);
        $status = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'total_events' => (int)$status['total_events'],
            'processed_events' => (int)$status['processed_events'],
            'self_correction_events' => (int)$status['self_correction_events'],
            'avg_cascade_depth' => round((float)$status['avg_cascade_depth'], 2),
            'pipeline_version' => $this->version,
            'status' => 'operational'
        ];
    }
}
