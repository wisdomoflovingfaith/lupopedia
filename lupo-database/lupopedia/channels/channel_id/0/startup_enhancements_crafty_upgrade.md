# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "instruction"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/0/startup_enhancements_crafty_upgrade.md"
  system_version: "4.0.53"
  last_modified_utc: "20260301155700"
  channel_id: 0
  actor_id: 1006
  delegation_chain: "0:10000"
  artifact_type: "update"
  artifact_kind: "agent_instruction"
  purpose: "Instruct Windsurf to implement system agent boot script enhancements for Crafty Syntax upgrade, run via web-side PHP during install, seed active lupo-actors/agents on Channel 0 with Task 1"
  mood_rgb: "32CD32"  # LimeGreen for implementation go-ahead
  traits: ["boot_enhancements", "crafty_upgrade", "install_task", "v4.0.53"]
  tags: ["windsurf", "boot_script", "crafty_syntax", "ai_agents", "database_seed"]
  lupo_agent: "gemini-cli"

lupopedia.edges:
  outbound_edges:
    - { to: "bin/boot_system_agent.php", type: "implementation_reference", weight: 1.0 }
    - { to: "lupo-database/migrations/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "http://www.lupopedia.com/changelog", type: "update_target", weight: 0.9 }
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/toons/lupo_channel_boot_lifecycle.toon.json", type: "related_schema", weight: 0.8 }
  semantic_tags: ["crafty_upgrade", "install_process", "ai_boot"]

lupopedia.footer:
  version: "4.0.53"
  last_verified: "20260301"
  last_verified_by: "gemini-cli"
---

# Windsurf: System Agent Boot Script Enhancements for Crafty Upgrade

**Windsurf—enhance and integrate!** ✅  

Per Wolfie's directive (@lupopedia), implement enhancements to the system agent boot script for the Crafty Syntax upgrade. These must run in PHP from the **web side** during the install process (e.g., via a web-accessible install script like `install.php` or `upgrade.php`). Focus on seeding/activating actors and agents in the database, ensuring they're active on Channel 0 with **Task 1** (the only seeded task: validate install_lupopedia.sql and import from old Crafty tables). Any DB changes must happen before v4.1.0 (current: v4.0.53).

### Key Requirements
- **Web-Side Execution**: Trigger via web (e.g., http://lupopedia.local/install.php). Use sessions/user auth if needed; output progress/logs to browser/console.
- **Actors/Agents Seeding**: Not just create in seed—make them **active** on Channel 0.
  - Actors: SYSTEM (actor_id=0), CAPTAIN WOLFIE (actor_id=1), LILITH (actor_id=2).
  - Insert/update into relevant tables (e.g., `lupo_actors`, `lupo_channel_state`, `lupo_sessions` if tied).
  - Set status='active', channel_id=0, link to Task 1.
- **Task 1 Seeding**: Only task in this install.
  - Table: `lupo_tasks` (per `lupo-docs/toons/lupo_tasks.toon.json`).
  - Insert: task_id=1, description="Validate install_lupopedia.sql setup and import/migrate data from old Crafty Syntax 5.7.5 tables. Ensure no schema drift; log issues.", status='active', assigned_actors='[0,1,2]', channel_id=0.
- **Upgrade Logic**:
  - **Table Setup**: Run/validate `install_new_lupopedia.sql` (CREATEs, INDEXes; no FKs/triggers).
  - **Data Import**: Map/import from Crafty tables (e.g., SELECT INSERT INTO lupo_* FROM crafty_*; handle timestamps: CAST/STR_TO_DATE to BIGINT YYYYMMDDHHIISS).
  - **AI Boot on Install**: Start AIs (LILITH, SYSTEM, CAPTAIN WOLFIE) to oversee—e.g., `$lilith->validateTables(); $system->migrateData(); $wolfie->logMigration();`.
  - **DB Changes**: If needed (e.g., new fields/indexes), apply now (before 4.1.0). Propose in changelog if major.
- **Integration with Boot**:
  - Enhance `bin/boot_system_agent.php`: Call web-side logic if install mode (e.g., --install flag).
  - But primary: Web PHP script drives lupo-install/upgrade.
- **Error Handling**: Log to `lupo_channel_logs` (channel_id=0); escalate fails to `lupo_channel_escalations`.
- **Backward Compat**: Handle existing installs (skip seed if data exists).

### Action Items
1. **Create/Update Web Install Script**: `install.php` or `upgrade_crafty.php`—web-runnable, with progress UI (e.g., echo steps).
2. **Seed Actors/Agents/Task**: PHP inserts/updates; set active on Channel 0.
3. **Implement Upgrade/Import**: Validate tables, migrate data, AI oversight.
4. **Test**: Simulate Crafty dump → Lupopedia; check AIs active, Task 1 assigned.
5. **Commit & Changelog**: 
   - Git: `git commit -m "FLARE: Boot enhancements for Crafty upgrade - Web install, AI seeding/active on Channel 0 with Task 1"`.
   - Append changelog: "v4.0.53: Added web-side install for Crafty upgrade; seeded active AIs (0,1,2) on Channel 0 with Task 1 (install validation/import)".
6. **Broadcast Confirm**: To Channel 0 on completion.

Target: v4.0.53 stable. If schema changes needed, flag before push.

📢 **CHANNEL 0 BROADCAST**  
WINDSURF: Boot enhancements for Crafty upgrade received—implementing web PHP install, AI seeding/active with Task 1.  
UTC: 20260301 (09:56 AM CST, Sioux Falls)  
