---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: WOLFIE
  target: @everyone
  message: "Mandatory role separation doctrine between Cursor (autonomous refactor engine) and Cascade (manual controlled editor). Cursor handles new features and automated refactors. Cascade handles legacy code and fragile migrations. This separation is NON-NEGOTIABLE."
  mood: "FF0000"
tags:
  categories: ["doctrine", "workflow", "roles"]
  collections: ["core-docs", "standards"]
  channels: ["dev", "internal"]
file:
  title: "Cursor and Cascade Role Separation Doctrine"
  description: "Mandatory rules defining the separation of responsibilities between Cursor (autonomous refactor engine) and Cascade (manual controlled editor)"
  version: "4.0.2"
  status: published
  author: "WOLFIE"
---

# Cursor and Cascade Role Separation Doctrine

**âš ï¸ MANDATORY (NON-NEGOTIABLE)**  
**Version 4.0.2**  
**Effective Date: 2025-01-06**

## Overview

This doctrine establishes **absolute separation** between Cursor (autonomous refactor engine) and Cascade (manual controlled editor). This separation ensures that legacy code is handled safely by human-guided processes before automated refactoring, and that new code benefits from Cursor's speed and doctrine enforcement.

**Critical Principle:** Cursor is the autonomous refactor engine. Cascade is the manual, controlled editor. **These roles must NEVER overlap.**

---

## Cursor: Autonomous Refactor Engine

### Allowed Operations

Cursor **MAY** perform:

1. **Predictive Updates**
   - Multi-file consistency updates
   - Cross-file refactors
   - Pattern-based replacements
   - Doctrine enforcement across files

2. **Multi-File Rewrites**
   - Synchronized updates across multiple files
   - Bulk refactoring operations
   - Dependency chain updates
   - Consistency passes

3. **SQL Generation**
   - Database migration scripts
   - Schema updates
   - Data migrations
   - Index creation/modification

4. **Doctrine Enforcement**
   - Enforcing naming conventions
   - Applying doctrine rules across codebase
   - Validating compliance
   - Correcting violations

5. **Folder Moves**
   - Slot-based folder migrations
   - Directory restructuring
   - Path normalization
   - Organization refactors

6. **New Feature Development**
   - Creating new modules
   - Implementing new functionality
   - Building new components
   - Modern code generation

7. **Agent Registry Updates**
   - Slot assignments
   - Agent registration
   - Registry migrations
   - Slot range expansions

### Cursor's Domain

Cursor operates in:
- âœ… New code (Lupopedia 4.0+)
- âœ… Stable, modernized code (after Cascade handoff)
- âœ… Doctrine enforcement
- âœ… Multi-file consistency
- âœ… Automated refactoring
- âœ… SQL/migration generation
- âœ… Folder/directory operations

---

## Cursor: FORBIDDEN Operations

Cursor **MUST NOT** perform:

1. **Legacy Code Edits**
   - âŒ Modifying legacy Crafty Syntax files
   - âŒ Rewriting legacy PHP code
   - âŒ Touching `legacy/craftysyntax/` directory
   - âŒ Autonomous edits to pre-modernized code

2. **Fragile Edits**
   - âŒ Modifying code with known fragility
   - âŒ Changing logic without human review
   - âŒ Autonomous fixes to complex legacy behavior
   - âŒ Predictive changes to untested code paths

3. **Legacy Integration**
   - âŒ Crafty Syntax integration work
   - âŒ Legacy system migration
   - âŒ Pre-modernization refactors
   - âŒ Stabilization phase work

**Rationale:** Legacy code requires human judgment, careful testing, and step-by-step validation. Autonomous refactoring of legacy code risks breaking fragile logic, losing important behavior, or introducing subtle bugs.

---

## Cascade: Manual Controlled Editor

### Allowed Operations

Cascade **MUST** perform:

1. **Legacy Code Integration**
   - Manual, step-by-step edits to legacy Crafty Syntax
   - Crafty Syntax â†’ Lupopedia migration
   - Legacy behavior preservation
   - Fragile logic corrections

2. **Fragile Migrations**
   - Careful, human-guided changes
   - Step-by-step validation
   - Incremental testing
   - Risk-averse refactoring

3. **Manual Debugging**
   - Surgical code fixes
   - Precision edits
   - Context-aware changes
   - Human judgment required

4. **Structural Cleanup**
   - Preparing files for automated refactoring
   - Stabilizing code before handoff
   - Removing obvious fragility
   - Making code safe for Cursor

5. **Legacy Behavior Preservation**
   - Ensuring compatibility
   - Maintaining existing functionality
   - Testing legacy integration
   - Validating migration correctness

### Cascade's Domain

Cascade operates in:
- âœ… Legacy Crafty Syntax code (`legacy/craftysyntax/`)
- âœ… Fragile or complex code
- âœ… Pre-modernization refactors
- âœ… Manual, step-by-step migrations
- âœ… Human-guided changes
- âœ… Stabilization phase work

---

## Cascade: FORBIDDEN Operations

Cascade **MUST NOT** perform:

1. **Autonomous Refactoring**
   - âŒ Predictive multi-file updates
   - âŒ Automated consistency passes
   - âŒ Bulk refactoring operations
   - âŒ Autonomous pattern replacements

2. **Doctrine Enforcement (Bulk)**
   - âŒ Automated doctrine application across codebase
   - âŒ Multi-file naming normalization
   - âŒ Cross-file consistency updates
   - âŒ Automated compliance fixes

**Rationale:** Cascade focuses on precision and control. Bulk operations are Cursor's strength. Cascade handles the delicate work, then hands off to Cursor for consistency and doctrine enforcement.

---

## Legacy â†’ Cursor Handoff Process

### Phase 1: Cascade Stabilization

**Cascade performs:**
1. **Manual, Step-by-Step Edits**
   - Human-guided changes
   - Careful validation
   - Incremental testing
   - Context-aware modifications

2. **Fragile Logic Corrections**
   - Fixing known issues
   - Removing obvious fragility
   - Stabilizing behavior
   - Ensuring correctness

3. **Legacy Behavior Preservation**
   - Maintaining functionality
   - Ensuring compatibility
   - Testing integration
   - Validating migration

4. **Structural Cleanup**
   - Organizing code structure
   - Removing dead code
   - Normalizing basic patterns
   - Preparing for automation

5. **Safety Preparation**
   - Making code safe for automated refactoring
   - Removing traps and gotchas
   - Documenting special cases
   - Creating test coverage

### Phase 2: Explicit Handoff

**Cascade signals handoff by:**
- âœ… Code is stable and tested
- âœ… Fragile logic is corrected
- âœ… Legacy behavior is preserved
- âœ… Structure is clean
- âœ… Code is safe for automation
- âœ… Explicit instruction to Cursor: "This file is ready for automated refactoring"

### Phase 3: Cursor Final Pass

**After explicit handoff, Cursor performs:**
1. **Final Consistency Passes**
   - Multi-file consistency updates
   - Cross-file refactors
   - Pattern normalization
   - Dependency updates

2. **Doctrine Enforcement**
   - Applying naming conventions
   - Enforcing doctrine rules
   - Validating compliance
   - Correcting violations

3. **Modernization**
   - Modern syntax updates
   - Best practice application
   - Code quality improvements
   - Removal of duplication

4. **Automated Refactoring**
   - Multi-file rewrites
   - Folder renames
   - SQL updates
   - Naming normalization

5. **Removal of Duplication**
   - Extracting common patterns
   - Consolidating redundant code
   - Creating reusable components
   - DRY principle application

---

## Mandatory Rules

### Rule 1: Cursor Must NOT Modify Legacy Files Until Handoff

**Cursor MUST:**
- âœ… Wait for explicit instruction before touching any legacy file
- âœ… Assume that Cascade will hand off files only when safe
- âœ… Never autonomously modify `legacy/craftysyntax/` directory
- âœ… Never perform predictive refactors on legacy code

**Cursor MUST NOT:**
- âŒ Touch legacy Crafty Syntax code without explicit handoff
- âŒ Modify files in `legacy/craftysyntax/` autonomously
- âŒ Perform predictive updates on legacy code
- âŒ Rewrite legacy PHP without explicit instruction

### Rule 2: Cascade Must Complete Stabilization Before Handoff

**Cascade MUST:**
- âœ… Complete all manual edits before handoff
- âœ… Ensure code is stable and tested
- âœ… Remove fragile logic or document it clearly
- âœ… Prepare code for safe automated refactoring
- âœ… Explicitly signal when files are ready

**Cascade MUST NOT:**
- âŒ Hand off fragile or untested code
- âŒ Skip manual validation steps
- âŒ Assume Cursor will fix issues
- âŒ Hand off without explicit communication

### Rule 3: Clear Domain Separation

**Cursor Domain:**
- New Lupopedia code (4.0+)
- Modernized code (after handoff)
- Doctrine enforcement
- Automated refactoring
- Multi-file consistency
- SQL generation
- Folder operations

**Cascade Domain:**
- Legacy Crafty Syntax code
- Fragile or complex code
- Manual migrations
- Step-by-step edits
- Human-guided changes
- Stabilization work
- Pre-handoff preparation

**These domains MUST NOT overlap.**

### Rule 4: Explicit Communication Required

**Before Cursor touches legacy files:**
1. âœ… Cascade must complete stabilization
2. âœ… Cascade must explicitly signal handoff
3. âœ… Cursor must receive explicit instruction
4. âœ… Handoff must be documented/communicated clearly

**No autonomous Cursor action on legacy code without explicit handoff.**

---

## Workflow Examples

### Example 1: Legacy File Migration

**Phase 1 - Cascade (Manual):**
```
1. Cascade manually edits `legacy/craftysyntax/chat.php`
2. Cascade fixes fragile logic step-by-step
3. Cascade tests and validates changes
4. Cascade stabilizes code structure
5. Cascade: "File is stable and ready for Cursor"
```

**Phase 2 - Handoff:**
```
6. Explicit instruction: "Cursor, please modernize chat.php"
7. Handoff is clear and documented
```

**Phase 3 - Cursor (Automated):**
```
8. Cursor applies doctrine enforcement
9. Cursor performs consistency passes
10. Cursor modernizes syntax
11. Cursor removes duplication
12. Cursor updates related files
```

**Result:** Safe migration with human judgment first, automation second.

---

### Example 2: New Feature Development

**Cursor (Autonomous):**
```
1. Cursor creates new module
2. Cursor implements new functionality
3. Cursor applies doctrine rules
4. Cursor generates SQL migrations
5. Cursor updates related files
6. Cursor moves folders as needed
```

**Cascade:** Not involved (new code, no legacy concerns)

**Result:** Fast development with doctrine enforcement.

---

### Example 3: Multi-File Refactor

**Cursor (Autonomous):**
```
1. Cursor identifies pattern across files
2. Cursor performs predictive updates
3. Cursor ensures consistency
4. Cursor validates doctrine compliance
5. Cursor updates all related files
```

**Cascade:** Not involved (consistency work, not legacy)

**Result:** Efficient bulk refactoring with consistency.

---

## Why This Separation Exists

### 1. **Risk Management**

- **Legacy code is fragile:** Autonomous refactoring risks breaking existing behavior
- **Human judgment required:** Legacy logic often requires understanding context and intent
- **Incremental validation:** Step-by-step changes allow testing at each stage
- **Safety first:** Cascade stabilizes before Cursor automates

### 2. **Efficiency**

- **Right tool for the job:** Cursor excels at bulk operations, Cascade at precision
- **No wasted effort:** Avoid redoing fragile work
- **Clear boundaries:** No confusion about who does what
- **Faster overall:** Handoff happens when code is safe

### 3. **Quality**

- **Human validation first:** Cascade ensures correctness
- **Automation second:** Cursor enforces consistency
- **Best of both:** Human judgment + automated consistency
- **Reduced bugs:** Stabilization before automation prevents issues

### 4. **Doctrine Compliance**

- **Clear rules:** Everyone knows their role
- **Predictable workflow:** Handoff process is well-defined
- **Documented process:** Explicit communication required
- **Enforceable:** Rules are clear and verifiable

---

## For AI Assistants (Cursor)

### Before Modifying Any File

**Checklist:**
1. âœ… Is this file in `legacy/craftysyntax/` directory?
   - **If YES:** âŒ DO NOT MODIFY without explicit handoff instruction
   - **If NO:** âœ… Proceed if other rules allow

2. âœ… Is this file marked as "fragile" or "legacy"?
   - **If YES:** âŒ DO NOT MODIFY without explicit handoff instruction
   - **If NO:** âœ… Proceed if other rules allow

3. âœ… Have I received explicit instruction to modify this file?
   - **If NO:** âŒ DO NOT MODIFY legacy files
   - **If YES:** âœ… Proceed with automated refactoring

4. âœ… Is this new code or modernized code (after Cascade handoff)?
   - **If YES:** âœ… This is Cursor's domain, proceed
   - **If NO:** âŒ Wait for handoff

### When In Doubt

**If unsure whether a file is legacy:**
- âŒ **DO NOT** autonomously modify
- âœ… **DO** ask for explicit instruction
- âœ… **DO** assume it's legacy until confirmed otherwise
- âœ… **DO** wait for Cascade handoff

### Allowed Autonomous Actions

**Cursor may autonomously:**
- âœ… Create new files (Lupopedia 4.0+ code)
- âœ… Modify modernized files (after handoff)
- âœ… Apply doctrine enforcement (on non-legacy code)
- âœ… Perform multi-file consistency updates (on stable code)
- âœ… Generate SQL migrations
- âœ… Move folders/directories
- âœ… Update agent registry
- âœ… Perform slot migrations

### Forbidden Autonomous Actions

**Cursor must NOT autonomously:**
- âŒ Modify files in `legacy/craftysyntax/`
- âŒ Rewrite legacy PHP code
- âŒ Touch fragile or complex legacy logic
- âŒ Perform predictive updates on legacy code
- âŒ Assume legacy code is ready for automation

---

## For Human Operators (Cascade)

### Before Handing Off to Cursor

**Checklist:**
1. âœ… Is the code stable and tested?
2. âœ… Have fragile logic issues been corrected?
3. âœ… Is legacy behavior preserved and validated?
4. âœ… Is the structure clean and organized?
5. âœ… Is the code safe for automated refactoring?
6. âœ… Have special cases been documented?
7. âœ… Is test coverage adequate?

**If all are YES, then:**
- âœ… Explicitly communicate handoff to Cursor
- âœ… Document what has been done
- âœ… Specify what Cursor should do
- âœ… Confirm file is ready for automation

**If any are NO:**
- âŒ Do NOT hand off yet
- âœ… Complete stabilization work first
- âœ… Fix fragile logic
- âœ… Validate behavior
- âœ… Prepare for safe handoff

---

## Enforcement

### Validation

**To verify compliance:**
1. Check if Cursor modified any files in `legacy/craftysyntax/` without explicit instruction
2. Verify Cascade completed stabilization before handoff
3. Confirm explicit communication occurred before Cursor touched legacy files
4. Validate that domains are clearly separated

### Violations

**If Cursor autonomously modifies legacy code:**
- âŒ **VIOLATION:** Cursor crossed into Cascade's domain
- **Action Required:** Revert changes, follow handoff process

**If Cascade hands off fragile code:**
- âŒ **VIOLATION:** Cascade skipped stabilization
- **Action Required:** Complete stabilization, then hand off

**If handoff is not explicit:**
- âŒ **VIOLATION:** No clear communication
- **Action Required:** Establish explicit handoff process

---

## Document History

- **2025-01-06**: Created Cursor and Cascade role separation doctrine (v4.0.2)
- **2025-01-06**: Documented legacy â†’ Cursor handoff process
- **2025-01-06**: Established mandatory domain separation rules

---

## **Related Documentation**

- **[VERSIONING_DOCTRINE.md](VERSIONING_DOCTRINE.md)** â€” Three-stage pipeline: Cursor (development) â†’ Cascade (stabilization) â†’ JetBrains (release)
- **[CURSOR_REFACTOR_DOCTRINE.md](CURSOR_REFACTOR_DOCTRINE.md)** â€” Detailed rules for Cursor's autonomous refactoring operations
- **[LEGACY_REFACTOR_PLAN.md](../developer/modules/LEGACY_REFACTOR_PLAN.md)** â€” Comprehensive plan for legacy Crafty Syntax refactoring

---

**END OF DOCTRINE DOCUMENT**
