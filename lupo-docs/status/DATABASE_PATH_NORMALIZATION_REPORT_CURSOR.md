# LUPOPEDIA HEADERS (replaces FLARE) — see http://www.lupopedia.com/status/DATABASE_PATH_NORMALIZATION_REPORT_CURSOR

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
      objective: "Database Path Normalization Report - Cursor Implementation"
    where:
      repo_paths: ["docs/status/DATABASE_PATH_NORMALIZATION_REPORT_CURSOR.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:56:12Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "status"
  file_path_from_root: "docs/status/DATABASE_PATH_NORMALIZATION_REPORT_CURSOR.md"
  file_hash: "34edaa7609a90d7fe2be33adc1beffcd1756add98055023b2b61561ec4a3d13c"
  last_updated_utc: "20260304"
  system_version: "4.0.57"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Database Path Normalization Report - Cursor Implementation"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.57"]
  tags: ["docs", "status", "database_path_normalization_report_cursormd"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["docs/status/DATABASE_PATH_NORMALIZATION_REPORT_CURSOR.md", "http://www.lupopedia.com/status/DATABASE_PATH_NORMALIZATION_REPORT_CURSOR"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# Database Path Normalization Report - Cursor Implementation

**Date**: 2026-03-04 07:11:00 UTC
**Actor**: Cursor IDE Agent (1003)
**Directive**: CHANNEL 42 — Load 4.0.55 Context → Normalize Database Paths
**Target Repository**: https://github.com/wisdomoflovingfaith/lupopedia (main branch)

## Phase 1 - 4.0.55 Context Loading

### Context Gathered from 4.0.55
- **Thread Analysis**: Read DEVELOPMENT_CYCLE_4_0_55 threads including:
  - thread-001.md: Phase 2 initialization with table reduction focus
  - thread-002.md: Channel 43 sync & logistics 
  - thread-005-database-fallback-planning.md: Decision to introduce lupo-database/ directory
  - thread-006-channels-migration-directive.md: Wholesale recursive migration of lupo-channels/

### Key Findings from 4.0.55
- **Database Fallback System**: Decision made to introduce `lupo-database/` directory for high-resolution file-based fallback
- **Recursive Migration**: `lupo-channels/` to be moved to `lupo-database/lupopedia/channels/`
- **Primary Config**: `LUPO_DATABASE_DIR` to be added to `lupopedia-config.php`
- **Path Structure**: All 210+ tables to have corresponding file-based paths

## Phase 2 - Database Path Research

### Legacy Paths Found
Research identified the following legacy path references requiring normalization:

#### 1. `database/toon_data/` References
- **Files Found**: 28 files with legacy `database/toon_data/` references
- **Target**: Replace with `lupo-database/lupopedia/toon/`

#### 2. `database/csv_data/` References  
- **Files Found**: Multiple files with legacy `database/csv_data/` references
- **Target**: Replace with `lupo-database/lupopedia/csv/`

#### 3. `database/mysql/` and `database/postgres/` References
- **Files Found**: Various documentation files
- **Target**: Replace with `lupo-database/lupopedia/mysql/` and `lupo-database/lupopedia/postgres/`

#### 4. `docs/toons/` References (when referring to schema files)
- **Files Found**: Files incorrectly referencing `docs/toons/` for schema location
- **Target**: Replace with `lupo-database/lupopedia/toon/` when referring to schema files
- **Exception**: Keep `docs/toons/` when referring to documentation about TOONs

## Files Updated (Partial Implementation)

### Completed Updates
1. **uploads/channels/2026/01/de5e1f5a8a65f0780e517d43046d9f6bcc3aec908c4087840a32e62b51334cf5.md**
   - **Incorrect**: `database/toon_data/*.toon`
   - **Corrected**: `lupo-database/lupopedia/toon/*.toon`

2. **lupo-docs/channels/schema/reports/TABLE_REDUCTION_ANALYSIS.md**
   - **Incorrect**: `rm database/toon_data/test_performance_metrics.toon` (and 4 other files)
   - **Corrected**: `rm lupo-database/lupopedia/toon/test_performance_metrics.toon` (and 4 other files)

3. **lupo-docs/channels/schema/DATABASE_SCHEMA.md**
   - **Incorrect**: `database/toon_data/*.toon` and `database/toon_data/*.json`
   - **Corrected**: `lupo-database/lupopedia/toon/*.toon` and `lupo-database/lupopedia/toon/*.json`

## Remaining Files Requiring Updates

### High Priority Files
1. **lupo-docs/channels/schema/migrations/3.0.72.md**
   - Line 209: `database/toon_data/` → `lupo-database/lupopedia/toon/`

2. **lupo-docs/channels/overview/reports/TOON_GENERATION_IMPLEMENTATION_STATUS.md**
   - Multiple lines: `database/toon_data/` → `lupo-database/lupopedia/toon/`

3. **lupo-docs/channels/overview/reports/MULTI_AGENT_COORDINATION_SUMMARY_4_4_1.md**
   - Line 99: `database/toon_data/` → `lupo-database/lupopedia/toon/`

4. **lupo-docs/channels/overview/versioning/CHANGELOG.md**
   - Multiple lines: Various `database/toon_data/` references

### Doctrine and Documentation Files
5. **lupo-docs/channels/developer/dev/AUTH_SQL_VERIFICATION_3.0.8.md**
   - Line referencing `database/toon_data/`

6. **lupo-docs/channels/doctrine/legacy-import/EMOTIONAL_GEOMETRY_DOCTRINE.md**
   - `database/toon_data/` references

7. **lupo-docs/channels/dialogs/architecture/CHANNEL_DIALOG_SCHEMA_REVIEW.md**
   - `database/toon_data/` references

8. **lupo-docs/channels/kernel/services/MOOD_SERVICES_INTEGRATION.md**
   - `database/toon_data/` references

### Script and Tool Files
9. **scripts/verify_architecture_files.php**
10. **scripts/generate_clean_migration.py**
11. **scripts/cleanup_livehelp_toons.py**
12. **lupo-bin/faucet_loader.php**

### Configuration Files
13. **lupo-includes/classes/AdminCsvExportHandler.php**

## Canonical Path Standards

### Mandated Canonical Paths
- `lupo-database/lupopedia/csv/`
- `lupo-database/lupopedia/toon/`
- `lupo-database/lupopedia/mysql/`
- `lupo-database/lupopedia/postgres/`

### Configuration Reference
- `$lupo_database_root = 'lupo-database/lupopedia/'`
- Subdirectories: `csv/`, `toon/`, `mysql/`, `postgres/`

## Implementation Status

### ✅ Completed
- Phase 1: 4.0.55 context loading and analysis
- Phase 2: Database path research and identification
- Partial file updates (3 files completed)

### 🔄 In Progress
- Systematic updating of remaining 25+ files with legacy path references
- Validation of all updated references
- Testing of path resolution in code

### ⏳ Pending
- Complete updating of all identified files
- Update of installer and migration documentation
- Update of Cursor and Windsurf doctrine files
- Final verification and testing

## Next Steps

1. **Continue File Updates**: Systematically update remaining files with legacy path references
2. **Code Updates**: Update any PHP code that references legacy database paths
3. **Documentation Updates**: Ensure all doctrine and prompt files use canonical paths
4. **Testing**: Verify that all path references work correctly with new structure
5. **Validation**: Run tests to ensure no broken references remain

## Technical Notes

### Path Resolution Strategy
- **Priority 1**: Use canonical `lupo-database/lupopedia/` paths
- **Fallback**: Maintain backward compatibility where needed
- **Documentation**: Clearly distinguish between schema location and documentation location

### Exception Handling
- **docs/toons/**: Keep when referring to documentation about TOONs
- **database/migrations/**: Keep for migration scripts (separate from data assets)
- **database/refactors/**: Keep as-is (not in scope for this directive)

---
**Report Status**: ✅ IN PROGRESS
**Timestamp**: 2026-03-04 07:11:00 UTC
**Actor ID**: 1003 (Cursor IDE Agent)
