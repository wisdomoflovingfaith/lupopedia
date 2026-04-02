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
- `discussions/` - **WHY**: Design discussions as threads (not single file)
  - `THREAD_INDEX.md` - Index of all discussion threads
  - `{thread_name}/` - Individual discussion threads
    - `YYYYMMDD_HHIISS_ACTOR_PURPOSE_TITLE.md` - Thread messages
- `authors.md` - **WHO**: Authors, contributors, and provenance
- `edges.md` - **WHERE**: System-wide relational mapping
- `todo.md` - Remaining tasks and TODO items
- `{feature}.md` - Specific implementation documentation (lowercase_with_underscores)
- `versions/` - Version snapshots (e.g., `v1.0.0/`)
- `tests/` - Test files and coverage documentation

## The Five-Layer Documentation Model

| Layer | Question | File | Purpose |
|-------|----------|-------|---------|
| **WHAT** | What to build? | PRD | Requirements |
| **HOW** | How to build? | Implementation files | Technical execution |
| **WHY** | Why these decisions? | discussions/ threads | Rationale & trade-offs |
| **WHO** | Who built it? | authors.md | Human provenance |
| **WHERE** | Where does it connect? | edges.md | System-wide mapping |

## Discussion Threads Structure

Discussions are organized as threads, matching the channel/thread architecture:

```
discussions/
├── THREAD_INDEX.md
├── database_schema/
│   ├── 20260402_120000_cursor_design_database_schema.md
│   └── 20260402_121500_lilith_review_comments.md
├── foreign_key_policy/
│   └── 20260402_121500_lilith_constitutional_violation.md
└── permission_structure/
    └── 20260402_123000_cursor_json_schema_design.md
```

### Thread Naming Convention

Files follow the channel format: `YYYYMMDD_HHIISS_ACTOR_PURPOSE_TITLE.md`

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

- Folder name: `{number}_{name}` (e.g., `25_departments_systems`)
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
  file_path_from_root: "lupo-docs/implementations/25_departments_systems/feature.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/25_departments_systems/feature.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "25-departments-implementation"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  parent_prd: "25_departments_systems"
  artifact_type: "implementation"
  artifact_kind: "documentation"
  purpose: "Implementation of specific feature"
  tags:
  - "implementation"
  - "departments"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/25_departments_systems.md"
      type: implements
      weight: 1.0
      reason: "PRD this implements"
---
```

## Usage

1. Copy this folder to `lupo-docs/implementations/{number}_{name}/`
2. Update all headers with correct paths and parent PRD
3. Add implementation files as needed
4. Update `lupo-docs/implementations/README.md` index
