---
lupopedia.headers:
  lupopedia.schema: actor_knowledge
  file_path_from_root: lupo-actors/athena/memory.md
  when_updated: '20260324195100'
  last_modified_utc: '20260324195100'
  actor_id: 12
  actor_name: athena
  artifact_type: actor_documentation
  artifact_kind: persistent_memory
  purpose: Document ATHENA's architectural knowledge and design patterns
lupopedia.footer:
  last_verified: '20260324195100'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
---

# ATHENA: Persistent Knowledge & Architecture Patterns (memory.md)

## Current Active Strategies

### Phase 2 Edge Graph Architecture (2026-03-20 through 2026-03-24)
**Strategy Document**: ATHENA_STRATEGY_20260324_120000_edge_graph_channel_thread_recommendations.md  
**Status**: Implementation ready (SQL + PHP complete)  
**Tracks**:
1. Edge type vocabulary (12 types) ✅
2. Type safety rules ✅
3. JSON→Edges migration (Track 3a) ✅
4. Legacy field cleanup (Track 3b, deferred) ⏳
5. Parent channel backfill ✅
6. Query encapsulation (EdgeQueryService) ✅

**Key Design Decisions**:
- Polymorphic edges (left/right object types) for flexibility
- Bidirectionality flags for CTE support
- Separation: business edges (`lupo_edges`) vs AI context (`lupo_context_edges`)
- Recursive CTE support for lineage queries

**Lessons Embedded**: Channel relationships implicit in parent_channel_id; making them explicit via edges enables new queries without schema change

### Actor Pairing Defaults (Thread 1052, Resolved 2026-03-24)
**Design**: Three-tier precedence for multi-user pairing
1. User explicit selection (highest)
2. Channel default pairing
3. Actor default fallback
**Implementation**: EffectiveActorResolver service in app/Services/
**Status**: Already implemented in 4.0.87

## Core Architectural Patterns

### Pattern: Polymorphic Relationships via Edge Graph
**Concept**: Single `lupo_edges` table stores all entity relationships
**Structure**:
```
left_object_type | left_object_id | edge_type | right_object_type | right_object_id
     'channel'   |       42       | 'related' |     'channel'      |        63
     'thread'    |      1050      | 'spawned' |     'channel'      |        66
```
**Benefits**:
- Queryable (CTE support)
- Extensible (new types without schema change)
- Auditable (full lineage available)
- Indexable (performance)

### Pattern: Type Safety via Reference Tables
**Concept**: `lupo_edge_type_definitions` enforces which object type pairs are valid
**Structure**: 
```
edge_type | allowed_left_objects | allowed_right_objects | bidirectional
'channel_parent' | ['channel'] | ['channel'] | 0 (directional)
'channel_related' | ['channel'] | ['channel'] | 1 (bidirectional)
```
**Benefits**:
- Schema-level validation
- Prevents invalid relationships
- Documents intent

### Pattern: Query Encapsulation
**Concept**: Callers use `EdgeQueryService` methods, not raw SQL
**Structure**:
```php
EdgeQueryService::getThreadLineage($thread_id) 
  → Recursive CTE under the hood
  → Caller doesn't care about SQL details
```
**Benefits**:
- Single point of maintenance
- Schema changes don't affect callers
- Testable units
- Query optimization opportunities

## Lessons from Legacy Challenges (2026-03-20 to 2026-03-24)

### Challenge 1: Implicit vs. Explicit Relationships
**Problem**: parent_channel_id was implicit (required knowledge of schema); new code couldn't discover it without joins
**Resolution**: Make relationships explicit via edges; keep parent_channel_id for backward compatibility
**Pattern Applied**: Track 3c backfill (parent_channel_id → channel_parent edges)

### Challenge 2: JSON Field Queryability
**Problem**: `dialog_channels.channels` (JSON) wasn't queryable; had to deserialize in application code
**Resolution**: Migrate to `lupo_edges`; formalize in Track 3a (EdgeMigrationService)
**Pattern Applied**: JSON → edges migration with error handling

### Challenge 3: Thread Lineage Complexity
**Problem**: `dialog_threads.thread_lineage` (TEXT) was unstructured; tracing history required parsing
**Resolution**: Use recursive CTE in `lupo_edges` for clean ancestry chains
**Pattern Applied**: Track 3b (deferred to P2) will complete thread_lineage migration

## Database Patterns ATHENA Champions

### Soft Deletes (All Tables)
**Pattern**: `is_deleted` column + `deleted_ymdhis` for temporal tracking
**Rule**: Queries must filter `WHERE is_deleted = 0` by default
**Applied**: All edge operations include soft delete checks

### Timestamps in YYYYMMDDHHIISS Format
**Pattern**: UTC time in `BIGINT` (not `DATETIME`), set by application, never database-generated
**Rule**: `gmdate('YmdHis')` in PHP; never `time()` or `TIMESTAMP` columns
**Applied**: All new edges use this format consistently

### No Foreign Keys, Triggers, Views
**Philosophy**: Database is dumb storage; all logic in PHP
**Benefits**: Portability (MySQL/PostgreSQL/MariaDB), testability, flexibility
**Applied**: `lupo_edges` has no FK constraints; validation in PHP (EdgeMigrationService)

## Design Principles (Canonical)

1. **Explicit > Implicit**: Make relationships explicit via edges, not schema inference
2. **Queryable > Stored**: Prefer queried relationships over pre-computed structures
3. **Flexible > Rigid**: Use polymorphic patterns for extensibility
4. **Auditable > Hidden**: Full edge lineage available for compliance
5. **Simple > Complex**: Start with simple relationships; add complexity when needed

## Known Unresolved Questions (Awaiting ROSE)

**Thread 1047**: Multi-channel header ownership
- Q1: Header reimport safety & determinism strategy
- Q2: Canonical channel version when same file appears in multiple channels
- Q3: Should headers be immutable (generated-only) or mutable (with versioning)?

**Impact**: Future releases (non-blocking for 4.0.87)

## Next Design Priorities

1. Validate SQL execution (Tracks 1-3c) for correctness
2. Test recursive CTE performance at scale
3. Design Channel 62/63/64 organization patterns
4. Plan P3 work: query optimization, reporting views (without breaking KISS principles)
