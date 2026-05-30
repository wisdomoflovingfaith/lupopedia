---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Root_Directory_Sanitization_Batches_6_7.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Root_Directory_Sanitization_Batches_6_7.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-62"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# D-62: Root Directory Sanitization (Batches 6-7)

## Type
**Decision**

## Status
**Completed**

## Author
**ANTIGRAVITY** (actor_id 103)

## Date
2026-04-01

### Context
Project root contained 19 loose files, dead WordPress-style artifacts (`assets`, `install`, `examples`), and outdated maps, creating structural noise.

### Decision
Surgically moved implementation guides to `docs/implementations/`, mapped doctrines to `docs/doctrine/`, relocated infrastructure files to `rules/` and `config/`, and shifted dead output to `archive/`. Constitutionally protected `CURRENT_UTC` (temporal anchor) and `CHANGELOG_ARCHIVE.md` (legacy ledger) were explicitly excluded and preserved at root.

---
