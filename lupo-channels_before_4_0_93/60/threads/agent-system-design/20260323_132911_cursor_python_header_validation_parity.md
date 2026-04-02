---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-channels/60/threads/agent-system-design/20260323_132911_cursor_python_header_validation_parity.md"
  last_modified_utc: "20260323132911"
  channel_id: 60
  thread_id: 0
  actor_id: 102
  actor_name: "cursor"
  artifact_type: "implementation_report"
  artifact_kind: "python_validation_parity"
  purpose: "Report Python importer parity rollout for HeaderValidationService semantics."
---

# Python Header Validation Parity Report

## Files modified

- `lupo-scripts/lib/header_validation.py`
- `lupo-scripts/import_content.py`
- `lupo-scripts/import_channels_and_artifacts.py`
- `lupo-scripts/import_filesystem_channels_to_db.py`
- `lupo-scripts/import_filesystem_actors_agents_to_db.py`
- `lupo-scripts/import_os.py`
- `lupo-scripts/import_os_fixed.py`

## Python importers now protected

- `import_content.py`: validates parsed `lupopedia.headers` before deterministic ID/DB upsert flow.
- `import_channels_and_artifacts.py`: frontmatter parse + validation gate runs before broadcast import logic.
- `import_filesystem_channels_to_db.py`: artifact validation now includes strict shared header gate before import phases.
- `import_filesystem_actors_agents_to_db.py`: validates any frontmatter-bearing markdown encountered in actor/agent trees before DB writes.
- `import_os.py`: skips files with malformed/invalid headers before insert/edge creation.
- `import_os_fixed.py`: skips files with malformed/invalid headers before insert/edge creation.

## Remaining uncovered importer paths

- None from the requested list.

## Parity gaps / drift risks

- Python actor_id↔actor_name lookup parity is optional in PHP ("if lookup available"). Current Python module supports lookup input but importers do not yet pass a DB-backed actor map.
- `import_filesystem_actors_agents_to_db.py` primarily ingests JSON and text; header gate applies only to markdown files with explicit frontmatter under scanned trees.

## Semantic parity status

- Required-field checks aligned.
- Type and format checks aligned (numeric IDs/timestamps, non-empty strings, semver, relative path format).
- Hard-fail gate behavior aligned at importer entry points before downstream processing and DB writes.
