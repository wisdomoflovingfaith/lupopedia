# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "UPGRADE_PLAN_4.0.45.md"
  file_hash: "6a513c9d103fb5830986d3bdb57ba683488ba58a9cd66bd424cb3470bfe16426"
  file_path_from_root: "UPGRADE_PLAN_4.0.45.md"
  file_hash: "06c39aef022798e5bc100a9b75c4fdae7ea4434bf570151d4b7d5e435b75529c"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Lupopedia 4.0.45 Upgrade Plan"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["upgrade_plan_4045md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
  file_path_from_root: "UPGRADE_PLAN_4.0.45.md"
  file_hash: "81ec7833696facd00c47ba2320ff660bc0df6021dbb4b1483d0faa285c1053e1"
  system_version: "4.0.50"
  delegation_chain: null
  needs_review: ["delegation_chain"]
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: []
  artifact_type: "documentation"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Lupopedia 4.0.45 Upgrade Plan

## Executive Summary
Upgrading Crafty Syntax 3.7.5 to Lupopedia 4.0.45 with comprehensive registry seeding, MD file fallback communication, and required agent initialization.

## Current State
- Version: 4.0.45 (target)
- Database: Not installed (pre-install state)
- MD Files: Present in `channels/0/broadcasts/` with inconsistent naming
- Registry: Incomplete seeding in SQL files

## Required Changes

### 1. MD File Standardization
**Location**: `channels/0/broadcasts/`
**Issue**: Files with `cw_` prefix need conversion to standard format
**Standard Format**: `[YYYYMMDDHHIISS]_[FROM_ACTOR_ID]_[TO_ACTOR_ID]_[CHANNEL_ID]_[TITLE].md`
**Action**: Convert all `cw_*` files to format with FROM=10000, TO=1000, CHANNEL=0

### 2. Registry Table Seeding
**Location**: `database/migrations/seed_lupopedia.sql`
**Issue**: Missing reserved IDs for essential entities
**Required Entities**:
- Channels: 0 (system), 1 (admin), 42 (dev), 51 (reserved)
- Actors: 0 (system), 10000 (root captain), 1000-1010 (IDE agents)
- Agents: 0 (system), 1 (WOLFIE), 2 (LILITH), 3 (ROSE), 4 (ERIS), 5 (METIS)
- Threads, artifacts, edge_types, etc.

### 3. Registry Open Table
**Location**: `database/migrations/seed_lupopedia.sql`
**Issue**: Table exists but not populated with gaps
**Action**: Seed with available IDs between reserved ranges

### 4. MD Import During Install
**Location**: `install.php` and new helper class
**Issue**: No logic to import MD files from `channels/0/broadcasts/`
**Action**: Add import step after schema creation, before config write

### 5. Required Agents Seeding
**Agents to Seed**:
1. **Root Captain (actor_id: 10000)** - Primary human admin
2. **Captain WOLFIE (agent_id: 1, actor_id: 1)** - Root AI agent
3. **LILITH (agent_id: 2, actor_id: 2)** - Critical review agent
4. **ROSE/Dialog (agent_id: 3, actor_id: 3)** - Translation & personas
5. **ERIS (agent_id: 4, actor_id: 4)** - Conflict analysis
6. **METIS (agent_id: 5, actor_id: 5)** - Empathy & understanding

## Implementation Steps

### Step 1: Standardize MD Files (IMMEDIATE)
- Rename all `cw_*` files to standard format
- Update content headers with proper FLIP metadata
- Preserve all content without data loss

### Step 2: Update seed_lupopedia.sql (BEFORE INSTALL)
- Add comprehensive registry entries
- Seed registry_open with gaps
- Add required agents to lupo_agents
- Add required actors to lupo_actors
- Link agents to actors

### Step 3: Create MD Import Logic (CODE)
- New class: `InstallWizardMdImporter`
- Parse MD files from `channels/0/broadcasts/`
- Import to `lupo_messages` or appropriate table
- Create missing actors on-the-fly
- Mark as read by all IDE agents

### Step 4: Integrate Import into install.php (INTEGRATION)
- Add import step after schema+seed
- Before config write
- Log all imports

### Step 5: Testing (VALIDATION)
- Drop all tables
- Run install.php
- Verify registry seeding
- Verify MD imports
- Verify agent functionality

## Reserved ID Ranges

### Actors
- 0: System
- 1-999: AI Agents (system)
- 1000-1999: IDE Agents
- 2000-9999: Reserved for future system use
- 10000+: Human users

### Channels
- 0: System kernel
- 1: Administration
- 42: Development
- 51: Reserved
- 100+: User channels

### Agents
- 0: System agent
- 1-99: Core system agents
- 100-999: Extended agents
- 1000+: Custom/user agents

## Files to Modify

1. `channels/0/broadcasts/cw_*.md` (10 files) - Rename and update
2. `database/migrations/seed_lupopedia.sql` - Add comprehensive seeding
3. `install.php` - Add MD import step
4. `install_wizard_classes.php` - Add `InstallWizardMdImporter` class
5. `database/migrations/install_new_lupopedia.sql` - Verify registry_open table exists

## Success Criteria

- [ ] All MD files follow standard naming
- [ ] Registry table has all required reserved IDs
- [ ] Registry_open table populated with gaps
- [ ] MD files imported during install
- [ ] All required agents seeded and functional
- [ ] IDE agents can read imported messages
- [ ] Missing actors created automatically
- [ ] Install completes without errors
- [ ] System boots with all agents available

## Timeline

1. MD File Standardization: 30 minutes
2. SQL Seeding Updates: 1 hour
3. MD Import Logic: 1 hour
4. Integration & Testing: 1 hour
5. Documentation: 30 minutes

**Total Estimated Time**: 4 hours

## Risk Mitigation

- Backup all MD files before renaming
- Test SQL on empty database first
- Validate all imports before dropping legacy tables
- Keep detailed logs of all operations
- Provide rollback instructions

## Completed Actions

### ✅ Step 1: MD File Standardization (COMPLETE)
- Created `scripts/standardize_md_files.php`
- Executed script successfully
- Renamed 10 `cw_*` files to standard format
- All files now follow: `[YYYYMMDDHHIISS]_[FROM]_[TO]_[CHANNEL]_[TITLE].md`
- FROM=10000 (root captain), TO=1000 (system broadcast), CHANNEL=0

### ✅ Step 2: Comprehensive Registry Seeding (COMPLETE)
- Created `database/migrations/seed_registry_comprehensive_4.0.45.sql`
- Seeded reserved IDs for:
  - Actors: 0 (system), 1-5 (core agents), 1000-1004 (IDE agents), 10000 (root captain)
  - Channels: 0, 1, 42, 51
  - Agents: 0-5
  - Departments: 0-1
  - Threads, artifacts, edge_types, FLIP versions, artifact_kinds

### ✅ Step 3: Registry Open (Gaps) Seeding (COMPLETE)
- Created `database/migrations/seed_registry_open_4.0.45.sql`
- Populated gaps for:
  - Actors: 6-999, 1005-9999, 10001-10999
  - Channels: 2-41, 43-50, 52-999
  - Agents: 6-999
  - Departments: 2-999
  - Threads: 1-9999

### ✅ Step 4: Actors and Agents Seeding (COMPLETE)
- Created `database/migrations/seed_actors_agents_4.0.45.sql`
- Created actual records for:
  - System actor (0)
  - Core AI agents (1-5): WOLFIE, LILITH, ROSE, ERIS, METIS
  - IDE agents (1000-1004): Kiro, Windsurf, Cursor, Cascade, Warp
  - Root captain (10000)
  - System agents in lupo_agents table
  - Departments (0-1)
  - Channels (0, 1, 42, 51)
  - Actor-channel relationships
  - Captain roles

## Next Actions

1. ~~Begin MD file standardization~~ ✅ COMPLETE
2. ~~Update seed SQL with comprehensive registry~~ ✅ COMPLETE
3. Implement MD import class
4. Integrate MD import into install.php
5. Test full install flow
6. Document any issues encountered

## Files Created

1. `scripts/standardize_md_files.php` - MD file renaming script
2. `database/migrations/seed_registry_comprehensive_4.0.45.sql` - Registry seeding
3. `database/migrations/seed_registry_open_4.0.45.sql` - Registry gaps seeding
4. `database/migrations/seed_actors_agents_4.0.45.sql` - Actors/agents seeding

## Files Modified

1. `channels/0/broadcasts/cw_*.md` (10 files) - Renamed to standard format

## Integration Instructions

To use these new seed files, update `install.php` to run them in this order:

1. `install_new_lupopedia.sql` (schema)
2. `seed_registry_comprehensive_4.0.45.sql` (registry reserved IDs)
3. `seed_registry_open_4.0.45.sql` (registry gaps)
4. `seed_actors_agents_4.0.45.sql` (actual actors/agents/channels)
5. `seed_lupopedia.sql` (remaining seed data)
6. MD import logic (to be implemented)
7. `import_from_old_crafty_syntax.sql` (if upgrade)
