---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.1.5/status/install_archaeology_4_1_2_to_4_1_4.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.1.5/status/install_archaeology_4_1_2_to_4_1_4.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/install-archaeology-4-1-2-to-4-1-4.toon
  atoms_toon: null
  transcript_jsonl: 0/development/install_archaeology_4_1_2_to_4_1_4
  artifact_type: documentation
  artifact_kind: report
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE
  title: Install Archaeology Report 4.1.2 to 4.1.5
  summary: Archaeological analysis of install-relevant changes from 4.1.2 to 4.1.5, including schema changes, installer flow modifications, and contradictions.
---

# Install Archaeology Report 4.1.2 to 4.1.5

## CRITICAL FINDINGS - PRE-PHASE VALIDATION

### 🚨 PRD VALIDATION VIOLATIONS

**install.php HEADER VIOLATION:**
- Current: `header_format_version: "4.1.2"`
- Required: `header_format_version: "4.1.5"`
- **SEVERITY: CRITICAL** - Violates PRD 16 + PRD 86 enforcement
- **IMPACT: install.php would be BLOCKED in strict mode**

**No PRD References Found:**
- install.php contains no explicit PRD references
- **SEVERITY: MEDIUM** - Missing doctrinal linkage

### 🚨 CANONICAL SQL ENFORCEMENT VIOLATIONS

**Non-Canonical SQL Files Detected:**
- `install/seed_lupopedia_4_1_0.sql` (legacy version)
- `database/lupopedia/mysql/seed/seed_4.1.0.sql` (legacy version)
- `database/lupopedia/mysql/seed/seed_4.1.3.sql` (version-specific)
- Multiple backup SQL files with timestamps

**Canonical SQL Files Present:**
✅ `database/lupopedia/mysql/install/install_new_lupopedia.sql`
✅ `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`
✅ `database/lupopedia/mysql/import/drop_old_crafty_syntax_tables.sql`

## TIMELINE ANALYSIS

### 4.1.2 → 4.1.3 Changes
- Header format version remained at 4.1.2
- SQL seed files versioned separately
- No major installer flow changes detected

### 4.1.3 → 4.1.5 Changes
- PRD 16 and PRD 86 enforcement added (new requirement)
- Header format version target updated to 4.1.5
- install.php header not updated (CRITICAL GAP)

## INSTALL-IMPACT vs IMPORT-IMPACT

### Install Impact
- **HIGH RISK:** install.php header version mismatch
- **MEDIUM RISK:** Legacy seed files may cause confusion
- **LOW RISK:** Backup SQL files present but unused

### Import Impact
- **LOW RISK:** Import SQL files appear canonical
- **NO IMPACT:** Import logic unchanged

## CONTRADICTIONS / STALE ASSUMPTIONS

### 1. Header Version Doctrine
- **Assumption:** install.php complies with current header format
- **Reality:** install.php stuck at 4.1.2, system requires 4.1.5
- **Risk:** Installer blocked in strict mode

### 2. Seed File Strategy
- **Assumption:** Single canonical seed file
- **Reality:** Multiple versioned seed files exist
- **Risk:** Installer may use wrong seed version

### 3. SQL File Organization
- **Assumption:** Clean canonical SQL structure
- **Reality:** Legacy files mixed with canonical files
- **Risk:** Confusion during maintenance

## RECOMMENDATIONS

### IMMEDIATE (CRITICAL)
1. Update install.php header to version 4.1.5
2. Remove or archive legacy seed files
3. Document canonical SQL file structure

### SHORT-TERM (HIGH)
1. Add PRD references to install.php
2. Clean up backup SQL files
3. Update installer documentation

### LONG-TERM (MEDIUM)
1. Implement automated header validation
2. Establish SQL file versioning strategy
3. Create installer test suite

## CONCLUSION

The install system has **CRITICAL PRD compliance issues** that must be resolved before fresh reinstall testing. The core installer logic appears sound, but doctrinal compliance failures could block installation in strict mode.

**VERDICT: NOT SAFE TO TEST until header version fixed**
