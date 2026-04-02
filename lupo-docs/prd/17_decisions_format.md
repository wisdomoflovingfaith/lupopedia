---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260331190000"
  file_path_from_root: "lupo-docs/prd/17_decisions_format.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/17_decisions_format.md"
  last_modified_utc: "20260331190000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-decisions-format"
  context_id: 1001
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit"
  artifact_type: "prd"
  artifact_kind: "specification"
  purpose: "Canonical format specification for decisions.md files in version directories"
  tags:
  - "prd"
  - "decisions"
  - "format"
  - "adr"
  - "governance"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/versions/4.0.93/decisions.md"
      type: references
      weight: 1.0
      reason: "Example implementation of this format"
    - to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      type: references
      weight: 1.0
      reason: "Header format with channel_id/thread_id/context_id"
    - to: "lupo-contexts/4.0.93/decisions_context.md"
      type: references
      weight: 0.9
      reason: "Finalized context for version decisions"
lupopedia.footer:
  last_verified: "20260331190000"
  verified_by:
    identity_type: "agent"
    actor_id: 2
    agent_name_identity: "LILITH"
    department_id_delta: 0
  verified_via:
    type: "direct"
    faucet_slug: "none"
  orchestrator: "lilith:audit"
  next_action:
    - "Create lupo-contexts/4.0.93/decisions_context.md"
    - "Update LUPOPEDIA_HEADERS documentation with context_id"
    - "Ensure all decisions.md files follow this format"
---

# PRD: decisions.md Format Specification

## Overview


This PRD defines the canonical format for documenting architectural decisions, questions, answers, and action items for a given Lupopedia version. **As of version 4.0.93+, the canonical and only supported format is the folder-based threaded decisions system. The legacy single-file `decisions.md` approach is deprecated and must not be used for new work.**

## Canonical Decisions Folder System (Required)

All decisions for a version **must** be stored in a `decisions/` folder under the version directory:

```
lupo-docs/versions/
└── <version>/
  └── decisions/
    ├── THREAD_INDEX.md     # Required: index of all decision threads
    └── YYYYMMDD_HHIISS_TYPE_STATUS_TITLE.md  # Individual thread files
```

**Monolithic `decisions.md` files are forbidden for new versions.**

### THREAD_INDEX.md (Required)
Every `decisions/` folder must contain a `THREAD_INDEX.md` file. This file lists all decision threads, their status, and links to each thread file. It serves as the authoritative index for the folder.

### Thread File Naming Convention
- Format: `YYYYMMDD_HHIISS_TYPE_STATUS_TITLE.md`
  - Example: `20260402_120000_DECISION_completed_header_validator_update.md`
- Each file documents a single decision, question, answer, dialog, or action item thread.
- All files must use UTC timestamps and lowercase, underscore-separated titles.

### Migration and Legacy Files
- If migrating from a legacy `decisions.md`, parse each entry into its own thread file and update `THREAD_INDEX.md` accordingly.
- The old file may be preserved as `old_decisions.md` for reference, but must not be updated.

### Validation Rules (Folder System)
1. `decisions/` folder must exist for every version.
2. `THREAD_INDEX.md` must be present and up to date.
3. All thread files must follow the naming convention and include LUPOPEDIA HEADERS.
4. No new `decisions.md` files may be created; all new decisions must be threads.

### Rationale
This system enables:
- Threaded, timestamped, and atomic decision records
- Parallel work by multiple agents
- Channel/thread provenance and auditability
- Elimination of merge conflicts and monolithic file bottlenecks

**Reference:** See [README.md](../README.md), [LUPOPEDIA_HEADERS/README.md](../doctrine/LUPOPEDIA_HEADERS/README.md), and [LILITH directive 20260331] for constitutional requirements.

## Location


### Legacy Location (Deprecated)
The old single-file format:
```
lupo-docs/versions/
└── <version>/
  └── decisions.md
```
is deprecated and must not be used for new work. Only the folder-based system is canonical.

### Thread File Naming Conventions

#### Filesystem Threads (IDE Development)
- **Format**: `YYYYMMDD_HHIISS_TYPE_STATUS_TITLE.md`
- **Example**: `20260402_120000_DECISION_completed_header_validator_update.md`
- **Used for**: IDE agent development, local documentation work
- **Location**: `lupo-docs/versions/<version>/decisions/`

#### Database Threads (Web Application)
- **Format**: Numeric auto-increment ID (e.g., 1038)
- **Example**: Thread ID 1038 in `lupo_dialog_threads` table
- **Used for**: Web-based discussions, chat interfaces
- **Storage**: Database table with metadata

### Migration Strategy

When transitioning from consolidated `decisions.md` to individual thread files:

1. **Parse decision entries** from consolidated file
2. **Create individual files** using filesystem naming convention
3. **Update THREAD_INDEX.md** to reference all threads
4. **Preserve original** as `old_decisions.md` for reference

## Header Requirements

Every `decisions.md` file MUST include a LUPOPEDIA HEADERS block with:

| Field | Required | Description |
|-------|----------|-------------|
| `header_format_version` | Yes | Always 2 |
| `lupopedia.schema` | Yes | "doctrine" |
| `when_updated` | Yes | UTC YYYYMMDDHHIISS |
| `file_path_from_root` | Yes | Full path from repo root |
| `web_path` | Yes | Full web path with /lupopedia/ prefix |
| `last_modified_utc` | Yes | UTC YYYYMMDDHHIISS |
| `federation_node_id` | Yes | 0 (core) |
| `channel_id` | Yes | Channel where decisions were discussed (e.g., 42) |
| `thread_id` | Yes | Thread where discussions occurred |
| `context_id` | Optional | ID of context in lupo-contexts/ if finalized |
| `actor_id` | Yes | Primary author/maintainer |
| `actor_name` | Yes | Human-readable name |
| `delegation_chain` | Yes | e.g., "lilith:audit" |
| `artifact_type` | Yes | "doctrine" |
| `artifact_kind` | Yes | "decisions" |
| `purpose` | Yes | One-line purpose |
| `tags` | Yes | ["decisions", "adr", "version-X.X.X"] |

### Header Example

```yaml
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331190000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions.md"
  last_modified_utc: "20260331190000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.93-decisions"
  context_id: 1001
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Architecture and design decisions for Lupopedia 4.0.93"
  tags:
  - "decisions"
  - "adr"
  - "version-4.0.93"
```

## Decision Log Summary

The file MUST begin with a summary table listing all entries:

```markdown
## Decision Log Summary

| ID | Type | Title | Author | Status | Date |
|----|------|-------|--------|--------|------|
| D-01 | Decision | ... | ... | ... | ... |
| Q-01 | Question | ... | ... | Open | ... |
| A-01 | Answer | ... | ... | Completed | ... |
```

## Entry Types

### Type Taxonomy

| Type | ID Prefix | Purpose | Status Options |
|------|-----------|---------|----------------|
| Decision | D-xx | Formal architectural decision | Proposed, Accepted, Completed, Deprecated, Superseded |
| Question | Q-xx | Open question needing answer | Open, Answered, Resolved |
| Answer | A-xx | Response to a Question | N/A (linked to parent) |
| Dialog | DG-xx | Ongoing discussion | Open, Resolved, Closed |
| Action | A-xx | Task to be completed | Pending, In Progress, Completed, Blocked |
| Warning | W-xx | Identified risk or issue | Acknowledged, Mitigated, Resolved |
| Observation | O-xx | Lesson learned | Noted, Integrated |
| Comment | (inline) | Brief note | N/A (attached to parent) |

### Entry Format

Each entry MUST follow this structure:

```markdown
## [ID]: [Title]

### Type
[Type from taxonomy]

### Status
[Status appropriate to type]

### Parent ID (for Answer type only)
[ID of parent Question]

### Author
[Name] (actor_id [ID]) - [Role]

### Date
YYYY-MM-DD

### Context
[What led to this entry?]

### Question (for Question type)
[What needs to be answered?]

### Options (for Question type)
| Option | Description | Pros | Cons |
|--------|-------------|-----|------|
| A | ... | ... | ... |

### Answer (for Answer type)
[The response to the question]

### Rationale (for Answer type)
[Why this answer was chosen]

### Decision (for Decision type)
[What was decided?]

### Consequences (for Decision type)
[What changed?]

### Content (for Dialog/Warning/Observation)
[Relevant details]

### Resolution (for Dialog/Warning)
[How was this resolved?]

### Implementation Notes (for Answer/Action)
[How to implement]

### Comments
*YYYY-MM-DD [Author]*: [Comment text]
```

## Channel vs Thread vs Context

### Distinction

| Field | Purpose | When Used |
|-------|---------|-----------|
| `channel_id` | Discussion location | During initial discussion, brainstorming, debate |
| `thread_id` | Specific discussion thread | During ongoing conversation within a channel |
| `context_id` | Finalized knowledge | After decision is made, when moved to formal context |

### Lifecycle

```
1. Discussion begins in Channel (channel_id)
   └── Specific thread (thread_id)
       └── Decisions documented in decisions.md (channel_id, thread_id)

2. Decision matures
   └── Context created in lupo-contexts/ (context_id assigned)
       └── decisions.md header updated with context_id
           └── Context becomes source of truth for finalized knowledge
```

### Example

```yaml
# During discussion phase
lupopedia.headers:
  channel_id: 42
  thread_id: "version-4.0.93-decisions"
  # context_id not yet assigned

# After finalization
lupopedia.headers:
  channel_id: 42
  thread_id: "version-4.0.93-decisions"
  context_id: 1001  # Now linked to finalized context
```

## Action Items Section

After entries, include an Action Items section:

```markdown
## Action Items

### High Priority (Immediate)

| ID | Action | Owner | Status | Target |
|----|--------|-------|--------|--------|
| A-01 | ... | ... | ... | ... |

### Medium Priority (This Week)

| ID | Action | Owner | Status | Target |
|----|--------|-------|--------|--------|
| ... | ... | ... | ... | ... |

### Low Priority (This Month)

| ID | Action | Owner | Status | Target |
|----|--------|-------|--------|--------|
| ... | ... | ... | ... | ... |

### Completed Actions

| ID | Action | Owner | Completed |
|----|--------|-------|-----------|
| ... | ... | ... | ... |
```

## Session Notes & Observations

Include a section for session notes and key observations:

```markdown
## Session Notes & Observations

### YYYY-MM-DD: [Author]
- [Observation 1]
- [Observation 2]

## Key Lessons Learned

1. [Lesson 1]
2. [Lesson 2]
```

## Footer

Every decisions.md file MUST include a footer with next actions:

```markdown
---

**Next Review**: YYYY-MM-DD
**Canonical Reference**: This file is the single source of truth for decisions and action items for Lupopedia [version].
```

## Validation Rules

Validators MUST enforce:

1. **Header completeness** - All required header fields present
2. **ID uniqueness** - No duplicate IDs across entries
3. **ID format** - IDs follow prefix-number pattern (D-01, Q-01, etc.)
4. **Parent linkage** - Answer entries have valid Parent ID
5. **Status values** - Status values match allowed set for type
6. **Date format** - Dates are YYYY-MM-DD
7. **Thread linkage** - thread_id matches discussion thread
8. **Context linkage** - If context_id present, context file must exist

## Example Implementation

See `lupo-docs/versions/4.0.93/decisions.md` for a complete example.

---

**Status**: ACTIVE
**Constitutional Adherence**: FULL
**Version**: 1.0
