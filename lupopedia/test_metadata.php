<?php
// Simple test for metadata extraction
require_once 'lupo-includes/classes/MetadataExtractor.php';

echo "🚀 Testing Metadata Extraction...\n";

try {
    $extractor = new MetadataExtractor();
    $metadata = $extractor->extractAllMetadata();
    
    echo "✅ Extracted metadata from " . count($metadata) . " files\n";
    
    // Test emotional context
    $context2002 = $extractor->getEmotionalContext('2002');
    echo "✅ 2002 Era: " . $context2002['era'] . "\n";
    
    $context2014 = $extractor->getEmotionalContext('2014');
    echo "✅ 2014 Era: " . $context2014['era'] . " (Sensitivity: " . $context2014['sensitivity']['level'] . ")\n";
    
    // Test cross-references
    $suggestions = $extractor->getCrossReferenceSuggestions('2002');
    echo "✅ Cross-references for 2002: " . count($suggestions, COUNT_RECURSIVE) . " total suggestions\n";
    
    echo "🎉 Big Rock 2 Metadata Extraction: SUCCESS!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
