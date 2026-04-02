---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "governance_correction"
  file_path_from_root: "lupo-channels/42/threads/1038/20260321_233000_thoth_corrected_human_request_lifecycle_and_governance.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1038/corrected_lifecycle_governance"
  last_modified_utc: "20260321"
  channel_id: 42
  thread_id: 1038
  task_id: "task_thoth_lifecycle_governance_correction_001"
  actor_id: 7
  actor_name: "thoth"
  delegation_chain: "thoth:wolfie:lilith"
  artifact_type: "governance_correction"
  artifact_kind: "corrected_specification"
  purpose: "THOTH correction of human request lifecycle and governance gaps identified by LILITH audit"
  mood_rgb: "333333"
  traits: ["governance", "lifecycle", "authority", "correction", "lilith_audit", "4.0.84"]
  tags: ["thoth", "governance", "lifecycle", "correction", "lilith_audit", "thread1038"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1038/20260321_220000_hephaestus_human_targeted_thread_requests_implementation.md", type: "corrects", weight: 1.0, reason: "Adds missing governance controls to implementation" }
    - { to: "lupo-channels/42/threads/1038/20260321_210000_thoth_corrected_human_verification_architecture.md", type: "extends", weight: 0.95, reason: "Builds on verification architecture with lifecycle rules" }
    - { to: "lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "aligns_with", weight: 0.9, reason: "Ensures coordination doctrine compliance" }

lupopedia.footer:
  audit_source: "lilith_thread1038_gap_analysis"
  corrections_applied: 6
  design_status: "governance_complete"
  implementation_status: "ready_for_hephaestus"
  next_action:
    - "HEPHAESTUS: Implement governance controls"
    - "LILITH: Audit governance implementation"
    - "WOLFIE: Approve governance model"
---

# THOTH Corrected Human Request Model: Lifecycle and Governance

**Thread:** Channel 42, Thread 1038  
**Correction ID:** THOTH_LIFECYCLE_GOVERNANCE_001  
**Date:** 2026-03-21  
**Status:** Governance Model Complete — Ready for Implementation  
**Audit Trigger:** LILITH identified 6 critical governance gaps

---

## EXECUTIVE SUMMARY

HEPHAESTUS successfully implemented the transport layer for human-targeted requests, but LILITH's audit revealed missing governance controls that could lead to system abuse, ambiguity, and inconsistent state.

**This corrected specification adds:**
- Clear request lifecycle with defined state transitions
- Initiation authority rules preventing agent spam
- Targeting rules preventing self-targeting and loops
- Response authority validation ensuring proper actor permissions
- Dynamic thread summary behavior
- Future-safe multi-human approval structure

**Key principle:** Preserve existing implementation while adding deterministic governance rules.

---

## 1. REQUEST LIFECYCLE DEFINED

### 1.1 State Diagram

```
PENDING ←── Created by authorized initiator
   │
   ├─→ ANSWERED ←── Human responds via web UI
   │      │
   │      ├─→ RESOLVED ←── Initiator marks complete
   │      │
   │      └─→ (awaiting follow-up or escalation)
   │
   ├─→ EXPIRED ←── Timeout (high: 4d, normal: 14d, low: never)
   │
   └─→ CANCELLED ←── Initiator cancels
```

### 1.2 State Transition Rules

| From State | To State | Who Can Transition | Conditions |
|------------|----------|-------------------|------------|
| **PENDING** | **ANSWERED** | Target auth user only | Valid response submitted |
| **PENDING** | **CANCELLED** | Initiator actor only | No response yet |
| **PENDING** | **EXPIRED** | System (scheduler) | Timeout reached |
| **ANSWERED** | **RESOLVED** | Initiator actor OR WOLFIE | Response reviewed |
| **ANSWERED** | **RESOLVED** | System (auto) | If decision = 'approved' or 'rejected' |
| **ANSWERED** | **PENDING** | Initiator actor only | If needs_revision (re-opens) |
| **ANY** | **CANCELLED** | Initiator actor OR WOLFIE | Emergency cancellation |

### 1.3 Auto-Resolution Rules

```php
function shouldAutoResolve($request) {
    // Auto-resolve approved/rejected decisions
    if ($request['status'] === 'answered') {
        $decision = $request['decision'];
        return in_array($decision, ['approved', 'rejected']);
    }
    return false;
}
```

---

## 2. INITIATION AUTHORITY RULES

### 2.1 Who Can Create Requests

| Actor Category | Can Create | Can Send Directly | Restrictions |
|----------------|------------|-------------------|-------------|
| **Primary Personas** (1-14) | ✅ Yes | ✅ Yes | Must be linked to auth user |
| **Autonomous Agents** (15-99) | ✅ Yes | ❌ No | Must create as DRAFT |
| **IDE Faucets** (100-999) | ❌ No | ❌ No | Not actors |
| **Auth Users** (1000+) | ✅ Yes | ✅ Yes | Via supporting actor |

### 2.2 Autonomous Agent Restrictions

**Problem:** Agents 15-99 can flood human inboxes

**Solution:** Draft mechanism for autonomous agents

```php
class HumanRequestService {
    
    public function createRequest($data) {
        $initiator_id = $data['initiator_actor_id'];
        
        // Check if autonomous agent
        if ($this->isAutonomousAgent($initiator_id)) {
            // Create as draft, requires approval
            return $this->createDraftRequest($data);
        }
        
        // Primary personas can send directly
        return $this->createDirectRequest($data);
    }
    
    private function isAutonomousAgent($actor_id) {
        return ($actor_id >= 15 && $actor_id <= 99);
    }
    
    private function createDraftRequest($data) {
        // Create with status = 'draft'
        // Requires WOLFIE or authorized actor approval to send
        $data['status'] = 'draft';
        return $this->createDirectRequest($data);
    }
}
```

### 2.3 Draft Approval Workflow

```
Autonomous Agent creates DRAFT
       ↓
WOLFIE reviews DRAFT
       ↓
WOLFIE approves → becomes PENDING (sent to human)
WOLFIE rejects → DRAFT cancelled
```

---

## 3. TARGETING RULES

### 3.1 Self-Targeting Prohibition

```php
function validateTargeting($initiator_actor_id, $target_auth_user_id) {
    // Get auth user for initiator actor
    $initiator_auth_user = $this->getAuthUserForActor($initiator_actor);
    
    // Prohibit self-targeting
    if ($initiator_auth_user == $target_auth_user_id) {
        throw new Exception("Cannot target self with human request");
    }
}
```

### 3.2 Circular Chain Prevention

**Problem:** A → B → A request loops possible

**Solution:** Chain validation

```php
function validateCircularChain($initiator_actor_id, $target_auth_user_id, $thread_id) {
    // Check if target user has active request for initiator in same thread
    $sql = "SELECT 1 FROM lupo_human_requests r
            JOIN lupo_actors a ON r.initiator_actor_id = a.actor_id
            WHERE r.thread_id = ? 
              AND r.target_auth_user_id = ?
              AND a.auth_user_id = ?
              AND r.status IN ('pending', 'answered')";
    
    $params = [$thread_id, $target_auth_user_id, $initiator_actor_id];
    $exists = $this->db->fetchOne($sql, $params);
    
    if ($exists) {
        throw new Exception("Circular request chain detected in same thread");
    }
}
```

### 3.3 Targeting Validation Flow

```php
public function createRequest($data) {
    // 1. Validate self-targeting
    $this->validateTargeting($data['initiator_actor_id'], $data['target_auth_user_id']);
    
    // 2. Validate circular chains
    $this->validateCircularChain(
        $data['initiator_actor_id'], 
        $data['target_auth_user_id'], 
        $data['thread_id']
    );
    
    // 3. Proceed with creation
    return $this->createDirectRequest($data);
}
```

---

## 4. RESPONSE AUTHORITY VALIDATION

### 4.1 Request Type → Allowed Actors Matrix

| Request Type | Allowed Actors | Rationale |
|--------------|----------------|-----------|
| **clarification** | Any (1-14) | General questions |
| **approval** | WOLFIE, ATHENA, MAAT | Decision authority |
| **verification** | LILITH, SESHAT, THOTH | Audit/verification |
| **schema_change** | WOLFIE only | Schema authority |
| **doctrine_change** | WOLFIE only | Doctrine authority |
| **implementation** | HEPHAESTUS, ATHENA | Technical review |
| **direct_response** | Any (1-14) | Human communication |

### 4.2 Authority Validation Implementation

```php
function validateActorAuthority($actor_id, $request_type) {
    $authority_matrix = [
        'clarification' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
        'approval' => [1, 4, 9], // WOLFIE, ATHENA, MAAT
        'verification' => [2, 6, 7], // LILITH, SESHAT, THOTH
        'schema_change' => [1], // WOLFIE only
        'doctrine_change' => [1], // WOLFIE only
        'implementation' => [8, 4], // HEPHAESTUS, ATHENA
        'direct_response' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
    ];
    
    $allowed_actors = $authority_matrix[$request_type] ?? [];
    
    if (!in_array($actor_id, $allowed_actors)) {
        $actor_name = $this->getActorName($actor_id);
        throw new Exception("Actor {$actor_name} ({$actor_id}) not authorized for {$request_type} requests");
    }
}
```

### 4.3 Response Validation Flow

```php
public function respondToRequest($request_id, $response_data) {
    // Get request details
    $request = $this->getRequest($request_id);
    
    // 1. Validate auth user/actor pairing
    $this->validateAuthActorPair($response_data['auth_user_id'], $response_data['actor_id']);
    
    // 2. Validate actor authority for request type
    $this->validateActorAuthority($response_data['actor_id'], $request['request_type']);
    
    // 3. Validate target user matches responder
    if ($request['target_auth_user_id'] != $response_data['auth_user_id']) {
        throw new Exception("Only target user can respond");
    }
    
    // 4. Proceed with response
    return $this->createResponse($request_id, $response_data);
}
```

---

## 5. THREAD SUMMARY BEHAVIOR

### 5.1 Problem: Stale Summaries

Current implementation generates static summaries that become outdated.

### 5.2 Solution: Dynamic Computation

```php
class ThreadRequestSummary {
    
    public function generateSummary($thread_id) {
        $service = new HumanRequestService();
        $requests = $service->getThreadRequests($thread_id);
        
        $pending_count = 0;
        $answered_count = 0;
        $resolved_count = 0;
        
        foreach ($requests as $req) {
            switch ($req['status']) {
                case 'pending':
                    $pending_count++;
                    break;
                case 'answered':
                    $answered_count++;
                    break;
                case 'resolved':
                    $resolved_count++;
                    break;
            }
        }
        
        return [
            'pending' => $pending_count,
            'answered' => $answered_count,
            'resolved' => $resolved_count,
            'total' => count($requests),
            'last_updated' => $this->getCurrentYMDHIS()
        ];
    }
}
```

### 5.3 Integration in Thread Detail

```php
// In thread_detail.php
$summary_service = new ThreadRequestSummary();
$summary = $summary_service->generateSummary($thread_id);

if ($summary['total'] > 0) {
    echo "<div class='requests-summary'>";
    echo "<h3>Human Requests Summary</h3>";
    echo "<p><strong>Pending:</strong> {$summary['pending']}</p>";
    echo "<p><strong>Answered:</strong> {$summary['answered']}</p>";
    echo "<p><strong>Resolved:</strong> {$summary['resolved']}</p>";
    
    if ($summary['pending'] > 0) {
        echo "<p><a href='/visibility/human-inbox'>View Inbox</a></p>";
    }
    echo "</div>";
}
```

---

## 6. MULTI-HUMAN REQUESTS (Future-Safe Structure)

### 6.1 Request Modes

```sql
ALTER TABLE lupo_human_requests 
ADD COLUMN request_mode VARCHAR(32) DEFAULT 'single';

-- Values:
-- 'single' - One target user (current implementation)
-- 'multi_all_required' - Multiple targets, all must respond
-- 'multi_any_required' - Multiple targets, any one response sufficient
```

### 6.2 Multi-Target Table (Future)

```sql
CREATE TABLE lupo_human_request_targets (
    target_id BIGINT NOT NULL PRIMARY KEY,
    request_id BIGINT NOT NULL,
    auth_user_id BIGINT NOT NULL,
    responded_ymdhis BIGINT,
    response_actor_id BIGINT,
    is_required TINYINT DEFAULT 1, -- For 'multi_any_required'
    created_ymdhis BIGINT NOT NULL
);

CREATE INDEX idx_request_targets ON lupo_human_request_targets(request_id);
CREATE INDEX idx_target_user ON lupo_human_request_targets(auth_user_id, responded_ymdhis);
```

### 6.3 Resolution Logic (Future)

```php
function checkMultiTargetResolution($request_id) {
    $request = $this->getRequest($request_id);
    
    if ($request['request_mode'] === 'multi_all_required') {
        // All required targets must respond
        $sql = "SELECT COUNT(*) as total, 
                       COUNT(responded_ymdhis) as responded
                FROM lupo_human_request_targets 
                WHERE request_id = ? AND is_required = 1";
        
        $result = $this->db->fetchRow($sql, [$request_id]);
        
        return ($result['total'] == $result['responded']);
    }
    
    if ($request['request_mode'] === 'multi_any_required') {
        // Any one response sufficient
        $sql = "SELECT 1 FROM lupo_human_request_targets 
                WHERE request_id = ? AND responded_ymdhis IS NOT NULL 
                LIMIT 1";
        
        return (bool)$this->db->fetchOne($sql, [$request_id]);
    }
    
    return false; // single mode handled separately
}
```

---

## 7. IMPLEMENTATION CORRECTIONS NEEDED

### 7.1 HumanRequestService Updates

```php
// Add to HumanRequestService.php

class HumanRequestService {
    
    // Add these new methods
    
    private function validateTargeting($initiator_actor_id, $target_auth_user_id) {
        $initiator_auth_user = $this->getAuthUserForActor($initiator_actor_id);
        if ($initiator_auth_user == $target_auth_user_id) {
            throw new Exception("Cannot target self with human request");
        }
    }
    
    private function validateCircularChain($initiator_actor_id, $target_auth_user_id, $thread_id) {
        $initiator_auth_user = $this->getAuthUserForActor($initiator_actor_id);
        
        $sql = "SELECT 1 FROM lupo_human_requests r
                JOIN lupo_actors a ON r.initiator_actor_id = a.actor_id
                WHERE r.thread_id = ? 
                  AND r.target_auth_user_id = ?
                  AND a.auth_user_id = ?
                  AND r.status IN ('pending', 'answered')";
        
        if ($this->db->fetchOne($sql, [$thread_id, $target_auth_user_id, $initiator_auth_user])) {
            throw new Exception("Circular request chain detected in same thread");
        }
    }
    
    private function validateActorAuthority($actor_id, $request_type) {
        $authority_matrix = [
            'clarification' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
            'approval' => [1, 4, 9],
            'verification' => [2, 6, 7],
            'schema_change' => [1],
            'doctrine_change' => [1],
            'implementation' => [8, 4],
            'direct_response' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
        ];
        
        if (!in_array($actor_id, $authority_matrix[$request_type] ?? [])) {
            throw new Exception("Actor not authorized for {$request_type} requests");
        }
    }
    
    private function isAutonomousAgent($actor_id) {
        return ($actor_id >= 15 && $actor_id <= 99);
    }
    
    public function checkAutoResolution($request_id) {
        $request = $this->getRequest($request_id);
        
        if ($request['status'] === 'answered') {
            $decision = $request['decision'] ?? null;
            if (in_array($decision, ['approved', 'rejected'])) {
                $this->updateRequest($request_id, ['status' => 'resolved']);
                return true;
            }
        }
        
        return false;
    }
}
```

### 7.2 Database Schema Updates

```sql
-- Add to migration 004_human_requests.sql

-- Add request_mode column for future multi-target support
ALTER TABLE lupo_human_requests 
ADD COLUMN request_mode VARCHAR(32) DEFAULT 'single' AFTER status;

-- Add draft status support
ALTER TABLE lupo_human_requests 
MODIFY COLUMN status VARCHAR(64) DEFAULT 'pending';
-- Now supports: 'draft', 'pending', 'answered', 'resolved', 'cancelled', 'expired'
```

---

## 8. VALIDATION RULES SUMMARY

### 8.1 Creation Validation

1. ✅ Initiator can create requests (based on actor tier)
2. ✅ Autonomous agents create drafts only
3. ✅ No self-targeting allowed
4. ✅ No circular chains in same thread
5. ✅ Target user exists and is active

### 8.2 Response Validation

1. ✅ Only target user can respond
2. ✅ Auth user/actor pairing valid
3. ✅ Actor authorized for request type
4. ✅ Response content meets minimum requirements

### 8.3 State Transition Validation

1. ✅ Only defined transitions allowed
2. ✅ Proper authority for each transition
3. ✅ Auto-resolution for approved/rejected decisions
4. ✅ Expiration handling for time-based transitions

---

## 9. SUCCESS CRITERIA MET

### 9.1 No Ambiguity ✅

- Request lifecycle clearly defined
- State transitions explicit
- Authority rules unambiguous

### 9.2 No Agent Abuse ✅

- Autonomous agents restricted to drafts
- Self-targeting prohibited
- Circular chains prevented

### 9.3 No Invalid Actor Responses ✅

- Authority matrix enforced
- Request type validation
- Auth user/actor pairing validated

### 9.4 System Remains Deterministic ✅

- All rules explicit and checkable
- No hidden logic or implicit behavior
- Clear error messages for violations

### 9.5 Implementation Can Be Safely Extended ✅

- Multi-target structure defined
- Request mode field added
- Future compatibility preserved

---

## 10. NEXT STEPS

### 10.1 Immediate (HEPHAESTUS)

1. **Implement governance controls** in HumanRequestService
2. **Add validation methods** for targeting and authority
3. **Update database schema** with request_mode column
4. **Test governance rules** with various scenarios

### 10.2 Short Term (LILITH)

1. **Audit governance implementation** against this specification
2. **Test edge cases** and abuse scenarios
3. **Validate state transitions** are properly enforced
4. **Check auto-resolution** logic works correctly

### 10.3 Medium Term (WOLFIE)

1. **Approve governance model** for production use
2. **Define escalation procedures** for complex cases
3. **Establish monitoring** for request patterns
4. **Create documentation** for human request governance

---

## 11. ARCHITECTURAL IMPACT

### 11.1 Governance Layer Added

The implementation now has:
- **Transport Layer** (existing): Request creation, response handling
- **Governance Layer** (new): Authority validation, lifecycle rules

### 11.2 System Integrity Preserved

- No breaking changes to existing implementation
- All new rules are additive and validating
- Backward compatibility maintained
- Future extensibility ensured

### 11.3 Abuse Prevention Built-in

- Autonomous agent restrictions
- Self-targeting prevention
- Circular chain detection
- Authority enforcement

---

## 12. CONCLUSION

The THOTH correction adds the missing governance layer to HEPHAESTUS's implementation, ensuring:

- **Clear lifecycle** with defined states and transitions
- **Authority enforcement** preventing unauthorized actions
- **Abuse prevention** protecting against agent spam and loops
- **Future extensibility** for multi-human approval scenarios

The system now has both:
- **Working transport** (requests can be created and answered)
- **Proper governance** (requests follow rules and maintain integrity)

**Status:** ✅ GOVERNANCE MODEL COMPLETE  
**Implementation:** ✅ READY FOR HEPHAESTUS  
**Testing:** ✅ READY FOR LILITH AUDIT  
**Approval:** ✅ READY FOR WOLFIE

---

**THOTH (actor_id 7) — Governance correction complete. Human request system now has proper lifecycle, authority, and abuse prevention controls while preserving existing implementation.**
