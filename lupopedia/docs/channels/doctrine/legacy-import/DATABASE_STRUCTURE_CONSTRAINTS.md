# DOCTRINE: DATABASE STRUCTURE CONSTRAINTS

**Filename:** doctrine/DATABASE_STRUCTURE_CONSTRAINTS.md  
**Status:** Architectural Hard Limit  
**Authority:** High (below Ethical Foundations, above operational doctrine)  
**Version:** 1.0  
**Channel:** 0 (System/Kernel) - Mandatory Boot Content

## 1. TABLE LIMIT ENFORCEMENT

### 1.1 Hard Limit Constraint
- **MAX_TABLES_PER_DATABASE**: 199 tables
- **Scope**: Applies to all databases in Lupopedia ecosystem
- **Enforcement**: System-level validation prevents table creation beyond limit
- **Exception**: Migration overage window (see Section 2)

### 1.2 Table Count Monitoring
```sql
-- Current table count query (must be ≤ 199)
SELECT COUNT(*) as table_count 
FROM information_schema.tables 
WHERE table_schema = DATABASE();
```

### 1.3 Database Scope
- **lupopedia**: Canonical shipping database (primary constraint)
- **lupopedia_worms**: Experimental/ORM sandbox (same constraint)
- **Future databases**: All subject to 199-table limit

## 2. MIGRATION OVERAGE WINDOW

### 2.1 Migration Exception Rules
Migrations may temporarily exceed 199 tables only if:

- **Active Migration**: Migration is currently executing
- **Temporary Overage**: Table count exceeds 199 during migration only
- **Cleanup Required**: Deprecated tables dropped before completion
- **Final Compliance**: Post-migration count ≤ 199

### 2.2 Migration Overage Window Protocol
```yaml
migration_overage_window:
  start_condition: migration_execution_begins
  end_condition: migration_completion_or_failure
  max_overage_duration: <migration_execution_time>
  cleanup_requirement: mandatory_before_completion
  final_validation: table_count_must_be_199_or_less
```

### 2.3 Migration Validation
```sql
-- Pre-migration validation
SELECT 
  COUNT(*) as current_tables,
  199 - COUNT(*) as remaining_capacity
FROM information_schema.tables 
WHERE table_schema = DATABASE();

-- Post-migration validation
SELECT 
  COUNT(*) as final_tables,
  CASE 
    WHEN COUNT(*) <= 199 THEN 'COMPLIANT' 
    ELSE 'VIOLATION' 
  END as compliance_status
FROM information_schema.tables 
WHERE table_schema = DATABASE();
```

## 3. TABLE NAMING CONVENTIONS

### 3.1 Table Name Structure
- **Format**: `{prefix}_{name}_{suffix}`
- **Max Length**: 64 characters
- **Characters**: lowercase letters, numbers, underscores only
- **No Spaces**: Underscores as word separators

### 3.2 Prefix Definitions
```yaml
table_prefixes:
  lupo_: "Core Lupopedia tables"
  auth_: "Authentication and authorization"
  crafty_: "Crafty Syntax system"
  emotional_: "Emotional framework tables"
  routing_: "Routing and navigation"
  audit_: "Audit and logging"
  temp_: "Temporary tables (auto-cleanup)"
  backup_: "Backup tables (scheduled cleanup)"
```

### 3.3 Reserved Prefixes
- **system_**: System internal use only
- **mysql_**: MySQL system tables (reserved)
- **information_schema_**: MySQL metadata (reserved)
- **performance_schema_**: MySQL performance (reserved)

## 4. COLUMN CONSTRAINTS

### 4.1 Column Naming Rules
- **Format**: `{name}_{type_suffix}` where applicable
- **Max Length**: 64 characters
- **No Reserved Words**: Avoid SQL keywords
- **Descriptive**: Clear purpose indication

### 4.2 Type Suffixes
```yaml
type_suffixes:
  _id: "Primary or foreign key identifiers"
  _num: "Numeric values (int, decimal)"
  _ymdhis: "Unix timestamp format"
  _flag: "Boolean/tinyint indicators"
  _code: "Short code values"
  _key: "Unique key values"
  _hash: "Hash values"
  _json: "JSON data fields"
  _text: "Long text content"
  _url: "URL or path values"
```

### 4.3 Mandatory Columns
Every table must include:
```sql
-- Standard audit columns
created_ymdhis bigint NOT NULL DEFAULT '0' COMMENT 'Creation timestamp',
updated_ymdhis bigint NOT NULL DEFAULT '0' COMMENT 'Last update timestamp'
```

## 5. INDEX CONSTRAINTS

### 5.1 Index Naming Convention
- **Primary Key**: `pk_{table_name}`
- **Unique Key**: `uk_{column_names}`
- **Foreign Key**: `fk_{table_name}_{referenced_table}`
- **Index**: `idx_{column_names}`

### 5.2 Index Limits
- **Max Indexes Per Table**: 64 (MySQL limit)
- **Index Size Monitoring**: Regular size audits required
- **Performance Impact**: Index additions require performance review

### 5.3 Foreign Key Constraints
```yaml
foreign_key_rules:
  naming: "fk_{table}_{referenced_table}"
  reference_action: "RESTRICT or CASCADE (explicitly specified)"
  on_delete: "Must be explicitly defined"
  on_update: "Must be explicitly defined"
  check_existing: "Verify referential integrity before creation"
```

## 6. DATA TYPE CONSTRAINTS

### 6.1 Preferred Data Types
```yaml
recommended_types:
  identifiers: "bigint UNSIGNED"
  timestamps: "bigint (Unix timestamp) or timestamp"
  short_text: "varchar(255)"
  long_text: "text"
  json_data: "json"
  boolean: "tinyint(1)"
  decimal_values: "decimal(precision, scale)"
  enums: "enum('value1','value2')"
```

### 6.2 Prohibited Data Types
- **BLOB**: Use text or json instead
- **SET**: Use enum or separate table instead
- **FLOAT**: Use decimal for precision
- **YEAR**: Use timestamp instead

### 6.3 Character Set Requirements
- **Default Charset**: utf8mb4
- **Default Collation**: utf8mb4_unicode_ci
- **No Legacy Encodings**: utf8, latin1 prohibited

## 7. ENGINE AND STORAGE CONSTRAINTS

### 7.1 Storage Engine Requirements
- **Default Engine**: InnoDB
- **No MyISAM**: Transactional support required
- **No MEMORY**: Persistent storage required
- **No ARCHIVE**: Full functionality required

### 7.2 Table Options
```sql
-- Standard table options
ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci
COMMENT='Descriptive table comment'
```

### 7.3 Row Format Constraints
- **Default Row Format**: Dynamic
- **No Fixed Row Format**: Inefficient for variable data
- **No Compressed Row Format**: Compatibility issues

## 8. CONSTRAINT VALIDATION

### 8.1 Pre-Creation Validation
```sql
-- Table count validation
SELECT 
  (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE()) as current_tables,
  CASE 
    WHEN (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE()) < 199 
    THEN 'CAN_CREATE' 
    ELSE 'AT_LIMIT' 
  END as creation_allowed;
```

### 8.2 Post-Creation Validation
```sql
-- New table validation
SELECT 
  table_name,
  engine,
  table_collation,
  table_comment,
  table_rows
FROM information_schema.tables 
WHERE table_schema = DATABASE() 
  AND table_name = 'new_table_name';
```

### 8.3 Compliance Monitoring
```sql
-- Daily compliance check
SELECT 
  'TABLE_COUNT_COMPLIANCE' as check_type,
  COUNT(*) as table_count,
  CASE 
    WHEN COUNT(*) <= 199 THEN 'COMPLIANT' 
    ELSE 'VIOLATION' 
  END as status,
  NOW() as check_time
FROM information_schema.tables 
WHERE table_schema = DATABASE();
```

## 9. MIGRATION CONSTRAINTS

### 9.1 Migration Table Management
```yaml
migration_rules:
  table_addition:
    - check_current_table_count
    - verify_under_limit
    - create_new_table
    - update_table_count
  table_removal:
    - check_dependencies
    - backup_if_needed
    - drop_table
    - update_table_count
  table_modification:
    - assess_impact
    - create_backup
    - apply_changes
    - validate_structure
```

### 9.2 Migration Validation Steps
1. **Pre-Migration Check**: Verify table count compliance
2. **Impact Assessment**: Calculate table count changes
3. **Dependency Analysis**: Check table relationships
4. **Rollback Planning**: Prepare rollback strategy
5. **Execution Monitoring**: Track table count during migration
6. **Post-Migration Validation**: Verify final compliance

### 9.3 Migration Error Handling
```yaml
error_handling:
  limit_exceeded:
    action: "HALT_MIGRATION"
    notification: "IMMEDIATE_ESCALATION"
    rollback: "AUTOMATIC"
  dependency_violation:
    action: "PAUSE_MIGRATION"
    notification: "DEVELOPER_ALERT"
    rollback: "MANUAL"
  validation_failure:
    action: "STOP_MIGRATION"
    notification: "SYSTEM_ADMIN"
    rollback: "FULL"
```

## 10. AUDIT AND MONITORING

### 10.1 Audit Requirements
```yaml
audit_events:
  table_creation:
    - log_table_details
    - record_actor_id
    - capture_creation_time
    - store_table_count_before_after
  table_modification:
    - log_changes_made
    - record_reason_for_change
    - capture_rollback_capability
  table_deletion:
    - log_deletion_details
    - record_backup_location
    - capture_deletion_time
```

### 10.2 Monitoring Metrics
```yaml
monitoring_metrics:
  table_count_trend: "Track table count over time"
  table_size_growth: "Monitor individual table sizes"
  index_efficiency: "Track index usage and performance"
  migration_success_rate: "Monitor migration compliance"
  constraint_violations: "Track rule violations"
```

### 10.3 Reporting Requirements
- **Daily**: Table count summary
- **Weekly**: Constraint compliance report
- **Monthly**: Database growth analysis
- **Quarterly**: Full structural audit

## 11. SPECIAL DATABASE CONSIDERATIONS

### 11.1 Lupopedia Database (Canonical)
- **Human Authored**: No AI-generated tables
- **Doctrine Aligned**: Must follow all constraints
- **No ORM**: Direct SQL only
- **Strict Validation**: All changes require human approval

### 11.2 Lupopedia_worms Database (Experimental)
- **AI Sandbox**: ORM experiments allowed
- **Same Constraints**: 199-table limit applies
- **Experimental Features**: Must be isolated
- **Regular Cleanup**: Temporary tables auto-cleanup

### 11.3 Future Databases
- **Explicit Approval**: Required before creation
- **Constraint Inheritance**: All constraints apply
- **Naming Convention**: Must follow naming rules
- **Compliance Monitoring**: Same audit requirements

## 12. COMPLIANCE ENFORCEMENT

### 12.1 System Enforcement
- **Prevention**: Block table creation beyond limit
- **Monitoring**: Real-time table count tracking
- **Alerting**: Immediate notification of violations
- **Rollback**: Automatic rollback of violations

### 12.2 Human Enforcement
- **Approval**: Human approval for structural changes
- **Review**: Regular compliance reviews
- **Escalation**: Human escalation for violations
- **Documentation**: Maintain compliance records

### 12.3 Violation Consequences
```yaml
violation_consequences:
  table_limit_exceeded:
    immediate_action: "SYSTEM_HALT"
    escalation: "HUMAN_ADMIN"
    remediation: "IMMEDIATE_CLEANUP"
  constraint_violation:
    immediate_action: "BLOCK_OPERATION"
    escalation: "DEVELOPER_TEAM"
    remediation: "COMPLIANCE_FIX"
```

---

**AUTHORITY:** This doctrine defines the mandatory structural constraints for all databases in the Lupopedia ecosystem. The 199-table limit is absolute and non-negotiable.

**COMPLIANCE:** Required for all database operations. System validation enforces these constraints automatically.
