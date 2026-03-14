---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/version_tracking.md"
  last_modified_utc: "20260306"
  system_version: "4.0.61"
  channel_id: 0
  purpose: "Version tracking (version.php, version.md) for v4.0.61"
  traits: ["documentation", "feature", "v4.0.61", "versioning"]
  tags: ["version", "versioning", "implementation"]
---

# Version Tracking

## Overview

Version 4.0.61 centralizes version display and checks:

- **lupo-includes/version.php** — LUPOPEDIA_VERSION; `get_lupo_version()` and `is_version_at_least($min_version)`.
- **lupo-docs/version.md** — Version history, 4.0.61 summary, table 4.0.57–4.0.61, upgrade notes.
- **lupopedia-config.php** — `$lupo_config['version'] = '4.0.61'` when $lupo_config exists or is initialized.

## get_lupo_version()

Returns current version (from atom/constant or fallback 4.0.61). Used by CLI help and banners.

## is_version_at_least($min_version)

Uses `version_compare()` for minimum-version checks. PHP 5.3 safe.

## lupo-docs/version.md

- Current version and date
- Summary of 4.0.61 changes
- Recent version table (4.0.57–4.0.61)
- Upgrade notes and session-context note

## Files

- `lupo-includes/version.php`
- `lupo-docs/version.md`
- `lupopedia-config.php`
- CLI/HelpRenderer use `get_lupo_version()` for output
