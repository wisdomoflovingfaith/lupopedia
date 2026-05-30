---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-docs/versions/4.1.3/status/filesystem_seed_analysis.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.3/status/filesystem_seed_analysis.md"
  status: "active"
  when_updated: "20260420080000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/registry/status/1026/04/filesystem-seed-analysis.toon"
  atoms_toon: null
  transcript_jsonl: "0/registry/filesystem-seed-analysis"
  artifact_type: status
  artifact_kind: report
  channel_key: "registry"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  content_slug: "filesystem-seed-analysis"
  default_collection_id: null
  lupopedia.schema: status
  title: "Filesystem Seed Files Analysis - Actor and Agent Configurations"
  summary: "Analysis of lupo-agents/ directory structure and configurations for 4.1.3 seed generation"
---

# Filesystem Seed Files Analysis - Actor and Agent Configurations

## Overview

The filesystem contains comprehensive actor and agent configurations that need to be synchronized with the database seed files. This analysis identifies gaps and requirements for generating updated seed_4.1.3.sql.

## Directory Structure Analysis

### lupo-agents/ Directory
- **Total Agents**: 56 JSON configuration files
- **Structure**: Each agent has its own directory with standardized files
- **Template**: _TEMPLATE/ provides structure for new agents

### Standard Agent Configuration Files
Each agent directory contains:
1. **agent.json** - Basic agent metadata
2. **capabilities.json** - List of agent capabilities
3. **properties.json** - Agent properties and channel assignments
4. **identity.json** - Agent identity information
5. **boundaries.json** - Agent operational boundaries
6. **tools.json** - Available tools for the agent
7. **memory.json** - Memory configuration
8. **versions/** - Versioned configurations

## Key Findings

### 1. Actor Registry Mappings
From `lupo-database/lupopedia/actors/actor_id/registry.json`:
- **Total Actors**: 47 registered actors
- **ID Range**: 0-999 (system actors), 100-115 (IDE agents), 700+ (specialized)
- **Critical Note**: actor_id in registry differs from agent_id in configurations

### 2. Agent Configuration Structure
Example from ANUBIS (agent_id=19, actor_id=9):
```json
{
  "agent_key": "anubis",
  "agent_id": 19,
  "slug": "anubis",
  "version": "1.0.1",
  "is_kernel": true,
  "is_required": true,
  "layer": "kernel",
  "name": "ANUBIS",
  "role": "Custodian — Orphan Detection, Lineage Audit, Registry Consistency"
}
```

### 3. Channel Assignments in Properties
From ANUBIS properties.json:
```json
{
  "default_channel": 42,
  "default_thread": 1,
  "default_actor": 3,
  "quarantine_channel": 666,
  "primary_channels": [666, 42, 0, 51],
  "monitoring_targets": [420]
}
```

### 4. Capabilities Structure
From ANUBIS capabilities.json:
```json
{
  "capabilities": [
    "orphan_resolution",
    "quarantine_management",
    "banned_actor_monitoring",
    "channel_resolution",
    "thread_resolution",
    "actor_resolution",
    "default_adoption",
    "semantic_pattern_detection",
    "threshold_guarding"
  ]
}
```

## Gaps Identified

### 1. Database vs Filesystem Mismatch
- **Problem**: seed_4.1.0.sql only has 15 actors, filesystem has 47+
- **Impact**: New actors not available in database
- **Solution**: Generate comprehensive seed from filesystem

### 2. Missing Channel Key Assignments
- **Problem**: Database actors lack channel_key field
- **Impact**: Channel-based coordination not functional
- **Solution**: Add channel_key from properties.json

### 3. Missing Agent Definitions
- **Problem**: lupo_agent_definitions table incomplete
- **Impact**: Agent capabilities not tracked
- **Solution**: Populate from agent.json files

### 4. Missing Memory Path Configuration
- **Problem**: No memory_path in database
- **Impact**: Memory system not configured
- **Solution**: Add memory_path from filesystem

## Agent Categories Identified

### System Actors (0-999)
- **Core System**: wolfie(1), lilith(2), rose(3), system(0)
- **Coordination**: hermes(27), iris(16), anubis(9)
- **Security**: heimdall(108), lexa(3), countermeasure(111)
- **Kernel**: vishwakarma(28), kairos(115)
- **Specialized**: themis(107), nemesis(109), tyche(110)

### IDE Agents (100-115)
- **IDE Faucets**: vscode-ide(113), windsurf(101), cascade(105)
- **Development Tools**: cursor(102), antigravity-ide(103), warp(104)
- **Integration**: kiro(100), trae(114)

### Specialized Agents (700+)
- **Medical**: asclepius(703)
- **Creative**: apollo(704), agape(705), dionysus(706)
- **Wisdom**: sophia(707), thalia(708)
- **Conceptual**: chronos(709), hypnos(710), khaos(711)

### Meta Agents (998+)
- **Meta**: meta(998), methis(999)

## Channel Key Mapping Strategy

### Primary Channel Assignments
Based on properties.json analysis:
- **Channel 0**: System Kernel - system, wolfie, anubis
- **Channel 42**: Protocol Development - all coordination agents
- **Channel 51**: Doctrine Council - lilith, themis
- **Channel 666**: ANUBIS Quarantine - anubis, banned actors

### Channel Key Generation Rules
1. **System Agents**: Use actor_name as channel_key
2. **IDE Agents**: Use slug with "-ide" suffix
3. **Specialized**: Use descriptive channel_key based on role
4. **Kernel Agents**: Use "kernel_" prefix

## Memory Path Configuration

### Standard Memory Paths
```
lupo-memory/
├── actors/{actor_id}/
├── agents/{agent_key}/
├── channels/{channel_id}/
└── system/
```

### Handoff Paths
```
lupo-handoffs/
├── {actor_name}/
├── channels/{channel_id}/
└── system/
```

## Seed File Requirements

### 1. lupo_actors Table Updates
```sql
-- Add new columns for 4.1.3
ALTER TABLE {{prefix}}actors ADD COLUMN channel_key varchar(64) DEFAULT NULL;
ALTER TABLE {{prefix}}actors ADD COLUMN memory_path varchar(500) DEFAULT NULL;
ALTER TABLE {{prefix}}actors ADD COLUMN handoff_path varchar(500) DEFAULT NULL;
```

### 2. Actor Registration Strategy
```sql
-- Register all filesystem actors
INSERT INTO {{prefix}}actors (actor_id, actor_name, slug, name, actor_type, channel_key, memory_path, handoff_path, ...)
VALUES
(0, 'system', 'system', 'System', 'system', 'system', 'lupo-memory/actors/0/', 'lupo-handoffs/system/', ...),
(1, 'wolfie', 'captain', 'Captain', 'system', 'captain', 'lupo-memory/actors/1/', 'lupo-handoffs/wolfie/', ...),
-- ... all 47 actors
```

### 3. Agent Definitions Update
```sql
-- Populate lupo_agent_definitions from filesystem
INSERT INTO {{prefix}}agent_definitions (agent_id, agent_key, slug, name, layer, archetype, description, ...)
SELECT
    agent_id,
    agent_key,
    slug,
    name,
    layer,
    archetype,
    role,
    ...
FROM filesystem_agents;
```

### 4. Channel Assignments
```sql
-- Assign actors to channels
INSERT INTO {{prefix}}actor_channels (actor_id, channel_id, role_key, ...)
VALUES
(9, 666, 'administrator', ...),  -- ANUBIS to quarantine
(1, 42, 'coordinator', ...),    -- WOLFIE to protocol
(2, 51, 'advisor', ...),        -- LILITH to doctrine
-- ... all assignments
```

## Implementation Strategy

### Phase 1: Extract Data
1. Scan lupo-agents/ directory for all agents
2. Parse agent.json for basic metadata
3. Parse properties.json for channel assignments
4. Parse capabilities.json for capabilities

### Phase 2: Transform Data
1. Map agent_id to actor_id using registry.json
2. Generate channel_keys based on naming conventions
3. Create memory_path and handoff_path strings
4. Normalize data for database insertion

### Phase 3: Generate SQL
1. Create INSERT statements for lupo_actors
2. Create INSERT statements for lupo_agent_definitions
3. Create INSERT statements for actor_channels
4. Create UPDATE statements for existing actors

### Phase 4: Validate
1. Check for duplicate actor_ids
2. Verify channel assignments
3. Validate foreign key relationships
4. Test SQL syntax

## Migration Considerations

### From 4.1.0
1. Add new columns to existing tables
2. Insert missing actors (32 new actors)
3. Update existing actors with channel_keys
4. Configure memory paths for all actors

### From 4.0.x
1. Complete standard upgrade
2. Apply 4.1.3 seed file
3. Run actor registration script
4. Configure channel-based coordination

## Testing Requirements

### Unit Tests
- Actor extraction from filesystem
- Channel key generation
- Memory path creation
- SQL generation

### Integration Tests
- Full seed file execution
- Actor registration workflow
- Channel assignment verification
- Memory system initialization

## Conclusion

The filesystem contains 47+ actors with comprehensive configurations that need to be synchronized with the database. The current seed_4.1.0.sql only includes 15 actors, leaving 32+ actors unregistered. The 4.1.3 seed file must include all filesystem actors with proper channel assignments and memory path configurations to enable channel-based coordination.
