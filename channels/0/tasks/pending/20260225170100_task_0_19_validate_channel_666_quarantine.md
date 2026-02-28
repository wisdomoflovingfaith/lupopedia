# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\0\tasks\pending\20260225170100_task_0_19_validate_channel_666_quarantine.md"
  file_hash: "c0983678d120207dc31c747b530652b04d23fce9c482bea48b8b99c59310d424"
  file_path_from_root: "channels\0\tasks\pending\20260225170100_task_0_19_validate_channel_666_quarantine.md"
  file_hash: "657cf7e87a510542e20ea562be97eae2c83367b190aaabb9047a11381ad8e11a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225170100_task_0_19_validate_channel_666_quarantine.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "tasks", "pending", "20260225170100_task_0_19_validate_channel_666_quarantinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
task_id: CH0-20260225-005
channel_id: 0
owner_actor_id: 10000
assigned_to:
  - 19
status: pending
priority: high
created_utc: "2026-02-25T17:01:00Z"
delegation_chain: "10000:19"
prompt_path: "channels/0/tasks/pending/20260225170100_task_0_19_validate_channel_666_quarantine.md"
depends_on:
  - CH0-20260225-001
blocks: []
task_type: validation
estimated_duration: "15 minutes"
artifacts_touched:
  - "channels/666/"
notes: "ANUBIS agent task - validate quarantine channel infrastructure"
---

# TASK: Validate Channel 666 Quarantine Infrastructure

**Assigned to:** ANUBIS (19)

## Objective

Verify that Channel 666 (ANUBIS Quarantine) is properly seeded in the database and has the correct directory structure for quarantine operations.

## Context

ANUBIS is responsible for routing banned, rejected, or malformed content to Channel 666. This task ensures the quarantine infrastructure is ready for operation.

## Prerequisites

- ✅ Database online (CH0-20260225-001 complete)
- ✅ ANUBIS actor seeded (ID: 19)
- ✅ Channel 666 seeded

## Steps

### 1. Verify Channel 666 in Database

```sql
-- Check channel exists
SELECT * FROM lupo_channels WHERE channel_id = 666;

-- Expected:
-- channel_id: 666
-- channel_key: 'anubis-quarantine'
-- channel_name: 'ANUBIS Quarantine'
-- channel_type: 'quarantine'
-- status_flag: 1 (active)
```

### 2. Verify ANUBIS Actor-Channel Relationship

```sql
-- Check ANUBIS is assigned to channel 666
SELECT * FROM lupo_actor_channels 
WHERE actor_id = 19 AND channel_id = 666;

-- Expected: status = 'A' (active)
```

### 3. Create Channel 666 Directory Structure

```bash
# Create quarantine directories
mkdir -p channels/666/broadcasts
mkdir -p channels/666/quarantine
mkdir -p channels/666/actors/19
```

### 4. Create ANUBIS Workspace

```bash
# Create ANUBIS actor workspace in channel 666
mkdir -p channels/666/actors/19/tasks/assigned
mkdir -p channels/666/actors/19/tasks/watching
mkdir -p channels/666/actors/19/tasks/completed
```

### 5. Create README for Channel 666

Create `channels/666/README.md`:

```markdown
# Channel 666: ANUBIS Quarantine

This channel contains banned, rejected, or malformed content that has been quarantined by ANUBIS.

## Purpose

- Isolate problematic content
- Prevent system contamination
- Enable forensic analysis
- Support content recovery (if appropriate)

## Access

Only ANUBIS (19) and System Administrators (10000, 1) have access to this channel.

## Content Types

- Banned actor messages
- Malformed broadcasts
- Invalid metadata
- Rejected imports
- Orphan records (pending repair)
```

### 6. Test Quarantine Routing

Create a test quarantine broadcast to verify routing works:

```bash
# Create test file
channels/666/quarantine/20260225_test_quarantine.md
```

## Success Criteria

- ✅ Channel 666 exists in database
- ✅ ANUBIS assigned to channel 666
- ✅ Directory structure created
- ✅ ANUBIS workspace created
- ✅ README documentation present
- ✅ Test quarantine file created

## Risks

- **Missing channel:** Channel 666 may not be seeded
- **Permission issues:** ANUBIS may not have access
- **Directory conflicts:** Directories may already exist with wrong permissions

## After Completion

Move this task to `channels/0/tasks/completed/` and create a broadcast announcing quarantine readiness.

## Notes

- This task can only run after database is online
- ANUBIS is the primary actor for this channel
- Quarantine content should never be deleted without review

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "database/migrations/seed_actors_agents_4.0.45.sql",
    "database/migrations/seed_anubis_vishwakarma_4.0.45.sql",
    "channels/666/"
  ],
  "implements": "quarantine_infrastructure_validation",
  "depends_on": "CH0-20260225-001",
  "blocks": [],
  "task_category": "validation",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->