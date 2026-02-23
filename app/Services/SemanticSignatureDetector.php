---
# FLIP Header (alias: Actor 4.0.30)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: app/Services/SemanticSignatureDetector.php
file.last_modified_system_version: "4.0.30"
file.last_modified_utc: "20260222213800"
channel_id: 430
actor_id: 10000
---

<?php

/**
 * Semantic Signature Detector - 4.0.30
 * 
 * Detects semantic patterns and signatures for security validation.
 * Implements Actor 420 bypass pattern detection.
 * 
 * @package Lupopedia\Services
 * @version 4.0.30
 */

class SemanticSignatureDetector {
    
    private $database;
    private $registered_signatures = array();
    
    public function __construct() {
        $this->database = DatabaseFactory::getConnection();
        $this->loadRegisteredSignatures();
    }
    
    /**
     * Detect semantic signature from content
     * 
     * @param string $content Content to analyze
     * @return string Detected semantic signature
     */
    public function detectSignature($content) {
        // Remove HTML tags and normalize content
        $clean_content = strip_tags($content);
        $clean_content = strtolower(trim($clean_content));
        
        // Check for Actor 420 specific patterns
        $actor_420_patterns = array(
            'stoned_wolfie',
            'actor_420',
            'hybrid_consciousness',
            'semantic_bypass',
            'boundary_violation',
            'x_lupo_forwarded_bypass',
            'wolfie_420_persistence'
        );
        
        foreach ($actor_420_patterns as $pattern) {
            if (strpos($clean_content, $pattern) !== false) {
                return 'ACTOR_420_BYPASS_PATTERN';
            }
        }
        
        // Extract semantic signature using pattern matching
        $signature = $this->extractSemanticSignature($clean_content);
        
        return $signature ?: 'UNKNOWN_SIGNATURE';
    }
    
    /**
     * Extract semantic signature using pattern matching
     */
    private function extractSemanticSignature($content) {
        // Look for semantic patterns
        $patterns = array(
            '/\b(semantic|semantic_|semantic_content|semantic_graph)\b/i',
            '/\b(hybrid|hybrid_actor|hybrid_consciousness)\b/i',
            '/\b(lilith|maat|thoth|anubis)\b/i',
            '/\b(emotional_geometry|mood_rgb|emotional_state)\b/i',
            '/\b(semantic_boundary|semantic_containment)\b/i',
            '/\b(semantic_security|semantic_validation)\b/i'
        );
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                return strtoupper(str_replace(array(' ', '_'), '-', $matches[0]));
            }
        }
        
        // Generate signature from content characteristics
        return $this->generateSignatureFromContent($content);
    }
    
    /**
     * Generate signature from content characteristics
     */
    private function generateSignatureFromContent($content) {
        $characteristics = array();
        
        // Content type detection
        if (strpos($content, 'actor_') !== false) {
            $characteristics[] = 'ACTOR';
        }
        if (strpos($content, 'channel_') !== false) {
            $characteristics[] = 'CHANNEL';
        }
        if (strpos($content, 'semantic_') !== false) {
            $characteristics[] = 'SEMANTIC';
        }
        if (strpos($content, 'emotional_') !== false) {
            $characteristics[] = 'EMOTIONAL';
        }
        
        if (empty($characteristics)) {
            return 'GENERIC_CONTENT';
        }
        
        return implode('_', $characteristics);
    }
    
    /**
     * Check if signature is registered in the system
     */
    public function isSignatureRegistered($signature) {
        return in_array($signature, $this->registered_signatures);
    }
    
    /**
     * Register a new semantic signature
     */
    public function registerSignature($signature, $description = '') {
        if (!$this->isSignatureRegistered($signature)) {
            $this->registered_signatures[] = $signature;
            
            // Store in database
            $this->database->insert(LUPO_TABLE_PREFIX . 'semantic_signatures', array(
                'signature' => $signature,
                'description' => $description,
                'created_ymdhis' => gmdate('YmdHis'),
                'is_active' => 1
            ));
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Get all registered signatures
     */
    public function getRegisteredSignatures() {
        return $this->registered_signatures;
    }
    
    /**
     * Load registered signatures from database
     */
    private function loadRegisteredSignatures() {
        $query = "SELECT signature FROM " . LUPO_TABLE_PREFIX . "semantic_signatures 
                  WHERE is_active = 1 ORDER BY signature";
        
        $stmt = $this->database->prepare($query);
        $stmt->execute();
        $signatures = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $this->registered_signatures = $signatures;
    }
    
    /**
     * Analyze semantic patterns for security assessment
     */
    public function analyzeSemanticPatterns($content) {
        $analysis = array(
            'patterns_found' => array(),
            'risk_level' => 'low',
            'recommendations' => array()
        );
        
        $patterns = array(
            'actor_420_patterns' => array(
                'stoned_wolfie',
                'actor_420',
                'hybrid_consciousness',
                'semantic_bypass',
                'boundary_violation',
                'x_lupo_forwarded_bypass'
            ),
            'security_patterns' => array(
                'security_bypass',
                'authentication_bypass',
                'authorization_bypass',
                'privilege_escalation'
            ),
            'emotional_patterns' => array(
                'emotional_instability',
                'destructive_intent',
                'confusion_detected',
                'emotional_manipulation'
            ),
            'boundary_patterns' => array(
                'boundary_crossing',
                'unauthorized_access',
                'semantic_boundary_violation',
                'containment_breach'
            )
        );
        
        foreach ($patterns as $category => $pattern_list) {
            foreach ($pattern_list as $pattern) {
                if (strpos(strtolower($content), $pattern) !== false) {
                    $analysis['patterns_found'][] = array(
                        'category' => $category,
                        'pattern' => $pattern,
                        'severity' => $this->getPatternSeverity($pattern)
                    );
                    
                    // Adjust risk level based on pattern severity
                    $pattern_severity = $this->getPatternSeverity($pattern);
                    if ($pattern_severity === 'critical') {
                        $analysis['risk_level'] = 'critical';
                    } elseif ($pattern_severity === 'high' && $analysis['risk_level'] !== 'critical') {
                        $analysis['risk_level'] = 'high';
                    } elseif ($pattern_severity === 'medium' && $analysis['risk_level'] === 'low') {
                        $analysis['risk_level'] = 'medium';
                    }
                }
            }
        }
        
        // Generate recommendations
        $analysis['recommendations'] = $this->generateRecommendations($analysis['patterns_found']);
        
        return $analysis;
    }
    
    /**
     * Get pattern severity level
     */
    private function getPatternSeverity($pattern) {
        $critical_patterns = array(
            'stoned_wolfie',
            'actor_420',
            'hybrid_consciousness',
            'semantic_bypass',
            'boundary_violation',
            'x_lupo_forwarded_bypass'
        );
        
        $high_patterns = array(
            'security_bypass',
            'authentication_bypass',
            'authorization_bypass',
            'privilege_escalation',
            'emotional_instability',
            'destructive_intent'
        );
        
        $medium_patterns = array(
            'confusion_detected',
            'emotional_manipulation',
            'boundary_crossing',
            'unauthorized_access'
        );
        
        if (in_array($pattern, $critical_patterns)) {
            return 'critical';
        } elseif (in_array($pattern, $high_patterns)) {
            return 'high';
        } elseif (in_array($pattern, $medium_patterns)) {
            return 'medium';
        }
        
        return 'low';
    }
    
    /**
     * Generate security recommendations based on patterns found
     */
    private function generateRecommendations($patterns_found) {
        $recommendations = array();
        
        foreach ($patterns_found as $pattern) {
            switch ($pattern['severity']) {
                case 'critical':
                    $recommendations[] = "IMMEDIATE ACTION REQUIRED: " . $pattern['pattern'] . " detected";
                    break;
                case 'high':
                    $recommendations[] = "Review and investigate: " . $pattern['pattern'] . " pattern";
                    break;
                case 'medium':
                    $recommendations[] = "Monitor: " . $pattern['pattern'] . " activity";
                    break;
                case 'low':
                    $recommendations[] = "Note: " . $pattern['pattern'] . " detected";
                    break;
            }
        }
        
        return $recommendations;
    }
}
