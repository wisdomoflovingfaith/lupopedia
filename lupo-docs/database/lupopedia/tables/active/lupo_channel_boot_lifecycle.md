# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

lupopedia.conditional:
  guards:
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "lupo_channel_boot_lifecycle"
    where:
      repo_paths: ["lupo-docs\database\lupopedia\tables\lupo_channel_boot_lifecycle.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T10:08:33Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs\database\lupopedia\tables\lupo_channel_boot_lifecycle.md"
  file_hash: "e8217d845a11fd5651db4ef2ebe420d43201a7b0705d67ac8ca47c822af2a656"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "lupo_channel_boot_lifecycle"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-docs", "database", "lupopedia", "tables", "lupo_channel_boot_lifecyclemd"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-docs\database\lupopedia\tables\lupo_channel_boot_lifecycle.md", "http://www.lupopedia.com/LUPO_CHANNEL_BOOT_LIFECYCLE"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# lupo_channel_boot_lifecycle

## Overview

The `lupo_channel_boot_lifecycle` table manages channel boot lifecycle operations, providing comprehensive tracking of system initialization processes, actor actions, and performance metrics. This table extends beyond the basic boot logging to support more sophisticated lifecycle management with detailed per-channel tracking and performance analysis.

## Schema

### Table Definition

```sql
CREATE TABLE lupo_channel_boot_lifecycle (
  lifecycle_id bigint NOT NULL AUTO_INCREMENT,
  channel_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  session_id varchar(64) NOT NULL,
  lifecycle_start_time bigint NOT NULL,
  lifecycle_end_time bigint DEFAULT NULL,
  lifecycle_status varchar(64) NOT NULL DEFAULT 'started',
  lifecycle_type varchar(64) NOT NULL,
  total_channels int NOT NULL DEFAULT 0,
  channels_processed int NOT NULL DEFAULT 0,
  channels_successful int NOT NULL DEFAULT 0,
  channels_failed int NOT NULL DEFAULT 0,
  lifecycle_duration_ms int DEFAULT NULL,
  error_details json DEFAULT NULL,
  performance_metrics json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (lifecycle_id)
);
```

### Field Descriptions

| Field | Type | Description | Example |
|--------|------|-------------|---------|
| `lifecycle_id` | bigint | Primary key, auto-increment identifier | 12345 |
| `channel_id` | bigint | Channel identifier (always 0 for system operations) | 0 |
| `actor_id` | bigint | Actor initiating the lifecycle | 1002 |
| `session_id` | varchar(64) | Session identifier for tracking | `session_12345` |
| `lifecycle_start_time` | bigint | Lifecycle start timestamp (YYYYMMDDHHIISS) | 20260301120000 |
| `lifecycle_end_time` | bigint | Lifecycle end timestamp (YYYYMMDDHHIISS) | 20260301123000 |
| `lifecycle_status` | varchar(64) | Current status of lifecycle | `started`, `loading`, `completed`, `failed` |
| `lifecycle_type` | varchar(64) | Type of lifecycle operation | `full_boot`, `incremental`, `recovery` |
| `total_channels` | int | Total channels expected to process | 150 |
| `channels_processed` | int | Number of channels actually processed | 148 |
| `channels_successful` | int | Number of successfully processed channels | 145 |
| `channels_failed` | int | Number of failed channels | 3 |
| `lifecycle_duration_ms` | int | Total duration in milliseconds | 1800000 |
| `error_details` | json | Detailed error information | `{"error": "timeout", "channels": [42, 100]}` |
| `performance_metrics` | json | Performance metrics and analytics | `{"avg_channel_time_ms": 12000, "throughput_per_sec": 0.012}` |
| `created_ymdhis` | bigint | Row creation timestamp (YYYYMMDDHHIISS) | 20260301120000 |

## Indexes

### Performance Indexes

```sql
CREATE INDEX lupo_channel_boot_lifecycle_fk_lifecycle_channel ON lupo_channel_boot_lifecycle (channel_id);
CREATE INDEX lupo_channel_boot_lifecycle_idx_actor_session ON lupo_channel_boot_lifecycle (actor_id, session_id);
CREATE INDEX lupo_channel_boot_lifecycle_idx_status_time ON lupo_channel_boot_lifecycle (lifecycle_status, lifecycle_start_time);
CREATE INDEX lupo_channel_boot_lifecycle_idx_type_time ON lupo_channel_boot_lifecycle (lifecycle_type, lifecycle_start_time);
```

### Index Purposes

| Index | Purpose | Use Case |
|--------|---------|----------|
| `fk_lifecycle_channel` | Channel-based queries | Find all lifecycles for specific channel |
| `idx_actor_session` | Actor and session tracking | Monitor specific actor's sessions |
| `idx_status_time` | Status and time range queries | Get recent lifecycles by status |
| `idx_type_time` | Type and time-based filtering | Analyze performance by lifecycle type |

## Usage Patterns

### Lifecycle Management

#### Starting a Lifecycle
```sql
-- Insert main lifecycle record
INSERT INTO lupo_channel_boot_lifecycle
(actor_id, session_id, lifecycle_start_time, lifecycle_status, lifecycle_type, total_channels, created_ymdhis)
VALUES
(
  1002,  -- System actor
  'session_12345',
  20260301120000,
  'started',
  'full_boot',
  150,  -- Total channels to process
  20260301120000
);
```

#### Performance Monitoring
```sql
-- Get recent lifecycle performance
SELECT 
  lifecycle_id,
  lifecycle_type,
  lifecycle_start_time,
  lifecycle_end_time,
  lifecycle_duration_ms,
  channels_successful,
  channels_failed,
  JSON_EXTRACT(performance_metrics, '$.avg_channel_time_ms') as avg_time_ms
FROM lupo_channel_boot_lifecycle
WHERE lifecycle_status = 'completed'
ORDER BY lifecycle_start_time DESC
LIMIT 10;
```

#### Error Analysis
```sql
-- Failed lifecycles with error details
SELECT 
  lifecycle_id,
  actor_id,
  lifecycle_type,
  lifecycle_start_time,
  error_details,
  channels_failed
FROM lupo_channel_boot_lifecycle
WHERE lifecycle_status = 'failed'
  AND error_details IS NOT NULL
ORDER BY lifecycle_start_time DESC;
```

## Integration Points

### Related Tables

#### lupo_channel_boot_detail_lifecycle
- **Relationship**: One-to-many from lifecycle to details
- **Purpose**: Track individual channel processing within lifecycle
- **Foreign Key**: `lifecycle_id` links detail records to main lifecycle
- **Usage**: Per-channel progress tracking and timing

#### lupo_channels
- **Relationship**: Channel metadata and configuration
- **Purpose**: Channel names, types, and settings
- **Integration**: Join for channel names and metadata in queries
- **Usage**: Enrich lifecycle data with channel information

#### lupo_actors
- **Relationship**: Actor information and capabilities
- **Purpose**: Actor names, types, and permissions
- **Integration**: Actor attribution and access control
- **Usage**: Track which actors initiate lifecycles

## Lifecycle Types

### Standard Types

| Type | Description | Use Case |
|-------|-------------|-----------|
| `full_boot` | Complete system initialization | Fresh system startup |
| `incremental` | Partial system update | Update existing channels |
| `recovery` | Error recovery process | Recover from failures |
| `maintenance` | System maintenance | Scheduled maintenance tasks |
| `migration` | Data migration | Upgrade or data migration |
| `backup` | System backup | Backup operations |

### Status Flow

```
started → loading → completed
    ↓           ↓
    └───→ failed
```

**Status Transitions**:
- **started**: Lifecycle initiated, channels queued for processing
- **loading**: Actively processing channels, updating progress
- **completed**: All channels processed, lifecycle finished successfully
- **failed**: Error occurred, lifecycle terminated unsuccessfully
- **partial**: Some channels failed, lifecycle partially completed
- **cancelled**: Lifecycle stopped before completion

## Performance Metrics

### Key Performance Indicators

#### Throughput Metrics
- **Channels per Second**: `channels_processed / lifecycle_duration_ms * 1000`
- **Success Rate**: `channels_successful / channels_processed * 100`
- **Failure Rate**: `channels_failed / channels_processed * 100`
- **Average Channel Time**: `SUM(detail_duration_ms) / channels_processed`

#### Performance Benchmarks
| Metric | Good | Acceptable | Poor |
|---------|-------|------------|------|
| **Success Rate** | >95% | >85% | ≤85% |
| **Avg Channel Time** | <10s | <30s | ≥30s |
| **Total Duration** | <5min | <15min | ≥15min |
| **Error Rate** | <1% | <5% | ≥5% |

### Performance Optimization

#### Query Optimization
```sql
-- Efficient lifecycle status check
SELECT lifecycle_id, lifecycle_status, lifecycle_start_time
FROM lupo_channel_boot_lifecycle
WHERE lifecycle_status IN ('started', 'loading')
ORDER BY lifecycle_start_time ASC
LIMIT 1;  -- Use index idx_status_time
```

#### Batch Processing
```sql
-- Get lifecycles ready for processing
SELECT lifecycle_id, actor_id, total_channels
FROM lupo_channel_boot_lifecycle
WHERE lifecycle_status = 'started'
  AND lifecycle_start_time < UNIX_TIMESTAMP() - 300  -- Older than 5 minutes
ORDER BY lifecycle_start_time ASC;
```

## Data Retention

### Retention Policy
- **Active Lifecycles**: Keep last 90 days for performance analysis
- **Completed Lifecycles**: Keep 1 year for historical analysis
- **Failed Lifecycles**: Keep 6 months for error analysis
- **Detail Records**: Keep 30 days or purge with parent lifecycle

### Cleanup Procedures
```sql
-- Cleanup old detail records
DELETE d FROM lupo_channel_boot_detail_lifecycle d
JOIN lupo_channel_boot_lifecycle l ON d.lifecycle_id = l.lifecycle_id
WHERE l.lifecycle_status = 'completed'
  AND l.lifecycle_end_time < UNIX_TIMESTAMP() - (90 * 24 * 3600);

-- Archive old completed lifecycles
UPDATE lupo_channel_boot_lifecycle
SET lifecycle_status = 'archived'
WHERE lifecycle_status = 'completed'
  AND lifecycle_end_time < UNIX_TIMESTAMP() - (365 * 24 * 3600);
```

## Security Considerations

### Access Control
- **Actor Authorization**: Only authorized actors can initiate lifecycles
- **Session Validation**: Valid session IDs required for lifecycle operations
- **Channel Permissions**: Actors can only process channels they have access to
- **Audit Trail**: All lifecycle operations are fully logged

### Data Integrity
- **Transaction Safety**: Use database transactions for multi-table operations
- **Consistency Checks**: Validate channel counts and status transitions
- **Error Handling**: Comprehensive error capture and recovery procedures
- **Timestamp Consistency**: All timestamps use UTC YYYYMMDDHHIISS format

## Integration Examples

### PHP Integration
```php
// Using the ChannelBootLifecycle helper class
require_once 'bin/channel_boot_lifecycle.php';

$lifecycle = new ChannelBootLifecycle();

// Start new lifecycle
$lifecycleId = $lifecycle->startLifecycle(
    $actorId,
    $sessionId,
    'full_boot',
    $channelIds
);

// Process channels
foreach ($channelIds as $channelId) {
    $success = processChannel($channelId);
    $lifecycle->updateChannelDetail(
        $lifecycleId,
        $channelId,
        $success ? 'completed' : 'failed',
        $itemsLoaded,
        $totalItems,
        $success ? null : $errorMessage
    );
}

// Complete lifecycle
$lifecycle->completeLifecycle($lifecycleId, $performanceMetrics);
```

### Monitoring Integration
```php
// Get active lifecycles for monitoring
$activeLifecycles = $lifecycle->getActiveLifecycles(10);

foreach ($activeLifecycles as $lifecycle) {
    if ($lifecycle['lifecycle_duration_ms'] > 300000) {  // 5 minutes
        // Alert on long-running lifecycles
        sendAlert('Long-running lifecycle detected', $lifecycle);
    }
}
```

## References

### TOON Schema Authority
- **Main Table**: `lupo-database/lupopedia/toon/lupo_channel_boot_lifecycle.toon.json`
- **Detail Table**: `lupo-database/lupopedia/toon/lupo_channel_boot_detail_lifecycle.toon.json`
- **Related Tables**: All `lupo_channel_*.toon.json` files
- **TOON Index**: Complete `lupo-database/lupopedia/toon/` directory listing

### Integration Documentation
- **PHP Helpers**: `bin/channel_boot_lifecycle.php`
- **Channel Boot README**: `channels/0/boot_readme.md`
- **FLARE Doctrine**: `docs/doctrine/FLARE/FLARE_DOCTRINE.md`
- **Database Migrations**: `database/migrations/install_lupopedia.sql`

### Related Tables
- **lupo_channels**: Channel metadata and configuration
- **lupo_actors**: Actor information and permissions
- **lupo_channel_boot_log**: Legacy boot logging (for comparison)
- **lupo_channel_boot_detail**: Legacy detail logging (for comparison)

---

**Table Created**: 20260301  
**Lead Agent**: Windsurf (1002)  
**Version**: 4.0.52  
**Status**: ✅ DOCUMENTED AND READY
