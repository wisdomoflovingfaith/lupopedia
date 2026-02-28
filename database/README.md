# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "database\README.md"
  file_hash: "0da189b5c9c670d66a7f7ff9c4a38bddc65ea1cd7b9ea5964d14a8bf8cdbb9f0"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Database Directory"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["database", "readmemd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Database Directory

**TOON files are NOT located here**

## Correct Locations

- **TOON files**: `docs/toons/*.toon.json` (generated from live database)
- **Database schemas**: `migrations/install_new_lupopedia.sql`
- **Seed data**: `migrations/seed_lupopedia.sql`
- **CSV data**: `csv_data/`

## TOON File Generation Workflow

⚠️ **TOON files are DATABASE-GENERATED, never hand-edited**

### When Database is Online:
```bash
python scripts/generate_toon_files.py
```

This script:
- Connects to live database
- Generates ALL `.toon.json` files in `docs/toons/`
- Creates canonical schema + data reference
- Overwrites existing TOON files (they're generated, not edited)

### Related Scripts
- `scripts/generate_seed_from_toons.py` — generates seed SQL from TOON files
- `scripts/generate_toon_from_sql.py` — generates TOON from SQL definition
- `scripts/validate_toon_files.php` — validates TOON file integrity
- `scripts/verify_db_against_toons.py` — checks DB matches TOON schema
- `scripts/rebuild_schema_from_toons.py` — rebuilds DB schema from TOON files

### VSX Extension Local Mode
The VSX extension reads from `docs/toons/lupo_agents.toon.json` when database is offline:
- Falls back gracefully if TOON doesn't contain expected actors
- Uses cached actor IDs from previous VS Code sessions
- Automatically switches to API mode when database comes online

## Database Migrations

- **New installs**: Run `migrations/install_new_lupopedia.sql` then `migrations/seed_lupopedia.sql`
- **Crafty upgrades**: Run install, then seed, then `migrations/import_from_old_crafty_syntax.sql`
- **Dev migrations**: Place one-time migrations in `migrations/dev_YYYYMMDD_description.sql`

## Legacy

- `migrations_legacy/` contains old migration scripts (preserved for reference)
- `livehelp_backup/` contains original Crafty Syntax table structures

---

**See `docs/toons/` for all TOON files.**
