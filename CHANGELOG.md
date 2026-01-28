# Note on Versioning and Multiple Changelogs
Lupopedia uses a multi-channel versioning system.

The canonical semantic version history is maintained under:
docs/channels/overview/versioning/CHANGELOG.md

This root-level CHANGELOG.md is a high-level meta-log and does not
represent the full version history of the Semantic OS. Contributors
should refer to the channel-based changelog for authoritative version
increments, doctrine updates, and schema-related notes.

## [2026.1.1.12] - 2026-01-24 20:32:00
- Version boundary synced across canonical changelog and global atoms.

## 2026-01-25 - Prefix Normalization Cycle Completed

### Summary
Completed the full table-prefix normalization cycle across the schema. All tables now consistently use the dynamic prefix defined in `lupopedia-config.php` (`lupo_`). Legacy unified_* tables were preserved and renamed with `_old` suffixes to maintain historical integrity and avoid namespace collisions with newer Lupopedia-native tables.

### Details
- Normalized all remaining unprefixed tables to use the `lupo_` prefix.
- Renamed legacy unified subsystem tables to `lupo_unified_*_old` to clearly mark them as deprecated but preserved.
- Ensured no active tables remain without the required prefix.
- Regenerated all `.toon.json` files using `python scripts/generate_toon_files.py`.
- Removed stale TOON files corresponding to tables that no longer exist.
- Verified schema consistency through TOON metadata rather than direct DB introspection.
- Added migration artifact `2026_01_25_01_prefix_normalization_noop.sql` documenting the completion of the normalization cycle. Migration is a no-op by design and uses the `@table_prefix` variable injected by the migration runner.

### Notes
This completes the prefix normalization era. Future schema changes must:
- Always use the dynamic prefix from `lupopedia-config.php`.
- Never introduce unprefixed tables.
- Treat `lupo_unified_*_old` tables as legacy and safe for future cleanup.
- Regenerate TOON files after every schema modification.

The schema is now fully aligned with Lupopedia's naming doctrine and ready for the next phase of development.

## [2026.1.1.4] - 2026-01-27
### Added
- Integrated Emotional Geometry Doctrine v4.1 (Light-Emotion Isomorphism section added)
- Added mood_rgb and mood_framework fields to lupo_dialog_messages
- Phase 1 emotional framework migration initiated

### Changed
- Updated emotional metadata architecture to support pluralistic frameworks

### Notes
- This patch is part of the ongoing Crafty Syntax -> Lupopedia migration work
