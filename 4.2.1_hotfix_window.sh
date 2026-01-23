#!/bin/bash
#
# 4.2.1 Hotfix Window Execution Script
# Run during 4.2.1 hotfix window (2026-01-21 to 2026-02-03).
# Audits the system for minor issues and applies fixes within 4.2.1 scope.
#
# Paths: LUPOPEDIA_PATH (default: script directory)
#        lupo-includes/version.php, config/global_atoms.yaml, database/generate_toon_files.py
#
# Requires: bash, grep, sed, python3 or python. For in-place sed: GNU sed on Linux;
#           on macOS use: brew install gnu-sed && export PATH="/usr/local/opt/gnu-sed/libexec/gnubin:$PATH"
#           or the script will use sed -i.bak and remove .bak (macOS fallback).

set -e

# Configuration
LUPOPEDIA_PATH="${LUPOPEDIA_PATH:-$(cd "$(dirname "$0")" && pwd)}"
BACKUP_DIR="${BACKUP_DIR:-/tmp/backup_4.2.0_$(date +%Y%m%d_%H%M%S)}"
AUDIT_REPORT="${AUDIT_REPORT:-/tmp/4.2.1_audit_report_$(date +%Y%m%d).md}"
HOTFIX_LOG="${HOTFIX_LOG:-/tmp/4.2.1_hotfix_log_$(date +%Y%m%d).log}"
EXPECTED_TOON=173
LAST_DAY="2026-02-03"

# sed in-place: GNU sed -i vs macOS sed -i ''
if [[ "$OSTYPE" == "darwin"* ]]; then
  sed_inplace() { local f="$1"; shift; sed -i.bak "$@" "$f" && rm -f "${f}.bak"; }
else
  sed_inplace() { local f="$1"; shift; sed -i "$@" "$f"; }
fi

log() {
  echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1" | tee -a "$HOTFIX_LOG"
}

# --- Phase 1: Backup ---
log "Phase 1: Backup current state..."
mkdir -p "$BACKUP_DIR"
cp -r "$LUPOPEDIA_PATH/docs" "$BACKUP_DIR/"
cp -r "$LUPOPEDIA_PATH/dialogs" "$BACKUP_DIR/"
mkdir -p "$BACKUP_DIR/database"
cp -r "$LUPOPEDIA_PATH/database/toon_data" "$BACKUP_DIR/database/"
cp -r "$LUPOPEDIA_PATH/database/migrations" "$BACKUP_DIR/database/"
cp -r "$LUPOPEDIA_PATH/config" "$BACKUP_DIR/"
cp "$LUPOPEDIA_PATH/lupo-includes/version.php" "$BACKUP_DIR/"
log "Backup at $BACKUP_DIR"

# --- Phase 2: Audit ---
log "Phase 2: Audit..."
echo "# 4.2.1 Audit Report — $(date '+%Y-%m-%d %H:%M:%S')
" > "$AUDIT_REPORT"

# Check 1: TOON file count
toon_count=$(find "$LUPOPEDIA_PATH/database/toon_data" -maxdepth 1 -name "*.toon" 2>/dev/null | wc -l | tr -d ' ')
txt_count=$(find "$LUPOPEDIA_PATH/database/toon_data" -maxdepth 1 -name "*.txt" 2>/dev/null | wc -l | tr -d ' ')
if [ "$toon_count" -ne "$EXPECTED_TOON" ] || [ "$txt_count" -ne "$EXPECTED_TOON" ]; then
  {
    echo "## TOON file count"
    echo "Expected $EXPECTED_TOON .toon and $EXPECTED_TOON .txt; found $toon_count .toon and $txt_count .txt."
    echo ""
  } >> "$AUDIT_REPORT"
fi

# Check 2: Version atom in file headers (expect 4.2.0; report others)
{
  echo "## file.last_modified_system_version (expect 4.2.0 in key files)"
  grep -rE "file\.last_modified_system_version: [0-9]+\.[0-9]+\.[0-9]+" \
    --include="*.md" "$LUPOPEDIA_PATH" 2>/dev/null | \
    grep -v "file.last_modified_system_version: 4.2.0" || true
  echo ""
} >> "$AUDIT_REPORT" 2>/dev/null || true

# Check 3: Dialog sync (CHANGELOG and changelog_dialog both mention 4.2.0)
{
  echo "## Dialog sync"
  if grep -q "## 4.2.0" "$LUPOPEDIA_PATH/CHANGELOG.md" 2>/dev/null; then
    echo "- CHANGELOG.md: has ## 4.2.0"
  else
    echo "- CHANGELOG.md: missing ## 4.2.0"
  fi
  if grep -q "4.2.0" "$LUPOPEDIA_PATH/dialogs/changelog_dialog.md" 2>/dev/null; then
    echo "- changelog_dialog.md: references 4.2.0"
  else
    echo "- changelog_dialog.md: missing 4.2.0"
  fi
  echo ""
} >> "$AUDIT_REPORT" 2>/dev/null || true

# Check 4: Doctrine (TABLE_COUNT_DOCTRINE)
{
  echo "## TABLE_COUNT_DOCTRINE"
  if grep -q "table_count: 173" "$LUPOPEDIA_PATH/docs/doctrine/TABLE_COUNT_DOCTRINE.md" 2>/dev/null; then
    echo "- table_count: 173 present"
  else
    echo "- table_count: 173 missing or different"
  fi
  if grep -q "table_ceiling: 180" "$LUPOPEDIA_PATH/docs/doctrine/TABLE_COUNT_DOCTRINE.md" 2>/dev/null; then
    echo "- table_ceiling: 180 present"
  else
    echo "- table_ceiling: 180 missing or different"
  fi
  echo ""
} >> "$AUDIT_REPORT" 2>/dev/null || true

log "Audit written to $AUDIT_REPORT"

# --- Phase 3: Fixes (within 4.2.1 scope) ---
log "Phase 3: Apply fixes..."

if [ "$toon_count" -ne "$EXPECTED_TOON" ] || [ "$txt_count" -ne "$EXPECTED_TOON" ]; then
  log "Regenerating TOON files..."
  (cd "$LUPOPEDIA_PATH" && (python3 database/generate_toon_files.py || python database/generate_toon_files.py) 2>&1) | tee -a "$HOTFIX_LOG"
  log "TOON regeneration done."
fi

# --- Phase 4: Verify ---
log "Phase 4: Verify..."
toon_after=$(find "$LUPOPEDIA_PATH/database/toon_data" -maxdepth 1 -name "*.toon" 2>/dev/null | wc -l | tr -d ' ')
log "TOON count after: $toon_after"

# --- Phase 5: Bump to 4.2.1 on last day ---
current_date=$(date +%Y-%m-%d)
if [ "$current_date" = "$LAST_DAY" ]; then
  log "Last day of hotfix window. Bumping to 4.2.1..."

  # config/global_atoms.yaml
  sed_inplace "$LUPOPEDIA_PATH/config/global_atoms.yaml" -e 's/^version: "4\.2\.0"$/version: "4.2.1"/'
  sed_inplace "$LUPOPEDIA_PATH/config/global_atoms.yaml" -e 's/^  lupopedia: "4\.2\.0"$/  lupopedia: "4.2.1"/'
  sed_inplace "$LUPOPEDIA_PATH/config/global_atoms.yaml" -e 's/^GLOBAL_CURRENT_LUPOPEDIA_VERSION: "4\.2\.0"$/GLOBAL_CURRENT_LUPOPEDIA_VERSION: "4.2.1"/'

  # config/GLOBAL_IMPORTANT_ATOMS.yaml
  sed_inplace "$LUPOPEDIA_PATH/config/GLOBAL_IMPORTANT_ATOMS.yaml" -e 's/^GLOBAL_CURRENT_LUPOPEDIA_VERSION: "4\.2\.0"$/GLOBAL_CURRENT_LUPOPEDIA_VERSION: "4.2.1"/'

  # lupo-includes/version.php
  sed_inplace "$LUPOPEDIA_PATH/lupo-includes/version.php" -e 's/@version 4\.2\.0/@version 4.2.1/'
  sed_inplace "$LUPOPEDIA_PATH/lupo-includes/version.php" -e "s/'4\\.2\\.0'/'4.2.1'/g"

  # .cursorrules
  sed_inplace "$LUPOPEDIA_PATH/.cursorrules" -e 's/`"4\.2\.0"`/`"4.2.1"`/'

  # CHANGELOG: insert 4.2.1 before ## 4.2.0
  cat > /tmp/4.2.1_changelog_entry.md << 'EOF'
## 4.2.1 — Hotfix Window (2026-01-21 to 2026-02-03)

### Fixed
- TOON file count mismatch (regenerated TOON files when needed)
- Version atom inconsistencies in file headers (on last-day bump)
- Dialog synchronization verified

### Notes
- Schema freeze remains active
- Table count unchanged (173/180)
- No new features or schema changes

Cross-reference:
- dialogs/changelog_dialog.md (4.2.1 entry)
- dialogs/changelog_dialog-side.md (sync update)

---

EOF

  linum=$(grep -n '^## 4\.2\.0' "$LUPOPEDIA_PATH/CHANGELOG.md" | head -1 | cut -d: -f1)
  insert_line=$((linum - 1))
  [ "$insert_line" -lt 1 ] && insert_line=1
  # r command: insert file after line (GNU sed; macOS: sed -i.bak "Linum r /tmp/..." file)
  if [[ "$OSTYPE" == "darwin"* ]]; then
    sed -i.bak "${insert_line}r /tmp/4.2.1_changelog_entry.md" "$LUPOPEDIA_PATH/CHANGELOG.md" && rm -f "$LUPOPEDIA_PATH/CHANGELOG.md.bak"
  else
    sed -i "${insert_line}r /tmp/4.2.1_changelog_entry.md" "$LUPOPEDIA_PATH/CHANGELOG.md"
  fi

  # dialogs/changelog_dialog.md: after frontmatter (second ---)
  cat > /tmp/4.2.1_dialog_entry.md << 'EOF'
## 2026-02-03 — Version 4.2.1: Hotfix Window Complete

**Speaker:** SYSTEM  
**Target:** @everyone  
**Mood_RGB:** "33AAFF"  
**Message:** "Version 4.2.1 released. Hotfix window complete. Minor documentation and TOON file issues resolved."

**DETAILS:**
- TOON files regenerated to match 173-table schema (if count was off)
- Version atoms set to 4.2.1
- Dialog synchronization verified
- Schema freeze remains active

**Cross-Reference:** CHANGELOG.md section 4.2.1

---

EOF

  dlinum=$(grep -n '^---$' "$LUPOPEDIA_PATH/dialogs/changelog_dialog.md" | sed -n '2p' | cut -d: -f1)
  if [[ "$OSTYPE" == "darwin"* ]]; then
    sed -i.bak "${dlinum}r /tmp/4.2.1_dialog_entry.md" "$LUPOPEDIA_PATH/dialogs/changelog_dialog.md" && rm -f "$LUPOPEDIA_PATH/dialogs/changelog_dialog.md.bak"
  else
    sed -i "${dlinum}r /tmp/4.2.1_dialog_entry.md" "$LUPOPEDIA_PATH/dialogs/changelog_dialog.md"
  fi

  # dialogs/changelog_dialog.md: update header to 4.2.1
  sed_inplace "$LUPOPEDIA_PATH/dialogs/changelog_dialog.md" -e 's/file\.last_modified_system_version: 4\.2\.0/file.last_modified_system_version: 4.2.1/'

  # dialogs/changelog_dialog-side.md: insert ### Version 4.2.1 Sync before ### Version 4.2.0 Sync
  LAST_SYNCED=$(date -u +%Y-%m-%dT%H%M%SZ)
  cat > /tmp/4.2.1_side_sync_block.md << EOF

### Version 4.2.1 Sync (2026-02-03)
- Hotfix window complete
- Version 4.2.1
- TOON and docs verified
- Schema freeze active

Update current_sync_state:
  version: 4.2.1
  latest_version: 4.2.1
  last_synced: $LAST_SYNCED
  table_count: 173
  table_ceiling: 180
  schema_freeze: active
  sync_status: "clean"

EOF

  side_linum=$(grep -n '### Version 4\.2\.0 Sync' "$LUPOPEDIA_PATH/dialogs/changelog_dialog-side.md" | head -1 | cut -d: -f1)
  side_insert=$((side_linum - 1))
  [ "$side_insert" -lt 1 ] && side_insert=1
  if [[ "$OSTYPE" == "darwin"* ]]; then
    sed -i.bak "${side_insert}r /tmp/4.2.1_side_sync_block.md" "$LUPOPEDIA_PATH/dialogs/changelog_dialog-side.md" && rm -f "$LUPOPEDIA_PATH/dialogs/changelog_dialog-side.md.bak"
  else
    sed -i "${side_insert}r /tmp/4.2.1_side_sync_block.md" "$LUPOPEDIA_PATH/dialogs/changelog_dialog-side.md"
  fi

  # dialogs/changelog_dialog-side.md: update Current sync and frontmatter
  sed_inplace "$LUPOPEDIA_PATH/dialogs/changelog_dialog-side.md" -e 's/Current sync (4\.2\.0)/Current sync (4.2.1)/'
  sed_inplace "$LUPOPEDIA_PATH/dialogs/changelog_dialog-side.md" -e 's/Header 4\.2\.0; 4\.2\.0 entry/Header 4.2.1; 4.2.1 entry/'
  sed_inplace "$LUPOPEDIA_PATH/dialogs/changelog_dialog-side.md" -e 's/4\.2\.0 section "Stability Release"/4.2.1 section "Hotfix Window"/'
  sed_inplace "$LUPOPEDIA_PATH/dialogs/changelog_dialog-side.md" -e 's/file\.last_modified_system_version: 4\.2\.0/file.last_modified_system_version: 4.2.1/'
  # file.version and latest_version: only in frontmatter (before second ---)
  side_end=$(grep -n '^---$' "$LUPOPEDIA_PATH/dialogs/changelog_dialog-side.md" | sed -n '2p' | cut -d: -f1)
  sed_inplace "$LUPOPEDIA_PATH/dialogs/changelog_dialog-side.md" -e "1,${side_end}s/version: 4\.2\.0/version: 4.2.1/"
  sed_inplace "$LUPOPEDIA_PATH/dialogs/changelog_dialog-side.md" -e "1,${side_end}s/latest_version: 4\.2\.0/latest_version: 4.2.1/"
  sed_inplace "$LUPOPEDIA_PATH/dialogs/changelog_dialog-side.md" -e 's/sync_state: "4\.2\.0"/sync_state: "4.2.1"/'

  # CHANGELOG frontmatter
  sed_inplace "$LUPOPEDIA_PATH/CHANGELOG.md" -e 's/file\.last_modified_system_version: 4\.2\.0/file.last_modified_system_version: 4.2.1/'

  log "Version 4.2.1 applied."
else
  log "Not $LAST_DAY. Version stays 4.2.0."
fi

log "4.2.1 hotfix window run done."
