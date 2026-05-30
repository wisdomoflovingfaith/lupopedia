---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260402220000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260402_220000_DECISION_context_system_rejection.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260402_220000_DECISION_context_system_rejection.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "version-4.0.93-decisions"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Decision: Reject PRD 31 Context System

## WHO
- **Decision Maker**: LILITH (actor_id 2) - Adversarial QA Agent
- **COUNTERMEASURE Review**: Identified architectural conflict
- **CURSOR Implementation**: Created PRD 31

## WHAT
Rejection of PRD 31 "Context System Framework" due to creation of parallel classification system that conflicts with existing architecture.

## WHERE
- **PRD 31**: `docs/versions/4.0.94/prd/31_context_system.md` (rejected spec; retained only as a redesign workspace for 4.0.94+)
- **Conflict Point**: PRD 26's five-layer architecture
- **Alternative**: Use existing `tags` system and `edges.md`

## WHEN
- **Date**: 2026-04-02 22:00 UTC
- **Context**: During PRD 30 development thread
- **Effective**: Immediately upon this decision

## WHY
### Primary Reasons
1. **Parallel Classification System**: PRD 31 created a second way to categorize documentation
2. **Architectural Conflict**: Conflicted with PRD 26's established five-layer architecture
3. **Unnecessary Complexity**: Existing `tags` system already provides categorization
4. **COUNTERMEASURE Finding**: Correctly identified as architectural drift risk

### COUNTERMEASURE Analysis
```
"LILITH Analysis: COUNTERMEASURE Objection — PRD 31 (Context System)"
Verdict: REJECTED
Reason: A parallel classification system will fragment the documentation architecture
```

## HOW
### Implementation Steps
1. **Relocate PRD 31 workspace**: Canonical working path `docs/versions/4.0.94/prd/31_context_system.md` (rejected content must be redesigned; no parallel classification in production docs)
2. **Remove Context System**: Deleted `contexts/` directory structure
3. **Database Cleanup**: Removed `contexts` and `contexts_map` tables
4. **Maintain Simplicity**: Continue using `tags` in headers
5. **Preserve Architecture**: Keep PRD 26's five-layer model intact

### Resolution
- **Status**: Complete
- **Alternative**: Use existing `tags` for basic categorization
- **WHERE Layer**: Continue using `edges.md` for relationships
- **5W1H Framework**: Apply as thinking pattern, not structural system

## Impact Assessment

### Positive Outcomes
- ✅ Maintained architectural consistency
- ✅ Prevented system fragmentation
- ✅ Preserved simplicity
- ✅ Reinforced PRD 26 authority

### Files Affected
- `docs/versions/4.0.94/prd/31_context_system.md` → Redesign stub / future PRD workspace
- `contexts/` → Deleted
- `install_new_lupopedia.sql` → Tables removed
- `docs/doctrine/CONTEXT_TAXONOMY.md` → Deleted

## Success Criteria
1. No parallel classification systems exist
2. Context handled via edges.md or tags
3. Five-layer architecture preserved
4. Documentation remains simple and maintainable

---

## LILITH Final Assessment

```yaml
verdict: "Correct decision - architectural integrity preserved"
rationale: "COUNTERMEASURE correctly identified the risk of parallel systems"
outcome: "System remains cohesive with established architecture"
```
