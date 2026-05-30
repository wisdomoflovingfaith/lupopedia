<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: class
  when_updated: "20260406010447"
  file_path_from_root: "includes/classes/AnubisHeaderFallback.php"
  web_path: "http://www.lupopedia.com/lupopedia/includes/classes/AnubisHeaderFallback.php"
  questions_toon: null
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 19
    name: "ANUBIS"
  delegation_chain: "anubis:root"
  artifact_type: "service"
  artifact_kind: "automation_engine"
  purpose: "Header detection and generation for files missing legacy wolfie/FLIP metadata; path anchoring; AgentDiscovery for actor id."
  tags: ["anubis", "headers", "automation"]
---
*/

/**
 * Automated detection, generation, and management of legacy wolfie/FLIP-style headers
 * for files missing proper metadata.
 *
 * Actor id: resolved via AgentDiscovery (anubis); numeric fallback only if discovery returns none.
 *
 * @package Lupopedia
 */

class AnubisHeaderFallback
{
    /** @var array|null */
    private $agent;

    /** @var int Resolved from AgentDiscovery; not a hardcoded const. */
    private $ANUBIS_ACTOR_ID;

    /** @var int */
    private $CAPTAIN_ACTOR_ID;

    /** @var string */
    private $SYSTEM_VERSION = '4.0.39';

    private $review_queue_dir = 'anubis/review_queue/';

    private $quarantine_dir = 'anubis/quarantine/';

    private $backup_dir = 'anubis/backups/';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Load agent config from agents/anubis/
        if (!class_exists('AgentDiscovery')) {
            require_once(dirname(__FILE__) . '/AgentDiscovery.php');
        }
        $this->agent = AgentDiscovery::getAgent('anubis');
        $this->ANUBIS_ACTOR_ID = (isset($this->agent['agent_id']) && $this->agent['agent_id'] !== '')
            ? (int) $this->agent['agent_id']
            : 19;
        $this->CAPTAIN_ACTOR_ID = 10000;
        $this->ensureDirectories();
    }

    /**
     * Load global timestamp helper when available (packed UTC BIGINT semantics).
     *
     * @return void
     */
    private function ensureTimestampClass()
    {
        if (class_exists('\\timestamp_ymdhis', false)) {
            return;
        }
        if (defined('LUPOPEDIA_PATH')) {
            $p = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'TimestampYmdhis.php';
            if (is_file($p)) {
                require_once $p;
            }
        }
    }

    /**
     * @return int Packed UTC YmdHis
     */
    private function nowPackedInt()
    {
        $this->ensureTimestampClass();
        if (class_exists('\\timestamp_ymdhis', false)) {
            return \timestamp_ymdhis::now();
        }
        return (int) gmdate('YmdHis');
    }
    
    /**
     * Ensure ANUBIS directories exist
     */
    private function ensureDirectories()
    {
        $dirs = array(
            $this->review_queue_dir,
            $this->quarantine_dir,
            $this->backup_dir
        );
        
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
    }
    
    /**
     * Detect files missing headers
     * 
     * @param string $directory Directory to scan
     * @param array $extensions File extensions to check
     * @return array List of files missing headers
     */
    // $UNTRUSTED: $directory must be validated/anchored
    public function detectMissingHeaders($directory = '.', $extensions = array('md', 'php'))
    {
        $UNTRUSTED = array('directory' => $directory);
        $missing = array();
        $files = $this->scanDirectory($UNTRUSTED['directory'], $extensions);
        foreach ($files as $file) {
            if (!$this->hasValidHeader($file)) {
                $missing[] = $file;
            }
        }
        return $missing;
    }
    
    /**
     * Scan directory recursively for files
     * 
     * @param string $directory Directory to scan
     * @param array $extensions File extensions to include
     * @return array List of file paths
     */
    private function scanDirectory($directory, $extensions)
    {
        // Path anchoring: only allow scans within LUPOPEDIA_PATH
        $real_base = realpath(defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : getcwd());
        $real_dir = realpath($directory);
        if ($real_dir === false || strpos($real_dir, $real_base) !== 0) {
            return array(); // refuse to scan outside project root
        }
        $files = array();
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $ext = pathinfo($file->getPathname(), PATHINFO_EXTENSION);
                if (in_array($ext, $extensions)) {
                    // Skip excluded directories
                    if ($this->isExcludedPath($file->getPathname())) {
                        continue;
                    }
                    $files[] = $file->getPathname();
                }
            }
        }
        return $files;
    }
    
    /**
     * Check if path should be excluded from scanning
     * 
     * @param string $path File path
     * @return bool True if excluded
     */
    private function isExcludedPath($path)
    {
        $excluded = array(
            'vendor/',
            'node_modules/',
            '.git/',
            '.anubis/',
            '.lupo/'
        );
        
        foreach ($excluded as $exclude) {
            if (strpos($path, $exclude) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if file has valid FLIP header
     * 
     * @param string $file_path Path to file
     * @return bool True if valid header exists
     */
    public function hasValidHeader($file_path)
    {
        $content = file_get_contents($file_path);
        
        // Check for header delimiters
        if (strpos($content, 'wolfie.headers') === false) {
            return false;
        }
        
        // Check for required fields
        $required_fields = array(
            'file_path_from_root',
            'system_version',
            'delegation_chain',
            'actor_id',
            'artifact_type'
        );
        
        foreach ($required_fields as $field) {
            if (strpos($content, $field) === false) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Generate default FLIP v3 header for file
     * 
     * @param string $file_path Path to file
     * @return array Header data
     */
    public function generateHeader($file_path)
    {
        $classification = $this->classifyFile($file_path);
        $packed = $this->nowPackedInt();
        $packedStr = (string) $packed;
        
        $header = array(
            'file_path_from_root' => $file_path,
            'system_version' => $this->SYSTEM_VERSION,
            'channel_id' => 42,
            'mood_vector' => '808080',
            'purpose' => $classification['purpose'],
            'last_modified_utc' => $packedStr,
            'delegation_chain' => $this->ANUBIS_ACTOR_ID . ':' . $this->CAPTAIN_ACTOR_ID,
            'actor_id' => $this->ANUBIS_ACTOR_ID,
            'lupo_agent' => 'anubis',
            'artifact_type' => $classification['artifact_type'],
            'artifact_kind' => $classification['artifact_kind'],
            'traits' => array_merge(array('auto_generated'), $classification['traits']),
            'hashtags' => $classification['hashtags'],
            'engagement' => array(
                'likes' => 0,
                'shares' => 0,
                'views' => 0,
                'last_interaction_utc' => $packedStr
            ),
            'graph_stats' => array(
                'inbound_count' => 0,
                'outbound_count' => 0,
                'centrality_score' => 0.0
            )
        );
        
        return $header;
    }
    
    /**
     * Classify file based on path and content
     * 
     * @param string $file_path Path to file
     * @return array Classification data
     */
    private function classifyFile($file_path)
    {
        $classification = array(
            'artifact_type' => 'unknown',
            'artifact_kind' => 'unclassified',
            'traits' => array(),
            'hashtags' => array(),
            'purpose' => 'Auto-generated by ANUBIS'
        );
        
        // Classify by path patterns
        if (strpos($file_path, 'docs/doctrine/') !== false) {
            $classification['artifact_type'] = 'doctrine';
            $classification['artifact_kind'] = 'system_rules';
            $classification['traits'][] = 'mandatory';
            $classification['hashtags'][] = '#doctrine';
        } elseif (strpos($file_path, 'docs/status/') !== false) {
            $classification['artifact_type'] = 'status';
            $classification['artifact_kind'] = 'activity_report';
            $classification['hashtags'][] = '#status';
        } elseif (strpos($file_path, 'channels/') !== false && strpos($file_path, '/broadcasts/') !== false) {
            $classification['artifact_type'] = 'broadcast';
            $classification['artifact_kind'] = 'announcement';
            $classification['hashtags'][] = '#broadcast';
        } elseif (strpos($file_path, 'prompts/') !== false || strpos($file_path, 'prompts/') !== false) {
            $classification['artifact_type'] = 'prompt';
            $classification['artifact_kind'] = 'task_directive';
            $classification['hashtags'][] = '#prompt';
        } elseif (strpos($file_path, 'app/Services/') !== false) {
            $classification['artifact_type'] = 'service';
            $classification['artifact_kind'] = 'business_logic';
            $classification['traits'][] = 'core';
            $classification['hashtags'][] = '#service';
        } elseif (strpos($file_path, 'app/auth/') !== false) {
            $classification['artifact_type'] = 'service';
            $classification['artifact_kind'] = 'authentication';
            $classification['traits'][] = 'critical';
            $classification['hashtags'][] = '#auth';
        } elseif (strpos($file_path, 'app/auth/') !== false) {
            $classification['artifact_type'] = 'service';
            $classification['artifact_kind'] = 'authentication';
            $classification['traits'][] = 'critical';
            $classification['hashtags'][] = '#auth';
        } elseif (strpos($file_path, 'tests/') !== false || strpos($file_path, 'tests/') !== false) {
            $classification['artifact_type'] = 'test';
            $classification['artifact_kind'] = 'unit_test';
            $classification['traits'][] = 'test';
            $classification['hashtags'][] = '#test';
        } elseif (preg_match('/\.md$/', $file_path) && dirname($file_path) === '.') {
            $classification['artifact_type'] = 'guide';
            $classification['artifact_kind'] = 'documentation';
            $classification['hashtags'][] = '#guide';
        }
        
        // Add auto-generated hashtag
        $classification['hashtags'][] = '#auto_generated';
        
        return $classification;
    }
    
    /**
     * Generate footer for file
     *
     * @param string $file_path Path to file (reserved for future edge inference)
     * @return array Footer data
     */
    public function generateFooter($file_path)
    {
        $packedStr = (string) $this->nowPackedInt();
        $footer = array(
            'inbound_edges' => array(),
            'outbound_edges' => array(),
            'referenced_by_actors' => array($this->ANUBIS_ACTOR_ID),
            'references' => array(
                'by_files' => array(),
                'by_actors' => array($this->ANUBIS_ACTOR_ID)
            ),
            'semantic_tags' => array('auto_generated'),
            'enrichment' => array(
                'llm_inferred_edges' => array(),
                'federated_metrics' => array()
            ),
            'version' => $this->SYSTEM_VERSION,
            'last_verified_utc' => $packedStr,
            'last_verified_by' => 'anubis'
        );
        return $footer;
    }
    
    /**
     * Insert header into file
     *
     * @param string $file_path Path to file
     * @param array $header Header data
     * @param array $footer Footer data
     * @return bool Success
     */
    public function insertHeader($file_path, $header, $footer)
    {
        $this->createBackup($file_path);
        
        $content = file_get_contents($file_path);
        
        $ext = pathinfo($file_path, PATHINFO_EXTENSION);
        $header_block = $this->formatHeaderBlock($header, $footer, $ext);
        
        $new_content = $header_block . "\n" . $content;
        
        $result = file_put_contents($file_path, $new_content);
        
        $this->logAction('header_inserted', $file_path);
        
        return $result !== false;
    }
    
    /**
     * Format header block for file type
     * 
     * @param array $header Header data
     * @param array $footer Footer data
     * @param string $ext File extension
     * @return string Formatted header block
     */
    private function formatHeaderBlock($header, $footer, $ext)
    {
        $header_json = json_encode($header, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $footer_json = json_encode($footer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        if ($ext === 'md') {
            return "---\nwolfie.headers: $header_json\n\nflip.footer: $footer_json\n---";
        } elseif ($ext === 'php') {
            return "<?php\n/**\n * @wolfie.headers $header_json\n * @flip.footer $footer_json\n */\n";
        } else {
            return "// wolfie.headers: $header_json\n// flip.footer: $footer_json\n";
        }
    }
    
    /**
     * Create backup of file
     * 
     * @param string $file_path Path to file
     * @return bool Success
     */
    private function createBackup($file_path)
    {
        $packed = $this->nowPackedInt();
        $ps = (string) $packed;
        $folderTs = (strlen($ps) >= 14)
            ? (substr($ps, 0, 8) . '_' . substr($ps, 8, 6))
            : gmdate('Ymd_His');
        $backup_path = $this->backup_dir . $folderTs . '/' . basename($file_path);
        
        $backup_dir = dirname($backup_path);
        if (!is_dir($backup_dir)) {
            mkdir($backup_dir, 0755, true);
        }
        
        return copy($file_path, $backup_path);
    }
    
    /**
     * Route file to review queue
     * 
     * @param string $file_path Path to file
     * @param string $reason Reason for review
     * @param float $confidence ANUBIS confidence score
     * @return bool Success
     */
    public function routeToReview($file_path, $reason, $confidence = 0.5)
    {
        $ps = (string) $this->nowPackedInt();
        $date = (strlen($ps) >= 8) ? substr($ps, 0, 8) : gmdate('Ymd');
        $queue_dir = $this->review_queue_dir . $date . '/';
        
        if (!is_dir($queue_dir)) {
            mkdir($queue_dir, 0755, true);
        }
        
        $queue_entry = array(
            'file_path' => $file_path,
            'detected_date' => $date,
            'reason' => $reason,
            'suggested_header' => $this->generateHeader($file_path),
            'anubis_confidence' => $confidence,
            'requires_human' => true,
            'assigned_to' => $this->CAPTAIN_ACTOR_ID
        );
        
        $queue_file = $queue_dir . basename($file_path) . '.json';
        return file_put_contents($queue_file, json_encode($queue_entry, JSON_PRETTY_PRINT)) !== false;
    }
    
    /**
     * Log ANUBIS action
     * 
     * @param string $action Action type
     * @param string $file_path File path
     * @param array $metadata Additional metadata
     */
    private function logAction($action, $file_path, $metadata = array())
    {
        $log_entry = array(
            'timestamp' => $this->nowPackedInt(),
            'action' => $action,
            'file' => $file_path,
            'actor_id' => $this->ANUBIS_ACTOR_ID,
            'metadata' => $metadata
        );
        $log_file = 'anubis/audit.log';
        file_put_contents($log_file, json_encode($log_entry) . "\n", FILE_APPEND);
    }
}

