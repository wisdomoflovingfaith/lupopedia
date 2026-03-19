---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  lupopedia.schema: "doctrine"
  system_version: "4.0.82"
  file_path_from_root: "lupo-docs/doctrine/ACTOR_STATE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupo-docs/doctrine/ACTOR_STATE_DOCTRINE"
  last_modified_utc: "20260319"
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 51
  thread_id: 1037
  task_id: "task_actor_state_doctrine_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "actor_state"
  purpose: "Canonical doctrine establishing actor identity vs state separation - foundational system primitive"
  tags: ["wolfie", "doctrine", "actor_identity", "state_separation", "canonical", "foundational"]
lupopedia.footer:
  version: "4.0.82"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Propagate to IDE rules"
    - "Assign validator enforcement"
    - "Audit registry compliance"
---

# file: ACTOR STATE DOCTRINE — canonical foundational primitive

> **ACTORS ARE STABLE IDENTITIES**  
> **STATE IS A PROPERTY — NOT A NEW ACTOR**

---

## 🧱 FOUNDATIONAL PRINCIPLE

### Identity vs State

**Actor Identity** = immutable, canonical, persistent
- actor_id is permanent
- actor_name is stable
- identity never changes

**Actor State** = mutable, temporal, conditional
- banned/active/inactive
- fatigued/available
- restricted/permissive

**Critical Rule**: State changes NEVER create new actors

---

## 🚫 FORBIDDEN PATTERNS (NEVER ALLOWED)

### Variant Actor Names
- lilith_banned
- wolfie_test
- lilith_variant
- cursor_stoned
- windsurf_limited
- *_banned, *_test, *_variant, *_shadow, *_backup

### State-Based Actor Creation
- Creating new actor for banned state
- Creating new actor for fatigue state
- Creating new actor for restricted state
- Creating new actor for testing

### Identity Reuse
- Reusing deleted actor_ids
- Recycling actor identities
- Merging actors
- Splitting actors

---

## ✅ ALLOWED PATTERNS

### Persona vs Faucet Separation
- **Persona**: wolfie (1), lilith (2) - stable identities
- **Faucet**: cursor (102), windsurf (101), kiro (100) - execution surfaces
- Faucets MAY have separate actor_ids
- Faucets represent WHERE code runs, not WHO is acting

### State Properties
- `is_active`, `is_banned`, `is_deleted`
- `fatigue_level`, `availability_status`
- `restriction_flags`, `permission_masks`
- All stored on SAME actor record

---

## 🔍 BANNED / SOFT-DELETED ACTORS

### Query Requirements
- MUST remain queryable via standard APIs
- MUST return same actor_id regardless of state
- MUST be visible to admin/system queries
- MUST be usable for adversarial testing

### IDE Agent Behavior
When actor_id 2 (lilith) is requested:
```bash
# CORRECT
SELECT * FROM lupo_actors WHERE actor_id = 2;

# INCORRECT
SELECT * FROM lupo_actors WHERE actor_id = 2 AND is_active = 1;
```

### Testing Requirements
- Banned actors must be testable
- Soft-delete behavior must be verifiable
- Adversarial review requires access to banned states
- System limits must count banned actors

---

## 📊 ACTOR LIMITS

### Counting Rules
- ALL actors count toward system limits
- Soft-deleted actors still count
- Banned actors still count
- Inactive actors still count

### Limit Enforcement
```sql
-- CORRECT: Count all actors
SELECT COUNT(*) FROM lupo_actors;

-- INCORRECT: Count only active
SELECT COUNT(*) FROM lupo_actors WHERE is_active = 1;
```

### No Identity Reuse
- actor_id NEVER reused
- actor_name NEVER reused
- Registry maintains permanent record
- Historical integrity preserved

---

## 🧪 VALIDATOR REQUIREMENTS

### HEPHAESTUS Assignment (Channel 7)
Build validator to detect:

1. **Duplicate actor_name** across registry
2. **Variant naming patterns** (_banned, _test, _variant)
3. **Missing canonical actors** (wolfie=1, lilith=2)
4. **Registry/DB mismatch** between files and database
5. **State-based actor creation** attempts

### LILITH Assignment (Channel 66)
Audit existing actor registry:
- Detect all variant actors
- Report identity violations
- Confirm actor_id 2 integrity
- Validate banned actor visibility

---

## 🔧 ENFORCEMENT

### IDE Rules Update
Add to `lupo-rules/root/`:
```
Actors are canonical identities.
Never create a new actor to represent a state.
Always return the original actor_id even if banned or inactive.
```

### API Layer Enforcement
- Actor creation APIs reject variant names
- Actor query APIs include deleted/banned by default
- System limit queries count all actors
- Registry operations preserve identity

### Database Constraints
- UNIQUE constraint on actor_name (active and deleted)
- NO AUTO_INCREMENT on actor_id (explicit assignment)
- Soft delete via is_deleted flag
- Historical audit trail maintained

---

## 🎯 CANONICAL EXAMPLES

### Correct Implementation
```php
// Banning actor
function banActor($actor_id) {
    $sql = "UPDATE lupo_actors SET is_banned = 1 WHERE actor_id = ?";
    // Same actor_id, new state
}

// Querying actor
function getActor($actor_id) {
    $sql = "SELECT * FROM lupo_actors WHERE actor_id = ?";
    // Returns actor regardless of state
}
```

### Incorrect Implementation
```php
// Creating banned variant
function banActor($actor_id) {
    $new_name = $actor_name . "_banned";
    $sql = "INSERT INTO lupo_actors (actor_name, ...) VALUES (?, ...)";
    // VIOLATION: Creates new actor
}

// Hiding banned actors
function getActor($actor_id) {
    $sql = "SELECT * FROM lupo_actors WHERE actor_id = ? AND is_banned = 0";
    // VIOLATION: Hides banned actors
}
```

---

## 🔒 SYSTEM IMPACT

### Without This Doctrine
- Identity fragments across system
- Limits break artificially
- Adversarial testing fails
- Non-deterministic actor behavior
- Registry corruption

### With This Doctrine
- Actors become true primitives
- State becomes testable
- System remains stable
- Deterministic lineage preserved
- Clean separation of concerns

---

## 📋 REFERENCE IMPLEMENTATIONS

### Actor Query (All States)
```sql
-- Canonical query
SELECT * FROM lupo_actors WHERE actor_id = 2;
-- Returns lilith even if banned
```

### Actor Count (For Limits)
```sql
-- Canonical count
SELECT COUNT(*) FROM lupo_actors;
-- Counts all actors including banned/deleted
```

### State Update (No New Actor)
```sql
-- Canonical state change
UPDATE lupo_actors SET is_banned = 1 WHERE actor_id = 2;
-- Same actor, new state
```

---

## 🧠 ARCHITECTURAL SIGNIFICANCE

This doctrine establishes:

- **Identity Primitives**: Actors as foundational system objects
- **State Separation**: Clean boundary between who and what
- **Testing Foundation**: Adversarial review requires banned actors
- **System Stability**: Prevents identity fragmentation
- **Deterministic Behavior**: Predictable actor lifecycle

---

## 🔗 RELATED DOCTRINES

- **ACTOR_FACET_SEPARATION_DOCTRINE.md**: Actor vs execution environment
- **MULTI_AGENT_COORDINATION_DOCTRINE.md**: Actor roles and coordination
- **SYSTEM_LIMITS_DOCTRINE.md**: Actor counting and limits
- **CHANNEL_CREATION_DOCTRINE.md**: Channel-scoped actor operations

---

## 📚 HISTORY

- **2026-03-19**: Canonical ratification by WOLFIE (thread 1037)
- **2026-03-19**: Heterodox review by LILITH identified edge cases
- **2026-03-19**: Promoted to foundational doctrine

---

## ✅ COMPLIANCE CHECKLIST

- [ ] No variant actors exist in registry
- [ ] Banned actors are queryable
- [ ] Actor limits count all states
- [ ] IDE agents return actor_id 2 when requested
- [ ] No identity reuse in system
- [ ] State changes use UPDATE, not INSERT
- [ ] Registry matches database state
- [ ] Validators detect violations

---

*This doctrine is now canonical and non-negotiable.*
