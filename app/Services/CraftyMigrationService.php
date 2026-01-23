<?php
/**
 * Crafty Syntax Migration Service
 *
 * Executes SQL-only migration from Crafty Syntax to Lupopedia.
 * Handles chunked execution, progress tracking, and rollback operations.
 *
 * @package Lupopedia
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 * @author GLOBAL_CURRENT_AUTHORS
 */

namespace App\Services;

use Exception;
use PDO;

/**
 * CraftyMigrationService
 *
 * SQL-only migration service with chunked processing and rollback support.
 */
class CraftyMigrationService
{
    /** @var string Migration SQL file path */
    private const MIGRATION_SQL_PATH = 'database/migrations/craftysyntax_to_lupopedia_mysql.sql';

    /** @var string Progress storage file */
    private const PROGRESS_FILE = 'storage/framework/cache/crafty_migration_progress.json';

    /** @var string Log file path */
    private const LOG_FILE = 'storage/logs/crafty_migration.log';

    /** @var int Chunk size for large operations */
    private const CHUNK_SIZE = 1000;

    /** @var PDO|null Database connection */
    private $db;

    /** @var array Migration progress state */
    private $progress;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initializeDatabase();
        $this->initializeProgress();
    }

    /**
     * Start migration process
     *
     * @return array Result array with success status
     */
    public function startMigration(): array
    {
        try {
            $this->log('Starting Crafty Syntax migration');

            // Initialize progress tracking
            $this->updateProgress('initializing', 0, 100);

            // Load and validate migration SQL
            $statements = $this->loadMigrationSql();

            if (empty($statements)) {
                throw new Exception('No SQL statements found in migration file');
            }

            $this->log('Found ' . count($statements) . ' SQL statements to execute');

            // Execute migration in transaction
            $this->db->beginTransaction();

            try {
                $this->executeSqlFile($statements);
                $this->db->commit();

                $this->updateProgress('completed', 100, 100);
                $this->log('Migration completed successfully');

                return [
                    'success' => true,
                    'message' => 'Migration completed successfully',
                    'migration_id' => $this->generateMigrationId(),
                    'statements_executed' => count($statements)
                ];

            } catch (Exception $e) {
                $this->db->rollback();
                throw $e;
            }

        } catch (Exception $e) {
            $this->log('Migration failed: ' . $e->getMessage());
            $this->updateProgress('failed', 0, 100);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Execute SQL file statements
     *
     * @param array $statements SQL statements to execute
     * @throws Exception If execution fails
     */
    public function executeSqlFile(array $statements): void
    {
        $totalStatements = count($statements);
        $executed = 0;

        foreach ($statements as $sql) {
            $sql = trim($sql);

            if (empty($sql)) {
                continue;
            }

            // Check if this is a chunked insert operation
            if ($this->isLargeInsertStatement($sql)) {
                $this->executeChunkedInsert($sql);
            } else {
                $this->executeStatement($sql);
            }

            $executed++;
            $progress = intval(($executed / $totalStatements) * 90); // Reserve 10% for validation
            $this->updateProgress('executing', $progress, 100);

            $this->log("Executed statement {$executed}/{$totalStatements}");
        }
    }

    /**
     * Execute single SQL statement
     *
     * @param string $sql SQL statement to execute
     * @throws Exception If execution fails
     */
    public function executeStatement(string $sql): void
    {
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute();

            if (!$result) {
                throw new Exception('Statement execution failed: ' . implode(', ', $stmt->errorInfo()));
            }

        } catch (Exception $e) {
            throw new Exception('SQL execution error: ' . $e->getMessage() . ' - SQL: ' . substr($sql, 0, 100));
        }
    }

    /**
     * Execute large insert statement in chunks
     *
     * @param string $sql Large insert statement
     * @throws Exception If execution fails
     */
    public function executeChunkedInsert(string $sql): void
    {
        // Parse INSERT statement to extract components
        $insertParts = $this->parseInsertStatement($sql);

        if (!$insertParts) {
            // Fallback to regular execution
            $this->executeStatement($sql);
            return;
        }

        $table = $insertParts['table'];
        $columns = $insertParts['columns'];
        $values = $insertParts['values'];

        $totalRows = count($values);
        $chunks = array_chunk($values, self::CHUNK_SIZE);
        $processedRows = 0;

        foreach ($chunks as $chunk) {
            $chunkSql = "INSERT INTO {$table} ({$columns}) VALUES " . implode(', ', $chunk);

            $this->executeStatement($chunkSql);

            $processedRows += count($chunk);
            $this->log("Processed {$processedRows}/{$totalRows} rows for table {$table}");
        }
    }

    /**
     * Update migration progress
     *
     * @param string $table Current table being processed
     * @param int $processed Number of items processed
     * @param int $total Total number of items
     */
    public function updateProgress(string $table, int $processed, int $total): void
    {
        $this->progress = [
            'status' => $this->determineStatus($table, $processed, $total),
            'current_table' => $table,
            'processed' => $processed,
            'total' => $total,
            'percentage' => $total > 0 ? intval(($processed / $total) * 100) : 0,
            'updated_at' => date('Y-m-d H:i:s'),
            'estimated_completion' => $this->estimateCompletion($processed, $total)
        ];

        // Save to session if available
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['crafty_migration_progress'] = $this->progress;
        }

        // Save to file
        $this->saveProgressToFile();
    }

    /**
     * Get current migration progress
     *
     * @return array Progress data
     */
    public function getProgress(): array
    {
        // Try session first
        if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['crafty_migration_progress'])) {
            return $_SESSION['crafty_migration_progress'];
        }

        // Fallback to file
        return $this->loadProgressFromFile();
    }

    /**
     * Generate migration preview without executing
     *
     * @return array Migration preview data
     */
    public function generateMigrationPreview(): array
    {
        try {
            if (!file_exists(self::MIGRATION_SQL_PATH)) {
                throw new Exception('Migration SQL file not found');
            }

            $statements = $this->loadMigrationSql();

            return [
                'success' => true,
                'total_statements' => count($statements),
                'estimated_duration' => $this->estimateMigrationDuration(count($statements)),
                'tables_affected' => $this->extractTablesFromSql($statements),
                'sql_file_size' => filesize(self::MIGRATION_SQL_PATH),
                'chunk_size' => self::CHUNK_SIZE
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Pause migration execution
     *
     * @return array Result array
     */
    public function pauseMigration(): array
    {
        $this->progress['status'] = 'paused';
        $this->progress['paused_at'] = date('Y-m-d H:i:s');

        $this->saveProgressToFile();

        return [
            'success' => true,
            'message' => 'Migration paused successfully'
        ];
    }

    /**
     * Resume paused migration
     *
     * @return array Result array
     */
    public function resumeMigration(): array
    {
        $this->progress['status'] = 'running';
        $this->progress['resumed_at'] = date('Y-m-d H:i:s');

        $this->saveProgressToFile();

        return [
            'success' => true,
            'message' => 'Migration resumed successfully'
        ];
    }

    /**
     * Cancel migration execution
     *
     * @return array Result array
     */
    public function cancelMigration(): array
    {
        $this->progress['status'] = 'cancelled';
        $this->progress['cancelled_at'] = date('Y-m-d H:i:s');

        $this->saveProgressToFile();
        $this->log('Migration cancelled by user');

        return [
            'success' => true,
            'message' => 'Migration cancelled successfully'
        ];
    }

    /**
     * Execute rollback procedure
     *
     * @return array Result array
     */
    public function rollback(): array
    {
        try {
            $this->log('Starting migration rollback');

            // This method coordinates with CraftyBackupService
            // The actual restoration is handled by the backup service
            $this->updateProgress('rolling_back', 0, 100);

            // Clean up migration state
            $this->clearProgress();

            $this->log('Rollback preparation completed');

            return [
                'success' => true,
                'message' => 'Rollback preparation completed'
            ];

        } catch (Exception $e) {
            $this->log('Rollback failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get migration summary
     *
     * @return array Migration summary
     */
    public function getMigrationSummary(): array
    {
        $progress = $this->getProgress();

        return [
            'status' => $progress['status'] ?? 'unknown',
            'completion_time' => $progress['updated_at'] ?? null,
            'tables_processed' => $progress['current_table'] ?? 'none',
            'total_progress' => $progress['percentage'] ?? 0,
            'migration_file' => self::MIGRATION_SQL_PATH,
            'log_file' => self::LOG_FILE
        ];
    }

    /**
     * Initialize database connection
     */
    private function initializeDatabase(): void
    {
        try {
            // Try to get connection from existing config or use defaults
            if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASS')) {
                $this->db = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } else {
                throw new Exception('Database configuration not available');
            }
        } catch (Exception $e) {
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Initialize progress tracking
     */
    private function initializeProgress(): void
    {
        $this->progress = [
            'status' => 'idle',
            'current_table' => '',
            'processed' => 0,
            'total' => 0,
            'percentage' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Load migration SQL file and split into statements
     *
     * @return array Array of SQL statements
     * @throws Exception If file cannot be loaded
     */
    private function loadMigrationSql(): array
    {
        if (!file_exists(self::MIGRATION_SQL_PATH)) {
            throw new Exception('Migration SQL file not found: ' . self::MIGRATION_SQL_PATH);
        }

        $content = file_get_contents(self::MIGRATION_SQL_PATH);

        if ($content === false) {
            throw new Exception('Unable to read migration SQL file');
        }

        // Split into statements
        $statements = [];
        $lines = explode("\n", $content);
        $currentStatement = '';

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if (empty($line) || strpos($line, '--') === 0 || strpos($line, '/*') === 0) {
                continue;
            }

            $currentStatement .= $line . "\n";

            // Check for statement terminator
            if (substr(rtrim($line), -1) === ';') {
                $statements[] = trim($currentStatement);
                $currentStatement = '';
            }
        }

        return array_filter($statements);
    }

    /**
     * Check if SQL statement is a large insert
     *
     * @param string $sql SQL statement
     * @return bool True if large insert
     */
    private function isLargeInsertStatement(string $sql): bool
    {
        return stripos($sql, 'INSERT INTO') === 0 && substr_count($sql, 'VALUES') > 0;
    }

    /**
     * Parse INSERT statement into components
     *
     * @param string $sql INSERT statement
     * @return array|null Parsed components or null
     */
    private function parseInsertStatement(string $sql): ?array
    {
        // Simple parser for INSERT INTO table (columns) VALUES (...)
        if (!preg_match('/INSERT INTO\s+(\w+)\s*\(([^)]+)\)\s*VALUES\s*(.+)/i', $sql, $matches)) {
            return null;
        }

        $table = $matches[1];
        $columns = $matches[2];
        $valuesString = $matches[3];

        // Extract individual value sets
        $values = [];
        $depth = 0;
        $current = '';

        for ($i = 0; $i < strlen($valuesString); $i++) {
            $char = $valuesString[$i];

            if ($char === '(') {
                $depth++;
            } elseif ($char === ')') {
                $depth--;
                $current .= $char;

                if ($depth === 0) {
                    $values[] = trim($current);
                    $current = '';
                    continue;
                }
            }

            if ($depth > 0) {
                $current .= $char;
            }
        }

        return [
            'table' => $table,
            'columns' => $columns,
            'values' => array_filter($values)
        ];
    }

    /**
     * Determine migration status based on progress
     *
     * @param string $table Current table
     * @param int $processed Processed items
     * @param int $total Total items
     * @return string Status
     */
    private function determineStatus(string $table, int $processed, int $total): string
    {
        if ($table === 'completed') {
            return 'completed';
        }

        if ($table === 'failed') {
            return 'failed';
        }

        if ($table === 'initializing') {
            return 'initializing';
        }

        if ($table === 'rolling_back') {
            return 'rolling_back';
        }

        return 'running';
    }

    /**
     * Estimate completion time
     *
     * @param int $processed Processed items
     * @param int $total Total items
     * @return string Estimated completion time
     */
    private function estimateCompletion(int $processed, int $total): string
    {
        if ($total === 0 || $processed === 0) {
            return 'Unknown';
        }

        $remaining = $total - $processed;
        $estimatedMinutes = intval($remaining / 100); // Rough estimate

        return $estimatedMinutes . ' minutes remaining';
    }

    /**
     * Save progress to file
     */
    private function saveProgressToFile(): void
    {
        try {
            $dir = dirname(self::PROGRESS_FILE);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            file_put_contents(self::PROGRESS_FILE, json_encode($this->progress, JSON_PRETTY_PRINT));
        } catch (Exception $e) {
            // Log error but don't throw - progress saving is not critical
            error_log('Failed to save progress: ' . $e->getMessage());
        }
    }

    /**
     * Load progress from file
     *
     * @return array Progress data
     */
    private function loadProgressFromFile(): array
    {
        if (!file_exists(self::PROGRESS_FILE)) {
            return $this->progress;
        }

        $content = file_get_contents(self::PROGRESS_FILE);
        if ($content === false) {
            return $this->progress;
        }

        $data = json_decode($content, true);
        return $data ?: $this->progress;
    }

    /**
     * Clear progress data
     */
    private function clearProgress(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            unset($_SESSION['crafty_migration_progress']);
        }

        if (file_exists(self::PROGRESS_FILE)) {
            unlink(self::PROGRESS_FILE);
        }
    }

    /**
     * Extract affected tables from SQL statements
     *
     * @param array $statements SQL statements
     * @return array Table names
     */
    private function extractTablesFromSql(array $statements): array
    {
        $tables = [];

        foreach ($statements as $sql) {
            if (preg_match('/(?:INSERT INTO|UPDATE|DELETE FROM|CREATE TABLE|ALTER TABLE)\s+(\w+)/i', $sql, $matches)) {
                $tables[] = $matches[1];
            }
        }

        return array_unique($tables);
    }

    /**
     * Estimate migration duration
     *
     * @param int $statementCount Number of statements
     * @return string Estimated duration
     */
    private function estimateMigrationDuration(int $statementCount): string
    {
        $estimatedMinutes = intval($statementCount / 10); // Rough estimate
        return max(1, $estimatedMinutes) . ' minutes';
    }

    /**
     * Generate unique migration ID
     *
     * @return string Migration ID
     */
    private function generateMigrationId(): string
    {
        return 'crafty_migration_' . date('Ymd_His') . '_' . substr(md5(uniqid()), 0, 8);
    }

    /**
     * Log message to migration log file
     *
     * @param string $message Log message
     */
    public function log(string $message): void
    {
        try {
            $dir = dirname(self::LOG_FILE);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $timestamp = date('Y-m-d H:i:s');
            $logEntry = "[{$timestamp}] {$message}\n";

            file_put_contents(self::LOG_FILE, $logEntry, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            // Fallback to error_log if file logging fails
            error_log("CraftyMigration: {$message}");
        }
    }
}
