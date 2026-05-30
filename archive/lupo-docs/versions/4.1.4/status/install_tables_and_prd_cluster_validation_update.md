---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-docs/versions/4.1.4/status/install_tables_and_prd_cluster_validation_update.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.4/status/install_tables_and_prd_cluster_validation_update.md"
  status: "active"
  when_updated: "20260422100000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/install-tables-and-prd-cluster-validation-update.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/install_tables_and_prd_cluster_validation_update"
  artifact_type: "documentation"
  artifact_kind: "report"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "documentation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE"
  title: "Install Tables and PRD Cluster Validation Update"
  summary: "Report on adding 4 missing tables to canonical installer, updating install/wizard headers to 4.1.4, and formalizing prd_cluster-driven code validation doctrine."
---

# Install Tables and PRD Cluster Validation Update

## 1. THE 4 TABLES ADDED TO INSTALLER

### Tables Added
1. **`agent_status`** - Agent status tracking with heartbeat timestamps
2. **`operator_scratchpad`** - Temporary scratchpad content for operators with promotion flags
3. **`routing_events`** - HERMES message routing decision logging
4. **`sticky_notes`** - Digital sticky notes for channels with color coding and pinning

### Table Details
- **PK Names:** All follow RULE 93.PK_NAMING (`<singular_table_name>_id`)
- **No Foreign Keys:** Per Lupopedia doctrine
- **No Triggers/Procedures:** Per Lupopedia doctrine
- **No AUTO_INCREMENT:** Per database neutrality doctrine
- **Engine:** InnoDB with utf8mb4_unicode_ci charset

## 2. EXACT INSERTION LOCATION IN INSTALL_NEW_LUPOPEDIA.SQL

**Location:** End of file, before final `-- ============================================================================`

**Insertion Point:** Lines 4522-4570

**Section Added:** 
```
-- ============================================================================
-- SECTION 11: ADDITIONAL ORCHESTRATION TABLES (4.1.2+)
-- Tables from JSON mirror that were missing from main installer
-- PK names follow RULE 93.PK_NAMING: <singular_table_name>_id
-- ============================================================================
```

**Tables Inserted:**
- `CREATE TABLE {{prefix}}agent_status` (lines 4529-4535)
- `CREATE TABLE {{prefix}}operator_scratchpad` (lines 4538-4544)
- `CREATE TABLE {{prefix}}routing_events` (lines 4547-4557)
- `CREATE TABLE {{prefix}}sticky_notes` (lines 4560-4568)

## 3. NEW INSTALLER TABLE COUNT

**Previous Count:** 158 tables
**Added:** 4 tables
**New Count:** 162 tables

**Verification:** Installer table count now matches JSON mirror table count exactly.

## 4. INSTALL / WIZARD FILES UPDATED TO 4.1.4

### Files Updated
1. **`install.php`**
   - Previous version: "4.1.2"
   - Updated to: "4.1.4"
   - Added prd_cluster: `00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE`

2. **`lupo-install/ImportLegacyCraftySyntax.php`**
   - Previous version: "4.1.3"
   - Updated to: "4.1.4"
   - Added prd_cluster: `00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE`

3. **`lupo-install/InstallWizardHtaccessWriter.php`**
   - Previous: No lupopedia headers
   - Added complete lupopedia.headers with version "4.1.4"
   - Added prd_cluster: `00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE`

4. **`lupo-install/InstallWizardMdImporter.php`**
   - Previous: Old @wolfie.headers format
   - Replaced with lupopedia.headers format version "4.1.4"
   - Added prd_cluster: `00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE`

## 5. FILES THAT NEEDED PRD_CLUSTER CORRECTION FIRST

### Required prd_cluster Addition
All 4 files needed prd_cluster correction before header version update:

1. **`install.php`** - Missing prd_cluster field entirely
2. **`lupo-install/ImportLegacyCraftySyntax.php`** - Missing prd_cluster field entirely
3. **`lupo-install/InstallWizardHtaccessWriter.php`** - Missing lupopedia.headers entirely
4. **`lupo-install/InstallWizardMdImporter.php`** - Had old @wolfie.headers format, no prd_cluster

### prd_cluster Added
All files received the same comprehensive prd_cluster:
```
00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE
```

**Rationale:** This cluster ensures the files are governed by:
- Constitutional system requirements (00_C)
- Atoms and TOON handling (16_B)
- Header format and validation (16_C)
- Documentation architecture (26_A)

## 6. PRDS UPDATED FOR PRD_CLUSTER-DRIVEN CODE VALIDATION

### PRD 16 - Lupopedia Headers
**File:** `lupo-docs/prd/16_C_LUPOPEDIA_HEADERS.md`
**Section Added:** Section 20 - "PRD_CLUSTER-DRIVEN CODE VALIDATION"
**Page Range:** Lines 866-925

### New Doctrine Content

#### PRINCIPLE
- `prd_cluster` is not decorative; it is the declared implementation contract
- Correctness is evaluated against directions declared in `prd_cluster`

#### VALIDATION QUESTIONS
1. Do the PRDs in the cluster exist?
2. Are they current? (header_format_version = "4.1.4")
3. Do the implementation choices in the file align with those PRDs?

#### IMPLEMENTATION ALIGNMENT CRITERIA
- Material Compliance: No contradictions with cluster PRDs
- Scope Consistency: Stay within scope defined by cluster PRDs
- Constraint Adherence: Respect all constraints and requirements

#### FAILURE CONDITIONS
- Direct Contradiction: Implements forbidden behavior
- Missing Requirements: Omits required functionality
- Scope Violation: Implements outside defined scope

#### VALIDATION SCOPE CLARIFICATION
**Does NOT mean:**
- Every file must restate every rule inline
- Implementation must quote PRD text verbatim

**DOES mean:**
- The file's declared cluster is the implementation contract
- Validation is doctrine-first, not guess-first
- Material contradictions are validation failures

#### EXAMPLE VALIDATION FLOW
```
Question: "Does all the directions in the prd_cluster match what is written in this PHP file?"
Steps: Parse cluster → Validate PRDs exist/current → Extract directives → Compare implementation → Report pass/fail
```

#### ENFORCEMENT
- Strict mode enforces validation
- Material contradictions block progression
- Documentation correction takes priority over implementation changes

## 7. EXACT NEW DOCTRINE WORDING ADDED

### Section 20 - PRD_CLUSTER-DRIVEN CODE VALIDATION

**Key Wording:**
> For authored implementation files such as `.php` and `.py`, correctness is evaluated against the directions declared in the file's `prd_cluster`. The `prd_cluster` is not decorative; it is the declared implementation contract.

> **This DOES mean:**
> * The file's declared cluster is the implementation contract
> * Validation is doctrine-first, not guess-first
> * Material contradictions are validation failures

> Question: "Does all the directions in the prd_cluster match what is written in this PHP file?"

## 8. ANY AMBIGUITY FOUND

### JSON Mirror vs Installer PK Names
**Issue:** JSON mirror files still show old PK names while installer now has corrected names
**Affected Tables:** `agent_status`, `routing_events`, `sticky_notes`
**Resolution:** JSON mirror files need regeneration after database schema updates
**Status:** Documented in previous PK naming fix report

### Header Format Consistency
**Issue:** InstallWizardMdImporter.php had old @wolfie.headers format
**Resolution:** Completely replaced with proper lupopedia.headers format
**Status:** Resolved

### prd_cluster Scope
**Issue:** Determining appropriate scope for prd_cluster references
**Resolution:** Used comprehensive cluster covering constitutional requirements, atoms, headers, and documentation architecture
**Status:** Resolved with consistent cluster across all files

### Validation Implementation
**Issue:** Doctrine specifies validation criteria but doesn't implement validator logic
**Resolution:** Doctrine provides framework for future validator implementation
**Status:** Expected - this is doctrine definition, not implementation

## SUMMARY

**Tables Added:** 4 missing JSON mirror tables now in canonical installer
**Installer Count:** Increased from 158 to 162 tables (matches JSON mirror)
**Headers Updated:** 4 install/wizard files updated to 4.1.4 with proper prd_cluster
**Doctrine Formalized:** PRD 16 now includes comprehensive prd_cluster-driven code validation doctrine
**Compliance:** All changes follow Lupopedia doctrine (no FK, no triggers, PK naming, etc.)

**Impact:**
- Installer now includes all tables present in JSON mirror
- Install/wizard files have proper doctrinal alignment
- Code validation now has clear framework for prd_cluster-driven evaluation
- System ready for fresh reinstall testing with complete table set

**Next Steps:**
- Regenerate JSON mirror files to reflect corrected PK names
- Implement validator logic for prd_cluster-driven code validation
- Test fresh reinstall with complete 162-table installer
