---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260324200640"
  file_path_from_root: "docs/versions/4.0.87/CHANNEL_ORGANIZATION_STREAM.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.87/CHANNEL_ORGANIZATION_STREAM.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: planning
  artifact_kind: channel_stream
  thread_id: "folder-organization-charter"
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
# 4.0.87 Channel Stream: Lupo Folder Organization

## Channel
- Channel ID: 62
- Focus: `*` directory structure hygiene and documentation accuracy.

## Scope
- Inventory all root-level `*` folders and intended purpose.
- Identify deprecated/duplicate artifacts and mark for removal or archival.
- Reconcile `docs/` content with current runtime/code/doctrine.
- Ensure docs avoid stale upgrade claims and match 4.0.87 rules.

## Deliverables
- Folder inventory matrix with owner/doctrine status.
- Deprecation/removal plan with file-level actions.
- Updated docs with corrected references and canonical paths.
- Changelog/task registry updates with completion evidence.

## Constraints
- No Lupopedia -> Lupopedia upgrade compatibility scope.
- Maintain 4.0.x doctrine: fresh install or Crafty Syntax import path only.

## Thread Update (2026-03-24: Organization + Validation)
- Added organization report artifacts in channel 62, including canonical numeric thread path under `threads/6201/`.
- Normalized table-doc headers/footers for stale validation cutoff compliance.
- Established script metadata validation path for `scripts` tooling (`.py` and `.php` comment metadata).

## Thread Update (2026-03-24: Root stale move execution)
- Moved temporary/debug root files and per-agent root variants into:
  - `docs/archived/root_stale_20260324/tmp_debug/`
  - `docs/archived/root_stale_20260324/agent_variants/`
  - `docs/archived/root_stale_20260324/session_notes/`
- Opened channel 66 production question threads to settle remaining policy decisions:
  - `1050`: archive scope and retention boundaries
  - `1051`: edge review actor ownership and SLA

