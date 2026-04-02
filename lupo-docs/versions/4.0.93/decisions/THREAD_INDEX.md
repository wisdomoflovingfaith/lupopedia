---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260402210000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/THREAD_INDEX.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/THREAD_INDEX.md"
  last_modified_utc: "20260402210000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.93-decisions"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Architecture and design decisions for Lupopedia 4.0.93"
  tags:
  - "decisions"
  - "adr"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260402210000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
  next_action:
    - "Resolve MAAT/HEIMDALL actor_id 6 conflict in registry"
    - "Complete remaining primary coordination personas (SESHAT, HEIMDALL, JANUS, THEMIS, MAAT, CHIRON, VISHWAKARMA)"
    - "Complete remaining PRD namespaces (7 remaining)"
    - "Implement automated TOON generation pipeline"
    - "Consolidate all decisions into this file moving forward"
    - "Optional: wire main channels cockpit UI to same api/lupo-channels patterns where useful"
    - "Integrate GarbageCollector class into image.php or lupo_ajax.php for random execution"
    - "When adding new files under lupo-docs/prd/, include lupopedia.edges anchor to 00_root_constitutional_system_requirements.md"
---

# Lupopedia 4.0.93 - Decisions & Action Items

## Decision Threads

| ID | Type | Title | Author | Status | Date | Thread |
|----|------|-------|--------|--------|------|--------|
| D-56 | Audit | Universal Header Validator - Final Approval | LILITH | Accepted | 2026-04-02 | [D-56-universal-validator-audit](D-56_universal_validator_audit.md) |
| D-57 | Audit | PRD 16 - Artifact Type and Kind Definitions | LILITH | Approved with Corrections | 2026-04-02 | [D-57-prd16-taxonomy-audit](D-57_prd16_taxonomy_audit.md) |
| D-58 | Implementation | Universal Validator Update: Author Attribution | CASCADE | Completed | 2026-04-02 | [D-58-author-attribution](D-58_author_attribution.md) |
| D-59 | Implementation | Universal Validator Update: Conditional Field Requirements | CASCADE | Completed | 2026-04-02 | [D-59-conditional-fields](D-59_conditional_fields.md) |
| D-60 | Implementation | PRD 16 Enhancement: Artifact Type and Kind Taxonomy | CURSOR | Completed | 2026-04-02 | [D-60-prd16-enhancement](D-60_prd16_enhancement.md) |
| D-61 | Implementation | LUPOPEDIA_HEADERS Documentation Update | CURSOR | Completed | 2026-04-02 | [D-61-documentation-update](D-61_documentation_update.md) |
| D-62 | Directive | Update 4.0.93 Version Documentation (Current Thread) | CURSOR | Completed | 2026-04-02 | [D-62-version-docs](D-62_version_docs.md) |
| D-63 | Dialog | PRD Development Guide — Naming Convention Correction | LILITH | Completed | 2026-04-02 | [20260402_165500_DIALOG_prd_naming_correction](20260402_165500_DIALOG_prd_naming_correction.md) |
| D-64 | Dialog | PRD Development Guide — Structure Correction | LILITH | Completed | 2026-04-02 | [20260402_180000_DIALOG_prd_guide_structure_correction](20260402_180000_DIALOG_prd_guide_structure_correction.md) |
| D-65 | Dialog | PRD Development Guide — Final Clarification of Decision Contexts | LILITH | Completed | 2026-04-02 | [20260402_190000_DIALOG_prd_guide_final_clarification](20260402_190000_DIALOG_prd_guide_final_clarification.md) |
| D-66 | Decision | Context System Implementation for Decision Documentation | CURSOR | Completed | 2026-04-02 | [20260402_200000_DECISION_context_system_implementation](20260402_200000_DECISION_context_system_implementation.md) |
| D-67 | Decision | PRD 31 Rejection - Parallel Classification System Conflict | LILITH | Completed | 2026-04-02 | [20260402_210000_DECISION_prd31_rejection_parallel_classification](20260402_210000_DECISION_prd31_rejection_parallel_classification.md) |

## Recent Activity

# Major Architectural Decisions (2026-04-02)

| Timestamp        | File Name                                         | Type      | Summary                                 |
|------------------|--------------------------------------------------|-----------|-----------------------------------------|
| 20260402_120000  | 20260402_120000_DECISION_channel_directory_structure.md | DECISION  | Channel directory restructure           |
| 20260402_130000  | 20260402_130000_DECISION_decisions_folder_separation.md | DECISION  | Decisions folder separation             |
| 20260402_140000  | 20260402_140000_DECISION_edge_based_qa_linking.md      | DECISION  | Edge-based Q&A linking                  |
| 20260402_150000  | 20260402_150000_DECISION_prd26_approval.md             | DECISION  | PRD 26 approval                         |
| 20260402_160000  | 20260402_160000_DECISION_prd30_rejection.md            | DECISION  | PRD 30 rejection                        |
| 20260402_170000  | 20260402_170000_DECISION_prd31_rejection.md            | DECISION  | PRD 31 rejection                        |

### 2026-04-02
- **LILITH Audits**: Universal Header Validator (96% accuracy) and PRD 16 (88% accuracy)
- **CASCADE Implementation**: Updated universal validator with author field support and conditional requirements
- **CURSOR Implementation**: Enhanced PRD 16 with comprehensive taxonomy and updated LUPOPEDIA_HEADERS documentation
- **Documentation Update**: Completed version documentation updates for 4.0.93
- **PRD 30 Development**: Multiple corrections to decision documentation guidance
- **Context System**: Created and then rejected PRD 31 (parallel classification conflict)
- **Database Cleanup**: Removed contexts and contexts_map tables, hotfix_registry table

## Legacy Decisions (Pre-Thread Structure)

The following decisions were documented before the thread structure was implemented and have been migrated to individual thread files following the YYYYMMDD_HHIISS_TYPE_STATUS_TITLE naming convention:

| Date Range | Files | Description |
|-------------|--------|-------------|
| 2026-03-30 to 2026-03-31 | 55 files | Early architecture and implementation decisions |
| 2026-04-01 | 49 files | WOLFIE Doctrine, multi-agent orchestration, and major PRD alignments |

All legacy decision files are now individual threads in this directory, following the proper naming convention. The original consolidated file is preserved as `old_decisions.md` for reference.

### Key Legacy Decisions by Category

**Architecture & Doctrine (D-01 to D-05, D-28 to D-32)**
- Canonical Header Versioning (D-01)
- Department-Scoped Actor Model (D-02)
- Temporal System and UTC Authority (D-03)
- Agent/Actor Verification Attribution (D-04)
- Versioned Documentation Structure (D-05)
- WOLFIE Doctrine (D-28)
- Multi-Agent Orchestration Doctrine (D-29)
- Actor-Agent Distinction Doctrine (D-30)
- Database Doctrine (D-31)
- Garbage Collection System (D-32)

**Agent Enhancements (D-11 to D-14)**
- LEXA Security Enforcement (D-11)
- ATHENA Wisdom & Strategy (D-12)
- THOTH Knowledge & Records (D-13)
- ANUBIS Custodian Enhancement (D-14)

**Implementation & Infrastructure (D-06 to D-10, D-33 to D-37)**
- Consolidated Seed File (D-06)
- Dynamic Table Prefix Migration (D-07)
- File-Based Agent Doctrine (D-08)
- Subdirectory Installation Doctrine (D-09)
- JSON Schema Management Workflow (D-10)
- PRD Improvements (D-33, D-36)
- TOON Generator Updates (D-34)
- CSV Export Tool (D-35)
- Missing Table Protocol (D-36)
- Proven Code Preservation Doctrine (D-37)

**Documentation & Process (D-15 to D-27, D-38 to D-42, D-53 to D-55)**
- LILITH Audits (D-17 to D-24)
- Channel Chat Implementation (D-26)
- README & Documentation Updates (D-38, D-39)
- Project Structure (D-42)
- Constitutional Alignment (D-53)
- PRD Anchor Edges (D-54)
- Version Documentation Updates (D-25, D-27, D-55)

**Root Sanitization (D-43 to D-52)**
- Prompt Migration to Actor Workspaces (D-43)
- Root Directory Sanitization (D-44)
- Federation Intake Doctrine (D-45)
- Thread Graduation Doctrine (D-46)
- Automated Rules Compilation (D-47)
- Legacy Thread Archival Framework (D-48)
- lupo-includes Defoliation (D-49)
- Class Consolidation Protocol (D-50)
- LILITH Notepad Justification (D-51)
- Notepad++ Bulk Refactor Side-Effects (D-52)
