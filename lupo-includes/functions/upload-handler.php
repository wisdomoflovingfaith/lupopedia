<?php
/**
 * Upload Handler with Hash-Based Filenames
 *
 * Provides secure, scalable file upload handling with:
 * - Hash-based filenames (SHA256)
 * - Date-based directory structure (YYYY/MM)
 * - Automatic duplicate detection
 * - MIME type validation
 * - Size limits
 *
 * @package Lupopedia
 * @version 2026.3.9.0
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. upload-handler.php cannot be called directly.");
}

class LupoUploadHandler {
    private $uploadsRoot;
    private $allowedTypes = [];
    private $maxFileSize = 10485760; // 10MB default
    private $db;

    public function __construct() {
        $this->uploadsRoot = LUPOPEDIA_PATH . '/uploads';
        $this->ensureUploadDirectories();
        $this->connectDatabase();
    }

    private function connectDatabase() {
        global $wpdb;
        if (isset($wpdb)) {
            $this->db = $wpdb;
        } else {
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
            } catch (PDOException $e) {
                throw new Exception("Database connection failed: " . $e->getMessage());
            }
        }
    }

    private function ensureUploadDirectories() {
        $dirs = ['agents', 'channels', 'operators', 'content', 'temp'];

        foreach ($dirs as $dir) {
            $path = $this->uploadsRoot . '/' . $dir;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }
    }

    /**
     * Set allowed MIME types for uploads
     *
     * @param array $types Array of allowed MIME types
     */
    public function setAllowedTypes(array $types) {
        $this->allowedTypes = $types;
    }

    /**
     * Set maximum file size in bytes
     *
     * @param int $bytes Maximum file size
     */
    public function setMaxFileSize($bytes) {
        $this->maxFileSize = $bytes;
    }

    /**
     * Upload a file with hash-based filename
     *
     * @param array $file $_FILES array element
     * @param string $entityType 'agent', 'channel', 'operator', or 'content'
     * @param int $entityId Database ID of the entity
     * @param string $fileType Type classification (metadata, avatar, document, etc.)
     * @return array Upload result with file info
     */
    public function upload($file, $entityType, $entityId, $fileType = 'document') {
        // Validate input
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception("Invalid file upload");
        }

        // Validate entity type
        $validTypes = ['agent', 'channel', 'operator', 'content'];
        if (!in_array($entityType, $validTypes)) {
            throw new Exception("Invalid entity type: $entityType");
        }

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception($this->getUploadErrorMessage($file['error']));
        }

        // Validate file size
        if ($file['size'] > $this->maxFileSize) {
            throw new Exception("File size exceeds maximum allowed: " . $this->formatBytes($this->maxFileSize));
        }

        // Read file content
        $content = file_get_contents($file['tmp_name']);
        if ($content === false) {
            throw new Exception("Failed to read uploaded file");
        }

        // Generate hash
        $hash = hash('sha256', $content);

        // Check for duplicate
        $existing = $this->findFileByHash($hash, $entityType);
        if ($existing) {
            return [
                'success' => true,
                'duplicate' => true,
                'file_id' => $existing['file_id'],
                'file_path' => $existing['file_path'],
                'message' => 'File already exists (duplicate detected)'
            ];
        }

        // Detect MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        // Validate MIME type if restrictions are set
        if (!empty($this->allowedTypes) && !in_array($mimeType, $this->allowedTypes)) {
            throw new Exception("File type not allowed: $mimeType");
        }

        // Generate date-based path structure
        $datePath = gmdate('Y') . '/' . gmdate('m');

        // Generate hash-based filename
        $originalName = $file['name'];
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $hashFilename = $hash . ($extension ? '.' . $extension : '');

        // Build full paths
        $relativePath = "{$entityType}s/$datePath/$hashFilename";
        $fullPath = $this->uploadsRoot . '/' . $relativePath;

        // Create directory structure
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            throw new Exception("Failed to move uploaded file");
        }

        // Insert database record
        $fileId = $this->insertFileRecord(
            $entityType,
            $entityId,
            $fileType,
            $originalName,
            $relativePath,
            $hash,
            $file['size'],
            $mimeType
        );

        return [
            'success' => true,
            'duplicate' => false,
            'file_id' => $fileId,
            'file_path' => $relativePath,
            'file_hash' => $hash,
            'file_size' => $file['size'],
            'mime_type' => $mimeType,
            'message' => 'File uploaded successfully'
        ];
    }

    /**
     * Find file by hash to detect duplicates
     */
    private function findFileByHash($hash, $entityType) {
        $table = $this->getFileTableName($entityType);

        if ($this->db instanceof PDO) {
            $stmt = $this->db->prepare("
                SELECT file_id, file_path, file_hash
                FROM $table
                WHERE file_hash = :hash
                AND is_deleted = 0
                LIMIT 1
            ");
            $stmt->execute([':hash' => $hash]);
            return $stmt->fetch();
        } else {
            // WordPress wpdb
            return $this->db->get_row($this->db->prepare("
                SELECT file_id, file_path, file_hash
                FROM $table
                WHERE file_hash = %s
                AND is_deleted = 0
                LIMIT 1
            ", $hash), ARRAY_A);
        }
    }

    /**
     * Insert file record into appropriate table
     */
    private function insertFileRecord($entityType, $entityId, $fileType, $fileName, $filePath, $hash, $fileSize, $mimeType) {
        $table = $this->getFileTableName($entityType);
        $entityIdColumn = $this->getEntityIdColumn($entityType);
        $now = gmdate('YmdHis');

        $data = [
            $entityIdColumn => $entityId,
            'file_type' => $fileType,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_hash' => $hash,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'upload_ymdhis' => $now,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0
        ];

        if ($this->db instanceof PDO) {
            $columns = array_keys($data);
            $placeholders = array_map(function($col) { return ":$col"; }, $columns);

            $sql = sprintf(
                "INSERT INTO %s (%s) VALUES (%s)",
                $table,
                implode(', ', $columns),
                implode(', ', $placeholders)
            );

            $stmt = $this->db->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();

            return $this->db->lastInsertId();
        } else {
            // WordPress wpdb
            $this->db->insert($table, $data);
            return $this->db->insert_id;
        }
    }

    /**
     * Get file table name for entity type
     */
    private function getFileTableName($entityType) {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

        $tableMap = [
            'agent' => $prefix . 'agent_files',
            'channel' => $prefix . 'channel_files',
            'operator' => $prefix . 'operator_files',
            'content' => $prefix . 'content_files'
        ];

        return $tableMap[$entityType] ?? $prefix . 'files';
    }

    /**
     * Get entity ID column name
     */
    private function getEntityIdColumn($entityType) {
        $columnMap = [
            'agent' => 'agent_id',
            'channel' => 'channel_id',
            'operator' => 'operator_id',
            'content' => 'content_id'
        ];

        return $columnMap[$entityType] ?? 'entity_id';
    }

    /**
     * Get upload error message
     */
    private function getUploadErrorMessage($errorCode) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive in php.ini',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive in HTML form',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'Upload stopped by PHP extension'
        ];

        return $errors[$errorCode] ?? 'Unknown upload error';
    }

    /**
     * Format bytes to human-readable size
     */
    private function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Delete a file (soft delete in database, optionally remove from filesystem)
     *
     * @param int $fileId File ID in database
     * @param string $entityType Entity type (agent, channel, operator, content)
     * @param bool $removePhysical Whether to remove physical file
     * @return bool Success status
     */
    public function delete($fileId, $entityType, $removePhysical = false) {
        $table = $this->getFileTableName($entityType);
        $now = gmdate('YmdHis');

        // Get file info before deleting
        if ($this->db instanceof PDO) {
            $stmt = $this->db->prepare("
                SELECT file_path FROM $table WHERE file_id = :file_id LIMIT 1
            ");
            $stmt->execute([':file_id' => $fileId]);
            $file = $stmt->fetch();
        } else {
            $file = $this->db->get_row($this->db->prepare("
                SELECT file_path FROM $table WHERE file_id = %d LIMIT 1
            ", $fileId), ARRAY_A);
        }

        if (!$file) {
            throw new Exception("File not found");
        }

        // Soft delete in database
        if ($this->db instanceof PDO) {
            $stmt = $this->db->prepare("
                UPDATE $table
                SET is_deleted = 1, deleted_ymdhis = :now, updated_ymdhis = :now
                WHERE file_id = :file_id
            ");
            $stmt->execute([
                ':now' => $now,
                ':file_id' => $fileId
            ]);
        } else {
            $this->db->update(
                $table,
                ['is_deleted' => 1, 'deleted_ymdhis' => $now, 'updated_ymdhis' => $now],
                ['file_id' => $fileId]
            );
        }

        // Remove physical file if requested
        if ($removePhysical) {
            $fullPath = $this->uploadsRoot . '/' . $file['file_path'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        return true;
    }

    /**
     * Get file information from database
     *
     * @param int $fileId File ID
     * @param string $entityType Entity type
     * @return array|null File information or null if not found
     */
    public function getFileInfo($fileId, $entityType) {
        $table = $this->getFileTableName($entityType);

        if ($this->db instanceof PDO) {
            $stmt = $this->db->prepare("
                SELECT * FROM $table WHERE file_id = :file_id AND is_deleted = 0 LIMIT 1
            ");
            $stmt->execute([':file_id' => $fileId]);
            return $stmt->fetch();
        } else {
            return $this->db->get_row($this->db->prepare("
                SELECT * FROM $table WHERE file_id = %d AND is_deleted = 0 LIMIT 1
            ", $fileId), ARRAY_A);
        }
    }

    /**
     * Get all files for an entity
     *
     * @param int $entityId Entity ID
     * @param string $entityType Entity type
     * @return array Array of file records
     */
    public function getEntityFiles($entityId, $entityType) {
        $table = $this->getFileTableName($entityType);
        $entityIdColumn = $this->getEntityIdColumn($entityType);

        if ($this->db instanceof PDO) {
            $stmt = $this->db->prepare("
                SELECT * FROM $table
                WHERE $entityIdColumn = :entity_id AND is_deleted = 0
                ORDER BY upload_ymdhis DESC
            ");
            $stmt->execute([':entity_id' => $entityId]);
            return $stmt->fetchAll();
        } else {
            return $this->db->get_results($this->db->prepare("
                SELECT * FROM $table
                WHERE $entityIdColumn = %d AND is_deleted = 0
                ORDER BY upload_ymdhis DESC
            ", $entityId), ARRAY_A);
        }
    }
}

/**
 * Helper function to get upload handler instance
 *
 * @return LupoUploadHandler
 */
function lupo_get_upload_handler() {
    static $instance = null;
    if ($instance === null) {
        $instance = new LupoUploadHandler();
    }
    return $instance;
}

/**
 * Quick upload helper function
 *
 * @param array $file $_FILES element
 * @param string $entityType Entity type
 * @param int $entityId Entity ID
 * @param string $fileType File type classification
 * @return array Upload result
 */
function lupo_upload_file($file, $entityType, $entityId, $fileType = 'document') {
    $handler = lupo_get_upload_handler();
    return $handler->upload($file, $entityType, $entityId, $fileType);
}
