<?php
/**
 * Test Dialog Migration Components
 * Tests the migration system without database dependency
 */

require_once 'lupo-includes/DialogChannelMigration/DialogParser.php';

echo "=== TESTING DIALOG MIGRATION COMPONENTS ===\n\n";

try {
    // Test 1: Dialog Parser
    echo "1. Testing Dialog Parser...\n";
    $parser = new DialogParser();
    
    // Test with a sample dialog file
    $testFiles = [
        'dialogs/changelog_dialog.md',
        'dialogs/session_2026_01_16_version_4_0_46.md',
        'dialogs/humor_context_WOLFIE_LUPOPEDIA.md'
    ];
    
    $totalMessages = 0;
    $filesProcessed = 0;
    
    foreach ($testFiles as $file) {
        if (file_exists($file)) {
            echo "  Processing: " . basename($file) . "\n";
            
            try {
                $parsed = $parser->parseDialogFile($file);
                $messageCount = count($parsed['messages']);
                $totalMessages += $messageCount;
                $filesProcessed++;
                
                echo "    - Messages found: {$messageCount}\n";
                echo "    - Has WOLFIE header: " . (empty($parsed['metadata']['raw_header']) ? 'No' : 'Yes') . "\n";
                echo "    - Speaker: " . ($parsed['metadata']['speaker'] ?: 'Not specified') . "\n";
                echo "    - Errors: " . count($parsed['errors']) . "\n";
                echo "    - Warnings: " . count($parsed['warnings']) . "\n";
                
                if (!empty($parsed['errors'])) {
                    foreach ($parsed['errors'] as $error) {
                        echo "      ERROR: {$error}\n";
                    }
                }
                
            } catch (Exception $e) {
                echo "    ERROR: " . $e->getMessage() . "\n";
            }
            
            echo "\n";
        }
    }
    
    echo "📊 PARSING SUMMARY:\n";
    echo "Files processed: {$filesProcessed}\n";
    echo "Total messages: {$totalMessages}\n";
    
    // Test 2: Channel Name Generation
    echo "\n2. Testing Channel Name Generation...\n";
    $testFileNames = [
        'changelog_dialog.md',
        'session_2026_01_16_version_4_0_46.md',
        'humor_context_WOLFIE_LUPOPEDIA.md',
        'changelog_dialog_that_pertains_to_whatever_the_fuck_we_are_doing.md'
    ];
    
    foreach ($testFileNames as $fileName) {
        $channelName = generateChannelName($fileName);
        echo "  {$fileName} → {$channelName}\n";
    }
    
    // Test 3: Scan all dialog files
    echo "\n3. Scanning all dialog files...\n";
    $dialogFiles = glob('dialogs/*.md');
    echo "Found " . count($dialogFiles) . " dialog files:\n";
    
    $totalFilesWithHeaders = 0;
    $totalAllMessages = 0;
    
    foreach ($dialogFiles as $file) {
        $fileName = basename($file);
        $content = file_get_contents($file);
        $hasHeader = strpos($content, '---') === 0;
        $messageCount = substr_count($content, '**Speaker:**') + substr_count($content, 'Speaker:');
        
        if ($hasHeader) $totalFilesWithHeaders++;
        $totalAllMessages += $messageCount;
        
        $headerStatus = $hasHeader ? '✅' : '❌';
        echo sprintf("  %-50s %s (%d msgs)\n", $fileName, $headerStatus, $messageCount);
    }
    
    echo "\n📊 MIGRATION SCOPE:\n";
    echo "Total files: " . count($dialogFiles) . "\n";
    echo "Files with headers: {$totalFilesWithHeaders}\n";
    echo "Files needing headers: " . (count($dialogFiles) - $totalFilesWithHeaders) . "\n";
    echo "Estimated total messages: {$totalAllMessages}\n";
    
    echo "\n✅ COMPONENT TESTING COMPLETE\n";
    echo "Ready for database migration when database is configured.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

function generateChannelName($fileName) {
    $name = preg_replace('/\.md$/', '', $fileName);
    $name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
    $name = preg_replace('/_+/', '_', $name);
    return strtolower(trim($name, '_'));
}
?>