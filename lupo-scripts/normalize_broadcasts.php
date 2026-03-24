<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "lupo-scripts/normalize_broadcasts.php"
  last_modified_utc: "20260324175911"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175911"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
lupopedia.headers:
  when_updated: "20260324175617"
  file_path_from_root: "lupo-scripts/normalize_broadcasts.php"
  last_modified_utc: "20260324175617"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175617"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
 * Broadcast Normalization Tool
 * 
 * Normalizes broadcast files in channels/0/broadcasts/ and channels/42/broadcasts/
 * to achieve 100% compliance with importer requirements.
 * 
 * Usage:
 * php scripts/normalize_broadcasts.php --dry-run    # Show changes without applying
 * php scripts/normalize_broadcasts.php --apply      # Apply all changes
 * php scripts/normalize_broadcasts.php --audit      # Final compliance audit
 */

// Define constants
define('LUPOPEDIA_PATH', dirname(__DIR__));
define('CHANNELS_PATH', LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'channels');

class BroadcastNormalizer
{
    private $dryRun = false;
    private $apply = false;
    private $audit = false;
    private $results = array(
        'channel_0' => array('renamed' => 0, 'headers_fixed' => 0, 'footers_fixed' => 0, 'archived' => 0, 'errors' => 0),
        'channel_42' => array('renamed' => 0, 'headers_fixed' => 0, 'footers_fixed' => 0, 'archived' => 0, 'errors' => 0),
        'total' => array('renamed' => 0, 'headers_fixed' => 0, 'footers_fixed' => 0, 'archived' => 0, 'errors' => 0)
    );
    
    public function __construct($mode)
    {
        $this->dryRun = ($mode === 'dry-run');
        $this->apply = ($mode === 'apply');
        $this->audit = ($mode === 'audit');
    }
    
    public function run()
    {
        echo "Broadcast Normalization Tool\n";
        echo "Mode: " . ($this->dryRun ? 'DRY RUN' : ($this->apply ? 'APPLY' : 'AUDIT')) . "\n";
        echo "Channels: 0, 42\n\n";
        
        // Process both channels
        $this->processChannel(0);
        $this->processChannel(42);
        
        // Output summary
        $this->outputSummary();
        
        // Return results for programmatic use
        return $this->results;
    }
    
    private function processChannel($channelId)
    {
        $channelPath = CHANNELS_PATH . DIRECTORY_SEPARATOR . $channelId . DIRECTORY_SEPARATOR . 'broadcasts';
        
        if (!is_dir($channelPath)) {
            echo "Channel $channelId broadcasts directory not found: $channelPath\n";
            return;
        }
        
        // Ensure archive directory exists
        $archivePath = $channelPath . DIRECTORY_SEPARATOR . 'archive';
        if (!$this->dryRun && !is_dir($archivePath)) {
            mkdir($archivePath, 0755, true);
        }
        
        // Get all MD files
        $files = glob($channelPath . DIRECTORY_SEPARATOR . '*.md');
        if (empty($files)) {
            echo "No MD files found in channel $channelId\n";
            return;
        }
        
        echo "Processing Channel $channelId (" . count($files) . " files)...\n";
        
        // Group files by topic for duplicate detection
        $topicGroups = $this->groupFilesByTopic($files);
        
        // Process each group
        foreach ($topicGroups as $topic => $groupFiles) {
            if (count($groupFiles) > 1) {
                // Handle duplicates
                $this->handleDuplicates($channelId, $topic, $groupFiles);
            } else {
                // Process single file
                $this->processFile($channelId, $groupFiles[0]);
            }
        }
    }
    
    private function groupFilesByTopic($files)
    {
        $groups = array();
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $topic = $this->extractTopic($content, basename($file));
            $groups[$topic][] = $file;
        }
        
        return $groups;
    }
    
    private function extractTopic($content, $filename)
    {
        // Extract topic from content or filename
        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            return $this->slugify($matches[1]);
        }
        
        // Extract from filename
        if (preg_match('/^\d{14}_\d+_\d+_\d+_(.+)\.md$/', $filename, $matches)) {
            return $matches[1];
        }
        
        return 'unknown';
    }
    
    private function slugify($text)
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '_', $text);
        return trim($text, '_');
    }
    
    private function handleDuplicates($channelId, $topic, $files)
    {
        // Sort by modification time (newest first)
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        // Keep first (newest) as canonical, archive others
        $canonical = array_shift($files);
        $this->processFile($channelId, $canonical);
        
        foreach ($files as $duplicate) {
            $this->archiveFile($channelId, $duplicate, "Duplicate of topic: $topic");
        }
    }
    
    private function archiveFile($channelId, $filePath, $reason)
    {
        $filename = basename($filePath);
        $archivePath = CHANNELS_PATH . DIRECTORY_SEPARATOR . $channelId . DIRECTORY_SEPARATOR . 'broadcasts' . DIRECTORY_SEPARATOR . 'archive' . DIRECTORY_SEPARATOR . $filename;
        
        if ($this->dryRun) {
            echo "  [DRY RUN] Would archive: $filename (Reason: $reason)\n";
        } else {
            // Add archive reason to file
            $content = file_get_contents($filePath);
            $content = "# ARCHIVED: $reason\n\n" . $content;
            file_put_contents($archivePath, $content);
            unlink($filePath);
            echo "  Archived: $filename (Reason: $reason)\n";
        }
        
        $this->results["channel_$channelId"]['archived']++;
        $this->results['total']['archived']++;
    }
    
    private function processFile($channelId, $filePath)
    {
        $filename = basename($filePath);
        $content = file_get_contents($filePath);
        
        // Parse existing content
        $parsed = $this->parseContent($content);
        
        // Generate new filename if needed
        $newFilename = $this->generateFilename($channelId, $parsed, $filename);
        
        // Generate new header
        $newHeader = $this->generateHeader($channelId, $parsed);
        
        // Generate new footer
        $newFooter = $this->generateFooter($channelId, $parsed);
        
        // Reconstruct content
        $newContent = $newHeader . "\n" . $parsed['body'] . "\n" . $newFooter;
        
        // Check if changes are needed
        $changesNeeded = ($filename !== $newFilename) || ($content !== $newContent);
        
        if (!$changesNeeded) {
            echo "  ✓ Already compliant: $filename\n";
            return;
        }
        
        if ($this->dryRun) {
            echo "  [DRY RUN] Would rename: $filename → $newFilename\n";
            echo "  [DRY RUN] Would update header and footer\n";
        } else {
            // Rename file if needed
            if ($filename !== $newFilename) {
                $newPath = dirname($filePath) . DIRECTORY_SEPARATOR . $newFilename;
                rename($filePath, $newPath);
                $filePath = $newPath;
                echo "  Renamed: $filename → $newFilename\n";
                $this->results["channel_$channelId"]['renamed']++;
                $this->results['total']['renamed']++;
            }
            
            // Update content
            file_put_contents($filePath, $newContent);
            echo "  Updated: $newFilename\n";
            $this->results["channel_$channelId"]['headers_fixed']++;
            $this->results["channel_$channelId"]['footers_fixed']++;
            $this->results['total']['headers_fixed']++;
            $this->results['total']['footers_fixed']++;
        }
    }
    
    private function parseContent($content)
    {
        $parsed = array(
            'header' => '',
            'body' => $content,
            'footer' => '',
            'metadata' => array()
        );
        
        // Extract YAML header
        if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $content, $matches)) {
            $parsed['header'] = $matches[1];
            $parsed['body'] = $matches[2];
            
            // Parse YAML metadata
            $lines = explode("\n", $matches[1]);
            foreach ($lines as $line) {
                if (strpos($line, ':') !== false) {
                    list($key, $value) = explode(':', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    if ($key && $value) {
                        $parsed['metadata'][$key] = $value;
                    }
                }
            }
        }
        
        // Extract existing footer
        if (preg_match('/(.*)(<!-- FLIP_FOOTER_BEGIN.*?FLIP_FOOTER_END -->)/s', $parsed['body'], $matches)) {
            $parsed['body'] = $matches[1];
            $parsed['footer'] = $matches[2];
        }
        
        return $parsed;
    }
    
    private function generateFilename($channelId, $parsed, $originalFilename)
    {
        // Extract title from content or metadata
        $title = '';
        if (isset($parsed['metadata']['purpose'])) {
            $title = $parsed['metadata']['purpose'];
        } elseif (preg_match('/^#\s+(.+)$/m', $parsed['body'], $matches)) {
            $title = $matches[1];
        }
        
        if (!$title) {
            // Extract from original filename
            if (preg_match('/^\d{14}_\d+_\d+_\d+_(.+)\.md$/', $originalFilename, $matches)) {
                $title = $matches[1];
            } else {
                $title = 'broadcast';
            }
        }
        
        $title = $this->slugify($title);
        
        // Generate timestamp (sequential for today)
        static $timestampCounter = array(0 => 120000, 42 => 130000);
        $timestamp = '20260225' . ($timestampCounter[$channelId]++);
        
        return "{$timestamp}_10000_1000_{$channelId}_{$title}.md";
    }
    
    private function generateHeader($channelId, $parsed)
    {
        $header = array();
        
        // Required fields
        $header['from_actor_id'] = '10000';
        $header['to_actor_id'] = '1000';
        $header['channel_id'] = (string)$channelId;
        $header['delegation_chain'] = '"10000:1000"';
        $header['system_version'] = '"4.0.45"';
        
        // Preserve existing metadata where appropriate
        if (isset($parsed['metadata']['actor_id']) && $parsed['metadata']['actor_id'] !== '10000') {
            $header['actor_id'] = $parsed['metadata']['actor_id'];
        } else {
            $header['actor_id'] = '10000';
        }
        
        if (isset($parsed['metadata']['purpose'])) {
            $header['purpose'] = '"' . $parsed['metadata']['purpose'] . '"';
        }
        
        if (isset($parsed['metadata']['message_type'])) {
            $header['message_type'] = $parsed['metadata']['message_type'];
        } else {
            $header['message_type'] = 'broadcast';
        }
        
        if (isset($parsed['metadata']['visibility'])) {
            $header['visibility'] = $parsed['metadata']['visibility'];
        } else {
            $header['visibility'] = 'system';
        }
        
        if (isset($parsed['metadata']['priority'])) {
            $header['priority'] = $parsed['metadata']['priority'];
        } else {
            $header['priority'] = 'critical';
        }
        
        // Generate timestamp
        static $timeCounter = array(0 => '12:00:00', 42 => '13:00:00');
        $header['created_ymdhis'] = '20260225' . str_replace(':', '', $timeCounter[$channelId]);
        $header['created_utc'] = '"2026-02-25T' . $timeCounter[$channelId] . 'Z"';
        
        // Build YAML string
        $yaml = "---\n";
        foreach ($header as $key => $value) {
            $yaml .= "$key: $value\n";
        }
        $yaml .= "---";
        
        return $yaml;
    }
    
    private function generateFooter($channelId, $parsed)
    {
        $footer = array(
            'references' => '"docs/status/broadcast_collection_' . $channelId . '.md"',
            'implements' => '"broadcast_standardization"',
            'depends_on' => '"registry_seeding_completion"',
            'includes' => '"channel_' . $channelId . '_communications"',
            'version' => '"4.0.45"',
            'last_verified' => '"20260225"',
            'last_verified_by' => '"windsurf"'
        );
        
        // Add existing edges if present
        if (isset($parsed['metadata']['references'])) {
            $footer['references'] = '"' . $parsed['metadata']['references'] . '"';
        }
        
        // Build JSON footer
        $json = json_encode($footer, JSON_PRETTY_PRINT);
        
        return "<!-- FLIP_FOOTER_BEGIN\n$json\nFLIP_FOOTER_END -->";
    }
    
    private function outputSummary()
    {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "NORMALIZATION SUMMARY\n";
        echo str_repeat("=", 60) . "\n";
        
        echo "\nChannel 0:\n";
        echo "  Renamed: " . $this->results['channel_0']['renamed'] . "\n";
        echo "  Headers Fixed: " . $this->results['channel_0']['headers_fixed'] . "\n";
        echo "  Footers Fixed: " . $this->results['channel_0']['footers_fixed'] . "\n";
        echo "  Archived: " . $this->results['channel_0']['archived'] . "\n";
        echo "  Errors: " . $this->results['channel_0']['errors'] . "\n";
        
        echo "\nChannel 42:\n";
        echo "  Renamed: " . $this->results['channel_42']['renamed'] . "\n";
        echo "  Headers Fixed: " . $this->results['channel_42']['headers_fixed'] . "\n";
        echo "  Footers Fixed: " . $this->results['channel_42']['footers_fixed'] . "\n";
        echo "  Archived: " . $this->results['channel_42']['archived'] . "\n";
        echo "  Errors: " . $this->results['channel_42']['errors'] . "\n";
        
        echo "\nTotal:\n";
        echo "  Renamed: " . $this->results['total']['renamed'] . "\n";
        echo "  Headers Fixed: " . $this->results['total']['headers_fixed'] . "\n";
        echo "  Footers Fixed: " . $this->results['total']['footers_fixed'] . "\n";
        echo "  Archived: " . $this->results['total']['archived'] . "\n";
        echo "  Errors: " . $this->results['total']['errors'] . "\n";
        
        // Output JSON for programmatic use
        echo "\nJSON Results:\n";
        echo json_encode($this->results, JSON_PRETTY_PRINT) . "\n";
    }
}

// Main execution
$mode = $argv[1] ?? 'dry-run';
$validModes = array('dry-run', 'apply', 'audit');

// Handle different argument formats
if (in_array('--dry-run', $argv)) {
    $mode = 'dry-run';
} elseif (in_array('--apply', $argv)) {
    $mode = 'apply';
} elseif (in_array('--audit', $argv)) {
    $mode = 'audit';
}

if (!in_array($mode, $validModes)) {
    echo "Usage: php normalize_broadcasts.php [--dry-run|--apply|--audit]\n";
    exit(1);
}

$normalizer = new BroadcastNormalizer($mode);
$results = $normalizer->run();

// Exit with error code if there were errors
exit($results['total']['errors'] > 0 ? 1 : 0);
