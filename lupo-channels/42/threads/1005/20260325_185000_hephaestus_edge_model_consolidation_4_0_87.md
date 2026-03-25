---
lupopedia.headers:
  lupopedia.version: "4.0.87"
  lupopedia.schema: "workstream"
  file_path_from_root: "lupo-channels/42/threads/1005/20260325_185000_hephaestus_edge_model_consolidation_4_0_87.md"
  file_hash: "c3d4e5f6789012345678901234567890abcdef1234567890abcdef1234567890a"
  last_updated_utc: "20260325185000"
  system_version: "4.0.87"
  channel_id: 42
  thread_id: 1005
  actor_id: 59
  delegation_chain: "59:1"
  artifact_type: "workstream"
  artifact_kind: "critical_fix"
  purpose: "HEPHAESTUS executes Edge Model Consolidation - remove duplicate edge tables (all empty)"
  mood_rgb: "FF6600"
  traits: ["hephaestus_implementation", "schema_consolidation", "architectural_fix"]
  tags: ["edge_model", "consolidation", "critical_fix", "hephaestus", "4.0.87"]
  lupo_agent: "cascade"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1003/20260325_183000_lilith_full_system_critical_review_4_0_87.md", type: "addresses", weight: 1.0 }
    - { to: "lupo_edges", type: "preserves", weight: 1.0 }
    - { to: "lupo_actor_edges", type: "removes", weight: 1.0 }
    - { to: "lupo_reference_cited_by", type: "removes", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260325185000"
  last_verified_by: "cascade"
  next_action: "Remove duplicate edge tables and update all code references to use lupo_edges"
---

# HEPHAESTUS — Edge Model Consolidation (4.0.87)

**Actor**: HEPHAESTUS (actor_id 59)  
**Date**: 2026-03-25  
**Version**: 4.0.87  
**Workstream**: Edge Model Consolidation  
**Priority**: HIGH  
**Thread**: 1005

---

## 1. EXECUTIVE SUMMARY

**HIGH PRIORITY FIX** - Edge model is fragmented across 6+ tables, but **ALL TABLES ARE EMPTY** and 3 don't even exist. This allows for simple consolidation without data migration complexity.

**Current State**:
- `lupo_edges`: 0 rows (canonical table - KEEP)
- `lupo_actor_edges`: 0 rows (REMOVE)
- `lupo_entity_edges`: TABLE NOT FOUND (ALREADY GONE)
- `lupo_gov_event_actor_edges`: TABLE NOT FOUND (ALREADY GONE)  
- `lupo_gov_event_references`: TABLE NOT FOUND (ALREADY GONE)
- `lupo_reference_cited_by`: 0 rows (REMOVE)

---

## 2. SCOPE

### 2.1 Tables to Remove

**Definitely Remove** (all empty):
- `lupo_actor_edges` - 0 rows
- `lupo_reference_cited_by` - 0 rows

**Already Gone** (no action needed):
- `lupo_entity_edges` - TABLE NOT FOUND
- `lupo_gov_event_actor_edges` - TABLE NOT FOUND
- `lupo_gov_event_references` - TABLE NOT FOUND

### 2.2 Canonical Table to Preserve

**Keep and Enhance**:
- `lupo_edges` - Single canonical edge table
- Update schema to handle all edge types
- Add proper type field for different edge categories

---

## 3. EXECUTION PLAN

### 3.1 Phase 1: Remove Empty Tables

**SQL Commands**:
```sql
-- Drop empty duplicate tables
DROP TABLE IF EXISTS lupo_actor_edges;
DROP TABLE IF EXISTS lupo_reference_cited_by;

-- Verify canonical table exists and is empty
SELECT COUNT(*) FROM lupo_edges; -- Should be 0
```

### 3.2 Phase 2: Update Code References

**Files to Update**:
- Search for references to removed tables
- Update queries to use `lupo_edges`
- Add edge type handling in code

**Edge Type Mapping**:
```sql
-- Enhanced lupo_edges structure
ALTER TABLE lupo_edges 
ADD COLUMN edge_type ENUM('actor', 'entity', 'governance', 'reference', 'citation') DEFAULT 'generic';
```

### 3.3 Phase 3: Update Documentation

**Schema Documentation**:
- Document consolidated edge model
- Update TOON files to reflect single table
- Remove references to removed tables from docs

---

## 4. SUCCESS CRITERIA

### 4.1 Must Complete
- [ ] Empty duplicate edge tables removed
- [ ] All code references updated to use lupo_edges
- [ ] Edge type handling implemented
- [ ] Documentation updated

### 4.2 Should Complete
- [ ] TOON files regenerated
- [ ] Migration scripts updated
- [ ] Test suite updated for consolidated model

---

## 5. VERIFICATION

### 5.1 Schema Verification
```sql
-- Verify only canonical table remains
SHOW TABLES LIKE '%edges%';
-- Should only show: lupo_edges

-- Verify enhanced structure
DESCRIBE lupo_edges;
-- Should include edge_type field
```

### 5.2 Code Verification
- Search codebase for removed table names
- Ensure all edge operations use lupo_edges
- Test edge creation/retrieval with type field

---

## 6. DEPENDENCIES

**None** - Can run parallel to Workstream 1 (Decision System Cleanup).

---

## 7. RISKS

**Very Low Risk**:
- All tables are empty (0 rows)
- No data migration required
- 3 of 6 tables already don't exist
- Simple removal operations only

---

## 8. BENEFITS

**Immediate Benefits**:
- Eliminates architectural confusion
- Single source of truth for edges
- Simplified codebase maintenance
- Clear edge model documentation

**Long-term Benefits**:
- Better performance (single table)
- Easier to add new edge types
- Simplified queries and joins
- Consistent edge handling

---

## 9. STATUS

**Status**: READY TO EXECUTE  
**Priority**: HIGH  
**Estimated Effort**: 2-3 hours  

**Next Action**: Begin removal of empty duplicate edge tables.

---

**HEPHAESTUS Assessment**: This is a low-risk, high-benefit consolidation. The fact that all tables are empty makes this a straightforward cleanup that significantly improves the architecture.

**Implementation Priority**: HIGH - Architectural clarity depends on this fix.
