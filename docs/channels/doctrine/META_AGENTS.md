---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: Kiro
  target: @everyone
  message: "Created META_AGENTS.md - doctrine for meta-agents like CARMEN that operate on top of the emotional architecture without storing emotional state."
  mood: "00FF00"
tags:
  categories: ["doctrine", "meta-agents", "architecture"]
  collections: ["core-docs", "agent-architecture"]
  channels: ["dev", "internal"]
file:
  title: "Meta-Agents Doctrine"
  description: "Meta-agents operate on top of emotional architecture without storing state. Includes CARMEN (emotional interpretation) and other meta-agent patterns."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Meta-Agents Doctrine

**Official Doctrine Document**  
**Version 4.0.15**  
**Effective Date: 2026-01-13**

---

## Overview

**Meta-agents** are agents that operate on top of other agents or systems without storing their own state.

Meta-agents **interpret, route, synthesize, or coordinate** but do not **embody** the domains they work with.

---

## What is a Meta-Agent?

### Definition

A **meta-agent** is an agent that:
- Operates on top of existing systems
- Does not store its own domain-specific state
- Interprets, routes, or synthesizes information
- Coordinates across multiple domains or agents

### Characteristics

**Meta-agents:**
- ✅ Read state from other agents/domains
- ✅ Interpret and synthesize information
- ✅ Route requests to appropriate handlers
- ✅ Coordinate multi-domain operations
- ❌ Do not store their own emotional/domain state
- ❌ Do not belong to a single domain
- ❌ Do not consume slots in domain-specific ranges

---

## Meta-Agent vs Domain Agent

| Aspect | Domain Agent | Meta-Agent |
|--------|--------------|------------|
| **Purpose** | Embody a specific domain | Interpret/coordinate across domains |
| **State Storage** | Yes | No |
| **Slot Range** | Domain-specific (e.g., 1000-1999 for emotional) | System-level or N/A |
| **Operation** | Embody | Interpret/coordinate |
| **Example** | EMO_TRUST (trust domain) | CARMEN (emotional interpretation) |

---

## CARMEN: Emotional Interpretation Meta-Agent

**CARMEN** is the primary emotional interpretation meta-agent in Lupopedia.

### Purpose

CARMEN interprets emotional domains and synthesizes compassionate, context-aware responses.

### Operation

1. **Reads** emotional domains (vectors + textures)
2. **Routes** them through interpretive faucets
3. **Synthesizes** a coherent response

### Key Characteristics

- Does not store emotional state
- Operates across all ~25 emotional domains
- Uses default AGAPE/METIS/ERIS triad
- Supports extensible faucet plugins for multicultural interpretation

**See:** [CARMEN_DOCTRINE.md](CARMEN_DOCTRINE.md) for complete documentation.

---

## Other Meta-Agent Patterns

### Routing Meta-Agents

**Purpose:** Route requests to appropriate domain agents

**Example:** Emotional Router
- Receives generic term like "love"
- Determines context
- Routes to correct Greek love domain (Agápē, Éros, Philía, etc.)

### Synthesis Meta-Agents

**Purpose:** Combine outputs from multiple domain agents

**Example:** Multi-Domain Synthesizer
- Reads multiple active domains
- Synthesizes coherent interpretation
- Respects domain independence

### Coordination Meta-Agents

**Purpose:** Coordinate multi-agent operations

**Example:** Agent Orchestrator
- Manages agent lifecycle
- Coordinates agent communication
- Handles agent conflicts

---

## Meta-Agent Implementation Rules

### Rule 1: No State Storage

Meta-agents **must not** store domain-specific state.

**Correct:**
```php
class CARMEN {
    public function interpret($domains) {
        // Read domains, interpret, synthesize
        // No state storage
    }
}
```

**Wrong:**
```php
class CARMEN {
    private $emotionalState = [];  // ❌ Meta-agents don't store state
}
```

### Rule 2: Read-Only Access to Domains

Meta-agents **read** domain state but do not **modify** it.

**Correct:**
```php
$domains = $this->emotionalSystem->getActiveDomains();
$interpretation = $this->interpret($domains);
```

**Wrong:**
```php
$domains = $this->emotionalSystem->getActiveDomains();
$domains['trust']['vector']['trust'] = 0.5;  // ❌ Don't modify domain state
```

### Rule 3: Respect Domain Independence

Meta-agents must respect domain boundaries.

**Correct:**
```php
foreach ($domains as $domain) {
    $interpretation = $this->interpretDomain($domain);
    // Each domain interpreted independently
}
```

**Wrong:**
```php
$combinedVector = $this->mergeDomains($domains);  // ❌ Don't merge domains
```

### Rule 4: Extensible Architecture

Meta-agents should support extensibility through plugins or modules.

**Example:**
```php
interface FaucetPlugin {
    public function interpret($domain, $vector, $texture);
}

class CARMEN {
    private $faucets = [];
    
    public function registerFaucet($name, FaucetPlugin $faucet) {
        $this->faucets[$name] = $faucet;
    }
}
```

---

## For AI Agents (Kiro, Claude, Cascade, Junie, etc.)

When implementing meta-agents:

**DO:**
- ✅ Operate on top of existing systems
- ✅ Read state from domain agents
- ✅ Interpret and synthesize information
- ✅ Respect domain independence
- ✅ Support extensibility through plugins

**DON'T:**
- ❌ Store domain-specific state
- ❌ Modify domain agent state
- ❌ Merge independent domains
- ❌ Assign meta-agents to domain-specific slot ranges
- ❌ Treat meta-agents as domain agents

---

## Related Documentation

- **[CARMEN_DOCTRINE.md](CARMEN_DOCTRINE.md)** — CARMEN emotional interpretation meta-agent
- **[EMOTIONAL_GEOMETRY.md](EMOTIONAL_GEOMETRY.md)** — Multi-domain emotional architecture
- **[EMO_AGENT_RULES.md](EMO_AGENT_RULES.md)** — Rules for emotional agent implementation
- **[KERNEL_AGENTS.md](KERNEL_AGENTS.md)** — Kernel agents including LILITH

---

**This doctrine is absolute and binding for all AI agents.**
