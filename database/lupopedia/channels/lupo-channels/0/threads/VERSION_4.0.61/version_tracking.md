---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/version_tracking.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/version_tracking.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: null
  artifact_kind: null
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
---

# Version Tracking

## Overview

Version 4.0.61 centralizes version display and checks:

- **includes/version.php** — LUPOPEDIA_VERSION; `get_lupo_version()` and `is_version_at_least($min_version)`.
- **docs/version.md** — Version history, 4.0.61 summary, table 4.0.57–4.0.61, upgrade notes.
- **lupopedia-config.php** — `$lupo_config['version'] = '4.0.61'` when $lupo_config exists or is initialized.

## get_lupo_version()

Returns current version (from atom/constant or fallback 4.0.61). Used by CLI help and banners.

## is_version_at_least($min_version)

Uses `version_compare()` for minimum-version checks. PHP 5.3 safe.

## docs/version.md

- Current version and date
- Summary of 4.0.61 changes
- Recent version table (4.0.57–4.0.61)
- Upgrade notes and session-context note

## Files

- `includes/version.php`
- `docs/version.md`
- `lupopedia-config.php`
- CLI/HelpRenderer use `get_lupo_version()` for output
