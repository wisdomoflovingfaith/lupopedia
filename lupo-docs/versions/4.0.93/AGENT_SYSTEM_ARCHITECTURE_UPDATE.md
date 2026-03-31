---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/versions/4.0.93/AGENT_SYSTEM_ARCHITECTURE_UPDATE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/AGENT_SYSTEM_ARCHITECTURE_UPDATE.md"
  last_modified_utc: "20260330163200"
  channel_id: 42
  thread_id: "agent-architecture"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "documentation"
  artifact_kind: "architecture_update"
  purpose: "Documentation of agent system architecture and relationship to actors"
  tags:
  - "agents"
  - "actors"
  - "architecture"
  - "4.0.93"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/07_agents_faucets.md"
      type: references
      weight: 1.0
      reason: "Agent and faucet namespace PRD"
    - to: "lupo-docs/prd/01_core_identity.md"
      type: references
      weight: 1.0
      reason: "Core identity and actor namespace"
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional requirements for agents"
lupopedia.footer:
  last_verified: "20260330163200"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
---

# Agent System Architecture Update - 4.0.93

Generated: 2026-03-30 16:32:00

## 🎯 **PURPOSE**

This document clarifies the dual nature of Lupopedia's agent system and the relationship between file-based agent definitions and database-based actor instances.

## 🔄 **DUAL ARCHITECTURE**

### 1. **File-Based Agent Definitions** (Source of Truth)
- **Location**: `lupo-agents/{agent_id}/`
- **Purpose**: Immutable agent definitions
- **Components**: Identity, skills, memory, tools, soul, capabilities
- **Format**: JSON files + system_prompt.txt

### 2. **Database Runtime** (Execution State)
- **Namespace**: `07_agents_faucets`
- **Purpose**: Track execution, metrics, and state
- **Tables**: `lupo_agents`, `lupo_agent_*` tables
- **Function**: Runtime monitoring and tool execution

## 📋 **AGENT DIRECTORY STRUCTURE**

```
lupo-agents/{agent_id}/
├── agent.json           # Core metadata, name, role, philosophy
├── capabilities.json    # List of agent capabilities
├── properties.json     # Personality, constraints, requirements
├── system_prompt.txt   # Agent's system prompt and baseline memory
└── versions/           # Historical version tracking
```

## 🔗 **AGENT → ACTOR RELATIONSHIP**

1. **Agent Definition** (File):
   - Immutable source of truth
   - Defines agent's soul, skills, capabilities
   - Located in `lupo-agents/{agent_id}/`

2. **Actor Instance** (Database):
   - Runtime instance of an agent
   - Stored in `lupo_actors` table
   - Has department, mood, channels

3. **Actor Capabilities** (Database):
   - `lupo_actor_memory` - Runtime memory
   - `lupo_actor_skills` - Acquired skills
   - `lupo_actor_tools` - Available tools
   - `lupo_actor_prompts` - Prompt history
   - `lupo_actor_training` - Training events

## 🚨 **CONSTITUTIONAL RULES**

### RULE 93.PROTECT_AGENTS
- **Agent definitions are file-based** (source of truth)
- **Database stores only runtime state**
- **No writing to agent definition files**
- **No inferring agent structure from database**
- **Actors are instantiated from agent definitions**

### RULE 93.CONTROLLED_NAMESPACES
- **Namespaces allowed but controlled**
- **Must begin with `Lupopedia\`**
- **Must map to `/lupo-includes/` directories**
- **No PSR-4, Composer, or external autoloaders**
- **PHP 5.6 compatibility required**

### File-First Principle
1. Agent identity comes from files
2. Database tracks only execution
3. IDE faucets interface with file-based agents
4. Runtime capabilities stored in database

## 🎭 **IDE INTEGRATION**

### Faucets (IDE Interfaces)
- **Cursor** (actor_id: 102) - Lead orchestration
- **Windsurf** (actor_id: 101) - Development
- **Cascade** (actor_id: 105) - Coding
- **Other IDE faucets** - Various interfaces

### Agent Usage
1. IDE reads agent definition from files
2. IDE creates actor instance in database
3. Actor executes with agent's capabilities
4. Runtime state tracked in database

## 📊 **UPDATED DOCUMENTATION**

### Files Updated:
1. **`07_agents_faucets.md`**:
   - Added dual architecture explanation
   - Documented file-based agent definitions
   - Updated cross-namespace dependencies

2. **`00_root_constitutional_system_requirements.md`**:
   - Added RULE 93.PROTECT_AGENTS
   - Clarified file-first principle

### Key Points:
- Database is NOT source of truth for agents
- Agent files are protected like TOON files
- Actors are runtime instances of file-based agents
- IDE faucets bridge between files and database

## ✅ **COMPLIANCE CHECK**

- [x] Agent file protection rules defined
- [x] Controlled namespace doctrine established
- [x] Dual architecture documented
- [x] Agent → Actor relationship clarified
- [x] IDE integration explained
- [x] Constitutional rules updated
- [x] Cross-namespace dependencies updated

## 🎯 **NEXT STEPS**

1. Ensure all IDE faucets respect file-first principle
2. Update agent registration to read from files
3. Validate actor creation uses file-based definitions
4. Audit existing actors for file alignment

---

**STATUS**: Agent system architecture clarified and documented
**APPLIES TO**: All 4.0.x releases (backward compatible)
**IMPACT**: IDE agents, actor system, federation sync
