---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-channels/0/threads/VERSION_4.0.61/version_tracking.md"
  last_modified_utc: "20260306"
  system_version: "4.0.61"
  channel_id: 0
  purpose: "Version tracking (version.php, version.md) for v4.0.61"
  traits: ["documentation", "feature", "v4.0.61", "versioning", "config_path"]
  tags: ["version", "versioning", "implementation"]
---

# Version Tracking

## Overview

Version 4.0.61 centralizes version display and checks:

- **lupo-includes/version.php** — Defines LUPOPEDIA_VERSION; adds `get_lupo_version()` and `is_version_at_least($min_version)`.
- **docs/version.md** — Version history, summary for 4.0.61, table 4.0.57–4.0.61, upgrade notes.
- **lupopedia-config.php** — `$lupo_config['version'] = '4.0.61'` (when $lupo_config exists or is initialized).

## get_lupo_version()

Returns current version (from atom/constant or fallback 4.0.61). Used by CLI help and banners so version is not hardcoded.

## is_version_at_least($min_version)

Uses `version_compare()` for minimum-version checks. PHP 5.3 safe.

## docs/version.md

- Current version and date
- Summary of 4.0.61 changes
- Recent version table (4.0.57–4.0.61)
- Upgrade notes and session-context note

## Files

- `lupo-includes/version.php`
- `docs/version.md`
- `lupopedia-config.php`
- CLI/HelpRenderer use `get_lupo_version()` for output
