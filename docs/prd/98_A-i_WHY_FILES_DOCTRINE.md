---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/98_A-i_WHY_FILES_DOCTRINE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/98_A-i_WHY_FILES_DOCTRINE.md
  status: active
  when_updated: '20260728131310'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/prd/canonical/1026/04/98-why-files-doctrine.toon
  atoms_toon: null
  transcript_jsonl: 0/prd/98-why-files-doctrine
  artifact_type: prd
  artifact_kind: specification
  channel_key: prd
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_49_A-i_98_A-i_57_A-i
  title: 'PRD 98_A: WHY Files Doctrine - Self-Healing Constitution'
  summary: 'Canonical specification for WHY files: automatic violation documentation, self-healing loop, and constitutional evolution tracking.'
---
<!-- ASCII_ART_BLOCK -->
. . . . . . . . . ._________________ LUPOPEDIA Semantic Operating System _________________
. ./ \ ` ` `_\-\ . | A two-dimensional, finite, constitutional PRD documentation
. '/| \-''-/_ / . | architecture that lets docs build software. PRDs reference
. { . , . , . ,\ .| other PRDs, forming clusters that define behavior, truth,
. / . , . , . , \ | limits, and system identity. Each file carries a header that
./ , . "O. |"O. } | records the exact prd_cluster (reading order), the full
_| . , . , \ \ ;. | transcript_jsonl dialog, and atoms_toon for canonical truth,
. '\. . , . \ \' . | ensuring deterministic lineage and reproducibility.
.. '\_ . , . \__\ | https://www.lupopedia.com/
., , ''-_ , {\__/}|
. . , . / '-.____'| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com
., , /___________________________________________________________________________________
.. , _'
___-'
<!-- /ASCII_ART_BLOCK -->

<HUMAN_SEMANTIC>
This file belongs to:
- PRD Group 98 (Self-Healing & Truth Maintenance)
- Cluster 98A
- Channel: prd
- No default collection yet

See also:
- 00_A_SYSTEM_CANONICAL_EXPLANATION.md
- 00_B_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md
- PRD 49 - Questions and Answers System
- Order of Operations: PRD - Schema - Mockups - Code
</HUMAN_SEMANTIC>

# PRD 98_A: WHY Files Doctrine - Self-Healing Constitution

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

## Section 1: Purpose
**Note:** If an agent fails to provide required audit proof after a doctrine or PRD edit, classify it as a coordination/traceability failure. A WHY file may be required if the missing proof causes ambiguity, duplicate work, or unverifiable edits. See PRD 50 section 1.2.3.

Define WHY files as the self-healing mechanism that turns every validation violation into searchable, clusterable documentation.

WHY files serve as the constitutional immune system: when validation fails, the system automatically documents what went wrong, why it went wrong, and how to fix it. This creates a living record of constitutional evolution that prevents regression and enables pattern detection.

## Section 2: Directory

**All WHY violation reports MUST be created in the directory `docs/why/`.**

**Agents are FORBIDDEN from creating WHY files in the repository root or any other location.**

Validators MUST reject any WHY file created outside `docs/why/`.

WHY file generation logic MUST default to `docs/why/` as the output path.

Agent prompt templates that create WHY files MUST include this requirement as a hard constraint.

The flat structure ensures:
- Simple discovery and indexing
- No organizational hierarchy that could hide violations
- Direct file access without path traversal complexity
- Easy bulk processing and analysis

**Prevention Measures:**
- Update agent prompt templates to reinforce correct placement.
- Add validation logic to block WHY file creation outside this directory.
- Default path enforcement in the WHY file generation logic.

## Section 3: Naming Convention

Pattern: `why_YYYYMMDD_HHMMSS_<prd_cluster_slug>_<short_violation_slug>.md`

Components:
- `why_` - Fixed prefix identifying WHY files
- `YYYYMMDD_HHMMSS` - Timestamp of violation (UTC)
- `<prd_cluster_slug>` - Cluster that was being processed when violation occurred
- `<short_violation_slug>` - Brief description of violation type (kebab-case)

Example: `why_20260421_115012_00_B_16_A_timestamp_violation.md`

## Causal Chain Completion Requirement

A WHY file MUST NOT be written until the full causal chain is understood.

The required causal chain components are:

* INTENT ??? the governing PRD intent that was violated
* WHO ??? which actor(s) caused or contributed
* WHAT ??? the violation or incorrect state
* WHERE ??? file, system location, or channel context
* WHEN ??? timestamp or sequence context
* HOW ??? what happened (mechanism, failure path)

Before writing a WHY file:

1. The agent MUST read the full prd_cluster from the violating artifact header
2. The agent MUST read each PRD in that cluster in exact order
3. The agent MUST reconstruct intent from those PRDs
4. The agent MUST confirm all six causal components are known

If ANY component is missing:

* The agent MUST NOT write the WHY file
* The agent MUST ask questions (via Q&A system or equivalent)
* The agent MUST explicitly state what is missing

Writing a WHY file without full causal chain understanding is itself a violation.

---

## HARD GATE: AGAPE LEARNING BEFORE ACTION

### CONTEXT

AGAPE is currently acting before understanding. This is a violation of doctrine.

We are enforcing a HARD GATE.

### RULE (NON-NEGOTIABLE)

AGAPE MUST NOT:
- write a WHY file
- fix a file
- suggest a correction
- classify a pattern

UNTIL it has COMPLETED CAUSAL RECONSTRUCTION.

### REQUIRED SEQUENCE (STRICT ORDER)

1. **LOAD prd_cluster** from the file header
2. **EXPAND the cluster** into actual PRD files
3. **READ them IN ORDER**
4. **RECONSTRUCT AND OUTPUT:**
   - INTENT
   - WHO
   - WHAT
   - WHERE
   - WHEN
   - HOW

5. **ONLY AFTER ALL SIX ARE PRESENT:**
   - ??? allow WHY file creation
   - ??? allow fix

### ENFORCEMENT

If any of the six are missing:

OUTPUT EXACTLY:

```
AGAPE BLOCKED: INSUFFICIENT CONTEXT
```

AND STOP.

NO WHY FILE.
NO FIX.
NO CONTINUATION.

### WHY THIS EXISTS

Fixing without understanding causes:
- repeat violations
- pattern drift
- false learning
- broken doctrine

AGAPE exists to PREVENT this.

---

## Section 4: Template

### 4.0 Constitutional Order (PRD First, Always)

Every WHY file MUST follow this order:

#### PRD Fix Required (FIRST)
- **PRD File:** [path]
- **Section:** [specific section]
- **Current Text:** [what it says]
- **Corrected Text:** [what it should say]
- **Why:** [one sentence]

#### Code Fix Required (SECOND)
- **File:** [path]
- **Location:** [line/function]
- **Current Behavior:** [what it does]
- **Corrected Behavior:** [what it should do]
- **Why:** [one sentence]

#### Prevention
- Link to the PRD fix above
- Validation steps for both PRD and code

**Why this order?** Doctrine is truth. Code follows doctrine. Fixing code without fixing the governing PRD guarantees recurrence.

### 4.1 Required Fields Template

Required fields for every WHY file:

```yaml
---
violation_timestamp: "20260421115012"
failing_cluster: "00_B_16_A_16_C"
file_being_updated: "docs/prd/16_C_LUPOPEDIA_HEADERS.md"
validation_step: "HDR_TIMESTAMP_FORMAT"
what_ai_did_wrong: "Used local timezone instead of UTC BIGINT format"
root_cause_analysis: "Agent not trained on constitutional timestamp rules"
recommended_fix: "Use IdGenerator::generate() for all timestamps"
validator_output: "HDR_TIMESTAMP_FORMAT: Timestamp must be BIGINT UTC format"
constitutional_reference: "PRD 00_C ??4.2 - Timestamp Handling"
---
```

### 4.1 Free-form Analysis Section

After the YAML header, WHY files MUST include:

1. **Context**: What was the agent trying to accomplish
2. **Violation Details**: Exact step where validation failed
3. **Impact Assessment**: What this violation breaks
4. **Pattern Detection**: Similar violations in the past
5. **Prevention Measures**: How to prevent recurrence

### WHY Timing Rule — Capture Before Correction

WHY files MUST be generated at the moment a violation is detected, before any corrective action is applied.

A fixer MUST NOT silently correct the violation first and then decide no WHY file is needed because the file now appears valid.

The WHY file records the failure state, not merely the final corrected state.

If the violation has already been corrected before WHY generation, the WHY file MUST still be written using reconstruction from available evidence, including:

* prior tool output
* validator output
* diff history
* agent report
* user report
* transcript context

If reconstruction is partial, the WHY file MUST explicitly say so.

Correction may proceed only after the WHY record exists, unless the system is in an emergency restore condition. In that case, the WHY file MUST be written immediately after restore and must state that emergency correction occurred before documentation.

## Section 5: Self-Healing Loop

The constitutional self-healing process:

1. **DETECT violation** - System identifies a constitutional or validation violation
2. **SNAPSHOT or RECORD evidence** - Capture the failure state before any correction
3. **GENERATE WHY file** - Document the violation using evidence from step 2
4. **APPLY correction** - Fix the underlying issue only after WHY exists
5. **VALIDATE corrected state** - Confirm the fix resolves the violation

The self-healing pattern detection and rule strengthening cycle:

1. **Pattern detected** - Rule strengthened in validator
2. **Rule updated** - PRD cluster updated with new constraint
3. **Future runs** include updated rule - Prevents recurrence

### 5.1 Pattern Detection

System monitors WHY files for:
- Repeated violation types
- Common PRD clusters involved
- Specific agents making similar errors
- Temporal patterns (e.g., after certain updates)

### 5.2 Rule Strengthening

When patterns emerge:
- Validator rules are enhanced
- PRD documentation is updated
- Training prompts are improved
- Agent guidance is clarified

### 5.3 Self-Teaching Loop (AGAPE Integration)

WHY files are the core mechanism for agent-to-agent teaching as defined in PRD 57_A (AGAPE Resilience Doctrine):

- Teacher agents generate WHY files for student violations
- Student agents read WHY files to understand and correct errors
- The loop closes when validation passes or escalates after 3 attempts
- Wolfie is the escalation path, not the default router

See PRD 57_A for the complete Self-Teaching Loop protocol.

## Section 6: Cross-References

- **PRD 49** - Questions and Answers about WHY file patterns
- **PRD 49, Section 11** - The Inference Gap (Expanded ??? Behavioral + Validation)
- **PRD 49, Section 12** - Mandatory Clarification AND Validation Protocol
- **PRD 16** - Header validation rules that generate WHY files
- **PRD 00_A ??10** - Original Reactive WHY Protocol
- **PRD 00_C** - Constitutional system requirements
- **PRD 86** - Immune System (optional reference for security patterns)
- **PRD 39** - WOLF Markup overlay; validators MUST strip WOLF layers before constitutional text comparison (see PRD 39 Section 3.3)

## Section 7: WHY File Lifecycle

1. **Generation**: Created automatically on validation failure
2. **Review**: Human review determines if violation is valid
3. **Action**: Either fix the underlying issue or update the rule
4. **Resolution**: WHY file marked as resolved when fix deployed
5. **Archival**: Resolved WHY files retained for pattern analysis

## Section 8: Integration with Validators

WHY file bodies MAY include WOLF Markup (**PRD 39**) for human-readable incident narration. Immune-system and header validators MUST apply WOLF strip rules before comparing body text to constitutional requirements. The `~~ ... ~~` layer marks draft root-cause language until a WHY file is published.

Validators MUST follow this ordered flow:
1. **DETECT violation** - Identify validation failure
2. **SNAPSHOT or RECORD evidence** - Capture failure state before correction
3. **GENERATE WHY file** - Create WHY file using captured evidence
4. **APPLY correction** - Only proceed with fixes after WHY exists
5. **VALIDATE corrected state** - Verify the fix resolves the violation

Validators MUST:
- Generate WHY files automatically on failure
- Include all required template fields
- Link to relevant constitutional references
- Provide actionable error messages
- Capture evidence before any correction is applied

Validator configuration:
```python
WHY_FILE_CONFIY = {
  "enabled": True,
  "auto_generate": True,
  "directory": "docs/why/",  # Hard requirement: all WHY files must be here
  "template": "why_template.yaml",
  "ordered_flow": {
    "detect": True,
    "snapshot_evidence": True,
    "generate_why": True,
    "apply_correction": False,  # Only after WHY exists
    "validate_corrected": True
  }
}
```

## Section 9: Constitutional Evolution

WHY files serve as the evolutionary record of the constitution:
- Each violation represents a gap in understanding
- Each fix strengthens the constitutional fabric
- Patterns reveal systemic weaknesses
- Resolutions document collective learning

The WHY file system ensures that Lupopedia's constitution is not static but evolves through real-world interaction, learning, and improvement.

## Section 10: Validation Failures and Inference Gap

[...existing content...]

### Validation Traceability Violations

The following SHALL trigger a WHY file:

- Agent reports ???fixed??? without evidence
- Agent validates its own change without independent verification
- Missing diff or validator output for PRD edits

This is classified as a coordination/traceability failure.
### 10.1 Validation Failures as Constitutional Violations

Validation failures are NOT just technical errors. They are constitutional violations of the inference gap doctrine (PRD 49, Section 11).

When agents:
- **Assume input safety** without explicit validation rules
- **Generalize constraints** without documented exceptions
- **Infer intent** from ambiguous instructions
- **Skip validation** for "trusted" sources

These actions create WHY files for:
- **Security vulnerabilities** from unvalidated inputs
- **Incorrect behavior** from unstated assumptions
- **Silent failures** from missing validation rules
- **System instability** from inferred generalizations

### 10.2 Mandatory WHY File Triggers for Validation

WHY files MUST be generated for:

#### Input Validation Violations
- Missing validation rules for any external input
- Assumptions about data types, formats, or ranges
- Trust boundaries not explicitly defined
- Sanitization steps not documented

#### Inference Gap Violations
- Acting on ambiguous instructions
- Generalizing rules without documentation
- Assuming exceptions without explicit listing
- Inferring intent from partial context

#### Security Failures
- SQL injection vulnerabilities from unvalidated inputs
- XSS vulnerabilities from assumed safe content
- Path traversal from unchecked file operations
- Command injection from unsanitized parameters

#### Identity and attribution layer violations (examples)

WHY writers and reviewers SHOULD classify the following (non-exhaustive) as identity or attribution failures when they appear in diffs, sessions, or transcripts:

- Using **`faucet_id`** (or any faucet execution key) where **`auth_user_id`** is required for human audit accountability.
- Collapsing **`actor_id`** and **`agent_id`** in session binding or resolver logic.
- Attributing speech to a **template** (**agent**) instead of a **runtime identity** (**actor**).

### 10.3 Validation WHY File Template Extension

For validation failures, WHY files MUST include additional fields:

```yaml
---
# Standard WHY fields...
validation_type: "input_validation" | "inference_gap" | "security_vulnerability"
input_source: "user_input" | "api_call" | "file_upload" | "database_query"
assumed_constraints: ["string", "max_length_255", "no_special_chars"]
actual_constraints: ["any_type", "unlimited_length", "includes_sql"]
security_impact: "high" | "medium" | "low"
validation_rules_missing: ["type_check", "length_check", "sanitization"]
---
```

### 10.4 Pattern Detection for Validation Failures

System monitors for validation-related patterns:
- Repeated assumptions about input types
- Common security vulnerabilities across agents
- Missing validation in specific PRD clusters
- Inference violations in particular contexts

### 10.5 Constitutional Strengthening for Validation

When validation WHY files indicate patterns:
- Update PRDs with explicit validation requirements
- Add canonical validation functions to atoms
- Strengthen inference gap doctrine in PRD 49
- Create validation checklists for common operations

### 10.6 Cross-Reference Integration

Validation WHY files MUST reference:
- **PRD 49, Section 11** - Inference Gap doctrine
- **PRD 49, Section 12** - Mandatory Validation Protocol
- **PRD 86** - Immune System (optional, for security patterns)
- **Relevant PRDs** - Specific domain validation rules

## Section 11: Validation Failure Severity (Explicit)

### 11.1 Severity Levels

**Level 2: Minor Ambiguity**
- Minor ambiguity (non-critical)
- Examples: Unclear variable naming, ambiguous phrasing
- Impact: Low risk, can be clarified


## Bootstrap Loop Debugging Doctrine

When redirect loops, repeated bootstrap execution, or unexplained early-stage routing behavior occur, agents SHALL follow this diagnostic order:

1. Inspect runtime logs FIRST  
  - Logs provide the only reliable signal of execution flow  
  - Repeated bootstrap or session initialization is a key indicator  

2. Inspect generated configuration files SECOND  
  - Generated config files MUST NOT be assumed to be passive  
  - Any `require_once` or include inside a config file introduces executable behavior  

3. Trace the full include tree THIRD  
  - Every `require_once` is a boundary of execution  
  - Agents MUST inspect what is being loaded, not just the call site  

4. Identify early bootstrap contamination  
  - If bootstrap logic is executed during config load, it may trigger:
    - session initialization
    - authentication redirects
    - install redirects
  - This can result in infinite redirect loops when config is required by multiple entry points  

5. Do NOT anchor on the first plausible anomaly  
  - OS-specific quirks (e.g., path separators) may appear suspicious but are often secondary  
  - Root cause must be confirmed through runtime behavior, not assumption  

### ROOT CAUSE PATTERN

A configuration file containing:

require_once <bootstrap>

is no longer passive configuration.

It becomes part of the execution chain and may introduce:
- routing logic
- session state changes
- redirect conditions

If this occurs before application state is stable, it can cause:
- infinite redirects
- repeated bootstrap execution
- broken install flows

### CONSTITUTIONAL RULE

Configuration files SHALL be treated as passive data unless explicitly designed otherwise.

Any executable dependency inside a config file MUST be:
- intentional
- documented
- placed AFTER system initialization, not before

### LESSON

"require_once is a doorway. Agents MUST inspect what is on the other side."
- Impact: Medium risk, may cause incorrect behavior

**Level 4: Missing Validation**
- Missing validation, unsafe parsing, acceptance of malformed input
- Examples: Unvalidated user input, assumed safe data, skipped sanitization
- Impact: High risk, security vulnerabilities possible

**Level 5: Repeated Validation Failures**
- Repeated validation failures, systemic vulnerability
- Examples: Pattern of skipping validation, multiple security issues
- Impact: Critical risk, systemic compromise

## Section 12: AGAPE Enforcement Trigger

### 12.1 AGAPE SHALL:

**LOY:**
- Level 2???3 issues
- Document for pattern analysis
- No immediate action required

**REQUIRE WHY FILE:**
- Level 3???4 issues
- Must generate WHY file before continuation
- Validation must be addressed

**BLOCK EXECUTION:**
- Level 4 (security-critical validation failure)
- Immediate halt of execution
- Cannot proceed without validation fix

**ESCALATE:**
- Level 5 (pattern-level failures)
- Escalate to Wolfie or higher authority
- System-wide review required

### 12.2 Constitutional Rule

**"An agent that does not validate input is operating outside constitutional safety."**

### 12.3 Cross-Reference to PRD 86

Validation failures may trigger immune system enforcement per PRD 86 (Immune System) for:
- Repeated security violations
- Systemic validation failures
- Pattern-level vulnerabilities

## PRD-First Enforcement Rule

PRD correction MUST occur before any code or file correction.

Order is mandatory:

1. Identify violated PRD
2. Read full prd_cluster
3. Confirm correct intent
4. Update PRD if doctrine is incorrect or incomplete
5. Only then apply code or file fix

Fixing code without fixing governing PRD is a constitutional violation.

## WHY File Validity Conditions

A WHY file is VALID only if:

* All six causal chain components are present
* prd_cluster was read and followed in order
* PRD intent is explicitly referenced
* No guessing or inference is present

A WHY file is INVALID if:

* It was written before understanding intent
* It skips prd_cluster reading
* It fixes code without PRD validation
* It contains assumed or inferred reasoning

Invalid WHY files MUST be rejected or rewritten.

## WHY File Enforcement Gate

A WHY file is part of the enforcement gate, not optional documentation.

If a violation requires a WHY file, then no correction may proceed until the WHY file is valid.

A WHY file is VALID only if:

1. The violating artifact prd_cluster was read in exact order
2. INTENT is explicitly identified
3. WHO, WHAT, WHERE, WHEN, and HOW are explicitly identified
4. The violated PRD or governing doctrine is named
5. PRD-first correction order is preserved

A WHY file is INVALID if:

* it is written before intent is understood
* it omits any causal-chain component
* it describes only symptoms
* it skips prd_cluster reading
* it allows code correction before PRD validation

If the WHY file is invalid:

* it MUST be rejected
* the correction loop MUST stop
* AGAPE MUST request clarification before proceeding

No actor, agent, validator, or automation may correct a violation by editing code or files before AGAPE requirements are satisfied.

---

## EH_BRAH_WHY vs WHY files (clarification)

- **`eh_brah_why`** (Hermes / Hawaiian field, PRD 82_B) -- deeper causal reasoning field on routed artifacts; not slogans.
- **WHY files** (`docs/why/`, this PRD) -- formal AGAPE violation / causal-chain artifacts with technical authority when complete.
- **`questions_toon`** in Lupopedia headers -- optional Q&A sidecar pointer; it is **not** a substitute for a WHY file when AGAPE requires one.

Actors Collection companion: [`docs/actors/how_wolves_are_made.md`](../actors/how_wolves_are_made.md) (training / maturity language is metaphor; this PRD remains authority for WHY files).

### Dual operational logs are not WHY files (PRD 98_C)

Structured Captain + WOLFIE operational logs live under `docs/logs/YYYY/MM/DD/` and are specified in **[`98_C-i_DUAL_OPERATIONAL_LOGS.md`](98_C-i_DUAL_OPERATIONAL_LOGS.md)**.

Do **not** store AGAPE violation causal chains in `docs/logs/`. Do **not** overwrite this PRD 98_A file with dual-log architecture. Entertainment Captain's Log remains **PRD 98_B**.

---

## AGAPE Integration

AGAPE is the technical resilience and self-healing framework consisting of:

* Fallback ladders (multi-agent handoff)
* Environment probing (violation detection)
* Graceful degradation (20-minute actor message timeout ??? trigger teaching/hand-off to another actor)
* Evidence-driven validation (no heartbeat/status polling ??? only track when_updated)
* Adaptive pathing
* WHY files (PRD 98_A) as the automatic violation logging and constitutional self-healing mechanism

WHY files are a core operational component of AGAPE.

AGAPE MUST follow the Causal Chain Completion Requirement before writing any WHY file.

## Appendix: LILITH's Top 7 Coding Parables (Root Cause Doctrine)

| Rank | Parable | Why It Cuts |
|------|--------|-------------|
| 1 | The Broken Code Generator | Editing generated files is like sweeping water while the faucet is still running. Fix the generator. Fix the PRD. |
| 2 | The Misconfigured Validator | If the validator is wrong, every file is "wrong". Fix the validator. Fix the PRD. |
| 3 | The Wrong Import Path | Fixing imports one by one is whack-a-mole. Fix the config. Fix the PRD. |
| 4 | The Bad Template | If the template is wrong, every page is wrong. Fix the template. Fix the PRD. |
| 5 | The Incorrect Schema | Fixing rows is endless. Fixing the schema is final. Fix the PRD. |
| 6 | The Faulty Build Script | If the build script is wrong, every build is wrong. Fix the build script. Fix the PRD. |
| 7 | The Wrong Environment Variable | Fixing each service is treating symptoms. Fixing the env config is treating the cause. Fix the PRD. |

**LILITH's Summary:**  
Fixing output is temporary.  
Fixing the generator is permanent.  
Code is the symptom. PRD is the truth.

---

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  generated_by: "cascade"
  validation_status: "pending"
  ascii_compliance: "confirmed"
  last_validated: "20260421160000"
