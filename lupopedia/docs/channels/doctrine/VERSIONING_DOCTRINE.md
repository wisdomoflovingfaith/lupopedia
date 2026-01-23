---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
channel_key: system/kernel
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  mood_RGB: "0066FF"
  message: "Created VERSIONING_DOCTRINE.md as Phase 2 core document. Defines comprehensive versioning doctrine including version structure, increment rules, reset governance, and agent responsibilities for version discipline."
tags:
  categories: ["documentation", "doctrine", "versioning", "governance"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Versioning Doctrine (MANDATORY)
  - Purpose and Scope
  - Version Number Structure and Components
  - Versioning Rules and Increment Triggers
  - Doctrine Interaction with Versioning
  - Reset Governance and Control
  - Agent Responsibilities for Version Discipline
  - Versioning Examples and Anti-Patterns
  - Cross-References to Related Documentation
file:
  title: "Versioning Doctrine"
  description: "MANDATORY rules for version management, system evolution governance, and version discipline enforcement"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🔢 Versioning Doctrine (MANDATORY)

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** MANDATORY (NON-NEGOTIABLE)  
**Effective Date:** 2026-01-14

## Overview

This document defines the Versioning Doctrine for Lupopedia Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE). Versioning doctrine governs system evolution, prevents uncontrolled changes, and ensures predictable version progression through strict semantic versioning discipline.

**Critical Principle:** Every system change must be reflected in appropriate version increments with complete documentation and traceability through the dialog system.

---

## 1. Purpose and Scope

### 1.1 Why Versioning Doctrine Exists
Versioning doctrine serves multiple critical functions:
- **System Evolution Governance** - Controls how the system changes over time
- **Change Impact Communication** - Clearly communicates the scope and impact of changes
- **Compatibility Management** - Ensures backward compatibility and migration paths
- **Release Coordination** - Enables coordinated releases across all system components
- **Quality Assurance** - Prevents uncontrolled or undocumented system changes
- **Rollback Support** - Enables precise rollback to specific system states

### 1.2 How It Governs System Evolution
Versioning doctrine governs evolution through:
- **Semantic Version Structure** - Clear meaning for each version component
- **Increment Rules** - Specific triggers for version changes
- **Change Classification** - Categorization of changes by impact and scope
- **Documentation Requirements** - Complete documentation for all version changes
- **Agent Coordination** - Coordinated version updates across all agents
- **Validation Gates** - Quality checks before version increments

### 1.3 How It Prevents Drift and Uncontrolled Resets
Drift and reset prevention through:
- **Controlled Increment Process** - Only authorized version changes allowed
- **Documentation Requirements** - All changes must be fully documented
- **Reset Governance** - Strict rules for when resets are allowed
- **Agent Discipline** - Agents must follow version discipline exactly
- **Validation Requirements** - Version changes must pass validation
- **Audit Trail** - Complete history of all version changes

### 1.4 System-Wide Version Consistency
Versioning doctrine ensures:
- **Unified Version Numbers** - All system components use same version
- **Synchronized Updates** - All components updated together
- **Consistent Metadata** - Version information consistent across all files
- **Cross-Reference Integrity** - Version references maintained accurately
- **Documentation Alignment** - Documentation version matches system version

---

## 2. Version Number Structure

### 2.1 Major.Minor.Patch Format
**Lupopedia uses semantic versioning: `MAJOR.MINOR.PATCH`**

**Current Version Structure:**
- **Format:** `X.Y.Z` (e.g., 4.0.14)
- **Components:** Three numeric components separated by dots
- **Range:** Each component can be 0-999 (practical limit)
- **Ordering:** Lexicographic ordering with numeric comparison

**Version Examples:**
- `4.0.14` - Major 4, Minor 0, Patch 14
- `4.1.0` - Major 4, Minor 1, Patch 0
- `5.0.0` - Major 5, Minor 0, Patch 0

### 2.2 Major Version Component
**Major version increments for:**
- **Breaking Changes** - Changes that break backward compatibility
- **Architectural Overhauls** - Fundamental system architecture changes
- **API Breaking Changes** - Changes that break existing API contracts
- **Database Schema Breaking Changes** - Schema changes requiring migration
- **Security Model Changes** - Changes to fundamental security architecture
- **Doctrine Breaking Changes** - Changes that invalidate existing doctrine

**Major Version Characteristics:**
- **Backward Compatibility** - Not guaranteed across major versions
- **Migration Required** - Users may need to migrate data or configurations
- **Documentation Overhaul** - Major documentation updates required
- **Testing Requirements** - Comprehensive testing across all system components
- **Release Coordination** - Requires coordination across all development teams

### 2.3 Minor Version Component
**Minor version increments for:**
- **New Features** - Addition of new functionality without breaking changes
- **API Additions** - New API endpoints or methods (backward compatible)
- **Database Schema Additions** - New tables or columns (backward compatible)
- **Documentation Enhancements** - Significant documentation improvements
- **Performance Improvements** - Notable performance enhancements
- **Security Enhancements** - Security improvements without breaking changes

**Minor Version Characteristics:**
- **Backward Compatibility** - Guaranteed within same major version
- **Optional Migration** - New features may require optional configuration
- **Documentation Updates** - Documentation updated to reflect new features
- **Testing Requirements** - Testing focused on new functionality
- **Feature Coordination** - Coordination required for feature integration

### 2.4 Patch Version Component
**Patch version increments for:**
- **Bug Fixes** - Fixes to existing functionality without new features
- **Documentation Fixes** - Corrections to existing documentation
- **Security Patches** - Security fixes without architectural changes
- **Performance Fixes** - Performance improvements without new features
- **Compatibility Fixes** - Fixes to maintain compatibility
- **Metadata Updates** - Updates to metadata, headers, or cross-references

**Patch Version Characteristics:**
- **Backward Compatibility** - Guaranteed within same minor version
- **No Migration Required** - Changes should not require user action
- **Minimal Documentation** - Documentation updates focused on fixes
- **Focused Testing** - Testing focused on specific fixes
- **Rapid Release** - Can be released quickly for critical fixes

---

## 3. Versioning Rules

### 3.1 What Triggers a Major Version
**Major version increment is REQUIRED for:**

**Breaking Changes:**
- Changes that break existing API contracts
- Database schema changes requiring data migration
- Configuration format changes requiring manual updates
- File format changes that break existing tools
- Security model changes affecting authentication or authorization

**Architectural Changes:**
- Fundamental changes to system architecture
- Changes to core doctrine that invalidate existing implementations
- Directory structure changes that break existing references
- Agent runtime changes that break existing agent behavior
- Cross-reference system changes that break existing links

**Compatibility Breaks:**
- Changes that require users to modify their configurations
- Changes that break existing integrations or extensions
- Changes that require data migration or conversion
- Changes that break existing workflows or procedures

### 3.2 What Triggers a Minor Version
**Minor version increment is REQUIRED for:**

**New Features:**
- Addition of new functionality that doesn't break existing features
- New API endpoints or methods (backward compatible)
- New configuration options (with sensible defaults)
- New documentation sections or major enhancements
- New agent capabilities or runtime features

**Enhancements:**
- Significant performance improvements
- Security enhancements that don't break compatibility
- User interface improvements or new components
- Database schema additions (new tables or columns)
- New cross-reference capabilities or metadata features

**System Improvements:**
- New validation or quality assurance features
- Enhanced error handling or recovery mechanisms
- Improved logging or monitoring capabilities
- New development tools or utilities
- Enhanced documentation or help systems

### 3.3 What Triggers a Patch Version
**Patch version increment is REQUIRED for:**

**Bug Fixes:**
- Fixes to existing functionality that restore intended behavior
- Corrections to documentation errors or inconsistencies
- Fixes to cross-references or metadata inconsistencies
- Performance fixes that don't add new functionality
- Security patches that don't change system architecture

**Maintenance Updates:**
- Updates to WOLFIE headers for consistency
- Dialog system maintenance and cleanup
- Cross-reference network maintenance
- Metadata consistency improvements
- Documentation formatting or style improvements

**Compatibility Maintenance:**
- Fixes to maintain compatibility with existing systems
- Updates to maintain integration with external systems
- Corrections to maintain backward compatibility
- Fixes to prevent regression in existing functionality

### 3.4 Forbidden Version Jumps
**The following version changes are FORBIDDEN:**

**Skipping Version Numbers:**
- ❌ `4.0.14` → `4.0.16` (skipping 4.0.15)
- ❌ `4.0.14` → `4.2.0` (skipping 4.1.x)
- ❌ `4.0.14` → `6.0.0` (skipping 5.x.x)

**Backward Version Movement:**
- ❌ `4.0.14` → `4.0.13` (moving backward)
- ❌ `4.1.0` → `4.0.15` (moving backward)
- ❌ `5.0.0` → `4.9.9` (moving backward)

**Invalid Version Formats:**
- ❌ `4.0.14-beta` (pre-release suffixes not used)
- ❌ `4.0.14.1` (four-component versions not allowed)
- ❌ `v4.0.14` (prefix not part of version number)

### 3.5 Rollback Rules
**Version rollback is governed by strict rules:**

**Rollback Authorization:**
- Rollbacks require explicit human authorization
- Rollbacks must be documented with complete justification
- Rollbacks must include impact analysis and mitigation plan
- Rollbacks must be coordinated across all system components

**Rollback Process:**
1. **Document Rollback Reason** - Complete explanation of why rollback is needed
2. **Analyze Impact** - Identify all systems and users affected by rollback
3. **Plan Mitigation** - Develop plan to address issues caused by rollback
4. **Execute Rollback** - Restore system to previous version state
5. **Validate Rollback** - Ensure system functions correctly after rollback
6. **Document Results** - Record rollback results and lessons learned

**Post-Rollback Version Management:**
- Version number does not change during rollback
- New version increment required for any changes after rollback
- Rollback must be documented in changelog and dialog system
- Future version increments must address issues that caused rollback

---

## 4. Doctrine Interaction

### 4.1 How Doctrine Changes Affect Versioning
**Doctrine changes have specific version impact:**

**Major Doctrine Changes (Major Version):**
- Changes that break existing agent behavior
- Changes that invalidate existing implementations
- Changes that require system-wide updates
- Changes that break backward compatibility

**Minor Doctrine Changes (Minor Version):**
- Addition of new doctrine requirements
- Enhancement of existing doctrine with new capabilities
- Addition of new agent lanes or responsibilities
- New validation or quality requirements

**Patch Doctrine Changes (Patch Version):**
- Clarifications to existing doctrine
- Corrections to doctrine inconsistencies
- Updates to examples or documentation
- Metadata or formatting improvements

### 4.2 How Metadata Changes Affect Versioning
**Metadata changes have specific version impact:**

**Major Metadata Changes (Major Version):**
- Changes to WOLFIE header format that break compatibility
- Changes to atom resolution system that break existing references
- Changes to cross-reference system that break existing links
- Changes to dialog system that break existing threads

**Minor Metadata Changes (Minor Version):**
- Addition of new WOLFIE header fields
- Addition of new atom types or categories
- Enhancement of cross-reference capabilities
- New dialog system features or capabilities

**Patch Metadata Changes (Patch Version):**
- Updates to existing WOLFIE headers for consistency
- Corrections to atom references or resolutions
- Fixes to cross-reference links or formatting
- Dialog system maintenance and cleanup

### 4.3 How Directory Structure Changes Affect Versioning
**Directory structure changes have specific version impact:**

**Major Directory Changes (Major Version):**
- Changes that break existing file references
- Changes that require system-wide file moves
- Changes that break security boundaries
- Changes that break agent operational boundaries

**Minor Directory Changes (Minor Version):**
- Addition of new directories for new functionality
- Enhancement of directory organization for better structure
- Addition of new security boundaries or access controls
- New directory-based features or capabilities

**Patch Directory Changes (Patch Version):**
- Corrections to file placement within existing structure
- Cleanup of directory organization without breaking changes
- Updates to directory documentation or metadata
- Minor adjustments to improve organization

### 4.4 How Agent Runtime Changes Affect Versioning
**Agent runtime changes have specific version impact:**

**Major Agent Runtime Changes (Major Version):**
- Changes that break existing agent behavior
- Changes to agent lane boundaries that require reassignment
- Changes to agent coordination protocols that break compatibility
- Changes to agent-system interfaces that break integration

**Minor Agent Runtime Changes (Minor Version):**
- Addition of new agent capabilities or features
- Enhancement of agent coordination mechanisms
- Addition of new agent lanes or specializations
- New agent validation or quality features

**Patch Agent Runtime Changes (Patch Version):**
- Fixes to agent behavior or coordination issues
- Corrections to agent documentation or specifications
- Updates to agent examples or guidance
- Minor improvements to agent efficiency or reliability

---

## 5. Reset Governance

### 5.1 When Resets Are Allowed
**System resets are allowed ONLY in these circumstances:**

**Critical System Failure:**
- System corruption that cannot be repaired through normal means
- Security breach that requires complete system restoration
- Data corruption that affects system integrity
- Infrastructure failure that requires complete rebuild

**Development Reset (Pre-Production Only):**
- Major architectural changes during development phase
- Fundamental design changes that require clean slate
- Migration to completely different technology stack
- Consolidation of multiple development branches

**Authorized Architectural Overhaul:**
- Planned major version changes that require reset
- Migration to new semantic versioning scheme
- Consolidation of multiple systems into unified architecture
- Transition to new development methodology or workflow

### 5.2 When Resets Are Forbidden
**System resets are FORBIDDEN in these circumstances:**

**Production Systems:**
- Any reset of production system without extreme justification
- Resets to avoid fixing complex problems
- Resets to shortcut proper migration procedures
- Resets to avoid documentation or compliance requirements

**Minor Issues:**
- Bug fixes that can be addressed through normal patch process
- Documentation inconsistencies that can be corrected
- Metadata issues that can be resolved through updates
- Performance issues that can be optimized

**Convenience Resets:**
- Resets to avoid proper version increment procedures
- Resets to avoid proper documentation requirements
- Resets to avoid proper testing or validation
- Resets to avoid proper coordination with other agents

### 5.3 How Resets Must Be Documented
**All authorized resets require complete documentation:**

**Pre-Reset Documentation:**
- Complete justification for why reset is necessary
- Analysis of alternatives and why they are insufficient
- Impact analysis on all system components and users
- Rollback plan in case reset causes additional problems

**Reset Process Documentation:**
- Step-by-step procedure for executing reset
- Validation procedures to ensure reset is successful
- Testing procedures to ensure system functions correctly
- Communication plan for notifying affected users

**Post-Reset Documentation:**
- Complete record of reset execution and results
- Validation results and any issues encountered
- Lessons learned and improvements for future resets
- Updated system documentation reflecting post-reset state

### 5.4 How Resets Affect Version Numbers
**Reset version number handling:**

**Version Number After Reset:**
- Version number typically advances to next major version
- Reset must be clearly documented in changelog
- All system components must be updated to new version
- Cross-references must be updated to reflect new version

**Version History Preservation:**
- Previous version history must be preserved in documentation
- Reset must be clearly marked in version history
- Reasons for reset must be documented in changelog
- Migration path from pre-reset versions must be documented

---

## 6. Agent Responsibilities

### 6.1 How Agents Interpret Version Boundaries
**Agents must understand version boundaries:**

**Version Compatibility Assessment:**
- Agents must check version compatibility before making changes
- Agents must understand impact of their changes on version requirements
- Agents must coordinate with other agents for version-impacting changes
- Agents must validate that their changes don't break version discipline

**Change Impact Analysis:**
- Agents must analyze whether their changes require version increment
- Agents must determine appropriate version increment level (major/minor/patch)
- Agents must coordinate with release management for version changes
- Agents must ensure all related components are updated consistently

**Cross-Agent Coordination:**
- Agents must communicate version-impacting changes to other agents
- Agents must coordinate version increments across all affected components
- Agents must ensure version consistency across all system files
- Agents must validate version changes don't break other agent operations

### 6.2 How Agents Enforce Version Discipline
**Agents must actively enforce version discipline:**

**Version Validation:**
- Agents must validate version numbers in all files they modify
- Agents must ensure version consistency across related files
- Agents must check that version increments follow doctrine rules
- Agents must prevent forbidden version jumps or changes

**Change Classification:**
- Agents must correctly classify their changes by version impact
- Agents must escalate to appropriate authority for major version changes
- Agents must coordinate with other agents for minor version changes
- Agents must handle patch version changes according to discipline

**Documentation Requirements:**
- Agents must document all version-related changes in dialog system
- Agents must update WOLFIE headers with correct version information
- Agents must maintain changelog entries for version changes
- Agents must ensure cross-references reflect current version

### 6.3 How Agents Update Headers During Version Changes
**Agents must update headers correctly during version changes:**

**Version Field Updates:**
- Update `file.last_modified_system_version` to current version
- Ensure version format follows semantic versioning rules
- Validate version number is correct and current
- Coordinate version updates across all modified files

**Dialog Block Updates:**
- Update dialog block to reflect version change
- Include version change information in dialog message
- Use appropriate mood color for version change impact
- Ensure dialog block matches dialog thread entry

**Metadata Consistency:**
- Update header atoms if version-related atoms are referenced
- Ensure all metadata reflects current version
- Validate cross-references use current version
- Maintain consistency across all system files

**Validation Requirements:**
- Validate header syntax after version updates
- Ensure all required fields are present and correct
- Verify atom references resolve to current values
- Confirm cross-references are accurate and current

---

## 7. Examples

### 7.1 Correct Version Increments

**Example 1: Patch Version Increment**
```
Current Version: 4.0.14
Change: Fix typo in documentation
New Version: 4.0.15
Reasoning: Documentation fix is a patch-level change
Process:
1. Identify change as patch-level (documentation fix)
2. Increment patch version: 4.0.14 → 4.0.15
3. Update all system files with new version
4. Document change in changelog and dialog system
5. Validate version consistency across system
```

**Example 2: Minor Version Increment**
```
Current Version: 4.0.15
Change: Add new API endpoint for user preferences
New Version: 4.1.0
Reasoning: New functionality is a minor-level change
Process:
1. Identify change as minor-level (new feature)
2. Increment minor version, reset patch: 4.0.15 → 4.1.0
3. Update all system files with new version
4. Document new feature in changelog and documentation
5. Coordinate with all agents for version update
```

**Example 3: Major Version Increment**
```
Current Version: 4.1.0
Change: Breaking change to API authentication system
New Version: 5.0.0
Reasoning: Breaking change requires major version increment
Process:
1. Identify change as major-level (breaking change)
2. Increment major version, reset minor/patch: 4.1.0 → 5.0.0
3. Plan migration strategy for existing users
4. Update all system files with new version
5. Coordinate comprehensive testing and validation
6. Document breaking changes and migration path
```

### 7.2 Incorrect Version Increments

**Example 1: Forbidden Version Jump**
```
❌ INCORRECT:
Current Version: 4.0.14
Change: Add new feature
Attempted Version: 4.0.16 (skipping 4.0.15)
Problem: Skipping version numbers is forbidden

✅ CORRECT:
Current Version: 4.0.14
Change: Add new feature
Correct Version: 4.1.0 (new feature = minor increment)
Process: Increment minor version, reset patch version
```

**Example 2: Wrong Version Level**
```
❌ INCORRECT:
Current Version: 4.0.14
Change: Breaking API change
Attempted Version: 4.0.15 (patch increment)
Problem: Breaking change requires major version increment

✅ CORRECT:
Current Version: 4.0.14
Change: Breaking API change
Correct Version: 5.0.0 (breaking change = major increment)
Process: Increment major version, reset minor and patch
```

**Example 3: Backward Version Movement**
```
❌ INCORRECT:
Current Version: 4.0.14
Change: Rollback to previous state
Attempted Version: 4.0.13 (moving backward)
Problem: Backward version movement is forbidden

✅ CORRECT:
Current Version: 4.0.14
Change: Rollback to previous state
Correct Version: 4.0.14 (version stays same during rollback)
Process: Rollback system state, keep version number, document rollback
```

### 7.3 Correct Reset Handling

**Example 1: Authorized Development Reset**
```
Situation: Major architectural change during development
Current Version: 4.0.14
Reset Justification: Fundamental change to semantic OS architecture
Reset Process:
1. Document complete justification for reset
2. Analyze impact on all system components
3. Plan migration strategy for existing work
4. Execute reset with complete system rebuild
5. Advance to new major version: 5.0.0
6. Document reset in changelog and version history
7. Update all documentation to reflect new architecture
```

**Example 2: Forbidden Convenience Reset**
```
❌ INCORRECT:
Situation: Complex bug that's difficult to fix
Current Version: 4.0.14
Attempted Reset: Reset system to avoid fixing bug
Problem: Convenience resets are forbidden

✅ CORRECT:
Situation: Complex bug that's difficult to fix
Current Version: 4.0.14
Correct Approach: Fix bug through proper patch process
Process:
1. Analyze bug and develop proper fix
2. Implement fix following patch discipline
3. Test fix thoroughly
4. Increment patch version: 4.0.14 → 4.0.15
5. Document fix in changelog and dialog system
```

### 7.4 Correct Doctrine-Driven Version Bump

**Example 1: Major Doctrine Change**
```
Change: Modify WOLFIE header format (breaking change)
Current Version: 4.0.14
Impact Analysis: Breaking change affects all system files
Version Decision: Major increment required
New Version: 5.0.0
Process:
1. Identify doctrine change as breaking
2. Plan migration strategy for all existing files
3. Coordinate with all agents for system-wide update
4. Execute doctrine change and file updates
5. Increment major version: 4.0.14 → 5.0.0
6. Document breaking change and migration path
7. Validate all system files work with new doctrine
```

**Example 2: Minor Doctrine Enhancement**
```
Change: Add new optional WOLFIE header field
Current Version: 4.0.14
Impact Analysis: Enhancement doesn't break existing files
Version Decision: Minor increment required
New Version: 4.1.0
Process:
1. Identify doctrine change as enhancement
2. Plan gradual adoption of new field
3. Update doctrine documentation
4. Increment minor version: 4.0.14 → 4.1.0
5. Document enhancement in changelog
6. Coordinate gradual adoption across system files
```

---

## 8. Cross-References

- **[WOLFIE Header Doctrine](WOLFIE_HEADER_DOCTRINE.md)** (`docs/doctrine/WOLFIE_HEADER_DOCTRINE.md`) - MANDATORY rules for version information in file headers
- **[Dialog Doctrine](DIALOG_DOCTRINE.md)** - MANDATORY rules for documenting version changes in dialog system
- **[Agent Runtime](AGENT_RUNTIME.md)** - Agent responsibilities for version discipline and coordination
- **[Patch Discipline](PATCH_DISCIPLINE.md)** - Development workflow governance and version-impacting changes
- **[Directory Structure](DIRECTORY_STRUCTURE.md)** - File organization and version-related structural changes
- **[Metadata Governance](METADATA_GOVERNANCE.md)** - Metadata management and version consistency requirements
- **[Architecture Sync](ARCHITECTURE_SYNC.md)** - Cross-system synchronization and version coordination
- **[Agent Prompt Doctrine](AGENT_PROMPT_DOCTRINE.md)** - Agent communication standards and version-related coordination

---

**This Versioning Doctrine is MANDATORY and NON-NEGOTIABLE.**

All version changes in Lupopedia must follow these versioning requirements exactly. Version discipline ensures predictable system evolution, proper change communication, and maintainable system architecture.

> **Every change must be reflected in appropriate version increments.**  
> **Version numbers communicate change impact and scope.**  
> **Version discipline prevents uncontrolled system evolution.**  
> **Semantic versioning enables predictable system behavior.**

This is architectural doctrine.

---