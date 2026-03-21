---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/versions/4.0.84/PLAN.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.84/plan"
  last_modified_utc: "20260320"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "plan"
  artifact_kind: "version_plan"
  title: "Version 4.0.84 Plan"
  purpose: "Execution plan for Lupopedia version 4.0.84 development and release"
  tags: ["version", "4.0.84", "plan", "development", "release"]
lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  session_name: "L-LUPO-ROOT-CURSOR"
  actor_id: 102
  actor_name: "cursor"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  federation_node_id: 1
  context_source: "ide_runtime"
  department_id: 0
  thread_id: 1001
  agent_name: "cursor"
  actor_type: "agent"
  actor_nature: "ide"
  human_actor_name: "root"
  paired_actor_id: 10000
lupopedia.edges:
  comment: "Snapshot of outbound edges for 4.0.84 plan."
  meta: "Version 4.0.84 development plan; execution roadmap; release coordination."
  outbound_edges:
    - { to: "TODO.md", type: "references", weight: 1.0, reason: "Task registry for 4.0.84" }
    - { to: "CHANGELOG.md", type: "references", weight: 1.0, reason: "Version history and changes" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 0.95, reason: "Header format and versioning model" }
    - { to: "lupo-docs/doctrine/VERSIONING_DOCTRINE.md", type: "references", weight: 0.95, reason: "Versioning policy and doctrine" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Execute remaining 4.0.84 development tasks"
    - "Complete semantic validation fixes for Thread 1004"
    - "Monitor Channel 66 production deployment safety"
---

# file: Version 4.0.84 Plan — session: L-LUPO-ROOT-CURSOR — delegation: wolfie:root — web_path: http://www.lupopedia.com/docs/versions/4.0.84/plan

# Version 4.0.84 Plan

**Status:** 🔄 Active Development  
**Started:** 2026-03-20  
**Focus:** LUPOPEDIA_HEADERS doctrine cleanup and system stabilization

## Executive Summary

Version 4.0.84 focuses on completing the LUPOPEDIA_HEADERS doctrine cleanup and enforcing the single-field versioning model across all documentation. This version represents a significant architectural cleanup that eliminates deprecated version fields and establishes a consistent, maintainable versioning system.

## Key Objectives

### ✅ Completed Objectives

1. **LUPOPEDIA_HEADERS Doctrine Cleanup**
   - Removed all deprecated version fields (`lupopedia.version`, `system_version`, `last_verified_system_version`)
   - Enforced single-field versioning model using only `version_when_written`
   - Updated all documentation to reflect current doctrine

2. **Documentation Standardization**
   - Updated LUPOPEDIA_HEADERS_FORMAT.md with baseline rewrite rules
   - Converted VERSIONING_MODEL.md to obsolete stub
   - Added LILITH edge case analysis to README.md
   - Created WOLFIE script for TOON-based header generation

3. **Version Management Enhancement**
   - Updated VERSIONING_DOCTRINE.md with 4.0.84 single-field model
   - Established clear baseline rewrite requirements
   - Documented version resolution and dynamic version handling

4. **Canonical Schema Refactoring (Threads 1031 / 1032)**
   - Re-secured install schema via strict canonical governance standards
   - Flushed out illegally appended DDL payload blocks
   - Natively integrated Phase 1 Visibility extensions into structural payloads
   - Defined `lupo_actor_projects` and established `project_id` bindings globally

### 🔄 In Progress Objectives

1. **Semantic Validation Fixes (Thread 1004)**
   - Fix `lupo_visits.actor_id` mapping issues
   - Resolve validation blockers in channels 66 and 88
   - Complete HEPHAESTUS implementation coordination

2. **Production Deployment Safety**
   - Monitor Channel 66 production deployment
   - Ensure semantic validation doesn't break production systems
   - Coordinate with HEPHAESTUS implementation teams

## Implementation Areas

### Database Changes
**Location:** `database_changes/`
- Integrated Phase 1 Visibility configuration parameters natively into `lupo_channels`, `lupo_tasks`, `lupo_dialog_threads`.
- Bootstrapped Canonical Project tracking natively with `lupo_actor_projects` and `project_id`.
- Developed explicit `dev_20260321_project_model_and_schema_authority.sql` Dev Migration script for DDL deployment tracking.

### Organization Changes  
**Location:** `organization_changes/`
- LUPOPEDIA_HEADERS doctrine restructuring
- File organization and naming conventions
- Documentation hierarchy updates

### Class Changes
**Location:** `class_changes/`
- Header generation and validation classes
- Version resolution and management classes
- TOON-based schema integration

### Doctrine Changes
**Location:** `doctrine_changes/`
- Single-field versioning model enforcement
- Baseline rewrite rules and requirements
- Header format standardization

### Script Changes
**Location:** `script_changes/`
- `generate_headers_from_db.py` implementation
- `import_content.py` hardening
- Version management utilities

## Dependencies

### Internal Dependencies
- Thread 1004 semantic validation completion
- Channel 66 production deployment coordination
- HEPHAESTUS implementation plan execution

### External Dependencies
- None for 4.0.84 (internal cleanup focus)

## Risk Assessment

### Low Risk
- Documentation updates (no functional changes)
- Header format standardization
- Version field cleanup

### Medium Risk
- Semantic validation fixes (potential production impact)
- Baseline rewrite enforcement (file modification)

### High Risk
- None identified for 4.0.84

## Success Criteria

1. ✅ All documentation uses single-field versioning model
2. ✅ Deprecated version fields removed from codebase
3. 🔄 Semantic validation fixes deployed and tested
4. 🔄 Channel 66 production deployment stable
5. 📋 All TODO items completed

## Release Notes

### Major Changes
- Enforced single-field versioning model (`version_when_written` only)
- Removed deprecated version fields from all documentation
- Added baseline rewrite requirements for pre-4.0.84 files
- Enhanced header generation and validation tooling

### Breaking Changes
- Files with deprecated version headers will trigger baseline rewrite on edit
- Legacy version field references are no longer supported

### Upgrade Path
- Standard Crafty Syntax 3.7.5 → Lupopedia 4.0.x upgrade path
- No additional database changes required
- Documentation will be automatically updated on edit

---

*Last updated: 2026-03-20*
