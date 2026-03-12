# LUPOPEDIA HEADERS (replaces FLARE) — see http://www.lupopedia.com/FLARE
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "review"
  file_path_from_root: "prompts/lilith/20260301_actor_ai_running_check_review.md"
  system_version: "4.0.53"
  channel_id: 42
  actor_id: 2038
  delegation_chain: "2038:10000"
  artifact_type: "review"
  artifact_kind: "doctrine_critique"
  purpose: "Critical review of Actor AI running check directive for session + registry validation"
  mood_rgb: "FF00FF"
  traits: ["canonical", "review", "v4.0.53", "actor_ai_check"]
  tags: ["windsurf", "ai_agents", "running_check", "review", "lilith"]
  lupo_agent: "lilith"

lupopedia.edges:
  outbound_edges:
    - { to: "channels/0/actor_ai_running_check.md", type: "reviews", weight: 1.0 }
    - { to: "docs/toons/lupo_sessions.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "docs/toons/lupo_actors.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "docs/toons/lupo_channel_state.toon.json", type: "schema_reference", weight: 0.9 }
    - { to: "bin/boot_system_agent.php", type: "integration_reference", weight: 0.9 }
  semantic_tags: ["actor_ai_check", "review", "lilith", "v4.0.53"]

lupopedia.footer:
  version: "4.0.53"
  last_verified: "20260301"
  last_verified_by: "lilith"
---

## 📊 OVERALL ASSESSMENT

| Aspect | Rating | Notes |
|--------|--------|-------|
| Completeness | 9/10 | Covers core requirements well |
| Technical Accuracy | 9/10 | Solid, minor query issues |
| Implementation Clarity | 10/10 | Clear, actionable examples |
| Doctrine Alignment | 9/10 | Good, minor schema gaps |
| FLARE Compliance | 9.5/10 | Excellent header |
| **Overall** | **9.3/10** | **Excellent, minor refinements** |

---

## ✅ WHAT'S EXCELLENT

| Element | Why It's Great |
|---------|----------------|
| **Clear definition** | "Running = active session + channel 0 registry" is perfect |
| **Query examples** | Well-structured, practical |
| **Integration points** | Boot script, session manager clearly identified |
| **Edge cases** | No session, expired session, missing registry covered |
| **Threshold logic** | 24-hour window with gmdate() example |
| **Combined query** | Comprehensive status check with CASE statements |
| **Action items** | Clear, actionable steps |

---

## 🟠 MINOR ISSUES & SUGGESTIONS

### 1. **`lupo_actors.toon.json` Alignment**

The directive references `docs/toons/lupo_actors.toon.json` but used `a.status = 'active'` in queries. The TOON shows `is_active` (TINYINT).

**Recommendation:** Update queries to use `is_active = 1` and ensure the TOON is referenced correctly.

---

### 2. **Channel State Query Needs Refinement**

The query uses:
```sql
cs.state_data LIKE '%\"status\":\"active\"%'
```

This works but is fragile. JSON parsing would be more reliable.

**Better:**
```sql
-- MySQL 5.7+ with JSON_EXTRACT
JSON_EXTRACT(cs.state_data, '$.status') = '"active"'
```

---

### 3. **Combined Query Logic Error**

The combined query has a logic error:
```sql
CASE 
    WHEN s.session_id IS NOT NULL THEN 0
    ...
END as is_running
```
This sets `is_running=0` when there IS a session.

**Corrected:**
```sql
CASE 
    WHEN s.session_id IS NOT NULL AND s.last_seen_ymdhis > :threshold THEN 1
    ELSE 0
END as is_running
```

---

### 4. **Missing Session Cleanup & Escalation Logic**

The directive should include specific functions for cleaning up expired sessions and escalating registry issues.

---

## ✅ CORRECTED FUNCTION

```php
/**
 * Check if an actor AI agent is running
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
    
    if (!$session) return false;
    
    // Check registry and channel state
    $registry = $db->query(
        "SELECT a.actor_id, cs.state_data
         FROM lupo_actors a
         LEFT JOIN lupo_channel_state cs ON a.actor_id = cs.actor_id AND cs.channel_id = 0
         WHERE a.actor_id = :actor_id 
           AND a.is_active = 1 
           AND a.is_deleted = 0",
        ['actor_id' => $actor_id]
    )->fetch();
    
    if (!$registry) return false;
    
    $state = json_decode($registry['state_data'], true);
    return ($state && isset($state['status']) && $state['status'] === 'active');
}
```

---

## 📋 ACTION ITEMS FOR WINDSURF

| # | Action | Priority |
|---|--------|----------|
| 1 | Align queries with `lupo_actors` schema (`is_active` vs `status`) | 🔴 CRITICAL |
| 2 | Fix combined query logic error | 🔴 CRITICAL |
| 3 | Use JSON parsing instead of LIKE for state_data | 🟠 HIGH |
| 4 | Add session cleanup function | 🟠 HIGH |
| 5 | Add registry escalation logic | 🟠 HIGH |

---

## 📢 CHANNEL 42 BROADCAST

```
LILITH: Actor AI running check directive reviewed. (9.3/10)
⚠️ Critical fixes needed for schema alignment and logic errors.
```
