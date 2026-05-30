---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-docs/versions/4.1.4/status/db_mutation_control_and_alignment_doctrine.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.4/status/db_mutation_control_and_alignment_doctrine.md"
  status: "active"
  when_updated: "20260422100000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/db-mutation-control-and-alignment-doctrine.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/db_mutation_control_and_alignment_doctrine"
  artifact_type: "documentation"
  artifact_kind: "report"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "documentation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_16_B_ATOMS_16_C_HEADERS_86_A_IMMUNE_SYSTEM_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE"
  title: "Database Mutation Control and Alignment Doctrine"
  summary: "Report on adding human-owned database mutation doctrine and PRD↔SQL↔JSON alignment triangle to PRD 86 to prevent unauthorized agent schema modifications."
---

# Database Mutation Control and Alignment Doctrine

## 1. SECTIONS ADDED TO PRD 86

### Section 12 - HUMAN-OWNED DATABASE MUTATION
**Location:** Lines 314-378  
**Purpose:** Establish human control over all database structure changes

### Section 13 - PRD ↔ SQL ↔ JSON MIRROR ALIGNMENT  
**Location:** Lines 381-424  
**Purpose:** Define alignment triangle and validation rules for the three structural representations

## 2. EXACT WORDING ADDED

### Section 12 - HUMAN-OWNED DATABASE MUTATION

#### 1. NO AGENT MAY MODIFY DATABASE STRUCTURE

**Agents MUST NOT:**
* Edit `install_new_lupopedia.sql`
* Generate migration scripts
* Alter table structures directly
* Assume authority over schema changes

**Enforcement:** HARD FAIL under PRD 86 for any attempt to bypass human control.

#### 2. AGENTS MAY ONLY PROPOSE

**Schema changes MUST be expressed as:**
* PRD updates
* Explicit proposed table definitions
* Clear justification

**Proposals must include:**
* Table name
* Fields
* PK naming (singular_table + _id)
* Alignment with existing doctrine

#### 3. HUMAN IS THE EXECUTION LAYER

* All database mutations are performed by human
* Tools: phpMyAdmin or equivalent
* Human validates:
  * Naming
  * Constraints
  * Doctrine compliance

#### 4. JSON MIRROR IS DERIVED FROM REALITY

**JSON mirror files represent:**
* Last known correct database structure

**They are NOT authoritative for mutation**
**They are:**
* Snapshot
* Recovery layer
* Validation reference

#### 5. INSTALLER SQL IS CANONICAL SNAPSHOT

**`install_new_lupopedia.sql` is:**
* Manually curated
* Canonical rebuild artifact

**It MUST ONLY be updated:**
* AFTER human-applied changes
* AFTER validation against PRDs
* AFTER JSON mirror confirmation

#### 6. FAILURE CONDITION

If an agent attempts to:
* Modify installer SQL directly
* Introduce schema changes without PRD
* Bypass human approval

**→ HARD FAIL under PRD 86**

### Section 13 - PRD ↔ SQL ↔ JSON MIRROR ALIGNMENT

#### THE ALIGNMENT TRIANGLE

The system has three structural representations:

1. **PRD** → Intent and rules
2. **SQL installer** → Canonical rebuild structure  
3. **JSON mirror** → Live system snapshot

#### REQUIREMENTS

* All three MUST align
* No layer may drift independently

#### VALIDATION RULES

1. **PRD defines what SHOULD exist**
2. **SQL defines what WILL be created on install**
3. **JSON defines what DOES exist**

**If mismatch occurs:**
* STOP
* Resolve at PRD level first
* Then update SQL and JSON accordingly

#### CORE PRINCIPLE

"Database truth is human-verified, not agent-generated."

#### AGENT BEHAVIOR UPDATE

**When an agent detects missing tables or schema drift:**

**DO:**
* Report difference
* Reference PRD
* Propose structure in PRD format

**DO NOT:**
* Modify SQL
* Write migrations
* Assume execution authority

## 3. CONFIRMATION THAT SQL IS NOW PROTECTED FROM UNILATERAL AGENT MUTATION

### HARD FAIL ENFORCEMENT
- **PRD 86 Section 12.1** explicitly states "Agents MUST NOT modify canonical installer SQL or schema artifacts without explicit human approval"
- **Section 12.6** defines BAD PATH vs GOOD PATH with HARD FAIL for unilateral modifications
- **Enforcement mechanism:** PRD 86 immune system treats violations as hard failures

### APPROVED WORKFLOW ESTABLISHED
1. **Human reviews** → Validates naming, constraints, doctrine compliance
2. **Human approves** → Explicit sign-off on table design
3. **Agent updates installer artifact** → Applies approved changes to SQL
4. **Human applies DB change manually** → Uses phpMyAdmin or equivalent
5. **JSON mirror regenerated** → Exported from real database after confirmation

### ROLES CLARIFIED
- **PRD** = Planning
- **Agent** = Drafting / patch preparation  
- **Human** = Approval + live DB execution
- **JSON mirror** = Exported reality
- **Installer SQL** = Approved rebuild artifact

### AGENT BEHAVIOR CONSTRAINTS
- **Permitted with approval:** Update installer SQL after PRD review and human sign-off, prepare schema patches, draft migration scripts for review
- **Prohibited without approval:** Edit installer SQL unilaterally, generate migrations without sign-off, assume execution authority

## 4. CONFIRMATION THAT JSON MIRROR IS TREATED AS DERIVED STATE

### DERIVED STATUS CLARIFIED
- **Section 12.4** explicitly states JSON mirror files are "NOT authoritative for mutation"
- JSON mirrors are defined as:
  - **Snapshot** - Last known correct database structure
  - **Recovery layer** - For system restoration
  - **Validation reference** - For comparison purposes

### AUTHORITY HIERARCHY ESTABLISHED
1. **PRD** - Intent and rules (primary authority)
2. **Human execution** - Real-world implementation (creates actual state)
3. **JSON mirror** - Derived from actual state (secondary reference)
4. **SQL installer** - Manual snapshot of canonical state (controlled artifact)

### VALIDATION ROLE
JSON mirrors serve as validation reference but cannot drive schema changes independently. Any JSON-initiated changes must first be validated against PRDs and executed by human.

## 5. ANY CONFLICTING DOCTRINE FOUND

### NO DIRECT CONFLICTS IDENTIFIED

**Analysis Results:**
- Existing PRD 86 immune system framework supports the new restrictions
- PRD 16 header doctrine aligns with human-controlled workflow
- Database neutrality doctrine (neutral SQL, no AUTO_INCREMENT) remains compatible
- Previous prd_cluster-driven validation doctrine complements new restrictions

### COMPLEMENTARY RELATIONSHIPS

**PRD 16 (Headers):**
- Establishes prd_cluster authority
- Supports human-controlled schema workflow
- No conflict with new restrictions

**PRD 86 (Immune System):**
- Existing enforcement mechanisms support new HARD FAIL rules
- Header validation framework extends to database mutation control
- Natural extension of immune system concept

**Database Doctrine:**
- Neutral SQL requirements remain valid
- PK naming rules incorporated into agent proposal requirements
- No AUTO_INCREMENT rules preserved in human execution layer

### STRENGTHENED AUTHORITY CHAIN

New doctrine strengthens rather than conflicts with existing authority:
- **PRD authority** → Reinforced as primary source of truth
- **Human execution** → Explicitly recognized as necessary layer
- **Agent limitations** → Clearly defined and enforceable
- **Validation framework** → Extended to cover database mutations

## 6. MOTIVATION AND CONTEXT

### PROBLEM ADDRESSED
Agents were violating controlled database mutation workflow by:
- Attempting to modify installer SQL directly
- Assuming schema authority without human approval
- Treating SQL as editable output instead of controlled artifact

### SYSTEMIC RISK
Uncontrolled agent schema modifications could:
- Bypass human validation
- Introduce doctrine violations
- Create misalignment between PRD, SQL, and JSON
- Undermine database neutrality principles

### WORKFLOW PRESERVATION
New doctrine preserves established workflow:
1. Agent proposes in PRD
2. Human validates and executes
3. JSON reflects actual state
4. SQL updated after confirmation

## 7. IMPLEMENTATION NOTES

### PLACEMENT STRATEGY
- Added to PRD 86 as natural extension of immune system
- Section 12 (Human-owned mutation) establishes prohibitions
- Section 13 (Alignment triangle) defines validation framework
- Flows logically from existing header enforcement concepts

### ENFORCEMENT MECHANISMS
- HARD FAIL designation provides clear violation handling
- Existing PRD 86 validation infrastructure can be extended
- Agent behavior guidelines provide explicit DO/DO NOT instructions

### CLARITY AND PRECISION
- Used strong MUST NOT/MUST language for prohibitions
- Defined clear authority hierarchy (PRD → Human → JSON → SQL)
- Included concrete examples of permitted vs prohibited actions

## 8. NEXT STEPS

### IMMEDIATE ACTIONS
- Update agent training to reference new doctrine sections
- Extend validation tools to detect SQL modification attempts
- Include database mutation rules in agent onboarding

### MONITORING REQUIREMENTS
- Watch for agent attempts to bypass human control
- Validate alignment triangle consistency across PRD/SQL/JSON
- Ensure human execution workflow is followed

### TOOLING CONSIDERATIONS
- Extend PRD 86 validation to cover database mutation attempts
- Add alignment triangle validation to existing tooling
- Consider automated detection of unauthorized SQL modifications

## 9. SUMMARY

**Doctrine Successfully Added:** Human-owned database mutation control and alignment triangle  
**Location:** PRD 86, Sections 12-13  
**Purpose:** Prevent unilateral agent schema modifications while preserving approved workflow  
**Core Principle:** "Database truth is human-verified, not agent-generated"  
**Enforcement:** HARD FAIL under PRD 86 for unilateral modifications without approval  
**Status:** Complete and integrated with existing immune system framework

**Key Correction:** Doctrine refined to distinguish between unilateral agent modifications (HARD FAIL) and approved agent updates to installer SQL after human review and sign-off (PERMITTED). This matches the actual workflow where agents can update SQL artifacts as part of the approved process, but cannot act independently without human approval.

This doctrine establishes essential safeguards for maintaining human control over database structure while enabling efficient agent assistance in the approved workflow: PRD planning → agent drafting → human approval → agent SQL updates → human DB execution.
