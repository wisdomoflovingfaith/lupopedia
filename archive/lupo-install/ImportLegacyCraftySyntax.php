<?php
/*
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-install/ImportLegacyCraftySyntax.php"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-install/ImportLegacyCraftySyntax.php"
  status: "active"
  when_updated: "20260420180000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/import-legacy-crafty-syntax-php.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/import-legacy-crafty-syntax-php"
  artifact_type: implementation
  artifact_kind: tool
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: implementation
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE"
  title: "ImportLegacyCraftySyntax.php -- Legacy Crafty Syntax Import Wrapper"
  summary: "PHP wrapper for importing legacy Crafty Syntax data with user ID remapping and automatic table detection."
*/

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

/**
 * Import Legacy Crafty Syntax Data Wrapper
 * 
 * Handles the import of legacy Crafty Syntax data with proper user ID remapping
 * and automatic detection of tables containing user_id columns.
 */
class ImportLegacyCraftySyntax
{
    private $pdo;
    private $tablePrefix;
    private $log = array();
    private $jsonSchemas = array();
    
    // Column names from JSON schema for lupo_auth_users
    private $authUserColumns = array(
        'auth_user_id',
        'username',
        'display_name',
        'email',
        'password_hash',
        'auth_provider',
        'provider_id',
        'profile_image_url',
        'last_login_ymdhis',
        'created_ymdhis',
        'updated_ymdhis',
        'is_active',
        'is_deleted',
        'deleted_ymdhis',
        'two_factor_secret',
        'two_factor_enabled',
        'two_factor_backup_codes',
        'otp_code_hash',
        'otp_issued_ymdhis',
        'otp_attempts',
        'timezone_offset',
        'timezone_name'
    );
    
    public function __construct(PDO $pdo, $tablePrefix = 'lupo_')
    {
        $this->pdo = $pdo;
        $this->tablePrefix = $tablePrefix;
        $this->loadJsonSchemas();
    }
    
    /**
     * Load JSON schema files for column names
     */
    private function loadJsonSchemas()
    {
        $schemaDir = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'json';
        
        // Load lupo_auth_users schema
        $schemaFile = $schemaDir . DIRECTORY_SEPARATOR . 'lupo_auth_users.json';
        if (file_exists($schemaFile)) {
            $schema = json_decode(file_get_contents($schemaFile), true);
            if ($schema && isset($schema['fields'])) {
                $this->jsonSchemas['lupo_auth_users'] = $schema['fields'];
            }
        }
    }
    
    /**
     * Check if the required 5 livehelp tables exist
     */
    public function checkLegacyTables()
    {
        $requiredTables = array(
            'livehelp_autoinvite',
            'livehelp_channels',
            'livehelp_operator_departments',
            'livehelp_operator_channels',
            'livehelp_users'
        );
        
        $missingTables = array();
        
        foreach ($requiredTables as $table) {
            $stmt = $this->pdo->prepare("SHOW TABLES LIKE ?");
            $stmt->execute(array($table));
            if ($stmt->rowCount() === 0) {
                $missingTables[] = $table;
            }
        }
        
        if (!empty($missingTables)) {
            $this->log[] = array(
                'level' => 'error',
                'message' => 'Missing required legacy tables: ' . implode(', ', $missingTables)
            );
            return false;
        }
        
        $this->log[] = array(
            'level' => 'ok',
            'message' => 'All required legacy tables found: ' . implode(', ', $requiredTables)
        );
        
        return true;
    }
    
    /**
     * Get the 5 tables that contain user_id column
     */
    public function getTablesWithUserId()
    {
        $tables = array(
            'livehelp_autoinvite',
            'livehelp_channels',
            'livehelp_operator_departments',
            'livehelp_operator_channels'
            // Note: livehelp_users is the source table for mapping, not updated
        );
        
        $this->log[] = array(
            'level' => 'ok',
            'message' => 'Will update user_id in 4 tables: ' . implode(', ', $tables)
        );
        
        return $tables;
    }
    
    /**
     * Execute the import process
     */
    public function executeImport()
    {
        try {
            // Check prerequisites
            if (!$this->checkLegacyTables()) {
                return $this->getResult();
            }
            
            // Get the 4 tables that need user_id updates
            $tablesWithUserId = $this->getTablesWithUserId();
            
            // Begin transaction
            $this->pdo->beginTransaction();
            
            // Step 1: Create mapping table
            $this->createMappingTable();
            
            // Step 2: Populate mapping with sequential IDs
            $mappingResult = $this->populateMappingTable();
            
            if ($mappingResult['overflow']) {
                $this->pdo->rollBack();
                $this->log[] = array(
                    'level' => 'error',
                    'message' => 'Import stopped: Too many legacy users (' . $mappingResult['total'] . '). Limit is 9999.'
                );
                return $this->getResult();
            }
            
            // Step 3: Import users with new IDs
            $this->importUsers();
            
            // Step 4: Update user_id in the 4 specific tables
            $updatedTables = array();
            foreach ($tablesWithUserId as $table) {
                $affected = $this->updateUserIdInTable($table);
                $updatedTables[] = array(
                    'table' => $table,
                    'rows_updated' => $affected
                );
            }
            
            // Commit transaction
            $this->pdo->commit();
            
            $this->log[] = array(
                'level' => 'ok',
                'message' => 'Import completed successfully'
            );
            
            // Add updated tables to result
            $this->updatedTables = $updatedTables;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $this->log[] = array(
                'level' => 'error',
                'message' => 'Import failed: ' . $e->getMessage()
            );
        }
        
        return $this->getResult();
    }
    
    /**
     * Create temporary mapping table
     */
    private function createMappingTable()
    {
        $sql = "
            CREATE TEMPORARY TABLE user_id_mapping (
                legacy_id INT PRIMARY KEY,
                new_id INT NOT NULL
            )
        ";
        
        $this->pdo->exec($sql);
        $this->log[] = array(
            'level' => 'ok',
            'message' => 'Created user ID mapping table'
        );
    }
    
    /**
     * Populate mapping table with sequential IDs
     */
    private function populateMappingTable()
    {
        // Count total users first
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM livehelp_users");
        $totalUsers = $stmt->fetchColumn();
        
        if ($totalUsers > 9999) {
            return array('overflow' => true, 'total' => $totalUsers);
        }
        
        // Populate mapping with sequential IDs starting at 1
        $sql = "
            INSERT INTO user_id_mapping (legacy_id, new_id)
            SELECT user_id, (@new_id := @new_id + 1)
            FROM livehelp_users, (SELECT @new_id := 0) AS vars
            ORDER BY user_id
        ";
        
        $this->pdo->exec($sql);
        
        $this->log[] = array(
            'level' => 'ok',
            'message' => "Mapped {$totalUsers} legacy users to new sequential IDs"
        );
        
        return array('overflow' => false, 'total' => $totalUsers);
    }
    
    /**
     * Import users with new IDs using JSON schema columns
     */
    private function importUsers()
    {
        $columnList = implode(', ', $this->authUserColumns);
        
        $sql = "
            INSERT INTO {$this->tablePrefix}auth_users ({$columnList})
            SELECT 
                m.new_id AS auth_user_id,
                u.username,
                u.displayname AS display_name,
                NULLIF(u.email, '') AS email,
                CASE 
                    WHEN u.password IS NULL OR u.password = '' THEN NULL
                    ELSE u.password
                END AS password_hash,
                'crafty_import' AS auth_provider,
                CAST(u.user_id AS CHAR) AS provider_id,
                NULL AS profile_image_url,
                CASE 
                    WHEN u.lastaction IS NULL OR u.lastaction = 0 THEN NULL
                    ELSE CAST(FROM_UNIXTIME(u.lastaction) AS UNSIGNED)
                END AS last_login_ymdhis,
                UNIX_TIMESTAMP() AS created_ymdhis,
                UNIX_TIMESTAMP() AS updated_ymdhis,
                1 AS is_active,
                0 AS is_deleted,
                NULL AS deleted_ymdhis,
                NULL AS two_factor_secret,
                0 AS two_factor_enabled,
                NULL AS two_factor_backup_codes,
                'import_otp_hash' AS otp_code_hash,
                UNIX_TIMESTAMP() AS otp_issued_ymdhis,
                0 AS otp_attempts,
                0.00 AS timezone_offset,
                'UTC' AS timezone_name
            FROM livehelp_users u
            JOIN user_id_mapping m ON u.user_id = m.legacy_id
        ";
        
        $affected = $this->pdo->exec($sql);
        
        $this->log[] = array(
            'level' => 'ok',
            'message' => "Imported {$affected} users with new IDs"
        );
    }
    
    /**
     * Update user_id values in a specific table using mapping
     */
    private function updateUserIdInTable($table)
    {
        $sql = "
            UPDATE `{$table}` t
            JOIN user_id_mapping m ON t.user_id = m.legacy_id
            SET t.user_id = m.new_id
        ";
        
        $affected = $this->pdo->exec($sql);
        
        $this->log[] = array(
            'level' => 'ok',
            'message' => "Updated {$affected} user_id values in {$table}"
        );
        
        return $affected;
    }
    
    /**
     * Get import result
     */
    public function getResult()
    {
        // Get mapping summary
        $stmt = $this->pdo->query("
            SELECT 
                COUNT(*) AS total_mapped,
                MIN(legacy_id) AS min_legacy_id,
                MAX(legacy_id) AS max_legacy_id,
                MIN(new_id) AS min_new_id,
                MAX(new_id) AS max_new_id
            FROM user_id_mapping
        ");
        
        $mappingSummary = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get imported user count
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM {$this->tablePrefix}auth_users 
            WHERE auth_provider = 'crafty_import'
        ");
        $stmt->execute();
        $importedCount = $stmt->fetchColumn();
        
        return array(
            'success' => empty(array_filter($this->log, function($entry) {
                return $entry['level'] === 'error';
            })),
            'log' => $this->log,
            'mapping_summary' => $mappingSummary,
            'imported_users' => $importedCount,
            'skipped_users' => isset($mappingSummary['total_mapped']) ? 0 : 9999,
            'updated_tables' => isset($this->updatedTables) ? $this->updatedTables : array()
        );
    }
}
