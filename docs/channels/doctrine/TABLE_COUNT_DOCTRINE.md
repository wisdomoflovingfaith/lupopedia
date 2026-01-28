---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 2026.3.7.6
file.channel: doctrine
file.last_modified_utc: 20260120113800
file.utc_day: 20260120
file.name: "TABLE_COUNT_DOCTRINE.md"
file.lupopedia.5: 5
GOV-AD-PROHIBIT-001: true
UTC_TIMEKEEPER__CHANNEL_ID: "dev"
channel_key: system/kernel
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - UTC_TIMEKEEPER__CHANNEL_ID
temporal_edges:
  actor_identity: "Eric (Captain Wolfie)"
  actor_location: "Sioux Falls, South Dakota"
  system_context: "Schema Freeze Active / Table Count: 214 tables (8 under 222 limit) / File-Sovereignty"
dialog:
  speaker: CURSOR
  target: @everyone @CAPTAIN_WOLFIE @Monday_Wolfie
  mood_RGB: "00FF00"
  message: "Updated TABLE_COUNT_DOCTRINE: 214 tables (8 under 222 limit). Legacy livehelp tables removed in 4.4.1. Table ceiling set to 222. File-Sovereignty active."
tags:
  categories: ["documentation", "doctrine", "database", "architecture"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "architecture"]
file:
  name: "TABLE_COUNT_DOCTRINE.md"
  title: "Table Count Doctrine"
  description: "Architectural limits for database schema table count"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
system_context:
  schema_state: "Frozen"
  table_count: 214
  toon_defined_tables: 204
  missing_tables: 85
  projected_total: 205
  table_ceiling: 222
  table_count_violation: false
  table_count_overage: 0
  database_logic_prohibited: true
  governance_active: ["GOV-AD-PROHIBIT-001", "LABS-001", "GOV-WOLFIE-HEADERS-001", "TABLE_COUNT_DOCTRINE", "LIMITS_DOCTRINE"]
  doctrine_mode: "File-Sovereignty"
---

# NEW_TABLE_COUNT_DOCTRINE_v1.0
Effective Range: Versions 4.0.101 → 4.2.0
Status: ACTIVE ARCHITECTURE LAW

## Purpose
This doctrine establishes the hard architectural limits for the Lupopedia
database schema during the 4.0.x → 4.2.0 development cycle. It replaces
the earlier 111-table ideal with a more realistic constraint that reflects
current system truth, TOON-layer requirements, and Pack-era architecture.

## Doctrine Statement
**Until version 4.2.0, Lupopedia will maintain a total table count of no
more than 222 tables.**

MAX_ALLOWED_TABLES: 222
TARGET_TABLE_COUNT: 222
TABLE_OPTIMIZATION_TRIGGER: 223
TABLE_OPTIMIZATION_REQUIRED: true

- Current count: **214 tables** (in schema)
- TOON-defined tables: **170 tables** (after livehelp cleanup)
- Missing tables: **51 tables** (awaiting migration)  
- Target after migration: **170 tables** ✅ **52 tables under limit**
- Maximum allowed: **222 tables**
- Target operational count: **222 tables**

The system must operate at 222 tables or fewer. At 223+ tables, the Table Optimization Cycle is mandatory.

## Allowed Database Constructs
To preserve clarity, maintainability, and doctrine purity, the following
rules remain in full effect:

- ❌ No stored procedures  
- ❌ No database views  
- ❌ No database functions  
- ❌ No database triggers  
- ✔ Data only

All logic must reside in PHP service classes, doctrine files, or
application-level orchestration. The database remains a pure data store.

## Rationale
1. System truth alignment — 222 tables is the ceiling; current schema count is 214 tables (8 under limit).
2. Pack Architecture requirements — multi-agent coordination and emotional
   geometry require structural space.
3. Historical doctrine preservation — the 111-table rule remains part of
   Lupopedia's lore but is no longer a practical constraint.
4. Controlled growth — a 222-table ceiling provides flexibility without
   allowing schema sprawl.
5. Future-proofing — this ceiling remains in effect until 4.2.0.
6. Table optimization trigger — 223+ tables mandates immediate consolidation.
7. **NO VIOLATION**: Current projected total 205 tables (17 under limit).

## Doctrine Notes
- Legacy livehelp_ tables removed in version 4.1.17 (8 tables dropped)
- Table ceiling set to 222 to support system stability
- Target operational count: 222 tables
- Current schema count: 214 tables
- TOON-defined system: 204 tables
- Missing tables: 85 tables
- **NO VIOLATION**: Projected total 205 tables (17 under limit)
- Required action: Monitor table growth and prepare optimization at 223+

## Enforcement
- Any migration that would exceed 222 tables must be rejected.
- Any migration that would exceed 222 tables requires immediate optimization cycle.
- **CURRENT STATUS**: Migration allowed - projected total 205 tables (17 under limit)
- New tables require justification and a reduction plan.
- All schema changes must be logged in dialogs/versions/ and CHANGELOG.md.
- Violations trigger a Pack-level architectural warning.
- **IMMEDIATE ACTION REQUIRED**: Reduce TOON table count by 5 before migration

## Version Applicability
- Applies to all versions 4.0.101 → 4.2.0
- Does not modify historical versions
- Will be reevaluated during 4.2.0 planning

## Schema Freeze Status (4.2.0)

**Active Freeze:** 2026-01-20 through 4.3.x development cycle

### Restrictions
1. No `CREATE TABLE` operations without removing existing tables
2. No `DROP TABLE` of consolidated tables
3. No `ALTER TABLE` that changes data types or removes columns
4. Table count must remain ≤ 222
5. Operational count must remain ≤ 222

### Enforcement
- Application-level: migrations and LILITH oversight; veto authority
- Optional: `schema_freeze_enforcement_4_2_0` MySQL EVENT (see `database/migrations/4.2.0_schema_freeze_enforcement.sql`); deploy only if CAPTAIN_WOLFIE + LILITH approve
- Weekly compliance audits

### Exceptions
- Emergency security patches (requires CAPTAIN_WOLFIE + LILITH approval)
- Critical data corruption fixes
- Documented with `FREEZE_EXCEPTION` header

----------------------------------------------------------------------
END OF DOCTRINE
----------------------------------------------------------------------
