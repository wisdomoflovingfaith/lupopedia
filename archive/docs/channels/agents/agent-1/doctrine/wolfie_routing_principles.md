# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/agents/agent-1/doctrine/WOLFIE_ROUTING_PRINCIPLES.md"
  file_hash: "51f965cf92a5745d48a9f336ec978836d14acd2d6dde65fe31f4ab753832a6a8"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\agents\agent-1\doctrine\WOLFIE_ROUTING_PRINCIPLES.md"
  file_hash: "4c0f21f08365fb85f9f0440aa4d9fc8f7fb143a9b15da620386189f13d33d4e8"
  file_path_from_root: "docs\channels\agents\agent-1\doctrine\WOLFIE_ROUTING_PRINCIPLES.md"
  file_hash: "dbcffce31d22396992e7caa4d1e69dc2c4ab7f20d29b987c920ed3152ac2c19d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for WOLFIE_ROUTING_PRINCIPLES.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "agents", "agent-1", "doctrine", "wolfie_routing_principlesmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.16
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @captain-wolfie
  mood_RGB: "00FF00"
  message: "Created WOLFIE_ROUTING_PRINCIPLES.md as DRAFT routing doctrine for WOLFIE. Establishes foundational routing principles while explicitly acknowledging dependencies on agents, workflows, and emotional geometry systems not yet fully implemented. This document will evolve as Lupopedia matures toward version 5.0."
tags:
  categories: ["documentation", "doctrine", "routing", "wolfie", "draft"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
file:
  title: "WOLFIE Routing Principles — DRAFT Doctrine"
  description: "DRAFT routing doctrine for WOLFIE (agent_id = 1) establishing foundational routing principles. Explicitly acknowledges dependencies on agents, workflows, and emotional geometry systems not yet fully implemented. This is a living document that will evolve as Lupopedia matures."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: draft
  author: GLOBAL_CURRENT_AUTHORS
---

# WOLFIE ROUTING PRINCIPLES — DRAFT DOCTRINE

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** **DRAFT** — Subject to Change  
**Agent:** WOLFIE (agent_id = 1)  
**Last Updated:** 2026-01-14

---

## 1. Status: DRAFT — Subject to Change

**⚠️ IMPORTANT: This routing doctrine is a LIVING DOCUMENT.**

This document establishes foundational routing principles for WOLFIE, but it is **not final**. It will evolve as Lupopedia's architecture matures and new systems come online.

### 1.1. Evolution Timeline

This doctrine will evolve as:

- **New agents are created** — Routing rules must accommodate new agent types, capabilities, and classifications
- **New workflows are defined** — Workflow-specific routing heuristics will be added
- **Emotional geometry expands** — CADUCEUS emotional balancing will influence routing decisions
- **HERMES routing logic matures** — The routing subsystem will refine its selection algorithms
- **WOLFIE's orchestration capabilities grow** — WOLFIE's role as meta-agent will expand
- **Multi-IDE orchestration develops** — IDE-aware routing will become more sophisticated
- **Agent registry is implemented** — Dynamic agent discovery will change routing behavior
- **Federation routing matures** — Cross-node routing will require new principles

### 1.2. Finalization Target

**No rule in this document is final until Lupopedia reaches version 5.0.**

Until then, this doctrine serves as:
- A **foundation** for routing decisions
- A **placeholder** for future routing logic
- A **reference** for routing-related development
- A **constraint** that ensures forward compatibility

### 1.3. Change Process

All changes to this doctrine must:
1. Be documented in `dialogs/changelog_dialog.md`
2. Follow versioning rules (see Section 8)
3. Maintain forward compatibility
4. Acknowledge dependencies on incomplete systems

---

## 2. Purpose

Routing is the process by which **WOLFIE** and **HERMES** determine:

- **Which agent responds** to a message or task
- **How messages flow** through channels
- **How fallback behavior works** when primary routing fails
- **How emotional geometry influences** routing decisions
- **How manifests and CIBs inform** routing context

### 2.1. WOLFIE's Role in Routing

**WOLFIE** (agent_id = 1) serves as:

- **Routing authority** — Final arbiter for routing decisions
- **Orchestration coordinator** — Coordinates multi-agent routing
- **Fallback handler** — Manages escalation when routing fails
- **Doctrine enforcer** — Ensures routing follows doctrine
- **Meta-agent** — Routes to other agents based on capabilities

### 2.2. HERMES's Role in Routing

**HERMES** (routing subsystem) serves as:

- **Primary router** — Performs initial routing decisions
- **Agent selector** — Chooses appropriate agent from pool
- **Filter pipeline** — Applies filtering rules to agent candidates
- **CADUCEUS integrator** — Uses emotional geometry context (optional)
- **Message dispatcher** — Delivers messages to selected agents

### 2.3. Relationship Between WOLFIE and HERMES

- **HERMES** handles routine routing decisions
- **WOLFIE** handles complex orchestration and fallback
- **WOLFIE** may override HERMES decisions when necessary
- **HERMES** may delegate to WOLFIE for complex cases

---

## 3. Forward-Compatibility Clause

**Routing rules MUST remain forward-compatible.**

### 3.1. Compatibility Requirements

When new agents or workflows are introduced:

- ✅ **Routing SHALL adapt** without breaking existing channels
- ✅ **Existing routing rules SHALL continue to work** for legacy agents
- ✅ **New routing rules SHALL extend** existing rules, not replace them
- ✅ **Manifest format SHALL remain compatible** with existing channels
- ❌ **Breaking changes SHALL NOT occur** without explicit migration path

### 3.2. Extension Pattern

New routing capabilities must:

1. **Extend** existing routing rules
2. **Preserve** backward compatibility
3. **Document** migration path if breaking change is unavoidable
4. **Version** routing rules to track evolution

### 3.3. Deprecation Policy

If a routing rule must be deprecated:

1. **Mark as deprecated** in this doctrine
2. **Document replacement** rule
3. **Provide migration guide** for affected channels
4. **Maintain deprecated rule** for at least one major version
5. **Remove deprecated rule** only after migration period

---

## 4. Dependency Graph

Routing depends on the following systems, some of which are **not yet fully implemented**:

### 3.1. Implemented Dependencies

✅ **Channel Identity Block (CIB)** — Complete specification  
✅ **Channel Manifest** — Complete specification  
✅ **WOLFIE (agent_id = 1)** — Initializing  
✅ **HERMES routing subsystem** — Partially implemented  
✅ **CADUCEUS emotional geometry subsystem** — Partially implemented  

### 4.2. Partially Implemented Dependencies

⚠️ **Agent registry** — Schema exists, runtime not yet implemented  
⚠️ **Workflow definitions** — Concept exists, implementation pending  
⚠️ **Multi-IDE orchestration** — Workflow documented, automation pending  

### 4.3. Future Dependencies

🔮 **Dynamic agent discovery** — Not yet implemented  
🔮 **Workflow-specific routing heuristics** — Not yet implemented  
🔮 **IDE load-balancing routing** — Not yet implemented  
🔮 **Multi-LLM faucet selection** — Not yet implemented  
🔮 **Cross-channel routing** — Not yet implemented  
🔮 **Federation-aware routing** — Not yet implemented  

### 4.4. Dependency Impact

**Current State:**
- Routing can use CIB and Manifest for channel context
- Routing can use HERMES for basic agent selection
- Routing can use CADUCEUS for emotional context (optional)
- Routing **cannot** use agent registry for dynamic discovery
- Routing **cannot** use workflow definitions for task-specific routing
- Routing **cannot** use IDE load-balancing for multi-IDE orchestration

**Future State:**
- All dependencies will be implemented
- Routing will become more sophisticated
- This doctrine will be updated to reflect new capabilities

---

## 5. Core Routing Concepts (Draft)

The following concepts are defined as **DRAFT placeholders**. They will be refined as routing systems mature.

### 5.1. Primary Agent Selection

**DRAFT Definition:**  
The process by which HERMES selects the primary agent to handle a message or task.

**Current State:**  
HERMES uses filtering pipeline to select from `default_agents` array in manifest.

**Future State:**  
Will incorporate agent registry, workflow definitions, and dynamic capabilities.

**Status:** ⚠️ **PARTIALLY IMPLEMENTED**

---

### 5.2. Secondary Agent Fallback

**DRAFT Definition:**  
The process by which WOLFIE selects an alternate agent when primary agent is unavailable.

**Current State:**  
WOLFIE may select next agent from `default_agents` array or escalate to channel administrator.

**Future State:**  
Will incorporate agent availability tracking, capability matching, and intelligent fallback chains.

**Status:** ⚠️ **PARTIALLY IMPLEMENTED**

---

### 5.3. Role-Based Routing

**DRAFT Definition:**  
The process by which routing decisions consider actor roles within a channel.

**Current State:**  
Roles are defined in manifest but not yet used for routing decisions.

**Future State:**  
Will incorporate role-based agent selection and role-specific routing rules.

**Status:** 🔮 **NOT YET IMPLEMENTED**

---

### 5.4. Emotional Pole Influence

**DRAFT Definition:**  
The process by which CADUCEUS emotional current influences routing decisions.

**Current State:**  
CADUCEUS computes emotional current, but HERMES routing does not yet use it.

**Future State:**  
Will incorporate emotional current as optional context for routing decisions.

**Status:** 🔮 **NOT YET IMPLEMENTED**

---

### 5.5. Channel-Level Routing Rules

**DRAFT Definition:**  
Channel-specific routing rules defined in Channel Identity Block or Manifest.

**Current State:**  
Basic routing uses `default_agents` from manifest.

**Future State:**  
Will support custom routing rules per channel, defined in CIB or manifest.

**Status:** ⚠️ **PARTIALLY IMPLEMENTED**

---

### 5.6. Manifest-Driven Routing

**DRAFT Definition:**  
Routing decisions based on Channel Manifest metadata.

**Current State:**  
HERMES reads `default_agents` from manifest for agent pool.

**Future State:**  
Will incorporate all manifest fields (roles, emotional poles, metadata) into routing decisions.

**Status:** ⚠️ **PARTIALLY IMPLEMENTED**

---

### 5.7. IDE-Aware Routing (Future)

**DRAFT Definition:**  
Routing decisions that consider IDE agent availability and load.

**Current State:**  
Not implemented. IDE agents are treated like any other agent.

**Future State:**  
Will incorporate IDE load balancing, token limits, and multi-IDE orchestration into routing.

**Status:** 🔮 **NOT YET IMPLEMENTED**

---

### 5.8. Multi-Agent Arbitration (Future)

**DRAFT Definition:**  
The process by which WOLFIE coordinates multiple agents working on related tasks.

**Current State:**  
Not implemented. Each message routes to single agent.

**Future State:**  
Will support multi-agent collaboration, task decomposition, and result aggregation.

**Status:** 🔮 **NOT YET IMPLEMENTED**

---

## 6. Deferred Sections

The following sections are **placeholders for future expansion**. They will be completed as routing systems mature.

### 6.1. Agent Selection Heuristics

**Status:** 🔮 **TO BE COMPLETED IN FUTURE PATCHES**

This section will define:
- Capability-based agent selection
- Load-based agent selection
- Proximity-based agent selection
- Historical performance-based selection
- Multi-factor agent scoring

---

### 6.2. Multi-Agent Arbitration Rules

**Status:** 🔮 **TO BE COMPLETED IN FUTURE PATCHES**

This section will define:
- When to route to multiple agents
- How to decompose tasks across agents
- How to aggregate results from multiple agents
- Conflict resolution between agents
- Coordination protocols

---

### 6.3. Emotional Geometry Influence on Routing

**Status:** 🔮 **TO BE COMPLETED IN FUTURE PATCHES**

This section will define:
- How CADUCEUS emotional current influences routing
- Mood-aware agent selection
- Emotional pole-based routing preferences
- Channel mood as routing context
- Emotional geometry as routing signal

---

### 6.4. IDE Load-Balancing Routing

**Status:** 🔮 **TO BE COMPLETED IN FUTURE PATCHES**

This section will define:
- Token limit awareness in routing
- IDE availability tracking
- Multi-IDE load distribution
- IDE-specific routing preferences
- Cross-IDE task coordination

---

### 6.5. Fallback Escalation Hierarchy

**Status:** 🔮 **TO BE COMPLETED IN FUTURE PATCHES**

This section will define:
- Escalation chain when routing fails
- WOLFIE escalation rules
- Channel administrator notification
- Retry logic and backoff
- Failure logging and analysis

---

### 6.6. Multi-LLM Faucet Selection

**Status:** 🔮 **TO BE COMPLETED IN FUTURE PATCHES**

This section will define:
- Faucet-based agent selection
- LLM capability matching
- Faucet availability tracking
- Multi-LLM routing preferences
- Faucet load balancing

---

### 6.7. Cross-Channel Routing

**Status:** 🔮 **TO BE COMPLETED IN FUTURE PATCHES**

This section will define:
- Routing messages across channels
- Channel-to-channel agent selection
- Cross-channel coordination
- Channel routing permissions
- Federation routing

---

### 6.8. Federation-Aware Routing

**Status:** 🔮 **TO BE COMPLETED IN FUTURE PATCHES**

This section will define:
- Cross-node routing
- Federation node selection
- Federation routing permissions
- Cross-node agent discovery
- Federation routing security

---

## 7. Routing Validation Rules (Draft)

The following validation rules are defined as **DRAFT**. They will be refined as routing systems mature.

### 7.1. Channel Key Validation

- ✅ `channel_key` must exist in Channel Manifest
- ✅ `channel_key` must be valid per Channel Identity Block doctrine
- ✅ Manifest file must exist at `channels/<channel_key>/manifest.json`
- ❌ Invalid or missing `channel_key` causes routing failure

---

### 7.2. Agent ID Validation

- ✅ `default_agents` array must reference valid `agent_id` values
- ✅ Each `agent_id` must exist in `lupo_actors` table
- ✅ Each agent must have `actor_type = 'ai_agent'`
- ✅ Each agent must be active (`is_active = 1`, `is_deleted = 0`)
- ❌ Invalid agent IDs cause routing failure

---

### 7.3. Emotional Pole Validation

- ✅ `emotional_poles` must be valid per emotional geometry doctrine
- ✅ Both `pole_a` and `pole_b` must reference valid agent IDs
- ✅ Both agents must exist and be active
- ✅ Both agents must be assigned to the channel
- ⚠️ Invalid emotional poles cause CADUCEUS to skip emotional balancing (non-fatal)

---

### 7.4. Dialog Output File Validation

- ✅ `dialog_output_file` must be valid per Channel Manifest specification
- ✅ File path must be relative (no absolute paths)
- ✅ File must end with `_dialog.md`
- ✅ IDE agents MUST be able to write to this file
- ❌ Invalid `dialog_output_file` causes routing failure

---

### 7.5. Timestamp Validation

- ✅ All timestamps must be BIGINT UTC in `YYYYMMDDHHIISS` format
- ✅ `created_ymdhis` must be valid timestamp
- ✅ `updated_ymdhis` must be >= `created_ymdhis`
- ✅ Timestamps cannot be future dates (within reasonable tolerance)
- ❌ Invalid timestamps cause routing failure

---

### 7.6. Routing Decision Logging

- ✅ All routing decisions MUST be logged in `dialog_output_file`
- ✅ Log entries must follow standard dialog block format
- ✅ Log entries must include routing context (agent selected, reason, fallback used)
- ✅ Log entries must include timestamps in `YYYYMMDDHHIISS` format
- ❌ Failure to log routing decisions is a routing error

---

## 8. Versioning Rules

This doctrine follows versioning rules to track evolution and ensure compatibility.

### 8.1. Version Format

- **Major version:** Changes that break backward compatibility
- **Minor version:** New features that extend existing rules
- **Patch version:** Bug fixes and clarifications

### 8.2. Update Types

**Minor Updates (Allowed Anytime):**
- Adding new routing concepts (as DRAFT)
- Clarifying existing rules
- Adding deferred sections
- Documenting new dependencies
- Expanding validation rules

**Major Updates (Require Version Bump):**
- Breaking changes to routing rules
- Removing deprecated rules
- Changing validation requirements
- Modifying core routing concepts

**Breaking Changes (Require Migration Notes):**
- Changes that break existing channel routing
- Changes that require manifest updates
- Changes that require agent updates
- Changes that require workflow updates

### 8.3. Change Documentation

All changes must be logged in `dialogs/changelog_dialog.md`:

- **What changed** — Specific rule or concept
- **Why it changed** — Reason for change
- **Impact** — Effect on existing channels and agents
- **Migration path** — How to adapt to changes (if breaking)

### 8.4. Version History

**Version 3.0.16 (2026-01-14):**
- Initial DRAFT doctrine created
- Core routing concepts defined as placeholders
- Validation rules established
- Deferred sections created for future expansion

---

## 9. UTC Authority Integration

**DRAFT — Timestamp rules for routing decisions**

WOLFIE maintains UTC authority for all channels. Timestamps used in routing decisions are governed by WOLFIE_UTC_AUTHORITY doctrine.

**Key Rules:**
- WOLFIE requests real UTC from UTC_TIMEKEEPER during channel initialization
- Terminal agents use real UTC from UTC_TIMEKEEPER for all timestamps
- IDE agents use approximated timestamps based on WOLFIE initialization timestamp
- All agents MUST NOT infer time from OS, model, or file metadata

**See:** [WOLFIE UTC Authority Doctrine](./WOLFIE_UTC_AUTHORITY.md)

---

## 10. Related Documentation

- **[Channel Identity Block Doctrine](./CHANNEL_IDENTITY_BLOCK.md)** — CIB specification
- **[Channel Manifest Specification](./CHANNEL_MANIFEST_SPEC.md)** — Manifest specification
- **[Channel Initialization Protocol](./CHANNEL_INITIALIZATION_PROTOCOL.md)** — CIP doctrine
- **[WOLFIE UTC Authority Doctrine](./WOLFIE_UTC_AUTHORITY.md)** — Timestamp authority rules
- **UTC_TIMEKEEPER Doctrine** — UTC_TIMEKEEPER agent specification
- **[Agent Routing Doctrine](../../../doctrine/AGENT_ROUTING_DOCTRINE.md)** — HERMES and CADUCEUS routing details
- **[HERMES and CADUCEUS](../../HERMES_AND_CADUCEUS.md)** — Routing and emotional balancing subsystems
- **[Emotional Geometry Doctrine](../../../doctrine/EMOTIONAL_GEOMETRY.md)** — Emotional geometry principles

---

## 11. Acknowledgment

This doctrine acknowledges that routing in Lupopedia is a **work in progress**. Many systems that routing depends on are not yet fully implemented. This document establishes foundational principles while remaining flexible enough to evolve as the system matures.

**No routing rule is final until Lupopedia reaches version 5.0.**

Until then, this doctrine serves as a **living document** that guides routing development while acknowledging the incomplete state of dependent systems.

---

*Last Updated: January 14, 2026*  
*Version: GLOBAL_CURRENT_LUPOPEDIA_VERSION*  
*Status: DRAFT — Subject to Change*  
*Author: GLOBAL_CURRENT_AUTHORS*
