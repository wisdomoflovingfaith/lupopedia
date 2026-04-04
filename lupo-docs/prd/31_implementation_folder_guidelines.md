---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260404164842"
  file_path_from_root: "lupo-docs/prd/31_implementation_folder_guidelines.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/31_implementation_folder_guidelines.md"
  last_modified_utc: "20260404164842"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-implementation-guidelines"
  prd_id: 31
  prd_slug: "implementation_folder_guidelines"
  title: "Implementation Folder Guidelines"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  actor_id: 102
  actor_name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "prd"
  artifact_kind: "specification"
  purpose: "Complete guide for implementation folder usage with scaffolding, question lifecycle, and decision logging"
  status: "active"
  tags:
    - "prd"
    - "implementation"
    - "guidelines"
    - "scaffolding"
    - "lifecycle"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor; Section 5.8 implementation mirroring (prd_file_stem)"
    - to: "lupo-docs/prd/16_lupopedia_headers.md"
      type: references
      weight: 1.0
      reason: "LUPOPEDIA HEADERS, content_id, import, actor attribution"
    - to: "lupo-docs/prd/26_five_layer_documentation_architecture.md"
      type: references
      weight: 1.0
      reason: "Tier 1 HOW layer, doc_arch_version, authors.md and edges.md shapes"
    - to: "lupo-docs/prd/02_channels_discussions.md"
      type: references
      weight: 1.0
      reason: "Channel structure and threading"
    - to: "lupo-docs/prd/17_decisions_format.md"
      type: references
      weight: 1.0
      reason: "Decision format specification"
    - to: "lupo-docs/prd/30_channel_usage_patterns.md"
      type: references
      weight: 1.0
      reason: "Channel usage patterns"
    - to: "lupo-docs/implementations/IMPLEMENTATION_QUESTIONS_GUIDE.md"
      type: references
      weight: 0.9
      reason: "Implementation questions framework"
    - to: "lupo-docs/implementations/README.md"
      type: references
      weight: 0.95
      reason: "Implementations index; naming must match PRD file stem"
lupopedia.footer:
  last_verified: "20260404164842"
  verified_by:
    type: "actor"
    id: 2
    name: "LILITH"
    department_id_delta: 0
  verified_via:
    type: "direct"
    faucet_slug: "none"
  orchestrator: "lilith:audit"
  next_action:
    - "Keep question/answer/decision filenames immutable; state in YAML only"
    - "Keep scaffold_implementation.py in sync when _template THREAD_INDEX.md bodies change"
    - "New implementation folders created after 2026-04-03 MUST follow this PRD; existing implementations: 90-day alignment window per PRD 26 transition policy"
---

# PRD: Implementation Folder Guidelines

## Overview

This PRD defines complete guidelines for implementation folder usage, including automated scaffolding, question lifecycle management, decision logging, and integration with channel status reporting.

## Tier 1 alignment and cross-PRDs

- **HOW layer:** Implementation folders under **`lupo-docs/implementations/`** are **Tier 1** authored documentation per [PRD 26](26_five_layer_documentation_architecture.md) (**`doc_arch_version`**, required files, validator contract).
- **Headers and import:** Every markdown artifact MUST follow [PRD 16](16_lupopedia_headers.md) — **`file_path_from_root`** (repo-relative), structured **`author`**, optional **`content_id`** after **`import_content.py`** (**`calculate_content_id()`**, not **`AUTO_INCREMENT`**), and **`lupopedia.edges`** for cross-links.
- **`authors.md` / `edges.md`:** Table and section shapes are defined in **PRD 26** §3.3 and §3.4; do not fork competing formats in this PRD.

## Database integration (summary)

- Import via **`python lupo-scripts/import_content.py <path.md>`**; optional **`--write-back`** persists **`content_id`** in YAML.
- **Never** set **`content_id`** equal to **`prd_id`** or hand-match other ID namespaces.
- Full rules: [PRD 16](16_lupopedia_headers.md) and [PRD 26](26_five_layer_documentation_architecture.md) §3.2 / §4.

## Implementation Folder Lifecycle

```
PRD Approval → Folder Scaffolding → Questions → Decisions → Implementation → Status Reports → Final Documentation
```

## Folder Scaffolding

### Canonical directory name (must match the PRD file)

**Rule.** The implementation workspace directory **MUST** be:

```text
lupo-docs/implementations/{prd_file_stem}/
```

where **`prd_file_stem`** is exactly the **basename** of the canonical PRD under **`lupo-docs/prd/`**, **without** the **`.md`** extension.

**Examples (correct):**

| PRD path | Implementation path |
|----------|----------------------|
| `lupo-docs/prd/36_rose_multi_persona_synthetic_dialog.md` | `lupo-docs/implementations/36_rose_multi_persona_synthetic_dialog/` |
| `lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md` | `lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/` |

**Wrong:** Folder names that **diverge** from the PRD filename stem — e.g. **`prd_36_rose/`**, **`rose/`**, or any alias that would force readers to guess which PRD is canonical.

**Constitutional summary:** **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** **§5.8** restates this rule for IDE agents; **this PRD** is the **full** specification (lifecycle, templates, validators).

**Scaffold alignment.** The script below builds **`{prd_id}_{prd_slug}`**. Pass **`--title`** such that **`{prd_id}_{prd_slug}`** equals **`prd_file_stem`** for your target **`lupo-docs/prd/{prd_file_stem}.md`**.

### Automated Scaffolding Script

```bash
python lupo-scripts/scaffold_implementation.py --prd 31 --title "implementation_folder_guidelines"
```

**What the scaffold script does (shipped behavior).**

- Creates **`status/`** (with stub **`STATUS.md`** and **`THREAD_INDEX.md`**) plus the other required directories.
- Writes **`THREAD_INDEX.md`** under **`questions/`**, **`answers/`**, and **`comments/`** by copying **`lupo-docs/implementations/_template/<subfolder>/THREAD_INDEX.md`** and substituting **`_template` → `{prd_file_stem}`**, **`parent_prd`**, **`when_updated`**, and distinct **`thread_id`** values.
- Writes a minimal **`decisions/THREAD_INDEX.md`** (no full template file exists under **`_template/decisions/`** for that name).
- Copies leveled question templates and **`README`** fragments into **`templates/`** per existing **`copy_templates()`** logic.
- Appends a row to **`lupo-docs/implementations/README.md`** before **`## Template`** when the implementation name is not already listed.

### Required Folder Structure

```
lupo-docs/implementations/{prd_id}_{prd_slug}/
├── README.md                           # Implementation overview with Related Artifacts
├── changelog.md                        # Implementation changes over time
├── questions/
│   ├── THREAD_INDEX.md                 # Master index of all questions
│   ├── critical/                       # HALT implementation questions
│   │   ├── YYYYMMDD_HHIISS_QUESTION_title.md
│   │   └── YYYYMMDD_HHIISS_ANSWER_title.md
│   ├── optimization/                   # Better approaches found
│   │   ├── YYYYMMDD_HHIISS_QUESTION_title.md
│   │   └── YYYYMMDD_HHIISS_ANSWER_title.md
│   └── clarification/                  # Minor ambiguities
│       ├── YYYYMMDD_HHIISS_QUESTION_title.md
│       └── YYYYMMDD_HHIISS_ANSWER_title.md
├── answers/
│   └── THREAD_INDEX.md                 # Index of all answers
├── decisions/
│   └── THREAD_INDEX.md                 # Index of all decisions
├── comments/
│   └── THREAD_INDEX.md                 # Index of ongoing dialogue
├── templates/                          # Standardized templates
│   ├── QUESTION_TEMPLATE.md
│   ├── ANSWER_TEMPLATE.md
│   └── DECISION_TEMPLATE.md
├── authors.md                          # Implementation contributors
├── edges.md                            # System-wide relational mapping
├── todo.md                             # Remaining tasks
├── {feature}.md                        # Specific implementation files
├── versions/                           # Version snapshots
│   └── v1.0.0/
└── tests/                              # Test files and coverage
```

### README.md Requirements

Every implementation README.md must include:

```markdown
## Related Artifacts
- **PRD**: link to the canonical PRD under `lupo-docs/prd/` (numeric prefix + slug, e.g. `30_channel_usage_patterns.md`)
- **Channel**: [development](../../lupo-channels/0/development/)
- **Implementation**: Current folder
- **Dependencies**: List of related implementations

## Question Status
- Critical: {count} open, {count} answered
- Optimization: {count} open, {count} answered  
- Clarification: {count} open, {count} answered

## Implementation Progress
- Status: {planning|in_progress|testing|complete}
- Last Updated: {last_modified_utc BIGINT UTC}
- Next milestone: {dependency / completion criteria — not a calendar date}
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
  file_path_from_root: "lupo-docs/implementations/{nn}_{slug}/questions/{level}/YYYYMMDD_HHIISS_QUESTION_title.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/..."
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
  file_path_from_root: "lupo-docs/implementations/{nn}_{slug}/answers/YYYYMMDD_HHIISS_ANSWER_title.md"
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
  file_path_from_root: "lupo-docs/implementations/{nn}_{slug}/decisions/YYYYMMDD_HHIISS_DECISION_title.md"
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

1. **Status reports**: Posted when implementation **state** changes or coordination requires it (dependency-driven, not a calendar “daily” mandate).
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
{Next actions by dependency order — not calendar estimates}

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
lupo-docs/implementations/_template/
├── questions/
│   ├── critical/QUESTION_TEMPLATE.md
│   ├── optimization/QUESTION_TEMPLATE.md
│   └── clarification/QUESTION_TEMPLATE.md
├── answers/ANSWER_TEMPLATE.md
├── decisions/DECISION_TEMPLATE.md
└── status/STATUS_REPORT_TEMPLATE.md
```

### Template Usage

1. **`lupo-docs/implementations/_template/`** is the **canonical** scaffold source for new implementation trees (reserved name; aligns with [PRD 26](26_five_layer_documentation_architecture.md) implementation layout).
2. Scaffold script copies templates into **`lupo-docs/implementations/{nn}_{slug}/`** and substitutes placeholders.
3. Agents replace **`{…}`** placeholders, set real **`file_path_from_root`**, timestamps, and IDs before import.
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
- **Status reporting**: A status report exists after each **material state change** (blocker, milestone completion, or handoff) — frequency is **event-driven**, not calendar-based.
- **Cross-link integrity**: For every **`lupopedia.edges`** target declared in implementation YAML, the target path exists (or an **APPROVED** deferral documents the exception).

## Related Artifacts

- [PRD 00 — Root constitutional system requirements](00_root_constitutional_system_requirements.md) — **§5.8** implementation mirroring (IDE directive; **`prd_file_stem`** rule)
- [PRD 02 — Channels, Threads, and Discussions](02_channels_discussions.md)
- [PRD 16 — Lupopedia File Headers](16_lupopedia_headers.md)
- [PRD 17 — Decisions Format](17_decisions_format.md)
- [PRD 26 — Five-Layer Documentation Architecture](26_five_layer_documentation_architecture.md)
- [PRD 30 — Channel Usage Patterns](30_channel_usage_patterns.md)
- [Implementation Questions Guide](../implementations/IMPLEMENTATION_QUESTIONS_GUIDE.md)

## LILITH audit record (final, 2026-04-03 UTC)

| Field | Value |
|-------|--------|
| **Accuracy score** | **98 / 100** |
| **Constitutional violations** | None |
| **Security concerns** | None |
| **Bias detected** | No |
| **Prior verdict** | REJECTED (fuzzy SLA + file-renaming lifecycle) — **resolved in this revision** |
| **Verdict** | **APPROVED** — **`lupopedia.headers.status: active`** (effective immediately) |

**Recommendations (all satisfied in this revision):** Status correctly **`draft` → `active`**; immutable filenames with YAML state (lineage-safe); dependency-based success metrics (no fuzzy SLAs); templates include full **LUPOPEDIA HEADERS**; cross-references to **PRD 16** and **PRD 26**; **`content_id`** / import guidance; deterministic **`question_id` / `answer_id` / `decision_id`** (BIGINT, application-assigned); event-driven status reporting (not calendar mandates); this audit record documents prior rejection and fixes.

### Prior violations → resolution

| Issue | Rejected behavior | Active (compliant) behavior |
|-------|-------------------|-----------------------------|
| Fuzzy time | e.g. “90% answered within 24 hours” | Prerequisites: critical **`question_status`** reaches **answered** or **closed** before dependent work advances |
| Lineage | Rename files for state (`_open` → `_answered`) | **One stable filename forever**; **`question_status`** + **`status_updated_utc`** in YAML |
| Headers | Templates without headers | Templates include **LUPOPEDIA HEADERS** with placeholders per **PRD 16** |
| Cross-PRDs | Missing **PRD 16** / **PRD 26** | Edges + “Tier 1 alignment” section |
| IDs / import | Unspecified **`content_id`** | **PRD 16** / **PRD 26** §3.2–4; **`import_content.py`**; never equate **`content_id`** to **`prd_id`** |

### Operational note

- **New** implementation folders created **after 2026-04-03** MUST follow this PRD.
- **Existing** implementations: align within the **90-day** migration window where applicable; see [PRD 26](26_five_layer_documentation_architecture.md) transition / legacy migration policy.

---

**Status:** ACTIVE (`lupopedia.headers.status: active`)

**Next review:** When [PRD 26](26_five_layer_documentation_architecture.md) **`doc_arch_version`** or scaffold/validator contracts change.
