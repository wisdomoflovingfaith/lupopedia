<?php
/**
 * Filesystem to Database Migration Script
 *
 * Migrates agent and channel metadata from numeric filesystem directories
 * to database tables with new hash-based upload structure.
 *
 * Usage: php scripts/migrate_filesystem_to_db.php [--dry-run] [--type=agents|channels]
 *
 * @package Lupopedia
 * @version 2026.3.9.0
 */

// Bootstrap Lupopedia
require_once __DIR__ . '/../lupopedia-config.php';

class FilesystemMigrator {
    private $db;
    private $dryRun = false;
    private $uploadsRoot;
    private $stats = [
        'agents_processed' => 0,
        'agents_success' => 0,
        'agents_failed' => 0,
        'channels_processed' => 0,
        'channels_success' => 0,
        'channels_failed' => 0,
        'files_migrated' => 0,
        'bytes_processed' => 0
    ];

    public function __construct($dryRun = false) {
        $this->dryRun = $dryRun;
        $this->connectDatabase();
        $this->uploadsRoot = LUPOPEDIA_PATH . '/uploads';
        $this->ensureUploadDirectories();
    }

    private function connectDatabase() {
        try {
            $dsn = sprintf(
                "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                DB_HOST,
                DB_PORT,
                DB_NAME,
                DB_CHARSET
            );
            $this->db = new PDO($dsn, DB_USER, DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            echo "✓ Database connected\n";
        } catch (PDOException $e) {
            die("✗ Database connection failed: " . $e->getMessage() . "\n");
        }
    }

    private function ensureUploadDirectories() {
        $dirs = [
            'agents',
            'channels',
            'operators'
        ];

        foreach ($dirs as $dir) {
            $path = $this->uploadsRoot . '/' . $dir;
            if (!is_dir($path)) {
                if (!$this->dryRun) {
                    mkdir($path, 0755, true);
                }
                echo "✓ Created upload directory: $dir/\n";
            }
        }
    }

    private function ymdhis() {
        return gmdate('YmdHis');
    }

    private function getDatePath() {
        return gmdate('Y') . '/' . gmdate('m');
    }

    private function generateHashFilename($originalFilename, $content) {
        $hash = hash('sha256', $content);
        $ext = pathinfo($originalFilename, PATHINFO_EXTENSION);
        return $hash . ($ext ? '.' . $ext : '');
    }

    /**
     * Migrate all agents from filesystem to database
     */
    public function migrateAgents() {
        $agentsDir = LUPOPEDIA_PATH . '/agents';

        if (!is_dir($agentsDir)) {
            echo "✗ Agents directory not found: $agentsDir\n";
            return;
        }

        $directories = glob($agentsDir . '/*', GLOB_ONLYDIR);
        echo "\n" . str_repeat('=', 70) . "\n";
        echo "MIGRATING AGENTS\n";
        echo str_repeat('=', 70) . "\n";
        echo "Found " . count($directories) . " agent directories\n\n";

        foreach ($directories as $dir) {
            $dirName = basename($dir);

            // Skip non-numeric directories
            if (!preg_match('/^\d{4}$/', $dirName)) {
                echo "⊘ Skipping non-numeric directory: $dirName\n";
                continue;
            }

            $this->stats['agents_processed']++;
            echo "Processing agent directory: $dirName ... ";

            try {
                $this->migrateAgentDirectory($dir, $dirName);
                $this->stats['agents_success']++;
                echo "✓\n";
            } catch (Exception $e) {
                $this->stats['agents_failed']++;
                echo "✗ " . $e->getMessage() . "\n";
                $this->logMigrationError('agents', $dir, 'agent', null, $e->getMessage());
            }
        }
    }

    private function migrateAgentDirectory($dirPath, $dirName) {
        $metadataFile = $dirPath . '/metadata.json';

        if (!file_exists($metadataFile)) {
            throw new Exception("No metadata.json found");
        }

        $metadata = json_decode(file_get_contents($metadataFile), true);
        if (!$metadata) {
            throw new Exception("Invalid metadata.json");
        }

        // Extract agent data
        $agentKey = $metadata['code'] ?? strtoupper($dirName);
        $agentId = $metadata['id'] ?? $dirName;
        $version = $metadata['version'] ?? '1.0.0';
        $status = $metadata['status'] ?? 'unknown';

        // Check if agent already exists in database
        $existingAgent = $this->findAgentByKey($agentKey);

        if (!$existingAgent) {
            // Create new agent record
            if (!$this->dryRun) {
                $agentDbId = $this->createAgentRecord($agentKey, $dirName, $version, $metadata);
            } else {
                $agentDbId = null;
                echo "[DRY-RUN] Would create agent: $agentKey ";
            }
        } else {
            $agentDbId = $existingAgent['agent_id'];
            echo "[EXISTS: agent_id=$agentDbId] ";
        }

        // Migrate files
        $filesMigrated = $this->migrateAgentFiles($dirPath, $agentDbId ?? 0, $dirName);

        if (!$this->dryRun && $agentDbId) {
            $this->logMigrationSuccess('agents', $dirPath, 'agent', $agentDbId, $filesMigrated);
        }

        return $agentDbId;
    }

    private function findAgentByKey($agentKey) {
        $stmt = $this->db->prepare("
            SELECT agent_id, agent_key
            FROM lupo_agents
            WHERE agent_key = :agent_key
            AND is_deleted = 0
            LIMIT 1
        ");
        $stmt->execute([':agent_key' => $agentKey]);
        return $stmt->fetch();
    }

    private function createAgentRecord($agentKey, $agentName, $version, $metadata) {
        $now = $this->ymdhis();

        $stmt = $this->db->prepare("
            INSERT INTO lupo_agents (
                agent_key,
                agent_name,
                version,
                description,
                created_ymdhis,
                updated_ymdhis,
                is_deleted
            ) VALUES (
                :agent_key,
                :agent_name,
                :version,
                :description,
                :created_ymdhis,
                :updated_ymdhis,
                0
            )
        ");

        $stmt->execute([
            ':agent_key' => $agentKey,
            ':agent_name' => $agentName,
            ':version' => $version,
            ':description' => 'Migrated from filesystem directory: ' . $agentName,
            ':created_ymdhis' => $now,
            ':updated_ymdhis' => $now
        ]);

        return $this->db->lastInsertId();
    }

    private function migrateAgentFiles($dirPath, $agentId, $dirName) {
        $files = glob($dirPath . '/*');
        $filesMigrated = 0;

        foreach ($files as $filePath) {
            if (!is_file($filePath)) continue;

            $fileName = basename($filePath);
            $fileType = $this->detectFileType($fileName);
            $content = file_get_contents($filePath);
            $fileSize = filesize($filePath);
            $hash = hash('sha256', $content);

            // Generate new path with date structure and hash
            $datePath = $this->getDatePath();
            $newFileName = $this->generateHashFilename($fileName, $content);
            $newRelativePath = "agents/$datePath/$newFileName";
            $newFullPath = $this->uploadsRoot . '/' . $newRelativePath;

            // Create directory structure
            $newDir = dirname($newFullPath);
            if (!is_dir($newDir) && !$this->dryRun) {
                mkdir($newDir, 0755, true);
            }

            // Copy file to new location
            if (!$this->dryRun && !file_exists($newFullPath)) {
                copy($filePath, $newFullPath);
            }

            // Insert file record
            if (!$this->dryRun) {
                $this->insertAgentFileRecord($agentId, $fileType, $fileName, $newRelativePath, $hash, $fileSize, $dirName);
            }

            $filesMigrated++;
            $this->stats['files_migrated']++;
            $this->stats['bytes_processed'] += $fileSize;
        }

        return $filesMigrated;
    }

    private function detectFileType($fileName) {
        $fileName = strtolower($fileName);

        if ($fileName === 'metadata.json') return 'metadata';
        if ($fileName === 'system_prompt.txt') return 'system_prompt';
        if ($fileName === 'readme.md') return 'readme';
        if (strpos($fileName, 'config') !== false) return 'config';

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        return $ext ?: 'unknown';
    }

    private function insertAgentFileRecord($agentId, $fileType, $fileName, $filePath, $hash, $fileSize, $originalDir) {
        $now = $this->ymdhis();
        $mimeType = $this->getMimeType($fileName);

        $stmt = $this->db->prepare("
            INSERT INTO lupo_agent_files (
                agent_id,
                file_type,
                file_name,
                file_path,
                file_hash,
                file_size,
                mime_type,
                upload_ymdhis,
                created_ymdhis,
                updated_ymdhis,
                is_deleted,
                migrated_from_directory
            ) VALUES (
                :agent_id,
                :file_type,
                :file_name,
                :file_path,
                :file_hash,
                :file_size,
                :mime_type,
                :upload_ymdhis,
                :created_ymdhis,
                :updated_ymdhis,
                0,
                :migrated_from_directory
            )
        ");

        $stmt->execute([
            ':agent_id' => $agentId,
            ':file_type' => $fileType,
            ':file_name' => $fileName,
            ':file_path' => $filePath,
            ':file_hash' => $hash,
            ':file_size' => $fileSize,
            ':mime_type' => $mimeType,
            ':upload_ymdhis' => $now,
            ':created_ymdhis' => $now,
            ':updated_ymdhis' => $now,
            ':migrated_from_directory' => 'agents/' . $originalDir
        ]);
    }

    /**
     * Migrate all channels from filesystem to database
     */
    public function migrateChannels() {
        $channelsDir = LUPOPEDIA_PATH . '/channels';

        if (!is_dir($channelsDir)) {
            echo "✗ Channels directory not found: $channelsDir\n";
            return;
        }

        $directories = glob($channelsDir . '/*', GLOB_ONLYDIR);
        echo "\n" . str_repeat('=', 70) . "\n";
        echo "MIGRATING CHANNELS\n";
        echo str_repeat('=', 70) . "\n";
        echo "Found " . count($directories) . " channel directories\n\n";

        foreach ($directories as $dir) {
            $dirName = basename($dir);

            // Skip non-numeric directories
            if (!preg_match('/^\d{4}$/', $dirName)) {
                echo "⊘ Skipping non-numeric directory: $dirName\n";
                continue;
            }

            $this->stats['channels_processed']++;
            echo "Processing channel directory: $dirName ... ";

            try {
                $this->migrateChannelDirectory($dir, $dirName);
                $this->stats['channels_success']++;
                echo "✓\n";
            } catch (Exception $e) {
                $this->stats['channels_failed']++;
                echo "✗ " . $e->getMessage() . "\n";
                $this->logMigrationError('channels', $dir, 'channel', null, $e->getMessage());
            }
        }
    }

    private function migrateChannelDirectory($dirPath, $dirName) {
        $metadataFile = $dirPath . '/metadata.json';

        if (!file_exists($metadataFile)) {
            throw new Exception("No metadata.json found");
        }

        $metadata = json_decode(file_get_contents($metadataFile), true);
        if (!$metadata) {
            throw new Exception("Invalid metadata.json");
        }

        // Extract channel data
        $channelKey = 'channel_' . $dirName;
        $channelPurpose = $metadata['channel']['purpose'] ?? 'migrated';

        // Check if channel already exists
        $existingChannel = $this->findChannelByKey($channelKey);

        if (!$existingChannel) {
            if (!$this->dryRun) {
                $channelDbId = $this->createChannelRecord($channelKey, $dirName, $metadata);
            } else {
                $channelDbId = null;
                echo "[DRY-RUN] Would create channel: $channelKey ";
            }
        } else {
            $channelDbId = $existingChannel['channel_id'];
            echo "[EXISTS: channel_id=$channelDbId] ";
        }

        // Migrate files
        $filesMigrated = $this->migrateChannelFiles($dirPath, $channelDbId ?? 0, $dirName);

        if (!$this->dryRun && $channelDbId) {
            $this->logMigrationSuccess('channels', $dirPath, 'channel', $channelDbId, $filesMigrated);
        }

        return $channelDbId;
    }

    private function findChannelByKey($channelKey) {
        $stmt = $this->db->prepare("
            SELECT channel_id, channel_key
            FROM lupo_channels
            WHERE channel_key = :channel_key
            AND is_deleted = 0
            LIMIT 1
        ");
        $stmt->execute([':channel_key' => $channelKey]);
        return $stmt->fetch();
    }

    private function createChannelRecord($channelKey, $channelName, $metadata) {
        $now = $this->ymdhis();

        $stmt = $this->db->prepare("
            INSERT INTO lupo_channels (
                federation_node_id,
                created_by_actor_id,
                default_actor_id,
                channel_key,
                channel_slug,
                channel_type,
                channel_name,
                description,
                created_ymdhis,
                updated_ymdhis,
                is_deleted
            ) VALUES (
                1,
                1,
                1,
                :channel_key,
                :channel_slug,
                'migrated',
                :channel_name,
                :description,
                :created_ymdhis,
                :updated_ymdhis,
                0
            )
        ");

        $stmt->execute([
            ':channel_key' => $channelKey,
            ':channel_slug' => $channelKey,
            ':channel_name' => 'Channel ' . $channelName,
            ':description' => 'Migrated from filesystem directory: ' . $channelName,
            ':created_ymdhis' => $now,
            ':updated_ymdhis' => $now
        ]);

        return $this->db->lastInsertId();
    }

    private function migrateChannelFiles($dirPath, $channelId, $dirName) {
        $files = glob($dirPath . '/*');
        $filesMigrated = 0;

        foreach ($files as $filePath) {
            if (!is_file($filePath)) continue;

            $fileName = basename($filePath);
            $fileType = $this->detectFileType($fileName);
            $content = file_get_contents($filePath);
            $fileSize = filesize($filePath);
            $hash = hash('sha256', $content);

            // Generate new path
            $datePath = $this->getDatePath();
            $newFileName = $this->generateHashFilename($fileName, $content);
            $newRelativePath = "channels/$datePath/$newFileName";
            $newFullPath = $this->uploadsRoot . '/' . $newRelativePath;

            $newDir = dirname($newFullPath);
            if (!is_dir($newDir) && !$this->dryRun) {
                mkdir($newDir, 0755, true);
            }

            if (!$this->dryRun && !file_exists($newFullPath)) {
                copy($filePath, $newFullPath);
            }

            if (!$this->dryRun) {
                $this->insertChannelFileRecord($channelId, $fileType, $fileName, $newRelativePath, $hash, $fileSize, $dirName);
            }

            $filesMigrated++;
            $this->stats['files_migrated']++;
            $this->stats['bytes_processed'] += $fileSize;
        }

        return $filesMigrated;
    }

    private function insertChannelFileRecord($channelId, $fileType, $fileName, $filePath, $hash, $fileSize, $originalDir) {
        $now = $this->ymdhis();
        $mimeType = $this->getMimeType($fileName);

        $stmt = $this->db->prepare("
            INSERT INTO lupo_channel_files (
                channel_id,
                file_type,
                file_name,
                file_path,
                file_hash,
                file_size,
                mime_type,
                upload_ymdhis,
                created_ymdhis,
                updated_ymdhis,
                is_deleted,
                migrated_from_directory
            ) VALUES (
                :channel_id,
                :file_type,
                :file_name,
                :file_path,
                :file_hash,
                :file_size,
                :mime_type,
                :upload_ymdhis,
                :created_ymdhis,
                :updated_ymdhis,
                0,
                :migrated_from_directory
            )
        ");

        $stmt->execute([
            ':channel_id' => $channelId,
            ':file_type' => $fileType,
            ':file_name' => $fileName,
            ':file_path' => $filePath,
            ':file_hash' => $hash,
            ':file_size' => $fileSize,
            ':mime_type' => $mimeType,
            ':upload_ymdhis' => $now,
            ':created_ymdhis' => $now,
            ':updated_ymdhis' => $now,
            ':migrated_from_directory' => 'channels/' . $originalDir
        ]);
    }

    private function getMimeType($fileName) {
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $mimeMap = [
            'json' => 'application/json',
            'txt' => 'text/plain',
            'md' => 'text/markdown',
            'toon' => 'application/json'
        ];

        return $mimeMap[$ext] ?? 'application/octet-stream';
    }

    private function logMigrationSuccess($migrationType, $dirPath, $entityType, $entityId, $filesMigrated) {
        $now = $this->ymdhis();

        $stmt = $this->db->prepare("
            INSERT INTO lupo_filesystem_migration_log (
                migration_type,
                directory_path,
                entity_type,
                entity_id,
                status,
                files_migrated,
                started_ymdhis,
                completed_ymdhis
            ) VALUES (
                :migration_type,
                :directory_path,
                :entity_type,
                :entity_id,
                'success',
                :files_migrated,
                :started_ymdhis,
                :completed_ymdhis
            )
        ");

        $stmt->execute([
            ':migration_type' => $migrationType,
            ':directory_path' => $dirPath,
            ':entity_type' => $entityType,
            ':entity_id' => $entityId,
            ':files_migrated' => $filesMigrated,
            ':started_ymdhis' => $now,
            ':completed_ymdhis' => $now
        ]);
    }

    private function logMigrationError($migrationType, $dirPath, $entityType, $entityId, $errorMessage) {
        if ($this->dryRun) return;

        $now = $this->ymdhis();

        $stmt = $this->db->prepare("
            INSERT INTO lupo_filesystem_migration_log (
                migration_type,
                directory_path,
                entity_type,
                entity_id,
                status,
                error_message,
                started_ymdhis
            ) VALUES (
                :migration_type,
                :directory_path,
                :entity_type,
                :entity_id,
                'failed',
                :error_message,
                :started_ymdhis
            )
        ");

        $stmt->execute([
            ':migration_type' => $migrationType,
            ':directory_path' => $dirPath,
            ':entity_type' => $entityType,
            ':entity_id' => $entityId,
            ':error_message' => $errorMessage,
            ':started_ymdhis' => $now
        ]);
    }

    public function printStats() {
        echo "\n" . str_repeat('=', 70) . "\n";
        echo "MIGRATION STATISTICS\n";
        echo str_repeat('=', 70) . "\n";
        echo "Agents processed:    {$this->stats['agents_processed']}\n";
        echo "  ✓ Success:         {$this->stats['agents_success']}\n";
        echo "  ✗ Failed:          {$this->stats['agents_failed']}\n";
        echo "\n";
        echo "Channels processed:  {$this->stats['channels_processed']}\n";
        echo "  ✓ Success:         {$this->stats['channels_success']}\n";
        echo "  ✗ Failed:          {$this->stats['channels_failed']}\n";
        echo "\n";
        echo "Files migrated:      {$this->stats['files_migrated']}\n";
        echo "Bytes processed:     " . number_format($this->stats['bytes_processed']) . " (" . $this->formatBytes($this->stats['bytes_processed']) . ")\n";
        echo str_repeat('=', 70) . "\n";
    }

    private function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}

// ============================================================================
// MAIN EXECUTION
// ============================================================================

$dryRun = in_array('--dry-run', $argv);
$type = null;

foreach ($argv as $arg) {
    if (strpos($arg, '--type=') === 0) {
        $type = substr($arg, 7);
    }
}

echo "\n";
echo str_repeat('=', 70) . "\n";
echo "FILESYSTEM TO DATABASE MIGRATION\n";
echo str_repeat('=', 70) . "\n";
echo "Mode: " . ($dryRun ? "DRY RUN (no changes will be made)" : "LIVE (changes will be applied)") . "\n";
echo "Type: " . ($type ?: "ALL (agents + channels)") . "\n";
echo str_repeat('=', 70) . "\n\n";

if ($dryRun) {
    echo "⚠ DRY RUN MODE - No database changes will be made\n";
    echo "⚠ Files will NOT be copied to new upload structure\n\n";
}

$migrator = new FilesystemMigrator($dryRun);

if (!$type || $type === 'agents' || $type === 'all') {
    $migrator->migrateAgents();
}

if (!$type || $type === 'channels' || $type === 'all') {
    $migrator->migrateChannels();
}

$migrator->printStats();

echo "\n✓ Migration complete!\n\n";

if ($dryRun) {
    echo "To run the actual migration, execute without --dry-run:\n";
    echo "  php scripts/migrate_filesystem_to_db.php\n\n";
} else {
    echo "Next steps:\n";
    echo "1. Verify data integrity in database\n";
    echo "2. Test application with new database-backed system\n";
    echo "3. Run cleanup script: php scripts/cleanup_old_directories.php\n\n";
}
