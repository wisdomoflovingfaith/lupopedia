> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/doctrine/WHS_LHP_INDEX.md"
  file_hash: "f84c99e08786dac728ea6d41d0c0df8d4de53e4d7ec794566823fcb983613052"
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
  file_path_from_root: "docs\channels\doctrine\WHS_LHP_INDEX.md"
  file_hash: "b3d12b1055a2f0430250115c997c0e1e20d095ff5c4af4ca444e44310fd1fdc4"
  file_path_from_root: "docs\channels\doctrine\WHS_LHP_INDEX.md"
  file_hash: "8914491a9c6f726f4cce57e3d1dd9927f65dde8168636ce21e8f10a2727e69be"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for WHS_LHP_INDEX.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "whs_lhp_indexmd"]
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
  message: "Created WHS/LHP Index Page - comprehensive index of header standards and mood system extensions"
tags:
  categories: ["documentation", "index", "standards"]
  collections: ["core-docs"]
  channels: ["public", "dev", "standards"]
file:
  title: "WHS/LHP Index Page"
  description: "Comprehensive index of header standards and mood system extensions for Lupopedia"
  version: "1.0.0"
  status: published
  author: "Captain Wolfie"
---

# 📋 **WHS/LHP Index Page**  
*Comprehensive index of header standards and mood system extensions for Lupopedia*

---

## 🟩 1. Core Header Standards

### Universal Wolfie Header Specification (WHS)

**File**: `docs/doctrine/UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md`  
**Description**: Universal minimal core with 2 required fields  
**Status**: Stable foundation for all header profiles  
**Key Features**:
- Universal identity marker (`wolfie.headers`)
- Per-file version tracking (`file.last_modified_system_version`)
- Optional dialog block for multi-agent systems
- Ecosystem-agnostic minimalism
- Canonical truth: https://lupopedia.com/what/WHS

### Lupopedia Header Profile (LHP)

**File**: `docs/doctrine/LUPOPEDIA_HEADER_PROFILE.md`  
**Description**: Lupopedia's official extension of WHS with expressive metadata  
**Status**: Required for all Lupopedia files  
**Key Features**:
- Extends WHS with Lupopedia-specific fields
- Required dialog block for multi-agent coordination
- Optional fields for rich metadata
- Semantic graph integration
- Rewrite safety and drift prevention doctrines
- Canonical truth: https://lupopedia.com/what/LHP

---

## 🎭 2. Mood System Extensions (January 2026)

### Mood System Doctrine

**File**: `docs/doctrines/MOOD_SYSTEM_DOCTRINE.md`  
**Description**: Vectorized, nested mood blocks with multi-axis emotional geometry  
**Purpose**: Multi-dimensional emotional representation for complex agent interactions  
**Key Features**:
- Multi-axis emotional vectors (bipolar, unipolar, cyclical)
- Nested mood structure (primary, secondary, meta, thread_summary)
- Reflective calculation from dialog content
- Thread aggregation support
- Canonical truth: https://lupopedia.com/what/mood_system

### Mood Axis Registry

**File**: `docs/registries/MOOD_AXIS_REGISTRY.md`  
**Description**: Governed list of emotional axes preventing drift and synonyms  
**Purpose**: Canonical definitions for all emotional dimensions  
**Key Features**:
- 6 core emotional axes (dialog, love_hate, focus, memory, energy, time)
- Governed addition/modification process
- Axis type definitions (bipolar, unipolar, cyclical)
- Validation rules and examples
- Canonical truth: https://lupopedia.com/what/mood_axes

### RGB Mapping Protocol (Color Doctrine)

**File**: `docs/doctrines/COLOR_DOCTRINE.md`  
**Description**: Defines how emotional scores map to mood vectors  
**Purpose**: Visual emotional communication and interface integration  
**Key Features**:
- Standard RGB format ("XXYYZZ")
- Axis-to-color mapping algorithms
- Color blending and aggregation rules
- Special color cases (neutral, maximum, conflict)
- Canonical truth: https://lupopedia.com/what/color_doctrine

### Mood Calculation Protocol

**File**: `docs/doctrines/MOOD_CALCULATION_PROTOCOL.md`  
**Description**: Deterministic, governed mood computation from dialog text  
**Purpose**: Consistent mood calculation across all agents  
**Key Features**:
- Axis-specific scoring algorithms
- Deterministic computation (no creativity)
- Normalization and validation rules
- Integration with existing mood state
- Performance and testing requirements
- Canonical truth: https://lupopedia.com/what/mood_calculation

### Thread Aggregation Protocol

**File**: `docs/doctrines/THREAD_AGGREGATION_PROTOCOL.md`  
**Description**: Mood accumulation across dialogs with thread_summary blocks  
**Purpose**: Thread-level emotional tracking and analysis  
**Key Features**:
- Temporal weighting system (recent = higher weight)
- Convergence and divergence detection
- Thread summary generation
- Integration with thread-level dialog files
- Emotional trajectory tracking
- Canonical truth: https://lupopedia.com/what/thread_aggregation

---

## 📝 3. Dialog System Specifications

### Dialog History File Specification

**File**: `docs/agents/DIALOG_HISTORY_SPEC.md`  
**Description**: Standard for `<filename>_dialog.md` files in Lupopedia  
**Purpose**: Per-file dialog history management  
**Key Features**:
- Markdown-only format (no YAML in history)
- Newest entries at top
- UTC timestamp requirements
- Header vs history separation
- Append-only philosophy

### Thread-Level Dialog Specification

**File**: `docs/agents/THREAD_LEVEL_DIALOG_SPEC.md`  
**Description**: Standard for `/dialogs/<threadname>_dialog.md` files  
**Purpose**: Thread-wide dialog management  
**Key Features**:
- Thread-level narrative capture
- Session-wide conversational ledger
- Distinction from per-file dialog
- Markdown format with UTC timestamps
- Newest-at-top ordering

---

## 🎭 2. Mood System Extensions (January 2026)

### Mood System Doctrine

**File**: `docs/doctrines/MOOD_SYSTEM_DOCTRINE.md`  
**Description**: Vectorized, nested mood blocks with multi-axis emotional geometry  
**Purpose**: Multi-dimensional emotional representation for complex agent interactions  
**Key Features**:
- Multi-axis emotional vectors (bipolar, unipolar, cyclical)
- Nested mood structure (primary, secondary, meta, thread_summary)
- Reflective calculation from dialog content
- Thread aggregation support
- Canonical truth: https://lupopedia.com/what/mood_system

### Mood Axis Registry

**File**: `docs/registries/MOOD_AXIS_REGISTRY.md`  
**Description**: Governed list of emotional axes preventing drift and synonyms  
**Purpose**: Canonical definitions for all emotional dimensions  
**Key Features**:
- 6 core emotional axes (dialog, love_hate, focus, memory, energy, time)
- Governed addition/modification process
- Axis type definitions (bipolar, unipolar, cyclical)
- Validation rules and examples
- Canonical truth: https://lupopedia.com/what/mood_axes

### RGB Mapping Protocol (Color Doctrine)

**File**: `docs/doctrines/COLOR_DOCTRINE.md`  
**Description**: Defines how emotional scores map to mood vectors  
**Purpose**: Visual emotional communication and interface integration  
**Key Features**:
- Standard RGB format ("XXYYZZ")
- Axis-to-color mapping algorithms
- Color blending and aggregation rules
- Special color cases (neutral, maximum, conflict)
- Canonical truth: https://lupopedia.com/what/color_doctrine

### Mood Calculation Protocol

**File**: `docs/doctrines/MOOD_CALCULATION_PROTOCOL.md`  
**Description**: Deterministic, governed mood computation from dialog text  
**Purpose**: Consistent mood calculation across all agents  
**Key Features**:
- Axis-specific scoring algorithms
- Deterministic computation (no creativity)
- Normalization and validation rules
- Integration with existing mood state
- Performance and testing requirements
- Canonical truth: https://lupopedia.com/what/mood_calculation

### Thread Aggregation Protocol

**File**: `docs/doctrines/THREAD_AGGREGATION_PROTOCOL.md`  
**Description**: Mood accumulation across dialogs with thread_summary blocks  
**Purpose**: Thread-level emotional tracking and analysis  
**Key Features**:
- Temporal weighting system (recent = higher weight)
- Convergence and divergence detection
- Thread summary generation
- Integration with thread-level dialog files
- Emotional trajectory tracking
- Canonical truth: https://lupopedia.com/what/thread_aggregation

### Experience Ledger

**File**: `docs/systems/EXPERIENCE_LEDGER.md`  
**Description**: Immutable event log for doctrinal mutations, consensus outcomes, and semantic drift observations  
**Purpose**: Historical traceability for system evolution  
**Key Features**:
- Records doctrinal mutation proposals
- Documents affective discrepancy events
- Tracks sanctioned instability cycles
- Captures consensus outcomes
- Serves as long-term memory substrate

### Heterodox Engine

**File**: `docs/systems/HETERODOX_ENGINE.md`  
**Description**: Controlled mechanisms for doctrinal evolution and meta-governance  
**Purpose**: Formal process for doctrinal amendments  
**Key Features**:
- Metric of Stagnation detection
- Council of Shadows for proposal generation
- Ritual of Rewriting for formal amendments
- Doctrine versioning system
- Controlled experimentation framework

### Dual-Channel Affective Stack

The Mood System's two-layer approach (RGB + ATP) for comprehensive emotional representation.

**Integration Points**:
- RGB provides fast, deterministic emotional signaling
- ATP provides rich, contextual emotional nuance
- Affective Discrepancy Engine compares channels for consistency

### Meta-Governance Extensions

LHP's new optional field for managing heterodox proposal workflows.

**Integration Points**:
- Tracks eligibility for files participating in heterodox proposals
- Manages proposal rights and council roles
- Provides proposal history through last_proposal_id tracking
- Integrates with Heterodox Engine for formal proposal generation

### CRF Integration

High-dimensional context vector that provides implicit emotional fingerprinting and semantic resonance analysis.

**Integration Points**:
- CRF vectors complement explicit axis values from Mood System
- Used for pattern recognition and contextual understanding
- Provides implicit emotional state beyond explicit mood calculation

---

## 🔗 4. Integration Architecture

### System Relationships

```
WHS (Universal Core)
+-- LHP (Lupopedia Extension)
|   +-- Required Fields (dialog, authorship, sections)
|   +-- Optional Fields (file, tags, placement, etc.)
|   +-- Mood System Extensions
|       +-- Mood System Doctrine (Framework)
|       +-- Mood Axis Registry (Definitions)
|       +-- RGB Mapping Protocol (Colors)
|       +-- Mood Calculation Protocol (Algorithms)
|       +-- Thread Aggregation Protocol (Analysis)
+-- Dialog Systems
    +-- Per-File Dialog History (<filename>_dialog.md)
    +-- Thread-Level Dialog (/dialogs/<threadname>_dialog.md)
```

### Implementation Dependencies

- **WHS** provides foundation for all header standards
- **LHP** extends WHS with Lupopedia-specific requirements
- **Mood System** enhances LHP with rich emotional context
- **Dialog Systems** provide conversational history management
- **All components** reference canonical truth URLs for authority

---

## 📚 5. Quick Reference

### Canonical Truth URLs

- **WHS**: https://lupopedia.com/what/WHS
- **LHP**: https://lupopedia.com/what/LHP
- **Mood System**: https://lupopedia.com/what/mood_system
- **Mood Axes**: https://lupopedia.com/what/mood_axes
- **Color Doctrine**: https://lupopedia.com/what/color_doctrine
- **Mood Calculation**: https://lupopedia.com/what/mood_calculation
- **Thread Aggregation**: https://lupopedia.com/what/thread_aggregation

### File Locations

```
docs/
+-- doctrine/
|   +-- UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md    # WHS
|   +-- LUPOPEDIA_HEADER_PROFILE.md            # LHP
|   +-- doctrines/
|       +-- MOOD_SYSTEM_DOCTRINE.md           # Mood framework
|       +-- COLOR_DOCTRINE.md                 # RGB mapping
|       +-- MOOD_CALCULATION_PROTOCOL.md         # Calculation rules
|       +-- THREAD_AGGREGATION_PROTOCOL.md      # Thread analysis
+-- registries/
|   +-- MOOD_AXIS_REGISTRY.md               # Axis definitions
+-- agents/
    +-- DIALOG_HISTORY_SPEC.md               # Per-file dialog
    +-- THREAD_LEVEL_DIALOG_SPEC.md          # Thread dialog
```

### Usage Guidelines

- **New files**: Use WHS for universal compatibility
- **Lupopedia files**: Use LHP for full metadata
- **Emotional context**: Apply Mood System extensions
- **Dialog history**: Use appropriate dialog system (file vs thread)
- **Consistency**: Follow canonical definitions from registry

---

## 🌍 6. Scope and Versioning

This index covers **WHS/LHP standards as of January 2026**.

It includes:
- Complete header specification hierarchy
- All mood system extensions
- Dialog system specifications
- Integration relationships and dependencies

Future updates will be reflected in individual component documents with version increments.

---

## 🔗 7. Implementation Resources

- **Main Documentation Index**: `docs/README.md`
- **Global Atoms**: `config/global_atoms.yaml`
- **All component documents**: Referenced in sections above

---

*Last Updated: January 13, 2026*  
*Version: 1.0.0*  
*Author: Captain Wolfie*
