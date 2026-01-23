---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.78
channel_key: system/kernel
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: WOLFIE
  target: @fleet
  mood_RGB: "FF0000"
  message: "NO TRIGGERS. NO STORED PROCEDURES. Database = state. Application = behavior. All logic must live in application code only."
tags:
  categories: ["doctrine", "database", "architecture"]
  collections: ["core-doctrine"]
  channels: ["dev", "architecture"]
file:
  title: "No Triggers No Procedures Doctrine"
  description: "Absolute prohibition of database-embedded logic in Lupopedia architecture"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# NO TRIGGERS NO PROCEDURES DOCTRINE

**Version**: 4.0.76  
**Status**: ACTIVE DOCTRINE - MANDATORY COMPLIANCE  
**Authority**: Captain Wolfie Fleet Directive  
**Scope**: All Lupopedia database operations  

## Core Principle

**Database = state. Application = behavior.**

The database exists solely for data storage and retrieval. All business logic, validation, computation, and behavioral rules must exist exclusively in application code.

## Absolute Prohibitions

### NO TRIGGERS
- **ZERO TOLERANCE**: No triggers of any kind may exist in Lupopedia databases
- **No INSERT triggers**: All insertion logic in application code
- **No UPDATE triggers**: All modification logic in application code  
- **No DELETE triggers**: All deletion logic in application code
- **No BEFORE/AFTER triggers**: All timing logic in application code

### NO STORED PROCEDURES
- **ZERO TOLERANCE**: No stored procedures may exist in Lupopedia databases
- **No business logic procedures**: All logic in application classes
- **No validation procedures**: All validation in application code
- **No calculation procedures**: All computation in application code
- **No utility procedures**: All utilities as application functions

### NO DATABASE-EMBEDDED LOGIC
- **No functions**: Database functions prohibited
- **No views with logic**: Simple SELECT-only views permitted
- **No computed columns**: All computation in application layer
- **No check constraints with logic**: Simple data type constraints only
- **No foreign key cascades**: All relationship management in application

## Doctrinal Foundation

### Separation of Concerns
- **Database Layer**: Storage, indexing, basic constraints only
- **Application Layer**: All logic, validation, business rules, computation
- **Clear Boundaries**: No overlap between storage and behavior

### Maintainability
- **Single Source of Truth**: Logic exists in one place only (application code)
- **Debuggability**: All logic visible and debuggable in application
- **Testability**: All logic unit testable without database dependencies
- **Refactorability**: Logic changes don't require schema migrations

### Federation Safety
- **Node Independence**: No database-specific logic dependencies
- **Migration Safety**: Schema changes don't break embedded logic
- **Version Compatibility**: Application logic versioned independently
- **Rollback Safety**: Logic rollbacks don't require schema changes

### Performance Predictability
- **No Hidden Logic**: All operations explicitly coded and visible
- **No Surprise Cascades**: All side effects explicitly programmed
- **Resource Control**: Application controls all computation resources
- **Monitoring**: All logic execution monitored at application level

## Implementation Rules

### Database Schema Rules
1. **Tables**: Data storage only, no computed columns
2. **Indexes**: Performance optimization only, no logic
3. **Constraints**: Data type and nullability only, no business rules
4. **Foreign Keys**: PROHIBITED - relationships managed in application
5. **Views**: Simple SELECT statements only, no complex logic

### Application Code Rules
1. **All Validation**: Performed in application before database operations
2. **All Computation**: Performed in application, results stored in database
3. **All Business Rules**: Implemented as application classes and methods
4. **All Relationships**: Managed through application-level foreign key logic
5. **All Cascades**: Explicitly programmed in application transaction logic

### Migration Rules
1. **Schema Only**: Database migrations change structure only
2. **No Logic Migration**: Logic changes deployed as application updates
3. **Clean Separation**: Schema and logic versioned independently
4. **Rollback Independence**: Schema rollbacks don't affect logic rollbacks

## Quarantine Protocol

### Existing Violations
Any existing triggers, stored procedures, or database-embedded logic must be:

1. **QUARANTINED**: Marked as doctrine violations, not trusted architecture
2. **INVENTORIED**: Catalogued for extraction planning
3. **CLASSIFIED**: Rated as CRITICAL (extract immediately) or LEGACY (disable later)
4. **EXTRACTED**: Logic moved to application code with full test coverage
5. **REMOVED**: Database artifacts deleted after successful extraction

### Violation Response
- **Immediate**: Stop using violating database features
- **Short-term**: Implement application-layer equivalents
- **Medium-term**: Extract all logic to application code
- **Long-term**: Remove all database artifacts

## Enforcement

### Code Review Requirements
- **Schema Reviews**: All schema changes reviewed for doctrine compliance
- **Logic Reviews**: All application logic reviewed for proper placement
- **Migration Reviews**: All migrations reviewed for logic separation
- **Architecture Reviews**: All designs reviewed for doctrine adherence

### Automated Validation
- **Schema Scanning**: Automated detection of triggers and procedures
- **Deployment Blocking**: Deployments blocked if violations detected
- **Monitoring**: Continuous monitoring for doctrine compliance
- **Alerting**: Immediate alerts for any doctrine violations

### Exception Process
- **NO EXCEPTIONS**: This doctrine admits no exceptions or special cases
- **Alternative Solutions**: All requirements must be met through application code
- **Escalation**: Any perceived need for database logic must be escalated to Captain Wolfie
- **Doctrine Evolution**: Only Captain Wolfie may modify this doctrine

## Rationale

### Historical Context
The TOON update introduced triggers and stored procedures that violate core Lupopedia architectural principles. These must be treated as transitional artifacts requiring immediate remediation.

### Architectural Integrity
Lupopedia's federated, multi-node architecture requires clean separation between data storage and business logic. Database-embedded logic creates:
- **Coupling**: Tight coupling between schema and behavior
- **Fragility**: Changes in one layer break the other
- **Opacity**: Hidden logic that's difficult to debug and maintain
- **Federation Risk**: Node-specific logic that breaks federation

### Long-term Stability
This doctrine ensures:
- **Predictable Behavior**: All logic explicitly coded and visible
- **Easy Maintenance**: Logic changes don't require schema changes
- **Clean Testing**: All logic unit testable without database
- **Safe Migration**: Schema and logic evolve independently

## Compliance Verification

### Database Audit
```sql
-- Verify no triggers exist
SHOW TRIGGERS;

-- Verify no stored procedures exist  
SHOW PROCEDURE STATUS;

-- Verify no functions exist
SHOW FUNCTION STATUS;
```

### Expected Results
All queries above must return empty result sets for full compliance.

## Summary

**Database = state. Application = behavior.**

This is not a suggestion or guideline. This is absolute doctrine that admits no exceptions. All logic must live in application code only. The database exists solely for data storage and retrieval.

**Compliance is mandatory. Violations will be quarantined and extracted.**

---

*This doctrine is issued under the authority of Captain Wolfie and is binding on all Lupopedia development activities.*