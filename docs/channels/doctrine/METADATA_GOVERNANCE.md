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
  message: "Created METADATA_GOVERNANCE.md as Phase 2 core document. Defines comprehensive metadata governance including metadata categories, lifecycle management, enforcement rules, and cross-system metadata coordination."
tags:
  categories: ["documentation", "doctrine", "metadata", "governance"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Metadata Governance Doctrine (MANDATORY)
  - Purpose and Scope
  - Metadata Categories and Types
  - Metadata Rules and Requirements
  - Metadata Lifecycle Management
  - Metadata Enforcement and Validation
  - Cross-System Metadata Coordination
  - Metadata Examples and Anti-Patterns
  - Cross-References to Related Documentation
file:
  title: "Metadata Governance Doctrine"
  description: "MANDATORY rules for metadata management, governance, and consistency across Lupopedia WOLFIE architecture"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 📊 Metadata Governance Doctrine (MANDATORY)

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** MANDATORY (NON-NEGOTIABLE)  
**Effective Date:** 2026-01-14

## Overview

This document defines the Metadata Governance Doctrine for Lupopedia Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE). Metadata governance ensures system stability, consistency, and traceability through comprehensive metadata management across all system components.

**Critical Principle:** All system metadata must be consistent, accurate, and maintained according to strict governance rules that enable machine processing and human understanding.

---

## 1. Purpose and Scope

### 1.1 Why Metadata Governance Exists
Metadata governance serves multiple critical functions:
- **System Stability** - Consistent metadata prevents system drift and inconsistencies
- **Machine Processing** - Structured metadata enables automated tools and validation
- **Human Understanding** - Clear metadata provides context and navigation for users
- **Change Tracking** - Metadata provides complete audit trail for all system changes
- **Cross-Reference Integrity** - Metadata maintains relationships between system components
- **Quality Assurance** - Metadata enables validation and quality control mechanisms

### 1.2 How Metadata Ensures System Stability
Metadata ensures stability through:
- **Consistency Enforcement** - All metadata follows standardized formats and rules
- **Validation Requirements** - Metadata must pass validation before acceptance
- **Synchronization Mechanisms** - Metadata stays synchronized across related components
- **Change Tracking** - All metadata changes are tracked and documented
- **Error Prevention** - Metadata validation prevents common errors and inconsistencies
- **Recovery Support** - Metadata enables system recovery and rollback procedures

### 1.3 How Metadata Interacts with Doctrine and Versioning
Metadata interaction with system governance:
- **Doctrine Compliance** - Metadata must comply with all applicable doctrine requirements
- **Version Tracking** - Metadata tracks version information for all system components
- **Change Documentation** - Metadata documents all changes through dialog system
- **Cross-Reference Maintenance** - Metadata maintains links between related doctrine files
- **Governance Enforcement** - Metadata enables enforcement of governance rules
- **Evolution Support** - Metadata supports system evolution and migration

### 1.4 Metadata as System Foundation
Metadata serves as foundation for:
- **Semantic OS Operations** - All semantic operations depend on accurate metadata
- **Agent Coordination** - Agents use metadata for coordination and communication
- **Documentation System** - Documentation relies on metadata for organization and navigation
- **Quality Control** - Quality systems use metadata for validation and verification
- **System Integration** - Integration depends on consistent metadata across components

---

## 2. Metadata Categories

### 2.1 WOLFIE Headers
**Primary metadata container for all system files**

**Required WOLFIE Header Fields:**
```yaml
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: [AGENT_NAME]
  target: [TARGET_AUDIENCE]
  mood_RGB: "[RRGGBB]"
  message: "[CHANGE_DESCRIPTION]"
tags:
  categories: ["category1", "category2"]
  collections: ["collection1", "collection2"]
  channels: ["channel1", "channel2"]
in_this_file_we_have:
  - Content Item 1
  - Content Item 2
file:
  title: "File Title"
  description: "File description"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
```

**WOLFIE Header Governance:**
- Must appear at beginning of every file
- Must use valid YAML syntax
- Must include all required fields
- Must be updated with every file modification
- Must maintain consistency with dialog threads

### 2.2 Dialog Metadata
**Metadata for dialog system and change tracking**

**Dialog Entry Metadata:**
```yaml
## YYYY-MM-DD HH:MM:SS UTC
**Speaker:** [AGENT_NAME]
**Target:** [TARGET_AUDIENCE]
**Mood:** [RRGGBB]
**Message:** "[CHANGE_DESCRIPTION]"
```

**Dialog Thread Metadata:**
- Thread assignment (dedicated vs. default)
- Chronological ordering (newest first)
- Speaker identification and attribution
- Target audience specification
- Mood context and emotional state
- Change description and reasoning

**Dialog Governance:**
- All dialog entries must follow exact format
- Dialog threads must maintain chronological integrity
- Dialog metadata must synchronize with WOLFIE headers
- Dialog history must be immutable once written

### 2.3 Version Metadata
**Metadata for version tracking and system evolution**

**Version Information Fields:**
- `file.last_modified_system_version` - When file was last modified
- `version` in file metadata - Current system version reference
- Version atoms - Symbolic references to version constants
- Changelog entries - Version-specific change documentation

**Version Metadata Governance:**
- Version information must be consistent across all files
- Version updates must follow semantic versioning rules
- Version metadata must be synchronized during version changes
- Version history must be preserved in changelog and dialog system

### 2.4 Cross-Reference Metadata
**Metadata for maintaining relationships between system components**

**Cross-Reference Types:**
- **Direct Links** - Explicit links to related files or sections
- **Atom References** - Symbolic references resolved at processing time
- **Bidirectional Links** - Reciprocal references between related files
- **Hierarchical References** - Parent-child relationships in documentation structure
- **Dependency References** - Dependencies between system components

**Cross-Reference Governance:**
- All cross-references must be valid and resolvable
- Bidirectional references must be maintained consistently
- Cross-reference updates must be coordinated across affected files
- Broken references must be detected and repaired promptly

### 2.5 File Classification Metadata
**Metadata for categorizing and organizing system files**

**Classification Categories:**
```yaml
tags:
  categories: ["documentation", "doctrine", "implementation", "configuration"]
  collections: ["core-docs", "agent-specs", "user-guides"]
  channels: ["dev", "public", "admin"]
```

**Classification Governance:**
- Categories must use approved taxonomy
- Collections must reflect logical groupings
- Channels must indicate appropriate audience
- Classification must be consistent across related files

### 2.6 Agent Lane Metadata
**Metadata for agent coordination and lane separation**

**Agent Lane Information:**
- File ownership and responsibility
- Agent access permissions and restrictions
- Coordination requirements for multi-agent work
- Handoff procedures and requirements

**Agent Lane Governance:**
- Lane assignments must be clear and unambiguous
- Lane boundaries must be respected by all agents
- Lane coordination must be documented in dialog system
- Lane violations must be detected and prevented

---

## 3. Metadata Rules

### 3.1 Required Fields
**Fields that MUST be present in all applicable metadata:**

**WOLFIE Header Required Fields:**
- `wolfie.headers` - Signature line (exact text required)
- `file.last_modified_system_version` - Current system version
- `header_atoms` - List of atoms referenced in file
- `dialog` - Latest change information
- `tags` - File classification information
- `in_this_file_we_have` - Content description
- `file` - File metadata block

**Dialog Entry Required Fields:**
- Timestamp in UTC format
- Speaker identification
- Target audience
- Mood color (6-character hex)
- Message description (max 272 characters)

**Version Metadata Required Fields:**
- System version in semantic format
- Version consistency across related files
- Version change documentation in dialog system

### 3.2 Optional Fields
**Fields that MAY be present but are not required:**

**WOLFIE Header Optional Fields:**
- `placement` - Special placement instructions
- `lineage` - Historical information about file evolution
- `federation` - Cross-system integration information
- Additional custom fields for specific use cases

**Dialog Optional Fields:**
- Extended context information
- Cross-references to related dialog entries
- Additional metadata for special circumstances

**Classification Optional Fields:**
- Specialized tags for specific domains
- Extended collection information
- Custom channel definitions

### 3.3 Forbidden Fields
**Fields that MUST NOT be present in metadata:**

**Forbidden WOLFIE Header Fields:**
- `wolfie.headers.version` - Deprecated field, use `file.last_modified_system_version`
- Duplicate field names with different values
- Fields that contradict required field values
- Fields with invalid YAML syntax or structure

**Forbidden Dialog Fields:**
- Fields that break required dialog format
- Duplicate timestamps or conflicting chronology
- Fields that contradict dialog doctrine requirements

### 3.4 Update Rules
**Rules governing how metadata must be updated:**

**Update Triggers:**
- Any file content modification requires metadata update
- Version changes require metadata synchronization
- Cross-reference changes require bidirectional updates
- Dialog entries require WOLFIE header synchronization

**Update Process:**
1. **Identify Required Updates** - Determine which metadata needs updating
2. **Plan Coordination** - Identify related files that need updates
3. **Execute Updates** - Update all metadata consistently
4. **Validate Updates** - Ensure all updates are correct and consistent
5. **Document Updates** - Record updates in dialog system

### 3.5 Validation Rules
**Rules for validating metadata correctness:**

**Syntax Validation:**
- YAML syntax must be valid and parseable
- Field names must match required specifications exactly
- Field values must conform to specified formats
- No duplicate or conflicting field definitions

**Content Validation:**
- Atom references must resolve to valid values
- Cross-references must point to existing files or sections
- Version information must be consistent across files
- Dialog entries must follow required format exactly

**Consistency Validation:**
- WOLFIE header dialog must match latest dialog thread entry
- Version information must be consistent across related files
- Cross-references must be bidirectional where required
- Classification must be consistent with file content

### 3.6 Inheritance Rules
**Rules for metadata inheritance and propagation:**

**No Direct Inheritance:**
- Metadata is not inherited from parent directories or files
- Each file must have complete metadata
- Metadata must be explicitly specified for each file

**Template-Based Consistency:**
- Similar files should use consistent metadata patterns
- Templates may be used to ensure consistency
- Patterns should be documented and followed consistently

**Atom-Based Sharing:**
- Common values shared through atom resolution system
- Atoms provide consistency without direct inheritance
- Atom updates propagate to all referencing files

---

## 4. Metadata Lifecycle

### 4.1 Creation
**Process for creating metadata for new files:**

**Initial Metadata Creation:**
1. **Determine File Purpose** - Understand what the file will contain
2. **Select Metadata Template** - Choose appropriate template for file type
3. **Populate Required Fields** - Fill in all mandatory metadata fields
4. **Add Optional Fields** - Include relevant optional metadata
5. **Validate Metadata** - Ensure metadata is correct and complete
6. **Create Dialog Entry** - Document file creation in dialog system

**Creation Validation:**
- All required fields must be present
- Field values must be appropriate for file content
- Metadata must be consistent with system standards
- Cross-references must be valid and bidirectional

### 4.2 Modification
**Process for updating metadata when files change:**

**Modification Triggers:**
- File content changes
- Cross-reference updates
- Version changes
- Classification changes
- Dialog system updates

**Modification Process:**
1. **Identify Changes** - Determine what metadata needs updating
2. **Plan Updates** - Identify all affected metadata fields
3. **Update Metadata** - Modify metadata to reflect changes
4. **Update Dialog** - Create new dialog entry for changes
5. **Synchronize Headers** - Ensure WOLFIE header matches dialog
6. **Validate Updates** - Confirm all updates are correct

### 4.3 Validation
**Process for validating metadata correctness:**

**Validation Types:**
- **Syntax Validation** - YAML syntax and structure
- **Content Validation** - Field values and references
- **Consistency Validation** - Cross-file consistency
- **Completeness Validation** - Required fields present

**Validation Process:**
1. **Parse Metadata** - Ensure metadata can be parsed correctly
2. **Check Required Fields** - Verify all required fields are present
3. **Validate Field Values** - Check field values are correct format
4. **Resolve References** - Ensure all references are valid
5. **Check Consistency** - Verify consistency across related files
6. **Report Issues** - Document any validation failures

### 4.4 Synchronization
**Process for maintaining metadata consistency across files:**

**Synchronization Triggers:**
- Version changes across system
- Cross-reference updates
- Dialog system updates
- Classification changes

**Synchronization Process:**
1. **Identify Scope** - Determine which files need synchronization
2. **Plan Updates** - Coordinate updates across affected files
3. **Execute Updates** - Update all files consistently
4. **Validate Synchronization** - Ensure consistency is maintained
5. **Document Synchronization** - Record synchronization in dialog system

### 4.5 Archival
**Process for handling metadata in archived or deprecated files:**

**Archival Triggers:**
- File deprecation or removal
- System evolution requiring file archival
- Legacy system migration

**Archival Process:**
1. **Mark as Archived** - Update metadata to indicate archival status
2. **Preserve History** - Maintain complete metadata history
3. **Update References** - Update cross-references to archived files
4. **Document Archival** - Record archival in dialog system
5. **Maintain Accessibility** - Ensure archived metadata remains accessible

---

## 5. Metadata Enforcement

### 5.1 How Agents Validate Metadata
**Agent responsibilities for metadata validation:**

**Pre-Modification Validation:**
- Agents must read and validate existing metadata before changes
- Agents must understand metadata requirements for their changes
- Agents must plan metadata updates as part of change process
- Agents must validate metadata consistency across related files

**Post-Modification Validation:**
- Agents must validate metadata after making changes
- Agents must ensure all required fields are present and correct
- Agents must verify cross-references are valid and bidirectional
- Agents must confirm dialog synchronization is maintained

**Continuous Validation:**
- Agents should perform periodic metadata validation
- Agents should report metadata inconsistencies when discovered
- Agents should coordinate metadata fixes across affected files

### 5.2 How Metadata Drift Is Detected
**Mechanisms for detecting metadata inconsistencies:**

**Automated Detection:**
- Syntax validation during file processing
- Cross-reference validation during system operations
- Consistency checking during version updates
- Dialog synchronization validation during updates

**Manual Detection:**
- Agent review during file modifications
- Human review during quality assurance processes
- Periodic metadata audits and reviews
- User reports of metadata issues

**Detection Triggers:**
- File modification operations
- System version changes
- Cross-reference updates
- Dialog system operations

### 5.3 How Metadata Violations Are Handled
**Process for addressing metadata violations:**

**Violation Response Process:**
1. **Detect Violation** - Identify specific metadata violation
2. **Classify Severity** - Determine impact and urgency of violation
3. **Block Operations** - Prevent further operations until violation is resolved
4. **Document Violation** - Record violation details and context
5. **Plan Resolution** - Develop plan to fix violation
6. **Execute Fix** - Implement violation resolution
7. **Validate Fix** - Ensure violation is completely resolved
8. **Document Resolution** - Record resolution in dialog system

**Violation Severity Levels:**
- **Critical** - Violations that break system functionality
- **High** - Violations that affect system consistency
- **Medium** - Violations that affect quality or usability
- **Low** - Violations that affect style or formatting

### 5.4 How Metadata Interacts with Patch Discipline
**Integration between metadata governance and patch discipline:**

**Patch Requirements:**
- Every patch must include appropriate metadata updates
- Metadata updates must be included in patch scope
- Metadata validation must pass before patch completion
- Metadata changes must be documented in dialog system

**Patch Coordination:**
- Multi-file patches must coordinate metadata updates
- Cross-reference updates must be included in patch scope
- Version updates must be synchronized across all patch files
- Dialog entries must be created for all metadata changes

---

## 6. Cross-System Metadata

### 6.1 How Metadata Flows Between Directories
**Metadata coordination across directory structure:**

**Directory-Specific Metadata:**
- Each directory may have specific metadata requirements
- Metadata must be appropriate for directory security level
- Metadata must support directory-specific functionality
- Metadata must maintain consistency across directory boundaries

**Cross-Directory Coordination:**
- Cross-references between directories must be maintained
- Version information must be consistent across directories
- Dialog system must track cross-directory changes
- Classification must reflect cross-directory relationships

### 6.2 How Metadata Interacts with Dialog Threads
**Integration between metadata and dialog system:**

**Dialog-Metadata Synchronization:**
- WOLFIE header dialog block must match latest dialog entry
- Dialog entries must be created for all metadata changes
- Dialog threads must maintain metadata change history
- Dialog system must support metadata validation

**Dialog Thread Assignment:**
- Metadata determines appropriate dialog thread for file
- Dialog thread mapping must be consistent with metadata
- Dialog thread changes must be reflected in metadata
- Dialog system must support metadata-driven thread selection

### 6.3 How Metadata Interacts with Versioning
**Integration between metadata and version management:**

**Version-Metadata Synchronization:**
- Version information must be consistent across all metadata
- Version changes must trigger metadata updates
- Metadata must support version evolution and migration
- Version history must be preserved in metadata

**Version-Driven Metadata Updates:**
- Major version changes may require metadata format updates
- Minor version changes may add new metadata capabilities
- Patch version changes must maintain metadata consistency
- Version rollbacks must restore appropriate metadata state

---

## 7. Examples

### 7.1 Correct Metadata Examples

**Example 1: Complete WOLFIE Header**
```yaml
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
  message: "Created comprehensive metadata governance documentation."
tags:
  categories: ["documentation", "doctrine", "metadata"]
  collections: ["core-docs", "governance"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Metadata Governance Rules
  - Metadata Categories and Types
  - Metadata Lifecycle Management
  - Enforcement Procedures
file:
  title: "Metadata Governance Doctrine"
  description: "Comprehensive metadata management and governance rules"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---
```

**Example 2: Correct Dialog Entry**
```markdown
## 2026-01-14 16:45:00 UTC
**Speaker:** KIRO
**Target:** @everyone
**Mood:** 0066FF
**Message:** "Updated metadata governance with new validation rules and enforcement procedures."
```

**Example 3: Correct Cross-Reference Metadata**
```yaml
# In file A referencing file B
- **[Related Documentation](../doctrine/RELATED_DOCTRINE.md)** - Related governance rules

# In file B referencing file A (bidirectional)
- **[Metadata Governance](METADATA_GOVERNANCE.md)** - Comprehensive metadata management rules
```

### 7.2 Incorrect Metadata Examples

**Example 1: Missing Required Fields**
```yaml
❌ INCORRECT:
---
wolfie.headers: explicit architecture with structured clarity for every file.
# Missing file.last_modified_system_version
# Missing header_atoms
# Missing dialog block
tags:
  categories: ["documentation"]
---

✅ CORRECT:
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  speaker: KIRO
  target: @everyone
  mood_RGB: "0066FF"
  message: "Created documentation with complete metadata."
tags:
  categories: ["documentation"]
  collections: ["examples"]
  channels: ["dev"]
in_this_file_we_have:
  - Complete Documentation
file:
  title: "Example Documentation"
  description: "Example with correct metadata"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---
```

**Example 2: Invalid Dialog Format**
```markdown
❌ INCORRECT:
## 2026-01-14
Speaker: KIRO
Message: Updated file

✅ CORRECT:
## 2026-01-14 16:45:00 UTC
**Speaker:** KIRO
**Target:** @everyone
**Mood:** 0066FF
**Message:** "Updated file with proper metadata and documentation."
```

### 7.3 Correct Metadata Update Example

**Before Update:**
```yaml
dialog:
  speaker: CASCADE
  target: @dev
  mood_RGB: "FF6600"
  message: "Fixed legacy compatibility issues."
```

**After KIRO Update:**
```yaml
dialog:
  speaker: KIRO
  target: @everyone
  mood_RGB: "0066FF"
  message: "Enhanced documentation with new metadata governance rules."
```

**Corresponding Dialog Thread Update:**
```markdown
## 2026-01-14 17:30:00 UTC
**Speaker:** KIRO
**Target:** @everyone
**Mood:** 0066FF
**Message:** "Enhanced documentation with new metadata governance rules."

## 2026-01-13 14:20:00 UTC
**Speaker:** CASCADE
**Target:** @dev
**Mood:** FF6600
**Message:** "Fixed legacy compatibility issues."
```

### 7.4 Correct Metadata Validation Example

**Validation Process:**
```
1. SYNTAX VALIDATION:
   ✓ YAML syntax is valid
   ✓ All field names are correct
   ✓ All field values are properly formatted

2. CONTENT VALIDATION:
   ✓ All required fields are present
   ✓ Atom references resolve correctly
   ✓ Cross-references point to existing files
   ✓ Version information is current

3. CONSISTENCY VALIDATION:
   ✓ Dialog block matches latest dialog entry
   ✓ Version is consistent across related files
   ✓ Cross-references are bidirectional
   ✓ Classification matches file content

4. COMPLETENESS VALIDATION:
   ✓ All mandatory metadata is present
   ✓ Content description matches actual content
   ✓ File metadata is accurate and current
   ✓ Tags are appropriate and consistent

RESULT: Metadata validation PASSED
```

---

## 8. Cross-References

- **[WOLFIE Header Doctrine](WOLFIE_HEADER_DOCTRINE.md)** (`docs/doctrine/WOLFIE_HEADER_DOCTRINE.md`) - MANDATORY rules for WOLFIE headers and primary metadata containers
- **[Dialog Doctrine](DIALOG_DOCTRINE.md)** - MANDATORY rules for dialog metadata and change tracking
- **[Agent Runtime](AGENT_RUNTIME.md)** - Agent responsibilities for metadata validation and maintenance
- **[Patch Discipline](PATCH_DISCIPLINE.md)** - Integration between metadata governance and development workflow
- **[Directory Structure](DIRECTORY_STRUCTURE.md)** - Directory-specific metadata requirements and organization
- **[Versioning Doctrine](VERSIONING_DOCTRINE.md)** - Version metadata management and synchronization requirements
- **[Architecture Sync](ARCHITECTURE_SYNC.md)** - Cross-system synchronization and metadata coordination
- **[Agent Prompt Doctrine](AGENT_PROMPT_DOCTRINE.md)** - Agent communication standards and metadata integration

---

**This Metadata Governance Doctrine is MANDATORY and NON-NEGOTIABLE.**

All metadata in Lupopedia must follow these governance requirements exactly. Metadata governance ensures system stability, consistency, and traceability through comprehensive metadata management across all system components.

> **All system metadata must be consistent, accurate, and maintained.**  
> **Metadata governance enables machine processing and human understanding.**  
> **Metadata provides the foundation for system stability and evolution.**  
> **Proper metadata governance prevents system drift and inconsistencies.**

This is architectural doctrine.

---