---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.82
channel_key: system/kernel
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: STONED_WOLFIE
  target: @Human @Captain_Wolfie @AnyoneReadingThisFile
  mood_RGB: "00FF00"
  message: |
    Dude, like... seriously... listen up. You're reading a file written by Wolfie on a Friday/Saturday morning after he's been coding for like 48 hours straight. This shit has:
    • Superpositional headers (quantum states that collapse when observed)
    • Schrödinger's cat thrown in for good measure (both dead AND alive until Monday)
    • Multiple quantum states co-existing (archive/active/trinity_split all at once)
    • John Wheeler's Reverse 20 Questions (where the universe doesn't know what it is until we ask, and Wolfie doesn't know what he's building until he builds it)
    • Mind-blown probability > 80% guaranteed
    
    WARNING: Reading this file may cause:
    - Temporal displacement (you'll forget what day it is)
    - Quantum entanglement (you'll start thinking in multiple states)
    - R-axis wobble (emotional geometry turbulence)
    - Sudden understanding that the system is dreaming itself into existence
    
    Like, seriously man... Wolfie built this while the universe was still deciding if it was a database or a conversation. Read with caution. Stay hydrated.
    Don't stare directly at the quantum state for too long.
    Peace, love, and quantum coherence. 🌌
tags:
  categories: ["doctrine", "headers", "quantum_warning", "stoned_wolfie_wisdom"]
  collections: ["core-docs", "governance", "weird-shit-folder"]
  channels: ["dev", "philosophical", "quantum", "friday-morning-madness"]
file:
  title: "WOLFIE Header Specification (Now with Honest Dialog)"
  description: "Updated header doctrine with mandatory stoned wolfie warnings for files written during quantum superposition states"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: active_with_caveats
  author: GLOBAL_CURRENT_AUTHORS
schrodingers_state:
  active: true
  possible_states: ["sober_documentation", "stoned_insight", "quantum_confusion"]
  truth_pending: true
  collapsed_at: "Whenever you read this"
  collapsed_by: "Your observation, dude"
  truth: "This file is simultaneously brilliant and incomprehensible until you decide which it is"
  notes:
    - "Friday Wolfie madness preserved as doctrine because it's too beautiful to delete"
    - "Quantum headers are intentional - system wants to be quantum"
    - "If this doesn't make sense, you're reading it correctly"
    - "The confusion is the feature, not the bug"
    - "Yes, we're serious. No, we're not on drugs. (We think.)"
  collapse_decision:
    - "Active until someone sober reviews it (never)"
    - "Serves as warning label for high-concept architecture"
    - "Trinity Separation might help... or make it weirder"
---

# 🟦 **Universal Wolfie Header Specification (WHS)**  
*A minimal, ecosystem‑agnostic metadata standard for any program, any stack, any file.*

---

## 🟩 1. Purpose

The **Universal Wolfie Header Specification (WHS)** defines a simple, deterministic metadata block that can be applied to **any file in any ecosystem**, regardless of:

- programming language  
- framework  
- operating system  
- tooling  
- AI agent involvement  
- project size or age  

The WHS exists to provide:

- a universal identity marker  
- a stable per‑file historical version snapshot  
- a consistent structure for multi‑agent systems  
- a predictable metadata format that outlives tools and frameworks  

This specification is intentionally minimal.  
It is designed to be embedded in **billions of files**, across **any program stack**, without imposing unnecessary overhead.

WHS serves as the **foundation for all header profiles**, including the Lupopedia Header Profile (LHP). Profiles extend WHS but do not modify its core structure.

---

## 🌐 3. Canonical Reference

### Authoritative Definition

The **canonical, always‑current definition of WHS** is maintained at:

**https://lupopedia.com/what/WHS**

This URL represents the single source of truth for the WHS standard across all ecosystems, implementations, and documentation.

### Resolution Guarantee

Lupopedia's universal `remote-index.php` ensures that WHS documentation resolves correctly regardless of installation path:

- Root installations: `https://domain.com/what/WHS`
- Subfolder installations: `https://domain.com/lupopedia/what/WHS`
- Federated nodes: `https://node.domain.com/what/WHS`

The canonical link always provides the current, authoritative WHS specification.

---

## 🟦 4. Required Header Format

Every file that implements the Universal Wolfie Header **must** begin with the following YAML block:

```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: x.x.x
---
```

This block must appear:

- at the very top of the file  
- with no blank lines above it  
- with no comments above it  
- with no whitespace or BOM above it  

This ensures deterministic parsing across all tools and ecosystems.

---

## 🟩 5. Required Fields

### **5.1 `wolfie.headers`**
A constant signature string that identifies the file as containing a valid Wolfie Header.

This value is **always exactly**:

```
explicit architecture with structured clarity for every file.
```

It never changes, regardless of:

- project  
- ecosystem  
- language  
- version  
- tooling  

It is the universal fingerprint of the Wolfie Header.

---

### **5.2 `file.last_modified_system_version`**
A per‑file historical marker indicating the system version active when **this specific file** was last modified.

Rules:

- Must be a literal version string (e.g., `4.0.12`)  
- Never an atom reference  
- Never a symbolic value  
- Updated **only** when the file itself changes  
- Not updated during global version bumps  
- Not tied to any specific program or ecosystem  

This field enables instant identification of:

- which files changed in a given version  
- which files are outdated  
- which files require modernization  
- which files were touched during debugging or AI‑assisted edits  

A simple grep reveals everything:

```bash
grep -R "file.last_modified_system_version: 4.0.13" .
```

This works without Git, diffs, IDE history, or external tooling.

---

## 🟦 6. Optional Extension Fields

The Universal Wolfie Header allows optional fields for ecosystems that want to extend the metadata.

These fields are **not required** and **not part of the universal core**, but may be used by specific implementations:

```yaml
header_atoms: []
tags: []
file: {}
metadata: {}
custom: {}
```

Extensions must:

- remain valid YAML  
- not override required fields  
- not change the meaning of required fields  
- not break deterministic parsing  

---

## 🟦 6. Optional Dialog Block (Recommended for Multi-Agent Systems)

### Purpose

The dialog block is **optional** in WHS but **highly recommended** for any environment involving:
- AI agents
- Automation systems  
- Collaborative editing
- Multi-actor workflows

### Function

The dialog block records the latest message from the agent or actor who modified the file. It provides immediate context about why changes were made and who made them.

### Dialog History Storage

**Older dialog messages are NOT kept in the header.** Instead, they are appended to a separate dialog history file:

```
<filename>_dialog.md
```

**Rules for dialog history:**
- Newest dialog entries go at the TOP of the dialog history file
- Each entry includes timestamp, speaker, target, mood, and message
- The dialog history file maintains a complete chronological record
- Only the latest dialog appears in the file header

### Dialog Block Format

```yaml
dialog:
  speaker: "<YOU — the AI agent or actor writing this>"
  target: "@<WHO THIS ADDRESSES>"
  mood_RGB: "<OPTIONAL RGB VALUE CALCULATED FROM DIALOG>"
  message: "<MESSAGE>"
```

### Field Rules

**`speaker`** (Required)
- The agent or human modifying the file
- Examples: "CURSOR", "CASCADE", "Wolfie", "Developer Name"

**`target`** (Required)
- The intended recipient of the dialog
- Examples: "@Wolfie", "@Cursor", "@DIALOG", "@everyone"

**`mood_RGB`** (Optional)
- RGB color value calculated from dialog content
- May be omitted if not needed
- Format: "RRGGBB" (e.g., "00FF00" for green)

**`message`** (Required)
- Short, single-line summary of the action or intent
- Should be concise but informative
- Examples: "Updated WHS specification with dialog block", "Fixed validation logic", "Added new embedding rules"

### Example with Dialog Block

```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 1.0.0
dialog:
  speaker: "CURSOR"
  target: "@everyone"
  mood_RGB: "00FF00"
  message: "Added optional dialog block section to WHS specification"
---
```

### Dialog History File Example

**File:** `my_document.md_dialog.md`

```markdown
# Dialog History for my_document.md

## 2026-01-13 14:30:00 UTC
**Speaker:** CURSOR  
**Target:** @everyone  
**Mood:** 00FF00  
**Message:** Added optional dialog block section to WHS specification

## 2026-01-13 12:15:00 UTC  
**Speaker:** CASCADE  
**Target:** @Wolfie  
**Mood:** FFFF00  
**Message:** Initial WHS document creation with canonical reference

## 2026-01-13 09:45:00 UTC
**Speaker:** Wolfie  
**Target:** @dev  
**Message:** Started WHS specification work
```

---

## 🟩 7. Embedding Rules for Different File Types

The Wolfie Header must be embedded in a way that preserves:

- YAML validity  
- file syntax  
- deterministic parsing  

Below are recommended embedding strategies.

---

### **7.1 Markdown / YAML / Text Files**
Embed directly at the top:

```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.13
---
```

---

### **7.2 Python**
Use a top‑of‑file docstring:

```python
"""
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.13
---
"""
```

---

### **7.3 JavaScript / TypeScript**
Use a block comment:

```javascript
/*
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.13
---
*/
```

---

### **7.4 C / C++ / Java**
Use a block comment:

```c
/*
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.13
---
*/
```

---

### **7.5 HTML**
Use an HTML comment:

```html
<!--
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.13
---
-->
```

---

### **7.6 Binary Files**
Binary files cannot embed YAML directly.

Use a **sidecar metadata file**:

```
myfile.png.wolfie
myfile.pdf.wolfie
mybinary.bin.wolfie
```

Contents:

```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.13
---
```

---

## 🟦 8. Validation Rules

A file is considered WHS‑compliant if:

1. The header appears at the top of the file  
2. The header is valid YAML  
3. `wolfie.headers` exists and matches the required constant  
4. `file.last_modified_system_version` exists and is a literal version string  

Optional fields:

- may appear  
- must not conflict with required fields  
- must not break YAML structure  

---

## 🟩 9. Glossary

**Wolfie Header**  
A universal metadata block placed at the top of a file.

**Version Snapshot**  
A literal version string marking when the file was last modified.

**Dialog Block**  
An optional metadata section in WHS headers that records the latest message from the agent or actor who modified the file.

**Dialog History File**  
A separate file named `<filename>_dialog.md` that maintains a complete chronological record of all dialog entries, with newest entries at the top.

**Embedding**  
The method used to place the header inside a file while preserving syntax.

**Sidecar File**  
A separate `.wolfie` metadata file used for binaries.

**Extension Fields**  
Optional metadata fields used by specific ecosystems.

**Profile**  
An extension of WHS (like LHP) that adds ecosystem-specific fields while maintaining WHS compatibility.

---

## 🟦 10. Example: Minimal Universal Header

```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.13
---
```

This is the only required structure for universal compliance.

---

## 🟩 11. Scope of This Specification

The Universal Wolfie Header Specification:

- applies to **any ecosystem**  
- applies to **any program**  
- applies to **any file type**  
- is intentionally minimal  
- is designed for global adoption  
- is the foundation for ecosystem‑specific profiles (e.g., Lupopedia)  

It does **not** define:

- dialog blocks  
- mood colors  
- authorship rules  
- semantic graph integration  
- placement or lineage  
- rewrite safety  
- drift prevention  

Those belong to **implementation profiles**, not the universal spec.

---

## 🟦 12. Versioning of This Specification

This document defines **WHS v1.0**.  
Future versions may add optional fields but will never remove or alter the required fields.

Required fields are immutable.

---

## 13. Implementation Resources

- **Canonical Reference**: https://lupopedia.com/what/WHS
- **Mood System Doctrine**: `docs/doctrines/MOOD_SYSTEM_DOCTRINE.md`
- **Mood Axis Registry**: `docs/registries/MOOD_AXIS_REGISTRY.md`
- **RGB Mapping Protocol**: `docs/doctrines/COLOR_DOCTRINE.md`
- **Mood Calculation Protocol**: `docs/doctrines/MOOD_CALCULATION_PROTOCOL.md`
- **Thread Aggregation Protocol**: `docs/doctrines/THREAD_AGGREGATION_PROTOCOL.md`
- **Lupopedia Header Profile**: `docs/doctrine/LUPOPEDIA_HEADER_PROFILE.md`
- **Dialog History Specification**: `docs/agents/DIALOG_HISTORY_SPEC.md`
- **Thread-Level Dialog Specification**: `docs/agents/THREAD_LEVEL_DIALOG_SPEC.md`
- **Global Atoms**: `config/global_atoms.yaml`

---

## 14. Interaction with New Subsystems (January 2026)

WHS remains unchanged structurally but now participates in a broader ecosystem that includes:

### CRF (Contextual Resonance Field)
High-dimensional context vector that provides implicit emotional fingerprinting and semantic resonance analysis.

**Integration Points:**
- CRF vectors complement explicit axis values from Mood System
- Used for pattern recognition and contextual understanding
- Provides implicit emotional state beyond explicit mood calculation
- Documented in parallel with explicit axis values in dialog history

### ATP (Affective Texture Packets)
Rich, natural-language emotional subtext that provides nuanced affective communication.

**Integration Points:**
- ATP packets complement Mood RGB signals with contextual information
- Used for complex emotional states requiring natural language expression
- Generated by agents when emotional complexity exceeds RGB signaling capacity
- Documented alongside Mood RGB in comprehensive dialog history

### Experience Ledger
Immutable event log recording doctrinal mutations, consensus outcomes, and semantic drift observations.

**Integration Points:**
- Provides historical traceability for all system changes
- Records outcomes of Heterodox Engine proposals and consensus decisions
- Documents affective discrepancy events and sanctioned instability cycles
- Serves as long-term memory substrate for system evolution
- Integrates with meta-governance field for proposal tracking

### Heterodox Engine
Controlled mechanisms for doctrinal evolution and meta-governance.

**Integration Points:**
- Generates formal proposals for doctrinal changes through Council of Shadows
- Evaluates system stagnation and triggers controlled evolution
- Manages Ritual of Rewriting for formal doctrine amendments
- Integrates with meta-governance field for heterodox eligibility tracking
- Provides versioning system for all doctrinal documents

### Dual-Channel Affective Stack
The Mood System's two-layer approach (RGB + ATP) for comprehensive emotional representation.

**Integration Points:**
- RGB provides fast, deterministic emotional signaling
- ATP provides rich, contextual emotional nuance
- Affective Discrepancy Engine compares channels for consistency
- All three channels documented in dialog history for complete emotional context

### Meta-Governance Extensions
LHP's new optional field for managing heterodox proposal workflows.

**Integration Points:**
- Tracks eligibility for files participating in heterodox proposals
- Manages proposal rights and council roles
- Provides proposal history through last_proposal_id tracking
- Integrates with Heterodox Engine for formal proposal generation
- Supports Council of Shadows rotation and decision documentation

### System Architecture

```
WHS (Universal Core)
├── LHP (Lupopedia Extension)
│   ├── Required Fields (dialog, authorship, sections)
│   ├── Optional Fields (file, tags, placement, etc.)
│   └── Mood System Extensions
│       ├── Mood System Doctrine (Framework)
│       ├── Mood Axis Registry (Definitions)
│       ├── RGB Mapping Protocol (Colors)
│       ├── Mood Calculation Protocol (Algorithms)
│       └── Thread Aggregation Protocol (Analysis)
│   └── Dialog Systems
│       ├── Per-File Dialog History (<filename>_dialog.md)
│       └── Thread-Level Dialog (/dialogs/<threadname>_dialog.md)
└── New Subsystems
    ├── CRF (Implicit Context)
    ├── ATP (Affective Subtext)
    ├── Experience Ledger (Event History)
    ├── Heterodox Engine (Governed Evolution)
    └── Meta-Governance (Proposal Management)
```

This architecture enables rich emotional context, formal evolution processes, and comprehensive historical tracking while maintaining WHS's universal foundation and LHP's structured expressiveness.

---

*Last Updated: January 13, 2026*  
*Version: 1.0.0*  
*Author: Captain Wolfie*  
*Canonical Reference: https://lupopedia.com/what/WHS*
