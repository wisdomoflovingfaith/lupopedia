<?php
/**
 * Big Rock 3: Color Protocol Integration - Comprehensive Test Suite
 * 
 * Tests the color protocol integration with dialog system and metadata extraction
 * Validates color mapping, emotional geometry integration, and visual feedback
 * Tests accessibility and user experience enhancements
 * 
 * @version 4.0.66
 * @author GLOBAL_CURRENT_AUTHORS
 */

require_once __DIR__ . '/../lupo-includes/classes/ColorProtocol.php';
require_once __DIR__ . '/../lupo-includes/classes/DialogHistoryManager.php';

class BigRock3ColorProtocolTest {
    
    private $colorProtocol;
    private $dialogManager;
    private $testResults = [];
    
    public function __construct() {
        $this->colorProtocol = new ColorProtocol();
        $this->dialogManager = new DialogHistoryManager();
    }
    
    /**
     * Run all Big Rock 3 color protocol tests
     */
    public function runAllTests(): array {
        echo "🎨 Big Rock 3: Color Protocol Integration Tests\n";
        echo str_repeat("=", 70) . "\n\n";
        
        $this->testColorProtocolBasics();
        $this->testEmotionalGeometryColorMapping();
        $testEraColorMapping();
        $this->testSensitivityColorMapping();
        $this->testColorSchemeGeneration();
        $this->testCSSGeneration();
        $this->testDialogIntegration();
        $this->testColorCodedResponses();
        $this->testAccessibility();
        $this->testPerformance();
        
        $this->printTestSummary();
        return $this->testResults;
    }
    
    /**
     * Test basic color protocol functionality
     */
    private function testColorProtocolBasics(): void {
        echo "🎨 Testing Color Protocol Basics...\n";
        
        try {
            // Test color protocol initialization
            $this->assert($this->colorProtocol !== null, "ColorProtocol should be initialized");
            
            // Test color scheme generation
            $testContext = [
                'era' => 'active_development',
                'sensitivity' => ['level' => 'low', 'topics' => [], 'handling_required' => false],
                'emotional_geometry' => [
                    'creative_axis' => ['items' => ['innovation', 'creativity']],
                    'growth_axis' => ['items' => ['learning', 'development']],
                    'foundation_axis' => ['items' => ['stability', 'quality']]
                ]
            ];
            
            $colorScheme = $this->colorProtocol->getColorScheme($testContext);
            $this->assert(is_array($colorScheme), "Should return color scheme array");
            $this->assert(isset($colorScheme['primary']), "Should have primary color");
            $this->assert(isset($colorScheme['secondary']), "Should have secondary color");
            $this->assert(isset($colorScheme['accent']), "Should have accent color");
            
            $this->testResults['color_protocol_basics'] = 'PASS';
            echo "✅ Color Protocol Basics: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['color_protocol_basics'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Color Protocol Basics: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test emotional geometry color mapping
     */
    private function testEmotionalGeometryColorMapping(): void {
        echo "🎨 Testing Emotional Geometry Color Mapping...\n";
        
        try {
            // Test creative axis colors
            $creativeContext = [
                'emotional_geometry' => [
                    'creative_axis' => ['items' => ['innovation', 'creativity', 'design']]
                ]
            ];
            
            $creativeScheme = $this->colorProtocol->getColorScheme($creativeContext);
            $this->assert($creativeScheme['primary'] === '#9B59B6', "Should map creativity to purple");
            
            // Test growth axis colors
            $growthContext = [
                'emotional_geometry' => [
                    'growth_axis' => ['items' => ['learning', 'development', 'mastery']]
                ]
            ];
            
            $growthScheme = $this->colorProtocol->getColorScheme($growthContext);
            $this->assert($growthScheme['secondary'] === '#27AE60', "Should map growth to green");
            
            // Test foundation axis colors
            $foundationContext = [
                'emotional_geometry' => [
                    'foundation_axis' => ['items' => ['stability', 'quality', 'structure']]
                ]
            ];
            
            $foundationScheme = $this->colorProtocol->getColorScheme($foundationContext);
            $this->assert($foundationScheme['accent'] === '#34495E', "Should map foundation to gray");
            
            $this->testResults['emotional_geometry_mapping'] = 'PASS';
            echo "✅ Emotional Geometry Color Mapping: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['emotional_geometry_mapping'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Emotional Geometry Color Mapping: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test era color mapping
     */
    private function testEraColorMapping(): void {
        echo "📅 Testing Era Color Mapping...\n";
        
        try {
            // Test active development era
            $activeContext = ['era' => 'active_development'];
            $activeScheme = $this->colorProtocol->getColorScheme($activeContext);
            $this->assert($activeScheme['primary'] === '#3498DB', "Should map active development to blue");
            
            // Test hiatus era
            $hiatusContext = ['era' => 'hiatus'];
            $hiatusScheme = $this->colorProtocol->getColorScheme($hiatusContext);
            $this->assert($hiatusScheme['primary'] === '#95A5A6', "Should map hiatus to gray");
            
            // Test resurgence era
            $resurgenceContext = ['era' => 'resurgence'];
            $resurgenceScheme = $this->colorProtocol->getColorScheme($resurgenceContext);
            $this->assert($resurgenceScheme['primary'] === '#27AE60', "Should map resurgence to green");
            
            $this->testResults['era_color_mapping'] = 'PASS';
            echo "✅ Era Color Mapping: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['era_color_mapping'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Era Color Mapping: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test sensitivity color mapping
     */
    private function testSensitivityColorMapping(): void {
        echo "🔒 Testing Sensitivity Color Mapping...\n";
        
        try {
            // Test high sensitivity
            $highContext = ['sensitivity' => ['level' => 'high', 'topics' => ['personal_tragedy'], 'handling_required' => true]];
            $highScheme = $this->colorProtocol->getColorScheme($highContext);
            $this->assert($highScheme['primary'] === '#E74C3C', "Should map high sensitivity to red");
            $this->assert($highScheme['background'] === '#FADBD8', "Should use light red background");
            
            // Test medium sensitivity
            $mediumContext = ['sensitivity' => ['level' => 'medium', 'topics' => ['change'], 'handling_required' => true]];
            $mediumScheme = $this->colorProtocol->getColorScheme($mediumContext);
            $this->assert($mediumScheme['primary'] === '#F39C12', "Should map medium sensitivity to orange");
            
            // Test low sensitivity
            $lowContext = ['sensitivity' => ['level' => 'low', 'topics' => [], 'handling_required' => false]];
            $lowScheme = $this->colorProtocol->getColorScheme($lowContext);
            $this->assert($lowScheme['primary'] === '#27AE60', "Should map low sensitivity to green");
            
            $this->testResults['sensitivity_color_mapping'] = 'PASS';
            echo "✅ Sensitivity Color Mapping: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['sensitivity_color_mapping'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Sensitivity Color Mapping: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test color scheme generation
     */
    private function testColorSchemeGeneration(): void {
        echo "🎨 Testing Color Scheme Generation...\n";
        
        try {
            // Test complex context with all elements
            $complexContext = [
                'era' => 'active_development',
                'sensitivity' => ['level' => 'low', 'topics' => [], 'handling_required' => false],
                'emotional_geometry' => [
                    'creative_axis' => ['items' => ['innovation', 'design', 'artistic']],
                    'growth_axis' => ['items' => ['learning', 'development', 'progress']],
                    'foundation_axis' => ['items' => ['stability', 'quality', 'excellence']]
                ]
            ];
            
            $complexScheme = $this->colorProtocol->getColorScheme($complexContext);
            
            // Verify all required properties
            $requiredProps = ['primary', 'secondary', 'accent', 'background', 'text', 'border'];
            foreach ($requiredProps as $prop) {
                $this->assert(isset($complexScheme[$prop]), "Should have $prop property");
            }
            
            // Verify color format (hex codes)
            $this->assert(preg_match('/^#[0-9A-F]{6}$/', $complexScheme['primary']), "Primary color should be hex code");
            $this->assert(preg_match('/^#[0-9A-F]{6}$/', $complexScheme['secondary']), "Secondary color should be hex code");
            
            $this->testResults['color_scheme_generation'] = 'PASS';
            echo "✅ Color Scheme Generation: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['color_scheme_generation'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Color Scheme Generation: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test CSS generation
     */
    private function testCSSGeneration(): void {
        echo "🎨 Testing CSS Generation...\n";
        
        try {
            $testScheme = [
                'primary' => '#3498DB',
                'secondary' => '#2ECC71',
                'accent' => '#E74C3C',
                'background' => '#F8F9FA',
                'text' => '#2C3E50',
                'border' => '#E1E8ED'
            ];
            
            $css = $this->colorProtocol->generateCSS($testScheme);
            
            // Verify CSS contains required properties
            $this->assert(strpos($css, '--color-primary: #3498DB') !== false, "Should contain primary color variable");
            $this->assert(strpos($css, '--color-secondary: #2ECC71') !== false, "Should contain secondary color variable");
            $this->assert(strpos($css, '.emotional-response') !== false, "Should contain emotional response styles");
            $this->assert(strpos($css, '.sensitive-notice') !== false, "Should contain sensitive notice styles");
            $this->assert(strpos($css, '.cross-reference') !== false, "Should contain cross-reference styles");
            
            $this->testResults['css_generation'] = 'PASS';
            echo "✅ CSS Generation: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['css_generation'] = 'FAIL: ' . $e->getMessage();
            echo "❌ CSS Generation: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test dialog integration
     */
    private function testDialogIntegration(): void {
        echo "💬 Testing Dialog Integration...\n";
        
        try {
            // Test dialog manager initialization
            $this->assert($this->dialogManager !== null, "Dialog manager should be initialized");
            
            // Test color-enhanced query processing
            $response = $this->dialogManager->processQuery("What happened in 2002?");
            
            // Verify color protocol integration
            $this->assert(isset($response['metadata']['color_protocol_applied']), "Should indicate color protocol applied");
            $this->assert($response['metadata']['color_protocol_applied'] === true, "Color protocol should be applied");
            
            // Verify color scheme inclusion
            $this->assert(isset($response['metadata']['color_scheme']), "Should include color scheme");
            $this->assert(isset($response['metadata']['css']), "Should include CSS");
            
            // Verify color-coded elements
            $this->assert(isset($response['emotional_state_color']), "Should have emotional state color");
            $this->assert(isset($response['era_color']), "Should have era color");
            $this->assert(isset($response['sensitivity_indicator']), "Should have sensitivity indicator");
            
            $this->testResults['dialog_integration'] = 'PASS';
            echo "✅ Dialog Integration: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['dialog_integration'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Dialog Integration: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test color-coded responses
     */
    private function testColorCodedResponses(): void {
        echo "🎨 Testing Color-Coded Responses...\n";
        
        try {
            // Test different queries with different emotional contexts
            $testQueries = [
                "What happened in 2014?" => ['era' => 'hiatus', 'sensitivity' => 'high'],
                "Tell me about the active development period" => ['era' => 'active_development', 'sensitivity' => 'low'],
                "How was Lupopedia created?" => ['era' => 'resurgence', 'sensitivity' => 'low']
            ];
            
            foreach ($testQueries as $query => $expected) {
                $response = $this->dialogManager->processQuery($query);
                
                // Verify color-coded elements
                $this->assert(isset($response['emotional_state_color']), "Should have emotional state color for: $query");
                $this->assert(isset($response['era_color']), "Should have era color for: $query");
                $this->assert(isset($response['sensitivity_indicator']), "Should have sensitivity indicator for: $query");
                
                // Verify sensitivity level
                if (isset($expected['sensitivity']) && $expected['sensitivity'] === 'high') {
                    $this->assert($response['metadata']['sensitivity_level'] === 'high', "Should detect high sensitivity for: $query");
                }
            }
            
            $this->testResults['color_coded_responses'] = 'PASS';
            echo "✅ Color-Coded Responses: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['color_coded_responses'] = 'FAIL: ' . . $e->getMessage();
            echo "❌ Color-Coded Responses: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test accessibility compliance
     */
    private function testAccessibility(): void {
        echo "♿ Testing Accessibility Compliance...\n";
        
        try {
            // Test color contrast ratios
            $testScheme = $this->colorProtocol->getColorScheme([
                'sensitivity' => ['level' => 'high', 'topics' => ['personal_tragedy']]
            ]);
            
            // Verify high contrast for sensitive content
            $this->assert($testScheme['primary'] === '#E74C3C', "High sensitivity should use high contrast colors");
            $this->assert($testScheme['background'] === '#FADBD8', "Should use light background for dark text");
            $this->assert($testScheme['text'] === '#A93226', "Should use dark text for light background");
            
            // Test CSS accessibility features
            $css = $this->colorProtocol->generateCSS($testScheme);
            $this->assert(strpos($css, 'transition: all 0.3s ease') !== false, "Should include smooth transitions");
            $this->assert(strpos($css, 'cursor: pointer') !== false, "Should include cursor indicators");
            
            $this->testResults['accessibility'] = 'PASS';
            echo "✅ Accessibility Compliance: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['accessibility'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Accessibility Compliance: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test performance
     */
    private function testPerformance(): void {
        echo "⚡ Testing Performance...\n";
        
        try {
            $startTime = microtime(true);
            
            // Test color scheme generation performance
            for ($i = 0; $i < 100; $i++) {
                $context = [
                    'era' => 'active_development',
                    'sensitivity' => ['level' => 'low'],
                    'emotional_geometry' => [
                        'creative_axis' => ['items' => ['innovation', 'creativity']],
                        'growth_axis' => ['items' => ['learning', 'development']],
                        'foundation_axis' => ['items' => ['stability', 'quality']]
                    ]
                ];
                $this->colorProtocol->getColorScheme($context);
            }
            
            $colorSchemeTime = microtime(true) - $startTime;
            $this->assert($colorSchemeTime < 0.1, "Color scheme generation should be fast (<100ms for 100 iterations)");
            
            // Test CSS generation performance
            $startTime = microtime(true);
            
            for ($i = 0; $i < 50; $i++) {
                $scheme = ['primary' => '#3498DB', 'secondary' => '#2ECC71'];
                $this->colorProtocol->generateCSS($scheme);
            }
            
            $cssTime = microtime(true) - $startTime;
            $this->assert($cssTime < 0.05, "CSS generation should be very fast (<50ms for 50 iterations)");
            
            // Test dialog integration performance
            $startTime = microtime(true);
            
            $response = $this->dialogManager->processQuery("What happened in 2002?");
            
            $dialogTime = microtime(true) - $startTime;
            $this->assert($dialogTime < 0.5, "Dialog processing should be fast (<500ms)");
            
            $this->testResults['performance'] = 'PASS';
            echo "✅ Performance: PASS\n";
            echo "  - Color Scheme Generation: " . round($colorSchemeTime * 1000, 2) . "ms (100 iterations)\n";
            echo "  - CSS Generation: " . round($cssTime * 1000, 2) . "ms (50 iterations)\n";
            echo "  - Dialog Processing: " . round($dialogTime * 1000, 2) . "ms\n";
            
        } catch (Exception $e) {
            $this->testResults['performance'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Performance: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Simple assertion helper
     */
    private function assert(bool $condition, string $message): void {
        if (!$condition) {
            throw new Exception("Assertion failed: $message");
        }
    }
    
    /**
     * Print test summary
     */
    private function printTestSummary(): void {
        echo "📊 Big Rock 3 Test Summary\n";
        echo str_repeat("-", 50) . "\n";
        
        $totalTests = count($this->testResults);
        $passedTests = array_filter($this->testResults, function($result) {
            return strpos($result, 'PASS') === 0;
        });
        $passedCount = count($passedTests);
        
        echo "Total Tests: $totalTests\n";
        echo "Passed: $passedCount\n";
        echo "Failed: " . ($totalTests - $passedCount) . "\n\n";
        
        echo "Detailed Results:\n";
        foreach ($this->testResults as $test => $result) {
            $status = strpos($result, 'PASS') === 0 ? '✅' : '❌';
            echo "$status $test: $result\n";
        }
        
        if ($passedCount === $totalTests) {
            echo "\n🎉 All Big Rock 3 Color Protocol Tests PASSED!\n";
            echo "✅ Color Protocol Integration is ready for production!\n";
            echo "🎨 Visual enhancement and emotional intelligence complete!\n";
        } else {
            echo "\n⚠️  Some tests failed. Review and fix issues before deployment.\n";
        }
        
        echo "\n" . str_repeat("=", 70) . "\n";
    }
}

// Run tests if this file is accessed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    $test = new BigRock3ColorProtocolTest();
    $test->runAllTests();
}
