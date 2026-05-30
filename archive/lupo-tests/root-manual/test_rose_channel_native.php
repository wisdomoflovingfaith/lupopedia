<?php

/**
 * ROSE Channel-Native Test Script
 * 
 * This script demonstrates the new ROSE functionality by:
 * 1. Reading actual channel artifacts from lupo-channels/
 * 2. Synthesizing responses based on repository evidence
 * 3. Generating packet-style artifacts (~2000 characters)
 * 4. Writing to canonical lupo-chats/rose/json/ directory
 */

// Define LUPOPEDIA_PATH if not already defined
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(__FILE__));
}

// Include ROSE class
require_once(LUPOPEDIA_PATH . '/lupo-includes/classes/rose.php');

// Mock database class for testing
class MockDB
{
    public function getPdo()
    {
        return null;
    }
}

echo "=== ROSE Channel-Native Test ===\n\n";

try {
    // Initialize ROSE
    $db = new MockDB();
    $rose = new ROSE($db);
    
    echo "✅ ROSE initialized successfully\n";
    echo "📁 Channels path: " . $rose->channelsPath . "\n";
    echo "📁 Output path: " . $rose->outputPath . "\n\n";
    
    // Process channels with specific options
    $options = [
        'channels' => [42, 59], // Focus on key channels
        'max_artifacts' => 3,   // Generate 3 example packets
        'packet_size' => 2000,  // Target ~2000 characters
        'output_format' => 'json' // JSON format for canonical output
    ];
    
    echo "🔄 Processing channels...\n";
    echo "📋 Channels to scan: " . implode(', ', $options['channels']) . "\n";
    echo "📦 Max artifacts: " . $options['max_artifacts'] . "\n";
    echo "📏 Packet size: " . $options['packet_size'] . " characters\n\n";
    
    // Execute channel processing
    $results = $rose->processChannels($options);
    
    echo "📊 Processing Results:\n";
    echo "===================\n";
    
    if (empty($results)) {
        echo "⚠️ No artifacts generated. Check channel directories exist.\n";
    } else {
        foreach ($results as $index => $result) {
            echo "📄 Artifact " . ($index + 1) . ": " . $result['filename'] . "\n";
            echo "   📁 Path: " . $result['filepath'] . "\n";
            echo "   📏 Size: " . number_format($result['size']) . " bytes\n";
            echo "   📦 Packet size: " . number_format($result['packet_size']) . " characters\n";
            echo "\n";
        }
        
        echo "✅ Successfully generated " . count($results) . " ROSE artifacts\n";
        echo "📍 Artifacts written to: " . $rose->outputPath . "\n";
    }
    
    // Show example of generated artifact content
    if (!empty($results)) {
        $firstArtifact = $results[0];
        $artifactPath = $firstArtifact['filepath'];
        
        if (file_exists($artifactPath)) {
            echo "\n📖 Example Artifact Content:\n";
            echo "============================\n";
            
            $content = file_get_contents($artifactPath);
            $data = json_decode($content, true);
            
            if ($data && isset($data['packet'])) {
                $packet = $data['packet'];
                echo "🗣️ Speaker: " . $packet['speaker'] . "\n";
                echo "🎯 Target: " . $packet['target'] . "\n";
                echo "🎨 Mood Vector: " . $packet['mood_RGB'] . "\n";
                echo "⏰ Timestamp: " . $packet['timestamp_utc'] . "\n";
                echo "📏 Message length: " . strlen($packet['message']) . " characters\n";
                echo "📚 Sources: " . count($packet['sources']) . " references\n\n";
                
                echo "💬 Message (first 500 chars):\n";
                echo substr($packet['message'], 0, 500) . "...\n\n";
                
                if (!empty($packet['sources'])) {
                    echo "📚 Source References:\n";
                    foreach (array_slice($packet['sources'], 0, 3) as $source) {
                        echo "- " . $source['file_path'] . " (" . $source['actor'] . ")\n";
                    }
                    if (count($packet['sources']) > 3) {
                        echo "... and " . (count($packet['sources']) - 3) . " more\n";
                    }
                }
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";
