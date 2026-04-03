---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.94"
  when_updated: "20260404210000"
  file_path_from_root: "lupo-docs/versions/4.0.94/session_changelog/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/session_changelog/README.md"
  last_modified_utc: "20260404210000"
  channel_id: 42
  thread_id: "version-4.0.94-session-changelog"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "convention"
  purpose: "Deterministic session-scoped changelog files; no calendar-day aggregation"
  tags:
    - "4.0.94"
    - "changelog"
    - "session"
lupopedia.footer:
  last_verified: "20260404210000"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: session_changelog/README.md — delegation: cursor:root

# Session changelog (4.0.94)

**Binding:** Per [Task Planning Doctrine](../../../doctrine/TASK_PLANNING_DOCTRINE.md) — use **dependency and session identity**, not fuzzy calendar buckets (“per day”). Human-readable dates in prose are allowed; **canonical ordering** uses **BIGINT UTC** `YYYYMMDDHHIISS` in content and **UTC filename timestamps** per [TIMESTAMP doctrine](../../../doctrine/TIMESTAMP_DOCTRINE.md) (filename form `YYYYMMDD_HHIISS`).

## Purpose

- Each IDE/agent **session** may append one **session log file** under this directory.
- **No** required daily rollup; aggregation is **mechanical** (sort by UTC timestamp in filename or in-file fields).

## Filename (new files)

Lowercase, digits, underscores only (repository filename rule):

```text
changelog_<actor_id>_<session_id>_<YYYYMMDD>_<HHIISS>.md
```

Example: `changelog_102_1_20260402_200000.md`

- **`actor_id`:** Canonical `lupo_actors.actor_id` (integer).
- **`session_id`:** Opaque identifier chosen by the orchestrator or tool for that session (integer or slug without spaces; prefer digits).
- **`YYYYMMDD_HHIISS`:** **Real UTC** session start (or first write), same validation as thread artifacts (`HH` 00–23, `II`/`SS` 00–59).

## Required body shape (markdown)

Each file should begin with a small YAML block:

```yaml
agent_id: 102
session_id: 1
start_timestamp_utc: 20260402200000
end_timestamp_utc: 0
parent_session_id: 0
is_deleted: 0
```

- **`start_timestamp_utc` / `end_timestamp_utc`:** BIGINT UTC `YYYYMMDDHHIISS`; `0` = unknown or open session.
- **`parent_session_id`:** Continuation chain; `0` if none.
- **`is_deleted`:** `0` = active log; `1` = superseded or retracted entry (soft-delete mirror of constitutional DB semantics for docs).

Optional list of operations (each line with its own UTC timestamp and action text) may follow.

## Relationship to `CHANGELOG.md`

- **`CHANGELOG.md`** in this version folder remains the **human narrative** release log (merge summaries, directives).
- **`session_changelog/`** holds **deterministic, session-keyed** traces for multi-agent forensics.

This output complies with Lupopedia Constitutional Root Rules.
