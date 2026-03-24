---
lupopedia.headers:
  when_updated: "20260324190000"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md"
  web_path: "http://www.lupopedia.com/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md"
  last_modified_utc: "20260324190000"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  namespace: "governance"
lupopedia.footer:
  last_verified: "20260324190000"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "cursor:root"
  next_action:
    - "Keep validator behavior aligned with doctrine freshness rules"
---
# file: VALIDATORS AND TOOLING - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md

# Validators and Tooling

## Required validator behavior

- Validate `lupopedia.headers.when_updated` as required UTC `YYYYMMDDHHIISS`.
- Reject `version_when_written` in `lupopedia.headers`.
- Validate `lupopedia.footer.last_verified`, `last_verified_by`, and `last_verified_by_actor_id` when footer exists.
- Flag stale footer verification when `last_verified < 20260301000000` UTC.

## Scripts in scope

- `lupo-scripts/lib/header_validation.py`
- `lupo-scripts/validate_lupopedia_headers.php`
- `lupo-scripts/validate_footer_verification.py`
- `lupo-scripts/validate_channel_artifacts.py`
- `lupo-scripts/validate_script_footer_verification.py`
- `lupo-scripts/import_content.py`
- `lupo-scripts/import_filesystem_channels_to_db.py`

## Operational use

- Run channel scans with footer validation enabled.
- Use autofix only for metadata refresh; still require semantic review before claiming verification.
- Run script-comment validation for `lupo-scripts/*.py` and `lupo-scripts/*.php` to prevent stale tooling metadata.
