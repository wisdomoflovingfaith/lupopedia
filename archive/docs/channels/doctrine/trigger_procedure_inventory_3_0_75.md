# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/doctrine/TRIGGER_PROCEDURE_INVENTORY_3_0_75.md"
  file_hash: "1f852fc6b1315e56f4f06e677ac3431f25d7ef20fe1cbc4e2498dd5f4a9b98d0"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\doctrine\TRIGGER_PROCEDURE_INVENTORY_3_0_75.md"
  file_hash: "22d1825974db39442192b593f3e7bd45cba19ab439d1ef23d5c36ab67497be0d"
  file_path_from_root: "docs\channels\doctrine\TRIGGER_PROCEDURE_INVENTORY_3_0_75.md"
  file_hash: "f5fa6bf52730c2590212377802b6b11d2726540f58779e9815251655c922b6d0"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for TRIGGER_PROCEDURE_INVENTORY_3_0_75.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "trigger_procedure_inventory_3_0_75md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.78
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CASCADE
  target: @everyone
  mood_RGB: "FF0000"
  message: "TRIGGER AND PROCEDURE INVENTORY - Identification and classification of all database-embedded logic violating NO_TRIGGERS/NO_PROCEDURES doctrine. All logic must be extracted to application code."
tags:
  categories: ["doctrine", "inventory", "triggers", "procedures", "violation"]
  collections: ["doctrine", "governance"]
  channels: ["doctrine", "governance"]
file:
  title: "Trigger and Procedure Inventory - Version 3.0.75"
  description: "Inventory and classification of all database-embedded logic violating doctrine"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: active
  author: GLOBAL_CURRENT_AUTHORS
---

# TRIGGER AND PROCEDURE INVENTORY - VERSION 3.0.75

## 🚨 DOCTRINE VIOLATION SUMMARY

**Fleet Directive**: NO TRIGGERS, NO STORED PROCEDURES, NO DATABASE-EMBEDDED LOGIC  
**Current Status**: 4 triggers identified, 0 procedures identified  
**Compliance Status**: VIOLATION - All logic must be extracted to application code

---

## 📋 INVENTORY CLASSIFICATION

### 🚨 CRITICAL TRIGGERS (Must be extracted to code immediately)

#### 1. Dialog Message Count Triggers
**File**: `database/schema/dialog_system_schema.sql`  
**Triggers**: 
- `tr_dialog_messages_insert` (AFTER INSERT)
- `tr_dialog_messages_delete` (AFTER DELETE)

**Classification**: 🚨 CRITICAL  
**Reason**: Core business logic embedded in database triggers  
**Impact**: Message count updates and timestamp management  
**Extraction Priority**: IMMEDIATE

**Current Logic**:
```sql
-- INSERT trigger updates message count and timestamp
UPDATE lupo_dialog_channels 
SET message_count = (SELECT COUNT(*) FROM lupo_dialog_messages WHERE channel_id = NEW.channel_id),
    modified_timestamp = UNIX_TIMESTAMP() * 1000000 + MICROSECOND(NOW(6))
WHERE channel_id = NEW.channel_id;

-- DELETE trigger updates message count and timestamp  
UPDATE lupo_dialog_channels 
SET message_count = (SELECT COUNT(*) FROM lupo_dialog_messages WHERE channel_id = OLD.channel_id),
    modified_timestamp = UNIX_TIMESTAMP() * 1000000 + MICROSECOND(NOW(6))
WHERE channel_id = OLD.channel_id;
```

**Extraction Plan**:
- Move message count updates to application layer
- Move timestamp management to application layer
- Implement in DialogMessageManager class
- Add message count caching for performance

#### 2. Protocol Enforcement Trigger
**File**: `database/migrations/multi_agent_protocol_schema_3_0_70.sql`  
**Trigger**: `tr_enforce_protocol_completion` (BEFORE INSERT)

**Classification**: 🚨 CRITICAL  
**Reason**: Multi-agent protocol enforcement logic embedded in database  
**Impact**: Communication blocking before protocol completion  
**Extraction Priority**: IMMEDIATE

**Current Logic**:
```sql
-- Check protocol completion before allowing communication
DECLARE protocol_status VARCHAR(20);
SELECT protocol_completion_status INTO protocol_status
FROM lupo_actor_channel_roles 
WHERE actor_id = NEW.speaker AND channel_id = NEW.channel_id;

-- Enforce invariant: No communication before protocol completion
IF protocol_status IS NULL OR protocol_status != 'ready' THEN
    SIGNAL SQLSTATE '45000' 
    SET MESSAGE_TEXT = 'PROTOCOL VIOLATION: Agent must complete AAL + RSHAP + CJP before communication';
END IF;
```

**Extraction Plan**:
- Move protocol validation to application layer
- Implement in AgentAwarenessLayer class
- Add pre-insert validation in DialogMessageManager
- Maintain same enforcement behavior in application code

---

## 📊 EXTRACTED LOGIC REQUIREMENTS

### Application Code Implementation

#### 1. DialogMessageManager Enhancement
**File**: `includes/classes/DialogMessageManager.php`  
**Required Methods**:
- `updateChannelMessageCount($channel_id)` - Update message count
- `updateChannelTimestamp($channel_id)` - Update modified timestamp
- `validateProtocolCompletion($actor_id, $channel_id)` - Validate protocol completion
- `preInsertValidation($message_data)` - Pre-insert validation

#### 2. AgentAwarenessLayer Enhancement  
**File**: `includes/classes/AgentAwarenessLayer.php`  
**Required Methods**:
- `checkCommunicationPermission($actor_id, $channel_id)` - Check communication permission
- `validateProtocolStatus($actor_id, $channel_id)` - Validate protocol status
- `enforceProtocolCompletion($actor_id, $channel_id)` - Enforce protocol completion

#### 3. Database Transaction Management
**Required Changes**:
- Wrap message insertion with protocol validation
- Ensure atomic operations for message count updates
- Implement proper error handling and rollback
- Maintain data consistency during extraction

---

## 🔧 EXTRACTION PLAN

### Phase 1: Critical Logic Extraction (IMMEDIATE)
**Timeline**: Within 24 hours  
**Priority**: CRITICAL  

**Tasks**:
1. Create enhanced DialogMessageManager class
2. Create enhanced AgentAwarenessLayer class  
3. Implement protocol validation in application code
4. Implement message count updates in application code
5. Test extracted logic thoroughly

### Phase 2: Trigger Removal (IMMEDIATE)
**Timeline**: Within 48 hours  
**Priority**: CRITICAL  

**Tasks**:
1. Create migration to drop all identified triggers
2. Test system without triggers
3. Verify all functionality works with application code
4. Deploy trigger removal migration

### Phase 3: Validation and Testing (IMMEDIATE)
**Timeline**: Within 72 hours  
**Priority**: CRITICAL  

**Tasks**:
1. Comprehensive testing of extracted logic
2. Performance testing of application code
3. Integration testing with multi-agent protocols
4. Production readiness validation

---

## 📋 EXTRACTION CHECKLIST

### ✅ Pre-Extraction Requirements
- [ ] Application code classes created and tested
- [ ] All trigger logic replicated in application code
- [ ] Performance benchmarks established
- [ ] Rollback plan prepared
- [ ] Testing environment validated

### ✅ Extraction Execution Requirements  
- [ ] Triggers disabled (not dropped yet)
- [ ] Application code deployed and tested
- [ ] All functionality verified with application code
- [ ] Performance metrics validated
- [ ] Error handling tested

### ✅ Post-Extraction Requirements
- [ ] Triggers dropped permanently
- [ ] System performance monitored
- [ ] All functionality validated
- [ ] Documentation updated
- [ ] Doctrine compliance verified

---

## 🚨 COMPLIANCE STATUS

### Current Doctrine Violations
- **Triggers**: 4 identified (all CRITICAL)
- **Procedures**: 0 identified
- **Database-Embedded Logic**: 4 violations
- **Application Code Logic**: 0 (needs implementation)

### Target Compliance State
- **Triggers**: 0 (all extracted to application code)
- **Procedures**: 0 (none exist)
- **Database-Embedded Logic**: 0 (all in application code)
- **Application Code Logic**: 4 (all extracted logic)

### Compliance Timeline
- **Day 1**: Application code implementation
- **Day 2**: Trigger removal and testing
- **Day 3**: Final validation and deployment

---

## 🎯 GOVERNANCE IMPACT

### Table Governance Protocol
- **111-Table Rule**: Maintained during extraction
- **Schema Compliance**: Enhanced through application code
- **Change Tracking**: Improved through application logging
- **Invariant Enforcement**: Enhanced through application validation

### CIP Invariants
- **No Critique Discarded**: Maintained through application code
- **Defensiveness Never Blocks**: Enhanced through application logic
- **All Critique Recorded**: Improved through application logging
- **Doctrine Updates Reflect Integration**: Enhanced through application control

### Multi-Agent Coordination
- **Protocol Enforcement**: Enhanced through application validation
- **Communication Blocking**: Maintained through application code
- **Fleet Synchronization**: Enhanced through application coordination
- **Trust Management**: Enhanced through application logic

---

## 📈 QUALITY ASSURANCE

### Testing Requirements
- **Unit Tests**: All extracted logic unit tested
- **Integration Tests**: Multi-agent coordination tested
- **Performance Tests**: Application code performance validated
- **Regression Tests**: All existing functionality tested

### Performance Considerations
- **Message Count Updates**: Optimize with caching
- **Protocol Validation**: Optimize with efficient queries
- **Database Transactions**: Optimize for atomicity
- **Error Handling**: Optimize for reliability

### Monitoring Requirements
- **Application Performance**: Monitor extracted logic performance
- **Database Performance**: Monitor without triggers
- **Error Rates**: Monitor application error rates
- **User Experience**: Monitor system responsiveness

---

## 🚀 NEXT STEPS

### Immediate Actions (Next 24 Hours)
1. Implement DialogMessageManager enhancements
2. Implement AgentAwarenessLayer enhancements
3. Create comprehensive test suite
4. Validate extracted logic functionality

### Short-term Actions (Next 48 Hours)
1. Deploy application code changes
2. Disable triggers (not drop yet)
3. Test system with application code only
4. Validate all functionality

### Medium-term Actions (Next 72 Hours)
1. Drop all identified triggers
2. Monitor system performance
3. Validate doctrine compliance
4. Update documentation

---

## 🎯 CONCLUSION

### Critical Assessment
The identification of 4 CRITICAL triggers represents a significant doctrine violation that requires immediate attention. All business logic must be extracted from the database layer to maintain compliance with the NO_TRIGGERS/NO_PROCEDURES doctrine.

### Extraction Success Criteria
- All trigger logic successfully extracted to application code
- System functionality maintained or enhanced
- Performance maintained or improved
- Doctrine compliance achieved
- Multi-agent coordination preserved

### Strategic Impact
This extraction will strengthen the system architecture by:
- Improving maintainability through centralized logic
- Enhancing testability through application code testing
- Increasing flexibility through application-controlled behavior
- Strengthening governance through application-layer enforcement

---

**Inventory Status**: 🚨 CRITICAL - 4 triggers identified, all requiring immediate extraction to application code to achieve doctrine compliance.
