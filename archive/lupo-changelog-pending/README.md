---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-changelog-pending/README.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-changelog-pending/README.md"
  status: "active"
  when_updated: "20260416215839"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/changelog-pending-readme.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/changelog-pending-readme"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: null
  parent_pk_id: null
  lupopedia.schema: documentation
  title: "Changelog Pending Buffer (README)"
  summary: "Describes lupo-changelog-pending/ role for atomic changelog fragments before merge to version CHANGELOG."
---
# Changelog Pending Buffer

Agents write **one fragment per completed task** here (Markdown or JSON shape per project convention). The merge script `lupo-scripts/merge_changelog_buffer.py` is the **only** supported path that appends to versioned `CHANGELOG.md` and archives processed fragments.

## Layout

- **Pending:** `lupo-changelog-pending/*.{md,json}` (examples may exist; absence of `.json` does not invalidate the buffer)
- **Archived (4.1.2):** `lupo-docs/versions/4.1.2/buffer/archive/`

## Weekly report cross-check

Executive report references to “changelog buffer” are satisfied by this directory plus the archived folder above. See `lupo-docs/versions/4.1.2/status/weekly_report_evidence_index_20260416.md`.
