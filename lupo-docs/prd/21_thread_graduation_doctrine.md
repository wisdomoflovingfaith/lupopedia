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
thread_id: 42
purpose: "Discussion topic"
start_date: "20260401"
last_activity: "20260401"
status: "active"  # active, concluded, archived
resolution: "prd/21_thread_graduation_doctrine.md"  # if concluded
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

---

**Status**: ACTIVE
**Constitutional Adherence**: FULL
