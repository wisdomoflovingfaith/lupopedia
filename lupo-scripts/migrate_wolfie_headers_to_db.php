<?php
/**
 * Wolfie Header v2.6 Migration Script
 * 
 * Populates lupo_files and lupo_file_edges tables from existing headers
 * Handles reserved field names and timestamp conversions
 * 
 * @package Lupopedia
 * @version 1.0.0
 */

// Include database configuration
require_once __DIR__ . '/../lupopedia-config.php';

class WolfieHeaderMigrator {
    private $pdo;
    private $taxonomy;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->loadTaxonomy();
    }
    
    /**
     * Load taxonomy v2.3 for pattern matching
     */
    private function loadTaxonomy() {
        $taxonomyFile = __DIR__ . '/../docs/reference/wolfie_header_taxonomy.json';
        if (file_exists($taxonomyFile)) {
            $taxonomy = json_decode(file_get_contents($taxonomyFile), true);
            $this->taxonomy = $taxonomy['wolfie.header.taxonomy'] ?? [];
        }
    }
    
    /**
     * Parse Wolfie v2.6 header from file content
     */
    public function parseHeader($filePath) {
        if (!file_exists($filePath)) {
            return null;
        }
        
        $content = file_get_contents($filePath);
        $header = [];
        
        // Extract header block
        if (preg_match('/\/\* ⧉ WOLFIE v2\.6 ⧉\s*\n(.*?)\s*\*\//s', $content, $matches)) {
            $headerContent = $matches[1];
            
            // Parse NAV section
            if (preg_match('/## NAV\s*\n(.*?)\s*## META/s', $headerContent, $navMatches)) {
                $navLines = explode("\n", trim($navMatches[1]));
                foreach ($navLines as $line) {
                    if (preg_match('/^(\w+):\s*(.*)$/', trim($line), $fieldMatch)) {
                        $header[$fieldMatch[1]] = trim($fieldMatch[2]);
                    }
                }
            }
            
            // Parse META section
            if (preg_match('/## META\s*\n(.*?)\s*## MYTH/s', $headerContent, $metaMatches)) {
                $metaLines = explode("\n", trim($metaMatches[1]));
                foreach ($metaLines as $line) {
                    if (preg_match('/^(\w+):\s*(.*)$/', trim($line), $fieldMatch)) {
                        $header[$fieldMatch[1]] = trim($fieldMatch[2]);
                    }
                }
            }
            
            // Parse MYTH section
            if (preg_match('/## MYTH\s*\n(.*?)\s*## REL/s', $headerContent, $mythMatches)) {
                $mythLines = explode("\n", trim($mythMatches[1]));
                foreach ($mythLines as $line) {
                    if (preg_match('/^(\w+):\s*(.*)$/', trim($line), $fieldMatch)) {
                        $header[$fieldMatch[1]] = trim($fieldMatch[2]);
                    }
                }
            }
            
            // Parse REL section
            if (preg_match('/## REL\s*\n(.*?)\s*## DOCS/s', $headerContent, $relMatches)) {
                $relLines = explode("\n", trim($relMatches[1]));
                foreach ($relLines as $line) {
                    $trimmed = trim($line);
                    if (!empty($trimmed) && !preg_match('/^→\s*$|^←\s*$|^↔\s*$/', $trimmed)) {
                        if (preg_match('/^(→|←|↔)\s*(.*)$/', $trimmed, $relMatch)) {
                            $relType = $relMatch[1];
                            $targets = array_map('trim', explode(',', $relMatch[2]));
                            $header['relations'][$relType] = $targets;
                        }
                    }
                }
            }
            
            return $header;
        }
        
        return null;
    }
    
    /**
     * Convert timestamp string to Unix epoch
     */
    private function timestampToEpoch($timestamp) {
        if (empty($timestamp)) return null;
        
        // Handle both full UTC and date-only formats
        if (preg_match('/(\d{4}-\d{2}-\d{2})T(\d{2}:\d{2}:\d{2})Z/', $timestamp, $matches)) {
            return strtotime($matches[1] . ' ' . $matches[2]);
        } elseif (preg_match('/(\d{4}-\d{2}-\d{2})/', $timestamp, $matches)) {
            return strtotime($matches[1] . ' 00:00:00');
        }
        
        return null;
    }
    
    /**
     * Parse update count from agent#N format
     */
    private function parseUpdateCount($updField) {
        if (empty($updField)) return ['agent' => null, 'count' => 0];
        
        if (preg_match('/^(.+)#(\d+)$/', $updField, $matches)) {
            return [
                'agent' => $matches[1],
                'count' => (int)$matches[2]
            ];
        }
        
        return ['agent' => $updField, 'count' => 0];
    }
    
    /**
     * Infer taxonomy from file path if missing
     */
    private function inferTaxonomy($filePath, $header) {
        $relativePath = str_replace(__DIR__ . '/../', '', $filePath);
        $dirPath = dirname($relativePath);
        
        // Use existing header values if available
        if (!empty($header['pkg'])) return $header;
        
        // Apply directory patterns from taxonomy
        foreach ($this->taxonomy['directory_patterns'] ?? [] as $pattern => $mapping) {
            if ($pattern === '' && $dirPath === '.') {
                // Root directory pattern
                $header['pkg'] = $mapping['package'] ?? 'misc';
                $header['mod'] = $mapping['module'] ?? 'helpers';
                $header['asp'] = $mapping['aspect'] ?? 'utility';
                break;
            } elseif (strpos($dirPath, rtrim($pattern, '/')) === 0) {
                $header['pkg'] = $mapping['package'] ?? 'misc';
                $header['mod'] = $mapping['module'] ?? 'helpers';
                $header['asp'] = $mapping['aspect'] ?? 'utility';
                break;
            }
        }
        
        // Apply file patterns
        $filename = basename($filePath);
        foreach ($this->taxonomy['file_patterns'] ?? [] as $pattern => $mapping) {
            if (fnmatch($pattern, $filename)) {
                if (isset($mapping['aspect'])) $header['asp'] = $mapping['aspect'];
                if (isset($mapping['module'])) $header['mod'] = $mapping['module'];
                break;
            }
        }
        
        return $header;
    }
    
    /**
     * Insert file record into lupo_files table
     */
    public function insertFile($filePath, $header) {
        $relativePath = str_replace(__DIR__ . '/../', '', $filePath);
        $header = $this->inferTaxonomy($filePath, $header);
        
        $updateInfo = $this->parseUpdateCount($header['upd'] ?? '');
        
        $sql = "INSERT INTO lupo_files (
            file_path, package_name, module_name, aspect_name, pur,
            cre_ymdhis, mod_ymdhis, upd_by, upd_count
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            package_name = VALUES(package_name),
            module_name = VALUES(module_name),
            aspect_name = VALUES(aspect_name),
            pur = VALUES(pur),
            cre_ymdhis = VALUES(cre_ymdhis),
            mod_ymdhis = VALUES(mod_ymdhis),
            upd_by = VALUES(upd_by),
            upd_count = VALUES(upd_count)";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->execute([
            $relativePath,
            $header['pkg'] ?? 'misc',
            $header['mod'] ?? 'helpers',
            $header['asp'] ?? 'utility',
            $header['pur'] ?? '',
            $this->timestampToEpoch($header['cre'] ?? ''),
            $this->timestampToEpoch($header['mod'] ?? ''),
            $updateInfo['agent'],
            $updateInfo['count']
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Insert relationship records into lupo_file_edges table
     */
    public function insertRelationships($sourceFileId, $relations) {
        if (empty($relations)) return;
        
        $sql = "INSERT INTO lupo_file_edges (
            source_file_id, target_file_id, rel_type, description, cre_ymdhis
        ) VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($relations as $relType => $targets) {
            $relTypeMap = [
                '→' => 'supports',
                '←' => 'supported_by',
                '↔' => 'conflicts_with'
            ];
            
            $dbRelType = $relTypeMap[$relType] ?? $relType;
            
            foreach ($targets as $target) {
                // Find target file by path pattern
                $targetFileId = $this->findFileByPattern($target);
                
                if ($targetFileId) {
                    $stmt->execute([
                        $sourceFileId,
                        $targetFileId,
                        $dbRelType,
                        "Relationship from {$relType} to {$target}",
                        time()
                    ]);
                }
            }
        }
    }
    
    /**
     * Find file ID by pattern matching
     */
    private function findFileByPattern($pattern) {
        // Simple pattern matching - can be enhanced
        $sql = "SELECT file_id FROM lupo_files WHERE file_path LIKE ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["%{$pattern}%"]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['file_id'] : null;
    }
    
    /**
     * Migrate all PHP files in directory
     */
    public function migrateDirectory($directory, $recursive = true) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
            $recursive ? RecursiveIteratorIterator::SELF_FIRST : RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        $migrated = 0;
        $errors = [];
        
        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                try {
                    $header = $this->parseHeader($file->getPathname());
                    
                    if ($header) {
                        $fileId = $this->insertFile($file->getPathname(), $header);
                        
                        if (!empty($header['relations'])) {
                            $this->insertRelationships($fileId, $header['relations']);
                        }
                        
                        $migrated++;
                        echo "Migrated: {$file->getPathname()}\n";
                    }
                } catch (Exception $e) {
                    $errors[] = "Error migrating {$file->getPathname()}: " . $e->getMessage();
                }
            }
        }
        
        return [
            'migrated' => $migrated,
            'errors' => $errors
        ];
    }
    
    /**
     * Create tables if they don't exist
     */
    public function createTables() {
        // lupo_files table
        $sql = "CREATE TABLE IF NOT EXISTS lupo_files (
            file_id int NOT NULL AUTO_INCREMENT,
            file_path varchar(255) NOT NULL,
            package_name varchar(100),
            module_name varchar(100),
            aspect_name varchar(100),
            pur text,
            cre_ymdhis bigint,
            mod_ymdhis bigint,
            upd_by varchar(50),
            upd_count int DEFAULT 0,
            is_deleted tinyint(1) DEFAULT 0,
            deleted_ymdhis bigint,
            PRIMARY KEY (file_id),
            UNIQUE KEY file_path (file_path)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->pdo->exec($sql);
        
        // lupo_file_edges table
        $sql = "CREATE TABLE IF NOT EXISTS lupo_file_edges (
            edge_id int NOT NULL AUTO_INCREMENT,
            source_file_id int NOT NULL,
            target_file_id int NOT NULL,
            rel_type varchar(50) NOT NULL,
            description text,
            cre_ymdhis bigint,
            is_deleted tinyint(1) DEFAULT 0,
            deleted_ymdhis bigint,
            PRIMARY KEY (edge_id),
            KEY idx_source_file (source_file_id),
            KEY idx_target_file (target_file_id),
            KEY idx_rel_type (rel_type)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->pdo->exec($sql);
        
        echo "Tables created or verified.\n";
    }
}

// Main execution
try {
    // Get database connection
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    $migrator = new WolfieHeaderMigrator($pdo);
    
    // Create tables
    $migrator->createTables();
    
    // Migrate root directory first
    echo "=== Migrating Root Directory ===\n";
    $result = $migrator->migrateDirectory(__DIR__ . '/..', false);
    
    echo "\n=== Migration Summary ===\n";
    echo "Files migrated: {$result['migrated']}\n";
    
    if (!empty($result['errors'])) {
        echo "\n=== Errors ===\n";
        foreach ($result['errors'] as $error) {
            echo $error . "\n";
        }
    }
    
    // Optionally migrate subdirectories
    if (isset($argv[1]) && $argv[1] === '--recursive') {
        echo "\n=== Migrating Subdirectories ===\n";
        $result = $migrator->migrateDirectory(__DIR__ . '/..', true);
        echo "Additional files migrated: {$result['migrated']}\n";
    }
    
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
