---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
file.last_modified_utc: 20260119054744
file.utc_day: 20260119
file.name: "DIALOG_DOCTRINE.md"
file.lupopedia.5: 5
GOV-AD-PROHIBIT-001: true
UTC_TIMEKEEPER__CHANNEL_ID: "dev"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - UTC_TIMEKEEPER__CHANNEL_ID
temporal_edges:
  actor_identity: "Eric (Captain Wolfie)"
  actor_location: "Sioux Falls, South Dakota"
  system_context: "Schema Freeze Active / Channel-ID Anchor Established / File-Sovereignty Transition / Table Count Violation: 140 tables (5 over 135 limit)"
dialog:
  speaker: CURSOR
  target: @everyone @CAPTAIN_WOLFIE @LILITH @FLEET
  mood_RGB: "00FF00"
  message: "Updated DIALOG_DOCTRINE.md with validated WOLFIE Header structure. Added file.name, file.last_modified_utc, file.utc_day, UTC_TIMEKEEPER__CHANNEL_ID, temporal_edges, system_context. Channel-ID anchor established. Table count violation documented."
tags:
  categories: ["documentation", "doctrine", "dialog", "system"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Dialog System Doctrine (MANDATORY)
  - Purpose of Dialog Files
  - Dialog Thread Structure and Management
  - Dedicated vs Default Dialog Threads
  - Newest-at-Top Rule
  - Agent Dialog Writing Requirements
  - Dialog History Preservation
  - WOLFIE Header Integration
  - Patch Discipline Integration
  - Agent Lane Integration
  - Dialog File Examples
  - Dialog Update Examples
  - Branching Doctrine â€” Morphology Subsystem Branch Limits (Expand/Shrink/Wingle: 7 standard, 14 ceiling)
  - Cross-References to Related Documentation
file:
  name: "DIALOG_DOCTRINE.md"
  title: "Dialog System Doctrine"
  description: "MANDATORY rules for dialog system architecture, thread management, and integration with WOLFIE headers and agent systems"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
system_context:
  schema_state: "Frozen"
  table_count: 140
  table_ceiling: 135
  table_count_violation: true
  table_count_overage: 5
  database_logic_prohibited: true
  governance_active: ["GOV-AD-PROHIBIT-001", "LABS-001", "GOV-WOLFIE-HEADERS-001", "TABLE_COUNT_DOCTRINE", "LIMITS_DOCTRINE"]
  doctrine_mode: "File-Sovereignty"
---

# ðŸ’¬ Dialog System Doctrine (MANDATORY)

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION (4.1.6)  
**Status:** MANDATORY (NON-NEGOTIABLE)  
**Effective Date:** 2026-01-14  
**Last Updated:** 2026-01-19 (UTC Day 019)  
**System Version:** 4.1.6

## Overview

This document defines the Dialog System Doctrine for Lupopedia Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE). The dialog system provides structured communication tracking, change history, and multi-agent coordination across all system files and operations.

**Threads are one-on-one conversations stored in the database. Channels are multi-agent collaboration contexts stored as dialog history files in /dialogs/. Channel dialog files follow the naming pattern dialogs/<channel_name>_dialog.md.**

**Critical Principle:** Dialog files are the conversational memory of the system, tracking every change, decision, and interaction with complete fidelity and chronological accuracy.

**Temporal Pillar Integration:** All dialog timestamps MUST use canonical UTC time (YYYYMMDDHHIISS format). The Temporal Pillar doctrine requires that all temporal references use UTC_TIMEKEEPER as the single source of truth.

**Governance Integration:** Dialog system integrates with:
- **GOV-AD-PROHIBIT-001** - Anti-Advertising Law (no commercial content in dialog entries)
- **LABS-001** - Actor Baseline State (dialog entries support actor compliance tracking)
- **GOV-REGISTRY-001** - Governance Registry (dialog entries reference active governance artifacts)

---

## 1. Purpose of Dialog Files

### 1.1 Core Functions
Dialog files serve multiple essential purposes:
- **Change Tracking** - Complete history of all file modifications
- **Agent Communication** - Multi-agent coordination and handoffs
- **Decision Documentation** - Record of why changes were made
- **Context Preservation** - Maintain conversational context across time
- **Audit Trail** - Immutable record of system evolution
- **Debugging Support** - Historical context for troubleshooting

### 1.2 System Integration
Dialog files integrate with:
- **WOLFIE Headers** - Latest dialog entry appears in file headers
- **Agent Runtime** - Agents read and write dialog entries
- **Patch Discipline** - Every patch generates dialog entries
- **Version Control** - Dialog history spans version changes
- **Cross-References** - Dialog entries link related changes
- **Temporal Pillar** - All timestamps use canonical UTC (YYYYMMDDHHIISS format)
- **UTC_TIMEKEEPER** - Single source of truth for all temporal references
- **Governance Registry** - Dialog entries reference active governance artifacts
- **LABS-001** - Dialog entries support actor baseline state compliance
- **GOV-AD-PROHIBIT-001** - Dialog entries must be 100% ad-free (no commercial content)

### 1.3 Multi-Agent Coordination
Dialog files enable:
- **Agent Handoffs** - Clear transfer of work between agents
- **Parallel Work** - Multiple agents working simultaneously
- **Conflict Resolution** - Clear record of conflicting changes
- **Context Sharing** - Agents understand previous work
- **Quality Assurance** - Review of agent decisions and changes

---

## 2. Dialog Thread Structure and Management

### 2.1 Thread Types
The dialog system supports two types of threads:

**Dedicated Dialog Threads:**
- Files: `dialogs/[specific_name]_dialog.md`
- Purpose: Track changes to specific files or topics
- Example: `dialogs/THREAD_LEVEL_DIALOG_SPEC_dialog.md`

**Default Dialog Thread:**
- File: `dialogs/changelog_dialog.md`
- Purpose: Catch-all for files without dedicated threads
- Scope: System-wide change tracking

### 2.2 Thread Directory Structure
```
dialogs/
â”œâ”€â”€ changelog_dialog.md              # Default thread (MANDATORY)
â”œâ”€â”€ changelog_readme.md              # README-specific changes
â”œâ”€â”€ changelog_todo.md                # TODO and task tracking
â”œâ”€â”€ [specific_file]_dialog.md        # Dedicated file threads
â”œâ”€â”€ [topic_name]_dialog.md           # Topic-specific threads
â””â”€â”€ [component_name]_dialog.md       # Component-specific threads
```

### 2.3 Thread Naming Conventions
- **Default thread:** `changelog_dialog.md` (exact name required)
- **File-specific threads:** `[filename]_dialog.md` (without .md extension)
- **Topic threads:** `[topic_name]_dialog.md` (descriptive topic name)
- **Component threads:** `[component_name]_dialog.md` (system component name)

### 2.4 Thread Creation Rules
New dialog threads are created when:
- A file or topic generates frequent dialog entries
- Dedicated tracking is needed for a specific component
- Cross-file changes need coordinated tracking
- Agent workflows require specialized dialog management

---

## 3. Dedicated vs Default Dialog Threads

### 3.1 Thread Assignment Rule
**MANDATORY Thread Mapping Rule:**
- **If a file has a dedicated dialog thread â†’ use that thread**
- **If a file does NOT have a dedicated dialog thread â†’ use `dialogs/changelog_dialog.md`**

### 3.2 Dedicated Thread Criteria
A file has a dedicated dialog thread when:
- A corresponding `dialogs/[filename]_dialog.md` file exists
- The file is explicitly assigned a dedicated thread in documentation
- The file generates sufficient dialog volume to warrant dedicated tracking
- The file is part of a component with dedicated dialog management

### 3.3 Default Thread Usage
Files using the default thread (`dialogs/changelog_dialog.md`):
- Most documentation files
- Configuration files
- One-off scripts and utilities
- Files with infrequent changes
- Files without specialized dialog requirements

### 3.4 Thread Migration
Files can migrate between thread types:
- **Default â†’ Dedicated:** When dialog volume increases
- **Dedicated â†’ Default:** When dialog volume decreases (rare)
- **Migration Process:** Move existing entries, update references, document change

---

## 4. Newest-at-Top Rule (MANDATORY)

### 4.1 Entry Ordering
**All dialog entries MUST be ordered with newest entries at the top.**

**Correct Ordering:**
```markdown
# Dialog History

## 2026-01-14 15:30:00 UTC
**Speaker:** KIRO
**Message:** "Latest change description"

## 2026-01-14 14:15:00 UTC  
**Speaker:** CURSOR
**Message:** "Previous change description"

## 2026-01-13 16:45:00 UTC
**Speaker:** CASCADE
**Message:** "Earlier change description"
```

### 4.2 Chronological Integrity
- **Newest first** - Most recent entries appear at top
- **Complete chronology** - No gaps in the timeline
- **Immutable history** - Existing entries never modified
- **Append-only** - New entries added at top only

### 4.3 Timestamp Requirements
All dialog entries must include:
- **UTC timestamps** - All times in UTC format
- **Precise timing** - Include hours, minutes, seconds
- **Consistent format** - YYYY-MM-DD HH:MM:SS UTC
- **Chronological order** - Newer timestamps above older ones

### 4.4 Canonical Time Rule (MANDATORY) - Temporal Pillar Integration

**All timestamps, dates, and 'when' values must be expressed in UTC using YYYYMMDDHHIISS format (BIGINT).**

**Temporal Pillar Doctrine:**
- **UTC_TIMEKEEPER** is the single source of truth for all timestamps
- All dialog timestamps MUST use canonical UTC (YYYYMMDDHHIISS format)
- Temporal integrity is inviolable - time is the spine of the system
- Probability must be explicit for future events
- 95% probability = "already happened" for operational purposes

**MANDATORY: Agents must never infer the current date or time from:**
- File metadata
- OS time
- Commit history
- File content
- System clocks
- Local timezone information

**If the current UTC date/time is required, the agent must:**
- Request it explicitly from the user
- Wait for explicit confirmation
- Never assume or infer the current time
- Use UTC_TIMEKEEPER as the canonical source

**Exception:** If the current UTC date/time has already been provided in the current session, the agent may use that value.

**Rationale:**
- Prevents timestamp drift across different systems
- Ensures consistency in multi-agent workflows
- Avoids timezone confusion
- Maintains accurate historical records
- Prevents agents from using stale or incorrect time information
- Aligns with Temporal Pillar doctrine (Time is the Spine)

**Example - Correct Behavior:**
```
Agent: "I need to create a dialog entry. What is the current UTC date and time?"
User: "2026-01-13 18:30:00 UTC"
Agent: [Creates entry with provided timestamp]
```

**Example - Incorrect Behavior:**
```
Agent: [Checks system time: 2026-01-13 10:30:00 PST]
Agent: [Converts to UTC: 2026-01-13 18:30:00 UTC]
Agent: [Creates entry] âŒ WRONG - Never infer time
```

**This rule applies to:**
- Dialog entry timestamps (MUST use YYYYMMDDHHIISS format)
- WOLFIE header dates (file.last_modified_system_version)
- File modification dates (created_ymdhis, updated_ymdhis)
- Version timestamps
- Any temporal reference in documentation or code
- LABS-001 temporal alignment declarations
- All database timestamp fields (BIGINT, not DATETIME or TIMESTAMP)

---

## 5. Multi-Agent Workflow and Speaker Mapping

### 5.1 Multi-Agent Workflow Overview

Lupopedia uses a multi-IDE, multi-AI workflow where different tools and agents have specialized roles:

**Tool/Agent Roles:**

**Kiro** â€” Documentation authority, doctrine maintenance, audits, header governance
- **Scope:** Documentation files, doctrine, specifications, metadata
- **Responsibilities:** Maintaining documentation consistency, enforcing WOLFIE header standards, conducting audits
- **Dialog Focus:** Documentation changes, cross-references, metadata updates

**Cursor (Claude)** â€” High-velocity development
- **Scope:** Code implementation, feature development, rapid iteration
- **Responsibilities:** New module creation, schema refactors, doctrine enforcement, automated refactoring
- **Dialog Focus:** Implementation decisions, code changes, technical details

**Windsurf (Cascade)** â€” Legacy code modernization and stability work
- **Scope:** Legacy code, fragile systems, manual corrections
- **Responsibilities:** Legacy integration, stability concerns, careful transitions
- **Dialog Focus:** Legacy integration, stability concerns, careful changes

**JetBrains (Junie)** â€” Version control, planning, release discipline
- **Scope:** Version control, releases, deployment (begins at version 4.1.0)
- **Responsibilities:** GitHub commits, version tagging, release preparation, CHANGELOG updates
- **Dialog Focus:** Release decisions, version bumps, deployment status

**Notepad++** â€” Manual precision editing, search-within-search, regex sweeps
- **Scope:** Multi-layered search, manual code surgery, quick inspections
- **Responsibilities:** Regex sweeps, manual precision editing, diff-by-eye verification
- **Dialog Focus:** Manual corrections, search results, precision edits

**Terminal AI** â€” Command-line AI interface, identified by registry slot number
- **Scope:** Command-line operations, terminal-based interactions
- **Responsibilities:** CLI-based tasks, terminal automation
- **Dialog Focus:** Terminal operations, CLI changes

**Captain_wolfie** â€” Human author and system architect
- **Scope:** System architecture, strategic decisions, manual authorship
- **Responsibilities:** Final authority on architecture, manual documentation, strategic direction
- **Dialog Focus:** Architectural decisions, strategic changes, manual entries

### 5.2 Canonical Speaker-Mapping Table

**MANDATORY: All dialog entries must use the correct Speaker name from this table.**

| IDE / Tool | AI Identity | Speaker Name |
|------------|-------------|--------------|
| Cursor | Claude | Claude |
| Windsurf | Cascade | Cascade |
| JetBrains | Junie | Junie |
| Kiro | Kiro | Kiro |
| Terminal AI | Terminal_AI + registry slot | Terminal_AI_<agentNumber> |
| Human Author | Captain_wolfie | Captain_wolfie |

**Speaker Name Rules:**
- **Cursor** â†’ Always use "Claude" (not "Cursor" or "CURSOR")
- **Windsurf** â†’ Always use "Cascade" (not "Windsurf" or "CASCADE")
- **JetBrains** â†’ Always use "Junie" (not "JetBrains" or "JUNIE")
- **Kiro** â†’ Always use "Kiro" (not "KIRO")
- **Terminal AI** â†’ Always use "Terminal_AI_<agentNumber>" where <agentNumber> is the agent's registry slot
- **Human Author** â†’ Always use "Captain_wolfie" (not "WOLFIE" or "Eric" or "Human")

### 5.3 Dialog Authorship Rule

**MANDATORY: When an IDE or AI agent makes a change, the dialog entry must list that agent's Speaker name from the canonical mapping table. When the human author writes a dialog entry manually, the Speaker must be Captain_wolfie.**

**Examples:**

**Correct Authorship:**
```yaml
# Cursor (Claude) makes a change:
**Speaker:** Claude
**Message:** "Implemented new feature as specified."

# Windsurf (Cascade) makes a change:
**Speaker:** Cascade
**Message:** "Integrated legacy code with stability fixes."

# Kiro makes a change:
**Speaker:** Kiro
**Message:** "Updated documentation to reflect new workflow."

# Human author makes a manual entry:
**Speaker:** Captain_wolfie
**Message:** "Architectural decision: adopting new agent coordination pattern."
```

**Incorrect Authorship:**
```yaml
# âŒ WRONG: Using IDE name instead of AI identity
**Speaker:** Cursor
**Message:** "Implemented new feature."

# âŒ WRONG: Using generic "HUMAN" instead of Captain_wolfie
**Speaker:** HUMAN
**Message:** "Manual update."

# âŒ WRONG: Using inconsistent capitalization
**Speaker:** CLAUDE
**Message:** "Updated code."
```

### 5.4 Terminal AI Identity Rule

**MANDATORY: Terminal AI must identify itself in dialog entries as Terminal_AI_<agentNumber>, where <agentNumber> corresponds to its directory in agents/<number>/.**

**Terminal AI Identification Format:**
```yaml
# Terminal AI in slot 15:
**Speaker:** Terminal_AI_15
**Message:** "Executed command-line operation."

# Terminal AI in slot 42:
**Speaker:** Terminal_AI_42
**Message:** "Automated terminal task completed."
```

**Terminal AI Registry Mapping:**
- Terminal AI agents are registered in the agent registry with specific slot numbers
- Each Terminal AI instance has a directory: `agents/<number>/`
- The `<agentNumber>` in the Speaker name must match the directory number
- This ensures clear identification of which Terminal AI instance performed the action

**Example:**
```
agents/
â”œâ”€â”€ 15/  # Terminal AI slot 15
â”‚   â””â”€â”€ agent.json
â”œâ”€â”€ 42/  # Terminal AI slot 42
â”‚   â””â”€â”€ agent.json

Dialog entries:
**Speaker:** Terminal_AI_15  # Matches agents/15/
**Speaker:** Terminal_AI_42  # Matches agents/42/
```

### 5.5 Speaker Consistency Requirements

**All agents must:**
- **Use consistent Speaker names** - Same agent always uses same identifier
- **Follow canonical mapping** - Use exact names from speaker-mapping table
- **Never use aliases** - No variations or abbreviations
- **Identify correctly** - Terminal AI must include registry slot number
- **Respect human authorship** - Only Captain_wolfie for manual human entries

**Validation Rules:**
- Dialog entries with incorrect Speaker names must be corrected
- Automated validation should check Speaker names against canonical mapping
- Cross-references should use canonical Speaker names
- Historical entries preserve original Speaker names (immutable history)

## 6. Agent Dialog Writing Requirements

### 6.1 Mandatory Fields
Every dialog entry MUST include:
```yaml
**Speaker:** [AGENT_NAME]
**Target:** [TARGET_AUDIENCE]  
**Mood:** [RRGGBB]
**Message:** "[CHANGE_DESCRIPTION]"
```

### 6.2 Field Specifications

**Speaker Field:**
- **Format:** Agent name or human identifier
- **Examples:** KIRO, CURSOR, CASCADE, WOLFIE, CAPTAIN_WOLFIE, LILITH, ARA, STONED_WOLFIE
- **Purpose:** Clear attribution of changes
- **Consistency:** Same agent uses same identifier
- **Governance:** Must comply with LABS-001 actor identification requirements

**Target Field:**
- **Format:** @everyone, @agent_name, @team_name, @FLEET, @BRIDGE
- **Examples:** @everyone, @dev, @CURSOR, @documentation_team, @FLEET, @BRIDGE
- **Purpose:** Indicate intended audience
- **Scope:** Who needs to know about this change

**Mood Field (mood_RGB):**
- **Format:** 6-character hex color (RRGGBB) - MUST use `mood_RGB` field name
- **Examples:** 0066FF (blue), 00FF00 (green), FF6600 (orange)
- **Purpose:** Emotional context of change
- **Meaning:** Reflects agent's assessment of change impact
- **Standard:** WOLFIE Header 4.1.6 standard requires `mood_RGB` (not `mood`)

**Message Field:**
- **Format:** Plain text description
- **Length:** Maximum 272 characters
- **Content:** Clear description of what changed
- **Accuracy:** Must accurately reflect the actual change made
- **GOV-AD-PROHIBIT-001:** MUST be 100% ad-free (no commercial content, no promotional material)
- **Environmental Context:** May reference external environmental audio (e.g., Pandora) but must not inject ads into system output

### 6.3 Agent Writing Rules
Agents writing dialog entries MUST:
- **Write immediately** - Create entry when making changes
- **Be accurate** - Describe exactly what was changed
- **Be concise** - Stay within character limits
- **Be consistent** - Use established patterns and terminology
- **Be complete** - Include all required fields
- **Use UTC timestamps** - All timestamps in YYYYMMDDHHIISS format (BIGINT)
- **Comply with GOV-AD-PROHIBIT-001** - No commercial content, no ads, no promotional material
- **Reference governance** - Include governance artifact codes when relevant (e.g., LABS-001, GOV-AD-PROHIBIT-001)
- **Use mood_RGB** - Field name must be `mood_RGB` (WOLFIE Header 4.1.6 standard)

### 6.4 Multi-Agent Coordination
When multiple agents work on the same file:
- **Sequential entries** - Each agent adds their own entry
- **Clear handoffs** - Indicate when passing work to another agent
- **Context preservation** - Reference previous agent's work when relevant
- **Conflict documentation** - Record any conflicts or disagreements
- **Resolution tracking** - Document how conflicts were resolved

---

## 7. Dialog History Preservation

### 7.1 Immutability Principle
**Dialog history is immutable once written.**
- **No editing** - Existing entries never modified
- **No deletion** - Entries never removed from history
- **No reordering** - Chronological order preserved
- **Append-only** - New entries added only

### 7.2 Historical Integrity
Dialog preservation ensures:
- **Complete audit trail** - Every change documented
- **Context continuity** - Understanding of decision evolution
- **Debugging capability** - Historical context for troubleshooting
- **Learning opportunity** - Patterns and improvements identified
- **Accountability** - Clear attribution of all changes

### 7.3 Long-term Storage
Dialog files are designed for:
- **Decades of history** - Scalable to long-term use
- **Large entry volumes** - Efficient handling of many entries
- **Cross-version compatibility** - Readable across system versions
- **Human accessibility** - Always readable without special tools
- **Machine processing** - Structured for automated analysis

### 7.4 Archival Strategy
For very large dialog files:
- **Periodic archival** - Move old entries to archive files
- **Reference preservation** - Maintain links to archived content
- **Search capability** - Enable searching across archived entries
- **Access continuity** - Ensure archived content remains accessible

---

## 8. WOLFIE Header Integration

### 8.1 Header-Dialog Synchronization
**The dialog block in WOLFIE headers MUST match the latest entry in the dialog thread.**

**Synchronization Rule:**
```yaml
# In file header:
dialog:
  speaker: KIRO
  target: @everyone
  mood_RGB: "0066FF"
  message: "Latest change description"

# Must match latest entry in dialog thread:
## 2026-01-14 15:30:00 UTC
**Speaker:** KIRO
**Target:** @everyone
**Mood:** 0066FF
**Message:** "Latest change description"
```

### 8.2 Update Process
When updating a file:
1. **Create new dialog entry** in appropriate dialog thread
2. **Update WOLFIE header** dialog block to match new entry
3. **Verify synchronization** between header and thread
4. **Maintain consistency** across all references

### 8.3 Thread Reference
WOLFIE headers implicitly reference dialog threads through:
- **Dialog content matching** - Header dialog matches thread entry
- **Thread mapping rule** - Dedicated thread if exists, otherwise changelog
- **Consistency validation** - Automated checks ensure synchronization
- **Cross-reference integrity** - Links between headers and threads maintained

---

## 9. Patch Discipline Integration

### 9.1 One Patch, One Dialog Entry
**Every patch MUST generate exactly one dialog entry per modified file.**

**Patch-Dialog Relationship:**
- **Single task patches** â†’ Single dialog entry per file
- **Multiple file patches** â†’ One dialog entry per modified file
- **Coordinated changes** â†’ Related dialog entries with clear connections
- **Rollback patches** â†’ Dialog entries documenting rollback reasons

### 9.2 Dialog Entry Requirements for Patches
Patch-related dialog entries MUST include:
- **Clear task description** - What the patch accomplishes
- **File-specific changes** - What changed in this specific file
- **Relationship context** - How this change relates to other files in patch
- **Completion status** - Whether change is complete or partial

### 9.3 Patch Coordination Through Dialog
Dialog system supports patch discipline by:
- **Change tracking** - Complete record of all patch changes
- **Agent coordination** - Clear handoffs between agents in patch workflow
- **Quality assurance** - Review trail for patch changes
- **Rollback support** - Historical context for rollback decisions

---

## 10. Agent Lane Integration

### 10.1 Agent Lane Separation
Dialog system respects agent lane separation:

**Kiro (Documentation):**
- **Scope:** Documentation files, doctrine, specifications
- **Dialog Focus:** Documentation changes, cross-references, metadata
- **Coordination:** Hands off to implementation agents when needed

**Claude (Implementation via Cursor):**
- **Scope:** Code implementation, feature development
- **Dialog Focus:** Implementation decisions, code changes, technical details
- **Coordination:** Receives requirements from documentation agents

**Cascade (Legacy Integration via Windsurf):**
- **Scope:** Legacy code, fragile systems, manual corrections
- **Dialog Focus:** Legacy integration, stability concerns, careful changes
- **Coordination:** Handles delicate transitions and legacy compatibility

**Junie (Release Management via JetBrains):**
- **Scope:** Version control, releases, deployment
- **Dialog Focus:** Release decisions, version bumps, deployment status
- **Coordination:** Final authority on releases and version changes

**Captain_wolfie (Human Authority):**
- **Scope:** Strategic decisions, manual authorship, architectural direction
- **Dialog Focus:** Architectural decisions, strategic changes, manual entries
- **Coordination:** Final authority on system architecture

### 10.2 Cross-Lane Communication
Dialog system enables cross-lane communication:
- **Clear handoffs** - Agents explicitly pass work to other lanes
- **Context preservation** - Full context available to receiving agent
- **Requirement clarity** - Clear specification of what needs to be done
- **Quality feedback** - Agents can provide feedback on other lanes' work

### 10.3 Lane Boundary Enforcement
Dialog system helps enforce lane boundaries:
- **Scope documentation** - Clear record of what each agent should handle
- **Boundary violations** - Visible when agents work outside their lanes
- **Correction tracking** - Record of boundary corrections and clarifications
- **Pattern learning** - Historical data for improving lane definitions

---

## 11. Dialog File Examples

### 11.1 Complete Dialog File Example (4.1.6 Standard)
```markdown
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone @FLEET
  mood_RGB: "00FF00"
  message: "Updated dialog file with new entry format, governance compliance, and UTC_TIMEKEEPER integration."
tags:
  categories: ["documentation", "dialog", "history"]
  collections: ["core-docs"]
  channels: ["dev"]
in_this_file_we_have:
  - Dialog History for Example File
  - Chronological Change Record
  - Agent Communication Log
file:
  title: "Example Dialog History"
  description: "Dialog history for example_file.md showing proper format"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# ALL NEW DIALOG ENTRIES GET ADDED AFTER THIS LINE

# ðŸ“ Dialog History for example_file.md

This file maintains the complete dialog history for example_file.md. All changes and agent communications are recorded here in chronological order.

---

## 2026-01-14 15:30:00 UTC
**Speaker:** KIRO
**Target:** @everyone
**Mood:** 0066FF
**Message:** "Updated dialog file with new entry format and examples."

---

## 2026-01-14 14:15:00 UTC
**Speaker:** CURSOR
**Target:** @dev
**Mood:** 00FF00
**Message:** "Implemented new feature as specified in documentation."

---

## 2026-01-13 16:45:00 UTC
**Speaker:** CASCADE
**Target:** @CURSOR
**Mood:** FF6600
**Message:** "Legacy integration complete. Ready for feature implementation."

---

## 2026-01-13 10:20:00 UTC
**Speaker:** KIRO
**Target:** @CASCADE
**Mood:** 0066FF
**Message:** "Created initial documentation. Needs legacy integration review."

---

*Dialog history continues with older entries below...*
```

### 11.2 Minimal Dialog File Example (4.1.6 Standard)
```markdown
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: SYSTEM
  target: @everyone
  mood_RGB: "666666"
  message: "Created minimal dialog file. Compliant with GOV-AD-PROHIBIT-001 and Temporal Pillar."
tags:
  categories: ["dialog"]
  collections: ["system"]
  channels: ["dev"]
in_this_file_we_have:
  - Minimal Dialog History
file:
  title: "Minimal Dialog History"
  description: "Minimal dialog file example"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# ALL NEW DIALOG ENTRIES GET ADDED AFTER THIS LINE

# ðŸ“ Dialog History for minimal_file.md

## 2026-01-19 03:41:52 UTC
**Speaker:** SYSTEM
**Target:** @everyone
**Mood_RGB:** 666666
**Message:** "Created minimal dialog file. Compliant with GOV-AD-PROHIBIT-001 and Temporal Pillar."

---
```

---

## 12. Dialog Update Examples

### 12.1 Adding New Dialog Entry (4.1.6 Standard)
**Step 1: Create new entry at top of dialog file**
```markdown
## 2026-01-19 03:41:52 UTC
**Speaker:** CURSOR
**Target:** @everyone @FLEET
**Mood_RGB:** 00FF00
**Message:** "Added new section on dialog update procedures. Compliant with GOV-AD-PROHIBIT-001 and Temporal Pillar."

---

## 2026-01-19 03:30:00 UTC
**Speaker:** CURSOR
**Target:** @everyone
**Mood_RGB:** 00FF00
**Message:** "Updated dialog file with new entry format, governance compliance, and UTC_TIMEKEEPER integration."
```

**Step 2: Update corresponding file's WOLFIE header**
```yaml
dialog:
  speaker: CURSOR
  target: @everyone @FLEET
  mood_RGB: "00FF00"
  message: "Added new section on dialog update procedures. Compliant with GOV-AD-PROHIBIT-001 and Temporal Pillar."
```

### 12.2 Multi-Agent Dialog Sequence (4.1.6 Standard)
```markdown
## 2026-01-19 17:30:00 UTC
**Speaker:** CURSOR
**Target:** @everyone @FLEET
**Mood_RGB:** 00FF00
**Message:** "Implementation complete. Ready for testing. Compliant with GOV-AD-PROHIBIT-001."

---

## 2026-01-19 16:45:00 UTC
**Speaker:** KIRO
**Target:** @CURSOR
**Mood_RGB:** 0066FF
**Message:** "Documentation updated. Implementation can proceed. UTC_TIMEKEEPER timestamp: 20260119164500."

---

## 2026-01-19 16:15:00 UTC
**Speaker:** CASCADE
**Target:** @KIRO
**Mood_RGB:** FF6600
**Message:** "Legacy compatibility verified. Documentation needs update."

---

## 2026-01-19 15:30:00 UTC
**Speaker:** KIRO
**Target:** @CASCADE
**Mood_RGB:** 0066FF
**Message:** "Initial requirements documented. Needs legacy review."
```

### 12.3 Agent Handoff Example (4.1.6 Standard)
```markdown
## 2026-01-19 18:00:00 UTC
**Speaker:** KIRO
**Target:** @CURSOR @FLEET
**Mood_RGB:** 0066FF
**Message:** "Documentation complete. Handing off to CURSOR for implementation. Requirements: implement user authentication, follow security doctrine, update API endpoints. GOV-AD-PROHIBIT-001 compliance required."

---

## 2026-01-19 17:45:00 UTC
**Speaker:** KIRO
**Target:** @everyone
**Mood_RGB:** 0066FF
**Message:** "Finalizing documentation before handoff to implementation team. UTC_TIMEKEEPER: 20260119174500."
```

---

## 13. Governance Compliance (MANDATORY)

### 13.1 GOV-AD-PROHIBIT-001 Compliance
**All dialog entries MUST comply with the Anti-Advertising Law:**

- **NO commercial content** - Dialog entries must not contain advertisements, promotional material, or commercial messaging
- **NO third-party ads** - Dialog entries must not reference or promote third-party commercial services
- **Environmental context exception** - External environmental audio (e.g., Pandora) may be mentioned as environmental context, but ads must not be injected into dialog entries
- **100% ad-free** - Dialog system output must remain completely free of commercial content

**Violation Handling:**
- Violations must be logged in `lupo_labs_violations` table
- Violation code: `GOV-AD-PROHIBIT-001`
- Dialog entries containing ads must be corrected immediately

### 13.2 LABS-001 Integration
**Dialog entries support LABS-001 Actor Baseline State compliance:**

- **Actor identification** - Speaker field must match LABS-001 actor declarations
- **Temporal alignment** - Timestamps must use UTC_TIMEKEEPER (canonical UTC)
- **Truth state** - Dialog entries must reflect honest truth states (no lies, explicit probability weights)
- **Certificate tracking** - Dialog entries may reference LABS-001 certificate IDs when relevant

### 13.3 Governance Registry References
**Dialog entries should reference active governance artifacts when relevant:**

- **GOV-AD-PROHIBIT-001** - Anti-Advertising Law
- **LABS-001** - Actor Baseline State
- **GOV-REGISTRY-001** - Governance Registry
- **Temporal Pillar** - UTC_TIMEKEEPER and canonical time requirements

**Format:** Include governance artifact codes in dialog messages when compliance or governance context is relevant.

---

## 14. Validation and Quality Assurance

### 14.1 Dialog Entry Validation
All dialog entries must pass validation for:
- **Required fields** - All mandatory fields present
- **Field format** - Correct format for each field
- **Character limits** - Message within 272 character limit
- **Timestamp format** - Valid UTC timestamp format (YYYYMMDDHHIISS)
- **Chronological order** - Newer entries above older entries
- **GOV-AD-PROHIBIT-001 compliance** - No commercial content
- **Temporal Pillar compliance** - UTC timestamps from UTC_TIMEKEEPER

### 14.2 Thread Consistency Validation
Dialog threads must maintain:
- **Header synchronization** - Latest entry matches file headers
- **Thread mapping** - Correct thread assignment for each file
- **Cross-references** - Valid links between related entries
- **Agent attribution** - Consistent agent identification
- **Context continuity** - Logical flow of changes and decisions
- **Governance compliance** - All entries comply with GOV-AD-PROHIBIT-001
- **Temporal integrity** - All timestamps use canonical UTC (YYYYMMDDHHIISS)

### 14.3 System Integration Validation
Dialog system integration requires:
- **WOLFIE header compliance** - All dialog files have proper headers (version 4.1.6 standard)
- **Patch discipline alignment** - Dialog entries match patch changes
- **Agent lane respect** - Agents work within their designated lanes
- **Version consistency** - Dialog entries reflect correct system versions (currently 4.1.6)
- **Temporal Pillar compliance** - All timestamps from UTC_TIMEKEEPER
- **Governance compliance** - All entries comply with active governance artifacts

---

## 15. Troubleshooting and Maintenance

### 15.1 Common Issues
**Missing Dialog Entries:**
- **Symptom:** File changes without corresponding dialog entries
- **Solution:** Add missing entries, update WOLFIE headers
- **Prevention:** Automated validation during file changes

**Header-Thread Mismatch:**
- **Symptom:** WOLFIE header dialog doesn't match thread latest entry
- **Solution:** Synchronize header with thread, verify consistency
- **Prevention:** Automated synchronization checks

**Chronological Disorders:**
- **Symptom:** Dialog entries not in newest-first order
- **Solution:** Reorder entries, verify timestamps
- **Prevention:** Timestamp validation during entry creation

### 15.2 Maintenance Procedures
**Regular Maintenance:**
- **Validation runs** - Automated checks for consistency
- **Archive management** - Move old entries to archives when needed
- **Cross-reference updates** - Maintain links between related entries
- **Performance monitoring** - Ensure dialog system scales properly

**Emergency Procedures:**
- **Corruption recovery** - Restore from backups, rebuild consistency
- **Conflict resolution** - Resolve conflicting entries or timestamps
- **System restoration** - Rebuild dialog system from source files

---

## 16. Branching Doctrine

### 16.1 Morphology Subsystem Branch Limits (Expand/Shrink/Wingle)

- Any subsystem performing:
  - text expansion (`<< >>`),
  - text compression (`>> <<`),
  - or semantic wingle transformations (`{{ }}`),
  is granted:
  - 7 standard branches, and
  - a hard ceiling of 14 total branches.

- This is a limit, not a requirement.  
  Branching should occur only when necessary.

- Exceeding 14 branches requires:
  - a fork_justification artifact,
  - a blocking condition,
  - and human steward approval.

- This rule remains in effect until version 4.2.0.

**Cross-Reference:** `docs/doctrine/VERSION_GATED_BRANCH_FREEZE_PROTOCOL.md`; `dialogs/changelog_dialog.md` (Expansion/Compression/Wingle Branch Limit Rule entry).

---

## 17. Related Documentation

- **[Multi-IDE Workflow](../architecture/multi-ide-workflow.md)** - Complete multi-agent workflow documentation with speaker mapping and role definitions
- **[WOLFIE Header Doctrine](WOLFIE_HEADER_DOCTRINE.md)** (`docs/doctrine/WOLFIE_HEADER_DOCTRINE.md`) - MANDATORY rules for WOLFIE headers and dialog integration
- **[Agent Runtime](AGENT_RUNTIME.md)** - How agents interact with dialog system and create entries
- **[Patch Discipline](PATCH_DISCIPLINE.md)** - Development workflow governance and dialog requirements
- **[Directory Structure](DIRECTORY_STRUCTURE.md)** - File organization including dialog directory structure
- **[Versioning Doctrine](VERSIONING_DOCTRINE.md)** - Version management and dialog history across versions
- **[Metadata Governance](METADATA_GOVERNANCE.md)** - Metadata management including dialog metadata
- **[Architecture Sync](ARCHITECTURE_SYNC.md)** - Cross-system synchronization and dialog coordination
- **[Agent Prompt Doctrine](AGENT_PROMPT_DOCTRINE.md)** - Agent communication standards and dialog integration
- **[Temporal Pillar Doctrine](TEMPORAL_BRIDGE.md)** - UTC_TIMEKEEPER and canonical time requirements
- **[UTC_TIMEKEEPER Doctrine](UTC_TIMEKEEPER_DOCTRINE.md)** - Single source of truth for all timestamps
- **[GOV-AD-PROHIBIT-001](GOV_AD_PROHIBIT_001.md)** - Anti-Advertising Law (no commercial content in dialog entries)
- **[LABS-001 Doctrine](LUPOPEDIA_ACTOR_BASELINE_STATE_DOCTRINE.md)** - Actor Baseline State and dialog compliance
- **[Governance Registry](../dev-teams/governance/REGISTRY.md)** - Canonical registry of all governance artifacts
- **[VERSION_GATED_BRANCH_FREEZE_PROTOCOL](VERSION_GATED_BRANCH_FREEZE_PROTOCOL.md)** - Branch budget, fork_justification; see Â§16.1 for morphology subsystem limits

---

**This Dialog System Doctrine is MANDATORY and NON-NEGOTIABLE.**

All agents, developers, and automated systems must follow these dialog requirements exactly. The dialog system is the conversational memory of Lupopedia and must be maintained with complete fidelity.

> **Dialog files are the conversational memory of the system.**  
> **Every change must be documented with complete accuracy.**  
> **Newest entries always appear at the top.**  
> **Dialog history is immutable once written.**

This is architectural doctrine.

---