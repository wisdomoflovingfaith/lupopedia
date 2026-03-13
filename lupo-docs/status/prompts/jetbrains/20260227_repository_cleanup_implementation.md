# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\prompts\jetbrains\20260227_repository_cleanup_implementation.md"
  file_hash: "3d753d084c7060b4afca80a7c26e6ed25e0534a11e1868d82cf9e5e6414ebbf8"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE

---
lupopedia.headers:
  file_path_from_root: "prompts/jetbrains/20260227_repository_cleanup_implementation.md"
  file_hash: "525500e7dd41d9bd567cbb40559e34e80737cf042f8af64dc2d9cfa748406bb3"
  system_version: "4.0.50"
  channel_id: 42
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "implementation_prompt"
  purpose: "JetBrains IDE task implementation for repository cleanup and legacy files removal"
  dialog_message: "JetBrains: Execute comprehensive repository cleanup to remove legacy files, unnecessary migrations, and development artifacts while preserving critical documentation and active development files."
  mood_rgb: "FF6347"
  artifact_kind: "cleanup_implementation"
  traits: ["cleanup", "repository", "legacy_removal", "file_management"]
  tags: ["repository_cleanup", "legacy_files", "migration_cleanup", "jetbrains", "4.0.49"]
  lupo_agent: "jetbrains"

lupopedia.edges:
  file_path_from_root: "prompts\jetbrains\20260227_repository_cleanup_implementation.md"
  outbound_edges:
    - { to: "channels/42/tasks/active/repository_cleanup_legacy_files_removal.md", type: "implements", weight: 1.0, reason: "Task definition source" }
    - { to: "database/migrations/", type: "cleans", weight: 0.8, reason: "Migration files cleanup" }
    - { to: "audits/", type: "cleans", weight: 0.7, reason: "Audit files removal" }
    - { to: "progress_blog/", type: "cleans", weight: 0.6, reason: "Progress blog cleanup" }
    - { to: "dialogs/", type: "cleans", weight: 0.6, reason: "Dialog transcript removal" }
  semantic_tags: ["repository_cleanup", "file_management", "legacy_removal", "jetbrains"]

  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260227"
  last_verified_by: "lupopedia"
---

# JetBrains IDE Task: Repository Cleanup - Legacy Files Removal

**Task ID**: CLEANUP-2026-02-27-001  
**Assigned To**: JetBrains (1007)  
**Priority**: High  
**Estimated Time**: 6-8 hours  
**Target Version**: 4.0.49

---

## 🎯 **Mission Objective**

Execute comprehensive repository cleanup to remove irrelevant, outdated, and unnecessary files while preserving critical documentation and active development files. Focus on eliminating Lupopedia → Lupopedia migration files and cleaning up status/report files that are no longer needed.

---

## 📋 **Primary Cleanup Targets**

### 1. 🚫 **Remove Unnecessary Migration Files**
**Target**: All Lupopedia → Lupopedia migration files  
**Keep Only**: Crafty Syntax 3.7.5 → Lupopedia migration files  
**Rationale**: Doctrine states no Lupopedia → Lupopedia upgrades until 4.1.0

**Files to Remove**:
- `database/migrations/2026_*.sql` (except Crafty import)
- `database/migrations/dev_*.sql` (if obsolete)
- `migrations/*.sql` (duplicate migration directory)
- `migrations/*.php` (PHP migration scripts)

**Files to Keep** ✅:
- `database/migrations/install_new_lupopedia.sql`
- `database/migrations/seed_lupopedia.sql`
- `database/migrations/import_from_old_crafty_syntax.sql`
- `database/migrations/old_crafty_syntax_3_7_5_start.sql`

### 2. 🧹 **Clean Status/Report Files**
**Target**: Outdated status files, temporary reports, obsolete audit files

**Directories to Clean**:
- `audits/*.md` (old audit reports > 6 months)
- `progress_blog/*.md` (completed progress blogs)
- `dialogs/*.md` (old dialog transcripts)
- `docs/audit/*.md` (audit documentation)
- `docs/archive/*.md` (archived docs)

### 3. 🔧 **Remove Development Artifacts**
**Target**: Old experimental files, abandoned prototypes, obsolete configs

**Areas to Clean**:
- `channels/42/threads/DEVELOPMENT_CYCLE_4_0_4[0-6]/` (old cycles)
- `exports/*.csv` and `exports/*.json` (temporary exports)
- Temporary scripts in `scripts/`
- Obsolete tools in `tools/`

---

## 📁 **Critical Files - DO NOT REMOVE**

### ✅ **Essential Files (Protected)**
- `README.md`, `CHANGELOG.md`, `CONTRIBUTING.md`
- `docs/doctrine/`, `docs/api/`, `docs/database/`
- `config/global_atoms.yaml`, `lupo-includes/version.php`
- `install.php`, `.lupo_actor`
- `channels/42/threads/DEVELOPMENT_CYCLE_4_0_47/` and `DEVELOPMENT_CYCLE_4_0_48/`
- `channels/42/tasks/active/`
- All FLARE documentation files

---

## 🔧 **Implementation Procedure**

### **Phase 1: Preparation (30 minutes)**
1. **Backup Repository** (MANDATORY)
   ```bash
   git checkout -b backup-before-cleanup-$(date +%Y%m%d-%H%M%S)
   git add .
   git commit -m "Backup before repository cleanup"
   ```

2. **Create Cleanup Log**
   ```bash
   touch repository_cleanup_log_$(date +%Y%m%d).md
   echo "# Repository Cleanup Log - $(date)" > repository_cleanup_log_$(date +%Y%m%d).md
   ```

### **Phase 2: Migration File Cleanup (2 hours)**
1. **List and Review Migration Files**
   ```bash
   find database/migrations/ -name "*.sql" -type f | sort
   find migrations/ -name "*.sql" -type f | sort
   ```

2. **Review Each Migration File**:
   - Check if it's Crafty Syntax → Lupopedia related
   - Verify it's not referenced in current code
   - Confirm it's not documented in active docs

3. **Remove Unnecessary Migrations**:
   - Delete Lupopedia → Lupopedia migrations
   - Remove duplicate migration files
   - Keep only essential Crafty import files

### **Phase 3: Status/Report File Cleanup (2 hours)**
1. **Audit Status Directories**:
   ```bash
   ls -la audits/
   ls -la progress_blog/
   ls -la dialogs/
   ```

2. **Remove Obsolete Reports**:
   - Delete old audit reports (> 6 months)
   - Remove completed progress blogs
   - Archive or delete old dialog transcripts

### **Phase 4: Development Artifact Cleanup (2 hours)**
1. **Clean Old Development Cycles**:
   ```bash
   ls -la channels/42/threads/
   ```
   - Keep only 4.0.47 and 4.0.48 cycles
   - Remove or archive older cycles

2. **Remove Temporary Files**:
   - Clean `exports/` directory
   - Remove temporary scripts in `scripts/`
   - Clean obsolete tools in `tools/`

### **Phase 5: Final Review (1 hour)**
1. **Verify No Critical Files Removed**
2. **Update Any References to Removed Files**
3. **Commit Cleanup Changes**

---

## ⚠️ **Critical Safety Requirements**

### **MANDATORY CHECKS**:
- Manual review of EACH file before deletion
- Check for file references in code before removal
- Search for file references in documentation
- Test that key functionality still works

### **BACKUP STRATEGY**:
- Create git branch before starting
- Commit changes in logical groups
- Use descriptive commit messages

### **VERIFICATION**:
- Run `php scripts/run_tests.sh .` after cleanup
- Verify install.php still works
- Check that CHANGELOG.md references are valid

---

## 📈 **Success Criteria**

1. ✅ All Lupopedia → Lupopedia migration files removed
2. ✅ Only Crafty Syntax → Lupopedia migrations remain
3. ✅ Repository size reduced by >20%
4. ✅ No broken file references
5. ✅ All active documentation still accessible
6. ✅ Clean directory structure maintained
7. ✅ All tests still pass
8. ✅ Installation process unaffected

---

## 🔄 **Expected Commands Pattern**

```bash
# Example cleanup commands (use with caution)
# Phase 1: Migration cleanup
rm database/migrations/2026_lupopedia_to_lupopedia_v1.sql
rm database/migrations/dev_experimental_feature.sql
rm -rf migrations/

# Phase 2: Status cleanup
find audits/ -name "*.md" -mtime +180 -delete
rm -rf progress_blog/2025-*.md
rm -rf dialogs/old_transcripts/

# Phase 3: Development cleanup
rm -rf channels/42/threads/DEVELOPMENT_CYCLE_4_0_45/
rm exports/temp_*.csv
```

---

## 📝 **Documentation Requirements**

1. **Update CHANGELOG.md**: Add cleanup completion entry
2. **Update Task File**: Mark as completed with details
3. **Create Cleanup Summary**: Document what was removed
4. **Update README.md**: If any referenced files were removed

---

## 🚀 **Final Deliverables**

1. **Cleaned Repository**: Reduced size, better organization
2. **Cleanup Log**: Detailed record of all removed files
3. **Updated Documentation**: Reflects cleanup changes
4. **Task Completion**: Move task to completed directory
5. **Git Commits**: Logical, well-documented cleanup commits

---

**⚡ IMPORTANT**: This task requires careful attention to detail. When in doubt, keep the file and mark for review. Test thoroughly before committing cleanup changes.

**🔒 Remember**: We only support Crafty Syntax 3.7.5 → Lupopedia upgrades. Any Lupopedia → Lupopedia migration files are obsolete and should be removed.
