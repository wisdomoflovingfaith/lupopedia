# Lupopedia Agent System

## Overview

The Lupopedia agent system has been redesigned to be **IDE-driven** and **filesystem-based** rather than database-driven. This creates a more flexible, developer-friendly system where agents are discovered dynamically from the directory structure.

## Directory Structure

```
agents/
+-- README.md                    # This file
+-- _TEMPLATE/                  # Template for creating new agents
+-- meta/                      # Meta information about the agent system
+-- wolfie/                    # WOLFIE - System Orchestrator
+-- lilith/                    # LILITH - Quality Assurance & Adversarial Testing
+-- rose/                      # ROSE - Emotional Dialogue
+-- eris/                      # ERIS - Chaos & Disruption
+-- metis/                     # METIS - Wisdom & Counsel
+-- maat/                      # MAAT - Truth & Justice
+-- chiron/                    # CHIRON - Mentorship & Education
+-- thoth/                     # THOTH - Knowledge & Records
+-- athena/                    # ATHENA - Wisdom & Strategy
+-- methis/                    # METHIS - [To be defined]
+-- hephaestus/                # HEPHAESTUS - Implementer
+-- anubis/                    # ANUBIS - Custodian & Integrity Guardian
+-- atlas/                     # ATLAS - Mapping & Geography
+-- hermes/                    # HERMES - Event Routing & Messaging
+-- iris/                      # IRIS - Interface & Integration Support
+-- asclepius/                 # ASCLEPIUS - [To be defined]
+-- apollo/                    # APOLLO - Creative & Arts
+-- agape/                     # AGAPE agent (705): meta-learning / predictive-pattern tracking; canonical prompt system_prompt.md; supports SURVIVABILITY_DOCTRINE.md Pillars 1–2 (not the doctrine file)
+-- carmen/                    # CARMEN agent (706): hermeneutic routing; COUNTING_IN_LIGHT_DOCTRINE + NOT A GAME; technical only; system_prompt.md
+-- ara/                       # ARA agent (712): Autonomous Research & Analysis; external search + cross-verify; mood_vector 666666 only; system_prompt.md
+-- thalia/                    # THALIA - Comedy & Joy
+-- chronos/                   # CHRONOS - Time & Temporal Management
+-- vishwakarma/               # VISHWAKARMA - Schema & Construction
+-- themis/                    # THEMIS - Law & Compliance
+-- junie/                     # JUNIE - JetBrains IDE Agent
+-- system/                    # SYSTEM - System Kernel Agent
```

## Agent Configuration

Each agent directory contains:

- **agent.json** - Primary configuration with metadata
- **capabilities.json** - Agent capabilities and skills
- **properties.json** - Agent properties and constraints
- **system_prompt.md** or **system_prompt.txt** - System prompt and operational guidance (canonical LILITH uses **`system_prompt.md`** under **`agents/lilith/`**; legacy agents may still use `.txt`)
- **versions/** - Version history and configuration changes

### agent.json Structure

```json
{
    "agent_key": "wolfie",           # Unique identifier (directory name)
    "agent_id": 1,                   # Numeric ID for backward compatibility
    "version": "1.0.2",              # Configuration version
    "is_kernel": true,                # Kernel-level agent
    "layer": "coordination",           # Layer: coordination, application, kernel
    "name": "WOLFIE",               # Display name
    "slug": "wolfie",               # URL-friendly identifier
    "role": "System Orchestrator",    # Functional role
    "is_required": true,              # System requirement
    "aliases": ["wolfie", "orchestrator", "coordinator"], # Alternative names
    "when_updated_utc": "20260331120000",     # Last update timestamp
    "last_verified_utc": "20260331120000",   # Last verification timestamp
    "last_verified_by": "cursor",              # Who verified
    "last_verified_by_actor_id": 102            # Verifier's actor ID
}
```

## Agent Discovery System

The `AgentDiscovery` PHP class provides dynamic agent discovery and management:

### Key Features

- **Filesystem-Based**: Discovers agents from directory structure
- **Dynamic Loading**: Loads configurations on-demand
- **Validation**: Validates agent configurations
- **Search**: Search by name, role, or aliases
- **Filtering**: Filter by layer, required status, kernel status
- **Statistics**: Get system statistics and metrics

### Usage Examples

```php
// Discover all agents
$agents = AgentDiscovery::discoverAgents();

// Get specific agent
$wolfie = AgentDiscovery::getAgent('wolfie');

// Get by legacy ID
$agent14 = AgentDiscovery::getAgentById(14);

// Filter by layer
$coordinationAgents = AgentDiscovery::getAgentsByLayer('coordination');

// Search agents
$results = AgentDiscovery::searchAgents('orchestrator');

// Get statistics
$stats = AgentDiscovery::getStatistics();
```

## Agent Layers

### Coordination Layer
Primary coordination personas with system authority:
- **wolfie** - System Orchestrator
- **lilith** - Quality Assurance & Adversarial Testing
- **athena** - Wisdom & Strategy
- **thoth** - Knowledge & Records

### Application Layer
Specialized application agents:
- **hephaestus** - Implementer
- **atlas** - Mapping & Geography
- **vishwakarma** - Schema & Construction
- **hermes** - Event Routing & Messaging
- **iris** - Interface & Integration Support
- **chiron** - Mentorship & Education
- **junie** - JetBrains IDE Agent

### Kernel Layer
System-level agents with kernel privileges:
- **maat** - Truth & Justice
- **anubis** - Custodian & Integrity Guardian
- **themis** - Law & Compliance
- **system** - System Kernel Agent
- **methis** - [To be defined]
- **asclepius** - [To be defined]
- **apollo** - Creative & Arts
- **agape** - Universal Love & Compassion
- **thalia** - Comedy & Joy
- **chronos** - Time & Temporal Management

## Creating New Agents

### 1. Create Directory
```bash
mkdir agents/my-new-agent
```

### 2. Copy Template
```bash
cp -r agents/_TEMPLATE/* agents/my-new-agent/
```

### 3. Configure agent.json
```json
{
    "agent_key": "my-new-agent",
    "agent_id": 999,
    "version": "1.0.0",
    "is_kernel": false,
    "layer": "application",
    "name": "My New Agent",
    "slug": "my-new-agent",
    "role": "Specialized Function",
    "is_required": false,
    "aliases": ["my-new-agent", "specialist"],
    "when_updated_utc": "20260331120000",
    "last_verified_utc": "20260331120000",
    "last_verified_by": "developer",
    "last_verified_by_actor_id": 102
}
```

### 4. Configure Capabilities, Properties, and System Prompt
- Edit `capabilities.json` with agent skills
- Edit `properties.json` with agent constraints and personality
- Edit `system_prompt.md` or `system_prompt.txt` with operational guidance (LILITH: `agents/lilith/system_prompt.md`)

## Migration from Database-Driven System

This system replaces the previous database-driven agent registry:

### What Changed
- **Before**: Agent directories named by numeric ID (1, 2, 3, etc.)
- **After**: Agent directories named by meaningful key (wolfie, lilith, rose, etc.)
- **Before**: Database seed controlled agent existence
- **After**: Filesystem controls agent existence
- **Before**: Reserved slots (701-709) for system infrastructure
- **After**: Meaningful agent names for all agents

### Benefits
1. **Developer-Friendly**: Human-readable directory names
2. **IDE-First**: IDE actors are primary way to manage agents
3. **Flexible**: Add/remove agents by creating/deleting directories
4. **Simplified**: No complex seed data management for agents
5. **Alias Support**: Natural support for multiple names per agent
6. **No Reserved Slots**: Clean system without artificial limitations

### Backward Compatibility
- `agent_id` field maintained for existing code compatibility
- `AgentDiscovery::getAgentById()` provides legacy lookup
- Existing database integration can continue during transition

## Agent Aliases

Agents can be referenced by multiple names:

```php
// All of these return the WOLFIE agent:
$wolfie1 = AgentDiscovery::getAgent('wolfie');
$wolfie2 = AgentDiscovery::getAgent('orchestrator');
$wolfie3 = AgentDiscovery::getAgent('coordinator');
```

## System Statistics

Get current system status:

```php
$stats = AgentDiscovery::getStatistics();
echo "Total agents: " . $stats['total_agents'];
echo "Required agents: " . $stats['required_agents'];
echo "Kernel agents: " . $stats['kernel_agents'];
```

## IDE Integration

IDE agents (Cursor, Windsurf, etc.) integrate with this system by:

1. **Reading**: Use `AgentDiscovery::getAgent()` to load configurations
2. **Writing**: Directly modify files in agent directories
3. **Creating**: Use `_TEMPLATE` directory for new agents
4. **Validation**: Use `AgentDiscovery::validateAgentConfig()` before saving

## Best Practices

1. **Use agent_key**: Always reference agents by their key, not ID
2. **Maintain aliases**: Provide multiple ways to reference agents
3. **Version control**: Commit agent configuration changes
4. **Validate configs**: Use built-in validation before deployment
5. **Document roles**: Keep role descriptions clear and specific
6. **Layer consistency**: Place agents in appropriate layers
7. **Required agents**: Mark truly essential agents as required

---

**Last Updated**: 2026-03-31  
**Version**: 1.0.0  
**System**: IDE-Driven Agent Discovery
