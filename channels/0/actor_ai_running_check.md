# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "instruction"
  file_path_from_root: "channels/0/actor_ai_running_check.md"
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

flare.edges:
  outbound_edges:
    - { to: "docs/toons/lupo_sessions.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "docs/toons/lupo_actors.toon.json", type: "schema_reference", weight: 1.0 }  # Assuming lupo_actors for registry
    - { to: "docs/toons/lupo_channel_state.toon.json", type: "related_schema", weight: 0.9 }
    - { to: "bin/boot_system_agent.php", type: "integration_reference", weight: 0.9 }
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 0.8 }
  semantic_tags: ["actor_ai_check", "session_active", "registry_existence"]

flare.footer:
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
function isActorAIRunning($actor_id, $db) {
    // Check registry existence (lupo_actors + channel_state for Channel 0)
    $registry_check = $db->query(
        "SELECT 1 FROM lupo_actors a
           LEFT JOIN lupo_channel_state cs ON a.actor_id = cs.actor_id AND cs.channel_id = 0
           WHERE a.actor_id = :actor_id AND a.status = 'active' AND a.is_deleted = 0
           AND cs.state_data LIKE '%\"status\":\"active\"%'",
        ['actor_id' => $actor_id]
    )->fetch();

    // Check active session
    $session_check = $db->query(
        "SELECT 1 FROM lupo_sessions
           WHERE actor_id = :actor_id AND status = 'active' AND is_deleted = 0
           AND last_seen_ymdhis > :threshold",  // e.g., gmdate('YmdHis', time() - 86400) for 24h
        ['actor_id' => $actor_id, 'threshold' => gmdate('YmdHis', time() - 86400)]
    )->fetch();

    return $registry_check && $session_check;
}
```

#### Integration Points
- **Boot Script**: (`bin/boot_system_agent.php`): Use in AI start/validation (e.g., if (!isActorAIRunning(2)) { startLilith(); })
- **Session Manager**: (`bin/session_manager.php`): Add to validate/stats (e.g., count running AIs)
- **Install/Upgrade**: Seed with this check; ensure new agents pass on creation
- **Edge Cases**:
  - No session: Agent not running (even if registered)
  - Expired session: Flag as inactive; trigger cleanup/revoke
  - Registry missing: Escalate to logs/escalations
- **Doctrine Alignment**: No FKs; use JOINs for checks. Log all verifications to `lupo_channel_logs`

### Action Items

1. **Add Function to Helpers**: Create `lupo-includes/functions/ai_checks.php`
2. **Integrate into Boot/Session Scripts**: Use in AI start/validation logic
3. **Test**: For actors 0,1,2 (SYSTEM, CAPTAIN WOLFIE, LILITH)—simulate active/inactive
4. **Commit**: "FLARE: Added core running check for actor AI agents - active session + Channel 0 registry"
5. **Update Docs**: SESSION_MANAGEMENT_SYSTEM.md, boot_readme.md with this definition
6. **Generate lupo_actors.toon.json**: TOON for actor registry table, v4.0.53

### Example Queries

#### Active Session Check
```sql
-- Check for active session within last 24 hours
SELECT COUNT(*) as active_sessions 
FROM lupo_sessions 
WHERE actor_id = 2 
  AND status = 'active' 
  AND is_deleted = 0 
  AND last_seen_ymdhis > 20260228100000;

-- Check registry existence in Channel 0
SELECT 1 as is_registered 
FROM lupo_actors a
LEFT JOIN lupo_channel_state cs ON a.actor_id = cs.actor_id AND cs.channel_id = 0
WHERE a.actor_id = 2 
  AND a.status = 'active' 
  AND a.is_deleted = 0 
  AND cs.state_data LIKE '%\"status\":\"active\"%';
```

#### Combined Running Check
```sql
-- Comprehensive running check
SELECT 
    a.actor_id,
    a.actor_name,
    CASE 
        WHEN s.session_id IS NOT NULL THEN 0
        WHEN s.last_seen_ymdhis < 20260228100000 THEN 0
        ELSE 1
    END as is_running,
    CASE 
        WHEN cs.actor_id IS NOT NULL THEN 0
        WHEN cs.state_data LIKE '%\"status\":\"active\"%' THEN 1
        ELSE 0
    END as is_registered
FROM lupo_actors a
LEFT JOIN lupo_sessions s ON a.actor_id = s.actor_id 
    AND s.status = 'active' AND s.is_deleted = 0 
    AND s.last_seen_ymdhis > 20260228100000
LEFT JOIN lupo_channel_state cs ON a.actor_id = cs.actor_id AND cs.channel_id = 0
WHERE a.actor_id IN (0,1,2) 
  AND a.status = 'active' 
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
