---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260402180000"
  file_path_from_root: "lupo-docs/versions/4.0.93/edges.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/edges.md"
  last_modified_utc: "20260402180000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.93-edges"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "edges"
  purpose: "Relationships between version 4.0.93 documentation components"
  tags:
  - "edges"
  - "relationships"
  - "version-4.0.93"
lupopedia.edges:
  outbound_edges:
    - to: "/lupo-docs/prd/16_lupopedia_headers.md"
      type: references
      weight: 1.0
      reason: "Header structure and author field requirements"
    - to: "/lupo-docs/prd/26_five_layer_documentation_architecture.md"
      type: references
      weight: 1.0
      reason: "Five-layer architecture for all documentation"
    - to: "/lupo-docs/versions/4.0.94/prd/30_prd_development_guide.md"
      type: references
      weight: 0.85
      reason: "PRD 30 working copy (4.0.94); rejected pending rewrite — not frozen with 4.0.93 core PRDs"
    - to: "/lupo-docs/versions/4.0.94/prd/31_context_system.md"
      type: references
      weight: 0.85
      reason: "PRD 31 redesign workspace (4.0.94); rejected parallel classification in 4.0.93"
    - to: "/lupo-docs/doctrine/DOCUMENTATION_ARCHITECTURE.md"
      type: references
      weight: 1.0
      reason: "Complete 5W1H documentation framework"
lupopedia.footer:
  last_verified: "20260402180000"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    name: "CURSOR"
    department_id_delta: 0
  verified_via:
    type: "direct"
    faucet_slug: "none"
  orchestrator: "cursor:root"
---

# Version 4.0.93 Documentation Edges

## Documentation Edges

### Core PRDs frozen with 4.0.93 (`status: "approved"` in headers)
- **PRD 00**: `lupo-docs/prd/00_root_constitutional_system_requirements.md`
- **PRD 16**: `lupo-docs/prd/16_lupopedia_headers.md` — headers, author/verifier, conditional fields
- **PRD 17**: `lupo-docs/prd/17_decisions_format.md` — decisions folder thread format
- **PRD 26**: `lupo-docs/prd/26_five_layer_documentation_architecture.md` — five-layer architecture, validation framework
- **PRD 27**: `lupo-docs/prd/27_installer_requirements.md`
- **PRD 28**: `lupo-docs/prd/28_semantic_monitoring_widget.md`
- **PRD 29**: `lupo-docs/prd/29_project_structure.md`

### Working PRDs (4.0.94 only — not part of 4.0.93 freeze)
- **PRD 30**: `lupo-docs/versions/4.0.94/prd/30_prd_development_guide.md` — rewrite as writing guide (`status: rejected` until rework)
- **PRD 31**: `lupo-docs/versions/4.0.94/prd/31_context_system.md` — redesign (`status: draft`)

### Doctrine Documents
- **DOCUMENTATION_ARCHITECTURE**: `lupo-docs/doctrine/DOCUMENTATION_ARCHITECTURE.md`
  - Complete 5W1H framework explanation
  - Universal application across all doc types
  - Headers, edges, content, threads relationship

## Decision Edges

### Key Decisions in This Version
- **Context System Rejection**: `decisions/20260402_220000_DECISION_context_system_rejection.md`
  - Rejects PRD 31 parallel classification system
  - Maintains architectural simplicity
  - Preserves PRD 26 authority
- **PRD 26 Final Corrections**: `decisions/20260402_230000_DECISION_prd26_final_corrections.md`
  - Fixes constitutional violations identified by COUNTERMEASURE
  - Implements deterministic ID generation
  - Establishes numeric-only identifiers

## Code Edges

### Validators Updated
- **Universal Validator**: `lupo-scripts/validate_lupopedia_headers_universal.py`
  - Added author field support
  - Conditional field requirements
  - Legacy format deprecation warnings

- **Implementation Validator**: `lupo-scripts/validate_implementation.py`
  - Enhanced with 5W1H validation
  - PRD 26 compliance checks
  - Author structure validation

## Database Edges

### Schema Changes
- **install_new_lupopedia.sql**: Database installation script
  - Removed `contexts` table
  - Removed `contexts_map` table
  - Removed `hotfix_registry` table
  - Preserved main `edges` table

## External Edges

### None
No external dependencies for this version - all work was internal documentation and schema cleanup.

## Cross-References

# Documentation Edges (2026-04-02)
- PRD: 00_root_constitutional_system_requirements.md
- PRD: 16_lupopedia_headers.md
- PRD: 17_decisions_format.md
- PRD: 26_five_layer_documentation_architecture.md
- PRD: 27_installer_requirements.md
- PRD: 28_semantic_monitoring_widget.md
- PRD: 29_project_structure.md
- PRD (4.0.94): versions/4.0.94/prd/30_prd_development_guide.md
- PRD (4.0.94): versions/4.0.94/prd/31_context_system.md
- DECISION: decisions/20260402_120000_DECISION_channel_directory_structure.md
- DECISION: decisions/20260402_130000_DECISION_decisions_folder_separation.md
- DECISION: decisions/20260402_140000_DECISION_edge_based_qa_linking.md

# Code Edges
- VALIDATOR: lupo-scripts/validate_implementation.py
- VALIDATOR: lupo-scripts/validate_lupopedia_headers_universal.py
- SCRIPT: lupo-scripts/bootstrap_thread_manifests.py
- SCRIPT: lupo-scripts/archive_stale_threads.py

# Channel Edges
- CHANNEL: lupo-channels/0/development/
- CHANNEL: lupo-channels/0/security/
- CHANNEL: lupo-channels/0/governance/
### Within Version
- `README.md` → Freeze notes and thread file naming convention
- `PLAN.md` → Tracks implementation status
- `TODO.md` → Task backlog
- `CHANGELOG.md` → Change history
- `edges.md` → This file
- `decisions/` → Formal decisions, dialogs, directives
- `questions/` → Open questions (`THREAD_INDEX.md` + timestamped files)
- `answers/` → Answers linked to questions
- `comments/` → Brief notes

### To Other Versions
- Previous: `4.0.92` → Base version
- Next: `4.0.94` → Future development

---

## Edge Categories

| Type | Count | Purpose |
|------|-------|---------|
| PRD References | 8+ | Core frozen PRDs + 4.0.94 working PRDs |
| Doctrine | 1 | Architectural guidance |
| Decisions | 2+ | Version-specific choices |
| Code | 2 | Implementation tools |
| Database | 1 | Schema definition |
| External | 0 | No dependencies |

---

## Maintenance Notes

### When Adding New Edges
1. Update this file with new relationships
2. Ensure bidirectional references where appropriate
3. Update weight values based on importance
4. Add clear reason for each edge

### Edge Weight Guidelines
- **1.0**: Critical/Required
- **0.9**: Important/Strongly recommended
- **0.8**: Helpful/Good practice
- **0.7**: Optional/Nice to have
- **0.6**: Reference/Informational
