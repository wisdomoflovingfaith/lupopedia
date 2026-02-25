---
task_id: CH0-20260225-001
channel_id: 0
owner_actor_id: 10000
assigned_to:
  - 10000
status: active
priority: critical
created_utc: "2026-02-25T08:30:00Z"
depends_on: []
blocks:
  - CH0-20260225-002
  - CH0-20260225-003
  - CH0-20260225-004
task_type: database_operation
estimated_duration: "30 minutes"
---

# Task: Database Reset and Fresh Install

## Objective

Drop all existing Lupopedia tables, load Crafty Syntax 3.7.5 legacy schema, and run the Lupopedia install wizard to create a clean 4.0.45 installation.

## Context

The database is currently offline or in an inconsistent state. Before any other work can proceed, we need a clean installation that matches the 4.0.45 schema defined in `database/migrations/install_new_lupopedia.sql`.

## Steps

1. **Drop all tables** (if any exist)
   - Drop all `lupo_*` tables
   - Verify clean slate

2. **Load Crafty Syntax 3.7.5 legacy schema**
   - Execute: `database/migrations/old_crafty_syntax_3_7_5_start.sql`
   - Verify 34 legacy tables created

3. **Load legacy Crafty config** (if available)
   - Import old configuration data
   - Preserve historical settings

4. **Run Lupopedia install wizard**
   - Navigate to: `http://localhost/lupopedia/install.php`
   - Complete installation wizard
   - Verify all tables created

5. **Verify installation**
   - Check table count (should be 80+ tables)
   - Verify schema matches `install_new_lupopedia.sql`
   - Run: `python scripts/verify_db_against_toons.py`

6. **Seed registry data**
   - Execute: `database/migrations/seed_registry_comprehensive_4.0.45.sql`
   - Execute: `database/migrations/seed_registry_open_4.0.45.sql`
   - Execute: `database/migrations/seed_actors_agents_4.0.45.sql`

7. **Verify seeding**
   - Check actor count (should have System, WOLFIE, LILITH, ROSE, ERIS, METIS, IDE agents, Captain)
   - Check channel count (should have 0, 1, 42, 51, 666)
   - Verify registry tables populated

## Success Criteria

- ✅ Database contains all Lupopedia 4.0.45 tables
- ✅ Schema matches TOON files
- ✅ All reserved actors seeded (0-5, 1000-1005, 10000)
- ✅ All channels seeded (0, 1, 42, 51, 666)
- ✅ Registry tables populated with reserved and open IDs
- ✅ No schema validation errors

## Risks

- **Data loss:** This is a destructive operation. Backup any important data first.
- **Config loss:** Old Crafty config may not be available.
- **Schema drift:** Manual changes to schema will be lost.

## Notes

This is a blocking task. No other database-dependent work can proceed until this is complete.

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "database/migrations/install_new_lupopedia.sql",
    "database/migrations/old_crafty_syntax_3_7_5_start.sql",
    "database/migrations/seed_registry_comprehensive_4.0.45.sql",
    "database/migrations/seed_registry_open_4.0.45.sql",
    "database/migrations/seed_actors_agents_4.0.45.sql"
  ],
  "implements": "fresh_install_workflow",
  "depends_on": [],
  "blocks": [
    "CH0-20260225-002",
    "CH0-20260225-003",
    "CH0-20260225-004"
  ],
  "task_category": "infrastructure",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->
