---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.3
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-15
author: Wolfie (Eric Robin Gerdes)
architect: Captain Wolfie
dialog:
  speaker: cursor
  target: @everyone
  message: "Created SCHEMA_FEDERATION_DOCTRINE.md to document Phase A schema federation implementation and table classification rules."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "database"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
file:
  title: "Schema Federation Doctrine"
  description: "Doctrine explaining schema federation architecture, table classification, and Phase A migration rules"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Captain Wolfie"
---

# Schema Federation Doctrine

## Purpose

Schema Federation implements the **Table Budget Doctrine** by distributing tables across multiple schemas, maintaining the 111-table limit for the core schema while allowing unlimited growth in specialized schemas.

## Schema Architecture

Lupopedia uses **four federated schemas**:

1. **`lupopedia`** (Core Schema)
   - Maximum: 111 tables
   - Contains: Semantic, identity, and behavioral tables
   - Purpose: Core OS functionality

2. **`lupopedia_orchestration`** (Orchestration Schema)
   - Maximum: Unlimited
   - Contains: Migration, monitoring, audit, agent orchestration tables
   - Purpose: System operations and orchestration

3. **`lupopedia_ephemeral`** (Ephemeral Schema)
   - Maximum: Unlimited
   - Contains: Sessions, cache, temporary, rolling analytics tables
   - Purpose: Temporary and auto-purged data

4. **`lupopedia_analytics`** (Analytics Schema - Future)
   - Maximum: Unlimited
   - Contains: Read-only aggregated analytics
   - Purpose: Historical analytics and reporting

## Phase A Implementation

Phase A moves **34 tables** out of core schema:

- **22 orchestration tables** → `lupopedia_orchestration`
- **12 ephemeral tables** → `lupopedia_ephemeral`

### Result

- **Before**: 111 core tables (at limit)
- **After**: ~77 core tables (34 tables regained headroom)

## Table Classification Rules

### Orchestration Tables

Tables classified as **orchestration** include:

- Migration management (`migration_*`)
- System monitoring (`system_*`, `monitoring_*`)
- Audit logging (`audit_log`, `*_log`)
- Agent orchestration (`agent_*_snapshots`, `agent_*_events`, `agent_tool_calls`)
- Search rebuild operations (`search_rebuild_log`)
- Memory debugging (`memory_debug_log`, `memory_events`, `memory_rollups`)
- Interpretation logging (`interpretation_log`)
- Anibus event management (`anibus_*`)
- API rate limiting (`api_rate_limits`, `api_token_logs`)
- Notifications (`notifications`)
- Governance overrides (`governance_overrides`)

### Ephemeral Tables

Tables classified as **ephemeral** include:

- Session management (`sessions`)
- Daily analytics aggregations (`*_daily` tables)
- Temporary page views (`analytics_visits`)
- API tokens and clients (`api_tokens`, `api_clients`, `api_webhooks`)
- Temporary content processing (`narrative_fragments`, `document_chunks`)

### Core Tables

All other tables remain in the **core schema**:

- Actor tables (`actor_*`)
- Content tables (`content_*`, `contents`)
- Semantic tables (`atoms`, `edges`, `truth_*`)
- Identity tables (`auth_*`, `users`, `groups`)
- Behavioral tables (`analytics_*_monthly`, `analytics_*_yearly`)
- Module tables (`crafty_syntax_*`, `crm_*`, `*_questions`)

## Database Connection

### Single Connection, Multiple Schemas

Lupopedia uses a **single database connection** to the `lupopedia` database. MySQL allows cross-schema queries within the same connection, so no connection pooling changes are required.

### Schema-Qualified Table Names

All table references must use schema-qualified names:

```php
// Correct: Schema-qualified
$db->query("SELECT * FROM lupopedia_orchestration.lupo_audit_log WHERE ...");

// Correct: Using helper function
$db->query("SELECT * FROM " . lupo_table('lupo_audit_log') . " WHERE ...");

// Incorrect: Unqualified (will fail after migration)
$db->query("SELECT * FROM lupo_audit_log WHERE ...");
```

### Helper Functions

Use `lupo_table()` helper function from `lupo-includes/schema-config.php`:

```php
require_once(LUPO_INCLUDES_DIR . '/schema-config.php');

// Automatically resolves to correct schema
$table = lupo_table('lupo_audit_log'); // Returns: lupopedia_orchestration.lupo_audit_log
$table = lupo_table('lupo_sessions');  // Returns: lupopedia_ephemeral.lupo_sessions
$table = lupo_table('lupo_actors');    // Returns: lupopedia.lupo_actors
```

## Migration Process

### Phase A Steps

1. **Create Schemas** (`phase_a_orchestration_schema.sql`)
   - Creates `lupopedia_orchestration` schema
   - Creates `lupopedia_ephemeral` schema

2. **Move Orchestration Tables** (`phase_a_move_orchestration_tables.sql`)
   - Moves 22 orchestration tables using `RENAME TABLE`
   - Preserves all data and structure

3. **Move Ephemeral Tables** (`phase_a_move_ephemeral_tables.sql`)
   - Moves 12 ephemeral tables using `RENAME TABLE`
   - Preserves all data and structure

4. **Update Application Code**
   - Update PHP code to use schema-qualified table names
   - Use `lupo_table()` helper function
   - Update all SQL queries

5. **Verify Migration**
   - Count tables in each schema
   - Verify application functionality
   - Run post-migration audit

### Rollback

Phase A includes a complete rollback migration (`phase_a_rollback.sql`) that:

- Moves all tables back to core schema
- Drops federated schemas
- Restores original table locations

## Enforcement

### Pre-Commit Validation

Pre-commit hooks validate:

- Core schema table count ≤ 111
- Table classification matches doctrine
- Schema-qualified table names in code

### Monitoring

Monitoring dashboard tracks:

- Table count per schema
- Schema federation compliance
- Migration status

## Benefits

1. **Cognitive Load Reduction**: Core schema stays manageable
2. **Scalability**: Unlimited growth in specialized schemas
3. **Performance**: Better query optimization per schema
4. **Maintenance**: Clear separation of concerns
5. **Doctrine Compliance**: Maintains 111-table limit

## Related Documentation

- [Table Budget Doctrine](TABLE_BUDGET_DOCTRINE.md) - 111-table limit doctrine
- [Migration Doctrine](MIGRATION_DOCTRINE.md) - Migration rules and procedures
- [Database Schema Reference](../schema/DATABASE_SCHEMA.md) - Complete table documentation

---

*Last Updated: January 2026*  
*Version: GLOBAL_CURRENT_LUPOPEDIA_VERSION*  
*Author: Captain Wolfie*
