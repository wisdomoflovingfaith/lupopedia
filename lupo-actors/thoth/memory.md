---
lupopedia.headers:
  lupopedia.schema: actor_knowledge
  file_path_from_root: lupo-actors/thoth/memory.md
  when_updated: '20260324195100'
  last_modified_utc: '20260324195100'
  actor_id: 26
  actor_name: thoth
  artifact_type: actor_documentation
  artifact_kind: persistent_memory
  purpose: Document THOTH's documentation knowledge and edge review queue status
lupopedia.footer:
  last_verified: '20260324195100'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
---

# THOTH: Persistent Knowledge & Edge Review Queue (memory.md)

## Active Responsibilities (2026-03-24)

### Edge Review Queue Manager
**Primary Accountability**: Edge graph review authority  
**SLA Framework**:
- **P0 (48-hour turnaround)**: Blocking edge types, schema changes
  - Examples: edge type definitions, bidirectionality flags, type safety rules
  - Unblock Criteria: THOTH sign-off + ATHENA strategic approval
  
- **P1 (5-day turnaround)**: Strategic relationship documentation
  - Examples: Query method review, CTE correctness, indexes
  
- **P2 (2-week turnaround)**: Documentation updates, reference materials
  - Examples: Table docs, deprecation notices, guides
  
- **P3 (1-month turnaround)**: Maintenance work, style improvements
  - Examples: Link updates, formatting fixes, examples

**Queue Status** (2026-03-24):
- P0 Wait List: 12 edge types waiting initial validation → Sign-off pending
- P1 In Review: EdgeQueryService skeleton → Technical review in progress
- P2 Backlog: Deprecation notices (done), table docs (done)
- P3 Blocked: Waiting for P0/P1 completion

## Current Documentation Projects

### 1. Edge Type Definitions Documentation (P0)
**Artifacts Created**:
- 12 edge types defined with clear descriptions
- Type safety rules in `lupo_edge_type_definitions` table
- Bidirectionality flags documented

**Validation Checklist**:
- [ ] Are type descriptions clear to developers?
- [ ] Do allowed object type pairs match business intent?
- [ ] Are bidirectional flags semantically correct?
- [ ] Are there missing edge types?

**Reference**: ATHENA_STRATEGY Phase 2 Tracks 1-2

### 2. Table Documentation Updates (P1)
**Completed**:
- `lupo_context_edges.md` — Scope clarification (AI-only, NOT business relationships)
- `lupo_dialog_threads.md` — Deprecation notice for thread_lineage TEXT field
- `lupo_dialog_channels.md` — Deprecation notice for channels JSON field

**Lessons Embedded**:
- Scope boundaries prevent misuse
- Deprecation notices with migration paths aid transition
- Clear "do not use" messages prevent developer confusion

### 3. EdgeQueryService Documentation (P1 In Progress)
**Task**: Review and document 7 query methods
- `getRelatedChannels()` — Relationship discovery
- `getThreadsForChannel()` — Ownership queries
- `getThreadLineage()` — Ancestry chains via recursive CTE
- `getChannelGraph()` — Full adjacency visualization
- `getChannelHierarchy()` — Ancestors + descendants
- `getEdgeTypeStats()` — Aggregate reporting
- `getEdgesForObject()` — Polymorphic queries

**Validation Points**:
- CTE syntax correctness (MySQL 8.0+ compatible)
- Query performance (index awareness)
- Return value clarity (schema documented)
- Error handling (null cases)

## Core Documentation Patterns

### Pattern 1: Table Documentation Structure
**Standard Sections**:
1. Purpose (1-2 lines)
2. Column Definitions (name, type, meaning, constraints)
3. Relationships (FKs, edges, dependencies)
4. Query Examples (common patterns)
5. Deprecation Notice (if applicable, with migration path)
6. References (related tables, doctrine)

**Applied To**: All active table docs (lupo_actors, lupo_edges, lupo_edge_types, etc.)

### Pattern 2: Deprecation Notices
**Structure**:
```markdown
⚠️ **DEPRECATED** for [purpose]

**Replacement**: [New approach/table/service]

**Migration Path**: [How to migrate]

**Timeline**: Supported until [version], removed in [version]

**Questions**: See [Channel 66 Thread]
```

**Applied To**: JSON fields, Text fields, legacy columns

### Pattern 3: Artifact Lineage Tracking
**Format**:
```
Question (Thread XXXX)
  ↓
ATHENA Strategy Proposal (ATHENA_STRATEGY_*.md)
  ↓
LILITH Review (noting objections/validations)
  ↓
WOLFIE Decision (WOLFIE_DIRECTIVE_* or CHANGELOG entry)
  ↓
HEPHAESTUS Implementation (SQL/PHP code)
  ↓
THOTH Documentation (resolution artifact + table docs)
  ↓
CHANGELOG Entry (session documentation)
```

**Benefits**: Full audit trail; developer can trace why each decision was made

## Lessons from Phase 2 Documentation Work

### Lesson 1: Scope Clarity Prevents Developer Mistakes
**Challenge**: Developers confusing `lupo_edges` (business) with `lupo_context_edges` (AI-only)
**Resolution**: Create explicit scope section in table doc with "What belongs / What does NOT belong" table
**Application**: All new edges/tables need explicit scope boundaries

### Lesson 2: Deprecation Notices Need Migration Paths
**Challenge**: Developers abandoned old patterns without knowing alternative
**Resolution**: Add `channels JSON field` → `EdgeMigrationService::migrateDialogChannelRelations()` in deprecation notice
**Application**: Always provide "replacement" and "how to migrate" in deprecation notices

### Lesson 3: Recursive CTE Validation Requires SQL Review
**Challenge**: Simple mistakes in CTE syntax cause query failure
**Resolution**: THOTH reviews all graph queries for:
- Anchor/recursive clause separation
- Termination conditions (depth limits, UNION ALL)
- Correct column alias usage
**Application**: CTE queries go through P0 SLA process

### Lesson 4: Documentation Timestamps Bridge Actor Coordination
**Challenge**: Multiple actors updating docs simultaneously = conflict risk
**Resolution**: Use `last_verified` timestamp + staleness threshold (20260301000000)
**Application**: Before consolidating docs, check timestamps + read existing content

## Edge Review Queue Tracking

### Active P0 Items
**Item 1: Edge Type Vocabulary (12 types)**
- Status: ✅ Defined, awaiting validation sign-off
- Effort: Created (ATHENA), awaiting THOTH review
- Unblock Criteria: THOTH validates descriptions + bidirectionality
- Timeline: 48-hour SLA = answer by 2026-03-26 18:00 UTC
- Owner: THOTH

**Item 2: Type Safety Rules (12 definitions)**
- Status: ✅ Defined, awaiting validation sign-off
- Effort: Created (ATHENA), awaiting THOTH review
- Unblock Criteria: THOTH validates allowed object type pairs
- Timeline: 48-hour SLA = answer by 2026-03-26 18:00 UTC
- Owner: THOTH

**Item 3: EdgeQueryService Methods (7 methods)**
- Status: ⏳ Implementation complete, documentation in progress
- Effort: Code review + CTE validation + usage doc
- Unblock Criteria: THOTH documents all 7 methods with examples
- Timeline: 48-hour SLA = answer by 2026-03-26 18:00 UTC
- Owner: THOTH

## Documentation Maintenance Calendar

**Daily**: Monitor incoming questions, flag scope issues  
**Weekly**: Update edge review queue status in EDGE_REVIEW_QUEUE.md  
**Bi-weekly**: Review staleness threshold, identify docs needing re-verification  
**Monthly**: Archive completed documentation, prepare for next version  

## Known Documentation Gaps

⚠️ **Gap 1**: Thread 1047 awaiting ROSE external consultation (not THOTH responsibility, pending)  
⚠️ **Gap 2**: P2 documentation for Channels 62-64 not yet started (in queue)  
⚠️ **Gap 3**: Performance documentation for edge queries (CTE optimization tips) deferred to P3  

## Next Session Priorities

1. Issue P0 sign-off on edge types + definitions (48-hour SLA)
2. Document EdgeQueryService methods with examples
3. Update edge_review_queue.md with current status
4. Validate table doc links for correctness
5. Prepare P2 documentation for Channel 62/63/64 work
