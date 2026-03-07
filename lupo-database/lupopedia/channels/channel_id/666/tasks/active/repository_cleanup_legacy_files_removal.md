# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/666/tasks/active/repository_cleanup_legacy_files_removal

---
flame.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

flare.conditional:
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
      objective: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
    where:
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/666/tasks/active/repository_cleanup_legacy_files_removal.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:39:52Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

flare.headers:
  flare.version: "1.0"
  flare.schema: "task"
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/666/tasks/active/repository_cleanup_legacy_files_removal.md"
  file_hash: "86333982713023f0fdab6e6c901137d3b0f42aa76e97ba9ec95bc1d065e3fa84"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "666", "tasks"]
  lupo_agent: "antigravity"

flare.edges:
  outbound_edges: []

flare.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

flame.see:
  mappings:
    - ["lupo-database/lupopedia/channels/lupo-channels/666/tasks/active/repository_cleanup_legacy_files_removal.md", "http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/666/tasks/active/repository_cleanup_legacy_files_removal"]

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\42\tasks\active\repository_cleanup_legacy_files_removal.md"
  file_hash: "a51657c81750bd719566072bc3cf5e73e453695d05b755ce098832abed5d0442"
  file_path_from_root: "channels\42\tasks\active\repository_cleanup_legacy_files_removal.md"
  file_hash: "55e1356e27ff73b9d84e47400729c06b660070408f4218e7581c6abb5ee62b11"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "🧹 Repository Cleanup - Legacy Files and Migration Removal"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "tasks", "active", "repository_cleanup_legacy_files_removalmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# 🧹 Repository Cleanup - Legacy Files and Migration Removal

**Task ID:** CLEANUP-2026-02-27-001  
**Channel:** 42 (FLARE Protocol Development)  
**Assigned:** JetBrains (1007)  
**Priority:** High  
**Status**: 📋 Ready for Implementation  
**Created:** 2026-02-27  
**Target Completion:** 4.0.49

---

## 🎯 **Task Overview**

Perform comprehensive repository cleanup by removing irrelevant, outdated, and unnecessary files. Focus on eliminating Lupopedia → Lupopedia migration files (since we only support Crafty Syntax → Lupopedia) and cleaning up status/report files that are no longer needed. This requires manual review of files one-by-one to ensure no critical documentation is accidentally removed.

---

## 📊 **Current Status**

**Phase**: Planning and Scoping  
**Review Required**: Manual file-by-file examination  
**Risk Level**: Medium (requires careful review to avoid removing important files)  
**Estimated Time**: 6-8 hours

---

## 🎯 **Primary Objectives**

### 1. Remove Unnecessary Migration Files
- **Target**: All Lupopedia → Lupopedia migration files
- **Keep Only**: Crafty Syntax 3.7.5 → Lupopedia migration files
- **Rationale**: Doctrine states no Lupopedia → Lupopedia upgrades until 4.1.0

### 2. Clean Status/Report Files
- **Target**: Outdated status files, temporary reports, obsolete audit files
- **Keep**: Current and relevant documentation only
- **Rationale**: Reduce repository noise and maintenance burden

### 3. Remove Legacy Development Artifacts
- **Target**: Old experimental files, abandoned prototypes, obsolete configs
- **Keep**: Only active and referenced files
- **Rationale**: Clean development environment

---

## 📁 **Target Directory Tree for Review**

```
lupopedia/
├── audits/                          # ⚠️ REVIEW - Old audit files
├── database/migrations/             # ⚠️ REVIEW - Migration files
│   ├── dev_*.sql                    # ⚠️ REVIEW - Dev migrations
│   ├── 2026_*.sql                   # ⚠️ REVIEW - 2026 migrations
│   └── *.sql                        # ⚠️ REVIEW - Other migrations
├── dialogs/                         # ⚠️ REVIEW - Old dialog files
│   ├── *.md                         # ⚠️ REVIEW - Dialog transcripts
│   └── */                           # ⚠️ REVIEW - Subdirectories
├── docs/                            # ⚠️ REVIEW - Documentation
│   ├── audit/                       # ⚠️ REVIEW - Audit docs
│   ├── archive/                     # ⚠️ REVIEW - Archived docs
│   └── */                           # ⚠️ REVIEW - Other doc dirs
├── migrations/                      # ⚠️ REVIEW - Alternative migration dir
│   ├── *.sql                        # ⚠️ REVIEW - Migration files
│   └── *.php                        # ⚠️ REVIEW - Migration scripts
├── progress_blog/                   # ⚠️ REVIEW - Progress blogs
├── channels/42/threads/            # ⚠️ REVIEW - Old threads
│   ├── DEVELOPMENT_CYCLE_4_0_*      # ⚠️ REVIEW - Old dev cycles
│   └── */                           # ⚠️ REVIEW - Other threads
├── channels/42/tasks/               # ⚠️ REVIEW - Completed tasks
│   └── */                           # ⚠️ REVIEW - Task subdirs
├── exports/                         # ⚠️ REVIEW - Export files
├── lupo-tests/                      # ⚠️ REVIEW - Test files
├── scripts/                         # ⚠️ REVIEW - Scripts
│   ├── *.py                         # ⚠️ REVIEW - Python scripts
│   ├── *.sh                         # ⚠️ REVIEW - Shell scripts
│   └── *.php                        # ⚠️ REVIEW - PHP scripts
├── tools/                           # ⚠️ REVIEW - Tools
├── *.md                             # ⚠️ REVIEW - Root markdown files
└── *.txt                            # ⚠️ REVIEW - Root text files
```

---

## 🔍 **Specific File Categories to Review**

### 🚫 **Files to Remove (Candidates)**

#### Migration Files (Lupopedia → Lupopedia)
- `database/migrations/2026_*.sql` (except Crafty import)
- `database/migrations/dev_*.sql` (if obsolete)
- `migrations/*.sql` (duplicate migration directory)
- `migrations/*.php` (PHP migration scripts)

#### Status/Report Files
- `audits/*.md` (old audit reports)
- `progress_blog/*.md` (old progress blogs)
- `dialogs/*.md` (old dialog transcripts)
- `docs/audit/*.md` (audit documentation)
- `docs/archive/*.md` (archived docs)

#### Development Artifacts
- `channels/42/threads/DEVELOPMENT_CYCLE_4_0_4[0-6]/` (old cycles)
- `channels/42/tasks/completed/` (if exists)
- `exports/*.csv` (temporary exports)
- `exports/*.json` (temporary exports)

#### Root Level Files
- Old README variants
- Temporary status files
- Obsolete configuration files

### ✅ **Files to Keep (Do NOT Remove)**

#### Critical Migration Files
- `database/migrations/install_new_lupopedia.sql` ✅
- `database/migrations/seed_lupopedia.sql` ✅
- `database/migrations/import_from_old_crafty_syntax.sql` ✅
- `database/migrations/old_crafty_syntax_3_7_5_start.sql` ✅

#### Core Documentation
- `README.md` ✅
- `CHANGELOG.md` ✅
- `CONTRIBUTING.md` ✅
- `docs/doctrine/` ✅
- `docs/api/` ✅
- `docs/database/` ✅

#### Active Development
- `channels/42/threads/DEVELOPMENT_CYCLE_4_0_47/` ✅
- `channels/42/threads/DEVELOPMENT_CYCLE_4_0_48/` ✅
- `channels/42/tasks/active/` ✅

#### Configuration
- `config/` ✅
- `lupo-config.php` ✅
- `.lupo_actor` ✅

---

## 📋 **Cleanup Procedure**

### Phase 1: Migration File Cleanup (2 hours)
1. **List all migration files**
   ```bash
   find database/migrations/ -name "*.sql" -type f
   find migrations/ -name "*.sql" -type f
   ```

2. **Review each migration file**
   - Check if it's Crafty Syntax → Lupopedia related
   - Verify it's not referenced in current code
   - Confirm it's not documented in active docs

3. **Remove unnecessary migrations**
   - Delete Lupopedia → Lupopedia migrations
   - Remove duplicate migration files
   - Keep only essential Crafty import files

### Phase 2: Status/Report File Cleanup (2 hours)
1. **Audit old status files**
   - Review `audits/` directory contents
   - Check `progress_blog/` for outdated entries
   - Examine `dialogs/` for old transcripts

2. **Remove obsolete reports**
   - Delete old audit reports (> 6 months)
   - Remove completed progress blogs
   - Archive or delete old dialog transcripts

### Phase 3: Development Artifact Cleanup (2 hours)
1. **Clean old development cycles**
   - Review `channels/42/threads/` for old cycles
   - Keep only 4.0.47 and 4.0.48 cycles
   - Remove or archive older cycles

2. **Remove temporary files**
   - Clean `exports/` directory
   - Remove temporary scripts in `scripts/`
   - Clean obsolete tools in `tools/`

### Phase 4: Final Review (1-2 hours)
1. **Verify no critical files were removed**
2. **Update any references to removed files**
3. **Commit cleanup changes**

---

## ⚠️ **Critical Warnings**

### **DO NOT REMOVE These Files:**
- `database/migrations/install_new_lupopedia.sql`
- `database/migrations/seed_lupopedia.sql`
- `database/migrations/import_from_old_crafty_syntax.sql`
- `database/migrations/old_crafty_syntax_3_7_5_start.sql`
- `config/global_atoms.yaml`
- `lupo-includes/version.php`
- `install.php`
- Any files referenced in active documentation

### **REQUIREMENTS:**
- Manual review of EACH file before deletion
- Check for file references in code before removal
- Backup repository before starting cleanup
- Commit changes in logical groups

---

## 📈 **Success Criteria**

1. ✅ All Lupopedia → Lupopedia migration files removed
2. ✅ Only Crafty Syntax → Lupopedia migrations remain
3. ✅ Repository size reduced by >20%
4. ✅ No broken file references
5. ✅ All active documentation still accessible
6. ✅ Clean directory structure maintained

---

## 🔄 **Next Steps**

1. **Backup repository** (mandatory)
2. **Begin Phase 1**: Migration file cleanup
3. **Document removed files** in cleanup log
4. **Proceed to Phase 2**: Status file cleanup
5. **Complete Phase 3**: Development artifact cleanup
6. **Final review and commit**

---

**Notes:**
- This task requires careful attention to detail
- When in doubt, keep the file and mark for review
- Update any documentation that references removed files
- Consider creating an archive branch for historical files if needed