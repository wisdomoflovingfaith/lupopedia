---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  file_path_from_root: "lupo-database/lupopedia/actors/actor_id/1000/QUICK_REFERENCE.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "documentation"
  purpose: "Quick reference for KIRO IDE (Actor 1000)"
  tags: ["actor-1000", "kiro-ide", "quick-reference"]
---

# Actor 1000 — Quick Reference (KIRO IDE)

**Actor ID:** 1000 | **Slug:** kiro-ide | **Kind:** agent (IDE)

## Usage

- **Role:** Lead coordinator for installation, verification, and IDE-side operations. Handles DB/schema coordination, TOON and install verification, multi-agent workflow integration.
- **Capabilities (from capabilities.json):** `db_query`, `file_write`, `system_exec`; channel roles: 0 (admin), 42 (contributor). Max tasks limit applies.
- **Integration:** Install wizard, TOON generation/validation scripts, registry seeding. Use with `lupo-database/lupopedia/mysql/` for installer SQL; `lupo-scripts/verify_db_against_toons.py` for schema checks.

## Key references

| Topic | Location |
|-------|----------|
| Identity / profile | `README.md`, `profile.json`, `identity.json` in this directory |
| Capabilities | `capabilities.json` |
| Install / TOON | `lupo-database/lupopedia/mysql/`, `install.php`, AGENTS.md (Schema Source of Truth) |
| Verification | `lupo-scripts/verify_db_against_toons.py`, `lupo-scripts/generate_toon_files.py` |

## Commands / API (context)

- **Schema verification:** `python lupo-scripts/verify_db_against_toons.py`
- **TOON generation:** `python lupo-scripts/generate_toon_files.py` (from DB) or from install SQL per TOON doctrine.
- **Fresh install:** Run install wizard (`install.php`); SQL loaded from `LUPO_MYSQL_DIR` (see install.php and MYSQL_INSTALL_SQL_RELOCATION_REPORT.md).

## Troubleshooting

- **Install fails (missing SQL):** Ensure `lupo-database/lupopedia/mysql/` exists and contains lupo-install/seed/import/migrations; check LUPO_DATABASE_DIR / LUPO_MYSQL_DIR.
- **TOON mismatch:** Regenerate TOONs from current DB or from install SQL; ensure code and TOONs match per TOON doctrine.
