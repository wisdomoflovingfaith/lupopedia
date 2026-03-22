# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/guidelines/list_csv_documentation.md"
  file_hash: "449d12695d98d2585a405602de6641bd1fd7a77830eb99749ab04cd9b5e6e5ed"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE

---
lupopedia.headers:
  file_path_from_root: "lupo-docs/guidelines/list_csv_documentation.md"
  file_hash: "e08501bf772388793ced517efaccc54634f3b19a864ecb2e6863d23e867afc34"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 10000
  last_modified_utc: "20260228"
  delegation_chain: null
  artifact_type: "documentation"
  purpose: "Comprehensive documentation for list.csv file usage, structure, and database generation"
  dialog_message: "Complete guide for list.csv files across channels, departments, and actors with database integration"
  mood_rgb: "4169E1"
  artifact_kind: "documentation"
  traits: ["list_csv", "database_integration", "file_structure", "4.0.49"]
  tags: ["list_csv", "documentation", "database", "generation", "4.0.49"]
  lupo_agent: "windsurf"

lupopedia.edges:
  file_path_from_root: "lupo-docs\guidelines\list_csv_documentation.md"
  outbound_edges:
    - { to: "lupo-channels/list.csv", type: "examples", weight: 1.0, reason: "Channels list example" }
    - { to: "lupo-channels/departments/list.csv", type: "examples", weight: 0.9, reason: "Departments list example" }
    - { to: "lupo-channels/42/actors/list.csv", type: "examples", weight: 0.9, reason: "Actors list example" }
    - { to: "lupo-docs/toons/", type: "references", weight: 0.8, reason: "TOON schema reference" }
    - { to: "lupo-database/migrations/", type: "references", weight: 0.7, reason: "Database schema and migrations" }
  semantic_tags: ["list_csv_documentation", "database_integration", "file_structure"]

  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# List.csv Files Documentation

## Overview

List.csv files provide structured, comma-separated data exports for channels, departments, and actors. They serve as both human-readable lists and machine-parseable data sources for system integration and backup purposes.

## File Locations and Structure

### Channels List
**Location**: `lupo-channels/list.csv`  
**Purpose**: Complete registry of all channels in the system  
**Primary Key**: `channel_id`

### Departments List
**Location**: `lupo-channels/departments/list.csv`  
**Purpose**: Complete registry of all departments in the system  
**Primary Key**: `department_id`

### Actors Lists
**Location**: `lupo-channels/{channel_id}/actors/list.csv`  
**Purpose**: Registry of all actors assigned to a specific channel  
**Primary Key**: `actor_id`

## Standard CSV Structure

### Common Columns

**Soft Delete Columns**:
- `is_deleted` (first column) - Soft delete flag (0=active, 1=deleted)
- `deleted_ymdhis` - Deletion timestamp (null if not deleted)

**Primary Key Columns**:
- Second column: Primary identifier (`channel_id`, `department_id`, or `actor_id`)
- Used for unique identification and database relationships

**Timestamp Columns**:
- `created_ymdhis` - Creation timestamp (YYYYMMDDHHIISS format)
- `updated_ymdhis` - Last modification timestamp
- Ordered newest first in most displays

**Data Columns**:
- All remaining columns follow TOON schema field order
- Specific to each entity type (channels, departments, actors)

### Ordering Convention

**Standard Sort Order**:
1. `is_deleted ASC` (undeleted records first)
2. Primary key ASC (channel_id, department_id, or actor_id)
3. `created_ymdhis DESC` (newest records first)

**Note on Deleted Records**:
- Current generation scripts filter `WHERE is_deleted = 0` to exclude deleted records
- If audit trails including deleted records are needed, modify queries to `WHERE is_deleted IN (0,1)` and maintain ordering
- For complete exports including deleted records, remove the `is_deleted` filter entirely

## Database Integration

### When Database is Online

#### Generation Script
```php
<?php
// generate_list_csv.php
require_once 'lupo-includes/bootstrap.php';

// Command line argument parsing
$options = getopt('', ['type:', 'channel:', 'federation:', 'from:', 'to:', 'backup']);
$type = isset($options['type']) ? $options['type'] : 'all';
$channel_id = isset($options['channel']) ? (int)$options['channel'] : null;

try {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    // Generate channels list
    if ($type === 'all' || $type === 'channels') {
        $where = isset($options['from']) && isset($options['to']) 
            ? "WHERE created_ymdhis BETWEEN :from_date AND :to_date" 
            : "WHERE is_deleted = 0";
        $params = isset($options['from']) && isset($options['to']) 
            ? ['from_date' => $options['from'], 'to_date' => $options['to']] 
            : [];
            
        $channels = $db->fetchAll("SELECT * FROM {$table_prefix}channels {$where} ORDER BY is_deleted ASC, channel_id ASC, created_ymdhis DESC", $params);
        generate_csv('lupo-channels/list.csv', $channels);
    }
    
    // Generate departments list
    if ($type === 'all' || $type === 'departments') {
        $departments = $db->fetchAll("SELECT * FROM {$table_prefix}departments WHERE is_deleted = 0 ORDER BY department_id ASC, created_ymdhis DESC");
        generate_csv('lupo-channels/departments/list.csv', $departments);
    }
    
    // Generate actors lists for each channel
    if ($type === 'all' || $type === 'actors') {
        if ($channel_id) {
            // Generate for specific channel
            $actors = $db->fetchAll(
                "SELECT a.*, ac.channel_id 
                 FROM {$table_prefix}actors a 
                 LEFT JOIN {$table_prefix}actor_channels ac ON a.actor_id = ac.actor_id 
                 WHERE ac.channel_id = :channel_id AND a.is_deleted = 0 
                 ORDER BY a.is_deleted ASC, a.actor_id ASC, a.created_ymdhis DESC",
                ['channel_id' => $channel_id]
            );
            generate_csv("lupo-channels/{$channel_id}/actors/list.csv", $actors);
        } else {
            // Generate for all channels
            $channels_list = $db->fetchAll("SELECT channel_id FROM {$table_prefix}channels WHERE is_deleted = 0 ORDER BY channel_id ASC");
            foreach ($channels_list as $channel) {
                $actors = $db->fetchAll(
                    "SELECT a.*, ac.channel_id 
                         FROM {$table_prefix}actors a 
                         LEFT JOIN {$table_prefix}actor_channels ac ON a.actor_id = ac.actor_id 
                         WHERE ac.channel_id = :channel_id AND a.is_deleted = 0 
                         ORDER BY a.is_deleted ASC, a.actor_id ASC, a.created_ymdhis DESC",
                    ['channel_id' => $channel['channel_id']]
                );
                generate_csv("lupo-channels/{$channel['channel_id']}/actors/list.csv", $actors);
            }
        }
    }
    
} catch (Exception $e) {
    error_log("CSV generation failed: " . $e->getMessage());
    exit(1);
}

function generate_csv($filename, $data) {
    if (empty($data)) {
        echo "No data to export for $filename\n";
        return;
    }
    
    $dir = dirname($filename);
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0755, true)) {
            throw new Exception("Cannot create directory: $dir");
        }
    }
    
    $temp_file = $filename . '.tmp';
    $fp = fopen($temp_file, 'w');
    if ($fp === false) {
        throw new Exception("Cannot open file: $temp_file");
    }
    
    // Write header
    $headers = array_keys($data[0]);
    fputcsv($fp, $headers);
    
    // Write data
    foreach ($data as $row) {
        fputcsv($fp, $row);
    }
    
    fclose($fp);
    
    // Atomic rename
    if (!rename($temp_file, $filename)) {
        unlink($temp_file);
        throw new Exception("Cannot rename temp file to: $filename");
    }
    
    echo "Generated: $filename (" . count($data) . " records)\n";
}
?>
```

#### Command Line Execution
```bash
# Generate all list.csv files
php lupo-scripts/generate_list_csv.php

# Generate specific entity type
php lupo-scripts/generate_list_csv.php --type=channels
php lupo-scripts/generate_list_csv.php --type=departments
php lupo-scripts/generate_list_csv.php --type=actors

# Generate actors for specific channel
php lupo-scripts/generate_list_csv.php --type=actors --channel=42

# Generate with date range
php lupo-scripts/generate_list_csv.php --type=channels --from=20260201 --to=20260228

# Generate for specific federation node
php lupo-scripts/generate_list_csv.php --type=channels --federation=1

# Generate backup with all data including deleted records
php lupo-scripts/generate_list_csv.php --backup --type=all
```

### Database Schema Mapping

#### Channels Table Mapping
```sql
-- lupo_channels TOON fields to CSV columns
SELECT 
    is_deleted,
    channel_id,
    created_ymdhis,
    federation_node_id,
    created_by_actor_id,
    default_actor_id,
    department_id,
    channel_key,
    channel_slug,
    channel_type,
    language,
    channel_name,
    description,
    website_link,
    metadata_json,
    status_flag,
    end_ymdhis,
    duration_seconds,
    updated_ymdhis,
    deleted_ymdhis,
    aal_metadata_json,
    fleet_composition_json,
    awareness_version,
    channel_number,
    parent_channel_id,
    is_kernel,
    boot_sequence_order
FROM lupo_channels
WHERE is_deleted = 0
ORDER BY is_deleted ASC, channel_id ASC, created_ymdhis DESC
```

#### Departments Table Mapping
```sql
-- lupo_departments TOON fields to CSV columns
SELECT 
    is_deleted,
    department_id,
    created_ymdhis,
    federation_node_id,
    name,
    description,
    department_type,
    default_actor_id,
    settings_json,
    updated_ymdhis,
    deleted_ymdhis
FROM lupo_departments
WHERE is_deleted = 0
ORDER BY is_deleted ASC, department_id ASC, created_ymdhis DESC
```

#### Actors Table Mapping
```sql
-- lupo_actors TOON fields to CSV columns
SELECT 
    is_deleted,
    actor_id,
    created_ymdhis,
    actor_type,
    display_name,
    email,
    channel_id,
    department_id,
    status,
    is_active,
    capabilities_json,
    metadata_json,
    updated_ymdhis,
    deleted_ymdhis
FROM lupo_actors a
LEFT JOIN lupo_actor_channels ac ON a.actor_id = ac.actor_id
WHERE a.is_deleted = 0
ORDER BY a.is_deleted ASC, a.actor_id ASC, a.created_ymdhis DESC
```

## File System Integration

### Automatic Updates
```php
// Trigger CSV regeneration when data changes
class ListCSVManager {
    private $db;
    
    public function __construct() {
        $this->db = DatabaseFactory::getConnection();
    }
    
    public function regenerateChannelList() {
        $channels = $this->db->fetchAll("SELECT * FROM lupo_channels WHERE is_deleted = 0 ORDER BY channel_id ASC");
        $this->generateCSV('lupo-channels/list.csv', $channels);
    }
    
    public function regenerateDepartmentList() {
        $departments = $this->db->fetchAll("SELECT * FROM lupo_departments WHERE is_deleted = 0 ORDER BY department_id ASC");
        $this->generateCSV('lupo-channels/departments/list.csv', $departments);
    }
    
    public function regenerateActorList($channel_id) {
        $actors = $this->db->fetchAll(
            "SELECT a.*, ac.channel_id 
             FROM lupo_actors a 
             LEFT JOIN lupo_actor_channels ac ON a.actor_id = ac.actor_id 
             WHERE ac.channel_id = :channel_id AND a.is_deleted = 0 
             ORDER BY a.actor_id ASC",
            array('channel_id' => $channel_id)
        );
        $this->generateCSV("lupo-channels/{$channel_id}/actors/list.csv", $actors);
    }
    
    private function generateCSV($filename, $data) {
        // Implementation as shown above
    }
}
```

### Real-time Synchronization
```php
// Hook into database operations for real-time CSV updates
class DatabaseChangeHook {
    public static function afterInsert($table, $data) {
        switch ($table) {
            case 'lupo_channels':
                self::regenerateChannelList();
                break;
            case 'lupo_departments':
                self::regenerateDepartmentList();
                break;
            case 'lupo_actors':
            case 'lupo_actor_channels':
                self::regenerateAllActorLists();
                break;
        }
    }
    
    public static function afterUpdate($table, $id, $data) {
        self::afterInsert($table, $data);
    }
    
    public static function afterDelete($table, $id) {
        self::afterInsert($table, $data);
    }
}
```

## Usage Scenarios

### System Administration
```bash
# Generate all CSV files for backup
php lupo-scripts/generate_list_csv.php --backup

# Generate with specific date range
php lupo-scripts/generate_list_csv.php --from=20260201 --to=20260228

# Generate for specific federation node
php lupo-scripts/generate_list_csv.php --federation=1
```

### Data Migration
```php
// Import CSV data to new database
function import_csv_to_database($csv_file, $table_name) {
    $db = DatabaseFactory::getConnection();
    
    if (($handle = fopen($csv_file, 'r')) !== FALSE) {
        $headers = fgetcsv($handle, 1000, ',');
        
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $row = array_combine($headers, $data);
            $db->insert($table_name, $row);
        }
        
        fclose($handle);
    }
}
```

### API Integration
```php
// REST endpoint for CSV generation
$app->get('/api/export/csv/{type}', function($request, $response, $args) {
    $type = $args['type'];
    $channel_id = $request->getParam('channel_id', null);
    
    switch ($type) {
        case 'channels':
            $file = 'lupo-channels/list.csv';
            break;
        case 'departments':
            $file = 'lupo-channels/departments/list.csv';
            break;
        case 'actors':
            if (!$channel_id) {
                return $response->withJson(['error' => 'channel_id required'], 400);
            }
            $file = "lupo-channels/{$channel_id}/actors/list.csv";
            break;
        default:
            return $response->withJson(['error' => 'Invalid type'], 400);
    }
    
    if (file_exists($file)) {
        return $response->withHeader('Content-Type', 'text/csv')
                    ->withHeader('Content-Disposition', "attachment; filename=\"$type.csv\"")
                    ->write(file_get_contents($file));
    } else {
        return $response->withJson(['error' => 'File not found'], 404);
    }
});
```

## Performance Considerations

### Large Datasets
- **Memory Management**: Process in batches for large datasets
- **File Locking**: Use flock() to prevent concurrent writes
- **Compression**: Consider gzip compression for large exports

### Caching Strategy
```php
// Cache generated CSV files
class CSVCache {
    private $cache_dir = 'lupo-cache/csv/';
    private $cache_duration = 3600; // 1 hour
    
    public function getCachedCSV($filename, $query_hash) {
        $cache_file = $this->cache_dir . $query_hash . '_' . basename($filename);
        
        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $this->cache_duration) {
            return file_get_contents($cache_file);
        }
        
        return false;
    }
    
    public function setCachedCSV($filename, $query_hash, $content) {
        $cache_file = $this->cache_dir . $query_hash . '_' . basename($filename);
        file_put_contents($cache_file, $content);
    }
}
```

## Error Handling

### Common Issues
1. **Database Connection**: Handle connection failures gracefully
2. **File Permissions**: Ensure write access to target directories
3. **Memory Limits**: Use batch processing for large datasets
4. **Encoding**: Handle UTF-8 encoding properly
5. **TOON Schema Changes**: Update CSV generation when TOON files change

### Validation
```php
function validate_csv_structure($filename, $expected_headers) {
    if (!file_exists($filename)) {
        return false;
    }
    
    $handle = fopen($filename, 'r');
    $headers = fgetcsv($handle, 1000, ',');
    fclose($handle);
    
    return $headers === $expected_headers;
}
```

## Best Practices

### File Management
- Use atomic writes (temp file + rename)
- Implement proper error logging
- Maintain backup copies
- Use version control for generated files

### Security
- Validate input parameters
- Sanitize file paths
- Implement access controls for sensitive data
- Use prepared statements for database queries

## CSV Examples

### Channels List Example
```csv
is_deleted,channel_id,created_ymdhis,federation_node_id,created_by_actor_id,default_actor_id,department_id,channel_key,channel_slug,channel_type,language,channel_name,description,website_link,metadata_json,status_flag,end_ymdhis,duration_seconds,updated_ymdhis,deleted_ymdhis,aal_metadata_json,fleet_composition_json,awareness_version,channel_number,parent_channel_id,is_kernel,boot_sequence_order
0,0,20260225162516,1,0,0,1,system,system,system,en,System Kernel Channel,System channel (kernel/system operations).,,,,1,,20260225162516,,3.0.0,,,1,,
0,42,20260225130000,1,10000,10000,1,development,development,development,en,Development Channel,Primary development coordination channel for Lupopedia Semantic OS,https://lupopedia.com/lupopedia/content/42,"{""purpose"":""development_coordination"",""type"":""primary_development"",""version"":""4.0.49""}",1,,,20260225130000,,3.0.0,42,,0,
```

### Departments List Example
```csv
is_deleted,department_id,created_ymdhis,federation_node_id,name,description,department_type,default_actor_id,settings_json,updated_ymdhis,deleted_ymdhis
0,0,20260225162525,1,System,System Department (Reserved),system,0,,20260225162525,
1,1,20260225163000,1,Development,Department for development coordination and task management,general,10000,"{""allow_self_assignment"":true,""task_tracking"":true}",20260225163000,
```

### Actors List Example
```csv
is_deleted,actor_id,created_ymdhis,actor_type,display_name,email,channel_id,department_id,status,is_active,capabilities_json,metadata_json,updated_ymdhis,deleted_ymdhis
0,0,20260225000000,system,System Agent,,42,1,active,"{""system_tool"":true,""development"":true}",,20260225000000,
0,10000,20260225000000,human,Captain,,42,1,active,"{""system_admin"":true,""development"":true}",,20260225000000,
0,1001,20260225000000,ide_agent,Windsurf IDE,,42,1,active,"{""system_tool"":true,""development"":true}",,20260225000000,
```

## Database Schema Mapping

### TOON Schema Reference
**Source**: `lupo-docs/toons/*.toon.json` files  
**Purpose**: Canonical database structure definition  
**Usage**: All CSV generation must reference TOON field order

**Field Mapping Process**:
1. Read TOON file for target table
2. Extract field order from `fields` array
3. Map TOON field names to CSV column names
4. Generate SELECT query matching field order
5. Export data maintaining TOON sequence

### Schema Change Handling
```php
function get_toon_field_order($table_name) {
    $toon_file = "lupo-docs/toons/{$table_name}.toon.json";
    $toon_data = json_decode(file_get_contents($toon_file), true);
    return $toon_data['fields'];
}

function generate_csv_from_toon($table_name, $filename) {
    $fields = get_toon_field_order($table_name);
    $field_list = implode(', ', array_map(function($field) {
        return str_replace('`', '', $field);
    }, $fields));
    
    $query = "SELECT {$field_list} FROM lupo_{$table_name} WHERE is_deleted = 0 ORDER BY created_ymdhis DESC";
    // Execute query and generate CSV...
}
```

### Overall Assessment

This documentation for `list.csv` files is comprehensive, well-organized, and serves its stated purpose as a "Complete guide for list.csv files across channels, departments, and actors with database integration." It covers everything from high level overviews to low-level implementation details, including code snippets in PHP, SQL, and bash, which makes it practical for developers, administrators, and users involved in system maintenance. The structure follows a logical progression: starting with basics (locations, structure), moving to integration (database, file system), and ending with advanced topics (performance, error handling, best practices). The inclusion of metadata in the FLARE header and footer adds a professional touch, facilitating versioning and discoverability.

Strengths:
- **Clarity and Readability**: The markdown format is clean, with clear headings, bullet points, and code blocks. Terms like "TOON schema" are referenced consistently, assuming familiarity but providing links (e.g., to `lupo-docs/toons/`).
- **Technical Depth**: The PHP scripts for generation, hooks for real-time sync, and SQL mappings are detailed and appear functional. For instance, the `generate_csv` function handles directory creation, header writing, and data export robustly. Usage scenarios (e.g., command-line execution, API integration, data migration) make it actionable. Error handling and best practices sections address real-world concerns like memory management and security.
- **Practicality**: Usage scenarios (e.g., command-line execution, API integration, data migration) make it actionable. Error handling and best practices sections address real-world concerns like memory management and security.
- **Consistency**: Column orders, primary keys, and sort conventions are uniformly described. The focus on soft deletes (via `is_deleted` and `deleted_ymdhis`) is a good design choice for data integrity.
- **Versioning**: Tied to system version 4.0.49, with last modified/verified dates, which helps track changes.

### Potential Issues and Suggestions for Improvement

While the document is strong, here are some observations on accuracy, completeness, and enhancements:

1. **Inconsistencies in Ordering and Deleted Records**:
