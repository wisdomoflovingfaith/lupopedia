> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/doctrine/TABLE_COUNT_DOCTRINE.md"
  file_hash: "91242a97c84437b7922fe59d0d5e60cf444694efcf8375c6540cde5b113a0e3d"
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
  file_path_from_root: "docs\channels\doctrine\TABLE_COUNT_DOCTRINE.md"
  file_hash: "d1f86093a5ce0e805bb83cba544d58b9c4ee95b0c0948f76713492af6eaffac5"
  file_path_from_root: "docs\channels\doctrine\TABLE_COUNT_DOCTRINE.md"
  file_hash: "82d1575b9b907b2e230962ae004db2c0efe4a65588383bb722a933684e9bcde8"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for TABLE_COUNT_DOCTRINE.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "table_count_doctrinemd"]
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
file.last_modified_system_version: 3.0.0
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
  system_context: "Schema Freeze Active / Table Count: run generate_toon_files.py (ceiling 199) / File-Sovereignty"
dialog:
  speaker: CURSOR
  target: @everyone @CAPTAIN_WOLFIE @Monday_Wolfie
  mood_vector: "00FF00"
  message: "TABLE_COUNT_DOCTRINE: current count = TOON file count after python scripts/generate_toon_files.py; do not hardcode. Ceiling 199."
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
  table_count: "run generate_toon_files.py; do not hardcode"
  toon_defined_tables: "= table_count from script"
  table_ceiling: 199
  table_count_violation: false
  table_count_overage: 0
  database_logic_prohibited: true
  governance_active: ["GOV-AD-PROHIBIT-001", "LABS-001", "GOV-WOLFIE-HEADERS-001", "TABLE_COUNT_DOCTRINE", "LIMITS_DOCTRINE"]
  doctrine_mode: "File-Sovereignty"
---

# NEW_TABLE_COUNT_DOCTRINE_v1.0
Effective Range: Versions 3.0.101 → 4.2.0
Status: ACTIVE ARCHITECTURE LAW

## How to obtain current table count (do not guess or hardcode)

**The current number of tables is determined by counting the TOON files produced after running the TOON generator.** Do not write or maintain a fixed table count in documentation.

1. Run: `python scripts/generate_toon_files.py` (from the project root).
2. The script writes one TOON per table; the number of TOON files written (or the count printed by the script) is the **current table count**.
3. Use that count when updating this doctrine or any doc that states "current table count". Do not guess or hardcode the value.

## Purpose
This doctrine establishes the hard architectural limits for the Lupopedia
database schema during the 3.0.x → 4.2.0 development cycle. It replaces
the earlier 111-table ideal with a more realistic constraint that reflects
current system truth, TOON-layer requirements, and Pack-era architecture.

## Doctrine Statement
**Until version 4.2.0, Lupopedia will maintain a total table count of no
more than 199 tables.**

MAX_ALLOWED_TABLES: 199
TARGET_TABLE_COUNT: 199
TABLE_OPTIMIZATION_TRIGGER: 200
TABLE_OPTIMIZATION_REQUIRED: true

- Current count: **see above** — run `python scripts/generate_toon_files.py` and use the TOON file count (do not hardcode).
- Maximum allowed: **199 tables**
- Target operational count: **199 tables**
- Tables under limit: **199 − (TOON count from script)**

The system must operate at 199 tables or fewer. At 200+ tables, the Table Optimization Cycle is mandatory.

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
1. System truth alignment — 199 tables is the ceiling; current schema count = TOON file count from `python scripts/generate_toon_files.py` (do not hardcode).
2. Pack Architecture requirements — multi-agent coordination and emotional
   geometry require structural space.
3. Historical doctrine preservation — the 111-table rule remains part of
   Lupopedia's lore but is no longer a practical constraint.
4. Controlled growth — a 199-table ceiling provides flexibility without
   allowing schema sprawl.
5. Future-proofing — this ceiling remains in effect until 4.2.0.
6. Table optimization trigger — 200+ tables mandates immediate consolidation.
7. **NO VIOLATION**: Current total = TOON count from script; must remain under 199.

## Doctrine Notes
- Legacy livehelp_ tables removed in version 3.1.17 (8 tables dropped)
- Table ceiling set to 199 to support system stability
- Target operational count: 199 tables
- Current schema count: run `python scripts/generate_toon_files.py` and use TOON file count (do not hardcode)
- TOON-defined system: same as table count from script
- **NO VIOLATION**: Current total must remain &lt; 199
- Required action: Monitor table growth and prepare optimization at 200+

## Enforcement
- Any migration that would exceed 199 tables must be rejected.
- Any migration that would exceed 199 tables requires immediate optimization cycle.
- **CURRENT STATUS**: Migration allowed while total &lt; 199; obtain current total from TOON count after running generate_toon_files.py
- New tables require justification and a reduction plan.
- All schema changes must be logged in dialogs/versions/ and CHANGELOG.md.
- Violations trigger a Pack-level architectural warning.

## Version Applicability
- Applies to all versions 3.0.101 → 4.2.0
- Does not modify historical versions
- Will be reevaluated during 4.2.0 planning

## Schema Freeze Status (4.2.0)

**Active Freeze:** 2026-01-20 through 4.3.x development cycle

### Restrictions
1. No `CREATE TABLE` operations without removing existing tables
2. No `DROP TABLE` of consolidated tables
3. No `ALTER TABLE` that changes data types or removes columns
4. Table count must remain ≤ 199
5. Operational count must remain ≤ 199

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
