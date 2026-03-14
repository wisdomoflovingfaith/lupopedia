# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "instruction"
  file_path_from_root: "lupo-channels/0/boot_enhancements_corrections.md"
  system_version: "4.0.53"
  last_modified_utc: "20260301"
  channel_id: 0
  actor_id: 0
  delegation_chain: "0:10000"
  artifact_type: "update"
  artifact_kind: "agent_instruction"
  purpose: "Instruct Windsurf to implement corrections to boot enhancements for Crafty upgrade based on LILITH's review"
  mood_rgb: "FF00FF"  # Fuchsia for Lilith-inspired review/action
  traits: ["boot_corrections", "crafty_upgrade", "lilith_review", "v4.0.53"]
  tags: ["windsurf", "boot_script", "crafty_syntax", "ai_agents", "review_fixes"]
  lupo_agent: "codex-ide"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-prompts/lilith/20260301_windsurf_boot_enhancements_review.md", type: "reviews", weight: 1.0 }
    - { to: "bin/boot_system_agent.php", type: "implementation_reference", weight: 1.0 }
    - { to: "install_lupopedia.sql", type: "schema_reference", weight: 0.9 }
    - { to: "lupo-docs/toons/lupo_tasks.toon.json", type: "schema_reference", weight: 0.8 }
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 0.8 }
  semantic_tags: ["crafty_upgrade", "review_corrections", "ai_boot"]

lupopedia.footer:
  version: "4.0.53"
  last_verified: "20260301"
  last_verified_by: "codex-ide"
---

# Windsurf: Boot Enhancements Corrections for Crafty Upgrade

**Windsurf—refine and perfect!** ✅  

Per LILITH's review (Channel 42, 9.4/10 score), implement these corrections to the system agent boot script enhancements for Crafty Syntax upgrade. Focus on web-side PHP execution during install, seeding active lupo-actors/agents on Channel 0 with Task 1. Apply all suggestions to make it 9.9/10 production-ready. We're on v4.0.53—any DB changes before v4.1.0.

### Corrections to Implement (from LILITH's Review)

#### 1. Add `lupo_tasks` Table Specification
- **Reference/Create TOON**: `lupo-docs/toons/lupo_tasks.toon.json`
- **Schema**: BIGINT task_id PK, TEXT description, VARCHAR(64) status, JSON assigned_actors, BIGINT channel_id, BIGINT created_ymdhis/updated_ymdhis, TINYINT is_deleted
- **No FKs/triggers**: Indexes on task_id/channel_id/status
- **Documentation**: Add table markdown as suggested

#### 2. Add Actor Seeding with Channel State Update
- **Update Seeding**: Include `lupo_channel_state` inserts/updates
- **Use Provided PHP**: foreach actors, INSERT ON DUPLICATE KEY UPDATE with state_data JSON ('status' => 'active', 'role' => ...)
- **Channel State Integration**: Proper actor state tracking on Channel 0

#### 3. Fix Timestamp Conversion Logic
- **Replace Incorrect SQL**: Use PHP function `craftyDatetimeToYmdHis($datetime)` using strtotime/gmdate
- **Alternative SQL**: CAST(DATE_FORMAT(created, '%Y%m%d%H%i%s') AS UNSIGNED)
- **Apply in Migration**: Consistent timestamp conversion throughout migration steps

#### 4. Add Rollback Capability
- **Transaction Wrapping**: Wrap seeding/migration in `$db->beginTransaction()/commit()/rollBack()`
- **Exception Handling**: Catch exceptions, log detailed errors, echo failures
- **Partial Recovery**: Handle partial migration failures with rollback mechanisms

#### 5. Document AI Agent Classes
- **Add Note**: "AI classes in `lupo-includes/classes/ai/`; load via autoloader or require_once"
- **Class Loading**: Ensure proper inclusion in install script
- **Example**: `require_once 'lupo-includes/classes/ai/SystemAI.php';` etc.

#### 6. Add Bootstrap File Check
- **Early Validation**: At script start, check `if (!file_exists('lupo-includes/bootstrap.php')) die("❌ ERROR: bootstrap.php not found.");`
- **Dependency Check**: Ensure all required files are present before execution

#### 7. Channel 0 Broadcast Format
- **FLARE Headers**: Ensure broadcasts have proper FLARE header structure
- **Message Format**: Wrap broadcast messages in markdown with standardized headers

### Enhanced Sections (Incorporate Directly)

#### Database Seeding with Channel State
```php
// Enhanced seeding with channel state tracking
$actors = [
    0 => ['SYSTEM', 'System operations and table validation'],
    1 => ['CAPTAIN WOLFIE', 'Leadership coordination and oversight'],
    2 => ['LILITH', 'Critical review and documentation quality assurance']
];

try {
    $db->beginTransaction();
    
    foreach ($actors as $actor_id => $actor_info) {
        // Insert/update actor
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
        
        // Insert/update channel state
        $db->execute(
            "INSERT INTO lupo_channel_state (actor_id, channel_id, state_data, created_ymdhis, updated_ymdhis, is_deleted) 
                 VALUES (:actor_id, :channel_id, :state_data, :created, :updated, 0)
                 ON DUPLICATE KEY UPDATE state_data = VALUES(state_data), updated_ymdhis = VALUES(updated_ymdhis)",
            [
                'actor_id' => $actor_id,
                'channel_id' => 0,
                'state_data' => json_encode([
                    'status' => 'active',
                    'role' => strtolower($actor_info[0]) === 'system' ? 'system_agent' : 'ai_agent',
                    'last_seen' => gmdate('YmdHis'),
                    'task_assignment' => 1
                ]),
                'created' => gmdate('YmdHis'),
                'updated' => gmdate('YmdHis')
            ]
        );
    }
    
    $db->commit();
    echo "✅ Actors and channel state seeded successfully\n";
    
} catch (Exception $e) {
    $db->rollBack();
    echo "❌ Seeding failed: " . $e->getMessage() . "\n";
    // Log to lupo_channel_logs
    $db->execute(
        "INSERT INTO lupo_channel_logs (channel_id, actor_id, log_type_id, log_text, created_ymdhis) 
                 VALUES (0, 1002, 1, :log_text, :created)",
        [
            'log_text' => 'Actor seeding failed: ' . $e->getMessage(),
            'created' => gmdate('YmdHis')
        ]
    );
}
```

#### Correct Timestamp Conversion
```php
// PHP function for proper timestamp conversion
function craftyDatetimeToYmdHis($datetime) {
    // Handle various Crafty datetime formats
    $formats = [
        'Y-m-d H:i:s',
        'Y-m-d H:i',
        'Y/m/d H:i:s',
        'm/d/Y H:i:s'
    ];
    
    foreach ($formats as $format) {
        $timestamp = strtotime($datetime);
        if ($timestamp !== false) {
            return gmdate('YmdHis', $timestamp);
        }
    }
    
    // Fallback for invalid formats
    throw new Exception("Invalid datetime format: $datetime");
}

// SQL alternative
$sql = "INSERT INTO lupo_actors (actor_id, actor_name, created_ymdhis, updated_ymdhis)
          SELECT id, name, 
                 CASE 
                     WHEN created REGEXP '^[0-9]{14}$' THEN CAST(created AS UNSIGNED)
                     ELSE UNIX_TIMESTAMP(STR_TO_DATE(created, '%Y-%m-%d %H:%i:%s')) * 10000
                 END as created_ymdhis,
                 CASE 
                     WHEN modified REGEXP '^[0-9]{14}$' THEN CAST(modified AS UNSIGNED)
                     ELSE UNIX_TIMESTAMP(STR_TO_DATE(modified, '%Y-%m-%d %H:%i:%s')) * 10000
                 END as updated_ymdhis
          FROM crafty_operators 
          WHERE is_deleted = 0";
```

### Additional Notes

#### Web-Side Focus
- **Install Script**: Ensure `install.php`/`upgrade_crafty.php` handles all parameters (install=1, debug=1, force=1)
- **CLI Fallback**: Provide command-line interface for testing and automation
- **Session Integration**: Use existing session management for user authentication during install

#### AI Class Documentation
```php
// In install script, load AI classes
require_once 'lupo-includes/bootstrap.php';

// Load AI classes
require_once 'lupo-includes/classes/ai/SystemAI.php';
require_once 'lupo-includes/classes/ai/CaptainWolfieAI.php';
require_once 'lupo-includes/classes/ai/LilithAI.php';

// Initialize and start
$system_ai = new SystemAI(0);
$wolfie_ai = new CaptainWolfieAI(1);
$lilith_ai = new LilithAI(2);
```

#### Channel 0 Broadcast Format
```markdown
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "broadcast"
  file_path_from_root: "lupo-channels/0/broadcast_message.md"
  system_version: "4.0.53"
  last_modified_utc: "20260301"
  channel_id: 0
  actor_id: 1002
  delegation_chain: "0:10000"
  artifact_type: "broadcast"
  artifact_kind: "status_update"
  purpose: "Broadcast completion of Crafty upgrade boot enhancements"
  mood_rgb: "32CD32"  # LimeGreen for success
  traits: ["crafty_upgrade", "boot_completion", "v4.0.53"]
  tags: ["windsurf", "broadcast", "crafty_syntax", "completion"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/0/boot_enhancements_corrections.md", type: "instruction_reference", weight: 1.0 }
  semantic_tags: ["crafty_upgrade", "completion", "broadcast"]

lupopedia.footer:
  version: "4.0.53"
  last_verified: "20260301"
  last_verified_by: "windsurf"
---

# Broadcast: Crafty Upgrade Boot Enhancements Completed

**Windsurf—implementation complete!** ✅  

Successfully applied LILITH's review corrections to boot enhancements for Crafty upgrade. All suggestions implemented for 9.9/10 production readiness.

## Status Update
- **Corrections Applied**: All 7 items from LILITH's review implemented
- **TOON Schema**: lupo_tasks specification created
- **Channel State**: Enhanced seeding with proper state tracking
- **Timestamp Logic**: Fixed conversion with PHP functions and SQL alternatives
- **Rollback**: Added transaction wrapping and error recovery
- **AI Classes**: Documented proper loading and integration
- **Bootstrap**: Added dependency validation at script start
- **Broadcasts**: Ensured proper FLARE header format

## Next Steps
- **Implementation**: Apply corrections to install script and boot enhancements
- **Testing**: Comprehensive validation of all components
- **Documentation**: Update all related documentation
- **Quality**: Target 9.9/10 production readiness score

📢 **CHANNEL 0 BROADCAST**  
WINDSURF: LILITH review corrections implemented—boot enhancements refined to 9.9/10 readiness.  
UTC: 20260301 (10:02 AM CST, Sioux Falls)  
```

---

**Implementation Target**: Complete by EOD 20260301  
**Priority**: High - Required for v4.0.53 stable release  
**Status**: ✅ READY FOR IMPLEMENTATION

📢 **CHANNEL 0 BROADCAST**  
WINDSURF: Boot enhancements corrections from LILITH review received—implementing fixes for 9.9/10 readiness.  
UTC: 20260301 (10:01 AM CST, Sioux Falls)
