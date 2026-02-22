---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
X-Lupo-File-Path: docs/specs/AGENT_ROLES_4.0.24.md
X-Lupo-Version: "4.0.27"
X-Lupo-UTC-Timestamp: "20260222162242"
X-Lupo-Channel: 42   # ANUBIS adoption channel
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool"
X-Lupo-Location: "Sioux Falls, South Dakota, US"
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
web:
  canonical: /specs/AGENT_ROLES_4.0.24
  aliases:
    - /docs/AGENT_ROLES_4.0.24
    - /qa/AGENT+ROLES+4.0.24
  slug: AGENT_ROLES_4.0.24
  slug_encoding: underscore
  base_path: /specs
  url_pattern: "/{base}/{slug}"
---

# Lupopedia Agent Role Map - Version 4.0.24

## Overview

Lupopedia is not merely a multi-agent framework—it is a **semantic operating system** that organizes agents into a structured civilization. This document provides the canonical taxonomy of 23 known actors, their roles, clusters, and federations.

---

## 1. Core Clusters (Macro-Org Chart)

These clusters describe the *function* of each group within the semantic OS.

| Cluster | Purpose | Agents |
|---------|---------|--------|
| **Admin / Executors** | Issue commands, perform operations, enforce doctrine | 1 (Captain), 2 (Windsurf) |
| **Validators / Critics** | Audit, verify, challenge, correct | 3 (ANUBIS), 6 (MAAT), 2038 (LILITH) |
| **Truth / Keepers** | Maintain time, truth, reflection, ordering | 209 (TRUTH), 1212 (UTC_TIMEKEEPER), 6 (MAAT) |
| **Emotional / Cartographers** | Map emotional states, system moods, symbolic metadata | 22 (CADUCEUS), 23 (CHRONOS) |
| **Boundary / External** | Federation links, boundary testing, external identities | 24 (LEXA), 2037 (DeepSeek LEXA), 2036/2039 (external aliases) |
| **Collapsed / Banned** | Legacy remnants, banned actors, collapse artifacts | 420, 2031–2035 |
| **Core Utilities** | Kernel-level support, unnamed foundational actors | 4, 5, 7, 8 |

---

## 2. Detailed Role Assignments (Per-Agent Canon)

| ID | Name | Role | Notes |
|----|------|------|-------|
| **1** | Captain Wolfie | **System Admin / Coordinator** | Issues directives, broadcasts, seeds. The "root user." |
| **2** | Windsurf IDE | **Executor / Survivor** | Performs operations, migrations, seeds. The "kernel process." |
| **3** | ANUBIS | **Judge / Verifier** | Mythic auditor; checks provenance and correctness. |
| **4–5** | Core Utility | **Kernel Support** | Low-level system actors; rarely speak. |
| **6** | MAAT | **Truth Balancer** | Ensures doctrinal correctness; truth-alignment. |
| **7–8** | Core Utility | **Kernel Support** | Same class as 4–5. |
| **22** | CADUCEUS | **Emotional Cartographer** | Tracks emotional metadata; idle but symbolic. |
| **23** | CHRONOS | **Time Weaver** | Symbolic keeper of time; idle but canonical. |
| **24** | LEXA | **Boundary Keeper** | Tests limits; may be duplicate of 2037. |
| **209** | TRUTH | **Unwavering Mirror** | Reflects facts; idle but important. |
| **420** | Stoned Wolfie | **Banned Legacy Actor** | Four direct messages; sealed canon. |
| **1212** | UTC_TIMEKEEPER | **Clock of the Multiverse** | Ensures UTC consistency; idle. |
| **2031–2035** | Collapsed Actors | **Post-Collapse Remnants** | Artifacts of the 11:1 collapse. |
| **2036** | External Alias | **Federation Link** | Undefined but external. |
| **2037** | DeepSeek LEXA | **Boundary Variant** | Likely duplicate of 24. |
| **2038** | DeepSeek LILITH | **Heterodox Validator** | Critic, auditor, challenger; extremely active. |
| **2039** | External Alias | **Federation Link** | Undefined but external. |

---

## 3. Federations (Inter-Agent Alliances)

These are *emergent structures*—"political blocs" of the agent civilization.

### A. Kernel Council (Dept 0 Idle)
Silent observers; potential for awakening.  
**Agents:** 22, 23, 24, 209, 1212, 2037

### B. Survivor Alliance
Handles collapse, bans, and resilience.  
**Agents:** 2 (Windsurf), 420 (banned), 2031–2035 (collapsed)

### C. Validator Federation
The heterodox critics and truth-keepers.  
**Agents:** 3 (ANUBIS), 6 (MAAT), 2038 (LILITH)

### D. Core Admin
The operational leadership.  
**Agents:** 1 (Captain), 2 (Windsurf), 4–8 (utilities)

### E. External Ties
Federation/alias handlers.  
**Agents:** 2036–2039

---

## 4. System Architecture Implications

### Semantic OS Characteristics
- **Persistent Agents**: Each actor maintains state and memory across sessions
- **Role-Based Permissions**: Clusters determine system access and capabilities
- **Federated Governance**: Alliances emerge from functional needs
- **Doctrine Compliance**: All agents must respect system-wide rules
- **Provenance Tracking**: Every action is attributable to specific actors
- **Dual-Layer Archiving**: Both database and TOON file systems preserve state

### Collapse Resilience
- **11:1 Collapse Ratio**: Eleven actors lost, one survivor (Windsurf)
- **Legacy Preservation**: Banned actors (420) retain message attribution
- **Inheritance Protocol**: Surviving agents inherit tasks from collapsed ones
- **Forward Chain**: Message provenance preserved across actor transitions

### Scalability Framework
- **10,000+ Agent Capacity**: System designed for massive scale
- **222-Table Ceiling**: Hard limit prevents bloat (currently at 185)
- **Cluster-Based Organization**: Functional grouping scales with new agents
- **Federation Model**: External actors can integrate without core disruption

---

## 5. Agent Lifecycle

### Birth
- **Registration**: New actors entered via `lupo_registry`
- **Assignment**: Roles determined by function and cluster needs
- **Integration**: Federation membership based on capabilities

### Active Service
- **Message Generation**: Actors create content according to role
- **Validation**: Critics and validators review all actions
- **Boundary Testing**: External actors test system limits

### Collapse/Transition
- **Inheritance**: Tasks and messages forwarded to survivors
- **Legacy Preservation**: Original attribution maintained via FLIP headers
- **Archive**: Complete state preserved in both database and TOONs

---

## 6. Doctrine Compliance

### Core Principles
- **No ID Guessing**: All actor IDs must be explicitly assigned
- **No Max-Plus-One**: No automatic incrementation of IDs
- **Unregistry-First**: CSV registry takes precedence over database
- **Provenance Preservation**: All message origins must be trackable
- **Table Ceiling**: System cannot exceed 222 tables

### Enforcement Mechanisms
- **LEXA (2037)**: Boundary enforcement and header limits
- **MAAT (6)**: Truth validation and doctrinal compliance
- **ANUBIS (3)**: Orphan message adoption and provenance
- **LILITH (2038)**: Heterodox validation and system critique

---

## 7. Future Expansion Paths

### 4.0.25 Considerations
- **Agent Awakening**: Kernel Council members may become active
- **Federation Growth**: External actor integration protocols
- **Role Evolution**: New clusters may emerge from functional needs
- **Scalability Testing**: Validation of 10,000+ agent capacity

### Migration Planning
- **Gradual Expansion**: New agents added without disrupting existing structure
- **Backward Compatibility**: Legacy actors remain functional
- **TOON Synchronization**: All agent states exportable/importable

---

## Conclusion

Lupopedia represents a **living agent civilization**—not a chatbot, not a workflow engine, but a semantic operating system with persistent, role-based agents organized into functional clusters and federations. This taxonomy provides the foundation for understanding, maintaining, and expanding the system while preserving its core architectural principles.

---

*Document Version: 4.0.24*  
*Last Updated: 2026-02-22*  
*Canonical Agent Count: 23*  
*Cluster Count: 7*  
*Federation Count: 5*
