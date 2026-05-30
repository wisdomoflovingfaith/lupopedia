---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/actors/actor_id/1000/QUICK_REFERENCE.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/actors/actor_id/1000/QUICK_REFERENCE.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: null
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: null
  prd_cluster: null
  title: null
  summary: null
---

# Actor 1000 — Quick Reference (KIRO IDE)

**Actor ID:** 1000 | **Slug:** kiro-ide | **Kind:** agent (IDE)

## Usage

- **Role:** Lead coordinator for installation, verification, and IDE-side operations. Handles DB/schema coordination, TOON and install verification, multi-agent workflow integration.
- **Capabilities (from capabilities.json):** `db_query`, `file_write`, `system_exec`; channel roles: 0 (admin), 42 (contributor). Max tasks limit applies.
- **Integration:** Install wizard, TOON generation/validation scripts, registry seeding. Use with `database/lupopedia/mysql/` for installer SQL; `scripts/verify_db_against_toons.py` for schema checks.

## Key references

| Topic | Location |
|-------|----------|
| Identity / profile | `README.md`, `profile.json`, `identity.json` in this directory |
| Capabilities | `capabilities.json` |
| Install / TOON | `database/lupopedia/mysql/`, `install.php`, AGENTS.md (Schema Source of Truth) |
| Verification | `scripts/verify_db_against_toons.py`, `scripts/generate_toon_files.py` |

## Commands / API (context)

- **Schema verification:** `python scripts/verify_db_against_toons.py`
- **TOON generation:** `python scripts/generate_toon_files.py` (from DB) or from install SQL per TOON doctrine.
- **Fresh install:** Run install wizard (`install.php`); SQL loaded from `LUPO_MYSQL_DIR` (see install.php and MYSQL_INSTALL_SQL_RELOCATION_REPORT.md).

## Troubleshooting

- **Install fails (missing SQL):** Ensure `database/lupopedia/mysql/` exists and contains install/seed/import/migrations; check LUPO_DATABASE_DIR / LUPO_MYSQL_DIR.
- **TOON mismatch:** Regenerate TOONs from current DB or from install SQL; ensure code and TOONs match per TOON doctrine.
