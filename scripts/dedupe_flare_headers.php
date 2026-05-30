<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "scripts/dedupe_flare_headers.php"
  questions_toon: null
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
  file_path_from_root: "scripts/dedupe_flare_headers.php"
  questions_toon: null
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
 * FLARE Header Deduplication Script
 * 
 * Removes duplicate FLARE headers from markdown files, keeping only the first header block
 * per FLARE doctrine requirements.
 * 
 * @author Windsurf (1002)
 * @version 4.0.52
 * @date 2026-03-01
 */

class FlareHeaderDeduper {
    private $repoRoot;
    private $dryRun = false;
    private $filesProcessed = 0;
    private $headersRemoved = 0;
    
    public function __construct($repoRoot, $dryRun = false) {
        $this->repoRoot = $repoRoot;
        $this->dryRun = $dryRun;
    }
    
    /**
     * Process all markdown files in repository
     */
    public function dedupeAll() {
        $this->processDirectory($this->repoRoot);
        
        echo "\n=== FLARE Header Deduplication Summary ===\n";
        echo "Files processed: {$this->filesProcessed}\n";
        echo "Headers removed: {$this->headersRemoved}\n";
        echo "Dry run: " . ($this->dryRun ? 'YES' : 'NO') . "\n";
        
        if ($this->headersRemoved > 0) {
            echo "⚠️  Duplicate headers found and removed!\n";
        } else {
            echo "✅ No duplicate headers found.\n";
        }
    }
    
    /**
     * Process directory recursively
     */
    private function processDirectory($dir) {
        $items = scandir($dir);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            
            $path = $dir . '/' . $item;
            
            if (is_dir($path)) {
                $this->processDirectory($path);
            } elseif (substr($path, -3) === '.md') {
                $this->processMarkdownFile($path);
            }
        }
    }
    
    /**
     * Process individual markdown file
     */
    private function processMarkdownFile($filePath) {
        $content = file_get_contents($filePath);
        
        if ($content === false) {
            echo "Error reading file: $filePath\n";
            return;
        }
        
        // Find all FLARE header blocks
        $pattern = '/---\nflare\.headers:.*?\n---.*?\n---/s';
        
        if (!preg_match_all($pattern, $content, $matches)) {
            // No FLARE headers found
            return;
        }
        
        $this->filesProcessed++;
        
        if (count($matches) <= 1) {
            // Only one header - no deduplication needed
            return;
        }
        
        // Remove duplicate headers, keep only the first one
        $firstHeader = $matches[0];
        $remainingContent = preg_replace($pattern, $firstHeader, $content);
        
        // Remove all additional header blocks
        $cleanPattern = '/---\nflare\.headers:.*?\n---.*?\n---/s';
        $cleanContent = preg_replace($cleanPattern, '', $remainingContent, 1);
        
        if ($this->dryRun) {
            echo "DRY RUN: Would process {$filePath}\n";
            echo "  Headers found: " . count($matches) . "\n";
            echo "  Headers to remove: " . (count($matches) - 1) . "\n";
            return;
        }
        
        // Write back the cleaned content
        $result = file_put_contents($filePath, $cleanContent);
        
        if ($result !== false) {
            $this->headersRemoved += (count($matches) - 1);
            echo "Processed: $filePath\n";
            echo "  Headers found: " . count($matches) . "\n";
            echo "  Headers removed: " . (count($matches) - 1) . "\n";
        } else {
            echo "Error writing file: $filePath\n";
        }
    }
}

// String endsWith function for PHP < 8.0
if (!function_exists('endsWith')) {
    function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }
}

// Main execution
if ($argc < 2) {
    echo "Usage: php dedupe_flare_headers.php <repo_root> [--dry-run]\n";
    exit(1);
}

$repoRoot = $argv[1];
$dryRun = in_array('--dry-run', $argv) ? true : false;

$deduper = new FlareHeaderDeduper($repoRoot, $dryRun);
$deduper->dedupeAll();
?>
