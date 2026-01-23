# Phase A: Schema Federation Migration

## Overview

Phase A implements schema federation by creating two new schemas and moving 34 tables out of the core schema, reducing core schema table count from 111 to 77.

## Migration Files

### 1. Schema Creation
**File**: `phase_a_orchestration_schema.sql`

Creates the two federated schemas:
- `lupopedia_orchestration` - For orchestration and migration management tables
- `lupopedia_ephemeral` - For ephemeral session, cache, and temporary tables

**Execution**:
```sql
SOURCE database/migrations/phase_a_orchestration_schema.sql;
```

### 2. Move Orchestration Tables
**File**: `phase_a_move_orchestration_tables.sql`

Moves 22 orchestration tables from core schema to `lupopedia_orchestration`:
- Migration management tables
- System monitoring tables
- Audit logging tables
- Agent orchestration tables
- Memory and event management tables
- API rate limiting tables
- Notifications and governance tables

**Execution**:
```sql
SOURCE database/migrations/phase_a_move_orchestration_tables.sql;
```

### 3. Move Ephemeral Tables
**File**: `phase_a_move_ephemeral_tables.sql`

Moves 12 ephemeral tables from core schema to `lupopedia_ephemeral`:
- Session management tables
- Daily analytics aggregations
- Temporary page views
- API tokens and clients
- Temporary content processing tables

**Execution**:
```sql
SOURCE database/migrations/phase_a_move_ephemeral_tables.sql;
```

### 4. Rollback Migration
**File**: `phase_a_rollback.sql`

Complete rollback that:
- Moves all tables back to core schema
- Drops federated schemas
- Restores original table locations

**Execution** (if rollback needed):
```sql
SOURCE database/migrations/phase_a_rollback.sql;
```

## Execution Order

Execute migrations in this order:

1. `phase_a_orchestration_schema.sql` - Create schemas
2. `phase_a_move_orchestration_tables.sql` - Move orchestration tables
3. `phase_a_move_ephemeral_tables.sql` - Move ephemeral tables

## Verification

After migration, verify table counts:

```sql
-- Core schema (should be ~77)
SELECT COUNT(*) FROM information_schema.tables 
WHERE table_schema = 'lupopedia';

-- Orchestration schema (should be 22)
SELECT COUNT(*) FROM information_schema.tables 
WHERE table_schema = 'lupopedia_orchestration';

-- Ephemeral schema (should be 12)
SELECT COUNT(*) FROM information_schema.tables 
WHERE table_schema = 'lupopedia_ephemeral';
```

## Code Updates Required

After migration, update PHP code to use schema-qualified table names:

1. Include schema config:
```php
require_once(LUPO_INCLUDES_DIR . '/schema-config.php');
```

2. Use helper function:
```php
// Before
$db->query("SELECT * FROM lupo_audit_log WHERE ...");

// After
$db->query("SELECT * FROM " . lupo_table('lupo_audit_log') . " WHERE ...");
```

## Related Documentation

- [Schema Federation Doctrine](../../docs/doctrine/SCHEMA_FEDERATION_DOCTRINE.md)
- [Table Budget Doctrine](../../docs/doctrine/TABLE_BUDGET_DOCTRINE.md)
- [Database Schema Reference](../../docs/schema/DATABASE_SCHEMA.md)

---

*Version: 4.0.3*  
*Author: Captain Wolfie*
