# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

lupopedia.conditional:
  guards:
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "Table Optimization Plan - v4.0.55"
    where:
      repo_paths: ["docs\table_optimization_plan.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T10:08:31Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs\table_optimization_plan.md"
  file_hash: "e9f9c87afc319fe1af48116f965db4b3fcae72e03c0227077885792d56ae9ba6"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Table Optimization Plan - v4.0.55"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["docs", "table_optimization_planmd"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["docs\table_optimization_plan.md", "http://www.lupopedia.com/TABLE_OPTIMIZATION_PLAN"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# Table Optimization Plan - v4.0.55

**Objective**: Maintain table count under 199 (current: 155)
**Status**: 155 tables (44 under 199 ceiling) - Founder-level doctrine constraint
**Lead Agent**: Windsurf (1002)  
**Focus**: Table consolidation and design optimization  

## Current Status Analysis

**Table Count**: 155 tables current; 199 tables (MAXIMUM CAPACITY)  
**Target**: ≤218 tables (4+ table reduction required)  
**Success Criteria**: v4.0.55 success = ≤218 tables while preserving functionality  

## Table Audit Results

**Total TOON Files**: 222 (confirmed via file system scan)  
**Table Categories Identified**:
- **Logging/Audit Tables**: ~15 tables with similar structures
- **Session Management Tables**: ~8 tables with overlapping functionality  
- **Channel Metadata Tables**: ~12 tables with redundant fields
- **Cache/Temporary Tables**: ~6 tables that could be consolidated
- **System/Configuration Tables**: ~5 tables with merge potential

## Consolidation Strategy

### Phase 1: Logging Table Consolidation (Target: -3 tables)

**Merge Candidates**:
- `lupo_channel_boot_lifecycle` + `lupo_channel_boot_detail_lifecycle` → Unified with type discriminator
- `lupo_anubis_processing_log` + `lupo_anubis_recovery_attempts` → Unified audit trail
- Multiple `lupo_*_log` tables → Single `lupo_unified_log` with type field

**Implementation**:
```sql
-- Example: Unified logging table
CREATE TABLE lupo_unified_log (
    log_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    log_type ENUM('boot', 'anubis', 'channel', 'system') NOT NULL,
    log_level ENUM('debug', 'info', 'warning', 'error', 'critical') DEFAULT 'info',
    log_message TEXT NOT NULL,
    log_context JSON,
    actor_id INT DEFAULT NULL,
    created_ymdhis BIGINT NOT NULL,
    INDEX idx_log_type_created (log_type, created_ymdhis),
    INDEX idx_actor_log (actor_id, log_type)
);
```

### Phase 2: Session Table Optimization (Target: -1 table)

**Merge Candidates**:
- `lupo_sessions` + `lupo_session_metadata` → Enhanced sessions table
- Consolidate session-related temporary tables

**Implementation**:
```sql
-- Enhanced sessions table
ALTER TABLE lupo_sessions 
ADD COLUMN session_metadata JSON,
ADD COLUMN session_context TEXT;
```

### Phase 3: Channel Metadata Consolidation (Target: -1 table)

**Merge Candidates**:
- Multiple channel-specific metadata tables → Unified channel metadata
- Use JSON fields for flexible metadata storage

## Migration Plan

### Step 1: Data Migration Scripts
- Create migration scripts for each consolidation
- Implement data validation and rollback procedures
- Test on development environment first

### Step 2: Schema Updates
- Update `install_new_lupopedia.sql`
- Regenerate TOON files for consolidated tables
- Update documentation and CHANGELOG

### Step 3: Application Updates
- Update PHP code to use new unified table structures
- Modify queries to use type discriminators
- Update SystemHealthService for new schema

### Step 4: Validation
- Boot system agent testing
- ANUBIS queue processing verification
- Session management validation
- Performance benchmarking

## Risk Mitigation

**Data Loss Prevention**:
- Full database backup before migrations
- Step-by-step rollback procedures
- Data integrity validation scripts

**Functionality Preservation**:
- Comprehensive testing of all affected systems
- Backward compatibility considerations
- Performance impact assessment

## Timeline

**Today (2026-03-01)**:
- [x] Version bump to 4.0.55
- [x] Table audit completed
- [ ] Migration script development
- [ ] Phase 1 consolidation (logging tables)

**EOD Today**:
- [ ] All consolidation phases complete
- [ ] Testing and validation
- [ ] Documentation updates
- [ ] CHANGELOG.md update

## Success Metrics

**Primary**: Table count ≤218  
**Secondary**: 
- No data loss during migration
- No performance degradation
- All systems functional post-migration
- Clean, maintainable schema design

## Next Steps

1. **Immediate**: Begin Phase 1 logging table consolidation
2. **Parallel**: Develop migration scripts and validation procedures
3. **Continuous**: Update application code to use new structures
4. **Final**: Comprehensive testing and documentation

---

**Last Updated**: 2026-03-01 03:25 PM CST  
**Agent**: Windsurf (1002)  
**Status**: IN PROGRESS - Table optimization phase 1 starting
