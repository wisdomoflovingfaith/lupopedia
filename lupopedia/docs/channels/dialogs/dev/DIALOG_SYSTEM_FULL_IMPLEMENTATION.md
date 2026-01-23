---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.46
dialog:
  speaker: CURSOR
  target: @wolfie
  message: "Full Implementation plan for dialog system - all 7 phases documented and ready for execution."
tags:
  categories: ["documentation", "implementation", "dialog-system"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Dialog System Full Implementation"
  description: "Complete implementation plan and execution status for dialog system"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: active
  author: GLOBAL_CURRENT_AUTHORS
---

# Dialog System Full Implementation

**Status:** IN PROGRESS  
**Started:** 2026-01-16  
**Approach:** Full Implementation (All 7 Phases)  
**Estimated Time:** ~8 hours

---

## Current Status

### ✅ Phase 1: Database Layer - COMPLETE
- PDO_DB class exists (`lupo-includes/class-pdo_db.php`)
- Database connection established in `bootstrap.php`
- `getPdo()` method available
- **Action Required:** Fix DialogManager table/field names

### ⏳ Phase 2: Basic Message Flow - IN PROGRESS
- Need to create API endpoint
- Need to fix DialogManager field mappings
- Need test script

### ⏳ Phase 3: LLM Integration - PENDING
- Need API key configuration
- Need agent_properties population
- Need IRIS provider setup

### ⏳ Phase 4: DIALOG Agent Special Handling - PENDING
- Need YAML parser
- Need DIALOG agent handler

### ⏳ Phase 5: Routing Logic - PENDING
- Need agent pool selection
- Need availability checking

### ⏳ Phase 6: Memory Integration - PENDING
- Need WOLFMIND verification
- Need memory integration

### ⏳ Phase 7: Testing & Validation - PENDING
- Need test suite
- Need demo script

---

## Implementation Checklist

### Phase 1: Database Layer ✅
- [x] PDO_DB class exists
- [x] Database connection works
- [ ] Fix DialogManager table name (`lupo_dialog_messages`)
- [ ] Fix DialogManager field names (from_actor_id, to_actor_id, message_text, dialog_thread_id)

### Phase 2: Basic Message Flow
- [ ] Create `api/dialog/send-message.php`
- [ ] Fix DialogManager field mappings
- [ ] Create test script `test-dialog-send.php`
- [ ] Verify message insertion

### Phase 3: LLM Integration
- [ ] Add API key to config
- [ ] Configure provider (OpenAI/DeepSeek)
- [ ] Populate agent_properties with system prompts
- [ ] Update IRIS with provider endpoint
- [ ] Test LLM response

### Phase 4: DIALOG Agent Special Handling
- [ ] Create `class-dialog-agent.php`
- [ ] Implement YAML parser
- [ ] Update DialogManager for DIALOG agent
- [ ] Test YAML output

### Phase 5: Routing Logic
- [ ] Implement agent pool selection
- [ ] Add availability checking
- [ ] Update HERMES routing
- [ ] Test routing decisions

### Phase 6: Memory Integration
- [ ] Verify WOLFMIND exists
- [ ] Test memory storage
- [ ] Integrate memory into routing

### Phase 7: Testing & Validation
- [ ] Create test suite
- [ ] Create demo script
- [ ] Document issues
- [ ] Verify end-to-end flow

---

## Table/Field Mapping Fixes Required

**Current DialogManager uses:**
- Table: `dialog_messages` ❌
- Fields: `actor_id`, `to_actor`, `content`, `thread_id` ❌

**Actual database schema:**
- Table: `lupo_dialog_messages` ✅
- Fields: `from_actor_id`, `to_actor_id`, `message_text`, `dialog_thread_id` ✅

**Fix Required:**
```php
// Change table name
$this->db->insert('lupo_dialog_messages', $data);

// Change field mappings
$data = [
    'from_actor_id' => $packet['actor_id'],
    'to_actor_id' => $packet['to_actor'] ?? null,
    'message_text' => $packet['content'],
    'dialog_thread_id' => $packet['thread_id'] ?? null,
    'mood_rgb' => $packet['mood_rgb'] ?? '666666',
    'created_ymdhis' => $now,
    'updated_ymdhis' => $now,
    'message_type' => 'text',
    'is_deleted' => 0
];
```

---

## Next Steps

1. Fix DialogManager table/field names
2. Create API endpoint
3. Test basic message flow
4. Configure LLM provider
5. Continue through remaining phases

---

**Last Updated:** 2026-01-16  
**Status:** Implementation in progress
