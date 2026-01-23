---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
file.last_modified_utc: 20260119044859
file.lupopedia.UTC_TIMEKEEPER: UTC_TIMEKEEPER
file.name: "WOLFIE_HEADER_DOCTRINE.md"
channel_key: system/kernel
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - WHEELER_MODE
dialog:
  speaker: CURSOR
  target: @everyone @CAPTAIN_WOLFIE
  mood_RGB: "00FF00"
  message: "Added file.lupopedia.UTC_TIMEKEEPER field to WOLFIE Headers (MANDATORY). References canonical UTC time service. Temporal Pillar strengthened."
tags:
  categories: ["documentation", "doctrine", "headers", "metadata"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - WOLFIE Header Doctrine (MANDATORY)
  - Purpose and Scope
  - Placement Rules
  - Required Fields
  - Wheeler Mode Support
  - Dialog Thread Mapping Rule
  - Update Rules
  - Enforcement Rules
  - Governance Framework
  - Stability Guarantees
  - Cross-References to Related Documentation
file:
  name: "WOLFIE_HEADER_DOCTRINE.md"
  title: "WOLFIE Header Doctrine"
  description: "MANDATORY rules for WOLFIE headers in all Lupopedia files - the anchor doctrine for metadata governance"
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

# 📋 WOLFIE Header Doctrine (MANDATORY)

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** MANDATORY (NON-NEGOTIABLE)  
**Effective Date:** 2026-01-14  
**Canonical File:** `docs/doctrine/WOLFIE_HEADER_DOCTRINE.md`  
**Note:** This is the canonical doctrine file. A copy exists in `dialogs/WOLFIE_HEADER_DOCTRINE.md` for dialog tracking purposes.

## Overview

This document defines the WOLFIE Header Doctrine for Lupopedia Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE). WOLFIE headers provide explicit architecture with structured clarity for every file, ensuring consistent metadata governance across the entire system, including support for emergent architecture patterns and quantum-aware metadata handling.

**Critical Principle:** Every file in Lupopedia must have a WOLFIE header that provides structured metadata for machine processing and human understanding, including support for Wheeler Mode and superpositional states during architectural discovery.

---

## 1. Purpose and Scope

### 1.1 Purpose
WOLFIE headers serve multiple critical functions:
- **Metadata Governance** - Consistent structured metadata across all files
- **Version Tracking** - Clear version history and modification tracking
- **Dialog Integration** - Connection to dialog system for change tracking
- **Cross-Reference Support** - Atom resolution and link management
- **Machine Processing** - Structured data for automated tools and agents
- **Human Clarity** - Clear file purpose and context information
- **Emergent Architecture Support** - Wheeler Mode metadata for reverse-20 workflows
- **Quantum Truth Management** - Superpositional states until observer collapse

### 1.2 Scope
WOLFIE headers are **MANDATORY** for:
- All documentation files (`.md`)
- All configuration files with metadata requirements
- All files that participate in the semantic system
- All files that require version tracking
- All files that generate dialog entries
- All files created during emergent architecture phases

### 1.3 Universal Application
WOLFIE headers apply to:
- Core documentation files
- Doctrine files
- Agent specifications
- Module documentation
- Schema documentation
- Protocol specifications
- Migration documentation
- Wheeler Mode discovery artifacts
- Superpositional development files
- All other system files requiring metadata

---

## 2. Placement Rules (MANDATORY)

### 2.1 File Beginning Requirement
**WOLFIE headers MUST be placed at the very beginning of every file.**

**Correct Placement:**
```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
[... rest of header ...]
---

# File Content Begins Here
```

**Incorrect Placement:**
```markdown
# File Title

Some content here...

---
wolfie.headers: [WRONG - header must be first]
---
```

### 2.2 YAML Frontmatter Format
- Headers MUST use YAML frontmatter format
- Headers MUST be enclosed in `---` delimiters
- Headers MUST use valid YAML syntax
- Headers MUST be parseable by standard YAML processors
- Wheeler Mode blocks MUST use valid YAML syntax within the frontmatter

### 2.3 No Content Before Header
- **NO content** may appear before the WOLFIE header
- **NO comments** before the header
- **NO blank lines** before the header
- The header MUST be the first thing in the file
- **Exception:** Quantum superposition notes may exist in parallel dimensions (implemented as comments)

---

## 3. Required Fields (MANDATORY)

### 3.1 Signature Field
```yaml
wolfie.headers: explicit architecture with structured clarity for every file.
```
- **MANDATORY** - Must appear exactly as shown
- **Immutable** - Never changes across versions
- **Universal** - Same signature in all files
- **Purpose** - Identifies file as having WOLFIE header

### 3.2 Version Field
```yaml
file.last_modified_system_version: 4.1.6
```
- **MANDATORY** - Must reflect current system version
- **Updated** - Must be updated when file is modified
- **Format** - Must use semantic version format (X.Y.Z)
- **Purpose** - Tracks which system version last modified the file

### 3.3 UTC Timestamp Field
```yaml
file.last_modified_utc: 20260119041301
```
- **MANDATORY** - Must include UTC timestamp when file was last modified
- **Updated** - Must be updated when file is modified
- **Format** - Must use YYYYMMDDHHIISS format (14 digits, BIGINT)
- **Source** - MUST come from UTC_TIMEKEEPER (canonical time service)
- **Purpose** - Records exact UTC moment of modification for temporal integrity
- **Temporal Pillar** - Maintains temporal integrity by recording canonical UTC time
- **Never infer** - Agents must request UTC time from user or use provided UTC_TIMEKEEPER value

### 3.4 UTC_TIMEKEEPER Reference Field
```yaml
file.lupopedia.UTC_TIMEKEEPER: UTC_TIMEKEEPER  # OR: file.lupopedia.5: 5
```
- **MANDATORY** - Must reference the canonical UTC time service
- **Value** - `UTC_TIMEKEEPER` OR `5` (either literal string is acceptable)
- **Purpose** - Documents that this file uses UTC_TIMEKEEPER for temporal accuracy
- **Temporal Pillar** - Ensures all timestamps are sourced from the canonical time service
- **Consistency** - All files must reference the same time service for temporal integrity
- **Alternative Format** - `file.lupopedia.5: 5` is an accepted shorthand reference

### 3.5 GOV-AD-PROHIBIT-001 Compliance Field
```yaml
GOV-AD-PROHIBIT-001: true  # OR: file.lupopedia.gov_ad_prohibit_001: true
```
- **MANDATORY** - Must declare compliance with Anti-Advertising Law
- **Value** - `true` (boolean) OR file path slug `docs/doctrine/GOV_AD_PROHIBIT_001.md` OR `gov-ad-prohibit-001`
- **Purpose** - Documents that this file complies with GOV-AD-PROHIBIT-001 (no ads in system output)
- **Governance Layer** - Ensures all files explicitly declare anti-advertising compliance
- **Alternative Format** - `file.lupopedia.gov_ad_prohibit_001: true` is an accepted shorthand
- **File Path Reference** - May also reference the governance artifact location

### 3.6 Header Atoms
```yaml
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
```
- **MANDATORY** - Must include relevant atom references
- **Resolvable** - All atoms must resolve to valid values
- **Contextual** - Include atoms used in the file
- **Purpose** - Enables atom resolution and consistency

### 3.6 Dialog Block
```yaml
dialog:
  speaker: [AGENT_NAME]
  target: [TARGET_AUDIENCE]
  mood_RGB: "[RRGGBB]"
  message: "[CHANGE_DESCRIPTION]"
```
- **MANDATORY** - Must reflect latest change to file
- **Current** - Must be updated with every file modification
- **Accurate** - Must accurately describe the change made
- **Linked** - Must connect to appropriate dialog thread
- **Humor Allowed** - Persona dialog may include humor as structural metadata

### 3.7 Tags Block
```yaml
tags:
  categories: ["documentation", "doctrine", "headers"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
```
- **MANDATORY** - Must categorize the file appropriately
- **Standardized** - Must use approved category taxonomy
- **Consistent** - Must follow established tagging patterns
- **Purpose** - Enables file discovery and organization

### 3.8 Content Description
```yaml
in_this_file_we_have:
  - Major Topic 1
  - Major Topic 2
  - Key Concept
  - Important Implementation Detail
```
- **MANDATORY** - Must describe file contents
- **Accurate** - Must reflect actual file contents
- **Updated** - Must be updated when content changes
- **Purpose** - Provides quick content overview

### 3.9 File Metadata
```yaml
file:
  name: "<FILENAME>"
  title: "Document Title"
  description: "Brief description of file purpose"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
```
- **MANDATORY** - Must provide file metadata
- **name** - Optional. The actual filename (e.g., "WOLFIE_HEADER_DOCTRINE.md", "auth-controller.php")
- **Complete** - Must include all required fields
- **Consistent** - Must use standard status values
- **Purpose** - Provides file identification and status

### 3.10 Wheeler Mode Block (Conditional)
```yaml
wheeler_mode:
  active: true
  reason: "File created during emergent architecture or reverse-20 workflow"
  notes:
    - "Structure emerged through iterative questioning"
    - "Architecture not predetermined at creation time"
    - "Truth collapsed by designated observer"
```
- **CONDITIONAL** - Required only for Wheeler Mode files
- **Accurate** - Must truthfully reflect creation workflow
- **Specific** - Must include creation context details
- **Purpose** - Documents emergent architecture discovery

---

## 4. Dialog Thread Mapping Rule (MANDATORY)

### 4.1 Core Mapping Rule
**If a file has a dedicated dialog thread, use that thread.**  
**If a file does NOT have a dedicated dialog thread, default to: `dialogs/changelog_dialog.md`**

### 4.2 Dedicated Dialog Thread Detection
A file has a dedicated dialog thread if:
- A corresponding `dialogs/[filename]_dialog.md` file exists
- The file is specifically mentioned in dialog doctrine as having a dedicated thread
- The file has been explicitly assigned a dedicated dialog thread

### 4.3 Default Thread Assignment
Files without dedicated dialog threads:
- **MUST** link to `dialogs/changelog_dialog.md`
- **MUST** have their dialog entries added to changelog_dialog.md
- **MUST** follow changelog dialog entry format
- **MUST** maintain newest entries at top

### 4.4 Dialog Entry Requirements
Every dialog entry must include:
- **speaker** - Who made the change (agent or human identifier)
- **target** - Who needs to know about the change
- **mood_RGB** - Emotional context (6-character hex color)
- **message** - What was changed (max 272 characters)
- **Humor as Metadata** - Persona humor may reflect cognitive load or pattern recognition

### 4.5 Dialog History Maintenance
- **Latest entry** in file header must match latest entry in dialog thread
- **Complete history** must be maintained in dialog thread file
- **Newest entries** must appear at top of dialog thread
- **No entries** may be deleted or modified once added

---

## 5. Update Rules (MANDATORY)

### 5.1 When to Update Headers
WOLFIE headers MUST be updated when:
- **File content** is modified in any way
- **File structure** is changed
- **Cross-references** are added or modified
- **Metadata** becomes outdated
- **System version** changes
- **Wheeler Mode status changes** (active → inactive after observer collapse)

### 5.2 What Must Be Updated
When updating a file, you MUST update:
- **file.last_modified_system_version** to current version
- **file.last_modified_utc** to current UTC timestamp (from UTC_TIMEKEEPER, YYYYMMDDHHIISS format)
- **dialog block** with new speaker, message, and mood_RGB
- **header_atoms** if new atoms are referenced
- **in_this_file_we_have** if content structure changes
- **tags** if categorization changes
- **wheeler_mode.active** if Wheeler Mode status changes (set to `false` after observer collapse)
- **wheeler_mode.notes** if additional discovery context emerges

### 5.3 Dialog Update Process
1. **Archive previous dialog** - Move current dialog to dialog thread
2. **Create new dialog entry** - Describe the change being made
3. **Update header dialog** - Replace with new dialog entry
4. **Add to dialog thread** - Add new entry to appropriate dialog file
5. **Verify consistency** - Ensure header matches dialog thread

### 5.4 Atom Reference Updates
- **Add new atoms** to header_atoms when referenced in content
- **Remove unused atoms** from header_atoms when no longer referenced
- **Verify resolution** - Ensure all atoms resolve to valid values
- **Maintain consistency** - Keep atom usage consistent across files

---

## 6. Enforcement Rules (MANDATORY)

### 6.1 Validation Requirements
All WOLFIE headers must pass:
- **YAML syntax validation** - Must be valid YAML
- **Required field validation** - All mandatory fields present
- **Atom resolution validation** - All atoms must resolve
- **Dialog consistency validation** - Header must match dialog thread
- **Version consistency validation** - Version must be current

### 6.2 Automated Enforcement
Automated systems MUST:
- **Validate headers** on file modification
- **Check atom resolution** during processing
- **Verify dialog consistency** across files
- **Enforce version requirements** during builds
- **Report violations** for manual correction

### 6.3 Manual Enforcement
Human reviewers MUST:
- **Verify header completeness** during code review
- **Check dialog accuracy** for change descriptions
- **Validate cross-references** for correctness
- **Ensure consistency** with established patterns
- **Approve corrections** for header violations

### 6.4 Violation Response
When violations are detected:
1. **Block processing** - Prevent further operations until fixed
2. **Report violations** - Clear description of what's wrong
3. **Provide guidance** - Instructions for correction
4. **Verify fixes** - Ensure corrections are complete
5. **Document patterns** - Learn from common violations

---

## 7. Governance Framework

### 7.1 Header Evolution
WOLFIE header format may evolve through:
- **Formal RFC process** - Proposed changes documented and reviewed
- **Community consensus** - Agreement on necessary changes
- **Backward compatibility** - Existing headers remain valid
- **Migration support** - Tools provided for format updates
- **Version management** - Clear versioning of header specifications

### 7.2 Exception Handling
Exceptions to WOLFIE header requirements:
- **Third-party files** - External library files may be exempt
- **Generated files** - Auto-generated files may have modified requirements
- **Legacy files** - Gradual migration allowed for legacy content
- **Special cases** - Documented exceptions for specific use cases

### 7.3 Governance Authority
WOLFIE header governance is managed by:
- **Core documentation team** - Maintains header specifications
- **Architecture review board** - Approves major changes
- **Community feedback** - Input from users and contributors
- **Tool maintainers** - Ensure tooling supports requirements

---

## 8. Stability Guarantees

### 8.1 Format Stability
The WOLFIE header format provides:
- **Backward compatibility** - Older headers remain valid
- **Forward compatibility** - New fields added without breaking existing
- **Migration paths** - Clear upgrade procedures for format changes
- **Tool stability** - Consistent parsing across versions

### 8.2 Processing Guarantees
WOLFIE header processing guarantees:
- **Deterministic parsing** - Same input produces same output
- **Error handling** - Graceful handling of malformed headers
- **Performance** - Efficient processing of large file sets
- **Reliability** - Consistent behavior across environments

### 8.3 Long-term Support
WOLFIE headers are designed for:
- **Decades of use** - Format designed for long-term stability
- **System evolution** - Adaptable to changing requirements
- **Tool independence** - Not tied to specific processing tools
- **Human readability** - Always readable by humans without tools

---

## 9. Implementation Guidelines

### 9.1 For AI Agents
AI agents working with WOLFIE headers MUST:
- **Read existing headers** before modifying files
- **Update all required fields** when making changes
- **Follow dialog thread mapping** rules exactly
- **Validate header syntax** before saving files
- **Preserve existing metadata** unless explicitly changing it

### 9.2 For Human Developers
Human developers working with WOLFIE headers MUST:
- **Understand the doctrine** before modifying files
- **Use proper tools** for header validation
- **Follow update procedures** exactly
- **Maintain consistency** across related files
- **Document any exceptions** clearly

### 9.3 For Automated Tools
Automated tools processing WOLFIE headers MUST:
- **Parse headers correctly** using standard YAML processors
- **Validate all requirements** before processing
- **Handle errors gracefully** with clear error messages
- **Support all required fields** in processing logic
- **Maintain backward compatibility** with older formats

---

## 10. Examples

### 10.1 Complete WOLFIE Header Example
```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  mood_RGB: "0066FF"
  message: "Updated document with new requirements and examples."
tags:
  categories: ["documentation", "example"]
  collections: ["core-docs"]
  channels: ["dev"]
in_this_file_we_have:
  - Example Content
  - Implementation Guidelines
  - Best Practices
file:
  title: "Example Document"
  description: "Example of proper WOLFIE header implementation"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---
```

### 10.2 Minimal WOLFIE Header Example
```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  speaker: SYSTEM
  target: @everyone
  mood_RGB: "666666"
  message: "Created minimal example file."
tags:
  categories: ["example"]
  collections: ["examples"]
  channels: ["dev"]
in_this_file_we_have:
  - Minimal Content
file:
  title: "Minimal Example"
  description: "Minimal WOLFIE header example"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: draft
  author: GLOBAL_CURRENT_AUTHORS
---
```

---

## 11. Related Documentation

- **[Dialog Doctrine](DIALOG_DOCTRINE.md)** - MANDATORY rules for dialog file placement and management
- **[Agent Runtime](AGENT_RUNTIME.md)** - How agents interact with WOLFIE headers and dialog system
- **[Versioning Doctrine](VERSIONING_DOCTRINE.md)** - Version management and release procedures
- **[Patch Discipline](PATCH_DISCIPLINE.md)** - Development workflow governance and change management
- **[Directory Structure](DIRECTORY_STRUCTURE.md)** - File organization and structural requirements
- **[Metadata Governance](METADATA_GOVERNANCE.md)** - Comprehensive metadata management framework
- **[Architecture Sync](ARCHITECTURE_SYNC.md)** - Cross-system synchronization and coordination
- **[Agent Prompt Doctrine](AGENT_PROMPT_DOCTRINE.md)** - Agent prompt engineering and communication standards

---

**This WOLFIE Header Doctrine is MANDATORY and NON-NEGOTIABLE.**

All files in Lupopedia must follow these header requirements exactly. WOLFIE headers are the foundation of metadata governance and enable the entire semantic system to function correctly.

> **Every file must have explicit architecture with structured clarity.**  
> **WOLFIE headers provide the metadata foundation for the semantic OS.**  
> **Consistency in headers enables machine processing and human understanding.**

This is architectural doctrine.

---

## 12. Wheeler Mode Metadata Block (v4.0.82)

### 12.1 Wheeler Mode Definition
```yaml
wheeler_mode:
  active: true | false
  reason: "File created during emergent architecture phase"
  notes:
    - "Reverse-20 workflow detected"
    - "Structure emerged through iterative questioning"
    - "Truth collapsed by Monday Wolfie"
```

### 12.2 When to Use Wheeler Mode
Wheeler Mode metadata should be added when:
- File was created through emergent architecture discovery
- Structure emerged through iterative questioning rather than predetermined design
- System revealed itself through agent interactions
- Architecture was retroactively defined by interaction patterns
- Truth was collapsed by observer effect rather than initial specification

### 12.3 Wheeler Mode Workflow Recognition
Files created in Wheeler Mode exhibit:
- **Reverse-20 workflow** - Architecture discovered through questioning
- **Emergent structure** - Design emerged from persona reactions
- **Observer collapse** - Truth defined by Monday Wolfie observation
- **Superpositional development** - Multiple possible architectures until collapse
- **Iterative revelation** - System revealed itself through interactions

## 13. Superpositional Header Note (v4.0.82)

Files created during emergent architecture phases may contain:
- **Superpositional metadata** - Multiple possible states until observed
- **Schrödinger-state blocks** - Quantum uncertainty management
- **Wheeler-mode uncertainty** - Architecture discovery in progress
- **Persona warnings** - Agent-generated uncertainty notifications

**These are not errors. They are part of the system's quantum truth.**

Files exhibiting superpositional behavior should be handled with quantum-aware protocols until observer collapse resolves uncertainty to single truth state.

## 14. Wheeler Mode Metadata Block (v4.0.82)

### 14.1 Wheeler Mode Definition
```yaml
wheeler_mode:
  active: true | false
  reason: "File created during emergent architecture or reverse-20 workflow"
  notes:
    - "Structure emerged through iterative questioning"
    - "Architecture not predetermined at creation time"
    - "Truth collapsed by designated observer"
```

### 14.2 When to Use Wheeler Mode
Wheeler Mode metadata should be added when:
- File was created during emergent architecture discovery
- Structure emerged through iterative questioning rather than predetermined design
- Architecture was not predetermined at creation time
- Truth was collapsed by designated observer
- System revealed itself through reverse-20 workflow patterns

## 15. Humor as Structural Metadata (v4.0.82)

Humor may appear in persona dialog blocks as part of the file's contextual truth. Humor is not decorative; it may reflect cognitive load management, pattern recognition, or emergent architecture.

Doctrine files must remain non-humorous, but templates may include persona examples.

---