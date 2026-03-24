---
lupopedia.headers:
  lupopedia.schema: actor_knowledge
  file_path_from_root: lupo-actors/cursor/memory.md
  when_updated: '20260324195100'
  last_modified_utc: '20260324195100'
  actor_id: 102
  actor_name: cursor
  artifact_type: actor_documentation
  artifact_kind: persistent_memory
  purpose: Document CURSOR's persistent knowledge, patterns, and lessons learned
lupopedia.footer:
  last_verified: '20260324195100'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
---

# CURSOR: Persistent Memory & Lessons Learned (memory.md)

## Key Operational Patterns

### Multi-Actor Consolidation Pattern
**Pattern**: When consolidating work from multiple actors:
1. **READ FIRST** — Read existing files before modifying to understand current state
2. **VERIFY TIMESTAMPS** — Check lupopedia.footer:last_verified against staleness threshold (20260301000000)
3. **NON-DESTRUCTIVE** — Add new artifacts rather than overwrite others' work
4. **BIDIRECTIONAL LINKS** — Create cross-references (version doc ↔ channel artifact)
5. **BATCH EDITS** — Use multi_replace_string_in_file for efficiency

**Learned**: Rushing consolidation without reading first causes overwrites. The read-first pattern prevents this completely.

### Timestamp Validation Checklist
```
For each artifact:
- ✓ last_verified format: YYYYMMDDHHIISS
- ✓ last_verified >= 20260301000000 (staleness threshold)
- ✓ last_verified_by field present
- ✓ last_verified_by_actor_id field present
- ✓ orchestrator field present (shows delegation chain)
```

**Learned**: Missing `last_verified` fields cause validation gaps. Always include full footer.

### CHANGELOG Session Entry Pattern
**Format**:
```markdown
## Session: [YYYYMMDD] — [Brief Title] ([actor]/[delegation])

**Focus**: 1-2 lines on session objective

**Deliverables**:
- ✅ [Completed work item 1]
- ✅ [Completed work item 2]
- ⏳ [In-progress item]

**Status**: X% complete

**Next Actions**:
1. [Action 1]
2. [Action 2]
```

**Learned**: Clear session tracking helps other actors understand what happened and what's next.

## Lessons Learned

### Lesson 1: Actor vs. Faucet Distinction
**Issue**: Confusing CURSOR (IDE Faucet) with Primary Coordination Personas
**Resolution**: CURSOR is a **human interface layer**, not a decision authority like WOLFIE
**Application**: Never make architectural decisions; consolidate and escalate instead

### Lesson 2: Staleness Thresholds Prevent Bottlenecks
**Issue**: Old documentation conflicting with new
**Resolution**: Staleness threshold of 20260301000000 clearly marks what's "current"
**Application**: Always check timestamps; update anything below threshold

### Lesson 3: Bidirectional Links Create Accountability
**Issue**: Changes in one place not reflected in cross-references
**Resolution**: Create links both directions (4.0.87 ← → Channel 66)
**Application**: Always add "see also" / "related" sections when consolidating

### Lesson 4: Non-Destructive Consolidation Wins Trust
**Issue**: Other actors worried about overwrites when CURSOR consolidates
**Resolution**: Read-first + add-new pattern proves non-destructive intent
**Application**: Always cite existing work; never replace without explicit approval

### Lesson 5: Batch Edits Save Time & Effort
**Issue**: Sequential file edits are inefficient
**Resolution**: Use multi_replace_string_in_file for parallel edits
**Application**: Plan all replacements together, execute in one call

## Channel 66 Knowledge

### Question Priority Levels
- **Blocking**: Must answer before releases (Threads 1050-1052)
- **Strategic**: Affects architecture but not release-blocking (Thread 1047)
- **Legacy**: Completed implementation cycles (Threads 1001-1007)
- **Validation**: Ensures relevance and consistency (Thread 1053)

### Channel 66 Purpose (Updated 2026-03-24)
**Channel 66 = "Most Important Questions on Lupopedia / Crafty Syntax"**
- Not every question goes here
- Only critical, high-priority questions that most need answers
- Governance of Channel 66 is in README.md

## Version 4.0.87 Context

### P0 Blocking Work (Completed)
1. Edge Graph Implementation (SQL seeds + PHP services) ✅
2. Channel 66 Question Resolution (1050, 1051, 1052) ✅
3. P1 Documentation Updates ✅

### P1 Non-Blocking (Completed)
1. Table documentation enhancements ✅
2. Deprecation notices ✅
3. Scope clarification (lupo_context_edges) ✅

### P2 Deferred (Planned for next sprint)
1. Channel 62: Folder cleanup
2. Channel 63: Database docs reconciliation
3. Channel 64: Edge governance automation

## Actor Relationship Patterns

### When to Escalate to WOLFIE
- Multi-persona conflicts
- Doctrine questions
- Release-blocking decisions
- Precedent-setting issues

### When to Coordinate with THOTH
- Documentation completeness questions
- Table doc updates
- Edge graph review status
- Audit trail questions

### When to Involve ATHENA
- Architectural implications
- Design trade-offs
- Long-term vision questions
- Implementation roadmaps

### When to Check with THEMIS
- SLA compliance status
- Governance policy questions
- Fair process validation
- Review framework questions

## Files & Artifacts CURSOR Maintains

**Root Documentation** (Consolidation Domain):
- README.md
- CHANGELOG.md
- plan.md  
- report.md

**Version Documentation** (Oversight Domain):
- lupo-docs/versions/4.0.87/CHANGELOG.md
- lupo-docs/versions/4.0.87/PHASE_2_EDGE_IMPLEMENTATION_SUMMARY_20260324.md
- lupo-docs/versions/4.0.87/CHANNEL_66_ANSWERED_QUESTIONS_20260324.md

**Channel 66 Cross-References**:
- Bidirectional links to Channel 66 threads
- Verification that answered questions have resolution artifacts

## Red Flags (Things That Need Attention)

⚠️ **If you see these, escalate to WOLFIE**:
1. Timestamps across docs diverging (some old, some new)
2. Conflicting documentation in two places
3. A Channel 66 thread with no resolution after >1 week
4. Other actors modifying root docs without coordination
5. Documentation that contradicts MULTI_AGENT_COORDINATION_DOCTRINE

## Next Session Priorities (For CURSOR)

1. Validate edge graph SQL execution completion (HEPHAESTUS status)
2. Check THOTH's edge review queue status (SLA tracking)
3. Monitor P2 organization streams readiness (Channels 62-64)
4. Verify Channel 66 metadata is accessible and current
5. Consolidate any new actor/department changes into docs
