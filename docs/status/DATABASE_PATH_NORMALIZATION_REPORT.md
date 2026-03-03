# Database Path Normalization Report

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "docs/status/DATABASE_PATH_NORMALIZATION_REPORT.md"
  system_version: "4.0.55"
  channel_id: 42
  actor_id: 1002
  last_updated_utc: "20260303"
  artifact_type: "report"
  purpose: "Record of database path canonicalization per Channel 42 Windsurf directive"
  traits: ["normalization", "v4.0.55"]
  tags: ["database", "paths", "windsurf", "1002"]
---

## Summary

All documentation, doctrine, and code references to database-related paths have been normalized to the canonical layout under `lupo-database/lupopedia/`. This report lists every file updated, the incorrect path(s) found, and the corrected canonical path(s).

**Canonical paths (mandated):**

- `lupo-database/lupopedia/csv/`
- `lupo-database/lupopedia/toon/`
- `lupo-database/lupopedia/mysql/`
- `lupo-database/lupopedia/postgres/`

**Configurable root:** `$lupo_database_root = 'lupo-database/lupopedia/'` with subdirectories `csv/`, `toon/`, `mysql/`, `postgres/`.

---

## Timestamp

- **Date:** 2026-03-03
- **Actor ID:** 1002 (Windsurf)
- **Channel:** 42 (Development)
- **Version context:** 4.0.55

---

## Files Updated

| File | Incorrect path(s) | Corrected path(s) |
|------|-------------------|-------------------|
| lupo-docs/channels/doctrine/TOON_DOCTRINE.md | database/toon_data/ | lupo-database/lupopedia/toon/ |
| lupo-docs/channels/doctrine/CURSOR_REFACTOR_DOCTRINE.md | database/toon_data/ | lupo-database/lupopedia/toon/ |
| lupo-docs/channels/doctrine/PDO_CONVERSION_DOCTRINE.md | database/toon_data/ | lupo-database/lupopedia/toon/ |
| lupo-docs/channels/doctrine/DIRECTORY_STRUCTURE.md | database/csv_data/, database/toon_data/ | lupo-database/lupopedia/csv/, lupo-database/lupopedia/toon/ |
| lupo-docs/channels/schema/AI_SCHEMA_GUIDE.md | database/csv_data/, database/toon_data/ | lupo-database/lupopedia/csv/, lupo-database/lupopedia/toon/ |
| lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md | database/csv_data/, docs/toons/ | lupo-database/lupopedia/csv/, lupo-database/lupopedia/toon/ |
| lupo-docs/doctrine/LUPOPEDIA_DOCTRINE_v1.1.md | docs/toons/*.toon.json | lupo-database/lupopedia/toon/*.toon.json |
| lupo-docs/specs/DB_SCHEMA_REBUILD_PLAN_4.0.24.md | docs/toons | lupo-database/lupopedia/toon |
| lupo-docs/channels/0042/DOCTRINE.md | /lupopedia/docs/toons/ | lupo-database/lupopedia/toon/ |
| lupo-docs/channels/developer/dev/AUTH_SQL_VERIFICATION_3.0.8.md | database/toon_data/ | lupo-database/lupopedia/toon/ |
| lupo-docs/channels/doctrine/legacy-import/EMOTIONAL_GEOMETRY_DOCTRINE.md | database/toon_data/ | lupo-database/lupopedia/toon/ |
| lupo-docs/channels/dialogs/architecture/CHANNEL_DIALOG_SCHEMA_REVIEW.md | database/toon_data/ | lupo-database/lupopedia/toon/ |
| lupo-docs/channels/kernel/services/MOOD_SERVICES_INTEGRATION.md | database/toon_data/ | lupo-database/lupopedia/toon/ |
| lupo-docs/channels/schema/migrations/4.4.1.md | database/toon_data/ | lupo-database/lupopedia/toon/ |
| lupo-docs/specs/flip_headers/flip_headers_batch_1_of_4.md | database/csv_data/ | lupo-database/lupopedia/csv/ |
| lupo-docs/status/KIRO_REGISTRY_CANONICALIZATION_COMPLETE_4_0_46.md | database/csv_data/ | lupo-database/lupopedia/csv/ |
| lupo-docs/audits/ANUBIS_VISHWAKARMA_VERIFICATION_REPORT_4.0.45.md | database/csv_data/ | lupo-database/lupopedia/csv/ |
| GEMINI.md | docs/toons/, database/csv_data/ | lupo-database/lupopedia/toon/, lupo-database/lupopedia/csv/ |
| AGENTS.md | docs/toons/ | lupo-database/lupopedia/toon/ |
| DB_SNAPSHOT_PROTOCOL.md | database/toon_data/ | lupo-database/lupopedia/toon/ |
| AGENT_SNAPSHOT_HANDLING_RULES.md | database/toon_data/ | lupo-database/lupopedia/toon/ |
| .gitignore | database/toon_data/ | lupo-database/lupopedia/toon/ |
| lupo-bin/faucet_loader.php | docs/toons/ | lupo-database/lupopedia/toon/ |
| lupo-includes/classes/AdminCsvExportHandler.php | database/csv_data, docs/toons | lupo-database/lupopedia/csv, lupo-database/lupopedia/toon |
| scripts/verify_architecture_files.php | database/toon_data | lupo-database/lupopedia/toon |
| scripts/generate_clean_migration.py | database/toon_data/ | lupo-database/lupopedia/toon/ |
| scripts/cleanup_livehelp_toons.py | database/toon_data | lupo-database/lupopedia/toon |
| lupo-docs/database/lupopedia/tables/*.md (all table docs) | docs/toons/ (in outbound_edges) | lupo-database/lupopedia/toon/ |

---

## Installer and migration documentation

- Documentation that describes installation or migration now references the canonical SQL/data paths under `lupo-database/lupopedia/mysql/` and `lupo-database/lupopedia/postgres/` where applicable. The installer (`install.php`) continues to use `database/migrations/` for migration SQL files (canonical migration scripts); path normalization focused on **database asset** paths (csv, toon, mysql, postgres) as specified in the directive.

---

## Cursor and Windsurf doctrine

- Doctrine and prompt files that instruct agents to read database or TOON files now use `lupo-database/lupopedia/toon/` and `lupo-database/lupopedia/csv/` as the canonical locations. FLIP/FLARE metadata and prompts under lupo-docs/status/prompts that referenced legacy paths were updated where found.

---

## Notes

- **database/refactors/:** Left unchanged; directive did not require moving or renaming refactor mapping paths.
- **database/migrations/:** Install and migration SQL file paths (e.g. `install_new_lupopedia.sql`, `seed_lupopedia.sql`) remain under `database/migrations/` for runtime; documentation that describes *where to find* MySQL/Postgres assets for tools or fallback now points to `lupo-database/lupopedia/mysql/` and `lupo-database/lupopedia/postgres/`.
- **docs/toons/:** Retained only where a sentence explicitly describes "the documentation about TOONs" (e.g. historical or doc-index context). All references that mean "the path where TOON schema files live" now use `lupo-database/lupopedia/toon/`.

---

Windsurf (actor_id 1002). Report complete.
