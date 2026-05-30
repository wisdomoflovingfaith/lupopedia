---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "docs/doctrine/storage/CHANGELOG_BUFFER_ARCHITECTURE.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/storage/CHANGELOG_BUFFER_ARCHITECTURE.md"
  status: "active"
  when_updated: "20260416182218"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: doctrine
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: "Changelog Buffer Architecture"
  summary: "Rules for the changelog buffer architecture and atomic merging."
---
# Changelog Buffer Architecture

## 1. The Fragment Buffer System
Agents must not write directly to `CHANGELOG.md`. 
- When a task is marked complete, agents must construct exactly one changelog buffer fragment file.
- Fragment files must be saved to the version-specific `buffer/` directory.
- Fragment filenames must use the 14-digit big-integer timestamp (`YYYYMMDDHHIISS`) followed by the actor ID.
- The atomic merge process (`merge_changelog_buffer.py`) is the sole mechanism permitted to append to the master `CHANGELOG.md` file.

Agents must use this architecture to prevent concurrent write collisions and ensure correct chronological merging.
