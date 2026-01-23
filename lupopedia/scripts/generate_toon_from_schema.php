<?php
/**
 * wolfie.header.identity: generate-toon-from-schema
 * wolfie.header.placement: /scripts/generate_toon_from_schema.php
 * wolfie.header.version: 4.1.1
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created script to generate TOON files from actual database schema. Scans tables and generates TOON format with fields, indexes, and primary keys. Updates existing TOON files to match current database state."
 *   mood: "00FF00"
 */

/**
 * Generate TOON Files from Database Schema
 * 
 * Scans specified tables in the database and generates/updates TOON files
 * to match the current schema.
 * 
 * Usage: php scripts/generate_toon_from_schema.php [table1] [table2] ...
 * If no tables specified, processes: help_topics, actors, agents, labs_declarations, 
 * labs_violations, contents, channels, collections
 */

require_once __DIR__ . '/../lupopedia-config.php';
require_once __DIR__ . '/../lupo-includes/bootstrap.php';

// Get database connection
if (empty($GLOBALS['mydatabase'])) {
    die("❌ Database connection not available.\n");
}

$db = $GLOBALS['mydatabase'];
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

// Tables to process
$tables_to_process = $argv;
array_shift($tables_to_process); // Remove script name

if (empty($tables_to_process)) {
    $tables_to_process = [
        'help_topics',
        'actors',
        'agents',
        'labs_declarations',
        'labs_violations',
        'contents',
        'channels',
        'collections'
    ];
}

echo "🔍 Generating TOON files from database schema...\n\n";

foreach ($tables_to_process as $table_base) {
    $table_name = $table_prefix . $table_base;
    $toon_file = __DIR__ . '/../database/toon_data/' . $table_name . '.toon';
    
    echo "Processing: {$table_name}...\n";
    
    try {
        // Check if table exists
        $check_sql = "SHOW TABLES LIKE ?";
        $stmt = $db->prepare($check_sql);
        $stmt->execute([$table_name]);
        if (!$stmt->fetch()) {
            echo "  ⚠️  Table {$table_name} does not exist. Skipping.\n\n";
            continue;
        }
        
        // Get table structure
        $desc_sql = "DESCRIBE `{$table_name}`";
        $columns = $db->query($desc_sql)->fetchAll(PDO::FETCH_ASSOC);
        
        // Get indexes
        $index_sql = "SHOW INDEXES FROM `{$table_name}`";
        $indexes_raw = $db->query($index_sql)->fetchAll(PDO::FETCH_ASSOC);
        
        // Get table comment
        $comment_sql = "SELECT TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES 
                       WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?";
        $stmt = $db->prepare($comment_sql);
        $stmt->execute([$table_name]);
        $table_comment = $stmt->fetchColumn() ?: '';
        
        // Build fields array
        $fields = [];
        $primary_key_col = null;
        $unsigned_columns = [];
        
        foreach ($columns as $col) {
            $field_def = "`{$col['Field']}`";
            
            // Build type
            $type = $col['Type'];
            $field_def .= " {$type}";
            
            // Add nullability
            if ($col['Null'] === 'NO') {
                $field_def .= " NOT NULL";
            }
            
            // Add auto_increment
            if (strpos($col['Extra'], 'auto_increment') !== false) {
                $field_def .= " auto_increment";
                $primary_key_col = $col['Field'];
            }
            
            // Add default
            if ($col['Default'] !== null) {
                $default = is_numeric($col['Default']) ? $col['Default'] : "'{$col['Default']}'";
                $field_def .= " DEFAULT {$default}";
            }
            
            // Add comment
            if (!empty($col['Comment'])) {
                $field_def .= " COMMENT '{$col['Comment']}'";
            }
            
            $fields[] = $field_def;
            
            // Track unsigned columns
            if (preg_match('/bigint\s+unsigned|int\s+unsigned/i', $type)) {
                $unsigned_columns[] = [
                    'column_name' => $col['Field'],
                    'current_type' => $type,
                    'expected_type' => preg_replace('/\s+unsigned/i', '', $type),
                    'is_primary_key' => ($col['Key'] === 'PRI')
                ];
            }
        }
        
        // Build indexes
        $indexes = [];
        $index_groups = [];
        
        foreach ($indexes_raw as $idx) {
            $idx_name = $idx['Key_name'];
            
            if ($idx_name === 'PRIMARY') {
                continue; // Skip primary key (handled separately)
            }
            
            if (!isset($index_groups[$idx_name])) {
                $index_groups[$idx_name] = [
                    'columns' => [],
                    'unique' => ($idx['Non_unique'] == 0),
                    'type' => $idx['Index_type']
                ];
            }
            
            $index_groups[$idx_name]['columns'][] = $idx['Column_name'];
        }
        
        foreach ($index_groups as $idx_name => $idx_info) {
            $indexes[] = [
                'index_name' => $idx_name,
                'columns' => $idx_info['columns'],
                'is_unique' => $idx_info['unique'],
                'index_type' => $idx_info['type']
            ];
        }
        
        // Build primary key info
        $primary_key = null;
        if ($primary_key_col) {
            $primary_key = [
                'column_name' => $primary_key_col,
                'expected_name' => $primary_key_col,
                'is_correct' => true,
                'needs_rename' => false
            ];
        }
        
        // Build TOON structure
        $toon_data = [
            'table_name' => $table_name,
            'table_comment' => $table_comment,
            'fields' => $fields,
            'data' => [],
            'primary_key' => $primary_key,
            'unsigned_columns' => $unsigned_columns,
            'indexes' => $indexes
        ];
        
        // Preserve existing data if TOON file exists
        if (file_exists($toon_file)) {
            $existing = json_decode(file_get_contents($toon_file), true);
            if (isset($existing['data']) && !empty($existing['data'])) {
                $toon_data['data'] = $existing['data'];
            }
        }
        
        // Write TOON file
        $json = json_encode($toon_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents($toon_file, $json);
        
        echo "  ✅ Generated: {$toon_file}\n";
        echo "     Fields: " . count($fields) . "\n";
        echo "     Indexes: " . count($indexes) . "\n";
        if ($primary_key) {
            echo "     Primary Key: {$primary_key_col}\n";
        }
        echo "\n";
        
    } catch (PDOException $e) {
        echo "  ❌ Error processing {$table_name}: " . $e->getMessage() . "\n\n";
    }
}

echo "✅ TOON file generation complete!\n";
