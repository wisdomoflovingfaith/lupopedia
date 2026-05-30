---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/30_A_CHANNEL_USAGE_PATTERNS.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/30_A_CHANNEL_USAGE_PATTERNS.md"
  status: active
  when_updated: "20260422232349"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/30_channel_usage_patterns.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/channel-usage-patterns
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_30_A
  title: "PRD: Channel Usage Patterns"
  summary: null
---
# PRD: Channel Usage Patterns

## Overview

This PRD defines clear boundaries between channel content and documentation, ensuring channels focus on real-time coordination while permanent documentation resides in docs.

## Core Principle

**Channels are for coordination, docs are for documentation.**

## Constitutional alignment (Tier 1 vs database)

- **Channel filesystem** (`channels/???????`) is **Tier 1** coordination per [PRD 26](26_five_layer_documentation_architecture.md). **Permanent** specifications and doctrine live under **`docs/`**.
- When markdown is **imported** into **`lupo_contents`**, **`content_id`** is assigned in **application code** (**`import_content.py` ???????? `calculate_content_id()`**), **not** MySQL **`AUTO_INCREMENT`** or vendor sequences ???????? see [PRD 16](16_lupopedia_headers.md).
- **Implementation folder** layout and **`doc_arch_version`** bumps follow **PRD 26** ????6 (fresh alignment / no doc-structure SQL migration before 4.2.0). This PRD does not redefine that versioning; it references PRD 26.
- **Enforcement** below means **validators and scripts** operating on **files and paths** (application layer). **No** database triggers, **no** ORM enforcement of channel policy ???????? constitution forbids DB-side logic for this class of rule.

## Channel Purpose

### ALLOWED Content in Channels

| Content Type | Purpose | Format | Example |
|--------------|---------|--------|---------|
| **Status Reports** | Progress updates on implementations | `STATUS_REPORT_YYYYMMDD_HHIISS.md` (UTC filename per [PRD 17](17_decisions_format.md)) | "Implementation X is 50% complete" |
| **Progress Updates** | Milestone achievements | `PROGRESS_UPDATE_YYYYMMDD_HHIISS.md` | "Completed authentication module" |
| **Critical Coordination** | Urgent cross-agent coordination | `CRITICAL_COORDINATION_YYYYMMDD_HHIISS.md` | "HALT: Database schema conflict" |
| **Agent Handoffs** | Transfer work between agents | `AGENT_HANDOFF_YYYYMMDD_HHIISS.md` | "HERMES to WOLFIE: Decision required" |

### FORBIDDEN Content in Channels

| Forbidden Type | Where It Belongs | Reason |
|----------------|------------------|--------|
| Doctrine documents | docs/doctrine/ | Permanent policy documentation |
| Module specifications | docs/prd/ | Technical specifications |
| Implementation details | docs/implementations/ | Technical implementation docs |
| Reference materials | docs/ | Permanent reference information |
| Technical documentation | docs/ | Technical guides and manuals |

## Message Formats

### Status Report Format
```markdown
# STATUS_REPORT_20260402_160000

## Summary
- Completed: Feature X implementation
- In Progress: Testing phase
- Blocked: Waiting for database schema decision

## Changes Since Last Report
- Added authentication module
- Fixed 3 critical bugs
- Updated API documentation

## Next Steps
- Complete testing when test prerequisites are satisfied (dependency order, not calendar)
- Deploy to staging when deployment prerequisites are satisfied
```

### Critical Coordination Format
```markdown
# CRITICAL_COORDINATION_20260402_161500

## Issue
Database schema conflict between authentication and user management modules

## Impact
- Blocks both implementations
- Requires architectural decision
- Affects deployment timeline

## Required Action
WOLFIE to review and decide on schema approach by EOD

## Related Implementation
- docs/implementations/18_channel_chat_display/
- docs/implementations/25_departments_system/
```

## Channel-Docs Synchronization

### Critical Questions Workflow

1. **Question Posted in Channel**: Agent posts critical question requiring immediate attention
2. **Copy to Implementation**: Question copied to implementation/questions/critical/
3. **Bidirectional Linking**: 
   - Implementation question links to channel message
   - Channel message references implementation question
4. **Resolution in Both Places**: Answer posted in implementation/answers/ and referenced in channel

### Example Synchronization
```yaml
# In implementation question:
lupopedia.edges:
  outbound_edges:
    - to: "../../../../channels/0/development/critical_db_schema/20260402_161500_MESSAGE.md"
      type: synchronized_from
      weight: 1.0
      reason: "Critical question also posted in development channel"

# In channel message:
Related Implementation: docs/implementations/25_departments_system/questions/20260403210005_QUESTION_root_hybrids_followups.md
```

## Channel Usage Guidelines

### When to Use Channels

??????? **Use channels for:**
- Real-time status updates
- Urgent coordination needs
- Agent handoff notifications
- Critical blocker notifications
- Progress broadcasting

### When to Use docs

??????? **Use docs for:**
- Permanent documentation
- Technical specifications
- Implementation details
- Doctrine and policies
- Reference materials

## Channel-Specific Guidelines

### Development Channel (channel_key: development)
- **Purpose**: Core development coordination
- **Expected Content**: Implementation status, technical coordination
- **Frequency**: Daily status reports expected

### Security Channel (channel_key: security)
- **Purpose**: Security and compliance coordination
- **Expected Content**: Security findings, compliance status
- **Frequency**: As needed for security issues

### Governance Channel (channel_key: governance)
- **Purpose**: Rules and policies coordination
- **Expected Content**: Policy updates, governance decisions
- **Frequency**: Weekly governance summaries

### Architecture Channel (channel_key: architecture)
- **Purpose**: System design coordination
- **Expected Content**: Architectural decisions, design reviews
- **Frequency**: As architectural decisions are made

### Organization Channel (channel_key: organization)
- **Purpose**: Repo and docs organization
- **Expected Content**: Structural changes, documentation updates
- **Frequency**: As organizational changes occur

### Semantic Channel (channel_key: semantic)
- **Purpose**: Semantic and knowledge systems
- **Expected Content**: Knowledge graph updates, semantic engine status
- **Frequency**: As semantic system changes occur

### Help Documentation Channel (channel_key: help_documentation)
- **Purpose**: User-facing help content and documentation
- **Expected Content**: Help guides, FAQs, tutorials, user documentation
- **Frequency**: As help content is created or updated
- **Content Organization**: Stored in `content/0/help_documentation/` with channel_key-based folder structure

## Enforcement

### Automated Validation
- Scripts check channel content compliance using **filesystem and path rules** (application layer). **No** DB triggers; **no** vendor-specific SQL for policy enforcement.
- Tooling may use **PyYAML** / shared **`scripts`** dependencies where consistent with [PRD 16](16_lupopedia_headers.md) and [PRD 26](26_five_layer_documentation_architecture.md) ????4.2 ???????? **no** npm/Composer ecosystems for these checks.
- Alerts when forbidden content detected
- Automatic suggestions for docs placement

### Manual Review
- SESHAT conducts weekly content reviews
- ANUBIS monitors for compliance violations
- Warnings issued for non-compliant content

## Migration Path

### For Existing Channel Content
1. **Audit**: Review all existing channel content
2. **Categorize**: Identify content that should move to docs
3. **Migrate**: Move appropriate content to docs
4. **Link**: Leave forwarding references in channels
5. **Clean**: Remove migrated content from channels

### Dependency order (no calendar estimates)

Per [Task Planning Doctrine](../doctrine/TASK_PLANNING_DOCTRINE.md):

| Phase | Prerequisites | Completion criteria |
|-------|-----------------|---------------------|
| **A ???????? Audit** | None | All existing channel trees reviewed; each path categorized as coordination vs misplaced doc |
| **B ???????? Migrate** | Phase A complete | Misplaced content moved to **`docs/`** (or implementation folders) with edges updated |
| **C ???????? Link** | Phase B complete | Forwarding references in channels point at canonical **`docs`** paths |
| **D ???????? Enforce** | Phase C complete | Validators and manual review gates enabled per **Enforcement** above |

Phases **B** and **C** may proceed in parallel for disjoint subtrees once **A** has labeled them.

## Success Metrics

- **Channel Clarity**: 100% of channel content is coordination-focused
- **Documentation Organization**: All permanent docs in docs
- **Agent Compliance**: 95%+ compliance with usage patterns
- **Critical coordination**: Issues triaged with explicit owner **`actor_id`** and evidence timestamps (BIGINT UTC) ???????? no fuzzy SLA in plans (see Task Planning Doctrine)

## Related Artifacts

- [PRD 02 ???????? Channels, Threads, and Discussions](02_channels_discussions.md)
- [PRD 17 ???????? Decisions Format](17_decisions_format.md)
- [PRD 26 ???????? Five-Layer Documentation Architecture](26_five_layer_documentation_architecture.md)
- [PRD 29 ???????? Project Structure](29_project_structure.md)
- [PRD 31 ???????? Implementation Folder Guidelines](31_implementation_folder_guidelines.md)
- [Channel index](../../channels/channel_index.md)

## LILITH audit record (final, 2026-04-03 UTC)

**Note:** Early review paste that reused **PRD 26** section numbers (3.2 / 4.2 / ????6) referred to the **five-layer** PRD, not this file. **PRD 30**-specific review is recorded below.

| Field | Value |
|-------|--------|
| **Verdict** | **APPROVED** ???????? active and constitutionally compliant |
| **Accuracy (reported)** | 96/100 |
| **Constitutional violations** | None reported |

**Resolved / verified (final pass):**

| Item | Result |
|------|--------|
| **`status`** | **`draft`** ???????? **`active`** |
| **Required fields** | **`title`**, **`last_modified_utc`** present |
| **Edges** | [PRD 16](16_lupopedia_headers.md), [PRD 26](26_five_layer_documentation_architecture.md) in **`lupopedia.edges`** |
| **???? Constitutional alignment** | Tier 1 vs import; **`content_id`** app-layer; PRD 26 ????6 pointer; no DB triggers for enforcement |
| **Filenames** | **`HHIISS`** aligned with [PRD 17](17_decisions_format.md) |
| **Migration** | Dependency phases A????????D; no calendar weeks ([Task Planning Doctrine](../doctrine/TASK_PLANNING_DOCTRINE.md)) |
| **Enforcement** | Filesystem / application-layer language explicit |
| **Success metrics** | Traceability (**`actor_id`**, BIGINT UTC), not fuzzy SLA |

**Operational use:** Agents SHOULD use this PRD when placing coordination vs **`docs`** content. Automated checks belong in the **Enforcement** section (validators to be implemented per repo backlog).

---

**Status:** ACTIVE (`lupopedia.headers.status: active`; LILITH final audit stamp UTC **`20260403024421`**)

**Next review:** When PRD 02 / 17 / 29 channel filesystem rules change materially.
