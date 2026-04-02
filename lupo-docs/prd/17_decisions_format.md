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
  purpose: "Canonical format specification for decision thread files in decisions/ folders"
  status: "approved"
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
    - to: "lupo-docs/versions/4.0.93/decisions/"
      type: references
      weight: 1.0
      reason: "Example implementation of this format (folder with threaded decision files)"
    - to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      type: references
      weight: 1.0
      reason: "Header format with channel_id/thread_id/context_id"
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
    - "Update LUPOPEDIA_HEADERS documentation with context_id"
    - "Ensure all decision thread files follow this format"
---

# PRD: Decision Thread Format Specification

## Overview


This PRD defines the canonical format for documenting architectural decisions, questions, answers, and action items for a given Lupopedia version.

---
## LILITH Analysis: Separating Decisions, Questions, Answers, Comments

**As of April 2026, the canonical and only supported format is the multi-folder threaded system.**

---
## LILITH Analysis: Using `lupopedia.edges` to Link Questions to Answers

**Canonical Q&A and Relationship Linking: Use `lupopedia.edges`**

All relationships between questions, answers, decisions, and comments **must** be expressed using the `lupopedia.edges` YAML block in each file. Do not use manual cross-reference fields or custom YAML keys for linking.

### How to Link

- In a question file, add an outbound edge to its answer(s):

```yaml
lupopedia.edges:
  outbound_edges:
    - to: "../answers/20260402_130000_ANSWER_header_format.md"
      type: "has_answer"
      weight: 1.0
      reason: "This question was answered by LILITH"
```

- In the answer file, add an outbound edge back to the question:

```yaml
lupopedia.edges:
  outbound_edges:
    - to: "../questions/20260402_120000_QUESTION_header_format.md"
      type: "answers"
      weight: 1.0
      reason: "This answers the question about header format"
```

### Canonical Edge Types for Q&A
| Edge Type | Direction | Meaning |
|-----------|-----------|---------|
| `has_answer` | Question → Answer | This question has this answer |
| `answers` | Answer → Question | This answers that question |
| `related_question` | Question → Question | Related/similar question |
| `clarifies` | Answer → Answer | This answer clarifies another |
| `supersedes` | Answer → Answer | This answer replaces an older one |

### Benefits
| Aspect | Manual Link | `lupopedia.edges` |
|--------|-------------|-------------------|
| **Queryable** | No | Yes (via `lupo_edges` table) |
| **Bidirectional** | Manual | Automatic (import both directions) |
| **Validation** | None | Validator can check both exist |
| **Weight/confidence** | No | Yes (`weight`, `semantic_weight`) |
| **Reason tracking** | No | Yes (`reason`, `flare_reason`) |
| **Agent discoverable** | Grep files | Query database |

### LILITH Sign-off
Using `lupopedia.edges` is the canonical solution for all Q&A and related relationships. No new syntax is required. Validators and agents must use these edges for linking, querying, and UI integration.

### Canonical Multi-Folder Structure (Required)

For any context (version, implementation, channel, agent), the following subfolders must be used:

```
<context>/
├── decisions/
│   ├── THREAD_INDEX.md
│   └── YYYYMMDD_HHIISS_DECISION_title.md
├── questions/
│   ├── THREAD_INDEX.md
│   └── YYYYMMDD_HHIISS_QUESTION_title.md
├── answers/
│   ├── THREAD_INDEX.md
│   └── YYYYMMDD_HHIISS_ANSWER_title.md
└── comments/
  ├── THREAD_INDEX.md
  └── YYYYMMDD_HHIISS_COMMENT_title.md
```

- Each folder contains only its type (decisions, questions, answers, comments).
- Each folder must have its own `THREAD_INDEX.md` listing all threads.
- All thread files must use the naming convention: `YYYYMMDD_HHIISS_TYPE_title.md` (UTC timestamp, type, lowercase/underscored title).
- All thread files must include a LUPOPEDIA HEADERS block.
- No new monolithic `decisions.md` files may be created; all new content must use this folder structure.

#### Where This Applies
| Location | Would Have |
|----------|------------|
| `lupo-docs/versions/{version}/` | `decisions/`, `questions/`, `answers/`, `comments/` |
| `lupo-docs/implementations/{id}_{slug}/` | `decisions/`, `questions/`, `answers/`, `comments/` |
| `lupo-channels/{id}/` | `decisions/`, `questions/`, `answers/`, `comments/` |
| `lupo-agents/{agent_key}/` | `decisions/`, `questions/`, `answers/`, `comments/` |

#### Benefits
| Aspect | Current | Proposed |
|--------|---------|----------|
| **Discoverability** | Scan filenames for TYPE | Look in the right folder |
| **Querying** | Parse TYPE from filename | Filter by folder path |
| **Validation** | Check TYPE matches content | Folder implies type |
| **Agent routing** | Read file to know type | Know type from location |
| **UI integration** | Complex filtering | Simple folder-based views |

#### Migration Path
1. Create the new folder structure for each context.
2. Move existing files to their respective folders by type.
3. Add/refresh `THREAD_INDEX.md` in each folder.
4. Update cross-references (e.g., a question links to its answer in `../answers/`).
5. Gradually migrate old content; all new content must use the new structure.

**LILITH Sign-off:** ✅ Separate folders for decisions, questions, answers, and comments is a cleaner architecture. Implement for new content, migrate old content gradually. Update this PRD to document the structure.

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
- Format: `YYYYMMDD_HHIISS_TYPE_title.md`
  - Example: `20260402_120000_DECISION_header_validator_update.md`
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

Every decision thread file MUST include a LUPOPEDIA HEADERS block with:

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
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260402_120000_DECISION_header_format.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260402_120000_DECISION_header_format.md"
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
  status: "completed"
  purpose: "Architecture and design decisions for Lupopedia 4.0.93"
  tags:
  - "decisions"
  - "adr"
  - "version-4.0.93"
```


## THREAD_INDEX.md (Folder Index)

Each folder (`decisions/`, `questions/`, `answers/`, `comments/`) MUST contain a `THREAD_INDEX.md` file listing all thread files, their status, author, and relevant metadata. This replaces the old summary table from monolithic files.

Example:

```markdown
# Decisions Index

| Filename | Title | Author | Status | Date |
|----------|-------|--------|--------|------|
| 20260402_120000_DECISION_header_format.md | Header Format Decision | LILITH | completed | 2026-04-02 |
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

Each thread file should follow this structure:

```markdown
# [Title]

## Type
[Type from taxonomy]

## Status
[Status appropriate to type]

## Author
[Name] (actor_id [ID]) - [Role]

## Date
YYYY-MM-DD

## Context
[What led to this entry?]

## Question (for Question type)
[What needs to be answered?]

## Options (for Question type)
| Option | Description | Pros | Cons |
|--------|-------------|-----|------|
| A | ... | ... | ... |

## Answer (for Answer type)
[The response to the question]

## Rationale (for Answer type)
[Why this answer was chosen]

## Decision (for Decision type)
[What was decided?]

## Consequences (for Decision type)
[What changed?]

## Content (for Dialog/Warning/Observation)
[Relevant details]

## Resolution (for Dialog/Warning)
[How was this resolved?]

## Implementation Notes (for Answer/Action)
[How to implement]

## Comments
*YYYY-MM-DD [Author]*: [Comment text]

## Parent ID (optional, for Answer type only)
[ID of parent Question, if used. **Note:** Use `lupopedia.edges` for canonical linking; Parent ID is for backward compatibility.]
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
       └── Decisions documented in decision thread files (channel_id, thread_id)

2. Decision matures
   └── Context created in lupo-contexts/ (context_id assigned)
       └── decision thread file header updated with context_id
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
**Canonical Reference**: This decisions/ folder is the single source of truth for decision threads and action items for Lupopedia [version].
```


## Validation Rules

Validators MUST enforce:

1. **Header completeness** - All required header fields present
2. **Filename convention** - All thread files use `YYYYMMDD_HHIISS_TYPE_title.md`
3. **THREAD_INDEX.md** - Present and up to date in each folder
4. **Status values** - Status values match allowed set for type (in header)
5. **Date format** - Dates are YYYY-MM-DD
6. **Thread linkage** - thread_id matches discussion thread
7. **Context linkage** - If context_id present, context file must exist
8. **Edge validation** - All Q&A and related links use `lupopedia.edges` (see PRD 16 for canonical edge format)


## Example Implementation

See `lupo-docs/versions/4.0.93/decisions/` for a complete example.
See [PRD 16](16_lupopedia_headers.md) for canonical `lupopedia.edges` usage and schema.

---

**Status**: ACTIVE
**Constitutional Adherence**: FULL
**Version**: 1.0
