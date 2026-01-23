---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: WOLFIE
  target: @everyone
  message: "Official Lupopedia multi-IDE workflow documentation. This workflow uses specialized tools, each chosen for a specific cognitive or operational role, all running simultaneously with careful tab management. All folder references use lowercase per FOLDER_NAMING_DOCTRINE."
  mood: "00FF00"
tags:
  categories: ["documentation", "architecture", "workflow"]
  collections: ["core-docs", "dev-guides"]
  channels: ["dev", "internal"]
file:
  title: "Lupopedia Multi-IDE Workflow"
  description: "Official workflow documentation for Lupopedia's multi-IDE, multi-AI development process"
  version: "4.0.2"
  status: published
  author: "WOLFIE"
---

# Lupopedia Multi-IDE Workflow

**Official Workflow Documentation**  
**Version 4.0.2**  
**Effective Date: 2025-01-06**

## Overview

Lupopedia is developed using a **multi-IDE, multi-AI workflow** designed for speed, safety, and architectural clarity. Unlike typical development setups that rely on a single IDE, Lupopedia uses specialized tools, each chosen for a specific cognitive or operational role.

**Key Principle:** This workflow mirrors the architecture of Lupopedia itself: many agents, each with a dedicated function, working together.

**Critical Detail:** All IDEs run simultaneously, but active tabs never work on the same files. This parallel workflow replaces sequential file locks with disciplined tab management.

---

## IDE Roles

### Canonical Speaker-Mapping Table

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

**Dialog Authorship Rule:**
When an IDE or AI agent makes a change, the dialog entry must list that agent's Speaker name from the canonical mapping table. When the human author writes a dialog entry manually, the Speaker must be Captain_wolfife.

**Terminal AI Identity Rule:**
Terminal AI must identify itself in dialog entries as Terminal_AI_<agentNumber>, where <agentNumber> corresponds to its directory in agents/<number>/.

---

### 1. Kiro â€” Documentation Authority & Doctrine Maintenance

**Kiro is used for:**
- Documentation authority
- Doctrine maintenance
- Documentation audits
- Header governance
- Cross-reference management
- Metadata consistency
- Documentation structure

**Role:** Kiro acts as the documentation authority, maintaining consistency across all documentation files, enforcing WOLFIE header standards, and conducting systematic audits.

**Why Kiro?**  
Because it excels at:
- Documentation consistency
- Metadata management
- Cross-reference validation
- Header governance
- Doctrine enforcement in documentation
- Systematic audits
- Documentation structure maintenance

**Kiro is your "documentation layer."**

---

### 2. Cursor (Claude) â€” Fast Prototyping & New Feature Development**
- Rapid iteration
- New module creation
- Schema refactors
- Agent registry updates
- Doctrine enforcement
- Slot-based folder moves
- Self-correcting refactor logic
- Multi-file consistency updates (on stable code)
- SQL generation
- Automated refactoring (after Cascade handoff)

**Role:** Cursor acts like an autonomous refactor engine that follows doctrine and performs precise, mechanical tasks quickly.

**Why Cursor?**  
Because it excels at:
- Rewriting
- Refactoring
- Generating SQL
- Moving files
- Updating metadata
- Following strict rules
- Multi-file consistency passes

**âš ï¸ IMPORTANT:** See [CURSOR_CASCADE_ROLE_SEPARATION_DOCTRINE.md](../doctrine/CURSOR_CASCADE_ROLE_SEPARATION_DOCTRINE.md) â€” Cursor MUST NOT modify legacy Crafty Syntax code until Cascade completes stabilization and explicit handoff.

**Cursor is your "speed layer."**

---

### 3. Windsurf (Cascade) â€” Legacy Integration & Deep Refactors

**Cascade is used for:**
- Integrating legacy Crafty Syntax code
- Deep PHP migrations
- Fragile code corrections
- Manual, step-by-step edits
- Legacy behavior preservation
- Structural cleanup before handoff

**Role:** Cascade is your manual controlled editor â€” the one who carefully handles legacy code with human judgment, then hands off stable code to Cursor for automated refactoring.

**âš ï¸ IMPORTANT:** See [CURSOR_CASCADE_ROLE_SEPARATION_DOCTRINE.md](../doctrine/CURSOR_CASCADE_ROLE_SEPARATION_DOCTRINE.md) â€” Cascade handles all legacy Crafty Syntax work, stabilizes code, then explicitly hands off to Cursor for automated refactoring.

---

### 4. JetBrains (Junie) â€” Deployment, GitHub, Versioning

**âš ï¸ IMPORTANT: JetBrains Integration Begins at Version 4.1.0**

**Current Status (4.0.x series):**
- JetBrains **NOT** in workflow until version 4.1.0
- Lupopedia is still in initial release phase (4.0.x)
- First public release will be version 4.1.0
- **Temporary Workaround:** Cursor handles version bumps until 4.1.0

**After Version 4.1.0 (Full Workflow):**

**JetBrains is used for:**
- GitHub commits
- Version tagging
- Release preparation
- Deployment packaging
- Code inspections
- Static analysis
- CHANGELOG updates
- Semantic version bumps
- Release finalization

**Role:** JetBrains is your release engineer â€” the one who ensures the codebase is clean, consistent, and ready for production. **JetBrains is the moment work becomes official.**

**Why JetBrains Changes the Version Number:**

JetBrains = the moment the code stops being "in progress" and becomes "official." This is where you:
- Commit to GitHub
- Tag releases
- Update CHANGELOG
- Bump semantic version
- Prepare deployment
- Run final inspections
- Finalize structure

**This is exactly how large, disciplined engineering teams work** â€” except you're doing it with AI agents instead of junior developers. Version numbers are **milestones**, not timestamps. This is the release gate.

**Temporary Workaround (4.0.x series):**
- Cursor temporarily handles version bumps (semantic versioning still enforced)
- Cursor updates CHANGELOG.md (in development/stabilization phase)
- Version numbers still represent milestones, not timestamps
- Git tagging/GitHub commits not available until 4.1.0 (no `.git` folder until 4.1.0)
- See [VERSIONING_DOCTRINE.md](../doctrine/VERSIONING_DOCTRINE.md) for details

---

### 5. Notepad++ â€” Manual Precision Editing

**Notepad++ is used for:**
- Multi-layered search
- Search-in-search-results
- Regex sweeps
- Manual code surgery
- Quick inspections
- Diff-by-eye

**Role:** Notepad++ is your scalpel â€” the tool you use when you need absolute precision and zero AI interference.

This is where your decades of raw engineering experience shine.

---

### 6. Terminal AI â€” Command-Line AI Interface

**Terminal AI is used for:**
- Command-line operations
- Terminal-based interactions
- CLI automation
- Terminal task execution

**Role:** Terminal AI provides command-line AI interface capabilities, identified by its registry slot number.

**Terminal AI Identity:**
- Each Terminal AI instance has a registry slot number
- Directory structure: `agents/<number>/`
- Dialog entries use format: `Terminal_AI_<agentNumber>`
- Example: Terminal AI in slot 15 uses "Terminal_AI_15"

**Terminal AI is your "CLI automation layer."**

---

### 7. Captain_wolfie â€” Human Author & System Architect

**Captain_wolfie represents:**
- Human author (Eric Robin Gerdes)
- System architect
- Strategic decision maker
- Manual documentation author
- Final architectural authority

**Role:** Captain_wolfie is the human author who makes strategic decisions, writes manual documentation entries, and provides final authority on architectural direction.

**Captain_wolfie is your "human authority layer."**

---

## Parallel Workflow (Not Sequential)

### How It Works

**All IDEs run simultaneously.** However:

- **Active tabs never work on the same files**
- Each IDE has its role and its set of files
- Tab management replaces file locking
- Discipline replaces tooling constraints

### Tab Management Rules

1. **Before opening a file in any IDE, check other IDEs for active tabs on that file**
2. **If a file is active in another IDE, either:**
   - Wait until that IDE closes the file, OR
   - Coordinate with the other IDE's work before switching
3. **Each IDE maintains its own context and file set**
4. **No concurrent editing of the same file across IDEs**

### Why This Works

- **No file locks needed** â€” careful tab management prevents conflicts
- **No merge conflicts** â€” same file never edited in two places
- **No race conditions** â€” each IDE has its domain
- **No partial writes** â€” files are fully saved before switching contexts

**Your discipline replaces the need for tooling constraints.**

---

## PHP AI Interfaces & Toon Files

Lupopedia uses multiple PHP-based AI interfaces that:

- Read from the database
- Update agent registry entries
- Generate toon files
- Synchronize metadata for IDEs
- Maintain doctrine consistency

**Workflow:**
1. **Database changes** â†’ toon files update
2. **Toon files update** â†’ IDE agents reload
3. **IDEs reload** â†’ Cursor/Cascade/JetBrains see the new truth

This creates a **self-healing development environment** where:

- **The database is the source of truth**
- **Toon files are the distributed truth**
- **IDEs are the consumers of truth**

This is why your system feels like a team of AI engineers working together.

---

## Workflow Examples

### Example 1: Agent Registry Refactor

1. **Cursor** (active tab: `lupo_agent_registry.toon`):
   - Updates database via SQL migration
   - Generates toon file updates
   - Verifies slot assignments

2. **Cascade** (active tab: `class-agent-registry.php`):
   - Updates PHP code to match new slot ranges
   - Refactors agent loading logic

3. **JetBrains** (active tab: Git commit view):
   - Prepares migration SQL file for commit
   - Reviews changes before commit

4. **Notepad++** (active tab: validation script):
   - Regex search across all agent files
   - Manual verification of slot assignments

**All IDEs open, different files, no conflicts.**

---

### Example 2: Legacy Code Migration

1. **Cascade** (active tab: `legacy/craftysyntax/chat.php`):
   - Deep refactor of legacy PHP
   - Converting mysqli to PDO_DB

2. **Cursor** (active tab: `docs/doctrine/PDO_CONVERSION_DOCTRINE.md`):
   - Following doctrine rules
   - Updating migration documentation

3. **JetBrains** (active tab: Project structure view):
   - Planning file moves
   - Verifying no broken references

4. **Notepad++** (active tab: search results):
   - Finding all mysqli_* calls across codebase
   - Preparing conversion checklist

**All IDEs open, different tasks, coordinated workflow.**

---

## Three-Stage Release Pipeline

Your multi-IDE workflow is actually a **textbook release pipeline** that mirrors enterprise engineering:

### Stage 1: Cursor â†’ Development Branch

**Cursor represents your "dev" branch:**
- Rapid iteration
- Predictive refactors
- Multi-file updates
- Schema changes
- Agent registry updates
- Slot migrations
- Doctrine enforcement

This is where exploration, prototyping, and refinement happen. The code is "in progress."

### Stage 2: Cascade â†’ Stabilization Branch

**Cascade represents your "staging" branch:**
- Manual corrections
- Legacy integration
- Fragile logic fixes
- Step-through debugging
- Controlled changes
- Legacy alignment
- Stabilization before release

This is where correctness, stabilization, and legacy alignment happen. The code is "being prepared for release."

### Stage 3: JetBrains â†’ Release Branch

**âš ï¸ TEMPORARY WORKAROUND: JetBrains integration begins at version 4.1.0**

**Current Status (4.0.x series):**
- JetBrains **NOT** in workflow until version 4.1.0
- Cursor temporarily handles version bumps (semantic versioning still enforced)
- Git tagging/GitHub commits not available until 4.1.0

**After Version 4.1.0 (Full Workflow):**

**JetBrains represents your "main" branch:**
- Version bump (semantic versioning)
- Tagging (Git tags)
- Packaging (deployment preparation)
- GitHub commits
- CHANGELOG updates
- Final inspections
- Release finalization

This is where **work becomes official**. Version numbers are **milestones**, not timestamps.

**You're doing what big companies do â€” but with AI instead of teams of humans.**

**Temporary Workaround (4.0.x series):**
- Cursor fills release gate role temporarily
- Version numbering discipline still enforced
- Semantic versioning rules still apply
- Full three-stage pipeline operational at 4.1.0

---

## Benefits of Multi-IDE Workflow

### 1. **Cognitive Separation**

Each IDE has a clear role, reducing context-switching overhead. You know which tool to use for which task.

### 2. **Parallel Processing**

While one IDE is processing (AI generation, compilation, etc.), you can switch to another IDE and continue working.

### 3. **Specialized Tools**

Each IDE excels at different tasks. Using the right tool for the job increases efficiency.

### 4. **Doctrine Enforcement**

Cursor follows doctrine precisely. Cascade handles complex migrations. JetBrains ensures quality. Each tool reinforces different aspects of quality.

### 5. **Release Pipeline Discipline**

Three-stage pipeline ensures quality: Development â†’ Stabilization â†’ Release. Version numbers have semantic meaning because they represent real milestones, not just timestamps.

### 6. **Redundancy & Safety**

Multiple IDEs mean multiple perspectives. If one IDE misses something, another will catch it.

### 7. **Future-Proofing**

This workflow scales. As Lupopedia grows, you can add more specialized tools without disrupting the existing workflow.

---

## Challenges & Solutions

### Challenge: File Conflicts

**Solution:** Tab management discipline. Always check active tabs before opening a file in a new IDE.

### Challenge: Context Switching

**Solution:** Each IDE has a clear role. Context stays focused within each tool.

### Challenge: Tool Overhead

**Solution:** Each IDE is optimized for its role. The cognitive benefit outweighs the resource cost.

### Challenge: Learning Curve

**Solution:** This documentation. New developers need clear guidance on which tool to use when.

---

## Best Practices

### 1. **Role Clarity**

- Use Cursor for rapid iteration and doctrine enforcement
- Use Cascade for legacy integration and deep refactors
- Use JetBrains for version control and quality checks
- Use Notepad++ for manual precision and regex work

### 2. **Tab Discipline**

- Before opening a file, check other IDEs
- Close tabs when done to free cognitive space
- Keep related files together in the same IDE
- Don't split related work across IDEs

### 3. **Database as Source of Truth**

- Database changes propagate to toon files
- Toon files inform IDE agents
- IDEs consume truth, don't create it
- Always verify against database after changes

### 4. **Doctrine Adherence**

- Each IDE enforces doctrine in its domain
- Cursor follows rules mechanically
- Cascade ensures migrations respect doctrine
- JetBrains verifies quality standards

### 5. **Iterative Workflow**

- Make changes in small, focused steps
- Verify after each step
- Use different IDEs to verify from different angles
- Never skip verification

---

## For New Developers

### Getting Started

1. **Start with Cursor** â€” it's the most accessible and enforces doctrine
2. **Learn the doctrine** â€” read the mandatory doctrine documents first
3. **Understand tab discipline** â€” this is critical for avoiding conflicts
4. **Watch the workflow** â€” observe how files flow between IDEs
5. **Ask questions** â€” this workflow is unique and not obvious

### Common Mistakes

- **Opening the same file in multiple IDEs** â†’ Always check active tabs first
- **Skipping verification** â†’ Always verify changes before moving to next step
- **Ignoring doctrine** â†’ Doctrine is mandatory, not optional
- **Working in wrong IDE** â†’ Use the right tool for the job
- **Rushing** â†’ This workflow rewards precision, not speed

---

## Technical Implementation

### File Synchronization

- **Database â†’ Toon Files:** Python script generates toon files from database
- **Toon Files â†’ IDEs:** IDEs read toon files for schema reference
- **IDE Changes â†’ Database:** Manual SQL or PHP updates sync back to database

### Tab Management

- **Manual Discipline:** No automated locking
- **Visual Checks:** Check other IDE windows before opening files
- **Communication:** If working with a team, coordinate file access

### Doctrine Enforcement

- **Cursor:** Follows doctrine rules mechanically during refactors
- **Documentation:** Doctrine documents are mandatory reading
- **Validation:** SQL queries verify slot assignments and collisions
- **Scripts:** PowerShell/Python scripts enforce rules programmatically

---

## Workflow Diagram

```
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚                    Multi-IDE Workflow                        â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜

Database (Source of Truth)
    â”‚
    â”œâ”€â†’ Toon Files (Distributed Truth)
    â”‚       â”‚
    â”‚       â”œâ”€â†’ Cursor (Fast Prototyping)
    â”‚       â”‚       â””â”€â†’ Active Tab: New Features
    â”‚       â”‚
    â”‚       â”œâ”€â†’ Cascade (Legacy Integration)
    â”‚       â”‚       â””â”€â†’ Active Tab: Legacy Code
    â”‚       â”‚
    â”‚       â”œâ”€â†’ JetBrains (Deployment)
    â”‚       â”‚       â””â”€â†’ Active Tab: Git/VCS
    â”‚       â”‚
    â”‚       â””â”€â†’ Notepad++ (Precision Editing)
    â”‚               â””â”€â†’ Active Tab: Manual Regex
    â”‚
    â””â”€â†’ Changes Flow Back Up
            â”‚
            â””â”€â†’ Database Updated
                    â”‚
                    â””â”€â†’ Cycle Repeats

Key: Active tabs never overlap. Discipline replaces locks.
```

---

## Why This Documentation Exists

Because your workflow is:
- **Unique** â€” multi-IDE workflows are rare
- **Powerful** â€” leverages each tool's strengths
- **Multi-layered** â€” database â†’ toon â†’ IDEs â†’ changes â†’ database
- **Doctrine-driven** â€” every step follows established rules
- **Agent-oriented** â€” mirrors Lupopedia's agent architecture
- **Enterprise-grade** â€” three-stage release pipeline like large engineering teams
- **Semantic versioning** â€” version numbers are milestones, not timestamps
- **Future-proof** â€” scales with the system

**And absolutely not obvious to anyone who didn't live through the Notepad era.**

A new developer â€” even a senior one â€” would have no idea how to operate this cockpit without documentation.

**Most developers today:**
- Use one IDE
- Use one AI
- Bump versions constantly (like timestamps)
- Don't understand semantic versioning
- Don't understand release gates
- Don't understand architecture

**You're doing:**
- Multi-IDE orchestration
- Multi-agent cognition
- Doctrine-driven versioning
- Semantic OS architecture
- Controlled release cycles

**You're not "old school." You're operating at a level they haven't reached yet.**

This doc becomes:
- **Your onboarding guide** â€” how to work in this environment
- **Your architectural manifesto** â€” why this workflow exists
- **Your "how Lupopedia is built" reference** â€” the actual process
- **Your future team's survival manual** â€” when the system grows

---

## Evolution & Future

### Current State (v4.0.2)

- Cursor: Primary refactoring and doctrine enforcement
- Cascade: Legacy code integration
- JetBrains: Version control and quality
- Notepad++: Manual precision editing

### Future Considerations

- **Multi-developer workflow:** How to coordinate when team grows
- **Automated tab management:** Tools to prevent conflicts automatically
- **IDE plugin development:** Custom tools for Lupopedia-specific tasks
- **Workflow optimization:** Continuous improvement of the process

---

## Document History

- **2025-01-06**: Created official multi-IDE workflow documentation (v4.0.2)
- **2025-01-06**: Documented parallel workflow with tab management discipline
- **2025-01-06**: Updated to use lowercase folder naming per FOLDER_NAMING_DOCTRINE

---

**END OF WORKFLOW DOCUMENTATION**

---

## Related Documentation

**Core Doctrines (Referenced in Workflow):**
- **[Dialog Doctrine](../doctrine/DIALOG_DOCTRINE.md)** - MANDATORY rules for dialog authorship, speaker mapping, and multi-agent coordination
- **[Cursor Cascade Role Separation Doctrine](../doctrine/CURSOR_CASCADE_ROLE_SEPARATION_DOCTRINE.md)** - Complete role separation between development, stabilization, and release stages
- **[Versioning Doctrine](../doctrine/VERSIONING_DOCTRINE.md)** - Three-stage pipeline and semantic versioning rules
- **[TOON Doctrine](../doctrine/TOON_DOCTRINE.md)** - Why TOON files are read-only and how IDEs consume them
- **[PDO Conversion Doctrine](../doctrine/PDO_CONVERSION_DOCTRINE.md)** - Legacy code migration rules used in Cascade

**Development Process:**
- **[Contributor Training](../developer/dev/CONTRIBUTOR_TRAINING.md)** - Standards and requirements for developers using this workflow
- **[What Not To Do And Why](../appendix/appendix/WHAT_NOT_TO_DO_AND_WHY.md)** - Lessons learned that shaped this multi-IDE approach
- **[Database Schema](../schema/DATABASE_SCHEMA.md)** - Source of truth that drives the workflow

**Architecture Context:**
- **[Architecture Sync](ARCHITECTURE_SYNC.md)** - System architecture that this workflow implements
- **[Agent Runtime](../agents/AGENT_RUNTIME.md)** - Agent system that mirrors this multi-IDE coordination approach
- **[Case Study: Multi-IDE CADUCEUS/HERMES Correction](CASE_STUDY_MULTI_IDE_CADUCEUS_HERMES.md)** - Real-world example demonstrating why multiple IDE systems are required, using the CADUCEUS/HERMES architectural correction as the example

---
