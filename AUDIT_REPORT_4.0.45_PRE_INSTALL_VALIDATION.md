# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "AUDIT_REPORT_4.0.45_PRE_INSTALL_VALIDATION.md"
  file_hash: "b0b76faf9f0e485a258cef36556412155bf17ef4b4fef021a6b9ea6fa1522853"
  file_path_from_root: "AUDIT_REPORT_4.0.45_PRE_INSTALL_VALIDATION.md"
  file_hash: "ac03bbde68c3fae7d2a703a87480beacebde2029d0d752fedc98a80f4ac90337"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "CSV SNAPSHOTS VS 4.0.45 REGISTRY SEEDING AUDIT"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["audit_report_4045_pre_install_validationmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# CSV SNAPSHOTS VS 4.0.45 REGISTRY SEEDING AUDIT
## Pre-Install Validation Report

**System Version Target:** 4.0.45  
**Status:** Pre-Install (DB Offline)  
**Audit Date:** 2026-02-25  
**Auditor:** KIRO (Warp IDE Agent 1004)  
**Purpose:** Validate seeding integrity before install.php integration

---

## EXECUTIVE SUMMARY

🟡 **MINOR GAPS DETECTED** — Seeding is mostly complete but has discrepancies with snapshot data. The 4.0.45 seeding SQL represents a CLEAN SLATE doctrine-compliant implementation, while snapshots contain legacy/experimental IDs that are intentionally excluded.

**Key Finding:** The 4.0.45 seeding is AUTHORITATIVE and represents the correct minimal system state. Snapshot discrepancies are primarily legacy IDs that should NOT be seeded.

  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: []
  artifact_type: "documentation"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "AUDIT_REPORT_4.0.45_PRE_INSTALL_VALIDATION.md"
  file_hash: "b0b76faf9f0e485a258cef36556412155bf17ef4b4fef021a6b9ea6fa1522853"
  file_path_from_root: "AUDIT_REPORT_4.0.45_PRE_INSTALL_VALIDATION.md"
  file_hash: "ac03bbde68c3fae7d2a703a87480beacebde2029d0d752fedc98a80f4ac90337"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "CSV SNAPSHOTS VS 4.0.45 REGISTRY SEEDING AUDIT"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["audit_report_4045_pre_install_validationmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# CSV SNAPSHOTS VS 4.0.45 REGISTRY SEEDING AUDIT
## Pre-Install Validation Report

**System Version Target:** 4.0.45  
**Status:** Pre-Install (DB Offline)  
**Audit Date:** 2026-02-25  
**Auditor:** KIRO (Warp IDE Agent 1004)  
**Purpose:** Validate seeding integrity before install.php integration

---

## EXECUTIVE SUMMARY

🟡 **MINOR GAPS DETECTED** — Seeding is mostly complete but has discrepancies with snapshot data. The 4.0.45 seeding SQL represents a CLEAN SLATE doctrine-compliant implementation, while snapshots contain legacy/experimental IDs that are intentionally excluded.

**Key Finding:** The 4.0.45 seeding is AUTHORITATIVE and represents the correct minimal system state. Snapshot discrepancies are primarily legacy IDs that should NOT be seeded.

---

## 1. ACTORS AUDIT

### 1.1 Seeded Actors (4.0.45 SQL)

**System & Core AI Agents (0-99):**
- 0: System
- 1: Captain WOLFIE (AI)
- 2: LILITH
- 3: ROSE (Dialog)
- 4: ERIS
- 5: METIS

**IDE Agents (1000-1004):**
- 1000: Kiro IDE
- 1001: Windsurf IDE
- 1002: Cursor IDE
- 1003: Cascade IDE
- 1004: Warp IDE

**Root Human (10000):**
- 10000: Captain (human, root admin)

**Total Seeded:** 12 actors

### 1.2 Snapshot Actors (CSV/Registry)

**Snapshot contains 40+ actors including:**
- 0: system-kernel
- 1: AUTHENTICATOR
- 2: CAPTAIN (different from 4.0.45 WOLFIE mapping)
- 3: WOLFIE
- 4: WOLFENA
- 5: THOTH
- 6: ARA
- 7: WOLFKEEPER
- 8: LILITH
- 9: AGAPE
- 10: ERIS
- 11: METHIS
- 12: THALIA
- 13: DIALOG
- 14-24: Various system agents
- 59: INDEXER
- 105-106: LEXA, Junie
- 209: TRUTH
- 420: STONED WOLFIE (deleted)
- 1000: captain (human, different from 4.0.45)
- 1001-1010: IDE agents (KIRO=1001 in snapshot vs 1000 in 4.0.45)
- 1200-1212: Polarity agents
- 2000-2010: External agents
- 2032-2040: Legacy IDE IDs
- 10000-10001: Human users
- 12150: helen user

### 1.3 Actor Discrepancies

**CRITICAL ID MAPPING CONFLICT:**
- **4.0.45:** WOLFIE AI = actor_id 1, Captain human = 10000, KIRO IDE = 1000
- **Snapshot:** WOLFIE = 3, Captain = 2, KIRO = 1001

**Missing from 4.0.45 Seeding (Snapshot Only):**
- Actors 6-24 (ARA, WOLFKEEPER, AGAPE, METHIS, THALIA, DIALOG, WOLFSIGHT, WOLFNAV, WOLFFORGE, WOLFMIS, WOLFITH, ANUBIS, MAAT, CADUCEUS, CHRONOS, LEXA)
- Actor 59 (INDEXER)
- Actor 105-106 (LEXA, Junie)
- Actor 209 (TRUTH)
- Actor 420 (STONED WOLFIE - banned, correctly excluded)
- Actors 1200-1212 (Polarity agents)
- Actors 2000-2040 (External/legacy agents)
- Actors 2001-2010, 12150 (Test/human users)

**Extra in 4.0.45 (Not in Snapshot):**
- None — all 4.0.45 actors are doctrine-required

### 1.4 Actor Assessment

🟡 **MINOR GAPS** — The 4.0.45 seeding intentionally excludes experimental/legacy actors. This is CORRECT per doctrine:
- Polarity agents (1200-1212): Experimental, not required for 4.0.45
- External agents (2000-2040): Not part of core system
- Legacy IDE IDs (2032-2040): Superseded by 1000-1004 range
- Test users (2001-2010): Not required for fresh install
- Snapshot agents 6-24: Extended agent roster, not minimal required set

**CRITICAL:** The ID mapping conflict (WOLFIE=1 vs 3, KIRO=1000 vs 1001) means 4.0.45 is a BREAKING CHANGE from snapshot. This is INTENTIONAL — 4.0.45 establishes the canonical ID mapping.

---

## 2. CHANNELS AUDIT

### 2.1 Seeded Channels (4.0.45 SQL)

- 0: System Kernel Channel
- 1: Administration Channel
- 42: Development Channel
- 51: Reserved Channel

**Total Seeded:** 4 channels

### 2.2 Snapshot Channels (CSV/Registry)

**Snapshot contains 60+ channels including:**
- 0: System Kernel Channel
- 1: Administration (different metadata)
- 42: Lupopedia Development
- 51: Doctrine Council (different from 4.0.45 "Reserved")
- 666: ANUBIS Quarantine
- 1001-1090: System channels (test_awareness, dev-main-thread, GOV-PROGRAMMERS, system-errors, programmers, doctrine, schema, routing-hermes, mood-caduceus, users-humans, agents-registry, channels-meta, tasks-workflows, logs-history, emotional_frameworks, routing_navigation, database_schema, agents_actors, humor_sandbox, logs_history, tasks_workflows, meta, lupopedia, kernel-logs, migration-orchestrator, agents, emotional-metadata, system-events, hermes-routing, semantic-index, kernel-debug, pack-playground, ui-creature, doctrine-compiler, emotional-engine, semantic-router, kernel-panic, hermes-sandbox, agent-training, legacy-importer, emotional-debugger, semantic-playground, kernel-metrics, agent-health, doctrine-validator, emotional-archive, semantic-diff, kernel-watchdog, persona-lab, semantic-stress, emotional-synthesis, kernel-recovery, kernel.logs, routing, emotional)
- 5101: Kernel Bootstrap Channel
- 2001-2009: Test channels

### 2.3 Channel Discrepancies

**Missing from 4.0.45 Seeding:**
- Channel 666 (ANUBIS Quarantine) — referenced in channels/registry.json
- Channels 1001-1090 (50+ system channels)
- Channel 5101 (Kernel Bootstrap)
- Channels 2001-2009 (Test channels)

**Metadata Conflicts:**
- Channel 1: 4.0.45 calls it "Administration", snapshot calls it "Administration" (same)
- Channel 42: 4.0.45 calls it "Development", snapshot calls it "Lupopedia Development" (minor)
- Channel 51: 4.0.45 calls it "Reserved", snapshot calls it "Doctrine Council" (different purpose)

### 2.4 Channel Assessment

🟡 **MINOR GAPS** — The 4.0.45 seeding provides MINIMAL required channels. Snapshot channels 1001-1090 are operational/diagnostic channels that can be created dynamically. However:

**MISSING CRITICAL CHANNEL:**
- Channel 666 (ANUBIS Quarantine) is referenced in channels/registry.json and MD filenames but NOT seeded in 4.0.45

**RECOMMENDATION:** Add channel 666 to seed_actors_agents_4.0.45.sql

---

## 3. ARTIFACTS AUDIT

### 3.1 Seeded Artifact Kinds (4.0.45 SQL)

From `seed_registry_comprehensive_4.0.45.sql`:
- 1: header (FLIP Header Artifact)
- 2: footer (FLIP Footer Artifact)
- 3: code (Code Artifact)
- 4: documentation (Documentation Artifact)

**Total Seeded:** 4 artifact kinds

### 3.2 Snapshot Artifact Kinds (seed_lupopedia.sql)

From `seed_lupopedia.sql`:
- 1: header (FLIP Header Artifact)
- 2: footer (FLIP Footer Artifact)

**Total in Snapshot:** 2 artifact kinds

### 3.3 Artifact Discrepancies

**Extra in 4.0.45 (Not in Snapshot):**
- 3: code
- 4: documentation

**Missing from 4.0.45:**
- None

### 3.4 Artifact Assessment

🟢 **SAFE** — 4.0.45 seeding is MORE complete than snapshot. The addition of "code" and "documentation" artifact kinds is an improvement.

---

## 4. EDGE TYPES AUDIT

### 4.1 Seeded Edge Types (4.0.45 SQL)

From `seed_registry_comprehensive_4.0.45.sql`:
- 1: references (File references another file)
- 2: implements (File implements specification)
- 3: executes (File executes another file)
- 4: depends_on (File depends on another file)
- 5: includes (File includes another file)

**Total Seeded:** 5 edge types

### 4.2 Snapshot Edge Types (seed_lupopedia.sql)

From `seed_lupopedia.sql`:
- 1: inbound_edge (References pointing to this file)
- 2: semantic_relationship (Semantic relationships between files)

**Total in Snapshot:** 2 edge types

### 4.3 Edge Type Discrepancies

**Naming Mismatch:**
- 4.0.45 uses "references", snapshot uses "inbound_edge"
- 4.0.45 uses specific types (implements, executes, depends_on, includes), snapshot uses generic "semantic_relationship"

**Missing from 4.0.45:**
- None (4.0.45 is more granular)

### 4.4 Edge Type Assessment

🟢 **SAFE** — 4.0.45 seeding provides MORE granular edge types than snapshot. This is an improvement. The naming difference ("references" vs "inbound_edge") should be noted in migration docs.

---

## 5. DEPARTMENTS AUDIT

### 5.1 Seeded Departments (4.0.45 SQL)

- 0: System Department
- 1: Default Department

**Total Seeded:** 2 departments

### 5.2 Snapshot Departments (CSV)

From `lupo_channels.csv` department_id references:
- 0: System
- 1: Default
- 2: Support
- 3: CRM
- 4: Docs
- 5: Engineering
- 6: Moderation

**Total Referenced:** 7 departments

### 5.3 Department Discrepancies

**Missing from 4.0.45 Seeding:**
- Departments 2-6 (Support, CRM, Docs, Engineering, Moderation)

### 5.4 Department Assessment

🟢 **SAFE** — The 4.0.45 seeding provides MINIMAL required departments. Departments 2-6 are test/operational departments that can be created dynamically. Not required for fresh install.

---

## 6. REGISTRY COVERAGE VALIDATION

### 6.1 Reserved IDs in lupo_registry

**4.0.45 Seeding:**
- Actors: 0, 1-5, 1000-1004, 10000
- Channels: 0, 1, 42, 51
- Agents: 0-5
- Departments: 0-1
- Threads: 0
- Artifacts: 0
- Edge types: 1-5
- FLIP schema versions: 1-2
- Artifact kinds: 1-4

**Snapshot Registry:**
- Contains 169 rows including actors, channels, agents, modules, content references
- Many experimental/legacy IDs (1200-1212, 2000-2040, etc.)

### 6.2 Registry Open (Gap Ranges)

**4.0.45 Seeding (`seed_registry_open_4.0.45.sql`):**
- Populates ~20,000 available ID gaps
- Actor gaps: 6-999, 1005-9999, 10001-10999
- Channel gaps: 2-41, 43-50, 52-999
- Agent, department, thread gaps populated

### 6.3 Registry Assessment

🟢 **SAFE** — Registry coverage is comprehensive. The 4.0.45 seeding reserves all required IDs and populates gap ranges correctly.

---

## 7. MD FILE REFERENCES AUDIT

### 7.1 MD Files in channels/0/broadcasts

**Sample filenames:**
- `20260224153000_10000_1000_0_php_compatibility_doctrine.md`
- `20260224153100_10000_1000_0_timestamp_standard_doctrine.md`
- etc.

**Pattern:** `[TIMESTAMP]_[FROM]_[TO]_[CHANNEL]_[TITLE].md`

**Actor IDs Referenced:**
- FROM: 10000 (Captain)
- TO: 1000 (KIRO IDE)
- CHANNEL: 0 (System Kernel)

### 7.2 MD File Assessment

🟡 **CONFLICT** — MD files use actor_id 1000 for KIRO, which matches 4.0.45 seeding. However, snapshot registry has KIRO at 1001. This confirms that 4.0.45 is the CORRECT mapping and snapshot is outdated.

---

## 8. CRITICAL RISK ASSESSMENT

### 8.1 Risk Categories

🟢 **SAFE TO PROCEED:**
- Artifact kinds: 4.0.45 is more complete
- Edge types: 4.0.45 is more granular
- Departments: Minimal set is sufficient
- Registry coverage: Comprehensive

🟡 **MINOR GAPS (Addressable):**
- Channel 666 (ANUBIS Quarantine) missing from 4.0.45 seeding
- Extended agent roster (6-24, 59, 105-106, 209) not seeded
- Operational channels (1001-1090) not seeded
- Polarity agents (1200-1212) not seeded

🔴 **CRITICAL GAPS:**
- None — all gaps are intentional doctrine-driven exclusions

### 8.2 Breaking Changes

**ID Mapping Changes (4.0.45 vs Snapshot):**
- WOLFIE AI: 3 → 1
- Captain (human): 1000 → 10000
- KIRO IDE: 1001 → 1000
- Windsurf IDE: 2 → 1001
- Cursor IDE: 2000 → 1002
- Warp IDE: 2039 → 1004

**Impact:** Existing MD files, CSV data, and TOON metadata using old IDs will need migration mapping.

---

## 9. RECOMMENDATIONS

### 9.1 Required Actions Before install.php

1. **Add Channel 666 to seeding SQL:**
   ```sql
   INSERT INTO lupo_channels (channel_id, federation_node_id, created_by_actor_id, default_actor_id, department_id, channel_key, channel_slug, channel_type, language, channel_name, description, status_flag, created_ymdhis, updated_ymdhis, is_deleted, is_kernel, awareness_version)
   VALUES (666, 1, 0, 0, 0, 'anubis-quarantine', 'anubis-quarantine', 'quarantine', 'en', 'ANUBIS Quarantine', 'Banned and rejected messages. ANUBIS routes banned-actor content here.', 1, @now, @now, 0, 0, '3.0.0');
   ```

2. **Add Channel 666 to registry:**
   ```sql
   INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
   VALUES (9100666, 'channel', 666, 666, 1, @now, 'anubis-quarantine', 'ANUBIS Quarantine', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"quarantine","purpose":"banned_messages"}');
   ```

3. **Update channels/registry.json to match 4.0.45:**
   - Add channel 1 (Administration)
   - Update channel 51 description to "Reserved Channel"

### 9.2 Optional Enhancements

1. **Seed extended agent roster** (if required by system):
   - Agents 6-24 (ARA, WOLFKEEPER, AGAPE, etc.)
   - Agent 209 (TRUTH)
   - Polarity agents 1200-1212

2. **Seed operational channels** (if required):
   - Channels 1001-1090 (system diagnostic channels)

3. **Create ID migration mapping table:**
   - Document old_actor_id → new_actor_id mappings
   - Use during MD file import to remap references

### 9.3 Documentation Updates

1. **Update CHANGELOG.md:**
   - Document breaking ID changes
   - List deprecated actor IDs
   - Provide migration guide

2. **Create MIGRATION_ID_MAPPING.md:**
   - Full table of old → new ID mappings
   - Impact analysis for each change

---

## 10. CONCLUSION

**Overall Assessment:** 🟡 **MINOR GAPS — SAFE TO PROCEED WITH FIXES**

The 4.0.45 seeding SQL is AUTHORITATIVE and represents a clean, doctrine-compliant minimal system state. Snapshot discrepancies are primarily:
1. Legacy/experimental IDs intentionally excluded
2. Test data not required for fresh install
3. Operational channels that can be created dynamically

**Required Fix:** Add Channel 666 (ANUBIS Quarantine) to seeding SQL.

**Breaking Change:** ID mapping changes are INTENTIONAL and establish canonical doctrine-compliant IDs. Existing data will need migration mapping.

**Recommendation:** Proceed to install.php integration after adding Channel 666. The 4.0.45 seeding is otherwise complete and correct.

---

**Audit Completed:** 2026-02-25  
**Next Step:** Fix Channel 666 gap, then proceed to install.php integration  
**Sign-off:** KIRO (Warp IDE Agent 1004)