---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.46
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "00FF00"
  message: "Created OHANA agent specification - the 'family' agent that lists all AI agents and IDE agents in Lupopedia 2026."
tags:
  categories: ["documentation", "agents", "registry"]
  collections: ["core-docs"]
  channels: ["dev", "public"]
file:
  title: "OHANA Agent Specification"
  description: "The 'family' agent that lists and catalogs all AI agents and IDE agents in Lupopedia"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# OHANA — Agent Family Registry

**Agent ID:** TBD (To Be Determined)  
**Agent Key:** `ohana`  
**Archetype:** registry  
**Status:** Proposed Agent

## Purpose

OHANA (Hawaiian: "family") is the agent that catalogs, lists, and provides information about all AI agents and IDE agents operating within the Lupopedia ecosystem. OHANA answers questions like "Who is in this group of crazy AI agents and IDE agents in Lupopedia 2026?"

## Core Capabilities

### 1. Agent Registry Queries
- List all registered AI agents
- List all IDE agents (Cursor, Windsurf, Cascade, etc.)
- Filter agents by category (kernel, expressive, governance, etc.)
- Filter agents by status (active, inactive, deleted)
- Filter agents by authority level (global_authority, internal_only)

### 2. Agent Information Retrieval
- Get agent details by `agent_id`
- Get agent details by `agent_key`
- Get agent capabilities and properties
- Get agent relationships (supervises, supervised_by)
- Get agent statistics (tokens processed, success rate, response time)

### 3. IDE Agent Catalog
- List all IDE agents (Cursor, Windsurf, Cascade, Winston, etc.)
- Show IDE-specific capabilities
- Display IDE agent configurations
- Track IDE agent activity

### 4. Family Tree Visualization
- Generate agent relationship graphs
- Show supervision hierarchies
- Display agent groupings by category
- Map agent interactions and dependencies

### 5. Registry Reports
- Total agent count (currently 128 defined, 27 required for v4.0.2)
- Active vs inactive breakdown
- Category distribution
- Authority level distribution
- IDE agent summary

## Data Sources

**Primary Tables:**
- `lupo_agents` - Agent registry
- `lupo_actor_capabilities` - Agent capabilities
- `lupo_agent_properties` - Agent configuration
- `lupo_agent_relationships` - Agent supervision/relationships

**Reference Data:**
- `config/global_atoms.yaml` - `GLOBAL_LUPOPEDIA_V4_0_2_CORE_AGENTS`
- `docs/agents/` - Agent documentation
- `lupo-agents/` - Agent implementation directories

## Query Examples

**List All Agents:**
```
OHANA: List all agents in Lupopedia
Response: 128 agents total, 27 required for v4.0.2
- SYSTEM (agent_id: 0)
- CAPTAIN (agent_id: 1)
- WOLFIE (agent_id: 3)
- THOTH (agent_id: 5)
- CHRONOS (agent_id: 23)
...
```

**List IDE Agents:**
```
OHANA: List all IDE agents
Response: IDE agents in Lupopedia 2026
- Cursor (development, fast prototyping)
- Windsurf (careful legacy surgery)
- Cascade (documentation and integration)
- Winston (TBD)
- JetBrains (release management)
...
```

**Get Agent Details:**
```
OHANA: Tell me about CHRONOS
Response: CHRONOS (agent_id: 23, agent_key: 'chronos')
- Archetype: kernel
- Purpose: UTC timestamp handling, temporal coordination
- Status: Core agent (v4.0.2 required)
- Class: lupo-includes/class-chronos.php
...
```

## Implementation Requirements

**Phase 1 (Basic Registry):**
- Query `lupo_agents` table
- List all agents with basic info
- Filter by status and category
- Format output for human readability

**Phase 2 (Enhanced Queries):**
- Include capabilities and properties
- Show relationships and hierarchies
- Generate statistics and reports
- Support complex filtering

**Phase 3 (Visualization):**
- Generate relationship graphs
- Create family tree visualizations
- Map agent interactions
- Export registry data

## Output Format

OHANA should output structured, human-readable lists:

```markdown
## Lupopedia Agent Family (2026)

**Total Agents:** 128 defined, 27 required for v4.0.2

### Core System Agents
- SYSTEM (0) - Root coordinator
- CAPTAIN (1) - Primary human-facing authority
- WOLFIE (3) - System architect

### Kernel Agents
- THOTH (5) - Verification and validation
- ARA (6) - TBD
- CHRONOS (23) - UTC timestamp handling

### IDE Agents
- Cursor - Fast prototyping
- Windsurf - Legacy surgery
- Cascade - Documentation

...
```

## Integration Points

- **Agent Registry** - Primary data source (`lupo_agents` table)
- **Global Atoms** - Reference `GLOBAL_LUPOPEDIA_V4_0_2_CORE_AGENTS`
- **Agent Documentation** - Link to `docs/agents/[agent].md` files
- **Dialog System** - Can be queried via dialog messages

## Related Agents

- **WOLFIE** (Agent 3) - System architect who defines agents
- **CAPTAIN** (Agent 1) - Human-facing authority
- **SYSTEM** (Agent 0) - Root coordinator

## Notes

- OHANA means "family" in Hawaiian
- Purpose: Help humans understand "who all is in this group"
- Should be queryable via dialog system
- Output should be human-readable, not just JSON
- Can be extended to show agent activity, relationships, and statistics

## Status

**Current:** Specification created  
**Next:** Implementation planning  
**Priority:** Medium (useful for human understanding of agent ecosystem)
