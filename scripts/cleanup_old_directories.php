<?php
/**
 * Cleanup Script for Old Numeric Directories
 *
 * Safely removes old agents/NNNN and channels/NNNN directories after
 * verifying that all data has been successfully migrated to the database.
 *
 * Usage: php scripts/cleanup_old_directories.php [--force] [--type=agents|channels]
 *
 * IMPORTANT: Run migration script first and verify data integrity!
 *
 * @package Lupopedia
 * @version 2026.3.9.0
 */

require_once __DIR__ . '/../lupopedia-config.php';

class DirectoryCleanup {
    private $db;
    private $force = false;
    private $backupDir;
    private $stats = [
        'agents_dirs_removed' => 0,
        'agents_dirs_backed_up' => 0,
        'channels_dirs_removed' => 0,
        'channels_dirs_backed_up' => 0,
        'bytes_freed' => 0
    ];

    public function __construct($force = false) {
        $this->force = $force;
        $this->connectDatabase();
        $this->backupDir = LUPOPEDIA_PATH . '/backups/filesystem_migration_' . gmdate('Ymd_His');
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

    /**
     * Verify migration status before cleanup
     */
    public function verifyMigration() {
        echo "\n" . str_repeat('=', 70) . "\n";
        echo "VERIFYING MIGRATION STATUS\n";
        echo str_repeat('=', 70) . "\n";

        // Check migration log for failures
        $stmt = $this->db->query("
            SELECT COUNT(*) as failed_count
            FROM lupo_filesystem_migration_log
            WHERE status = 'failed'
        ");
        $result = $stmt->fetch();

        if ($result['failed_count'] > 0) {
            echo "✗ WARNING: {$result['failed_count']} migration failures detected!\n";
            echo "  Review migration log before proceeding with cleanup.\n\n";

            if (!$this->force) {
                echo "Use --force to proceed anyway (not recommended)\n";
                return false;
            } else {
                echo "⚠ Proceeding with cleanup despite failures (--force)\n\n";
            }
        } else {
            echo "✓ No migration failures detected\n";
        }

        // Count successful migrations
        $stmt = $this->db->query("
            SELECT
                migration_type,
                COUNT(*) as count,
                SUM(files_migrated) as total_files
            FROM lupo_filesystem_migration_log
            WHERE status = 'success'
            GROUP BY migration_type
        ");

        echo "\nMigration summary:\n";
        while ($row = $stmt->fetch()) {
            echo "  {$row['migration_type']}: {$row['count']} directories, {$row['total_files']} files\n";
        }

        return true;
    }

    /**
     * Clean up agent directories
     */
    public function cleanupAgents() {
        $agentsDir = LUPOPEDIA_PATH . '/agents';

        if (!is_dir($agentsDir)) {
            echo "✗ Agents directory not found: $agentsDir\n";
            return;
        }

        $directories = glob($agentsDir . '/*', GLOB_ONLYDIR);
        echo "\n" . str_repeat('=', 70) . "\n";
        echo "CLEANING UP AGENT DIRECTORIES\n";
        echo str_repeat('=', 70) . "\n";
        echo "Found " . count($directories) . " agent directories\n\n";

        foreach ($directories as $dir) {
            $dirName = basename($dir);

            // Only process numeric directories
            if (!preg_match('/^\d{4}$/', $dirName)) {
                echo "⊘ Skipping non-numeric directory: $dirName\n";
                continue;
            }

            // Verify this directory was successfully migrated
            $migrated = $this->checkMigrationStatus('agents', $dir);

            if (!$migrated && !$this->force) {
                echo "⊘ Skipping $dirName (not migrated)\n";
                continue;
            }

            echo "Processing $dirName ... ";

            try {
                $size = $this->getDirectorySize($dir);
                $this->backupDirectory($dir, 'agents');
                $this->removeDirectory($dir);
                $this->stats['agents_dirs_removed']++;
                $this->stats['agents_dirs_backed_up']++;
                $this->stats['bytes_freed'] += $size;
                echo "✓ removed (" . $this->formatBytes($size) . " freed)\n";
            } catch (Exception $e) {
                echo "✗ " . $e->getMessage() . "\n";
            }
        }
    }

    /**
     * Clean up channel directories
     */
    public function cleanupChannels() {
        $channelsDir = LUPOPEDIA_PATH . '/channels';

        if (!is_dir($channelsDir)) {
            echo "✗ Channels directory not found: $channelsDir\n";
            return;
        }

        $directories = glob($channelsDir . '/*', GLOB_ONLYDIR);
        echo "\n" . str_repeat('=', 70) . "\n";
        echo "CLEANING UP CHANNEL DIRECTORIES\n";
        echo str_repeat('=', 70) . "\n";
        echo "Found " . count($directories) . " channel directories\n\n";

        foreach ($directories as $dir) {
            $dirName = basename($dir);

            // Only process numeric directories
            if (!preg_match('/^\d{4}$/', $dirName)) {
                echo "⊘ Skipping non-numeric directory: $dirName\n";
                continue;
            }

            // Verify this directory was successfully migrated
            $migrated = $this->checkMigrationStatus('channels', $dir);

            if (!$migrated && !$this->force) {
                echo "⊘ Skipping $dirName (not migrated)\n";
                continue;
            }

            echo "Processing $dirName ... ";

            try {
                $size = $this->getDirectorySize($dir);
                $this->backupDirectory($dir, 'channels');
                $this->removeDirectory($dir);
                $this->stats['channels_dirs_removed']++;
                $this->stats['channels_dirs_backed_up']++;
                $this->stats['bytes_freed'] += $size;
                echo "✓ removed (" . $this->formatBytes($size) . " freed)\n";
            } catch (Exception $e) {
                echo "✗ " . $e->getMessage() . "\n";
            }
        }
    }

    /**
     * Check if directory was successfully migrated
     */
    private function checkMigrationStatus($type, $dirPath) {
        $stmt = $this->db->prepare("
            SELECT status
            FROM lupo_filesystem_migration_log
            WHERE migration_type = :type
            AND directory_path = :path
            AND status = 'success'
            LIMIT 1
        ");
        $stmt->execute([
            ':type' => $type,
            ':path' => $dirPath
        ]);

        return $stmt->fetch() !== false;
    }

    /**
     * Get total size of directory
     */
    private function getDirectorySize($dir) {
        $size = 0;
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            $size += $file->getSize();
        }

        return $size;
    }

    /**
     * Backup directory before removal
     */
    private function backupDirectory($sourceDir, $type) {
        $dirName = basename($sourceDir);
        $backupPath = $this->backupDir . '/' . $type . '/' . $dirName;

        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $this->recursiveCopy($sourceDir, $backupPath);
    }

    /**
     * Recursively copy directory
     */
    private function recursiveCopy($src, $dst) {
        $dir = opendir($src);
        if (!$dir) {
            throw new Exception("Failed to open source directory: $src");
        }

        while (($file = readdir($dir)) !== false) {
            if ($file === '.' || $file === '..') continue;

            $srcFile = $src . '/' . $file;
            $dstFile = $dst . '/' . $file;

            if (is_dir($srcFile)) {
                if (!is_dir($dstFile)) {
                    mkdir($dstFile, 0755, true);
                }
                $this->recursiveCopy($srcFile, $dstFile);
            } else {
                copy($srcFile, $dstFile);
            }
        }

        closedir($dir);
    }

    /**
     * Remove directory recursively
     */
    private function removeDirectory($dir) {
        if (!is_dir($dir)) {
            return;
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($dir);
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

    public function printStats() {
        echo "\n" . str_repeat('=', 70) . "\n";
        echo "CLEANUP STATISTICS\n";
        echo str_repeat('=', 70) . "\n";
        echo "Agent directories:\n";
        echo "  Removed:         {$this->stats['agents_dirs_removed']}\n";
        echo "  Backed up:       {$this->stats['agents_dirs_backed_up']}\n";
        echo "\n";
        echo "Channel directories:\n";
        echo "  Removed:         {$this->stats['channels_dirs_removed']}\n";
        echo "  Backed up:       {$this->stats['channels_dirs_backed_up']}\n";
        echo "\n";
        echo "Space freed:       " . number_format($this->stats['bytes_freed']) . " bytes (" . $this->formatBytes($this->stats['bytes_freed']) . ")\n";
        echo "Backup location:   {$this->backupDir}\n";
        echo str_repeat('=', 70) . "\n";
    }
}

// ============================================================================
// MAIN EXECUTION
// ============================================================================

$force = in_array('--force', $argv);
$type = null;

foreach ($argv as $arg) {
    if (strpos($arg, '--type=') === 0) {
        $type = substr($arg, 7);
    }
}

echo "\n";
echo str_repeat('=', 70) . "\n";
echo "OLD DIRECTORY CLEANUP SCRIPT\n";
echo str_repeat('=', 70) . "\n";
echo "Force mode: " . ($force ? "YES (skip verification)" : "NO (verify migration first)") . "\n";
echo "Type: " . ($type ?: "ALL (agents + channels)") . "\n";
echo str_repeat('=', 70) . "\n\n";

if ($force) {
    echo "⚠ WARNING: Force mode enabled - directories will be removed even if migration failed!\n\n";
}

echo "This script will:\n";
echo "  1. Verify migration status\n";
echo "  2. Backup directories to /backups/\n";
echo "  3. Remove old numeric directories\n\n";

echo "Press Ctrl+C to cancel, or press Enter to continue...";
if (!$force) {
    fgets(STDIN);
}
echo "\n";

$cleanup = new DirectoryCleanup($force);

// Verify migration status first
if (!$cleanup->verifyMigration()) {
    die("\n✗ Migration verification failed. Aborting cleanup.\n\n");
}

// Perform cleanup
if (!$type || $type === 'agents' || $type === 'all') {
    $cleanup->cleanupAgents();
}

if (!$type || $type === 'channels' || $type === 'all') {
    $cleanup->cleanupChannels();
}

$cleanup->printStats();

echo "\n✓ Cleanup complete!\n\n";
echo "Important:\n";
echo "- Backups are stored in the backups/ directory\n";
echo "- Keep backups for at least 30 days before permanent deletion\n";
echo "- Verify application functionality before removing backups\n\n";
