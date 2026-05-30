---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/31_A-i_IMPLEMENTATION_FOLDER_GUIDELINES.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/31_A-i_IMPLEMENTATION_FOLDER_GUIDELINES.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/31_implementation_folder_guidelines.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/implementation-folder-guidelines
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_31_A-i
  title: 'PRD: Implementation Folder Guidelines'
  summary: null
---
# PRD: Implementation Folder Guidelines

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

## Overview

This PRD defines complete guidelines for implementation folder usage, including automated scaffolding, question lifecycle management, decision logging, and integration with channel status reporting.

## Tier 1 alignment and cross-PRDs

- **HOW layer:** Implementation folders under **`docs/implementations/`** are **Tier 1** authored documentation per [PRD 26](26_five_layer_documentation_architecture.md) (**`doc_arch_version`**, required files, validator contract).
- **Headers and import:** Every markdown artifact MUST follow [PRD 16](16_lupopedia_headers.md) ?????" **`file_path_from_root`** (repo-relative), structured **`author`**, optional **`content_id`** after **`import_content.py`** (**`calculate_content_id()`**, not **`AUTO_INCREMENT`**), and **`lupopedia.edges`** for cross-links.
- **`authors.md` / `edges.md`:** Table and section shapes are defined in **PRD 26** ??section 3.3 and ??section 3.4; do not fork competing formats in this PRD.
- **Staged UI and PRD-to-ship gates:** Template-first operator/admin workflow (**`templates/`** then **`includes/lang/`** then entrypoints) is **[PRD 45](45_template_first_staged_ui_workflow.md)**; implementation mirrors here document **WHAT**; **PRD 45** sequences **HOW** for UI.

## Database integration (summary)

- Import via **`python scripts/import_content.py <path.md>`**; optional **`--write-back`** persists **`content_id`** in YAML.
- **Never** set **`content_id`** equal to **`prd_id`** or hand-match other ID namespaces.
- Full rules: [PRD 16](16_lupopedia_headers.md) and [PRD 26](26_five_layer_documentation_architecture.md) ??section 3.2 / ??section 4.

## Implementation Folder Lifecycle

```
PRD Approval ?????' Folder Scaffolding ?????' Questions ?????' Decisions ?????' Implementation ?????' Status Reports ?????' Final Documentation
```

## Folder Scaffolding

### Canonical directory name (must match the PRD file)

**Rule.** The implementation workspace directory **MUST** be:

```text
docs/implementations/{prd_file_stem}/
```

where **`prd_file_stem`** is exactly the **basename** of the canonical PRD under **`docs/prd/`**, **without** the **`.md`** extension.

**Non-negotiable:** The folder name must match the PRD filename **character-for-character** after removing **`.md`** only. No pluralization, abbreviations, or alternate stems (e.g. do **not** create **`25_departments_systems/`** when the PRD file is **`25_departments_system.md`**).

**Examples (correct):**

| PRD path | Implementation path |
|----------|----------------------|
| `docs/prd/36_rose_multi_persona_synthetic_dialog.md` | `docs/implementations/36_rose_multi_persona_synthetic_dialog/` |
| `docs/prd/33_softaculous_certification_4_2_0_gate.md` | `docs/implementations/33_softaculous_certification_4_2_0_gate/` |
| `docs/prd/25_departments_system.md` | `docs/implementations/25_departments_system/` |

**Wrong:** Folder names that **diverge** from the PRD filename stem ?????" e.g. **`prd_36_rose/`**, **`rose/`**, **`25_departments_systems/`** (extra **`s`**) when the PRD is **`25_departments_system.md`**, or any alias that would force readers to guess which PRD is canonical.

**Constitutional summary:** **`docs/prd/00_root_constitutional_system_requirements.md`** **??section 5.8** restates this rule for IDE agents; **this PRD** is the **full** specification (lifecycle, templates, validators). **PRD count and consolidation thresholds** (when to stop adding mirrored implementation trees without merging PRDs first) are in **`docs/prd/99_limits_for_everything_and_why.md`**.

**Scaffold alignment.** The script below builds **`{prd_id}_{prd_slug}`**. Pass **`--title`** such that **`{prd_id}_{prd_slug}`** equals **`prd_file_stem`** for your target **`docs/prd/{prd_file_stem}.md`**.

### Automated Scaffolding Script

```bash
python scripts/scaffold_implementation.py --prd 31 --title "implementation_folder_guidelines"
```

**Dated status files (`add-status` subcommand).** After the tree exists, create a timestamped **`status/YYYYMMDD_HHIISS_STATUS_{slug}.md`**, optional **`references`** / **`supersedes`** edge to the **prior** status artifact (sorted per **PRD 37** ??section 10), and append **`status/THREAD_INDEX.md`**:

```bash
python scripts/scaffold_implementation.py add-status --impl 37_kairos_channel_memory_consolidation --title "ingest_pipeline_ready"
python scripts/scaffold_implementation.py add-status --impl 37 --title "milestone" --edge-type references --non-interactive
```

**What the scaffold script does (shipped behavior).**

- Creates **`status/`** (with stub **`STATUS.md`** and **`THREAD_INDEX.md`**) plus the other required directories.
- Writes **`THREAD_INDEX.md`** under **`questions/`**, **`answers/`**, and **`comments/`** by copying **`docs/implementations/_template/<subfolder>/THREAD_INDEX.md`** and substituting **`_template` ?????' `{prd_file_stem}`**, **`parent_prd`**, **`when_updated`**, and distinct **`thread_id`** values.
- Writes a minimal **`decisions/THREAD_INDEX.md`** (no full template file exists under **`_template/decisions/`** for that name).
- Copies leveled question templates and **`README`** fragments into **`templates/`** per existing **`copy_templates()`** logic.
- Appends a row to **`docs/implementations/README.md`** before **`## Template`** when the implementation name is not already listed.

### Channel orchestration (multi-actor dialog) ?????" product cross-reference

Implementation work that touches **channel chat** SHOULD align with the **simple routing pattern** (no **`mention_actor_ids`** JSON column; routing-only addressee field):

| Topic | Canonical PRD |
|--------|----------------|
| UI + transcript semantics | **PRD 18** ?????" *Multi-actor routing (simple pattern)* |
| ROSE switchboard + synthetic rows | **PRD 36** ??section 1.3 |
| KAIROS full-thread ingest | **PRD 37** ??section 10.6 |
| Auth / actor mental model | **PRD 05** ?????" *Channel communication model* |

**`lupo_dialog_messages` (current TOON / install SQL):**

| Column | Role |
|--------|------|
| **`to_actor_id`** | **Routing recipient**; **NULL** = broadcast. *Directive synonym: **said-to** / **`said_to_actor_id`** ?????" same meaning.* |
| **`dialog_thread_id`**, **`created_ymdhis`** | **Ordering** and coarse threading (canonical today). |
| **`parent_dialog_message_id`** | **Not** in current install TOON ?????" **planned** only if a future schema change adds it; until then use thread + timestamps (and allowed **`metadata_json`** provenance subkeys). |

**Visibility:** **Channel membership** controls who may **read** the channel ?????" **not** **`to_actor_id`**. **ROSE** / **THOTH** / **KAIROS** read **full threads** when building context (**PRD 18**, **PRD 36**, **PRD 37**).

### Required Folder Structure

```
docs/implementations/{prd_id}_{prd_slug}/
+-- README.md                           # Implementation overview with Related Artifacts
+-- changelog.md                        # Implementation changes over time
+-- questions/
|   +-- THREAD_INDEX.md                 # Master index of all questions
|   +-- critical/                       # HALT implementation questions
|   |   +-- YYYYMMDD_HHIISS_QUESTION_title.md
|   |   +-- YYYYMMDD_HHIISS_ANSWER_title.md
|   +-- optimization/                   # Better approaches found
|   |   +-- YYYYMMDD_HHIISS_QUESTION_title.md
|   |   +-- YYYYMMDD_HHIISS_ANSWER_title.md
|   +-- clarification/                  # Minor ambiguities
|       +-- YYYYMMDD_HHIISS_QUESTION_title.md
|       +-- YYYYMMDD_HHIISS_ANSWER_title.md
+-- answers/
|   +-- THREAD_INDEX.md                 # Index of all answers
+-- decisions/
|   +-- THREAD_INDEX.md                 # Index of all decisions
+-- comments/
|   +-- THREAD_INDEX.md                 # Index of ongoing dialogue
+-- templates/                          # Standardized templates
|   +-- QUESTION_TEMPLATE.md
|   +-- ANSWER_TEMPLATE.md
|   +-- DECISION_TEMPLATE.md
+-- authors.md                          # Implementation contributors
+-- edges.md                            # System-wide relational mapping
+-- todo.md                             # Remaining tasks
+-- {feature}.md                        # Specific implementation files
+-- versions/                           # Version snapshots
|   +-- v1.0.0/
+-- tests/                              # Test files and coverage
```

### README.md Requirements

Every implementation README.md must include:

```markdown
## Related Artifacts
- **PRD**: link to the canonical PRD under `docs/prd/` (numeric prefix + slug, e.g. `30_channel_usage_patterns.md`)
- **Channel**: [development](../../channels/0/development/)
- **Implementation**: Current folder
- **Dependencies**: List of related implementations

## Question Status
- Critical: {count} open, {count} answered
- Optimization: {count} open, {count} answered  
- Clarification: {count} open, {count} answered

## Implementation Progress
- Status: {planning|in_progress|testing|complete}
- Last Updated: {last_modified_utc BIGINT UTC}
- Next milestone: {dependency / completion criteria ?????" not a calendar date}
```

## Question lifecycle management (immutable filenames)

### Lineage rule (constitutional)

- **Do not rename** question, answer, or decision files to reflect state changes. Renaming destroys filename lineage and conflicts with **Identity & Lineage Doctrine** (no rewriting history).
- **One file per question**, with a **stable name** forever: **`YYYYMMDD_HHIISS_QUESTION_{level}_{title}.md`** per [PRD 17](17_decisions_format.md) UTC filename tokens (`HHIISS` = hour, minute, second).
- **State** is carried in **`lupopedia.headers`** (and optional append-only **Comments** / **state transition** artifacts), not in the basename.

### Lifecycle states (YAML, not filename)

| `question_status` | Meaning |
|-------------------|---------|
| `open` | Awaiting first substantive response |
| `discussion` | Active dialogue |
| `answered` | Answer recorded (see **`answers/`** and **`question_status`**) |
| `closed` | Resolved; no further action on this thread |

### State transition rules

| Current `question_status` | May transition to | Trigger (dependency / event, not clock) |
|---------------------------|-------------------|----------------------------------------|
| `open` | `discussion` | First response or comment in thread |
| `open` | `answered` | Direct answer file linked |
| `discussion` | `answered` | Consensus reached |
| `answered` | `closed` | Implementation work that depended on the answer is complete |
| `answered` | `discussion` | Follow-up question (same file; update YAML + add comment) |

On each transition, set **`status_updated_utc`** (14-digit BIGINT UTC string) in **`lupopedia.headers`**. Optional: add **`comments/YYYYMMDD_HHIISS_COMMENT_state_transition.md`** pointing to this file for audit trail.

### Question Templates

#### QUESTION_TEMPLATE.md
```markdown
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  file_path_from_root: "docs/implementations/{nn}_{slug}/questions/{level}/YYYYMMDD_HHIISS_QUESTION_title.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/..."
  last_modified_utc: "{YYYYMMDDHHIISS}"
  when_updated: "{YYYYMMDDHHIISS}"
  federation_node_id: 0
  channel_id: 42
  thread_id: "{implementation_thread_id}"
  artifact_type: "documentation"
  artifact_kind: "guide"
  purpose: "Implementation question (scaffolded from template)"
  delegation_chain: "cursor:root"
  tags:
    - "question"
    - "implementation"
  question_id: "{generated_bigint_id}"
  question_status: "open"
  status_updated_utc: "{YYYYMMDDHHIISS}"
  level: "{critical|optimization|clarification}"
  implementation_id: "{implementation_folder}"
  related_prd: {prd_id}
---

# Question: {Title}

## Context
{Background information}

## Question
{Clear, specific question}

## Options Considered
| Option | Pros | Cons | Recommendation |
|--------|------|------|----------------|
| A | | | |
| B | | | |

## Impact
{How this affects implementation}

## Required Response
{What kind of response is needed}
```

#### ANSWER_TEMPLATE.md
```markdown
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  file_path_from_root: "docs/implementations/{nn}_{slug}/answers/YYYYMMDD_HHIISS_ANSWER_title.md"
  last_modified_utc: "{YYYYMMDDHHIISS}"
  when_updated: "{YYYYMMDDHHIISS}"
  federation_node_id: 0
  channel_id: 42
  thread_id: "{implementation_thread_id}"
  artifact_type: "documentation"
  artifact_kind: "guide"
  purpose: "Answer to implementation question"
  delegation_chain: "cursor:root"
  tags:
    - "answer"
    - "implementation"
  answer_id: "{generated_bigint_id}"
  question_id: "{original_question_id}"
  level: "{matching_question_level}"
  implementation_id: "{implementation_folder}"
---

# Answer: {Title}

## Response To
{Question title and reference}

## Decision
{Clear answer to the question}

## Rationale
{Why this decision was made}

## Implementation Action
{What the agent should do next}

## Impact Assessment
{Effect on implementation}
```

## Decision Logging

### Required Decision Documentation

All major implementation decisions must be logged in `decisions/` with:

1. **Reference to Triggering Question**: Link to the question that prompted the decision
2. **PRD Section Reference**: Which PRD requirement this addresses
3. **Alternatives Considered**: Options evaluated and rejected
4. **Rationale**: Why this decision was made
5. **Impact**: Effect on implementation and system

### DECISION_TEMPLATE.md
```markdown
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  file_path_from_root: "docs/implementations/{nn}_{slug}/decisions/YYYYMMDD_HHIISS_DECISION_title.md"
  last_modified_utc: "{YYYYMMDDHHIISS}"
  when_updated: "{YYYYMMDDHHIISS}"
  federation_node_id: 0
  channel_id: 42
  thread_id: "{implementation_thread_id}"
  artifact_type: "documentation"
  artifact_kind: "guide"
  purpose: "Implementation decision record"
  delegation_chain: "cursor:root"
  tags:
    - "decision"
    - "implementation"
  decision_id: "{generated_bigint_id}"
  implementation_id: "{implementation_folder}"
  related_prd: {prd_id}
  triggered_by_question: {question_id}
---

# Decision: {Title}

## Context
{Background leading to this decision}

## Triggering Question
Reference: [../questions/{level}/{question_file}.md](../questions/{level}/{question_file}.md)

## Decision Made
{Clear statement of the decision}

## Alternatives Considered
| Alternative | Reason for Rejection |
|-------------|---------------------|
| Option A | {Why rejected} |
| Option B | {Why rejected} |

## Rationale
{Why chosen approach is best}

## Implementation Impact
- Code changes required
- Testing implications
- Documentation updates

## PRD Reference
Section {section} of PRD {prd_id}: {requirement}
```

### Deterministic IDs (`question_id`, `answer_id`, `decision_id`)

- IDs MUST be **BIGINT**, **application-assigned**, **not** MySQL **`AUTO_INCREMENT`** or random client IDs (constitutional **Reserved ID** / allocator doctrine).
- **Filename** carries a **UTC token** `YYYYMMDD_HHIISS` (see [PRD 17](17_decisions_format.md)); header **`question_id` / `answer_id` / `decision_id`** MUST be filled by tooling or an explicit allocator step so they are **stable** for the life of the artifact.
- For markdown imported into **`lupo_contents`**, **`content_id`** follows [PRD 16](16_lupopedia_headers.md) / **`import_content.py`** (separate from logical **`question_id`** fields above).

## Status Reporting Flow

### From Implementation to Channel

1. **Status reports**: Posted when implementation **state** changes or coordination requires it (dependency-driven, not a calendar ???????daily??????? mandate).
2. **Milestone updates**: Announced when milestone **completion criteria** are met.
3. **Blocker notifications**: Critical blockers posted when discovered.
4. **Completion**: Announced when implementation reaches **`complete`** per README criteria.

### Status Report Template

```markdown
# STATUS_REPORT_20260402_160000

## Implementation: {implementation_title}
**PRD**: {prd_id}
**Status**: {in_progress|testing|complete|blocked}

## Progress Summary
- Completed: {list of completed items}
- In Progress: {current work}
- Blocked: {any blockers}

## Questions Resolved (since last report)
- Critical: {count} moved to `answered` or `closed`
- Optimization: {count} moved to `answered` or `closed`
- Clarification: {count} moved to `answered` or `closed`

## Decisions Made
{List of major decisions with references}

## Next Steps
{Next actions by dependency order ?????" not calendar estimates}

## Channel Reference
Discussion in: {channel_name} channel
```

## Cross-Linking Requirements

### LUPOPEDIA HEADERS Cross-References

All implementation files must include:

```yaml
lupopedia.headers:
  related_prds: ["{prd_id}"]
  related_channels: ["{channel_key}"]
  related_implementations: ["{implementation_folder}"]
```

### Edge Linking Requirements

- Questions must link to triggering PRD sections
- Answers must link to their questions
- Decisions must link to triggering questions
- Status reports must link to implementation folder

## Templates Distribution

### Standard Templates Location

```
docs/implementations/_template/
+-- questions/
|   +-- critical/QUESTION_TEMPLATE.md
|   +-- optimization/QUESTION_TEMPLATE.md
|   +-- clarification/QUESTION_TEMPLATE.md
+-- answers/ANSWER_TEMPLATE.md
+-- decisions/DECISION_TEMPLATE.md
+-- status/STATUS_REPORT_TEMPLATE.md
```

### Template Usage

1. **`docs/implementations/_template/`** is the **canonical** scaffold source for new implementation trees (reserved name; aligns with [PRD 26](26_five_layer_documentation_architecture.md) implementation layout).
2. Scaffold script copies templates into **`docs/implementations/{nn}_{slug}/`** and substitutes placeholders.
3. Agents replace **`{???????}`** placeholders, set real **`file_path_from_root`**, timestamps, and IDs before import.
4. Copied files MUST retain full **LUPOPEDIA HEADERS** per templates above (and [PRD 16](16_lupopedia_headers.md)).

## Validation and Compliance

### Automated Checks

```python
# validate_implementation_structure.py
- Verify all required folders exist
- Check THREAD_INDEX.md files are present
- Validate question lifecycle completeness
- Ensure cross-link integrity
- Check template usage compliance
```

### Manual Review Checklist

- [ ] Folder structure complete
- [ ] All questions have valid **`question_status`** / **`status_updated_utc`** in headers (no renames)
- [ ] Decisions reference triggering questions
- [ ] Cross-links are accurate
- [ ] Status reports follow format
- [ ] Templates used consistently; headers complete per [PRD 16](16_lupopedia_headers.md)

## Integration with Channels

### Critical Question Synchronization

1. Agent posts critical question in channel
2. Question copied to implementation/questions/critical/
3. Bidirectional links created
4. Resolution posted in both locations

### Status Broadcasting

- Implementation status automatically summarized for channels
- Progress milestones announced
- Blocker notifications escalated

## Success Metrics (dependency-based)

- **Structure compliance**: 100% of new implementations include the required folder layout and **THREAD_INDEX** files per this PRD and [PRD 26](26_five_layer_documentation_architecture.md).
- **Question resolution**: For each **critical** question, **`question_status`** reaches **`answered`** or **`closed`** before work that **depends** on that answer advances (state machine prerequisite, not a clock SLA).
- **Decision logging**: Each resolved **critical** or **optimization** question has a corresponding **decision** or explicit deferral recorded when the architecture requires it.
- **Status reporting**: A status report exists after each **material state change** (blocker, milestone completion, or handoff) ?????" frequency is **event-driven**, not calendar-based.
- **Cross-link integrity**: For every **`lupopedia.edges`** target declared in implementation YAML, the target path exists (or an **APPROVED** deferral documents the exception).

## Related Artifacts

- [PRD 00 ?????" Root constitutional system requirements](00_root_constitutional_system_requirements.md) ?????" **??section 5.8** implementation mirroring (IDE directive; **`prd_file_stem`** rule)
- [PRD 02 ?????" Channels, Threads, and Discussions](02_channels_discussions.md)
- [PRD 16 ?????" Lupopedia File Headers](16_lupopedia_headers.md)
- [PRD 17 ?????" Decisions Format](17_decisions_format.md)
- [PRD 26 ?????" Five-Layer Documentation Architecture](26_five_layer_documentation_architecture.md)
- [PRD 30 ?????" Channel Usage Patterns](30_channel_usage_patterns.md)
- [Implementation Questions Guide](../implementations/IMPLEMENTATION_QUESTIONS_GUIDE.md)

## LILITH audit record (final, 2026-04-03 UTC)

| Field | Value |
|-------|--------|
| **Accuracy score** | **98 / 100** |
| **Constitutional violations** | None |
| **Security concerns** | None |
| **Bias detected** | No |
| **Prior verdict** | REJECTED (fuzzy SLA + file-renaming lifecycle) ?????" **resolved in this revision** |
| **Verdict** | **APPROVED** ?????" **`lupopedia.headers.status: active`** (effective immediately) |

**Recommendations (all satisfied in this revision):** Status correctly **`draft` ?????' `active`**; immutable filenames with YAML state (lineage-safe); dependency-based success metrics (no fuzzy SLAs); templates include full **LUPOPEDIA HEADERS**; cross-references to **PRD 16** and **PRD 26**; **`content_id`** / import guidance; deterministic **`question_id` / `answer_id` / `decision_id`** (BIGINT, application-assigned); event-driven status reporting (not calendar mandates); this audit record documents prior rejection and fixes.

### Prior violations ?????' resolution

| Issue | Rejected behavior | Active (compliant) behavior |
|-------|-------------------|-----------------------------|
| Fuzzy time | e.g. ???????90% answered within 24 hours??????? | Prerequisites: critical **`question_status`** reaches **answered** or **closed** before dependent work advances |
| Lineage | Rename files for state (`_open` ?????' `_answered`) | **One stable filename forever**; **`question_status`** + **`status_updated_utc`** in YAML |
| Headers | Templates without headers | Templates include **LUPOPEDIA HEADERS** with placeholders per **PRD 16** |
| Cross-PRDs | Missing **PRD 16** / **PRD 26** | Edges + ???????Tier 1 alignment??????? section |
| IDs / import | Unspecified **`content_id`** | **PRD 16** / **PRD 26** ??section 3.2?????"4; **`import_content.py`**; never equate **`content_id`** to **`prd_id`** |

### Operational note

- **New** implementation folders created **after 2026-04-03** MUST follow this PRD.
- **Existing** implementations: align within the **90-day** migration window where applicable; see [PRD 26](26_five_layer_documentation_architecture.md) transition / legacy migration policy.

---

**Status:** ACTIVE (`lupopedia.headers.status: active`)

**Next review:** When [PRD 26](26_five_layer_documentation_architecture.md) **`doc_arch_version`** or scaffold/validator contracts change.


---

## Context?????'Typed, Status?????'Aware, Directional Edged Memory Doctrine (4.0.96)

1. Memory in Lupopedia is represented as a directed graph of nodes and edges. 
  Each memory node is a first-class entity in the semantic network and may be 
  owned by actors, departments, auth_users, channels, federation nodes, or the 
  global system.

2. Every edge in the memory graph has FOUR dimensions:
  - **edge type** (the relationship)
  - **edge context** (the classification of the memory)
  - **edge status** (the epistemic support level)
  - **edge direction** (the traversal orientation)

3. **Edge Direction** defines whether the relationship is:
  - unidirectional (A ?????' B)
  - bidirectional (A ?????" B)
  - restricted-direction (A ?????' B but not B ?????' A unless explicitly defined)
  Reverse traversal MUST NOT be inferred unless explicitly defined.

4. **Edge Type** defines the relationship between nodes, including but not 
  limited to:
  - influences
  - inherits
  - authored_by
  - observed_by
  - contradicts
  - supports
  - consolidates_from
  - refines
  - overrides

5. **Edge Context** defines the classification of the memory node. Context is 
  not based on the content of the memory, but on the structural support 
  provided by the graph. The primary context classifications are:
  - doctrine
  - experiential
  - system_generated
  - countermeasure_generated
  - summary
  - contradictory
  - deprecated

6. **Edge Status** defines the epistemic support level of the memory node:
  - **unsupported**: insufficient supporting edges; provisional memory.
  - **supported**: sufficient supporting edges; validated memory.
  - **needs_review**: conflicting, incomplete, or ambiguous edges requiring 
    agent or human intervention.

7. When `edge_status = 'needs_review'`, a **review_reason** MUST be provided. 
  This field explains *why* the edge requires review and *which agent* should 
  handle it. Examples include:
  - orphaned_edge
  - contradiction
  - new_doctrine
  - schema_drift
  - consolidation_candidate
  - integrity_unknown
  - human_escalation

  Agents use this field to determine their work queues:
  - ANUBIS handles: integrity_unknown, orphaned_edge
  - THOTH handles: schema_drift, contradiction, new_doctrine
  - KAIROS handles: consolidation_candidate
  - Human operator handles: human_escalation

8. Memory nodes may transition between statuses as edges are added, removed, 
  or reclassified. A node may move from unsupported ?????' supported when 
  sufficient supporting edges accumulate.

9. Actors inherit memory edges from:
  - their department
  - their auth_user
  - their federation node
  - their assigned faucets
  - their assigned tasks

10. Memory traversal is context-aware and direction-aware. Actors may only 
   traverse edges permitted by their boundaries, department rules, auth_user 
   pairing, faucet assignments, and operational mode (live, simulation, 
   analysis).

11. No inference is allowed. All edges, contexts, statuses, directions, and 
   review reasons must be explicitly defined in PRDs, database rows, or 
   system-generated memory.

12. Memory is not a flat file. It is a structured, typed, classified, 
   status-aware, direction-aware graph. Traversal depth determines visible 
   memory; deeper traversal reveals more context, subject to boundary rules.

13. All changes to memory structure, edge types, edge contexts, edge statuses, 
   edge directions, or review reasons must be documented in PRDs and versioned.
```
