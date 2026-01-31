---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 2026.3.8.0
file.channel: versioning
file.last_modified_utc: 20260130005340
file.name: "CHANGELOG.md"
---

# Note on Versioning and Multiple Changelogs
Lupopedia uses a multi-channel versioning system.

The canonical semantic version history is maintained under:
docs/channels/overview/versioning/CHANGELOG.md

This root-level CHANGELOG.md is a high-level meta-log and does not
represent the full version history of the Semantic OS. Contributors
should refer to the channel-based changelog for authoritative version
increments, doctrine updates, and schema-related notes.

## 2026-02-01 — Architecture Rebuild + Crafty Syntax Integration

### Summary
January architecture rebuild completed with major structural changes and Crafty Syntax integration preparation.

### Changes Made
- January architecture rebuild completed
- Removed legacy agent directories (0001–0022)
- Removed legacy channel directories (0000–5101, 9000)
- Added new doctrine files
- Added new TOON files generated from database
- Added migration SQLs (actor model fix, doctrine boot block, filesystem migration)
- Implemented new login system (MD5 upgrade, redirect-back, session upgrade)
- Implemented Collection 0 documentation landing
- Implemented Q/A module and routing consolidation
- Updated controllers, helpers, UI components
- Added filesystem migration scripts
- Repo cleaned and pushed (commit 47731d9)

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

## [2026.3.8.0] – Crafty Syntax Subsystem Activation + AI→Human Escalation Engine

### Added
- Activated the new Crafty Syntax operator console under `lupopedia/crafty_syntax/`
- Implemented full routing, controllers, views, includes, and admin CSS
- Added Operator Expertise System (`includes/expertise.php`) using TOON-aligned scoring
- Added AI→Human escalation engine (`includes/escalation.php`) with topic/department/channel resolution
- Escalation metadata stored safely in `lupo_dialog_threads.metadata_json` (no schema drift)
- Logged AI→human handoffs in `lupo_agent_tool_calls`
- Updated operator presence and load via `lupo_operator_status`
- Integrated Crafty Syntax module router to forward `/crafty_syntax/...` slugs into the new subsystem
- Added Operator Expertise Snapshot panel to the Operator Overview page

### Changed
- Operator Overview now renders real data from `lupo_actors`, `lupo_operator_status`, and `lupo_dialog_threads`
- Legacy placeholder routing replaced with the new procedural console
- Added `/crafty_syntax/escalate` endpoint for AI→human escalation

### Notes
- No database schema changes beyond the new migration; all new behavior stored in metadata_json per doctrine
- Escalation engine uses `lupo_crafty_select_operator()` for expertise-based routing
- This version marks the first fully functional semantic OS integration of Crafty Syntax

## [2026.3.7.6] - 2026-01-28
### Added
- Added Captain's Log entries documenting fragmented feature recall and emergency bridge session.
- Added doctrine `doctrine/ETHICAL_STATE_MARKERS_DOCTRINE.md` (pono/pilau/kapakai).
- Added `scripts/generate_blessed_channel_registry.py` for blessed registry generation with ethical triad defaults.
- Generated `channels/registry.json` and normalized channel directories to numeric zero-padded folders.
- Added `plan_for_crafty_syntax.md` migration sprint plan (Cycles + Consecration).

### Changed
- Updated Emotional Geometry Doctrine to v4.2 with Light-Emotion Isomorphism section.
- Updated `README.md` with Wolfie Header update requirements and channel provenance rules.
- Updated system version to 2026.3.7.6.
- Raised table ceiling doctrine/config references to 222 and added optimization trigger at 223+.

## [2026.1.1.14] - 2026-01-27
### Added
- Integrated Emotional Geometry Doctrine v4.1 (Light-Emotion Isomorphism section added)
- Added mood_rgb and mood_framework fields to lupo_dialog_messages
- Phase 1 emotional framework migration initiated

### Changed
- Updated emotional metadata architecture to support pluralistic frameworks

### Notes
- This patch is part of the ongoing Crafty Syntax -> Lupopedia migration work
