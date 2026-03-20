---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/versions/4.0.84/TODO.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.84/todo"
  last_modified_utc: "20260320"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "task_list"
  artifact_kind: "version_todo"
  title: "Version 4.0.84 TODO"
  purpose: "Active task registry for Lupopedia version 4.0.84 development"
  tags: ["version", "4.0.84", "todo", "tasks", "development"]
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
  comment: "Snapshot of outbound edges for 4.0.84 TODO."
  meta: "Version 4.0.84 task registry; active development items; blockers."
  outbound_edges:
    - { to: "PLAN.md", type: "references", weight: 1.0, reason: "Execution plan for 4.0.84" }
    - { to: "CHANGELOG.md", type: "references", weight: 1.0, reason: "Version history and completed work" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 0.95, reason: "Header format requirements" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Complete remaining semantic validation fixes"
    - "Monitor Channel 66 production deployment"
    - "Coordinate HEPHAESTUS implementation across channels"
---

# file: Version 4.0.84 TODO — session: L-LUPO-ROOT-CURSOR — delegation: wolfie:root — web_path: http://www.lupopedia.com/docs/versions/4.0.84/todo

# Version 4.0.84 TODO

**Status:** 🔄 Active Development  
**Last Updated:** 2026-03-20

## ✅ Completed Tasks

### LUPOPEDIA_HEADERS Doctrine Cleanup
- [x] Remove all `lupopedia.version`, `system_version`, `last_verified_system_version` from documentation
- [x] Update LUPOPEDIA_HEADERS_FORMAT.md with baseline rewrite rules (§2.0)
- [x] Convert VERSIONING_MODEL.md to obsolete stub
- [x] Add LILITH edge case analysis to README.md
- [x] Create WOLFIE script `generate_headers_from_db.py` for TOON-based header generation
- [x] Update VERSIONING_DOCTRINE.md with single-field versioning model
- [x] Create 4.0.84 version directory structure

### Documentation Updates
- [x] Update CHANGELOG.md with 4.0.84 entries
- [x] Update TODO.md with completion status
- [x] Update HANDOFF_TO_WINDSURF.md with completion status
- [x] Update plan.md with next actions
- [x] Create comprehensive version documentation

## 🔄 In Progress Tasks

### LUPOPEDIA Headers Reliability (Channel 66 / Thread 1005)
- [ ] Implement canonical header key registry shared by generation/import/validation
- [ ] Add table-aware projection for channels, threads, actors, collections, tasks, and edges
- [ ] Enforce `channel_id` + `thread_id` + `actor_id` integrity checks before DB write
- [ ] Add dry-run diff + conflict detection for header import operations
- [ ] Add round-trip tests: DB -> header -> edited header -> DB
- [ ] Include all TOON tables containing `channel_id` in validation coverage
- [ ] Track implementation against artifact: `lupo-channels/66/threads/1005/20260320_700000_lilith_headers_improvement_plan_channel_scoped_metadata_sync.md`

### Semantic Validation Fixes (Thread 1004)
- [ ] Fix `lupo_visits.actor_id` mapping semantic validation issues
- [ ] Resolve validation blockers in Channel 66
- [ ] Resolve validation blockers in Channel 88
- [ ] Complete HEPHAESTUS implementation plan execution
- [ ] Test semantic validation fixes in production environment

### Production Deployment Monitoring
- [ ] Monitor Channel 66 production deployment safety
- [ ] Ensure semantic validation doesn't break production systems
- [ ] Coordinate with HEPHAESTUS implementation teams
- [ ] Validate production system stability

## 📋 Pending Tasks

### Code Quality and Testing
- [ ] Add unit tests for `generate_headers_from_db.py`
- [ ] Test baseline rewrite functionality on legacy files
- [ ] Validate TOON schema integration
- [ ] Test version resolution across all components

### Documentation Finalization
- [ ] Update lupo-docs/version.md to show current 4.0.84
- [ ] Verify all documentation references are current
- [ ] Update any remaining legacy version references

## 🚫 Blocked Tasks

### A12.4 Constitutional Signoff
- **Blocker:** task_prompt_010100
- **Status:** Awaiting constitutional review and signoff
- **Impact:** Cannot proceed with certain governance-related tasks

### Watcher Auto-Draft Acceptance
- **Blocker:** task_prompt_234200
- **Status:** Awaiting watcher system configuration
- **Impact:** Automated task drafting not available

## 🎯 Priority Tasks (P0)

1. **Thread 1004 Semantic Validation Fixes**
   - **Owner:** HEPHAESTUS implementation team
   - **Priority:** Critical - blocking production deployment
   - **Timeline:** Immediate

2. **Channel 66 Production Deployment Safety**
   - **Owner:** WOLFIE + Channel 66 team
   - **Priority:** Critical - production system stability
   - **Timeline:** Continuous monitoring

## 📊 Task Statistics

- **Total Tasks:** 18
- **Completed:** 9 (50%)
- **In Progress:** 4 (22%)
- **Pending:** 3 (17%)
- **Blocked:** 2 (11%)

## 🔗 Related Artifacts

- [Thread 1004](../../lupo-channels/42/threads/1004/) - Semantic validation coordination
- [Channel 66](../../lupo-channels/66/) - Production deployment channel
- [LUPOPEDIA_HEADERS Doctrine](../../../doctrine/LUPOPEDIA_HEADERS/) - Header format and requirements
- [VERSIONING_DOCTRINE.md](../../../doctrine/VERSIONING_DOCTRINE.md) - Versioning policy and rules
- [Version 4.0.84 Documentation](./) - Complete version archive and plans

## 📝 Notes

- All LUPOPEDIA_HEADERS cleanup work is complete
- Single-field versioning model is now enforced across all documentation
- Focus has shifted to semantic validation and production stability
- Version 4.0.84 is ready for release pending semantic validation fixes

---

*Last updated: 2026-03-20*
