<?php
/**
 * Faucet Integrity Audit - Cross-Channel Integrity Checker
 *
 * Checks cross-channel integrity: duplicate slugs, orphan files, missing actor faucets.
 * Includes id-scoped faucets: database/lupopedia/actors/faucets/<id>/faucet.json
 *
 * @author Windsurf (1002), Cursor (1003)
 * @version 4.0.56
 */

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(__DIR__));
}
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'bootstrap.php';

class FaucetIntegrityAuditor {
    private $all_faucets = array();
    private $channels = array();
    private $issues = array();
    private $base_path = null;

    public function __construct() {
        $this->resolveBasePath();
        $this->loadAllFaucets();
    }

    private function resolveBasePath() {
        if (defined('LUPO_DATABASE_DIR') && LUPO_DATABASE_DIR) {
            $this->base_path = rtrim(LUPO_DATABASE_DIR, DIRECTORY_SEPARATOR . '/\\') . DIRECTORY_SEPARATOR . 'lupopedia';
        } else {
            $root = defined('LUPOPEDIA_PATH') && LUPOPEDIA_PATH ? rtrim(LUPOPEDIA_PATH, DIRECTORY_SEPARATOR . '/\\') : dirname(__DIR__);
            $this->base_path = $root . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'lupopedia';
        }
    }

    /**
     * Load all faucet files (channels + id-scoped)
     */
    private function loadAllFaucets() {
        $sep = DIRECTORY_SEPARATOR;
        $channels_dir = (defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : dirname(__DIR__)) . $sep . 'channels';
        if (is_dir($channels_dir)) {
            $items = scandir($channels_dir);
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') {
                    continue;
                }
                $channel_path = $channels_dir . $sep . $item;
                if (is_dir($channel_path) && is_numeric($item)) {
                    $this->loadChannelFaucets($item, $channel_path);
                }
            }
        }
        $id_scoped_dir = $this->base_path . $sep . 'actors' . $sep . 'faucets';
        if (is_dir($id_scoped_dir)) {
            $items = scandir($id_scoped_dir);
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') {
                    continue;
                }
                $path = $id_scoped_dir . $sep . $item;
                if (is_dir($path)) {
                    $faucet_file = $path . $sep . 'faucet.json';
                    if (file_exists($faucet_file)) {
                        $content = file_get_contents($faucet_file);
                        $faucet = json_decode($content, true);
                        if (json_last_error() === JSON_ERROR_NONE && isset($faucet['agent_faucet_id'])) {
                            $this->all_faucets[] = array(
                                'channel_id' => isset($faucet['domain_id']) ? $faucet['domain_id'] : null,
                                'type' => 'id_scoped',
                                'faucet' => $faucet,
                                'file' => $faucet_file
                            );
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Load faucets from specific channel
     */
    private function loadChannelFaucets($channel_id, $channel_path) {
        $this->channels[$channel_id] = [
            'channel_wide_faucets' => [],
            'per_actor_faucets' => [],
            'actors' => []
        ];
        
        // Load channel-wide faucets
        $channel_wide_file = $channel_path . '/faucets.json';
        if (file_exists($channel_wide_file)) {
            $content = file_get_contents($channel_wide_file);
            $faucets = json_decode($content, true);
            
            if (json_last_error() === JSON_ERROR_NONE && isset($faucets['faucets'])) {
                $this->channels[$channel_id]['channel_wide_faucets'] = $faucets['faucets'];
                
                foreach ($faucets['faucets'] as $faucet) {
                    $this->all_faucets[] = [
                        'channel_id' => $channel_id,
                        'type' => 'channel_wide',
                        'faucet' => $faucet,
                        'file' => $channel_wide_file
                    ];
                }
            }
        }
        
        // Load per-actor faucets
        $actors_path = $channel_path . '/actors';
        if (is_dir($actors_path)) {
            $actors = scandir($actors_path);
            
            foreach ($actors as $actor_id) {
                if ($actor_id === '.' || $actor_id === '..') {
                    continue;
                }
                
                $actor_path = $actors_path . '/' . $actor_id;
                if (is_dir($actor_path)) {
                    $faucet_file = $actor_path . '/faucets.json';
                    
                    if (file_exists($faucet_file)) {
                        $content = file_get_contents($faucet_file);
                        $faucet = json_decode($content, true);
                        
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $this->channels[$channel_id]['per_actor_faucets'][] = $faucet;
                            $this->channels[$channel_id]['actors'][] = $actor_id;
                            
                            $this->all_faucets[] = [
                                'channel_id' => $channel_id,
                                'type' => 'per_actor',
                                'faucet' => $faucet,
                                'file' => $faucet_file
                            ];
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Perform integrity audit
     */
    public function audit() {
        echo "=== FAUCET INTEGRITY AUDIT ===\n\n";
        
        $this->auditDuplicateSlugs();
        $this->auditOrphanFaucets();
        $this->auditMissingActorFaucets();
        
        $this->outputSummary();
    }
    
    /**
     * Check for duplicate slugs across channels
     */
    private function auditDuplicateSlugs() {
        echo "=== DUPLICATE SLUG AUDIT ===\n";
        $slug_map = [];
        $duplicates_found = false;
        
        foreach ($this->all_faucets as $faucet_data) {
            $slug = $faucet_data['faucet']['slug'];
            $channel_id = $faucet_data['channel_id'];
            
            if (isset($slug_map[$slug])) {
                $existing = $slug_map[$slug];
                echo "DUPLICATE SLUG: '{$slug}' found in:\n";
                echo "  Channel {$existing['channel_id']} (file: {$existing['file']})\n";
                echo "  Channel {$channel_id} (file: {$faucet_data['file']})\n\n";
                $duplicates_found = true;
                
                $this->issues[] = [
                    'type' => 'duplicate_slug',
                    'slug' => $slug,
                    'channels' => [$existing['channel_id'], $channel_id],
                    'files' => [$existing['file'], $faucet_data['file']]
                ];
            } else {
                $slug_map[$slug] = $faucet_data;
            }
        }
        
        if (!$duplicates_found) {
            echo "No duplicate slugs found across channels\n\n";
        }
    }
    
    /**
     * Check for orphan faucet files
     */
    private function auditOrphanFaucets() {
        echo "=== ORPHAN FAUCET AUDIT ===\n";
        $actor_dirs = [];
        
        // Get all actor root directories
        $actors_dir = 'actors';
        if (is_dir($actors_dir)) {
            $items = scandir($actors_dir);
            foreach ($items as $item) {
                if ($item !== '.' && $item !== '..' && is_dir($actors_dir . '/' . $item)) {
                    $actor_dirs[] = $item;
                }
            }
        }
        
        $orphan_count = 0;
        foreach ($this->all_faucets as $faucet_data) {
            $actor_id = $faucet_data['faucet']['actor_id'];
            $channel_id = $faucet_data['channel_id'];
            
            // Check if actor directory exists
            if (!in_array($actor_id, $actor_dirs)) {
                echo "ORPHAN FAUCET: Actor {$actor_id} has faucet but no root directory at actors/{$actor_id}/\n";
                echo "  Faucet file: {$faucet_data['file']}\n";
                echo "  Channel: {$channel_id}\n\n";
                
                $this->issues[] = [
                    'type' => 'orphan_faucet',
                    'actor_id' => $actor_id,
                    'missing_directory' => "actors/{$actor_id}/",
                    'faucet_file' => $faucet_data['file'],
                    'channel_id' => $channel_id
                ];
                $orphan_count++;
            }
        }
        
        if ($orphan_count === 0) {
            echo "No orphan faucets found\n\n";
        }
    }
    
    /**
     * Check for actor directories without faucets in active channels
     */
    private function auditMissingActorFaucets() {
        echo "=== MISSING ACTOR FAUCETS AUDIT ===\n";
        $missing_count = 0;
        
        foreach ($this->channels as $channel_id => $channel_data) {
            if (empty($channel_data['actors'])) {
                continue;
            }
            
            foreach ($channel_data['actors'] as $actor_id) {
                $has_faucet = false;
                
                // Check if actor has per-actor faucet
                foreach ($channel_data['per_actor_faucets'] as $faucet) {
                    if ($faucet['actor_id'] == $actor_id) {
                        $has_faucet = true;
                        break;
                    }
                }
                
                // Check if actor has channel-wide faucet
                if (!$has_faucet) {
                    foreach ($channel_data['channel_wide_faucets'] as $faucet) {
                        if ($faucet['actor_id'] == $actor_id) {
                            $has_faucet = true;
                            break;
                        }
                    }
                }
                
                if (!$has_faucet) {
                    echo "MISSING FAUCET: Actor {$actor_id} in channel {$channel_id} has no faucet definition\n";
                    echo "  Actor directory: channels/{$channel_id}/actors/{$actor_id}/\n";
                    echo "  Expected: channels/{$channel_id}/actors/{$actor_id}/faucets.json\n";
                    echo "  Or: channels/{$channel_id}/faucets.json (channel-wide)\n\n";
                    
                    $this->issues[] = [
                        'type' => 'missing_faucet',
                        'actor_id' => $actor_id,
                        'channel_id' => $channel_id,
                        'actor_directory' => "channels/{$channel_id}/actors/{$actor_id}/",
                        'expected_files' => [
                            "channels/{$channel_id}/actors/{$actor_id}/faucets.json",
                            "channels/{$channel_id}/faucets.json"
                        ]
                    ];
                    $missing_count++;
                }
            }
        }
        
        if ($missing_count === 0) {
            echo "All actors in active channels have faucet definitions\n\n";
        }
    }
    
    /**
     * Output audit summary
     */
    private function outputSummary() {
        echo "=== AUDIT SUMMARY ===\n";
        echo "Total Channels Scanned: " . count($this->channels) . "\n";
        echo "Total Faucet Files: " . count($this->all_faucets) . "\n";
        echo "Total Issues Found: " . count($this->issues) . "\n\n";
        
        if (!empty($this->issues)) {
            echo "=== DETAILED ISSUES ===\n";
            foreach ($this->issues as $i => $issue) {
                echo "Issue " . ($i + 1) . ": {$issue['type']}\n";
                foreach ($issue as $key => $value) {
                    if (is_array($value)) {
                        echo "  {$key}: " . implode(', ', $value) . "\n";
                    } else {
                        echo "  {$key}: {$value}\n";
                    }
                }
                echo "\n";
            }
        } else {
            echo "No integrity issues found\n";
        }
        
        echo "=== RECOMMENDATIONS ===\n";
        echo "1. Fix duplicate slugs by using unique identifiers across channels\n";
        echo "2. Create missing actor directories for orphan faucets\n";
        echo "3. Add faucet definitions for actors without faucets\n";
        echo "4. Run regular integrity audits to maintain system consistency\n\n";
        
        // Exit with non-zero code if issues found
        exit(!empty($this->issues) ? 1 : 0);
    }
}

// CLI Interface
if (php_sapi_name() === 'cli') {
    try {
        $auditor = new FaucetIntegrityAuditor();
        $auditor->audit();
    } catch (Exception $e) {
        echo "FATAL ERROR: " . $e->getMessage() . "\n";
        exit(1);
    }
}
?>
