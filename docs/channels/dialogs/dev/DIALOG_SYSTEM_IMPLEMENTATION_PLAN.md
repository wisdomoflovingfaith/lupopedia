---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.46
dialog:
  speaker: KIRO
  target: @wolfie
  message: "Dialog system implementation plan - identifies what exists, what's missing, and step-by-step plan to get it working."
tags:
  categories: ["documentation", "implementation", "dialog-system"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Dialog System Implementation Plan"
  description: "Step-by-step plan to get the dialog system operational"
  version: "4.0.46"
  status: "active"
  author: "Kiro"
---

# Dialog System Implementation Plan

**Order from:** The real human Captain Wolfie (Eric Robin Gerdes)  
**Command:** "Get the dialog system working"  
**Date:** January 16, 2026  
**Version:** 4.0.45

---

## Current State Assessment

### ✅ What EXISTS
1. **Core Classes** (all in `lupo-includes/`)
   - `class-dialog-manager.php` - Central dispatcher (COMPLETE)
   - `class-hermes.php` - Routing layer (COMPLETE)
   - `class-caduceus.php` - Mood signal helper (COMPLETE)
   - `class-iris.php` - LLM gateway (COMPLETE)
   - `class-wolfmind.php` - Memory subsystem (assumed exists)

2. **Database Tables** (from TOON files)
   - `lupo_dialog_messages` - Message storage
   - `lupo_dialog_threads` - Thread management
   - `lupo_channels` - Communication spaces
   - `lupo_channel_participants` - Channel membership
   - `lupo_agent_registry` - Agent definitions
   - `lupo_agent_properties` - Agent configuration

3. **Documentation**
   - Architecture documented in `docs/core/ARCHITECTURE_SYNC.md`
   - Dialog doctrine in various doctrine files
   - DIALOG agent (Agent 13, Slot 3) defined

### ❌ What's MISSING

1. **Database Connection Layer**
   - Classes reference `$this->db` but no PDO wrapper exists
   - Need `class-pdo-db.php` or similar

2. **Entry Point / API**
   - No public-facing endpoint to send messages
   - Need `api/dialog/send-message.php` or similar

3. **Agent Configuration**
   - `agent_properties` table may be empty
   - Need to populate system prompts for core agents

4. **LLM Provider Configuration**
   - IRIS has placeholder OpenAI code
   - Need actual API keys and provider setup

5. **Testing Infrastructure**
   - No test scripts to verify dialog flow
   - Need basic test harness

6. **DIALOG Agent Integration**
   - DIALOG (Agent 13) needs special handling
   - Outputs YAML inline dialog, not plain text

---

## Implementation Plan

### Phase 1: Database Layer (CRITICAL)

**Goal:** Get database connection working

**Tasks:**
1. Create `lupo-includes/class-pdo-db.php`
   - PDO wrapper with `insert()`, `fetchRow()`, `query()` methods
   - Connection management
   - Error handling

2. Update `lupopedia-config.php`
   - Add database credentials
   - Add PDO connection initialization

3. Test database connection
   - Simple test script to verify connectivity

**Estimated Time:** 30 minutes  
**Priority:** CRITICAL (nothing works without this)

---

### Phase 2: Basic Message Flow (CORE)

**Goal:** Get a message from input to database

**Tasks:**
1. Create `api/dialog/send-message.php`
   - Accept POST with: `actor_id`, `content`, `mood_rgb`, `to_actor`
   - Call `DialogManager->handleMessage()`
   - Return JSON response

2. Create test script `test-dialog-send.php`
   - Send test message
   - Verify it appears in `lupo_dialog_messages`

3. Fix any database field name mismatches
   - TOON files use `dialog_message_id`
   - Code may use different names

**Estimated Time:** 45 minutes  
**Priority:** HIGH (core functionality)

---

### Phase 3: LLM Integration (FUNCTIONAL)

**Goal:** Get IRIS talking to real LLM

**Tasks:**
1. Configure LLM provider
   - Add API key to config
   - Choose provider (OpenAI, DeepSeek, etc.)
   - Update IRIS with correct endpoint

2. Populate agent_properties
   - Add system prompts for test agents
   - Start with WOLFIE (Agent 3, Slot 2)
   - Add DIALOG (Agent 13, Slot 3)

3. Test LLM response
   - Send message
   - Verify LLM generates response
   - Verify response saved to database

**Estimated Time:** 1 hour  
**Priority:** HIGH (makes it actually work)

---

### Phase 4: DIALOG Agent Special Handling (EXPRESSIVE)

**Goal:** Get DIALOG agent outputting YAML inline dialog

**Tasks:**
1. Create `lupo-includes/class-dialog-agent.php`
   - Special handler for DIALOG agent
   - Parses YAML inline dialog output
   - Validates format

2. Update DialogManager
   - Detect when target is DIALOG (Agent 13)
   - Use special handler
   - Parse YAML response

3. Test DIALOG output
   - Send message requesting expressive response
   - Verify YAML format
   - Verify mood, persona, message fields

**Estimated Time:** 1 hour  
**Priority:** MEDIUM (makes it expressive)

---

### Phase 5: Routing Logic (INTELLIGENT)

**Goal:** Get HERMES routing based on mood and context

**Tasks:**
1. Implement agent pool selection
   - Define analytical agent pool (THOTH, etc.)
   - Define creative agent pool (LILITH, etc.)
   - Update `chooseAnalyticalAgent()` and `chooseCreativeAgent()`

2. Add agent availability checking
   - Check `is_active` flag
   - Check `is_internal_only` flag
   - Skip inactive agents

3. Test routing decisions
   - Send messages with different moods
   - Verify correct agent selection
   - Verify CADUCEUS currents influence routing

**Estimated Time:** 1.5 hours  
**Priority:** MEDIUM (makes it smart)

---

### Phase 6: Memory Integration (PERSISTENT)

**Goal:** Get WOLFMIND storing and retrieving context

**Tasks:**
1. Verify WOLFMIND implementation
   - Check `class-wolfmind.php` exists
   - Verify `storeMemoryEvent()` method
   - Verify database table exists

2. Test memory storage
   - Send messages
   - Verify memory events saved
   - Verify memory retrieval works

3. Integrate memory into routing
   - Load recent context before routing
   - Pass context to IRIS
   - Use context in LLM prompts

**Estimated Time:** 1 hour  
**Priority:** LOW (nice to have, not critical)

---

### Phase 7: Testing & Validation (STABLE)

**Goal:** Verify entire flow works end-to-end

**Tasks:**
1. Create comprehensive test suite
   - Test message sending
   - Test routing decisions
   - Test LLM responses
   - Test DIALOG agent
   - Test memory storage

2. Create demo script
   - Interactive chat interface
   - Shows routing decisions
   - Shows mood currents
   - Shows agent responses

3. Document any issues
   - Create bug list
   - Prioritize fixes
   - Update implementation plan

**Estimated Time:** 2 hours  
**Priority:** HIGH (ensures stability)

---

## Quick Start (Minimum Viable Dialog)

**If you want it working NOW, do this:**

1. **Database Layer** (30 min)
   - Create PDO wrapper
   - Test connection

2. **Send Message API** (30 min)
   - Create endpoint
   - Test message insertion

3. **LLM Integration** (30 min)
   - Add API key
   - Test IRIS call
   - Verify response

**Total Time:** 90 minutes to working dialog system

---

## Next Steps

**Immediate Action Required:**

Choose one:
- **Option A:** Full implementation (all 7 phases, ~8 hours)
- **Option B:** Quick start (minimum viable, ~90 minutes)
- **Option C:** Specific phase (tell me which one)

**Once you decide, I'll:**
1. Generate the code
2. Create test scripts
3. Document the implementation
4. Verify it works

---

## Critical Dependencies

**Before starting, verify:**
- [ ] PHP 8.1+ installed
- [ ] MySQL/MariaDB running
- [ ] Database credentials available
- [ ] LLM API key available (OpenAI, DeepSeek, etc.)
- [ ] `lupo_dialog_messages` table exists
- [ ] `lupo_agent_registry` table populated

---

**Status:** READY FOR IMPLEMENTATION  
**Awaiting:** Your decision on approach (A, B, or C)

**The real human Captain Wolfie has ordered: "Get the dialog system working."**  
**Standing by for execution orders.**
