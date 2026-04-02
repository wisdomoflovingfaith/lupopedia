---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260402220000"
  file_path_from_root: "lupo-docs/versions/4.0.93/edges.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/edges.md"
  last_modified_utc: "20260402220000"
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
    - to: "/lupo-docs/prd/30_prd_development_guide.md"
      type: references
      weight: 1.0
      reason: "PRD writing methodology and 5W1H framework"
    - to: "/lupo-docs/doctrine/DOCUMENTATION_ARCHITECTURE.md"
      type: references
      weight: 1.0
      reason: "Complete 5W1H documentation framework"
lupopedia.footer:
  last_verified: "20260402220000"
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

### Core PRDs Referenced
- **PRD 16**: `lupo-docs/prd/16_lupopedia_headers.md`
  - Header structure and author field requirements
  - Author/verifier distinction
  - Conditional field requirements

- **PRD 26**: `lupo-docs/prd/26_five_layer_documentation_architecture.md`
  - Five-layer architecture (WHAT, HOW, WHY, WHO, WHERE)
  - Implementation structure requirements
  - Validation framework

- **PRD 30**: `lupo-docs/prd/30_prd_development_guide.md`
  - PRD writing methodology
  - 5W1H thinking pattern
  - Decision documentation guidance

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

### Within Version
- `PLAN.md` → Tracks implementation status
- `TODO.md` → Task backlog
- `CHANGELOG.md` → Change history
- `decisions/` → Decision records

### To Other Versions
- Previous: `4.0.92` → Base version
- Next: `4.0.94` → Future development

---

## Edge Categories

| Type | Count | Purpose |
|------|-------|---------|
| PRD References | 3 | Core requirements |
| Doctrine | 1 | Architectural guidance |
| Decisions | 1 | Version-specific choices |
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
