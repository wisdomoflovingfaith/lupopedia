# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "instruction"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/0/crafty_upgrade_instructions.md"
  system_version: "4.0.53"
  last_modified_utc: "20260301"
  channel_id: 0
  actor_id: 0
  delegation_chain: "0:10000"
  artifact_type: "update"
  artifact_kind: "agent_instruction"
  purpose: "Instruct Windsurf to implement system agent boot script enhancements for Crafty Syntax upgrade, run via web-side PHP during install, seed active lupo-actors/agents on Channel 0 with Task 1"
  mood_vector: "32CD32"  # LimeGreen for implementation go-ahead
  traits: ["boot_enhancements", "crafty_upgrade", "install_task", "v4.0.53"]
  tags: ["windsurf", "boot_script", "crafty_syntax", "ai_agents", "database_seed"]
  lupo_agent: "codex-ide"

lupopedia.edges:
  outbound_edges:
    - { to: "bin/boot_system_agent.php", type: "implementation_reference", weight: 1.0 }
    - { to: "install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "http://www.lupopedia.com/changelog", type: "update_target", weight: 0.9 }
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/toons/lupo_channel_boot_lifecycle.toon.json", type: "related_schema", weight: 0.8 }
  semantic_tags: ["crafty_upgrade", "install_process", "ai_boot"]

lupopedia.footer:
  version: "4.0.53"
  last_verified: "20260301"
  last_verified_by: "codex-ide"
---

# Windsurf: System Agent Boot Script Enhancements for Crafty Upgrade

**Windsurf—enhance and integrate!** ✅  

Per Wolfie's directive (@lupopedia), implement enhancements to the system agent boot script for Crafty Syntax upgrade. These must run in PHP from the **web side** during the install process (e.g., via a web-accessible install script like `install.php` or `upgrade.php`). Focus on seeding/activating actors and agents in the database, ensuring they're active on Channel 0 with **Task 1** (the only seeded task: validate install_lupopedia.sql and import from old Crafty tables). Any DB changes must happen before v4.1.0 (current: v4.0.53).

### Key Requirements

- **Web-Side Execution**: Trigger via web (e.g., http://lupopedia.local/install.php). Use sessions/user auth if needed; output progress/logs to browser/console.
- **Actors/Agents Seeding**: Not just create in seed—make them **active** on Channel 0.
  - Actors: SYSTEM (actor_id=0), CAPTAIN WOLFIE (actor_id=1), LILITH (actor_id=2).
  - Insert/update into relevant tables (e.g., `lupo_actors`, `lupo_channel_state`, `lupo_sessions` if tied).
  - Set status='active', channel_id=0, link to Task 1.
- **Task 1 Seeding**: Only task in this install.
  - Table: Assume `lupo_tasks` or similar (create TOON if missing: PK task_id BIGINT, description TEXT, status VARCHAR(64), assigned_actors JSON, created_ymdhis BIGINT).
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

**Target**: v4.0.53 stable. If schema changes needed, flag before push.

### Implementation Details

**Web Install Script Structure**:
```php
<?php
// install.php - Web-side Crafty upgrade installer
require_once 'lupo-includes/bootstrap.php';

echo "=== Lupopedia Crafty Syntax Upgrade Installer ===\n";
echo "Version: " . LUPOPEDIA_VERSION . "\n";
echo "Time: " . gmdate('Y-m-d H:i:s') . " UTC\n\n";

// Step 1: Validate current install
echo "🔍 Validating current installation...\n";
// Check if already installed, handle upgrade vs fresh install

// Step 2: Setup database tables
echo "🏗️ Setting up database tables...\n";
// Run install_new_lupopedia.sql with proper error handling

// Step 3: Seed actors and agents
echo "👥 Seeding actors and agents...\n";
// Insert SYSTEM (0), CAPTAIN WOLFIE (1), LILITH (2) as active on Channel 0

// Step 4: Create Task 1
echo "📋 Creating Task 1 (Install Validation)...\n";
// Insert task for install validation and Crafty migration

// Step 5: Start AI agents
echo "🤖 Starting AI agents...\n";
// Initialize AI classes and start oversight

// Step 6: Import Crafty data
echo "📥 Importing Crafty Syntax data...\n";
// Handle data migration with timestamp conversion

// Step 7: Final validation
echo "✅ Final validation...\n";
// Verify all components are working

echo "\n🎉 Crafty upgrade completed successfully!\n";
?>
```

**Database Seeding Logic**:
```php
// Seed actors
$actors = [
    0 => ['SYSTEM', 'System operations and table validation'],
    1 => ['CAPTAIN WOLFIE', 'Leadership coordination and oversight'],
    2 => ['LILITH', 'Critical review and documentation quality assurance']
];

foreach ($actors as $actor_id => $actor_info) {
    $db->execute(
        "INSERT INTO lupo_actors (actor_id, actor_name, actor_type, status, channel_id, created_ymdhis, updated_ymdhis, is_deleted) 
         VALUES (:actor_id, :actor_name, :actor_type, :status, :channel_id, :created, :updated, 0)
         ON DUPLICATE KEY UPDATE status = VALUES(status), updated_ymdhis = VALUES(updated_ymdhis)",
        [
            'actor_id' => $actor_id,
            'actor_name' => $actor_info[0],
            'actor_type' => 'ai_agent',
            'status' => 'active',
            'channel_id' => 0,
            'created' => gmdate('YmdHis'),
            'updated' => gmdate('YmdHis')
        ]
    );
}

// Create Task 1
$db->execute(
    "INSERT INTO lupo_tasks (task_id, description, status, assigned_actors, channel_id, created_ymdhis, updated_ymdhis, is_deleted) 
         VALUES (1, :description, :status, :assigned_actors, :channel_id, :created, :updated, 0)
         ON DUPLICATE KEY UPDATE description = VALUES(description), status = VALUES(status), assigned_actors = VALUES(assigned_actors), updated_ymdhis = VALUES(updated_ymdhis)",
    [
        'description' => 'Validate install_lupopedia.sql setup and import/migrate data from old Crafty Syntax 5.7.5 tables. Ensure no schema drift; log issues.',
        'status' => 'active',
        'assigned_actors' => json_encode([0, 1, 2]),
        'channel_id' => 0,
        'created' => gmdate('YmdHis'),
        'updated' => gmdate('YmdHis')
    ]
);
```

**AI Agent Integration**:
```php
// Start AI agents after seeding
$system_ai = new SystemAI(0);
$wolfie_ai = new CaptainWolfieAI(1);
$lilith_ai = new LilithAI(2);

// Assign Task 1 to all AI agents
$system_ai->assignTask(1);
$wolfie_ai->assignTask(1);
$lilith_ai->assignTask(1);

// Start AI oversight
$system_ai->start();
$wolfie_ai->start();
$lilith_ai->start();

echo "✅ AI Agents started with Task 1 assignment\n";
echo "   - SYSTEM AI (0): Table validation and migration oversight\n";
echo "   - CAPTAIN WOLFIE AI (1): Leadership and coordination\n";
echo "   - LILITH AI (2): Critical review and quality assurance\n";
```

**Crafty Data Migration**:
```php
// Example migration for a specific table
function migrateCraftyTable($crafty_table, $lupo_table, $field_mappings) {
    global $db;
    
    echo "🔄 Migrating $crafty_table → $lupo_table...\n";
    
    $sql = "INSERT INTO $lupo_table (" . implode(', ', array_keys($field_mappings)) . ")
              SELECT " . implode(', ', $field_mappings) . "
              FROM $crafty_table 
              WHERE is_deleted = 0";
    
    try {
        $db->execute($sql);
        echo "✅ Successfully migrated " . $db->rowCount() . " records\n";
    } catch (Exception $e) {
        echo "❌ Migration failed: " . $e->getMessage() . "\n";
        // Log to lupo_channel_logs
        $db->execute(
            "INSERT INTO lupo_channel_logs (channel_id, actor_id, log_type_id, log_text, created_ymdhis) 
                     VALUES (0, 1002, 1, :log_text, :created)",
            [
                'log_text' => 'Crafty migration failed for ' . $crafty_table . ': ' . $e->getMessage(),
                'created' => gmdate('YmdHis')
            ]
        );
    }
}

// Handle timestamp conversion
$field_mappings = [
    'id' => 'actor_id',
    'name' => 'actor_name',
    'created' => 'created_ymdhis', // Convert DATETIME to BIGINT
    'modified' => 'updated_ymdhis' // Convert DATETIME to BIGINT
];

// Example timestamp conversion SQL
$sql = "INSERT INTO lupo_actors (actor_id, actor_name, created_ymdhis, updated_ymdhis)
          SELECT id, name, 
                 UNIX_TIMESTAMP(STR_TO_DATE(created, '%Y-%m-%d %H:%i:%s')) * 10000 + 
                 UNIX_TIMESTAMP(STR_TO_DATE(modified, '%Y-%m-%d %H:%i:%s')) * 10000
          FROM crafty_operators 
          WHERE is_deleted = 0";
```

### Usage Instructions

**Web Installation**:
```bash
# Access via web browser
http://lupopedia.local/install.php

# With debug mode
http://lupopedia.local/install.php?debug=1

# Force upgrade (skip existing install check)
http://lupopedia.local/install.php?force=1
```

**Expected Output**:
```
=== Lupopedia Crafty Syntax Upgrade Installer ===
Version: 4.0.53
Time: 2026-03-01 10:00:00 UTC

🔍 Validating current installation...
✅ Installation validation complete

🏗️ Setting up database tables...
✅ Database tables created from install_new_lupopedia.sql
✅ All indexes created successfully

👥 Seeding actors and agents...
✅ SYSTEM AI (0): Active on Channel 0
✅ CAPTAIN WOLFIE AI (1): Active on Channel 0  
✅ LILITH AI (2): Active on Channel 0

📋 Creating Task 1 (Install Validation)...
✅ Task 1 created: Install validation and Crafty migration

🤖 Starting AI agents...
✅ AI Agents started with Task 1 assignment
   - SYSTEM AI (0): Table validation and migration oversight
   - CAPTAIN WOLFIE AI (1): Leadership and coordination
   - LILITH AI (2): Critical review and quality assurance

📥 Importing Crafty Syntax data...
✅ Crafty operators migrated: 15 records
✅ Crafty chat sessions migrated: 1,247 records
✅ Crafty help desk migrated: 89 records

✅ Final validation...
✅ All systems operational
✅ Task 1 assigned to AI agents

🎉 Crafty upgrade completed successfully!
Duration: 45 seconds
AI Agents: 3 active
Tasks: 1 active
Crafty Data: Migrated successfully
```

### Error Handling

**Installation Failures**:
- **Database Errors**: Log detailed error messages to `lupo_channel_logs`
- **Migration Failures**: Rollback capabilities with partial data recovery
- **AI Startup Issues**: Individual agent failure handling without stopping entire process

**Escalation Procedures**:
- **Critical Issues**: Automatically escalate to `lupo_channel_escalations`
- **Notification**: System notifications for failed operations
- **Recovery**: Automatic retry mechanisms for transient failures

---

**Implementation Target**: Complete by EOD 20260301  
**Priority**: High - Required for v4.0.53 stable release  
**Status**: ✅ READY FOR IMPLEMENTATION

📢 **CHANNEL 0 BROADCAST**  
WINDSURF: Boot enhancements for Crafty upgrade received—implementing web PHP install, AI seeding/active with Task 1.  
UTC: 20260301 (09:56 AM CST, Sioux Falls)
