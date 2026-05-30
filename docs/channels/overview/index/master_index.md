> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/overview/index/MASTER_INDEX.md"
  file_hash: "fbd5b35b839806355dbcc4fb105bc1e06aae1e44b2002d632f00643c7d2ab182"
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
  file_path_from_root: "docs\channels\overview\index\MASTER_INDEX.md"
  file_hash: "ec89b5a7a9b3de38d62a684f90ecd46811bf33ab7fed28035589541618abd7ef"
  file_path_from_root: "docs\channels\overview\index\MASTER_INDEX.md"
  file_hash: "9b8357c4799180dcbb1d3b03e511da1b1ee37313f9faf5666c3817be30f83ff1"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for MASTER_INDEX.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "overview", "index", "master_indexmd"]
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
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.15
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  speaker: "CASCADE"
  target: "@everyone"
  mood_vector: "00FF00"
  message: "Created Master Index v1.0 - comprehensive index of all Lupopedia documentation"
tags:
  categories: ["documentation", "index", "master"]
  collections: ["core-docs", "index"]
  channels: ["public", "dev", "standards"]
file:
  title: "Lupopedia Master Index v1.0"
  description: "Comprehensive index of all Lupopedia documentation and specifications"
  version: "1.0.0"
  status: published
  author: "Captain Wolfie"
---

# ðŸ“š **Lupopedia Master Index v1.0**  
*Comprehensive index of all Lupopedia documentation and specifications*

---

## ðŸŸ© 1. Purpose

This master index provides **complete navigation** to all Lupopedia documentation, organized by category and purpose. It serves as the central hub for finding any specification, protocol, or reference document in the ecosystem.

---

## ðŸ“‚ 2. Documentation Structure

### Core Documentation (`/docs/`)

```
docs/
+-- README.md                    # Main documentation index
+-- core/                       # Core architecture and design
+-- modules/                    # First-party modules and integrations
+-- doctrine/                   # Mandatory rules and principles
+-- agents/                     # AI agent system and communication
+-- schema/                     # Database schema and reference
+-- dev/                        # Installation and development guides
+-- protocols/                  # Communication and synchronization
+-- history/                    # Project history and lineage
+-- appendix/                   # Reference materials
+-- systems/                    # System specifications
+-- registries/                 # Governed lists and definitions
+-- index/                      # Index and navigation files
```

---

## ðŸ“œ 3. Doctrine Documents

### Core Doctrines (`/docs/doctrine/`)

- **[Universal Wolfie Header Specification](../../doctrine/UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md)** - Minimal universal metadata standard
- **[Lupopedia Header Profile](../../doctrine/LUPOPEDIA_HEADER_PROFILE.md)** - Expressive metadata extensions
- **[Doctrines README](../../doctrine/doctrines/README.md)** - Doctrine overview and relationships

### Emotional System Doctrines (`/docs/doctrines/`)

- **[Mood System Doctrine](../../doctrine/doctrines/MOOD_SYSTEM_DOCTRINE.md)** - Multi-dimensional emotional representation
- **[Color Doctrine](../../doctrine/doctrines/COLOR_DOCTRINE.md)** - RGB mapping for emotional visualization
- **[Mood Calculation Protocol](../../doctrine/doctrines/MOOD_CALCULATION_PROTOCOL.md)** - Deterministic emotional computation
- **[Thread Aggregation Protocol](../../doctrine/doctrines/THREAD_AGGREGATION_PROTOCOL.md)** - Emotional accumulation across conversations

---

## ðŸ“‹ 4. Registry Documents

### Emotional Registries (`/docs/registries/`)

- **[Mood Axis Registry](../../kernel/registries/MOOD_AXIS_REGISTRY.md)** - Canonical emotional axis definitions

---

## ðŸ¤– 5. Agent Documentation

### Agent Specifications (`/docs/agents/`)

- **[Agent Guidelines](../../agents/AGENT_GUIDELINES.md)** - Agent behavior and implementation requirements
- **[Dialog History Specification](../../dialogs/agents/DIALOG_HISTORY_SPEC.md)** - Per-file dialog history management
- **[Thread-Level Dialog Specification](../../dialogs/agents/THREAD_LEVEL_DIALOG_SPEC.md)** - Thread-wide dialog management
- **[Agents README](../../agents/README.md)** - Agent system overview

---

## âš™ï¸ 6. System Specifications

### Core Systems (`/docs/systems/`)

- **[Experience Ledger](../../kernel/systems/EXPERIENCE_LEDGER.md)** - Immutable event log for system evolution
- **[Heterodox Engine](../../kernel/systems/HETERODOX_ENGINE.md)** - Controlled mechanisms for doctrinal evolution
- **[CRF Specification](../../kernel/systems/CRF_SPECIFICATION.md)** - High-dimensional context vector specification
- **[Affective Discrepancy Engine](../../kernel/systems/AFFECTIVE_DISCREPANCY_ENGINE.md)** - Emotional mismatch detection

---

## ðŸ”— 7. Protocol Documents

### Communication Protocols (`/docs/protocols/`)

- **[WOLFIE Header RFC](../../architecture/protocols/WOLFIE_HEADER_RFC.md)** - Formal RFC for header metadata standard

---

## ðŸ“– 8. Reference Documentation

### Core References (`/docs/core/`)

- **[Definition](../DEFINITION.md)** - Lupopedia ecosystem definition
- **[Architecture](../../architecture/ARCHITECTURE.md)** - System architecture overview

### Development References (`/docs/dev/`)

- **Getting Started** - Installation and setup guide
- **Development Guide** - Development best practices

---

## ðŸŽ­ 9. New Subsystems Added (January 2026)

The following subsystems have been added to the Lupopedia architecture:

- **CRF Specification** - High-dimensional context vector for implicit emotional fingerprinting
- **Affective Discrepancy Engine** - Emotional mismatch detection between RGB and ATP
- **Experience Ledger** - Immutable event log for doctrinal mutations and consensus
- **Heterodox Engine** - Controlled mechanisms for doctrinal evolution and meta-governance
- **Meta-Governance Extensions** - Optional LHP field for heterodox proposal workflows
- **Dual-Channel Affective Stack** - Mood Vector and ATP integration for comprehensive emotional representation

### Integration Status

All new subsystems are fully integrated with:
- **Existing doctrine framework**
- **Header and metadata systems**
- **Agent communication protocols**
- **Documentation and versioning**

---

## ðŸ” 10. Navigation Guide

### Quick Access

- **For implementers**: Start with [Doctrines README](../../doctrine/doctrines/README.md)
- **For agents**: Review [Agent Guidelines](../../agents/AGENT_GUIDELINES.md)
- **For developers**: See Getting Started
- **For system architects**: Read [Core Architecture](../../architecture/ARCHITECTURE.md)

### Search Strategy

1. **Identify category** (doctrine, system, agent, etc.)
2. **Locate specific document** in relevant section
3. **Follow cross-references** for related information
4. **Check implementation resources** for integration details

---

## ðŸŒ 11. Scope and Versioning

This is **Master Index v1.0** (January 2026).

It applies to all Lupopedia documentation organization and navigation.

Future versions may add new documents or reorganize structure, but core categorization principles are immutable.

---

## ðŸ”— 12. External References

### Canonical URLs

All major specifications have canonical URLs:
- **WHS**: https://lupopedia.com/what/WHS
- **LHP**: https://lupopedia.com/what/LHP
- **Mood System**: https://lupopedia.com/what/mood_system
- **Color Doctrine**: https://lupopedia.com/what/color_doctrine
- **Mood Axes**: https://lupopedia.com/what/mood_axes
- **Thread Aggregation**: https://lupopedia.com/what/thread_aggregation
- **Mood Calculation**: https://lupopedia.com/what/mood_calculation

### Related Resources

- **Global Atoms**: `../config/global_atoms.yaml`
- **Database Schema**: `../database/`
- **Legacy Documentation**: `../legacy/`

---

*Last Updated: January 13, 2026*  
*Version: 1.0.0*  
*Author: Captain Wolfie*  
*Canonical Reference: https://lupopedia.com/what/master_index*
