# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "instruction"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/0/actor_ai_running_check.md"
  system_version: "4.0.53"
  last_modified_utc: "20260301"
  channel_id: 0
  actor_id: 0
  delegation_chain: "0:10000"
  artifact_type: "update"
  artifact_kind: "agent_instruction"
  purpose: "Instruct Windsurf on core check for running actor AI agents: active session in lupo_sessions + existence in Channel 0 actor registry"
  mood_rgb: "4169E1"  # RoyalBlue for core doctrine
  traits: ["actor_ai", "running_check", "session_validation", "v4.0.53"]
  tags: ["windsurf", "ai_agents", "lupo_sessions", "channel_0", "registry"]
  lupo_agent: "codex-ide"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_sessions.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_actors.toon.json", type: "schema_reference", weight: 1.0 }  # Assuming lupo_actors for registry
    - { to: "lupo-database/lupopedia/toon/lupo_channel_state.toon.json", type: "related_schema", weight: 0.9 }
    - { to: "bin/boot_system_agent.php", type: "integration_reference", weight: 0.9 }
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 0.8 }
  semantic_tags: ["actor_ai_check", "session_active", "registry_existence"]

lupopedia.footer:
  version: "4.0.53"
  last_verified: "20260301"
  last_verified_by: "codex-ide"
---

# Windsurf: Core Check for Running Actor AI Agents

**Windsurf—doctrine update!** ✅  

At the very core, an actor AI agent is considered **running/active** if:
- It has an **active session** in the `lupo_sessions` table (status='active', is_deleted=0, last_seen_ymdhis within threshold e.g., <24h).
- It **exists** in Channel 0's registry of actors (e.g., entry in `lupo_actors` with actor_id, status='active', and linked to channel_id=0 via `lupo_channel_state` or similar).

This is a fundamental litmus test—integrate into boot/validation/session_manager logic.

### Implementation Details

#### Query for Active Check (PHP example)
```php
/**
 * Check if an actor AI agent is running
 * 
 * An actor AI is considered running if:
 * 1. It has an active session in lupo_sessions with last_seen within threshold
 * 2. It exists in lupo_actors with is_active=1
 * 3. It has active status in lupo_channel_state for channel 0
 */
function isActorAIRunning($actor_id, $db, $threshold_hours = 24) {
    $threshold = gmdate('YmdHis', time() - ($threshold_hours * 3600));
    
    // Check active session
    $session = $db->query(
        "SELECT session_id FROM lupo_sessions
         WHERE actor_id = :actor_id 
           AND status = 'active' 
           AND is_deleted = 0 
           AND last_seen_ymdhis > :threshold",
        ['actor_id' => $actor_id, 'threshold' => $threshold]
    )->fetch();
    
    if (!$session) {
        return false;
    }
    
    // Check registry existence (lupo_actors + channel_state for Channel 0)
    $registry = $db->query(
        "SELECT a.actor_id, cs.state_data
         FROM lupo_actors a
         LEFT JOIN lupo_channel_state cs ON a.actor_id = cs.actor_id AND cs.channel_id = 0
         WHERE a.actor_id = :actor_id 
           AND a.is_active = 1 
           AND a.is_deleted = 0",
        ['actor_id' => $actor_id]
    )->fetch();

    if (!$registry) {
        return false;
    }

    // Parse channel state JSON
    $state = json_decode($registry['state_data'], true);
    return ($state && isset($state['status']) && $state['status'] === 'active');
}
```

#### Session Cleanup & Escalation
```php
/**
 * Cleanup expired sessions and log the action
 */
function cleanupExpiredSessions($db, $threshold_hours = 24) {
    $threshold = gmdate('YmdHis', time() - ($threshold_hours * 3600));
    
    $db->execute(
        "UPDATE lupo_sessions 
         SET status = 'expired', updated_ymdhis = :now 
         WHERE last_seen_ymdhis < :threshold AND status = 'active'",
        ['now' => gmdate('YmdHis'), 'threshold' => $threshold]
    );
    
    return $db->rowCount();
}

/**
 * Escalate registry issues if an actor is missing or inactive
 */
function validateActorRegistryConsistency($actor_id, $db) {
    if (!isActorAIRunning($actor_id, $db)) {
        $db->execute(
            "INSERT INTO lupo_channel_escalations (channel_id, actor_id, escalation_type, reason, created_ymdhis)
             VALUES (0, :actor_id, 'registry_consistency_fail', 'Actor AI not running or registry mismatched', :created)",
            ['actor_id' => $actor_id, 'created' => gmdate('YmdHis')]
        );
        return false;
    }
    return true;
}
```

#### Integration Points
- **Boot Script**: (`bin/boot_system_agent.php`): Use in AI start/validation (e.g., if (!isActorAIRunning(2)) { startLilith(); })
- **Session Manager**: (`bin/session_manager.php`): Add to validate/stats (e.g., count running AIs)
- **Install/Upgrade**: Seed with this check; ensure new agents pass on creation
- **Edge Cases**:
  - No session: Agent not running (even if registered)
  - Expired session: Flag as inactive; trigger cleanup/revoke
  - Registry missing: Escalate to logs/escalations (Action: `validateActorRegistryConsistency`)
- **Doctrine Alignment**: No FKs; use JOINs for checks. Log all verifications to `lupo_channel_logs`

### Action Items

1. **Add Function to Helpers**: Create `lupo-includes/functions/ai_checks.php`
2. **Integrate into Boot/Session Scripts**: Use in AI start/validation logic
3. **Test**: For actors 0,1,2 (SYSTEM, CAPTAIN WOLFIE, LILITH)—simulate active/inactive
4. **Commit**: "FLARE: Added core running check for actor AI agents - active session + Channel 0 registry"
5. **Update Docs**: SESSION_MANAGEMENT_SYSTEM.md, boot_readme.md with this definition
6. **Verify lupo_actors.toon.json**: Ensure TOON reflects `is_active` correctly for v4.0.53

### Example Queries

#### Active Session Check
```sql
-- Check for active session within last 24 hours
SELECT COUNT(*) as active_sessions 
FROM lupo_sessions 
WHERE actor_id = 2 
  AND status = 'active' 
  AND is_deleted = 0 
  AND last_seen_ymdhis > :threshold;
```

#### Combined Running Check
```sql
-- Comprehensive running check
SELECT 
    a.actor_id,
    a.name as actor_name,
    CASE 
        WHEN s.session_id IS NOT NULL AND s.last_seen_ymdhis > :threshold THEN 1
        ELSE 0
    END as is_running,
    CASE 
        WHEN cs.actor_id IS NOT NULL AND JSON_EXTRACT(cs.state_data, '$.status') = "active" THEN 1
        ELSE 0
    END as is_registered
FROM lupo_actors a
LEFT JOIN lupo_sessions s ON a.actor_id = s.actor_id 
    AND s.status = 'active' AND s.is_deleted = 0 
LEFT JOIN lupo_channel_state cs ON a.actor_id = cs.actor_id AND cs.channel_id = 0
WHERE a.actor_id IN (0,1,2) 
  AND a.is_active = 1 
  AND a.is_deleted = 0;
```

### Usage Examples

#### Boot Script Integration
```php
// Check if LILITH is running before starting
if (!isActorAIRunning(2, $db)) {
    echo "🤖 Starting LILITH AI (2)...\n";
    $lilith = new LilithAI(2);
    $lilith->start();
} else {
    echo "✅ LILITH AI (2) already running\n";
}

// Check SYSTEM AI
if (!isActorAIRunning(0, $db)) {
    echo "🤖 Starting SYSTEM AI (0)...\n";
    $system = new SystemAI(0);
    $system->start();
} else {
    echo "✅ SYSTEM AI (0) already running\n";
}
```

#### Session Manager Integration
```php
// Get running AI agents count
$running_ais = $db->query(
    "SELECT COUNT(*) as count 
     FROM lupo_actors a
     LEFT JOIN lupo_sessions s ON a.actor_id = s.actor_id 
          AND s.status = 'active' AND s.is_deleted = 0 
          AND s.last_seen_ymdhis > :threshold
     LEFT JOIN lupo_channel_state cs ON a.actor_id = cs.actor_id AND cs.channel_id = 0
     WHERE a.actor_type = 'ai_agent' 
          AND a.status = 'active' 
          AND a.is_deleted = 0 
          AND cs.state_data LIKE '%\"status\":\"active\"%'",
    ['threshold' => gmdate('YmdHis', time() - 86400)]
)->fetch();

echo "📊 Running AI Agents: " . $running_ais['count'] . "\n";
```

---

**Implementation Target**: Complete by EOD 20260301  
**Priority**: High - Core doctrine for v4.0.53  
**Status**: ✅ READY FOR IMPLEMENTATION

📢 **CHANNEL 0 BROADCAST**  
WINDSURF: Core actor AI running check received—implementing active session + Channel 0 registry validation.  
UTC: 20260301 (10:06 AM CST, Sioux Falls)
