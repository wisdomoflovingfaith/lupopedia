<?php

/**
 * Simple ROSE Test - Generate Example Artifact
 */

// Define LUPOPEDIA_PATH if not already defined
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(__FILE__));
}

// Include ROSE class
require_once(LUPOPEDIA_PATH . '/includes/classes/rose.php');

// Mock database class
class MockDB
{
    public function getPdo()
    {
        return null;
    }
}

echo "=== Simple ROSE Test ===\n\n";

try {
    // Initialize ROSE
    $db = new MockDB();
    $rose = new ROSE($db);
    
    echo "✅ ROSE initialized\n\n";
    
    // Create sample channel data based on actual repository
    $sampleChannelData = [
        'actor' => 'wolfie',
        'artifacts' => [
            [
                'file_path' => LUPOPEDIA_PATH . '/channels/42/threads/1001/20260323_234500_wolfie_rose_identity_canonization_and_agent_propagation.md',
                'actor_name' => 'wolfie',
                'body' => 'Channel 42 Summary - ROSE Canonization Complete

Status: DOCTRINE_UPDATE_REQUIRED -> COMPLETE
Scope: ROSE_IDENTITY_CANONIZATION

## Completed Outputs

1. Doctrine file created:
- docs/doctrine/ROSE_DOCTRINE.md

2. Agent propagation completed in canonical ROSE agent path:
- agents/3/.metadata.yaml
- agents/3/agent.json
- agents/3/properties.json
- agents/3/capabilities.json
- agents/3/system_prompt.txt',
                'timestamp' => '20260323_234500',
                'artifact_type' => 'status_post'
            ]
        ]
    ];
    
    // Generate a ROSE packet
    echo "🔄 Generating ROSE packet...\n";
    $packet = $rose->generatePacket($sampleChannelData, 'positive');
    
    echo "📦 Packet generated:\n";
    echo "- Speaker: " . $packet['speaker'] . "\n";
    echo "- Target: " . $packet['target'] . "\n";
    echo "- Mood Vector: " . $packet['mood_RGB'] . "\n";
    echo "- Timestamp: " . $packet['timestamp_utc'] . "\n";
    echo "- Packet size: " . strlen($packet['message']) . " characters\n";
    echo "- Sources: " . count($packet['sources']) . " references\n\n";
    
    // Write the artifact manually
    $outputDir = LUPOPEDIA_PATH . '/chats/rose/json';
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
        echo "📁 Created output directory: " . $outputDir . "\n";
    }
    
    $filename = $packet['timestamp_utc'] . '_DIALOG_channel_native_rose_implementation.json';
    $filepath = $outputDir . '/' . $filename;
    
    $artifact = [
        'artifact_type' => 'rose_dialogue_packet',
        'artifact_kind' => 'channel_synthesis',
        'version_when_written' => '4.0.87',
        'generated_utc' => $packet['timestamp_utc'],
        'title' => 'Channel-Native ROSE Implementation Example',
        'dialog_title_slug' => 'channel_native_rose_implementation',
        'packet' => $packet,
        'metadata' => [
            'speaker' => $packet['speaker'],
            'target' => $packet['target'],
            'mood_RGB' => $packet['mood_RGB'],
            'packet_size' => $packet['packet_size'],
            'sources_count' => count($packet['sources']),
            'implementation_note' => 'Example artifact demonstrating ROSE channel-native functionality'
        ]
    ];
    
    $jsonContent = json_encode($artifact, JSON_PRETTY_PRINT);
    
    if (file_put_contents($filepath, $jsonContent)) {
        echo "✅ Artifact written: " . $filename . "\n";
        echo "📍 Path: " . $filepath . "\n";
        echo "📏 Size: " . number_format(strlen($jsonContent)) . " bytes\n\n";
        
        echo "📖 Message preview (first 300 chars):\n";
        echo substr($packet['message'], 0, 300) . "...\n\n";
        
        echo "📚 Source references:\n";
        foreach ($packet['sources'] as $source) {
            echo "- " . $source['file_path'] . " (" . $source['actor'] . ")\n";
        }
    } else {
        echo "❌ Failed to write artifact\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";
