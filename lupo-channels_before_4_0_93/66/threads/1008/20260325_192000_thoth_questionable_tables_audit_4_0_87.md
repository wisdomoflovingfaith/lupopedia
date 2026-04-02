---
lupopedia.headers:
  lupopedia.version: "4.0.87"
  lupopedia.schema: "workstream"
  file_path_from_root: "lupo-channels/66/threads/1008/20260325_192000_thoth_questionable_tables_audit_4_0_87.md"
  file_hash: "f6789012345678901234567890abcdef1234567890abcdef1234567890abcd"
  last_updated_utc: "20260325192000"
  system_version: "4.0.87"
  channel_id: 66
  thread_id: 1008
  actor_id: 26
  delegation_chain: "26:1"
  artifact_type: "workstream"
  artifact_kind: "audit_classification"
  purpose: "THOTH audits questionable tables - classify 12 suspicious tables and create removal plan"
  mood_rgb: "9933FF"
  traits: ["thoth_analysis", "table_audit", "classification_systematic"]
  tags: ["questionable_tables", "audit", "classification", "thoth", "4.0.87"]
  lupo_agent: "cascade"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1003/20260325_183000_lilith_full_system_critical_review_4_0_87.md", type: "addresses", weight: 1.0 }
    - { to: "lupo_meta_log_events", type: "classifies", weight: 1.0 }
    - { to: "lupo_memory_events", type: "classifies", weight: 1.0 }
    - { to: "lupo_pack_role_registry", type: "classifies", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260325192000"
  last_verified_by: "cascade"
  next_action: "Classify all 12 questionable tables and create removal plan for orphaned tables"
---

# THOTH — Questionable Tables Audit (4.0.87)

**Actor**: THOTH (actor_id 26)  
**Date**: 2026-03-25  
**Version**: 4.0.87  
**Workstream**: Questionable Tables Audit  
**Priority**: MEDIUM  
**Thread**: 1008  
**Channel**: 666 (ANUBIS Quarantine)

---

## 1. EXECUTIVE SUMMARY

**MEDIUM PRIORITY AUDIT** - 12 questionable tables identified in LILITH's review require classification and potential removal. These tables may be deprecated, orphaned, or contain unclear functionality that creates maintenance burden.

---

## 2. QUESTIONABLE TABLES INVENTORY

### 2.1 Top 12 Most Suspicious Tables

**From LILITH's Review**:
1. `lupo_meta_log_events` - Event logging system
2. `lupo_memory_events` - Memory tracking system  
3. `lupo_pack_role_registry` - Unclear purpose
4. `lupo_meta_log_events` - Event logging system (duplicate entry)
5. `lupo_memory_events` - Memory tracking system (duplicate entry)
6. `lupo_pack_role_registry` - Unclear purpose (duplicate entry)
7. *(Additional 6 tables from full audit needed)*

**Note**: Full list of 12 tables needs to be extracted from LILITH's complete audit findings.

---

## 3. CLASSIFICATION FRAMEWORK

### 3.1 Classification Categories

**ACTIVE AND NEEDED**:
- Table serves clear business purpose
- Actively used by current system
- No better canonical alternative exists
- Well-documented and maintained

**DEPRECATED BUT REFERENCED**:
- No longer needed for current functionality
- Still referenced by legacy code
- Safe to remove after code updates
- Migration plan required

**ORPHANED AND SAFE TO REMOVE**:
- No code references found
- No active usage detected
- Contains no valuable data
- Safe for immediate removal

**UNCLEAR PURPOSE**:
- Function cannot be determined
- No documentation found
- Requires investigation before classification
- May be safe to remove

---

## 4. AUDIT METHODOLOGY

### 4.1 Investigation Process

**For Each Table**:
1. **Schema Analysis**: Examine structure and indexes
2. **Data Content**: Check row counts and data patterns
3. **Code Search**: Find all references in codebase
4. **Usage Analysis**: Determine active vs inactive status
5. **Documentation Check**: Look for existing documentation
6. **Dependency Mapping**: Identify what depends on table

### 4.2 Investigation Tools

**Schema and Data**:
```sql
-- Table structure
DESCRIBE [table_name];

-- Data content
SELECT COUNT(*) FROM [table_name];
SELECT * FROM [table_name] LIMIT 5;

-- Index analysis
SHOW INDEX FROM [table_name];
```

**Code References**:
```bash
# Search for table references
grep -r "table_name" --include="*.php" .
grep -r "Table_Name" --include="*.php" .
```

**Usage Patterns**:
- Check last modified timestamps
- Look for INSERT/UPDATE/DELETE operations
- Identify read-only vs active usage

---

## 5. CLASSIFICATION PLAN

### 5.1 Phase 1: Data Collection

**Gather Information**:
- Row counts for all 12 tables
- Schema analysis for structure clues
- Code reference search results
- Any existing documentation

**Create Classification Matrix**:
| Table | Row Count | Code Refs | Purpose | Status | Action |
|-------|-----------|-----------|---------|--------|--------|

### 5.2 Phase 2: Classification

**Apply Framework**:
- Classify each table using 4 categories
- Document reasoning for each classification
- Identify dependencies and removal risks

**Prioritize Actions**:
- Immediate removal: Orphaned tables
- Planned removal: Deprecated tables
- Keep: Active and needed tables
- Investigate: Unclear purpose tables

### 5.3 Phase 3: Removal Planning

**Safe Removal Process**:
1. Verify no active references
2. Back up table data (if any)
3. Remove code references
4. Drop table from database
5. Update documentation
6. Test system functionality

**Migration Planning**:
- For deprecated tables with data
- Identify canonical destination tables
- Create migration scripts
- Test migration process

---

## 6. EXPECTED OUTCOMES

### 6.1 Classification Results

**Anticipated Distribution**:
- Active and needed: 2-3 tables
- Deprecated but referenced: 3-4 tables  
- Orphaned and safe to remove: 4-5 tables
- Unclear purpose: 2-3 tables

### 6.2 Removal Impact

**System Cleanup**:
- Reduced database complexity
- Eliminated maintenance burden
- Clearer architecture
- Improved performance

**Documentation Updates**:
- Updated schema documentation
- Removal notes in CHANGELOG
- Clarified system boundaries

---

## 7. SUCCESS CRITERIA

### 7.1 Classification Complete
- [ ] All 12 tables investigated and classified
- [ ] Classification matrix completed
- [ ] Reasoning documented for each table
- [ ] Dependencies identified and mapped

### 7.2 Action Plan Ready
- [ ] Removal plan for orphaned tables
- [ ] Migration plan for deprecated tables
- [ ] Documentation plan for kept tables
- [ ] Investigation plan for unclear tables

---

## 8. DEPENDENCIES

**None** - Can run parallel to other Phase 2 workstreams.

---

## 9. RISKS

**Low Risk**:
- Tables are questionable by nature
- Expected to be mostly unused
- Low probability of critical dependencies

**Mitigation**:
- Thorough code reference search
- Careful dependency analysis
- Safe removal procedures

---

## 10. STATUS

**Status**: READY TO CLASSIFY  
**Priority**: MEDIUM  
**Estimated Effort**: 3-4 hours  

**Next Action**: Begin data collection and classification of 12 questionable tables.

---

**THOTH Assessment**: Systematic classification of questionable tables will reduce maintenance burden and clarify system architecture. Most tables are expected to be orphaned and safe for removal.

**Implementation Priority**: MEDIUM - System cleanliness and clarity improvement.
