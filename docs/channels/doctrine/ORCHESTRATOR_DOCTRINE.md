---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.26
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-15
author: Wolfie (Eric Robin Gerdes)
architect: Captain Wolfie
dialog:
  speaker: cursor
  target: @everyone
  message: "Created ORCHESTRATOR_DOCTRINE.md to document the migration orchestrator subsystem, its schemas, tables, and operational principles. Foundation established for automated migration management."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "orchestration", "migration"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
file:
  title: "Migration Orchestrator Doctrine"
  description: "Doctrine explaining the migration orchestrator subsystem, schema federation, and migration management infrastructure"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Captain Wolfie"
---

# 🟦 **Migration Orchestrator Doctrine**

### **Purpose of This Document (For Cursor & Contributors)**

This doctrine explains the **Migration Orchestrator** subsystem introduced in version 4.0.25, which provides automated migration tracking, schema versioning, table classification, state machine management, and validation infrastructure. The orchestrator enables controlled, auditable, and reversible database migrations across Lupopedia's federated schema architecture.

---

# 🟩 **1. Schema Federation Architecture**

Lupopedia uses **schema federation** to organize tables across multiple schemas:

- **`lupopedia`** (core schema) - 77 core runtime tables
- **`lupopedia_orchestration`** (orchestration schema) - 5 migration management tables
- **`lupopedia_ephemeral`** (ephemeral schema) - 5 temporary/session tables

**Total: 87 tables across 3 schemas** (as of version 4.0.25)

This architecture:
- Reduces cognitive load by separating concerns
- Enables unlimited growth in specialized schemas
- Maintains the 111-table doctrine boundary for core schema
- Provides clear boundaries for migration planning

---

# 🟦 **2. Orchestration Schema Tables**

The `lupopedia_orchestration` schema contains 5 core tables:

### **2.1 `lupo_migration_tracking`**
Tracks migration execution history and status:
- Migration name, version, and type
- Execution status (pending, running, completed, failed, rolled_back)
- Execution order and timing
- Tables affected
- Error messages and rollback information
- Actor who executed the migration

### **2.2 `lupo_schema_versions`**
Tracks schema versions across all federated schemas:
- Schema name and version string
- Numeric version for comparisons
- Table count per schema
- Last migration information

### **2.3 `lupo_table_classifications`**
Classifies tables for migration planning:
- Table classification (core, orchestration, ephemeral, legacy, deprecated)
- Functional category
- Migration priority and target schema
- Migration status (unmigrated, pending, migrated, skipped)

### **2.4 `lupo_migration_states`**
Tracks state machine transitions for migration execution:
- Current and previous states
- State entry timing and duration
- State-specific data in JSON format

### **2.5 `lupo_migration_validations`**
Stores validation results for migrations:
- Validation type and status
- Validation messages and details
- Validation timing

---

# 🟧 **3. Ephemeral Schema Tables**

The `lupopedia_ephemeral` schema contains 5 core tables:

### **3.1 `lupo_sessions`**
Stores ephemeral session data:
- Session keys and actor associations
- Session data in JSON format
- IP address and user agent
- Last activity and expiration timestamps

### **3.2 `lupo_cache`**
Stores ephemeral cache data:
- Cache keys and groups
- Cached values (JSON, serialized, or text)
- Expiration timestamps
- Hit count and last hit timing

### **3.3 `lupo_temp_data`**
Stores temporary data that can be safely purged:
- Temporary data keys and types
- Data in JSON or text format
- Creator actor and expiration timestamps

### **3.4 `lupo_job_queue`**
Stores ephemeral job queue data for background tasks:
- Job type, status, and priority
- Job parameters in JSON format
- Scheduled, started, and completed timestamps
- Error messages and retry counts

### **3.5 `lupo_locks`**
Stores ephemeral distributed lock data:
- Lock keys and types
- Lock holder actor
- Lock acquisition and expiration timestamps

---

# 🟩 **4. Migration Orchestrator Principles**

### **4.1 Idempotency**
All migrations must be **idempotent** - they can be run multiple times safely without side effects. Use `INSERT ... ON DUPLICATE KEY UPDATE` or `UPDATE`/`INSERT` patterns with existence checks.

### **4.2 Non-Destructiveness**
Migrations must be **non-destructive** - they add new structures or data without removing existing functionality. Use soft deletes (`is_deleted`) rather than hard deletes.

### **4.3 State Machine**
Migrations follow a **state machine** lifecycle:
1. `pre_migration` - Validation and preparation
2. `validation` - Pre-execution validation
3. `backup` - Backup creation (if needed)
4. `execution` - Migration execution
5. `verification` - Post-execution verification
6. `post_migration` - Cleanup and finalization
7. `completed` - Migration complete
8. `failed` - Migration failed (rollback available)
9. `rolled_back` - Migration rolled back

### **4.4 Validation**
All migrations must pass **validation** before and after execution:
- Schema integrity checks
- Data integrity checks
- Constraint validation
- Foreign key validation (if applicable)

### **4.5 Rollback**
All migrations must have **rollback capability**:
- Rollback migration SQL file
- Rollback state tracking
- Rollback validation

---

# 🟦 **5. Table Classification System**

Tables are classified for migration planning:

- **`core`** - Core runtime tables in `lupopedia` schema
- **`orchestration`** - Migration management tables in `lupopedia_orchestration` schema
- **`ephemeral`** - Temporary tables in `lupopedia_ephemeral` schema
- **`legacy`** - Legacy tables pending migration or removal
- **`deprecated`** - Deprecated tables scheduled for removal

Classification enables:
- Migration priority planning
- Target schema determination
- Migration status tracking
- Audit and reporting

---

# 🟧 **6. Migration File Organization**

Migration files are organized in `/database/migrations/`:

- `orchestrator_schema_4_0_25.sql` - Orchestration schema creation
- `ephemeral_schema_4_0_25.sql` - Ephemeral schema creation
- `{migration_name}_{version}.sql` - Version-specific migrations
- `{migration_name}_{version}_rollback.sql` - Rollback migrations

Migration files must:
- Be idempotent
- Include version in filename
- Follow Lupopedia doctrine (no foreign keys, triggers, or stored procedures)
- Use UTC timestamps (YYYYMMDDHHMMSS format)
- Use soft deletes

---

# 🟩 **7. Doctrine Compliance**

All orchestrator tables follow Lupopedia doctrine:

- ✅ **BIGINT(20) UNSIGNED** for all ID columns
- ✅ **UTC timestamps** in BIGINT format (YYYYMMDDHHMMSS)
- ✅ **Soft deletes** with `is_deleted` and `deleted_ymdhis`
- ✅ **No foreign keys** - all relationships in application layer
- ✅ **No triggers** - all logic in application layer
- ✅ **No stored procedures** - all logic in application layer
- ✅ **UTF8MB4** character set and collation
- ✅ **JSON columns** for flexible metadata storage

---

# 🟦 **8. Usage Guidelines**

### **For Cursor:**
- Reference orchestrator tables when generating migration code
- Use orchestrator state machine for migration execution
- Track migrations in `lupo_migration_tracking`
- Classify tables in `lupo_table_classifications`
- Store validation results in `lupo_migration_validations`

### **For Developers:**
- Use ephemeral tables for temporary data
- Use cache table for application caching
- Use job queue for background tasks
- Use locks for distributed locking
- Clean up expired ephemeral data regularly

### **For Migration Authors:**
- Register migrations in `lupo_migration_tracking`
- Update schema versions in `lupo_schema_versions`
- Classify affected tables in `lupo_table_classifications`
- Track state transitions in `lupo_migration_states`
- Store validation results in `lupo_migration_validations`

---

# 🟧 **9. Future Enhancements**

Planned enhancements for the orchestrator subsystem:

- Migration dependency tracking
- Parallel migration execution
- Migration performance metrics
- Automated rollback on failure
- Migration testing framework
- Schema diff generation
- Migration conflict detection

---

# 🟩 **10. Related Doctrine**

- [Schema Federation Doctrine](SCHEMA_FEDERATION_DOCTRINE.md)
- [Migration Doctrine](MIGRATION_DOCTRINE.md)
- [SQL Doctrine](SQL_REWRITE_DOCTRINE.md)
- [Database Security Doctrine](DATABASE_SECURITY_DOCTRINE.md)

---

**Version:** 4.0.26  
**Status:** STABLE  
**Last Updated:** 2026-01-15
