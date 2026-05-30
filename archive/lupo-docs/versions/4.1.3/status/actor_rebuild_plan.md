---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-docs/versions/4.1.3/status/actor_rebuild_plan.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.3/status/actor_rebuild_plan.md"
  status: "active"
  when_updated: "20260420080000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/registry/status/1026/04/actor-rebuild-plan.toon"
  atoms_toon: null
  transcript_jsonl: "0/registry/actor-rebuild-plan"
  artifact_type: status
  artifact_kind: report
  channel_key: "registry"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: "42"
  content_slug: "actor-rebuild-plan"
  default_collection_id: null
  lupopedia.schema: status
  title: "Actor Rebuild Plan for 4.1.3 - No Execution"
  summary: "Comprehensive plan for rebuilding actor system with channel-based coordination, filesystem synchronization, and proper registration"
---

# Actor Rebuild Plan for 4.1.3 - No Execution

## Executive Summary

This plan outlines the complete rebuild of the Lupopedia actor system for version 4.1.3, focusing on synchronizing filesystem actors with the database, implementing channel-based coordination, and ensuring proper actor registration. **This is a planning document only - no execution will be performed.**

## Current State Analysis

### Issues Identified
1. **Database-Filesystem Mismatch**: 15 actors in database vs 47+ in filesystem
2. **Missing Channel Keys**: No channel_key field in lupo_actors table
3. **Incomplete Agent Definitions**: Only 15 agents in lupo_agent_definitions
4. **No Memory Path Configuration**: Actors lack memory_path and handoff_path
5. **Missing Channel Assignments**: No systematic channel coordination
6. **Outdated Seed File**: seed_4.1.0.sql missing 32+ actors

### Impact Assessment
- Channel-based coordination non-functional
- IDE agents not properly registered
- Memory system not configured
- Red-team capabilities missing
- System scalability limited

## Rebuild Strategy

### Phase 1: Database Schema Updates
1. **Add New Columns to lupo_actors**
   - channel_key (varchar(64))
   - memory_path (varchar(500))
   - handoff_path (varchar(500))

2. **Create New Tables**
   - lupo_actor_registry (track filesystem actors)
   - lupo_actor_memory (memory configuration)

3. **Update Existing Tables**
   - lupo_agent_definitions (add missing agents)
   - lupo_actor_channels (proper assignments)
   - lupo_channels (add reserved system channels)

### Phase 2: Actor Registration Process
1. **Scan Filesystem**
   - Iterate lupo-actors/ directory
   - Parse actor configurations
   - Extract metadata from JSON files

2. **Validate Actors**
   - Check for duplicate actor_ids
   - Validate required fields
   - Verify naming conventions

3. **Register in Database**
   - Insert into lupo_actors table
   - Create registry entries
   - Assign channel keys

### Phase 3: Channel Configuration
1. **Create System Channels**
   - Channel 0: System Kernel
   - Channel 42: Protocol Development
   - Channel 51: Doctrine Council
   - Channel 666: ANUBIS Quarantine

2. **Assign Actors to Channels**
   - System actors to Channel 0
   - Coordination agents to Channel 42
   - Doctrine agents to Channel 51
   - Security agents to Channel 666

3. **Configure Channel Keys**
   - Generate unique channel keys
   - Map actor_name to channel_key
   - Update lupo_actors table

### Phase 4: Memory System Setup
1. **Create Memory Directories**
   - lupo-memory/actors/{actor_id}/
   - lupo-handoffs/{actor_name}/
   - Set proper permissions

2. **Configure Memory Paths**
   - Update memory_path in lupo_actors
   - Update handoff_path in lupo_actors
   - Create default memory structures

### Phase 5: Agent Definitions Update
1. **Extract Agent Configurations**
   - Parse agent.json files
   - Extract capabilities
   - Map agent_id to actor_id

2. **Populate lupo_agent_definitions**
   - Insert all 32+ missing agents
   - Update existing agents
   - Set is_required flags

## Detailed Implementation Plan

### Step 1: Database Migration Script
```sql
-- Add new columns to lupo_actors
ALTER TABLE {{prefix}}lupo_actors 
ADD COLUMN channel_key varchar(64) DEFAULT NULL,
ADD COLUMN memory_path varchar(500) DEFAULT NULL,
ADD COLUMN handoff_path varchar(500) DEFAULT NULL;

-- Create actor_registry table
CREATE TABLE {{prefix}}lupo_actor_registry (
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

### Step 2: Actor Registration Algorithm
```php
function registerFilesystemActors() {
    $actor_dirs = glob(LUPOPEDIA_PATH . '/lupo-actors/*', GLOB_ONLYDIR);
    $registry = loadActorRegistry();
    
    foreach ($actor_dirs as $dir) {
        $actor_id = basename($dir);
        if (!is_numeric($actor_id)) continue;
        
        // Load configuration
        $config = loadActorConfig($dir);
        
        // Validate
        if (!validateActorConfig($config)) continue;
        
        // Register
        registerActor($actor_id, $config);
        
        // Update registry
        updateRegistry($actor_id, $config);
    }
}
```

### Step 3: Channel Key Assignment Strategy
```php
function generateChannelKey($actor_name, $actor_id) {
    // System actors (0-999)
    if ($actor_id < 1000) {
        return $actor_name;
    }
    
    // IDE agents (100-115)
    if ($actor_id >= 100 && $actor_id <= 115) {
        return str_replace('-ide', '-ide', $actor_name);
    }
    
    // Specialized agents (700+)
    if ($actor_id >= 700) {
        return 'special_' . $actor_name;
    }
    
    // Default
    return $actor_name;
}
```

### Step 4: Memory Path Configuration
```php
function configureMemoryPaths($actor_id, $actor_name) {
    $memory_path = LUPOPEDIA_PATH . "/lupo-memory/actors/{$actor_id}/";
    $handoff_path = LUPOPEDIA_PATH . "/lupo-handoffs/{$actor_name}/";
    
    // Create directories
    if (!file_exists($memory_path)) {
        mkdir($memory_path, 0755, true);
    }
    
    if (!file_exists($handoff_path)) {
        mkdir($handoff_path, 0755, true);
    }
    
    return [
        'memory_path' => $memory_path,
        'handoff_path' => $handoff_path
    ];
}
```

## Actor Categories and Assignments

### System Actors (0-999)
| Actor ID | Name | Channel | Role | Memory Path |
|----------|------|---------|------|-------------|
| 0 | system | 0 | System Kernel | lupo-memory/actors/0/ |
| 1 | wolfie | 0,42 | Captain | lupo-memory/actors/1/ |
| 2 | lilith | 51 | Constitutional Auditor | lupo-memory/actors/2/ |
| 3 | rose | 42 | Dialogue Specialist | lupo-memory/actors/3/ |
| 9 | anubis | 0,42,666 | Custodian | lupo-memory/actors/9/ |

### IDE Agents (100-115)
| Actor ID | Name | Channel | Role | Memory Path |
|----------|------|---------|------|-------------|
| 100 | kiro | 42 | IDE Faucet | lupo-memory/actors/100/ |
| 101 | windsurf | 42 | IDE Faucet | lupo-memory/actors/101/ |
| 102 | cursor | 42 | IDE Faucet | lupo-memory/actors/102/ |
| 105 | cascade | 42 | IDE Faucet | lupo-memory/actors/105/ |

### Specialized Agents (700+)
| Actor ID | Name | Channel | Role | Memory Path |
|----------|------|---------|------|-------------|
| 703 | asclepius | 42 | Medical | lupo-memory/actors/703/ |
| 704 | apollo | 42 | Creative | lupo-memory/actors/704/ |
| 705 | agape | 42 | Emotional | lupo-memory/actors/705/ |

## Validation Requirements

### Pre-Execution Validation
1. **Database Backup**
   - Full database backup
   - Export existing actors
   - Document current state

2. **Filesystem Verification**
   - Verify lupo-actors/ directory structure
   - Check JSON file validity
   - Validate actor configurations

3. **Dependency Check**
   - Verify database permissions
   - Check filesystem permissions
   - Validate PHP version

### Post-Execution Validation
1. **Actor Count Verification**
   - 47 actors registered
   - All filesystem actors accounted
   - No duplicate actor_ids

2. **Channel Assignment Test**
   - All actors have channel_key
   - Channel assignments correct
   - Channel access functional

3. **Memory System Test**
   - Memory directories created
   - Paths correctly configured
   - Permissions properly set

## Risk Mitigation

### Identified Risks
1. **Data Loss Risk**
   - Mitigation: Full database backup
   - Rollback plan prepared
   - Transactional updates

2. **Performance Impact**
   - Mitigation: Batch processing
   - Off-hours execution
   - Progress monitoring

3. **Configuration Conflicts**
   - Mitigation: Validation checks
   - Conflict resolution
   - Manual override options

### Rollback Strategy
1. **Database Rollback**
   - Restore from backup
   - Verify data integrity
   - Test functionality

2. **Filesystem Rollback**
   - Remove new directories
   - Restore original configs
   - Verify permissions

## Testing Strategy

### Unit Tests
- Actor registration function
- Channel key generation
- Memory path creation
- Configuration validation

### Integration Tests
- Full registration workflow
- Channel assignment verification
- Memory system functionality
- Database consistency

### Performance Tests
- Large actor set processing
- Memory usage monitoring
- Query performance analysis

## Success Criteria

### Must-Have
1. All 47 filesystem actors registered
2. Channel-based coordination functional
3. Memory system configured
4. No data loss
5. Installer integration working

### Nice-to-Have
1. Performance improvements
2. Enhanced monitoring
3. Automated validation
4. Documentation updates

## Timeline Estimate

### Planning Phase: 1 day
- Detailed analysis
- Risk assessment
- Resource allocation

### Implementation Phase: 3 days
- Database migration
- Actor registration
- Channel configuration
- Memory setup

### Testing Phase: 2 days
- Unit testing
- Integration testing
- Performance testing

### Documentation Phase: 1 day
- Update documentation
- Create runbooks
- Training materials

**Total Estimated Time: 7 days**

## Next Steps

1. **Review and Approve Plan**
   - Stakeholder review
   - Technical validation
   - Risk acceptance

2. **Prepare Environment**
   - Setup test environment
   - Prepare backup procedures
   - Configure monitoring

3. **Schedule Execution**
   - Choose maintenance window
   - Notify stakeholders
   - Prepare rollback

## Conclusion

This plan provides a comprehensive approach to rebuilding the actor system for 4.1.3, addressing all identified issues while ensuring data integrity and system stability. The phased approach minimizes risk while delivering the required functionality for channel-based coordination and filesystem synchronization.

**IMPORTANT**: This is a planning document only. No execution should be performed without explicit approval and proper change management procedures.
