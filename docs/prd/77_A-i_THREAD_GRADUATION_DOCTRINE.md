---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/77_A-i_THREAD_GRADUATION_DOCTRINE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/77_A-i_THREAD_GRADUATION_DOCTRINE.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/77_thread_graduation_doctrine.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/thread-graduation-doctrine
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_77_A-i
  title: 'PRD 77: Thread Graduation Doctrine'
  summary: null
---
# PRD: Thread Graduation Doctrine

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## Overview

Channel threads are for active discussion. When discussion concludes, threads must either:
1. **Formalize** ???????? port findings to canonical PRD or implementation
2. **Archive** ???????? move to `archive/threads/` after inactivity period

## Thread Lifecycle

| Phase | Location | Action | Time Limit |
|-------|----------|--------|------------|
| **Active** | `channels/{federation_node_id}/{channel_key}/{thread_key}/` (PRD 17 markdown threads) and/or legacy `channels/{channel_id}/threads/{thread_id}/` (API-mirrored) | Discussion occurs | Ongoing |
| **Concluded** | Same location | Create `RESOLUTION.md` | At discussion end |
| **Formalized** | `docs/prd/` or `docs/implementations/` | Port canonical content | When findings are definitive |
| **Archived** | `archive/threads/YYYY/MM/{thread_id}/` | Move entire thread | After 30 days inactivity |

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

`scripts/archive_stale_threads.py` runs daily to:
- Identify stale threads
- Move them to `archive/threads/YYYY/MM/{thread_id}/`
- Update `THREAD_MANIFEST.md` with archival timestamp

## Resolution Linking

When a thread concludes and its findings are formalized, the `resolution` field MUST point to the canonical document:

- **PRD**: `prd/21_thread_graduation_doctrine.md`
- **Implementation**: `implementations/channel_chat.md`
- **Doctrine**: `doctrine/CASCADE_FALLBACK_DOCTRINE.md`

Paths are relative to `docs/` root.

## Thread ID Format

| Thread Type | Format | Example | Storage |
|-------------|--------|---------|---------|
| **Filesystem** | Lowercase, hyphens | `antigravity-dir-audit` | `channels/{federation_node_id}/{channel_key}/{thread_key}/` (preferred) or legacy `channels/{channel_id}/threads/{thread_id}/` |
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

1. **`THREAD_MANIFEST.md`** ???????? with purpose and start_date
2. **`README.md`** ???????? initial context for participants
3. **Thread ID** ???????? lowercase, hyphen-separated, descriptive

Example (legacy numeric mirror):
```
channels/42/threads/agent-orchestration-review/
+-- THREAD_MANIFEST.md
+-- README.md
+-- discussion_notes.md
```

Example (active human-readable thread ???????? PRD 17 folders):
```
channels/0/organization/prd_29_project_organization/
+-- README.md
+-- decisions/
+-- questions/
+-- answers/
+-- comments/
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
