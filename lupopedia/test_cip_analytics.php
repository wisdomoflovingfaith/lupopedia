<?php
/**
 * wolfie.headers: explicit architecture with structured clarity for every file.
 * file.last_modified_system_version: 4.0.76
 * 
 * CIP Analytics Testing Tool
 * 
 * Tests the CIP Analytics Engine, Doctrine Refinement Module, and 
 * Emotional Geometry Calibration components for version 4.0.75.
 * 
 * @package Lupopedia
 * @version 4.0.76
 * @author kiro (AI Assistant)
 */

require_once 'lupopedia-config.php';
require_once 'lupo-includes/classes/CIPAnalyticsEngine.php';
require_once 'lupo-includes/classes/CIPDoctrineRefinementModule.php';
require_once 'lupo-includes/classes/CIPEmotionalGeometryCalibration.php';

class CIPAnalyticsTest {
    
    private $db;
    private $analytics_engine;
    private $doctrine_module;
    private $geometry_calibration;
    
    public function __construct() {
        // Initialize database connection
        $this->db = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // Initialize CIP components
        $this->analytics_engine = new CIPAnalyticsEngine($this->db);
        $this->doctrine_module = new CIPDoctrineRefinementModule($this->db);
        $this->geometry_calibration = new CIPEmotionalGeometryCalibration($this->db);
    }
    
    /**
     * Run comprehensive CIP analytics tests
     */
    public function runTests() {
        echo "=== CIP Analytics Testing Tool v4.0.76 ===\n\n";
        
        try {
            // Test 1: Database schema validation
            $this->testDatabaseSchema();
            
            // Test 2: Create test CIP event
            $test_event_id = $this->createTestCIPEvent();
            
            // Test 3: Analytics engine processing
            $analytics = $this->testAnalyticsEngine($test_event_id);
            
            // Test 4: Doctrine refinement processing
            $refinements = $this->testDoctrineRefinement($test_event_id, $analytics);
            
            // Test 5: Emotional geometry calibration
            $calibrations = $this->testEmotionalGeometryCalibration($analytics);
            
            // Test 6: Integration testing
            $this->testIntegration($test_event_id, $analytics, $refinements, $calibrations);
            
            echo "\n=== All Tests Completed Successfully ===\n";
            
        } catch (Exception $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
            echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
        }
    }
    
    /**
     * Test database schema for CIP analytics tables
     */
    private function testDatabaseSchema() {
        echo "Testing database schema...\n";
        
        $required_tables = [
            'lupo_cip_analytics',
            'lupo_cip_trends',
            'lupo_doctrine_refinements',
            'lupo_doctrine_evolution_audit',
            'lupo_emotional_geometry_calibrations',
            'lupo_calibration_impacts',
            'lupo_cip_propagation_tracking',
            'lupo_multi_agent_critique_sync'
        ];
        
        foreach ($required_tables as $table) {
            $stmt = $this->db->prepare("SHOW TABLES LIKE ?");
            $stmt->execute([$table]);
            
            if ($stmt->rowCount() === 0) {
                throw new Exception("Required table missing: {$table}");
            }
            
            echo "  ✓ Table exists: {$table}\n";
        }
        
        // Test extended columns
        $this->testExtendedColumns();
        
        echo "  ✓ Database schema validation passed\n\n";
    }
    
    /**
     * Test extended columns in existing tables
     */
    private function testExtendedColumns() {
        // Test lupo_cip_events extensions
        $stmt = $this->db->prepare("SHOW COLUMNS FROM lupo_cip_events LIKE 'analytics_processed'");
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            throw new Exception("Missing analytics_processed column in lupo_cip_events");
        }
        
        // Test lupo_actor_collections extensions
        $stmt = $this->db->prepare("SHOW COLUMNS FROM lupo_actor_collections LIKE 'emotional_geometry_calibrated_ymdhis'");
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            throw new Exception("Missing emotional_geometry_calibrated_ymdhis column in lupo_actor_collections");
        }
    }
    
    /**
     * Create a test CIP event for testing
     */
    private function createTestCIPEvent() {
        echo "Creating test CIP event...\n";
        
        // Insert test CIP event
        $sql = "INSERT INTO lupo_cip_events (
            critique_source, critique_content, response_content,
            event_status, created_ymdhis, event_version
        ) VALUES (?, ?, ?, 'processed', ?, '4.0.75')";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'test_source',
            'This is a test critique for analytics processing',
            'This is a test response showing some defensiveness',
            intval(date('YmdHis'))
        ]);
        
        $event_id = $this->db->lastInsertId();
        echo "  ✓ Created test CIP event ID: {$event_id}\n\n";
        
        return $event_id;
    }
    
    /**
     * Test CIP Analytics Engine
     */
    private function testAnalyticsEngine($event_id) {
        echo "Testing CIP Analytics Engine...\n";
        
        // Process the test event
        $analytics = $this->analytics_engine->processEvent($event_id);
        
        // Validate analytics results
        $required_fields = [
            'defensiveness_index', 'integration_velocity', 
            'architectural_impact_score', 'doctrine_propagation_depth'
        ];
        
        foreach ($required_fields as $field) {
            if (!isset($analytics[$field])) {
                throw new Exception("Missing analytics field: {$field}");
            }
            echo "  ✓ {$field}: {$analytics[$field]}\n";
        }
        
        // Verify analytics were stored in database
        $stmt = $this->db->prepare("SELECT * FROM lupo_cip_analytics WHERE event_id = ?");
        $stmt->execute([$event_id]);
        $stored_analytics = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$stored_analytics) {
            throw new Exception("Analytics not stored in database");
        }
        
        echo "  ✓ Analytics stored in database\n";
        echo "  ✓ Analytics engine test passed\n\n";
        
        return $analytics;
    }
    
    /**
     * Test Doctrine Refinement Module
     */
    private function testDoctrineRefinement($event_id, $analytics) {
        echo "Testing Doctrine Refinement Module...\n";
        
        // Process analytics for refinement
        $refinements = $this->doctrine_module->processAnalyticsForRefinement($event_id, $analytics);
        
        echo "  ✓ Generated " . count($refinements) . " refinement proposals\n";
        
        foreach ($refinements as $refinement) {
            echo "  ✓ Refinement: {$refinement['refinement_type']} - {$refinement['doctrine_file_path']}\n";
            
            // Verify refinement was stored
            $stmt = $this->db->prepare("SELECT * FROM lupo_doctrine_refinements WHERE id = ?");
            $stmt->execute([$refinement['id']]);
            $stored = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$stored) {
                throw new Exception("Refinement not stored in database");
            }
        }
        
        echo "  ✓ Doctrine refinement test passed\n\n";
        
        return $refinements;
    }
    
    /**
     * Test Emotional Geometry Calibration
     */
    private function testEmotionalGeometryCalibration($analytics) {
        echo "Testing Emotional Geometry Calibration...\n";
        
        // Get analytics ID from database
        $stmt = $this->db->prepare("SELECT id FROM lupo_cip_analytics WHERE event_id = ?");
        $stmt->execute([$analytics['event_id']]);
        $analytics_row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$analytics_row) {
            throw new Exception("Analytics not found in database");
        }
        
        $analytics_id = $analytics_row['id'];
        
        // Process analytics for calibration
        $calibrations = $this->geometry_calibration->processAnalyticsForCalibration($analytics_id, $analytics);
        
        echo "  ✓ Generated " . count($calibrations) . " calibration proposals\n";
        
        foreach ($calibrations as $calibration) {
            echo "  ✓ Calibration: {$calibration['calibration_target']} - {$calibration['target_identifier']}\n";
            
            // Verify calibration was stored
            $stmt = $this->db->prepare("SELECT * FROM lupo_emotional_geometry_calibrations WHERE id = ?");
            $stmt->execute([$calibration['id']]);
            $stored = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$stored) {
                throw new Exception("Calibration not stored in database");
            }
        }
        
        echo "  ✓ Emotional geometry calibration test passed\n\n";
        
        return $calibrations;
    }
    
    /**
     * Test integration between all components
     */
    private function testIntegration($event_id, $analytics, $refinements, $calibrations) {
        echo "Testing component integration...\n";
        
        // Verify event was marked as processed
        $stmt = $this->db->prepare("SELECT analytics_processed, self_correction_triggered FROM lupo_cip_events WHERE id = ?");
        $stmt->execute([$event_id]);
        $event_status = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$event_status['analytics_processed']) {
            throw new Exception("Event not marked as analytics processed");
        }
        
        echo "  ✓ Event marked as analytics processed\n";
        
        if ($event_status['self_correction_triggered']) {
            echo "  ✓ Self-correction triggered (high defensiveness detected)\n";
        }
        
        // Test trend aggregation
        $this->testTrendAggregation();
        
        echo "  ✓ Integration test passed\n\n";
    }
    
    /**
     * Test trend aggregation functionality
     */
    private function testTrendAggregation() {
        // This would normally be done by a scheduled process
        // For testing, we'll simulate it
        
        $sql = "INSERT INTO lupo_cip_trends (
            trend_period, period_start_ymdhis, period_end_ymdhis,
            avg_defensiveness_index, avg_integration_velocity, avg_architectural_impact,
            total_events, calculated_ymdhis
        ) VALUES ('daily', ?, ?, 0.5000, 0.3000, 0.2000, 1, ?)";
        
        $now = intval(date('YmdHis'));
        $start_of_day = intval(date('Ymd') . '000000');
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$start_of_day, $now, $now]);
        
        echo "  ✓ Trend aggregation test data created\n";
    }
}

// Run the tests if this file is executed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    $test = new CIPAnalyticsTest();
    $test->runTests();
}

?>