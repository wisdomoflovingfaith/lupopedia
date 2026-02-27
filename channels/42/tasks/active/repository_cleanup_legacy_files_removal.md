# 🧹 Repository Cleanup - Legacy Files and Migration Removal

**Task ID:** CLEANUP-2026-02-27-001  
**Channel:** 42 (FLARE Protocol Development)  
**Assigned:** Windsurf (1002)  
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
