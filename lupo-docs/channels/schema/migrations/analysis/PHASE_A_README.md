# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\schema\migrations\analysis\PHASE_A_README.md"
  file_hash: "1e7753b4558c732781d6c7836f90f7b3f3c4f7a7c053df6345f0edb68af570b2"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\schema\migrations\analysis\PHASE_A_README.md"
  file_hash: "ef9f8fe268ec2dff4763909426b987133ae32bc29f0044966b9d1626ade17e7c"
  file_path_from_root: "docs\channels\schema\migrations\analysis\PHASE_A_README.md"
  file_hash: "17da0f9504a425afa79f51bda476d383feafc6ef6b10b3af931dc5ebdec0722e"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Phase A: Schema Federation Migration"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "schema", "migrations", "analysis", "phase_a_readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

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

*Version: 3.0.3*  
*Author: Captain Wolfie*