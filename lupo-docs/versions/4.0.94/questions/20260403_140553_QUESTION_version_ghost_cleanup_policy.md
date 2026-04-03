---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  file_path_from_root: "lupo-docs/versions/4.0.94/questions/20260403_140553_QUESTION_version_ghost_cleanup_policy.md"
  when_updated: "20260403140552"
  channel_id: 42
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:audit"
  artifact_type: question
  status: resolved
  purpose: "Policy for remaining version ghost findings (phantom paths, 3.0.x prose)"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/implementations/29_project_structure/status/version_ghosts_report.json"
      type: references
      weight: 1.0
      reason: "Scanner output — 34 files with critical findings at report generation"
---

# file: QUESTION — version ghost cleanup policy

# QUESTION: How to handle remaining critical version ghost files?

## Context

`lupo-scripts/find_version_ghosts.py` reports **34** doctrine/PRD files with **critical** findings (e.g. **`phantom_legacy_path_slash_docs_or_similar`**, **`three_zero_semver_reference`**) in `version_ghosts_report.json` (paths under `lupo-docs/doctrine/` and `lupo-docs/prd/`).

## Question

What is the **default policy** per finding category — batch script vs file-by-file?
