---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/84_A_PRD_NUMBER_ALLOCATION_DOCTRINE.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/84_A_PRD_NUMBER_ALLOCATION_DOCTRINE.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/84_prd_number_allocation_doctrine.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd-number-allocation
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_84_A
  title: "PRD 84: PRD Number Allocation Doctrine"
  summary: "Defines the canonical allocation, grouping, and reserved ranges for PRD numbers 00-99."
---
# PRD 84: PRD Number Allocation Doctrine

## 1. Purpose

Define the canonical allocation, grouping, and reserved ranges for PRD numbers 00 through 99. PRD numbers are group identifiers, not unique primary keys. This doctrine ensures long-term stability, prevents collisions, and preserves the semantic meaning of PRD numbering across the Lupopedia system.

## 2. Scope

### 2.1 In Scope
- PRD number ranges
- Reserved PRD groups
- Group semantics
- Allocation rules
- Future-proofing constraints

### 2.2 Out of Scope
- Individual PRD content
- PRD header rules (PRD 16)
- Memory graph integration (PRD 38, PRD 51)
- Runtime ledger (PRD 70)

## 3. PRD Numbering Principles

1. PRD numbers are **group identifiers**, not unique IDs.
2. A PRD group may contain multiple files:
   - specification
   - guide
   - reference
3. Only one file per PRD group may have a non-null `content_id`.
4. PRD numbers MUST remain stable forever once assigned.
5. PRD numbers MUST NOT be renumbered, reused, or repurposed.

## 3.1 Anti-Normalization Doctrine

**FORCED RANGE NORMALIZATION IS EXPRESSLY FORBIDDEN.**

1. **Group Identifiers, Not Continuous Range**: PRD numbers are semantic group identifiers, not a strict continuous range system. Missing numbers are intentional and valid.

2. **Gaps Are Allowed**: Missing numbers may be reserved, deferred, or intentionally unused. Gaps do not indicate problems or require correction.

3. **No Priority Implication**: Numeric order does not imply priority, importance, or mandatory adjacency. PRD 47 is not inherently more important than PRD 46 or 48.

4. **Anchor Group Weight**: Important anchor groups (00, 16, 50, 98) are allowed to carry more semantic weight than neighboring numbers. This is intentional and valid.

5. **Renumbering Discouraged**: Renumbering existing PRDs is discouraged unless absolutely required to reduce genuine confusion. Historical numbering is preserved.

6. **Historical Growth Is Valid**: The organic, historical growth of PRD numbers is valid and should not be forcibly normalized into artificial ranges or patterns.

## PRD Cluster Selector Expansion (Shorthand Only)

### Purpose

Defines how shorthand tokens in `prd_cluster` are interpreted.

Only shorthand selector tokens are allowed in `prd_cluster`.

No other formats are supported.

---

### Format Requirement

`prd_cluster` MUST use shorthand selector tokens only.

Format:
* `NN_X` repeated with underscores
* Example: `00_A_55_A_16_C` 

Where:
* `NN` = exactly two digits (00–99)
* `X` = exactly one uppercase letter (A–Z)

### No Mixed Formats

`prd_cluster` MUST NOT contain:
* descriptive text
* extra words
* mixed formats

Examples:
* VALID: `00_A_55_A` 
* INVALID: `00_A_FORBIDDEN_AND_WHY_55_A` 

If anything other than selector tokens exists → REJECT

---

### Selector Rule

A shorthand token of the form:

`NN_X` 

represents all PRD files whose filename begins with:

`docs/prd/NN_X*` 

Examples:

* `00_A` → all files matching `docs/prd/00_A*` 
* `16_C` → all files matching `docs/prd/16_C*` 
* `55_A` → all files matching `docs/prd/55_A*` 

---

### Expansion Rule

A cluster such as:

`00_A_16_C_55_A` 

is evaluated as:

1. Expand `00_A` 
2. Expand `16_C` 
3. Expand `55_A` 
4. Concatenate results in that order

---

### Deterministic Expansion

Expansion MUST be deterministic.
Use stable lexicographic filename order for all matches.
If selector expansion matches no files, the selector is INVALID.

---

### No Alias Mapping

* Shorthand tokens are NOT mapped to a single file
* No one-to-one alias registry exists
* PRD 84 MUST NOT define alias mappings
* No registry table is created or maintained

---

### Failure Conditions

A selector token is INVALID if:

* it matches no files
* it expands ambiguously without deterministic ordering
* it violates token format rules defined in PRD 16
* it contains forbidden characters or content
* it contains descriptive text or mixed formats

---

### Validation Dependency

Validators (PRD 86) MUST:

1. reject any cluster not matching shorthand format
2. parse selector tokens
3. expand them deterministically
4. validate the resulting read set
5. reject any alias mapping attempts

---

## 4. Reserved PRD Groups

### 4.1 Permanent Reservations
- **98**: Captain's Log and WHY documents
- **99**: Limits for Everything and Why

These groups are immutable and MUST NOT be used for any other purpose.

### 4.2 Structural Reservations
- **00**: Constitutional Root Requirements
- **01-09**: Core identity, federation, and system foundations

These groups define the system's constitutional and foundational layers.

## 5. Canonical Allocation Blocks

### 5.1 Block 10-19: Core Systems and Operations
Identity, analytics, tasks, operations, departments, and system maintenance.

### 5.2 Block 20-29: Navigation, UI, and Project Structure
Navigation architecture, onboarding, documentation architecture, project layout.

### 5.3 Block 30-39: Coordination, Memory, and Semantic Systems
Channel usage, actor authority, federation network, semantic systems.

### 5.4 Block 40-49: Runtime, Ledger, and Multi-Agent Coordination
This block is dedicated to runtime systems, agent coordination, and ledger operations.

Current and proposed assignments:
- 40: Versioning Doctrine
- 41: Wolfie Identity
- 42: Truth Tables
- 43: Trust Ladder
- 44: Session Config
- 45: Template-First UI Workflow
- 46: Actor Gateway Types
- 47: Runtime Ledger Extensions (reserved)
- 48: Multi-Agent Scheduling (reserved)
- 49: Actor State Machine (reserved)

### 5.5 Block 50-59: Memory Graph, Runtime Guard, and System Enforcement
Memory graph authority, runtime guard, compliance, probe harness, transcript filter.

### 5.6 Block 60-69: Orchestration, Consolidation, and Mobile Suite
Scheduler, consolidation compiler, mobile app suite.

### 5.7 Block 70-79: Data Model, Runtime Directory, Install Doctrine
Data model, runtime directory structure, install seed doctrine, database doctrine.

### 5.8 Block 80-89: Testing, Validation, and Semantic Compression
This block is dedicated to testing, validation, and compression systems.

Current and proposed assignments:
- 80: Database Design Doctrine
- 82: Hermes Routing Gateway
- 83: Memory TOON Doctrine
- 84: PRD Number Allocation Doctrine (this file)
- 85: Memory TOON Validators (reserved)
- 86: Runtime Ledger Validators (reserved)
- 87: Multi-Agent Test Harness (reserved)
- 88: Semantic Drift Detection (reserved)
- 89: Compliance Test Suite (reserved)

### 5.9 Block 90-97: Governance, Audit, and Compliance
Governance, audit, schema protection, memory integrity, federation compliance.

## 6. Allocation Rules

1. New PRDs MUST be assigned to the correct block based on topic.
2. PRD numbers MUST NOT be reused or reassigned.
3. PRD numbers MUST NOT exceed 99.
4. PRD 100 MUST NEVER exist.
5. PRD 98 and 99 MUST remain permanently reserved.
6. **Anti-Normalization Compliance**: All allocations MUST respect the Anti-Normalization Doctrine (Section 3.1). Forced range filling or gap elimination is forbidden.

## 7. Future-Proofing Constraints

- PRD blocks MUST remain stable across major versions.
- Reserved PRD groups MUST NOT be overwritten.
- New PRDs MUST include a justification for their block placement.
- PRD_INDEX MUST reflect all assignments.
- **Historical Integrity**: The organic historical development of PRD numbering MUST be preserved. No retroactive reorganization or normalization is permitted.

## 8. Cross-References

- PRD 16: Header Doctrine
- PRD 38: Memory Unification
- PRD 51: Memory Graph Authority
- PRD 70: Runtime Directory Structure
- PRD 83: Memory TOON Doctrine
- PRD_INDEX.md

---
This output complies with Lupopedia Constitutional Root Rules.
