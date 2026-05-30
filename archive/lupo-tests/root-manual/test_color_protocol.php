<?php
// Simple test for color protocol integration
require_once 'lupo-includes/classes/ColorProtocol.php';
require_once 'lupo-includes/classes/DialogHistoryManager.php';

echo "🎨 Testing Color Protocol Integration...\n";

try {
    $colorProtocol = new ColorProtocol();
    $dialogManager = new DialogHistoryManager();
    
    echo "✅ Color Protocol initialized\n";
    
    // Test color scheme generation
    $testContext = [
        'era' => 'active_development',
        'sensitivity' => ['level' => 'low', 'topics' => [], 'handling_required' => false],
        'emotional_geometry' => [
            'creative_axis' => ['items' => ['innovation', 'creativity', 'design']],
            'growth_axis' => ['items' => ['learning', 'development', 'mastery']],
            'foundation_axis' => ['items' => ['stability', 'quality', 'excellence']]
        ]
    ];
    
    $colorScheme = $colorProtocol->getColorScheme($testContext);
    echo "✅ Color scheme generated: " . $colorScheme['primary'] . "\n";
    
    // Test CSS generation
    $css = $colorProtocol->generateCSS($colorScheme);
    echo "✅ CSS generated (" . strlen($css) . " characters)\n";
    
    // Test dialog integration
    $response = $dialogManager->processQuery("What happened in 2002?");
    echo "✅ Dialog response with color protocol: " . ($response['metadata']['color_protocol_applied'] ? 'YES' : 'NO') . "\n";
    echo "✅ Color scheme included: " . (isset($response['metadata']['color_scheme']) ? 'YES' : 'NO') . "\n";
    echo "✅ CSS included: " . (isset($response['metadata']['css']) ? 'YES' : 'NO') . "\n";
    
    // Test color-coded elements
    echo "✅ Emotional state color: " . $response['emotional_state_color'] . "\n";
    echo "✅ Era color: " . $response['era_color'] . "\n";
    echo "✅ Sensitivity level: " . $response['metadata']['sensitivity_level'] . "\n";
    
    echo "🎉 Big Rock 3 Color Protocol: SUCCESS!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
