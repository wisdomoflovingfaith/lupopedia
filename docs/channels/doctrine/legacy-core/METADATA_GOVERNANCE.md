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
  message: "Created METADATA_GOVERNANCE.md as core documentation for Phase 2. Defines canonical metadata management, WOLFIE header governance, atom resolution, and metadata consistency rules for Lupopedia Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE)."
tags:
  categories: ["documentation", "core", "metadata", "governance"]
  collections: ["core-docs", "architecture"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Metadata Governance Principles
  - WOLFIE Header Management
  - Atom Resolution Governance
  - Cross-Reference Mesh Maintenance
  - Metadata Consistency Rules
  - Dialog Metadata Management
  - Version Metadata Control
  - Tag and Category Governance
  - File Metadata Standards
  - Enforcement Mechanisms
file:
  title: "Metadata Governance Doctrine"
  description: "Canonical metadata management and governance rules for Lupopedia Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE)"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# ðŸ›ï¸ Metadata Governance Doctrine

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** MANDATORY (NON-NEGOTIABLE)  
**Effective Date:** 2026-01-14

## Overview

This document defines the canonical metadata governance system for Lupopedia Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE). Metadata governance ensures consistency, accuracy, and maintainability across all documentation, code, and system files.

**Critical Principle:** Metadata is the nervous system of the semantic OS. Governance ensures the nervous system remains healthy and functional.

---

## 1. Metadata Governance Principles

### 1.1 Single Source of Truth
- **Global atoms** (`config/global_atoms.yaml`) are the authoritative source for system-wide values
- **Local atoms** (file-specific) override global atoms only when necessary
- **No hardcoded values** in documentation or code where atoms can be used
- **Atom resolution** follows strict precedence rules

### 1.2 Consistency Enforcement
- All files must use **WOLFIE headers** with proper metadata
- **Version consistency** across all files in a release
- **Cross-reference integrity** maintained through governance
- **Dialog metadata** must reflect actual file changes

### 1.3 Machine-Readable First
- Metadata designed for **machine processing** first, human reading second
- **Structured data** over free-form text
- **Standardized formats** (YAML, JSON) for metadata
- **Validation rules** enforced programmatically

---

## 2. WOLFIE Header Governance

### 2.1 Mandatory Header Elements
Every file MUST contain:
```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: [VERSION]
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: [AGENT_NAME]
  target: [TARGET]
  mood_RGB: "[RRGGBB]"
  message: "[CHANGE_DESCRIPTION]"
---
```

### 2.2 Header Validation Rules
- **wolfie.headers** signature must be exact
- **file.last_modified_system_version** must match current version
- **header_atoms** must reference valid atoms
- **dialog** block must reflect latest change
- **No missing required fields**

### 2.3 Header Update Protocol
When modifying a file:
1. **Update dialog block** with new speaker, message, mood
2. **Update file.last_modified_system_version** to current version
3. **Verify atom references** are still valid
4. **Maintain cross-reference links** if applicable
5. **Log change** in appropriate dialog file

---

## 3. Atom Resolution Governance

### 3.1 Atom Precedence Rules
Resolution order (first match wins):
1. **FILE_** scope (file-specific atoms)
2. **DIR_** scope (directory-specific atoms)
3. **DIRR_** scope (recursive directory atoms)
4. **MODULE_** scope (module-specific atoms)
5. **GLOBAL_** scope (system-wide atoms)

### 3.2 Atom Naming Standards
- **GLOBAL_CURRENT_LUPOPEDIA_VERSION** - Current system version
- **GLOBAL_CURRENT_AUTHORS** - Current author list
- **GLOBAL_LUPOPEDIA_DESCRIPTION** - System description
- **MODULE_[NAME]_VERSION** - Module-specific versions
- **FILE_[NAME]_SPECIFIC** - File-specific values

### 3.3 Atom Validation
- All atom references must resolve to valid values
- **No broken atom references** allowed
- **Circular references** are forbidden
- **Atom definitions** must be well-formed YAML

### 3.4 Atom Lifecycle Management
- **Creation:** New atoms added to appropriate scope file
- **Modification:** Changes propagated to all references
- **Deprecation:** Gradual replacement with migration period
- **Removal:** Only after all references updated

---

## 4. Cross-Reference Mesh Governance

### 4.1 Cross-Reference Standards
- All documentation files must maintain **bidirectional links**
- **Related Documentation** sections required
- **Link validation** performed regularly
- **Orphaned files** must be connected or archived

### 4.2 Cross-Reference Patterns
```markdown
## Related Documentation

- **[Document Title](../path/DOCUMENT.md)** - Brief description
- **[Another Document](../other/DOCUMENT.md)** - Brief description
```

### 4.3 Cross-Reference Maintenance
- **Regular audits** of link integrity
- **Automated validation** where possible
- **Manual verification** for complex relationships
- **Update cascades** when files are moved or renamed

---

## 5. Dialog Metadata Management

### 5.1 Dialog Block Requirements
Every file change must update the dialog block:
```yaml
dialog:
  speaker: [AGENT_NAME]        # Who made the change
  target: [TARGET_AUDIENCE]    # Who needs to know
  mood_RGB: "[RRGGBB]"        # Emotional context
  message: "[DESCRIPTION]"     # What was changed (max 272 chars)
```

### 5.2 Dialog Consistency Rules
- **Speaker** must be valid agent or human identifier
- **Target** must follow standard patterns (@everyone, @dev, @specific_agent)
- **mood_RGB** must be valid 6-character hex color
- **Message** must accurately describe the change made

### 5.3 Dialog History Tracking

**All dialog history files stored in /dialogs/ represent channel-level collaboration logs, not thread logs. Threads are database entities and do not produce filesystem dialog files.**

- All dialog changes logged in appropriate `/dialogs/` files
- **Newest entries at top** of dialog files
- **Complete change history** preserved
- **Dialog file headers** reflect latest entry

---

## 6. Version Metadata Control

### 6.1 Version Consistency
- All files in a release must use **same version number**
- **file.last_modified_system_version** updated with every change
- **Version atoms** maintained in `config/global_atoms.yaml`
- **No version drift** between related files

### 6.2 Version Update Protocol
When bumping version:
1. **Update global version atom** in `config/global_atoms.yaml`
2. **Update version constants** in `lupo-includes/version.php`
3. **Update CHANGELOG.md** with version entry
4. **Cascade version updates** to all modified files
5. **Verify version consistency** across system

### 6.3 Version Validation
- **Automated checks** for version consistency
- **Manual verification** during release process
- **Version mismatch detection** and correction
- **Historical version tracking** in changelog

---

## 7. Tag and Category Governance

### 7.1 Tag Standards
```yaml
tags:
  categories: ["documentation", "core", "metadata"]
  collections: ["core-docs", "architecture"]
  channels: ["dev", "public"]
```

### 7.2 Category Taxonomy
**Standardized categories:**
- **documentation** - Documentation files
- **core** - Core system files
- **doctrine** - Architectural doctrine files
- **agents** - Agent-related files
- **modules** - Module-specific files
- **schema** - Database-related files
- **architecture** - System architecture files

### 7.3 Collection Management
**Standard collections:**
- **core-docs** - Essential documentation
- **doctrine** - Architectural doctrines
- **agent-docs** - Agent documentation
- **module-docs** - Module documentation
- **dev-docs** - Developer documentation

### 7.4 Channel Classification
**Standard channels:**
- **public** - Public-facing documentation
- **dev** - Developer-specific content
- **internal** - Internal system documentation
- **admin** - Administrative content

---

## 8. File Metadata Standards

### 8.1 File Information Block
```yaml
file:
  title: "Document Title"
  description: "Brief description of file purpose"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
```

### 8.2 Content Description Block
```yaml
in_this_file_we_have:
  - Major Topic 1
  - Major Topic 2
  - Major Topic 3
  - Important Concept
  - Key Implementation Detail
```

### 8.3 Section Navigation Block
```yaml
sections:
  - title: "Section Name"
    anchor: "#section-anchor"
  - title: "Another Section"
    anchor: "#another-section"
```

---

## 9. Metadata Validation Rules

### 9.1 Structural Validation
- **YAML syntax** must be valid
- **Required fields** must be present
- **Field types** must match specifications
- **No malformed metadata** allowed

### 9.2 Content Validation
- **Atom references** must resolve
- **Cross-references** must be valid
- **Version numbers** must be consistent
- **Dialog messages** must be under 272 characters

### 9.3 Semantic Validation
- **Categories** must be from approved taxonomy
- **Collections** must be defined
- **Channels** must be valid
- **File relationships** must make sense

---

## 10. Enforcement Mechanisms

### 10.1 Automated Validation
- **Pre-commit hooks** (when Git is available)
- **Build-time validation** during releases
- **Continuous validation** of metadata integrity
- **Automated correction** where possible

### 10.2 Manual Review Process
- **Peer review** of metadata changes
- **Documentation review** for consistency
- **Cross-reference audits** periodically
- **Quality assurance** checks

### 10.3 Error Handling
- **Validation errors** must be fixed before merge
- **Warning notifications** for potential issues
- **Graceful degradation** when metadata is incomplete
- **Recovery procedures** for corrupted metadata

---

## 11. Metadata Migration

### 11.1 Schema Evolution
- **Backward compatibility** maintained where possible
- **Migration scripts** for breaking changes
- **Deprecation warnings** for old formats
- **Gradual transition** periods

### 11.2 Data Migration
- **Automated migration** of metadata formats
- **Manual verification** of complex migrations
- **Rollback procedures** for failed migrations
- **Data integrity** checks post-migration

---

## 12. Special Metadata Cases

### 12.1 Agent Files
Agent files require additional metadata:
```yaml
agent:
  id: [AGENT_ID]
  name: "[AGENT_NAME]"
  classification: "[CLASSIFICATION]"
  layer: "[LAYER]"
```

### 12.2 Module Files
Module files require module-specific metadata:
```yaml
module:
  name: "[MODULE_NAME]"
  version: "[MODULE_VERSION]"
  dependencies: ["dep1", "dep2"]
```

### 12.3 Database Files
Database files require schema metadata:
```yaml
database:
  schema_version: "[VERSION]"
  tables_affected: ["table1", "table2"]
  migration_type: "[TYPE]"
```

---

## 13. Metadata Security

### 13.1 Sensitive Information
- **No credentials** in metadata
- **No API keys** in headers
- **No personal information** in public metadata
- **Sanitized descriptions** only

### 13.2 Access Control
- **Public metadata** for public files
- **Internal metadata** for internal files
- **Admin metadata** for administrative files
- **Proper classification** of sensitivity levels

---

## 14. Performance Considerations

### 14.1 Metadata Size
- **Keep metadata concise** but complete
- **Avoid redundant information**
- **Use atom references** instead of duplication
- **Optimize for parsing speed**

### 14.2 Caching Strategy
- **Cache resolved atoms** for performance
- **Invalidate cache** on atom changes
- **Lazy loading** of metadata where appropriate
- **Efficient storage** of metadata

---

## 15. Integration with Other Systems

### 15.1 Documentation System
- **Metadata drives** documentation generation
- **Cross-reference resolution** uses metadata
- **Search indexing** based on metadata
- **Navigation generation** from metadata

### 15.2 Agent System
- **Agents read** metadata for context
- **Dialog system** updates metadata
- **Agent classification** stored in metadata
- **Routing decisions** based on metadata

### 15.3 Build System
- **Build process** validates metadata
- **Release generation** uses metadata
- **Version management** controlled by metadata
- **Deployment decisions** based on metadata

---

## 16. Governance Roles and Responsibilities

### 16.1 Metadata Stewards
- **Maintain** global atom definitions
- **Validate** metadata consistency
- **Enforce** governance rules
- **Resolve** metadata conflicts

### 16.2 Content Authors
- **Follow** metadata standards
- **Update** metadata with changes
- **Maintain** cross-references
- **Report** metadata issues

### 16.3 System Administrators
- **Monitor** metadata health
- **Perform** validation checks
- **Execute** migration procedures
- **Maintain** governance tools

---

## 17. Metadata Evolution

### 17.1 Standards Evolution
- **Gradual improvement** of metadata standards
- **Community input** on governance rules
- **Regular review** of effectiveness
- **Adaptation** to new requirements

### 17.2 Tool Evolution
- **Improved validation** tools
- **Better automation** of governance
- **Enhanced reporting** capabilities
- **Integration** with development workflow

---

## 18. Related Documentation

- **[WOLFIE Header Specification](../../agents/WOLFIE_HEADER_SPECIFICATION.md)** - Complete header format specification
- **[Atom Resolution Specification](../ATOM_RESOLUTION_SPECIFICATION.md)** - Atom resolution engine documentation
- **[Dialog Doctrine](../DIALOG_DOCTRINE.md)** - Dialog metadata requirements
- **[Documentation Doctrine](../DOCUMENTATION_DOCTRINE.md)** - Documentation metadata standards
- **[Single Task Patch Doctrine](../SINGLE_TASK_PATCH_DOCTRINE.md)** - Change tracking requirements
- **[Versioning Doctrine](../VERSIONING_DOCTRINE.md)** - Version metadata management
- **[Directory Structure](DIRECTORY_STRUCTURE.md)** - File organization and structural requirements
- **[Patch Discipline](PATCH_DISCIPLINE.md)** - Development workflow governance
- **[Architecture Sync](../../architecture/ARCHITECTURE_SYNC.md)** - System architecture and metadata integration
- **[Inline Dialog Specification](../../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md)** - Multi-agent communication format

---

**This metadata governance is MANDATORY and NON-NEGOTIABLE.**

All AI agents, developers, and content authors must follow these governance rules exactly. Metadata is the foundation of the semantic OS and must be maintained with precision and consistency.

> **Metadata is the nervous system of the semantic OS.**  
> **Governance ensures the nervous system remains healthy.**  
> **Consistency enables machine processing and automation.**

This is architectural doctrine.

---