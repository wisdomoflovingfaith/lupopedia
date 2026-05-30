---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: changelog-pending/README.md
  web_path: https://www.lupopedia.com/lupopedia/changelog-pending/README.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/changelog-pending-readme.toon
  atoms_toon: null
  transcript_jsonl: 0/development/changelog-pending-readme
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: Changelog Pending Buffer (README)
  summary: Describes changelog-pending/ role for atomic changelog fragments before merge to version CHANGELOG.
---
# Changelog Pending Buffer

Agents write **one fragment per completed task** here (Markdown or JSON shape per project convention). The merge script `scripts/merge_changelog_buffer.py` is the **only** supported path that appends to versioned `CHANGELOG.md` and archives processed fragments.

## Layout

- **Pending:** `changelog-pending/*.{md,json}` (examples may exist; absence of `.json` does not invalidate the buffer)
- **Archived (4.1.2):** `docs/versions/4.1.2/buffer/archive/`

## Weekly report cross-check

Executive report references to “changelog buffer” are satisfied by this directory plus the archived folder above. See `docs/versions/4.1.2/status/weekly_report_evidence_index_20260416.md`.
