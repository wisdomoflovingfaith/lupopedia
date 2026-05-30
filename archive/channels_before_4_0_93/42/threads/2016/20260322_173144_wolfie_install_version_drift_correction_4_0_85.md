---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "channels/42/threads/2016/20260322_173144_wolfie_install_version_drift_correction_4_0_85.md"
  questions_toon: null
  channel_id: 42
  thread_id: 2016
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "implementation_report"
  artifact_kind: "install_version_correction"
  purpose: "Fix installer version drift and enforce canonical version source in install/runtime surfaces."
---

# Install Version Drift Correction 4.0.85

## Summary
- Installer version display drift was caused by stale fallback code paths overriding atom-derived version.
- Installer version source is now canonicalized to `GLOBAL_CURRENT_LUPOPEDIA_VERSION` (from atoms).
- Verified installer render now reports `Lupopedia 4.0.85`.

## Files Modified
- install.php
- includes/functions/load_atoms.php
- includes/version.php
- bin/lupo.php
- config.php
- config/config.php
- config/GLOBAL_IMPORTANT_ATOMS.yaml
- config/config/GLOBAL_IMPORTANT_ATOMS.yaml
- docs/versions/4.0.85/CHANGELOG.md

## Previous Version Source (Drift Path)
- install.php had a hardcoded wizard fallback and allowed `LUPOPEDIA_VERSION` from `version.php` to override atom-derived installer version.
- includes/functions/load_atoms.php fallback returned hardcoded `4.0.74` when loader context was absent.
- includes/version.php fallbacks included stale hardcoded defaults (`4.0.84`).
- bin/lupo.php had hardcoded fallback `4.0.74` for CLI version rendering.

## New Canonical Version Source
- Canonical source: `GLOBAL_CURRENT_LUPOPEDIA_VERSION` in:
  - config/global_atoms.yaml
  - config/config/global_atoms.yaml
- Installer flow now:
  1. Parse canonical atom directly in install.php.
  2. Use version.php only as fallback when atoms cannot be read.
  3. Never override atom-derived installer version with a stale constant.

## Validation
- install.php rendered output probe result: `4.0.85`.
- Active PHP version-indicator scans show no remaining hardcoded `4.0.74` in runtime version sources.
- Remaining `4.0.74` references are limited to seed-history comments and `_iter1_install.php` historical diagnostic script context.

## Hardcoded Version Cleanup Confirmation
- Removed active hardcoded drift values from installer/runtime version sources.
- No active installer fallback to `4.0.74` remains.
- No active installer fallback to `4.0.75` or `4.0.84` remains.
