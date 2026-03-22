# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/file_count_optimization_4_1_0

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.73"
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
      objective: "Documentation for file_count_optimization_4_1_0.md"
    where:
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/file_count_optimization_4_1_0.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:39:10Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "task"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/tasks/active/file_count_optimization_4_1_0.md"
  file_hash: "cc9ddca010dffc4defcf130d8d4fce77d46c883e42703a926a066e44aa963fef"
  last_updated_utc: "20260304"
  system_version: "4.0.73"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for file_count_optimization_4_1_0.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.73"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "tasks"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/file_count_optimization_4_1_0.md", "http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/file_count_optimization_4_1_0"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# Task: File Count Optimization for 4.1.0 Deployment

**Task ID:** FILEOPT-2026-02-27-001  
**Created:** 2026-02-27 11:23 UTC  
**Assigned:** Captain (10000)  
**Priority:** High  
**Status:** Active Planning  
**Target Completion:** Version 4.1.0  
**Estimated Time:** 2-3 weeks (spread across development cycles)

## Current Status

**Remote Backup Complete:** Successfully backed up 9,994 files to remote server via FTP  
**Deployment Target:** Under 5,000 files for version 4.1.0 release  
**Reduction Required:** ~4,994 files (50% reduction)

## Objectives

- **Primary Goal:** Reduce deployed file count from 9,994 to under 5,000 by version 4.1.0
- **Secondary Goal:** Optimize directory structure for better maintainability
- **Tertiary Goal:** Establish deployment package standards for future releases

## Current File Analysis

**Total Files:** 9,994 (including backups, logs, and old files)  
**Categories Contributing to File Count:**
- Backup files and archives
- Log files (development and historical)
- Legacy migration files
- Documentation duplicates
- Test files and temporary artifacts
- Development-only assets
- Old README and status files

## Optimization Strategy

### Phase 1: Immediate Cleanup (Current 4.0.48 cycle)
- Remove backup files (*.bak, *.old, *_backup.*)
- Clean up log files older than 30 days
- Remove temporary development artifacts
- Consolidate duplicate documentation

### Phase 2: Structural Optimization (4.0.49-4.0.50)
- Remove unnecessary migration files (Lupopedia → Lupopedia)
- Consolidate legacy reference files
- Optimize image and asset organization
- Remove obsolete configuration files

### Phase 3: Deployment Package Strategy (4.0.51-4.1.0)
- Create deployment-only file list
- Exclude development-only directories
- Optimize vendor and dependency management
- Establish clean deployment baseline

## Target File Categories for Removal

### High-Impact Removals (Estimated: 2,000+ files)
```
- Backup files: *.bak, *.old, *_backup.*
- Log files: *.log, logs/ (keep recent only)
- Temporary files: temp/, lupo-tmp/, lupo-cache/
- Development artifacts: .DS_Store, Thumbs.db
- Test outputs: test_results/, coverage/
```

### Medium-Impact Removals (Estimated: 1,500+ files)
```
- Legacy migrations: lupo-database/migrations/old/
- Duplicate docs: lupo-docs/archive/, lupo-docs/old/
- Unused assets: lupo-images/unused/, old_themes/
- Development tools: lupo-tools/dev/, lupo-scripts/dev/
```

### Low-Impact Optimizations (Estimated: 1,000+ files)
```
- Consolidated documentation
- Optimized image assets
- Streamlined configuration
- Cleaned directory structure
```

## Implementation Plan

### Step 1: File Audit (Week 1)
- Run comprehensive file count analysis
- Categorize all files by purpose and necessity
- Identify files safe for removal vs. essential

### Step 2: Cleanup Execution (Week 2-3)
- Remove identified backup and temporary files
- Clean up log files and development artifacts
- Consolidate and remove duplicate documentation

### Step 3: Structural Optimization (Week 4-5)
- Remove unnecessary migration files
- Optimize asset organization
- Streamline directory structure

### Step 4: Deployment Strategy (Week 6)
- Create deployment package specification
- Establish file exclusion lists
- Test deployment package integrity

## Success Metrics

- **Primary Metric:** Total file count < 5,000
- **Secondary Metrics:**
  - Deployment package size reduction
  - Directory structure simplification
  - Maintenance overhead reduction

## Risk Mitigation

**Backup Strategy:** All files backed up to remote server before removal  
**Rollback Plan:** Maintain complete file list for recovery if needed  
**Testing:** Verify functionality after each major cleanup phase  
**Documentation:** Track removed files for future reference

## Dependencies

- [Repository Cleanup Task](repository_cleanup_legacy_files_removal.md) - Phase 1 coordination
- [Database Documentation Task](database_documentation_remaining_tables.md) - Avoid removing active docs
- FLARE Validator Enhancement - Ensure system stability

## Next Actions

1. **Immediate:** Run file count analysis by category
2. **Week 1:** Create detailed removal plan with file lists
3. **Week 2:** Begin Phase 1 cleanup (backup/temp files)
4. **Ongoing:** Track file count reduction progress

## Notes

- This is a multi-cycle task spanning several 4.0.x versions
- Focus on deployment package optimization, not development repository
- Maintain all essential functionality while reducing file count
- Coordinate with other cleanup tasks to avoid conflicts

**Progress Tracking:**
- Start: 9,994 files
- Phase 1 Target: ~8,500 files  
- Phase 2 Target: ~6,500 files
- Phase 3 Target: <5,000 files (4.1.0)
