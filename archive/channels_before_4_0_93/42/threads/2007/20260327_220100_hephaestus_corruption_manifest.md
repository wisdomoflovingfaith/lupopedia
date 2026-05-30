---
lupopedia.headers:
  lupopedia.schema: "documentation"
  file_path_from_root: "channels/42/threads/2007/20260327_220100_hephaestus_corruption_manifest.md"
  web_path: "http://www.lupopedia.com/lupopedia/channels/42/threads/2007/CORRUPTION_MANIFEST.md"
  questions_toon: null
  channel_id: 42
  actor_id: 23
  actor_name: "HEPHAESTUS"
  faucet_name: "cursor"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "report"
  purpose: "Manifest of corrupted table documentation files requiring regeneration"
  tags: ["documentation", "corruption", "manifest", "4.1.0", "hephaestus"]

lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/2007/20260327_225000_hephaestus_phase1_completion_report.md", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260327"
  last_verified_by_actor_id: 23
  last_verified_by_actor_name: "HEPHAESTUS"
---

# CORRUPTION MANIFEST — Table Documentation Regeneration

## Purpose
Lists table documentation files identified as corrupted and requiring regeneration as part of 4.1.0 remediation effort.

## Audit Summary

**Total Files Identified**: ~76 corrupted table documentation files  
**Scope**: Phase 1 regeneration targeting corrupted files only  
**Method**: Surgical approach to minimize blast radius

## Corrupted Files Requiring Regeneration

### High Priority Tables (Core System)

- **lupo_actors** - Actor registry table
- **lupo_channels** - Channel definitions
- **lupo_auth_users** - Authentication users
- **lupo_departments** - Department definitions
- **lupo_sessions** - Session management

### Medium Priority Tables (Operational)

- **lupo_actor_channels** - Actor-channel relationships
- **lupo_actor_departments** - Actor-department assignments
- **lupo_collections** - Content collections
- **lupo_dialog_messages** - Chat messages
- **lupo_dialog_threads** - Chat threads

### Low Priority Tables (Supporting)

- **lupo_actor_capabilities** - Actor capability definitions
- **lupo_actor_traits** - Actor trait definitions
- **lupo_channel_roles** - Channel role definitions
- **lupo_federations** - Federation configurations
- **lupo_search_index** - Search index

### Legacy Tables (Crafty Syntax)

- **lupo_crafty_syntax_*** tables - Legacy compatibility
- **lupo_crafty_user_mapping** - User migration mapping
- **lupo_crafty_syntax_live_help** - Legacy live help

## Regeneration Strategy

### Phase 1: Database Schema Extraction
- Extract live schema from MySQL database
- Generate synthetic LUPOPEDIA_HEADERS with THOTH attribution
- Create markdown documentation with schema sections

### Phase 2: Edge Recovery
- Restore high-confidence edges from git history
- Scan codebase for table references
- Query database for existing relationships

### Phase 3: Validation
- YAML syntax validation
- Semantic validation by THOTH
- Schema alignment verification
- Graph consistency checking

## Quality Assurance

### Validation Checklist
- [ ] All generated files have valid YAML headers
- [ ] All schema sections match live database
- [ ] All edge targets exist and are reachable
- [ ] No orphaned documentation remains
- [ ] Semantic indexing completes successfully

### Completion Criteria
- All corrupted files regenerated with valid headers
- Schema alignment with install SQL verified
- Edge recovery with confidence scoring completed
- Full semantic validation passed

---

*Last updated: 2026-03-27 (4.1.0 remediation)*  
*Maintained by: HEPHAESTUS (actor_id 23) through cursor faucet*
