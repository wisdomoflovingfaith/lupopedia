---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260402000000"
  file_path_from_root: "lupo-docs/implementations/_template/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/_template/README.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "implementation-template"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "implementation"
  artifact_kind: "template"
  purpose: "Template for new implementation folders"
  tags:
  - "implementation"
  - "template"
  - "documentation"
---

# Implementation Template

This folder serves as a template for creating new implementation folders.

## Standard Structure

Each implementation folder should contain:

- `README.md` - Overview and status of the implementation
- `changelog.md` - Changes to the implementation over time
- `questions/` - **UNCERTAINTY**: Structured inquiry during implementation
  - `THREAD_INDEX.md` - Index of all questions by level
  - `critical/` - Questions that HALT implementation (require human decision)
  - `optimization/` - Better approaches found (proceed with assumption)
  - `clarification/` - Minor ambiguities (document assumption, continue)
- `decisions/` - **RESOLUTIONS**: Final decisions made during implementation
- `answers/` - **RESPONSES**: Human answers to implementation questions
- `comments/` - **DIALOGUE**: Ongoing discussion and notes
- `authors.md` - **WHO**: Authors, contributors, and provenance
- `edges.md` - **WHERE**: System-wide relational mapping
- `todo.md` - Remaining tasks and TODO items
- `{feature}.md` - Specific implementation documentation (lowercase_with_underscores)
- `versions/` - Version snapshots (e.g., `v1.0.0/`)
- `tests/` - Test files and coverage documentation

## The Six-Layer Documentation Model

| Layer | Question | File | Purpose |
|-------|----------|-------|---------|
| **WHAT** | What to build? | PRD | Requirements |
| **HOW** | How to build? | Implementation files | Technical execution |
| **UNCERTAINTY** | What needs clarification? | questions/ | Structured inquiry during implementation |
| **RESOLUTION** | How was uncertainty resolved? | decisions/ + answers/ | Final decisions and human responses |
| **WHY** | Why these decisions? | comments/ | Rationale & ongoing dialogue |
| **WHO** | Who built it? | authors.md | Human provenance |
| **WHERE** | Where does it connect? | edges.md | System-wide mapping |

## Implementation Questions Structure

Questions are organized by level, matching the structured inquiry framework:

```
questions/
├── THREAD_INDEX.md
├── critical/
│   ├── 20260402_120000_QUESTION_authentication_approach.md
│   └── 20260402_130000_ANSWER_use_token_based_auth.md
├── optimization/
│   ├── 20260402_140000_QUESTION_found_better_algorithm.md
│   └── 20260402_150000_ANSWER_stay_with_current.md
└── clarification/
    ├── 20260402_160000_QUESTION_timezone_assumption.md
    └── 20260402_161000_ANSWER_utc_confirmed.md
```

### Question Levels

| Level | When to Use | Action | Example |
|-------|-------------|--------|---------|
| **Critical** | Implementation can go different ways | HALT, await human decision | "Token or session-based auth?" |
| **Optimization** | Found better approach, current works | Document, proceed with assumption | "Found faster algorithm - switch?" |
| **Clarification** | Minor ambiguity, reasonable assumption | Document assumption, continue | "Assuming UTC timezone - confirm?" |

## Version Snapshots

Create `versions/v{version}/` directories for major implementation milestones:

```
versions/
├── v1.0.0/
│   ├── README.md
│   ├── feature1.md
│   └── feature2.md
└── v1.1.0/
    ├── README.md
    └── feature1.md
```

## Tests Directory

Include a `tests/` directory with:

- `README.md` - Test overview and coverage
- `test_{component}.php` - Unit tests
- `integration_test.php` - Integration tests

## Naming Convention

- Folder name: `{prd_file_stem}` — exact PRD basename without `.md` (e.g., `25_departments_system` for `25_departments_system.md`)
- Files: `lowercase_with_underscores` (e.g., `access_control.md`)
- No spaces or camelCase

## Header Requirements

Each implementation file should have LUPOPEDIA headers with:
- `parent_prd` field linking to the PRD
- Proper `file_path_from_root`
- Correct `web_path`
- Cross-link edges to PRD

Example:
```yaml
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260402000000"
  file_path_from_root: "lupo-docs/implementations/25_departments_system/feature.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/25_departments_system/feature.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "25-departments-implementation"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  parent_prd: "25_departments_system"
  artifact_type: "implementation"
  artifact_kind: "question"
  purpose: "Critical question about authentication approach"
  tags:
    - "implementation"
    - "question"
    - "critical"
    - "authentication"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/25_departments_system.md"
      type: questions
      weight: 1.0
      reason: "PRD this implementation questions"
---
```

## Usage

1. Copy this folder to `lupo-docs/implementations/{prd_file_stem}/` (see **PRD 31**)
2. Update all headers with correct paths and parent PRD
3. Add implementation files as needed
4. Update `lupo-docs/implementations/README.md` index
