---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260401000000"
  file_path_from_root: "lupo-docs/prd/21_thread_graduation_doctrine.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/21_thread_graduation_doctrine.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "thread-graduation-doctrine"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "doctrine"
  purpose: "Defines lifecycle of channel threads: active → conclusion → formalization → archival"
  tags:
  - "prd"
  - "threads"
  - "lifecycle"
  - "governance"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
---

# PRD: Thread Graduation Doctrine

## Overview

Channel threads are for active discussion. When discussion concludes, threads must either:
1. **Formalize** — port findings to canonical PRD or implementation
2. **Archive** — move to `lupo-archive/threads/` after inactivity period

## Thread Lifecycle

| Phase | Location | Action | Time Limit |
|-------|----------|--------|------------|
| **Active** | `lupo-channels/{id}/threads/{thread_id}/` | Discussion occurs | Ongoing |
| **Concluded** | Same location | Create `RESOLUTION.md` | At discussion end |
| **Formalized** | `lupo-docs/prd/` or `lupo-docs/implementations/` | Port canonical content | When findings are definitive |
| **Archived** | `lupo-archive/threads/YYYY/MM/{thread_id}/` | Move entire thread | After 30 days inactivity |

## Thread Manifest

Every active thread MUST contain a `THREAD_MANIFEST.md`:

```yaml
---
thread_id: "antigravity-dir-audit"
purpose: "Discussion topic"
start_date: "20260401"
last_activity: "20260401"
status: "active"           # active, concluded, archived, legacy, orphaned
resolution: "prd/21_thread_graduation_doctrine.md"  # if concluded/formalized
archived_date: ""          # populated on archival
archived_by: ""            # script name or actor
---
```

## Archival Criteria

A thread is eligible for archival if:
1. `status` in manifest is `concluded`, OR
2. No activity for 30 days (based on `last_activity` or file modification time)

## Cleanup Script

`lupo-scripts/archive_stale_threads.py` runs daily to:
- Identify stale threads
- Move them to `lupo-archive/threads/YYYY/MM/{thread_id}/`
- Update `THREAD_MANIFEST.md` with archival timestamp

## Resolution Linking

When a thread concludes and its findings are formalized, the `resolution` field MUST point to the canonical document:

- **PRD**: `prd/21_thread_graduation_doctrine.md`
- **Implementation**: `implementations/channel_chat.md`
- **Doctrine**: `doctrine/CASCADE_FALLBACK_DOCTRINE.md`

Paths are relative to `lupo-docs/` root.

## Thread ID Format

| Thread Type | Format | Example | Storage |
|-------------|--------|---------|---------|
| **Filesystem** | Lowercase, hyphens | `antigravity-dir-audit` | `lupo-channels/{id}/threads/` |
| **Database** | Numeric (auto-increment) | `1038` | `lupo_dialog_threads` |

**Filesystem threads** are for structured discussion with Markdown artifacts.  
**Database threads** are for high-volume message streams (via `lupo_dialog_messages`).

**Important:** The `archive_stale_threads.py` script skips numeric threads (database-backed). They have their own lifecycle in MySQL.

## Orphaned Thread Handling

If a thread directory exists without a `THREAD_MANIFEST.md`:

1. **Bootstrap**: `bootstrap_thread_manifests.py` creates manifest with `status: "legacy"`
2. **Archival**: If still missing after 30 days, manifest is created with `status: "orphaned"` before archiving
3. **Review**: Orphaned threads should be reviewed to determine if content needs preservation

**Orphaned status** indicates a thread that was never properly initialized. It may contain valuable content that needs manual triage.

## New Thread Creation

When creating a new filesystem thread, include:

1. **`THREAD_MANIFEST.md`** — with purpose and start_date
2. **`README.md`** — initial context for participants
3. **Thread ID** — lowercase, hyphen-separated, descriptive

Example:
```
lupo-channels/42/threads/agent-orchestration-review/
├── THREAD_MANIFEST.md
├── README.md
└── discussion_notes.md
```

**Template for `README.md`:**
```markdown
# Thread: [Topic]

## Purpose
[What is this discussion about?]

## Participants
- [Actor/Agent names]

## Goals
- [ ] Goal 1
- [ ] Goal 2

## Related
- [Links to relevant PRDs, issues, etc.]
```

---

**Status**: ACTIVE
**Constitutional Adherence**: FULL
