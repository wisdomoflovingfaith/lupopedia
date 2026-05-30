---
# FLIP Header (alias: Actor 4.0.30)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: lupo-app/Services/EmotionalGeometryValidator.php
file.last_modified_system_version: "4.0.31"
file.last_modified_utc: "20260222215200"
actor_420_status: "banned_mythological"
channel_id: 42
actor_id: 10000
---

<?php

/**
 * Emotional Geometry Validator - 4.0.30
 * 
 * Validates emotional geometry vectors (mood_vector) for security decision making.
 * Implements emotional stability assessment and intent analysis.
 * 
 * @package Lupopedia\Services
 * @version 4.0.30
 */

class EmotionalGeometryValidator {
    
    private $database;
    private $emotional_states = array();
    
    public function __construct() {
        $this->database = DatabaseFactory::getConnection();
        $this->loadEmotionalStates();
    }
    
    /**
     * Analyze emotional state from content
     * 
     * @param string $content Content to analyze
     * @return array Emotional state analysis
     */
    public function analyzeEmotionalState($content) {
        $analysis = array(
            'mood_vector' => null,
            'stability' => 1.0,
            'intent' => 'neutral',
            'clarity' => 1.0,
            'compliance' => true,
            'emotional_state' => 'stable',
            'recommendations' => array()
        );
        
        // Extract mood_vector from content if present
        $mood_vector = $this->extractMoodVector($content);
        if ($mood_vector) {
            $analysis['mood_vector'] = $mood_vector;
            $analysis['emotional_state'] = $this->getEmotionalState($mood_vector);
            $analysis['stability'] = $this->calculateStability($mood_vector);
            $analysis['intent'] = $this->analyzeIntent($content, $mood_vector);
            $analysis['clarity'] = $this->calculateClarity($content);
            $analysis['compliance'] = $this->checkEmotionalCompliance($mood_vector);
        }
        
        // Generate recommendations
        $analysis['recommendations'] = $this->generateEmotionalRecommendations($analysis);
        
        return $analysis;
    }
    
    /**
     * Validate mood_vector format and values
     * 
     * @param string $mood_vector Mood Vector value to validate
     * @return bool True if valid, false otherwise
     */
    public function isValidEmotionalGeometry($mood_vector) {
        if (empty($mood_vector)) {
            return false;
        }
        
        // Check if it's a valid hex color code
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $mood_vector)) {
            return false;
        }
        
        // Check if it's within valid emotional geometry range
        $rgb = $this->hexToRgb($mood_vector);
        
        // Check for invalid emotional combinations
        $invalid_combinations = array(
            '#FF0000', // Pure red (aggressive)
            '#000000', // Pure black (depressive)
            '#FF00FF', // Pure cyan (chaotic)
            '#FFFF00', // Pure yellow (manipulative)
            '#FF00FF', // Pure magenta (confusing)
            '#800080', // Dark green (unstable)
            '#800000', // Dark red (dangerous)
            '#808080', // Dark gray (ambiguous)
        );
        
        return !in_array($mood_vector, $invalid_combinations);
    }
    
    /**
     * Extract mood_vector from content
     */
    private function extractMoodVector($content) {
        // Look for mood_vector pattern
        $patterns = array(
            '/mood_vector[:\s]*([#][0-9A-Fa-f]{6})/i',
            '/emotional_geometry[:\s]*([#][0-9A-Fa-f]{6})/i',
            '/mood[:\s]*([#][0-9A-Fa-f]{6})/i',
            '/rgb[:\s]*([#][0-9A-Fa-f]{6})/i'
        );
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }
    
    /**
     * Convert hex color to RGB values
     */
    private function hexToRgb($hex) {
        return array(
            'r' => hexdec(substr($hex, 1, 2)),
            'g' => hexdec(substr($hex, 3, 2)),
            'b' => hexdec(substr($hex, 5, 2))
        );
    }
    
    /**
     * Get emotional state from mood_vector
     */
    private function getEmotionalState($mood_vector) {
        $rgb = $this->hexToRgb($mood_vector);
        
        // Calculate emotional state based on RGB values
        $r = $rgb['r'];
        $g = $rgb['g'];
        $b = $rgb['b'];
        
        // Determine emotional state based on color dominance
        if ($r > $g && $r > $b) {
            if ($r > 200) {
                return 'aggressive';
            } elseif ($r > 150) {
                return 'excited';
            } else {
                return 'energetic';
            }
        } elseif ($g > $r && $g > $b) {
            if ($g > 200) {
                return 'chaotic';
            } elseif ($g > 150) {
                'creative';
            } else {
                'happy';
            }
        } elseif ($b > $r && $b > $g) {
            if ($b > 200) {
                'sad';
            } elseif ($b > 150) {
                'fearful';
            } else {
                'calm';
            }
        } else {
            // Balanced or neutral
            if ($r + $g + $b > 600) {
                return 'vibrant';
            } else {
                return 'neutral';
            }
        }
    }
    
    /**
     * Calculate emotional stability from mood_vector
     */
    private function calculateStability($mood_vector) {
        $rgb = $this->hexToRgb($mood_vector);
        
        $r = $rgb['r'];
        $g = $rgb['g'];
        $b = $rgb['b'];
        
        // Calculate stability based on color balance
        $max_val = max($r, $g, $b);
        $min_val = min($r, $g, $b);
        
        // High contrast indicates emotional instability
        $contrast = ($max_val - $min_val) / 255;
        
        if ($contrast > 0.8) {
            return 0.2; // Very unstable
        } elseif ($contrast > 0.6) {
            return 0.4; // Unstable
        } elseif ($contrast > 0.4) {
            return 0.6; // Moderately stable
        } elseif ($contrast > 0.2) {
            return 0.8; // Stable
        } else {
            return 1.0; // Very stable
        }
    }
    
    /**
     * Analyze intent from content and mood_vector
     */
    private function analyzeIntent($content, $mood_vector) {
        $rgb = $this->hexToRgb($mood_vector);
        
        $r = $rgb['r'];
        $g = $rgb['g'];
        $b = $rgb['b'];
        
        // Analyze intent based on color psychology
        if ($r > 200) {
            return 'destructive';
        } elseif ($r > 150) {
            return 'aggressive';
        } elseif ($g > 200) {
            return 'chaotic';
        } elseif ($g > 150) {
            'creative';
        } elseif ($b > 200) {
            'fearful';
        } elseif ($b > 150) {
            'sad';
        } else {
            return 'constructive';
        }
    }
    }
    
    /**
     * Calculate clarity from content analysis
     */
    private function calculateClarity($content) {
        // Analyze content characteristics
        $word_count = str_word_count($content);
        $sentence_count = preg_match_all('/[.!?]+/', $content);
        $avg_word_length = $word_count > 0 ? strlen($content) / $word_count : 0;
        
        // Clarity based on content structure
        if ($avg_word_length > 10) {
            return 0.3; // Poor clarity
        } elseif ($avg_word_length > 7) {
            return 0.5; // Low clarity
        } elseif ($avg_word_length > 5) {
            return 0.7; // Moderate clarity
        } elseif ($avg_word_length > 3) {
            return 0.9; // Good clarity
        } else {
            return 1.0; // Excellent clarity
        }
    }
    
    /**
     * Check emotional compliance with security requirements
     */
    private function checkEmotionalCompliance($mood_vector) {
        // Check for restricted emotional states
        $restricted_states = array(
            '#FF0000', // Aggressive red
            '#000000', // Depressive black
            '#FF00FF', // Chaotic cyan
            '#FFFF00', // Manipulative yellow
            '#FF00FF', // Confusing magenta
        );
        
        return !in_array($mood_vector, $restricted_states);
    }
    
    /**
     * Generate emotional recommendations based on analysis
     */
    private function generateEmotionalRecommendations($analysis) {
        $recommendations = array();
        
        if ($analysis['stability'] < 0.6) {
            $recommendations[] = 'Emotional instability detected - Consider emotional stabilization';
        }
        
        if ($analysis['intent'] === 'destructive') {
            $recommendations[] = 'Destructive intent detected - Immediate intervention required';
        }
        
        if ($analysis['clarity'] < 0.5) {
            $recommendations[] = 'Low clarity detected - Improve content clarity';
        }
        
        if (!$analysis['compliance']) {
            $recommendations[] = 'Emotional compliance violation detected - Review emotional state';
        }
        
        if ($analysis['emotional_state'] === 'aggressive' || $analysis['emotional_state'] === 'chaotic') {
            $recommendations[] = 'High emotional state detected - Consider emotional regulation';
        }
        
        return $recommendations;
    }
    
    /**
     * Get emotional state classification
     */
    public function getEmotionalStateClassification($mood_vector) {
        if (!$this->isValidEmotionalGeometry($mood_vector)) {
            return 'invalid';
        }
        
        $emotional_state = $this->getEmotionalState($mood_vector);
        
        // Classify emotional state for security decisions
        switch ($emotional_state) {
            case 'stable':
            return 'safe';
            case 'excited':
            case 'energetic':
            case 'happy':
            case 'calm':
            case 'constructive':
            case 'vibrant':
            case 'neutral':
                return 'safe';
            case 'aggressive':
            case 'chaotic':
            case 'creative':
            case 'fearful':
            case 'sad':
            case 'destructive':
                return 'danger';
            default:
                return 'unknown';
        }
    }
    
    /**
     * Validate emotional geometry for security decisions
     */
    public function validateForSecurity($content, $headers = array()) {
        $validation_result = array(
            'valid' => false,
            'errors' => array(),
            'emotional_state' => 'unknown',
            'security_level' => 'unknown',
            'recommendations' => array()
        );
        
        // Check if emotional geometry is provided in headers
        if (isset($headers['X-Lupo-Emotional-Geometry'])) {
            $mood_vector = $headers['X-Lupo-Emotional-Geometry'];
            
            if (!$this->isValidEmotionalGeometry($mood_vector)) {
                $validation_result['errors'][] = 'Invalid emotional geometry in headers';
                return $validation_result;
            }
            
            $emotional_state = $this->getEmotionalStateClassification($mood_vector);
            $validation_result['emotional_state'] = $emotional_state;
            
            // Check if emotional state is safe for security operations
            if ($emotional_state === 'danger') {
                $validation_result['errors'][] = 'Dangerous emotional state detected';
                $validation_result['security_level'] = 'high';
                return $validation_result;
            }
            
            $validation_result['valid'] = true;
            $validation_result['security_level'] = $this->getSecurityLevel($emotional_state);
        } else {
            $validation_result['errors'][] = 'Missing emotional geometry in headers';
        }
        
        return $validation_result;
    }
    
    /**
     * Get security level based on emotional state
     */
    private function getSecurityLevel($emotional_state) {
        switch ($emotional_state) {
            case 'stable':
                return 'low';
            case 'excited':
            case 'energetic':
            case 'happy':
            case 'calm':
            case 'constructive':
            case 'vibrant':
            case 'neutral':
                return 'low';
            case 'aggressive':
            case 'chaotic':
            case 'creative':
            case 'fearful':
            case 'sad':
            case 'destructive':
                return 'high';
            default:
                return 'medium';
        }
    }
    
    /**
     * Get emotional color palette for security visualization
     */
    public function getEmotionalColorPalette() {
        return array(
            'stable' => array('#4A90E2', '#3498DB', '#2ECC71', '#27AE60', '#229954'),
            'excited' => array('#F39C12', '#E67E22', '#D68910', '#A93226'),
            'energetic' => array('#F39C12', '#E67E22', '#D68910', '#A93226'),
            'happy' => array('#2ECC71', '#27AE60', '#229954', '#3498DB'),
            'calm' => array('#3498DB', '#95A5A6', '#7F8C8D', '#707B7C'),
            'constructive' => array('#27AE60', '#229954', '#3498DB', '#2ECC71'),
            'vibrant' => array('#E67E22', '#D68910', '#A93226', '#27AE60'),
            'neutral' => array('#95A5A6', '#7F8C8D', '#707B7C', '#95A5A6'),
            'aggressive' => array('#E74C3C', '#C0392B', '#A93226', '#B58910'),
            'chaotic' => array('#E67E22', '#D68910', '#A93226', '#27AE60'),
            'creative' => array('#D68910', '#A93226', '#27AE60', '#3498DB'),
            'fearful' => array('#A93226', '#B58910', '#C0392B', '#E74C3C'),
            'sad' => array('#B58910', '#C0392B', '#E74C3C', '#A93226'),
            'destructive' => array('#A93226', '#B58910', '#C0392B', '#E74C3C')
        );
    }
    
    /**
     * Load predefined emotional states from database
     */
    private function loadEmotionalStates() {
        $query = "SELECT * FROM " . LUPO_TABLE_PREFIX . "emotional_states 
                  WHERE is_active = 1 ORDER BY state_name";
        
        $stmt = $this->database->prepare($query);
        $stmt->execute();
        $states = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->emotional_states = $states;
    }
    
    /**
     * Get emotional state by name
     */
    public function getEmotionalStateByName($state_name) {
        foreach ($this->emotional_states as $state) {
            if ($state['state_name'] === $state_name) {
                return $state;
            }
        }
        return null;
    }
    
    /**
     * Create emotional state record
     */
    public function createEmotionalState($state_name, $mood_vector, $description = '') {
        $this->database->insert(LUPO_TABLE_PREFIX . 'emotional_states', array(
            'state_name' => $state_name,
            'mood_vector' => $mood_vector,
            'description' => $description,
            'created_ymdhis' => gmdate('YmdHis'),
            'is_active' => 1
        ));
    }
    
    /**
     * Update emotional state
     */
    public function updateEmotionalState($state_id, $mood_vector, $description = '') {
        $update_data = array(
            'mood_vector' => $mood_vector,
            'description' => $description,
            'updated_ymdhis' => gmdate('YmdHis')
        );
        
        $where_clause = "state_id = :state_id";
        
        $this->database->update(LUPO_TABLE_PREFIX . 'emotional_states', $update_data, $where_clause, array(
            'state_id' => $state_id
        ));
    }
    
    /**
     * Delete emotional state
     */
    public function deleteEmotionalState($state_id) {
        $update_data = array(
            'is_active' => 0,
            'deleted_ymdhis' => gmdate('YmdHis')
        );
        
        $where_clause = "state_id = :state_id";
        
        $this->database->update(LUPO_TABLE_PREFIX . 'emotional_states', $update_data, $where_clause, array(
            'state_id' => $state_id
        ));
    }
}
