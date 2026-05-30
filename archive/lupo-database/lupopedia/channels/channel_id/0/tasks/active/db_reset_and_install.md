# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/0/tasks/active/db_reset_and_install.md"
  file_hash: "5dd0b56a449d240827394e0fbd2e99c595db592ef310ee10e966ff2ced93db11"
  last_updated_utc: "20260228155738"
  system_version: "4.0.73"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.73"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-channels\0\tasks\active\db_reset_and_install.md"
  file_hash: "a39c5cb37f5641ac53135c698a1d25d93b0234d4efc75ce1b08acc0ebe0a2463"
  file_path_from_root: "lupo-channels\0\tasks\active\db_reset_and_install.md"
  file_hash: "0a3c7d3090546f741245380b6ea57079076d73ca29cc88a0a2707c09262d32d2"
  last_updated_utc: "20260228"
  system_version: "4.0.73"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for db_reset_and_install.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.73"]
  tags: ["channels", "0", "tasks", "active", "db_reset_and_installmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.73"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

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

The database is currently offline or in an inconsistent state. Before any other work can proceed, we need a clean installation that matches the 4.0.45 schema defined in `lupo-database/migrations/install_new_lupopedia.sql`.

## Steps

1. **Drop all tables** (if any exist)
   - Drop all `lupo_*` tables
   - Verify clean slate

2. **Load Crafty Syntax 3.7.5 legacy schema**
   - Execute: `lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql`
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
   - Run: `python lupo-scripts/verify_db_against_toons.py`

6. **Seed registry data**
   - Execute: `lupo-database/migrations/seed_registry_comprehensive_4.0.45.sql`
   - Execute: `lupo-database/migrations/seed_registry_open_4.0.45.sql`
   - Execute: `lupo-database/migrations/seed_actors_agents_4.0.45.sql`

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
    "lupo-database/migrations/install_new_lupopedia.sql",
    "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql",
    "lupo-database/migrations/seed_registry_comprehensive_4.0.45.sql",
    "lupo-database/migrations/seed_registry_open_4.0.45.sql",
    "lupo-database/migrations/seed_actors_agents_4.0.45.sql"
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
