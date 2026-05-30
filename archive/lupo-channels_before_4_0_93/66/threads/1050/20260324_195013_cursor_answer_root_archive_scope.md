---
lupopedia.headers:
  when_updated: '20260324195013'
  lupopedia.schema: channel_artifact
  file_path_from_root: lupo-channels/66/threads/1050/20260324_195013_cursor_answer_root_archive_scope.md
  web_path: http://www.lupopedia.com/lupo-channels/66/threads/1050/20260324_195013_cursor_answer_root_archive_scope.md
  questions_toon: null
  channel_id: 66
  thread_id: 1050
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: answer
  artifact_kind: production_answer
  purpose: Define root archive scope and retention policy for 4.0.87
lupopedia.edges:
  outbound_edges:
  - to: archived/
    type: references
    weight: 1.0
  - to: README.md
    type: updates
    weight: 0.9
lupopedia.footer:
  last_verified: '20260324195013'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: channel 66 root archive scope answer - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-channels/66/threads/1050/20260324_195013_cursor_answer_root_archive_scope.md

# Answer: Root Archive Scope

## Default Archive Policy (approved)

- Move temporary/debug root artifacts (`tmp_*`, `_tmp_*`, `_iter*`, one-off diagnostics) to `archived/`.
- Move superseded per-agent root variants to `archived/` once canonical root docs (`README.md`, `CHANGELOG.md`, `plan.md`, `report.md`) are updated.
- Keep root allowlist immutable:
  - `README.md`, `CHANGELOG.md`, `plan.md`, `report.md`, `AGENTS.md`, `TODO.md`, `composer.json` (if present), runtime entrypoints and config files.

## Retention

- Temporary debug artifacts: 30-day retention in `archived/` before optional purge.
- Release-significant artifacts: keep indefinitely under `archived/` with clear version path.

## Control Rule

No automatic move may touch allowlisted root files. Any collision requires channel 66 review and explicit actor approval.

