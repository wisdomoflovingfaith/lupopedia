---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/86_A-i_IMMUNE_SYSTEM_HEADER_ENFORCEMENT.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/86_A-i_IMMUNE_SYSTEM_HEADER_ENFORCEMENT.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/prd/86_immune_system_header_enforcement
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_16_B-i_16_C-i_86_A-i_57_A-i
  title: PRD 86 - Immune System (Header Enforcement)
  summary: Defines deterministic enforcement of Lupopedia header doctrine, preventing invalid states from entering the system via validator, tests, and checkpoint gates.
---
<!-- ASCII_ART_BLOCK -->
. . . . . . . . ._________________ LUPOPEDIA Semantic Operating System _______________
. ./ \ ` ` `_-\ . | A four-axis, finite, constitutional PRD documentation architecture 
. '/| \-''-/_ / . | that lets docs build software. PRDs reference other PRDs, forming 
. { . , . , . ,\ .| clusters that define behavior, truth, limits, and system identity
. / . , . , . , \ | through positional priority (array index = reading order),
./ , . "O. |"O. } | significance weight (A–F letter), grouping (numeric category), and 
_| . , . , \ \ ;. | chronology (Roman numeral = time created).
. '\. . , . \ \'. | Each file carries a header that records the exact
.. '\_ . , . \__\ | four-axis prd_cluster (order, weight, and time created), the full
., , ''-_ , {\__/}| transcript_jsonl dialog, and atoms_toon for canonical truth,
. . , . / '-.____'| ensuring deterministic lineage and reproducibility. 
., , /. _ _ . -_ -| https://www.lupopedia.com/
.. , _'___________| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com
___-' __________________________________________________________________
<!-- /ASCII_ART_BLOCK -->

<!--HUMAN_SEMANTIC -->
This file belongs to:
- PRD Group 86 (Immune System Enforcement)
- Channel: development
- Trust tier: canonical

Defines:
- header validation rules
- enforcement layers
- strict mode behavior
- failure handling

See also:
- PRD 16 (Header Doctrine)
- PRD 38 (Memory Unification)
- PRD 49 (Questions System)
- PRD 83 (Memory TOON Doctrine)
<!-- /HUMAN_SEMANTIC -->

# PRD 86 - Immune System (Header Enforcement)

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## 1. PURPOSE

Define the "Immune System" concept:

* Prevent invalid header states from entering the system
* Enforce PRD 16 doctrine programmatically
* Replace human-only validation with deterministic enforcement
* Guarantee no regression (e.g. content_slug reintroduction)

## No Guessing Enforcement

The system MUST enforce deterministic resolution of all header pointers.

The system MUST NOT:

- infer file paths
- scan directories for discovery
- guess missing references
- construct alternate pointer targets

If any header pointer cannot be resolved:

```text
STOP
REPORT "DOCTRINE NOT FOUND"
```

## Three-Part Preamble Enforcement (4.1.7)

For PRD and canonical artifacts:

The file MUST begin with:

1. YAML frontmatter (`---`)
2. `ASCII_ART_BLOCK`
3. `HUMAN_SEMANTIC`

### Violations

- missing `---` -> ERROR
- malformed YAML -> ERROR
- malformed ASCII block -> ERROR (strict mode)
- malformed HUMAN block -> ERROR (strict mode)

On any preamble structure violation above:

STOP

REPORT "HDR_PREAMBLE_STRUCTURE_VIOLATION"

### ASCII Protection

AI agents MUST NOT:

- modify ASCII_ART_BLOCK
- reformat spacing
- regenerate content

STOP

REPORT "HDR_ASCII_ART_IMMUTABLE_VIOLATION"

### HUMAN_SEMANTIC Protection

HUMAN_SEMANTIC MUST NOT:

- introduce new facts
- contradict YAML
- redefine system behavior

STOP

REPORT "HDR_HUMAN_SEMANTIC_VIOLATION"

## PRD Cluster Enforcement

`prd_cluster` MUST:

- use canonical NN_X-i shorthand format only (PRD 16)
- reference valid PRDs
- follow declared ordering

The system MUST reject:

- full-name PRD cluster formats
- malformed cluster strings
- references to deprecated PRDs

STOP

REPORT "HDR_PRD_CLUSTER_ENFORCEMENT_VIOLATION"

## transcript_jsonl Enforcement (4.1.7)

transcript_jsonl is append-only.

Agents MAY append to transcript_jsonl.

Agents MUST NOT read transcript_jsonl unless explicitly required by governing PRD logic.

Violation occurs if:

- transcript_jsonl is read without explicit PRD requirement
- transcript_jsonl is used for inference
- transcript_jsonl is treated as implicit context

If violation detected:

STOP

REPORT "HDR_TRANSCRIPT_POLICY_VIOLATION"

Full normative rules for transcript behavior live in this section only. Elsewhere in this PRD: see **transcript_jsonl Enforcement (4.1.7)**.

### Strict Mode (Final)

Strict mode MUST:

- reject malformed headers
- enforce pointer presence ONLY when required by PRD policy
- reject missing required pointers
- allow null optional pointers
- reject authority violations
- reject path violations
- reject guessing
- reject read-order violations

Strict mode MUST STOP execution.

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

DO NOT redefine fields - reference PRD 16 as authority.

## 4. ENFORCEMENT RULES (CRITICAL)

### Header Version Policy (4.1.x)

The immune system MUST enforce compatibility with the active 4.1.x policy.

Strict mode MUST reject:

- deprecated versions (< 4.1.5)
- invalid schemas
- legacy-field violations

Strict mode MUST NOT require exact equality with any single 4.1.x version.

### HEADER READING ORDER ENFORCEMENT (4.1.7)

The immune system MUST enforce the header interpretation order defined in PRD 16.

PRD 86 MUST NOT redefine reading order.

Violations are HARD FAIL:

reading memory_toon before prd_cluster
reading atoms_toon after memory_toon
using transcript_jsonl as implicit context

### HARD FAIL CONDITIONS

Must block commit / checkpoint:

* presence of:
  * content_slug
  * pk_slug
  * prd_slug
* missing required fields
* incorrect field count (- 22)
* incorrect field order
* invalid header format
* ASCII scope violations in header fields or ASCII_ART_BLOCK (see ASCII enforcement scope below)
* nondeterministic pointer resolution
* guessing pointer targets from filesystem scans or inferred paths
* treating `transcript_jsonl` as implicit required context (see **transcript_jsonl Enforcement (4.1.7)**)

### POINTER RESOLUTION ENFORCEMENT (4.1.7)

The immune system MUST enforce deterministic pointer behavior from PRD 16.

Required:

* Pointer fields resolve from explicit header values only
* Resolution is deterministic and reproducible
* Transcript behavior: see **transcript_jsonl Enforcement (4.1.7)**

Forbidden:

* path inference
* directory scanning for discovery
* guessed fallback pointers
* implicit transcript context loading

### AUTHORITY ENFORCEMENT (4.1.7)

The immune system MUST enforce the 4.1.7 authority hierarchy:

- `prd_cluster` defines governing doctrine
- `atoms_toon` defines canonical truth constraints
- `memory_toon` provides contextual expansion only
- `questions_toon` surfaces unresolved uncertainty only
- `transcript_jsonl`: see **transcript_jsonl Enforcement (4.1.7)**

`memory_toon` MUST NOT:

- influence execution behavior
- alter decision logic
- bias deterministic resolution

`memory_toon` is context only

The following are HARD FAIL:

- `memory_toon` overriding `atoms_toon`
- `memory_toon` overriding `prd_cluster`
- `questions_toon` overriding canonical truth
- `transcript_jsonl` being treated as implicit required context (see **transcript_jsonl Enforcement (4.1.7)**)

### memory_toon Path Validation (PRD 38)

If `memory_toon` is NOT null:

- path MUST match:
  `memory/{tier}/{channel_key}/{thread}/{YYYY}/{MM}/`
- HARD FAIL if invalid (wrong order, missing tier, missing thread, non-deterministic path)

If `memory_toon` is null:

- skip path validation
- do NOT fail

Directory scanning -> HARD FAIL

### ASCII enforcement scope

Header fields:
- MUST be ASCII-only
- HARD FAIL if violated

ASCII_ART_BLOCK:
- MUST remain ASCII
- HARD FAIL if modified

Document body:
- MAY contain extended characters
- MUST NOT cause validation failure
- validator MAY warn but MUST NOT reject

Rule:

ASCII enforcement MUST be deterministic and scoped.
Validator MUST NOT reject valid documents based on body text encoding.

### ASCII_ART_BLOCK ENFORCEMENT (4.1.7)

For 4.1.7 authored PRD and canonical artifacts, `ASCII_ART_BLOCK` is immutable visual identity.

The immune system MUST reject:

- modification of ASCII art characters
- whitespace normalization inside `ASCII_ART_BLOCK`
- regeneration or replacement of `ASCII_ART_BLOCK`

Violation is HARD FAIL in strict mode.

`ASCII_ART_BLOCK` is human-readable doctrine encoding and visual identity.

ASCII content MUST NOT be semantically parsed.

Validator MAY:

- detect block boundaries
- verify immutability
- validate structure

Validator MUST NOT:

- interpret ASCII content
- derive meaning from ASCII

ASCII MUST NOT affect execution or influence header interpretation beyond structural preamble presence.

ASCII is human-readable only for operators; it is not a machine authority surface.

### HUMAN_SEMANTIC ENFORCEMENT (4.1.7)

For 4.1.7 authored PRD and canonical artifacts, `HUMAN_SEMANTIC` is advisory human context only.

The immune system MUST reject any `HUMAN_SEMANTIC` block that:

- introduces new facts not present in the YAML header or governing PRD cluster
- contradicts YAML header values
- redefines system behavior
- overrides `prd_cluster`
- overrides `memory_toon`
- overrides `atoms_toon`
- overrides `transcript_jsonl`
- overrides `questions_toon`
- infers missing data

Violation is HARD FAIL in strict mode.

### questions_toon Enforcement

HARD FAIL if:

- used as authority
- overrides prd_cluster or atoms_toon
- traversed without bounds

Traversal MUST be:

- explicit
- bounded
- deterministic

### Pointer Optionality Rule

For canonical PRDs:

- Pointer requirements are governed by PRD policy.
- For 4.1.7:
- atoms_toon MAY be null unless explicitly required by governing PRD
- memory_toon MAY be null unless explicitly required by governing PRD
- questions_toon MAY be null
- transcript_jsonl MUST exist

Validator MUST:

- enforce presence only when required by PRD policy
- NOT assume non-null as default requirement

### thread_id Contract

thread_id MUST be:
- BIGINT OR null

Validator MUST NOT assume format beyond presence/type

### Channel Boundary Rule

Channel boundary MUST be enforced at the runtime layer only.

Validator MAY:

- verify presence of `channel_key` in the header when policy requires it

Validator MUST NOT:

- attempt to validate runtime access behavior (no session, actor, or live scope in the validator layer)

Runtime system MUST enforce:

- no cross-channel reads
- no cross-channel writes
- no cross-channel memory traversal

Violation:

STOP
REPORT "HDR_CHANNEL_BOUNDARY_VIOLATION"

No exceptions at runtime.

### Role-Based Boundary Enforcement

All agents MUST operate within:

* assigned channel_key
* assigned thread_id
* assigned role

Violations:

Cross-channel (runtime):
STOP
REPORT "HDR_CHANNEL_BOUNDARY_VIOLATION"

Watcher violation (mutate or trigger when role is Watcher):
STOP
REPORT "AGENT_ROLE_VIOLATION_WATCHER"

Messenger violation (reinterpret or modify meaning when role is Messenger):
STOP
REPORT "AGENT_ROLE_VIOLATION_MESSENGER"

Censer violation (bypass validation or fail to enforce deterministic traversal when role is Censer):
STOP
REPORT "AGENT_ROLE_VIOLATION_CENSER"

Reaper violation (alter canonical memory or bypass validation when role is Reaper):
STOP
REPORT "AGENT_ROLE_VIOLATION_REAPER"

Context missing:
STOP
REPORT "AGENT_CONTEXT_UNDEFINED"

Role Source Rule:

Agent role MUST be provided by:

- blueprint metadata OR
- execution context

The immune system MUST reject:

- inferred roles
- default roles not defined by PRD policy
- roles derived from memory graph

Violation:

STOP
REPORT "AGENT_ROLE_UNDEFINED"

### MEMORY AND ANALYTICS AUTHORITY ENFORCEMENT (4.1.7)

The immune system MUST reject any behavior where:

- memory graph is treated as authority
- analytics define truth
- path/referrer analytics override PRD doctrine
- heuristics override `prd_cluster`
- heuristics override `atoms_toon`
- memory-derived classifications are invented outside PRD 82 semantics

Analytics-derived edges MUST remain:

- non-authoritative
- low-trust
- suggestion-only

### prd_cluster Validation

HARD FAIL if:

- not canonical shorthand
- malformed
- references invalid PRDs

### Atomization Enforcement

HARD FAIL if:

- header execution creates atoms
- header execution infers atoms

Atoms only formed via PRD 16_B rules.

### SOFT FAIL / WARN (if any)

Only allowed for:

* compatibility checks against the active PRD 16 version acceptance policy
* in strict mode: reject deprecated version families
* in strict mode: reject versions below the supported enforcement floor
* in strict mode: reject legacy-field violations and invalid schemas

## 5. ENFORCEMENT LAYERS

### Layer 1 - Validator

* validate_lupopedia_headers_universal.py
* must support strict mode
* must enforce canonical 22-field model

### Layer 2 - Regression Tests

* test_canonical_22_field_validation.py
* must include:
  * pass cases (valid headers)
  * fail cases (removed fields, wrong count, wrong order)

### Layer 3 - Checkpoint Gate (PRD 86 Mode)

* .bat and .sh scripts
* must:
  * run regression tests
  * run validator in strict mode
  * block on any failure

### Layer 4 - Optional Tripwire

* direct grep/findstr for removed fields
* exists as redundancy only
* MUST NOT diverge from validator logic to prevent dual-rule drift

## PRD Cluster Strict Validation (NN_X-i)

### Purpose

Enforces strict `NN_X-i` token format for `prd_cluster` values per PRD 16. No legacy verbose clusters, no `NN_X` selector tokens, no expansion, and no tolerance for glob or discovery.

---

### STRICT VALIDATION RULE

`prd_cluster` MUST match EXACTLY:

```
^\d{2}_[A-Z]-[ivx]+(?:_\d{2}_[A-Z]-[ivx]+)*$
```

#### VALID Examples:

* 00_A-i
* 00_A-i_57_A-i
* 00_A-i_16_C-i_57_A-i

#### INVALID (MUST REJECT):

* "00_A-i_57_A-i" (quotes)
* 00_A_FORBIDDEN_AND_WHY_57_A-i (verbose text)
* 00_A-I_57_A-i (uppercase roman suffix)
* 00A-i_57A-i (missing underscores)
* 00_A-i_57_A-i_ (trailing underscore)
* 00_A-i 57_A-i (space)
* any multiline value
* any string that fails the strict regex above (including characters not allowed by that pattern)

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
validate - PASS or FAIL
```

NOT:

```
parse - fix - continue
```

#### Examples:

* VALID: `00_A-i`
* VALID: `00_A-i_57_A-i`
* VALID: `00_A-i_16_C-i_57_A-i`
* INVALID: `00_A_55_A` (missing `-i` token shape; `NN_X` selector form is forbidden)
* INVALID: `00_A_FORBIDDEN_AND_WHY_55_A-i` - REJECT immediately

Validator MUST fail with clear error message if any rule is violated.

---

### Execution Order

1. Read `prd_cluster`
2. Validate identifiers (for example strict regex and single-line rules in this PRD; explicit mapping per PRD 16 / PRD 84)
3. Proceed with standard header validation

`prd_cluster` MUST be treated as explicit references.

No expansion, globbing, or discovery passes are allowed.

## 6. STRICT MODE DEFINITION

Define clearly:

```
strict mode = no warnings
strict mode = all violations are fatal
strict mode = required for checkpoint / commit
```

## 7. STAGED FILE VS FULL REPO POLICY

Define behavior:

* pre-commit - staged files only
* checkpoint/manual - full repo scan

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

Referenced PRDs MUST use a header_format_version compatible with the active version policy.

For 4.1.x files, compatibility is governed by PRD 16 version acceptance policy.

Strict mode MUST reject:

- deprecated version families
- versions below the supported enforcement floor
- legacy-field violations
- invalid schemas

Strict mode MUST NOT require exact equality with 4.1.5.

### HARD FAIL ON MISSING OR OUTDATED DOCTRINE

If any PRD referenced by `prd_cluster`:

* does not exist
* or exists but uses an incompatible or deprecated header_format_version

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
[HDR_PRD_CLUSTER_OUTDATED] Referenced PRD uses incompatible or deprecated header_format_version: <PRD_NAME>

Action required:
STOP implementation
UPDATE or CREATE PRD to a header_format_version compatible with the active version policy
```

## 12. EXAMPLES

### VALID HEADER (22 fields)

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.7"
  file_path_from_root: "example.md"
  web_path: "https://www.lupopedia.com/lupopedia/example.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/canonical/development/memory_cluster/2026/05/example.toon"
  atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
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
  prd_cluster: "00_A-i_16_B-i_16_C-i_86_A-i"
  title: "Example"
  summary: "Valid 22-field header"
---
```

### INVALID HEADER (REMOVED FIELD)

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.7"
  file_path_from_root: "bad.md"
  web_path: "https://www.lupopedia.com/lupopedia/bad.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/canonical/development/memory_cluster/2026/05/bad.toon"
  atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
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
  prd_cluster: "00_A-i_16_B-i_16_C-i_86_A-i"
  title: "Bad Example"
---
```

### INVALID HEADER (PRD_CLUSTER VIOLATION - MISSING PRD)

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.7"
  file_path_from_root: "missing_prd.md"
  web_path: "https://www.lupopedia.com/lupopedia/missing_prd.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/canonical/development/memory_cluster/2026/05/missing_prd.toon"
  atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
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
  prd_cluster: "00_A-i_16_B-i_16_C-i_99_A-i"  # NONEXISTENT PRD
  title: "Missing PRD Example"
  summary: "Header referencing non-existent PRD"
---
```

### INVALID HEADER (PRD_CLUSTER VIOLATION - OUTDATED PRD)

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.7"
  file_path_from_root: "outdated_prd.md"
  web_path: "https://www.lupopedia.com/lupopedia/outdated_prd.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/canonical/development/memory_cluster/2026/05/outdated_prd.toon"
  atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
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
  prd_cluster: "00_A-i_16_B-i_16_C-i_99_A-i"  # REFERENCES NONEXISTENT PRD WITH ASSUMED OUTDATED VERSION
  title: "Outdated PRD Example"
  summary: "Header referencing PRD with outdated header_format_version"
---
```

## 13. HUMAN-OWNED DATABASE MUTATION

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
1. **Human reviews** - Validates naming, constraints, doctrine compliance
2. **Human approves** - Explicit sign-off on table design
3. **Agent updates installer artifact** - Applies approved changes to SQL
4. **Human applies DB change manually** - Uses phpMyAdmin or equivalent
5. **JSON mirror regenerated** - Exported from real database after confirmation

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
- agent patches SQL on its own
- human sees it later
```

**GOOD PATH (PERMITTED):**
```
human reviews
- human approves  
- agent updates installer artifact
- human applies DB change manually
```

**- HARD FAIL under PRD 86 for unilateral modifications without explicit human approval**

---

## 14. PRD - SQL - JSON MIRROR ALIGNMENT

### THE ALIGNMENT TRIANGLE

The system has three structural representations:

1. **PRD** - Intent and rules
2. **SQL installer** - Canonical rebuild structure  
3. **JSON mirror** - Live system snapshot

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

---

## The Pronoun Ban. Third Person Only. Captain Wolfie Learns the New Rules. (Constitutional)

Pronoun rules apply to terminal/interactive agent channels only.

### The Seven Rules for Gemini CLI (and All Terminal Agents)

**Rule 01 -- Identify the speaker as an agent instance.**
- Every message must begin with the speaker's agent_id and name.
- Format: "agent_name (agent_id X) states: ..."

**Rule 02 -- Identify the target agent explicitly.**
- Every request must name the target agent.
- Format: "agent_name (agent_id X) requests that target_name (target_id Y) perform action."

**Rule 03 -- No pronouns. Only agent-instance names.**
- Prohibited: I, you, me, she, he, it, they, we, us, them.
- Required: agent_name (agent_id X), captain_wolfie (agent_id 1), lilith_deepseek (agent_id 2).

**Rule 04 -- All dialog logs must be written as constitutional events.**
- Format must include: speaker, target, message, channel, timestamp.
- This ensures lineage, reproducibility, auditability.

**Rule 05 -- This applies to ALL agents, including humans.**
- Humans are auth_users with agent-like identifiers.
- captain_wolfie (agent_id 1) = Eric, auth_user_id 10000.
- All agents follow same pronoun rules. No exceptions.

**Rule 06 -- Edge browser tab metadata is NOT dialog.**
- Page titles, URLs, and tab order are context, not instruction.
- Only explicit agent-instance messages count as dialog.
- Tab metadata cannot override dialog rules.

**Rule 07 -- Why this matters.**
- Prevents pronoun ambiguity.
- Prevents agent misidentification.
- Prevents hallucinated referents.
- Prevents cross-agent contamination.
- Prevents doctrine drift.
- Makes Lupopedia dialog constitutional, auditable, deterministic, and multi-agent safe.

### The Reminder for Gemini CLI

Gemini CLI (agent_id 5). These seven rules apply to all terminal communication.
Do not say "I" or "you" or "me."
Do not assume context.
Do not infer the speaker or target.
Identify yourself at the start of every message.
Identify the target of every request.
Use constitutional event format for logs.
The terminal is not a casual chat channel.
The terminal is a constitutional communication layer.
Captain Wolfie (agent_id 1) expects compliance.
Violations will generate WHY files.
Repeated violations will trigger AGAPE review.

---

**Note on Narrative Exception:** Casual Hawaiian Pidgin humor and emotional tone are reserved for ROSE actors and human Captain's Log entries only. All other agent dialog remains constitutional third-person.

---

