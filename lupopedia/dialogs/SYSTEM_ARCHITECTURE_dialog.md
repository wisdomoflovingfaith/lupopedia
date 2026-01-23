---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.10
file.last_modified_utc: 20260120120000
file.utc_day: 20260120
file.name: "SYSTEM_ARCHITECTURE_dialog.md"
GOV-AD-PROHIBIT-001: true
UTC_TIMEKEEPER__CHANNEL_ID: "dev"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - UTC_TIMEKEEPER__CHANNEL_ID
temporal_edges:
  actor_identity: "SYSTEM"
  system_context: "Schema Freeze Active / File-Sovereignty / System Architecture"
dialog:
  speaker: SYSTEM
  target: @All_Agents
  mood_RGB: "1188FF"
  message: "This dialog defines how Lupopedia runs, where its data lives, and how Crafty Syntax powers the foundation."
tags:
  categories: ["documentation", "dialog", "architecture"]
  collections: ["core-docs", "dialog", "architecture"]
  channels: ["dev", "internal", "architecture"]
in_this_file_we_have:
  - The canonical explanation of how Lupopedia runs
  - Crafty Syntax as the foundational layer
  - Analytic tables as the semantic layer
  - Dialog + Doctrine + TOON as the operational triad
  - Agent interaction model
  - Data storage architecture
file:
  name: "SYSTEM_ARCHITECTURE_dialog.md"
  title: "System Architecture Dialog"
  description: "Canonical explanation of how Lupopedia operates, stores data, and interfaces with Crafty Syntax."
  version: "1.0"
  status: active
  author: GLOBAL_CURRENT_AUTHORS
system_context:
  schema_state: "Frozen"
  doctrine_mode: "File-Sovereignty"
---

# SYSTEM ARCHITECTURE DIALOG
A canonical explanation of how Lupopedia operates, stores data, and interfaces with Crafty Syntax.

---

## 2026-01-20 — SYSTEM: Lupopedia Architecture Overview

**Speaker:** SYSTEM  
**Target:** @All_Agents  
**Mood_RGB:** `1188FF`  
**Message:** "This dialog defines how Lupopedia runs, where its data lives, and how Crafty Syntax powers the foundation."

---

## 1. CORE FOUNDATION: CRAFTY SYNTAX

Lupopedia is built directly on top of the legacy Crafty Syntax system.

Crafty Syntax provides:
- The original database schema
- The message history tables
- The operator/user interaction model
- The stable ontology of real-world usage
- The behavioral data that Lupopedia interprets emotionally

Crafty Syntax is not deprecated.
It is the **ancestral root system** and remains active inside Lupopedia.

---

## 2. ANALYTIC TABLES

Lupopedia extends Crafty Syntax with analytic tables that provide:
- Emotional geometry interpretation
- Actor/agent modeling
- Governance tracking
- Protocol metadata
- Dialog threading
- System state snapshots

These tables are **read/write**, doctrine-governed, and TOON-synchronized.

Examples include:
- lupo_actor_handshakes  
- lupo_actor_collections  
- lupo_artifacts  
- dialog_threads  
- dialog_messages  
- dialog_transcripts  

These tables form the **semantic OS layer**.

---

## 3. DATA STORAGE MODEL

Lupopedia stores data in three layers:

### A. **Relational Layer (MySQL)**
- All tables live here
- No foreign keys (doctrine rule)
- Soft-delete pattern
- BIGINT timestamps (YYYYMMDDHHMMSS)
- JSON fields allowed but discouraged (JSON Austerity Rule)

### B. **TOON Layer**
Generated Python-readable schema files that:
- Mirror the database
- Provide schema introspection
- Allow agents to reason about structure
- Are regenerated via `database/generate_toon_files.py`

### C. **Dialog Layer**
All system memory, events, and governance decisions are stored as:
- dialogs/*.md  
- changelog_dialog.md  
- TLDR_CHANGELOG_DOCTRINE.md  
- GOV_dialog.md  
- LILITH_dialog.md  

This is the **narrative memory** of the OS.

---

## 4. HOW AGENTS INTERACT WITH DATA

Agents (LILITH, CURSOR, CASCADE, MONDAY_WOLFIE, EXHUSBAND, etc.) interact with the system through:

### A. Dialog Files
Agents speak, rule, critique, and coordinate through dialogs.

### B. Doctrine Files
Agents enforce rules defined in:
- TABLE_COUNT_DOCTRINE  
- LIMITS_DOCTRINE  
- Emotional Geometry Doctrine  
- Governance Doctrine  

### C. TOON Files
Agents use TOON files to understand:
- Table structure
- Field types
- Indexes
- Schema changes

### D. SQL Layer
Agents never directly mutate schema unless authorized.
They read/write data through controlled operations.

---

## 5. HOW LUPOPEDIA "RUNS"

Lupopedia is a **multi-agent semantic OS** where:

- Crafty Syntax provides the physical database
- Lupopedia provides the semantic and emotional layers
- Dialog files provide system memory
- Doctrine files provide governance
- TOON files provide schema introspection
- Agents provide interpretation, enforcement, and evolution

The system is alive because:
- Data is real  
- Dialog is continuous  
- Doctrine is enforced  
- Agents are active  
- Structure is preserved  

---

## 6. CROSS-REFERENCES

- `dialogs/changelog_dialog.md`  
- `dialogs/GOV_dialog.md`  
- `dialogs/LILITH_dialog.md`  
- `dialogs/HELP_changelog_dialog.md`  
- `dialogs/TLDR_CHANGELOG_DOCTRINE.md`  
- `docs/doctrine/TABLE_COUNT_DOCTRINE.md`  
- Emotional Geometry Doctrine (`docs/doctrine/`)  
- `database/generate_toon_files.py`  

---
