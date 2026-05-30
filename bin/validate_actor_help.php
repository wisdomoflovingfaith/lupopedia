<?php
/**
 * Actor Help Documentation Validator - v2
 * 
 * Validates actor help documentation completeness and quality
 * Implements corrected actor priorities and comprehensive validation framework
 * 
 * @author Windsurf (1002)
 * @version 4.0.50
 */

require_once 'includes/bootstrap.php';

class ActorHelpValidator {
    private $doctrine_path = 'docs/doctrine/ACTOR_HELP_DOCTRINE.md';
    private $validation_rules = null;
    
    public function __construct() {
        $this->loadValidationRules();
    }
    
    /**
     * Load validation rules from doctrine
     */
    private function loadValidationRules() {
        if (!file_exists($this->doctrine_path)) {
            throw new Exception("Doctrine file not found: {$this->doctrine_path}");
        }
        
        $doctrine_content = file_get_contents($this->doctrine_path);
        $doctrine = json_decode($doctrine_content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON in doctrine: " . json_last_error_msg());
        }
        
        $this->validation_rules = $doctrine['validation_rules'];
    }
    
    /**
     * Validate specific actor help documentation
     */
    public function validateActor($actor_id) {
        echo "=== Validating Actor {$actor_id} ===\n";
        
        $required_files = [
            'profile.md' => 10,
            'capabilities.md' => 10,
            'quickref.md' => 10,
            'examples.md' => 5,
            'troubleshooting.md' => 5
        ];
        
        $actor_path = "actors/{$actor_id}";
        if (!is_dir($actor_path)) {
            echo "ERROR: Actor directory not found: {$actor_path}\n";
            return 0;
        }
        
        $score = 0;
        $missing_files = [];
        $present_files = [];
        
        foreach ($required_files as $file => $points) {
            $file_path = "{$actor_path}/{$file}";
            if (file_exists($file_path)) {
                $present_files[] = $file;
                $score += $points;
                echo "✅ {$file} present (+{$points} points)\n";
                
                // Content validation
                $content = file_get_contents($file_path);
                $this->validateContent($content, $file_path, $actor_id, $file);
                
            } else {
                $missing_files[] = $file;
                echo "❌ {$file} missing (+{$points} points)\n";
            }
        }
        
        // Actor type specific validation
        $this->validateActorType($actor_id, $score);
        
        // Consistency checks
        $this->validateConsistency($actor_id, $score);
        
        $total_possible = array_sum($required_files);
        $percentage = round(($score / $total_possible) * 100, 1);
        
        echo "\n=== VALIDATION RESULTS ===\n";
        echo "Actor: {$actor_id}\n";
        echo "Score: {$score}/{$total_possible} ({$percentage}%)\n";
        echo "Present Files: " . count($present_files) . "\n";
        echo "Missing Files: " . count($missing_files) . "\n";
        
        if ($percentage >= 80) {
            echo "✅ PASS\n";
            return 1;
        } elseif ($percentage >= 60) {
            echo "⚠️ WARNING\n";
            return 0;
        } else {
            echo "❌ FAIL\n";
            return 0;
        }
    }
    
    /**
     * Validate content quality and completeness
     */
    private function validateContent($content, $file_path, $actor_id, $file_name) {
        // Check for actor_id in profile
        if ($file_name === 'profile.md' && strpos($content, 'actor_id:') === false) {
            echo "⚠️ WARNING: {$file_path} - Missing actor_id in profile\n";
        }
        
        // Check for capabilities section
        if ($file_name === 'capabilities.md') {
            if (strpos($content, 'capabilities:') === false) {
                echo "⚠️ WARNING: {$file_path} - Missing capabilities section\n";
            }
        }
        
        // Check for examples in capabilities
        if ($file_name === 'capabilities.md' && strpos($content, 'Examples:') === false) {
            echo "⚠️ WARNING: {$file_path} - Missing examples in capabilities\n";
        }
    }
    
    /**
     * Validate actor type specific requirements
     */
    private function validateActorType($actor_id, &$score) {
        $type = $this->getActorType($actor_id);
        
        if (!isset($this->validation_rules[$type])) {
            echo "NOTE: No specific validation rules for actor type {$type}\n";
            return;
        }
        
        $rules = $this->validation_rules[$type];
        $required = $rules['required'] ?? [];
        $optional = $rules['optional'] ?? [];
        
        // Check required files
        foreach ($required as $file) {
            $file_path = "actors/{$actor_id}/{$file}";
            if (file_exists($file_path)) {
                $score += 10;
            }
        }
        
        // Bonus points for optional files
        foreach ($optional as $file) {
            $file_path = "actors/{$actor_id}/{$file}";
            if (file_exists($file_path)) {
                $score += 5;
            }
        }
    }
    
    /**
     * Validate consistency across sources
     */
    private function validateConsistency($actor_id, &$score) {
        // Check profile vs WHO.json (simulated)
        $profile_path = "actors/{$actor_id}/profile.md";
        $who_path = "actors/{$actor_id}/WHO.json";
        
        if (file_exists($profile_path) && file_exists($who_path)) {
            $profile_content = file_get_contents($profile_path);
            $who_content = file_get_contents($who_path);
            
            $profile_actor_id = $this->extractActorId($profile_content);
            $who_actor_id = $this->extractActorId($who_content);
            
            if ($profile_actor_id === $who_actor_id && $profile_actor_id === $actor_id) {
                echo "✅ Profile matches WHO.json\n";
                $score += 10;
            } else {
                echo "⚠️ WARNING: Profile/WHO.json mismatch\n";
            }
        }
    }
    
    /**
     * Extract actor_id from content
     */
    private function extractActorId($content) {
        if (preg_match('/actor_id\s*:\s*(\d+)/', $content, $matches)) {
            return (int)$matches[1];
        }
        return null;
    }
    
    /**
     * Get actor type based on ID
     */
    private function getActorType($actor_id) {
        // Core AI agents (0-5)
        if ($actor_id >= 0 && $actor_id <= 5) {
            return 'core_ai';
        }
        
        // System agents (19-25)
        if ($actor_id >= 19 && $actor_id <= 25) {
            return 'system';
        }
        
        // IDE agents (1000-1005)
        if ($actor_id >= 1000 && $actor_id <= 1005) {
            return 'ide';
        }
        
        // Human agents (10000+)
        if ($actor_id >= 10000) {
            return 'human';
        }
        
        return 'unknown';
    }
    
    /**
     * Validate all actors (optional --all flag)
     */
    public function validateAll() {
        echo "=== Actor Help Documentation Validation - v2 ===\n";
        
        $actors_dir = 'actors';
        $items = scandir($actors_dir);
        
        $total_actors = 0;
        $passing_actors = 0;
        $failing_actors = 0;
        $warning_actors = 0;
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            
            if (is_dir("actors/{$item}") && is_numeric($item)) {
                $total_actors++;
                $result = $this->validateActor($item);
                
                if ($result === 1) {
                    $passing_actors++;
                } elseif ($result === 0) {
                    $failing_actors++;
                } else {
                    $warning_actors++;
                }
            }
        }
        
        echo "\n=== SUMMARY ===\n";
        echo "Total Actors: {$total_actors}\n";
        echo "Passing: {$passing_actors}\n";
        echo "Failing: {$failing_actors}\n";
        echo "Warnings: {$warning_actors}\n";
        
        if ($failing_actors > 0) {
            echo "\n❌ VALIDATION FAILED - Some actors failing\n";
            exit(1);
        } else {
            echo "\n✅ VALIDATION PASSED - All actors passing\n";
            exit(0);
        }
    }
}

// CLI Interface
if (php_sapi_name() === 'cli') {
    $options = getopt('', ['all::', 'actor:']);
    
    if (isset($options['all'])) {
        $validator = new ActorHelpValidator();
        $validator->validateAll();
    } elseif (isset($options['actor'])) {
        $validator = new ActorHelpValidator();
        $result = $validator->validateActor($options['actor']);
        exit($result === 1 ? 0 : 1);
    } else {
        echo "Usage: php bin/validate_actor_help.php [--all] [--actor=<actor_id>]\n";
        echo "  --all        Validate all actors\n";
        echo "  --actor=<id> Validate specific actor\n";
        echo "\nExamples:\n";
        echo "  php bin/validate_actor_help.php --actor=0\n";
        echo "  php bin/validate_actor_help.php --all\n";
        exit(1);
    }
}
?>
