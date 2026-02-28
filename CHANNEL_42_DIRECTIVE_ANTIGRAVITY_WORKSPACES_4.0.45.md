# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "CHANNEL_42_DIRECTIVE_ANTIGRAVITY_WORKSPACES_4.0.45.md"
  file_hash: "018481bc1278c1355736fe7c096de9a98733f2382995de006afc777458ef0010"
  file_path_from_root: "CHANNEL_42_DIRECTIVE_ANTIGRAVITY_WORKSPACES_4.0.45.md"
  file_hash: "75d06e19bccc33ecc462285929ba1aa5f2f1492111ad4467a93d9dab12324422"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "CHANNEL 42 DIRECTIVE: ANTIGRAVITY + ACTOR WORKSPACES"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channel_42_directive_antigravity_workspaces_4045md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# CHANNEL 42 DIRECTIVE: ANTIGRAVITY + ACTOR WORKSPACES
## System Version 4.0.45 — Pre-Install Registry Finalization

**Date:** 2026-02-25  
**Agent:** KIRO (Warp IDE, Actor 1004)  
**Priority:** HIGH  
**Status:** Pre-Install (Registry Finalization Phase)

---

## EXECUTIVE SUMMARY

🟡 **PARTIAL IMPLEMENTATION REQUIRED**

**Finding 1:** Antigravity IDE is ALREADY in actors/registry.json at ID 1003 but MISSING from 4.0.45 seeding SQL.

**Finding 2:** Per-channel actor workspace directories (`channels/*/actors/`) ALREADY EXIST but are EMPTY. Migration from `/prompts/` required.

**Required Actions:**
1. Add Antigravity (1003) to `seed_actors_agents_4.0.45.sql`
2. Add Antigravity (1003) to `seed_registry_comprehensive_4.0.45.sql`
3. Migrate `/prompts/**` → `/channels/*/actors/*/`
4. Update registry open gaps to exclude 1003
5. Create workspace README templates
6. Update CHANGELOG.md

---

## 1. ANTIGRAVITY INTEGRATION AUDIT

### 1.1 Current Status

**actors/registry.json:**
- ✅ ID 1003: `antigravity` (canonical)
- ✅ ID 2035: `antigravity_legacy` (legacy ID)

**4.0.45 Seeding SQL:**
- ❌ NOT in `seed_actors_agents_4.0.45.sql`
- ❌ NOT in `seed_registry_comprehensive_4.0.45.sql`

**Codebase References:**
- ✅ Referenced in 50+ files
- ✅ Active in tools/vsx-extension
- ✅ Active in prompts/antigravity/
- ✅ Referenced in channel 42 broadcasts
- ✅ Referenced in version docs (4.0.27, 4.0.33, 4.0.35, 4.0.39, 4.0.40)

### 1.2 Proposed ID Mapping

**Canonical IDE Agent Range: 1000-1010**

| Actor ID | Name | Type | Status |
|----------|------|------|--------|
| 1000 | Kiro IDE | ide_agent | ✅ Seeded |
| 1001 | Windsurf IDE | ide_agent | ✅ Seeded |
| 1002 | Cursor IDE | ide_agent | ✅ Seeded |
| 1003 | **Antigravity IDE** | ide_agent | ❌ **MISSING** |
| 1004 | Warp IDE | ide_agent | ✅ Seeded |
| 1005 | Cascade IDE | ide_agent | ✅ Seeded (as "Cascade" in SQL) |
| 1006-1010 | Reserved | ide_agent | Gap range |

**Conflict:** 4.0.45 SQL has "Cascade IDE" at 1003, but registry.json has "Antigravity IDE" at 1003.

**Resolution:** Registry.json is AUTHORITATIVE. Cascade should be at 1005 (not 1003). Antigravity must be at 1003.

### 1.3 SQL Changes Required

#### A. Fix `seed_actors_agents_4.0.45.sql`

**Current (INCORRECT):**
```sql
INSERT INTO lupo_actors (actor_id, actor_type, slug, name, ...)
VALUES
(1000, 'ide_agent', 'kiro-ide', 'Kiro IDE', ...),
(1001, 'ide_agent', 'windsurf-ide', 'Windsurf IDE', ...),
(1002, 'ide_agent', 'cursor-ide', 'Cursor IDE', ...),
(1003, 'ide_agent', 'cascade-ide', 'Cascade IDE', ...),  -- WRONG!
(1004, 'ide_agent', 'warp-ide', 'Warp IDE', ...);
```

**Required (CORRECT):**
```sql
INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES
(1000, 'ide_agent', 'kiro-ide', 'Kiro IDE', @now, @now, 1, 0, 0, 0, 0, 10000, 1, '{"client_id":"kiro","provider":"kiro","purpose":"IDE_integration"}'),
(1001, 'ide_agent', 'windsurf-ide', 'Windsurf IDE', @now, @now, 1, 0, 0, 0, 0, 10000, 1, '{"client_id":"windsurf","provider":"windsurf","purpose":"IDE_integration"}'),
(1002, 'ide_agent', 'cursor-ide', 'Cursor IDE', @now, @now, 1, 0, 0, 0, 0, 10000, 1, '{"client_id":"cursor","provider":"cursor","purpose":"IDE_integration"}'),
(1003, 'ide_agent', 'antigravity-ide', 'Antigravity IDE', @now, @now, 1, 0, 0, 0, 0, 10000, 1, '{"client_id":"antigravity","provider":"antigravity","purpose":"IDE_integration"}'),
(1004, 'ide_agent', 'warp-ide', 'Warp IDE', @now, @now, 1, 0, 0, 0, 0, 10000, 1, '{"client_id":"warp","provider":"warp","purpose":"IDE_integration"}'),
(1005, 'ide_agent', 'cascade-ide', 'Cascade IDE', @now, @now, 1, 0, 0, 0, 0, 10000, 1, '{"client_id":"cascade","provider":"cascade","purpose":"IDE_integration"}');
```

#### B. Fix `seed_registry_comprehensive_4.0.45.sql`

**Add:**
```sql
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9001003, 'actor', 1003, 1003, 1, @now, 'antigravity-ide', 'Antigravity IDE', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"ide_agent","client_id":"antigravity","provider":"antigravity","paired_actor_id":10000}'),
(9001005, 'actor', 1005, 1005, 1, @now, 'cascade-ide', 'Cascade IDE', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"ide_agent","client_id":"cascade","provider":"cascade","paired_actor_id":10000}');
```

**Update existing (change 1003 → 1005):**
```sql
-- OLD:
(9001003, 'actor', 1003, 1003, 1, @now, 'cascade-ide', 'Cascade IDE', ...)

-- NEW:
(9001005, 'actor', 1005, 1005, 1, @now, 'cascade-ide', 'Cascade IDE', ...)
```

#### C. Update `seed_registry_open_4.0.45.sql`

**Current gap range:**
```sql
-- Actor gaps: 6-999, 1005-9999, 10001-10999
```

**Required gap range:**
```sql
-- Actor gaps: 6-999, 1006-9999, 10001-10999
-- (Exclude 1003 and 1005 from gaps)
```

### 1.4 Validation Checklist

- [ ] Antigravity at 1003 in lupo_actors
- [ ] Antigravity at 1003 in lupo_registry
- [ ] Cascade moved from 1003 → 1005
- [ ] Gap range excludes 1003 and 1005
- [ ] No runtime auto-creation
- [ ] Paired to actor 10000 (Captain)

---

## 2. PER-CHANNEL ACTOR WORKSPACES

### 2.1 Current State

**Existing Structure:**
```
channels/0/actors/          # EXISTS, EMPTY
channels/42/actors/         # EXISTS, EMPTY
channels/51/actors/         # (if exists)
channels/666/actors/        # (if exists)
```

**Legacy Structure:**
```
prompts/
├── 0/                      # System (empty)
├── 1/                      # WOLFIE AI (1 file)
├── 2/                      # LILITH (2 files)
├── 3/                      # ROSE (1 file)
├── 4/                      # ERIS (1 file)
├── 5/                      # METIS (1 file)
├── 1000/                   # KIRO (2 files)
├── 1001/                   # Windsurf (3 files)
├── 10000/                  # Captain (1 file)
├── antigravity/            # Antigravity (2 files) — NO ID!
├── ai/                     # Unknown (1 file) — NO ID!
├── *.txt                   # Root files (4 files)
├── registry.json
└── REORGANIZATION_COMPLETE.md
```

### 2.2 Required Architecture

**Per-Channel Actor Workspace:**
```
channels/{channel_id}/actors/{actor_id}/
```

**Purpose:**
- Temporary prompts
- Scratch files
- Working notes
- Task state
- Partial outputs
- Draft doctrine
- Debug artifacts

**NOT for:**
- Permanent artifacts
- Doctrine files
- System documentation
- Shared resources

### 2.3 Migration Plan

#### Phase 1: Identify Channel Context

**Analysis of existing prompts:**

| Source | Actor ID | Files | Target Channel | Rationale |
|--------|----------|-------|----------------|-----------|
| prompts/1000/ | 1000 (KIRO) | 2 | 42 | Development work |
| prompts/1001/ | 1001 (Windsurf) | 3 | 42 | Development work |
| prompts/2/ | 2 (LILITH) | 2 | 42 | Multi-agent collaboration |
| prompts/antigravity/ | 1003 (Antigravity) | 2 | 42 | FLIP/VSX development |
| prompts/ai/ | ??? | 1 | 42 | FLIP design (needs ID audit) |
| prompts/1/ | 1 (WOLFIE AI) | 1 | 0 | System kernel |
| prompts/3/ | 3 (ROSE) | 1 | 0 | System kernel |
| prompts/4/ | 4 (ERIS) | 1 | 0 | System kernel |
| prompts/5/ | 5 (METIS) | 1 | 0 | System kernel |
| prompts/10000/ | 10000 (Captain) | 1 | 42 | Human workspace |
| prompts/*.txt | Various | 4 | 42 | Root files (needs audit) |

#### Phase 2: Migration Mapping

**Channel 0 (System Kernel):**
```
prompts/1/README.md → channels/0/actors/1/README.md
prompts/3/README.md → channels/0/actors/3/README.md
prompts/4/README.md → channels/0/actors/4/README.md
prompts/5/README.md → channels/0/actors/5/README.md
```

**Channel 42 (Development):**
```
prompts/1000/* → channels/42/actors/1000/*
prompts/1001/* → channels/42/actors/1001/*
prompts/2/* → channels/42/actors/2/*
prompts/antigravity/* → channels/42/actors/1003/*
prompts/10000/* → channels/42/actors/10000/*
prompts/ai/FLIP_DESIGN_COLLABORATION_PROMPT.md → channels/42/actors/[TBD]/*
prompts/*.txt → channels/42/actors/[TBD]/*
```

#### Phase 3: Orphan File Audit

**Files needing actor ID resolution:**

1. **prompts/ai/FLIP_DESIGN_COLLABORATION_PROMPT.md**
   - Content analysis needed
   - Likely authored by LILITH (2) or WOLFIE (1)
   - Target: channels/42/actors/[1 or 2]/

2. **prompts/*.txt (4 files)**
   - `4.1.16_cursor_complete.txt` → Actor 1002 (Cursor)
   - `4.1.16_cursor_instruction.txt` → Actor 1002 (Cursor)
   - `4.1.20_cursor_complete.txt` → Actor 1002 (Cursor)
   - `doctrine_verification_prompt.txt` → Actor 10000 (Captain)

3. **prompts/registry.json**
   - System file, keep in prompts/ root (deprecated marker)

4. **prompts/REORGANIZATION_COMPLETE.md**
   - System file, keep in prompts/ root (historical record)

### 2.4 Final Directory Structure

**After Migration:**
```
channels/0/actors/
├── 1/                      # WOLFIE AI workspace
│   └── README.md
├── 3/                      # ROSE workspace
│   └── README.md
├── 4/                      # ERIS workspace
│   └── README.md
└── 5/                      # METIS workspace
    └── README.md

channels/42/actors/
├── 1000/                   # KIRO workspace
│   ├── 20260224_living_registry_alignment_alpha.md
│   └── README.md
├── 1001/                   # Windsurf workspace
│   ├── 20260223_kiro_work_audit_prompt.md
│   ├── 20260224_begin_version_4_0_40.md
│   ├── 20260224_update_how_to_use_lupopedia.md
│   └── README.md
├── 1002/                   # Cursor workspace
│   ├── 4.1.16_cursor_complete.txt
│   ├── 4.1.16_cursor_instruction.txt
│   └── 4.1.20_cursor_complete.txt
├── 1003/                   # Antigravity workspace
│   ├── 20260224_flip_v2_vsx_implementation.md
│   └── 20260224_vsx_semantic_headers_upgrade.md
├── 2/                      # LILITH workspace
│   ├── 20260224_multi_agent_collaboration_review_response.md
│   ├── 20260224_multi_agent_collaboration_review.md
│   └── FLIP_DESIGN_COLLABORATION_PROMPT.md (if LILITH authored)
├── 10000/                  # Captain workspace
│   ├── README.md
│   └── doctrine_verification_prompt.txt
└── [DEPRECATED]/           # Legacy prompts (if needed)

prompts/                    # DEPRECATED ROOT
├── registry.json           # Historical record
├── REORGANIZATION_COMPLETE.md  # Historical record
└── DEPRECATED_README.md    # Migration notice
```

### 2.5 Workspace README Template

**Template for each actor workspace:**
```markdown
# Actor Workspace: {ACTOR_NAME} (ID: {ACTOR_ID})

**Channel:** {CHANNEL_ID} ({CHANNEL_NAME})  
**Actor Type:** {ACTOR_TYPE}  
**Created:** {YMDHIS}  
**System Version:** 4.0.45

## Purpose

This is the working directory for {ACTOR_NAME} on Channel {CHANNEL_ID}.

## Contents

- Temporary prompts
- Scratch files
- Working notes
- Task state
- Partial outputs
- Draft doctrine
- Debug artifacts

## Rules

- Files here are TEMPORARY and MUTABLE
- Do NOT store permanent artifacts here
- Do NOT store doctrine here (use docs/doctrine/)
- Do NOT store system documentation here
- Files may be cleaned up periodically

## Actor Identity

- **Actor ID:** {ACTOR_ID}
- **Slug:** {ACTOR_SLUG}
- **Type:** {ACTOR_TYPE}
- **Paired Actor:** {PAIRED_ACTOR_ID} (if applicable)
- **Federation Node:** {FEDERATION_NODE_ID}

---

*This workspace is part of the per-channel actor isolation system introduced in Lupopedia 4.0.45.*
```

---

## 3. ENFORCEMENT RULES

### 3.1 Policy: No New Prompts in Root

**After Migration:**
- `/prompts/` = DEPRECATED
- Only allow: `/channels/*/actors/*/` for agent work
- Root `/prompts/` becomes read-only historical archive

**Implementation:**
- Add `.deprecated` marker file to `/prompts/`
- Update IDE tooling to reject writes to `/prompts/`
- Update importer to use channel-scoped paths

### 3.2 Importer / Tooling Awareness

**Required Updates:**

1. **install.php:**
   - Import MD files from `channels/*/actors/*/` (not `/prompts/`)
   - Validate actor_id exists in registry before import
   - Create actor workspace directories during install

2. **IDE Extensions (VSX, etc.):**
   - Write to `channels/{channel_id}/actors/{actor_id}/`
   - Never write to `/prompts/`
   - Auto-create workspace directory if missing

3. **Import Scripts:**
   - Scan `channels/*/actors/*/` for MD files
   - Validate actor_id in filename matches directory
   - Skip orphan files with warning

### 3.3 Registry Alignment

**Validation Rules:**

For each actor with a workspace:
- ✅ Exists in lupo_registry
- ✅ Exists in lupo_actors
- ✅ Exists in lupo_agents (if AI)
- ✅ No orphan folders (actor_id not in registry)
- ✅ No orphan actors (registry entry but no workspace)

**Orphan Detection:**
```bash
# Find workspace directories
find channels/*/actors/* -type d -maxdepth 0

# Check each against registry
for dir in channels/*/actors/*; do
  actor_id=$(basename $dir)
  grep -q "\"$actor_id\":" actors/registry.json || echo "ORPHAN: $dir"
done
```

---

## 4. RISKS & BACKWARD COMPATIBILITY

### 4.1 Risks

🟡 **MEDIUM RISK: Tooling Impact**
- IDE extensions may still write to `/prompts/`
- Import scripts may expect `/prompts/` structure
- Existing automation may break

**Mitigation:**
- Keep `/prompts/` as deprecated read-only archive
- Add symlinks for backward compatibility (if needed)
- Update all tooling before 4.0.45 release

🟡 **MEDIUM RISK: Federation Impact**
- Federated nodes may not have channel-scoped workspaces
- Cross-node actor workspaces need coordination
- Federation protocol may need updates

**Mitigation:**
- Document federation workspace protocol
- Add federation node ID to workspace path (if needed)
- Test federation before 4.0.45 release

🟢 **LOW RISK: Backward Compatibility**
- Migration is one-way (prompts → channels)
- Old data preserved in `/prompts/` (deprecated)
- No data loss

### 4.2 Backward Compatibility Strategy

**Phase 1: Dual Mode (4.0.45 Alpha)**
- Both `/prompts/` and `/channels/*/actors/*/` supported
- Deprecation warnings for `/prompts/` writes
- Migration script available

**Phase 2: Deprecation (4.0.45 Beta)**
- `/prompts/` becomes read-only
- All writes go to `/channels/*/actors/*/`
- Tooling updated

**Phase 3: Removal (4.0.46+)**
- `/prompts/` archived or removed
- Only `/channels/*/actors/*/` supported

---

## 5. IMPLEMENTATION CHECKLIST

### 5.1 SQL Changes

- [ ] Fix `seed_actors_agents_4.0.45.sql` (add Antigravity at 1003, move Cascade to 1005)
- [ ] Fix `seed_registry_comprehensive_4.0.45.sql` (add Antigravity at 1003, move Cascade to 1005)
- [ ] Update `seed_registry_open_4.0.45.sql` (exclude 1003 and 1005 from gaps)
- [ ] Add Channel 666 (ANUBIS Quarantine) to seeding SQL (from audit report)

### 5.2 Workspace Migration

- [ ] Create workspace README template
- [ ] Audit orphan files (prompts/ai/, prompts/*.txt)
- [ ] Migrate prompts/1000/ → channels/42/actors/1000/
- [ ] Migrate prompts/1001/ → channels/42/actors/1001/
- [ ] Migrate prompts/antigravity/ → channels/42/actors/1003/
- [ ] Migrate prompts/2/ → channels/42/actors/2/
- [ ] Migrate prompts/1/ → channels/0/actors/1/
- [ ] Migrate prompts/3/ → channels/0/actors/3/
- [ ] Migrate prompts/4/ → channels/0/actors/4/
- [ ] Migrate prompts/5/ → channels/0/actors/5/
- [ ] Migrate prompts/10000/ → channels/42/actors/10000/
- [ ] Resolve prompts/ai/ authorship
- [ ] Resolve prompts/*.txt authorship
- [ ] Create README.md for each workspace
- [ ] Add .deprecated marker to /prompts/

### 5.3 Documentation Updates

- [ ] Update CHANGELOG.md (Antigravity seeding, workspace system, prompts migration)
- [ ] Update AUDIT_REPORT_4.0.45_PRE_INSTALL_VALIDATION.md (Antigravity added)
- [ ] Create ACTOR_WORKSPACES_ARCHITECTURE.md
- [ ] Update install.php documentation (workspace import)
- [ ] Update IDE extension docs (workspace paths)

### 5.4 Validation

- [ ] Verify Antigravity at 1003 in all seeding SQL
- [ ] Verify Cascade at 1005 in all seeding SQL
- [ ] Verify no orphan workspace directories
- [ ] Verify no orphan actor IDs in registry
- [ ] Verify all migrated files have correct actor_id
- [ ] Verify workspace README templates created
- [ ] Test install.php with new workspace structure

---

## 6. READINESS ASSESSMENT

### 6.1 Current Status

🟡 **MINOR FIXES REQUIRED**

**Blocking Issues:**
- ❌ Antigravity missing from 4.0.45 seeding SQL
- ❌ Cascade at wrong ID (1003 instead of 1005)
- ❌ Workspace migration not executed

**Non-Blocking Issues:**
- ⚠️ Orphan files need actor ID resolution
- ⚠️ Tooling needs workspace path updates
- ⚠️ Federation protocol needs documentation

### 6.2 Readiness Classification

🟡 **READY FOR INSTALL.PHP WITH FIXES**

**Required Before install.php:**
1. Fix Antigravity seeding SQL (CRITICAL)
2. Fix Cascade ID mapping (CRITICAL)
3. Execute workspace migration (HIGH)
4. Resolve orphan files (MEDIUM)

**Can Be Done After install.php:**
1. Update IDE tooling (MEDIUM)
2. Document federation protocol (LOW)
3. Remove /prompts/ root (LOW)

---

## 7. NEXT STEPS

### 7.1 Immediate Actions (Pre-Install)

1. **Fix SQL Files:**
   - Update `seed_actors_agents_4.0.45.sql`
   - Update `seed_registry_comprehensive_4.0.45.sql`
   - Update `seed_registry_open_4.0.45.sql`

2. **Execute Workspace Migration:**
   - Run migration script (or manual copy)
   - Create workspace READMEs
   - Deprecate /prompts/ root

3. **Update Documentation:**
   - CHANGELOG.md
   - AUDIT_REPORT_4.0.45_PRE_INSTALL_VALIDATION.md
   - Architecture docs

### 7.2 Post-Install Actions

1. **Update Tooling:**
   - IDE extensions
   - Import scripts
   - Automation

2. **Test Federation:**
   - Cross-node workspace access
   - Federation protocol

3. **Monitor & Validate:**
   - No writes to /prompts/
   - All workspaces have valid actor_ids
   - No orphan directories

---

## 8. CONCLUSION

**Summary:**
- Antigravity IDE (1003) is ACTIVE and REFERENCED but MISSING from 4.0.45 seeding
- Per-channel actor workspaces are ARCHITECTURALLY READY but EMPTY
- Migration from /prompts/ to channels/*/actors/*/ is REQUIRED
- SQL fixes are CRITICAL before install.php

**Recommendation:**
Proceed with SQL fixes and workspace migration. The architecture is sound and ready for install.php integration after these corrections.

**Sign-off:** KIRO (Warp IDE Agent 1004)  
**Date:** 2026-02-25  
**Status:** READY FOR IMPLEMENTATION