---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "docs/prd/86_A_IMMUNE_SYSTEM_HEADER_ENFORCEMENT.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/86_A_IMMUNE_SYSTEM_HEADER_ENFORCEMENT.md"
  status: "active"
  when_updated: "20260422232349"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: "0/prd/86_immune_system_header_enforcement"
  artifact_type: "prd"
  artifact_kind: "specification"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "prd"
  prd_cluster: 00_A_16_B_16_C_86_A_57_A
  title: "PRD 86 — Immune System (Header Enforcement)"
  summary: "Defines deterministic enforcement of Lupopedia header doctrine, preventing invalid states from entering the system via validator, tests, and checkpoint gates."
---

# PRD 86 — Immune System (Header Enforcement)

## 1. PURPOSE

Define the "Immune System" concept:

* Prevent invalid header states from entering the system
* Enforce PRD 16 doctrine programmatically
* Replace human-only validation with deterministic enforcement
* Guarantee no regression (e.g. content_slug reintroduction)

## 2. SCOPE

Applies to:

* PRD files (.md)
* code files with headers (.py, .php, .js)
* validator logic
* pre-commit / checkpoint scripts
* regression test suite

## 3. CANONICAL HEADER CONTRACT (REFERENCE)

Reference PRD 16:

* exactly 22 fields
* `prd_cluster` REQUIRED
* `content_slug` REMOVED

DO NOT redefine fields — reference PRD 16 as authority.

## 4. ENFORCEMENT RULES (CRITICAL)

### HARD FAIL CONDITIONS

Must block commit / checkpoint:

* presence of:
  * content_slug
  * pk_slug
  * prd_slug
* missing required fields
* incorrect field count (≠ 22)
* incorrect field order
* invalid header format
* non-ASCII characters in header values or file content

### ASCII VALIDATION RULES

All files MUST be ASCII-safe:

* **Forbidden characters:**
  * em dash (—), en dash (–)
  * smart quotes (" ", ' ')
  * curly apostrophes (')
  * unicode slash variants (⁄, ∕)
  * ellipsis character (…)
  * non-breaking spaces ( )
  * emoji and pictographs
  * any non-ASCII punctuation

* **Parser-critical fields** (e.g., prd_cluster):
  * ASCII-only
  * single-line only
  * no tabs
  * no spaces
  * no unicode punctuation
  * reject immediately if invalid

* **Document text:**
  * ASCII-safe output required
  * sanitize before write when appropriate
  * validator may reject output containing forbidden characters

### SOFT FAIL / WARN (if any)

Only allowed for:

* legacy headers < 4.1.4 in non-strict mode (optional)
* In strict mode: legacy headers < 4.1.4 are HARD FAIL (see Section 6)
* Mixed interpretation not permitted: strict mode disallows all < 4.1.4 headers

## 5. ENFORCEMENT LAYERS

### Layer 1 — Validator

* validate_lupopedia_headers_universal.py
* must support strict mode
* must enforce canonical 22-field model

### Layer 2 — Regression Tests

* test_canonical_22_field_validation.py
* must include:
  * pass cases (valid headers)
  * fail cases (removed fields, wrong count, wrong order)

### Layer 3 — Checkpoint Gate (PRD 86 Mode)

* .bat and .sh scripts
* must:
  * run regression tests
  * run validator in strict mode
  * block on any failure

### Layer 4 — Optional Tripwire

* direct grep/findstr for removed fields
* exists as redundancy only
* MUST NOT diverge from validator logic to prevent dual-rule drift

## PRD Cluster Strict Validation (Shorthand Only)

### Purpose

Enforces strict shorthand-only format for `prd_cluster` values with no legacy support, parsing, or tolerance.

---

### STRICT VALIDATION RULE

`prd_cluster` MUST match EXACTLY:

```
^\d{2}_[A-Z](?:_\d{2}_[A-Z])*$
```

#### VALID Examples:

* 00_A
* 00_A_57_A
* 00_A_16_B_57_A

#### INVALID (MUST REJECT):

* "00_A_57_A" (quotes)
* 00_A_FORBIDDEN_AND_WHY_57_A (verbose text)
* 00_A_57_a (lowercase)
* 00A_57A (missing underscores)
* 00_A_57_A_ (trailing underscore)
* 00_A 57_A (space)
* any multiline value
* any character outside [0-9A-Z_]

---

### VALIDATOR BEHAVIOR (MANDATORY)

1. **Read as raw string** - no normalization
2. **Apply strict regex validation** - pattern above only
3. **Validate additional constraints**:
   * MUST be single line
   * MUST NOT contain: quotes ("), spaces, tabs, newline characters
4. **If ANY validation fails**:
   * FAIL immediately
   * DO NOT attempt repair
   * DO NOT attempt parsing
   * DO NOT attempt extraction

---

### ERROR HANDLING

Return EXACT error message:

```
INVALID_PRD_CLUSTER
```

No additional explanation.
No fallback behavior.

---

### REMOVED LEGACY SUPPORT

The following are DISABLED:

* verbose cluster parsing
* token extraction logic
* normalization logic
* any code that attempts to "fix" cluster strings

Validator MUST NOT mutate input.

---

### ENFORCEMENT MODE

Validator operates as:

```
validate → PASS or FAIL
```

NOT:

```
parse → fix → continue
```
* Use regex to extract tokens from mixed formats
* Ignore descriptive text
* Attempt recovery from invalid input
* Guess mappings
* Use alias tables
* Treat selector tokens as one-file aliases
* Allow legacy format support

#### Examples:

* VALID: `00_A_55_A` 
* INVALID: `00_A_FORBIDDEN_AND_WHY_55_A` → REJECT immediately

Validator MUST fail with clear error message if any rule is violated.

---

### Execution Order

1. Read `prd_cluster` 
2. Expand shorthand using PRD 84
3. Validate canonical identifiers
4. Proceed with standard header validation

## 6. STRICT MODE DEFINITION

Define clearly:

```
strict mode = no warnings
strict mode = all violations are fatal
strict mode = required for checkpoint / commit
```

## 7. STAGED FILE VS FULL REPO POLICY

Define behavior:

* pre-commit → staged files only
* checkpoint/manual → full repo scan

## 8. FAILURE BEHAVIOR

On violation:

* print clear error
* identify file + field
* exit non-zero
* block implementation only (DO NOT block validator or test execution)

## 9. REGRESSION PREVENTION

Define:

* removed fields MUST NEVER reappear
* validator + tests must include negative cases
* checkpoint gate is final authority

## 10. IMPLEMENTATION REFERENCES

Link (by path):

* scripts/validate_lupopedia_headers_universal.py
* scripts/lib/header_spec_v3_1.py
* scripts/test_canonical_22_field_validation.py
* pre-commit scripts (.bat / .sh)

## 11. PRD_CLUSTER ENFORCEMENT

### HARD FAIL ON MISSING OR OUTDATED DOCTRINE

If any PRD referenced by `prd_cluster`:

* does not exist
* or exists but has header version != "4.1.4"

then:

* HARD FAIL
* ALLOW validator and tests to run to report violations
* BLOCK implementation and progression
* require documentation work first

### ENFORCEMENT RULE

NO IMPLEMENTATION MAY PROCEED AGAINST MISSING OR OUTDATED DOCTRINE.

VALIDATION layers (validator + tests) MUST still execute to surface violations.

These errors are surfaced by validator in strict mode (see Section 6).

Error codes are part of validator taxonomy (align with PRD 16 Section 10).

Source of truth for prd_cluster semantics: **PRD 16 Section 19**.

### FAILURE OUTPUT

```
[HDR_PRD_CLUSTER_MISSING] Referenced PRD does not exist: <PRD_NAME>
[HDR_PRD_CLUSTER_OUTDATED] Referenced PRD has header_format_version != "4.1.4": <PRD_NAME>

Action required:
STOP implementation
UPDATE or CREATE PRD to v4.1.4
```

## 12. EXAMPLES

### VALID HEADER (22 fields)

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "example.md"
  web_path: "https://www.lupopedia.com/lupopedia/example.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: "0/development/example"
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS_86_A_IMMUNE_SYSTEM"
  title: "Example"
  summary: "Valid 22-field header"
---
```

### INVALID HEADER (REMOVED FIELD)

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "bad.md"
  web_path: "https://www.lupopedia.com/lupopedia/bad.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: "0/development/bad"
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS_86_A_IMMUNE_SYSTEM"
  title: "Bad Example"
  content_slug: "this-field-is-removed"  # ❌ REMOVED FIELD
---
```

### INVALID HEADER (PRD_CLUSTER VIOLATION - MISSING PRD)

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "missing_prd.md"
  web_path: "https://www.lupopedia.com/lupopedia/missing_prd.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: "0/development/missing_prd"
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS_99_NONEXISTENT_PRD"  # ❌ NONEXISTENT PRD
  title: "Missing PRD Example"
  summary: "Header referencing non-existent PRD"
---
```

### INVALID HEADER (PRD_CLUSTER VIOLATION - OUTDATED PRD)

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "outdated_prd.md"
  web_path: "https://www.lupopedia.com/lupopedia/outdated_prd.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: "0/development/outdated_prd"
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS_99_NONEXISTENT_OUTDATED_PRD"  # ❌ REFERENCES NONEXISTENT PRD WITH ASSUMED OUTDATED VERSION
  title: "Outdated PRD Example"
  summary: "Header referencing PRD with outdated header_format_version"
---
```

## 12. HUMAN-OWNED DATABASE MUTATION

### 1. AGENT AUTHORITY OVER DATABASE STRUCTURE

**Agents MUST NOT modify canonical installer SQL or schema artifacts without explicit human approval.**

**Prohibited without approval:**
* Edit `install_new_lupopedia.sql` unilaterally
* Generate migration scripts without sign-off
* Alter table structures without human review
* Assume authority over schema changes

**Permitted with explicit human approval:**
* Update installer SQL after PRD review and human sign-off
* Prepare schema patches based on approved designs
* Draft migration scripts for human review

**Enforcement:** HARD FAIL under PRD 86 for unilateral modifications without approval.

### 2. AGENTS MAY ONLY PROPOSE

**Schema changes MUST be expressed as:**
* PRD updates
* Explicit proposed table definitions
* Clear justification

**Proposals must include:**
* Table name
* Fields
* PK naming (singular_table + _id)
* Alignment with existing doctrine

### 3. APPROVED WORKFLOW

**Correct sequence:**
1. **Human reviews** → Validates naming, constraints, doctrine compliance
2. **Human approves** → Explicit sign-off on table design
3. **Agent updates installer artifact** → Applies approved changes to SQL
4. **Human applies DB change manually** → Uses phpMyAdmin or equivalent
5. **JSON mirror regenerated** → Exported from real database after confirmation

**Roles:**
* **PRD** = Planning
* **Agent** = Drafting / patch preparation  
* **Human** = Approval + live DB execution
* **JSON mirror** = Exported reality
* **Installer SQL** = Approved rebuild artifact

### 4. LIVE DATABASE MUTATION

* **Live database changes remain human-only**
* Tools: phpMyAdmin or equivalent
* Human validates final implementation before execution

### 5. JSON MIRROR IS DERIVED FROM REALITY

**JSON mirror files represent:**
* Last known correct database structure

**They are NOT authoritative for mutation**
**They are:**
* Snapshot
* Recovery layer
* Validation reference

### 6. FAILURE CONDITION

**BAD PATH (HARD FAIL):**
```
agent notices drift
→ agent patches SQL on its own
→ human sees it later
```

**GOOD PATH (PERMITTED):**
```
human reviews
→ human approves  
→ agent updates installer artifact
→ human applies DB change manually
```

**→ HARD FAIL under PRD 86 for unilateral modifications without explicit human approval**

---

## 13. PRD ↔ SQL ↔ JSON MIRROR ALIGNMENT

### THE ALIGNMENT TRIANGLE

The system has three structural representations:

1. **PRD** → Intent and rules
2. **SQL installer** → Canonical rebuild structure  
3. **JSON mirror** → Live system snapshot

### REQUIREMENTS

* All three MUST align
* No layer may drift independently

### VALIDATION RULES

1. **PRD defines what SHOULD exist**
2. **SQL defines what WILL be created on install**
3. **JSON defines what DOES exist**

**If mismatch occurs:**
* STOP
* Resolve at PRD level first
* Then update SQL and JSON accordingly

### CORE PRINCIPLE

"Database truth is human-verified, not agent-generated."

### AGENT BEHAVIOR UPDATE

**When an agent detects missing tables or schema drift:**

**DO:**
* Report difference
* Reference PRD
* Propose structure in PRD format
* Prepare installer SQL updates after human approval
* Draft migration scripts for human review

**DO NOT:**
* Modify SQL without explicit human approval
* Write migrations without sign-off
* Assume execution authority
* Update installer artifacts unilaterally
