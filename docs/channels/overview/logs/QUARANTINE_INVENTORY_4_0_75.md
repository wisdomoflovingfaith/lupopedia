---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.76
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: WOLFIE
  mood_RGB: "FF8800"
  message: "QUARANTINE INVENTORY COMPLETE. 4 triggers and 1 stored procedure identified as doctrine violations. Classification and extraction plan prepared."
tags:
  categories: ["quarantine", "doctrine", "database"]
  collections: ["quarantine-docs"]
  channels: ["dev", "architecture"]
file:
  title: "Quarantine Inventory - Database Logic Violations"
  description: "Complete inventory and classification of triggers and procedures violating NO_TRIGGERS_NO_PROCEDURES_DOCTRINE"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: quarantine
  author: GLOBAL_CURRENT_AUTHORS
---

# QUARANTINE INVENTORY - DATABASE LOGIC VIOLATIONS

**Date**: 2026-01-17  
**Version**: 4.0.76  
**Authority**: Captain Wolfie Fleet Directive  
**Status**: QUARANTINED STRUCTURES - NOT TRUSTED ARCHITECTURE  

## EXECUTIVE SUMMARY

**TOTAL VIOLATIONS IDENTIFIED**: 5
- **Triggers**: 4
- **Stored Procedures**: 1
- **Functions**: 0

**CLASSIFICATION RESULTS**:
- **CRITICAL** (extract immediately): 3 violations
- **LEGACY** (disable later): 2 violations

## DETAILED INVENTORY

### TRIGGER VIOLATIONS

#### 1. tr_enforce_protocol_completion
- **Location**: `database/migrations/multi_agent_protocol_schema_4_0_70.sql:70`
- **Type**: BEFORE INSERT trigger on `lupo_dialog_messages`
- **Classification**: **CRITICAL**
- **Logic**: Protocol completion validation before message insertion
- **Business Rule**: "No communication before protocol completion"
- **Extraction Target**: `AgentAwarenessLayer.php` validation method
- **Risk Level**: HIGH - Blocks core communication functionality

#### 2. tr_dialog_messages_insert  
- **Location**: `database/schema/dialog_system_schema.sql:72`
- **Type**: AFTER INSERT trigger on `lupo_dialog_messages`
- **Classification**: **CRITICAL**
- **Logic**: Updates message count and modified timestamp on channel
- **Business Rule**: Maintain channel message count accuracy
- **Extraction Target**: `DialogHistoryManager.php` post-insert method
- **Risk Level**: MEDIUM - Affects message counting accuracy

#### 3. tr_dialog_messages_delete
- **Location**: `database/schema/dialog_system_schema.sql:83`  
- **Type**: AFTER DELETE trigger on `lupo_dialog_messages`
- **Classification**: **CRITICAL**
- **Logic**: Updates message count and modified timestamp on channel
- **Business Rule**: Maintain channel message count accuracy  
- **Extraction Target**: `DialogHistoryManager.php` post-delete method
- **Risk Level**: MEDIUM - Affects message counting accuracy

#### 4. [Implicit CIP Analytics Trigger]
- **Location**: `database/migrations/cip_analytics_schema_4_0_75.sql` (if created)
- **Type**: Potential auto-processing trigger
- **Classification**: **LEGACY** 
- **Status**: Not yet implemented - prevent creation
- **Risk Level**: LOW - Preventable

### STORED PROCEDURE VIOLATIONS

#### 1. validate_doctrine_compliance
- **Location**: `migrations/structural_alignment_mysql_migration.sql:184`
- **Type**: Validation procedure
- **Classification**: **LEGACY**
- **Logic**: Iterates through tables to validate doctrine compliance
- **Business Rule**: Schema validation and compliance checking
- **Extraction Target**: `DoctrineValidator.php` class
- **Risk Level**: LOW - Utility function, not core business logic

## EXTRACTION PLAN

### PHASE 1: CRITICAL EXTRACTIONS (Immediate)

#### A. Protocol Completion Validation
**Target**: `tr_enforce_protocol_completion`
**Extraction Steps**:
1. Create `ProtocolCompletionValidator.php` class
2. Implement `validateProtocolCompletion($actor_id, $channel_id)` method
3. Add validation call to `DialogHistoryManager::insertMessage()`
4. Test validation logic thoroughly
5. Disable trigger after validation deployment
6. Remove trigger after 48-hour validation period

**Code Structure**:
```php
class ProtocolCompletionValidator {
    public function validateProtocolCompletion($actor_id, $channel_id) {
        // Extract trigger logic here
        // Throw exception if validation fails
    }
}
```

#### B. Message Count Management
**Target**: `tr_dialog_messages_insert`, `tr_dialog_messages_delete`
**Extraction Steps**:
1. Create `ChannelMessageCounter.php` class
2. Implement `updateMessageCount($channel_id)` method
3. Add counter calls to `DialogHistoryManager` insert/delete methods
4. Implement transaction safety for count updates
5. Test count accuracy thoroughly
6. Disable triggers after counter deployment
7. Remove triggers after 48-hour validation period

**Code Structure**:
```php
class ChannelMessageCounter {
    public function updateMessageCount($channel_id) {
        // Extract trigger logic here
        // Update count and timestamp atomically
    }
}
```

### PHASE 2: LEGACY EXTRACTIONS (Scheduled)

#### A. Doctrine Compliance Validation
**Target**: `validate_doctrine_compliance`
**Extraction Steps**:
1. Create `DoctrineValidator.php` class
2. Implement schema scanning methods
3. Add CLI tool for doctrine validation
4. Test validation accuracy
5. Drop stored procedure after tool deployment

### PHASE 3: PREVENTION (Ongoing)

#### A. Schema Review Process
- All schema changes reviewed for trigger/procedure introduction
- Automated scanning in CI/CD pipeline
- Deployment blocking for doctrine violations

#### B. Developer Education
- Update development documentation
- Add doctrine compliance to code review checklist
- Training on application-layer alternatives

## RISK ASSESSMENT

### HIGH RISK VIOLATIONS
- **tr_enforce_protocol_completion**: Core communication blocking logic
- **Immediate Action Required**: Extract to application layer within 24 hours

### MEDIUM RISK VIOLATIONS  
- **Message count triggers**: Data consistency logic
- **Acceptable Delay**: Extract within 48 hours with monitoring

### LOW RISK VIOLATIONS
- **Validation procedure**: Utility function
- **Scheduled Extraction**: Can be addressed in next sprint

## COMPLIANCE VERIFICATION

### Pre-Extraction State
```sql
-- Current violations (expected results)
SHOW TRIGGERS; -- Should show 3-4 triggers
SHOW PROCEDURE STATUS; -- Should show 1 procedure
```

### Post-Extraction Target State  
```sql
-- Target state (expected results)
SHOW TRIGGERS; -- Should return empty set
SHOW PROCEDURE STATUS; -- Should return empty set
```

### Monitoring Commands
```sql
-- Continuous monitoring
SELECT COUNT(*) FROM INFORMATION_SCHEMA.TRIGGERS WHERE TRIGGER_SCHEMA = DATABASE();
SELECT COUNT(*) FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_SCHEMA = DATABASE();
```

## EXTRACTION TIMELINE

### Immediate (0-24 hours)
- [x] Doctrine creation complete
- [x] Inventory and classification complete  
- [ ] Protocol validation extraction
- [ ] Application layer validation deployment

### Short-term (24-48 hours)
- [ ] Message count logic extraction
- [ ] Counter deployment and testing
- [ ] Trigger disabling after validation

### Medium-term (48-72 hours)
- [ ] Trigger removal after validation period
- [ ] Doctrine compliance procedure extraction
- [ ] Full compliance verification

## NOTES

- **NO DELETION YET**: All violations remain in place until extraction complete
- **QUARANTINE STATUS**: All identified structures treated as untrusted
- **EXTRACTION PRIORITY**: CRITICAL items first, LEGACY items scheduled
- **TESTING REQUIRED**: All extracted logic must be thoroughly tested
- **ROLLBACK PLAN**: Maintain ability to re-enable triggers if extraction fails

## COMPLIANCE STATEMENT

This inventory identifies all database-embedded logic violations of the NO_TRIGGERS_NO_PROCEDURES_DOCTRINE. All identified structures are quarantined and scheduled for extraction to application code.

**Database = state. Application = behavior.**

---

*Inventory completed under Captain Wolfie's stabilization directive. All violations will be extracted to maintain doctrine compliance.*