# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/20260225170000_task_0_10000_drop_tables_and_run_install

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

lupopedia.conditional:
  guards:
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
    where:
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/20260225170000_task_0_10000_drop_tables_and_run_install.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:39:31Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "task"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/20260225170000_task_0_10000_drop_tables_and_run_install.md"
  file_hash: "0d224f7268bac4593f4b5296a5ff24cb9fe84f1014df56edaa9bfa9efaf0b1f1"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "threads"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/20260225170000_task_0_10000_drop_tables_and_run_install.md", "http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/20260225170000_task_0_10000_drop_tables_and_run_install"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-channels\0\tasks\active\20260225170000_task_0_10000_drop_tables_and_run_install.md"
  file_hash: "7e38c3e8ee97366281b8d81a19f6668cf73aa0fbb92473fc13850d12c0151e5f"
  file_path_from_root: "lupo-channels\0\tasks\active\20260225170000_task_0_10000_drop_tables_and_run_install.md"
  file_hash: "446704b5a3f6ab8e0f19c5a3071d5de0ff02d0774f609bab1d09378d82692a69"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "10000:1003"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225170000_task_0_10000_drop_tables_and_run_install.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "tasks", "active", "20260225170000_task_0_10000_drop_tables_and_run_installmd"]
  lupo_agent: "cursor"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "cursor"
---

---
task_id: CH0-20260225-001
channel_id: 42
owner_actor_id: 10000
assigned_to:
  - 10000
status: active
priority: critical
created_utc: "2026-02-25T17:00:00Z"
delegation_chain: "10000:10000"
prompt_path: "lupo-channels/0/tasks/active/20260225170000_task_0_10000_drop_tables_and_run_install.md"
depends_on: []
blocks:
  - CH0-20260225-002
  - CH0-20260225-003
  - CH0-20260225-004
  - CH0-20260225-005
task_type: database_operation
estimated_duration: "30 minutes"
artifacts_touched:
  - "lupo-database/migrations/install_new_lupopedia.sql"
  - "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql"
  - "lupo-database/migrations/seed_registry_comprehensive_4.0.45.sql"
  - "lupo-database/migrations/seed_registry_open_4.0.45.sql"
  - "lupo-database/migrations/seed_actors_agents_4.0.45.sql"
  - "lupo-database/migrations/seed_anubis_vishwakarma_4.0.45.sql"
notes: "This is a HUMAN task. Only Captain (10000) can execute database operations."
---

# TASK: Drop Tables and Run Install (HUMAN REQUIRED)

**⚠️ HUMAN TASK - Captain (10000) ONLY**

## Objective

Drop all existing Lupopedia tables, load Crafty Syntax 3.7.5 legacy schema, and run the Lupopedia install wizard to create a clean 4.0.45 installation.

## Context

The database is currently offline or in an inconsistent state. Before any other work can proceed, we need a clean installation that matches the 4.0.45 schema defined in `lupo-database/migrations/install_new_lupopedia.sql`.

This task BLOCKS all other tasks. No database-dependent work can proceed until this is complete.

## Prerequisites

- ✅ Validation gate passed (all broadcasts compliant)
- ✅ ANUBIS + VISHWAKARMA agents added to seeding SQL
- ✅ Offline task system implemented
- ✅ All seeding SQL files ready

## Steps

### 1. Backup Current Database (if needed)

```bash
# If you have any data you want to preserve
mysqldump -u root -p lupopedia > backup_before_4.0.45.sql
```

### 2. Drop All Lupopedia Tables

```sql
-- Connect to database
USE lupopedia;

-- Drop all lupo_* tables
-- (You may need to disable foreign key checks first)
SET FOREIGN_KEY_CHECKS = 0;

-- List all tables and drop them
SHOW TABLES LIKE 'lupo_%';
-- Then drop each one manually or use a script
```

### 3. Load Crafty Syntax 3.7.5 Legacy Schema

```bash
# Execute the legacy schema
mysql -u root -p lupopedia < lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql
```

**Verify:** Should have 34 legacy Crafty tables

### 4. Run Lupopedia Install Wizard

```bash
# Navigate to install.php in browser
http://localhost/lupopedia/install.php

# Follow the wizard:
# 1. Accept license
# 2. Check requirements
# 3. Configure database (should detect existing Crafty tables)
# 4. Run upgrade/install
# 5. Complete setup
```

### 5. Verify Installation

```bash
# Check table count
mysql -u root -p lupopedia -e "SHOW TABLES LIKE 'lupo_%';" | wc -l
# Should be 80+ tables

# Run TOON validation
python lupo-scripts/verify_db_against_toons.py
```

### 6. Seed Registry Data

```bash
# Execute seeding SQL files in order
mysql -u root -p lupopedia < lupo-database/migrations/seed_registry_comprehensive_4.0.45.sql
mysql -u root -p lupopedia < lupo-database/migrations/seed_registry_open_4.0.45.sql
mysql -u root -p lupopedia < lupo-database/migrations/seed_actors_agents_4.0.45.sql
mysql -u root -p lupopedia < lupo-database/migrations/seed_anubis_vishwakarma_4.0.45.sql
```

### 7. Verify Seeding

```sql
-- Check actors
SELECT actor_id, name, actor_type FROM lupo_actors WHERE actor_id IN (0,1,2,3,4,5,19,25,1000,1001,1002,1003,1004,1005,10000);

-- Check channels
SELECT channel_id, channel_name, channel_type FROM lupo_channels WHERE channel_id IN (0,1,42,51,666);

-- Check agents
SELECT agent_id, agent_name, archetype FROM lupo_agents WHERE agent_id IN (0,1,2,3,4,5,19,25);

-- Check registry
SELECT COUNT(*) FROM lupo_registry WHERE entity_type = 'actor';
SELECT COUNT(*) FROM lupo_registry WHERE entity_type = 'channel';
```

## Success Criteria

- ✅ Database contains all Lupopedia 4.0.45 tables
- ✅ Schema matches TOON files
- ✅ All reserved actors seeded (0-5, 19, 25, 1000-1005, 10000)
- ✅ All channels seeded (0, 1, 42, 51, 666)
- ✅ Registry tables populated with reserved and open IDs
- ✅ ANUBIS (19) and VISHWAKARMA (25) present
- ✅ No schema validation errors

## Risks

- **Data loss:** This is a destructive operation. Backup any important data first.
- **Config loss:** Old Crafty config may not be available.
- **Schema drift:** Manual changes to schema will be lost.
- **Seeding errors:** SQL errors may prevent proper seeding.

## After Completion

Once this task is complete, update status to `completed` and move file to:
```
lupo-channels/0/tasks/completed/20260225170000_task_0_10000_drop_tables_and_run_install.md
```

Then notify all IDE agents that database is online and they can proceed with their assigned tasks.

## Notes

- This is a BLOCKING task - highest priority
- Only human operator (Captain 10000) can execute
- Estimated time: 30 minutes
- Must be done before any other 4.0.45 work

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "lupo-database/migrations/install_new_lupopedia.sql",
    "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql",
    "lupo-database/migrations/seed_registry_comprehensive_4.0.45.sql",
    "lupo-database/migrations/seed_registry_open_4.0.45.sql",
    "lupo-database/migrations/seed_actors_agents_4.0.45.sql",
    "lupo-database/migrations/seed_anubis_vishwakarma_4.0.45.sql"
  ],
  "implements": "fresh_install_workflow",
  "depends_on": [],
  "blocks": [
    "CH0-20260225-002",
    "CH0-20260225-003",
    "CH0-20260225-004",
    "CH0-20260225-005"
  ],
  "task_category": "infrastructure",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->
