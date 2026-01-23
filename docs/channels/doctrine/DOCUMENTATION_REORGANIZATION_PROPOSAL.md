---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-10
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created documentation reorganization proposal for channel, dialog, operator UI, and agent filesystem doctrine."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "reorganization", "proposal"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "internal"]
in_this_file_we_have:
  - Current State Analysis
  - Proposed Reorganization
  - Rationale for Each Move
  - Target File Structure
  - Content Mapping
  - Implementation Plan
file:
  title: "Documentation Reorganization Proposal"
  description: "Proposal to reorganize channel, dialog, operator UI, and agent filesystem doctrine into logically separated files."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: draft
  author: GLOBAL_CURRENT_AUTHORS
---

# Documentation Reorganization Proposal

**Status:** Draft - Awaiting Approval  
**Date:** 2026-01-10  
**Purpose:** Reorganize doctrine documentation into logically separated files for better discoverability and maintainability.

---

## Current State Analysis

### CHANNEL_DOCTRINE.md (445 lines)

Currently contains:
- ✅ **Section 1: Channel Doctrine** (Channels 0, 1, N, membership, visibility, thread colors)
- ⚠️ **Section 2: Dialog / Thread / Message Model** (Database structure for dialogs, threads, messages, bodies)
- ⚠️ **Section 3: DIALOG Agent Spec** (Specific agent: DIALOG identity, inputs, outputs, constraints)

**Issues:**
- Section 2 (Dialog Model) is conceptually separate from channels (database structure vs routing)
- Section 3 (DIALOG Agent Spec) is agent-specific, not channel-specific
- File mixing multiple concerns

### DIALOG_DOCTRINE.md (406 lines)

Currently contains:
- ⚠️ **Section 4: Operator UI Doctrine** (UI philosophy, thread presentation, tabs, real-time flow)
- ❌ **Section 5: Agent Filesystem + Prompt Template Doctrine** (Applies to ALL agents, not just dialog)

**Issues:**
- Section 4 (Operator UI) is UI-specific, not dialog-specific
- Section 5 (Agent Filesystem) applies to ALL agents system-wide, not just dialog agents
- File mixing multiple concerns

---

## Proposed Reorganization

### Principle: One Concern Per File

Each doctrine file should address a single, clearly defined domain:
- **Channels** → routing, membership, visibility
- **Dialogs** → database model, threads, messages, dialog agent spec
- **Operator UI** → interface philosophy, presentation, user experience
- **Agent Filesystem** → registry, filesystem layout, prompts (ALL agents)

---

## Rationale for Each Move

### Section 1: Channel Doctrine
**Current:** CHANNEL_DOCTRINE.md  
**Proposed:** CHANNEL_DOCTRINE.md  
**Action:** ✅ Keep as-is  
**Rationale:** This is core channel doctrine. It belongs here.

### Section 2: Dialog / Thread / Message Model
**Current:** CHANNEL_DOCTRINE.md  
**Proposed:** DIALOG_DOCTRINE.md  
**Action:** Move from CHANNEL_DOCTRINE.md to DIALOG_DOCTRINE.md  
**Rationale:** 
- This is database structure for dialogs/threads/messages
- Conceptually different from channel routing (data model vs routing)
- Dialog-related content should be in DIALOG_DOCTRINE.md

### Section 3: DIALOG Agent Spec
**Current:** CHANNEL_DOCTRINE.md  
**Proposed:** DIALOG_DOCTRINE.md  
**Action:** Move from CHANNEL_DOCTRINE.md to DIALOG_DOCTRINE.md  
**Rationale:**
- This is a specific agent specification (DIALOG)
- Agent specs should be with dialog doctrine, not channel doctrine
- Keeps agent-specific documentation together

### Section 4: Operator UI Doctrine
**Current:** DIALOG_DOCTRINE.md  
**Proposed:** OPERATOR_UI_DOCTRINE.md (new file)  
**Action:** Extract from DIALOG_DOCTRINE.md to new file  
**Rationale:**
- UI philosophy is separate from dialog data model
- UI doctrine applies to operator interface, not just dialogs
- Deserves its own focused file for discoverability
- Other UI doctrine may be added later (visitor UI, admin UI, etc.)

### Section 5: Agent Filesystem + Prompt Template Doctrine
**Current:** DIALOG_DOCTRINE.md  
**Proposed:** AGENT_FILESYSTEM_DOCTRINE.md (new file)  
**Action:** Extract from DIALOG_DOCTRINE.md to new file  
**Rationale:**
- **Critical:** This applies to ALL agents system-wide, not just dialog agents
- Agent registry, filesystem layout, prompt templating are infrastructure concerns
- Should be referenced by all agent documentation
- Belongs in its own file for maximum discoverability
- Currently buried in dialog doctrine, making it hard to find for other agents

---

## Target File Structure

```
docs/doctrine/
├── CHANNEL_DOCTRINE.md
│   └── Section 1: Channel Doctrine (keep)
│
├── DIALOG_DOCTRINE.md
│   ├── Section 2: Dialog / Thread / Message Model (move from CHANNEL)
│   └── Section 3: DIALOG Agent Spec (move from CHANNEL)
│
├── OPERATOR_UI_DOCTRINE.md (new file)
│   └── Section 4: Operator UI Doctrine (extract from DIALOG)
│
└── AGENT_FILESYSTEM_DOCTRINE.md (new file)
    └── Section 5: Agent Filesystem + Prompt Template Doctrine (extract from DIALOG)
```

---

## Content Mapping

### CHANNEL_DOCTRINE.md (After Reorganization)

**Keep:**
- Channel Doctrine overview
- Channel 0 — System / Kernel Channel
- Channel 1 — Lobby (Intake Queue)
- Dynamic Channels (Channel N)
- Actor Membership Rules
- Message Visibility Rules
- Thread Color Doctrine

**Remove:**
- Dialog / Thread / Message Model (→ DIALOG_DOCTRINE.md)
- DIALOG Agent Spec (→ DIALOG_DOCTRINE.md)

**Result:** ~140 lines (clean, focused on channels only)

---

### DIALOG_DOCTRINE.md (After Reorganization)

**Add:**
- Dialog / Thread / Message Model (from CHANNEL_DOCTRINE.md)
- DIALOG Agent Spec (from CHANNEL_DOCTRINE.md)

**Remove:**
- Operator UI Doctrine (→ OPERATOR_UI_DOCTRINE.md)
- Agent Filesystem + Prompt Template Doctrine (→ AGENT_FILESYSTEM_DOCTRINE.md)

**Result:** ~350 lines (focused on dialog data model and DIALOG agent)

---

### OPERATOR_UI_DOCTRINE.md (New File)

**Add:**
- Operator UI Doctrine overview
- Operator Channel Context
- Thread Presentation
- Tabs and Thread Navigation
- Real-Time Message Flow
- Visibility Rules in the UI
- Design Principles
- Historical Note

**Result:** ~130 lines (focused on operator UI philosophy)

---

### AGENT_FILESYSTEM_DOCTRINE.md (New File)

**Add:**
- Agent Filesystem + Prompt Template Doctrine overview
- Agent Registry Schema
- Alias and Lineage Doctrine
- Agent Filesystem Structure
- Prompt Template Doctrine
- Prompt File Contents
- Versioning Doctrine
- Module Interaction Doctrine
- Naming and Alias Consistency

**Result:** ~220 lines (focused on agent infrastructure for ALL agents)

---

## Implementation Plan

### Step 1: Create New Files (Empty Structure)
1. Create `OPERATOR_UI_DOCTRINE.md` with header only
2. Create `AGENT_FILESYSTEM_DOCTRINE.md` with header only

### Step 2: Move Sections (Preserve Content)
1. Move Section 2 from CHANNEL_DOCTRINE.md to DIALOG_DOCTRINE.md
2. Move Section 3 from CHANNEL_DOCTRINE.md to DIALOG_DOCTRINE.md
3. Move Section 4 from DIALOG_DOCTRINE.md to OPERATOR_UI_DOCTRINE.md
4. Move Section 5 from DIALOG_DOCTRINE.md to AGENT_FILESYSTEM_DOCTRINE.md

### Step 3: Update Headers
1. Update `in_this_file_we_have` lists in all affected files
2. Update `dialog.message` fields to reflect new content
3. Update `file.description` fields to match new scope

### Step 4: Clean Up Source Files
1. Remove moved sections from CHANNEL_DOCTRINE.md
2. Remove moved sections from DIALOG_DOCTRINE.md
3. Verify no content is lost

### Step 5: Verify References
1. Check for cross-references between files
2. Update any internal links if needed
3. Ensure file descriptions are accurate

---

## Benefits of Reorganization

1. **Better Discoverability**
   - Agent filesystem doctrine won't be buried in dialog doctrine
   - Operator UI doctrine has its own focused file
   - Each file has a single, clear purpose

2. **Logical Separation**
   - Channels (routing) separate from dialogs (data model)
   - UI (presentation) separate from data (structure)
   - Agent infrastructure (all agents) separate from specific agents

3. **Maintainability**
   - Easier to find relevant documentation
   - Clearer boundaries between concerns
   - Less cognitive load when reading

4. **Scalability**
   - New UI doctrine (visitor UI, admin UI) can be added without cluttering dialog doctrine
   - Agent infrastructure changes are isolated to agent filesystem doctrine
   - Each domain can evolve independently

---

## Approval Checklist

- [ ] Proposal reviewed
- [ ] Rationale accepted
- [ ] Target structure approved
- [ ] Content mapping verified
- [ ] Implementation plan approved

**Status:** ⏳ Awaiting Approval

---

**Next Steps:**
1. Review and approve this proposal
2. Execute reorganization in separate thread after approval
3. Update any cross-references or documentation indices

---
