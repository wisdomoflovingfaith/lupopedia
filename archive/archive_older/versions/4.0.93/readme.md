---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260403193000"
  file_path_from_root: "docs/versions/4.0.93/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/README.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: version_readme
  thread_id: "version-4.0.93-readme"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "approved"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: docs/versions/4.0.93/README.md — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/README.md

# Lupopedia 4.0.93 (frozen)

This directory is the **frozen documentation snapshot** for release **4.0.93**. Active planning and backlog live in **`docs/versions/4.0.94/`**.

## Contents

| Path | Role |
|------|------|
| `PLAN.md` | What was planned and completed for this release |
| `TODO.md` | Completed freeze checklist; open work is listed under “Carried to 4.0.94” |
| `CHANGELOG.md` | Per-version change history |
| `edges.md` | Edges between PRDs, doctrine, validators, and this version |
| `decisions/` | Formal decisions, dialogs, directives (`THREAD_INDEX.md` + timestamped files) |
| `questions/` | Open questions (`THREAD_INDEX.md` + timestamped files) |
| `answers/` | Answers (`THREAD_INDEX.md` + timestamped files) |
| `comments/` | Short notes (`THREAD_INDEX.md` + timestamped files) |

## Frozen core PRDs (`docs/prd/`)

Approved for 4.0.93 (`status: "approved"` in each header):

- `00_root_constitutional_system_requirements.md`
- `16_lupopedia_headers.md`
- `17_decisions_format.md`
- `26_five_layer_documentation_architecture.md`
- `27_installer_requirements.md`
- `28_semantic_monitoring_widget.md`
- `29_project_structure.md`

PRDs **30** and **31** are **not** frozen here; working copies live under `docs/versions/4.0.94/prd/`.

## Thread file naming (canonical)

**Authoritative specification:** [PRD 17 — Thread filename pattern (authoritative)](../../prd/17_decisions_format.md#thread-filename-pattern-authoritative) (per-folder patterns, `STATUS` only in `decisions/`, `TYPE` tokens, `HHIISS`, optional `YYYYMMDDHHIISS` prefix).

Summary (same release):

```text
YYYYMMDD_HHIISS_TYPE_title.md
```

| Part | Meaning |
|------|---------|
| `YYYYMMDD` | UTC date |
| `HHIISS` | UTC time (24h); hours `00`–`23` only |
| `TYPE` | One of: `DECISION`, `QUESTION`, `ANSWER`, `COMMENT`, `DIALOG`, `DIRECTIVE` |
| `title` | Lowercase words separated by underscores |

**Examples**

- `decisions/20260402_120000_DECISION_APPROVED_channel_directory_structure.md`
- `questions/20260402_130000_QUESTION_how_to_migrate_channels.md`
- `answers/20260402_140000_ANSWER_migrate_channels_archive.md`
- `comments/20260402_150000_COMMENT_great_solution.md`

`DIALOG` and `DIRECTIVE` files live under `decisions/` (same as other decision-class threads).

## Tag

Git tag: **`v4.0.93`** marks this freeze. Ongoing development uses **`4.0.94`** in `config/global_atoms.yaml` and `docs/versions/4.0.94/`.
