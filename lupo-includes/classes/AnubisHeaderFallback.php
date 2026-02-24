<?php
/**
 * @wolfie.headers {
 *   file_path_from_root: "lupo-includes/classes/AnubisHeaderFallback.php",
 *   system_version: "4.0.39",
 *   channel_id: 42,
 *   mood_rgb: "8B4513",
 *   purpose: "ANUBIS automated header detection, generation, and fallback system",
 *   last_modified_utc: "20260224",
 *   delegation_chain: "1001:19:10000",
 *   actor_id: 1001,
 *   lupo_agent: "kiro",
 *   artifact_type: "service",
 *   artifact_kind: "automation_engine",
 *   traits: ["core", "anubis", "automation", "v4.0.39"],
 *   hashtags: ["#anubis", "#headers", "#automation", "#detection", "#generation"],
 *   engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
 *   graph_stats: { inbound_count: 1, outbound_count: 3, centrality_score: 0.85 }
 * }
 * 
 * @flip.footer {
 *   inbound_edges: [
 *     { from: "docs/doctrine/ANUBIS_FALLBACK_DOCTRINE.md", type: "implements", weight: 1.0, hashtag: "#doctrine" }
 *   ],
 *   outbound_edges: [
 *     { to: "docs/versions/4.0.39/PRIORITY_FILES.md", type: "processes", weight: 0.9, hashtag: "#priority" },
 *     { to: "docs/status/kiro_header_completion_4_0_39.md", type: "reports_to", weight: 0.8, hashtag: "#status" },
 *     { to: "lupo-includes/class-pdo_db.php", type: "depends_on", weight: 0.7, hashtag: "#database" }
 *   ],
 *   referenced_by_actors: [1001, 19, 10000],
 *   references: { by_files: ["docs/doctrine/ANUBIS_FALLBACK_DOCTRINE.md"], by_actors: [1001, 19, 10000] },
 *   semantic_tags: ["anubis_fallback", "header_detection", "header_generation", "file_classification"],
 *   enrichment: { llm_inferred_edges: [], federated_metrics: {} },
 *   version: "4.0.39",
 *   last_verified_utc: "20260224",
 *   last_verified_by: "kiro"
 * }
 */

/**
 * ANUBIS Header Fallback System
 * 
 * Automated detection, generation, and management of FLIP v3 headers
 * for files missing proper metadata.
 * 
 * @package Lupopedia
 * @version 4.0.39
 * @author KIRO (1001) + ANUBIS (19)
 * @since 4.0.39
 */
class AnubisHeaderFallback
{
    /**
     * ANUBIS actor ID
     */
    const ANUBIS_ACTOR_ID = 19;
    
    /**
     * Captain actor ID (for delegation chain)
     */
    const CAPTAIN_ACTOR_ID = 10000;
    
    /**
     * Current system version
     */
    const SYSTEM_VERSION = '4.0.39';
    
    /**
     * Database connection
     * @var PDO_DB
     */
    private $db;
    
    /**
     * Review queue directory
     * @var string
     */
    private $review_queue_dir = '.anubis/review_queue/';
    
    /**
     * Quarantine directory
     * @var string
     */
    private $quarantine_dir = '.anubis/quarantine/';
    
    /**
     * Backup directory
     * @var string
     */
    private $backup_dir = '.anubis/backups/';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = DatabaseFactory::getConnection();
        $this->ensureDirectories();
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
    public function detectMissingHeaders($directory = '.', $extensions = array('md', 'php'))
    {
        $missing = array();
        $files = $this->scanDirectory($directory, $extensions);
        
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
        $timestamp = gmdate('YmdHis');
        
        $header = array(
            'file_path_from_root' => $file_path,
            'system_version' => self::SYSTEM_VERSION,
            'channel_id' => 42,
            'mood_rgb' => '808080',
            'purpose' => $classification['purpose'],
            'last_modified_utc' => substr($timestamp, 0, 8),
            'delegation_chain' => self::ANUBIS_ACTOR_ID . ':' . self::CAPTAIN_ACTOR_ID,
            'actor_id' => self::ANUBIS_ACTOR_ID,
            'lupo_agent' => 'anubis',
            'artifact_type' => $classification['artifact_type'],
            'artifact_kind' => $classification['artifact_kind'],
            'traits' => array_merge(array('auto_generated'), $classification['traits']),
            'hashtags' => $classification['hashtags'],
            'engagement' => array(
                'likes' => 0,
                'shares' => 0,
                'views' => 0,
                'last_interaction_utc' => substr($timestamp, 0, 8)
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
        } elseif (strpos($file_path, 'prompts/') !== false) {
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
        } elseif (strpos($file_path, 'tests/') !== false) {
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
     * @param string $file_path Path to file
     * @return array Footer data
     */
    public function generateFooter($file_path)
    {
        $timestamp = gmdate('YmdHis');
        
        $footer = array(
            'inbound_edges' => array(),
            'outbound_edges' => array(),
            'referenced_by_actors' => array(self::ANUBIS_ACTOR_ID),
            'references' => array(
                'by_files' => array(),
                'by_actors' => array(self::ANUBIS_ACTOR_ID)
            ),
            'semantic_tags' => array('auto_generated'),
            'enrichment' => array(
                'llm_inferred_edges' => array(),
                'federated_metrics' => array()
            ),
            'version' => self::SYSTEM_VERSION,
            'last_verified_utc' => substr($timestamp, 0, 8),
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
        // Create backup first
        $this->createBackup($file_path);
        
        // Read existing content
        $content = file_get_contents($file_path);
        
        // Generate header block based on file type
        $ext = pathinfo($file_path, PATHINFO_EXTENSION);
        $header_block = $this->formatHeaderBlock($header, $footer, $ext);
        
        // Insert header at beginning
        $new_content = $header_block . "\n" . $content;
        
        // Write back to file
        $result = file_put_contents($file_path, $new_content);
        
        // Log action
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
        $timestamp = gmdate('Ymd_His');
        $backup_path = $this->backup_dir . $timestamp . '/' . basename($file_path);
        
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
        $date = gmdate('Ymd');
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
            'assigned_to' => self::CAPTAIN_ACTOR_ID
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
            'timestamp' => gmdate('YmdHis'),
            'action' => $action,
            'file' => $file_path,
            'actor_id' => self::ANUBIS_ACTOR_ID,
            'metadata' => $metadata
        );
        
        $log_file = '.anubis/audit.log';
        file_put_contents($log_file, json_encode($log_entry) . "\n", FILE_APPEND);
    }
}

