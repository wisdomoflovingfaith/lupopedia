---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/49_A-i_QUESTIONS_AND_ANSWERS_SYSTEM.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/49_A-i_QUESTIONS_AND_ANSWERS_SYSTEM.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/canonical/prd/memory_cluster/2026/05/49-questions-answers-system.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/prd/49-questions-answers-system
  artifact_type: prd
  artifact_kind: specification
  channel_key: prd
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_16_C-i_38_A-i_49_A-i_51_A-i_86_A-i
  title: 'PRD 49: Questions and Answers System - The Crying of Lot 49'
  summary: Complete specification for truth questions, answers, and evidence system. Database tables, questions_toon files, hybrid hierarchical + graph edge organization, web interface, and Crafty Syntax import. No foreign keys - all integrity in PHP.
---
<!-- ASCII_ART_BLOCK -->
. . . . . . . . ._________________ LUPOPEDIA Semantic Operating System _______________
. ./ \ ` ` `_-\ . | A four-axis, finite, constitutional PRD documentation architecture 
. '/| \-''-/_ / . | that lets docs build software. PRDs reference other PRDs, forming 
. { . , . , . ,\ .| clusters that define behavior, truth, limits, and system identity
. / . , . , . , \ | through positional priority (array index = reading order),
./ , . "O. |"O. } | significance weight (A-F letter), grouping (numeric category), and 
_| . , . , \ \ ;. | chronology (Roman numeral = time created).
. '\. . , . \ \'. | Each file carries a header that records the exact
.. '\_ . , . \__\ | four-axis prd_cluster (order, weight, and time created), the full
., , ''-_ , {\__/}| transcript_jsonl dialog, and atoms_toon for canonical truth,
. . , . / '-.____'| ensuring deterministic lineage and reproducibility. 
., , /. _ _ . -_ -| https://www.lupopedia.com/
.. , _'___________| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com
___-' __________________________________________________________________
<!-- /ASCII_ART_BLOCK -->

### ASCII_ART_BLOCK Protection

See PRD 86 for ASCII enforcement rules.

<!--HUMAN_SEMANTIC -->
This file belongs to:
- PRD Group 49 (Truth Maintenance)
- Channel: prd
- Trust tier: canonical

See also:
- PRD 16 - Lupopedia Headers
- PRD 38 - Memory Unification
- PRD 51 - Memory Graph as Contextual Suggestion Layer
- PRD 86 - Immune System Enforcement
<!-- /HUMAN_SEMANTIC -->

### 4.1.7 Preamble Compliance

This file follows the 4.1.7 three-part preamble:

1. YAML header
2. ASCII_ART_BLOCK
3. HUMAN_SEMANTIC

Execution authority remains YAML header only.

# PRD 49: Questions and Answers System - The Crying of Lot 49

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

## Section 1: Purpose and Naming

The Crying of Lot 49 reference: Entropy, hidden patterns, communication systems, the search for truth through fragmented signals. The Q&A system is Lupopedia's Tristero - the hidden layer that connects questions to answers across time, agents, and contexts.

Define the Q&A system as an uncertainty tracking layer: questions track uncertainty, answers record resolution attempts, and evidence provides provenance.

Narrative references (e.g. "Crying of Lot 49") are descriptive only.

They MUST NOT:
- affect execution
- affect validation
- influence system behavior

### Authority Boundary (4.1.7)

Answers and questions do NOT define canonical truth.

Canonical truth is defined by:
- prd_cluster
- atoms_toon

Q&A system:
- tracks uncertainty
- records resolution attempts
- provides evidence

Q&A MUST NOT:
- override doctrine
- redefine truth
- conflict with governing PRDs

Canonical answers are subordinate to:
- prd_cluster
- atoms_toon

If a canonical answer conflicts with PRD or atoms:
- answer MUST be explicitly downgraded or deprecated by an authorized actor
- The system MUST NOT automatically downgrade answers
- PRD/atoms remain authoritative

### prd_cluster Dependencies (4.1.7)

`prd_cluster` MUST include explicit governing dependencies. No implicit dependencies allowed.

`prd_cluster` MUST reference at minimum:

- PRD 16_C (`16_C-i`, header interpretation)
- PRD 38 (`38_A-i`, memory structure)
- PRD 51 (`51_A-i`, memory graph)
- PRD 86 (`86_A-i`, enforcement)
- `49_A-i` (this PRD)

Example canonical form:

`prd_cluster: "00_A-i_16_C-i_38_A-i_49_A-i_51_A-i_86_A-i"`

### transcript_jsonl Enforcement (4.1.7)

Canonical transcript rules for this PRD (single definition; do not restate elsewhere):

- `transcript_jsonl` is append-only
- MUST NOT be read implicitly
- MAY be read only if a governing PRD explicitly requires

## Section 2: Database Tables

**CONSTITUTIONAL CONSTRAINT:** No FOREIGN KEY, no REFERENCES, no database-enforced constraints. All referential integrity is enforced in PHP application layer.

### 2.1 lupo_truth_questions

| Column | Type | Purpose |
|--------|------|---------|
| question_id | BIGINT | Primary key (IdGenerator) |
| question_text | TEXT | The question being asked |
| question_slug | VARCHAR(255) | URL-friendly identifier |
| asked_by_actor_id | BIGINT | Who asked |
| asked_ymdhis | BIGINT | When asked |
| channel_key | VARCHAR(255) | Source channel |
| thread_key | VARCHAR(255) | Source thread key |
| status | VARCHAR(32) | open, answered, deprecated, merged |
| merged_into_question_id | BIGINT | NULL if not merged |
| context_json | JSON | Additional metadata (PRD references, tags) |
| is_deleted | TINYINT | Soft delete flag, default 0 |
| deleted_ymdhis | BIGINT | Soft delete timestamp, default 0 |

`question_slug` validation requirements:
- question_slug MUST be lowercase
- question_slug MUST use hyphen-separated tokens
- question_slug MUST match regex `^[a-z0-9]+(-[a-z0-9]+)*$`
- question_slug MUST be unique within channel_key scope

### 2.2 lupo_truth_answers

| Column | Type | Purpose |
|--------|------|---------|
| answer_id | BIGINT | Primary key (IdGenerator) |
| question_id | BIGINT | References lupo_truth_questions.question_id (PHP-enforced) |
| answer_text | TEXT | The answer |
| answered_by_actor_id | BIGINT | Who answered |
| answered_ymdhis | BIGINT | When answered |
| is_canonical | TINYINT | 1 = accepted truth, 0 = provisional |
| context_json | JSON | Resolution status, caveats, supporting evidence IDs |
| is_deleted | TINYINT | Soft delete flag, default 0 |
| deleted_ymdhis | BIGINT | Soft delete timestamp, default 0 |

`context_json` resolution status schema (if provided):
- `resolution_status` MAY be present as a categorical field

Allowed values:
- `deprecated`
- `validated`
- `candidate`
- `provisional`

Rule:
- `resolution_status` MUST NOT influence canonical answer selection
- `resolution_status` MUST NOT represent probability
- `resolution_status` MUST NOT be numeric

Violation handling:

If `resolution_status` is used to:

- select canonical answers
- rank answers
- override actor decision

THEN:

STOP

REPORT "RESOLUTION_STATUS_MISUSE"

### 2.3 lupo_truth_evidence

| Column | Type | Purpose |
|--------|------|---------|
| evidence_id | BIGINT | Primary key (IdGenerator) |
| question_id | BIGINT | References lupo_truth_questions.question_id (PHP-enforced) |
| answer_id | BIGINT | NULL if evidence for question only; references lupo_truth_answers.answer_id (PHP-enforced) |
| evidence_type | VARCHAR(32) | prd_section, doctrine_file, transcript_entry, memory_node, external_url |
| evidence_location | TEXT | Path, URL, or reference |
| evidence_hash | VARCHAR(255) | SHA-256 computed in application layer (PHP); database MUST NOT compute hashes |
| provided_by_actor_id | BIGINT | Who provided this evidence |
| provided_ymdhis | BIGINT | When provided |
| is_deleted | TINYINT | Soft delete flag, default 0 |
| deleted_ymdhis | BIGINT | Soft delete timestamp, default 0 |

### Thread Identity Rule

- thread_id (BIGINT):
  - canonical execution identity
  - used in headers and runtime

- thread_key (VARCHAR):
  - human-readable identifier
  - used for routing and file paths

- transcript_jsonl segment:
  - derived from thread_key

Rule:

thread_key MUST NOT replace thread_id

thread_id remains canonical identity

thread_key is descriptive only

## Section 3: questions_toon File Structure

thread = "questions"

questions_toon files MUST reside within:

`memory/{tier}/{channel_key}/{thread}/{YYYY}/{MM}/`

File naming MUST follow deterministic naming rules defined by implementation

All questions_toon paths MUST comply with PRD 38 memory cluster directory structure

Validator MUST:

- validate directory path structure
- NOT assume specific filename patterns unless defined by PRD

Format: TOON (Token-Oriented Object Notation) per TOON_ORDERING_SPEC.md

Purpose: Portable, machine-readable snapshot of questions for a given channel/thread/context

Structure:
```json
{
  "atom_version": "1.0.0",
  "export_ymdhis": "20260421130000",
  "channel_key": "headers",
  "questions": [
    {
      "question_slug": "what-is-atoms-toon",
      "question_text": "What is the purpose of atoms_toon?",
      "status": "answered",
      "answers": [
        {
          "answer_text": "Pointer to immutable machine-readable constants",
          "is_canonical": true,
          "evidence_refs": ["16_B_ATOMS_SYSTEM.md#section1"]
        }
      ]
    }
  ]
}
```

## Section 4: Hybrid Hierarchical + Graph Edge Organization

### 4.1 Hierarchical (Folder) Structure

Crafty Syntax legacy: hierarchical folder tree of Q&A

Migrated to directory cluster: `memory/{tier}/{channel_key}/{thread}/{YYYY}/{MM}/`

questions_toon filenames are implementation-defined under that cluster per Section 3

Year/month sharding prevents directory explosion

### 4.2 Graph Edge Structure (in lupo_memory_edges)

- edge_type: "questions_references_prd" - question to PRD section
- edge_type: "answer_supersedes_answer" - newer answer overrides older
- edge_type: "question_merged_into" - question consolidation
- edge_type: "evidence_supports_answer" - evidence to answer

edge_type values are canonical constants.

They MUST be defined in application code or registry.

Agents MUST NOT invent new edge_type values.

edge_type values MUST be defined in:
- application constant registry OR
- approved lupo_edge_types table

Undefined edge_type MUST trigger:
STOP
REPORT "EDGE_TYPE_NOT_REGISTERED"

### 4.3 Hybrid Rule

Hierarchical path provides deterministic location

Graph edges provide semantic relationships

Both must remain consistent; validator checks for drift

## Section 5: Web Interface

### 5.1 Question Lookup Interface

Route: `/admin/questions` or `/api/questions`

Search by: question text, slug, actor, channel, status, date range

View question with all answers and evidence

Filter by canonical status

### 5.2 Question Submission

Form for asking new questions

Requires: question text, channel context (auto-filled from header)

Optional: PRD reference, tags

### 5.3 Answer Submission

Form for answering existing questions

Requires: answer text, canonical flag (only for authorized actors)

Optional: evidence links

### 5.4 Evidence Attachment

Upload evidence files or link to existing artifacts

Compute SHA-256 hash for integrity in application layer (PHP). Database MUST NOT compute hashes.

## Section 6: Crafty Syntax Import

### 6.1 Legacy Structure

Crafty Syntax stored Q&A in hierarchical folders

Path-based categorization

No relational links between related questions

### 6.2 Import Process

Scan legacy folder tree recursively

For each .question file, create lupo_truth_questions row

For each .answer file, create lupo_truth_answers row

Preserve original path as context_json.legacy_path

Create graph edges from path hierarchy (parent folder = related question)

### 6.3 Import Script

```bash
python scripts/import_crafty_qa.py --source /path/to/crafty/qa --channel knowledge
```

## Section 7: Truth Maintenance Workflow

### 7.1 Question Lifecycle

1. Question asked (status: open)
2. Answers submitted (status remains open until canonical answer selected)
3. Canonical answer marked (status: answered)
4. If question becomes obsolete: status deprecated
5. If duplicate: merged into existing question (status: merged, merged_into_question_id set)

Canonical answer selection MUST:
- be explicitly set by authorized actor
- NOT be inferred
- NOT be auto-selected
- NOT be based on resolution_status

### 7.2 Evidence Integrity

All evidence must have SHA-256 hash computed in application layer (PHP). Database MUST NOT compute hashes.

Validator MUST:
- verify evidence_location if local
- verify hash if recomputable

If verification fails:
- mark evidence invalid
- trigger ALERT (not hard failure)

Invalid evidence MUST:
- NOT be used in canonical answer evaluation
- NOT be used in validation decisions

Broken evidence triggers [ALERT] via THOTH

## Section 8: questions_toon in Headers (PRD 16 integration)

Header field questions_toon points to a TOON file path under the Section 3 directory cluster

When questions_toon is non-null, the resolved file MUST exist and be valid TOON

Validator rule: HDR_QUESTIONS_TOON_VALID - checks directory path structure per PRD 38, file existence when path is given, TOON format validity, and schema validation (required fields present); MUST NOT assume specific filename patterns unless defined by PRD

questions_toon behavior requirements:

* optional: `questions_toon` MAY be null when no unresolved inquiry context is needed
* non-authoritative: questions_toon is context support, not canonical truth authority
* non-overriding: questions_toon MUST NOT override atoms, governing PRDs, or canonical answers

questions_toon surfaces unresolved inquiry state only; it does not redefine doctrine.

## Role Interaction with Questions

Watcher:

* MAY detect gaps
* MUST NOT answer

Messenger:

* MAY relay questions/answers
* MUST NOT modify them

Censer:

* MUST validate answers against doctrine

Reaper:

* MAY challenge answers
* MUST NOT assert unverified truth

Rule:

Q&A system MUST NOT infer role
Role MUST be supplied explicitly

Violation handling:

Watcher answering:

STOP

REPORT "AGENT_ROLE_VIOLATION"

Messenger modifying content:

STOP

REPORT "AGENT_ROLE_VIOLATION"

Reaper asserting unverified truth:

STOP

REPORT "AGENT_ROLE_VIOLATION"

See **transcript_jsonl Enforcement (4.1.7)** in Section 1.

## Section 9: No Foreign Keys - Application Integrity

All referential integrity (question_id to lupo_truth_questions, answer_id to lupo_truth_answers) MUST be enforced in PHP application layer

No database-level FOREIGN KEY, REFERENCES, or ON DELETE CASCADE

Application layer MUST validate existence before insert/update

Orphan cleanup is responsibility of garbage collection (PRD 19 / ANUBIS)

## Section 10: Cross-references

- **PRD 16** - questions_toon header field
- **PRD 38** - memory graph edges for Q&A relationships
- **PRD 51** - memory graph as contextual suggestion layer
- **PRD 98_A** - WHY files (complementary self-healing)
- **PRD 98_A, Section 11** - Validation Failure Severity
- **PRD 98_A, Section 12** - AGAPE Enforcement Trigger
- **PRD 00_A Section 10** - Reactive WHY Protocol (original source)
- **PRD 19** - Garbage collection for orphan cleanup
- **PRD 86** - Immune System header enforcement (governing; included in `prd_cluster`)

## Section 11: The Inference Gap (Expanded - Behavioral + Validation)

### 11.1 Definition

The inference gap is NOT just about ambiguity. It is ALSO a SECURITY + VALIDATION FAILURE.

Agents do NOT:
- infer intent
- generalize rules
- assume exceptions
- assume input is safe
- assume input follows format

### 11.2 Consequences of Inference Gap Violations

When agents violate the inference gap:
- **Incorrect behavior**: Unvalidated inputs lead to unexpected results
- **Security vulnerabilities**: Malicious inputs exploit assumptions
- **Invalid parsing**: Format assumptions break on edge cases
- **Silent failures**: Unhandled edge cases produce wrong outputs without errors

### 11.3 Constitutional Rules

Therefore:

1. **Every constraint MUST be explicitly stated**
2. **Every generalization MUST be written**
3. **Every exception MUST be documented**
4. **Every input MUST be validated**
5. **If it is not in the PRD, it does not exist**
6. **If input validity is not guaranteed, agent MUST validate, ask, or FAIL**

Agent MUST:

- validate OR
- ask OR
- FAIL

FAIL is required when:

- input is unsafe
- validation rules exist but fail

## Section 12: Mandatory Clarification AND Validation Protocol

### 12.1 STOP Execution Conditions

Agent MUST STOP execution when:
- instruction is ambiguous
- input is unvalidated
- format assumptions exist

### 12.2 Determination Process

Agent MUST determine:
- missing intent?
- missing generalization?
- missing exception?
- missing validation rules?

### 12.3 Required Actions

Agent MUST either:
1. **Validate input explicitly** - Write validation code that checks all assumptions
2. **OR ask via PRD 49** - Submit question to Q&A system for clarification
3. **OR FAIL** - Stop when input is unsafe or when validation rules exist but fail

### 12.4 Constitutional Prohibition

**NEVER assume safety** - All inputs are potentially malicious until proven otherwise.

## Section 13: Canonical Examples

### 13.1 Example - Sanitize Function (Inference Failure)

```python
def sanitize(input_string):
    if "<script>" in input_string:
        return "[BLOCKED]"
    return input_string
```

**INFERENCE FAILURE**: This function assumes:
- Input is a string
- Only script tags are dangerous
- No other injection vectors exist
- Case sensitivity doesn't matter

**CORRECT APPROACH**:
```python
def sanitize(input_string):
    # Validate input type
    if not isinstance(input_string, str):
        raise ValueError("Input must be string")
    
    # Explicit validation rules
    dangerous_patterns = [
        "<script>", "</script>",  # Script tags
        "javascript:",            # JS protocol
        "onload=", "onerror=",    # Event handlers
        "<?php", "<?", "<%"       # Server tags
    ]
    
    # Case-insensitive check
    input_lower = input_string.lower()
    for pattern in dangerous_patterns:
        if pattern in input_lower:
            return "[BLOCKED]"
    
    return input_string
```

### 13.2 Example - Database Query (Validation Required)

```php
// WRONG: Assumes user_id is safe integer
function getUserData($user_id) {
    return "SELECT * FROM users WHERE id = $user_id";
}

// CORRECT: Validate explicitly
function getUserData($user_id) {
    if (!is_numeric($user_id) || $user_id < 0) {
        throw new InvalidArgumentException("Invalid user ID");
    }
    return "SELECT * FROM users WHERE id = " . (int)$user_id;
}
```

## Section 14: Inference Gap Failure Types (Explicit Classification)

### 14.1 Type 1: Ambiguity
- **Definition**: Missing intent, generalization, or exception
- **Examples**: Unclear instruction scope, unstated edge cases
- **Action**: ASK (PRD 49)

### 14.2 Type 2: Literalism Error
- **Definition**: Overly narrow interpretation
- **Examples**: Interpreting "users" as only human users, missing system accounts
- **Action**: ASK or correct via doctrine

### 14.3 Type 3: Validation Failure
- **Definition**: Input not validated or assumed safe
- **Examples**: Unvalidated user input, assumed clean data, skipped sanitization
- **Action**: MUST NOT ASK - MUST FAIL OR VALIDATE

## Section 15: Ask vs Fail Boundary

### 15.1 ASK Conditions
Agents SHALL ASK when:
- Rules are missing from governing PRDs
- Intent is unclear from instruction context
- Generalizations need explicit documentation
- Exceptions are not defined

### 15.2 FAIL or VALIDATE Conditions
Agents SHALL FAIL or VALIDATE when:
- Input may be unsafe (external data sources)
- Format is untrusted (user-generated content)
- External data is involved (API calls, file uploads)
- Security boundaries are crossed

### 15.3 Constitutional Rule

**"Security is not a question. It is a requirement."**

## Section 16: Definition of Validation (Deterministic)

### 16.1 Validation Requirements

Validation MUST include:

#### 1. Pattern Enforcement
- Input MUST match explicit pattern (regex or equivalent)
- Example (`prd_cluster` per PRD 16 / PRD 86):
  - each token pair is `NN` (two digits) plus `_` plus section token `X-[ivx]+` (section letter, instance suffix such as `-i` or `-ii`)
  - full cluster: `NN_X-suffix` pairs joined by `_`, for example `00_A-i_16_C-i_49_A-i`

#### 2. Type Enforcement
- Data type MUST be verified
- No implicit casting

#### 3. Boundary Enforcement
- Length, format, and structure MUST be checked

#### 4. Rejection Rule
- If input fails validation - MUST:
  - throw error OR
  - reject input
- MUST NOT continue processing

#### 5. No Silent Correction
- Agent MUST NOT "fix" invalid input silently
- Must reject or escalate

### 16.2 Constitutional Rule

**"Validation is only valid if failure is explicitly handled."**

### 16.3 Example: prd_cluster Validation

```python
import re

def validate_prd_cluster(prd_cluster):
    # Pattern enforcement (NN_X-[ivx]+ pairs; aligns with PRD 16 / PRD 86)
    if not re.match(r'^([0-9]{2}_[A-Z]-[ivx]+)(?:_[0-9]{2}_[A-Z]-[ivx]+)*$', prd_cluster):
        raise ValueError("Invalid prd_cluster format")

    parts = prd_cluster.split('_')
    if len(parts) % 2 != 0:
        raise ValueError("prd_cluster must be alternating NN and section-suffix segments")

    # Type and boundary enforcement for PRD numbers (segments 0, 2, 4, ...)
    for i in range(0, len(parts), 2):
        seg = parts[i]
        if len(seg) != 2 or not seg.isdigit():
            raise ValueError("PRD number segment must be exactly two digits")
        prd_num = int(seg)
        if prd_num < 0 or prd_num > 99:
            raise ValueError("PRD number must be 00-99")

    # Section + instance suffix (segments 1, 3, 5, ...), e.g. A-i, C-ii
    for j in range(1, len(parts), 2):
        if not re.match(r'^[A-Z]-[ivx]+$', parts[j]):
            raise ValueError("Section token must be one uppercase letter plus instance suffix (-i, -ii, etc.)")

    # Rejection rule - no silent correction
    return prd_cluster
```

---

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  generated_by: "cascade"
  validation_status: "pending"
  ascii_compliance: "confirmed"
  last_validated: "20260421130000"
