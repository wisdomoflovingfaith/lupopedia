---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/98_A_WHY_FILES_DOCTRINE.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/98_A_WHY_FILES_DOCTRINE.md"
  status: active
  when_updated: "20260422232349"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/prd/canonical/1026/04/98-why-files-doctrine.toon
  atoms_toon: null
  transcript_jsonl: 0/prd/98-why-files-doctrine
  artifact_type: prd
  artifact_kind: specification
  channel_key: prd
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: 49
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_49_A_98_A_57_A
  title: "PRD 98_A: WHY Files Doctrine - Self-Healing Constitution"
  summary: "Canonical specification for WHY files: automatic violation documentation, self-healing loop, and constitutional evolution tracking."
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

## Section 1: Purpose

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

* INTENT — the governing PRD intent that was violated
* WHO — which actor(s) caused or contributed
* WHAT — the violation or incorrect state
* WHERE — file, system location, or channel context
* WHEN — timestamp or sequence context
* HOW — what happened (mechanism, failure path)

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
   - → allow WHY file creation
   - → allow fix

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

## Section 5: Self-Healing Loop

The constitutional self-healing process:

1. **Violation occurs** - WHY file generated automatically
2. **Pattern detected** - Rule strengthened in validator
3. **Rule updated** - PRD cluster updated with new constraint
4. **Future runs** include updated rule - Prevents recurrence

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
- **PRD 16** - Header validation rules that generate WHY files
- **PRD 00_A ??10** - Original Reactive WHY Protocol
- **PRD 00_C** - Constitutional system requirements

## Section 7: WHY File Lifecycle

1. **Generation**: Created automatically on validation failure
2. **Review**: Human review determines if violation is valid
3. **Action**: Either fix the underlying issue or update the rule
4. **Resolution**: WHY file marked as resolved when fix deployed
5. **Archival**: Resolved WHY files retained for pattern analysis

## Section 8: Integration with Validators

Validators MUST:
- Generate WHY files automatically on failure
- Include all required template fields
- Link to relevant constitutional references
- Provide actionable error messages

Validator configuration:
```python
WHY_FILE_CONFIG = {
  "enabled": True,
  "auto_generate": True,
  "directory": "docs/why/",  # Hard requirement: all WHY files must be here
  "template": "why_template.yaml"
}
```

## Section 9: Constitutional Evolution

WHY files serve as the evolutionary record of the constitution:
- Each violation represents a gap in understanding
- Each fix strengthens the constitutional fabric
- Patterns reveal systemic weaknesses
- Resolutions document collective learning

The WHY file system ensures that Lupopedia's constitution is not static but evolves through real-world interaction, learning, and improvement.

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

## AGAPE Integration

AGAPE is the technical resilience and self-healing framework consisting of:

* Fallback ladders (multi-agent handoff)
* Environment probing (violation detection)
* Graceful degradation (20-minute actor message timeout → trigger teaching/hand-off to another actor)
* Evidence-driven validation (no heartbeat/status polling — only track when_updated)
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
