---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260403140552"
  file_path_from_root: "docs/versions/4.0.94/questions/20260403_140553_QUESTION_version_ghost_cleanup_policy.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: question
  artifact_kind: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "resolved"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: QUESTION — version ghost cleanup policy

# QUESTION: How to handle remaining critical version ghost files?

## Context

`scripts/find_version_ghosts.py` reports **34** doctrine/PRD files with **critical** findings (e.g. **`phantom_legacy_path_slash_docs_or_similar`**, **`three_zero_semver_reference`**) in `version_ghosts_report.json` (paths under `docs/doctrine/` and `docs/prd/`).

## Question

What is the **default policy** per finding category — batch script vs file-by-file?
