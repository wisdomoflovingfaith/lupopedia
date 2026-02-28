# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "OFFLINE_GOVERNANCE_MODEL_4.0.45.md"
  file_hash: "8c74df03b4273a4562029ba880fca314d507c679c924ef41bb5df4f947fac00f"
  file_path_from_root: "OFFLINE_GOVERNANCE_MODEL_4.0.45.md"
  file_hash: "f6bc484e6017566a7e06d93326394463742f2d0672c5700d7ae3637e824def5a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for OFFLINE_GOVERNANCE_MODEL_4.0.45.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["offline_governance_model_4045md"]
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
wolfie.headers:
  file_path_from_root: "OFFLINE_GOVERNANCE_MODEL_4.0.45.md"
  system_version: "4.0.45"
  channel_id: 0
  purpose: "Offline Governance Model Documentation"
  last_modified: "20260225"
  actor_id: 1004
  artifact_type: "documentation"
  artifact_kind: "governance_model"
  created_utc: "2026-02-25T08:50:00Z"
---

# OFFLINE GOVERNANCE MODEL (4.0.45)

**Status:** Active  
**Effective:** Version 4.0.45+  
**Authority:** Captain (10000)  
**Author:** Warp IDE (1004)

## 🎯 OBJECTIVE

While the database is offline, Lupopedia operates from filesystem state. This document defines the offline governance model that enables multi-agent coordination without database access.

## 🏗️ ARCHITECTURE

### Channels as Mini-Operating Systems

Each channel is a self-contained coordination environment with:

```
/channels/{channel_id}/
├── broadcasts/          → Announcements and system messages
├── actors/              → Actor workspaces
├── directives/          → Authority and policy documents
├── tasks/               → Coordination layer (NEW)
└── roles/               → Role-based access control (NEW)
```

### Central Task Management

Tasks are **centrally managed per channel** (shared state):

```
/channels/{channel_id}/tasks/
├── active/              → Currently in progress
├── pending/             → Waiting to start
├── completed/           → Finished successfully
├── blocked/             → Waiting on dependencies
└── archived/            → Historical record
```

### Actor Task Views

Each actor gets a **view** of their tasks (not primary storage):

```
/channels/{channel_id}/actors/{actor_id}/tasks/
├── assigned/            → Tasks assigned to this actor
├── watching/            → Tasks this actor is monitoring
└── completed/           → Tasks this actor completed
```

These are **references** to central tasks, not copies.

## 📋 TASK SCHEMA

Each task is a Markdown file with YAML frontmatter:

```yaml
---
task_id: CH0-20260225-001
channel_id: 0
owner_actor_id: 10000
assigned_to:
  - 10000
  - 1000
status: active
priority: critical
created_utc: "2026-02-25T08:30:00Z"
depends_on:
  - CH0-20260225-000
blocks:
  - CH0-20260225-002
task_type: database_operation
estimated_duration: "30 minutes"
---

# Task: [Title]

## Objective
[What needs to be done]

## Context
[Why this is needed]

## Steps
[How to do it]

## Success Criteria
[How to know it's done]

## Risks
[What could go wrong]

## Notes
[Additional information]
```

## 🎭 ROLE SCHEMA

Each role is a Markdown file with YAML frontmatter:

```yaml
---
role_id: system_admin
channel_id: 0
authority_level: root
granted_by: 10000
derived_from:
  - "system_architecture"
  - "database_governance"
permissions:
  - drop_tables
  - run_install
  - seed_registry
assigned_to:
  - 10000
  - 1
created_utc: "2026-02-25T09:00:00Z"
---

# Role: [Name]

## Authority
[Level and scope]

## Description
[What this role does]

## Permissions
[What this role can do]

## Assigned Actors
[Who has this role]

## Responsibilities
[What this role is responsible for]

## Constraints
[What this role cannot do]
```

## 🔄 WORKFLOW

### For IDE Agents (Offline Mode)

When the database is offline, agents must:

1. **Read Roles**
   - Location: `/channels/{id}/roles/`
   - Question: "Who am I? What can I do?"

2. **Read Active Tasks**
   - Location: `/channels/{id}/tasks/active/`
   - Question: "What is happening?"

3. **Read Assigned Tasks**
   - Location: `/channels/{id}/actors/{me}/tasks/assigned/`
   - Question: "What do I do next?"

4. **Update Task Status**
   - Location: `/channels/{id}/tasks/{status}/`
   - Action: Move task file to appropriate status directory

5. **Create New Tasks** (if authorized)
   - Location: `/channels/{id}/tasks/pending/`
   - Action: Create new task file with proper schema

### For Human Operators

1. **Review Dashboard**
   - Read: `/channels/{id}/tasks/active/`
   - See all active work

2. **Assign Tasks**
   - Edit task file `assigned_to` field
   - Update actor task views

3. **Monitor Progress**
   - Check task status directories
   - Review completed tasks

4. **Resolve Blocks**
   - Identify blocked tasks
   - Remove blockers
   - Move to active

## 🎯 CURRENT STATE (4.0.45)

### Channel 0 (System)

**Tasks Created:**
- ✅ `db_reset_and_install.md` (CRITICAL, assigned to 10000)
- ✅ `broadcast_normalization.md` (HIGH, assigned to 1000, 1001)
- ✅ `registry_lock.md` (HIGH, assigned to 1, 10000)
- ✅ `installer_integration.md` (MEDIUM, assigned to 10000)

**Roles Created:**
- ✅ `system_admin.md` (assigned to 10000, 1)
- ✅ `installer.md` (assigned to 10000)
- ✅ `auditor.md` (assigned to 1000, 1001, 1004)
- ✅ `registry_steward.md` (assigned to 1, 10000)
- ✅ `communications_lead.md` (assigned to 10000, 1, 1004)

**Actor Task Views:**
- ✅ Captain (10000): 3 assigned tasks
- ✅ Captain WOLFIE (1): 1 assigned task
- ✅ Kiro IDE (1000): 1 assigned task
- ✅ Windsurf IDE (1001): 1 assigned task

### Channel 42 (Development)

**Status:** Directories created, no tasks or roles yet

## 🔐 PRINCIPLES

### 1. Central Task Storage

Tasks live in `/channels/{id}/tasks/` (shared state), NOT inside actor folders.

**Why:** Prevents conflicts, enables global view, supports dependency graphs.

### 2. Actor Task Views

Actor task directories are **views** (references), not primary storage.

**Why:** Actors need to see their queue without duplicating state.

### 3. Roles Derived from Directives

Roles are formalized versions of directives.

**Why:** Directives define doctrine, roles define executable authority.

### 4. No Database Dependency

All coordination happens via filesystem.

**Why:** System must function while database is offline.

### 5. No Runtime Magic

All state is explicit in files.

**Why:** Humans and agents must be able to read and understand state.

## 🚀 FUTURE: DATABASE ONLINE

When the database comes back online, tasks and roles import cleanly into:

- `lupo_tasks` table
- `lupo_roles` table
- `lupo_permissions` table
- `lupo_actor_roles` table

The filesystem becomes the **design** for the database schema.

## 📊 BENEFITS

### For Agents
- ✅ Self-coordination without database
- ✅ Clear task assignments
- ✅ Explicit permissions
- ✅ Dependency tracking

### For Humans
- ✅ Global view of all work
- ✅ Easy task assignment
- ✅ Progress monitoring
- ✅ Conflict resolution

### For System
- ✅ No database dependency
- ✅ Audit trail
- ✅ Version control friendly
- ✅ Multi-agent coordination

## ⚠️ CONSTRAINTS

### What This Is NOT

- ❌ Not a replacement for database
- ❌ Not a workflow engine
- ❌ Not a project management tool
- ❌ Not a ticketing system

### What This IS

- ✅ Offline coordination layer
- ✅ Filesystem-based governance
- ✅ Multi-agent task management
- ✅ Role-based access control

## 🎓 EXAMPLES

### Example 1: Agent Reads Tasks

```bash
# Kiro IDE (1000) wants to know what to do
cd /channels/0/actors/1000/tasks/assigned/
cat README.md
# See: broadcast_normalization.md

# Read the actual task
cat ../../../../tasks/active/broadcast_normalization.md
# Now Kiro knows what to do
```

### Example 2: Agent Updates Task

```bash
# Windsurf IDE (1001) completes broadcast normalization
cd /channels/0/tasks/
mv active/broadcast_normalization.md completed/

# Update task status in file
# status: completed
# completed_utc: "2026-02-25T10:00:00Z"
# completed_by: 1001
```

### Example 3: Human Assigns Task

```bash
# Captain (10000) assigns new task to Warp IDE (1004)
cd /channels/0/tasks/pending/
# Create new task file
# assigned_to: [1004]

# Move to active
mv pending/new_task.md active/

# Update actor view
cd /channels/0/actors/1004/tasks/assigned/
# Add reference to new task
```

## 📚 REFERENCES

- `channels/0/tasks/` - Central task storage
- `channels/0/roles/` - Role definitions
- `channels/0/actors/*/tasks/` - Actor task views
- `AGENTS.md` - Agent coordination rules
- `CONTRIBUTING.md` - Multi-agent workflow

## 🎯 NEXT STEPS

1. **Human:** Execute `db_reset_and_install.md` task
2. **Agents:** Read assigned tasks and begin work
3. **All:** Update task status as work progresses
4. **System:** Monitor for blocked tasks and resolve

---

**Offline governance is now operational. All agents may proceed with assigned tasks.**

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "channels/0/tasks/",
    "channels/0/roles/",
    "AGENTS.md"
  ],
  "implements": "offline_governance_model",
  "depends_on": "channel_scoped_workspaces",
  "includes": "task_schema,role_schema",
  "version": "4.0.45",
  "last_verified": "20260225",
  "last_verified_by": "warp"
}
FLIP_FOOTER_END -->
