---
lupopedia.headers:
  lupopedia.schema: actor_knowledge
  file_path_from_root: lupo-actors/1/memory.md
  when_updated: '20260324195100'
  questions_toon: null
  actor_id: 1
  actor_name: wolfie
  artifact_type: actor_documentation
  artifact_kind: persistent_memory
  purpose: Document WOLFIE's persistent knowledge, system state, and precedents
lupopedia.footer:
  last_verified: '20260324195100'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
---

# WOLFIE: Persistent Memory & System State (memory.md)

## Active Work Streams (2026-03-24)

### Phase 2 Edge Graph Implementation
**Status**: ~85% Complete
- SQL seeds: Ready for HEPHAESTUS execution
- PHP services: EdgeMigrationService and EdgeQueryService both implemented
- Documentation: All P0/P1 items complete
- **Next**: Execute SQL, then integration testing

### Channel 66 Production Questions
**Status**: All 4.0.87 blockers answered
- Thread 1050: Root archive scope ✅
- Thread 1051: Edge review ownership + SLA ✅
- Thread 1052: Actor pairing defaults ✅
- Thread 1053: Relevance validation ✅
- **Next**: Route to implementing actors

### P2 Organization Streams (Queued)
**Status**: Planning complete
- Channel 62: Folder cleanup planning ready
- Channel 63: Database reconciliation planning ready
- Channel 64: Edge governance planning ready
- **Next**: Begin execution when P0 clears

## System Architecture Decisions (Precedents)

### 1. Edge Graph as Primary Relationship Storage (Decision: 2026-03-20)
**Question**: Should channels/threads relationships live in `lupo_edges` or legacy JSON fields?
**Decision**: `lupo_edges` is primary; legacy JSON (`dialog_channels.channels`, `dialog_threads.thread_lineage`) deprecated
**Reasoning**: Queryable, polymorphic, enforces schema
**Impact**: Requires Track 3a/3b migrations (JSON → edges)
**Enforcement**: HEPHAESTUS executes, THOTH documents

### 2. lupo_context_edges Is AI-Only (Decision: 2026-03-20)
**Question**: Can `lupo_context_edges` be used for business relationships like channels?
**Decision**: NO — `lupo_context_edges` is strictly AI cognitive context, NOT for entity relationships
**Reasoning**: Separate concerns (business vs. reasoning), prevents schema confusion
**Impact**: Clear boundary prevents unintended usage
**Reference**: Thread 1051 SLA assignment for governance

### 3. Actor Pairing Precedence Hierarchy (Decision: 2026-03-24, Thread 1052)
**Question**: When an actor has multiple user linkages, which is primary?
**Decision**: (1) Explicit user selection in chat UI (highest), (2) Channel pairing, (3) Fallback list
**Reasoning**: User intentionality > implicit, explicit > default
**Enforcement**: EffectiveActorResolver service (already implemented)
**Reference**: ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md

### 4. SLA Tiers for Edge Review (Decision: 2026-03-24, Thread 1051)
**Question**: What SLA applies to edge graph review tasks?
**Decision**: P0=48h, P1=5d, P2=2w, P3=1mo
**Reasoning**: P0 blocks releases; P1 is strategic; P2 is roadmap; P3 is polish
**Assignment**: THOTH primary, ATHENA strategic, THEMIS governance
**Unblock**: THOTH sign-off + ATHENA approval for P0
**Reference**: EDGE_REVIEW_QUEUE.md

## The Eleven Persona Coordination

**Current Status** (Active Personas):
- ✅ WOLFIE (1) — Orchestrator [Active]
- ✅ LILITH (2) — Critic [Active, in review mode]
- ✅ ATHENA (12) — Strategy [Active on Phase 2]
- ✅ HEPHAESTUS (14) — Implementation [Active, awaiting SQL assignment]
- ✅ THEMIS (9) — Governance [Active on SLA tracking]
- ✅ THOTH (26) — Documentation [Active on edge review]
- ✅ CURSOR (102) — IDE Faucet [Active on consolidation]
- ⏳ ROSE — External consultation [Pending Thread 1047 response]
- ⏳ MAAT — Conflict resolution [On-call]
- ⏳ ANUBIS — Data integrity [On-call]
- ⏳ LEXA/HEIMDALL/SESHAT/JANUS — Support roles [On-call]

## Lessons Learned from Phase 1-2

### Lesson 1: LUPOPEDIA HEADERS as Source of Truth
**Insight**: Headers should be generated FROM database, not manually maintained
**Application**: Going forward, use `generate_headers_from_db.py` for all table docs
**Exception**: Actor documentation and version artifacts use manual headers

### Lesson 2: Staleness Thresholds Prevent Git-Like Merges
**Insight**: Instead of merge conflict resolution, use timestamps + read-first pattern
**Application**: All future consolidation work uses staleness threshold
**Threshold**: 20260301000000 (2026-03-01) is current baseline for 4.0.87

### Lesson 3: Non-Blocking P1/P2 Work Prevents Release Bottlenecks
**Insight**: Split into blocking (P0) and non-blocking (P1/P2) from day one
**Application**: Future phases will clearly mark P0/P1/P2 split
**Result**: Phase 2 can release P0 work while P1/P2 continue

### Lesson 4: Channel 66 as Critical Question Hub Works
**Insight**: Dedicating Channel 66 to "most important questions" focuses effort
**Application**: Maintain strict filtering on what goes to Channel 66
**Criteria**: Must satisfy architecture/blocker/governance/data model/UX impact

## Known Issues & Tracking

### In Progress
- SQL migration execution (waiting on HEPHAESTUS assignment)
- THOTH edge review queue formation (in progress)
- P2 organization streams readiness (planning complete)

### Deferred to Long-Term (Thread 1047)
- Multi-channel header ownership resolution
- Header immutability vs. editability policy
- External AI consultation requirements
**Status**: Awaiting ROSE response (non-blocking)

## Next Governance Actions

1. **Assign HEPHAESTUS** for SQL track 1-3c execution
2. **Verify THOTH** starts edge review queue tracking
3. **Monitor CURSOR** consolidation for all session exits
4. **Blueprint P2 execution** for Channels 62-64
5. **Track SLA compliance** across all actors

## Current Version Context

**Version**: 4.0.87  
**Branch**: main  
**Release Stage**: Final P0 execution + P1/P2 queueing  
**Documentation**: Complete for release  
**Code**: Ready for SQL execution + integration testing  

**Status**: ✅ System is optimally positioned for successful completion
