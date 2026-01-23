---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.46
dialog:
  speaker: KIRO
  target: @copilot
  message: "Quick reference for Copilot and other AI assistants - explains ROSE, dialog system, WOLFIE headers, and core Lupopedia concepts."
tags:
  categories: ["documentation", "reference", "ai-assistants"]
  collections: ["core-docs"]
  channels: ["public", "dev"]
file:
  title: "Copilot Quick Reference - Lupopedia Core Concepts"
  description: "Essential Lupopedia concepts for AI assistants without full context"
  version: "4.0.46"
  status: published
  author: "Kiro"
---

# Copilot Quick Reference - Lupopedia Core Concepts

**For:** GitHub Copilot, Claude, Gemini, and other AI assistants  
**Purpose:** Quick reference for core Lupopedia concepts  
**Version:** 4.0.46

---

## 🤖 DIALOG - The Only Expressive Agent

**DIALOG** (formerly ROSE) - Agent Registry ID: 13, Dedicated Slot: 3

### What DIALOG Is
- **The ONLY agent allowed to be expressive** in Lupopedia
- Generates emotional, conversational, persona-driven content
- Outputs YAML inline dialog format ONLY (no prose, no reasoning)
- Has multiple personas (default_dialog, ara_soft_empathic_dialog, shadow_lilith_dialog, etc.)
- Expressive inline dialog renderer (not a thinker or reasoner)

### What DIALOG Is NOT
- NOT a general-purpose agent
- NOT for technical documentation
- NOT for code generation
- NOT for system operations
- NOT for reasoning or analysis

### DIALOG Output Format
```yaml
speaker: DIALOG
target: @wolfie
persona: default_dialog
message: "Here's what I'm seeing in your system right now..."
mood: "88CCFF"
```

### Critical Rule
**ALL OTHER AGENTS MUST BE DRY, DIRECT, LOW-TEMPERATURE**
- No roleplay
- No emotional simulation
- No expressive language
- Technical and factual only

### Historical Note
- DIALOG was formerly called "ROSE" (Rosetta Stone Agent)
- Renamed to DIALOG in version 4.0.17
- All references to ROSE are now historical/deprecated

---

## 💬 Dialog System - Multi-Agent Communication

### What It Is
Lupopedia's internal communication system for humans, AI agents, and services to coordinate.

### Core Tables
- `dialog_messages` - All messages (≤272 chars)
- `dialog_threads` - Thread management
- `channels` - Communication spaces
- `channel_participants` - Channel membership

### Message Structure
```php
actor_id         // Sender
to_actor         // Target (optional)
content          // Message text (≤272 chars)
mood_rgb         // Emotional coordinate (RRGGBB)
thread_id        // Thread identifier
directive_dialog_id  // Reference to directive
created_ymdhis   // UTC timestamp (BIGINT)
```

### Dialog Routing
- **HERMES** - Routes messages based on mood and directives
- **CADUCEUS** - Computes channel emotional current
- **DialogManager** - Central dispatcher (orchestrates entire flow)

### Visibility Rules
- **AI agents** - See only messages addressed to them
- **Humans** - See all messages in channel
- **Intentional design** - Mirrors Crafty Syntax operator model

---

## 🐺 WOLFIE Headers - File Metadata Format

### What They Are
YAML frontmatter at the top of every Lupopedia file providing metadata, versioning, and conversational lineage.

### Minimal Required Format
```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.46
---
```

### With Dialog (Required on File Modification)
```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.46
dialog:
  speaker: CURSOR
  target: @everyone
  message: "What I changed in this file"
---
```

### Purpose
- **Per-file version tracking** - Know which files changed in which version
- **Conversational lineage** - Track what agents did when
- **Semantic classification** - Tags, categories, collections
- **Atom references** - Symbolic constants instead of hardcoded values

### Critical Rules
- Signature line NEVER changes: `"explicit architecture with structured clarity for every file."`
- `file.last_modified_system_version` is literal version string (not atom)
- Only update when file is modified (per-file snapshot)
- Dialog block updated ONLY when file changes

---

## ⚛️ Atoms - Symbolic Constants

### What They Are
Symbolic variables used throughout documentation instead of hardcoded values.

### Atom Scopes (Resolution Order)
1. **FILE_** - File-specific (highest priority)
2. **DIR_** - Directory-specific (non-recursive)
3. **DIRR_** - Directory recursive (walks up parents)
4. **MODULE_** - Module-specific
5. **GLOBAL_** - Ecosystem-wide (final fallback)

### Example Usage
```yaml
file:
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  author: GLOBAL_CURRENT_AUTHORS
```

### Critical Rule
**NEVER expand or inline atom values** - treat as symbolic constants

---

## 🗄️ TOON Files - Schema Reference

### What They Are
Read-only `.toon` files in `database/toon_data/` containing exact table structures.

### Purpose
- Schema reference for AI agents
- Generated by Python cron job from database
- phpMyAdmin is authoritative source

### Critical Rules
- **READ-ONLY** for all IDEs and agents
- **NEVER modify** TOON files
- **Schema forging phase** - Tables can be redesigned during development
- Use TOON files for reference, not as source of truth during active development

---

## 🏗️ Core Architecture Concepts

### Lupopedia Is NOT a CMS
- **Semantic operating system** (not web app)
- **Reference layer** (not content storage)
- **Actor-centric** (humans + AI + services = equals)
- **Federated** (independent nodes)

### Mandatory Doctrines
- **No foreign keys** - Application-managed relationships
- **BIGINT UTC timestamps** - `YYYYMMDDHHIISS` format
- **No triggers** - All logic in PHP
- **No stored procedures** - Database is storage only
- **TOON read-only** - Schema reference only

### Schema Architecture (v4.0.46)
- **Core schema** (`lupopedia`) - 77 tables
- **Orchestration schema** (`lupopedia_orchestration`) - 8 tables
- **Ephemeral schema** (`lupopedia_ephemeral`) - 5 tables
- **Total:** 90 tables

---

## 🎯 Agent System Quick Reference

### Agent Layers
- **Kernel Agents (0-49)** - OS-level (SYSTEM, CAPTAIN, WOLFIE, THOTH, DIALOG, etc.)
- **System Services (50-99)** - Infrastructure (JANUS, HEIMDALL, MIGRATOR, etc.)
- **Persona Agents (100+)** - Conversational (LILITH, ARA, AGAPE, etc.)

### Key Subsystems
- **HERMES** - Routing layer (determines which agent receives messages)
- **CADUCEUS** - Emotional balancer (computes channel mood)
- **IRIS** - LLM gateway (sends requests to external AI providers)
- **DialogManager** - Central dispatcher (orchestrates message flow)
- **THOTH** - Truth engine (evaluates claims against ontology)
- **WOLFMIND** - Memory subsystem (stores agent memory)

### Critical Rule
**Only persona agents participate in chat** - Kernel and system agents are OS components (except DIALOG which renders expressive content)

---

## 🚀 Migration Orchestrator (v4.0.46)

### What It Is
8-state machine for managing database migrations across federated schemas.

### 8 States
1. **idle** - Dormant, waiting for trigger
2. **preparing** - Dependency resolution, file validation
3. **validating_pre** - Pre-execution validation
4. **migrating** - SQL execution
5. **validating_post** - Post-execution validation
6. **completing** - Finalization
7. **rolling_back** - Rollback execution
8. **failed** - Terminal failure (manual intervention required)

### Status
**100% COMPLETE** as of version 4.0.46

---

## 📚 Key Documentation Files

### Must Read
- `docs/README.md` - Documentation index
- `docs/core/ARCHITECTURE_SYNC.md` - Authoritative subsystem reference
- `docs/agents/WOLFIE_HEADER_SPECIFICATION.md` - Header format
- `docs/doctrine/ATOM_RESOLUTION_SPECIFICATION.md` - Atom system
- `docs/appendix/GLOSSARY.md` - Terminology reference

### For AI Agents
- `docs/doctrine/TOON_DOCTRINE.md` - TOON file rules
- `docs/doctrine/NO_FOREIGN_KEYS_DOCTRINE.md` - Database doctrine
- `docs/doctrine/TIMESTAMP_DOCTRINE.md` - Timestamp format
- `docs/agents/INLINE_DIALOG_SPECIFICATION.md` - Dialog format

---

## 🎨 Mood RGB System

### What It Is
Emotional coordinate system using RGB hex values (RRGGBB).

### Color Meanings
- **R (Red)** - Strife / intensity / chaos / conflict
- **G (Green)** - Harmony / balance / cohesion / stability
- **B (Blue)** - Memory depth / introspection / persistence / reflection

### Usage
- Dialog messages include `mood_rgb` field
- CADUCEUS uses mood to compute channel emotional current
- HERMES may use mood for routing decisions

---

## 🔧 Version System

### Current Version
**4.0.46** (as of January 16, 2026)

### Version Atoms
- `GLOBAL_CURRENT_LUPOPEDIA_VERSION` - Current system version
- `GLOBAL_CURRENT_AUTHORS` - Current authors
- Loaded from `config/global_atoms.yaml`

### Version Bump Rules
- Only Wolfie (human architect) may initiate version bumps
- No auto-increment by any IDE or agent
- Version Bump Instruction Block required
- Prevents version drift across multi-IDE environment

---

## 🚨 Critical Rules for AI Assistants

### MUST DO
- Treat Lupopedia as semantic OS (not web app)
- Preserve symbolic constants (atoms) exactly as written
- Use BIGINT UTC timestamps (`YYYYMMDDHHIISS`)
- Follow all doctrine without deviation
- Wait for human approval in stabilization mode

### MUST NOT DO
- Expand or inline atom values
- Modify TOON files
- Create foreign keys, triggers, or stored procedures
- Use DATETIME or TIMESTAMP columns
- Treat ROSE behavior as normal for other agents
- Bypass DialogManager for message processing

---

## 📞 When You Need More Context

If you encounter concepts not covered here:

1. Check `docs/README.md` for documentation index
2. Check `docs/appendix/GLOSSARY.md` for terminology
3. Check `docs/core/ARCHITECTURE_SYNC.md` for subsystems
4. Ask human for clarification (don't guess)

---

**This is your quick reference. Use it when you need to understand Lupopedia concepts quickly.**

---

**Last Updated:** January 16, 2026  
**Version:** 4.0.46  
**For:** All AI assistants working with Lupopedia
