# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\schema\migrations\20260120_migration_audit.md"
  file_hash: "46ebe171df8d8f69771e6c0122ba2042bd8383320602562a5fca452bb396fcd3"
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
  file_path_from_root: "docs\channels\schema\migrations\20260120_migration_audit.md"
  file_hash: "98d64b0fa35c65db2ef632ace16d578603667e96e30ab684c077a970d5714190"
  file_path_from_root: "docs\channels\schema\migrations\20260120_migration_audit.md"
  file_hash: "a74b4fa15762c78fddc26e256ac4a3d35c1c071fe6e1ea7b0f51e80d6ea4143c"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260120_migration_audit.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "schema", "migrations", "20260120_migration_auditmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.name: "docs/migrations/20260120_migration_audit.md"
file.last_modified_system_version: 3.1.14
file.last_modified_utc: 20260120070000
file.utc_day: 20260120
GOV-AD-PROHIBIT-001: true
ads_prohibition_statement: "Ads are manipulation. Ads are disrespect. Ads violate user trust."
UTC_TIMEKEEPER__CHANNEL_ID: "dev"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION: "3.1.14"
temporal_edges:
  actor_identity: "CASCADE"
  actor_location: "Lupopedia Core"
  system_context: "Migration Audit Documentation"
dialog:
  speaker: CASCADE
  target: @CAPTAIN_WOLFIE @LILITH @ARA @CURSOR @SYSTEM
  mood_RGB: "FF6600"
  message: "Comprehensive migration audit findings documented for remediation."
tags:
  categories: ["migration", "audit", "documentation"]
  collections: ["core-docs", "migrations"]
  channels: ["dev", "migration"]
file:
  name: "Migration Audit Report - 2026-01-20"
  title: "Migration Audit Report"
  description: "Comprehensive analysis of craftysyntax_to_lupopedia_mysql.sql migration script"
  version: "3.1.14"
  status: active
  author: GLOBAL_CURRENT_AUTHORS
system_context:
  audit_scope: "craftysyntax_to_lupopedia_mysql.sql"
  script_version: "Crafty Syntax 3.6.1–3.7.5 → Lupopedia 3.0.3"
  tables_analyzed: 145
  compliance_status: "MEDIUM_RISK"
---

# Migration Audit Report - 2026-01-20

## Audit Scope
**File:** `database/migrations/craftysyntax_to_lupopedia_mysql.sql` 
**Script Version:** Crafty Syntax 3.6.1–3.7.5 → Lupopedia 3.0.3
**Tables:** 34 legacy `livehelp_*` tables, 111 core Lupopedia tables, 8 new Crafty module tables
**Total During Migration:** 145 tables
**Target After Migration:** 111 tables (after legacy DROP)

## Critical Findings

### 1. Doctrine Violations
- **Timestamp Issues:**
  - `0` used as `created_ymdhis`/`updated_ymdhis` in multiple tables
  - `DATE_FORMAT(NOW(), ...)` instead of UTC timestamps
  - Violates doctrine requirement: "timestamps UTC YYYYMMDDHHIISS"

- **Column Mismatch:**
  - `lupo_crm_lead_messages` INSERT has literal `1` as column name
  - SELECT returns 8 columns vs target 9 columns (including `1`)

- **Data Mapping Gaps:**
  - `livehelp_emailque` documented but not migrated
  - 15 legacy tables have no Lupopedia equivalents

### 2. Legacy Table Status
**Remaining Legacy Tables:** 45
**Migration Progress:** 65% complete
**Consolidation Ratio:** 1.2:1 (poor vs target 3:1)

### 3. Refactoring Required
1. Fix timestamp generation to use UTC
2. Correct column mismatch in `lupo_crm_lead_messages` 
3. Decide fate of unmapped legacy tables
4. Document "remnoved" typo fixes

## Recommended Actions
1. **Immediate (48h):** Fix timestamp doctrine violations
2. **Short-term (7d):** Address column mismatch and data gaps
3. **Medium-term (30d):** Achieve 3:1 consolidation ratio
4. **Long-term (90d):** Complete migration with ≤135 tables

## Compliance Status
- **Doctrine Adherence:** 6/10 (multiple violations)
- **Migration Progress:** 65% complete
- **Risk Level:** MEDIUM (data integrity concerns)

---

**Documented by:** CASCADE  
**Date:** 2026-01-20  
**Version:** 3.1.14