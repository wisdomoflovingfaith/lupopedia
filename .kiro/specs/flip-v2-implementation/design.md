# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\.kiro\specs\flip-v2-implementation\design.md"
  file_hash: "b73b3930c8141068a9400d7cf5a619915a5f84ebfa81dd7f58cfe83121280b57"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: ".kiro\specs\flip-v2-implementation\design.md"
  file_hash: "8be279f8d3696af2edd0e293a928b87832a224096e1a811f0ee103af2c8a1017"
  file_path_from_root: ".kiro\specs\flip-v2-implementation\design.md"
  file_hash: "34e1e8b4764a99dfddb052fb6ad41b0c91993adfa4650eb80c07b52c8085a405"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "FLIP v2 Implementation Design Document"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["kiro", "specs", "flip-v2-implementation", "designmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# FLIP v2 Implementation Design Document

## Overview

FLIP v2 extends the existing File-Level Inference Protocol (FLIP v1) by adding database persistence for parsed FLIP header and footer metadata. This design enables efficient querying, semantic relationship tracking, and artifact integrity verification across the Lupopedia codebase.

The implementation follows Lupopedia's core architectural doctrines: no foreign keys, no triggers, no stored procedures, BIGINT YMDHIS timestamps, PHP 5.3 compatibility, and PDO_DB-only database access through DatabaseFactory.

### Design Goals

1. Enable efficient querying of FLIP metadata without filesystem scanning
2. Track semantic relationships between artifacts through footer edges
3. Verify artifact integrity through SHA-256 hashing
4. Maintain backward compatibility with FLIP v1 files
5. Support incremental adoption without requiring file format migration

### Key Constraints

- PHP 5.3 compatibility (no modern PHP features)
- No external dependencies (no Composer, no YAML libraries)
- Database-agnostic SQL (MySQL 8.0+, MariaDB 10.5+, PostgreSQL)
- All database access through DatabaseFactory::getConnection()
- BIGINT YMDHIS timestamps only (no DATETIME, TIMESTAMP, or epoch)
- No foreign keys, triggers, stored procedures, or views

## Architecture

### System Components

The FLIP v2 system consists of four primary components:

1. **FLIPScanner**: Extracts FLIP metadata from markdown files
2. **FLIPArtifactRepository**: Manages database persistence of artifacts
3. **FLIPEdgeMapper**: Creates semantic relationships from footer metadata
4. **FLIPBackfillService**: One-time migration to populate existing artifacts

### Component Interaction Flow

```
┌─────────────────┐
│  Markdown File  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  FLIPScanner    │──────┐
│  - Parse YAML   │      │
│  - Extract meta │      │
│  - Compute hash │      │
└────────┬────────┘      │
         │               │
         ▼               ▼
┌─────────────────┐  ┌──────────────────┐
│ FLIPArtifact    │  │  FLIPEdgeMapper  │
│ Repository      │  │  - Parse footer  │
│ - Insert/Update │  │  - Create edges  │
└────────┬────────┘  └────────┬─────────┘
         │                    │
         ▼                    ▼
┌─────────────────────────────────┐
│  lupo_flip_artifacts table      │
│  lupo_edges table                │
└──────────────────────────────────┘
```

### Data Flow

1. **Scanning Phase**: FLIPScanner reads markdown files and extracts YAML headers/footers
2. **Parsing Phase**: YAML content is parsed into associative arrays (no external libraries)
3. **Hashing Phase**: SHA-256 hash computed for integrity verification
4. **Storage Phase**: FLIPArtifactRepository persists metadata to database
5. **Edge Mapping Phase**: FLIPEdgeMapper creates relationships in lupo_edges table

### Integration Points

- **UrlResolver**: Already uses FLIP headers for web path resolution (tier 3 fallback)
- **lupo_contents**: Existing content table; FLIP artifacts complement this
- **lupo_edges**: Existing semantic edges table; footer edges populate this
- **lupo_actors**: Actor validation for actor_id fields
- **lupo_agents**: Agent validation for agent_slug fields

## Components and Interfaces

### FLIPScanner Class

**Location**: `lupo-includes/classes/FLIPScanner.php`

**Responsibility**: Extract FLIP metadata from markdown files

**Public Methods**:

```php
class FLIPScanner
{
    /**
     * Scan a single file and extract FLIP metadata
     * @param string $filePath Absolute path to file
     * @return array|null Parsed metadata or null on failure
     */
    public function scanFile($filePath);

    /**
     * Scan a directory recursively
     * @param string $dirPath Absolute path to directory
     * @param array $extensions File extensions to scan (default: ['md'])
     * @return array Array of parsed metadata
     */
    public function scanDirectory($dirPath, $extensions = array('md'));

    /**
     * Extract YAML header from file content
     * @param string $content File content
     * @return array|null Parsed header or null
     */
    public function extractHeader($content);

    /**
     * Extract YAML footer from file content
     * @param string $content File content
     * @return array|null Parsed footer or null
     */
    public function extractFooter($content);

    /**
     * Compute SHA-256 hash of file content
     * @param string $content File content
     * @return string 64-character hex hash
     */
    public function computeHash($content);
}
```

**Dependencies**:
- None (pure PHP, no external libraries)

**Error Handling**:
- Returns null on parse failures
- Logs warnings for malformed YAML
- Continues processing on individual file errors

### FLIPArtifactRepository Class

**Location**: `app/Services/FLIPArtifactRepository.php`

**Responsibility**: Manage database persistence of FLIP artifacts

**Public Methods**:

```php
class FLIPArtifactRepository
{
    /**
     * Constructor
     * @param PDO_DB $db Database connection
     */
    public function __construct($db);

    /**
     * Insert or update a FLIP artifact
     * @param array $metadata Parsed metadata from scanner
     * @return int|false flip_artifact_id or false on failure
     */
    public function upsert($metadata);

    /**
     * Find artifact by file path
     * @param string $filePath File path from root
     * @return array|null Artifact record or null
     */
    public function findByPath($filePath);

    /**
     * Find artifacts by actor_id
     * @param int $actorId Actor ID
     * @param int $limit Result limit
     * @return array Array of artifact records
     */
    public function findByActor($actorId, $limit = 100);

    /**
     * Find artifacts by channel_id
     * @param int $channelId Channel ID
     * @param int $limit Result limit
     * @return array Array of artifact records
     */
    public function findByChannel($channelId, $limit = 100);

    /**
     * Check if artifact needs update (hash differs)
     * @param string $filePath File path from root
     * @param string $newHash New file hash
     * @return bool True if update needed
     */
    public function needsUpdate($filePath, $newHash);

    /**
     * Soft delete an artifact
     * @param int $flipArtifactId Artifact ID
     * @return bool Success
     */
    public function softDelete($flipArtifactId);
}
```

**Dependencies**:
- PDO_DB (via DatabaseFactory)
- LUPO_TABLE_PREFIX constant

**Error Handling**:
- Returns false on database errors
- Logs errors with file path and query
- Uses transactions for multi-step operations

### FLIPEdgeMapper Class

**Location**: `app/Services/FLIPEdgeMapper.php`

**Responsibility**: Create semantic relationships from FLIP footer metadata

**Public Methods**:

```php
class FLIPEdgeMapper
{
    /**
     * Constructor
     * @param PDO_DB $db Database connection
     */
    public function __construct($db);

    /**
     * Process footer edges and create relationships
     * @param array $footerData Parsed footer metadata
     * @param int $targetArtifactId Target artifact ID
     * @return int Number of edges created/updated
     */
    public function processFooterEdges($footerData, $targetArtifactId);

    /**
     * Create or update a single edge
     * @param array $edgeData Edge metadata
     * @param int $targetArtifactId Target artifact ID
     * @return int|false edge_id or false on failure
     */
    public function upsertEdge($edgeData, $targetArtifactId);

    /**
     * Find edges by source artifact
     * @param int $sourceArtifactId Source artifact ID
     * @return array Array of edge records
     */
    public function findEdgesBySource($sourceArtifactId);

    /**
     * Find edges by target artifact
     * @param int $targetArtifactId Target artifact ID
     * @return array Array of edge records
     */
    public function findEdgesByTarget($targetArtifactId);
}
```

**Dependencies**:
- PDO_DB (via DatabaseFactory)
- LUPO_TABLE_PREFIX constant
- FLIPArtifactRepository (for path-to-ID resolution)

**Error Handling**:
- Skips invalid edges (missing source/target)
- Logs warnings for unresolvable paths
- Continues processing remaining edges on errors

### FLIPBackfillService Class

**Location**: `app/Services/FLIPBackfillService.php`

**Responsibility**: One-time migration to populate FLIP artifacts from existing files

**Public Methods**:

```php
class FLIPBackfillService
{
    /**
     * Constructor
     * @param PDO_DB $db Database connection
     * @param FLIPScanner $scanner Scanner instance
     * @param FLIPArtifactRepository $repository Repository instance
     * @param FLIPEdgeMapper $edgeMapper Edge mapper instance
     */
    public function __construct($db, $scanner, $repository, $edgeMapper);

    /**
     * Backfill all configured directories
     * @return array Statistics (files_processed, artifacts_stored, errors)
     */
    public function backfillAll();

    /**
     * Backfill a single directory
     * @param string $dirPath Directory path relative to root
     * @return array Statistics for this directory
     */
    public function backfillDirectory($dirPath);

    /**
     * Get backfill progress
     * @return array Current progress statistics
     */
    public function getProgress();
}
```

**Dependencies**:
- FLIPScanner
- FLIPArtifactRepository
- FLIPEdgeMapper
- PDO_DB

**Error Handling**:
- Logs all errors with file paths
- Continues processing on individual file failures
- Returns comprehensive statistics

## Data Models

### lupo_flip_artifacts Table Schema

```sql
CREATE TABLE lupo_flip_artifacts (
    flip_artifact_id BIGINT NOT NULL PRIMARY KEY,
    file_path_from_root VARCHAR(500) NOT NULL,
    artifact_kind VARCHAR(50) DEFAULT NULL,
    channel_id BIGINT DEFAULT NULL,
    actor_id BIGINT DEFAULT NULL,
    agent_slug VARCHAR(255) DEFAULT NULL,
    agent_type VARCHAR(64) DEFAULT NULL,
    system_version VARCHAR(20) DEFAULT NULL,
    last_modified_ymd BIGINT DEFAULT NULL,
    x_forward_from_actor_id BIGINT DEFAULT NULL,
    x_forward_to_actor_id BIGINT DEFAULT NULL,
    x_lupo_forwarded VARCHAR(64) DEFAULT NULL,
    header_json TEXT DEFAULT NULL,
    footer_json TEXT DEFAULT NULL,
    file_hash VARCHAR(64) DEFAULT NULL,
    created_ymdhis BIGINT NOT NULL,
    updated_ymdhis BIGINT NOT NULL,
    is_deleted TINYINT DEFAULT 0,
    deleted_ymdhis BIGINT DEFAULT 0
);

-- Indexes for efficient querying
CREATE INDEX idx_flip_artifacts_path ON lupo_flip_artifacts(file_path_from_root);
CREATE INDEX idx_flip_artifacts_actor_date ON lupo_flip_artifacts(actor_id, last_modified_ymd);
CREATE INDEX idx_flip_artifacts_channel_date ON lupo_flip_artifacts(channel_id, last_modified_ymd);
CREATE INDEX idx_flip_artifacts_forward ON lupo_flip_artifacts(x_forward_from_actor_id, x_forward_to_actor_id);
CREATE INDEX idx_flip_artifacts_kind_date ON lupo_flip_artifacts(artifact_kind, last_modified_ymd);
CREATE INDEX idx_flip_artifacts_deleted ON lupo_flip_artifacts(is_deleted);
CREATE INDEX idx_flip_artifacts_version ON lupo_flip_artifacts(system_version);
```

### Column Descriptions

| Column | Type | Purpose |
|--------|------|---------|
| flip_artifact_id | BIGINT | Primary key, allocated via lupo_findpuka |
| file_path_from_root | VARCHAR(500) | Relative path from LUPOPEDIA_PATH |
| artifact_kind | VARCHAR(50) | Type: doctrine, status, directive, channel, etc. |
| channel_id | BIGINT | Associated channel (nullable) |
| actor_id | BIGINT | Creator actor (nullable) |
| agent_slug | VARCHAR(255) | Agent identifier (nullable) |
| agent_type | VARCHAR(64) | Agent type: IDE, runtime, etc. (nullable) |
| system_version | VARCHAR(20) | Lupopedia version (e.g., "4.0.37") |
| last_modified_ymd | BIGINT | Last modified date in YYYYMMDD format |
| x_forward_from_actor_id | BIGINT | Forwarding source actor (nullable) |
| x_forward_to_actor_id | BIGINT | Forwarding destination actor (nullable) |
| x_lupo_forwarded | VARCHAR(64) | Forwarding metadata (nullable) |
| header_json | TEXT | Complete YAML header as JSON |
| footer_json | TEXT | Complete YAML footer as JSON |
| file_hash | VARCHAR(64) | SHA-256 hash for integrity |
| created_ymdhis | BIGINT | Creation timestamp (YYYYMMDDHHIISS) |
| updated_ymdhis | BIGINT | Last update timestamp (YYYYMMDDHHIISS) |
| is_deleted | TINYINT | Soft delete flag (0 or 1) |
| deleted_ymdhis | BIGINT | Deletion timestamp (0 if not deleted) |

### FLIP Header Structure

FLIP headers are YAML blocks at the start of markdown files:

```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: "docs/doctrine/FLIP_V2_DOCTRINE.md"
artifact_kind: "doctrine"
channel_id: 42
actor_id: 10001
agent_slug: "wolfie"
agent_type: "IDE"
system_version: "4.0.37"
file.last_modified_system_version: "4.0.37"
file.last_modified_utc: "2026-02-14T15:30:45Z"
x_lupo_forwarded: "cascade->wolfie"
---
```

### FLIP Footer Structure

FLIP footers are YAML blocks at the end of markdown files:

```yaml
---
inbound_edges:
  - source: "docs/doctrine/DATABASE_DOCTRINE.md"
    relationship: "references"
    weight: 1.0
  - source: "docs/doctrine/TIMESTAMP_DOCTRINE.md"
    relationship: "depends_on"
    weight: 0.8
---
```

### Relationship to lupo_edges Table

Footer edges are mapped to the existing `lupo_edges` table:

```sql
-- Existing lupo_edges schema (simplified)
CREATE TABLE lupo_edges (
    edge_id BIGINT NOT NULL PRIMARY KEY,
    source_type VARCHAR(50),
    source_id BIGINT,
    target_type VARCHAR(50),
    target_id BIGINT,
    relationship_type VARCHAR(100),
    weight DECIMAL(5,2),
    created_ymdhis BIGINT,
    updated_ymdhis BIGINT,
    is_deleted TINYINT DEFAULT 0
);
```

Mapping:
- `source_type` = "flip_artifact"
- `source_id` = flip_artifact_id (resolved from footer edge source path)
- `target_type` = "flip_artifact"
- `target_id` = flip_artifact_id (current artifact)
- `relationship_type` = footer edge relationship field
- `weight` = footer edge weight field (default 1.0)


## YAML Parsing Strategy

Since Lupopedia cannot use external dependencies and must support PHP 5.3, we implement a minimal YAML parser for FLIP headers/footers.

### Parsing Approach

1. **Regex Extraction**: Use regex to extract YAML blocks between `---` markers
2. **Line-by-Line Parsing**: Parse simple key-value pairs and arrays
3. **Limited YAML Support**: Support only the subset needed for FLIP metadata
4. **Fallback to yaml_parse**: Use native `yaml_parse()` if available, fallback to custom parser

### Supported YAML Features

- Simple key-value pairs: `key: value`
- Quoted strings: `key: "value with spaces"`
- Integers: `key: 12345`
- Arrays: `key: [item1, item2]` or multi-line with `-` prefix
- Nested objects: Limited to one level for inbound_edges

### Not Supported

- Complex nested structures beyond two levels
- YAML anchors and aliases
- Multi-line strings with `|` or `>`
- Complex data types

### Parser Implementation

```php
class FLIPYAMLParser
{
    /**
     * Parse simple YAML content
     * @param string $yaml YAML content
     * @return array|null Parsed data or null on failure
     */
    public function parse($yaml);

    /**
     * Parse a single YAML line
     * @param string $line YAML line
     * @return array|null [key, value] or null
     */
    private function parseLine($line);

    /**
     * Parse array notation
     * @param string $value Array string
     * @return array Parsed array
     */
    private function parseArray($value);
}
```

## Implementation Details

### File Hash Computation

SHA-256 hashing for integrity verification:

```php
public function computeHash($content)
{
    return hash('sha256', $content);
}
```

Hash comparison determines if re-scanning is needed:

```php
public function needsUpdate($filePath, $newHash)
{
    $existing = $this->findByPath($filePath);
    if ($existing === null) {
        return true; // New file
    }
    return $existing['file_hash'] !== $newHash;
}
```

### Path Normalization

All file paths are normalized to forward slashes and stored relative to LUPOPEDIA_PATH:

```php
private function normalizePath($absolutePath)
{
    $repoRoot = rtrim(str_replace('\\', '/', LUPOPEDIA_PATH), '/');
    $normalized = str_replace('\\', '/', $absolutePath);
    
    if (strpos($normalized, $repoRoot) === 0) {
        $normalized = substr($normalized, strlen($repoRoot) + 1);
    }
    
    return ltrim($normalized, '/');
}
```

### Actor ID Validation

Actor IDs are validated against lupo_actors table:

```php
private function validateActorId($actorId)
{
    if ($actorId === null || $actorId === 0) {
        return null;
    }
    
    $table = $this->db->quoteIdentifier($this->prefix . 'actors');
    $row = $this->db->fetchRow(
        "SELECT actor_id FROM {$table} WHERE actor_id = :actor_id AND is_deleted = 0 LIMIT 1",
        array('actor_id' => $actorId)
    );
    
    if ($row === null) {
        $this->logWarning("Invalid actor_id: {$actorId}");
        return null;
    }
    
    return $actorId;
}
```

### Timestamp Handling

All timestamps use the timestamp_ymdhis class:

```php
$now = timestamp_ymdhis::now(); // Returns BIGINT in YYYYMMDDHHIISS format

// For last_modified_ymd, extract date portion
$ymd = (int) substr((string) $now, 0, 8); // YYYYMMDD
```

### Database Operations

All database operations use prepared statements with named placeholders:

```php
public function upsert($metadata)
{
    $table = $this->prefix . 'flip_artifacts';
    $existing = $this->findByPath($metadata['file_path_from_root']);
    
    if ($existing === null) {
        // Insert new artifact
        $flipArtifactId = lupo_findpuka($this->db, $table, 'flip_artifact_id', 1, null);
        $data = array(
            'flip_artifact_id' => $flipArtifactId,
            'file_path_from_root' => $metadata['file_path_from_root'],
            'artifact_kind' => $metadata['artifact_kind'],
            'channel_id' => $metadata['channel_id'],
            'actor_id' => $this->validateActorId($metadata['actor_id']),
            'agent_slug' => $metadata['agent_slug'],
            'agent_type' => $metadata['agent_type'],
            'system_version' => $metadata['system_version'],
            'last_modified_ymd' => $metadata['last_modified_ymd'],
            'x_forward_from_actor_id' => $metadata['x_forward_from_actor_id'],
            'x_forward_to_actor_id' => $metadata['x_forward_to_actor_id'],
            'x_lupo_forwarded' => $metadata['x_lupo_forwarded'],
            'header_json' => json_encode($metadata['header']),
            'footer_json' => json_encode($metadata['footer']),
            'file_hash' => $metadata['file_hash'],
            'created_ymdhis' => timestamp_ymdhis::now(),
            'updated_ymdhis' => timestamp_ymdhis::now(),
            'is_deleted' => 0,
            'deleted_ymdhis' => 0
        );
        $this->db->insert($table, $data);
        return $flipArtifactId;
    } else {
        // Update existing artifact
        $data = array(
            'artifact_kind' => $metadata['artifact_kind'],
            'channel_id' => $metadata['channel_id'],
            'actor_id' => $this->validateActorId($metadata['actor_id']),
            'agent_slug' => $metadata['agent_slug'],
            'agent_type' => $metadata['agent_type'],
            'system_version' => $metadata['system_version'],
            'last_modified_ymd' => $metadata['last_modified_ymd'],
            'x_forward_from_actor_id' => $metadata['x_forward_from_actor_id'],
            'x_forward_to_actor_id' => $metadata['x_forward_to_actor_id'],
            'x_lupo_forwarded' => $metadata['x_lupo_forwarded'],
            'header_json' => json_encode($metadata['header']),
            'footer_json' => json_encode($metadata['footer']),
            'file_hash' => $metadata['file_hash'],
            'updated_ymdhis' => timestamp_ymdhis::now()
        );
        $this->db->update(
            $table,
            $data,
            'flip_artifact_id = :id',
            array('id' => $existing['flip_artifact_id'])
        );
        return $existing['flip_artifact_id'];
    }
}
```

### Edge Mapping Implementation

Footer edges are processed and stored in lupo_edges:

```php
public function processFooterEdges($footerData, $targetArtifactId)
{
    if (!isset($footerData['inbound_edges']) || !is_array($footerData['inbound_edges'])) {
        return 0;
    }
    
    $edgesCreated = 0;
    foreach ($footerData['inbound_edges'] as $edge) {
        if (!isset($edge['source'])) {
            continue;
        }
        
        // Resolve source path to flip_artifact_id
        $sourceArtifact = $this->repository->findByPath($edge['source']);
        if ($sourceArtifact === null) {
            $this->logWarning("Cannot resolve source path: {$edge['source']}");
            continue;
        }
        
        $edgeData = array(
            'source_type' => 'flip_artifact',
            'source_id' => $sourceArtifact['flip_artifact_id'],
            'target_type' => 'flip_artifact',
            'target_id' => $targetArtifactId,
            'relationship_type' => isset($edge['relationship']) ? $edge['relationship'] : 'references',
            'weight' => isset($edge['weight']) ? $edge['weight'] : 1.0
        );
        
        if ($this->upsertEdge($edgeData) !== false) {
            $edgesCreated++;
        }
    }
    
    return $edgesCreated;
}
```

### Backfill Process

The backfill service scans configured directories:

```php
public function backfillAll()
{
    $directories = array(
        'channels',
        'docs/directives',
        'docs/status',
        'docs/brainstorm',
        'docs/doctrine',
        'docs/versions'
    );
    
    $totalStats = array(
        'files_processed' => 0,
        'artifacts_stored' => 0,
        'edges_created' => 0,
        'errors' => array()
    );
    
    foreach ($directories as $dir) {
        $dirPath = LUPOPEDIA_PATH . '/' . $dir;
        if (!is_dir($dirPath)) {
            continue;
        }
        
        $stats = $this->backfillDirectory($dir);
        $totalStats['files_processed'] += $stats['files_processed'];
        $totalStats['artifacts_stored'] += $stats['artifacts_stored'];
        $totalStats['edges_created'] += $stats['edges_created'];
        $totalStats['errors'] = array_merge($totalStats['errors'], $stats['errors']);
    }
    
    return $totalStats;
}
```

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*


### Property Reflection

After analyzing all acceptance criteria, I identified the following redundancies:

1. **Header and Footer Extraction**: Properties 4.1-4.3 and 5.1-5.2 can be combined into comprehensive YAML block extraction properties
2. **JSON Conversion**: Properties 4.4 and 5.3 are identical operations on different inputs - combine into one
3. **Field Extraction**: Properties 4.6-4.13 can be combined into a single property about field extraction
4. **Hash Operations**: Properties 6.1-6.2 are part of the same operation - combine
5. **Hash-Based Updates**: Properties 6.4-6.5 are complementary conditions - combine into one property
6. **Timestamp Format**: Properties 7.2, 7.3, 10.5, and 16.2 all test YMDHIS format - combine
7. **Actor Validation**: Properties 17.1-17.3 can be combined into comprehensive actor validation
8. **Path Normalization**: Properties 18.1-18.3 are all part of path normalization - combine
9. **Backward Compatibility**: Properties 20.1-20.3 test the same behavior with different inputs - combine
10. **Directory Scanning**: Properties 8.1 and 9.1-9.7 test the same recursive scanning behavior - combine

After reflection, 40+ initial properties reduce to 20 unique, non-redundant properties.

### Property 1: YAML Block Extraction

*For any* markdown file containing a YAML block delimited by `---` markers (at start for headers, after content for footers), the scanner should successfully extract the YAML content between the delimiters.

**Validates: Requirements 4.1, 4.2, 4.3, 5.1, 5.2**

### Property 2: YAML to JSON Conversion

*For any* valid YAML content extracted from headers or footers, converting to JSON should produce valid JSON that can be round-tripped back to equivalent data structures.

**Validates: Requirements 4.4, 5.3**

### Property 3: Header Field Extraction

*For any* YAML header containing standard FLIP fields (file_path_from_root, actor_id, agent_slug, agent_type, artifact_kind, channel_id, system_version, x_lupo_forwarded), all present fields should be correctly extracted and stored in the parsed metadata.

**Validates: Requirements 4.6, 4.7, 4.8, 4.9, 4.10, 4.11, 4.12, 4.13**


### Property 4: Footer Edge Extraction

*For any* YAML footer containing an `inbound_edges` array with edge objects, all edges with valid source paths should be extracted and their metadata (source, relationship, weight) should be preserved.

**Validates: Requirements 5.4, 5.5, 10.1, 10.3, 10.4**

### Property 5: Parse Error Recovery

*For any* file with malformed YAML in headers or footers, the scanner should log the error, skip that file's metadata, and continue processing remaining files without halting.

**Validates: Requirements 4.5, 5.6, 8.6**

### Property 6: File Hash Computation

*For any* file content, computing the SHA-256 hash should produce a 64-character hexadecimal string, and computing the hash twice on the same content should produce identical results.

**Validates: Requirements 6.1, 6.2**

### Property 7: Hash-Based Update Detection

*For any* file that has been previously scanned, re-scanning should compare the new hash with the stored hash, and if they differ, the artifact record should be updated; if they match, no database update should occur.

**Validates: Requirements 6.3, 6.4, 6.5**

### Property 8: Artifact Insertion

*For any* successfully parsed FLIP artifact that doesn't exist in the database, inserting it should create a new record with a unique flip_artifact_id and both created_ymdhis and updated_ymdhis set to the current timestamp.

**Validates: Requirements 7.1, 7.2, 7.3**

### Property 9: Artifact Upsert Behavior

*For any* FLIP artifact identified by file_path_from_root, if a record already exists, the upsert operation should update the existing record rather than creating a duplicate, and should update the updated_ymdhis timestamp.

**Validates: Requirements 7.4**


### Property 10: Recursive Directory Scanning

*For any* directory containing markdown files in nested subdirectories, the backfill process should recursively discover and process all markdown files regardless of nesting depth.

**Validates: Requirements 8.1, 9.1, 9.2, 9.3, 9.4, 9.5, 9.6, 9.7**

### Property 11: Metadata Extraction and Storage

*For any* markdown file containing valid FLIP metadata, the backfill process should successfully extract the metadata and store it in the database with all fields correctly mapped.

**Validates: Requirements 8.2**

### Property 12: Edge Creation from Footer

*For any* FLIP footer containing inbound_edges, the edge mapper should create or update a corresponding record in the lupo_edges table for each edge, with source_type and target_type set to "flip_artifact" and source_id/target_id resolved from file paths.

**Validates: Requirements 10.2**

### Property 13: YMDHIS Timestamp Format

*For any* timestamp stored in created_ymdhis, updated_ymdhis, or deleted_ymdhis columns, the value should be a valid BIGINT in YYYYMMDDHHIISS format representing a valid UTC date and time.

**Validates: Requirements 7.2, 7.3, 10.5, 16.2**

### Property 14: Actor ID Validation

*For any* actor_id in FLIP metadata, the scanner should validate that the actor exists in lupo_actors table (checking lupo_agents for IDs 0-9999, lupo_auth_users for IDs 10000+), and if validation fails, should set actor_id to NULL and continue processing.

**Validates: Requirements 17.1, 17.2, 17.3, 17.4, 17.5**

### Property 15: Path Normalization

*For any* absolute file path, the scanner should normalize it to a relative path from LUPOPEDIA_PATH with forward slashes and no leading slash, ensuring consistent path representation across platforms.

**Validates: Requirements 18.1, 18.2, 18.3**


### Property 16: Path Length Validation

*For any* file path, if the normalized path exceeds 500 characters, the scanner should log an error and skip processing that file without halting the overall scan.

**Validates: Requirements 18.4, 18.5**

### Property 17: Backward Compatibility

*For any* markdown file with FLIP v1 headers, FLIP v2 headers, or no FLIP headers at all, the scanner should process the file without errors, extracting metadata when present and gracefully skipping when absent.

**Validates: Requirements 20.1, 20.2, 20.3, 20.4**

### Property 18: Non-Destructive Scanning

*For any* file scanned by the FLIP scanner, the file content should remain completely unchanged after scanning - the scanner operates in read-only mode and never modifies source files.

**Validates: Requirements 20.5**

### Property 19: Edge Upsert Behavior

*For any* edge relationship between two FLIP artifacts, if an edge with the same source_id, target_id, and relationship_type already exists, the edge mapper should update the updated_ymdhis timestamp rather than creating a duplicate edge record.

**Validates: Requirements 10.7**

### Property 20: Soft Delete Preservation

*For any* artifact or edge marked as deleted (is_deleted = 1), queries filtering by is_deleted = 0 should exclude these records, and the deleted_ymdhis timestamp should reflect when the deletion occurred.

**Validates: Requirements 10.6**

## Error Handling

### Error Categories

1. **File System Errors**: Unreadable files, missing directories, permission issues
2. **Parse Errors**: Malformed YAML, invalid syntax, encoding issues
3. **Validation Errors**: Invalid actor IDs, path length violations, missing required fields
4. **Database Errors**: Connection failures, constraint violations, transaction rollbacks

### Error Handling Strategy


**Graceful Degradation**: The scanner continues processing after individual file errors

**Comprehensive Logging**: All errors logged with context (file path, error type, error message)

**Validation Fallbacks**: Invalid data (e.g., actor_id) set to NULL rather than causing failure

**Transaction Safety**: Database operations use transactions where appropriate

**No Silent Failures**: All errors logged, even if processing continues

### Error Logging Format

```php
// File system error
error_log("FLIPScanner: Cannot read file: {$filePath} - {$errorMessage}");

// Parse error
error_log("FLIPScanner: YAML parse error in {$filePath}: {$yamlError}");

// Validation error
error_log("FLIPScanner: Invalid actor_id {$actorId} in {$filePath} - setting to NULL");

// Database error
error_log("FLIPArtifactRepository: Insert failed for {$filePath}: {$dbError}");
```

### Recovery Mechanisms

- **File Errors**: Skip file, continue with next
- **Parse Errors**: Skip metadata extraction, continue with next file
- **Validation Errors**: Use NULL/default values, continue processing
- **Database Errors**: Log error, return false, allow caller to handle

## Testing Strategy

### Dual Testing Approach

FLIP v2 requires both unit tests and property-based tests for comprehensive coverage:

**Unit Tests**: Verify specific examples, edge cases, and error conditions
- Test specific YAML header formats
- Test specific file path normalization cases
- Test database connection error handling
- Test actor validation with known actor IDs

**Property Tests**: Verify universal properties across all inputs
- Generate random YAML headers and verify extraction
- Generate random file paths and verify normalization
- Generate random file content and verify hash consistency
- Generate random directory structures and verify recursive scanning

### Property-Based Testing Configuration

**Library**: Use fast-check for JavaScript/TypeScript or Hypothesis for Python test harness

**Iterations**: Minimum 100 iterations per property test

**Tagging**: Each property test tagged with design document reference

Tag format: `Feature: flip-v2-implementation, Property {number}: {property_text}`


### Unit Test Coverage

**FLIPScanner Tests** (`tests/unit/flip_scanner.php`):
- Test YAML header extraction with various formats
- Test YAML footer extraction
- Test hash computation consistency
- Test path normalization edge cases
- Test error handling for malformed YAML

**FLIPArtifactRepository Tests** (`tests/unit/flip_artifact_repository.php`):
- Test insert new artifact
- Test update existing artifact (upsert)
- Test find by path
- Test find by actor
- Test find by channel
- Test soft delete

**FLIPEdgeMapper Tests** (`tests/unit/flip_edge_mapper.php`):
- Test edge creation from footer
- Test edge upsert behavior
- Test source path resolution
- Test invalid source handling

**FLIPBackfillService Tests** (`tests/unit/flip_backfill_service.php`):
- Test directory scanning
- Test file filtering by extension
- Test statistics collection
- Test error recovery

### Property Test Coverage

**Property 1-2: YAML Parsing** (`tests/property/flip_yaml_parsing.php`):
- Generate random valid YAML headers
- Generate random valid YAML footers
- Verify extraction and JSON conversion

**Property 6-7: Hash Operations** (`tests/property/flip_hash_operations.php`):
- Generate random file content
- Verify hash consistency
- Verify hash-based update detection

**Property 13: Timestamp Format** (`tests/property/flip_timestamp_format.php`):
- Generate random timestamps
- Verify YMDHIS format validity
- Verify timestamp comparison operations

**Property 15: Path Normalization** (`tests/property/flip_path_normalization.php`):
- Generate random absolute paths
- Generate paths with mixed separators
- Verify normalization consistency

**Property 18: Non-Destructive Scanning** (`tests/property/flip_non_destructive.php`):
- Generate random file content
- Scan files
- Verify content unchanged

### Integration Tests

**End-to-End Backfill** (`tests/integration/flip_backfill_integration.php`):
- Create test directory structure
- Create test markdown files with FLIP metadata
- Run backfill process
- Verify database records
- Verify edge relationships

**Database Compatibility** (`tests/integration/flip_database_compatibility.php`):
- Run migration on MySQL
- Run migration on PostgreSQL
- Verify schema compatibility
- Verify query performance


### Test Execution

```bash
# Run all FLIP v2 unit tests
php tests/unit/flip_scanner.php
php tests/unit/flip_artifact_repository.php
php tests/unit/flip_edge_mapper.php
php tests/unit/flip_backfill_service.php

# Run all FLIP v2 property tests (100+ iterations each)
php tests/property/flip_yaml_parsing.php
php tests/property/flip_hash_operations.php
php tests/property/flip_timestamp_format.php
php tests/property/flip_path_normalization.php
php tests/property/flip_non_destructive.php

# Run integration tests
php tests/integration/flip_backfill_integration.php
php tests/integration/flip_database_compatibility.php

# Run complete test suite
sh scripts/run_tests.sh .
```

## Implementation Phases

### Phase 1: Database Schema (Version 4.0.37)

1. Create migration script `database/migrations/upgrade_flip_v2.sql`
2. Define lupo_flip_artifacts table with all columns
3. Create all required indexes
4. Test migration on MySQL, MariaDB, PostgreSQL
5. Generate TOON file `docs/toons/lupo_flip_artifacts.toon.json`

**Deliverables**:
- Migration SQL file
- TOON file
- Migration test results

### Phase 2: Core Scanner Implementation

1. Implement FLIPYAMLParser class
2. Implement FLIPScanner class
3. Write unit tests for scanner
4. Write property tests for YAML parsing
5. Test with sample FLIP files

**Deliverables**:
- FLIPYAMLParser.php
- FLIPScanner.php
- Unit tests
- Property tests

### Phase 3: Repository and Persistence

1. Implement FLIPArtifactRepository class
2. Implement database operations (insert, update, find)
3. Write unit tests for repository
4. Test upsert behavior
5. Test query performance

**Deliverables**:
- FLIPArtifactRepository.php
- Repository unit tests
- Performance benchmarks

### Phase 4: Edge Mapping

1. Implement FLIPEdgeMapper class
2. Implement footer edge processing
3. Implement lupo_edges integration
4. Write unit tests for edge mapper
5. Test edge relationship creation

**Deliverables**:
- FLIPEdgeMapper.php
- Edge mapper unit tests
- Edge relationship verification

### Phase 5: Backfill Service

1. Implement FLIPBackfillService class
2. Implement directory scanning
3. Implement progress tracking
4. Write integration tests
5. Test on actual codebase directories

**Deliverables**:
- FLIPBackfillService.php
- Backfill integration tests
- Backfill execution script

### Phase 6: Documentation and Deployment

1. Write FLIP v2 doctrine document
2. Update CHANGELOG for version 4.0.37
3. Write status report
4. Run backfill on production data
5. Verify all artifacts stored correctly

**Deliverables**:
- docs/doctrine/FLIP_V2_DOCTRINE.md
- CHANGELOG.md update
- docs/status/kiro_flip_v2_implementation_4_0_37.md
- Backfill results report

## Migration Path

### For New Installations

1. Run `database/migrations/upgrade_flip_v2.sql`
2. Run backfill: `php scripts/flip_v2_backfill.php`
3. Verify: `php scripts/flip_v2_verify.php`

### For Existing Installations

1. Backup database
2. Run migration script
3. Run backfill service
4. Verify artifact count matches file count
5. Verify edge relationships created

### Rollback Plan

If issues occur:

1. Drop lupo_flip_artifacts table
2. Remove FLIP v2 related edges from lupo_edges (source_type = 'flip_artifact')
3. Restore from backup if needed

FLIP v1 functionality remains unaffected - files continue to work as before.

## Performance Considerations

### Scanning Performance

- **Batch Processing**: Process files in batches of 100
- **Hash Caching**: Skip re-processing if hash matches
- **Incremental Updates**: Only update changed files

### Database Performance

- **Indexed Queries**: All common queries use indexes
- **Prepared Statements**: Reuse prepared statements in loops
- **Transaction Batching**: Batch inserts in transactions of 50

### Memory Management

- **Stream Processing**: Read files in chunks for large files
- **Result Pagination**: Limit query results to prevent memory exhaustion
- **Garbage Collection**: Unset large variables after use

### Expected Performance

- **Scanning**: ~100 files/second on SSD
- **Database Insert**: ~500 records/second
- **Backfill**: ~10,000 files in ~2 minutes

## Security Considerations

### SQL Injection Prevention

- All queries use prepared statements with named placeholders
- No string concatenation in SQL
- DatabaseFactory enforces PDO_DB usage

### Path Traversal Prevention

- Validate all paths are within LUPOPEDIA_PATH
- Reject paths containing `..`
- Normalize all paths before storage

### Actor Validation

- Validate all actor_id values against lupo_actors
- Set invalid actor_id to NULL
- Log validation failures

### File System Access

- Read-only access to files
- No file modifications
- No file deletions
- Respect file permissions

## Maintenance and Monitoring

### Monitoring Points

1. **Backfill Progress**: Track files processed, artifacts stored, errors
2. **Database Growth**: Monitor lupo_flip_artifacts table size
3. **Query Performance**: Monitor slow queries on artifact lookups
4. **Error Rates**: Track parse errors, validation errors, database errors

### Maintenance Tasks

1. **Periodic Re-scan**: Re-run backfill to catch new/modified files
2. **Orphan Cleanup**: Remove artifacts for deleted files
3. **Index Optimization**: Rebuild indexes if performance degrades
4. **Log Rotation**: Rotate error logs to prevent disk fill

### Health Checks

```php
// Check artifact count vs file count
$artifactCount = $repository->countAll();
$fileCount = countMarkdownFiles(LUPOPEDIA_PATH);
$discrepancy = abs($fileCount - $artifactCount);

if ($discrepancy > 10) {
    error_log("FLIP v2 Health Check: Artifact count discrepancy: {$discrepancy}");
}
```

## Conclusion

FLIP v2 provides a robust foundation for metadata persistence and semantic relationship tracking in Lupopedia. The design follows all architectural doctrines, maintains backward compatibility, and enables efficient querying of FLIP metadata without filesystem scanning.

The implementation is incremental, testable, and maintainable, with clear error handling and comprehensive test coverage through both unit tests and property-based tests.