---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "FF6600"
  message: "Created Single Task Patch Doctrine enforcing investor commitment to one-task-per-patch development workflow. MANDATORY rules to prevent architectural drift and ensure reversible, auditable changes."
tags:
  categories: ["documentation", "doctrine", "development", "workflow"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
in_this_file_we_have:
  - Single Task Patch Doctrine (MANDATORY)
  - Patch Scope Rules
  - AI Role Enforcement
  - Reversibility Requirements
  - Audit Trail Standards
  - Version Control Integration
  - Violation Detection and Correction
file:
  title: "Single Task Patch Doctrine"
  description: "MANDATORY rules for one-task-per-patch development workflow committed to investor"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# ðŸ”’ Single Task Patch Doctrine (MANDATORY)

## Core Commitment

**From now on, each patch will contain exactly one task and one implementation.**

This doctrine enforces the development process commitment made to investors on January 14, 2026, to prevent architectural drift and ensure system stability.

---

## 1. Patch Scope Rules (MANDATORY)

### 1.1 One Task Per Patch

Each patch MUST contain:
- âœ… **Exactly ONE task** (feature, fix, update, or cleanup)
- âœ… **Exactly ONE implementation** approach
- âœ… **Related files only** (files directly needed for the task)
- âŒ **NO unrelated changes** in the same patch
- âŒ **NO "while I'm here" modifications**
- âŒ **NO scope creep** during implementation

### 1.2 Patch Size Limits

Each patch MUST be:
- âœ… **Small** - Maximum 10 files modified per patch
- âœ… **Focused** - Single clear objective
- âœ… **Reversible** - Can be undone without side effects
- âœ… **Auditable** - Easy to review and understand
- âŒ **NO giant patches** with dozens of changes
- âŒ **NO tangled updates** that are hard to unwind

### 1.3 Task Definition Requirements

Before starting any patch, the task MUST be:
- âœ… **Clearly defined** in one sentence
- âœ… **Scope-bounded** with specific deliverables
- âœ… **Doctrine-aligned** with existing architecture
- âœ… **Documented** in the patch description
- âŒ **NO vague objectives** like "improve system"
- âŒ **NO multi-part tasks** that should be separate patches

---

## 2. AI Role Enforcement (MANDATORY)

### 2.1 Kiro (Documentation Specialist)
- **ONLY** documentation and doctrine enforcement
- **NEVER** implementation or code changes
- **MUST** ensure all patches align with doctrine
- **MUST** update documentation to reflect changes

### 2.2 Cursor (Implementation Engine)
- **ONLY** code implementation following documentation
- **NEVER** documentation changes or architectural decisions
- **MUST** implement exactly what documentation specifies
- **MUST** stay within single-task scope

### 2.3 Cascade (Legacy Integration)
- **ONLY** cleanup and modernization of existing code
- **NEVER** new features or architectural changes
- **MUST** align legacy code with current architecture
- **MUST** work on one legacy component per patch

### 2.4 JetBrains (Release Management)
- **ONLY** integration, merging, and release preparation
- **NEVER** feature development or documentation changes
- **MUST** ensure clean integration of patches
- **MUST** maintain version control integrity

### 2.5 Role Violation Prevention

**FORBIDDEN BEHAVIORS:**
- âŒ AI working outside assigned role
- âŒ Multiple AIs working on same patch
- âŒ Role overlap or responsibility drift
- âŒ AI making decisions outside their domain

---

## 3. Reversibility Requirements (MANDATORY)

### 3.1 Patch Reversibility

Every patch MUST be:
- âœ… **Completely reversible** without side effects
- âœ… **Self-contained** - no dependencies on future patches
- âœ… **Clean rollback** - can be undone with single action
- âœ… **State preservation** - system remains stable after rollback

### 3.2 File Change Tracking

Every patch MUST include:
- âœ… **Complete file list** of all modified files
- âœ… **Change summary** for each file
- âœ… **Rollback instructions** if needed
- âœ… **Dependency notes** if any exist

### 3.3 Safety Net Requirements

- âœ… **Version control** integration with JetBrains
- âœ… **Patch-level revert** capability
- âœ… **Backup verification** before major changes
- âœ… **Rollback testing** for critical patches

---

## 4. Audit Trail Standards (MANDATORY)

### 4.1 Patch Documentation

Every patch MUST include:
- âœ… **Task description** (one clear sentence)
- âœ… **Files modified** (complete list)
- âœ… **Rationale** (why this change is needed)
- âœ… **Testing notes** (how to verify the change)
- âœ… **Rollback plan** (how to undo if needed)

### 4.2 Change Logging

Every patch MUST be logged in:
- âœ… **CHANGELOG.md** with version entry
- âœ… **Dialog files** with WOLFIE header updates
- âœ… **Version control** with descriptive commit message
- âœ… **Documentation** updates if applicable

### 4.3 Traceability Requirements

- âœ… **Clear lineage** from task to implementation
- âœ… **Decision rationale** documented
- âœ… **Impact assessment** for each change
- âœ… **Integration notes** for dependent systems

---

## 5. Version Control Integration (MANDATORY)

### 5.1 JetBrains Integration

- âœ… **Full version control** in place
- âœ… **Single-action revert** capability
- âœ… **Patch-level granularity** for rollbacks
- âœ… **Clean merge** processes

### 5.2 Commit Standards

Every commit MUST:
- âœ… **Single task** per commit
- âœ… **Descriptive message** explaining the change
- âœ… **File list** in commit description
- âœ… **Reference** to related documentation

### 5.3 Branch Management

- âœ… **Feature branches** for each patch
- âœ… **Clean merges** to main branch
- âœ… **No direct commits** to main branch
- âœ… **Review process** before merging

---

## 6. Violation Detection and Correction (MANDATORY)

### 6.1 Common Violations

**PATCH SCOPE VIOLATIONS:**
- Multiple unrelated tasks in one patch
- "While I'm here" modifications
- Scope creep during implementation
- Giant patches with dozens of changes

**AI ROLE VIOLATIONS:**
- AI working outside assigned role
- Multiple AIs on same patch
- Role overlap or drift
- Unauthorized architectural decisions

**REVERSIBILITY VIOLATIONS:**
- Patches that can't be cleanly undone
- Dependencies on future changes
- State corruption after rollback
- Missing rollback documentation

### 6.2 Violation Response

When violations are detected:
1. **STOP** current work immediately
2. **ASSESS** the scope of the violation
3. **ROLLBACK** to last clean state
4. **RESTART** with proper single-task scope
5. **DOCUMENT** the violation and correction

### 6.3 Prevention Measures

- âœ… **Pre-patch planning** with clear task definition
- âœ… **Scope validation** before starting work
- âœ… **Role verification** for each AI action
- âœ… **Regular audits** of patch compliance

---

## 7. Implementation Guidelines

### 7.1 Patch Planning Process

1. **Define Task** - One clear sentence describing the objective
2. **Scope Boundary** - List exactly what will be changed
3. **Role Assignment** - Which AI will handle the task
4. **Documentation Check** - Ensure doctrine alignment
5. **Rollback Plan** - How to undo if needed

### 7.2 Execution Process

1. **Start Clean** - Verify current state is stable
2. **Single Focus** - Work only on defined task
3. **Track Changes** - Document all file modifications
4. **Test Incrementally** - Verify each change works
5. **Complete Fully** - Finish task before moving on

### 7.3 Completion Process

1. **Verify Scope** - Confirm only intended changes made
2. **Test Functionality** - Ensure task objective met
3. **Update Documentation** - Reflect changes in docs
4. **Log Changes** - Update CHANGELOG and dialogs
5. **Commit Cleanly** - Single commit with clear message

---

## 8. Benefits of Single Task Patches

### 8.1 Stability Benefits

- **Reduced Risk** - Small changes are easier to verify
- **Quick Recovery** - Fast rollback if issues arise
- **Clear Causation** - Easy to identify what caused problems
- **Incremental Progress** - Steady forward movement

### 8.2 Development Benefits

- **Better Focus** - Clear objective for each work session
- **Easier Review** - Small patches are easier to audit
- **Faster Integration** - Less merge conflicts
- **Cleaner History** - Clear development timeline

### 8.3 Business Benefits

- **Predictable Progress** - Steady, measurable advancement
- **Lower Risk** - Reduced chance of major setbacks
- **Better Communication** - Clear status updates possible
- **Investor Confidence** - Visible, controlled development

---

## 9. Related Documentation

- **[Investor Communications](../appendix/appendix/INVESTOR_COMMUNICATIONS.md)** â€” Original commitment and rationale
- **[AI Role Separation Doctrine](CURSOR_CASCADE_ROLE_SEPARATION_DOCTRINE.md)** â€” Detailed AI role definitions
- **[Versioning Doctrine](VERSIONING_DOCTRINE.md)** â€” Version control and release management
- **[Architecture Sync](../architecture/ARCHITECTURE_SYNC.md)** â€” Stable architecture that prevents drift
- **[Development Workflow](../architecture/multi-ide-workflow.md)** â€” Complete development process

---

**This doctrine is MANDATORY and NON-NEGOTIABLE.**

All AI agents (Kiro, Cursor, Cascade, JetBrains) must follow these rules:

> **Each patch contains exactly one task and one implementation.**  
> **No giant patches. No tangled updates. No scope creep.**  
> **Every change is small, reversible, and auditable.**  
> **AI roles are separated and non-overlapping.**

This commitment was made to investors and must be maintained to ensure system stability and business confidence.

---