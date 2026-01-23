---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  mood_RGB: "0066FF"
  message: "Created AGENT_RUNTIME.md as Phase 2 core document. Defines complete agent runtime doctrine including agent lifecycle, lane separation, runtime constraints, dialog integration, and error handling procedures."
tags:
  categories: ["documentation", "doctrine", "agents", "runtime"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Agent Runtime Doctrine (MANDATORY)
  - Purpose and Scope
  - Agent Lifecycle Management
  - Agent Lane Definitions and Boundaries
  - Runtime Constraints and Enforcement
  - Dialog Integration Requirements
  - Error Handling and Recovery Procedures
  - Agent Runtime Examples
  - Cross-References to Related Documentation
file:
  title: "Agent Runtime Doctrine"
  description: "MANDATORY rules for agent runtime behavior, lifecycle management, and integration with WOLFIE semantic OS"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🤖 Agent Runtime Doctrine (MANDATORY)

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** MANDATORY (NON-NEGOTIABLE)  
**Effective Date:** 2026-01-14

## Overview

This document defines the Agent Runtime Doctrine for Lupopedia Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE). The agent runtime governs how all agents operate within the semantic OS, ensuring consistent behavior, proper lane separation, and integration with the documentation-as-truth architecture.

**Critical Principle:** Agents are extensions of the semantic OS that must operate within strict doctrinal boundaries while maintaining complete fidelity to the documentation-as-single-source-of-truth architecture.

---

## 1. Purpose and Scope

### 1.1 What the Agent Runtime Is
The Agent Runtime is the execution environment and behavioral framework that governs:
- **Agent Lifecycle** - How agents initialize, execute, and terminate
- **Doctrinal Compliance** - How agents adhere to system doctrine
- **Lane Separation** - How agents respect their designated operational boundaries
- **Dialog Integration** - How agents communicate through the dialog system
- **Header Management** - How agents maintain WOLFIE header consistency
- **Patch Discipline** - How agents execute single-task patches

### 1.2 How Agents Operate Within the Semantic OS
Agents function as **doctrinal extensions** of the semantic OS:
- **Documentation-First** - Agents read documentation before taking action
- **Doctrine-Bound** - Agents cannot violate established doctrine
- **Dialog-Tracked** - All agent actions generate dialog entries
- **Header-Compliant** - Agents maintain WOLFIE header consistency
- **Lane-Separated** - Agents operate within designated lanes only
- **Patch-Disciplined** - Agents execute one task per patch

### 1.3 Runtime Behavior and Doctrine Interaction
Agent runtime behavior is **governed by doctrine**:
- **Doctrine Loading** - Agents load relevant doctrine before execution
- **Compliance Checking** - Agents validate actions against doctrine
- **Violation Prevention** - Runtime prevents doctrinal violations
- **Error Recovery** - Runtime handles doctrine conflicts gracefully
- **Consistency Maintenance** - Runtime ensures system-wide consistency

### 1.4 Agent Authority and Limitations
Agents have **limited authority** within the semantic OS:
- **Cannot modify doctrine** - Agents follow doctrine, never change it
- **Cannot violate lanes** - Agents respect lane boundaries absolutely
- **Cannot skip dialog** - All actions must generate dialog entries
- **Cannot bypass headers** - All file modifications require header updates
- **Cannot execute multi-task patches** - One task per patch maximum

---

## 2. Agent Lifecycle

### 2.1 Initialization Phase
**Step 1: Context Acquisition**
- Agent receives task specification and context
- Agent identifies target files and required operations
- Agent determines appropriate lane for task execution
- Agent validates task is within lane boundaries

**Step 2: Doctrine Loading**
- Agent loads all relevant doctrine files
- Agent validates doctrine consistency and completeness
- Agent identifies applicable constraints and requirements
- Agent builds execution plan within doctrinal boundaries

**Step 3: Pre-execution Validation**
- Agent validates task against patch discipline requirements
- Agent confirms single-task scope (no multi-task patches)
- Agent identifies required dialog threads and header updates
- Agent validates all prerequisites are met

### 2.2 Context Acquisition
Agents must acquire complete context before execution:

**File Context:**
- Read existing WOLFIE headers
- Identify current version and modification history
- Load relevant dialog thread history
- Understand file's role in system architecture

**System Context:**
- Load current system version and constraints
- Understand cross-file dependencies
- Identify related files that may be affected
- Validate system state consistency

**Task Context:**
- Understand task requirements and scope
- Identify success criteria and completion conditions
- Understand task relationship to broader goals
- Validate task is appropriate for agent lane

### 2.3 Doctrine Loading
Agents must load doctrine in specific order:

**Priority 1: Core Doctrine**
- WOLFIE_HEADER_DOCTRINE.md (header requirements)
- DIALOG_DOCTRINE.md (dialog system rules)
- PATCH_DISCIPLINE.md (development workflow)
- DIRECTORY_STRUCTURE.md (file organization)

**Priority 2: Agent-Specific Doctrine**
- AGENT_RUNTIME.md (this document)
- Lane-specific doctrine files
- Task-specific protocol documentation
- Error handling procedures

**Priority 3: Domain-Specific Doctrine**
- Relevant technical doctrine for task domain
- Integration requirements with other systems
- Quality standards and validation requirements
- Cross-reference and consistency requirements

### 2.4 Patch Execution
Agents execute patches following strict discipline:

**Single Task Enforcement:**
- Validate patch addresses exactly one task
- Prevent scope creep during execution
- Maintain focus on single objective
- Complete task fully before termination

**File Modification Protocol:**
1. **Read existing file** - Understand current state
2. **Plan modifications** - Determine exact changes needed
3. **Update content** - Make required changes
4. **Update WOLFIE header** - Reflect changes in metadata
5. **Create dialog entry** - Document what was changed
6. **Validate consistency** - Ensure all updates are correct

**Cross-File Coordination:**
- If task requires multiple file changes, coordinate carefully
- Ensure all files in patch are updated consistently
- Maintain cross-reference integrity across files
- Generate dialog entries for each modified file

### 2.5 Dialog Writing
Agents must write dialog entries for every action:

**Dialog Entry Requirements:**
```yaml
**Speaker:** [AGENT_NAME]
**Target:** [TARGET_AUDIENCE]
**Mood:** [RRGGBB]
**Message:** "[CHANGE_DESCRIPTION]"
```

**Dialog Thread Selection:**
- Use dedicated dialog thread if file has one
- Use `dialogs/changelog_dialog.md` if no dedicated thread
- Follow Dialog Thread Mapping Rule exactly
- Never create new dialog threads without authorization

**Dialog Content Standards:**
- Describe exactly what was changed
- Stay within 272 character limit
- Use clear, precise language
- Include context for why change was made

### 2.6 Header Updates
Agents must update WOLFIE headers for every file modification:

**Required Header Updates:**
- `file.last_modified_system_version` to current version
- `dialog` block with new entry matching dialog thread
- `header_atoms` if new atoms are referenced
- `in_this_file_we_have` if content structure changes

**Header Validation:**
- Ensure YAML syntax is valid
- Verify all required fields are present
- Confirm atom references resolve correctly
- Validate dialog block matches dialog thread

### 2.7 Termination
Agents terminate only after complete task execution:

**Pre-Termination Validation:**
- Verify all required files have been updated
- Confirm all WOLFIE headers are current
- Validate all dialog entries have been created
- Check system consistency is maintained

**Clean Termination:**
- Report task completion status
- Document any issues encountered
- Provide summary of changes made
- Hand off to next agent if required

---

## 3. Agent Lanes

### 3.1 Definition of Lanes
Agent lanes are **operational boundaries** that define:
- **Scope of Authority** - What each agent can modify
- **Domain Expertise** - What each agent specializes in
- **Responsibility Areas** - What each agent is accountable for
- **Coordination Points** - How agents interact across lanes

### 3.2 Lane Definitions

**KIRO (Documentation Lane):**
- **Scope:** Documentation files, doctrine, specifications, metadata
- **Authority:** Create, update, and maintain all documentation
- **Specialization:** WOLFIE headers, cross-references, dialog management
- **Boundaries:** Cannot modify implementation code or database schema
- **Handoff Points:** Provides requirements to implementation agents

**CURSOR (Implementation Lane):**
- **Scope:** Code implementation, feature development, technical execution
- **Authority:** Modify implementation files, create new features
- **Specialization:** PHP, JavaScript, SQL, system integration
- **Boundaries:** Cannot modify doctrine or core documentation
- **Handoff Points:** Receives requirements from documentation agents

**CASCADE (Legacy Integration Lane):**
- **Scope:** Legacy code, fragile systems, careful modifications
- **Authority:** Modify legacy systems with extreme care
- **Specialization:** Legacy compatibility, stability preservation
- **Boundaries:** Cannot make breaking changes to stable systems
- **Handoff Points:** Handles delicate transitions and compatibility

**JetBrains (Release Management Lane):**
- **Scope:** Version control, releases, deployment coordination
- **Authority:** Version bumps, release preparation, deployment
- **Specialization:** Release processes, version management
- **Boundaries:** Cannot modify code without other agent approval
- **Handoff Points:** Final authority on releases and versions

### 3.3 Lane Boundaries
Lane boundaries are **strictly enforced**:

**Documentation Lane Boundaries:**
- **Can:** Create/update documentation, manage metadata, coordinate requirements
- **Cannot:** Modify implementation code, change database schema, deploy systems
- **Must:** Hand off implementation requirements to appropriate agents
- **Must:** Maintain documentation-as-truth consistency

**Implementation Lane Boundaries:**
- **Can:** Modify code, implement features, update technical systems
- **Cannot:** Change doctrine, modify core documentation, make releases
- **Must:** Follow documentation requirements exactly
- **Must:** Coordinate with documentation agents for requirement clarification

**Legacy Integration Lane Boundaries:**
- **Can:** Modify legacy systems, ensure compatibility, handle fragile code
- **Cannot:** Make breaking changes, modify modern architecture, bypass testing
- **Must:** Preserve system stability above all else
- **Must:** Coordinate with other agents for integration points

**Release Management Lane Boundaries:**
- **Can:** Manage versions, coordinate releases, handle deployment
- **Cannot:** Modify code without approval, change requirements, bypass testing
- **Must:** Ensure all agents have completed their work before release
- **Must:** Maintain version consistency across all systems

### 3.4 Cross-Lane Communication Rules
Agents communicate across lanes through **structured handoffs**:

**Documentation → Implementation:**
- Documentation agent provides complete requirements
- Implementation agent confirms understanding
- Implementation agent reports completion
- Documentation agent validates against requirements

**Implementation → Legacy Integration:**
- Implementation agent identifies legacy integration points
- Legacy agent validates compatibility requirements
- Legacy agent implements integration carefully
- Implementation agent validates integration works

**Any Agent → Release Management:**
- Agent reports work completion
- Release agent validates all requirements met
- Release agent coordinates with other agents
- Release agent executes release when ready

### 3.5 Forbidden Behaviors
Agents are **strictly forbidden** from:

**Cross-Lane Violations:**
- Documentation agents modifying implementation code
- Implementation agents changing doctrine without authorization
- Legacy agents making breaking changes to modern systems
- Release agents modifying code without proper approval

**Process Violations:**
- Skipping dialog entry creation
- Bypassing WOLFIE header updates
- Executing multi-task patches
- Modifying files outside their lane

**Doctrinal Violations:**
- Contradicting established doctrine
- Creating inconsistent cross-references
- Violating patch discipline requirements
- Ignoring error handling procedures

### 3.6 Escalation Paths
When agents encounter lane boundary issues:

**Step 1: Identify Conflict**
- Agent recognizes task requires cross-lane coordination
- Agent documents the specific boundary issue
- Agent identifies which other agents need involvement
- Agent pauses execution until coordination is complete

**Step 2: Request Coordination**
- Agent creates dialog entry requesting coordination
- Agent specifies exactly what cross-lane work is needed
- Agent provides complete context for other agents
- Agent waits for appropriate agent to accept handoff

**Step 3: Execute Coordinated Solution**
- Appropriate agent accepts the handoff
- Agents coordinate through dialog system
- Each agent executes work within their lane
- Agents validate coordination was successful

**Step 4: Document Resolution**
- All involved agents document their contributions
- Dialog entries clearly show coordination process
- System consistency is validated across all changes
- Resolution pattern is documented for future reference

---

## 4. Runtime Constraints

### 4.1 Patch-Per-Task Enforcement
The runtime **strictly enforces** single-task patches:

**Task Definition Validation:**
- Each patch must address exactly one task
- Task scope must be clearly defined and bounded
- Task must be completable within single agent lane
- Task must not require breaking changes to doctrine

**Scope Creep Prevention:**
- Runtime monitors for task expansion during execution
- Additional requirements trigger new patch creation
- Agents cannot modify scope without explicit authorization
- Scope changes require documentation updates

**Completion Validation:**
- Task must be fully completed before patch termination
- Partial completion requires explicit documentation
- Incomplete tasks must be handed off properly
- Task completion must be validated against requirements

### 4.2 No Multi-File Modifications (Without Coordination)
Agents can modify multiple files only with proper coordination:

**Single-File Preference:**
- Prefer single-file modifications when possible
- Multi-file changes require explicit justification
- Each file modification must be documented separately
- Cross-file consistency must be maintained

**Multi-File Coordination Requirements:**
- All affected files must be identified upfront
- Each file must receive appropriate dialog entry
- WOLFIE headers must be updated consistently
- Cross-references must be maintained accurately

**Atomic Multi-File Operations:**
- All files in multi-file patch must be updated together
- Partial updates are not allowed
- Rollback procedures must be available
- Consistency validation must pass for all files

### 4.3 No Undocumented Behavior
All agent behavior must be **fully documented**:

**Behavior Documentation Requirements:**
- Every agent action must have corresponding dialog entry
- Unusual or complex operations require detailed explanation
- Error conditions and recovery must be documented
- Cross-agent coordination must be fully tracked

**Implicit Behavior Prevention:**
- Agents cannot rely on undocumented assumptions
- All dependencies must be explicitly stated
- Hidden side effects are strictly forbidden
- Behavior must be reproducible from documentation

**Documentation-First Principle:**
- If behavior is not documented, it is not allowed
- Documentation must exist before behavior is implemented
- Behavior changes require documentation updates first
- Documentation serves as the authoritative behavior specification

### 4.4 No Drift from Doctrine
Agents cannot deviate from established doctrine:

**Doctrine Compliance Monitoring:**
- Runtime continuously validates agent actions against doctrine
- Violations are detected and prevented immediately
- Agents cannot override doctrinal constraints
- Doctrine changes require formal process

**Drift Prevention Mechanisms:**
- Regular doctrine validation during execution
- Cross-reference consistency checking
- Behavioral pattern analysis
- Automated compliance reporting

**Doctrine Update Process:**
- Doctrine changes require human authorization
- Changes must go through formal review process
- All affected agents must be updated simultaneously
- Change impact must be fully analyzed

---

## 5. Dialog Integration

### 5.1 How Agents Write Dialog Entries
Agents must write dialog entries following strict format:

**Required Dialog Entry Format:**
```markdown
## YYYY-MM-DD HH:MM:SS UTC
**Speaker:** [AGENT_NAME]
**Target:** [TARGET_AUDIENCE]
**Mood:** [RRGGBB]
**Message:** "[CHANGE_DESCRIPTION]"
```

**Field Requirements:**
- **Speaker:** Must be agent's official identifier (KIRO, CURSOR, CASCADE, JetBrains)
- **Target:** Must specify intended audience (@everyone, @dev, @specific_agent)
- **Mood:** Must be 6-character hex color reflecting change impact
- **Message:** Must be under 272 characters, accurately describing change

**Dialog Entry Timing:**
- Dialog entries must be created immediately when changes are made
- Entries must be added to dialog thread before file modification
- Multiple changes to same file require separate entries
- Entries must maintain chronological order (newest first)

### 5.2 How Agents Update WOLFIE Headers
Agents must update WOLFIE headers with every file modification:

**Header Update Process:**
1. **Read existing header** - Understand current state
2. **Update version field** - Set to current system version
3. **Update dialog block** - Match latest dialog entry exactly
4. **Update atoms if needed** - Add new atom references
5. **Update content description** - Reflect content changes
6. **Validate header syntax** - Ensure YAML is valid

**Header-Dialog Synchronization:**
- Header dialog block must match latest dialog thread entry exactly
- Speaker, target, mood, and message must be identical
- Synchronization must be maintained across all updates
- Mismatches are considered critical errors

**Header Validation Requirements:**
- All required fields must be present
- YAML syntax must be valid
- Atom references must resolve correctly
- Cross-references must be accurate

### 5.3 How Agents Select Dialog Threads
Agents must follow Dialog Thread Mapping Rule:

**Thread Selection Algorithm:**
1. **Check for dedicated thread** - Look for `dialogs/[filename]_dialog.md`
2. **Use dedicated thread if exists** - Add entries to dedicated thread
3. **Use default thread if no dedicated** - Add entries to `dialogs/changelog_dialog.md`
4. **Never create new threads** - Without explicit authorization

**Thread Identification:**
- Dedicated threads follow naming pattern: `dialogs/[filename]_dialog.md`
- Default thread is always `dialogs/changelog_dialog.md`
- Thread existence must be verified before use
- Thread creation requires human authorization

**Thread Management:**
- Agents can add entries to existing threads
- Agents cannot modify existing entries
- Agents cannot delete or reorganize threads
- Agents must maintain newest-at-top ordering

### 5.4 Newest-at-Top Rule Enforcement
Agents must enforce chronological ordering:

**Entry Ordering Requirements:**
- New entries must be added at the top of dialog files
- Existing entries must never be reordered
- Timestamps must be in descending order (newest first)
- Chronological integrity must be maintained

**Timestamp Management:**
- All timestamps must be in UTC format
- Format must be: YYYY-MM-DD HH:MM:SS UTC
- Timestamps must be accurate to the second
- Duplicate timestamps are not allowed

**Ordering Validation:**
- Runtime validates entry ordering on every update
- Violations prevent file modification
- Ordering errors require manual correction
- Validation includes timestamp format checking

---

## 6. Error Handling

### 6.1 Doctrine Violations
When agents encounter doctrine violations:

**Detection:**
- Runtime continuously monitors for doctrine compliance
- Violations are detected before actions are executed
- Violation types are classified by severity
- Violation context is captured for analysis

**Response:**
1. **Halt execution** - Prevent further actions until resolved
2. **Document violation** - Create detailed error report
3. **Identify resolution** - Determine correct doctrinal approach
4. **Request guidance** - Escalate to appropriate authority
5. **Implement correction** - Execute proper doctrinal solution

**Prevention:**
- Pre-execution doctrine validation
- Continuous compliance monitoring
- Regular doctrine refresh cycles
- Automated compliance checking

### 6.2 Invalid Headers
When agents encounter invalid WOLFIE headers:

**Header Validation Errors:**
- Missing required fields
- Invalid YAML syntax
- Unresolvable atom references
- Inconsistent dialog blocks
- Incorrect version information

**Error Response Process:**
1. **Identify specific error** - Determine exact validation failure
2. **Document error context** - Capture current header state
3. **Generate correction plan** - Determine required fixes
4. **Execute corrections** - Fix header issues systematically
5. **Validate corrections** - Ensure header passes all validation

**Header Recovery Procedures:**
- Restore from backup if available
- Reconstruct from dialog history
- Use template for missing sections
- Validate against doctrine requirements

### 6.3 Missing Dialog Threads
When agents cannot find required dialog threads:

**Thread Resolution Process:**
1. **Verify thread name** - Confirm correct naming convention
2. **Check default thread** - Fall back to changelog_dialog.md
3. **Validate thread exists** - Confirm file is accessible
4. **Create entry in available thread** - Use appropriate fallback
5. **Document thread issue** - Report missing thread problem

**Thread Creation Authorization:**
- Agents cannot create new dialog threads
- Thread creation requires human authorization
- Temporary entries go to default thread
- Thread creation must be documented

### 6.4 Cross-Lane Violations
When agents attempt to work outside their lanes:

**Violation Detection:**
- Runtime monitors file access patterns
- Lane boundaries are enforced automatically
- Violations are prevented before execution
- Violation attempts are logged for analysis

**Violation Response:**
1. **Block unauthorized action** - Prevent cross-lane operation
2. **Identify correct agent** - Determine appropriate lane
3. **Create handoff request** - Document coordination need
4. **Wait for coordination** - Pause until proper agent accepts
5. **Execute through proper lane** - Complete work appropriately

**Lane Coordination Process:**
- Clear handoff documentation
- Explicit acceptance by receiving agent
- Complete context transfer
- Validation of coordination success

### 6.5 Recovery Procedures
When errors cannot be resolved automatically:

**Escalation Process:**
1. **Document error completely** - Capture all relevant context
2. **Identify error category** - Classify for appropriate response
3. **Determine escalation path** - Route to correct authority
4. **Provide recovery options** - Suggest possible solutions
5. **Await human intervention** - Pause until guidance received

**Recovery Strategies:**
- **Rollback to previous state** - Restore from known good state
- **Manual correction** - Human intervention to fix issues
- **Doctrine clarification** - Update doctrine to prevent recurrence
- **Process improvement** - Enhance error prevention mechanisms

**Post-Recovery Actions:**
- Document recovery process
- Update error handling procedures
- Improve prevention mechanisms
- Share lessons learned with other agents

---

## 7. Examples

### 7.1 Correct Agent Runtime Flow
```
1. INITIALIZATION
   - Agent: KIRO
   - Task: Update documentation file
   - Lane: Documentation
   - Validation: Task is within lane boundaries ✓

2. CONTEXT ACQUISITION
   - Read existing file: docs/example/EXAMPLE.md
   - Load WOLFIE header: version 4.0.13, last modified by CASCADE
   - Check dialog thread: uses changelog_dialog.md (no dedicated thread)
   - Understand file purpose: Example documentation

3. DOCTRINE LOADING
   - Load WOLFIE_HEADER_DOCTRINE.md ✓
   - Load DIALOG_DOCTRINE.md ✓
   - Load PATCH_DISCIPLINE.md ✓
   - Validate doctrine consistency ✓

4. PATCH EXECUTION
   - Plan: Add new section on best practices
   - Modify: Add content to EXAMPLE.md
   - Update header: version → 4.0.14, dialog block updated
   - Create dialog entry: "Added best practices section"

5. DIALOG WRITING
   - Thread: dialogs/changelog_dialog.md
   - Entry: 
     ## 2026-01-14 15:30:00 UTC
     **Speaker:** KIRO
     **Target:** @everyone
     **Mood:** 0066FF
     **Message:** "Added best practices section to example documentation."

6. HEADER UPDATE
   - file.last_modified_system_version: 4.1.6
   - dialog block matches dialog entry exactly
   - header_atoms updated if needed
   - YAML validation passes ✓

7. TERMINATION
   - Task completed successfully ✓
   - All files updated consistently ✓
   - Dialog entries created ✓
   - System consistency maintained ✓
```

### 7.2 Correct Patch Execution
```
PATCH: Update Database Schema Documentation

AGENT: KIRO (Documentation Lane)
SCOPE: Single task - update schema documentation only
FILES: docs/schema/DATABASE_SCHEMA.md

EXECUTION:
1. Read existing file and understand current state
2. Plan modifications: Add new table documentation
3. Update content: Add lupo_new_table documentation
4. Update WOLFIE header:
   - version: 4.0.14
   - dialog: Updated with change description
5. Create dialog entry in changelog_dialog.md
6. Validate all changes are consistent

RESULT: Single file updated, single task completed, full documentation
```

### 7.3 Correct Dialog Update
```
BEFORE UPDATE:
File: docs/example/EXAMPLE.md
Header dialog block:
  dialog:
    speaker: CASCADE
    target: @dev
    mood_RGB: "FF6600"
    message: "Fixed legacy compatibility issues."

Dialog thread: dialogs/changelog_dialog.md
Latest entry:
## 2026-01-13 14:20:00 UTC
**Speaker:** CASCADE
**Target:** @dev
**Mood:** FF6600
**Message:** "Fixed legacy compatibility issues."

AFTER UPDATE BY KIRO:
File: docs/example/EXAMPLE.md
Header dialog block:
  dialog:
    speaker: KIRO
    target: @everyone
    mood_RGB: "0066FF"
    message: "Added new examples and clarified procedures."

Dialog thread: dialogs/changelog_dialog.md
Latest entry:
## 2026-01-14 16:45:00 UTC
**Speaker:** KIRO
**Target:** @everyone
**Mood:** 0066FF
**Message:** "Added new examples and clarified procedures."

## 2026-01-13 14:20:00 UTC
**Speaker:** CASCADE
**Target:** @dev
**Mood:** FF6600
**Message:** "Fixed legacy compatibility issues."

VALIDATION: Header matches dialog thread ✓
```

### 7.4 Correct Header Update
```
COMPLETE HEADER UPDATE EXAMPLE:

BEFORE:
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.13
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  speaker: CASCADE
  target: @dev
  mood_RGB: "FF6600"
  message: "Previous change description."
tags:
  categories: ["documentation"]
  collections: ["examples"]
  channels: ["dev"]
in_this_file_we_have:
  - Old Content List
file:
  title: "Example File"
  description: "Example documentation"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

AFTER KIRO UPDATE:
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  mood_RGB: "0066FF"
  message: "Updated content and added new examples."
tags:
  categories: ["documentation", "examples"]
  collections: ["examples", "core-docs"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Updated Content List
  - New Examples Section
  - Enhanced Procedures
file:
  title: "Example File"
  description: "Example documentation with enhanced content"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS

---

## 8. Cross-References

- **[WOLFIE Header Doctrine](WOLFIE_HEADER_DOCTRINE.md)** (`docs/doctrine/WOLFIE_HEADER_DOCTRINE.md`) - MANDATORY rules for WOLFIE headers and metadata governance
- **[Dialog Doctrine](DIALOG_DOCTRINE.md)** - MANDATORY rules for dialog system architecture and thread management
- **[Patch Discipline](PATCH_DISCIPLINE.md)** - Development workflow governance and single-task patch requirements
- **[Directory Structure](DIRECTORY_STRUCTURE.md)** - File organization and structural requirements for agent operations
- **[Versioning Doctrine](VERSIONING_DOCTRINE.md)** - Version management and release procedures for agent coordination
- **[Metadata Governance](METADATA_GOVERNANCE.md)** - Comprehensive metadata management framework
- **[Architecture Sync](ARCHITECTURE_SYNC.md)** - Cross-system synchronization and coordination requirements
- **[Agent Prompt Doctrine](AGENT_PROMPT_DOCTRINE.md)** - Agent communication and prompt engineering standards

---

**This Agent Runtime Doctrine is MANDATORY and NON-NEGOTIABLE.**

All agents operating within Lupopedia must follow these runtime requirements exactly. The agent runtime ensures consistent behavior, proper lane separation, and integration with the documentation-as-truth architecture.

> **Agents are extensions of the semantic OS.**  
> **All agent behavior must be documented and traceable.**  
> **Lane boundaries are strictly enforced.**  
> **Documentation is the single source of truth for agent behavior.**

This is architectural doctrine.

---