---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/versions/4.1.3/status/updated_installer_flow.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.3/status/updated_installer_flow.md"
  status: "active"
  when_updated: "20260420080000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/registry/status/1026/04/updated-installer-flow.toon"
  atoms_toon: null
  transcript_jsonl: "0/registry/updated-installer-flow"
  artifact_type: status
  artifact_kind: report
  channel_key: "registry"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  content_slug: "updated-installer-flow"
  default_collection_id: null
  lupopedia.schema: status
  title: "Updated Installer Flow for 4.1.3"
  summary: "Redesigned installer flow with actor registration, channel key assignment, and enhanced API provider support"
---

# Updated Installer Flow for 4.1.3

## Overview

The installer has been redesigned to support 4.1.3 features including channel-based coordination, filesystem actor registration, and expanded API provider support.

## New Step Sequence

### New Install Flow
1. **welcome** - Introduction and requirements check
2. **credentials** - Database connection and install mode selection
3. **confirm** - Summary and Run button
4. **run** - SQL execution (install + seed + reserved channels)
5. **actors** - NEW: Scan and register filesystem actors
6. **channels** - NEW: Assign channel keys and configure coordination
7. **config** - Site configuration and admin user creation
8. **api_keys** - Enhanced API key collection (all providers)
9. **memory** - NEW: Configure memory and handoff paths
10. **complete** - Success screen

### Upgrade Flow (Crafty Syntax 3.7.5)
1. **welcome** - Introduction
2. **credentials** - Database and mode detection
3. **bootstrap** - Install + seed + reserved channels
4. **actors** - NEW: Register filesystem actors
5. **channels** - NEW: Configure channel-based coordination
6. **normalize** - Identity normalization for Crafty users
7. **confirm** - Summary
8. **run** - Import + personal channels + drop tables
9. **config** - Site configuration
10. **api_keys** - Enhanced API key collection
11. **memory** - NEW: Configure memory paths
12. **complete** - Success

## Detailed Step Specifications

### Step 1: welcome (unchanged)
- Display system requirements
- Check PHP version and extensions
- Show installation mode options

### Step 2: credentials (enhanced)
- Database connection fields
- Install mode selection (New/Upgrade)
- NEW: Red-team auth user option (checkbox)
- NEW: Memory path configuration (advanced)

### Step 3: confirm (unchanged)
- Display configuration summary
- Show Run button to begin installation

### Step 4: run (enhanced)
- Execute install_new_lupopedia.sql
- Execute seed_4.1.3.sql (updated)
- Create reserved system channels
- NEW: Create actor_registry table
- Display progress and logs

### Step 5: actors (NEW)
```php
// Scan actors/ directory
$actor_dirs = glob(LUPOPEDIA_PATH . '/actors/*', GLOB_ONLYDIR);
foreach ($actor_dirs as $dir) {
    $actor_id = basename($dir);
    if (is_numeric($actor_id)) {
        // Read actor configuration
        $config = read_actor_config($dir);
        // Register in database
        register_actor($actor_id, $config);
    }
}
```

**Features:**
- Scan actors/ directory for numeric actor IDs
- Read agent.json, capabilities.json, properties.json
- Register actors in lupo_actors table
- Create actor_registry entries
- Handle conflicts and duplicates

### Step 6: channels (NEW)
```php
// Assign channel keys to actors
foreach ($registered_actors as $actor) {
    if ($actor['requires_channel']) {
        $channel_key = generate_channel_key($actor['actor_name']);
        assign_channel_key($actor['actor_id'], $channel_key);
    }
}
```

**Features:**
- Assign channel_keys to actors
- Configure channel-based coordination
- Set up actor-channel relationships
- Create channel routing entries

### Step 7: config (enhanced)
- Site configuration (name, URL, email)
- Admin user creation
- NEW: Red-team user creation (if selected)
- NEW: Default channel assignments

### Step 8: api_keys (enhanced)
**Supported Providers:**
- OpenAI (existing)
- DeepSeek (existing)
- Gemini (existing)
- Grok (existing)
- Groq (existing)
- Anthropic (existing)
- Claude (NEW)
- Perplexity (NEW)
- Custom providers (increased to 5)

**Features:**
- Dynamic provider discovery from filesystem
- API key validation
- Provider-specific configuration
- Test connection capability

### Step 9: memory (NEW)
```php
// Configure memory paths
$memory_config = [
    'memory_path' => LUPOPEDIA_PATH . '/memory',
    'handoff_path' => LUPOPEDIA_PATH . '/handoffs',
    'actor_memory' => []
];

foreach ($actors as $actor) {
    $memory_config['actor_memory'][$actor['actor_id']] = [
        'memory_path' => $memory_config['memory_path'] . '/actors/' . $actor['actor_id'],
        'handoff_path' => $memory_config['handoff_path'] . '/' . $actor['actor_name']
    ];
}
```

**Features:**
- Configure memory paths for each actor
- Set up handoff directories
- Create memory directory structure
- Set appropriate permissions

### Step 10: complete (enhanced)
- Display success message
- Show installed actors summary
- Display channel assignments
- Provide next steps
- Generate installation report

## Code Changes Required

### 1. Update InstallWizardSteps Class
```php
// In install_wizard_classes.php
public static function getWizardSteps($install_type = 'new') {
    if ($install_type === 'new') {
        return ['welcome', 'credentials', 'confirm', 'run', 'actors', 'channels', 
                'config', 'api_keys', 'memory', 'complete'];
    } else {
        return ['welcome', 'credentials', 'bootstrap', 'actors', 'channels', 
                'normalize', 'confirm', 'run', 'config', 'api_keys', 'memory', 'complete'];
    }
}
```

### 2. Add New Step Handlers
```php
// In install.php
case 'actors':
    handle_actors_step();
    break;
case 'channels':
    handle_channels_step();
    break;
case 'memory':
    handle_memory_step();
    break;
```

### 3. New Helper Functions
```php
function handle_actors_step() {
    // Scan and register filesystem actors
}

function handle_channels_step() {
    // Assign channel keys and configure coordination
}

function handle_memory_step() {
    // Configure memory and handoff paths
}

function read_actor_config($actor_dir) {
    // Read actor configuration files
}

function register_actor($actor_id, $config) {
    // Register actor in database
}

function assign_channel_key($actor_id, $channel_key) {
    // Assign channel key to actor
}
```

### 4. Update Configuration Template
```php
// In lupopedia-config.php template
// Add channel key assignments
// Add memory path configurations
// Add extended API provider list
```

## Database Schema Updates

### New Tables
```sql
-- Actor registry for tracking filesystem actors
CREATE TABLE {{prefix}}actor_registry (
    actor_registry_id bigint NOT NULL,
    actor_id bigint NOT NULL,
    actor_name varchar(64) NOT NULL,
    filesystem_path varchar(500) NOT NULL,
    config_hash varchar(64) NOT NULL,
    registration_status varchar(32) NOT NULL DEFAULT 'pending',
    channel_key varchar(64) DEFAULT NULL,
    memory_path varchar(500) DEFAULT NULL,
    handoff_path varchar(500) DEFAULT NULL,
    created_ymdhis bigint NOT NULL DEFAULT 0,
    updated_ymdhis bigint NOT NULL DEFAULT 0,
    is_deleted tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (actor_registry_id)
);
```

### Updated Tables
```sql
-- Add channel_key to lupo_actors
ALTER TABLE {{prefix}}actors ADD COLUMN channel_key varchar(64) DEFAULT NULL;

-- Add memory_path and handoff_path to lupo_actors
ALTER TABLE {{prefix}}actors ADD COLUMN memory_path varchar(500) DEFAULT NULL;
ALTER TABLE {{prefix}}actors ADD COLUMN handoff_path varchar(500) DEFAULT NULL;
```

## File System Changes

### New Directories Created
- `memory/actors/{actor_id}/` - Individual actor memory
- `handoffs/{actor_name}/` - Actor handoff directories
- `channels/registry/` - Channel registry storage

### Configuration Files Updated
- `lupopedia-config.php` - Enhanced with new settings
- `actor_registry.json` - Track registered actors

## Security Enhancements

### New Security Features
- Red-team user isolation (auth_user_id 420)
- API key encryption in configuration
- Channel-based access control
- Actor permission validation

### Validation Rules
- Actor ID must be numeric and unique
- Channel keys must follow naming convention
- Memory paths must be within allowed directories
- API keys must pass provider validation

## Error Handling

### New Error Scenarios
- Actor registration conflicts
- Channel key assignment failures
- Memory path permission issues
- API provider validation failures

### Recovery Mechanisms
- Rollback on actor registration failure
- Fallback channel key generation
- Memory path permission repair
- Graceful API provider degradation

## Testing Requirements

### Unit Tests
- Actor registration function
- Channel key assignment
- Memory path configuration
- API key validation

### Integration Tests
- Full installer flow
- Actor filesystem scanning
- Channel coordination setup
- Memory system initialization

## Migration Path

### From 4.1.0
1. Run database migration script
2. Rescan filesystem actors
3. Assign channel keys to existing actors
4. Update configuration files

### From 4.0.x
1. Complete standard upgrade
2. Run actor registration step
3. Configure channel-based coordination
4. Migrate to new configuration format

## Performance Considerations

### Optimization Points
- Cache actor directory scans
- Batch actor registration
- Parallel channel key assignment
- Lazy memory path creation

### Resource Usage
- Memory: Increased for actor scanning
- Disk: Additional storage for memory paths
- Database: Minimal impact (new indexes)
- Network: No significant change

## Conclusion

The updated installer flow provides comprehensive support for 4.1.3 features while maintaining backward compatibility. The new steps for actor registration, channel configuration, and memory setup ensure proper system initialization for channel-based coordination.
