---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/database_constraints.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/database_constraints.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: constraints
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# Database Constraints — AUTO_INCREMENT Remediation Plan

## 🚨 CRITICAL BLOCKER IDENTIFIED

**Issue**: Install SQL contains multiple `AUTO_INCREMENT` definitions that conflict with deterministic-ID doctrine for canonical 4.1.0 model.

**Evidence**: Lines 74, 718, 1393, and others in `install_new_lupopedia.sql`

## 📋 AUTO_INCREMENT INVENTORY

### Tables with AUTO_INCREMENT Conflicts

| Table | Line(s) | Current Definition | Doctrine Conflict | Priority |
|--------|-----------|-------------------|------------------|----------|
| `lupo_bans_log` | 74 | `bans_log_id bigint NOT NULL AUTO_INCREMENT` | **HIGH** - Log table should use deterministic IDs |
| `lupo_visits` | 718 | `visit_id bigint NOT NULL AUTO_INCREMENT` | **HIGH** - High-volume log table |
| `lupo_anubis_queue` | 800 | `queue_id bigint NOT NULL AUTO_INCREMENT` | **HIGH** - ANUBIS operations queue |
| `lupo_anubis_processing_log` | 826 | `log_id bigint NOT NULL AUTO_INCREMENT` | **HIGH** - Processing log |
| `lupo_anubis_recovery_attempts` | 839 | `attempt_id bigint NOT NULL AUTO_INCREMENT` | **MEDIUM** - Recovery tracking |
| `lupo_anubis_quarantine` | 853 | `quarantine_id bigint NOT NULL AUTO_INCREMENT` | **HIGH** - Quarantine operations |
| `lupo_unified_log` | 1054 | `log_id bigint NOT NULL AUTO_INCREMENT` | **HIGH** - Unified logging |
| `lupo_auth_users` | 1155 | `auth_user_id is NOT AUTO_INCREMENT` | **CRITICAL** - Auth user table |
| `lupo_channel_boot_lifecycle` | 1224 | `lifecycle_id bigint NOT NULL AUTO_INCREMENT` | **MEDIUM** - Channel lifecycle |
| `lupo_channel_boot_detail_lifecycle` | 1249 | `detail_lifecycle_id bigint NOT NULL AUTO_INCREMENT` | **MEDIUM** - Lifecycle details |
| `lupo_collections` | 1393 | `collection_id bigint NOT NULL AUTO_INCREMENT` | **HIGH** - Collections table |
| `lupo_collection_tabs` | 1425 | `collection_tab_id bigint NOT NULL AUTO_INCREMENT` | **MEDIUM** - UI tabs |
| `lupo_crafty_user_mapping` | 1614 | `crafty_user_mapping_id bigint NOT NULL auto_increment` | **LOW** - Legacy mapping |
| `lupo_crafty_syntax_leave_message` | 1631 | `crafty_syntax_leave_message_id bigint NOT NULL auto_increment` | **LOW** - Legacy table |
| `lupo_crafty_syntax_layer_invites` | 1661 | `crafty_syntax_layer_invite_id bigint NOT NULL auto_increment` | **LOW** - Legacy table |
| `lupo_crafty_syntax_chat_questions` | 1685 | `crafty_syntax_chat_question_id bigint NOT NULL auto_increment` | **LOW** - Legacy table |
| `lupo_crafty_syntax_chat_mod_departments` | 1704 | `crafty_syntax_chat_mod_department_id bigint NOT NULL auto_increment` | **LOW** - Legacy table |
| `lupo_crafty_syntax_auto_invite` | 1714 | `crafty_syntax_auto_invite_id bigint NOT NULL auto_increment` | **LOW** - Legacy table |
| `lupo_department_metadata` | 1866 | `department_metadata_id bigint NOT NULL AUTO_INCREMENT` | **MEDIUM** - Department metadata |

**Total**: 19 tables with AUTO_INCREMENT conflicts

## 🎯 DETERMINISTIC ID CONVERSION PLAN

### Phase 1: High-Priority Tables (Immediate)

**Target Tables**: `lupo_auth_users`, `lupo_bans_log`, `lupo_visits`, `lupo_anubis_queue`, `lupo_anubis_processing_log`, `lupo_anubis_quarantine`, `lupo_unified_log`, `lupo_collections`

**Conversion Strategy**:
1. **Replace AUTO_INCREMENT with deterministic ID generation**
2. **Use application-supplied IDs via ID generation service**
3. **Maintain backward compatibility for existing data**

**Implementation Approach**:
```sql
-- Before (conflicting):
CREATE TABLE lupo_auth_users (
  auth_user_id bigint NOT NULL AUTO_INCREMENT,
  ...
);

-- After (deterministic):
CREATE TABLE lupo_auth_users (
  auth_user_id bigint NOT NULL,
  ...
);
```

### Phase 2: Medium-Priority Tables (Secondary)

**Target Tables**: `lupo_channel_boot_lifecycle`, `lupo_channel_boot_detail_lifecycle`, `lupo_department_metadata`, `lupo_collection_tabs`

**Conversion Strategy**: Same as Phase 1, but can be done incrementally.

### Phase 3: Legacy Tables (Optional)

**Target Tables**: All `lupo_crafty_syntax_*` tables and `lupo_crafty_user_mapping`

**Conversion Strategy**: These are legacy Crafty Syntax tables; consider deprecation rather than conversion.

## 🔧 TECHNICAL IMPLEMENTATION

### Deterministic ID Generation Service

**Requirements**:
- Generate unique BIGINT IDs without AUTO_INCREMENT
- Ensure no collisions across tables
- Maintain performance for high-volume tables
- Provide rollback capability

**Proposed Implementation**:
```php
class DeterministicIdService {
    private $tableCounters = [];
    
    public function generateId(string $tableName): bigint {
        if (!isset($this->tableCounters[$tableName])) {
            $this->tableCounters[$tableName] = $this->getLastId($tableName) ?? 0;
        }
        
        $this->tableCounters[$tableName]++;
        
        // Use timestamp + table prefix + counter for uniqueness
        return (time() * 1000000) + $this->getTablePrefix($tableName) + $this->tableCounters[$tableName];
    }
}
```

### Migration Strategy

**Step 1**: Create new deterministic ID service
**Step 2**: Update install SQL to remove AUTO_INCREMENT
**Step 3**: Update application code to use ID service
**Step 4**: Test with high-volume scenarios
**Step 5**: Deploy with rollback plan

## ✅ COMPLIANCE CHECKLIST

- [ ] Remove all AUTO_INCREMENT from install SQL
- [ ] Implement deterministic ID service
- [ ] Update application insertion logic
- [ ] Test high-volume inserts (visits, logs)
- [ ] Verify uniqueness across tables
- [ ] Performance benchmark vs AUTO_INCREMENT
- [ ] Rollback procedure documented
- [ ] Update database documentation

## 📊 IMPACT ASSESSMENT

**Benefits**:
- ✅ **Doctrine Compliance**: Aligns with 4.1.0 deterministic-ID model
- ✅ **Predictability**: IDs become deterministic across deployments
- ✅ **Testing**: Easier to generate test data with known IDs
- ✅ **Sync**: Better database-to-filesystem synchronization

**Risks**:
- ⚠️ **Performance**: Deterministic generation may be slower than AUTO_INCREMENT
- ⚠️ **Complexity**: Application must manage ID generation
- ⚠️ **Migration**: Requires careful coordination to avoid data loss

**Mitigation**:
- Implement efficient ID generation with caching
- Use database transactions for atomicity
- Comprehensive testing before production deployment

---

## 🎯 NEXT STEPS

1. **Implement Phase 1 conversion** for high-priority tables
2. **Update install SQL** to remove AUTO_INCREMENT definitions
3. **Create migration script** for existing data
4. **Test deterministic ID service** performance
5. **Update this artifact** with implementation evidence

---

*Last updated: 2026-03-26 (4.1.0 remediation)*  
*Maintained by: THOTH (actor_id 26) through cursor faucet*
