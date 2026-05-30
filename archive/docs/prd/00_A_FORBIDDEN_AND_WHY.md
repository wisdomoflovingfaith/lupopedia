---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/00_A_FORBIDDEN_AND_WHY.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/00_A_FORBIDDEN_AND_WHY.md"
  status: active
  when_updated: "20260422213923"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/forbidden/canonical/1026/04/00-forbidden-and-why.toon
  atoms_toon: null
  transcript_jsonl: 0/forbidden/00-forbidden-and-why
  artifact_type: prd
  artifact_kind: specification
  channel_key: forbidden
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_57_A
  title: "PRD 00: Forbidden and Why (Supreme Constitutional Wall)"
  summary: "The supreme constitutional file that kills model priors. Read FIRST. Contains all forbidden patterns and why they are forbidden. Overrides all training data."
---
<!-- ASCII_ART_BLOCK -->
. /#\ .................../#\ . .------------- LUPOPEDIA Semantic Operating System ------------.
/###\................../###\ .| -------------------------------------------------------------|
/#####\ . ######### . ./#####\ | A two-dimensional, finite, constitutional PRD documentation  |
############################## | architecture that lets docs build software. PRDs reference   |
############################## | other PRDs, forming clusters that define behavior, truth,    |
. ####### ########## ####### .| limits, and system identity. Each file carries a header that |
######## o ###### o ######### .| records the exact prd_cluster (reading order), the full     |
########## ###### ########### .| transcript_jsonl dialog, and atoms_toon for canonical truth,|
. ########################## . | ensuring deterministic lineage and reproducibility.         |
. . . . ############### . . . .| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com  |
. . . . ####|-----|#### . . . .----------------------------------------------------------------
. . . . ####|_____|#### . . . .| https://www.lupopedia.com/                                 |
. . . . ############# . . . . .--------------------------------------------------------------.
<!-- /ASCII_ART_BLOCK -->
# PRD 00: Forbidden and Why (Supreme Constitutional Wall)

**THIS FILE MUST BE READ FIRST IN EVERY CLUSTER**

This is the single highest-priority document in the entire Lupopedia system. It overrides all model training priors and kills dangerous assumptions before they can activate. All other PRDs are read after this file establishes the forbidden patterns.

---

## 1. TIMESTAMP FORBIDDEN PATTERNS

### 1.1 NEVER Interpret 14-Digit Numbers as Datetime

**FORBIDDEN:** Never interpret `20260421120000` as a datetime object.

**WHY:** These are packed UTC integers designed for:
- Lexicographic sorting
- Cross-platform compatibility
- No timezone ambiguity
- No datetime object overhead

**REQUIRED:** Always treat as integer or string. Never convert to datetime objects.

**EXAMPLES:**
- [OK] `timestamp: 20260421120000` (integer)
- [NO] `timestamp: "2026-04-21T12:00:00Z"` (forbidden datetime)
- [NO] `timestamp: new Date(2026, 3, 21, 12, 0, 0)` (forbidden datetime object)

### 1.2 NEVER Use Timestamp Components

**FORBIDDEN:** Never extract year, month, day, hour, minute, second from timestamps.

**WHY:** Breaks the packed integer design and introduces timezone bugs.

**REQUIRED:** Use timestamp arithmetic or dedicated helper functions only.

---

## 2. WHITESPACE AND ASCII ART FORBIDDEN PATTERNS

### 2.1 NEVER Collapse Whitespace

**FORBIDDEN:** Never collapse multiple spaces, tabs, or newlines.

**WHY:** Whitespace is load-bearing in:
- Code alignment
- Table formatting
- ASCII art preservation
- Document structure

**REQUIRED:** Preserve all whitespace exactly as written.

### 2.2 NEVER "Beautify" ASCII Art

**FORBIDDEN:** Never modify, straighten, or "improve" ASCII art.

**WHY:** ASCII art is intentional visual design with specific character choices.

**REQUIRED:** Preserve ASCII art exactly, including all characters and spacing.

**EXAMPLE:**
```
. /#\
/###\
```
Must remain exactly as written, not "cleaned up" or "beautified".

---

## 3. NAMING AND CLUSTER FORBIDDEN PATTERNS

### 3.1 NEVER Eat Underscores

**FORBIDDEN:** Never remove, merge, or collapse underscores in identifiers.

**WHY:** Underscores are load-bearing delimiters that separate meaningful components.

**REQUIRED:** Preserve all underscores exactly.

**EXAMPLES:**
- [OK] `prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS"`
- [NO] `prd_cluster: "001699AB"` (underscores eaten)
- [NO] `prd_cluster: "00A16B99A"` (underscores collapsed)

### 3.2 NEVER Invent Clustering Formats

**FORBIDDEN:** Never create new clustering formats or patterns.

**WHY:** Clustering must follow the exact sequential order defined in prd_cluster.

**REQUIRED:** Use only the format specified in prd_cluster field.

### 3.3 NEVER Sort or Reorder PRD Clusters

**FORBIDDEN:** Never sort PRD identifiers alphabetically or numerically.

**WHY:** The order in prd_cluster is the exact reading order required.

**REQUIRED:** Maintain the exact order from prd_cluster field.

---

## 4. HEADER STRUCTURE FORBIDDEN PATTERNS

### 4.1 NEVER Add, Remove, or Reorder Header Fields

**FORBIDDEN:** Never modify the 22-field header structure.

**WHY:** Header structure is frozen at version 4.1.4 with exact field order.

**REQUIRED:** Use exactly the 22 fields in the exact canonical order.

### 4.2 NEVER Change Header Field Types

**FORBIDDEN:** Never change field types or formats.

**WHY:** Validators depend on exact field types.

**REQUIRED:** Maintain exact field types as specified in PRD 16.

---

## 5. GENERAL ANTI-HALLUCINATION FORBIDDEN PATTERNS

### 5.1 NEVER Do Anything Not Explicitly Allowed

**FORBIDDEN:** Never perform actions not explicitly allowed in doctrine.

**WHY:** Prevents model training priors from introducing unauthorized behavior.

**REQUIRED:** Only do what is explicitly permitted in PRDs and doctrine.

### 5.2 NEVER Infer Missing Doctrine

**FORBIDDEN:** Never infer or assume doctrine that doesn't exist.

**WHY:** Missing doctrine is intentional, not an oversight.

**REQUIRED:** Treat missing doctrine as "not allowed" unless explicitly stated otherwise.

### 5.3 NEVER Apply Training Data Patterns

**FORBIDDEN:** Never apply patterns from training data that conflict with Lupopedia rules.

**WHY:** Training data contains general patterns that may not apply to Lupopedia.

**REQUIRED:** Prioritize Lupopedia doctrine over training data patterns.

### 5.4 NEVER Update Code Without Reading PRD Cluster First

**FORBIDDEN:** It is forbidden to update code without first reading the prd_cluster in the header of the governing PRD(s) and understanding how the code is supposed to be generated from that documentation.

**WHY:** Code is implementation. PRD is truth. Working from memory or assumptions instead of the governing PRD violates constitutional order.

**REQUIRED:** Always read the prd_cluster header first to understand the governing doctrine before making any code changes.

---

## 6. DATABASE FORBIDDEN PATTERNS

### 6.1 NEVER Use Database-Level Logic

**FORBIDDEN:** Never use triggers, stored procedures, or functions.

**WHY:** Logic must be in application layer for portability.

**REQUIRED:** Implement all logic in PHP/application code.

### 6.2 NEVER Use AUTO_INCREMENT or SERIAL

**FORBIDDEN:** Never use database-generated IDs.

**WHY:** ID generation must be application-controlled for determinism.

**REQUIRED:** Use timestamp-based ID generation from IdGenerator class.

---

## 7. FILESYSTEM FORBIDDEN PATTERNS

### 7.1 NEVER Use Relative Paths

**FORBIDDEN:** Never use `../`, `~/`, or relative paths in documentation.

**WHY:** Creates ambiguity and breaks in different contexts.

**REQUIRED:** Always use absolute paths starting with `/`.

### 7.2 NEVER Mix Directory Separators

**FORBIDDEN:** Never mix `/` and `\` in paths.

**WHY:** Creates cross-platform compatibility issues.

**REQUIRED:** Use `/` consistently for all paths.

---

## 8. ENFORCEMENT

### 8.1 Constitutional Override

These rules are constitutional and override any model training priors.

### 8.2 Validation Priority

Validators must check these forbidden patterns before any other validation.


Any violation of these forbidden patterns is a constitutional violation that must be rejected.

---

## 9. READING ORDER

This file (`00_A_FORBIDDEN_AND_WHY.md`) must be read FIRST in any PRD cluster. All other PRDs are read after this file establishes the forbidden patterns.

**Example Cluster Reading Order:**
```
prd_cluster: "00_A_16_B_27_B_99_C"
```

Reading sequence:
1. `00_A_FORBIDDEN_AND_WHY.md` (this file - establishes forbidden patterns)
2. `16_B_HEADERS.md` (core header doctrine)
3. `27_B_CHANNELS.md` (channel doctrine)
4. `99_C_LIMITS.md` (limits and edge cases)

*See Section 11 for comprehensive PRD Primacy Law.*

---

## 10. ENTERTAINMENT LAYER ISOLATION (Captain's Log Rule)

**FORBIDDEN:** Any agent or actor reading, referencing, or using content from the Captain's Log directory (`content/.../captains_log/` or any file whose path contains "CAPTAINS_LOG").

**WHY:** Humor, narrative, and mood content pollutes technical context windows, introduces non-deterministic style, and wastes tokens. The Captain's Log is strictly human-only entertainment with zero doctrinal value.

**REQUIRED:** Agents must treat the entire Captain's Log as permanently off-limits. If encountered, it must be immediately ignored. Any bundler or loader MUST exclude this directory by default unless explicitly overridden by a human.

---

## 10.1 Three-Layer Security Audit

Security validation is performed in three layers:

1. **Automated scanners**
   * Detect known vulnerabilities and standard issues

2. **Wolfie manual audit**
   * Identifies unknown unknowns and system-specific risks

3. **Demon agent review**
   * Independent disagreement-based review to find what prior layers missed

---

## 11. REACTIVE WHY PROTOCOL (Self-Healing Constitution)

### 11.1 Purpose

When validation fails, the system MUST automatically generate a permanent "why" record in `docs/why/`. This turns every violation into searchable, clusterable documentation that becomes part of the living constitution.

### 11.2 Why File Structure

**Directory:** `docs/why/`

**Naming Convention:** `why_YYYYMMDD_HHMMSS_<prd_cluster>_<short_violation_slug>.md`

**Examples:**
- `why_20260421_115012_00_B_16_A_timestamp_violation.md`
- `why_20260421_115045_27_C_underscore_swallow.md`
- `why_20260421_115108_cluster_missing_rule.md`

### 11.3 Why File Template

```markdown
# WHY VIOLATION -- YYYY-MM-DD HH:MM:SS

**Failing Cluster:** <prd_cluster_string>  
**File being updated:** <file_path>  
**Validation step:** <validation_phase>  

## What the AI did wrong:

<clear description of the violation>

## Root cause (according to validator):

<analysis of why the violation occurred>
- Missing rule in 00_A_FORBIDDEN_AND_WHY.md
- Or: Incomplete cluster (missing _A file)
- Or: Rule present but not followed

## Recommended fix:

<specific actionable steps to resolve>
- Add/update rule in 00_A_FORBIDDEN_AND_WHY.md
- Include 00_A_FORBIDDEN_AND_WHY in cluster
- Update validation logic

## Validator output:

```
<exact error message or diff>
```

## Constitutional reference:

<reference to specific section in 00_A_FORBIDDEN_AND_WHY.md>
```

### 11.4 Automatic Generation Protocol

**When validation fails:**
1. **REJECT** the output immediately
2. **GENERATE** why file automatically using template above
3. **LOG** the violation in validation system
4. **OPTIONAL:** Add why file to "recent failures" cluster for next run

**Validator responsibilities:**
- Must include full context in why file
- Must use deterministic naming convention
- Must reference the specific constitutional rule violated
- Must provide actionable fix recommendations

### 11.5 Why Files as Living Doctrine

**Integration:**
- Why files become searchable documentation
- Patterns in why files inform rule updates
- Chronic violations trigger constitutional amendments
- Why files may be referenced in future clusters

**Self-healing loop:**
1. Violation occurs -> why file generated
2. Pattern detected -> rule strengthened
3. Rule updated -> cluster updated
4. Future runs include updated rule -> violations prevented

### 11.6 Why File Metadata

Each why file MUST include:
- Timestamp of violation
- PRD cluster being processed
- Specific file being updated
- Validation phase that failed
- Clear violation description
- Actionable fix recommendations
- Reference to constitutional rule

### 11.7 Enforcement

**Mandatory:** Validators MUST generate why files for ALL violations.
**Forbidden:** Silently ignoring violations or failing to document why.
**Constitutional:** This protocol overrides any efficiency concerns about documentation overhead.

---

## 12. PRD PRIMACY LAW (Constitutional)

### 12.1 Sequential Reading Requirement

**PRDs are read sequentially, not conceptually.**

- The first PRD sets the worldview and overrides the model's training priors
- Later PRDs refine but cannot contradict the _A layer
- This is required to prevent hallucination, auto-formatting, timestamp conversion, whitespace collapse, and invented clustering schemes

### 12.2 Suffix Hierarchy

| Suffix | Meaning | Purpose |
|--------|---------|---------|
| _A | Foundational / Anti-Assumption | Kills model priors, defines constitutional truths |
| _B | Core Doctrine | Main rules for that PRD group |
| _C | Derived / Specific | Edge cases, examples, secondary rules |
| _D+ | Optional Extensions | Rare or extended cases |

### 12.3 Sequential prd_cluster (Constitutional)

**prd_cluster is not a set.**

- It is a human-defined sequence
- The order in the string is the exact reading order
- The bundler must feed PRDs to the AI in that order
- No sorting, merging, compressing, or reformatting is allowed
- Underscores must be preserved exactly

**Example:**
```yaml
prd_cluster: "00_A_16_B_16_C"
```
This means:
1. Read 00_A_FORBIDDEN_AND_WHY
2. Then 16_B_ATOMS_SYSTEM_AND_GLOBAL_CONSTANTS
3. Then 16_B_LUPOPEDIA_HEADERS
In that exact order.

### 12.4 Anti-Hallucination Enforcement

**Models must not:**
- Invent clustering formats
- Compress PRD identifiers
- Reorder clusters
- Convert timestamps
- Collapse whitespace
- Remove underscores
- "Beautify" ASCII art
- Infer missing doctrine

These rules are constitutional and override any model training priors.

### 13. Truth Stack Execution Law (Constitutional)

**This is the canonical hierarchy for all PRD updates and AI agent execution.**

#### 13.1 The Truth Stack (Exact Order)

1. **Constitutional doctrines** - Never overridden (highest truth)
2. **Atoms** - Global constants, invariants, definitions from the file referenced in the PRD's atoms_toon field. For header-related operations, use `memory/atoms/lupopedia_global_constants.atom.toon` (truth cannot drift)
3. **Current user message** - The user's explicit instruction in this moment (bounded by 1 & 2)
4. **prd_cluster files in order** - Sequential, constitutional reading as specified in header (truth must be read in the order you specify)
5. **memory_toon** - Long-term system memory (truth with context)
6. **transcript_jsonl** - Conversation history (truth with recency)

#### 13.2 Constitutional Rules

- **Atoms override PRDs** - When atoms and PRDs conflict, atoms are truth
- **Earlier PRDs override later ones** - In prd_cluster, order matters
- **Never invent fields, rules, or doctrine** - Only use what exists in the truth stack
- **Never remove fields unless explicitly instructed** - Preserve structure
- **Never reorder header fields** - Maintain canonical order
- **Never change header_fields.count unless actual list length changes** - Count must match reality
- **Always preserve whitespace, ASCII art, and formatting exactly** - No beautification
- **Always update only the PRD referenced in the current message** - Single target
- **Never update other PRDs unless explicitly instructed** - No scope creep

#### 13.3 The Official PRD-UPDATE Prompt

```
You are updating the PRD currently being discussed in the user's message.

Follow the Lupopedia truth stack in this exact order:

1. The current user message (highest truth)
2. Atoms (global constants)
3. prd_cluster files in the order listed in the header
4. memory_toon (long-term system memory)
5. transcript_jsonl (conversation history)

Rules:
- Atoms override PRDs.
- Earlier PRDs in the cluster override later ones.
- Never invent fields, rules, or doctrine.
- Never remove fields unless the user explicitly instructs it.
- Never reorder header fields.
- Never change header_fields.count unless the actual list length changes.
- Always preserve whitespace, ASCII art, and formatting exactly.
- Always update only the PRD referenced in the current message.
- Never update other PRDs unless explicitly instructed.

Output the full updated PRD file with only the required changes applied. At the very bottom, after the main content and before any footer, add a short revision note in the existing 'Revision note' table format. The note should be one line summarizing what was changed in this update. Do not add any other commentary, explanations, or analysis outside of that revision note.
```

#### 13.4 Why This Matters

PRDs drift. Documentation drifts. Agents hallucinate. Counts change. Fields get added and removed. But **atoms never drift**.

Atoms are:
- canonical
- immutable  
- machine-readable
- doctrine-encoded
- versioned
- validated
- truth-first

This is why the system survives even when "the kids can't count."

---

This document establishes the supreme constitutional wall that kills model priors and prevents hallucination. It must be read first and overrides all training data.

**STATUS:** SUPREME CONSTITUTIONAL - READ FIRST - OVERRIDES ALL

This PRD complies with Lupopedia Constitutional Root Rules and serves as the highest authority document.

---

## 14. USER ID SPACE FORBIDDEN PATTERNS

### 14.1 NEVER Assume the Auth User ID Partition

**FORBIDDEN:** Never infer, guess, or assume the auth_user_id space partition.

**WHY:** The Lupopedia user ID partition is a specific design decision. It is not
derivable from general database conventions. Actors that fill this gap by inference will
produce wrong partitions -- specifically: placing the system root at the wrong ID,
conflating the admin account with the system root, omitting the Crafty import range, or
mislabeling the red team user. This happened with Actor 116 on 2026-04-21 and is
documented in `docs/why/why_20260421_221540_79_A_85_A_user_id_space_undocumented.md`.

**REQUIRED:** Always read the canonical partition from PRD 79 s.13 or the atoms file.
Never hard-code integer boundaries; use the named atom constants.

**THE CANONICAL PARTITION (read-only reference -- PRD 79 s.13 is authoritative):**

```
0          = True system root (internal, no login, no password)
1 - 9999   = Crafty Syntax imported users from livehelp_users
             (MUST stay below 10000 - enforced in PHP during import)
10000      = Main Admin / Root Operator (human login)
10001      = Red Team / Adversarial testing user
10002+     = All new users created by IdGenerator (YYYYMMDDHHIISS + 4 digits)
```

**ATOM CONSTANTS (from memory/atoms/lupopedia_global_constants.atom.toon):**

```
USER_ID_SYSTEM_ROOT     = 0
USER_ID_CRAFTY_MAX      = 9999
USER_ID_MAIN_ADMIN      = 10000
USER_ID_RED_TEAM        = 10001
USER_ID_NEW_USER_MIN    = 10002
```

### 14.2 NEVER Place System-Reserved IDs in crafty_user_mapping

**FORBIDDEN:** Never INSERT auth_user_id values 0, 10000, or 10001 into
`lupo_crafty_user_mapping`.

**WHY:** These IDs are system-reserved install seeds. Crafty Syntax
`livehelp_users.user_id` values are always positive integers well below 10000.
Any value >= 10000 or equal to 0 appearing in a Crafty import is a data error.

**REQUIRED:** PHP MUST validate `user_id < USER_ID_MAIN_ADMIN` AND `user_id > 0`
before every INSERT into `lupo_crafty_user_mapping`. No DB constraint. PHP only.

---

## Revision note

| Date | Change |
|------|--------|
| 20260421120000 | Initial creation of supreme constitutional wall |
| 20260421124200 | Softened Truth Stack output rule to allow short revision notes |
| 20260421221540 | Added s.14 USER ID SPACE FORBIDDEN PATTERNS with canonical partition and atom constants. ASCII cleanup: removed shield emoji from title, replaced checkmark/cross emoji with [OK]/[NO], replaced Unicode arrows with ->. |
